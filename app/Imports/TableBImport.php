<?php

namespace App\Imports;

use App\Models\TableB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TableBImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new TableB([
            'kode_toko' => $row['kode_toko'],
            'nominal_transaksi' => $row['nominal_transaksi'],
        ]);
    }
}
