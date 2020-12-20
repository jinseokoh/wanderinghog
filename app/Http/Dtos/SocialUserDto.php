<?php

namespace App\Http\Dtos;

class SocialUserDto
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string|null
     */
    private $uuid;
    /**
     * @var string|null
     */
    private $gender;
    /**
     * @var string|null
     */
    private $age;
    /**
     * @var string|null
     */
    private $dob;
    /**
     * @var string|null
     */
    private ?string $device;

    /**
     * @var string|null
     */

    public function __construct(
        ?string $id,
        ?string $name,
        ?string $email,
        ?string $uuid,
        ?string $gender,
        ?string $age,
        ?string $dob,
        ?string $device
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->uuid = $uuid;
        $this->gender = $gender;
        $this->age = $age;
        $this->dob = $dob;
        $this->device = $device;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
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
    public function getAge(): ?string
    {
        return $this->age;
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
    public function getDevice(): ?string
    {
        return $this->device;
    }
}
