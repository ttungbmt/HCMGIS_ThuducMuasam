<?php
namespace App\Models;

use Rennokki\QueryCache\Traits\QueryCacheable;

class HcPhuong extends Model
{
    use QueryCacheable;
    use \Larabase\Relations\BelongsTo\HcQuan;

    protected $table = 'hc_phuong';

    public $cacheFor = 30*24*60*60;
}
