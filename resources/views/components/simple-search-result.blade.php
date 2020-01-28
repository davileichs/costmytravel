<div class="content-section-heading text-center">
  <h2 class="text-secondary mb-2">Costs</h2>
</div>

<div class="row">
    <div class="offset-0 col-12 offset-md-1 col-md-10 offset-lg-2 col-lg-8">
        <div class="content-section-body">
              <p>Cost for {{ $persons }} persons</p>
        </div>
        <ul class="list-group list-group-flush">

            <li class="list-group-item bg-primary">
                <div class="row">
                    <div class="col-6">Flight</div>
                    <div class="col-3 text-right">{{ $persons }} tickets</div>
                    <div class="col-3 text-right">€{{ $avgFlight }}</div>
                </div>
            </li>

            <li class="list-group-item bg-primary">
                <div class="row">
                    <div class="col-6">Hotel</div>
                    <div class="col-3 text-right">{{ $days }} days</div>
                    <div class="col-3 text-right">€{{ $avgHotel }}</div>
                </div>
            </li>

            <li class="list-group-item bg-primary">
                <div class="row">
                    <div class="col-6">Meal</div>
                    <div class="col-3 text-right">{{ $days }} days</div>
                    <div class="col-3 text-right">€{{ $avgMeal }}</div>
                </div>
            </li>

            <li class="list-group-item bg-primary">
                <div class="row">
                    <div class="col-6">Public Transport</div>
                    <div class="col-3 text-right">{{ $days }} days</div>
                    <div class="col-3 text-right">€{{ $avgTickets }}</div>
                </div>
            </li>

            <li class="list-group-item list-group-item-light">
                <div class="row">
                    <div class="col-9">Total</div>
                    <div class="col-3 text-right font-weight-bold">€{{ $avgFlight + $avgHotel + $avgMeal + $avgTickets}}</div>
                </div>
            </li>

        </ul>
    </div>
</div>
