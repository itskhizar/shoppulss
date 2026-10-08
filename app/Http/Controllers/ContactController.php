<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\Seo\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the Contact Us page.
     */
    public function show(SeoService $seo): View
    {
        $phone = Setting::get('store_phone', '+923328912706');
        $email = Setting::get('store_email', 'support@shoppulss.com');
        $whatsapp = Setting::get('whatsapp_helpline', '+923328912706');
        $address = Setting::get('store_address', 'Central Logistics & Fulfillment Facility, Karachi, Pakistan');
        $hours = Setting::get('store_support_hours', 'Monday – Saturday: 9:00 AM – 7:00 PM PKT');

        $seo->setTitle('Contact Us | ShopPulss Customer Care')
            ->setDescription('Get in touch with ShopPulss customer support in Pakistan. Contact us via WhatsApp, phone, email, or our online inquiry form for order assistance.')
            ->setCanonical(route('contact'))
            ->addBreadcrumb('Home', route('home'))
            ->addBreadcrumb('Contact Us', route('contact'));

        return view('pages.contact', compact('phone', 'email', 'whatsapp', 'address', 'hours'));
    }

    /**
     * Handle contact form submission with validation, honeypot, and rate limiting.
     */
    public function submit(Request $request): RedirectResponse
    {
        // 1. Honeypot check (field hidden from human users)
        if (! empty($request->input('website_trap'))) {
            // Silently redirect bots without processing
            return redirect()->route('contact')->with('success', 'Thank you for your message. We will respond shortly.');
        }

        // 2. IP Rate Limiting (max 5 messages per 10 minutes per IP)
        $ip = $request->ip();
        $rateKey = 'contact-form:'.$ip;

        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            $seconds = RateLimiter::availableIn($rateKey);

            return back()->withInput()->withErrors([
                'rate_limit' => "You have submitted multiple messages recently. Please wait {$seconds} seconds before sending another message.",
            ]);
        }

        RateLimiter::hit($rateKey, 600);

        // 3. Server-side validation
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'email.email' => 'Please provide a valid email address so we can reply to you.',
            'message.min' => 'Please provide at least 10 characters describing your inquiry.',
        ]);

        // 4. Secure logging (omitting sensitive or personal details beyond operational necessity)
        Log::info('ShopPulss Contact Form submission received', [
            'subject' => $validated['subject'],
            'sender_email_domain' => substr(strrchr($validated['email'], '@') ?: '', 1),
            'has_phone' => ! empty($validated['phone']),
            'ip' => $ip,
            'timestamp' => now()->toIso8601String(),
        ]);

        return redirect()->route('contact')->with('success', 'Thank you for reaching out to ShopPulss! Your message has been received. Our Karachi support team will get back to you within 24 business hours.');
    }
}
