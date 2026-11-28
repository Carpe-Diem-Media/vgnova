# 09: Events and Ticketing Evaluation v0.1.0

| Field | Value |
|---|---|
| Target | `vgnova-local-10039` staging runtime |
| Assessment date | 2026-11-19 |
| Source baseline | `main` at `454346d9e6a2c36864a4bbd944e52596f16e09c1` |
| Plane scope | VGN-55 read-only foundation audit; VGN-51 draft-template work reviewed without changes |

## 1. Executive Summary

Tickera owns the event records, event categories, native ticket types, attendee ticket instances and check-in log. WooCommerce owns the four ticket products and their prices; the active Tickera WooCommerce Bridge connects those products and Woo orders to Tickera ticket instances. This follows the project's native-first architecture; the inspected evidence does not justify a custom event, ticket, order or scan subsystem.

The current database contains 17 published `tc_events`, eight configured `event_category` terms, three native Tickera ticket types and four published Woo ticket products. The three native types map to QA events 1189, 1191 and 1193. All four Woo products map to event 763. There are 17 published attendee ticket instances: four under paid Tickera orders and 13 under Woo HPOS placeholder posts. No instance has `tc_checkins` or legacy `tc_checkouts` metadata.

Canonical Event Participation is configured through ACF and Platform Core, with four `event_role` terms and no stored participation records. The older `event_medewerkers` field still links two published `medewerker` profiles to event 763. Published Event Single template 774 and draft template 1919 query canonical `event_participation`, so those legacy links do not establish that either template will show the two profiles.

Template 1919 is present in runtime as a draft with no placement conditions. Its active ACF group 1913 is also present at runtime, but none of its five optional fields has values on any of the 17 published events. The related PR #91 is still open, draft and unmerged. The current `main` tree does not contain its template or ACF JSON files.

Ticket/order/check-in behavior is not end-to-end verified. Static code shows the bridge creates ticket instances from eligible Woo order line items, restricts download access and scanner eligibility to appropriate order states, and trashes/restores instances on cancellation and recovery transitions. Current records include one published instance for a `pending` Woo order. Tickera's HPOS event-summary query can count a `draft` placeholder while the bridge's direct check-in payment guard rejects an underlying Woo order unless it is `completed` or `processing`; this is an inferred summary-versus-scan discrepancy, not an endpoint result.

**Overall domain coverage: PARTIAL.** Current configuration and source paths are inventoried; checkout, scan, email, rendering and recovery behavior remain unproven. The active Woo Order Test gateway is configured for administrator-only use, and the latest VGN-18 checkout observation reported no guest payment method. Event ticket sales therefore have no demonstrated guest payment path.

## 2. Inspection Scope and Evidence

Read the [coordination brief](COORDINATION_BRIEF_v0.1.0.md), [parent baseline](PARENT_BASELINE_v0.1.0.md), [report 01](01_RUNTIME_FOUNDATION_v0.1.0.md), [report 02](02_PLUGIN_INVENTORY_v0.1.0.md), [report 03](03_CPT_TAXONOMY_v0.1.0.md), [report 04](04_ACF_RELATIONSHIPS_v0.1.0.md), [report 05](05_CUSTOM_CODE_v0.1.0.md), and relevant WooCommerce lifecycle and email sections of [report 06](06_WOOCOMMERCE_v0.1.0.md). Independently checked the high-impact Tickera, Woo bridge, HPOS, Event Participation and template claims against installed plugin source and the current staging database. Read current Plane issue records and PR #91 evidence for VGN-51. Evidence labels follow the coordination brief: `BOTH_CONFIRMED`, `RUNTIME_CONFIRMED`, `SOURCE_CONFIRMED`, `HISTORICAL_ONLY`, `INFERRED`, `UNKNOWN`, and `CONFLICT`.

Database access used direct PHP/mysqli `SELECT` and `SHOW` statements against the registered LocalWP database socket, serialized with `flock -n /tmp/vgnova-evaluation-read.lock /usr/bin/php8.5`. `git rev-parse HEAD` returned `454346d9e6a2c36864a4bbd944e52596f16e09c1`. Queries returned public event/ticket configuration and private-data-free counts only. No customer names or emails, ticket codes, order identifiers, API keys, database credentials, raw order records, or exports were read into this report. No WordPress bootstrap, Bricks MCP, Novamira MCP, HTTP request, checkout, ticket creation, email, scan, job, migration, mutation, commit or Plane update was performed. The required safe local fallback was used for Bricks data; no Bricks operation was sent through Novamira.

WP-CLI `db query` was unavailable because the local `mysql` client executable is absent. The direct read-only mysqli path succeeded. A Bricks MCP read was unnecessary because the draft and published template records, settings and element loops could be inspected through the approved local database fallback without bootstrapping WordPress. Tickera's `TC_Ticket_Instance::get_attendance_records()` can rewrite normalized attendance metadata and delete legacy checkout metadata during a read (`tickera/includes/classes/class.ticket_instance.php:82-129`); it was not called.

