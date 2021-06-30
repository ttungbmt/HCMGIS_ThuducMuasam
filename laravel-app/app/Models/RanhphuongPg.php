<?php
namespace App\Models;

use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class RanhphuongPg extends Model
{
    use PostgisTrait;

    protected $table = 'ranhphuong_pg';

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
