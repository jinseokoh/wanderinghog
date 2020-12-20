<?php

namespace App\Http\Requests;

use App\Rules\AppointmentQuestionsRule;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentUpdateRequest extends FormRequest
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
            'friend_id' => [ 'integer' ],
            'relation_type' => [ 'integer' ],
            'venue_id' => [ 'integer' ],
            'theme_type' => [ 'integer' ],
            'title' => [ 'string', 'min:3' ],
            'description' => [ 'string' ],
            'questions' => [ 'array', new AppointmentQuestionsRule() ],
            'expired_at' => [ 'string' ],
            'estimate' => [ 'integer' ],
        ];
    }
}