| Evidence | Result | Label |
|---|---|---|
| Coordination brief, parent baseline, reports 01, 02, 03, 04 and 05 | Read for scope, ownership, baseline and access; high-impact event/ticket claims independently checked | `BOTH_CONFIRMED` where stated |
| Report 06, WooCommerce | Cross-domain checkout and email context; relevant settings/code independently checked | `HISTORICAL_ONLY` for its browser observation; current config `RUNTIME_CONFIRMED` |
| LocalWP selected SQL reads | Event, taxonomy, staff, ticket, product, order-status aggregate, attendee-instance, ACF group and Bricks template records | `RUNTIME_CONFIRMED` |
| Installed Tickera and Bridge source | CPT registration, issue/check-in/order/email paths and guards | `SOURCE_CONFIRMED` |
| Platform Core and ACF JSON source | Event Participation fields, role model, validation, locks and consumer adapter | `SOURCE_CONFIRMED`; runtime rows independently checked |
| VGN-51 and PR #91 | Issue state, draft scope, changed files, PR head/base and available review/workflow evidence | `RUNTIME_CONFIRMED` for tracker state; author render claims `HISTORICAL_ONLY` |
| FastCheckin/EventSales search | No distinct installed component or source registration found; only post-VS1 FastCheckin hardening references | `UNKNOWN` for any external/non-repository scanner |

## 3. Component Inventory

| Component | ID / slug / path | State | Owner | Purpose | Consumers | Evidence |
|---|---|---|---|---|---|---|
| Tickera Events | `tc_events`; 17 published | Active | Tickera | Event identity, title, content, date/time, location | `event_category`, ticket types, Woo Bridge, Bricks | `tickera/tickera.php:4883-4933`; SQL; `RUNTIME_CONFIRMED` |
| Event category | `event_category`; 8 terms | Active | Tickera | Event classification | Tickera and Bricks event queries | `tickera/tickera.php:4934-4966`; SQL; `RUNTIME_CONFIRMED` |
| Tickera ticket types | `tc_tickets`; 3 published | Active | Tickera | Native ticket definitions and inventory | Tickera order and attendee flows | `tickera/tickera.php:4967-5008`; SQL; `RUNTIME_CONFIRMED` |
| Attendee tickets | `tc_tickets_instances`; 17 published | Active | Tickera; Woo Bridge creates/maintains Woo-linked instances | Per-attendee ticket, event/type/order linkage and check-in history | Tickera attendee UI, order email/PDF paths, scanner API | `tickera/tickera.php:5031-5065`; bridge `:2371-2522`; SQL |
| Tickera orders | `tc_orders`; 4 with `order_paid` | Active for native Tickera orders | Tickera | Native ticket order state | Tickera ticket issuance/check-in | SQL; report 02 |
| WooCommerce and Tickera Bridge | Woo 11.3; Bridge 1.8.0; HPOS tables active | Active | WooCommerce owns product/order state; Tickera Bridge integration | Woo ticket products, prices and orders connected to Tickera instances | Checkout, order lifecycle, ticket links/emails and scanner eligibility | Installed plugin inventory; bridge `:219-283, 2371-2522, 2904-3004, 5074-5108, 5322-5368` |
| Event Participation | `event_participation`; no rows; `event_role` has 4 terms | Configured, unused in current data | Platform Core owns behavior; ACF owns CPT/fields; Tickera owns event endpoint | Canonical event-to-person role and display-order record | Bricks Event Single 774, draft 1919, reverse Person consumers | Source JSON and `includes/Event/*`; direct aggregate |
| Legacy event staff field | ACF `event_medewerkers` on `tc_events` | Active legacy data; 2 profile IDs on event 763 | ACF relationship to `medewerker`; legacy consumers remain | Transitional direct event-to-profile link | Legacy profile/product snippets; not canonical 774/1919 loop | Report 04/05; SQL; `RUNTIME_CONFIRMED` |
| Event Single | Bricks template 774 | Published; condition `tc_events` | Bricks presentation; Tickera event context | Existing Event page | Event routes and product/participation loops | Runtime template settings and DB content; `RUNTIME_CONFIRMED` |
| Event Landing template 1919 | Bricks template 1919 | Draft; no template conditions | Bricks presentation; PR #91 source is not merged | Separate reusable event landing composition | Preview event 542 only; no public assignment | Runtime SQL; PR #91; `CONFLICT` source/runtime |
| Event Landing fields | ACF group 1913; five fields | Active in runtime; no event values found | ACF | Optional testimonials, gallery, video, programme and information image | Draft template 1919 | Runtime ACF posts; PR #91 source JSON; `CONFLICT` source/runtime |
| Tickera ticket PDF template | Legacy `tc_templates` post 41, "Default"; Woo products 842/844/845/846 select it | Published; not rendered | Tickera | PDF ticket presentation | Woo email attachment generation | SQL; bridge `:3795-3832` |
| Tickera modern ticket template | `wp_tickera_ticket_templates` row 1, "Default Template", active/default | Active | Tickera | Native template registry | Tickera ticket rendering | Read-only `SHOW`/`SELECT`; runtime config |
| FastCheckin / EventSales | No standalone runtime plugin or code path found in searched repo/plugin paths | Not evidenced | Unknown | No separately evidenced owner or authority | Tickera built-in check-in API is present | Scoped search and plugin inventory; `UNKNOWN` beyond checked paths |

