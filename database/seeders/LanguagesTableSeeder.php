<?php

namespace Database\Seeders;

use App\Enums\LanguageEnum;
use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => '한국어',
                'slug' => LanguageEnum::KOREAN(),
                'code' => 'ko_KR',
                'english' => 'Korean',
                'korean' => '한국어',
                'is_active' => true,
            ],
            [
                'name' => 'English',
                'slug' => LanguageEnum::ENGLISH(),
                'code' => 'en_US',
                'english' => 'English',
                'korean' => '영어',
                'is_active' => true,
            ],
            [
                'name' => '日本語',
                'slug' => LanguageEnum::JAPANESE(),
                'code' => 'ja_JP',
                'english' => 'Japanese',
                'korean' => '일본어',
                'is_active' => true,
            ],
            [
                'name' => 'Bahasa Indonesia',
                'slug' => LanguageEnum::INDONESIAN(),
                'code' => 'in_ID',
                'english' => 'Bahasa Indonesia',
                'korean' => '인도네시아어',
                'is_active' => false,
            ],
            [
                'name' => 'Bahasa Malaysia',
                'slug' => LanguageEnum::MALAY(),
                'code' => 'ms_MY',
                'english' => 'Malay',
                'korean' => '말레이어',
                'is_active' => false,
            ],
            [
                'name' => 'Čeština',
                'slug' => LanguageEnum::CZECH(),
                'code' => 'cs_CZ',
                'english' => 'Czech',
                'korean' => '체코어',
                'is_active' => false,
            ],
            [
                'name' => 'Dansk',
                'slug' => LanguageEnum::DANISH(),
                'code' => 'da_DK',
                'english' => 'Danish',
                'korean' => '덴마크어',
                'is_active' => false,
            ],
            [
                'name' => 'Deutsch',
                'slug' => LanguageEnum::DUTCH(),
                'code' => 'de_DE',
                'english' => 'German',
                'korean' => '독일어',
                'is_active' => false,
            ],
            [
                'name' => 'Español',
                'slug' => LanguageEnum::SPANISH(),
                'code' => 'es_ES',
                'english' => 'Spanish',
                'korean' => '스페인어',
                'is_active' => false,
            ],
            [
                'name' => '正體中文',
                'slug' => LanguageEnum::TAIWANESE(),
                'code' => 'zh_TW',
                'english' => 'Chinese-Traditional',
                'korean' => '중국어-번체',
                'is_active' => false,
            ],
            [
                'name' => '简体中文',
                'slug' => LanguageEnum::CHINESE(),
                'code' => 'zh_CN',
                'english' => 'Chinese-Simplified',
                'korean' => '중국어-간체',
                'is_active' => false,
            ],
            [
                'name' => 'Français',
                'slug' => LanguageEnum::FRENCH(),
                'code' => 'fr_FR',
                'english' => 'French',
                'korean' => '프랑스어',
                'is_active' => false,
            ],
            [
                'name' => 'Italiano',
                'slug' => LanguageEnum::ITALIAN(),
                'code' => 'it_IT',
                'english' => 'Italian',
                'korean' => '이탈리아어',
                'is_active' => false,
            ],
            [
                'name' => 'Nederlands',
                'slug' => LanguageEnum::DUTCH(),
                'code' => 'nl_NL',
                'english' => 'Dutch',
                'korean' => '네덜란드어',
                'is_active' => false,
            ],
            [
                'name' => 'Norsk',
                'slug' => LanguageEnum::NORWEGIAN(),
                'code' => 'no_NO',
                'english' => 'Norwegian',
                'korean' => '노르웨이어',
                'is_active' => false,
            ],
            [
                'name' => 'Polski',
                'slug' => LanguageEnum::POLISH(),
                'code' => 'pl_PL',
                'english' => 'Polish',
                'korean' => '폴란드어',
                'is_active' => false,
            ],
            [
                'name' => 'Português',
                'slug' => LanguageEnum::PORTUGUESE(),
                'code' => 'pt_BR',
                'english' => 'Portuguese',
                'korean' => '포르투갈어',
                'is_active' => false,
            ],
            [
                'name' => 'Română',
                'slug' => LanguageEnum::ROMANIAN(),
                'code' => 'ro_RO',
                'english' => 'Romanian',
                'korean' => '루마니아어',
                'is_active' => false,
            ],
            [
                'name' => 'Русский',
                'slug' => LanguageEnum::RUSSIAN(),
                'code' => 'ru_RU',
                'english' => 'Russian',
                'korean' => '러시아어',
                'is_active' => false,
            ],
            [
                'name' => 'Svenska',
                'slug' => LanguageEnum::SWEDISH(),
                'code' => 'sv_SE',
                'english' => 'Swedish',
                'korean' => '스웨덴어',
                'is_active' => false,
            ],
            [
                'name' => 'Tagalog',
                'slug' => LanguageEnum::TAGALOG(),
                'code' => 'tl_PH',
                'english' => 'Tagalog',
                'korean' => '필리핀어',
                'is_active' => false,
            ],
            [
                'name' => 'ภาษาไทย',
                'slug' => LanguageEnum::THAI(),
                'code' => 'th_TH',
                'english' => 'Thai',
                'korean' => '태국어',
                'is_active' => false,
            ],
            [
                'name' => 'Türkçe',
                'slug' => LanguageEnum::TURKISH(),
                'code' => 'tr_TR',
                'english' => 'Turkish',
                'korean' => '터키어',
                'is_active' => false,
            ],
            [
                'name' => 'العربية',
                'slug' => LanguageEnum::ARABIC(),
                'code' => 'ar_AE',
                'english' => 'Arabic',
                'korean' => '아랍어',
                'is_active' => false,
            ],
        ];

        foreach ($tags as $tag) {
            Language::create([
                'name'       => $tag['name'],
                'slug'       => $tag['slug'],
                'code'       => $tag['code'],
                'english'    => $tag['english'],
                'korean'     => $tag['korean'],
                'is_active'  => $tag['is_active'],
            ]);
        }
    }
}
