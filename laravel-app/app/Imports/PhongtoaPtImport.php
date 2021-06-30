<?php

namespace App\Imports;

use App\Models\HcPhuong;
use App\Models\HcQuan;
use App\Models\HcTp;
use App\Models\PhongtoaPt;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Larabase\Imports\Import;
use Larabase\Validation\Rules\Latitude;
use Larabase\Validation\Rules\Longitude;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use MStaack\LaravelPostgis\Geometries\Point;

class PhongtoaPtImport extends Import implements ToCollection, WithChunkReading, WithBatchInserts, WithValidation, WithHeadingRow, SkipsEmptyRows
{
      public function collection(Collection $rows)
    {
         $this->validateHeadings($rows, ['ten_dd', 'diachi', 'ma_tp', 'maquan', 'maphuong', 'khupho', 'to_dp', 'ngay_pt', 'ngaygo_pt', 'lat', 'lng']);

        foreach ($rows as $k => $row) {
            $row = $row->toArray();
            $row['ngay_pt'] = to_date($row['ngay_pt']);
            $row['ngaygo_pt'] = to_date($row['ngaygo_pt']);

            $model = new PhongtoaPt();
            $model->fill($row);
            if($row['lat'] && $row['lng']) $model->geom = new Point($row['lat'], $row['lng']);

            $model->save();

            if($row['ngay_pt']){
                $model->lans_pt()->create([
                    'ngay_pt' => $row['ngay_pt'],
                    'ngaygo_pt' => $row['ngaygo_pt'],
                    'order' => 1
                ]);
            }

            $this->count = $k+1;
        }
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
            'ten_dd' => 'unique:phongtoa_pt',
            'diachi' => 'required|unique:phongtoa_pt',
            'ma_tp' => ['required', Rule::in($ma_tps)],
            'maquan' => ['required', Rule::in($maquans)],
            'maphuong' => ['required', Rule::in($maphuongs)],
            'ngay_pt' => 'nullable|string|date_format:d/m/Y',
            'ngaygo_pt' => 'nullable|string|date_format:d/m/Y',
            'lat' => ['nullable', 'required_without:lng', new Latitude],
            'lng' => ['nullable', 'required_without:lat', new Longitude],
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