### Published event inventory

Dates below are stored local event metadata. At the 2026-11-19 assessment date, several are past; titles/slugs indicate that these are staging or QA records. This inventory does not assert that they are real or approved public events.

| Event ID | Slug | Stored title | Category | Start → end | Ticket mapping |
|---:|---|---|---|---|---|
| 151 | `aanlyn-uitsendings` | Aanlyn, uitsendings … | `aanlyn` | 2027-01-01 → 2027-01-02 | none |
| 163 | `donasies-kompetisies` | Donasies, kompetisies … | `kompetisies` | 2026-12-02 | none |
| 164 | `feeste-markte` | Feeste, markte … | `feeste` | 2026-11-01 → 2026-11-02 | none |
| 165 | `funksies-vroue-oggende` | Funksies, vroue-oggende … | `funksies` | 2026-09-01 → 2026-09-02 | none |
| 166 | `sport-gholfdae` | Sport, gholfdae … | `sport` | 2026-12-01 → 2026-12-21 | none |
| 167 | `vertonings-produksies` | Vertonings, produksies … | `vertonings` | 2027-04-21 → 2027-05-01 | none |
| 172 | `reise-toere` | Reise, toere … | `reise` | 2026-07-28 → 2026-07-31 | none |
| 181 | `werkswinkels-praatjies` | Werkswinkels, praatjies … | `werkswinkels` | 2027-02-04 → 2027-02-06 | none |
| 542 | `vroue-oggend-rustig` | Vroue-oggend: Rustig & Herlaai | `funksies` | 2026-06-10 18:00 → 21:00 | none |
| 545 | `werkswinkel-begroting` | Werkswinkel: Begroting met Hart | `werkswinkels` | 2026-06-17 18:00 → 21:00 | none |
| 548 | `herfsmark-gemeenskap` | Herfsmark: Handgemaak & Gemeenskap | `feeste` | 2026-07-01 18:00 → 21:00 | none |
| 551 | `aanlyn-selfsorg-101` | Aanlyn Meesterklas: Selfsorg 101 | `aanlyn` | 2026-06-03 18:00 → 21:00 | none |
| 554 | `weekend-drakensberg` | Weekend Wegbreek: Drakensberg | `reise` | 2026-07-11 18:00 → 21:00 | none |
| 763 | `test-event-medewerkers-qa` | Test Event | none | 2026-06-26 23:45 → 2026-07-04 16:30 | Woo products 842, 844, 845, 846 |
| 1189 | `qa-dashboard-event-early` | [QA] Dashboard Event Early | none | 2026-09-22 19:03 | native ticket 1195 |
| 1191 | `qa-dashboard-event-late` | [QA] Dashboard Event Late | none | 2026-09-24 19:03 | native ticket 1196 |
| 1193 | `qa-dashboard-event-past` | [QA] Dashboard Event Past | none | 2026-09-19 19:03 | native ticket 1197 |

The eight configured category terms and published-event counts are `funksies` (2), `vertonings` (1), `feeste` (2), `werkswinkels` (2), `aanlyn` (2), `reise` (2), `sport` (1), and `kompetisies` (1). The 13 assignments leave four events without a category: 763, 1189, 1191 and 1193.

## 4. Current Architecture and Configuration

**Event and classification authority.** Tickera registers public `tc_events` and the hierarchical `event_category` taxonomy. Current event records use Tickera metadata `event_date_time`, `event_end_date_time` and `event_location`; dates exist for all 17 published events and the location key exists on 14, including empty values. Event categories are separate from ticket types. The project should continue to treat Tickera's event/category model as authoritative.

**Ticket type and price mapping.** The three native `tc_tickets` records are all published, free (`price_per_ticket=0`) and configured with quantity 10:

| Ticket ID | Title | Event ID | Price / quantity | Issued instances |
|---:|---|---:|---|---:|
| 1195 | [QA] Early Ticket | 1189 | 0 / 10 | 2 under `order_paid` Tickera orders |
| 1196 | [QA] Late Ticket | 1191 | 0 / 10 | 1 under `order_paid` Tickera orders |
| 1197 | [QA] Past Ticket | 1193 | 0 / 10 | 1 under `order_paid` Tickera orders |

