<?php

namespace App\Notifications;

use App\Models\VendorOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderForVendor extends Notification
{
    use Queueable;

    public function __construct(private readonly VendorOrder $vendorOrder)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nouvelle commande',
            'message' => 'Vous avez recu une nouvelle commande #' . $this->vendorOrder->id . '.',
            'action_url' => route('vendeur.orders.show', $this->vendorOrder),
            'action_text' => 'Voir la commande',
        ];
    }
}
