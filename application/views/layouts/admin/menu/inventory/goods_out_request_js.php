
<script>
    $(document).ready(function () {

        var transID = 0;

        //Identity
        var identity = "<?php echo $identity; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="inventory/goods_out"]').addClass('active');

        //Url
        var url = "<?= base_url('inventory/manage'); ?>";
        var url_print = "<?= base_url('inventory/prints'); ?>";
        var url_print_all = "<?= base_url('transaksi/report_pembelian'); ?>";
        var url_product = "<?= base_url('produk/manage'); ?>";
        var url_message         = "<?= base_url('message'); ?>";

        var product_stock_qty = 0;
        var product_name = '';
        var product_unit = '';

        var post_order = "<?php echo $post_order; ?>";
        var operator = "<?php echo $operator; ?>";
        
        var whatsapp_config     = "<?php echo $whatsapp_config; ?>";

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

        //Autonumeric
        const autoNumericOption = {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalCharacterAlternative: '.',
            decimalPlaces: 2,
            watchExternalChanges: true //!!!        
        };
        new AutoNumeric('#qty', autoNumericOption);
        // new AutoNumeric('#harga', autoNumericOption);

        var index = $("#table-data").DataTable({
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load_goods_out';
                    d.tipe = identity;
                    d.date_start = $("#start").val();
                    d.date_end = $("#end").val();
                    // d.start = $("#table-data").attr('data-limit-start');
                    // d.length = $("#table-data").attr('data-limit-end'); 
                    d.length = $("#filter_length").find(':selected').val();
                    d.user = $("#filter_user").find(':selected').val();
                    d.branch_id_2 = $("#filter_branch_id_2").find(':selected').val();
                    d.search = {
                        value: $("#filter_search").val()
                    };

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
                    "targets": [4]
                },
                {
                    "searchable": false,
                    "orderable": true,
                    "targets": [2, 3]
                }
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [
                {
                    'data': 'trans_date_format',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += data;
                        if (row.trans_label == undefined) {
                            // dsp += '<br><button class="btn-tag btn btn-mini btn-default" data-trans-id="'+row.trans_id+'">';
                            // dsp += '<span class="fas fa-undo"></span> Label';
                            // dsp += '</button>';
                            dsp += '<br><span class="label btn-label" style="cursor:pointer;color:white;background-color:#929ba1;padding:1px 4px;" data-trans-id="' + row.trans_id + '">Label</span>';
                        } else {

                            // dsp += '            <option value="Tercetak">Tercetak</option>';
                            // dsp += '            <option value="Penting">Penting</option>';
                            // dsp += '            <option value="Progress">Progress</option>';
                            // dsp += '            <option value="Belum Dikirim">Belum Dikirim</option>';
                            // dsp += '            <option value="Label">Label</option>';

                            var bgcolor = '#929ba1';
                            var color = 'white';
                            var icon = '';

                            if (row.trans_label !== undefined) {
                                icon = row.label_icon;
                                bgcolor = row.label_background;
                                color = row.label_color;
                            }

                            dsp += '<br><span class="label btn-label" data-trans-id="' + row.trans_id + '" style="cursor:pointer;color:' + color + ';background-color:' + bgcolor + ';padding:1px 4px;">';
                            dsp += '<span class="' + icon + '">&nbsp;';
                            dsp += row.trans_label;
                            dsp += '</span>';
                            dsp += '</span>';
                        }
                        return dsp;
                    }
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
                    'data': 'branch_2_name',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += row.branch_2_name;
                        return dsp;
                    }
                }, {
                    'data': 'user_username', className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += data;
                        return dsp;
                    }
                }, {
                    'data': 'trans_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<button class="btn-edit btn btn-mini btn-default" data-id="'+ data +'">';
                        dsp += '<span class="fas fa-edit"></span>';
                        dsp += '</button>';
                        if(whatsapp_config == 1){
                            dsp += '&nbsp;<button class="btn btn-send-whatsapp btn-mini btn-primary"';
                            dsp += 'data-number="'+row.trans_number+'" data-id="'+data+'" data-total="'+row.trans_total+'" data-date="'+row.trans_date_format+'" data-contact-id="'+row.contact_id+'" data-contact-name="'+row.contact_name+'" data-contact-phone="'+row.contact_phone_1+'">';
                            dsp += '<span class="fab fa-whatsapp primary"></span></button>';
                        }                        
                        dsp += '&nbsp;<button class="btn-delete btn btn-mini btn-danger" data-id="' + data + '" data-number="' + row.trans_number + '">';
                        dsp += '<span class="fas fa-trash"></span>';
                        dsp += '</button>';
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
        $("#filter_location").on('change', function (e) {
            // var value = $(this).find(':selected').val();
            // $('select[name="table-data_length"]').val(value).trigger('change');
            index.ajax.reload();
        });

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
        }

        //Select2
        // $('#kontak').select2({
        //     placeholder: '--- Pilih ---',
        //     minimumInputLength: 0,
        //     ajax: {
        //         type: "get",
        //         url: "<?= base_url('search/manage'); ?>",
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //             var query = {
        //                 search: params.term,
        //                 tipe: 3, //1=Supplier, 2=Asuransi
        //                 source: 'contacts'
        //             }
        //             return query;
        //         },
        //         processResults: function (data) {
        //             return {
        //                 results: data
        //             };
        //         },
        //         cache: true
        //     },
        //     escapeMarkup: function (markup) {
        //         return markup;
        //     },
        //     templateResult: function (datas) { //When Select on Click
        //         if (!datas.id) {
        //             return datas.text;
        //         }
        //         if ($.isNumeric(datas.id) == true) {
        //             // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
        //             return datas.text;
        //         } else {
        //             // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;          
        //         }
        //     },
        //     templateSelection: function (datas) { //When Option on Click
        //         if (!datas.id) {
        //             return datas.text;
        //         }
        //         //Custom Data Attribute
        //         $(datas.element).attr('data-alamat', datas.alamat);
        //         $(datas.element).attr('data-telepon', datas.telepon);
        //         $(datas.element).attr('data-email', datas.email);
        //         return '<i class="fas fa-user-check ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
        //     }
        // });
        $('#trans_branch_id_2').select2({
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
                        source: 'branchs_exclude'
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
                return datas.text;
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
                    return datas.text;
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
                return datas.text;
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
        $('#filter_user').select2({
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
                        // tipe: 3,
                        source: 'users'
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
        $('#filter_branch_id_2').select2({
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
                        source: 'branchs_exclude'
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
                        id: id,
                        location: $("#gudang").find(':selected').val()
                    };
                    $("#form-trans-item input[name='satuan']").val('Pcs');
                    $("#form-trans-item input[name='qty']").focus();
                    $.ajax({
                        type: "POST",
                        url: url_product,
                        data: data,
                        dataType: 'json',
                        cache: 'false',
                        beforeSend: function () {},
                        success: function (d) {
                            if (parseInt(d.status) === 1) { //Success
                                // notif(1,d.message);
                                $("#form-trans-item input[name='satuan']").val(d.result.product_unit);
                                $("#form-trans-item input[name='harga']").val(d.result.product_price_buy);
                                $("#form-trans-item input[name='stock']").val(d.stock.product_qty);
                                $("#qty").focus();

                                product_name = d.result.product_name;
                                product_unit = d.result.product_unit;
                                product_stock_qty = d.stock['product_qty'];
                                //Check Product have a stok
                                console.log(id + ',' + location + ',' + d.stock.product_qty + ',' + d.result.product_unit
                                        + ',' + d.result.product_name);
                                if (parseInt(d.stock.product_qty) == 0) {

                                    loadProductLocation(id, location, d.stock.product_qty, d.result.product_unit, d.result.product_name);
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
            }
        });
        $(document).on("change", "#gudang", function (e) {
            e.preventDefault();
            e.stopPropagation();
            formTransItemNew();
        });
        $(document).on("change", "#filter_branch_id_2", function (e) {
            index.ajax.reload();
        });
        $(document).on("change", "#filter_user", function (e) {
            index.ajax.reload();
        });

        // Save Button
        $(document).on("click", "#btn-save", function (e) {
            e.preventDefault();
            var next = true;
            // var total_item = $("#total_produk").val();

            // if (parseInt(total_item) < 1) {
                // notif(0, 'Minimal harus ada satu produk diinput');
                // next = false;
            // }

            if (next == true) {
                if ($("select[id='trans_branch_id_2']").find(':selected').val() == 0) {
                    notif(0, 'Cabang harus dipilih dahulu');
                    next = false;
                }
            }

            if (next == true) {
                // if($("select[id='gudang']").find(':selected').val() == 0){
                //   notif(0,'Gudang harus dipilih dahulu');
                //   next=false;
                // }      
            }

            if (next == true) {

                //Fetch ID of Trans Item ID
                var trans_item_list_id = [];
                $('.tr-product-id').each(function () {
                    trans_item_list_id.push($(this).data('id'));
                });

                //Prepare all Data
                var prepare = {
                    tipe: identity,
                    nomor: $("input[id='nomor']").val(),
                    nomor_ref: $("input[id='nomor_ref']").val(),
                    tgl: $("input[id='tgl']").val(),
                    tgl_tempo: $("input[id='tgl_tempo']").val(),
                    alamat: $("textarea[id='alamat']").val(),
                    email: $("#email").val(),
                    telepon: $("#telepon").val(),
                    keterangan: $("#keterangan").val(),
                    gudang:$("#gudang").find(':selected').val(),
                    trans_list: trans_item_list_id,
                    trans_branch_id_2: $("select[id='trans_branch_id_2']").find(':selected').val(),
                }
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'create_goods_out',
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
                            transID = parseInt(d.result.trans_id);
                            $("#id_document").val(d.result.trans_id);
                            $("#form-trans input[id=nomor]").val(d.result.trans_number);
                            index.ajax.reload();
                            // loadTransItems();
                            loadGoodsItem();
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
                        transID = parseInt(d.result.trans_id);
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

                        $("select[name='gudang']").append('' +
                                '<option value="' + d.result.trans_location_id + '" data-nama="' + d.result.location_name + '">' +
                                d.result.location_name +
                                '</option>');
                        $("select[name='gudang']").val(d.result.trans_location_id).trigger('change');

                        $("select[name='trans_branch_id_2']").append('' +
                                '<option value="' + d.result.trans_branch_id_2 + '" data-nama="' + d.result.branch_2_name + '">' +
                                d.result.branch_2_name +
                                '</option>');
                        $("select[name='trans_branch_id_2']").val(d.result.trans_branch_id_2).trigger('change');

                        // loadTransItems(d.result.trans_id);
                        loadGoodsItem(d.result.trans_id);

                        $("#btn-new").hide();
                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-print").show();
                        $("#btn-cancel").show();

                        $("#btn-update").removeAttr('disabled');
                        $("#btn-print").attr('data-id', d.result.trans_id);
                        $("#btn-print").attr('data-number', d.result.trans_number);
                        $("#btn-print").attr('data-session', d.result.trans_session);                        
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
            // var id = $("#form-trans input[name='id_document']").val();
            var id = transID;
            var kode = $("#form-trans input[name='kode']");
            var nama = $("#form-trans input[name='nama']");

            if ((id == '') || parseInt(id) == 0) {
                notif(0, 'Dokumen tidak ditemukan');
                next = false;
            }

            if (next == true) {
                if ($("select[id='trans_branch_id_2']").find(':selected').val() == 0) {
                    notif(0, 'Cabang harus dipilih dahulu');
                    next = false;
                }
            }

            if (next == true) {
                // if($("select[id='gudang']").find(':selected').val() == 0){
                //   notif(0,'Gudang harus dipilih dahulu');
                //   next=false;
                // }      
            }
            if (next == true) {
                var prepare = {
                    tipe: identity,
                    nomor: $("input[id='nomor']").val(),
                    nomor_ref: $("input[id='nomor_ref']").val(),
                    id: id,
                    tgl: $("input[id='tgl']").val(),
                    tgl_tempo: $("input[id='tgl_tempo']").val(),
                    alamat: $("textarea[id='alamat']").val(),
                    email: $("#email").val(),
                    telepon: $("#telepon").val(),
                    // gudang:$("#gudang").find(':selected').val(),
                    keterangan: $("#keterangan").val(),
                    trans_branch_id_2: $("select[id='trans_branch_id_2']").find(':selected').val(),
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
                            transID = 0;
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
                                        formTransCancel();
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
            $("#div-form-trans").show(300);
            $(this).hide();
            // loadGoodsItem();
        });
        // Cancel Button
        $(document).on("click", "#btn-cancel", function () {
            event.preventDefault();
            formTransCancel();
            $("#div-form-trans").hide(300);
        });
        $(document).on("click", ".btn-label", function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            var id = $(this).attr('data-trans-id');

            var title = 'Ganti Label';
            $.confirm({
                title: title,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                autoClose: 'button_2|10000',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                animation: 'zoom',
                closeAnimation: 'bottom',
                animateFromElement: false,
                content: function () {
                    let self = this;
                    let url = "<?= base_url('referensi/manage'); ?>"; //CI

                    let form = new FormData();
                    form.append('action', 'load');
                    form.append('tipe', 9);
                    form.append('order[0][column]', '0');
                    form.append('order[0][dir]', 'asc');
                    return $.ajax({
                        url: url,
                        data: form,
                        dataType: 'json',
                        type: 'post',
                        cache: 'false', contentType: false, processData: false,
                    }).done(function (d) {
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if (parseInt(s) == 1) {
                            notif(s, m);
                            // notifSuccess(m);
                            /* hint zz_for or zz_each */
                        } else {
                            // notif(s,m);
                            // notifSuccess(m);
                        }
                        self.setTitle(m);
                        // self.setContentAppend('<br>Version: ' + d.short_name); //Json Return
                        // self.setContentAppend('<img src="'+ d.icons[0].src+'" class="img-responsive" style="margin:0 auto;">'); // Image Return
                        /*type_your_code_here*/

                    }).fail(function () {
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                onContentReady: function () {
                    var self = this;
                    var content = '';
                    var dsp = '';
                    let r = self.ajaxResponse.data;
                    // dsp += '<div>Content is ready after process !</div>';
                    dsp += '<form id="jc_form">';
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                    dsp += '    <div class="form-group">';
                    // dsp += '    <label class="form-label">Label</label>';
                    dsp += '        <select id="jc_select" name="jc_select" class="form-control">';
                    if (parseInt(r['status']) == 1) {
                        for (var ss = 0; ss < r['result'].length; ss++) {
                            dsp += '          <option value="' + r['result'][ss]['ref_name'] + '">' + r['result'][ss]['ref_name'] + '</option>';
                        }
                    }
                    dsp += '            <option value="Label">Label</option>';
                    dsp += '        </select>';
                    dsp += '    </div>';
                    dsp += '</div>';
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);
                },
                buttons: {
                    button_1: {
                        text: 'Terapkan',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function () {
                            var self = this;
                            var select = self.$content.find('#jc_select').val();

                            if (select == 0) {
                                $.alert('Select mohon dipilih dahulu');
                                return false;
                            } else {
                                var form = new FormData();
                                form.append('action', 'update-label');
                                form.append('trans_id', id);
                                form.append('trans_label', select);
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    data: form, dataType: 'json',
                                    cache: 'false', contentType: false, processData: false,
                                    beforeSend: function () {},
                                    success: function (d) {
                                        var s = d.status;
                                        var m = d.message;
                                        var r = d.result;
                                        if (parseInt(s) == 1) {
                                            notif(s, m);
                                            index.ajax.reload(null, false);
                                        } else {
                                            // notif(s,m);
                                            // notifSuccess(m);
                                        }
                                    },
                                    error: function (xhr, status, err) {}
                                });
                            }
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

        // Print Button
        $(document).on("click", ".btn-print-dropdown", function () {
            var id = $("#btn-print").attr("data-id");
            var session = $("#btn-print").attr('data-session');
            var action = $(this).attr('data-action');
            // alert(action);
            if (session.length > 0) {

                if (action == 'print_struk') {
                    var trans_id = $("#btn-print").attr('data-id');
                    var set_print_url = url_print + '_struk/' + trans_id;
                    // alert(set_print_url); return false;
                    $.ajax({
                        type: "get",
                        url: set_print_url,
                        data: {action: 'print_raw'},
                        dataType: 'json',
                        cache: 'false',
                        beforeSend: function () {
                            notif(1, 'Perintah print dikirim');
                        },
                        success: function (d) {
                            var s = d.status;
                            var m = d.message;
                            if (parseInt(s) == 1) {
                                if(parseInt(d.print_to) == 0){
                                    //For Localhost
                                    window.open(d.print_url).print();
                                }else{
                                    //For RawBT
                                    return printFromUrl(d.print_url);
                                }
                            } else {
                                notif(s, m);
                            }
                        }, error: function (xhr, Status, err) {
                            notif(0, 'Error');
                        }
                    });
                } else {
                    var x = screen.width / 2 - 700 / 2;
                    var y = screen.height / 2 - 450 / 2;
                    var print_url = url_print_global + '/' + action + '/' + session;
                    var win = window.open(print_url, 'Print Penjualan', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
                }
            } else {
                notif(0, 'Dokumen belum di buka');
            }
        });        
        $(document).on("click", "#btn-print", function () {
            // var id = $(this).attr("data-id");
            // var id = $("#form-trans input[id='id_document']").val();
            // if (parseInt(id) > 0) {
            //     var x = screen.width / 2 - 700 / 2;
            //     var y = screen.height / 2 - 450 / 2;
            //     var print_url = url_print + '/' + id;
            //     // console.log(print_url);
            //     var win = window.open(print_url, 'Print Pembelian', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
            //     var data = id;
            // } else {
            //     notif(0, 'Dokumen belum di buka');
            // }
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
        $(document).on("click", ".btn-send-whatsapp", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var trans_id = $(this).attr('data-id');
            if (parseInt(trans_id) > 0) {
                var params = {
                    trans_id: trans_id,
                    trans_number: $(this).attr('data-number'),
                    trans_date: $(this).attr('data-date'),
                    trans_total: $(this).attr('data-total'),
                    contact_id: $(this).attr('data-contact-id'),
                    contact_name: $(this).attr('data-contact-name'),
                    contact_phone: $(this).attr('data-contact-phone')
                }
                formWhatsApp(params);
            } else {
                notif(0, 'Data tidak ditemukan');
            }
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

        $(document).on("click", "#btn-save-item", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // var id = $(this).attr('data-id');
            var next = true;

            var qty = removeCommas($("#qty").val());

            if (parseFloat(qty) > parseFloat(product_stock_qty)) {
                notif(0, 'Stok ' + product_name + ' hanya ' + product_stock_qty + ' ' + product_unit);
                next = false;
            }

            // next=false;
            if (next == true) {
                if ($("#gudang").find(':selected').val() == 0) {
                    notif(0, 'Gudang harus di pilih dahulu');
                    next = false;
                }
            }

            if ($("#produk").find(':selected').val() == 0) {
                notif(0, 'Produk belum dipilih');
                next = false;
            }

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
                var harga = $("#harga").val();
                var prepare = {
                    tipe: identity,
                    id: $("#id_document").val(),
                    tgl: $("#tgl").val(),
                    produk: $("#produk").find(':selected').val(),
                    satuan: $("#satuan").val(),
                    qty: $("#qty").val(),
                    lokasi: $("#gudang").find(':selected').val(),
                    // harga: harga,
                    // harga_konversi: removeCommas(harga),
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
                            // loadTransItems();
                            loadGoodsItem();
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
        $(document).on("click", "#btn-update-item", function (e) {
            e.preventDefault();
            e.stopPropagation();
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
                                // loadTransItems(id_document);
                                loadGoodsItem(id_document);
                            } else {
                                // loadTransItems();
                                loadGoodsItem();                                
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
                                        // loadTransItems();
                                        loadGoodsItem();
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
                            // loadTransItems();
                            loadGoodsItem();
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
                    url: url_product,
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
        function loadTransItems(order_ids = 0) {
            $("#table-item tbody").html('');
            // var trans_id = $("#id_document").val();
            var trans_id = transID;
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
                                dsp += '<td>';
                                    dsp += d.result[a]['product_name'];
                                    // var note = d.result[a]['order_item_note'];
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
                                dsp += '<td style="text-align:right;">' + d.result[a]['trans_item_out_qty'] + ' ' + d.result[a]['trans_item_unit'] + '</td>';
                                dsp += '<td style="text-align:right;">' + d.result[a]['trans_item_out_price'] + '</td>';
                                dsp += '<td style="text-align:left;">' + d.result[a]['location']['location_name'] + '</td>';
                                dsp += '<td style="text-align:right;">' + d.result[a]['trans_item_total'] + '</td>';
                                dsp += '<td style="text-align:right;">';
                                dsp += '</td>';
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
                            $("#total").val(d.total);       
                        } else {
                            $("#table-item tbody").html('');
                            $("#table-item tbody").html('<tr><td colspan="5">Tidak ada item produk</td></tr>');
                        }
                    } else { //No Data
                        $("#table-item tbody").html('');
                        $("#table-item tbody").html('<tr><td colspan="5">Tidak ada item produk</td></tr>'); // 
                        $("#total_produk").val(0);
                        $("#subtotal").val(0);   
                        $("#total").val(0);     
                    }
                },
                error: function (xhr, Status, err) {
                    // notif(0,err);
                }
            });
        }      
        function loadGoodsItem(){
            $("#table-item tbody").html('');
            // var trans_id = $("#id_document").val();
            var trans_id = transID;
            if (parseInt(trans_id) > 0) {
                var data = {
                    action: 'load_goods_out_item',
                    tipe: identity,
                    trans_id: trans_id,
                    product_type: 1
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json',
                    cache: 'false',
                    beforeSend: function () {
                        $("#table-item tbody").html('<tr><td colspan="6"><i class="fas fa-spinner fa-spin"></i> Sedang memuat data</td></tr>');                        
                    },
                    success: function (d) {
                        if (parseInt(d.status) === 1) {
                            notif(1, d.message);
                            $("#div-form-trans").show(300);
                            var total_records = d.total_records;
                            if (parseInt(total_records) > 0) {
                                var dsp = '';
                                var total_recordss = parseInt(d.total_records.length);
                                // console.log(total_recordss);
                                var num = 1;
                                for (var a = 0; a < total_records; a++) {
                                    
                                    var set_attr = ' data-trans-item-id="'+d.result[a]['trans_item_id']+'"'; 
                                    set_attr += ' data-product-id="'+d.result[a]['product_id']+'"';
                                    set_attr += ' data-value="'+d.result[a]['trans_item_out_qty']+'"';                                    
                                    set_attr += ' data-product-name="'+d.result[a]['product_name']+'"';
                                    set_attr += ' data-product-unit="'+d.result[a]['product_unit']+'"';                                    
                                    
                                    dsp += '<tr class="tr-trans-item-id" data-id="'+d.result[a]['trans_item_id']+'">';
                                    dsp += '<td style="text-align:center;">';
                                        dsp += num++;
                                    dsp += '</td>';
                                    dsp += '<td>';
                                        dsp += d.result[a]['product_name'];
                                    dsp += '</td>';
                                    // dsp += '<td style="text-align:right;">' + d.result[a]['trans_item_out_qty'] + ' ' + d.result[a]['trans_item_unit'] + '</td>';
                                    dsp += '<td style="text-align:left;">' + d.result[a]['category_name'] + '</td>';     
                                    dsp += '<td style="text-align:right;">' + d.result[a]['product_stock'] + ' ' + d.result[a]['product_unit'] + '</td>';                                                                
                                    // dsp += '<td style="text-align:right;">' + d.result[a]['trans_item_out_price'] + '</td>';
                                    // dsp += '<td style="text-align:right;"></td>';
                                    // dsp += '<td style="text-align:left;">' + d.result[a]['location']['location_name'] + '</td>';
                                    // dsp += '<td style="text-align:left;"></td>';                                
                                    // dsp += '<td style="text-align:right;">' + d.result[a]['trans_item_total'] + '</td>';
                                    // dsp += '<td style="text-align:right;"></td>';                                
                                    // dsp += '<td style="text-align:right;">';
                                    // dsp += '</td>';
                                    dsp += '<td class="tr-trans-item-qty" style="text-align:right;cursor:pointer;" '+set_attr+'>'; 
                                        dsp += d.result[a]['trans_item_out_qty'];
                                    dsp += '</td>';
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
                                $("#total").val(d.total);       
                            } else {
                                $("#table-item tbody").html('');
                                $("#table-item tbody").html('<tr><td colspan="6">Tidak ada item produk</td></tr>');
                            }
                        } else {
                            $("#table-item tbody").html('');
                            $("#table-item tbody").html('<tr><td colspan="5">Tidak ada item produk</td></tr>'); // 
                            $("#total_produk").val(0);
                            $("#subtotal").val(0);   
                            $("#total").val(0);     
                        }
                    },
                    error: function (xhr, Status, err) {
                        // notif(0,err);
                    }
                });
            }else{
                $("#table-item tbody").html('<tr><td colspan="6">Silahkan simpan data terlebih dahulu</td></tr>');                
            }
        }
        $(document).on("click",".tr-trans-item-qty", function (e){
            e.preventDefault();
            e.stopPropagation();
            var tid = $(this).attr('data-trans-item-id');
            var pid = $(this).attr('data-product-id');
            var pnm = $(this).attr('data-product-name');
            var pun = $(this).attr('data-product-unit');                        
            var pval = $(this).attr('data-value');

            let title   = pnm;
            $.confirm({
                title: title,
                // icon: 'fas fa-check',
                columnClass: 'col-md-5 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
                closeIcon: true, closeIconClass: 'fas fa-times', 
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content: function(){
                },
                onContentReady: function(e){
                    let self    = this;
                    let content = '';
                    let dsp     = '';
            
                    dsp += '<form id="jc_form">';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Qty Yang Diminta</label>';
                        dsp += '        <input id="jc_input" name="jc_input" class="form-control" value="'+pval+'">';
                        dsp += '    </div>';
                        dsp += '</div>';
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);
                    $("#jc_input").focus();
                },
                buttons: {
                    button_1: {
                        text:'<i class="fas fa-check white"></i> Proses',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){
                            let self      = this;
            
                            let input     = self.$content.find('#jc_input').val();
                            
                            if(!input){
                                $.alert('Input mohon diisi dahulu');
                                return false;
                            } else{
                                let form = new FormData();
                                form.append('action', 'update_goods_out_item');
                                form.append('trans_item_id', tid);
                                form.append('trans_item_out_qty', input);
                                form.append('product_name', pnm);                                
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    data: form, dataType: 'json',
                                    cache: 'false', contentType: false, processData: false,
                                    beforeSend: function() {},
                                    success: function(d) {
                                        let s = d.status;
                                        let m = d.message;
                                        let r = d.result;
                                        if(parseInt(s) == 1){
                                            notif(s, m);
                                            $(".tr-trans-item-qty[data-trans-item-id='"+tid+"']").attr('data-value',input).html(input);
                                        }else{
                                            notif(s,m);
                                        }
                                    },
                                    error: function(xhr, status, err) {}
                                });
                            }
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-ban white"></i> Batal',
                        btnClass: 'btn-default',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }
                }
            });
        });
        
        function loadProductLocation(product_id, location_id, product_qty, product_unit, product_name) {
            $.confirm({
                title: 'Informasi Stok',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                // autoClose: 'button_2',    
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                animation: 'zoom',
                closeAnimation: 'bottom',
                animateFromElement: false,
                content: function () {
                    var self = this;
                    var data = {
                        action: 'stock',
                        id: product_id,
                        tipe: 1
                    };

                    return $.ajax({
                        url: url_product,
                        data: data,
                        dataType: 'json',
                        type: 'post',
                        cache: 'false',
                    }).done(function (d) {
                        var s = d.status;
                        var m = d.message;
                        var r = d.result;
                        var dsp = '';

                        dsp += 'Barang : <b>' + product_name + '</b><br>';
                        dsp += 'Satuan : <b>' + product_unit + '</b><br>';
                        dsp += 'Lokasi Terpilih : <b>' + $("#gudang").find(':selected').text() + '</b><br>';
                        dsp += 'Stok Terakhir: <b>' + product_qty + '</b>&nbsp;<b>' + product_unit + '</b><br><br>';
                        dsp += '<table class="table table-bordered">';
                        dsp += '  <thead>';
                        dsp += '    <tr>';
                        dsp += '      <th>Gudang</th>';
                        dsp += '      <th class="text-right">Stok Saat Ini</th>';
                        // dsp += '      <th>Action</th>';  
                        dsp += '    <tr>';
                        dsp += '  </thead>';
                        dsp += '  <tbody>';

                        if (parseInt(s) == 1) {


                            var total_data = r.length;
                            for (var a = 0; a < total_data; a++) {
                                dsp += '<tr>';
                                dsp += '<td>' + d.result[a]['location_name'] + '</td>';
                                dsp += '<td style="text-align:right;">' + d.result[a]['qty_balance'] + ' ' + d.result[a]['product_unit'] + '</td>';
                                // dsp += '<td>';
                                //   dsp += '<button type="button" class="btn-price-choose btn btn-mini btn-primary" data-name="'+d.result[a]['product_price_name']+'" data-price="'+addCommas(d.result[a]['product_price_price'])+'">';
                                //   dsp += '<span class="fas fa-check"></span>';
                                //   dsp += '&nbsp;Gunakan Harga Ini</button>';
                                // dsp += '</td>';
                                dsp += '</tr>';
                            }

                        } else {
                            dsp += '<tr><td colspan="2">Stok tidak ditemukan di Gudang lain</td></tr>';
                        }

                        dsp += '  </tbody>';
                        dsp += '</table>';
                        self.setContentAppend(dsp);

                    }).fail(function () {
                        self.setContent('Something went wrong, Please try again.');
                    });

                },
                onContentReady: function () {
                },
                buttons: {
                    button_2: {
                        text: 'Tutup',
                        btnClass: 'btn-default',
                        keys: ['Escape'],
                        action: function () {
                            //Close
                        }
                    }
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
            transID = 0;
            // $("#form-trans input[id='id_document']").val(0);    
            // if (parseInt($("#id_document").val()) == 0) {
            if(parseInt(transID) == 0){
                $("#form-trans input").not("input[id='id_document']")
                        .not("input[id='tipe']")
                        .not("input[id='tgl']")
                        .not("input[id='tgl_tempo']").val('');
                $("#form-trans select").val(0).trigger('change');
            }
            // $("#btn-new").hide();
            // $("#btn-save").show();
            // $("#btn-cancel").show();
            // loadTransItems();
            loadGoodsItem();
        }
        function formTransCancel() {
            formTransSetDisplay(1);
            transID = 0;
            $("#form-trans input").not("input[id='tipe']").not("input[id='tgl']").not("input[id='tgl_tempo']").val('');
            // $("#form-trans input[id='id_document']").val(0);
            $("#form-trans select").val(0).trigger('change');
            $("#btn-new").show();
            $("#btn-save").show();
            $("#btn-print").hide();
            $("#btn-update").hide();
            // loadTransItems();
        }
        function formWhatsApp(params) {
            // var a = JSON.parse(params);
            // var b = params['trans_id'];
            // console.log(b);
            var d = {
                trans_id: params['trans_id'],
                trans_number: params['trans_number'],
                trans_date: params['trans_date'],
                trans_total: params['trans_total'],
                contact_id: params['contact_id'],
                contact_name: params['contact_name'],
                contact_phone: params['contact_phone']
            }

            var content = '';
            content += 'Apakah anda ingin mengirim ini ?<br><br>';
            // content += 'Nomor: <b>'+d['trans_number']+'</b><br>';
            // content += 'Tanggal: <b>'+d['trans_date']+'</b><br>'; 
            // content += 'Total: <b>'+addCommas(d['trans_total'])+'</b><br><br>';
            // content += 'Kepada<br> Nomor: <b>'+d['contact_phone']+'</b><br>Customer: <b>'+d['contact_name']+'</b>';

            let title = 'Kirim via WhatsApp';
            $.confirm({
                title: title,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                // autoClose: 'button_2|30000',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation: 'zoom', closeAnimation: 'bottom', animateFromElement: false, useBootstrap: true,
                content: function () {
                    var dsp = '';
                    dsp += content;
                    return dsp;
                },
                onContentReady: function (e) {
                    let self = this;
                    let content = '';
                    let dsp = '';

                    // dsp += '<div>'+content+'</div>';
                    dsp += '<form id="jc_form">';
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                    dsp += '<div class="col-md-6 col-xs-12 col-sm-12 padding-remove-left">';
                    dsp += '    <div class="form-group">';
                    dsp += '    <label class="form-label">Nomor Invoice</label>';
                    dsp += '        <input id="jc_number" name="jc_number" class="form-control" value="' + d['trans_number'] + '" readonly>';
                    dsp += '    </div>';
                    dsp += '</div>';
                    dsp += '<div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">';
                    dsp += '    <div class="form-group">';
                    dsp += '    <label class="form-label">Tgl Transaksi</label>';
                    dsp += '        <input id="jc_date" name="jc_date" class="form-control" value="' + d['trans_date'] + '" readonly>';
                    dsp += '    </div>';
                    dsp += '</div>';
                    dsp += '</div>';
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '<div class="col-md-6 col-xs-12 col-sm-12 padding-remove-left">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Nama Penerima</label>';
                        dsp += '        <input id="jc_contact_name" name="jc_contact_name" class="form-control" value="' + d['contact_name'] + '">';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Nomor Whatsapp</label>';
                        dsp += '        <input id="jc_contact_number" name="jc_contact_number" class="form-control" value="' + d['contact_phone'] + '">';
                        dsp += '    </div>';
                        dsp += '</div>';
                    dsp += '</div>';
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);
                    // self.buttons.button_1.disable();
                    // self.buttons.button_2.disable();

                    // this.$content.find('form').on('submit', function (e) {
                    //      e.preventDefault();
                    //      self.$$formSubmit.trigger('click'); // reference the button and click it
                    // });
                },
                buttons: {
                    button_1: {
                        text: 'Kirim',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function (e) {
                            let self = this;

                            let name = self.$content.find('#jc_contact_name').val();
                            let number = self.$content.find('#jc_contact_number').val();

                            if (!name) {
                                $.alert('Nama Penerima diisi dahulu');
                                return false;
                            } else if (!number) {
                                $.alert('Nomor Whatsapp diisi dahulu');
                                return false;
                            } else {
                                var data = {
                                    action: 'whatsapp-send-message-inventory-goods-out',
                                    trans_id: d['trans_id'],
                                    contact_id: d['contact_id'],
                                    contact_name: name,
                                    contact_phone: number,
                                }
                                $.ajax({
                                    type: "POST",
                                    url: url_message,
                                    data: data,
                                    dataType: 'json',
                                    cache: false,
                                    beforeSend: function () {},
                                    success: function (d) {
                                        let s = d.status;
                                        let m = d.message;
                                        let r = d.result;
                                        if (parseInt(d.status) == 1) {
                                            notif(1, d.message);
                                            index.ajax.reload(null, false);
                                            // $("#modal-trans-save").modal('hide');
                                        } else {
                                            notif(0, d.message);
                                        }
                                    },
                                    error: function (xhr, status, err) {}
                                });
                            }
                        }
                    },
                    button_2: {
                        text: 'Tidak Jadi',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function () {
                            //Close
                        }
                    }
                }
            });
        }        
        // loadGoodsItem();
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
            "trans_branch_id_2",
            "gudang",
            "syarat_pembayaran"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
    function formTransItemSetDisplay(value) { // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        if (value == 1) {
            var flag = true;
        } else {
            var flag = false;
        }
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
    function printFromUrl(url) {
        var beforeUrl = 'intent:';
        var afterUrl = '#Intent;';
        // Intent call with component
        //afterUrl += 'component=ru.a402d.rawbtprinter.activity.PrintDownloadActivity;'
        afterUrl += 'package=ru.a402d.rawbtprinter;end;';
        document.location = beforeUrl + encodeURI(url) + afterUrl;
        return false;
    }    
</script>