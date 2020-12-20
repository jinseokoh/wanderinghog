<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self none()
 * @method static self pendingWithoutText()
 * @method static self pendingWithText()
 * @method static self approved()
 * @method static self rejectedPhotoError()
 * @method static self rejectedPhotoUnclear()
 * @method static self rejectedPhotoUnrelated()
 * @method static self rejectedOtherReason()
 *
 * @method static bool isNone(int|string $value = null)
 * @method static bool isPendingWithoutText(int|string $value = null)
 * @method static bool isPendingWithText(int|string $value = null)
 * @method static bool isApproved(int|string $value = null)
 * @method static bool isRejectedPhotoError(int|string $value = null)
 * @method static bool isRejectedPhotoUnclear(int|string $value = null)
 * @method static bool isRejectedPhotoUnrelated(int|string $value = null)
 * @method static bool isRejectedOtherReason(int|string $value = null)
 */
class VerificationEnum extends Enum
{
//    protected static function values(): array
//    {
//        return [
//            'none' => 0,
//            'pendingWithoutText' => 1,
//            'pendingWithText' => 2,
//            'approved' => 3,
//            'rejectedPhotoError' => -1,
//            'rejectedPhotoUnclear' => -2,
//            'rejectedPhotoUnrelated' => -3,
//            'rejectedOtherReason' => -4,
//        ];
//    }
}
