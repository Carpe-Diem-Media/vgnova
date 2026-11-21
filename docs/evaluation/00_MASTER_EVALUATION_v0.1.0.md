# VG Nova master evaluation v0.1.0

Inspection window: 2026-11-19, approximately 18:15–21:14 UTC. Africa/Johannesburg is UTC+02:00.

Task: VGN-55, comprehensive foundation evaluation and documentation. Assessment role: independent architecture reviewer of thirteen delegated research reports. No implementation, cleanup, deployment, publication, commit, push or merge is authorised by this document.

Source candidate: main at 454346d9e6a2c36864a4bbd944e52596f16e09c1. Local HEAD and remote main matched at preflight and final reconciliation. Runtime: vgnova-local-10039, http://localhost:10039, LocalWP site nAZNpFs99, root /home/jcschoeman96/Local Sites/voelgoed-novamira/app/public.

**Deliverable state:** thirteen domain reports submitted, reviewed and reconciled; master documentation delivered. **Platform verification:** PARTIALLY_VERIFIED, with every domain retaining partial coverage. **Audit safety gate:** BLOCKED by two reported tool-output incidents. No production, merge or full Golden Journey acceptance is asserted.

Read this document together with [parent review notes](PARENT_REVIEW_NOTES_v0.1.0.md). Those notes explicitly correct claims in the preserved original domain snapshots. A child report's score is not evidence of platform readiness.

## A. Executive Platform Assessment

VG Nova is a small local staging implementation of a much larger consolidation programme. It has WordPress, Bricks, ACF Pro, Automatic.css, Frames, WooCommerce, Subscriptions, PMPro, Member Perks, Masteriyo and Tickera, plus custom integration code. The provider split is coherent. The available evidence supports completing the existing native workflows and small adapters before considering replacement systems.

The installed WordPress version is 7.1.5, web PHP-FPM is 8.5.12, database engine is MySQL Community 8.0.35, and Nginx is 1.26.1. Bricks 2.4.5 and Bricks Child Theme 1.1 are active. There are 31 active regular plugins, no inactive recognised plugin entries, one MU plugin, 63 registered post types, 55 registered taxonomies, 28 ACF field groups and 247 inventoried field definitions. These totals include vendor/internal records and do not represent 63 distinct business domains. [Runtime](01_RUNTIME_FOUNDATION_v0.1.0.md), [plugins](02_PLUGIN_INVENTORY_v0.1.0.md), [entities](03_CPT_TAXONOMY_v0.1.0.md), [ACF](04_ACF_RELATIONSHIPS_v0.1.0.md).

Platform Core has strong deployment traceability. Parent independently compared all 63 installed regular files and modes with finalized journal 2de8147a38934ad69d0808867dc38e6a. No files were missing, extra or mismatched. The computed tree hash and journal hash both equal 91775a8c85f3a756b58f2a650db2ad010eb605577512d8d218731a1ae0890276. The journal identifies current main as its source. PE-007 status showed the host and both database locks free; PE-008 showed no in-progress deployment or recovery options. This proves file-to-journal parity, not successful application execution or a newly downloaded artifact verification. [Parent verification](PARENT_REVIEW_NOTES_v0.1.0.md).

Member Perks does not have equivalent source alignment. Its installed production code has 49 changed shared PHP/JS/CSS files and 41 production files absent from the pinned source checkout. The all-file comparison has 59 changed shared paths and 43 runtime-only paths. Parent reproduced both populations. They are compatible totals, not contradictory measurements. Runtime additions affect mapping identity, claims, caches and health. Their accepted release provenance remains OPEN. [Custom code](05_CUSTOM_CODE_v0.1.0.md), [membership](07_MEMBERSHIPS_v0.1.0.md).

The local content is not a reconciled migration of 500+ Articles, 15 Courses and 50+ Products. It has two published Articles, six Courses including one synthetic Course, eight published Products, one canonical Person and five transitional profiles. Five editorial Course records have no curriculum; all current Sections and Lessons belong to synthetic Course 1889. The only canonical Topic assignment is on synthetic Article 1887. File existence for all 92 attachments does not establish rights or editorial approval. [LMS](08_LMS_COURSES_v0.1.0.md), [editorial](10_CONTENT_EDITORIAL_v0.1.0.md).

No current customer journey was behaviour-tested in this evaluation. Saved templates, forms, mappings, prices and existing enrolment rows are useful evidence, but do not prove payment, access, email, scanning, responsive rendering or production readiness.

Two agents reported tool-output disclosure incidents. These are audit failures, not evidence of a compromised site or an invitation to investigate unrelated systems. Details and limits appear in section G. The reports omit the values.

## B. Complete Domain Status Matrix

Evidence labels retain the requested meanings: BOTH_CONFIRMED, RUNTIME_CONFIRMED, SOURCE_CONFIRMED, HISTORICAL_ONLY, INFERRED, UNKNOWN and CONFLICT. Direct delegated runtime reads are distinguished from parent repetitions in the review notes. No source-only conclusion is promoted to current behaviour proof.

