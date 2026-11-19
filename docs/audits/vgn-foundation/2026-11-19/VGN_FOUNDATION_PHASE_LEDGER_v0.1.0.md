# VG Nova — Foundation audit phase execution ledger

**Version:** 0.1.0  
**As-of:** 2026-11-19, Africa/Johannesburg  
**Tracking:** [VGN-55](https://app.plane.so/symphony-development/browse/VGN-55).  
**Baseline:** GitHub protected `main` `454346d9e6a2c36864a4bbd944e52596f16e09c1`.

## Status rules

Only these phases statuses are permitted: `NOT_STARTED`, `IN_PROGRESS`, `PARTIAL`, `VERIFIED`, `BLOCKED`. A phase is VERIFIED only after all acceptance conditions have direct, appropriate evidence, including current runtime where the phase claims runtime behavior. Historical evidence must remain dated; absence of runtime access is PARTIAL (or BLOCKED if it prevents all progress), not silently passed.

| Phase | Focus | Status as of initial checkpoint | Evidence collected | Exit evidence / next action |
|---|---|---|---|---|
| 0 | Source/target/Plane baseline and authority reconciliation | **PARTIAL** | Live GitHub protected-main SHA/CI/PRs; verified tracked AGENTS, registry, historical docs; Plane issues/modules and VGN-50/VGN-18 comments; contradiction register | Fresh LocalWP PHP/WP/DB/theme/plugins and git checkout/dirty state; deployment and ACF parity; source-owner matrix reconciled |
| 1 | WP foundation, plugins, MU plugins, hooks, cron, caching, email, integrations | **NOT_STARTED** | AGENTS native-first and tracked source paths identified as orientation only | Runtime inventory; per-plugin ownership, settings and source/runtime consumers; source-to-deployment parity |
| 2 | All CPTs, taxonomies, term hierarchies, ACF fields, options and relationships | **NOT_STARTED** | Prior taxonomy/person/product contracts found; no fresh runtime schema read | Complete runtime definitions, term and field counts, registered authority, Bricks/PHP consumers, provenance graph |
| 3 | Custom plugin/source business authority | **NOT_STARTED** | Canonical Platform Core assembly and overlay source paths located | All active code/plugins/snippets + hooks/readers/writers, exact runtime file/hash parity, lifecycle semantics |
| 4 | Woo, Subscriptions, PMPro, Perks, Masteriyo, Tickera, journeys | **NOT_STARTED** | Existing VGN-18/50, locked VS1 spec and native-provider ownership | Native settings, live course-product/membership/ticket mapping and status machines; identify unverified E2E gates without submitting transactions |
| 5 | Editorial data and media health | **NOT_STARTED** | Prior audit and legacy register indexes only | Aggregate record/status/fixture coverage, links/media/SEO/relationships, source-migration reconciliation |
| 6 | Bricks templates, frontend and route consumers | **NOT_STARTED** | Draft PR #91/#92 and linked Plane issues identified | Template/condition/binding/route registry, read-only browser and accessibility evidence |
| 7 | Cross-system consistency and operational risk | **NOT_STARTED** | Initial cross-authority discrepancies recorded in Phase 0 | Prove/close data, access, business, scheduling, cache, notifications and security concerns; no speculative hardening programme |
| 8 | Redundancy, superseded modules, decommission gates | **NOT_STARTED** | Existing legacy register located and read as historical snapshot | Consumer/read/write/scheduled/external proof, replacement, impact/rollback and owner approval per candidate; no actual removals |
| 9 | Complete recap, architecture, gaps, prioritized next work and agent handoff | **NOT_STARTED** | Planned document taxonomy and tracking destination | Versioned consolidated evidence-backed report, precise P0–P3 roadmap, distinction between VS1 and full migration/launch |

## Phase 0 checkpoint

**Status:** PARTIAL.

**Verified findings:** GitHub protected main SHA; four completed successful exact-main checks and corresponding run; PR #94 merged; PRs #91/#92 open draft; Plane project and issues; source target registry. These are SOURCE_CONFIRMED. VGN-50 deployment and VGN-18 checkout are HISTORICAL_ONLY until independently rechecked.

**Current-state summary:** Source code has advanced beyond the Plane project's older summary; LocalWP runtime is not reachable with an appropriate connected tool in the ChatGPT session.

**Historical contradictions:** See [Contradiction register](VGN_FOUNDATION_CONTRADICTIONS_v0.1.0.md).

**Risks/gaps:** Site-specific and business lifecycle configuration unknown today; cannot claim source/runtime parity from CI or a prior browser report. Checkout payment/fulfilment gate incomplete in recorded evidence.

**Redundancy candidates:** No new safe-to-remove conclusions. `LEGACY_DECOMMISSION_REGISTER.md` retained as sole canonical decommission authority.

**Documentation produced:** Index, Baseline, Contradictions, Phase Ledger, Local Evidence Request v0.1.0. No WordPress modifications.

**Plane updates:** VGN-55 tracking entry created; existing VGN-1 and VGN-21 remain distinct.

**Outstanding evidence:** See [T3Code Local Evidence Request](VGN_LOCAL_EVIDENCE_REQUEST_v0.1.0.md).

**Next:** Collect sanitized exact-target read-only T3Code evidence. Reconcile with source; publish successor SemVer checkpoint. Keep Golden Journey development moving independently.

## Phase 1–9 checkpoint policy

At each completed phase retain: phase, status, verified findings, current-state summary, historical contradictions, risks, redundancy candidates, documents produced, Plane updates, evidence still needed and exact next action. A role/record with meaningful lifecycle must enumerate **states, transitions, guards, side effects, recovery/failure behavior and terminal states**, or explicitly state why evidence is unavailable.

No future WordPress mutation is authorised by these audit records; PRs require independent exact-head/diff/CI/contract verification before any merge recommendation.
