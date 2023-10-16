<script>
$(document).ready(function () {

    // Auto Set Navigation
    var identity = "<?php echo $identity; ?>";
    var menu_link = "<?php echo $_view;?>";
    $(".nav-tabs").find('li[class="active"]').removeClass('active');
    $(".nav-tabs").find('li[data-name="finance/opening_balances"]').addClass('active');

    var url = "<?= base_url('keuangan/manage'); ?>";
    var url_print = "<?= base_url('keuangan/print'); ?>";
    // var url_print_all = "<?= base_url('report/report_operasional'); ?>";

    var operator = "<?php echo $operator; ?>";

    var counter = true;
	// var total_debit = 0;
	// var total_credit = 0;

    $("#start, #end").datepicker({
		// defaultDate: new Date(),
		format: 'dd-mm-yyyy',
		autoclose: true,
		enableOnReadOnly: true,
		language: "id",
		todayHighlight: true,
		weekStart: 1
    }).on('change', function () {
		if (counter) {
			index.ajax.reload();
			counter = false;
		}
		setTimeout(function () {
			counter = true;
		})
    });

    $("#tgl").datepicker({
		// defaultDate: new Date(),
		format: 'dd-mm-yyyy',
		autoclose: true,
		enableOnReadOnly: true,
		language: "id",
		todayHighlight: true,
		weekStart: 1
    }).on('change', function () {

    });

    const autoNumericOption = {
		digitGroupSeparator: ',',
		decimalCharacter: '.',
		decimalCharacterAlternative: '.',
		decimalPlaces: 2,
		watchExternalChanges: true //!!!        
    };

	// new AutoNumeric('#account_debit_total', autoNumericOption);
    // new AutoNumeric('#account_credit_total', autoNumericOption);    
    // new AutoNumeric('#e_total_debit', autoNumericOption);
    // new AutoNumeric('#e_total_credit', autoNumericOption);        

    var index = $("#table-data").DataTable({
		// "processing": true,
		"serverSide": true,
		"ajax": {
			url: url,
			type: 'post',
			dataType: 'json',
			cache: 'false',
			data: function (d) {
				d.action = 'load';
				d.tipe = identity;
				d.date_start = $("#start").val();
				d.date_end = $("#end").val();
				// d.start = $("#table-data").attr('data-limit-start');
				// d.length = $("#table-data").attr('data-limit-end');
				d.length = $("#filter_length").find(':selected').val(); 
				// d.kontak = $("#filter_kontak").find(':selected').val();     
				d.search = {
					value:$("#filter_search").val()
				};                           
				// d.csrf_token = csrfData;  
				// d.user_role =  $("#select_role").val();
			},
			dataSrc: function (data) {
				return data.result;
			}
		},
      	"columnDefs": [
		  {
				"searchable": false,
				"orderable": false,
				"targets": [3]
			}, {
				"searchable": false,
				"orderable": true,
				"targets": [1, 2]
			}
		],
      	"order": [
        	[0, 'asc']
      	],
      	"columns": [
		  	{
        		'data': 'journal_date_format'
			}, {
				'data': 'journal_id',
				className: 'text-left',
				render: function (data, meta, row) {
					var dsp ='';
					dsp += '<a class="btn-edit" data-id="'+ row.journal_id +'" style="cursor:pointer;">';
					dsp += '<span class="fas fa-file-alt"></span>&nbsp;'+row.journal_number;
					dsp += '</a>';
					return dsp;
				}, 
			}, {
				'data': 'journal_note'
			}, {
				'data': 'journal_id',
				className: 'text-left',
				render: function (data, meta, row) {
					var dsp = '';
					// disp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';

					dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="'+ data +'">';
					dsp += '<span class="fas fa-edit"></span>Edit';
					dsp += '</button>&nbsp;';

					// dsp += '<button class="btn-print btn btn-mini btn-primary" data-id="' + data +
					//   '" data-number="' + row.journal_number + '">';
					// dsp += '<span class="fas fa-print"></span> Print';
					// dsp += '</button>';

					dsp += '<button class="btn-delete btn btn-mini btn-danger" data-id="' + data +
						'" data-number="' + row.journal_number + '">';
					dsp += '<span class="fas fa-trash"></span> Hapus';
					dsp += '</button>';

					// if (parseInt(row.flag) === 1) {
					//   dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-primary"';
					//   dsp += 'data-nomor="'+row.trans_nomor+'" data-kode="'+row.kode+'" data-id="'+data+'" data-flag="'+row.trans_flag+'">';
					//   dsp += '<span class="fas fa-check-square primary"></span></button>';
					// }else{ 
					//   dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
					//   dsp += 'data-nama="'+row.nama+'" data-kode="'+row.kode+'" data-id="'+data+'" data-flag="'+row.flag+'">';
					//   dsp += '<span class="fas fa-times danger"></span></button>';
					// }

					return dsp;
				}
			}
		]
    });
    //Datatable Config
    $("#table-data_filter").css('display','none');  
    $("#table-data_length").css('display','none');
    $("#filter_length").on('change', function(e){
		var value = $(this).find(':selected').val(); 
		$('select[name="table-data_length"]').val(value).trigger('change');
		index.ajax.reload();     
    }); 
    $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ index.ajax.reload(); } });     
    $('#table-data').on('page.dt', function () {
		var info = index.page.info();
		// console.log( 'Showing page: '+info.page+' of '+info.pages);
		var limit_start = info.start;
		var limit_end = info.end;
		var length = info.length;
		var page = info.page;
		var pages = info.pages;
		console.log(limit_start, limit_end);
		$("#table-data").attr('data-limit-start', limit_start);
		$("#table-data").attr('data-limit-end', limit_end);
    });

    //Function Load
    // formTransNew();
    // formTransItemSetDisplay(0);
    if(operator.length > 0){
      	$("#div-form-trans").show(300);
    }
    // loadJournalItems();
    
    $(document).on("change","#account_debit_account, #e_account",function(e) {
      e.preventDefault();
      e.stopPropagation();
      // console.log($(this));
      var this_val = $(this).find(':selected').val();
      if(this_val == '-'){
        $("#modal-account").modal('toggle');
        formAccountNew();               
      }
    });
	$(document).on("change",".debit",function(e) {
		e.preventDefault();
		e.stopPropagation();
		var total_debit = 0;
		var trans_list = $("#table-item tbody").children().length;
		var trans_list_data = [];
		if(parseInt(trans_list) > 0){    
			for(var a=1; a<trans_list+1; a++){
				var journal_item_debit = removeCommas($(".tr-trans-item-id:nth-child("+a+") td:nth-child(2) input").val());
				total_debit = Math.floor(total_debit) + parseFloat(journal_item_debit);
			}   
			console.log(total_debit);  
			$("#total_debit").val(total_debit);
		}		
	});	
	$(document).on("change",".credit",function(e) {
		e.preventDefault();
		e.stopPropagation();
		var total_credit = 0;
		var trans_list = $("#table-item tbody").children().length;
		var trans_list_data = [];
		if(parseInt(trans_list) > 0){    
			for(var a=1; a<trans_list+1; a++){
				// var journal_item_debit = removeCommas($(".tr-trans-item-id:nth-child("+a+") td:nth-child(2) input").val());
				var journal_item_credit = removeCommas($(".tr-trans-item-id:nth-child("+a+") td:nth-child(3) input").val());
				total_credit = Math.floor(total_credit) + parseFloat(journal_item_credit);
			}   
			console.log(total_credit);  
			$("#total_credit").val(total_credit);
		}		
	});	
	
    // New Button
    $(document).on("click", "#btn-new", function (e) {
		formTransNew();
		// $("#div-form-trans").show(300);
		$("#div-form-trans").show(300);
		$(this).hide();      
    });

    $(document).on("click", "#btn-cancel", function (e) {
		formTransCancel();
		$("#div-form-trans").hide(300);
    });

    // Save Button
    $(document).on("click", "#btn-save", function (e) {
		e.preventDefault();
		var next = true;
		var id_document = $("input[id=id_document]").val();

		if(parseInt(id_document) > 0 ){
			notif(0,'Silahkan refresh halaman ini');
			next =false;
		}

		if (next == true) {
			var total_item = $("input[id='total_item']").val();
				if (parseInt(total_item) == 0) {
				notif(0, 'Minimal satu rincian di input');
				next = false;
			}
		}      

		if(next == true){
			var total_debit = removeCommas($("#total_debit").val());
			var total_credit = removeCommas($("#total_credit").val());
			// console.log(total_debit+','+total_credit);
			if(parseFloat(total_debit) !== parseFloat(total_credit)){
				notif(0, 'Total debit & Total kredit harus imbang');
				next=false;
			}       
		}

		if(next == true){
			var trans_list = $("#table-item tbody").children().length;
			var trans_list_data = [];
			next=true;
			if(parseInt(trans_list) > 0){    
				for(var a=1; a<trans_list+1; a++){
					var account_id = $(".tr-trans-item-id:nth-child("+a+")").attr('data-account-id');
					var journal_item_debit = removeCommas($(".tr-trans-item-id:nth-child("+a+") td:nth-child(2) input").val());
					var journal_item_credit = removeCommas($(".tr-trans-item-id:nth-child("+a+") td:nth-child(3) input").val());
					var push_trans = {
							'account_id':account_id,
							'journal_item_debit':journal_item_debit,
							'journal_item_credit': journal_item_credit,
						}
					trans_list_data.push(push_trans);
				}
				next=true;       
			}
		}

		// return false;
		if (next == true) {

			//Prepare all Data
			// var prepare = {
				// tipe: identity,
				// journal_item_date: $("input[id='tgl']").val(),
				// journal_item_list_data: trans_list_data,
			// }
			// var prepare_data = JSON.stringify(prepare);
			// var data = {
				// action: 'create-opening-balance',
				// data: prepare_data
			// };
			var list_data = JSON.stringify(trans_list_data);

			var form = new FormData();
			form.append('action', 'create-opening-balance');
			form.append('journal_item_type', identity);
			form.append('journal_item_date', $("input[id='tgl']").val());
			form.append('journal_item_note', $("textarea[id='keterangan']").val());
			form.append('journal_item_list_data', list_data);
			
			$.ajax({
				type: "POST",
				url: url,
				data: form,
				dataType: 'json',
				cache: false,
				processData: false,
				contentType: false,				
				beforeSend: function () {},
				success: function (d) {
					if (parseInt(d.status) == 1) {
						/* Success Message */
						notif(1, d.message);
						$("input[id=id_document]").val(d.result.journal_id);
						$("input[id=journal_session]").val(d.result.journal_session);              
						// alert(d.result.journal_id);
						$("input[id=nomor]").val(d.result.journal_number);
						index.ajax.reload();
						$(".btn-print").attr('data-id', d.result.order_id);
						$(".btn-print").attr('data-number', d.result.order_number);
						// formTransNew();
						// formTransSetDisplay(0);
					} else { //Error
						notif(0, d.message);
					}
					checkDocumentExist();            
				},
				error: function (xhr, Status, err) {
					notif(0, 'Error');
				}
			});
		}
    });

    // Edit Button
    $(document).on("click", ".btn-edit", function (e) {
      	// formMasterSetDisplay(0);

		e.preventDefault();
		var id = $(this).data("id");
		var data = {
			action: 'read',
			tipe:identity,
			id: id
		}
		$.ajax({
			type: "POST",
			url: url,
			data: data,
			dataType: 'json',
			cache: false,
			beforeSend: function () {},
			success: function (d) {
				if (parseInt(d.status) == 1) {
					/* Success Message */
					$("#form-trans input[name='id_document']").val(d.result.journal_id);
					$("#form-trans input[name='journal_session']").val(d.result.journal_session);            
					var dd = d.result.journal_date.substr(8,2);
					var mm = d.result.journal_date.substr(5,2);
					var yy = d.result.journal_date.substr(0,4);
					var set_date = dd+'-'+mm+'-'+yy;            
					
					$("#form-trans input[name='tgl']").datepicker("update", set_date);             
					$("#form-trans input[name='nomor']").val(d.result.journal_number);          
					$("textarea[id='keterangan']").val(d.result.journal_note);              
					$("select[name='kontak']").append(''+
									'<option value="'+d.result.contact_id+'" data-alamat="'+d.result.contact_address+'" data-telepon="'+d.result.phone_1+'" data-email="'+d.result.email_1+'">'+
										d.result.contact_name+
									'</option>');
					$("select[name='kontak']").val(d.result.contact_id).trigger('change');
					$("select[name='account_kredit']").append(''+
									'<option value="'+d.result.account_id+'" data-kode="'+d.result.account_code+'" data-nama="'+d.result.account_name+'">'+
										d.result.account_code+' - '+d.result.account_name+'</option>');
					$("select[name='account_kredit']").val(d.result.account_id).trigger('change');
					$("select[name='cara_pembayaran']").val(d.result.journal_paid_type).trigger('change');

					loadJournalItems();
					$("#btn-new").hide();
					$("#btn-save").hide();
					$("#btn-update").show();
					$("#btn-cancel").show();
					// $("#btn-update").attr('data-id',d.result.journal_id);
					// $("#btn-print").attr('data-id',d.result.journal_id);            
					scrollUp('content');
				} else {
					notif(0, d.message);
				}
				checkDocumentExist();          
			},
			error: function (xhr, Status, err) {
				notif(0, 'Error');
			}
		});
    });

    // Update Button
    $(document).on("click","#btn-update",function(e) {
		e.preventDefault();
		var next = true;
		var id = $("#form-trans input[name='id_document']").val();
		var kode = $("#form-trans input[name='kode']");
		var nama = $("#form-trans input[name='nama']");
		
		if((id == '') || parseInt(id) == 0){
			notif(0,'Dokumen tidak ditemukan');
			next=false;
		}

		if (next == true) {
			var total_item = $("input[id='total_item']").val();
				if (parseInt(total_item) == 0) {
				notif(0, 'Minimal satu rincian di input');
				next = false;
			}
		}      

		if(next == true){
			var total_debit = removeCommas($("#total_debit").val());
			var total_credit = removeCommas($("#total_credit").val());
			// console.log(total_debit+','+total_credit);
			if(parseFloat(total_debit) !== parseFloat(total_credit)){
				notif(0, 'Total debit & Total kredit harus imbang');
				next=false;
			}       
		}

		if(next == true){
			var trans_list = $("#table-item tbody").children().length;
			var trans_list_data = [];
			next=true;
			if(parseInt(trans_list) > 0){    
				for(var a=1; a<trans_list+1; a++){
					var account_id = $(".tr-trans-item-id:nth-child("+a+")").attr('data-account-id');
					var item_id = $(".tr-trans-item-id:nth-child("+a+")").attr('data-journal-item-id');					
					var journal_item_debit = removeCommas($(".tr-trans-item-id:nth-child("+a+") td:nth-child(2) input").val());
					var journal_item_credit = removeCommas($(".tr-trans-item-id:nth-child("+a+") td:nth-child(3) input").val());
					var push_trans = {
							'journal_item_id':item_id,
							'account_id':account_id,
							'journal_item_debit':journal_item_debit,
							'journal_item_credit': journal_item_credit,
						}
					trans_list_data.push(push_trans);
				}
				next=true;       
			}
		}

		if(next==true){
			var list_data = JSON.stringify(trans_list_data);

			var form = new FormData();
			form.append('action', 'update-opening-balance');
			form.append('journal_id',id);
			form.append('journal_item_type', identity);
			form.append('journal_item_date', $("input[id='tgl']").val());
			form.append('journal_item_note', $("textarea[id='keterangan']").val());
			form.append('journal_item_list_data', list_data);
			
			$.ajax({
				type: "POST",
				url: url,
				data: form,
				dataType: 'json',
				cache: false,
				processData: false,
				contentType: false,		 
				beforeSend:function(){},
				success:function(d){
					if(parseInt(d.status) == 1){
						// $("#btn-new").show();
						// $("#btn-save").hide();
						// $("#btn-update").hide();
						// $("#btn-cancel").hide();
						// $("#form-trans input").val(); 
						// formTransSetDisplay(1);      
						notif(1,d.message);
						index.ajax.reload(null,false);

						if(parseInt(d.journal_id) > 0){
							index.ajax.reload(null,false);
						}
					}else{
						notif(0,d.message);  
					}     
					checkDocumentExist();                   
				},
				error:function(xhr, Status, err){
					notif(0,'Error');
				}
			});
		}
    }); 

    // Delete Button
    $(document).on("click", ".btn-delete", function () {
		event.preventDefault();
		var id = $(this).attr("data-id");
		var number = $(this).attr("data-number");

		$.confirm({
			title: 'Hapus!',
			content: 'Apakah anda ingin menghapus <b>' + number + '</b> ?',
			buttons: {
				confirm: {
					btnClass: 'btn-danger',
					text: 'Ya',
					action: function () {
						var data = {
							action: 'delete',
							tipe:identity,
							id: id,
							number: number
						}
						$.ajax({
							type: "POST",
							url: url,
							data: data,
							dataType: 'json',
							success: function (d) {
							if (parseInt(d.status) == 1) {
								notif(1, d.message);
								index.ajax.reload(null,false);
							} else {
								notif(0, d.message);
							}
							checkDocumentExist();                  
							}
						});
					}
				},
				cancel: {
					btnClass: 'btn-success',
					text: 'Batal',
					action: function () {
					// $.alert('Canceled!');
					}
				}
			}
		});
    });

    // Print Button
    $(document).on("click","#btn-print",function() {
		// var id = $(this).attr("data-id");
		var id = $("#form-trans input[id='id_document']").val();
		var journal_session = $("#form-trans input[id='journal_session']").val();      
		if(journal_session.length > 0){
			var x = screen.width / 2 - 700 / 2;
			var y = screen.height / 2 - 450 / 2;
			var print_url = url_print+'/'+journal_session;
			// console.log(print_url);
			var next=true;
			if(next == true){
				var total_debit = removeCommas($("#total_debit").val());
				var total_credit = removeCommas($("#total_credit").val());
				// console.log(total_debit+','+total_credit);
				if(parseFloat(total_debit) !== parseFloat(total_credit)){
					notif(0, 'Total debit & Total kredit harus imbang');
					next=false;
				}       
			}

			if(next){        
				var win = window.open(print_url,'Print Jurnal Umum','width=700,height=485,left=' + x + ',top=' + y + '').print();
				var data = id;
				// $.post(url_print, {id:data}, function (data) {
				//     var w = window.open(print_url,'Print');
				//     w.document.open();
				//     w.document.write(data);
				//     w.document.close();
				// });
			}
		}else{
			notif(0,'Dokumen belum di buka');
		}
    }); 

    $(document).on("click", ".btn-print-all", function () {
		var id = $(this).attr("data-id");
		var action = $(this).attr('data-action');
		var request = $(this).attr('data-request');
		//alert('#btn-print-all on Click'+action+','+request);
		var x = screen.width / 2 - 700 / 2;
		var y = screen.height / 2 - 450 / 2;
		// var print_url = url_print +'/'+ action + '/' +request+ '/' + $("#start").val() + '/' + $("#end").val();
		// window.open(print_url,'_blank');
		var request = $('.btn-print-all').data('request');
		var print_url = url_print_all + '/' + $("#start").val() + '/' + $("#end").val();
		var win = window.open(print_url, 'Print', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
    });
    $(document).on("click", "#btn-add-item", function (e) {
		e.preventDefault();

		var account_label = 'account_debit_account';
		var account_note = 'account_debit_note';
		var account_total = 'account_debit_total';

		var i = $(".div-item").length;
		var dsp = '';
		var div_parent = "#div-item";
		var div_item = "#div-item-" + i;
		i = parseInt(i) + 1;

		dsp += '<div id="div-item-' + i + '" data-id="' + i + '" class="col-md-12 col-xs-12 col-sm-12 div-item"' +
			'style="padding-left: 0px;padding-right: 0px;">';
		dsp += '<div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0px;">' +
			'<div class="form-group">' +
			'<label class="form-label">Akun Biaya *</label>' +
			'<select id="'+account_label+'_'+i+'" name="'+account_label+'" class="form-control account-debit-account">' +
			'<option value="0">-- Cari Akun Biaya--</option>' +
			'</select>' +
			'</div>' +
			' </div>';
		dsp += '<div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0px;">' +
			'<div class="form-group">' +
			'<label class="form-label">Keterangan</label>' +
			'<input id="'+account_note+'_'+i+'" name="'+account_note+'" type="text" value="" class="form-control account-debit-note"' +
			'/>' +
			'</div>' +
			' </div>';
		dsp += '<div class="col-md-3 col-xs-12 col-sm-12" style="padding-left: 0px;">' +
			'<div class="form-group">' +
			'<label class="form-label">Jumlah</label>' +
			'<input id="'+account_total+'_'+i+'" name="'+account_total+'" type="text" value="" class="form-control account-debit-total"/>' +
			'</div>' +
			'</div>';
		dsp += '<div class="col-md-1 col-xs-12 col-sm-12 padding-remove-side">' +
			'<button data-id="' + i + '" data-journal-id="0" class="btn btn-add-div-item btn-success btn-small" type="button"' +
			'style="display: inline; margin-top: 20px; width: 34px; height: 27px;">' +
			'<i class="fas fa-check"></i>' +
			'</button>' +
			'<button data-id="' + i + '" data-journal-id="0" class="btn btn-remove-div-item btn-danger btn-small" type="button"' +
			'style="display: inline; margin-top: 20px; width: 34px; height: 27px;">' +
			'<i class="fas fa-trash-alt"></i>' +
			'</button>' +                    
			'</div>';
		dsp += '</div>';

		$(div_parent).append(dsp);
		// alert(div_item);
		domSelect2(account_label+'_'+i);
		domAutonumeric(account_total+'_'+i);
    });
    $(document).on("click", ".btn-remove-div-item", function () {
		var num = $(this).attr('data-id');
		$("#div-item-" + num).remove();
		// alert(num);
    });
    /*
    function domSelect2(element_id){
      $("#"+element_id).select2({
        placeholder: '--- Pilih ---',
        minimumInputLength: 0,
        ajax: {
          type: "get",
          url: "<?= base_url('search/manage');?>",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            var query = {
              search: params.term,
              tipe: 1, //1=Supplier, 2=Asuransi
              category: 0,
              source: 'accounts'
            }
            return query;
          },
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        },
        templateSelection: function (data, container) {
          // Add custom attributes to the <option> tag for the selected option
          // $(data.element).attr('data-custom-attribute', data.customValue);
          // $("input[name='satuan']").val(data.satuan);
          return data.text;
        }
      });
    }
    function domAutonumeric(element_id){
      const autoNumericOption = {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalCharacterAlternative: ',',
        decimalPlaces: 0,
        watchExternalChanges: true //!!!        
      };
      new AutoNumeric('#'+element_id, autoNumericOption);
    }
    */        
    $(document).on("click","#btn-journal",function() {
		event.preventDefault();
		var id = $("#id_document").val();   
		var date = new Date();
		// var start_date = '01-'+date.getMonth()+'-'+date.getFullYear();
		// var end_date   = date.getDate()+'-'+date.getMonth()+'-'+date.getFullYear();
		var start_date = $("#start").val();
		var end_date = $("#end").val();
		
		var url_ledger = '<?= base_url('report/report_finance_ledger/');?>'+start_date+'/'+end_date+'/';
		var order = '?format=html&order=journal_item_date&dir=asc';

		$.confirm({
			title: 'Jurnal Entri',
			columnClass: 'col-md-8 col-md-offset-2 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
			closeIcon: true,
			closeIconClass: 'fas fa-times', 
			animation:'zoom',
			closeAnimation:'bottom',
			animateFromElement:false,         
			content: function(){
				var self = this;
				var data = {
					action: 'journal',
					id:id,
					tipe: identity
				}
				return $.ajax({
					url:url,
					data:data,
					dataType: 'json',
					method: 'post',
					cache: false
				}).done(function (d){
					
					// self.setTitle('Your Title After');
					// self.setContentAppend('<br>Version: ' + d.short_name); //Json Return
					// self.setContentAppend('<img src="'+ d.icons[0].src+'" class="img-responsive" style="margin:0 auto;">'); // Image Return
					
					// Total Record dan Each Prepare  
					if(d.status > 0){
						
						// table tag
						var head = '';
						head += '<table class="table table-bordered table-striped"><tbody>';
						head += '<tr>';
						head += '<td style="padding:4px 0px!important;text-align:left"><b>Tanggal</b>&nbsp;</td>';                
						head += '<td style="padding:4px 0px!important;text-align:left"><b>Akun</b>&nbsp;</td>';
						head += '<td style="padding:4px 0px!important;text-align:left"><b>Nama</b>&nbsp;</td>';    
						head += '<td style="padding:4px 0px!important;text-align:left"><b>Keterangan</b>&nbsp;</td>';                              
						head += '<td style="padding:4px 0px!important;text-align:right">&nbsp;<b>Debit</b></td>';
						head += '<td style="padding:4px 0px!important;text-align:right">&nbsp;<b>Kredit</b></td>';              
						head += '<td class="hide" style="padding:4px 0px!important;text-align:left">&nbsp;<b>Action</b></td>';
						head += '</tr>';
						
						// table body
						var body = '';
						
						// end tag table
						var end = '</tbody></table>';
						var total_debit = 0;
						var total_credit = 0; 
						$.each(d.result, function(i,val){
						var account_code = '<a href="'+url_ledger+val.account_id+order+'" target="_blank">'+val.account_code+'</a>';
						var account_name = '<a href="'+url_ledger+val.account_id+order+'" target="_blank">'+val.account_name+'</a>';
						// table body
						body += '<tr>'+
								'<td style="padding:4px 0px!important;text-align:left;">'+ val.journal_item_date+'</b>&nbsp;<span class="hide fa fa-arrow-right"></td>'+
								'<td style="padding:4px 0px!important;text-align:left;">'+ account_code+'</b>&nbsp;<span class="hide fa fa-arrow-right"></td>'+
								'<td style="padding:4px 0px!important;text-align:left;">'+ account_name+'</b>&nbsp;<span class="hide fa fa-arrow-right"></td>'+ 
								'<td style="padding:4px 0px!important;text-align:left;">'+ val.journal_item_note+'</b>&nbsp;<span class="hide fa fa-arrow-right"></td>'+
								'<td style="padding:4px 0px!important;text-align:right;">&nbsp;'+ addCommas(val.journal_item_debit) +'&nbsp;</td>'+
								'<td style="padding:4px 0px!important;text-align:right;">&nbsp;'+ addCommas(val.journal_item_credit) +'&nbsp;</td>'+ 
								'<td class="hide" style="text-align:center;">'+
									'<button class="btn btn-primary btn-mini btn-riwayat-kartu-stok"'+
									' data-id-barang='+ val.id_barang+''+
									' data-id-lokasi='+ val.id_gudang+''+
									'>'+
									'Kartu Stok</button></td>'+
								'</tr>';
						total_debit = parseFloat(total_debit) + parseFloat(val.journal_item_debit);
						total_credit = parseFloat(total_credit) + parseFloat(val.journal_item_credit);                
						});
						body += '<tr><td colspan="4" style="padding:4px 0px!important;">&nbsp;<b>Total</b></td><td style="padding:4px 0px!important;text-align:right">&nbsp;<b>'+addCommas(total_debit)+'</b>&nbsp;</td><td style="padding:4px 0px!important;text-align:right">&nbsp;<b>'+addCommas(total_credit)+'</b>&nbsp;</td></tr>';
						// table structure
						var table = head+body+end;
						// content        
						self.setContent(table);
					}        
				}).fail(function(){
					self.setContent('Something went wrong, Please try again.');
				});
			},
			onContentReady: function(){
				// this.setContentAppend('<div>Apakah anda ingin menghapus <b>'+number+'</b> ?</div>');
			},
			buttons: {
				button_2: {
					text: 'Tutup',
					btnClass: 'btn-default',
					keys: ['Escape'],
					action: function(){
					// Close 
					}
				}
			}
		});
    });   
    function addCommas(string){
		string += '';
		var x = string.split('.');
		var x1 = x[0];
		var x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
    }
    function removeCommas(string){

      	return string.split(',').join("");
    }
    function loadJournalItems(journal_ids = 0){
		$("#table-item tbody").html('');    
		var journal_id = $("#id_document").val();
		// console.log('Load Journal: '+journal_id);
		if(parseInt(journal_id) > 0){
			var data = {
				action: 'load-account-for-opening-balance',
				tipe: identity,
				journal_id:journal_id
			};
		}else{
			var data = {
				action: 'load-account-for-opening-balance',
				tipe: identity
			};    
		}

		var next=true;
		if(next==true){  
			$.ajax({
				type: "POST",
				url: url,
				data: data,
				dataType: 'json',
				cache: 'false',    
				beforeSend:function(){},
				success:function(d){
					if (parseInt(d.status) === 1){ //Success
						notif(1,d.message);
						$("#div-form-trans").show(300);          
						var total_records = d.total_records;
						if(parseInt(total_records) > 0){
							var dsp = '';
							var total_recordss = parseInt(d.total_records.length);
							// console.log(total_recordss);
							$("#div-item").html('');
							for(var a=0; a < total_records; a++) {
								var account_label = 'account_debit_account';
								var account_note = 'account_debit_note';
								var account_total = 'account_debit_total';                  
								var i = a;
								dsp += '<tr class="tr-trans-item-id" data-account-id="'+d.result[a]['account_id']+'" data-journal-item-id="'+d.result[a]['journal_item_id']+'">';

									dsp += '<td>'+d.result[a]['account_code']+'&nbsp&nbsp;'+
											''+d.result[a]['account_name'];
									dsp +='</td>';
									dsp += '<td style="text-align:right;"><input id="debit" class="debit" name"debit" type="text" value="'+d.result[a]['journal_item_debit']+'" style="text-align:right;"></td>';
									dsp += '<td style="text-align:right;"><input id="credit" class="credit" name"credit" type="text" value="'+d.result[a]['journal_item_credit']+'" style="text-align:right;"></td>';                    
									dsp += '<td>';
									// dsp += '<button type="button" class="btn-edit-item btn btn-mini btn-primary" data-journal-id="'+d.result[a]['journal_item_journal_id']+'" data-journal-item-id="'+d.result[a]['journal_item_id']+'" data-journal-item-account-id="'+d.result[a]['account_id']+'" data-nama="'+d.result[a]['account_name']+'" data-kode="'+d.result[a]['account_code']+'" data-journal-item-debit="'+d.result[a]['journal_item_debit']+'" data-journal-item-credit="'+d.result[a]['journal_item_credit']+'" data-journal-item-note="'+d.result[a]['journal_item_note']+'">';
									// dsp += '<span class="fas fa-edit"></span>';
									// dsp += '</button>';                  
									// dsp += '<button type="button" class="btn-delete-item btn btn-mini btn-danger" data-journal-item-id="'+d.result[a]['journal_item_id']+'" data-nama="'+d.result[a]['account_name']+'" data-kode="'+d.result[a]['account_code']+'">';
									// dsp += '<span class="fas fa-trash-alt"></span>';
									// dsp += '</button>';
									dsp += '</td>';                  
								dsp += '</tr>';

								// dsp += '<div id="div-item-' + i + '" data-id="' + i + '" class="col-md-12 col-xs-12 col-sm-12 div-item"' +
								//   'style="padding-left: 0px;padding-right: 0px;">';
								// dsp += '<div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0px;">' +
								//   '<div class="form-group">' +
								//   '<label class="form-label">Akun Biaya *</label>' +
								//   '<select id="'+account_label+'_'+i+'" name="'+account_label+'" class="form-control account-debit-account">' +
								//   '<option value="0">-- Cari Akun Biaya--</option>' +
								//   '</select>' +
								//   '</div>' +
								//   ' </div>';
								// dsp += '<div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0px;">' +
								//   '<div class="form-group">' +
								//   '<label class="form-label">Keterangan</label>' +
								//   '<input id="'+account_note+'_'+i+'" name="'+account_note+'" type="text" value="'+d.result[i].journal_item_note+'" class="form-control account-debit-note"' +
								//   '/>' +
								//   '</div>' +
								//   ' </div>';
								// dsp += '<div class="col-md-3 col-xs-12 col-sm-12" style="padding-left: 0px;">' +
								//   '<div class="form-group">' +
								//   '<label class="form-label">Jumlah</label>' +
								//   '<input id="'+account_total+'_'+i+'" name="'+account_total+'" type="text" value="'+addCommas(d.result[i].journal_item_debit)+'" class="form-control account-debit-total"/>' +
								//   '</div>' +
								//   '</div>';
								// dsp += '<div class="col-md-1 col-xs-12 col-sm-12 padding-remove-side">' +
								//   '<button data-id="' + i + '" data-journal-id="0" class="btn btn-add-div-item btn-success btn-small" type="button"' +
								//   'style="display: inline; margin-top: 20px; width: 34px; height: 27px;">' +
								//   '<i class="fas fa-check"></i>' +
								//   '</button>' +
								//   '<button data-id="' + i + '" data-journal-id="0" class="btn btn-remove-div-item btn-danger btn-small" type="button"' +
								//   'style="display: inline; margin-top: 20px; width: 34px; height: 27px;">' +
								//   '<i class="fas fa-trash-alt"></i>' +
								//   '</button>' +                    
								//   '</div>';
								// dsp += '</div>';
								// $("#div-item").append(dsp);                 
								// domSelect2(account_label+'_'+i);
								// domAutonumeric(account_total+'_'+i);
							}


							// for
							// alert('here');
							$("#table-item tbody").html(dsp);
							$("#total_item").val(d.total_records);
							// $("#subtotal").val(d.subtotal);
							// $("#total_diskon").val(d.total_diskon);
							$("#total_debit").val(d.total_debit);
							$("#total_credit").val(d.total_credit);                
							// $("#btn-cancel").css('display','inline');
							// $("#btn-save").css('display','inline'); 

							// $("#label-subtotal").html(d.total);
							// $("#label-total").html(d.total);          
						}else{
							$("#table-item tbody").html('');
							$("#table-item tbody").html('<tr><td colspan="5">- Tidak ada item -</td></tr>'); 
						}          

							// new AutoNumeric('#debit', autoNumericOption);
							// new AutoNumeric('#credit', autoNumericOption);							
					}else{ //No Data
						// notif(1,d.message);
						$("#table-item tbody").html('');
						$("#table-item tbody").html('<tr><td colspan="5">- Tidak ada item -</td></tr>'); // 
						$("#total_item").val(0);
						$("#total_debit").val('0.00');
						$("#total_credit").val('0.00');            
					}            
				},
				error:function(xhr, Status, err){
					// notif(0,err);
				}
			});
		}  
    }
    function formTransNew() {
		formTransSetDisplay(0);
		$("#form-trans input").not("input[id='tipe']").not("input[id='tgl']").not("input[id='tgl_tempo']").val('');
		// $("#btn-new").hide();
		$("#btn-save").show();
		$("#btn-cancel").show();
		$("#form-trans input[id='id_document']").val(0);   
		$("#form-trans input[id='journal_session']").val(0); 
		loadJournalItems();   		          
    }
    function formTransCancel() {
		formTransSetDisplay(1);
		$("#form-trans input").not("input[id='tipe']").not("input[id='tgl']").not("input[id='tgl_tempo']").val('');
		// $("#btn-new").show();
		// $("#btn-save").hide();
		// $("#btn-update").hide();
		// $("#btn-cancel").hide();
		$("select[id='account_debit_account']").val(0).trigger('change');
		$("#form-trans input[id='id_document']").val(0);
		$("#form-trans input[id='journal_session']").val(0);          
		$("#btn-new").show();  
		// loadJournalItems();        
    }
});

    function checkDocumentExist(){
		var id = $("#id_document").val();
		// alert(id);
		if(parseInt(id) > 0){
			$("#btn-new").show();
			$("#btn-save").hide();
			$("#btn-update").show();
			$("#btn-cancel").show(); 
			$("#btn-print").show();             
		}else{
			$("#btn-update").hide();
			$("#btn-print").hide();        
		}    
    }
    function formTransItemCancel() {
		$("input[id='account_debit_note']").val('');
		$("input[id='account_debit_total']").val(''); 
		$("input[id='account_credit_total']").val('');          
		// $("#btn-new").show();
		// $("#btn-save").hide();
		// $("#btn-update").hide();
		// $("#btn-cancel").hide();
		$("select[id='account_debit_account']").val(0).trigger('change');
    }  
    function formTransSetDisplay(value) { // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
		if (value == 1) {
			var flag = true;
		} else {
			var flag = false;
		}
		//Attr Input yang perlu di setel
		var form = '#form-trans';
		var attrInput = [
			// "nomor",
			// "jumlah",
			"keterangan"
		];

		for (var i = 0; i <= attrInput.length; i++) {
			$("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
		}

		//Attr Textarea yang perlu di setel
		/*
		var attrText = [
			"keterangan"
		];
		for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }
		*/

		//Attr Select yang perlu di setel 
		var atributSelect = [
			"kontak",
			"cara_pembayaran",
			"account_debit_account",
			"account_kredit"
		];
		for (var i = 0; i <= atributSelect.length; i++) {
			$("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
		}
    }
</script>