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
     * @var array Hotel standard search attributes
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
     * Get format data returned from Amadeus
     *
     *
     * @return Array
     */
    public function getHotels()  {


      $hotels = $this->hotels;

      $hotelList = array();

      foreach($hotels as $hotel) {

          $data = [
            'name'        => $hotel->hotel->name,
            'description' => $hotel->hotel->description->text ?? '',
            'rating'      => $hotel->hotel->rating,
            'distance'    => $hotel->hotel->hotelDistance->distance . ' ' . $hotel->hotel->hotelDistance->distanceUnit,
            'available'   => $hotel->available,
            'price'       => ( isset($hotel->offers[0]->price->total) ? $hotel->offers[0]->price->total : '--' ),
            'url'         => $hotel->self,
          ];

          array_push($hotelList, $data);
      }

      return json_decode(json_encode($hotelList), FALSE);


    }



    /**
     * Get Average price of Hotels
     *
     * @return Integer price
     */
    public function getAveragePrice(int $quantity = 10)  {

        $sum = 0;
        foreach ($this->hotels as $k => $hotel) {
            $total = ($hotel->offers[0]->price->total) ?? 0;
            $sum += $total;

            if($k == $quantity) break;
        }

        $avg = $sum / ( ( count($this->hotels) < $quantity) ? count($this->hotels) : $quantity );

        return round($avg);

    }



}
