# Evaluation coordination brief v0.1.0

User-authorized operation on 2026-11-19. Evaluate and document actual VG Nova, never develop, fix, clean up, deploy, commit, push or merge. Parent source baseline main 454346d9e6a2c36864a4bbd944e52596f16e09c1 matches remote main. Initial git status contains only ?? .agents/ and ?? site-code/runtime-tools/__pycache__/. Preserve both. Output folder initially empty. PR 95 OPEN, unmerged, head 771b0c92b1c799c468ce9f74aac63ba2770cab7f. Its docs/audits/vgn-foundation/2026-11-19 directory is absent locally; git show remote PR blobs is permitted as historical unmerged evidence, never checkout.

## Restrictions

No child agents. Write only your exact assigned Markdown file directly in docs/evaluation/. Never overwrite another report or earlier snapshot. Read all relevant AGENTS.md, including nested rules. Load /home/jcschoeman96/.agents/skills/unslop/SKILL.md and apply its rewrite/self-audit. No WordPress records, ACF, Bricks, plugin activation/configuration, SQL writes, migrations, flushes, jobs, emails, orders, enrolments, users, infrastructure or other runtime mutations. No database exports, secrets or private customer data. Inspect tool safety before execution. Prefer scoped read-only metadata and aggregates. Never dump whole options, logs, credentials, customer/contact/order personal details. Use an advisory flock /tmp/vgnova-evaluation-read.lock around expensive database read batches, with no lock creation in runtime; avoid expensive concurrent reads. If safe runtime verification is unavailable report UNKNOWN. Reads may have incidental plugin effects: evaluate these before bootstrap/HTTP/tool use; do not claim absence of side effects without evidence. No active security scans or transactional tests.

Runtime target vgnova-local-10039, http://localhost:10039. Historical root verified exists /home/jcschoeman96/Local Sites/voelgoed-novamira/app/public. Novamira MCP discovery available and reports WP 7.1.5, PHP 8.5.12, but independently verify target identity before runtime abilities. Bricks/frontend MUST use Bricks MCP endpoint /wp-json/mcp/mcp-adapter-default-server, never Novamira endpoint. Read-only local filesystem/WP-CLI fallback permitted if MCP unavailable. Other WP work prefer Novamira MCP per project instruction. Local novamira CLI 1.3.6 available, profiles may help. Never run doctor or other supposedly diagnostic tools without checking for writes. PHP 8.5 minimum, Local PHP may differ from shell default.

## Authorities to read

AGENTS.md; docs/architecture/IMPLEMENTATION_LEDGER.md; docs/architecture/LEGACY_DECOMMISSION_REGISTER.md; docs/architecture/VS1_CURRENT_REALITY_AUDIT_v1.1.0.md; docs/architecture/PLATFORM_CORE_ASSEMBLY_CONTRACT_v1.5.0.md; docs/architecture/VGNOVA_RUNTIME_MUTATION_COORDINATION_v1.2.0.md and its v1.1.0 base as relevant; docs/architecture/PLATFORM_CORE_RUNTIME_DEPLOYMENT_v1.0.1.md; docs/superpowers/specs/2026-10-25-vs1-golden-journey-product-tech-spec_v0.2.0.md; docs/superpowers/plans/2026-11-10-vgn-5b-runtime-binding_v0.1.1.md. Historical documents never establish current runtime.

Plane project e0f4a753-a9d1-447d-974a-fa48ae8912b2, Voelgoed Nova Rebuild and Launch. Live VGN-55 1782fd6f-206f-432b-bf85-0ad97c0a43bb is started, audit tracker. Its text says VGN-50 comments record November 11 deployment, old ledger outdated; VGN-18 comments record November 17 checkout page success, no payment/enrolment proof. Inspect relevant live issues and comments, read-only. Do not create/update Plane issues, parent handles tracking. Existing Golden Journey development continues separately.

## Required report structure

Use these exact twelve numbered section headings:
1. Executive Summary
2. Inspection Scope and Evidence
3. Component Inventory
4. Current Architecture and Configuration
5. How It Works
6. Source Versus Runtime
7. Completeness and Functionality
8. Problems, Risks and Contradictions
9. Redundancy Candidates
10. Recommendations
11. Outstanding Verification
12. Final Domain Assessment

Markdown tables explicitly requested by user are permitted in report files. Inventory columns Component | ID/Slug/Path | State | Owner | Purpose | Consumers | Evidence. Include substantial domain-specific findings with precise source lines, setting keys, safe command outcomes and timestamps. State exact SHA and runtime; tools, inspection scope and inaccessible sources. Lifecycle descriptions must identify states/terminal states, transitions, guards, side effects, recovery and owning component. Mark no meaningful lifecycle N/A with reason rather than invent one. Separate SOURCE_MERGED / RUNTIME_APPLIED / BEHAVIOR_VERIFIED / BUSINESS_APPROVED / LAUNCH_READY. Use BOTH_CONFIRMED, RUNTIME_CONFIRMED, SOURCE_CONFIRMED, HISTORICAL_ONLY, INFERRED, UNKNOWN, CONFLICT explicitly. Severity P0/P1/P2/P3 requires actual evidence and concrete impact; unknown is not a confirmed defect. Classify completeness honestly. Native-first recommendations; no new subsystem proposal without native capability review. Redundancy requires consumer evidence, replacement, retention, prerequisites, rollback, owner gate; never immediate deletion. Final assessment includes FULL/PARTIAL/BLOCKED coverage, HIGH/MEDIUM/LOW confidence with explanation, YES/NO/PARTIAL runtime verification, blockers and exact verified Plane references. Do not invent numerical confidence for this research task. Add brief documentation/research assessment with Implementation Score N/A and evidence limits.

Parent will inspect COMPLETE reports and independently verify significant claims. Create your report before declaring completion. Final response must give path, inspected inventory totals, coverage, strongest findings, explicit unknowns and tooling/safety gaps. No PR work, no commits.
