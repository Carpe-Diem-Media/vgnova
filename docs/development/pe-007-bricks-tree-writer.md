# PE-007 Bricks tree writer for VGN-5B

This writer applies the approved Journey Intent edit to Bricks template `1733` and page `933`. It runs as a WP-CLI child of `site-code/bin/vgnova-runtime-mutate.php` and calls the installed Bricks `wp_ajax_bricks_save_post` handler. It does not call Bricks MCP or write Bricks postmeta directly.

The entrypoint asserts the live PE-007 session for `vgnova-local-10039` before reading the plan or invoking a write. It reasserts the session on every target read and immediately before each Bricks save. It stops unless WordPress resolves to `http://localhost:10039/`, Bricks is exactly `2.4.5`, and the current WP-CLI user can edit both approved targets through Bricks.

## Plan contract

The operator supplies one private JSON plan file and its SHA-256 as positional WP-CLI arguments. The file must be a regular file owned by the current user, have no group or world permissions, and be no larger than 4 MiB. The plan contains exactly both approved post IDs, the Bricks version, each complete before tree, each complete proposed tree, SHA-256 digests of both trees, and exact lists of changed and added element IDs.

The writer checks both live trees and both Bricks sanitization results before the first save. It rejects removed elements, added or reordered top-level content, unlisted additions, unlisted modifications, unexpected post types, template-type changes, stale before trees, and plans whose tree digests do not match their contents. It rereads each target immediately before saving and verifies strict array equality plus the after-tree SHA-256 after Bricks returns success.

Bricks' native handler also creates its normal post revision, updates the post modified time and editor-mode metadata, and runs the site's save hooks. It may regenerate Bricks CSS files. Those effects remain within this bounded Bricks edit. The operation does not submit Journey Intent forms or change Course, membership, commerce, or Masteriyo records.

The writer does not retry or automatically roll back a failed save. If a target fails after another has passed verification, the output lists the completed target and the failing stage. Stop, inspect both trees read-only, and prepare a separately reviewed recovery plan before another write.

## Guarded invocation

Before execution, refresh both before trees and the proposed after trees. Set the plan file to mode `0600`, confirm its owner, and obtain the SHA-256 of the exact file. Close the Bricks Builder for both targets and confirm no other writer is editing them. Then run one coordinator child command:

```sh
php site-code/bin/vgnova-runtime-mutate.php \
  --target=vgnova-local-10039 \
  --wp-root="/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public" \
  --db-socket="/home/jcschoeman96/.config/Local/run/nAZNpFs99/mysql/mysqld.sock" \
  --purpose="VGN-5B Bricks trees 1733 and 933" \
  -- \
  wp --path="/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public" \
    --user="APPROVED_EDITOR_LOGIN" \
    eval-file site-code/runtime-tools/write-bricks-tree.php \
    PRIVATE_PLAN_JSON_PATH REVIEWED_PLAN_SHA256
```

Replace the three uppercase placeholders with the approved editor login, the absolute private plan path, and the exact SHA-256. Do not include the plan contents in command output or logs.

The owner has approved the bounded Bricks edit, but this source PR adds no runtime authorization. Review and merge the writer first. Before any later invocation, refresh the runtime identities and complete before trees, review the exact plan and its SHA-256, confirm PE-007 lock availability and PE-008 idle status, and stop on any drift.
