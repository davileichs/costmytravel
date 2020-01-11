<?php

namespace App\Library;

use Ixudra\Curl\Facades\Curl;
use App\Library\AmadeusConnection;
use App\Helpers\HandlingHelper as Handling;


/**
 * Amadeus API Connection and extract data
 *
 * @author Davi Leichsenring
 */
class AmadeusHotels extends AmadeusConnection {


    /**
     * @var string Hotel Endpoint
     */
    private $endpoint = 'v2/shopping/hotel-offers';

    /**
     * @var array Hotels result
     */
    private $hotels = [];

    /**
     * @var array Hotel standard attributes
     */
    protected $search = [
       'currency'        => 'EUR',
       'cityCode'        => '',
       'checkInDate'     => '',
       'checkOutDate'    => '',
       'adults'          => 1,
       'radius'          => 5,
       'radiusUnit'      => 'KM',
       'paymentPolicy'   => 'NONE',
       'includeClosed'   => 'false',
       'bestRateOnly'    => 'true',
       'view'            => 'FULL',
       'sort'            => 'PRICE',
       'page'            => '10'
     ];



    /**
     * Get list of Hotels
     *
     * @return Json object
     */
    public function searchHotels(array $search) {

          $endpoint = $this->baseUrl . $this->endpoint;

          $this->prepareSearch($search);

          $response = Curl::to($endpoint)
          ->withData( $this->search )
          ->withHeader('Authorization: Bearer ' . $this->token )
          ->withHeader('Accept-Encoding: gzip')
          ->get();

          $result = Handling::unzipJson($response);

          $this->hotels = $result->data;

    }



    /**
     * Get Average price of Hotels
     *
     * @return Integer price
     */
    public function getAveragePrice(int $quantity = 10)  {

        $avg = 0;
        foreach ($this->hotels as $k => $hotel) {
            $total = ($hotel->offers[0]->price->total) ?? 0;
            $avg += $total;

            if($k == $quantity) break;
        }

        return $avg / ( ( count($this->hotels) < $quantity) ? count($this->hotels) : $quantity );

    }



}
