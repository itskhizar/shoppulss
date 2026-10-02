<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Merge guest cart if any
            $this->mergeGuestCart($request);

            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('home'))
                ->with('success', 'Welcome back, '.$user->name.'!');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Merge guest session cart into user DB cart on login.
     */
    private function mergeGuestCart(Request $request): void
    {
        $sessionCartId = $request->session()->get('cart_id');
        if (! $sessionCartId) {
            return;
        }

        $guestCart = Cart::where('id', $sessionCartId)
            ->whereNull('user_id')
            ->active()
            ->with('items')
            ->first();

        if (! $guestCart || $guestCart->items->isEmpty()) {
            return;
        }

        $user = Auth::user();
        $userCart = $user->activeCart ?? Cart::create([
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        foreach ($guestCart->items as $guestItem) {
            $existing = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $guestItem->quantity);
                $existing->update(['total_price' => $existing->unit_price * $existing->quantity]);
            } else {
                $userCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'product_variant_id' => $guestItem->product_variant_id,
                    'quantity' => $guestItem->quantity,
                    'unit_price' => $guestItem->unit_price,
                    'total_price' => $guestItem->total_price,
                ]);
            }
        }

        // Remove guest cart
        $guestCart->delete();
        $request->session()->forget('cart_id');

        // Bind user cart to session
        $request->session()->put('cart_id', $userCart->id);
    }
}
