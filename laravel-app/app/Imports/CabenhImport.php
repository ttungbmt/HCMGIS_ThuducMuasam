<?php

namespace App\Imports;

use App\Models\Cabenh;
use App\Models\HcPhuong;
use App\Models\HcQuan;
use App\Models\HcTp;
use App\Models\PhongtoaPt;
use App\Support\Directory;
use App\Support\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Larabase\Imports\Import;
use Larabase\Validation\Rules\Latitude;
use Larabase\Validation\Rules\Longitude;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;
use MStaack\LaravelPostgis\Geometries\Point;


class CabenhImport implements WithMultipleSheets
{
    use WithConditionalSheets;

    public function conditionalSheets(): array
    {
        return [
            'MAIN' => new MainSheetImport(),
        ];
    }


}

class MainSheetImport extends Import implements ToCollection, WithChunkReading, WithBatchInserts, WithValidation, WithHeadingRow, SkipsEmptyRows {
    protected $dates = [
        'ngay_ntt',
        'ngay_cb',
    ];

    protected $dir = [
        'gioitinh' => [
            1 => 'NAM',
            2 => 'NU',
        ]
    ];

     public function collection(Collection $rows)
    {
        // check duplicate headings;
        $this->validateHeadings($rows, ['diachi_luutru']);

        $count = 0;

        foreach ($rows as $k => $row) {
            $row = $row->toArray();
            $fields = collect($this->fields())->mapWithKeys(function ($v, $k) {
                if(is_integer($k)) return [$v => Text::make($v)];
                return [$k => $v];
            });

            $values = $fields->mapWithKeys(function ($f, $k) use($row){
                $value = data_get($row, $k);

                try {
                    $resolveCallback = $f->resolveCallback;

                    if(is_null($value)) return [$f->attribute => null];

                    if($resolveCallback){
                        $value = call_user_func($resolveCallback, $value);
                    }
                } catch (\Exception $e){
                    dd($e);
                }

                return [$f->attribute => $value];
            });

            $model = new Cabenh();
            $model->fill($values->all());
            if($values['lat_luutru'] && $values['lon_luutru']) $model->geom = new Point($values['lat_luutru'], $values['lon_luutru']);
            $model->save();

            $count++;
        }

        session()->flash('importCount', $count);
        Helper::updateCountF0();
    }

    public function fields()
    {
        $formatDate = fn($value) => $value ? Carbon::createFromFormat('d/m/Y', $value) : $value;
        return [
            'phanloai_cb',
            'phanloai_cl',
            'ngay_ntt' => Date::make('ngay_ntt', 'ngay_ntt', $formatDate),
            'ngay_cb' => Date::make('ngay_cb', 'ngay_cb', $formatDate),
            'tinh_cb',
            'ma_qg',
            'ma_kv',
            'hoten',
            'namsinh' => Date::make('ngaysinh', 'ngaysinh', fn ($value) => Carbon::createFromDate($value, 1, 1)),
//            'ngaysinh' => null,
//            'tuoi' => null,
            'gioitinh' => Select::make('gioitinh')->resolveUsing(fn ($value) => data_get(array_flip($this->dir['gioitinh']), $value)),
            'quoctich',
            'nghenghiep',
            'sohochieu',
            'dienthoai',
            'email',
//            'phuongtien' => null,
//            'noidi' => null,
//            'diemdi' => null,
//            'noiquacanh' => null,
//            'noiden' => null,
//            'diemden' => null,
//            'sohieu' => null,
//            'soghe' => null,
//            'thgian_khoihanh' => null,
//            'thgian_den' => null,
//            'loaihinh_khoiphat' => null,
//            'ten_khoiphat' => null,
//            'diachi_khoiphat' => null,
//            'to_dp_khoiphat' => null,
//            'khupho_khoiphat' => null,
//            'qh_khoiphat' => null,
//            'px_khoiphat' => null,
//            'tinh_khoiphat' => null,
//            'quocgia_khoiphat' => null,
            'loaihinh_luutru' => Text::make('dc_loaihinh'),
            'ten_luutru' => Text::make('dc_ten'),
            'diachi_luutru' => Text::make('dc_diachi'),
            'to_dp_luutru' => Text::make('dc_to_dp'),
            'khupho_luutru' => Text::make('dc_khupho'),
            'tinh_luutru' => Text::make('dc_ma_tp'),
            'qh_luutru' => Text::make('dc_maquan'),
            'px_luutru' => Text::make('dc_maphuong'),
            'lat_luutru',
            'lon_luutru',
//            'loaihinh_lamviec' => null,
//            'ten_lamviec' => null,
//            'diachi_lamviec' => null,
//            'to_dp_lamviec' => null,
//            'khupho_lamviec' => null,
//            'qh_lamviec' => null,
//            'px_lamviec' => null,
//            'tinh_lamviec' => null,
//            'noi_dt' => null,
//            'lydo_vv' => null,
//            'ngay_vv' => null,
//            'ngay_rv' => null,
//            'tinhtrang_ht' => null,
//            'noi_cl' => null,
//            'diachi_cl' => null,
//            'to_dp_cl' => null,
//            'khupho_cl' => null,
//            'qh_cl' => null,
//            'px_cl' => null,
//            'lat_cl' => null,
//            'lon_cl' => null,
//            'hinhthuc_cl' => null,
//            'ngay_bd_cl' => null,
//            'ngay_kt_cl' => null,
//            'phong_cl' => null,
//            'lydo_xn' => null,
//            'ngay_lm' => null,
//            'noi_lm' => null,
//            'noi_xn' => null,
//            'ngay_kq' => null,
//            'ketqua_xn' => null,
            'yeuto_dt',
            'ghichu',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function rules(): array
    {
        $ma_tps = HcTp::pluck('ma_tp');
        $maquans = HcQuan::pluck('maquan');
        $maphuongs = HcPhuong::pluck('maphuong');

        return [
            'ngay_ntt' => 'nullable|string|date_format:d/m/Y',
            'ngay_cb' => 'nullable|string|date_format:d/m/Y',
            'phanloai_cl' => ['required', Rule::in(Directory::phanloai_cl())],
            'namsinh' => ['nullable', 'integer'],
            'gioitinh' => ['nullable', Rule::in(['NU', 'NAM'])],

            'diachi_luutru' => 'required',
            'tinh_luutru' => ['required', Rule::in($ma_tps)],
            'qh_luutru' => ['required', Rule::in($maquans)],
            'px_luutru' => [Rule::in($maphuongs)],
            'lat_luutru' => ['nullable', 'required_with:lon_luutru', new Latitude],
            'lon_luutru' => ['nullable', 'required_with:lat_luutru', new Longitude],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [

        ];
    }
}

