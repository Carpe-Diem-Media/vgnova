# PE-008 Platform Core runtime deployment status v1.0.1

**Status:** Proposed status successor. Source implementation is merged and fixture-tested. Independent PE-008 operational acceptance and LocalWP deployment authorization are not evidenced.
**Scope:** Status and evidence reconciliation only. The deployment design and safety requirements remain in [v1.0.0](PLATFORM_CORE_RUNTIME_DEPLOYMENT_v1.0.0.md).
**Date:** 2026-11-10

This document preserves the historical v1.0.0 contract. It corrects its implementation and authority status against merged commits and recorded verification. It does not change deployment behavior, approve PE-008 for operational use, authorize a LocalWP write, or replace the PE-007 session requirement.

## Source status

PR [#61](https://github.com/JCSchoeman96/vgnova/pull/61) merged the deployer at `bfd6c98f7375a47307311ed534f273024b949e30` from source head `967a555ca86ab41af7199ae4c357b8c0503b3da6`. The merged source includes the artifact verifier binding, registered-target and PE-007 session checks, staged manifest writes, atomic directory exchange, health and parity checks, durable journals, retained rollback paths, recovery commands, and fixture-based tests.

PR #61 records 45 Platform Core deployment tests and a passing `./site-code/bin/check-source.sh`. Exact-head workflow [40869081004](https://github.com/JCSchoeman96/vgnova/actions/runs/40869081004) completed with Dashboard, Canonical Source, and VG Nova Platform Gate successful. Artifact publication was skipped for the pull request.

PR [#68](https://github.com/JCSchoeman96/vgnova/pull/68) later fixed the proved PE-007-to-PE-008 database-socket transport defect. It merged at `06ec482445ca4065b49e745d2164308c5548ce59` from source head `cc52a154e2c16271ad13dda00b6743a24327559a`. Its verification record reports 61 deployment tests, the runtime mutation suites, and `./site-code/bin/check-source.sh` passing. Exact-head workflow [41031953483](https://github.com/JCSchoeman96/vgnova/actions/runs/41031953483) completed with Dashboard, Canonical Source, and VG Nova Platform Gate successful. Artifact publication was skipped for the pull request. The transport correction is recorded in [PE008 health DB transport fix v1.0.0](PE008_HEALTH_DB_TRANSPORT_FIX_v1.0.0.md).

The socket defect explains the historical LocalWP health-preflight stop. That attempt did not create a deployment journal or stage a candidate, and the runtime tree remained unchanged. PR #68 corrected the transport path. The evidence reviewed here demonstrates no unresolved atomic-swap or recovery defect. The deployment test suite covers fixture rollback and recovery, including Linux `RENAME_EXCHANGE`; it does not demonstrate a successful deployment or recovery on the registered LocalWP site.

## Authority status

GitHub records no submitted review on PR #61 or PR #68. PR #61's description said it must not merge before independent review; the merge did not satisfy that review prerequisite. The v1.0.0 design says Task 10B resumes after PE-008 is independently reviewed. The files and verification above establish merged and tested source, not independent PE-008 approval. No independent PE-008 approval or owner operational authorization was found in the GitHub or Plane records reviewed for this successor.

PE-007 [v1.2.0](VGNOVA_RUNTIME_MUTATION_COORDINATION_v1.2.0.md) is approved for the database transport amendment. The selected socket remains transport context; it does not prove an active mutation session or authorize deployment.

PE-008 v1.0.0 points to assembly v1.4.0. Assembly [v1.5.0](PLATFORM_CORE_ASSEMBLY_CONTRACT_v1.5.0.md) says it supersedes v1.4.0 after merge. Plane [VGN-5](https://app.plane.so/symphony-development/browse/VGN-5) records the 2026-11-09 Master Gate R2 source review as independent and `READY_FOR_OWNER_MERGE_AUTHORIZATION`. PR [#85](https://github.com/JCSchoeman96/vgnova/pull/85) later merged at `5a934dc87793b243d0b05ee963dcf807f59c1164`. Therefore v1.5.0 is the effective source-to-artifact assembly contract. Its `Proposed/Pending` header is stale relative to its explicit supersession clause and merged PR. This resolves the assembly input for PE-008; it does not approve PE-008 deployment. The deployer's schema-3 manifest validation is compatible with assembly v1.5.0's schema 3, which is compatibility evidence only.

## Remaining operational acceptance

The documented safety and recovery mechanisms exist in source and have fixture-test evidence. No specific unresolved code-level safety or recovery deficiency is established by the evidence above. Before using the deployer on `vgnova-local-10039`, the execution record still needs:

1. Independent review of the current PE-008 source and its exact artifact/deployment boundary, followed by an explicit approved operational decision. Merged tooling, green CI, PE-007 v1.2.0, and this status correction do not supply that decision.
2. A corrected post-merge Platform Core artifact from the exact protected `main` commit, with canonical manifest, source SHA, archive and manifest hashes, and `JourneyIntentBricks.php` verified in its file list. PR #87 remains open and unmerged as of this record, so no corrected artifact is identified here.
3. A separately authorized LocalWP deployment using the PE-007 coordinator as parent and PE-008 as its child. Immediately before that write, recheck registered target identity, the selected live database socket, both database advisory locks, host lock, deployment journal/recovery state, and the full active-plugin before-image. Earlier snapshots are evidence, not mutation guards.
4. Post-deployment PHP and HTTP health, exact artifact-to-runtime file and hash parity, and retained prior-tree/rollback evidence. If target identity, lock proof, journal topology, health, parity, or rollback state is uncertain, stop and preserve all trees for review. Use only PE-008's guarded recovery path; do not copy files manually or clear locks.

This status successor does not require a live failure injection or deliberate rollback. Existing fixture tests cover those paths. Any later independent review finding or deployment failure reopens the relevant acceptance question.

## Decision boundary

PE-008 source implementation: **MERGED AND TESTED**.

Independent PE-008 operational approval: **NOT EVIDENCED**.

LocalWP deployment authorization and corrected deployment artifact: **NOT AVAILABLE**.

No deployment, mutation session, Bricks edit, database write, checkout, membership activation, enrolment, or `COMPLETED` transition is authorized by this record.
