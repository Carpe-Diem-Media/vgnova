# VGN-5B — Local Course → Vriendinne → Course implementation plan

| Field | Value |
|---|---|
| Version | **0.1.0** (working execution plan, 2026-11-09) |
| Source prerequisite | PR #85 merged, `main` `5a934dc87793b243d0b05ee963dcf807f59c1164` at plan creation |
| Scope | One representative local Course and native membership context, with **no checkout** |
| Target | Registered `vgnova-local-10039` on `http://localhost:10039` |
| Authority | [VS1 spec v0.2.0](../specs/2026-10-25-vs1-golden-journey-product-tech-spec_v0.2.0.md) §§14–26; [current-state handoff](../../architecture/VG_NOVA_VS1_IMPLEMENTATION_HANDOFF_v0.1.0.md); repository AGENTS.md; Plane VGN-5 |
| Authorization | **Planning and read-only preflight only**. This document does NOT authorize source merge, LocalWP deployment, runtime mutation, creation of test accounts, or live business transactions. |

## Desired observable experience

From published representative Masteriyo Course **1889**, a **real visitor** explicitly chooses Vriendinne membership information. The site saves the Course origin, opens the universal Vriendinne page **933**, shows a restrained contextual panel ("Jy het na hierdie kursus gekyk") and a working server-resolved **Back to Course** link. **No Thanks** dismisses the contextual reminder without redirecting the visitor into membership or silently reactivating the intent; **Close** only hides a prompt without changing stored state. A second explicit Course replaces the first. Guest intent survives a browser restart; if a legitimate test account logs in, intent promotes to user meta. There is **no claim** of completed membership, price advantage, accessible paid Course or checkout.

**Scope split**: VGN-5A source has merged. VGN-5B is guarded *local runtime deployment and native Bricks binding*, plus any independently reviewed minimal source follow-up needed for correct dismissal or cache semantics. Do not combine all mutations into one broad unchecked operation.

## Phase A — Read-only preflight (may proceed without runtime mutation authorization)

**A1 — Source/CI**: Fetch exact current `main`, ensure PR #85 was merged and post-merge CI is complete and successful. Read the exact six-overlay build contract and PE-008 deployment contract. Confirm `site-code/bin/deploy-platform-core.py` exists, the installed runtime plugin version/tree, exact source-to-runtime differences and whether a deployment would overwrite legitimate runtime-only changes. Do not manually copy files or use unverified archive bytes.

**A2 — Registered target**: Confirm `vgnova-local-10039`, WP root, DB socket and coordinator `--status` with both DB locks and host lock free. If the coordinator/database or target is wrong, STOP. Never use DanBricks port 10049.

**A3 — Bricks snapshots**: Using Bricks MCP read only, retrieve full ordered Bricks trees, native document digests and rendered output for Course Single template **1733**, Course **1889**, Vriendinne page **933**, and the current shared header only as context. Determine the exact existing Course disclosure link/button ID and the smallest native Form replacement/binding; don't assume the old VGN-4A element positions remain stable. Page-933 previously recorded 256 elements/digest `fee120a1027d1d4dec4f06c40f140a22b1f6a76a472252a7a21e98422650e56e`/revision 1899: this is evidence to compare, **not an automatic write guard**. Capture actual before-images for each proposed edit.

**A4 — Provider support**: Verify Bricks native Form action registration on the **installed** version, correct field IDs/serialized structure and dynamic Course ID in a template context (must resolve `1889`, not template `1733`), dynamic nonce, action payload, result/redirect handling and Bricks conditions. Confirm installed plugin/class status. Inspect WP/Bricks page cache and local Cloudflare configuration where applicable. Do not assume a response-local `nocache_headers()` call prevents previously cached user-specific HTML from being served.

