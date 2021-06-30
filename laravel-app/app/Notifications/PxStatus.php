<?php

namespace App\Notifications;

use App\Mail\WarningMail;
use App\Models\Cabenh;
use App\Models\HcPhuong;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class PxStatus extends Notification
{
    use Queueable;

    private $px;
    private $count;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     */
    public function __construct(HcPhuong $px, $count)
    {
        $this->px = $px;
        $this->count = $count;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database', 'broadcast', 'mail'
        ];
    }

    public function toMail($notifiable)
    {
        return (new WarningMail($this->px))->to([
            'dhminh.tpthuduc@tphcm.gov.vn',
            'nphung.tpthuduc@tphcm.gov.vn',
            'ttungbmt@gmail.com',
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return \Mirovit\NovaNotifications\Notification::make()
            ->error('Cảnh báo nguy cơ - '.$this->px->tenphuong)
            ->subtitle('Số lượng ca dương tính đã tăng ' . $this->count . '!')
            ->routeDetail('hc-phuongs', $this->px->id)
            ->toArray();
    }
}
