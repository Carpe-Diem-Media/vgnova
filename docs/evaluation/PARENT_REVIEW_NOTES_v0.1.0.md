# Parent domain review notes v0.1.0

This working evidence log records review of submitted reports. Master reconciliation starts only after all thirteen reports are submitted and checked. Source remains main `454346d9e6a2c36864a4bbd944e52596f16e09c1`. No runtime writes are authorized.

## Runtime report 01

Read the complete 344-line report. Independently repeated both documented PE status commands. Both returned exit 0. PE007 filesystem lock and both database advisory locks were free. PE008 returned `in_progress=false` and `recovery_options=[]`.

Independently opened the finalized journal at `/home/jcschoeman96/Local Sites/voelgoed-novamira/app/.vgnova-platform-core-deployments/history/2de8147a38934ad69d0808867dc38e6a.json`. Re-enumerated installed regular files, hashed their bytes and executable modes, compared with all journal target manifest records, and reconstructed the canonical sorted tree hash. Result: 63 files, zero missing, zero extra, zero content/mode mismatches. Computed and journal tree hashes both `91775a8c85f3a756b58f2a650db2ad010eb605577512d8d218731a1ae0890276`. Journal source commit is current main and phase is finalized. The first parent comparison script used the wrong ancestor for the journal, failed before reading it, then succeeded with the exact absolute journal path. This is current file-to-journal parity, not a new artifact download or application behavior test.

Inspected `site-code/runtime-tools/runtime-mutation-database.php`: mysqli implementation uses prepared IS_USED_LOCK, GET_LOCK and RELEASE_LOCK calls. No vendor rejection was found in that search. The report's MariaDB/MySQL concern is documentation/support terminology and untested failure semantics, not a demonstrated lock failure. No lock acquisition or failure injection performed.

Review limitations: report recommendation 5 repeats part of its own completed FPM/OPcache configuration inspection. Only request-level invalidation remains missing. Domain correctly remains PARTIAL. Full application behavior, ingress, queue failure meaning and host cron remain open.

## CPT and taxonomy report 03

Inspected report inventory, capability maps, taxonomy hierarchy, counts and findings through multiple bounded reads. Source `ContentTopicTaxonomy.php` confirms eight object types and the 55-term canonical tree. Source ACF JSON confirms person public `mense` rewrite and nonpublic event_participation/product_contribution definitions. Runtime registry and aggregate counts are delegated direct observations until independently sampled later.

Important reconciliation questions for subsequent domains: current single Topic assignment may be a known synthetic fixture rather than a new defect; ACF list/get source labels may describe differing metadata semantics rather than actual competing authority; snippet bodies and Bricks query conditions require reports 05/11; LifterLMS-looking storage keys require plugin/source provenance. Empty relationship CPTs do not prove their adapters are unused because legacy fallback consumers may remain.

Report 03's issue list lacks explicit P0-P3 labels and a detailed domain lifecycle. This is a documentation completeness gap, not permission to invent a lifecycle or transactional behavior proof. Parent will preserve the submitted snapshot and record required qualifications in the master report. No deletion recommendation is accepted from zero counts alone.

## Plugin report 02

Read complete report in two bounded reads and its assessment continuation. Parent independently reproduced the Member Perks tree comparison excluding Git/cache/dependency internals. Exactly 360 source files, 305 runtime files, 262 shared paths, 59 changed shared files, 98 source-only paths and 43 runtime-only paths. Runtime-only PHP includes claim-availability services, cache generation, health projection and source-change coordination. This is substantive drift. Provenance and business impact remain open pending report 07; a version string alone cannot establish parity. Parent independently confirmed SettingsRepository and plugin entrypoint differ while PerkPostType and options JSON match.

The report incorrectly describes the historical guest checkout with no methods as differing from the current enabled admin-only test gateway setting. Both can be true simultaneously. Treat this as compatible evidence, not a cross-system contradiction.

