# ShopPulss — Comprehensive SEO Growth, Trust & Technical Foundation Plan

**Target Market:** Pakistan (Nationwide)  
**Expansion Ready:** International  
**Domain:** `https://shoppulss.com`  
**Operating Model:** Direct-to-Consumer Retail (Central Karachi Fulfillment Warehouse)  
**Primary Currency:** PKR (Pakistani Rupees)  

---

## 1. Executive Summary & Philosophy

This strategy document establishes a high-standard, sustainable search engine optimization (SEO), digital trust, and information architecture roadmap for **ShopPulss**. 

### Core Operating Principles
1. **Zero Manipulation:** Strictly adherence to Google Search Essentials. No keyword stuffing, hidden text, doorway pages, fake reviews, or link networks.
2. **Intent-Led Relevancy:** Rank by fulfilling shopper intent with accurate product specifications, transparent PKR pricing, fast page speed, and seamless mobile usability.
3. **Verified Commercial Claims:** Avoid unverified superlatives ("#1 in Pakistan", "cheapest prices", "100% original guarantee") unless supported by documentation or admin configuration.
4. **Direct Retail Transparency:** Clearly communicate that ShopPulss operates single-store central fulfillment from Karachi with direct inspection, rather than an unmoderated third-party marketplace.

---

## 2. Technical SEO Architecture

### 2.1 Canonical URL & Parameter Management
* **Preferred Protocol & Host:** Strict HTTPS with canonical domain configured via `APP_URL` (`https://shoppulss.com`).
* **Tracking Parameter Stripping:** All incoming marketing and tracking parameters (`utm_source`, `utm_medium`, `utm_campaign`, `utm_content`, `utm_term`, `gclid`, `fbclid`, `msclkid`, `ttclid`, `ref`, `_ga`, `_gl`) are systematically stripped from canonical link tags.
* **Faceted Navigation / Sorting:** Query parameters for sorting (`?sort=price_asc`) and arbitrary price filtering canonicalize to the clean base category URL (`/categories/{slug}`).
* **Pagination:** Self-referencing canonicals with clean numeric query parameters (`?page=2`) are maintained for paginated listings to ensure all products are discoverable.
* **Trailing Slashes:** Standardized without trailing slashes.

### 2.2 Indexation Map

| Route Type | URL Pattern | Indexing Directive | In XML Sitemap? | Canonical Target |
| :--- | :--- | :--- | :--- | :--- |
| **Homepage** | `/` | `index, follow` | Yes (`1.0`) | `https://shoppulss.com/` |
| **All Products Catalog** | `/shop` | `index, follow` | Yes (`0.9`) | `https://shoppulss.com/shop` |
| **Primary Category** | `/categories/{slug}` | `index, follow` | Yes (`0.85`) | Clean category URL |
| **Subcategory** | `/categories/{slug}` | `index, follow` | Yes (`0.75`) | Clean subcategory URL |
| **Product Detail (Published)** | `/products/{slug}` | `index, follow` | Yes (`0.7–0.9`) | Clean product URL |
| **Product Detail (Draft/Inactive)** | `/products/{slug}` | `noindex, follow` | No | None |
| **Internal Search Results** | `/search?q=...` | `noindex, follow` | No | Base `/search` URL |
| **Cart & Mini-Cart** | `/cart` | `noindex, follow` | No | Base `/cart` URL |
| **Checkout & Confirmation** | `/checkout`, `/order/confirmation/*` | `noindex, follow` | No | Base `/checkout` URL |
| **Customer Account Area** | `/account/*` | `noindex, follow` | No | Base `/account` URL |
| **Customer Auth (Login/Register)** | `/login`, `/register`, `/forgot-password` | `noindex, follow` | No | Base auth URL |
| **About Us** | `/about-us` | `index, follow` | Yes (`0.8`) | `https://shoppulss.com/about-us` |
| **Contact Us** | `/contact-us` | `index, follow` | Yes (`0.8`) | `https://shoppulss.com/contact-us` |
| **FAQ Hub** | `/faq` | `index, follow` | Yes (`0.8`) | `https://shoppulss.com/faq` |
| **Customer Support / Help** | `/customer-support` | `index, follow` | Yes (`0.7`) | `https://shoppulss.com/customer-support` |
| **Legal Policies (7 pages)** | `/privacy-policy`, `/terms-and-conditions`, etc. | `index, follow` | Yes (`0.7`) | Clean policy URL |
| **404 Error Page** | `/*` (Non-existent) | `noindex, follow` (HTTP 404) | No | None |

---

## 3. Dynamic XML Sitemap Strategy

