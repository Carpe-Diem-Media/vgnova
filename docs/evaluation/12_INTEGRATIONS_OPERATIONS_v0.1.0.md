# VG Nova integrations and operations evaluation v0.1.0

Source baseline: main, commit 454346d9e6a2c36864a4bbd944e52596f16e09c1. Local runtime target: vgnova-local-10039, WordPress at http://localhost:10039. Inspection date: 2026-11-19.

## 1. Executive Summary

This read-only audit covers integration boundaries and operational behavior visible in the current source, selected local configuration, and aggregate metadata. It does not establish live application behavior or launch readiness.

Coverage is PARTIAL. Runtime verification is PARTIAL. Confidence is MEDIUM for the bounded source/configuration/aggregate findings and LOW for end-to-end behavior. WordPress bootstrap was avoided because inspected plugin code can schedule work or write on admin initialization. No REST route, mail callback, queue job, order, enrollment, login, user record, log body, or delivery path was exercised.

Current findings:

- Platform Core is applied from the current source baseline. The finalized PE-008 journal and installed tree match. This verifies file deployment only.
- WP-Cron is enabled by the inspected configuration because DISABLE_WP_CRON is undefined. An earlier same-day read found 45 scheduled hook groups/events and seven due. No host-level cron or worker inventory is available.
- A serialized Action Scheduler aggregate at approximately 20:36 UTC showed 4,205 complete, seven failed, and 21 pending actions. All seven failed entries share WooCommerce’s fetch_patterns hook and have last-attempt ages over 30 days. Nineteen pending actions were scheduled in the future. Two were overdue by less than one day. Their arguments and logs were not read, so repeated hook counts do not establish duplicate work or user impact.
- The local count of complete actions fell from 5,001 in the earlier same-day foundation report to 4,205. A runtime-only maintenance snippet sets the generic retention filter to seven days. The timing is consistent with retention cleanup, but the cleaner’s loaded state and the cause of the count change were not verified.
- WooCommerce and Masteriyo connect order status changes to Course enrollment and a Masteriyo email. The installed Masteriyo email code sets its sent marker after calling send without checking the transport result. Course-email overlap and lost-retry risk are already tracked by blocked issue VGN-19. No duplicate or failed email was observed.
- FluentSMTP is active, but the selected fluentmail-settings option is absent. LocalWP registers Mailpit. No email was sent, and the effective wp_mail transport is UNKNOWN.
- The WooCommerce webhook table has no configured rows. This does not cover custom REST endpoints, plugin callbacks, or non-Woo webhooks.
- Member Perks REST claim routes have logged-in and nonce checks in the inspected installed files. The Member Perks source and runtime trees diverge, including claim and Course integration files. This is a source/runtime change-control risk, not evidence of an active authorization defect.
- Cache, analytics, consent, external media storage, database backups, host cron, and current Cloudflare ingress are not fully verified. Current ingress remains UNKNOWN. This report adds no deployment prerequisite.

## 2. Inspection Scope and Evidence

The coordination brief, parent baseline, parent review notes, root and nested repository instructions, relevant sections of the current architecture authorities, reports 01-08, and available parent-review references to reports 09-10 were consulted. The three assigned authority documents were read in full. Plane issue metadata and relevant descriptions/comments were checked read-only for VGN-19, VGN-10, VGN-31, VGN-42, VGN-54, and VGN-55. VGN-54's sensitive details were not read. No issue was created or updated.

Source baseline is main at 454346d9e6a2c36864a4bbd944e52596f16e09c1, matching origin/main as recorded in the parent baseline. The report uses current local evidence where available, source evidence for code paths, and HISTORICAL_ONLY labels for prior observations. It does not treat configuration or source hooks as proof that a callback ran.

Runtime evidence came from existing report 01, safe selected database projections, current installed source reads, and static file comparisons recorded in the parent review. The Action Scheduler projection used the advisory lock at /tmp/vgnova-evaluation-read.lock and the registered LocalWP MySQL socket. Its command form was:

    flock -w 20 -x /tmp/vgnova-evaluation-read.lock php /dev/stdin '<LocalWP WordPress root>'

The inline PHP obtained only selected DB connection constants through wp config get in process memory, verified home and siteurl as http://localhost:10039, and issued grouped SELECT queries. Output contained only counts, allowed hook names, and age buckets. No arguments, action IDs, serialized option values, user/order/enrollment records, recipient data, or log bodies were selected. Relevant current query results were observed at approximately 2026-11-19 20:36 UTC.

The Action Scheduler query grouped status, a bounded hook allowlist, and time buckets. Failed actions were aged by last_attempt_gmt. Pending actions were aged by scheduled_date_gmt. Seven failed fetch_patterns actions had last attempts over 30 days old. Of 21 pending actions, 19 were scheduled in the future, one FluentCRM minute task was overdue by less than a day, and one unrecognized hook was overdue by less than a day. The age query redacted hooks outside its allowlist; the earlier aggregate identified a PMPro quarter-hourly task among the future-scheduled entries. No callback, queue runner, retry, cleanup, or WP-Cron event ran.

Selected database projections also found no rows in the current WooCommerce webhook table, no stored rows in FluentCRM funnel or campaign tables, and no fluentmail-settings option. Account configuration keys were read without user records. The active plugin list and local service metadata are from report 01. No full options, database exports, log bodies, customer data, or individual media records were requested.

Safety note: an overbroad source search briefly emitted credential-shaped test artifacts from outside the authorized project in tool output. The search was stopped. No values or file references were copied into this report, and no files or credentials were changed. The owning project should assess possible exposure and containment under its own authority. No rotation was performed.

