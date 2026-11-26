# Plugin Inventory v0.1.0

Project: Voelgoed Platform Consolidation (VG Nova)
Inventory date: 2026-11-19, Africa/Johannesburg
Source baseline: vgnova main at 454346d9e6a2c36864a4bbd944e52596f16e09c1
Runtime target: LocalWP site "Voelgoed", http://localhost:10039
Status: Read-only inventory. No runtime setting, plugin, theme, database, or content was changed.

## 1. Executive summary

The active LocalWP runtime reports 31 installed plugins: all 31 are active and none are inactive. It has one must-use plugin and no WordPress PHP drop-ins. Bricks is the active parent theme for Bricks Child Theme; it is not a plugin.

The source assigns data ownership to ACF, WooCommerce, WooCommerce Subscriptions, PMPro, Masteriyo, Tickera, Member Perks, and Platform Core. Bricks renders their data through the active theme. The source-controlled ACF Local JSON definitions show the project's native-first approach. Platform Core owns the documented content_topic exception. The active Member Perks runtime and Fluent Snippets store contain code that differs from repository copies.

Fresh read-only configuration findings:

- Member Perks has hub display and runtime-status cron enabled. Woo discounts, initial subscription product discounts, course claims, ticket claims, Woo Blocks compatibility, and notifications are disabled. Its environment mode is set to production while this WordPress install is a local staging site; the effect of that setting is not established here.
- PMPro selects Paystack. Both PMPro gateway extensions, Paystack and PayFast, are active. PayFast is currently unselected.
- WooCommerce has its admin-only WC Order Test gateway enabled. The inspected COD and PayPal settings are disabled. This does not prove what an anonymous visitor sees at checkout or that any payment can complete.
- Fluent Snippets has nine published runtime entries. Only the Medewerker query snippet has a source-controlled counterpart in this repository. Its runtime index says Fluent Snippets 10.56 although the installed plugin header says 10.58.
- Platform Core has a finalized runtime deployment record for source commit 454346d. A fresh file and mode comparison matched all 63 runtime files to that record. This establishes runtime application and file parity, not current functional, commercial, business, or launch approval.
- The Member Perks source checkout is at 972a279, but its installed runtime copy has 59 changed files among 262 shared paths and 43 runtime-only files. The runtime version string matches the source version label, but does not establish source parity or deployment provenance.

No plugin is recommended for removal from this inventory. Several plugins have potential overlap or unclear current use. Any deactivation needs consumer mapping, replacement evidence, owner approval, and a reversible rollback path as described in Section 9.

Evidence labels used below:

- BOTH_CONFIRMED: source evidence and current runtime evidence agree.
- RUNTIME_CONFIRMED: observed in the LocalWP runtime.
- SOURCE_CONFIRMED: observed in the repository or plugin source.
- HISTORICAL_ONLY: dated evidence that may no longer describe current runtime state.
- INFERRED: supported by code or architecture but not confirmed through a live journey.
- UNKNOWN: not established by this read-only inspection.
- CONFLICT: current evidence differs across source, runtime, or project rules and needs an authorized resolution.

## 2. Inspection scope and evidence

### Scope

The coordination brief was read in full. The inventory covers every WordPress plugin recognized by the runtime, the active theme and parent theme, MU plugins, drop-in locations, relevant plugin dependencies, selected non-secret runtime settings, source-controlled ACF configuration, and the custom plugin and snippet source/runtime boundaries.

The repository was at main commit 454346d9e6a2c36864a4bbd944e52596f16e09c1, equal to origin/main. Live issue context was checked for VGN-55, VGN-50, and VGN-18. VGN-55 is the current foundation audit issue. VGN-50 records a prior authorized Platform Core deployment. VGN-18 has commerce observations dated 17 November that are HISTORICAL_ONLY for this 19 November inventory.

### Evidence collection

- Plugin names, slugs, versions, authors, descriptions, and declared requirements were read from each installed plugin's runtime header. Current active/inactive state was read from WordPress plugin metadata and the active-plugin option. The following read-only command returned 31 active regular plugin rows and one must-use plugin row, with no inactive rows.

    set -a; source '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/.envrc'; set +a; flock -s /tmp/vgnova-evaluation-read.lock wp --path="$PWD" --skip-plugins --skip-themes plugin list --format=csv --fields=name,status,version

  set -a; source '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/.envrc'; set +a; flock -s /tmp/vgnova-evaluation-read.lock wp --path="$PWD" --skip-plugins --skip-themes plugin list --format=csv --fields=name,status,version
- WordPress core read-only evaluation used WP-CLI with plugins and themes skipped, behind the shared read lock at /tmp/vgnova-evaluation-read.lock. LocalWP's assignment-only environment file was used without printing its contents. A conventional wp db query attempt was unavailable because the mysql client wrapper was absent; the core evaluation path then succeeded.
- The skipped-plugin evaluation still bootstraps MU plugins. The sole MU plugin was inspected statically before use. The inventory did not run its frontend hooks or trigger a request.
- Database reads were limited to selected Member Perks feature values, the selected PMPro gateway, Woo gateway enabled flags, and aggregate content counts. I did not query customer, member, attendee, or order records. The Woo settings arrays were projected to the enabled field; credential fields were not printed or copied. No SMTP credentials, tokens, or secret values appear in the collected output.
- File and version comparisons were read-only. No tests or security scans, checkout journey, email, payment, enrollment, ticket purchase, or frontend browser verification was run.
- Current runtime files were inspected at /home/jcschoeman96/Local Sites/voelgoed-novamira/app/public. Paths under wp-content below are relative to that root unless identified as repository paths.

### Evidence limits

Plugin activation proves installation and activation, not that a feature is configured or required. Code inspection establishes intended integration, not successful behavior. "Necessary" below is a current architectural assessment, not launch approval. I did not read password-bearing integrations or customer records. I did not check current public reachability or cloudflared ingress.

## 3. Component inventory

All plugin rows below have runtime state ACTIVE. The runtime reported zero inactive plugins. Plugin name, slug, entrypoint, author, version, and description come from the cited installed header.

