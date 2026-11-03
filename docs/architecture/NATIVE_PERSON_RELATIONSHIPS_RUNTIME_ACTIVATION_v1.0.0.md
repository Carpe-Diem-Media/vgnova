# Native Person Relationships Runtime Activation v1.0.0

## Result

```text
SOURCE_BASE_SHA=c450475971de722378a1658157a3a24838e467f7
ACTIVE_ACF_GENERATION=c450475971de722378a1658157a3a24838e467f7
ARTICLE_ACF_SYNC=PASS
COURSE_ACF_SYNC=PASS
BACKFILL_REQUIRED=NO
BEHAVIORAL_QA=PASS
ADMIN_FIELD_HIDE_QA=PASS
NATIVE_BRICKS_RELATIONSHIPS=IMPLEMENTED
FRONTEND_LIFECYCLE_QA=PASS
MASTERIYO_EFFECTIVE_COURSE_ORDER=date_DESC
ACTIVATION_SEQUENCE_DEVIATION=RECORDED
PRODUCT_PERSON_CARD_REGRESSION=PASS
PE008_HEALTH=PASS
HALLMARK_PAGE_REVIEW=31_FAIL_0_WARN
PRODUCT_SINGLE_HALLMARK=20_FAIL_1_REFLEX_0_WARN
FRONTEND_COMPLETION=NOT_CLAIMED
```

The native ACF relationship contract and its Bricks presentation are active in the Local VGNova runtime. The relationship, lifecycle, and Product Person Card regression checks passed. Hallmark still reports page-level failures in shared site systems, so this record does not mark the full frontend pages complete.

## Authority and native-capability review

The implementation follows the approved architecture in [Native Person Relationships v0.1.0](../superpowers/specs/2026-11-01-native-person-relationships_v0.1.0.md) and amendments [v0.1.1](../superpowers/specs/2026-11-02-native-person-relationships-amendment_v0.1.1.md) and [v0.1.2](../superpowers/specs/2026-11-02-native-person-relationships-amendment_v0.1.2.md). Those decisions make Article/Course ACF fields authoritative, the Person reverse fields derived, and Bricks responsible for rendering.

| Native capability | What it provides here | Result |
|---|---|---|
| WordPress core | Post statuses, lifecycle changes, and native Posts queries | Sufficient; no custom publication validation was added |
| ACF Pro 6.8.1 | Post Object source fields, Relationship reverse fields, bidirectional updates, Local JSON | Sufficient; source fields remain the only editorial authority |
| Bricks 2.4.3 | Native Posts Query Loops, dynamic includes, element conditions, templates, and components | Sufficient; no custom query type or relationship renderer was added |
| Masteriyo 3.4.3 | Owns `mto-course` and supplies its Bricks integration/template path | Sufficient after enabling the installed Bricks Integration add-on |
| Existing approved component | `Product Person Card` renders canonical Person profiles | Reused for Article/Course-to-Person roles |

No custom relationship table, repository, REST endpoint, index, reconciler, SQL query, or Bricks query type was added. Existing Platform Core only hides the seven derived Person fields in the Person editor.

## ACF activation and relationship contract

The active Local ACF JSON generation is the immutable generation at `c450475971de722378a1658157a3a24838e467f7`. `group_vg_article_people` and `group_vg_course_people` were synchronized successfully. The unrelated Member Perks groups `group_vgmp_perk_details` and `group_vgmp_options` were not synchronized.

The seven source fields had zero relationship rows before activation. No source values needed migration, so no backfill ran.

| Authoritative source field | Derived Person field |
|---|---|
| Article `primary_author` (`field_vg_article_primary_author`) | `person_articles_primary_author` (`field_vg_person_articles_primary_author`) |
| Article `additional_authors` (`field_vg_article_additional_authors`) | `person_articles_additional_author` (`field_vg_person_articles_additional_author`) |
| Article `expert_contributors` (`field_vg_article_expert_contributors`) | `person_articles_expert_contributor` (`field_vg_person_articles_expert_contributor`) |
| Article `reviewed_by` (`field_vg_article_reviewed_by`) | `person_articles_reviewed_by` (`field_vg_person_articles_reviewed_by`) |
| Course `lead_instructor` (`field_vg_course_lead_instructor`) | `person_courses_lead_instructor` (`field_vg_person_courses_lead_instructor`) |
| Course `additional_instructors` (`field_vg_course_additional_instructors`) | `person_courses_additional_instructor` (`field_vg_person_courses_additional_instructor`) |
| Course `experts` (`field_vg_course_experts`) | `person_courses_expert` (`field_vg_person_courses_expert`) |

