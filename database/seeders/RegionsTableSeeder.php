<?php

namespace Database\Seeders;

use App\Enums\RegionEnum;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $regions = [
            'slug' => RegionEnum::korea(),
            'depth' => 0,
            'children' => [
                [
                    'slug' => RegionEnum::seoul_gangbuk(),
                    'depth' => 1,
                    'children' => [
                        [
                            'slug' => RegionEnum::jongno(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::euljiro(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::gwanghwamun(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::itaewon(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::sinchon_hongdae(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::yeonnam(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::hapjeong_mangwon(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::sangam(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::dongdaemun(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::geondae(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::wangsimni(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::seoungsu(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::seoul_gangbuk_etc(),
                            'depth' => 2,
                        ],
                    ],
                ],
                [
                    'slug' => RegionEnum::seoul_gangnam(),
                    'depth' => 1,
                    'children' => [
                        [
                            'slug' => RegionEnum::gangnam_station(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::apgujeong_sinsa(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::cheongdam(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::seocho(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::yeoksam(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::jamsil(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::yeouido(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::yeongdeungpo(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::mokdong(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::guro(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::gwanak(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::seoul_gangnam_etc(),
                            'depth' => 2,
                        ],
                    ],
                ],
                [
                    'slug' => RegionEnum::gyeonggi(),
                    'depth' => 1,
                    'children' => [
                        [
                            'slug' => RegionEnum::seongnam(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::hanam(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::goyang(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::gimpo(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::bucheon(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::gwangmyeong(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::anyang(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::suwon(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::yongin(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::uijeongbu(),
                            'depth' => 2,
                        ],
                        [
                            'slug' => RegionEnum::gyeonggi_etc(),
                            'depth' => 2,
                        ],
                    ],
                ],
                [
                    'slug' => RegionEnum::incheon(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::busan(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::ulsan(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::daejeon(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::daegu(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::gwangju(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::sejong(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::jeju(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::gangwon(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::northern_jeolla(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::southern_jeolla(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::northern_gyeongsang(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::southern_gyeongsang(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::northern_chungcheong(),
                    'depth' => 1,
                ],
                [
                    'slug' => RegionEnum::southern_chungcheong(),
                    'depth' => 1,
                ],
            ],
        ];

        Region::create($regions);
    }
}
