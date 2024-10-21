
<script>
$(document).ready(function() {   
	var url = "<?= base_url('report'); ?>";
	//var url_print = "<?= base_url('report/prints'); ?>"; 
	var url_print = "<?= base_url('report'); ?>";
	$(".nav-tabs").find('li[class="active"]').removeClass('active');
	$(".nav-tabs").find('li[data-name="report/finance/cash_flow"]').addClass('active');    
	// $.alert('Datatable: Belum sepenuhnya betul Sampai disini');
	const autoNumericOption = {
		digitGroupSeparator : '.', 
		decimalCharacter  : ',', 
		decimalCharacterAlternative: ',', 
		decimalPlaces: 0,
		watchExternalChanges: true //!!!        
	};

  	// Start of Daterange
	var start = moment().startOf('month');
	var end   = moment();

	function set_daterangepicker(start, end) {
		$("#filter_date").attr('data-start',start.format('DD-MM-YYYY'));
		$("#filter_date").attr('data-end',end.format('DD-MM-YYYY'));
		$('#filter_date span').html(start.format('D-MMM-YYYY') + '&nbsp;&nbsp;&nbsp;sd&nbsp;&nbsp;&nbsp;' + end.format('D-MMM-YYYY'));
	}
	$('#filter_date').daterangepicker({
		"startDate": start, //mm/dd/yyyy
		"endDate": end, ////mm/dd/yyyy
		"showDropdowns": true,
		// "minYear": 2019,
		// "maxYear": 2020,
		"autoApply": true,
		"alwaysShowCalendars": true,
		"opens": "left",
		"buttonClasses": "btn btn-sms",
		"applyButtonClasses": "btn-primaryd",
		"cancelClass": "btn-defaults",        
		"ranges": {
			'Hari ini': [moment(), moment()],
			'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'7 hari terakhir': [moment().subtract(6, 'days'), moment()],
			'30 hari terakhir': [moment().subtract(29, 'days'), moment()],
			'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
			'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
		"locale": {
			"format": "MM/DD/YYYY",
			"separator": " - ",
			"applyLabel": "Apply",
			"cancelLabel": "Cancel",
			"fromLabel": "From",
			"toLabel": "To",
			"customRangeLabel": "Custom",
			"weekLabel": "W",
			"daysOfWeek": ["Mn","Sn","Sl","Rb","Km","Jm","Sb"],
			"monthNames": ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
			"firstDay": 1
		}
	}, function(start, end, label) {
		// console.log(start.format('YYYY-MM-DD')+' to '+end.format('YYYY-MM-DD'));
		set_daterangepicker(start,end);
		// checkup_table.ajax.reload();
	});
	$('#filter_date').on('apply.daterangepicker', function(ev, picker) {
		// console.log(ev+', '+picker);
		$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
		index.ajax.reload();
	});
	set_daterangepicker(start,end); 
	// End of Daterange

	$("#start, #end").datepicker({
		// defaultDate: new Date(),
		format: 'dd-mm-yyyy',
		autoclose: true,
		enableOnReadOnly: true,
		language: "id",
		todayHighlight: true,
		weekStart: 1 
	}).on('change', function(){
		// index.ajax.reload();
	});
	$('#filter_branch').select2({
		minimumInputLength: 0,
		placeholder: {
			id: '0',
			text: '-- Pilih --'
		},
		allowClear: true,
		ajax: {
			type: "get",
			url: "<?= base_url('search/manage');?>",
			dataType: 'json',
			delay: 250,
			cache: true,
			data: function (params) {
				let query = {
					search: params.term,
					tipe: 1,
					source: 'branchs',
				};
				return query;
			},
			processResults: function (result, params){
				let datas = [];
				$.each(result, function(key, val){
					datas.push({
						'id' : val.id,
						'text' : val.text
					});
				});
				return {
					results: datas,
					pagination: {
						more: (params.page * 10) < result.count_filtered
					}
				};
			}
		},
		templateResult: function(datas){ //When Select on Click
			//if (!datas.id) { return datas.text; }
			if($.isNumeric(datas.id) == true){
				// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
				return datas.text;
			}
		},
		templateSelection: function(datas) { //When Option on Click
			//if (!datas.id) { return datas.text; }
			//Custom Data Attribute
			//$(datas.element).attr('data-column', datas.column);        
			//return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
			if($.isNumeric(datas.id) == true){
				// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
				return datas.text;
			}
		}
	}); 
	$('#filter_user').select2({
		minimumInputLength: 0,
		placeholder: {
			id: '0',
			text: '-- Pilih --'
		},
		allowClear: true,
		ajax: {
			type: "get",
			url: "<?= base_url('search/manage');?>",
			dataType: 'json',
			delay: 250,
			cache: true,
			data: function (params) {
				let query = {
					search: params.term,
					source: 'users',
				};
				return query;
			},
			processResults: function (result, params){
				let datas = [];
				$.each(result, function(key, val){
					datas.push({
						'id' : val.id,
						'text' : val.text
					});
				});
				return {
					results: datas,
					pagination: {
						more: (params.page * 10) < result.count_filtered
					}
				};
			}
		},
		templateResult: function(datas){ //When Select on Click
			//if (!datas.id) { return datas.text; }
			if($.isNumeric(datas.id) == true){
				// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
				return datas.text;
			}
		},
		templateSelection: function(datas) { //When Option on Click
			//if (!datas.id) { return datas.text; }
			//Custom Data Attribute
			//$(datas.element).attr('data-column', datas.column);        
			//return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
			if($.isNumeric(datas.id) == true){
				// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
				return datas.text;
			}
		}
	}); 	

	var index = $("#table-data").DataTable({
		// "processing": true,
		"serverSide": true,
		"ajax": {
		url: url,
		type: 'post',
		dataType: 'json',
		cache: 'false',
		data: function(d) {
			d.action = 'load-report-cash-flow';
			// d.tipe = 1;
			d.date_start = $("#filter_date").attr('data-start');
			d.date_end = $("#filter_date").attr('data-end');
			// d.start = $("#table-data").attr('data-limit-start');
			// d.length = $("#table-data").attr('data-limit-end'); 
			d.length = $("#filter_length").find(':selected').val(); 
			d.branch = $("#filter_branch").find(':selected').val(); 
			d.user = $("#filter_user").find(':selected').val(); 		
		},
		dataSrc: function(data) {
			return data.result;
		}
		},
		"columnDefs": [
			{"targets":0, "title":"Deskripsi","searchable":false,"orderable":false},   
			{"targets":1, "title":"Jumlah","searchable":false,"orderable":false},                       
		],
		// "order": [
		//   [0, 'asc']
		// ],
		"columns": [
			{
				'data': 'recap_name',
				render:function(data,meta,row){
					var dsp ='';
					if(row.link){
						dsp += '<a href="'+row.link+'" target="_blank"><i class="fas fa-print"></i> '+row.recap_name+'</a>';
					}else{
						dsp += row.recap_name;
					}
					return dsp;
				}				
			},{
				'data': 'recap_total',className:'text-right',
				render:function(data,meta,row){
					var dsp ='';
					dsp += addCommas(row.recap_total);       
					return dsp;
				}
			}
		],
	});

	//Datatable Config  
	$("#table-data_filter").css('display','none');  
	$("#table-data_length").css('display','none');

	$(document).on("change","#filter_branch",function(e) {
		index.ajax.reload();
	});   
	$(document).on("change","#filter_user",function(e) {
		index.ajax.reload();
	});   	

	$(document).on("click",".btn-print-all",function() {
		var id = $(this).attr("data-id"); 
		var action = $(this).attr('data-action'); //1,2
		var request = $(this).attr('data-request'); //report_purchase_buy_recap
		var format = $(this).attr('data-format'); //html, xls
		var branch = $("#filter_branch").find(':selected').val();
		var fuser = $("#filter_user").find(':selected').val();		
		var order = 'date';
		var dir = 'asc';    

		// if(parseInt(branch) > 0){
		var print_url = url_print +'/' 
			+ request + '/'
			+ $("#filter_date").attr('data-start') + '/'
			+ $("#filter_date").attr('data-end') + '/'  
			+ branch + '/' + fuser + "?format="+format+"&order="+order+"&dir="+dir;    
		window.open(print_url,'_blank');
		// }else{
			// notif(0,'Akun Perkiraan harus dipilih dahulu');
		// }
	});
});
</script>