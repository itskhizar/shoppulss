# SHOPPULSS — PRODUCTION LEGAL, TRUST, SEO, INDEXING & ECOMMERCE SEO IMPLEMENTATION

You are a principal Laravel ecommerce engineer, Laravel Boost specialist, technical SEO architect, ecommerce content strategist, accessibility engineer, performance engineer, and secure software developer.

You are working directly in the existing ShopPulss Laravel ecommerce codebase.

## Project context

Website:
https://shoppulss.com

Brand:
ShopPulss

Primary market:
Pakistan

Current currency:
PKR

Future:
International expansion, additional locales, potential additional currencies.

Business:
A Pakistan-first, multi-category ecommerce store selling consumer products through main categories, subcategories, product listing pages, product detail pages, brands where applicable, search, filters, cart, checkout, payments, order tracking, and CMS pages.

Known stack/project baseline:
- Laravel 12+
- PHP 8.3+
- MySQL 8+
- Redis
- Nginx
- Blade
- Tailwind CSS
- Cloudflare/CDN-ready deployment
- Laravel Boost installed
- Existing product/category/brand/CMS architecture
- Existing search, filtering, sorting, cart, guest and authenticated checkout
- Existing payment support may include COD, Bank Transfer and EasyPaisa
- Existing courier/shipping support may include TCS and Leopards
- Existing product/category/CMS SEO fields may already exist
- Existing mobile-first, security-first and performance-oriented architecture

The actual existing application is the source of truth. Inspect it before making assumptions.

---

# 1. PRIMARY OBJECTIVE

Implement a production-quality legal/trust-page system and a complete, sustainable technical + on-page ecommerce SEO foundation for ShopPulss.

The work must improve:
- Crawlability
- Indexability
- Canonical URL management
- Internal linking
- Metadata
- Structured data
- Sitemap and robots management
- Product/category SEO
- Legal/trust transparency
- Accessibility
- Mobile UX
- Core Web Vitals readiness
- Google Search Console readiness
- Google Merchant Center readiness
- Sustainable organic growth readiness

This is NOT a request for superficial meta tags.

This is NOT a request to guarantee #1 Google rankings.

Do not make any claim that SEO guarantees a first-place ranking in Google, Safari, Bing, or any search engine. Build the strongest legitimate foundation possible according to search intent, relevance, useful content, technical quality, performance, trust and user experience.

---

# 2. ABSOLUTE NON-NEGOTIABLE RULES

1. Do not rewrite, replace, break, or redesign working application architecture.

2. Do not change core business logic unless a small, safe integration is required for this scope.

3. Do not break:
   - Authentication
   - Authorization
   - Admin panel
   - Product management
   - Category management
   - Inventory
   - Product variants
   - Search
   - Filtering
   - Sorting
   - Cart
   - Checkout
   - Payments
   - COD
   - Bank transfer
   - EasyPaisa
   - Shipping/courier integrations
   - Orders
   - Order tracking
   - Customer accounts
   - CMS
   - Existing APIs
   - Existing analytics
   - Existing production routes

4. Inspect and reuse existing application conventions:
   - Folder structure
   - Naming conventions
   - Service/action/domain pattern
   - Existing repositories or service classes
   - Existing Blade components
   - Existing layout components
   - Existing Tailwind design tokens
   - Existing CMS models and pages
   - Existing config files
   - Existing policies and permissions
   - Existing tests
   - Existing migrations
   - Existing SEO fields
   - Existing redirect behavior

5. Use Laravel Boost as the project workflow and code-quality assistant where available. Follow Laravel Boost’s existing project instructions, conventions, generated guidance, diagnostics and testing workflow. Do not bypass or conflict with Boost rules.

6. Keep changes modular, minimal, maintainable, production-safe, migration-safe, null-safe and testable.

7. Do not introduce unnecessary dependencies, packages, frameworks, JavaScript libraries or external services. Prefer clean native Laravel, Blade, Tailwind, existing project utilities and existing packages.

8. Do not hard-code business facts that have not been verified in the application or explicitly configured by the owner.

9. Do not invent:
   - Legal entity name
   - NTN/tax number
   - Registration number
   - Physical address
   - Customer-support email
   - Phone number
   - WhatsApp number
   - Social-media profiles
   - Shipping coverage
   - Shipping fee
   - Free-shipping threshold
   - Delivery time
   - Courier commitment
   - Return window
   - Refund timeline
   - Warranty rules
   - Payment methods
   - Governing law
   - Dispute jurisdiction
   - Certifications
   - “100% original/authentic” claims
   - “Lowest price” claims
   - “Best” claims
   - “Fastest delivery” claims
   - “Guaranteed delivery” claims
   - “Secure payment” claims unless technically and contractually supported
   - “Verified seller” claims unless a real verification process exists
   - Ratings, reviews or testimonials
   - GTINs, MPNs, brand data, product specifications or stock status

10. Store uncertain business/legal details as admin-configurable fields, configuration values, CMS placeholders, or clearly documented launch checklist items.

11. Do not use prohibited or manipulative SEO practices:
   - Keyword stuffing
   - Hidden text
   - Hidden links
   - Cloaking
   - Doorway pages
   - Fake city landing pages
   - Thin AI-generated pages
   - Copy-pasted manufacturer content without value addition
   - Fake ratings or reviews
   - Fake structured data
   - Purchased links
   - PBNs
   - Link farms
   - Automated backlink tools
   - Spam directories
   - Misleading titles/descriptions
   - Canonical manipulation
   - Redirecting unrelated deleted pages to the homepage

12. All important SEO content must be present in meaningful server-rendered HTML. Do not render product titles, price, availability, category links, product descriptions, primary headings, canonical tags, or structured data only through client-side JavaScript.

