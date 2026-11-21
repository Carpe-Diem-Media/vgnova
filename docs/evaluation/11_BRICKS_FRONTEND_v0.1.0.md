# Bricks Frontend Evaluation

Snapshot: 2026-11-19  
Task: VGN-55 Phase 6, read-only Bricks frontend research  
Repository HEAD: `454346d9e6a2c36864a4bbd944e52596f16e09c1`  
Runtime: LocalWP `vgnova-local-10039`, site ID `nAZNpFs99`, `http://localhost:10039`  
Observed platform versions: WordPress 7.1.5, Bricks 2.4.5, Automatic.css 4.0.3, Frames 1.5.13

## 1. Executive Summary

Coverage is **PARTIAL**. A read-only local database inspection enumerated all 32 non-trashed Bricks templates, their stored template types and conditions, relevant page trees, Bricks forms, saved query/filter settings, ACF relationship markers, and global design-resource records. The live frontend was not rendered. T3 `preview_status` returned `available: false` and no tab. The tool catalog exposed only Bricks MCP aliases labelled as Novamira aliases, so they were not used. No WordPress route was requested.

Current persisted state confirms that homepage page 1926 and Event Landing template 1919 are drafts. Page 1926 is not the assigned front page, and it has no saved `home_featured_destination` value. Template 1919 has an empty placement-condition list and its five optional Event Landing fields have no populated values. GitHub PRs #91 and #92 are still open drafts; neither page nor template is published through those PRs.

The clearest site-wide gaps are the missing `/content/` editorial index, the empty global Footer template 78, broad default archive assignment through template 369, and a global Bricks header with several unresolved destinations and placeholder/legacy labels. The draft homepage links to transitional `/kursusse/` and `/produkte/` routes. Current IA records `/leer/` as the Learning area and `/winkel/` as the Shop area. The archive route differences remain governed by the existing URL decisions; this report does not authorize rewrites.

Course and membership Journey Intent forms are persisted in the Course template and page 933. Their presence and action names are confirmed; no form was submitted. Current ACF relationship metadata contains one Article-to-Person expert edge and one Course-to-Person additional-instructor edge, plus one stale reverse Course reference. Legacy Event-to-Medewerker and Product-to-Medewerker edges remain; canonical Event Participation and Product Contribution records are empty.

Confidence is **MEDIUM**: persisted database and source metadata were inspected directly, while rendering, route resolution, keyboard behavior, screen-reader announcements, contrast, and responsive appearance remain unverified. Runtime verification is **PARTIAL**.

## 2. Inspection Scope and Evidence

### Authorities and reports read

- `docs/evaluation/COORDINATION_BRIEF_v0.1.0.md`, including its twelve required headings, inventory columns, status vocabulary, and no-numerical-confidence instruction.
- `docs/evaluation/PARENT_BASELINE_v0.1.0.md` and `docs/evaluation/PARENT_REVIEW_NOTES_v0.1.0.md`.
- `docs/evaluation/01_RUNTIME_FOUNDATION_v0.1.0.md` through `07_MEMBERSHIPS_v0.1.0.md`, with direct attention to reports 03–07 and the homepage, course, event, commerce, and relationship contradictions they record.
- `docs/architecture/LEGACY_DECOMMISSION_REGISTER.md`, especially `/content/`, `/leer/`, `/winkel/`, `/events/`, Person, and `medewerker` entries.
- `docs/superpowers/plans/2026-11-10-vgn-5b-runtime-binding_v0.1.1.md` and applicable runtime-mutation/deployment authority records.
- Root `AGENTS.md` and `voelgoed-member-perks/AGENTS.md`.
- Installed `bricks-browser-verify`, `bricks-site-audit`, `unslop`, and `task-assessment` skills.

The nested Member Perks instructions were used for ownership boundaries: PMPro owns membership access, WooCommerce owns orders and billing, Masteriyo owns course delivery, Tickera owns event ticketing, and Member Perks owns perk configuration and claims. Bricks is a display surface. No Member Perks code or runtime state was changed.

### Read-only evidence collected

- `git rev-parse HEAD` returned `454346d9e6a2c36864a4bbd944e52596f16e09c1`.
- `mcp__t3_code__preview_status({})` returned `available: false`, `tabId: null`, and no URL. No `preview_open`, navigation, or snapshot was possible.
- The callable Bricks MCP entries were legacy aliases whose descriptions identify them as Novamira aliases. They were not called, and the Bricks endpoint was not requested.
- Local fallback used a PHP `mysqli` client against the LocalWP database with `MYSQLI_TRANS_START_READ_ONLY`, targeted `SELECT` statements over `wp_posts`, `wp_postmeta`, and `wp_options`, and `ROLLBACK`. The WordPress runtime was not bootstrapped for these reads. No database credentials were printed by the queries.
- Current Bricks data was read from serialized `_bricks_page_content_2`, `_bricks_page_header_2`, and Bricks template-setting metadata. Element trees were summarized for IDs 1926, 933, 77, 78, 1919, 774, 124, 1733, 1734, 1838, 1859, 372, 369, 137, 280, 614, 1090, 772, 773, 731, 134, 690, 879, 1162, and 1172. Persisted Bricks forms were scanned across pages and templates.
- Current Plane work items, states, and comments were read. GitHub pull-request metadata was read with GET-only fetches for PRs #91, #92, and #95.
- Current Bricks source was read for default-template behavior. Current Journey Intent source was read for its documented state and guard logic.
- `sha256sum` and `diff -u` compared the source and runtime copies of FluentSnippet 10. The runtime copy adds the FluentSnippets metadata/guard wrapper; the remaining PHP body matches the source file.

No page was visited. No login, authentication-link creation, form submission, intent start/dismissal, cart/add-to-cart, order, thank-you, enrolment, or other runtime write was performed. No tests, Hallmark lint, or redesign audit were run. Hallmark results mentioned below are historical PR claims only.

