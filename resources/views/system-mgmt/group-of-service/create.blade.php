@extends('system-mgmt.group-of-service.base')

@section('action-content')
<div class="container">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add new Service Group</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('group-of-service.store') }}">
                        {{ csrf_field() }}






                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


				<div class="form-group">

                              <label class="col-md-4 control-label"></label>
                              <div class="col-md-6">
                    		<input type="checkbox"  name="main" value="Yes">Default Service Page<br>
                            </div>
                            </div>








                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
