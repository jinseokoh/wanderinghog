<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class StatController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $now = Carbon::now()->toDateString();

        $query = <<<EOF
SELECT
    age,
    COUNT(*) as count
FROM appointments
    WHERE
		expired_at >= '?' AND
		is_active = true
    GROUP BY age
EOF;

        $rows = \DB::select(\DB::raw(str_replace('?', $now, $query)));
        $rc = collect($rows);
        $stats = $rc->mapWithKeys(function ($item) {
            return [$item->age => $item->count];
        })->toArray();

        if ($rc->count() > 0) {
            $minAge = $rc->first()->age;
            $maxAge = $rc->last()->age;
            $maxCount = $rc->max('count');
            $items = array_fill($minAge, $maxAge - $minAge + 1, 0);
            for ($i = $minAge; $i <= $maxAge; $i++) {
                if (array_key_exists($i, $stats)) {
                    $items[$i] = (int) (($stats[$i] / $maxCount) * 100);
                }
            }

            return response()->json([
                'data' => [
                    'max_count' => $maxCount,
                    'min_age' => $minAge,
                    'max_age' => $maxAge,
                    'values' => array_values($items)
                ]
            ], 200);
        }

        return response()->json([
            'error' => 'items not found.'
        ], 422);
    }
}