The four published Woo products are all flagged `_tc_is_ticket=yes`, map `_event_name=763`, have current price R999 and regular price R9,999, and are marked `instock`. Product 842 has `tc_ticket_id=842`; products 844, 845 and 846 have no `tc_ticket_id` value. All four select Tickera `tc_templates` post 41 ("Default"). No attendee or order data was used to produce the following aggregate:

| Product ID / title | Woo line-item quantity by status | Published attendee instances by parent order status |
|---|---|---|
| 842 / Event Test | completed 2; processing 1; pending 1 | completed 2; processing 1; pending 1 (4 total) |
| 844 / Event Test 2 | none | none |
| 845 / Event Test 3 | completed 1; processing 3 | completed 1; processing 3 (4 total) |
| 846 / Event Test 4 | cancelled 1; completed 1; on-hold 2; pending 5; processing 4 | completed 1; processing 4 (5 total) |

WooCommerce's current currency option is `ZAR`. The line-item totals and instance counts are present-state aggregates; they do not prove checkout, payment, cancellation or recovery events occurred in a particular sequence.

**Attendee and scanner state.** Each `tc_tickets_instances` record stores Tickera ticket/event linkage and order parentage. Four native instances have parent type `tc_orders` and Tickera status `order_paid`. Thirteen Woo-linked instances have parent type `shop_order_placehold`; their Woo order state is in HPOS. These Woo placeholder posts have WordPress status `draft`, while the HPOS orders have completed, processing or pending statuses. `tc_checkins` and `tc_checkouts` are absent from all 17 instances. No ticket codes or attendee fields are disclosed here.

The Tickera general option has `hide_checkin_ineligible_tickets=no` and `ean_13_checker=no`. The former affects the Attendees & Tickets admin listing. It is not a substitute for the bridge's scanner payment check. No Tickera `tickera_email_setting` option row was present. When that option is absent, bridge code defaults customer ticket-table flags to `yes`; PDF attachments require explicit per-email `woo_{email_id}_attach_ticket=yes`. No email or attachment was generated.

**People at events.** ACF group `Event Medewerkers` (group 748 per report 04) owns legacy field `event_medewerkers` on `tc_events`, linking to `medewerker`. Event 763 has profile IDs 753 and 757, both published `medewerker` posts. A second matching metadata row belongs to revision 769 of event 763, not a second event. The canonical ACF group `Event Participation Details` stores one Tickera event, one `person`, zero or more `event_role` terms, and non-negative display order. The four role terms are `spreker`, `paneellid`, `fasiliteerder` and `gas`; all currently have zero assignments. No canonical participation posts exist.

**Bricks templates.** Published Event Single 774 has the `tc_events` placement condition. Its people query is `event_participation`; its product loop filters `_event_name={post_id}`. Draft Event Landing 1919 has no placement conditions and previews event 542. It has 69 Bricks elements and seven named content sections in the stored design. Its ticket-products loop selects published Woo `product` records by `_event_name={post_id}`, without an `_tc_is_ticket=yes` clause. Its people loop selects `event_participation`; its testimonial loop names the query object `acf_event_landing_testimonials`. The active ACF group 1913 contains five optional fields: testimonials, gallery, video URL, programme and info image. No values were found on the 17 published events.

## 5. How It Works

The following lifecycle is supported by installed source and current records. It is not a behavioral test result.

