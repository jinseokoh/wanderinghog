<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreferenceUpdateRequest extends FormRequest
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
            'ready' => [ 'boolean' ],
            'height' => [ 'array' ],
            'height.min' => [ 'integer' ],
            'height.max' => [ 'integer' ],
            'age' => [ 'array' ],
            'age.min' => [ 'integer' ],
            'age.max' => [ 'integer' ],
            'gender' => [ 'regex:/^[M|F|A]$/i' ],
            'smoking' => [ 'integer' ],
            'drinking' => [ 'integer' ],
            'notifications' => [ 'array' ],
            'notifications.viewed' => [ 'boolean' ],
            'notifications.liked' => [ 'boolean' ],
            'notifications.commented' => [ 'boolean' ],
            'notifications.messaged' => [ 'boolean' ],
            'notifications.matched' => [ 'boolean' ],
        ];
    }
}