13. Keep all user-generated and rich text content safely escaped/sanitized. Do not weaken security.

14. Do not commit secrets, `.env` values, credentials, API keys, analytics keys, tokens, personally identifiable information, or generated vendor files that are excluded by existing repository standards.

---

# 3. REQUIRED EXECUTION PROCESS

Perform the work in phases. Do not begin broad refactoring.

## Phase 0 — Inspect, audit and plan

Before editing code, inspect at minimum:

- Project documentation and README files
- Laravel Boost instructions and available project tooling
- composer.json and package.json
- routes/web.php and all relevant route files
- Controllers
- Models
- Migrations
- Factories and seeders
- Services/actions/domain classes
- Existing middleware
- Existing Blade layouts
- Shared components
- Header navigation
- Footer
- Product list pages
- Product detail pages
- Category/subcategory pages
- Brand pages
- Search pages
- Filter/sort/pagination implementation
- Cart and checkout pages
- Existing CMS/static pages
- Admin product/category/CMS management
- Existing product/category/CMS SEO fields
- Existing canonical/meta/Open Graph/schema components
- Existing sitemap/robots implementation
- Existing redirects and slug history implementation
- Existing error pages
- Existing analytics, consent scripts, Google integrations and tracking setup
- Existing test setup and CI configuration
- Existing deployment conventions

Create a concise internal implementation checklist.

Then provide an initial audit summary that states:
1. What already exists.
2. What is missing.
3. What will be reused.
4. What needs to be added.
5. Any risks or ambiguous business/legal requirements.
6. The exact existing route conventions to preserve.
7. Any actual indexing blockers found.

Do not duplicate existing SEO systems. Improve or extend them.

---

# 4. ROUTE POLICY

Do not blindly create new routes if equivalent pages already exist.

Use the existing canonical public route style found in the project. If no routes exist, prefer these routes:

- `/about`
- `/contact`
- `/faq`
- `/privacy-policy`
- `/terms-conditions`
- `/shipping-policy`
- `/returns-refunds`
- `/payment-policy`
- `/warranty-policy`
- `/cookie-policy`
- `/accessibility`
- `/help` only if it fits the existing support architecture

For catalog pages, preserve the existing project route system. Do not change public catalog URLs merely to match an example.

Typical preferred patterns only if the project does not already define alternatives:
- `/categories`
- `/category/{category-slug}`
- `/category/{category-slug}/{subcategory-slug}`
- `/product/{product-slug}`
- `/brand/{brand-slug}`

If a public URL must change:
- Use a permanent 301 redirect only when the old URL has a true replacement.
- Avoid redirect chains.
- Maintain old slug history where appropriate.
- Never redirect unrelated removed URLs to the homepage.
- Test the redirect status and final canonical destination.

---

# 5. LEGAL, TRUST AND CUSTOMER INFORMATION PAGES

Create or improve the public pages listed below.

All legal/trust pages must:
- Use the existing site layout, header, footer, typography, color system, spacing, buttons and responsive rules.
- Be mobile-first.
- Use semantic HTML.
- Have exactly one visible H1.
- Include a useful title, meta description, canonical URL and Open Graph metadata.
- Be readable, customer-friendly and professional.
- Use clean content width and generous line-height.
- Avoid large unreadable walls of text.
- Use headings, short paragraphs, flat bullet lists and anchor links.
- Include a “Last updated” date.
- Include a table of contents on long pages.
- Use accessible anchor targets.
- Be keyboard accessible.
- Link naturally to related policies.
- Use real business data only when configured.
- Be editable through the existing CMS where possible.
- Be indexable unless there is a verified reason to noindex them.

## 5.1 Reusable CMS/legal page foundation

First determine whether an existing CMS/static-page model already supports this.

If it does:
- Extend it safely.
- Do not create a duplicate parallel CMS.

If no suitable model exists:
- Create a minimal, admin-manageable `Page` or `LegalPage` solution consistent with project conventions.

Suggested fields only where needed:
- title
- slug
- body
- status
- locale
- seo_title
- seo_description
- canonical_url
- robots directive
- og_image
- effective_at
- updated_at
- show_in_footer
- show_in_sitemap

Use migrations only if necessary.

Provide default seeded content only when the project has a safe seeding/content workflow. Make all organization-specific information configurable. Never seed fake contact details.

## 5.2 About page

Route:
`/about` or existing canonical equivalent.

Purpose:
Present ShopPulss as a Pakistan-first multi-category online shopping platform.

Content requirements:
- Explain what ShopPulss is.
- Explain that customers can discover products across categories actually available in the live catalog.
- Explain the focus on understandable product information and convenient shopping.
- Refer to quality standards only in non-absolute language unless a verified policy exists.
- Mention customer support, delivery, payments, returns and warranty only by linking to applicable policy pages or describing verified operational behavior.
- State future expansion carefully: “ShopPulss may expand its services and availability over time.”
- Include natural calls to action to browse categories and contact support.
- Avoid unsupported claims such as “Pakistan’s largest,” “#1,” “lowest price,” “guaranteed original,” or “fastest delivery.”

Recommended SEO fallback:
Title:
`About ShopPulss | Online Shopping in Pakistan`

Meta description:
`Learn about ShopPulss, a Pakistan-first multi-category online shopping destination with clear product information and customer support.`

Adjust dynamically to the actual business and catalog.

## 5.3 Contact page

Route:
`/contact` or existing equivalent.

Include only configured/verified:
- Support email
- Phone
- WhatsApp
- Support hours
- Business address
- Contact form

Contact form requirements:
- CSRF protection.
- Server-side validation.
- Accessible labels.
- Accessible error messages.
- Clear success/failure feedback.
- Honeypot or existing CAPTCHA/spam prevention.
- Rate limiting.
- No sensitive information in logs.
- Privacy notice linked to Privacy Policy.
- Do not expose internal staff emails.

