# ACF relationships and field consumers

Audit snapshot: 2026-11-19  
Task: VGN-55, read-only ACF schema and relationship research  
Repository baseline: `454346d9e6a2c36864a4bbd944e52596f16e09c1`

## 1. Executive Summary

This report maps the ACF groups, options pages, fields, taxonomies, and relationship consumers observed in the VG Nova source and LocalWP staging runtime. It covers 28 ACF groups and 247 field definitions, including tabs and nested Perks repeater fields. The report also inventories 10 ACF-managed post types, 15 ACF taxonomies, four database-managed options pages, and the Member Perks plugin's PHP-registered settings page.

The core Article-to-Person and Course-to-Person fields use native ACF post-object fields with bidirectional targets. Runtime data has one valid Article expert edge and one valid Course additional-instructor edge. A reverse Course field also contains one reference without a current matching Course row.

The legacy Event-to-Medewerker and Product-to-Medewerker fields still contain edges. The canonical Event Participation and Product Contribution schemas exist, but their runtime post types have no records. The Product People mode field has no nonempty value. The topic tree has 55 terms and seven roots, while runtime has one direct topic edge and no stored Primary Topic values.

The source and runtime agree on the seven repo-managed ACF JSON groups at the file level. The ACF ability list classifies those groups as JSON, while the full-schema ability reports database source. The runtime ACF symlink points to generation `c450475971de722378a1658157a3a24838e467f7`. Earlier source documents still identify generation `5a3bc4fc89fa61ae8916aa460989f1e8912b473d`. Both discrepancies are documented below; neither was silently resolved.

Coverage is **PARTIAL**. Schema inspection is complete for the groups returned by the ACF abilities. Runtime populations are aggregate-only. No Bricks page was rendered, no relationship write was attempted, and no behavior test was run. This report does not establish business approval or launch readiness.

## 2. Inspection Scope and Evidence

### Scope

- Read the coordination brief and repository authorities relevant to ACF ownership, canonical relationships, runtime mutation control, and the current Person relationship activation.
- Read the source repository at `454346d9e6a2c36864a4bbd944e52596f16e09c1`.
- Read ACF group and options-page schemas through the approved Bricks MCP endpoint at `http://localhost:10039/wp-json/mcp/mcp-adapter-default-server`. The calls were `novamira/acf-list-fieldgroups`, `novamira/acf-get-fieldgroup-schema`, `novamira/acf-list-options-pages`, and `novamira/acf-get-options-page`. These returned schema and metadata, not stored field values.
- Inspect Bricks template configuration for 32 templates through read-only Bricks abilities. This was a configuration scan, not a browser render.
- Read aggregate runtime post, field-key, term, and taxonomy-edge counts. Population findings use counts only. The report omits stored field values, content titles and IDs, contact details, and customer information.
- Read the live Plane context for VGN-55. No Plane record was changed.

### Evidence labels

- `SOURCE_CONFIRMED`: present in source files at the inspected repository commit.
- `RUNTIME_CONFIRMED`: returned by read-only inspection of the LocalWP runtime.
- `BOTH_CONFIRMED`: the relevant source and runtime artifacts match.
- `HISTORICAL_ONLY`: an earlier document or snapshot records the fact, but it does not establish current runtime state.
- `INFERRED`: a conclusion follows from observed source and state, but was not rendered or executed in the runtime.
- `UNKNOWN`: the available evidence does not establish the fact.
- `CONFLICT`: two inspected sources report different provenance or state.

### Evidence and safety limits

The LocalWP site identity was confirmed as registered site `nAZNpFs99`, host `http://localhost:10039`, root `/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public`. The shell WP-CLI database follow-up for the broadcast taxonomy pair and the legacy `dorp` tag failed with “Error establishing a database connection.” The earlier successful aggregates remain usable; no counts for those additional fields were returned from the failed call.

The read-only MCP calls did not invoke ACF, Bricks, snippet, or database write abilities. WP-CLI aggregate reads used `--skip-plugins --skip-themes` where applicable and an advisory lock. Plugin bootstrap or MCP bootstrap side effects were not independently ruled out. No HTTP checks, browser rendering, transaction tests, or active security scans were performed. No tests were run.

The live VGN-55 issue is `1782fd6f-206f-432b-bf85-0ad97c0a43bb`, in Plane project `e0f4a753-a9d1-447d-974a-fa48ae8912b2`. Its latest reviewed comment describes the LocalWP audit as partial. This report did not update the issue.

## 3. Component Inventory

### ACF field groups

The table lists every returned group, its ID/key, source classification, active state, purpose, and located consumers. “No consumer located” means none was found in the inspected source search or 32 template configurations. It does not prove that no external consumer exists.

| Component | ID, key or path | State | Owner | Purpose | Consumers, readers or writers | Evidence |
|---|---|---|---|---|---|---|
| Kompetisie Inskrywing — Parent | ID 456; `group_kompetisie_entry_parent`; n/a | active; list source=db; schema source=db | ACF database; source file not found | parent competition reference | Kompetisie Entry editor; no reader located | ACF list/schema; n/a |
| Company & Brand | ID 466; `group_vg_company`; n/a | active; list source=db; schema source=db | ACF database; source file not found | company name, brand, contact and social fields | Company & Brand options page; no Bricks template consumer located | ACF list/schema; n/a |
| Newsletter & CTAs | ID 489; `group_vg_newsletter`; n/a | active; list source=db; schema source=db | ACF database; source file not found | newsletter defaults, form reference and placement overrides | Newsletter & CTAs options page; no Bricks template consumer located | ACF list/schema; n/a |
| Testimonial — Details | ID 577; `group_testimonial_details`; n/a | active; list source=db; schema source=db | ACF database; source file not found | testimonial quote, author, rating and display data | Draft Bricks template 1919 Event Landing Frames | ACF list/schema; n/a |
| Team Member — Profile | ID 588; `group_team_member_profile`; n/a | active; list source=db; schema source=db | ACF database; source file not found | team member profile, contact and display order | No consumer located in 32 inspected Bricks templates or source search | ACF list/schema; n/a |
| Content — Article | ID 625; `group_vg_content_article`; n/a | active; list source=db; schema source=db | ACF database; source file not found | article lede and read time | Runtime-only FluentSnippet 2 dynamic tags; published template 372 | ACF list/schema; n/a |
| Content Category — Sidebar Promo | ID 628; `group_vg_content_category_promo`; n/a | inactive; list source=db; schema source=db | ACF database; source file not found | content-category promo target and button labels; inactive group | No consumer located; runtime snippet's category-promo marker reads Featured Content Manager options instead | ACF list/schema; n/a |
| Featured Content Manager | ID 633; `group_vg_featured_content_manager`; n/a | active; list source=db; schema source=db | ACF database; source file not found | one featured product, course or event per configured category | Runtime-only FluentSnippet 2; published Bricks template consumer not located | ACF list/schema; n/a |
| Vennoot Besonderhede | ID 663; `group_vennoot_besonderhede`; n/a | active; list source=db; schema source=db | ACF database; source file not found | partner logo, description, URL and status | No consumer located in 32 inspected templates or source search | ACF list/schema; n/a |
| Kategorie Vennoot Mapping | ID 668; `group_kategorie_vennoot_mapping`; n/a | active; list source=db; schema source=db | ACF database; source file not found | map a content category to a partner area taxonomy | No consumer located in 32 inspected templates or source search | ACF list/schema; n/a |
| Partner Display Override | ID 670; `group_partner_display_override`; n/a | active; list source=db; schema source=db | ACF database; source file not found | manual partner and hide-flag override on posts/pages | No consumer located in 32 inspected templates or source search | ACF list/schema; n/a |
| Medewerker Besonderhede | ID 744; `group_medewerker_besonderhede`; n/a | active; list source=db; schema source=db | ACF database; source file not found | current medewerker title, short bio and profile status | Published Medewerker Single 772; source FluentSnippet 10 legacy query modes | ACF list/schema; n/a |
| Event Medewerkers | ID 748; `group_event_medewerkers`; n/a | active; list source=db; schema source=db | ACF database; source file not found | legacy event-to-medewerker relationship | Published Medewerker Single 772 and runtime-only FluentSnippet 9/10; legacy query modes | ACF list/schema; n/a |
| Medewerker Produkte | ID 750; `group_medewerker_produkte`; n/a | active; list source=db; schema source=db | ACF database; source file not found | legacy product-to-event and product-to-medewerker fields | Published Product Single 124 has canonical markers; legacy FluentSnippet 10 modes; runtime-only FluentSnippet 9 writer | ACF list/schema; n/a |
| Broadcast: Episode Details | ID 1070; `group_vg_broadcast_episode`; n/a | active; list source=db; schema source=db | ACF database; source file not found | episode state, schedule, URLs, hosts, guests and related content | Published Voelgoed Live Single 1127 | ACF list/schema; n/a |
| Editorial Classification | ID 1219; `group_vg_editorial_classification`; site-code/acf-json/group_vg_editorial_classification.json | active; list source=json; schema source=db | site-code/acf-json plus database mirror | editorial format and collection taxonomy fields | No consumer located in 32 inspected Bricks templates or source search | ACF list/schema; site-code/acf-json/group_vg_editorial_classification.json |
| Persoon Besonderhede | ID 1253; `group_vg_person_details`; site-code/acf-json/group_vg_person_details.json | active; list source=json; schema source=db | site-code/acf-json plus database mirror | person professional title, credentials, company, website and type | Published Person Single 1734; Person Related Content reverse fields | ACF list/schema; site-code/acf-json/group_vg_person_details.json |
| Event Participation Details | ID 1263; `group_vg_event_participation_details`; site-code/acf-json/group_vg_event_participation_details.json | active; list source=json; schema source=db | site-code/acf-json plus database mirror | canonical event-person role and display-order relationship | Platform Core EventParticipationService/Repository; published Event Single 774 | ACF list/schema; site-code/acf-json/group_vg_event_participation_details.json |
| Product Contribution Details | ID 1478; `group_vg_product_contribution_details`; site-code/acf-json/group_vg_product_contribution_details.json | active; list source=json; schema source=db | site-code/acf-json plus database mirror | canonical product-person role and display-order relationship | Platform Core ProductContributionService/Repository; Product Single 124 and Person Single 1734 | ACF list/schema; site-code/acf-json/group_vg_product_contribution_details.json |
| Person Related Content | ID 1706; `group_vg_person_related_content`; site-code/acf-json/group_vg_person_related_content.json | active; list source=json; schema source=db | site-code/acf-json plus database mirror | derived reverse relationships from articles and courses to people | Platform Core bidirectional ACF targets; hidden in Person editor; published Person Single 1734 | ACF list/schema; site-code/acf-json/group_vg_person_related_content.json |
| Article People | ID 1714; `group_vg_article_people`; site-code/acf-json/group_vg_article_people.json | active; list source=json; schema source=db | site-code/acf-json plus database mirror | article primary/additional authors, experts and reviewer | Published Article template 372; reverse targets on Person Single 1734 | ACF list/schema; site-code/acf-json/group_vg_article_people.json |
| Course People | ID 1719; `group_vg_course_people`; site-code/acf-json/group_vg_course_people.json | active; list source=json; schema source=db | site-code/acf-json plus database mirror | course lead/additional instructors and experts | Published Course Single 1733; reverse targets on Person Single 1734 | ACF list/schema; site-code/acf-json/group_vg_course_people.json |
| Event: Landing page | ID 1913; `group_vg_event_landing`; n/a | active; list source=db; schema source=db | ACF database; source file not found | event testimonials, gallery, video, programme and info image | Draft Bricks template 1919 Event Landing Frames | ACF list/schema; n/a |
| Home: Featured destination | ID 1928; `group_vg_homepage`; n/a | active; list source=db; schema source=db | ACF database; source file not found | single featured destination on page 1926 | Homepage page 1926; no active Bricks template consumer located | ACF list/schema; n/a |
| Voelgoed Perk Settings | ID n/a; `group_vgmp_options`; voelgoed-member-perks/acf-json/group_vgmp_options.json | active; list source=json; schema source=json | voelgoed-member-perks/acf-json | Member Perks plugin settings, programme map and feature flags | Member Perks SettingsRepository; PHP settings page source | ACF list/schema; voelgoed-member-perks/acf-json/group_vgmp_options.json |
| Member Perk Details | ID n/a; `group_vgmp_perk_details`; voelgoed-member-perks/acf-json/group_vgmp_perk_details.json | active; list source=json; schema source=json | voelgoed-member-perks/acf-json | Member Perk schedule, eligibility, benefit, target, claims and card fields | Member Perks services, admin, display, hub and integration readers | ACF list/schema; voelgoed-member-perks/acf-json/group_vgmp_perk_details.json |
| Content Topic Roles | ID n/a; `group_vg_content_topic_roles`; n/a | active; list source=php; schema source=php | Platform Core PHP | optional Primary Topic selector | Platform Core ContentTopicRoles; six supported post types; taxonomy edges remain authoritative | ACF list/schema; n/a |
| Product People | ID n/a; `group_vg_product_people`; n/a | active; list source=php; schema source=php | Platform Core PHP | Product People mode selector | Platform Core ProductPeopleResolver/Repository and Product Single 124 | ACF list/schema; n/a |

