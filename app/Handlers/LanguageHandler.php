<?php

namespace App\Handlers;

use App\Models\Language;

class LanguageHandler
{
    /**
     * @param array $slugs
     * @return array
     */
    public function getIds(array $slugs): array
    {
        return Language::whereIn('slug', $slugs)
            ->get()
            ->pluck('id')
            ->toArray();
    }
}