Also link to FAQ, shipping, returns/refunds, privacy and terms.

Add Organization or LocalBusiness schema only if all required business information is accurate and the schema is appropriate. Do not create LocalBusiness markup merely because it exists as an option.

## 5.4 Privacy Policy

Route:
`/privacy-policy`

Write professional, editable ecommerce privacy content that accurately reflects configured behavior.

Include sections covering:
- Introduction
- Information collected
- Account information
- Contact information
- Order and shipping information
- Payment information
- Support communications
- Website/device/browser usage data
- Cookies and similar technologies
- Purpose of processing/use
- Order fulfilment
- Delivery/courier data sharing where applicable
- Payment providers where applicable
- Customer support
- Marketing communications and consent where applicable
- Analytics where actually used
- Security practices without absolute guarantees
- Data retention approach
- Third-party service providers
- Children’s privacy
- Customer rights and data requests
- Policy updates
- Contact information

Rules:
- Do not claim compliance certifications or legal frameworks without legal review.
- Do not claim card details are never handled unless this is technically true.
- Do not claim international transfers unless actually applicable.
- Use configuration/CMS placeholders for business-specific details.
- Link to Cookie Policy where present.

## 5.5 Terms and Conditions

Route:
`/terms-conditions`

Cover:
- Acceptance of terms
- Eligibility
- Account responsibilities
- Product information and images
- Product availability
- Price and price errors
- Promotions/discounts where applicable
- Order submission, acceptance and cancellation
- Payment verification and fraud prevention
- Actual payment methods only
- COD rules only if COD exists
- Bank transfer rules only if bank transfer exists
- Online payment rules only if online payments exist
- Shipping/delivery policy reference
- Customer address responsibility
- Delivery delays outside reasonable control
- Product inspection
- Returns/refunds reference
- Warranty reference
- Website use
- Prohibited activities
- Intellectual property
- Third-party services
- Limitation of liability to the extent permitted by applicable law
- Changes to terms
- Governing law and dispute language only as approved configurable text
- Contact information

Do not make legal assertions that exceed verified business policy.

## 5.6 Shipping Policy

Route:
`/shipping-policy`

Write Pakistan-focused shipping information that uses live configuration or content placeholders.

Cover:
- Where delivery is available
- Order processing
- Delivery estimates
- Shipping charges
- Free shipping threshold only if configured
- COD availability only if active
- Couriers only if active/configured
- TCS/Leopards support only if actually enabled
- Tracking
- Delivery attempts
- Address accuracy/customer responsibilities
- Remote-area considerations
- Delays caused by weather, public holidays, operational limitations and courier circumstances
- Failed delivery
- Returned-to-sender scenarios
- Contact/support process

Do not invent timing, fees, coverage or courier commitments.

## 5.7 Returns and Refunds

Route:
`/returns-refunds`

Write clear, configurable customer-facing content covering:
- Return eligibility
- Damaged/incorrect/missing/defective items
- Required photos/evidence when applicable
- Unused condition requirements
- Non-returnable product classes where applicable
- Return request process
- Inspection
- Approval/rejection
- Refund method
- COD refund handling
- Payment-provider refund handling
- Return-shipping responsibility
- Exchange/replacement if supported
- Cancellation before dispatch
- Contact support

All time windows, exclusions and processing timeframes must use real configured values or visible placeholders requiring owner review.

## 5.8 Payment Policy

Route:
`/payment-policy`

Cover only configured payment methods:
- Currency and PKR display
- COD, only if active
- Bank transfer, only if active
- EasyPaisa, only if active
- Other online payment providers, only if active
- Payment verification
- Payment failure handling
- Order cancellation/payment mismatch handling
- Privacy/security statements limited to what is technically true
- No card-storage claims unless accurate

## 5.9 Warranty Policy

Route:
`/warranty-policy`

Clarify:
- Manufacturer warranty versus ShopPulss/seller warranty, where applicable.
- Product-specific warranty terms.
- What may be included or excluded.
- Proof of purchase requirements.
- Warranty claim process.
- Repair/replacement/refund paths according to actual policy.
- Product page terms take precedence where they differ.

Do not claim every item has a warranty.

## 5.10 FAQ

Route:
`/faq`

Build a customer-friendly FAQ page organized by:
- General
- Orders
- Payments
- Delivery
- Returns and refunds
- Account
- Product availability
- Warranty
- Privacy and support

Implementation requirements:
- Use native semantic accordion controls with actual `<button>` elements.
- Add `aria-expanded` and `aria-controls`.
- Support keyboard navigation.
- Make answers present in server-rendered HTML.
- Ensure the content is readable without JavaScript.
- Link answers to the authoritative policy pages.
- Only publish answers that reflect actual ShopPulss functionality and configured policies.
- Avoid unsupported statements about quality checks, authenticity, delivery time or payment availability.

FAQ schema:
- Add `FAQPage` JSON-LD only for the visible questions and answers on the dedicated FAQ page.
- Do not use FAQ schema for hidden answers.
- Do not add identical FAQ schema to hundreds of pages.
- Do not use promotional FAQs merely to manipulate rich results.

## 5.11 Cookie Policy and consent

Route:
`/cookie-policy`

First audit all cookies, scripts and tracking tools in the application.

Then:
- Write a cookie policy that describes only actual cookie categories and services.
- Clearly separate essential cookies from optional analytics/marketing cookies.
- Implement consent controls only if needed by the actual legal/deployment requirements and existing tracking setup.
- Do not load non-essential analytics/marketing scripts before consent when consent is required.
- Provide accessible “manage preferences” functionality if a consent manager is implemented.
- Do not claim use of cookies/services that are not actually present.

