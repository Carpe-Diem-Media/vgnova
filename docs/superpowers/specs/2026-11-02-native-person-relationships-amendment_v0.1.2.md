# Native Person relationships architecture amendment

**NF-PERSON-001 · Version 0.1.2 · 2026-11-02**

**Status:** Records the NF-PERSON-002C ACF Local JSON sync correction

**Amends:** [`2026-11-01-native-person-relationships_v0.1.0.md`](2026-11-01-native-person-relationships_v0.1.0.md) and [`2026-11-02-native-person-relationships-amendment_v0.1.1.md`](2026-11-02-native-person-relationships-amendment_v0.1.1.md)

**SemVer change:** Patch amendment. This defines the Local JSON sync metadata needed to persist the approved Article and Course source-field settings through native ACF synchronization. Relationship fields, ownership, targets, and behavior do not change.

## Finding

Runtime activation of NF-PERSON-003 showed that ACF 6.8.1 did not classify the changed Article and Course field groups as pending sync. The JSON contained the approved bidirectional settings, but the database field-group definitions remained unchanged.

ACF 6.8.1 native `wp acf json status` and `wp acf json sync` treat an existing database field group as syncable when its Local JSON has a truthy top-level `modified` timestamp newer than the database field-group post's modified timestamp. A semantic JSON change without an advanced `modified` value can therefore remain unapplied even while Git contains different JSON.

## Locked synchronization rule

For canonical ACF Local JSON groups whose database field groups already exist:

- Advance the top-level `modified` Unix timestamp when the canonical JSON changes semantically.
- Treat `modified` as deployment and sync metadata, not business data.
- Use native `acf json status` and `acf json sync` as the authorized synchronization mechanism.
- Do not rewrite the database directly.
- Do not use `acf json import` as an activation bypass.
- Do not add a permanent reconciler.

This rule applies to the existing Article and Course source groups. It does not require `modified` on `group_vg_person_related_content.json` solely because those two source groups need it.

## Runtime consequence

After this source correction is merged and a new immutable ACF runtime generation is activated, native ACF status is expected to report:

| Field group | Expected status |
|---|---|
| `group_vg_article_people` | Update |
| `group_vg_course_people` | Update |

Native status is expected to show those two `Update` rows alongside the two pre-existing unrelated Member Perks pending groups. Do not sync those groups as part of NF-PERSON-003.

NF-PERSON-003 runtime activation remains pending. This amendment and source correction do not authorize runtime mutation, ACF sync, direct database changes, Platform Core deployment, Bricks changes, or relationship data changes.
