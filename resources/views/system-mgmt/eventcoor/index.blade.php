@extends('system-mgmt.eventcoor.base')
@section('action-content')

    <!-- Main content -->
    <section class="content">

      <div class="box">
  <div class="box-header">
    <div class="row">


        <div class="col-sm-8">
          <h3 class="box-title">List of event</h3>
        </div>

    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
      <div class="row">

        <div class="col-sm-6"></div>
        <div class="col-sm-6"></div>
      </div>
      <form method="POST" action="{{ route('eventcoor.search') }}">
         {{ csrf_field() }}
        {{-- @component('layouts.search', ['title' => 'Search'])
          @component('layouts.two-cols-search-row', ['items' => ['structure_name','name'],
          'oldVals' => [isset($searchingVals) ? $searchingVals['structure_name'] : '',isset($searchingVals) ? $searchingVals['name'] : '']])
          @endcomponent--}}

		         @component('layouts.search', ['title' => 'Search'])
          @component('layouts.two-cols-search-row', ['items' => ['event_name'],
          'oldVals' => [isset($searchingVals) ? $searchingVals['event_name'] : '']])
          @endcomponent

        @endcomponent
      </form>
    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-12">



		<div style="overflow-x:auto;">

          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                <th width="" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="event: activate to sort column ascending">Event Name</th>
                <th width="" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="event: activate to sort column ascending">Organization</th>
                <th width="" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="event: activate to sort column ascending">Start Date</th>
                <th width="" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="event: activate to sort column ascending">End Date</th>
                <th width="" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="event: activate to sort column ascending">Location</th>
                <th width="">Action</th>
              </tr>
            </thead>
            <tbody>








            @foreach ($events as $event)
                <tr role="row" class="odd">

                  <td>{{ $event->event_name }}</td>
                  <td>{{ $event->organize_name }}</td>
                  <td>{{ $event->event_start_date }}</td>
                  <td>{{ $event->event_end_date }}</td>
                  <td>{{ $event->location }}</td>
                  <td>
                    <form class="row" method="POST" action="{{ route('eventcoor.destroy', ['id' => $event->id]) }}" onsubmit = "return confirm('Are you sure?')">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <a href="{{ URL::to('/eventcoor/eventregis',$event->id)}}" class="btn btn-info  btn-margin">
  Register Event
            </a>

                  <a href="{{ URL::to('/eventcoor/checkevent',$event->id)}}" class="btn btn-primary  btn-margin">
More Details
</a>
                {{--}}        <a href="{{ route('eventcoor.edit', ['id' => $event->id]) }}" class="btn btn-warning  btn-margin">
							Update
            </a>--}}
                        {{--}}<button type="submit" class="btn btn-danger  btn-margin">
                          Delete
                        </button>--}}
                    </form>

                  </td>
              </tr>
            @endforeach

            </tbody>
            <tfoot>
              <tr>
                <th width="" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="event: activate to sort column ascending">Event Name</th>
                <th width="" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="event: activate to sort column ascending">Organization</th>
                <th width="" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="event: activate to sort column ascending">Start Date</th>
                <th width="" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="event: activate to sort column ascending">End Date</th>
                <th width="" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="event: activate to sort column ascending">Location</th>
                <th width="">Action</th>
              </tr>
            </tfoot>
          </table>
            </div>
		</div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($events)}} of {{count($events)}} entries</div>

        <div class="col-sm-7">
          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
              {{ $events->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.box-body -->
</div>
<script>
function myFunction() {
  var copyText = document.getElementById("myInput");
  copyText.select();
  document.execCommand("copy");
  alert("Copied the text: " + copyText.value);
}

function myFunction2() {
  var copyText = document.getElementById("myInput2");
  copyText.select();
  document.execCommand("copy");
  alert("Copied the text: " + copyText.value);
}
</script>
    </section>



    <!-- /.content -->


@endsection
