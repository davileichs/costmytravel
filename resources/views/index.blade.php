@extends('layout.master')

@section('content')

<!-- Header -->
<header class="masthead d-flex">
  <div class="container text-center my-auto">
    <h1 class="mb-1">Cost my Travel</h1>

    <form method="post" action="{{ route('search') }}" class="mt-5 d-flex row rounded bg-dark p-3 text-light">
        @csrf
          <div class="form-group text-left input-group-lg col-12 col-md-6">
              <label for="from">From:</label>
              <input type="text" class="form-control" id="from" aria-describedby="emailHelp" value="" name="from">
          </div>
          <div class="form-group text-left input-group-lg col-12 col-md-6">
              <label for="to">To:</label>
              <input type="text" class="form-control" id="to" aria-describedby="emailHelp" value="" name="to">
          </div>
          <div class="form-group text-left input-group-lg col-12 col-md-5">
              <label for="duration">When:</label>
              <input type="date" class="form-control" id="date" aria-describedby="emailHelp" name="date" value="">
          </div>
          <div class="form-group text-left input-group-lg col-6 col-md-2">
              <label for="days">Days</label>
              <input type="number" class="form-control" id="days" aria-describedby="emailHelp" name="days" value="">
          </div>
          <div class="form-group text-left input-group-lg col-6 col-md-2">
              <label for="persons">Persons</label>
              <select class="form-control" id="persons" name="persons">
                  @for($i=1;$i<7;$i++)
                  <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
              </select>
          </div>
          <div class="form-group input-group-lg col-md-3">
              <label>&nbsp;</label>
              <button type="submit" class="form-control btn btn-primary btn-md">Go</button>
          </div>
    </form>
  </div>
</header>


<!-- Services -->
<section class="pt-5 pb-5 bg-primary text-white" id="services">
  <div class="container">
    <div class="content-section-heading text-center">
      <h2 class="text-secondary mb-2">Costs</h2>
    </div>

    <div class="row">
        <div class="offset-0 col-12 offset-md-1 col-md-10 offset-lg-2 col-lg-8">
            <div class="content-section-body">
                  <p>Cost for {{ $persons }} persons</p>
            </div>
            <ul class="list-group list-group-flush">
                @if(isset($avgFlight))
                <li class="list-group-item bg-primary">
                    <div class="row">
                        <div class="col-6">Flight</div>
                        <div class="col-3 text-right">{{ $persons }} tickets</div>
                        <div class="col-3 text-right">${{ $avgFlight }}</div>
                    </div>
                </li>
                @endif
                @if(isset($avgHotel))
                <li class="list-group-item bg-primary">
                    <div class="row">
                        <div class="col-6">Hotel</div>
                        <div class="col-3 text-right">{{ $days }} days</div>
                        <div class="col-3 text-right">${{ $avgHotel }}</div>
                    </div>
                </li>
                @endif
                @if(isset($priceMeal))
                <li class="list-group-item bg-primary">
                    <div class="row">
                        <div class="col-6">Meal</div>
                        <div class="col-3 text-right">{{ $days }} days</div>
                        <div class="col-3 text-right">${{ $priceMeal }}</div>
                    </div>
                </li>
                @endif
                @if(isset($priceTicket))
                <li class="list-group-item bg-primary">
                    <div class="row">
                        <div class="col-6">Public Transport</div>
                        <div class="col-3 text-right">{{ $days }} days</div>
                        <div class="col-3 text-right">${{ $priceTicket }}</div>
                    </div>
                </li>
                @endif
                @if(isset($total))
                <li class="list-group-item list-group-item-light">
                    <div class="row">
                        <div class="col-9">Total</div>
                        <div class="col-3 text-right font-weight-bold">$ {{ $total }}</div>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>

    </div>
  </div>
</section>


@endsection

@push('scripts')
<script>

$(document).ready(function(){


  $('#to, #from').autoComplete({
    resolverSettings: {
        url: "{{ env('APP_URL') }}/api/v1/cities/",
        dataType: 'json',

    }
});


});
</script>
@endpush
