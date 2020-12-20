<?php

namespace Database\Seeders;

use App\Enums\ProfessionEnum;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProfessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $professions = [
            'name' => 'professions',
            'slug' => 'professions',
            'depth' => 1,
            'children' => [
                [
                    'name' => '학생',
                    'slug' => ProfessionEnum::student(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '의대',
                            'slug' => ProfessionEnum::school_of_medicine(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '약대',
                            'slug' => ProfessionEnum::school_of_pharmacy(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '수의대',
                            'slug' => ProfessionEnum::school_of_veterinary_medicine(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '간호대',
                            'slug' => ProfessionEnum::school_of_nursing(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '법과대',
                            'slug' => ProfessionEnum::school_of_law(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '공대',
                            'slug' => ProfessionEnum::school_of_engineering(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '자연과학대',
                            'slug' => ProfessionEnum::school_of_science(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '경영대',
                            'slug' => ProfessionEnum::school_of_business(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '인문대',
                            'slug' => ProfessionEnum::school_of_language_and_literature(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '사범대/교대',
                            'slug' => ProfessionEnum::school_of_education(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '음대',
                            'slug' => ProfessionEnum::school_of_music(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '미대',
                            'slug' => ProfessionEnum::school_of_arts(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '체대',
                            'slug' => ProfessionEnum::school_of_sports(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '사관학교',
                            'slug' => ProfessionEnum::military_academy(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타전공',
                            'slug' => ProfessionEnum::school_of_other_categories(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '사업가',
                    'slug' => ProfessionEnum::entrepreneur(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '스타트업',
                            'slug' => ProfessionEnum::startup(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '벤쳐캐피탈',
                            'slug' => ProfessionEnum::venture_capital(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '부동산투자',
                            'slug' => ProfessionEnum::real_estate_investment(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '자영업',
                            'slug' => ProfessionEnum::small_business(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '프랜차이즈',
                            'slug' => ProfessionEnum::franchise(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '프리랜서',
                            'slug' => ProfessionEnum::freelancer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타사업가',
                            'slug' => ProfessionEnum::other_entrepreneur(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '의료직',
                    'slug' => ProfessionEnum::medical(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '의사',
                            'slug' => ProfessionEnum::doctor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '한의사',
                            'slug' => ProfessionEnum::oriental_doctor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '약사',
                            'slug' => ProfessionEnum::pharmacist(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '한약사',
                            'slug' => ProfessionEnum::oriental_pharmacist(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '수의사',
                            'slug' => ProfessionEnum::vet(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '간호사',
                            'slug' => ProfessionEnum::nurse(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 의료직',
                            'slug' => ProfessionEnum::other_medical(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '법조인',
                    'slug' => ProfessionEnum::legal(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '판사',
                            'slug' => ProfessionEnum::judge(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '검사',
                            'slug' => ProfessionEnum::prosecutor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '변호사',
                            'slug' => ProfessionEnum::lawyer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 법관련직',
                            'slug' => ProfessionEnum::other_legal(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '전문직',
                    'slug' => ProfessionEnum::high_paying_profession(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '감정평가사',
                            'slug' => ProfessionEnum::public_appraiser(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '건축사',
                            'slug' => ProfessionEnum::architect(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '경영지도사',
                            'slug' => ProfessionEnum::management_consultant(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '펀드매니저',
                            'slug' => ProfessionEnum::fund_manager(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '공인노무사',
                            'slug' => ProfessionEnum::labor_consultant(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '공인회계사',
                            'slug' => ProfessionEnum::public_accountant(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '관세사',
                            'slug' => ProfessionEnum::customs_agent(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '법무사',
                            'slug' => ProfessionEnum::judicial_scrivener(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '변리사',
                            'slug' => ProfessionEnum::patent_attorney(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '보험계리사',
                            'slug' => ProfessionEnum::actuary(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '세무사',
                            'slug' => ProfessionEnum::tax_accountant(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '손해사정사',
                            'slug' => ProfessionEnum::claim_adjuster(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '자산관리사',
                            'slug' => ProfessionEnum::financial_planner(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '항공 조종사',
                            'slug' => ProfessionEnum::pilot(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '항공 승무원',
                            'slug' => ProfessionEnum::flight_attendant(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '항공교통관제사',
                            'slug' => ProfessionEnum::air_traffic_controller(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '측량사',
                            'slug' => ProfessionEnum::surveyor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '행정사',
                            'slug' => ProfessionEnum::public_attorney(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 전문직',
                            'slug' => ProfessionEnum::other_high_paying_profession(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '금융직',
                    'slug' => ProfessionEnum::financial(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '은행직',
                            'slug' => ProfessionEnum::bank(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '증권직',
                            'slug' => ProfessionEnum::securities(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '보험직',
                            'slug' => ProfessionEnum::insurance(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '세무/회계직',
                            'slug' => ProfessionEnum::accounting(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 금융직',
                            'slug' => ProfessionEnum::other_financial(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '연구직',
                    'slug' => ProfessionEnum::research(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => 'IT연구원',
                            'slug' => ProfessionEnum::it_researcher(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '반도체연구원',
                            'slug' => ProfessionEnum::semiconductor_researcher(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '과학연구원',
                            'slug' => ProfessionEnum::science_researcher(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '화학연구원',
                            'slug' => ProfessionEnum::chemical_researcher(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기계연구원',
                            'slug' => ProfessionEnum::mechanical_researcher(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '바이오연구원',
                            'slug' => ProfessionEnum::bio_researcher(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타연구원',
                            'slug' => ProfessionEnum::other_researcher(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '엔지니어',
                    'slug' => ProfessionEnum::engineer(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '하드웨어 엔지니어',
                            'slug' => ProfessionEnum::hardware_engineer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '소프트웨어 엔지니어',
                            'slug' => ProfessionEnum::software_engineer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '항공 기관사',
                            'slug' => ProfessionEnum::flight_engineer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '선박 기관사',
                            'slug' => ProfessionEnum::marine_engineer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '전기 엔지니어',
                            'slug' => ProfessionEnum::electrical_engineer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '건축 엔지니어',
                            'slug' => ProfessionEnum::architectual_engineer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기계 엔지니어',
                            'slug' => ProfessionEnum::mechanical_engineer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 기술직',
                            'slug' => ProfessionEnum::other_engineer(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '디자인직',
                    'slug' => ProfessionEnum::design(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '그래픽 디자이너',
                            'slug' => ProfessionEnum::graphic_designer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => 'UI/UX 디자이너',
                            'slug' => ProfessionEnum::ui_ux_designer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '패션 디자이너',
                            'slug' => ProfessionEnum::fashion_designer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '인테리어 디자이너',
                            'slug' => ProfessionEnum::interior_designer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '제품 디자이너',
                            'slug' => ProfessionEnum::product_designer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 디자이너',
                            'slug' => ProfessionEnum::other_designer(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '교육직',
                    'slug' => ProfessionEnum::teaching(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '유치원 교사',
                            'slug' => ProfessionEnum::kindergarten_teacher(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '초/중/고 교사',
                            'slug' => ProfessionEnum::school_teacher(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '교수',
                            'slug' => ProfessionEnum::professor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '학원강사',
                            'slug' => ProfessionEnum::academy_instructor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '개인교사',
                            'slug' => ProfessionEnum::tutor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 교육직',
                            'slug' => ProfessionEnum::other_teaching(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '방송언론직',
                    'slug' => ProfessionEnum::media(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '언론기자',
                            'slug' => ProfessionEnum::journalist(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '언론편집자',
                            'slug' => ProfessionEnum::journal_editor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '방송작가',
                            'slug' => ProfessionEnum::broadcasting_writer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '배우',
                            'slug' => ProfessionEnum::actor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '희극인',
                            'slug' => ProfessionEnum::comedian(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '성우',
                            'slug' => ProfessionEnum::voice_actor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '모델',
                            'slug' => ProfessionEnum::model(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '아나운서',
                            'slug' => ProfessionEnum::announcer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 방송언론직',
                            'slug' => ProfessionEnum::other_media(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '엔터테인먼트',
                    'slug' => ProfessionEnum::entertainment(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '연예 매니저',
                            'slug' => ProfessionEnum::entertainment_manager(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '프로게이머',
                            'slug' => ProfessionEnum::e_sports_player(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '인터넷방송',
                            'slug' => ProfessionEnum::streamer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '인플루언서',
                            'slug' => ProfessionEnum::influencer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 엔터테인먼트 관련직',
                            'slug' => ProfessionEnum::other_entertainment(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '체육관련직',
                    'slug' => ProfessionEnum::sports(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '감독/코치',
                            'slug' => ProfessionEnum::coach(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '운동선수',
                            'slug' => ProfessionEnum::athlete(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '스포츠 강사',
                            'slug' => ProfessionEnum::sports_instructor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '헬스 트레이너',
                            'slug' => ProfessionEnum::fitness_trainer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 체육관련직',
                            'slug' => ProfessionEnum::other_sports(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '예술관련직',
                    'slug' => ProfessionEnum::arts(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '가수',
                            'slug' => ProfessionEnum::vocalist(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '뮤지션',
                            'slug' => ProfessionEnum::musician(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '댄서',
                            'slug' => ProfessionEnum::dancer(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '화가',
                            'slug' => ProfessionEnum::painter(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '조각가',
                            'slug' => ProfessionEnum::sculptor(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 예술관련직',
                            'slug' => ProfessionEnum::other_arts(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '서비스직',
                    'slug' => ProfessionEnum::service(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '고객 응대직',
                            'slug' => ProfessionEnum::customer_service(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '번역가/통역가',
                            'slug' => ProfessionEnum::language_service(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '미용 서비스',
                            'slug' => ProfessionEnum::beauty_service(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '환경 서비스',
                            'slug' => ProfessionEnum::environmental_services(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '애견 서비스',
                            'slug' => ProfessionEnum::pet_service(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '보안 관련직',
                            'slug' => ProfessionEnum::security_service(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타 서비스',
                            'slug' => ProfessionEnum::other_service(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '공무원',
                    'slug' => ProfessionEnum::government(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '일반공무원',
                            'slug' => ProfessionEnum::government_official(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '외무공무원',
                            'slug' => ProfessionEnum::foreign_service(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '경찰공무원',
                            'slug' => ProfessionEnum::police_force(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '소방공무원',
                            'slug' => ProfessionEnum::fire_service(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '군인',
                            'slug' => ProfessionEnum::military_service(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '군인(병사)',
                            'slug' => ProfessionEnum::soldier(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '기타공무원',
                            'slug' => ProfessionEnum::other_government(),
                            'depth' => 3,
                        ],
                    ],
                ],
                [
                    'name' => '기타',
                    'slug' => ProfessionEnum::other(),
                    'depth' => 2,
                    'children' => [
                        [
                            'name' => '영업직',
                            'slug' => ProfessionEnum::sales(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '유통업',
                            'slug' => ProfessionEnum::retail(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '농업',
                            'slug' => ProfessionEnum::farming(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '어업',
                            'slug' => ProfessionEnum::fishery(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '휴직',
                            'slug' => ProfessionEnum::between_jobs(),
                            'depth' => 3,
                        ],
                        [
                            'name' => '무직',
                            'slug' => ProfessionEnum::none(),
                            'depth' => 3,
                        ],
                    ],
                ],
            ],
        ];

        Category::create($professions);
    }
}
