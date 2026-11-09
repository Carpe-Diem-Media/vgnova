# VGN-5A — Journey Intent source implementation

> **Execution note:** This is a source-only implementation plan in the isolated `feat/vgn-5a-journey-intent` worktree. Runtime deployment, Bricks template edits, membership checkout, and completion transitions require later review and authorization.

**Goal:** Let an explicit Course-to-Vriendinne action persist one validated Course return intent, support dismissal and replacement, and recover anonymous intent after login without trusting browser URLs.

**Architecture:** Keep the integration in the canonical `site-code/voelgoed-platform-core/` module. Use a minimal HMAC-signed first-party cookie for anonymous state and one versioned WordPress user-meta value for authenticated state. A small repository/service, per-user WordPress advisory lock, Bricks native Form custom action, and a Bricks dynamic return-link tag provide the required boundaries. No custom table, CPT, ACF field, Woo session, PMPro state, product, subscription, enrollment, or template mutation.

## Native capability review

| Capability | What it provides | Fit and remaining limitation |
|---|---|---|
| WordPress core | Public post status/permalink validation, first-party cookies, user meta, nonces, auth hooks, transients and safe redirects | Provides storage and request primitives, but no one-intent lifecycle, signed anonymous record, or Course-aware validation. Use the smallest adapter around core APIs. |
| Bricks | Native Form custom actions and dynamic data tags | Suitable for explicit Start/Dismiss actions and the fixed return endpoint URL. No template is edited in this source pass. |
| ACF Pro | Editorial fields and relationships | Not suitable for visitor state. No ACF schema or relationship change. |
| WooCommerce | Cart/customer session and checkout | Session state belongs to commerce and does not define this lifecycle or its 30-day persistence. Do not change session lifetime or store intent in the cart. |
| WooCommerce Subscriptions | Recurring billing lifecycle | Not an intent store. No subscription changes. |
| PMPro | Membership entitlement and level state | Remains authoritative for membership; it does not own pre-purchase anonymous context. No level or entitlement changes. |
| Masteriyo | `mto-course` identity and Course access | Validate Course type, publication and public viewability; derive permalink through WordPress. Do not enroll or change access. |
| Platform Core | Canonical source for VG Nova integrations | No existing Journey Intent implementation was found. Add a bounded module and assembly wiring here. |
| FluentSnippets | Runtime snippet management | Not used: source ownership requires Platform Core for this integration. |

**Custom development boundary:** No custom storage subsystem is justified. The small Platform Core adapter is needed because native tools provide primitives, not the required one-record state transitions, signature verification, promotion arbitration, or validated return behavior.

## Lifecycle contract

| State | Entry guard | Persistence and effect |
|---|---|---|
| `ACTIVE` | Only after the Bricks custom form action receives an explicit `start` submission with a currently published, publicly viewable `mto-course` ID | One signed cookie for a guest or one user-meta record for an authenticated visitor; 30-day default expiry from the configurable `vgpc_journey_intent_ttl_days` filter. |
| `SUPERSEDED` | A newer explicit Start action targets a different Course while the previous record is `ACTIVE` | The former intent is logically superseded and immediately replaced by the new `ACTIVE` record. No superseded history is retained; the store keeps one current record only. |
| `DISMISSED` | Explicit `dismiss` form action with valid nonce and same-origin request | Persist the terminal state through the original expiry. Repeated dismissal is idempotent; no automatic ACTIVE restoration. A later explicit Start may create a new ACTIVE record. |
| `COMPLETED` | Requires a future authoritative membership activation/order outcome | State is defined but no transition method or browser-facing completion path is implemented in VGN-5A. Return clicks never complete intent. |
| `INVALID` | Stored Course is missing, no longer `mto-course`, unpublished, password-protected, or no longer publicly viewable | Persist invalid state and do not redirect to the Course. |
| `EXPIRED` | `expires_at <= now` | Persist expired state and do not redirect. Expiry takes precedence if both expiry and invalidity are observed on the same read. |

Each intent contains only `intent_id`, `target_type`, `target_id`, `source_type`, `source_id`, `created_at`, `updated_at`, `expires_at`, `status`, and schema `version`. It contains no price, membership tier, entitlement, raw URL, or browser-supplied redirect destination. The Course permalink is regenerated server-side and passed through safe-redirect validation.

## Implementation and test sequence

1. Add focused lifecycle/repository/form tests first. Run the new focused runner and record the expected RED result before implementation.
2. Implement the bounded store and service: signed `HttpOnly`, `SameSite=Lax` cookie with `Secure` on HTTPS; user-meta persistence; strict shape validation; explicit Start/replace/dismiss; 30-day configurable TTL; target lifecycle checks; no completion transition. Keep the guest cookie one day beyond its logical expiry so a later request can record an `EXPIRED` tombstone, then remove it.
3. Implement a per-user advisory lock for login promotion. Under the lock, compare `updated_at`, promote only the newest valid intent, deduplicate by `intent_id`, and clear the guest cookie after promotion. Register promotion on `wp_login` and lazily for an authenticated request that has a valid guest cookie.
4. Register the native Bricks Form action (`start` or `dismiss`) in its action selector and add dynamic tags for the active reminder, Course title, current intent ID, form nonce, and fixed server-side return endpoint. The action verifies nonce and exact same-origin Origin, validates submitted Course ID through WordPress, binds dismissal to the intent ID rendered with that form, ignores any `return_to`, and sets only the approved membership-page redirect after a successful start/dismiss. Visitor-specific reminder tags emit no-cache headers.
5. Add source include and component registration to `site-code/voelgoed-platform-core/integration.patch`; add test-runner wiring and a README contract. Do not edit the runtime plugin or Bricks template 1733/page 933.
6. Run focused Journey Intent tests, the Platform Core source/assembly checks available in this checkout, PHP syntax checks, and relevant existing Platform Core tests. Review the full isolated diff and exact branch head. Record the evidence on VGN-5 without changing its workflow state.

## Acceptance evidence

- A Course view alone produces no intent or cookie.
- Valid explicit Start creates `ACTIVE`; invalid/unpublished Course is rejected without persistence.
- A second explicit Course replaces the first; no history is retained.
- Dismiss persists `DISMISSED`; repeated dismiss and return requests do not reactivate or complete it.
- A stale Dismiss form cannot dismiss a newer replacement intent.
- Expiry boundary and filter override are tested.
- Return destination is derived from the stored Course ID and canonical permalink; `return_to` input and unsafe destinations are ignored/rejected.
- Cookie tampering fails closed; cookie flags are asserted.
- Anonymous-to-user promotion is idempotent, preserves the newest state, and is serialized by a user-scoped lock.
- No runtime DB writes, template writes, checkout, product/level changes, emails, enrollments, deployment, or PR merge occur.

## Later runtime binding, outside this authorization

After exact source review, a separate guarded local task can bind the native Bricks Form action on Course template `1733` to the Course-origin CTA, add the current Course ID and dynamic nonce fields, and add a reminder using the fixed return URL tag plus a Dismiss form. That task must use PE-007 and verify the exact template before-image. No membership purchase, completion event, discount, or checkout is part of that binding.
