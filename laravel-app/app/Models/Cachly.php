<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cachly extends Model
{
    use HasFactory;

    protected $table = 'cachly';

    protected $fillable = ['hinhthuc', 'ngay_bd', 'ngay_kt', 'phong', 'ghichu', 'noi_cl_id'];

    protected $dates = ['ngay_bd', 'ngay_kt'];

    public $timestamps = false;

    public function cabenh(){
        return $this->belongsTo(Cabenh::class, 'cabenh_id', 'id');
    }

//    public function cabenh(){
//        return $this->hasOne(Cabenh::class, 'cabenh_id');
//    }

    public function diadiem(){
        return $this->belongsTo(Diadiem::class, 'diadiem_id');
    }
}