### Options pages

The four database-managed pages came from the ACF options-page list and full-page reads. The `vgmp-settings` page is registered in `voelgoed-member-perks/includes/Admin/SettingsPage.php:14-27`; it did not appear in the ACF list, and the full-page lookup did not resolve it. Its field-group JSON uses the `vgmp-settings` location. The plugin settings repository reads its values through the ACF-backed configuration provider, including option names referenced in `voelgoed-member-perks/includes/Config/SettingsRepository.php:29-108`.

| Options page ID | Key | Menu slug | Title | Post ID used by ACF | Parent | Capability | Source/state |
|---:|---|---|---|---|---|---|---|
| 571 | `options_page_vg_site_settings` | `vg-site-settings` | Voelgoed Site Settings | `vg_site_settings` | top level | `manage_options` | db, active |
| 572 | `options_page_vg_company` | `vg-company` | Company & Brand | `vg_company` | vg-site-settings | `manage_options` | db, active |
| 573 | `options_page_vg_newsletter` | `vg-newsletter` | Newsletter & CTAs | `vg_newsletter` | vg-site-settings | `manage_options` | db, active |
| 632 | `options_page_featured_content_manager` | `featured-content-manager` | Featured Content Manager | `featured-content-manager` | vg-site-settings | `manage_options` | db, active |
| n/a | PHP registration | `vgmp-settings` | Voelgoed Perk Settings | `options` (default option store) | plugin root | `manage_vg_perks` | PHP source confirmed; absent from ACF options-page list |

### ACF-managed post types

| Post type | Runtime exposure | ACF location or purpose | Current aggregate | Evidence |
|---|---|---|---|---|
| `person` | Public, queryable, REST-visible, archive `/mense/` | Canonical Person model | 1 published | ACF post-type list/getter and runtime post count |
| `event_participation` | Nonpublic and nonqueryable; admin UI and REST enabled | Event-person relationship record | 0 posts | ACF schema and runtime post count |
| `product_contribution` | Nonpublic and nonqueryable; admin UI and REST enabled | Product-person relationship record | 0 posts | ACF schema and runtime post count |
| `kompetisie` | Public, hierarchical, archive `/kompetisie/` | Competition parent | No rows observed | ACF post-type list and aggregate count |
| `kompetisie_entry` | Public, no archive; parent competition field | Competition entry | No rows observed | ACF post-type list and aggregate count |
| `medewerker` | Public, archive `/medewerkers/` | Current profile implementation | 5 published | ACF post-type list and aggregate count |
| `team_member` | Public, archive `/span/` | Internal team profiles | No rows observed | ACF post-type list and aggregate count |
| `testimonial` | Public, archive `/testimonials/` | Testimonial content | No rows observed | ACF post-type list and aggregate count |
| `vennoot` | Public, no public archive | Partner records | 7 published | ACF post-type list and aggregate count |
| `uitsending` | Public, archive `/voelgoed-live/` | Voelgoed Live episodes | 5 published | ACF post-type list and aggregate count |

The ACF post-type list labels `person`, `event_participation`, and `product_contribution` as JSON source. The full post-type getter labels those same definitions as DB source. This is a `CONFLICT` in ability metadata. The source files under `site-code/acf-json` define the Person and relationship CPTs, and the runtime source checkout contains those files.

`tc_events` is owned by Tickera, `mto-course` by Masteriyo, and `product` by WooCommerce. They are not counted as ACF-registered post types.

### ACF-managed taxonomies and adjacent taxonomies

| Taxonomy | Source and exposure | Attached content | Runtime terms / edges | Evidence |
|---|---|---|---:|---|
| `broadcast_series` | ACF DB; public, flat | `uitsending` | Unknown | ACF taxonomy schema; follow-up aggregate query failed |
| `broadcast_type` | ACF DB; public, flat | `uitsending` | Unknown | ACF taxonomy schema; follow-up aggregate query failed |
| `team_department` | ACF DB; public, hierarchical | `team_member` | Unknown | ACF taxonomy schema |
| `kompetisie_type` | ACF DB; public, flat | `kompetisie` | Unknown | ACF taxonomy schema |
| `medewerker_groep` | ACF DB; public, hierarchical | `medewerker` | 5 terms, 5 object edges | Runtime taxonomy aggregate |
| `medewerker_rol` | ACF DB; public, flat | `medewerker` | 9 terms, 5 object edges | Runtime taxonomy aggregate |
| `vennoot_kategorie` | ACF DB; public, hierarchical | `vennoot` | 7 terms, 7 object edges | Runtime taxonomy aggregate |
| `content_category` | ACF DB; public, hierarchical; legacy | `post`, `product`, `tc_events`, `mto-course`, `medewerker`, `uitsending`, `kompetisie`, `kompetisie_entry`, `testimonial`, `team_member` | 13 terms, 8 roots, 0 object edges | Runtime taxonomy aggregate |
| `uitsending_type` | ACF DB; public, flat | `uitsending` | Unknown | ACF taxonomy schema; follow-up aggregate query failed |
| `uitsending_series` | ACF DB; public, flat | `uitsending` | Unknown | ACF taxonomy schema; follow-up aggregate query failed |
| `event_role` | Repo JSON; nonpublic, nonqueryable, REST-visible | `event_participation` | 4 terms, 0 assignments | Runtime taxonomy aggregate |
| `person_type` | Repo JSON; nonpublic, nonqueryable, REST-visible | `person` | 2 terms, 2 assignments | Runtime taxonomy aggregate |
| `product_role` | Repo JSON; nonpublic, nonqueryable, REST-visible | `product_contribution` | 4 terms, 0 assignments | Runtime taxonomy aggregate |
| `editorial_format` | Repo JSON; nonpublic, REST disabled | Posts | 3 terms, 0 assignments | Runtime taxonomy aggregate |
| `editorial_collection` | Repo JSON; nonpublic, REST disabled | Posts | 0 terms | Runtime taxonomy aggregate |

Two further taxonomies are not ACF-managed. Platform Core registers public hierarchical `content_topic` and attaches it to posts, courses, products, events, current medarbeiders, broadcasts, competitions, and Person. It has 55 terms, seven roots, and one direct object edge. Tickera registers `event_category`; it has eight terms and 13 object edges. The two taxonomies have separate owners and separate jobs.

## 4. Current Architecture and Configuration

### ACF ownership

The inspected inventory has 28 groups:

- 17 groups classified as database source by the ACF group list.
- 7 repo-owned JSON groups under `site-code/acf-json`, each also present in the runtime database.
- 2 plugin JSON groups under `voelgoed-member-perks/acf-json`.
- 2 Platform Core PHP groups.

The 247 field definitions comprise 134 fields in the 24 database-backed groups, 83 top-level Perks JSON fields, 28 nested Perks repeater fields, and 2 PHP-registered fields. The counts include tab fields. The field tables below include IDs where ACF exposes a database field ID, keys, names, labels, types, required flags, schema defaults, locations, conditional logic, return formats, relationship targets, and available bidirectional settings. For JSON-only and PHP-local fields without database child IDs, the ID is shown as `n/a`. “blank” means an explicit empty schema default; “n/a” means the schema has no default property. These are schema defaults, not current record values.

The seven repo JSON groups are Article People, Course People, Editorial Classification, Event Participation Details, Person Details, Person Related Content, and Product Contribution Details. The two plugin JSON groups are Voelgoed Perk Settings and Member Perk Details. The PHP groups are Content Topic Roles and Product People.

### Field group inventory

| Group ID | Group key | Title | Source evidence | Active | Location | Top-level fields |
|---:|---|---|---|---|---|---:|
| 456 | `group_kompetisie_entry_parent` | Kompetisie Inskrywing — Parent | list=db; schema=db | active | `post_type==kompetisie_entry` | 1 |
| 466 | `group_vg_company` | Company & Brand | list=db; schema=db | active | `options_page==vg-company` | 22 |
| 489 | `group_vg_newsletter` | Newsletter & CTAs | list=db; schema=db | active | `options_page==vg-newsletter` | 18 |
| 577 | `group_testimonial_details` | Testimonial — Details | list=db; schema=db | active | `post_type==testimonial` | 10 |
| 588 | `group_team_member_profile` | Team Member — Profile | list=db; schema=db | active | `post_type==team_member` | 12 |
| 625 | `group_vg_content_article` | Content — Article | list=db; schema=db | active | `post_type==post` | 2 |
| 628 | `group_vg_content_category_promo` | Content Category — Sidebar Promo | list=db; schema=db | inactive | `taxonomy==content_category` | 3 |
| 633 | `group_vg_featured_content_manager` | Featured Content Manager | list=db; schema=db | active | `options_page==featured-content-manager` | 8 |
| 663 | `group_vennoot_besonderhede` | Vennoot Besonderhede | list=db; schema=db | active | `post_type==vennoot` | 4 |
| 668 | `group_kategorie_vennoot_mapping` | Kategorie Vennoot Mapping | list=db; schema=db | active | `taxonomy==content_category` | 1 |
| 670 | `group_partner_display_override` | Partner Display Override | list=db; schema=db | active | `post_type==post OR post_type==page` | 2 |
| 744 | `group_medewerker_besonderhede` | Medewerker Besonderhede | list=db; schema=db | active | `post_type==medewerker` | 3 |
| 748 | `group_event_medewerkers` | Event Medewerkers | list=db; schema=db | active | `post_type==tc_events` | 1 |
| 750 | `group_medewerker_produkte` | Medewerker Produkte | list=db; schema=db | active | `post_type==product` | 2 |
| 1070 | `group_vg_broadcast_episode` | Broadcast: Episode Details | list=db; schema=db | active | `post_type==uitsending` | 10 |
| 1219 | `group_vg_editorial_classification` | Editorial Classification | list=json; schema=db | active | `post_type==post` | 2 |
| 1253 | `group_vg_person_details` | Persoon Besonderhede | list=json; schema=db | active | `post_type==person` | 5 |
| 1263 | `group_vg_event_participation_details` | Event Participation Details | list=json; schema=db | active | `post_type==event_participation` | 4 |
| 1478 | `group_vg_product_contribution_details` | Product Contribution Details | list=json; schema=db | active | `post_type==product_contribution` | 4 |
| 1706 | `group_vg_person_related_content` | Person Related Content | list=json; schema=db | active | `post_type==person` | 7 |
| 1714 | `group_vg_article_people` | Article People | list=json; schema=db | active | `post_type==post` | 4 |
| 1719 | `group_vg_course_people` | Course People | list=json; schema=db | active | `post_type==mto-course` | 3 |
| 1913 | `group_vg_event_landing` | Event: Landing page | list=db; schema=db | active | `post_type==tc_events` | 5 |
| 1928 | `group_vg_homepage` | Home: Featured destination | list=db; schema=db | active | `page==1926` | 1 |
| n/a | `group_vgmp_options` | Voelgoed Perk Settings | list=json; schema=json | active | `options_page==vgmp-settings` | 30 |
| n/a | `group_vgmp_perk_details` | Member Perk Details | list=json; schema=json | active | `post_type==vg_perk` | 53 |
| n/a | `group_vg_content_topic_roles` | Content Topic Roles | list=php; schema=php | active | `post_type==post OR post_type==mto-course OR post_type==product OR post_type==tc_events OR post_type==uitsending OR post_type==kompetisie` | 1 |
| n/a | `group_vg_product_people` | Product People | list=php; schema=php | active | `post_type==product` | 1 |

### Database and repo JSON field schemas

### Kompetisie Inskrywing — Parent, `group_kompetisie_entry_parent`, group ID 456. List source=db; schema source=db; active=yes; location `post_type==kompetisie_entry`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 457 | field_kompetisie_entry_parent | parent_kompetisie | Kompetisie | post_object | 1 / n/a | none | return=object; post_type=kompetisie; multiple=0; allow_null=0; bidirectional_target=[] |

