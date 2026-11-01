# Native Person relationships

**NF-PERSON-001 · Version 0.1.0 · 2026-11-01**

**Status:** Proposed for Master Gate review

**Repository:** `JCSchoeman96/vgnova`

**Design baseline:** `origin/main` at `2cfa36f469241f3ebc1dac82d57d203b760088a8`

**Scope:** Article/Course ↔ Person relationships only

This document specifies the Native-First replacement for the custom Article/Course Person reverse-index design. It does not implement the design. NF-PERSON-002 and NF-PERSON-003 require Master Gate approval before work starts.

## 1. Intent

Keep Article and Course relationship editing on the existing ACF source fields. Let ACF write each selected source object into one role-specific Relationship field on Person. Use Bricks Posts Query Loops to read those target fields on Person pages.

```text
Article or Course ACF source field
        ↓ one-way ACF bidirectional update
Person ACF Relationship target field
        ↓ Bricks Posts Query Loop
published Articles or Courses

Article or Course ACF source field
        ↓ Bricks Posts Query Loop, Include = source field
published Person records
```

The Article or Course field is the sole editorial authority for its relationship. The Person field is derived convenience storage. It is not an independent source.

Person stays the canonical human record. WordPress remains the Article authority. Masteriyo remains the Course authority through its `mto-course` post type. ACF adds only the Voelgoed-specific Person relationships.

## 2. NATIVE CAPABILITY REVIEW

Review date: 2026-11-01. Current official documentation was checked alongside the source-controlled VG Nova contracts.