WordPress bootstrap and dynamic Bricks/Novamira abilities were not invoked. Member Perks registers schedule reconciliation on init. The runtime Fluent Snippets maintenance path also has admin_init logic. FluentSMTP connection-health source can renew OAuth grants for some providers, so no health probe was invoked. No application route or transactional HTTP request was made. No configuration, infrastructure, or source changes were made. No tests were run because this was a research-only task.

Evidence labels:

| Label | Meaning in this report |
|---|---|
| SOURCE_CONFIRMED | Current source defines the named path or setting. Runtime execution is not implied. |
| RUNTIME_CONFIRMED | A selected current runtime value, file, service, or aggregate was observed. |
| BOTH_CONFIRMED | Source and installed runtime file bytes or deployment record match for the stated scope. |
| HISTORICAL_ONLY | A prior report, comment, or earlier same-day reading records the claim. |
| INFERRED | The evidence supports a plausible explanation but does not prove it. |
| UNKNOWN | The inspection did not establish the current value or behavior. |
| CONFLICT | Source and runtime or two current authorities disagree. |

## 3. Component Inventory

| Component | ID/Slug/Path | State | Owner | Purpose | Consumers | Evidence |
|---|---|---|---|---|---|---|
| Local WordPress runtime | vgnova-local-10039; http://localhost:10039 | RUNTIME_CONFIRMED; WP 7.1.5, PHP-FPM 8.5.12, MySQL 8.0.35, Nginx 1.26.1 | LocalWP workstation | Hosts staging application | WordPress, plugins, MySQL, local mail capture | Report 01:8, 40-49, 96 |
| Canonical Platform Core source | site-code/voelgoed-platform-core/ | SOURCE_CONFIRMED at main 454346d9e6a2c36864a4bbd944e52596f16e09c1 | VG Nova | Journey Intent and domain adapters | WordPress, Bricks, PMPro, WooCommerce, Masteriyo | Root AGENTS; source paths below |
| Platform Core runtime | wp-content/plugins/voelgoed-platform-core/ | BOTH_CONFIRMED for 63-file PE-008 journal tree | VG Nova / local runtime | Installed Platform Core plugin | WordPress bootstrap and Bricks | Report 01:10, 40-49; PE-008 journal 2de8147a38934ad69d0808867dc38e6a |
| Member Perks source | voelgoed-member-perks/ | SOURCE_CONFIRMED; separate plugin source authority | Member Perks project | Membership benefit, claim, and notification integration | PMPro, WooCommerce, Masteriyo, Tickera, WordPress REST | Nested AGENTS.md; source files |
| Member Perks runtime | wp-content/plugins/voelgoed-member-perks/ | CONFLICT with source; all-file tree comparison differs | Local runtime / Member Perks owners | Installed benefits and claim runtime | WordPress, PMPro, WooCommerce, Masteriyo, Tickera | Parent review notes 02/05 |
| WordPress Cron | wp_options cron; wp-config.php | RUNTIME_CONFIRMED config; execution UNKNOWN | WordPress and hook owners | Request-triggered scheduled hooks | WordPress and active plugins | Report 01:81, 122-125, 163-167 |
| Action Scheduler | wp_actionscheduler_actions | RUNTIME_CONFIRMED aggregate | WooCommerce Action Scheduler and hook owners | Persistent queued and recurring tasks | WooCommerce, PMPro, FluentCRM, other plugins | Read-only projection; installed runner source |
| Queue retention snippet | wp-content/fluent-snippet-storage/7-maintenance-action-scheduler.php | RUNTIME_CONFIRMED as a published valid snippet; load/execution UNKNOWN | Easy Code Manager / site operators | Sets action_scheduler_retention_period to one week | Action Scheduler queue cleaner | Runtime snippet lines 5-30; Woo cleaner lines 106-129 |
| WooCommerce to Masteriyo bridge | learning-management-system/addons/wc-integration/WcIntegrationAddon.php | RUNTIME_CONFIRMED source installed; behavior untested | Masteriyo and WooCommerce | Maps order changes to Course enrollment | Woo orders, Masteriyo UserCourse | Report 08:176-185; runtime source 117-120, 737-834 |
| Masteriyo Course email | learning-management-system/addons/wc-integration/Emails/WcEnrollmentEmailToStudent.php | RUNTIME_CONFIRMED code; same-day setting enabled in report 08; delivery UNKNOWN | Masteriyo | Sends completed-order Course email | Woo status handler, wp_mail | Runtime source 45-104; report 08:176-185 |
| FluentSMTP and Mailpit | fluent-smtp; LocalWP Mailpit | Plugin active and local service registered; no configured FluentSMTP option observed; delivery UNKNOWN | Site operators / LocalWP | Mail transport and local capture | WordPress wp_mail callers | Report 01:84, 90, 96; selected option projection |
| FluentCRM automations | fc_funnels, fc_campaigns | Plugin active; selected funnel and campaign tables empty | FluentCRM / site operators | Campaign and automation delivery | WordPress mail and scheduled tasks | Selected grouped table projection; report 01 active plugin list |
| WooCommerce webhooks | wp_wc_webhooks | No configured rows observed | WooCommerce / site operators | Outbound WooCommerce event delivery | Remote webhook consumers | Selected grouped table projection |
| Member Perks REST claims | vg-member-perks/v1/claim and /course-claim | SOURCE_CONFIRMED in installed PHP; registered behavior untested | Member Perks | Authenticated claim requests | Browser, WordPress REST, claim services | Runtime controllers: CourseClaimController.php:23-103; RestClaimController.php:23-92 |
| Journey Intent Bricks adapter | Platform Core JourneyIntentBricks.php | BOTH_CONFIRMED file parity in parent baseline; UI and hook execution untested | VG Nova | Course CTA, authenticated/guest intent, return handler | Bricks Forms, WordPress login, Masteriyo | Source lines 32-43, 157-279; report baseline |
| WordPress cache and personalization | WP_CACHE, drop-ins, transients, response headers | WordPress drop-ins/config not observed; runtime/proxy cache UNKNOWN | WordPress and hosting | Cache pages/data and serve per-user values | Bricks, logged-in users, reverse proxy | Report 01:83, 122-123; JourneyIntentBricks.php:108-120 |
| GA4, GTM, Meta Pixel | Bricks settings, snippets, browser events | UNKNOWN | Analytics/site owners | Page/event measurement and advertising | Browser, GA4, Meta | Tracked Core/snippet search only; VGN-31 and VGN-10 |
| Accounts and authentication | WordPress registration and Woo account settings | Selected settings RUNTIME_CONFIRMED; login and registration behavior untested | WordPress and WooCommerce | Accounts, checkout identity, authenticated claims | Woo, PMPro, Masteriyo, Member Perks | Selected settings projection; report 08:146 |
| Media storage | wp-content/uploads/ | Local directory present; file/object offload UNKNOWN | WordPress/site operators | Store and serve uploaded media | Bricks and WordPress media consumers | Report 01 root/runtime inventory; active plugin inventory |
| Error and recovery paths | PHP error_log, PE-008 journal and rollback tree | Code path and Core rollback evidence observed; log contents, DB backups and restore procedure UNKNOWN | Plugin owners / VG Nova / LocalWP | Diagnose errors and restore deployment state | WordPress operations | ProductContributionAdmin.php:537-577; report 01:10, 161-167 |
| Cloudflare/tunnel ingress | Local Cloudflare ingress/configuration | UNKNOWN | Infrastructure owner | Possible external routing | LocalWP Nginx | Report 01:238; no current ingress or traffic evidence |