Each source is an ACF Post Object targeting `person`, returning IDs, allowing `publish`, `draft`, and `private` People, with native bidirectional updates to exactly its listed target. `primary_author` and `lead_instructor` are single-value fields; the other source fields allow multiple People. Each target is a multiple ACF Relationship field returning IDs, with no reverse bidirectional owner. The source remains authoritative.

## Behavioural and editor QA

Disposable records only were used. The storage QA created Person, Article, and Course fixtures 1726–1728 and passed the seven role mappings. It verified:

- One Person can hold all seven roles across the same Article and Course.
- Clearing a source role removes that source from the matching derived field; adding it again restores the value.
- Repeated source saves do not add duplicate reverse IDs.
- Person transitions through `private`, `publish`, and `draft` preserve all seven reverse fields.
- Article and Course transitions through `draft`, `private`, and `publish` preserve authoritative and derived values.
- A normal Person ACF save submitting only the visible title field (`field_vg_person_professional_title`) preserves all seven derived relationships.

`PersonRelatedContentFields` in Platform Core registers exactly seven field-specific filters. The isolated screen harness passed 37 checks: each reverse field is hidden on Person add/edit screens and remains available on Article screens, outside the Person add/edit screen, and outside admin. This harness simulates only the WordPress screen state read by the helper. The prior full WP-CLI `--context=admin` attempt failed in WooCommerce before reaching these assertions (`wc_get_page_screen_id()` was undefined); that is a harness bootstrap failure. WooCommerce was not changed or disabled.

No publication validation was added. No changes were made to canonical Person 1705.

## Native Bricks implementation

Runtime template and component state is database-managed Bricks configuration. The repository PR contains this evidence record; it does not export the Local Bricks database state as source files.

| Bricks resource | Runtime ID | Change |
|---|---:|---|
| Article Single | 372 | Added four conditional Person role sections and native Posts loops; removed the WordPress-user byline so the ACF-selected Person is the article attribution |
| Person Single | 1734 | Added the canonical Person profile header and seven conditional reverse-role sections; revisions 1778 and 1820 hold the focus and width updates |
| Masteriyo Single Course | 1733 | Added three conditional Person role sections to the native `masteriyo-single-course` template; revision 1772 |
| Product Person Card component | `dc8611` | Reused for source-to-Person loops; updated its profile action text color and focus-visible rule |

The `Product Person Card` button is element `p7bx06`; its link continues to use the canonical Person permalink. Person-to-content loops use the existing Article card styling and dynamic post title, excerpt, and date. The legacy Blog Post Card was not reused because it adds WordPress-user attribution. Each Person role has its own section and query; no generic aggregate relationship query was created.

### Article and Course to Person

Each source role has a native Bricks Posts Query Loop with `objectType=post`, `post_type=person`, the matching source ACF dynamic value in `post__in`, `posts_per_page=-1`, `orderby=post__in`, and the existing `Product Person Card` component. The saved loop settings do not include a `post_status` key. Bricks 2.4.3 applies `post_status=publish` while preparing a public Posts query. The dynamic includes are `{acf_primary_author}`, `{acf_additional_authors}`, `{acf_expert_contributors}`, `{acf_reviewed_by}`, `{acf_lead_instructor}`, `{acf_additional_instructors}`, and `{acf_experts}` on their matching templates.

Each whole role section has a native Bricks condition requiring its source ACF field to be non-empty. This guard is required because an empty `post__in` can otherwise remove the include restriction and return unrelated People.

### Person to Article and Course

Person Single contains four Article loops (`post`) and three Course loops (`mto-course`). Each uses `objectType=post`, the corresponding derived Person ACF field as the dynamic `post__in` include, `posts_per_page=-1`, and date descending order (`orderby=date`, `order=DESC`). The saved loop settings do not include a `post_status` key. Each section is conditioned on its own reverse ACF field being non-empty.

Inspection of the installed Bricks 2.4.3 Posts query (`themes/bricks/includes/query.php`) confirmed that it resolves dynamic include values and sets `post_status=publish` at query time for public Posts queries. Anonymous public-render checks confirmed draft, private, and trashed content is not returned.

### Masteriyo integration and empty-field protection

Masteriyo’s Bricks Integration add-on was installed but inactive. It was enabled through Masteriyo’s add-on API under PE-007. The existing Masteriyo setting now enables the custom Bricks template with source `bricks` and template ID `1733`. The template’s post type is `masteriyo-single-course`. Course fixtures were created through the Masteriyo course model and store, not by raw post insertion, for frontend rendering checks.

