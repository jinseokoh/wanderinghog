<?php

namespace App\Handlers;

use Google\Cloud\Vision\V1\EntityAnnotation;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class GoogleVisionHandler
{
    /**
     * @param $contents
     * @throws \Google\ApiCore\ValidationException
     * @throws \Throwable
     */
    public function detectSingleFace($contents): void
    {
        /** @var ImageAnnotatorClient $imageAnnotator */
        $imageAnnotator = new ImageAnnotatorClient([
            'projectId' => config('services.google_cloud.project_id'),
            'credentials' => config('services.google_cloud.credentials'),
        ]);

        try {
            $response = $imageAnnotator->faceDetection($contents);
        } catch (\Throwable $exception) {
            // send a report to slack or newrelic
            \Log::info($exception->getMessage());
            throw $exception;
        }

        $faces = $response->getFaceAnnotations();
        $imageAnnotator->close();

        if (count($faces) === 0) {
            throw new \Exception('The photo does not show your face.');
        } else if (count($faces) > 1) {
            throw new \Exception('You should be the only person in the photo.');
        }
        foreach ($faces as $face) {
            $score = $face->getDetectionConfidence();
            if ($score < 0.3) {
                $percentage = sprintf('%.1f%%', $score * 100);
                \Log::info("[Score : {$percentage}]");
                throw new \Exception("Try another photo that clearly shows your face. [Score: {$percentage}]");
            }
        }
    }

    /**
     * @param $contents
     * @return EntityAnnotation
     * @throws \Google\ApiCore\ValidationException
     */
    public function detectText($contents): EntityAnnotation
    {
        /** @var ImageAnnotatorClient $imageAnnotator */
        $imageAnnotator = new ImageAnnotatorClient([
            'projectId' => config('services.google_cloud.project_id'),
            'credentials' => config('services.google_cloud.credentials'),
        ]);

        try {
            $response = $imageAnnotator->textDetection($contents);
        } catch (\Exception $exception) {
            // send a report to slack or new-relic
            \Log::info($exception->getMessage());
            throw $exception;
        }
        $imageAnnotator->close();

        return ($response->getTextAnnotations())[0];
    }
}
