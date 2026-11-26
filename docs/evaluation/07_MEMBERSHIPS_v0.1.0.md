# Memberships & Entitlements Evaluation v0.1.0

Project: Voelgoed Platform Consolidation (VG Nova)
Assessment date: 2026-11-19, Africa/Johannesburg
Source baseline: `main` at `454346d9e6a2c36864a4bbd944e52596f16e09c1`
Runtime target: LocalWP `vgnova-local-10039`, `http://localhost:10039`
Status: Read-only research. No users, memberships, subscriptions, orders, enrolments, intents, messages, settings, plugin files, or Bricks records were changed.

## 1. Executive Summary

The ownership model is clear in the approved architecture: WooCommerce Subscriptions bills; PMPro grants membership access; Member Perks evaluates configured benefits; WooCommerce owns products and checkout; Masteriyo owns course access. Current configuration maps seven PMPro levels to seven variable-subscription products, with fourteen published monthly/yearly variations carrying ZAR regular and sale prices. **The seven parent products are drafts, and all seven PMPro levels have direct signups disabled.** The configuration therefore does not establish an available public membership checkout.

The zero PMPro `initial_payment` and `billing_amount` values do not mean the tiers have no prices. The mapped Woo variations currently hold prices from R24/month to R344/month and R240/year to R3,440/year after sale pricing. The runtime currency is ZAR. No payment or order was attempted.

Member Perks currently has hub display enabled and two active programme mappings, but Woo discounts, initial subscription discounts, course claims, ticket claims, Woo Blocks compatibility, and notifications are disabled. Fifteen `vg_perk` records are published and five are drafts. The published records include ten course-access perks and two product perks; those records are configuration data, not proof of approved or active customer benefits. No Member Perks Woo/course execution was verified.

Three consequential gaps remain:

- **CONFLICT — Member Perks runtime/source drift.** An independent SHA-256 comparison reproduced the 59 changed shared files, 43 runtime-only files, and 98 source-only test files reported in `02_PLUGIN_INVENTORY_v0.1.0.md`. Runtime-only membership identities, mapping validation, claim availability, and cache/source-change code alter effective behavior. The approved runtime artifact is unknown.
- **INFERRED, conditional — cancellation can split access from billing.** The installed My Voelgoed membership service builds its cancellation action from the PMPro cancel page. PMPro’s cancel page cancels PMPro-native subscriptions or changes PMPro access; the installed PMPro Woo add-on listens for Woo order/subscription events to grant or remove PMPro access, but contains no reverse hook to cancel a Woo Subscription when a PMPro cancellation is requested. If the membership-card action is rendered and used for a Woo-billed tier, billing may continue after access is removed. The page binding was not inspected. My Voelgoed’s separate subscription service exposes Woo-native cancellation actions, which are the billing-owned path.
- **INFERRED — the My Voelgoed Lede-area link fails closed.** Its dashboard service requires a public Member Perks feature-flag accessor. The installed Member Perks `Plugin` exposes `hub_access()` but no recognized flag accessor, while the dashboard registry constructs the service without an injected resolver. With the current flag set to on, the service still returns a hidden card. The current rendered Bricks binding was not inspected.

Current PMPro page/category access tables contain zero restriction rows. PMPro can restrict individual pages and configured taxonomies, but this inspection found no configured restrictions in those tables. The Member Perks hub has a separate login and active-programme gate. Actual page rendering and restricted-content presentation remain unverified.

The latest VGN-18 comment records a historical guest checkout view for product 1935 at R149 sale / R199 regular, with no payment methods shown and no order submitted. The current product metadata still matches those prices. The latest VGN-5 comment records basic Journey Intent start, dismiss, refresh, and return checks, but no membership purchase or commerce transition. These comments are historical evidence, not current journey verification.

## 2. Inspection Scope and Evidence

### Scope and boundaries

I fully read `docs/evaluation/COORDINATION_BRIEF_v0.1.0.md` and `docs/evaluation/PARENT_BASELINE_v0.1.0.md`, then inspected the listed current-source and current-runtime membership materials. The root and nested instructions were applied. The source worktree was at the stated main SHA; the Member Perks submodule was clean at `972a279be7a753594888e974132562e1cc099e24`.

Current WordPress configuration reads used WP-CLI with regular plugins and themes skipped, after statically inspecting the sole MU plugin. Each database-backed read was serialized with the shared exclusive lock `/tmp/vgnova-evaluation-read.lock`. The LocalWP assignment-only `.envrc` was sourced without printing its contents. The successful read path used PHP 8.5.12 and the LocalWP socket registered for this target. Queries were limited to plugin options, seven PMPro level definitions and level mappings, product/variation metadata, published/draft course and perk configuration, account page IDs, and aggregate PMPro restriction counts. They did **not** query user, membership-user, subscription, order, enrolment, claim, redemption, attendee, or email records.

The WP-CLI invocation pattern was:

```sh
flock -x /tmp/vgnova-evaluation-read.lock /bin/bash
source '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/.envrc'
wp --path='/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public' \
  --skip-plugins --skip-themes --url='http://localhost:10039' eval '<read-only aggregate PHP>'
```

The first ordinary WP-CLI read, before using LocalWP’s PHP/socket environment, returned `Error establishing a database connection`; it did not alter data. The LocalWP environment retry returned the expected `siteurl` `http://localhost:10039`. The skipped-plugin bootstrap still loads MU plugins; its sole MU plugin was inspected statically first. No frontend request, scheduled job, migration, or write path was invoked.

### Evidence reviewed

