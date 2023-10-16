<script>
    $(document).ready(function () {
        // $.alert('JS Line : btn-update');
        // var csrfData = {};
        // csrfData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
        // var csrfData = '<?php echo $this->security->get_csrf_hash(); ?>';

        // $("body").condensMenu();  
        // $('#sidebar .start .sub-menu').css('display','none');
        // $("#modal-trans-save").modal({backdrop: 'static', keyboard: false});
        // $.alert('Filter Transfer From n To Datatable pending');
        // Auto Set Navigation
        var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="finance/bank_statement"]').addClass('active');

        var url = "<?= base_url('keuangan/manage'); ?>";
        var url_print = "<?= base_url('keuangan/print'); ?>";
        var url_print_all = "<?= base_url('report/report_operasional'); ?>";
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

        const autoNumericOption = {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalCharacterAlternative: '.',
            decimalPlaces: 2,
            watchExternalChanges: true //!!!        
        };
        new AutoNumeric('#jumlah', autoNumericOption);
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
                    d.akun_kredit = $("#filter_akun_kredit").find(':selected').val();
                    d.akun_debit = $("#filter_akun_debit").find(':selected').val();
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
            "columns": [{
                    'data': 'journal_date_format'
                }, {
                    'data': 'journal_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<a class="btn-edit" data-id="' + row.journal_id + '" style="cursor:pointer;">';
                        dsp += '<span class="fas fa-file-alt"></span>&nbsp;' + row.journal_number;
                        dsp += '</a>';
                        return dsp;
                    }
                }, {
                    'data': 'account_name',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += row.account_code+' - '+row.account_name;
                        return dsp;
                    }
                }, {
                    'data': 'journal_note'
                }, {
                    'data': 'journal_total',
                    className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += addCommas(row.journal_total);
                        return dsp;
                    }
                }, {
                    'data': 'journal_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // disp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';

                        dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="' + data + '">';
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
                }]
        });
        //Datatable Config
        $("#table-data_filter").css('display', 'none');
        $("#table-data_length").css('display', 'none');
        $("#filter_length").on('change', function (e) {
            var value = $(this).find(':selected').val();
            $('select[name="table-data_length"]').val(value).trigger('change');
            index.ajax.reload();
        });
        $("#filter_akun_kredit").on('change', function (e) {
            index.ajax.reload();
        });
        $("#filter_akun_debit").on('change', function (e) {
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
        if (operator.length > 0) {
            $("#div-form-trans").show(300);
        }

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
                        group: 1,
                        group_sub: 3
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
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;    
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute         
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });
        $('#account_debit').select2({
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
                        group: 1,
                        group_sub: 3
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
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;    
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute         
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });
        // $('#cara_pembayaran').select2();
        /*    
         $(document).on("change","#kontak",function(e) {
         e.preventDefault();
         e.stopPropagation();
         // console.log($(this));
         var this_val = $(this).find(':selected').val();
         if(this_val == '-'){
         $("#modal-contact").modal('toggle');
         formKontakNew();               
         }
         });
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
         $(document).on("change","#filter_kontak",function(e) {
         index.ajax.reload();
         });
         */
        $('#filter_akun_kredit').select2({
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
                        group: 1,
                        group_sub: 3
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
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;    
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute         
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });
        $('#filter_akun_debit').select2({
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
                        group: 1,
                        group_sub: 3
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
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;    
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute         
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
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

            if (parseInt(id_document) > 0) {
                notif(0, 'Silahkan refresh halaman ini');
                next = false;
            }

            if (next == true) {
                if ($("select[id='account_kredit']").find(':selected').val() == 0) {
                    notif(0, 'Transfer dari harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='account_debit']").find(':selected').val() == 0) {
                    notif(0, 'Setor ke harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='account_debit']").find(':selected').val() == $("select[id='account_kredit']").find(
                        ':selected').val()) {
                    notif(0, 'Tidak boleh transfer ke sesama');
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='jumlah']").val().length == 0) {
                    notif(0, 'Jumlah harus diisi');
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

            if (next == true) {

                //Prepare all Data
                var prepare = {
                    tipe: identity,
                    tgl: $("input[id='tgl']").val(),
                    nomor: $("input[id='nomor']").val(),
                    // debit: $("select[id='account_debit']").find(':selected').val(),
                    akun_kredit: $("select[id='account_kredit']").find(':selected').val(),
                    akun_debit: $("select[id='account_debit']").find(':selected').val(),
                    jumlah: $("input[id='jumlah']").val(),
                    keterangan: $("textarea[id='keterangan']").val()
                }
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'create',
                    data: prepare_data
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json',
                    cache: false,
                    beforeSend: function(){
                        $("#btn-save").attr('disabled',true);
                        notif(1,'Sedang menambahkan');
                    },
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
                            // formTransCancel();
                            $("#form-trans input[name='id_document']").attr('data-journal-item-debit', d.result_detail.journal_item_debit_id);
                            $("#form-trans input[name='id_document']").attr('data-journal-item-kredit', d.result_detail.journal_item_credit_id);
                        } else { //Error
                            notif(0, d.message);
                        }
                        $("#btn-save").attr('disabled',false);
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

                        var dd = d.result.journal_date.substr(8, 2);
                        var mm = d.result.journal_date.substr(5, 2);
                        var yy = d.result.journal_date.substr(0, 4);
                        var set_date = dd + '-' + mm + '-' + yy;

                        $("#form-trans input[name='tgl']").datepicker("update", set_date);
                        $("#form-trans input[name='nomor']").val(d.result.journal_number);
                        $("#form-trans input[name='jumlah']").val(d.result.journal_total);
                        $("textarea[id='keterangan']").val(d.result.journal_note);

                        var result_debit = d.result_detail_debit;
                        var result_credit = d.result_detail_credit;

                        $("select[name='account_debit']").append('' +
                                '<option value="' + result_debit[0].account_id + '">' +
                                result_debit[0].account_code + ' - ' + result_debit[0].account_name +
                                '</option>');
                        $("select[name='account_debit']").val(result_debit[0].account_id).trigger('change');

                        $("select[name='account_kredit']").append('' +
                                '<option value="' + result_credit[0].account_id + '">' +
                                result_credit[0].account_code + ' - ' + result_credit[0].account_name +
                                '</option>');
                        $("select[name='account_kredit']").val(result_credit[0].account_id).trigger('change');

                        $("#form-trans input[name='id_document']").val(d.result.journal_id);
                        $("#form-trans input[name='journal_session']").val(d.result.journal_session);
                        $("#form-trans input[name='id_document']").attr('data-journal-item-debit', result_debit[0].journal_item_id);
                        $("#form-trans input[name='id_document']").attr('data-journal-item-kredit', result_credit[0].journal_item_id);

                        $("#btn-new").hide();
                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-cancel").show();
                        // $("#btn-update").attr('data-id',d.result.journal_id);
                        // $("#btn-print").attr('data-id',d.result.journal_id);            
                        scrollUp('content');
                        $("#div-form-trans").show(300);
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
                if ($("select[id='account_kredit']").find(':selected').val() == 0) {
                    notif(0, 'Transfer dari harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='account_debit']").find(':selected').val() == 0) {
                    notif(0, 'Setor ke harus dipilih');
                    next = false;
                }
            }

            if ($("select[id='account_debit']").find(':selected').val() == $("select[id='account_kredit']").find(
                    ':selected').val()) {
                notif(0, 'Debit dan Kredit tidak boleh sama');
                next = false;
            }

            if (next == true) {
                if ($("input[id='jumlah']").val().length == 0) {
                    notif(0, 'Jumlah harus diisi');
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

            if (next == true) {
                var prepare = {
                    tipe: identity,
                    id: id,
                    journal_item_debit_id: $("#form-trans input[name='id_document']").attr('data-journal-item-debit'),
                    journal_item_kredit_id: $("#form-trans input[name='id_document']").attr('data-journal-item-kredit'),
                    tgl: $("input[id='tgl']").val(),
                    nomor: $("input[id='nomor']").val(),
                    keterangan: $("textarea[id='keterangan']").val(),
                    akun_kredit: $("select[id='account_kredit']").find(':selected').val(),
                    akun_debit: $("select[id='account_debit']").find(':selected').val(),
                    jumlah: $("input[id='jumlah']").val()
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
            var journal_session = $("#form-trans input[id='journal_session']").val();
            if (journal_session.length > 0) {
                var x = screen.width / 2 - 700 / 2;
                var y = screen.height / 2 - 450 / 2;
                var print_url = url_print + '/' + journal_session;
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
                    // $.post(url_print, {id:data}, function (data) {
                    //     var w = window.open(print_url,'Print');
                    //     w.document.open();
                    //     w.document.write(data);
                    //     w.document.close();
                    // });
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

        $(document).on("click", "#btn-save-account", function (e) {
            e.preventDefault();
            var next = true;

            var kode = $("#form-account input[name='kode-akun']");
            var nama = $("#form-account input[name='nama-akun']");

            if (next == true) {
                if ($("input[id='kode-akun']").val().length == 0) {
                    notif(0, 'Kode wajib diisi');
                    kode.focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='nama-akun']").val().length == 0) {
                    notif(0, 'Nama wajib diisi');
                    nama.focus();
                    next = false;
                }
            }

            if (next == true) {
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
                    dataType: 'json',
                    cache: false,
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) == 1) { /* Success Message */
                            notif(1, d.message);
                            formAccountNew();
                            $("#modal-account").modal('toggle');
                        } else { //Error
                            notif(0, d.message);
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, 'Error');
                    }
                });
            }
        });
        $(document).on("click", "#btn-journal", function () {
            event.preventDefault();
            var id = $("#id_document").val();
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
        function formTransNew() {
            formTransSetDisplay(0);
            $("#form-trans input").not("input[id='tipe']").not("input[id='tgl']").not("input[id='tgl_tempo']").val('');
            // $("#btn-new").hide();
            $("#btn-save").show();
            $("#btn-cancel").show();
            $("#form-trans input[id='id_document']").val(0);
            $("#form-trans input[id='journal_session']").val(0);
        }
        function formTransCancel() {
            formTransSetDisplay(1);
            $("#form-trans input").not("input[id='tipe']").not("input[id='tgl']").not("input[id='tgl_tempo']").val('');
            // $("#btn-new").show();
            // $("#btn-save").hide();
            // $("#btn-update").hide();
            // $("#btn-cancel").hide();
            $("account_kredit").val(0).trigger('change');
            $("account_debit").val(0).trigger('change');
            $("#form-trans input[id='id_document']").val(0);
            $("#form-trans input[id='journal_session']").val(0);
            $("#btn-new").show();
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
    }
    function formAccountNew() {
        $("#kode-akun").val('');
        $("#nama-akun").val('');
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
            "jumlah",
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
            "account_debit",
            "account_kredit"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>