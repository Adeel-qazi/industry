<?php

namespace App\Exports;

use App\Models\Organization;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ExportOrganization implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Organization::all(
            'id',
            'user_id',
            'first_nation',
            'treaty',
            'tribal_council',
            'website',
            'mailing_address',
            'town',
            'province',
            'postal_code',
            'phone',
            'Physical_location',
            'language',
            'on_reserve_membership',
            'off_reserve_membership',
            'chief',
            'councillor',
            'term',
            'election_date',
            'economic_development_corp',
            'ec_dev_corp_website',
            'existing_company',
            'equity_investment',
            'partner',
            'news',
        );

    }


    public function headings(): array
    {

        return [
            "ID",
            'USER ID',
            'First Nation',
            'Treaty',
            'Tribal Council',
            'Website',
            'Mailing Address',
            'Town',
            'Province',
            'Postal Code',
            'Phone',
            'Physical Location',
            'Language',
            'On Reserve Membership',
            'Off Reserve Membership',
            'Chief',
            'Councillor',
            'Term',
            'Election Date',
            'Economic Development Corp',
            'Ec Dev Corp Website',
            'Existing Company',
            'Equity Investment',
            'Partner',
            'News',
        ];

    }
}