Parent read nested Member Perks AGENTS.md. It explicitly declares a custom plugin architecture, plugin-owned repositories and tables, ACF as an admin provider, and its own repository as code authority. Its existence qualifies the report's broad ACF registration-policy conflict. An explicit vg_perk registration exception was not located by parent, but the finding must remain an authority interpretation question, not a proven unapproved CPT implementation or grounds to rebuild in ACF.

Report 02 recommends an approved non-live environment for later tests. The current task forbids new clones/environments. Any next test proposal must use the existing authorized local site under separately scoped transaction approval; no additional environment is proposed here.

Report structure and inventory are substantial but configuration coverage remains partial. The final completion response assigns a numeric confidence despite the user's request to avoid invented numerical precision. Parent will use qualitative confidence and will not transfer that number into the master conclusion. No plugin is approved for deactivation from this inventory.

## ACF report 04

Read all 877 lines in five bounded reads, including the 247-field inventory and lifecycle/consumer sections. Parent independently resolved active ACF symlink to c450475971de722378a1658157a3a24838e467f7, counted 15 JSON files on each side and found zero file mismatches against current main. Parent read all seven Article/Course forward fields from source JSON and confirmed bidirectional flags/target keys. Parent inspected PersonRelatedContentFields.php and confirmed reverse fields are hidden on Person admin edit screens. This supports retaining native bidirectional reverse storage rather than treating it as duplicate editorial authority.

Inspected installed Novamira Pro ACF list-fieldgroups.php:104-136 and get-fieldgroup-schema.php:58-96. Both compute source labels from the returned group's `local` value with db fallback; the schema getter calls acf_get_field_group(key), while list uses acf_get_field_groups. Thus the reported label disagreement is real tool metadata, but does not itself prove conflicting effective schema or a broken JSON loader. Current file parity is separately verified. Exact ACF internal resolution and DB parity require further evidence; keep OPEN.

Report 04 omits exact missing reverse Course ID by its aggregate-only choice. Report 08 is assigned to trace status and consumer implications safely. Missing raw ID does not prove a visible broken card because native query filters may suppress it. Report 03 already supplies successful Broadcast term/assignment counts, so report 04's failed follow-up is a domain-specific gap that can be reconciled later without pretending that failed query passed.

Home featured field is populated in report 04, whereas older VGN52 description says no destination selected. Report 11 must check current value, target validity and draft state. A field name with stored zero/false or a revision value must not be promoted into an active valid public destination. Pending draft definitions have no counterpart on main but may exist on open PR91/92; distinguish pending source work from unique unversioned runtime architecture.

## Custom-code report 05

Read complete 276-line report in two bounded reads. Independently reproduced its production-only Member Perks comparison by selecting PHP/JS/CSS and excluding tests/tools/bin/docs/Git/dependencies. Result exactly 205 source files, 246 runtime files, 205 shared paths, 49 differing shared files, 41 runtime-only, zero source-only. Report 02's 59/43 totals use a broader all-file population; both results are compatible. Do not present different population totals as runtime drift during the audit.

Report 05 section 6 incorrectly says none of the nine snippets has a repository counterpart. Parent confirmed `site-code/fluent-snippets/10-vg-bricks-medewerker-queries.php` exists. Reports 02/04 inspected it and found the runtime wrapper differs while its body matches. Correct consolidated conclusion is eight without a root-repository counterpart, one with a matching implementation body.

Report 05's Platform Core comparison is the raw base-plus-overlay population before applying integration.patch. Its two expected bootstrap differences are not evidence of assembled-artifact mismatch. Parent's complete journal-bound file/mode/tree comparison in report01 review takes precedence for current installed parity. A current artifact re-download/source-bound reproduction was not performed by parent.

Parent independently read all HappyFiles sync snippet logic. It can flatten term parents, create counterpart terms and assign post terms during admin_init when the marker is absent, then sets the marker without verifying every operation. This is a concrete latent administrative write path. No backfill or admin bootstrap was invoked by parent, and the marker's current value is not yet established. Ongoing created-term/set-term hooks also call that logic. Preserve the snippet and data pending consumer/retention review; no claim of current corruption follows from code alone.

