<?php

namespace App\Models;

use Spatie\EloquentSortable\SortableTrait;

class PhongtoaLns extends Model
{
    use SortableTrait;

    protected $table = 'phongtoa_lns';

    protected $fillable = [
        'ngay_pt',
        'ngaygo_pt',
        'ghichu',
        'order',
    ];

    protected $dates = [
        'ngay_pt',
        'ngaygo_pt',
    ];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    public function phongtoaPt(){
        return $this->belongsTo(PhongtoaPt::class);
    }
}
