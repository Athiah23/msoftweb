@extends('layouts.main')

@section('title', 'Item Enquiry')
@section('style')
	.num{
		width:20px;
	}
	.mybtn{
		float: right;
		display: none;
	}
	.bg-primary .mybtn{
		display:block;
	}
	.dataTables_scroll table{
		background-color: rgba(221, 221, 221, 0.3);
	}
	#TableDetailMovement td:first-child,.dataTables_scrollHead th:first-child{
		border-top-color:white !important;
		border-top-style:solid !important;
		border-top-width:1px !important;
		border-left-color:white;
		border-left-style:solid;
		border-left-width:1px;
		border-bottom-color:white;
		border-bottom-style:solid;
		border-bottom-width:1px;
		cursor:pointer;
		background-color: white;
	}
	#TableDetailMovement tr:hover td:first-child{
		border-style:solid;
		border-width:1px;
		border-color:#ddd;
		background-color:#ddd;
	}
	#detailMovement{
		margin: 0.2%;position: absolute;right: 50px; bottom:5px;
	}
	
@endsection

@section('body')
	
	<div class='row'>

		<input id="Class2" name="Class" type="hidden" value="{{ $_GET['Class'] }}">
		<input id="getYear" name="getYear" type="hidden"  value="{{ Carbon\Carbon::now()->year }}">
		<!-- <input id="deptcode" name="deptcode" type="hidden" value="{{Request::get('deptcode')}}">
		<input id="itemcode" name="itemcode" type="hidden" value="{{Request::get('itemcode')}}"> -->
		

		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass"  style="padding:0 0 0 15px">
					<div name='Scol' style='font-weight:bold'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">

				</div>

			 </fieldset> 

		</form>

		<div class="panel panel-default">
    		<div class="panel-body">
    			<div class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid" class="table table-striped"></table>
    					<div id="jqGridPager"></div>
				</div>
    		</div>
		</div>

 		<div class="panel panel-default">
		    <div class="panel-body">
		    	<input id="itemcode" name="itemcode" type="hidden">
		    	<div class='col-md-8'>
            		<table id="detail" class="table table-striped"></table>
            			<div id="jqGridPager2"></div>
        		</div>

        		<div class='col-md-4'>
            		<table id="itemExpiry" class="table table-striped"></table>
        			<div id="jqGridPager3">
        			</div>
            		<button type="button" id='detailMovement' class='btn btn-info btn-sm'>Detail Movement</button>	
        		</div>

        	</div>

		</div>
    </div>

<!---*********************************** VIEW DETAIL MOVEMENT ************************************************** -->

	<div id="detailMovementDialog" title="View Detail Movement" >
		<div id='detailMovement_c' class=''>
			<div class="panel panel-default">
		    	<div class="panel-heading">Detail Movement by Item</div>
		    		<div class="panel-body">

		    			<div class='col-md-12' style="padding:0 0 15px 0">
        					<div class="form-group">
            					
            					<label class="col-md-1 control-label" for="itemcodedtl">Item Code:</label>  
						  			<div class="col-md-2">
										<input id="itemcodedtl" name="itemcodedtl" class="form-control input-sm"  value="$itemcode" readonly="true">
						  			</div>
						  		<label class="col-md-1 control-label" for="deptcodedtl">Dept Code:</label>  
						  			<div class="col-md-2">
										<input id="deptcodedtl" name="deptcodedtl" class="form-control input-sm" value = "" readonly="true">
						  			</div>
						  		<label class="col-md-1 control-label" for="uomcodedtl">UOM Code:</label>  
						  			<div class="col-md-2">
										<input id="uomcodedtl" name="uomcodedtl" class="form-control input-sm" value = "" readonly="true">
						  			</div>		
					  		</div>

						</div>	 


							<div class='col-md-12' style="padding:0 0 15px 0">
	        					<div class="form-group">
	        						
		            					<label class="col-md-1 control-label" for="monthfrom">Month From :</label>  
		            					<div class="col-md-2">
										  	<select id="monthfrom" name="monthfrom" class="form-control input-sm">
										      <option value="monthfrom" selected>-- MONTH FROM --</option>
										      <option value="1">January</option>
										      <option value="2">February</option>
										      <option value="3">March</option>
										      <option value="4">April</option>
										      <option value="5">May</option>
										      <option value="6">June</option>
										      <option value="7">July</option>
										      <option value="8">August</option>
										      <option value="9">September</option>
										      <option value="10">October</option>
										      <option value="11">November</option>
										      <option value="12">December</option>
										    </select>
										</div>    
									

							  		<label class="col-md-1 control-label" for="yearfrom">Year From :</label>  
		            					<div class="col-md-2">
			  									<select id='yearfrom' name='yearfrom' class="form-control input-sm"></select>
										</div>    

							  		<label class="col-md-1 control-label" for="monthto">Month To :</label>  
		            					<div class="col-md-2">
										  	<select id="monthto" name="monthto" class="form-control input-sm">
										      <option value="monthto" selected>-- MONTH TO --</option>
										      <option value="1">January</option>
										      <option value="2">February</option>
										      <option value="3">March</option>
										      <option value="4">April</option>
										      <option value="5">May</option>
										      <option value="6">June</option>
										      <option value="7">July</option>
										      <option value="8">August</option>
										      <option value="9">September</option>
										      <option value="10">October</option>
										      <option value="11">November</option>
										      <option value="12">December</option>
										    </select>
										</div>    
									

							  		<label class="col-md-1 control-label" for="yearto">Year To :</label>  
		            					<div class="col-md-2">
										  	<select id='yearto' name='yearto' class="form-control input-sm"></select>
										</div>    			
						  		</div> 
						  		<br>
						  		<br>
						  			<div class="col-md-12">
										<button type="button" id="search" class="pull-right btn btn-primary" >Search</button>
			              			</div>
						  			<hr/>

						  			<hr/>
						  	</div>	

						  	<div class='col-md-12' style="padding:0 0 15px 0">
		        				<div class="form-group">
		            				<label class="col-md-1 control-label" for="openbalqty">Opening Balance Quantity:</label>  
								  		<div class="col-md-2">
											<input id="openbalqty" name="openbalqty" type="text" maxlength="11" class="form-control input-sm" value = "" readonly="true">
								  		</div>
								  	<label class="col-md-1 control-label" for="openbalval">Opening Balance Value:</label>  
								  		<div class="col-md-2">
											<input id="openbalval" name="openbalval" type="text" maxlength="11" class="form-control input-sm" value = "" readonly="true">
								  		</div>
								</div>
							</div> 
        				</div>
		    		</div>
		</div>

			<table class="table table-hover  table-bordered" id='TableDetailMovement'>
				<thead>
					<tr>
						<th> </th>
						<th>Transaction Date</th>
						<th>Trantype</th>
						<th>Transaction Description</th>
						<th>Qty In</th>
						<th>Qty Out</th>
						<th>Balance Quantity</th>
						<th>Unit Cost</th>
						<th>Trans Amount</th>
						<th>Balance Amount</th>
						<th>Document No</th>
						<th>User Id</th>
						<th>Trans Time</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>

@endsection

@section('scripts')

	<script src="js/material/itemInquiry/itemInquiry.js"></script>
	<script src="plugins/datatables/js/jquery.datatables.min.js"></script>

@endsection