| Lifecycle states and transitions | Guard and side effects | Terminal state and recovery | Evidence |
|---|---|---|---|
| Event configured → published | Tickera owns `tc_events`, event dates/location and `event_category`. No separate event-type CPT exists. | No domain terminal status is defined; permanent event deletion ends the record. WordPress/Tickera statuses and editorial data remain authoritative. | Tickera `tickera.php:4883-4966`; SQL |
| Woo ticket line → attendee instance | Bridge `woocommerce_new_order_item` handler requires checkout/extension data, a Woo order type, a product ID and `_tc_is_ticket=yes`. It loops over line quantity, creates published `tc_tickets_instances`, sets parent order and writes `event_id`, `ticket_type_id`, `item_id` and a private ticket code. | A failed post insert is skipped; source comments say callers check ticket counts. No live create was run. Removing an order line removes its linked instances. Hard deletion of the order deletes associated instances and is terminal. Admin edits distinguish omitted order lines from zero quantity; reconciliation uses `item_id` so duplicate product lines do not overwrite each other. | Bridge `:597-612, 2371-2522, 2532-2590` |
| Woo order status → ticket visibility | `woocommerce_order_status_changed`: cancellation trashes associated instances and marks `_cancelled_order`; every non-cancelled status change restores trashed instances to `publish` and clears that marker, including changes to `refunded` or `pending`. Deleting an order removes associated instances. Resuming an order deletes stale instances before line items are recreated. | Cancellation is reversible through a later non-cancelled status transition. Permanent order deletion ends the ticket records. Restoration depends on the order transition hooks; no cancellation, refund or resume was exercised. A failed or partial order/item operation has no end-to-end reconciliation proof in this task. | Bridge `:2904-2935, 2942-3004, 5322-5368` |
| Woo order → download/email eligibility | Bridge requires a ticket product. For ordinary payment methods downloads are allowed only on `completed` or `processing`; COD has a separate setting and completed-order rule. Completed orders trigger attendee email action. Woo order emails can include the ticket table; PDF ticket files are attached only when that email's attachment option is explicitly enabled, then temp files are deleted after send. | No delivery terminal state was confirmed. Email transport, exact message content, PDF rendering, attachment config, retry and duplicate-send behavior were not exercised. | Bridge `:1392-1395, 3429-3478, 3795-3832, 3850-3910` |
| Ticket instance → scan decision/log | Tickera scanner checks API-key event scope and ticket-event match. Bridge's `tc_order_is_paid` filter accepts Woo `completed` or `processing`; failed eligibility exits before check-in. Allowed scans append chronological `tc_checkins` data including pass/fail, direction and time. | The source defines no terminal check-in state; `out` can change current admission direction while the history remains. No scan/check-out or API call was run. Correcting an erroneous history and scanner credentials/permissions remain unverified. | `class.checkin_api.php:643-750, 767-791`; bridge `:5085-5108`; `general-functions.php:64-78` |
| Event Participation create/edit | Platform Core validates a non-trashed Tickera event, a non-trashed `person`, approved role terms, unique event/person pair and non-negative order. Create accepts `publish`, `draft`, `pending` or `private`; only `publish` is active for consumers. Pair and record locks serialize writes. ACF fields/roles are written after the post row; a partial create failure hard-deletes the new row. Admin save rechecks fresh state and returns 409/503 on duplicate/read/lock failures. Titles resync after endpoint title changes. | No participation create, update or publish was run. There is no separate participation status machine; `publish` is active, draft/pending/private are inactive, and trash can be restored. Hard deletion is terminal. | `EventParticipationService.php:26-78, 99-258`; `EventParticipationAdmin.php:32-49, 80-167, 170-215`; `EventParticipationRepository.php:11-18, 102-189` |

**HPOS summary versus scan guard.** For HPOS, the bridge adds the WordPress placeholder status `draft` to Tickera's `tc_paid_post_statuses`. Tickera's `get_event_essentials()` query filters ticket instances by the parent WordPress post status, so the published instance for product 842's underlying `wc-pending` order is eligible for the aggregate query. The bridge's separate `tc_order_is_paid` filter resolves the Woo order and accepts only `completed` or `processing`, so that pending instance should fail the scanner payment guard. This is a code-and-data inference; neither endpoint was called. `get_event_essentials()` also calls the mutating attendance-record getter, so it was deliberately not used for verification.

## 6. Source Versus Runtime

| Area | Source evidence | Runtime evidence | Reconciliation |
|---|---|---|---|
| Platform Core | Event Participation repository/service/admin/Bricks adapter and ACF JSON are present on `main` at `454346d9…`. | Report 01 records the installed Platform Core tree matching the finalized PE-008 manifest for this same source baseline. Current participation aggregate is zero. | `BOTH_CONFIRMED` for source/deployment inventory; behavior remains unverified. |
| Tickera and Bridge | Vendor code is not repository-owned source; installed code registers the event/ticket CPTs and lifecycle hooks. | Tickera 3.6.0.9 and Bridge 1.8.0 are active; current records and selected configuration are in the LocalWP database. | `RUNTIME_CONFIRMED` configuration plus `SOURCE_CONFIRMED` installed vendor code; this does not prove behavior. |
| Event Single 774 | Existing architecture/report 05 documents canonical Participation consumption. | Published 774 has a `tc_events` condition and `event_participation` loop. | `BOTH_CONFIRMED` for the current stored template configuration. |
| Event Landing 1919 and ACF group 1913 | Main lacks `site-code/bricks/event-landing/template.json` and `site-code/acf-json/group_vg_event_landing.json`. PR #91 contains both. | Bricks draft 1919 and active ACF group 1913 are present in runtime. | `CONFLICT`: runtime-only pending artifacts; source remains in open draft PR #91. |
| VGN-51 / PR #91 | PR #91 head `4508a2d0691cfe525e5039b5d1511cc49c1c1a11`; base `fcb5fd8bb954b20ea957690b986eb78908f733ca`. | VGN-51 is started; template 1919 remains draft with no conditions. PR is open, draft and unmerged; no review is recorded. One "Voelgoed dashboard checks" workflow run is completed successfully, while combined status contexts are empty. | Author's render/Hallmark claims are `HISTORICAL_ONLY`; there is no independent review or complete CI evidence. `NOT_VERIFIED_FOR_MERGE`. |
| Event content and commerce | Source defines ownership and ACF relationships, not live business records. | Direct selected queries show current events, products, prices, order-state aggregates and ticket instances. | `RUNTIME_CONFIRMED`; the records are staging data and are not proof of approved real event content or customer sales. |

