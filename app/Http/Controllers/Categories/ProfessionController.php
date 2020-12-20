<?php

namespace App\Http\Controllers\Categories;

use App\Enums\ProfessionEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'data' => [
                [
                    'id' => ProfessionEnum::student()->value,
                    'name' => __('professions.'.ProfessionEnum::student()->value),
                ],
                [
                    'id' => ProfessionEnum::employee()->value,
                    'name' => __('professions.'.ProfessionEnum::employee()->value),
                ],
                [
                    'id' => ProfessionEnum::professional()->value,
                    'name' => __('professions.'.ProfessionEnum::professional()->value),
                ],
                [
                    'id' => ProfessionEnum::medical()->value,
                    'name' => __('professions.'.ProfessionEnum::medical()->value),
                ],
                [
                    'id' => ProfessionEnum::education()->value,
                    'name' => __('professions.'.ProfessionEnum::education()->value),
                ],
                [
                    'id' => ProfessionEnum::civil_officer()->value,
                    'name' => __('professions.'.ProfessionEnum::civil_officer()->value),
                ],
                [
                    'id' => ProfessionEnum::artist()->value,
                    'name' => __('professions.'.ProfessionEnum::artist()->value),
                ],
                [
                    'id' => ProfessionEnum::entrepreneur()->value,
                    'name' => __('professions.'.ProfessionEnum::entrepreneur()->value),
                ],
                [
                    'id' => ProfessionEnum::financial()->value,
                    'name' => __('professions.'.ProfessionEnum::financial()->value),
                ],
                [
                    'id' => ProfessionEnum::researcher()->value,
                    'name' => __('professions.'.ProfessionEnum::researcher()->value),
                ],
                [
                    'id' => ProfessionEnum::military()->value,
                    'name' => __('professions.'.ProfessionEnum::military()->value),
                ],
                [
                    'id' => ProfessionEnum::others()->value,
                    'name' => __('professions.'.ProfessionEnum::others()->value),
                ],
            ],
        ]);
    }
}
