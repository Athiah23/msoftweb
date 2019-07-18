@extends('layouts.main')

@section('title', 'Fa Control 2')

@section('body')

	@include('layouts.default_search_and_table')

	<div id="dialogForm" title="Add Form" >
		<form class='form-horizontal' style='width:99%' id='formdata'>
			{{ csrf_field() }}
            <input type="hidden" name="idno">
            
            <!-- <div class="form-group">
            	<label class="col-md-2 control-label" for="compcode">Compcode</label>  
                <div class="col-md-3">
                    <input id="compcode" name="compcode" type="text" maxlength="4" class="form-control input-sm">
                </div>
            </div>
            
            <div class="form-group">
            	<label class="col-md-2 control-label" for="name">Company Name</label>  
                <div class="col-md-4">
                    <input id="name" name="name" type="text" maxlength="20" class="form-control input-sm">
                </div>
			</div> -->

			<div class="form-group">
	        	<label class="col-md-2 control-label" for="year">Year</label>  
	              <div class="col-md-3">
	              <select class="form-control"  id="year" name="year">
	              	@foreach ($yearperiod as $year)
	              		<option value="{{$year->year}}">{{$year->year}}</option>
	              	@endforeach
	              </select>
	              </div>
			</div>
	        
	        <div class="form-group">
	        	<label class="col-md-2 control-label" for="period">Period</label>  
	              <div class="col-md-3">
	              <select class="form-control" id="period" name="period">
		              <option value="1">1</option>
		              <option value="2">2</option>
		              <option value="3">3</option>
		              <option value="4">4</option>
		              <option value="5">5</option>
		              <option value="6">6</option>
		              <option value="7">7</option>
		              <option value="8">8</option>
		              <option value="9">9</option>
		              <option value="10">10</option>
		              <option value="11">11</option>
		              <option value="12">12</option>
	              </select>
	              </div>
			</div>

		</form>
	</div>
		
@endsection


@section('scripts')

	<script src="js/finance/FA/facontrol2/facontrol2.js"></script>

	
@endsection