| Plugin name, slug and entrypoint | Author, version, state | Purpose and data authority | Consumers, configuration, overlap and current need | Runtime header evidence |
|---|---|---|---|---|
| Admin and Site Enhancements (ASE); slug admin-site-enhancements; admin-site-enhancements/admin-site-enhancements.php | wpase.com; 9.3.3; ACTIVE | Admin and site utility toggles. No canonical Voelgoed business data authority. | Which ASE modules are enabled was not read. Its module list could overlap other admin/security utilities, but no active overlap was established. Current necessity UNKNOWN; retain pending settings and consumer review. | Plugin Name line 4, description 6, version 7, author 8. |
| Advanced Custom Fields PRO; slug advanced-custom-fields-pro; advanced-custom-fields-pro/acf.php | WP Engine; 6.8.1; ACTIVE | Owns the project's custom field and ACF-managed content model, subject to the explicit Platform Core content_topic exception and plugin-owned domain behavior described below. | Used by Bricks dynamic data, Platform Core's field adapters and Member Perks' configuration provider. Fifteen repository ACF JSON definitions are linked into the runtime theme and matched byte-for-byte. Required by the project's content model. | Name 9, description 11, version 12, author 13. |
| Automatic.css; slug automatic-css; automatic-css/automatic-css.php | Kevin Geary, Matteo Greco; 4.0.3; ACTIVE | Utility classes, CSS variables, and design tokens. No content authority. | Used by Bricks templates and theme styling. Exact token/settings configuration was not read. It is part of the declared frontend stack; current rendered use was not browser-verified. | Name 9, description 11, version 12, author 15. |
| Tickera Bridge for WooCommerce; slug bridge-for-woocommerce; bridge-for-woocommerce/bridge-for-woocommerce.php | Tickera; 1.8.0; ACTIVE | Connects Tickera ticket sales to WooCommerce. Tickera remains event and ticket authority; Woo remains checkout/order authority. | Integrates Tickera and WooCommerce. Plugin header has no Requires Plugins declaration. Current ticket product and order behavior was not tested. Keep for the declared sales path until consumers are verified. | Name 4, description 6, version 7, author 10. |
| Tickera CSV Export; slug csv-export; csv-export/index.php | Tickera.com; 1.3.7; ACTIVE | Exports Tickera attendee data to CSV; it is an operational export, not the attendee system of record. | Attendee export use and permissions were not inspected. It handles personal attendee data, which was not read. Optional operational necessity UNKNOWN; no removal case established. | Name 4, description 6, version 9, author 7. |
| Fluent Snippets; slug easy-code-manager; easy-code-manager/easy-code-manager.php | Fluent Snippets; 10.58; ACTIVE | Executes and manages site-specific PHP and JavaScript snippets stored as runtime files. Those snippets are live integration code, not a separate canonical content database. | Nine runtime entries are marked published; see Section 4. Eight lack a repository source counterpart. The active index version is 10.56. This plugin is the approved snippet manager, but its runtime contents need source provenance and consumer mapping before cleanup. | Name 3, description 5, author 6, version 11. |
| FluentCRM - Marketing Automation For WordPress; slug fluent-crm; fluent-crm/fluent-crm.php | WP Email Newsletter Team - FluentCRM; 3.3.1; ACTIVE | CRM contacts, lists, campaigns, and automation data. | Forms and membership/course journeys may feed CRM, but the current connection graph, automation list, and delivery state were not inspected. Contact data was not read. Current necessity UNKNOWN; keep pending journey mapping. | Name 4, description 6, version 7, author 10. |
| FluentAuth - Auth Security Plugin; slug fluent-security; fluent-security/fluent-security.php | Fluent Auth Team; 3.1.1; ACTIVE | Login security and authentication controls. WordPress remains the account authority. | Its security settings may overlap WordPress login controls, ASE options, and MCP access controls. I did not inspect settings or protected routes. Retain until the access configuration is mapped. | Name 5, description 7, version 8, author 9. |
| Fluent Forms; slug fluentform; fluentform/fluentform.php | Contact Form - WPManageNinja LLC; 6.2.17; ACTIVE | Form definitions and form submissions. Submissions may contain personal data. | FluentCRM integration is possible, but active forms, actions, and submission retention were not inspected. No submission data was read. Current form journey necessity UNKNOWN. | Name 5, description 6, version 7, author 8. |
| FluentSMTP; slug fluent-smtp; fluent-smtp/fluent-smtp.php | FluentSMTP & WPManageNinja Team; 2.4.5; ACTIVE | Outbound mail transport. It does not own the CRM or membership record. | Used by WordPress and plugin mailers if configured. Provider configuration and secrets were not read; no send test was run. Retain until the mail route is verified. | Name 3, description 5, version 6, author 7. |
| Frames; slug frames-plugin; frames-plugin/frames-plugin.php | Kevin Geary, Matteo Greco; 1.5.13; ACTIVE | Bricks-adjacent wireframing and component authoring system. No domain data authority. | Whether active site pages depend on Frames output was not checked. It may be an authoring aid rather than a runtime requirement. Keep until builder consumers and saved design data are mapped. | Name 9, description 11, version 12, author 15. |
| HappyFiles Pro; slug happyfiles-pro; happyfiles-pro/happyfiles-pro.php | Codeer; 1.9; ACTIVE | Media library organization and HappyFiles taxonomy/configuration. | The published snippet 6 is titled "HappyFiles ↔ content_category sync," creating an explicit dependency on the legacy content_category classification. Folder/taxonomy settings were not read. Do not remove while that snippet or editors depend on it. | Name 3, description 5, version 6, author 7. |
| JSM Show Post Metadata; slug jsm-show-post-meta; jsm-show-post-meta/jsm-show-post-meta.php | JS Morisset; 4.9.1; ACTIVE | Displays post metadata in the editor for debugging. It does not own the underlying metadata. | Admin diagnostic only. Whether staff currently use it is UNKNOWN. It is a removal candidate only after editor workflow review; no current consumer scan was made. | Name 3, description 12, version 16, author 8. |
| Masteriyo LMS; slug learning-management-system; learning-management-system/lms.php | Masteriyo; 3.5.1; ACTIVE | Course, lesson, enrollment, and progress authority. | WooCommerce products may provide course purchase; the MU plugin filters linked products from public catalogs and redirects product singles to the course. Member Perks can integrate through a disabled course-claim flag. Six courses and three lessons are currently published. Required for the declared LMS journey. | Name 3, description 5, version 8, author 6. |
| MCP Adapter; slug mcp-adapter; mcp-adapter/mcp-adapter.php | WordPress.org Contributors; 0.7.1; ACTIVE | Exposes WordPress Abilities API functions as MCP tools, resources, or prompts. It does not own domain data. | It may expose some of the same WordPress tools as Novamira and Novamira Pro. I did not inspect enabled abilities, endpoints, or clients. Retain until the client and ability map is known. | Name 11, description 13, version 14, author 17. |
| Novamira; slug novamira; novamira/novamira.php | Dynamic.ooo; 1.12.15; ACTIVE | WordPress MCP server with PHP execution and filesystem operations, documented by its header for development/staging use. It does not own content. | Project runtime instructions specify the Bricks MCP endpoint for Bricks work and Novamira for other WordPress runtime work. Endpoint binding, ability permissions, and network ingress were not verified. It is required for the stated workflow; exposure remains UNKNOWN. | Name 9, description 11, version 12, author 15. |
| Novamira Pro; slug novamira-pro; novamira-pro/novamira-pro.php | Dynamic.ooo; 1.10.0; ACTIVE | Adds ready-made WordPress abilities and skills for agents. No domain data authority. | Extends the Novamira/WordPress abilities. Exact enabled abilities and endpoint consumers were not checked. Potentially overlaps MCP Adapter as an access interface; do not remove without a client map. | Name 11, description 13, version 14, author 17. |
| Paid Memberships Pro; slug paid-memberships-pro; paid-memberships-pro/paid-memberships-pro.php | Paid Memberships Pro; 3.9.1; ACTIVE | Membership levels, membership status, and gated access authority. | Consumed by Member Perks, PMPro gateway extensions, the PMPro WooCommerce add-on, and potentially Platform Core membership adapters. Selected PMPro gateway is Paystack. Membership levels and access journeys were not tested. Required by the declared membership model. | Name 3, description 5, version 6, author 7. |
| Paid Memberships Pro - PayFast Gateway; slug pmpro-payfast; pmpro-payfast/pmpro-payfast.php | Paid Memberships Pro; 1.8; ACTIVE | Adds PayFast as a PMPro payment gateway. | PMPro currently selects Paystack, so PayFast is installed but unselected in the observed config. A fallback, existing order, or recurring-use dependency was not checked. Candidate for later deactivation review only; safeguards are in Section 9. | Name 3, description 5, version 6, author 7. |
| Paid Memberships Pro - Roles Add On; slug pmpro-roles; pmpro-roles/pmpro-roles.php | Paid Memberships Pro; 1.5.4; ACTIVE | Maps PMPro membership levels to WordPress roles. | Role mappings and capability consumers were not inspected. Role-based pricing and site access can also consume WordPress roles, but this is not equivalent to PMPro membership authority. Retain until access and pricing dependencies are mapped. | Name 3, description 4, version 8, author 6. |
| Paid Memberships Pro - WooCommerce Add On; slug pmpro-woocommerce; pmpro-woocommerce/pmpro-woocommerce.php | Paid Memberships Pro; 1.11; ACTIVE | Integrates WooCommerce product/order events with PMPro membership grants. | Connects Woo product sales to PMPro access; it does not replace Woo checkout or PMPro access. WooCommerce Subscriptions handles recurring billing separately. Current product-to-level mappings and order behavior were not verified. Retain for the declared Woo membership sales path. | Name 3, description 5, version 6, author 9. |
| Paystack Gateway for Paid Memberships Pro; slug paystack-gateway-paid-memberships-pro; paystack-gateway-paid-memberships-pro/paystack-gateway-paid-memberships-pro.php | Paid Memberships Pro, Paystack; 1.9.2; ACTIVE | Adds Paystack gateway support to PMPro. | Paystack is the selected PMPro gateway. No credentials were read and no transaction was attempted. Current selection makes this a configured integration, not proof of successful payment. | Name 3, description 5, version 6, author 7. |
| Role based prices for Tickera; slug role-based-prices; role-based-prices/roled-based-prices.php | Tickera.com; 1.4.4; ACTIVE | Applies different Tickera ticket prices by WordPress role. | Could interact with PMPro Roles and Member Perks ticket benefits. Member Perks ticket claims are currently disabled, and the role-price rules were not inspected. Keep until live ticket pricing consumers are known. | Name 4, description 6, version 9, author 7. |
| Tickera Seating Charts; slug seating-charts; seating-charts/seating-charts.php | Tickera.com; 1.2.2; ACTIVE | Defines event seat charts and assignments. Tickera remains the event/ticket authority. | Seat-map configuration and event consumers were not inspected. Its current necessity depends on the event inventory. Keep until event template and event setup owners confirm usage. | Name 4, description 6, version 9, author 7. |
| Tickera Terms & Conditions; slug terms-and-conditions; terms-and-conditions/index.php | Tickera.com; 1.3.5; ACTIVE | Adds purchase terms acceptance to Tickera checkout. | The terms content and checkout integration were not inspected. It may be a required ticket-sale control; do not remove until the actual ticket journey is reviewed. | Name 4, description 6, version 9, author 7. |
| Tickera – Sell Tickets & Manage Events (Premium); slug tickera; tickera/tickera.php | Tickera.com; 3.6.0.9; ACTIVE | Event registration, ticket inventory, attendees, tickets, and check-in authority. | Consumed by Bricks event presentation, Tickera add-ons, the Woo bridge, and optional Member Perks ticket claims. Seventeen tc_events are currently published. Required for the declared live event model. | Name 4, description 6, version 9, author 7. |
| Voelgoed Member Perks; slug voelgoed-member-perks; voelgoed-member-perks/voelgoed-member-perks.php | Voelgoed; 0.18.0; ACTIVE | Owns perk definitions, benefit targeting, eligibility, claims, redemptions, and associated status and reporting functions. It reads PMPro, Woo, Masteriyo, Tickera, and ACF through adapters. | Current flags and source/runtime divergence are detailed in Sections 4 and 6. Fifteen perks are published and five are drafts. It is a distinct entitlement/benefit layer, not a replacement for PMPro, Woo, Masteriyo, or Tickera. | Name 3, description 4, version 5, author 6. |
| Voelgoed Platform Core; slug voelgoed-platform-core; voelgoed-platform-core/voelgoed-platform-core.php | Author header absent; 0.1.0; ACTIVE | Owns the canonical content_topic taxonomy and source-controlled domain adapters for people, event participation, product contribution, journey intent, and service integration. | Source commit and runtime file parity are confirmed in Section 6. It consumes ACF-backed definitions and provides Bricks/runtime adapters; it does not replace the owning plugins. Required for the declared platform architecture. | Description 6, version 7; author header absent. |
| WC Order Test - WP Fix It; slug woo-order-test; woo-order-test/wc-ot.php | WP Fix It; 4.2; ACTIVE | Adds an admin-only WooCommerce test payment gateway. | Its Woo settings say enabled=yes. It is useful for staging checkout diagnostics, not a production payment provider. Keep while checkout QA depends on it; verify it is not exposed to non-admins in a browser before any commerce acceptance. Header declares WooCommerce dependency. | Name 3, description 5, author 6, version 8, Requires Plugins 11. |
| WooCommerce; slug woocommerce; woocommerce/woocommerce.php | Automattic; 11.3.0; ACTIVE | Product catalog, cart, checkout, orders, and commerce state authority. | Consumed by Subscriptions, the PMPro Woo add-on, Tickera bridge, Masteriyo product links, Member Perks discounts, and the test gateway. Eight products and 14 product variations are published. Only the admin test gateway was observed enabled among the inspected gateway settings; storefront behavior was not tested. | Name 3, description 5, version 6, author 7. |
| WooCommerce Subscriptions; slug woocommerce-subscriptions; woocommerce-subscriptions/woocommerce-subscriptions.php | WooCommerce; 9.4.1; ACTIVE | Recurring billing schedules and subscription lifecycle for Woo products. | It requires WooCommerce in its plugin header. PMPro owns membership access; Subscriptions owns recurring billing. Existing subscription records, renewal jobs, and product mappings were not inspected. Required if the declared recurring membership/product journeys use it. | Name 3, description 5, author 6, version 8, Requires Plugins 9. |

