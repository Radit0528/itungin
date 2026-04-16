<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected $fillable = [
        'user_id',
        'nama_target',
        'target_jumlah',
        'jumlah_terkumpul',
        'tanggal_target',
        'status',
        'kategori',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }   
}
