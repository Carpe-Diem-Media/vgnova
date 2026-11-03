# VS1 discovery authority gate v1.1.0

| Audit item | Value |
|---|---|
| Status | BLOCKED BY REPRESENTATIVE DATA |
| Version | 1.1.0 |
| Audit date | 2026-11-03 |
| Repository base | `8f32fe3dbc94d6e02c4f11227e90e91ff4b9053a` |
| Runtime | VG Nova Local, `http://localhost:10039/` |

This successor replaces only stale discovery findings in v1.0.0. Article-to-Person and Person-to-Course architecture decisions are settled by NF-PERSON. The implementation below uses those approved fields, templates, and native queries. It does not reopen Person architecture or migrate legacy `medewerker` records.

## Gate summary

```text
DISCOVERY_GATE=BLOCKED
HOME_TO_TOPIC_GATE=GAP
TOPIC_TO_ARTICLE_AUTHORITY=content_topic
TOPIC_TO_ARTICLE_GATE=GAP
ARTICLE_TO_PERSON_AUTHORITY=ACF_ROLE_FIELDS_WITH_BIDIRECTIONAL_PERSON_FIELDS
ARTICLE_TO_PERSON_ARCHITECTURE_GATE=PASS
ARTICLE_TO_PERSON_RUNTIME_CONSUMER_GATE=PASS
ARTICLE_TO_PERSON_DATA_GATE=BLOCKED
PERSON_TO_COURSE_AUTHORITY=ACF_ROLE_FIELDS_WITH_BIDIRECTIONAL_PERSON_FIELDS
PERSON_TO_COURSE_ARCHITECTURE_GATE=PASS
PERSON_TO_COURSE_RUNTIME_CONSUMER_GATE=PASS
PERSON_TO_COURSE_DATA_GATE=BLOCKED
PERSON_TO_COURSE_ORDER=date_DESC
REPRESENTATIVE_DATA_GATE=BLOCKED
PERSON_PROGRAMME_DEPENDENCY=NO_FOR_PLAN_A
FCM_RULING=ADAPT
SOURCE_RUNTIME_PARITY=PASS
```

There is no current architecture decision for Article-to-Person or Person-to-Course. The discovery journey is still blocked because the runtime has no approved Topic-to-Article-to-Person-to-Course record path and Home has no working canonical Topic destination.

## Native capability review

| Capability | Current provision | Plan A result |
|---|---|---|
| WordPress core | Public `content_topic` taxonomy archives and native post taxonomy membership | Use for Topic routes and Article membership. Nuwe Jy archive resolves, but has no Articles. |
| Bricks | Native Posts queries, ACF dynamic includes, templates, and element conditions | Existing Article, Person, and Masteriyo templates already render the NF-PERSON roles. The Home has no working Topic destination. |
| ACF Pro | Source Post Object role fields and native bidirectional Person Relationship fields | Sufficient for Article-to-Person and Course-to-Person relationship storage. |
| Masteriyo | Owns `mto-course`, its Course lifecycle, Bricks single template integration, and archive order | Sufficient for Course presentation and date-descending order. No current Course is linked to a canonical Person. |
| Featured Content Manager | Existing ACF option store and runtime target resolver for Article feature cards | Reuse its current target store. Its `content_category` selector needs a later bounded adaptation to `content_topic` where Plan A uses it. It does not provide Home-to-Topic navigation. |
| Custom query/render code | No native limitation demonstrated | `CUSTOM_DEVELOPMENT=NOT_AUTHORIZED` |

## Edge results

| Edge | Authority | Current runtime evidence | Result |
|---|---|---|---|
| Home → Topic | Canonical roots in `content_topic` | Home is the posts index. Header 77 has no WordPress menu; its "Nuwe Jy" anchor points to `#`. | `GAP` |
| Topic → Article | Native WordPress taxonomy membership in `content_topic` | `/onderwerp/nuwe-jy/` resolves and shows the Topic title. Nuwe Jy has zero Article assignments. The only published standard post is ineligible placeholder content. | `GAP` |
| Article → Person | Article ACF fields `primary_author`, `additional_authors`, `expert_contributors`, and `reviewed_by`; derived Person reverse fields | Runtime Article template 372 has four native Posts loops using those ACF values. Each role section has an `empty_not` guard. Post 375 has no source value, and its public page shows no Person. | `ARCHITECTURE PASS; DATA BLOCKED` |
| Person → Course | Course ACF fields `lead_instructor`, `additional_instructors`, and `experts`; derived Person reverse fields | Runtime Person Single 1734 has three native Course loops with guards and `date DESC` order. Masteriyo's effective order is `date DESC`. No current Course or Person role value exists. | `ARCHITECTURE PASS; DATA BLOCKED` |

The public empty Article, Person, and Course renders returned no unrelated relationship cards. The saved Bricks sections guard each dynamic `post__in` query when its ACF relationship is empty. The checked runtime loops do not introduce custom query infrastructure.

## NF-PERSON authority

The source ACF groups are `group_vg_article_people`, `group_vg_course_people`, and `group_vg_person_related_content`. Runtime ACF exposes the same role names, target post types, and bidirectional targets as the tracked Local JSON. Article and Course source fields remain authoritative; Person reverse fields are derived.

The latest runtime activation evidence records lifecycle tests for draft, private, publish, trash, untrash, and republish transitions using disposable QA records. This Plan A rebaseline did not alter those fields or repeat destructive lifecycle tests against representative content. Public Bricks queries use published records; no current relationship values exist to exercise a live editorial path.

## FCM and topic boundary

The current FCM keeps its existing eight ACF option fields and Article card consumers. Its runtime snippet still chooses a feature by the first `content_category` term and validates published Product, Course, or Event targets. `featured_nuwe_jy` currently points to an Event, not a Topic or Article relationship.

```text
FCM_RULING=ADAPT
FCM_CURRENT_SELECTOR=content_category
CONTENT_TOPIC_REMAINS_CANONICAL=YES
CONTENT_CATEGORY_REMAINS_LEGACY=YES
LEEFSTYL_IS_A_CANONICAL_TOPIC=NO
NEW_COMPETING_FEATURE_SYSTEM_AUTHORIZED=NO
```

The selector was not changed because the representative-data gate blocks Plan A. The current Home-to-Topic UI must use canonical Topic terms directly, not FCM or `content_category`.

## Person programme boundary

Plan A uses canonical Person Single template 1734 and the NF-PERSON loops in templates 372 and 1733. It does not edit legacy templates 772 or 773, change `medewerker`, migrate legacy relationships, or alter profile URLs.

```text
PERSON_PROGRAMME_DEPENDENCY=NO_FOR_PLAN_A
LEGACY_MEDEWERKER_CONSUMERS_CHANGED=NO
```

## Decision

```text
STALE_ARTICLE_TO_PERSON_DECISION=SUPERSEDED_BY_NF_PERSON
STALE_PERSON_TO_COURSE_DECISION=SUPERSEDED_BY_NF_PERSON
DISCOVERY_GATE=BLOCKED
PLAN_A_IMPLEMENTATION=NOT_STARTED_DATA_GATE_BLOCKED
```

No runtime, Bricks, ACF, content, FCM, or Platform Core mutation was made. See [the current reality audit](VS1_CURRENT_REALITY_AUDIT_v1.1.0.md) and [representative-data contract](VS1_REPRESENTATIVE_DATA_CONTRACT_v1.1.0.md) for evidence and the smallest owner decision needed to continue.
