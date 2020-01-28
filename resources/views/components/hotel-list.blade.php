<div class="card bg-primary">
    <div class="card-header bg-dark text-white">
        Hotel
    </div>
    <div class="list-flights">
      @foreach($hotels as $i=>$hotel)
      <div class="card-body border-bottom mb-1 bg-white text-dark">
          <div class="row">
              <div class="col-12 col-sm-6">
                  <h5 class="card-title">{{ $hotel->name }} - €{{ $hotel->price }} </h5>
                  <p class="card-text font-weight-bold">{{ $hotel->distance }} from centre</p>
                  <p><a data-toggle="collapse" href="#collapseHotelDetails{{ $i }}" role="button" aria-expanded="false" aria-controls="collapseHotelDetails{{ $i }}">Details</a></p>
              </div>
              <div class="col-12 col-sm-6 text-right">

              </div>
              @if(isset($hotel->description))
              <div class="col-12 collapse" id="collapseHotelDetails{{ $i }}">
                {{ $hotel->description }}
              </div>
              @endif
          </div>
      </div>
      @endforeach
    </div>
</div>
