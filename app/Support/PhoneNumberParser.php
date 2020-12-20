<?php

namespace App\Support;

class PhoneNumberParser
{
    const REGEX = '/\(?(010|\+?82\)?\s?[.=\-\/]?\s?\(?0?\)?10)\s?[.=\-\/]?\s?(\d{4})\s?[.=\-\/]?\s?(\d{4})/';

    /**
     * @param string $phone
     * @return string|null
     */
    public function parse(string $raw): ?string
    {
        $texts = explode("\n", $raw);

        foreach ($texts as $val) {
            if (preg_match(self::REGEX, $val, $matches)) {
                return "010-{$matches[2]}-{$matches[3]}";
            }
        }

        return null;
    }

    public function international(string $phone): string
    {
        return '+82'.preg_replace(['/\-/', '/^0/'], null, $phone);
    }
}
