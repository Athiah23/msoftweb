
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
	/////////////////////////validation//////////////////////////
	$.validate({
		language : {
			requiredFields: ''
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				return {
					element : $('#'+errorField[0]),
					message : ' '
				}
			}
		},
	};
	//////////////////////////////////////////////////////////////


	////////////////////object for dialog handler//////////////////

	var dialog_costcode = new ordialog(
		'ccode','finance.costcenter','#ccode',errorField,
		{	colModel:[
				{label:'Code',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			]
		},{
			title:"Select Cost Code",
			open: function(){
				dialog_costcode.urlParam.filterCol=['recstatus'],
				dialog_costcode.urlParam.filterVal=['A']
			}
		},'urlParam'
	);
	dialog_costcode.makedialog();

	var dialog_glaccount = new ordialog(
		'glaccno','finance.glmasref','#glaccno',errorField,
		{	colModel:[
				{label:'Code',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			]
		},{
			title:"Select GL Account",
			open: function(){
				dialog_glaccount.urlParam.filterCol=['recstatus'],
				dialog_glaccount.urlParam.filterVal=['A']
			}
		},'urlParam'
	);
	dialog_glaccount.makedialog();

	var dialog_cardbank = new ordialog(
		'cardcent','finance.bank','#cardcent',errorField,
		{	colModel:[
				{label:'Code',name:'code',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			]
		},{
			title:"Select Bank",
			open: function(){
				dialog_cardbank.urlParam.filterCol=['recstatus'],
				dialog_cardbank.urlParam.filterVal=['A']
			}
		},'urlParam'
	);
	dialog_cardbank.makedialog();
	
	//// to hide bank/card handler////

	function disableCardBank() {
		$("#cardcent").hide();
		$("#2shit").addClass("hidden");
		$("#3shit").removeClass("hidden");
		$("#4shit").hide();
	}

	function enableCardBank() {
		$("label[for=cardcent]").show();
		$("#cardcent").show();
		$("#4shit").show();
		$("#2shit").removeClass("hidden");
		$("#3shit").addClass("hidden");
	}

	///////////////end hide bank/card handler/////////

	$("input[name=paytype]:radio").on('change',  function(){
		paytype = $("input[name=paytype]:checked").val();			 
	
		if(paytype == 'Bank') {
			$("label[for=cardcent]").text(paytype);
			enableCardBank();
			dialog_cardbank.urlParam.table_name='finance.bank';
			dialog_cardbank.urlParam.field=['bankcode as code','bankname as description'];
			
		}else if(paytype == 'Card') {
			$("label[for=cardcent]").text(paytype);
			enableCardBank();
			dialog_cardbank.urlParam.table_name='finance.cardcent';
			dialog_cardbank.urlParam.field=['cardcode as code','name as description'];

		} else  {
			$("label[for=cardcent]").hide();
			disableCardBank();
		}
	});


	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1=[{
		text: "Save",click: function() {
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
			}
		}
	},{
		text: "Cancel",click: function() {
			$(this).dialog('close');
		}
	}];

	var butt2=[{
		text: "Close",click: function() {
			$(this).dialog('close');
		}
	}];

	var oper;
	$("#dialogForm")
	  .dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			switch(oper) {
				case state = 'add':
					$( this ).dialog( "option", "title", "Add" );
					$("label[for=cardcent]").hide();
					disableCardBank();
					enableForm('#formdata');
					rdonly("#formdata");
					hideOne("#formdata");
					break;

				case state = 'edit':
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					rdonly("#formdata");
					$('#formdata :input[hideOne]').show();
					paytype = $("input[name=paytype]:checked").val();			 

					if(paytype == 'Bank') {
						$("label[for=cardcent]").text(paytype);
						enableCardBank();
						dialog_cardbank.urlParam.table_name='finance.bank';
						dialog_cardbank.urlParam.field=['bankcode as code','bankname as description'];
						
					}else if(paytype == 'Card') {
						$("label[for=cardcent]").text(paytype);
						enableCardBank();
						dialog_cardbank.urlParam.table_name='finance.cardcent';
						dialog_cardbank.urlParam.field=['cardcode as code','name as description'];

					} else  {
						$("label[for=cardcent]").hide();
						disableCardBank();
					}
					break;
					
				case state = 'view':
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					$(this).dialog("option", "buttons",butt2);
					break;
			}
			if(oper!='view'){
				set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
				dialog_glaccount.on();
				dialog_costcode.on();
				dialog_cardbank.on();
			}
			if(oper!='add'){
				dialog_glaccount.check(errorField);
				dialog_costcode.check(errorField);
				dialog_cardbank.check(errorField);
			}
		},
		close: function( event, ui ) {
			dialog_glaccount.off();
			dialog_costcode.off();
			dialog_cardbank.off();
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			$('#formdata .alert').detach();
			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
		},
		buttons :butt1,
	  });
	////////////////////////////////////////end dialog///////////////////////////////////////////

	
	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'debtor.paymode',
		table_id:'paymode',
		filterCol:['source'],
		filterVal:[$('#source2').val()]
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		url:'paymentMode/form',
		field:'',
		oper:oper,
		table_name:'debtor.paymode',
		table_id:'paymode',
		filterCol:['source'],
		filterVal:[$('#source2').val()],
		saveip:'true'

	};
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{label: 'compcode', name: 'compcode', width: 90 , hidden: true},
			{label: 'source', name: 'source', width: 90, hidden: true},
			{label: 'PayMode', name: 'paymode', width: 90, classes: 'wrap', canSearch: true,},
			{label: 'PayType', name: 'paytype', width: 90, classes: 'wrap'},
			{label: 'Description', name: 'description', width: 100, canSearch: true, checked:true,classes: 'wrap'}, 
			{label: 'Cost Code', name: 'ccode', width: 90, hidden: true, classes: 'wrap'}, 
			{label: 'GL Account', name: 'glaccno', width: 90, hidden: true, classes: 'wrap'},
			{label: 'Dr. Payment', name: 'drpayment', width: 90, hidden: true, classes: 'wrap'},
			{label: 'Card Flag', name: 'cardflag', width: 90, hidden: true,classes: 'wrap'},
			{label: 'ValExpDate', name: 'valexpdate', width: 90, hidden: true, classes: 'wrap'},
			{label: 'Card Cent', name: 'cardcent', width: 90, hidden: true, classes: 'wrap'},
			{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
			{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
			{ label: 'deluser', name: 'deluser', width: 90, hidden:true},
			{ label: 'deldate', name: 'deldate', width: 90, hidden:true},
			{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
			{ label: 'upddate', name: 'upddate', width: 90, hidden:true},
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
			{ label: 'computerid', name: 'computerid', width: 90, hidden:true},
			{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden:true},
			{label: 'idno', name: 'idno', width: 200, hidden: true, classes: 'wrap'}, 
			{ label: 'Record Status', name: 'recstatus', width: 25, classes: 'wrap', formatter:formatterstatus, unformat:unformatstatus, cellattr: function(rowid, cellvalue)
							{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, 
			},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
		},
		
		
	});

	/*///////////////////////////formatter//////////////////////////////////////////////////////////
	function formatter(cellvalue, options, rowObject){
		if(cellvalue == 'A'){
			return "Active";
		}
		if(cellvalue == 'D') { 
			return "Deactive";
		}
	}

	function  unformat(cellvalue, options){
		if(cellvalue == 'Active'){
			return "Active";
		}
		if(cellvalue == 'Deactive') { 
			return "Deactive";
		}
	}*/
	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			oper='del';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#formdata');
			}else{
				saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,null,{'idno':selRowId});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			oper='view';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",  
		onClickButton: function(){
			oper='edit';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
			recstatusDisable();
		}, 
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$( "#dialogForm" ).dialog( "open" );
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////
	
	//////////handle searching, its radio button and toggle////////////////////////////////////////////////

	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress']);

});