### Evidence labels

- `RUNTIME_CONFIRMED`: current LocalWP persisted state read on 2026-11-19.
- `SOURCE_CONFIRMED`: current repository source at the stated HEAD.
- `HISTORICAL_ONLY`: previous report, Plane comment, or PR claim not re-run here.
- `INFERRED`: conclusion from persisted configuration or installed Bricks source, not from a rendered page.
- `UNKNOWN`: the read-only evidence does not resolve the point.
- `CONFLICT`: sources report different facts or describe different states that need reconciliation.

## 3. Component Inventory

The runtime contains 32 non-trashed Bricks templates: 30 published and two drafts. The table separates persisted existence from stored conditions. A saved template type or condition does not establish that the frontend rendered it.

| Component | ID/Slug/Path | State | Owner | Purpose | Consumers | Evidence |
|---|---|---|---|---|---|---|
| Header | 77 / `header` | Published; default global header | Bricks | Main and responsive navigation | Site pages | `RUNTIME_CONFIRMED`; 132 stored elements; Bricks default-header rule applies when no conditions exist and default templates are enabled |
| Footer | 78 / `footer` | Published; default global footer; no saved content tree found | Bricks | Global footer | Site pages | `RUNTIME_CONFIRMED`; template post exists but no Bricks content/header/footer tree was stored |
| Cart | 79 / `cart` | Published; `wc_cart` default type | Bricks/WooCommerce | Cart surface | Cart route | `RUNTIME_CONFIRMED`; not opened |
| Checkout | 80 / `checkout` | Published; `wc_form_checkout` default type | Bricks/WooCommerce | Checkout form and summary | Checkout route | `RUNTIME_CONFIRMED`; not opened |
| Login | 113 / `login` | Published; `wc_account_form_login` default type | Bricks/WooCommerce | Login form | Account routes | `RUNTIME_CONFIRMED`; persisted form action `login`; not opened |
| Lost Password | 114 / `lost-password` | Published; `wc_account_form_lost_password` default type | Bricks/WooCommerce | Password-recovery form | Account routes | `RUNTIME_CONFIRMED`; persisted form action `lost-password`; not opened |
| Reset Password | 120 / `reset-passowrd` | Published; `wc_account_reset_password` default type | Bricks/WooCommerce | Password-reset form | Account routes | `RUNTIME_CONFIRMED`; persisted form action `reset-password`; not opened |
| Single Product | 124 / `single-product` | Published; `wc_product` default type | Bricks/WooCommerce | Product details, price, stock, add-to-cart, sharing, accordion | `/winkel/{slug}/` | `RUNTIME_CONFIRMED`; 82 stored elements; includes `product-add-to-cart` and Code elements |
| Thank You Page | 128 / `thank-you-page` | Published; `wc_thankyou` default type | Bricks/WooCommerce | Post-order confirmation | Thank-you route | `RUNTIME_CONFIRMED`; thank-you element present; route deliberately not opened |
| Card | 134 / `card` | Published section; no placement conditions | Bricks | Reusable product-card composition | Consumers not confirmed | `RUNTIME_CONFIRMED`; 58 stored elements; product-card classes present |
| Kalender | 137 / `kalender` | Published content template; no placement conditions | Bricks/ClassicMonks query filters | Event directory/search layout | No route assignment confirmed | `RUNTIME_CONFIRMED`; event query plus search, date, radio, range, and submit filters |
| Kalender_2 | 280 / `kalender_2` | Published content template; no placement conditions | Bricks/ClassicMonks query filters | Alternate event directory/search layout | No route assignment confirmed | `RUNTIME_CONFIRMED`; event loops plus search, date, and checkbox filters |
| Menu GSAP | 320 / `menu-gsap` | Published section; no placement conditions | Bricks/child theme | Menu/animation composition | Consumers not confirmed | `RUNTIME_CONFIRMED`; no render or consumer found in this review |
| Category Archive | 369 / `category-archive` | Published archive; no explicit condition; Bricks default is all archives | Bricks | Generic archive presentation | Unshadowed archive routes | `RUNTIME_CONFIRMED` metadata; default assignment is `INFERRED` from Bricks 2.4.5 source |
| Posts | 372 / `posts` | Published content template; condition `postType=post` | Bricks | Single Article presentation | `/content/{slug}/` | `RUNTIME_CONFIRMED`; title says “Posts” but stored type/condition identify a post single; ACF author and review loops present |
| Test 2 | 428 / no slug | Draft; no template-type metadata | Bricks | Unclear test artifact | None confirmed | `RUNTIME_CONFIRMED`; draft and no type/condition assignment |
| Voelgoed Live Archive | 614 / `voelgoed-live-archive` | Published archive; condition `postType=uitsending` | Bricks | Episode archive | `/voelgoed-live/` | `RUNTIME_CONFIRMED`; queries replay/scheduled episode states and `broadcast_series` terms |
| Article Card | 690 / `article-card` | Published section; no placement conditions | Bricks | Intended article card | Consumers not confirmed | `RUNTIME_CONFIRMED`; only one stored block, 222-byte tree, no class references |
| Product Card | 731 / `product-card` | Published section; no placement conditions | Bricks | Product card and related-product loops | Product and partner consumers require exact assignment confirmation | `RUNTIME_CONFIRMED`; 44 elements; renders legacy `content_category` tag |
| Medewerker Single | 772 / `medewerker-single` | Published content template; condition `postType=medewerker` | Bricks | Transitional profile single | `/medewerkers/{slug}/` | `RUNTIME_CONFIRMED`; ACF title/bio and legacy query-helper markers present |
| Medewerker Archive | 773 / `medewerker-archive` | Published archive; condition `postType=medewerker` | Bricks | Transitional profile archive | `/medewerkers/` | `RUNTIME_CONFIRMED`; query loop for `medewerker` |
| Event Single | 774 / `event-single` | Published content template; condition `postType=tc_events` | Bricks/Tickera | Event detail and ticket/product information | `/events/{slug}/` | `RUNTIME_CONFIRMED`; canonical participation and event-linked product loops; includes add-to-cart element |
| Frames Components | 879 / `frames-components-2` | Published content template; no placement conditions | Bricks/Frames | Frames composition/reference content | Consumers not confirmed | `RUNTIME_CONFIRMED`; 39 elements, 28 sections; no route assignment found |
| Voelgoed Live Series Archive | 1090 / `voelgoed-live-series-archive-2` | Published archive; condition `broadcast_series::all` | Bricks | Series-term archive | `broadcast_series` term routes | `RUNTIME_CONFIRMED`; episode and term queries |
| Voelgoed Live Single | 1127 / `voelgoed-live-single` | Published content template; condition `postType=uitsending` | Bricks | Episode detail | `/voelgoed-live/{slug}/` | `RUNTIME_CONFIRMED`; condition and preview post stored |
| Unified Customer Dashboard Shell | 1162 / `unified-customer-dashboard-shell` | Published content template; no conditions recorded | Bricks | Customer dashboard shell | Dashboard pages | `RUNTIME_CONFIRMED`; render/assignment not verified |
| Unified Customer Dashboard Section Shell | 1172 / `unified-customer-dashboard-section-shell` | Published section; hooked after header on page IDs 1155–1161 | Bricks | Dashboard section wrapper | Seven dashboard pages | `RUNTIME_CONFIRMED`; `bricks_after_header`, priority 10 |
| Masteriyo Single Course | 1733 / `masteriyo-single-course-default` | Published Masteriyo single-course template | Masteriyo/Bricks | Course detail, instructor relationships, Journey Intent form | `/leer/{slug}/` | `RUNTIME_CONFIRMED`; native Masteriyo elements, ACF person queries, persisted `vgpc_journey_intent` form |
| Person Single | 1734 / `person-single` | Published content template; condition `postType=person` | Bricks/ACF | Canonical Person profile and reverse relationships | `/mense/{slug}/` | `RUNTIME_CONFIRMED`; seven Article/Course reverse query sections |
| Masteriyo Course Archive | 1838 / `masteriyo-course-archive-default` | Published Masteriyo archive template | Masteriyo/Bricks | Course catalog/search | `/kursusse/` | `RUNTIME_CONFIRMED`; native `courses`, course search, and view-mode elements |
| Nuwe Jy Discovery Hub | 1859 / `nuwe-jy-discovery-hub` | Published archive; condition `content_topic::349`, children excluded | Bricks/Platform Core | Topic discovery and Article loop | Nuwe Jy topic route | `RUNTIME_CONFIRMED`; topic term and published post queries |
| Event Landing — Frames | 1919 / draft, no slug | Draft content template; empty condition list; preview event 542 | Bricks/ACF/Tickera | Optional enriched event landing | No frontend placement | `RUNTIME_CONFIRMED`; no assignment; all five optional ACF values empty |

