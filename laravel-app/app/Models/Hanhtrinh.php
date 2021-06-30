<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hanhtrinh extends Model
{
    use HasFactory;

    protected $table = 'hanhtrinh';

    protected $fillable = ['phuongtien', 'noidi', 'diemdi', 'noiquacanh', 'noiden', 'diemden', 'sohieu', 'soghe', 'thgian_khoihanh', 'thgian_den', 'phannhom',];

    public $timestamps = false;

    public $groupColumn = 'phannhom';

    protected $dates = ['thgian_khoihanh', 'thgian_den'];
}

