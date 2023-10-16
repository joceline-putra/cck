
<script>
$(document).ready(function() {   
	var url = "<?= base_url('report'); ?>";
	//var url_print = "<?= base_url('report/prints'); ?>"; 
	var url_print = "<?= base_url('report'); ?>";
	$(".nav-tabs").find('li[class="active"]').removeClass('active');
	$(".nav-tabs").find('li[data-name="report/finance/ledger"]').addClass('active');    
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

	$('#filter_account').select2({
		//dropdownParent:$("#modal-id"), //If Select2 Inside Modal
		// placeholder: '<i class="fas fa-balance-scale"></i> Pilih Akun Perkiraan',		
		placeholder: {
		   id: '0',
		   text: '<i class="fas fa-balance-scale"></i> Pilih Akun Perkiraan'
	   	},
	   	allowClear: true,		
		minimumInputLength: 0,
		ajax: {
			type: "get",
			url: "<?= base_url('search/manage');?>",
			dataType: 'json',
			delay: 250,
			data: function(params){
				var query = {
					search: params.term,
					source: 'accounts'
				}      
				return query;  
			},
			processResults: function (datas, params) {
				params.page = params.page || 1;
				return {
					results: datas,
					pagination: {
						more: (params.page * 10) < datas.count_filtered
					}
				};      
			},    
			cache: true
		},
		escapeMarkup: function(markup){ 
			return markup; 
		},
		templateResult: function(datas){ //When Select on Click
			if (!datas.id) { return datas.text; }
			if($.isNumeric(datas.id) == true){
				return '<i class="fas fa-balance-scale"></i> '+datas.text;
				return datas.text;          
			}
		},
		templateSelection: function(datas) { //When Option on Click
			if (!datas.id) { return datas.text; }
			//Custom Data Attribute         
			if($.isNumeric(datas.id) == true){
				return datas.text;
				return '<i class="fas fa-balance-scale"></i> '+datas.text;
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
			d.action = 'load-report-ledger';
			// d.tipe = 1;
			d.date_start = $("#filter_date").attr('data-start');
			d.date_end = $("#filter_date").attr('data-end');
			// d.start = $("#table-data").attr('data-limit-start');
			// d.length = $("#table-data").attr('data-limit-end'); 
			d.length = $("#filter_length").find(':selected').val(); 
			d.account = $("#filter_account").find(':selected').val();     
			// d.product = $("#filter_product").find(':selected').val();             
			// d.order[0]['column'] = $("#filter_order").find(':selected').val();
			// d.order[0]['dir'] = $("#filter_dir").find(':selected').val();     
			// d.search = {
			// value:$("#filter_search").val()
			// };               
			// d.user_role =  $("#select_role").val();
		},
		dataSrc: function(data) {
			return data.result;
		}
		},
		"columnDefs": [
		{"targets":0, "title":"Tanggal","searchable":false,"orderable":false},   
		{"targets":1, "title":"Nomor","searchable":false,"orderable":false},
		{"targets":2, "title":"Keterangan","searchable":false,"orderable":false},
		{"targets":3, "title":"Debit","searchable":false,"orderable":false,"class":"text-right"},
		{"targets":4, "title":"Kredit","searchable":false,"orderable":false,"class":"text-right"},
		{"targets":5, "title":"Saldo","searchable":false,"orderable":false,"class":"text-right"}                        
		],
		// "order": [
		//   [0, 'asc']
		// ],
		"columns": [{
			'data': 'journal_item_date_format'
		},{
			'data': 'journal_item_id',
			render:function(data,meta,row){
			var dsp = '';
			var document_number = '';

			if(row.journal_number == null){
				if(row.trans_number == null){
				}else{                    
				document_number += row.trans_number;
				
				}
			}else{
		
				document_number += row.journal_number;
			}
			dsp += '<a href="'+row.url+'" target="_blank">'+document_number+'</a><br>';
			if(row.contact_name !== null){
				dsp += row.contact_name+'<br>';
			}
			dsp += '<b>'+row.type_name+'</b>';
			return dsp;
			}
		},{
			'data': 'journal_item_note'
		},{
			'data': 'debit',className:'text-right',
			render:function(data,meta,row){
			var dsp ='';
			if(row.type_name=='Saldo Awal'){
				dsp ='';
			}else{
				dsp += addCommas(row.debit);            
			}          
			return dsp;
			}
		},{
			'data': 'credit',className:'text-right',
			render:function(data,meta,row){
			var dsp ='';
			if(row.type_name=='Saldo Awal'){
				dsp ='';
			}else{
				dsp += addCommas(row.credit);            
			}          
			return dsp;
			}
		},{
			'data': 'balance', className: 'text-right',
			render:function(data,meta,row){
			var dsp ='';
			return addCommas(row.balance);
			}
		}
		],
		"language": {
		"emptyTable": "Data tidak tersedia / Harap pilih Akun Perkiraan terlebih dahulu"
		}
	});

	//Datatable Config  
	$("#table-data_filter").css('display','none');  
	$("#table-data_length").css('display','none');
	$('#table-data').on('page.dt', function () {
		var info = index.page.info();
		// console.log( 'Showing page: '+info.page+' of '+info.pages);
		var limit_start = info.start;
		var limit_end = info.end;
		var length = info.length;
		var page = info.page;
		var pages = info.pages;
		console.log(limit_start,limit_end);
		$("#table-data").attr('data-limit-start',limit_start);
		$("#table-data").attr('data-limit-end',limit_end);
	}); 

	$(document).on("change","#filter_account",function(e) {
		index.ajax.reload();
	});   

	$(document).on("click",".btn-print-all",function() {
		var id = $(this).attr("data-id"); 
		var action = $(this).attr('data-action'); //1,2
		var request = $(this).attr('data-request'); //report_purchase_buy_recap
		var format = $(this).attr('data-format'); //html, xls
		var account = $("#filter_account").find(':selected').val();
		var order = 'journal_item_date';
		var dir = 'asc';    

		if(parseInt(account) > 0){
		var print_url = url_print +'/' 
			+ request + '/'
			+ $("#filter_date").attr('data-start') + '/'
			+ $("#filter_date").attr('data-end') + '/'  
			+ account + "?format="+format+"&order="+order+"&dir="+dir;    
		window.open(print_url,'_blank');
		}else{
			notif(0,'Akun Perkiraan harus dipilih dahulu');
		}
	});
});
</script>