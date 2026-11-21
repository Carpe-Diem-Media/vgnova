# VG Nova — Business Grilling & Requirements Register

**Document version:** 0.1.4  
**Filename:** `VGNOVA_BUSINESS_GRILLING_REQUIREMENTS_REGISTER_v0.1.4.md`  
**Created:** 21 November 2026 (Africa/Johannesburg)  
**State:** `DISCOVERY — IN PROGRESS` (business requirements confirmed; implementation not approved)  
**Latest accepted interview answer:** `S1-Q09B` — episode numbering restarts at 1 within each season  
**Next interview question:** `S1-Q10` — episode metadata required by the editorial team  
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