## 5.12 Accessibility statement

Route:
`/accessibility`

Create an accessibility statement with:
- ShopPulss’s commitment to improving access.
- Supported accessibility practices actually implemented.
- A reporting/contact mechanism using configured support information.
- A note that accessibility improvements are ongoing.
- No claim of certification or full legal conformance unless audited.

---

# 6. GLOBAL SEO ARCHITECTURE

Create or extend one centralized, reusable SEO system.

Do not manually duplicate metadata in individual Blade pages.

Use a design consistent with the existing project. A suitable pattern may include:
- `app/Services/Seo/SeoService.php`
- Value object/data transfer object for metadata
- Blade components such as `<x-seo.meta />`
- Schema generator/service
- Breadcrumb schema component
- Route/page-specific SEO resolvers

Do not add complexity where existing components already handle this.

The centralized SEO system must support:
- Title
- Meta description
- Canonical URL
- Meta robots
- Open Graph metadata
- Twitter/X metadata
- JSON-LD schema blocks
- Page locale
- Image fallback handling
- SEO overrides for products, categories and CMS pages
- Safe defaults when optional SEO fields are empty
- Configurable site name/domain/logo/social profiles
- Noindex logic
- Consistent absolute URLs

Every indexable public page should render:
- `<title>`
- `<meta name="description">`
- `<link rel="canonical">`
- Appropriate robots directive
- Open Graph title, description, type, URL, image, site name and locale
- Twitter/X card tags where appropriate
- Correct `<html lang="en">` now, with architecture ready for future locales
- Charset, viewport, favicon and appropriate theme metadata

Keep the default market as Pakistan and the current public language as English unless the codebase says otherwise.

Do not add hreflang until alternate localized URLs actually exist.

---

# 7. PAGE-BY-PAGE SEO RULES

## 7.1 Homepage

Canonical:
The actual production canonical root URL, using the preferred configured host and HTTPS.

Fallback title:
`ShopPulss | Quality Products Online in Pakistan`

Fallback meta description:
`Shop products across popular categories at ShopPulss. Explore quality products with clear product details and customer support in Pakistan.`

Requirements:
- One descriptive H1.
- Example H1 only if appropriate: `Quality Products Online in Pakistan`.
- Short original introductory text that accurately describes ShopPulss and its available categories.
- Crawlable category links.
- Crawlable featured/new/offer product links only where those collections exist.
- Natural internal links to trust/support pages.
- Do not add excessive SEO paragraphs or keyword blocks.
- Do not list unavailable categories.
- Do not promise shipping/payment/warranty terms that are not configured.
- Use Organization and WebSite schema only with correct configured values.
- Add SearchAction only if a public functional search endpoint exists and accepts a query parameter.

## 7.2 Category pages

Apply only to non-empty, public, useful, indexable categories.

Fallback title:
`{Category Name} Online in Pakistan | ShopPulss`

Fallback meta description:
`Shop {Category Name} online in Pakistan at ShopPulss. Browse available products, compare key features and find details before you buy.`

Requirements:
- One H1 matching the category name.
- Original category introduction only where it helps shoppers.
- Normally 100–250 words maximum, but use less when the category does not need it.
- Explain useful selection factors, use cases and real subcategories.
- Use product/category data, not generic keyword templates.
- Provide crawlable links to subcategories and products.
- Display visible breadcrumbs.
- Use BreadcrumbList schema matching visible breadcrumb labels and URLs.
- Canonicalize to the clean category URL.
- Empty, unpublished or thin categories should normally be noindex or unpublished according to the actual business strategy.

## 7.3 Subcategory pages

Fallback title:
`Buy {Subcategory Name} Online in Pakistan | ShopPulss`

Fallback meta description:
`Explore {Subcategory Name} online in Pakistan at ShopPulss. Compare available options, pricing and key product details before you buy.`

Requirements:
- One H1.
- Useful, original introductory content only when justified.
- Visible breadcrumb navigation.
- Crawlable product links.
- Clean canonical URL.
- No filter URL duplicates.

## 7.4 Product pages

Fallback title:
`Buy {Product Name} Online in Pakistan | ShopPulss`

Fallback meta description:
`Buy {Product Name} online in Pakistan at ShopPulss. View price, product details, availability and applicable delivery or warranty information.`

Requirements:
- Exact product name as H1.
- Server-rendered product title, price, currency, availability, primary image, description, specifications, variants, SKU/model where available, brand where valid, category context, return link and warranty information where applicable.
- Do not copy manufacturer descriptions word-for-word where an original concise description can be created.
- Do not create unsupported product details.
- Use accurate image alt text that describes the actual image.
- Preserve existing related-products/recommendation logic; do not replace the algorithm.
- Add related products only through existing relevant logic.
- Canonicalize tracking URLs and non-canonical parameters.
- Do not create duplicate URLs for variants unless the product model specifically supports canonical variant URLs.
- Add Product and Offer JSON-LD only from live, trusted database fields and only when they match visible content.
- Add AggregateRating and Review markup only if real, visible, eligible reviews exist.
- Add ProductGroup schema only if real product variants are correctly modeled.
- Do not fabricate brand, GTIN, MPN, SKU, availability, review data or warranty data.

Product lifecycle:
- Draft/private products: inaccessible or noindex according to current rules.
- Public published products: indexable when useful.
- Temporarily out-of-stock items: retain indexability only when the page remains valuable and may return; accurately show stock status and relevant alternatives.
- Permanently discontinued items: choose the appropriate behavior case by case:
  1. 301 to a genuine direct replacement.
  2. 301 to a tightly relevant category only where appropriate.
  3. Keep a useful page with alternatives.
  4. Return 410 for intentionally removed content with no value or replacement.
