<?php
namespace App\Nova;

use ChrisWare\NovaBreadcrumbs\Traits\Breadcrumbs;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;
use Larabase\Nova\Resources\PermissionsAuthTrait;
use Larabase\Nova\Resources\Resource as NovaResource;
use OptimistDigital\NovaDetachedFilters\HasDetachedFilters;
use Titasgailius\SearchRelations\SearchesRelations;

abstract class Resource extends NovaResource
{
    use HasConditionalContainer, SearchesRelations, Breadcrumbs, HasDetachedFilters, PermissionsAuthTrait;

    public static $trafficCop = false;
}
