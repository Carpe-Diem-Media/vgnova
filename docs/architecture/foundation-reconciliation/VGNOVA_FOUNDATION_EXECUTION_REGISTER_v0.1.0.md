# VG Nova — Foundation Execution Register

**Version:** 0.1.0  
**Filename:** `VGNOVA_FOUNDATION_EXECUTION_REGISTER_v0.1.0.md`  
**Date:** 25 November 2026 (Africa/Johannesburg)  
**Status:** EXECUTION ROADMAP ESTABLISHED — development-first; implementation and acceptance remain gated.  
**Owner:** Voelgoed business owner  
**Governance:** Plane VGN-56 (foundation gate), VGN-55 (audit evidence), VGN-23 (source-to-target migration), VGN-30 (production cutover)  
**Persistence:** Downloadable initial snapshot; Plane work packages, modules and dependency relationships created and read back. No GitHub commit or merge made for this document.

## 1. Purpose and controlling sequencing decision

Finish the consolidated VG Nova target foundation using the existing WordPress-native architecture. Reuse the completed target inventory and reconciliation. Do not restart a broad audit or invent replacement subsystems simply because runtime behavior remains unverified.

Owner direction, 25 November 2026: move the broad F-00 security audit until after functional development; record security findings in Plane as encountered and address them in the later hardening phase. Keep development focused on the current goal. The execution register translates requirements into dependency-ordered work packages; Plane is the live operational backlog. Neither document nor ticket creation authorizes every underlying runtime write or migration.

**Development-first is not a security or integrity waiver.** Source provenance, actual target identity, scoped authorization, safe fixtures, guard correctness, rollback and exact authority checks necessary for the current task remain just-in-time prerequisites. If an issue presents ongoing exposure or would cause unauthorized access, money movement, corruption, irreversible side effects or unsafe test execution, stop only the affected work, record the issue and seek narrow containment authorization; continue independent work. The reported audit-output incidents remain distinct and unresolved; the audit safety gate remains BLOCKED. No secret values or raw credential-bearing artifacts may be copied into Plane, GitHub or this register.

## 2. Authority and baseline

