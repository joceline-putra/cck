
<script>
    $(document).ready(function () {
        // $.alert('Sampai Dengan: btn-update controller');
        // var konten = '';
        // konten += 
        // '- MY_Controller CALL SP journal_from_trans Done, Minus Contact_id, Account_id<br>';
        // $.alert(konten);

        // $("body").condensMenu();  
        // $('#sidebar .start .sub-menu').css('display','none');
        // $("#modal-trans-save").modal({backdrop: 'static', keyboard: false});

        //Identity
        var identity = "<?php echo $identity; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="inventory/goods_in"]').addClass('active');

        //Url
        var url = "<?= base_url('inventory/manage'); ?>";
        // var url_finance = "<?= base_url('finance/account_payable'); ?>";  
        var url_print = "<?= base_url('transaksi/prints'); ?>";
        var url_print_all = "<?= base_url('transaksi/report_pembelian'); ?>";

        var post_order = "<?php echo $post_order; ?>";
        // console.log(post_contact); 
        // $("select").select2();
        var operator = "<?php echo $operator; ?>";

        $(function () {
            //DatePicker
            $("#tgl").datepicker({
                // defaultDate: new Date(),
                format: 'dd-mm-yyyy',
                autoclose: true,
                enableOnReadOnly: true,
                language: "en",
                todayHighlight: true,
                weekStart: 1
            }).on("changeDate", function (e) { });
            $("#tgl_tempo").datepicker({
                // defaultDate: new Date(),
                format: 'dd-mm-yyyy',
                autoclose: true,
                enableOnReadOnly: true,
                language: "en",
                todayHighlight: true,
                weekStart: 1
            }).on("changeDate", function (e) { });

            $("#start, #end").datepicker({
                // defaultDate: new Date(),
                format: 'dd-mm-yyyy',
                autoclose: true,
                enableOnReadOnly: true,
                language: "id",
                todayHighlight: true,
                weekStart: 1
            }).on('changeDate', function () {
                index.ajax.reload();
            });
        });

        $(document).on("change", "#syarat_pembayaran", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var value = $(this).find(":selected").val();
            // var dd = d.result.order_date.substr(8,2);
            // var mm = d.result.order_date.substr(5,2);
            // var yy = d.result.order_date.substr(0,4);
            // var set_date = dd+'-'+mm+'-'+yy;
            // // $("#form-trans input[name='tgl']").val(set_date).trigger('changeDate');
            // // $("#form-trans input[name='tgl']").attr('data-value',set_date);
            // $("#form-trans input[name='tgl']").datepicker("update", set_date); 

            var tgl = $("#tgl").val();
            var tgl_tempo = $("#tgl_tempo").val();

            var tgl_convert = new Date(tgl);
            var tgl_tempo_convert = new Date(tgl_tempo);
            var different = parseInt((tgl_tempo_convert.getTime() - tgl_convert.getTime()) / (24 * 3600 * 1000));
            // console.log('A:'+tgl_convert+', B:'+tgl_tempo_convert+', C:'+different);  
            // console.log(different);
            if (parseInt(different) == 0) {
                // $(this).val("0").trigger('change');
            } else if (parseInt(different) == 30) {
                // $(this).val("30").trigger('change');
            } else {

            }
        });

        //Autonumeric
        const autoNumericOption = {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalCharacterAlternative: '.',
            decimalPlaces: 2,
            watchExternalChanges: true //!!!        
        };
        new AutoNumeric('#qty', autoNumericOption);
        new AutoNumeric('#harga', autoNumericOption);

        //Animate
        const btnNew = document.querySelector('#btn-new');
        const btnCancel = document.querySelector('#btn-cancel');

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
                    d.location_from = $("#filter_location").find(':selected').val();
                    d.search = {
                        value: $("#filter_search").val()
                    };

                    // d.user_role =  $("#select_role").val();
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": [4]
                }, {
                    "searchable": false,
                    "orderable": true,
                    "targets": [2, 3]
                }
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [{
                    'data': 'trans_date_format'
                }, {
                    'data': 'trans_number',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<a class="btn-edit" data-id="' + row.trans_id + '" style="cursor:pointer;">';
                        dsp += '<span class="fas fa-file-alt"></span>&nbsp;' + row.trans_number;
                        dsp += '</a>';
                        return dsp;
                    }
                }, {
                    'data': 'contact_name'
                }, {
                    'data': 'trans_total', className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // dsp += addCommas(row.trans_subtotal);
                        dsp += addCommas(row.trans_total);
                        return dsp;
                    }
                }, {
                    'data': 'trans_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // disp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';

                        // dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="'+ data +'">';
                        // dsp += '<span class="fas fa-edit"></span>Edit';
                        // dsp += '</button>';

                        // if(parseInt(row.trans_paid)==0){
                        // dsp += '<button class="btn-pay btn btn-mini btn-info" data-contact-id="'+ row.contact_id +'">';
                        // dsp += '<span class="fas fa-money-bill"></span> Bayar';
                        // dsp += '</button>';          
                        // }

                        // dsp += '&nbsp;<button class="btn-retur btn btn-mini btn-default" data-trans-id="'+row.trans_id+'" data-contact-id="'+ row.contact_id +'">';
                        // dsp += '<span class="fas fa-undo"></span> Retur';
                        // dsp += '</button>';

                        dsp += '&nbsp;<button class="btn-delete btn btn-mini btn-danger" data-id="' + data + '" data-number="' + row.trans_number + '">';
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
        // formTransNew();
        // formTransItemSetDisplay(0);
        //Function Load
        formTransNew();
        formTransItemSetDisplay(0);
        if (operator.length > 0) {
            $("#div-form-trans").show(300);
        }
        // loadTransItems();

        if (operator.length > 0) {
            $("#div-form-trans").show(300);
        }

        if (parseInt(post_order) > 0) {
            $("#div-form-trans").show(300);
            loadOrderTrans(post_order);
        }

        //Select2
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
                        tipe: 3, //1=Supplier, 2=Asuransi
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
                    // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;          
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
        $('#gudang').select2({
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
                        // tipe: 1, //1=Supplier, 2=Asuransi
                        source: 'locations'
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
                    return '<i class="fas fa-warehouse ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute
                // $(datas.element).attr('data-alamat', datas.alamat);
                // $(datas.element).attr('data-telepon', datas.telepon);
                // $(datas.element).attr('data-email', datas.email);            
                return '<i class="fas fa-warehouse ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });
        $('#produk').select2({
            //dropdownParent:$("#modal-id"), //If Select2 Inside Modal
            placeholder: '<i class="fas fa-boxes"></i> Search',
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
                        category: 1,
                        source: 'products'
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
                // $(datas.elemet).attr('data-custom-value', d.anything);
                return '<i class="fas fa-boxes ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });
        $('#e_produk').select2({
            //dropdownParent:$("#modal-id"), //If Select2 Inside Modal
            placeholder: '<i class="fas fa-boxes"></i> Search',
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
                        category: 1,
                        source: 'products'
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
                // $(datas.elemet).attr('data-custom-value', d.anything);
                return '<i class="fas fa-boxes ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
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
                        tipe: 3, //1=Supplier, 2=Asuransi
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
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
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
        $('#filter_location').select2({
            //dropdownParent:$("#modal-id"), //If Select2 Inside Modal
            placeholder: '<i class="fas fa-warehouse"></i> Search',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        // tipe: 3, //1=Supplier, 2=Asuransi
                        source: 'locations'
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
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                if ($.isNumeric(datas.id) == true) {
                    return '<i class="fas fa-warehouse ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
                }
            }
        });
        $('#satuan_barang').select2({
            //dropdownParent:$("#modal-id"), //If Select2 Inside Modal
            placeholder: '<i class="fas fa-boxes"></i> Search',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        source: 'units'
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
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    return datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute
                // $(datas.elemet).attr('data-custom-value', d.anything);
                return datas.text;
            }
        });
        $(document).on("change", "#kontak", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // console.log($(this));
            var this_val = $(this).find(':selected').val();
            if (this_val == '-') {
                $("#modal-contact").modal('toggle');
                formKontakNew();
            } else {
                var id = $(this).find(':selected').val();
                var alamat = $(this).find(':selected').attr('data-alamat');
                var email = $(this).find(':selected').attr('data-email');
                var telepon = $(this).find(':selected').attr('data-telepon');
                $("#alamat").val(alamat);
                $("#telepon").val(telepon);
                $("#email").val(email);
                // alert(id);
            }
        });
        $(document).on("change", "#produk", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // console.log($(this));
            var this_val = $(this).find(':selected').val();
            if (this_val == '-') {
                $("#modal-product").modal('toggle');
                formBarangNew();
            } else {
                var id = $(this).find(':selected').val();
                if (parseInt(id) !== 0) {
                    var data = {
                        action: 'read',
                        tipe: 1,
                        id: id
                    };
                    $("#form-trans-item input[name='satuan']").val('Pcs');
                    $("#form-trans-item input[name='qty']").focus();
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('produk/manage'); ?>",
                        data: data,
                        dataType: 'json',
                        cache: 'false',
                        beforeSend: function () {},
                        success: function (d) {
                            if (parseInt(d.status) === 1) { //Success
                                // notif(1,d.message);
                                $("#form-trans-item input[name='satuan']").val(d.result.product_unit);
                                $("#form-trans-item input[name='harga']").val(d.result.product_price_buy);
                                $("#qty").focus();
                            } else { //No Data
                                notif(0, d.message);
                            }
                        },
                        error: function (xhr, Status, err) {
                            notif(0, err);
                        }
                    });
                }
            }
        });
        $(document).on("change", "#filter_kontak", function (e) {
            index.ajax.reload();
        });



        // Save Button
        $(document).on("click", "#btn-save", function (e) {
            e.preventDefault();
            var next = true;
            var total_item = $("#total_produk").val();

            if (parseInt(total_item) < 1) {
                notif(0, 'Minimal harus ada satu produk diinput');
                next = false;
            }

            if (next == true) {
                if ($("select[id='kontak']").find(':selected').val() == 0) {
                    notif(0, 'Karyawan harus dipilih dahulu');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='gudang']").find(':selected').val() == 0) {
                    notif(0, 'Gudang harus dipilih dahulu');
                    next = false;
                }
            }

            if (next == true) {

                //Fetch ID of Trans Item ID
                var trans_item_list_id = [];
                $('.tr-trans-item-id').each(function () {
                    trans_item_list_id.push($(this).data('id'));
                });

                //Prepare all Data
                var prepare = {
                    tipe: identity,
                    nomor: $("input[id='nomor']").val(),
                    nomor_ref: $("input[id='nomor_ref']").val(),
                    tgl: $("input[id='tgl']").val(),
                    tgl_tempo: $("input[id='tgl_tempo']").val(),
                    kontak: $("select[id='kontak']").find(':selected').val(),
                    alamat: $("textarea[id='alamat']").val(),
                    email: $("#email").val(),
                    telepon: $("#telepon").val(),
                    keterangan: $("#keterangan").val(),
                    gudang: $("#gudang").find(':selected').val(),
                    trans_list: trans_item_list_id
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
                    beforeSend: function () {
                        $("#btn-save").attr('disabled', true);
                    },
                    success: function (d) {
                        if (parseInt(d.status) == 1) { /* Success Message */
                            // notif(1,d.message);
                            $("#id_document").val(d.result.trans_id);
                            $("#form-trans input[id=nomor]").val(d.result.trans_number);
                            index.ajax.reload();
                            loadTransItems();
                            $(".btn-print").attr('data-id', d.result.trans_id);
                            $(".btn-print").attr('data-number', d.result.trans_number);
                            $("#modal-trans-save").modal({backdrop: 'static', keyboard: false});
                            formTransNew();
                            formTransItemSetDisplay(0);
                            $("#btn-save").hide();
                            $("#btn-update").show();
                            $("#btn-print").show();
                        } else { //Error
                            notif(0, d.message);
                        }
                        $("#btn-save").attr('disabled', false);
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
            $("#div-form-trans").show(300);
            // $(this).hide();    
            // $("#form-trans input[name='kode']").attr('readonly',true);

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
                    if (parseInt(d.status) == 1) { /* Success Message */
                        activeTab('tab1'); // Open/Close Tab By ID
                        // notif(1,d.result.id);
                        $("#form-trans input[name='id_document']").val(d.result.trans_id);
                        var dd = d.result.trans_date.substr(8, 2);
                        var mm = d.result.trans_date.substr(5, 2);
                        var yy = d.result.trans_date.substr(0, 4);
                        var set_date = dd + '-' + mm + '-' + yy;
                        // $("#form-trans input[name='tgl']").val(set_date).trigger('changeDate');
                        // $("#form-trans input[name='tgl']").attr('data-value',set_date);
                        $("#form-trans input[name='tgl']").datepicker("update", set_date);
                        // alert(dd+'-'+mm+'-'+yy);
                        $("#form-trans input[name='nomor']").val(d.result.trans_number);
                        $("#form-trans input[name='nomor_ref']").val(d.result.trans_ref_number);

                        $("#form-trans input[name='email']").val(d.result.trans_contact_email);
                        $("#form-trans input[name='telepon']").val(d.result.trans_contact_phone);
                        // $("#form-trans input[name='harga_beli']").val(d.result.harga_beli);
                        // $("#form-trans input[name='harga_jual']").val(d.result.harga_jual);
                        // $("#form-trans input[name='stok_minimal']").val(d.result.stok_minimal);
                        // $("#form-trans input[name='stok_maksimal']").val(d.result.stok_maksimal);          
                        $("textarea[id='alamat']").val(d.result.trans_contact_address);
                        $("textarea[id='keterangan']").val(d.result.trans_note);
                        // $("#form-trans select[name='satuan']").val(d.result.satuan).trigger('change');
                        // $("#form-trans select[name='status']").val(d.result.flag).trigger('change');

                        $("select[name='kontak']").append('' +
                                '<option value="' + d.result.trans_contact_id + '" data-alamat="' + d.result.trans_contact_address + '" data-telepon="' + d.result.trans_contact_phone + '" data-email="' + d.result.trans_contact_email + '">' +
                                d.result.contact_code + ' - ' + d.result.contact_name +
                                '</option>');
                        $("select[name='kontak']").val(d.result.trans_contact_id).trigger('change');

                        $("select[name='gudang']").append('' +
                                '<option value="' + d.result.trans_location_id + '" data-nama="' + d.result.location_name + '">' +
                                d.result.location_code + ' - ' + d.result.location_name +
                                '</option>');
                        $("select[name='gudang']").val(d.result.trans_location_id).trigger('change');
                        // $.alert('Gudang harus select2');
                        // alert(dd+'-'+mm+'-'+yy);
                        loadTransItems(d.result.trans_id);

                        $("#btn-new").hide();
                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-print").show();
                        $("#btn-cancel").show();

                        $("#btn-update").removeAttr('disabled');
                        // $("#btn-update").attr('data-id',d.result.trans_id);
                        // $("#btn-print").attr('data-id',d.result.trans_id);
                        scrollUp('content');
                    } else {
                        notif(0, d.message);
                    }
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
                    notif(0, 'Supplier harus dipilih dahulu');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='gudang']").find(':selected').val() == 0) {
                    notif(0, 'Gudang harus dipilih dahulu');
                    next = false;
                }
            }
            if (next == true) {
                var prepare = {
                    tipe: identity,
                    nomor: $("input[id='nomor']").val(),
                    nomor_ref: $("input[id='nomor_ref']").val(),
                    id: id,
                    tgl: $("input[id='tgl']").val(),
                    tgl_tempo: $("input[id='tgl_tempo']").val(),
                    kontak: $("select[id='kontak']").find(':selected').val(),
                    alamat: $("textarea[id='alamat']").val(),
                    email: $("#email").val(),
                    telepon: $("#telepon").val(),
                    gudang: $("#gudang").find(':selected').val(),
                    keterangan: $("#keterangan").val()
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
                    beforeSend: function () {
                        $("#btn-update").attr('disabled', true);
                    },
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
                            // notif(0,d.message);  
                        }
                        $("#btn-update").attr('disabled', false);
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

            /*
             $.confirm({
             title: 'Hapus!',
             content: 'Apakah anda ingin menghapus <b>'+number+'</b> ?',
             buttons: {
             confirm:{ 
             btnClass: 'btn-danger',
             text: 'Ya',
             action: function () {
             var data = {
             action: 'delete',
             id:id,
             number: number,
             tipe: identity
             }
             $.ajax({
             type: "POST",     
             url : url,
             data: data,
             dataType:'json',
             success:function(d){
             if(parseInt(d.status)==1){ 
             notif(1,d.message); 
             index.ajax.reload();
             }else{ 
             notif(0,d.message); 
             }
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
             */

            $.confirm({
                title: 'Hapus!',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                animation: 'zoom',
                closeAnimation: 'bottom',
                animateFromElement: false,
                content: function () {
                    var self = this;
                    var data = {
                        action: 'read',
                        id: id,
                        number: number,
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
                        if (d.total_records > 0) {

                            // table tag
                            var head = '';
                            head += '<table class="table table-bordered table-striped"><tbody>';
                            head += '<tr>';
                            head += '<td style="padding:4px 0px!important;text-align:center"><b>Stok</b>&nbsp;</td>';
                            head += '<td style="padding:4px 0px!important;text-align:center">&nbsp;<b>Gudang</b></td>';
                            head += '<td style="padding:4px 0px!important;text-align:center">&nbsp;<b>Riwayat</b></td>';
                            head += '</tr>';

                            // table body
                            var body = '';

                            // end tag table
                            var end = '</tbody></table>';

                            $.each(d.result, function (i, val) {

                                // table body
                                body += '<tr>' +
                                        '<td style="padding:4px 0px!important;text-align:right;"><b>' + val.qty_akhir + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;">&nbsp;<b>' + val.gudang_kode + ' - ' + val.gudang_nama + '</b>&nbsp;</td>' +
                                        '<td style="text-align:center;">' +
                                        '<button class="btn btn-primary btn-mini btn-riwayat-kartu-stok"' +
                                        ' data-id-barang=' + val.id_barang + '' +
                                        ' data-id-lokasi=' + val.id_gudang + '' +
                                        '>' +
                                        'Kartu Stok</button></td>' +
                                        '</tr>';

                            });

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
                    this.setContentAppend('<div>Apakah anda ingin menghapus <b>' + number + '</b> ?</div>');
                },
                buttons: {
                    button_1: {
                        text: 'Hapus',
                        btnClass: 'btn-default',
                        keys: ['enter'],
                        action: function () {
                            var data = {
                                action: 'delete',
                                id: id,
                                number: number,
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
                                        index.ajax.reload(null, false);
                                        formTransNew();
                                    } else {
                                        notif(0, d.message);
                                    }
                                }
                            });
                        }
                    },
                    button_2: {
                        text: 'Tidak Jadi',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function () {
                            // Close 
                        }
                    }
                }
            });

        });
        // New Button
        $(document).on("click", "#btn-new", function (e) {
            formTransNew();
            // $("#div-form-trans").show(300);
            $("#div-form-trans").show(300);
            $(this).hide();
            // animateCSS('#btn-new', 'backOutLeft','true');

            // btnNew.classList.add('animate__animated', 'animate__fadeOutRight');
        });
        // Cancel Button
        $(document).on("click", "#btn-cancel", function () {
            event.preventDefault();
            formTransCancel();
            // btnNew.classList.remove('animate__animated', 'animate__fadeOutRight');    
            $("#div-form-trans").hide(300);
            // $("#btn-new").show();
            // var id = $(this).attr("data-id"); 
            // $.confirm({
            //   title: 'Yakin membatalkan!',
            //   content: 'Apakah anda ingin membatalkan transaksi ?',
            //   buttons: {
            //     confirm:{ 
            //       btnClass: 'btn-danger',
            //       text: 'Batalkan',
            //       action: function () {
            //         var data = {
            //           action: 'cancel',
            //         }
            //         $.ajax({
            //           type: "POST",     
            //           url : url,     
            //           data: data,
            //           dataType: 'json',
            //           success:function(d){
            //             if(parseInt(d.status)==1){ 
            //               notif(1,d.message); 
            //               loadTransItems();
            //             }else{ 
            //               notif(0,d.message); 
            //             }
            //           }
            //         });
            //       }
            //     },
            //     cancel:{
            //       btnClass: 'btn-success',
            //       text: 'Tidak Jadi', 
            //       action: function () {
            //         // $.alert('Canceled!');
            //       }
            //     }
            //   }
            // });           
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
                var win = window.open(print_url, 'Print Pembelian', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
                var data = id;
                // $.post(url_print, {id:data}, function (data) {
                //     var w = window.open(print_url,'Print');
                //     w.document.open();
                //     w.document.write(data);
                //     w.document.close();
                // });
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

        // Set Flag Button
        $(document).on("click", ".btn-set-active", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            var flag = $(this).attr("data-flag");
            if (flag == 1) {
                var set_flag = 0;
                var msg = 'nonaktifkan';
            } else {
                var set_flag = 1;
                var msg = 'aktifkan';
            }
            var kode = $(this).attr("data-kode");
            var nama = $(this).attr("data-nama");
            $.confirm({
                title: 'Set Status',
                content: 'Apakah anda ingin <b>' + msg + '</b> dengan nama <b>' + nama + '</b> ?',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                autoClose: 'button_2|10000',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                buttons: {
                    button_1: {
                        text: 'Ok',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function () {
                            var data = {
                                action: 'set-active',
                                id: id,
                                flag: set_flag,
                                nama: nama,
                                kode: kode,
                                tipe: identity
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
                                        notif(1, d.message);
                                        index.ajax.reload();
                                    } else {
                                        notif(0, d.message);
                                    }
                                },
                                error: function (xhr, Status, err) {
                                    notif(0, 'Error');
                                }
                            });
                        }
                    },
                    button_2: {
                        text: 'Batal',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function () {
                            //Close
                        }
                    }
                }
            });
        });

        // Save Item Button
        $(document).on("click", "#btn-save-item", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // var id = $(this).attr('data-id');
            var next = true;
            if ($("#produk").find(':selected').val() == 0) {
                notif(0, 'Produk belum dipilih');
                next = false;
            }
            // if($("#gudang").find(':selected').val() == 0){
            //   notif(0,'Gudang harus dipilih');
            //   next=false;
            // }

            if (next == true) {
                if ($("#satuan").val().length == 0) {
                    notif(0, 'Satuan harus diisi');
                    next = false;
                }
            }
            if (next == true) {
                if (parseInt($("#qty").val().length == 0) || ($("#qty").val() == "0")) {
                    notif(0, 'Qty harus diisi');
                    $("#qty").focus();
                    next = false;
                }
            }
            if (next == true) {
                if ($("#qty").val() == '') {
                    notif(0, 'Qty harus diisi');
                    $("#qty").focus();
                    next = false;
                }
            }
            if (next == true) {
                if (parseInt($("#harga").val().length == 0) || ($("#harga").val() == 0)) {
                    notif(0, 'Harga harus diisi');
                    $("#harga").focus();
                    next = false;
                }
            }
            if (next == true) {
                var harga = $("#harga").val();
                var prepare = {
                    tipe: identity,
                    id: $("#id_document").val(),
                    tgl: $("#tgl").val(),
                    produk: $("#produk").find(':selected').val(),
                    satuan: $("#satuan").val(),
                    qty: $("#qty").val(),
                    harga: harga,
                    harga_konversi: removeCommas(harga),
                    diskon: $("#diskon").val(),
                    ppn: $("#ppn_item").find(':selected').val(),
                    jumlah: $("#jumlah").val(),
                    keterangan: $("#keterangan").val()
                };
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'create-item',
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
                        if (parseInt(d.status) == 1) { //Success
                            // notif(1,d.message);
                            formTransItemNew();
                            loadTransItems();

                            if (parseInt(d.trans_id) > 0) {
                                index.ajax.reload();
                            }
                        } else { //No Data
                            notif(0, d.message);
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, err);
                    }
                });
            }
        });
        // Edit Item Button
        // $(document).on("click",".btn-edit-item",function(e) {
        //   e.preventDefault();
        //   e.stopPropagation();
        //   console.log($(this));
        //   var id = $(this).attr('data-id');
        //   /* hint zz_ajax */  
        // });
        $(document).on("click", ".btn-edit-item", function (e) {
            var trans_id = $(this).attr('data-trans-id');
            var trans_item_id = $(this).attr('data-trans-item-id');
            var trans_number = $(this).attr('data-trans-number');
            var trans_name = $(this).attr('data-nama');

            var trans_item_product_id = $(this).attr('data-trans-item-product-id');
            var trans_item_product = $(this).attr('data-kode') + ' - ' + $(this).attr('data-nama');
            var trans_item_unit = $(this).attr('data-trans-item-unit');
            var trans_item_qty = $(this).attr('data-trans-item-qty');
            var trans_item_price = $(this).attr('data-trans-item-price');
            var trans_item_ppn = $(this).attr('data-trans-item-ppn');
            // var trans_item_discount = $(this).attr('data-trans-item-discount');   
            var trans_item_total = $(this).attr('data-trans-item-total');
            var trans_item_note = $(this).attr('data-trans-item-note');

            if (parseInt(trans_item_id) > 0) {
                setTimeout(function () {
                    $("#modal-trans-item-edit").modal('show');
                    $("#modal-trans-item-edit-title").html('Edit Produk Untuk ' + trans_name);

                    //Set Value to Edit Form
                    $("select[name='e_produk']").append('' +
                            '<option value="' + trans_item_product_id + '">' +
                            trans_item_product +
                            '</option>');
                    $("select[name='e_produk']").val(trans_item_product_id).trigger('change');
                    $("select[name='e_ppn_item']").val(trans_item_ppn).trigger('change');

                    var e_produk = $("#form-edit-item select[id='e_produk']");
                    var e_satuan = $("#form-edit-item input[id='e_satuan']");
                    var e_harga = $("#form-edit-item input[id='e_harga']");
                    var e_qty = $("#form-edit-item input[id='e_qty']");
                    var e_total = $("#form-edit-item input[id='e_subtotal']");
                    var e_note = $("#form-edit-item textarea[id='e_keterangan']");

                    e_satuan.val(trans_item_unit);
                    e_harga.val(trans_item_price);
                    e_qty.val(trans_item_qty);
                    e_total.val(trans_item_total);
                    e_note.val(trans_item_note);

                    e_produk.attr('disabled', false);
                    e_harga.attr('readonly', false);
                    e_qty.attr('readonly', false);

                    $("#btn-update-item").attr('data-trans-item-id', trans_item_id);

                }, 1000);
            } else {
                notif(0, 'Item harus dipilih');
            }
        });
        // Update Item Button
        $(document).on("click", "#btn-update-item", function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            var id = $(this).attr('data-trans-item-id');
            var next = true;

            if ($("#e_produk").find(':selected').val() == 0) {
                notif(0, 'Produk harus diisi');
                next = false;
            }
            if (next == true) {
                if ($("#e_harga").val().length == 0) {
                    notif(0, 'Harga harus diisi');
                    $("#e_harga").focus();
                    next = false;
                }
            }
            if (next == true) {
                if ($("#e_qty").val().length == 0) {
                    notif(0, 'Qty harus diisi');
                    $("#e_qty").focus();
                    next = false;
                }
            }
            if (next == true) {
                var prepare = {
                    tipe: identity,
                    id: id,
                    produk: $("#form-edit-item select[id='e_produk']").find(':selected').val(),
                    harga: $("#form-edit-item input[id='e_harga']").val(),
                    qty: $("#form-edit-item input[id='e_qty']").val(),
                    satuan: $("#form-edit-item input[id='e_satuan']").val(),
                    ppn: $("#form-edit-item select[id='e_ppn_item']").find(':selected').val(),
                    // harga: harga,
                    // harga_konversi: removeCommas(harga),
                    // diskon: $("#diskon").val(),
                    // ppn: $("#ppn_item").find(':selected').val(),
                    // jumlah: $("#jumlah").val(),                  
                    keterangan: $("#e_keterangan").val()
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
                                loadTransItems(id_document);
                            } else {
                                loadTransItems();
                            }
                            $("#modal-trans-item-edit").modal('hide');

                            if (parseInt(d.trans_id) > 0) {
                                index.ajax.reload();
                            }
                        } else { //No Data
                            notif(0, d.message);
                        }
                    },
                    error: function (xhr, Status, err) {
                        notifError(err);
                    }
                });
            }
        });
        // Delete Item Button
        $(document).on("click", ".btn-delete-item", function () {
            event.preventDefault();
            var id = $(this).attr("data-trans-item-id");
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
                                        loadTransItems();

                                        if (parseInt(d.trans_id) > 0) {
                                            index.ajax.reload();
                                        }
                                    } else {
                                        notif(0, d.message);
                                    }
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

        // Only Note
        $(document).on("click", ".btn-click-item-note", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var qty = $(this).attr('data-qty');
            var note = $(this).attr('data-note');

            if (note == 'null') {
                $("#trans-item-note").val('');
            } else {
                $("#trans-item-note").val(note);
            }
            $("#trans-item-label").html('Item Produk: <b>' + name + '</b><br>Qty: <b>' + qty + '</b>');
            $("#trans-item-note").focus();
            $("#btn-save-item-note").attr('data-id', id);
            $("#modal-trans-note").modal({backdrop: 'static', keyboard: false});
        });
        $(document).on("click", "#btn-save-item-note", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-id');
            var next = true;

            if ($("#trans-item-note").val().length == 0) {
                notif(0, 'Catatan harus diisi');
                next = false;
            }
            if (next == true) {

                var prepare = {
                    tipe: identity,
                    id: id,
                    note: $("#trans-item-note").val()
                };
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'create-item-note',
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
                        if (parseInt(d.status) === 1) { //Success
                            // notif(1,d.message);
                            $("#modal-trans-note").modal('toggle');
                            loadTransItems();
                        } else { //No Data
                            notif(0, d.message);
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, err);
                    }
                });
            }
        });


        $(document).on("click", "#diskon", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-id');
            /* hint zz_ajax */
            $("#modal-trans-diskon").modal({backdrop: 'static', keyboard: false});
        });
        $(document).on("click", ".btn-diskon", function (e) {
            var diskon = $(this).attr('data-diskon');
            $("#modal-trans-diskon").modal('toggle');
            $("#diskon").val(diskon);
            loadTransItems();
        });

        $(document).on("click", "#btn-preview", function (e) {
            index.ajax.reload();
        });
        $(document).on("click", "#btn-save-contact", function (e) {
            e.preventDefault();
            var next = true;

            var kode = $("#form-master input[name='kode_contact']");
            var nama = $("#form-master input[name='nama_contact']");

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
        $(document).on("click", "#btn-save-product", function (e) {
            e.preventDefault();
            var next = true;

            var kode = $("#form-product input[name='kode_barang']");
            var nama = $("#form-product input[name='nama_barang']");

            if (next == true) {
                if ($("input[id='kode_barang']").val().length == 0) {
                    notif(0, 'Kode Barang wajib diisi');
                    $("#kode_barang").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='nama_barang']").val().length == 0) {
                    notif(0, 'Nama Barang wajib diisi');
                    $("#nama_barang").focus();
                    next = false;
                }
            }


            if (next == true) {
                var prepare = {
                    tipe: 1,
                    kode: $("input[id='kode_barang']").val(),
                    nama: $("input[id='nama_barang']").val(),
                    satuan: $("select[id='satuan_barang']").find(':selected').text(),
                    status: 1,
                    category: 1
                }
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'create-from-modal',
                    data: prepare_data
                };
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Produk/manage'); ?>",
                    data: data,
                    dataType: 'json',
                    cache: false,
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) == 1) { /* Success Message */
                            notif(1, d.message);
                            formBarangNew();
                            $("#modal-product").modal('toggle');
                            $("#product").val(0).trigger('change');
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
        $(document).on("click", "#btn-export", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // console.log($(this));
            // var id = $(this).attr('data-id');
            // /* hint zz_ajax */  
            $.alert('Nothing to do');
        });
        $(document).on("change", "#attribute", function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            var id = $(this).attr('data-id');
            /* hint zz_ajax */
        });
        $(document).on("input", "#e_harga, #e_qty", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var harga = $("#e_harga").val();
            var qty = $("#e_qty").val();
            var result = "0.00";
            if (harga.length > 0) {
                harga = removeCommas(harga);
            }
            if (qty.length > 0) {
                qty = removeCommas(qty);
            }
            result = addCommas(harga * qty);
            $("#e_subtotal").val(result);
        });
        $(document).on("click", ".btn-pay", function (e) {
            e.preventDefault();
            var next = true;
            var contact_id = $(this).attr('data-contact-id');

            if (contact_id < 1) {
                notif(0, 'Kontak tidak ditemukan');
                next = false;
            }
            if (next == true) {

                var operator = 'new';
                var data = {
                    contact: contact_id
                };
                var post_url = url_finance + '/' + operator;
                $.redirect(post_url, data, "POST", "_self");
            }
        });
        $(document).on("click", ".btn-retur", function (e) {
            e.preventDefault();
            var next = true;
            var trans_id = $(this).attr('data-trans-id');
            var contact_id = $(this).attr('data-contact-id');

            if (trans_id < 1) {
                notif(0, 'Transaksi tidak ditemukan');
                next = false;
            }

            if (contact_id < 1) {
                notif(0, 'Kontak tidak ditemukan');
                next = false;
            }
            if (next == true) {

                var operator = 'new';
                var data = {
                    trans: trans_id,
                    contact: contact_id
                };
                var post_url = url_finance + '/' + operator;
                $.redirect(post_url, data, "POST", "_self");
            }
        });
        $(document).on("click", "#btn-journal", function () {
            event.preventDefault();
            var id = $("#id_document").val();

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

                                // table body
                                body += '<tr>' +
                                        '<td style="padding:4px 0px!important;text-align:left;">' + val.journal_item_date + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;text-align:left;">' + val.account_code + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;text-align:left;">' + val.account_name + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
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
                    button_1: {
                        text: 'Cek Buku Besar',
                        btnClass: 'btn-default',
                        keys: ['enter'],
                        action: function () {
                            var data = {
                                action: 'cek',
                                id: id,
                                number: number,
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
                                        index.ajax.reload();
                                    } else {
                                        notif(0, d.message);
                                    }
                                }
                            });
                        }
                    },
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
        function loadOrderTrans(order_id) {
            $.confirm({
                title: 'Info',
                content: 'Sementara fitur post order to trans dihentikan',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                autoClose: 'button_2|10000',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                buttons: {
                    button_2: {
                        text: 'Close',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function () {
                            //Close
                        }
                    }
                }
            });

            // $.alert(contact_id);
            $("#table-item tbody").html('');
            // var journal_id = $("#id_document").val();
            // console.log('Load Journal: '+journal_id);
            if (parseInt(order_id) > 0) {
                var data = {
                    action: 'load-order',
                    tipe: identity,
                    order_id: order_id
                };
            } else {
                var data = {
                    action: 'load-order',
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
                            var total_records = d.result;

                            if (d.contact) {
                                $("select[name='kontak']").append('' +
                                        '<option value="' + d.contact.contact_id + '">' +
                                        d.contact.contact_code + ' - ' + d.contact.contact_name +
                                        '</option>');
                                $("select[name='kontak']").val(d.contact.contact_id).trigger('change');
                                $("select[name='kontak']").attr('readonly', true);
                                $("select[name='kontak']").attr('disabled', true);

                                $("#alamat").val(d.contact.contact_address);
                                $("#telepon").val(d.contact.contact_phone);
                                $("#email").val(d.contact.contact_email);
                            }
                            if (parseInt(total_records.length) > 0) {
                                var dsp = '';
                                var total_recordss = parseInt(d.result.length);
                                console.log(total_recordss);
                                $("#div-item").html('');
                                for (var a = 0; a < total_recordss; a++) {
                                    // var account_label = 'account_debit_account';
                                    // var account_note = 'account_debit_note';
                                    // var account_total = 'account_debit_total';                  
                                    var i = a;
                                    dsp += '<tr class="tr-trans-item-id" data-id="' + d.result[a]['order_item_id'] + '">';
                                    dsp += '<td><a class="btn-modal-transaksi" href="" data-id="' + d.result[a]['order_item__id'] + '"><i class="fas fa-file-alt"></i> ' + d.result[a]['product_name'] + '</a></td>';
                                    dsp += '<td style="text-align:right;">' + d.result[a]['order_item_qty'] + '</td>';
                                    dsp += '<td style="text-align:right;">Rp. ' + d.result[a]['order_item_price'] + '</td>';
                                    dsp += '<td style="text-align:left;">Ppn</td>';
                                    dsp += '<td style="text-align:right;">Rp. ' + d.result[a]['order_item_total'] + '</td>';
                                    // dsp += '<td style="text-align:right;"><a class="btn-modal-riwayat-pembayaran" href="#" data-id="'+d.result[a]['order_item_id']+'">Rp. '+addCommas(d.result[a]['order_item_total'])+'</a></td>';
                                    dsp += '<td></td>';
                                    // dsp += '<td>';
                                    // dsp += '<input name="form-trans-item-input" type="text" class="form-control form-trans-item-input" value="'+addCommas(d.result[a]['order_item_total'])+'">';
                                    // dsp += '<button type="button" class="btn-edit-item btn btn-mini btn-primary" data-journal-id="'+d.result[a]['journal_item_journal_id']+'" data-journal-item-id="'+d.result[a]['journal_item_id']+'" data-journal-item-account-id="'+d.result[a]['account_id']+'" data-nama="'+d.result[a]['account_name']+'" data-kode="'+d.result[a]['account_code']+'" data-journal-item-debit="'+d.result[a]['journal_item_debit']+'" data-journal-item-credit="'+d.result[a]['journal_item_credit']+'" data-journal-item-note="'+d.result[a]['journal_item_note']+'">';
                                    // dsp += '<span class="fas fa-edit"></span>';
                                    // dsp += '</button>';                  
                                    // dsp += '<button type="button" class="btn-delete-item btn btn-mini btn-danger" data-journal-item-id="'+d.result[a]['journal_item_id']+'" data-nama="'+d.result[a]['account_name']+'" data-kode="'+d.result[a]['account_code']+'">';
                                    // dsp += '<span class="fas fa-trash-alt"></span>';
                                    // dsp += '</button>';
                                    // dsp += '</td>';                  
                                    dsp += '</tr>';

                                }
                                // for
                                // alert('here');
                                $("#table-item tbody").html(dsp);
                                $("#total_item").val(d.total_records);
                                // $("#subtotal").val(d.subtotal);
                                $("#total_produk").val(total_recordss);
                                $("#total").val(d.total_order);
                                // $("#total_debit").val(d.total_debit);
                                // $("#total_credit").val(d.total_credit);                
                                // $("#btn-cancel").css('display','inline');
                                // $("#btn-save").css('display','inline'); 

                                // $("#label-subtotal").html(d.total);
                                // $("#label-total").html(d.total);          
                            } else {
                                $("#table-item tbody").html('');
                                $("#table-item tbody").html('<tr><td colspan="4">- Tidak ada item -</td></tr>');
                            }
                        } else { //No Data
                            notif(1, d.message);
                            $("#table-item tbody").html('');
                            $("#table-item tbody").html('<tr><td colspan="4">- Tidak ada item -</td></tr>'); // 
                            $("#total_item").val(0);
                            // $("#total_debit").val('0.00');
                            // $("#total_credit").val('0.00');              
                            // $("#subtotal").val(0);
                            // $("#total_diskon").val(0);
                            $("#total").val(0);
                            $("#total_produk").val(0);
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
        function loadTransItems(order_ids = 0) {
            $("#table-item tbody").html('');
            var trans_id = $("#id_document").val();
            // alert('Load Order: '+order_id);
            if (parseInt(trans_id) > 0) {
                var data = {
                    action: 'load-trans-items',
                    tipe: identity,
                    trans_id: trans_id,
                    product_type: 1
                };
            } else {
                var data = {
                    action: 'load-trans-items',
                    tipe: identity,
                    product_type: 1
                };
            }
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
                        if (parseInt(total_records) > 0) {
                            var dsp = '';
                            var total_recordss = parseInt(d.total_records.length);
                            // console.log(total_recordss);
                            for (var a = 0; a < total_records; a++) {
                                dsp += '<tr class="tr-trans-item-id" data-id="' + d.result[a]['trans_item_id'] + '">';

                                var ppn = 'Non-Ppn';
                                if (parseInt(d.result[a]['trans_item_ppn']) == 1) {
                                    ppn = 'Ppn';
                                }

                                // dsp += '<td>'+
                                //           d.result[a]['product_name']+'<br>&nbsp&nbsp;'+
                                //           'Rp.'+d.result[a]['trans_item_in_price']+' x '+d.result[a]['trans_item_in_qty']+' '+d.result[a]['trans_item_unit'];
                                dsp += '<td>' + d.result[a]['product_name'];
                                var note = d.result[a]['order_item_note'];
                                // sizeof(note);
                                // if(note){
                                //   dsp += '<br><button class="btn-click-item-note btn btn-default btn-mini" data-id="'+d.result[a]['order_item_id']+'" data-name="'+d.result[a]['product_name']+'" data-qty="'+d.result[a]['order_item_qty']+'" data-note="'+d.result[a]['order_item_note']+'">';
                                //   dsp += '<span class="fa fa-pencil"></span> Catatan: '+d.result[a]['order_item_note'];
                                //   dsp += '</button>';  
                                // }else{
                                //   dsp += '<br><button class="btn-click-item-note btn btn-info btn-mini" data-id="'+d.result[a]['order_item_id']+'" data-name="'+d.result[a]['product_name']+'" data-qty="'+d.result[a]['order_item_qty']+'" data-note="'+d.result[a]['order_item_note']+'">';
                                //   dsp += '<span class="fas fa-plus"></span> Catatan';
                                //   dsp += '</button>';
                                // }
                                dsp += '</td>';
                                dsp += '<td style="text-align:right;">' + d.result[a]['trans_item_in_qty'] + ' ' + d.result[a]['trans_item_unit'] + '</td>';
                                dsp += '<td style="text-align:right;">' + d.result[a]['trans_item_in_price'] + '</td>';
                                dsp += '<td style="text-align:right;">' + d.result[a]['trans_item_total'] + '</td>';
                                dsp += '<td>';
                                // dsp += '<button type="button" class="btn-edit-item btn btn-mini btn-primary" data-trans-id="'+d.result[a]['trans_item_trans_id']+'" data-trans-item-id="'+d.result[a]['trans_item_id']+'" data-trans-item-product-id="'+d.result[a]['product_id']+'" data-nama="'+d.result[a]['product_name']+'" data-kode="'+d.result[a]['product_code']+'" data-trans-item-unit="'+d.result[a]['trans_item_unit']+'" data-trans-item-price="'+d.result[a]['trans_item_in_price']+'" data-trans-item-qty="'+d.result[a]['trans_item_in_qty']+'" data-trans-item-ppn="'+d.result[a]['trans_item_ppn']+'" data-trans-item-total="'+d.result[a]['trans_item_total']+'"data-trans-item-note="'+d.result[a]['trans_item_note']+'">';
                                // dsp += '<span class="fas fa-edit"></span>';
                                // dsp += '</button>';                  
                                dsp += '<button type="button" class="btn-delete-item btn btn-mini btn-danger" data-trans-item-id="' + d.result[a]['trans_item_id'] + '" data-nama="' + d.result[a]['product_name'] + '" data-kode="' + d.result[a]['product_code'] + '">';
                                dsp += '<span class="fas fa-trash-alt"></span>';
                                dsp += '</button>';
                                dsp += '</td>';
                                dsp += '</tr>';
                            }
                            $("#table-item tbody").html(dsp);
                            $("#total_produk").val(d.total_produk);
                            $("#subtotal").val(d.subtotal);
                            // $("#total_diskon").val(d.total_diskon);
                            // $("#total_ppn").val(d.total_ppn);
                            $("#total").val(d.total);
                            // $("#btn-cancel").css('display','inline');
                            // $("#btn-save").css('display','inline'); 

                            // $("#label-subtotal").html(d.total);
                            // $("#label-total").html(d.total);          
                        } else {
                            $("#table-item tbody").html('');
                            $("#table-item tbody").html('<tr><td colspan="5">Tidak ada item produk</td></tr>');
                        }
                    } else { //No Data
                        // notif(1,d.message);
                        $("#table-item tbody").html('');
                        $("#table-item tbody").html('<tr><td colspan="5">Tidak ada item produk</td></tr>'); // 
                        $("#total_produk").val(0);
                        $("#subtotal").val(0);
                        // $("#total_diskon").val(0);
                        // $("#total_ppn").val(0);     
                        $("#total").val(0);
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

            // $("#form-trans input[id='id_document']").val(0);    
            if (parseInt($("#id_document").val()) == 0) {
                $("#form-trans input").not("input[id='id_document']")
                        .not("input[id='tipe']")
                        .not("input[id='tgl']")
                        .not("input[id='tgl_tempo']").val('');
                $("#form-trans select").val(0).trigger('change');
            }
            // $("#btn-new").hide();
            // $("#btn-save").show();
            // $("#btn-cancel").show();
            loadTransItems();
        }
        function formTransCancel() {
            formTransSetDisplay(1);
            $("#form-trans input").not("input[id='tipe']").not("input[id='tgl']").not("input[id='tgl_tempo']").val('');
            $("#form-trans input[id='id_document']").val(0);
            $("#form-trans select").val(0).trigger('change');
            $("#btn-new").show();
            $("#btn-save").show();
            $("#btn-print").hide();
            $("#btn-update").hide();
            loadTransItems();
        }
    });
    function formKontakNew() {
        $("#kode_contact").val('');
        $("#nama_contact").val('');
        $("#perusahaan_contact").val('');
        $("#telepon_1_contact").val('');
        $("#email_1_contact").val('');
        $("#alamat_contact").val('');
    }
    function formBarangNew() {
        $("#kode_barang").val('');
        $("#nama_barang").val('');
        $("#satuan_barang").val(0).trigger('change');
    }
    function formTransItemNew() {
        $("select[name='produk']").append('<option value="0">-- Pilih produk --</option>');
        $("select[name='produk']").val(0).trigger('change');
        formTransItemSetDisplay(0);
        $("#form-trans-item input").val('');
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
            "gudang",
            "syarat_pembayaran"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag); }
    }
    function formTransItemSetDisplay(value) { // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        if (value == 1) {
            var flag = true;
        } else {
            var flag = false; }
        //Attr Input yang perlu di setel
        var form = '#form-trans-item';
        var attrInput = [
            "keterangan",
            // "satuan",
            "qty",
            "harga",
            "jumlah"
        ];
        $("input[name='qty']").val(1);
        $("input[name='harga']").val(0);
        $("input[name='jumlah']").val(0);

        for (var i = 0; i <= attrInput.length; i++) {
            $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        }
        // $(""+ form +" input[name='satuan']").attr('readonly',true);
        //Attr Textarea yang perlu di setel
        //
        /*
         var attrText = [
         "keterangan"
         ];
         for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }
         */

        //Attr Select yang perlu di setel
        var atributSelect = [
            "produk"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>