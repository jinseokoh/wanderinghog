<?php

namespace App\Http\Dtos;

class UserUpdateDto
{
    /**
     * @var string|null
     */
    private $username;
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string|null
     */
    private $email;
    /**
     * @var string|null
     */
    private $phone;
    /**
     * @var string|null
     */
    private $password;
    /**
     * @var string|null
     */
    private $avatar;
    /**
     * @var string|null
     */
    private $dob;
    /**
     * @var string|null
     */
    private $gender;
    /**
     * @var string|null
     */
    private $locale;
    /**
     * @var bool|null
     */
    private $is_active;

    public function __construct(
        ?string $username,
        ?string $name,
        ?string $email,
        ?string $phone,
        ?string $password,
        ?string $avatar,
        ?string $dob,
        ?string $gender,
        ?string $locale,
        ?bool $is_active
    ) {
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->avatar = $avatar;
        $this->dob = $dob;
        $this->gender = $gender;
        $this->locale = $locale;
        $this->is_active = $is_active;
    }

    /**
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @return string|null
     */
    public function getDob(): ?string
    {
        return $this->dob;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @return bool|null
     */
    public function isActive(): ?bool
    {
        return $this->is_active;
    }
}