### Theme, MU plugin, and drop-ins

| Component | Runtime state and purpose | Evidence and limit |
|---|---|---|
| Bricks | Parent theme, version 2.4.5, author Bricks. WordPress reports template=bricks and stylesheet=bricks-child. Bricks is a theme, not a plugin. | wp-content/themes/bricks/style.css lines 2-11; read-only WordPress theme options. |
| Bricks Child Theme | Active child theme, version 1.1, author Bricks, Template=bricks. | wp-content/themes/bricks-child/style.css lines 2-9; read-only WordPress theme options. |
| Voelgoed Masteriyo Course Products | One MU plugin, version 1.0.0, author header absent. It hides Masteriyo-linked products from public Woo catalogs and redirects a linked product single to the published course URL. | wp-content/mu-plugins/vg-masteriyo-course-products.php lines 3-5, 18-49, 54-116, 130-149. It reads Masteriyo/Woo product links during frontend requests. It has no source-controlled code counterpart found in this repository search; the runtime manifest records its hash. |
| WordPress drop-ins | None found. wp-content root contains only the standard index.php guard. No advanced-cache.php, object-cache.php, db.php, or sunrise.php is installed. | Read-only wp-content root listing and drop-in filename scan. This does not establish whether a separate external object-cache service exists. |

The project stack lists ClassicMonks as a Bricks add-on at AGENTS.md lines 802 and 880. No installed plugin header or runtime plugin row matches ClassicMonks. Whether this is stale documentation, a renamed package, or a required missing add-on is UNKNOWN. No Bricks page consumer check was run.

