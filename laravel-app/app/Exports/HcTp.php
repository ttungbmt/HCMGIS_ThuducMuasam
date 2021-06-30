<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HcTp implements FromCollection, WithHeadings
{
    public function collection()
    {
        $models = \App\Models\HcPhuong::all()->sortBy('ten_tp')
            ->groupBy('ma_tp')
            ->map(fn($v) => $v->first->all()->only($this->headings()))
            ->values()
            ->sortBy('ten_tp')
        ;

        $hcm = $models->firstWhere('ma_tp', '79');
        $hn = $models->firstWhere('ma_tp', '01');

        return collect()
            ->push($hcm)
            ->push($hn)
            ->merge($models->whereNotIn('ma_tp', ['79', '01']));
    }

    public function headings(): array
    {
        return [
            'ma_tp',
            'ten_tp',
        ];
    }
}