Other relevant persisted resources:

| Component | ID/Slug/Path | State | Owner | Purpose | Consumers | Evidence |
|---|---|---|---|---|---|---|
| Home: Featured destination | ACF group 1928 / field 1929 / page 1926 | Group active; page draft; no value stored | ACF/Bricks | Optional page-scoped featured destination | Draft homepage 1926 | `RUNTIME_CONFIRMED`; no `home_featured_destination` or `_home_featured_destination` meta row |
| Event Landing ACF | Group 1913 / fields 1914–1918 | Active DB group; all values empty | ACF/Bricks | Testimonials, gallery, video, programme, information image | Draft template 1919 | `RUNTIME_CONFIRMED`; no saved values on current Events |
| Bricks global classes | `bricks_global_classes` | 1,176 records | Bricks/ACSS/Frames | Shared classes | Site templates/pages | `RUNTIME_CONFIRMED`; names include Frames `fr-*`, old `bp-lx-*`, and `acss_import_*` classes |
| Bricks global variables | `bricks_global_variables` | 208 records | Bricks/ACSS | Shared design variables | Site templates/pages | `RUNTIME_CONFIRMED`; includes navigation, brand, and grid variables |
| Bricks components | `bricks_components` | 61 records | Bricks/Frames | Reusable component library | Builder compositions | `RUNTIME_CONFIRMED`; record count only, not a component-by-component consumer audit |
| Palettes and theme styles | `bricks_color_palette`, `bricks_theme_styles` | 10 palettes; 2 theme-style groups | Bricks/ACSS/Frames | Global colors and styles | Site templates/pages | `RUNTIME_CONFIRMED`; palette includes ACSS Global; theme styles include `standard` and `frames_(ignore)` |
| Automatic.css | Plugin 4.0.3; `automatic_css_settings` | Active | Automatic.css | Utility classes, variables, typography, spacing | Bricks and Frames compositions | `RUNTIME_CONFIRMED` from plugin inventory and option metadata; no visual quality claim |
| Frames | Plugin 1.5.13; Frames remote-template resource | Active | Frames | Bricks layouts and components | Header, dashboard, event draft, components | `RUNTIME_CONFIRMED` from plugin inventory and Bricks resource records |
| FluentSnippets | `fluent-snippet-storage/index.php` | Nine snippets marked `published`; `force_disabled=no` | FluentSnippets | Existing site integrations | Bricks, ACF, Tickera, legacy taxonomy | `RUNTIME_CONFIRMED`; eight current runtime files have no source counterpart in `site-code/fluent-snippets` |
| Medewerker query helper | `site-code/fluent-snippets/10-vg-bricks-medewerker-queries.php` | Source and runtime copy present; runtime copy published | FluentSnippets | Supplies `vg_query_type` Bricks queries | Templates 772, 124, 774, 1127 | `SOURCE_CONFIRMED`/`RUNTIME_CONFIRMED`; runtime adds FluentSnippets wrapper metadata; body matches source |
| Journey Intent forms | Page 933; template 1733 | Two persisted forms, no submission in this review | Platform Core/Bricks | Start or dismiss a course intent | Course detail and Voelgoedvriendinne page | `RUNTIME_CONFIRMED` for form/action markers; behavior not retested |