| Domain | Coverage | Implemented or configured | Current runtime evidence | Main concern and readiness |
|---|---|---|---|---|
| 01 Runtime | PARTIAL | LocalWP services, PE-007/008 and Core deployment | Identity, versions, selected settings, locks and 63-file journal parity | Routes, host cron and ingress unverified. No launch conclusion |
| 02 Plugins | PARTIAL | 31 active regular plugins, one MU plugin, Bricks theme | Headers, activation, selected dependencies/settings | Perks drift; eight snippets and MU/theme provenance incomplete |
| 03 CPTs/taxonomies | PARTIAL | 63 types, 55 taxonomies; canonical and legacy models coexist | Effective registry, terms, capabilities and assignments | Consumer, permission and route behaviour incomplete |
| 04 ACF | PARTIAL | 28 groups, 247 fields; native bidirectional Article/Course relationships | Schema and aggregate populations; JSON parity | Source-label semantics and stale reverse Course reference |
| 05 Custom code | PARTIAL | Core domains, nine snippets, MU adapter and theme hooks | Static installed files and loader configuration | Perks divergence, runtime-only writers and incomplete consumers |
| 06 Commerce | PARTIAL | 24 Products across statuses, 14 variations, four native Course mappings | Catalogue and selected gateway/tax/shipping/order settings | No demonstrated guest payment or fulfilment; Product route unresolved |
| 07 Membership | PARTIAL | Seven PMPro tiers mapped to seven draft subscription parents | Prices, mappings, flags and provider code | No paid activation/renewal/cancellation proof; conditional cancel authority risk |
| 08 LMS | PARTIAL | Six published Courses; two Sections and three Lessons | Curriculum, mappings, learner aggregates and selected source parity | Editorial curriculum absent; access/order provenance unverified |
| 09 Events | PARTIAL | 17 Events, three native ticket types, four Woo ticket Products | 17 published attendee instances; status/parent aggregates | Count/admission semantics, issuance, emails and scans untested |
| 10 Editorial/media/SEO | PARTIAL | Small staging corpus; 92 attachment files present | Statuses, fixtures, assignment, metadata and file existence | Source migration, rights, rendered SEO and destinations incomplete |
| 11 Frontend | PARTIAL | 32 nontrashed templates, two persisted intent forms, shared resources | Conditions, loops, draft state, 1,176 classes, 208 variables, 61 components | No current render/accessibility pass; consumer map incomplete; disclosure incident |
| 12 Operations | PARTIAL | Native schedulers and provider integrations | Selected queue/mail/webhook/CRM aggregates and source paths | Mail, analytics, backups, ingress and full restore unknown; read-scope incident |
| 13 Redundancy | PARTIAL | Cross-domain candidate and register-amendment review | Document/source synthesis; no fresh behaviour tests | No live removal gate satisfied; canonical register unchanged |

The reconciled assessment distinguishes source merged, runtime applied, behaviour verified, business approved and launch ready. None establishes launch readiness. “Partial” is an evidence limit, not a finding that every component is broken.

## C. System Architecture

Solid arrows below show recorded configuration or inspected code relationships. They do not certify that a transaction completed. Dotted arrows mark unresolved delivery or external boundaries.

~~~mermaid
flowchart TD
    Source["vgnova main: assembly base + approved overlays"] --> Deployment["PE-007 parent / PE-008 deployment"]
    Deployment --> Core["Platform Core installed: 63 files"]
    WP["WordPress posts, users, options, taxonomies"] --> Bricks["Bricks + child theme"]
    ACF["ACF definitions, options, relationships"] --> Bricks
    ACSS["Automatic.css + Frames"] --> Bricks
    Core --> Topic["content_topic + Topic roles"]
    Core --> Relations["Person / Event Participation / Product Contribution adapters"]
    Core --> Intent["Journey Intent: signed guest cookie or user meta"]
    Core --> Dashboard["My Voelgoed provider projection"]
    Woo["WooCommerce products, cart, orders"] --> WCS["Subscriptions: billing"]
    WCS --> Bridge["PMPro Woo add-on"]
    Woo --> Bridge
    Bridge --> PMPro["PMPro: access state"]
    PMPro --> Perks["Member Perks: benefits, eligibility, claims"]
    Perks -. gated benefit execution .-> Woo
    Woo --> LMSBridge["Masteriyo native Woo integration"]
    LMSBridge --> LMS["Masteriyo: enrolment, curriculum, progress"]
    Woo --> TicketBridge["Tickera Woo Bridge"]
    TicketBridge --> Tickera["Tickera: Events, tickets, admission"]
    Dashboard --> PMPro
    Dashboard --> WCS
    Dashboard --> LMS
    Dashboard --> Tickera
    Snippets["Fluent Snippets: 9 published bodies"] --> Bricks
    Snippets --> ACF
    MU["MU Course-product adapter"] --> Woo
    MU --> LMS
    LMSBridge -. configured email .-> Mail["wp_mail / effective transport UNKNOWN"]
    Mail -. unverified .-> SMTP["FluentSMTP / LocalWP Mailpit"]
    CRM["FluentCRM + Fluent Forms"] -. unverified actions .-> Mail
    Jobs["WP-Cron + Action Scheduler"] --> WP
    Analytics["GTM / GA4 / Meta / consent UNKNOWN"] -. browser pipeline unverified .-> Bricks
~~~

The native integration boundaries are supported by installed source and selected saved state. Member Perks source/runtime divergence prevents assuming that its pinned repository describes every installed guard. The MU plugin and child-theme integrations have no identified source counterpart in current main. The draft homepage/Event work does have versioned source in open PRs #92/#91, which is a different provenance condition. [Reports 05](05_CUSTOM_CODE_v0.1.0.md), [06](06_WOOCOMMERCE_v0.1.0.md), [09](09_EVENTS_TICKETING_v0.1.0.md), [12](12_INTEGRATIONS_OPERATIONS_v0.1.0.md).

## D. Source of Truth Matrix

| Entity or operation | Authoritative owner | Stored or derived representation | Boundary that must hold |
|---|---|---|---|
| Architecture and Core source | Approved contracts and vgnova Git | Root assembly base plus site-code overlays and integration.patch | Installed plugin is a derived deployment copy |
| Actual local configuration/content | Registered LocalWP runtime | Database, installed files and LocalWP service registry | Historical documents cannot establish current state |
| Editorial posts, pages, media | WordPress; editorial/rights owner approves content | Native records and attachments | Publish status and file presence are not rights approval |
| Canonical semantic Topic | Platform Core content_topic | Native taxonomy and explicit seeding | Leefstyl commercial meanings remain separate |
| Simple Article/Course-to-Person relation | Forward ACF fields | Derived bidirectional Person reverse fields | Do not introduce a second editable relationship authority |
| Person identity | Canonical person; approved owner identity decisions | Person 1705 for legacy 753; medewerker remains transitional | Do not migrate excluded placeholder identities or edges |
| Event participation | ACF relationship CPT plus Core domain rules | Event/Person, role, order and publication | Tickera remains Event endpoint owner |
| Product contribution | ACF relationship CPT plus Core domain rules | Product/Person, roles, order and People mode | Woo Product remains endpoint; source Event is a separate relationship |
| Featured content | ACF FCM options plus existing selector | Legacy category-to-feature mapping | Optional homepage destination is independent of FCM |
| Product/base or sale price/cart/order | WooCommerce | Products, variations, HPOS orders | Bricks and intent do not decide price or payment |
| Recurring billing | WooCommerce Subscriptions | Subscription lifecycle and native actions | PMPro access cancellation alone must not be assumed to stop billing |
| Membership/access | PMPro | Configured levels and membership state | Woo add-on projects commerce state into access |
| Benefits/claims | Member Perks | Config, eligibility, targets, redemption state | Disabled flags and staged records are not approved offers |
| Course fulfilment/progress | Masteriyo | UserCourse, Sections, Lessons, progress and certificates | Woo completion is a trigger; saved mappings are not fulfilment proof |
| Tickets/admission | Tickera; Woo owns sales orders | Ticket instances, event scope and attendance | Counted placeholders are not necessarily paid/admissible tickets |
| My Voelgoed | Platform Core projection of providers | Dashboard cards and links | Must not become a billing or entitlement authority |
| Journey Intent | Core lifecycle adapter | Minimum validated Course target/state | Never price, membership or access authority |
| Presentation | Bricks, ACSS, Frames | Templates, native queries, forms, tokens/components | Existing design must be preserved during focused fixes |
| Transactional communication | Owning provider plus agreed email policy | Woo/Masteriyo/CRM messages through effective transport | Delivery and one-use marker semantics remain to be proved |
| Decommission decisions | Existing Legacy Decommission Register and owners | Consumer/replacement/rollback gates | This evaluation proposes amendments only |

Dashboard source is tracked under the root voelgoed-platform-core/includes tree. Parent verified five dashboard/service files are tracked and byte-identical to runtime. Report 07's missing-source claim was incorrect because it searched the overlay as if it were the entire assembly. [Assembly contract](../architecture/PLATFORM_CORE_ASSEMBLY_CONTRACT_v1.5.0.md), [parent correction](PARENT_REVIEW_NOTES_v0.1.0.md).

## E. Current Functionality

### Directly verified stored or installed state

- Current Core deployment bytes match its finalized journal. Active ACF source resolves to generation c450475971de722378a1658157a3a24838e467f7. Parent compared all 15 JSON files with current main and found no differences.
- All 63 registered post types and 55 taxonomies were inventoried by the domain agent. The 402 stored term rows divide into 394 terms under registered taxonomies and 8 under three unregistered keys.
- Four published Course/Product pairs are reciprocal:534/1938,536/1937,538/1936,540/1935. Courses 532 and 1889 have no native Woo product link.
- Seven draft subscription parents map to PMPro levels 15–21 and contain 14 published priced variations. PMPro direct signup is disabled for all seven access levels. PMPro zero billing fields do not erase Woo prices.
- Current selected Member Perks settings have hub display and runtime-status scheduling enabled; discounts, initial subscription discounts, Course claims, ticket claims, notifications and Woo Blocks compatibility are disabled.
- Saved Article/Course/Person queries and native bidirectional fields exist. The valid fixture edges connect Article 1887 and Course 1889 to Person 1705. A reverse reference to missing Course 1896 remains.
- Thirty-two nontrashed Bricks templates exist. Page 1926 is draft and not the front page; its optional featured destination is unset. Template 1919 is draft with no placement conditions. PRs #91/#92 remain open drafts.
- All 92 attachment paths checked by the editorial agent resolve to files. No image-content, license or rights conclusion follows.

### Historical behaviour that remains useful

VGN-5's November 17 comments record bounded Journey Intent start, dismissal, stale-ID error, keyboard/focus and authenticated-continuity work. The owner accepted that functional slice for development handoff and deferred screen-reader announcement verification. This is a sequence decision, not a WCAG pass or launch waiver. Two-authenticated-user isolation remains unverified if required by the acceptance matrix.

VGN-18's November 17 comment records a checkout form rendered at HTTP with Product 1935 priced R149, without an SSL error, but with no guest payment methods. No order was submitted. VGN-53's Product 1935 404 is also a dated observation. Neither was repeated here. [Parent baseline with exact comments](PARENT_BASELINE_v0.1.0.md).

### Incomplete, untested and conditional defects

No current payment, subscription activation/renewal/cancellation, PMPro grant, Masteriyo access/completion, ticket issue/scan, mail delivery or authenticated dashboard journey was exercised. Existing learner/ticket rows show saved state, not accepted transaction history.

Five editorial Courses have no curriculum. The synthetic Course has two Sections/three Lessons and is not an approved production Course. Certificates/quizzes have no demonstrated completion journey.

