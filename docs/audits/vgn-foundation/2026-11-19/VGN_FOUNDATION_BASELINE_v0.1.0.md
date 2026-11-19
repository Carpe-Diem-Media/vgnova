# VG Nova — Phase 0 baseline

**Version:** 0.1.0

**Inspection date:** 2026-11-19, Africa/Johannesburg

**State:** PARTIAL; GitHub and Plane directly inspected, LocalWP runtime not directly reached from this ChatGPT connector context.

**Tracking:** [VGN-55](https://app.plane.so/symphony-development/browse/VGN-55).

## 1. Current authoritative GitHub facts — SOURCE_CONFIRMED

| Attribute | Direct finding | Evidence |
|---|---|---|
| Repository | `Carpe-Diem-Media/vgnova`; default `main`; visible as public | GitHub repository metadata, 2026-11-19 |
| Branch protection | `main` protected | GitHub branch read, 2026-11-19 |
| Current `main` SHA | `454346d9e6a2c36864a4bbd944e52596f16e09c1` | [Exact commit](https://github.com/Carpe-Diem-Media/vgnova/commit/454346d9e6a2c36864a4bbd944e52596f16e09c1) |
| Head event | PR [#94](https://github.com/Carpe-Diem-Media/vgnova/pull/94), "Fix Journey dismissal focus and skip unchanged Bricks saves"; merged 2026-11-16 18:18:06 UTC | GitHub exact commit and PR records |
| Source tree | GitHub recursive tree query succeeded and was not truncated | GitHub tree for exact SHA, 2026-11-19 |
| Exact-head checks | Dashboard checks, Canonical source checks, VG Nova Platform Gate and Publish Platform Core artifact = **success** | GitHub check-runs for exact SHA |
| Exact-head workflow | GitHub run `43927385765` completed successfully | [Run](https://github.com/Carpe-Diem-Media/vgnova/actions/runs/43927385765) |
| Pending draft PRs | [#91](https://github.com/Carpe-Diem-Media/vgnova/pull/91) Frames event landing; [#92](https://github.com/Carpe-Diem-Media/vgnova/pull/92) Frames homepage | GitHub PR list, 2026-11-19 |
| Recent merged source | PR #93 PE-007 guarded Bricks tree writer; PR #94 focus/no-op-save fix | GitHub PR list and commit tree |

**Boundary:** Four passing checks at the existing main SHA are not evidence of any new audit PR's checks. They prove source CI on that pinned main, not LocalWP configuration, browser behavior or business approval.

Canonical tracked paths observed:

- [AGENTS.md](https://github.com/Carpe-Diem-Media/vgnova/blob/454346d9e6a2c36864a4bbd944e52596f16e09c1/AGENTS.md) — mandatory native-first, PHP 8.5 policy and runtime mutation rules.
- `site-code/voelgoed-platform-core/` — canonical overlay/domain integration source. `voelgoed-platform-core/` — assembly base; do not equate either to the active plugin directory.
- `site-code/acf-json/` — tracked ACF source. Active runtime symlink/DB source needs fresh local verification.
- `site-code/runtime-tools/runtime-mutation-targets.json` — exact registered target; schema version 1.
- `docs/architecture/IMPLEMENTATION_LEDGER.md`, `LEGACY_DECOMMISSION_REGISTER.md` and architecture contracts.

## 2. Target identity, sources and access

| Dimension | Finding | Classification |
|---|---|---|
| Registered target ID | `vgnova-local-10039` | SOURCE_CONFIRMED |
| Registry `home` and `site` | `http://localhost:10039` | SOURCE_CONFIRMED |
| Registry database name / host | `local` / `localhost` | SOURCE_CONFIRMED — registry, not live connection |
| Registered Platform Core entrypoint | `wp-content/plugins/voelgoed-platform-core/voelgoed-platform-core.php` | SOURCE_CONFIRMED — registry |
| LocalWP root | `/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public` | HISTORICAL_ONLY (AGENTS and VGN-50) |
| LocalWP instance ID | `nAZNpFs99` | HISTORICAL_ONLY (VGN-50) |
| LocalWP database socket | `/home/jcschoeman96/.config/Local/run/nAZNpFs99/mysql/mysqld.sock` | HISTORICAL_ONLY (VGN-50) |
| Local checkout | VGN-18 logs 2026-11-17 checkout rendered at HTTP, but guest lacked payment methods | HISTORICAL_ONLY (Plane report, not freshly observed here) |
| Runtime WordPress version | Not gathered in this session | UNKNOWN |
| Runtime PHP patch version | Repository policy is PHP 8.5; current deployed PHP not measured | UNKNOWN |
| Current database engine/version | Not freshly gathered | UNKNOWN |
| Current theme/child theme and active plugins/versions | Not freshly gathered | UNKNOWN |
| Local checkout/Bricks/ACF/REST/MCP availability | No connected tool successfully reached target from this ChatGPT session | UNKNOWN |
| Local checkout Git SHA and dirty state | No local shell access in this session | UNKNOWN |

The connected WordPress content plugin lists only `https://voelgoed.co.za`, not `http://localhost:10039`. **Do not substitute any production data for staging evidence.** A previous 2026-11-03 audit used a local WP-CLI fallback and Local PHP but is a historical snapshot.

## 3. Plane — tracking authority

Verified project: [Voelgoed Nova — Rebuild & Launch](https://app.plane.so/symphony-development), ID `e0f4a753-a9d1-447d-974a-fa48ae8912b2`, prefix `VGN`. Initial read returned 54 existing issues and 14 modules (before creating VGN-55); new [VGN-55](https://app.plane.so/symphony-development/browse/VGN-55) owns this comprehensive audit.

Relevant issue references:

- [VGN-1](https://app.plane.so/symphony-development/browse/VGN-1): existing VS1 local source/runtime preflight. Narrower than this entire foundation audit; no duplication of its acceptance authority.
- [VGN-18](https://app.plane.so/symphony-development/browse/VGN-18): commercial rules and controlled local Course checkout; latest browser comment dated 2026-11-17.
- [VGN-50](https://app.plane.so/symphony-development/browse/VGN-50): completion comment dated 2026-11-11 establishes historical installed Core artifact parity and finalized journal.
- [VGN-51](https://app.plane.so/symphony-development/browse/VGN-51), [VGN-52](https://app.plane.so/symphony-development/browse/VGN-52): draft event and homepage work; source PRs still draft.
- [VGN-53](https://app.plane.so/symphony-development/browse/VGN-53): Product 1935 public permalink 404 as reported on 2026-11-17.
- [VGN-21](https://app.plane.so/symphony-development/browse/VGN-21): earlier stale implementation ledger remediation.
- [VGN-22](https://app.plane.so/symphony-development/browse/VGN-22): historical Drive inventory, not runtime proof.
- [VGN-55](https://app.plane.so/symphony-development/browse/VGN-55): this audit programme, separate from active delivery.

**Authority caution:** the Plane project description last projected `main=fcb5fd8...` on 2026-11-11; the 2026-11-19 GitHub observation is newer. VGN-50 issue *body* still contains old "NO LOCAL EXECUTION YET" wording; its later completion comment, not the old body, describes the 2026-11-11 finalized deployment. Neither is current on-host verification.

## 4. Historical and governing document index

| Document | Record date/version | Nature and limits |
|---|---|---|
| `AGENTS.md` | Updated November 2026 | Active repository policy; describes intended provider ownership and local tool routing |
| `docs/superpowers/specs/2026-10-25-vs1-golden-journey-product-tech-spec_v0.2.0.md` | 2026-10-25 v0.2.0 | Locked VS1 product/tech scope, not proof of deployment |
| `docs/architecture/VS1_CURRENT_REALITY_AUDIT_v1.1.0.md` | 2026-11-03 v1.1.0 | Real prior local Plan A evidence, source SHA `5413d3e...`; superseded for current-state claims |
| `docs/architecture/VG_NOVA_VS1_IMPLEMENTATION_HANDOFF_v0.1.0.md` | 2026-11-09 v0.1.0 | Historical implementation navigation, source SHA `5a934dc...` |
| `docs/superpowers/plans/2026-11-10-vgn-5b-runtime-binding_v0.1.1.md` | 2026-11-10 v0.1.1 | Journey Intent/binding gates, not live acceptance |
| `docs/architecture/PLATFORM_CORE_ASSEMBLY_CONTRACT_v1.5.0.md` | v1.5.0 in current main | Versioned source-artifact assembly law; header says pending PR review—governance status needs reconciliation |
| `docs/architecture/PLATFORM_CORE_RUNTIME_DEPLOYMENT_v1.0.1.md` | 2026-11-10 v1.0.1 | Pre-deployment status; historical after VGN-50 completion |
| `docs/architecture/VGNOVA_RUNTIME_MUTATION_COORDINATION_v1.2.0.md` | v1.2.0 | PE-007 coordination contract; read before any future runtime write, which this audit forbids |
| `docs/architecture/ACF_RUNTIME_SOURCE_CUTOVER_v1.0.0.md` | v1.0.0 historical cutover | Prior local ACF source symlink evidence; do not assume current parity |
| `docs/architecture/IMPLEMENTATION_LEDGER.md` | Current tracked file; many historical rows | Source milestone index; needs successor for PE-008 deployment/status drift |
| `docs/architecture/LEGACY_DECOMMISSION_REGISTER.md` | Snapshot 2026-10-18 | **Existing authoritative decommission register**; historical candidate and removal gates |
| Plane roadmap page `VG Nova Full Programme Roadmap v0.2.0` | 2026-11-05 | Management projection, not current GitHub/main or architecture authority |

## 5. Evidence and authority matrix

| Subject | Governing authority | Direct observation needed |
|---|---|---|
| Intended domain rules / locked VS1 behavior | Approved versioned contracts/spec | Compare source code and runtime behavior without altering contracts |
| Source code / released artifacts | Protected GitHub exact commits, PRs and CI | Trace exact files, hashes, enabled modules and artifact lineage |
| Actual installed WP / DB / Bricks / ACF behavior | Registered LocalWP instance | On-host read-only inventory, effective options, front-end browser tests with no submissions |
| Customer/product/course/member/event data | Native owning plugin/provider and database | Aggregate/categorical counts, IDs and redacted configuration only |
| Work priority and blockers | Plane VGN project | Most recent substantive issue comments and state; project summary may lag |
| Legacy decommission | Existing `LEGACY_DECOMMISSION_REGISTER.md` | Current consumers, replacement, reversible rollback and owner permission |

## 6. Phase 0 acceptance gaps (all outstanding)

1. Confirm Git local checkout/ref/dirty state and runtime deployment source parity.
2. Confirm LocalWP registered identity, exact active database/socket, actual WP/PHP/database/theme/plugin baseline and enabled MU plugins.
3. Verify active Core tree against last known VGN-50 artifact and any subsequent deployment evidence.
4. Verify active ACF JSON source/symlink, DB/local JSON drift and approved PHP-owned schema exceptions.
5. Collect exact Bricks/WordPress read-only tool access and redacted inspection results.
6. Pin meaningful latest active delivery status against on-host/browser evidence, not older issue headers.
7. Index relevant local-only runtime configuration and logs without revealing secrets or customer data.

**Gate result:** PHASE_0=PARTIAL; do not promote to VERIFIED until these are addressed.