## 7. Completeness and Functionality

| Capability | Configuration evidence | Behavior evidence | Assessment |
|---|---|---|---|
| Event CPT and category model | Tickera registration and 17 published events/eight terms directly inspected | No route or event archive render performed | `CONFIGURED / PARTIALLY_VERIFIED` |
| Native Tickera tickets | Three published free types mapped to three QA events; four paid attendee instances | No direct Tickera purchase or ticket download performed | `CONFIGURED / UNVERIFIED` |
| Woo ticket products | Four published ticket-flagged products, price and event link inspected | Order line and instance aggregates exist, but no checkout/payment flow was run | `CONFIGURED / PARTIALLY_VERIFIED` |
| Ticket issuance | 17 published instances with event/type and order-parent aggregates | Source supports issuance; no new instance was created or lifecycle replayed | `PARTIALLY_VERIFIED` |
| Scanner/check-in | Tickera code path and bridge status guard inspected; zero scan-history rows | No scanner/API call or check-in was performed | `CONFIGURED / UNVERIFIED` |
| Event Participation | ACF schema, role terms, validation/locking source and zero rows inspected | No record creation, publish, duplicate or rollback path was exercised | `CONFIGURED / UNVERIFIED` |
| Event Single 774 | Published condition and stored query loops inspected | No page render or responsive/browser check | `CONFIGURED / UNVERIFIED` |
| Draft landing 1919 | Draft state, empty conditions, element loops and empty ACF values inspected | No preview route, public render, checkout, PDF or email test | `CONFIGURED / UNVERIFIED` |
| Payment and email | Active test gateway config and static bridge email hooks inspected | Latest VGN-18 browser note reports no guest payment method; no payment or email was attempted | `CONFIGURED / NOT BEHAVIOR-PROVEN` |

The PR description's three event renders and viewport/focus/reduced-motion checks were not independently reproduced. Its claims do not cover purchase, ticket issuance, email delivery, PDF rendering or scanning.

## 8. Problems, Risks and Contradictions

1. **The pending draft template's ticket loop is broader than its label.** Template 1919 selects published Woo products by `_event_name={post_id}` but does not also require `_tc_is_ticket=yes`. Event Single 774 has the same event-only product constraint and no explicit post-status or ticket-flag condition in its stored query. Current mappings all point to ticket products, but a non-ticket product associated with the same event could enter the price loop.

2. **Canonical people are not populated while legacy event links remain.** Event 763 has two legacy `medewerker` links (IDs 753 and 757), but no `event_participation` rows exist. Both 774 and 1919 use the canonical loop. The transition register records these event edges as excluded from migration, so do not copy or remove them without an approved mapping and verification of all legacy consumers.

3. **Order status, ticket visibility and scan eligibility can diverge.** One published product 842 ticket instance belongs to a Woo `pending` order whose placeholder post is `draft`. Tickera's HPOS paid-status filter includes `draft`, and `get_event_essentials()` queries placeholder post status; the scanner's separate bridge payment filter checks the underlying Woo state and rejects pending. The bridge also restores instances on every non-cancelled status change, including `refunded`, while its scanner guard rejects refunded orders. A summary can count tickets that the scanner will not admit. This is `INFERRED`, not a live endpoint observation.

4. **Order-to-ticket reconciliation is only a staging aggregate.** Product 842 has one pending instance; product 846 has order line quantities in cancelled, on-hold and pending states without corresponding current published instances. Paid completed/processing quantities match current instances for products 845 and 846. These aggregates do not establish event chronology or prove failure; they must not be used as a migration or liability ledger.

5. **Event Landing source/runtime drift remains.** ACF group 1913 and Bricks template 1919 exist in staging, but their source files are only in unmerged PR #91. The draft's five optional fields are unpopulated across published events.

6. **Two parts of the new landing loop remain unverified.** The testimonial query uses `acf_event_landing_testimonials`; no matching provider registration was found in the searched source/runtime files. The product loop lacks the ticket flag guard. Neither item was behavior-tested.

7. **Guest purchase is not demonstrated.** `Woo Order Test` is enabled with administrator payment bypass enabled. Its `process_payment()` completes an order only for administrators and returns an error for other users. VGN-18's 2026-11-17 checkout observation reported no guest payment method. This is not a usable guest payment proof for event ticketing.

8. **Ticket email configuration does not prove delivery.** Bridge hooks can append ticket tables and can attach PDFs when per-email flags are enabled. The Tickera email-setting option is absent, so code defaults are relevant for ticket-table flags; attachment flags remain opt-in. Sender transport, actual content, duplicate/retry behavior and PDF render are unknown.

9. **Some Tickera reads can write.** `get_event_essentials()` calls `get_attendance_records()`, which can normalize `tc_checkins`, migrate legacy `tc_checkouts` and delete the old key. Future read-only reviews should use direct scoped `SELECT` aggregates rather than invoking this accessor or exports.

