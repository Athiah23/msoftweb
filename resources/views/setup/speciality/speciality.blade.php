@extends('layouts.main')

@section('title', 'Speciality Setup')

@section('body')

	@include('layouts.default_search_and_table')
		
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
			{{ csrf_field() }}
			<input type="hidden" name="idno">
			
				<div class="form-group">
                	<label class="col-md-2 control-label" for="specialitycode">Speciality Code</label>  
                      <div class="col-md-3">
                      <input id="specialitycode" name="specialitycode" type="text" maxlength="10" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
				</div>
                
                <div class="form-group">
                	<label class="col-md-2 control-label" for="description">Description</label>  
                      <div class="col-md-8">
                      <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
                      </div>
				</div>

				<div class="form-group">
                	<label class="col-md-2 control-label" for="disciplinecode">Discipline Code</label>  
                      <div class="col-md-8">
                      <input id="disciplinecode" name="disciplinecode" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
                      </div>
				</div>

				<div class="form-group">
                	<label class="col-md-2 control-label" for="type">Category Type</label>  
                      <div class="col-md-8">
                      <input id="type" name="type" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
                      </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-3">
					<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				  </div>
				</div>
                
				<div class="form-group">
					<label class="col-md-2 control-label" for="adduser">Created By</label>  
						<div class="col-md-3">
						  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
						  	<div class="col-md-3">
								<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="adddate">Created Date</label>  
						<div class="col-md-3">
						  	<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>  
						  	<div class="col-md-3">
								<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>  

				<div class="form-group">
					<label class="col-md-2 control-label" for="lastcomputerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit rdonly>
						</div>

						<label class="col-md-2 control-label" for="lastipaddress">IP Address</label>  
						  	<div class="col-md-3">
								<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit rdonly>
						  	</div>
				</div>    
            </form>
		</div>

@endsection


@section('scripts')

	<script src="js/setup/speciality/specialityScript.js"></script>
	
@endsection