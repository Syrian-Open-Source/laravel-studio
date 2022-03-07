<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class PostRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [$this->required()],
            'description' => [$this->required(), 'min:3'],
            'user_id' => [$this->required(), Rule::exists('users', 'id')],
        ];
    }
}
