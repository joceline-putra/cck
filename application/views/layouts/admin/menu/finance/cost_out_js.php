<script>
    $(document).ready(function () {
        // $.alert('JS Line : btn-edit-item, btn-update-item, btn-delete-item');
        // var csrfData = {};
        // csrfData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
        // var csrfData = '<?php echo $this->security->get_csrf_hash(); ?>';

        // $("body").condensMenu();  
        // $('#sidebar .start .sub-menu').css('display','none');
        // $("#modal-trans-save").modal({backdrop: 'static', keyboard: false});

        // Auto Set Navigation
        var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        // $(".nav-tabs").find('li[class="active"]').removeClass('active');
        // $(".nav-tabs").find('li[data-name="finance/cost_out"]').addClass('active');
        // console.log(menu_link);
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
        // new AutoNumeric('#jumlah', autoNumericOption);
        new AutoNumeric('#account_debit_total', autoNumericOption);
        // new AutoNumeric('#account_credit_total', autoNumericOption);    
        new AutoNumeric('#e_total_debit', autoNumericOption);
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
                    d.branch = $("#filter_branch").find(':selected').val();                    
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
                    },
                }, {
                    'data': 'branch_name',
                    render:function(data,meta,row){
                        var dsp = '';
                        dsp += row.branch_name;
                        return dsp;
                    }
                }, {
                    'data': 'account_name',
                    render:function(data,meta,row){
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
        $("#filter_branch").on('change', function (e) {
            index.ajax.reload();
        });        
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
            // $("#div-form-trans").show(300);
        }
        loadJournalItems();

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
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    return '<i class="fas fa-plus ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
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
                return '<i class="fas fa-user-check ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });
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
                        source: 'accounts_branch_1',
                        group: 5,
                        group_sub: 16
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
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                    // }else{
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
                        source: 'accounts_branch_1',
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

        $(document).on("change", "#kontak", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // console.log($(this));
            var this_val = $(this).find(':selected').val();
            if (this_val == '-') {
                $("#modal-contact").modal('toggle');
                formKontakNew();
            }
        });
        $(document).on("change", "#account_debit_account, #e_account", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // console.log($(this));
            var this_val = $(this).find(':selected').val();
            if (this_val == '-') {
                $("#modal-account").modal('toggle');
                formAccountNew();
            }
        });
        $(document).on("change", "#filter_kontak", function (e) {
            index.ajax.reload();
        });
        // New Button
        $(document).on("click", "#btn-new", function (e) {
            formTransNew();
            // $("#div-form-trans").show(300);
            // $("#div-form-trans").show(300);
            // $(this).hide();
            activeTab('tab2');
        });

        $(document).on("click", "#btn-cancel", function (e) {
            formTransCancel();
            // $("#div-form-trans").hide(300);
            activeTab('tab1');            
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

            // if (next == true) {
            //   if ($("select[id='account_kredit']").find(':selected').val() == 0) {
            //     notif(0, 'Bayar dari harus dipilih');
            //     next = false;
            //   }
            // }

            // if (next == true) {
            //     if ($("select[id='kontak']").find(':selected').val() == 0) {
            //         notif(0, 'Penerima harus dipilih');
            //         next = false;
            //     }
            // }

            // if (next == true) {
            //   if ($("select[id='cara_pembayaran']").find(':selected').val() == 0) {
            //     notif(0, 'Cara Pembayaran harus dipilih');
            //     next = false;
            //   }
            // }

            if (next == true) {
                var total_item = $("input[id='total_item']").val();
                if (parseInt(total_item) == 0) {
                    notif(0, 'Minimal satu rincian di input');
                    next = false;
                }
            }

            // if ($("select[id='account_debit']").find(':selected').val() == $("select[id='account_kredit']").find(
            //     ':selected').val()) {
            //   notif(0, 'Debit dan Kredit tidak boleh sama');
            //   next = false;
            // }

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
                    // kontak: $("select[id='kontak']").find(':selected').val(),
                    kontak: 2,
                    // debit: $("select[id='account_debit']").find(':selected').val(),
                    akun: $("select[id='account_kredit']").find(':selected').val(),
                    // jumlah: $("input[id='jumlah']").val(),
                    keterangan: $("textarea[id='keterangan']").val()
                    // cara_pembayaran: $("select[id='cara_pembayaran']").find(':selected').val()
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
                        } else { //Error
                            notif(0, d.message);
                        }
                        checkDocumentExist();
                        $("#btn-save").attr('disabled',false);
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
                        $("#form-trans input[name='id_document']").val(d.result.journal_id);
                        $("#form-trans input[name='journal_session']").val(d.result.journal_session);
                        var dd = d.result.journal_date.substr(8, 2);
                        var mm = d.result.journal_date.substr(5, 2);
                        var yy = d.result.journal_date.substr(0, 4);
                        var set_date = dd + '-' + mm + '-' + yy;

                        $("#form-trans input[name='tgl']").datepicker("update", set_date);
                        $("#form-trans input[name='nomor']").val(d.result.journal_number);
                        $("textarea[id='keterangan']").val(d.result.journal_note);
                        // $("select[name='kontak']").append('' +
                        //         '<option value="' + d.result.contact_id + '" data-alamat="' + d.result.contact_address + '" data-telepon="' + d.result.phone_1 + '" data-email="' + d.result.email_1 + '">' +
                        //         d.result.contact_name +
                        //         '</option>');
                        // $("select[name='kontak']").val(d.result.contact_id).trigger('change');
                        $("select[name='account_kredit']").append('' +
                                '<option value="' + d.result.account_id + '" data-kode="' + d.result.account_code + '" data-nama="' + d.result.account_name + '">' +
                                d.result.account_code + ' - ' + d.result.account_name + '</option>');
                        $("select[name='account_kredit']").val(d.result.account_id).trigger('change');
                        // $("select[name='cara_pembayaran']").val(d.result.journal_paid_type).trigger('change');

                        loadJournalItems();
                        $("#btn-new").hide();
                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-cancel").show();
                        // $("#btn-update").attr('data-id',d.result.journal_id);
                        // $("#btn-print").attr('data-id',d.result.journal_id);            
                        // scrollUp('content');
                        activeTab('tab2');
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

            // if (next == true) {
            //     if ($("select[id='kontak']").find(':selected').val() == 0) {
            //         notif(0, 'Penerima harus diisi');
            //         next = false;
            //     }
            // }

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
                    tgl: $("input[id='tgl']").val(),
                    nomor: $("input[id='nomor']").val(),
                    kontak: $("select[id='kontak']").find(':selected').val(),
                    akun: $("select[id='account_kredit']").find(':selected').val(),
                    keterangan: $("textarea[id='keterangan']").val(),
                    // cara_pembayaran: $("select[id='cara_pembayaran']").find(':selected').val()          
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
                    '<select id="' + account_label + '_' + i + '" name="' + account_label + '" class="form-control account-debit-account">' +
                    '<option value="0">-- Cari Akun Biaya--</option>' +
                    '</select>' +
                    '</div>' +
                    ' </div>';
            dsp += '<div class="col-md-4 col-xs-12 col-sm-12" style="padding-left: 0px;">' +
                    '<div class="form-group">' +
                    '<label class="form-label">Keterangan</label>' +
                    '<input id="' + account_note + '_' + i + '" name="' + account_note + '" type="text" value="" class="form-control account-debit-note"' +
                    '/>' +
                    '</div>' +
                    ' </div>';
            dsp += '<div class="col-md-3 col-xs-12 col-sm-12" style="padding-left: 0px;">' +
                    '<div class="form-group">' +
                    '<label class="form-label">Jumlah</label>' +
                    '<input id="' + account_total + '_' + i + '" name="' + account_total + '" type="text" value="" class="form-control account-debit-total"/>' +
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
            domSelect2(account_label + '_' + i);
            domAutonumeric(account_total + '_' + i);
        });
        $(document).on("click", ".btn-remove-div-item", function () {
            var num = $(this).attr('data-id');
            $("#div-item-" + num).remove();
            // alert(num);
        });
        // CRUD Detail
        $(document).on("click", "#btn-save-item", function (e) { // save journal_detail    
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
                if ($("select[id='account_debit_account']").find(':selected').val() == '-') {
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
                    journal_id: journal_id,
                    journal_account: account_debit_account,
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
                    beforeSend: function(){
                        $("#btn-save-item").attr('disabled',true);
                        notif(1,'Sedang menambahkan');                        
                    },
                    success: function (d) {
                        if (parseInt(d.status) == 1) {
                            /* Success Message */
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
                            $(this).attr('data-journal-id', d.result.id);
                            $(this).parent().find('btn-remove-div-item').attr('data-journal-id', d.result.id);

                            if (parseInt(d.journal_id) > 0) {
                                index.ajax.reload();
                            }

                        } else { //Error
                            notif(0, d.message);
                        }
                        checkDocumentExist();
                        $("#btn-save-item").attr('disabled',false);                                   
                    },
                    error: function (xhr, Status, err) {
                        notif(0, 'Error');
                    }
                });

            }
        });
        // Edit Item Button
        $(document).on("click", ".btn-edit-item", function (e) {
            var journal_id = $(this).attr('data-journal-id');
            var journal_item_id = $(this).attr('data-journal-item-id');
            // alert(order_id);
            // var journal_number = $(this).attr('data-journal-number');
            var journal_name = $(this).attr('data-nama');
            var journal_item_account_id = $(this).attr('data-journal-item-account-id');
            var journal_item_account = $(this).attr('data-kode') + ' - ' + $(this).attr('data-nama');
            var journal_item_note = $(this).attr('data-journal-item-note');
            var journal_item_total_debit = $(this).attr('data-journal-item-debit');
            var journal_item_total_credit = $(this).attr('data-journal-item-credit');
            // var journal_item_price = $(this).attr('data-order-item-price');

            if (parseInt(journal_item_id) > 0) {
                setTimeout(function () {
                    $("#modal-journal-item-edit").modal('show');
                    $("#modal-journal-item-edit-title").html('Edit ' + journal_name);

                    //Set Value to Edit Form
                    $("select[name='e_account']").append('' +
                            '<option value="' + journal_item_account_id + '">' +
                            journal_item_account +
                            '</option>');
                    $("select[name='e_account']").val(journal_item_account_id).trigger('change');

                    var e_account = $("#form-edit-item select[id='e_account']");
                    var e_total_debit = $("#form-edit-item input[id='e_total_debit']");
                    var e_total_credit = $("#form-edit-item input[id='e_total_credit']");
                    var e_note = $("#form-edit-item input[id='e_note']");

                    e_note.val(journal_item_note);
                    e_total_debit.val(journal_item_total_debit);
                    e_total_credit.val(journal_item_total_credit);

                    e_account.attr('disabled', false);
                    e_note.attr('readonly', false);
                    e_total_debit.attr('readonly', false);
                    e_total_credit.attr('readonly', false);

                    $("#btn-update-item").attr('data-journal-item-id', journal_item_id);

                }, 1000);
            } else {
                notif(0, 'Item tidak ditemukan');
            }
        });
        // Update Item Button
        $(document).on("click", "#btn-update-item", function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            var id = $(this).attr('data-journal-item-id');

            var prepare = {
                tipe: identity,
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
                beforeSend: function () {},
                success: function (d) {
                    if (parseInt(d.status) === 1) {
                        notif(1, d.message);

                        var id_document = $("#form-trans input[name='id_document']").val();
                        if (parseInt(id_document > 0)) {
                            loadJournalItems();
                        } else {
                            loadJournalItems();
                        }

                        if (parseInt(d.journal_id) > 0) {
                            index.ajax.reload();
                        }
                        $("#modal-journal-item-edit").modal('hide');
                    } else { //No Data
                        notif(0, d.message);
                    }
                    checkDocumentExist();
                },
                error: function (xhr, Status, err) {
                    notifError(err);
                }
            });
        });
        // Delete Item Button
        $(document).on("click", ".btn-delete-item", function () {
            event.preventDefault();
            var id = $(this).attr("data-journal-item-id");
            var kode = $(this).attr("data-kode");
            var nama = $(this).attr("data-nama");
            $.confirm({
                title: 'Hapus!',
                content: 'Apakah anda ingin menghapus <b>' + nama + '</b> ini?',
                buttons: {
                    confirm: {
                        btnClass: 'btn-danger',
                        text: 'Ya',
                        action: function () {
                            var data = {
                                action: 'delete-item',
                                id: id,
                                kode: kode,
                                nama: nama,
                                tipe: identity
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        if (parseInt(d.journal_id) > 0) {
                                            index.ajax.reload();
                                        }
                                        loadJournalItems();
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
         */
        $(document).on("click", "#btn-save-contact", function (e) {
            e.preventDefault();
            var next = true;

            var kode = $("#form-master input[name='kode']");
            var nama = $("#form-master input[name='nama']");

            if (next == true) {
                if ($("input[id='kode_contact']").val().length == 0) {
                    notif(0, 'Kode wajib diisi');
                    $("#kode_contact").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='nama_contact']").val().length == 0) {
                    notif(0, 'Nama wajib diisi');
                    $("#nama_contact").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='telepon_1_contact']").val().length == 0) {
                    notif(0, 'Telepon 1 wajib diisi');
                    $("#telepon_1_contact").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("textarea[id='alamat_contact']").val().length == 0) {
                    notif(0, 'Alamat wajib diisi');
                    $("#alamat_contact").focus();
                    next = false;
                }
            }


            if (next == true) {
                var prepare = {
                    tipe: 1,
                    kode: $("input[id='kode_contact']").val(),
                    nama: $("input[id='nama_contact']").val(),
                    perusahaan: $("input[id='perusahaan_contact']").val(),
                    telepon_1: $("input[id='telepon_1_contact']").val(),
                    email_1: $("input[id='email_1_contact']").val(),
                    alamat: $("textarea[id='alamat_contact']").val(),
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
                    dataType: 'json',
                    cache: false,
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) == 1) { /* Success Message */
                            notif(1, d.message);
                            formKontakNew();
                            $("#modal-contact").modal('toggle');
                            $("#kontak").val(0).trigger('change');
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
        function loadJournalItems(journal_ids = 0) {
            $("#table-item tbody").html('');
            var journal_id = $("#id_document").val();
            // console.log('Load Journal: '+journal_id);
            if (parseInt(journal_id) > 0) {
                var data = {
                    action: 'load-journal-items',
                    tipe: identity,
                    journal_id: journal_id
                };
            } else {
                var data = {
                    action: 'load-journal-items',
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
                            // $("#div-form-trans").show(300);

                            var total_records = d.total_records;
                            if (parseInt(total_records) > 0) {
                                var dsp = '';
                                var total_recordss = parseInt(d.total_records.length);
                                // console.log(total_recordss);
                                $("#div-item").html('');
                                for (var a = 0; a < total_records; a++) {
                                    var account_label = 'account_debit_account';
                                    var account_note = 'account_debit_note';
                                    var account_total = 'account_debit_total';
                                    var i = a;
                                    dsp += '<tr class="tr-trans-item-id" data-id="' + d.result[a]['journal_item_id'] + '">';

                                    dsp += '<td>' + d.result[a]['account_name'];
                                    dsp += '</td>';
                                    // dsp += '<td style="text-align:left;">' + d.result[a]['journal_item_note'] + '</td>';
                                    dsp += '<td style="text-align:right;">' + d.result[a]['journal_item_debit'] + '</td>';
                                    // dsp += '<td style="text-align:right;">'+d.result[a]['journal_item_credit']+'</td>';                    
                                    dsp += '<td>';
                                    dsp += '<button type="button" class="btn-edit-item btn btn-mini btn-primary" data-journal-id="' + d.result[a]['journal_item_journal_id'] + '" data-journal-item-id="' + d.result[a]['journal_item_id'] + '" data-journal-item-account-id="' + d.result[a]['account_id'] + '" data-nama="' + d.result[a]['account_name'] + '" data-kode="' + d.result[a]['account_code'] + '" data-journal-item-debit="' + d.result[a]['journal_item_debit'] + '" data-journal-item-credit="' + d.result[a]['journal_item_credit'] + '" data-journal-item-note="' + d.result[a]['journal_item_note'] + '">';
                                    dsp += '<span class="fas fa-edit"></span>';
                                    dsp += '</button>';
                                    dsp += '<button type="button" class="btn-delete-item btn btn-mini btn-danger" data-journal-item-id="' + d.result[a]['journal_item_id'] + '" data-nama="' + d.result[a]['account_name'] + '" data-kode="' + d.result[a]['account_code'] + '">';
                                    dsp += '<span class="fas fa-trash-alt"></span>';
                                    dsp += '</button>';
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
                            } else {
                                $("#table-item tbody").html('');
                                $("#table-item tbody").html('<tr><td colspan="4">- Tidak ada item -</td></tr>');
                            }
                        } else { //No Data
                            // notif(1,d.message);
                            $("#table-item tbody").html('');
                            $("#table-item tbody").html('<tr><td colspan="4">- Tidak ada item -</td></tr>'); // 
                            $("#total_item").val(0);
                            $("#total_debit").val('0.00');
                            $("#total_credit").val('0.00');
                            // $("#subtotal").val(0);
                            // $("#total_diskon").val(0);
                            // $("#total").val(0);     
                            // $("#btn-cancel").css('display','none');
                            // $("#btn-save").css('display','none');                

                            // $("#label-subtotal").html(0);
                            // $("#label-total").html(0);          
                        }
                    },
                    error: function (xhr, Status, err) {
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
        }
        function formTransCancel() {
            formTransSetDisplay(1);
            $("#form-trans input").not("input[id='tipe']").not("input[id='tgl']").not("input[id='tgl_tempo']").val('');
            // $("#btn-new").show();
            // $("#btn-save").hide();
            // $("#btn-update").hide();
            // $("#btn-cancel").hide();
            $("#account_kredit").val(0).trigger('change');
            // $("#cara_pembayaran").val(0).trigger('change');
            $("#account_debit_account").val(0).trigger('change');
            $("#kontak").val(0).trigger('change');
            $("#form-trans input[id='id_document']").val(0);
            $("#form-trans input[id='journal_session']").val(0);
            $("#btn-new").show();
            loadJournalItems();
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
    function formKontakNew() {
        $("#kode").val('');
        $("#nama").val('');
        $("#perusahaan").val('');
        $("#telepon_1").val('');
        $("#email_1").val('');
        $("#alamat").val('');
    }
    function formAccountNew() {
        $("#kode-akun").val('');
        $("#nama-akun").val('');
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
            // "cara_pembayaran",
            "account_debit_account",
            "account_kredit"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>