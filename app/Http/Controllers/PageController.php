<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Setting;
use App\Services\Seo\SeoService;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the About Us page.
     */
    public function about(SeoService $seo): View
    {
        $categories = Category::active()->parents()->orderBy('display_order')->get();

        $seo->setTitle('About ShopPulss | Multi-Category Online Shopping in Pakistan')
            ->setDescription('Learn about ShopPulss, a direct retail online shopping destination in Pakistan. Explore our catalog, quality standards, central Karachi fulfillment, and customer support.')
            ->setCanonical(route('about'))
            ->addBreadcrumb('Home', route('home'))
            ->addBreadcrumb('About Us', route('about'));

        return view('pages.about', compact('categories'));
    }

    /**
     * Display the dedicated FAQ page with accessible accordion controls.
     */
    public function faq(SeoService $seo): View
    {
        $faqSections = [
            'Orders & Checkout' => [
                [
                    'q' => 'How do I place an order on ShopPulss?',
                    'a' => 'Browsing our catalog is simple: select your desired product, choose any applicable specifications or variants, and click "Add to Cart" or "Buy Now". Proceed to Checkout, enter your delivery address in Pakistan, select your payment preference (such as Cash on Delivery), and confirm your order.',
                ],
                [
                    'q' => 'Do I need an account to place an order?',
                    'a' => 'No. You can check out as a guest using only your delivery address and contact phone number. However, creating a free account allows you to track order progress, review past purchase history, and save shipping details for future visits.',
                ],
                [
                    'q' => 'Can I cancel or modify my order after placing it?',
                    'a' => 'Yes, provided your parcel has not yet been dispatched from our central fulfillment center. Please reach out to our WhatsApp helpline (+923328912706) immediately with your Order Number to request modifications or cancellation.',
                ],
            ],
            'Payments' => [
                [
                    'q' => 'What payment methods do you accept?',
                    'a' => 'We accept Cash on Delivery (COD) nationwide across Pakistan, as well as Direct Bank Transfer (IBFT) and authorized mobile wallet payments (EasyPaisa and JazzCash).',
                ],
                [
                    'q' => 'Is Cash on Delivery (COD) available in my city?',
                    'a' => 'Yes. Cash on Delivery is supported in all major cities, secondary towns, and regional locations serviced by our courier network across Pakistan.',
                ],
                [
                    'q' => 'Do you save my debit or credit card details?',
                    'a' => 'No. ShopPulss does not collect or store credit or debit card credentials on our servers. For electronic payments, transactions are handled through your own banking app or mobile wallet interface.',
                ],
            ],
            'Shipping & Delivery' => [
                [
                    'q' => 'How long does delivery take?',
                    'a' => 'Orders within Karachi typically deliver within 1 to 2 business days. For major cities (Lahore, Islamabad, Rawalpindi, Faisalabad, Multan, Peshawar, Quetta), transit takes 2 to 4 business days. Regional and remote destinations may require 3 to 6 business days.',
                ],
                [
                    'q' => 'How much are the delivery charges?',
                    'a' => 'Standard shipping is Rs. 200 nationwide. Orders meeting or exceeding Rs. 2,500 qualify for Free Delivery.',
                ],
                [
                    'q' => 'How can I track my package in real time?',
                    'a' => 'Once your parcel is dispatched, you receive a courier tracking number. You can monitor milestone updates directly by visiting our Track Order page at /track-order.',
                ],
            ],
            'Returns, Refunds & Warranty' => [
                [
                    'q' => 'What is the return period for products?',
                    'a' => 'We offer a 7-day inspection window starting from the day your parcel is marked as delivered by the courier. Eligible products that arrive defective, damaged, or incorrect may be returned for replacement or refund under our Return & Refund Policy.',
                ],
                [
                    'q' => 'How are refunds disbursed for Cash on Delivery orders?',
                    'a' => 'Once returned items pass inspection at our Karachi facility, refunds for COD purchases are disbursed directly to your designated Pakistani bank account (IBFT) or EasyPaisa/JazzCash wallet within 5 to 7 business days.',
                ],
                [
                    'q' => 'Do products come with a warranty?',
                    'a' => 'Eligible electronics and technical accessories include a 7-day checking warranty from ShopPulss. Products carrying official manufacturer warranties are serviced directly through the brand authorized service centers in Pakistan using the provided warranty card and purchase receipt.',
                ],
            ],
            'Product Authenticity & Storage' => [
                [
                    'q' => 'Does ShopPulss operate as a multi-seller marketplace?',
                    'a' => 'No. ShopPulss is a direct retail storefront. We inspect, inventory, and dispatch every item directly from our central Karachi fulfillment facility, ensuring you receive verified items without third-party marketplace middlemen.',
                ],
                [
                    'q' => 'What if an item is out of stock?',
                    'a' => 'Out-of-stock items are clearly marked. You can contact customer support on WhatsApp to inquire about restock timelines or receive recommendations for comparable in-stock items.',
                ],
            ],
        ];

        // Flatten for SEO schema
        $flatFaqs = [];
        foreach ($faqSections as $section => $items) {
            foreach ($items as $item) {
                $flatFaqs[] = $item;
            }
        }

        $seo->forFaq($flatFaqs);

        return view('pages.faq', compact('faqSections'));
    }

    /**
     * Display a published CMS / Legal policy page.
     */
    public function show(string $slug, SeoService $seo): View
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        // Dynamically inject live business values into placeholders
        $placeholders = [
            '{{store_name}}' => config('seo.site_name', 'ShopPulss'),
            '{{store_phone}}' => Setting::get('store_phone', '+923328912706'),
            '{{store_email}}' => Setting::get('store_email', 'support@shoppulss.com'),
            '{{store_whatsapp}}' => Setting::get('whatsapp_helpline', '+923328912706'),
            '{{store_address}}' => Setting::get('store_address', 'Central Logistics & Fulfillment Facility, Karachi, Pakistan'),
            '{{return_window_days}}' => Setting::get('return_window_days', '7'),
            '{{refund_days}}' => Setting::get('refund_processing_days', '5 to 7 business days'),
            '{{shipping_days}}' => Setting::get('standard_delivery_days', '2 to 5 business days'),
        ];

        $renderedBody = str_replace(array_keys($placeholders), array_values($placeholders), $page->body);

        $seo->forPage($page);

        // Related policy links for the sidebar
        $relatedPages = Page::published()
            ->where('slug', '!=', $page->slug)
            ->where('category', 'policy')
            ->orderBy('title')
            ->get();

        return view('pages.policy', compact('page', 'renderedBody', 'relatedPages'));
    }

    /**
     * Help Center / Customer Support hub.
     */
    public function support(SeoService $seo): View
    {
        return $this->show('customer-support', $seo);
    }
}
