@extends('layouts.main')

@section('style')
	.ui-dialog { z-index: 250 !important ;}

	fieldset.scheduler-border {
	    border: 1px groove #ddd !important;
	    padding: 2px;
	    -webkit-box-shadow:  0px 0px 0px 0px #000;
	    box-shadow:  0px 0px 0px 0px #000;
	}

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0;
        margin:0px;
        border-bottom:none;
    }
    .scheduler-border button{
    	margin:3px;
	}

	.alertmodal{
		margin-top: 150px;
	}

	.alertmodal .modal-body{
		background-color:red;
		color:white;
		border-radius:5px;
	}

@endsection

@section('css')
	<link rel="stylesheet" href="plugins/datatables/css/jquery.dataTables.css">
	<link href="plugins/glDatePicker/styles/glDatePicker.default.css" rel="stylesheet" type="text/css">
@endsection

@section('title', 'Emergency Department')

@section('body')

	<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
	<div class='row'>
		<input id="Type" name="Type" type="hidden" value="{{Request::get('TYPE')}}">
		<form id="searchForm" class="formclass" style='width:99%;position: relative;'>
			<fieldset>
				<div class="ScolClass" style="padding:0 0 0 15px">
					<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div>
				<div style="position: absolute;top: 5px;right: 5px">
					<fieldset class="scheduler-border">
					<legend class="scheduler-border">Legend</legend>
		  				
		  				<input type='color' id='CurrentPTcolor' name='CurrentPTcolor' class="form-control input-sm bg_color" value="{{$color->CurrentPTcolor}}" style="display: none;">

		  				<input type='color' id='CancelPTcolor' name='CancelPTcolor' class="form-control input-sm bg_color" value="{{$color->CancelPTcolor}}" style="display: none;">

		  				<input type='color' id='DiscPTcolor' name='DiscPTcolor' class="form-control input-sm bg_color" value="{{$color->DiscPTcolor}}" style="display: none;">

		  				<div class="btn-group btn-sm" role="group" style="padding:2px">
							<button type="button" class="btn" style="margin-right:0px ">Current</button>
							<button type="button" class="btn colorpointer" style="padding: 1px" data-color='CurrentPTcolor' id='btn_CurrentPTcolor'>
								<img src="img/paint.png" style="width:30px" alt="..." id="imgid">
							</button>
						</div>

						<div class="btn-group btn-sm" role="group" style="padding:2px">
							<button type="button" class="btn" style="margin-right:0px ">Cancel</button>
							<button type="button" class="btn btn-sm colorpointer" style="padding: 1px" data-color='CancelPTcolor' id='btn_CancelPTcolor'>
								<img src="img/paint.png" style="width:30px" alt="..." id="imgid">
							</button>
						</div>

						<div class="btn-group btn-sm" role="group" style="padding:2px">
							<button type="button" class="btn" style="margin-right:0px ">Discharge</button>
							<button type="button" class="btn btn-sm colorpointer" style="padding: 1px" data-color='DiscPTcolor' id='btn_DiscPTcolor'>
								<img src="img/paint.png" style="width:30px" alt="..." id="imgid">
							</button>
						</div>
					</fieldset>
				</div>
				<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." style="padding-right:15px;padding-top: 25px" >
				<button type="button" class="btn btn-default" id='biodata_but'>
					<span class='fa fa-user fa-lg'></span> Biodata
				</button>
				    <!-- <button id="biodata_but_emergency" type="button" class="btn btn-default" data-mrn='0'>
				    	<span class='fa fa-user fa-lg'></span> 
				    	<span id="span_biodata_label"> Biodata </span>
					</button> -->
				    <button id="regBtn" type="button" class="btn btn-success " >
				    	<span class="glyphicon glyphicon-inbox" aria-hidden="true"> </span> Register New
				    </button>
				</div>
				
			 </fieldset> 
		</form>

		<div class="panel panel-default">
			<div class="panel-body">
				<div class='col-md-4' id="colmd_outer">
				   	<div id="mydate" gldp-id="mydate"></div>
				    <div gldp-el="mydate" id="mydate_glpd" style="position:static;top:30px;left:0px;z-index:0;font-size: 28px;"></div>
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
	<form class='form-horizontal' style='width:99%' id='registerformdata'>
		<input id="code" name="code" type="hidden" value="{{Session::get('code')}}">
		{{ csrf_field() }}
		<div class="form-group">
		<label for="title" class="col-md-2 control-label">MRN</label>
	        <div class="col-md-2">
				<div class="input-group">
					<input type="text" class="form-control input-sm" placeholder="MRN No" id="mrn" name="mrn" maxlength="12" rdonly >
					<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
				</div>
				<span class='help-block'></span>
			</div>
             <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="patname" name="patname">
			</div>
		</div>	
        <div class="form-group">
        	<label class="col-md-2 control-label" for="idtype">ID Type</label>
			<div class="col-md-2">
					<select id='idtype' class="form-control input-sm">
						<option value="none" selected>None</option>
						<option value="Father">Father</option>
						<option value="Mother">Mother</option>
						<option value="Relative">Relative</option>
						<option value="Passport">Passport</option>
						<option value="Police">Police</option>
						<option value="Army">Army</option>
				    </select>
			</div>

		    <label class="col-md-1 control-label" for="Newic">New I.C</label>
			<div class="col-md-2">
				<input type="text" name="Newic" id="Newic" class="form-control input-sm"  maxlength="14">
			</div>	

		   	<label class="col-md-1 control-label" for="Oldic">Old I.C</label>
			<div class="col-md-2">
				<input type="text" name="Oldic" id="Oldic" class="form-control input-sm" maxlength="7">
			</div>	

        </div>
        <div class="form-group">
        	<label class="col-md-2 control-label" for="DOB">D.O.B</label>
			<div class="col-md-2">
				<input type="date" name="DOB" id="DOB" class="form-control input-sm">
			</div>

            <label class="col-md-1 control-label" for="idnumber">Others No</label>
			<div class="col-md-2">
				<input type="idnumber" name="idnumber" id="idnumber" class="form-control input-sm" >
			</div>

          	<label class="col-md-1 control-label" for="sex">Sex</label>
			<div class="col-md-2">
				<select id='sex' class="form-control input-sm" data-validation="required">
				 <option value="" selected>Please Choose</option>
	      		 <option value="M">Male</option>
		         <option value="F">Female</option>
		         <option value="U">Unisex</option>
			    </select>
			</div>
		</div>

        <div class="form-group">
			<label for="title" class="col-md-2 control-label">Race</label>
	        <div class="col-md-2">
				<div class="input-group">
				<input type="text" class="form-control input-sm" placeholder="Race" id="race" name="race" maxlength="12" rdonly>
				<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
				</div>
				<span class='help-block'></span>
			</div>

            <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="description_race" name="description_race">
			</div>
		</div>

		<hr>

		<div class="form-group">

			<label for="title" class="col-md-2 control-label">Financial Class</label>
	        <div class="col-md-2">
				<div class="input-group">
				<input type="text" class="form-control input-sm" placeholder="Finanncial Class" id="financeclass" name="financeclass" maxlength="12" rdonly>
				<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
				</div>
				<span class='help-block'></span>
			</div>

             <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="fName" name="fName">
			</div>

		</div>

		<div class="form-group">
			<label for="title" class="col-md-2 control-label">Payer</label>
		    <div class="col-md-2">
				<div class="input-group">
				<input type="text" class="form-control input-sm" placeholder="Payer" id="payer" name="payer" maxlength="12" rdonly>
				<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
				</div>
				<span class='help-block'></span>
			</div>
            <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="payername" name="payername">
				<input type="hidden" name="paytype" id="paytype">
			</div>
		</div>

		<div class="form-group">
			<label for="title" class="col-md-2 control-label">Bill Type</label>
	        <div class="col-md-2">
				<div class="input-group">
				<input type="text" class="form-control input-sm" placeholder="BillType" id="billtype" name="billtype" maxlength="12" rdonly>
				<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
				</div>
				<span class='help-block'></span>
			</div>
            <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="description" name="description_bt">
			</div>
		</div>

		<div class="form-group">
			<label for="title" class="col-md-2 control-label">Doctor</label>
	        <div class="col-md-2">
				<div class="input-group">
				<input type="text" class="form-control input-sm" placeholder="Doctor" id="doctor" name="doctor" maxlength="12" rdonly>
				<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
				</div>
				<span class='help-block'></span>
			</div>
            <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="docname" name="docname">
			</div>
		</div>

	</form>		
	</div>

	@include('hisdb.pat_mgmt.mdl_patient')
	@include('hisdb.pat_mgmt.itemselector')
 
@endsection

@section('scripts')

	<script type="text/javascript" src="plugins/datatables/js/jquery.datatables.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/jquery.validate.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/additional-methods.min.js"></script>

	<script type="text/javascript" src="js/myjs/modal-fix.js"></script>
	<script type="text/javascript" src="js/myjs/global.js"></script>
	<script src="js/hisdb/pat_mgmt/biodata.js"></script>

	<script src="js/hisdb/emergency/emergency.js"></script>
	<script type="text/javascript" src="plugins/glDatePicker/glDatePicker.js"></script>
	<script type="text/javascript" src="plugins/glDatePicker/glDatePicker.min.js"></script>

@endsection