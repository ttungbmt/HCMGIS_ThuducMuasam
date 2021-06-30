<?php
namespace App\Models;

use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;
use Spatie\Tags\HasTags;

class Diadiem extends Model implements HasMedia
{
    use SchemalessAttributesTrait;
    use InteractsWithMedia, HasTags, PostgisTrait;

    use \Larabase\Relations\BelongsTo\HcTp;
    use \Larabase\Relations\BelongsTo\HcQuan;
    use \Larabase\Relations\BelongsTo\HcPhuong;

    protected $table = 'diadiem';

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

    protected $schemalessAttributes = [
        'meta',
    ];


    public function scopeWithMeta()
    {
        return $this->meta->modelCast();
    }
}
