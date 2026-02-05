<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusChangedForClient extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Order $order,
        private readonly string $newStatus
    ) {
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
        $statusLabel = $this->formatStatus($this->newStatus);

        return [
            'title' => 'Mise a jour de commande',
            'message' => 'Votre commande #' . $this->order->id . ' est maintenant : ' . $statusLabel . '.',
            'action_url' => route('client.orders.show', $this->order),
            'action_text' => 'Voir la commande',
        ];
    }

    private function formatStatus(string $status): string
    {
        return match ($status) {
            'pending' => 'En attente',
            'processing' => 'En traitement',
            'confirmed' => 'Confirmee',
            'preparing' => 'En preparation',
            'ready' => 'Prete',
            'delivered' => 'Livree',
            'cancelled' => 'Annulee',
            default => $status,
        };
    }
}
<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusChangedForClient extends Notification
{
    use Queueable;

    public function __construct(private readonly Order $order, private readonly string $status)
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
        $labels = [
            'pending' => 'En attente',
            'processing' => 'En cours',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
        ];

        $label = $labels[$this->status] ?? $this->status;

        return [
            'title' => 'Statut de commande mis à jour',
            'message' => 'Votre commande #' . $this->order->id . ' est maintenant: ' . $label . '.',
            'action_url' => route('client.orders.show', $this->order),
            'action_text' => 'Voir la commande',
        ];
    }
}