### Company & Brand, `group_vg_company`, group ID 466. List source=db; schema source=db; active=yes; location `options_page==vg-company`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 467 | field_vg_company_tab_brand | blank | Brand | tab | 0 / n/a | none | n/a |
| 468 | field_vg_company_name | company_name | Site: Company / Site Name | text | 0 / blank | none | n/a |
| 469 | field_vg_company_tagline | company_tagline | Site: Tagline | text | 0 / blank | none | n/a |
| 470 | field_vg_company_legal_name | company_legal_name | Site: Legal Entity Name | text | 0 / blank | none | n/a |
| 471 | field_vg_company_tab_logos | blank | Logos | tab | 0 / n/a | none | n/a |
| 472 | field_vg_logo_light | logo_light | Site: Logo (Light Background) | image | 0 / n/a | none | return=array; min_width=0; max_width=0 |
| 473 | field_vg_logo_dark | logo_dark | Site: Logo (Dark Background) | image | 0 / n/a | none | return=array; min_width=0; max_width=0 |
| 474 | field_vg_logo_icon | logo_icon | Site: Logo Mark / Icon | image | 0 / n/a | none | return=array; min_width=0; max_width=0 |
| 475 | field_vg_company_tab_contact | blank | Contact | tab | 0 / n/a | none | n/a |
| 476 | field_vg_contact_email | contact_email | Site: Email | email | 0 / blank | none | n/a |
| 477 | field_vg_contact_phone | contact_phone | Site: Phone | text | 0 / blank | none | n/a |
| 478 | field_vg_contact_whatsapp | contact_whatsapp | Site: WhatsApp | text | 0 / blank | none | n/a |
| 479 | field_vg_contact_address | contact_address | Site: Physical Address | textarea | 0 / blank | none | n/a |
| 480 | field_vg_contact_hours | contact_hours | Site: Office Hours | textarea | 0 / blank | none | n/a |
| 481 | field_vg_company_tab_social | blank | Social | tab | 0 / n/a | none | n/a |
| 482 | field_vg_social_facebook | social_facebook | Site: Facebook URL | url | 0 / blank | none | n/a |
| 483 | field_vg_social_instagram | social_instagram | Site: Instagram URL | url | 0 / blank | none | n/a |
| 484 | field_vg_social_youtube | social_youtube | Site: YouTube URL | url | 0 / blank | none | n/a |
| 485 | field_vg_social_tiktok | social_tiktok | Site: TikTok URL | url | 0 / blank | none | n/a |
| 486 | field_vg_social_linkedin | social_linkedin | Site: LinkedIn URL | url | 0 / blank | none | n/a |
| 487 | field_vg_social_pinterest | social_pinterest | Site: Pinterest URL | url | 0 / blank | none | n/a |
| 488 | field_vg_social_x | social_x | Site: X (Twitter) URL | url | 0 / blank | none | n/a |

### Newsletter & CTAs, `group_vg_newsletter`, group ID 489. List source=db; schema source=db; active=yes; location `options_page==vg-newsletter`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 490 | field_vg_newsletter_tab_global | blank | Global Defaults | tab | 0 / n/a | none | n/a |
| 491 | field_vg_newsletter_heading | newsletter_heading | Default Heading | text | 0 / "Bly op hoogte" | none | n/a |
| 492 | field_vg_newsletter_subheading | newsletter_subheading | Default Subheading | textarea | 0 / blank | none | n/a |
| 493 | field_vg_newsletter_cta_button | newsletter_cta_button_text | Button Label | text | 0 / "Teken in" | none | n/a |
| 494 | field_vg_newsletter_email_placeholder | newsletter_email_placeholder | Email Placeholder | text | 0 / "Jou e-posadres" | none | n/a |
| 495 | field_vg_newsletter_privacy_text | newsletter_privacy_text | Privacy / Consent Text | textarea | 0 / blank | none | n/a |
| 496 | field_vg_newsletter_tab_form | blank | Form | tab | 0 / n/a | none | n/a |
| 497 | field_vg_newsletter_form_shortcode | newsletter_form_shortcode | Fluent Forms Shortcode | text | 0 / blank | none | n/a |
| 498 | field_vg_newsletter_form_id | newsletter_form_id | Fluent Form ID | number | 0 / blank | none | n/a |
| 499 | field_vg_newsletter_success_message | newsletter_success_message | Success Message | text | 0 / "Dankie! Jy is suksesvol ingeteken." | none | n/a |
| 500 | field_vg_newsletter_error_message | newsletter_error_message | Error Message | text | 0 / "Iets het verkeerd geloop. Probeer asseblief weer." | none | n/a |
| 501 | field_vg_newsletter_tab_placements | blank | Placement Overrides | tab | 0 / n/a | none | n/a |
| 502 | field_vg_newsletter_footer_heading | newsletter_footer_heading | Footer Heading | text | 0 / blank | none | n/a |
| 503 | field_vg_newsletter_footer_subheading | newsletter_footer_subheading | Footer Subheading | textarea | 0 / blank | none | n/a |
| 504 | field_vg_newsletter_article_heading | newsletter_article_heading | Article Inline Heading | text | 0 / blank | none | n/a |
| 505 | field_vg_newsletter_article_subheading | newsletter_article_subheading | Article Inline Subheading | textarea | 0 / blank | none | n/a |
| 506 | field_vg_newsletter_home_heading | newsletter_home_heading | Homepage Block Heading | text | 0 / blank | none | n/a |
| 507 | field_vg_newsletter_home_subheading | newsletter_home_subheading | Homepage Block Subheading | textarea | 0 / blank | none | n/a |

### Testimonial — Details, `group_testimonial_details`, group ID 577. List source=db; schema source=db; active=yes; location `post_type==testimonial`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 578 | field_testimonial_tab_content | blank | Testimonial | tab | 0 / n/a | none | n/a |
| 579 | field_testimonial_quote | testimonial_quote | Quote | textarea | 1 / blank | none | n/a |
| 580 | field_testimonial_author_name | testimonial_author_name | Author Name | text | 1 / blank | none | n/a |
| 581 | field_testimonial_author_role | testimonial_author_role | Author Role / Title | text | 0 / blank | none | n/a |
| 582 | field_testimonial_author_company | testimonial_author_company | Company / Organisation | text | 0 / blank | none | n/a |
| 583 | field_testimonial_tab_display | blank | Display | tab | 0 / n/a | none | n/a |
| 584 | field_testimonial_rating | testimonial_rating | Star Rating | number | 0 / blank | none | min=1; max=5 |
| 585 | field_testimonial_source_type | testimonial_source_type | Source Type | select | 0 / "written" | none | return=value; multiple=0; allow_null=0; choices=written=Written; video=Video; social=Social media; event=Event / Live |
| 586 | field_testimonial_video_url | testimonial_video_url | Video URL | url | 0 / blank | field_testimonial_source_type == video | n/a |
| 587 | field_testimonial_featured | testimonial_featured | Featured Testimonial | true_false | 0 / 0 | none | n/a |

### Team Member — Profile, `group_team_member_profile`, group ID 588. List source=db; schema source=db; active=yes; location `post_type==team_member`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 589 | field_team_tab_profile | blank | Profile | tab | 0 / n/a | none | n/a |
| 590 | field_team_job_title | team_job_title | Job Title | text | 1 / blank | none | n/a |
| 591 | field_team_short_bio | team_short_bio | Short Bio | textarea | 0 / blank | none | n/a |
| 592 | field_team_full_bio | team_full_bio | Full Bio | wysiwyg | 0 / blank | none | n/a |
| 593 | field_team_photo | team_photo | Profile Photo | image | 0 / n/a | none | return=array; min_width=0; max_width=0 |
| 594 | field_team_tab_contact | blank | Contact & Social | tab | 0 / n/a | none | n/a |
| 595 | field_team_email | team_email | Email | email | 0 / blank | none | n/a |
| 596 | field_team_phone | team_phone | Phone | text | 0 / blank | none | n/a |
| 597 | field_team_linkedin | team_linkedin | LinkedIn URL | url | 0 / blank | none | n/a |
| 598 | field_team_tab_display | blank | Display | tab | 0 / n/a | none | n/a |
| 599 | field_team_display_order | team_display_order | Display Order | number | 0 / 0 | none | n/a |
| 600 | field_team_featured | team_featured | Featured Team Member | true_false | 0 / 0 | none | n/a |

### Content — Article, `group_vg_content_article`, group ID 625. List source=db; schema source=db; active=yes; location `post_type==post`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 626 | field_article_lede | article_lede | Article Lede | textarea | 0 / blank | none | n/a |
| 627 | field_article_read_time | article_read_time | Read Time (minutes) | number | 0 / blank | none | min=1 |

### Content Category — Sidebar Promo, `group_vg_content_category_promo`, group ID 628. List source=db; schema source=db; active=no; location `taxonomy==content_category`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 629 | field_category_promo_item | category_promo_item | Featured Promo Item | post_object | 0 / n/a | none | return=id; post_type=product,tc_events,mto-course; multiple=0; allow_null=1; bidirectional_target=[] |
| 630 | field_category_promo_label | category_promo_label | Promo Eyebrow Label | text | 0 / blank | none | n/a |
| 631 | field_category_promo_cta_text | category_promo_cta_text | Promo CTA Button Text | text | 0 / blank | none | n/a |

### Featured Content Manager, `group_vg_featured_content_manager`, group ID 633. List source=db; schema source=db; active=yes; location `options_page==featured-content-manager`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 634 | field_vg_featured_nuwe_jy | featured_nuwe_jy | Featured Nuwe Jy | post_object | 0 / n/a | none | return=id; post_type=product,mto-course,tc_events; multiple=0; allow_null=1; bidirectional=0; bidirectional_target=[] |
| 635 | field_vg_featured_geloof | featured_geloof | Featured Geloof | post_object | 0 / n/a | none | return=id; post_type=product,mto-course,tc_events; multiple=0; allow_null=1; bidirectional=0; bidirectional_target=[] |
| 636 | field_vg_featured_self | featured_self | Featured Self | post_object | 0 / n/a | none | return=id; post_type=product,mto-course,tc_events; multiple=0; allow_null=1; bidirectional=0; bidirectional_target=[] |
| 637 | field_vg_featured_werk_geld | featured_werk_geld | Featured Werk Geld | post_object | 0 / n/a | none | return=id; post_type=product,mto-course,tc_events; multiple=0; allow_null=1; bidirectional=0; bidirectional_target=[] |
| 638 | field_vg_featured_ouerskap | featured_ouerskap | Featured Ouerskap | post_object | 0 / n/a | none | return=id; post_type=product,mto-course,tc_events; multiple=0; allow_null=1; bidirectional=0; bidirectional_target=[] |
| 639 | field_vg_featured_huwelik | featured_huwelik | Featured Huwelik | post_object | 0 / n/a | none | return=id; post_type=product,mto-course,tc_events; multiple=0; allow_null=1; bidirectional=0; bidirectional_target=[] |
| 640 | field_vg_featured_mensstories | featured_mensstories | Featured Mensstories | post_object | 0 / n/a | none | return=id; post_type=product,mto-course,tc_events; multiple=0; allow_null=1; bidirectional=0; bidirectional_target=[] |
| 785 | field_vg_featured_leefstyl | featured_leefstyl | Featured Leefstyl | post_object | 0 / n/a | none | return=id; post_type=product,mto-course,tc_events; multiple=0; allow_null=1; bidirectional=0; bidirectional_target=[] |

### Vennoot Besonderhede, `group_vennoot_besonderhede`, group ID 663. List source=db; schema source=db; active=yes; location `post_type==vennoot`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 664 | field_vennoot_logo | vennoot_logo | Vennoot Logo | image | 0 / n/a | none | return=id; min_width=0; max_width=0 |
| 665 | field_vennoot_short_description | vennoot_short_description | Short Description | textarea | 0 / blank | none | n/a |
| 666 | field_vennoot_url | vennoot_url | Vennoot URL | url | 0 / blank | none | n/a |
| 667 | field_vennoot_status | vennoot_status | Vennoot Status | select | 0 / "active" | none | return=value; multiple=0; allow_null=0; choices=active=Active; paused=Paused; expired=Expired |

### Kategorie Vennoot Mapping, `group_kategorie_vennoot_mapping`, group ID 668. List source=db; schema source=db; active=yes; location `taxonomy==content_category`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 669 | field_category_linked_vennoot_kategorie | linked_vennoot_kategorie | Linked Vennoot Area | taxonomy | 0 / n/a | none | return=id; taxonomy=vennoot_kategorie; field_type=select; multiple=0; allow_null=1; save_terms=0; load_terms=0; add_term=0; bidirectional_target=[] |

