# 1. Executive Summary

**Inspection date:** 2026-11-19 (UTC)  
**Repository:** `/home/jcschoeman96/projects/current/vgnova`  
**Runtime target:** `vgnova-local-10039`, LocalWP site ID `nAZNpFs99`, WordPress root `/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public`  
**Source baseline:** branch `main`, `454346d9e6a2c36864a4bbd944e52596f16e09c1`; `origin/main` matched at inspection. Remote is `git@github.com:Carpe-Diem-Media/vgnova.git`.

The target identity and local services were verified from LocalWP’s registry, live process/socket state, the WordPress root, and selected database settings. Observed versions are WordPress 7.1.5, LocalWP PHP-FPM 8.5.12, MySQL Community Server 8.0.35, and Nginx 1.26.1. The actual active theme is Bricks 2.4.5 with Bricks Child Theme 1.1. The FPM PHP version meets the repository’s PHP 8.5 minimum.

The strongest runtime finding is the current Platform Core deployment. PE-008 journal `2de8147a38934ad69d0808867dc38e6a` is finalized and bound to source commit `454346d9e6a2c36864a4bbd944e52596f16e09c1`. An independent read-only fingerprint of the installed plugin tree matched its 63-file journal manifest: tree SHA-256 `91775a8c85f3a756b58f2a650db2ad010eb605577512d8d218731a1ae0890276`, with no missing, extra, content, or mode mismatches. The previous 62-file runtime tree is retained at the deployment’s rollback path. This proves the present Platform Core files match the finalized deployment record; it does not prove all current page, commerce, course, or accessibility behavior.

PE-007 read-only status reported the filesystem lock and both database locks free. PE-008 read-only status reported no deployment in progress and no recovery options. This evaluator performed no runtime mutation, WordPress record write, form/order/enrolment, migration, rewrite flush, cron job, test transaction, commit, or deployment.

**Assessment:** Runtime foundation coverage is **PARTIAL**. Target identity, core versions, selected configuration, PHP-FPM module/configuration state, coordinator state, and Platform Core file parity are verified. Application-route behavior, host-level scheduled jobs, and cloudflared ingress remain unknown. Overall launch readiness is **not established**.

Evidence labels used below:

- **BOTH_CONFIRMED** — source contract and runtime state were both observed.
- **RUNTIME_CONFIRMED** — observed directly in the current local runtime.
- **SOURCE_CONFIRMED** — observed in the current repository.
- **HISTORICAL_ONLY** — issue/comment or older deployment record; not treated as a current live check.
- **INFERRED** — likely interpretation, explicitly not a direct observation.
- **UNKNOWN** — not established within this read-only scope.
- **CONFLICT** — two observed sources disagree or a current source record is stale.

# 2. Inspection Scope and Evidence

## Scope and safety

This was a bounded, read-only evaluation of the registered LocalWP WordPress environment, local source/runtime identity, server and database configuration, cache and queue indicators, and PE-007/PE-008 coordination state. The coordination brief and repository policies were read, including the source/runtime ownership rule in `AGENTS.md:17-27,812-828`, the PE-007 mutation boundary at `AGENTS.md:1026-1034`, and the PHP 8.5 contract. The PE-007 v1.1.0/v1.2.0 and PE-008 deployment documents were compared with current local state and current Plane evidence.

The current WordPress root was resolved and checked for safe read access. Only selected configuration keys and aggregate database values were inspected. No passwords, tokens, salts, database exports, customer records, order details, or full serialized options were emitted. Expensive database reads were serialized with the coordination brief’s advisory `flock` at `/tmp/vgnova-evaluation-read.lock`. No lock file was created inside the WordPress runtime.

No WordPress content ability or Bricks frontend MCP call was made. Such calls may bootstrap plugins or cause incidental runtime activity; the needed local identity, process, static-file, and selected database observations were available through scoped local read-only checks. The sole HTTP request was for static `/readme.html`; it returned 200 and did not request a WordPress route. This does not verify a WordPress page render.

## Evidence collected

| Evidence | Observed result | Classification |
|---|---|---|
| Git source and remote | `main`, HEAD `454346d9e6a2c36864a4bbd944e52596f16e09c1`; `origin/main` matched; origin is the Carpe-Diem-Media repository | SOURCE_CONFIRMED |
| LocalWP registry | Site `Voelgoed - Novamira`, ID `nAZNpFs99`, Local domain `voelgoed-novamira.local`, Local service ports 10035–10039 | RUNTIME_CONFIRMED |
| Runtime process/socket | Local PHP-FPM and MySQL processes tie to `nAZNpFs99`; Nginx and MySQL listeners bind loopback; FPM and MySQL Unix sockets exist | RUNTIME_CONFIRMED |
| WordPress root and database identity | Root path exists/readable; selected `wp_options` values report `home=siteurl=http://localhost:10039`, database name `local` | BOTH_CONFIRMED |
| Static HTTP reachability | `curl --noproxy '*' -sS --max-time 5 -D - -o /dev/null http://127.0.0.1:10039/readme.html` returned `HTTP/1.1 200 OK`, `nginx/1.26.1` | RUNTIME_CONFIRMED; static-file reachability only |
| PHP and WordPress versions | Local FPM 8.5.12; WordPress core 7.1.5; shell/WP-CLI PHP is separately 8.5.4 | RUNTIME_CONFIRMED |
| PHP-FPM modules and OPcache | Local FPM binary info mode, with the exact generated site `php.ini`, reports configured modules and OPcache settings; active process maps Imagick and Xdebug | RUNTIME_CONFIRMED for configured FPM binary/ini; request behavior not exercised |
| Selected database metadata | Read-only PHP/mysqli query through the LocalWP socket, under the advisory flock; MySQL 8.0.35 and selected options/aggregates | RUNTIME_CONFIRMED |
| PE-007 status | `filesystem_lock_held=false`; database locks A and B `free` | RUNTIME_CONFIRMED |
| PE-008 status and tree | `in_progress=false`; `recovery_options=[]`; 63-file live tree matches journal manifest | RUNTIME_CONFIRMED |
| Plane coordination evidence | VGN-55, VGN-50, VGN-5 and VGN-18 issue/comment records; older deployment claims are labelled historical | HISTORICAL_ONLY for those recorded events |

