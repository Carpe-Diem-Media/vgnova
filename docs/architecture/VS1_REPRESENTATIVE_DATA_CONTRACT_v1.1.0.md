# VS1 representative-data contract v1.1.0

| Audit item | Value |
|---|---|
| Status | BLOCKED |
| Version | 1.1.0 |
| Audit date | 2026-11-03 |
| Repository base | `5413d3e20a7117ba0da1a4363143b18dcf9e6726` |
| Runtime | VG Nova Local, `http://localhost:10039/` |

This successor refreshes the Plan A dataset only. IDs and slugs below are runtime evidence; they are not production constants. No record was selected or changed.

## Provenance rules

| Classification | Current meaning |
|---|---|
| `REAL_EDITORIAL` | Genuine Voelgoed editorial content with established source and an approved Topic and relationship assignment. |
| `OWNER_AUTHORIZED` | The owner approved only the named identity or content and scope. Approval does not imply other fields or relationships. |
| `QA_FIXTURE` | Test or demonstration data. Excluded from the Golden Journey. |
| `LEGACY` | Existing `medewerker` records or old relationships. Not a substitute for a canonical Person path. |
| `SYNTHETIC_OR_UNRESOLVED` | Placeholder or content with unresolved source and no owner-approved provenance. Not eligible. |

## Fresh candidate inventory

| Object | Current evidence | Classification | Journey decision |
|---|---|---|---|
| Nuwe Jy Topic | Canonical `content_topic` root, slug `nuwe-jy`; 0 assigned Articles | Canonical taxonomy term | Available as the required Topic, but it has no eligible Article path. |
| Article 375 | "Integer maximus sapien in bibendum."; published; no Topic or NF-PERSON fields | `SYNTHETIC_OR_UNRESOLVED` | Excluded. The provenance audit classifies its pseudo-Latin copy as placeholder and leaves its origin unresolved. |
| Person 1705 | Lynette Beer; published; `mentor` and `kenner`; no approved profile content, Topic, Article, or Course relationships | `OWNER_AUTHORIZED` for identity and classifications only | Not selected. Do not add a bio, profile image, Topic, or relationship to make it eligible. |
| Published Masteriyo Courses | Five published records; no `content_topic`, NF-PERSON role fields, or Woo Product mapping; editorial provenance is unconfirmed | Unverified staging content | None selected. The titles are not sufficient proof of real editorial use. |
| Draft Masteriyo Course | "Untitled Course"; draft; no slug or Person/Topic relationship | Draft/system content | Excluded. |

The five published Course titles are "Selfsorg vir Besige Ma's", "Geloof & Identiteit", "Finansiële Vryheid Basiese", "Gesonde Huwelik: Kommunikasie", and "Mindfulness vir Vroue". These names help the owner identify records for review; they do not approve their provenance or role relationships.

The Home's current Nuwe Jy link is `#`, not the canonical Topic URL. The public Nuwe Jy archive returns HTTP 200 but has no Articles. The current FCM option `featured_nuwe_jy` points to a published Event. That option is not a Topic-to-Article assignment.

## Plan A data decision

```text
SELECTED_TOPIC=none
SELECTED_ARTICLES=none
SELECTED_PERSON=none
SELECTED_COURSE=none
REPRESENTATIVE_DATA_GATE=BLOCKED
```

The smallest owner/content decision needed to unblock a single real path is:

1. Name one existing, genuine Nuwe Jy Article and provide its source or provenance record. Approve its canonical `content_topic` assignment.
2. Name the canonical Person for that Article and approve the public profile content needed for the journey. Confirm the Article role through the existing ACF field, such as primary author, expert contributor, or reviewer. If that Person is Lynette Beer, her identity approval does not replace approval of profile content or this relationship.
3. Name one genuine Masteriyo Course and approve its editorial relationship to that Person through the existing Course role field, normally lead instructor. Confirm the Course content has an acceptable source.

If the owner cannot name existing content, the required action is to supply or commission real editorial content through the normal editorial process. Do not use post 375, unverified staging Courses, old `medewerker` profiles, legacy edges, or QA fixtures to fill the path. A preferred set of three to five Articles can be selected after the owner confirms that such real content exists; it is not a reason to fabricate data.

No data entry, ACF relationship assignment, Bricks edit, Course edit, or other runtime mutation is authorized by this blocked contract.

## Result

```text
HOME_TO_TOPIC_DATA=GAP
TOPIC_TO_ARTICLE_DATA=GAP
ARTICLE_TO_PERSON_AUTHORITY=PASS
ARTICLE_TO_PERSON_REPRESENTATIVE_EDGE=BLOCKED
PERSON_TO_COURSE_AUTHORITY=PASS
PERSON_TO_COURSE_REPRESENTATIVE_EDGE=BLOCKED
REPRESENTATIVE_DATA_GATE=BLOCKED
REPRESENTATIVE_DATA_CREATION_REQUIRES_OWNER_CONTENT_DECISION=YES
PE007_RUNTIME_MUTATIONS=0
PE008_DEPLOYMENTS=0
ACF_MUTATIONS=0
BRICKS_MUTATIONS=0
```
