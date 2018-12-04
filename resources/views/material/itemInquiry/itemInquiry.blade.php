@extends('layouts.main')

@section('title', 'Debtor Type')

@section('body')

	<div class='row'>
		<input id="Class2" name="Class" type="hidden" value="{{ $_GET['Class'] }}">
		<input id="getYear" name="getYear" type="hidden"  value="{{ Carbon\Carbon::now()->year }}">

		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass"  style="padding-right: 15px;">
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

 		<div class="panel panel-default">
		    <div class="panel-body">
		    	<div class='col-md-8'>
            		<table id="detail" class="table table-striped"></table>
            			<div id="jqGridPager2"></div>
        		</div>

        		<div class='col-md-4'>
            		<table id="itemExpiry" class="table table-striped"></table>
            			<div id="jqGridPager3">			
            			</div>
        		</div>
        	</div>

		</div>

		<!-- <div class="panel panel-default">
		    <div class="panel-body">
		    	<div class='col-md-4'>
            		<table id="detail" class="table table-striped"></table>
            			<div id="jqGridPager4"></div>
        		</div> -->
		<!-- <form class='form-horizontal' style='width:99%' id='formdata'>
			
    <button type="button" id='save' class='btn btn-info btn-sm pull-right' style='margin: 0.2%;display: none;'>Detail Movement</button>
		</form>	
 -->	
 	<!-- <div id="dialogForm" title="detailmovement  dialog">
  <div id=body></div>
</div> -->
    </div>


	@endsection

@section('scripts')

	<script src="js/material/itemInquiry/itemInquiry.js"></script>

@endsection