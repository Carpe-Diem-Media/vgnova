# VG Nova — Phase 0 contradiction and drift register

**Version:** 0.1.0

**Date:** 2026-11-19, Africa/Johannesburg

**Scope:** Reconciliation of observed GitHub/Plane records and earlier documentation only. No fresh LocalWP inspection.

**Tracking:** [VGN-55](https://app.plane.so/symphony-development/browse/VGN-55).

Status legend: `RESOLVED_AS_HISTORICAL` = chronology reconciles records, but current runtime may still need inspection; `OPEN_EVIDENCE` = unverified, not a proven defect; `ACTIVE_CONFLICT` = current authoritative observations genuinely disagree.

| ID | Earlier authority / claim | Newer evidence | Interpretation | Status | Next evidence / tracking |
|---|---|---|---|---|---|
| C-001 | Plane project overview pins `main=fcb5fd8...` (2026-11-11) | GitHub protected `main=454346d9e6a2c36864a4bbd944e52596f16e09c1` as inspected 2026-11-19; PR #93 and #94 merged | Project overview is a dated projection; **do not overwrite historical wording without an explicit successor** | RESOLVED_AS_HISTORICAL | Update current roadmap projection in a governed follow-up; [VGN-55](https://app.plane.so/symphony-development/browse/VGN-55) |
| C-002 | `IMPLEMENTATION_LEDGER.md` PE-008 row and `PLATFORM_CORE_RUNTIME_DEPLOYMENT_v1.0.1.md` say no verified LocalWP deployment | [VGN-50](https://app.plane.so/symphony-development/browse/VGN-50) 2026-11-11 `DEPLOYED_AND_VERIFIED` comment records deployment `459dea0e508b4658aabdd79ab40755f7`, canonical 62-file tree SHA-256 `a6b77b25d5f6bb45456deeaecdbc4387f47ca3656c19360863bc8fa1e364a5ef`, final journal and installed parity | Older source status text is pre-deployment; **Plane comment is historical runtime evidence**, not today's live read | RESOLVED_AS_HISTORICAL / OPEN_EVIDENCE for current parity | Fresh current installed tree/parity and deployment journal inventory; existing [VGN-21](https://app.plane.so/symphony-development/browse/VGN-21) for ledger drift |
| C-003 | VGN-50 issue body has prominent "NO LOCAL EXECUTION YET" current-heading wording, with older proposed command | Same VGN-50 later completion comment (2026-11-11) and completed issue workflow state | A same-record chronology conflict; prefer newer completion evidence and retain old text as superseded | RESOLVED_AS_HISTORICAL | Surface a successor current-state summary, not edit away provenance |
| C-004 | Earlier audit `VS1_CURRENT_REALITY_AUDIT_v1.1.0.md` reports source/runtime parity PASS on 2026-11-03 for SHA `5413d3e...` | GitHub source has since advanced through PRs #85–#94; installed runtime not reread here | Old parity is real historical evidence but **not transferable to latest main**; deployed Core from VGN-50 was an earlier exact artifact | OPEN_EVIDENCE | Local active Core tree/commit and changed overlay availability; do not assume newest code deployed |
| C-005 | VGN-18 earlier SSL-failure report (checkout wrongly forced HTTPS on local HTTP) | 2026-11-17 Plane comments report guarded one-option correction succeeded, then browser checkout form rendered at `http://localhost:10039/checkout/`; no payment method for guest | URL/render issue reportedly resolved; **payment, order completion and enrolment remain unverified** | RESOLVED_AS_HISTORICAL / OPEN_EVIDENCE for fulfilment | Exact local checkout state, approvals and blockers tracked in [VGN-18](https://app.plane.so/symphony-development/browse/VGN-18) |
| C-006 | `PLATFORM_CORE_ASSEMBLY_CONTRACT_v1.5.0.md` header says "Proposed ... pending PR review" | File is present in protected main at 2026-11-19 inspection | Presence in main verifies tracking, not independent approval or legal contract status; **do not silently mark LOCKED** | OPEN_EVIDENCE | Locate exact approving PR/owner decision and successor status; do not change assembly law by audit assumption |
| C-007 | `ACF_RUNTIME_SOURCE_CUTOVER_v1.0.0.md` records approved prior symlink/source clean and parity | No current LocalWP ACF source/DB read in this session | Current ACF source identity/parity is **UNKNOWN**, not proven failed | OPEN_EVIDENCE | Check active runtime source symlink and DB/local JSON/PHP-owned exceptions read-only |
| C-008 | [VGN-52](https://app.plane.so/symphony-development/browse/VGN-52) and [VGN-51](https://app.plane.so/symphony-development/browse/VGN-51) document implemented draft Bricks designs | GitHub [PR #92](https://github.com/Carpe-Diem-Media/vgnova/pull/92) and [PR #91](https://github.com/Carpe-Diem-Media/vgnova/pull/91) remain OPEN DRAFT as of 2026-11-19 | Local draft evidence is not a source merge, template assignment, publication or frontend business acceptance | OPEN_EVIDENCE | Check live Bricks IDs/conditions and independent PR gate; no activation |
| C-009 | [VGN-53](https://app.plane.so/symphony-development/browse/VGN-53) reports published Product 1935 permalink 404 on 2026-11-17 | [VGN-18](https://app.plane.so/symphony-development/browse/VGN-18) independently reports checkout page rendered from existing cart | Both can be true: product single permalink and checkout route are different surfaces | OPEN_EVIDENCE (not a contradiction) | Local product permalink recheck, consumer mapping; no rewrite flush or URL mutation |
| C-010 | Previous WordPress audits included local environment observations | Only connected WP content tool accessible here exposes `https://voelgoed.co.za`, not `http://localhost:10039` | Cross-environment data substitution would fabricate staging evidence; none performed | OPEN_EVIDENCE | Handoff to T3Code; do not connect through production |

## Governing action

Classify detected redundancy as a **candidate**, not safe-to-delete. Update or propose successor entries within the existing [Legacy Decommission Register](../../../architecture/LEGACY_DECOMMISSION_REGISTER.md). No cleanup work, changes to production or staging WordPress, extra clones, migrations, PR merge or browser test orders are authorized by this file.

### Current highest-impact evidence gaps

- Local WordPress version, PHP, DB, plugin activation, themes, MU plugins, and active source/deployment hashes.
- On-host Bricks assignment/conditional rendering and dynamic-binding inventory.
- Native Woo/Subscriptions/PMPro/Member Perks/Masteriyo/Tickera provider authority and actual relationship mapping.
- Payment checkout/fulfilment/notification and learner access gates.
- Current ACF JSON source parity, CPT/taxonomy registration/consumers, and legacy decommission consumers.

**Phase 0 disposition:** PARTIAL / no current runtime conflicts proven.