## 4. Current architecture and configuration

### Plugin activation and declared dependencies

WordPress reports 31 plugin folders with valid plugin headers, 31 active, and zero inactive. Bricks is the selected parent theme, not a plugin. The only explicit Requires Plugins declarations in installed plugin headers are:

| Plugin | Declared requirement | Evidence |
|---|---|---|
| WooCommerce Subscriptions 9.4.1 | WooCommerce | Runtime header, Requires Plugins line 9. |
| WC Order Test - WP Fix It 4.2 | WooCommerce | Runtime header, Requires Plugins line 11. |
| Other 29 plugins | No Requires Plugins header declaration found. This does not mean there are no code-level dependencies. | Installed plugin headers scanned. |

Code-level relationships inferred from plugin purpose and repository architecture:

- PMPro gateway extensions and PMPro add-ons integrate with PMPro. The PMPro WooCommerce add-on also integrates with WooCommerce.
- Tickera bridge integrates Tickera with WooCommerce. Tickera add-ons integrate with Tickera.
- Member Perks has optional adapters for ACF, PMPro, WooCommerce, Masteriyo, and Tickera. Runtime flags gate most transaction paths.
- The MU plugin connects Masteriyo course records and Woo products and changes public catalog/single-page behavior.
- The Bricks Child Theme declares Bricks as its parent.
- MCP Adapter, Novamira, and Novamira Pro all expose or extend WordPress tools for agents. I did not read the configured client/endpoint map, so active overlap is possible but not established.

### Current Member Perks settings

The values below came from whitelisted options reads, not from loading the Member Perks plugin:

| Runtime field | Current value | Consumer / limit |
|---|---:|---|
| vgmp_enable_hub_display | 1 | Hub display path enabled by configuration. Actual page behavior not tested. |
| vgmp_enable_runtime_status_cron | 1 | Runtime status scheduling enabled by configuration. Scheduled execution not observed. |
| vgmp_enable_woo_discounts | 0 | Woo discount execution disabled. |
| vgmp_allow_initial_subscription_product_discounts | 0 | Initial subscription product discount path disabled. |
| vgmp_enable_course_claims | 0 | Masteriyo claim/enrolment path disabled. |
| vgmp_enable_ticket_claims | 0 | Tickera ticket claim path disabled. |
| vgmp_enable_woo_blocks_compat | 0 | Woo Blocks compatibility path disabled. |
| vgmp_enable_notifications | 0 | Member Perks notification feature disabled. |
| vgmp_environment_mode | production | Does not match the LocalWP site's staging role by label. How this mode affects behavior was not verified. |

The source plugin defines these feature gates in voelgoed-member-perks/includes/Config/SettingsRepository.php lines 29-43 and 93-126. The source JSON definitions live in voelgoed-member-perks/acf-json/group_vgmp_options.json and group_vgmp_perk_details.json. The runtime copies of both files match the repository copies. Current perk record counts are 15 published and 5 draft, from aggregate wp_posts counts; no perk benefits, eligibility rules, member records, or redemption rows were read.

### PMPro and Woo payment configuration

