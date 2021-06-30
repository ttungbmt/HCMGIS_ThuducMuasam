<?php
namespace App\Exports;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HcQuan implements FromCollection, WithHeadings
{
    public function collection()
    {
        $models = \App\Models\HcPhuong::all()->sortBy('ten_tp');

        $hcm = $models->where('ma_tp', '79');
        $hn = $models->where('ma_tp', '01');
        $hcs =collect()
            ->merge($hcm)
            ->merge($hn)
            ->merge($models->whereNotIn('ma_tp', ['79', '01']));

        $data = collect();

        foreach ($hcs->groupBy('ma_tp') as $tp){
            $quans = $tp->sortBy('tenquan', SORT_NATURAL)->groupBy('maquan')->map(fn ($v) => $v->first()->only($this->headings()));

            $w_tp = $quans->filter(fn($v) => Str::of($v['tenquan'])->startsWith('Thành phố'));
            $w_quan = $quans->filter(fn($v) => Str::of($v['tenquan'])->startsWith('Quận'));
            $w_huyen = $quans->whereNotIn('maquan', $w_tp->keys()->merge($w_quan->keys())->all());
            $data = $data->merge($w_tp->merge($w_quan)->merge($w_huyen));
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
        ];
    }
}