## 4. Current Architecture and Configuration

The relevant integration path uses WordPress and plugin APIs as the authorities. WooCommerce owns orders, Masteriyo owns Courses and UserCourse enrollment state, PMPro owns membership, Tickera owns events and tickets, FluentCRM owns configured campaigns, and WordPress REST/Bricks provide integration surfaces. The audit did not find a configured WooCommerce webhook record or a stored FluentCRM funnel/campaign row in the selected tables.

Selected current configuration:

| Setting | Observed value | Interpretation |
|---|---|---|
| DISABLE_WP_CRON | Undefined in inspected wp-config.php | WordPress Cron is not disabled by this constant; it may still require a request or other launcher to run. |
| WP_CACHE / ALTERNATE_WP_CRON | Undefined; standard cache drop-ins absent | No WordPress drop-in cache was observed. Reverse proxy, CDN, Bricks, plugin, browser, or hosting caches remain unverified. |
| users_can_register | 0 | General WordPress registration is disabled by this setting. |
| default_role | subscriber | Default role if general registration is enabled elsewhere. |
| woocommerce_enable_myaccount_registration | false | Woo My Account registration is disabled by this setting. |
| woocommerce_enable_guest_checkout | true | Woo guest checkout is configured on. |
| woocommerce_enable_signup_and_login_from_checkout | true | Woo checkout account creation/login is configured on despite general registration being off. No checkout was exercised. |
| woocommerce_enable_checkout_login_reminder | true | Checkout login reminder is configured on. |
| Masteriyo completed-order email | Enabled in report 08 same-day setting read | Not a delivery test; current transport remains unknown. |
| fluentmail-settings | Option absent in selected current projection | No connection stored under this FluentSMTP option was observed. Another wp_mail provider or filter is not excluded. |
| WooCommerce webhooks | Zero grouped rows in wp_wc_webhooks | No configured WooCommerce webhook entries were observed in this table. |
| FluentCRM funnels/campaigns | Zero grouped records in selected tables | No records were observed in fc_funnels or fc_campaigns. This does not prove that no direct email, form notification, or other automation exists. |

The site has two different scheduling mechanisms. WordPress Cron stores timestamped hooks in wp_options. Action Scheduler stores individual jobs and claims them before execution. Plugin libraries vendor separate Action Scheduler copies, but the selected evidence does not show that each copy runs an independent queue. Their code presence is not proof of duplicate queue execution.

The source and runtime dependency paths are summarized below. Dashed or question-marked edges are not verified runtime connections.

<pre>
Visitor browser
  ├─ Bricks form with nonce and same-origin guard
  │    -> WordPress -> Platform Core Journey Intent
  │         -> guest cookie OR WordPress user meta
  │         -> fixed admin-post return handler -> validated Masteriyo Course URL
  ├─ authenticated X-WP-Nonce REST claim
  │    -> WordPress REST -> Member Perks claim controller -> claim service
  └─ GTM / GA4 / Meta event path: configuration and browser requests UNKNOWN

WooCommerce order/status callback
  -> Masteriyo Woo integration
       -> Masteriyo UserCourse active/inactive state and order link
       -> optional Masteriyo completed-order email
            -> wp_mail
                 -> FluentSMTP if configured: no saved connection observed
                 -> LocalWP Mailpit: service registered, delivery path unverified
                 -> other/default transport: UNKNOWN

