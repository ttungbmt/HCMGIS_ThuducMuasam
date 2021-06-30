<?php
namespace App\Models;


use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Laynhiem extends Model
{
    use PostgisTrait;
    use \Larabase\Relations\BelongsTo\HcTp;
    use \Larabase\Relations\BelongsTo\HcQuan;
    use \Larabase\Relations\BelongsTo\HcPhuong;

    protected $table = 'laynhiem';

    protected $fillable = ['layout', 'loaihinh', 'ten', 'uutien', 'diachi', 'to_dp', 'khupho', 'qh', 'px', 'tinh', 'quocgia', 'ghichu'];

    public $timestamps = false;

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

    public function cabenh(){
        return $this->belongsTo(Cabenh::class);
    }
}

