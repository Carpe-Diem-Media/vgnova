# Reusable Frames event landing template

VGN-51 creates a separate Bricks content template, **Event Landing — Frames**, ID **1919**. It is a **draft with no placement conditions**. Event Single **774** and all existing event routes remain unchanged.

Open in Bricks: <http://localhost:10039/?post_type=bricks_template&p=1919&bricks=run>.

## Sections and editing

The template contains 69 native Bricks elements. Its seven sections follow the owner's approved order:

1. Hero uses the event title, excerpt and featured image. Missing excerpts and images hide their wrappers.
2. Date, place and time cards use Tickera and the site's existing Afrikaans date/time tags. Missing values show confirmation text.
3. Social proof loops over the testimonials selected for this event. It hides when none are selected.
4. Media displays the event gallery and optional video. It hides when both fields are empty.
5. Booking CTA links to the pricing section.
6. Info uses the WordPress event body, optional programme and optional image. Existing Event Participation query markers and the existing participant card preserve canonical current-event people access.
7. Pricing lists published WooCommerce products linked through Tickera's existing `_event_name` relationship. Native Woo price widgets show current and sale prices. Each card links to its product page for the native buying flow. Events without linked products show a confirmation message.

In **Events**, edit the existing Tickera date/time and venue, WordPress title/excerpt/body and featured image. The new **Event: Landing page** ACF group adds five optional fields:

- **Event: Getuigskrifte** selects up to three existing Testimonial records. Create approved quote/author content in the existing Testimonial editor first.
- **Event: Galery** selects up to twelve local media attachments. Provide appropriate media alt text.
- **Event: Video URL** accepts an approved public YouTube or Vimeo URL.
- **Event: Program en praktiese inligting** adds the programme and practical details.
- **Event: Inligtingsfoto** adds an optional information-section image.

No event field values were changed for this task. No testimonials, events, products, transactions or media were created as fixtures. Test-only ACF values were injected into a single render request, including an explicitly marked QA quote that was not an attendee endorsement.

## Source and ownership

- `site-code/bricks/event-landing/template.json` contains the saved native tree and template settings. When creating a replacement copy, submit its title, type, status, elements and settings to `bricks/create-template`; do not overwrite 774.
- `event-landing.css` is applied through the new template root's Bricks Custom CSS control. It consumes existing ACSS tokens and does not create or alter shared classes, variables or theme styles. The document overflow rule is inside `@scope (html:has(.c-event-landing))`; removing the template restores the original root behavior. Bricks preserves this standard CSS rule in its generated stylesheet.
- `frames-provenance.json` records the five remote Frames structures used. They were read through the configured Frames source, adapted into native elements, and stripped of sample claims, avatars, prices and notes. The fact cards and booking band are native Bricks compositions.
- `site-code/acf-json/group_vg_event_landing.json` is the source definition for runtime ACF group 1913. Its creation used native `acf_import_field_group` through the Bricks MCP, with ACF JSON writing disabled for that one request. This preserved the immutable runtime source worktree and symlink. It does not advance the pinned ACF generation; future source activation still follows the existing project contract.

Every persistent runtime call ran synchronously through the Bricks MCP as the actual PE-007 coordinator child. The host lock and both MariaDB advisory locks remained held until each request completed. No dummy wait process or external uncoordinated MCP mutation was used. The registered Local socket was supplied explicitly; no credential values were copied or logged.

## Verification on 2026-11-11

Exact results and the final saved digest are in `site-code/bricks/event-landing/verification.json`.

- Bricks `get-template` and `get-page-elements` readbacks verified draft 1919, its empty placement conditions and 69 persisted elements.
- `bricks/render-elements` rendered the saved tree against events **542**, **551** and **763**. Tags resolved without literal expressions. Event 763 displayed exactly its four linked ticket products, while 542 and 551 displayed the empty state. No cross-event ticket leakage was observed.
- A request-only fixture exercised all seven sections, testimonial field references, gallery attachments **544/553**, programme and optional information image. It generated no records and reported no PHP errors.
- Full Template 774 data and settings matched the pre-write baseline. Its full-record SHA-256 remained `12ef6f3c08236441edf61ed3b22f393f5a0c42526b2afcf8f8eec77e98cca231`.
- The dedicated ACF runtime worktree remained clean and its symlink target unchanged.

The rendered verification documents use exact Bricks-generated markup/CSS plus the site's existing stylesheet dependencies. They contain the template body, without substituting it into an existing public event route. Existing inline global styles were preserved byte-for-byte in a linked inherited stylesheet so static findings in unrelated site CSS could be distinguished from this template's changes.