- Never mass-redirect removed product pages to the homepage.

## 7.5 Brand pages

Create/index brand pages only if:
- Brands are real.
- The site has enough visible products for that brand.
- There is original, useful content.
- The page is not thin or empty.

Fallback title:
`{Brand} Products Online in Pakistan | ShopPulss`

Do not invent brand information.
No brand page should be indexed if it has no available products or no meaningful content.

## 7.6 Search pages

Internal search must remain useful for customers, but generally:
- Use `noindex, follow`.
- Exclude from sitemap.
- Prevent arbitrary query explosion.
- Use controlled canonical behavior.
- Do not block essential search assets.
- Do not create indexable pages for arbitrary internal search queries.

## 7.7 Cart, checkout, accounts and private flows

Use `noindex, follow` or equivalent controls for:
- Cart
- Checkout
- Checkout success/thank-you pages
- Login
- Registration
- Password reset
- Account dashboard
- Orders
- Wishlist
- Payment callbacks
- Admin
- Internal APIs
- Other private/session-specific pages

Exclude all such pages from XML sitemaps.

---

# 8. INDEXATION, CANONICAL AND FACETED NAVIGATION

## 8.1 Indexation policy

Index only pages that deserve organic search traffic:
- Homepage
- Valuable categories
- Valuable subcategories
- Valuable public products
- Valuable brands where applicable
- About
- Contact
- FAQ
- Shipping
- Returns/refunds
- Privacy
- Terms
- Other useful public help/legal pages
- High-quality editorial pages only when they exist

Do not index:
- Search results
- Cart
- Checkout
- Account
- Login
- Registration
- Password reset
- Order details
- Admin
- Internal API pages
- Empty categories
- Unpublished products
- Thin tags
- Sort-only URLs
- Tracking URLs
- Session URLs
- Duplicate filtered URLs
- Soft 404 pages
- Broken/redirected pages

## 8.2 Canonical policy

Implement one canonical URL per indexable page.

Requirements:
- Use absolute HTTPS URLs.
- Use one preferred production host only, determined from actual deployment configuration.
- Do not assume www or non-www; inspect existing production behavior and configuration.
- Redirect alternate hosts/protocols consistently only if deployment controls support it.
- Preserve existing trailing slash conventions.
- Remove tracking parameters such as `utm_*`, `gclid`, `fbclid` and similar from canonicals.
- Ensure sorting and filtering do not create duplicate canonical pages.
- Do not canonicalize page 2+ of a paginated category to page 1 if page 2+ shows distinct products.
- Use self-referencing canonicals for paginated pages where they should remain crawlable.
- Never canonicalize unrelated pages to the homepage.
- Avoid canonical loops.
- Do not canonicalize distinct products to categories.

## 8.3 Filter/faceted navigation

This is important for ecommerce.

Audit actual filter/sort behavior.

Create a controlled strategy:
- Keep customer filtering usable.
- Avoid creating unlimited crawlable parameter combinations.
- Use `noindex, follow`, canonicalization, URL parameter controls, or route-level handling for non-strategic combinations.
- Only create indexable facet/landing pages if all conditions are met:
  - Genuine demand
  - Sufficient product inventory
  - Stable clean URL
  - Unique useful title
  - Unique useful description/content
  - Clear user benefit
  - Correct canonical
  - No duplication
- Do not generate mass city, color, size, brand/filter, price or attribute pages.
- Do not use doorway-page patterns.

## 8.4 Pagination

- Use crawlable HTML pagination links.
- Do not rely solely on infinite scrolling.
- Ensure all products can be discovered through links.
- Preserve filter/search usability.
- Ensure pagination state does not create broken canonicals.
- Keep page URLs stable.

---

# 9. ROBOTS.TXT AND META ROBOTS

Implement or improve a public `/robots.txt`.

Requirements:
- Return HTTP 200 with valid plain text.
- Reference the production sitemap URL.
- Allow crawling of public product, category, CMS, CSS, JavaScript and image resources needed for rendering.
- Do not block CSS, JS, or images required for Google rendering.
- Disallow only appropriate private/non-public paths based on actual routes.
- Do not rely on robots.txt as the sole mechanism for deindexing pages.
- Use `noindex` on page responses where appropriate.
- Ensure robots rules do not accidentally block pages that are intended to be indexed.

Use dynamically configured primary domain in sitemap reference:
`Sitemap: {APP_URL}/sitemap.xml`

Do not hard-code a wrong host.

---

# 10. XML SITEMAP SYSTEM

Create or improve a dynamic XML sitemap system.

Primary public endpoint:
`/sitemap.xml`

Use a sitemap index if appropriate.

Potential child sitemaps:
- `/sitemap-pages.xml`
- `/sitemap-categories.xml`
- `/sitemap-products.xml`
- `/sitemap-brands.xml` only when valid public brand pages exist
- `/sitemap-images.xml` only if beneficial and implemented accurately

Requirements:
- Include only public, canonical, indexable URLs returning 200.
- Use absolute canonical URLs.
- Use accurate `lastmod` values from meaningful content updates.
- Exclude noindex pages, duplicate URLs, parameter URLs, search, cart, checkout, accounts, auth, admin, private pages, redirected URLs, soft 404s, unpublished items and empty/thin pages.
- Support pagination/chunking for larger catalogs.
- Use cache safely.
- Invalidate cache or update sitemap data when a product, category, CMS/legal page or brand becomes published/updated/unpublished.
- Validate XML formatting.
- Add sitemap URL to robots.txt.
- Add automated tests for sitemap contents.
- Make the sitemap ready for Search Console submission.

Do not include URLs simply because they exist. Include URLs that deserve indexing.

