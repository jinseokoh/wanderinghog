<?php

namespace App\Http\Requests;

use App\Http\Dtos\AppointmentStoreDto;
use App\Rules\AppointmentQuestionsRule;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'friend_id' =>     [ 'integer' ],
            'relation_type' => [ 'integer' ],

            'venue_id' =>      [ 'required', 'integer' ],
            'theme_type' =>    [ 'required', 'integer' ],
            'title' =>         [ 'required', 'string', 'min:3' ],
            'description' =>   [ 'required', 'string' ],
            'questions' =>     [ 'required', 'array', new AppointmentQuestionsRule() ],
            'expired_at' =>    [ 'required', 'string' ],

            'estimate' =>      [ 'integer' ],
        ];
    }

    public function getFriendId()
    {
        return $this->input('friend_id');
    }

    public function getRelationType()
    {
        return $this->input('relation_type');
    }

    public function getVenueId(): int
    {
        return $this->input('venue_id');
    }

    public function getThemeType(): int
    {
        return $this->input('theme_type');
    }

    public function getTitle(): string
    {
        return $this->input('title');
    }

    public function getDescription(): string
    {
        return $this->input('description');
    }

    public function getQuestions(): array
    {
        return $this->input('questions', []);
    }

    public function getExpiredAt(): string
    {
        return $this->input('expired_at');
    }

    public function getEstimate(): int
    {
        return $this->input('estimate');
    }

    public function getImageLink(): ?string
    {
        return $this->input('image_link');
    }

    public function getAppointmentStoreDto(): AppointmentStoreDto
    {
        return new AppointmentStoreDto(
            $this->getFriendId(),
            $this->getRelationType(),
            $this->getVenueId(),
            $this->getThemeType(),
            $this->getTitle(),
            $this->getDescription(),
            $this->getQuestions(),
            $this->getExpiredAt(),
            $this->getEstimate(),
            $this->getImageLink()
        );
    }
}
