<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerStoreRequest extends FormRequest
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
            'question_id' => [ 'required', 'integer' ],
            'answer' => [ 'required', 'string', 'min:4' ],
            'image' => [ 'image' ],
        ];
    }

    /**
     * @return int
     */
    public function getQuestionId(): int
    {
        return $this->get('question_id');
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->get('answer');
    }
}
