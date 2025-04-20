<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataMahasiswa extends Model
{
    //
       protected $table = 'data_mahasiswa'; // Nama tabel
       protected $fillable = [
       'nim',
       'nama_lengkap',
       'no_hp',
       'token',
       'user_id',
       'no_kamar',
       'nama_mabna',
       'link_ktmm',
       ];
}
