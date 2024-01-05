<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Client;

class ClientImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Client::create([
                'ClientName' => $row['client_name'],
                'DateStart' => \Carbon\Carbon::parse($row['date_start'])->format('Y-m-d'),
                'DateEnd' => \Carbon\Carbon::parse($row['date_end'])->format('Y-m-d'),
                'Status' => $row['status'],
            ]);
        }
    }
}
