@extends('layouts.main')

@section('title', 'User Maintenance')

@section('body')
	
	@include('layouts.default_search_and_table')


	<!-------------------------------- table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%;' id='formdata'>
				{{ csrf_field() }}
				<input id="id" name="id" type="hidden">

				<div class="form-group">
				  <label class="col-md-2 control-label" for="username">Username</label>  
				  <div class="col-md-4">
				  	<input id="username" name="username" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit>
				  </div>
                </div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="name">Name</label>  
				  <div class="col-md-10">
				  	<input id="name" name="name" type="text" class="form-control input-sm" data-validation="required">
				  </div>
                </div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="password">Password</label>  
				  <div class="col-md-4">
				  <input id="password" name="password" type="text" class="form-control input-sm" data-validation="required">
				  </div>

				  <label class="col-md-2 control-label" for="groupid">Group</label>  
					<div class="col-md-4">
					  <div class='input-group'>
						<input id="groupid" name="groupid" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
                </div>

                <div class="form-group">


				  <label class="col-md-2 control-label" for="programmenu">Menu</label>  
				  <div class="col-md-4">
				  <input id="programmenu" name="programmenu" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  </div>

				  <label class="col-md-2 control-label" for="deptcode">Department</label>  
				  <div class="col-md-4">
				  <input id="url" name="deptcode" type="deptcode" class="form-control input-sm" data-validation="required">
				  </div>

                </div>
                <div class="form-inline">
                 <label class="control-label col-md-2">Cashier</label>
				  	<select class="form-control col-md-4" id='cashier' name='cashier'>
				  		<option value='1'>Yes</option>
				  		<option value='0'>No</option>
				  	</select>

				  <label class="control-label col-md-2">Price View</label>
				  	<select class="form-control col-md-4" id='priceview' name='priceview'>
				  		<option value='1'>Yes</option>
				  		<option value='0'>No</option>
				  	</select>
				 </div>
                
			</form>
		</div>

@endsection

@section('scripts')

	<script src="js/setup/user_maintenance/user_maintenance.js"></script>
	
@endsection