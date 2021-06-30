<?php

namespace App\Mail;

use App\Models\HcPhuong;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class WarningMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $px;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(HcPhuong $px)
    {
        $this->px = $px;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = DB::table('v_tk_px_cabenh')->where('count', '<>', 0)->orderBy('count', 'DESC')->orderBy('today_count', 'DESC')->get(['maphuong', 'tenphuong', 'count', 'today_count']);
        $model = $data->where('maphuong', $this->px->maphuong)->first();
        return $this->subject('Cảnh báo nguy cơ ca dương tính')->view('mail', compact('data', 'model'));
    }
}
