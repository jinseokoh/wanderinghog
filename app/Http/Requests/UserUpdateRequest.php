<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => [ 'string', 'max:24', 'unique:users' ], // costs a coin
            'name' => [ 'string', 'max:24' ],
            'gender' => [ 'regex:/[M|F]/' ],
            'dob' => [ 'date', 'before:' . Carbon::today()->subYears(18)->toDateString() ],
            'locale' => [ 'string' ],
        ];
    }
}
