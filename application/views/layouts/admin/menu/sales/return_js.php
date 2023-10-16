<script>
    $(document).ready(function () {
        // var csrfData = {};
        // csrfData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
        // var csrfData = '<?php echo $this->security->get_csrf_hash(); ?>';
        // $.alert('update belum bisa hitung ulang jika ganti nominal pembelian');
        // $("body").condensMenu();  
        // $('#sidebar .start .sub-menu').css('display','none');
        // $("#modal-trans-save").modal({backdrop: 'static', keyboard: false});
        // $.alert('Btn Print Belum Tersedia');
        // Auto Set Navigation
        var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="sales/return"]').addClass('active');

        var url = "<?= base_url('transaksi/manage'); ?>";
        var url_print = "<?= base_url('transaksi/prints'); ?>";
        var url_print_trans = "<?= base_url('transaksi/print'); ?>";
        // var url_print_all = "<?= base_url('report/report_operasional'); ?>";
        var post_contact = "<?php echo $post_contact; ?>";
        // console.log(post_contact); 
        // $("select").select2();
        var operator = "<?php echo $operator; ?>";

        var counter = true;
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

        $("#trans_date").datepicker({
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
        // new AutoNumeric('.form-trans-item-input', autoNumericOption);
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
                    d.kontak = $("#filter_kontak").find(':selected').val();
                    // d.paid_type = $("#filter_paid_type").find(':selected').val();
                    // d.account = $("#filter_account").find(':selected').val();
                    d.search = {
                        value: $("#filter_search").val()
                    };
                    // d.csrf_token = csrfData;  
                    // d.user_role =  $("#select_role").val();
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": [3]
                }, {
                    "searchable": false,
                    "orderable": true,
                    "targets": [1, 2]
                }],
            "order": [
                [0, 'asc']
            ],
            "columns": [
                {'data': 'trans_date_format'
                }, {
                    'data': 'trans_number',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<a class="btn-edit" data-id="' + row.trans_id + '" style="cursor:pointer;">';
                        dsp += '<span class="fas fa-file-alt"></span>&nbsp;' + row.trans_number;
                        dsp += '</a>';
                        return dsp;
                    },
                }, {
                    'data': 'contact_name',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<a class="btn-contact-info" data-id="' + row.trans_contact_id + '" data-type="trans" data-trans-type="3" style="cursor:pointer;">';
                        dsp += '<span class="hide fas fa-user-tie"></span>&nbsp;' + row.contact_name;
                        dsp += '</a>';
                        return dsp;
                    }
                }, {'data': 'trans_note'
                }, {
                    'data': 'trans_total',
                    className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<a class="btn-trans-item-info" data-id="' + row.trans_id + '" data-session="' + row.trans_session + '" data-type="trans" style="cursor:pointer;">';
                        dsp += addCommas(row.trans_total);
                        dsp += '</a>';
                        return dsp;
                    }
                }, {
                    'data': 'trans_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // disp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';

                        // dsp += '<button class="btn-journal btn btn-mini btn-primary" data-id="'+ data +'">';
                        // dsp += '<span class="fas fa-edit"></span> Jurnal';
                        // dsp += '</button>';

                        dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="' + data + '">';
                        dsp += '<span class="fas fa-edit"></span> Lihat';
                        dsp += '</button>';

                        // dsp += '<button class="btn-print btn btn-mini btn-primary" data-id="' + data +
                        //   '" data-number="' + row.journal_number + '">';
                        // dsp += '<span class="fas fa-print"></span> Print';
                        // dsp += '</button>';

                        dsp += '&nbsp;<button class="btn-delete btn btn-mini btn-danger" data-id="' + data +
                                '" data-number="' + row.trans_number + '">';
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
        $("#table-data_filter").css('display', 'none');
        $("#table-data_length").css('display', 'none');
        $("#filter_length").on('change', function (e) {
            var value = $(this).find(':selected').val();
            $('select[name="table-data_length"]').val(value).trigger('change');
            index.ajax.reload();
        });
        $("#filter_search").on('input', function (e) {
            var ln = $(this).val().length;
            if (parseInt(ln) > 3) {
                index.ajax.reload();
            }
        });
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
        formTransNew();
        // formTransItemSetDisplay(0);
        if (operator.length > 0) {
            $("#div-form-trans").show(300);
        }

        if (parseInt(post_contact) > 0) {
            $("#div-form-trans").show(300);
            // loadContact(post_contact);
        }
        // loadJournalItems();
        /*
         $('#kontak').select2({
         placeholder: '--- Pilih ---',
         minimumInputLength: 0,
         ajax: {
         type: "get",
         url: "<?= base_url('search/manage'); ?>",
         dataType: 'json',
         delay: 250,
         data: function (params) {
         var query = {
         search: params.term,
         tipe: 1, //1=Supplier, 2=Asuransi
         source: 'contacts'
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
         escapeMarkup: function(markup){ 
         return markup; 
         },
         templateResult: function(datas){ //When Select on Click
         if (!datas.id) { return datas.text; }
         if($.isNumeric(datas.id) == true){
         // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
         return datas.text;          
         }else{
         return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;          
         }  
         },
         templateSelection: function(datas) { //When Option on Click
         if (!datas.id) { return datas.text; }
         //Custom Data Attribute
         $(datas.element).attr('data-alamat', datas.alamat);
         $(datas.element).attr('data-telepon', datas.telepon);
         $(datas.element).attr('data-email', datas.email);            
         return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
         }
         });
         */
        /*
         $('#account_debit_account').select2({
         placeholder: '--- Pilih ---',
         minimumInputLength: 0,
         ajax: {
         type: "get",
         url: "<?= base_url('search/manage'); ?>",
         dataType: 'json',
         delay: 250,
         data: function (params) {
         var query = {
         search: params.term,
         tipe: 1, //1=Supplier, 2=Asuransi
         category: 0,
         source: 'accounts',
         group: 5
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
         escapeMarkup: function(markup){ 
         return markup; 
         },
         templateResult: function(datas){ //When Select on Click
         if (!datas.id) { return datas.text; }
         return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
         },
         templateSelection: function(datas) { //When Option on Click
         if (!datas.id) { return datas.text; }
         //Custom Data Attribute         
         return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
         }
         });
         */
        /*
         $('#account_debit_account, #e_account').select2({
         placeholder: '--- Pilih ---',
         minimumInputLength: 0,
         ajax: {
         type: "get",
         url: "<?= base_url('search/manage'); ?>",
         dataType: 'json',
         delay: 250,
         data: function (params) {
         var query = {
         search: params.term,
         tipe: 1, //1=Supplier, 2=Asuransi
         // category: 0,
         source: 'accounts',
         group: 5
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
         escapeMarkup: function(markup){ 
         return markup; 
         },
         templateResult: function(datas){ //When Select on Click
         if (!datas.id) { return datas.text; }
         if($.isNumeric(datas.id) == true){
         // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
         return datas.text;          
         }else{
         return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;    
         }          
         },
         templateSelection: function(datas) { //When Option on Click
         if (!datas.id) { return datas.text; }
         //Custom Data Attribute         
         return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
         }
         });    
         $('#account_kredit').select2({
         placeholder: '--- Pilih ---',
         minimumInputLength: 0,
         ajax: {
         type: "get",
         url: "<?= base_url('search/manage'); ?>",
         dataType: 'json',
         delay: 250,
         data: function (params) {
         var query = {
         search: params.term,
         source: 'accounts',
         group:1,
         group_sub:3
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
         escapeMarkup: function(markup){ 
         return markup; 
         },
         templateResult: function(datas){ //When Select on Click
         if (!datas.id) { return datas.text; }
         // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
         if($.isNumeric(datas.id) == true){
         // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
         return datas.text;          
         }else{
         // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;    
         }           
         },
         templateSelection: function(datas) { //When Option on Click
         if (!datas.id) { return datas.text; }
         //Custom Data Attribute         
         return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
         }
         });       
         $('#filter_account').select2({
         placeholder: '--- Pilih ---',
         minimumInputLength: 0,
         ajax: {
         type: "get",
         url: "<?= base_url('search/manage'); ?>",
         dataType: 'json',
         delay: 250,
         data: function (params) {
         var query = {
         search: params.term,
         source: 'accounts',
         group:1,
         group_sub:3
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
         escapeMarkup: function(markup){ 
         return markup; 
         },
         templateResult: function(datas){ //When Select on Click
         if (!datas.id) { return datas.text; }
         // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
         if($.isNumeric(datas.id) == true){
         // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
         return datas.text;          
         }else{
         // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;    
         }           
         },
         templateSelection: function(datas) { //When Option on Click
         if (!datas.id) { return datas.text; }
         //Custom Data Attribute         
         return datas.text;
         }
         });
         */
        $('#filter_kontak').select2({
            //dropdownParent:$("#modal-id"), //If Select2 Inside Modal
            placeholder: '<i class="fas fa-search"></i> Search',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1, //1=Supplier, 2=Asuransi
                        source: 'contacts'
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
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                if ($.isNumeric(datas.id) == true) {
                    return '<i class="fas fa-user-check ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute
                $(datas.element).attr('data-alamat', datas.alamat);
                $(datas.element).attr('data-telepon', datas.telepon);
                $(datas.element).attr('data-email', datas.email);
                if ($.isNumeric(datas.id) == true) {
                    return '<i class="fas fa-user-check ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
                }
            }
        });
        // $('#cara_pembayaran').select2();
        /*
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
         */
        $(document).on("change", "#filter_kontak", function (e) {
            // index.ajax.reload();
        });
        /*
         $(document).on("change","#filter_paid_type",function(e) {
         index.ajax.reload();
         });    
         $(document).on("change","#filter_account",function(e) {
         index.ajax.reload();
         });
         */
        // New Button
        $(document).on("click", "#btn-new", function (e) {
            formTransNew();
            // $("#div-form-trans").show(300);
            $("#div-form-trans").show(300);
            $(this).hide();
        });

        $(document).on("click", "#btn-cancel", function (e) {
            e.preventDefault();
            // formTransCancel();
            $("#div-form-trans").hide(300);
        });

        // Save Button
        $(document).on("click", "#btn-save", function (e) {
            e.preventDefault();
            var next = true;
            var id_document = $("input[id=id_document]").val();

            if (parseInt(id_document) > 0) {
                notif(0, 'Silahkan refresh halaman ini');
                next = false;
            }

            if (next == true) {
                if ($("select[id='kontak']").find(':selected').val() == 0) {
                    notif(0, 'Supplier harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                // var total_item = $("input[id='total_item']").val();
                // if (parseInt(total_item) == 0) {
                //   notif(0, 'Minimal satu rincian di input');
                //   // next = false;
                // }
            }

            if (next == true) {
                var trans_list = $("#table-item-tbody").children().length;
                var trans_list_data = [];
                next = true;
                if (parseInt(trans_list) > 0) {
                    for (var a = 1; a < trans_list + 1; a++) {
                        var push_trans = {
                            'trans_item_id': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").attr('data-trans-item-id'),
                            'trans_item_ref': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").attr('data-trans-ref'),
                            'trans_item_qty': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").attr('data-trans-item-qty'),
                            'trans_item_unit': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").attr('data-trans-item-unit'),
                            'trans_item_price': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").attr('data-trans-item-price'),
                            'trans_item_sell_price': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").attr('data-trans-item-sell-price'),
                            'trans_item_product_id': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").attr('data-trans-item-product-id'),
                            'trans_item_product_type': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").attr('data-trans-item-product-type'),
                            'trans_item_location': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").attr('data-trans-item-location'),
                            'trans_item_ppn': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").attr('data-trans-item-ppn'),
                            'trans_item_ppn_value': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").attr('data-trans-item-ppn-value'),
                            'qty_input': $(".tr-trans-item-id:nth-child(" + a + ") td:nth-child(4) input").val(),
                        }
                        trans_list_data.push(push_trans);
                    }
                    // console.log(push_trans);  
                }
            }

            if (next == true) {
                if (parseFloat(removeCommas($("input[id='total']").val())) < 1) {
                    next = false;
                    notif(0, 'Jumlah harus diisi');
                }
            }
            if (next == true) {
                //Prepare all Data
                var kontak = $("select[id='kontak']").find(':selected').val()
                var prepare = {
                    tipe: identity,
                    tgl: $("input[id='tgl']").val(),
                    nomor: $("input[id='nomor']").val(),
                    kontak: kontak,
                    keterangan: $("textarea[id='keterangan']").val(),
                    trans_list: trans_list_data,
                    trans_source_id: $("#btn-print-trans-source").attr('data-trans-id'),
                    jumlah_dpp: removeCommas($("input[id='total_dpp']").val()),
                    jumlah_ppn: removeCommas($("input[id='total_ppn']").val()),
                    jumlah: removeCommas($("input[id='total']").val())
                }
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'create-return',
                    data: prepare_data
                };
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
                            notif(1, d.message);
                            $("input[id=id_document]").val(d.result.trans_id);
                            // alert(d.result.journal_id);
                            $("input[id=nomor]").val(d.result.trans_number);
                            index.ajax.reload();
                            $(".btn-print").attr('data-id', d.result.trans_id);
                            $(".btn-print").attr('data-number', d.result.trans_number);
                            // formTransNew();
                            // formTransSetDisplay(0);
                            // loadJournalItems(d.result.journal_id,kontak);
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
                action: 'read-return',
                tipe: identity,
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
                        $("#form-trans input[name='id_document']").val(d.result.trans_id);
                        var dd = d.result.trans_date.substr(8, 2);
                        var mm = d.result.trans_date.substr(5, 2);
                        var yy = d.result.trans_date.substr(0, 4);
                        var set_date = dd + '-' + mm + '-' + yy;

                        var dd2 = d.result_source.trans_source_date.substr(8, 2);
                        var mm2 = d.result_source.trans_source_date.substr(5, 2);
                        var yy2 = d.result_source.trans_source_date.substr(0, 4);
                        var set_date2 = dd2 + '-' + mm2 + '-' + yy2;

                        $("#form-trans input[name='trans_date']").datepicker("update", set_date);
                        $("#form-trans input[name='tgl']").datepicker("update", set_date2);

                        $("#btn-print-trans-source").attr('data-trans-session', d.result_source.trans_source_session);
                        $("#btn-print-trans-source").attr('data-trans-id', d.result_source.trans_source_id);
                        $("#btn-print-trans-source").html('<i class="fas fa-file-alt">&nbsp;' + d.result_source.trans_source_number + '</i>');

                        $("#form-trans input[name='nomor']").val(d.result.trans_number);

                        $("select[name='kontak']").append('' +
                                '<option value="' + d.result.contact_id + '" data-alamat="' + d.result.contact_address + '" data-telepon="' + d.result.phone_1 + '" data-email="' + d.result.email_1 + '">' +
                                d.result.contact_name +
                                '</option>');
                        $("select[name='kontak']").val(d.result.contact_id).trigger('change');
                        $("textarea[id='keterangan']").val(d.result.trans_note);
                        $("textarea[id='trans_contact_address']").val(d.result.trans_contact_address);
                        $("#form-trans input[name='trans_contact_phone']").val(d.result.trans_contact_phone);
                        $("#form-trans input[name='trans_contact_email']").val(d.result.trans_contact_email);

                        // loadJournalItems();
                        loadTransItems(d.result.trans_id, d.result.contact_id);
                        $("#div-form-trans").show(300);
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
        $(document).on("click", "#btn-update", function (e) {
            e.preventDefault();
            var next = true;
            var id = $("#form-trans input[name='id_document']").val();
            var kode = $("#form-trans input[name='kode']");
            var nama = $("#form-trans input[name='nama']");

            if ((id == '') || parseInt(id) == 0) {
                notif(0, 'Dokumen tidak ditemukan');
                next = false;
            }

            if (next == true) {
                if ($("select[id='kontak']").find(':selected').val() == 0) {
                    notif(0, 'Penerima harus diisi');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='cara_pembayaran']").find(':selected').val() == 0) {
                    notif(0, 'Cara Pembayaran harus dipilih');
                    next = false;
                }
            }
            // if(next == true){
            //   var total_debit = removeCommas($("#total_debit").val());
            //   var total_credit = removeCommas($("#total_credit").val());
            //    // console.log(total_debit+','+total_credit);
            //   if(parseFloat(total_debit) !== parseFloat(total_credit)){
            //     notif(0, 'Total debit & Total kredit harus imbang');
            //     next=false;
            //   }       
            // }
            /*
             if(next == true){
             var trans_list = $("#table-item-tbody").children().length;
             var trans_list_data = [];
             next=true;
             if(parseInt(trans_list) > 0){
             for(var a=1; a<trans_list+1; a++){
             
             var trans_number = $(".tr-trans-item-id:nth-child("+a+") td:nth-child(6) input").attr('data-trans-number');
             var trans_total = removeCommas($(".tr-trans-item-id:nth-child("+a+") td:nth-child(6) input").attr('data-trans-total'));
             var trans_sisa = removeCommas($(".tr-trans-item-id:nth-child("+a+") td:nth-child(6) input").attr('data-trans-total-sisa'));
             var trans_paid = removeCommas($(".tr-trans-item-id:nth-child("+a+") td:nth-child(6) input").val());
             var total_check = parseFloat(trans_paid)-parseFloat(trans_sisa);            
             // console.log(total_check);
             if(parseFloat(total_check) > 0){
             notif(0,'Jumlah '+ trans_number +' tidak boleh lebih dari tagihan');
             next=false;
             }else{
             var push_trans = {
             'journal_item_id':$(".tr-trans-item-id:nth-child("+a+") td:nth-child(6) input").attr('data-journal-item-id'),
             'trans_id':$(".tr-trans-item-id:nth-child("+a+") td:nth-child(6) input").attr('data-trans-id'),
             'trans_number': trans_number,
             'trans_total': trans_total,
             'trans_total_sisa':trans_sisa,
             'trans_total_paid': trans_paid,
             }
             trans_list_data.push(push_trans);
             }
             }
             }
             }
             */
            if (next == true) {
                var kontak = $("#kontak").find(':selected').val();
                var prepare = {
                    tipe: identity,
                    id: id,
                    tgl: $("input[id='tgl']").val(),
                    nomor: $("input[id='nomor']").val(),
                    kontak: $("select[id='kontak']").find(':selected').val(),
                    akun: $("select[id='account_kredit']").find(':selected').val(),
                    keterangan: $("textarea[id='keterangan']").val(),
                    cara_pembayaran: $("select[id='cara_pembayaran']").find(':selected').val(),
                    // trans_list : trans_list_data,
                    // jumlah: removeCommas($("input[id='total']").val())                   
                }
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'update',
                    data: prepare_data
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    cache: false,
                    dataType: "json",
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) == 1) {
                            // $("#btn-new").show();
                            // $("#btn-save").hide();
                            // $("#btn-update").hide();
                            // $("#btn-cancel").hide();
                            // $("#form-trans input").val(); 
                            // formTransSetDisplay(1);      
                            notif(1, d.message);
                            loadJournalItems(id, kontak);
                            index.ajax.reload(null, false);
                        } else {
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
                                tipe: identity,
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
                                        index.ajax.reload(null, false);
                                        // formTransCancel();
                                        formTransNew();
                                        $("#div-form-trans").hide(300);
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
        $(document).on("click", "#btn-print", function () {
            // var id = $(this).attr("data-id");
            var id = $("#form-trans input[id='id_document']").val();
            if (parseInt(id) > 0) {
                var x = screen.width / 2 - 700 / 2;
                var y = screen.height / 2 - 450 / 2;
                var print_url = url_print + '/' + id;
                // console.log(print_url);
                var next = true;
                // if(next == true){
                //   var total_debit = removeCommas($("#total_debit").val());
                //   var total_credit = removeCommas($("#total_credit").val());
                //    // console.log(total_debit+','+total_credit);
                //   if(parseFloat(total_debit) !== parseFloat(total_credit)){
                //     notif(0, 'Total debit & Total kredit harus imbang');
                //     next=false;
                //   }       
                // }

                if (next) {
                    var win = window.open(print_url, 'Print Kirim Uang', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
                    var data = id;
                }
            } else {
                notif(0, 'Dokumen belum di buka');
            }
        });
        $(document).on("click", "#btn-print-trans-source", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-trans-id');
            var session = $(this).attr('data-trans-session');
            if (parseInt(id) > 0) {
                var x = screen.width / 2 - 700 / 2;
                var y = screen.height / 2 - 450 / 2;
                var print_url = url_print_trans + '/' + session;
                var next = true;

                if (next) {
                    var win = window.open(print_url, 'Print', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
                    var data = id;
                }
            } else {
                notif(0, 'Dokumen belum di buka');
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

        /*
         $(document).on("click","#btn-add-item", function (e) {
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
         $(document).on("click",".btn-remove-div-item", function () {
         var num = $(this).attr('data-id');
         $("#div-item-" + num).remove();
         // alert(num);
         });
         */
        // CRUD Detail
        /*
         $(document).on("click","#btn-save-item", function (e) { // save journal_detail    
         // $(document).on("change", ".account-debit-total", function (e) { // save journal_detail
         e.preventDefault();
         e.stopPropagation();
         var next = true;
         // var account_debit_account = $(this).parent().parent().find('div:nth-child(1)').find('.account-debit-account option:selected').val(); 
         // var account_debit_note = $(this).parent().parent().parent('div:nth-child(2)').find('.account-debit-note').val(); 
         // var account_debit_total = $(this).parent().parent().find('div:nth-child(3)').find('.account-debit-total').val();      
         var account_debit_account = $("#account_debit_account").find(':selected').val(); 
         var account_debit_note = $("#account_debit_note").val(); 
         var account_debit_total = $("#account_debit_total").val();
         var account_credit_total = $("#account_credit_total").val();                  
         // var coba = 'Account:'+account_debit_account+', Note:'+account_debit_note+', Total:'+account_debit_total;     
         
         if (next == true) {
         if ($("select[id='account_debit_account']").find(':selected').val() == 0) {
         notif(0, 'Akun Perkiraan harus dipilih dahulu');
         next = false;
         }
         }
         
         // if (next == true) {
         //   if ($("select[id='account_kredit']").find(':selected').val() == 0) {
         //     notif(0, 'Kredit harus dipilih dahulu');
         //     next = false;
         //   }
         // }
         
         // if (next == true) {
         //   if ($("input[id='account_debit_total']").val().length == 0) {
         //     notif(0, 'Debit harus diisi');
         //     next = false;
         //   }
         // }
         // if (next == true) {
         //   if ($("input[id='account_debit_total']").val().length == 0) {
         //     notif(0, 'Debit harus diisi');
         //     next = false;
         //   }
         // }        
         
         // if ($("select[id='account_debit']").find(':selected').val() == $("select[id='account_kredit']").find(
         //     ':selected').val()) {
         //   notif(0, 'Debit dan Kredit tidak boleh sama');
         //   next = false;
         // }
         
         if (next == true) {
         
         //Prepare all Data
         var journal_id = $("input[id='id_document']").val();
         // console.log(journal_id);
         var prepare = {
         tipe: identity,
         journal_id:journal_id,
         journal_account:account_debit_account,
         tgl: $("input[id='tgl']").val(),
         kredit: account_credit_total,
         debit: account_debit_total,
         keterangan: account_debit_note,
         }
         var prepare_data = JSON.stringify(prepare);
         var data = {
         action: 'create-item',
         data: prepare_data
         };
         $.ajax({
         type: "POST",
         url: url,
         data: data,
         dataType: 'json',
         cache: false,
         beforeSend: function () {},
         success: function (d) {
         if (parseInt(d.status) == 1) {
         notif(1, d.message);
         // $("#form-trans input[name=id_document]").val(d.result.order_id);
         // $("#form-trans input[name=nomor]").val(d.result.order_number);
         // index.ajax.reload();
         // $(".btn-print").attr('data-id', d.result.order_id);
         // $(".btn-print").attr('data-number', d.result.order_number);
         // formTransNew();
         formTransItemCancel();
         
         //Set Button Add/Remove
         loadJournalItems();
         $(this).attr('data-journal-id',d.result.id);
         $(this).parent().find('btn-remove-div-item').attr('data-journal-id',d.result.id);
         
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
         // Edit Item Button
         $(document).on("click",".btn-edit-item", function(e) {
         var journal_id = $(this).attr('data-journal-id');
         var journal_item_id = $(this).attr('data-journal-item-id');
         // alert(order_id);
         // var journal_number = $(this).attr('data-journal-number');
         var journal_name = $(this).attr('data-nama');
         var journal_item_account_id = $(this).attr('data-journal-item-account-id');
         var journal_item_account = $(this).attr('data-kode')+' - '+ $(this).attr('data-nama');
         var journal_item_note = $(this).attr('data-journal-item-note');
         var journal_item_total_debit = $(this).attr('data-journal-item-debit');    
         var journal_item_total_credit = $(this).attr('data-journal-item-credit');          
         // var journal_item_price = $(this).attr('data-order-item-price');
         
         if(parseInt(journal_item_id) > 0){
         setTimeout(function(){
         $("#modal-journal-item-edit").modal('show'); 
         $("#modal-journal-item-edit-title").html('Edit '+ journal_name);
         
         //Set Value to Edit Form
         $("select[name='e_account']").append(''+
         '<option value="'+journal_item_account_id+'">'+
         journal_item_account+
         '</option>');
         $("select[name='e_account']").val(journal_item_account_id).trigger('change');
         
         var e_account = $("#form-edit-item select[id='e_account']");
         var e_total_debit = $("#form-edit-item input[id='e_total_debit']");
         var e_total_credit = $("#form-edit-item input[id='e_total_credit']");          
         var e_note = $("#form-edit-item input[id='e_note']");          
         
         e_note.val(journal_item_note);
         e_total_debit.val(journal_item_total_debit);   
         e_total_credit.val(journal_item_total_credit);                      
         
         e_account.attr('disabled',false);        
         e_note.attr('readonly',false);
         e_total_debit.attr('readonly',false);
         e_total_credit.attr('readonly',false);          
         
         $("#btn-update-item").attr('data-journal-item-id',journal_item_id);
         
         }, 1000);
         }else{
         notif(0,'Item tidak ditemukan');
         }
         });  
         // Update Item Button
         $(document).on("click","#btn-update-item", function(e) {
         e.preventDefault();
         e.stopPropagation();
         console.log($(this));
         var id = $(this).attr('data-journal-item-id');
         
         var prepare = {
         tipe:identity,
         id: id,
         account: $("#form-edit-item select[id='e_account']").find(':selected').val(),
         debit: $("#form-edit-item input[id='e_total_debit']").val(),
         credit: $("#form-edit-item input[id='e_total_credit']").val(),
         keterangan: $("#form-edit-item input[id='e_note']").val()
         };
         var prepare_data = JSON.stringify(prepare);
         var data = {
         action: 'update-item',
         data: prepare_data
         };
         $.ajax({
         type: "post",
         url: url,
         data: data,
         dataType: 'json',
         cache: 'false',    
         beforeSend:function(){},
         success:function(d){
         if (parseInt(d.status) === 1){
         notif(1,d.message);
         
         var id_document = $("#form-trans input[name='id_document']").val();
         if(parseInt(id_document > 0)){
         loadJournalItems();
         }else{
         loadJournalItems();
         }
         $("#modal-journal-item-edit").modal('hide');             
         }else{ //No Data
         notif(0,d.message);
         }      
         checkDocumentExist();                  
         },
         error:function(xhr, Status, err){
         notifError(err);
         }
         });
         });
         // Delete Item Button
         $(document).on("click",".btn-delete-item",function() {
         event.preventDefault();
         var id = $(this).attr("data-journal-item-id");   
         var kode = $(this).attr("data-kode");
         var nama = $(this).attr("data-nama");       
         $.confirm({
         title: 'Hapus!',
         content: 'Apakah anda ingin menghapus <b>'+nama+'</b> ini?',
         buttons: {
         confirm:{ 
         btnClass: 'btn-danger',
         text: 'Ya',
         action: function () {
         var data = {
         action: 'delete-item',
         id:id,
         kode:kode,
         nama:nama,
         tipe:identity
         }
         $.ajax({
         type: "POST",     
         url : url,     
         data: data,
         dataType:'json',
         success:function(d){
         if(parseInt(d.status)==1){ 
         notif(1,d.message); 
         loadJournalItems();
         }else{ 
         notif(0,d.message); 
         }
         checkDocumentExist();                  
         }
         });
         }
         },
         cancel:{
         btnClass: 'btn-success',
         text: 'Batal', 
         action: function () {
         // $.alert('Canceled!');
         }
         }
         }
         });                          
         });
         */
        /*
         function domSelect2(element_id){
         $("#"+element_id).select2({
         placeholder: '--- Pilih ---',
         minimumInputLength: 0,
         ajax: {
         type: "get",
         url: "<?= base_url('search/manage'); ?>",
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
         
         $(document).on("click","#btn-save-contact",function(e) {
         e.preventDefault();
         var next = true;
         
         var kode = $("#form-master input[name='kode']");
         var nama = $("#form-master input[name='nama']");
         
         if(next==true){    
         if($("input[id='kode']").val().length == 0){
         notif(0,'Kode wajib diisi');
         $("#kode").focus();
         next=false;
         }
         }
         
         if(next==true){
         if($("input[id='nama']").val().length == 0){
         notif(0,'Nama wajib diisi');
         $("#nama").focus();
         next=false;
         }   
         }
         
         if(next==true){
         if($("input[id='telepon_1']").val().length == 0){
         notif(0,'Telepon 1 wajib diisi');
         $("#telepon_1").focus();
         next=false;
         }   
         }    
         
         if(next==true){
         if($("textarea[id='alamat']").val().length == 0){
         notif(0,'Alamat wajib diisi');
         $("#alamat").focus();
         next=false;
         }   
         }        
         
         
         if(next==true){
         var prepare = {
         tipe: 1,
         kode: $("input[id='kode']").val(),
         nama: $("input[id='nama']").val(),
         perusahaan: $("input[id='perusahaan']").val(),        
         telepon_1: $("input[id='telepon_1']").val(),
         email_1: $("input[id='email_1']").val(),    
         alamat: $("textarea[id='alamat']").val(),
         status: 1
         }
         var prepare_data = JSON.stringify(prepare);
         var data = {
         action: 'create-from-modal',
         data: prepare_data
         };
         $.ajax({
         type: "POST",     
         url: "<?php echo base_url('Kontak/manage'); ?>",
         data: data, 
         dataType:'json',
         cache: false,
         beforeSend:function(){},
         success:function(d){
         if(parseInt(d.status)==1){
         notif(1,d.message);
         formKontakNew();
         $("#modal-contact").modal('toggle');
         $("#kontak").val(0).trigger('change');
         }
         else{ //Error
         notif(0,d.message);  
         }            
         },
         error:function(xhr, Status, err){
         notif(0,'Error');
         }
         });
         }
         });  
         $(document).on("click","#btn-save-account",function(e) {
         e.preventDefault();
         var next = true;
         
         var kode = $("#form-account input[name='kode-akun']");
         var nama = $("#form-account input[name='nama-akun']");
         
         if(next==true){    
         if($("input[id='kode-akun']").val().length == 0){
         notif(0,'Kode wajib diisi');
         kode.focus();
         next=false;
         }
         }
         
         if(next==true){
         if($("input[id='nama-akun']").val().length == 0){
         notif(0,'Nama wajib diisi');
         nama.focus();
         next=false;
         }   
         }
         
         if(next==true){
         var prepare = {
         tipe: 3,
         kode: $("input[id='kode-akun']").val(),
         nama: $("input[id='nama-akun']").val(),
         }
         var prepare_data = JSON.stringify(prepare);
         var data = {
         action: 'create-from-modal',
         data: prepare_data
         };
         $.ajax({
         type: "POST",     
         url: "<?php echo base_url('konfigurasi/manage'); ?>",
         data: data, 
         dataType:'json',
         cache: false,
         beforeSend:function(){},
         success:function(d){
         if(parseInt(d.status)==1){
         notif(1,d.message);
         formAccountNew();
         $("#modal-account").modal('toggle');
         }
         else{ //Error
         notif(0,d.message);  
         }            
         },
         error:function(xhr, Status, err){
         notif(0,'Error');
         }
         });
         }
         });
         $(document).on("click",".btn-modal-transaksi",function(e) {
         e.preventDefault();
         e.stopPropagation();
         var id = $(this).attr('data-id');
         // $("#modal-riwayat").modal('toggle');
         // $("#modal-riwayat-title").html('Set Modal Title');
         // $("#modal-riwayat-body").html('Set Modal Body');   
         // $.redirect(url_print_buy,id,"GET","_blank");
         var url_print = url_print_trans+'/'+id;   
         window.open(url_print,'_blank');
         });
         $(document).on("click",".btn-modal-riwayat-pembayaran",function(e) {
         e.preventDefault();
         e.stopPropagation();
         var id = $(this).attr('data-id');
         $("#modal-riwayat").modal('toggle');
         $("#modal-riwayat-title").html('Set Modal Title');
         $("#modal-riwayat-body").html('Set Modal Body');  
         });
         */

        //Form Trans Item Input
        $(document).on("input", ".form-trans-item-input", function (e) {
            var input = $(this).val();
            var qty = $(this).attr('data-trans-item-qty');
            var qty_ready = $(this).attr('data-trans-item-qty-ready');
            // var price = $(this).attr('data-trans-item-price');
            var price = $(this).attr('data-trans-item-sell-price');
            var ref = $(this).attr('data-trans-item-ref');
            var ppn = $(this).attr('data-trans-item-ppn-value');

            var next = true;

            if (parseFloat(input) > parseFloat(qty_ready)) {
                next = false;
                notif(0, 'Qty retur melebihi');
                $(this).val(0);
            } else {
                next = true;
            }


            if (next) {
                var result = input * price;
                var result_ppn = result * (parseFloat(ppn) / 100);
                if (input > 0) {
                    result = result;
                } else {
                    result = '0.00';
                }
                $(this).parent().parent().find('.td-trans-item-total').html(addCommas(result));
                $(this).parent().parent().find('.td-trans-item-total').attr('data-value', result);
                $(this).parent().parent().find('.td-trans-item-total').attr('data-value-ppn', result_ppn);
            }
        });
        $(document).on("change", ".form-trans-item-input", function (e) {
            var total_dpp = 0;
            var total_ppn = 0;
            var total = 0;
            $('.td-trans-item-total').each(function (e) {
                total_dpp += parseFloat($(this).attr('data-value'));
                total_ppn += parseFloat($(this).attr('data-value-ppn'));
                // $(this).val(addCommas($(this).val()));
            });
            total = parseFloat(total_dpp) + parseFloat(total_ppn);
            // console.log(total);
            $('#total_dpp').val(addCommas(total_dpp));
            $('#total_ppn').val(addCommas(total_ppn));
            $('#total').val(addCommas(total));
        });
        $(document).on("click", "#btn-journal", function () {
            event.preventDefault();
            var id = $("#id_document").val();
            if (parseInt(id) > 0) {
                var date = new Date();
                // var start_date = '01-'+date.getMonth()+'-'+date.getFullYear();
                // var end_date   = date.getDate()+'-'+date.getMonth()+'-'+date.getFullYear();
                var start_date = $("#start").val();
                var end_date = $("#end").val();

                var url_ledger = '<?= base_url('report/report_finance_ledger/'); ?>' + start_date + '/' + end_date + '/';
                var order = '?format=html&order=journal_item_date&dir=asc';

                $.confirm({
                    title: 'Jurnal Entri',
                    columnClass: 'col-md-8 col-md-offset-2 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                    closeIcon: true,
                    closeIconClass: 'fas fa-times',
                    animation: 'zoom',
                    closeAnimation: 'bottom',
                    animateFromElement: false,
                    content: function () {
                        var self = this;
                        var data = {
                            action: 'journal',
                            id: id,
                            tipe: identity
                        }
                        return $.ajax({
                            url: url,
                            data: data,
                            dataType: 'json',
                            method: 'post',
                            cache: false
                        }).done(function (d) {

                            // self.setTitle('Your Title After');
                            // self.setContentAppend('<br>Version: ' + d.short_name); //Json Return
                            // self.setContentAppend('<img src="'+ d.icons[0].src+'" class="img-responsive" style="margin:0 auto;">'); // Image Return

                            // Total Record dan Each Prepare  
                            if (d.status > 0) {

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
                                $.each(d.result, function (i, val) {
                                    var account_code = '<a href="' + url_ledger + val.account_id + order + '" target="_blank">' + val.account_code + '</a>';
                                    var account_name = '<a href="' + url_ledger + val.account_id + order + '" target="_blank">' + val.account_name + '</a>';
                                    // table body
                                    body += '<tr>' +
                                            '<td style="padding:4px 0px!important;text-align:left;">' + val.journal_item_date + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                            '<td style="padding:4px 0px!important;text-align:left;">' + account_code + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                            '<td style="padding:4px 0px!important;text-align:left;">' + account_name + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                            '<td style="padding:4px 0px!important;text-align:left;">' + val.journal_item_note + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                            '<td style="padding:4px 0px!important;text-align:right;">&nbsp;' + addCommas(val.journal_item_debit) + '&nbsp;</td>' +
                                            '<td style="padding:4px 0px!important;text-align:right;">&nbsp;' + addCommas(val.journal_item_credit) + '&nbsp;</td>' +
                                            '<td class="hide" style="text-align:center;">' +
                                            '<button class="btn btn-primary btn-mini btn-riwayat-kartu-stok"' +
                                            ' data-id-barang=' + val.id_barang + '' +
                                            ' data-id-lokasi=' + val.id_gudang + '' +
                                            '>' +
                                            'Kartu Stok</button></td>' +
                                            '</tr>';
                                    total_debit = parseFloat(total_debit) + parseFloat(val.journal_item_debit);
                                    total_credit = parseFloat(total_credit) + parseFloat(val.journal_item_credit);
                                });
                                body += '<tr><td colspan="4" style="padding:4px 0px!important;">&nbsp;<b>Total</b></td><td style="padding:4px 0px!important;text-align:right">&nbsp;<b>' + addCommas(total_debit) + '</b>&nbsp;</td><td style="padding:4px 0px!important;text-align:right">&nbsp;<b>' + addCommas(total_credit) + '</b>&nbsp;</td></tr>';
                                // table structure
                                var table = head + body + end;
                                // content        
                                self.setContent(table);
                            }
                        }).fail(function () {
                            self.setContent('Something went wrong, Please try again.');
                        });
                    },
                    onContentReady: function () {
                        // this.setContentAppend('<div>Apakah anda ingin menghapus <b>'+number+'</b> ?</div>');
                    },
                    buttons: {
                        button_2: {
                            text: 'Tutup',
                            btnClass: 'btn-default',
                            keys: ['Escape'],
                            action: function () {
                                // Close 
                            }
                        }
                    }
                });
            } else {
                notif(0, 'Dokumen belum dibuka');
            }
        });
        $(document).on("click", ".btn-journal", function () {
            event.preventDefault();
            var id = $(this).attr('data-id');
            var date = new Date();
            // var start_date = '01-'+date.getMonth()+'-'+date.getFullYear();
            // var end_date   = date.getDate()+'-'+date.getMonth()+'-'+date.getFullYear();
            var start_date = $("#start").val();
            var end_date = $("#end").val();

            var url_ledger = '<?= base_url('report/report_finance_ledger/'); ?>' + start_date + '/' + end_date + '/';
            var order = '?format=html&order=journal_item_date&dir=asc';

            $.confirm({
                title: 'Jurnal Entri',
                columnClass: 'col-md-8 col-md-offset-2 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                animation: 'zoom',
                closeAnimation: 'bottom',
                animateFromElement: false,
                content: function () {
                    var self = this;
                    var data = {
                        action: 'journal',
                        id: id,
                        tipe: identity
                    }
                    return $.ajax({
                        url: url,
                        data: data,
                        dataType: 'json',
                        method: 'post',
                        cache: false
                    }).done(function (d) {

                        // self.setTitle('Your Title After');
                        // self.setContentAppend('<br>Version: ' + d.short_name); //Json Return
                        // self.setContentAppend('<img src="'+ d.icons[0].src+'" class="img-responsive" style="margin:0 auto;">'); // Image Return

                        // Total Record dan Each Prepare  
                        if (d.status > 0) {

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
                            $.each(d.result, function (i, val) {
                                var account_code = '<a href="' + url_ledger + val.account_id + order + '" target="_blank">' + val.account_code + '</a>';
                                var account_name = '<a href="' + url_ledger + val.account_id + order + '" target="_blank">' + val.account_name + '</a>';
                                // table body
                                body += '<tr>' +
                                        '<td style="padding:4px 0px!important;text-align:left;">' + val.journal_item_date + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;text-align:left;">' + account_code + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;text-align:left;">' + account_name + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;text-align:left;">' + val.journal_item_note + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;text-align:right;">&nbsp;' + addCommas(val.journal_item_debit) + '&nbsp;</td>' +
                                        '<td style="padding:4px 0px!important;text-align:right;">&nbsp;' + addCommas(val.journal_item_credit) + '&nbsp;</td>' +
                                        '<td class="hide" style="text-align:center;">' +
                                        '<button class="btn btn-primary btn-mini btn-riwayat-kartu-stok"' +
                                        ' data-id-barang=' + val.id_barang + '' +
                                        ' data-id-lokasi=' + val.id_gudang + '' +
                                        '>' +
                                        'Kartu Stok</button></td>' +
                                        '</tr>';
                                total_debit = parseFloat(total_debit) + parseFloat(val.journal_item_debit);
                                total_credit = parseFloat(total_credit) + parseFloat(val.journal_item_credit);
                            });
                            body += '<tr><td colspan="4" style="padding:4px 0px!important;">&nbsp;<b>Total</b></td><td style="padding:4px 0px!important;text-align:right">&nbsp;<b>' + addCommas(total_debit) + '</b>&nbsp;</td><td style="padding:4px 0px!important;text-align:right">&nbsp;<b>' + addCommas(total_credit) + '</b>&nbsp;</td></tr>';
                            // table structure
                            var table = head + body + end;
                            // content        
                            self.setContent(table);
                        }
                    }).fail(function () {
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                onContentReady: function () {
                    // this.setContentAppend('<div>Apakah anda ingin menghapus <b>'+number+'</b> ?</div>');
                },
                buttons: {
                    button_2: {
                        text: 'Tutup',
                        btnClass: 'btn-default',
                        keys: ['Escape'],
                        action: function () {
                            // Close 
                        }
                    }
                }
            });
        });
        function addCommas(string) {
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
        function removeCommas(string) {

            return string.split(',').join("");
        }
        function loadContact(contact_id) {
            // $.alert(contact_id);
            $("#table-item tbody").html('');
            // var journal_id = $("#id_document").val();
            // console.log('Load Journal: '+journal_id);
            if (parseInt(contact_id) > 0) {
                var data = {
                    action: 'load-account-payable',
                    tipe: identity,
                    contact_id: contact_id
                };
            } else {
                var data = {
                    action: 'load-account-payable',
                    tipe: identity
                };
            }

            var next = true;
            if (next == true) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json',
                    cache: 'false',
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) === 1) { //Success
                            notif(1, d.message);
                            $("#div-form-trans").show(300);
                            var total_records = d.total_records;

                            if (d.contact) {
                                $("select[name='kontak']").append('' +
                                        '<option value="' + d.contact.contact_id + '">' +
                                        d.contact.contact_code + ' - ' + d.contact.contact_name +
                                        '</option>');
                                $("select[name='kontak']").val(d.contact.contact_id).trigger('change');
                                $("select[name='kontak']").attr('readonly', true);
                                $("select[name='kontak']").attr('disabled', true);
                            }
                            if (parseInt(total_records) > 0) {
                                var dsp = '';
                                var total_recordss = parseInt(d.total_records.length);
                                // console.log(total_recordss);
                                $("#div-item").html('');
                                for (var a = 0; a < total_records; a++) {
                                    // var account_label = 'account_debit_account';
                                    // var account_note = 'account_debit_note';
                                    // var account_total = 'account_debit_total';                  
                                    var trans_note = '-';
                                    if (d.result[a]['trans_note'] != null) {
                                        trans_note = d.result[a]['trans_note'];
                                    }
                                    console.log('AS' + trans_note);
                                    var i = a;
                                    dsp += '<tr class="tr-trans-item-id" data-id="' + d.result[a]['trans_id'] + '">';
                                    dsp += '<td><a class="btn-modal-transaksi" href="" data-id="' + d.result[a]['trans_id'] + '"><i class="fas fa-file-alt"></i> ' + d.result[a]['trans_number'] + '</a></td>';
                                    dsp += '<td style="text-align:left;">' + trans_note + '</td>';
                                    dsp += '<td style="text-align:left;">' + d.result[a]['trans_date_due_format'] + '</td>';
                                    dsp += '<td style="text-align:right;">Rp. ' + d.result[a]['trans_total'] + '</td>';
                                    dsp += '<td style="text-align:right;"><a class="btn-modal-riwayat-pembayaran" href="#" data-id="' + d.result[a]['trans_id'] + '">Rp. ' + addCommas(d.result[a]['total_sisa']) + '</a></td>';
                                    dsp += '<td>';
                                    dsp += '<input ' +
                                            'data-trans-id="' + d.result[a]['trans_id'] + '"' +
                                            'data-trans-number="' + d.result[a]['trans_number'] + '"' +
                                            'data-trans-total="' + d.result[a]['trans_total'] + '"' +
                                            'data-trans-total-sisa="' + d.result[a]['total_sisa'] + '"' +
                                            'value="' + addCommas(d.result[a]['total_sisa']) + '"' +
                                            'name="form-trans-item-input" type="text" class="form-control form-trans-item-input">';
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
                                $("#total").val(d.total_sisa);
                                // $("#total_debit").val(d.total_debit);
                                // $("#total_credit").val(d.total_credit);                
                                // $("#btn-cancel").css('display','inline');
                                // $("#btn-save").css('display','inline'); 

                                // $("#label-subtotal").html(d.total);
                                // $("#label-total").html(d.total);          
                            } else {
                                $("#table-item tbody").html('');
                                $("#table-item tbody").html('<tr><td colspan="6">- Tidak ada item -</td></tr>');
                            }
                        } else { //No Data
                            // notif(1,d.message);
                            $("#table-item tbody").html('');
                            $("#table-item tbody").html('<tr><td colspan="6">- Tidak ada item -</td></tr>'); // 
                            $("#total_item").val(0);
                            // $("#total_debit").val('0.00');
                            // $("#total_credit").val('0.00');              
                            // $("#subtotal").val(0);
                            // $("#total_diskon").val(0);
                            $("#total").val(0);
                            // $("#btn-cancel").css('display','none');
                            // $("#btn-save").css('display','none');                

                            // $("#label-subtotal").html(0);
                            // $("#label-total").html(0);          
                        }
                        // new AutoNumeric('.form-trans-item-input', autoNumericOption);  
                    },
                    error: function (xhr, Status, err) {
                        // notif(0,err);
                    }
                });
            }
        }
        function loadTransItems(trans_id, contact_id) {
            // $.alert(contact_id);
            $("#table-item tbody").html('');
            // var journal_id = $("#id_document").val();
            // console.log('Load Journal: '+journal_id);
            if (parseInt(contact_id) > 0) {
                var data = {
                    action: 'load-trans-items',
                    tipe: identity,
                    trans_id: trans_id,
                };
            } else {
                var data = {
                    action: 'load-trans-items',
                    tipe: identity,
                    trans_id: trans_id,
                };
            }

            var next = true;
            if (next == true) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json',
                    cache: 'false',
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) === 1) { //Success
                            notif(1, d.message);
                            $("#div-form-trans").show(300);
                            var total_records = d.total_records;

                            if (d.contact) {
                                $("select[name='kontak']").append('' +
                                        '<option value="' + d.contact.contact_id + '">' +
                                        d.contact.contact_code + ' - ' + d.contact.contact_name +
                                        '</option>');
                                $("select[name='kontak']").val(d.contact.contact_id).trigger('change');
                                $("select[name='kontak']").attr('readonly', true);
                                $("select[name='kontak']").attr('disabled', true);
                            }
                            if (parseInt(total_records) > 0) {
                                var dsp = '';
                                var total_recordss = parseInt(d.total_records.length);

                                $("#table-item").html('');
                                var thead = '';
                                thead += '<thead>';
                                thead += '  <tr>';
                                thead += '    <th>Produk</th>';
                                thead += '    <th style="text-align:right;">Qty Retur</th>';
                                thead += '    <th style="text-align:left;">Satuan</th>';
                                thead += '    <th style="text-align:left;">Lokasi Penempatan</th>';
                                thead += '    <th style="text-align:right;">Harga Satuan</th>';
                                thead += '    <th style="text-align:right;">Jumlah</th>';
                                thead += '  </tr>';
                                thead += '</thead>';
                                thead += '<tbody>';
                                thead += '</tbody>';

                                $("#table-item").append(thead);

                                // console.log(total_recordss);
                                $("#div-item").html('');
                                for (var a = 0; a < total_records; a++) {
                                    // var account_label = 'account_debit_account';
                                    // var account_note = 'account_debit_note';
                                    // var account_total = 'account_debit_total';
                                    var trans_note = '-';
                                    if (d.result[a]['trans_note'] != null) {
                                        trans_note = d.result[a]['trans_note'];
                                    }
                                    var i = a;
                                    // dsp += '<tr class="tr-trans-item-id" data-id="'+d.result[a]['trans_id']+'">';
                                    //   dsp += '<td><a class="btn-modal-transaksi" href="" data-id="'+d.result[a]['trans_id']+'"><i class="fas fa-file-alt"></i> '+d.result[a]['trans_number']+'</a></td>';
                                    //   dsp += '<td style="text-align:left;">'+trans_note+'</td>';
                                    //   dsp += '<td style="text-align:left;">'+d.result[a]['trans_date_due_format']+'</td>';                   
                                    //   dsp += '<td style="text-align:right;">Rp. '+d.result[a]['trans_total']+'</td>';      
                                    //   // dsp += '<td style="text-align:right;"><a class="btn-modal-riwayat-pembayaran" href="#" data-id="'+d.result[a]['trans_id']+'">Rp. '+addCommas(d.result[a]['total_sisa'])+'</a></td>';
                                    //   dsp += '<td style="text-align:right;"><a class="btn-modal-riwayat-pembayaran" href="#" data-id="'+d.result[a]['trans_id']+'">Rp. '+d.result[a]['total_sisa']+'</a></td>';                    
                                    //   dsp += '<td>';
                                    //    dsp += '<input '+
                                    //       'data-journal-item-id="'+d.result[a]['journal_item_id']+'"'+
                                    //       'data-trans-id="'+d.result[a]['trans_id']+'"'+
                                    //       'data-trans-number="'+d.result[a]['trans_number']+'"'+
                                    //       'data-trans-total="'+d.result[a]['trans_total']+'"'+
                                    //       'data-trans-total-sisa="'+d.result[a]['total_sisa']+'"'+
                                    //       'value="'+addCommas(d.result[a]['total_paid'])+'"'+
                                    //       'name="form-trans-item-input" type="text" class="form-control form-trans-item-input" readonly>';
                                    //     // dsp += '<button type="button" class="btn-edit-item btn btn-mini btn-primary" data-journal-id="'+d.result[a]['journal_item_journal_id']+'" data-journal-item-id="'+d.result[a]['journal_item_id']+'" data-journal-item-account-id="'+d.result[a]['account_id']+'" data-nama="'+d.result[a]['account_name']+'" data-kode="'+d.result[a]['account_code']+'" data-journal-item-debit="'+d.result[a]['journal_item_debit']+'" data-journal-item-credit="'+d.result[a]['journal_item_credit']+'" data-journal-item-note="'+d.result[a]['journal_item_note']+'">';
                                    //     // dsp += '<span class="fas fa-edit"></span>';
                                    //     // dsp += '</button>';                  
                                    //     // dsp += '<button type="button" class="btn-delete-item btn btn-mini btn-danger" data-journal-item-id="'+d.result[a]['journal_item_id']+'" data-nama="'+d.result[a]['account_name']+'" data-kode="'+d.result[a]['account_code']+'">';
                                    //     // dsp += '<span class="fas fa-trash-alt"></span>';
                                    //     // dsp += '</button>';
                                    //   dsp += '</td>';                  
                                    // dsp += '</tr>';

                                    dsp += '<tr class="tr-trans-item-id" data-trans-item-id="' + d.result[a]['trans_item_id'] + '">';
                                    dsp += '  <td><a class="btn-modal-produk" href="#"';
                                    dsp += '    data-trans-item-id="' + d.result[a]['trans_item_id'] + '">';
                                    dsp += '    ' + d.result[a]['product_name'] + '</a>';
                                    dsp += '  </td>';
                                    // dsp += '  <td style="text-align:right;">'+d.result[a]['trans_item_in_qty']+'</td>';
                                    // dsp += '  <td style="text-align:right;"><?php #echo $v['qty_ready_for_return']; ?></td>';
                                    dsp += '  <td style="text-align:right;">';
                                    dsp += '    <input name="form-trans-item-input" type="text" class="form-control form-trans-item-input"';
                                    dsp += '    data-trans-item-id="' + d.result[a]['trans_item_id'] + '" ';
                                    dsp += '    data-trans-id="' + d.result[a]['trans_item_trans_id'] + '"';
                                    dsp += '    data-trans-item-qty="' + d.result[a]['trans_item_in_qty'] + '"';
                                    dsp += '    data-trans-item-price="' + d.result[a]['trans_item_in_price'] + '"';
                                    // dsp += '    data-trans-ref="'+d.result[a]['last_ref']+'"';
                                    dsp += '    data-trans-item-unit="' + d.result[a]['trans_item_unit'] + '"';
                                    dsp += '    data-trans-item-product-id="' + d.result[a]['trans_item_product_id'] + '"';
                                    dsp += '    data-trans-item-product-type="' + d.result[a]['trans_item_product_type'] + '"';
                                    // dsp += '    data-trans-item-location="'+d.result[a]['trans_item_location_id']+'"';
                                    // dsp += '    data-trans-item-qty-ready="'+d.result[a]['qty_return']+'"';      
                                    dsp += '    value="' + d.result[a]['trans_item_in_qty'] + '" readonly';
                                    dsp += '    >';
                                    dsp += '  </td>';
                                    dsp += '  <td>' + d.result[a]['trans_item_unit'] + '</td>';
                                    dsp += '  <td>' + d.result[a]['location']['location_name'] + '</td>';
                                    dsp += '  <td style="text-align:right;">';
                                    // dsp += '    <!-- <a class="btn-modal-riwayat-pembayaran" href="#" data-trans-item-id="<?php #echo $v['trans_item_id']; ?>">Rp. 1,000.00</a> -->';
                                                                        dsp += '    ' + d.result[a]['trans_item_sell_price'] + '';
                                                                        dsp += '  </td>';
                                                                        dsp += '  <td class="td-trans-item-total" style="text-align:right;" data-value="0.00">' + d.result[a]['trans_item_total'] + '</td>';
                                                                        dsp += '</tr>';

                                                                    }
                                                                    $("#table-item tbody").html(dsp);
                                                                    $("#total_item").val(d.total_records);
                                                                    $("#subtotal").val(d.subtotal);
                                                                    if (parseInt(trans_id) > 0) {
                                                                        $("#total_dpp").val(d.subtotal);
                                                                        $("#total_ppn").val(d.total_ppn);
                                                                        $("#total").val(d.total);
                                                                    } else {
                                                                        $("#total").val(d.total);
                                                                    }
                                                                } else {
                                                                    $("#table-item tbody").html('');
                                                                    $("#table-item tbody").html('<tr><td colspan="6">- Tidak ada item -</td></tr>');
                                                                }
                                                            } else { //No Data
                                                                $("#table-item tbody").html('');
                                                                $("#table-item tbody").html('<tr><td colspan="6">- Tidak ada item -</td></tr>'); // 
                                                                $("#total_item").val(0);
                                                                $("#total").val(0);
                                                            }
                                                            // new AutoNumeric('.form-trans-item-input', autoNumericOption);  
                                                        },
                                                        error: function (xhr, Status, err) {
                                                            // notif(0,err);
                                                        }
                                                    });
                                                }
                                            }
                                            function formTransNew() {
                                                formTransSetDisplay(0);
                                                $("#form-trans input").val();
                                                // $("#btn-new").hide();
                                                $("#btn-save").show();
                                                $("#btn-cancel").show();
                                                $("#form-trans input[id='id_document']").val(0);
                                            }
                                            function formTransCancel() {
                                                formTransSetDisplay(1);
                                                $("#form-trans input").val();
                                                // $("#btn-new").show();
                                                // $("#btn-save").hide();
                                                // $("#btn-update").hide();
                                                // $("#btn-cancel").hide();
                                                $("select").val(0).trigger('change');
                                                $("#form-trans input[id='id_document']").val(0);
                                                $("#btn-new").show();
                                                // loadJournalItems();        
                                                $("#table-item tbody").html('');
                                                $("#btn-print-trans-source").attr('data-trans-session', 0).attr('data-trans-id', 0);
                                                $("#btn-print-trans-source").html('-');
                                            }
                                        });

                                        function checkDocumentExist() {
                                            var id = $("#id_document").val();
                                            // alert(id);
                                            if (parseInt(id) > 0) {
                                                $("#btn-new").show();
                                                $("#btn-save").hide();
                                                $("#btn-update").show();
                                                $("#btn-cancel").show();
                                                $("#btn-print").show();
                                            } else {
                                                $("#btn-update").hide();
                                                $("#btn-print").hide();
                                            }
                                        } /*
                                         function formKontakNew(){
                                         $("#kode").val('');
                                         $("#nama").val('');
                                         $("#perusahaan").val('');
                                         $("#telepon_1").val('');
                                         $("#email_1").val('');
                                         $("#alamat").val(''); 
                                         } */
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
                                                "nomor",
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
                                                "account_debit_account",
                                            ];
                                            for (var i = 0; i <= atributSelect.length; i++) {
                                                $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
                                            }
                                        }
</script>