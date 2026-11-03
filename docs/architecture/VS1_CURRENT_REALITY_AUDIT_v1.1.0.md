# VS1 current reality audit v1.1.0

| Audit item | Value |
|---|---|
| Status | Fresh read-only Plan A rebaseline |
| Version | 1.1.0 |
| Audit date | 2026-11-03 |
| Source base | `origin/main` = `5413d3e20a7117ba0da1a4363143b18dcf9e6726` |
| Work branch | `feat/vs1-discovery-chain` |
| Runtime | VG Nova Local, `http://localhost:10039/` |

This successor updates only evidence that could have changed since v1.0.0. The October audit remains historical. Plan A uses the approved NF-PERSON relationship implementation. It does not reopen Person architecture or the separate `medewerker` migration.

## Repository and merge verification

PR #78 is merge commit `8f32fe3dbc94d6e02c4f11227e90e91ff4b9053a`, with parents `c450475971de722378a1658157a3a24838e467f7` and `cb2bedd62e13c95bb8febd44e2375936912ae8e3`. After PR #79 merged, current `origin/main` advanced to `5413d3e20a7117ba0da1a4363143b18dcf9e6726`. PR #79 changed only `AGENTS.md` to route Bricks and frontend work through the local Bricks MCP. The Platform Core source is unchanged.

