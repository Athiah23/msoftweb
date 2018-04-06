@extends('layouts.main')
@section('css')
	 <link href="plugins/glDatePicker/styles/glDatePicker.default.css" rel="stylesheet" type="text/css">
@endsection
@section('title', 'Emergency Department')

@section('body')
<style type = "text/css">


/*width:400px; height:250px; position:absolute;top:30px;left:0px;z-index:1000;font-size: 28px;*/
</style>

	<div class='row'>
		<input id="Type" name="Type" type="hidden" value="{{Request::get('TYPE')}}">
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass" style="padding:0 0 0 15px">
					<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div>
				<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." style="padding-right:15px" >
				 
				    <button id="regBtn" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-inbox" aria-hidden="true"> </span> Register New</button>
				</div>
				
			 </fieldset> 
		</form>

		<div class="panel panel-default">
          <div class="panel-body">
            <div class='col-md-4'>
               <div id="mydate" gldp-id="mydate"></div>
                 <div gldp-el="mydate" style="width:400px; height:300px; position:static;top:30px;left:0px;z-index:1000;font-size: 28px;"></div>
                </div>
                <div class='col-md-8' style="padding:0 0 15px 0">
					<table id="jqGrid" class="table table-striped"></table>
						<div id="jqGridPager"></div>
				</div>
         </div>
		</div>
		<!--   <input type="text" id="mydate" gldp-id="mydate">
          <div gldp-el="mydate" style="width:400px; height:250px; position:absolute;top:30px;left:0px;z-index:1000;font-size: 28px;"></div>
                </div> -->
	</div>

	<div id="registerform" title="Register Form">
	<form class='form-horizontal' style='width:89%' id='registerformdata'>
			{{ csrf_field() }}
		<div class="form-group">
		<label for="title" class="col-md-2 control-label">MRN</label>
	        <div class="col-md-2">
			<div class="input-group">
			<input type="text" class="form-control input-sm" placeholder="MRN No" id="mrn" name="mrn" maxlength="12" readonly>
			<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
			</div>
			<span class='help-block'></span>
			</div>
              <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="patname" name="patname">
		</div>
		</div>	
        <div class="form-group">
          <label class="col-md-2 control-label" for="resourcecode">D.O.B</label>
					<div class="col-md-2">
							<input type="text" name="resourcecode" id="resourcecode" class="form-control input-sm" data-validation="required" >
					</div>
		  <label class="col-md-1 control-label" for="resourcecode">New I.C</label>
					<div class="col-md-2">
							<input type="text" name="resourcecode" id="resourcecode" class="form-control input-sm" data-validation="required" >
					</div>		
		   <label class="col-md-1 control-label" for="resourcecode">Old I.C</label>
					<div class="col-md-2">
							<input type="text" name="resourcecode" id="resourcecode" class="form-control input-sm" data-validation="required" >
					</div>				
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label" for="resourcecode">Sex</label>
					<div class="col-md-2">
							<input type="text" name="resourcecode" id="resourcecode" class="form-control input-sm" data-validation="required" >
					</div>
        </div>
        <div class="form-group">
		<label for="title" class="col-md-2 control-label">Race</label>
	        <div class="col-md-2">
			<div class="input-group">
			<input type="text" class="form-control input-sm" placeholder="Race" id="mrn" name="mrn" maxlength="12" readonly>
			<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
			</div>
			<span class='help-block'></span>
			</div>
              <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="patname" name="patname">
		</div>
		</div>
		 <div class="form-group">
		<label for="title" class="col-md-2 control-label">Financial Class</label>
	        <div class="col-md-2">
			<div class="input-group">
			<input type="text" class="form-control input-sm" id="mrn" name="mrn" maxlength="12" readonly>
			<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
			</div>
			<span class='help-block'></span>
			</div>
              <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="patname" name="patname">
		</div>
		</div>	
		 <div class="form-group">
		<label for="title" class="col-md-2 control-label">Payer</label>
	        <div class="col-md-2">
			<div class="input-group">
			<input type="text" class="form-control input-sm"  id="mrn" name="mrn" maxlength="12" readonly>
			<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
			</div>
			<span class='help-block'></span>
			</div>
              <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="patname" name="patname">
		</div>
		</div>	
		 <div class="form-group">
		<label for="title" class="col-md-2 control-label">Bill Type</label>
	        <div class="col-md-2">
			<div class="input-group">
			<input type="text" class="form-control input-sm" id="mrn" name="mrn" maxlength="12" readonly>
			<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
			</div>
			<span class='help-block'></span>
			</div>
              <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="patname" name="patname">
		</div>
		</div>	
		 <div class="form-group">
		<label for="title" class="col-md-2 control-label">Doctor</label>
	        <div class="col-md-2">
			<div class="input-group">
			<input type="text" class="form-control input-sm" id="mrn" name="mrn" maxlength="12" readonly>
			<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
			</div>
			<span class='help-block'></span>
			</div>
              <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="patname" name="patname">
		</div>
		</div>		
	</form>		
	</div>

 
@endsection


@section('scripts')

	<script src="js/hisdb/emergency/emergency.js"></script>
	<!-- <script type="text/javascript" src="plugins/glDatePicker/glDatePicker.min.js"></script> -->
	<script type="text/javascript" src="plugins/glDatePicker/glDatePicker.js"></script>
	<script type="text/javascript" src="plugins/glDatePicker/glDatePicker.min.js"></script>
@endsection