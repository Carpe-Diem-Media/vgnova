# Native Person relationships architecture amendment

**NF-PERSON-001 · Version 0.1.1 · 2026-11-02**

**Status:** Approved for the NF-PERSON-002B source correction

**Amends:** [`2026-11-01-native-person-relationships_v0.1.0.md`](2026-11-01-native-person-relationships_v0.1.0.md)

**Supersedes:** Any ambiguity in v0.1.0 about the serialized ACF bidirectional setting on Article and Course source fields. All other v0.1.0 decisions remain in force.

**SemVer change:** Patch amendment. This makes the already specified one-way source-to-reverse behavior explicit in ACF Local JSON. It adds no field, relationship role, or data authority.

## Locked direction

The existing Article and Course Person source fields are the sole authority for their relationships. Each source field must use ACF's native bidirectional setting to update its one mapped Person reverse field. Updates flow from Article or Course to Person only.

Set the numeric JSON property `"bidirectional": 1` on exactly these seven Post Object source fields:

| Source field key | Person reverse field key |
|---|---|
| `field_vg_article_primary_author` | `field_vg_person_articles_primary_author` |
| `field_vg_article_additional_authors` | `field_vg_person_articles_additional_author` |
| `field_vg_article_expert_contributors` | `field_vg_person_articles_expert_contributor` |
| `field_vg_article_reviewed_by` | `field_vg_person_articles_reviewed_by` |
| `field_vg_course_lead_instructor` | `field_vg_person_courses_lead_instructor` |
| `field_vg_course_additional_instructors` | `field_vg_person_courses_additional_instructor` |
| `field_vg_course_experts` | `field_vg_person_courses_expert` |

Keep each source field's existing `bidirectional_target` mapped to the one Person field in this table. Preserve all existing field keys, names, locations, types, cardinalities, instructions, status filters, and return formats.

## Reverse fields remain one-way targets

Do not enable bidirectional updates on any field in `group_vg_person_related_content`. Each Person reverse field must have bidirectionality disabled or omit the `bidirectional` property. Its `bidirectional_target` must remain empty. Saving or editing a Person reverse field must not write an Article or Course source field.

If a Person reverse value is wrong, correct the owning Article or Course source field. Do not add another relationship source, custom synchronization code, or a repair process.

## Scope

This amendment governs the seven source field settings and their seven existing Person targets. It does not authorize ACF synchronization, runtime mutation, relationship backfill, Platform Core deployment, Bricks changes, or WordPress data changes. NF-PERSON-003 retains its separate runtime authorization and pre-sync inspection requirements.

The source schema test must require `bidirectional === 1` on every Article and Course source field and must reject enabled bidirectionality on every Person reverse field.
