<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Services\Client\OrderService;
use App\Exceptions\OrderException;
use App\Exceptions\StockException;
use App\Exceptions\OrderStatusException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Affiche la liste des commandes du client
     */
    public function index(): View
    {
        $orders = Order::where('user_id', auth()->id())
                      ->with(['user', 'orderItems.product.category', 'orderItems.product.vendor', 'vendorOrders'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return view('client.orders.index', compact('orders'));
    }

    /**
     * Affiche les détails d'une commande
     */
    public function show(Order $order): View
    {
        // Vérifier que la commande appartient au client connecté
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cette commande.');
        }

        $order->load(['orderItems.product.category', 'orderItems.product.vendor', 'vendorOrders.vendor']);

        return view('client.orders.show', compact('order'));
    }

    /**
     * Affiche la page de validation de commande
     */
    public function checkout(): View|RedirectResponse
    {
        $cartItems = Cart::forUser(auth()->id())
                        ->withAvailableProducts()
                        ->with(['product.category', 'product.vendor'])
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')
                           ->with('error', 'Votre panier est vide.');
        }

        // Vérifier que le client a les informations requises
        $canOrder = $this->orderService->canUserOrder(auth()->id());
        if (!$canOrder['can_order']) {
            return redirect()->route('client.profile.edit')
                           ->with('error', $canOrder['message']);
        }

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('client.orders.checkout', compact('cartItems', 'total'));
    }

    /**
     * Crée une nouvelle commande à partir du panier
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $validated = $request->validate([
                'payment_method' => 'nullable|string|max:50',
                'payment_reference' => 'nullable|string|max:100',
            ]);

            $paymentMethod = $validated['payment_method'] ?? null;
            $paymentReference = $validated['payment_reference'] ?? null;

            if (! $paymentReference) {
                $paymentReference = 'PAY-' . strtoupper(Str::random(8));
            }

            $order = $this->orderService->createOrderFromCart(
                auth()->id(),
                $paymentMethod,
                $paymentReference
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('client.orders.show', $order),
                ]);
            }

            return redirect()->route('client.orders.show', $order)
                ->with('success', 'Commande créée avec succès! Votre commande #' . $order->id . ' est en attente de confirmation.');
        } catch (StockException $e) {
            return back()->with('error', 'Problème de stock: ' . $e->getMessage());
        } catch (OrderException $e) {
            return back()->with('error', 'Erreur de commande: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de commande: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur technique est survenue. Veuillez réessayer.',
                ], 500);
            }

            return back()->with('error', 'Une erreur technique est survenue. Veuillez réessayer.');
        }
    }

    /**
     * Annule une commande (si possible)
     */
    public function cancel(Order $order): RedirectResponse
    {
        // Vérifier que la commande appartient au client connecté
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cette commande.');
        }

        try {
            $this->orderService->cancelOrder($order);
            
            return back()->with('success', 'Commande annulée avec succès.');
        } catch (OrderStatusException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'annulation de commande: ' . $e->getMessage());
            return back()->with('error', 'Une erreur technique est survenue lors de l\'annulation.');
        }
    }
}