| Reference | Authority / status |
|---|---|
| BIZ | [VGNOVA_BUSINESS_GRILLING_REQUIREMENTS_REGISTER_v0.2.10.md](https://github.com/Carpe-Diem-Media/vgnova/blob/b14980a1e64dda0ae45376b15acb75e49593461e/docs/architecture/business-discovery/VGNOVA_BUSINESS_GRILLING_REQUIREMENTS_REGISTER_v0.2.10.md); blob `7266a60d801e9c6be3f6f65f45979f9c6cce3036`. Confirmed owner requirements, historical decisions and open policies. |
| RECON | Uploaded `VGNOVA_FOUNDATION_RECONCILIATION_REGISTER_v0.1.10(1).md`, internal filename `VGNOVA_FOUNDATION_RECONCILIATION_REGISTER_v0.1.10.md`; SHA-256 `f82a495cb77277113b098d6e129325124083d0808b39de57947375475ba9eaf1`; 911,224 bytes. Original audit corpus ingestion complete (B10), not current runtime or engineering acceptance. |
| ROOT | [AGENTS.md at historical evaluation baseline](https://github.com/Carpe-Diem-Media/vgnova/blob/454346d9e6a2c36864a4bbd944e52596f16e09c1/AGENTS.md). At each slice read current applicable root/nested AGENTS and locked contracts; reconcile any change before implementation. |
| AUDIT | November 19, 2026 LocalWP inspection: main `454346d9e6a2c36864a4bbd944e52596f16e09c1`; `vgnova-local-10039`, `nAZNpFs99`, `http://localhost:10039`. 13/13 domain reports ingested; register reports 18/18 manifest identity checks; all domains PARTIAL at that baseline. No underlying tests rerun here. |
| LEG | [Canonical Legacy Decommission Register at historical baseline](https://github.com/Carpe-Diem-Media/vgnova/blob/454346d9e6a2c36864a4bbd944e52596f16e09c1/docs/architecture/LEGACY_DECOMMISSION_REGISTER.md). RECON directly read only first 80 lines; full current authority check required before retirement decisions. |
| PLANE | Live project `e0f4a753-a9d1-447d-974a-fa48ae8912b2`; [VGN-56](https://app.plane.so/symphony-development/browse/VGN-56), [VGN-55](https://app.plane.so/symphony-development/browse/VGN-55), [VGN-23](https://app.plane.so/symphony-development/browse/VGN-23), [VGN-30](https://app.plane.so/symphony-development/browse/VGN-30). 56 existing items inspected; 17 new items created, 2 new modules, 24 dependency edges verified. Existing statuses and modules preserved. |

The execution order below supersedes the proposed F-00-first sequence only; it does not supersede the business requirements, historical evidence, acceptance obligations or canonical architecture. RECON §1 has a stale interim 8/13 report count; B10 and §20 are controlling for original corpus completion. Its historical batch instructions are not new work orders. RECON §4 trace keys, §7 E-evidence, §11 CF-conflicts, §12 LC-contracts, §13 risks, §14 DC/LEG candidates, §15 owner decisions, §16 TV backlog and §17 Plane mapping remain the detailed reference. No need to copy its entire contents into every agent prompt.

## 3. Roadmap at a glance

| Phase | Execution disposition |
|---|---|
| R-0 baseline | Reuse dated evidence; refresh only affected source/runtime authority. Audit safety remains blocked. |
| R-1 inventory | Target inventory delivered; production-source and external-provider inventory remains outstanding under VGN-23/domain source plans. |
| R-2 reconciliation | Original audit corpus integrated; remaining targeted authority, policy and verification gaps handled as each work package starts. |
| R-3/R-4 | Primary work now: approved small development slices and behavioral proof. Model/lifecycle decisions just in time; no blanket contract-writing phase before all code. |
| F-00 broad security/hardening | Deferred until functional development completed; record findings immediately, apply narrow stop conditions when required. |
| R-5 | Evaluate and approve only specific legacy dispositions after consumer, replacement, retention and rollback proof. |
| R-6 | Integrated foundation acceptance, then separately authorized source rehearsals and migration/cutover. |

## 4. Live Plane structure and ticket map

Development module ID `6790b2b1-2ffc-407b-941b-3ed8648e57ff` — **Foundation development — R-3/R-4 target completion** (14 new items). Deferred security module ID `1010ca70-2798-4dc1-9a1c-51e6c8315dbd` — **Deferred security and pre-launch hardening — F-00** (3 new items). Both planned, not execution approval. All new items are children of VGN-56; existing work items retain their original parent/module/status. No assignments or dates invented.

| Package | Plane work item | Initial state | Priority | Native blocked-by links (direct) | Scope |
|---|---|---|---|---|---|
| F-01 | [VGN-57](https://app.plane.so/symphony-development/browse/VGN-57) | Todo | High | — | Canonical content schema and relationship authority |
| F-02 | [VGN-58](https://app.plane.so/symphony-development/browse/VGN-58) | Backlog | High | 57 | Mense and Vennote identity and role model |
| F-03 | [VGN-59](https://app.plane.so/symphony-development/browse/VGN-59) | Backlog | High | 57 | Shared account, permissions and independent entitlement boundaries |
| F-04 | [VGN-60](https://app.plane.so/symphony-development/browse/VGN-60) | Backlog | High | 59 | Core checkout and order-line fulfilment |
| F-05 | [VGN-61](https://app.plane.so/symphony-development/browse/VGN-61) | Backlog | High | 60 | Member Perks release and full benefit policy |
| F-06 | [VGN-62](https://app.plane.so/symphony-development/browse/VGN-62) | Backlog | High | 61 | Learning and Mentorklas beyond Golden Journey |
| F-07 | [VGN-63](https://app.plane.so/symphony-development/browse/VGN-63) | Backlog | High | 61 | Ticket lifecycle and external event integrations |
| F-08 | [VGN-64](https://app.plane.so/symphony-development/browse/VGN-64) | Backlog | High | 61 | Digital library, reader and app/web entitlements |
| F-09 | [VGN-65](https://app.plane.so/symphony-development/browse/VGN-65) | Backlog | High | 58, 61 | Editorial discovery and commercial placement |
| F-10 | [VGN-66](https://app.plane.so/symphony-development/browse/VGN-66) | Backlog | Medium | 65 | LIVE, podcasts and media |
| F-11A | [VGN-67](https://app.plane.so/symphony-development/browse/VGN-67) | Backlog | Medium | 58, 62 | Programmes, editions and participants |
| F-11B | [VGN-68](https://app.plane.so/symphony-development/browse/VGN-68) | Backlog | Medium | 58, 60 | Competitions, selection and results |
| F-12A | [VGN-69](https://app.plane.so/symphony-development/browse/VGN-69) | Backlog | High | 59 | FluentCRM and communications |
| F-12B | [VGN-70](https://app.plane.so/symphony-development/browse/VGN-70) | Backlog | High | 60 | Jobs, monitoring and reconciliation |
| F-13 | Existing VGN-29/36, 20/46/51/52/44 | Existing | High | Per relevant domain; existing guards | Bricks templates/components, editorial/admin workflows and browser QA |
| F-00 | [VGN-71](https://app.plane.so/symphony-development/browse/VGN-71) | Backlog | High (deferred) | 63, 64, 66, 67, 68, 69, 70, 36 | Comprehensive security and pre-launch hardening |
| F-00 incident A | [VGN-72](https://app.plane.so/symphony-development/browse/VGN-72) | Backlog | High | — | Reported Frames tool-output incident; distinct from VGN-54 |
| F-00 incident B | [VGN-73](https://app.plane.so/symphony-development/browse/VGN-73) | Backlog | High | — | Reported sibling-workspace tool-output incident; distinct from VGN-54 |
| F-14 | Existing VGN-55/23/24–29/42/21 | Existing | Gated | Verified replacements, mapping, recovery and owner approval | Redundancy review and migration preparation |
| F-15 | Existing VGN-56, VGN-11 | Existing | Gate | All applicable target acceptance, security and migration prerequisites | Foundation acceptance; VGN-11 remains VS1-only |

Native dependency relations are conservative package-completion gates, not a prohibition on safe independent planning or approved earlier sub-slices. In particular, draft F-05 policy work may precede checkout proof; basic F-09 public discovery need not wait for paid membership proof; F-12A/B can implement baseline communications/jobs then connect later producer slices; F-13 presentation is validated with each domain. If dependencies are unnecessarily broad for a bounded slice, create a narrowly scoped child or reuse an existing ticket and record why; do not silently bypass a financial/access dependency. VGN-71 links to terminal feature packages and VGN-36 frontend completion, transitively following earlier work. Incident A/B may be triaged separately whenever affected safe use requires it.

## 5. Work package execution specifications

### F-01 — Canonical content model and relationship authority (VGN-57)

- **Reuse:** existing Platform Core `content_topic` (seven roots, 55 terms at historical baseline); ACF schema and supported simple bidirectional Person relationships; Event Participation/Product Contribution contextual ownership; existing native provider entities and Bricks consumers.
- **Evidence:** RECON §4.2/4.3, E53–E63, E122–E123; LC-20/24–27. Legacy `content_category` and detached core category are distinct; 15 source-matched JSON files, 28 effective groups/247 fields reported; DB-only schemas and consumer precedence still require scoped reconciliation. No registration-count-only acceptance.
- **First slice F-01.1:** inspect current root and exact Topic/Person/relationship contracts and corresponding source. Select the smallest unresolved canonical model gap needed for real cross-domain reuse. Compare against historical RECON, identify actual consumers and current authorized LocalWP evidence only if needed. Produce the precise retain/reconcile/complete decision, one implementation ticket or existing issue handoff, and acceptance tests. Keep competition, broadcast series and unrelated template redesign in their own packages.
- **Acceptance:** canonical model has one editable owner per fact; semantic Topics distinct from commerce/event/format/commercial/folder taxonomy; validated forward and derived reverse relations; approved positive/negative save/query/visibility tests; no silent deletion, unapproved rename, fixture migration, blind backfill or legacy consumer breakage. Reuse VGN-21, VGN-24, VGN-43/44, VGN-55 where their scope matches.

### F-02 — Mense / Vennote (VGN-58)

- **Reuse:** canonical `person` and legacy `medewerker` coexist; native Article/Course relations and rich contextual Event/Product role contracts. Retain contributor vs account vs partner distinction; current identity/fixture approvals limited.
- **Evidence:** BIZ MP-01–04; RECON §4.3, E57–59/E67/E102/E123, LC-08/25–27; TV-41–45. Define consent, public profile publication, correction, privacy/withdrawal/archive and role-assignment lifecycle; longer-lived Vennote vs one-off commercial placement.
- **Acceptance:** representative reused Person across domains, role-correct display and query, guarded privacy/drafts/archive, no duplicate or fake identity, verified retained history. Reuse VGN-2/13/43/44; VGN-24 source mapping separate. Do not retire legacy profile storage or URLs yet.

### F-03 — Customer accounts, scoped permissions and rights (VGN-59)

- **Reuse:** WP account framework; native Woo/PMPro/Masteriyo/Tickera actions; My Voelgoed projection and Journey Intent; no CRM or Person substitute identity.
- **Evidence:** BIZ I-01/02/04 and C-03; RECON §4.5/4.12 E65/E76/E83/E115–117; LC-01/13/16, TV-83/85. Lock verified linking/claiming, duplicate/conflict/recovery, independent purchase/member/gift grant provenance and scope; no email-only merges; permitted guests.
- **Acceptance:** two synthetic identities cannot access each other's records; required-account checkout guards; safe guest-to-account transition; correct identity projection, scoped staff permissions, audited account changes. No real customer import. Existing VGN-5/8/18 and VGN-27/23 mapping are preserved.

### F-04 — Woo core commerce and fulfilment (VGN-60)

- **Reuse:** WooCommerce cart/order/payment and native domain fulfilment bridges. Historical 24 parent products/14 variations, four native reciprocal course/product pairs; no completed transaction evidence. Keep actual payment, order and line fulfilment distinct.
- **Evidence:** BIZ C-01–05, RECON §4.5 E72–77/E94–97; LC-02/28–32, TV-51–55. Decide tax, shipping, payment methods, mixed-basket compatibility, account timing, order eligibility, partial refund/reversal, exception handling and idempotency. Guard historical admin thank-you completion callback before relevant tests; VGN-18/53 and VGN-19 remain their existing Course/route/mail scopes.
- **Acceptance:** safe sandbox or approved test gateway and isolated mail sink; guest physical/ticket flow, required-account line, compatible/incompatible baskets, authoritative paid/unpaid and duplicate events, correct line fulfilment and reversal, no real charges/double issue. Verify correct totals and native provider ownership. VGN-27/34 remain migration-specific.

### F-05 — Subscription and Member Perks (VGN-61)

- **Reuse:** WCS billing, PMPro access, Member Perks benefits. Historical seven levels, fourteen subscription variations, programme mapping and 20 perk records; execution flags disabled. Do not infer an accepted offer or paid lineage.
- **Evidence:** BIZ M-01–04; RECON §4.4 E68/E71/E79–84/E125; LC-03/33–35, TV-46–50. Verify nested repo AGENTS, exact release and runtime provenance (historically 49 changed/41 runtime-only production paths, different larger all-file population). Resolve WCS/PMPro cancellation authority, paid-through/grace, gifts, tier hierarchy, overlap, quota, retention and pricing before benefit activation.
- **Acceptance:** approved programme-specific benefit matrix; native billing → access → eligible benefit with correct price, failure/retry/cancel/grace/renewal, independent grants preserved, no duplicate charges or claims. Use existing VGN-4/6/7/18 for Golden Journey; F-05 is full policy/release delta, not duplicate implementation.

### F-06 — Learning and Mentorklas (VGN-62)

- **Reuse:** native Masteriyo and four stored reciprocal Woo links; only synthetic Course had curriculum at baseline. Existing VGN-45/8/19/18 own representative enrolment, progress, purchase and email proof.
- **Evidence:** BIZ L-01–05; RECON §4.6 E86–91/E125; LC-05/33–35, TV-56–60. Native feature review for cohort/scheduled learning, assessments, certificates, enrolment sources, progress and expiry/history; Mentorklas as membership offering with appropriate non-course content authority.
- **Acceptance:** one catalogue/dashboard; free/paid/member/gift access distinct; progress/resume, assessment/completion/certificate and cohort transitions proven as approved; failure/retirement/retention and user isolation tested. VGN-26/33 handle source LearnDash/Masteriyo migration separately.

### F-07 — Events and ticketing (VGN-63)

- **Reuse:** Tickera, Woo Bridge, installed add-ons; Event Participation for contributors, not attendees. Existing VGN-9 regression, VGN-51 draft frontend, VGN-28 source plan; Fast Check-in and EventSales are downstream, external evidence outstanding.
- **Evidence:** BIZ EV-01–05; RECON §4.7 E93–99/E125; LC-06/36–40, TV-61–66. Define inventory/seat/issue, named/unnamed attendee, transfer, refund, admission, duplicate/offline reconciliation and organiser read-only scope. Counts/HPOS placeholders do not prove paid admission. Avoid mutating attendance getter in read-only inspections.
- **Acceptance:** stable order-line/ticket/attendee/scan identifiers reconcile; correct paid/free/VIP/group/comp/reserved flows as approved, duplicates and offline conflicts recover, refunds/voids propagate, organisers see only authorized event reporting. EventSales integration scoped to verified external contracts, not a new reporting truth. Preserve real ticket liabilities; VGN-35 migration separate.

### F-08 — Digital library / reader (VGN-64)

- **Reuse:** existing reader app subject to actual inspection; Woo commerce and a single approved digital entitlement source. Podcast distribution and native LMS downloadable flags are not proof of book delivery.
- **Evidence:** BIZ C-04/05 and I-01/02; RECON §4.5 E103/E118/E125; LC-04, TV-53/55. Approve licence, ownership duration, grant/reversal, storage, browser/app identity, streaming/download/offline and recovery rules.
- **Acceptance:** the same approved identity/rights grant drives app and browser access; verify eligible and ineligible access, retries, expiry/refund and independent ownership preservation. VGN-25 and VGN-27/34 maintain historical asset and order lineage.

### F-09 — Editorial and Leefstyl (VGN-65)

- **Reuse:** seven canonical semantic roots; editorial format/collection, Bricks queries, FCM and HappyFiles/legacy consumer mappings as applicable. Do not conflate unset optional home feature with FCM failure.
- **Evidence:** BIZ E-01–04/MP-03–04; RECON §4.2/4.13 E53–59/E66/E100–104/E122; LC-07/20/24, TV-36–40. Define editorial publication, commercial placement and promotional/partner/related-content precedence; separate access restriction from format and indexing.
- **Acceptance:** approved Topic/format filtering, commercial exclusion from ordinary editorial listings, dedicated promotional placements, free and member access, related/featured behavior and editor workflow. Use VGN-3/43/44/52 for overlapping existing proof; source mapping VGN-24/32 separate. No mechanical taxonomy remap.

### F-10 — LIVE, podcasts and media (VGN-66)

- **Reuse:** existing `uitsending` records, both observed taxonomy generations and published URLs until reconciled. Restream and YouTube are owner-reported distribution/replay authorities, not verified integrations.
- **Evidence:** BIZ S1-Q01–12A, A-01–05; RECON §4.10/4.11 E54/E60/E100–104/E124; LC-11/12, TV-01–35/78–82. Formal editorial seasons, numbering resets, shared Mense, permanent episode URL, live→upcoming→latest replay automatic priority; resolve S1-Q12B override. Separate independent/derived podcast, rights, feeds, site player, access and asset custody.
- **Acceptance:** representative upcoming/live/replay transitions and same URL, editorial feature fallback, presenter/guest roles, season identity/numbering, public LIVE and configurable podcast rights, correct feed/update/takedown semantics. VGN-25/29 source/media/design planning retained.

### F-11A — Programmes and challenges (VGN-67)

- **Evidence:** BIZ P-01–05; RECON §4.8 LC-09, TV-67–71. Confirm Nuwe Jy / 60 Dae Uitdaging relation, Walk in Faith classification and Mentorklas boundary; reuse native provider capabilities before adding programme entities.
- **Acceptance:** a representative dated run and evergreen offering with approved free/paid/member participation, schedule/self-directed content, progress, check-ins, reminders, community, awards and reporting as applicable. Guard late entry, withdrawal, expiry, duplicate rewards, moderation/privacy; no unapproved health-data collection. Connect communication producers under F-12A without circular package dependence.

### F-11B — Competitions (VGN-68)

- **Evidence:** BIZ K-01–05; RECON §4.9 E53/E60/E124; LC-10, TV-72–77. Historical effective keys `kompetisie` and `kompetisie_entry`, no rows in inspected scoped storage; conflicting written keys remain authority issue. Inspect exact current registration and contract before any rename or creation; native forms first.
- **Acceptance:** configured competition/edition, permitted self/third-party entry, verified consent and publication, independent paid/free entry and judging/public/hybrid selection, eligibility and vote limits, fraud/ties, close/result/prize and correction/withdrawal; positive/negative/retry tests. No actual nominee or vote mutation before scoped approval.

### F-12A — FluentCRM and communications (VGN-69)

- **Reuse:** owner-selected FluentCRM, Fluent Forms, FluentSMTP/approved transport, native business-event emitters. Existing VGN-19 owns Course email; do not duplicate sender. Separate marketing contact/consent and transactional obligations.
- **Evidence:** BIZ I-03; RECON §4.12 E114/E118/E126, LC-13/43, TV-84. Historical selected funnel/campaign tables empty; no delivery proof; sent marker is not transport receipt. Define source event, verified contact, segment, opt-in/out, suppression, retention, delivery and retry.
- **Acceptance:** synthetic opt-in/out, safe transactional email despite marketing unsubscribe where appropriate, no accidental re-subscription, duplicate prevention, correct delivery and failure evidence via approved sink. Add producer integrations as domain slices complete. VGN-31 analytics legacy consent issue retained.

### F-12B — Operations and reconciliation (VGN-70)

- **Reuse:** WP-Cron/Action Scheduler and existing provider jobs; no duplicate queue engine. Historical aggregates do not establish health.
- **Evidence:** BIZ I-05; RECON §4.12 E113–120/E126–127, LC-14/42–44, TV-86/87. Define launcher/owner, failed job visibility, safe retry, escalation, reconcile order/entitlement/ticket/learner by stable identity and state, preserve privacy-safe evidence. No auto-financial correction.
- **Acceptance:** synthetic failed/retried jobs recover without duplicate side effects; reconciliation identifies mismatches rather than relying on counts; incident ownership and actionable alerts; complete provider integrations as available. VGN-10/42/55 retained; full restore/prelaunch audit under F-00 and migration gates.

### F-13 — Frontend and editor experience (reuse VGN-29/36 and existing tickets)

- **Reuse:** Bricks/ACSS/Frames, existing templates and queries; historical 32 non-trashed templates, 1,176 classes, 208 variables, 61 components; saved state not rendered proof. No broad redesign or removal on counts.
- **Evidence:** RECON R-X-02, E107–112/E124, LC-41; existing VGN-20/46/44/51/52 for shared header, page quality, Person headings, event and home draft review. VGN-29 design/admin and VGN-36 approved implementation are existing scope; no duplicate F-13 parent ticket.
- **Acceptance:** componentized approved BEM/ACSS-native patterns, correct selected templates and responsive/accessibility/editor behavior for each accepted capability, secure session/cache/access and canonical URLs. Exact draft PR diff/CI/authority before recommendation; preserve owner-gated placements and no unauthorized draft activation.

### F-00 — Deferred broad security and hardening (VGN-71, 72, 73)

- **When:** after functional development, before foundation acceptance and bulk migration/production exposure; scope-specific integrity/permission tests still part of each development slice. VGN-10/16/17/31/41/42/54 remain existing references; VGN-41 remains cancelled/deferred unless owner changes scope.
- **Evidence:** RECON §13, E119/E127, §19. Two distinct November 19 tool-output incidents; no verified containment or compromise. Separate VGN-72 Frames and VGN-73 sibling-workspace classification from November 17 cache-salt VGN-54. Do not reopen raw logs or secret-bearing output.
- **Acceptance:** approved comprehensive audit and remediation, credential classification/containment evidence, scope and RBAC/REST/MCP, origin/Cloudflare, privacy/consent, cache isolation, SEO disclosure, supported releases, safe logs, backups and verified restore, all material blockers resolved or explicitly dispositioned by authorized owner within policy. No security acceptance by deferral; no scans or production changes authorized here.

### F-14 — Legacy disposition and source migration preparation (reuse VGN-55/23/24–29/42/21)

- **Evidence:** RECON §14, E121–128: 42 analytical candidates and 21 LEG amendment proposals; zero removal candidates established, one documentation-only archive candidate. Current full LEG and approved authority require exact direct review.
- **Acceptance:** each retirement candidate has observed consumer/producer map, replacement and historical/rights preservation, owner-approved disposition, backup/restore, reversible controlled steps and post-change verification. Source inventories and mapping (IDs, transactions, entitlements, URL/SEO, media rights) under VGN-23/24–29; keep source and target separate. No blind deletion or bulk migration. Preparatory read-only inventory can occur in parallel with approved development; mutations and retirement remain gated.

### F-15 — Foundation acceptance (reuse VGN-56)

- **Acceptance:** every in-scope capability reaches actual verified and owner-approved target state using VGN-56; VS1 VGN-11 proves its bounded slice only. Negative/failure/retry/permission, end-to-end and operational evidence complete; no unexplained critical financial or rights mismatches; deferred security reviewed; source mapping and reversible rehearsal contract accepted before bulk import. Distinct VGN-32–36 execution gates and VGN-30 cutover remain separately authorized. Foundation acceptance is not production launch.

## 6. Existing work reuse and Kanban operation

Preserve existing Plane modules 00–13 and their issues. This new development module fills target capability completion gaps, not the source-migration or Golden Journey scope. Existing work should close within its original scope and be linked as evidence in corresponding work package. Do not create a second VGN-18, VGN-45, VGN-51/52, VGN-6–11 or VGN-23–36. If exact existing issue scope overlaps a new parent, reuse the existing implementation task instead of duplicating a PR. Retain VGN-37/38 governance (owners/cadence/source↔Plane), VGN-21 stale ledger, VGN-22 historical Drive review. No changes to their workflow states were made in this turn.

| Plane state | Entry / exit discipline |
|---|---|
| Backlog | Known need; dependency, decision or authorization pending. Not permission to start. |
| Todo | Next scoped planning/model reconciliation or prepared task; verify authority/dependencies before change. VGN-57 is the next Todo. |
| Ready | Scope and authority approved; prerequisites clear, acceptance and rollback defined; separately authorized execution. |
| In Progress | One active bounded deliverable per assigned agent. If unrelated issue found, log and return to task. |
| Blocked | Named blocker, owner, evidence and unblock criterion; pause affected work only. Do not move unrelated work. |
| In Review | Exact PR diff/CI and governing sources or actual authorized runtime evidence under independent review; no automatic merge. |
| Done | Completion criteria and scoped evidence accepted; not proof of global launch readiness. |
| Cancelled | Explicit owner disposition, retain evidence and reason. |

**/to-tickets practice:** A parent work package is a scoped backlog container with outcome, authorities, dependencies and acceptance. Before coding, decompose the next bounded delta into an existing issue or one atomic child task (decision, implementation, or verification). No duplicate task for an existing open issue. Define exactly which files, contracts, tests and target are in scope. Do not generate speculative dozens of tickets for unknown native capability gaps. Prefer vertical slices that deliver working behavior, not horizontal rewrite efforts.

Ticket template: `[F-xx.y] action + outcome`; requirement trace keys and exact evidence; current scope/non-goals; predecessor/blocked-by; owner decisions required; permitted mutation/environment; acceptance cases; lifecycle delta; expected source and test artifacts; rollback; independent review gate. Report exact state and links at handoff.

## 7. Definition of done for every development slice

1. Read current relevant AGENTS, exact approved contract and applicable RECON rows; compare actual source at pinned revision and runtime only where authorized and necessary. No historical-to-current inference.
2. Reuse native APIs and established owners; justify minimal custom code with measured native limitation. Explicitly approve missing business rule before implementation, not candidate enums.
3. Every meaningful lifecycle has states, allowed transitions, actor/trigger, preconditions, guards, invariants, effective time, side effects, idempotency/concurrency, failure/recovery, terminal state and audit trail. Include meaningful negative and retry cases. Reuse exact locked lifecycle contracts instead of rewriting them.
4. Work in scoped branch/worktree; clean diff, clear labels/comments, project PHP minimum and exact CI; no unrelated refactor. No unapproved writes to local or production, no real charges/mail/imports. Use existing PE-007/PE-008 and runtime authorization as applicable; verify target/scope and safe fixtures/sink first.
5. Independently review exact PR diff + CI + all authority references BEFORE any merge recommendation; do not infer merge acceptance from green tests or agent claims. Verify actual installed and loaded source separately from source merge.
6. Update Plane with proof, open findings, dependency status and acceptance decision. Keep business-approved intent distinct from behavior acceptance. Preserve predecessor register snapshots; amend only on material new decision/evidence.

Track per-capability `SOURCE_MERGED`, `RUNTIME_APPLIED`, `RUNTIME_LOADED`, `BEHAVIOR_VERIFIED`, `BUSINESS_APPROVED`, `MIGRATION_READY` separately. VGN-56 lifecycle (RECON §19): DISCOVERY → MODEL_RECONCILED → DESIGN_APPROVED → IMPLEMENTED → INTEGRATION_VERIFIED → FOUNDATION_ACCEPTED → MIGRATION_READY, with owner-approved transitions and rollback to prior valid stage. All 13 historical domain reports PARTIAL; no acceptance upgrade in this document. Unknown is not pass, and unknown is not proof of absence.

## 8. Deferred finding protocol

If unrelated security, performance, cleanup, UX or architecture concern is found, search Plane for an existing matching issue, then add a privacy-safe evidence comment or create a new issue if genuinely distinct. For security, label Security & Privacy and group under deferred F-00 module; use explicit scope, observed evidence, affected version/environment, consequence, priority, owner/approval needed, remediation and verification. Never include secrets or personal data. Continue the original task unless the issue actually blocks a safe correct slice. Distinguish conditional code risk from demonstrated behavior; do not downgrade known serious risks merely because execution is deferred.

Active finding record: VGN-71 comprehensive audit; VGN-72 Frames incident; VGN-73 sibling incident; existing VGN-54 cache-key salt; VGN-16/17 disclosure/SEO; VGN-10 quality and scoped access/cache; VGN-41 historical cancellation; VGN-31 analytics consent. Financial/entitlement correctness issues (admin thank-you, cancellation, Perks provenance, email/issuance idempotency) remain in relevant development gates and are not deferred to general security.

## 9. Immediate agent handoff — F-01.1

Start [VGN-57](https://app.plane.so/symphony-development/browse/VGN-57), not F-00. Read BIZ domain E/MP and RECON relevant §4/§7/§11/§12, applicable current GitHub authorities and existing VGN-21/24/43/44/55. Pin the actual branch/commit and permitted scope. Establish the smallest remaining canonical schema/relationship decision needed for the next real development slice. Distinguish already implemented, incomplete, conflicting and unknown. Do not inventory the whole site again. Preserve existing Topic/Person/contextual relationships and transitional consumers. Present one bounded implementation plan with tests and exact authorities; use existing issue where it matches or create a child only for genuine gap. Owner approval and scope before implementation. Record unrelated findings and continue.

## 10. Completion of this planning transaction

- 17 new Plane children VGN-57–73 created and verified via module list; 14 development and 3 deferred/security follow-up items. Direct dependency lists for all 17 were read back; 24 edges. No existing issue closed, moved or deleted. No runtime changes.
- RECON v0.1.10 remains the detailed evidence working copy; this new register does not amend it. Reported manifest identity and historical tests have not been independently repeated in this transaction. No security incident resolved, no source/CI or live application proof granted, no full LEG review or migration performed.
- Proposed repository location `docs/architecture/foundation-reconciliation/VGNOVA_FOUNDATION_EXECUTION_REGISTER_v0.1.0.md`; verify existing repo convention and approved branch before commit. No repository write authorized or performed in this planning transaction; downloadable artifact and actual Plane board are current outputs.
- Future register changes: SemVer in metadata and filename; preserve old versions, exact changed decision and issue references; Plane live status is authoritative for active work. No duplicate roadmap. Record owner/security triage decision separate from development completion.

## 11. Revision history

| Version | Date | Changes |
|---|---|---|
| 0.1.0 | 2026-11-25 | Initial development-first execution register based on BIZ v0.2.10 and RECON v0.1.10; F-00 deferred while retaining affected-slice safety and correctness; VGN-57–73 created, native dependencies and modules verified; existing issues reused for frontend, retirement/migration and acceptance. No implementation, runtime, merge or migration approval. |
