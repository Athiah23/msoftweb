@extends('layouts.main')

@section('title', 'Doctor Maintenance Setup')

@section('body')
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass" style="padding:0 0 0 15px">
					<div name='Scol'>Search By : </div>
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

<<<<<<< HEAD
		<a class='pull-right pointer text-primary' id='PHBtn'>
	     	<span class='fa fa-calendar fa-lg '></span> Public Holiday 
	    </a>
	    <a class='pull-right pointer text-primary' id='ALBtn'>
	    	<span class='fa fa-calendar fa-lg '></span> Leave &nbsp;&nbsp;
	    </a>
	    <a class='pull-right pointer text-primary' id='TSBtn'>
	    	<span class='fa fa-clock-o fa-lg '></span> Time Session&nbsp;&nbsp; 
	    </a>
	</div>
=======
   

	@include('layouts.default_search_and_table')
	 <a class='pull-right pointer text-primary' id='PHBtn'><span class='fa fa-calendar fa-lg '></span> Public Holiday </a>
    <a class='pull-right pointer text-primary' id='ALBtn'><span class='fa fa-calendar fa-lg '></span> Leave &nbsp;&nbsp;</a>
    <a class='pull-right pointer text-primary' id='TSBtn'><span class='fa fa-clock-o fa-lg '></span> Time Session&nbsp;&nbsp; </a>
	<br><br><br>
>>>>>>> ca86fb1193c3e0e609c2ea06b681f887d9cd6211
    
	 <div id="TSBox" title="Time Session" style="display:none">
    <ul>
			<b>DOCTOR CODE : </b><span name='resourcecode' ></span> <br><br>
			<b>DOCTOR NAME: </b><span name='description' ></span>
			
		</ul>	

		<div id='gridtime_c' style="padding:15px 0 0 0">
            <table id="gridtime" class="table table-striped"></table>
            <div id="gridtimepager"></div>
        </div>
	</div>

	<div id="PHBox" title="Public Holiday" style="display:none">	
      <ul>
			<b>DOCTOR CODE : </b><span name='resourcecode' ></span> <br><br>
			<b>DOCTOR NAME: </b><span name='description' ></span>
		</ul>
        
			<fieldset>
				
					
					<div class="col-md-2">
			  		<label class="control-label" for="YEAR">Year:</label> 
						<select id='YEAR' class="form-control input-sm">
				      		<option value="All" selected>ALL</option>
						</select>
				</div>
				
				
			 </fieldset> 
		
		<div id='gridph_c' style="padding:15px 0 0 0">
            <table id="gridph" class="table table-striped"></table>
            <div id="gridphpager"></div>
        </div>
</div>
	

	<div id="ALBox" title="Annual Leave" style="display:none">
    <ul>
			<b>DOCTOR CODE : </b><span name='resourcecode' ></span> <br><br>
			<b>DOCTOR NAME: </b><span name='description' ></span>
			
		</ul>	

		<div id='gridleave_c' style="padding:15px 0 0 0">
            <table id="gridleave" class="table table-striped"></table>
            <div id="gridleavepager"></div>
        </div>
	</div>

    <div id="tsdialogForm" title="Transfer Form">
			
			<form class='form-horizontal' style='width:89%' >
			<div class="prevnext btn-group pull-right"></div>
			
			<!-- <div class="form-group">
				<label class="col-md-2 control-label" for="resourcecode">Resource Code</label>
				<div class="col-md-2">
					<input id="resourcecode" name="resourcecode" type="text" class="form-control input-sm" frozeOnEdit>
				</div>
				<label class="col-md-3 control-label" for="description">Description</label>
				<div class="col-md-4">
					<input type="text" name="description" id="description" class="form-control input-sm" frozeOnEdit>
				</div>
			</div>
 -->
			</form>

			<!-- <hr> -->
			<form class='form-horizontal' style='width:89%' id='tsformdata'>
			<input id="resourcecode" name="resourcecode" type="hidden">
			<input id="idno" name="idno"  type="hidden">
			{{ csrf_field() }}
				<div class="form-group">
				<div class="form-group">
				
			</div>

					<!-- <label class="col-md-2 control-label" for="days">Day</label>
					<div class="col-md-2">
						<input type="text" name="days" id="days" class="form-control input-sm"  frozeOnEdit data-validation="required">
					</div> -->
					<!-- <div class="col-md-2"> -->
					<label class="col-md-2 control-label" for="days">Day</label>  
					<div class="col-md-2">
					  	<select id="days" name="days" class="form-control input-sm">
					      <option value="MONDAY">MONDAY</option>
					      <option value="TUESDAY">TUESDAY</option>
					      <option value="WEDNESDAY">WEDNESDAY</option>
					      <option value="THURSDAY">THURSDAY</option>
					      <option value="FRIDAY">FRIDAY</option>
					      <option value="SATURDAY">SATURDAY</option>
					      <option value="SUNDAY">SUNDAY</option>
					    </select>
					    </div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="timefr1">Start Time</label>
					<div class="col-md-2">
						<div class='input-group'>
							<input type="time" name="timefr1" id="timefr1" class="form-control input-sm" data-validation="required">
							
						</div>
						
					</div>
					<label class="col-md-2 control-label" for="timeto1">End Time</label>
					<div class="col-md-3">
						<div class='input-group'>
							<input type="time" name="timeto1" id="timeto1" class="form-control input-sm" data-validation="required">
							
						</div>
						
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label" for="timefr2">Start Time</label>
					<div class="col-md-2">
						<div class='input-group'>
							<input type="time" name="timefr2" id="timefr2" class="form-control input-sm" data-validation="required">
							
						</div>
						
					</div>
					<label class="col-md-2 control-label" for="timeto2">End Time</label>
					<div class="col-md-3">
						<div class='input-group'>
							<input type="time" name="timeto2" id="timeto2" class="form-control input-sm" data-validation="required">
							
						</div>
						
					</div>
				</div>
	
			</form>
		
	</div>

            
