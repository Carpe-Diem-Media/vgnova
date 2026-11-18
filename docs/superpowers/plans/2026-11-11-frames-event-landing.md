# Separate Frames event landing template implementation plan

> Execute the approved layout in this session. Use bounded independent review for the resulting template and source artifacts.

**Goal:** Create a new reusable, unassigned Bricks Single template for Tickera events, without changing Event Single 774 or its routing.

**Authority:** Owner approved the layout and instructed "Dont replace my current single template, just create a new one. Continue". Tracked as VGN-51. Event/ticket migration in VGN-35 and the broad VGN-36 programme remain outside scope.

**Architecture:** Remote Frames supplies native editable section structures. Tickera remains authoritative for event dates, time and venue. WooCommerce supplies linked ticket products, prices and purchase controls. A small ACF editorial group supplies optional event-specific testimonial relationships, gallery, video and programme content. No custom subsystem or persistent PHP is required.

## NATIVE CAPABILITY REVIEW

- WORDPRESS_CORE: title, excerpt, featured image and body supply event content.
- BRICKS: native elements, conditions and Posts queries supply reusable event rendering; remote Frames supplies section structures.
- ACF_PRO: native fields supply optional gallery, testimonial selection, video and programme. Existing testimonial records retain their quote/author authority.
- WOOCOMMERCE: native product widgets supply linked ticket prices and product-page booking links through the existing `_event_name` relationship.
- TICKERA: existing `tc_events`, `event_date_time`, `event_end_date_time` and `event_location` retain their authority.
- PMPro / WOOCOMMERCE_SUBSCRIPTIONS / MASTERIYO / CLOUDFLARE: no changes required.
- CUSTOM_DEVELOPMENT=NOT_AUTHORISED for a new subsystem. Only source artifacts and scoped Bricks styling are needed.

## Artifacts

- `site-code/bricks/event-landing/template.json`: complete new native Bricks template, with no placement conditions.
- `site-code/bricks/event-landing/event-landing.css`: scoped ACSS styling, applied only to the new template.
- `site-code/acf-json/group_vg_event_landing.json`: portable ACF editorial definition.
- `docs/development/event-landing-template.md`: editing instructions, provenance, rollback and observed verification.

## Execution

- [x] Capture exact Template 774 and design/ACF baselines; inspect remote Frames content and native schema.
- [x] Prepare the native tree in this order: hero, date/place/time cards, testimonial/social proof, gallery/media, CTA, info with participant cards, pricing.
- [x] Preview dynamic tags and render the saved template against three existing staging events.
- [x] Create the source-owned ACF definition. Preserve the immutable ACF runtime worktree and symlink. Use only native ACF API persistence for the new group, with JSON writes disabled for this request, then verify all fields. Do not advance the runtime source generation.
- [x] Execute synchronous Bricks MCP requests as the actual PE-007 child, with both advisory locks and host lock held for each request. No external MCP mutation under a dummy wait process.
- [x] Create a new draft content template with preview context `tc_events` and no placement conditions. Never update 774 or existing event records.
- [x] Render against existing event contexts, verify optional-content absence and native ticket product scoping, and use marked temporary in-memory render data where richer existing content is absent. Do not fabricate endorsements.
- [x] Run installed Hallmark lint with rendering on the actual rendered candidate; inspect mobile/tablet/desktop, keyboard focus, contrast, reduced motion and empty states. Record all FAIL/REFLEX findings and waivers.
- [x] Compare complete 774 baseline after writes and confirm the new template stays draft/unassigned.
- [x] Review the final diff, commit source artifacts, create an unmerged PR and hand off the template URL with precise verification limits.

## Safety and rollback

No existing template, shared class, global variable, event, price, cart, order or ticket is intentionally changed. New ACF fields have no required values or default business claims. The template stays draft/unassigned. Rollback is leaving that new template unassigned; destructive deletion is unnecessary. The pinned ACF source generation remains unchanged and clean. Any source activation requires the separate approved source-generation workflow.
