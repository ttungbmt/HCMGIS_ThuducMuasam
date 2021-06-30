<?php
namespace App\Models;

use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class CachlyPg extends Model
{
    use PostgisTrait;

    use \Larabase\Relations\BelongsTo\HcTp;
    use \Larabase\Relations\BelongsTo\HcQuan;
    use \Larabase\Relations\BelongsTo\HcPhuong;

    protected $table = 'cachly_pg';

    protected $postgisFields = [
        'geom',
    ];

    protected $postgisTypes = [
        'geom' => [
            'type' => 'MultiPolygon',
            'geomtype' => 'geometry',
            'srid' => 4326
        ],
    ];
}
