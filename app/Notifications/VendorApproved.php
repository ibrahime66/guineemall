<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VendorApproved extends Notification
{
    use Queueable;

    public function __construct(private readonly Vendor $vendor)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $shopName = $this->vendor->shop_name ?? 'votre boutique';

        return [
            'title' => 'Boutique approuvée',
            'message' => 'Votre boutique "' . $shopName . '" a été approuvée. Vous pouvez commencer à vendre.',
            'action_url' => route('vendeur.dashboard'),
            'action_text' => 'Accéder au tableau de bord',
        ];
    }
}
