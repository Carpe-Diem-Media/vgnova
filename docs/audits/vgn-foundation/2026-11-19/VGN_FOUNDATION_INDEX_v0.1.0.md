# VG Nova foundation audit — index

| Property | Value |
|---|---|
| Document | `VGN_FOUNDATION_INDEX` |
| Version | **0.1.0** |
| Captured | **2026-11-19** (Africa/Johannesburg) |
| Scope | Phase 0 initial connected-source baseline and read-only local evidence request |
| Overall status | **PARTIAL — local runtime not independently accessible through this ChatGPT session** |
| Canonical code | [Carpe-Diem-Media/vgnova](https://github.com/Carpe-Diem-Media/vgnova) |
| Source pinned at inspection | `454346d9e6a2c36864a4bbd944e52596f16e09c1` (protected `main`) |
| Plane tracking | [VGN-55](https://app.plane.so/symphony-development/browse/VGN-55) |
| Local target | `vgnova-local-10039` / `http://localhost:10039` — **registry/source confirmed, not freshly reached** |
| Runtime write permission | **NONE** |
| Documentation merge permission | **NONE — independent review required** |

## Purpose

Establish WHAT exists, HOW it is configured, HOW it works, WHAT has been completed, WHAT is redundant or superseded, and WHAT should happen next. This audit is not a redesign, migration, site cleanup, or production launch.

T3Code/local agent gathers direct read-only runtime evidence; GitHub represents tracked source; Plane represents issue and delivery tracking; ChatGPT independently challenges and reconciles findings. A statement cannot be promoted from planned/source/historical to runtime verified without actual new runtime evidence.

## Documents in this versioned initial checkpoint

1. [Baseline](VGN_FOUNDATION_BASELINE_v0.1.0.md): source identity, GitHub CI, authority matrix, document index, access and unknowns.
2. [Contradiction register](VGN_FOUNDATION_CONTRADICTIONS_v0.1.0.md): cross-authority discrepancies, risk and next evidence.
3. [Phase ledger](VGN_FOUNDATION_PHASE_LEDGER_v0.1.0.md): phased status and exit criteria, without pretending read-only source inspection proves WordPress runtime.
4. [Local evidence request](VGN_LOCAL_EVIDENCE_REQUEST_v0.1.0.md): precise T3Code handoff for read-only local collection and secure redaction.

These initial documents DO NOT constitute a completed 10-phase audit or a verified list of deployed WordPress plugins, ACF fields, CPTs, taxonomies, templates, orders, or customers.

## Evidence classifications

`BOTH_CONFIRMED` — directly verified canonical source and current runtime agree.

`RUNTIME_CONFIRMED` — direct observation from the current LocalWP runtime.

`SOURCE_CONFIRMED` — verified from the pinned canonical repository only.

`HISTORICAL_ONLY` — earlier agent/issue/document observation not freshly replicated.

`INFERRED` — hypothesis pending proof.

`UNKNOWN` — access or evidence insufficient.

`CONFLICT` — observations or authority records disagree.

For every important finding, retain inspection date/time, environment/ref, exact paths or IDs, owners, readers/writers, evidence, confidence, limitations and related Plane issue. Record exact times when available; do not fabricate them.

## Completion and gate rules

Phase 0 cannot be marked VERIFIED before the registered LocalWP identity, working-tree/current checkout SHA, actual WP/PHP/database/theme/plugin baselines, deployment/ACF source parity and current meaningful access limits are verified on the local machine.

Use stages `SOURCE_MERGED`, `RUNTIME_APPLIED`, `BEHAVIOR_VERIFIED`, `BUSINESS_APPROVED` and `LAUNCH_READY` independently. Plane completion labels do not substitute for these proofs.

**No WordPress database writes, fixture creation, plugin changes, ACF sync, Bricks edits, imports, deletions, rollback actions, order submissions, gateway enablement, test-account creation, or additional staging environments.** Proposed documentation-only PRs must receive exact-diff, CI and authority review before any merge recommendation. Do not divert the active Golden Journey.

## Next action

Run the sanitized [local evidence request](VGN_LOCAL_EVIDENCE_REQUEST_v0.1.0.md) in T3Code against the registered LocalWP environment. Reconcile its artifacts against the pinned source and Plane; then issue a SemVer successor and advance Phase 0 from PARTIAL only when its acceptance conditions pass. Continue source-only mapping in subsequent phases with every unverified runtime assertion explicitly marked.
