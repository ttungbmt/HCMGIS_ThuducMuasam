<?php
namespace App\Models;

use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Cabenh extends Model implements HasMedia
{
    use InteractsWithMedia, PostgisTrait;
    use \Larabase\Relations\BelongsTo\HcTp;
    use \Larabase\Relations\BelongsTo\HcQuan;
    use \Larabase\Relations\BelongsTo\HcPhuong;

    protected $table = 'cabenh';

    protected $fillable = ['hoten', 'ma_qg', 'ma_kv', 'phanloai_cl', 'phanloai_cb', 'ngay_ntt', 'ngay_cb', 'tinh_cb', 'ngaysinh', 'tuoi', 'gioitinh', 'nghenghiep', 'quoctich', 'sohochieu', 'dienthoai', 'email', 'ngay_kp', 'ghichu', 'phongtoa_id', 'dc_loaihinh', 'dc_ten', 'dc_diachi', 'dc_ma_tp', 'dc_maquan', 'dc_maphuong', 'dc_to_dp', 'dc_khupho'];

    protected $dates = ['ngay_ntt', 'ngay_cb', 'ngaysinh'];

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

    public function hanhtrinhs(){
        return $this->hasMany(Hanhtrinh::class, 'cabenh_id');
    }

    public function quanhes(){
        return $this->belongsToMany(Cabenh::class, 'quanhe', 'parent_id', 'id');
    }

    public function invQuanhes(){
        return $this->belongsToMany(Cabenh::class, 'quanhe', 'id', 'parent_id');
    }

    public function quanheParent(){
        return $this->hasOneThrough(Cabenh::class, Quanhe::class, 'id', 'id', 'id', 'parent_id');
    }

    public function quanheLinks(){
        return $this->belongsToMany(Cabenh::class, 'quanhe_link', 'cabenh_id', 'cabenh_link_id');
    }

    public function laynhiems(){
        return $this->hasMany(LayNhiem::class, 'cabenh_id');
    }

    public function dieutri(){
        return $this->hasOne(Dieutri::class, 'cabenh_id');
    }

    public function cachly(){
        return $this->hasOne(Cachly::class, 'cabenh_id');
    }

    public function xetnghiems(){
        return $this->hasMany(Xetnghiem::class, 'cabenh_id');
    }

    public function newPivot(\Illuminate\Database\Eloquent\Model $parent, array $attributes, $table, $exists, $using = null)
    {
        if($using) return $using::fromRawAttributes($parent, $attributes, $table, $exists);

        if($table === with(new Quanhe)->getTable()) return QuanhePivot::fromAttributes($parent, $attributes, $table, $exists);

        return parent::newPivot($parent, $attributes, $table, $exists, $using);
    }

    public function phongtoa(){
        return $this->belongsTo(PhongtoaPt::class, 'phongtoa_id');
    }

    public function tp()
    {
        return $this->belongsTo(\App\Models\HcTp::class, 'dc_ma_tp', 'ma_tp');
    }

    public function quan()
    {
        return $this->belongsTo(\App\Models\HcQuan::class, 'dc_maquan', 'maquan');
    }

    public function phuong(){
        return $this->belongsTo(\App\Models\HcPhuong::class, 'dc_maphuong', 'maphuong');
    }
}