---

# 11. STRUCTURED DATA / JSON-LD

Generate JSON-LD server-side from trusted model/configuration data.

All schema must:
- Match visible page content.
- Use valid absolute URLs.
- Avoid false/empty fields.
- Be omitted if required data is absent.
- Be safely JSON encoded.
- Be tested in appropriate structured-data validators.

## 11.1 Site-wide schema

Use only where data exists:

1. Organization
- `name`: ShopPulss
- `url`
- `logo`
- `sameAs`: only verified social URLs
- `contactPoint`: only if accurate/configured
- Address: only if accurate/configured
- Do not invent legal identifiers or social profiles.

2. WebSite
- `name`
- `url`
- `potentialAction` SearchAction only when the public search endpoint works and uses a valid query parameter.

3. WebPage
- Use where appropriate and non-duplicative.

## 11.2 Breadcrumb schema

Implement `BreadcrumbList` on:
- Category pages
- Subcategory pages
- Product pages
- Relevant information pages where visible breadcrumbs exist

Rules:
- Visible breadcrumb UI and JSON-LD must match.
- Use actual page labels and URLs.
- Do not create schema breadcrumbs for hidden navigation.

## 11.3 Product schema

For eligible public product pages:
- `@type: Product`
- `name`
- `description`
- `image`
- `sku` if available
- `mpn` if available
- `gtin` only if real
- `brand` only if real
- `offers` with:
  - `price`
  - `priceCurrency` (PKR only when correct)
  - `availability`
  - `url`
  - `itemCondition`
- Shipping and return policy schema fields only when they are current, verified and match visible details.
- `aggregateRating` and `review` only for genuine, visible reviews.

Do not show “InStock” if the product cannot be bought.
Do not create fake ratings or reviews.
Do not mark up invisible information.

## 11.4 Collection/category schema

Use `CollectionPage` or `ItemList` only if it accurately reflects visible content and does not create unnecessary schema volume.

## 11.5 FAQ schema

Use `FAQPage` only on the dedicated FAQ page and only for visible Q&A content.

---

# 12. ON-PAGE CONTENT, KEYWORDS AND INTERNAL LINKING

## 12.1 Keyword strategy

Use intent-led, natural keywords derived from actual catalog data.

Potential patterns:
- `ShopPulss`
- `ShopPulss Pakistan`
- `online shopping in Pakistan`
- `buy {product} online in Pakistan`
- `{product} price in Pakistan`
- `{category} online in Pakistan`
- `{brand} {product} Pakistan`
- Model numbers and specifications where accurate

Rules:
- Do not keyword stuff.
- Do not repeat “best,” “cheap,” “Pakistan,” “online,” or the brand excessively.
- Do not create pages for misspellings, city names, minor attributes or arbitrary filters.
- Use the brand consistently as `ShopPulss`.
- Do not randomly alternate brand spellings.
- Use SEO titles/descriptions as helpful page summaries, not keyword lists.

## 12.2 Category copy

Category and subcategory text must:
- Be original and user-focused.
- Describe real available products.
- Explain relevant choice factors and use cases.
- Link naturally to valid subcategories.
- Avoid generic repeated templates.
- Be concise where the catalog is small.
- Never push products below long irrelevant SEO content.

## 12.3 Product copy

- Preserve product accuracy.
- Prefer original concise descriptions and specifications.
- Do not plagiarize manufacturer copy where possible.
- Do not invent technical information.
- Include user-useful context from verified product fields.
- Use image alt text that describes the actual visual, not stuffed SEO terms.

## 12.4 Internal linking

Ensure no meaningful page is orphaned.

Header:
- Use regular crawlable HTML `<a href>` links.
- Link to important main categories without overloading navigation.

Homepage:
- Link to available categories, selected product collections and important trust/support pages.

Category pages:
- Link to subcategories, real product listings and relevant categories.

Product pages:
- Link to visible breadcrumbs, parent categories/subcategories and genuinely relevant products.

Footer:
Organize logically and avoid link spam.

Suggested groups:
- Customer Service: Contact, FAQ, Order Tracking if available
- Policies: Shipping, Returns & Refunds, Privacy, Terms, Payment, Warranty, Cookie Policy
- Company: About, Accessibility
- Shop: a limited selection of important real categories

Do not place hundreds of product or keyword links in the footer.

---

# 13. IMAGE SEO, PERFORMANCE AND CORE WEB VITALS

Improve performance only where safe and consistent with current tooling.

Requirements:
- Add meaningful alt text to meaningful images.
- Decorative images must have empty alt attributes.
- Include width and height or aspect ratio handling to reduce layout shift.
- Use responsive image sizes.
- Use current project image optimization/storage/CDN practices.
- Use WebP/AVIF only where current infrastructure supports them.
- Lazy load below-the-fold images.
- Do not lazy-load the LCP image.
- Preload or increase fetch priority for the actual LCP image when justified.
- Avoid bloated third-party scripts.
- Defer non-critical JavaScript where safe.
- Avoid render-blocking resources where possible.
- Use Blade server-side rendering for critical content.
- Preserve or improve caching, Redis use, eager loading and query efficiency.
- Watch for N+1 queries on category/product/sitemap pages.
- Avoid layout shifts on product cards, images, banners and loading content.
- Do not add heavy JavaScript only to make pages “SEO friendly.”

Performance goals are directional:
- Good mobile usability
- Better LCP
- CLS controlled
- Better INP
- Better TTFB
- Efficient images
- Under-3-second 4G experience where realistically supported by hosting, assets and real catalog data

Do not falsely claim performance targets were met without measurement.

---

# 14. ACCESSIBILITY, SECURITY AND QUALITY

