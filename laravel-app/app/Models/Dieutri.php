<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dieutri extends Model
{
    use HasFactory;

    protected $table = 'dieutri';

    protected $fillable = ['ngay_vv', 'ngay_rv', 'lydo_vv',  'ghichu', 'noi_dt_id'];

    public $timestamps = false;

    protected $dates = [
        'ngay_vv',
        'ngay_rv',
    ];

    public function cabenh(){
        return $this->belongsTo(Cabenh::class, 'cabenh_id');
    }

    public function diadiem(){
        return $this->belongsTo(Diadiem::class, 'diadiem_id');
    }
}
