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

	var Class2 = $('#Class2').val();

	if(Class2 == 'DOC'){
		$('#Scol').val('Doctor')
	}else{
		$('#Scol').val('Resource')
	}

	var dialog_name = new ordialog(
				'resourcecode', ['hisdb.apptresrc AS a', 'hisdb.doctor AS d', 'hisdb.apptsession AS s'], "input[name='resourcecode']", errorField,
			
				
        {
            colModel: [
                { label: 'Code', name: 'a_resourcecode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
								{ label: 'Description', name: 'a_description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
								{ label: 'Interval Time', name: 'd_intervaltime', width: 400, classes: 'pointer', canSearch: false, or_search: true },
								{ label: 'Time From 1', name: 's_timefr1', width: 400, classes: 'pointer', canSearch: false, or_search: true },
            ]
        }, {
            title: "Select " + ($("#Scol").val()),
            open: function () {
                var type = $('#Class2').val();
								dialog_name.urlParam.join_type = ['LEFT JOIN', 'LEFT JOIN'];
								dialog_name.urlParam.join_onCol = ['a.resourcecode'];
								dialog_name.urlParam.join_onVal = ['d.doctorcode'];
								dialog_name.urlParam.join_filterCol = [['a.compcode on =','a.compcode on =']];
								dialog_name.urlParam.join_filterVal = [['d.compcode','s.compcode']];
								dialog_name.urlParam.fixPost='true';
								dialog_name.urlParam.filterCol = ['a.TYPE'];
								dialog_name.urlParam.filterVal = [type];
								let data = selrowData('#' + dialog_name.gridname);
								$("#addForm input[name='interval']").val(data['d_intervaltime']);
			},
			close: function () {
				var events = {
					url: "apptrsc/getEvent",
					type: 'GET',
					data: {
						drrsc: $('#resourcecode').val()
					}
				}
		
				$('#calendar').fullCalendar( 'removeEventSource', events);
				$('#calendar').fullCalendar( 'addEventSource', events);         
				$('#calendar').fullCalendar( 'refetchEvents' );
			}
        }, 'urlParam'
    );
	dialog_name.makedialog(true);
	
	var dialog_doctor = new ordialog(
		'doctor', ['hisdb.apptresrc a','hisdb.doctor d'], "#dialogForm input[name='doctor']", errorField,
		{
			colModel: [
				{ label: 'Resource Code', name: 'a.resourcecode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'a.description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Interval', name: 'd.interval', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		},
		{
			title: "Select Doctor",
			open: function () {
				var test = $('#Class2').val();
				dialog_doctor.urlParam.filterCol = ['TYPE'];
				dialog_doctor.urlParam.filterVal = [test];
			},
		}, 'urlParam'
	);
	dialog_doctor.makedialog(true);

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
				// var test = $('#Class2').val();
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
				{	label: 'MRN', name: 'MRN', width: 100, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{	label: 'Name', name: 'Name', width: 200, classes: 'pointer', canSearch: true, or_search: true },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_mrn.gridname);
				$("#addForm input[name='patname']").val(data['Name']);
			}
		},
		{
			title: "Select Case",
			open: function () {
				// var test = $('#Class2').val();
				dialog_mrn.urlParam.filterCol = ['compcode'];
				dialog_mrn.urlParam.filterVal = ['9A'];
			},
		}, 'urlParam'
	);
	dialog_mrn.makedialog(true);

	$("body").show();
		var d = new Date();

		$("#dialogForm").dialog({
			autoOpen: false,
			width: 9.5 / 10 * $(window).width(),
			modal: true
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

								 var temp = $('#resourcecode').val();
								
								 var start = $(".fc-myCustomButton-button").data( "start");
								 var end = $(".fc-myCustomButton-button").data("end");

									$('#dialogForm #doctor').val(temp);
									
									$('#dialogForm #start').datetimepicker({
											format: 'YYYY-MM-DD HH:mm:ss',
											stepping: 15
										});
									
								$('#dialogForm #end').datetimepicker({
										format: 'YYYY-MM-DD HH:mm:ss',
										stepping: 15
									});
									
									// $('#dialogForm #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
									// $('#dialogForm #end').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
									
									$("#dialogForm").dialog("open");
            }
        }
    },
		// defaultDate: '2016-01-12',
		defaultDate: d,
		navLinks: true, // can click day/week names to navigate views
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		selectable: true,
		selectHelper: true,
		select: function(start, end) {
			$('#calendar').fullCalendar('changeView', 'agendaDay', moment(start).format('YYYY-MM-DD'));
			$(".fc-myCustomButton-button").data( "start", start );
			$(".fc-myCustomButton-button").data( "end", end );
		},
		eventRender: function(event, element) {
			element.bind('dblclick', function() {
				$('#ModalEdit #id').val(event.idno);
				$('#ModalEdit #title').val(event.title);
				$('#ModalEdit #color').val(event.color);
				$('#ModalEdit').modal('show');
			});
		},
		eventDrop: function(event, delta, revertFunc) { // si changement de position

			edit(event);

		},
		eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

			edit(event);

		},
		events: {
			url:'apptrsc/getEvent',
			type:'GET',
			data:{
				drrsc:''
			}
		}
		
	});
	
	function edit(event){
		start = event.start.format('YYYY-MM-DD HH:mm:ss');
		if(event.end){
			end = event.end.format('YYYY-MM-DD HH:mm:ss');
		}else{
			end = start;
		}
		
		id =  event.id;
		
		Event = [];
		Event[0] = id;
		Event[1] = start;
		Event[2] = end;
		
		$.ajax({
		 url: 'editEventDate.php',
		 type: "POST",
		 data: {Event:Event},
		 success: function(rep) {
				if(rep == 'OK'){
					// alert('Saved');
				}else{
					alert('Could not be saved. try again.'); 
				}
			}
		});
	}
	
});

$('#submit').click(function(){
	$.post("apptrsc/addEvent", $("#addForm").serialize(), function (data) {
	}).fail(function (data) {
		//////////////////errorText(dialog,data.responseText);
	}).done(function (data) {
		$("#dialogForm").dialog('close');
		var events = {
						url: "apptrsc/getEvent",
						type: 'GET',
						data: {
							drrsc: $('#resourcecode').val()
						}
					}
			
		$('#calendar').fullCalendar( 'removeEventSource', events);
		$('#calendar').fullCalendar( 'addEventSource', events);         
		$('#calendar').fullCalendar( 'refetchEvents' );
	});
});

$('#submitEdit').click(function(){
	$.post("apptrsc/editEvent", $("#editForm").serialize(), function (data) {
	}).fail(function (data) {
		//////////////////errorText(dialog,data.responseText);
	}).done(function (data) {
		$("#ModalEdit").modal('hide');
		
		var events = {
						url: "apptrsc/getEvent",
						type: 'GET',
						data: {
							drrsc: $('#resourcecode').val()
						}
					}
			
		$('#calendar').fullCalendar( 'removeEventSource', events);
		$('#calendar').fullCalendar( 'addEventSource', events);         
		$('#calendar').fullCalendar( 'refetchEvents' );
	});

});
