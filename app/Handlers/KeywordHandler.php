<?php

namespace App\Handlers;

use App\Models\Keyword;

class KeywordHandler
{
    /**
     * @param array $slugs
     * @return array
     */
    public function getIds(array $slugs): array
    {
        return Keyword::whereIn('slug', $slugs)
            ->get()
            ->pluck('id')
            ->toArray();
    }
}