## 4. Current Architecture and Configuration

### Bricks assignment rules

Bricks 2.4.5 treats a published template with no `templateConditions` as its default template when that template type is in `Database::$default_template_types` and `defaultTemplatesDisabled` is false. The installed source shows the fallback in `themes/bricks/includes/admin.php:1848–1855` and the default type list in `themes/bricks/includes/database.php:11–32`. The `defaultTemplatesDisabled` key is not stored in current `bricks_global_settings`; the Bricks source default is `false`. Therefore Header 77, Footer 78, generic Archive 369, and the published WooCommerce default templates are assigned by Bricks' default behavior (`INFERRED`). The front-end selection was not rendered.

The `templateConditions` stored in Bricks template settings include:

- 372 → `postType=post`.
- 614 → archive of `uitsending`.
- 772 → `postType=medewerker`; 773 → archive of `medewerker`.
- 774 → `postType=tc_events`.
- 1090 → term archive `broadcast_series::all`.
- 1127 → `postType=uitsending`.
- 1172 → page IDs 1155–1161, injected at `bricks_after_header` priority 10.
- 1734 → `postType=person`.
- 1859 → term archive `content_topic::349`, children excluded.
- 1919 → an empty condition list while the template remains a draft.

Template types for 1733 and 1838 are Masteriyo integration types. They have no explicit Bricks condition metadata. Their runtime route behavior is not established by this database read alone.

### Homepage 1926

Page 1926 (`Voelgoed Tuis`, slug `voelgoed-tuis`) is `draft`. WordPress has `show_on_front=posts`, `page_on_front=0`, and `page_for_posts=0`. The page is not the site front page.

The saved tree has 29 elements, a Bricks Search element, and a native taxonomy loop for the seven `content_topic` root IDs 349, 363, 370, 377, 388, 396, and 404. It contains a post loop guarded by a nonempty `home_featured_destination` ACF condition and a type guard for page, product, course, or Tickera event. The CTA links resolve in saved data to `/`, `/events/`, `/kursusse/`, and `/produkte/`.

The active field group contains one Post Object field, targeting `page`, `tc_events`, `mto-course`, or `product`. The current page has no stored field value or value marker. Earlier `1/1` wording in report 04 refers to one field in the group, not one selected post. There is no target ID whose publication status can be checked; target validity is not applicable until an editor selects a target.

### Navigation and global design resources

Header 77 is a 132-element Bricks header with two nested navigation elements, dropdowns, a mobile-menu setting, Frames classes, and text links. There are no WordPress `nav_menu` terms in the current runtime; navigation is stored in Bricks. The only in-site path decoded from the link settings is `/onderwerp/nuwe-jy/`. Other link settings were not safely resolved to current routes. The labels include legacy topics, English labels, and a placeholder telephone entry. No claim is made about what visitors currently see.

The saved `Kalender` and `Kalender_2` trees connect Bricks Query Filters to Tickera `tc_events` loops through `filterQueryId`. Bricks global settings have `enableQueryFilters=true` and `enableQueryFiltersIntegration=true`. Both templates are content-type templates with no placement conditions, so an active public event-search route is not confirmed.

The ACSS/Frames resources are substantial and mixed. Persisted classes include Frames BEM families such as `fr-woo-product-bravo`, ACSS imports such as `acss_import_focus-parent`, legacy `bp-lx-*`, and older custom product-card classes. This is evidence of existing shared styling, not evidence that the classes are redundant or visually consistent. No class cleanup or Hallmark audit was run.

The stored Bricks `saveFormSubmissions` setting is true. This review only read form configuration; it did not submit any form.

## 5. How It Works

### Journey Intent path

The current persisted Course template 1733 and page 933 each contain a Bricks form whose action is `vgpc_journey_intent`. The source owner is Platform Core, not ACF or Bricks. `JourneyIntentService` defines `ACTIVE`, `SUPERSEDED`, `DISMISSED`, `COMPLETED`, `INVALID`, and `EXPIRED` at `site-code/voelgoed-platform-core/includes/JourneyIntent/JourneyIntentService.php:11–16`.

The source guards the lifecycle as follows: an explicit Course CTA starts or refreshes an intent only after the Course and public destination validate; a same-current active Course request can refresh the current intent; authentication can promote a guest intent; No Thanks dismisses only the current active intent whose ID matches the submitted ID; stale IDs and non-active states fail closed; expiry or invalidation is recorded when observed; a later explicit Start creates a new active intent. The Bricks handler validates the nonce and same-origin request before dispatch, then redirects only an explicitly started active intent to the membership page. The fixed return endpoint selects a destination from validated server state. These behaviors are source-confirmed, not re-exercised here.

The documented Golden Journey is Course 1889 → explicit Vriendinne intent → page 933 → return or dismiss. The form action and page are persisted, while no action was triggered in this review. Plane VGN-5 comments report prior headed tests and a finalized deployment. The 17 November latest comment says the final screen-reader test was deferred; this report does not treat that as a pass.

### Relationship and domain ownership

Article templates use ACF fields `primary_author`, `additional_authors`, `expert_contributors`, and `reviewed_by`, with Bricks loops to Person. Course template 1733 uses `lead_instructor`, `additional_instructors`, and `experts`, with Bricks loops to Person. Person template 1734 uses the reverse ACF fields to query Articles and Courses. This follows the native ACF relationship and Bricks Query Loop approach.