**A5 — UX and source gap**: `JourneyIntentBricks::handle_form_action()` currently uses `type=redirect` to `/voelgoedvriendinne/` for **both** Start and Dismiss. That is incompatible with an honest "No Thanks" action on the landing page. Prepare the **smallest source-controlled follow-up** that returns a valid in-place acknowledgement for Dismiss and does not send visitors back into membership; add a regression that confirms no redirect and `DISMISSED` persistence. Review its exact source diff and CI through a new PR before putting it into the deployed artifact. Keep Start redirect to membership. If actual Bricks behavior reveals a different required contract, document the evidence and implement that native contract instead; do not invent unsupported response shapes.

Deliverable **VGN5B_READONLY_PREFLIGHT**: current `main` SHA/checks, runtime parity and target identity, exact template/page IDs and before-image digests, proposed specific Bricks element changes, native Form verification, cache path and the minimal source follow-up if needed. This is enough to authorize exact subsequent writes without a general architecture audit.

## Phase B — Narrow source correction (if Phase A confirms it is needed)

**Existing candidate to inspect first:** [PR #87 — No Thanks in-place result](https://github.com/JCSchoeman96/vgnova/pull/87) was opened after the initial handoff. It is a **source-only proposal**, not automatically merged or deployed. Use its **current exact head**, review/test/CI and authority before deciding whether a separate patch is still needed; do not duplicate the fix in another subsystem.



- Change only the Start-vs-Dismiss result handling and related tests/source documentation. Preserve R2 protections: stale dismissal must be an error; authenticated promotion before dismissal; guest cookie never cleared on failure; no return-to/browser URLs.
- Confirm native Bricks feedback type and whether a page refresh or front-end state re-render is necessary. A successful server-side dismissal must not leave a misleading still-active reminder in the DOM or unexpectedly navigate.
- Add and run focused regression; run full `site-code/bin/check-source.sh`, mutation policy, deterministic six-overlay build, PHP 8.5 lint and new-head CI.
- Submit a **source-only PR for independent diff/CI/authority review** before merging. Do not bundle a LocalWP plugin-file copy into the source change.
- Do not introduce new DB tables, CPTs, services, JS frameworks or commercial state.

## Phase C — Deploy the exact source artifact to LocalWP (separate scoped authorization)

1. Obtain explicit owner authorization for deploying the exact reviewed `main` commit to registered `vgnova-local-10039` using existing **PE-008** under **PE-007**. Confirm PE-008 read-only `status` and no unresolved recovery journal. Back up current plugin tree, compare manifests and preserve legitimate runtime differences.
2. Build the canonical Platform Core artifact from that exact commit using the verified, repository-controlled build toolchain; verify its manifest and hashes before touching runtime.
3. Start the PE-007 mutation coordinator session with registered target/root/socket/owner purpose and run PE-008 only within its authorized child/session boundary. Do not bypass it with `cp`, SFTP, root shell or direct SQL. If health, origin request, tree fingerprint or lock checks fail, fail closed and use documented guarded recovery—not an ad-hoc overwrite.
4. Validate plugin health and include/registration once deployed; confirm Bricks Form action is exposed, WordPress boot has no fatal error, and existing Person/Event/Product consumers still load. Retain rollback artifact, session evidence and deployed commit digest.

**No VGN-5B source deployment authorization exists merely because PR #85 was merged.**

## Phase D — Bricks-native binding (separate exact before-image and PE-007 scope)

**Course template 1733**: Replace or adapt only the approved Course→Vriendinne CTA inside the VGN-4A disclosure to explicitly submit native Bricks Form action `vgpc_journey_intent`. Include hidden `vgpc_journey_intent_action=start`, the current Course's resolved `vgpc_journey_course_id`, and `vgpc_journey_intent_nonce={vgpc_journey_intent_nonce}`. Verify real Bricks `get_fields()` names: `form-field-vgpc_journey_intent_action`, `form-field-vgpc_journey_course_id`, `form-field-vgpc_journey_intent_nonce`. Do not touch unrelated Course content, price, instructor relation or Masteriyo controls.

**Membership page 933**: Add a small native, conditional Course-context panel (only when `{vgpc_journey_intent_active}` reports active). Display current stored Course title via `{vgpc_journey_intent_course_title}`; link to `{vgpc_journey_intent_return_url}`; add a native `dismiss` Form with hidden current intent ID `{vgpc_journey_intent_id}` and nonce. Never use `return_to` URL from query parameters or store price/tier in the intent. General comparison cards, six prices, billing switch and CTA anchors remain unchanged.

**No Thanks vs Close**: "No Thanks" must transition `ACTIVE → DISMISSED` and hide the reminder (no membership redirect); a close button may only hide temporarily without marking `DISMISSED`. If native Bricks cannot realize both safely with the approved small UI scope, implement only the truthful minimal return/dismiss path and report that specific UI limitation rather than creating a parallel frontend system.

**Access/caching**: The context panel must not leak another visitor's Course through HTML/page cache or fragment cache. Demonstrate anonymous A and B independent sessions, fresh reloads and cache hits/misses. Confirm correct cache bypass/segmentation for dynamic tags at origin and through Cloudflare as actually deployed; no claim of proof when Cloudflare is absent locally. Prefer serving personal context from a non-cacheable controlled endpoint if caching cannot safely be proven with template conditions; such a new endpoint needs a separate narrow source proposal and review.

## Phase E — Local acceptance matrix

| Scenario | Required observation |
|---|---|
| Guest visits Course only | No intent or guest cookie created |
| Guest explicitly chooses Vriendinne | ACTIVE intent persisted, membership page opens, context identifies the same Course |
| Browser reload/restart | Active Course context and fixed safe return survive within TTL |
| Back to Course | Server-resolved, published, same-site Course permalink; return itself does **not** set COMPLETED |
| New Course chosen | New Course wins; no duplicate active record/history |
| Stale No Thanks | Error; newer intent retained; **no success redirect** |
| Current No Thanks | DISMISSED persisted; panel no longer shown; later reload does not resurrect it |
| Close | Temporarily hidden but intent remains ACTIVE (if Close is exposed) |
| Anonymous → authenticated | Approved test account only; user meta carries intent; guest cookie cleared after durable success |
| Auth/promotion failure | Fail safely and retain legitimate guest intent; no false success |
| Invalid/unpublished/unsafe target | INVALID or safe non-Course fallback; never redirect externally |
| Expiry | EXPIRED at configured boundary; no return destination |
| Anonymous A vs B/cached response | No cross-visitor Course title, cookie, nonce or intent ID leakage |
| Keyboard and mobile | 320/390/412/1440 widths, focus/Tab/Enter/pointer, no newly introduced overflow/console errors |
| Regression | Six availability CTAs, recurring-year prices, Course content, Header, unrelated Bricks elements unchanged |

**Hallmark:** retain the recorded page-933 baseline **19 FAIL / 7 REFLEX / 0 WARN** and separately attribute new/inherited findings. Full frontend pass cannot be inferred from a narrowed component pass. Body overflow was previously observed and remains in VGN-20 unless a new candidate makes it worse.

**Test identity/side effects:** use only an isolated, explicitly approved local test account if necessary; no real customer identities, memberships, Woo checkout, orders, Masteriyo enrolment, outbound email or webhooks. Never claim membership activation or Course savings. Comply with safety isolation for browser/FPM and background workers if tests would trigger notifications.

## Deliverable and stop conditions

Report **VGN5B_LOCAL_RUNTIME_PASS** only when native Form submits, persistence, return/dismiss, cache separation and page-specific responsive regression are directly observed with the exact deployed artifact and saved Bricks diffs. Otherwise report **VGN5B_BLOCKED** with one concrete cause and the smallest proposed next action. Keep VGN-5 open until its actual completion/acceptance gate is satisfied; `COMPLETED` remains unavailable in this slice.

**Immediate next authorized action:** Phase A **read-only preflight** only. Any Phase B code commit requires a PR and exact independent review; any Phase C/D runtime write requires separate explicit scoped approval and fresh PE-007/PE-008 guards. No broad architecture refactor, production deployment or paid conversion is implied by this plan.
