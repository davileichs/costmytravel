<?php

namespace App\Library;

use Ixudra\Curl\Facades\Curl;
use App\Library\AmadeusConnection;


/**
 * Amadeus API Connection and extract data
 *
 * @author Davi Leichsenring
 */
class AmadeusLocation extends AmadeusConnection {


    /**
     * @var string Hotel Endpoint
     */
    protected $endpoint = 'v1/reference-data/locations';

    /**
     * @var array Hotels result
     */
    private $cities = [];

    /**
     * @var array Location standard attributes
     */
    protected $search = [
      'subType'     => 'CITY',
      'keyword'     => '',
      'page[limit]' => '5'
    ];


    /**
     * Get list of Location
     *
     * @return Json object
     */
    public function searchLocation(array $search) {


        $endpoint = $this->baseUrl . $this->endpoint;

        $this->prepareSearch($search);

        $response = Curl::to($endpoint)
        ->withData( $this->search )
        ->withHeader('Authorization: Bearer ' . $this->token )
        ->withHeader('Accept-Encoding: gzip')
        ->get();

        $result =  $this->unzipJson($response);

        $this->cities = $result->data;

    }

    /**
     * Get especific attributes from cities
     *
     * @return array object
     */
    public function cities() {

      $result = [];

      foreach($this->cities as $city) {

          array_push($result,  ucfirst(strtolower($city->address->cityName)) . ', ' . ucfirst(strtolower($city->address->countryName)) . ' (' . $city->iataCode . ')');

      }

      return $result;


    }




}