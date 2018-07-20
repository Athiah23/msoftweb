<!DOCTYPE html>

<html lang="en">
<head>
	
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">


	<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
	<script type="text/ecmascript" src="plugins/jquery-migrate-3.0.0.js"></script>
    <script type="text/ecmascript" src="plugins/trirand/i18n/grid.locale-en.js"></script>
    <script type="text/ecmascript" src="plugins/trirand/jquery.jqGrid.min.js"></script>
    <script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript">$.fn.modal.Constructor.prototype.enforceFocus = function() {};</script>
    <script type="text/ecmascript" src="plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script type="text/ecmascript" src="plugins/form-validator/jquery.form-validator.min.js"></script>
    <script type="text/ecmascript" src="plugins/numeral.min.js"></script>
	<script type="text/ecmascript" src="plugins/moment.js"></script>
	<script type="text/ecmascript" src="plugins/fullcalendar-3.7.0/fullcalendar.min.js"></script>
	<script type="text/ecmascript" src="plugins/bootbox.min.js"></script>
	<script type="text/ecmascript" src="plugins/velocity.min.js"></script>
	<script type="text/ecmascript" src="plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>

	<link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="plugins/jquery-ui-1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="plugins/css/trirand/ui.jqgrid-bootstrap.css" />
    <link rel="stylesheet" href="plugins/form-validator/theme-default.css" />
	<link rel="stylesheet" href="plugins/font-awesome-4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="plugins/fullcalendar-3.7.0/fullcalendar.min.css">
	<link rel="stylesheet" href="plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
	@yield('css')
	
    <style>
	    html{
	 		height: 100%;
	    }
    	body{
  			height: 100%;
    		background: #f9f1f1;
			background: -webkit-linear-gradient(#f9f1f1, #0c7567);
			background: -o-linear-gradient(#f9f1f1, #0c7567);
			background: -moz-linear-gradient(#f9f1f1, #0c7567);
    		background: linear-gradient(#f9f1f1, #0c7567) !important;
    		background-repeat: no-repeat !important;
    		background-attachment: fixed !important;
    	}
		.formclass{
			background-color:#f5f5f5;
			border-radius:5px 5px 5px 5px;
			box-shadow:0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
			padding: 5px 0px 10px 0px;
			margin: 0px 0px 15px 0px;
		}
		.ScolClass{
			float:left;
			margin-top:5px;
		}
		.StextClass{
			position: relative;
			padding-left: 65px;
			margin-top: 25px;
			padding-right: 60%;
		}
		.Stext2{
		    position: absolute;
		    right: 2%;
		    top: 12px;
		}
		.row{
			padding: 0px 5px 5px 5px;
			margin: 0px 5px 5px 5px;
		}
		.pointer {
			cursor: pointer;
		}
		.wrap{
			word-wrap: break-word;
			white-space: normal !important;
			vertical-align: top !important;
		}
		.ui-th-column{
			word-wrap: break-word;
			white-space: normal !important;
			vertical-align: top !important;
		}
		.radio-inline+.radio-inline {
			margin-left: 0;
		}
		.alert.alert-warning{
			float: left !important;
			margin-bottom: 0 !important;
			width: 80% !important;
			padding: 10px !important;
		}
		.radio-inline {
			margin-right: 10px;
		}
		::-webkit-scrollbar{
		  width: 10px;  /* for vertical scrollbars */
		  height: 10px; /* for horizontal scrollbars */
		}
		::-webkit-scrollbar-track{
		  background: rgba(0, 0, 0, 0.1);
		}
		::-webkit-scrollbar-thumb{
		  background: rgba(0, 0, 0, 0.5);
		}
		.box-shadow--2dp {
			box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12)
		}
		.box-shadow--3dp {
			box-shadow: 0 3px 4px 0 rgba(0, 0, 0, .14), 0 3px 3px -2px rgba(0, 0, 0, .2), 0 1px 8px 0 rgba(0, 0, 0, .12)
		}
		.box-shadow--4dp {
			box-shadow: 0 4px 5px 0 rgba(0, 0, 0, .14), 0 1px 10px 0 rgba(0, 0, 0, .12), 0 2px 4px -1px rgba(0, 0, 0, .2)
		}
		.box-shadow--6dp {
			box-shadow: 0 6px 10px 0 rgba(0, 0, 0, .14), 0 1px 18px 0 rgba(0, 0, 0, .12), 0 3px 5px -1px rgba(0, 0, 0, .2)
		}
		.box-shadow--8dp {
			box-shadow: 0 8px 10px 1px rgba(0, 0, 0, .14), 0 3px 14px 2px rgba(0, 0, 0, .12), 0 5px 5px -3px rgba(0, 0, 0, .2)
		}
		.box-shadow--16dp {
			box-shadow: 0 16px 24px 2px rgba(0, 0, 0, .14), 0 6px 30px 5px rgba(0, 0, 0, .12), 0 8px 10px -5px rgba(0, 0, 0, .2)
		}
		.ui-search-toolbar {
			background: rgba(0,0,0,0.05);
		}
		.ui-search-toolbar input[type='text']{
			height:25px;
		}
		.minuspad-15{
			padding-left: 0px !important;
			padding-right: 0px !important;
		}
		.minuspad-13{
			padding-left: 2px !important;
			padding-right: 2px !important;
		}
		.myformgroup{
			display: -webkit-box;
			margin-bottom: 15px;
		}
		.form-horizontal .myformgroup{
			margin-right: -15px;
			margin-left: -15px;
		}
		.allobtn{
			font-weight: bold;
		}
		.allobtn:hover{
			background: rgba(51, 122, 183, 0.3);
			outline: none;
		}
		html{
			height:100%;
		}
		.data_info{
			text-align: center;
			color: #286090;
			background: #d9edf7;
			width: 400px;
			height: 120px;
		    margin: 0px !important;
		    padding: 5px !important;
		    border-top-left-radius: 30px;
		    position: absolute;
		    bottom: 0px;
		    right: 0px;
		    cursor: pointer;
		    display: block;
		}
		.click_row{
			width:15%;
			display: inline-block;
			padding:0 5px 1px 0;
			background: beige;
	    	margin: 5px;
	    	border-radius: 5px;
	    	text-align: center;
		    cursor: pointer;
		}
		.click_row:hover{
			opacity: 0.7;
		}
		.help-block{
			margin: 0 !important;
		}
		@yield('style')
 	</style>	
    <title>@yield('title')</title>
</head>
<body style="display:none">


	@yield('body')

	@yield('scripts')
	<!-- <script src="religionScript.js"></script> example yielded scripts-->

	<script src="js/myjs/utility.js"></script>
	<!-- <script src="js/myjs/dialogHandler.js"></script> -->

<script>
		
</script>
</body>
</html>