<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des conversations
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Récupérer uniquement les conversations appropriées selon le rôle
        $conversationsQuery = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id);

        // Filtrer selon le rôle pour n'afficher que les conversations client-vendeur
        if ($user->role === 'client') {
            // Client ne voit que les conversations avec les vendeurs
            $conversationsQuery->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->whereHas('receiver', function ($q) {
                          $q->where('role', 'vendeur');
                      })
                      ->orWhere('receiver_id', $user->id)
                      ->whereHas('sender', function ($q) {
                          $q->where('role', 'vendeur');
                      });
            });
        } elseif ($user->role === 'vendeur') {
            // Vendeur ne voit que les conversations avec les clients
            $conversationsQuery->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->whereHas('receiver', function ($q) {
                          $q->where('role', 'client');
                      })
                      ->orWhere('receiver_id', $user->id)
                      ->whereHas('sender', function ($q) {
                          $q->where('role', 'client');
                      });
            });
        }

        $conversations = $conversationsQuery
            ->with(['sender', 'receiver', 'product'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($user) {
                return $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($messages) {
                return $messages->first();
            });

        // Compter les messages non lus
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('chat.index', compact('conversations', 'unreadCount'));
    }

    /**
     * Afficher une conversation spécifique
     */
    public function show(User $user): View
    {
        $currentUser = Auth::user();
        
        // Vérifier si l'utilisateur peut discuter avec cette personne
        if (!$this->canChatWith($currentUser, $user)) {
            abort(403, 'Vous ne pouvez pas discuter avec cet utilisateur');
        }

        // Marquer les messages comme lus
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Récupérer les messages de la conversation
        $messages = Message::between($currentUser->id, $user->id)
            ->with(['sender', 'receiver', 'product', 'order'])
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        return view('chat.show', compact('user', 'messages'));
    }

    /**
     * Envoyer un message
     */
    public function sendMessage(Request $request, User $receiver): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'product_id' => 'nullable|exists:products,id',
            'order_id' => 'nullable|exists:orders,id',
            'type' => 'in:text,image,file',
        ]);

        $sender = Auth::user();

        // Vérifier si l'utilisateur peut discuter avec cette personne
        if (!$this->canChatWith($sender, $receiver)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas discuter avec cet utilisateur'
            ], 403);
        }

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => $request->content,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'type' => $request->type ?? 'text',
        ]);

        // Charger les relations pour la réponse
        $message->load(['sender', 'receiver', 'product', 'order']);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Démarrer une conversation depuis un produit
     */
    public function startFromProduct(Product $product): View
    {
        $currentUser = Auth::user();
        
        // Le client peut discuter avec le vendeur du produit
        if ($currentUser->role === 'client') {
            $vendor = $product->vendor->user;
        } else {
            abort(403, 'Seuls les clients peuvent initier une conversation depuis un produit');
        }

        return redirect()->route('chat.show', $vendor);
    }

    /**
     * Démarrer une conversation depuis une commande
     */
    public function startFromOrder(Order $order): View
    {
        $currentUser = Auth::user();
        
        // Déterminer l'autre participant
        if ($currentUser->role === 'client') {
            $otherUser = $order->vendor->user;
        } elseif ($currentUser->role === 'vendeur') {
            $otherUser = $order->user;
        } else {
            abort(403, 'Action non autorisée');
        }

        return redirect()->route('chat.show', $otherUser);
    }

    /**
     * Obtenir les messages non lus (API pour le temps réel)
     */
    public function getUnreadMessages(): JsonResponse
    {
        $user = Auth::user();
        
        $unreadMessages = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->with(['sender', 'product', 'order'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'unread_count' => $unreadMessages->count(),
            'messages' => $unreadMessages,
        ]);
    }

    /**
     * Marquer un message comme lu
     */
    public function markAsRead(Message $message): JsonResponse
    {
        if ($message->receiver_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $message->markAsRead();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Supprimer une conversation
     */
    public function deleteConversation(User $user): JsonResponse
    {
        $currentUser = Auth::user();
        
        Message::between($currentUser->id, $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conversation supprimée avec succès'
        ]);
    }

    /**
     * Vérifier si deux utilisateurs peuvent discuter
     */
    private function canChatWith(User $currentUser, User $otherUser): bool
    {
        // Un admin peut discuter avec tout le monde
        if ($currentUser->role === 'admin') {
            return true;
        }

        // Un client peut uniquement discuter avec les vendeurs
        if ($currentUser->role === 'client') {
            return $otherUser->role === 'vendeur';
        }

        // Un vendeur peut uniquement discuter avec les clients
        if ($currentUser->role === 'vendeur') {
            return $otherUser->role === 'client';
        }

        return false;
    }

    /**
     * Rechercher des conversations
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $currentUser = Auth::user();
        $query = $request->query;

        $conversationsQuery = Message::where(function ($q) use ($currentUser) {
                $q->where('sender_id', $currentUser->id)
                  ->orWhere('receiver_id', $currentUser->id);
            });

        // Filtrer selon le rôle pour ne rechercher que les conversations client-vendeur
        if ($currentUser->role === 'client') {
            // Client ne recherche que les vendeurs
            $conversationsQuery->where(function ($query) use ($currentUser) {
                $query->where('sender_id', $currentUser->id)
                      ->whereHas('receiver', function ($q) {
                          $q->where('role', 'vendeur');
                      })
                      ->orWhere('receiver_id', $currentUser->id)
                      ->whereHas('sender', function ($q) {
                          $q->where('role', 'vendeur');
                      });
            });
        } elseif ($currentUser->role === 'vendeur') {
            // Vendeur ne recherche que les clients
            $conversationsQuery->where(function ($query) use ($currentUser) {
                $query->where('sender_id', $currentUser->id)
                      ->whereHas('receiver', function ($q) {
                          $q->where('role', 'client');
                      })
                      ->orWhere('receiver_id', $currentUser->id)
                      ->whereHas('sender', function ($q) {
                          $q->where('role', 'client');
                      });
            });
        }

        $conversations = $conversationsQuery
            ->whereHas('sender', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->orWhereHas('receiver', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($message) use ($currentUser) {
                return $message->sender_id === $currentUser->id ? $message->receiver_id : $message->sender_id;
            })
            ->take(10);

        return response()->json([
            'conversations' => $conversations,
        ]);
    }
}