Masteriyo 3.4.3 stores site settings in the WordPress `masteriyo_settings` option. A fresh read-only WP-CLI inspection through the approved Local socket found explicit `course_archive.display.order_by=date` and `course_archive.display.order=DESC` values in that option. A second WP-CLI read confirmed the effective values returned by `masteriyo_get_setting()` are also `date` and `DESC`.

The installed resolver in `learning-management-system/includes/Helper/core.php:4002-4012` reads `masteriyo_settings` and overlays the saved value on plugin defaults with `array_replace_recursive()`. The installed defaults at lines 4299-4300 are also `date` and `DESC`. `learning-management-system/includes/FrontendQuery.php:534-535` uses the saved settings when the archive request has no explicit `order` or `orderby` query argument. An active site-level order is therefore present and matches the plugin default. The effective Course listing order is **date descending**. The three Person-to-Course loops already use `orderby=date`, `order=DESC`, so no Bricks runtime change was required.

The seven Person sections and all seven source-side sections use the required non-empty ACF condition. Empty-fixture pages returned no unrelated Person, Article, or Course cards.

## Activation sequencing deviation

The original v0.1.0 specification, section 7, required a disposable normal Person profile-save preservation check before ACF synchronization. Actual runtime chronology was different: reverse/source ACF activation occurred, `group_vg_article_people` and `group_vg_course_people` were synchronized, and the successful normal Person profile-save preservation QA was completed afterward on disposable fixtures using the visible `person_professional_title` field (`field_vg_person_professional_title`). All seven reverse values were preserved.

No representative Person was modified; Person 1705 remained untouched. No rollback or re-sync was performed solely to reproduce the historical ordering. This sequencing deviation is retained here explicitly rather than rewriting the execution history.

## Bricks save details and width correction

Bricks 2.4.3 rejects newly-authored element IDs unless they contain six characters. IDs on new Person and Course template elements were normalized through Bricks’ native element-save API. Article Single already contained two seven-character legacy IDs, `vgah09a` and `vgah09b`; Bricks rejected a whole-tree save of that existing tree. Under PE-007, only the 20 new Article relationship element IDs were normalized through WordPress `update_post_meta`, while those two legacy IDs remained unchanged. No raw SQL was used.

The first Hallmark pass flagged the new Person template’s wide hero/container at 1920 px. The ACSS token `--content-width-safe` resolves from the existing 1366 px `--content-width` and the responsive gutter. A version-checked Bricks native batch update set `_widthMax=var(--content-width-safe)` on the Person profile and all seven relationship containers. The update applied to elements `nfp002`, `nfp02x`, `npg02x`, `nph02x`, `npi02x`, `npj02x`, `npk02x`, and `npl02x`; Bricks created revision 1820. The follow-up Hallmark run no longer reported the Person container finding.

The Person title-link focus treatment uses ACSS focus width and offset tokens and the existing `--text-dark` token. A browser diagnostic across the role links measured a 2 px black ring with a 2 px offset and 21:1 contrast against the rendered white cards. The final Hallmark run did not report a relationship-card focus failure. Product Person Card focus and contrast findings also cleared after its Bricks component update.

## Frontend lifecycle QA

The final anonymous render QA after the width correction passed **68 checks across 22 HTTP requests**. It used six disposable fixtures: Person, empty Person, Article, empty Article, Course, and empty Course. The Masteriyo courses used the supported course model and open access mode.

The checks verified source-role cards and canonical Person links on Article/Course pages; role-specific Article/Course links on Person Single; all empty-role guards; and these lifecycle outcomes:

- Published People appear in source loops. Private and draft People are omitted while relationships remain stored. Republishing restores visibility without rewriting relationships.
- Published Articles and Courses appear on Person Single. Draft, private, and Trash items are omitted while source and reverse relationships remain stored. Untrash and republishing restores visibility where WordPress retains the source values.
- Hard deletion of every disposable record removed the post and all its postmeta.

The latest lifecycle run used fixture IDs 1821–1826. All six were hard-deleted; each had `post_exists=false` and zero postmeta rows.

## Product Person Card regression

Product Single Template 124 uses Product Person Card component `dc8611` as the direct child of its canonical Product People loop. A read-only runtime inventory found no published canonical Product–Person association suitable for this check, so the regression used disposable native fixtures: WooCommerce Products 1833 and 1836, canonical People 1832 and 1835, and native Product Contributions 1834 and 1837. Product People resolved each disposable Person through the existing `ProductContributionService` path. No existing Product or Person was edited.

