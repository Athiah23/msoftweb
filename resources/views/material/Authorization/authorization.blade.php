@extends('layouts.main')

@section('title', 'Debtor Type')

@section('body')

	@include('layouts.default_search_and_table')
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				{{ csrf_field() }}
				<input type="hidden" name="idno">

				<div class="form-group">
				  <label class="col-md-2 control-label" for="authorid">Author ID</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="authorid" name="authorid" type="text" maxlength="15" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <!--<span class="help-block"></span>-->
				  </div>
                </div>
                
                <div class="form-group">                  
                  <label class="col-md-2 control-label" for="name">Name</label>  
				  <div class="col-md-5">
				  <input id="name" name="name" type="text" maxlength="100" class="form-control input-sm" rdonly>
				  </div>
                </div>
                
                <div class="form-group">
				   <label class="col-md-2 control-label" for="password">Password</label>  
				  <div class="col-md-3">
				  <input id="password" name="password" type="text" maxlength="15" class="form-control input-sm" rdonly>
				  </div>
				</div>

				<div class="form-group">
				   <label class="col-md-2 control-label" for="deptcode">Department Code</label>  
				  <div class="col-md-4">
				  <input id="deptcode" name="deptcode" type="text" maxlength="15" class="form-control input-sm" rdonly>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-2">
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
					<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
							<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>    

				<div class="form-group">
					<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						<div class="col-md-3">
						  	<input id="ipaddress" name="ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="lastipaddress">Last IP Address</label>  
						<div class="col-md-3">
							<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>              
                
			</form>
		</div>
	@endsection

@section('scripts')

	<script src="js/material/Authorization/authorization.js"></script>

@endsection