Parent ran one standalone PHP harness, with no WordPress bootstrap/database/HTTP, against the unchanged MU adapter. A main single-product query for a linked Course product gained its own ID in post__not_in. This proves a callback transformation for controlled inputs and supports VGN-53's root-cause hypothesis. It does not establish the current route parse or response. The intended later product-to-Course redirect and the earlier query exclusion need reconciliation.

The installed admin test gateway's thank-you callback can mark an order completed when an administrator reaches it, without checking the order's gateway. Parent verified that code statically. Therefore opening an admin thank-you URL is not a safe read-only inspection. No such URL or order was accessed.

Masteriyo marks Course products virtual/downloadable and hides Woo downloadable-file controls. Empty file lists on Products 1935–1938 are normal native configuration, not a proved delivery defect. Course enrolment/access is the relevant acceptance path.

## F. Golden Journey Evaluation

| Transition | Current evidence | What remains before acceptance | Tracking |
|---|---|---|---|
| Home → Topic | Posts front page; Header Nuwe Jy destination stored; draft 1926 has seven root Topic loop | Current render, navigation and owner-approved front-page activation | VGN-3, VGN-52 |
| Topic → Article | Topic template 1859 and one Topic edge on fixture 1887 | Current discovery query/render and representative acceptance | VGN-3, VGN-43 |
| Article → Person | Native expert contributor edge 1887 to 1705, template 372 role loops | Current rendered card/destination; real-role approval is separate | VGN-43, VGN-2 |
| Person → Course | Reverse native query can return Course 1889 while excluding missing Course 1896 | Current render and source-history resolution for stale metadata | VGN-43, VGN-44 |
| Course → Vriendinne exploration | Persisted native start form in template 1733; VGN-5 historical bounded proof | Preserve explicit-action-only guard; current behaviour not repeated | VGN-4, VGN-5 |
| Exploration → membership choice | Published page 933 and draft billing products/prices | Approved offer/tier semantics and usable sale path | VGN-4, VGN-18 |
| Membership purchase → activation | Native WCS/PMPro mapping exists | Payment, status/grace/cancel policy, actual PMPro grant and recovery | VGN-6, VGN-18 |
| Activation → validated return | Intent safe-return adapter exists | Authoritative activation handoff; COMPLETED is not implemented by this bounded slice | VGN-5, VGN-6 |
| Returned Course → correct member price | Woo base/sale prices exist; Perks execution disabled | Approved benefit matrix and server-calculated guest/member proof | VGN-7, VGN-18 |
| Course checkout → paid order | Four native product links; historical form, no guest methods | Route/gateway/identity/notification-safe controlled acceptance | VGN-18, VGN-53 |
| Paid order → Masteriyo access | Native order-status integration; six existing active rows | Verify order provenance, idempotency, failure/refund/reactivation and access | VGN-8, VGN-45 |
| Access → thank-you/start/My Voelgoed | Native learning adapter and saved pages/templates | Current-user privacy, continuation, progress, completion and error states | VGN-8, VGN-45 |
| Transaction → one useful email | Masteriyo completed-order email enabled; send marker code | Agreed ownership, transport outcome, retry and duplicate suppression | VGN-19 |
| Event regression | Tickera/Woo Bridge, ticket mappings and stored instances | Guest purchase, cancellation/refund, download, email/PDF and scan guards | VGN-9, VGN-28 |
| Ordinary Product regression | Native Product/cart/order configuration | Public route, totals, shipping/tax decision and order states | VGN-9, VGN-53 |

**VS1 verdict:** NOT ACCEPTED by this evaluation. No full connected purchase/access journey is demonstrated. Synthetic fixtures can support authorised local acceptance and should remain inventoried; they do not fulfil real-data, rights or production gates.

Course 1889 used by the discovery/intent proof has no linked Woo Product. Course 540 linked to Product 1935 carries the commerce mapping but has no curriculum. The reports therefore describe separate partial proofs. They must not be combined into one supposedly completed Golden Journey. The owner must choose the smallest representative native path and approve any test/data changes separately.

Event/Product regressions remain bounded fix-blockers under the locked spec. Do not expand them into full migration programmes before VS1 unless a shared defect requires it. [Locked specification](../superpowers/specs/2026-10-25-vs1-golden-journey-product-tech-spec_v0.2.0.md).

## G. Cross-System Contradictions

| Finding | Parent resolution | Remaining uncertainty |
|---|---|---|
| Ledger says no deployment; live Core matches finalized current journal | Earlier status text is superseded chronologically. Core is deployed | Fresh artifact re-download not done; behaviour not implied |
| Member Perks 49/41 vs 59/43 drift totals | RESOLVED: production-only vs all-file sets, both independently reproduced | Exact approved runtime source/release OPEN |
| Report 07 says dashboard source missing | REJECTED: tracked assembly-base files match runtime | Rendered actions and provider behaviour OPEN |
| Report 05 says no snippet counterparts | REJECTED: snippet 10 tracked; eight others lack identified counterparts | Complete runtime code provenance/consumers OPEN |
| ACF list=json, schema getter=db | OPEN metadata semantics. Both tools derive labels from their returned ACF local property | Effective save/load precedence not established; matched files do not resolve metadata semantics |
| Historical zero Topic edges vs current one | RESOLVED as later synthetic Article 1887 assignment | Real editorial classification remains absent |
| Report 04 two Event owners/four references | RESOLVED: one current Event 763 plus revision 769; two current profile edges | Legacy consumer migration still gated and edges excluded |
| Home featured 1/1 vs no selected target | Earlier population claim is corrected by page-specific inspection; no value on 1926 | Optional empty slot is not broken |
| Downloadable Course products have no files | RESOLVED by native Masteriyo implementation | Purchase-to-access behaviour unverified |
| Enabled admin test gateway vs no guest methods | Compatible facts, not a contradiction | Current guest checkout not repeated |
| Product redirect code vs historical 404 | Controlled harness proves possible self-exclusion before redirect | Exact live response/root cause OPEN |
| PMPro cancel action vs WCS billing owner | Conditional source risk: dashboard membership action builds PMPro cancel URL; separate WCS actions exist | Persisted action exposure and cross-provider cancellation outcome OPEN |
| HPOS counts vs scanner eligibility | Installed count filter admits placeholder draft status; paid-scan guard checks completed/processing | No actual endpoint/scan result; financial/admission effect OPEN |
| Bricks aliases described as Novamira | Local configured Bricks endpoints point to the required mcp-adapter-default-server | Each agent's effective connection not independently proved; fallback coverage remains partial |
| Action Scheduler complete 5,001 vs 4,205 | Later samples during a live site; retention is a possible cause | Cause not observed; no audit-caused cleanup asserted |