### Partner Display Override, `group_partner_display_override`, group ID 670. List source=db; schema source=db; active=yes; location `post_type==post OR post_type==page`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 672 | field_manual_vennoot | manual_vennoot | Manual Vennoot | post_object | 0 / n/a | none | return=id; post_type=vennoot; multiple=0; allow_null=1; bidirectional_target=[] |
| 673 | field_hide_vennoot_blok | hide_vennoot_blok | Hide Vennoot Blok | true_false | 0 / 0 | none | n/a |

### Medewerker Besonderhede, `group_medewerker_besonderhede`, group ID 744. List source=db; schema source=db; active=yes; location `post_type==medewerker`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 745 | field_medewerker_titel | medewerker_titel | Medewerker: Titel | text | 0 / blank | none | n/a |
| 746 | field_medewerker_kort_bio | medewerker_kort_bio | Medewerker: Kort bio | textarea | 0 / blank | none | n/a |
| 747 | field_medewerker_profiel_status | medewerker_profiel_status | Medewerker: Profielstatus | select | 0 / "public" | none | return=value; multiple=0; allow_null=0; choices=public=Public; private=Private; noindex=Noindex; archived=Archived |

### Event Medewerkers, `group_event_medewerkers`, group ID 748. List source=db; schema source=db; active=yes; location `post_type==tc_events`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 749 | field_event_medewerkers | event_medewerkers | Event Medewerkers | relationship | 0 / n/a | none | return=id; post_type=medewerker; min=0; max=0; bidirectional=0; bidirectional_target=[]; filters=search |

### Medewerker Produkte, `group_medewerker_produkte`, group ID 750. List source=db; schema source=db; active=yes; location `post_type==product`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 751 | field_produk_bron_event | produk_bron_event | Bron Event | post_object | 0 / n/a | none | return=id; post_type=tc_events; multiple=0; allow_null=0; bidirectional_target=[] |
| 752 | field_produk_medewerkers | produk_medewerkers | Produk Medewerkers | relationship | 0 / n/a | none | return=id; post_type=medewerker; min=0; max=0; bidirectional=0; bidirectional_target=[]; filters=search |

### Broadcast: Episode Details, `group_vg_broadcast_episode`, group ID 1070. List source=db; schema source=db; active=yes; location `post_type==uitsending`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 1071 | field_vg_broadcast_state | broadcast_state | Broadcast: State | select | 1 / "replay" | none | return=value; multiple=0; allow_null=0; choices=scheduled=Scheduled; live=Live; replay=Replay; archived=Archived |
| 1072 | field_vg_broadcast_start_at | broadcast_start_at | Broadcast: Start | date_time_picker | 0 / n/a | none | return=Y-m-d H:i:s |
| 1073 | field_vg_broadcast_end_at | broadcast_end_at | Broadcast: End | date_time_picker | 0 / n/a | none | return=Y-m-d H:i:s |
| 1074 | field_vg_broadcast_replay_url | broadcast_replay_url | Broadcast: YouTube Replay URL | url | 0 / blank | field_vg_broadcast_state == replay OR field_vg_broadcast_state == archived | n/a |
| 1075 | field_vg_broadcast_live_url | broadcast_live_url | Broadcast: Live URL | url | 0 / blank | field_vg_broadcast_state == live | n/a |
| 1076 | field_vg_broadcast_live_provider | broadcast_live_provider | Broadcast: Live Provider | select | 0 / 0 | field_vg_broadcast_state == live | return=value; multiple=0; allow_null=1; choices=restream=ReStream; youtube_live=YouTube Live |
| 1077 | field_vg_broadcast_episode_number | broadcast_episode_number | Broadcast: Episode Number | number | 0 / blank | none | min=1 |
| 1078 | field_vg_broadcast_hosts | broadcast_hosts | Broadcast: Hosts | relationship | 0 / n/a | none | return=id; post_type=medewerker; min=0; max=0; bidirectional=0; bidirectional_target=[]; filters=search |
| 1079 | field_vg_broadcast_guests | broadcast_guests | Broadcast: Guests | relationship | 0 / n/a | none | return=id; post_type=medewerker; min=0; max=0; bidirectional=0; bidirectional_target=[]; filters=search |
| 1080 | field_vg_broadcast_related_content | broadcast_related_content | Broadcast: Related Content | relationship | 0 / n/a | none | return=id; post_type=post,mto-course,product,tc_events; min=0; max=0; bidirectional=0; bidirectional_target=[]; filters=search |

### Editorial Classification, `group_vg_editorial_classification`, group ID 1219. List source=json; schema source=db; active=yes; location `post_type==post`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 1220 | field_vg_editorial_format | editorial_format | Formaat | taxonomy | 0 / [] | none | return=id; taxonomy=editorial_format; field_type=radio; multiple=0; allow_null=1; save_terms=1; load_terms=1; add_term=0; bidirectional_target=[] |
| 1221 | field_vg_editorial_collection | editorial_collection | Versameling | taxonomy | 0 / [] | none | return=id; taxonomy=editorial_collection; field_type=multi_select; multiple=1; allow_null=1; save_terms=1; load_terms=1; add_term=0; bidirectional_target=[] |

### Persoon Besonderhede, `group_vg_person_details`, group ID 1253. List source=json; schema source=db; active=yes; location `post_type==person`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 1254 | field_vg_person_professional_title | person_professional_title | Persoon: Professionele titel | text | 0 / blank | none | n/a |
| 1255 | field_vg_person_credentials | person_credentials | Persoon: Kwalifikasies | textarea | 0 / blank | none | n/a |
| 1256 | field_vg_person_company | person_company | Persoon: Maatskappy | text | 0 / blank | none | n/a |
| 1257 | field_vg_person_website | person_website | Persoon: Webwerf | url | 0 / blank | none | n/a |
| 1258 | field_vg_person_type | person_type | Persoon: Tipe | taxonomy | 0 / [] | none | return=id; taxonomy=person_type; field_type=multi_select; multiple=1; allow_null=1; save_terms=1; load_terms=1; add_term=0; bidirectional_target=[] |

### Event Participation Details, `group_vg_event_participation_details`, group ID 1263. List source=json; schema source=db; active=yes; location `post_type==event_participation`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 1264 | field_vg_event_participation_event | event_participation_event | Event Participation: Event | post_object | 1 / n/a | none | return=id; post_type=tc_events; multiple=0; allow_null=0; bidirectional_target=[] |
| 1265 | field_vg_event_participation_person | event_participation_person | Event Participation: Person | post_object | 1 / n/a | none | return=id; post_type=person; multiple=0; allow_null=0; bidirectional_target=[] |
| 1266 | field_vg_event_participation_roles | event_participation_roles | Event Participation: Roles | taxonomy | 0 / [] | none | return=id; taxonomy=event_role; field_type=multi_select; multiple=1; allow_null=0; save_terms=1; load_terms=1; add_term=0; bidirectional_target=[] |
| 1267 | field_vg_event_participation_display_order | event_participation_display_order | Event Participation: Display Order | number | 0 / 0 | none | min=0 |

### Product Contribution Details, `group_vg_product_contribution_details`, group ID 1478. List source=json; schema source=db; active=yes; location `post_type==product_contribution`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 1479 | field_vg_product_contribution_product | product_contribution_product | Product Contribution: Product | post_object | 0 / n/a | none | return=id; post_type=product; multiple=0; allow_null=0; bidirectional_target=[] |
| 1480 | field_vg_product_contribution_person | product_contribution_person | Product Contribution: Person | post_object | 0 / n/a | none | return=id; post_type=person; multiple=0; allow_null=0; bidirectional_target=[] |
| 1481 | field_vg_product_contribution_roles | product_contribution_roles | Product Contribution: Roles | taxonomy | 0 / [] | none | return=id; taxonomy=product_role; field_type=multi_select; multiple=1; allow_null=0; save_terms=1; load_terms=1; add_term=0; bidirectional_target=[] |
| 1482 | field_vg_product_contribution_display_order | product_contribution_display_order | Product Contribution: Display Order | number | 0 / 0 | none | min=0 |

### Person Related Content, `group_vg_person_related_content`, group ID 1706. List source=json; schema source=db; active=yes; location `post_type==person`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 1707 | field_vg_person_articles_primary_author | person_articles_primary_author | Person: Articles as primary author | relationship | 0 / n/a | none | return=id; post_type=post; min=0; max=0; bidirectional_target=[]; filters=search,post_type,taxonomy |
| 1708 | field_vg_person_articles_additional_author | person_articles_additional_author | Person: Articles as additional author | relationship | 0 / n/a | none | return=id; post_type=post; min=0; max=0; bidirectional_target=[]; filters=search,post_type,taxonomy |
| 1709 | field_vg_person_articles_expert_contributor | person_articles_expert_contributor | Person: Articles as expert contributor | relationship | 0 / n/a | none | return=id; post_type=post; min=0; max=0; bidirectional_target=[]; filters=search,post_type,taxonomy |
| 1710 | field_vg_person_articles_reviewed_by | person_articles_reviewed_by | Person: Articles reviewed by | relationship | 0 / n/a | none | return=id; post_type=post; min=0; max=0; bidirectional_target=[]; filters=search,post_type,taxonomy |
| 1711 | field_vg_person_courses_lead_instructor | person_courses_lead_instructor | Person: Courses as lead instructor | relationship | 0 / n/a | none | return=id; post_type=mto-course; min=0; max=0; bidirectional_target=[]; filters=search,post_type,taxonomy |
| 1712 | field_vg_person_courses_additional_instructor | person_courses_additional_instructor | Person: Courses as additional instructor | relationship | 0 / n/a | none | return=id; post_type=mto-course; min=0; max=0; bidirectional_target=[]; filters=search,post_type,taxonomy |
| 1713 | field_vg_person_courses_expert | person_courses_expert | Person: Courses as expert | relationship | 0 / n/a | none | return=id; post_type=mto-course; min=0; max=0; bidirectional_target=[]; filters=search,post_type,taxonomy |

### Article People, `group_vg_article_people`, group ID 1714. List source=json; schema source=db; active=yes; location `post_type==post`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 1715 | field_vg_article_primary_author | primary_author | Article: Primary author | post_object | 0 / n/a | none | return=id; post_type=person; multiple=0; allow_null=1; bidirectional=1; bidirectional_target=field_vg_person_articles_primary_author |
| 1716 | field_vg_article_additional_authors | additional_authors | Article: Additional authors | post_object | 0 / n/a | none | return=id; post_type=person; multiple=1; allow_null=1; bidirectional=1; bidirectional_target=field_vg_person_articles_additional_author |
| 1717 | field_vg_article_expert_contributors | expert_contributors | Article: Expert contributors | post_object | 0 / n/a | none | return=id; post_type=person; multiple=1; allow_null=1; bidirectional=1; bidirectional_target=field_vg_person_articles_expert_contributor |
| 1718 | field_vg_article_reviewed_by | reviewed_by | Article: Reviewed by | post_object | 0 / n/a | none | return=id; post_type=person; multiple=1; allow_null=1; bidirectional=1; bidirectional_target=field_vg_person_articles_reviewed_by |

### Course People, `group_vg_course_people`, group ID 1719. List source=json; schema source=db; active=yes; location `post_type==mto-course`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 1720 | field_vg_course_lead_instructor | lead_instructor | Course: Lead instructor | post_object | 0 / n/a | none | return=id; post_type=person; multiple=0; allow_null=1; bidirectional=1; bidirectional_target=field_vg_person_courses_lead_instructor |
| 1721 | field_vg_course_additional_instructors | additional_instructors | Course: Additional instructors | post_object | 0 / n/a | none | return=id; post_type=person; multiple=1; allow_null=1; bidirectional=1; bidirectional_target=field_vg_person_courses_additional_instructor |
| 1722 | field_vg_course_experts | experts | Course: Experts | post_object | 0 / n/a | none | return=id; post_type=person; multiple=1; allow_null=1; bidirectional=1; bidirectional_target=field_vg_person_courses_expert |

