<?php

namespace App\Handlers;

use App\Models\DeviceToken;
use App\Models\User;
use Aws\Sns\Exception\SnsException;

/**
 * Implementation to conform the pseudocode as suggested in the following link
 * https://docs.aws.amazon.com/ko_kr/sns/latest/dg/mobile-platform-endpoint.html
 *
 * retrieve the latest device token from the mobile operating system
 *
 * if (the platform endpoint ARN is not stored)
 *   # this is a first-time registration
 *   call create platform endpoint
 *   store the returned platform endpoint ARN
 * endif
 *
 * call get endpoint attributes on the platform endpoint ARN
 *
 * if (while getting the attributes a not-found exception is thrown)
 *   # the platform endpoint was deleted
 *   call create platform endpoint with the latest device token
 *   store the returned platform endpoint ARN
 * else
 *   if (the device token in the endpoint does not match the latest one) or (get endpoint attributes shows the endpoint as disabled)
 *     call set endpoint attributes to set the latest device token and then enable the platform endpoint
 *   endif
 * endif
 *
 * Class AwsSnsPushHandler
 *
 * @package App\Handlers
 */
class AwsSnsPushHandler
{
    private $snsClient;

    public function __construct()
    {
        $this->snsClient = app('aws')->createClient('sns');
    }

    public function send(DeviceToken $token, $title, $message, $count = 0)
    {
        if (!$token->arn || !$token->platform) {
            return;
        }

        $arn = $token->arn;
        $platform = $token->platform;

        try {
             if ($this->checkIfPushIsEnabled($arn)) {
                 $args = ($platform === 'android') ?
                     $this->buildFcmPayload($arn, $title, $message, $count) :
                     $this->buildApnPayload($arn, $title, $message, $count);

                $this->snsClient->publish($args);
             }
        } catch (SnsException $e) {
            \Log::error($e->getMessage());
        }
    }

    /**
     * basically, arn 이 없거나 token 이 잘못되었으면 수정하고, 모두 정상이면 pass!
     *
     * @param User $user
     * @param string $token
     * @param string $platform
     */
    public function upsert(
        User $user,
        string $token,
        string $platform
    ) {
        // $deviceToken = DeviceToken::whereToken($token)->first();
        $deviceToken = $user->deviceToken;

        if (! $deviceToken) { // first timer
            $endpointArn = $this->createApplicationEndpointArn($token, $platform);
            $deviceToken = new DeviceToken();
            $deviceToken->token = $token;
            $deviceToken->platform = $platform;
            $deviceToken->arn = $endpointArn;
            $deviceToken->user_id = $user->id;
            $deviceToken->save();
            return;
        }

        if (! $deviceToken->arn) { // with no arn
            $endpointArn = $this->createApplicationEndpointArn($token, $platform);
            $deviceToken->token = $token;
            $deviceToken->platform = $platform;
            $deviceToken->arn = $endpointArn;
            $deviceToken->save();
            return;
        }

        $endpointArn = $deviceToken->arn;

        try {
            if ($this->checkIfTokenMismatchesAndPushIsDisabled($token, $endpointArn)) { // SNS 에 있긴 있는데 잘못된 정보야
                \Log::info("[UPDATE]");
                $this->updateEndpointAttributes($token, $endpointArn); // SNS 기록 고쳐!
                $deviceToken->token = $token;
                $deviceToken->platform = $platform;
                $deviceToken->arn = $endpointArn;
                $deviceToken->save();
            } else {
                \Log::info("[GOOD]");
            }
        } catch (SnsException $e) { // SNS 에 그런 arn 없어!
            \Log::info("[SNS-EXCEPTION]");
            $endpointArn = $this->createApplicationEndpointArn($token, $platform);
            $deviceToken->token = $token;
            $deviceToken->platform = $platform;
            $deviceToken->arn = $endpointArn;
            $deviceToken->save();
        }
    }

    /**
     * Todo. check if the user has disabled the push notification
     *       check if the current 1 hour caching logic is adequate
     *
     * @param string $endpointArn
     * @return bool
     */
    private function checkIfPushIsEnabled(
        string $endpointArn
    ): bool {
        $cacheKey = $this->getCacheKey($endpointArn);
        return \Cache::remember($cacheKey, 60 * 60, function() use ($endpointArn) {
            $result = $this->snsClient->getEndpointAttributes([
                'EndpointArn' => $endpointArn,
            ]);
            $enabledAttr = $result['Attributes']['Enabled'];

            return ($result !== 'failed' && $enabledAttr !== 'false');
        });
    }

    private function buildFcmPayload(string $arn, $title, $message, $count): array
    {
        $payload = json_encode([
            "notification" => [
                "title" => $title,
                "body" => $message,
                "sound" => 'default'
            ],
            "data" => [
                "type" => "Manual Notification" // You can add your custom contents here
            ]
        ]);
        $message = json_encode([
            "default" => $message,
            "GCM" => $payload
        ]);

        return [
            'TargetArn' => $arn,
            'Message' => $message,
            'MessageStructure' => 'json'
        ];
    }

    private function buildApnPayload(string $arn, $title, $message, $count)
    {
        $payload = json_encode([
            "notification" => [
                "title" => $title,
                "body" => $message,
            ],
        ]);

        $message = json_encode([
            "GCM" => $payload
        ]);

        return [
            'TargetArn' => $arn,
            'Message' => $message,
            'MessageStructure' => 'json',
        ];
    }

    /**
     * @param string $token
     * @param string $endpointArn
     * @return bool
     */
    private function checkIfTokenMismatchesAndPushIsDisabled(
        string $token,
        string $endpointArn
    ): bool {
        $result = $this->snsClient->getEndpointAttributes([
            'EndpointArn' => $endpointArn,
        ]);
        $tokenAttr = $result['Attributes']['Token'];
        $enabledAttr = $result['Attributes']['Enabled'];

        return $tokenAttr !== $token || $enabledAttr !== 'true';
    }

    /**
     * @param string $token
     * @param string $platform
     * @return string|null
     */
    private function createApplicationEndpointArn(
        string $token,
        string $platform = 'android'
    ): ?string {
        $platformApplicationArn = '';
        if ($platform === 'android' || $platform === 'ios') {
            $platformApplicationArn = config('aws.platform_arn');
        }

        try {
            $result = $this->snsClient->createPlatformEndpoint([
                'PlatformApplicationArn' => $platformApplicationArn,
                'Token' => $token,
            ]);
        } catch (SnsException $e) {
            return null;
        }

        return isset($result['EndpointArn']) ? $result['EndpointArn'] : null;
    }

    /**
     * @param string $token
     * @param string $endpointArn
     * @return void
     */
    private function updateEndpointAttributes(
        string $token,
        string $endpointArn
    ): void {
        $this->snsClient->setEndpointAttributes([
            'Attributes' => [
                'Token' => $token,
                'Enabled' => 'true',
            ],
            'EndpointArn' => $endpointArn,
        ]);
    }

    private function getCacheKey(string $arn)
    {
        return "sns-notification-{$arn}";
    }
}