Report 13 still treats some current same-session Perks flag reads as merely older historical state. Reports 02/06/07/08 supply current selected values. Use them as configuration evidence, while preserving behaviour limits. Its proposed FCM amendment should also keep the optional homepage destination separate from the legacy category selector.

### Audit-output incidents

1. The frontend agent reported surfacing a stored Frames remote-template credential in a raw settings result. Its report omits the value. Parent did not reopen the raw result, repeat, use, validate, rotate or revoke the credential. Current validity, privilege, audience, retention and access are unknown.
2. The operations agent reported an overbroad search that emitted credential-shaped test artifacts from outside the authorised project. It stopped the search. Parent has not inspected the unrelated project or the raw values. Whether these are valid secrets or synthetic artifacts is unknown.

These are separate from VGN-54's existing cache-salt disclosure. No new Plane issue was created and VGN-54 was not repurposed. Owner/security follow-up must classify each incident, access/retention and appropriate containment. Credential rotation/revocation requires its own authority and is not performed by this audit. No site compromise or external use is established.

The confidentiality and project-boundary requirements failed. This remains a BLOCKED audit safety gate even though documentation can be delivered. A no-deliberate-runtime-write record does not erase a confidentiality failure.

## H. Redundancy and Legacy

The [canonical Legacy Decommission Register](../architecture/LEGACY_DECOMMISSION_REGISTER.md) remains unchanged. [Report 13](13_REDUNDANCY_DECOMMISSION_v0.1.0.md) proposes entry-specific amendments with consumers, replacement, retention, rollback and owner gates.

| Disposition | Candidates and required interpretation |
|---|---|
| KEEP | Native Woo/WCS/PMPro/Masteriyo/Tickera authorities; derived ACF reverse fields; rich Event/Product relation models; WP-Cron plus Action Scheduler; FluentSMTP plus Mailpit where their distinct roles are confirmed |
| IMPROVE | Member Perks release provenance; source ownership for snippets/MU/theme; account action ownership; FCM migration plan |
| MIGRATE only through existing gates | Legacy semantic classification, approved Person consumers and approved real Event/Product relationships. Excluded fixture edges are not migration targets |
| DEFER/UNKNOWN | Broadcast taxonomy generations, old LMS rows, optional plugin features, generic cards, calendar variants, global class overlap, gateway fallback use and inactive promo data |
| ARCHIVE_CANDIDATE, documentation only | Superseded /uitsendings/ working proposal, if register owner approves; historical provenance must remain |
| REMOVE_CANDIDATE | None established |

The current 31-plugin count, nine snippets, large class library, empty CPTs and unassigned templates do not prove waste. The native tools carry different responsibilities; deactivating them based on names or low counts would add risk.

Preserve legacy medewerker and /medewerkers/ consumers. Owner decisions approve Person 1705 identity/classifications but exclude the existing legacy Event 763 and Product fixture edges and placeholder profiles 754–757. Empty canonical relationship tables are not an instruction to migrate those excluded edges.

Retain PE-008 rollback trees as recovery evidence. No old deployment directory, failed queue item, fixture, attachment or taxonomy is approved for deletion.

## I. Missing Foundation Elements

- One representative, connected Course path with approved identity/content/test scope, native product mapping, curriculum and controlled access evidence. Existing discovery and commercial fixtures are separate.
- A reviewed Member Perks runtime release lineage. Same version 0.18.0 does not identify the installed implementation.
- Safe ownership of payment completion and cancellation actions, including the admin test gateway and PMPro/WCS boundary.
- Accepted business rules for tier benefits, free/paid Course 532, sale/discount interaction, grace/refund/cancellation access and recurring billing. Technical options do not decide those rules.
- A proven local notification sink/effective transport and one-email retry/idempotency contract. FluentSMTP active plus Mailpit registered is not a verified mail route.
- Current rendered template/route/navigation/mobile/accessibility evidence. The Footer 78 tree is empty; template 369 is a generic archive fallback; header destinations remain incompletely resolved. Exact visitor impact is unknown.
- Full media/source/rights mapping and all three source inventories. Local file presence and staging publish state do not discharge migration gates.
- Rendered SEO/schema/sitemap/canonical evidence. Rank Math is declared but absent from recognised runtime plugins; plugin absence is not proof no metadata renders.
- Backup authority and whole-site restore evidence. Core rollback is not a database/uploads backup.
- Complete operational/provider/analytics consumer maps. Current ingress, traffic, tracking/consent, external storage and host scheduler remain UNKNOWN.
- Owner-controlled assessment and containment of both audit-output incidents.