| Capability | Native support checked | Result and remaining limit |
|---|---|---|
| `WORDPRESS_CORE` | [`WP_Query`](https://developer.wordpress.org/reference/classes/wp_query/) supports `post_type`, `post_status`, `post__in`, `orderby`, `meta_query`, and `tax_query`. Its documentation warns that an empty `post__in` array returns all posts. | **PASS.** WordPress supplies the post query Bricks needs. Core does not define the Article/Course-to-Person fields; ACF owns those. The design relies on a Bricks section condition for empty fields, not empty `post__in`. No custom query layer is needed. |
| `BRICKS` | Bricks [Posts Query Loop documentation](https://academy.bricksbuilder.io/builder/dynamic-content/query-loop/) defines Posts as a `WP_Query` and lists post type, Include/Exclude, order, taxonomy, and meta query controls. It lists ACF Relationship and Post Object values as supported dynamic Include values and says dynamic Include/Exclude is supported from Bricks 1.12. [Element Conditions](https://academy.bricksbuilder.io/builder/features/element-conditions/) supports a Dynamic data `is not empty` condition. Bricks support also [confirms](https://forum.bricksbuilder.io/t/no-bug-posts-in-draft-mode-are-still-being-displayed-on-the-front-end-in-query-loops/37695) that a normal frontend Posts query defaults to `post_status = publish`. | **PASS.** A Person template can query each role field directly. The Bricks docs do not list a post-status control, so NF-PERSON-003 must verify the installed version's normal frontend behavior. No custom Bricks query type is justified. |
| `ACF_PRO` | ACF [Post Object](https://www.advancedcustomfields.com/resources/post-object/) and [Relationship](https://www.advancedcustomfields.com/resources/relationship/) fields support post-type/status filters, ID return values, and bidirectional settings. [Bidirectional Relationships](https://www.advancedcustomfields.com/resources/bidirectional-relationships/) documents target fields, one-way updates, top-level target-field limits, non-chaining behavior, and the limitation that a target's single/max selection limit is not enforced by an automatic update. [Local JSON](https://www.advancedcustomfields.com/resources/local-json/) supports versioned field definitions. ACF [field groups](https://www.advancedcustomfields.com/resources/creating-a-field-group/) support `Post Type == person` location rules. | **PASS.** The source fields can update seven top-level, multi-value Relationship fields. Keep each target field multiple and unbounded. Do not enable bidirectionality on the target fields. ACF does not document a native read-only control for Relationship fields; editor presentation is specified in section 7. |
| `MASTERIYO` | Masteriyo's official [Course Creation guide](https://docs.masteriyo.com/course-creation/complete-course-creation) documents creating and publishing Courses through Masteriyo. VG Nova's current source and project contract identify `mto-course` as the Masteriyo Course post type. | **PASS.** Masteriyo remains the owner of Course records and Course behavior. ACF fields add Voelgoed Person links only. Do not infer these links from a WooCommerce product or WordPress/Masteriyo user author. No Masteriyo redesign is proposed. |

```text
WORDPRESS_CORE=PASS
BRICKS=PASS
ACF_PRO=PASS
MASTERIYO=PASS
NATIVE_CAPABILITY_REVIEW=PASS
```

### ACF bidirectional limits applied here

- ACF updates the configured target field from the field currently being edited. Enabling a target field to point back to the source is a separate setting and would create two-way editing. That setting stays off.
- Target fields must be top-level fields. All seven Person targets are top-level fields in `group_vg_person_related_content`, not subfields of a Group or Repeater.
- ACF does not enforce a target field's single-value or maximum-value restriction during its automatic update. Each target is therefore a multiple Relationship field with no maximum.
- ACF does not chain updates through further target fields. No target points back to an Article or Course field.
- Source Post Object fields can return IDs and limit selectable Person records by post type and status. The existing `publish`, `draft`, and `private` Person choices remain available to editors. Public Bricks loops filter Person presentation to `publish` records.

The documentation establishes native capability. NF-PERSON-003 still has to confirm the installed plugin versions and actual saved template behavior. No runtime plugin inspection, ACF sync, or Bricks change was performed for this design.

## 3. Existing authoritative source fields

Keep all current field names, keys, field types, cardinalities, required settings, Person status filters, and locations. The proposed source-field changes are clear editorial instructions and the bidirectional target.

| Source | Location | Existing field | Current cardinality | Current ACF type / return | Current Person status filter |
|---|---|---|---|---|---|
| Article | `post_type == post` | `primary_author` | 0..1 | Post Object / ID | `publish`, `draft`, `private` |
| Article | `post_type == post` | `additional_authors` | 0..n | Post Object / ID | `publish`, `draft`, `private` |
| Article | `post_type == post` | `expert_contributors` | 0..n | Post Object / ID | `publish`, `draft`, `private` |
| Article | `post_type == post` | `reviewed_by` | 0..n | Post Object / ID | `publish`, `draft`, `private` |
| Course | `post_type == mto-course` | `lead_instructor` | 0..1 | Post Object / ID | `publish`, `draft`, `private` |
| Course | `post_type == mto-course` | `additional_instructors` | 0..n | Post Object / ID | `publish`, `draft`, `private` |
| Course | `post_type == mto-course` | `experts` | 0..n | Post Object / ID | `publish`, `draft`, `private` |

The fields live in `group_vg_article_people` and `group_vg_course_people`. Their canonical definitions remain in `site-code/acf-json/`. Articles continue to be WordPress `post` records. Courses continue to be Masteriyo `mto-course` records. No `article_participation` or `course_participation` entity will be added.

## 4. Person reverse-field schema

Add a separate ACF field group:

```text
Key:       group_vg_person_related_content
Title:     Person Related Content
Location:  post_type == person
Purpose:   seven ACF targets maintained from Article/Course source fields
```

Keep `group_vg_person_details` focused on profile facts. Do not add relationship targets to that group.

Add seven top-level ACF Relationship fields. ACF Relationship fields accept multiple posts; set minimum to zero and maximum to unlimited. Return IDs. Restrict each selector to the specified source post type.

| Field key | Field name | Label | Target post type |
|---|---|---|---|
| `field_vg_person_articles_primary_author` | `person_articles_primary_author` | Person: Articles as primary author | `post` |
| `field_vg_person_articles_additional_author` | `person_articles_additional_author` | Person: Articles as additional author | `post` |
| `field_vg_person_articles_expert_contributor` | `person_articles_expert_contributor` | Person: Articles as expert contributor | `post` |
| `field_vg_person_articles_reviewed_by` | `person_articles_reviewed_by` | Person: Articles reviewed by | `post` |
| `field_vg_person_courses_lead_instructor` | `person_courses_lead_instructor` | Person: Courses as lead instructor | `mto-course` |
| `field_vg_person_courses_additional_instructor` | `person_courses_additional_instructor` | Person: Courses as additional instructor | `mto-course` |
| `field_vg_person_courses_expert` | `person_courses_expert` | Person: Courses as expert | `mto-course` |

The exact field keys are reserved for the Local JSON definitions. They do not change the existing source field keys.

## 5. Exact source-to-target mapping

Set one ACF bidirectional target on each existing source Post Object field. The target field has no bidirectional target of its own.

| Source field | Target field |
|---|---|
| `post.primary_author` | `person.person_articles_primary_author` |
| `post.additional_authors` | `person.person_articles_additional_author` |
| `post.expert_contributors` | `person.person_articles_expert_contributor` |
| `post.reviewed_by` | `person.person_articles_reviewed_by` |
| `mto-course.lead_instructor` | `person.person_courses_lead_instructor` |
| `mto-course.additional_instructors` | `person.person_courses_additional_instructor` |
| `mto-course.experts` | `person.person_courses_expert` |

The Article/Course source field stores Person IDs. ACF adds or removes the edited Article/Course ID in the matching Person Relationship field. It does not copy source field values into another authority or infer roles from other records.

## 6. Direction of authority

**ARTICLE FIELDS OWN ARTICLE↔PERSON RELATIONSHIPS.**

**COURSE FIELDS OWN COURSE↔PERSON RELATIONSHIPS.**

Person-side reverse fields are derived convenience storage maintained by ACF. They are not independent editorial authority. This is one-way synchronization from the source field to its configured target.

Do not enable bidirectional updates on any Person target. Do not add a second Person-side relationship source. If a reverse value is wrong, correct the Article or Course source field. No target-field save may rewrite a source field.

## 7. Editor visibility

Editors should not normally maintain the seven derived fields. ACF's [`acf/prepare_field`](https://www.advancedcustomfields.com/resources/acf-prepare_field/) filter can prevent a field from rendering. NF-PERSON-002 may add one small admin-only filter for these seven target field keys on the `person` post edit and add screens. The callback returns `false` for those fields and leaves them registered, stored, and available to Bricks.

Do not add a read-only summary, role headings, edit-screen links, or empty-state UI in version 1. Do not use disabled controls or write target values from the Person edit form. These hidden controls send no field values from the Person form. Before ACF sync, NF-PERSON-003 must verify on an approved disposable QA fixture that saving a Person profile leaves all seven existing target values unchanged. If that check fails, stop and revise the presentation before syncing ACF.

This callback only hides derived controls. It must not query related content, add or remove relationships, synchronize fields, validate source fields, reconcile values, or expose a Bricks query API. Defer any summary UI until an editor journey demonstrates that the hidden fields create a real operational need.

## 8. Publication-status decision

Keep the existing Person selection statuses and filter public presentation natively.

| Option | Behavior | Decision |
|---|---|---|
| A. Published Person selection | Each Article/Course source Post Object field lists only `person` records with `post_status = publish`. Editors must publish a complete Person profile before relating it. | Rejected. Editorial preparation can require a Person relationship before the public profile is ready. |
| B. Draft/private Person selection with custom validation | Keep draft/private choices but enforce publication rules through source-save, REST, scheduled-publication, and low-level-write validation plus partial-write and repair machinery. | Rejected. It retains the custom lifecycle machinery built for the discarded index subsystem. |
| Native lifecycle selected | Keep the current `publish`, `draft`, and `private` source-field choices. Do not add a publication validator. Public Article/Course and Person presentation uses Bricks Posts Query Loops whose public result status is `publish`. | Selected. WordPress status controls public visibility while ACF preserves editorial relationships. |

Do not change the seven source field status filters. An editor may relate an Article or Course to a draft or private Person while the profile is being prepared. Do not add a custom publication validator.

Relationships remain stored when a Person changes status. Public Article/Course loops omit draft, private, trashed, or missing Person records. Public Person loops omit non-published Articles and Courses. Republishing a Person makes the existing relationship eligible for public presentation again. A deleted or stale relationship is ordinary content maintenance; it does not justify a permanent validator or reconciler.

## 9. Role overlap decision

Do not enforce the old one-Person-one-role-per-source rule. The source fields are independent and ACF may store the same Person in multiple roles on one Article or Course. A Person may, for example, be both an Article primary author and an expert contributor, or both a Course lead instructor and an expert.

No custom role-overlap validation is authorized. Keep the seven Person targets separate so Bricks can render role-specific sections without custom role-query logic. If the same source appears under two roles for a Person, it may appear in both sections.

## 10. Bricks Query Loop design

Use native Bricks Posts Query Loops in both public directions. Do not output an Article/Course Person relationship field directly as public identity text or a link. A raw `{acf_primary_author}` or equivalent relationship tag may resolve a non-public Person. Render the Person through a Posts Query Loop that queries `person` and includes the source field's dynamic value. Use the Person Card for each result.

### Article/Course to Person

On Article and Course templates, make one Person loop for each populated role field. Set query type to Posts, post type to `person`, Include to the source field's ACF dynamic value, and public status to `publish`. Use the exact field values below. If the installed Bricks version has no explicit status control, verify that the normal public Posts query returns only published Persons. Stop for Master Gate review if it does not; do not add a custom query type by default.

| Source field | Query type | Post type | Include dynamic value | Public status |
|---|---|---|---|---|
| `post.primary_author` | Posts | `person` | `{acf_primary_author}` | `publish` only |
| `post.additional_authors` | Posts | `person` | `{acf_additional_authors}` | `publish` only |
| `post.expert_contributors` | Posts | `person` | `{acf_expert_contributors}` | `publish` only |
| `post.reviewed_by` | Posts | `person` | `{acf_reviewed_by}` | `publish` only |
| `mto-course.lead_instructor` | Posts | `person` | `{acf_lead_instructor}` | `publish` only |
| `mto-course.additional_instructors` | Posts | `person` | `{acf_additional_instructors}` | `publish` only |
| `mto-course.experts` | Posts | `person` | `{acf_experts}` | `publish` only |

This filters unpublished People in the public output while retaining draft/private Person IDs in the editorial source fields. A Person who is published later can appear without an Article or Course relationship rewrite.

### Person to Article/Course

Use the Person Single template's native **Posts** Query Loop, not Bricks' separate ACF Relationship query source. Set each loop's `Include` control to the matching ACF dynamic tag. The post type must match that target field's value type. The public frontend query must return `publish` sources only. If the installed Bricks version has a native status control, set it to `publish`; otherwise verify the standard public Posts query does so. If that check fails, stop for Master Gate review before adding a query adapter.

| Person field / dynamic Include value | Query type | Post type | Status | Order |
|---|---|---|---|---|
| `person_articles_primary_author` / `{acf_person_articles_primary_author}` | Posts | `post` | Public frontend: `publish` only | `date DESC` |
| `person_articles_additional_author` / `{acf_person_articles_additional_author}` | Posts | `post` | Public frontend: `publish` only | `date DESC` |
| `person_articles_expert_contributor` / `{acf_person_articles_expert_contributor}` | Posts | `post` | Public frontend: `publish` only | `date DESC` |
| `person_articles_reviewed_by` / `{acf_person_articles_reviewed_by}` | Posts | `post` | Public frontend: `publish` only | `date DESC` |
| `person_courses_lead_instructor` / `{acf_person_courses_lead_instructor}` | Posts | `mto-course` | Public frontend: `publish` only | Use the existing Masteriyo/WordPress course listing order. If none is configured, use `date DESC`. |
| `person_courses_additional_instructor` / `{acf_person_courses_additional_instructor}` | Posts | `mto-course` | Public frontend: `publish` only | Use the existing Masteriyo/WordPress course listing order. If none is configured, use `date DESC`. |
| `person_courses_expert` / `{acf_person_courses_expert}` | Posts | `mto-course` | Public frontend: `publish` only | Use the existing Masteriyo/WordPress course listing order. If none is configured, use `date DESC`. |

Each role gets its own section and loop in both directions. Do not combine the fields into a generic `related_content` field. Do not add a custom Bricks query type, custom `WP_Query` adapter, repository, service, or role-aggregation helper.

Bricks' native Posts query supports post type, included IDs, order, taxonomy queries, and meta queries. These loops need only post type, Include, published status, and order. No custom meta/tax query is needed for membership.

## 11. Empty-state behavior

Put a Bricks Dynamic data condition on each complete reverse role section, including its heading: the matching `{acf_person_...}` field **is not empty**. When a Person reverse field is empty, Bricks omits the whole section from frontend HTML.

On Article and Course templates, condition each role section on its existing source field being nonempty. The Posts loop still filters its results to published Persons. If the source field contains only draft, private, trashed, or missing Person IDs, the loop returns no Person cards; do not render relationship-field values as a fallback. A static role heading may remain in that case unless the installed Bricks version suppresses the no-result wrapper natively. Verify the saved behavior in NF-PERSON-003. No PHP section-visibility logic is proposed.

Do not rely on an empty dynamic Include value to make the query return zero posts. WordPress documents that `post__in => []` returns all posts. The enclosing condition is the empty-state control; NF-PERSON-003 must confirm Bricks skips the section's output when the condition fails. Bricks keeps conditioned elements available in the builder. Do not add PHP visibility logic or a custom Bricks filter.

## 12. Ordering

Article role loops sort by publication date, newest first. Person reverse fields do not introduce a global ranking or copy per-Article relationship positions.

Course role loops preserve the existing Masteriyo/WordPress course listing order when one is configured. If the current Course journey has no explicit order, sort by publication date, newest first. Do not add a `position` field, global Person ranking, or custom ordering system. NF-PERSON-003 records the native order it finds before configuring the loops.

## 13. Rejected custom architecture

```text
vg_person_relationship_index = ABANDONED / MUST NOT BE CREATED
```

Do not create an equivalent table or another custom reverse store. Do not create shadow meta, transients, options indexes, cron-built indexes, materialized projections, a custom relationship repository or service, an indexer, a reconciler, a repair/rebuild command, dirty-generation state, publication machinery for those discarded invariants, or a custom Bricks query type.

The seven ACF target Relationship fields are the only derived convenience storage. ACF maintains them from the authoritative source fields. If an ACF value is ever inconsistent, use normal ACF/WordPress maintenance or a separately approved one-time repair. Do not build permanent reconciliation infrastructure in advance.

There is no measured native-query limitation in the current evidence. Custom reverse-query infrastructure is not authorized. Reconsider it only after implementing and measuring the native path against a realistic corpus and identifying a user-visible bottleneck.

## 14. Historical supersession

The approved document [`2026-10-27-simple-person-relationship-architecture-design_v0.1.0.md`](2026-10-27-simple-person-relationship-architecture-design_v0.1.0.md) remains unchanged as historical evidence. This specification supersedes only these decisions and clauses:

- Section 4.2's rejection of native ACF bidirectional syncing.
- Section 5's custom normalized table and index schema.
- Section 7's one-Person-one-role rule.
- Section 8's custom `position` ordering semantics.
- Sections 9 and 10's custom publication validator, Person lifecycle checks, and repair workflow.
- Sections 11, 12, 13, 14, 15, and 16's derived index lifecycle, synchronizer, dirty state, rebuild/reconciliation operations, repository/service boundary, and index-backed reverse query.
- Section 17's index-backed reverse-read rule. The source fields remain authoritative, and no serialized-meta `LIKE` query is introduced.
- Section 18's custom reverse-service eligibility checks. Public Person and source visibility still follows WordPress and native provider status/access rules.
- Section 19's custom Bricks API/adaptor boundary. Bricks uses native ACF Dynamic Data and Posts Query Loops.
- Section 20's prohibition on bidirectional updates. Its source-field, canonical Person, and ID-return requirements remain in force.
- The role-overlap and publication enforcement clauses in sections 12 and 22, and the custom-index performance rationale in section 23.
- The custom-index statements in section 26.

Amendments v0.1.1 and v0.1.2 remain unchanged. Their supported-path validators, partial-write projection checks, dirty-state response, repair mechanics, and role/status restrictions are superseded only where they implement the discarded relationship subsystem or invariants above. Their historical explanation of the old approach remains intact.

All other settled decisions remain in force. In particular, Articles and Courses own their source fields; no Article/Course participation CPT is added; WordPress remains Article authority; Masteriyo remains Course authority; Product Contribution remains Product authority; Event Participation remains Event authority; and no legacy relationship is inferred.

[`SIMPLE_PERSON_RELATIONSHIP_SOURCE_IMPLEMENTATION_v1.0.2.md`](../../architecture/SIMPLE_PERSON_RELATIONSHIP_SOURCE_IMPLEMENTATION_v1.0.2.md), amendments v0.1.1/v0.1.2, and runtime activation reports remain unedited historical implementation and activation evidence. They do not authorize deployment or activate the rejected design.

NF-PERSON-001 does not remove any `PersonRelationship*` source file. The source implementation is retired only in NF-PERSON-002 after Master Gate approval. Before any later Platform Core deployment, NF-PERSON-002 must remove the old production classes and wiring from the source assembly so the discarded validator, table, command, dirty state, and reverse service cannot run. Remove their implementation tests and current operational README instructions in that source change. Preserve the historical specs, amendments, and activation evidence.

## 15. Migration boundary

This design specifies schema and relationship ownership. It does not migrate real relationships.

NF-PERSON-001 creates no field definitions, target values, identity, or runtime data. Lynette's existing canonical `person` record remains untouched. Legacy `medewerker` relationships remain subject to their existing provenance and migration gates. Do not infer or migrate Article/Course links from `medewerker`, WordPress authors, Masteriyo authors, WooCommerce products, Product Contribution, Topics, or historical QA data.

Enabling ACF bidirectionality does not itself authorize a bulk backfill. Before NF-PERSON-003 syncs ACF, inspect the current seven source fields. If any existing canonical source links need target values and are not already projected, stop and request a separately approved one-time source-driven backfill decision. Do not create a permanent repair or reconciliation process.

Event Participation, Event Role, Product Contribution, and Product Role are outside this scope and remain unchanged.

## 16. Acceptance criteria

The following checkboxes record design coverage. They do not claim implementation or runtime completion.

- [x] Existing Article ACF fields are reused with their names, keys, cardinalities, and `post` location preserved.
- [x] Existing Course ACF fields are reused with their names, keys, cardinalities, and `mto-course` location preserved.
- [x] Person remains the canonical human identity.
- [x] Masteriyo remains Course authority; WordPress remains Article authority.
- [x] Reverse access uses seven ACF native bidirectional targets with one-way authority.
- [x] Bricks native Posts Query Loops are the default public consumer from Article/Course to Person and Person to Article/Course.
- [x] Source selectors keep the existing `publish`, `draft`, and `private` Person choices; no custom publication validator is required.
- [x] Public loops return published records only and omit non-public or missing related records.
- [x] The seven derived Person targets are hidden on the Person admin edit screen without a summary UI or disabled controls.
- [x] No `vg_person_relationship_index` table or equivalent custom reverse index is part of the target design; old source code is retired before any future deployment.
- [x] No custom repository or service is required for Article/Course reverse reads.
- [x] No custom reconciler or index rebuild remains in the target design.
- [x] No custom Bricks query type is proposed.
- [x] Event Participation and Product Contribution remain untouched.
- [x] No legacy data migration is in scope.
- [x] NF-PERSON-001 performs no runtime mutation.
- [x] Historical architecture and activation evidence remain preserved and are explicitly superseded only where listed.

## 17. NF-PERSON-002/003 implementation scope

### NF-PERSON-002: source schema and subsystem retirement

Begin only after this design has Master Gate approval.

1. In a source-only PR, remove the custom `PersonRelationship*` production classes, Platform Core includes/registrations and CLI wiring, their implementation tests, and current README instructions for setup/rebuild/reconcile. Do not alter historical architecture or activation evidence.
2. Add `group_vg_person_related_content` to ACF Local JSON with the seven top-level multi-value Relationship fields in section 4.
3. Update only the existing Article/Course source field settings in `group_vg_article_people` and `group_vg_course_people`: preserve keys, names, field types, cardinalities, locations, and `publish`/`draft`/`private` Person status filters; add the exact target field key from section 5; update editor instructions.
4. Add only the admin-only `acf/prepare_field` suppression described in section 7 for the seven derived targets on Person edit and add screens. Do not add a `PersonRelatedContentPresentation` class or read-only summary UI.
5. Run source checks and prove the source definitions contain no custom table, reverse-query service, validator, reconciler, or index lifecycle. Do not deploy Platform Core, sync ACF, mutate Bricks, or write WordPress relationship data in NF-PERSON-002.

### NF-PERSON-003: controlled activation and native Bricks consumer

Begin only after NF-PERSON-002 has merged and its independent review passed, with separate explicit authorization for runtime changes.

1. Recheck the exact `origin/main` and inspect the saved Person Single template and current Bricks/ACF runtime versions.
2. Check the seven existing source fields for real canonical values. If any nonempty source field would need a bulk target backfill, stop for a separate approval.
3. Use the repository-owned runtime mutation coordinator for the authorized Platform Core deployment and targeted ACF sync. Sync only `group_vg_article_people`, `group_vg_course_people`, and `group_vg_person_related_content`. Do not create a database table or migrate legacy relationships.
4. Configure native Bricks Posts Query Loops for each needed role in both directions using section 10, with section 11 conditions. Confirm the installed Bricks version returns only published Person results on public Article/Course pages. Do not add a query adapter or change Event/Product templates.
5. Verify field persistence, one-way additions/removals, all three source selector statuses, published-only public output for Article/Course-to-Person and Person-to-Article/Course, Person status transitions, empty section rendering, and preservation of the hidden target fields after a Person save. Use only a separately authorized disposable QA fixture. Do not mutate representative records.

NF-PERSON-003 must stop if any prerequisite, current field inventory, editor-save preservation check, or query behavior fails. No step in this document authorizes bypassing runtime mutation coordination.

## Decision request

Master Gate review is requested for the seven-field mapping, one-way source authority, native Person publication lifecycle, role overlap, minimal field suppression, both Bricks query directions, subsystem retirement boundary, and NF-PERSON-002/003 scope. No implementation may begin until this design receives explicit approval.
