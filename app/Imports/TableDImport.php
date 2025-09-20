<?php

namespace App\Imports;

use App\Models\TableD;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TableDImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new TableD([
            'kode_sales' => $row['kode_sales'],
            'nama_sales'    => $row['nama_sales'],
        ]);
    }
}
