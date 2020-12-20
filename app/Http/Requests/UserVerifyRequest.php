<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class UserVerifyRequest extends FormRequest
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
            'name' => [ 'required', 'string', 'max:24' ],
            'gender' => [ 'required', 'regex:/[M|F|1|2]/' ],
            'dob' => [ 'required', 'date_format:Y-m-d' ],
            'phone' => [ 'required', 'regex:/^[0-9\+\-]+$/' ],
        ];
    }

    public function getName()
    {
        return trim($this->get('name'));
    }

    public function getGender()
    {
        $gender = $this->get('gender');
        return ($gender === 'M' || $gender === '1') ? 'M' : 'F';
    }

    public function getDob()
    {
        return $this->get('dob');
    }

    public function getPhone()
    {
        return $this->get('phone');
    }
}