Accessibility:
- Semantic landmarks: header, nav, main, section, article, footer.
- Exactly one meaningful H1 per page.
- Correct heading hierarchy.
- Keyboard-accessible menus, FAQ accordions, forms and interactive controls.
- Visible focus styles.
- Sufficient contrast using existing design system.
- Accessible form labels and inline error messaging.
- Proper button/link semantics.
- Respect reduced motion where relevant.
- Responsive layouts and tap targets.
- Do not hide meaningful content from mobile users.

Security:
- Preserve CSRF protections.
- Preserve authorization checks.
- Escape output.
- Sanitize rich CMS content.
- Use rate limiting for public contact forms.
- Avoid sensitive data in logs.
- Preserve secure cookies, HTTPS/HSTS/security headers where already configured.
- Do not expose admin or internal routes.
- Do not weaken validation or authentication for SEO features.

---

# 15. ADMIN SEO MANAGEMENT

First check what SEO management already exists.

Do not recreate existing admin functionality.

Improve existing product/category/CMS administration where needed so authorized admins can manage:

Products:
- SEO title
- SEO description
- Canonical URL if manually needed
- Slug
- Index/noindex status where policy permits
- Primary image alt text
- Product description/specifications

Categories:
- SEO title
- SEO description
- Slug
- Category introduction/description
- Category image alt text
- Index/noindex status

CMS/legal pages:
- SEO title
- SEO description
- Slug
- Canonical URL if needed
- Robots directive
- OG image
- Last updated/effective date
- Footer visibility
- Sitemap inclusion

Add non-blocking guidance:
- Missing title warning
- Missing description warning
- Excessively long title/description warning
- Missing canonical warning where appropriate
- Missing slug warning
- Missing alt text warning
- SEO preview showing title, URL and description

Do not force arbitrary length rules. Prioritize clarity and accuracy.

---

# 16. ANALYTICS, SEARCH CONSOLE AND MERCHANT CENTER READINESS

Do not introduce analytics tracking without checking existing tools and consent requirements.

If analytics already exists:
- Audit that it does not expose PII.
- Preserve its architecture.
- Do not duplicate events.
- Ensure scripts do not materially harm performance.
- Respect consent controls where needed.

Document a future-ready ecommerce measurement plan for:
- `view_item`
- `view_item_list`
- `search`
- `add_to_cart`
- `remove_from_cart`
- `view_cart`
- `begin_checkout`
- `add_shipping_info`
- `add_payment_info`
- `purchase`

Do not send:
- Passwords
- Payment credentials
- Raw personal data
- Sensitive address/account details

Create/update documentation at:
`docs/seo-growth-plan.md`

Include:
1. Google Search Console property verification.
2. Sitemap submission.
3. URL Inspection usage.
4. Monitoring indexing, coverage, rich result reports and Core Web Vitals.
5. Google Analytics/GTM configuration only after owner approval.
6. Merchant Center readiness and requirements.
7. Product feed readiness, only if existing catalog data supports it.
8. Merchant Center fields:
   - id
   - title
   - description
   - link
   - image_link
   - availability
   - price
   - condition
   - brand
   - gtin only where valid
   - mpn only where valid
9. Genuine customer review process.
10. Ethical link-earning strategy:
   - Supplier/manufacturer relationships
   - Useful buying guides
   - Relevant Pakistani publications
   - Product reviewers/influencers where genuine
   - Digital PR
   - Verified social profiles
   - Real business citations when eligible
11. Explicitly prohibit:
   - Link buying
   - PBNs
   - Fake directories
   - Fake reviews
   - Spam outreach
   - Automated backlink generation

Create a 30/60/90-day measurement checklist focused on:
- Index coverage
- Crawl errors
- Duplicate/canonical issues
- Search impressions
- CTR
- Organic landing pages
- Product rich results
- Merchant listing status
- Conversion quality
- Core Web Vitals
- High-intent query opportunities

Do not make ranking guarantees.

---

# 17. REDIRECTS, 404 AND LIFECYCLE MANAGEMENT

## 17.1 404 page

Create or improve the 404 page:
- Return a true HTTP 404 status.
- Use `noindex`.
- Match ShopPulss design.
- Clearly explain the page is unavailable.
- Offer search, homepage link and selected real categories.
- Do not return 200 for invalid URLs.

## 17.2 Slug redirects

If the project supports changing slugs:
- Implement or preserve old-slug history for products, categories and CMS pages.
- Return 301 from old public URL to current canonical URL.
- Avoid duplicate redirect records.
- Avoid chains.
- Ensure redirects preserve no sensitive/query state unnecessarily.
- Test status and target.

## 17.3 SEO lifecycle

Products:
- Draft: not public/not in sitemap/not indexable.
- Published: indexable when useful.
- Temporarily out of stock: accurate availability and alternatives.
- Permanently discontinued: redirect, retain with alternatives, 404 or 410 based on actual value.

Categories:
- Empty/unpublished/thin: do not automatically index.
- Valuable populated categories: index and include in sitemap.

CMS/legal pages:
- Draft/unpublished pages must not be publicly indexable.
- Published public pages can be indexed where appropriate.

---

# 18. TESTING AND VALIDATION

Use the project’s existing test style and Laravel Boost workflow.

Run relevant existing tests and create focused automated tests for all new critical functionality.

At minimum test:

## Homepage
- HTTP 200
- One title
- Meta description
- Canonical
- H1
- Organization schema where configured
- WebSite schema where configured

## Category
- HTTP 200
- SEO title/description fallback or override
- Canonical
- H1
- Visible breadcrumbs
- Breadcrumb JSON-LD
- Crawlable product links
- Correct noindex behavior for empty/thin/non-public categories

