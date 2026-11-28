# Content and Editorial Inventory: v0.1.0

**Inspection date:** 2026-11-19
**Runtime target:** LocalWP site vgnova-local-10039, site ID nAZNpFs99, WordPress root /home/jcschoeman96/Local Sites/voelgoed-novamira/app/public/
**Source baseline:** main at 454346d9e6a2c36864a4bbd944e52596f16e09c1
**Scope:** Read-only content, classification, relationships, editorial metadata, media, route and migration assessment. No runtime writes, HTTP requests, source-site access, exports, transactions, cleanup or tracker updates.

## 1. Executive Summary

The local WordPress database holds a small staging corpus. It does not contain a reconciled copy of the three legacy sites. Direct, read-only SQL confirmed 873 wp_posts rows in the local database. In the editorial and public-domain types reviewed, the runtime has only **2 published Articles, 36 published Pages, 6 published Masteriyo Courses, 3 Lessons, 2 Sections, 1 canonical Person, 5 legacy Medewerkers, 17 Tickera Events, 8 published WooCommerce products, 5 Voelgoed Live episodes and 7 Vennote**. Product totals across all statuses are 24 parent products plus 14 published variations. The repository target remains 500+ articles, 15 courses and 50+ products; there is no evidence those source inventories have been imported or reconciled.

The two published Articles are not approved launch content. Article 1887 is explicitly marked as a synthetic fixture and remains published. Article 375 is a placeholder/demo-or-import candidate with unresolved origin and a removal gate that remains open. The only current content_topic relationship is on Article 1887, so it is fixture classification evidence, not approved editorial classification. Article 375 has one WordPress core category relationship, not a content_category or canonical topic assignment.

The canonical Person record, ID 1705, represents the owner-approved identity for legacy profile 753. That approval does not establish approval for the synthetic Article/Course relationships attached to that Person, source rights, profile-content migration, or route cutover. Legacy profiles 754 to 757 remain published staging records and are explicitly excluded from migration. The synthetic Article 1887, Course 1889, Sections 1890/1893 and Lessons 1891/1892/1894 remain published, marked, and not retired. The reverse Person field still refers to deleted Course 1896.

Classification, editorial provenance, media rights, SEO metadata, schema output, public route behavior and old-site migration remain incomplete or unverified. No Rank Math plugin file or active-plugin entry was found, and no Rank Math/Yoast per-record SEO fields were present. All 92 attachment file paths checked resolve to local files, but file existence does not establish source, license or reuse permission. Four published course products are configured as virtual/downloadable, yet none has a registered downloadable file.

**Domain readiness:** PARTIAL inventory coverage; NO launch readiness. The native WordPress, Bricks, ACF, WooCommerce, Masteriyo and Tickera model is sufficient for the documented content domains. The next work is source inventory, rights/provenance, editorial mapping, destination completion and native-workflow verification.

## 2. Inspection Scope and Evidence

### Sources read

- Read the full [coordination brief](COORDINATION_BRIEF_v0.1.0.md) and [parent evidence baseline](PARENT_BASELINE_v0.1.0.md), including the requested read-only boundary and twelve-section report contract.
- Read reports [01 Runtime Foundation](01_RUNTIME_FOUNDATION_v0.1.0.md), [02 Plugin Inventory](02_PLUGIN_INVENTORY_v0.1.0.md), [03 CPT and Taxonomy Inventory](03_CPT_TAXONOMY_v0.1.0.md), [04 ACF Relationships](04_ACF_RELATIONSHIPS_v0.1.0.md), [05 Custom Code](05_CUSTOM_CODE_v0.1.0.md) and [06 WooCommerce](06_WOOCOMMERCE_v0.1.0.md). Their dated evidence was compared with current local SELECT results rather than treated as live behavior proof.
- Read [AGENTS.md](../../AGENTS.md), the [implementation ledger](../architecture/IMPLEMENTATION_LEDGER.md), [legacy decommission register](../architecture/LEGACY_DECOMMISSION_REGISTER.md), [VS1 current reality audit](../architecture/VS1_CURRENT_REALITY_AUDIT_v1.1.0.md), [Platform Core assembly contract](../architecture/PLATFORM_CORE_ASSEMBLY_CONTRACT_v1.5.0.md), [runtime deployment status](../architecture/PLATFORM_CORE_RUNTIME_DEPLOYMENT_v1.0.1.md), [runtime mutation coordination v1.1.0](../architecture/VGNOVA_RUNTIME_MUTATION_COORDINATION_v1.1.0.md) and [v1.2.0](../architecture/VGNOVA_RUNTIME_MUTATION_COORDINATION_v1.2.0.md), the locked [VS1 product-tech spec](../superpowers/specs/2026-10-25-vs1-golden-journey-product-tech-spec_v0.2.0.md) and the current [VGN-5B runtime-binding plan](../superpowers/plans/2026-11-10-vgn-5b-runtime-binding_v0.1.1.md).
- Read the content-specific [Post 375 provenance audit](../architecture/POST_375_PROVENANCE_AUDIT.md), [content-category mapping](../architecture/CONTENT_CATEGORY_TO_TOPIC_LOCAL_MAPPING.md), [topic seed editorial review](../architecture/CONTENT_TOPIC_SEED_EDITORIAL_REVIEW.md), [Post-Task-3C state](../architecture/POST_TASK_3C_CURRENT_STATE.md) and relevant Legacy Decommission Register entries. The source records themselves are historical snapshots where indicated; they do not prove current rendered routes.
- Read current Plane records for VGN-24, VGN-32, VGN-25, VGN-17, VGN-16, VGN-43 and relevant VGN-2, VGN-18, VGN-26, VGN-30 and VGN-55 context. No Plane records or comments were created or changed.

