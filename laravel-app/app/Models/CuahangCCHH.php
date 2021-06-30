<?php
namespace App\Models;


use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class CuahangCCHH extends Model
{
    use PostgisTrait;
    use \Larabase\Relations\BelongsTo\HcTp;
    use \Larabase\Relations\BelongsTo\HcQuan;
    use \Larabase\Relations\BelongsTo\HcPhuong;

    protected $table = 'cuahang_cchh';

    protected $postgisFields = [
        'geom',
    ];

    protected $postgisTypes = [
        'geom' => [
            'type' => 'Point',
            'geomtype' => 'geometry',
            'srid' => 4326
        ],
    ];

    public function phantuyens(){
        return $this->belongsToMany(PhantuyenHH::class, 'cuahang_cchh_phtuyen', 'cuahang_cchh_id', 'phantuyen_hh_id');
    }
}
