
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	$("body").show();
	/////////////////////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: ''
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				console.log(errorField);
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#amount']);
	
	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#trandate"]);
	actdateObj.getdata().set();

	////////////////////////////////////start dialog//////////////////////////////////////

	var oper;
	var unsaved = false;
	$("#dialogForm")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				switch (oper) {
					case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					break;
				case state = 'view':
					disableForm('#formdata');
					$("#pg_jqGridPager2 table").hide();
					break;
				}
				if(oper!='view'){
						dialog_supplier.on();
						dialog_payto.on();
						dialog_category.on();
						dialog_department.on();
				}
				if(oper!='add'){
						dialog_supplier.check(errorField);
						dialog_payto.check(errorField);
						dialog_category.check(errorField);
						dialog_department.check(errorField);
					}
			},
			beforeClose: function(event, ui){
				if(unsaved){
					event.preventDefault();
					bootbox.confirm("Are you sure want to leave without save?", function(result){
						if (result == true) {
							unsaved = false
							$("#dialogForm").dialog('close');
						}
					});
				}
			},
			close: function( event, ui ) {
		/*	addmore_jqgrid2.state = false;
			addmore_jqgrid2.more = false;*/
			//reset balik
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			$('.alert').detach();
			$("#formdata a").off();
			dialog_supplier.off();
			dialog_payto.off();
			dialog_category.off();
			dialog_department.off();
			$(".noti").empty();
			$("#refresh_jqGrid").click();
			//refreshGrid("#jqGrid2",null,"kosongkan");
			//radbuts.reset();
			errorField.length=0;
		},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'/util/get_table_default',
		field:'',
		fixPost:'true',
		table_name:['finance.apacthdr','material.supplier'],
		table_id:'apacthdr_idno',
		join_type:['LEFT JOIN'],
		join_onCol:['supplier.suppcode'],
		join_onVal:['apacthdr.suppcode'],
		filterCol: ['source'],
		filterVal: ['AP'],
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'invoiceAP_save',
		url:'/invoiceAP/form',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'finance.apacthdr',
		table_id:'idno'
	};

	$("#jqGrid").jqGrid({
	datatype: "local",
	colModel: [
		//{ label: 'compcode', name: 'compcode', width: 40, hidden:'true'},
		{ label: 'Audit No', name: 'apacthdr_auditno', width: 10, classes: 'wrap'},
		{ label: 'TT', name: 'apacthdr_trantype', width: 10, classes: 'wrap'},
		{ label: 'Creditor', name: 'apacthdr_suppcode', width: 20, classes: 'wrap', canSearch: true},
		{ label: 'Creditor Name', name: 'supplier_name', width: 50, classes: 'wrap', canSearch: true},
		{ label: 'Document Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap', canSearch: true},
		{ label: 'Document No', name: 'apacthdr_document', width: 50, classes: 'wrap', canSearch: true},
		{ label: 'Department', name: 'apacthdr_deptcode', width: 25, classes: 'wrap'},
		{ label: 'Amount', name: 'apacthdr_amount', width: 25, classes: 'wrap',align: 'right'},
		{ label: 'Status', name: 'apacthdr_recstatus', width: 25, classes: 'wrap',},
		{ label: 'Pay To', name: 'apacthdr_payto', width: 50, classes: 'wrap', hidden:true},
		{ label: 'Doc Date', name: 'apacthdr_recdate', width: 25, classes: 'wrap', hidden:true},
		{ label: 'category', name: 'apacthdr_category', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'remarks', name: 'apacthdr_remarks', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'adduser', name: 'apacthdr_adduser', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'adddate', name: 'apacthdr_adddate', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'upduser', name: 'apacthdr_upduser', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'upddate', name: 'apacthdr_upddate', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'source', name: 'apacthdr_source', width: 40, hidden:'true'},
		{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:'true'},
	],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'apacthdr_idno',
		sortorder:'desc',
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPager",
		/*onSelectRow:function(rowid, selected){
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			switch($("#scope").val()){
				case "cancel": 
					if(stat=='POSTED'){
						$('#but_cancel_jq').show();
						$('#but_post_jq,#but_reopen_jq').hide();
					}else if(stat=="CANCELLED"){
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					}else{
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					}
					break;
				case "all": 
					if(stat=='POSTED'){
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
					}else if(stat=="CANCELLED"){
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
					}else{
						$('#but_cancel_jq,#but_post_jq').show();
						$('#but_reopen_jq').hide();
					}
					break;
			}

			refreshGrid("#jqGrid3",urlParam2);
		},*/
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			if (oper == 'add' || oper == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
		},
		

	});

	////////////////////// set label jqGrid right ////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	////////////////////////////////hide at dialogForm///////////////////////////////////////////////////
	function hideatdialogForm(hide){
		if(hide){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete").hide();
			$("#saveDetailLabel").show();
		}else{
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete").show();
			$("#saveDetailLabel").hide();
		}
	}

	/*/////////////////////////////// for Button /////////////////////////////////////////////////////////
	var adtNo
		function sometodo(){
			$("#jqGrid2_iledit").show();
			$("#jqGrid2").jqGrid('showCol', 'action');
			$('#formdata  textarea').prop("readonly",true);
			$('#formdata :input[hideOne]').show();
			$('#formdata input').prop("readonly",true);
			$('#formdata  input[type=radio]').prop("disabled",true);
			$("input[id*='_auditno']").val(auditno);
			$("#formdata a").off(); 
		}
*/
	////////////////////selected///////////////

	$('#apacthdr_ttype').on('change', function() {
		let ttype1 = $("#apacthdr_ttype option:selected" ).val();
			if(ttype1 == 'IN') {
				$("#formdata :input[name='apacthdr_source']").val("AP");
				$("#formdata :input[name='apacthdr_trantype']").val("IN");
			}else if(ttype1 == 'DN') {
				$("#formdata :input[name='apacthdr_source']").val("AP");
				$("#formdata :input[name='apacthdr_trantype']").val("DN");
			}
			if (($("#apacthdr_ttype option:selected" ).text()=='Supplier')){
				$('#save').hide();
				$('#ap_parent').show();
			}
			else {
				$('#save').show();
				$('#ap_parent').hide();
			}
			});
	
	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			oper = 'del';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
				return emptyFormdata(errorField, '#formdata');
				//return emptyFormdata('#formdata');
			} else {
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", 'del', saveParam, urlParam, null, { 'idno': selrowData('#jqGrid').idno });
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view');
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit');
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_cancel_jq,#but_post_jq,#but_reopen_jq").click(function(){
		saveParam.oper = $(this).data("oper");
		let obj={
			auditno:selrowData('#jqGrid').auditno,
			_token:$('#_token').val(),
			idno:selrowData('#jqGrid').idno
		};
		$.post(saveParam.url+"?" + $.param(saveParam),obj,function (data) {
			refreshGrid("#jqGrid", urlParam);
		}).fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			//2nd successs?
		});
	});

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');


	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['apacthdr_idno','apacthdr_auditno','apacthdr_adduser','apacthdr_adddate','apacthdr_upduser','apacthdr_upddate','apacthdr_recstatus','supplier_name']);

	$("#save").click(function(){
		unsaved = false;
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveHeader("#formdata", oper,saveParam,{idno:selrowData('#jqGrid').apacthdr_idno});
				unsaved = false;
				$("#dialogForm").dialog('close');
				}else{
					mycurrency.formatOn();
			}
	});

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj){
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			//alert(data.responseJSON.message);
			dialog_supplier.on();
			dialog_payto.on();
			dialog_category.on();
			dialog_department.on();
		}).done(function (data) {

			unsaved = false;
			hideatdialogForm(false);
			
			/*if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				//addmore_jqgrid2.state = true;
				$('#jqGrid2_iladd').click();
			}*/
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				//sometodo();
				$('#pvno').val(data.pvno);
				$('#auditno').val(data.auditno);
				$('#amount').val(data.amount);//just save idno for edit later
				
				//urlParam.filterVal[0]=data.auditno;
			}else if(selfoper=='edit'){
				//doesnt need to do anything
			}
			disableForm('#formdata');
			
		});
	}
	
	$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	////////////////////////////populate data for dropdown search By////////////////////////////
	searchBy();
	function searchBy(){
		$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
			if(value['canSearch']){
				if(value['selected']){
					$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
				}else{
					$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
				}
			}
			searchClick2('#jqGrid','#searchForm',urlParam);
		});
	}

	////////////////////object for dialog handler///////////////////
	var dialog_supplier = new ordialog(
		'supplier','material.supplier','#apacthdr_suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Name',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			]
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_supplier.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_supplier.urlParam.filterVal=['A', '9A']
				}
			},'urlParam'
		);
	dialog_supplier.makedialog();

	var dialog_payto = new ordialog(
		'payto','material.supplier','#apacthdr_payto',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			]
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_payto.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_payto.urlParam.filterVal=['A', '9A']
				}
			},'urlParam'
		);
	dialog_payto.makedialog();

	var dialog_category = new ordialog(
		'category','material.category','#apacthdr_category',errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'povalidate',name:'povalidate',width:400,classes:'pointer', hidden:true},
				{label:'source',name:'source',width:400,classes:'pointer', hidden:true},
			],
			/*ondblClickRow:function(){
				let data=selrowData('#'+dialog_category.gridname);
				
				$('#apacthdr_category').val(data['apacthdr_trantype']);
				//$("#jqGrid input[name='apacthdr_category']").val(data['apacthdr_category']);
			
			}	*/
		},{
			title:"Select Category Code",
			open: function(){
				//if(apacthdr_ttype=='#apacthdr_category'){&& ($("#apacthdr_ttype").text()=='Supplier'))
					if (($("#apacthdr_trantype").val()=="IN")) {
						dialog_category.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
						dialog_category.urlParam.filterVal=['A', '9A', 'CR', '!=.0'];
					}else {
						dialog_category.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
						dialog_category.urlParam.filterVal=['A', '9A', 'CR', '=.0'];
					}
				}

				//}		
				
			},'urlParam'
		);
	dialog_category.makedialog();

	var dialog_department = new ordialog(
		'department','sysdb.department','#apacthdr_deptcode',errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			]
		},{
			title:"Select Department Code",
			open: function(){
				dialog_department.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_department.urlParam.filterVal=['A', '9A']
				}
			},'urlParam'
		);
	dialog_department.makedialog();

var genpdf = new generatePDF('#pdfgen1','#formdata','#jqGrid2');
	genpdf.printEvent();
});
