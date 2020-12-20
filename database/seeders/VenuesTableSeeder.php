<?php

namespace Database\Seeders;

use App\Handlers\MediaHandler;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class VenuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $venues = [
            [
                'place_id' => 'ChIJbV4Kqi6jfDUReu01VjRdw2c',
                'title' => '마로니에 공원',
                'address' => '대한민국 서울특별시 종로구 이화동 대학로8길 1',
                'description' => '편안한, 재미있는, 멋스러운, 분주한, 화사한',
                'latitude' => 37.5805009,
                'longitude' => 127.0006358,
            ],
            [
                'place_id' => 'ChIJc9c0zgNUezURmIAjGbaKoR8',
                'title' => '애버랜드',
                'address' => '대한민국 경기도 용인시 처인구 포곡읍 에버랜드로 199',
                'description' => '재미있는, 화려한, 편안한, 이국적, 화사한',
                'latitude' => 37.2999561,
                'longitude' => 127.1318137,
            ],
            [
                'place_id' => 'ChIJcd3IZEWkfDURSAhaRS3cqVA',
                'title' => '잠실야구장',
                'address' => '대한민국 서울특별시 송파구 올림픽로 25 (잠실동)',
                'description' => '재미있는, 화려한, 편안한, 쾌적한, 깨끗한',
                'latitude' => 37.5122579,
                'longitude' => 127.0697124,
            ],
        ];

        $images = [
            'https://img6.yna.co.kr/photo/yna/YH/2019/11/04/PYH2019110404960001300_P4.jpg',
            'http://www.newsworks.co.kr/news/photo/201912/415278_310996_4838.jpg',
            'https://ojsfile.ohmynews.com/STD_IMG_FILE/2017/1223/IE002262769_STD.jpg',
        ];

        $mediaHandler = new MediaHandler();
        foreach ($venues as $key => $venue) {
            $venue = Venue::create([
                'place_id' => $venue['place_id'],
                'title' => $venue['title'],
                'address' => $venue['address'],
                'description' => $venue['description'],
                'latitude' => $venue['latitude'],
                'longitude' => $venue['longitude'],
            ]);
            $mediaHandler->saveModelMediaFromUrl($venue, $images[$key]);
        }
    }
}
