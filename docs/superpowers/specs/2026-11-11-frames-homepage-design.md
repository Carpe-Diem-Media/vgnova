# Voelgoed homepage design

Date: 2026-11-11
Status: Layout A approved in chat; written specification awaiting owner review.

## Outcome and authority

Create a new editable Bricks homepage draft using available remote Frames structures and the existing Voelgoed design system. The owner selected layout A after comparing two discovery-led sketches.

Follow section 38 of the locked `2026-10-25-vs1-golden-journey-product-tech-spec_v0.2.0.md`. Home helps visitors choose what they need help with. Topics and search take priority over individual content formats.

The runtime inspection returned `show_on_front=posts` and `page_on_front=0`. Create a separate draft without changing these options, existing templates, global navigation or shared component definitions. Assigning the new page as the homepage is a later owner decision.

## Composition

1. Retain the existing site header and footer. Reuse existing fonts, ACSS tokens and compatible global classes.
2. A clean hero with one H1, proposed Afrikaans copy `Waarmee kan ons jou help?`, supporting text and a labelled native search field. Audit the current search provider, supported content types and results route before implementation. Do not promise unified search or add a search subsystem.
3. A structured grid containing all seven canonical root `content_topic` terms: Nuwe Jy, Geloof, Self, Werk & geld, Ouerskap, Huwelik and Mensstories. Resolve labels and URLs from the canonical terms. Reflow on mobile without a carousel. Use available Frames structures, with Romeo-style cards as the preferred starting point where compatible.
4. Compact secondary navigation labelled Lees, Leer, Woon by and Winkel. Inspect and use working runtime destinations without changing existing slugs or rewrites.
5. One optional featured destination below the topic grid. Display its canonical title, image and permalink through native dynamic data. Use an existing suitable field if available; otherwise propose a page-scoped ACF Post Object field restricted to supported destination types. Only published, publicly viewable destinations may render. An empty or invalid selection hides the entire section. Do not choose a campaign on the owner's behalf or duplicate its content into separate fields.

The sketches establish hierarchy, not replacement brand tokens. Use native editable Bricks elements rather than embedding the sketch HTML. Do not add separate event, course, product, people or media catalogues, invented testimonials, newsletter integrations or automatic carousels.

## Native capability review

WORDPRESS_CORE=Page drafts, canonical term links, native search and post permalinks are the intended foundation. Search coverage and valid destinations still require runtime inspection.

BRICKS=Native search, query loops, dynamic data, element conditions and existing components are the first choice. Confirm current schemas and available Frames structures before authoring.

ACF_PRO=Inspect existing page and options fields. A native page-scoped Post Object field is the fallback for the optional featured destination, with source-controlled Local JSON before runtime creation. No new CPT or custom admin interface.

WOOCOMMERCE/MASTERIYO/TICKERA/PMPro=Remain authoritative for their existing destinations and visibility. This page links to their journeys and does not alter checkout, enrolment, tickets or membership rules.

WOOCOMMERCE_SUBSCRIPTIONS/CLOUDFLARE=No configuration changes required by this design.

OTHER_RELEVANT_PLUGIN=Reuse ACSS styling and the configured search provider where present. Frames supplies editable layout structures.

CUSTOM_DEVELOPMENT=NOT_AUTHORISED. No custom query engine, index, provider or rendering subsystem is proposed. Any discovered native limitation must be reported before expanding scope.

## States and safety

The new page remains draft and unassigned. Repeated execution must find the recorded draft ID and update it rather than create duplicates. Search uses its existing results and empty-state behavior. All seven topics remain visible even if a topic has no content. Missing featured images must not produce broken images. Invalid featured targets hide the optional section.

Inspect current shared resources before changes. All runtime mutations must execute through the repository-owned mutation coordinator and Bricks MCP endpoint. Save source exports, provenance, rollback instructions and the exact created runtime IDs. Rollback consists of reverting only the new page and any newly introduced page-specific field definitions after checking dependencies. No direct SQL writes.

## Acceptance criteria

1. A new Bricks-native draft exists; front-page options, existing templates and shared component definitions are preserved.
2. The hero has a single H1 and an accessible, working search form using the inspected provider and route.
3. All seven canonical root topics appear once with correct term URLs, without a carousel.
4. The four compact action links use verified existing destinations.
5. The optional featured destination uses canonical dynamic content and handles empty, invalid and missing-image states.
6. The page follows existing Voelgoed tokens and uses inspected Frames structures with recorded provenance.
7. Keyboard focus, contrast, mobile layout, overflow and reduced-motion behavior pass applicable checks. Run installed Hallmark checks, fix introduced FAILs and record REFLEX waivers.
8. Source exports, native field definitions if needed, verification evidence and rollback details are committed for PR review. Runtime readback matches the saved candidate. No publication or front-page reassignment occurs.

## Verification and delivery

Inspect search behavior and actual topic routes before finalizing the implementation plan. Verify rendered draft content at mobile, tablet and desktop widths. Exercise search submission, keyboard navigation, topic links and featured-target states. Compare protected resource snapshots after writes. Run relevant repository source checks and Hallmark on the exact saved candidate. Report unavailable checks without claiming completion.

Create a dedicated Plane execution issue after checking for existing work. Open a PR for external independent review once implementation is ready. Neither a score nor a passing author check authorizes merge or publication.

## Specification self-review

Checked for placeholders, conflicting scope and ambiguous publication behavior. Runtime discovery tasks are explicit implementation prerequisites, not claims of completed verification. The selected composition remains within the locked homepage guardrails. No custom subsystem or unrelated design work is included.
