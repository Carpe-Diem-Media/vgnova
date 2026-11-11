# Frames homepage implementation plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans for this tightly coupled runtime build. Use bounded independent review for native capability and final verification.

**Goal:** Create the approved layout A as a separate, unassigned Bricks homepage draft.

**Architecture:** Native Bricks elements, canonical term queries and default WordPress search. A page-scoped ACF Post Object selects an optional published public destination; a native post query renders canonical content. Preserve the existing brand and global resources.

**Tech Stack:** WordPress, Bricks, Frames, Automatic.css, ACF Pro, PE-007 mutation coordinator.

## Native capability review

WORDPRESS_CORE=Native page drafts, search, canonical permalinks and WP_Term_Query satisfy discovery. Inspected runtime front-page options are posts/0. Search coverage will be verified from actual requests.

BRICKS=Installed native search has labelText/showLabel/buttonText controls. Native terms loop supports include/orderby/parent/show_empty. Native post query resolves dynamic post__in and enforces publish status. These satisfy the composition without a custom query engine.

ACF_PRO=Existing featured-content manager targets article contexts and must remain unchanged. Create one page-scoped Post Object field, selecting page/product/mto-course/tc_events records, returning an ID. Native query and conditions must reject empty, unpublished, password-protected and invalid IDs.

WOOCOMMERCE/MASTERIYO/TICKERA/PMPro=Keep existing destinations and domain logic. WOOCOMMERCE_SUBSCRIPTIONS/CLOUDFLARE=No changes. OTHER_RELEVANT_PLUGIN=Reuse ACSS tokens and Frames trees. CUSTOM_DEVELOPMENT=NOT_AUTHORISED.

## Files and tasks

### 1. Inspect and protect runtime

- [x] Read schemas, remote Frames trees and design context through Bricks MCP. Record selected IDs, adaptations and hashes in `site-code/bricks/homepage/frames-provenance.json`.
- [x] Snapshot template metadata/elements, global classes/components/styles and front-page options to a local verification file. Record hashes in `site-code/bricks/homepage/verification.json` without credentials or unrelated option values.
- [x] Test search and all eleven navigation destinations using HTTP requests. Read templates and search hooks. Preserve current results behavior; report inherited search limitations.

### 2. Build native draft

- [x] Create source ACF definition `site-code/acf-json/group_vg_homepage.json` with one nullable published Post Object field, `home_featured_destination`. Scope it to the new page only after creation.
- [x] Author native Bricks tree `site-code/bricks/homepage/page.json`: Frames Hero Charlie content structure adapted to search, Frames Feature Romeo cards adapted to a static terms grid, Content Section Alpha adapted to optional feature. No remote stock media, global class imports or theme style replacement.
- [x] Use native controls and existing ACSS tokens first. Any necessary page-specific styles stay scoped and exported with the tree; do not add a code element or custom PHP/JS subsystem.
- [x] Create the page via `bricks/create-post` inside an active PE-007 session, status draft. Reuse its recorded ID on retries. Create the new ACF group through native APIs inside that session with JSON autosave disabled to preserve the pinned runtime source generation.
- [x] Save and read back the complete page. Save the canonical readback in `page.json`.

### 3. Verify exact saved candidate

- [x] Render the saved page through Bricks. Assert one H1, seven distinct canonical topic links, four valid action links and accessible search.
- [x] Exercise request-only feature selections: empty, invalid, draft, password protected, published with image and published without image. Do not persist fixtures or choose a campaign for the owner.
- [x] Run `node /home/jcschoeman96/.agents/skills/hallmark/scripts/sloplint.mjs <rendered-html> --render --genre editorial --json`. Fix introduced FAILs, resolve or justify REFLEX findings, and record results.
- [x] Verify keyboard focus, search submission, reduced motion, no horizontal overflow and responsive layout at 320, 375, 991, 1280 and 1920 pixels. Inspect screenshots and compare protected runtime hashes.
- [x] Run `bash site-code/bin/check-source.sh` and `git diff --check`. Save observed results and limitations in `verification.json`.

### 4. Review and handoff

- [x] Write editor instructions, created IDs, source ownership, exact verification commands and rollback procedure in `docs/development/homepage.md`.
- [x] Obtain independent spec and quality review of the actual diff and runtime evidence. Fix introduced findings and repeat affected checks.
- [x] Commit the final source artifacts and evidence. Open a PR for external review and link it to this thread. Keep page draft and front-page options unchanged. Report remaining CI/review gaps without recommending merge.

## Plan self-review

All eight approved criteria map to tasks 2 through 4. Native search and feature visibility require observed runtime evidence; no speculative custom adapter is authorized. No publication, existing template replacement, search-provider replacement or URL rewrite is included.

## Execution record

Created draft page1926 and ACF group1928. Saved digest and observed checks are in `site-code/bricks/homepage/verification.json`. Native query uses selected ID plus a child type condition to avoid the installed WooCommerce mixed-query issue. Password exclusion was exercised with a read-only derived-row fixture; no protected record was persisted. Independent review found no concrete implementation defect. Source and rollback documentation are committed for PR review. Full authenticated frontend/editor roundtrip and owner SEO review remain before activation.
