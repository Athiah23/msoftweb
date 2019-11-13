
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$("body").show();
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
							element : $(errorField[0]),
							message : ' '
						}
					}
				},
			};
			
			////////////////////////////////////start dialog///////////////////////////////////////

			var mycurrency =new currencymode(['#minlimit','#maxlimit', '#d_minlimit', '#d_maxlimit', '#dtl_minlimit', '#dtl_maxlimit']);
			var mycurrency2 =new currencymode([]);
			var fdl = new faster_detail_load();
			var cbselect = new checkbox_selection("#gridAuthdtl","Checkbox","dtl_idno","dtl_cando");

			var oper;
			$("#dialogForm")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);

					refreshGrid("#jqGrid2", urlParam2);
					$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
					switch(oper) {
						case state = 'add':
							$("#jqGrid2").jqGrid("clearGridData", false);
							$("#pg_jqGridPager2 table").show();
							hideatdialogForm(true);
							enableForm('#formdata');
							hideOne('#formdata');
							rdonly("#dialogForm");
							break;
						case state = 'edit':
							$("#pg_jqGridPager2 table").show();
							hideatdialogForm(true);
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							$('#formdata :input[hideOne]').show();
							rdonly("#dialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$('#formdata :input[hideOne]').show();
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						dialog_authorid.on();
						dialog_deptcodehd.on();
					}
					if(oper!='add'){
						dialog_authorid.check(errorField);
						dialog_deptcodehd.check(errorField);
					}
				},
				beforeClose: function(event, ui){
					/*if(unsaved){
						event.preventDefault();
						bootbox.confirm("Are you sure want to leave without save?", function(result){
							if (result == true) {
								unsaved = false
								$("#dialogForm").dialog('close');
							}
						});
					}*/
				},
				close: function( event, ui ) {
					addmore_jqgrid2.state = false;
					addmore_jqgrid2.more = false;
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					emptyFormdata(errorField,'#formdata2');
					$('.alert').detach();
					$("#formdata a").off();
					dialog_authorid.off();
					dialog_deptcodehd.off();
					$(".noti").empty();
					$("#refresh_jqGrid").click();
					refreshGrid("#jqGrid2",null,"kosongkan");
					refreshGrid("#gridAuthdtl",null,"kosongkan");
					
				},
			  });
			////////////////////////////////////////end dialog///////////////////////////////////////////

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				url:'/util/get_table_default',
				field:'',
				table_name:'material.authorise',
				table_id:'authorid',
				sort_idno:true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				url:'/authorization/form',
				field:'',
				oper:oper,
				table_name:'material.authorise',
				table_id:'authorid',
				saveip:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{label: 'idno', name: 'idno', hidden:true},
					{label: 'Author ID', name: 'authorid', width: 90 ,  classes: 'wrap', canSearch: true,},							
					{label: 'Name', name: 'name', width: 90,  classes: 'wrap' , canSearch: true},	
					{label: 'Password', name: 'password', width: 90 ,  classes: 'wrap' , hidden: true,},
					{label: 'Department Code', name: 'deptcode', width: 90 , classes: 'wrap',},
					{label: 'Active', name: 'active', width: 90 ,hidden:true,},
					{label: 'adddate', name: 'adddate', width: 90 , hidden:true,},
					{label: 'adduser', name: 'adduser', width: 90 , hidden:true,},
					{label: 'upduser', name: 'upduser', width: 90,hidden:true},
					{label: 'upddate', name: 'upddate', width: 90,hidden:true},
					{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', formatter:formatterstatus, unformat:unformatstatus, cellattr: function(rowid, cellvalue)
							{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, 
					},
					{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden: true, classes: 'wrap' },
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){
					urlParam_authdtl.filterVal[1]=selrowData("#jqGrid").authorid;
					urlParam2.filterVal[1]=selrowData("#jqGrid").authorid;
					refreshGrid("#gridAuthdtl",urlParam_authdtl);
				},
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, {'idno':selrowData('#jqGrid').idno});
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

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			populateSelect('#jqGrid','#searchForm');

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['idno','computerid', 'ipaddress', 'adduser', 'adddate', 'upddate', 'upduser', 'recstatus']);


			////////////////////////////////hide at dialogForm///////////////////////////////////////////////////
			function hideatdialogForm(hide,saveallrow){
				if(saveallrow == 'saveallrow'){
					$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#saveDetailLabel").hide();
					$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
				}else if(hide){
					$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
					$("#saveDetailLabel").show();
				}else{
					$("#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll").show();
					$("#saveDetailLabel,#jqGridPager2SaveAll,#jqGrid2_iledit,#jqGridPager2CancelAll").hide();
				}
			}

			/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
			function saveHeader(form,selfoper,saveParam,obj){
				if(obj==null){
					obj={};
				}
				saveParam.oper=selfoper;

				$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {

				},'json').fail(function (data) {
					alert(data.responseText);
				}).done(function (data) {
					unsaved = false;
					hideatdialogForm(false);

					if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
						addmore_jqgrid2.state = true;
						$('#jqGrid2_iladd').click();
					}
					if(selfoper=='add'){
						oper='edit';//sekali dia add terus jadi edit lepas tu
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

		/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
		var cando_filter = [['A','D']];
			if($("#dtl_cando").val() == 'D'){
				cando_filter = [['ACTIVE','DEACTIVE']];
				filterCol_urlParam = ['authdtl.compcode'];
				filterVal_urlParam = ['session.compcode'];
			
		}

		var urlParam2={
			action:'get_table_default',
			url:'/util/get_table_default',
			field:'',
			fixPost:'true',
			table_name:['material.authdtl AS dtl'],
			table_id:'lineno_',
			filterCol:['compcode','authorid'],
			filterVal:['session.compcode','']
		};

		var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
		////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
		$("#jqGrid2").jqGrid({
			datatype: "local",
			editurl: "/authorizationDetail/form",
			colModel: [
			 	{ label: 'idno', name: 'dtl_idno', width: 20, classes: 'wrap', key: true, editable: true, hidden:true},
			 	{ label: 'compcode', name: 'dtl_compcode', width: 20, classes: 'wrap', hidden:true},
				{ label: 'Trantype', name: 'dtl_trantype', width: 200, classes: 'wrap', canSearch: true, editable: true,
					 editable: true,
                         edittype: "select",
                         editoptions: {
                             value: "PR:Purchase Request;PO:Purchase Order"
                         }
				},
				{ label: 'Deptcode', name: 'dtl_deptcode', width: 200, classes: 'wrap', canSearch: true, editable: true,
					editrules:{required: false,custom:true, custom_func:cust_rules},
					edittype:'custom',	editoptions:
						{ custom_element:deptcodeCustomEdit,
						custom_value:galGridCustomValue },
				},
				{ label: 'Record Status', name: 'dtl_recstatus', width: 150, classes: 'wrap', canSearch: true, editable: true,
					 editable: true,
                         edittype: "select",
                         editoptions: {
                             value: "Request:Request;Support:Support;Verify:Verify;Approve:Approve"
                         }
				},
			
				{ label: 'CanDo', name: 'dtl_cando', width: 150, classes: 'wrap', canSearch: true, editable: true,
					 editable: true,
                         edittype: "select",
                         editoptions: {
                             value: "A:Active;D:Deactive"
                         }
				},
				{ label: 'Min Limit', name: 'dtl_minlimit', width: 150, align: 'right', classes: 'wrap', editable:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
				editrules:{required: true},edittype:"text",
						editoptions:{
						readonly: "readonly",
						defaultValue: '1.00',
						maxlength: 100,
						dataInit: function(element) {
							element.style.textAlign = 'right';
							$(element).keypress(function(e){
								if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
									return false;
								 }
							});
						}
					},
				},
			{ label: 'Max Limit', name: 'dtl_maxlimit', width: 150, align: 'right', classes: 'wrap', editable:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
				editrules:{required: true},edittype:"text",
						editoptions:{
						maxlength: 100,
						dataInit: function(element) {
							element.style.textAlign = 'right';
							$(element).keypress(function(e){
								if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
									return false;
								 }
							});
						}
					},
				},
				
			
			],
			autowidth: true,
			shrinkToFit: true,
			multiSort: true,
			viewrecords: true,
			loadonce:false,
			width: 1150,
			height: 200,
			rowNum: 30,
			sortname: 'dtl_idno',
			sortorder: "desc",
			pager: "#jqGridPager2",
			loadComplete: function(){
				if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
				else{
					$('#jqGrid2').jqGrid ('setSelection', "1");
				}

				addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			},
			gridComplete: function(){
				fdl.set_array().reset();
				
			},
			beforeSubmit: function(postdata, rowid){ 
				dialog_deptcodedtl.check(errorField);
		 	}
		});

		////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
		jqgrid_label_align_right("#jqGrid2");
		addParamField('#jqGrid2',false,urlParam2);
		
		//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
		
		var myEditOptions = {
	        keys: true,
	        extraparam:{
			    "_token": $("#_token").val()
	        },
	        oneditfunc: function (rowid) {

	        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

	        	dialog_deptcodedtl.on();
	        	$("#jqGrid2 input[name='dtl_deptcode']").val('ALL');


	        	unsaved = false;
				mycurrency2.array.length = 0;
				Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='dtl_maxlimit']","#jqGrid2 input[name='dtl_minlimit']"]);

				mycurrency2.formatOnBlur();//make field to currency on leave cursor

	        	$("input[name='dtl_maxlimit']").keydown(function(e) {//when click tab at document, auto save
					var code = e.keyCode || e.which;
					if (code == '9')$('#jqGrid2_ilsave').click();
				})
	        },
	        aftersavefunc: function (rowid, response, options) {
	        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
	        	refreshGrid('#jqGrid2',urlParam2,'add');
		    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
	        }, 
	        errorfunc: function(rowid,response){
	        	alert(response.responseText);
	        	refreshGrid('#jqGrid2',urlParam2,'add');
		    	$("#jqGridPager2Delete").show();
	        },
	        beforeSaveRow: function(options, rowid) {

	        	//if(errorField.length>0)return false;

				let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
				let editurl = "/authorizationDetail/form?"+
					$.param({
						action: 'authorizationDetail_save',
						authorid:$('#authorid').val()
					});
				$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
	        },
	        afterrestorefunc : function( response ) {
				hideatdialogForm(false);
		    }
	    };

	    //////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
		$("#jqGrid2").inlineNav('#jqGridPager2',{	
			add:true,
			edit:true,
			cancel: true,
			//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
			restoreAfterSelect: false,
			addParams: { 
				addRowParams: myEditOptions
			},
			editParams: myEditOptions
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2Delete",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-trash",
			title:"Delete Selected Row",
			onClickButton: function(){
				selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
				if(!selRowId){
					bootbox.alert('Please select row');
				}else{
					bootbox.confirm({
					    message: "Are you sure you want to delete this row?",
					    buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
					    },
					    callback: function (result) {
					    	if(result == true){
					    		param={
					    			action: 'authorizationDetail_save',
									idno: $('#dtl_idno').val(),

					    		}
					    		$.post( "/authorizationDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
								}).fail(function(data) {
									//////////////////errorText(dialog,data.responseText);
								}).done(function(data){
									refreshGrid("#jqGrid2",urlParam2);
								});
					    	}else{
	        					$("#jqGridPager2EditAll").show();
					    	}
					    }
					});
				}
			},
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2EditAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-th-list",
			title:"Edit All Row",
			onClickButton: function(){
				mycurrency2.array.length = 0;
				var ids = $("#jqGrid2").jqGrid('getDataIDs');
			    for (var i = 0; i < ids.length; i++) {

			        $("#jqGrid2").jqGrid('editRow',ids[i]);

			        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_dtl_minlimit", "#"+ids[i]+"_dtl_maxlimit"]);
			    }
			    mycurrency2.formatOnBlur();
		    	onall_editfunc();
				hideatdialogForm(true,'saveallrow');
			},
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2SaveAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-download-alt",
			title:"Save All Row",
			onClickButton: function(){
				var ids = $("#jqGrid2").jqGrid('getDataIDs');

				var jqgrid2_data = [];
				mycurrency2.formatOff();
			    for (var i = 0; i < ids.length; i++) {

					var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
			    	var obj = 
			    	{
			    		'idno' : ids[i],
			    		'trantype' : $("#jqGrid2 select#"+ids[i]+"_dtl_trantype option:selected").val(),
			    		'deptcode' : $("#jqGrid2 input#"+ids[i]+"_dtl_deptcode").val(),
			    		'recstatus' : $("#jqGrid2 select#"+ids[i]+"_dtl_recstatus option:selected").val(),
			    		'cando' : $("#jqGrid2 select#"+ids[i]+"_dtl_cando option:selected").val(),
			    		'minlimit' : $("#jqGrid2 input#"+ids[i]+"_dtl_minlimit").val(),
	                    'maxlimit' : $("#jqGrid2 input#"+ids[i]+"_dtl_maxlimit").val()
			    	}

			    	jqgrid2_data.push(obj);
			    }

				var param={
	    			action: 'authorizationDetail_save',
					_token: $("#_token").val()
	    		}

	    		$.post( "/authorizationDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
				}).fail(function(data) {
					//////////////////errorText(dialog,data.responseText);
				}).done(function(data){
					hideatdialogForm(false);
					refreshGrid("#jqGrid2",urlParam2);
				});
			},	
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2CancelAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-remove-circle",
			title:"Cancel",
			onClickButton: function(){
				hideatdialogForm(false);
				refreshGrid("#jqGrid2",urlParam2);
			},	
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "saveHeaderLabel",
			caption:"Header",cursor: "pointer",position: "last", 
			buttonicon:"",
			title:"Header"
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "saveDetailLabel",
			caption:"Detail",cursor: "pointer",position: "last", 
			buttonicon:"",
			title:"Detail"
		});

		//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
		function showdetail(cellvalue, options, rowObject){
			var field, table, case_;
			switch(options.colModel.name){
				case 'dtl_deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			}
			var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
			fdl.get_array('authorization',options,param,case_,cellvalue);
			return cellvalue;
		}

		///////////////////////////////////////cust_rules//////////////////////////////////////////////
		function cust_rules(value,name){
			var temp;
			switch(name){
				case 'Deptcode':temp=$('#dtl_deptcode');break;
				case 'Deptcode':temp=$('#d_deptcode');break;
			
			}
			return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
		}

		/////////////////////////////////////////////custom input////////////////////////////////////////////
		function deptcodeCustomEdit(val, opt) {
		val = (val=="undefined")? "" : val;
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="dtl_deptcode" type="text" class="form-control input-sm"  value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
		}

		function deptcodedtlCustomEdit(val,opt){
		val = (val=="undefined")? "" : val;	
		return $('<div class="input-group"><input jqgrid="gridAuthdtl" optid="'+opt.id+'" id="'+opt.id+'" name="d_deptcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
		}

		function galGridCustomValue (elem, operation, value){
			if(operation == 'get') {
				return $(elem).find("input").val();
			} 
			else if(operation == 'set') {
				$('input',elem).val(value);
			}
		}

		

		//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function () {
		mycurrency.formatOff();
		// mycurrency.check0value(errorField);
		unsaved = false;
		dialog_authorid.off();
		dialog_deptcodehd.off();
		//radbuts.check();
		errorField.length = 0;
	if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			unsaved = false;
		} else {
			mycurrency.formatOn();
			dialog_authorid.on();
			dialog_deptcodehd.on();
		}
	});


	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function () {
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		dialog_authorid.on();
		dialog_deptcodehd.on();

		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
	});

	////////////////////////////////////////////////////ordialog////////////////////////////////////////

	var dialog_authorid = new ordialog(
		'authorid','sysdb.users','#authorid',errorField,
		{	colModel:[
				{label:'Username',name:'username',width:100,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Name',name:'name',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Password',name:'password',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Dept Code',name:'deptcode',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_authorid.gridname);
					$("#name").val(data['name']);
					$("#password").val(data['password']).attr('type','password');
					$("#deptcode").val(data['deptcode']);
					$('#deptcode').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#deptcode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}	
			},{
			title:"Select Author ID",
			open: function(){
					dialog_authorid.urlParam.filterCol=['recstatus'],
					dialog_authorid.urlParam.filterVal=['A']
				}
			},'urlParam', 'radio', 'tab'
		);
	dialog_authorid.makedialog();

	var dialog_deptcodehd = new ordialog(
		'deptcode','sysdb.department','#deptcode',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Unit',name:'sector'},
			],
			urlParam: {
				filterCol:['storedept', 'recstatus','compcode','sector'],
				filterVal:['1', 'A', 'session.compcode', 'session.unit']
			},
			ondblClickRow:function(){
				//$('#recstatus').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#recstatus').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}	
		},{
			title:"Select Department",
			open: function(){
				dialog_deptcodehd.urlParam.filterCol=['storedept', 'recstatus','compcode','sector'];
				dialog_deptcodehd.urlParam.filterVal=['1', 'A', 'session.compcode', 'session.unit'];
			}
		},'urlParam','radio','tab');
	dialog_deptcodehd.makedialog();

	var dialog_deptcodedtl = new ordialog(
		'dtl_deptcode','sysdb.department',"#jqGrid2 input[name='dtl_deptcode']",errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Unit',name:'sector'},
			],
			urlParam: {
				filterCol:['storedept', 'recstatus','compcode','sector'],
				filterVal:['1', 'A', 'session.compcode', 'session.unit']
			},
			ondblClickRow:function(){
				//$('#delordhd_credcode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;

				let str = $(obj.textfield).val() ? $(obj.textfield).val() : '';
				if(str.toUpperCase() == 'ALL' && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
					obj.ontabbing = false;
				}

				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					//$('#delordhd_credcode').focus();
				}
			}
		},{
			title:"Select Department",
			open: function(){
				dialog_deptcodedtl.urlParam.filterCol=['storedept', 'recstatus','compcode','sector'];
				dialog_deptcodedtl.urlParam.filterVal=['1', 'A', 'session.compcode', 'session.unit'];
			}
		},'none','radio','tab'
	);
	dialog_deptcodedtl.makedialog();

	function onall_editfunc(){
		dialog_deptcodedtl.on();
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////Auth Detail////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	var dialog_deptcodeD = new ordialog(
		'd_deptcode','sysdb.department','#d_deptcode',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Unit',name:'sector'},
			],
			urlParam: {
				filterCol:['storedept', 'recstatus','compcode','sector'],
				filterVal:['1', 'A', 'session.compcode', 'session.unit']
			},
			ondblClickRow:function(){
				$('#dtl_recstatus').focus();
			},
			gridComplete: function(obj){
				let str = $(obj.textfield).val() ? $(obj.textfield).val() : '';
				if(str.toUpperCase() == 'ALL' && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
					obj.ontabbing = false;
				}

				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}
			}
		},{
			title:"Select Department",
			open: function(){
				dialog_deptcodeD.urlParam.filterCol=['storedept', 'recstatus','compcode','sector'];
				dialog_deptcodeD.urlParam.filterVal=['1', 'A', 'session.compcode', 'session.unit'];
			}
		},'none','radio','tab'
	);
	dialog_deptcodeD.makedialog();
	

	var buttItem1=[{
		text: "Save",click: function() {
			mycurrency.formatOff();
			// mycurrency.check0value(errorField);
			if( $('#FAuthdtl').isValid({requiredFields: ''}, {}, true) ) {
				saveFormdata("#gridAuthdtl","#Authdtl","#FAuthdtl",oper_authdtl,saveParam_authdtl,urlParam_authdtl);
			}else{
				mycurrency.formatOn();
			}
		}
	},{
		text: "Cancel",click: function() {
			$(this).dialog('close');
		}
	}];

	var oper_authdtl;
	$("#Authdtl")
	  .dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			switch(oper_authdtl) {
				case state = 'add':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#FAuthdtl');
					hideOne('#FAuthdtl');
					rdonly('#FAuthdtl');
					$(this).dialog("option", "buttons",buttItem1);
					break;
				case state = 'edit':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#FAuthdtl');
					frozeOnEdit("#Authdtl");
					$('#FAuthdtl :input[hideOne]').show();
					rdonly('#FAuthdtl');
					$(this).dialog("option", "buttons",buttItem1);
					break;
				case state = 'view':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "View" );
					disableForm('#FAuthdtl');
					$('#FAuthdtl :input[hideOne]').show();
					$(this).dialog("option", "buttons",butt2);
					break;
			}
			
			if(oper_authdtl == 'edit'){
				dialog_deptcodeD.on();
			}
			
			if(oper_authdtl!='add'){
				// dialog_deptcodeD.check(errorField);

			}
			if (oper_authdtl != 'view') {
				$("#d_authorid").val(selrowData('#jqGrid').authorid);
				$("input[name='dtl_authorid']").val(selrowData('#jqGrid').authorid);
				dialog_deptcodeD.on();
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#FAuthdtl');
			$('#FAuthdtl .alert').detach();
			dialog_deptcodeD.off();
			if(oper=='view'){
				$(this).dialog("option", "buttons",buttItem1);
			}
		},
		buttons :buttItem1,
	  });
	
	/////////////////////parameter for jqgrid url SVC/////////////////////////////////////////////////
	var urlParam_authdtl={
		action:'get_table_default',
			url:'/util/get_table_default',
			field:'',
			fixPost:'true',
			table_name:['material.authdtl AS dtl'],
			table_id:'d_lineno_',
			filterCol:['compcode','authorid'],
			filterVal:['session.compcode','']
	}

	var saveParam_authdtl={
		action:'save_table_default',
			url:'authorizationDetail/form',
			field:'',
			oper:oper_authdtl,
			table_name:'material.authdtl',
			table_id:'d_idno',
			saveip:'true'
	};

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$("#gridAuthdtl").jqGrid({
		datatype: "local",
		 colModel: [
			 	//{ label: 'compcode', name: 'd_compcode', width: 20, classes: 'wrap', hidden:true},
				{ label: 'dtl_authorid', name: 'dtl_authorid', width: 20, classes: 'wrap', hidden:true},
				{ label: 'idno', name: 'dtl_idno', width: 20, classes: 'wrap', hidden:true, editable:true},
				{ label: 'Trantype', name: 'dtl_trantype', width: 200, classes: 'wrap', canSearch: true, editable: true,
					 editable: true,
                         edittype: "select",
                         editoptions: {
                             value: "PR:Purchase Request;PO:Purchase Order"
                         }
				},
				{ label: 'Deptcode', name: 'dtl_deptcode', width: 200, classes: 'wrap', canSearch: true, editable: true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
					edittype:'custom',	editoptions:
						{ custom_element:deptcodedtlCustomEdit,
						custom_value:galGridCustomValue },
				},
				{ label: 'Record Status', name: 'dtl_recstatus', width: 150, classes: 'wrap', canSearch: true, editable: true,
					 editable: true,
                         edittype: "select",
                         editoptions: {
                             value: "Request:Request;Support:Support;Verify:Verify;Approve:Approve"
                         }
				},
				{ label: 'CanDo', name: 'dtl_cando', width: 150, classes: 'wrap', canSearch: true, editable: true,
					 editable: true,
                         edittype: "select",
                         editoptions: {
                             value: "A:Active;D:Deactive"
                         }
				},
			
				{ label: 'Min Limit', name: 'dtl_minlimit', width: 200, classes: 'wrap', editable: true,
					edittype:"text",
				},
				{ label: 'Max Limit', name: 'dtl_maxlimit', width: 200, classes: 'wrap', editable: true,
					edittype:"text",
				},
				{ label: ' ', name: 'Checkbox',sortable:false, width: 80,align: "center", formatter: formatterCheckbox },
		],
		viewrecords: true,
		//shrinkToFit: true,
		autowidth:true,
        multiSort: true,
		loadonce:false,
		width: 900,
		height: 200,
		rowNum: 30,
		hidegrid: false,
		pager: "#jqGridPager3",
		onPaging: function(pgButton){
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$('#d_idno').val(selrowData('#gridAuthdtl').dtl_idno);
			$('#d_authorid').val(selrowData('#gridAuthdtl').dtl_authorid);
			$("#jqGridPager3 td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if(oper == 'add'){
				$("#gridAuthdtl").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#gridAuthdtl #'+$("#gridAuthdtl").jqGrid ('getGridParam', 'selrow')).focus();

			/////////////////////////////// reccount ////////////////////////////
			
			if($("#gridAuthdtl").getGridParam("reccount") >= 1){
				$("#jqGridPagerglyphicon-trash").hide();
			} 

			if($("#gridAuthdtl").getGridParam("reccount") < 1){
				$("#jqGridPagerglyphicon-trash").show()
			}

			cbselect.checkbox_function_on();
			cbselect.refresh_seltbl();
		},
		onSelectRow:function(rowid, selected){
			/*if(rowid != null) {
				rowData = $('#gridAuthdtl').jqGrid ('getRowData', rowid);
				//console.log(rowData.svc_billtype);
				urlParam_suppbonus.filterVal[0]=selrowData("#gridAuthdtl").si_itemcode; 

				$("#Fsuppbonus :input[name*='sb_suppcode']").val(selrowData("#gridAuthdtl").si_suppcode);
				$("#Fsuppbonus :input[name*='sb_pricecode']").val(selrowData("#gridAuthdtl").si_pricecode);
				$("#Fsuppbonus :input[name*='sb_itemcode']").val(selrowData("#gridAuthdtl").si_itemcode);
				$("#Fsuppbonus :input[name*='sb_uomcode']").val(selrowData("#gridAuthdtl").si_uomcode);
				$("#Fsuppbonus :input[name*='sb_purqty']").val(selrowData("#gridAuthdtl").si_purqty);
				refreshGrid('#gridSuppBonus',urlParam_suppbonus);
				$("#pg_jqGridPager3 table").show();
			}*/
		},
		
	});

	function formatterCheckbox(cellvalue, options, rowObject){
		let dtl_idno = cbselect.idno;
		let dtl_cando = cbselect.cando;
		if(options.gid == "jqGrid" && rowObject[dtl_cando] == cando_filter[0][0]){
			return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[dtl_idno]+"' data-idno='"+rowObject[dtl_idno]+"' data-rowid='"+options.rowId+"'>";
		}else if(options.gid != "jqGrid" && rowObject[dtl_cando] == cando_filter[0][0]){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[dtl_idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}else{
			return ' ';
		}
	}
	
	$("#gridAuthdtl").jqGrid('navGrid','#jqGridPager3',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#gridAuthdtl",urlParam_authdtl);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-trash", 
		id:"jqGridPager3glyphicon-trash",
		onClickButton: function(){
			oper_authdtl='del';
			var selRowId = $("#gridAuthdtl").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#FAuthdtl');
			}else{
				emptyFormdata(errorField,'#FAuthdtl');
				saveFormdata("#gridAuthdtl","#Authdtl","#FAuthdtl",'del',saveParam_authdtl,urlParam_authdtl,{'idno':selrowData('#gridAuthdtl').dtl_idno});
			}
		}, 
		position: "first", 
		title:"Delete Selected Row", 
		cursor: "pointer"
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-info-sign", 
		onClickButton: function(){
			oper_authdtl='view';
			selRowId = $("#gridAuthdtl").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#gridAuthdtl","#Authdtl","#FAuthdtl",selRowId,'view');
		}, 
		position: "first", 
		title:"View Selected Row", 
		cursor: "pointer"
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-edit", 
		onClickButton: function(){
			oper_authdtl='edit';
			var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#FAuthdtl');
			}else{
				selRowId2 = $("#gridAuthdtl").jqGrid ('getGridParam', 'selrow');
				populateFormdata("#gridAuthdtl","#Authdtl","#FAuthdtl",selRowId2,'edit');
				
				var recstatusvalue = selrowData('#gridAuthdtl').dtl_cando;

				if(recstatusvalue == 'A'){
					$("#FAuthdtl [name='dtl_cando']").prop('disabled', true);
				}else{
					$("#FAuthdtl [name='dtl_cando']").prop('disabled', false);
				}
			}
		}, 
		position: "first", 
		title:"Edit Selected Row", 
		cursor: "pointer"
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-plus", 
		onClickButton: function(){
			oper_authdtl='add';
			var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#FAuthdtl');
			}else{
				$( "#Authdtl" ).dialog( "open" );
			}
			//$('#FAuthdtl :input[name=d_lineno_]').hide();
			//$("#Fsuppitems :input[name*='SuppCode']").val(selrowData('#jqGrid').SuppCode);
		}, 
		position: "first", 
		title:"Add New Row", 
		cursor: "pointer"
	});


	$("#gridAuthdtl_panel").on("show.bs.collapse", function(){
		$("#gridAuthdtl").jqGrid ('setGridWidth', Math.floor($("#gridAuthdtl_c")[0].offsetWidth-$("#gridAuthdtl_c")[0].offsetLeft-28));
	});

	addParamField('#gridAuthdtl',false,urlParam_authdtl);
	addParamField('#gridAuthdtl',false,saveParam_authdtl,["d_idno", "d_adduser", "d_adddate", "d_upduser", "d_upddate", "d_computerid", 'd_ipaddress', 'd_recstatus']);

	populateSelect('#gridAuthdtl','#searchForm2');
	searchClick('#gridAuthdtl','#searchForm2',urlParam_authdtl);



});
		