### Event: Landing page, `group_vg_event_landing`, group ID 1913. List source=db; schema source=db; active=yes; location `post_type==tc_events`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 1914 | field_vg_event_landing_testimonials | event_landing_testimonials | Event: Getuigskrifte | relationship | 0 / n/a | none | return=id; post_type=testimonial; min=0; max=3; bidirectional=0; bidirectional_target=[]; filters=search |
| 1915 | field_vg_event_landing_gallery | event_landing_gallery | Event: Galery | gallery | 0 / n/a | none | return=array; min=0; max=12; min_width=0; max_width=0 |
| 1916 | field_vg_event_landing_video | event_landing_video | Event: Video URL | url | 0 / blank | none | n/a |
| 1917 | field_vg_event_landing_programme | event_landing_programme | Event: Program en praktiese inligting | wysiwyg | 0 / blank | none | n/a |
| 1918 | field_vg_event_landing_info_image | event_landing_info_image | Event: Inligtingsfoto | image | 0 / n/a | none | return=id; min_width=0; max_width=0 |

### Home: Featured destination, `group_vg_homepage`, group ID 1928. List source=db; schema source=db; active=yes; location `page==1926`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| 1929 | field_vg_home_featured_destination | home_featured_destination | Home: Featured destination | post_object | 0 / n/a | none | return=id; post_type=page,tc_events,mto-course,product; multiple=0; allow_null=1; bidirectional=0; bidirectional_target=[] |


### Member Perks JSON field schemas

The two plugin files define 83 top-level fields, including tabs, plus 28 repeater children. The schema has no conditional logic on these fields. The ACF JSON definitions and runtime plugin copies have matching SHA-256 hashes. Repeater child rows are nested under their parent in the field table.

### Voelgoed Perk Settings, `group_vgmp_options`, group ID n/a. List source=json; schema source=json; active=yes; location `options_page==vgmp-settings`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| n/a | field_vgmp_options_general_tab | blank | General Settings | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_default_timezone | vgmp_default_timezone | Default Timezone | text | 0 / blank | none | n/a |
| n/a | field_vgmp_enable_logging | vgmp_enable_logging | Enable Logging | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_enable_admin_debug_badges | vgmp_enable_admin_debug_badges | Enable Admin Debug Badges | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_environment_mode | vgmp_environment_mode | Environment Mode | select | 0 / "production" | none | return=value; multiple=0; allow_null=0; choices=production=Production; staging=Staging; local=Local |
| n/a | field_vgmp_programme_settings_tab | blank | Programme Settings | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_programme_settings | vgmp_programme_settings | Programme Settings | repeater | 0 / n/a | none | min=0; max=0; layout=row |
| n/a | field_vgmp_programme_key | programme_key | Programme Settings > Programme Key | text | 0 / blank | none | n/a |
| n/a | field_vgmp_programme_label | programme_label | Programme Settings > Programme Label | text | 0 / blank | none | n/a |
| n/a | field_vgmp_programme_aliases | programme_aliases | Programme Settings > Programme Aliases | textarea | 0 / blank | none | n/a |
| n/a | field_vgmp_programme_enabled | programme_enabled | Programme Settings > Enabled | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_programme_sort_order | programme_sort_order | Programme Settings > Sort Order | number | 0 / 0 | none | n/a |
| n/a | field_vgmp_programme_supports_hub | programme_supports_hub | Programme Settings > Supports Hub | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_programme_hub_page_id | programme_hub_page_id | Programme Settings > Hub Page | post_object | 0 / n/a | none | return=id; post_type=page; multiple=0; allow_null=0; bidirectional_target=[] |
| n/a | field_vgmp_programme_upsell_page_id | programme_upsell_page_id | Programme Settings > Upsell Page | post_object | 0 / n/a | none | return=id; post_type=page; multiple=0; allow_null=0; bidirectional_target=[] |
| n/a | field_vgmp_programme_intro_text | programme_intro_text | Programme Settings > Intro Text | textarea | 0 / blank | none | n/a |
| n/a | field_vgmp_programme_supports_notifications | programme_supports_notifications | Programme Settings > Supports Notifications | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_programme_notes | programme_notes | Programme Settings > Programme Notes | textarea | 0 / blank | none | n/a |
| n/a | field_vgmp_programme_level_mapping_tab | blank | Programme Level Mapping | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_programme_level_mappings | vgmp_programme_level_mappings | Programme Level Mappings | repeater | 0 / n/a | none | min=0; max=0; layout=row |
| n/a | field_vgmp_mapping_programme_key | programme_key | Programme Level Mappings > Programme Key | text | 0 / blank | none | n/a |
| n/a | field_vgmp_mapping_level_key | level_key | Programme Level Mappings > Level Key | text | 0 / blank | none | n/a |
| n/a | field_vgmp_level_label | level_label | Programme Level Mappings > Level Label | text | 0 / blank | none | n/a |
| n/a | field_vgmp_level_aliases | level_aliases | Programme Level Mappings > Level Aliases | textarea | 0 / blank | none | n/a |
| n/a | field_vgmp_level_rank | level_rank | Programme Level Mappings > Level Rank | number | 0 / 0 | none | n/a |
| n/a | field_vgmp_pmpro_level_ids | pmpro_level_ids | Programme Level Mappings > PMPro Level IDs | text | 0 / blank | none | n/a |
| n/a | field_vgmp_is_paid_level | is_paid_level | Programme Level Mappings > Paid Level | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_mapping_enabled | mapping_enabled | Programme Level Mappings > Mapping Enabled | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_member_cap | member_cap | Programme Level Mappings > Member Cap | number | 0 / blank | none | min=0 |
| n/a | field_vgmp_cap_mode | cap_mode | Programme Level Mappings > Cap Mode | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=none=None; soft=Soft cap; hard=Hard cap |
| n/a | field_vgmp_billing_notes | billing_notes | Programme Level Mappings > Billing Notes | textarea | 0 / blank | none | n/a |
| n/a | field_vgmp_mapping_notes | mapping_notes | Programme Level Mappings > Mapping Notes | textarea | 0 / blank | none | n/a |
| n/a | field_vgmp_hub_settings_tab | blank | Hub Settings | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_main_dashboard_page_id | vgmp_main_dashboard_page_id | Main Dashboard Page | post_object | 0 / n/a | none | return=id; post_type=page; multiple=0; allow_null=0; bidirectional_target=[] |
| n/a | field_vgmp_empty_state_message | vgmp_empty_state_message | Empty State Message | textarea | 0 / blank | none | n/a |
| n/a | field_vgmp_no_membership_message | vgmp_no_membership_message | No Membership Message | textarea | 0 / blank | none | n/a |
| n/a | field_vgmp_display_defaults_tab | blank | Display Defaults | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_default_cta_label | vgmp_default_cta_label | Default CTA Label | text | 0 / blank | none | n/a |
| n/a | field_vgmp_default_card_image | vgmp_default_card_image | Default Card Image | image | 0 / n/a | none | return=id; min_width=0; max_width=0 |
| n/a | field_vgmp_deduplicate_shared_perks | vgmp_deduplicate_shared_perks | Deduplicate Shared Perks | true_false | 0 / 1 | none | n/a |
| n/a | field_vgmp_feature_flags_tab | blank | Rules and Feature Flags | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_default_stackable | vgmp_default_stackable | Default Stackable | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_default_coupon_compatible | vgmp_default_coupon_compatible | Default Coupon Compatible | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_default_claim_scope | vgmp_default_claim_scope | Default Claim Scope | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=per_user=Per user; per_programme=Per programme; per_programme_level=Per programme level; per_order=Per order; unlimited=Unlimited |
| n/a | field_vgmp_default_claims_per_user | vgmp_default_claims_per_user | Default Claims Per User | number | 0 / blank | none | min=0 |
| n/a | field_vgmp_enable_runtime_status_cron | vgmp_enable_runtime_status_cron | Enable Runtime Status Cron | true_false | 0 / 1 | none | n/a |
| n/a | field_vgmp_enable_hub_display | vgmp_enable_hub_display | Enable Hub Display | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_enable_woo_discounts | vgmp_enable_woo_discounts | Enable Woo Discounts | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_allow_initial_subscription_product_discounts | vgmp_allow_initial_subscription_product_discounts | Allow Initial Subscription Product Discounts | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_enable_course_claims | vgmp_enable_course_claims | Enable Course Claims | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_enable_ticket_claims | vgmp_enable_ticket_claims | Enable Ticket Claims | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_enable_woo_blocks_compat | vgmp_enable_woo_blocks_compat | Enable Woo Blocks Compatibility | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_enable_notifications | vgmp_enable_notifications | Enable Notifications | true_false | 0 / 0 | none | n/a |

