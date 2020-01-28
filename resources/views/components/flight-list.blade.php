<div class="card bg-primary">
    <div class="card-header bg-dark text-white">
        Flights
    </div>
    <div class="list-flights">
      @foreach($routes as $i=>$route)
      <div class="card-body border-bottom mb-1 bg-white text-dark">
          <div class="row">
              <div class="col-12 col-sm-6">
                  <h5 class="card-title">{{ $route->flightFrom }} &bull; {{ $route->flightTo }} - €${{ $route->price }}</h5>
                  <p class="card-text font-weight-bold">Duration: {{ $route->duration }}</p>
                  <p><a data-toggle="collapse" href="#collapseFlightDetails{{ $i }}]" role="button" aria-expanded="false" aria-controls="collapseFlightDetails{{ $i }}">Details</a></p>
              </div>
              <div class="col-12 col-sm-6 text-right">
                  <a href="{{  $route->url }}" class="btn btn-kiwi" target="_blank">Check on Kiwi</a>
              </div>
              <div class="col-12 collapse" id="collapseFlightDetails{{ $i }}">
                @foreach($route->transfer as $transfer)
                    <p class="card-text">{{ $transfer->from }} &bull; {{ $transfer->to }}<br /><span class="small">Departure: {{ $transfer->departure }} - Arrival: {{ $transfer->arrival }}</span></p>
                @endforeach
              </div>
          </div>
      </div>
      @endforeach
    </div>
</div>
