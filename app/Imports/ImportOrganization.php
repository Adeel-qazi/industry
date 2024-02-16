<?php

namespace App\Imports;

use App\Models\Organization;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportOrganization implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (auth()->check()) {
            if (intVal($row[1])) {

                return new Organization([
                    'user_id' => (int) $row[1],
                    'first_nation' => $row[2],
                    'treaty' => $row[3],
                    'tribal_council' => $row[4],
                    'website' => $row[5],
                    'mailing_address' => $row[6],
                    'town' => $row[7],
                    'province' => $row[8],
                    'postal_code' => $row[9],
                    'phone' => $row[10],
                    'physical_location' => $row[11],
                    'language' => $row[12],
                    'on_reserve_membership' => $row[13],
                    'off_reserve_membership' => $row[14],
                    'chief' => $row[15],
                    'councillor' => $row[16],
                    'term' => $row[17],
                    'election_date' => $row[18],
                    'economic_development_corp' => $row[19],
                    'ec_dev_corp_website' => $row[20],
                    'existing_company' => $row[21],
                    'equity_investment' => $row[22],
                    'partner' => $row[23],
                    'news' => $row[24],
                ]);
            }

        }

    }
}