- Current runtime plugin inventory and selected setting evidence in `docs/evaluation/02_PLUGIN_INVENTORY_v0.1.0.md`.
- Current PMPro/Woo product mapping, price, level, and account-page reads under the lock described above.
- Installed PMPro core 3.9.1, PMPro WooCommerce Add On 1.11, WooCommerce 11.3.0, WooCommerce Subscriptions 9.4.1, Member Perks 0.18.0, and Masteriyo 3.5.1 source/runtime files.
- Member Perks source instructions and current source checkout, plus an independent full relative-path and SHA-256 comparison against the runtime plugin folder.
- Project billing architecture, VS1 membership-commerce gate, locked VS1 product journey spec, VGN-5B handoff, and dashboard staging/QA documents. Dated or older records are labeled historical below.
- Read-only current Plane context for [VGN-18](https://app.plane.so/symphony-development/browse/VGN-18) and [VGN-5](https://app.plane.so/symphony-development/browse/VGN-5), including their latest comments available during this inspection.
- A bounded scanner over the eight runtime-only Fluent Snippets files reported in the plugin inventory. It returned keyword identifiers only, not source lines or credential-like values.

The dedicated Bricks MCP endpoint specified by the repository was not exposed as a callable tool. The available `mcp__Bricks_Voelgoed_Local` and `mcp__Voelgoed_BricksMCP_Frontend` tools are explicitly described as legacy aliases for the Novamira MCP server, so neither was used for Bricks inspection. I did not inspect persisted Bricks trees or current frontend output. No tests, checkout, payment, browser journey, Hallmark lint, or accessibility check was run; no UI was changed.

## 3. Component Inventory

| Component | ID/Slug/Path | State | Owner | Purpose | Consumers | Evidence |
|---|---|---|---|---|---|---|
| Paid Memberships Pro | `paid-memberships-pro`; levels 15–21; pages listed in §4 | Active, 3.9.1 | PMPro | Canonical membership levels and content access state | PMPro Woo add-on, Member Perks, Platform Core dashboard projection, PMPro page templates | RUNTIME_CONFIRMED; source inspected |
| WooCommerce | `woocommerce`; products 905, 908, 911, 914, 917, 920, 923 | Active, 11.3.0; mapped parents are drafts | WooCommerce | Product, cart, checkout, order and base price authority | PMPro Woo add-on, WCS, Masteriyo, Member Perks | RUNTIME_CONFIRMED |
| WooCommerce Subscriptions | `woocommerce-subscriptions` | Active, 9.4.1 | WooCommerce Subscriptions | Recurring billing and subscription lifecycle | PMPro Woo add-on, My Voelgoed SubscriptionService, Woo My Account | RUNTIME_CONFIRMED; source hooks inspected |
| PMPro WooCommerce Add On | `pmpro-woocommerce` | Active, 1.11 | PMPro/Woo integration | Maps Woo membership products/orders/subscriptions to PMPro levels; optional member-price filters | Woo products and order/subscription status hooks | RUNTIME_CONFIRMED; source inspected |
| Voelgoed Member Perks | Source `voelgoed-member-perks/`; runtime `wp-content/plugins/voelgoed-member-perks/` | Source 0.18.0 at `972a279`; runtime header also 0.18.0, tree CONFLICT | Member Perks | Perk configuration, membership eligibility, hub display, claims, redemptions, discount adapters | PMPro, Woo, Masteriyo, Tickera, Bricks, dashboard integration | CONFLICT; independent tree comparison in §6 |
| Masteriyo | `learning-management-system`; six published `mto-course` rows | Active, 3.5.1 | Masteriyo | Course, enrolment, progress and access authority | Woo course-product integration; Member Perks course-claim adapter; My Voelgoed LearningService | RUNTIME_CONFIRMED; no enrolment records read |
| My Voelgoed dashboard integration | Runtime `voelgoed-platform-core/includes/{Dashboard,Services,Bricks}/` | Runtime files present; relevant dashboard files are absent from tracked `site-code/voelgoed-platform-core/` | Platform Core by project policy; runtime provenance unresolved | Login guard and account projections for membership, subscriptions, orders, tickets, learning and member area | Pages 1155–1161; section template 1172 per historical dashboard handoff | RUNTIME_CONFIRMED code; CONFLICT/UNKNOWN source provenance and Bricks binding |
| PMPro page access tables | `wp_pmpro_memberships_pages`, `wp_pmpro_memberships_categories` | 0 page rows; 0 category rows | PMPro | Restrict individual pages and configured taxonomies to levels | WordPress content rendering and PMPro filters | RUNTIME_CONFIRMED aggregate; custom filters/UI unverified |
| Membership content pages | Levels 66, checkout 63, confirmation 64, invoice 65, billing 61, account 60, cancel 62, login 67, profile 68 | All mapped pages published | PMPro | Native membership/login/account routes | PMPro shortcodes/templates and cancellation flow | RUNTIME_CONFIRMED by selected options and page metadata |
| Woo account pages | Cart 72, checkout 73, My Account 74 | Published; My Account slug `my-account` | WooCommerce | Cart, checkout, orders and native subscription actions | WCS and Woo customer flows | RUNTIME_CONFIRMED |
| Programme hubs | Member Perks hub pages 991 (`my-vriendinneklub`) and 992 (`my-voelgoed-netwerk`) | Published; both configured to programme mappings | Member Perks/Bricks | Member-only benefit presentation | `vgmp_member_hub` shortcode and Bricks | RUNTIME_CONFIRMED settings/pages; presentation unverified |
| Fluent Snippets | Eight runtime-only snippets named in §6 | Runtime index marks published; no source counterpart found | Fluent Snippets/site integrations | Product-card JS, article tags, sponsor block, taxonomy/media glue, scheduled-action retention, product staff copy, Tickera date tokens | WordPress/Bricks/Woo hooks; exact membership consumers not found by bounded scan | RUNTIME_CONFIRMED; source provenance gap |
| PMPro gateways | Paystack selected; PayFast plugin active but unselected | Configured plugin state; no payment test | PMPro/gateway providers | PMPro-native checkout payment | PMPro checkout only; mapped Woo tier billing uses Woo | RUNTIME_CONFIRMED; credentials and transactions not inspected |
| Woo test gateway | `wpfi_woo_order_test` | Enabled in options; plugin documents admin-only use | Woo test gateway | Local test orders | Woo checkout/admin | RUNTIME_CONFIRMED option; guest visibility and payment not tested |

## 4. Current Architecture and Configuration

### Authority model

| Domain | Authority | Current integration | Limit |
|---|---|---|---|
| Recurring billing | WooCommerce Subscriptions | Variable-subscription parents and monthly/yearly variations | Parents are drafts; no subscription data or checkout was read |
| Membership access | PMPro | Seven PMPro levels; PMPro Woo maps Woo products and subscription events to levels | No membership-user records or activation journey inspected |
| Benefits and discounts | Member Perks | Reads PMPro profile and configured programme/level mapping; can resolve Woo/course/ticket perks | Woo discounts and course claims are disabled; exact runtime source is unknown |
| Products, cart and order prices | WooCommerce | Current product regular/sale/current price | No member-specific price override found; no live shopper view |
| Course access | Masteriyo | Four of six published courses have `_wc_product_id` links to published Woo products | Two courses, including 1889, have no current linked product; no access records read |
| Account projection | My Voelgoed/Platform Core | Reads PMPro and Woo Subscriptions; links to native provider pages/actions | Runtime dashboard service code is not in the tracked Platform Core module; current Bricks binding unavailable |
| Intent and return | Platform Core Journey Intent | Stores a minimal course target and server-resolves a safe canonical return | Current comments verify only basic intent interactions, not purchase or entitlement completion |

### Programme, level and Woo product mapping

The PMPro Woo option `_pmprowoo_product_levels` and the Member Perks ACF option rows map as follows. Every parent is a `Variable Subscription` product in `draft` status; its two variations are `publish`. Prices are ZAR. Each variation has a one-month or one-year period, interval 1, billing length 0 (no fixed end), zero signup fee and zero trial. `_membership_product_autocomplete` is `0` for all seven parents.

| Programme | Tier / PMPro level | Group | Woo parent | Monthly variation: regular → sale/current | Yearly variation: regular → sale/current |
|---|---:|---:|---:|---:|---:|
| Vriendinneklub | Brons / 15 | 4 | 905 | 906: R39 → R24 | 907: R390 → R240 |
| Vriendinneklub | Silwer / 16 | 4 | 908 | 909: R59 → R39 | 910: R590 → R390 |
| Vriendinneklub | Goud / 17 | 4 | 911 | 912: R99 → R79 | 913: R990 → R790 |
| Voelgoednetwerk | Brons / 18 | 5 | 914 | 915: R94 → R74 | 916: R940 → R740 |
| Voelgoednetwerk | Silwer / 19 | 5 | 917 | 918: R194 → R144 | 919: R1,940 → R1,440 |
| Voelgoednetwerk | Goud / 20 | 5 | 920 | 921: R299 → R224 | 922: R2,990 → R2,240 |
| Voelgoednetwerk | Platinum / 21 | 5 | 923 | 924: R444 → R344 | 925: R4,440 → R3,440 |

Current PMPro configuration confirms seven levels with `allow_signups=0`, `initial_payment=0`, `billing_amount=0`, no cycle period/number, no billing limit, no trial, and no expiry. Levels 15–17 are grouped in “Voelgoedvriendinne” group 4; levels 18–21 are grouped in “Voelgoednetwerk” group 5. Both groups have `allow_multiple_selections=0`. Each PMPro level’s `vg_membership_code`, programme key, level key and labels match the Member Perks mappings. Current mappings have both programmes and all seven levels enabled. The Member Perks programme `upsell_page_id` values are blank.

The PMPro level zero-price fields are not the Woo product price. The PMPro Woo add-on takes the Woo order line total when it writes the PMPro membership record; the current Woo variation regular/sale/current price fields hold the displayed/billed candidates. The parent drafts and disabled PMPro direct signups mean neither price metadata nor mapping alone proves the products can currently be purchased.

### Current Member Perks configuration and records

| Setting | Current value | Meaning and limit |
|---|---:|---|
| `vgmp_environment_mode` | `production` | Label does not match the LocalWP staging purpose; code effect not established |
| `vgmp_enable_hub_display` | `1` | Hub display config enabled; page rendering not tested |
| `vgmp_enable_runtime_status_cron` | `1` | Scheduling config enabled; execution not observed |
| `vgmp_enable_woo_discounts` | `0` | Member Perks Woo discount execution disabled |
| `vgmp_allow_initial_subscription_product_discounts` | `0` | Initial subscription product discount path disabled |
| `vgmp_enable_course_claims` | `0` | Masteriyo claim/enrolment path disabled |
| `vgmp_enable_ticket_claims` | `0` | Ticket claim path disabled |
| `vgmp_enable_woo_blocks_compat` | `0` | Woo Blocks compatibility path disabled |
| `vgmp_enable_notifications` | `0` | Member Perks notification path disabled |

The two configured programmes are `vriendinneklub` → page 991 and `voelgoednetwerk` → page 992. The hub service checks login and then the current PMPro profile. It returns `logged_out`, `dependency_missing`, `no_membership`, `programme_not_active`, or `can_view`; mapping errors fail closed. The shortcode renders an empty-state message when the visitor is logged out or not eligible. Current data does not supply a programme upsell page.

There are 15 published and five draft `vg_perk` records. The published set contains ten `course/free` entries, two product entries (one 20% product perk and one free product perk), one page/free entry, and two incomplete entries. Four draft entries are product percentage discounts and one is a free Woo-product-target entry. Published course perks target courses 532, 534, 536, 538 and 540; no published course perk targets Course 1889. These are staging records, some clearly QA-shaped by their slugs/configuration. They do not establish approved member offers. The current feature flags prevent treating them as active course/discount entitlements.

### Course/product links and base prices

Masteriyo’s installed Woo integration reads/writes the course `_wc_product_id` metadata. Current metadata links four of six published courses to published Woo products. The current Woo base prices are:

| Course ID | Linked Woo product | Current Woo product price | PMPro level-price override |
|---:|---:|---:|---|
| 532 | None | — | — |
| 534 | 1938 | R299 | None |
| 536 | 1937 | R449 | None |
| 538 | 1936 | R399 regular / R299 sale/current | None |
| 540 | 1935 | R199 regular / R149 sale/current | None |
| 1889 | None | — | — |

The scan found no non-empty PMPro `_level_{id}_price` overrides on products or variations. `_pmprowoo_member_discounts` is absent/null and `pmpro_pmprowoo_discounts_on_subscriptions` is absent/null; the installed PMPro Woo source defaults subscription/member discount behavior off when unset. This means no PMPro per-level/global price override was found in current settings. It does not prove a member’s rendered checkout price; no user account or cart was inspected.

### Payment and account settings

PMPro selects `paystack`; PayFast is active but not selected. Woo’s `wpfi_woo_order_test` setting is enabled, while COD and PayPal are disabled. Woo currency is `ZAR`; `woocommerce_force_ssl_checkout` is `no`. The test gateway is described as admin-only. The current shopper-facing payment methods were not checked. The 17 November VGN-18 comment saw no guest payment methods, which remains HISTORICAL_ONLY.

Current PMPro page IDs map to published pages: levels 66 (`membership-levels`), checkout 63 (`membership-checkout`), confirmation 64, invoice 65 (`membership-orders`), billing 61, account 60 (`membership-account`), cancel 62 (`membership-cancel`), login 67 (`login`), and profile 68 (`your-profile`). Woo My Account is page 74 (`my-account`); cart is 72 and checkout 73. My Voelgoed root page 1155 is published. The PMPro page/category restriction tables have 0 rows each. No user data or individual page rule IDs were read.

## 5. How It Works

### Membership lifecycle and side effects

The following is installed-source behavior, not a tested customer journey.

| State/transition | Guard in installed code | Side effect | Recovery/owner |
|---|---|---|---|
| Woo membership order reaches `completed` → PMPro level active | PMPro available; product-to-level option populated; order has a user and items; item is a mapped membership product | PMPro Woo creates a PMPro membership level record using the Woo order line total as `initial_payment`, preserving an existing active level start date; calls `pmpro_changeMembershipLevel()` unless the Multiple Memberships per User add-on function exists | Woo order and WCS remain billing authority; PMPro is access authority. No order was inspected or created. |
| WCS subscription becomes `active` or `on-hold → active` → PMPro level active | Subscription item resolves to a mapped membership product; user exists; mapping exists | PMPro Woo calls `pmpro_changeMembershipLevel()` (or MMPU add-level function) | WCS status is the billing source; PMPro access follows its hooks. No status transition was executed. |
| Renewal order reaches `completed` | If `wcs_order_contains_renewal()` and an active or pending-cancel WCS membership product already maps to that level, the add-on returns without duplicate grant | Avoids duplicate PMPro level records on ordinary renewal | Renewal and cross-level behavior remain untested. Confirm the guard against actual approved tier-change rules before launch. |
| Woo order becomes `refunded`, `failed`, or `cancelled` | Order contains mapped product; helper finds no other active/pending-cancel subscription for that PMPro level | PMPro Woo calls `pmpro_cancelMembershipLevel()` | Failure/refund grace and access policy are not business-approved here. No order records were read. |
| WCS subscription becomes `cancelled`, `trash`, `expired`, `on-hold`, or reaches scheduled end of prepaid term | Mapped product/user is found; helper finds no other active/pending-cancel subscription for that level | PMPro Woo removes the PMPro level | The access timing for `on-hold` and prepaid end should match the approved service policy; no status transition was tested. |
| Member clicks the PMPro cancellation URL from the My Voelgoed membership card | Runtime dashboard code sends `levelstocancel=<PMPro level>` to the PMPro cancel page. PMPro checks login, ownership, and a confirmation nonce. PMPro itself looks for PMPro-native subscription objects and either schedules end-date/cancels those subscriptions or calls `pmpro_cancelMembershipLevel()` immediately. | PMPro sends member/admin cancellation emails on successful confirmation. No PMPro Woo reverse hook was found to cancel the WCS subscription. | If this action is rendered for a Woo-billed tier, billing and access can diverge. Use the native WCS cancellation action for billing; reconcile PMPro access with WCS status. No cancellation was attempted. |
| Member uses the separate My Voelgoed SubscriptionService action | The runtime service obtains `wcs_get_all_user_actions_for_subscription()` and exposes native WCS `cancel`, `renew`, or `reactivate` URLs | WCS remains responsible for subscription operations | This is aligned with billing ownership. Actual Bricks binding and action rendering are UNKNOWN. |

PMPro’s group setup allows at most one selected level within each programme. PMPro Woo also rejects adding a second product from the same level group to the current cart. The observed code does not prove the user-facing rule for switching an existing member between tiers, prorating, or simultaneous membership in both programmes. Those remain product-policy questions.

### Benefit calculation and guest/member prices

Member Perks’ intended chain is PMPro active levels → canonical programme/level mapping → eligible configured perk → target/course resolution → selected benefit → Woo/cart or course-claim adapter. The source calculator uses the applicable Woo base price and a configured benefit type such as free, percentage, fixed discount, or fixed member price. The runtime Woo validator now requires canonical availability, eligibility, provider readiness, and a non-`per_order` scope before the Woo path treats a claim as allowed. The current `vgmp_enable_woo_discounts=0` and course-claims flag `0` disable those execution paths.

PMPro WooCommerce Add On is a separate potential discount provider. Its installed price filter considers the current user’s PMPro levels and, when allowed, membership levels represented in the cart. It can use the lowest `_level_{id}_price` on a product and the highest configured global level discount. It skips membership products/subscription product types unless its subscription-discount option is enabled. Current option and postmeta reads found no configured global or per-level PMPro member price. The PMPro Woo add-on remains necessary for Woo membership grants; its optional discount feature overlaps the project’s Member Perks discount authority if enabled later.

Guest price behavior is the Woo product regular/sale/current price. Current linked course products 1935 and 1936 have ordinary Woo sale prices. No evidence establishes a tier-specific course price. A member price must be calculated server-side from current PMPro status and an approved provider; URL, cookie, Bricks text, or Journey Intent state is not price authority. This follows the locked VS1 spec §§11–15 and 45.

### Gated content and account presentation

PMPro core checks its page and category access tables, checks active PMPro levels, blocks restricted feeds, and lets PMPro filters alter access/content presentation (`paid-memberships-pro/includes/content.php`, lines 55–150). Current page/category mapping tables contain zero rows, so this read found no PMPro-managed gated pages or categories. Custom hooks, taxonomy filter settings beyond those tables, Bricks conditions, and the content shown to a guest/member were not verified.

Member Perks hub access is separate: `HubShortcodes` emits a logged-out state; `HubAccess` checks current PMPro programme membership and fails closed for unavailable configuration or blocked mappings; eligible users can see the hub cards. The display flag is on and programme pages are mapped. No hub page tree or rendered empty state was inspected.

The runtime My Voelgoed `AccessGuard` protects `/my-voelgoed` and child paths, then redirects unauthenticated visitors through `wp_login_url()` with `wp_validate_redirect()` validation and a same-site dashboard fallback. Its membership service projects PMPro status and links to PMPro account/invoice/cancel; its subscription service projects WCS state and uses native WCS actions. The account pages are separate: PMPro `/membership-account/`, Woo `/my-account/`, and the custom `/my-voelgoed/` projection. The dashboard code is runtime-only relative to the tracked Platform Core module, and the Bricks account bindings were not inspected.

### Journey Intent and return

The locked spec and VGN-5B plan define one active course intent with states `ACTIVE`, `SUPERSEDED`, `DISMISSED`, `COMPLETED`, `INVALID`, and `EXPIRED`. A deliberate Course CTA starts/replaces the intent; ordinary page views do not. A guest intent stores a minimal signed/HTTP-only target; authenticated state is promoted to user metadata. The server validates the target and generates its canonical return URL. The intent stores no price, discount, tier, membership status, or raw return URL. “No Thanks” dismisses; a separate Close control only hides temporarily. Completion requires an authoritative membership/benefit outcome; a failed or cancelled checkout does not mark success.

Latest VGN-5 comment `d6b942d4-e0dd-4d9a-a73a-f57730a2ea87` (2026-11-17 07:50 UTC) records browser checks for valid start/dismiss/refresh/return and authenticated continuity, while noting no membership or Woo commerce changes and unverified two-authenticated-user isolation. It does not verify activation, cancellation, purchase, course enrolment, benefit price, or the post-membership return. VGN-5 remains open per the comment. The VGN-5B plan explicitly keeps checkout, entitlement and `COMPLETED` out of that slice.

## 6. Source Versus Runtime

### Member Perks — CONFLICT

The source checkout is clean at `972a279be7a753594888e974132562e1cc099e24`. Both source and runtime headers say 0.18.0, but a matching label is not parity. An independent full-tree comparison used relative paths and SHA-256 values and reproduced the counts in report 02:

| Set | Count | Membership-domain consequence |
|---|---:|---|
| Source files | 360 | Current nested source checkout |
| Runtime files | 305 | Installed local plugin copy |
| Shared paths | 262 | Compared by hash |
| Changed shared paths | 59 | Includes mapping/profile resolution, settings/config, hub, claim, target, Woo validation and plugin wiring changes |
| Source-only paths | 98 | All are test files or `tests/.gitkeep`; no source-only production PHP was found |
| Runtime-only paths | 43 | Includes active PHP for option submission, claim availability, mapping/config identities, caches, health projection, source-change tokens and coordination |

Runtime/source differences that affect this evaluation:

- `MembershipProfileResolver.php` uses runtime mapping identity/state and reports fail-closed states for unavailable or blocked mappings; source has a simpler mapping hash.
- `ProgrammeMappingRepository.php` adds explicit provider/mapping states, rejected/disabled/ambiguous lookup identities, and treats malformed/blocked mappings as blocking. This can change which levels qualify for a hub or benefit.
- `SettingsRepository.php` and `PerkConfigRepository.php` use runtime provider identities and expose additional save/status paths. ACF mapping/config changes can invalidate or alter identities.
- `WooApplicablePerkResolver.php` and `WooClaimValidatorAdapter.php` now require canonical claim availability (`available`, `eligible`, `provider_ready`) and reject `per_order` scope. A source-only review would not reproduce the active Woo claim/discount guard.
- Runtime-only `ClaimAvailabilityService`, `PerkRuntimeStatusEvaluator`, config/mapping save coordinators, source-change tokens, cache generations, health fences, and response policy are loaded/wired by the runtime plugin entrypoint. They can affect effective availability, cache invalidation, diagnostics, and mutations to runtime configuration.
- `HubShortcodes.php` and `HubServiceProvider.php` differ. The current hub feature flags/config are known, but exact behavior of this runtime implementation is not equivalent to the source checkout.

No current database setting was changed. The exact approved runtime commit/artifact and the business approval for its additional behavior remain UNKNOWN. This conflict affects both entitlement evaluation and how future benefit changes are applied; it is not a header-only difference.

### Exact differing paths

The following lists are the complete path sets from the independent comparison. `SOURCE_ONLY` paths are tests and do not execute in the runtime. Every `CHANGED_SHARED` and `RUNTIME_ONLY` path is named so the runtime/source boundary can be reconciled without relying on summary counts alone.

<details>
<summary>59 changed shared paths</summary>

```text
README.md
bin/check.sh
bin/sync-to-staging.sh
docs/development/vs-013-authority-amendments.md
docs/planning/VS-013_performance-and-cache-hardening_v1.4.0.md
docs/superpowers/plans/2026-09-15-vs-013c-runtime-identity-contracts.md
docs/superpowers/plans/2026-09-15-vs-013d-versioned-cache-foundation.md
docs/superpowers/plans/2026-09-15-vs-013e-health-projection.md
docs/superpowers/plans/2026-09-15-vs-013f-consumer-caches-privacy-diagnostics.md
docs/superpowers/specs/2026-09-15-vs-013-correctness-first-cache-architecture-design.md
includes/Admin/AdminToolsPage.php
includes/Admin/PerkAdminColumns.php
includes/Admin/PerkAdminFilters.php
includes/Admin/PerkDuplicator.php
includes/Admin/PerkQuickActions.php
includes/Admin/PerkValidationSummary.php
includes/Claims/ClaimService.php
includes/Claims/ClaimStatus.php
includes/Claims/ClaimValidator.php
includes/Claims/RedemptionRepository.php
includes/Claims/RedemptionTransitionService.php
includes/Claims/RestClaimController.php
includes/Config/PerkConfigRepository.php
includes/Config/SettingsRepository.php
includes/Database/PerkTargetTable.php
includes/Database/RedemptionTable.php
includes/Database/Schema.php
includes/Display/CoursePerkDisplayData.php
includes/Display/PerkClaimDisplayData.php
includes/Display/PerkDisplayFormatter.php
includes/Display/TicketPerkDisplayData.php
includes/Hub/HubActionResolver.php
includes/Hub/HubAssets.php
includes/Hub/HubServiceProvider.php
includes/Hub/HubShortcodes.php
includes/Masteriyo/CourseClaimController.php
includes/Masteriyo/CoursePerkService.php
includes/Membership/MembershipProfile.php
includes/Membership/MembershipProfileResolver.php
includes/Membership/ProgrammeMappingRepository.php
includes/Perks/PerkRuleRepository.php
includes/Perks/PerkRuntimeStatusUpdater.php
includes/Perks/PerkTargetIndexer.php
includes/Perks/PerkTargetRepository.php
includes/Plugin.php
includes/Query/BricksQueryProvider.php
includes/Query/CandidatePerkQuery.php
includes/Query/ClaimAwarePerkFilter.php
includes/Query/CurrentUserPerksQuery.php
includes/Query/QueryContextFactory.php
includes/Query/RestQueryController.php
includes/Tickera/TicketClaimController.php
includes/Tickera/TicketPerkService.php
includes/Tools/PerkHealthCheckTool.php
includes/Tools/ProgrammeMappingCacheTool.php
includes/Woo/WooApplicablePerkResolver.php
includes/Woo/WooClaimValidator.php
includes/Woo/WooClaimValidatorAdapter.php
voelgoed-member-perks.php
```

</details>

<details>
<summary>43 runtime-only paths</summary>

```text
docs/superpowers/plans/2026-09-19-vs-013b-claim-availability-authority.md
docs/superpowers/plans/2026-10-09-vs-013g-source-owner-health-fence-integration.md
includes/Admin/AcfProgrammeMappingSubmission.php
includes/Admin/ProgrammeMappingOptionSaveHooks.php
includes/Claims/ClaimAvailabilityResult.php
includes/Claims/ClaimAvailabilityService.php
includes/Claims/RedemptionInsertSourceChangeToken.php
includes/Claims/RedemptionTransitionSourceChangeToken.php
includes/Config/AcfPerkConfigSubmission.php
includes/Config/OptionOverlayConfigProvider.php
includes/Database/CacheGenerationTable.php
includes/Database/HealthInvalidationFenceTable.php
includes/Database/PerkHealthTable.php
includes/Database/SchemaStateVerifier.php
includes/Health/PerkHealthIssueState.php
includes/Health/PerkHealthProjectionRepository.php
includes/Health/PerkHealthProjectionResult.php
includes/Health/PerkHealthProjector.php
includes/Membership/MappingChangedSourceChangeToken.php
includes/Membership/MembershipProfileIdentity.php
includes/Membership/ProgrammeMappingIdentity.php
includes/Membership/ProgrammeMappingSaveCoordinator.php
includes/Membership/ProgrammeSurfaceIdentity.php
includes/Performance/CacheDiagnostics.php
includes/Performance/CacheGenerationStore.php
includes/Performance/CacheInvalidator.php
includes/Performance/CacheKeyFactory.php
includes/Performance/CacheSnapshotMetadata.php
includes/Performance/GlobalSourceMutationLock.php
includes/Performance/HealthInvalidationFenceStore.php
includes/Performance/SourceHealthChangeCoordinator.php
includes/Performance/VersionedCacheStore.php
includes/Perks/PerkConfigIdentity.php
includes/Perks/PerkConfigSaveCoordinator.php
includes/Perks/PerkConfigSaveHooks.php
includes/Perks/PerkConfigSaveSourceChangeToken.php
includes/Perks/PerkRuntimeStatusEvaluator.php
includes/Perks/PerkSourceMutationLock.php
includes/Perks/PerkSourceMutationLockInterface.php
includes/Perks/TargetIndexSourceChangeToken.php
includes/Support/CanonicalIdentity.php
includes/Support/MemberResponsePolicy.php
includes/Tools/CacheDiagnosticsTool.php
```

</details>

<details>
<summary>98 source-only paths</summary>

```text
tests/.gitkeep
tests/admin-ux/AdminUxTest.php
tests/admin-ux/bootstrap.php
tests/claims/ClaimLimitLockTest.php
tests/claims/ClaimLockContextTest.php
tests/claims/ClaimPeriodResolverTest.php
tests/claims/ClaimPolicyTest.php
tests/claims/ClaimServiceTest.php
tests/claims/RedemptionRepositoryTest.php
tests/claims/RedemptionTransitionPolicyTest.php
tests/claims/RedemptionTransitionServiceTest.php
tests/claims/RestClaimControllerTest.php
tests/claims/bootstrap.php
tests/hub-display/CurrentUserPerksQueryTest.php
tests/hub-display/HubAccessTest.php
tests/hub-display/PerkAudienceEvaluatorTest.php
tests/hub-display/QueryContextTest.php
tests/hub-display/RequestCacheTest.php
tests/hub-display/bootstrap.php
tests/hub/BricksHubDynamicDataTest.php
tests/hub/BricksQueryProviderTest.php
tests/hub/HubActionResolverTest.php
tests/hub/HubAssetDataTest.php
tests/hub/HubLoopActionDataTest.php
tests/hub/HubMessageMapperTest.php
tests/hub/HubPageResolverTest.php
tests/hub/HubProgrammeSettingsInspectorTest.php
tests/hub/HubRendererTest.php
tests/hub/HubShortcodeTest.php
tests/hub/bootstrap.php
tests/masteriyo/CourseClaimControllerTest.php
tests/masteriyo/CoursePerkDisplayDataTest.php
tests/masteriyo/CoursePerkServiceTest.php
tests/masteriyo/CoursePolicyTest.php
tests/masteriyo/MasteriyoDetectorTest.php
tests/masteriyo/PerkCourseWarningsTest.php
tests/masteriyo/bootstrap.php
tests/membership/EligibilityCheckerTest.php
tests/membership/MembershipProfileResolverTest.php
tests/membership/ProgrammeMappingRepositoryTest.php
tests/membership/bootstrap.php
tests/reports/CsvExporterTest.php
tests/reports/IssueDetectorTest.php
tests/reports/MembershipProfileReportTest.php
tests/reports/MisconfigurationReportTest.php
tests/reports/PerkPerformanceReportTest.php
tests/reports/PerkStatusReportTest.php
tests/reports/ProgrammeMappingDriftReportTest.php
tests/reports/ProgrammeReportTest.php
tests/reports/RedemptionReportTest.php
tests/reports/ReportAssetsTest.php
tests/reports/ReportDependencyStatusTest.php
tests/reports/ReportExporterTest.php
tests/reports/ReportFiltersTest.php
tests/reports/ReportFormatterTest.php
tests/reports/ReportIssueTest.php
tests/reports/ReportRepositoryTest.php
tests/reports/ReportsMenuTest.php
tests/reports/ReportsPageTest.php
tests/reports/bootstrap.php
tests/run-admin-ux-tests.php
tests/run-claims-tests.php
tests/run-hub-claim-ux-tests.php
tests/run-hub-display-tests.php
tests/run-masteriyo-tests.php
tests/run-membership-tests.php
tests/run-reports-tests.php
tests/run-runtime-status-tests.php
tests/run-target-index-tests.php
tests/run-tickera-tests.php
tests/run-woo-blocks-tests.php
tests/run-woo-tests.php
tests/runtime-status/PerkRuntimeStatusUpdaterTest.php
tests/runtime-status/TimeTest.php
tests/runtime-status/bootstrap.php
tests/target-index/PerkAudienceIndexExtractorTest.php
tests/target-index/PerkTargetIndexerTest.php
tests/target-index/PerkTargetNormalizerTest.php
tests/target-index/PerkTargetRepositoryTest.php
tests/target-index/PerkTargetTableTest.php
tests/target-index/bootstrap.php
tests/tickera/TickeraDetectorTest.php
tests/tickera/TicketCartServiceTest.php
tests/tickera/TicketClaimControllerTest.php
tests/tickera/TicketDisplayWarningsTest.php
tests/tickera/TicketPerkServiceTest.php
tests/tickera/bootstrap.php
tests/woo-blocks/WooBlocksCapabilitiesTest.php
tests/woo-blocks/WooBlocksCartUpdateTest.php
tests/woo-blocks/WooBlocksDetectorTest.php
tests/woo-blocks/WooBlocksIntegrationRegistrarTest.php
tests/woo-blocks/WooBlocksStoreApiSchemaTest.php
tests/woo-blocks/WooBlocksTickeraGateTest.php
tests/woo-blocks/WooBlocksWooDiscountIndependenceTest.php
tests/woo-blocks/bootstrap.php
tests/woo/WooMoneyPathTest.php
tests/woo/WooServiceMoneyPathTest.php
tests/woo/bootstrap.php
```

</details>

### Platform Core dashboard source boundary

The tracked `site-code/voelgoed-platform-core/` module contains Journey Intent and other current source areas, but it has no tracked `includes/Dashboard/*`, `includes/Services/MembershipService.php`, `SubscriptionService.php`, `MemberAreaService.php`, or dashboard Bricks adapter files. Those files are present in the installed runtime. The runtime `DashboardRegistry` wires MembershipService, SubscriptionService and MemberAreaService; `AccessGuard` registers a `template_redirect` guard. `docs/development/unified-dashboard-qa.md` and the staging audit are historical handoffs describing an earlier dashboard tree, its provider-native actions, and deferred hidden Lede-area link. The current runtime dashboard source-to-module provenance is therefore UNKNOWN/CONFLICT, even though report 02 confirms the Platform Core runtime matched its own finalized deployment manifest. Manifest parity is not proof that every runtime-only dashboard file is source-controlled under the current module contract.

### Eight runtime-only Fluent Snippets

The eight files below have no repository source counterpart in the report-02 scan. The bounded token scan looked for PMPro, member/membership, subscription, discount, price, login, account, intent, course/enrolment identifiers and Woo hook prefixes. It found no explicit membership, PMPro, subscription, price, discount, login, account, or intent identifier in any of the eight; `2-vg-article-dynamic-tags.php` contained only course terms. This is not proof that a generic product-card or content snippet cannot affect a future membership card indirectly, and no full frontend consumer map was available.

| Runtime file | Inventory-described surface | Scan result | Membership consumer status |
|---|---|---|---|
| `1-vg-product-card.php` | Woo product-card JavaScript, `wp_footer` | No membership/price/discount/account token | No explicit consumer found; product-card binding may be generic |
| `2-vg-article-dynamic-tags.php` | Bricks article/featured-content tags | Course token only | No explicit membership consumer found |
| `3-shortcode-voelgoed-vennoot-block.php` | Sponsor/partner shortcode | No membership token | No explicit consumer found |
| `4-taxonomy-posts-hide.php` | Hides native post categories | No membership token | No explicit consumer found |
| `6-taxonomy-posts-happyfiles.php` | HappyFiles and legacy `content_category` synchronization | No membership token | No explicit consumer found |
| `7-maintenance-action-scheduler.php` | Action Scheduler retention | No membership token | Indirect job-retention impact is UNKNOWN; no membership job consumer was mapped |
| `9-acf-product-copy.php` | Copies event staff into product ACF relationship | No membership token | No explicit consumer found |
| `9-bricks-tickera-event-date-time.php` | Tickera date/time tokens | No membership token | No explicit consumer found |

## 7. Completeness and Functionality

### Acceptance criteria

| Criterion | Result | Evidence/limit |
|---|---|---|
| Read the complete coordination brief and parent baseline | VERIFIED | Both documents read before scope work; exact entry point and no-write boundaries followed |
| Inspect safe current membership, billing, gateway, account and Member Perks configuration | VERIFIED for selected configuration | Serialized WP-CLI reads; no secrets, user rows, subscription rows or orders read |
| Map PMPro levels to current Woo products and prices | VERIFIED | Seven current mappings and fourteen variation price/period records; parents are drafts |
| Evaluate activation, cancellation, renewal guards and side effects | PARTIALLY_VERIFIED | Installed source paths and guards inspected; no order/subscription/member lifecycle executed or queried |
| Evaluate benefits, discount authority, guest/member price and course discounts | PARTIALLY_VERIFIED | Flags, perk aggregates, Woo prices and per-level override scan read; no approved offer decision or shopper/member price check |
| Evaluate account/login/My Voelgoed and gated-content presentation | PARTIALLY_VERIFIED | Current IDs and runtime code inspected; no Bricks tree/render or PMPro content request |
| Evaluate Journey Intent return and latest VGN-18/VGN-5 comments | PARTIALLY_VERIFIED | Latest comments read; comments are historical and do not prove commerce or completion |
| Independently compare Member Perks source/runtime | VERIFIED | Full-tree SHA-256 path comparison reproduced exact counts and paths |
| Check the eight snippet provenance gaps for explicit membership identifiers | PARTIALLY_VERIFIED | Bounded token scan found no explicit identifiers; indirect consumers remain unknown |
| Preserve read-only/no-user-data/no-commit/no-Plane-update boundary | VERIFIED | No runtime write, user/order query, commit, or Plane mutation performed |

Coverage is **PARTIAL** because the report has current configuration evidence but not customer-facing or lifecycle behavior evidence. No current records were inspected to infer actual members, subscriptions, or entitlements.

### Delivery-state separation

| Stage | Status | Evidence |
|---|---|---|
| SOURCE_MERGED | `454346d` confirmed for vgnova main; Member Perks source is clean at `972a279` | Repository baseline and nested checkout |
| RUNTIME_APPLIED | Membership plugins/settings and current products are installed/configured; exact Member Perks source artifact is UNKNOWN | Current local read; tree conflict in §6 |
| BEHAVIOR_VERIFIED | Not established | No checkout, activation, cancellation, renewal, discount, course claim, or Bricks display was executed |
| BUSINESS_APPROVED | Not established | No current tier/benefit/cancellation/grace-period approval evidence was found |
| LAUNCH_READY | Not established | Parent products are drafts; gateway visibility/payment, current UI, and membership lifecycle remain unverified |

## 8. Problems, Risks and Contradictions

| Priority | Evidence label | Finding and concrete impact | Current confidence/limit |
|---|---|---|---|
| P2 | INFERRED; conditional | My Voelgoed MembershipService offers PMPro cancellation URLs for active PMPro levels. PMPro cancellation source does not forward to Woo Subscriptions in the installed PMPro Woo add-on. If rendered and used for a Woo-billed tier, PMPro access can end while WCS billing stays active. | Runtime code and WCS-to-PMPro hooks are confirmed; dashboard binding and a cancellation outcome are unverified. The separate WCS-native subscription action exists. |
| P2 | CONFLICT | Member Perks runtime differs in 59 shared files and 43 extra paths from its clean source checkout. The differences include current membership mapping, eligibility, claim availability, Woo validation and cache/health code. | Exact artifact/provenance and approved behavior are unknown. This was independently reproduced, not functionally tested. |
| P2 | INFERRED | My Voelgoed’s MemberAreaService hides its card unless it can resolve an approved public feature flag. The current registry provides no resolver, and Member Perks exposes `hub_access()` but no recognized feature-flag accessor. | Current option is on and the code path appears to fail closed; persisted Bricks card binding/render was not inspected. Historical dashboard QA also says the link was deferred/hidden. |
| P2, conditional on acceptance requiring a sale | RUNTIME_CONFIRMED; public sellability INFERRED | All seven membership parent products are drafts and all PMPro level direct signups are disabled. The mapped monthly/yearly variation price records exist, but there is no verified public membership checkout path. | Current status is confirmed; no direct checkout test or mutation was permitted. |
| P2, conditional on expected protected content | RUNTIME_CONFIRMED | PMPro page/category access tables have no rows, so PMPro-managed page/category restrictions were not configured in the inspected tables. | Whether another filter, taxonomy, template condition or Member Perks hub is intended to gate content is UNKNOWN. |
| P3 / unresolved configuration | RUNTIME_CONFIRMED; effect UNKNOWN | Member Perks `environment_mode=production` on a local staging site. | No code path impact was established; do not treat this alone as a defect. |
| P3 / unresolved configuration | HISTORICAL_ONLY | VGN-18’s 17 November guest checkout displayed no payment methods. The current admin-only Woo test gateway setting is enabled. | No current guest checkout or payment-method rendering was checked. |
| UNKNOWN | UNKNOWN | No approved current benefit matrix or guest/member price rule was established. Published perks include QA-shaped entries while all relevant execution flags are off. | A published post is not business approval or proof of eligibility. |

The latest available VGN-18 comment `ac9fc988-d58d-4c40-97a9-be117bfa8e6f` (2026-11-17 15:39 UTC) says the guest checkout opened and showed product 1935 at R149, regular R199, but no payment methods appeared and no order was submitted. A preceding VGN-18 comment records the one-use `woocommerce_force_ssl_checkout` change to `no`; the current read sees `no`. Both are HISTORICAL_ONLY for behavior. The VGN-18 acceptance still calls for approved SKUs/tier semantics and paid member/non-member proof.

## 9. Redundancy Candidates

No deletion or deactivation is recommended by this research. Each item below needs owner decision, consumer evidence, a replacement path, and rollback planning before any change.

| Candidate | Observed overlap | Replacement/retention position | Prerequisites and rollback | Owner gate |
|---|---|---|---|---|
| PMPro Woo optional member-price feature vs Member Perks discount engine | Both can calculate membership-aware Woo prices. Current global/per-product PMPro discount config is empty and Member Perks Woo discounts are off. | Retain PMPro Woo for product/order/subscription-to-PMPro access. Keep optional pricing disabled until the approved discount authority is explicit; project architecture assigns configured benefits to Member Perks. | Scan all product/member-price metadata, identify Bricks and cart consumers, approve benefit matrix, test sale/coupon/subscription rules, and record a reversible configuration restore. | Membership product owner and Platform/Master Gate; no setting change authorized here. |
| PMPro account/cancel pages, Woo My Account and My Voelgoed projection | Three account surfaces expose membership, billing, order or summary functions. | Keep native PMPro and Woo routes for their provider-owned actions; use My Voelgoed as a projection only. Align cancellation links with the WCS billing authority. | Inspect current Bricks pages and redirects; verify login, owner checks, valid return URLs, WCS cancellation and PMPro entitlement timing; preserve provider route rollback. | Product owner and Platform Core owner. |
| Member Perks standalone hub pages and My Voelgoed Lede-area navigation | The programme pages exist and are mapped; the dashboard link is designed as a conditional entry point but currently fails closed without the public flag accessor. | Keep hub content and dashboard summary separate until the provider boundary is approved. Do not duplicate member benefit rules in Platform Core. | Resolve the public feature flag/API contract, verify current ACF option/page IDs and per-programme gating, capture rendered behavior and rollback. | Member Perks source owner and dashboard owner. |
| Eight published Fluent Snippets without repository source | Runtime behavior has no reviewable source baseline; product-card and maintenance snippets may have broad request/job consumers. | Preserve each runtime item until its source, consumer map, and replacement are established. No runtime deletion or overwrite. | Obtain source-controlled copies, compare hashes, scan hooks/Bricks callers, document rollback copies, then separately approve any replacement. | Site owner/editor and snippet owner. |

## 10. Recommendations

### NATIVE CAPABILITY REVIEW

| Capability | Checked and current result | Remaining limitation |
|---|---|---|
| `WORDPRESS_CORE` | Login, pages, hooks, and redirect validation support native account access and page routing. | Core does not own paid billing or membership entitlements. |
| `BRICKS` | Bricks is the presentation layer; dashboard dynamic-data and Member Perks hub adapters exist in runtime source. | Dedicated Bricks MCP/tree inspection was unavailable; template bindings and visual states are UNKNOWN. |
| `ACF_PRO` | Current Member Perks programme/level mapping and perk fields use ACF options/fields. | Runtime submission provider differs from source; no new fields or CPTs are proposed. |
| `WOOCOMMERCE` | Owns products, base/sale price, cart and checkout; current course links use `_wc_product_id`. | Membership parent products are drafts; no current buyer checkout verified. |
| `WOOCOMMERCE_SUBSCRIPTIONS` | Owns monthly/yearly recurring terms and subscription actions/status. | Cancellation from the PMPro membership card may bypass WCS; actual action binding unknown. |
| `PMPro` | Owns membership levels, user access state, and native page/category restrictions. | Direct signups are disabled; current PMPro page/category restriction tables are empty. |
| `Member Perks` | Owns perk eligibility/configuration and the intended benefit/claim path; hub is configured. | Pricing/course/ticket execution flags are off and the installed code differs from source. |
| `Masteriyo` | Owns course access; four published courses have Woo product links. | Course 1889 has no linked product or published course perk. No enrolment was inspected. |
| `OTHER_RELEVANT_PLUGIN` | PMPro Woo bridges mapped Woo product/recurring subscription events to PMPro access. | Its optional price feature overlaps Member Perks if enabled. |

`CUSTOM_DEVELOPMENT=NOT_PROPOSED`. Current findings call for authority, source provenance, and route verification; they do not justify a new subsystem.

### Priority follow-up

1. Resolve the cancellation control contract before any paid membership acceptance: every customer-facing cancel action must stop or schedule WCS billing through WCS and align PMPro access to the approved end-of-term/grace rule.
2. Have the membership owner approve the tier matrix, current variation prices, sale periods, eligibility and discount semantics. Confirm whether Member Perks is the sole benefit-price authority; keep both discount engines disabled until that authority is documented.
3. Reconcile the exact approved Member Perks runtime artifact against source and identify who authorized the 59 shared changes and 43 runtime-only files. Preserve the installed copy until an approved migration/rollback plan exists.
4. Decide whether `/my-voelgoed/` is the primary member summary and confirm the PMPro, Woo and programme-hub links. Expose the Lede-area link only through an approved public feature-flag boundary.
5. Recheck the dedicated Bricks MCP and inspect the persisted membership, hub, account, and checkout trees. Verify guest/member empty, error and eligible states, focus/keyboard behavior, and exact server-side price/return bindings when the tool is available.
6. Keep VGN-18 and VGN-5 acceptance separate: a Journey Intent return is not membership activation, course discount, successful payment, course access, or intent completion.

## 11. Outstanding Verification

- Exact approved Member Perks runtime source/artifact, source branch, release record, and business authorization for runtime-only behavior.
- Whether current Bricks account/dashboard/hub templates bind the runtime `MembershipService`, `SubscriptionService`, `MemberAreaService`, PMPro cancel link, or membership-price labels.
- Current user-facing member activation, renewal success/failure, on-hold grace, tier switch, refund, cancellation, and end-of-term access behavior. No users or subscription/order rows were read.
- Current guest and authenticated checkout payment methods, whether draft parents can be directly purchased, and exact member prices. No checkout or transaction was performed.
- Whether current published `vg_perk` records are approved offers, QA fixtures, or mixed; no business source was established.
- Whether course 1889 should use a Woo product, a Member Perks claim, or another approved access path. It currently has neither `_wc_product_id` nor a published course-target perk.
- Any current custom PMPro restriction filters, Bricks visibility rules, role mappings, or other content gates outside the two PMPro restriction tables.
- Current page-cache behavior, anonymous/authenticated personalization, and the VGN-5 two-user isolation case.
- A dedicated callable Bricks MCP endpoint. The available Bricks-named tools are legacy Novamira aliases and were not used for Bricks work.
- Current browser/UI and Hallmark/accessibility results. No UI was changed, so Hallmark lint was not run and no Hallmark verification is claimed.
- Current live ingress/reachability. It is outside this membership inspection and is not inferred from local configuration.

The no-write policy prevented live billing or account-transition verification. That is a scope boundary, not permission to infer success from configuration or older test evidence.

## 12. Final Domain Assessment

**Domain assessment:** The system has the expected division of authority in source and current configuration: Woo Subscriptions bills, PMPro controls access, Member Perks defines benefits, Woo owns prices, and Masteriyo owns course access. Seven price-bearing recurring tiers are mapped, but their parent products are drafts and direct PMPro signups are disabled. There is no verified member-price or course-claim path in the current configuration. Current PMPro content restriction tables are empty. My Voelgoed and Journey Intent provide projection/return structure, but membership activation and post-purchase completion have not been verified.

**Coverage:** PARTIAL.
**Confidence:** MEDIUM. Current configuration, product prices, mapping, source files, and code hooks were directly observed. Main uncertainties are the exact approved runtime code, persisted Bricks bindings, and unexecuted lifecycle behavior.
**Runtime verification:** PARTIAL — current selected runtime values and product metadata verified; frontend, checkout, payment, entitlement, cancellation, and renewal behavior not verified.
**Verification Status:** `PARTIALLY_VERIFIED`.

**Task type:** RESEARCH.
**Task / issue:** Read-only VGN-18 and VGN-5 membership/entitlement evaluation, resulting only in this assigned report.
**Assessment role:** Research.
**Implementation Score:** N/A. No implementation was requested or performed.
**Task Execution Score:** 8/10 — authority and scope 3/3; current evidence collection 3/4; synthesis and handoff 2/2. The evidence point is deducted for missing persisted Bricks inspection and unexecuted lifecycle/customer-facing price checks.
**Acceptance criteria:** 5 verified, 6 partially verified, 0 failed, 0 not done; the eleven criteria and evidence limits are listed in §7.

**Evidence:** Main source `454346d9e6a2c36864a4bbd944e52596f16e09c1`; Member Perks source `972a279be7a753594888e974132562e1cc099e24`; current LocalWP runtime `http://localhost:10039`; serialized WP-CLI reads and SHA-256 comparison described above; installed PMPro/PMPro Woo/WCS/Member Perks/Masteriyo source; live VGN-18 comment `ac9fc988-d58d-4c40-97a9-be117bfa8e6f`; live VGN-5 comment `d6b942d4-e0dd-4d9a-a73a-f57730a2ea87`.

**Known gaps / highest-risk assumption / next action:** The highest-risk assumption is that users will be directed to Woo-native cancellation for Woo-billed tiers. Dashboard code also exposes a PMPro cancellation link, so the owner should confirm the persisted Bricks action and make cancellation authority explicit before a paid acceptance run. The Member Perks source owner must reconcile runtime provenance. No tracker state or source was changed by this report.