## Product
- HTTP 200 for public product
- Title
- Description
- Canonical
- H1
- Product JSON-LD where data exists
- Accurate price/currency/availability mapping
- Breadcrumbs
- Correct behavior for draft/out-of-stock/discontinued states

## Legal/CMS pages
- HTTP 200 when published
- Correct title
- H1
- Canonical
- Last updated display
- Correct index/noindex state
- Footer links where configured

## FAQ
- Accessible accordion structure
- Visible Q&A content
- FAQ schema only where valid

## Robots
- HTTP 200
- Valid plain text behavior
- Sitemap reference
- No accidental block of public catalog URLs

## Sitemap
- HTTP 200
- Valid XML
- Only canonical public indexable URLs
- Excludes private/noindex/parameter/search/redirect URLs
- Uses correct absolute production URLs

## Redirects
- Old slug returns 301
- Final destination is canonical
- No redirect chain where testable

## 404
- Invalid URL returns genuine 404
- Includes noindex behavior

## Contact form
- Validation
- CSRF
- Rate limiting
- Spam/honeypot behavior
- Success/failure response
- No sensitive logging

Before completing:
- Run formatters/linters configured by the repository.
- Run focused test suite.
- Run relevant full test suite if time/resources allow.
- Fix all high-priority errors.
- Use Laravel logs and test output to verify no new exceptions.
- Do not claim an external Google Rich Results Test has been run unless you actually have external access; instead ensure the output is ready for validation and document the exact URLs/pages to test after deployment.

---

# 19. GIT AND GITHUB DELIVERY REQUIREMENTS

Before any Git operation:
1. Inspect `git status`.
2. Inspect current branch name.
3. Inspect remote configuration with `git remote -v`.
4. Do not overwrite or discard unrelated user changes.
5. Do not force push.
6. Do not amend an existing commit unless explicitly requested.
7. Do not commit `.env`, secrets, credentials, generated private files or unrelated artifacts.
8. Ensure `.gitignore` is respected.
9. Verify all intended code/config/docs/tests are included.
10. Run tests before committing.

Git workflow:
- Create a dedicated branch if permitted by the environment, using a clear name such as:
  `feat/legal-pages-and-seo-foundation`
- Make logically organized commits. Prefer one clean implementation commit or a small set of focused commits rather than dozens of noisy commits.
- Suggested commit message:
  `feat: add legal pages and ecommerce SEO foundation`
- Include implementation code, migrations, tests, documentation, configuration examples and templates as appropriate.
- Do not include any secret configuration values.

Push requirements:
- Push the completed branch to the configured GitHub remote only after all tests and checks pass.
- If GitHub authentication, remote access, push permissions, branch protection, merge conflict, or CI restrictions prevent push:
  - Do not bypass security controls.
  - Do not force push.
  - Clearly report the exact blocker.
  - Leave the branch and commit in a clean state.
  - Provide the exact branch name, commit hash and command needed to push after access is granted.

If direct push to the default branch is required by repository policy, inspect the policy first. Never push directly to `main`/`master` if branch protection or project instructions indicate a pull request workflow.

After successful push:
- Report remote name.
- Report branch name.
- Report commit hash.
- Report whether a pull request still needs to be opened.
- Do not claim a successful push unless the push command has actually completed successfully.

---

# 20. FINAL IMPLEMENTATION REPORT

After all work is complete, provide a precise final report with these sections:

1. Audit summary
- Existing SEO/legal capabilities found
- Gaps identified
- Architecture reused

2. Files created
- List all created files

3. Files modified
- List all modified files

4. Database changes
- Migrations, new fields, indexes, data migrations or seeders

5. Routes
- New or updated public routes
- Canonical URLs used
- Any redirects created

6. Legal/trust pages
- Every page created or updated
- Whether content is CMS-managed/config-managed
- Any placeholders requiring owner/legal review

7. SEO implementation
- Metadata system
- Canonicals
- robots/noindex rules
- Sitemaps
- Open Graph/Twitter
- Internal linking
- Pagination/facet strategy
- 404 and redirects
- Image SEO
- Performance changes
- Structured data types

8. Indexation map
Provide a concise route-type table:
| Route type | Index? | Canonical? | Sitemap? | Notes |

9. Tests and validation
- Tests created
- Tests run
- Test results
- Lint/format results
- Any limitations

10. Manual owner actions before production launch
Clearly list:
- Business/legal name
- Support email
- Support phone/WhatsApp
- Physical address if applicable
- Social URLs
- Actual shipping coverage
- Shipping fees
- Delivery timelines
- Active courier partners
- Active payment methods
- Return/refund rules
- Warranty rules
- Legal governing-law text
- Analytics/GTM consent decisions
- Search Console verification
- Sitemap submission
- Merchant Center setup
- Rich Results validation
- Performance measurements on production

11. Git/GitHub delivery
- Current branch
- Commit hash
- Remote
- Push result
- PR status
- Any blockers

---

# 21. FINAL QUALITY BAR

Do not declare the work complete until:
- Existing ecommerce functionality remains intact.
- Legal/trust pages are usable, readable and configurable.
- Footer/navigation links are correct.
- Public indexable pages have correct metadata and canonicals.
- Private/user-specific pages are controlled with noindex and omitted from sitemaps.
- Sitemap and robots.txt are valid.
- Product/category structured data is truthful and visible.
- No fake claims, fake reviews, fake schema or SEO spam was added.
- New pages are mobile-friendly and accessible.
- Tests pass or remaining failures are explicitly documented.
- Git changes are committed cleanly.
- The configured GitHub remote push is attempted safely and reported truthfully.

Build an excellent long-term foundation for ShopPulss. Prioritize customer trust, useful product discovery, clean architecture, legitimate SEO, page performance, mobile usability, accessibility and maintainability over shortcuts.