ShopPulss provides a segmented XML sitemap index located at:
```
https://shoppulss.com/sitemap.xml
```

### Child Sitemaps:
1. **`https://shoppulss.com/sitemap-pages.xml`**
   * Homepage, `/shop`, `/about-us`, `/contact-us`, `/faq`, `/customer-support`, and all active published legal policy pages.
   * Cached for 1 hour; uses real `updated_at` timestamps for `lastmod`.
2. **`https://shoppulss.com/sitemap-categories.xml`**
   * All active parent categories and subcategories.
3. **`https://shoppulss.com/sitemap-products.xml`**
   * All published, canonical product pages with stock status.
   * Auto-excludes drafts, archived items, and soft-404 entities.

### Robots.txt Configuration
The `/robots.txt` file permits crawling of CSS, JS, and image assets required for mobile rendering, while preventing crawl budget waste on private and session routes:
```text
User-agent: *
Allow: /
Allow: /images/
Allow: /build/

# Disallow Private & User-Specific Paths
Disallow: /admin/
Disallow: /cart
Disallow: /cart/
Disallow: /checkout
Disallow: /checkout/
Disallow: /account/
Disallow: /search
Disallow: /api/
Disallow: /login
Disallow: /register
Disallow: /forgot-password
Disallow: /reset-password/

# Sitemap Index Declaration
Sitemap: https://shoppulss.com/sitemap.xml
```

---

## 4. Structured Data (Schema.org / JSON-LD) Specifications

Server-rendered JSON-LD is injected dynamically per page:

### 1. Site-Wide Organization & WebSite
* **`Organization`:** Name (`ShopPulss`), canonical URL, official logo, verified social channels (`sameAs`), and customer service `ContactPoint` (Pakistan phone, support hours, language: English + Urdu).
* **`WebSite`:** Includes `SearchAction` with `query-input` bound to `/search?q={search_term_string}`.

### 2. Category & Listing Pages
* **`BreadcrumbList`:** Exact match with visible breadcrumbs (`Home > Shop > Category > Subcategory`).
* **`CollectionPage`:** Clean representation of listed catalog items.

### 3. Product Pages
* **`Product`:** `name`, `description`, `image`, `sku`.
* **`Offer`:** `priceCurrency` (PKR), live `price`, `availability` (`https://schema.org/InStock` or `OutOfStock`), `itemCondition` (`NewCondition`), `seller` (ShopPulss).
* **Strict Rule on Reviews/Ratings:** `aggregateRating` and `review` schema are emitted **only** when real, approved customer reviews exist in the database. Fake or hardcoded review schemas are strictly prohibited.

### 4. FAQ Page
* **`FAQPage`:** Emits `Question` and `acceptedAnswer` blocks matching visible questions on `/faq`.

---

## 5. Google Search Console & Webmaster Setup

### Step-by-Step Onboarding:
1. **Property Verification:**
   * Recommended: DNS TXT record via your domain registrar (e.g., Cloudflare, Namecheap).
   * Fallback: HTML meta tag in `<head>`.
2. **Sitemap Submission:**
   * Submit `https://shoppulss.com/sitemap.xml` directly in Search Console.
   * Verify that child sitemaps (`sitemap-pages.xml`, `sitemap-categories.xml`, `sitemap-products.xml`) are recognized without parsing errors.
3. **URL Inspection & Live Testing:**
   * Inspect the Homepage, 1 Category, and 1 Product page using URL Inspection tool.
   * Verify rendered DOM contains server-side `<title>`, `<meta name="description">`, `<link rel="canonical">`, and `<script type="application/ld+json">`.
