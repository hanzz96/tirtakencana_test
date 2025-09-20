<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableB extends Model
{
    protected $table = 'table_b';
    protected $primaryKey = 'kode_toko';
    public $timestamps = false;

    protected $fillable = [
        'kode_toko',
        'nominal_transaksi',
    ];
}