Observed commands:

```sh
node ~/.agents/skills/hallmark/scripts/sloplint.mjs site-code/bricks/event-landing/event-landing.css /tmp/vgnova-event-542.html /tmp/vgnova-event-763.html --genre editorial --render --json
node ~/.agents/skills/hallmark/scripts/sloplint.mjs site-code/bricks/event-landing/event-landing.css /tmp/vgnova-event-fixture.html --genre editorial --render --json
node site-code/bricks/event-landing/verify-render.mjs /tmp/vgnova-event-542.html /tmp/vgnova-event-763.html /tmp/vgnova-event-fixture.html
node --check site-code/bricks/event-landing/verify-render.mjs
git diff --check
bash site-code/bin/check-source.sh
```

Both final Hallmark runs exited successfully with **0 FAIL and 0 open REFLEX**. The event-context run recorded two applications of the gate 79 waiver, and the populated fixture recorded one. The source also records the gate 44c reason for varying event-title length beside a fixed-ratio photograph. Gate 79 is waived because the approved date/place/time cards occupy the first viewport below a compact hero; empty optional sections collapse rather than adding filler.

The remaining gate 77 warnings report one section because the checker counts the root wrapper. Manual inspection and DOM assertions confirmed five sections for empty optional-content cases and all seven for the populated fixture. No structural change is justified by this warning.

The interaction check passed **15 viewport/context combinations**, covering widths **320, 375, 991, 1280 and 1920** for the event, ticket and optional-content cases. It checked horizontal overflow, native keyboard links, visible focus outlines, anchor targets, unresolved tags, the primary desktop CTA fold and reduced-motion durations. Hallmark additionally rendered 1280, 1440, 1920 and 375 screenshots and checked contrast. Source JSON parsed successfully.

An initial broad static scan also saw pre-existing WordPress presets, legacy navigation CSS, decorative native Woo quantity SVGs and existing global colour literals. No unrelated CSS or plugins were rewritten. The new ticket cards use native product-page booking links instead of repeating the existing quantity control markup. Independent review raised document-root styling scope; the final `@scope` rule corrected that concern. Small subsequent quote-line-height corrections received fresh rendered checks.

## Limits, activation and rollback

This is author-side template verification, not production or merge approval. A checkout/payment/order/ticket-issuance transaction was not performed. External video playback is unverified. Existing staging photos and content are representative material, not approved final event content.

To use this template on public event routes, a separate explicit assignment must be chosen and verified against the existing 774 conditions. Do not add another equal-scoring post-type rule without resolving the competing selection. The current task leaves 1919 unassigned, as requested.

Rollback requires no change to existing events: leave 1919 draft and unassigned. The new ACF group is optional and its values are unused by 774. Do not delete content or advance the runtime ACF source as part of this handoff.

## Completion assessment

Task **VGN-51**, type **IMPLEMENTATION**, author self-assessment. The verified scope is the separate draft template body and its source artifacts.

Acceptance criteria **8/8 PASS**:

1. New draft 1919 exists with no placement conditions.
2. Existing Event Single 774 and its settings match the protected baseline.
3. Seven native, editable sections follow the approved order and recorded remote Frames provenance.
4. Event title, excerpt, image, date, venue and time resolve in event context.
5. Optional social proof, gallery and programme fields render with request-only values and hide when empty.
6. Pricing is scoped to linked products and uses native prices and product-page booking destinations, with an empty state.
7. Responsive, focus, contrast, reduced-motion, anchor and Hallmark checks pass with recorded structural waivers and warnings.
8. Portable source, editor instructions, rollback and verification evidence are prepared for an unmerged external-review PR.

Implementation Score **9.5/10**, requirements **4/4**, observed verification **2.5/3**, correctness/architecture/risk **2/2**, handoff **1/1**. The half-point verification deduction reflects fixture-based optional-field testing rather than an observed editor save-and-preview round trip with approved content. Confidence Score **90%**, based on persisted readbacks, three event contexts, the populated request-only fixture, 15 viewport checks and independent scoped review. The main uncertainty is final editorial material and the optional external video.

Verification Status **VERIFIED** for the draft template body and configuration only. The complete repository source check exited **0** on PHP **8.5.4**, including 148 PHP syntax checks and the existing exact-commit artifact build checks. No production, public-route activation, payment, ticket issuance or final external PR/CI review is certified. Merge readiness **NOT_VERIFIED_FOR_MERGE**. Next action is owner/editor review in Bricks, followed by independent exact-PR review; public assignment remains separate.
