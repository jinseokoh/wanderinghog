<?php

namespace App\Http\Controllers\Categories;

use App\Enums\RelationEnum;
use App\Http\Controllers\Controller;

class RelationController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => [
                [
                    'id' => RelationEnum::college()->value,
                    'name' => __('relations.'.RelationEnum::college()->value),
                ],
                [
                    'id' => RelationEnum::school()->value,
                    'name' => __('relations.'.RelationEnum::school()->value),
                ],
                [
                    'id' => RelationEnum::academy()->value,
                    'name' => __('relations.'.RelationEnum::academy()->value),
                ],
                [
                    'id' => RelationEnum::club()->value,
                    'name' => __('relations.'.RelationEnum::club()->value),
                ],
                [
                    'id' => RelationEnum::work()->value,
                    'name' => __('relations.'.RelationEnum::work()->value),
                ],
                [
                    'id' => RelationEnum::neighbor()->value,
                    'name' => __('relations.'.RelationEnum::neighbor()->value),
                ],
                [
                    'id' => RelationEnum::religion()->value,
                    'name' => __('relations.'.RelationEnum::religion()->value),
                ],
                [
                    'id' => RelationEnum::other()->value,
                    'name' => __('relations.'.RelationEnum::other()->value),
                ],
            ],
        ]);
    }
}
