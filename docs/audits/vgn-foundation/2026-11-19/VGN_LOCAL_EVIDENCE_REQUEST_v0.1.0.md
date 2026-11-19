# T3Code local agent — VG Nova foundation evidence request

**Version:** 0.1.0  
**Date:** 2026-11-19  
**Scope:** Current on-host LocalWP audit of **`vgnova-local-10039` ONLY**.  
**Companion tracking:** [VGN-55](https://app.plane.so/symphony-development/browse/VGN-55).  
**Status:** READ-ONLY EVIDENCE REQUEST; NOT authority for runtime writes.

## Mission and mandatory boundary

You are the **local evidence-gathering agent**, not an implementation/deletion agent. Use your local shell, WP-CLI, registered Bricks MCP and/or Novamira MCP only for **read-only** operations against the existing LocalWP site `http://localhost:10039`. No additional WordPress instance, database clone, Docker/test sandbox, write fixtures, test orders or new servers. Do not switch to production as a substitute.

Read current `AGENTS.md`, the phase-index documents in this folder and applicable locked contracts first. Inspect the exact local checkout/ref and target identity. Do not assume the historical socket, target, source symlink or deployed artifact has remained unchanged.

**NEVER** run `wp core update`, `wp plugin update`, `wp option update`, `wp rewrite flush`, `wp acf sync`, `wp db export` (private raw DB), `wp cron event run`, migrations, destructive imports, test checkout, payment gateway activation, ticket issuance, enrolment creation, Bricks saves or snippet saves. Do not modify WordPress content/settings or run PE-007/PE-008 mutation sessions. Avoid any command that can produce email, payment, job execution or state changes. A read command that requires WordPress bootstrap can have incidental plugin effects: check safety and log the read method; if unsure, stop and mark UNKNOWN rather than expanding authority.

## Operating sequence

1. Identify and **verify** registered LocalWP target and exact source checkout. Determine available read-only tools. Record date/time and full SHA before describing live state.
2. Collect Phase 0 evidence below. Prefer local WordPress CLI for database-backed inspection and Bricks MCP for actual template/condition/binding inspection. Use Novamira MCP for non-Bricks WordPress read APIs.
3. Collect Phases 1–8 inventories incrementally without changing runtime. Do not turn a candidate for removal into a deletion recommendation.
4. Deliver sanitized evidence files on the **audit documentation branch/worktree only**, never in the active WP root or an unrelated repo. Preserve the output set and provide hashes/paths and links. If audit branch clashes with active development, use a separate documentation worktree, **not** another WP staging environment.
5. Summarize verified/unknown/conflicting states per phase; report missing privileges and consumer-map gaps precisely. Do not claim that tests or browser journeys passed without observed results.

## Phase 0 — exact baseline and minimal read-only commands

First verify the target's on-machine identity in the LocalWP GUI/registry and registered file `site-code/runtime-tools/runtime-mutation-targets.json`. Historical references, **subject to reconfirmation**:

```bash
# Expected historical locations. Confirm their existence and identity before use.
REPO_ROOT="/home/jcschoeman96/projects/current/vgnova"
WP_ROOT="/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public"
LOCAL_DB_SOCKET="/home/jcschoeman96/.config/Local/run/nAZNpFs99/mysql/mysqld.sock"

# Git source identity only — no checkout/switch/reset/pull.
git -C "$REPO_ROOT" rev-parse HEAD
git -C "$REPO_ROOT" branch --show-current
git -C "$REPO_ROOT" status --porcelain=v1
git -C "$REPO_ROOT" remote -v

# Confirm binaries and target paths first; no WP bootstrap from these commands.
command -v php8.5
command -v wp
test -d "$WP_ROOT" && echo "WP_ROOT_EXISTS"
test -S "$LOCAL_DB_SOCKET" && echo "REGISTERED_DB_SOCKET_EXISTS"
```

The 17 November operator evidence used `/usr/bin/php8.5`, `/usr/local/bin/wp` and explicit `mysqli.default_socket` / `pdo_mysql.default_socket`. Confirm those are still correct; do not fall back to the wrong MySQL socket and mistake its failure for damaged WordPress. If local guard/target checks pass and this invocation remains read-only and safe, gather **only non-sensitive** configuration through the already verified PHP/WP-CLI combination:

```bash
# Explicit LocalWP PHP+socket for observational WordPress CLI commands only.
PHP_BIN="/usr/bin/php8.5"
WP_CLI="/usr/local/bin/wp"

# Basic core and URL identity.
"$PHP_BIN" -d "mysqli.default_socket=$LOCAL_DB_SOCKET" \
  -d "pdo_mysql.default_socket=$LOCAL_DB_SOCKET" \
  "$WP_CLI" --path="$WP_ROOT" core version

"$PHP_BIN" -d "mysqli.default_socket=$LOCAL_DB_SOCKET" \
  -d "pdo_mysql.default_socket=$LOCAL_DB_SOCKET" \
  "$WP_CLI" --path="$WP_ROOT" option get home

"$PHP_BIN" -d "mysqli.default_socket=$LOCAL_DB_SOCKET" \
  -d "pdo_mysql.default_socket=$LOCAL_DB_SOCKET" \
  "$WP_CLI" --path="$WP_ROOT" option get siteurl

# Named plugin inventory includes inactive and active plugins.
"$PHP_BIN" -d "mysqli.default_socket=$LOCAL_DB_SOCKET" \
  -d "pdo_mysql.default_socket=$LOCAL_DB_SOCKET" \
  "$WP_CLI" --path="$WP_ROOT" plugin list --fields=name,status,version --format=json

# Themes: installed, active, parent/child metadata.
"$PHP_BIN" -d "mysqli.default_socket=$LOCAL_DB_SOCKET" \
  -d "pdo_mysql.default_socket=$LOCAL_DB_SOCKET" \
  "$WP_CLI" --path="$WP_ROOT" theme list --fields=name,status,version --format=json
```

Record WordPress version, **actual PHP binary version**, database engine/version, local site ID, selected database/socket (never password), URL, theme/child theme, installed/active/inactive/MU plugin inventory and versions. Do not dump `wp-config.php`, .env, salts, WP options as an unfiltered list, SQL databases or user/customer/order payloads.

Also capture read-only PE-007 coordinator `--status` and read-only PE-008 status/health/journal listing **only if the existing tool documents safe observational verbs**. Record the current installed Platform Core exact file/tree/hash state against [VGN-50](https://app.plane.so/symphony-development/browse/VGN-50)'s historical 62-file canonical tree hash `a6b77b25d5f6bb45456deeaecdbc4387f47ca3656c19360863bc8fa1e364a5ef` and known deployment `459dea0e508b4658aabdd79ab40755f7`. Different source `main` and deployed artifact revisions are not automatically defects; report divergence and its consequence.

ACF: verify current database/local JSON/PHP-owned schema authority; target symlink destination and exact source revision; whether fields are staged for sync; **do not sync**. Bricks: enumerate the read-only MCP methods actually reachable on `localhost:10039`; record template IDs/conditions and actual render evidence if accessible. If local MCP is unauthorized, mark it as such and use an approved read-only WP-CLI or shell fallback; never treat a 401 as successful inspection.

## Phase 1 — WordPress platform

Deliver per-plugin **name, slug, exact version, active/inactive/MU state, actual purpose, current provider ownership, important settings and dependencies, source filename(s), runtime-loaded path/hash, known consumers and suspected overlap**.

Record core options limited to route/reading/registration/cron/cache/security/notifications; WP Cron vs server cron vs Action Scheduler ownership; registered REST routes/webhooks (names and callbacks only); Redis/object cache/OPcache/CDN usage where verifiable; email transport/provider plus notification rules **without recipients or secrets**. Inventory FluentSnippets entries, execution context and loaded source references, including inactive candidates. Identify runtime-only custom PHP, JS and CSS without assuming it can be removed.

## Phase 2 — CPT, taxonomies, ACF, relationships

Enumerate all registered post types (native, ACF-managed, plugin-managed), exact label/slug, supports, rewrite, visibility, registering code/plugin, templates, record counts by post status and relationship links. Enumerate all attached taxonomies, hierarchical terms with parent IDs, taxonomy-term assignments and consumers, especially `content_topic`, `content_category`, `category`, `post_tag`, event/product/course/person dimensions. Do not enumerate private user profiles.

For each ACF group and field capture exact post ID/key/name/type, location rules, return formats, source (DB/Local JSON/PHP exception), conditional/required/repeater/relationship/bidirectional settings, aggregated non-sensitive population counts, and readers/writers. **Never dump field values** (they may contain private tokens). Native ACF relationship vs Event Participation/Product Contribution first-class domain lifecycle must be explicit.

## Phase 3 — custom architecture

Inventory every active plugin/MU plugin/FluentSnippet, shortcode, `add_action`, `add_filter`, REST/AJAX route, JS/CSS entrypoint and custom template helper. For each component list exact source path, runtime deployed hash, bootstrap trigger, registration, readers, writers, state transitions/guards/side effects, error/recovery and terminal states, and native plugin alternative. Locate orphan/duplicate candidates but do not change or remove them.

## Phase 4 — commerce and operational domains

Using native read-only APIs, document Woo product types, pricing/tax/checkout settings, gateway activation (no credentials), Woo Subscriptions billing model, PMPro levels/access, Woo-PMPro rules, Member Perks, Masteriyo courses/sections/lessons/enrolment mapping and Tickera events/tickets/attendee authority. Use aggregate counts, product/event/course IDs, field names and redacted diagnostics only, never customer data.

Verify **configuration**, **provider behavior established by source**, and **browser-observed behavior** as separate classes. Do not submit orders, trigger webhooks or send emails. VGN-18's 17 November checkout rendering does not prove order fulfillment; VGN-53's product permalink 404 is distinct from the cart/checkout route.

For each meaningful lifecycle produce a states/transitions/guards/side-effects/failures/terminal-status and single authoritative provider matrix. Include Course→Woo purchase→Masteriyo enrolment, subscription→PMPro entitlement→Member Perks, and event→Tickera attendee→FastCheck handoff only where integrations are actually found.

## Phase 5 — editorial content and media

By post type and status, capture total/published/draft/private/trash counts; legitimate content vs staging/demo/placeholder with evidenced ownership; missing required fields, relationships, featured images and referenced media; broken internal links, SEO metadata coverage, categories and actual assignment counts. For imported records indicate evidence of migration origin. No media deletions, editorial modifications or test fixture creation.

## Phase 6 — Bricks and frontend

Inventory template IDs, types, publication states, **assignment conditions**, page/template/component IDs, dynamic binding expressions, Bricks Query Loops, ACF/Platform Core consumers, navigation, redirects, assets, ACSS global tokens and frontend forms. Distinguish EXISTS / ASSIGNED / RENDERS / VERIFIED_FOR_INTENDED_USER. Observe representative pages read-only; no form submissions, Bricks saves, editor preview mutations or unrelated visual-QA project. Draft template IDs 1919 and homepage page 1926 from VGN-51/52 require independent current runtime confirmation.

## Phase 7 — integrity, security and risk

Compare current source/runtime authorities; detect conflicting price/entitlement/permission calculations, mismatched relations, orphaned jobs, Cron/Action Scheduler/webhooks, checkout/payments/notifications, cache personalization, logged errors, backup and security gaps. Report risk only with exact evidence, impact and owner. Avoid expanding into speculative VPS hardening.

## Phase 8 — decommission candidates

Start from the existing `docs/architecture/LEGACY_DECOMMISSION_REGISTER.md`. For each suspected redundant CPT/taxonomy/field/plugin/snippet/template/page/redirect/cron/task/asset, collect exact source/runtime/DB/template/external/scheduled consumers and dependencies, proven replacement, data loss risk, rollback and separate owner approval. Use classifications KEEP / IMPROVE / MIGRATE / REPLACE / ARCHIVE_CANDIDATE / REMOVE_CANDIDATE / DEFER / UNKNOWN. Do not recommend actual removal while consumer inventory remains incomplete.

## Phase 9 handoff input

Provide a concise index of redacted collected evidence: exact authority, date/time, SHA/version, coverage status, known conflicts, current Golden Journey blockers and next action. The ChatGPT review agent can reconcile this with current GitHub and Plane and propose an independent SemVer successor. Do not claim a production launch or business acceptance from source merge, local staging or historical tasks.

## Output format and hygiene

Store files under a dedicated `docs/audits/vgn-foundation/2026-11-19/local-evidence/` **documentation** worktree/branch only (not under WordPress). Suggested initial artifacts: `00-target-identity.md`, `00-source-worktree.md`, `00-wp-platform.json`, `00-plugins.json`, `00-themes.json`, `00-deployment-acf-parity.md`, `00-tool-access.md`, `00-runtime-contradictions.md`. Subsequent phase files should use numbered prefixes and versioned summary reports. Avoid committing large raw exports or secrets.

For every item include:

```text
observed_at: YYYY-MM-DDTHH:MM:SS+02:00
target: vgnova-local-10039
method: read-only Bricks MCP | Novamira MCP | WP-CLI | shell | browser
repository_sha: full SHA, if relevant
runtime_identity: verified instance ID / site home, if relevant
item: exact slug / ID / path
observation: concrete statement
evidence_location: sanitized doc / command summary / screenshot
classification: BOTH_CONFIRMED | RUNTIME_CONFIRMED | SOURCE_CONFIRMED | HISTORICAL_ONLY | INFERRED | UNKNOWN | CONFLICT
limitations: exact unresolved gap
plane_issue: existing issue or VGN-55
```

**Never include** database passwords, authentication cookies, WordPress salts, API keys, SMTP secrets, Turnstile/Cloudflare keys, payment credentials, personally identifying contact details, sensitive order/customer/learner records, raw logs with personal data or private URL tokens. Aggregate and redact. Do not publish raw backup exports. If in doubt, stop and record the evidence gap.

## Checkpoint response

At phase completion, report: Phase; Status; Verified findings; Current-state summary; Historical contradictions; Risks/gaps; Redundancy candidates; Documentation produced; Plane updates; Outstanding evidence; Next phase/action. Continue source/documentation-only work while preserving active Golden Journey priorities. **Audit alone never authorizes a mutation.**
