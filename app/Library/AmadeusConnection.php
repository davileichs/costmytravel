<?php

namespace App\Library;

use Ixudra\Curl\Facades\Curl;


/**
 * Amadeus API Connection and extract data
 *
 * @author Davi Leichsenring
 */
class AmadeusConnection {

    /**
     * @var string Base API URL from Amadeus
     */
    protected $baseUrl = 'https://api.amadeus.com/';

    /**
     * @var string Token Endpoint
     */
    protected $endpointToken = 'v1/security/oauth2/token';

    /**
     * @var string Token Authorization
     */
    protected $token = '';

    /**
     * @var Array search attributes
     */
    protected $search = [];



    /**
     * Connect API and save Token Authorization
     *
     */
    public function __construct() {

          $endpoint = $this->baseUrl . $this->endpointToken;

          $response = Curl::to( $endpoint )
            ->withData( 'grant_type=client_credentials&client_id=' . env('AMADEUS_KEY') . '&client_secret=' . env('AMADEUS_SECRET') )
            ->withHeader( 'Content-Type: application/x-www-form-urlencoded' )
            ->post();

          $result = json_decode($response);

          $this->token = $result->access_token;

    }


    /**
     * Define standard search variables and set with new ones
     *
     * @param Array $search
     *
     * @return Array
     */
    protected function prepareSearch(array $search) {

          foreach($search as $k => $item) {
              $this->search[$k] = $item;
          }
    }


}