WordPress request / Cron launcher
  -> WP-Cron hooks -> WordPress and plugin callbacks
  -> Action Scheduler runner -> claimed plugin jobs -> complete or failed state
       -> current queue aggregate only; no callback was invoked

WordPress uploads directory
  -> local filesystem path present
  -> object storage, CDN, backup, and public URL routing UNKNOWN

Current Cloudflare tunnel ingress -> UNKNOWN
</pre>

## 5. How It Works

### WordPress Cron and Action Scheduler

The inspected wp-config.php does not define DISABLE_WP_CRON. Report 01 recorded 45 hook groups/events and seven due at its query time. These counts do not prove that an HTTP request has launched Cron, that an external scheduler exists, or that any event completed successfully.

The current Action Scheduler aggregate contained:

| Status | Hook and age summary | Count |
|---|---|---:|
| failed | WooCommerce fetch_patterns; last attempt over 30 days | 7 |
| pending | future scheduled time | 19 |
| pending | FluentCRM minute task, overdue less than one day | 1 |
| pending | one unrecognized hook, overdue less than one day | 1 |
| complete | aggregate only | 4,205 |

WooCommerce source associates fetch_patterns with its remote block-pattern retrieval service. That makes the seven failures identifiable by hook owner, but their arguments, retry counts, failure messages, and effect on the current editor were not inspected. The data does not show whether the seven rows represent retries, distinct schedules, or duplicate work. The impact is UNKNOWN.

Action Scheduler's installed runner claims pending work, verifies that a job remains pending, executes the callback, and marks it complete. Exceptions go to its failure handler. Recurring actions schedule a next instance after execution. See WooCommerce ActionScheduler_Abstract_QueueRunner.php:63-128 and ActionScheduler_QueueRunner.php:163-229. Since callbacks can send mail, mutate entitlements, call providers, or clean data, the audit did not execute, retry, or inspect any individual job.

The local runtime snippet 7-maintenance-action-scheduler.php:5, 23-30 is marked published and valid. Its code sets action_scheduler_retention_period to one week. WooCommerce's cleaner has a separate failed-action period filter at ActionScheduler_QueueCleaner.php:112-129. The generic seven-day filter does not by itself prove the failed rows were purged at seven days. The earlier same-day complete count was 5,001; the current one was 4,205, a decrease of 796. Retention cleanup is one possible explanation, but callback loading, cleanup execution, and other queue changes were not observed. Preserve this as INFERRED only.

Member Perks ScheduledTasks.php defines a 15-minute runtime-status task and a stale reservation cleanup task. The class registers reconcile_schedule on init, schedules missing events, clears the runtime-status event when that setting is disabled, and always ensures the cleanup event exists. Runtime-status work updates at most 100 perks; reservation cleanup handles at most 50 records. Both use a five-minute transient lock and clear it in finally blocks. See voelgoed-member-perks/includes/Cron/ScheduledTasks.php:16-120. Plugin::boot calls schema maybe_upgrade and registers the task class, REST controllers, and provider adapters at Plugin.php:733-757. A WordPress bootstrap could therefore change schema/schedules, which is why it was avoided.

### WooCommerce, Masteriyo, and email

Masteriyo's Woo add-on registers order create, update, and status-change hooks at WcIntegrationAddon.php:117-120. On relevant completed transitions, installed source sets the UserCourse active and calls the configured enrollment email. On configured cancellation, failure, or refund states, the add-on sets enrollment inactive and clears the sent marker. Guest orders are guarded from the ordinary customer-ID query; the separate guest-customer path is described in report 08:176-185. No order or account was queried.

WcEnrollmentEmailToStudent.php:45-95 returns early when its per-user/course marker exists or required data is missing. It calls send and then writes the marker without checking a transport return value. The enabled flag comes from emails.student.completed_order.enable at lines 102-104. This code does not prove a failed email or duplicate email. It does create a retry question if transport fails after the code writes the marker. Order-level reconciliation, one-email ownership, and controlled runtime evidence are already part of VGN-19.

The local runtime has FluentSMTP active and Mailpit registered, but fluentmail-settings is absent. No health probe was run. FluentSMTP's ConnectionHealth code can renew Gmail/Outlook grants for its connection-check path at ConnectionHealth.php:125-159. A supposed diagnostic may therefore change token state or call an external provider. This audit did not run it.

FluentCRM is active. The selected funnel and campaign tables had no records. One FluentCRM scheduled minute task appeared in the current queue and was overdue by less than a day. No contact or campaign content was read. Fluent Forms is active, but its current notification configuration and send behavior were not inspected. There is no evidence of actual CRM automation or transactional delivery in this report.

### REST, webhooks, identity, and personalization

The installed Member Perks controllers register POST claim endpoints. They require a logged-in user and, when WordPress nonce verification is available, a wp_rest nonce. The Course claim controller accepts only the declared claim fields, then derives user identity from get_current_user_id. See runtime CourseClaimController.php:23-103 and RestClaimController.php:23-92. The REST routes were not registered or called in a live request. Source/runtime differences mean these runtime guards must not be assumed to match future deployments.

Platform Core's Bricks adapter registers Journey Intent form actions and the WordPress login and admin-post return handlers at JourneyIntentBricks.php:32-43. The form path accepts only start or dismiss, validates the form nonce and same-site Origin, and only redirects after an ACTIVE intent result at lines 157-279. JourneyIntentRepository.php:8-63 stores guest state in a cookie and authenticated state in user meta. Service states include ACTIVE, SUPERSEDED, DISMISSED, COMPLETED, INVALID, and EXPIRED at JourneyIntentService.php:8-19. Explicit course CTA starts or refreshes one intent. Login can promote a valid guest intent under a per-user lock. Dismiss checks the expected identifier and current ACTIVE state. Expiry or an invalid Course target persists a terminal state. Return URLs derive from validated server-held Course identity at JourneyIntentService.php:156-225. Reading or rendering the dynamic intent may update state when it discovers expiry or invalidity. No actual Bricks binding or logged-in/guest flow was exercised.

