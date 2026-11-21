# LMS and course inventory, v0.1.0

**Task:** VGN-55 bounded research, Agent 08. Scope is Masteriyo courses, instructors, WooCommerce links, learner access and progress, certificates, dashboard surfaces, and migration evidence.

**Inspection date:** 2026-11-19.

**Source baseline:** `main` at `454346d9e6a2c36864a4bbd944e52596f16e09c1`, matching the parent baseline. The nested `voelgoed-member-perks` source repository is at `972a279be7a753594888e974132562e1cc099e24`.

**Runtime:** LocalWP `vgnova-local-10039`, `http://localhost:10039`, WordPress 7.1.5, PHP 8.5.12. WordPress root: `/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public/`.

## 1. Executive Summary

Masteriyo 3.5.1 is active. The runtime contains six published Courses and one trashed Course. Five published records are editorial Courses; the sixth is the existing synthetic QA Course 1889. Only Course 1889 has curriculum: two published Sections and three published Lessons. The five editorial Courses have no Masteriyo Sections or Lessons. No Masteriyo quiz or question posts were found.

Four editorial Courses have reciprocal Masteriyo and WooCommerce product links. Course 532, `Selfsorg vir Besige Ma’s`, has no linked WooCommerce product. The QA Course also has no product. The native Masteriyo WooCommerce add-on is active and configured to deactivate access after cancelled, failed, or refunded orders. No purchase, payment, order, or enrollment journey was run. A November 17 VGN-18 comment records that checkout rendered for an existing guest cart but showed no payment methods, and no order was submitted.

Aggregate learner state contains six active Masteriyo user-course rows across four Courses and four `started` progress rows. There are no completed progress rows, quiz attempts, or certificate records. The six active rows do not establish valid purchase provenance because their orders were not inspected. These counts disclose no learner identities or contact details.

ACF Course-to-Person fields use canonical `person` records and bidirectional Person fields. One Course additional-instructor edge resolves to published Course 1889. Person 1705's reverse field also contains Course ID 1896, which has no `wp_posts` row. The published Person Single template consumes that reverse field through a native Posts query. Its query should return Course 1889 and omit the missing ID 1896; the query-count condition remains satisfied by the valid result. This is an inference from saved Bricks configuration and current post rows, not a rendered-page test.

The installed Masteriyo LearnDash migrator at `addons/migration-tool/Migrators/LearnDashMigrator.php:20-45` lists users, Courses, enrollments, lesson progress, quiz attempts, orders, and assignments as migration steps, but the migration-tool add-on is inactive. No LearnDash plugin directory or `sfwd-*` records were found in the current site. The runtime also contains a LifterLMS migrator. Unregistered `course`, `lesson`, `section`, and `llms_form` storage exists; the `llms_form` key is consistent with LifterLMS naming, but its origin and migration status remain unknown. VGN-26 records an obligation to audit 15 source Courses plus lesson, quiz, learner access, and progress data. Current target storage and plugin adapters do not establish that migration. VGN-33 still governs any cutover.

| Readiness field | Assessment |
|---|---|
| `SOURCE_MERGED` | PARTIAL. Platform Core and ACF relationship definitions are in source. Masteriyo vendor code and the runtime course-product MU plugin are not source-controlled in this repository. |
| `RUNTIME_APPLIED` | YES for the active Masteriyo plugin, add-on settings, Course records, Bricks records, and current source/runtime copies checked below. |
| `RUNTIME_LOADED` | UNKNOWN for a public Course request. Plugin status and add-on options show active/configured state, but no public request was used to establish hook execution. |
| `BEHAVIOR_VERIFIED` | NO for public course access, purchase, enrollment, progress, completion, certificates, or dashboard rendering in this inspection. |
| `BUSINESS_APPROVED` | PARTIAL. Domain ownership and transitional URLs are documented. Course-to-product coverage, tier eligibility, old LMS provenance, and migration acceptance remain open. |
| `LAUNCH_READY` | NO. Editorial Courses lack curriculum, one Course has no product link, checkout lacks payment methods in the latest recorded VGN-18 check, and learner/migration journeys are unproven. |

## 2. Inspection Scope and Evidence

### Runtime and source inspected

- Read the complete `docs/evaluation/COORDINATION_BRIEF_v0.1.0.md` and `docs/evaluation/PARENT_BASELINE_v0.1.0.md`, plus repository and nested Member Perks instructions.
- Read reports [01 Runtime Foundation](01_RUNTIME_FOUNDATION_v0.1.0.md), [03 CPT and Taxonomy Inventory](03_CPT_TAXONOMY_v0.1.0.md), and [04 ACF Relationships](04_ACF_RELATIONSHIPS_v0.1.0.md). Report 01 records Masteriyo 3.5.1 as active and states that course/access behavior was not tested there; the current plugin version and configuration were rechecked. Report 03 records Masteriyo CPT counts and unregistered LMS-like storage. Report 04 records one valid forward Course-to-Person edge and an unmatched reverse Course reference. This report rechecked both against current rows and the Person template consumer.
- Read current Plane items VGN-18, VGN-45, VGN-26, and VGN-33 and relevant comments. No Plane changes were made. The references and recorded states appear in §11.
- Confirmed the LocalWP target, WordPress and PHP versions, and active plugin versions through read-only WP-CLI metadata. Masteriyo plugin file header is `learning-management-system/lms.php`; WooCommerce Integration add-on header is `addons/wc-integration/main.php`; Certificate Builder add-on header is `addons/certificate/main.php`. Plugin status and the active-add-on option establish active/configured state, not hook execution on a public Course request.
- Read Masteriyo options through `$wpdb` using PHP 8.5 WP-CLI with the explicit LocalWP socket and `--skip-plugins --skip-themes`. Expensive read batches were serialized with `flock -w 20 -x /tmp/vgnova-evaluation-read.lock`. The direct reads selected course, curriculum, mapping, user-course, progress, certificate, and aggregate metadata only. They did not select learner names, emails, order contents, or other private learner details.
- Read Bricks templates and page content using the configured read-only Bricks MCP abilities `novamira/bricks-list-templates` and `novamira/bricks-get-content`. No Bricks write ability was called.
- Read the runtime Masteriyo WooCommerce Integration, Certificate Builder, LearnDash migrator, the runtime `vg-masteriyo-course-products.php` MU plugin, and the source/runtime Platform Core and Member Perks files cited below.
- Compared the source-controlled Platform Core LearningService with its runtime copy by SHA-256. Compared the Member Perks CoursePerkService source and runtime copies. A successful hash match proves file parity only, not that a hook ran or a page behaved as intended.