Post-merge [workflow `41613026611`](https://github.com/JCSchoeman96/vgnova/actions/runs/41613026611) completed successfully on that exact merge SHA. Dashboard checks, Canonical source checks, and VG Nova Platform Gate all succeeded.

Current-main [workflow `41620681843`](https://github.com/JCSchoeman96/vgnova/actions/runs/41620681843) also completed successfully on exact SHA `5413d3e20a7117ba0da1a4363143b18dcf9e6726`. Dashboard checks, Canonical source checks, Platform Core artifact publication, and VG Nova Platform Gate all succeeded.

## Runtime identity and Platform Core parity

The Bricks MCP endpoint returned HTTP 401 from this workspace, and no Bricks MCP tool is connected here. The repository permits WP-CLI or the local shell as a read-only fallback, so runtime inspection used WP-CLI with the Local PHP 8.5.12 binary and Local database socket. No credentials were read or printed.

| Item | Current runtime |
|---|---|
| Home and site URL | `http://localhost:10039/` |
| WordPress root | `/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public/` |
| Database name | `local` |
| WordPress / PHP | `7.1.4` / `8.5.12` |
| Theme | Bricks Child Theme `1.1`; parent Bricks `2.4.3` |
| ACF Pro | `6.8.1` |
| WooCommerce / Subscriptions | `11.2.1` / `8.8.1` |
| PMPro / PMPro Woo integration | `3.8.6` / `1.10.1` |
| Masteriyo | `3.4.3` |
| Tickera | `3.5.7.4` |
| FluentCRM / SMTP / snippet manager | `3.2.0` / `2.4.1` / `10.56` |
| Voelgoed Member Perks | `0.18.0` |
| Voelgoed Platform Core | `0.1.0`, active |

The canonical builder and verifier produced and verified the Platform Core artifact from this audit's base SHA. The artifact contains 56 files and has tree hash `fe1e6ccb58580929740411dd8715fa725da89c565e4359fdcbec1a0d101141d4`. A read-only comparison of every archived file against the active plugin found 56 runtime files, with no missing, extra, or differing files.

```text
SOURCE_RUNTIME_PARITY=PASS
PE008_UNRESOLVED_DEPLOYMENT=NO
```

The PE-008 status command reported no in-progress deployment. No deployment or runtime mutation was run for this audit.

## Plan A runtime state

### Home and canonical Topics

`content_topic` is public, appears in REST, and has the canonical rewrite root `/onderwerp/`. Runtime has 55 terms and seven roots: `nuwe-jy`, `geloof`, `self`, `werk-geld`, `ouerskap`, `huwelik`, and `mensstories`.

The homepage remains the posts index (`show_on_front=posts`, `page_on_front=0`). There are no WordPress navigation menus. Header template 77 contains a visible "Nuwe Jy" anchor whose current `href` is `#`; it does not lead to a canonical Topic URL. The Home renders the single published standard post listed below.

The canonical Nuwe Jy URL, `http://localhost:10039/onderwerp/nuwe-jy/`, returned HTTP 200 and the correct page title. Its current term has zero Article assignments.

```text
HOME_TO_TOPIC_GATE=GAP
TOPIC_TO_ARTICLE_GATE=GAP
```

### Articles

The Local runtime has one published standard post: ID `375`, "Integer maximus sapien in bibendum." It has no `content_topic`, `content_category`, or NF-PERSON source-field values. [Post 375 provenance audit](POST_375_PROVENANCE_AUDIT.md) classifies its pseudo-Latin content as `DEMO_OR_IMPORT_PLACEHOLDER / NEEDS_PROVENANCE`. It is not representative editorial data.

The public Article URL returned HTTP 200. Its current render did not show Lynette Beer or another Person relationship.

### Canonical Person and relationship implementation

Runtime Person `1705`, Lynette Beer, is published and has `person_type` slugs `mentor` and `kenner`. The [current owner decision](PERSON_PROFILE_OWNER_DECISIONS_v1.0.0.md) approves her identity and those classifications only. Her profile content, image, Topic assignments, and relationships remain unapproved. All seven reverse relationship fields are empty.

The active ACF groups match the tracked NF-PERSON field contract in [Article People JSON](../../site-code/acf-json/group_vg_article_people.json), [Course People JSON](../../site-code/acf-json/group_vg_course_people.json), and [Person Related Content JSON](../../site-code/acf-json/group_vg_person_related_content.json). The runtime activation evidence is in [Native Person Relationships Runtime Activation v1.0.0](NATIVE_PERSON_RELATIONSHIPS_RUNTIME_ACTIVATION_v1.0.0.md).

| Source fields | Derived Person fields |
|---|---|
| Article `primary_author`, `additional_authors`, `expert_contributors`, `reviewed_by` | `person_articles_primary_author`, `person_articles_additional_author`, `person_articles_expert_contributor`, `person_articles_reviewed_by` |
| Course `lead_instructor`, `additional_instructors`, `experts` | `person_courses_lead_instructor`, `person_courses_additional_instructor`, `person_courses_expert` |

The source ACF Post Object fields target canonical `person` records and are bidirectional to the listed derived ACF Relationship fields. No extra author field, WordPress-user mapping, or aggregate relationship was found or added.

Current Bricks runtime resources are Article template 372, canonical Person Single template 1734, and Masteriyo Single Course template 1733. Their 4 Article-to-Person loops, 7 Person-to-Article/Course loops, and 3 Course-to-Person loops use native Posts queries. Each relationship section has an `empty_not` ACF condition, preventing an empty include from exposing unrelated records. The public Article, Person, and Course checks showed no unrelated Person, Article, or Course cards.

The Person Single Course loops use `orderby=date`, `order=DESC`. Masteriyo's saved and effective archive order is also `date DESC`.

```text
ARTICLE_TO_PERSON_AUTHORITY=ACF_ROLE_FIELDS_WITH_BIDIRECTIONAL_PERSON_FIELDS
ARTICLE_TO_PERSON_ARCHITECTURE=PASS
ARTICLE_TO_PERSON_RUNTIME_CONSUMER=PASS
PERSON_TO_COURSE_AUTHORITY=ACF_ROLE_FIELDS_WITH_BIDIRECTIONAL_PERSON_FIELDS
PERSON_TO_COURSE_ARCHITECTURE=PASS
PERSON_TO_COURSE_RUNTIME_CONSUMER=PASS
PERSON_TO_COURSE_ORDER=date_DESC
```

The current database has no representative Article-to-Person or Person-to-Course relationship rows. The routes are present, but the actual Golden Journey cannot be demonstrated with approved content.

### Masteriyo Courses

There are five published Courses and one draft. None has a `content_topic` assignment or a populated NF-PERSON instructor field. None has a linked Woo Product. Course titles and runtime IDs are evidence only; the published records have no confirmed editorial provenance. The draft "Untitled Course" is not a candidate.

The public canonical Course route `/leer/mindfulness-vroue/` returned HTTP 200 and did not show Lynette or an unrelated Person relationship.

### Featured Content Manager

The existing runtime ACF group `group_vg_featured_content_manager` still has eight Post Object options. The current values are `featured_nuwe_jy` → Event 542, `featured_geloof` → Course 534, and `featured_self` → Product 531; the other five values are empty. These targets are not Topic-to-Article or Person relationships. The [v1.0.0 FCM preflight](VS1_FCM_PREFLIGHT_v1.0.0.md) ruling remains unchanged.

The active runtime snippet `2-vg-article-dynamic-tags.php` still chooses its first `content_category` term and maps that term to an FCM option. It validates that the target is published and is a Product, Course, or Event. No current FCM change was needed because Plan A is blocked before implementation.

```text
FCM_RULING=ADAPT
FCM_RUNTIME_SELECTOR=content_category
NEW_COMPETING_FEATURE_SYSTEM_AUTHORIZED=NO
```

The v1.0.0 FCM ruling remains current. This audit confirms its runtime state; it does not create an FCM successor or authorize an FCM mutation.

## Frontend and mutation boundary

No Home, Topic, Article, Person, or Course Bricks resource changed. The existing Hallmark review recorded 31 page-level FAIL findings and 0 WARN findings across Article, Person, and Course pages. This rebaseline did not rerun Hallmark because it changed no visual surface. `FRONTEND_COMPLETION=NOT_CLAIMED`.

```text
PE007_RUNTIME_MUTATIONS=0
PE008_DEPLOYMENTS=0
ACF_MUTATIONS=0
BRICKS_MUTATIONS=0
WORDPRESS_CONTENT_MUTATIONS=0
```

## Current conclusion

```text
REPO_BASELINE=5413d3e20a7117ba0da1a4363143b18dcf9e6726
SOURCE_RUNTIME_PARITY=PASS
HOME_TO_TOPIC_GATE=GAP
TOPIC_TO_ARTICLE_GATE=GAP
ARTICLE_TO_PERSON_ARCHITECTURE=PASS
PERSON_TO_COURSE_ARCHITECTURE=PASS
REPRESENTATIVE_DATA_GATE=BLOCKED
PLAN_A_GATE=BLOCKED
VS1_MASTER_GATE=BLOCKED
```

The exact remaining data decision is recorded in [the v1.1.0 representative-data contract](VS1_REPRESENTATIVE_DATA_CONTRACT_v1.1.0.md). This rebaseline does not change commerce, email, analytics, event, product, or legacy Person migration gates.
