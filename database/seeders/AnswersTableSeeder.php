<?php

namespace Database\Seeders;

use App\Models\Answer;
use Illuminate\Database\Seeder;

class AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $answers = [
            [
                'user_id' => 1,
                'question_id' => 3,
                'body' => '매일 아침 일찍 기상해 요가를 하는 것으로 하루를 시작하며 매일매일 플래너에 일정을 꽉꽉 채워 그것을 달성하는 재미로 하루를 살아간다.',
            ],
            [
                'user_id' => 2,
                'question_id' => 4,
                'body' => '절친인 도은의 카페에서 일하는 같은 대학 선배 신재현을 착한 사람이라 생각하며 반하게 되면서 이야기가 시작된다.',
            ],
            [
                'user_id' => 3,
                'question_id' => 5,
                'body' => '흑발 단발에 짜리몽땅한 스타일로, 대다수 웹툰의 주인공들이 소심하고 답답한 성격인 경우가 많은 것에 비해 직설적이고 당찬 성격이다.',
            ],
        ];

        foreach ($answers as $answer) {
            Answer::create($answer);
        }
    }
}