The root remained on `main`; no tracked source edits were made. Initial untracked workspace material (`.agents/`, runtime-tools `__pycache__`, the coordination brief, and the parent baseline) was preserved. This evaluator created only this report.

# 3. Component Inventory

Inventory is for the observed local target. “Consumers” names the directly evident consumers; where the read-only scope did not establish consumers, that is stated rather than inferred.

| Component | ID/Slug/Path | State | Owner | Purpose | Consumers | Evidence |
|---|---|---|---|---|---|---|
| Canonical source repository | `vgnova`, branch `main`, SHA `454346d9e6a2c36864a4bbd944e52596f16e09c1` | Current; clean tracked tree; untracked files preserved | Carpe-Diem-Media / repository owners | Source-controlled architecture, Platform Core, deployer and coordination tools | CI, deployment artifact builder, local runtime deployment | Git `rev-parse`, `status`, and `remote`; SOURCE_CONFIRMED |
| LocalWP site | ID `nAZNpFs99`; Local domain `voelgoed-novamira.local`; path `~/Local Sites/voelgoed-novamira` | Running | LocalWP workstation | Hosts local WordPress staging | Nginx, PHP-FPM, MySQL, Mailpit | Local registry `~/.config/Local/sites.json`; process/socket inspection; RUNTIME_CONFIRMED |
| WordPress root | `/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public` | Exists, readable; directory mode 0755 | Local site filesystem | WordPress application root | Nginx document root, WP-CLI, PE-007/PE-008 | `realpath`/`stat`; Nginx root at generated config line 8 |
| Nginx | Local service `nginx-1.26.1`; loopback HTTP `127.0.0.1:10039`, `[::1]:10039` | Running; static readme returned 200 | LocalWP | HTTP server and PHP FastCGI routing | WordPress root, PHP-FPM | `sites.json`; `ss`; generated `conf/nginx/site.conf:6-8,65-100`; curl |
| PHP-FPM | Local service PHP 8.5.12; CGI port 10037; FPM socket under Local run directory | Running; supported PHP line | LocalWP | PHP runtime for HTTP requests | WordPress and active plugins | Registry nested service metadata; process executable/version; FPM socket |
| PHP CLI/WP-CLI | `/usr/bin/php` 8.5.4; WP-CLI 2.12.0 | Available; differs from Local FPM | Workstation | Command-line WordPress administration and selected diagnostics | WP-CLI commands | `wp --info`; RUNTIME_CONFIRMED |
| MySQL | Local MySQL 8.0.35; DB `local`; TCP port 10038; socket `/home/jcschoeman96/.config/Local/run/nAZNpFs99/mysql/mysqld.sock` | Running; exact socket reachable for scoped reads | LocalWP | WordPress persistent storage | WordPress, PE-007 status and guarded deployment health | Registry, process, socket and read-only SQL; RUNTIME_CONFIRMED |
| WordPress core | Version 7.1.5 | Installed | WordPress | CMS runtime | Bricks, ACF, WooCommerce and other plugins | `wp-includes/version.php` and `wp core version`; RUNTIME_CONFIRMED |
| Active theme | Template `bricks`; stylesheet `bricks-child`; Bricks 2.4.5; child theme 1.1 | Active | Bricks / site theme owner | Theme and child-theme presentation | WordPress rendering and Bricks templates | `wp_options`; theme headers; RUNTIME_CONFIRMED |
| ACF Pro | `advanced-custom-fields-pro/acf.php`, 6.8.1 | Active | WP Engine / site license owner | Field groups and custom fields | WordPress content, Bricks dynamic data, editor workflows | Active-plugin option and plugin header |
| Automatic.css | `automatic-css/automatic-css.php`, 4.0.3 | Active | Automatic.css / site license owner | CSS utilities and design tokens | Bricks and child-theme styles | Active-plugin option and plugin header |
| WooCommerce | `woocommerce/woocommerce.php`, 11.3.0 | Active | Automattic / commerce owner | Product/cart/checkout domain | Bricks, Subscriptions, payment gateways, membership integrations | Active-plugin option and plugin header |
| WooCommerce Subscriptions | `woocommerce-subscriptions/woocommerce-subscriptions.php`, 9.4.1 | Active | Automattic / commerce owner | Subscription billing lifecycle | WooCommerce and PMPro integrations | Active-plugin option and plugin header |
| Masteriyo | `learning-management-system/lms.php`, 3.5.1 | Active | Masteriyo / learning owner | Course/lesson/enrollment provider | WordPress, WooCommerce and Bricks | Active-plugin option and plugin header |
| Paid Memberships Pro | `paid-memberships-pro/paid-memberships-pro.php`, 3.9.1 | Active | PMPro / membership owner | Membership levels and access rules | WooCommerce, member perks, protected content | Active-plugin option and plugin header |
| Tickera | `tickera/tickera.php`, 3.6.0.9 | Active | Tickera / event owner | Event tickets and registration | WordPress and event pages | Active-plugin option and plugin header |
| FluentCRM / Fluent Forms | `fluent-crm/fluent-crm.php` 3.3.1; `fluentform/fluentform.php` 6.2.17 | Active | Fluent / marketing and forms owners | CRM and form workflows | Newsletter/contact flows; exact active form consumers not inspected | Active-plugin option and plugin headers |
| Voelgoed Platform Core source | `site-code/voelgoed-platform-core/` | Canonical repository source at current main | VG Nova | Platform domain integrations, including canonical `content_topic` | WordPress runtime artifact, Bricks/provider integrations | `AGENTS.md:812-828`; source tree; SOURCE_CONFIRMED |
| Voelgoed Platform Core runtime | `wp-content/plugins/voelgoed-platform-core/` | Active; 63 files; current tree matches deployment journal | VG Nova runtime deployment process | Deployed Platform Core runtime | WordPress bootstrap and registered hooks | PE-008 journal/fingerprint and active plugin option; BOTH_CONFIRMED |
| PE-007 mutation coordinator | `site-code/bin/vgnova-runtime-mutate.php` | Source present; current status free | VG Nova | Serializes repository-owned administrative writes | PE-008 deployer and other approved one-shot mutations | Current `--status`; source docs |
| PE-008 deployer | `site-code/bin/deploy-platform-core.py`; `site-code/runtime-tools/platform_core_deployment.py` | Source present; no in-progress journal; latest journal finalized | VG Nova | Source-bound, journaled, atomic Platform Core deployment/recovery | Platform Core plugin directory | Current `status`, journal and independent tree comparison |
| WP-Cron | WordPress cron option; `DISABLE_WP_CRON` not defined in `wp-config.php` | 45 hook groups/events recorded; 7 were due at read time; no event executed | WordPress and individual plugins | Request-triggered scheduled tasks unless separately configured otherwise | Active plugins; exact event owners not disclosed in aggregate read | Static config + serialized-option aggregate; RUNTIME_CONFIRMED |
| Action Scheduler | `wp_actionscheduler_actions` | 5,001 complete; 7 failed; 21 pending at read time | Action Scheduler and scheduling plugins | Persistent asynchronous jobs | WooCommerce/plugin jobs; individual job details not read | Read-only grouped status counts; RUNTIME_CONFIRMED |
| Page/object cache layer | `WP_CACHE` undefined; `advanced-cache.php`, `object-cache.php`, `db.php`, and `wp-content/cache` absent; no identified cache plugin in active set | No WordPress drop-in cache observed | WordPress/site operations | Potential page/object caching | No current cache consumer established | Static files/config and active plugin list; runtime/proxy cache remains UNKNOWN |
| Mailpit | Local service version 1.24.1; web 10035, SMTP 10036 | Registered; delivery not tested | LocalWP | Local mail capture/testing | WordPress mail plugins | Local registry only; no email sent |

