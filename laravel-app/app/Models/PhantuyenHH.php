<?php
namespace App\Models;

use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class PhantuyenHH extends Model
{
    use PostgisTrait;

    use \Larabase\Relations\BelongsTo\HcTp;
    use \Larabase\Relations\BelongsTo\HcQuan;
    use \Larabase\Relations\BelongsTo\HcPhuong;

    protected $table = 'phantuyen_hh';

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

    public function cuahang_cchhs(){
        return $this->belongsToMany(CuahangCCHH::class, 'cuahang_cchh_phtuyen', 'phantuyen_hh_id', 'cuahang_cchh_id');
    }
}