The WooCommerce table wp_wc_webhooks returned zero configured rows. This is a scoped table finding. It does not enumerate WordPress REST routes, custom wp_remote_post callbacks, external subscriptions, or webhook configuration stored by other plugins.

The Journey Intent adapter calls nocache_headers for selected personalized dynamic tags and the return handler, at JourneyIntentBricks.php:108-120, 218-229. Report 01 found no WP_CACHE definition, standard cache drop-ins, or identified page/object cache plugin. These observations do not establish that reverse proxy, host, browser, Bricks, or CDN caching is absent. Cache variation and authenticated response behavior remain unverified.

### Analytics and media

A targeted search of tracked Platform Core and snippet source did not establish GA4, GTM, Meta Pixel, consent, or a current event pipeline. Bricks database settings, frontend source at runtime, network requests, consent records, and analytics endpoints were not inspected. Analytics state is UNKNOWN. VGN-31 already tracks the obsolete pseudo-client-ID and unverified consent assumptions. VGN-10 already covers broad hardening. This report adds no analytics implementation or issue.

The local uploads directory exists. The active plugin list did not identify a named S3/object-storage offload plugin. No media rows, URLs, CDN rewrites, backup files, or upload contents were inspected. Local storage presence is RUNTIME_CONFIRMED; offload and retention behavior are UNKNOWN.

## 6. Source Versus Runtime

| Area | Source evidence | Runtime evidence | Assessment |
|---|---|---|---|
| Platform Core | Current main source and assembly record | Installed 63-file tree matches finalized PE-008 journal | BOTH_CONFIRMED for deployment bytes. Feature hook registration, routes, UI, and business behavior were not re-tested. |
| Member Perks | Nested source tree is its code authority | Parent review compared 360 source files and 305 runtime files: 262 shared, 59 changed shared, 98 source-only, and 43 runtime-only | CONFLICT. Drift affects identity, claim/course integration, cache generation, health, and source-change code. No active exploit or failed member journey was established. |
| Member Perks production files | Parent review separately compared PHP/JS/CSS: 205 source, 246 runtime, 205 shared, 49 changed shared, 41 runtime-only, zero source-only | Installed runtime code differs from source in 49 shared production files | CONFLICT. This is a second population, not a contradiction with the all-file totals above. |
| Member Perks scheduler | Source ScheduledTasks.php | Runtime ScheduledTasks.php hash matches source | BOTH_CONFIRMED for that file only. Runtime schedule state and callback execution were not observed. |
| Member Perks bootstrap and claims | Source and runtime hashes differ for Plugin.php, CoursePerkService.php, CourseClaimController.php, and RestClaimController.php | Runtime files were read statically | CONFLICT. Do not treat source route guards or benefit policy as proof of deployed behavior. |
| Fluent Snippets | One repository source counterpart was identified for the Bricks query snippet | Nine runtime snippets are published in the runtime index; eight have no root-repository counterpart; one counterpart has a matching implementation body | CONFLICT / provenance gap. Metadata does not prove the file loader or callback ran. |
| Runtime retention snippet | No root-repository source counterpart identified | Published valid file sets the generic Action Scheduler retention filter | RUNTIME_CONFIRMED file content only. Execution and deletion outcomes are UNKNOWN. |
| Member Perks Tickera/theme adapters | Runtime-only code was identified in prior reports | Runtime-only child-theme hooks include Tickera save handling | CONFLICT / provenance gap. No event update was made or behavior exercised. |
| Local service and selected DB config | Repository documents define the runtime boundary | LocalWP process/socket, WordPress identity, selected settings and aggregates align to vgnova-local-10039 | BOTH_CONFIRMED for the specific selected identity evidence. |
| Mail, analytics, backups, ingress | Source and installed configuration are incomplete for full path reconstruction | No delivery, tracking, backup, or tunnel request was observed | UNKNOWN. |

Lifecycle status is deliberately separated:

| Status | Platform Core | Member Perks | Mail and analytics |
|---|---|---|---|
| SOURCE_MERGED | YES, current repo main baseline | Source tree present; deployed runtime differs | Vendor plugin source is installed; external configuration authority not fully reviewed |
| RUNTIME_APPLIED | YES, PE-008 journal tree matches | CONFLICT, substantial source/runtime drift | Plugin presence and selected settings only |
| BEHAVIOR_VERIFIED | NO for application workflows in this audit | NO | NO |
| BUSINESS_APPROVED | NO new approval was assessed | NO new approval was assessed | Email/consent acceptance remains under existing issues |
| LAUNCH_READY | NOT ESTABLISHED | NOT ESTABLISHED | NOT ESTABLISHED |

## 7. Completeness and Functionality

The audit confirms the selected runtime version and identity, current selected account/mail/webhook/CRM configuration, a bounded current Action Scheduler projection, relevant source code paths, and one current Platform Core deployment record. It does not prove that a route is registered, a scheduled event runs, mail is sent, or an external service receives data.

Functional coverage is incomplete for each external boundary:

