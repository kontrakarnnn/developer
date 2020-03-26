@extends('system-mgmt.policy.base')
@section('action-content')
    <!-- Main content -->
    <section class="content">
      <div class="box">
  <div class="box-header">
    <div class="row">
        <div class="col-sm-8">
          <h3 class="box-title">List of Policy</h3>
        </div>
        <div class="col-sm-4">
          <a class="btn btn-primary" href="{{ route('policy.create') }}">Add Policy</a>
        </div>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
      <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6"></div>
      </div>




      <form method="POST" action="{{ route('policy.search') }}">
         {{ csrf_field() }}
         @component('layouts.search', ['title' => 'Search'])
          @component('layouts.two-cols-search-row', ['items' => [ 'name'],
          'oldVals' => [isset($searchingVals) ? $searchingVals['name'] : '']])
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
				  <th width="20%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="port: activate to sort column ascending">Name</th>
                <th tabindex="0" aria-controls="example2" rowspan="1" colspan="2" aria-label="Action: activate to sort column ascending">Action</th>
              </tr>
            </thead>
            <tbody>
              {{$policy}}
            @foreach ($policy as $po)
                <tr role="row" class="odd">
					<td>{{ $po->name}}</td>
                  <td>
                    <form class="row" method="POST" action="{{ route('policy.destroy', ['id' => $po->id]) }}" onsubmit = "return confirm('Are you sure?')">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="button" class="btn btn-default btn-margin" data-toggle="modal" data-target="#myModal{{ $po->id }}">More Details</button>

                        <a href="{{ route('policy.edit', ['id' => $po->id]) }}" class="btn btn-warning  btn-margin">
                        Update
                        </a>
                        <button type="submit" class="btn btn-danger btn-margin">
                          Delete
                        </button>
                    </form>
                    <div class="modal fade" id="myModal{{ $po->id }}" role="dialog">
                    <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-content">
                    <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal" style="color:white;">&times;</button>
                    <h4 class="modal-title">{{$po->name}} </h4>
                    </div>
                    <div class="modal-body">
                      {!! html_entity_decode($po->policy)!!}
                    </div>
                    <div class="modal-footer" >
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                    </div>
                  </div>
                </div>
                  </td>
              </tr>
            @endforeach
            </tbody>
            <tfoot>
              <tr>
				  <th width="20%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="port: activate to sort column ascending">Name</th>
                <th rowspan="1" colspan="2">Action</th>
              </tr>
            </tfoot>
          </table>
			</div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($policy)}} of {{count($policy)}} entries</div>
        </div>
        <div class="col-sm-7">
          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            {{ $policy->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.box-body -->
</div>
    </section>
    <!-- /.content -->
  </div>
@endsection