4. **Rich Results Validation:**
   * Validate schema using the [Google Rich Results Test](https://search.google.com/test/rich-results) for Product, Breadcrumbs, Organization, and FAQ.

---

## 6. Google Merchant Center Readiness (Pakistan)

If launching Google Shopping / Free Product Listings for Pakistan:
1. **Store Requirements:**
   * Working checkout with clear pricing in PKR.
   * Published Return & Refund Policy with clear return window and refund timeline.
   * Published Shipping Policy with realistic transit times and delivery fees.
   * Secure HTTPS checkout and verified phone/email contact details.
2. **Product Feed Fields Required:**
   * `id`: SKU or product ID (e.g., `SP-PROD-102`).
   * `title`: Clean product name (50–150 characters, brand and model included).
   * `description`: Original, factual description without promotional gimmicks.
   * `link`: Canonical product URL (`https://shoppulss.com/products/{slug}`).
   * `image_link`: High-resolution product image on white or clean neutral background (min 800x800).
   * `availability`: `in_stock` or `out_of_stock`.
   * `price`: Formatted with currency code (e.g., `2450.00 PKR`).
   * `condition`: `new`.
   * `brand`: Brand name where known; store brand `ShopPulss` for direct retail private labels.
   * `identifier_exists`: `no` if item does not have a GTIN/EAN barcode.

---

## 7. Ethical Link-Earning Strategy (No Spam / No PBNs)

Google’s Spam Policies penalize purchased links, private blog networks (PBNs), automated directory submissions, and spam outreach.

### Sustainable, High-Authority Link Acquisition for ShopPulss:
1. **Supplier & Brand Partner Listings:**
   * Request that official suppliers, authorized distributors, and local brand partners list ShopPulss as an authorized retail stockist on their official websites.
2. **High-Utility Buying Guides (Pakistani Context):**
   * Publish comprehensive, shopper-oriented buying guides:
     * *“What to Look for When Buying a Fast Charger in Pakistan (Voltages, Protocols & Safety)”*
     * *“Smartwatch Compatibility Guide: Android vs iOS in Pakistan”*
     * *“Home Audio Guide: Bluetooth Codecs and Room Sizing”*
   * These educational assets earn natural backlinks from tech forums, local tech blogs (e.g., ProPakistani, TechJuice), and community discussions.
3. **Digital PR & Company Milestones:**
   * Announce genuine operational milestones (e.g., expansion of Karachi warehouse, logistics partnerships with couriers like TCS or Leopards, introduction of verified checkout).
4. **Verified Local Citations & Directories:**
   * Register verified company profiles on legitimate business directories (Google Business Profile if walk-in or office location is staffed, Pakistan Chamber of Commerce, reputable business registries). Ensure NAP (Name, Address, Phone) consistency.
5. **Authentic Customer Reviews Strategy:**
   * Automated post-delivery email/SMS 3 days after courier delivery requesting honest feedback.
   * Never incentivize positive-only reviews with discounts (violates Google guidelines and Consumer Protection regulations).

---

## 8. 30 / 60 / 90-Day Measurement Plan

### First 30 Days: Crawlability & Health
* **Goal:** 100% of canonical URLs indexed without technical errors.
* **Key Metrics:**
  * Search Console Index Coverage: zero 5xx server errors, zero unintended 404s.
  * Validation of all child sitemaps in Search Console.
  * Mobile usability: 100% pass rate in Google Mobile-Friendly check.
  * Core Web Vitals baseline check (LCP < 2.5s, CLS < 0.1, INP < 200ms).

### 60 Days: Impression Growth & Rich Results
* **Goal:** Rich result enhancement activations and organic click growth.
* **Key Metrics:**
  * Search Console "Merchant Listings" and "Product Snippets" showing green valid items.
  * Growth in total organic impressions across branded ("ShopPulss") and non-branded category queries.
  * Click-through rate (CTR) optimization on category meta descriptions.
  * Contact form submission verification and zero spam reports.

### 90 Days: Conversions & Sustainable Scaling
* **Goal:** Organic traffic translating into genuine orders and repeat buyers.
* **Key Metrics:**
  * Organic landing page conversion rate (Visitor to Cart, Cart to Order).
  * Expansion of high-demand category landing page copy based on Search Console queries.
  * Zero crawl anomalies or canonical divergence.

---

## 9. Store Owner / Legal Counsel Pre-Launch Checklist

Before promoting the store publicly, the store owner and legal counsel should review and customize the following settings in the Laravel application:

- [ ] **Official Registered Business Name:** Verify legal entity name in `Setting` / policy pages (e.g., `ShopPulss Retail Operations (PVT) Ltd.`).
- [ ] **NTN / Tax Registration Number:** Insert official National Tax Number in admin settings.
- [ ] **Physical Address:** Confirm central Karachi fulfillment address or registered office address.
- [ ] **Helpline Numbers:** Confirm customer support phone and WhatsApp hotline (+923328912706).
- [ ] **Shipping Policy Constants:** Confirm standard delivery fee (currently Rs. 200) and Free Shipping threshold (currently Rs. 2,500).
- [ ] **Courier Partners:** Confirm whether couriers (TCS, Leopards, Trax, PostEx) are currently under active commercial contract.
- [ ] **Return Window:** Confirm the 7-day inspection return window and non-returnable categories.
- [ ] **Dispute Resolution Jurisdiction:** Confirm court of competent jurisdiction (e.g., Courts of Karachi, Pakistan).
- [ ] **Google Search Console Ownership:** Add DNS TXT verification token for `shoppulss.com`.
- [ ] **Google Merchant Center:** Submit product feed once product inventory and pricing are finalized.
