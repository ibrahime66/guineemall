<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductRejectedForVendor extends Notification
{
    use Queueable;

    public function __construct(private readonly Product $product)
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
            'title' => 'Produit desactive',
            'message' => 'Votre produit "' . $this->product->name . '" a ete desactive par un administrateur.',
            'action_url' => route('vendeur.products.show', $this->product),
            'action_text' => 'Voir le produit',
        ];
    }
}
