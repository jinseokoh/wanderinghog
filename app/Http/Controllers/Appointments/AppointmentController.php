<?php

namespace App\Http\Controllers\Appointments;

use App\Events\AppointmentViewed;
use App\Events\VenueSelected;
use App\Handlers\AppointmentHandler;
use App\Handlers\MediaHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentStoreRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Http\Resources\AppointmentDetailResource;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Party;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * @param AppointmentHandler $appointmentHandler
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(AppointmentHandler $appointmentHandler)
    {
        $appointments = $appointmentHandler->fetch();

        return AppointmentResource::collection($appointments);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param AppointmentHandler $appointmentHandler
     * @return AppointmentDetailResource
     */
    public function show(
        int $id,
        Request $request,
        AppointmentHandler $appointmentHandler
    ): AppointmentDetailResource {
        $user = $request->user();
        $userId = $user ? $user->id : $request->getClientIp();
        $cacheKey = "appointments-{$id}-{$userId}";

        $appointment = $appointmentHandler->findById($id);

        // asynchronously update the view count
        event(new AppointmentViewed($appointment, $cacheKey));

        return new AppointmentDetailResource($appointment);
    }

    /**
     * @param AppointmentStoreRequest $request
     * @return JsonResponse
     */
    public function store(
        AppointmentStoreRequest $request,
        MediaHandler $mediaHandler
    ): JsonResponse {

        $user = $request->user();
        $uploadedFile = $request->file('image');
        $dto = $request->getAppointmentStoreDto();

        // create appointment model itself
        $appointment = Appointment::create([
            'user_id' => $user->id,
            'venue_id' => $dto->getVenueId(),
            'theme_type' => $dto->getThemeType(),
            'title' => $dto->getTitle(),
            'questions' => $dto->getQuestions(),
            'description' => $dto->getDescription(),
            'estimate' => $dto->getEstimate(),
            'age' => $user->age,
            'expired_at' => $dto->getExpiredAt()
        ]);

        event(new VenueSelected($appointment));

        if ($dto->getFriendId() && $dto->getRelationType()) {
            Party::create([
                'appointment_id' => $appointment->id,
                'user_id' => $user->id,
                'friend_id' => $dto->getFriendId(),
                'relation_type' => $dto->getRelationType(),
                'is_host' => true,
            ]);
        }

        // attach image in case there's any
        if ($uploadedFile) {
            $mediaHandler->saveModelMediaFromUploadedFile($appointment, $uploadedFile);
        } else if ($dto->getImageLink()) {
            $mediaHandler->saveModelMediaFromUrl($appointment, $dto->getImageLink());
        }

        return response()->json([
            'data' => [
                'id' => $appointment->id,
            ]
        ], 201);
    }

    public function update(
        int $id,
        AppointmentUpdateRequest $request,
        MediaHandler $mediaHandler
    ): JsonResponse {

        $user = $request->user();
        /** @var Appointment $appointment */
        $appointment = Appointment::findOrFail($id);
        $uploadedFile = $request->file('image');

        if ($request->input('friend_id') && $request->input('relation_type')) {
            Party::updateOrCreate(
                [
                    'appointment_id' => $appointment->id,
                    'user_id' => $user->id,
                ], [
                    'friend_id' => $request->input('friend_id'),
                    'relation_type' => $request->input('relation_type'),
                    'is_host' => true,
                ]
            );
        }

        $data = [];
        if ($venueId = $request->input('venue_id')) {
            $data['venue_id'] = $venueId;
        }
        if ($themeType = $request->input('theme_type')) {
            $data['theme_type'] = $themeType;
        }
        if ($title = $request->input('title')) {
            $data['title'] = $title;
        }
        if ($description = $request->input('description')) {
            $data['description'] = $description;
        }
        if ($questions = $request->input('questions')) {
            $data['questions'] = $questions;
        }
        if ($expiredAt = $request->input('expired_at')) {
            $data['expired_at'] = $expiredAt;
        }
        if ($estimate = $request->input('estimate')) {
            $data['estimate'] = $estimate;
        }
        if ($data && count($data)) {
            $appointment->update($data);
        }

        // attach image in case there's any
        if ($uploadedFile) {
            $mediaHandler->saveModelMediaFromUploadedFile($appointment, $uploadedFile);
        } else if ($request->input('image_link')) {
            $mediaHandler->saveModelMediaFromUrl($appointment, $request->input('image_link'));
        }

        return response()->json([
            'data' => [
                'id' => $appointment->id,
            ]
        ], 200);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param AppointmentHandler $appointmentHandler
     * @return AppointmentDetailResource
     */
    public function destroy(
        int $id,
        Request $request,
        AppointmentHandler $appointmentHandler
    ): JsonResponse {
        $user = $request->user();
        $appointment = Appointment::findOrFail($id);

        if ($appointment->user_id !== $user->id) {
            return response()->json([
                'error' => 'access denied'
            ], 422);
        }

        $appointment->delete();

        return response()->json([
            'data' => 'deleted'
        ], 200);
    }
}