### Member Perk Details, `group_vgmp_perk_details`, group ID n/a. List source=json; schema source=json; active=yes; location `post_type==vg_perk`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| n/a | field_vgmp_perk_schedule_tab | blank | Schedule | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_perk_start_datetime | perk_start_datetime | Start Date | date_time_picker | 0 / n/a | none | return=Y-m-d H:i |
| n/a | field_vgmp_perk_end_datetime | perk_end_datetime | End Date | date_time_picker | 0 / n/a | none | return=Y-m-d H:i |
| n/a | field_vgmp_perk_timezone | perk_timezone | Timezone | text | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_manual_status | perk_manual_status | Manual Status | select | 0 / "auto" | none | return=value; multiple=0; allow_null=0; choices=auto=Auto; paused=Paused; archived=Archived |
| n/a | field_vgmp_perk_audience_tab | blank | Audience | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_perk_audience_mode | perk_audience_mode | Audience Mode | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=all_members=All members; programme_rules=Programme rules; manual_review=Manual review |
| n/a | field_vgmp_perk_audience_rules | perk_audience_rules | Audience Rules | repeater | 0 / n/a | none | min=0; max=0; layout=row |
| n/a | field_vgmp_perk_audience_programme_key | programme_key | Audience Rules > Programme Key | text | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_level_selection_mode | level_selection_mode | Audience Rules > Level Selection Mode | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=any=Any level; specific=Specific level keys; minimum=Minimum level key |
| n/a | field_vgmp_perk_level_keys | level_keys | Audience Rules > Level Keys | text | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_minimum_level_key | minimum_level_key | Audience Rules > Minimum Level Key | text | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_audience_rule_label | audience_rule_label | Audience Rules > Rule Label | text | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_benefit_tab | blank | Benefit | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_perk_benefit_type | perk_benefit_type | Benefit Type | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=percentage_discount=Percentage discount; fixed_discount=Fixed discount; fixed_member_price=Fixed member price; free=Free course / product; member_only_access=Member-only access; display_only=Display only; free_access=Free access; content_unlock=Content unlock; ticket_access=Ticket access; external_link=External link |
| n/a | field_vgmp_perk_discount_value | perk_discount_value | Discount Value | number | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_discount_basis | perk_discount_basis | Discount Basis | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=percentage=Percentage; fixed=Fixed amount; member_price=Member price |
| n/a | field_vgmp_perk_stackable | perk_stackable | Stackable | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_perk_coupon_compatible | perk_coupon_compatible | Coupon Compatible | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_perk_priority | perk_priority | Priority | number | 0 / 0 | none | n/a |
| n/a | field_vgmp_perk_dual_programme_resolution | perk_dual_programme_resolution | Dual Programme Resolution | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=highest_priority=Highest priority; best_member_value=Best member value; show_all=Show all matching perks |
| n/a | field_vgmp_perk_target_tab | blank | Target | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_perk_target_type | perk_target_type | Target Type | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=product=Woo product; product_category=Woo product category; course=Masteriyo course; event=Tickera event; ticket=Tickera ticket; page=Page; post=Post; external_url=External URL |
| n/a | field_vgmp_perk_target_products | perk_target_products | Target Products | relationship | 0 / n/a | none | return=id; post_type=product; min=0; max=0; bidirectional_target=[]; filters=search,post_type |
| n/a | field_vgmp_perk_target_product_categories | perk_target_product_categories | Target Product Categories | taxonomy | 0 / n/a | none | return=id; taxonomy=product_cat; field_type=multi_select; multiple=0; allow_null=0; save_terms=0; load_terms=0; add_term=0; bidirectional_target=[] |
| n/a | field_vgmp_perk_target_courses | perk_target_courses | Target Courses | relationship | 0 / n/a | none | return=id; post_type=mto-course; min=0; max=0; bidirectional_target=[]; filters=search,post_type |
| n/a | field_vgmp_perk_target_events | perk_target_events | Target Events | relationship | 0 / n/a | none | return=id; post_type=tc_events; min=0; max=0; bidirectional_target=[]; filters=search,post_type |
| n/a | field_vgmp_perk_target_tickets | perk_target_tickets | Target Tickets | relationship | 0 / n/a | none | return=id; post_type=tc_tickets; min=0; max=0; bidirectional_target=[]; filters=search,post_type |
| n/a | field_vgmp_perk_target_pages | perk_target_pages | Target Pages | relationship | 0 / n/a | none | return=id; post_type=page; min=0; max=0; bidirectional_target=[]; filters=search,post_type |
| n/a | field_vgmp_perk_target_posts | perk_target_posts | Target Posts | relationship | 0 / n/a | none | return=id; post_type=post; min=0; max=0; bidirectional_target=[]; filters=search,post_type |
| n/a | field_vgmp_perk_external_url | perk_external_url | External URL | url | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_claim_tab | blank | Claim Behaviour | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_perk_claim_mode | perk_claim_mode | Claim Mode | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=display_only=Display only; claim_button=Claim button; manual_admin=Manual admin; auto_apply=Auto apply |
| n/a | field_vgmp_perk_claim_scope | perk_claim_scope | Claim Scope | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=per_user=Per user; per_programme=Per programme; per_programme_level=Per programme level; per_order=Per order; unlimited=Unlimited |
| n/a | field_vgmp_perk_max_claims_per_user | perk_max_claims_per_user | Max Claims Per User | number | 0 / blank | none | min=0 |
| n/a | field_vgmp_perk_max_total_claims | perk_max_total_claims | Max Total Claims | number | 0 / blank | none | min=0 |
| n/a | field_vgmp_perk_claim_reset_period | perk_claim_reset_period | Claim Reset Period | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=never=Never; daily=Daily; weekly=Weekly; monthly=Monthly; yearly=Yearly |
| n/a | field_vgmp_perk_keep_visible_after_claim | perk_keep_visible_after_claim | Keep Visible After Claim | true_false | 0 / 1 | none | n/a |
| n/a | field_vgmp_perk_course_access_mode | perk_course_access_mode | Course Access Mode | select | 0 / "claim_and_enrol" | none | return=value; multiple=0; allow_null=1; choices=claim_and_enrol=Claim and enrol; display_only=Display only; manual_admin=Manual admin |
| n/a | field_vgmp_perk_course_access_retention | perk_course_access_retention | Course Access Retention | select | 0 / "keep_after_claim" | none | return=value; multiple=0; allow_null=1; choices=keep_after_claim=Keep after claim; while_membership_active=While membership active; until_perk_expires=Until perk expires |
| n/a | field_vgmp_perk_ticket_claim_mode | perk_ticket_claim_mode | Ticket Claim Mode | select | 0 / "add_to_cart" | none | return=value; multiple=0; allow_null=1; choices=add_to_cart=Add to cart; view_event=View event; display_only=Display only; manual_admin=Manual admin |
| n/a | field_vgmp_perk_ticket_claim_enabled | perk_ticket_claim_enabled | Enable Ticket Claim For Woo Product Target | true_false | 0 / 0 | none | n/a |
| n/a | field_vgmp_perk_default_ticket_product | perk_default_ticket_product | Default Ticket Product | relationship | 0 / n/a | none | return=id; post_type=product; min=0; max=1; bidirectional_target=[]; filters=search,post_type |
| n/a | field_vgmp_perk_ticket_quantity_limit | perk_ticket_quantity_limit | Ticket Quantity Limit | number | 0 / 1 | none | min=1 |
| n/a | field_vgmp_perk_ticket_require_checkout | perk_ticket_require_checkout | Ticket Requires Checkout | true_false | 0 / 1 | none | n/a |
| n/a | field_vgmp_perk_ticket_redirect_after_claim | perk_ticket_redirect_after_claim | Ticket Redirect After Claim | select | 0 / "cart" | none | return=value; multiple=0; allow_null=1; choices=cart=Cart; checkout=Checkout |
| n/a | field_vgmp_perk_ticket_event_selection_mode | perk_ticket_event_selection_mode | Ticket Event Selection Mode | select | 0 / "explicit_ticket_required" | none | return=value; multiple=0; allow_null=1; choices=explicit_ticket_required=Explicit ticket required; default_ticket=Default ticket; member_selects_ticket=Member selects ticket |
| n/a | field_vgmp_perk_hub_card_tab | blank | Hub Card Display | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_perk_card_title_override | perk_card_title_override | Card Title Override | text | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_badge_text | perk_badge_text | Badge Text | text | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_card_excerpt | perk_card_excerpt | Card Excerpt | textarea | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_cta_label | perk_cta_label | CTA Label | text | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_cta_behaviour | perk_cta_behaviour | CTA Behaviour | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=target=Open target; claim=Start claim; external=Open external URL; details=Open details |
| n/a | field_vgmp_perk_card_image_override | perk_card_image_override | Card Image Override | image | 0 / n/a | none | return=id; min_width=0; max_width=0 |
| n/a | field_vgmp_perk_programme_display_grouping | perk_programme_display_grouping | Programme Display Grouping | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=auto=Automatic; single_card=Single shared card; programme_cards=Programme-specific cards |
| n/a | field_vgmp_perk_admin_notes_tab | blank | Admin Notes | tab | 0 / n/a | none | n/a |
| n/a | field_vgmp_perk_internal_notes | perk_internal_notes | Internal Notes | textarea | 0 / blank | none | n/a |
| n/a | field_vgmp_perk_reporting_tag | perk_reporting_tag | Reporting Tag | text | 0 / blank | none | n/a |


### Platform Core PHP fields

### Content Topic Roles, `group_vg_content_topic_roles`, group ID n/a. List source=php; schema source=php; active=yes; location `post_type==post OR post_type==mto-course OR post_type==product OR post_type==tc_events OR post_type==uitsending OR post_type==kompetisie`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| n/a | field_vg_primary_content_topic | _vg_primary_content_topic | Content Topic: Primary Topic | taxonomy | 0 / n/a | none | return=id; taxonomy=content_topic; field_type=select; multiple=0; allow_null=1; save_terms=0; load_terms=0; add_term=0; bidirectional_target=[] |

### Product People, `group_vg_product_people`, group ID n/a. List source=php; schema source=php; active=yes; location `post_type==product`.

| ID | Field key | Name | Label | Type | Required / default | Conditional logic | Return format, relationships and type settings |
|---:|---|---|---|---|---|---|---|
| n/a | field_vg_product_people_mode | product_people_mode | Product: People mode | select | 0 / blank | none | return=value; multiple=0; allow_null=1; choices=event=Event-derived; manual=Manual |


### Relationship graph

