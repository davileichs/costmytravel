@extends('layout.master')

@section('content')

<!-- Header -->
<header class="masthead d-flex">
  <div class="container text-center my-auto">
    <h1 class="mb-1">Cost my Travel</h1>

    <form id="simpleSearch" class="mt-5 d-flex row rounded bg-dark p-3 text-light">
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
          <div class="invalid-feedback">
              Invalid parameters
          </div>
    </form>
  </div>
</header>


<!-- Result -->
<section class="pt-5 pb-5 bg-primary text-white d-none" id="costs">
  <div class=" text-center loading-icon">
      <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
      </div>
  </div>
  <div class="error"></div>

  <div class="container d-none">

    <div id="show-simple-search"> </div>

    <div  id="show-flights" class="pt-5 pb-5"></div>

  </div>



</section>


@endsection

@push('scripts')
<script>

$(document).ready(function(){

   $(document).on('submit', '#simpleSearch', function(e){
     e.preventDefault();


     $('#costs').removeClass('d-none');
     $('html, body').animate({
         scrollTop: $('#costs').offset().top
     }, 'slow');

     $.ajax({
         url: "{{ env('APP_URL') }}api/v1/search",
         type: 'POST',
         data: $(this).serialize(),
         dataType: 'json'
       }).done(function(data) {

           $('#show-simple-search').append(data.view);

           $('#show-flights').append(data.flight.view);

           $('#costs .container').removeClass('d-none');
           $('.loading-icon').hide();

            $('.persons').text(data.persons);
            $('.days').text(data.days);

            $('.avgHotel').text(data.avgHotel);
            $('.priceTicket').text(data.priceTicket);
            $('.priceMeal').text(data.priceMeal);
            $('.total').text(data.total);

         }).fail(function(error){

            $('.error').text(error.responseJSON.error);
            $('.loading-icon').hide();
            $('.error').show();
         });

   });

  $('#to, #from').autoComplete({
    resolverSettings: {
        url: "{{ env('APP_URL') }}api/v1/cities/",
        dataType: 'json',
    }
  });

});

function drawFlight(flight) {



}
</script>
@endpush
