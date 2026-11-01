<?php
declare(strict_types=1);

final class PersonNativeRelationshipSchemaTest
{
    private const INSTRUCTIONS = 'Select canonical Person records. Published, draft, and private People are available. This source field owns the relationship. Public templates display published related records only.';

    public function run(): void
    {
        $this->assert_person_related_content_group();
        $this->assert_article_source_group();
        $this->assert_course_source_group();
    }

    private function assert_person_related_content_group(): void
    {
        $group = $this->load_group('group_vg_person_related_content.json');
        $this->assert_same('group_vg_person_related_content', $group['key'] ?? null, 'Person related content group key must stay canonical.');
        $this->assert_same('Person Related Content', $group['title'] ?? null, 'Person related content group title must stay canonical.');
        $this->assert_same([
            [[
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'person',
            ]],
        ], $group['location'] ?? null, 'Person related content fields must appear only on Person records.');

        $expected = [
            'field_vg_person_articles_primary_author' => ['person_articles_primary_author', 'post'],
            'field_vg_person_articles_additional_author' => ['person_articles_additional_author', 'post'],
            'field_vg_person_articles_expert_contributor' => ['person_articles_expert_contributor', 'post'],
            'field_vg_person_articles_reviewed_by' => ['person_articles_reviewed_by', 'post'],
            'field_vg_person_courses_lead_instructor' => ['person_courses_lead_instructor', 'mto-course'],
            'field_vg_person_courses_additional_instructor' => ['person_courses_additional_instructor', 'mto-course'],
            'field_vg_person_courses_expert' => ['person_courses_expert', 'mto-course'],
        ];
        $fields = $group['fields'] ?? null;
        $this->assert_true(is_array($fields), 'Person related content fields must be an array.');
        $this->assert_same(7, count($fields), 'Person related content must contain exactly seven top-level fields.');

        $actual_keys = array_map(
            static fn (array $field): mixed => $field['key'] ?? null,
            $fields
        );
        $this->assert_same(array_keys($expected), $actual_keys, 'Person related content field keys and order must match the approved schema.');

        foreach ($expected as $field_key => [$field_name, $post_type]) {
            $field = $this->find_field($fields, $field_key);
            $this->assert_same($field_name, $field['name'] ?? null, "{$field_key} must keep its approved field name.");
            $this->assert_same('relationship', $field['type'] ?? null, "{$field_key} must use ACF Relationship behavior.");
            $this->assert_same([$post_type], $field['post_type'] ?? null, "{$field_key} must target only {$post_type} records.");
            $this->assert_same('id', $field['return_format'] ?? null, "{$field_key} must return IDs.");
            $this->assert_same(0, $field['min'] ?? null, "{$field_key} must allow zero related records.");
            $this->assert_same(0, $field['max'] ?? null, "{$field_key} must use ACF's unlimited maximum.");
            $this->assert_same([], $field['bidirectional_target'] ?? [], "{$field_key} must not point back to a source field.");
            $this->assert_true(! array_key_exists('multiple', $field), "{$field_key} must use native Relationship multiplicity without an unsupported multiple setting.");
        }
    }

    private function assert_article_source_group(): void
    {
        $this->assert_source_group(
            'group_vg_article_people.json',
            'group_vg_article_people',
            'post',
            [
                'field_vg_article_primary_author' => ['primary_author', 0, 'field_vg_person_articles_primary_author'],
                'field_vg_article_additional_authors' => ['additional_authors', 1, 'field_vg_person_articles_additional_author'],
                'field_vg_article_expert_contributors' => ['expert_contributors', 1, 'field_vg_person_articles_expert_contributor'],
                'field_vg_article_reviewed_by' => ['reviewed_by', 1, 'field_vg_person_articles_reviewed_by'],
            ]
        );
    }

    private function assert_course_source_group(): void
    {
        $this->assert_source_group(
            'group_vg_course_people.json',
            'group_vg_course_people',
            'mto-course',
            [
                'field_vg_course_lead_instructor' => ['lead_instructor', 0, 'field_vg_person_courses_lead_instructor'],
                'field_vg_course_additional_instructors' => ['additional_instructors', 1, 'field_vg_person_courses_additional_instructor'],
                'field_vg_course_experts' => ['experts', 1, 'field_vg_person_courses_expert'],
            ]
        );
    }

    /** @param array<string, array{string, int, string}> $expected_fields */
    private function assert_source_group(string $filename, string $group_key, string $source_post_type, array $expected_fields): void
    {
        $group = $this->load_group($filename);
        $this->assert_same($group_key, $group['key'] ?? null, "{$filename} group key must remain unchanged.");
        $this->assert_same([
            [[
                'param' => 'post_type',
                'operator' => '==',
                'value' => $source_post_type,
            ]],
        ], $group['location'] ?? null, "{$filename} location must remain on {$source_post_type}.");

        $fields = $group['fields'] ?? null;
        $this->assert_true(is_array($fields), "{$filename} fields must be an array.");
        $this->assert_same(count($expected_fields), count($fields), "{$filename} must retain exactly its existing source fields.");

        foreach ($expected_fields as $field_key => [$field_name, $multiple, $target_key]) {
            $field = $this->find_field($fields, $field_key);
            $this->assert_same($field_name, $field['name'] ?? null, "{$field_key} name must remain unchanged.");
            $this->assert_same('post_object', $field['type'] ?? null, "{$field_key} must remain a Post Object field.");
            $this->assert_same(['person'], $field['post_type'] ?? null, "{$field_key} must continue to select Person records.");
            $this->assert_same(['publish', 'draft', 'private'], $field['post_status'] ?? null, "{$field_key} must retain all approved Person statuses.");
            $this->assert_same('id', $field['return_format'] ?? null, "{$field_key} must continue to return IDs.");
            $this->assert_same($multiple, $field['multiple'] ?? null, "{$field_key} cardinality must remain unchanged.");
            $this->assert_same(1, $field['allow_null'] ?? null, "{$field_key} must remain optional.");
            $this->assert_same(0, $field['required'] ?? null, "{$field_key} must remain non-required.");
            $this->assert_same([$target_key], $field['bidirectional_target'] ?? null, "{$field_key} must update only its approved Person target.");
            $this->assert_same(self::INSTRUCTIONS, $field['instructions'] ?? null, "{$field_key} instructions must describe native relationship ownership and public visibility.");
        }
    }

    /** @return array<string, mixed> */
    private function load_group(string $filename): array
    {
        $path = dirname(__DIR__, 2) . '/acf-json/' . $filename;
        if (! is_file($path)) {
            throw new RuntimeException("Missing ACF Local JSON file: {$path}");
        }

        $group = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($group)) {
            throw new RuntimeException("ACF Local JSON root must be an object: {$path}");
        }

        return $group;
    }

    /** @param array<int, mixed> $fields
     *  @return array<string, mixed>
     */
    private function find_field(array $fields, string $field_key): array
    {
        foreach ($fields as $field) {
            if (is_array($field) && ($field['key'] ?? null) === $field_key) {
                return $field;
            }
        }

        throw new RuntimeException("Missing ACF field: {$field_key}");
    }

    private function assert_same(mixed $expected, mixed $actual, string $message): void
    {
        if ($expected !== $actual) {
            throw new RuntimeException(
                $message . PHP_EOL
                . 'Expected: ' . var_export($expected, true) . PHP_EOL
                . 'Actual: ' . var_export($actual, true)
            );
        }
    }

    private function assert_true(bool $condition, string $message): void
    {
        if (! $condition) {
            throw new RuntimeException($message);
        }
    }
}
