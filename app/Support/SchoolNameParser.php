<?php

namespace App\Support;

use Illuminate\Support\Str;

class SchoolNameParser
{
    /**
     * @param string $phone
     * @return string|null
     */
    public function college(string $raw): ?string
    {
        if (preg_match('/([가-힣]*?대학교)/', $raw, $matches)) {
            return $matches[1];
        }

        $texts = explode("\n", strtolower(Str::lower($raw)));
        foreach ($texts as $key => $val) {
            if ($val === 'university of') {
                return Str::title("{$val} {$texts[$key + 1]}");
            }
            if (preg_match('/university of \w+/', $val)) {
                if (preg_match('/^at \w+/', $texts[$key + 1])) {
                    return Str::title("{$val} {$texts[$key + 1]}");
                }
                return Str::title("{$val}");
            }
            if (preg_match('/\w+ institute of \w+/', $val)) {
                return Str::title("{$val}");
            }
            if (preg_match('/^(state university|university|community college|college)$/', $val)) {
                return Str::title("{$texts[$key - 1]} {$val}");
            }
            if (preg_match('/\w+ (university|college)/', $val)) {
                return Str::title("{$val}");
            }
        }

        return null;
    }
}