### Limits and safety

This was a read-only inventory. No database writes, plugin activation, setting changes, migration, HTTP add-to-cart request, checkout submission, order, user, intent, enrollment, test transaction, clone, job, email, deployment, commit, or Plane update occurred. No behavioral test was run. The Bricks MCP tools declared the inspected abilities read-only, but WordPress-backed reads can have incidental bootstrap effects; this report does not claim otherwise. The LearnDash source site, its database, and backups were not available in this inspection. No database export was made.

Evidence labels used below mean `BOTH_CONFIRMED` for source and runtime evidence, `RUNTIME_CONFIRMED` for current persisted/runtime state, `SOURCE_CONFIRMED` for inspected source behavior, `HISTORICAL_ONLY` for prior comments, `INFERRED` for conclusions from saved state, `UNKNOWN` where no evidence establishes a fact, and `CONFLICT` where sources disagree.

## 3. Component Inventory

### Platform components

| Component | ID/Slug/Path | State | Owner | Purpose | Consumers | Evidence |
|---|---|---|---|---|---|---|
| Masteriyo LMS | `learning-management-system`, 3.5.1 active; 3.5.2 update offered | `RUNTIME_APPLIED=YES`; `SOURCE_MERGED=NO` in this repository; `BEHAVIOR_VERIFIED=NO` | Masteriyo | Courses, curriculum, enrollment, progress, quizzes, account pages | Course templates, Woo integration, My Voelgoed Learning card | `RUNTIME_CONFIRMED`; plugin file and version metadata |
| Masteriyo WooCommerce Integration | Add-on 2.2.0; `wc-integration=true` | Active and configured; no transaction behavior tested | Masteriyo; WooCommerce owns order/payment | Link Course products and translate order status to access | Course purchase button, Woo orders, user-course rows | `RUNTIME_CONFIRMED`, `SOURCE_CONFIRMED` |
| Masteriyo Certificate Builder | Add-on 2.3.7; `certificate=true` | Active; zero certificate posts; some Course-level settings are unknown | Masteriyo | Issue and download Course certificates | Masteriyo account page and Platform Core Learning card | `RUNTIME_CONFIRMED`, `SOURCE_CONFIRMED` |
| Masteriyo Migration Tool | `migration-tool=false` | Installed code; add-on inactive; no run evidence | Masteriyo | Import data from supported LMS providers | VGN-26 and VGN-33 migration planning | `RUNTIME_CONFIRMED`, `SOURCE_CONFIRMED` |
| WooCommerce | 11.3.0 active | Active; checkout journey not proven | WooCommerce | Products, cart, checkout, orders, payments | Masteriyo Woo integration and course products | `RUNTIME_CONFIRMED`; Plane checkout note is `HISTORICAL_ONLY` |
| WooCommerce Subscriptions | 9.4.1 active | Active; no Course subscription mappings found in this inventory | WooCommerce | Subscription billing lifecycle | Masteriyo recurring-product support is present in add-on source | `RUNTIME_CONFIRMED`; use for Courses is `UNKNOWN` |
| Course People ACF fields | `group_vg_course_people`, ID 1719; `site-code/acf-json/group_vg_course_people.json` | Source schema present and runtime group active | ACF owns fields; Course fields are authoritative | Lead instructor, additional instructors, experts | Course Single 1733; Person Related Content; Person Single 1734 | `BOTH_CONFIRMED` for schema and current group |
| Person reverse Course fields | `group_vg_person_related_content`, ID 1706; `person_courses_*` | Derived reverse fields active | ACF bidirectional storage; forward Course fields own the relationship | Find Courses associated with a Person | Person Single 1734 | `BOTH_CONFIRMED`; one stale stored reference is `RUNTIME_CONFIRMED` |
| Runtime course-product MU plugin | `wp-content/mu-plugins/vg-masteriyo-course-products.php` | `RUNTIME_APPLIED=YES`; no source counterpart found in `vgnova`; behavior not browser-tested | Runtime-only MU plugin; project owner not established by this inspection | Redirect linked product singles to Course and exclude linked products from catalog queries | Woo product single, archive, product queries and shortcodes | `RUNTIME_CONFIRMED`, `SOURCE_CONFIRMED`; owner `UNKNOWN` |
| Platform Core LearningService | `voelgoed-platform-core/includes/Services/LearningService.php` | Source present; runtime copy has matching SHA-256 `b7156f25406f15e8caf8a250961742a2f6514ce68762d609092b3d06d20f0338` | Voelgoed Platform Core | Build the current-user Learning dashboard card from Masteriyo APIs | My Voelgoed page 1160 and `{vg_dashboard_learning_summary}` | `BOTH_CONFIRMED` for file contents; behavior not tested |
| LearnDash legacy source | `sfwd-lms/sfwd_lms.php` expected by Masteriyo's `LearnDashMigrator` | Not present in the current plugin directory; no `sfwd-*` posts observed | Prior LMS owner unknown | Legacy Course and learner source for VGN-26/VGN-33 | Migration Tool, if source becomes available | Current-site absence is `RUNTIME_CONFIRMED`; source-system state is `UNKNOWN` |

### Complete current Masteriyo Course and curriculum inventory

All current `mto-course`, `mto-section`, and `mto-lesson` rows were inspected by status. Names below are stored public/editorial titles, not learner data.