### Active plugin set

The database’s `active_plugins` value listed 31 plugins. Key version headers were read for the platform components above. The full active slug set observed was:

`fluent-smtp/fluent-smtp.php`; `tickera/tickera.php`; `admin-site-enhancements/admin-site-enhancements.php`; `advanced-custom-fields-pro/acf.php`; `automatic-css/automatic-css.php`; `easy-code-manager/easy-code-manager.php`; `fluent-crm/fluent-crm.php`; `fluent-security/fluent-security.php`; `fluentform/fluentform.php`; `frames-plugin/frames-plugin.php`; `happyfiles-pro/happyfiles-pro.php`; `jsm-show-post-meta/jsm-show-post-meta.php`; `learning-management-system/lms.php`; `mcp-adapter/mcp-adapter.php`; `novamira-pro/novamira-pro.php`; `novamira/novamira.php`; `paid-memberships-pro/paid-memberships-pro.php`; `paystack-gateway-paid-memberships-pro/paystack-gateway-paid-memberships-pro.php`; `pmpro-payfast/pmpro-payfast.php`; `pmpro-roles/pmpro-roles.php`; `pmpro-woocommerce/pmpro-woocommerce.php`; `voelgoed-member-perks/voelgoed-member-perks.php`; `voelgoed-platform-core/voelgoed-platform-core.php`; `woo-order-test/wc-ot.php`; `woocommerce-subscriptions/woocommerce-subscriptions.php`; `woocommerce/woocommerce.php`; `role-based-prices/roled-based-prices.php`; `bridge-for-woocommerce/bridge-for-woocommerce.php`; `csv-export/index.php`; `seating-charts/index.php`; `terms-and-conditions/index.php`.

# 4. Current Architecture and Configuration

## Local identity, server and database

- LocalWP registry record: `name=Voelgoed - Novamira`; `id=nAZNpFs99`; domain `voelgoed-novamira.local`; path `~/Local Sites/voelgoed-novamira`; Nginx service 1.26.1; MySQL 8.0.35 on port 10038; PHP service 8.5.12 on CGI port 10037; Mailpit 1.24.1.
- The same registry record’s top-level `phpVersion` is `8.5.3`, while its nested PHP service says `8.5.12`. The active PHP-FPM executable/process is 8.5.12. This is a registry-field mismatch, not evidence that the site is running below PHP 8.5.
- The running FPM process uses the LocalWP site’s configuration under `~/.config/Local/run/nAZNpFs99/`; its socket is under that site ID. MySQL’s listening Unix socket is `/home/jcschoeman96/.config/Local/run/nAZNpFs99/mysql/mysqld.sock`.
- Current generated Nginx configuration listens on `127.0.0.1:10039` and `[::1]:10039`, uses the WordPress root, and passes PHP through the Local `php` FastCGI upstream. The observed HTTP listener is loopback-bound. This alone does not establish effective tunnel ingress or whether traffic was externally reachable.
- `wp-config.php` defines `WP_ENVIRONMENT_TYPE='local'`, uses `DB_HOST=localhost`, and table prefix `wp_`. Database name reported through the configured connection is `local`.
- WordPress `home` and `siteurl` are both `http://localhost:10039`. This differs from LocalWP’s registered alias `voelgoed-novamira.local`; alias behavior was not tested.
- The WordPress root is readable and its actual MySQL socket is reachable. A default `wp db query` attempt failed before SQL because the MySQL client selected `/tmp/mysql.sock` (error 2002). No config or socket was changed. Subsequent selected reads used the registered socket explicitly.

