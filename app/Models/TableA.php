<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableA extends Model
{
    protected $table = 'table_a';
    protected $primaryKey = 'kode_toko_baru';
    public $timestamps = false; // no created_at / updated_at
    
    protected $fillable = [
        'kode_toko_baru',
        'kode_toko_lama',
    ];
}
