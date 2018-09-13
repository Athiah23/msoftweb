$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$('body').show();
			/////////////////////////validation//////////////////////////
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
			//////////////////////////////////////////////////////////////
				
		////////////////////////////////////start dialog///////////////////////////////////////

		var dialog_assetcode= new ordialog(
			'assetcode','finance.facode','#assetcode',errorField,
			{	colModel:[
				    {label:'Assetcode',name:'assetcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,or_search:true},
					{label:'AssetType',name:'assettype',width:100,classes:'pointer',hidden:true},
					{label:'Method',name:'method',width:100,classes:'pointer',hidden:true},
					{label:'Residualvalue',name:'residualvalue',width:100,classes:'pointer',hidden:true},
			],
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_assetcode.gridname);
				$('#assettype').val(data['assettype']);		
				$('#method').val(data['method']);
				$('#rvalue').val(data['residualvalue']);
			}},
			{
				title:"Select Category",
				open: function(){
					dialog_assetcode.urlParam.filterCol=['compcode'];
					dialog_assetcode.urlParam.filterVal=['9A'];
				}
			},'urlParam','radio','tab'
		);
		dialog_assetcode.makedialog();

		var dialog_deptcode= new ordialog(
			'deptcode','sysdb.department','#deptcode',errorField,
			{	colModel:[
				    {label:'Deptcode',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,or_search:true},
			]},
			{
				title:"Select Department",
				open: function(){
					dialog_deptcode.urlParam.filterCol=['compcode'],
					dialog_deptcode.urlParam.filterVal=['9A']
				}
			},'urlParam','radio','tab'
		);
		dialog_deptcode.makedialog();

		var  dialog_loccode= new ordialog(
			'loccode','sysdb.location','#loccode',errorField,
			{	colModel:[
				    {label:'Loccode',name:'loccode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,or_search:true},

			]
			},
			{
				title:"Select Location",
				open: function(){
					dialog_loccode.urlParam.filterCol=['compcode'],
					dialog_loccode.urlParam.filterVal=['9A']
				}
			},'urlParam','radio','tab'
		);
		dialog_loccode.makedialog();		

	    var dialog_delordno= new ordialog(
			'delordno','material.delordhd','#delordno',errorField,
			{	colModel:[
				    {label:'Delordno',name:'delordno',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Suppcode',name:'suppcode',width:300,classes:'pointer',canSearch:true,or_search:true},
							
					]
			},{
				title:"Select Delordno",
				open: function(){
					dialog_delordno.urlParam.filterCol=['compcode'],
					dialog_delordno.urlParam.filterVal=['9A']
				}
			},'urlParam','radio','tab'
		);
		dialog_delordno.makedialog();

		var  dialog_suppcode= new ordialog(
			'suppcode','material.supplier','#suppcode',errorField,
			{	colModel:[
				    {label:'SuppCode',name:'suppcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Name',name:'name',width:300,classes:'pointer',canSearch:true,or_search:true},

			]
			},{
				title:"Select Supplier",
				open: function(){
					dialog_suppcode.urlParam.filterCol=['compcode'],
					dialog_suppcode.urlParam.filterVal=['9A']
				}
			},'urlParam','radio','tab'
		);
		dialog_suppcode.makedialog();
		
		var dialog_itemcode= new ordialog(
			'itemcode','material.product','#itemcode',errorField,
			{	colModel:[
					{label:'Itemcode',name:'itemcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'description',name:'description',width:300,classes:'pointer',canSearch:true,or_search:true},
					
					]
			},{
				title:"Select Itemcode",
				open: function(){
					dialog_itemcode.urlParam.filterCol=['compcode','groupcode','productcat'],
					dialog_itemcode.urlParam.filterVal=['9A','asset',$('#assetcode').val()]
				}
			},'urlParam','radio','tab'
		);
		dialog_itemcode.makedialog();
	


		var butt1=[{
				text: "Save",click: function() {
					if( checkdate_asset(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
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
					switch(oper) {
						case state = 'add':
							//mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							ToggleDisableForm();
							rdonly("#dialogForm");
							
							break;
						case state = 'edit':
							//mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly("#dialogForm");
							
							break;
						case state = 'view':
							//mycurrency.formatOn();
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							getmethod_and_res(selrowData("#jqGrid").assetcode);
							//getNVB();
							//getOrigCost();
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
						dialog_assetcode.on();
						dialog_deptcode.on();
						dialog_loccode.on();
						dialog_suppcode.on();
						dialog_itemcode.on();
						dialog_delordno.on();
					}
					if(oper!='add'){
						dialog_itemcode.check(errorField);
						dialog_delordno.check(errorField);
						dialog_assetcode.check(errorField);
						dialog_suppcode.check(errorField);
						dialog_deptcode.check(errorField);
						dialog_loccode.check(errorField);
					}
				},
				close: function( event, ui ) {
					emptyFormdata(errorField,'#formdata');
					$(".noti ol").empty();
					$("#formdata a").off();
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
				url: '/util/get_table_default',
				field:'',
				table_name:'finance.fatemp', 
				table_id:'idno',				
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				url:'assetregister/form',
				oper:oper,
				table_name:'finance.fatemp',
				table_id:'idno' 				
				
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'compcode', name: 'compcode', width: 20, hidden:true },
					{ label: 'Idno', name: 'idno', width: 8, sorttype: 'text', classes: 'wrap', checked: true}, 
					{ label: 'Category', name: 'assetcode', width: 15, sorttype: 'text', classes: 'wrap' },
					{ label: 'Asset Type', name: 'assettype', width: 15, sorttype: 'text', classes: 'wrap'},
					{ label: 'Department', name: 'deptcode', width: 15, sorttype: 'text', classes: 'wrap'},			
					{ label: 'Location', name: 'loccode', width: 40, sorttype: 'text', classes: 'wrap', hidden:true},					
					{ label: 'Supplier', name: 'suppcode', width: 20, sorttype: 'text', classes: 'wrap'},	
					{ label: 'DO No', name:'delordno',width: 15, sorttype:'text', classes:'wrap'},					
					{ label: 'Invoice No', name:'invno', width: 20,sorttype:'text', classes:'wrap', canSearch: true},
					{ label: 'Purchase Order No', name:'purordno',width: 20, sorttype:'text', classes:'wrap', hidden:true},
					{ label: 'Item Code', name: 'itemcode', width: 15, sorttype: 'text', classes: 'wrap', canSearch: true},
					{ label: 'Regtype', name: 'regtype', width: 40, sorttype: 'text', classes: 'wrap', hidden:true},	
					//{ label: 'Description', name: 'description', width: 40, sorttype: 'text', classes: 'wrap', canSearch: true,},
					{ label: 'DO Date', name:'delorddate', width: 20, classes:'wrap',formatter:dateFormatter, hidden:true},
					{ label: 'Invoice Date', name:'invdate', width: 20, classes:'wrap', formatter:dateFormatter, hidden:true},
					{ label: 'GRN No', name:'docno', width: 20, classes:'wrap',hidden:true},
					{ label: 'Purchase Date', name:'purdate', width: 20, classes:'wrap', formatter:dateFormatter, hidden:true},																	
					{ label: 'Purchase Price', name:'purprice', width: 20, classes:'wrap', hidden:true},
					{ label: 'Original Cost', name:'origcost', width: 20, classes:'wrap', hidden:true},
					{ label: 'Current Cost', name:'currentcost', width:20, classes:'wrap', hidden:true},
					{ label: 'Quantity', name:'qty', width:20, classes:'wrap', hidden:true},
					{ label: 'Individual Tagging', name:'individualtag', width:20, classes:'wrap', hidden:true},
					{ label: 'Delivery Order Line No', name:'lineno_', width:20, classes:'wrap', hidden:true},
					//method
					//residual value
					{ label: 'Start Date', name:'statdate', width:20, classes:'wrap', formatter:dateFormatter, hidden:true},
					{ label: 'Post Date', name:'trandate', width:20, classes:'wrap', formatter:dateFormatter, hidden:true},
					//accumprev
					{ label: 'Accum Prev', name:'lstytddep', width:20, classes:'wrap', hidden:true},
					//accumytd
					{ label: 'Accum YTD', name:'cuytddep', width:20, classes:'wrap', hidden:true},
					//nbv
					{ label: 'Status', name:'recstatus', width:20, classes:'wrap', hidden:true},
					{ label: 'Tran Type', name:'trantype', width:20, classes:'wrap', hidden:true},

								
				
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				sortname: 'idno',
				sortorder: 'desc',
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					if (oper == 'add') {
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
						}
	
						$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
				},				
			});

			////////////////////////////// DATE FORMATTER ////////////////////////////////////////
			function dateFormatter(cellvalue, options, rowObject){
				return moment(cellvalue).format("YYYY-MM-DD");
			}

			///////////////////////// REGISTER TYPE SELECTION/////////////////////////////////////
			$("input[name=regtype]:radio").on('change', function(){
				regtype  = $("input[name=regtype]:checked").val();
				if(regtype == 'P'){
					disableField();
				}else if(regtype == 'D') {
					enableField();
				}
			});

			function disableField() {
				$("#invno").prop('readonly',true);
				dialog_delordno.on()
				$("#delorddate").prop('readonly',true);
				$("#invdate").prop('readonly',true);
				$("#docno").prop('readonly',true);
				$("#purordno").prop('readonly',true);
				$("#purdate").prop('readonly',true);
				$("#purprice").prop('readonly',true);
				$("#origcost").prop('readonly',true);
				$("#currentcost").prop('readonly',true);
				$("#qty").prop('readonly',true);
			}

			function enableField() {
				$("#invno").prop('readonly',false);
				dialog_delordno.off()
				$("#delorddate").prop('readonly',false);
				$("#invdate").prop('readonly',false);
				$("#docno").prop('readonly',false);
				$("#purordno").prop('readonly',false);
				$("#purdate").prop('readonly',false);
				$("#purprice").prop('readonly',false);
				$("#origcost").prop('readonly',true);
				$("#currentcost").prop('readonly',true);
				$("#qty").prop('readonly',false);
			}


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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'idno':selRowId});
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
					if(selrowData('#jqGrid').regtype == 'P'){
						disableField();
					}else if(selrowData('#jqGrid').regtype == 'D') {
						enableField();
						
					}
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
					if(selrowData('#jqGrid').regtype == 'P'){
						disableField();
					}else if(selrowData('#jqGrid').regtype == 'D') {
						enableField();
						
					}
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
			
			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam);
			
			$("#delorddate,#invdate,#delorddate").blur(checkdate_asset);

			function checkdate_asset(nkreturn=false){
				var delorddate = $('#delorddate').val();
				var invdate = $('#invdate').val();
				var purdate = $('#purdate').val();

				$(".noti ol").empty();
				var failmsg=[];

				if(moment(invdate).isBefore(delorddate)){
					failmsg.push("Invoice date cannot be lower than Delivery Order date");
				}

				if(moment(purdate).isAfter(invdate) || moment(purdate).isAfter(delorddate) ){
					failmsg.push("Purchase date cannot be greater than Invoice date and Delivery Order date");
				}

				if(failmsg.length){
					failmsg.forEach(function(element){
						$('#dialogForm .noti ol').prepend('<li>'+element+'</li>');
					});
					if(nkreturn)return false;
				}else{
					if(nkreturn)return true;
				}

			}

			function ToggleDisableForm(disable=true){
				if(disable){
					disableForm('#disableGroup');
					$('#disableGroup input').on('click',alert_toogle);
				}else{
					enableForm('#disableGroup');
					$("#currentcost").prop("readonly",true);
					$('#disableGroup input').off('click',alert_toogle);
				}
			}

			function alert_toogle(){
				let element = "Choose 'Register Type' either Purchase Order or Direct";
				$(".noti ol").empty();
				$('#dialogForm .noti ol').prepend('<li>'+element+'</li>');
			}

			$("input[type='radio'][name='regtype']").on('click',regtype_choose);

			function regtype_choose(){
				ToggleDisableForm(false);
				$(".noti ol").empty();
				$( '#disableGroup input' ).each(function() {
					if ( $(this).hasClass('error') || $(this).closest("div").hasClass('has-error') ){
						$(this).removeClass('error');
						$(this).closest("div .has-error").removeClass('has-error');
						$(this).css("border-color","");
					}
				});
				$('#disableGroup .help-block').html('');
			}

			$("#purprice,#qty").blur(getcurentcost);
			function getcurentcost(event) { 
		        let purprice = parseFloat($("#purprice").val());
		        let qty = parseFloat($("#qty").val());

		        var currentcost = (purprice * qty);

		        $("#currentcost, #origcost").val(currentcost);
			}
		
		});