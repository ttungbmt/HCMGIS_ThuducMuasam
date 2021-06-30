<?php
namespace App\Nova\Actions;

use App\Imports\CabenhImport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Larabase\NovaFields\Html;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportCabenh extends Action
{
    use InteractsWithQueue, Queueable;

    public $standalone = true;

    public function name()
    {
        return __('Import Excel');
    }

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        try {
            $import = new CabenhImport();
            $import->onlySheets('MAIN');
            Excel::import($import, $fields->file);
            $count = session()->get('importCount', 0);
            return Action::message("Đã nhập thành công {$count} đối tượng!");
        } catch (ValidationException $e){
            return Action::modal('response-excel-modal', [
                'errors' => collect($e->failures())->map(fn ($failure) => collect($failure->errors())->map(fn ($message) => __('Dòng :row. :message', ['row' => $failure->row(), 'message' => $message]))->all()),
            ]);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $sampleUrl = Storage::url('imports/cabenhs.xlsx');

        return [
            File::make('File')->rules('required')->help('Tải file mẫu: <a class="no-underline" href="' . $sampleUrl . '" target="_blank">cabenhs.xlsx</a>'),
            Html::make('Some Title')
                ->wrapperClasses('py-4 px-8')
                ->html('<p class="text-sm italic text-gray-500">Vui lòng nhập các cột ' . collect([
                        'ma_tp', 'maquan', 'maphuong'
                    ])->map(fn($v) => "<b>{$v}</b>")->implode(', ') . ' theo danh mục</p>'),
        ];
    }
}