- PMPro's current selected gateway option is paystack. Both the Paystack gateway plugin and PayFast gateway plugin are active. Gateway secrets were not read, and no payment was attempted.
- WooCommerce settings read by enabled flag only report wpfi_woo_order_test as enabled, COD disabled, and PayPal disabled. BACS and cheque settings were absent. The Woo test plugin describes itself as admin-only. Checkout visibility and real payment completion remain unverified.
- The PMPro selection and Woo gateway flags are configuration observations only. They do not prove that a user can complete checkout, that gateway credentials are valid, that billing is in test mode, or that an order grants the expected membership.
- The 17 November VGN-18 note records a checkout attempt with no payment methods displayed. That observation is HISTORICAL_ONLY. The current enabled test-gateway setting differs from that old summary, and the shopper-facing checkout was not rechecked.

### Fluent Snippets runtime configuration

The installed Fluent Snippets plugin is 10.58. The runtime storage directory is wp-content/fluent-snippet-storage. Its index lists nine entries as published with run_at=all for PHP snippets and wp_footer for the product card JavaScript. The runtime index metadata says cached_version=10.56 and force_disabled=no. The plugin's CodeRunner reads the index's published list and skips listed error files; the error-file list was not separately evaluated here. Because the cached index version differs from the installed plugin version, a plugin-managed index refresh should be verified before editing snippets. The observation alone does not prove snippets failed to run.

Current runtime entries:

| Runtime file | Indexed title | Type and configured run point | Current source/consumer evidence |
|---|---|---|---|
| 1-vg-product-card.php | [VG] Product Card — WooCommerce hooks | JavaScript; wp_footer | Woo card behavior; current template caller not inspected. |
| 10-vg-bricks-medewerker-queries.php | [Bricks] Medewerker — Query loop support | PHP; all | Source exists at site-code/fluent-snippets/10-vg-bricks-medewerker-queries.php. The runtime adds plugin metadata and an ABSPATH guard; its diff shows no body change. |
| 2-vg-article-dynamic-tags.php | [Bricks] Article — featured content dynamic tags | PHP; all | Article dynamic tags; current Bricks consumers not freshly scanned. |
| 3-shortcode-voelgoed-vennoot-block.php | [Shortcode] Voelgoed Vennoot Block | PHP; all | Shortcode output; current page callers not freshly scanned. |
| 4-taxonomy-posts-hide.php | [Taxonomy] Posts — Hide native Categories | PHP; all | Alters post-category administration/presentation; current editor consumers not freshly scanned. |
| 6-taxonomy-posts-happyfiles.php | [Taxonomy] Posts — HappyFiles ↔ content_category sync | PHP; all | Connects HappyFiles and legacy content_category. The legacy register still lists content_category consumers. |
| 7-maintenance-action-scheduler.php | [Maintenance] Action Scheduler — 7-day retention | PHP; all | Cleanup policy; retention rules and effect on scheduled actions not tested. |
| 9-acf-product-copy.php | [ACF] Product — Copy event medewerkers | PHP; all | Copies event_medewerkers into an empty product relationship, according to current architecture notes. A template helper still consumes legacy product people data. |
| 9-bricks-tickera-event-date-time.php | [Bricks] Tickera Event Date/Time Tokens | PHP; all | Tickera event date/time dynamic tokens; current Bricks consumers not freshly scanned. |

All nine are configured as published in the runtime index. Runtime publication means the code is eligible to execute when the plugin loads the index, not that each consumer was exercised. Only the file 10 source and sidecar metadata are present under site-code/fluent-snippets. The other eight runtime snippets have no source-controlled counterpart found in this repository scan.

### Aggregate current runtime content counts

These are aggregate post counts only, read from wp_posts without content or user records:

| Type | Current counts |
|---|---|
| Masteriyo mto-course | 6 published, 1 trash |
| Masteriyo mto-lesson | 3 published |
| WooCommerce product | 8 published, 7 draft, 5 pending, 1 private, 3 trash |
| WooCommerce product_variation | 14 published |
| Tickera tc_events | 17 published |
| Person | 1 published |
| Member Perks vg_perk | 15 published, 5 draft |
| event_participation and product_contribution | No post rows returned in the queried statuses. |

These counts establish runtime records, not accurate content, relationships, ownership, eligibility, or successful user journeys. WooCommerce Subscriptions orders and PMPro membership records were not counted.

## 5. How it works

The current architecture has distinct authorities:

1. ACF stores editorial custom fields and most project-defined post types and taxonomies through Local JSON. Its 15 source definitions cover article people, course people, editorial classification, event participation, Person, Person related content, product contribution, and associated taxonomies. Bricks reads this data for rendering. The runtime ACF JSON directory is a symlink to a detached source checkout; all 15 linked files matched repository hashes.
2. Platform Core owns content_topic as the explicitly documented taxonomy exception. It also supplies source-controlled adapters and domain lifecycle code for Person, event participation, product contribution, Journey Intent, and service integration. It does not replace Woo, PMPro, Masteriyo, Tickera, ACF, or Bricks.
3. WooCommerce owns products, cart, checkout, and orders. WooCommerce Subscriptions owns recurring billing. The PMPro WooCommerce add-on maps Woo commerce events to membership grants; PMPro remains the membership/access authority.
4. Masteriyo owns courses, lessons, and learning progress. The MU plugin excludes linked course products from Woo catalog loops and redirects linked product singles to the published course URL.
5. Tickera owns event registration, ticket inventory, attendee tickets, and check-in. Its Woo bridge connects ticket sales to WooCommerce orders. Tickera add-ons extend attendee export, role pricing, seating, and purchase terms.
6. Member Perks owns perk configuration and the lifecycle of eligibility, claims, redemptions, and reporting. It consumes PMPro membership state and can call Woo, Masteriyo, or Tickera adapters. Current runtime flags enable hub display and status cron but keep commerce/course/ticket execution paths disabled.
7. FluentCRM owns CRM records and automation; Fluent Forms owns form definitions/submissions; FluentSMTP transports mail. Their current links and delivery configuration are not verified.
8. Fluent Snippets executes integration and presentation glue. Its nine runtime snippets include Bricks data helpers, legacy taxonomy synchronization, Woo product-card behavior, and scheduled-action cleanup.
9. Novamira and related adapters provide the declared local MCP workflow. I did not inspect their enabled abilities or ingress configuration.

The separation above is SOURCE_CONFIRMED by the project architecture and plugin source. The successful integration of these journeys is not established by this inventory.

