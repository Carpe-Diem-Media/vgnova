<?php
declare(strict_types=1);

namespace Voelgoed\PlatformCore\Person;

defined('ABSPATH') || exit;

/** Hides ACF-maintained reverse relationship fields from Person editors. */
final class PersonRelatedContentFields
{
    private const FIELD_KEYS = [
        'field_vg_person_articles_primary_author',
        'field_vg_person_articles_additional_author',
        'field_vg_person_articles_expert_contributor',
        'field_vg_person_articles_reviewed_by',
        'field_vg_person_courses_lead_instructor',
        'field_vg_person_courses_additional_instructor',
        'field_vg_person_courses_expert',
    ];

    public function register(): void
    {
        foreach (self::FIELD_KEYS as $field_key) {
            add_filter('acf/prepare_field/key=' . $field_key, [self::class, 'hide_derived_field']);
        }
    }

    public static function hide_derived_field(mixed $field): mixed
    {
        if (! is_admin() || ! function_exists('get_current_screen') || ! is_array($field)) {
            return $field;
        }

        $screen = get_current_screen();
        if (! is_object($screen) || ($screen->base ?? null) !== 'post' || ($screen->post_type ?? null) !== 'person') {
            return $field;
        }

        return false;
    }
}
