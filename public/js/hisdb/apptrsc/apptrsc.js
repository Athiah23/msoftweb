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
	var d = new Date();

	var Class2 = $('#Class2').val();

	if(Class2 == 'DOC'){
		$('#Scol').val('Doctor')
	}else{
		$('#Scol').val('Resource')
	}

	var dialog_name = new ordialog(
		'resourcecode', ['hisdb.apptresrc AS a', 'hisdb.doctor AS d'], "input[name='resourcecode']", errorField,
        {
            colModel: [
                { label: 'Resource Code', name: 'a_resourcecode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'a_description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Interval Time', name: 'd_intervaltime', width: 400, classes: 'pointer', hidden:true},
            ],
            onSelectRow: function (rowid, selected) {
				let data = selrowData('#' + dialog_name.gridname);

				var session_param ={
					action:"get_table_default",
					url:'/util/get_table_default',
					field:'*',
					table_name:'hisdb.apptsession',
					table_id:'idno',
					filterCol:['doctorcode','status'],
					filterVal:[data['a_resourcecode'],'True']
				};

				refreshGrid("#grid_session",session_param);
            },
            ondblClickRow_off:'off',
        },{
            title: "Select Doctor",
            open: function () {

				$("#"+dialog_name.gridname).jqGrid ('setGridHeight',100);

				$("#grid_session").jqGrid ('setGridWidth', Math.floor($("#grid_session_c")[0].offsetWidth-$("#grid_session_c")[0].offsetLeft));

                var type = $('#Class2').val();
				dialog_name.urlParam.join_type = ['LEFT JOIN'];
				dialog_name.urlParam.join_onCol = ['a.resourcecode'];
				dialog_name.urlParam.join_onVal = ['d.doctorcode'];
				dialog_name.urlParam.join_filterCol = [['a.compcode on =']];
				dialog_name.urlParam.join_filterVal = [['d.compcode']];
				dialog_name.urlParam.fixPost='true';
				dialog_name.urlParam.filterCol = ['a.TYPE'];
				dialog_name.urlParam.filterVal = [type];
			},
			close: function () {
				
			}
        }, 'urlParam'
    );
	dialog_name.makedialog(true);

	$("#"+dialog_name.dialogname+" .panel-body").append(`
		<button type='button' id='selecting_doctor' class="btn btn-primary" style="margin-top:10px; margin-left:15px">Select This Doctor</button>
		<div id='grid_session_c' class='col-xs-12' align='center' style='padding-top:10px;'>
			<table id='grid_session' class='table table-striped'></table>
			<div id='grid_session_pager'></div>
		</div>
		`);

	$('#selecting_doctor').click(function(){
		$(dialog_name.textfield).off('blur',onBlur);
		$(dialog_name.textfield).val(selrowData("#"+dialog_name.gridname)[getfield(dialog_name.field)[0]]);
		$(dialog_name.textfield).parent().next().html(selrowData("#"+dialog_name.gridname)[getfield(dialog_name.field)[1]]);

		let data = selrowData('#' + dialog_name.gridname);
		let interval = data['d_intervaltime'];
		let apptsession = $("#grid_session").jqGrid('getRowData');
		$('.fc-myCustomButton-button').show();

		session_field.addSessionInterval(interval,apptsession);

		var event_apptbook = {
			id: 'apptbook',
			url: "apptrsc/getEvent",
			type: 'GET',
			data: {
				type: 'apptbook',
				drrsc: $('#resourcecode').val()
			}
		}

		var event_appt_leave = {
			id: 'appt_leave',
			url: "apptrsc/getEvent",
			type: 'GET',
			data: {
				type: 'appt_leave',
				drrsc: $('#resourcecode').val()
			},
			color: '#e27370',
        	rendering: 'background'
		}

		$('#calendar').fullCalendar( 'removeEventSource', 'apptbook');
		$('#calendar').fullCalendar( 'removeEventSource', 'appt_leave');
		$('#calendar').fullCalendar( 'addEventSource', event_apptbook);
		$('#calendar').fullCalendar( 'addEventSource', event_appt_leave);


		$(dialog_name.textfield).focus();
		$("#"+dialog_name.dialogname).dialog( "close" );
		$("#"+dialog_name.gridname).jqGrid("clearGridData", true);
		$(dialog_name.textfield).on('blur',{data:dialog_name,errorField:errorField},onBlur);
	});

	$("#grid_session").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'Day', name: 'days', width: 80, classes: 'pointer' },
            { label: 'From Morning', name: 'timefr1', width: 100, classes: 'pointer' },
            { label: 'To Morning', name: 'timeto1', width: 100, classes: 'pointer' },
            { label: 'From Evening', name: 'timefr2', width: 100, classes: 'pointer' },
            { label: 'To Evening', name: 'timeto2', width: 100, classes: 'pointer' },
        ],
		autowidth:true,viewrecords:true,loadonce:false,width:200,height:200,owNum:30,
		pager: "#grid_session_pager",
		onSelectRow:function(rowid, selected){
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
	});

	var dialog_case = new ordialog(
		'case', 'hisdb.casetype', "#dialogForm input[name='case']", errorField,
		{
			colModel: [
				{ label: 'Case Code', name: 'case_code', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		},
		{
			title: "Select Case",
			open: function () {
				dialog_case.urlParam.filterCol = ['compcode'];
				dialog_case.urlParam.filterVal = ['9A'];
			},
		}, 'urlParam'
	);
	dialog_case.makedialog(true);

	var dialog_mrn = new ordialog(
		'mrn', 'hisdb.pat_mast', "#dialogForm input[name='mrn']", errorField,
		{
			colModel: [
				{	label: 'MRN', name: 'MRN', width: 100, classes: 'pointer', formatter: padzero, unformat: unpadzero, canSearch: true, or_search: true },
				{	label: 'Name', name: 'Name', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_mrn.gridname);
				$("#addForm input[name='patname']").val(data['Name']);
				$(dialog_mrn.textfield).parent().next().text(" ");
			}
		},
		{
			title: "Select Case",
			open: function () {
				dialog_mrn.urlParam.filterCol = ['compcode'];
				dialog_mrn.urlParam.filterVal = ['9A'];
			},
		}, 'urlParam'
	);
	dialog_mrn.makedialog(true);


	$("#dialogForm").dialog({
		autoOpen: false,
		width: 9.5 / 10 * $(window).width(),
		modal: true,
		close: function( event, ui ){
			emptyFormdata(errorField,'#addForm');
		}		
	});

	var session_field = new session_field();

	$("#start_time_dialog").dialog({
    	autoOpen : false, 
    	modal : true,
		width: 8/10 * $(window).width(),
		open: function(){
			$("#grid_start_time").jqGrid ('setGridWidth', Math.floor($("#grid_start_time_c")[0].offsetWidth-$("#grid_start_time_c")[0].offsetLeft));
		},
		close:function(){
		}
    });

	$("#start_time ~ a").click(function(){
		$("#start_time_dialog").dialog("open");
	});

	$("#grid_start_time").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'Pick time', name: 'time', width: 80, classes: 'pointer' },
            { label: 'Patient Name', name: 'pat_name', width: 200, classes: 'pointer' },
            { label: 'Remarks', name: 'remarks', width: 200, classes: 'pointer' },
        ],
		autowidth:true,viewrecords:true,loadonce:false,width:200,height:200,owNum:30,
		pager: "#grid_start_time_pager",
		onSelectRow:function(rowid, selected){
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$('#start_time').val(rowid);
			$('#end_time').val(moment($('#apptdatefr_day').val()+" "+rowid).add(session_field.interval, 'minutes').subtract(1, 'minutes').format("HH:mm:SS"));
			$("#start_time_dialog").dialog('close');
		},
	});

	function session_field(){
		this.apptsession;
		this.interval;
		this.date_fr;
		this.day_fr;
		this.fr_obj;
		this.fr_start;
		this.events=[];

		this.addSessionInterval = function(interval,apptsession){
			this.apptsession = apptsession;

			let temp_bussHour=[];
			this.apptsession.forEach(function( obj ) {
				var temp_obj = {dow:[],start:obj.timefr1,end:obj.timeto2};
				switch(obj.days) {
					case "SUNDAY": temp_obj.dow.push(0); break;
					case "MONDAY":  temp_obj.dow.push(1); break;
					case "TUESDAY": temp_obj.dow.push(2); break;
					case "WEDNESDAY": temp_obj.dow.push(3); break;
					case "THURSDAY": temp_obj.dow.push(4); break;
					case "FRIDAY": temp_obj.dow.push(5); break;
					case "SATURDAY": temp_obj.dow.push(6); break;
				}

				temp_bussHour.push(temp_obj);
			});

			///tukar business hour
			$('#calendar').fullCalendar('option', {
			   	businessHours: temp_bussHour
			});

			this.interval = interval;
			return this;
		}

		this.clear = function(){
			$("#grid_start_time").jqGrid("clearGridData", true);
			$("#grid_end_time").jqGrid("clearGridData", true);
			return this;
		}

		this.ready = function(){
			this.events =  $('#calendar').fullCalendar( 'clientEvents');

			this.date_fr = $('#apptdatefr_day').val();
			this.day_fr = moment(this.date_fr).format('dddd').toUpperCase();
			let day_fr = this.day_fr;
			this.fr_obj = this.apptsession.filter(function( obj ) {
				return obj.days == day_fr;
			});
			this.fr_start = moment(this.date_fr+" "+this.fr_obj[0].timefr1);

			return this;
		}

		this.set = function(){
			var fr_start = this.fr_start, date_fr = this.date_fr, fr_obj = this.fr_obj, interval= this.interval, events = this.events;

			while(!fr_start.isSameOrAfter(date_fr+" "+fr_obj[0].timeto1)){
    			let time_use = fr_start.format("HH:mm:SS");
    			let objuse = {time:time_use,pat_name:'',remarks:''}

    			events.forEach(function(elem,id){
					if(elem.start.isSame(fr_start)){
	    				objuse.pat_name=(objuse.pat_name=='')?elem.pat_name:objuse.pat_name+', '+elem.pat_name;
	    				objuse.remarks=(objuse.remarks=='')?elem.remarks:objuse.remarks+', '+elem.remarks;
					}
    			});

    			$("#grid_start_time").jqGrid('addRowData', time_use,objuse);
    			fr_start.add(interval, 'minutes');
        	}

        	fr_start = moment(date_fr+" "+fr_obj[0].timefr2);

        	while(!fr_start.isSameOrAfter(moment(date_fr+" "+fr_obj[0].timeto2).add(interval, 'minutes'))){
        		let time_use = fr_start.format("HH:mm:SS");
    			let objuse = {time:time_use,pat_name:'',remarks:''}

    			events.forEach(function(elem,id){
					if(elem.start.isSame(fr_start)){
	    				objuse.pat_name=(objuse.pat_name=='')?elem.pat_name:objuse.pat_name+','+elem.pat_name;
	    				objuse.remarks=(objuse.remarks=='')?elem.remarks:objuse.remarks+','+elem.remarks;
					}
    			});

    			$("#grid_start_time").jqGrid('addRowData', time_use,objuse);
    			fr_start = fr_start.add(interval, 'minutes');
        	}
		}
	}

	$("#apptdatefr_day,#apptdateto_day").change(function(){
		session_field.clear().ready().set();
	});
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today myCustomButton',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		customButtons: {
	        myCustomButton: {
	            text: 'Add',
	            click: function() {
	            	oper='add';

					var temp = $('#resourcecode').val();
					var start = $(".fc-myCustomButton-button").data("start");

					$('#dialogForm #doctor').val(temp);
					$('#apptdatefr_day').val(moment(start).format('YYYY-MM-DD'));

	            	session_field.clear().ready().set();
					
					$("#dialogForm").dialog("open");
            	}
        	}
    	},
		defaultDate: d,
		navLinks: true,
  		viewRender: function(view, element) { 
  			// if(view.name=='agendaDay'){
  			// 	$('#calendar').fullCalendar('gotoDate', $(".fc-myCustomButton-button").data("start"));
  			// } 
  		},
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		selectable: true,
		selectHelper: true,
		timezone: 'local',
		select: function(start, end, jsEvent, view, resource) {
			$(".fc-myCustomButton-button").data( "start", start );
			var events = $('#calendar').fullCalendar( 'clientEvents');
			$(".fc-myCustomButton-button").show();
			events.forEach(function(elem,id){
				if(elem.allDay){
					let elem_end = (elem.end==null)?elem.start:elem.end;
					if(start.isBetween(elem.start,elem_end, null, '[)')){
						$(".fc-myCustomButton-button").hide();
					}
				}
			});
			if(!start.isSameOrAfter(moment().subtract(1, 'days'))){
				$(".fc-myCustomButton-button").hide();
			}

		},
		eventRender: function(event, element) {
			if(event.source.id == "apptbook"){
				element.bind('dblclick', function() {
					oper = 'edit';
					$('#doctor').val(event.loccode);
					$('#mrn').val(event.mrn);
					$('#patname').val(event.pat_name);
					$('#apptdatefr_day').val(event.start.format('YYYY-MM-DD'));
					$('#start_time').val(event.start.format('HH:mm:ss'));
					$('#end_time').val(event.end.format('HH:mm:ss'));
					$('#telno').val(event.telno);
					$('#telhp').val(event.telhp);
					$('#case').val(event.case_code);
					$('#remarks').val(event.remarks);
					$('#status').val(event.apptstatus);
					$('#idno').val(event.idno);
					$("#dialogForm").dialog('open');
				});
			}
			if(event.source.rendering == 'background'){
				element.append(event.title);
			}
		},
		timeFormat: 'h(:mm)a',
		eventDrop: function(event, delta, revertFunc) {
			var param={
				'event_drop':'event_drop',
				'idno':event.idno,
				'start':event.start.format("YYYY-MM-DD HH:mm:SS"),
				'end':event.start.clone().add(session_field.interval, 'minutes').format("YYYY-MM-DD HH:mm:SS"),
				'_token': $('#token').val()
			};

			$.post("apptrsc/editEvent",param, function (data) {

			}).fail(function (data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function (data) {
				
			});
		},
		eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
			var param={
				'event_drop':'event_drop',
				'idno':event.idno,
				'start':event.start.format("YYYY-MM-DD HH:mm:SS"),
				'end':event.start.clone().add(session_field.interval, 'minutes').format("YYYY-MM-DD HH:mm:SS"),
				'_token': $('#token').val()
			};

			$.post("apptrsc/editEvent",param, function (data) {

			}).fail(function (data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function (data) {
				
			});
		},
		displayEventTime:true,
		selectConstraint : "businessHours",
		eventSources: [
			{	
				id:'apptbook'
			},
			{	
				id:'appt_ph',
				url:'apptrsc/getEvent',
				type:'GET',
				data:{
					type:'appt_ph'
				},
				color: 'yellow', 
            	textColor: 'black',
            	rendering: 'background'
			},
			{	
				id:'appt_leave'
			}
	    ]
	});
	$('.fc-myCustomButton-button').hide();

	$('.fc-agendaDay-button.fc-button.fc-state-default.fc-corner-right').click(function(){
		if($(".fc-myCustomButton-button").data("start")!=null){
			$('#calendar').fullCalendar(
					'changeView', 
					'agendaDay', 
					$(".fc-myCustomButton-button").data("start").format('YYYY-MM-DD')
			);
		}
		
	});
	
	var oper = 'add';
	$('#submit').click(function(){
		var url = (oper == 'add')?"apptrsc/addEvent":"apptrsc/editEvent";

		if( $('#addForm').isValid({requiredFields: ''}, conf, true) ) {
			$.post(url, $("#addForm").serialize(), function (data) {
			}).fail(function (data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function (data) {
				$("#dialogForm").dialog('close');
				var events = {
	        		id:'apptbook',
					url: "apptrsc/getEvent",
					type: 'GET',
					data: {
						type:'apptbook',
						drrsc: $('#resourcecode').val()
					}
				}

				$('#calendar').fullCalendar( 'removeEventSource', 'apptbook');
				$('#calendar').fullCalendar( 'addEventSource', events); 
			});
		}
	});
	
});


