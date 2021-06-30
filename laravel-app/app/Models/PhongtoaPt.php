<?php
namespace App\Models;

use App\Support\Directory;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class PhongtoaPt extends Model
{
    use PostgisTrait;

    use \Larabase\Relations\BelongsTo\HcTp;
    use \Larabase\Relations\BelongsTo\HcQuan;
    use \Larabase\Relations\BelongsTo\HcPhuong;

    protected $table = 'phongtoa_pt';

    protected $fillable = ['ten_dd', 'ma_tp', 'maquan', 'maphuong', 'diachi', 'ghichu', 'to_dp', 'khupho'];

    protected $postgisFields = [
        'geom',
    ];

    protected $dates = [
    ];

    protected $postgisTypes = [
        'geom' => [
            'type' => 'Point',
            'geomtype' => 'geometry',
            'srid' => 4326
        ],
    ];

    public function lans_pt(){
        return $this->hasMany(PhongtoaLns::class);
    }

    public function lan_pt(){
        return $this->hasOne(LanPtCc::class);
    }

    public function cabenhs(){
        return $this->hasMany(Cabenh::class, 'phongtoa_id');
    }

    protected function formatDates($dates){
        return collect($dates)->map(fn($v) => to_date($v, false))->values()->filter()->all();
    }

    public function scopeNgayPt($query, ...$values) {
        return $query->whereHas('lan_pt', fn($q) => $q->whereIn('ngay_pt', $this->formatDates($values)));
    }

    public function scopeNgayPtBetween($query, ...$values) {
        $dates = $this->formatDates($values);
        if(count($dates) === 1) return $query->whereHas('lan_pt', fn($q) => $q->where('ngay_pt', $dates[0]));
        if(count($dates) === 2) return $query->whereHas('lan_pt', fn($q) => $q->whereBetween('ngay_pt', $dates));
        return $query;
    }

    public function scopeNgaygoPtBetween($query, ...$values) {
        $dates = $this->formatDates($values);
        if(count($dates) === 1) return $query->whereHas('lan_pt', fn($q) => $q->where('ngaygo_pt', $dates[0]));
        if(count($dates) === 2) return $query->whereHas('lan_pt', fn($q) => $q->whereBetween('ngaygo_pt', $dates));
        return $query;
    }

    public function scopeNgaygoPt($query, ...$values) {
        return $query->whereHas('lan_pt', fn($q) => $q->whereIn('ngaygo_pt', $this->formatDates($values)));
    }

    public function scopeTinhtrang($query, $value) {
        return $query->whereHas('lan_pt', function ($q) use($value){
            if($value === '1') $q->whereNull('ngaygo_pt');
            if($value === '0') $q->whereNotNull('ngaygo_pt');
        });
    }
}