| Type | ID | Title | Status | Structure or relation | Evidence |
|---|---:|---|---|---|---|
| Course | 532 | Selfsorg vir Besige Ma’s | Published | No Sections or Lessons; no Woo link; no course-visibility term | `RUNTIME_CONFIRMED` |
| Course | 534 | Geloof & Identiteit | Published | No Sections or Lessons; Woo product 1938; no course-visibility term | `RUNTIME_CONFIRMED` |
| Course | 536 | Finansiële Vryheid Basiese | Published | No Sections or Lessons; Woo product 1937; no course-visibility term | `RUNTIME_CONFIRMED` |
| Course | 538 | Gesonde Huwelik: Kommunikasie | Published | No Sections or Lessons; Woo product 1936; no course-visibility term | `RUNTIME_CONFIRMED` |
| Course | 540 | Mindfulness vir Vroue | Published | No Sections or Lessons; Woo product 1935; no course-visibility term | `RUNTIME_CONFIRMED` |
| Course | 1889 | [VG QA] Klim uit jou groef — Toetskursus | Published | Sections 1890 and 1893; no Woo link; `free` course-visibility term; existing synthetic QA fixture | `RUNTIME_CONFIRMED`; QA intent is documented in VGN-45 |
| Course | 438 | Untitled Course | Trash | No current linked product or curriculum observed | `RUNTIME_CONFIRMED` |
| Section | 1890 | Afdeling 1 — 'n Nuwe begin | Published | Parent Course 1889; order 0 | `RUNTIME_CONFIRMED` |
| Section | 1893 | Afdeling 2 — Hou aan beweeg | Published | Parent Course 1889; order 1 | `RUNTIME_CONFIRMED` |
| Lesson | 1891 | Neem die eerste tree | Published | Parent Section 1890; order 0 | `RUNTIME_CONFIRMED` |
| Lesson | 1892 | Kyk na nuwe moontlikhede | Published | Parent Section 1890; order 1 | `RUNTIME_CONFIRMED` |
| Lesson | 1894 | Jou eenvoudige aksieplan | Published | Parent Section 1893; order 0 | `RUNTIME_CONFIRMED` |
| Quiz/question | No rows | N/A | No Masteriyo quiz/question posts; quiz-attempt table has zero rows | No quiz lifecycle is available to test in the current data | `RUNTIME_CONFIRMED` |

The `course_visibility` taxonomy has one term, `free`. The published QA Course 1889 is assigned to it. The trashed Course 438 also has that term. The other five published Courses have no `course_visibility` term. An absent term does not establish whether a Course is intended to be free, paid, hidden, or private.

### Unregistered LMS-like legacy storage

These are current published `wp_posts` rows under keys that are not registered by the active site. IDs are listed for source identification only. No row content or learner data was read for this inventory.

| Stored key | Published rows | IDs | Current site status |
|---|---:|---|---|
| `course` | 1 | 393 | Unregistered; provider and consumer unknown |
| `lesson` | 13 | 397-399, 401-404, 406, 408-412 | Unregistered; provider and consumer unknown |
| `section` | 4 | 396, 400, 405, 407 | Unregistered; provider and consumer unknown |
| `llms_form` | 3 | 54-56 | Unregistered; key is consistent with LifterLMS naming, but provider and consumer are unconfirmed |

The current site has no `sfwd-*` post rows and no `sfwd-lms` plugin directory. This establishes only that the current target does not contain those observed records or plugin path. It does not show whether a separate LearnDash source site or backup exists.

### WooCommerce products and reciprocal links

| Masteriyo Course | Woo product | Woo product title | Product state/type | Reciprocal mapping |
|---|---:|---|---|---|
| 534, Geloof & Identiteit | 1938 | Geloof & Identiteit | Published, `mto_course` | `_wc_product_id` and `_masteriyo_course_id` agree |
| 536, Finansiële Vryheid Basiese | 1937 | Finansiële Vryheid Basiese | Published, `mto_course` | `_wc_product_id` and `_masteriyo_course_id` agree |
| 538, Gesonde Huwelik: Kommunikasie | 1936 | Gesonde Huwelik: Kommunikasie | Published, `mto_course` | `_wc_product_id` and `_masteriyo_course_id` agree |
| 540, Mindfulness vir Vroue | 1935 | Mindfulness vir Vroue | Published, `mto_course` | `_wc_product_id` and `_masteriyo_course_id` agree |

These are native Masteriyo/Woo links. VGN-18's older use of `linked_product_id` and its benefit/tier decision are not interchangeable with the native `_wc_product_id` / `_masteriyo_course_id` pair. The source defines the native link operation, but this inspection did not call it.

### Learner, progress, certificate and benefit aggregates

| Record family | Aggregate only | Evidence |
|---|---|---|
| Masteriyo user-course | 6 rows, all `active`: Course 534 has 1, Course 536 has 1, Course 538 has 2, Course 540 has 2 | `RUNTIME_CONFIRMED`; no learner identifiers disclosed |
| Course progress | 4 rows, all `started`: one each for Courses 534, 536, 538 and 540 | `RUNTIME_CONFIRMED` |
| Completed user-course/progress | 0 rows in the inspected status aggregates | `RUNTIME_CONFIRMED`; does not prove every separate activity store is empty |
| Quiz attempts | 0 rows | `RUNTIME_CONFIRMED` |
| Masteriyo certificate records | 0 `mto-certificate` posts | `RUNTIME_CONFIRMED` |
| Course certificate settings | Courses 534, 536, 538 and 540 have `_certificate_enabled=false`, `_certificate_id=0`; no Course-level certificate configuration was found for the other Courses | `RUNTIME_CONFIRMED` |
| Published `vg_perk` records targeting Courses | 15 published and 5 draft Perks overall; 10 published target relations cover Courses 532, 534, 536, 538 and 540, two per Course | `RUNTIME_CONFIRMED`; target mappings are not tier approval |

## 4. Current Architecture and Configuration

### Authority boundaries

