<script>
$(document).ready(function() {   
  var url = "<?= base_url('report'); ?>";
  //var url_print = "<?= base_url('report/prints'); ?>"; 
  var url_print = "<?= base_url('report'); ?>";
  var url_ledger = "<?= base_url('report/report_finance_ledger');?>";
    
  $(".nav-tabs").find('li[class="active"]').removeClass('active');
  $(".nav-tabs").find('li[data-name="report/finance/worksheet"]').addClass('active');    
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
				d.action = 'load-report-trial-balance';
				// d.tipe = 1;
				d.date_start = $("#filter_date").attr('data-start');
				d.date_end = $("#filter_date").attr('data-end');
				// d.start = $("#table-data").attr('data-limit-start');
				// d.length = $("#table-data").attr('data-limit-end'); 
				// d.length = $("#filter_length").find(':selected').val(); 
				d.length = 500;
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
		"order": [
			[0, 'asc']
		],
		"columnDefs": [
			{"width":"70%", "targets":0}
		],        
		"columns": [
			{'data': 'account_name','width':'70%'},
			{'data': 'start_debit',className:'text-right'},
			{'data': 'start_credit',className:'text-right'},
			{'data': 'movement_debit',className:'text-right'},
			{'data': 'movement_credit',className:'text-right'},
			{'data': 'end_debit',className:'text-right'},
			{'data': 'end_credit',className:'text-right'},
			{'data': 'profit_loss_debit',className:'text-right'},
			{'data': 'profit_loss_credit',className:'text-right'},
			{'data': 'balance_debit',className:'text-right'},
			{'data': 'balance_credit',className:'text-right'}                  
		],   
		stripeClasses: [], 
		createdRow: function(row, data, dataIndex){
			if(data.type_name === 'Saldo Awal'){
				// $('td:eq(1)', row).attr('colspan', 5);

				// // Hide required number of columns
				// // next to the cell with COLSPAN attribute
				// $('td:eq(1)', row).css('text-align','right');
				// $('td:eq(2)', row).css('text-align','right');
				// $('td:eq(3)', row).css('text-align','right');
				// $('td:eq(4)', row).css('text-align','right');
				// $('td:eq(5)', row).css('text-align','right');
				// $('td:eq(6)', row).css('text-align','right');

				// // Update cell data
				// this.api().cell($('td:eq(0)', row)).data(data.journal_item_date_format);            
				// this.api().cell($('td:eq(1)', row)).data(data.type_name);  
			}else{
				// var debit = data.debit;
				// var credit = data.credit;

				
				if(parseInt(data.account_id) > 0){

				$('td:eq(1)', row).css('text-align','right');
				$('td:eq(2)', row).css('text-align','right');
				$('td:eq(3)', row).css('text-align','right');
				$('td:eq(4)', row).css('text-align','right');
				$('td:eq(5)', row).css('text-align','right');
				$('td:eq(6)', row).css('text-align','right');   
				$('td:eq(7)', row).css('text-align','right');
				$('td:eq(8)', row).css('text-align','right');
				$('td:eq(9)', row).css('text-align','right');
				$('td:eq(10)', row).css('text-align','right');                    
				var account_name = '';
				account_name +='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				account_name +='<a class="btn-print" data-id="'+data.account_id+'" style="cursor:pointer;font-weight:600;color:#6e67f1;">';
				account_name +=data.account_code;
				account_name +='&nbsp;&nbsp;';
				account_name +=data.account_name;
				account_name +='</a>';
				this.api().cell($('td:eq(0)', row)).data(account_name);                                                         
				this.api().cell($('td:eq(1)', row)).data(addCommas(data.start_debit));            
				this.api().cell($('td:eq(2)', row)).data(addCommas(data.start_credit));                                                
				this.api().cell($('td:eq(3)', row)).data(addCommas(data.movement_debit));            
				this.api().cell($('td:eq(4)', row)).data(addCommas(data.movement_credit));                 
				this.api().cell($('td:eq(5)', row)).data(addCommas(data.end_debit));            
				this.api().cell($('td:eq(6)', row)).data(addCommas(data.end_credit));                                                
				this.api().cell($('td:eq(7)', row)).data(addCommas(data.profit_loss_debit));            
				this.api().cell($('td:eq(8)', row)).data(addCommas(data.profit_loss_credit));                 
				this.api().cell($('td:eq(9)', row)).data(addCommas(data.balance_debit));            
				this.api().cell($('td:eq(10)', row)).data(addCommas(data.balance_credit));            
				}else{
				// var account_name = '<b>'+data.account_name+'</b>';          
				// this.api().cell($('td:eq(0)', row)).data(account_name);
				var account_name = '<b>'+data.account_name+'</b>';          
				this.api().cell($('td:eq(0)', row)).data(account_name);
				
				// $('td:eq(0)', row).attr('colspan', 5);                 
				// console.log(account_name);
				var total_start_debit = '';
				var total_start_credit = '';
				var total_movement_debit = '';
				var total_movement_credit = '';
				var total_end_debit = '';
				var total_end_credit = '';             
				var total_profit_loss_debit = '';
				var total_profit_loss_credit = '';
				var total_balance_loss_debit = '';
				var total_balance_loss_credit = '';                                        

				if(account_name == '<b>Total</b>'){
					var total_start_debit = '<b>'+addCommas(data.start_debit)+'</b>';
					var total_start_credit = '<b>'+addCommas(data.start_credit)+'</b>';
					var total_movement_debit = '<b>'+addCommas(data.movement_debit)+'</b>';
					var total_movement_credit = '<b>'+addCommas(data.movement_credit)+'</b>';            
					var total_end_debit = '<b>'+addCommas(data.end_debit)+'</b>';
					var total_end_credit = '<b>'+addCommas(data.end_credit)+'</b>';
					var total_profit_loss_debit = '<b>'+addCommas(data.profit_loss_debit)+'</b>';
					var total_profit_loss_credit = '<b>'+addCommas(data.profit_loss_credit)+'</b>';
					var total_balance_debit = '<b>'+addCommas(data.balance_debit)+'</b>';
					var total_balance_credit = '<b>'+addCommas(data.balance_credit)+'</b>';

					var background_color = '#ecf0f2';
					$('td:eq(0)', row).css('background-color',background_color);
					$('td:eq(1)', row).css('background-color',background_color);
					$('td:eq(2)', row).css('background-color',background_color);
					$('td:eq(3)', row).css('background-color',background_color);
					$('td:eq(4)', row).css('background-color',background_color);
					$('td:eq(5)', row).css('background-color',background_color);
					$('td:eq(6)', row).css('background-color',background_color);
					$('td:eq(7)', row).css('background-color',background_color);
					$('td:eq(8)', row).css('background-color',background_color);
					$('td:eq(9)', row).css('background-color',background_color);
					$('td:eq(10)', row).css('background-color',background_color);                                                                        
				}
					this.api().cell($('td:eq(1)', row)).data(total_start_debit);            
					this.api().cell($('td:eq(2)', row)).data(total_start_credit);          
				// $('td:eq(0)', row).attr('colspan', 5);                 
				// this.api().cell($('td:eq(1)', row)).data('');            
				// this.api().cell($('td:eq(2)', row)).data('');                                                
				this.api().cell($('td:eq(3)', row)).data(total_movement_debit);            
				this.api().cell($('td:eq(4)', row)).data(total_movement_credit);                 
				this.api().cell($('td:eq(5)', row)).data(total_end_debit);            
				this.api().cell($('td:eq(6)', row)).data(total_end_credit);                                   
				this.api().cell($('td:eq(7)', row)).data(total_profit_loss_debit);            
				this.api().cell($('td:eq(8)', row)).data(total_profit_loss_credit);                 
				this.api().cell($('td:eq(9)', row)).data(total_balance_debit);            
				this.api().cell($('td:eq(10)', row)).data(total_balance_credit)                            
				}
						
			}
		},
		"language": {
			"emptyTable": "Data tidak tersedia / Harap pilih Akun Perkiraan terlebih dahulu"
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
	$("#table-data_paginate").css('display','none');
	$("#table-data_info").css('display','none');    
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
	$(document).on("click",".btn-print",function() {
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
});
</script>