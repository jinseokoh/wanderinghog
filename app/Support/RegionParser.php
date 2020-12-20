<?php

namespace App\Support;

class RegionParser
{
    public function area(string $address): ?string
    {
        $patterns = [
            'seoul' => '대한민국 서울특별시',
            'gyeonggi' => '대한민국 경기도',
            'incheon' => '대한민국 인천광역시',
            'busan' => '대한민국 부산광역시',
            'ulsan' => '대한민국 울산광역시',
            'daejeon' => '대한민국 대전광역시',
            'daegu' => '대한민국 대구광역시',
            'gwangju' => '대한민국 광주광역시',
            'sejong' => '대한민국 세종.*?특별자치시',
            'jeju' => '대한민국 제주특별자치도',
            'gangwon' => '대한민국 강원도',
            'northern_jeolla' => '대한민국 전라북도',
            'southern_jeolla' => '대한민국 전라남도',
            'northern_gyeongsang' => '대한민국 경상북도',
            'southern_gyeongsang' => '대한민국 경상남도',
            'northern_chungcheong' => '대한민국 충청북도',
            'southern_chungcheong' => '대한민국 충청남도',
        ];

        foreach ($patterns as $key => $value) {
            if (preg_match("/{$value}/", $address)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @param string $address
     * @return string|null
     */
    public function seoul(string $address): ?string
    {
        $patterns = [
            'seoul_gangbuk' => '대한민국 서울특별시 (종로구|중구|용산구|서대문구|은평구|마포구|도봉구|강북구|성북구|동대문구|성동구|노원구|중랑구|광진구)',
            'seoul_gangnam' => '대한민국 서울특별시 (서초구|강남구|송파구|강동구|강서구|양천구|구로구|영등포구|동작구|관악구|금천구)',
        ];

        foreach ($patterns as $key => $value) {
            if (preg_match("/{$value}/", $address)) {
                return $key;
            }
        }

        return null;
    }

    public function gyeonggi(string $address): ?string
    {
        $patterns = [
            'seongnam' => '대한민국 경기도 성남시',
            'hanam' => '대한민국 경기도 하남시',
            'goyang' => '대한민국 경기도 고양시',
            'gimpo' => '대한민국 경기도 김포시',
            'bucheon' => '대한민국 경기도 부천시',
            'gwangmyeong' => '대한민국 경기도 광명시',
            'anyang' => '대한민국 경기도 안양시',
            'suwon' => '대한민국 경기도 수원시',
            'yongin' => '대한민국 경기도 용인시',
            'uijeongbu' => '대한민국 경기도 의정부시',
        ];

        foreach ($patterns as $key => $value) {
            if (preg_match("/{$value}/", $address)) {
                return $key;
            }
        }

        return null;
    }

}