Severity reconciliation remains for the master: reports02/05 classify the same Member Perks provenance risk P2/P1 respectively. Both explicitly report no proven functional failure. Treat it as a gate before deploying or making source-based benefit claims; do not manufacture a P0 incident.

## Commerce report 06

Read complete 331-line report in two bounded reads. Inventory covers 24 parent products plus 14 variations, selected runtime configuration and provider code. Parent independently read installed Woo Order Test functions.php:152-163 and 209-254. Its process_payment rejects non-administrators, while its woocommerce_thankyou callback calls update_status(completed) for any order reached by an administrator without checking payment method, current status or the bypass option. No order or thank-you URL was accessed. This static code path means an admin browser read is not automatically read-only. Preserve that distinction in any later checkout test plan.

Parent independently read Masteriyo WcEnrollmentEmailToStudent.php:40-99. It calls send and then unconditionally sets a per-user/course sent marker. No actual delivery failure is established. VGN19 already tracks fulfilment/email work, so do not propose a duplicate issue.

Tax disabled and no rate rows is a current configuration/business decision gate, not proof of tax noncompliance or incorrect customer totals. No legal conclusion is made. Real guest gateway availability remains a historical failure plus current selected configuration; no new checkout request was made.

### Product single exclusion hypothesis, independently exercised without WordPress

Parent read the complete runtime MU plugin. Its pre_get_posts handler considers a query a product context if its post_type is product, even if it is a single and not an archive/taxonomy query. It then adds all linked Course product IDs to post__not_in. The intended redirect runs later on template_redirect and requires is_singular(product). This can explain a single turning into a 404 before that redirect. The current HTTP route was not requested.

Executed a standalone php8.5 stdin harness. It defined ABSPATH; no-op add_action/add_filter; false is_admin/wp_doing_ajax/taxonomy_exists; absint; get_posts returning [1935] for product queries and [] otherwise; and a WP_Query stub with post_type=product/name=mindfulness-vir-vroue, is_main_query=true, archive=false, tax=false and get/set accessors. Then required the unchanged MU file and called vg_masteriyo_course_product_hide_from_main_queries on that query. No WordPress bootstrap, database, HTTP, filesystem write or provider API was used. Exit 0; resulting query added post__not_in=[1935]; single_query_self_excluded=true. This proves the callback transformation for those inputs, not the current live request's exact parse flags or final response. Link the hypothesis to existing VGN53 and verify with a separately assessed safe route/query observation before repair. No fix performed.

Report06 calls the MU source path active from automatic loading; that is conventional load-path inference rather than observed callback execution. Keep the qualified label. Parent has made one source-only harness check; domain agents' statements that they ran no tests remain accurate for their own work.

## Membership report 07

Read complete 577-line report in three bounded reads, including all drift file lists. It independently confirms current tier mappings/prices and disabled Perks execution. Parent identified a material source-boundary error: dashboard files are absent from the site-code overlay but are tracked in the top-level assembly base. Parent used git ls-files and byte comparison for MembershipService.php, SubscriptionService.php, MemberAreaService.php, DashboardRegistry.php and AccessGuard.php under `voelgoed-platform-core/includes/`. All five are tracked and byte-identical to runtime. Therefore report07's dashboard source-provenance UNKNOWN/CONFLICT claim is rejected. Canonical assembly explicitly includes this base plus overlays; it does not require every base file to be duplicated under site-code. This corrects source provenance, not UI or lifecycle verification.

Parent read tracked/runtime-identical MembershipService cancel action construction at lines76-91 and 131-133. It produces a PMPro cancel URL with levelstocancel. Parent also inspected MemberAreaService's explicit public feature-flag contract and fail-closed fallback. These code findings remain meaningful after the provenance correction. Actual Bricks action bindings and user-visible results are still unknown. Cancellation mismatch is conditional and requires source/provider/action reconciliation before paid acceptance; no cancellation or user lookup performed.