## 6. Source versus runtime

### Repository baseline

The vgnova checkout and origin/main were both at 454346d9e6a2c36864a4bbd944e52596f16e09c1. The Platform Core source module is site-code/voelgoed-platform-core. The runtime plugin folder is a deployed assembly copy.

### Platform Core: BOTH_CONFIRMED

The read-only deployment record at /home/jcschoeman96/Local Sites/voelgoed-novamira/app/.vgnova-platform-core-deployments/history/2de8147a38934ad69d0808867dc38e6a.json is finalized and records source commit 454346d9e6a2c36864a4bbd944e52596f16e09c1, 63 target manifest records, and a runtime parity tree hash of 91775a8c85f3a756b58f2a650db2ad010eb605577512d8d218731a1ae0890276. A fresh read-only comparison matched all 63 runtime file paths, SHA-256 values, and modes; there were zero missing, extra, or mismatched files. The record logs an HTTP 200 during its 17 November health check. That is a dated deployment result, not a new behavior test.

Status: SOURCE_MERGED is supported by the current main baseline; RUNTIME_APPLIED is supported by the finalized record and parity comparison; BEHAVIOR_VERIFIED is not established by this inventory; BUSINESS_APPROVED and LAUNCH_READY are not established.

The runtime plugin entrypoint description says "Unified authenticated Voelgoed customer dashboard integrations." That description is narrower than its actual assembled code. Code authority is established from the source module and deployment manifest, not the short plugin header.

### Member Perks: CONFLICT

The nested source repository voelgoed-member-perks is clean at commit 972a279be7a753594888e974132562e1cc099e24. Its plugin changelog labels 0.18.0 unreleased. The installed runtime header also reports 0.18.0, but a same-version label does not prove parity.

The exact source checks were git -C voelgoed-member-perks rev-parse HEAD and git -C voelgoed-member-perks status --short. The first returned 972a279be7a753594888e974132562e1cc099e24; the second returned no entries. A Python read-only comparison of relative file paths and SHA-256 values produced the counts below.

A read-only full-tree comparison found:

- Source: 360 files; runtime: 305 files.
- Shared paths: 262; of those, 59 differ by content.
- Source-only paths: 98; runtime-only paths: 43.

The drift includes runtime-only PHP classes for ACF option submission, claim availability, source-change tokens, and other integration/reporting behavior. This exceeds a packaging-only or header-only difference. The exact approved runtime source branch or artifact was not established. Nested plugin instructions identify the plugin repository as code authority and the runtime copy as disposable. Reconcile provenance before using the source checkout as evidence for all active runtime behavior.

Current disposition: source commit exists; exact runtime application from that commit is UNKNOWN; behavioral verification is outside this inventory; business and launch approvals are UNKNOWN.

### ACF definitions: BOTH_CONFIRMED

The active child theme's acf-json path resolves to /home/jcschoeman96/projects/runtime/vgnova-acf-sources/c450475971de722378a1658157a3a24838e467f7/site-code/acf-json at detached source commit c450475971de722378a1658157a3a24838e467f7. The directory contains the same 15 JSON definitions as repository site-code/acf-json; all hashes matched. The two Member Perks ACF JSON groups also match voelgoed-member-perks/acf-json. This confirms these configuration files are available and in parity, not that all intended ACF data or page bindings are correct.

### Fluent Snippets: CONFLICT

The repository contains only the source and sidecar metadata for 10-vg-bricks-medewerker-queries.php. Its SHA-256 is 8ae19709e1a6979f1d58b4fa30165c77fa677d10139d1f4d2afc3e8c84ceebac; the runtime file SHA-256 is 7125fa0e4b0acfba24e9731d4f6edb2c1ea0de4ba5fce9254a9ec66d4d613831. The diff consists of Fluent Snippets' runtime metadata block and ABSPATH guard before the source body; no body change appeared in the diff. The other eight published snippets have no source-controlled counterpart in this repository scan.

The runtime index is generated by Fluent Snippets and says cached_version 10.56 while the installed plugin header says 10.58. Runtime metadata values include a secret key; its value was not printed or copied. The cached version and missing source copies create a source-provenance and safe rollback gap for code that can execute on requests.

### Other runtime-only code

The MU plugin file is present in the WordPress runtime. No matching source file was found in the repository; existing product implementation notes record a runtime hash only. No source-to-runtime provenance for this file is established here.

### Other vendor plugins and theme

The runtime copies of Bricks, Bricks Child Theme, and the 29 third-party plugins are installed software, not source-controlled components of this repository. Their runtime headers establish installed versions; no package signatures, vendor checksums, or upgrade provenance were reviewed.

## 7. Completeness and functionality

### Inventory coverage

| Acceptance criterion | Result |
|---|---|
| Read the coordination brief and follow its read-only boundary | VERIFIED. No runtime writes or customer/credential reads were made. |
| Inventory every installed active and inactive plugin with metadata | VERIFIED for WordPress-recognized plugin entries: 31 installed, 31 active, 0 inactive; each row gives name, slug, author, version, state, purpose, and header evidence. |
| Inspect MU plugins and drop-ins | VERIFIED for the LocalWP wp-content filesystem: one MU plugin, no WordPress PHP drop-ins. |
| Record relevant dependencies and current configuration | PARTIALLY VERIFIED. Header declarations, high-impact safe flags, PMPro gateway, and Woo enabled flags are recorded. Other plugin settings and secret-bearing configuration remain explicitly unknown. |
| Distinguish current configuration from functional and launch proof | VERIFIED. No plugin journey, browser checkout, payment, email, enrollment, ticket purchase, or production readiness is claimed. |
| Write only the requested inventory artifact | VERIFIED after final status review. Existing untracked repository items were preserved. |

### Delivery-stage distinctions

| Stage | Assessment |
|---|---|
| SOURCE_MERGED | Current vgnova main baseline is confirmed. Member Perks nested source is at a clean commit, but the exact runtime artifact is not traced to it. |
| RUNTIME_APPLIED | Platform Core only: confirmed against the finalized 17 November deployment record and a fresh 63-file parity check. Other active plugins are installed; that does not prove their intended configuration is applied. |
| BEHAVIOR_VERIFIED | Not established by this inventory. Dated Platform Core health evidence is not a substitute for all current domain behaviors. |
| BUSINESS_APPROVED | Not established. Active plugins or successful technical checks do not authorize business rules or payments. |
| LAUNCH_READY | Not established. The runtime remains a staging installation and the critical payment, email, access, content, and journey gates were not tested here. |

