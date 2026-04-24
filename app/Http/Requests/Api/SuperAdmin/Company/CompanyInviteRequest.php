<?php

namespace App\Http\Requests\Api\SuperAdmin\Company;

use App\Http\Requests\ApiCommonRequest;

class CompanyInviteRequest extends ApiCommonRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->roles === 'superadmin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'  => 'required',
            'email' => 'required|email|unique:users,email'
        ];
    }
}