10. **No separate FastCheckin/EventSales authority was evidenced.** The repository contains future FastCheckin hardening references, but no distinct installed plugin or implementation was found in the searched paths. This does not exclude an external scanner or untracked deployment component.

## 9. Redundancy Candidates

| Candidate | Assessment | Native-first action |
|---|---|---|
| Custom event, ticket, order or scanner subsystem | No measured deficiency; Tickera and Woo/Bridge already own these domains | Keep Tickera and WooCommerce as authorities; use supported plugin configuration or a small approved adapter only after reproducing a specific gap. |
| `event_medewerkers` and Event Participation | Transitional relationship duplication with two existing links; migration explicitly gated | Keep both datasets intact until event/profile/role/order consumers and reverse queries are mapped. Do not create a parallel People implementation or infer roles. |
| 774 and 1919 event presentation | 774 is the published template; 1919 is a separate draft composition | Keep 1919 unassigned until PR review and a separately approved activation decision. Compare both templates before consolidation. |
| Product event mapping and ticket eligibility | `_event_name` identifies event association; `_tc_is_ticket` identifies ticket product | Add/verify both conditions in any approved ticket-price loop. Do not infer all event-linked products are tickets. |
| Testimonial query provider | ACF relationship field is native; template names a custom query object | First verify Bricks/ACF's supported relationship loop and the registered provider. Add only the smallest supported adapter if the native loop cannot satisfy it. |
| FastCheckin/EventSales | No separate implementation evidenced | Do not add or assume another scanner. Verify Tickera's existing API and event-scoped credentials through an approved task without exposing keys. |

## 10. Recommendations

1. Keep the existing domain ownership: Tickera for events/ticket instances/check-in, WooCommerce for ticket product prices and orders, Bridge for integration, ACF/Platform Core for canonical event participation, and Bricks for presentation. Do not introduce a custom ticket ledger, order engine, scanner, relationship table or event taxonomy.

2. Before treating event ticketing as ready for guest sales, replace or supplement the administrator-only test payment method with an approved guest-capable gateway. VGN-18 and report 06 provide the current blocker evidence; no transaction was run here.

3. Ask the independent PR reviewer to inspect exact PR #91 head/base, all checks and the current runtime/source difference. Resolve the missing `_tc_is_ticket` predicate and confirm the testimonial loop's provider before any merge. Keep template 1919 in draft with no conditions until a separately authorized publication decision.

4. For Event Participation, preserve legacy values and follow the existing decommission register. Do not migrate event 763's edges without approved provenance; verify canonical output and legacy product/profile consumers before any retirement.

5. Reconcile the HPOS event-summary query against real Woo statuses before using sold counts for admission or migration. Prefer the existing bridge/Tickera APIs or a narrowly scoped supported filter that checks actual Woo status. Confirm with a non-mutating fixture or approved integration test before changing behavior; do not rely on placeholder `draft` alone.

6. For any post-VS1 ticket migration, follow VGN-28/VGN-35: freeze sales, reconcile event/product/order/instance states, preserve ticket liabilities, rehearse recovery, and obtain the required PE-007/PE-008 gates and separate execution authorization. Current counts are not a migration ledger.

7. Keep future email/PDF and scan verification in a separate approved staging plan. Use a test-only event/product and controlled recipient/scanner only when that task authorizes the transaction; validate cleanup and recovery without exposing attendee data or API keys.

8. Continue read-only ticket audits with serialized SELECT-only aggregates. Do not use Tickera's attendance getter, attendee export, ticket download, check-in API or order-email path as an inspection shortcut.

## 11. Outstanding Verification

- Render 774 and 1919 in a supported Bricks/browser session, including empty and populated participant/testimonial/media states, responsive layout, keyboard focus and reduced motion. This report verified stored structure, not frontend output.
- Confirm the runtime/provider resolution for `acf_event_landing_testimonials` and whether the native ACF relationship loop suffices.
- Reconcile event summary totals and scanner acceptance for HPOS `pending`, `on-hold`, `cancelled`, `refunded`, `processing` and `completed` statuses. No endpoint call or scan was made.
- With separately authorized staging transactions, verify Woo guest checkout, ticket issuance, completion/cancellation/resume/order-line edit recovery, attendee access and scanner eligibility. Do not use the current admin-only test gateway as guest proof.
- Verify actual Woo email delivery, message templates, ticket PDF template 41 rendering, attachment configuration and temp-file cleanup. No emails or PDFs were generated.
- Complete source review and independent PR review of #91 against its exact head/base and current checks; current PR evidence has no recorded review and is not merge-verified.
- For Event Participation migration, establish approved provenance for legacy event/profile edges and verify reverse/profile/product consumers before any data change.
- Confirm whether any external scanner or EventSales system exists outside the checked repository and installed-plugin paths; no credentials or external systems were inspected.

