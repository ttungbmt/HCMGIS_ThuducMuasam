<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuanheLink extends Model
{
    protected $table = 'quanhe_link';

    public function cabenh(){
        return $this->belongsTo(Cabenh::class, 'cabenh_link_id');
    }
}