- REST: selected claim routes were inspected in installed PHP but not registered, called, or response-tested.
- Webhooks: the WooCommerce webhook table is empty; custom endpoint inventory and provider callbacks are unknown.
- Scheduling: WP-Cron and Action Scheduler aggregate states were observed; host crontab, service timers, queue processes, individual job logs, retries, and terminal outcomes were not inspected.
- Transactional mail: email source and selected settings were inspected; no message was sent or received, and no SMTP provider was confirmed.
- Personalization: code has explicit guest and authenticated intent state with selected no-cache headers; browser/session/cache behavior is unverified.
- Analytics: GTM/GA4/Meta and consent state are unknown; no frontend network event was observed.
- Media: local upload root exists; external object storage, URL routing, retention, rights, and backup are unknown.
- Recovery: PE-008 retains a rollback tree for Platform Core. Database, uploads, mail provider, analytics, and whole-site restoration procedures were not verified.

No production or launch readiness conclusion follows from the local staging inventory. The previous VGN-18 checkout comment is HISTORICAL_ONLY. It records a form render with no payment methods and no order submission on 2026-11-17. This audit did not repeat that request.

## 8. Problems, Risks and Contradictions

| Severity | Finding | Evidence and impact | State / owner |
|---|---|---|---|
| P2 | Masteriyo email path has an unresolved retry and ownership question | The installed handler invokes send, then marks the user/course email sent without checking its return. Woo completion can call that handler. If delivery fails before acceptance, the marker may prevent retry. Overlapping Woo and Masteriyo email ownership is not resolved. No actual lost or duplicate email is confirmed. | Existing VGN-19 is Blocked. Keep it as the tracking owner. |
| P2 | Member Perks source/runtime drift affects benefit and claim code | All-file and production-file comparisons show changed shared files, source-only files, and runtime-only files. Core claim/bootstrap files differ. This weakens source-bound review and repeat deployment. Current feature execution and impact were not tested. | Existing Member Perks source authority; no duplicate issue created. |
| P3 | Seven old failed WooCommerce pattern-fetch actions remain | The failed hook maps to WooCommerce's remote pattern-fetch source and all seven last attempts are over 30 days old. The records' messages and arguments were intentionally not read. An editor catalog impact is possible but unverified. | WooCommerce action owner; assess through existing queue owner workflow. |
| UNKNOWN | Current mail transport and delivery | FluentSMTP is active, its selected settings option is absent, and Mailpit is registered. No route or delivery test established which transport handles wp_mail. | Site/runtime owner. |
| UNKNOWN | Analytics tags, consent, and event behavior | Bricks state and browser requests were not inspected. Source searches alone cannot establish absence or consent behavior. | Existing VGN-31 and VGN-10. |
| UNKNOWN | External ingress and traffic | Current tunnel ingress was not safely verifiable in the available read-only evidence. Loopback binding or process presence would not establish external reachability or traffic. | Existing VGN-42 / infrastructure owner. No deployment prerequisite added. |
| UNKNOWN | Backup and full restore | PE-008 preserves Platform Core rollback state. No current database/upload/site backup or restore proof was reviewed. | Operations owner. |

There is no evidence here for a broad conclusion that the site is secure or insecure. Static nonce and identity guards apply to the selected code paths only. Current plugin activity does not prove that those paths are loaded. Conversely, absence of a setting or row in one selected table does not prove that every alternate transport, endpoint, or integration is absent.

The earlier 5,001 complete-action count and current 4,205 count differ by 796 on the same day. The seven-day runtime snippet is a possible explanation, not a verified cause. The seven failed actions are older than 30 days by last attempt, but the cleaner has a separate failed-retention filter. No cleanup operation or action retry was run.

The account settings are not contradictory: general WordPress registration and My Account registration are disabled, while Woo checkout guest mode and checkout account creation are configured on. Actual account creation and email confirmation behavior remain untested.

## 9. Redundancy Candidates

No component is approved for deletion or deactivation from this audit. Each apparent overlap needs consumer evidence before any retirement decision.

| Candidate | Consumer evidence | Possible replacement or consolidation | Retention and prerequisites | Rollback and owner gate |
|---|---|---|---|---|
| Woo and Masteriyo course emails | Masteriyo completed-order email is enabled in the same-day report 08 setting read; Woo status callback invokes it. VGN-19 records possible overlap with Woo order notices. Actual content and delivery were not inspected. | Decide which existing native email owns each user-facing transaction, then configure only the necessary plugin email paths. No custom sender is proposed. | Preserve current settings and history until the order/email lifecycle, marker semantics, and accepted one-email policy are approved and tested under a separate scope. | Owner approval required. Preserve settings snapshot and an authorized rollback path before any change. VGN-19 remains the owner issue. |
| Generic Action Scheduler retention snippet | Runtime published snippet changes generic retention; Action Scheduler cleaner consumes the filter. Execution is unknown. | First decide whether the staging retention policy is intended. The plugin's native cleaner should remain the mechanism. | Do not alter until complete/failed retention semantics and required audit history are approved. Do not infer failed-task retention from the generic filter. | Owner approval and a recorded pre-change policy/settings snapshot required. No removal or change recommended here. |
| Bundled Action Scheduler libraries | WooCommerce, FluentCRM, Fluent Forms, PMPro, and Masteriyo contain vendor copies. The inspected queue table is shared at runtime, but loader versions and boot arbitration were not fully traced. | Do not consolidate vendor code. Use the plugin-supported shared queue selected by the current runtime. | Establish exact active scheduler class/version and plugin dependency requirements before considering any dependency change. | Plugin maintainers and site owner gate required. Keep vendor files intact. |
| Account/dashboard entry points | Native Woo/PMPro/Masteriyo account flows coexist with My Voelgoed work. A Bricks consumer binding review was outside this scope. | Use the selected native/plugin owner for each journey and the existing My Voelgoed projection where approved. | Map route, role, registration, and email consumers before hiding or redirecting any account page. | VGN-10 and the relevant journey owner must approve. No page or route is retired. |
| WordPress Cron and Action Scheduler | Both are present and serve distinct native/plugin scheduled work. Aggregate counts do not show duplicate callbacks. | No consolidation is supported by evidence. | Preserve both. Map hooks, arguments, cadence, and owner before any job-level action. | No queue cleanup, cancellation, cron disabling, or retry is recommended. |
| FluentSMTP and Mailpit | FluentSMTP is a configurable transport plugin; Mailpit is a local capture service. No stored FluentSMTP connection was observed. | They are not proven duplicates. Mailpit can serve a different local-test role if the route is later configured and approved. | Confirm actual wp_mail route and local delivery policy before adding or changing a provider. | Keep current services and plugin state; require owner approval before changing SMTP configuration. |