- **Article to Person:** `primary_author`, `additional_authors`, `expert_contributors`, and `reviewed_by` point to Person. Each field has a matching Person reverse relationship field configured as its bidirectional target. The reverse fields are derived and hidden from the Person edit screen by `PersonRelatedContentFields.php:11-39).
- **Course to Person:** `lead_instructor`, `additional_instructors`, and `experts` point to Person and target their corresponding Person reverse fields.
- **Event to Person:** canonical `event_participation` records link a Tickera event to a Person, with event role terms and display order. Legacy `event_medewerkers` separately links the event to a `medewerker`.
- **Product to Person:** canonical `product_contribution` records link a WooCommerce product to a Person, with product role terms and display order. Product mode chooses the manual or event-derived path. Legacy `produk_medewerkers` still links a product to `medewerker`; `produk_bron_event` points to a Tickera event.
- **Content topic:** direct `content_topic` term relationships are authoritative. The PHP ACF field `_vg_primary_content_topic` stores an optional Primary role and does not save or load taxonomy terms itself.
- **Member Perks:** the plugin owns Perk metadata, targeting and claim configuration. Its fields do not define the core Article, Person, Course, Product, or Event relationships.

## 5. How It Works

### Article, Person, and Course

ACF stores the forward Article and Course references. The configured bidirectional targets keep the reverse Person fields aligned when ACF saves these values. Platform Core hides the reverse fields from direct editing and reads them as derived Person-related content. The Article template 372 consumes author, expert, reviewer, lede, and read-time fields. Person template 1734 consumes the reverse Article and Course fields. Course template 1733 consumes its three forward Person fields.

The data model is native ACF; there is no custom ArticleParticipation or CourseParticipation record in the inspected source. The templates and ACF configuration exist, but the audit did not render them.

### Event Participation lifecycle

`EventParticipationService` accepts WordPress statuses `publish`, `draft`, `pending`, and `private`. It validates a nontrashed Tickera event and Person, rejects a negative display order, validates the role terms, acquires a pair lock, then repeats the pair check before creating a record. The service writes the two required ACF references and the display order, then assigns native `event_role` terms. The repository treats `publish` as active and checks `publish`, `draft`, `pending`, `private`, and `future` for uniqueness. A failed create attempts a hard-delete rollback. These rules appear in `EventParticipationService.php:99-259` and `EventParticipationRepository.php:11-17,68-189`.

The Bricks query adapter reads published participations for the current event and hides the participants section when the query has no matching IDs. Template 774 contains the event participation markers. The runtime has no Event Participation records, so this path has no current association data. There is no distinct domain terminal state in the ACF schema. The service's failed-create rollback is the only explicit hard-delete path established here. Transitions for existing records, retirement rules, and edit behavior remain unverified.

### Product Contribution and Product People mode

`ProductContributionService` supports `publish`, `draft`, `pending`, and `private` create statuses. Before publishing it requires a valid Product/Person pair, a nonempty approved Product Role, and a selected Product People mode. It uses a pair lock and a fresh duplicate check, writes ACF endpoint/order fields, assigns native `product_role` terms, and verifies role persistence. Failed writes attempt hard-delete rollback and check the post, metadata, and taxonomy edges. See `ProductContributionService.php:11-22,50-99,120-265,352-419` and `ProductContributionRepository.php:13-24,249-418`.

The Product People selector offers `event` and `manual`, but has no nonempty stored runtime value and no default. The resolver does not read the legacy `produk_medewerkers`, `event_medewerkers`, or `medewerker` fields. It returns no people when the selected mode is invalid or blank. **INFERRED:** the canonical Product Single query therefore has no people to resolve for products without a selected mode or canonical contribution. The template was not rendered to verify its visible output.

The canonical event-derived route reads event participations and can use Product Contribution rows for product-specific roles. It remains separate from `produk_bron_event`, which has its own source-event Bricks query. No terminal Product Contribution state was found. The post statuses and hard-delete rollback are WordPress/service behavior, not an ACF domain state machine.

### Topic roles

The `ContentTopicRoles` PHP field group registers on six post types: `post`, `mto-course`, `product`, `tc_events`, `uitsending`, and `kompetisie`. It does not register on Person. The field is optional, single-select, returns a term ID, and has `save_terms=0` and `load_terms=0`. Platform Core requires the Primary term to be a direct `content_topic` term before it saves the role. Clearing Primary leaves the direct taxonomy relationship unchanged. See `ContentTopicRoles.php:17-27,63-111,232-257,264-325,349-401`.

The observed classification states are `UNCLASSIFIED`, `MIGRATION_SEED`, `NEEDS_REVIEW`, `REVIEWED`, `INVALID`, and `NOT_APPLICABLE`. A valid stored Primary produces `REVIEWED`; direct terms without Primary become `MIGRATION_SEED` or `NEEDS_REVIEW` based on the migration record; no direct terms gives `UNCLASSIFIED`; unsupported types give `NOT_APPLICABLE`. The code clears a Primary role when its direct taxonomy edge is removed. This classification state is derived from data and migration provenance. Runtime counts show one direct topic edge and no Primary field values.

### Legacy event and product readers

The source-controlled FluentSnippet `10-vg-bricks-medewerker-queries.php:62-162` reads legacy `event_medewerkers`, `produk_medewerkers`, and `produk_bron_event` fields for Bricks query types. Runtime-only snippet `9-acf-product-copy.php` writes legacy product people on product save, but only when the product field is empty. It reads the source event's `event_medewerkers` and copies those references to `produk_medewerkers`. This writer is outside the root `site-code/fluent-snippets` source tree.

### Reader and writer map

| Field family | Writer or source | Reader or consumer | Evidence |
|---|---|---|---|
| Article people | ACF edit; ACF bidirectional targets | Article template 372; Person template 1734 | ACF group schemas; template scan |
| Course people | ACF edit; ACF bidirectional targets | Course template 1733; Person template 1734 | ACF group schemas; template scan |
| Event Participation | Platform Core service and admin | Event Participation repository, Bricks adapter, Event template 774 | Source files above; runtime post count zero |
| Product Contribution | Platform Core service and admin | Product Contribution repository, resolver, Product template 124, Person template 1734 | Source files above; runtime post count zero |
| Topic direct term and Primary | WordPress taxonomy and Platform Core/ACF | Topic archive 1859; `ContentTopicRoles` readers | Source files and runtime taxonomy aggregate |
| Legacy event staff | ACF; runtime-only snippet 9 can copy to product | FluentSnippet 10; Medewerker template 772 | Source and runtime snippet scan |
| Legacy product staff/source event | ACF; runtime-only snippet 9 | FluentSnippet 10; Product Single 124 has canonical markers and a source-event query | Source/runtime scan |
| Article lede, read time, featured options | Runtime-only FluentSnippet 2 | Published Article template 372 and its Bricks query/body-class hooks | Runtime snippet read; source file absent |
| Event landing fields | ACF | Draft template 1919 Event Landing Frames | Template scan; no current field population |
| Broadcast details | ACF | Published Voelgoed Live template 1127 | Template scan |
| Perk settings and Perk fields | Member Perks plugin and ACF JSON | Plugin settings, claims, hub, display, WooCommerce, Masteriyo, and Tickera readers | Plugin source search and ACF schema |

## 6. Source Versus Runtime

### Repo ACF JSON source

**BOTH_CONFIRMED:** The active Bricks Child ACF JSON symlink resolves to:

`/home/jcschoeman96/projects/runtime/vgnova-acf-sources/c450475971de722378a1658157a3a24838e467f7/site-code/acf-json`

The detached runtime worktree reports SHA `c450475971de722378a1658157a3a24838e467f7` and a clean status. `diff -qr site-code/acf-json <active-symlink>` returned no output. The repo and runtime JSON files therefore match byte-for-byte for the inspected subtree. This does not make the runtime worktree's whole repository HEAD equal to main.

The seven repo-managed relationship and classification JSON files are also present in runtime. A field-level comparison of the seven source files with the database-backed fields returned zero differences for the compared schema properties. The ACF group list calls the groups JSON source. The schema getter calls them DB source. **CONFLICT:** ACF's two read abilities disagree on provenance even though the source files and compared schema properties match.

### Perks plugin JSON source

**BOTH_CONFIRMED:** The source files in `voelgoed-member-perks/acf-json` match the runtime plugin copies:

- `group_vgmp_options.json`: `4206527fddd2536ae9527fbecc78f64f7976fa0292db346c4884f28ab563192b`
- `group_vgmp_perk_details.json`: `d2fd83ee4069ee7d6986e87bf4c2449a9612e9042f4ff02b7b332f75f1c186f4`

`AcfFields.php:18-26` adds the plugin's `acf-json` directory. `SettingsPage.php:20-26` registers the settings page. The ACF tools saw the two plugin field groups as JSON source, but did not list the PHP-registered page.

### PHP-local field groups

**BOTH_CONFIRMED:** `ProductPeopleFields.php` registers `group_vg_product_people`. `ContentTopicRoles.php` registers `group_vg_content_topic_roles`. The current runtime group schema matches those two source definitions. The active Platform Core source files for Product People, Topic Roles, and Person Related Content matched their runtime copy hashes during the read-only inspection. These source files do not replace Bricks queries or ACF's native relationship storage.

### Database-only field groups

**RUNTIME_CONFIRMED:** 17 groups are classified as DB source. They have active ACF definitions in the runtime. No corresponding JSON files for those groups were found under `site-code/acf-json`. The report does not establish whether the DB is their approved long-term source of truth. A source export or explicit authority decision is needed before these definitions are treated as portable.

### Runtime-only FluentSnippets and provenance conflicts

- `9-acf-product-copy.php` is published in runtime snippet storage and absent from the inspected source tree. It writes legacy product relationship metadata.
- `2-vg-article-dynamic-tags.php` is published in runtime snippet storage and absent from the inspected source tree. It reads article lede/read-time fields, Featured Content Manager fields, and `content_category`.
- `10-vg-bricks-medewerker-queries.php` exists in source and runtime. Its PHP logic matched; the runtime file also has a FluentSnippets wrapper.

**CONFLICT:** The Local ACF source design and plan v0.1.1 identify generation `5a3bc4fc89fa61ae8916aa460989f1e8912b473d` at `/home/jcschoeman96/projects/runtime/vgnova-acf-source`. The runtime activation record v1.0.0 identifies `c450475971de722378a1658157a3a24838e467f7` as active. The observed symlink resolves to the latter. The older design and plan therefore have stale current-generation text unless a newer approved amendment says otherwise. See `docs/superpowers/specs/2026-10-29-vgnova-dedicated-acf-runtime-source-design_v0.1.1.md:26-39,103-110`, `docs/superpowers/plans/2026-10-29-vgnova-dedicated-acf-runtime-source_v0.1.1.md:11,23-38,504-509`, and `docs/architecture/NATIVE_PERSON_RELATIONSHIPS_RUNTIME_ACTIVATION_v1.0.0.md:6-42`.

### Delivery-state separation

| State | Assessment |
|---|---|
| SOURCE_MERGED | Confirmed for inspected root source at `454346d9e6a2c36864a4bbd944e52596f16e09c1`; DB-only groups and runtime-only snippets are outside that source baseline |
| RUNTIME_APPLIED | PARTIAL. Repo ACF JSON subtree, Perks JSON, and selected Platform Core PHP files match runtime; other DB field groups have no repo JSON counterpart |
| BEHAVIOR_VERIFIED | NO. No browser render, transactional exercise, or field-write test was run |
| BUSINESS_APPROVED | UNKNOWN. No approval was established by this read-only audit |
| LAUNCH_READY | UNKNOWN. This report does not assess launch readiness |

## 7. Completeness and Functionality

### Stored relationship counts

All counts below are aggregate counts. No referenced record IDs or stored values are included.

| Relationship | Runtime aggregate | Integrity finding |
|---|---:|---|
| Article primary author | 0 owners, 0 references | No current forward edge |
| Article additional authors | 0 owners, 0 references | No current forward edge |
| Article expert contributors | 1 owner, 1 reference | Valid Person edge; reverse field matches |
| Article reviewed by | 0 owners, 0 references | No current forward edge |
| Person reverse Article fields | Expert field 1 owner / 1 reference; other fields empty | Expert edge matches forward edge |
| Course lead instructor | 0 owners, 0 references | No current forward edge |
| Course additional instructors | 1 owner, 1 reference | One valid Person edge |
| Course experts | 0 owners, 0 references | No current forward edge |
| Person reverse Course fields | Additional instructor field 1 owner / 2 references; other fields empty | One valid current Course edge and one stored reference with no matching current Course row |
| Legacy Event Medewerkers | 2 event owners, 4 references | All references resolve to current `medewerker` records; no repeated ID within an event field |
| Legacy Product source event | 3 product owners, 3 references | All references resolve to current `tc_events` records |
| Legacy Product Medewerkers | 2 product owners, 3 references | All references resolve to current `medewerker` records |
| Canonical Event Participation | 0 posts; no endpoint fields | No Event-Person record or edge |
| Canonical Product Contribution | 0 posts; no endpoint fields | No Product-Person record or edge |
| Product People mode | 0 nonempty values | Canonical Product People route has no selected mode |
| Primary Topic | 0 nonempty values | No stored Primary term |
| Direct `content_topic` | 1 object edge | One direct term assignment, on a post |

The reverse Course reference is a stale edge relative to current post rows. Its source or delete history was not available. It may be old metadata from a removed or otherwise absent Course; that explanation is **UNKNOWN**. No duplicate forward Article, Course, Event, or Product relationship was found in the inspected aggregates.

### Field-group population

The numerator is the count of distinct ACF field names with at least one nonempty stored value. The denominator is the number of non-tab fields; nested Perks repeater fields are included in their denominators. This scan counted keys only and did not expose their values. A zero means no nonempty data was found for that group's fields, not that the group has no code consumer.

| Group | Populated field names / data-bearing field names | Note |
|---|---:|---|
| Kompetisie Inskrywing Parent | 0 / 1 | No current competition-entry record |
| Company & Brand | 4 / 18 | Options page fields |
| Newsletter & CTAs | 7 / 15 | Options page fields |
| Testimonial Details | 0 / 8 | Draft Event Landing template references them |
| Team Member Profile | 0 / 9 | No Team Member posts observed |
| Content Article | 0 / 2 | Runtime-only snippet reads lede and read time |
| Content Category Sidebar Promo | 1 / 3 | Group inactive; stored data spans two term-meta objects |
| Featured Content Manager | 3 / 8 | Runtime-only snippet maps seven semantic slugs; it does not map `featured_leefstyl` |
| Vennoot Besonderhede | 3 / 4 | Partner fields |
| Kategorie Vennoot Mapping | 1 / 1 | Content-category taxonomy field |
| Partner Display Override | 1 / 2 | Post/page override fields |
| Medewerker Besonderhede | 3 / 3 | Current profile metadata |
| Event Medewerkers | 1 / 1 | Legacy event relationship field |
| Medewerker Produkte | 2 / 2 | Legacy product source and staff fields |
| Broadcast Episode Details | 1 / 10 | Aggregate only; no field name or value reported |
| Editorial Classification | 0 / 2 | Editorial taxonomies have no assignment edges |
| Person Details | 0 / 5 | Person type is stored as taxonomy edges, not in these five fields |
| Event Participation Details | 0 / 4 | Canonical relation type has no posts |
| Product Contribution Details | 0 / 4 | Canonical relation type has no posts |
| Person Related Content | 2 / 7 | Derived reverse fields |
| Article People | 1 / 4 | One expert contributor field populated |
| Course People | 1 / 3 | One additional instructor field populated |
| Event Landing | 0 / 5 | Only draft template consumer located |
| Home Featured Destination | 1 / 1 | Page-specific field |
| Voelgoed Perk Settings | 31 / 47 | Across top-level settings and nested mapping fields |
| Member Perk Details | 45 / 51 | Across 452 owner objects; field values omitted |
| Content Topic Roles | 0 / 1 | No Primary Topic metadata |
| Product People | 0 / 1 | No mode selected |

### Domain coverage

| Domain | Schema and consumers | Runtime evidence | Coverage |
|---|---|---|---|
| Article | Native ACF Person fields, reverse targets, topic classification, article lede/read time, FCM consumer | 2 published posts; one expert edge; one direct topic edge; no author, additional-author, or reviewer references | PARTIAL |
| Person | Canonical public `person`, Person Details, reverse article/course references, `person_type` | 1 published Person; two Person Type edges; no data in Person Details fields; one stale reverse Course reference | PARTIAL |
| Course | Three native Person relationships and reverse fields | 6 published Courses; one valid additional-instructor edge; one unmatched reverse reference | PARTIAL |
| Event | Tickera event, legacy staff, Event Participation, Event Landing, topic/taxonomy fields | 17 published events; 2 legacy staff owners and 4 references; no Event Participation records; Event Landing fields empty | PARTIAL |
| Product | WooCommerce product, legacy event/staff fields, Product Contribution, Product People mode | 8 published products plus other statuses; 3 source-event references and 3 legacy staff references; no Product Contribution records; mode empty | PARTIAL |
| Topic | Platform Core canonical tree and optional Primary role | 55 terms, 7 roots, 1 direct edge, 0 Primary values; `content_category` has 0 edges | PARTIAL |
| Member Perks | Plugin-owned settings and Perk schema, integrations read the fields | Nonempty schema data appears across settings and Perk records; no checkout/claim behavior was exercised | PARTIAL |

The content-category taxonomy is empty of current object edges, while the runtime-only FCM snippet selects featured fields by `content_category` slug. That leaves the observed FCM selector without a currently assigned content category. The runtime snippet maps the seven canonical semantic slugs and excludes `featured_leefstyl`; the schema includes the latter field. Because Leefstyl is a commercial classification, this is a consumer gap to review, not proof the field is redundant.

The Bricks scan found the Article, Person, Course, Event, Product, Medewerker, and Voelgoed Live templates and their configured fields or query markers. The Event Landing and Testimonial fields appear only in draft template 1919. The Calendar templates 137 and 280 contain `{acf_dorp}`, but no inspected ACF group defines a `dorp` field. Whether another provider or legacy group owns that tag is **UNKNOWN**.

## 8. Problems, Risks and Contradictions

Severity uses the project's P0-P3 scale. No P0 or P1 defect was confirmed. P2 records a concrete correctness or source-governance risk that needs owner follow-up. P3 records a lower-impact candidate that needs more evidence.

| Severity | Finding | Evidence and impact | Confidence |
|---|---|---|---|
| P2 | One reverse Course edge has no current matching Course row | Reverse `additional_instructors` has two references while forward Course data has one valid edge. Person reverse lookup can contain stale metadata. | RUNTIME_CONFIRMED |
| P2 | Legacy and canonical Event/Product relationships coexist | Legacy fields have valid data; the canonical Event Participation and Product Contribution post types have no records. The canonical Product mode is blank. Canonical template sections may therefore show no people even while legacy fields contain people. The rendered result was not checked. | INFERRED |
| P2 | Runtime-only snippets own a writer and a consumer | Runtime snippet 9 writes legacy product relationships, and snippet 2 reads FCM/article fields. Neither has a source counterpart in the inspected root repository. A rebuild from source may omit their behavior. | RUNTIME_CONFIRMED |
| P2 | ACF list and schema tools disagree on provenance | Seven repo JSON groups are labeled JSON by list and DB by schema getter. The three relationship CPTs have the same list/getter conflict. | CONFLICT |
| P2 | Current ACF generation differs from older design text | The symlink and later activation record point to c450; older v0.1.1 design and plan call 5a3 the current generation. | CONFLICT |
| P2 | FCM reads the empty legacy category taxonomy | The FCM runtime consumer selects by `content_category`; that taxonomy has zero current object edges. FCM placement may not resolve for current posts. | RUNTIME_CONFIRMED / INFERRED |
| P3 | Two Broadcast taxonomy pairs are attached to `uitsending` | Both `broadcast_series`/`broadcast_type` and `uitsending_series`/`uitsending_type` are active. Their term and assignment counts could not be read in the failed follow-up. No consumer was located in source search. | RUNTIME_CONFIRMED / UNKNOWN |
| P3 | Inactive category promo group retains data | One field has nonempty data across two term-meta objects, while the group is inactive. No consumer was located. Deletion could orphan that data. | RUNTIME_CONFIRMED |
| P3 | Event Landing fields have no current population and only a draft consumer | Event Landing and Testimonial fields are configured in a draft Bricks template, not the published Event Single template. | RUNTIME_CONFIRMED |

No storage or mutation test was run. The source services implement validation and rollback paths, but these source paths are not proof of current rendered or transactional behavior.

## 9. Redundancy Candidates

| Candidate | Existing data and readers | Possible replacement | Preconditions, retention, rollback, and owner gate |
|---|---|---|---|
| `event_medewerkers` versus Event Participation | Legacy event-staff edges exist and FluentSnippet 10 reads them. Event Participation has no runtime posts. | Event Participation plus its native Bricks query and Event Role terms | Map every current event/person edge, role, order, template, product-derived consumer, and external reader. Keep source snapshots and legacy fields until the canonical result is compared in staging. Only retire after owner approval and a verified rollback path. |
| `produk_medewerkers` and snippet 9 versus Product Contribution | Legacy product people and source-event fields exist. Runtime snippet 9 writes the legacy field. Canonical Product Contribution has no posts and Product mode is empty. | Product Contribution in manual mode, or event-derived people through Event Participation, selected per product | Decide mode for each product. Preserve `produk_bron_event` where source-event display remains required. Backfill and compare all people, roles, order, and Person reverse views. Verify template output and snippet consumers before any retirement. Get owner approval for the data migration. |
| `broadcast_series`/`broadcast_type` versus `uitsending_series`/`uitsending_type` | Both pairs are active on `uitsending`; term and assignment counts are unknown. No consumer was located. | None selected | Read term/object counts and all runtime/source consumers first. Confirm which URL and taxonomy contract is approved. Keep both definitions and data until ownership is decided. |
| Featured Content Manager fields and article category selector | The FCM group has eight post-object fields; runtime snippet maps seven semantic category slugs through the legacy `content_category` taxonomy. | A reviewed selector aligned with the canonical content topic model | Do not rekey or remove fields before an approved mapping includes the commercial Leefstyl classification, post type targets, current stored options, and template behavior. Preserve current option data and the prior resolver for rollback. |
| Inactive Content Category Sidebar Promo | One field has nonempty data; the group is inactive and no consumer was located. | None selected | Identify the term-meta owners and any theme/plugin consumers. Export the affected schema and data before a separately approved cleanup. |
| Person reverse relationship fields | Reverse ACF fields mirror forward Article/Course references and are hidden in the Person editor. | No replacement recommended | These fields support current reverse reads. They are not duplicate editorial inputs. Retain bidirectional targets and reconcile the stale Course reference. |

The legacy taxonomy `content_category` is not interchangeable with `content_topic`. The repository's decommission register keeps the legacy taxonomy until its migration gates pass. Leefstyl remains a commercial classification. These rules block an assumption that empty current use authorizes deletion.

## 10. Recommendations

1. Keep the native ACF relationships and Bricks queries as the current architecture. The source already uses ACF bidirectional fields for simple Article/Course relationships and first-class relation records only for Event and Product contribution domains.
2. Ask the source owner to reconcile the two ACF generation documents with the observed c450 symlink and the later activation record. Record the approved current pointer in the source documents.
3. Resolve the ACF source metadata conflict by checking ACF's JSON load/sync state for the seven groups and the three CPTs. Treat the current files and schema match as evidence of parity, not proof that the abilities report ownership consistently.
4. Assign source control for the 17 DB-only groups and the runtime-only snippets. Export or version them only under an approved source-control task; do not edit staging fields or snippets as part of this report.
5. Reconcile the unmatched Person reverse Course reference against the current Course inventory and the owner-approved migration history. Do not delete it until the source and retention requirements are known.
6. Choose and set Product People mode per Product only after the canonical versus legacy relationship decision is approved. A blank selector has no source-code default.
7. Plan any Event/Product relationship migration as an explicit data task. Preserve all legacy fields and snippet behavior until edge, role, order, and Bricks output parity is verified.
8. Decide whether FCM continues to use the legacy category taxonomy or receives an approved canonical topic mapping. Include the commercial Leefstyl option and retain stored values until consumers are verified.
9. Read term/edge counts and source consumers for both Broadcast taxonomy pairs and trace `acf_dorp` to its provider. Do not remove these fields or taxonomies based on a missing result from the failed query.
10. Keep the inactive promo data and empty group definitions until a consumer and retention audit is complete. Empty field values alone do not establish that a group is safe to remove.

These are research actions for the owner or the next approved task. This report authorizes no schema edit, data migration, snippet change, runtime write, or deletion.

## 11. Outstanding Verification

- A browser render of Article, Person, Course, Event, Product, Medewerker, and Voelgoed Live pages is still required to confirm their configured dynamic tags and conditions.
- No transactional test exercised Event Participation or Product Contribution creation, uniqueness locks, role persistence, rollback, or publish behavior.
- The unmatched reverse Course reference needs provenance and owner-approved reconciliation.
- The final follow-up aggregate could not query terms and assignments for `broadcast_series`, `broadcast_type`, `uitsending_series`, or `uitsending_type`. It also could not count `dorp` field metadata. Those findings remain **UNKNOWN**.
- The current ACF JSON source pointer in the two older v0.1.1 documents conflicts with the observed symlink and the later activation record.
- ACF ability source metadata conflicts for seven group schemas and three CPT schemas. The source and runtime field JSON match, but the ownership label remains inconsistent.
- The approved source authority for 17 DB-only groups remains unknown.
- The current rendered state of FCM placement, legacy product copying, canonical Product People output, and Event Landing fields was not checked.
- ACF and Bricks ability bootstrap side effects were not independently ruled out. No explicit write ability or runtime mutation was invoked.

## 12. Final Domain Assessment

### Coverage and state

- Schema coverage: **FULL** for the 28 groups returned by ACF abilities, their 247 field definitions, four database-managed options pages, the source-registered Perks settings page, 10 ACF-managed post types, and 15 ACF-managed taxonomies.
- Runtime relationship and population coverage: **PARTIAL**. Main Article, Person, Course, Event, Product, and Topic aggregates were read. Broadcast taxonomy assignments and the `dorp` metadata count remain unknown.
- Behavior verification: **PARTIAL**. Source paths, ACF schemas, Bricks template configurations, and aggregate runtime records were inspected. Page rendering and transactional behavior were not tested.
- Runtime verification status: **PARTIAL**.
- Business approval: **NOT ESTABLISHED**.
- Launch readiness: **NOT ESTABLISHED**.

### Completion assessment

- Task type: **RESEARCH / DOCUMENTATION**.
- Task / issue: **VGN-55**, ACF groups, field schemas, relationship state and readers.
- Assessment role: **Research author**.
- Acceptance criteria: **PASS 5/6**. PASS: exact twelve sections; full group and field schema inventory; Article/Topic/Person/Course/Product/Event relationship map; read-only aggregate population and broken-edge findings; source/runtime parity review. UNVERIFIED: population and consumers for the duplicate Broadcast taxonomy pairs, plus the `dorp` tag provider, because the final aggregate query failed.
- Implementation Score: **N/A**, no implementation was requested.
- Task Execution Score: **9/10**. The schema and principal relationship evidence is complete. One point was deducted because the final duplicate-taxonomy and `dorp` follow-up did not return data.
- Confidence: **HIGH** on the schema inventory, observed relationship counts, and source/runtime hashes. The unverified taxonomy assignments, tag ownership, and lack of rendered output limit conclusions about visible behavior. The coordination brief specifies a qualitative confidence scale for research, so no numerical confidence is assigned here.
- Verification Status: **PARTIALLY_VERIFIED**.
- Evidence: repository SHA `454346d9e6a2c36864a4bbd944e52596f16e09c1`; ACF read abilities named above; Bricks template scan of 32 templates; successful source/runtime `diff -qr` with no output; matched Perks JSON hashes; read-only runtime aggregate counts; failed follow-up WP-CLI query returned “Error establishing a database connection.” Report artifact: `docs/evaluation/04_ACF_RELATIONSHIPS_v0.1.0.md`.
- Known gaps and highest-risk assumption: The product and event templates consume canonical relation adapters while legacy fields still have data. The code implies a blank Product People mode returns no people, but this was not browser-rendered. The data owner must decide whether to migrate the legacy edges and how to treat the stale Course reverse edge.
- Next action: The project owner should decide the source authority for DB-only groups and runtime-only snippets, reconcile the ACF source pointer documents, and authorize any separate data migration only after consumer and rollback review.

### Read-only commands and artifact checks

Observed source/runtime checks:

```text
git rev-parse HEAD
454346d9e6a2c36864a4bbd944e52596f16e09c1