Report07's acceptance table contains ten criteria, five VERIFIED and five PARTIALLY_VERIFIED, whereas its final assessment claims eleven, five plus six. Use the explicit table's ten-item breakdown; do not transfer an unsupported count. Source/runtime drift all-file counts agree with reports02 and parent reproduction; source-only98 are tests, so they do not represent missing runtime application code.

The current lack of PMPro page/category rows is limited to those tables. It does not prove every content route is public because custom hooks, role checks and hub gates also exist. PMPro zero amounts are access-only configuration and must not be described as missing Woo tier prices. Parent product drafts and disabled direct signups are intentional current configuration until a separate commercialization decision.

## LMS report 08

Read complete 312-line report in two bounded reads. It identifies the stale reverse reference as absent Course1896, alongside valid synthetic Course1889. Saved native query/result-count guard can omit the absent ID while retaining the valid Course; stale metadata is not proof of a visible broken or empty section. Reports04/08 are compatible after that distinction. Persisted learner aggregates do not establish order provenance, current access enforcement or full migration.

Parent inspected LearningService current-user comparison and access/progress checks in the tracked assembly base. Parent also independently compared source/runtime CoursePerkService logic: source explicitly rejects ClaimPolicy::SCOPE_PER_ORDER; runtime instead adds canonical availability evaluation and omits the local explicit scope rejection. Claim feature gate precedes both paths. This confirms implementation divergence, not an exploitable access path or active claim failure. Further policy checks may exist deeper in the runtime validator.

### Bricks endpoint availability reconciliation

Reports04/08 successfully used configured Bricks-named read tools. Reports03/07 considered them unavailable or rejected them based on legacy-alias descriptions. Parent safely parsed only selected local MCP endpoint strings from ~/.codex/config.toml without displaying arguments, environment values, credentials or query strings. Bricks-Voelgoed-Local and Voelgoed-BricksMCP-Frontend are both configured for `http://localhost:10039/wp-json/mcp/mcp-adapter-default-server`. Novamira-Voelgoed-Local is configured for `http://localhost:10039/wp-json/mcp/novamira`. Therefore legacy naming/description alone was not evidence that the Bricks tool used the wrong endpoint. The inspected local configuration supports reports04/08 endpoint choice. It does not prove every child's effective process configuration or make an unattempted read successful. Reports03/07 retain their actual coverage gaps; do not generalize them into a global Bricks MCP outage.

## Events report 09

Read complete 262-line report in two bounded reads. Report09 resolves an aggregate interpretation error in report04: two event_medewerkers owner rows are one current event763 and revision769, not two current events. Four raw references therefore must not be presented as four current live relationships. Current event763 has two legacy profile links. Preserve revision history and both relationship models.

Parent independently read installed Bridge payment-status filters at lines5085-5108. The scan payment guard resolves Woo order status and accepts completed/processing. The HPOS summary paid-status list additionally includes the WordPress placeholder status draft. Tickera checkin_api.php:300-362 queries published ticket instances against that parent WordPress status and increments sold_tickets. Parent also read Bridge status-change handler: cancelled trashes tickets; all other transitions attempt untrash. This supports a count-versus-admission risk for pending/refunded orders, not a claim of a successful unauthorized scan or an observed customer incident.

Parent independently read Tickera class.ticket_instance.php:88-129. Its attendance getter normalizes records, merges legacy checkouts, writes tc_checkins and deletes tc_checkouts under its guards. Do not call this getter or get_event_essentials during a strict read-only audit. No endpoint, export, check-in, download or attendance accessor was invoked by parent.

Report09's 10/10 research execution score and 90% confidence are self-ratings, not completeness or operational acceptance evidence. Its actual domain/runtime coverage is PARTIAL. Section8 lacks P0-P3 finding labels; parent will assign justified priority in the master. Pending draft PR91 source is versioned but unmerged, so runtime presence is pending work rather than proof of architecture unique to runtime. Verify exact draft/source parity separately if needed before activation.

## Editorial report 10