## WordPress routes and rendering settings

- `template=bricks`; `stylesheet=bricks-child`.
- `permalink_structure=/content/%postname%/`.
- `show_on_front=posts`, `page_on_front=0`, `page_for_posts=0`.
- The persisted `rewrite_rules` option contains 1,044 rules. This was counted as serialized metadata only; no full ruleset was emitted and no exact route match was tested. No rewrite flush was run.
- The LocalWP domain and the database’s `home`/`siteurl` are distinct host values. The configured target for this evaluation remains `http://localhost:10039` as directed by the coordination brief.

## PHP configuration and extensions

- Local PHP-FPM 8.5.12 is the actual web SAPI target; `/usr/bin/php`/WP-CLI PHP 8.5.4 is a separate CLI runtime.
- Generated runtime `php.ini` records `memory_limit=256M`, `max_execution_time=1200`, `max_input_time=600`, `post_max_size=1000M`, `upload_max_filesize=300M`, and `display_errors=On` (runtime config path `~/.config/Local/run/nAZNpFs99/conf/php/php.ini`, lines 5–17 and 66).
- The Local PHP-FPM binary was queried in info mode with the exact site ini and its bundled `bin/linux/shared-libs` library path. It reported these PHP modules: `bcmath`, `bz2`, `calendar`, `cgi-fcgi`, `Core`, `ctype`, `curl`, `date`, `dom`, `exif`, `FFI`, `fileinfo`, `filter`, `ftp`, `gd`, `gettext`, `hash`, `iconv`, `imagick`, `intl`, `json`, `lexbor`, `libxml`, `mbstring`, `mysqli`, `mysqlnd`, `openssl`, `pcntl`, `pcre`, `PDO`, `pdo_mysql`, `pdo_sqlite`, `Phar`, `posix`, `random`, `readline`, `Reflection`, `session`, `shmop`, `SimpleXML`, `soap`, `sockets`, `sodium`, `SPL`, `sqlite3`, `standard`, `sysvmsg`, `sysvsem`, `sysvshm`, `tidy`, `tokenizer`, `uri`, `xdebug`, `xml`, `xmlreader`, `xmlwriter`, `xsl`, `Zend OPcache`, `zip`, and `zlib`. Zend modules were `Xdebug` and `Zend OPcache`. The active FPM process map independently showed `imagick.so` and `xdebug.so`. The Local package library path was needed because the system loader otherwise could not find its bundled `libtidy` dependency.
- FPM info mode loaded `/home/jcschoeman96/.config/Local/run/nAZNpFs99/conf/php/php.ini` and reported `opcache.enable=On`, `opcache.enable_cli=On`, `opcache.validate_timestamps=On`, and `opcache.revalidate_freq=2`. No pool-level override for these settings was found in the inspected PHP conf files. This verifies the configured FPM binary/ini state; no current WordPress request was used to test cache invalidation behavior. A previous deployment record’s “2-second” observation is historical and consistent with this configuration, but is not a fresh request-level check.
- `display_errors=On` is configured in the local FPM ini. This is a staging configuration observation, not a finding about production.

## Cache, cron and background processing

- `WP_CACHE`, `DISABLE_WP_CRON`, and `ALTERNATE_WP_CRON` are not defined in the inspected `wp-config.php`; `WP_CACHE` therefore has no explicit enabling definition in that file.
- `wp-content/advanced-cache.php`, `object-cache.php`, `db.php`, and `cache/` are absent. The active plugin list contains no identified page/object cache plugin. No assertion is made about Nginx, host, browser, or tunnel caching.
- Aggregate WP-Cron metadata showed 45 hook groups/events, 7 due at the query time, and next due timestamp `1795113321`. Event names/payloads were not emitted; no event ran.
- Aggregate Action Scheduler states were 5,001 `complete`, 7 `failed`, and 21 `pending`. The failed and pending job identities, age, retries, and owning plugins were not inspected; these counts alone do not establish a defect.
- No external system cron, queue daemon, scheduled host job, or plugin worker process inventory was performed. No queued job was run.

# 5. How It Works

## Request and data path

A local HTTP request to `localhost:10039` is accepted by LocalWP’s Nginx listener, which serves from the registered WordPress root and routes PHP requests to the site’s PHP-FPM upstream. WordPress core reads from the Local MySQL database. Bricks and its child theme provide the active presentation layer; ACF Pro and the specialist plugins supply fields and business domains. Platform Core source is maintained in the repository module and deployed into the active plugin path under the PE-007/PE-008 process.

The observed public URL settings are stored in WordPress options, while LocalWP’s service identity and ports live in its local registry. These are related but separate authorities. LocalWP’s alias and WordPress’s `home`/`siteurl` are not the same string.

## PE-007 mutation-session lifecycle

Source contract: `docs/architecture/VGNOVA_RUNTIME_MUTATION_COORDINATION_v1.1.0.md:11-27,54-67,83-114`; socket transport amendment: `v1.2.0:9-62`; repository summary: `AGENTS.md:1026-1034`.

1. **Free** — host `flock` and both registered database locks are free.
2. **Acquire** — an authorized one-shot mutation acquires the host filesystem lock, then DB lock A and DB lock B on separate keeper connections.
3. **Verify** — a separate connection checks database/site identity and both lock owners before a child starts. A supplied socket changes transport only, not target identity or proof.
4. **Child active** — the mutation child receives the session proof; locks remain held while the child and descendants could write.
5. **Release** — after child/descendant completion, keepers release the database locks and the host lock; terminal metadata reports `released`.
6. **Failure/recovery** — failure to acquire or prove a lock fails closed before mutation. Independent keepers retain exclusion if one coordinator component dies. No manual lock clearing is permitted.

