<?php

namespace App\Http\Requests;

use App\Rules\PartyAnswersRule;
use Illuminate\Foundation\Http\FormRequest;

class PartyRegisterRequest extends FormRequest
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
            'friend_id' => ['required', 'integer'],
            'relation_type' => ['required', 'integer'],
            'answers' => ['required', 'array', new PartyAnswersRule()]
        ];
    }

    /**
     * @return int
     */
    public function getFriendId(): int
    {
        return $this->get('friend_id');
    }

    /**
     * @return int
     */
    public function getRelationType(): int
    {
        return $this->get('relation_type');
    }

    /**
     * @return array
     */
    public function getAnswers(): array
    {
        return $this->get('answers', []);
    }
}
