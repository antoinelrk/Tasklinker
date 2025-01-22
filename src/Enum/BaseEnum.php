<?php

namespace App\Enum;

trait BaseEnum
{
    /**
     * Return the enum as an array.
     *
     * [string $key => mixed $value]
     *
     * @return array
     */
    public static function asArray(): array
    {
        $array = [];

        foreach (static::cases() as $case) {
            $array[$case->name] = $case->value;
        }

        return $array;
    }

    /**
     * Return the enum as an array.
     *
     * [int $key => mixed $value]
     *
     * @return array
     */
    public static function asArrayInt(): array
    {
        $array = [];

        foreach (static::cases() as $case) {
            $array[] = $case->value;
        }

        return $array;
    }

    /**
     * Return the enum's cases keys
     *
     * @return array
     */
    public static function caseKeys(): array
    {
        return array_keys(self::asArray());
    }

    /**
     * Check if a given case key exists
     *
     * @param string $key
     *
     * @return bool
     */
    public static function caseKeyExists(string $key): bool
    {
        return in_array($key, self::caseKeys());
    }

    /**
     * Return a case's value from its key
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function getValue(string $key): ?string
    {
        return self::asArray()[$key] ?? null;
    }

    /**
     * Return the enum as an array with lowercase keys.
     *
     * [string $key => mixed $value]
     *
     * @return array
     */
    public static function asLowercaseKeyArray(): array
    {
        return array_change_key_case(self::asArray());
    }

    /**
     * Get the enum as an array formatted for a select.
     *
     * [mixed $value => string description]
     *
     * @return array
     */
    public static function asSelectArray(): array
    {
        $keys = static::asArray();
        $values = array_map(
            fn ($key) => ucfirst($key),
            $keys
        );

        return array_combine($keys, $values);
    }

    /**
     * Get the default localization key.
     *
     * @return string
     */
    public static function getLocalizationKey(): string
    {
        return 'enums.' . static::class;
    }
}