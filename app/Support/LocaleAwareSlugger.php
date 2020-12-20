<?php

namespace App\Support;

use Illuminate\Support\Str;

class LocaleAwareSlugger
{
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @return string
     */
    public static function slug($title): string
    {
        $locale = app()->getLocale();

        if ($locale === 'en') {
            return Str::slug($title);
        }

        return Str::slug($title, '-', $locale);
    }
}
