@extends('layouts.main')

@section('title', 'Charge Master Setup')

@section('style')

.panel-heading.collapsed .fa-angle-double-up,
.panel-heading .fa-angle-double-down {
	display: none;
}

.panel-heading.collapsed .fa-angle-double-down,
.panel-heading .fa-angle-double-up {
	display: inline-block;
}

i.fa {
	cursor: pointer;
	float: right;
	<!--  margin-right: 5px; -->
}

.clearfix {
	overflow: auto;
}

fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
	font-size: 1.2em !important;
	font-weight: bold !important;
	text-align: left !important;
	width:auto;
	padding:0 10px;
	border-bottom:none;
}

input.uppercase {
	text-transform: uppercase;
}

@endsection

@section('body')
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

	<!--***************************** Search + table ******************-->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative'>
			<fieldset>
				<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
		              	</div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
							<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">

							<div  id="show_chggroup" style="display:none">
								<div class='input-group'>
									<input id="chggroup" name="chggroup" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

							<div  id="show_chgtype" style="display:none">
								<div class='input-group'>
									<input id="chgtype" name="chgtype" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>

					  	<div class="col-md-1" id="div_chggroup" style="padding-left: 30px;padding-right: 30px;display:none">
							<label class="control-label"></label>
							<a class='form-control btn btn-primary' id="btn_chggroup"><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
					  	<div class="col-md-1" id="div_chgtype" style="padding-left: 30px;padding-right: 30px;display:none;margin: 0px">
							<label class="control-label"></label>
							<a class='form-control btn btn-primary' id="btn_chgtype"><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
		            </div>
				</div>
			</fieldset> 
		</form>

        <div class="panel panel-default">
		    <div class="panel-heading">Charge Master Header</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>

	    <!-- <div class="panel panel-default">
		    <div class="panel-heading">Charge Price</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
	            	<table id="jqGrid3" class="table table-striped"></table>
	            	<div id="jqGridPager3"></div>
	    		</div>
		    </div>
		</div>  -->

		<div class="panel-group">
  			<div class="panel panel-default" id="jqGrid3_c">
    			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid3_panel">
					<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i>Charge Price
    			</div>
    			<div id="jqGrid3_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<form id='formdata3' class='form-vertical' style='width:99%'>
							<div class='col-md-12' style="padding:0 0 15px 0">
								<table id="jqGrid3" class="table table-striped"></table>
								<div id="jqGridPager3"></div>
							</div>
						</form>
					</div>
    			</div>
  			</div>
		</div>

		<div class="panel-group">
  			<div class="panel panel-default" id="jqGridPkg3_c">
    			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGridPkg3_panel">
					<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i>Charge Price
    			</div>
    			<div id="jqGridPkg3_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<form id='formdataPkg3' class='form-vertical' style='width:99%'>
							<div class='col-md-12' style="padding:0 0 15px 0">
								<table id="jqGridPkg3" class="table table-striped"></table>
								<div id="jqGridPagerPkg3"></div>
							</div>
						</form>
					</div>
    			</div>
  			</div>
		</div>

		<div class="panel-group">
  			<div class="panel panel-default" id="jqGrid4_c">
    			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid4_panel">
					<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i>Package Deal Maintenance Details
    			</div>
    			<div id="jqGrid4_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<form id='formdata4' class='form-vertical' style='width:99%'>
							<div class='col-md-12' style="padding:0 0 15px 0">
								<table id="jqGrid4" class="table table-striped"></table>
								<div id="jqGridPager4"></div>
							</div>
						</form>
						<label>Grand total 1 &nbsp;</label><input type="input" name="grandtot1" id="grandtot1" disabled="disabled">&nbsp;
						<label>Grand total 2 &nbsp;</label><input type="input" name="grandtot2" id="grandtot2" disabled="disabled">&nbsp;
						<label>Grand total 3 &nbsp;</label><input type="input" name="grandtot3" id="grandtot3" disabled="disabled">&nbsp;
					</div>
    			</div>
  			</div>
		</div>

    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form">
		<div class='panel panel-info'>
			<div class="panel-heading">Charge Master</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="cm_idno" name="cm_idno" type="hidden">
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_chgcode">Charge Code</label>  
						<div class="col-md-3">
							<input id="cm_chgcode" name="cm_chgcode" type="text" class="form-control input-sm uppercase" data-validation="required" frozeOnEdit>
						</div>

						<label class="col-md-2 control-label" for="cm_description">Description</label>  
						<div class="col-md-3">
							<input id="cm_description" name="cm_description" type="text" class="form-control input-sm uppercase" data-validation="required">
						</div>
					</div>   

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_barcode">Bar Code</label>  
						<div class="col-md-3">
							<input id="cm_barcode" name="cm_barcode" type="text" class="form-control input-sm uppercase">
						</div>

						<label class="col-md-2 control-label" for="cm_brandname">Generic</label>  
						<div class="col-md-3">
							<input id="cm_brandname" name="cm_brandname" type="text" class="form-control input-sm uppercase">
						</div>
					</div>

					<hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_chgclass">Class Code</label>
						<div class="col-md-3">
							<div class='input-group'>
								<input id="cm_chgclass" name="cm_chgclass" type="text" maxlength="12" class="form-control input-sm uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="control-label col-md-2" for="cm_constype">Consultation Type</label>  
						<div class="col-md-3">
							<select class="form-control col-md-4" id='cm_constype' name='cm_constype' data-validation="">
								<option value='A'>Anaestetics</option>
								<option value='C'>Consultation</option>
								<option value='S'>Surgeon</option>
								<option value='' selected>None</option>
							</select> 
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_chggroup">Charge Group</label>  
						<div class="col-md-3">
							<div class='input-group'>
								<input id="cm_chggroup" name="cm_chggroup" type="text" maxlength="12" class="form-control input-sm uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_chgtype">Charge Type</label>
						<div class="col-md-3">
							<div class='input-group'>
								<input id="cm_chgtype" name="cm_chgtype" type="text" maxlength="12" class="form-control input-sm uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-2 control-label" for="cm_recstatus">Record Status</label>  
						<div class="col-md-3">
							<label class="radio-inline"><input type="radio" name="cm_recstatus" value='A' checked>Active</label>
							<label class="radio-inline"><input type="radio" name="cm_recstatus" value='D' >Deactive</label>
						</div>
					</div>

					<hr>

					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Inventory</legend> 
						<div class="form-group">
							<label class="col-md-2 control-label" for="cm_uom">UOM</label>  
							<div class="col-md-3">
								<input id="cm_uom" name="cm_uom" type="text" class="form-control input-sm uppercase" rdonly>
							</div>

							<label class="col-md-2 control-label" for="cm_invflag">Inventory Item</label>
							<div class="col-md-3">
								<label class="radio-inline"><input type="radio" name="cm_invflag" value='1' checked dsabled>Yes</label>
								<label class="radio-inline"><input type="radio" name="cm_invflag" value='0' dsabled>No</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="cm_packqty">Packing</label>  
							<div class="col-md-3">
								<input id="cm_packqty" name="cm_packqty" type="text" class="form-control input-sm uppercase" rdonly>
							</div>

							<label class="col-md-2 control-label" for="cm_druggrcode">Drug Group Code</label>  
							<div class="col-md-3">
								<input id="cm_druggrcode" name="cm_druggrcode" type="text" class="form-control input-sm uppercase" rdonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="cm_subgroup">Sub Group</label>  
							<div class="col-md-3">
								<input id="cm_subgroup" name="cm_subgroup" type="text" class="form-control input-sm uppercase" rdonly>
							</div>

							<label class="col-md-2 control-label" for="cm_stockcode">Stock Code</label>  
							<div class="col-md-3">
								<input id="cm_stockcode" name="cm_stockcode" type="text" class="form-control input-sm uppercase" rdonly>
							</div>
						</div>
					</fieldset>

					<hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_invgroup">Inv. Group</label>
						<div class="col-md-3">
							<select class="form-control col-md-4" id='cm_invgroup' name='cm_invgroup'>
								<option value='CC'>Charge Code</option>
								<option value='CG'>Charge Group</option>
								<option value='CT'>Charge Type</option>
								<option value='DC'>Doctor</option>
							</select> 
						</div>

						<label class="col-md-2 control-label" for="cm_costcode">Doctor Code</label>  
						<div class="col-md-3">
							<div class='input-group'>
								<input id="cm_costcode" name="cm_costcode" type="text" maxlength="12" class="form-control input-sm uppercase">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_revcode">Revenue Dept. Code</label>  
						<div class="col-md-3">
							<div class='input-group'>
								<input id="cm_revcode" name="cm_revcode" type="text" maxlength="12" class="form-control input-sm uppercase">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-2 control-label" for="cm_seqno">Sequence No</label>  
						<div class="col-md-3">
							<input id="cm_seqno" name="cm_seqno" type="text" class="form-control input-sm uppercase">
						</div>
					</div>

					<hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_overwrite">Price Overwrite</label>  
						<div class="col-md-3">
							<label class="radio-inline"><input type="radio" name="cm_overwrite" value='1' checked>Yes</label>
							<label class="radio-inline"><input type="radio" name="cm_overwrite" value='0' >No</label>
						</div>

						<label class="col-md-2 control-label" for="cm_doctorstat">Doctor</label>  
						<div class="col-md-3">
							<label class="radio-inline"><input type="radio" name="cm_doctorstat" value='1' checked>Yes</label>
							<label class="radio-inline"><input type="radio" name="cm_doctorstat" value='0' >No</label>
						</div>
					</div>

					<hr>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_upduser">Last User</label>  
						<div class="col-md-3">
							<input id="cm_upduser" name="cm_upduser" type="text" class="form-control input-sm uppercase" rdonly>
						</div>

						<label class="col-md-2 control-label" for="cm_upddate">Last Update</label>  
						<div class="col-md-3">
							<input id="cm_upddate" name="cm_upddate" type="text" maxlength="30" class="form-control input-sm uppercase" rdonly>
						</div>
					</div> 
					

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_lastcomputerid">Computer Id</label>  
						<div class="col-md-3">
							<input id="cm_lastcomputerid" name="cm_lastcomputerid" type="text" class="form-control input-sm uppercase" data-validation="required" rdonly>
						</div>

						<label class="col-md-2 control-label" for="cm_lastipaddress">IP Address</label>  
						<div class="col-md-3">
							<input id="cm_lastipaddress" name="cm_lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" rdonly>
						</div>
					</div> 
				</form>
			</div>
		</div>
			
		<div class='panel panel-info'>
			<div class="panel-heading">Charge Price Detail</div>
			<div class="panel-body">
				<form id='formdata2' class='form-vertical' style='width:99%'>
					<div id="jqGrid2_c" class='col-md-12'>
						<table id="jqGrid2" class="table table-striped"></table>
						<div id="jqGridPager2"></div>
					</div>

					<div id="jqGridPkg2_c" class='col-md-12'>
						<table id="jqGridPkg2" class="table table-striped"></table>
						<div id="jqGridPagerPkg2"></div>
					</div>
				</form>
			</div>

			<div class="panel-body">
				<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
			</div>
		</div>

		<!-- <div class='panel panel-info'>
			<div class="panel-heading">Charge Price Detail</div>
			<div class="panel-body">
				<form id='formdataPkg2' class='form-vertical' style='width:99%'>
					<div id="jqGridPkg2_c" class='col-md-12'>
						<table id="jqGridPkg2" class="table table-striped"></table>
						<div id="jqGridPagerPkg2"></div>
					</div>
				</form>
			</div>

			<div class="panel-body">
				<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
			</div>
		</div>	 -->
	</div>

@endsection


@section('scripts')

	<script src="js/setup/chargemaster/chargemaster.js"></script>
	
@endsection



		