readlink -f '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public/wp-content/themes/bricks-child/acf-json'
/home/jcschoeman96/projects/runtime/vgnova-acf-sources/c450475971de722378a1658157a3a24838e467f7/site-code/acf-json

git -C /home/jcschoeman96/projects/runtime/vgnova-acf-sources/c450475971de722378a1658157a3a24838e467f7 rev-parse HEAD
c450475971de722378a1658157a3a24838e467f7

git -C /home/jcschoeman96/projects/runtime/vgnova-acf-sources/c450475971de722378a1658157a3a24838e467f7 status --porcelain=v1 --untracked-files=all
no output, clean worktree

diff -qr site-code/acf-json '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public/wp-content/themes/bricks-child/acf-json'
no output, files matched

sha256sum voelgoed-member-perks/acf-json/group_vgmp_options.json voelgoed-member-perks/acf-json/group_vgmp_perk_details.json '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public/wp-content/plugins/voelgoed-member-perks/acf-json/group_vgmp_options.json' '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public/wp-content/plugins/voelgoed-member-perks/acf-json/group_vgmp_perk_details.json'
4206527fddd2536ae9527fbecc78f64f7976fa0292db346c4884f28ab563192b  voelgoed-member-perks/acf-json/group_vgmp_options.json
d2fd83ee4069ee7d6986e87bf4c2449a9612e9042f4ff02b7b332f75f1c186f4  voelgoed-member-perks/acf-json/group_vgmp_perk_details.json
4206527fddd2536ae9527fbecc78f64f7976fa0292db346c4884f28ab563192b  /home/jcschoeman96/Local Sites/voelgoed-novamira/app/public/wp-content/plugins/voelgoed-member-perks/acf-json/group_vgmp_options.json
d2fd83ee4069ee7d6986e87bf4c2449a9612e9042f4ff02b7b332f75f1c186f4  /home/jcschoeman96/Local Sites/voelgoed-novamira/app/public/wp-content/plugins/voelgoed-member-perks/acf-json/group_vgmp_perk_details.json
```

No tests, code changes, WordPress writes, ACF/Bricks edits, migrations, snippet writes, commits, pushes, or Plane updates were performed.
