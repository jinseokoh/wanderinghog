<?php

namespace App\Http\Requests;

use App\Enums\DecisionEnum;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PartyDecisionUpdateRequest extends FormRequest
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
            'decision' => [ 'required', Rule::in([
                DecisionEnum::approved()->label,
                DecisionEnum::denied()->label,
            ]) ],
        ];
    }

    public function getDecision()
    {
        return $this->get('decision');
    }
}