**Owner:** PE-007 coordinator and the explicitly authorized operator. **Guard:** exact registered target, absolute WordPress root, current lock ownership, and separately authorized mutation scope. **Side effects:** acquiring a session writes coordination metadata and holds locks; this evaluation called only `--status`, which the source states does not acquire, release, or change another session’s locks (v1.1.0:114). **Observed terminal state:** all three current locks free; metadata refers to a completed VGN-18 SSL option session dated Nov. 17, not an active session.

The contract and `AGENTS.md` refer to “MariaDB” advisory locks. The actual Local server is MySQL 8.0.35; see Section 8 for the compatibility/documentation discrepancy.

## PE-008 deployment lifecycle

Source phases are defined in `site-code/runtime-tools/platform_core_deployment.py:45-97`:

`prepared → stage_verified → swap_intent → swapped → health_verified → parity_verified → finalize_intent → finalized`

Failure paths use `rollback_intent → rolled_back`, or `rollback_failed` followed by guarded recovery. `finalized` and `rolled_back` are terminal for the deployment record except that finalized deployments can enter an explicitly guarded rollback path. Recovery is limited to topology and tree identities proven by the journal.

**Owner:** PE-008 deployer, running as the child of a PE-007 session. **Guard:** registered target/root, owner-approved artifact/source, manifest and before-image validation, active PE-007 proof, no unresolved journal, verified health and tree parity. **Side effects:** writes a deployment journal; stages and atomically swaps the plugin tree; retains the prior tree for rollback; finalizes the history entry. **Recovery:** only the deployer’s journal-guarded rollback/finalize options, never manual file copying or lock clearing. `status_deployment()` is documented as read-only and returns no recovery options if there is no in-progress journal (`platform_core_deployment.py:1650-1660`).

Current record `2de8147a38934ad69d0808867dc38e6a` is **finalized**. PE-008 status is `in_progress=false`, `recovery_options=[]`.

## WordPress cron and Action Scheduler lifecycle

WordPress scheduled hooks become due and are ordinarily invoked by WordPress cron on requests unless an alternate launcher is configured. Here `DISABLE_WP_CRON` is not defined in `wp-config.php`; this does not prove that requests have triggered the due events, nor whether another launcher exists.

Action Scheduler jobs are observed in `pending`, `complete`, and `failed` states. Execution can have plugin-specific side effects. Aggregate state counts were read without invoking a queue runner; no owner, state transition, retry, or effect was inferred for individual jobs.

# 6. Source Versus Runtime

| Domain | Source-controlled authority | Current runtime evidence | Reconciliation |
|---|---|---|---|
| Repository baseline | `main` at `454346d9e6a2c36864a4bbd944e52596f16e09c1`; remote `origin/main` matches | LocalWP runtime is separate from Git checkout | SOURCE_CONFIRMED; do not equate checkout with runtime |
| Platform Core code | `site-code/voelgoed-platform-core/` is canonical; `integration.patch` describes bootstrap changes (`AGENTS.md:812-828`) | Active plugin tree is 63 files and matches finalized journal manifest/tree hash | BOTH_CONFIRMED |
| PE-008 artifact | VGN-5 Plane comment records artifact 14256283667, archive and manifest evidence for source `454346d...` | Journal `2de8147...` ties source commit to archive SHA `fd6c8b1d0a0ad8118cd7fea10f4345fe73b94a0e758a577f3f4b8f7d31246871` and manifest SHA `f6cd5d58510e233730c77b144125e734c080e4cb20fecf4cc306e39d939e182a` | Historical deployment evidence plus current live/journal parity; artifact was not downloaded/reverified anew by this evaluator |
| Active plugin tree | Main source and artifact/journal manifest | Live tree has 63 files; canonical tree hash `91775a8c85f3a756b58f2a650db2ad010eb605577512d8d218731a1ae0890276`; no missing/extra/content/mode mismatch | BOTH_CONFIRMED |
| Previous Platform Core tree | PE-008 rollback record | Retained rollback path `.vgnova-platform-core-rollback-2de8147a38934ad69d0808867dc38e6a`; previous 62-file tree hash `a6b77b25d5f6bb45456deeaecdbc4387f47ca3656c19360863bc8fa1e364a5ef` | RUNTIME_CONFIRMED; retained as recovery evidence, not a redundant directory |
| Site configuration | Repository describes URL/content architecture; it does not own LocalWP database options | `home/siteurl=http://localhost:10039`, permalink `/content/%postname%/`, Bricks + child theme | Runtime database values are derived state; route rendering not tested |
| PHP target | Repository requires PHP 8.5 (`AGENTS.md:21-27`) | Local FPM 8.5.12; Local registry top-level says 8.5.3; CLI is 8.5.4 | Meets minimum; registry field and CLI differ from active FPM |
| Deployment ledger | `docs/architecture/IMPLEMENTATION_LEDGER.md:7` says no successful LocalWP deployment is evidenced | PE-008 journal, current exact tree parity, and later Plane deployment comments establish the Nov. 17 deployment | CONFLICT: ledger row is stale and should be reconciled by its owner |
| PE-008 status successor | `PLATFORM_CORE_RUNTIME_DEPLOYMENT_v1.0.1.md:44-52` records source merged, but says operational approval absent and local deployment unauthorized | Later VGN-50/VGN-5 Plane comments record separate one-use approvals and completed deployments; current journal is finalized | HISTORICAL_ONLY status text is superseded by later issue evidence and current runtime journal; the document itself was not edited |
| PR #95 | Coordination baseline records OPEN, unmerged, head `771b0c92b1c799c468ce9f74aac63ba2770cab7f` | Current `main` and runtime deployment are at `454346d...` | HISTORICAL_ONLY proposal; not treated as merged source or deployed runtime |
| Business approval | PE-008 runtime deployment is independently authorized per one-use Plane comments | One-use deployment completed; no broad launch/business approval is shown | One deployment approval is not launch approval |

## Required deployment states

