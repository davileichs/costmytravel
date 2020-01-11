<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\KiwiConnection as Kiwi;
use App\Library\AmadeusHotels as Hotels;
use App\Library\AmadeusLocation as Location;
use App\Library\NumbeoConnection as Numbeo;
use App\Helpers\HandlingHelper as Handling;

use Carbon\Carbon;

class SearchController extends Controller
{


      protected $from;
      protected $to;
      protected $days;
      protected $startDate;
      protected $endDate;
      protected $persons;



      protected function validation(Request $request) {

          $this->from = $request->from;
          $this->to = $request->to;
          $this->days = $request->days;
          $this->startDate = Carbon::create($request->date)->format('d/m/Y');;
          $this->endDate = Carbon::create($request->date)->addDays($request->days);
          $this->persons = $request->persons;


      }


      public function index() {

          return view('index');
      }


      public function search(Request $request) {

            $this->validation($request);

            $avgFlight= $this->getKiwiData();
            $avgHotel = $this->getAmadeusData();
            $costCity = $this->getNumbeoData();

            $priceMeal = $costCity['priceMeal'];
            $priceTicket = $costCity['priceTicket'];

            $total = $avgFlight + $avgHotel + $priceMeal + $priceTicket;
            $days = $request->days;
            $persons = $request->persons;

            return view('index')
            ->with(compact('avgFlight'))
            ->with(compact('avgHotel'))
            ->with(compact('priceMeal'))
            ->with(compact('priceTicket'))
            ->with(compact('days'))
            ->with(compact('persons'))
            ->with(compact('total'));
      }



      protected function getKiwiData() {

          $search = [
            'fly_from'    => Handling::getCityCode($this->from),
            'fly_to'      => Handling::getCityCode($this->to),
            'date_from'   => $this->startDate,
            'date_to'     => $this->endDate,
            'adults'      => $this->persons
          ];

          $kiwiSearch = new Kiwi();
          $kiwiSearch->searchFlights( $search );

          $avgFlightPrice = $kiwiSearch->getAveragePrice();

          return $avgFlightPrice;

      }


      protected function getAmadeusData() {

          $search = [
            'cityCode'        => Handling::getCityCode($this->to),
            'checkInDate'     => $this->startDate,
            'checkOutDate'    => $this->endDate,
            'adults'          => $this->persons
          ];


          $amadeusSearch = new Hotels();

          $amadeusSearch->searchHotels( $search );

          $avgHotelPrice = $amadeusSearch->getAveragePrice() * $this->days;

          return $avgHotelPrice;

      }

      protected function getNumbeoData() {

          $numbeo = new Numbeo( Handling::getCityName($this->to) );

          $prices = $numbeo->getData();


          $totalPriceMeal = $prices['priceMeal'] * $this->persons * $this->days;
          $totalPriceTicket = $prices['priceTicket'] * $this->persons * 2 * $this->days;

          return [
            'priceMeal' => $totalPriceMeal,
            'priceTicket' => $totalPriceTicket,
          ];

      }



}