No new custom storage, entitlement, queue, mail, relationship index or routing subsystem is justified by the observed evidence.

## J. Prioritised Recommendations

Existing Plane project e0f4a753-a9d1-447d-974a-fa48ae8912b2 contains 55 issues in the inspected list. Parent and domain agents read relevant current items/comments; no issue was created or updated. Workflow group "unstarted" can contain a state named "Blocked"; those are not conflicting statuses.

| Priority | Action and scope | Existing tracking / owner |
|---|---|---|
| P0 | No confirmed live data-loss, unauthorised paid access or payment-integrity incident was established. Do not label unknowns P0 | Escalate only if direct evidence establishes such an incident |
| P1 audit safety | Classify/contain Frames disclosure and separate out-of-project output incident; preserve values outside reports | Owner/security follow-up under VGN-55 audit context; new incident record not created. VGN-54 is a different existing incident |
| P1 VS1 | Complete the native Course commerce decision and controlled fulfilment proof on the existing authorised local site | [VGN-18](https://app.plane.so/symphony-development/browse/VGN-18), [VGN-45](https://app.plane.so/symphony-development/browse/VGN-45), [VGN-8](https://app.plane.so/symphony-development/browse/VGN-8) |
| P1 scoped defect investigation | Verify linked Product single exclusion hypothesis before repair or rewrite changes | [VGN-53](https://app.plane.so/symphony-development/browse/VGN-53) |
| P1 change control | Identify accepted Member Perks runtime source/artifact before deploying or enabling benefits | Member Perks owner; VGN-18/VGN-6 acceptance dependencies |
| P1 transactional guard | Resolve admin test thank-you side effect and customer-facing cancellation ownership before controlled paid/billing acceptance | VGN-18, [VGN-6](https://app.plane.so/symphony-development/browse/VGN-6) |
| P1 fulfilment | Agree email ownership, delivery outcome and retry/idempotency | [VGN-19](https://app.plane.so/symphony-development/browse/VGN-19) |
| P1 connected slice | Join discovery/intent evidence to the representative paid/access path; keep VGN-5 overall acceptance open | [VGN-3](https://app.plane.so/symphony-development/browse/VGN-3), [VGN-5](https://app.plane.so/symphony-development/browse/VGN-5), VGN-43 |
| P2 launch | Finish route/navigation/footer and current accessibility/cache/mobile evidence while preserving existing design | VGN-10, VGN-20, VGN-46; draft VGN-51/VGN-52 |
| P2 launch decision | Confirm intended tax/shipping treatment with responsible business owner before public sales | VGN-18/commercial acceptance; no legal conclusion from tax flag |
| P2 regressions | Reconcile Tickera HPOS count/admission status and verify Event/ordinary Product regressions | VGN-9, VGN-28/VGN-35 where migration is concerned |
| P2 source/recovery | Inventory source owners for runtime snippets/theme/MU code; reconcile stale ledger and backup/restore evidence | VGN-21, VGN-55, affected domain owners |
| P2 full launch | Source inventory, rights, editorial/LMS/shop/event/media migration, SEO and cutover | VGN-23–35; VGN-16/17; VGN-30 final cutover |
| P3 later | Template/class/plugin consolidation and retention-policy refinement after consumer proof | Canonical register gates and domain owners; no cleanup authorised |

No guest payment was demonstrated. A real supported Woo gateway is required for customer payment acceptance, but not automatically for every separately authorised backend fulfilment test. The owner may choose a controlled supported test on the existing local site with explicit identity, notification and rollback scope. This audit does not configure a gateway, create a clone or authorise a test order.

## K. Development Roadmap

### Next milestone: one complete native Course journey

1. Use VGN-18 to confirm the representative Course/Product/curriculum choice and commercial rules. Preserve the bounded VGN-5B acceptance and its outstanding screen-reader/isolation limits.
2. Resolve VGN-53's query/redirect interaction and the admin test gateway/cancellation boundaries through narrow independently reviewed changes, if confirmed. Do not flush rewrites or redesign the shop as an audit follow-up by default.
3. Establish the approved local test identity and effective notification sink on the existing environment. No additional environment or database is proposed.
4. Under separate execution authority, prove native Woo order-to-Masteriyo access, failure/refund/reactivation, repeat-order/idempotency, guest-account handling, progress and My Voelgoed privacy. VGN-45 owns learner proof.
5. Resolve VGN-19 delivery/one-email semantics and VGN-6/VGN-7 membership, validated return and tier price transitions. Reconcile Member Perks source before enabling its execution paths.
6. Recheck the connected browser journey, cache isolation, mobile/keyboard/error states and deferred accessibility. Run the Event/ordinary Product bounded regressions under VGN-9, then VGN-11 acceptance.

This sequence is a recommendation. It grants no implementation, runtime, test transaction, deployment, merge or publication authority.

### Later milestone: full consolidation and production launch

Use VGN-23–35 to inventory/reconcile all three source sites, approved identities/content/media rights, learners/progress, shop/customer/subscription continuity, ticket liabilities, redirects, SEO, operational recovery and cutover. Preserve source IDs, mapping, rollback and reconciliation evidence. A passing synthetic VS1 is not a completed source migration or production launch.

Draft PRs #91/#92 remain separate candidate reviews. Final source integration requires exact diff/base/head, required CI and independent review plus explicit owner merge authority. No merge recommendation is made here.

### NATIVE CAPABILITY REVIEW

| Capability checked | Existing provision | Next smallest action |
|---|---|---|
| WordPress Core | Content, taxonomy, users, URLs, queries and scheduled hooks | Complete data/route ownership and approved native workflows |
| Bricks | Templates, native Queries/Filters, dynamic data, Forms, conditions and components | Verify current assignment/render and repair focused confirmed gaps |
| ACF Pro | Editable schemas/options, forward and bidirectional relationships | Preserve simple relationships; reconcile source labels and stale metadata |
| WooCommerce/Subs | Product/price/order/payment and recurring lifecycle | Prove native configured transitions and approved commercial rules |
| PMPro | Access levels/state and Woo add-on | Keep billing and access cancellation aligned |
| Masteriyo | Native product mapping, enrolment, curriculum and progress | Complete one Course and verify fulfilment/access |
| Tickera/Woo Bridge | Events, tickets, order integration and admission | Verify count/payment guards and bounded regressions |
| Member Perks | Existing benefit/claim integration | Reconcile runtime release; do not enable unapproved rules |
| Fluent tools | Forms/CRM/mail transport and snippet management | Verify actual consumers/transport and source ownership |
| Cloudflare | No current routing/traffic verification | Keep UNKNOWN; no added PE-008 prerequisite |

CUSTOM_DEVELOPMENT=NOT_AUTHORISED by this evaluation. No measured native deficiency warrants a replacement subsystem.

## L. Final Verdict

Architecture maturity is sufficient to continue native-first Golden Journey development. File deployment discipline is strongest in Platform Core, and the ACF relationship approach is appropriately simple for Article/Course roles. Provider boundaries are mostly coherent.

Foundation quality is uneven. Member Perks release provenance, runtime-only glue, incomplete content/curriculum, account-action ownership and operational evidence need attention. Current rendered website behaviour remains unverified. The evaluation did not demonstrate payment, purchase-to-access, ticket admission, email delivery, full migration or launch readiness.

The next development milestone is the VGN-18/VGN-45 native Course fulfilment slice, with notification and test-gateway safety resolved before execution, followed by membership/return/price and connected acceptance. Full migration and production cutover remain later milestones. Do not derail that sequence with speculative cleanup or a new custom platform layer.

No live redundancy removal is supported. The two audit-output incidents prevent an unqualified safety/completion pass. Current ingress/traffic, rights, effective email transport, backups and whole-site restore, external analytics and exact end-to-end authority transitions cannot be concluded from the available evidence.

## M. Evidence, orchestration and completion assessment

### Orchestration and report registry

Exactly 13 distinct T3 child tasks were dispatched through provider codex_codex_pro, requested model gpt-6-luna and reasoningEffort=max. The capability call exposed support; every completed child recentRuns reports executedModel=gpt-6-luna. Dispatcher responses record max. Children received bounded prompts with no parent history and no permission to spawn agents. No replacement or extra review agent was started.

At most 4 children ran at once. Agents 01–04 started first; slots were filled 05–12 only after completion notifications. Agent 13 started only after all 12 initial submissions were read and their 12 headings checked. Parent read the complete final report before master synthesis. [Execution ledger](AGENT_EXECUTION_LEDGER_v0.1.0.md).

| Report | Location |
|---|---|
| 00 Master | [00_MASTER_EVALUATION_v0.1.0.md](00_MASTER_EVALUATION_v0.1.0.md) |
| 01 Runtime | [01_RUNTIME_FOUNDATION_v0.1.0.md](01_RUNTIME_FOUNDATION_v0.1.0.md) |
| 02 Plugins | [02_PLUGIN_INVENTORY_v0.1.0.md](02_PLUGIN_INVENTORY_v0.1.0.md) |
| 03 CPT/taxonomy | [03_CPT_TAXONOMY_v0.1.0.md](03_CPT_TAXONOMY_v0.1.0.md) |
| 04 ACF | [04_ACF_RELATIONSHIPS_v0.1.0.md](04_ACF_RELATIONSHIPS_v0.1.0.md) |
| 05 Custom code | [05_CUSTOM_CODE_v0.1.0.md](05_CUSTOM_CODE_v0.1.0.md) |
| 06 Commerce | [06_WOOCOMMERCE_v0.1.0.md](06_WOOCOMMERCE_v0.1.0.md) |
| 07 Membership | [07_MEMBERSHIPS_v0.1.0.md](07_MEMBERSHIPS_v0.1.0.md) |
| 08 LMS | [08_LMS_COURSES_v0.1.0.md](08_LMS_COURSES_v0.1.0.md) |
| 09 Events | [09_EVENTS_TICKETING_v0.1.0.md](09_EVENTS_TICKETING_v0.1.0.md) |
| 10 Editorial | [10_CONTENT_EDITORIAL_v0.1.0.md](10_CONTENT_EDITORIAL_v0.1.0.md) |
| 11 Frontend | [11_BRICKS_FRONTEND_v0.1.0.md](11_BRICKS_FRONTEND_v0.1.0.md) |
| 12 Operations | [12_INTEGRATIONS_OPERATIONS_v0.1.0.md](12_INTEGRATIONS_OPERATIONS_v0.1.0.md) |
| 13 Redundancy | [13_REDUNDANCY_DECOMMISSION_v0.1.0.md](13_REDUNDANCY_DECOMMISSION_v0.1.0.md) |

Supporting documents: coordination brief, parent baseline, parent review notes, execution ledger and [final validation record](REPORT_VALIDATION_v0.1.0.md). Original domain reports are preserved; material corrections are explicit in section G and parent notes. Final link validation found one unresolved fragment in report 08. The corrected target is recorded in the validation record; the referenced file exists. Some reports lack per-finding severity or complete consumer verification. Their section counts do not waive those limits.

### Observed parent checks

~~~text
git rev-parse HEAD
git status --short
git diff --name-only
git diff --check
git ls-remote origin HEAD refs/heads/main
gh pr view 95 --repo Carpe-Diem-Media/vgnova --json state,headRefOid,baseRefOid,url
gh api repos/Carpe-Diem-Media/vgnova/commits/454346d9e6a2c36864a4bbd944e52596f16e09c1/check-runs
php site-code/bin/vgnova-runtime-mutate.php --target=vgnova-local-10039 --wp-root='/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public' --db-socket='/home/jcschoeman96/.config/Local/run/nAZNpFs99/mysql/mysqld.sock' --status
PYTHONDONTWRITEBYTECODE=1 python3 site-code/bin/deploy-platform-core.py status --target vgnova-local-10039 --wp-root '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public'
~~~

HEAD/remote remained 454346d9e6a2c36864a4bbd944e52596f16e09c1. Current main's four observed checks, Dashboard, Canonical Source, Platform Gate and artifact publication, succeeded on [workflow 43927385765](https://github.com/Carpe-Diem-Media/vgnova/actions/runs/43927385765). This is existing-source CI, not CI for these uncommitted audit documents.

PR #95 remains OPEN, unmerged, head 771b0c92b1c799c468ce9f74aac63ba2770cab7f, base 454346d9e6a2c36864a4bbd944e52596f16e09c1. Its five Phase 0 documents were inspected with git show and were not checked out or overwritten. They lacked current local evidence; this audit adds local observations. PR #91/#92 are open drafts at the heads recorded above. No PR was created, taken over, pushed or merged.

Parent's static hash/JSON comparisons, journal fingerprint, standalone MU query harness, selected installed code and safe endpoint-configuration inspection are documented in full in parent review notes. Most delegated SQL client scripts ran on stdin and were not retained as executable artifacts; their reports record query families and results. That limits independent replay of every aggregate without reconstructing the safely scoped client.

Final observed Git status is only the new untracked docs/evaluation directory alongside pre-existing untracked .agents and site-code/runtime-tools/__pycache__ directories. No tracked diff, branch change or commit is introduced. Final observed status and file hashes appear in the validation record.

### Completion assessment

Task type: RESEARCH / DOCUMENTATION. Task/issue: VGN-55, 13-domain evaluation and master reconciliation. Role: parent independent synthesis of delegated research, with author self-assessment of the resulting documentation. This is not an independent exact-PR merge review.

| Criterion | Result | Evidence |
|---|---|---|
| Exact source/runtime baseline and authority scope | PASS | Parent baseline, runtime report, current refs and journal |
| 13 distinct Luna Max children; concurrency and Agent 13 ordering | PASS | Dispatcher and completed execution records; ledger |
| 13 domain reports with required 12 sections and honest limits | PASS with documented findings/coverage qualifications | Structure checks and complete parent reads |
| Independent checks and cross-report reconciliation | PASS | Parent hashes, source inspection, MU harness and section G |
| Gated canonical-register amendment proposals | PASS | Report 13; actual register unchanged |
| Master A–L assessment and linked report registry | PASS for document delivery | This file; final validation |
| No deliberate WordPress mutation, transaction, deployment or cleanup | PASS within observed actions | Read-only/static commands; bootstrap incidental effects not fully measurable |
| Preserve existing checkout/history and authorised output boundary | PASS for parent writes | Empty tracked diff; docs/evaluation only; original untracked paths remain |
| Secret confidentiality and project read scope | FAIL | Two reported tool-output incidents; containment unresolved |
| Exhaustive current behaviour and consumer verification | UNVERIFIED | Explicit gaps across all domains; no current render/transaction suite |

Acceptance breakdown: 8 PASS, 1 FAIL, 1 UNVERIFIED across 10 explicit audit criteria. Domain coverage remains 13 PARTIAL, 0 FULL, 0 wholly inaccessible domains. These are evidence outcomes, not implementation acceptance counts.

Implementation Score: N/A. Task Execution Score: 5/10, capped for the mandatory safety failure. This evaluates the audit delivery, not platform implementation. Deductions reflect failed confidentiality/read scope, unresolved current behaviour/consumer evidence and required corrections to child claims. Reaching 8+/10 would require owner-controlled incident assessment/containment plus fresh scoped consumer/browser/operations evidence and reviewed corrected documentation; no points are pre-awarded. No credential operation or unrelated-project inspection is safe to perform under this task's authority.

Confidence: MEDIUM overall, qualitative as requested. Parent has strong file-parity, registry/schema and selected configuration evidence. Behaviour, accepted Member Perks provenance, incident scope, source rights and full operations remain the main uncertainties.

Verification Status: BLOCKED for the audit confidentiality/read-scope gate. PARTIALLY_VERIFIED describes the website inventory and source/runtime findings only. The documentation set is delivered with those failures and limits disclosed.

Assessment lifecycle: UNVERIFIED at start; PARTIALLY_VERIFIED after observed checks; BLOCKED when the mandatory safety failures were reported. Only containment/correction and fresh applicable verification can change that gate. No score, issue state or artifact count authorises merge or launch.

Known gaps and next owners: security/credential owners assess the two output incidents; Member Perks owner reconciles release provenance; commerce/learning owners progress VGN-18/VGN-45/VGN-19 under separate execution scope; frontend owner supplies current safe browser evidence; source/media/operations owners close migration/rights/restore gaps. No Plane updates were made.

Merge readiness: NOT_VERIFIED_FOR_MERGE. No exact candidate independent diff/CI/authority review has been performed for an audit PR, and no merge recommendation is made.

