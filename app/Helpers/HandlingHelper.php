<?php

namespace App\Helpers;



class HandlingHelper {

    /**
     * Unzip and decode Json from API Response
     *
     * @param Json $search
     *
     * @return Json Json result
     */
    public static function unzipJson($response) {

          $decompressed = gzdecode($response);

          return json_decode($decompressed);

    }



    /**
     * Get code city iataCode
     *
     * @param Array $search
     *
     * @return Array
     */
    public static function getCityCode(string $text) {

        preg_match('/\((?<code>\w+)\)/', $text, $match);

        if (isset($match['code'])) {
          return $match['code'];
        }

    }


    /**
     * Clean city name to Numbeo Format
     *
     * @return array prices
     */
    public static function getCityName(string $text) {

        $aux = explode(',', $text);
        $cityName = ucwords($aux[0]); // city name

        $cityName = \transliterator_transliterate( 'Any-Latin; Latin-ASCII;', $cityName );
        $cityName = str_replace(' ', '-', $cityName);
        return $cityName;

    }

}
