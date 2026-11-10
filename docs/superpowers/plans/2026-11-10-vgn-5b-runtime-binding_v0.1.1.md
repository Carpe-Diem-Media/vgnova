# VGN-5B — Local Course → Vriendinne → Course implementation plan

| Field | Value |
|---|---|
| Version | **0.1.1** (cumulative current-state successor, 2026-11-10) |
| Source baseline | `main` at `5a934dc87793b243d0b05ee963dcf807f59c1164` (PR #85 merge) |
| PRs at review start | #86 docs: `b64717948cb878f6fae6cf199ee8a2666c18c9e6`; #87 source: `00702fe60563d1235bcbc0b440c7f21dc5aff105` |
| Runtime target | Registered LocalWP `vgnova-local-10039`, `http://localhost:10039` |
| Scope | One representative local Course and native membership context; checkout and entitlement remain out of scope |
| Authority | [Locked VS1 spec v0.2.0](../specs/2026-10-25-vs1-golden-journey-product-tech-spec_v0.2.0.md), [AGENTS.md](../../../AGENTS.md), Plane [VGN-5](https://app.plane.so/symphony-development/browse/VGN-5), [VGN-47](https://app.plane.so/symphony-development/browse/VGN-47), and [VGN-48](https://app.plane.so/symphony-development/browse/VGN-48) |
| Status | Current planning handoff only. It grants no PR merge, deployment, WordPress mutation, test-account or commercial authorization. |

This document is the complete current successor to [plan v0.1.0](2026-11-09-vgn-5b-runtime-binding_v0.1.0.md). The earlier [VS1 handoff v0.1.0](../../architecture/VG_NOVA_VS1_IMPLEMENTATION_HANDOFF_v0.1.0.md) and plan v0.1.0 remain unchanged historical snapshots. Use current Plane/GitHub/runtime evidence for any later change; record a later material status as another SemVer-named successor rather than silently rewriting these snapshots.

## Desired visitor journey and locked boundaries

From published representative Masteriyo Course **1889**, the visitor must explicitly choose to explore Vriendinne information. Only that action creates or replaces the one active intent. The universal membership page **933** may then show a contextual Course reminder and a server-resolved return link. A current **No Thanks** action dismisses that intent in place; it must not redirect into membership or revive on reload. A separate Close control, if retained, only hides the panel temporarily and does not change intent state. A newer explicit Course action supersedes the prior intent.

The experience does not claim a completed membership, a Course discount, paid Course access, checkout availability or Course completion. Do not enable products, subscriptions, gateways, Member Perks, enrolment or any `COMPLETED` transition in this slice. Never create intent on a Course page view and never accept a browser-provided return URL as authority.

The locked intent states are `ACTIVE`, `SUPERSEDED`, `DISMISSED`, `COMPLETED`, `INVALID` and `EXPIRED`, with one current intent and an initial configurable 30-day expiry. Persist only the minimum Course identity and lifecycle data. The server validates the stored target and generates its canonical URL; prices, discounts, membership state and access continue to come from their owning systems.

## Current verified source and recorded runtime state

| Area | Current evidence | Limit |
|---|---|---|
| Main / PR #85 | `main` is `5a934dc87793b243d0b05ee963dcf807f59c1164`; PR #85 is merged there. Exact post-merge run `42631519056` passed Dashboard, Canonical Source, Platform Gate and artifact publication. | Recheck exact current main and artifact before any deployment. |
| PR #86 | Open, unmerged, docs-only at the review-start head shown above; base is `main` at `5a934dc…`. Existing v0.1.0 handoff and plan are preserved. | This v0.1.1 update itself must receive independent exact-head review and fresh CI. |
| PR #87 | Open, unmerged, source-only at `00702fe60563d1235bcbc0b440c7f21dc5aff105`; two files: `JourneyIntentBricks.php` and `JourneyIntentTest.php`. Exact-head Dashboard, Canonical Source and Platform Gate passed; artifact publication was skipped for the PR. | Passing source CI does not deploy the code or prove browser behavior. |
| VGN-47 | The read-only diagnosis is marked complete and was accepted by the Master Gate for that research scope. The report identifies the socket mismatch and native Bricks dismissal behavior. | Its LocalWP observations are agent-recorded; acceptance does not authorize a write or substitute for fresh runtime guards. |
| Local Platform Core | VGN-47 reports the installed plugin does not contain the Journey Intent module, so the merged PR #85 source has not yet been deployed to this LocalWP target. No deployment is recorded by this plan. | No fresh runtime inspection is claimed here. |
| PE-007 transport | The reported cause of `DATABASE_CONNECT_FAILED` was the PHP CLI default socket not matching LocalWP's registered site's active socket. The coordinator's supported `--db-socket` option (or `VGNOVA_DB_SOCKET`) selects transport; precedence and propagation are documented in [PE-007 v1.2.0](../../architecture/VGNOVA_RUNTIME_MUTATION_COORDINATION_v1.2.0.md). | A socket path is transport context, not PE-007 session proof. A future authorized operation must freshly validate the registered target, host lock, both independent database locks and session proof. The prior diagnostic is not a live guard. |
| Native Bricks dismissal | Installed Bricks 2.4.5 was inspected during VGN-47. A native Form Success interaction can hide the specifically targeted reminder after a real successful response. PR #87 returns success only after a persisted current/repeated `DISMISSED` result; stale/error results remain errors. This is the recommended UI path; no speculative `refreshPage` result is needed. | No template edit or browser acceptance has occurred. Keep success feedback outside the hidden reminder and prove focus/error behavior in-browser. |
| Cache / accessibility | Personalized cache isolation and browser accessibility remain unproven. | These are mandatory local acceptance gates, not inferred from unit tests or `nocache_headers()`. |
| PE-008 / assembly authority | [PE-008 v1.0.0](../../architecture/PLATFORM_CORE_RUNTIME_DEPLOYMENT_v1.0.0.md) labels deployment tooling proposed/in progress and still references assembly v1.4.0. [Assembly v1.5.0](../../architecture/PLATFORM_CORE_ASSEMBLY_CONTRACT_v1.5.0.md) labels itself proposed/pending review and says it supersedes v1.4.0 after merge. PE-007 v1.2.0 labels its database-transport amendment approved. | Reconcile the PE-008 and assembly version/status against current merged source, PRs and authorized gate records before requesting deployment. A green CI run or published artifact does not resolve this document-authority mismatch or prove deployment approval. |

## Native capability review

| Capability | Use and remaining limitation |
|---|---|
| WordPress Core | Supplies canonical posts, URLs, authentication and request lifecycle. It does not provide this one-current-intent journey behavior by itself. |
| Bricks 2.4.5 | Native Form, dynamic fields/tags, conditions and Form Success interactions can bind and present the journey. It does not store the cross-request intent; the installed server adapter handles that after its source is approved and deployed. |
| ACF Pro | No new field group or relationship is required for this feature. Do not model Journey Intent as an editorial ACF relationship. |
| WooCommerce / Subscriptions | Remain authorities for products and billing; checkout and pricing changes are outside this plan. |
| PMPro / Member Perks | Remain authorities for membership state and configured benefits. Do not infer membership from a click or enable perks. |
| Masteriyo | Remains authority for Course identity/access. Course **1889** is a synthetic local journey fixture; do not alter enrolment or progress. |
| Platform Core | The source-controlled Journey Intent adapter is the existing integration. Keep changes to the currently reviewed source correction and native bindings; no new table, CPT, endpoint, cache framework or service is proposed. |

`CUSTOM_DEVELOPMENT`: no new subsystem. The source correction already proposed in PR #87 is bounded to the Bricks dismissal result and regression coverage. If independent review finds another necessary source change, prepare it as a separate focused source PR before deployment.

## Smallest proposed Bricks binding

The VGN-47 report records template **1733** as a 41-element tree with existing VGN-4A elements `vgn4a1`–`vgn4a4`; the Course-to-membership link is raw HTML inside the accordion rather than a standalone Bricks element. It records page **933** as 256 elements with historical native digest `fee120a1027d1d4dec4f06c40f140a22b1f6a76a472252a7a21e98422650e56e` and revision 1899. These values are historical comparison evidence only. **They are not mutation guards.** Capture complete fresh Bricks before-images, IDs, parent/order data and native digests immediately before any separately authorized write; stop on any difference from the approved candidate.

After the exact approved Platform Core artifact is deployed:

1. **Course template 1733:** Add one native Bricks Form inside the existing VGN-4A disclosure container, as a sibling of its accordion. Bind the existing action `vgpc_journey_intent`; submit hidden action `start`, the current Course ID from `{post_id}`, and `{vgpc_journey_intent_nonce}`. Verify the actual Bricks field names include `form-field-vgpc_journey_intent_action`, `form-field-vgpc_journey_course_id` and `form-field-vgpc_journey_intent_nonce`, with the dynamic ID resolving to Course **1889**, not template 1733. Replace the old raw link only with truthful explanatory copy. No intent is created before explicit submission.
2. **Membership page 933:** Add one small native conditional reminder using `{vgpc_journey_intent_active}`. Show `{vgpc_journey_intent_course_title}` and the server-generated `{vgpc_journey_intent_return_url}`. Add a native `dismiss` form carrying `{vgpc_journey_intent_id}` and `{vgpc_journey_intent_nonce}`. Use a success-only Form Success interaction to hide only the reminder wrapper after the server confirms persisted dismissal. Keep the success message outside that wrapper and visible to assistive technology. Error or stale responses must leave the reminder visible and announced. Do not use `return_to` or any browser-supplied URL.
3. Preserve the existing page-933 prices, comparison content, six availability buttons and responsive corrections, plus all other Course content and Masteriyo controls. Do not add a checkout action or membership price claim.

The Bricks edits are proposals only. A source merge, plugin deployment and Bricks mutation are separate operations, each with its own exact scope and owner authorization.

## Delivery sequence and authorization gates

| Gate | Required work | Write boundary / authorization |
|---|---|---|
| 1. Independent source review | Review the complete updated PR #86 doc diff and current PR #87 source diff against the locked VS1 spec, current Plane decisions, exact base/head and exact-head CI. Resolve review comments. | Read-only review. No merge. |
| 2. Source merges | Obtain explicit owner authorization for **each** PR. Merge only the authorized exact heads through the repository's approved process. | Two distinct merge decisions; this plan and a green check do not authorize either merge. |
| 3. Post-merge source / artifact | Verify resulting `main`, all required post-merge CI, successful artifact publication, and the artifact/manifest source commit and hashes. Confirm the candidate contains the reviewed Journey Intent correction. | Read-only source/artifact verification. Do not build from a dirty worktree or deploy an unreviewed artifact. |
| 4. Authority reconciliation | Resolve the current status of PE-008 deployment contract and Platform Core assembly v1.5.0 against merged source and explicit approval records. Confirm the approved deployment path and recovery authority. | No runtime write until the discrepancy is resolved and deployment authority is explicit. |
| 5. PE-007 fresh guard | With a separate owner authorization for the deployment attempt, resolve the currently registered LocalWP socket, then run the coordinator's supported socket selection and fresh status/preflight. Verify site identity, path and both DB locks before starting a session. Do not manually clear locks. | Coordinator session and deployment require separately scoped approval. `--db-socket` is not session proof. Stop on unknown target, lock or authority state. |
| 6. PE-008 deployment | Deploy only the exact approved main artifact through the repository deployment tool as a PE-007 child. Capture the active plugin before-image, artifact manifest, journal, health/parity readback and retained rollback tree. | Separate explicit authorization for plugin deployment. No manual copy, direct SQL or unrelated plugin changes. Roll back only through the authorized PE-008 recovery path if verification fails. |
| 7. Bricks binding | After plugin health confirms the action/tag registrations, capture fresh complete before-images for template 1733 and page 933. Apply only the approved native Form/condition/interaction elements and verify persisted trees. | Separate explicit authorization for the two Bricks targets under PE-007. Stop on drift or normalization; restore from captured before-images through the guarded path. |
| 8. Browser/session acceptance | Run the matrix below on the approved local target, recording real URLs, response results, state transitions, cache behavior, accessibility observations and rollback/cleanup. | Explicit test scope for any persisted test intents or authenticated test account. Never use real members or external notification/payment services. |

No deployment authorization follows automatically from PR #85, PR #87 CI, this plan, VGN-47 acceptance or the identified socket option. No production deployment or merge is part of this plan.

## Local acceptance matrix

| Scenario | Required result |
|---|---|
| Guest views Course 1889 only | No intent/cookie is created. |
| Guest explicitly starts from Course | One `ACTIVE` intent is stored; the universal membership page opens and names Course 1889. |
| Return link | Server resolves a published, same-site canonical Course URL; return does not set `COMPLETED`. |
| Second Course explicitly selected | New intent becomes current; previous is `SUPERSEDED`; no duplicate active intent. |
| Current No Thanks | Persist `DISMISSED`; no membership redirect; native success interaction hides the reminder; visible status remains accessible; reload/back does not restore the dismissed reminder. |
| Stale No Thanks | Return an error; keep the newer intent and reminder; no hide or success acknowledgement. |
| Repeated No Thanks | Idempotent success for the same persisted dismissal; does not create/reactivate an intent. |
| Close, if present | Temporarily hides only; intent remains `ACTIVE`. |
| Invalid, unpublished or deleted target | Mark/fail safely; never redirect to an untrusted or inaccessible target. |
| Expiry | Configured TTL is honored and an expired target cannot be returned to. |
| Anonymous A vs B and cache hit | Neither response exposes the other visitor's title, intent ID, nonce or return URL at origin or through configured cache layers. Test reload and back-forward behavior. |
| Authenticated continuity | Only with an explicitly approved synthetic account: promote durable state before clearing guest cookie; failed promotion retains valid guest state. |
| Responsive/accessibility | At 320, 390, 412 and desktop widths, verify no new overflow, readable content, labels, focus, keyboard form submission, announced errors/success, and focus behavior when the reminder hides. |
| Regression | Existing Course content, membership comparison/prices, six page-933 informational buttons, billing language and unrelated Bricks elements remain unchanged. No console error or introduced Hallmark FAIL. |

Record the existing page-933 Hallmark baseline of **19 FAIL / 7 REFLEX / 0 WARN** as an inherited result until a fresh applicable run is captured. Do not claim an overall frontend pass while required findings remain. Re-run the narrow supported checks for the changed templates and compare new versus inherited findings.

## Evidence and next permitted action

VGN-47 was accepted as completed read-only diagnosis; the earlier VGN-5B read-only preflight is also complete. Do not repeat either investigation unless fresh state is needed for a later guarded operation. The PE-007 socket transport cause has a supported option, and installed Bricks behavior supports the recommended success-only hide interaction. Those findings resolve the research questions; they do not establish a current live session, deployment authority, browser/cache behavior or merge permission.

The next permitted action is **independent exact-head review of the updated PR #86 and current PR #87**, including diff, CI and authority. If that review passes, request the owner's separate merge decisions. Keep VGN-5 open until the actual local visitor journey and cache/accessibility acceptance pass. Stop before any source merge, plugin deployment, Bricks write or commerce operation unless its separate authority has been granted.
