<?php
namespace App\Exports;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HcPhuong implements FromCollection, WithHeadings
{
    public function collection()
    {
        $models = \App\Models\HcPhuong::all()->sortBy('ten_tp')->groupBy('maquan');
        $quans = (new HcQuan())->collection()->pluck('maquan', 'maquan')->map(fn($v) => data_get($models, $v));

        $data = collect();

        foreach ($quans as $quan){
            $s = $quan->sortBy('tenphuong', SORT_NATURAL)->map(fn ($v) => $v->only($this->headings()));

            $w_phuong = $s->filter(fn($v) => Str::of($v['tenphuong'])->startsWith('Phường'));
            $w_xa = $s->filter(fn($v) => Str::of($v['tenphuong'])->startsWith('Xã'));
            $w_other = $s->whereNotIn('maphuong', $w_phuong->pluck('maphuong')->merge($w_xa->pluck('maphuong'))->all());
            $data = $data->merge($w_phuong->merge($w_xa)->merge($w_other));
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'ma_tp',
            'ten_tp',
            'maquan',
            'tenquan',
            'maphuong',
            'tenphuong',
            'cap_hc',
        ];
    }
}
