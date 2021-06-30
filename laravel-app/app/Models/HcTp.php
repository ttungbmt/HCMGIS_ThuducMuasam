<?php
namespace App\Models;

use Rennokki\QueryCache\Traits\QueryCacheable;

class HcTp extends Model
{
    use QueryCacheable;
    use \Larabase\Relations\HasMany\HcQuan;

    protected $table = 'hc_tp';

    protected $postgisTypes = [
        'geom' => [
            'type' => 'MultiPolygon',
            'geomtype' => 'geometry',
            'srid' => 4326
        ],
    ];

    public $cacheFor = 30*24*60*60;
}