Event Single 774 queries canonical `event_participation` records and event-linked products. Event Landing 1919 conditionally displays testimonials, gallery, video, programme, information image, participant records, and linked products. The canonical participation data is empty and all optional Event Landing fields are empty. The current event-related fallback consumers still use legacy `event_medewerkers` and `produk_medewerkers` through FluentSnippet 10 and runtime-only snippet 9.

Product Contribution and Event Participation remain first-class relationship domains with role/order fields; this report does not recommend replacing them with plain ACF bidirectional fields. Their current record counts are zero. The product people mode is blank, so the canonical Product Contribution display path has no selected mode to resolve.

### Commerce and course delivery

Bricks template 124 includes WooCommerce product price, stock, breadcrumb, add-to-cart, and sharing elements. Event Single 774 also has an add-to-cart element. Course template 1733 uses Masteriyo-native course elements and ACF Person fields. Product and course route behavior was not exercised. The work item VGN-18 comment on 17 November records a prior checkout render with no guest payment methods and no submitted order; that historical result is not a current checkout verification.

## 6. Source Versus Runtime

| Area | Source state | Current runtime state | Assessment |
|---|---|---|---|
| Protected main | HEAD `454346d9e6a2c36864a4bbd944e52596f16e09c1` | LocalWP read target `nAZNpFs99` | Main source and runtime target identified; no source build or deployment was run |
| Homepage 1926 / PR #92 | PR head `548d5895e285317d7635f8a2bd1ccb4bf28c8109`, base `fcb5fd8bb954b20ea957690b986eb78908f733ca`; GitHub says `open`, `draft` | Page 1926 exists as `draft`, not assigned to front page | `RUNTIME_APPLIED` as a draft; `SOURCE_MERGED=NO`; `BEHAVIOR_VERIFIED=NO` in this review; no publication |
| Event Landing 1919 / PR #91 | PR head `4508a2d0691cfe525e5039b5d1511cc49c1c1a11`, base `fcb5fd8bb954b20ea957690b986eb78908f733ca`; GitHub says `open`, `draft` | Template 1919 exists as `draft`, with no conditions | `RUNTIME_APPLIED` as a draft; `SOURCE_MERGED=NO`; no frontend placement or publication |
| Home ACF group | Report 04 at the same snapshot reports DB group 1928; PR #92 carries source JSON | Active DB group, one field, no selected value | `CONFLICT` resolved: prior `1/1` counted schema fields, not populated content |
| Event Landing ACF group | Report 04 says source file was absent on main; PR #91 carries source JSON | Active DB group 1913; five values empty | Runtime schema exists; source ownership remains pending PR review |
| Journey Intent | Platform Core source is on main; Plane VGN-5 records the 17 November deployment | Current form markers are stored in Bricks 1733 and 933 | `SOURCE_MERGED=YES`; current form marker `RUNTIME_CONFIRMED`; behavior evidence remains historical for this review |
| FluentSnippet 10 | `site-code/fluent-snippets/10-vg-bricks-medewerker-queries.php` | Published runtime copy exists in `fluent-snippet-storage` | Runtime wrapper differs from source; diff shows the remaining PHP body matches. Eight other published runtime snippets have no source counterpart in the source tree. |

PR #91 and #92 descriptions record prior browser/Hallmark verification on their draft candidates. Those reports do not establish that the current runtime routes rendered, that current main contains the drafts, or that a visitor-facing page is approved. The PRs remain open drafts and no publication or merge was performed.

## 7. Completeness and Functionality

The following map separates route existence, template assignment, persisted data, and current render verification. Routes are based on current runtime registry and the legacy decommission register; this review made no HTTP request to them.

| Route or page | Persisted route/data | Bricks assignment | Render / behavior evidence |
|---|---|---|---|
| `/` | WordPress posts front; `page_on_front=0` | No current homepage page assigned | Current route not rendered |
| `/content/` | No published page with slug `content`; no post archive | No editorial index template confirmed; template 372 is a single post | Missing index is `RUNTIME_CONFIRMED` by the decommission register; no current request |
| `/content/{slug}/` | Standard post permalink base | Template 372, condition `postType=post` | Persisted ACF role loops confirmed; not rendered |
| `/events/` and event singles | Tickera `tc_events` archive and singles | Event single 774; no dedicated event archive condition found. Generic archive 369 is the default fallback. Templates 137 and 280 contain event filters but no placement conditions. | No event route rendered; filters are persisted but active public use is unknown |
| `/leer/{slug}/` | Masteriyo single route | Masteriyo type template 1733 | Persisted Masteriyo/ACF/Journey Intent composition confirmed; no page render or form action |
| `/kursusse/` | Current Masteriyo archive path; published page 57 is “Course Catalog” | Masteriyo archive template 1838; page/template selection not rendered | Current path is transitional under the approved URL register |
| `/mense/{slug}/` | Canonical Person profile route; one Person record is reported in the ACF snapshot | Person Single 1734, condition `postType=person` | Seven reverse relationship sections persisted; no render |
| `/mense/` | Canonical Person destination | No dedicated Person archive template; generic archive 369 is the default archive candidate | Actual selection not rendered |
| `/medewerkers/` and profile singles | Transitional ACF CPT; five published profiles in report 04 | Archive 773 and single 772 | Current page/profile not rendered; do not infer a completed Person migration |
| `/winkel/{slug}/` | WooCommerce single product path | Single Product 124 | Product 1935 HTTP 404 is only a 17 November historical VGN-53 report; no current GET was made |
| `/produkte/` | Published Shop page 71; current catalog path | No dedicated Bricks `wc_archive` template found; Product Card 731 exists as a section | Shop route not rendered; `/winkel/` remains the canonical Shop area in the register |
| `/voelgoed-live/` | `uitsending` archive | Template 614, condition `postType=uitsending` | Queries persisted; not rendered |
| Broadcast series terms | `broadcast_series` term taxonomy | Template 1090, condition `broadcast_series::all` | Queries persisted; current term route not rendered |
| `/voelgoed-live/{slug}/` | `uitsending` single | Template 1127, condition `postType=uitsending` | Not rendered |
| `/kompetisie/` | Competition archive defined in the CPT registry | No competition-specific Bricks archive condition found; generic archive 369 is the default candidate | Not rendered |
| `/voelgoedvriendinne/` (page 933) | Published page with a persisted Journey Intent form | Page-level Bricks tree | Form action confirmed; no request or submission |
| Page 1926 `/voelgoed-tuis/` | Draft page, optional featured destination field empty | Page itself, not a template; not the front page | Persisted search/topic/query/CTA tree confirmed; no render |
| `/cart/`, `/checkout/`, login, recovery, and thank-you flows | Bricks templates exist | WooCommerce default template types | Deliberately not opened; no flow behavior verified |
| Dashboard pages 1155–1161 | Page IDs are named in template 1172 settings | Section shell 1172 inserted after header | Hook assignment persisted; dashboard pages not rendered |

