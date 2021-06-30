<?php

namespace App\Nova\Actions;

use App\Models\Cabenh;
use App\Support\Directory;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportCabenhs extends DownloadExcel implements WithMapping, WithStyles, WithColumnWidths, ShouldAutoSize, WithColumnFormatting,  WithHeadings
{
    protected $increment = 0;

    protected $dates = ['ngay_ntt', 'ngay_cb', 'ngay_vv', 'ngay_rv', 'ngay_bd', 'ngay_kt', 'ngay_lm'];

    public function __construct()
    {
        $this
            ->allFields()
            ->withChunkCount(300)
            ->askForFilename()
            ->askForWriterType();
    }

    public function headings() : array {
        return $this->columns()->keys()->map(fn($heading) => Str::upper($heading))->all();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FEFE00']]],
        ];
    }

    public function columnWidths(): array
    {
        return [
        ];
    }

    public function columnFormats(): array
    {
        $columns = $this->columns()->keys();
        $formats = $columns->filter(fn($field) => in_array($field, $this->dates))->mapWithKeys(fn($value, $key) => [Coordinate::stringFromColumnIndex($key+1) => NumberFormat::FORMAT_DATE_DDMMYYYY]);
        return $formats->all();
    }

    protected function columns()
    {
        $gioitinh = Directory::gioitinh();
        $ketqua_xn = Directory::ketqua_xn();
        $tinhtrang_ht = Directory::tinhtrang_ht();

        return collect([
            'tt' => fn($model) => $this->increment,
            'phanloai_cb',
            'ngay_ntt',
            'ngay_cb',
            'tinh_cb',
            'ma_qg',
            'ma_hcm',
            'hoten',
            'ngaysinh',
            'tuoi',
            'gioitinh' => ['filter' => $gioitinh, 'value' => 'gioitinh'],
            'quoctich',
            'nghenghiep',
            'dienthoai',

            // Hành trình
            'phuongtien' => 'hanhtrinhs.0.phuongtien',
            'noidi' => 'hanhtrinhs.0.noidi',
            'diemdi' => 'hanhtrinhs.0.diemdi',
            'noiquacanh' => 'hanhtrinhs.0.noiquacanh',
            'noiden' => 'hanhtrinhs.0.noiden',
            'diemden' => 'hanhtrinhs.0.diemden',
            'sohieu' => 'hanhtrinhs.0.sohieu',
            'soghe' => 'hanhtrinhs.0.soghe',
            'thgian_khoihanh' => 'hanhtrinhs.0.thgian_khoihanh',
            'thgian_den' => 'hanhtrinhs.0.thgian_den',

            // Lây nhiễm
            'loaihinh_khoiphat' => 'noi_laynhiems.0.loaihinh',
            'ten_khoiphat' => 'noi_laynhiems.0.ten',
            'diachi_khoiphat' => 'noi_laynhiems.0.diachi',
            'to_dp_khoiphat' => 'noi_laynhiems.0.to_dp',
            'khupho_khoiphat' => 'noi_laynhiems.0.khupho',
            'qh_khoiphat' => 'noi_laynhiems.0.qh',
            'px_khoiphat' => 'noi_laynhiems.0.px',
            'tinh_khoiphat' => 'noi_laynhiems.0.tinh',
            'quocgia_khoiphat' => 'noi_laynhiems.0.quocgia_khoiphat',

            'loaihinh_luutru' => 'noi_laynhiems.0.loaihinh',
            'ten_luutru' => 'noi_laynhiems.0.ten',
            'diachi_luutru' => 'noi_laynhiems.0.diachi',
            'to_dp_luutru' => 'noi_laynhiems.0.to_dp',
            'khupho_luutru' => 'noi_laynhiems.0.khupho',
            'qh_luutru' => 'noi_laynhiems.0.qh',
            'px_luutru' => 'noi_laynhiems.0.px',
            'tinh_luutru' => 'noi_laynhiems.0.tinh',

            'loaihinh_lamviec' => 'noi_laynhiems.0.loaihinh',
            'ten_lamviec' => 'noi_laynhiems.0.ten',
            'diachi_lamviec' => 'noi_laynhiems.0.diachi',
            'to_dp_lamviec' => 'noi_laynhiems.0.to_dp',
            'khupho_lamviec' => 'noi_laynhiems.0.khupho',
            'qh_lamviec' => 'noi_laynhiems.0.qh',
            'px_lamviec' => 'noi_laynhiems.0.px',
            'tinh_lamviec' => 'noi_laynhiems.0.tinh',

            // Điều trị
            'noi_dt' => 'dieutri.diadiem.ten',
            'lydo_vv' => 'dieutri.lydo_vv',
            'ngay_vv' => 'dieutri.ngay_vv',
            'ngay_rv' => 'dieutri.ngay_rv',
            'tinhtrang_ht' => ['filter' => $tinhtrang_ht, 'value' => 'dieutri.tinhtrang_ht'],

            // Cách ly
            'noi_cl' => 'cachly.diadiem.ten',
            'hinhthuc_cl' => 'cachly.hinhthuc',
            'ngay_bd_cl' => 'cachly.ngay_bd',
            'ngay_kt_cl' => 'cachly.ngay_kt',
            'phong_cl' => 'cachly.phong',

            // Xét nghiệm
            'lydo_xn' => 'xetnghiems.0.lydo_xn',
            'ngay_lm' => 'xetnghiems.0.ngay_lm',
            'noi_lm' => 'xetnghiems.0.noi_lm.ten',
            'noi_xn' => 'xetnghiems.0.noi_xn.ten',
            'ngay_kq' => 'xetnghiems.0.ngay_kq',
            'ketqua_xn' => ['filter' => $ketqua_xn, 'value' => 'xetnghiems.0.ketqua_xn'],

            'ghichu',
        ])->mapWithKeys(function ($value, $key) {
            if (is_string($key) || is_callable($value)) return [$key => $value];
            return [$value => $value];
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Cabenh::with([
            'noi_laynhiems',
            'hanhtrinhs',
            'cachly',
            'cachly.diadiem',
            'dieutri',
            'dieutri.diadiem',
            'xetnghiems',
            'xetnghiems.noi_lm',
            'xetnghiems.noi_xn'
        ])->get();
    }

    /**
     * @param \App\Models\Cabenh $model
     *
     * @return array
     */
    public function map($model): array
    {
        $this->increment = $this->increment + 1;

        $formatDate = fn($field) => fn($model) => data_get($model, $field) ? Date::dateTimeToExcel(data_get($model, $field)) : null;
        $getValue = fn($field, $category) => fn($model) => !is_null(data_get($model, $field)) ? data_get($category, data_get($model, $field)) : null;

        $dates = $this->dates;

        $data = $this->columns()->map(function ($v, $k) use ($model, $dates, $formatDate, $getValue) {
            if(is_callable($v)) return call_user_func($v, $model);

            $group_ln = collect(['khoiphat', 'luutru', 'lamviec'])->filter(fn($v) => Str::endsWith($k, $v))->first();
            if($group_ln) {
                $relateds = $model->noi_laynhiems->filter(fn($v) => $v[$v->groupColumn] === $group_ln)->values();
                return data_get(['noi_laynhiems' => $relateds], $v);
            }

            if(is_array($v)) return $getValue($v['value'], $v['filter'])($model);
            if(in_array($k, $dates)) return $formatDate($v)($model);

            return data_get($model, $v);
        })->all();

        return $data;
    }
}
