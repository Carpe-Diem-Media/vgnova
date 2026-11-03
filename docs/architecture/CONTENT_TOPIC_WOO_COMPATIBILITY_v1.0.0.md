# Content Topic and WooCommerce compatibility v1.0.0

Date: 2026-11-03. Scope: the accepted VS1 shared-taxonomy compatibility adapter.

## Authority and baseline

- Exact base: `d4a8e218b1084294a5eebb10ebe6d497da6a141b`.
- Exact-main run `41625390716` passed Dashboard checks, Canonical source checks, VG Nova Platform Gate and artifact publication.
- Open PRs #59 and #70 were inspected. Neither owns this compatibility change.
- Implementation and final fixture verification revision: `fc364fd06f40060a1a9436abf97ef02b9f02280a`.
- The canonical IA v0.1 keeps WordPress `post` as editorial Article authority and `content_topic` as shared semantic authority. Platform Core retains all eight registered object types and all 55 terms.
- This evidence supplements the VS1 v1.1.0 gates. It changes the observed Topic/Woo compatibility finding. It does not replace their history, approve representative editorial content, or declare discovery complete.

## Native capability review

`ARCHITECTURE_VERDICT=SMALL_COMPATIBILITY_ADAPTER_JUSTIFIED`

- `WORDPRESS_CORE`: taxonomy routing, resolved `WP_Term`, matched rewrite query, `pre_get_posts`, native `WP_Query`, feeds and pagination provide the required request/query ownership.
- `WOOCOMMERCE`: store-only Coming Soon and its documented `woocommerce_coming_soon_exclude` filter provide scoped public discovery without changing store settings.
- `BRICKS`: native template conditions and explicit section queries remain presentation authority. No template, component, navigation or rendering hook was changed.
- `ACF_PRO`: no field or relationship change is required.
- `MASTERIYO`: no Course configuration or source changes. Its existing late shared-taxonomy catalogue settings explain why the final editorial query values must be reasserted after plugin archive settings.
- `CUSTOM_DEVELOPMENT`: only the approved focused compatibility adapter. No storage, query engine, cache, relationship repository or parallel taxonomy is introduced.

A Woo setting or page-ID exclusion cannot separate this shared taxonomy. In installed WooCommerce 11.2.1, `is_product_taxonomy()` checks every taxonomy attached to `product`. `WCAdminHelper::is_current_page_store_page()` returns true for such taxonomies after its page-ID list checks. Removing a page ID cannot negate that branch.

## Extension points and boundary

Installed source inspected:

- `woocommerce/includes/wc-conditional-functions.php`, `is_product_taxonomy()`.
- `woocommerce/includes/class-wc-query.php`, constructor, `pre_get_posts()`, `product_query()`, ordering callbacks.
- `woocommerce/src/Admin/WCAdminHelper.php`, store request classification.
- `woocommerce/src/Internal/ComingSoon/ComingSoonRequestHandler.php`, documented exclusion filter.
- `learning-management-system/includes/FrontendQuery.php`, late archive ordering and pagination settings.
- `bricks/includes/database.php`, content classification and native term conditions.

Official references checked on the evidence date:

- [WooCommerce Coming Soon integration](https://developer.woocommerce.com/docs/extensions/extension-onboarding/integrating-coming-soon-mode/).
- [WordPress pre_get_posts](https://developer.wordpress.org/reference/hooks/pre_get_posts/).

Production code uses the public Woo exclusion filter, WordPress hook registration APIs and the public `WC()->query->pre_get_posts` callback. No internal Woo class is called by the adapter. Woo's final `woocommerce_product_query` action occurs after catalogue ordering and SQL callbacks are installed, so changing only `post_type` there would leave contamination behind. The adapter instead forwards Woo's registered callback for every non-Topic query and skips it for the guarded Topic main query.

The wrapper retains the registered hook priority and delegates the original query object. Replacing the callback appends the wrapper within that priority. No concrete conflict was found in installed plugins; callback-order and callback-identity interactions remain an upgrade review consideration. Regression tests cover Woo's installed priority 10 and an alternative registered priority.

## Request lifecycle and guards

`REQUEST` resolves through WordPress normally. A frontend main query qualifies only when:

1. WordPress matched its native `content_topic` rewrite, recorded in `$wp->matched_query`.
2. The query is `is_tax('content_topic')` and is not a 404.
3. Its queried object is a real `WP_Term` with taxonomy `content_topic` and a positive term ID.

A qualifying request enters `TOPIC_PUBLIC`. In store-only mode only, the documented Coming Soon exclusion returns true. The native main query uses published Articles, `date DESC, ID DESC`, an empty `wc_query` and no catalogue metadata key. Its page size uses explicit native query input or WordPress's `posts_per_page` setting. Feed limits remain WordPress-owned. Values are set early and finally after late archive settings. Other existing query constraints are retained.

The main query is an editorial fallback/feed, not a cross-entity hub engine. Bricks can independently query Products, Courses, Events and People for later Topic sections. Secondary queries are forwarded unchanged.

All other requests remain on Woo/default WordPress behavior. An existing true Coming Soon exclusion is preserved. Site-wide Coming Soon is not excluded. No authorization depends on role, cookies, or an arbitrary query string.

An adversarial browser test discovered that `/produkte/?content_topic=nuwe-jy` can resolve a real Topic queried object on the Shop URL. The initial taxonomy-only predicate was insufficient. RED commit `d11c846` reproduced this. The final guard requires the matched native Topic rewrite; the repeated browser case is now protected.

Terminal result is the canonical Topic response or the unchanged Woo/WordPress response. There are no membership changes, Product visibility changes, settings changes, term changes, cookies or persisted compatibility state.

## TDD and source verification

RED evidence is preserved separately:

- `e3c01bf`: Topic main query still reached Woo, expected call count 4, actual 5.
- `af433ee`: late catalogue ordering replaced editorial ordering with `meta_value_num`.
- `4fa66da`: late catalogue settings replaced native page size 2 with 12.
- `d11c846`: a Topic query string on Shop incorrectly returned a Coming Soon exclusion.

Each was observed failing before its corresponding production change. The final standalone runner passes:

```sh
php site-code/voelgoed-platform-core/tests/run-content-topic-woo-tests.php
php site-code/voelgoed-platform-core/tests/run-content-topic-tests.php
bash voelgoed-platform-core/bin/check.sh
bash site-code/bin/check-source.sh
git diff --check
```

Observed results include 67 existing taxonomy tests, 127 Dashboard tests, the compatibility runner, runtime coordination/policy suites, deployment tests, tracked PHP lint, JSON/Python/shell checks, patch application, canonical assembly reproducibility and negative build cases. Independent reviews found no remaining blocking source issue, including a separate review of the final rewrite guard. Exact PR-head CI remains a separate review requirement; consult the PR checks for its final head.

## PE-007 and PE-008 proof

All administrative runtime operations used `site-code/bin/vgnova-runtime-mutate.php` for `vgnova-local-10039`, the registered WordPress root and the verified Local MariaDB socket. PE-008 performed whole-directory artifact deployment. No direct runtime plugin edit was used.

The first deployment attempt was rejected before runtime changes because an artifact requested with `HEAD` did not match the full-SHA manifest contract. It was rebuilt using the full SHA. No unresolved journal remained.

Final code artifact:

- Source: `fc364fd06f40060a1a9436abf97ef02b9f02280a`.
- Files: 57.
- Tree SHA-256: `71741ece3a2d31fc8de8c6dfa43201a2181014622f4219b2eef84417a9e91420`.
- Archive SHA-256: `362610a1300000c57e3aa3ef4fb5f2d094166ba99f5e5af8bebc9e64efdc40eb`.
- Manifest SHA-256: `e6ccf8fa304336c94e49fdff04f020c1d901bbba544086ca93bfde8940a66a85`.

Final guard deployment `a2c8029b78e34010afac6ece27d0981d` finalized. Controlled rollback `f8695af50bc64513be053198b51c456c` redeployed the exact prior-main artifact, restored tree `fe1e6ccb58580929740411dd8715fa725da89c565e4359fdcbec1a0d101141d4`, and restored the original Topic Coming Soon response. Redeployment `82b907df69e847e39c335b434c5835d4` finalized with the final code tree active.

PE-008 journals are retained under `app/.vgnova-platform-core-deployments/history/`; rollback directories are retained by PE-008. PHP health passed all nine checks, including session, runtime identity, lifecycle, no diagnostics and no unexpected output. HTTP home health returned 200. Runtime parity had no missing/extra files, digest/mode differences, directory metadata differences or symlinks. Deployment status had no in-progress journal.

## Controlled runtime matrix and cleanup

The tracked guarded harness is `site-code/runtime-tests/content-topic-woo-compatibility.php`. It uses native WordPress/Woo APIs and existing empty `geloof`/`bybel` terms, IDs 363/365, selected by current emptiness rather than production hard-coding. Slugs are reserved in a mode-0600 recovery report before creation.

Final run `becdb13575b9e140` passed all 14 check groups:

- Empty root and child remain valid, honest empty Topic archives.
- Product-only Topic has no Product in its Article main loop; explicit secondary Product query still returns the Product.
- Article-only and mixed Article/Product roots and children return the correct Articles.
- Price, popularity, rating, minimum/maximum price, rating filter and attribute URL inputs do not add Product lookup joins, Product visibility clauses or catalogue ordering to the main query.
- Pagination has correct page counts, without missing or repeated Articles.
- RSS retains feed context and contains only qualifying Articles.
- Unknown Topic remains 404.
- Disposable draft/private/trash Articles disappear. Republishing restores visibility. Anonymous and an existing administrator's in-process WordPress user contexts were checked.
- Native Product main query retains Woo query mode and its visible Product.

The harness assigns matched rewrite context for its in-process query cases; real rewrite routing is verified separately by browser HTTP requests. Administrator coverage is server-side user context, not a claimed authenticated browser login.

Final fixtures were Products 1849/1855 and Articles 1850–1854/1856/1857. Earlier successful run `a2a8b0f25c664262` used Products 1839/1845 and Articles 1840–1844/1846/1847. Every recorded fixture was deleted. Both reports have no cleanup failures and an identical restored Topic tree/count snapshot.

An independent read-only database check found zero remaining fixture posts or child revisions, postmeta rows, term relationships, Product lookup rows, and posts with the reserved `vg-ctw-` slug prefix. All 55 Topic terms and original object-type attachments remain. No real editorial record was altered.

## Anonymous HTTP, cache and Bricks results

An incognito shared browser fetched each of these cases twice with credentials omitted:

- `/onderwerp/nuwe-jy/` and its canonical `voeding-gewig/gesonde-eetgewoontes` child: 200, public, no Coming Soon body.
- Topic requests with `post_type=product`, price/rating/popularity ordering and Product filters: public Topic response.
- Topic RSS: 200 `application/rss+xml`; unknown Topic: 404.
- `/produkte/`, `/winkel/event-test-4/`, `/produk-kategorie/uncategorized/`: 200 with Woo Coming Soon body.
- Those same store surfaces with `?content_topic=nuwe-jy`: still protected.

`woocommerce_coming_soon=yes` and `woocommerce_store_pages_only=yes` were read before and after, never changed. There are no existing Product Tag terms or registered Product attribute taxonomies on this runtime, so their HTTP archives are not applicable. Unit forwarding tests include Product Tag and attribute taxonomy contexts. No extra terms were invented for coverage.

Public Topic responses had `Cache-Control: no-store, no-cache, must-revalidate`. Protected store responses retained Woo's `max-age=60`. Both repetitions preserved the distinction, with no `Age` or `X-Cache` header and `Vary: Accept-Encoding`. No shared cache contamination was observed locally. This is not certification of a production CDN. Deployment or future visibility-option changes must purge any page/CDN cache for affected URLs; Woo documents visibility-option purge hooks. No cache subsystem was added.

No PHP diagnostics appeared in HTTP responses or PE-008 health. Browser console entries included intentional unknown-Topic 404 fetches; no JavaScript exception was observed. Browser tabs opened for verification were closed.

The dedicated authenticated Bricks MCP initialized with HTTP 200 and read template inventory and native condition schemas. Its `bricks/execute-php` ability is disabled by PHP configuration; it was not enabled. A documented read-only WP-CLI fallback inspected classification and the native condition evaluator. The result was `content_type=wc_archive`, `post_type=post`, empty `wc_query`, canonical Nuwe Jy term, and score 8 for a term-specific archive condition. That native condition route remains available to the next presentation task. No Bricks mutation or custom template-selection hook was added.

`FRONTEND_COMPLETION=NOT_CLAIMED`. Existing Bricks/Woo fallback still says "No products were found matching your selection." Existing shared header placeholders remain. This backend compatibility PR authors no visual component, CSS or JavaScript and does not certify Hallmark, mobile layout or keyboard completion. Topic presentation and its Hallmark checks belong to their separately authorized slice.

## Delivery boundary

This fixes shared-taxonomy compatibility only. No Article migration, Amanda Person, 2027 Course, header, pillar UI, commerce, Journey Intent, ACF synchronization, membership grant or enrolment is included. Return the PR to Master Gate review without merging it. Representative content approval and subsequent discovery presentation remain separate gates.
