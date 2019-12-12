
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

			/////////////////////////////////// currency ///////////////////////////////
			var mycurrency =new currencymode(['#amount']);

			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				id: "saveBut", 
				text: "Save",click: function() {
					/*mycurrency.formatOff();
					mycurrency.check0value(errorField);*/
						if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
							if ($("#formdata :input[name='payto']").val() === $("#formdata :input[name='bankcode']").val()) {
									
										bootbox.alert("Bank Code Credit cannot be same with Bank Code Debit");
									
									}
							else {
								saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
							}
						}/*else{
							mycurrency.formatOn();
						}*/
					}
			},{
				id: "canBut",
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
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							$('#formdata :input[hideOne]').show();
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
						dialog_paymode.on();
						dialog_bankcodefrom.on();
						dialog_bankcodeto.on();
						dialog_cheqno.on();
						
					}
					if(oper!='add'){
						dialog_paymode.check(errorField);
						dialog_bankcodefrom.check(errorField);
						dialog_bankcodeto.check(errorField);
						dialog_cheqno.check(errorField);
					
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					$('.my-alert').detach();
					dialog_paymode.off();
					dialog_bankcodefrom.off();
					dialog_bankcodeto.off();
					dialog_cheqno.off();
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
				table_name:'finance.apacthdr',
				table_id:'auditno',
				filterCol: ['source', 'trantype'],
				filterVal: ['CM', 'FT'],
				sort_idno: true
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				url:'bankTransfer/form',
				field:'',
				oper:oper,
				table_name:'finance.apacthdr',
				table_id:'auditno',
				sysparam: {source: 'CM', trantype: 'FT', useOn: 'auditno'},
				sysparam2: {source: 'HIS', trantype: 'PV', useOn: 'pvno'},
				saveip:'true',
				checkduplicate:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
				 	{label: 'compcode', name: 'compcode', width: 10 , hidden: true,  classes: 'wrap'},
				 	{label: 'idno', name: 'idno', width: 10 , hidden: true,  classes: 'wrap'},
				 	{label: 'source', name: 'source', width: 10, hidden: true, classes: 'wrap'},
				 	{label: 'trantype', name: 'trantype', width: 10, hidden: true, classes: 'wrap'},
					{label: 'Audit No', name: 'auditno', width: 27, classes: 'wrap'},
					{label: 'Payment No', name: 'pvno', width: 40, hidden: true, classes: 'wrap'},
					{label: 'Transfer Date', name: 'actdate', width: 25, canSearch:true, checked:true, classes: 'wrap'},
					{label: 'Bank Code From', name: 'bankcode', width: 35, classes: 'wrap', canSearch:true},
					{label: 'Bank Code To', name: 'payto', width: 35, classes: 'wrap', canSearch:true},
					{label: 'Cheque Date', name: 'cheqdate', width: 90, classes: 'wrap', hidden:true},
					{label: 'Amount', name: 'amount', width: 30, classes: 'wrap', formatter:'currency'},
					{label: 'Remarks', name: 'remarks', width: 40, classes: 'wrap'},
					{label: 'Status', name: 'recstatus', width: 30, classes: 'wrap', formatter:formatterPost,},
					{label: 'Entered By', name: 'adduser', width: 30, classes: 'wrap'},
					{label: 'Entered Date', name: 'adddate', width: 30, classes: 'wrap'},
					{label: 'Paymode', name: 'paymode', width: 30, classes: 'wrap', canSearch:true},
					{label: 'Cheq No', name: 'cheqno', width: 30, classes: 'wrap', formatter:formatterCheqnno, unformat:unformatterCheqnno},
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				sortname:'idno',
				sortorder:'desc',
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
						$("#saveBut").show();
						$("#canBut").show();
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
				},
				onSelectRow: function(rowid, selected) {
					var buts = $('#jqGrid').jqGrid('getRowData', rowid);
					auditno=rowid;
					recstatus=buts.recstatus;


					if (recstatus=='Open'){
						$("#postedBut").show();
						$("#cancelBut").show();
						$("#saveBut").show();
						$("#canBut").show();
						$("#glyphicon-edit").show();

					}

					else if (recstatus==='Posted'){
						$("#postedBut").hide();
						$("#cancelBut").hide();
						$("#saveBut").hide();
						$("#canBut").hide();
						$("#glyphicon-edit").hide();
						//$("#glyphicon-plus").hide();
					} 

					else if (recstatus==='Cancel'){
						$("#postedBut").hide();
						$("#cancelBut").hide();
						$("#saveBut").hide();
						$("#canBut").hide();
						$("#glyphicon-edit").hide();
						//$("#glyphicon-plus").hide();
					} 
					else {
						$("#postedBut").hide();
						$("#cancelBut").hide();
					}
				}
				
			});

				$("#postedBut").hide();
				$("#cancelBut").hide();


				/*$("#postedBut").click(function(){
					var param={
						action:'bankreg_save',
						oper:'add',
						field:'',
						table_name:'finance.cbtran',
						table_id:'auditno',
						skipduplicate: true,
						returnVal:true,
						sysparam:{source:'PB',trantype:'TN',useOn:'auditno'}
					};

					$.post( "../../../../assets/php/entry.php?"+$.param(param),
						{seldata:selrowData("#jqGrid")}, 
						function( data ) {
							
						}
					).fail(function(data) {
						bootbox.alert('error');
					}).success(function(data){
						refreshGrid("#jqGrid",urlParam);
						$("#postedBut").hide();
						$("#cancelBut").hide();

					});
				});


				$("#cancelBut").click(function(){
					var param={
						action:'cancel_save',
						oper:'add',
						field:'',
						table_name:'finance.cbtran',
						table_id:'auditno',
						skipduplicate: true,
						returnVal:true,
						sysparam:{source:'PB',trantype:'TN',useOn:'auditno'}
					};

					$.post( "../../../../assets/php/entry.php?"+$.param(param),
						{seldata:selrowData("#jqGrid")}, 
						function( data ) {
							
						}
					).fail(function(data) {
						bootbox.alert('error');
					}).success(function(data){
						refreshGrid("#jqGrid",urlParam);
						$("#postedBut").hide();
						$("#cancelBut").hide();

					});
				});*/
			
			////////////////////formatter status////////////////////////////////////////
				function formatterPost(cellvalue, option, rowObject){
					if (cellvalue == 'O'){
						return 'Open';
					}

					else if (cellvalue == 'P'){
						return 'Posted';
					}

					else if (cellvalue == 'C'){
						return 'Cancel';
					}
				}

				function formatterCheqnno  (cellValue, options, rowObject) {
					//return rowObject[9] != "CHEQUE" ? "&nbsp;" : $.jgrid.htmlEncode(cellValue);
					return rowObject[15] != "CHEQUE" ? "<span cheqno='"+cellValue+"'></span>" : "<span cheqno='"+cellValue+"'>"+cellValue+"</span>";

				}

				function unformatterCheqnno (cellValue, options, rowObject) {
					return $(rowObject).find('span').attr('cheqno');
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'idno':selrowData('#jqGrid').idno});
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
					$( "#formdata :input[name='source']" ).val( "CM" );
					$( "#formdata :input[name='trantype']" ).val( "FT" );

				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////
			
			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			populateSelect('#jqGrid','#searchForm');
			//searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress']);


			////////////////////object for dialog handler//////////////////

			var dialog_paymode = new ordialog(
				'paymode','debtor.paymode','#paymode',errorField,
				{	colModel:[
						{label:'Pay Mode',name:'paymode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus', 'source'],
						filterVal:['session.compcode','A', 'CM']
					},
					ondblClickRow: function () {
						$('#bankcode').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#bankcode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Payment",
					open: function(){
						dialog_paymode.urlParam.filterCol=['compcode','recstatus', 'source'],
						dialog_paymode.urlParam.filterVal=['session.compcode','A', 'CM']
					}
				},'urlParam','radio','tab'
			);
			dialog_paymode.makedialog(true);

			var dialog_bankcodefrom = new ordialog(
				'bankcode','finance.bank','#bankcode',errorField,
				{	colModel:[
						{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
					ondblClickRow: function () {
						$('#cheqno').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#cheqno').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Bank Code",
					open: function(){
						dialog_bankcodefrom.urlParam.filterCol=['compcode','recstatus'],
						dialog_bankcodefrom.urlParam.filterVal=['session.compcode','A']
					}
				},'urlParam','radio','tab'
			);
			dialog_bankcodefrom.makedialog(true);

			var dialog_bankcodeto = new ordialog(
				'payto','finance.bank','#payto',errorField,
				{	colModel:[
						{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
					ondblClickRow: function () {
						$('#remarks').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#remarks').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Bank Code Pay To",
					open: function(){
						dialog_bankcodeto.urlParam.filterCol=['compcode','recstatus'],
						dialog_bankcodeto.urlParam.filterVal=['session.compcode','A']
					}
				},'urlParam','radio','tab'
			);
			dialog_bankcodeto.makedialog(true);

			var dialog_cheqno = new ordialog(
				'cheqno','finance.chqtran','#cheqno',errorField,
				{	colModel:[
						{label:'Cheque No',name:'cheqno',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
						
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
					ondblClickRow: function () {
						$('#cheqdate').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#cheqdate').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Cheque No",
					open: function(){
						dialog_cheqno.urlParam.filterCol=['compcode','stat', 'bankcode'],
						dialog_cheqno.urlParam.filterVal=['session.compcode','A', $('#bankcode').val()]
					}
				},'urlParam','radio','tab'
			);
			dialog_cheqno.makedialog(true);


	
		});
		