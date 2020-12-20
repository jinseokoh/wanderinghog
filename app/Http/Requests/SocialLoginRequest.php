<?php

namespace App\Http\Requests;

use App\Http\Dtos\SocialUserDto;
use Illuminate\Foundation\Http\FormRequest;

class SocialLoginRequest extends FormRequest
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
            'id' => 'required|string|max:255',
            'email' => 'required|email|max:64',
            'uuid' => 'required|string',
        ];
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->get('id');
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->get('name');
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->get('email');
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->get('uuid');
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        $gender = $this->get('gender');
        if ($gender === 'male') {
            return 'M';
        }
        if ($gender === 'female') {
            return 'F';
        }
        return $gender;
    }

    /**
     * @return string|null
     */
    public function getAge(): ?string
    {
        return $this->get('age');
    }

    /**
     * @return string|null
     */
    public function getDob(): ?string
    {
        return $this->get('dob');
    }

    /**
     * @return string|null
     */
    public function getDevice(): ?string
    {
        return $this->get('device');
    }

    /**
     * @return SocialUserDto
     */
    public function getSocialUserDto(): SocialUserDto
    {
        return new SocialUserDto(
            $this->getId(),
            $this->getName(),
            $this->getEmail(),
            $this->getUuid(),
            $this->getGender(),
            $this->getAge(),
            $this->getDob(),
            $this->getDevice(),
        );
    }
}
