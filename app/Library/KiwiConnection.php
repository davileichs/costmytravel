<?php

namespace App\Library;

use Ixudra\Curl\Facades\Curl;
use App\Helpers\HandlingHelper as Handling;
use Carbon\Carbon;

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
     * Get format data returned from Kiwi
     *
     * @param Array $search
     *
     * @return Array
     */
    public function getRoutes() {

        $routes = $this->flights;

        $routeList = array();

        foreach($routes as $route) {

            $data = [
              'price'       => $route->price,
              'flightFrom'  => $route->cityFrom,
              'flightTo'  => $route->cityTo,
              'transfer'    => $this->getDataRoutes($route->route),
              'duration'    => $route->fly_duration,
              'url'         => $route->deep_link,
            ];

            array_push($routeList, $data);
        }

        return json_decode(json_encode($routeList), FALSE);
    }



    /**
     * Get Text format from routes
     *
     * @param Array $search
     *
     * @return Array
     */
     protected function getDataRoutes($route) {

        $dataRoutes = array();

        foreach($route as $direction) {
            $data = [
              'from'  => $direction->cityFrom,
              'to'  => $direction->cityTo,
              'departure'  => Carbon::createFromTimestamp($direction->dTime)->format('H:i'),
              'arrival'  => Carbon::createFromTimestamp($direction->aTime)->format('H:i'),
            ];

            array_push($dataRoutes, $data);
        }

        return $dataRoutes;

     }


    /**
     * Get raw data returned from Kiwi
     *
     * @param Array $search
     *
     * @return Array
     */
    public function getData() {

        return $this->flights;
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
