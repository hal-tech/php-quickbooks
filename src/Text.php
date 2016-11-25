<?php

namespace PhpQuickbooks;

class Text
{
    protected static $ignored_keys = [
        'domain',
        'sparse',
        'value',
        'name',
    ];

    /**
     * @param string $text
     *
     * @return string
     */
    public static function camelCase(string $text): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $text)));
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public static function pascalCase(string $text): string
    {
        if (in_array(strtolower($text), static::$ignored_keys)) {
            return $text;
        }

        return preg_replace_callback("/(?:^|_)([a-z])/", function ($matches) {
            return strtoupper($matches[1]);
        }, $text);
    }
}