| State | Finding | Evidence |
|---|---|---|
| `SOURCE_MERGED` | **YES** — current Platform Core source is on `main` at `454346d...` | Current Git and PE-008 journal |
| `RUNTIME_APPLIED` | **YES** — active 63-file tree currently matches finalized manifest/tree record | Independent live fingerprint versus journal |
| `BEHAVIOR_VERIFIED` | **PARTIAL** — deployment-time PHP bootstrap and HTTP health passed; current static file responds 200; no current WordPress page or end-to-end route was exercised here | Journal and current static HTTP request; VGN-5 handoff remains partial on accessibility |
| `BUSINESS_APPROVED` | **PARTIAL** — VGN-50/VGN-5 record one-use owner authorization for exact deployments; no general production/business launch approval is shown | Plane comments, not an ongoing authorization |
| `LAUNCH_READY` | **NO / NOT ESTABLISHED** | Current Plane work remains open; payment, course/access, site-wide accessibility and broader journeys were not validated by this runtime inspection |

# 7. Completeness and Functionality

## Verified for this task

- LocalWP identity resolves to the intended registered site and WordPress root.
- Root, FPM socket, MySQL socket, loopback HTTP listener, and selected database values align with target `vgnova-local-10039`.
- WordPress 7.1.5, Local FPM PHP 8.5.12, MySQL 8.0.35, Nginx 1.26.1, active Bricks theme, and current Platform Core tree state were observed.
- Current PE-007 lock status is free; current PE-008 status is finalized/no recovery required.
- Platform Core live-file parity matches the journal manifest and current deployment source commit.
- Cache drop-in files and explicit WP cache/cron constants were checked.
- WP-Cron and Action Scheduler counts were read without running work.

## Not verified

- Any live WordPress page, Bricks-rendered template, product/course/event route, canonical/alias host redirect, login flow, or browser interaction.
- Whether OPcache’s configured timestamp/revalidation settings produce the expected behavior during a current WordPress request or deployment.
- Current external tunnel ingress or actual external traffic.
- Whether pending/failed Action Scheduler tasks are stale, retryable, important, or benign.
- Whether the due WP-Cron events run successfully or an external cron launcher exists.
- Payment-method availability now, order submission, enrollment, membership access, email delivery, full Golden Journey, SEO, analytics, or accessibility compliance.
- A fresh re-download/reconstruction of the exact published artifact during this evaluator’s read-only pass. Current live tree was compared against the journal’s manifest; deployment-time artifact verification is recorded in the deployment history and Plane.

## Coordination context (historical issue evidence)

- **VGN-50** (`370ad89d-5912-4987-b91c-9f3d4c3c4d32`) comment `f97b9dfc-92ad-4640-88aa-046feeea7d88` records the first guarded PE-008 deployment on Nov. 11. This was later superseded by the Nov. 17 deployment.
- **VGN-5** (`951a2f73-5005-498d-bb97-50286dcbbf6d`) comment `1ba5e396-c13e-4448-b2a6-03e3914be58e` records the Nov. 17 VGN-5B PE-008 deployment, source `454346d...`, artifact 14256283667, exact manifest, current 63-file tree parity, PHP/HTTP health, and released PE-007 locks. Comment `0e3888de-72be-48b8-930a-c8c51d7ee780` reports the handoff reviewer had operator/Plane evidence but not the live local filesystem; screen-reader work remained open.
- **VGN-18** (`fac5419e-4347-418a-a1c2-15054dde2e91`) comment `ac9fc988-d58d-4c40-97a9-be117bfa8e6f` is HISTORICAL_ONLY: a Nov. 17 checkout page rendered with one cart item but reported no payment methods; no order was submitted.
- **VGN-55** (`1782fd6f-206f-432b-bf85-0ad97c0a43bb`) is the started foundation audit tracker. Its comment `30185303-4b3b-44d6-a5c1-36a12ddf77d3` describes the earlier Phase 0 as partial and calls for current local runtime evidence. This report supplies the assigned runtime/environment evidence; it does not update the tracker.
- VGN-5 comment `d6b942d4-e0dd-4d9a-a73a-f57730a2ea87` records screen-reader verification deferred to later accessibility refinement. This is not a pass or a launch waiver.

# 8. Problems, Risks and Contradictions

Severity is assigned only to evidenced issues. UNKNOWN items are not defects.

| Severity | Finding | Evidence and concrete impact | Status |
|---|---|---|---|
| P2 | PE-008 implementation ledger is stale | `IMPLEMENTATION_LEDGER.md:7` still says there is no successful LocalWP deployment. Current finalized journal and Nov. 17 Plane record establish one. Readers using the ledger may make an incorrect deployment-status decision. | CONFLICT, confirmed documentation drift; reconcile ledger under its own ownership |
| P3 | LocalWP PHP metadata disagrees with active FPM | Registry `phpVersion=8.5.3`, nested PHP service and running FPM executable `8.5.12`. It meets PHP 8.5 minimum, but tooling that trusts the top-level field can misreport the actual runtime. | CONFLICT, confirmed metadata mismatch |
| P3 | CLI DB default socket does not match this LocalWP MySQL socket | `wp db query` default attempt failed with MySQL 2002 at `/tmp/mysql.sock`; actual registered socket is under `run/nAZNpFs99/mysql/mysqld.sock`. Default `wp db query` calls fail before SQL on this target unless the socket is specified explicitly. | RUNTIME_CONFIRMED; no runtime config changed |
| P3 | PE-007 documentation says MariaDB while the target is MySQL | `AGENTS.md:1030` and PE-007 v1.1.0:18-27 describe MariaDB advisory locks; the active server reports MySQL Community 8.0.35. Current lock-owner status queries returned expected `free` states and deployment evidence records completed sessions, but this bounded audit did not independently prove full lock-acquisition behavior against this exact MySQL build. | CONFLICT in naming/declared engine; no lock malfunction observed |
| P3 | Source deployment status documents are stale relative to later runtime operations | `PLATFORM_CORE_RUNTIME_DEPLOYMENT_v1.0.1.md:44-52` says approval not established/deployment unauthorized, and ledger line 7 says no deployment. These were accurate at their historical point but are superseded by later one-use authorizations and finalized journals. | HISTORICAL_ONLY source text; reconcile for current operators |
| UNKNOWN | LocalWP alias versus WordPress canonical host | Registry domain is `voelgoed-novamira.local`, while `home` and `siteurl` are `http://localhost:10039`. Alias redirects, cookies, and canonical-link behavior were not tested. | CONFLICT of configured values; not proven harmful |
| UNKNOWN | Request-level OPcache behavior | FPM info mode with the LocalWP site ini reports Zend OPcache enabled, timestamp validation on, and `revalidate_freq=2`; this task did not exercise cache invalidation during a current WordPress request or deployment. | Configuration RUNTIME_CONFIRMED; behavior not tested |
| UNKNOWN | Effective network exposure | Nginx currently listens on loopback; a `cloudflared` process was observed, but its ingress was not inspected. No conclusion about external reachability or traffic is supported. | UNKNOWN; do not infer exposure or absence |
| UNKNOWN | WP-Cron/Action Scheduler backlog health | 7 due WP-Cron hooks, 21 pending and 7 failed Action Scheduler actions. Names, ages, retries, owners and expected transitions were intentionally not read. | Counts only; not classified as defects |

