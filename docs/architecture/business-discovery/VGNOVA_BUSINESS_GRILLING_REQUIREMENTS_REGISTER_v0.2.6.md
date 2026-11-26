# VG Nova — Business Grilling & Requirements Register

**Document version:** 0.2.6
**Filename:** `VGNOVA_BUSINESS_GRILLING_REQUIREMENTS_REGISTER_v0.2.6.md`
**Created:** 21 November 2026 (Africa/Johannesburg)
**State:** `DISCOVERY — IN PROGRESS` (business requirements confirmed; implementation not approved)
**Latest accepted interview answers:** `EV-01–EV-05` — first-pass Events and Ticketing checkpoint
**Next interview domain:** Programmes and Challenges (breadth-first); S2-Q01 shared identity and S1-Q12B broadcast override remain OPEN / DEFERRED.
**Authoritative business informant:** Voelgoed business owner (responses recorded in this conversation)
**Persistent tracker:** [Plane VGN-56](https://app.plane.so/symphony-development/browse/VGN-56)
**Historical evidence:** [November 2025 Google Drive planning folder](https://drive.google.com/drive/folders/1Dqnkm83XBbWm3QTNMLjUDfXf_DpkVEDt)
**Current technical sources to verify:** [`Carpe-Diem-Media/vgnova`](https://github.com/Carpe-Diem-Media/vgnova), [VG Nova PR #96](https://github.com/Carpe-Diem-Media/vgnova/pull/96), Plane VGN-23 and VGN-55. Technical snapshot descriptions are **not** acceptance evidence for individual features.

> **Purpose:** Preserve **every grilling question, owner answer, business fact, open decision, conflict, and verification obligation** before further development or migration. This is the cumulative interview record, not a parallel technical roadmap or an instruction to modify a website.

## 0. Governance — how to maintain this document

1. **Append-only decision history:** Never silently overwrite, remove or reinterpret a previously recorded owner answer. When a later answer changes the position, add a new decision/change record with the question ID, date, previous statement and supersession reason. Keep history visible.
2. **Version both filename and metadata.** Copy the entire latest file into `..._v<next-semver>.md` and append changes there. Don't create a competing second requirements register. Old versions are historical snapshots. A later version can reclassify an earlier finding, but must cite the earlier record explicitly.
3. **One question ID per interaction.** Record the actual question asked, choices offered if relevant, the exact owner-submitted answer, any free-text explanation, its evidence status and impact. Preselected UI defaults **do not** count as decisions until submitted.
4. **Evidence classes:** `OWNER-CONFIRMED` (business process or intent); `HISTORICAL-PROPOSAL` (old Drive docs); `TECHNICAL-SNAPSHOT` (dated audit); `RUNTIME-VERIFIED` (independent current runtime test); `DESIGN-CANDIDATE` (not approved); `APPROVED` (explicit architecture decision); `OPEN` (not answered). Do not silently promote a record between classes.
5. **Separate AS-IS, TO-BE and verification.** A requested target is not necessarily implemented, and a partially registered CPT is not an end-to-end workflow.
6. **Use Plane VGN-56** for milestones, decisions, blockers and links; use existing backlog issues as implementation owners. GitHub/repository Markdown is the detailed interview evidence, not an alternative project-management roadmap. Keep decommission questions in the existing `docs/architecture/LEGACY_DECOMMISSION_REGISTER.md`; no removals without consumer/migration verification and scoped approval.
7. **No engineering authority implied.** These notes do not approve a plugin/CPT/taxonomy, modification to production or LocalWP, historical data deletion, migration, deployment or PR merge. Any merge recommendation independently requires current exact diff, CI and all authority references checked.
8. **Domain lifecycles:** For each meaningful entity (programme, episode, media clip, course, subscription, ticket, competition, etc.), before design approval explicitly specify states, allowed transitions, guards, side effects and terminal states. The question-phase alone does not settle them.
9. **Pending questions remain pending.** The interviewer asks one focused question at a time; record subsequent answers in the next version and preserve unresolved items across threads.

---

## 1. Interview scope and source reconciliation

### 1.1 Overall goal (owner-established)

Consolidate the real content and services of `voelgoed.co.za`, `leer.voelgoed.co.za` and `winkel.voelgoed.co.za` into one streamlined VG Nova site. Before large migrations, obtain a complete business-capability inventory and an evidence-backed account of **what already exists in VG Nova, what is missing, what is redundant, and what must be built or fixed**. Then establish foundation acceptance, controlled migration and final frontend/customer journeys. Prefer native WordPress/WooCommerce/Tickera/PMPro/Masteriyo owners and one source of truth for each domain fact.

### 1.2 Historical-document evidence

The old Google Drive folder contains 39 files (35 direct items plus five files in one subfolder, excluding the subfolder itself), last generally changed in November 2025. The separate deep-dive document `VGNOVA_Historical_Drive_Deep_Dive_v0.1.0.md` catalogues these with source links and contradictions. Pertinent historical sources:

| Source ID | Historical source | Relevant themes and limitations |
|---|---|---|
| HIST-01 | [Three-site breakdown / *Voelgoed, VGW en VGL Uiteensetting*](https://docs.google.com/document/d/16PK7WjRKXHEpoSjKrsQMRfGwPhR9YHab/edit) | One site; seven themes plus Leefstyl; Mentorklas; Tickera-native events; Vriendinneklub; Voelgoed LIVE/Twintig Twintig; 60-day challenge; author/mentor relationships. November 2025, not current runtime proof. |
| HIST-02 | [Voelgoed LIVE + Inspirasie Vrou blueprint](https://docs.google.com/document/d/1pQrUyTc6YwSRS0JytDRK4Bc9OTvMRPa9/edit) | Channel → programme → episode idea, replay/highlight, competition/nomination/voting. Suggested `broadcast` CPT and taxonomies are **historical proposals**, not new model approvals. |
| HIST-03 | [In-Between Initiatives strategy](https://docs.google.com/document/d/1td6WCq9hLFsT1TzNpez7m18n33qQ_kqk/edit) | Conflicting treatment of Voelgoed LIVE as a recurring event; podcast and campaign hubs. Owner round 2 resolves channel/programme relationship. |
| HIST-04 | [Topic clusters/subcategories and URLs](https://docs.google.com/document/d/1gw4rKEJUCnzEI3xB81bfo69SjSfjwP9R/edit) | Multiple topic/subcategory concepts; not current taxonomy authority. |
| HIST-05 | [Master sitemap/taxonomy/URL architecture](https://docs.google.com/document/d/1Rb2LznAgSBgKEpwSbBI4-FzX_q931s2Z/edit) | Alternative flat URL and category proposal; conflicts with other 2025 proposal. |
| HIST-06 | [Vriendinneklub revenue strategy](https://docs.google.com/document/d/1Yn9IzRKfAXtMjNQ6lZOqBO56JtrNJq/edit) | 2025 tiers, offers and assumptions are historical, not current prices or approved benefits. |
| HIST-07 | [Featured Content guide](https://docs.google.com/document/d/1U9GI-EFUjdlciy3N3Ec1DPcxg6ovpndb/edit) | Editor-controlled placements, but claims of zero-code behaviour require verification. |
| HIST-08 | [Historical six-phase tracker](https://docs.google.com/spreadsheets/d/1_vFLC-05hzIGS7yYv7IyP79ouEhACzkqoWiV3rsEMtA/edit) | Its February 2026 launch milestone and completion numbers are outdated. Do not use as current task tracker. |

### 1.3 Verified-governance context versus unverified execution

- Existing repository architecture identifies a canonical **seven-root `content_topic`**, distinct from legacy `content_category`, which retains Leefstyl commercial terms and consumers. Do not add Leefstyl as an eighth semantic root by importing an old plan. See [Legacy Decommission Register](https://github.com/Carpe-Diem-Media/vgnova/blob/main/docs/architecture/LEGACY_DECOMMISSION_REGISTER.md).
- Existing architecture models a canonical Person with contextual roles rather than a separately editable Mentor/Speaker identity. It proposes/native-maps domain-specific event and product relationships. Actual feature workflows require verification.
- The November 2026 local audit reports some `uitsending` records, existing Bricks templates and an incomplete domain reconciliation. **This does not establish** an operational main programme page plus separately published, properly linked episode pages.
- A prior audit describes substantial source-versus-runtime drift in Member Perks, nonverified checkout completion and migration gaps for orders, subscriptions, tickets, LMS and reader entitlements. These remain independent acceptance gates, not interview decisions.

---

## 2. Session 1 — Business offerings / recorded questions and answers

### S1-Q01 — Inventory current business offering status

**Question asked:** "Does this table accurately represent all the different types of customers, users and business partners Voelgoed currently serves?" The opening business-role table mentioned free readers, registered users, paid members, shop customers, course students, event customers/attendees, advertisers/sponsors, presenters/contributors, organisers/partners and internal staff. **No submitted answer to that original ten-role customer-persona question was received.** We pivoted to historical-document review before answering; mark it `OPEN`, not answered.

**Reframed submitted Round 1 question:** "What is the status of these offerings today?" Choices provided: Active; Seasonal; Planned; Discontinued; To clarify.

**Owner-submitted answers (verbatim values):**

| Offering | Owner answer | Evidence class | Qualifications |
|---|---|---|---|
| Voelgoed editorial | **Active** | OWNER-CONFIRMED | Exact formats, authoring, approval and distribution unresolved. |
| Leefstyl | **Active** | OWNER-CONFIRMED | Current commercial service/partner classifications and boundaries unresolved. |
| Mentorklas | **Active** | OWNER-CONFIRMED | Current access entitlements, lesson vs article vs video structure unresolved. |
| Voelgoed LIVE | **To clarify** | OPEN at Round 1; business identity later resolved in Q02 | Round 2 confirms it is an umbrella channel/brand. This does not independently verify broadcast frequency or operating catalogue. |
| Twintig Twintig | **Active** | OWNER-CONFIRMED | Individual episode publishing workflow clarified in Q03–Q07. |
| Podsendings | **Active** | OWNER-CONFIRMED | Feed/distribution/episode model unresolved. |
| Inspirasievrou | **Active** | OWNER-CONFIRMED | Does **not** establish on-site voting or any particular competition mechanics. |
| 60 dae-uitdaging / Nuwe Jy | **Active** | OWNER-CONFIRMED | A combined label, not proof that every associated product/event/challenge is active at once. |

**Result:** Seven labels explicitly marked active and one label initially requiring clarification. No active/seasonal/discontinued decision has been taken for the remaining business activities named in the historical files. This is business-owner status, **not** technical proof on any website.

**Plane record:** [VGN-56](https://app.plane.so/symphony-development/browse/VGN-56), comment `d8e2401d-15ae-4743-8665-e954a45b6385`.

### S1-Q02 — Voelgoed LIVE relationship to Twintig Twintig

**Question asked:** "How exactly does Voelgoed LIVE relate to Twintig Twintig today?" Choices offered: umbrella channel with show; same offering/different names; separate offerings; historical channel vs current show; other.

**Owner answer, verbatim:** "Voelgoed LIVE is our online TV/broadcast brand and Twintig Twintig is one programme within it."

**Classification:** `OWNER-CONFIRMED` business identity and relationship. This disambiguates HIST-01/HIST-02 from HIST-03. The programme is **not automatically a Tickera event**. Other programmes under the umbrella are possible but **not confirmed**.

**Candidate modelling (NOT APPROVED):** separate concepts `Broadcast Brand/Channel`, `Programme`, `Episode` with explicit programme membership; do not yet add a CPT or taxonomy.

**Plane comment:** `31fc7bf4-3238-402b-a699-b170ca214ba8`.

### S1-Q03 — Live broadcast versus pre-recording

**Question asked:** "How does Twintig Twintig currently work from recording to viewing?" Choices: live broadcast plus replay; pre-recorded scheduled release; both; other.

**Owner answer, verbatim:**

> Current format: Live broadcast followed by replay availability
> Details: Live on socials using restream then replay on the site + snippets and highlights

**Classification:** `OWNER-CONFIRMED` current operational pattern: live distributed through **Restream to social platforms**, then replay on Voelgoed website, plus snippets/highlights. Do not assume exact social destination names, archive source, Restream recording ownership, episode schedule or the way clips are entered in WP.

**Plane comment:** `859e4b54-9622-41c2-871c-02c5710fe6ed`.

### S1-Q04 — Snippets/highlights publication

**Question asked:** "How do you currently publish and manage snippets or highlights from Twintig Twintig episodes?" Choices: socials primarily; inside episode page; separate website items; mixed socials and on-site; other.

**Owner answer, verbatim:** "Combination of social publishing and on-site individual or embedded highlights."

**Classification:** `OWNER-CONFIRMED` mixed use. **Unknown** whether every clip is its own WP post, whether the same video is embedded more than once, whether clips have independent permalinks or whether highlights are automatically related to the full episode.

**Candidate modelling (NOT APPROVED):** support both episode-associated media and editorially featured/discoverable clips without assuming every clip requires a CPT.

**Plane comment:** `106b0204-7ebd-4039-afe0-e9551acc9a75`.

### S1-Q05 — Public and restricted access

**Question asked:** "Who can currently watch Twintig Twintig full episodes and highlights on your website?" Choices: public/free; paid members; mixed; delayed public release; other.

**Owner answer, verbatim:**

> Current access: All full episodes and highlights are freely available.
> Details: Broadcasts are free at the moment - might have restricted access using PMP in the future, but currently it is fully free

**Classification:** `OWNER-CONFIRMED` public/free current policy, without required account or paid PMPro membership. `FUTURE OPTION, NOT APPROVED`: optional PMPro access restrictions for some broadcast content later. The existence of PMPro **does not justify** hiding currently public content; Mentorklas/other protected areas are different business domains.

**Future guard candidates (not approved):** permission-aware rendering and server-side access control; cache/SEO/indexing effects; channel/programme/episode-level overrides must be separately decided.

**Plane comment:** `14fd0f5f-317f-4612-86c0-58f0842688c7`.

### S1-Q06 — Replay video hosting and website display

**Question asked:** "Where are Twintig Twintig's full replay videos hosted, and how do they appear on your website?" Choices: YouTube embed; Facebook embed; multiple external platforms; self-hosted; other.

**Owner answer, verbatim:** "YouTube-hosted replay embedded on website."

**Classification:** `OWNER-CONFIRMED` full-replay playback through YouTube embeds. **Unverified** that all historical episodes are there, that original master footage is archived separately, that clips are also on YouTube, and whether WP stores IDs, links or iframe HTML.

**Design candidate (NOT APPROVED):** canonical episode data stores a validated external video identity/URL, with reusable Bricks presentation; do not duplicate full videos in WP purely to render them. Later verify YouTube/embed availability, permission, privacy/consent and fallback/metadata handling.

**Plane comment:** `bf27010b-744c-42f7-904e-0e56f0f53e82`.

### S1-Q07 — Current episode editing versus target programme/episode pages

**Question asked:** "How do your staff currently create and manage individual Twintig Twintig episodes on the website?" Choices: separate episode records; one recurring programme page; both programme hub and individual episodes; mostly external; other.

**Owner answer, verbatim:**

> Current workflow: One recurring programme page updated for each episode.
> Details: But this needs to change and be improved. We need to move to: Both. We have a main Twintig Twintig programme page plus separate individual episode pages, and I think we already started setting this up on the new site.

**AS-IS — OWNER-CONFIRMED:** Present production process updates one recurring page per new episode; this is inadequate for the desired archival model.

**TO-BE — OWNER-REQUESTED:** Keep a permanent main Twintig Twintig programme page **and** have permanent, individually addressable episode pages/records for each episode. Do not overwrite prior episodes when posting new ones.

**TECHNICAL STATUS — UNVERIFIED:** Owner believes VG Nova has begun implementing this. A dated inventory mentions local `uitsending` records but does **not** establish working templates, episode→programme relationships, stable URLs or the authoring process. Inspect repo/ACF/Bricks and test rendered frontend before marking implemented.

**Candidate acceptance test (not yet approved):** Staff publish one episode with title/date/replay video, linked programme and stable permalink; main programme page can list it; older episodes remain addressable; no duplicated editable content or accidental paid gating.

**Plane comment:** `bc59f78c-0cf6-4f71-a9ea-9844cd74e7ee`.

### S1-Q08 — Programme page automation versus editorial selection **(OPEN)**

**Question already asked, but not answered:** "When staff publish a new Twintig Twintig episode, how should the main programme page update?"

Options presented:

1. **Fully automatic:** Latest published episode first; older episodes in chronological archive.
2. **Automatic with editorial control:** Newest by default, staff may feature a different episode or special highlight.
3. **Manually curated:** Staff choose featured episode and listing order.
4. Other.

**Recorded answer:** `NONE — OPEN`. The UI's preselected default was **automatic with editorial control**, but **the owner has not submitted it**. Do not treat this default or the interviewer's personal preference as approved. When resuming, ask for the owner's actual selection.

---

## 3. Consolidated known facts and conflicts (as at v0.1.0)

| ID | Fact or proposition | Status | Practical consequence |
|---|---|---|---|
| F-01 | Seven named offerings currently active | OWNER-CONFIRMED | Keep all seven in coverage; business detail interviews still required. |
| F-02 | Voelgoed LIVE is umbrella broadcast brand | OWNER-CONFIRMED | Do not model it exclusively as an event series; preserve brand identity. |
| F-03 | Twintig Twintig is an active programme under Voelgoed LIVE | OWNER-CONFIRMED | Programme/episode association needed conceptually; technical shape pending. |
| F-04 | Live stream uses Restream to social platforms | OWNER-CONFIRMED | Distinguish distribution from canonical media ownership. |
| F-05 | Full replays are YouTube-hosted, site-embedded | OWNER-CONFIRMED | No automatic need for duplicate full-resolution website video files. |
| F-06 | Highlights use mixed social and on-site publishing | OWNER-CONFIRMED | Support both placements; clip content ownership unverified. |
| F-07 | All current broadcasts, full episodes and highlights are public/free | OWNER-CONFIRMED | No present PMPro paywall; preserve public browsing. |
| F-08 | Future PMPro-restricted broadcasts may be considered | FUTURE OPTION | Do not implement/paywall until policy approved and tested. |
| F-09 | Current episode publication reuses one main page | OWNER-CONFIRMED AS-IS | Needs migration of editorial practice/links without overwriting older content. |
| F-10 | Target is programme hub plus separate individual episode pages | OWNER-REQUESTED TO-BE | Foundation acceptance must validate this end-to-end. |
| F-11 | Some target implementation may already exist | OWNER BELIEF / UNVERIFIED | Verify before building; avoid duplicate `uitsending`/broadcast types. |
| F-12 | Main-page automation/curation behaviour | OPEN S1-Q08 | Next question before locking template/query design. |
| C-01 | Historical Voelgoed LIVE classified as event series vs broadcast brand | RESOLVED IN BUSINESS TERMINOLOGY by S1-Q02 | Do not copy outdated historical event classification. |
| C-02 | Historical schedule Wednesday 20:00 vs Friday 20:00 | OPEN | Confirm current schedule or avoid hardcoding. |
| C-03 | Historical eight-category incl Leefstyl vs seven semantic Topics | CURRENT TECHNICAL AUTHORITY HAS SEVEN ROOTS; further business mapping open | No taxonomy proliferations or premature decommission. |
| C-04 | Separate `broadcast` proposal vs existing target `uitsending` | OPEN TECHNICAL RECONCILIATION | Inspect current CPT/ACF/templates/routes, not create duplicated post types. |

---

## 4. Questions still to ask — scoped interview backlog

### 4.1 Continue Twintig Twintig / Voelgoed LIVE (one focused question per turn)

| ID | Priority | Question | Why it matters |
|---|---|---|---|
| S1-Q08 | **NEXT** | Main programme hub: fully automatic, editorially overridable, manual, or other? | Query/placement authority and workflow. |
| S1-Q09 | Next | Does the programme have distinct seasons/series or just a chronological episode sequence, and how is episode numbering handled? | Data identity and archive structure; avoid artificial entities. |
| S1-Q10 | Next | Which episode metadata is staff actually creating now/required (title, publish date, air date, description, video ID, image, guests, topics, transcript, summary, time-coded highlights)? | Minimum viable editor workflow and source migration map. |
| S1-Q11 | Next | Who are hosts/guests/contributors, and do they require roles, profiles and cross-linked content? | Canonical Person and contextual relationships. |
| S1-Q12 | Next | How are clips created, stored, approved and related to episodes? Do some need standalone URLs? | Clip identity and editorial discovery without extra CPT by default. |
| S1-Q13 | Next | What is the actual weekly/seasonal schedule and are there scheduled, cancelled or replaced broadcasts? | Episode lifecycle and publish/availability guards. |
| S1-Q14 | Next | Is there one main episode publishing team? How do draft, edit, preview, schedule, publish, withdraw and archive work? | Staff roles, states/transitions, audit/approval. |
| S1-Q15 | Next | Does sponsorship, branded content or linked products/events appear inside episodes? | Commercial attribution, disclosures and relations. |
| S1-Q16 | Next | Are broadcast episodes distributed by email, newsletters, syndication, SEO pages and video sitemaps? | Delivery, indexing, analytics, promotion and link migration. |
| S1-Q17 | Next | Where do original master video recordings live, how long are they kept, and who owns rights? | Source-of-truth/retention; separate video hosting from backups. |

### 4.2 Subsequent business domain grilling

1. **Podsendings:** formats, series/feed identity, audio hosting, episode transcription, Spotify/Apple/YouTube syndication, guest/host roles, public and paid access; how to share a reusable media model only if appropriate.
2. **Editorial / Leefstyl / Mentorklas:** article types, sponsored advertorials, writers, review/approval, privacy, licensing, media blocks, inline CTAs, Topic taxonomy and member restrictions.
3. **Inspirasievrou and other competitions:** year/campaign, nomination, eligibility, entry approvals, judges, SMS vs online voting, anti-fraud, outcomes, winner announcements, event/broadcast ties, privacy and removal.
4. **Nuwe Jy / 60-day challenge / subbrands:** programme, cohorts, entry/payment eligibility, challenge steps, members, course and product access, recurring yearly events, reporting, prizes and data retention.
5. **Events and ticketing:** own and external organisers, event identities/types, Woo product association, seats, attendee records, refunds/transfer/duplicate admission and FastCheckin sync; event feedback and organiser portals.
6. **Commerce and reader:** physical and digital goods, bundles, entitlement, delivery, app sign-in, reading/listening progress, returns and order history.
7. **Courses/LMS:** Masteriyo as target, learning products, sections, lessons, quizzes, certificates, progress migration, memberships, paid/free access and refunds.
8. **Membership and benefits:** current level mapping, Silver/Gold and other current levels, renewals, failed payments, grace, PMPro/WCS/Member Perks ownership, discounts and restrictions.
9. **Unified user journeys:** anonymous visitor, single identity, purchaser vs attendee, student, reader, member, administrator, contributor, advertiser and organiser; all privileges verified with provider authorities.
10. **Bricks/frontend, SEO, promotion and operations:** programme hubs, taxonomy/topic archives, search, featured placements, site journeys, forms, analytics consent, staff roles, cron, mail, backup, security, performance and decommission.

**Outstanding original business-role question:** The opening ten-group customer/persona table remains unanswered; revisit after enough domain knowledge is collected to prevent double counting.

---

## 5. Technical verification backlog arising from these answers

| Check ID | Verification to do | Evidence required before acceptance | Related existing work |
|---|---|---|---|
| TV-01 | Identify `uitsending` CPT registration authority, attached taxonomies, actual ACF fields/relationships, legacy alternatives, field counts | Repo file paths, current runtime registration, documented allowed values | VGN-55, VGN-56, VGN-24 |
| TV-02 | Inspect all existing Voelgoed LIVE / Twintig Twintig programme pages, Bricks templates and query loops | Current WP IDs/slugs, rendered URLs, SSR/html content, correct programme filtering | VGN-55, VGN-29 |
| TV-03 | Verify individually addressable episodes and programme hub really work | Create/edit in controlled test only with permission, stable URLs, published listing and no duplicate state | VGN-56 acceptance gate |
| TV-04 | Verify YouTube embed fields, validation, fallback, video meta and clip relationships | Current ACF/Bricks binding and rendered browser verification | VGN-24, VGN-25 |
| TV-05 | Confirm free/public guest access remains intact, including cache/consent/embeds | Guest browser and server access proof; no accidental PMPro restriction | VGN-56, VGN-29 |
| TV-06 | Inventory production Twintig Twintig posts, shared page, videos, highlights and archive links | Read-only source site ID/URL map; compare target semantics | VGN-23, VGN-55 |
| TV-07 | Determine historical permalink preservation/redirect strategy for old one-page workflow | Source URL/traffic/indexing inventory and approved redirects; no invented episode URLs | VGN-30 |
| TV-08 | Model programme/episode/clip lifecycles before implementation approval | States, transitions, guards, side effects, terminal/archived states, publishing and withdrawal | VGN-56 |
| TV-09 | Identify any redundant old broadcast taxonomies or field groups and their consumers | Usage inventory, dependency map, existing decommission register entry and approved removal gate | Existing legacy register |

**Working coverage classification:** For programme and episode publishing, `OWNER-REQUESTED; TECHNICALLY NOT YET VERIFIED`, not `FOUNDATION_ACCEPTED`, irrespective of how many existing `uitsending` records or templates are present.

---

## 6. Preliminary lifecycle questionnaire — not yet approved

These are prompts to ensure we do not neglect domain state changes. Do not treat them as a final implementation contract.

| Concept | Candidate states to evaluate with owner | Essential transitions / guards / side effects / terminal semantics to ask about |
|---|---|---|
| Programme | `draft`, `published`, `paused`, `archived` | Who can publish a programme? What happens to existing public episodes if the programme is paused? Does archived mean hidden or permanently historical? |
| Episode | `planned`, `recording/live`, `replay_pending`, `draft`, `scheduled`, `published`, `withdrawn`, `archived` | Validate YouTube source and rights; scheduling/guest approval, replacement of video ID, guest public playback, index updates; what does withdrawn mean for old permalinks? |
| Highlight/clip | `proposed`, `in_edit`, `approved`, `published`, `retired` | Original episode relation, contributor approval, clip rights, social versus on-site publication, asset deletion and link retention. |
| Access policy | `public` now; paid/protected **only if explicitly approved later** | PMPro authoritative membership checks; avoid inferring new paid tiers; cache keys, indexing and visibility require an explicit policy. |

**Caution:** These are candidate lifecycle labels for discovery only; do **not** implement them as ACF selects, custom tables, roles or code until owner confirms their business meaning.

---

## 7. Continuity instructions for next agent/thread

1. Open this latest versioned Markdown register **first**, and read the decisions and `OPEN` entries before asking anything.
2. Read the repo authority (`AGENTS.md`, locked specs, canonical decommission register) and relevant issue [VGN-56](https://app.plane.so/symphony-development/browse/VGN-56) rather than assuming a 2025 Google Drive file is current implementation design.
3. **Next question is S1-Q08**, already asked but **not yet answered**. Do not assume the preselected 'automatic with editorial override' UI choice equals consent.
4. After each owner answer, preserve verbatim answer wording and intent, add the next numbered Q&A, update the fact/decision register, link the supporting Plane comment, and increment the versioned filename. Do not silently discard previous answers.
5. **Do not drift into implementation** while the interview is active. Record gaps and follow-up evidence tasks, then return to the one question being discussed.
6. Before any technical claim that a feature exists or is complete, verify current repo **and** current runtime; before any merge recommendation, independently verify exact diff, current CI and all authority documents.
7. Keep technical roadmap changes in Plane/existing issues; this document is an **append-only requirements and findings record**, not a replacement for the roadmap.

### Document revision history

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.1.0 | 2026-11-21 | Initial reconstruction of Sessions 1 Q01–Q07, original unanswered role question, S1-Q08 open, historical context, unknowns, safeguards and technical verification backlog | None |

**End of v0.1.0. Next owner answer: S1-Q08.**


---

## 8. Version 0.1.1 — Owner-submitted Round 8 / supersession addendum

**Recorded:** 21 November 2026 (Africa/Johannesburg)
**Entry ID:** `S1-Q08`
**Decision scope:** Twintig Twintig programme hub presentation and editorial workflow
**Evidence class:** `OWNER-CONFIRMED — TO-BE BUSINESS REQUIREMENT`
**Implementation class:** `UNVERIFIED — DO NOT ASSERT BUILT`
**Previous record:** Section 2 `S1-Q08` in version 0.1.0, marked `OPEN` at that earlier time. That earlier record is preserved above as a truthful historical snapshot. **This later, owner-submitted answer closes the question** and takes precedence for subsequent interview planning.

### 8.1 Original question and owner-submitted answer

**Question asked:** "When staff publish a new Twintig Twintig episode, how should the main programme page update?"

Choices presented: (a) fully automatic newest plus chronological archive; (b) automatic newest plus chronological archive with optional editorial override; (c) manually curated; (d) other.

**Owner's actual submitted response (verbatim):**

> VG Nova Grilling Session 1 — Round 8: Twintig Twintig programme hub publishing.
> Desired behaviour: Automatic latest published episode and archive, with optional editorial override for featured episodes or highlights.
> Record as owner-confirmed target requirements in VGN-56, separate them from runtime-verified implementation, and continue with the next single focused grilling question.

**Interpretation, approved as business intent only:** The Twintig Twintig main programme hub should automatically surface the newest **published** episode by default and maintain an archive of prior episodes. Editorial staff may override the **featured selection** to promote another episode or a highlight. This is neither fully manual curation nor an owner instruction to override the chronological archive itself.

### 8.2 Clear separation of as-is, to-be, and implementation

| Dimension | Status | Finding |
|---|---|---|
| Current production | `OWNER-CONFIRMED AS-IS` (S1-Q07) | Staff currently update one recurring Twintig Twintig page each time. |
| Future programme structure | `OWNER-REQUESTED TO-BE` (S1-Q07) | Permanent main programme page plus individually addressable, durable episode pages. |
| Future programme display | `OWNER-CONFIRMED TO-BE` (S1-Q08) | Newest **published** episode and episode archive automatically populated. |
| Future editorial flexibility | `OWNER-CONFIRMED TO-BE` (S1-Q08) | Optional override of the featured episode or highlight. |
| Existing VG Nova support | `UNVERIFIED` | Historical/local snapshots refer to `uitsending` records; current ACF/Bricks data, relationships and published end-to-end behaviour not independently tested for this requirement. |
| Engineering design/implementation | `NOT APPROVED BY THIS INTERVIEW ENTRY` | No new CPT, taxonomy, ACF field, Bricks component, cron, automation, paid access, migration or deployment is authorised here. |

### 8.3 Acceptance and lifecycle questions raised (not yet owner-approved engineering contract)

These are **verification/design candidates**, not newly confirmed business rules:

1. Determine the source of the programme-to-episode relationship and render the newest *published and relevant* episode without a second manual edit of the programme page.
2. Verify a permanent per-episode URL, working YouTube embed and a programme archive that retains previous published episodes when new episodes go live.
3. Assess editorial override states such as `automatic` and `override_active`, plus transitions `set_override`, `clear_override`, `override_target_unavailable`; guards may include publication/access checks, role permissions and programme association. Define fallback if a featured item is unpublished, withdrawn or deleted. No state labels are final until the domain review.
4. Define whether highlights eligible for an override must have their own records, episode association or on-site presentation; do not create a clip CPT merely from the ability to feature a clip.
5. Confirm the archive sort semantics (actual air date vs WordPress publish date), whether future scheduled episodes can be listed separately, and whether a pinned feature changes archive ordering. The owner has **not** specified these details yet.
6. Verify against live/local runtime, current repository architecture and existing issue ownership before calling any portion complete. Treat all previously described local `uitsending` records and Bricks templates as **inspection leads**, not proof.

### 8.4 New cumulative findings and traceability

| Finding ID | Classification | Statement | Supersedes / links |
|---|---|---|---|
| F-13 | `OWNER-CONFIRMED TO-BE` | The programme page should dynamically show the latest published Twintig Twintig episode and an archive. | Resolves `F-12 OPEN` in the earlier v0.1.0 snapshot; S1-Q08. |
| F-14 | `OWNER-CONFIRMED TO-BE` | Editorial staff can optionally override the featured item with another episode or a highlight. | Adds an explicit editorial curation requirement; S1-Q08. |
| F-15 | `TECHNICALLY UNVERIFIED` | Existing VG Nova features may partially support the requested behaviour, but no implementation/acceptance assertion can be made without fresh verification. | Continues `F-11` and verification tasks `TV-01` to `TV-04`. |
| Q-09 | `OPEN — NEXT` | Does Twintig Twintig organise episodes into formal seasons, or is it one continuous programme with a chronological episode series? | Continue from the original interview backlog's S1-Q09. |

**VGN-56:** [Foundation requirements and owner-reported interview decisions](https://app.plane.so/symphony-development/browse/VGN-56). The current `S1-Q08` interview result is a business decision; later engineering acceptance must link the actual ACF/Bricks/WordPress evidence to the same Plane record.

### 8.5 Document revision history — appended amendment

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.1.1 | 2026-11-21 | Record the owner-submitted S1-Q08 answer; distinguish current, requested and unverified implementation; append F-13–F-15 and next S1-Q09 | Supersedes the earlier *open status* of S1-Q08 and F-12 for current planning only, without deleting the prior historical record |

**End of v0.1.1. Next owner question: S1-Q09 (seasons and episode sequencing).**


---

## 9. Version 0.1.2 — Owner-submitted Round 9: formal season identities and episode numbering

**Recorded:** 21 November 2026 (Africa/Johannesburg)
**Entry ID:** `S1-Q09`
**Decision scope:** Twintig Twintig / Voelgoed LIVE programme-episode grouping and season identity
**Evidence class:** `OWNER-CONFIRMED — TO-BE BUSINESS REQUIREMENT`
**Implementation class:** `TECHNICALLY UNVERIFIED — NO IMPLEMENTATION CLAIM OR AUTHORITY`
**Prior state:** `S1-Q09` was shown as `OPEN / NEXT` in earlier register versions and interview backlog. This new entry resolves that question for the business target without erasing its earlier status.

### 9.1 Question, options and actual owner response

**Question asked:** “How should Twintig Twintig episodes be organised?”

**Options presented:**

1. Continuous chronological programme without formal seasons.
2. Annual seasons, one season per calendar year.
3. **Formal seasons** with explicit season identity and episode numbering.
4. Flexible: Twintig Twintig continuous, with formal seasons for other Voelgoed LIVE programmes.
5. Other arrangement.

**Owner's actual submitted response (verbatim):**

> VG Nova Grilling Session 1 — Round 9: Seasons and episode organisation.
> Desired structure: Formal seasons with explicit season identity and episode numbering.
> Record this in Plane VGN-56 and the next versioned cumulative Markdown register, distinguish owner requirements from technical verification, and continue with the next single focused grilling question.

**Confirmed TO-BE requirement:** Twintig Twintig must support distinct, explicitly identifiable **seasons**, and episodes must have meaningful **episode numbering** within the programme's season structure. A single undifferentiated chronological list does **not** satisfy the target design.

**Not decided by this answer:** Whether seasons follow calendar years, topic/theme, production blocks or manual editorial boundaries; whether numbering restarts each season; mandatory season names vs labels/numbers; how specials/bonus episodes are numbered; whether episodes can change seasons; season landing pages and URL structures; whether other Voelgoed LIVE programmes must use the same season model.

### 9.2 Cumulative interpretation and separations

| Aspect | Evidence class | Finding or remaining question |
|---|---|---|
| Legacy site workflow | `OWNER-CONFIRMED AS-IS` (S1-Q07) | Existing staff update a single recurring programme page; no actual production season-data structure is established by that answer. |
| Target programme / episode | `OWNER-CONFIRMED TO-BE` (S1-Q07) | Permanent programme hub and permanent, individually addressable episode records. |
| Target programme hub | `OWNER-CONFIRMED TO-BE` (S1-Q08) | Latest published episode and archive by default, with optional featured override. |
| Target season organisation | `OWNER-CONFIRMED TO-BE` (S1-Q09) | Formal, explicitly identified seasons and episode numbers for Twintig Twintig. |
| Season boundary and numbering rules | `OPEN` | Exact season creation, closure, numbering reset, special episodes and change rules not specified. |
| Current VG Nova data support | `TECHNICALLY UNVERIFIED` | Audit current `uitsending` CPT registration/ACF, Bricks programme templates, WordPress entries and episode ordering before proposing changes. |
| Technical architecture approval | `NOT GRANTED` | Do not create season CPTs, taxonomies, ACF fields or URLs merely from this answer. Determine whether existing canonical structures already support the requirement. |

### 9.3 Season lifecycle and invariants — questions to validate, not approved implementation

Because **season** now has meaningful business identity, its lifecycle needs explicit guards and side effects before engineering approval. Consider candidate phases `planned`, `open_for_episodes`, `completed`, `archived`, with potential actions `create`, `open`, `close`, `reopen`, `archive`. These are **not** owner-approved enum values, transitions or persistence rules.

Questions that must be resolved before implementation include:

- **Identity:** What makes one formal season different from another (production block, year, theme or editorial decision)? Must it have an explicit name, number and ordering position?
- **Boundary/ownership:** Who may start or finish a season? Can a new episode be assigned only to an open season? Can an archived season be edited?
- **Episode numbering:** Does episode #1 restart at each new season, or remain continuous across the programme? Is a number unique within a season? How are specials, trailers and bonus episodes handled?
- **Publishing:** May an episode go public before the season is active or after a season is closed? Does a completed season remain browseable and searchable for everyone?
- **Mutations:** What happens if an already-published episode is moved to another season or renumbered? Preserve stable public URLs and SEO until a redirect/compatibility plan is verified.
- **Side effects:** Programme hub, season archive, episode sorting, cross-linking, sitemap/indexing and editorial override must update consistently without overwriting older episodes.
- **Terminal semantics:** Is `archived` merely a visibility/curation status or a permanent closure? Are deletion or unpublishing even allowed once episodes are distributed?

### 9.4 Technical verification additions, separate from business approval

| ID | Type | Verification work | Evidence required |
|---|---|---|---|
| TV-10 | `UNVERIFIED — inspect` | Inventory existing VG Nova season, series and episode representations (`uitsending`/ACF/taxonomies), including any season names and episode-number fields. | Current repo registration, WordPress schema, ACF definition and representative entries; no duplicate objects created. |
| TV-11 | `UNVERIFIED — design evaluation` | Compare the target formal-season structure with current canonical ACF and Bricks query capabilities; determine minimal data shape and authority. | Current authority references, design alternatives, implications for historic content and editorial control. |
| TV-12 | `UNVERIFIED — runtime` | Eventually exercise one programme with multiple formal seasons, numbered episodes, chronological programme hub and editor feature override. | Runtime/browser evidence and positive/negative cases only after the necessary implementation is authorised. |
| TV-13 | `UNVERIFIED — migration` | Map legacy episodes to seasons where records allow it, without inventing historic season labels/episode numbers. | Source inventory and explicit handling for unknown/unassigned legacy records; agreed URLs/redirects. |

No runtime test, implementation, new content type, migration or merge has been performed or approved as part of this business interview.

### 9.5 Updated cumulative findings and open question

| ID | Status | Record / traceability |
|---|---|---|
| F-16 | `OWNER-CONFIRMED TO-BE` | Twintig Twintig is structured into **formal seasons** with explicit season identity — `S1-Q09`. |
| F-17 | `OWNER-CONFIRMED TO-BE` | Episodes have explicit **episode numbering** in the formal season structure — `S1-Q09`; reset/uniqueness rules remain open. |
| F-18 | `TECHNICALLY UNVERIFIED` | Current VG Nova `uitsending`/ACF/Bricks implementation has not been inspected against these season requirements. |
| S1-Q09A | `OPEN — NEXT` | How does the editorial/business team decide when a new season begins and the previous one ends? The answer may determine whether seasons are fixed editorial runs, calendar-defined, fixed-episode blocks or another model. |
| S1-Q10 | `OPEN — FOLLOWING` | Which episode metadata do staff capture and need? (Preserved from original backlog; not superseded by Q09A.) |

### 9.6 Version history — appended amendment

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.1.2 | 2026-11-21 | Recorded S1-Q09 formal seasons and episode numbering; added F-16–F-18, TV-10–TV-13, lifecycle questions and the focused S1-Q09A follow-up | Closes previous `S1-Q09 OPEN` status, preserving original statements and all earlier interview answers. |

**End of v0.1.2. Next owner question: S1-Q09A (definition and closure of formal seasons).**


---

## 10. Version 0.1.3 — Round 9A confirmed answer (append-only amendment)

**Recorded:** 21 November 2026 (Africa/Johannesburg)
**Question ID:** S1-Q09A
**Authority:** OWNER-CONFIRMED TO-BE BUSINESS REQUIREMENT, not technical verification.
**Supersession:** S1-Q09A previously marked open in v0.1.2 is now answered; preserve that earlier question text as historical record.

### 10.1 Exact question and owner response

**Question:** "What determines when a Twintig Twintig season begins and ends?"

**Options offered:** Editorially defined; calendar-based; production-based/episode count; flexible by programme; other.

**Owner-submitted answer:**

> VG Nova Grilling Session 1 — Round 9A: Formal season boundaries.
> Owner-selected boundary rule: Editorially defined season starts and endings.
> Record this as an owner-confirmed business requirement in VGN-56 and the next cumulative versioned Markdown register. Keep technical implementation unverified, preserve earlier decisions, and continue with one focused question.

**Owner-confirmed rule:** The Twintig Twintig editorial/production team determines when each formal season starts and ends. Season boundaries must not be inferred automatically solely from the calendar year or a fixed episode count. This is a choice of editorial authority, not yet a defined WordPress permission role, UI action, or technical workflow.

### 10.2 Explicit lifecycle governance — open design decisions

The season is a meaningful lifecycle concept and must ultimately have explicitly defined **states, transitions, guards, side effects and terminal states**. The following is a DESIGN CHECKLIST, not an approved enum or implementation:

- **Candidate states to validate:** planned/draft, active/open, completed/closed, and possibly cancelled/archived. Exact terminology and whether these are needed remain undecided.
- **Candidate transitions to validate:** create season; publish/open season; editorially close season; reopen/correct a closed season (if allowed); cancel/archive.
- **Candidate transition guards to validate:** who may authorise a start/end; whether a season can overlap with another; what happens when the season contains scheduled, draft, or unpublished episodes; whether published episodes may be reassigned.
- **Candidate side effects to validate:** programme hub season navigation, latest-episode selection, archive grouping, ordering and numbering, stable permalink and SEO, and whether closing a season changes public access (no such change authorised).
- **Terminal states to decide:** does closed mean terminal? Can editors reopen a season for corrections? How do cancellation, removal and restoration affect episode visibility?

### 10.3 Requirements and technical verification separation

| ID | Classification | Finding / needed check |
|---|---|---|
| F-19 | OWNER-CONFIRMED TO-BE | Twintig Twintig formal season boundaries are editorially decided, not automatically determined by year or episode quota. |
| F-20 | OPEN BUSINESS RULE | Decide whether episode numbering restarts at 1 in each new season, runs continuously across seasons, or uses another scheme. |
| TV-14 | RUNTIME UNVERIFIED | Inspect current VG Nova programme/season/episode entities, ACF fields and Bricks templates for independent editorial start/end controls. |
| TV-15 | RUNTIME UNVERIFIED | Verify draft, scheduled and published episodes stay linked to the correct season and display consistently after season closing, reopening or changes. |
| TV-16 | GOVERNANCE UNDECIDED | Identify editorial permission/authority, collision rules, numbering uniqueness, season archive URLs and the permitted season lifecycle. |

**No implementation approval:** Do not add season CPTs, taxonomy terms, ACF field definitions, custom code, migration logic, redirects or production changes based solely on this answer. Inspect existing architecture first.

### 10.4 Next single focused owner question — S1-Q09B (OPEN)

**Does Twintig Twintig episode numbering restart at Episode 1 in every season, continue globally across all seasons, or follow another numbering convention?**

This is separate from the confirmed editorial authority to choose season boundaries. Following this answer, return to the wider interviewing roadmap (original S1-Q10 episode metadata/people and publishing questions), avoiding unnecessary tangents.

### 10.5 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.1.3 | 2026-11-21 | Confirmed S1-Q09A editorial season start/end boundaries; added F-19–F-20, TV-14–TV-16, unresolved season-lifecycle rules and S1-Q09B numbering question. | Closes previous S1-Q09A OPEN status while retaining every earlier answer. |

**End of v0.1.3. Next owner question: S1-Q09B (episode numbering across seasons).**


---

## 11. Version 0.1.4 — Round 9B confirmed answer (append-only amendment)

**Recorded:** 21 November 2026 (Africa/Johannesburg)
**Question ID:** S1-Q09B
**Authority:** OWNER-CONFIRMED TO-BE BUSINESS REQUIREMENT; current technical implementation unverified.
**Supersession:** Closes S1-Q09B OPEN and the numbering question in F-20; earlier entries remain historical evidence. All other unresolved decisions remain open.

### 11.1 Exact question and owner response

**Question:** "How should episode numbers work across seasons?"

**Options offered:** Restart each season; continuous numbering; both season-specific and programme-wide identifiers; different or flexible numbering rules.

**Owner-submitted answer:**

> VG Nova Grilling Session 1 — Round 9B: Episode numbering across formal seasons.
> Owner-selected numbering policy: Episode numbering restarts at 1 within every season.
> Record in Plane VGN-56 and the next cumulative Markdown register version, preserving all prior answers and separating confirmed requirements from technical verification. Continue with the next single focused question.

**Owner-confirmed rule:** Each formal Twintig Twintig season has its own episode-number sequence beginning at 1. Season 1 Episode 1 and Season 2 Episode 1 are valid distinct episodes. A continuous programme-wide episode number is not required by this answer. This does not establish automatic number allocation, gap handling, numbering of specials, or a physical database identifier.

### 11.2 Consequences and constraints to validate

- Keep the confirmed programme hub, individual permanent episode pages, chronological archive, optional editorial feature override and editorially defined season boundaries (Q07–Q09A).
- Candidate invariant: normal episode numbers are positive integers unique within a given season, not globally unique across all seasons. Confirm exceptions, specials and the scope of uniqueness before implementation approval.
- Separate an episode's stable record identity and public permalink from its mutable display number. Confirm permissions, corrections, reassignment and SEO consequences before allowing renumbering or movement between seasons.
- Do not infer numbers for old recordings, automatically fill gaps, recycle cancelled numbers, or allocate a number from the current count of episodes without a defined policy. Confirm editorial workflow and collision/concurrency rules before choosing an allocation mechanism.
- Season and episode lifecycle states, permitted transitions, guards, side effects, and terminal semantics remain outstanding design decisions; the numbering answer does not approve state enums, closure/reopening rules or access changes.

### 11.3 Cumulative findings and technical verification additions

| ID | Classification | Record / evidence required |
|---|---|---|
| F-20 | OWNER-CONFIRMED TO-BE (supersedes OPEN) | S1-Q09B: episode numbering restarts at 1 in every season. |
| F-21 | OPEN BUSINESS RULE | Allocation timing, gaps, duplicate handling, special episodes, corrections and reassignment remain unspecified. |
| TV-17 | UNVERIFIED — inspect | Determine whether current VG Nova season/episode representation, ACF fields and Bricks output support season-local numbers; inspect repository and runtime configuration rather than assuming fields exist. |
| TV-18 | UNVERIFIED — design/runtime | After design and implementation approval, verify two seasons can each contain Episode 1, regular episode number conflicts are handled under the approved rules, and archive sorting, programme hub and visible episode labels remain correct. Verify published links remain stable during permitted corrections. |
| TV-19 | UNVERIFIED — migration | Reconcile legacy episodes against reliable season and numbering evidence; flag unknown or conflicting records, never fabricate numbers. |

**Implementation status:** No runtime checks, new CPTs, taxonomies, ACF fields, code, permissions, migrations, redirects or deployment are approved or represented as completed by this answer. The repository and current runtime must still be verified against the existing authorities; draft PR #97 is not merge-approved.

### 11.4 Next single focused owner question — S1-Q10 (OPEN)

**When an editor creates a Twintig Twintig episode, which descriptive and media information should they capture on the episode record?**

Ask the owner about a proposed minimum editorial metadata set: title, short description/summary, broadcast date/time, YouTube replay reference, cover/thumbnail, programme/season and episode number (already confirmed concepts). Optional metadata candidates: longer show notes, transcript, time-coded highlights, topic classifications, related products/content and sponsor information. No suggested metadata is an approved new field merely because it appears here. Hosts, guests and contributor roles will receive their own dedicated S1-Q11 question.

### 11.5 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.1.4 | 2026-11-21 | Confirmed S1-Q09B season-local numbering; added F-20 confirmation, F-21, TV-17–TV-19 and next S1-Q10 metadata question. | Previous S1-Q09B OPEN and F-20 OPEN numbering classification only. All earlier answers retained. |

**End of v0.1.4. Next owner question: S1-Q10 (episode metadata).**


---

## 12. Version 0.1.5 — Round 10 confirmed answer (append-only amendment)

**Recorded:** 21 November 2026 (Africa/Johannesburg)
**Question ID:** S1-Q10
**Authority:** OWNER-CONFIRMED TO-BE BUSINESS REQUIREMENTS; technical implementation unverified.
**Supersession:** Resolves the previous S1-Q10 OPEN classification only; all previous answers and outstanding decisions are preserved.

### 12.1 Question and exact owner-submitted answer

**Question:** "What information should editors capture for every Twintig Twintig episode?"

**Owner-submitted answer:**

> VG Nova Grilling Session 1 — Round 10: Twintig Twintig episode metadata requirements.
> - Episode title: required
> - Short description / summary: required
> - Broadcast date and time: required
> - YouTube replay URL: required
> - Featured image / thumbnail: required
> - Programme, season and episode number: required
> - Longer show notes: optional
> - Transcript: not_needed
> - Highlights / short clips: optional
> - Editorial topics / categories: optional
> - Related articles or products: optional
> - Sponsor / advertiser information: optional
>
> Record all answers in Plane VGN-56 and the next cumulative Markdown register. Preserve all historical decisions, identify required versus optional fields, separate business requirements from the current technical implementation and continue with one focused question.

### 12.2 Confirmed requirements matrix

| ID | Metadata concept | Owner-selected status | Boundary / interpretation |
|---|---|---|---|
| EM-01 | Episode title | REQUIRED | A meaningful title; not a substitute for stable record identity. |
| EM-02 | Short description / summary | REQUIRED | Concise editorial description; reuse native excerpt if proven suitable. |
| EM-03 | Broadcast date and time | REQUIRED | Actual and/or planned time semantics, timezone handling and corrections remain to be defined. Do not conflate with WordPress publication timestamp. |
| EM-04 | YouTube replay URL | REQUIRED | Required business metadata; enforcement stage for upcoming/live versus replay-ready episodes remains open. |
| EM-05 | Featured image / thumbnail | REQUIRED | Reuse native featured image if suitable; image ownership and fallback policy unverified. |
| EM-06 | Programme, season and episode number | REQUIRED | Each concept required; Q02, Q09, Q09A and Q09B remain authoritative. Do not assume one compound field or global unique episode number. |
| EM-07 | Longer show notes | OPTIONAL | Not a publication prerequisite; assess native editor/body. |
| EM-08 | Transcript | NOT NEEDED | No dedicated transcript requirement for this offering in current scope. Not an instruction to delete old data or abandon accessibility/caption obligations. |
| EM-09 | Highlights / short clips | OPTIONAL | Episode may have no highlights. Existing mixed social/site distribution confirmed in Q04. |
| EM-10 | Editorial topics / categories | OPTIONAL | Reconcile with canonical content_topic authority before choosing classification implementation; no additional taxonomy approved. |
| EM-11 | Related articles or products | OPTIONAL | Reuse existing content and commercial relationships where supported; no duplication of product records. |
| EM-12 | Sponsor / advertiser information | OPTIONAL | Must not require free editorial episodes to have a sponsor. Existing Partner model is a verification lead, not an approved mapping; actual disclosure requirements remain open. |

Six interview items required (EM-06 contains three logical concepts), five optional items, one not needed. This is a business information contract, **not a count or specification of ACF fields**. Hosts and guests were deliberately excluded pending S1-Q11.

### 12.3 Lifecycle and acceptance questions

The owner has confirmed what information is required, but has not yet determined at which lifecycle stage each requirement is enforced. A replay URL may not exist when an upcoming episode is first prepared. Therefore:

- Clarify whether staff publish upcoming/live episode pages before the replay exists, or only publish the episode page after the recording is available.
- Candidate episode states for later review: draft/preparation, scheduled/upcoming, live, replay pending, replay available, withdrawn/cancelled. These are not approved database enums. Separate editorial publication status from broadcast/replay availability and PMPro access policy.
- Define allowed state transitions, authorisation and validity guards, side effects (hub/latest selection, archive, notifications, embeds and indexing), recovery paths and terminal states before approving implementation. Do not treat a missing replay during preparation as proof of invalid data.
- A replay-ready public episode should satisfy the owner-confirmed required metadata under the eventual approved validation contract. Optional data absent must not introduce empty sections, broken links or artificial publication blockers.
- Preserve existing data when mapping; missing required legacy values must become migration exceptions rather than fabricated timestamps, numbers or YouTube links. A transcript being not needed does not authorise destructive removal.

### 12.4 Cumulative findings and technical verification additions

| ID | Classification | Finding / verification requirement |
|---|---|---|
| F-22 | OWNER-CONFIRMED TO-BE | Required metadata: title, summary, broadcast date/time, YouTube replay URL, image, programme, season, episode number. |
| F-23 | OWNER-CONFIRMED TO-BE | Optional: longer show notes, highlights, editorial topics, related articles/products, sponsor information. |
| F-24 | OWNER-CONFIRMED NOT NEEDED | Transcript is outside current required/optional episode metadata scope. |
| F-25 | OPEN BUSINESS RULE | When required metadata becomes mandatory; whether upcoming/live pages precede the replay; role-specific publishing permissions. |
| TV-20 | UNVERIFIED — inspect | Inventory actual `uitsending` registration, native WP fields, ACF JSON/config, taxonomy and relationship consumers, and Bricks output; map each EM item without creating duplicate fields. |
| TV-21 | UNVERIFIED — contract/runtime | Once lifecycle and design are approved, test required-metadata validation at each permitted transition, absent optional metadata, correct season-local numbering, YouTube playback, thumbnail, valid publication and programme archive/featured fallback. |
| TV-22 | UNVERIFIED — migration | Profile historical episodes against all required concepts; identify missing/conflicting values and source evidence; record exceptions without inventing data. |
| TV-23 | UNVERIFIED — governance | Verify existing related content/product, Partner/sponsor and canonical editorial-topic relationships against authoritative rules. Confirm optional transcript exclusion is not a deletion or accessibility waiver. |

No application code, ACF fields, taxonomies, CPTs, runtime mutation, migration, deployment or merge has been approved or verified by this answer.

### 12.5 Next single focused owner question — S1-Q11 (OPEN)

**How should presenters and guests be linked to an individual Twintig Twintig episode?**

Determine whether the normal structure uses one presenter, multiple presenters/co-hosts and multiple guests, with each participant linked to a reusable canonical Person record when available, rather than duplicating names/biographies per episode. Clarify if episode participants without existing profiles (e.g. once-off guests) require reusable person records or another workflow. The owner has not yet approved any participant cardinality, role model or mandatory contributor field. Existing Person authority must be verified before technical design. Leave the separate upcoming/replay publication-stage question on the backlog (F-25).

### 12.6 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.1.5 | 2026-11-21 | Confirmed S1-Q10 metadata matrix EM-01–EM-12; appended F-22–F-25, TV-20–TV-23 and next S1-Q11 contributor question. | Previous S1-Q10 OPEN status only; all earlier answers retained. |

**End of v0.1.5. Next owner question: S1-Q11 (presenters and guests).**


---

## 13. Version 0.1.6 — Round 11 confirmed answer (append-only amendment)

**Recorded:** 21 November 2026 (Africa/Johannesburg)
**Question ID:** S1-Q11
**Authority:** OWNER-CONFIRMED TO-BE BUSINESS REQUIREMENT; technical implementation unverified.
**Supersession:** Closes the previous S1-Q11 OPEN participant-cardinality question only. All earlier responses, unresolved decisions and technical verification requirements remain intact.

### 13.1 Exact question and owner response

**Question:** "How should presenters and guests be assigned to a Twintig Twintig episode?"

**Options offered:** One presenter and multiple guests; multiple presenters and multiple guests; default programme presenters with episode overrides; fully flexible participant roles; another arrangement.

**Owner-submitted answer:**

> VG Nova Grilling Session 1 — Round 11: Episode presenters, guests and contributor roles.
> Owner-selected structure: Multiple presenters and multiple guests per episode.
> Record in Plane VGN-56 and the next cumulative Markdown register version. Preserve all earlier answers, distinguish business requirements from technical verification, and continue with one focused question.

### 13.2 Confirmed requirements and boundaries

- An individual Twintig Twintig episode must support multiple presenters and multiple guests. The presenter and guest assignments are distinguishable on the episode.
- "Multiple" means the system must accommodate more than one of each; no minimum, maximum or mandatory non-empty cardinality was specified. A minimum of two presenters or two guests must not be inferred.
- This answer does not approve programme-level presenter inheritance, episode overrides of inherited assignments, flexible additional contributor roles, display ordering, whether a person may hold both roles on the same episode, or a specific ACF implementation.
- The interviewer's reusable canonical Person relationship and context-specific roles proposal remains a design direction to reconcile with existing project authority. The owner's answer confirms episode-level participant structure, not a new Person schema, compulsory public profiles, or approval to create new CPTs, taxonomy terms or relationship fields. Check the exact existing Person model and its governance before proposing implementation.
- Retain S1-Q10 metadata requirements without silently converting presenter/guest assignments into mandatory publishing fields. Minimum participant counts and the lifecycle stage at which assignments become required are open.

### 13.3 Lifecycle and identity questions carried forward

The existing Person identity and an episode-specific participant assignment must not be conflated without examining the current implementation. If an independent assignment concept is warranted, model its states, permitted transitions, authorisation, validity guards, side effects and terminal semantics before implementation. Candidate cases to evaluate are assigning an existing person, correcting a mistaken identity or role, changing a presenter before broadcast, removing an unconfirmed participant and preserving historical published credits. No lifecycle enum or permission rule is approved here.

Do not create duplicate people by default, force once-off guests to have public pages without a confirmed business rule, or remove historical episode credits simply because a person becomes inactive. Identity matching, public visibility, consent/privacy, relationship ordering, corrections and decommissioning remain open.

### 13.4 Cumulative findings and technical verification additions

| ID | Classification | Finding / evidence required |
|---|---|---|
| F-26 | OWNER-CONFIRMED TO-BE | Twintig Twintig episodes support multiple presenters and multiple guests, with distinguishable assignments. |
| F-27 | OPEN BUSINESS RULE | Minimum and maximum counts, role overlap, display order, presenter inheritance, new/once-off guest identity workflow and profile visibility remain undecided. |
| TV-24 | UNVERIFIED — inspect | Inspect exact current Person authority, `uitsending` registration, ACF relationship fields, existing relationship/role representation, Bricks consumers and any existing canonical participant-related architecture. Reuse supported structures rather than duplicating them. |
| TV-25 | UNVERIFIED — contract/runtime | After approval, demonstrate that a single episode can associate and correctly display at least two distinct presenters and two distinct guests. Verify reusable people across episodes, no role confusion, correct permissions and the approved minimum-count/validation policy. Test duplicate or conflicting assignments, corrections and missing/withdrawn people against the eventual approved contract. |
| TV-26 | UNVERIFIED — migration | Reconcile existing historical presenter/guest credits with canonical identity evidence. Flag ambiguous identity matches and missing records; do not silently merge distinct people, fabricate identities or discard historical credits. |

**Implementation status:** No new Person fields, CPTs, taxonomies, relationship records, lifecycle states, permissions, runtime mutations, migrations, deployments or merges were approved or verified by this answer.

### 13.5 Next single focused owner question — S1-Q11A (OPEN)

**When a once-off Twintig Twintig guest has no existing Person record, should staff always create a reusable Person record, capture an episode-only guest credit, or decide case by case?**

This is a question about canonical identity reuse and editorial workflow, not whether every person must have a public profile. Profile visibility is a separate rule and must not be inferred. Preserve the confirmed multiple-presenter/multiple-guest capability, and revisit the minimum participant count and publication lifecycle at the appropriate focused interview stages.

### 13.6 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.1.6 | 2026-11-21 | Confirmed S1-Q11 multiple presenters and multiple guests; appended F-26–F-27, TV-24–TV-26 and focused S1-Q11A about once-off guest identity. | Previous S1-Q11 OPEN cardinality classification only; all earlier answers retained. |

**End of v0.1.6. Next owner question: S1-Q11A (once-off guest identity).**


---

## 14. Version 0.1.7 — Round 11 clarification (append-only amendment)

**Recorded:** 21 November 2026 (Africa/Johannesburg)
**Question ID:** S1-Q11A (owner clarification supplied before formal follow-up)
**Authority:** OWNER-CONFIRMED TO-BE business direction and owner-reported work-in-progress; technical implementation independently unverified.

### 14.1 Exact owner clarification

> Assigned and added via my person's (Mense) setup already started building on VGNova

Read together with S1-Q11 ("Multiple presenters and multiple guests per episode"), the owner directs that episode presenters and guests be added and assigned using the existing VG Nova Mense (Person) setup. Do not introduce a parallel broadcast-only people-management system. The owner reports that this setup has already been started on VG Nova; this is a report of implementation progress, not independent evidence that the current broadcast integration functions.

### 14.2 Confirmed direction and remaining boundaries

- Use the existing Mense/Person authority for creating and assigning episode participants; associate multiple presenters and multiple guests with an individual episode using distinguishable roles.
- Treat the existing setup as the starting point for inspection, not a mandate to create new Person fields or another people CPT. Map actual existing Person identifiers, relationships, role semantics, editor experience and consumers before proposing any delta.
- The intended workflow covers adding new people through Mense and selecting existing people where available. Whether rare exceptions are permitted for a once-off guest, and whether all such records require public profile pages, were not expressly specified. Do not assume compulsory public profiles or authorise a separate free-text identity store from that ambiguity.
- Preserve the existing architecture's scope, including Person reuse across broadcasts and other domains where supported. Role cardinality, display order, programme defaults, visibility, identity matching, correction and historic credits remain governed by later approved rules. Do not create duplicate people or infer that all participant categories are mandatory.
- Existing S1-Q10 required metadata and S1-Q11 cardinality decisions remain unchanged. No runtime validation, field creation, data mutation, migration or merge approval is conferred.

### 14.3 Findings and verification

| ID | Classification | Finding / evidence required |
|---|---|---|
| F-28 | OWNER-CONFIRMED TO-BE | Add and assign presenters and guests through the existing Mense (Person) setup. No parallel broadcast-only people system is requested. |
| F-29 | OWNER-REPORTED AS-IS PROGRESS | Mense has already been started on VG Nova; completeness and broadcast integration remain independently unverified. |
| F-27 | PARTIALLY RESOLVED | Mense is the participant identity workflow; precise public profile policy, rare once-off exceptions, minimum counts and role/display governance remain open. |
| TV-27 | UNVERIFIED — inspect | Read current repository and Mense/Person authority references, actual ACF registrations/relationship definitions and relevant plugins; inspect real editor workflow and Bricks consumers. Establish what exists, what is missing and whether any legacy competing person model still has consumers. |
| TV-28 | UNVERIFIED — runtime | Following approved design, demonstrate creating a new person through Mense, selecting an existing person, assigning multiple presenters and guests to an episode, and rendering the correct roles without duplicate person records. Check identity reuse, permissions, visibility and preservation of historical credits against approved rules. |

### 14.4 Next single focused owner question — S1-Q12 (OPEN)

**Should an individual Twintig Twintig episode page be published publicly before its broadcast, or only after the replay is ready?**

Possible owner choices: only after the replay is ready; publish an upcoming episode page before broadcast and later add the replay; public upcoming page with an embedded live stream when available and subsequent replay; flexible or other arrangement. This resolves the outstanding S1-Q10/F-25 lifecycle question about when the YouTube replay URL is required. The options are not approved state enums. Actual states, transitions, permissions, guards, side effects, cancelled/postponed and withdrawn states, and archive/featured selection rules require subsequent design approval.

### 14.5 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.1.7 | 2026-11-21 | Recorded owner clarification that participants are added and assigned through the existing Mense setup; appended F-28–F-29, partial F-27 resolution, TV-27–TV-28, and S1-Q12. | S1-Q11A's unresolved integration direction only; prior answers and remaining identity/profile questions preserved. |

**End of v0.1.7. Next owner question: S1-Q12 (upcoming publication versus replay availability).**


---

## 15. Version 0.1.8 — Round 12 confirmed answer (append-only amendment)

**Recorded:** 21 November 2026 (Africa/Johannesburg)
**Question ID:** S1-Q12
**Authority:** OWNER-CONFIRMED TO-BE BUSINESS REQUIREMENT; technical implementation independently unverified.
**Supersession:** Closes S1-Q12 OPEN. Partially resolves the F-25 metadata-enforcement question from Round 10: a public upcoming episode page must be supported before a replay is available. Exact validation guards for every transition still require approval. All previous interview answers, open questions and verification obligations remain intact.

### 15.1 Exact question and owner-submitted answer

**Question:** "Should individual episode pages already be publicly available before the broadcast takes place?"

**Options offered:** Replay only; upcoming plus replay; upcoming plus live plus replay; flexible editorial choice; other arrangement.

**Owner-submitted answer:**

> VG Nova Grilling Session 1 — Round 12: Episode publishing lifecycle.
> Owner-selected workflow: Permanent episode page supporting upcoming promotion, live broadcast embedding and subsequent replay.
> Record in Plane VGN-56 and the next cumulative Markdown register. Preserve earlier decisions, distinguish business requirements from technical verification, and continue with one focused question.

### 15.2 Confirmed business workflow and requirements

- An individual Twintig Twintig episode has a permanent page supporting upcoming promotion, embedded live broadcast when available, and subsequent replay. These are successive public experiences of the same episode, not three separately created episode records or URLs.
- The upcoming page can present the episode's title, summary, broadcast date/time, featured image and programme/season/episode identity, along with assigned Mense presenters and guests as available under the eventual validation policy.
- The selected workflow requires support for upcoming public publication before the replay is available. The Round 10 required replay URL (EM-04) remains required replay metadata; it cannot be an unconditional replay-availability prerequisite for publishing the public upcoming experience. A scheduled YouTube live URL may already exist, but the user has not specified a required live URL field, one shared media identifier, or a specific platform/API for live embedding.
- Continue to honour the confirmed Restream distribution workflow, YouTube-hosted replay, public/free current access policy, programme/season/episode associations, season-local numbering, individual episode pages and optional editorial override on the programme hub.
- This decision does not require all episodes to be advertised a fixed time before broadcast, a countdown, automated video-provider integrations, notifications, automatic publication, automatic live detection or manual switches. Those details were not selected by the owner.
- Programme-hub priority when an upcoming or live episode exists, alongside the latest replay and editorial override, remains open. Do not silently reinterpret S1-Q08's latest-published episode/archive rule.

### 15.3 Candidate lifecycle contract — NOT approved implementation states

Distinguish editorial publication/visibility, broadcast progression, playback availability and access entitlement. These are separate concerns; a WordPress publication status alone cannot prove a live stream is available or a recording is ready. The following is an interview-derived candidate for future architectural validation, not a mandate to register enums, create fields or automatically transition records.

| Candidate business stage | Meaning | Candidate guard / permissible transition | Candidate side effect |
|---|---|---|---|
| Preparation | Staff prepare the episode record before public promotion. | Authorised editor; minimum draft identity requirements to be determined. May proceed to upcoming or be abandoned under approved rules. | No unintended public listing or playback. |
| Upcoming | Public permanent episode page presents a future broadcast. | Valid confirmed episode identity and appropriate promotion metadata; no replay availability prerequisite. | Display upcoming information and accurate schedule on the same episode URL. |
| Live | Broadcast is taking place and an embeddable live stream is available where supported. | Require trustworthy confirmation under the eventual manual or automated transition policy; scheduled time alone does not establish actual live playback. | Display live experience without changing episode identity or URL. |
| Replay pending | Broadcast has ended but an accessible replay is not yet available. | Confirm end of broadcast; do not assert recording availability. | Show an accurate pending experience; do not render a broken player or claim the episode is live. |
| Replay available | Completed episode recording is available for public viewing. | Valid YouTube replay reference and approved required metadata/availability checks. | Render replay on the same permanent page; eligible for replay archive under approved rules. |
| Cancelled / withdrawn / postponed | Exceptional lifecycle or visibility change. | Reasons, authorised roles, allowed source states, rescheduling/reinstatement, historical preservation and visibility rules remain unapproved. | Suppress misleading live/replay claims; retain or hide public information according to an eventual approved policy. |

The main progression candidate is preparation -> upcoming -> live -> replay pending -> replay available, with permissible skips/recovery paths still to be defined (e.g. no embeddable live stream, delayed recording, failed provider). Do not infer live or replay completion from timestamps alone. Replay availability is an end stage of the normal broadcast progression, not proof that editorial corrections or subsequent withdrawal are prohibited. Exact terminal states, reopen/recovery semantics, actor authority, side effects, concurrency, archive membership, visibility, cancellation and postponement require separate decisions before implementation.

### 15.4 Findings and technical verification additions

| ID | Classification | Finding / evidence required |
|---|---|---|
| F-30 | OWNER-CONFIRMED TO-BE | One permanent individual episode page supports upcoming promotion, live embedding and subsequent replay. |
| F-25 | PARTIALLY RESOLVED | Upcoming public publication must not depend on replay availability. Exact required metadata enforcement per transition, live media references and transition authority remain open. |
| F-31 | OPEN BUSINESS RULE | Timing and authority for lifecycle transitions, delayed/failed playback, postponement/cancellation/withdrawal, and terminal/recovery policies. |
| F-32 | OPEN BUSINESS RULE | Programme hub priority and archive eligibility for upcoming, live and replay episodes, including interplay with S1-Q08 editorial override. |
| TV-29 | UNVERIFIED — inspect | Reconcile existing `uitsending`, native WordPress publication controls, ACF media/scheduling configuration, MU/custom plugin responsibilities and Bricks templates with the required permanent episode journey. Determine whether existing structures already support it and avoid duplication. |
| TV-30 | UNVERIFIED — contract/runtime | After design approval, test a public upcoming episode without an available replay, live embed when actually available, replay-pending fallback, and subsequent valid YouTube playback on the identical URL/record. Verify no duplicate records and no misleading playback or access status. |
| TV-31 | UNVERIFIED — contract/runtime | Under approved transitions and guards, test schedule changes, failed/unavailable live streams, delayed replay, cancellation/postponement, corrections, withdrawal, public access, cache/indexing and programme hub/featured behaviour. Confirm authorisation and stable episode identity. |
| TV-32 | UNVERIFIED — media governance | Establish real Restream/YouTube live/replay embed identifiers, permissions, provider dependencies and availability handling; do not assume Restream stores video or that a scheduled broadcast automatically becomes live. |

No new CPT, taxonomy, field, state machine, external integration, runtime mutation, migration or merge approved by this interview answer. Verify exact current repository and runtime and existing authorities first.

### 15.5 Next single focused owner question — S1-Q12A (OPEN)

**What should the main Twintig Twintig programme page feature when an upcoming or currently live episode exists alongside the latest replay?**

Options for discussion: (1) keep the latest replay featured and show upcoming/live separately; (2) automatically prioritise live, then next upcoming, then latest replay; (3) prioritise live, otherwise retain latest replay and show upcoming separately; (4) another rule. Preserve the already confirmed optional editorial override; its interaction with a live broadcast and archive eligibility may need further clarification. Ask about the default featured experience, not a new CPT or schema.

### 15.6 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.1.8 | 2026-11-21 | Recorded S1-Q12 permanent upcoming/live/replay episode journey; added F-30–F-32, partial F-25 resolution, candidate lifecycle discussion, TV-29–TV-32 and S1-Q12A. | Previous S1-Q12 OPEN and unconditional replay-availability ambiguity only; all previous answers preserved. |

**End of v0.1.8. Next owner question: S1-Q12A (programme hub featured priority).**


---

## 16. Version 0.1.9 — Round 12A confirmed answer (append-only amendment)

**Recorded:** 21 November 2026 (Africa/Johannesburg)
**Question ID:** S1-Q12A
**Authority:** OWNER-CONFIRMED TO-BE BUSINESS REQUIREMENT; technical implementation independently unverified.
**Supersession:** Closes S1-Q12A OPEN; clarifies the automatic feature selection aspect of S1-Q08 and F-32 while preserving the archive and optional editorial override requirements. Earlier answers and unresolved decisions remain intact.

### 16.1 Exact question and owner-submitted answer

**Question:** "What should the main Twintig Twintig programme page feature automatically?"

**Options offered:** Latest replay always featured with upcoming/live separately; dynamic priority live then upcoming then latest replay; live priority otherwise replay with upcoming separately; another arrangement.

**Owner-submitted answer:**

> VG Nova Grilling Session 1 — Round 12A: Programme hub featured priority.
> Owner-selected default priority: Dynamic priority: Live → Upcoming → Latest replay.
> Record in Plane VGN-56 and the next cumulative Markdown register. Preserve the earlier editorial override requirement and all previous decisions, distinguish business requirements from technical verification, and continue with one focused question.

### 16.2 Confirmed requirements

- The default automatic featured selection on the Twintig Twintig programme hub follows this strict category order: live first; upcoming second; latest replay third.
- When an eligible live episode exists, it has priority over an eligible upcoming episode or replay in automatic mode.
- If there is no eligible live episode, an eligible upcoming episode has priority over the latest eligible replay. If neither live nor upcoming is eligible, feature the latest eligible replay.
- Preserve S1-Q08's automatic episode archive and ability for editors to override the feature with another episode or highlight. This answer specifies the default automatic mode, not the precedence of overrides against live events, their duration, or the circumstances in which they expire or are suspended. Do not remove or silently redefine override capability.
- The owner has not requested a separate, always-visible latest-replay section; the interviewer's suggestion was not part of the selected answer. Archive membership, separate sections, layout and exact placement remain unapproved.
- Preserve the S1-Q12 single permanent episode record/page across upcoming, live and replay experiences, rather than creating records for distinct display states. Other earlier decisions, including seasons, episode numbering, Mense integration, Restream and YouTube media roles, and current free public access, remain unchanged.

### 16.3 Candidate selection contract — subject to architecture approval

Selection is a derived presentation decision, separate from the authoritative episode identity, editorial publication, playback availability and entitlement. The exact predicates for eligible live/upcoming/replay content still need design approval. Candidates must belong to the appropriate programme and be visible to the relevant audience under the approved publication and entitlement policy; an editor's choice must not bypass permissions.

| Automatic-mode situation | Expected featured category |
|---|---|
| Eligible live episode exists | Live |
| No eligible live episode; eligible upcoming episode exists | Upcoming |
| Neither eligible live nor upcoming episode exists; eligible replay exists | Latest replay |
| No eligible candidate in any category | OPEN — define accurate empty/fallback experience |

Within-category ordering remains open: handling simultaneous streams, multiple upcoming broadcasts, latest replay timestamp authority and deterministic ties. Do not choose silently between publication, scheduled broadcast, actual broadcast and replay-ready timestamps. An episode's scheduled start timestamp alone is not proof of live playback. A failed embed, cancelled event, stale schedule, withdrawn record or replay-pending episode requires explicit policy rather than automatic misclassification.

The eventual contract must specify trigger and actor authority, guards, side effects (including cache invalidation and changes to featured displays), timeout/recovery, failure and terminal conditions for relevant broadcast and override lifecycles. It must also define how feature recomputation reacts to lifecycle transitions without mutating episode identity or disrupting archive visibility. No status enum, scheduler, provider integration, ACF field, query implementation or data migration is approved here.

### 16.4 Findings and technical verification additions

| ID | Classification | Finding / evidence required |
|---|---|---|
| F-33 | OWNER-CONFIRMED TO-BE | Automatic programme hub feature priority: live, then upcoming, then latest replay. |
| F-32 | PARTIALLY RESOLVED | Default category priority confirmed. Archive eligibility and the interaction between an editorial override and a live broadcast remain open. |
| F-34 | OPEN BUSINESS RULE | Within-category ordering, overlapping broadcasts, replay recency, eligibility and empty/failure fallback behaviour. |
| TV-33 | UNVERIFIED — inspect | Inspect actual programme-hub templates, Bricks queries, existing feature/override and episode selection authorities, lifecycle/media eligibility data and cache consumers. Identify what is already supported without duplicating existing architecture. |
| TV-34 | UNVERIFIED — contract/runtime | Following approval, verify automatic selection of live over upcoming/replay, upcoming over replay absent live, and latest eligible replay absent both. Test publication/access filtering, overlap, tie-breaking, stale live status, provider failure and no eligible candidates against approved rules. |
| TV-35 | UNVERIFIED — contract/runtime | Verify that the existing archive and optional editorial override remain supported, with the eventual confirmed override precedence, expiry, cancellation and recovery semantics. Verify stable episode identity/URLs and accurate presentation during lifecycle transitions, including cache refresh. |

No application implementation, CPT, taxonomy, field, state machine, migration, decommissioning, deployment or merge is approved or verified. Before implementation, reconcile the actual repository and runtime with the existing authorities. Draft PR #97 remains documentation-only and not merge-approved.

### 16.5 Next single focused owner question — S1-Q12B (OPEN)

**When a live episode is actually being broadcast, should it always take the featured position, or may an active editorial override continue featuring another episode or highlight?**

Clarify the precedence decision independently of the default automatic ordering. Suggested choices: live always temporarily supersedes the override, which resumes if still valid once the live broadcast ends; valid editorial override always takes priority, even during live; explicit per-override ability to supersede a live broadcast; another rule. The owner's selection should not be inferred from the earlier automatic-mode priority. Override duration/expiry, permission and invalid-target handling remain open unless explicitly addressed.

### 16.6 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.1.9 | 2026-11-21 | Confirmed S1-Q12A automatic programme hub display priority; appended F-33–F-34, partial F-32 resolution, TV-33–TV-35 and S1-Q12B. | Previous S1-Q12A OPEN and default feature priority ambiguity only; preserves S1-Q08 editorial override and archive requirements and all earlier answers. |

**End of v0.1.9. Next owner question: S1-Q12B (editorial override precedence during live broadcast).**


---

## 17. Version 0.2.0 — Owner-directed breadth-first discovery reset

**Recorded:** 21 November 2026 (Africa/Johannesburg)
**Classification:** Owner-directed discovery scope adjustment; not technical acceptance or approval of additional business rules.

### 17.1 Exact owner direction

> We have now spent a considerable amount of time only focusing on Voelgoed Live.
>
> We should probably lock down and grill the rest of the site as well

This supersedes the next-question priority in v0.1.9, not any earlier business answer. Preserve all confirmed broadcast requirements, historical evidence, candidate designs, open questions and verification tasks. Voelgoed LIVE discovery is substantially advanced, not complete or runtime-accepted. S1-Q12B and S1-Q13–Q17 remain OPEN and DEFERRED, along with other unresolved lifecycle, migration, integration and verification questions in earlier sections. Do not interpret the previously offered answer about live/override precedence as owner approval.

### 17.2 Breadth-first interview workstreams

| Session | Domain | Discovery status |
|---|---|---|
| 1 | Voelgoed LIVE / Twintig Twintig | Substantially advanced; further deep dive deferred |
| 2 | Customer accounts and identity | Next |
| 3 | Vriendinneklub, memberships and benefits | Pending |
| 4 | Editorial content, categories and access | Pending |
| 5 | Mense, contributors and experts | Partially covered; cross-domain account/person distinction still open |
| 6 | Leefstyl, advertisers and commercial partners | Pending |
| 7 | Mentorklas, courses and learning | Pending |
| 8 | WooCommerce, physical and digital products | Pending |
| 9 | eBooks, audiobooks and reader app | Pending |
| 10 | Events, ticketing and Fast Check-in | Pending |
| 11 | Nuwe Jy, challenges and recurring programmes | Pending |
| 12 | Inspirasievrou, competitions and voting | Pending |
| 13 | Podcasts, audio and other media | Pending |
| 14 | Staff roles, administration and automation | Pending |
| 15 | Marketing, CRM, analytics and integrations | Pending |
| 16 | Migration, legacy decommissioning and launch readiness | Pending |

These are interview workstreams, not approval to create modules, CPTs, taxonomies or physical database schemas. They supplement earlier scope and retain any other questions or offerings not explicitly enumerated. Plane remains the roadmap and issue tracker; this register preserves requirements/evidence and interview coverage rather than creating a parallel implementation roadmap.

### 17.3 Interview policy

First pass across each domain: establish purpose and current business operation, target changes, customer/account and access rules, commercial ownership, high-level lifecycle and dependencies, existing implementation evidence and critical unknowns. Use concise focused questions, group closely related decisions where that helps reduce repetitive turns, and move to the next domain once the foundation-level requirements are established. Park detailed subrules unless they are immediate blockers to a sound cross-domain model.

Second pass: return to parked material in dependency and risk order; explicitly resolve meaningful lifecycles, transitions, guards, side effects and terminal states. Reconcile actual repository and runtime with current authorities, and classify exact existing implementation, missing work, legacy consumers, conflicts and verification/migration requirements. Do not mark a domain technically complete on owner statements alone. No implementation or migration authorization is implied.

### 17.4 Next focused owner question — S2-Q01 (OPEN)

**Across the consolidated website, should a customer have one shared Voelgoed account for membership, events/tickets, physical and digital purchases, learning and eBook/audiobook access?**

Clarify one shared login versus any intentionally separate account systems. Do not infer mandatory login for browsing or guest checkout, automatic merging by email, an identity provider design, equivalence between customer account and public Mense profile, or the reader app's current identity storage. Those concerns remain separate for subsequent discovery. Account consolidation is a prerequisite to the cross-domain entitlement and migration discussion; verify existing account systems and mappings before engineering approval.

### 17.5 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.2.0 | 2026-11-21 | Owner-directed switch to breadth-first site-wide discovery; parked remaining broadcast questions, listed broader workstreams, established pass discipline and opened S2-Q01. | Next-question priority only. Preserves all previous answers, pending decisions and verification tasks. |

**End of v0.2.0. Next owner question: S2-Q01 (shared customer identity).**


---

## 18. Version 0.2.1 — Editorial / Leefstyl first-pass checkpoint

**Recorded:** 22 November 2026 (Africa/Johannesburg)
**Authority:** OWNER-CONFIRMED business requirements; written engineering authority inspected separately; runtime UNVERIFIED.
**Supersession:** Discovery ordering only: proceed to Mense and Partners as explicitly requested by the owner. Earlier S2-Q01 and broadcast questions remain open, not answered or cancelled. All prior business requirements and history are preserved.

### 18.1 Exact owner responses

**E-01 — What does Leefstyl represent to the business today?**

> Owner-confirmed role: Leefstyl is primarily a commercial/advertising offering.

E-01 was previously recorded in VGN-56 comment c15929d8-c1bd-44a2-bab9-2b2a32e01962.

**E-02 — Should editorial content be discoverable together under the seven pillars, regardless of its format?**

**E-03 — Where should a paid promotional article appear?**

**E-04 — How should ordinary editorial articles, videos and audio be accessed?**

> VG Nova breadth-first discovery — Editorial foundation checkpoint.
> E-02 Content organisation: Unified editorial topics across content formats, with format filtering.
> E-03 Commercial content placement: Exclusively Leefstyl and dedicated promotional sections.
> E-04 Editorial access: Free public content and paid Vriendinneklub-exclusive content.
> Record these decisions and E-01 in the next cumulative Markdown domain checkpoint and VGN-56. Preserve prior requirements, distinguish verified architecture from unverified runtime, and move to Mense and Partners rather than continuing a detailed editorial deep dive.

### 18.2 Confirmed requirements

| ID | Classification | Rule |
|---|---|---|
| E-01 / F-35 | OWNER-CONFIRMED | Leefstyl is primarily a commercial/advertising offering, not an ordinary eighth editorial pillar. |
| E-02 / F-36 | OWNER-CONFIRMED | Shared editorial topics across formats, with format filtering; discover articles, audio, video and relevant podcast content through shared topic destinations. Dedicated independent format archives were not selected; this does not authorise removal of any existing archive. |
| E-03 / F-37 | OWNER-CONFIRMED | Commercial content appears exclusively within Leefstyl and dedicated promotional sections, not ordinary editorial topic results. Internal subject metadata, global search and specifically labelled promotional placements on editorial pages remain open; do not equate the placement rule with a ban on internal taxonomy associations. |
| E-04 / F-38 | OWNER-CONFIRMED | Ordinary editorial content includes free public content and paid Vriendinneklub-exclusive content. Exact entitlement mapping, access enforcement and teaser behavior remain open. No additional free-registered-only editorial access tier was selected. |

E-04 does not override the existing owner-confirmed public/free Twintig Twintig access (S1-Q05). Other domain-specific access policies must be established separately.

### 18.3 Written authority independently inspected

Default-branch AGENTS.md in Carpe-Diem-Media/vgnova, blob SHA `1c181e5d41bf8aef624630032b2c1ff6f0c5a5b1`, read 2026-11-22:

- Lines 783–787, 820–827, 895: the seven canonical editorial pillars in `content_topic` are `nuwe-jy`, `geloof`, `self`, `werk-geld`, `ouerskap`, `huwelik`, `mensstories`; Platform Core owns the taxonomy. Leefstyl and its child terms are commercial classifications. `content_category` remains live legacy until migration/decommission gates pass.
- Lines 778, 804: PMPro is designated for membership and content gating.
- Lines 327–416 and 536–563: prefer simple ACF relationships for ordinary Article/Course-to-Person relationships, with bidirectionality where appropriate; do not create unnecessary rich participation domains.

These are verified written instructions, not proof of current runtime behaviour. The E-01/E-03 distinction is consistent with them. The legacy decommission register referenced by AGENTS must be inspected at the technical gate, not presumed satisfied.

### 18.4 Open rules and later verification

| ID | Classification | Evidence or decision needed |
|---|---|---|
| F-39 | OPEN | Commercial internal topic metadata, global search, permitted promotional surfaces, sponsorship disclosure, campaign scheduling, publication, expiry and archive behavior. |
| F-40 | OPEN | Paid membership entitlements, teaser rules, content visibility, editorial publication permissions and transitions. |
| TV-36 | UNVERIFIED | Inspect actual content types, taxonomy roots/children/associations and legacy consumers; reconcile source-to-target mappings against written authorities. |
| TV-37 | UNVERIFIED | Test shared topic queries and format filters with representative content across supported formats and approved visibility policies. |
| TV-38 | UNVERIFIED | Prove commercial content exclusion from ordinary editorial listings and inclusion in dedicated commercial areas; later verify global search and promotional placements against approved policy. |
| TV-39 | UNVERIFIED | Verify PMPro content access end to end, including anonymous, ineligible, eligible and expired users, direct URLs, rendered queries, cache/indexing and media delivery under approved policies. |
| TV-40 | UNVERIFIED | Map legacy editorial/promotional content and classifications, preserve historical evidence and URLs, flag ambiguous records, verify all decommission gates before removal. |

Meaningful editorial and commercial lifecycles must later specify states, transitions, guards, authorised actors, side effects and terminal/recovery semantics. No new enum, CPT, taxonomy, field, implementation, migration, deployment, legacy removal or merge is approved. First-pass editorial checkpoint recorded; full technical acceptance remains open.

### 18.5 Next domain — Mense and Partners (OPEN)

Reuse current canonical Person authority rather than reopen already decided engineering architecture.

Independently inspected AGENTS.md at the same blob: lines 897, 925–938 describe canonical Person at /mense/, retain operational `medewerker` pending approved migration, document legacy profile statuses public/private/noindex/archived, prohibit parallel Person/mentor systems, and direct not to PMPro-gate the legacy profile CPT. Lines 327–416 and 536–611 favour simple ACF relationships for articles/courses and preserve existing Event Participation and Product Contribution authority. Line 916 documents `vennoot` for sponsors/partners. Actual runtime implementation remains unverified. Keep S1-Q11/Q11A Mense participant decisions.

- MP-01 — Mense identity scope: all content/commercial contributors including once-off participants, versus selected profile-worthy contributors only; ordinary customers/WordPress accounts and purely internal contacts are distinct concerns and not automatically public profiles.
- MP-02 — public person profile policy: public profiles for all contributor records versus only deliberately selected/approved people, with other records retained internally or another policy. Noindex is not privacy.
- MP-03 — organisation/partner scope: advertisers and sponsors only, or also other organisational relationships such as external organisers, suppliers, publishers and collaborating organisations. Avoid duplicate organisation records merely because roles differ.
- MP-04 — commercial content attribution: every external paid promotional item linked to the responsible partner; clarify whether Voelgoed's own promotions require a partner link or another attribution rule.

Proceed to these foundation questions and then the next domain checkpoint rather than another extended feature deep dive. Customer identity workstream S2-Q01 remains queued for accounts and operations; preserve separate reader-app and other workstreams from v0.2.0.

### 18.6 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.2.1 | 2026-11-22 | Captured E-01–E-04, F-35–F-40, TV-36–TV-40, written authority reconciliation and four Mense/Partners questions. | Next-domain priority only; previous customer identity and broadcast questions remain open. All earlier interview answers and v0.2.0 process history retained. |

**End of v0.2.1. Next owner domain: Mense and Partners (MP-01–MP-04).**


---

## 19. Version 0.2.2 — Mense and Partners first-pass checkpoint

**Recorded:** 22 November 2026 (Africa/Johannesburg)
**Authority:** OWNER-CONFIRMED business requirements; written engineering authority inspected independently; current runtime UNVERIFIED.
**Scope:** First-pass domain checkpoint, not approved schema, implementation or migration. Preserve all earlier decisions and outstanding questions.

### 19.1 Exact questions and owner submission

MP-01: Who should have a central Mense record?
MP-02: Should every person in Mense have a publicly accessible profile page?
MP-03: Which organisations should be managed through the central Partners system?
MP-04: Should every commercial or promotional article be linked to a Partner record?

> VG Nova breadth-first discovery — Mense and Partners checkpoint.
> MP-01 Person scope: All contributors, including once-off participants.
> MP-02 Public profiles: Every person receives a public profile.
> MP-03 Partner scope: Advertisers and sponsors only.
> MP-04 Commercial attribution: Alternative arrangement.
> Additional requirements: Partners/ Vennote is different from normal promotional content, vennote is more long-lived partners and other promotional content is more one-off
> Record this domain checkpoint in VGN-56 and the next cumulative Markdown register. Preserve previous decisions, distinguish business requirements from technical verification, and proceed directly to Vriendinneklub and memberships.

### 19.2 Confirmed business rules and boundaries

| ID | Classification | Rule |
|---|---|---|
| MP-01 / F-41 | OWNER-CONFIRMED | All contributors, including once-off participants, are represented through the shared Mense identity model. This resolves the ordinary once-off guest identity workflow from S1-Q11A: add/select the person in Mense, not a separate episode-only identity system. It does not turn every customer, WordPress user, supplier contact or employee into a contributor. |
| MP-02 / F-42 | OWNER-CONFIRMED | Every Mense contributor receives a public profile. This resolves the intended profile coverage question: do not limit profiles to prominent or recurring contributors. Privacy exceptions, consent, drafts, incomplete data, withdrawal, archived visibility and indexing remain unapproved lifecycle details; do not infer that a record must be publicly visible from the instant it is created. |
| MP-03 / F-43 | OWNER-CONFIRMED | Vennote is scoped to advertiser and sponsor relationships, not a general supplier, publisher, organiser or business-contact directory. The owner further distinguishes longer-lived commercial partnerships from ordinary one-off promotional content. An organisation having a one-off promotion is not by itself proof of an ongoing partnership. |
| MP-04 / F-44 | OWNER-CONFIRMED DISTINCTION; ATTRIBUTION RULE OPEN | Vennote and ordinary promotional content are distinct business concepts; do not automatically require every one-off promotion to create a Vennote record. The owner selected alternative arrangement, not any of the mandatory/optional attribution options. Whether one-off promotions reference a contact, advertiser identity, existing Vennote or other information, whether a long-term partner link is mandatory for relevant content, and Voelgoed self-promotion treatment are OPEN. No blanket attribution cardinality or required field is approved. |

Preserve E-01–E-04: Leefstyl commercial classification, promotions excluded from ordinary editorial results, shared editorial topic/format discovery and mixed free/paid editorial access. Vennote relationship duration is a distinction of business purpose, not a new taxonomy, CPT or numeric threshold. Do not infer partnership from sponsorship of a single item, or infer a business contract from classification. S1-Q11/Q11A Mense assignments and multiple presenter/guest capability remain confirmed; minimum contributor count, relationship role ordering, identity correction, and publication guards remain open.

### 19.3 Written authority checked and policy reconciliation

Default branch AGENTS.md, Carpe-Diem-Media/vgnova, blob `1c181e5d41bf8aef624630032b2c1ff6f0c5a5b1`, inspected 2026-11-22:

- Lines 776, 897, 925–938: canonical Person and /mense/ destination; current `medewerker` implementation remains operational pending separately approved mapping and migration of profiles, relationships, URLs and consumers. Do not create a parallel Person model or reintroduce mentor CPT/URLs.
- Line 932 documents legacy profile statuses `public`, `private`, `noindex`, `archived`. MP-02 establishes the target intended profile coverage but does not authorise deleting these controls or exposing private data. This is a policy reconciliation item: verify actual consumers, classify valid draft/restricted/withdrawal/privacy/archive cases, determine lifecycle and public visibility rules, then reconcile with the owner. Noindex is not a privacy boundary. Line 936 prohibits gating the legacy profile CPT with PMPro.
- Lines 327–416 and 536–611: ordinary Article/Course Person relationships prefer simple ACF fields and supported reverse relationships; Event Participation and Product Contribution remain authority for their contextual relationship facts when they own them. Do not create competing relationship storage. Confirm actual runtime before concluding any particular implementation exists.
- Line 916 documents `vennoot` as sponsors/partners. It does not establish the exact advertiser attribution workflow, partnership duration, or proven runtime boundaries. Inspect existing partner links, mappings, promotional content storage and consumers before recommending changes.

### 19.4 Later contract decisions and technical verification

| ID | Classification | Decision / evidence required |
|---|---|---|
| F-45 | OPEN | Public profile publication prerequisites, consent/privacy restrictions, draft and withdrawn profiles, archive retention, duplicate matching/merges and historical credits; reconcile MP-02 with current documented statuses. |
| F-46 | OPEN | Vennote qualification and activation/expiry/renewal/termination, historical campaign attribution, one-off advertiser identity and required links, sponsor disclosure and Voelgoed self-promotion. Distinguish partner relationship lifecycle from campaign and content lifecycle. |
| TV-41 | UNVERIFIED — inspect | Inventory actual Mense/medewerker registration, ACF, contextual relationships, role authority, public templates and profile state consumers, in source and selected runtime. Verify contributor scope across articles, courses, products, events, broadcasts and programmes without duplicate identity stores. |
| TV-42 | UNVERIFIED — contract/runtime | After policy approval, prove a new once-off contributor can be created and related, receives an accurate public profile under approved publication rules, and is reusable. Verify privacy, permissions, indexing, historical credits, relationship removal and correction/merge behavior without conflating site accounts with Mense. |
| TV-43 | UNVERIFIED — inspect | Reconcile `vennoot`, Leefstyl, ordinary promotional content, current partner/category mappings and commercial attribution consumers. Identify what exists without inventing another advertiser directory. |
| TV-44 | UNVERIFIED — contract/runtime | Demonstrate long-term partner and once-off promotion cases under approved rules; verify editorial exclusion, correct partner attribution where required, sponsorship disclosure, expiry, archive and historical links. |
| TV-45 | UNVERIFIED — migration | Map existing people and partner/commercial records to target identity and relationship scope, flag duplicate/ambiguous data and legacy consumers; satisfy separate migration/decommission gates before removal or redirect changes. |

For the later detailed pass explicitly model Person profile and Vennote partnership states, authorised transitions, guards, side effects, terminal/recovery rules and their independence from individual content publication, campaign scheduling and membership. This checkpoint does not approve state names, permission changes, code, fields, CPTs, migration, deployment or merge.

### 19.5 Next domain — Vriendinneklub and memberships (OPEN)

Written engineering authority from AGENTS.md at the same SHA, lines 501–530, 778/804 and 1071–1090: WooCommerce owns commerce; WooCommerce Subscriptions owns subscription billing lifecycle; PMPro owns membership entitlement; Member Perks is custom plugin authority for benefits, governed by its own AGENTS and code repository. Do not hardcode IDs, billing terms or programme/level assumptions. No alternative authority proposed.

Independently read `docs/architecture/VS1_MEMBERSHIP_COMMERCE_GATE_v1.0.0.md` (blob `0e16210272b1a9f6a0e46aacaf67809f58c3d486`), dated 2026-10-26. This records a previous read-only Local audit, NOT a fresh runtime check: Vriendinne Brons/Silwer/Goud each mapping to a PMPro level with monthly/yearly Woo variations; separate Voelgoednetwerk programme in Member Perks; levels 15–21 observed with disabled direct signups, no paid PMPro billing; draft Vriendinne product parents, unproven checkout/subscription-to-PMPro chain, unconfigured Course member price and disabled discount/claim features at that audit. These are historical technical evidence and must not be mistaken for current approved product prices, live offers or current runtime state. Preserve earlier VGN-56 proof obligations. The prior gate was BLOCKED at that recorded audit.

Ask grouped high-leverage business questions:

- M-01 Membership programme scope: confirm which membership programmes and tiers should launch, including Vriendinneklub Brons/Silwer/Goud and whether Voelgoednetwerk is a separate active programme with its own tiers. Historical configuration is not the business offer.
- M-02 Billing options: confirm monthly/annual billing for tiers and whether any non-recurring, gifted or complimentary memberships are part of launch scope. Do not conflate payment terms with entitlement tiers.
- M-03 Benefits: confirm whether higher Vriendinne tiers inherit lower-tier benefits or each level has independently selected benefits; identify essential benefit categories (premium editorial, Mentorklas, course discounts/free access, event discounts, product discounts, other). Perk amounts and precise entitlement matrix reserved for detailed checkpoint.
- M-04 Membership continuity: confirm business treatment of cancellation and payment failure (paid-through access, grace period where desired, or immediate revocation). Separate cancellation from refund and failed renewal; exact lifecycle states, date boundaries, recovery, and operator authority later.

Customer identity S2-Q01 remains open; do not infer mandatory single-account identity or guest checkout rules from the membership decision. Continue breadth-first; reconcile existing configured systems rather than recreate them.

### 19.6 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.2.2 | 2026-11-22 | Recorded MP-01–MP-04, F-41–F-46, TV-41–TV-45, Person visibility reconciliation, Vennote/one-off promotion distinction and membership next domain. | Resolves S1-Q11A ordinary contributor identity/profile coverage and MP-01–MP-03; MP-04 attribution details remain open. Prior history preserved. |

**End of v0.2.2. Next owner domain: Vriendinneklub and memberships.**


---

## 20. Version 0.2.3 — Vriendinneklub and memberships first-pass checkpoint

**Recorded:** 22 November 2026 (Africa/Johannesburg)
**Authority:** OWNER-CONFIRMED TO-BE business rules; written engineering authorities independently inspected; historical runtime evidence only; current runtime UNVERIFIED.
**Scope:** Membership foundation first pass, not a final entitlement matrix or technical approval.

### 20.1 Exact questions and owner submission

M-01: Which membership programmes should VG Nova support at launch?
M-02: Which membership billing options should be supported?
M-03A: Should higher membership tiers automatically inherit lower-tier benefits?
M-03B: Which benefit categories must the membership foundation support?
M-04A: After cancellation, what happens to existing membership access?
M-04B: After a failed renewal payment, what happens to membership access?

> VG Nova breadth-first discovery — Vriendinneklub and memberships checkpoint.
> M-01 Membership programmes: Vriendinneklub and Voelgoednetwerk as separate programmes with their own tiers.
> M-02 Billing options: Monthly and annual subscriptions, plus complimentary or gifted memberships.
> M-03A Tier benefits: Higher tiers inherit lower-tier benefits.
> M-03B Required benefit categories: Premium editorial content; Mentorklas access; Free courses / course access; Course discounts; Event ticket discounts; Free event tickets; Physical product discounts; Digital product discounts; Exclusive member offers
> M-04A Cancellation: Retain access through the already paid period.
> M-04B Failed renewal: Defined grace period during payment recovery.
> Record this checkpoint in VGN-56 and next cumulative Markdown version. Preserve all historical decisions, reconcile against existing Member Perks, PMPro and WooCommerce Subscriptions authorities, distinguish business requirements from technical verification, and proceed to Products and Commerce.

### 20.2 Confirmed business rules

| ID | Classification | Rule |
|---|---|---|
| M-01 / F-47 | OWNER-CONFIRMED | Vriendinneklub and Voelgoednetwerk are separate membership programmes with their own tiers. Do not establish cross-programme tier inheritance or mutual exclusion from this answer. Exact launch tier names/prices and simultaneous programme membership policy require business confirmation. |
| M-02 / F-48 | OWNER-CONFIRMED | Support monthly and annual subscriptions as well as complimentary or gifted memberships. Billing cadence is distinct from tier entitlement. Gift purchaser, recipient, redemption, duration, complimentary grant authority, renewal and expiry remain open. No specific gift payment or voucher implementation approved. |
| M-03A / F-49 | OWNER-CONFIRMED | Higher tiers inherit lower-tier benefits within the applicable programme. Preserve each programme's own hierarchy. Inheritance of eligibility is not automatic accumulation of discount amounts, duplicate claims or duplicate active PMPro levels. Exact benefit conflict/selection and scope must be reconciled against the existing authority and final benefit matrix. |
| M-03B / F-50 | OWNER-CONFIRMED | Support all nine selected benefit categories (listed below), without implying every member or tier receives every category or an identical amount. |
| M-04A / F-51 | OWNER-CONFIRMED | A cancelled recurring subscription retains membership access through its already-paid period; ordinary cancellation prevents future renewal rather than prematurely removing valid paid-through entitlement. Refund, chargeback, fraud and administrative termination are distinct circumstances with rules still open. |
| M-04B / F-52 | OWNER-CONFIRMED | Allow a defined grace period during failed-renewal payment recovery. Grace duration, start/end clock, access during grace, retry schedule, recovery, notice, expiry and programme-specific exceptions require detailed confirmation. Do not infer an unlimited grace period or a numeric duration. |

M-03B required capabilities: (1) premium editorial content; (2) Mentorklas access; (3) free courses/course access; (4) course discounts; (5) event ticket discounts; (6) free event tickets; (7) physical product discounts; (8) digital product discounts; (9) exclusive member offers. Distinguish claims, recurring access, purchased permanent entitlements, price adjustments, reservations and fulfilment. Preserve E-04 mixed editorial access and S1-Q05 current free broadcasts; do not gate public broadcasts as a consequence of premium editorial benefits.

### 20.3 Existing engineering authority and historical evidence

Independently reopened 2026-11-22:

- `AGENTS.md`, default branch blob `1c181e5d41bf8aef624630032b2c1ff6f0c5a5b1`, especially lines 501–530 and 1071–1090: WooCommerce owns products/cart/checkout/orders, WooCommerce Subscriptions billing lifecycle, PMPro membership entitlement, Masteriyo courses/enrolments/progress, Tickera ticketing; Member Perks plugin-specific rules and source repository govern benefit implementation. No hardcoding identifiers, term or programme assumptions; no competing plugin-domain implementation. The approved authority split remains intact.
- `docs/development/membership-billing-architecture.md`, blob `24986add898f36e3abb66467f286b161be7a65d2`, dated 2026-06-09: documents Vriendinneklub Brons/Silwer/Goud and Voelgoednetwerk Brons/Silwer/Goud/Platinum, separate PMPro groups, a PMPro level per tier, monthly/yearly Woo variable subscription variations and disabled direct PMPro billing/signups. Netwerk caps are display-only in that historical document. This is written architecture and prior staging configuration, not owner-approved current launch prices/caps or a fresh runtime check.
- `docs/architecture/VS1_MEMBERSHIP_COMMERCE_GATE_v1.0.0.md`, blob `0e16210272b1a9f6a0e46aacaf67809f58c3d486`, dated 2026-10-26: prior Local audit reported the tier mapping, Member Perks eligibility modes any/specific/minimum, active PMPro profile resolution, deterministic selection of competing Woo perks and Woo authoritative base price; no Course-member-price end-to-end proof, no successful subscription-to-PMPro purchase lineage, discount/claim flags off in that audit. The historical gate was BLOCKED. These are not re-observed runtime facts or current source-code acceptance. The pinned historical Member Perks source revision in that audit was `972a279be7a753594888e974132562e1cc099e24`. Inspect current plugin-specific authority/ref and runtime before implementation or acceptance.

The minimum-tier matching described in the prior audit appears suitable for inheritance but must be verified across the full benefit set, including PMPro premium content, course access, special offers and cross-programme targeting. Eligibility inheritance must not silently stack overlapping monetary discounts or produce duplicate grants. Preserve existing deterministic Member Perks selection until an approved business reconciliation requires a change. Do not use Woo paid order rows alone to infer active PMPro entitlement, nor create lower-tier membership rows merely to achieve inheritance. Confirm PMPro content gating for eligible higher tiers without exposing ineligible users.

### 20.4 Lifecycle contract candidates and outstanding decisions

The confirmed cancellation and grace rules concern entitlement continuity, not exact provider status codes. Keep billing, membership entitlement and benefit claim/fulfilment lifecycles separate. Candidate business journey: initial purchase/grant pending -> valid active entitlement -> paid-through non-renewing on ordinary cancellation -> expiry; active -> renewal recovery/grace on failed renewal -> recovered active or expiry according to approved grace policy. Complimentary/gifted path needs recipient entitlement and explicit effective period without inventing a recurring charge. Candidate states/transitions are not approved implementation enums or provider mapping.

For each transition establish triggering event and actor, effective dates/timezone, identity and payment/grant guards, authoritative provider state, reconciliation/idempotency, notifications, eligibility refresh, cache invalidation, side effects on claims/access, terminal states and recovery. Cancellation should stop future renewal and preserve paid-through access; grace requires bounded recovery semantics. Define restrictions during grace, successful retry, expiry, refund/reversal, reactivation, upgrade/downgrade, overlapping membership sources, independently purchased access and previously consumed benefits. Do not revoke separately purchased items or coursework merely because membership ends; precise ownership, claim and revocation policies remain to be decided. No runtime changes authorised.

### 20.5 Verification backlog

| ID | Classification | Evidence / decision required |
|---|---|---|
| F-53 | OPEN | Confirm tier offer/price/cap matrix, simultaneous programme enrolment, switching and recipient/gift/complimentary grant policy. |
| F-54 | OPEN | Benefit-by-tier matrix, inheritance eligibility across all domains, competing perk resolution, quotas, repeat claims, already-acquired entitlements and discount stacking/selection. |
| F-55 | OPEN | Grace duration/timing and access rules; cancellation at paid-through boundary; failed renewal retries, recovery, initial payment failure, expiry, upgrades/downgrades, refunds/chargebacks and administrative controls. |
| TV-46 | UNVERIFIED | Reinspect current PMPro, Woo Subscriptions/add-on and Member Perks configuration, exact source refs/authorities, programme mappings, tier ordering, billing variations, flags, legacy consumers and dashboard. Do not hardcode historical IDs. |
| TV-47 | UNVERIFIED | After approved mapping, prove purchase and successful billing -> correct PMPro entitlement -> inherited benefits, across both programmes, including month/year variants and all nine benefit categories. Test no cross-programme leakage, lower-tier denial, higher-tier eligibility, overlapping benefits without duplicate grants or competing price owners. |
| TV-48 | UNVERIFIED | Under approved grant policies verify gift purchaser vs recipient, complimentary grant, expiry/redeem/revoke, paid-through cancellation, failed renewal grace, recovery and eventual expiry/refund under controlled test conditions. Verify repeated/out-of-order events, access and cache consistency; avoid unintended charges. |
| TV-49 | UNVERIFIED | Reconcile existing production memberships, billing relationships and historical entitlements to target programme/tier and account identity; flag unmatched or duplicate records and missing lineage, preserve paid-through dates and prevent accidental renewal/rebilling or access loss during migration. |
| TV-50 | UNVERIFIED | Verify separate purchased vs member-granted course/product/ticket entitlements with provider authority, expiry, benefit claim and fulfilment history, cancellation/reversal rules and authenticated dashboard. |

No changes to products, PMPro levels, Member Perks mappings/flags, billing engines, application code, migration, runtime state, deployment or merge are approved. Membership first pass recorded; full lifecycle, commercial offer and technical acceptance remain open.

### 20.6 Next domain — Products and Commerce (OPEN)

WooCommerce remains product/cart/checkout/order authority, Woo Subscriptions recurring billing authority, Tickera ticket authority, Masteriyo learning authority, Product Contribution contextual contribution authority. Do not duplicate those systems or treat a purchase as automatically proving domain fulfilment. Preserve separate reader-app workstream and existing account identity question from v0.2.0; ask high-impact business rules instead of rebuilding the product architecture.

- C-01 Catalogue: should physical books/merchandise, eBooks/audiobooks, courses, event tickets and membership offers be discoverable in one shopping catalogue, or through domain-specific storefronts/hubs sharing checkout, or hybrid? Clarify product visibility rather than native Woo data ownership.
- C-02 Basket/checkout: may customers combine physical/digital goods, course products, tickets and recurring membership products in one order where provider rules allow, or should particular purchases use distinct purchase journeys? Separate desired mixed-basket experience from actual provider compatibility and split/renewal obligations.
- C-03 Customer account/access: guest checkout policy for physical goods vs identity-bearing digital, learning, ticketing and subscription purchases. Preserve open shared customer identity/migration details; no automatic matching/merging by unverified email.
- C-04 Digital ownership: distinguish standalone eBook/audiobook purchase and reading/listening entitlement from time-limited membership access; decide lifetime (subject to legitimate reversal) vs term-based access and whether reader app remains required/website access is desired. Preserve unresolved app integration details.
- C-05 Fulfilment and refunds: confirm whether order completion/fulfilment is type-specific (physical delivery, digital access, learning enrolment, ticket issuance, membership activation), and whether refunds must reverse only the relevant purchased entitlements under appropriate rules. Exact release and reversal guards, partial refunds, consumed access, delivery, payment status and legal policies remain detailed-pass questions.

Proceed to high-level commerce discovery, then courses and learning. Historical configuration or feature support does not substitute for owner confirmation or current proof.

### 20.7 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.2.3 | 2026-11-22 | Recorded M-01–M-04B, F-47–F-55, TV-46–TV-50, authority and historical runtime reconciliation; opened Products and Commerce. | Membership first-pass open questions only; prior decisions and other open questions retained. |

**End of v0.2.3. Next owner domain: Products and Commerce (C-01–C-05).**


---

## 21. Version 0.2.4 — Products and Commerce first-pass checkpoint

**Recorded:** 22 November 2026 (Africa/Johannesburg)
**Authority:** OWNER-CONFIRMED TO-BE business requirements; independently inspected written engineering authority and historical audit evidence; current runtime UNVERIFIED.
**Scope:** First-pass commerce requirements. No implementation, pricing, product data, provider configuration, schema, migration, deployment or merge approval.

### 21.1 Questions and exact owner submission

C-01 — Product catalogue.
C-02 — Shopping cart.
C-03 — Customer accounts.
C-04 — eBooks and audiobooks.
C-05 — Fulfilment and refunds.

> VG Nova breadth-first discovery — Products and Commerce checkpoint.
> C-01 Catalogue: One central shopping catalogue.
> C-02 Basket: Mixed baskets wherever technically compatible.
> C-03 Accounts: Guest checkout for physical goods/tickets; accounts for digital access/courses/memberships.
> C-04 Digital reading/listening: Reader app and website-based reading/listening.
> C-05 Fulfilment/refunds: Automated digital fulfilment with manual review for selected refunds/exceptions.
> Record in Plane VGN-56 and next cumulative Markdown register, preserving earlier answers and clearly distinguishing business rules from technical implementation. Proceed breadth-first to Courses and Learning.

### 21.2 Confirmed business requirements

| ID | Classification | Rule |
|---|---|---|
| C-01 / F-56 | OWNER-CONFIRMED | One central shopping catalogue across the portfolio discussed (physical/digital products, courses, tickets, membership offers). One discovery catalogue does not imply deleting domain-specific hubs or using one physical data model for all offerings. Existing provider entities and public landing pages remain governed by their own authorities; exact catalogue visibility and inclusion rules remain open. |
| C-02 / F-57 | OWNER-CONFIRMED | Permit mixed baskets wherever technically compatible. Compatibility is an explicit qualifier: actual subscription, ticket, course, payment, fulfilment, shipping and tax constraints must be tested; no promise that every combination works. Define clear handling of incompatible combinations rather than silently discarding items. |
| C-03 / F-58 | OWNER-CONFIRMED | Guest checkout is allowed for physical goods and tickets; accounts are required for digital access, courses and memberships. A mixed cart containing an account-required offering must not bypass that requirement. Exact login/registration timing, returning-user verification, guest-to-account conversion, buyer vs attendee, identity linking and deduplication remain open. S2-Q01 site-wide shared identity remains open, not fully answered by checkout policy. |
| C-04 / F-59 | OWNER-CONFIRMED | Support the existing reader app and website-based reading/listening for eBooks and audiobooks. Do not create independent divergent ownership records merely to enable another presentation surface. The exact shared entitlement authority, reader protocols, supported devices, streaming/download rights, DRM, purchase duration, revocation and offline access are not decided. The owner did not select downloadable files or lifetime ownership explicitly; retain these questions. |
| C-05 / F-60 | OWNER-CONFIRMED | Automated digital fulfilment with manual review for selected refunds and exceptions. Establish actual paid/granted eligibility and verified delivery/access, and support-visible recovery for failures. Exact eligible order statuses, manual review criteria, partial refund policy, physical fulfilment, refund effects on tickets/learning/digital access/subscription entitlements and reversal rules remain open. Do not infer all refunds are manual or every refund automatically revokes all related access. |

Preserve membership M-01–M-04B: distinct programmes and tier hierarchies, monthly/annual and gift/complimentary grants, paid-through cancellation and bounded renewal grace. A standalone purchase is a different entitlement source from ongoing membership or perk grants. Preserve editorial and Mense/Partners decisions. A unified catalogue is not permission to introduce competing product records, duplicate provider-owned relationships, or move sponsored content into ordinary editorial results.

### 21.3 Written authorities and prior evidence

Independently read 2026-11-22:

- Default branch `AGENTS.md` blob `1c181e5d41bf8aef624630032b2c1ff6f0c5a5b1`, lines 501–530: WooCommerce owns products/cart/checkout/orders; Woo Subscriptions owns billing lifecycle; PMPro membership entitlements; Masteriyo courses/lessons/enrolment/progress; Tickera ticketing; Bricks presentation and ACF metadata/relationships. Lines 585–611 preserve established Product Contribution and Event Participation responsibility for contextual relationships. Lines 919–921 document current provider URL conventions and protected learning URL migration boundary; no rewrite changes approved.
- `docs/superpowers/specs/2026-10-25-vs1-golden-journey-product-tech-spec_v0.2.0.md` blob `88ee6505244dced1c644791729e450c5679cb3c3`, lines 99, 443–446 and sections 29, 32–33: Masteriyo owns learning content/access/progress; successful purchase requires correct entitlement; useful Course-ready email/direct course continuation and failure safety are existing journey authorities. Do not reopen these technical authorities unnecessarily.
- `docs/architecture/VS1_COURSE_FULFILMENT_EMAIL_GATE_v1.0.0.md` blob `37645511aa1645512f93c22f60560943b734c873`: 2026-10-26 read-only Local audit reported `COURSE_PURCHASE_TO_ENROLMENT=GAP`, `COURSE_FULFILMENT_GATE=BLOCKED`, `EMAIL_GATE=GAP`. At that historical audit no Course-Woo product links existed, no paid order/enrolment proof, overlapping enabled email emitters, and controlled proof required authorisation to mutate. These are dated findings, not today's runtime state; subsequent work may have changed configuration. Re-inspect rather than assume current failure or success.

The prior membership commerce gate and Member Perks authority from section 20 remain applicable. Written ownership is independently confirmed; actual current runtime, catalogue, mixed cart compatibility, account enforcement, browser reader, fulfilment and refund behavior remain unverified.

### 21.4 Lifecycle obligations and verification backlog

Later detailed contracts must distinguish Woo order/payment lifecycle, line-level fulfilment, shipment, digital ownership/access, learning enrolment, ticket issuance/admission, membership billing/entitlement and refund/reversal lifecycle. Candidate commerce progression: basket → submitted/pending payment → eligible paid/granted order → item-specific fulfilment pending/fulfilled or failed/retry/manual review → delivery/access confirmation; separately cancellation, refund review, partial/full reversal and exceptional recovery. This is not an approved provider status mapping. Define exact states, authorised transitions and guards (including valid identity, compatible basket, confirmed payment, applicable access rights and idempotency), side effects (delivery/issuance, enrolment, entitlement, transactional emails, stock, cache, customer dashboard and support visibility), terminal outcomes, compensation and recovery. Do not treat an order-level status as proof every line was fulfilled, or revoke independently acquired entitlements indiscriminately.

| ID | Classification | Decision/evidence required |
|---|---|---|
| F-61 | OPEN | Inclusion/visibility per offering, central catalogue categories/search/filtering, dedicated landing pages, catalogue and product URL governance. |
| F-62 | OPEN | Mixed-cart compatibility matrix, identity enforcement on mixed purchases, guest ticket holder vs purchaser, account deduplication and payment/renewal constraints. |
| F-63 | OPEN | Purchased vs membership-granted digital entitlement duration, common library identity/reader authority, app and browser integration, file security, publisher/content rights, offline/download and refund behavior. |
| F-64 | OPEN | Payment/fulfilment triggers and manual review eligibility, exception handling, delivery, partial refunds, returns, chargebacks, consumed entitlements, stock, reconciliation and customer communications. |
| TV-51 | UNVERIFIED | Inventory actual Woo product types/categories/statuses, provider associations, catalogue/Bricks queries and templates, existing domain landing pages and URL consumers. Verify no duplicated canonical purchase records or legacy conflicts. |
| TV-52 | UNVERIFIED | Test compatible/incompatible mixed baskets and account policy including guest-only baskets, mixed guest/account-required baskets, existing customers, identity verification and provider-specific payment constraints. |
| TV-53 | UNVERIFIED | Audit existing reader app/data/API/rights and historical Woo-to-reader entitlement lineage; verify one authoritative entitlement is recognised by both app and web reader under approved security and rights policy. Check access denial and migration mapping. |
| TV-54 | UNVERIFIED | Prove actual paid/granted order and independent fulfilment by line/product type, including Masteriyo, Tickera, membership and digital delivery, failure recovery/idempotency, accurate email/dashboard, and no accidental duplicate communications. |
| TV-55 | UNVERIFIED | Test refund review and approved partial/full reversal outcomes, independent ownership preservation, consumed ticket/access cases, returns and failed fulfilment support; verify source/target data lineage before migration. |

No existing code, plugin setting, product, membership, user, order, enrolment, ticket, reader record or production state modified by this checkpoint. No decommission or merge recommended. Exact current source/runtime and authority references must be checked at the future technical gate.

### 21.5 Next domain — Courses and Learning (OPEN)

Masteriyo remains course, lesson, enrolment and progress authority; Woo owns purchase and base price; Member Perks owns configured member benefits; PMPro owns membership entitlement. Existing approved course purchase/access and email journey rules remain in force. The prior audit has stated evidence gaps; current runtime is not confirmed. Historical LearnDash/LearnPress migration and URL/source-content mapping remain separate verification tasks.

- **L-01 Course access models:** Which modes must be supported: free enrolled courses, separately purchased courses, membership-included courses and member-discounted purchases? Distinguish public discovery/previews from enrolled learning.
- **L-02 Access duration and membership dependency:** Once someone buys an individual course, should access persist indefinitely (subject to legitimate reversal), expire after a defined period or be configurable per course? Separately, should membership-included course access last only while eligible or persist after claim? Do not conflate purchased and membership-granted access or assume claim semantics.
- **L-03 Learning format and progression:** Self-paced learning, structured/ordered progression and scheduled/cohort/Drip content if applicable. Identify whether quizzes/assessments and completion certificates are required business capabilities; do not assume plugin features prove need or configured capability.
- **L-04 My Voelgoed learner journey:** Confirm unified learner dashboard/library for enrolled/purchased/included courses, progress, resume, completion and certificates where applicable, respecting existing provider authority; decide whether learning history is retained after access ends.
- **L-05 Programme relationship:** Clarify Mentorklas as programme offering/hub with courses, mentors and other resources versus a course itself; preserve confirmed membership benefit support without assuming an extra LMS or duplicating course content. Nuwe Jy and challenges will receive their own checkpoint.

Proceed to Events and Ticketing after the learning checkpoint. No detailed course-by-course pricing or curriculum implementation during this breadth-first pass.

### 21.6 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.2.4 | 2026-11-22 | Recorded C-01–C-05, F-56–F-64, TV-51–TV-55, written authority and historical audit reconciliation; opened Courses and Learning. | Commerce first-pass OPEN questions only; digital ownership term, identity, refund specifics and prior decisions remain explicitly open or preserved. |

**End of v0.2.4. Next owner domain: Courses and Learning (L-01–L-05).**


---

## 22. Version 0.2.5 — Courses and Learning first-pass checkpoint

**Recorded:** 22 November 2026 (Africa/Johannesburg)
**Authority:** OWNER-CONFIRMED TO-BE business requirements; written technical authority independently inspected; actual course functionality/runtime NOT VERIFIED.
**Scope:** Breadth-first requirement discovery. No LMS feature configuration, implementation, migration or merge approved.

### 22.1 Exact owner answer

**Questions:** L-01 catalogue, L-02 course access, L-03 format and progression, L-04 learner dashboard, L-05 Mentorklas business meaning.

> VG Nova breadth-first discovery — Courses and Learning checkpoint.
> L-01 Catalogue: One central course catalogue.
> L-02 Access: Free courses, individual purchases, membership access, member discounts and gifted/complimentary enrolments.
> L-03 Format: Self-paced plus scheduled/cohort, assessments and certificates.
> L-04 Dashboard: Unified dashboard for enrolled, purchased and member-included courses, with progress/resume.
> L-05 Mentorklas: Primarily an exclusive membership benefit with courses and other content.
> Record in Plane VGN-56 and next cumulative Markdown checkpoint, preserve all earlier answers and distinguish business requirements from technical implementation. Proceed breadth-first to Events and Ticketing.

### 22.2 Confirmed requirements

| ID | Classification | Business rule and limits |
|---|---|---|
| L-01 / F-65 | OWNER-CONFIRMED | One central discoverable course catalogue. This does not forbid dedicated programme landing pages or require a second course data model; exact collection/navigation hierarchy remains open. |
| L-02 / F-66 | OWNER-CONFIRMED | Support free enrolment, separately purchased courses, membership-included access, tier-eligible course discounts and gifted/complimentary enrolment. A course may be distributed through different approved mechanisms; the user has not selected specific pricing, grant duration, course-product mapping, gifts process or entitlement precedence. |
| L-03 / F-67 | OWNER-CONFIRMED | Support self-paced learning **and** scheduled/cohort learning, assessments and completion certificates. A native Masteriyo capability is not assumed for any of these, and no particular scheduling/drip model, instructor workflow, grading threshold, certificate criteria, retake policy or cohort enrolment process has been approved. |
| L-04 / F-68 | OWNER-CONFIRMED | One unified learner dashboard showing enrolled, individually purchased and membership-included courses, including persistent progress/resume experience. Historical progress and certificates after access expiry, and learner transfer across legacy systems, remain to be decided. A unified dashboard is not permission for an alternative source of learning progress. |
| L-05 / F-69 | OWNER-CONFIRMED | Mentorklas is **primarily an exclusive membership benefit** containing courses and other content. Do not model it automatically as one course, require separate purchase or invent another LMS; preserve its programme/collection identity and confirm eligible programme/tier access in the later membership benefit matrix. Non-course resources may use other existing content/domain owners rather than being replicated as Masteriyo lessons. |

Preserve M-01–M-04B: separate Vriendinneklub and Voelgoednetwerk membership programmes; higher tiers inherit lower-tier eligibility within the relevant programme; course benefits are supported categories but are not automatically granted to every member. Preserve C-01–C-05: central commerce catalogue, compatible mixed carts, authenticated digital/learning access and automated digital fulfilment. Purchased course access and membership-derived access are distinct entitlements. Revoking a membership must not silently delete an independently purchased course, progress or certificate.

### 22.3 Independently inspected written authority and historical status

Read `Carpe-Diem-Media/vgnova` default-branch `AGENTS.md` blob `1c181e5d41bf8aef624630032b2c1ff6f0c5a5b1`, 22 November 2026:
- Lines 501–530: Masteriyo is authoritative for courses, lessons, enrolment and progress; WooCommerce for purchase/checkout/orders; Woo Subscriptions for recurring billing; PMPro for membership access; Voelgoed Member Perks plugin for tier-specific benefits per its own plugin authority.
- Lines 550–563: simple Course↔Person relationships should favour Masteriyo plus ACF Person fields and supported bidirectional relationships where justified; do not create another course participation engine without need. Mense remains the shared person identity from MP-01/MP-02.
- Lines 919–921: current course CPT `mto-course`, canonical Learning area `/leer/`, current singles `/leer/{slug}/`, transitional archive `/kursusse/` awaiting dedicated URL decision; no permalink/rewrite changes approved here.

Historical technical reference `docs/architecture/VS1_MEMBERSHIP_COMMERCE_GATE_v1.0.0.md`, read previously during membership checkpoint (audit date 2026-10-26), reported Course↔Woo mapping absent for the sampled Local courses, unproven course pricing and disabled benefit claims. This is NOT a new/current runtime observation. Current implementation of scheduled/cohort delivery, assessment/certificates, gift enrolment, accurate progress/resume, membership grants and student access remains UNVERIFIED. Do not claim native Masteriyo support or site configuration solely based on plugin naming.

### 22.4 Lifecycle and boundary obligations for later contract approval

Treat Course publication/revision, scheduled/cohort offering, student enrolment/access entitlement, lesson progress, assessment attempts, certificate award and access revocation as potentially distinct lifecycle concepts. Their actual states and required independence must be defined from verified requirements, not fabricated enums.

Before implementation define:
- Who may create/publish/revise/retire courses and start/close cohorts; prerequisites, effective dates, schedule changes, access guards, student communications, completion and cancellation/recovery semantics.
- For free, paid, included and gifted enrolments: granting authority, account identity, payment/eligibility and gift validation, idempotency, access start/expiry, explicit source attribution and whether overlapping sources co-exist.
- Progress preservation, resumption, course updates, assessment pass/fail/retry and certificate eligibility/issue/revoke/verify, with actors, guards, side effects and terminal states. Do not claim completion automatically equals certification.
- Membership expiry, refund, chargeback and course retirement impacts. Purchased independent rights must not be removed simply because PMPro membership ended; exceptions and certificate/history retention need explicit owner policy.
- Dashboard representation and cache/access isolation for anonymous visitors, free learners, purchasers, eligible members, expired members and gift recipients.

The above are DESIGN OBLIGATIONS, not approved states, exact workflows, vendor feature claims or technical acceptance.

### 22.5 Open questions and technical verification backlog

| ID | Classification | Evidence / decision required |
|---|---|---|
| F-70 | OPEN | Specific course purchase-vs-membership-vs-gift entitlement ownership, access duration, stacking and expiry; existing enrolments after billing changes, withdrawals and course retirement. |
| F-71 | OPEN | Scheduled/cohort definition, instructor assignment, date/seat capacity, enrolment, schedule/drip constraints, changes, notifications and completion. |
| F-72 | OPEN | Assessment types, passing rules, retries, progress/completion criteria, certificates/verification and retention of historical learning records. |
| F-73 | OPEN | Mentorklas eligible programme/tier, course vs non-course resources, release calendar, exceptions and dedicated destination vs shared catalogue. |
| TV-56 | UNVERIFIED — current source/runtime | Inventory actual Masteriyo configuration/features and integrations, course/lesson/product links, published listings, ACF Person relations, Bricks templates, gift/cohort/assessment/certificate support and registration/role controls. |
| TV-57 | UNVERIFIED — controlled end-to-end | After approval, prove free enrolment, standalone purchase-to-enrolment, PMPro/Member Perks included access and pricing, gifts, appropriate account identity, duplicates/idempotency and access expiry/renewal. |
| TV-58 | UNVERIFIED — learning progression | Verify self-paced resume/progress, scheduled/cohort delivery and changes, assessments, certificate issuance/verification under approved conditions; test failure/recovery and cross-user isolation. |
| TV-59 | UNVERIFIED — dashboard/access | Reconcile My Voelgoed unified learner views, session/cache, course ownership/source, entitlement changes, progress persistence, history/certificates under approved retention rules and Bricks presentation. |
| TV-60 | UNVERIFIED — migration | Inventory old LearnDash/LearnPress course structures and student progress, source account identity and provenance, duplicates/revisions, unmappable assessment/certificate records; flag exceptions and preserve evidence rather than fabricating completions. |

No new CPT, field, plugin, status enum, course enrolment, benefit activation, data migration, runtime change, deployment or merge has been authorised.

### 22.6 Next domain — Events and Ticketing (OPEN)

Preserve existing `tc_events` + `event_category` authority, native Tickera tickets/attendees, Woo order/payment owner and externally developed Fast Check-in workflows without assuming their current sync integrity. Ask foundation questions:

- **EV-01 Event scope:** one public event catalogue covering Voelgoed-owned and external organiser events, in-person and online/hybrid delivery, with event-type filtering as needed; no new CPT per event type.
- **EV-02 Ticket offerings:** paid/free, ordinary/VIP, reserved-seat vs general admission, multiple ticket types, organiser-managed complimentary/group tickets and member benefits. Clarify required capabilities without specifying all price rules.
- **EV-03 Purchase and attendee identity:** separate buyer, ticket holder and attendee; guest checkout policy already C-03, capture named attendee details, reassign/transfer tickets where allowed, owner of each fact.
- **EV-04 Operational check-in:** authoritative ticket issuance and attendee sync into Fast Check-in, scanners/offline modes, duplicate suppression and reconciliation to paid orders; clarify critical reliability requirement, not assumed integrity.
- **EV-05 Event changes:** postponement, cancellation, refunds, transfer and reissue, staff organiser access, sales/attendance reporting; defer exact guards until second-pass lifecycle design.

Written authority in `AGENTS.md` (lines 501–530 and 895–919) assigns WooCommerce orders, Tickera event/ticket ownership, `tc_events` with `event_category` rather than separate CPTs per type, and existing Event Participation for contextual Person roles. Do not reimplement provider domains. Source snapshots and Fast Check-in status require independent verification.

### 22.7 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.2.5 | 2026-11-22 | Captured L-01–L-05, F-65–F-73, TV-56–TV-60, current written authorities and unverified learning lifecycle issues; next Events/Ticketing domain. | Prior Learning first-pass OPEN only; preserves all historical answers and other open/technical items. |

**End of v0.2.5. Next owner domain: Events and Ticketing (EV-01–EV-05).**


---

## 23. Version 0.2.6 — Events and Ticketing first-pass checkpoint

**Recorded:** 22 November 2026 (Africa/Johannesburg)
**Authority:** OWNER-CONFIRMED TO-BE business requirements; current source/runtime integration independently UNVERIFIED.
**Scope:** First-pass event/ticketing business scope; not acceptance of payment, issue, reconciliation, organiser analytics or scanner implementation.

### 23.1 Exact owner submission

**Questions:** EV-01 event catalogue; EV-02 ticket capabilities; EV-03 attendee identity; EV-04 Fast Check-in integration; EV-05 organiser operations and access.

> VG Nova breadth-first discovery — Events and Ticketing checkpoint.
> EV-01 Event scope: Voelgoed-owned and external organiser events, including in-person, online and hybrid.
> EV-02 Ticket capabilities: Paid general-admission tickets; Free registrations / tickets; VIP and multiple ticket tiers; Reserved seats / seat selection; Group and complimentary tickets; Membership discounts and free member tickets
> EV-03 Attendee identity: Named attendees optional unless specifically required.
> EV-04 Fast Check-in: Near-real-time sync plus periodic full reconciliation and offline-safe scanning where supported.
> EV-05 Organiser operations: Staff manage changes/refunds, organisers have read-only reporting.
> Additional requirements: We will use EventSales in the future for sales and analytics for organizers as well
> Record in VGN-56 and next cumulative Markdown checkpoint, preserving all prior decisions. Separate business requirements from technical/runtime verification, document lifecycle gaps, and continue breadth-first to Programmes and Challenges.

### 23.2 Confirmed business rules

| ID | Classification | Rule / scope |
|---|---|---|
| EV-01 / F-74 | OWNER-CONFIRMED | One event offering supports Voelgoed-owned and external organiser events, delivered in person, online or hybrid. An external organiser does not automatically get operational admin access. Retain existing Tickera event identity and event-type taxonomy; do not invent one CPT per event type. |
| EV-02 / F-75 | OWNER-CONFIRMED | Ticket capability scope includes paid general admission; free tickets/registration; VIP and multiple price/ticket tiers; reserved seating/seat selection; group and complimentary tickets; member ticket discounts and free member tickets. Availability of these functions in current Tickera, Woo or Member Perks configuration is not established. |
| EV-03 / F-76 | OWNER-CONFIRMED | Ticket holders/attendees may be anonymous or unnamed unless an event/ticket type or another valid event rule specifically requires personal identification. The purchaser, Woo account, named attendee, ticket holder and scanner operator are different concepts; do not force each attendee into a Mense contributor record or user account. Exact conditional attendee metadata, transfers and ticket ownership checks remain open. This is compatible with C-03 guest checkout for physical goods/tickets. |
| EV-04 / F-77 | OWNER-CONFIRMED | Target operational integration: near-real-time attendee synchronisation, periodic full reconciliation and offline-safe Fast Check-in scanning where supported. Near-real-time is a target, not proof that Tickera attendee creation, API synchronisation, Fast Check-in database import and device/offline cache work consistently. Reconciliation must check identity and ticket-level matching, not merely counts. |
| EV-05 / F-78 | OWNER-CONFIRMED | Voelgoed staff administer event changes/refunds. External organisers receive **read-only**, scoped sales/attendance reporting; no general refund, ticket mutation or staff privileges are implied. |
| EV-05 addition / F-79 | OWNER-CONFIRMED FUTURE DIRECTION | **EventSales** is intended to supply future organiser sales reporting and analytics. This does not mean EventSales is currently integrated, has proven authoritative order figures, or replaces WooCommerce, Tickera, Fast Check-in or their respective domain identities. Keep read-only organiser access and data isolation as explicit requirements. |

Preserve E-03 commercial-content separation, MP-01 contributors/Mense, MP-03 long-lived Vennote boundaries, M-03B member ticket discounts/free tickets, C-02 compatible mixed baskets and C-03 guest ticket checkout. An event organiser may be a business account/entity distinct from a long-lived advertiser/sponsor Vennote; no new organiser CPT or identity implementation approved. Seat-level holders and named attendees may be captured conditionally without requiring permanent public profiles.

### 23.3 Independently inspected written authority and historical evidence classification

Read default-branch `Carpe-Diem-Media/vgnova/AGENTS.md`, blob `1c181e5d41bf8aef624630032b2c1ff6f0c5a5b1`, on 22 November 2026:

- Lines 501–530: WooCommerce owns products/cart/checkout/orders; Tickera owns event ticketing; PMPro/Member Perks have separate membership/benefit responsibilities. Do not reproduce providers without a demonstrated gap.
- Lines 327–416 and 597–611: Event Participation owns rich contextual Person↔Event role/order/lifecycle facts where implemented. Do not replace with a competing bidirectional ACF field. This is for event contributors, not automatically for ticket holders or check-in attendance.
- Lines 775, 898, 919: `tc_events` and `event_category` are the documented event content model, not independent CPTs per event type.

Previously discussed Fast Check-in exports, Tickera API and device-sync concerns are **owner-reported operational context**, not source/runtime-verified in this checkpoint. Nothing in the inspected AGENTS excerpts proves EventSales' current architecture or data connector. EventSales remains an external/planned integration dependency pending direct repo and running-service inspection. Do not infer the existing Fast Check-in implementation guarantees reconciliation or offline scanning.

### 23.4 Lifecycle obligations and key guards — candidate design only

Distinguish event schedule/publication, ticket inventory/price, Woo purchase/payment, Tickera ticket issuance and attendee identity, ticket cancellation/refund/transfer, Fast Check-in synchronisation, offline scanner validation and EventSales reporting ingestion. They have separate authorities and potentially different effective timestamps and failure paths.

Candidate journeys to validate later: event draft → published/bookable → underway → completed/cancelled/postponed; ticket offered/held/paid/issued/voided/transferred/refunded; attendee expected → imported/synchronised → checked-in or denied/duplicate; offline scan captured → pending sync → reconciled/conflicted/resolved; organiser report ingestion pending → refreshed/lagging/reconciled. **These are discussion candidates only**, not approved provider state enums or a proposed new table schema.

Define for each lifecycle: actor and source authority; transition guard (authoritative payment/ticket issuance and seat/quantity availability, entitlement and refund restrictions, event-specific attendee identity policy); side effects (ticket delivery, member benefit claim, cache/scan updates, notifications and reporting), concurrency and idempotency (duplicate hook/API/device events), secure organiser scoping, exception recovery (outage, late ticket, mismatched identity, duplicate scan, transfer, refund, cancellation/postponement), terminal states and reopen semantics. Offline safety must account for duplicate scans on multiple disconnected devices and eventual conflict adjudication, not merely local scan acceptance. Report data recency and order/refund reconciliation must be visible in EventSales rather than silently appearing fully current.

### 23.5 Open decisions and verification tasks

| ID | Classification | Required decision / evidence |
|---|---|---|
| F-80 | OPEN | Seat/ticket inventory, conditional attendee names, transfer/change eligibility, complimentary/group issuance authority, member discounts/claims, refund/cancellation/postponement and virtual/hybrid viewing entitlement. |
| F-81 | OPEN | Precise sync SLA, reconciliation cadence and identity keys, Fast Check-in offline guarantee and conflict policy, late issuance, manual overrides, QR/unique-token security and duplicate prevention. |
| F-82 | OPEN | External organiser account identity, specific event/report access, approved sales definitions (gross/net/fees/refunds), attendee/privacy exposure, refresh cadence, analytics attribution, EventSales launch and data-source authority. |
| TV-61 | UNVERIFIED — inspect | Inventory actual `tc_events`, `event_category`, Tickera ticket/seat/attendee hooks and Woo mapping, event contributor relationships, Member Perks ticket claim flags and current Bricks event views. |
| TV-62 | UNVERIFIED — end-to-end | With authorised test data, reconcile Woo completed orders/items to authoritative Tickera issued tickets, attendees, Fast Check-in stored attendee rows and scanner device datasets by stable IDs and statuses, not just aggregate counts. Include guest, free, group, VIP, reserved seat and late sale scenarios as applicable. |
| TV-63 | UNVERIFIED — sync/offline | Observe near-real-time sync delays, retries, deletes/voids/refunds/transfers, periodic full reconciliation, offline scans, conflict resolution, duplicate detection across devices and loss/replay recovery; verify operational runbooks. |
| TV-64 | UNVERIFIED — EventSales | Inspect actual EventSales source, environment, ingest connectors and reporting permissions; map authoritative order/payment/ticket/check-in sources, event scope, refund status, timezone, reconciliation and freshness before asserting organiser analytics readiness. |
| TV-65 | UNVERIFIED — security/operations | Prove organiser read-only event-scoped reporting (including direct URLs/API), disallow refunds/changes for organisers, verify staff permissions, privacy, audit logs and failure modes. |
| TV-66 | UNVERIFIED — migration | Reconcile legacy event, ticket, buyer, attendee, seat, check-in and reporting identities and statuses. Preserve historical tickets and check-in events; no fabricated attendees or reissued tickets during migration. |

No new CPT, taxonomy, plugin, data store, provider integration, API mutation, migration, deployment or merge approved.

### 23.6 Next domain — Programmes and Challenges (OPEN)

Move breadth-first; don't infer that every named initiative requires a unique CPT or membership programme.

- **P-01 Programme inventory:** determine which recurring programmes and challenges are active and their umbrella relationships: Nuwe Jy/60 Dae Uitdaging, Walk in Faith, Mentorklas, Voelgoed LIVE, faith/wellness initiatives and any others; distinguish one recurring offering from individual runs or editions. Existing S1-Q01 status and Mentorklas L-05 rules remain valid.
- **P-02 Participation model:** should participants enrol in a challenge/programme independently of buying a product or course? Can people join free, pay once, or qualify through membership? Do not infer every participant is a public Mense contributor record.
- **P-03 Edition and content:** runs/rounds/dates, daily or weekly content releases, programme content types, cohort grouping and participant progress—identify first-pass requirements without new data structures.
- **P-04 Engagement and outcomes:** reminders, challenges, activity/check-ins, progress, community support, certificates/prizes and surveys if in scope; no assumed health-data collection or membership access bypass.
- **P-05 Cross-domain rights:** how programmes use existing courses, products, events, editorial content, memberships and Person contributors; distinguish ownership, access, lifetime and reporting without duplicate entities.

After checkpoint proceed to the next uncovered domain in the breadth-first roadmap, keeping prior Voelgoed LIVE detail deferred and leaving all technical authority/runtime verification open.

### 23.7 Append-only revision entry

| Version | Date | Change | Supersedes |
|---|---|---|---|
| 0.2.6 | 2026-11-22 | Captured EV-01–EV-05 and future EventSales direction; F-74–F-82; TV-61–TV-66; written authority, reliability and organiser-read-only boundaries; opened Programmes and Challenges. | Prior Events/Ticketing first-pass OPEN questions only; all earlier answers and technical blockers preserved. |

**End of v0.2.6. Next owner domain: Programmes and Challenges (P-01–P-05).**