Read complete 329-line report in two bounded reads. It traces the single current Topic assignment to synthetic Article1887 and the Course instructor relation to synthetic Course1889. This resolves reports03/04's historical-zero-versus-current-one question as a later fixture state rather than unexplained migration corruption. Parent read PERSON_PROFILE_OWNER_DECISIONS_v1.0.0.md: identity1705/classifications approved, existing profile copy/portrait and Event/Product edges explicitly excluded. Do not recommend populating Event Participation/Product Contribution from the legacy fixture edges as an assumed required next step.

Report10 repeats the already resolved Member Perks 59/43 versus49/41 totals as a conflict because it did not rerun the comparison. Parent's independently reproduced all-file and production-only sets resolve this; use the reconciliation above.

### Downloadable Course products are native LMS products

Parent inspected installed Masteriyo CourseProduct.php:45-70: get_virtual and get_downloadable unconditionally return true. WcIntegrationAddon.php:608-617 explicitly hides Woo downloadable-files controls for mto_course and mto_course_recurring. Therefore empty _downloadable_files on Products1935-1938 does not establish missing delivery or a standalone launch blocker. Masteriyo enrollment/curriculum/access are the course-delivery path. Keep VGN25's real digital-book/audiovisual source/rights/delivery obligations distinct; no ordinary downloadable-book product was proved configured incorrectly here. Correct report10's P2 digital-media finding to native configuration with course-access behavior unverified.

Report10 incorrectly states that the task forbids all HTTP/source-site requests. The user permits inspection and requires safety assessment of side effects; the agents chose not to request dynamic routes after identifying potential effects. Preserve the evidence gap without inventing a universal permission requirement for read-only inspection. Transactional and mutating requests remain prohibited. Published fixture status does not prove external reachability or traffic.

The WordPress indexability flag and absence of conventional SEO-plugin metadata are configuration evidence only. They do not establish rendered title/schema absence, actual indexing or public exposure. The declared Rank Math stack mismatch is confirmed as package/configuration absence; owner selection and rendered SEO checks remain open.

## Frontend report 11

Read complete 336-line report in two bounded reads. It inventories all32 nontrashed templates, query/form bindings, default assignments and shared style resources, but makes no current render/browser/accessibility pass. Parent independently inspected installed Bricks default template types and the published/no-conditions default logic. This supports default fallback inference; actual frontend selection remains unverified.

Report11 confirms draft page1926 has no home_featured_destination value or marker row. Therefore report04's populated1/1 statement does not establish a selected destination. Report11 calls it a schema-count reinterpretation, but report04 explicitly labels that table population; the consolidated result must correct the earlier statement rather than say it was never a population claim. A deliberately optional empty destination is not a defect. Draft CTAs using approved transitional routes are not a P1 blocker solely because later IA convergence is planned. Empty Footer78 and unresolved header consumers are current stored-state gaps, with render impact unproven.

Report11 repeats that Bricks MCP was unavailable based on alias descriptions. Parent's exact configured endpoint inspection above rejects that blanket conclusion; this agent chose the permitted SQL fallback. It also reports T3 preview unavailable. Do not infer an actual browser verification result from saved Bricks conditions or historical draft Hallmark results.

### Confirmed audit credential disclosure

The frontend agent reported that a raw settings result accidentally surfaced a stored Frames remote-template credential. Parent has not opened or copied that raw result and has not repeated, used, tested, rotated or revoked the value. The report omits it. This is a confirmed failure of the audit's no-secret-disclosure requirement. Current validity, privilege scope, retention, audience and subsequent use are unknown. Owner/security follow-up must classify scope, review access and transcript retention, and authorize appropriate rotation/revocation and recovery. No unauthorized credential operation is performed under this audit. VGN54 concerns a prior cache-salt disclosure and must not be misrepresented as tracking this different Frames incident. No new Plane record or external message has been created by parent.

The report's blanket pass of the read-only/safety scope does not erase this incident. WordPress record writes remain unobserved; confidentiality and mutation are distinct requirements. Final audit assessment must disclose the incident and the unresolved containment gap. Domain inventory can be delivered with PARTIAL coverage, but the confidentiality criterion cannot be marked PASS.