The PE-007 current status command reached the current MySQL service and reported both lock states as `free`; the coordinator’s current state is not blocked. No evidence was found of an active PE-007 mutation session or unresolved PE-008 recovery.

# 9. Redundancy Candidates

No runtime component is proposed for removal.

- The retained PE-008 rollback tree is required recovery evidence and is not classified as redundant. No cleanup is authorized or recommended from this inspection.
- No page/object cache drop-in or cache directory was observed, so there is no duplicate cache implementation to decommission.
- No redundant CPT, plugin, table, taxonomy, or runtime subsystem was evaluated or identified in this bounded environment task.
- The LocalWP `voelgoed-novamira.local` alias and `localhost:10039` WordPress host values need route/redirect evidence before any proposal to consolidate them.
- The failed/pending queue counts do not support deleting or retrying jobs.

Any future redundancy proposal needs a named consumer inventory, native replacement, retention/rollback plan, prerequisites, and explicit owner gate before cleanup.

# 10. Recommendations

1. **Reconcile PE-008 status documentation.** Update the implementation ledger and successor status record through their normal review process with the Nov. 17 finalized deployment and current parity evidence. Preserve historical snapshots rather than rewriting their original claims.
2. **Clarify the LocalWP PHP version source.** Align or document the top-level `phpVersion=8.5.3` registry field against the running 8.5.12 PHP service so future checks use the active SAPI. No runtime change was made.
3. **Document/test the MySQL contract explicitly.** Confirm the supported DB engine/version for PE-007 lock semantics and adjust “MariaDB” wording if the supported local server is MySQL. Current read-only status is healthy; this is a contract clarity item, not a claim of broken locking.
4. **Use the registered socket and coordination-safe reads.** Keep selected database reads under `/tmp/vgnova-evaluation-read.lock`; use the exact LocalWP socket in the PHP database client settings. Do not change `DB_HOST`, copy credentials, or default to another project’s database.
5. **Close the OPcache evidence gap with a bounded, approved diagnostic.** Confirm whether OPcache is loaded in this FPM process and the effective timestamp/revalidation settings before relying on deployment assumptions; do not treat the old 2-second log as current proof.
6. **Leave unverified runtime behavior to its owning acceptance work.** Route/alias behavior, payment availability, course access, screen-reader announcements, analytics and launch readiness belong to their existing Plane items. This report makes no implementation recommendation or change to those flows.
7. **Do not infer ingress from the process list.** If external reachability is in scope, separately inspect current cloudflared ingress and traffic evidence with the relevant owner; the present loopback observation cannot settle that question.
8. **Do not remove rollback data or run queued work based on counts.** Use the PE-008 guarded recovery path only if a future deployment requires it; queue decisions require per-job owner/context.

## Safe read-only access recipe

The following current status commands were inspected and run read-only:

```sh
env LD_LIBRARY_PATH='/home/jcschoeman96/.config/Local/lightning-services/php-8.5.12+0/bin/linux/shared-libs' \
  '/home/jcschoeman96/.config/Local/lightning-services/php-8.5.12+0/bin/linux/sbin/php-fpm' \
  -c '/home/jcschoeman96/.config/Local/run/nAZNpFs99/conf/php/php.ini' -m

env LD_LIBRARY_PATH='/home/jcschoeman96/.config/Local/lightning-services/php-8.5.12+0/bin/linux/shared-libs' \
  '/home/jcschoeman96/.config/Local/lightning-services/php-8.5.12+0/bin/linux/sbin/php-fpm' \
  -c '/home/jcschoeman96/.config/Local/run/nAZNpFs99/conf/php/php.ini' -i \
  | grep -E '^(PHP Version|Server API|Loaded Configuration File|opcache[.](enable|enable_cli|validate_timestamps|revalidate_freq) =>|display_errors =>|mysqli.default_socket =>)'
```

The output was limited to module names and selected ini values. The following current PE status commands were also inspected and run read-only:

```sh
php site-code/bin/vgnova-runtime-mutate.php \
  --target=vgnova-local-10039 \
  --wp-root='/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public' \
  --db-socket='/home/jcschoeman96/.config/Local/run/nAZNpFs99/mysql/mysqld.sock' \
  --status

PYTHONDONTWRITEBYTECODE=1 python3 site-code/bin/deploy-platform-core.py \
  status \
  --target vgnova-local-10039 \
  --wp-root '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public'
```

