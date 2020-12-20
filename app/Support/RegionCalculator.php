<?php

namespace App\Support;

class RegionCalculator
{
    /**
     * @param $point
     * @param $polygon
     * @return bool
     */
    public function include(float $latitude, float $longitude, array $polygon): bool
    {
        $point = [$latitude, $longitude];
        return ($this->pointInPolygon($point, $polygon) !== 'outside') ? true : false;
    }

    /**
     * reference) https://assemblysys.com/php-point-in-polygon-algorithm/
     *
     * @param array $point
     * @param array $polygon
     * @param bool $pointOnVertex
     * @return string
     */
    public function pointInPolygon(array $point, array $polygon, bool $pointOnVertex = true): string
    {
        // Check if the lat lng sits exactly on a vertex
        if ($pointOnVertex == true and $this->pointOnVertex($point, $polygon) == true) {
            return "vertex";
        }

        // Check if the lat lng is inside the polygon or on the boundary
        $intersections = 0;
        $polygon_count = count($polygon);

        for ($i=1; $i < $polygon_count; $i++) {
            $vertex1 = $polygon[$i-1];
            $vertex2 = $polygon[$i];
            if ($vertex1[0] == $vertex2[0] and
                $vertex1[0] == $point[0] and
                $point[1] > min($vertex1[1], $vertex2[1]) and
                $point[1] < max($vertex1[1], $vertex2[1])
            ) { // Check if point is on an horizontal polygon boundary
                return "boundary";
            }
            if ($point[0] > min($vertex1[0], $vertex2[0]) and
                $point[0] <= max($vertex1[0], $vertex2[0]) and
                $point[1] <= max($vertex1[1], $vertex2[1]) and
                $vertex1[0] != $vertex2[0]
            ) {
                $xinters = ($point[0] - $vertex1[0]) * ($vertex2[1] - $vertex1[1]) / ($vertex2[0] - $vertex1[0]) + $vertex1[1];
                if ($xinters == $point[1]) { // Check if lat lng is on the polygon boundary (other than horizontal)
                    return "boundary";
                }
                if ($vertex1[1] == $vertex2[1] || $point[1] <= $xinters) {
                    $intersections++;
                }
            }
        }

        // If the number of edges we passed through is odd, then it's in the polygon.
        if ($intersections % 2 != 0) {
            return "inside";
        } else {
            return "outside";
        }
    }

    private function pointOnVertex($point, $polygon): bool
    {
        foreach($polygon as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }

        return false;
    }

}
