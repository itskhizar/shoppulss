<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Redirect;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure essential business configuration placeholders exist in Settings
        $defaultSettings = [
            'store_legal_name' => Setting::get('store_legal_name', 'ShopPulss Retail Operations (PVT) Ltd. [Pending Legal Registration Review]'),
            'store_ntn' => Setting::get('store_ntn', '[Pending Legal Registration - NTN to be configured in Admin]'),
            'store_address' => Setting::get('store_address', 'Central Logistics & Fulfillment Facility, Karachi, Sindh, Pakistan'),
            'store_support_hours' => Setting::get('store_support_hours', 'Monday – Saturday: 9:00 AM – 7:00 PM PKT'),
            'return_window_days' => Setting::get('return_window_days', '7'),
            'refund_processing_days' => Setting::get('refund_processing_days', '5 to 7 business days'),
            'standard_delivery_days' => Setting::get('standard_delivery_days', '2 to 5 business days nationwide (Karachi: 1 to 2 business days)'),
            'standard_shipping_fee' => Setting::get('standard_shipping_fee', 'Rs. 200'),
            'free_shipping_threshold' => Setting::get('free_shipping_threshold', 'Rs. 2,500'),
            'governing_jurisdiction' => Setting::get('governing_jurisdiction', 'Courts of Karachi, Islamic Republic of Pakistan [Pending Legal Counsel Review]'),
        ];

        foreach ($defaultSettings as $key => $val) {
            if (! Setting::where('key', $key)->exists()) {
                Setting::set($key, $val);
            }
        }

        // 2. Pre-seed standard public policy pages
        $pages = [
            [
                'slug' => 'about-us',
                'title' => 'About ShopPulss',
                'category' => 'company',
                'summary' => 'Learn about ShopPulss, our direct-to-consumer retail model in Pakistan, catalog selection, and operational commitments.',
                'seo_title' => 'About ShopPulss | Multi-Category Online Shopping in Pakistan',
                'seo_description' => 'Discover ShopPulss, a direct retail online shopping destination in Pakistan. Learn about our curated catalog, quality checks, and nationwide fulfillment.',
                'effective_at' => now()->startOfYear(),
                'body' => <<<'HTML'
<h2>Welcome to ShopPulss</h2>
<p>ShopPulss is an online retail platform developed to provide shoppers across Pakistan with a dependable, transparent, and streamlined multi-category shopping experience. We focus on curating everyday consumer technology, lifestyle items, and home essentials, backed by transparent pricing, verified specifications, and responsive customer assistance.</p>

<h2>Our Operational Model: Direct Retail</h2>
<p>Unlike open unmoderated marketplaces where third-party sellers list inventory arbitrarily, ShopPulss operates a direct-to-consumer fulfillment approach. Products listed on our storefront are dispatched directly from our central fulfillment facility in Karachi, Pakistan. This allows us to inspect items before dispatch, maintain direct inventory counts, and take immediate responsibility for customer orders from confirmation through doorstep delivery.</p>

<h2>Product Catalog & Quality Standards</h2>
<p>Our catalog includes popular consumer electronics, mobile accessories, audio hardware, personal lifestyle gear, and everyday domestic essentials. We evaluate product build quality, functional specifications, and packaging integrity prior to listing. If a product fails to meet acceptable functional standards during intake inspection, it is withheld from sale.</p>

<h2>Transparency in Commerce</h2>
<p>We believe Pakistani online shoppers deserve complete clarity. On ShopPulss:</p>
<ul>
    <li><strong>Accurate Product Specifications:</strong> We publish clear, unembellished technical specifications and real product dimensions so you know exactly what will arrive at your doorstep.</li>
    <li><strong>Transparent Pricing:</strong> Product prices are displayed in Pakistani Rupees (PKR) with all applicable charges clearly itemized prior to final checkout.</li>
    <li><strong>Cash on Delivery (COD) Nationwide:</strong> You have the freedom to inspect sealed parcel integrity and pay courier personnel directly at the time of delivery across Pakistan.</li>
    <li><strong>7-Day Return Assistance:</strong> Eligible items that arrive damaged, defective, or incorrect can be returned under our published Return & Refund Policy.</li>
</ul>

<h2>Future Vision</h2>
<p>While our operational footprint is currently focused on serving customers across major cities and regional areas in Pakistan, ShopPulss may expand its services, catalog depth, and geographic availability over time as our logistics infrastructure grows.</p>

<h2>Contact & Assistance</h2>
<p>Have questions about a product or need guidance before placing an order? Our customer support team is available via WhatsApp, phone, and email during business hours.</p>
HTML
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'category' => 'policy',
                'summary' => 'Comprehensive information on how ShopPulss collects, protects, uses, and retains customer data.',
                'seo_title' => 'Privacy Policy | ShopPulss Pakistan',
                'seo_description' => 'Read the ShopPulss Privacy Policy to understand how customer personal details, orders, and payment data are securely handled in Pakistan.',
                'effective_at' => now()->startOfYear(),
                'body' => <<<'HTML'
<h2>1. Introduction</h2>
<p>ShopPulss ("we", "our", or "us") respects your personal privacy and is committed to protecting the personal data you share when browsing our website, creating an account, or placing an order. This Privacy Policy explains our data collection practices, the purposes for which data is processed, and your rights regarding your personal information.</p>

<h2>2. Information We Collect</h2>
<p>We collect only the information necessary to fulfill orders, maintain store security, and provide customer support:</p>
<ul>
    <li><strong>Account Information:</strong> Your name, email address, password hash, and contact numbers provided during registration.</li>
    <li><strong>Order & Delivery Information:</strong> Consignee name, delivery street address, city, postal code, province, and phone number required for courier dispatch.</li>
    <li><strong>Payment Details:</strong> For Cash on Delivery (COD), order totals and collection amounts. For bank transfers or mobile wallets (such as EasyPaisa/JazzCash), transaction reference IDs, sender mobile numbers, and bank receipts provided for manual payment verification. We do not store credit or debit card numbers on our servers.</li>
    <li><strong>Customer Communications:</strong> Messages, feedback, WhatsApp queries, and contact form submissions sent to our support desk.</li>
    <li><strong>Technical & Browsing Data:</strong> Standard server logs, IP addresses, browser user-agents, device categories, and session identifiers necessary for site security, fraud mitigation, and shopping cart persistence.</li>
</ul>

<h2>3. How We Use Your Information</h2>
<p>Your data is used strictly for legitimate commercial and operational purposes:</p>
<ul>
    <li>To process, verify, pack, and deliver your orders.</li>
    <li>To communicate order status updates, tracking numbers, and delivery notifications.</li>
    <li>To verify manual payments and prevent fraudulent orders.</li>
    <li>To facilitate product returns, warranty claims, and customer support queries.</li>
    <li>To monitor system performance, fix technical errors, and optimize website accessibility.</li>
    <li>To comply with applicable tax, commercial, and regulatory obligations in Pakistan.</li>
</ul>

<h2>4. Information Sharing & Third Parties</h2>
<p>We do not sell, rent, or trade your personal data. We share information only with authorized service partners essential to store operations:</p>
<ul>
    <li><strong>Logistics & Courier Partners:</strong> Third-party couriers (such as TCS, Leopards Courier, Trax, or PostEx) receive consignee names, delivery addresses, and phone numbers exclusively to effect parcel delivery.</li>
    <li><strong>Hosting & Technical Infrastructure:</strong> Secure cloud and server hosting vendors that maintain server hardware, database backups, and network uptime under confidentiality agreements.</li>
    <li><strong>Law Enforcement & Legal Authorities:</strong> Where required by valid legal process, judicial order, or statutory reporting under applicable Pakistani law.</li>
</ul>

<h2>5. Data Retention & Security</h2>
<p>We implement industry-standard administrative, technical, and physical security measures—including TLS encryption for web traffic, password hashing, and restricted database access—to protect your personal information against unauthorized disclosure or loss. While we take every reasonable precaution, no Internet transmission is completely invulnerable.</p>
<p>Order records are retained for the minimum statutory period required for tax auditing, accounting, and dispute resolution under Pakistani commerce regulations.</p>

<h2>6. Your Privacy Rights</h2>
<p>You have the right to request access to the personal data we hold about you, request corrections to inaccurate contact information, or request account closure. To submit a data request, please reach out through our Contact Us page.</p>

<h2>7. Updates to This Policy</h2>
<p>We may update this Privacy Policy periodically to reflect changes in our operational procedures or relevant legal standards. The "Effective Date" at the top of this page indicates when the latest version was published.</p>
HTML
            ],
            [
                'slug' => 'terms-and-conditions',
                'title' => 'Terms and Conditions',
                'category' => 'policy',
                'summary' => 'Terms governing the use of the ShopPulss website, product listings, orders, and legal liabilities.',
                'seo_title' => 'Terms and Conditions | ShopPulss Storefront',
                'seo_description' => 'Review the official terms of service governing customer accounts, order acceptance, pricing, and dispute resolution on ShopPulss.',
                'effective_at' => now()->startOfYear(),
                'body' => <<<'HTML'
<h2>1. Acceptance of Terms</h2>
<p>By browsing, accessing, or purchasing products on ShopPulss (https://shoppulss.com), you agree to be bound by these Terms and Conditions and all associated policies published on this website. If you do not agree with any provision of these terms, please discontinue use of this website.</p>

<h2>2. Customer Eligibility & Account Security</h2>
<p>You must be at least 18 years of age or possess legal capacity under Pakistani law to enter into binding purchase contracts. When registering an account, you agree to provide true, accurate, and current contact information and to maintain the confidentiality of your login credentials.</p>

<h2>3. Product Listings, Specifications & Pricing</h2>
<p>We strive to ensure all product images, descriptions, specifications, and prices are accurate and up-to-date. However, minor variations in screen color display or manufacturing packaging may occur. All prices are listed in Pakistani Rupees (PKR).</p>
<p>In the event of a typographical, technical, or clerical error where a product is listed at an incorrect price, ShopPulss reserves the right to cancel or decline any order placed for that item prior to dispatch, notifying the customer promptly.</p>

<h2>4. Order Submission & Acceptance</h2>
<p>Submitting an order through our checkout constitutes an offer to purchase. Order confirmation sent via email, SMS, or WhatsApp indicates receipt of your request, not final acceptance. Final contract formation occurs when your parcel is verified, packed, and handed over to our courier partner for dispatch.</p>
<p>We reserve the right to cancel orders due to stock unavailability, address inaccuracies, unverified phone numbers, or suspected fraud.</p>

<h2>5. Payment Methods & Verification</h2>
<p>We accept payment via Cash on Delivery (COD) and approved advance payment options (such as direct bank transfer or authorized mobile wallets). For advance payments, dispatch will proceed only after funds are successfully verified by our accounts department.</p>

<h2>6. Delivery & Risk of Loss</h2>
<p>Delivery timelines provided on our website are estimates and may vary due to weather events, regional holidays, or courier logistical limitations. Risk of loss passes to the customer upon physical receipt and signing of the parcel.</p>

<h2>7. Returns, Refunds & Warranties</h2>
<p>All returns, refunds, and warranty claims are governed strictly by our published Return & Refund Policy and Warranty Policy. Customers are advised to review these policies prior to completing purchases.</p>

<h2>8. Intellectual Property</h2>
<p>All content on ShopPulss, including logos, graphics, text, icons, and software, is the property of ShopPulss or its respective content providers and is protected under Pakistani copyright and intellectual property laws.</p>

<h2>9. Limitation of Liability</h2>
<p>To the maximum extent permitted under applicable law in Pakistan, ShopPulss shall not be liable for indirect, incidental, punitive, or consequential damages resulting from the use or inability to use this website, delay in delivery, or product defects beyond the purchase price of the item.</p>

<h2>10. Governing Law & Dispute Resolution</h2>
<p>These terms shall be governed by and construed in accordance with the substantive laws of the Islamic Republic of Pakistan. Any legal dispute arising under these terms shall be subject to the exclusive jurisdiction of the competent courts of Karachi, Pakistan.</p>
HTML
            ],
            [
                'slug' => 'return-refund-policy',
                'title' => 'Return and Refund Policy',
                'category' => 'policy',
                'summary' => 'Rules, eligibility, timelines, and procedures for returning items and obtaining refunds on ShopPulss.',
                'seo_title' => 'Return & Refund Policy | 7-Day Easy Assistance | ShopPulss',
                'seo_description' => 'Understand our straightforward return criteria, required proof for damaged goods, refund processing timelines, and customer rights on ShopPulss.',
                'effective_at' => now()->startOfYear(),
                'body' => <<<'HTML'
<h2>1. Return Window & Eligibility</h2>
<p>At ShopPulss, we want you to purchase with complete peace of mind. We offer a <strong>7-day return inspection window</strong> commencing from the date your parcel is marked as delivered by our courier partner.</p>
<p>To qualify for a standard return, items must satisfy the following conditions:</p>
<ul>
    <li>The return request must be lodged within 7 calendar days of delivery.</li>
    <li>The product must remain in its original, unused, and uninstalled condition.</li>
    <li>All original manufacturer packaging, tags, seals, warranty cards, manuals, and bundled accessories must be complete and undamaged.</li>
</ul>

<h2>2. Reasons Accepted for Return</h2>
<p>You may initiate a return under the following circumstances:</p>
<ul>
    <li><strong>Transit Damage:</strong> The product arrived physically damaged or broken.</li>
    <li><strong>Defective on Arrival (DOA):</strong> The product fails to power on or function according to its published specifications upon initial unboxing.</li>
    <li><strong>Incorrect Item Received:</strong> The product delivered differs in model, color, or specification from what was ordered.</li>
    <li><strong>Missing Parts:</strong> Essential components or accessories advertised with the product are missing from the sealed box.</li>
</ul>

<h2>3. Non-Returnable Product Categories</h2>
<p>For health, hygiene, and copyright reasons, the following items cannot be returned once opened unless proven defective upon arrival:</p>
<ul>
    <li>In-ear earphones, headphones, and personal hygiene grooming tools with broken seals.</li>
    <li>Consumable items, screen protectors, adhesives, and single-use accessories.</li>
    <li>Software products, digital codes, or activated electronic licenses.</li>
    <li>Products exhibiting signs of physical misuse, liquid damage, unauthorized disassembly, or electrical short-circuits.</li>
</ul>

<h2>4. Step-by-Step Return Process</h2>
<ol>
    <li><strong>Initiate Request:</strong> Contact our support team via WhatsApp (+923328912706) or email (support@shoppulss.com) within 7 days of delivery.</li>
    <li><strong>Provide Evidence:</strong> Share your Order Number along with clear unboxing photographs or a short video demonstrating the parcel condition and defect.</li>
    <li><strong>Authorization & Shipping:</strong> Once approved, our team will provide return parcel packing instructions and dispatch details. In major cities, reverse courier pickup may be arranged; otherwise, you will be guided to drop the parcel at an authorized courier branch.</li>
    <li><strong>Inspection:</strong> Returned items undergo physical and functional verification at our Karachi facility within 2 to 3 business days of receipt.</li>
</ol>

<h2>5. Refund Methods & Timelines</h2>
<p>Upon satisfactory inspection, refunds are processed according to your original payment method:</p>
<ul>
    <li><strong>Cash on Delivery (COD) Orders:</strong> Refunds are disbursed directly via Bank Transfer (IBFT) or verified mobile wallet (EasyPaisa / JazzCash) to customer-designated Pakistani accounts within <strong>5 to 7 business days</strong>.</li>
    <li><strong>Advance Bank Transfers:</strong> Returned directly to the originating bank account within 3 to 5 business days.</li>
</ul>
<p>Note: Standard courier delivery fees are non-refundable unless the return is due to an error on our part (such as a defective or incorrect item).</p>
HTML
            ],
            [
                'slug' => 'shipping-delivery-policy',
                'title' => 'Shipping and Delivery Policy',
                'category' => 'policy',
                'summary' => 'Delivery coverage, courier partners, estimated timelines, shipping charges, and tracking details across Pakistan.',
                'seo_title' => 'Shipping & Delivery Policy | Nationwide COD Logistics | ShopPulss',
                'seo_description' => 'Learn about ShopPulss shipping rates, delivery timelines across Karachi, Lahore, Islamabad and nationwide Pakistan, and parcel tracking.',
                'effective_at' => now()->startOfYear(),
                'body' => <<<'HTML'
<h2>1. Nationwide Delivery Coverage</h2>
<p>ShopPulss delivers to residential and commercial addresses across Pakistan, covering major metropolitan hubs, secondary cities, and regional towns served by our courier network. All orders originate directly from our central Karachi fulfillment facility.</p>

<h2>2. Delivery Timelines</h2>
<p>Estimated transit times after order confirmation and dispatch:</p>
<ul>
    <li><strong>Karachi:</strong> 1 to 2 business days.</li>
    <li><strong>Major Cities (Lahore, Islamabad, Rawalpindi, Faisalabad, Multan, Peshawar, Quetta, Gujranwala, Sialkot):</strong> 2 to 4 business days.</li>
    <li><strong>Secondary Cities & Regional Towns:</strong> 3 to 6 business days.</li>
</ul>
<p><em>Note: Orders placed on Sundays or public gazetted holidays are processed on the subsequent working day. Operational delays may occasionally occur during adverse weather events, national holidays, or courier peak seasons.</em></p>

<h2>3. Shipping Fees & Free Delivery Threshold</h2>
<ul>
    <li><strong>Standard Shipping:</strong> A flat delivery charge of Rs. 200 applies to standard orders.</li>
    <li><strong>Free Shipping:</strong> Orders with a merchandise total of Rs. 2,500 or higher qualify for Free Delivery nationwide.</li>
</ul>

<h2>4. Order Dispatch & Real-Time Tracking</h2>
<p>Once your order is verified and handed over to our courier partner (such as TCS, Leopards Courier, Trax, or PostEx), an automated notification containing your tracking number is generated. You can track parcel milestones directly on our <a href="/track-order">Track Order</a> page at any time.</p>

<h2>5. Cash on Delivery (COD) Rules</h2>
<p>For Cash on Delivery parcels, courier dispatch personnel are instructed to collect the exact invoice amount in cash prior to handing over the package. Customers are requested to keep exact change ready. Please inspect the outer flyer seal before accepting the parcel; do not accept flyers that appear tampered with or torn.</p>

<h2>6. Failed Deliveries & Re-attempts</h2>
<p>Couriers typically make up to two delivery attempts before returning a parcel to our hub. Please ensure your contact phone number is reachable so courier riders can contact you prior to arrival. If a parcel is returned due to incorrect address information or repeated unreachability, re-dispatch charges may apply.</p>
HTML
            ],
            [
                'slug' => 'payment-policy',
                'title' => 'Payment Policy',
                'category' => 'policy',
                'summary' => 'Accepted payment methods, currency details, Cash on Delivery procedures, and payment security.',
                'seo_title' => 'Payment Policy | Cash on Delivery & Direct Payments | ShopPulss',
                'seo_description' => 'Review accepted payment options on ShopPulss, including Cash on Delivery (COD), online bank transfers, and mobile wallet verification.',
                'effective_at' => now()->startOfYear(),
                'body' => <<<'HTML'
<h2>1. Operating Currency</h2>
<p>All prices, promotional deals, taxes, and shipping fees on ShopPulss are denominated and settled exclusively in <strong>Pakistani Rupees (PKR)</strong>.</p>

<h2>2. Accepted Payment Options</h2>
<p>To provide flexibility and security, we support the following payment methods:</p>

<h3>A. Cash on Delivery (COD)</h3>
<p>Cash on Delivery is available for eligible addresses across Pakistan. Under COD, you pay the courier delivery rider in cash upon receiving your order. A nominal COD handling charge may be included in the order summary where applicable.</p>

<h3>B. Direct Bank Transfer (IBFT)</h3>
<p>Customers may pay directly into our official bank account via online banking apps, ATM transfer, or branch deposit. Account details are presented during checkout. Please share your transaction receipt or reference number with our support team to expedite order dispatch.</p>

<h3>C. Mobile Wallets (EasyPaisa & JazzCash)</h3>
<p>Where enabled in checkout, payments may be submitted directly to our designated mobile merchant account. Orders move to packing once transaction reference IDs are verified by our billing department.</p>

<h2>3. Payment Security & Data Privacy</h2>
<p>ShopPulss does not collect or store customer debit or credit card credentials on our servers. For advance payments, transfers occur directly through your own banking application or authorized mobile wallet gateway.</p>

<h2>4. Fraud Prevention & Order Verification</h2>
<p>To safeguard customers against unauthorized orders and fraudulent submissions, our support team may contact first-time COD buyers via phone call or WhatsApp to confirm delivery details prior to parcel dispatch.</p>
HTML
            ],
            [
                'slug' => 'warranty-policy',
                'title' => 'Warranty Policy',
                'category' => 'policy',
                'summary' => 'Terms governing manufacturer warranties, testing warranties, and how to submit claims.',
                'seo_title' => 'Warranty Policy | Product Guarantee & Claims | ShopPulss',
                'seo_description' => 'Understand how warranty coverage works on ShopPulss, including 7-day checking warranties and official brand warranties in Pakistan.',
                'effective_at' => now()->startOfYear(),
                'body' => <<<'HTML'
<h2>1. Scope of Warranty Coverage</h2>
<p>Warranty terms on ShopPulss vary by product category and are clearly specified on individual product detail pages. Not all items carry long-term warranties.</p>

<h2>2. Types of Warranties</h2>
<ul>
    <li><strong>ShopPulss 7-Day Checking Warranty:</strong> Standard on eligible electronic and tech items. Covers manufacturing defects and dead-on-arrival faults identified within 7 days of delivery.</li>
    <li><strong>Official Brand / Manufacturer Warranty:</strong> Products carrying official brand warranties (e.g., specific mobile accessories or brand electronics) must be serviced through the manufacturer’s authorized service centers in Pakistan using the provided warranty card and purchase invoice.</li>
    <li><strong>No Warranty Products:</strong> Clearance items, consumable products, cosmetic accessories, and cables are sold without extended warranty unless dead-on-arrival.</li>
</ul>

<h2>3. What is Excluded from Warranty</h2>
<p>Warranty coverage is void under the following circumstances:</p>
<ul>
    <li>Physical breakage, cracks, scratches, dents, or structural damage resulting from drops or misuse.</li>
    <li>Liquid ingress, moisture damage, corrosion, or fire exposure.</li>
    <li>Damage caused by electrical voltage fluctuations, ungrounded outlets, or unauthorized third-party chargers.</li>
    <li>Evidence of tampering, unauthorized repair, or missing serial number / barcode labels.</li>
</ul>

<h2>4. How to Submit a Warranty Claim</h2>
<p>To lodge a checking warranty claim within the valid period:</p>
<ol>
    <li>Contact customer support with your Order Number and proof of purchase.</li>
    <li>Describe the issue in detail and share visual documentation (photos or video clip).</li>
    <li>Follow the return instructions to submit the product for technical evaluation at our Karachi facility.</li>
</ol>
HTML
            ],
            [
                'slug' => 'cookie-policy',
                'title' => 'Cookie Policy',
                'category' => 'policy',
                'summary' => 'Information on essential, functional, and session cookies used on ShopPulss.',
                'seo_title' => 'Cookie Policy | ShopPulss Storefront',
                'seo_description' => 'Learn how ShopPulss uses essential session cookies to enable shopping cart functionality, security, and user experience.',
                'effective_at' => now()->startOfYear(),
                'body' => <<<'HTML'
<h2>1. What Are Cookies?</h2>
<p>Cookies are small text files saved to your computer or mobile device when you browse websites. They enable the website to recognize your device, maintain shopping sessions, and store basic user preferences.</p>

<h2>2. Cookies Used on ShopPulss</h2>
<p>We use cookies strictly to ensure website operability and provide a smooth shopping journey:</p>
<ul>
    <li><strong>Essential & Session Cookies:</strong> Required for fundamental website operations, including remembering items in your shopping cart, securing user authentication, and protecting forms against CSRF attacks. The site cannot function properly without these cookies.</li>
    <li><strong>Preference Cookies:</strong> Used to remember your selected shopping preferences, category views, or recently viewed items across sessions.</li>
    <li><strong>Security & Anti-Abuse Cookies:</strong> Assist in detecting automated bots, rate-limiting malicious traffic, and preventing unauthorized checkout attempts.</li>
</ul>

<h2>3. Third-Party Scripts & Analytics</h2>
<p>Where analytical services are deployed, they collect aggregated, anonymized browsing statistics to help us identify slow-loading pages and broken links. We do not transmit personally identifiable information (PII) to third-party advertising networks without customer consent.</p>

<h2>4. Managing Cookies in Your Browser</h2>
<p>You can adjust your browser settings to reject cookies or notify you when a cookie is placed. Please note that disabling essential cookies will prevent the shopping cart and checkout from functioning.</p>
HTML
            ],
            [
                'slug' => 'accessibility',
                'title' => 'Accessibility Statement',
                'category' => 'company',
                'summary' => 'Our ongoing commitment to digital accessibility, keyboard navigation, and inclusive design.',
                'seo_title' => 'Accessibility Statement | ShopPulss Inclusive Design',
                'seo_description' => 'Read ShopPulss commitment to digital accessibility, readable typography, keyboard navigation, and reporting access barriers.',
                'effective_at' => now()->startOfYear(),
                'body' => <<<'HTML'
<h2>Our Commitment</h2>
<p>ShopPulss is committed to providing an online shopping experience that is accessible to all individuals, including customers with visual, auditory, motor, or cognitive disabilities. We continuously work to improve the usability and digital accessibility of our storefront.</p>

<h2>Implemented Accessibility Features</h2>
<ul>
    <li><strong>Semantic HTML:</strong> Use of standard HTML landmarks (header, nav, main, footer, article) and clear heading hierarchy.</li>
    <li><strong>Keyboard Navigation:</strong> Core interactive elements—including primary navigation, search bars, FAQ accordions, and checkout forms—are operable via standard keyboard controls.</li>
    <li><strong>Color Contrast & Typography:</strong> High-contrast color pairings and legible typography designed for comfortable reading across varying display sizes.</li>
    <li><strong>Responsive Scaling:</strong> Mobile-first layouts that adapt smoothly to screen zoom and varying device orientations without losing content.</li>
    <li><strong>Descriptive Image Attributes:</strong> Product and informational imagery include contextual alternative text descriptions.</li>
</ul>

<h2>Reporting Accessibility Barriers</h2>
<p>We welcome feedback on the accessibility of ShopPulss. If you encounter any accessibility barriers while browsing or placing an order, please contact our support team at <a href="mailto:support@shoppulss.com">support@shoppulss.com</a> or via WhatsApp at <a href="https://wa.me/923328912706">+923328912706</a>. We are committed to resolving accessibility issues promptly.</p>
HTML
            ],
            [
                'slug' => 'customer-support',
                'title' => 'Customer Support & Help Center',
                'category' => 'support',
                'summary' => 'Get assistance with your orders, tracking, returns, and general product questions.',
                'seo_title' => 'Customer Support & Help Center | ShopPulss Assistance',
                'seo_description' => 'Need help with your ShopPulss order, delivery status, or returns? Connect with our dedicated support team in Pakistan.',
                'effective_at' => now()->startOfYear(),
                'body' => <<<'HTML'
<h2>We're Here to Help</h2>
<p>At ShopPulss, customer satisfaction is our top priority. Whether you have questions before placing an order, need help choosing between products, or want an update on a package in transit, our support team is ready to assist you.</p>

<h2>Quick Self-Service Options</h2>
<ul>
    <li><strong>Track Existing Order:</strong> Check real-time parcel milestones using our <a href="/track-order">Track Order</a> tool.</li>
    <li><strong>Frequently Asked Questions:</strong> Find immediate answers to common shopping queries on our <a href="/faq">FAQ Page</a>.</li>
    <li><strong>Returns & Refunds:</strong> Review eligibility and initiate return requests on our <a href="/return-refund-policy">Return Policy</a> page.</li>
</ul>

<h2>Direct Support Channels</h2>
<ul>
    <li><strong>WhatsApp Helpline:</strong> +923328912706 (Fastest response during working hours)</li>
    <li><strong>Phone Support:</strong> +923328912706</li>
    <li><strong>Email Support:</strong> support@shoppulss.com</li>
    <li><strong>Operational Hours:</strong> Monday through Saturday, 9:00 AM – 7:00 PM PKT</li>
</ul>
HTML
            ],
        ];

        foreach ($pages as $pData) {
            Page::updateOrCreate(
                ['slug' => $pData['slug']],
                array_merge($pData, [
                    'is_published' => true,
                    'show_in_footer' => true,
                    'show_in_sitemap' => true,
                    'robots_directive' => 'index, follow',
                ])
            );
        }

        // 3. Seed canonical aliases into Redirects table
        $redirects = [
            ['source_path' => 'about', 'target_path' => '/about-us', 'status_code' => 301],
            ['source_path' => 'contact', 'target_path' => '/contact-us', 'status_code' => 301],
            ['source_path' => 'terms', 'target_path' => '/terms-and-conditions', 'status_code' => 301],
            ['source_path' => 'terms-conditions', 'target_path' => '/terms-and-conditions', 'status_code' => 301],
            ['source_path' => 'privacy', 'target_path' => '/privacy-policy', 'status_code' => 301],
            ['source_path' => 'shipping-policy', 'target_path' => '/shipping-delivery-policy', 'status_code' => 301],
            ['source_path' => 'returns-refunds', 'target_path' => '/return-refund-policy', 'status_code' => 301],
            ['source_path' => 'return-policy', 'target_path' => '/return-refund-policy', 'status_code' => 301],
            ['source_path' => 'help', 'target_path' => '/customer-support', 'status_code' => 301],
        ];

        foreach ($redirects as $rData) {
            Redirect::updateOrCreate(
                ['source_path' => $rData['source_path']],
                $rData
            );
        }
    }
}