Evidence anchors for the existing records: [report 03, effective CPT registry and status counts](03_CPT_TAXONOMY_v0.1.0.md#L64-L124), [report 03, assignment and source/runtime conclusions](03_CPT_TAXONOMY_v0.1.0.md#L323-L383) and [report 03, current template inventory](03_CPT_TAXONOMY_v0.1.0.md#L474-L505); [report 04, ACF relationship schemas and runtime edges](04_ACF_RELATIONSHIPS_v0.1.0.md#L401-L423) and [relationship findings](04_ACF_RELATIONSHIPS_v0.1.0.md#L586-L714); [report 06, product inventory and course pairs](06_WOOCOMMERCE_v0.1.0.md#L72-L101) and [checkout/route limits](06_WOOCOMMERCE_v0.1.0.md#L130-L153); [Post 375 provenance, attachment and lifecycle decision](../architecture/POST_375_PROVENANCE_AUDIT.md#L94-L151); [Legacy Register route and Person gates](../architecture/LEGACY_DECOMMISSION_REGISTER.md#L221-L234) and [fixture lifecycle gate](../architecture/LEGACY_DECOMMISSION_REGISTER.md#L536-L549); and [native-first policy](../../AGENTS.md#L809-L877).

### Independent read-only checks

- Confirmed current local Git branch main and HEAD 454346d9e6a2c36864a4bbd944e52596f16e09c1.
- Used a purpose-built PHP 8.5 mysqli client to read the local database over the registered LocalWP socket. It parsed database connection settings in memory from wp-config.php; credential values were not printed, copied or used outside the client.
- Serialized each database read batch with `flock -n /tmp/vgnova-evaluation-read.lock /usr/bin/php8.5` and a PHP script on stdin. SQL was SELECT-only. Direct checks covered current post counts, public IDs/slugs for editorial and public-domain records, fixture-marker presence, relationship references, taxonomy assignments, SEO metadata-key presence, featured-image references, media file existence and downloadable-file registrations. Identity read returned exit 0 with MySQL 8.0.35 and 873 wp_posts rows. One initial downloadable-metadata SELECT had a syntax error due to a reserved alias; it was corrected and rerun successfully under the same lock. No data was changed.
- File checks used is_file() for attachment paths referenced by the database. No media bytes, filenames or file contents were exported.
- Results independently confirmed the report-set database row count (873), current records and key relationship/classification counts. The direct MySQL response reported database version 8.0.35. WordPress 7.1.5 and PHP-FPM 8.5.12 are the versions recorded in report 01, not newly probed here.

### Limits and evidence labels

RUNTIME_CONFIRMED means a direct local database or filesystem metadata observation. SOURCE_CONFIRMED means repository evidence. HISTORICAL_ONLY means a prior task, issue comment or snapshot that was not re-tested. INFERRED is a constrained reading of stored configuration. UNKNOWN means the permitted evidence does not settle the question. CONFLICT marks incompatible observations or documentation.

No WordPress bootstrap, Novamira/Bricks MCP operation, browser, HTTP request, source-site request, database export, active network scan or transaction was run. This avoids route requests that could have incidental effects and obeys the source-access restrictions. Accordingly, rendered behavior, route status, access control, page links, public traffic, SEO output and actual license grants are UNKNOWN unless expressly identified as historical evidence. No private customer, order, attendee or learner records were selected.

## 3. Component Inventory

### Editorial and public-domain record inventory

The table records every in-scope content type with rows in the current database or an identified empty editorial CPT. IDs and slugs are shown only for published records. Non-published states are counts only; private, pending, draft and trashed IDs/slugs are omitted. A published WordPress status is not an editorial approval or public-access attestation.

| Type | Status totals | Published IDs / slugs | Provenance and state |
|---|---|---|---|
| Article (post) | 2 published | 375 integer-maximus-sapien-in-bibendum; 1887 vg-qa-nuwe-jy-dink-nuut | 375 is classified DEMO_OR_IMPORT_PLACEHOLDER / NEEDS_PROVENANCE; 1887 is a marked synthetic fixture. Neither is approved migration content. |
| Page | 36 published, 4 draft, 2 trash | 57 kursusse; 58 dashboard; 60 membership-account; 61 membership-billing; 62 membership-cancel; 63 membership-checkout; 64 membership-confirmation; 65 membership-orders; 66 membership-levels; 67 login; 68 your-profile; 71 produkte; 72 cart; 73 checkout; 74 my-account; 433 account; 434 checkout-1; 435 learn; 436 instructor-registration; 437 instructors-list; 465 vg-component-library; 612 components; 933 voelgoedvriendinne; 935 voelgoednetwerk; 990 my-voordele; 991 my-vriendinneklub; 992 my-voelgoed-netwerk; 1096 20-twintig; 1155 my-voelgoed; 1156 membership; 1157 subscriptions; 1158 orders; 1159 tickets; 1160 learning; 1161 profile; 1900 checkout-test | Many are plugin/account or member journeys. A QA purpose is suggested by checkout-test, but no complete per-page origin, approval or route map was established. |
| Masteriyo Course (mto-course) | 6 published, 1 trash | 532 selfsorg-besige-mas; 534 geloof-identiteit; 536 finansiele-vryheid; 538 huwelik-kommunikasie; 540 mindfulness-vroue; 1889 vg-qa-klim-uit-jou-groef | 1889 is a marked synthetic fixture. The other five are local records with unverified source lineage, rights and migration status. 532 has no Woo product link; 1889 is not a course product. |
| Masteriyo Section (mto-section) | 2 published | 1890 afdeling-1-n-nuwe-begin; 1893 afdeling-2-hou-aan-beweeg | Both are marked synthetic children of the fixture course. |
| Masteriyo Lesson (mto-lesson) | 3 published | 1891 neem-die-eerste-tree; 1892 kyk-na-nuwe-moontlikhede; 1894 jou-eenvoudige-aksieplan | All three are marked synthetic children of the fixture course. |
| Canonical Person (person) | 1 published | 1705 lynette-beer | Canonical identity corresponding to owner-approved legacy profile 753. Biography, portrait, editorial migration and public route behavior remain unverified. |
| Transitional profile (medewerker) | 5 published | 753 lynette-beer; 754 annelize-van-der-merwe; 755 thandi-mokoena; 756 elna-pretorius; 757 retha-coetzee | Owner decision: 753 is genuine and maps to Person 1705; 754 to 757 are staging/fake placeholders marked DO_NOT_MIGRATE. All remain published pending consumer and decommission gates. |
| Tickera Event (tc_events) | 17 published | 151 aanlyn-uitsendings; 163 donasies-kompetisies; 164 feeste-markte; 165 funksies-vroue-oggende; 166 sport-gholfdae; 167 vertonings-produksies; 172 reise-toere; 181 werkswinkels-praatjies; 542 vroue-oggend-rustig; 545 werkswinkel-begroting; 548 herfsmark-gemeenskap; 551 aanlyn-selfsorg-101; 554 weekend-drakensberg; 763 test-event-medewerkers-qa; 1189 qa-dashboard-event-early; 1191 qa-dashboard-event-late; 1193 qa-dashboard-event-past | 763 and 1189/1191/1193 are QA-labeled records. Other events' original-site provenance, actual date validity and editorial approval were not confirmed. |
| WooCommerce parent Product (product) | 8 published, 7 draft, 5 pending, 1 private, 3 trash | 842 event-test; 844 event-test-2; 845 event-test-3; 846 event-test-4; 1935 mindfulness-vir-vroue; 1936 gesonde-huwelik-kommunikasie; 1937 finansiele-vryheid-basiese; 1938 geloof-identiteit | 842/844/845/846 are published test ticket products. 1935 to 1938 are linked to four published Masteriyo Courses; route, original content, purchase, download and rights approval are unverified. All eight published records are assigned Woo uncategorized. |
| WooCommerce variation (product_variation) | 14 published | 906 voelgoedvriendinne-brons-maandeliks; 907 voelgoedvriendinne-brons-jaarliks; 909 voelgoedvriendinne-silver-maandeliks; 910 voelgoedvriendinne-silver-jaarliks; 912 voelgoedvriendinne-goud-maandeliks; 913 voelgoedvriendinne-goud-jaarliks; 915 voelgoednetwerk-brons-maandeliks; 916 voelgoednetwerk-brons-jaarliks; 918 voelgoednetwerk-silver-maandeliks; 919 voelgoednetwerk-silver-jaarliks; 921 voelgoednetwerk-goud-maandeliks; 922 voelgoednetwerk-goud-jaarliks; 924 voelgoednetwerk-platinum-maandeliks; 925 voelgoednetwerk-platinum-jaarliks | Membership billing configuration records, not editorial Articles. Product visibility, gateway and renewal behavior were not tested. |
| Voelgoed Live (uitsending) | 5 published | 602 voelgoed-pad-selfsorg-tyd; 604 rustige-oggend-asemhaling-gebed; 605 praatpod-stres-bestuur; 607 live-qa-genade-werkweek; 609 luisterlys-mindfulness-vroue | Current public root is /voelgoed-live/. Content/media origin and rights are not confirmed. |
| Partner (vennoot) | 7 published | 674 eksekuteurs; 675 everest-wealth; 780 wolkskool; 781 sos-skole; 782 living-naturally; 783 helpende-hand; 784 atkv | Partner relationship/brand approvals and media rights were not reviewed here. |
| Member Perk (vg_perk) | 15 published, 5 draft | Slugs omitted because this non-public CPT carries member-benefit content. | Not an editorial/public archive. Many records are QA-named in prior inventory, but the full current provenance/retirement map is not confirmed. |
| Attachment (attachment) | 92 inherit | IDs/slugs omitted; aggregate and referenced-file checks only. | All 92 recorded local paths resolved to files. Rights and reuse permission remain unknown. |

The following editorial or domain CPTs have zero wp_posts rows in the current inventory: kompetisie, kompetisie_entry, event_participation, product_contribution, team_member, testimonial, mto-quiz and mto-question. The registered competition entry key is kompetisie_entry, with rewrite inskrywing; kompetisie_inskrywing is not the current key. Zero post rows do not prove there is no plugin-owned data elsewhere.

Other content-like rows: Bricks has 30 published, 2 draft and 1 trashed template record; WordPress has 6 published block patterns and 1 global-styles record. Masteriyo/CPT residues under unregistered keys remain: course 1 published, lesson 13 published, section 4 published and llms_form 3 published. These are not assumed redundant or safe to remove.

### Key Bricks records

Report 03's metadata-only registry lists 32 non-trashed Bricks templates (30 published and 2 draft). Relevant consumers include template 372 Posts, 731 Product Card, 772/773 legacy Medewerker, 774 Event Single, 1733 Masteriyo Course Single, 1734 Person Single, 1838 Masteriyo Course Archive and 1859 Nuwe Jy Discovery Hub. Template 1919 is a draft Event Landing. Stored template presence does not prove which template rendered any current route.

## 4. Current Architecture and Configuration

### Content classification

- Canonical semantic taxonomy content_topic is owned by source-controlled Voelgoed Platform Core, with 55 terms in seven roots: nuwe-jy, geloof, self, werk-geld, ouerskap, huwelik and mensstories. Current direct SQL found one relationship, on synthetic Article 1887 only.
- Legacy content_category has 13 terms and zero current object assignments. It remains registered and has consumers in Bricks/FluentSnippets, Featured Content Manager, partner mapping and HappyFiles; zero assignments do not pass its retirement gate.
- WordPress core category has one stored relationship to post 375 even though the taxonomy is detached from registered post types and its stored count is zero. This relationship must not be described as content_category.
- All eight published Products are in product_cat=uncategorized. Thirteen of 17 published Events have an event_category assignment. No current Event or Course has a content_topic relationship.
- Voelgoed Live retains two parallel taxonomy generations. broadcast_series has 3 terms/5 assigned objects while uitsending_series has 3 terms/0 assignments. broadcast_type has 9 terms/5 assignments and uitsending_type has 4 terms/5 assignments. They are not proven equivalent.
- The older seven-object topic seed proof and its rollback record are historical. The current one topic relationship belongs to the marked Article 1887 fixture. The earlier Post-Task-5B statement of zero persistent relationships and current SQL disagree; report 03 records the same conflict. No reassignment was made.

### Relationship authority

- Simple editorial relationships are ACF fields, including Article primary_author, additional_authors, expert_contributors and reviewed_by; Course lead_instructor, additional_instructors and experts; plus derived Person reverse fields. Current published forward edges are one synthetic Article expert_contributors edge and one synthetic Course additional_instructors edge, both to Person 1705.
- The Person reverse field contains Course 1889 and a dangling reference to deleted Course 1896, in addition to the synthetic Article relationship. The deleted reference is not repaired by this audit.
- Legacy Event field event_medewerkers on published Event 763 contains two references to legacy profiles 753 and 757. Existing owner decisions exclude those Event relationships from migration.
- Legacy product/person fields still contain pending or trashed references. Their exact unpublished slugs and values are intentionally omitted. Existing architecture decisions exclude these legacy relationships until separately approved.
- Canonical event_participation and product_contribution records both have zero rows. Their richer role-aware schemas remain distinct from simple ACF relationships; do not infer that the older fields have migrated.
- Masteriyo/Woo references are reciprocal on four Course/Product pairs: 534 paired with 1938, 536 paired with 1937, 538 paired with 1936 and 540 paired with 1935. Course 532 and synthetic Course 1889 have no linked Woo product.

### Editorial and commercial lifecycle

WordPress technical states are draft, pending, private, publish and trash; they do not record editorial permission, original-site lineage, image rights or migration approval. A later import or publish transition must be guarded by the approved content map, rights evidence, author/Person mapping, backup, dry run, reversibility and reconciliation required by VGN-24/VGN-32.

Synthetic Article/Course fixtures use an explicit marker and remain published to support a local proof. The marker identifies test data; it does not approve production use. Their lifecycle remains RETAINED_LOCAL_FIXTURE  to  separately reviewed retirement; no cleanup is authorized by this assessment. The prior comment on VGN-43 says 1887 and 1889 remain published and the deleted 1896 reverse reference persists; the fixture journey is not a real-content acceptance gate.

The Person migration lifecycle is separately governed: profile 753 has an approved canonical identity mapping to Person 1705; profiles 754 to 757 are DO_NOT_MIGRATE; the remaining profile, relationship, URL, template and consumer gates are open. Keep /medewerkers/ operational until the approved migration and redirects pass their gates.

WooCommerce remains the product/order authority, Subscriptions owns recurring billing, PMPro membership access, Masteriyo course identity/access and Tickera Events/tickets. Published product state and Course links do not prove payment, enrollment, digital-file availability, entitlement, fulfillment, renewal or refund behavior.

## 5. How It Works

### Editorial entry and presentation

WordPress stores Article/Page and plugin-owned records. ACF supplies the site's ordinary custom fields and simple relationships. Bricks templates, dynamic data, conditions and Query Loops render those objects. Platform Core owns the explicit content_topic exception and its classification roles. The installed architecture uses native facilities already; this report found no measured reason to add a custom content repository, relationship index, import table or replacement query engine.

The current Article presentation is structurally represented by Bricks templates and an Article archive candidate, but the /content/ archive is absent in the current-state register: singles use /content/{slug}/, while no published Page or post archive provides the index. No route request was made here.

### Author, reviewer and Person relationships

ACF relationship fields store simple Article/Course roles. Bidirectional values on Person are a convenience view, not an independent editorial source of truth. A title or existing forward relationship is not proof of authorship, review, credentials or consent. VGN-2's selection of one representative source pairing is not approval of source-site copy, media rights, author roles or WordPress import.

Event participation and Product contribution are separate first-class relationships with roles and lifecycle. Their current record counts are zero. Do not copy the legacy Event/Product edges into them based solely on existing fields or Bricks display needs.

### Media and digital delivery

WordPress Media Library holds 92 attachments with local path metadata. The filesystem check found all 92 files. Published content has 33 featured-image references; every referenced image file exists. No media bytes were inspected, and there is no verified license/rights ledger.

WooCommerce products 1935 to 1938 are marked virtual and downloadable, but _downloadable_files is empty for all four. That means no local Woo download is configured for these records. It does not establish whether Masteriyo serves course content through another mechanism. VGN-25 covers books and audiovisual source URLs, file metadata, access rights, licensing, reader delivery and entitlements; those gates remain open.

### Editorial state and migration

The current record lifecycle is stored by WordPress/owning plugins; source provenance, approvals, legal rights and import status are not uniformly represented as validated editorial state. VGN-24 is the source inventory and mapping stage; VGN-32 is the governed Article/topic/media execution gate; VGN-25 is the digital-media continuity plan. Do not convert a local publish status into approval or source migration evidence.

## 6. Source Versus Runtime

| Area | Source-controlled or authority evidence | Current runtime evidence | Assessment |
|---|---|---|---|
| Repository baseline | Local main at 454346d9e6a2c36864a4bbd944e52596f16e09c1. Report 01/parent baseline records matching remote main. | Current checkout confirmed on main at that SHA. | BOTH_CONFIRMED for the inspected local source baseline; no merge or deploy action was part of this task. |
| Content corpus | Project target: 500+ legacy Articles, 15 LearnDash Courses and 50+ Woo Products across three legacy sites. VGN-24/25/32/26 define inventories, rights and migration gates. | Only 2 published local Articles, 6 Masteriyo Courses, 8 published Woo Products and the other small staging counts shown in §3. No source-site exports or requests were made. | CONFLICT if local counts are presented as a completed migration; migration remains incomplete/unknown. |
| Canonical topics | Platform Core source owns content_topic; source and ACF principles are documented in AGENTS and the Legacy Register. | 55 terms/seven roots; one current edge on marked fixture 1887. | BOTH_CONFIRMED for registration/term structure and current edge; provenance of the older expected zero state is unresolved. |
| ACF relationship schemas | Report 04 inventories 28 field groups/247 fields; seven repository JSON groups, two plugin JSON groups, two local PHP groups and 17 database-only groups. | Current simple Article/Course/Person edges match the fixture record; current direct edge counts recorded in §4. | RUNTIME_CONFIRMED; ACF List/Get source labels disagree for eight matching JSON definitions in report 04 (CONFLICT). The field definitions do not provide provenance/rights by themselves. |
| Rank Math / SEO | AGENTS describes Rank Math as the SEO and schema tool. | Rank Math, Yoast SEO and AIOSEO plugin files and active entries were absent. No Rank Math/Yoast title, description, canonical, robots or schema metadata keys were found in wp_postmeta. | CONFLICT between declared stack and inspected runtime. Rendered SEO behavior is UNKNOWN. |
| Platform Core and Bricks | Source repo and reports describe Platform Core adapters and Bricks layouts. | Runtime content and template metadata exist. Report 01 records current Platform Core deployment parity; report 03 has a 32-template structural inventory. No rendering was tested. The implementation ledger line 7 and deployment status v1.0.1 lines 44 to 52 still say a successful deployment/approval is not evidenced; report 01 and later VGN-50 evidence supersede those historical statements. | CONFLICT in source documentation status; current installed-tree parity is recorded by report 01. Source presence does not prove a consumer rendered or route worked. |
| Snippets and course route adapter | Project policy requires FluentSnippets for snippets and source control for custom behavior. | Reports 02/05 identify nine runtime snippets, eight without a repository counterpart, and runtime-only vg-masteriyo-course-products.php; current behavior/source ownership are not proven. | CONFLICT/provenance risk. Do not rely on these as reviewed canonical source or replace them without caller/native capability checks. |
| Member Perks | Nested plugin architecture owns its code separately. | Reports 02 and 05 disagree on source/runtime tree drift: report 02 records 59 changed shared files and 43 runtime-only files; report 05 records 49 and 41. This report did not repeat the tree comparison. | CONFLICT in the evidence totals and source parity. Perk content and behavior are outside public editorial scope; do not overwrite or expose it. |
| Content origin and rights | VGN-24/25/32 require source provenance and rights evidence before migration. | No old-site source files, media licenses, approvals or source inventory were inspected. | UNKNOWN; source-origin access is a prerequisite, not a substitute for authorization. |

## 7. Completeness and Functionality

### Missing-content and metadata matrix

| Domain | Current evidence | Missing or unverified |
|---|---|---|
| Articles | 2 published; 1 Article has post_content, 1 excerpt; 1 featured-image reference. | No source reconciliation or primary author field verified; only topic edge is a fixture. No migration batch, editorial approval, rights ledger or all-500+ source map. |
| Pages | 36 published, 4 draft, 2 trash. Raw post_content is non-empty on 21 published pages; _bricks_page_content_2 is present on 16, and _bricks_page_content on one. The keys were not deduplicated to a unique-page count. | Page metadata counts cannot tell whether a route renders complete content. No per-page destination, rights, navigation, SEO or editor-flow acceptance matrix. |
| Courses and lessons | 6 Courses, 3 Lessons, 2 Sections; all fixture children are marked. Five Course records have excerpts and five have featured-image refs. | No evidence of the 15-source LearnDash course/lesson/quiz inventory, learner identity, access, progress, quiz or completion migration. Rights/source for five nonfixture local Courses are unknown. |
| Person / Medewerker | Person 1705 has a title/slug but no stored body, excerpt or featured-image reference. All five legacy profiles have body, excerpt and thumbnail references. | Profile copy/portrait rights, source field mapping, credential verification, canonical author roles, and route/redirect behavior are unverified. Four legacy profiles are expressly excluded from migration. |
| Events | 17 published; 13 have body content, 5 excerpts, 13 featured-image references and 13 event-category edges. Event 763 has two legacy profile references. | Date/ticket validity, approved source provenance, accessible media rights, Event Participation records, public flow and current route behavior are not verified. |
| Products | 8 published; 4 have body content, 4 excerpts, 4 featured-image refs; all 8 are uncategorized. Four are test ticket Products; four link to Courses. | Catalog classification, original product content, source/rights, current route, payment availability, download fulfillment and purchase-to-enrollment are unverified. |
| Product downloads | Products 1935 to 1938 are virtual/downloadable; all four have zero registered download files. | Download delivery and rights for any course or other media assets remain unknown. No downloads were fetched. |
| Voelgoed Live | Five Episodes; all have body, excerpt and featured-image references. | Series/type generations overlap; assignment provenance, source media rights and rendered/archive routes are unverified. |
| Partners | Seven published; none has body content, two have excerpts, none has a featured-image reference. | Approved logo/brand assets, descriptions, partner relationships, and content source are unknown. |
| SEO/schema | blog_public=1. No Rank Math/Yoast per-record keys; Rank Math/Yoast/AIOSEO conventional plugin paths absent. | No page title/meta description/canonical/robots/schema output or sitemap/indexability behavior was rendered. Plugin absence does not prove absence of custom metadata output. |
| Featured media | 92/92 attachment rows have a stored local relative path; 92/92 files exist. Across 121 published in-scope records, 33 have a featured-image reference and all 33 files exist. | No content inspection, image validation, alt-text audit, responsive crop review, license or provenance decision. File existence is not permission. |
| Unregistered LMS residue | course 1, lesson 13, section 4, llms_form 3 published rows. | Origin, source plugin, ownership and consumers are unknown. No cleanup or migration is authorized. |

Featured-image coverage among published records:

| Type | Featured references / records | Referenced local files present |
|---|---:|---:|
| Article | 1 / 2 | 1 |
| Page | 0 / 36 | 0 |
| Masteriyo Course | 5 / 6 | 5 |
| Person | 0 / 1 | 0 |
| Medewerker | 5 / 5 | 5 |
| Event | 13 / 17 | 13 |
| Product | 4 / 8 | 4 |
| Voelgoed Live | 5 / 5 | 5 |
| Vennoot | 0 / 7 | 0 |
| Lesson, Section, Product variation and Perk | 0 featured references in the inspected published records | 0 |

### Route, SEO and link state

No current URL was requested. The Legacy Register (entries 25 to 31) documents known unfinished or transitional destinations:

- /content/{slug}/ is the single-Article base; /content/ has no published index Page or post archive in the recorded runtime.
- Course singles use /leer/{slug}/, while /kursusse/ is the current transitional archive; the final /leer/ hub and redirects require a dedicated Learning/URL decision.
- Product singles use /winkel/{slug}/, while the shop/archive is /produkte/; the approved shop area and current runtime path differ.
- /mense/ is the canonical Person destination; /medewerkers/ remains live with no redirects until profile and consumer gates pass.
- Runtime competition path is /kompetisie/; canonical project architecture names /kompetisies/. No competition records are currently stored.
- /voelgoed-live/ is the current five-Episode root. The older /podsending/ proposal is superseded, not a confirmed active destination.

Prior evidence also records a historical 404 for Product 1935 (VGN-53, Nov. 17), a previous product sitemap 404/Store API exposure scope (VGN-17), and a wrong Open Graph title report (VGN-16). VGN-16 and VGN-17 have no later comments in the inspected record; these observations are HISTORICAL_ONLY. The current Product 1935 route, current sitemap/API responses, Open Graph tags, all internal/external links and redirect behavior are UNKNOWN. No present-day broken-link finding is claimed.

## 8. Problems, Risks and Contradictions

| Priority | Finding | Evidence and impact |
|---|---|---|
| P2: migration readiness | The local corpus is not the legacy-site migration. | Current local counts are far below the stated source target. VGN-24 is Backlog; VGN-32 is Blocked. Without source inventories and reconciled target maps, articles, author roles, topics and assets cannot be accepted as complete. |
| P2: editorial approval and rights | Published status is being used alongside synthetic and placeholder content. | 1887/1889 and their child records are marked synthetic and remain published. Post 375 is unresolved placeholder/demo-or-import. No approved source/media rights register was inspected. A staging publish state must not flow into a production data gate. |
| P2: classification gap | The one current topic assignment is on a fixture; the nonfixture Article is unclassified in canonical topics. | Current SQL found one content_topic edge total. Post 375 has only the separate core category edge. No production classification coverage can be inferred. |
| P2: identity/relationship integrity | One Person reverse relationship points at missing Course 1896. | Direct metadata read confirms a stale reference after deletion; VGN-43 says it was not manually repaired. Article/Course forward edges are fixture-only. |
| P2: SEO readiness | Runtime does not match the declared Rank Math stack. | No Rank Math plugin file/active entry or SEO metadata keys were found. Prior VGN-16/17 risk evidence was not rechecked. Search/indexing and schema are not verified. |
| P2: route readiness | Canonical and runtime destinations diverge and some hubs are absent. | The /content/, /leer/, /winkel/ and competition path differences are recorded in the Legacy Register. No current route check was permitted. |
| P2: media rights | Local media paths exist but source/license ownership is unverified. | 92 files exist; origin metadata key names include _bricks_image_origin_url and _pexels_source_url, but values/rights were not inspected. Rights cannot be inferred from a valid file path or an origin URL. |
| P2: digital media | Downloadable products have no registered download file. | Four published products have downloadable/virtual flags and no Woo download entries. Course delivery could use another system, but it was not verified; VGN-25 is not complete. |
| P3: missing imagery and descriptive fields | Page, Person, Partner and Product records have limited featured/body coverage. | Counts are in §7; a blank raw body may still have Bricks content. Rendering and editorial adequacy remain unknown. |
| P3: duplicate classification models | Two Voelgoed Live taxonomy generations are populated, plus the independent core category. | Usage overlaps but meaning/consumers are not reconciled. Direct deletion or remapping could break routes, templates or folder sync. |
| P3: empty archive/content structures | Several registered CPTs have no rows while Bricks templates and older LMS data remain. | Empty team_member, testimonial, competition and canonical relationship CPTs are not proof of unused schemas. Unregistered LMS rows need provenance before action. |

No P0/P1 incident, current external exposure or production traffic is established by this research. The site is documented as local staging; effective cloudflared ingress and actual traffic were not checked, and no reachability claim is made.

## 9. Redundancy Candidates

These are scoped investigation candidates only. No cleanup, content edit, migration or route change is approved.

| Candidate | Current evidence | Native replacement or preservation path | Prerequisite, rollback and owner gate |
|---|---|---|---|
| content_category vs content_topic | 13 legacy terms/0 edges; 55 canonical terms/1 edge; known consumers remain. Leefstyl terms are commercial, not semantic topics. | Keep the two native taxonomies distinct. Move only owner-approved semantic relationships into the Platform Core-owned topic model. | Complete term mapping, each consumer inventory, editorial review, dry run and reversible record; retain old assignments until all consumers pass. No taxonomy deletion. |
| category vs content classifications | One stored core category edge on Article 375, even though the core taxonomy is detached from Posts and the stored count is zero. | Keep the core relation intact for provenance review; use native ACF/Platform Core classifications only after source authority is clear. | Resolve Post 375 origin and record a separately approved cleanup sequence. Preserve shared attachment 154. |
| broadcast_* vs uitsending_* | Both generations are populated; assignment counts and slugs differ. | Keep current native terms and consumers; map to one vocabulary only if an approved route/editorial plan shows semantic equivalence. | Inventory term IDs, archive URLs, Bricks, HappyFiles and content references; preserve before-image and redirects before any consolidation. |
| person vs medewerker | One canonical Person and five legacy public profiles coexist. | Continue staged migration into canonical Person only for owner-approved identities; keep ACF/Bricks legacy consumers operational during dual run. | Per-profile provenance, field/media rights, relation/URL consumer reconciliation and separate decommission approval. No parallel third People model. |
| person_articles_*/person_courses_* reverse fields | Reverse data mirrors ACF simple relationships, including a stale Course reference. | Preserve ACF bidirectional relationships as the native source/read path for simple Article/Course links. | Repair only through a scoped, reviewed ACF operation after the source edge is checked; validate both directions and lifecycle behavior. Do not introduce an index table. |
| event_medewerkers / produk_* vs canonical participation/contribution | Legacy edges exist; canonical Event Participation/Product Contribution records are zero. | Keep existing fields and their consumers unchanged until approved role-aware migration. Use established ACF CPT/domain model for rich roles when migration is authorized. | Owner-approved mapping, full consumer/dependency audit, reversible migration and exact before/after checks. No automatic copy or deletion. |
| course/lesson/section residue vs Masteriyo | Unregistered legacy rows coexist with Masteriyo Course/Section/Lesson records. | Masteriyo remains current Course authority; do not treat the residues as duplicates without lineage. | Identify source plugin/export lineage, media/quiz/progress links and authorized migration/retention plan; preserve every unresolved record. |
| Account, Membership and course/shop Page variants | Several published Pages have overlapping account/membership/login slugs; plugin-generated routes may own some. | Prefer the owning native plugin pages and Bricks layouts once route ownership is verified. | Map menus, redirects, memberships, templates and external links; check access and rollback before unpublishing or renaming any page. |
| /content/, /kursusse/, /produkte/ route variants | Current paths do not fully match the canonical IA. | Use WordPress/plugin rewrite and Bricks archive/hub capabilities; avoid custom routing until native configuration is evaluated. | Approve destination and redirects; verify canonicals, search indexes and incoming links before any route change. |

## 10. Recommendations

1. **Finish source inventory before importing.** For VGN-24, obtain authorized source-side Article, author, image and license inventories across the three legacy properties. Keep source IDs and canonical IDs separate. Record provenance, rights, approval, canonical URL, media usage and intended lifecycle for every candidate.
2. **Treat VGN-32 as blocked until its gates pass.** Require source/target reconciliation, approved editorial topic and author decisions, safe backup, a reversible sample/dry run, resumability and post-import count/media/URL/relationship verification. Do not infer consent or source truth from current local content.
3. **Keep synthetic records fenced.** Maintain the explicit fixture markers and inventory for Article 1887, Course 1889, Sections 1890/1893 and Lessons 1891/1892/1894. Do not call them approved content or include them in production launch acceptance. Resolve the stale reverse Course 1896 reference through the existing governed QA lifecycle, not an ad hoc edit.
4. **Resolve Article 375's provenance before any retirement.** Preserve it and shared attachment 154 until the documented origin question is closed. Its placeholder classification is not deletion authority.
5. **Complete the simple ACF relationship mapping natively.** Confirm primary author/reviewer/instructor roles from approved source, then use existing ACF relationship fields and Bricks Query Loops. Keep reverse fields derived. Preserve Event Participation and Product Contribution for rich domain roles; do not flatten them or invent additional relationship infrastructure.
6. **Build the seven-root topic coverage through editorial review.** Reconcile eligible source records against the canonical seven roots, keep commercial Leefstyl separate, and distinguish Primary/Secondary role data from direct taxonomy edges. Do not backfill from titles or guessed content.
7. **Close media and digital-delivery gates.** VGN-25 should identify source URLs, checksums or stable asset identity, permitted use/license, creator credit, target media ID, embed/download behavior, entitlement and accessible fallback. Verify Woo/Masteriyo native delivery before adding a custom reader or download subsystem.
8. **Reconcile SEO ownership and route outputs.** Confirm why the declared Rank Math stack is absent from the active runtime. Restore/configure the approved SEO tool or owner-authorize another native route; map title, description, canonical, robots, social metadata, schema and sitemap coverage. Run supported route/browser checks later under the applicable authorization. Do not add a parallel custom SEO framework.
9. **Resolve destination architecture before redirect work.** Complete the /content/ index, /leer/ hub/archive choice, /winkel/ shop route, competition pluralization and legacy People policy. Verify internal links, external legacy URLs, status codes, canonical tags, redirects and access before changing route settings.
10. **Keep existing plugins as the system of record.** WordPress owns posts/pages/media; ACF owns ordinary fields/relationships/CPTs within the stated content_topic exception; Bricks owns presentation; WooCommerce, Masteriyo, Tickera, PMPro and Subscriptions own their respective product, learning, event, membership and billing data. Measure a documented native limitation before authorizing custom replacement.
11. **Do not create/update Plane work from this subtask.** The parent task owner can reconcile this report with the already existing VGN issues and decide whether follow-up work belongs there.

### Native capability review

| Capability | Existing provision | Meets current need? | Remaining limitation |
|---|---|---|---|
| WordPress Core | Posts, Pages, media, taxonomy terms, statuses, permalinks and search visibility. | Yes for storage and basic lifecycle. | Source rights/editorial approvals and route completion require governed content/IA decisions. |
| Bricks | Existing templates, dynamic tags, conditions, Query Loops and archive/hub rendering. | Yes for presentation if routes and data are approved. | Current rendering/accessibility/route behavior was not checked. |
| ACF Pro | CPTs, field groups, simple relationships, bidirectional relationships and local JSON. | Yes for ordinary content and Person roles. | ACF does not establish provenance, rights or editorial approval. |
| Platform Core | Owns content_topic and defined domain adapters. | Yes for the explicit taxonomy exception and current models. | The live assignment/provenance mismatch must be resolved; it should not replace Bricks querying. |
| WooCommerce / Subscriptions | Product/catalog/order/billing and variations. | Yes for commerce authority. | Payment, delivery, renewal and product visibility were not tested. |
| Masteriyo | Current Course, Section, Lesson and learner system. | Yes for current LMS storage. | Legacy LearnDash mapping, learner progress, media rights and enrollment acceptance remain open. |
| Tickera | Event, ticket and attendance domain. | Yes for event/ticket storage. | Ticket/event migrations, user/attendee fields and public event flows were not read or tested. |
| PMPro | Membership access and levels. | Yes as membership authority. | No access or checkout gate was verified here. |
| Rank Math | Declared project SEO provider. | No current runtime evidence. | Plugin file, active entry and post metadata are absent; owner must resolve provider/configuration. |
| Cloudflare | Infrastructure is named in the project stack. | Not assessed for content modeling. | No current DNS, CDN, storage or tunnel request was made. |
| Custom content subsystem | No measured query/storage limitation was supplied. | Not justified. | CUSTOM_DEVELOPMENT=NOT_AUTHORIZED by this evidence; prefer native fields/relationships and migration tooling already governed by the repo. |

## 11. Outstanding Verification

### Evidence and work ownership

| Item | Current status | Next evidence needed | Owner gate |
|---|---|---|---|
| VGN-24: Article/topic/media source inventory | Backlog; last update recorded Nov. 5. | Authorized source inventory and reconciled target map for IDs, URLs, authors, image rights and topic roles. | Source access and editorial authority are required; no source request/export was made here. |
| VGN-32: governed Article/topic/media migration | Blocked in current per-issue retrieval; the parent baseline issue roster records an older unstarted state. The issue was last updated Nov. 5. | Approved map, backup, dry run, resumable/reversible import and reconciliation. | Explicit execution authorization and migration gates. |
| VGN-25: digital books/audiovisual media | Backlog. | Source URLs/assets, rights/license, metadata, reader/download continuity and entitlement tests. | Media-owner approval; no asset request or download was made. |
| VGN-43: synthetic Article to Person to Course proof | Blocked in the per-issue retrieval; parent's earlier issue list records an older workflow snapshot. | Retire or retain fixtures under its owner gate, clear stale Course reverse link through governed lifecycle, complete remaining accessibility/LMS gates. | No fixture cleanup or real-content claim is implied. |
| VGN-16: Open Graph title issue | Todo; no later comments in inspected issue. | Current rendered shop/category metadata and cache evidence. | Route request not made because the task forbids HTTP. |
| VGN-17: sitemap/API issue | Todo; no later comments in inspected issue. | Current public endpoint, sitemap and robots/indexability evidence. | No request or scan made. |
| VGN-2: selected source Article/Person/Course pairing | Blocked in current per-issue retrieval. | Original source content/media, rights, Person author/instructor roles and governed Masteriyo migration. | Pair selection is not content approval or mutation authorization. |
| VGN-26: LearnDash to Masteriyo full course migration | Backlog. | Source Course/lesson/quiz inventory, learner identity/progress decisions, media, access and reconciliation plan. | No source site was inspected. |
| VGN-18: Course commerce | Blocked; Nov. 17 comment records checkout form/no payment methods and no submitted order. | Authorized payment and access test with isolated identities after source/route gates. | Historical evidence only; no commerce action here. |
| VGN-30: production cutover | Not started. | Complete migration, redirects, DNS, access, rollback, SEO and acceptance gates. | No deployment or production authorization is included. |

### Unverified in this report

- Current rendered Page, Article, Course, Person, Event, Product and Voelgoed Live routes, including status codes, redirects, canonical URLs and access controls.
- Exhaustive in-page link targets or whether old-site links resolve. No current route scan or HTTP request was made.
- SEO title, description, canonical, robots, schema, Open Graph, sitemap and public indexability output. blog_public=1 is a configuration fact, not proof of crawlability or actual traffic.
- Editorial review and legal permission for every source article, profile, course, event, product, image, video, audio or digital book.
- File checksums/content type, visual quality, alt text, alt-language, responsive crops, license, creator credit and original source. The only file-level result is local existence.
- Course enrollment, student access, quiz/completion/progress migration, ticket purchase, order, payment, membership entitlement, subscription renewal/refund and digital fulfillment.
- All public/private visibility behavior of QA-labeled pages, events, products and member content.
- Exact provenance and retirement decisions for unregistered LMS rows; exact origin/approval of nonfixture local Course/Event/Product content.
- Current Bricks conditions/query loops and runtime-only custom code paths. Prior report inventories are structural evidence, not a complete render or dependency test.
- Effective cloudflared ingress, external exposure or actual public traffic. No reachability conclusion is made.

## 12. Final Domain Assessment

**Domain assessment:** PARTIAL. Current local record inventory, taxonomy assignment, selected Person/Product/Course relationships, SEO key presence and attachment-path existence were checked. Source migration, content approval, rights, current route/render behavior and launch acceptance are incomplete or unknown.

**Task type:** RESEARCH / documentation handoff.

**Task / issue:** VGN-55 audit subtask: evaluate editorial records, classification, Person/Product/Course relationships, metadata, media, SEO, destinations and migration readiness without writes.

**Assessment role:** Research self-assessment.

**Acceptance criteria:** 9/10 covered; 1 partially covered.
1. Coordination brief and Parent Baseline read fully: verified.
2. Reports 01 to 06 and required current architecture/evidence read: verified.
3. Current local database identity/row count and in-scope status counts independently checked: verified.
4. Published public IDs/slugs and known fixture provenance inventoried; nonpublic IDs/slugs withheld: verified for the scoped public-domain types.
5. Topics/categories/duplicate classifications independently counted: verified.
6. Person/Course/Product/Event relationship edges and stale fixture reference reviewed: verified.
7. Editorial field, featured-media, SEO key and local file-presence coverage assessed: verified as metadata/presence checks only.
8. Legacy three-site migration, digital media, rights and approval gates mapped to live issues: verified as governance evidence, not source-data verification.
9. Native-first recommendations and lifecycle/launch limits stated: verified.
10. Exhaustive route/link, rendered SEO/schema and source-media rights check: partially covered because current HTTP, source-site requests, exports and private content inspection were prohibited.

**Implementation Score:** N/A: no implementation was authorized or performed.

**Task Execution Score:** 9/10. The requested inventory and evidence synthesis are complete within the permitted local read-only boundary. One point is withheld because link behavior, source-site records and rights cannot be independently settled without the prohibited evidence.

**Confidence:** MEDIUM. Database rows, selected relationships, metadata-key presence and local file existence are current direct observations. Source provenance, legal rights, route behavior and rendered SEO remain the largest uncertainties.

**Verification Status:** PARTIALLY_VERIFIED: verified local stored state and file-path presence; did not verify browser behavior, current routes, source-site reconciliation, rights or launch readiness.

**Evidence:** Local main SHA 454346d9e6a2c36864a4bbd944e52596f16e09c1; MySQL 8.0.35 over the registered LocalWP socket; 873-row wp_posts SELECT; read-only status/slug/taxonomy/relationship/metadata queries under /tmp/vgnova-evaluation-read.lock; 92/92 local attachment-path checks; the six parent evaluation reports; current architecture records; and read-only Plane issue records listed in §11. No tests, HTTP checks, database writes, source exports, migrations, file uploads, commits or Plane updates were performed.

**Source/runtime/behavior/business/launch state:**

| Gate | Result |
|---|---|
| SOURCE_MERGED | PARTIAL: the inspected source baseline is on main; the legacy editorial corpus is not source-reconciled in this work. |
| RUNTIME_APPLIED | PARTIAL: local staging records, native schema and fixtures exist; no authorized legacy content/media import was observed. |
| BEHAVIOR_VERIFIED | PARTIAL: stored state verified; current rendered routes, access and metadata output not checked. |
| BUSINESS_APPROVED | NO for the editorial/media migration corpus. Profile 753 identity and one source-pair selection have bounded approvals only; those do not approve content, rights, roles or writes. |
| LAUNCH_READY | NO: incomplete source inventory, rights, classifications, product downloads, SEO/schema, destinations, route/link checks and course/commercial acceptance remain. |

**Known gaps / highest-risk assumption / next action:** The highest-risk assumption is treating published local staging content or present media files as genuine, licensed and migration-approved Voelgoed content. The parent task owner should use VGN-24 to obtain the authorized source map, resolve content/rights/editorial approval, then keep VGN-32 and VGN-25 blocked until their documented migration and media gates pass. Preserve the synthetic markers and unresolved records until their own governed lifecycle decisions are made.
