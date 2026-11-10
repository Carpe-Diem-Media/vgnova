# VG Nova VS1 — Golden Journey implementation handoff

| Field | Value |
|---|---|
| Document version | **0.1.0** (working snapshot; SemVer is also in the filename) |
| Captured | 2026-11-09, Africa/Johannesburg |
| Project | **VG Nova** — `JCSchoeman96/vgnova` |
| Source baseline | `main` at `5a934dc87793b243d0b05ee963dcf807f59c1164` (PR #85 merge) |
| Runtime target | Registered LocalWP **`vgnova-local-10039`**, `http://localhost:10039` |
| Nature of evidence | GitHub source/CI independently checked; LocalWP/browser findings are agent-recorded in Plane unless a fresh read is explicitly noted |
| Status | **Implementation handoff, not an authorization to deploy, mutate, merge, or claim VS1 acceptance** |

> **Start here in a new agent/thread.** This is the short, current-status navigation document; it does not supersede the locked VS1 specification or the historical architecture gates. Confirm GitHub `main`, Plane and the actual LocalWP state before executing anything. Future status changes must be recorded in a **new SemVer-named successor** (or explicit amendment) without silently rewriting this historical snapshot.

## 1. Source-of-truth priority and working rules

1. **Locked product intent:** [VS1 Golden Journey product/tech spec v0.2.0](../superpowers/specs/2026-10-25-vs1-golden-journey-product-tech-spec_v0.2.0.md), especially sections 11–26.
2. **Repository/agent policy:** [AGENTS.md](../../AGENTS.md): WordPress/Bricks/ACF/plugin native-first, PHP 8.5, canonical Platform Core source in `site-code/voelgoed-platform-core/`, PRs for independent review. For local Bricks use the Bricks MCP endpoint on port 10039, **not** the unrelated port 10049.
3. **Deployment/mutation:** PE-007 registered identity/session/host lock/two DB locks, and [PE-008 guarded deployment](PLATFORM_CORE_RUNTIME_DEPLOYMENT_v1.0.0.md) via `site-code/bin/deploy-platform-core.py`. Use a verified, exact-commit Platform Core artifact; never copy arbitrary worktree contents into the live plugin.
4. **Task-level state:** Plane project `e0f4a753-a9d1-447d-974a-fa48ae8912b2`, especially **VGN-4, VGN-5, VGN-18, VGN-20, VGN-44, VGN-45, VGN-46**.
5. **Live truth:** The local WordPress DB/Bricks templates may contain legitimate non-Git content. A merged code PR does not mean its module has been deployed or that local Bricks templates are updated.

Current source revision is a historical anchor, not a permanent assertion that `main` remains there. Re-read all relevant hashes, PRs and CI at the time of work.

## 2. The VS1 Golden Journey

```text
Home → Nuwe Jy topic → Article → Person (Lynette Beer)
  → Masteriyo Course → contextual Vriendinne discovery
  → Vriendinne comparison / membership flow
  → return to original Course
  → tier-aware Course purchase/access/progress
  → My Voelgoed / consolidated transactional communication
```

**Bounded synthetic local discovery proof:** Nuwe Jy navigation resolves to `/onderwerp/nuwe-jy/`; Article **1887** (`[VG QA] Dink nuut en beweeg vorentoe`), Person **1705** (Lynette Beer), and published Course **1889** (`[VG QA] Klim uit jou groef — Toetskursus`) have been used to demonstrate discovery and native relationships. Course 1889 has sections **1890/1893** and lessons **1891/1892/1894**. These are **representative local QA records**, not evidence of final editorial approval or customer-ready real data.

**Current distinction:** Discovery and truthful membership **information** have locally tested surfaces. The **Journey Intent engine** is reviewed, merged and source-tested but has **not been deployed or connected to those surfaces**. No successful membership checkout, paid Course fulfilment, learner completion or customer email has been proved end-to-end.

## 3. Local Bricks/browser surfaces

| Surface | Current recorded state / evidence | Important limit |
|---|---|---|
| Header **77** | Nuwe Jy menu previously corrected to canonical topic. Header JS duplicate declaration recovered with a single nav script. | Shared Header accessibility and overflow remain in VGN-20/VGN-46; do not casually edit. |
| Person Single template **1734** | Native published-Course query and empty-heading handling tested with local QA data. | VGN-44 remains formally blocked on wider Hallmark/data guard; stale ACF reverse Course ref after Course 1896 deletion was also recorded, not solved by a frontend query. |
| Course template **1733** | VGN-4A added Bricks elements `vgn4a1`–`vgn4a4` for a collapsible Brons/Silwer/Goud information disclosure with Course→Vriendinne link. Existing Course UI kept. | This link does **not** yet create Journey Intent; member-positive rendering was not browser-proved. Re-read exact before-image before binding. |
| Vriendinne landing **933** | Published at `/voelgoedvriendinne/`. VGN-4B replaced draft-product checkout links and unsupported benefits/terms with truthful informational copy. VGN-4C shortened six buttons to `Registrasie-inligting`, added tier/period-specific `aria-label`, kept `#registrasie-inligting`; general CTAs reach `#lidmaatskap`. | After VGN-4C, **256 Bricks elements**, native digest `fee120a1027d1d4dec4f06c40f140a22b1f6a76a472252a7a21e98422650e56e`, WordPress revision **1899** (historical guard only). No live checkout. |
| Existing-member hub **991** | Published at `/my-vriendinneklub/`. Guest view showed public heading/intro but zero perk cards; Member Perks guest query returned none. | Actual active-member access/entitlement was **not** tested; do not add a membership hub CTA based only on guest output. |

VGN-4C browser checks were reported at **320/390/412/1440 px**: 6 availability buttons had one-line visible text, correct accessible names and anchors; all pointer activations and representative keyboard activations passed with no console errors. Hallmark improved **22 FAIL / 7 REFLEX → 19 FAIL / 7 REFLEX**; broader page-wide acceptance remains **OPEN**. Body overflow at mobile sizes was observed (320px viewport: 440 scrollWidth; 390: 448; 412: 455) and the shared hidden Header dropdown is a known suspect, not established as caused by the six-button edit.

**Local-only QA artifacts** reported by agents live under `/home/jcschoeman96/.local/state/vgnova/qa/`; these are not repository-backed downloadable links and may need to be supplied/uploaded to a future independent reviewer.

## 4. Commercial authority — deliberately not activated

| Area | Authoritative source | Recorded state |
|---|---|---|
| Member state | PMPro | Brons **15**, Silwer **16**, Goud **17**; zero PMPro billing values do **not** disprove Woo prices. |
| Subscription billing/price | WooCommerce + Woo Subscriptions | Parents **905 / 908 / 911** are **draft**. Variations **906/907**, **909/910**, **912/913** have configured recurring monthly/yearly sale prices. |
| Benefit eligibility | Member Perks | No approved Course benefit policy; `woo_discounts` disabled and Course claims not proven. |
| Course identity/access/progress | Masteriyo | Published QA Course 1889; no approved Course↔Woo Product mapping for purchase fulfilment. |

Source-checked 2026-11-08 prices, in rand: **Brons R24/month or R240/year; Silwer R39/month or R390/year; Goud R79/month or R790/year** (sales); annual variants **recur every year**, not a one-time payment. Regular monthly R39/R59/R99; regular annual R390/R590/R990. These are **historical configured values**, not permission to display dynamic live pricing without rechecking Woo or to advertise an available checkout.

The landing page does not claim a working membership purchase. The old unsupported **12-month minimum/R100 cancellation-fee** statements and unapproved tier benefits were removed or qualified pending authoritative confirmation. **VGN-18** is the commercial decision/configuration blocker.

## 5. PR #85 — Journey Intent source successfully merged

- PR: [#85 — source Journey Intent](https://github.com/JCSchoeman96/vgnova/pull/85), merged at `5a934dc87793b243d0b05ee963dcf807f59c1164`; accepted head `ba590c16e1d4d4241d79bd9705747c6932307fb1`.
- Canonical source: `site-code/voelgoed-platform-core/includes/JourneyIntent/`, integrated via `site-code/voelgoed-platform-core/integration.patch`. The build contract [v1.5.0](PLATFORM_CORE_ASSEMBLY_CONTRACT_v1.5.0.md) adds JourneyIntent as the sixth deterministic overlay.
- Source regression review: R1 exposed false success on stale dismissal and loss of an unpromoted guest cookie on authenticated dismissal. R2 corrected both. At accepted head, **24 Journey Intent tests** and **34 mutation-policy tests** passed; full source gate and deterministic assembly passed. Independently checked R2 exact GitHub CI: Dashboard, Canonical Source, Platform Gate all SUCCESS; PR artifact publishing SKIPPED.
- **Post-merge CI:** Independently rechecked GitHub at `main` commit `5a934dc87793b243d0b05ee963dcf807f59c1164`: Dashboard, Canonical Source, VG Nova Platform Gate and **Publish Platform Core artifact** all completed **SUCCESS**. Verify the current exact-commit state again immediately before any deployment.
- Runtime deployment was **NOT** part of PR #85; no Bricks Form was added to template 1733 or landing page 933 by that merge.

**Locked lifecycle:** `ACTIVE`, `SUPERSEDED`, `DISMISSED`, `COMPLETED`, `INVALID`, `EXPIRED`. One current intent; newer explicit Course action replaces older; no tracking on mere page view; a true **No Thanks** dismisses while **Close** does not; configurable 30-day initial expiry; signed `HttpOnly` + `SameSite=Lax` guest cookie; authenticated user-meta promotion; server-derived public Course URL only. `COMPLETED` exists as a named state but is **not transitionable** until authoritative commerce activation.

Source adapter: `JourneyIntentBricks.php`, native Form action `vgpc_journey_intent`, dynamic tags `{vgpc_journey_intent_nonce}`, `{vgpc_journey_intent_active}`, `{vgpc_journey_intent_course_title}`, `{vgpc_journey_intent_id}`, `{vgpc_journey_intent_return_url}`.

**VGN-5B implementation risk in merged `main`:** `JourneyIntentBricks::handle_form_action()` returns a membership-page **redirect for both `start` and `dismiss`**. Before binding an actual No Thanks control, explicitly fix/test the dismiss result as an in-place acknowledgement that does **not** navigate into membership. Also verify actual HTTP Origin/nonce field binding and page-cache isolation. Unit-test calls to `nocache_headers()` are not proof of Cloudflare/WordPress/Bricks personalization safety.

**Source follow-up now open:** [PR #87 — No Thanks in-place result](https://github.com/JCSchoeman96/vgnova/pull/87) is proposed on a separate branch, **not merged** in this snapshot. It corrects the dismiss result and adds a regression test; source CI and independent merge review are separate from this handoff. Never deploy this candidate unless its exact head is independently approved and merged.

## 6. Remaining gates and priorities

| Priority | Work | Gate |
|---|---|---|
| **Now** | **VGN-5B**: deploy **verified** merged Platform Core to LocalWP via existing PE-008 under PE-007; wire native Bricks explicit Course start, landing contextual return and No Thanks; prove browser/session/cookie/cache behavior | Source and runtime operations separate; [implementation plan](../superpowers/plans/2026-11-09-vgn-5b-runtime-binding_v0.1.0.md) |
| Next | **VGN-18**: owner-approved membership subscription activation, tier benefit rules, Course↔Woo mapping | Draft parents, disabled benefit flags, untested checkout |
| Next | **VGN-45**: isolated Masteriyo learner enrolment/progress/completion QA | Avoid real notifications, orders and external effects |
| Before launch | **VGN-20 / 46**: shared header, skip links, responsive overflow, focus/contrast, Course/Masteriyo controls | 19 FAIL/7 REFLEX on page 933 remains open |
| Separate | **VGN-44**: Person-role query/ACF historical stale Course refs and QA follow-up | Do not confuse publish-only rendering with stored relationship repair |
| Final | Membership activation → original Course → tier-specific purchase/access → progress → My Voelgoed → one useful transactional email | No production readiness claim yet |

VGN-4 and VGN-5 were still **OPEN** in Plane as of capture; PR merge does not complete runtime acceptance.

## 7. Handoff rules for future coding agents

- Work **one vertical slice at a time**; fix actual user-visible blockers without introducing a new platform or multi-week QA programme.
- Every domain lifecycle needs explicit states, transitions, guards, side effects, terminal conditions, and focused tests; the one-current-intent rule does **not** imply an event history table.
- **Before any merge recommendation** independently fetch and verify the exact PR head/base and diff, current GitHub checks/CI logs, locked VS1 source references and current Plane decisions.
- **Before any local mutation** identify `vgnova-local-10039`, capture a fresh complete Bricks or plugin before-image, verify its full-object authority (distinguish native resolver digest from compact-JSON hash), obtain task-specific scope approval, run the registered PE-007 coordinator and stop on drift. Use current values rather than these historical digests for compare-and-swap.
- Do not overwrite legitimate local edits or `.agents/` content; never silently propagate local runtime changes into GitHub or vice versa.
- Page-specific Hallmark improvements are not a whole-site PASS. Keep inherited Header defects separate and flag genuinely new regressions.
- **Never** treat an authored report, even with checksums, as independent proof of the private LocalWP runtime unless fresh authenticated readback or supplied bytes support the conclusion.
- Never activate Woo Products, checkout, PMPro levels, Member Perks flags, enrollment, email sending, production DNS/Cloudflare, or `COMPLETED` under VGN-5B.