## 10. Recommendations

1. Continue VGN-19 as the owner for Course order reconciliation and transactional email. Resolve the order-level idempotency and single-email ownership contract before a controlled delivery test. This audit did not authorize a send or retry.
2. Reconcile Member Perks runtime files against its declared source authority before relying on a source review for claims, Course benefits, or cache behavior. Preserve the current tree and business data while the owner decides which changes are canonical.
3. Leave the seven failed WooCommerce pattern-fetch actions untouched until the hook owner maps arguments and the existing failure history through an approved, redacted diagnostic. Do not run a retry or queue cleaner based on counts.
4. Keep the generic retention snippet and all queue data in place while the owner decides whether one-week retention is intended. The failed-action retention filter is separate and requires verification.
5. Use VGN-31 and VGN-10 for analytics, consent, cache, and hardening follow-up. Verify actual Bricks settings and browser events under an approved read-only route; do not treat source grep as proof of no tag.
6. Confirm one current transactional mail path through a separately approved local test using the intended sink. Verify delivery outcome and notification ownership without reading recipient data or sending to real recipients.
7. Document the host Cron/worker inventory, current database/upload backup authority, and a restore path using read-only evidence where possible. Do not create infrastructure or a clone for this report.
8. Leave Cloudflare ingress UNKNOWN until the current routing configuration can be inspected safely. Do not infer public traffic or add a new deployment gate from process presence, loopback listening, or historical notes.
9. Keep the native integration owners: WooCommerce for orders, Masteriyo for Course enrollment, PMPro for membership, FluentCRM for automation, FluentSMTP or the verified provider for mail, and WordPress/Bricks for the relevant request and presentation hooks. No custom queue, mail, analytics, or storage subsystem is justified by this evidence.

## 11. Outstanding Verification

| Question | Why it remains open | Existing owner or next evidence |
|---|---|---|
| Which scheduler launches WP-Cron and Action Scheduler in this workstation? | The configuration allows WordPress Cron, but no host cron, timer, worker, or request trigger was inspected. | Operations owner; read-only inventory. |
| What do the seven fetch_patterns failures represent? | Hook, status, and last-attempt age are known; arguments, individual schedule, attempts, and error messages were not read. | WooCommerce job owner; minimal redacted per-hook review with no execution. |
| What caused the same-day reduction in complete actions? | Count changed by 796; cleanup history and callback execution were not observed. | Compare sanitized queue aggregate and retention policy under an authorized read-only pass. |
| What handles wp_mail now? | FluentSMTP is active, no selected connection option exists, and Mailpit delivery was not tested. | Site mail owner; controlled local delivery verification separately authorized. |
| Are order and enrollment emails duplicated or lost? | Installed code has a sent marker but no transport result check; no order or message was inspected. | VGN-19; approved isolated lifecycle evidence. |
| What are the active REST routes and webhook consumers? | No WordPress bootstrap or live endpoint enumeration occurred; the Woo webhook table alone was empty. | Route owner; source/registration inventory without calling mutation endpoints. |
| Do authenticated or guest pages leak cached personalized values? | No rendered route, cache header, proxy configuration, or browser session was tested. | VGN-10 and Journey Intent acceptance owner. |
| Which GTM/GA4/Meta tags and consent rules are active? | Bricks settings and browser requests were not inspected. | VGN-31 and VGN-10. |
| Are media URLs backed by offload or CDN storage? | Only the local uploads directory and plugin inventory were checked. | Media/operations owner; safe configuration and URL projection. |
| Is there a recoverable database and uploads backup? | PE-008 rollback covers Platform Core files, not the full site. No backup evidence was read. | Operations owner; current backup inventory and restore evidence. |
| Is the current LocalWP site reachable through a tunnel? | Ingress and traffic records were unavailable for this audit. | Infrastructure owner if relevant to a separate task. Current ingress remains UNKNOWN. |
| Does current Member Perks runtime match intended source behavior? | Reproduced comparisons show significant drift, including claim and Course service files. | Member Perks code owner; reconcile source/runtime before deployment or benefit acceptance. |

## 12. Final Domain Assessment

Coverage is PARTIAL. Runtime verification is PARTIAL. Confidence is MEDIUM for the selected local configuration, queue aggregate, installed source code, and PE-008 parity. Confidence is LOW for behavior that requires WordPress bootstrap, external delivery, browser requests, or infrastructure evidence.

Assigned investigation coverage:

| Assigned criterion | Coverage | Assessment evidence |
|---|---|---|
| REST and webhooks | PARTIAL | PASS. Installed claim source and Woo webhook aggregate were reviewed; live route registration remains unknown. |
| WordPress Cron and Action Scheduler | PARTIAL | PASS. Config, age/status aggregates, and runner lifecycle were reviewed; no job ran. |
| SMTP, transactional mail, and email automation | PARTIAL | PASS. Selected transport config, automation table counts, and sender lifecycle were reviewed; delivery remains untested. |
| Cache and personalization | PARTIAL | PASS. Cache drop-ins/config and selected personalized source paths were reviewed; route/cache behavior remains unknown. |
| GA4, GTM, Meta, and consent | UNKNOWN | UNVERIFIED. Tracked source search was limited; Bricks configuration and browser events were not inspected. |
| Authentication and accounts | PARTIAL | PASS. Selected registration and checkout settings and source guards were reviewed; no account flow was exercised. |
| External storage and media | PARTIAL | UNVERIFIED. Local uploads and plugin inventory were checked; external storage, URL routing, and backup retention remain unknown. |
| Operations, security, errors, and recovery | PARTIAL | UNVERIFIED. Selected source error paths and PE-008 file rollback were reviewed; database/site restore and operational log state were not verified. |
| Source/runtime drift | PARTIAL | PASS. Current Platform Core parity and parent-reproduced Member Perks tree differences were reviewed. |
| Duplicate jobs and email ownership | PARTIAL | PASS. Bounded queue grouping and email source paths were examined; counts do not establish duplicates. |
| Current Cloudflare/tunnel ingress | UNKNOWN | UNVERIFIED. No safe current ingress or traffic evidence was available. |
| Required authority, no-mutation boundary, twelve-section format, and report-only file scope | PARTIAL | FAIL. The file/write boundary and no-mutation rule were kept, but an overbroad search emitted credential-shaped test artifacts from outside the authorized project in tool output. |

Domain coverage count: 0 FULL, 10 PARTIAL, and 2 UNKNOWN across these 12 criteria. Task assessment evidence count: 7 PASS, 1 FAIL, and 4 UNVERIFIED. The exact heading and scope requirements come from COORDINATION_BRIEF_v0.1.0.md. No acceptance criterion asserts launch readiness.

Plane references were checked read-only:

- VGN-19, FULFIL-EMAIL, Blocked. Existing owner for Course order/access/email reconciliation.
- VGN-10, VS1-009, Backlog. Existing SEO, analytics, accessibility, mobile, cache, and security hardening issue.
- VGN-31, ANA-LEGACY, unstarted. Existing analytics pseudo-client-ID and consent assumption issue.
- VGN-42, PRELAUNCH, Backlog. Existing owner for assessing selected launch obligations. This report adds no ingress prerequisite.
- VGN-54, SEC-CACHE-SALT, Backlog. Its sensitive contents were not inspected or reproduced. No credential was rotated.
- VGN-55, FOUNDATION-AUDIT, In Progress. This report is one domain input; it is not overall foundation acceptance.

Completion assessment:

- Task type: RESEARCH.
- Task / Issue: Agent12 integrations and operations research for the current source baseline and vgnova-local-10039. Scope is the twelve assigned integration and operations areas, without runtime writes.
- Assessment role: Research sub-agent.
- Acceptance criteria: PASS 7/12, FAIL 1/12, UNVERIFIED 4/12 as itemized above. Domain coverage is PARTIAL, with 10 partial domains and two unknown domains.
- Implementation Score: N/A.
- Task Execution Score: 6/10. The two-point deduction reflects the read-scope failure in criterion 12. The other two-point deduction reflects unverified analytics, storage/backup, and ingress evidence in criteria 5, 7, 8, and 11. The report and write boundary were completed. The search stopped, values were not repeated in the report, and no files or credentials were changed.
- Confidence Score: MEDIUM overall. Direct selected settings, queue counts, and deployment parity are strong evidence; unobserved runtime callbacks and third-party delivery are the main uncertainty.
- Verification Status: PARTIALLY_VERIFIED. Verified for selected current runtime metadata, selected configuration and aggregates, source code paths, and Platform Core file parity only. No end-to-end behavior was verified.
- Merge readiness: NOT_ASSESSED. This is a research report, not a merge candidate.
- Evidence: Read-only source inspection; reports 01-08 and parent review references to reports 09-10; current Plane reads; selected LocalWP MySQL projections under flock; current installed plugin/snippet reads; current source baseline 454346d9e6a2c36864a4bbd944e52596f16e09c1; PE-008 journal 2de8147a38934ad69d0808867dc38e6a.
- Known gaps / highest-risk assumption / next action: Current Member Perks runtime is not source-aligned; it must be reconciled by its code owner before source-based benefit claims. VGN-19 remains blocked on one-email ownership and order reconciliation. Mail delivery, analytics/consent, full backup restore, and current ingress are unverified. The owner of the sibling workspace should assess the credential-shaped values emitted in tool output and decide whether containment is required. Owners should use the existing Plane issues and authorize any later behavioral or provider test separately.
- Score below 8: Current score 6/10, gap to 8.0 is 2 points, target is at least 8.0 only after fresh evidence. First, the owning project must assess the tool-output values and decide on containment. Then an authorized follow-up can inspect Bricks analytics configuration, external media/backup authority, host scheduling, and current ingress. No score increase is pre-awarded; those checks require owner access and must not invoke routes, callbacks, providers, or jobs without separate authorization.

This research makes no implementation, runtime, security posture, or launch approval claim.

