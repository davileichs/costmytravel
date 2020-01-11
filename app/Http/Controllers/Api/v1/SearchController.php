<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Library\AmadeusLocation as Location;


use Carbon\Carbon;

class SearchController
{



    public function searchLocation(Request $request) {

        $amadeusSearch = new Location();
        $result = array();

        $amadeusSearch->searchLocation( ['keyword' => $request->q ] );

        $result = $amadeusSearch->cities();



        return response()->json($result, 200);

      }

}
