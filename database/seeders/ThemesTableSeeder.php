<?php

namespace Database\Seeders;

use App\Handlers\MediaHandler;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $items = [
            [
                'name' => '산책/드라이브',
                'slug' => 'stroll',
            ],
            [
                'name' => '식사/술',
                'slug' => 'dining',
            ],
            [
                'name' => '반려동물',
                'slug' => 'animals',
            ],
            [
                'name' => '공연/전시/영화',
                'slug' => 'theater',
            ],
            [
                'name' => '스포츠/레저',
                'slug' => 'sports',
            ],
            [
                'name' => '강연/스터디',
                'slug' => 'lecture',
            ],
            [
                'name' => '봉사활동',
                'slug' => 'charity',
            ],
            [
                'name' => '인문학/글/토론',
                'slug' => 'writing',
            ],
            [
                'name' => '게임/오락',
                'slug' => 'game',
            ],
            [
                'name' => '여행/캠핑',
                'slug' => 'travel',
            ],
            [
                'name' => '페스티벌/스포츠관람',
                'slug' => 'stadium',
            ],
            [
                'name' => '기타주제',
                'slug' => 'others',
            ],
        ];

        $url = 'http://via.placeholder.com/800/FF0000/00FFFF';
        $mediaHandler = new MediaHandler();
        foreach ($items as $item) {
            $theme = Theme::create($item);
            $mediaHandler->saveModelMediaFromUrl($theme, $url);
            $mediaHandler->saveModelMediaFromUrl($theme, $url);
            $mediaHandler->saveModelMediaFromUrl($theme, $url);
            $mediaHandler->saveModelMediaFromUrl($theme, $url);
            $mediaHandler->saveModelMediaFromUrl($theme, $url);
        }
    }
}
