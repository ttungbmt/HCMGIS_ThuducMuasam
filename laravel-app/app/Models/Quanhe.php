<?php
namespace App\Models;

use Kalnoy\Nestedset\NodeTrait;

class Quanhe extends Model
{
    use NodeTrait;

    protected $table = 'quanhe';

    protected $fillable = ['id', 'parent_id', 'ghichu'];

    public $timestamps = false;

    public function cabenh(){
        return $this->belongsTo(Cabenh::class, 'id');
    }

    public function cabenhLinks(){
        return $this->hasMany(QuanheLink::class, 'cabenh_id');
    }
}
