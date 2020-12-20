<?php

namespace App\Http\Controllers\Users;

use App\Handlers\AppointmentHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentDetailResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AppointmentController extends controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function host(
        int $userId,
        AppointmentHandler $appointmentHandler
    ): AnonymousResourceCollection {
        $appointments = $appointmentHandler
            ->fetchByHostUserId($userId);

        return AppointmentDetailResource::collection($appointments);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function guest(
        int $userId,
        AppointmentHandler $appointmentHandler
    ): AnonymousResourceCollection {
        $appointments = $appointmentHandler
            ->fetchByJoinUserId($userId);

        return AppointmentDetailResource::collection($appointments);
    }
}