Persisted Bricks form inventory: template 113 `login`; template 114 `lost-password`; template 120 `reset-password`; template 1733 `vgpc_journey_intent`; page 933 `vgpc_journey_intent`. This confirms stored form actions only. It does not prove submission success or error states.

## 8. Problems, Risks and Contradictions

| Priority | Finding | Evidence and impact | Status |
|---|---|---|---|
| P1 | `/content/` editorial index is absent | The decommission register says there is no post archive and no published page at `/content/`; Bricks 372 is a single-post template. The homepage “Lees” link points to `/`. | `RUNTIME_CONFIRMED` by current page/options and register; route render not tested |
| P1 | Generic archive template is the only default for several archive families | Template 369 has no explicit condition and Bricks defaults it to all archives. There is no dedicated Person, Tickera Event, or Competition archive condition. Its saved `post_terms_category` references core `category`, while `content_category` remains a separate legacy taxonomy. | Assignment `INFERRED`; render/selection unknown |
| P1 | Global Footer template is empty | Published template 78 has a footer type but no saved Bricks element tree. | `RUNTIME_CONFIRMED` for persisted data; visitor output not rendered |
| P1 | Header navigation contains unresolved destinations and legacy/placeholder labels | Bricks-authored menu, no WP menu terms; several link targets were not resolvable from stored link settings. Labels include English items, legacy topic links, and a placeholder phone entry. Only Nuwe Jy resolved to `/onderwerp/nuwe-jy/`. | `RUNTIME_CONFIRMED` for stored labels/settings; current visibility unknown |
| P1 | Homepage draft links to transitional catalog routes | Page 1926 points to `/kursusse/` and `/produkte/`; the route register keeps `/leer/` canonical for Learning and `/winkel/` canonical for Shop. | `RUNTIME_CONFIRMED` for draft links; not public because page remains draft |
| P2 | Homepage destination is not selected | No field value exists on page 1926. Its optional section has a nonempty dynamic-data condition, so the section is expected to stay hidden until selection. | No target exists to validate; target-status check is N/A |
| P2 | Event Landing is a draft with no placement and empty content fields | Template 1919 has no conditions; all five ACF fields are empty on current events. Event Single 774 remains the assigned event single. | `RUNTIME_CONFIRMED`; no visual result |
| P2 | Two event directory templates have no placement conditions | Templates 137 and 280 include overlapping event loops and different filter sets; neither is a route assignment. | `RUNTIME_CONFIRMED`; active use unknown |
| P2 | Article Card 690 is nearly empty | It is published but contains one block, 222 bytes, no global class, and no consumer found in this review. | `RUNTIME_CONFIRMED` for content; removal not recommended without consumer search |
| P2 | Canonical relationship domains have no records while legacy edges remain | Event Participation and Product Contribution each have zero records; product mode has no nonempty value. Legacy event fields hold four edges; legacy product people hold three edges. | `RUNTIME_CONFIRMED`; migration gates remain |
| P2 | One reverse Course reference is stale | Forward Course additional-instructor has one edge; reverse Person field has two references. The Person template also requires query results for one relevant section, which may suppress a stale result, but render was not checked. | `RUNTIME_CONFIRMED` for metadata; output is `INFERRED` |
| P2 | Product 1935 route remains unresolved | VGN-53 records a 17 November 404. The runtime MU plugin's query callback can exclude a linked course product from product queries; the source-only harness made this a possible explanation. No current route request was made, so this report makes no current 404 claim. | 404 `HISTORICAL_ONLY`; explanation `INFERRED` |
| P1 security note | A stored Frames remote-template credential was accidentally included in one raw settings tool result during this review | The value is omitted from this report and is not repeated here. No remote request or runtime write used it. Treat the credential as exposed, rotate it, and review relevant access records. Its current validity and access scope are unknown. | Exposure is confirmed; owner action required; no rotation was performed |

The prior report 04 phrase “1/1” was a schema-field count, not a populated homepage destination. Current runtime metadata and the VGN-52 description both show no selected destination. The difference is resolved by interpreting the earlier count as group/field inventory rather than data population.

The VGN-53 issue description is evidence for a 17 November observation only. The code path at runtime `wp-content/mu-plugins/vg-masteriyo-course-products.php:150–196` is a plausible interaction with the product single query. No current 404 response was observed.

## 9. Redundancy Candidates

No removal is recommended from this read-only audit. The following items need a consumer and route check before any cleanup:

