<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Carbon\Carbon;

class HomeController extends Controller
{



      public function index() {

          return view('index');
      }




}
