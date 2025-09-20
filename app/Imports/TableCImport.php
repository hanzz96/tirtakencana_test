<?php

namespace App\Imports;

use App\Models\TableC;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TableCImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new TableC([
            'kode_toko' => $row['kode_toko'],
            'area_sales' => $row['area_sales'],
        ]);
    }
}