<div id="phdialogForm" title="Transfer Form">
			
			<!-- <form class='form-horizontal' style='width:89%' >
			<div class="prevnext btn-group pull-right"></div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="resourcecode">Resource Code</label>
				<div class="col-md-2">
					<input id="resourcecode" name="resourcecode" type="text" class="form-control input-sm" frozeOnEdit>
				</div>
				<label class="col-md-3 control-label" for="description">Description</label>
				<div class="col-md-4">
					<input type="text" name="description" id="description" class="form-control input-sm" frozeOnEdit>
				</div>
			</div>

			</form> -->

		<!-- 	<hr> -->
			<form class='form-horizontal' style='width:89%' id='phformdata'>
			<input id="resourcecode" name="resourcecode" type="hidden">
			<input id="idno" name="idno" type="hidden">
			<input id="YEAR" name="YEAR" type="hidden"  value="<?php echo date("Y") ?>">
				<div class="form-group">
				<!-- <div class="form-group">
				
			</div> -->
	         

				<div class="form-group">
					<label class="col-md-2 control-label" for="datefr">From</label>
					<div class="col-md-2">
						<div class='input-group'>
							<input type="date" name="datefr" id="datefr" class="form-control input-sm" data-validation="required" value="<?php echo date("d-m-Y"); ?>">
							
						</div>
						
					</div>
					<label class="col-md-2 control-label" for="dateto">To</label>
					<div class="col-md-2">
						<input type="date" name="dateto" id="dateto" class="form-control input-sm"   data-validation="required" value="<?php echo date("d-m-Y"); ?>" >
					</div>
				</div>
				</div>

				<div class="form-group">
				<!-- <div class="form-group">
				
			</div> -->
	

				<div class="form-group">
					<label class="col-md-2 control-label" for="remark">Remark</label>   
						  			<div class="col-md-5">
						  				<textarea rows="5" id='_remark' name='remark' class="form-control input-sm" ></textarea>
						  			</div>
				</div>
				</div>
                	
                   <!--  <label class="col-md-2 control-label" for="remark">Remark</label>
					<div class="col-md-2">
						<div class='input-group'>
							<textarea rows="5" name="remark" id="remark" class="form-control input-sm" data-validation="required"></textarea>
							
							
						</div>
						
					</div> -->
			</form>
		</div>
          
          <div id="aldialogForm" title="Transfer Form">
			
			<!-- <form class='form-horizontal' style='width:89%' >
			<div class="prevnext btn-group pull-right"></div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="resourcecode">Resource Code</label>
				<div class="col-md-2">
					<input id="resourcecode" name="resourcecode" type="text" class="form-control input-sm" frozeOnEdit>
				</div>
				<label class="col-md-3 control-label" for="description">Description</label>
				<div class="col-md-4">
					<input type="text" name="description" id="description" class="form-control input-sm" frozeOnEdit>
				</div>
			</div>

			</form> -->

			<!-- <hr> -->
			<form class='form-horizontal' style='width:89%' id='alformdata'>
			<input id="resourcecode" name="resourcecode" type="hidden">
			<input id="idno" name="idno"type="hidden">
			<input id="YEAR" name="YEAR" type="hidden"  value="<?php echo date("Y") ?>">
				<div class="form-group">
				<div class="form-group">
				
			</div>
                   <div class="form-group">
					<label class="col-md-2 control-label" for="datefr">From</label>
					<div class="col-md-2">
						<div class='input-group'>
							<input type="date" name="datefr" id="datefr" class="form-control input-sm" data-validation="required" value="<?php echo date("d-m-Y"); ?>">
							
						</div>
						
					</div>
					<label class="col-md-2 control-label" for="dateto">To</label>
					<div class="col-md-2">
						<input type="date" name="dateto" id="dateto" class="form-control input-sm"   data-validation="required" value="<?php echo date("d-m-Y"); ?>" >
					</div>
				</div>
				</div>
                  <div class="form-group">
				<!-- <div class="form-group">
				
			</div> -->
	

				<div class="form-group">
					<label class="col-md-2 control-label" for="remark">Remark</label>
					<div class="col-md-2">
						<div class='input-group'>
							<textarea rows="5" name="remark" id="remark" class="form-control input-sm" data-validation="required"></textarea>
							
							
						</div>
						
					</div>
				</div>
				</div>
			</form>
		</div>

	@endsection


@section('scripts')

	<script src="js/hisdb/appointment/doctor_maintenanceScript.js"></script>
	
@endsection