<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_nation' => 'nullable|string|max:255',
            'treaty' => 'nullable|integer',
            'tribal_council' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'mailing_address' => 'nullable|string|max:255',
            'town' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|numeric',
            'physical_location' => 'nullable|string',
            'language' => 'nullable|string|max:255',
            'on_reserve_membership' => 'nullable|integer',
            'off_reserve_membership' => 'nullable|integer',
            'chief' => 'nullable|string|max:255',
            'councillor' => 'nullable|string',
            'term' => 'nullable|integer',
            'election_date' => 'nullable|date',
            'economic_development_corp' => 'nullable|string|max:255',
            'ec_dev_corp_website' => 'nullable|url|max:255',
            'existing_company' => 'nullable|string',
            'equity_investment' => 'nullable|string',
            'partner' => 'nullable|string',
            'news' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'first_nation.string' => 'The first nation field must be a string.',
            'first_nation.max' => 'The first nation field may not be greater than :max characters.',
            'treaty.integer' => 'The treaty field must be an integer.',
            'tribal_council.string' => 'The tribal council field must be a string.',
            'tribal_council.max' => 'The tribal council field may not be greater than :max characters.',
            'website.url' => 'The website must be a valid URL.',
            'website.max' => 'The website field may not be greater than :max characters.',
            'mailing_address.string' => 'The mailing address field must be a string.',
            'mailing_address.max' => 'The mailing address field may not be greater than :max characters.',
            'town.string' => 'The town field must be a string.',
            'town.max' => 'The town field may not be greater than :max characters.',
            'province.string' => 'The province field must be a string.',
            'province.max' => 'The province field may not be greater than :max characters.',
            'postal_code.string' => 'The postal code field must be a string.',
            'postal_code.max' => 'The postal code field may not be greater than :max characters.',
            'phone.string' => 'The phone field must be a valid number.',
            'physical_location.string' => 'The physical location field must be a string.',
            'language.string' => 'The language field must be a string.',
            'language.max' => 'The language field may not be greater than :max characters.',
            'on_reserve_membership.integer' => 'The on reserve membership field must be an integer.',
            'off_reserve_membership.integer' => 'The off reserve membership field must be an integer.',
            'chief.string' => 'The chief field must be a string.',
            'chief.max' => 'The chief field may not be greater than :max characters.',
            'councillor.string' => 'The councillor field must be a string.',
            'term.integer' => 'The term field must be an integer.',
            'election_date.date' => 'The election date field must be a valid date.',
            'economic_development_corp.string' => 'The economic development corp field must be a string.',
            'economic_development_corp.max' => 'The economic development corp field may not be greater than :max characters.',
            'ec_dev_corp_website.url' => 'The economic development corp website must be a valid URL.',
            'ec_dev_corp_website.max' => 'The economic development corp website field may not be greater than :max characters.',
            'existing_company.string' => 'The existing company field must be a string.',
            'equity_investment.string' => 'The equity investment field must be a string.',
            'partner.string' => 'The partner field must be a string.',
            'news.string' => 'The news field must be a string.',
        ];
    }
}
