@extends('layouts.main')

@section('title', 'Language Setup')

@section('body')

	@include('layouts.default_search_and_table')
		
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
			{{ csrf_field() }}
			<input type="hidden" name="idno">
			

				<div class="form-group">
				  <label class="col-md-3 control-label" for="Code">Language Code</label>  
                      <div class="col-md-4">
                      <input id="Code" name="Code" type="text" maxlength="10" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
				</div>
                
                <div class="form-group">
                	<label class="col-md-3 control-label" for="Description">Description</label>  
                      <div class="col-md-8">
                      <input id="Description" name="Description" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
                      </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-3 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D'>Deactive</label>
				  </div>
				</div> 

				<div class="form-group">
					<label class="col-md-3 control-label" for="adduser">Created By</label>  
						<div class="col-md-3">
						  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-3 control-label" for="upduser">Last Entered</label>  
						<div class="col-md-3">
							<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						</div>
				</div> 

				<div class="form-group">
					<label class="col-md-3 control-label" for="lastcomputerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" rdonly >
						</div>

					<label class="col-md-3 control-label" for="lastipaddress">IP Address</label>  
					  	<div class="col-md-3">
							<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" rdonly >
					  	</div>
				</div>   
				
			</form>
		</div>

@endsection


@section('scripts')

	<script src="js/setup/language/language.js"></script>
	
@endsection