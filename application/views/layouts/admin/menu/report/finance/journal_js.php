
<script>
$(document).ready(function() {   
	var url = "<?= base_url('report'); ?>";
	//var url_print = "<?= base_url('report/prints'); ?>"; 
	var url_print = "<?= base_url('report'); ?>";
	var url_print_trans = "<?= base_url('transaksi/print'); ?>";  
	var url_ledger = "<?= base_url('report/report_finance_ledger');?>";

	$(".nav-tabs").find('li[class="active"]').removeClass('active');
	$(".nav-tabs").find('li[data-name="report/finance/journal"]').addClass('active');  
	// $.alert('Datatable: Belum sepenuhnya betul Sampai disini');
	const autoNumericOption = {
		digitGroupSeparator : '.', 
		decimalCharacter  : ',', 
		decimalCharacterAlternative: ',', 
		decimalPlaces: 0,
		watchExternalChanges: true //!!!        
	};
	
	// Start of Daterange
	//var start = moment().startOf('month');
	var start   = moment();
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
		placeholder: '<i class="fas fa-warehouse"></i> Search',
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
				// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
				return datas.text;          
			}
		},
		templateSelection: function(datas) { //When Option on Click
			if (!datas.id) { return datas.text; }          
			if($.isNumeric(datas.id) == true){
				return datas.text;
				// return '<i class="fas fa-warehouse '+datas.id.toLowerCase()+'"></i> '+datas.text;
			}
		}
	});
  
	var collapsedGroups = {};
	var top = '';
	var parent = ''; 
	var index = $("#table-data").DataTable({
		// "processing": true,
		"responsive":true,
		"serverSide": true,
		"ajax": {
		url: url,
		type: 'post',
		dataType: 'json',
		cache: 'false',
		data: function(d) {
			d.action = 'load-report-journal';
			// d.tipe = 1;
			d.date_start = $("#filter_date").attr('data-start');
			d.date_end = $("#filter_date").attr('data-end');
			// d.start = $("#table-data").attr('data-limit-start');
			// d.length = $("#table-data").attr('data-limit-end'); 
			// d.length = 500; 
			// d.account = $("#filter_account").find(':selected').val();    
			// d.account = 0; 
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
			// console.log(data.result);
		}
		},
		// "order": [
		//   [0, 'asc']
		// ],
		"columnDefs": [
		{"targets":0, "title":"Tanggal","searchable":false,"orderable":false,"rowGroup":true},
		{"targets":1, "title":"Akun","searchable":false,"orderable":false},      
		{"targets":2, "title":"Debit","searchable":false,"orderable":false,"class":"text-right"},
		{"targets":3, "title":"Kredit","searchable":false,"orderable":false,"class":"text-right"}                     
		],
		// columnDefs: [ {
		//     targets: [ 0, 1, 2],
		//     visible: false
		//   }
		// ],        
		"columns": [
		{'data': 'journal_item_date_format'},
		{'data': 'account_name'},
		{'data': 'debit',className:'text-right'},
		{'data': 'credit',className:'text-right'}
		],   
		// stripeClasses: [], 
		createdRow: function(row, data, dataIndex){
		if(data.type_name === 'Saldo Awal'){
			// $('td:eq(1)', row).attr('colspan', 5);

			// // Hide required number of columns
			// // next to the cell with COLSPAN attribute
			// $('td:eq(2)', row).css('display', 'none');
			// $('td:eq(3)', row).css('display', 'none');
			// $('td:eq(4)', row).css('display', 'none');
			// $('td:eq(5)', row).css('display', 'none');
			
			// // Update cell data
			// this.api().cell($('td:eq(0)', row)).data(data.journal_item_date_format);            
			// this.api().cell($('td:eq(1)', row)).data(data.type_name);  
		}else{
			var debit = addCommas(data.debit);
			var credit = addCommas(data.credit);
			var account_name = '[ '+data.account_code+' ] - <a class="btn-print-ledger" data-id="'+data.account_id+'" style="cursor:pointer;font-weight:600;color:#567ed8;">'+data.account_name+'</a>';
			this.api().cell($('td:eq(0)', row)).data('&nbsp;&nbsp;&nbsp;&nbsp;'+data.journal_item_date_format);  
			
			if(parseFloat(debit) > 0){
			if(parseInt(data.account_id) > 0){

			}else{
				if(data.journal_group_session=='TOTAL'){
				account_name = '';
				this.api().cell($('td:eq(0)', row)).data('<b>TOTAL</b>');                           
				debit = '<b>'+debit+'</b>';   
				credit = '<b>'+credit+'</b>';
				var background_color = '#ecf0f2';
				$('td:eq(0)', row).css('background-color', background_color);
				$('td:eq(1)', row).css('background-color', background_color);
				$('td:eq(2)', row).css('background-color', background_color);
				$('td:eq(3)', row).css('background-color', background_color);                                          
				}
			}
			}else{
			var account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+'[ '+data.account_code+' ] - <a class="btn-print-ledger" data-id="'+data.account_id+'" style="cursor:pointer;font-weight:600;color:#567ed8;">'+data.account_name+'</a>';
			}

			$('td:eq(4)', row).addClass('text-right');              
			$('td:eq(5)', row).addClass('text-right');          
			
			

			// this.api().cell($('td:eq(0)', row)).data(data.journal_group_session);                    
			this.api().cell($('td:eq(1)', row)).data(account_name);            
			// this.api().cell($('td:eq(2)', row)).data(data.journal_number);            
			// this.api().cell($('td:eq(3)', row)).data(data.journal_item_note);                                                
			this.api().cell($('td:eq(2)', row)).data(debit);            
			this.api().cell($('td:eq(3)', row)).data(credit);                 
		}
		},      
		"rowGroup": {
		dataSrc:['journal_text'],
		// dataSrc:['journal_group_session'],
		// dataSrc:function(data){
		//   // var dsp = '';
		//   // if(data['journal_group_session'] !== "TOTAL"){
		//   //   dsp +='<a href="#" class="btn-print" data-session="'+data['trans_session']+'">'+data['journal_text']+'</a>';
		//   // }
		//   var arr = {
		//     text: data['journal_text'],
		//     session: data['journal_group_session']
		//   };
		//   return arr;
		//   // return dsp;
		//   // console.log(row['journal_text']);
		// },
		startRender: function(row, data, level) {
			// console.log(row['journal_text']);
			// console.log(level['journal_group_session']);
			var all;
			var dsp = '';
			if (level === 0) {
				top = data;
				all = data;
				// console.log(all);
			}else{
				// if parent collapsed, nothing to do
				if (!!collapsedGroups[top]) {
					return;
				}
				all = top + data;
			}
			var collapsed = !!collapsedGroups[all];
			row.nodes().each(function(r) {
				r.style.display = collapsed ? 'none' : '';
			});

			// console.log(row[0][1]['journal_item_id']);
			// dsp += '<tr>';
			//   dsp += '<td colspan="4">'+ data +' ('+rows.count()+')</td>';
			// dsp += '<tr>';
			// console.log(row.level[1]);
			// return dsp;
			// Add category name to the <tr>. NOTE: Hardcoded colspan
			// console.log(data);
			if(data !== 'No group'){
			return $('<tr/>')
				.append('<td colspan="4" style="background-color:#dfe3e4;">' + data + ' (' + row.count() + ')</td>')
				.attr('data-name', all)
				.toggleClass('collapsed', collapsed);
			}else{
			// return
			}
		}
		},     
		"language": {
		"emptyTable": "Data tidak tersedia"
		}
	});
	$('#table-data tbody').on('click', 'tr.dtrg-start', function() {
		var name = $(this).data('name');
		console.log(name);
		collapsedGroups[name] = !collapsedGroups[name];
		// index.draw(false);
	});

	// //Datatable Config  
	$("#table-data_filter").css('display','none');  
	$("#table-data_length").css('display','none');
	$("#table-data_info").css('display','none');  
	$("#table-data_paginate").css('display','none');
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

	// $(document).on("change","#filter_account",function(e) {
		// index.ajax.reload();
	// });   
	$(document).on("click",".btn-print",function(e) {
		e.preventDefault();
		e.stopPropagation();
		// console.log($(this));
		
		var id = $(this).attr('data-session');
		var nomor = $(this).attr('data-number');
		var x = screen.width / 2 - 700 / 2;
		var y = screen.height / 2 - 450 / 2;

		window.open(url_print_trans+'/'+id,'Print','width=700,height=485,left=' + x + ',top=' + y + '').print();
	});  
	$(document).on("click",".btn-print-all",function() {
		var id = $(this).attr("data-id"); 
		var action = $(this).attr('data-action'); //1,2
		var request = $(this).attr('data-request'); //report_purchase_buy_recap
		var format = $(this).attr('data-format'); //html, xls
		var account = 0;
		
		var order = 'journal_item_date'
		var dir = 'asc';    
		var print_url = url_print +'/' 
		+ request + '/'
		+ $("#filter_date").attr('data-start') + '/'
		+ $("#filter_date").attr('data-end') + '/' 
		+ account + "?format="+format+"&order="+order+"&dir="+dir;    
		window.open(print_url,'_blank');
	});
	$(document).on("click",".btn-print-ledger",function() {
		var id = $(this).attr("data-id"); 
		var format = 'html'; //html, xls
		var order = 'journal_item_date'
		var dir = 'asc';    
		var print_url = url_ledger +'/' 
		+ $("#filter_date").attr('data-start') + '/'
		+ $("#filter_date").attr('data-end') + '/' 
		+ id + "?format="+format+"&order="+order+"&dir="+dir;    
		window.open(print_url,'_blank');
	});  
});
</script>