- Masteriyo owns Course identity, curriculum, enrollment, learning progress, quiz state, and certificate features. WooCommerce owns product, order, payment, and subscription records. This matches the LMS boundary in the [VS1 Golden Journey product and technical specification](../superpowers/specs/2026-10-25-vs1-golden-journey-product-tech-spec_v0.2.0.md#lms-boundaries).
- ACF stores Course-to-Person relationships. `lead_instructor`, `additional_instructors`, and `experts` are Post Object fields targeting canonical `person`; each field names a corresponding Person reverse field as its bidirectional target. The source instructions say the forward Course field owns the relation. See [Course People](../../site-code/acf-json/group_vg_course_people.json#L6) and [Person Related Content](../../site-code/acf-json/group_vg_person_related_content.json#L122).
- Platform Core owns the My Voelgoed Learning adapter. The runtime copy of `LearningService.php` matches source by hash. It queries Masteriyo using the current user ID, validates each returned row against that ID, checks enrollment, reads progress, and only exposes a certificate action when completion reaches 100% (source lines 37-94, 100-155, 184-210, 352-432, 270-288, and 599-620).
- The active runtime MU plugin redirects linked Woo product singles to their Course and hides linked products from catalog query results. It is a runtime-only implementation in this repository. The native Masteriyo add-on remains the order-to-enrollment owner.
- Paid Memberships Pro, WooCommerce Subscriptions, and Member Perks each have separate roles. Current Course benefit configuration is not sufficient evidence of a tier rule or owner approval.

### Current settings and URLs

| Setting or page | Current value | Interpretation and limit |
|---|---|---|
| Masteriyo account page | ID 433, `Account`, `/account/` | Configured; page rendering and account access were not tested |
| Masteriyo Courses page | ID 57, `Course Catalog`, `/kursusse/` | Configured archive page; `/kursusse/` is transitional in the [Legacy Decommission Register](../architecture/LEGACY_DECOMMISSION_REGISTER.md#26-course-routes-leer-and-kursusse) |
| Masteriyo checkout page | ID 434, `Checkout`, slug `checkout-1` | Masteriyo page setting only; not proof of Woo checkout configuration |
| Masteriyo learn page | ID 435, `Learn`, `/learn/` | Configured page; no lesson access journey tested |
| Course content without enrollment | `general.course_access.enable_course_content_access_without_enrollment=true` | Masteriyo helper grants this bypass to admins and instructors, with instructor restriction governed by `restrict_instructors`; it does not create an anonymous learner bypass. Source: runtime `includes/Helper/course.php:907-930` |
| Guest checkout | `general.registration.enable_guest_checkout=true` | Configured; no guest order submitted |
| Masteriyo payments | `payments.enabled="yes"` | Masteriyo setting only; VGN-18 records that Woo checkout showed no available payment methods |
| Account certificate page | `accounts_page.display.enable_certificate_page=true` | UI option configured; no certificates exist and no page rendering was tested |
| Quiz access/grading | `quiz_access=default`, `quiz_grading=last-attempt` | Configured; current Courses have no Masteriyo quizzes or attempts |
| Woo add-on unenrollment statuses | `cancelled`, `failed`, `refunded` | Configured and also listed in add-on default settings |
| Woo add-to-cart option | `add_to_cart.enable=false` | Configured; Course Single 1733 still contains Masteriyo's native `single_course_button`; rendered button and purchase path are unknown |
| Woo post-order redirect | No stored redirect override; source default is `none` | Configured default; actual thank-you behavior was not exercised |
| Masteriyo route shape | Course singles `/leer/{slug}/`; archive `/kursusse/` | Matches the transitional architecture record. Final archive/hub and redirects remain a separate Learning/URL task |

### Bricks and My Voelgoed consumers

| Surface | Stored runtime configuration | Consumer behavior evidence |
|---|---|---|
| Course Archive | Bricks template 1838, published, type `masteriyo-course-archive` | Template exists; no archive request or output checked |
| Course Single | Bricks template 1733, published, type `masteriyo-single-course` | Contains native Masteriyo title, price, button, and curriculum elements; contains three Person query sections sourced from the matching ACF fields; no rendered behavior checked |
| Person Single | Bricks template 1734, published, type `content`, conditioned on `person` | Additional-instructor section queries `mto-course` by `{acf_person_courses_additional_instructor}` and has a query-result-count condition. Current valid Course 1889 should appear; missing Course 1896 should not. `INFERRED`, not browser-tested |
| My Voelgoed Learning page | WordPress page 1160, published, `/my-voelgoed/learning/` | Five Bricks elements, including `{vg_dashboard_learning_summary}` and a link to `/kursusse/` |
| My Voelgoed dashboard shell | Bricks Section Shell 1172; dashboard pages 1155-1161 | Stores tabs for Overview, Membership, Subscriptions, Orders, Tickets, Courses, and Profile; page conditions are configured. No authenticated page was opened |
| Masteriyo account page | Page 433, `/account/` | Coexists with the separate My Voelgoed area. Functional overlap and canonical learner entry point need product-owner review |

## 5. How It Works

### Course authoring and publication

WordPress stores Masteriyo Courses as `mto-course`, Sections as `mto-section`, and Lessons as `mto-lesson`. Current saved curriculum rows attach Sections 1890 and 1893 to Course 1889 and attach Lessons 1891, 1892, and 1894 to those Sections. The other five published Courses have no saved Section or Lesson rows. Masteriyo owns the lifecycle UI and persistence. The current snapshot establishes their statuses, not how an editor's draft-to-publish or trash-to-restore transition behaves. No course was edited, cloned, unpublished, or restored.

The `free` visibility term is assigned only to Course 1889 among published Courses. The remaining published Course visibility intent is unknown. The configured `enable_course_content_access_without_enrollment` setting is restricted by Masteriyo source to administrators and instructors, with a separate instructor-restriction setting. Ordinary learner access is expected to use an enrollment check, but no anonymous, instructor, enrolled, or unenrolled request was tested.

### WooCommerce purchase and Masteriyo enrollment

The native Woo integration is active. Its source registers order-create, order-update, and order-status hooks. It maps Woo statuses to Masteriyo order statuses. On a completed order, the add-on creates or updates a Masteriyo user-course row as active, writes order linkage, and sends the Masteriyo enrollment email if enabled. On configured cancelled, failed, or refunded states, it marks the course row inactive and clears dates. A guest order initially has no customer ID; source contains a separate `woocommerce_created_customer` callback that can enroll the account by email after Woo creates the customer. The source also keeps an active enrollment from being linked to a non-completed order that did not grant it. Relevant code is in runtime `addons/wc-integration/WcIntegrationAddon.php:106-172, 704-836, 1265-1417`.

The normal grant path therefore has these states and guards:

| State/transition | Guard | Side effect | Recovery or terminal status |
|---|---|---|---|
| Course and product linked | Authorized editor; both records exist; product is not linked to a different Course | Saves reciprocal `_wc_product_id` and `_masteriyo_course_id`, syncs Woo regular price into Masteriyo Course price, assigns Masteriyo product type | Existing Course/product data can be inspected and relinked through the supported UI/API. This inspection did not call the link operation. Source: `WcIntegrationAddon.php:2837-2910` |
| Order pending, processing, or on hold | Woo order has a customer ID and a linked product resolves to a Course | A new user-course row starts inactive. An existing row keeps its current status unless the order is completed or enters the configured unenrollment set. The order-item Course ID metadata may be saved. | Non-terminal until a later status transition. Current order data was not inspected |
| Order completed to active enrollment | Customer ID is available, linked Course resolves, and Woo status is completed | Saves active Masteriyo user-course, links order when allowed, sends configured enrollment email, may process thank-you redirect | A later configured cancellation/failure/refund can deactivate it. Repeated and competing-order behavior was not exercised |
| Guest checkout to account enrollment | Woo created a customer for the guest order | Callback locates the guest order by email and attempts enrollment | Depends on Woo account creation, order and email matching; no guest flow tested |
| Order cancelled, failed, or refunded to inactive enrollment | Woo status is in the configured unenrollment set | Sets Masteriyo user-course inactive and clears enrollment dates | A later completed order can reactivate through the status handler; this was not tested |

The available add-on source establishes a path, not a functioning purchase journey. Masteriyo reports payments enabled, but the latest relevant VGN-18 browser comment reported no Woo payment methods and submitted no order. No side effect described above was observed in this inspection.

### Member Perks course claim path

The Member Perks source has a guarded course-claim path. It rejects claims when the `course_claims` feature flag is off, the mode is not `claim_and_enrol`, the retention policy is not executable, the target or Masteriyo capability fails validation, the user already has access, or the claim validator denies eligibility. For eligible requests, it reserves a redemption, grants access through Masteriyo's user-course API, then completes the redemption. A grant failure cancels the reservation; a completion failure after a successful grant marks the claim failed and emits a failure action. Existing reserved, claimed, or completed redemptions are treated as an existing claim. Source: `voelgoed-member-perks/includes/Masteriyo/CoursePerkService.php:62-98,100-171,174-188`, `CourseAccessPolicy.php:12-55`, and `CourseEnrollmentRepository.php:18-111`.

The runtime `options_vgmp_enable_course_claims` value is `0`. Runtime records contain 10 published target relations across five Courses. Most targeted configurations are `all_members`; one Course 538 perk has `programme_rules` with no audience rules. Access modes include `claim_and_enrol`, `display_only`, and `manual_admin`; retention includes `keep_after_claim` and `while_membership_active`. The data does not define a verified tier benefit rule. Course claims are disabled, and no claim was attempted.

### My Voelgoed access, progress, completion, and certificates

The Platform Core LearningService returns a login prompt for an unauthenticated user, queries Masteriyo using the current user ID, rejects returned records owned by another user, then checks Masteriyo enrollment before adding a Course card. It requests progress from Masteriyo and supports certificate actions only after 100% completion. Provider errors lead to an unavailable card. The Learning page stores a dynamic tag for this service. This behavior is supported by source and configuration, not a current browser test.

The runtime certificate add-on requires a logged-in user, valid Course, signed/nonce download request, existing certificate, and completed Course. Its REST response only adds a certificate URL when the Course has certificates enabled and a certificate ID and the user has completed the Course. The certificate page is enabled globally, while four Woo-linked Courses explicitly have certificates disabled and ID zero. No certificate record exists. Course-level certificate configuration for the other Courses is unknown. Source: runtime `addons/certificate/CertificateAddon.php:881-921` and REST response code in that file around lines 1078-1094.

## 6. Source Versus Runtime

| Area | Source evidence | Runtime evidence | Assessment |
|---|---|---|---|
| Root source baseline | `454346d9e6a2c36864a4bbd944e52596f16e09c1` | Parent baseline reports the same main SHA | `BOTH_CONFIRMED` for inspected baseline; no source change was made |
| Masteriyo plugin and add-ons | Third-party plugin implementation is not in the `vgnova` source tree | Masteriyo 3.5.1 and add-on versions are installed and active/configured in LocalWP | `RUNTIME_CONFIRMED`; no repository vendor parity claim |
| Course People and Person reverse ACF schemas | JSON source exists under `site-code/acf-json/` | Runtime ACF groups 1719 and 1706 are active; fields and current values inspected | `BOTH_CONFIRMED` for schema; current stored relation has one stale reverse item |
| Platform Core LearningService | `voelgoed-platform-core/includes/Services/LearningService.php` | Runtime copy SHA-256 matches source: `b7156f25406f15e8caf8a250961742a2f6514ce68762d609092b3d06d20f0338` | `BOTH_CONFIRMED` for file bytes only. Runtime hook registration and UI behavior remain untested |
| Course-product MU plugin | No file found in the `vgnova` repository | Runtime MU file exists and contains product redirect and catalog-exclusion hooks | `RUNTIME_CONFIRMED`; behavior and source owner `UNKNOWN` |
| Member Perks CoursePerkService | Source SHA-256 `52e66aba915082934996ff4d08ad5c0ad4ea93a6f7077e0b7bfc4190e46ac562` | Runtime SHA-256 `b31bc7b964c16b5bf04555cb94168aeb71fb8b9b92376cee8628feedfac07a94` | `CONFLICT`. Source rejects `claim_scope=per_order`; runtime has a canonical-availability check before config validation and lacks that explicit per-order rejection. Feature flag is currently off, so this difference is gated from the inspected claim path |
| Bricks templates and pages | Template structures are stored in WordPress, not source files here | Read through Bricks MCP; Course Single, Archive, Person Single, and dashboard records are published | `RUNTIME_CONFIRMED`; public rendering not verified |
| Legacy LMS | No LearnDash source plugin or migration package found in current repository evidence | No `sfwd-lms` plugin directory or `sfwd-*` posts in current site; unregistered old post-type rows exist | Current-site state is `RUNTIME_CONFIRMED`; origin, prior source, retention, and migration status are `UNKNOWN` |

The Member Perks divergence is material before course claims are enabled. The observed `course_claims=0` setting is a configuration guard, not proof that all runtime and source behavior is aligned. No attempt was made to reconcile files or enable the feature.

## 7. Completeness and Functionality

| Domain item | Current coverage | Assessment |
|---|---|---|
| Published Courses | 6 total, including one explicitly marked synthetic QA Course | PARTIAL. Five editorial Course records are published, but have no Sections or Lessons |
| Curriculum | 2 Sections and 3 Lessons, all under Course 1889 | PARTIAL. One synthetic Course exercises the structure; editorial Courses have none |
| Course visibility | One `free` term assigned to published Course 1889 | PARTIAL. Visibility intent for five published Courses is not stored under this taxonomy |
| Quizzes | 0 Masteriyo quiz/question posts and 0 attempts | NOT PRESENT in current inspected Course structure |
| Woo product links | 4 of 5 editorial Courses linked; QA Course unlinked | PARTIAL. Course 532 has no native Woo mapping |
| Course-to-Person relationships | 0 lead instructors; 1 valid additional-instructor edge; 0 Course expert edges | PARTIAL. Only synthetic Course 1889 has an instructor relation |
| Reverse Course references | 1 reverse owner with 2 Course IDs, one current and one unmatched | PARTIAL. ID 1896 has no current post row; Person template query still has valid Course 1889 result |
| Learner enrollment rows | 6 active rows across 4 Courses | Persisted state confirmed. Purchase provenance and effective access not confirmed |
| Progress/completion | 4 started rows; 0 completed rows in inspected aggregates | Persisted state confirmed; no completion journey tested |
| Certificates | Add-on active; global page enabled; 0 certificate posts | No issued certificate evidence; four mapped Courses explicitly disable certificates |
| My Voelgoed Learning card | Page, dynamic tag, and source adapter exist | Configured and source/runtime file parity confirmed; authenticated privacy and rendered content not tested |
| LearnDash migration | Migrator source supports LearnDash data steps; add-on inactive; current site lacks LearnDash plugin and `sfwd-*` records | No migration evidence. Legacy records have unconfirmed origin and cannot substitute for source LMS inventory |

## 8. Problems, Risks and Contradictions

Severity records concrete impact to the reviewed Course domain. `UNKNOWN` is not treated as a defect.

| Severity | Finding | Evidence and impact | Evidence status |
|---|---|---|---|
| P2 | Five published editorial Courses have no curriculum | Current Masteriyo Section/Lesson aggregates attach all three Lessons to the synthetic Course 1889. Published Course records without curriculum cannot demonstrate a learner lesson journey. | `RUNTIME_CONFIRMED` |
| P2 | Course 532 has no native Woo product link | Four of five editorial Courses have reciprocal product links. Course 532 has none, so the catalog-to-purchase path is not configured through Masteriyo's native mapping for that Course. Whether it should be free or paid is `UNKNOWN`. | `RUNTIME_CONFIRMED`; business intent `UNKNOWN` |
| P2 | VGN-18's recorded Course mapping and benefit snapshot needs reconciliation | The earlier issue description says five Course `linked_product_id` values are zero and no Course benefit rules exist. Current runtime has four reciprocal native `_wc_product_id`/`_masteriyo_course_id` links and 10 published Perk target relations across five Courses. These are separate record models, so the difference is a stale/scope conflict, not proof that either model is approved or executable. | Issue description `HISTORICAL_ONLY`; runtime records `RUNTIME_CONFIRMED`; interpretation `CONFLICT` |
| P2 | Course 1896 remains in a derived Person reverse field but no current post exists | Person Single 1734 queries current `mto-course` records by the reverse field. Course 1889 remains a valid result, so the stored query should return that Course and skip 1896; the section's query-count guard should remain true. Stale source/delete history is unknown. | Row mismatch `RUNTIME_CONFIRMED`; consumer result `INFERRED`; see Report 04 §7 and `:714` |
| P2 | Course claims are disabled and benefit configuration does not establish tier eligibility | `options_vgmp_enable_course_claims=0`; 10 target rows exist, but no approved membership-tier rule is evidenced. Enabling claims without resolving eligibility and retention could expose unintended access. | Config `RUNTIME_CONFIRMED`; approval `UNKNOWN` |
| P2 | Member Perks CoursePerkService differs between source and runtime | Source rejects per-order claim scope; runtime contains an additional canonical-availability guard and no matching explicit scope rejection. The current feature flag is off. This is a deployment/source-governance risk before claims are enabled, not proof of an active learner error. | `CONFLICT` |
| P2 | Purchase and learner access lifecycle remain behavior-unverified | Source hooks define order-status enrollment, guest account handling, and email effects. No order or enrollment journey was executed. VGN-18's latest recorded checkout had no available payment methods and submitted no order. | Source `SOURCE_CONFIRMED`; comment `HISTORICAL_ONLY` |
| P2 | Current active enrollment rows lack verified order provenance | Six active rows exist. Orders and their statuses were intentionally not queried, so the relationship between purchase and current access is unknown. | Enrollment rows `RUNTIME_CONFIRMED`; provenance `UNKNOWN` |
| P2 | LearnDash migration obligations are not discharged by current evidence | Masteriyo's installed migrator supports user, Course, enrollment, progress, quiz attempt, order, and assignment steps. Its tool is inactive, and no source LearnDash installation, source learner aggregate, mapping, rehearsal, rollback, or execution record was inspected. | Migrator `SOURCE_CONFIRMED`; migration completion `UNKNOWN` |
| P3 | `/kursusse/` remains a transitional archive route | Current setting uses `/kursusse/`; the architecture register names `/leer/` as the canonical Learning area and assigns the final hub/archive and redirects to a separate URL task. No route change is needed for this research report. | `BOTH_CONFIRMED` with register; behavior not tested |
| P3 | Masteriyo Account and My Voelgoed Courses pages coexist | Masteriyo account page 433 and My Voelgoed Learning page 1160 both provide learner entry surfaces. Whether both should remain public-facing is a product/navigation decision; no duplicate behavior is proven. | Runtime records `RUNTIME_CONFIRMED`; product decision `UNKNOWN` |

## 9. Redundancy Candidates

No deletion or cleanup is recommended. Each candidate needs consumer and retention evidence first.

| Candidate | Consumer evidence | Possible replacement or overlap | Retention and prerequisites | Rollback and owner gate |
|---|---|---|---|---|
| Runtime MU course-product plugin and native Masteriyo Woo integration | MU plugin redirects product singles and excludes linked products from Woo catalog queries. Native add-on owns reciprocal mapping, order hooks, enrollment, price sync, and product type. | The components serve adjacent functions. Native mapping and checkout do not obviously replace the MU redirect/catalog behavior. | Retain both while routes and catalog behavior are part of the current site. Confirm ownership and perform a rendered route/catalog comparison before proposing code consolidation. | Re-enable the prior MU file from a reviewed backup if a tested replacement regresses product visibility or route behavior. Runtime file change requires the relevant project authorization and owner gate. |
| Masteriyo Account page and My Voelgoed Learning page | Account page 433 is configured in Masteriyo. Page 1160 uses the Platform Core user-scoped Learning card and links to `/kursusse/`. | Both are learner-facing entry points, but one is Masteriyo-owned and one is the project dashboard. Their responsibilities may be complementary. | Retain until the canonical learner journey and dashboard ownership are approved and authenticated route behavior is verified. | Restore the previous page/menu/template configuration if an approved consolidation hides needed learner functions. Product owner approval required. |
| Person reverse Course field and forward Course field | ACF source declares the Course fields authoritative and reverse `person_courses_*` fields derived. Person Single consumes the reverse field. | Reverse fields are a query aid, not a second domain relationship. | Retain bidirectionality. Resolve Course 1896 through the approved ACF/editor workflow only after source history and the current Course relationship are checked. | Restore the reviewed reverse relation from the forward Course field using the approved native ACF workflow if a correction changes a valid Person course listing. Owner review required. |
| Unregistered `course`, `lesson`, `section`, and `llms_form` rows beside Masteriyo `mto-*` rows | Current registry/report 03 identifies 1, 13, 4, and 3 published rows respectively. Current Masteriyo has its own `mto-course`, `mto-section`, and `mto-lesson` rows. | Possible previous LMS residue; `llms_form` suggests a LifterLMS lineage but does not prove it. No consumer or origin is established. | Preserve rows. Identify their plugin/source, meta and media dependencies, learners, and consumers before any export, migration, retirement, or deletion. VGN-26 and VGN-33 are the current migration governance references. | Use an approved, reversible migration or restoration plan after mapping and owner approval. No deletion is authorized by this report. |
| Masteriyo Woo Course mapping and Member Perks targets | Four Courses have native Woo product IDs. Ten Perk target rows independently target five Courses. | These mappings have separate meanings: paid product/order versus member benefit/claim. They are not duplicate records. | Keep separate, and document how paid Courses, member entitlements, and free Courses relate before enabling benefit rules. | Preserve both sets of records. Any change requires business owner approval and a reviewed access rollback plan. |

## 10. Recommendations

1. Keep the native WordPress, ACF, Bricks, Masteriyo, and WooCommerce ownership boundaries. Do not create a custom Course repository or enrollment engine based on the current evidence.
2. Complete editorial Course curriculum and decide the stored visibility intent for each published Course. Keep the existing QA Course identified as a synthetic fixture; do not treat it as a real content or launch gate.
3. Decide whether Course 532 is free, paid, or excluded from the catalog. If paid, approve and use the Masteriyo native Woo product link. Do not assume VGN-18's `linked_product_id` means the native mapping is present.
4. Keep Member Perks course claims disabled until tier eligibility, claim retention, source/runtime parity, and owner approval are resolved. Reconcile the Member Perks CoursePerkService drift before enabling the feature.
5. Confirm that published Course 1889 points to Person 1705 and investigate missing Course ID 1896 through source history or an editor record. Do not delete the reverse reference solely from this report. The current Person template query has a valid Course 1889 result.
6. Use VGN-45 to define the safe isolation and proof gate for enrollment, progress, completion, and access. The next behavior check needs to cover the native mapped-product path, Woo status transitions, guest account handling, current-user scope in My Voelgoed, and failure/recovery. This report did not run those checks.
7. Keep VGN-26 and VGN-33 open for source LMS inventory and approved migration planning. Inventory the actual old provider, course/quiz/lesson structure, learner entitlements and progress, orders, identities, media, idempotency, concurrency, rollback, and cutover owner before executing any migration.
8. Keep `/kursusse/` unchanged until the separate Learning/URL decision approves the `/leer/` archive or hub and any redirects.
9. Review whether Masteriyo Account and My Voelgoed Learning are both required entry points after authenticated route and accessibility checks. Do not remove either page based on stored configuration alone.

## 11. Outstanding Verification

| Item | Current evidence | Required follow-up and owner gate |
|---|---|---|
| Current checkout/payment methods | VGN-18 comment `ac9fc988-d58d-4c40-97a9-be117bfa8e6f` records checkout form and one item at R149, no payment methods, no order. A separate comment records an owner-executed SSL option change, not an order. Both are historical claims for this inspection. | VGN-18 owner should refresh the current checkout UI without submitting an order. Any order test needs separate authorization and approved payment/notification isolation. |
| Product-to-enrollment lifecycle | Native source hooks inspected; four reciprocal mappings found; no transaction behavior observed | VGN-45 owner gate. Verify the supported status transitions, guest enrollment, repeat orders, deactivation/reactivation, order linkage, and email behavior only under the accepted isolation/authorization procedure. |
| Current active enrollment provenance | Six active user-course records; no order details queried | VGN-45 or responsible owner should verify aggregate order linkage and effective access without exposing learner data. |
| Progress, lesson completion, and quizzes | Four `started` progress rows, no completed rows or attempts, no current quizzes | VGN-45 should define behavior evidence after the Course curriculum is complete. Do not infer completion or quiz support from installed code. |
| My Voelgoed Learning access and privacy | Source is current-user scoped and runtime file matches; page structure exists | Verify authenticated and unauthenticated route behavior, cross-user isolation, empty and error states, progress, completion, and certificate links in the approved browser/runtime workflow. |
| Certificate readiness | Add-on active and account page enabled; no certificate posts; mapped Courses explicitly disabled | Confirm Course-level certificate template, completion gate, and account download behavior only after a real approved Course completion test exists. |
| Instructor and stale reverse reference | Current forward relationship is Course 1889 to Person 1705; reverse values include 1889 and missing 1896; Person template query configuration read | Confirm authoring history for 1896 and rendered Person output. Do not alter fields without owner approval. |
| Old LMS origin and migration completeness | No LearnDash plugin directory or `sfwd-*` rows in this current site; unregistered LMS-like storage remains | VGN-26 owner must identify the actual source site/database or approved backup, inventory learner/course data, and choose a provider-supported mapping. VGN-33 governs execution/cutover. |
| Member Perks runtime/source parity | CoursePerkService hashes differ; course claims flag is off | Plugin owner should reconcile and review source/runtime files before any course claim activation. |
| Final Learning URLs | `/kursusse/` is the configured archive and transitional route; `/leer/` is canonical Learning area | Complete the separately assigned URL decision and redirect requirements before changing routes. |

Plane context read without updates:

- [VGN-18](https://app.plane.so/symphony-development/browse/VGN-18), UUID `fac5419e-4347-418a-a1c2-15054dde2e91`, currently unstarted. Comment `ac9fc988-d58d-4c40-97a9-be117bfa8e6f` records the no-payment-methods checkout observation. Comment `621d6607-6776-4a80-9e4e-c71f3dd46df4` records the SSL option operation and pending browser acceptance. Neither proves a completed purchase.
- [VGN-45](https://app.plane.so/symphony-development/browse/VGN-45), UUID `af34641d-cf39-4d02-8d53-2beee7295ba4`, currently unstarted. Its November 6 comment describes a disposable network probe and `.invalid` email sink. That proves only the probe container and sink, not WordPress/FPM, browser, worker, or full runtime isolation.
- [VGN-26](https://app.plane.so/symphony-development/browse/VGN-26), UUID `c2e62f82-806b-4fe1-89a0-90de754da48e`, currently backlog. Its migration planning obligation remains open.
- [VGN-33](https://app.plane.so/symphony-development/browse/VGN-33), UUID `d1c99a5d-c3cc-4e0f-a359-d52cc66739e6`, currently unstarted. Its governed cutover and rollback obligations remain open.

## 12. Final Domain Assessment

**Coverage:** PARTIAL. Runtime Course structure, saved settings, native Woo mappings, aggregate learner state, ACF instructor relationships, Bricks template configuration, current migration-tool availability, and legacy storage counts were inspected. Rendered access, commerce, learner lifecycle, certificate behavior, and migration lineage were not verified.

**Confidence:** MEDIUM. Course, curriculum, mapping, settings, and aggregate row counts came from current runtime reads. Relevant source and template consumers were inspected. Confidence is limited by the absence of browser/transaction behavior, lack of source LearnDash access, unknown origin for legacy rows, and Member Perks source/runtime drift.

**Runtime verification:** PARTIAL. Persisted state and selected source/runtime file parity are verified for this snapshot. No learner or commerce behavior was tested. This is not an implementation, launch, migration, or merge approval.

**Acceptance criteria:** 8/8 requested inspection and reporting items addressed. The criteria breakdown is: (1) read the coordination brief and parent baseline, complete; (2) inventory every current Course, Section, Lesson, and quiz record, complete for the current runtime; (3) inspect settings, publish/visibility state, instructors, Woo links, access/progress/completion/certificate configuration, and templates/dashboard, complete as stored/runtime evidence; (4) cross-check reports 01/03/04, complete; (5) independently establish the status and Person Single consumer of the stale reverse Course ID, complete as persisted/configuration evidence; (6) inspect native Woo add-on and runtime MU plugin, complete by source; (7) read current VGN-18/45/26/33 and relevant comments, complete; (8) respect the read-only boundary and write only this report, complete. Behavior checks that require requests, transactions, learners, or runtime writes remain explicitly unverified under the task restrictions.

**Assessment role:** Research/documentation author self-assessment. **Task type:** RESEARCH and DOCUMENTATION. **Implementation Score:** N/A. **Task Execution Score:** 8/10. Two points are withheld because the old LMS source and migration state could not be established from the current runtime, and behavior evidence was outside the permitted read-only scope. **Verification Status:** `PARTIALLY_VERIFIED` for the persisted runtime inventory and selected source parity; behavior and launch readiness remain unverified.

**Evidence:** This report is the sole requested output, created under `docs/evaluation/08_LMS_COURSES_v0.1.0.md`. Read-only `$wpdb` batches were serialized with `flock -w 20 -x /tmp/vgnova-evaluation-read.lock`; LocalWP reads used PHP 8.5, the explicit registered socket, and `--skip-plugins --skip-themes`. Bricks data came from the named read-only MCP abilities. No tests, HTTP course/access calls, orders, users, enrollments, jobs, migrations, or Plane mutations were run.

**Known gaps, highest-risk assumption, next action:** The highest-risk assumption would be treating current active user-course rows or the unregistered legacy post types as proof of valid source-order provenance or completed LearnDash migration. Neither is established. VGN-18 should refresh checkout availability; VGN-45 should own the safe learner lifecycle proof; VGN-26 should identify and map the source LMS; VGN-33 should govern any later cutover. Business owners must decide Course 532's purchase/visibility intent, membership tier eligibility, and the final learner entry route before launch.
