<?php
namespace App\Models;

use Rennokki\QueryCache\Traits\QueryCacheable;

class Tag extends \Spatie\Tags\Tag
{
    use QueryCacheable;

    public $cacheFor = 30*24*60*60;
}
