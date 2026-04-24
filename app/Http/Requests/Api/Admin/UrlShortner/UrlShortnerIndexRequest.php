<?php

namespace App\Http\Requests\Api\Admin\UrlShortner;

use App\Http\Requests\ApiCommonRequest;

class UrlShortnerIndexRequest extends ApiCommonRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->roles === 'admin' || $this->user()->roles === 'member';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