Verified tracker references (read only): VGN-55 `1782fd6f-206f-432b-bf85-0ad97c0a43bb`; VGN-51 `f1c3da7b-15e8-49e1-a69f-e8ae1019f43d`; VGN-35 `16386406-b85c-4cf8-b53a-3baa80f8c89f`; VGN-28 `a48a03e7-5316-432f-8625-183a354c9f12`; VGN-18 latest checkout comment dated 2026-11-17. No issue state or comment was changed.

## 12. Final Domain Assessment

**Domain state:** Tickera and Woo Bridge are configured and hold staging event, ticket and attendee-instance records. Event Participation is configured but unused. Guest ticket purchase, scan acceptance, ticket email/PDF delivery and rendered event-page behavior are not verified. Do not treat this evidence as business approval or launch readiness.

**Lifecycle gates:** For Event Landing PR #91, `SOURCE_MERGED=NO`, `RUNTIME_APPLIED=YES` as an unassigned draft template and active ACF group, `BEHAVIOR_VERIFIED=NO`, `BUSINESS_APPROVED=UNKNOWN`, and `LAUNCH_READY=NO`. These gates do not alter Event Participation source or broader platform status.

**Coverage:** `PARTIAL`. The report contains a full read-only inventory of current published event records, event categories, ticket types and products, current price mappings, staff links, Participation structure, bridge status paths and draft/public template configuration. It partially verifies current ticket issuance from records. It does not verify checkout, email, rendering, scanning or recovery behavior.

**Runtime verification:** `PARTIAL`. Current database, plugin and template configuration was read directly. No user-facing runtime behavior was exercised.

**Qualitative confidence:** `HIGH` for the directly observed event, category, ticket, product and template configuration. `LOW` for unexercised checkout, scan, email, render/provider and recovery behavior.

**Acceptance criteria:** 10/10 research deliverables pass. Unverified runtime behavior is listed in section 11 and was outside the authorized read-only scope.

| # | Criterion | Status | Evidence |
|---:|---|---|---|
| 1 | Read the coordination brief and parent baseline | `PASS` | Section 2 links both files |
| 2 | Read reports 01, 02, 03, 04 and 05 and independently check high-impact claims | `PASS` | Section 2 links reports 01, 02, 03, 04 and 05; Sections 3–8 compare runtime and source |
| 3 | Inventory event CPT, taxonomy, metadata and staff | `PASS` | Sections 3–4; current SELECT aggregates |
| 4 | Check Event Participation source, runtime and consumers | `PASS` | Sections 3–6; Platform Core source references |
| 5 | Inventory all published events, ticket types/products, prices and mappings | `PASS` | Section 3 event table; Section 4 ticket/product tables |
| 6 | Review issuance and check-in authority with private-data-free aggregates | `PASS` | Sections 4–5; no ticket codes or attendee details included |
| 7 | Review Woo Bridge lifecycle, emails and ticket templates | `PASS` | Sections 4–5; installed bridge source references |
| 8 | Review template 1919 and VGN-51/PR #91 without edits or publication | `PASS` | Sections 3, 4 and 6; no runtime write performed |
| 9 | Search for FastCheckin/EventSales only where evidence exists | `PASS` | Sections 2–3 and 8; no distinct component found in checked paths |
| 10 | Record lifecycle guards, side effects, recovery, evidence limits and native-first recommendations | `PASS` | Sections 5, 8, 10 and 11 |

### Completion assessment

- Task type: `RESEARCH`
- Task / issue: VGN-55 foundation audit; Events and Ticketing domain assignment.
- Assessment role: Planning/research.
- Implementation Score: `N/A`. No implementation.
- Task Execution Score: `10/10` for the authorized read-only research and report deliverable. This score does not certify ticketing behavior.
- Confidence Score: `90%`. Direct staged database aggregates, installed-source paths, current template configuration and tracker/PR state support the inventory. Residual uncertainty remains in checkout, scan, email, rendering, provider and recovery behavior, plus any external scanner outside the checked paths.
- Verification Status: `PARTIALLY_VERIFIED`. Current runtime configuration and source paths were verified; end-to-end behavior was not.
- Merge readiness: `NOT_VERIFIED_FOR_MERGE` for PR #91. No independent review is recorded and complete exact-candidate CI/review evidence is unavailable.
- Evidence artifact: `docs/evaluation/09_EVENTS_TICKETING_v0.1.0.md`. SQL reads were SELECT-only under `/tmp/vgnova-evaluation-read.lock`. No tests, transactions, mutations, commits or Plane updates were run.
- Known gaps / highest-risk assumption / next action: Resolve the HPOS sold-count and paid-scan status discrepancy, then complete independent review of PR #91. The highest-risk assumption is treating an HPOS placeholder's `draft` post status as a paid Woo order. Follow the read-only and transaction gates in section 11 before changing behavior or granting business approval.
