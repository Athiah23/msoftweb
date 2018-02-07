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
			var Type = $('#Type').val();

			if(Type =='DOC') {
	             $('#TSBtn').show();
	             $('#ALBtn').show();
	             $('#PHBtn').show();
	           
	        }else {
	             $('#TSBtn').hide();
	             $('#ALBtn').show();
	             $('#PHBtn').show();
            }

			////////////////////////////////////start dialog///////////////////////////////////////
			var tsbtn=[{
				text: "Save",click: function() {
					if( $('#tsformdata').isValid({requiredFields: ''}, conf, true) ) {
						 saveFormdata("#gridtime","#tsdialogForm","#tsformdata",oper,saveParamtime,urlParamtime,null,{resourcecode:selrowData('#jqGrid').resourcecode});
						
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
					
				}
			}];

			var phbtn=[{
				text: "Save",click: function() {
					if( $('#phformdata').isValid({requiredFields: ''}, conf, true) ) {
						 saveFormdata("#gridph","#phdialogForm","#phformdata",oper,saveParamph,urlParamph,null,{resourcecode:selrowData('#jqGrid').resourcecode});
						
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
					
				}
			}];
            
            var albtn=[{
				text: "Save",click: function() {
					if( $('#alformdata').isValid({requiredFields: ''}, conf, true) ) {
						 saveFormdata("#gridleave","#aldialogForm","#alformdata",oper,saveParamleave,urlParamleave,null,{resourcecode:selrowData('#jqGrid').resourcecode});
						
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
					
				}
			}];
            
             var rscbtn=[{
				text: "Save",click: function() {
					if( $('#resourceformdata').isValid({requiredFields: ''}, conf, true) ) {
						 saveFormdata("#jqGrid","#resourceAddform","#resourceformdata",oper,saveParam,urlParam,null);
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
					
				}
			}];

			var btnclose=[{
				text: "Close",click: function() {
					$(this).dialog('close');
				}
			}];

			  $("#TSBtn").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	if(!selRowId){
            		alert('Please select doctor');
            	}else{
	            	$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	            	$("span[name='description']").text(selrowData('#jqGrid').description);
					
            		// urlParamtime.filterVal[0] = selrowData('#jqGrid').resourcecode;
	            	
					$("#TSBox").dialog("open");
            	}
            });

			   $("#PHBtn").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	if(!selRowId){
            		alert('Please select doctor');
            	}else{
	            	$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	            	$("span[name='description']").text(selrowData('#jqGrid').description);
					
            		// urlParamph.filterVal[] = selrowData('#jqGrid').idno;
					$("#PHBox").dialog("open");
            	}
            });
			     $("#ALBtn").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	if(!selRowId){
            		alert('Please select doctor');
            	}else{
	            	$("span[name='resourcecode']").text(selrowData('#jqGrid').resourcecode);
	            	$("span[name='description']").text(selrowData('#jqGrid').description);
					
            		// urlParamph.filterVal[] = selrowData('#jqGrid').idno;
					$("#ALBox").dialog("open");
            	}
            });

			var oper;
			$("#tsdialogForm")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#tsformdata');
							rdonly("#tsdialogForm");
							hideOne('#tsformdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#tsformdata');
							frozeOnEdit("#tsdialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#tsformdata');
							$(this).dialog("option", "buttons",btnclose);
							break;
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#tsformdata');
					$('#tsformdata .alert').detach();
					$("#tsformdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",tsbtn);
					}
				},
				buttons :tsbtn,
			  });

			  var oper;
			$("#phdialogForm")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#phformdata');
							rdonly("#phdialogForm");
							hideOne('#phformdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#phformdata');
							frozeOnEdit("#phdialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#phformdata');
							$(this).dialog("option", "buttons",btnclose);
							break;
						
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#phformdata');
					$('#phformdata .alert').detach();
					$("#phformdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",phbtn);
					}
				},
				buttons :phbtn,
			  });
               


			  var oper;
			$("#aldialogForm")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#alformdata');
							rdonly("#aldialogForm");
							hideOne('#alformdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#alformdata');
							frozeOnEdit("#aldialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#alformdata');
							$(this).dialog("option", "buttons",btnclose);
							break;
						
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#alformdata');
					$('#alformdata .alert').detach();
					$("#alformdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",albtn);
					}
				},
				buttons :albtn,
			  });

			  var oper;
			$("#resourceAddform")
			  .dialog({ 
				width: 5/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#resourceformdata');
							rdonly("#resourceAddform");
							hideOne('#resourceformdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#resourceformdata');
							frozeOnEdit("#resourceAddform");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#resourceformdata');
							$(this).dialog("option", "buttons",btnclose);
							break;
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#resourceformdata');
					$('#resourceformdata .alert').detach();
					$("#resourceformdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",rscbtn);
					}
				},
				buttons :rscbtn,
			  });

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			
			var urlParam={
				action:'get_table_default',
				url: '/util/get_table_default',
				field:['resourcecode','description','TYPE'],
				table_name:'hisdb.apptresrc',
				table_id:'idno',
				sort_idno:true,
				filterCol:['TYPE'],
				filterVal:[ $('#Type').val()]
			}
			if(Type =='DOC'){
				urlParam.url = "/doctor_maintenance/table";
			}
            var saveParam={
				action:'save_table_default',
				url:"/doctor_maintenance/form",
				field:['resourcecode','description','TYPE'],
				oper:oper,
				table_name:'hisdb.apptresrc',
				table_id:'resourcecode'
				
			};
			/////////////////////parameter for saving url////////////////////////////////////////////////
			
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
                    { label: 'idno', name: 'idno', hidden: true},
					{ label: 'Resource code', name: 'resourcecode', width: 40, classes: 'wrap', canSearch: true, checked:true},						
				    { label: 'Description', name: 'description', width: 40, classes: 'wrap', canSearch: true},
				    { label: 'Type', name: 'TYPE', width: 40, classes: 'wrap', hidden:true},
				    { label: 'session', name: 'countsession', width: 40, classes: 'wrap', hidden:true},
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				height: 250,
				//width: 100,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){

					 // $('#doctorcode').val(selrowData('#jqGrid').resourcecode);
					// $('#description').val(selrowData('#jqGrid').description);

					$('#doctorcode').val(selrowData('#jqGrid').resourcecode);
					urlParamtime.filterVal[0] = selrowData('#jqGrid').resourcecode;
					urlParamleave.filterVal[0] = selrowData('#jqGrid').resourcecode;

					// urlParamleave.filterVal[1] = selrowData('#jqGrid').resourcecode;

				},
				ondblClickRow: function(rowid, iRow, iCol, e){

					if(Type !='DOC'){
						$("#jqGridPager td[title='Edit Selected Row']").click();
					}

				},
				gridComplete: function(){ 
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();

					if(Type =='DOC'){
						$("#pg_jqGridPager td[title='Add New Row']").hide();
						$("#pg_jqGridPager td[title='Edit Selected Row']").hide();
					}
				},
			});

			
             

			$("#jqGrid").jqGrid('navGrid','#jqGridPager',
				{	
					edit:false,view:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						refreshGrid("#jqGrid",urlParam);
					},
			
			// });

			}).jqGrid('navButtonAdd', "#jqGridPager", {
				caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
				buttonicon: "glyphicon glyphicon-edit",
				title: "Edit Selected Row",
				onClickButton: function () {
					oper = 'edit';
					selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
					populateFormdata("#jqGrid", "#resourceAddform", "#resourceformdata", selRowId, 'edit');
					refreshGrid("#jqGrid", urlParam);
				},

			}).jqGrid('navButtonAdd', "#jqGridPager", {
			    caption: "", cursor: "pointer", position: "first",
			    buttonicon: "glyphicon glyphicon-plus",
			    id: 'glyphicon-plus',
			    title: "Add New Row",
			    onClickButton: function () {
					oper = 'add';
					$("#resourceAddform").dialog("open");
				},
			});


			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////

			$("#TSBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 6/10 * $(window).width(),
				open: function(){
					if(parseInt(selrowData('#jqGrid').countsession)==0){
						['MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY'].forEach(function(elem,id) {
							$("#gridtime").jqGrid('addRowData', id,
								{
									idno:id,
									doctorcode:$('#doctorcode').val(),
									days:elem,
									timefr1:'',
									timeto1:'',
									timefr2:'',
									timeto2:'',
								}
							);
						});
					}else{
						addParamField("#gridtime",true,urlParamtime);
					}
					$("#gridtime").jqGrid ('setGridWidth', Math.floor($("#gridtime_c")[0].offsetWidth-$("#gridtime_c")[0].offsetLeft));
				},
				close:function(){
					$("#gridtime").jqGrid("clearGridData", true);
				}
            });

          

            var urlParamtime = {
				action:'get_table_default',
				url: '/util/get_table_default',
				field:"['doctorcode','days','timefr1','timeto1','timefr2','timeto2']",
				table_name:'hisdb.apptsession',
				table_id:'idno',
				filterCol:['doctorcode'],
				filterVal:[$('#resourcecode').val()],
				// filterVal:[''],
			}

			var saveParamtime={
				action:'save_table_default',
				url:"/doctor_maintenance/form",
				field:['doctorcode','days','timefr1','timeto1','timefr2','timeto2'],
				oper:oper,
				table_name:'hisdb.apptsession',
				table_id:'idno',
				noduplicate:true,
				
			};

			function timefr1CustomEdit(val,opt){
				val = (val=="undefined"||val=="")? "08:00" : val;	
				return $('<input type="time" class="form-control input-sm" value="'+val+'" >');
			}

			function timeto1CustomEdit(val,opt){  	
				val = (val=="undefined"||val=="")? "12:00" : val;	
				return $('<input type="time" class="form-control input-sm" value="'+val+'" >');
			}

			function timefr2CustomEdit(val,opt){  	
				val = (val=="undefined"||val=="")? "13:00" : val;	
				return $('<input type="time" class="form-control input-sm" value="'+val+'" >');
			}

			function timeto2CustomEdit(val,opt){  	
				val = (val=="undefined"||val=="")? "18:00" : val;	
				return $('<input type="time" class="form-control input-sm" value="'+val+'" >');
			}

			function galGridCustomValue (elem, operation, value){
				if(operation == 'get') {
					return $(elem).find("input").val();
				} 
				else if(operation == 'set') {
					$('input',elem).val(value);
				}
			}

            $("#gridtime").jqGrid({
				datatype: "local",
				colModel: [
					{label: 'idno', name: 'idno', classes: 'wrap',hidden:true},
					{label: 'Resource Code', name: 'doctorcode', classes: 'wrap',hidden:true},
					{label: 'Day', name: 'days', classes: 'wrap'},
					{label: 'Start Time', name: 'timefr1', classes: 'wrap', editable:true,
						edittype:'custom',	editoptions:
						    {  
						    	custom_element:timefr1CustomEdit,
						        custom_value:galGridCustomValue 	
						    },
					},
					{label: 'End Time', name: 'timeto1', classes: 'wrap', editable:true,
						edittype:'custom',	editoptions:
						    {  
						        custom_element:timeto1CustomEdit,
						        custom_value:galGridCustomValue 	
						    },
					},
					{label: 'Start Time', name: 'timefr2', classes: 'wrap', editable:true,
						edittype:'custom',	editoptions:
						    {  
						        custom_element:timefr2CustomEdit,
						        custom_value:galGridCustomValue 	
						    },
					},
					{label: 'End Time', name: 'timeto2', classes: 'wrap', editable:true,
						edittype:'custom',	editoptions:
						    {  
						        custom_element:timeto2CustomEdit,
						        custom_value:galGridCustomValue 	
						    },
					}
				],
					
				autowidth:true,
				viewrecords: true,
				loadonce:false,
				width: 200,
				height: 380,
				rowNum: 300,
				sortname:'idno',
		        sortorder:'asc',
				pager: "#gridtimepager",
				onSelectRow:function(rowid, selected){
					$('#resourcecode').val(selrowData('#jqGrid').resourcecode);
					$('#doctorcode').val(selrowData('#jqGrid').doctorcode);
					$('#description').val(selrowData('#jqGrid').description);
					
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){ 
					var $this = $(this), rows = this.rows, l = rows.length, i, row;
				    for (i = 0; i < l; i++) {
				        row = rows[i];
				        if ($.inArray('jqgrow', row.className.split(' ')) >= 0) {
				            $this.jqGrid('editRow', row.id, true);
				        }
				    }
				},
			});

			$("#gridtime").jqGrid('setGroupHeaders', {
	            useColSpanStyle: true, 
	            groupHeaders:[
			        {startColumnName: 'timefr1', numberOfColumns: 2, titleText: 'Morning Session'},
			        {startColumnName: 'timefr2', numberOfColumns: 4, titleText: 'Evening Session'}
	            ]	
            });

			$("#gridtime").jqGrid('navGrid', '#gridtimepager', {
				view: false, edit: false, add: false, del: false, search: false, refresh:false,
				beforeRefresh: function () {
					refreshGrid("#gridtime", urlParamtime, oper);
				},
			});


			// gridph //
			$("#PHBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 8/10 * $(window).width(),
				open: function(){
					addParamField("#gridph",true,urlParamph);
					$("#gridph").jqGrid ('setGridWidth', Math.floor($("#gridph_c")[0].offsetWidth-$("#gridph_c")[0].offsetLeft));

				}, 

            });

            var urlParamph = {
				action:'get_table_default',
				url: '/util/get_table_default',
				field:"['YEAR','datefr','dateto','remark']",
				table_name:'hisdb.apptph',
				table_id:'idno',
				// filterCol:['YEAR'],
				// filterVal:[ ''],
			}

			var saveParamph={
				action:'save_table_default',
				url:"/doctor_maintenance/form",
				field:['YEAR','datefr','dateto','remark'],
				oper:oper,
				table_name:'hisdb.apptph',
				table_id:'idno'
				
			};

            $("#gridph").jqGrid({
				datatype: "local",
				colModel: [
					{label: 'idno', name: 'idno', classes: 'wrap',hidden:true},
					{label: 'Year', name: 'YEAR', classes: 'wrap',hidden:true,canSearch:true,checked:true},
					{label: 'From', name: 'datefr', classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter },
					{label: 'To', name: 'dateto', classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter },
					{label: 'Remark', name: 'remark', classes: 'wrap'},
				],
					
				autowidth:true,
				viewrecords: true,
				loadonce:false,
				width: 200,
				height: 200,
				rowNum: 300,
				sortname:'idno',
		        sortorder:'desc',
				pager: "#gridphpager",
				onSelectRow:function(rowid, selected){
					$('#resourcecode').val(selrowData('#jqGrid').resourcecode);
					$('#description').val(selrowData('#jqGrid').description);

					
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){ 
					if(oper == 'add'){
						$("#gridph").setSelection($("#gridph").getDataIDs()[0]);
					}

					$('#'+$("#gridph").jqGrid ('getGridParam', 'selrow')).focus();
				},
			});

			

		$("#gridph").jqGrid('navGrid', '#gridphpager', {
				view: false, edit: false, add: false, del: false, search: false,
				beforeRefresh: function () {
					refreshGrid("#gridph", urlParamph, oper);
				},
			}).jqGrid('navButtonAdd', "#gridphpager", {
					caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
					buttonicon: "glyphicon glyphicon-edit",
					title: "Edit Selected Row",
					onClickButton: function () {
						oper = 'edit';
						selRowId = $("#gridph").jqGrid('getGridParam', 'selrow');
						populateFormdata("#gridph", "#phdialogForm", "#phformdata", selRowId, 'edit');
						$("#phformdata :input[name='YEAR']").val($("#YEAR").val());
						refreshGrid("#gridph", urlParamph);
					},

			}).jqGrid('navButtonAdd', "#gridphpager", {
					caption: "", cursor: "pointer", position: "first",
					buttonicon: "glyphicon glyphicon-plus",
					id: 'glyphicon-plus',
					title: "Add New Row",
					onClickButton: function () {
						oper = 'add';
						$("#phdialogForm").dialog("open");
						$("#phformdata :input[name='YEAR']").val($("#YEAR").val());
					},
			});

           
            // gridleave //
			$("#ALBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 8/10 * $(window).width(),
				open: function(){
					addParamField("#gridleave",true,urlParamleave);
					$("#gridleave").jqGrid ('setGridWidth', Math.floor($("#gridleave_c")[0].offsetWidth-$("#gridleave_c")[0].offsetLeft));

				}, 

            });

            var urlParamleave = {
				action:'get_table_default',
				url: '/util/get_table_default',
				field:"['YEAR','datefr','dateto','remark','resourcecode']",
				table_name:'hisdb.apptleave',
				table_id:'idno',
				filterCol:['resourcecode'],
				filterVal:[''],
			}

			var saveParamleave={
				action:'save_table_default',
				url:"/doctor_maintenance/form",
				field:['YEAR','datefr','dateto','remark','resourcecode'],
				oper:oper,
				table_name:'hisdb.apptleave',
				table_id:'idno'
				
			};

            $("#gridleave").jqGrid({
				datatype: "local",
				colModel: [
					{label: 'idno', name: 'idno', classes: 'wrap',hidden:true},
					{label: 'Year', name: 'YEAR', classes: 'wrap',hidden:true},
					{label: 'Date From', name: 'datefr', classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
					{label: 'Date To', name: 'dateto', classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
					{label: 'Remark', name: 'remark', classes: 'wrap'},
					{label: 'resourcecode', name: 'resourcecode', classes: 'wrap',hidden:true},
					
					],
					
				autowidth:true,
				viewrecords: true,
				loadonce:false,
				width: 200,
				height: 200,
				rowNum: 300,
				sortname:'idno',
		        sortorder:'desc',
				pager: "#gridleavepager",
				onSelectRow:function(rowid, selected){
					$('#resourcecode').val(selrowData('#jqGrid').resourcecode);
					$('#description').val(selrowData('#jqGrid').description);

					
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){ 
					if(oper == 'add'){
						$("#gridleave").setSelection($("#gridleave").getDataIDs()[0]);
					}

					$('#'+$("#gridleave").jqGrid ('getGridParam', 'selrow')).focus();
				},
			});

			

			$("#gridleave").jqGrid('navGrid', '#gridleavepager', {
					view: false, edit: false, add: false, del: false, search: false,
					beforeRefresh: function () {
						refreshGrid("#gridleave", urlParamleave, oper);
					},
				}).jqGrid('navButtonAdd', "#gridleavepager", {
						caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
						buttonicon: "glyphicon glyphicon-edit",
						title: "Edit Selected Row",
						onClickButton: function () {
							oper = 'edit';
							selRowId = $("#gridleave").jqGrid('getGridParam', 'selrow');
							populateFormdata("#gridleave", "#aldialogForm", "#alformdata", selRowId, 'edit');
							$("#alformdata :input[name='YEAR']").val($("#YEAR").val());
							refreshGrid("#gridleave", urlParamleave);
						},

				}).jqGrid('navButtonAdd', "#gridleavepager", {
						caption: "", cursor: "pointer", position: "first",
						buttonicon: "glyphicon glyphicon-plus",
						id: 'glyphicon-plus',
						title: "Add New Row",
						onClickButton: function () {
							oper = 'add';
							$("#aldialogForm").dialog("open");
							$("#alformdata :input[name='YEAR']").val($("#YEAR").val());
						},
				});
   
 
			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			toogleSearch('#sbut1','#searchForm1','on');
			populateSelect('#gridph','#searchForm1');
			searchClick('#gridph','#searchForm1',urlParamph);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#gridtime',true,urlParamtime);
			addParamField('#gridph',true,urlParamph);
			addParamField('#gridleave',true,urlParamleave);
				///////////////////////////////////utk dropdown tran dept/////////////////////////////////////////
	 

			
		});
		