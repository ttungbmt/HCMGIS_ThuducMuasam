<?php
namespace App\Models;

use Rennokki\QueryCache\Traits\QueryCacheable;

class HcQuan extends Model
{
    use QueryCacheable;
    use \Larabase\Relations\HasMany\HcPhuong;

    protected $table = 'hc_quan';

    public $cacheFor = 30*24*60*60;

}