| Candidate | Why it merits review | Current consumers / replacement | Safe next step |
|---|---|---|---|
| `Kalender` 137 vs `Kalender_2` 280 | Both contain Tickera event loops; one uses search/date/radio/range, the other search/date/checkbox. Neither has placement conditions. | No public assignment confirmed; native Bricks Query Filters can remain the implementation. | Compare intended editor journey and direct page references before choosing one; keep both until route ownership is verified. |
| Article Card 690 | Published tree has one empty block. | No direct consumer found. Category Archive 369 and Person Single 1734 have their own Frames article-card structures. | Check Bricks references and Builder use before deactivation or deletion. |
| Card 134 vs Product Card 731 | Both store product-card classes and presentation elements; 731 contains product loops and legacy category tags. | Consumer relationship is incomplete; source/decommission records identify 731 as a `content_category` consumer. | Map exact page/template references and migrate taxonomy consumers before changing either. |
| Event Single 774 vs Event Landing 1919 | The draft landing adds optional editorial sections around the existing event single. | 774 is the published `tc_events` single; 1919 is a draft with no placement. | Preserve separate roles; test 1919 only after content and placement decisions are approved. |
| `medewerker` and canonical `person` | Both profile systems exist during an approved dual-run. | `medewerker` still has consumers and relationships; Person 1705 is canonical for the approved identity. | Follow the existing profile migration register; do not create a second Person system or retire `medewerker` here. |
| `broadcast_series` and `uitsending_series` | Similar episode-series structures and routes are recorded in the decommission register. | Templates 614/1090 use `broadcast_series`; the register lists other taxonomy consumers. | Map terms, links, templates, and SEO before any consolidation. |
| 1,176 global classes and 208 variables | High record counts make overlap possible, but count alone proves no duplication. | Global Bricks/ACSS/Frames styling and current templates. | Run a separate authorized design-system audit with consumer mapping; no cleanup in this report. |

## 10. Recommendations

1. Contain the confirmed remote-template credential exposure. The owner should rotate the credential and review access records. Do not copy it into an issue, report, command, or screenshot.
2. Reconcile PRs #91 and #92 against current main and obtain the required independent PR review. Keep both drafts unpublished until the exact candidate, checks, and approvals are current.
3. Use a future browser session to verify safe, read-only routes after T3 is available and the request bootstrap path is reviewed. Keep checkout, thank-you, account mutation, add-to-cart, and Journey Intent actions excluded unless separately authorized.
4. Resolve the editorial index and navigation routes in a dedicated route task. Preserve `/content/{slug}/`; decide the `/content/` index; map header destinations; align the homepage draft CTAs with the approved Learning and Shop routes without changing current runtime URLs here.
5. Verify the global header and Footer output on a safe route, including empty-footer behavior, menu targets, focus order, mobile menu, and placeholder content.
6. Keep Bricks Query Loops and ACF relationships as the default for Article/Course-to-Person relationships. Map legacy Event/Product edges and their consumers before any data migration or snippet retirement.
7. Confirm the intended event archive owner. The current Event Single is assigned, while the two event directory layouts are unassigned. Use the existing Bricks/Query Filters and Tickera configuration where they meet the approved journey.
8. Treat the historical Hallmark results in PRs #91 and #92 as candidate-specific claims. A current read-only browser and accessibility pass is still required before any current visual or launch claim.

No new custom subsystem is recommended. Existing WordPress, Bricks, ACF, WooCommerce, Masteriyo, Tickera, and Platform Core components already express the inspected data model; current gaps are route/assignment/content verification and approved migration work.

## 11. Outstanding Verification

- Current visual render for `/`, `/content/`, article single, `/events/`, event single, course single/archive, product single/shop, Person and Medewerker pages, and Voelgoed Live routes. T3 preview is unavailable; no route request was made.
- Actual Bricks template selection for default archives, shop, and Masteriyo archive pages.
- Header target values for labels other than the single decoded Nuwe Jy path; menu destinations and remote links need safe normalization.
- Footer output, responsive navigation behavior, mobile breakpoint appearance, keyboard focus, contrast, reduced motion, screen-reader announcements, image alt text, and empty/error/loading states.
- Current behavior of event filters and filters’ AJAX/query integration.
- Journey Intent submission, dismissal, redirect, return, and failure flows. The form action is persisted, but not run. VGN-5's latest comment `d6b942d4-e0dd-4d9a-a73a-f57730a2ea87` says the screen-reader test was deferred; no accessibility pass is claimed.
- Current status of Product 1935's permalink. VGN-53 reports a historical 17 November 404; no safe current request was made.
- Whether `event_participation` and `product_contribution` records should be populated for real journeys; no business-rule change is inferred.
- Exact active use of Card 134, Article Card 690, Product Card 731, Frames Components 879, and unassigned event templates.
- Independent review and current CI for the exact PR #91/#92 heads. This report did not fetch or validate those workflow checks.
- Credential containment and rotation. This remains an owner action.

## 12. Final Domain Assessment

### Domain status

The Bricks data model is substantially present, but this snapshot does not prove current visitor-facing behavior. Thirty-two templates and the relevant query/form/resource configuration were read. The root page remains the posts index. Page 1926 and template 1919 are persisted drafts. The `/content/` index, current archive ownership, header destinations, footer content, accessibility, and route rendering remain open verification items.

| Dimension | Status | Evidence |
|---|---|---|
| `SOURCE_MERGED` | Main source is at `454346d9e6a2c36864a4bbd944e52596f16e09c1`; PRs #91/#92 are not merged | GitHub GET metadata; local HEAD |
| `RUNTIME_APPLIED` | Page 1926, template 1919, templates, forms, relationships, and global resources are present in LocalWP | Current read-only database snapshot |
| `BEHAVIOR_VERIFIED` | Partial only; prior PR/Plane results are historical | No current browser tab or route render |
| `BUSINESS_APPROVED` | Homepage layout A and draft event template were approved for draft work; no publication authorization found | VGN-52/VGN-51 descriptions and open draft PRs |
| `LAUNCH_READY` | Not established | Missing routes, current visual/accessibility evidence, and business content checks |

