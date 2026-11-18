# Voelgoed homepage draft

Task VGN-52. The owner approved layout A and its written design on 2026-11-11.

The new page is **Voelgoed Tuis**, draft ID **1926**. Open the Bricks editor at <http://localhost:10039/?page_id=1926&bricks=run>. It is not assigned as the front page. Existing front-page options remain `show_on_front=posts`, `page_on_front=0`.

## Editing

The page contains a search-led hero, a native terms grid and four compact action links. Bricks elements remain editable. The topic loop resolves the labels and canonical links from the seven root `content_topic` terms in the approved order. Empty topics stay visible. The runtime label is `Werk + Geld`; the page deliberately uses that canonical label.

Search submits GET requests to the existing WordPress search route with `post_type=post`. It searches articles only. It does not claim unified search. The existing results and empty-results pages are retained. The known article query `Dink nuut` returns the existing QA article. The inherited `/content/` archive URL currently returns 404, so `Lees` uses the working core posts listing `/?post_type=post`. `Leer`, `Woon by` and `Winkel` retain `/kursusse/`, `/events/` and `/produkte/`.

The new ACF group **Home: Featured destination**, ID **1928**, applies only to page 1926. Its field `home_featured_destination` accepts one published page, product, course or event. Leave it empty to omit the optional section. No campaign is selected in the saved draft. When selected, the section uses the target's own title, excerpt, image and permalink. Missing images collapse the layout to one column.

## Native query details

The featured loop uses `post_type=any`, a dynamic `post__in` restricted to the selected ID, published status, and `has_password=false`. A child section inside that loop permits only `page`, `product`, `mto-course` or `tc_events` via native dynamic-data conditions. The child placement matters: conditions on the looping element itself evaluate before the destination context is active.

This configuration avoids an observed installed Bricks/WooCommerce issue. A mixed post-type query containing `product` reprocessed the unresolved ACF ID in WooCommerce's filter and produced `[0]`. No plugin, theme PHP or adapter was changed to work around it. An unsupported selected article ID was tested and hides the child section. A native generated SQL inspection confirmed the password predicate and published status. A read-only derived-row fixture then supplied a nonempty password for selected event 542 during one query; the native predicate excluded it and the featured section stayed hidden. No protected record or password was persisted. The exact read-only MCP payload is in `protected-destination-check.json`.

## Source and runtime ownership

`site-code/bricks/homepage/page.json` is the canonical exported native tree and saved document digest. `homepage.css` preserves the authored scoped styles and Hallmark annotations. Bricks strips comments and reorders media rules when saving `_cssCustom`; the JSON export records the actual saved form. Responsive grid rules use native breakpoint settings to avoid specificity conflicts with generated element IDs.

`frames-provenance.json` records the inspected remote Frames sources and adaptations. Remote global classes, theme styles and stock media were not imported. Existing ACSS tokens and the primary button class were reused. The featured button has scoped native contrast and height settings because the inherited primary button colors failed the rendered contrast check.

`site-code/acf-json/group_vg_homepage.json` defines the new field group. Native ACF APIs created its database copy with JSON autosave disabled for that request. The pinned runtime ACF source-generation symlink was not advanced or edited. A later approved source-generation deployment must include this definition before treating the entire runtime as source-synchronized.

All writes used Bricks MCP at the required localhost endpoint from a real PE-007 coordinated child request. Read-only rendering used the same endpoint. The long-lived connector session expired during verification; fresh MCP initialization to that same Bricks endpoint continued the checks.

## Verification

The commands and results are recorded in `site-code/bricks/homepage/verification.json`.

- `bash site-code/bin/check-source.sh` checks the repository source and native ACF JSON.
- `node site-code/bricks/homepage/verify-render.mjs /tmp/vgnova-home-final-render.html /tmp/vgnova-home-feature542.html /tmp/vgnova-home-feature763.html` verifies 15 saved-render viewport contexts at widths 320, 375, 991, 1280 and 1920. It checks one H1, seven unique topic links, native search labels and article scope, keyboard progression, visible focus, control heights, overflow, reduced motion and missing-image layout. It also submits the actual search form with a known article query and an unmatched query, checking the resulting URL and visible results or empty-state text.
- `node /home/jcschoeman96/.agents/skills/hallmark/scripts/sloplint.mjs /tmp/vgnova-home-final-render.html /tmp/vgnova-home-feature542.html /tmp/vgnova-home-feature763.html --render --genre editorial --json` checks the rendered body with the existing site's actual styles loaded.
- `git diff --check` checks the source diff.

For reproduction, use Bricks `render-elements` with `postId=1926`, retaining its returned HTML and CSS. Load the actual current site's styles. For optional-content checks only, a read-only request applied `acf/pre_load_reference` for the new field key and `acf/load_value/name=home_featured_destination` for one selected ID. The fixture IDs were 0, 542, 763, 99999999, 1904, 375 and 846. No selected value or fixture record was persisted. Run each fixture in a separate request to avoid ACF/query caches carrying state between cases.

The local render harness moves existing inline site CSS byte-for-byte into a separate loaded stylesheet. This keeps unrelated legacy CSS out of the new page's static lint target while preserving it during browser rendering. An initial whole-head lint identified inherited global CSS findings and the introduced search label contrast failure. The label was fixed. The final report is a scoped page-body sweep, not a claim that existing global CSS or the whole site passes Hallmark.

The final scoped sweep reports zero FAIL and zero open REFLEX findings. WARN 77 counts the outer Bricks wrapper as one section; the approved decision-page design deliberately has two main sections and one optional destination, rather than six catalogue sections. WARN F23 concerns missing meta description in the local draft render harness. Final SEO metadata is an owner editorial task before publication. The compact hero and inherited Voelgoed system take precedence over Hallmark's generic full-screen hero and new-theme defaults.

Protected snapshots compare all 32 pre-existing templates and the existing global classes, variables, components, theme styles and breakpoints. These checks do not certify checkout, all linked journeys or current header/footer behavior. Header/footer were preserved and are outside the isolated body-render checks. A full authenticated frontend/editor save roundtrip has not been run.

## Rollback and activation

Keep the recorded draft ID when retrying this task. Do not create another homepage draft or repurpose the article Featured Content Manager.

To roll back, use native WordPress actions inside a PE-007 session to trash only page 1926. Remove group 1928 only after verifying no other page uses its field and no new dependencies were added. Revert the corresponding source artifacts through normal review. Existing templates and front-page options need no restoration because this task did not change them. No direct SQL is needed.

Publication and assignment as the static homepage remain owner decisions. Before activation, verify authenticated frontend rendering with the current header/footer, save the page once in Bricks, choose final copy and a featured destination if wanted, review SEO metadata and repeat navigation/search checks. PR review and passing author checks do not authorize merge or publication.
