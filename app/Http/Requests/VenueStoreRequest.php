<?php

namespace App\Http\Requests;

use App\Http\Dtos\VenueStoreDto;
use Illuminate\Foundation\Http\FormRequest;

class VenueStoreRequest extends FormRequest
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
            'place_id' => [ 'required', 'string', 'min:6' ],
            'title' => [ 'required', 'string', 'min:2' ],
            'address' => [ 'required', 'string', 'min:6' ],
        ];
    }

    /**
     * @return string
     */
    public function getPlaceId(): string
    {
        return $this->get('place_id');
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->get('title');
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->get('address');
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->get('description');
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->get('latitude');
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->get('longitude');
    }

    /**
     * @return array|null
     */
    public function getPhotoRefs(): ?array
    {
        return $this->get('photo_refs') ?: [];
    }

    /**
     * @return VenueStoreDto
     */
    public function getVenueStoreDto(): VenueStoreDto
    {
        return new VenueStoreDto(
            $this->getPlaceId(),
            $this->getTitle(),
            $this->getAddress(),
            $this->getDescription(),
            $this->getLatitude(),
            $this->getLongitude(),
            $this->getPhotoRefs()
        );
    }
}
