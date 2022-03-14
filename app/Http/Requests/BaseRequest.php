<?php


namespace App\Http\Requests;

use App\Helpers\Classes\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    use Response;

    protected $formatMessage = false;
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
     * this function resolve validation error message
     *
     *
     * @param  Validator  $validator
     *
     * @return void
     * @throws \App\Exceptions\PublicException
     * @author karam mustaf
     */
    public function failedValidation(Validator $validator)
    {
        $response = $this->responseError(
            null,
                collect($validator->errors())->values()->collapse(),
                true,
                static::$VALIDATION_ERROR
            );

        throw new HttpResponseException($response);
    }

    /**
     * this function to check if request is update request
     *
     * @return bool
     * @author karam mustaf
     */
    public function isUpdatedRequest()
    {
        return request()->isMethod("PUT") || request()->isMethod("PATCH");
    }

    /**
     * this function to return all required rule for an image
     *
     * @return string
     * @author karam mustaf
     */
    public function imageRule()
    {
        return "{$this->required()}|mimes:jpeg,png,jpg,gif,svg|max:2048";
    }

    /**
     * this function to return all required rule for date request parameter
     *
     * @return string
     * @author karam mustaf
     */
    public function dateRules()
    {

        return "{$this->required()}|after:now";
    }

    /**
     * check if the request is update request then don't verify if the request key is required.
     *
     * @return string
     * @author karam mustaf
     */
    public function required()
    {
        return $this->isUpdatedRequest() ? 'sometimes' : 'required';
    }

}