The inventory is complete for installed-plugin identity and state. It is not a complete functional audit of each plugin, each setting, all public templates, all external service configuration, or customer-facing journeys.

## 8. Problems, risks and contradictions

All severities below are P2 unless stated otherwise. No P0 or P1 issue was established by this read-only inventory.

| ID / evidence | Finding and effect | Confidence |
|---|---|---|
| P2, CONFLICT: Member Perks source/runtime tree comparison | Same version label hides 59 changed shared files and 43 runtime-only files. The runtime's exact approved source artifact is unknown. Source-based review can miss behavior running on the site. | High for file drift; UNKNOWN for approval/provenance. |
| P2, CONFLICT: Fluent Snippets storage and source scan | Nine runtime snippets are published, but only one has a repository counterpart. The plugin index is versioned 10.56 while the plugin is 10.58. Custom PHP/JS lacks a complete source-controlled rollback set. | High for observed file/index mismatch. |
| P2, CONFLICT: Member Perks setting vgmp_environment_mode=production | The setting label conflicts with the site's documented LocalWP staging role. The exact effect of the setting is not established; a misleading mode can affect operator interpretation. | High for values; UNKNOWN for behavioral effect. |
| P2, RUNTIME_CONFIRMED: Woo gateway settings | The admin-only test gateway is enabled; inspected COD and PayPal methods are disabled. No real Woo payment provider was confirmed enabled in the inspected settings. A customer checkout path may be unavailable, but public checkout was not tested. | High for options; INFERRED for visitor impact. |
| P2, CONFLICT: Member Perks CPT registration vs project ACF rule | Member Perks source registers vg_perk with register_post_type in PHP at voelgoed-member-perks/includes/Content/PerkPostType.php lines 14-20. Repository policy says ACF handles CPT registration and names only content_topic as the explicit exception at AGENTS.md lines 842-845 and 820-822. The plugin's domain ownership does not document an approved registration exception. | High for both code and policy text; approval status UNKNOWN. |
| P2, UNKNOWN: MCP tools and ingress | MCP Adapter, Novamira, and Novamira Pro are active, but enabled abilities, endpoint binding, client permissions, and current tunnel ingress were not verified. Do not infer external exposure or public traffic from plugin activation. | High for plugin activation; UNKNOWN for exposure. |
| P2, HISTORICAL_ONLY: VGN-18 checkout note | The 17 November issue note differs from the current test-gateway option. Using it as current payment state would mislead the commerce inventory. | High for dated note; current settings independently read. |
| P2, CONFLICT: ClassicMonks stack declaration vs runtime | AGENTS.md lists ClassicMonks at lines 802 and 880, but the current 31-plugin runtime list has no matching entry. Feature loss is not established because Bricks consumers were not checked. | High for absence from plugin list; UNKNOWN for need. |
| P2, UNKNOWN: add-on usage | Tickera seating, terms, CSV export and role pricing, PMPro Roles, ASE modules, Frames, and JSM settings have active plugin states but no verified consumer map. Their activation does not establish current necessity or redundancy. | High for state; UNKNOWN for consumption. |

The Member Perks custom CPT vg_perk is not changed or re-registered by this document. Resolve the ACF policy and plugin-domain exception through the project's architecture authority before an implementation change.

## 9. Redundancy candidates

No removal or deactivation is recommended now. The following candidates need additional evidence. Each candidate has a rollback path and owner gate.

### PMPro PayFast Gateway

- Observed overlap: PayFast and Paystack gateway extensions are both active, while PMPro currently selects Paystack.
- Consumers to map: PMPro gateway selection, any environment-specific fallback, existing membership orders or recurring billing that names PayFast, and any business procedure that relies on it.
- Potential replacement: Paystack is the currently selected PMPro gateway. This does not prove a valid Paystack transaction or make it a proven substitute.
- Retention: Keep PayFast active until the commerce owner confirms no existing order, recurring path, fallback environment, or test process depends on it.
- Prerequisites: Review non-secret gateway state and relevant existing payment-method references without exposing credentials; test the chosen Paystack path in an approved non-live environment; obtain explicit owner approval.
- Rollback: Preserve the exact 1.8 package and its previous activation and PMPro selection state. If a later approved deactivation fails, restore the package and activation/configuration without copying credential values into logs or chat.
- Owner gate: Commerce/payment owner and project owner. This inventory does not authorize deactivation.

### WC Order Test - WP Fix It

- Observed overlap: It is an active, enabled admin-only test gateway. Its role is checkout diagnostics, not production payment.
- Consumers to map: Staging checkout QA, admin-only test orders, and any automated order test procedure.
- Potential replacement: An approved provider's sandbox/test mode may replace it after that pathway is available and demonstrated. No such replacement was verified.
- Retention: Keep it for staging QA, while confirming the admin-only visibility boundary in a browser.
- Prerequisites: Successful approved checkout test with the replacement path, documented test-order cleanup/retention policy, and owner approval.
- Rollback: Preserve the exact 4.2 package and enabled setting so it can be restored for staging tests.
- Owner gate: Commerce/test owner and project owner. No production use is recommended.

### MCP Adapter

- Observed overlap: MCP Adapter and the Novamira/Novamira Pro stack can each expose WordPress capabilities. I did not inspect the actual endpoints or clients.
- Consumers to map: Bricks MCP, Novamira MCP, connected clients, registered abilities, and any external orchestration or automation.
- Potential replacement: No replacement established. The components may be complementary.
- Retention: Keep while current Bricks and Novamira workflows depend on the local MCP stack.
- Prerequisites: Read-only endpoint and permission map; confirm no separate client uses the adapter; verify ingress before any scope decision.
- Rollback: Preserve its current package/version and activation state; re-enable it if a verified client fails after an owner-approved change.
- Owner gate: Platform/integration owner and project owner.

### Unmapped optional utilities and Tickera add-ons

ASE, Frames, JSM Show Post Metadata, Tickera CSV Export, Seating Charts, Terms & Conditions, Role based prices for Tickera, and PMPro Roles have no current consumer evidence in this inventory. No deletion recommendation follows from that absence. For each plugin, identify its admin, template, event, pricing, role, or export consumers; document a replacement if one is needed; retain it until those consumers are verified; obtain owner approval; and preserve the installed version and setting state for rollback. No specific plugin can be declared redundant until that review exists.