These returned current PE-007 locks free and PE-008 `in_progress=false`, `recovery_options=[]`. The database socket path is transport information, not a credential or proof of a mutation session. For additional database inspection, use a purpose-built **SELECT-only** client that reads WordPress connection configuration into process memory, sets the explicit registered socket, emits only approved aggregate/selected fields, and wraps the batch with:

```sh
flock -n /tmp/vgnova-evaluation-read.lock <read-only-command>
```

Do not print or place the DB password in argv, environment dumps, logs, or chat. The ordinary `wp db query` default transport previously tried `/tmp/mysql.sock`; do not mistake that path for this site’s actual socket.

# 11. Outstanding Verification

| Item | State | What would establish it |
|---|---|---|
| Complete FPM PHP extension list | VERIFIED for configured FPM binary and site ini | `php-fpm -c <site-php.ini> -m` reported PHP and Zend modules; active process maps Imagick and Xdebug |
| Effective OPcache configuration | VERIFIED for configured FPM binary and site ini | FPM info mode reported enable On, timestamp validation On, revalidate frequency 2; request-level invalidation remains untested |
| Local alias behavior | UNKNOWN | Verify `voelgoed-novamira.local` and `localhost:10039` route/canonical redirects in a separately approved browser check |
| WordPress route behavior | PARTIAL | Current permalink/rewrite settings known; a scoped read-only route check is still required to prove live rewrite resolution |
| External ingress/reachability | UNKNOWN | Inspect current cloudflared ingress and relevant traffic records; process presence alone is insufficient |
| Host-level cron or queue workers | UNKNOWN | Read-only scheduler/service inventory; this evaluator only inspected WordPress cron and Action Scheduler aggregates |
| Pending/failed job meaning | UNKNOWN | Inspect the minimum job metadata and owning plugin for the 21 pending/7 failed actions before any retry or cleanup decision |
| Current WordPress application response | PARTIAL | Only static `/readme.html` received current HTTP 200. Deployment-time PHP/HTTP health is recorded in PE-008 journal, but no current page bootstrap was run |
| Full Platform Core artifact reproduction | HISTORICAL_ONLY plus current parity | Re-download and verify the exact source-bound published artifact only if the next authorized deployment requires it; current live tree already matches the recorded journal manifest |
| Commerce/course/launch acceptance | NOT IN THIS TASK | Existing VGN-18, VGN-5, VGN-43 and launch work must establish their own criteria; this report does not close them |
| Source-ledger reconciliation | OPEN | Repository/document owner updates status through normal review; no source document was changed here |

No evidence gap above blocked the requested report. They limit claims about full runtime behavior and launch readiness.

# 12. Final Domain Assessment

## Runtime domain conclusion

The intended LocalWP target is identified and reachable on loopback. The active web runtime is WordPress 7.1.5 on Local PHP-FPM 8.5.12, backed by MySQL 8.0.35 and Nginx 1.26.1. Core routes/configuration metadata, selected plugin state, and WP background-job aggregates were inspected without changing runtime state. Current PE-007 and PE-008 status checks are clear.

Platform Core is source-controlled under `site-code/voelgoed-platform-core/` and the active plugin directory is a deployment copy, as required by `AGENTS.md:812-828`. Its active 63-file tree matches the finalized PE-008 journal manifest and tree hash. Deployment-time PHP/HTTP health is recorded as successful; this audit did not re-run WordPress bootstrap or a real application route. Overall platform behavior remains only partially verified.

## Final assessment

- **Coverage:** PARTIAL — all twelve requested report sections are present; target identity, versions, selected runtime configuration, current locks/deployment status, and Platform Core parity were inspected. Request-level OPcache behavior, full rewrite behavior, network ingress and application acceptance remain outstanding.
- **Confidence:** MEDIUM — high for the observed local process, configuration, selected database values, status outputs, and current tree fingerprint; medium overall because live WordPress page behavior, OPcache, and ingress were intentionally not established. No numerical confidence is assigned, per the coordination brief.
- **Runtime verification:** PARTIAL — actual root/target, versions, DB configuration, locks, journal and artifact-tree parity verified; no current WordPress page or transactional flow verified.
- **Blockers:** None for this research report. Full launch readiness is not established; existing application and accessibility acceptance remain with their separate owners.
- **Plane references verified:** VGN-55 `1782fd6f-206f-432b-bf85-0ad97c0a43bb`; VGN-50 `370ad89d-5912-4987-b91c-9f3d4c3c4d32`; VGN-5 `951a2f73-5005-498d-bb97-50286dcbbf6d`; VGN-18 `fac5419e-4347-418a-a1c2-15054dde2e91`. No Plane issue or comment was created or updated.
- **Task type:** RESEARCH / DOCUMENTATION.
- **Acceptance criteria:** 12/12 required sections included. Runtime coverage is partial as listed above; no report-structure criterion failed.
- **Implementation Score:** N/A — no implementation performed.
- **Task Execution Score:** 9/10 — target identity, version/config inventory, PHP-FPM modules and OPcache settings, PE-007/PE-008 status and current file parity are evidenced; live application routes, external ingress and host-level scheduling remain unverified.
- **Verification Status:** PARTIALLY_VERIFIED — identifies the exact state verified above; it does not assert end-to-end WordPress, commerce, course, accessibility, or launch verification.
- **Evidence:** Git baseline and remote; LocalWP registry and live sockets/processes; scoped PHP/WordPress/database configuration; `/readme.html` 200; current PE-007 status; current PE-008 status and finalized journal; independent 63-file runtime/manifest fingerprint; Plane comments cited above.
- **Highest-risk assumptions / next action:** Do not infer that deployment-file parity proves application readiness or that loopback listeners settle cloudflared exposure. The project owner should reconcile PE-008 status documents and direct the separate outstanding runtime/accessibility/commerce checks through their existing gates.

