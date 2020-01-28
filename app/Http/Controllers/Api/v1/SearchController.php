<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Library\AmadeusLocation as Location;
use App\Library\KiwiConnection as Kiwi;
use App\Library\AmadeusHotels as Hotels;
use App\Library\NumbeoConnection as Numbeo;
use App\Helpers\HandlingHelper as Handling;

use Carbon\Carbon;

class SearchController
{


    protected $from;
    protected $to;
    protected $days;
    protected $startDate;
    protected $endDate;
    protected $persons;



    /**
     * Validation entries request
     *
     */
    protected function validation(Request $request) {

        $this->from = $request->from;
        $this->to = $request->to;
        $this->days = $request->days;
        $this->startDate = Carbon::create($request->date)->format('d/m/Y');;
        $this->endDate = Carbon::create($request->date)->addDays($request->days);
        $this->persons = $request->persons;


    }


    /**
     * Return simple search request
     *
     * @param Request $request
     *
     */
    public function simpleSearch(Request $request) {

        $request->validate([
            'from'      => 'required|max:255',
            'to'        => 'required|max:255',
            'persons'   => 'required|integer',
            'days'      => 'required|integer',
            'date'      => 'required',
        ]);

        $this->validation($request);

        $flight = $this->getFlightData();
        $hotel = $this->getHotelData();
        $costCity = $this->getCostData();

        $days = $request->days;
        $persons = $request->persons;

        $view = view("components.simple-search-result", [
            'persons'     => $persons,
            'days'        => $days,
            'avgFlight'   => $flight['avgFlight'],
            'avgHotel'    => $hotel['avgHotel'],
            'avgMeal'     => $costCity['priceMeal'],
            'avgTickets'  => $costCity['priceTicket'],
          ])
        ->render();
        return response()->json([
          'view'          => $view,
          'flight'        => $flight,
          'hotel'         => $hotel,
          'persons'       => $persons,
        ], 200);


    }



    /**
     * Get Data of flights from Kiwi
     *
     */
    protected function getFlightData() {

        $search = [
          'fly_from'    => Handling::getCityCode($this->from),
          'fly_to'      => Handling::getCityCode($this->to),
          'date_from'   => $this->startDate,
          'date_to'     => $this->endDate,
          'adults'      => $this->persons,
          'flight_type'  => 'return'
        ];

        $kiwiSearch = new Kiwi();
        $kiwiSearch->searchFlights( $search );
        $routes = $kiwiSearch->getRoutes();
        $avgFlightPrice = $kiwiSearch->getAveragePrice();

        $view = view("components.flight-list", compact('routes'))->render();
        return [ 'avgFlight' => $avgFlightPrice, 'view' => $view ];

    }


    /**
     * Get data of hotels from Amadeus
     *
     */
    protected function getHotelData() {

        $search = [
          'cityCode'        => Handling::getCityCode($this->to),
          'checkInDate'     => $this->startDate,
          'checkOutDate'    => $this->endDate,
          'adults'          => $this->persons
        ];


        $amadeusSearch = new Hotels();

        $amadeusSearch->searchHotels( $search );
        $hotels = $amadeusSearch->getHotels();
        $avgHotelPrice = $amadeusSearch->getAveragePrice() * $this->days;

        $view = view("components.hotel-list", compact('hotels'))->render();
        return [ 'avgHotel' => $avgHotelPrice, 'view' => $view ];


    }

    /**
     * Get data of costs of meals and tickets from Numeo
     *
     */
    protected function getCostData() {

        $numbeo = new Numbeo( Handling::getCityName($this->to) );

        $prices = $numbeo->getData();


        $totalPriceMeal = $prices['priceMeal'] * $this->persons * $this->days;
        $totalPriceTicket = $prices['priceTicket'] * $this->persons * 2 * $this->days;

        return [
          'priceMeal' => $totalPriceMeal,
          'priceTicket' => $totalPriceTicket,
        ];

    }



    /**
     * Get City data from Amadeus
     *
     */
    public function searchLocation(Request $request) {

        $amadeusSearch = new Location();
        $result = array();

        $amadeusSearch->searchLocation( ['keyword' => $request->q ] );

        $result = $amadeusSearch->cities();



        return response()->json($result, 200);

      }

}
