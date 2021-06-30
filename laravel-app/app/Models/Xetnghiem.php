<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Xetnghiem extends Model
{
    use HasFactory;

    protected $table = 'xetnghiem';

    protected $fillable = ['noi_xn_id', 'noi_lm_id','ngay_lm', 'ngay_kq', 'ketqua_xn', 'lydo_xn'];

    protected $dates = ['ngay_lm', 'ngay_kq'];

    public $timestamps = false;

    public function noi_lm(){
        return $this->belongsTo(Diadiem::class, 'noi_lm_id');
    }

    public function noi_xn(){
        return $this->belongsTo(Diadiem::class, 'noi_xn_id');
    }

    public function cabenh(){
        return $this->belongsTo(Cabenh::class, 'cabenh_id');
    }
}
