<?php

namespace Database\Seeders;

use App\Enums\QuestionEnum;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $questions = [
            'name' => '가입질문',
            'slug' => 'questions',
            'depth' => 0,
            'children' => [
                [
                    'name' => '일상',
                    'slug' => QuestionEnum::daily()->label,
                    'depth' => 1,
                    'children' => [
                        [
                            'name' => '퇴근 후 주로 무엇을 하시나요?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '약속 없는 불금에 난',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '나의 소확행(소소하지만 확실한 행복)',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '요새 배우고 싶은 것',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '꼭 지켜야하는 습관이나 징크스가 있나요?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '가장 좋아하는 스포츠는?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '요새 하는 취미활동이 있다면?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                    ],
                ],
                [
                    'name' => '추억',
                    'slug' => QuestionEnum::memory()->label,
                    'depth' => 1,
                    'children' => [
                        [
                            'name' => '최고의 여행 경험을 소개해주세요',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '이건 다신 안 한다',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '태어나서 받았던 or 줬던 선물 중에 가장 이상한 선물은?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '생애 첫 기억으로 어떤 것이 떠오르나요?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                    ],
                ],
                [
                    'name' => '음식',
                    'slug' => QuestionEnum::food()->label,
                    'depth' => 1,
                    'children' => [
                        [
                            'name' => '최애 술+안주 조합',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '나만의 라면 레시피',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '자주 주문하는 음료',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '안 먹거나 못 먹는 음식이 있나요?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                    ],
                ],
                [
                    'name' => 'TMI 대잔치',
                    'slug' => QuestionEnum::tmi()->label,
                    'depth' => 1,
                    'children' => [
                        [
                            'name' => '요새 떠올린 가장 쓸데없는 아이디어는?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '처음 봤을 때 제가 이렇더라도 이해해주세요',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '나의 근자감(근거 없는 자신감) 포인트가 있다면?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '내가 봐도 내가 좀 또라이 같을 때',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '쉽게 공감받지 못하는 나만의 취향 (ex. 데자와, 민초)',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '아무도 안 부러워하는 나만의 장기',
                            'slug' => null,
                            'depth' => 2,
                        ],
                    ],
                ],
                [
                    'name' => '가치관',
                    'slug' => QuestionEnum::value()->label,
                    'depth' => 1,
                    'children' => [
                        [
                            'name' => '인생에서 가장 중요하게 여기는 가치 세 가지',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '내가 정말 몰입해서 할 수 있는 일',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '인생에서 가장 큰 성취는 무엇이었나요?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '현재 기부하고 있는 곳이 있다면 어디인가요?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '텅장이 됐지만 하나도 아깝지 않았던 소비는?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '존경하는 인물을 소개해주세요',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '가장 좋아하는 명언과 그 이유는?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '인생에서 감수했던 가장 큰 리스크',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '절대 타협할 수 없는 게 있다면 (ex. 탕수육 부먹)',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '올해 꼭 이루고 싶은 목표',
                            'slug' => null,
                            'depth' => 2,
                        ],
                    ],
                ],
                [
                    'name' => '매력·능력',
                    'slug' => QuestionEnum::charm()->label,
                    'depth' => 1,
                    'children' => [
                        [
                            'name' => '내 성격의 매력 포인트',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '다룰 줄 아는 악기가 있다면? (꿀성대 포함)',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '다양한 언어구사 능력이 있나요? (외국어,사투리 포함)',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '주변 사람에게 들었던 칭찬 중에 가장 뿌듯했던 한 마디는?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '외모 중 자신 있는 곳이 있다면?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                    ],
                ],
                [
                    'name' => '소셜·데이팅',
                    'slug' => QuestionEnum::social()->label,
                    'depth' => 1,
                    'children' => [
                        [
                            'name' => '내가 생각하는 가장 편안한 첫 만남은?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '친구들이랑 만나서 주로 뭐하고 노세요?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '연애할 때 이것만은 지키고 싶다!',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '데이트는 일주일에 몇 번이 적당하다고 생각하세요?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '연예인 중 이상형을 꼽자면?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                        [
                            'name' => '연예인 중 라이벌이 있다면?',
                            'slug' => null,
                            'depth' => 2,
                        ],
                    ],
                ],
            ],
        ];

        Question::create($questions);
    }
}
