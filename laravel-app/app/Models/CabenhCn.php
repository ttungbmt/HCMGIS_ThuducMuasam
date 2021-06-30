<?php

namespace App\Models;

use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;

class CabenhCn extends Model
{
    use PostgisTrait;

    protected $table = 'cabenh_cn';

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
        return $this->belongsTo(Cabenh::class, 'cabenh_id');
    }
}