## Operations report 12

Read complete 337-line report in two bounded reads. Queue aggregates are a later sample: complete actions decreased from5001 to4205 while failed7/pending21 remained. Failed hooks are Woo fetch_patterns older than30days;19 pending are future and2 overdue less than1day. Do not label this all-commerce backlog or infer the audit caused cleanup. Normal site activity and retention are possible causes; no cause was observed. Member Perks schedule reconciliation and SMTP diagnostics can mutate state, supporting the choice of scoped raw reads. Plugin activation plus missing fluentmail-settings does not establish no outbound mail or a safe notification sink.

### Separate read-scope incident

Agent12 reports an overbroad search briefly emitted credential-shaped test artifacts from outside the authorized project. It stopped and omitted the values and file references from its report. Parent has not reopened the raw results or inspected the unrelated project. Whether these are valid credentials, synthetic fixtures or exploitable secrets is unknown. The owning project/security owner must classify the output, access/retention and containment need. No reuse, credential testing, deletion, rotation or other external-project action was authorized or performed by parent. This is distinct from the Frames credential incident and prior VGN54 cache-salt issue.

Report12 explicitly marks its scope/safety criterion FAIL. Its overall PARTIALLY_VERIFIED and6/10 are the child's self-assessment, not a waived mandatory gate. Parent final assessment must preserve the failed scope/confidentiality requirements, include BLOCKED where the mandatory failure applies, and keep inventory completion distinct from full verification. No new issue or external message was sent.

## Initial domain completeness gate before Agent13

Reports01-12 are present and have each been read completely by parent through bounded reads. They all contain the twelve required section numbers; all report PARTIAL domain/runtime coverage. Exact shortcomings and rejected claims are recorded above. No claim is accepted that the complete website, all current routes, payment, learner access, scanning, email, analytics, rights or production launch was behavior-verified. Initial corrections are preserved in this parent errata log rather than overwriting submitted snapshots. Agent13 must read this log alongside every report before recommending any lifecycle changes.

## Redundancy report 13 and final reconciliation gate

Read complete336-line report in three bounded reads. Agent13 ran after reports01-12 completed and passed the initial structure/parent-review gate. Its recommendations name consumers, replacement/retention, prerequisites, business risk, rollback and owner gates. No REMOVE_CANDIDATE is established and the only ARCHIVE_CANDIDATE concerns a superseded documentation proposal, not a live route. The canonical decommission register remains unchanged.

Parent qualifies two residual synthesis errors: reports02/06/07/08 read current same-session Perks execution flags as disabled, so report13 must not downgrade all such evidence to an older historical-only snapshot. They are current selected configuration, with behavior still untested. Report13's proposed FCM register amendment should not conflate the independent optional homepage destination with the legacy category-based FCM selector. Its dashboard provenance wording softens report07's error; parent explicitly rejects that error because tracked assembly-base files match runtime.

The master evaluation begins after this final report read and completeness check. It will preserve the incident failures, current verification limits, exact source/runtime parity scope, separate milestones and no-removal boundary. Original domain snapshots remain unchanged; this log and the master contain the reviewed corrections.

## Final document validation erratum

All thirteen domain reports contain their twelve required numbered sections. Reports 01 and 06 use level-one section headings; the other reports use level-two headings. A validator restricted to level two would incorrectly mark those two reports incomplete. The master contains sections A through L plus an evidence/assessment section M.

The local Markdown link check found one unresolved fragment in the preserved report 08 at line 131. Its Golden Journey target file exists, but #lms-boundaries is not a heading. The applicable section is [10. Course vs Woo Product Boundary](../superpowers/specs/2026-10-25-vs1-golden-journey-product-tech-spec_v0.2.0.md#10-course-vs-woo-product-boundary). This erratum supplies the correction without silently overwriting the submitted snapshot. No other checked Markdown local path/heading fragment failed. Inline code paths and external URLs are not exhaustively validated by that check.
