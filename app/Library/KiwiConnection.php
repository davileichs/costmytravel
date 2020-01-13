<?php

namespace App\Library;

use Ixudra\Curl\Facades\Curl;
use App\Helpers\HandlingHelper as Handling;


/**
 * Kiwi API Connection and extract data
 *
 * @author Davi Leichsenring
 */
class KiwiConnection {


    /**
     * @var string Kiwi Flight Endpoint
     */
    private $endpoint = 'https://api.skypicker.com/flights';
    /**
     * @var array Flights result
     */
    private $flights = [];

    /**
     * @var string Flight standard search attributes
     */
     protected $search = [
       'curr'        => 'EUR',
       'fly_from'    => '',
       'fly_to'      => '',
       'date_from'   => '',
       'date_to'     => '',
       'adults'     => '1',
       'partner'     => 'picky',
       'limit'       => '10',
       'sort'        => 'price',
       'asc'         => '1',

     ];

     /**
      * Get List of Flights
      *
      */
    public function searchFlights( array $search ) {

        $this->prepareSearch( $search );

        $response = Curl::to( $this->endpoint )
        ->withData( $this->search )
        ->withHeader('Accept-Encoding: gzip')
        ->get();

        $result = Handling::unzipJson($response);

        $this->flights = $result->data;

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


    /**
     * Get Average price of Flights
     *
     * @return Double price
     */
    public function getAveragePrice() {

        $avg = 0;
          foreach ($this->flights as $flight) {

              $avg += $flight->price;
          }

        return $avg / count($this->flights);

    }



}
