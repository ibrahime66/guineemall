<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Event::listen(Login::class, function (Login $event) {
            $sessionId = session()->getId();
            $userId = $event->user->id;

            $guestItems = Cart::whereNull('user_id')
                ->where('session_id', $sessionId)
                ->with('product')
                ->get();

            if ($guestItems->isEmpty()) {
                return;
            }

            DB::transaction(function () use ($guestItems, $userId) {
                foreach ($guestItems as $guestItem) {
                    $product = $guestItem->product;
                    if (! $product) {
                        $guestItem->delete();
                        continue;
                    }

                    $target = Cart::where('user_id', $userId)
                        ->where('product_id', $guestItem->product_id)
                        ->first();

                    if ($target) {
                        $newQty = min($target->quantity + $guestItem->quantity, $product->stock);
                        $target->update(['quantity' => $newQty]);
                        $guestItem->delete();
                    } else {
                        $guestItem->update([
                            'user_id' => $userId,
                            'session_id' => null,
                        ]);
                    }
                }
            });
        });
    }
}