WooCommerce Subscriptions and PMPro are not duplicates: Subscriptions owns recurring billing and PMPro owns membership access. Tickera and WooCommerce are not duplicates: Tickera owns event/ticketing data and Woo owns the order/checkout layer. Masteriyo and the course-product MU plugin are not duplicates: Masteriyo owns courses while the MU plugin adapts the Woo catalog and product URL behavior.

## 10. Recommendations

1. Keep this inventory as a read-only baseline. Do not remove plugins based on active state or unverified consumer lists.
2. Reconcile the Member Perks runtime against an explicitly approved source commit or artifact. Record the expected runtime manifest and the owner decision before editing or replacing runtime files.
3. Bring all nine Fluent Snippets entries and the MU plugin into the approved source-control/deployment path, or document the exception and exact rollback source. Check the Fluent Snippets index refresh process before editing published snippets.
4. Resolve whether vg_perk is an explicitly approved custom-plugin CPT exception to the ACF-managed CPT rule. Keep current runtime data intact until the architecture authority decides.
5. Continue the commerce task by verifying anonymous checkout visibility, test gateway access restrictions, gateway sandbox readiness, and the Woo-to-PMPro membership grant path. Do not use live funds or treat the admin test gateway as a payment processor.
6. Verify PMPro-to-Woo membership mappings and WCS recurring product usage without exposing customer/payment data. Confirm whether PayFast has any stored transaction or fallback consumer before considering it redundant.
7. Map current Tickera add-on consumers and role-price behavior against Member Perks ticket features. The Member Perks ticket claims flag is currently off.
8. Map Fluent Forms to FluentCRM and FluentSMTP, and verify the delivery path without exposing credentials or contact records.
9. Verify the enabled MCP abilities, local endpoint binding, and current ingress before making network exposure or security claims.
10. Confirm whether ClassicMonks is still required, renamed, or absent from the current stack. Do not install or replace it without an approved dependency decision.
11. Treat each change as a separate approved implementation task. This inventory authorizes no runtime mutation, plugin deactivation, data migration, launch decision, or public-access claim.

## 11. Outstanding verification

- Anonymous and authenticated checkout rendering, payment method availability, test-gateway access restrictions, and successful WooCommerce order creation.
- PMPro level mappings, Woo product grants, PMPro gateway sandbox state, PayStack transaction behavior, PayFast historical consumers, and recurring renewal behavior in WooCommerce Subscriptions.
- Masteriyo purchase-to-enrollment and course access behavior, as well as course-product catalog exclusion and redirect behavior in the browser.
- Tickera registration, ticket sales, attendee email, seating, terms acceptance, CSV export permission, role pricing, and check-in journeys.
- Member Perks runtime source approval, current table presence, feature-flag consumers, membership resolution, discounts, claims, redemption lifecycle, and schedule behavior.
- Bricks template/loop consumers for all nine snippets, the full error-file state in Fluent Snippets, and safe regeneration of its 10.58 index.
- ASE module state; ACSS/Frames settings; FluentAuth security settings; Fluent Forms form actions; FluentCRM contacts/automations; FluentSMTP provider readiness; HappyFiles taxonomy setup; JSM editor usage; Tickera add-on settings; Novamira/MCP endpoint and ability policy.
- Whether the project-declared ClassicMonks package is intentionally absent or a required Bricks dependency.
- Current external network ingress. The LocalWP staging description alone does not prove local-only routing; no ingress or traffic records were inspected.
- Whether any Woo subscription or PMPro membership order currently depends on an installed gateway. Customer/order data was excluded from this inventory.
- The exact approved source/build provenance of the runtime-only MU plugin and eight Fluent Snippets entries.
- Any current behavior for these plugins. No test, browser, security, or launch verification was performed.

## 12. Final domain assessment

The source assigns each domain to a named owner. ACF owns editable custom fields and configured post models; Bricks renders; WooCommerce owns commerce; WooCommerce Subscriptions owns recurring billing; PMPro owns membership access; Masteriyo owns course delivery; Tickera owns events and ticketing; Member Perks owns benefits and redemption; Platform Core owns the canonical content topic taxonomy and its declared adapters. FluentCRM, Fluent Forms, and FluentSMTP provide messaging components.

The main gaps are operational provenance and completion evidence. Platform Core has current source/runtime parity, but this inventory does not prove its full behavior. Member Perks runtime behavior cannot be represented by its current source checkout without resolving file drift. Most Fluent Snippets code and the course-product MU plugin have no repository code counterpart. The checkout/payment state is not ready for a business claim: PMPro selects Paystack, but gateway success is unverified; Woo's enabled gateway is an admin-only test tool; no real Woo gateway or public checkout behavior was verified.

This document records inventory evidence only. It does not authorize plugin removal, runtime changes, migration, payment processing, business policy, or launch.

Task / Issue: VGN-55 plugin inventory. Expected outcome: write docs/evaluation/02_PLUGIN_INVENTORY_v0.1.0.md with the installed plugin and integration inventory.
Task type: RESEARCH / DOCUMENTATION
Assessment role: Research author
Acceptance criteria: 6/6 assessed in Section 7; 5/6 verified, 1/6 partially verified; no failed criteria. Detailed settings and behavior remain explicitly unknown where noted.
Implementation Score: N/A
Task Execution Score: 8/10. Deductions reflect uninspected settings for several plugins and no current browser or functional verification.
Confidence Score: 92%. High confidence in installed identity/state, selected safe settings, and source/runtime comparisons. The main uncertainty is uninspected plugin configuration and user-facing behavior.
Verification Status: PARTIALLY_VERIFIED. The current filesystem, active-plugin set, selected safe options, source/runtime file comparisons, and document scope were verified. Functional behavior, complete vendor settings, external integration health, business approval, and launch readiness were not.
Evidence: Read-only plugin/theme metadata, whitelisted option reads and aggregate counts; 63-file Platform Core manifest comparison; Member Perks source/runtime comparison; Fluent Snippets source/runtime diff; affected artifact is this document. No tests or CI were run.
Known gaps / highest-risk assumption / next action: Do not treat matching version labels as proof of source parity. Member Perks and Fluent Snippets show file drift. The project owner should resolve those source/runtime boundaries. The commerce owner should verify the non-live checkout path before a removal or launch conclusion.
