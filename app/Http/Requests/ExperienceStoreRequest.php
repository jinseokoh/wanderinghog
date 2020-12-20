<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceStoreRequest extends FormRequest
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
            'locations' => [ 'required', 'string' ],
            'images' => [ 'required' ],
        ];
    }

    public function getTitle(): string
    {
        return $this->get('title');
    }

    public function getLocations(): array
    {
        $value = trim(str_replace(['#', ',', '.'], ' ', $this->get('locations')));
        return collect(explode(' ', $value))
            ->map(function ($item) {
                return trim($item);
            })
            ->filter()
            ->all();
    }

    public function getKeywords(): array
    {
        $value = trim(str_replace(['#', ',', '.'], ' ', $this->get('keywords')));
        return collect(explode(' ', $value))
            ->map(function ($item) {
                return trim($item);
            })
            ->filter()
            ->toArray();
    }
}
