@extends('layouts.main')

@section('title', 'Invoice AP')

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

.collapsed ~ .panel-body {
 padding: 0;
}

.clearfix {
   overflow: auto;
}

@endsection

@section('body')

	<!-- @include('layouts.default_search_and_table') -->
	<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
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
							
						</div>
		            </div>
				</div>

				<div class="col-md-2" style="padding: 10px;">
					&nbsp;
	            </div>

				<!-- <div class="col-md-2">
				  	<label class="control-label" for="Status">Status</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
					      <option value="All" selected>ALL</option>
					      <option value="Open">OPEN</option>
					      <option value="Confirmed">CONFIRMED</option>
					      <option value="Posted">POSTED</option>
					      <option value="Cancelled">CANCELLED</option>
					    </select>
	            </div> -->

				<div id="div_for_but_post" class="col-md-3 col-md-offset-7" style="text-align: end;">
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq" data-oper="posted" style="display: none;">POST</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
				</div>

			</fieldset> 
		</form>
		 
		<div class="panel panel-default">
		    <div class="panel-heading">Invoice AP Data Entry Header</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            			<div id="jqGridPager"></div>
        			</div>
		    	</div>
		</div>

		<div class='click_row'>
        	<label class="control-label">Audit No</label>
        		<span id="auditnodepan" style="display: block;">&nbsp</span>
        </div>
        <div class='click_row'>
			<label class="control-label">Trantype</label>
        	<span id="trantypedepan" style="display: block;">&nbsp</span>
        </div>
        <div class='click_row'>
			<label class="control-label">Document No</label>
        	<span id="docnodepan" style="display: block;">&nbsp</span>
        </div>

	    <div class="panel panel-default" id="jqGrid3_c">
		<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid3_panel">
				<i class="fa fa-angle-double-up" style="font-size:24px"></i>
    			<i class="fa fa-angle-double-down" style="font-size:24px"></i>Invoice AP Data Entry Detail</div>
				<div id="jqGrid3_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<div class='col-md-12' style="padding:0 0 15px 0">
							<table id="jqGrid3" class="table table-striped"></table>
							<div id="jqGridPager3"></div>
						</div>'
					</div>
				</div>	
		</div>
        
    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Invoice AP Header
				<a class='pull-right pointer text-primary' id='pdfgen1'><span class='fa fa-print'></span> Print </a>
			</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="apacthdr_source" name="apacthdr_source" type="hidden" value="AP">
					<input id="apacthdr_trantype" name="apacthdr_trantype" value = "IN" type="hidden">
					<input id="auditno" name="auditno" type="hidden">
					<input id="idno" name="idno" type="hidden">

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_ttype">Doc Type</label> 
							<div class="col-md-3">
							  	<select id="apacthdr_ttype" name=apacthdr_ttype class="form-control" data-validation="required">
							       <option value="Supplier">Supplier</option>
							       <option value="Others">Others</option>
							       <option value="Debit_Note">Debit Note</option>
							    </select>
						  	</div>

				  		<label class="col-md-2 control-label" for="apacthdr_auditno">Audit No</label>  
				  			<div class="col-md-3"> <!--- value="<?php// echo "auditno";?>" -->
				  				<input id="apacthdr_auditno" name="apacthdr_auditno" type="text" class="form-control input-sm" rdonly>
				  		</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_suppcode">Creditor</label>	 
						 	<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_suppcode" name="apacthdr_suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

				  		<label class="col-md-2 control-label" for="apacthdr_recdate">Post Date</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_recdate" name="apacthdr_recdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>">
				  			</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_payto">Pay To</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_payto" name="apacthdr_payto" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

				  		<label class="col-md-2 control-label" for="apacthdr_actdate">Doc Date</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_actdate" name="apacthdr_actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
				  			</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_document">Document No</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_document" name="apacthdr_document" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  			</div>

				  		<label class="col-md-2 control-label" for="apacthdr_category">Category</label>	  
				  			<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_category" name="apacthdr_category" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_deptcode">Department</label>
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_deptcode" name="apacthdr_deptcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						 	 </div>
					</div>

					<div class="form-group">
			    		<label class="col-md-2 control-label" for="apacthdr_remarks">Remarks</label> 
			    			<div class="col-md-8"> 
			    				<textarea class="form-control input-sm text-uppercase" name="apacthdr_remarks" rows="2" cols="55" maxlength="400" id="apacthdr_remarks" ></textarea>
			    			</div>
			   		</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_amount">Invoice Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_amount" name="apacthdr_amount" maxlength="12" class="form-control input-sm"> 
		 					</div>

						<label class="col-md-2 control-label" for="apacthdr_outamount">Total Detail Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_outamount" name="apacthdr_outamount" maxlength="12" class="form-control input-sm" rdonly> 
		 					</div>
					</div>

					<button type="button" id='save' class='btn btn-info btn-sm pull-right' style='margin: 0.2%;'>Save</button>

				</form>
				<div class="panel-body">
				<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
			</div>
			</div>
		</div>
			

	<div class='panel panel-info' id="ap_parent">
		<div class="panel-heading">Invoice AP  Detail</div>
			<div class="panel-body">
				<form id='formdata2' class='form-vertical' style='width:99%'>
					<div id="jqGrid2_c" class='col-md-12'>
						<table id="jqGrid2" class="table table-striped"></table>
					        <div id="jqGridPager2"></div>
					</div>
				</form>
			</div>

			<div class="panel-body">
				<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
			</div>
		</div>
	</div>			
			
	</div>
@endsection


@section('scripts')

	<script src="js/finance/AP/invoiceAP/invoiceAP.js"></script>
	<script src="js/finance/AP/invoiceAP/pdfgen.js"></script>
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection