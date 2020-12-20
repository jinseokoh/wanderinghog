<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ImageMetaReader
{
    /**
     * @param array $exif
     * @return string|null
     */
    public function dateTaken(array $exif): ?string
    {
        $priorities = [
            'DateTaken',
            'Datetime',
            'DateTimeOriginal',
            'DateTimeDigitized'
        ];

        foreach ($priorities as $priority) {
            if (array_key_exists($priority, $exif)) {
                return $this->toDateString($exif[$priority]);
            }
        }

        return null;
    }

    /**
     * @param array $exif
     * @return string|null
     */
    public function latitude(array $exif): ?string
    {
        try {
            $latitude = $this->gps($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
        } catch (\Throwable $e) {
            return null;
        }

        return $latitude;
    }

    /**
     * @param array $exif
     * @return string|null
     */
    public function longitude(array $exif): ?string
    {
        try {
            $longitude = $this->gps($exif['GPSLongitude'], $exif['GPSLongitudeRef']);
        } catch (\Throwable $e) {
            return null;
        }

        return $longitude;
    }

    /**
     * @param string $mimeType
     * @param string|null $conversionName
     * @return string
     * @throws \Exception
     */
    public function randomizedName(string $mimeType): string
    {
        $extension = $this->extension($mimeType);

        return md5(Str::uuid()).'.'.$extension;
    }

    /**
     * @param string $mimeType
     * @return string
     * @throws \Exception
     */
    private function extension(string $mimeType): string
    {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/jpg':
                return 'jpg';
            case 'image/png':
            case 'image/x-png':
                return 'png';
            case 'image/gif':
                return 'gif';
            case 'image/webp':
                return 'webp';
        }

        throw new \Exception('Unsupported file.');
    }

    /**
     * ref) https://stackoverflow.com/questions/2526304/php-extract-gps-exif-data
     * @param $coordinate
     * @param $hemisphere
     * @return float|int
     */
    private function gps($coordinate, $hemisphere) {
        if (is_string($coordinate)) {
            $coordinate = array_map("trim", explode(",", $coordinate));
        }
        for ($i = 0; $i < 3; $i++) {
            $part = explode('/', $coordinate[$i]);
            if (count($part) == 1) {
                $coordinate[$i] = $part[0];
            } else if (count($part) == 2) {
                $coordinate[$i] = floatval($part[0])/floatval($part[1]);
            } else {
                $coordinate[$i] = 0;
            }
        }
        list($degrees, $minutes, $seconds) = $coordinate;
        $sign = ($hemisphere == 'W' || $hemisphere == 'S') ? -1 : 1;

        return $sign * ($degrees + $minutes/60 + $seconds/3600);
    }

    /**
     * @param string $date
     * @return string|null
     */
    private function toDateString(string $date): ?string
    {
        return Carbon::parse($date)->toDateString();
    }
}