Anonymous Product Single renders were intercepted by WooCommerce Coming Soon. Each check used the established request-scoped `woocommerce_coming_soon_exclude` filter restricted to a GET of the exact disposable Product path with its QA token. The filter file was removed after each check. `woocommerce_coming_soon=yes` and `woocommerce_store_pages_only=yes` remained unchanged.

The Product Single render showed the canonical Person title, ACF professional title, and `Bekyk profiel` action. The action URL matched the Person permalink. Keyboard Tab reached the action with `:focus-visible`; the computed outline was a solid 3 px black ring. Pressing Enter opened the canonical `/mense/{slug}/` Person page with the matching profile title. At the rendered 1280×800 viewport, the Product main area, relationship section, and 282 px card had no horizontal overflow. The browser reported zero console entries and zero failed network requests. The PHP error log size and SHA-256 matched its pre-check values. Hallmark separately reported the known shared-header `resizeTimeout` redeclaration; it was not attributed to `dc8611`.

Both disposable fixture sets were hard-deleted under PE-007. Post-check verification found no fixture posts or postmeta, no temporary bypass file, and unchanged Coming Soon options. The Bricks Product template and component were not modified during this regression pass.

## Hallmark review and remaining page findings

Hallmark v2.0.0 reviewed populated disposable Person, Article, and Course pages after the width correction. It rendered 15 screenshots across the three routes and reported **31 FAIL, 0 WARN**. For the final focus check, a temporary copy of Hallmark added read-only diagnostics around its focus measurement; the gate logic and render checks were unchanged. It reported no failure on the new relationship role cards or Person content width.

The required Product Single `sloplint --scope component --url … --render` pass rendered five screenshots and reported **20 FAIL, 1 REFLEX, 0 WARN** across the Product page. None of the reported selectors was the Product Person Card or its `Bekyk profiel` action. Findings were on surrounding header text and focus states, WooCommerce quantity/add-to-cart controls, the existing share and accordion controls, and the known shared-header `resizeTimeout` redeclaration. These page-level findings are recorded for separate follow-up and were not changed in this PR, as requested. The relationship-specific Product Person Card remained clear in both the browser regression and this lint run.

Remaining failures are in surrounding page systems:

- Shared header: skip-link and color-scheme-control focus contrast; skip-link, nav description, phone link, and primary CTA text contrast; and a page-load JavaScript error from the existing `resizeTimeout` redeclaration.
- Masteriyo course shell: tab, enrollment action, and empty-lesson text contrast; the course heading crosses the 1280×800 fold; the course hero exceeds the 1920×1080 screen; and the enrollment action wraps at 768 px.

The relationship feature is implemented and passed its data, editor, empty-state, status, and public-render checks. The full affected pages remain short of Hallmark’s floor until those shared header and Masteriyo issues are resolved. This programme does not change the unrelated header snippet or redesign Masteriyo’s course shell.

## Health, cleanup, and mutation record

The canonical PE-008 runtime health check passed before and after the behavioral/frontend QA, after the original Hallmark fixture cleanup, and again after the post-review Product regression cleanup. Each run returned `ok=true`; all nine checks passed: `lifecycle_registered`, `no_php_diagnostics`, `no_unexpected_output`, `plugin_class_loaded`, `plugin_entrypoint_active`, `runtime_identity`, `session_valid`, `site_name_available`, and `wordpress_bootstrapped`.

All runtime mutations ran as children of `site-code/bin/vgnova-runtime-mutate.php` under PE-007. The programme fixture groups were 1726–1728, 1757–1762, 1765–1767, 1779–1781, 1782–1819, 1821–1826, 1829–1831, and the post-review Product regression fixtures 1832–1837. The earlier read-only scan found no remaining posts or postmeta for the first 62 fixtures. The six post-review fixtures were separately checked after deletion and each had `post_exists=false` with zero postmeta rows. The first Product browser runner stopped before cleanup; a fresh PE-007 session verified the three fixture owners and contribution endpoints, then hard-deleted them. No fixture targeted Person 1705.

The approved ACF group syncs were already complete when this implementation resumed; they were not repeated during this post-review pass. The earlier relationship implementation changed the listed Bricks templates and component and enabled the Masteriyo Bricks integration add-on under PE-007. This post-review pass made no ACF or Bricks setting changes; it only created and removed the disposable Product fixtures and temporary scoped bypass under PE-007. No Member Perks configuration, relationship backfill, custom relationship storage, or non-disposable content was changed. No direct SQL write or merge was performed.