### Completion Assessment

- **Task type:** RESEARCH
- **Task / issue:** VGN-55 Phase 6, read-only Bricks/frontend inventory; deliver only this evaluation report.
- **Assessment role:** Research/documentation self-assessment.
- **Acceptance criteria:** PASS 12/15; FAIL 0; UNVERIFIED 3; NOT_DONE 0.

| Criterion | Status | Evidence |
|---|---|---|
| Read coordination brief, parent baseline, review notes, reports 01–07, relevant authorities, and both required Bricks skills | PASS | Source files listed in section 2 |
| Use dedicated Bricks MCP only, or a safe local fallback; do not use Novamira for Bricks | PASS | No dedicated Bricks callable tool; read-only SQL fallback used; legacy aliases not called |
| Check T3 status and avoid unsafe route/action requests | PASS | `preview_status` returned unavailable; no page or form request was made |
| Enumerate all non-trashed Bricks templates and stored conditions | PASS | 32 templates listed in section 3; conditions and default-template behavior recorded |
| Map relevant pages, routes, ACF bindings, loops, filters, CTAs, and forms | PASS | Sections 4, 5, and 7; current Bricks trees read from LocalWP |
| Inspect ACSS, Frames, global styles, and components without redesign/audit | PASS | Current option/resource counts and classes recorded; no cleanup performed |
| Confirm current relationship and snippet markers | PASS | Current ACF postmeta and FluentSnippets index inspected |
| Confirm page 1926 destination value and status | PASS | Page draft; no field-value row; no target ID exists to validate |
| Confirm template 1919, conditions, and pending PR #91 | PASS | Current runtime draft/no conditions; GitHub says PR open/draft |
| Confirm pending PR #92 and no homepage publication | PASS | Current runtime page draft and not front page; GitHub says PR open/draft |
| Keep VGN-53 404 historical unless a current safe request verifies it | PASS | No current GET; report labels the 404 historical |
| Connect Golden Journey without submitting or changing state | PASS | Form markers and source lifecycle read; no form submitted |
| Verify current rendered templates, routes, and responsive/accessibility behavior | UNVERIFIED | T3 preview unavailable; no page rendered |
| Resolve most header link targets and verify actual route destinations | UNVERIFIED | Only Nuwe Jy path decoded; remaining saved targets unresolved |
| Independently verify current CI/review evidence for PR #91/#92 | UNVERIFIED | PR open/draft state checked; CI and exact-diff review not fetched |

- **Implementation Score:** N/A (no implementation performed).
- **Task Execution Score:** 7/10. Static data coverage is broad and the requested report is complete. The score is reduced by unavailable current render evidence, unresolved navigation links, and an accidental credential disclosure described in section 8.
- **Confidence:** MEDIUM. Database state, template conditions, PR states, and relationship markers were directly observed. Render selection, visitor behavior, and several navigation targets remain unknown.
- **Verification Status:** `PARTIALLY_VERIFIED` for persisted runtime configuration and Plane/GitHub state; frontend rendering and behavior are unverified.
- **Evidence:** LocalWP read-only SQL transaction (`mysqli` read-only transaction, targeted `SELECT`s, rollback); `git rev-parse HEAD`; T3 `preview_status`; Plane `retrieve_by_identifier`, state, and comment reads; GitHub GET metadata for PRs #91, #92, and #95; Bricks source reads at `admin.php:1848–1855` and `database.php:11–32`; source/runtime FluentSnippet comparison.
- **Known gaps / highest-risk assumption:** Stored Bricks type/condition data is assumed to match frontend selection until a current render proves it. The credential exposed in tool output needs owner containment.
- **Merge readiness:** `NOT_ASSESSED`.
- **Next action:** Rotate the exposed credential and review access records; when T3 preview is available, run the safe visual checks listed in section 11. Keep PR publication, runtime changes, and route changes outside this report's scope.

### Verified Plane references

- **VGN-55** (`1782fd6f-206f-432b-bf85-0ad97c0a43bb`), In Progress. Latest Phase 0 comment `30185303-4b3b-44d6-a5c1-36a12ddf77d3` records a partial audit and open draft PR #95.
- **VGN-52** (`16e45666-7878-4f65-9e03-4a3c8ec39514`), In Review. Draft homepage PR #92 remains open at head `548d5895e285317d7635f8a2bd1ccb4bf28c8109`.
- **VGN-51** (`f1c3da7b-15e8-49e1-a69f-e8ae1019f43d`), In Review. Draft Event Landing PR #91 remains open at head `4508a2d0691cfe525e5039b5d1511cc49c1c1a11`.
- **VGN-53** (`f380b9e6-2baa-4320-af95-7daf2bd1d6b8`), Backlog. Its product 1935 404 is a 17 November historical observation.
- **VGN-18** (`fac5419e-4347-418a-a1c2-15054dde2e91`), Blocked. Comment `ac9fc988-d58d-4c40-97a9-be117bfa8e6f` records a historical checkout render with no guest payment methods and no submitted order.
- **VGN-50** (`370ad89d-5912-4987-b91c-9f3d4c3c4d32`), Done. Comment `f97b9dfc-92ad-4640-88aa-046feeea7d88` is historical deployment evidence, not a current UI render.
- **VGN-5** (`951a2f73-5005-498d-bb97-50286dcbbf6d`), In Progress. Comment `d6b942d4-e0dd-4d9a-a73a-f57730a2ea87` defers the screen-reader test and does not attest launch accessibility.

No Plane update, runtime mutation, commit, publication, or PR action was performed.
