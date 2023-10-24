<script>
    var identity            = "<?php echo $identity; ?>";
    var url                 = "<?= base_url('order/manage'); ?>";
    var url_trans           = "<?= base_url('transaksi/manage'); ?>";    
    var url_message         = "<?= base_url('message'); ?>";
    var url_print           = "<?= base_url('order/prints'); ?>";
    var url_print_order     = "<?= base_url('order/print_order'); ?>";
    var url_print_payment   = "<?= base_url('order/prints'); ?>";
    var url_voucher         = "<?= base_url('voucher'); ?>";
    var url_report          = "<?= base_url('report'); ?>";
    var url_contact         = "<?= base_url('kontak/manage');?>";
    var url_finance         = "<?= base_url('keuangan/manage');?>";    

    var product_image       = "<?= site_url('upload/noimage.png'); ?>";
    var base_url            = "<?= site_url(); ?>";

    let contact_1_alias = "<?php echo $contact_1_alias; ?>";
    let contact_2_alias = "<?php echo $contact_2_alias; ?>";
    let ref_alias = "<?php echo $ref_alias; ?>";     
    let order_alias = "<?php echo $order_alias; ?>";
    let payment_alias = "<?php echo $payment_alias; ?>";
    let dp_alias = "<?php echo $dp_alias; ?>";

    $(document).ready(function () {
        console.log('#search-produk: Pending function, Like 291 to 378');
        // $("body").toggleMenu();
        // $("#horizontal-menu").css('margin-left','0!important');
        // $("#page-content").css("margin-left","0");
        // $("body").condensMenu();  
        // $('#sidebar .start .sub-menu').css('display','none');
        var modal_down_payment  = 0;
        var modal_total         = 0;
        var modal_total_dibayar = 0;
        var modal_total_kembali = 0;

        var button_hit          = 0; //Untuk Ajax yg di hit berkali2
        var whatsapp_config     = "<?php echo $whatsapp_config; ?>";

        $("#start, #end").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        }).on('changeDate', function () {
            order_table.ajax.reload();
        });
        $("#start_2, #end_2").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        }).on('changeDate', function () {
            trans_table.ajax.reload();
        });        

        const autoNumericOption = {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalCharacterAlternative: '.',
            decimalPlaces: 0,
            watchExternalChanges: true //!!!        
        };
        new AutoNumeric('#trans-item-discount', autoNumericOption);
        new AutoNumeric('#method-payment-total-before', autoNumericOption);        
        new AutoNumeric('#method-payment-total', autoNumericOption);
        new AutoNumeric('#method-payment-received', autoNumericOption);
        new AutoNumeric('#method-payment-change', autoNumericOption);
        // new AutoNumeric('#harga_beli', autoNumericOption);
        new AutoNumeric('#harga', autoNumericOption);
        new AutoNumeric('#qty', autoNumericOption);
        // new AutoNumeric('#total_down_payment', autoNumericOption);

        var order_table = $("#table_data_order").DataTable({
            // "processing": true,
            // scrollY: '200px',
            // scrollCollapse: true,
            "responsive": true,
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'pos-load';
                    d.tipe = identity;
                    d.date_start = $("#start").val();
                    d.date_end = $("#end").val();
                    d.length = $("#filter_length").find(':selected').val();
                    d.kontak = $("#filter_kontak").find(':selected').val();
                    d.search = {
                        value: $("#filter_search").val()
                    };
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": "Tanggal", "searchable": true, "orderable": true, "width":"15%"},
                {"targets": 1, "title": "Nomor "+order_alias, "searchable": true, "orderable": true, "width":"15%"},
                {"targets": 2, "title": contact_1_alias, "searchable": false, "orderable": true, "className": "text-left", "width":"40%"},
                {"targets": 3, "title": "Total", "searchable": true, "orderable": true, "width":"15%"},
                {"targets": 4, "title": "Action", "searchable": false, "orderable": false, "width":"15%"}
            ],
            "order": [
                [0, 'desc']
            ],
            "columns": [{
                    'data': 'order_date_format',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += data;
                        if (row.order_label == undefined) {
                            // dsp += '<br><span class="label btn-label" style="cursor:pointer;color:white;background-color:#929ba1;padding:1px 4px;" data-order-id="' + row.order_id + '">Label</span>';
                        } else {

                            var bgcolor = '#929ba1';
                            var color = 'white';
                            var icon = '';

                            if (row.order_label !== undefined) {
                                icon = row.label_icon;
                                bgcolor = row.label_background;
                                color = row.label_color;
                            }

                            dsp += '<br><span class="label btn-label" data-order-id="' + row.order_id + '" style="cursor:pointer;color:' + color + ';background-color:' + bgcolor + ';padding:1px 4px;">';
                            dsp += '<span class="' + icon + '">&nbsp;';
                            dsp += row.order_label;
                            dsp += '</span>';
                            dsp += '</span>';
                        }
                        return dsp;
                    }                    
                }, {
                    'data': 'order_number'
                }, {
                    'data': 'contact_name',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<label class="label label-primary">'+ row.contact_name +'</label>';
                        // dsp += '&nbsp;<label class="label">' + row.ref_name + '</label>';
                        
                        if(row.order_contact_id_2 !== undefined){
                            dsp += '&nbsp;<label class="label">'+row.employee_name+'</label>';
                        }
                        if(row.ref_name !== undefined){
                            dsp += '&nbsp;<label class="label label-inverse">'+row.ref_name+'</label>'
                        }

                        if (parseInt(row.order_flag) == 1) {
                            dsp += '&nbsp;<label class="label label-success">Lunas</label>';
                        }else if (parseInt(row.order_flag) == 4) {
                            dsp += '&nbsp;<label class="label label-danger">Batal</label>';
                        }
                        // dsp += '<br><b>' + row.contact_name + '</b>';
                        // dsp += '<br><a class="btn-contact-info" data-id="' + row.order_contact_id + '" data-type="order" style="cursor:pointer;">';
                        // dsp += '<span class="hide fas fa-user-tie"></span>&nbsp;' + row.contact_name;

                        // dsp += '</a>';
                        return dsp;
                    }
                }, {
                    'data': 'order_total', className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // dsp += addCommas(row.order_subtotal);
                        dsp += '<a class="btn-order-item-info" data-id="' + row.order_id + '" data-session="' + row.order_session + '" data-order-number="' + row.order_number + '" data-contact-name="' + row.contact_name + '" data-type="order" style="cursor:pointer;">';
                        dsp += addCommas(row.order_total);
                        dsp += '</a>';

                        if (parseFloat(row.order_with_dp) > 0) {
                            dsp += '<br><span class="label" style="color:white;background-color:#7e7e7e;padding:2px 4px;"><span class="fas fa-thumbs-up"></span>&nbsp;Down Payment</span>';
                        }

                        return dsp;
                    }                    
                }, {
                    'data': 'order_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<button class="btn-print btn btn-mini btn-info" data-id="' + data + '" data-number="' + row.order_number + '" data-session="' + row.order_session + '">';
                        dsp += '<span class="fas fa-print"></span>';
                        dsp += '</button>';
                        
                        if(row.order_trans_id !== undefined){
                            dsp += '&nbsp;<button class="btn-print-payment btn btn-mini btn-success" data-id="' + row.order_trans_id + '" data-session="">';
                            dsp += '<span class="fas fa-print"></span>';
                            dsp += '</button>';
                        }

                        if(whatsapp_config == 1){
                            // dsp += '&nbsp;<button class="btn btn-send-whatsapp btn-mini btn-primary"';
                            // dsp += 'data-number="'+row.order_number+'" data-id="'+data+'" data-total="'+row.order_total+'" data-date="'+row.order_date_format+'" data-contact-id="'+row.contact_id+'" data-contact-name="'+row.contact_name+'" data-contact-phone="'+row.contact_phone_1+'">';
                            // dsp += '<span class="fab fa-whatsapp primary"></span></button>';
                        }
                        // dsp += '<button class="btn-delete btn btn-mini btn-danger" data-id="'+ data +'" data-number="'+row.order_number+'">';
                        // dsp += '<span class="fas fa-trash"></span> Hapus';
                        // dsp += '</button>';  

                        // if (parseInt(row.flag) === 1) {
                        //   dsp += '&nbsp;<button class="btn btn-set-active-order btn-mini btn-primary"';
                        //   dsp += 'data-nomor="'+row.trans_nomor+'" data-kode="'+row.kode+'" data-id="'+data+'" data-flag="'+row.trans_flag+'">';
                        //   dsp += '<span class="fas fa-check-square primary"></span></button>';
                        // }else{ 
                        //   dsp += '&nbsp;<button class="btn btn-set-active-order btn-mini btn-danger"';
                        //   dsp += 'data-nama="'+row.nama+'" data-kode="'+row.kode+'" data-id="'+data+'" data-flag="'+row.flag+'">';
                        //   dsp += '<span class="fas fa-times danger"></span></button>';
                        // }

                        return dsp;
                    }
                }]
        });
        var trans_table = $("#table_data_trans").DataTable({
            // "processing": true,
            "serverSide": true,
            "ajax": {
                url: url_trans,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load';
                    d.tipe = 2;
                    d.date_start = $("#start_2").val();
                    d.date_end = $("#end_2").val();
					// d.date_start = $("#filter_date").attr('data-start');
					// d.date_end = $("#filter_date").attr('data-end');                    
                    // d.start = $("#table-data").attr('data-limit-start');
                    // d.length = $("#table-data").attr('data-limit-end'); 
                    d.length = $("#filter_length_2").find(':selected').val();
                    d.kontak = $("#filter_kontak_2").find(':selected').val();
                    d.type_paid = $("#filter_type_paid_2").find(':selected').val();
                    d.search = {
                        value: $("#filter_search_2").val()
                    };
                    // d.user_role =  $("#select_role").val();
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": "Tanggal", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Nomor "+payment_alias, "searchable": true, "orderable": true},
                {"targets": 2, "title": contact_1_alias, "searchable": false, "orderable": true, "className": "text-left"},
                {"targets": 3, "title": "Tagihan", "searchable": true, "orderable": true},
                {"targets": 4, "title": "Status", "searchable": true, "orderable": true},
                {"targets": 5, "title": "Action", "searchable": false, "orderable": false}
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [{
                    'data': 'trans_date_format',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += data;
                        return dsp;
                    }
                }, {
                    'data': 'trans_number',
                    render: function (data, meta, row) {
                        var dsp = '';
                        /* NOT USED
                            dsp += '<a class="btn-edit-trans" data-id="' + row.trans_id + '" style="cursor:pointer;">';
                            dsp += '<span class="fas fa-file-alt"></span>&nbsp;' + row.trans_number;
                            dsp += '</a>';
                            if (row.trans_ref_number != undefined) {
                                dsp += '<br>' + row.trans_ref_number;
                            }
                        */
                        dsp += data;
                        return dsp;
                    }
                }, {
                    'data': 'contact_name',
                    render: function (data, meta, row) {
                        var dsp = '';
                        /*
                            dsp += '<a class="btn-contact-info" data-id="' + row.trans_contact_id + '" data-type="trans" data-trans-type="2" style="cursor:pointer;">';
                            dsp += '<span class="hide fas fa-user-tie"></span>&nbsp;' + row.contact_name;
                            dsp += '</a>';
                            if(row.contact_category_id != undefined){ 
                                dsp += '<br><span class="label btn-label label-inverse" style="padding:1px 4px;">' + row.category_name + '</span>';                             
                            }
                            if(row.trans_sales_id != undefined){ 
                                dsp += '<br><span class="label btn-label" style="padding:1px 4px;">' + row.trans_sales_name + '</span>';                             
                            }
                        */
                        dsp += '<label class="label label-primary">'+ row.contact_name +'</label>';                                                
                        return dsp;
                    }
                }, {
                    'data': 'trans_total', className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // dsp += addCommas(row.order_subtotal);
                        // dsp += '<a class="btn-trans-item-info" data-id="' + row.trans_id + '" data-session="' + row.trans_session + '" data-trans-number="' + row.trans_number + '" data-contact-name="' + row.contact_name + '" data-trans-type="' + row.trans_type + '" data-type="trans" style="cursor:pointer;">';
                        dsp += addCommas((parseFloat(row.trans_total_dpp) + parseFloat(row.trans_total_ppn)) - parseFloat(row.trans_discount));
                        // dsp += '</a>';
                        return dsp;
                    }
                }, {
                    'data': 'trans_total', className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';

                        if (parseInt(row.trans_paid) == 1) {
                            var rest_of_bill = 0;
                        } else if (parseInt(row.trans_paid) == 0) {
                            var rest_of_bill = row.trans_total - row.trans_total_paid;
                        }

                        /*
                            Menampilkan Jumlah Sisa Piutang
                            dsp += '<a class="btn-trans-payment-info" data-id="' + row.trans_id + '" data-session="' + row.trans_session + '" data-trans-number="' + row.trans_number + '" data-contact-name="' + row.contact_name + '" data-trans-type="' + row.trans_type + '" data-trans-total="' + row.trans_total + '" data-type="finance" style="cursor:pointer;">';
                            dsp += addCommas(rest_of_bill);
                            dsp += '</a>';
                        */

                        var date_due_over = parseInt(row.date_due_over);
                        if (row.trans_paid == 0) {
                            if (date_due_over > 0) {
                                dsp += '<span class="label label-danger" style="color:white;background-color:#1b3148;padding:1px 4px;"> ' + date_due_over + ' hari</span><span class="label" style="color:white;background-color:#ff6665;padding:1px 4px;">Jatuh Tempo</span>';
                            }
                        } else if (row.trans_paid == 1) {
                            dsp += '<span class="label label-success" style="color:white;background-color:#ce83f5;padding:2px 4px;">Lunas</span>&nbsp;';

                            if(parseInt(row.trans_paid_type) == 1){
                                dsp += '<label class="label label-inverse" style="padding:2px 4px;">Cash</label>';
                            }else if(parseInt(row.trans_paid_type) == 2){
                                dsp += '<label class="label label-inverse" style="padding:2px 4px;">Bank Transfer</label>';
                            }else if(parseInt(row.trans_paid_type) == 3){
                                dsp += '<label class="label label-inverse" style="padding:2px 4px;">EDC</label>';
                            }else if(parseInt(row.trans_paid_type) == 4){
                                dsp += '<label class="label label-inverse" style="padding:2px 4px;">EDC Debit</label>';
                            }else if(parseInt(row.trans_paid_type) == 5){
                                dsp += '<label class="label label-inverse" style="padding:2px 4px;">QRIS</label>';
                            }else if(parseInt(row.trans_paid_type) == 6){
                                dsp += '<label class="label label-primary" style="padding:2px 4px;">Link Payment</label>';
                            }else if(parseInt(row.trans_paid_type) == 7){
                                dsp += '<label class="label label-primary" style="padding:2px 4px;">e-Wallet</label>';
                            }else if(parseInt(row.trans_paid_type) == 8){
                                dsp += '<label class="label label-inverse" style="padding:2px 4px;">Deposit</label>';
                            }else{

                            }
                        }
                        return dsp;
                    }
                }, {
                    'data': 'trans_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '&nbsp;<button class="btn-print-payment btn btn-mini btn-success" data-id="' + row.trans_id + '" data-session="">';
                        dsp += '<span class="fas fa-print"></span>';
                        dsp += '</button>';

                        if(whatsapp_config == 1){
                            dsp += '&nbsp;<button class="btn btn-send-whatsapp btn-mini btn-primary"';
                            dsp += 'data-number="'+row.trans_number+'" data-id="'+data+'" data-total="'+row.trans_total+'" data-date="'+row.trans_date_format+'" data-contact-id="'+row.contact_id+'" data-contact-name="'+row.contact_name+'" data-contact-phone="'+row.contact_phone_1+'">';
                            dsp += '<span class="fab fa-whatsapp primary"></span></button>'
                        }
                        return dsp;
                    }
                }]
        });        

        //Datatable Order Config
        $("#table_data_order_filter").css('display', 'none');
        $("#table_data_order_length").css('display', 'none');         
        $('#table_data_order').on('page.dt', function () {
            var info = order_table.page.info();
            var limit_start = info.start;
            var limit_end = info.end;
            var length = info.length;
            var page = info.page;
            var pages = info.pages;
            $("#table_data_order").attr('data-limit-start', limit_start);
            $("#table_data_order").attr('data-limit-end', limit_end);
        });
        $("#filter_kontak").on('change', function (e) {
            order_table.ajax.reload();
        });  
        $("#filter_length").on('change', function (e) {
            var value = $(this).find(':selected').val();
            $('select[name="table_data_order_length"]').val(value).trigger('change');
            order_table.ajax.reload();
        });
        $("#filter_search").on('input', function (e) {
            var ln = $(this).val().length;
            if (parseInt(ln) > 3) {
                order_table.ajax.reload();
            }
        });

        //Datatable Trans Config
        $("#table_data_trans_filter").css('display', 'none');
        $("#table_data_trans_length").css('display', 'none');         
        $('#table_data_trans').on('page.dt', function () {
            var info = trans_table.page.info();
            var limit_start = info.start;
            var limit_end = info.end;
            var length = info.length;
            var page = info.page;
            var pages = info.pages;
            $("#table_data_trans").attr('data-limit-start', limit_start);
            $("#table_data_trans").attr('data-limit-end', limit_end);
        });
        $("#filter_kontak_2").on('change', function (e) {
            trans_table.ajax.reload();
        });  
        $("#filter_length_2").on('change', function (e) {
            var value = $(this).find(':selected').val();
            $('select[name="table_data_trans_length"]').val(value).trigger('change');
            trans_table.ajax.reload();
        });
        $("#filter_search_2").on('input', function (e) {
            var ln = $(this).val().length;
            if (parseInt(ln) > 3) {
                trans_table.ajax.reload();
            }
        });        
        $("#filter_type_paid_2").on('change', function (e) {
            trans_table.ajax.reload();
        });
        formTransNew();
        formTransItemSetDisplay(0);
        // loadProductTab();
        loadRoom({});        
        // loadOrderItem({ref_id:0,contact_id:0});
        // loadUnpaidOrder();
        // loadPaymentItem();
        // loadProductTabDetail({category_id:1,search:'ayam'});

        $('#order_contact_id').select2({
            placeholder: {
                id: '0',
                text: contact_1_alias
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 2, //1=Supplier, 2=Asuransi
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
            escapeMarkup: function (m) {
                return m;
            },
            templateSelection: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return '<i class="fas fa-user-check ' + d.id.toLowerCase() + '"></i> ' + d.text;
            },
            templateResult: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return '<i class="fas fa-user-check ' + d.id.toLowerCase() + '"></i> ' + d.text;
            },
        });
        $('#payment_contact_id').select2({
            placeholder: {
                id: '0',
                text: contact_1_alias
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 2, //1=Supplier, 2=Asuransi
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
            escapeMarkup: function (m) {
                return m;
            },
            templateSelection: function (d) {
                if (!d.id) {
                    return d.text;
                }
                if ($.isNumeric(d.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    if(d['contact_session'] != undefined){
                        $(d.element).attr('data-contact-session',d.contact_session);
                        console.log('Sess: '+d.contact_session);
                    }                               
                    return d.text;
                } else {
                    return '<i class="fas fa-plus ' + d.id.toLowerCase() + '"></i> ' + d.text;
                }
                // return '<i class="fas fa-user-check ' + d.id.toLowerCase() + '"></i> ' + d.text;
            },
            templateResult: function (datas) {
                if (!datas.id) {
                    return datas.text;
                }
                return '<i class="fas fa-user-check ' + datas.id + '"></i> ' + datas.text;
            }
        });
        $('#order_sales_id').select2({
            placeholder: {
                id: '0',
                text: contact_2_alias
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 3, //3=Karyawan
                        source: 'contacts-use-type'
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
            escapeMarkup: function (m) {
                return m;
            },
            templateSelection: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return '<i class="fas fa-user-check ' + d.id.toLowerCase() + '"></i> ' + d.text;
            },
            templateResult: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return '<i class="fas fa-user-check ' + d.id.toLowerCase() + '"></i> ' + d.text;
            },
        });        
        $('#ref').select2({ /* Meja / Ruangan */
            readonly:true,
            placeholder: '<i class="fas fa-object-group"></i> '+ref_alias,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 7,
                        source: 'references'
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
            escapeMarkup: function (m) {
                return m;
            },
            templateSelection: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return '<i class="fas fa-table ' + d.id.toLowerCase() + '"></i> ' + d.text;
            },
            templateResult: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return '<i class="fas fa-table ' + d.id.toLowerCase() + '"></i> ' + d.text;
            },
        });
        $('#produk').select2({
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
                        tipe: 2, //1=Supplier, 2=Asuransi
                        // category: 'add-on',
                        source: 'products-all'
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
                if(parseInt(data.id) > 0){
                    return data.text;
                }
            }
        });
        $('#search-produk').select2({
            placeholder: '<i class="fas fa-search"></i> Cari Produk',
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1, //1=Supplier, 2=Asuransi
                        category: '>1',
                        source: 'products'
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
            escapeMarkup: function (m) {
                return m;
            },
            templateSelection: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return '<i class="fas fa-box ' + d.id.toLowerCase() + '"></i> ' + d.text;
            },
            templateResult: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return '<i class="fas fa-box ' + d.id.toLowerCase() + '"></i> ' + d.text;
            },
        });
        $('#filter_kontak').select2({
            placeholder: {
                id: '0',
                text: 'Semua'
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 2, //1=Supplier, 2=Asuransi
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
            escapeMarkup: function (m) {
                return m;
            },
            templateSelection: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return d.text;
            },
            templateResult: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return d.text;
            },
        });
        $('#filter_kontak_2').select2({
            placeholder: {
                id: '0',
                text: 'Semua'
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 2, //1=Supplier, 2=Asuransi
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
            escapeMarkup: function (m) {
                return m;
            },
            templateSelection: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return d.text;
            },
            templateResult: function (d) {
                if (!d.id) {
                    return d.text;
                }
                return d.text;
            },
        });

        $("#order_contact_id").on("change", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var this_val = $(this).find(':selected').val();
            if (this_val == '-') {
                $("#modal-contact").modal('toggle');
                formKontakNew();
            }
        });
        $("#produk").on("change", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).find(':selected').val();
            if (parseInt(id) !== 0) {
                var data = {
                    action: 'read',
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
                            $("#form-trans-item input[name='qty']").val(1);
                            $("#form-trans-item input[name='satuan']").val(d.result.product_unit);
                            $("#form-trans-item input[name='harga']").val(d.result.product_price_sell);
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
        });
        $("#search-produk").on('change', function (e) { //Ajax to pos-create-item
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).val();
            var ref_id = $("#ref").find(":selected").val();       
            var next = true;
            if (id == undefined) {
                next = false;
            }

            if(id == '-'){
                next = false;
            }

            if(parseInt(id) == 0) {
                next = false;
            }

            if(ref_id == 0){
                notif('Room belum terpilih');
                next = false;
            }

            if(next){
                var prepare = {
                    tipe: identity,
                    produk: id,
                    satuan: 'Pcs',
                    qty: 1,
                    harga: 10000,
                    ref_id:ref_id
                };
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'pos-create-item',
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
                            notif(1, d.message);
                            formTransItemNew();
                            loadOrderItem({ref_id:ref_id,contact_id:0});
                            // $("#search-produk").html();
                            $("#search-produk").val(0).trigger('change');
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

        /* Navigation */
        $(document).on("click",".btn_show_order", function (e) {
            activeTab('tab3');             
        });
        $(document).on("click",".btn_back_order", function (e) {
            activeTab('tab1');             
        });
        $(document).on("click",".btn_show_trans", function (e) {
            activeTab('tab2');             
        });  
        $(document).on("click",".btn_menu_toggle", function (e) {
            var i = $(this).attr('data-id');
            if(i == 1){
                activeTab('tab3');  
            }else if(i == 2){
                activeTab('tab2');  
            }else if(i == 3){
                loadPaymentItem();
            }else if(i == 4){
                var url_redirect = "<?= base_url('login/logout'); ?>";
                $.redirect(url_redirect,[],"POST","_self"); 
            }           
        });                
        

        /* Order / Booking CRUD Form */
        // $(document).on("click", "#btn-new-order", function (e) {
        //     e.preventDefault();
        //     $("#div_order_data").hide(300);
        //     $("#div_order_form").show(300);
        // });
        // $(document).on("click", ".btn-close-order", function (e) {
        //     e.preventDefault();
        //     $("#div_order_form").hide(300);
        //     $("#div_order_data").show(300);  
        // });        
        $(document).on("click", "#btn-save-order", function (e) { // Save Button
            e.preventDefault();
            var next = true;

            var total_item = $("#total_produk").val();
            var ref_id = $("#ref").find(":selected").val();

            var order_is_member       = parseInt($(".order_contact_checkbox").attr('data-flag'));
            var order_contact_id      = $("#order_contact_id").find(':selected').val();
            var order_contact_name    = $("#order_contact_name").val();            

            if(order_is_member == 1){ //Member
                if((order_contact_id == 0) || (order_contact_id < 1) || (order_contact_id == undefined) || (order_contact_id == 'undefined')){
                    next=false;
                    notif(0,contact_1_alias+' wajib dipilih');
                }
            }else{ //Non Member
                if(parseInt(order_contact_name.length) < 2){
                    next=false;
                    notif(0,'Non '+contact_1_alias+' wajib tulis Nama');
                }
            }

            if(next){
                if(($("select[id='ref']").find(':selected').val() == 0) || ($("select[id='ref']").find(':selected').val() == undefined)){
                    notif(0,ref_alias+' belum terpilih');
                    next=false;
                }     
            }

            if(next){
                if (parseInt(total_item) < 1) {
                    notif(0, 'Minimal harus ada satu produk diinput');
                    next = false;
                }

                if (next == true) {
                    if (($("select[id='order_sales_id']").find(':selected').val() == 0) || ($("select[id='order_sales_id']").find(':selected').val() == undefined)) {
                        notif(0, contact_2_alias+' harus dipilih dahulu');
                        next = false;
                    }
                }

                if (next == true) {
                    // var dp = $("#total_down_payment").val();
                    // if(parseFloat($("#total_down_payment")) == 0){

                    // }

                    if (parseFloat($("#total_down_payment").val()) > 0) {
                        var total = $("#total").val();
                        var total_dp = $("#total_down_payment").val();
                        if (parseFloat(removeCommas(total_dp)) > parseFloat(removeCommas(total))) {
                            notif(0, 'Down Payment tidak boleh melebihi Total');
                            next = false;
                        }
                        if (parseFloat(removeCommas(total_dp)) == parseFloat(removeCommas(total))) {
                            notif(0, 'Down Payment harus lebih rendah dari Total');
                            next = false;
                        }
                    }

                }

                if (next == true) {
                    $.confirm({
                        title: 'Konfirmasi '+order_alias,
                        // content: 'Proses ini akan membuat '+order_alias+' akan masuk ke Daftar '+payment_alias+'',
                        content:order_alias+' akan dilanjutkan untuk mendapatkan nomor '+order_alias,
                        columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                        autoClose: 'button_2|10000',
                        closeIcon: true,
                        closeIconClass: 'fas fa-times',
                        buttons: {
                            button_1: {
                                text: 'Simpan',
                                btnClass: 'btn-primary',
                                keys: ['enter'],
                                action: function () {

                                    //Fetch ID of Trans Item ID
                                    var trans_item_list_id = [];
                                    $('.tr-trans-item-id').each(function () {
                                        trans_item_list_id.push($(this).data('id'));
                                    });

                                    //Prepare all Data
                                    var prepare = {
                                        tipe: identity,
                                        tgl: $("input[id='tgl']").val(),
                                        kontak: $("select[id='order_contact_id']").find(':selected').val(),
                                        order_contact_checkbox: $(".order_contact_checbox").attr('data-flag'),
                                        order_contact_name: $("#order_contact_name").val(),
                                        order_contact_phone: $("#order_contact_phone").val(),
                                        ref: ref_id,
                                        order_list: trans_item_list_id,
                                        total_down_payment: $("#total_down_payment").val(),
                                        total: removeCommas($("#total").val()),
                                        order_sales_id: $("select[id='order_sales_id']").find(':selected').val()
                                    }
                                    var prepare_data = JSON.stringify(prepare);
                                    var data = {
                                        action: 'pos-create',
                                        data: prepare_data
                                    };
                                    $.ajax({
                                        type: "POST",
                                        url: url,
                                        data: data,
                                        dataType: 'json',
                                        cache: false,
                                        beforeSend: function () {
                                            $("#btn-save-order").attr('disabled', true);
                                            $("#btn-save-order").html('<span class="fas fa-spinner fa-spin"></span> Loading');                                        
                                        },
                                        success: function (d) {
                                            if (parseInt(d.status) == 1) { /* Success Message */
                                                notif(1,'Berhasil membuat '+order_alias+' '+d.result.order_number);
                                                $("#form-trans input[name=id_document]").val(d.result.order_id);
                                                $("#form-trans input[name=nomor]").val(d.result.order_number);
                                                order_table.ajax.reload();

                                                $("#btn-save-order").attr('disabled', false);
                                                $("#btn-save-order").html('<span class="fas fa-save"></span> Simpan');                                             

                                                // loadOrderItem({ref_id:ref_id,contact_id:0});
                                                loadRoom({});                                            
                                                $(".btn-print").attr('data-id', d.result.order_id);
                                                $(".btn-print").attr('data-number', d.result.order_number);
                                                // $("#modal-trans-save").modal({backdrop: 'static', keyboard: false});
                                                formTransCancel();
                                                formTransNew();
                                                formTransItemSetDisplay(0);
                                                $("#total_down_payment").attr('readonly', true);
                                                $("#total_down_payment").val(0);
                                                formOrder({action:0});
                                                checkBoxOrderNonmember(1);
                                            } else { //Error
                                                notif(0, d.message);
                                            }
                                        },
                                        error: function (xhr, Status, err) {
                                            notif(0, 'Error');
                                            $("#btn-save-order").attr('disabled', false);
                                            $("#btn-save-order").html('<span class="fas fa-save"></span> Simpan');                                        
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
                }
            }
        });
        $(document).on("click", ".btn-edit-order", function (e) { // NOT USED Edit Button
            // formMasterSetDisplay(0);
            $("#form-trans input[name='kode']").attr('readonly', true);

            e.preventDefault();
            var id = $(this).data("id");
            var data = {
                action: 'read',
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
                        // notif(1,d.result.id);ss
                        $("#form-trans input[name='id_document']").val(d.result.id);
                        $("#form-trans input[name='kode']").val(d.result.kode);
                        $("#form-trans input[name='nama']").val(d.result.nama);
                        $("#form-trans input[name='keterangan']").val(d.result.keterangan);
                        $("#form-trans input[name='harga_beli']").val(d.result.harga_beli);
                        $("#form-trans input[name='harga_jual']").val(d.result.harga_jual);
                        $("#form-trans input[name='stok_minimal']").val(d.result.stok_minimal);
                        $("#form-trans input[name='stok_maksimal']").val(d.result.stok_maksimal);
                        $("#form-trans textarea[name='keterangan']").val(d.result.keterangan);
                        $("#form-trans select[name='satuan']").val(d.result.satuan).trigger('change');
                        $("#form-trans select[name='status']").val(d.result.flag).trigger('change');

                        $("#btn-new-order").hide();
                        $("#btn-save-order").hide();
                        $("#btn-update-order").show();
                        $("#btn-cancel-order").show();
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
        $(document).on("click", "#btn-update-order", function (e) { // NOT USED Update Button
            e.preventDefault();
            var next = true;
            var id = $("#form-trans input[name='id_dokumen']").val();
            var kode = $("#form-trans input[name='kode']");
            var nama = $("#form-trans input[name='nama']");

            if (id == '') {
                notif(0, 'ID tidak ditemukan');
                next = false;
            }

            if (kode.val().length == 0) {
                notif(0, 'Kode wajib diisi');
                kode.focus();
                next = false;
            }

            if (nama.val().length == 0) {
                notif(0, 'Nama wajib diisi');
                nama.focus();
                next = false;
            }

            if (next == true) {
                if ($("select[id='satuan']").find(':selected').val() == 0) {
                    notif(0, 'Satuan wajib dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='harga_jual']").val().length == 0) {
                    notif(0, 'Harga Jual wajib diisi');
                    $("#harga_jual").focus();
                    next = false;
                }
            }

            if (next == true) {
                var prepare = {
                    tipe: identity,
                    id: $("input[id=id_document]").val(),
                    kode: $("input[id='kode']").val(),
                    nama: $("input[id='nama']").val(),
                    keterangan: $("textarea[id='keterangan']").val(),
                    harga_beli: $("input[id='harga_beli']").val(),
                    harga_jual: $("input[id='harga_jual']").val(),
                    stok_minimal: $("input[id='stok_minimal']").val(),
                    stok_maksimal: $("input[id='stok_maksimal']").val(),
                    satuan: $("select[id='satuan']").find(':selected').val(),
                    status: $("select[id='status']").find(':selected').val()
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
                            $("#btn-new-order").show();
                            $("#btn-save-order").hide();
                            $("#btn-update-order").hide();
                            $("#btn-cancel-order").hide();
                            $("#form-trans input").val();
                            formMasterSetDisplay(1);
                            notif(1, d.message);
                            order_table.ajax.reload(null, false);
                        } else {
                            // notif(0,d.message);  
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, 'Error');
                    }
                });
            }
        });
        $(document).on("click", ".btn-delete-order", function (e) { // NOT USED Delete Button
            e.preventDefault();
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
                                        order_table.ajax.reload(null, false);
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
        $(document).on("click", "#btn-cancel-order", function () { // NOT USED Cancel Button
            event.preventDefault();
            var ref_id = $("#ref").find(":selected").val();
            alert('Not Used');
            $.confirm({
                title: 'Yakin membatalkan!',
                content: 'Apakah anda ingin membatalkan transaksi ?',
                buttons: {
                    confirm: {
                        btnClass: 'btn-danger',
                        text: 'Batalkan',
                        action: function () {
                            var data = {
                                action: 'cancel',
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        loadOrderItem({ref_id:ref_id,contact_id:0});
                                        formOrder({action:0});
                                    } else {
                                        notif(0, d.message);
                                    }
                                }
                            });
                        }
                    },
                    cancel: {
                        btnClass: 'btn-success',
                        text: 'Tidak Jadi',
                        action: function () {
                            // $.alert('Canceled!');
                        }
                    }
                }
            });
        });
        $(document).on("click", ".btn-set-active-order", function (e) { // NOT USED Set Flag Button
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
                                kode: kode
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
                                        order_table.ajax.reload();
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
        $(document).on("click", ".order_contact_checkbox", function (e) {
            var check = $(".order_contact_checkbox").attr('data-flag');
            if (check == 0) {
                checkBoxOrderNonmember(0);
            } else {
                checkBoxOrderNonmember(1);
            }
        });   

        $(document).on("click", ".btn-move-room-order", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var order_id = $(this).attr('data-order-id');
            var order_number = $(this).attr('data-order-number');          
            var ref_id = $(this).attr('data-ref-id');          
            var ref_name = $(this).attr('data-ref-name');                               
            var next = true;

            let title   = 'Pindahkan '+ref_alias+' ?';
            let content = 'Apakah anda ingin memindahkan <br><b>'+ref_name+'</b>';
            $.confirm({
                title: title,
                content: content,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content: function(){
                    let self = this;

                    var prepare = {
                        ref_type: 7,
                        search: ''
                    };
                    var data = {
                        action: 'pos-load-ref',
                        data: JSON.stringify(prepare)
                    };
                    return $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        dataType: 'json',
                        cache: 'false',
                    }).done(function (d) {
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                    }).fail(function(){
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                onContentReady: function(){
                    let self    = this;
                    let content = '';
                    let dsp     = '';
                    let d = self.ajaxResponse.data;
                    var total_records = parseInt(d.total_records);

                    dsp += '<form id="jc_form">';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Pilih '+ref_alias+' Tujuan</label>';
                        dsp += '        <select id="jc_select" name="jc_select" class="form-control">';
                        dsp += '            <option value="0">Pilih</option>';
                        for (var b = 0; b < total_records; b++) {
                            if(parseInt(d.result[b]['ref_use_type']) < 1){
                                dsp += '            <option value="'+d.result[b]['ref_id']+'">'+d.result[b]['ref_name']+'</option>';
                            }
                        }
                        dsp += '        </select>';
                        dsp += '    </div>';
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
                        text:'Proses',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            if (parseInt(order_id) > 0) {

                                let self      = this;
                                let select    = self.$content.find('#jc_select').val();

                                if(select == 0){
                                    $.alert(ref_alias+' mohon dipilih dahulu');
                                    return false;
                                } else{
                                    var prepare = {
                                        tipe: identity,
                                        id: order_id,
                                        ref_id_old:ref_id,
                                        ref_id_new:select
                                    };
                                    var prepare_data = JSON.stringify(prepare);
                                    var data = {
                                        action: 'update-order-ref',
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
                                                notif(1, d.message);
                                                // $("#modal-payment-list").modal('toggle');
                                                // loadPaymentItem();
                                                loadRoom({});
                                                // order_table.ajax.reload();
                                            } else { //No Data
                                                notif(0, d.message);
                                            }
                                        },
                                        error: function (xhr, Status, err) {
                                            notif(0, err);
                                        }
                                    });
                                }
                            }else{
                                notif(0,'Data tidak ditemukan');
                            }
                        }
                    },
                    button_2: {
                        text: 'Batal',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }
                }
            });

        });
        $(document).on("click", "#btn-addon-order", function (e) {
            var order_id = $(this).attr('data-order-id');
            // alert(order_id);
            var order_number = $(this).attr('data-order-number');
            var order_ref_name = $(this).attr('data-ref-name');
            if (parseInt(order_id) > 0) {
                setTimeout(function () {
                    $("#modal-order-addon").modal('show');
                    $("#modal-order-addon-title").html('Tambahan Produk Untuk ' + order_ref_name);
                    $("#btn-save-order-item-addon").attr('data-order-id', order_id);
                }, 1000);
            } else {
                notif(0, 'Order harus dipilih');
            }
        });
        $(document).on("click", ".btn-cancel-order", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var order_id        = $(this).attr('data-order-id');
            var order_number    = $(this).attr('data-order-number');          
            var ref_id          = $(this).attr('data-ref-id');          
            var ref_name        = $(this).attr('data-ref-name');          
            var order_total     = $(this).attr('data-grand-total');                        
            var next            = true;

            let title   = 'Konfirmasi Pembatalan !';
            $.confirm({
                title: title,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
                autoClose: 'button_2|80000',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content: function(){
                },
                onContentReady: function(e){
                    let self    = this;
                    let content = '';
                    let dsp     = '';
                    let content2 = 'Apakah anda ingin membatalkan '+order_alias+' <br><b>'+order_number+'</b><br><b>'+ref_name+'</b><br><b>'+addCommas(order_total)+'</b>';
                    content2 += '<br><br>Silahkan masukkan user dan password yg mempunyai hak memverifikasi';

                    dsp += content2;
                    dsp += '<form id="jc_form">';
                        dsp += '<div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">User</label>';
                        dsp += '        <input id="jc_input1" name="jc_input1" class="form-control">';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Password</label>';
                        dsp += '        <input id="jc_input2" name="jc_input2" type="password" class="form-control">';
                        dsp += '    </div>';
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
                        text:'Proses',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){
                            let self      = this;
            
                            let input1     = self.$content.find('#jc_input1').val();
                            let input2     = self.$content.find('#jc_input2').val();                            
                            
                            if(!input1){
                                $.alert('User mohon diisi dahulu');
                                return false;
                            } else if(!input2){
                                $.alert('Password mohon diisi dahulu');
                                return false;
                            } else{
                                if (parseInt(order_id) > 0) {
                                    var prepare = {
                                        tipe: identity,
                                        id: order_id,
                                        ref_id:ref_id,
                                        user:input1,
                                        password:input2
                                    };
                                    var prepare_data = JSON.stringify(prepare);
                                    var data = {
                                        action: 'pos-cancel',
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
                                                notif(1, d.message);
                                                loadPaymentItem();
                                                loadRoom({});
                                                order_table.ajax.reload();
                                            } else { //No Data
                                                notif(0, d.message);
                                                return false;
                                            }
                                        },
                                        error: function (xhr, Status, err) {
                                            notif(0, err);
                                        }
                                    });
                                }else{
                                    notif(0,order_alias+' tidak ditemukan');
                                }
                            }
                        }
                    },      
                    button_2: {
                        text: 'Tidak Jadi',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                            loadOrderDetail(order_id);
                        }
                    }
                }
            });
        });

        /* Order Item CRUD Form */
        $(document).on("click", "#btn-save-order-item", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // var id = $(this).attr('data-id');
            var ref_id = $("#ref").find(":selected").val();
            var next = true;

            if ($("#produk").find(':selected').val() == 0) {
                notif(0, 'Produk belum dipilih');
                next = false;
            }
            if (next == true) {
                if (ref_id == 0) {
                    notif(0, ref_alias+' belum dipilih');
                    next = false;
                }
            }            
            if (next == true) {
                if ($("#satuan").val().length == 0) {
                    notif(0, 'Satuan tidak ditemukan');
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
                if (parseInt($("#harga").val().length == 0) || ($("#harga").val() == 0)) {
                    notif(0, 'Harga harus diisi');
                    $("#harga").focus();
                    next = false;
                }
            }
            if (next == true) {
                if(parseFloat($("#harga").val()) > 0){
                    var prepare = {
                        tipe: identity,
                        produk: $("#produk").find(':selected').val(),
                        satuan: $("#satuan").val(),
                        qty: $("#qty").val(),
                        harga: $("#harga").val(),
                        ref_id:ref_id
                    };
                    var prepare_data = JSON.stringify(prepare);
                    var data = {
                        action: 'pos-create-item',
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
                                loadOrderItem({ref_id:ref_id,contact_id:0});
                            } else { //No Data
                                notif(0, d.message);
                            }
                        },
                        error: function (xhr, Status, err) {
                            notif(0, err);
                        }
                    });
                }else{
                    notif(0,'Gagal, Harga Jual tidak ada');
                }
            }
        });
        $(document).on("click", ".btn-save-order-item", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-id');
            var satuan = $(this).attr('data-satuan');
            var qty = $(this).attr('data-qty');
            var harga = $(this).attr('data-price');
            var ref_id = $("#ref").find(":selected").val();
            var ref_is_use = $("#ref").find(":selected").attr('data-use-type');            
            var next = true;

            // if(next==true){
            //     if(parseInt($("#harga").val().length == 0) || ($("#harga").val() == 0)){
            //         notif(0,'Harga harus diisi');
            //         $("#harga").focus();
            //         next=false;
            //     }
            // }
            if(parseInt(ref_is_use) == 1){
                notif(0,'Gagal, '+ref_alias+' sudah di daftar '+payment_alias);
                next =false;                
            }

            if(next){
                if(ref_id == 0){
                    notif(0,ref_alias+' belum dipilih');
                    next =false;
                }
            }

            if (next == true) {
                if(parseFloat($("#harga").val()) > 0){
                    var prepare = {
                        tipe: identity,
                        produk: id,
                        satuan: satuan,
                        qty: qty,
                        harga: harga,
                        ref_id: ref_id
                    };
                    var data = {
                        action: 'pos-create-item',
                        data: JSON.stringify(prepare)
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
                                notif(1, d.message);
                                formTransItemNew();
                                // formTransNew();
                                // formTransCancel();
                                loadOrderItem({ref_id:ref_id,contact_id:0});
                            } else { //No Data
                                notif(0, d.message);
                            }
                        },
                        error: function (xhr, Status, err) {
                            notif(0, err);
                        }
                    });
                }else{
                    notif(0,'Gagal, Harga Jual tidak ada');
                }
            }
        });
        $(document).on("click", "#btn-save-order-item-addon", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var order_id = $(this).attr('data-order-id');
            var next = true;
            if (parseInt(order_id) == 0) {
                notif(0, 'Pilih Nomor '+order_alias+' dahulu');
                next = false;
            }

            if (next == true) {
                var qty = $("#qty").val();
                var harga = $("#harga").val();
                var satuan = $("#satuan").val();
                var produk = $("#produk").find(':selected').val();
                if (parseInt(produk) == 0) {
                    notif(0, 'Produk harus dipilih');
                    next = false;
                }

                if (next == true) {
                    if (qty.length == 0) {
                        notif(0, 'Qty harus diisi');
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
                    var prepare = {
                        tipe: identity,
                        order_id: order_id,
                        produk: produk,
                        satuan: satuan,
                        qty: qty,
                        harga: harga
                    };
                    var prepare_data = JSON.stringify(prepare);
                    var data = {
                        action: 'pos-create-item-addon',
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
                                notif(1, d.message);
                                loadOrderDetail(d.result.order_id);
                                // formTransItemNew();
                                // loadOrderItem({ref_id:ref_id,contact_id:0});
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
        $(document).on("click", ".btn-save-order-item-plus-minus", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var operator = $(this).attr('data-operator'); /* +/-  */
            var id = $(this).attr('data-id');
            var qty = $(this).attr('data-qty');
            var price = $(this).attr('data-price');
            var discount = $(this).attr('data-discount');
            var ref_id = $("#ref").find(":selected").val();
            var next = true;

            if (id == '') {
                notif(0, 'Produk tidak ditemukan');
                next = false;
            }
            if (next == true) {

                var prepare = {
                    tipe: identity,
                    id: id,
                    operator: operator,
                    qty: qty,
                    price: removeCommas(price),
                    discount: removeCommas(discount)
                };
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'pos-create-item-plus-minus',
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
                            // $("#modal-trans-note").modal('toggle');             
                            loadOrderItem({ref_id:ref_id,contact_id:0});
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
        $(document).on("click", ".btn-save-order-item-note", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var ref_id = $("#ref").find(":selected").val();
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var qty = $(this).attr('data-qty');
            var note = $(this).attr('data-note');

            if (note == 'null') {
                $("#order-item-note").val('');
            } else {
                $("#order-item-note").val(note);
            }
            $("#order-item-note-id").val(id);
            $("#order-item-note-product-name").val(name);

            let title   = '';
            $.confirm({
                title: 'Tambah Catatan',
                icon: 'fas fa-check',
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
                        dsp += '    <label class="form-label">Produk</label>';
                        dsp += '        <input id="order-item-product" name="order-item-product" class="form-control" value="'+name+'" readonly>';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Catatan</label>';
                        dsp += '        <input id="order-item-note" name="order-item-note" class="form-control" value="'+note+'">';
                        dsp += '    </div>';
                        dsp += '</div>';
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);
                    $("#order-item-note").focus();
                },
                buttons: {
                    button_1: {
                        text:'<i class="fas fa-check white"></i> Simpan',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){
                            let self      = this;
            
                            let input     = self.$content.find('#order-item-note').val();
                            
                            if(!input){
                                $.alert('Catatan mohon diisi dahulu');
                                return false;
                            }else{
                                var prepare = {
                                    tipe:identity,
                                    id:id,
                                    note:input
                                };
                                var prepare_data = JSON.stringify(prepare);
                                var data = {
                                    action: 'create-item-note',
                                    data: prepare_data
                                };
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    data: data, dataType: 'json',
                                    cache: 'false',
                                    beforeSend: function() {},
                                    success: function(d) {
                                        let s = d.status;
                                        let m = d.message;
                                        let r = d.result;
                                        if(parseInt(s) == 1){
                                            // notif(s, m);
                                            /*type_your_code_here*/
                                            loadOrderItem({ref_id:ref_id,contact_id:0});
                                        }else{
                                            // notif(s,m);
                                            // notifSuccess(m);
                                        }
                                    },
                                    error: function(xhr, status, err) {}
                                });
                            }
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-window-close"></i> Batal',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }                                                                       
                }
            });
        });    
        $(document).on("click", ".btn-save-order-item-price-other", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var order_item_id = $(this).attr('data-order-item-id'); /* +/-  */
            var product_id = $(this).attr('data-product-id');
            var product_price_id = $(this).attr('data-product-price-id');
            var product_price_name = $(this).attr('data-product-price-name');
            var qty = $(this).attr('data-order-item-qty');
            var product_price_price = $(this).attr('data-product-price-price');
            var ref_id = $("#ref").find(":selected").val();
            var next = true;

            $.confirm({
                title: 'Harga ' + product_price_name,
                content: 'Pasang harga ' + product_price_name + ' ' + addCommas(product_price_price) + ' ?',
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

                            /* hint zz_for or zz_each */

                            if (order_item_id == '') {
                                notif(0, 'Order produk tidak ditemukan');
                                next = false;
                            }

                            if (next == true) {

                                var prepare = {
                                    tipe: identity,
                                    order_item_id: order_item_id,
                                    product_id: product_id,
                                    product_price_id: product_price_id,
                                    product_price_name: product_price_name,
                                    qty: qty,
                                    product_price_price: product_price_price
                                };
                                var prepare_data = JSON.stringify(prepare);
                                var data = {
                                    action: 'pos-update-item-product-price',
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
                                            // $("#modal-trans-note").modal('toggle');             
                                            loadOrderItem({ref_id:ref_id,contact_id:0});
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
        /* Discount click-order harus digabung dengan save-order-item-discount */
        $(document).on("click", ".btn-click-order-item-discount", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $("#modal-payment-list").modal('hide');
            var order_id = $(this).attr('data-order-id');
            var id = $(this).attr('data-id');
            var qty = $(this).attr('data-qty');
            var name = $(this).attr('data-name');
            var price = $(this).attr('data-price');
            var discount = $(this).attr('data-discount');
            var total = $(this).attr('data-total');
            var total_after_discount = $(this).attr('data-total-after-discount');


            var order_number = $(this).attr('data-order-number');
            var order_ref_name = $(this).attr('data-ref-name');
            if (parseInt(order_id) > 0) {
                setTimeout(function () {
                    // $("#modal-order-addon").modal('show'); 
                    // $("#modal-order-addon-title").html('Tambahan Produk Untuk '+ order_ref_name);
                    // $("#btn-save-order-item-addon").attr('data-order-id',order_id);

                    if (id == '') {
                        $("#trans-item-discount").val('');
                    } else {
                        $("#trans-item-discount").val(discount);
                    }
                    $("#trans-item-id").val(id);
                    $("#trans-item-product-name").val(name);
                    $("#trans-item-qty").val(qty);
                    $("#trans-item-price").val(price);
                    $("#trans-item-discount").focus();
                    $("#trans-item-total").val(total);
                    $("#trans-item-total-after-discount").val(total_after_discount);
                    $("#btn-save-order-item-discount").attr('data-id', id);
                    $("#btn-save-order-item-discount").attr('data-order-id', order_id);
                    $("#modal-order-item-discount").modal({backdrop: 'static', keyboard: false});
                }, 1000);
            } else {
                notif(0, order_alias+' harus dipilih');
            }
        });
        $(document).on("click", "#btn-save-order-item-discount", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var order_id = $(this).attr('data-order-id');
            var id = $("#trans-item-id").val();
            var qty = $("#trans-item-qty").val();
            var price = $("#trans-item-price").val();
            var discount = $("#trans-item-discount").val();
            var total_after_discount = $("#trans-item-total-after-discount").val();
            var ref_id = $("#ref").find(":selected").val();
            // var qty = $(this).val;
            // var price = $(this).attr('data-price');        
            var next = true;


            if (id == '') {
                notif(0, 'Produk tidak ditemukan');
                next = false;
            }

            if (next) {
                var subtotal = $("#trans-item-total-after-discount").val();
                subtotal = removeCommas(subtotal);
                if (parseFloat(subtotal) < 0) {
                    next = false;
                    notif(0, 'Subtotal Tidak Boleh Minus')
                }
            }

            if (next == true) {
                var prepare = {
                    tipe: identity,
                    order_id: order_id,
                    id: id,
                    qty: removeCommas(qty),
                    price: removeCommas(price),
                    discount: removeCommas(discount),
                    total: removeCommas(total_after_discount)
                };
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'pos-create-item-discount',
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
                            $("#modal-trans-item-discount").modal('toggle');
                            if (parseInt(order_id) > 0) {
                                loadOrderDetail(order_id);
                            }
                            loadOrderItem({ref_id:ref_id,contact_id:0});
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
        $(document).on("click", ".btn-edit-order-item", function (e) { // NOT USED Edit Item Button
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            var id = $(this).attr('data-id');
            /* hint zz_ajax */
        });
        $(document).on("click", "#btn-update-order-item", function (e) { // NOT USED Update Item Button
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            var id = $(this).attr('data-id');
            /* hint zz_ajax */
        });
        $(document).on("click", ".btn-delete-order-item", function () { // Delete Item Button
            event.preventDefault();
            var id = $(this).attr("data-id");
            var order_id = $(this).attr("data-order-id");
            var kode = $(this).attr("data-kode");
            var nama = $(this).attr("data-nama");
            var ref_id = $("#ref").find(":selected").val();
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
                                order_id: order_id,
                                kode: kode,
                                nama: nama
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        if (parseInt(order_id) > 0) {
                                            $("#modal-payment-list").modal('hide');
                                            // console.log('OrderId : ' + order_id);
                                            loadOrderDetail(d.result.order_id);
                                        }
                                    } else {
                                        notif(0, d.message);
                                    }
                                    loadOrderItem({ref_id:ref_id,contact_id:0});
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
        $(document).on("click", ".btn-delete-order-item-note", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-id');
            var note = $(this).attr('data-note');
            var ref_id = $("#ref").find(":selected").val();        
            var next = true;
            if (parseInt(id) < 1) {
                notif(0, 'Produk tidak ditemukan');
                next = false;
            }
            if (next) {
                $.confirm({
                    title: 'Hapus Catatan!',
                    content: 'Apakah anda ingin menghapus <b>' + note + '</b> ini?',
                    buttons: {
                        confirm: {
                            btnClass: 'btn-danger',
                            text: 'Ya',
                            action: function () {
                                var prepare = {
                                    tipe: identity,
                                    id: id,
                                };
                                var prepare_data = JSON.stringify(prepare);
                                var data = {
                                    action: 'pos-delete-item-note',
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
                                            loadOrderItem({ref_id:ref_id,contact_id:0});
                                        } else { //No Data
                                            notif(0, d.message);
                                        }
                                    },
                                    error: function (xhr, Status, err) {
                                        notif(0, err);
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
            }
        });
        $(document).on("click", ".btn-delete-order-item-discount", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var order_id = $(this).attr('data-order-id');
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var qty = $(this).attr('data-qty');
            var price = $(this).attr('data-price');
            var discount = $(this).attr('data-discount');
            var ref_id = $("#ref").find(":selected").val();
            var next = true;

            if (parseInt(id) < 0) {
                notif(0, 'Produk tidak ditemukan');
                next = false;
            }

            if (next == true) {
                $.confirm({
                    title: 'Hapus Diskon!',
                    content: 'Apakah anda ingin menghapus Diskon <b>' + discount + '</b> yang tertera di <b>' + name + '</b> ini?',
                    buttons: {
                        confirm: {
                            btnClass: 'btn-danger',
                            text: 'Ya',
                            action: function () {
                                var prepare = {
                                    tipe: identity,
                                    order_id: order_id,
                                    id: id,
                                    qty: removeCommas(qty),
                                    price: removeCommas(price)
                                };
                                var prepare_data = JSON.stringify(prepare);
                                var data = {
                                    action: 'pos-delete-item-discount',
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
                                            loadOrderItem({ref_id:ref_id,contact_id:0});
                                            if (parseInt(order_id) > 0) {
                                                $("#modal-payment-list").modal('hide');
                                                loadOrderDetail(order_id);
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
            }
        });
        
        /* Not Used */        
            $(document).on("input", "#trans-item-discount", function (e) {
                var qty = $("#trans-item-qty").val();
                var price = $("#trans-item-price").val();
                var total = $("#trans-item-total").val();
                var discount = $(this).val();
                if ((discount.length == 0) || (discount == "")) {
                    discount = 0;
                    $("#trans-item-discount").val(0);
                    result = total;
                } else {
                    var result = parseFloat(qty) * parseFloat(removeCommas(price));
                    var result_after = parseFloat(result) - parseFloat(removeCommas(discount));
                    // console.log(result_after);
                    result = addCommas(result_after);
                }
                $("#trans-item-total-after-discount").val(result);
                // console.log(qty,removeCommas(price),removeCommas(total),removeCommas(discount),result);      
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
                var ref_id = $("#ref").find(":selected").val();
                $("#modal-trans-diskon").modal('toggle');
                $("#diskon").val(diskon);
                loadOrderItem({ref_id:ref_id,contact_id:0});
            });
            $(document).on("click", ".btn-load-product", function (e) {
                e.preventDefault();
                e.stopPropagation();
                console.log($(this));
                var id = $(this).attr('data-id');

                var prepare = {
                    id: id,
                    name: $("#input_name").val()
                };
                var prepare_data = JSON.stringify(prepare);
                // prepare_data = Base64.encode(prepare_data);
                var data = {
                    action: 'update',
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
                            /* hint zz_for or zz_each */
                        } else { //No Data
                            notif(0, d.message);
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, err);
                    }
                });
            });
        /* */
        
        /* Payment / Trans */
        /*
        $(document).on("click", "#btn-new-payment", function (e) {
            e.preventDefault();
            loadPaymentItem();
        });
        */
        $(document).on("click", ".btn-prepare-payment", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-id');
            var next = true;
            if (next == true) {
                var prepare = {
                    tipe: identity,
                    id: id,
                };
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'bill-add',
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
                            notif(1, d.message);
                            $("#modal-payment-list").modal('hide');
                            setTimeout(function () {
                                loadPaymentItem();
                                loadRoom({});                                
                            }, 500);
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
        $(document).on("click", ".btn-remove-payment", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            var order_id = $(this).attr('data-order-id');
            var ref = $(this).attr("data-ref");
            var number = $(this).attr("data-number");
            $.confirm({
                title: 'Konfirmasi!',
                content: 'Apakah anda ingin membatalkan <b>' + number + ' - ' + ref + '</b> dari daftar '+payment_alias+'?',
                buttons: {
                    confirm: {
                        btnClass: 'btn-danger',
                        text: 'Batalkan',
                        action: function () {
                            var data = {
                                action: 'bill-remove',
                                id: id,
                                ref: ref,
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
                                        loadPaymentItem();
                                        // $("#modal-payment-form").modal('hide');
                                    } else {
                                        notif(0, d.message);
                                    }
                                }
                            });
                        }
                    },
                    cancel: {
                        btnClass: 'btn-success',
                        text: 'Tidak Jadi',
                        action: function () {
                            // $.alert('Canceled!');
                        }
                    }
                }
            });
        });
        $(document).on("click", "#btn-save-payment", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var next = true;
            var payment_is_member       = parseInt($(".payment_contact_checkbox").attr('data-flag'));
            var payment_contact_id      = $("#payment_contact_id").find(':selected').val();
            var payment_contact_name    = $("#payment_contact_name").val();            

            if(payment_is_member == 1){ //Member
                if((payment_contact_id == 0) || (payment_contact_id < 1) || (payment_contact_id == undefined)){
                    next=false;
                    notif(0,contact_1_alias+' wajib dipilih');
                }
            }else{ //Non Member
                if(parseInt(payment_contact_name.length) < 3){
                    next=false;
                    notif(0,'Non '+contact_1_alias+' wajib tulis Nama');
                }
            }

            if(next){
                var metode_pembayaran = parseInt($("#modal_metode_pembayaran").find(':selected').val());
                var modal_akun_transfer = $("#modal_akun_transfer").find(':selected').val();
                var modal_nomor_ref_transfer = $("#modal_nomor_ref_transfer").val();
                var modal_nama_pengirim = $("#modal_nama_pengirim").val();

                var card_type = $("#modal_jenis_kartu").find(":selected").val(); //Visa,Mastercard,AE
                var card_bank = $("#modal_bank_penerbit").find(":selected").val();
                var card_year = $("#modal_valid_tahun").val();
                var card_month = $("#modal_valid_bulan").val();
                var card_number = $("#modal_nomor_kartu").val();
                var card_name = $("#modal_nama_pemilik").val();

                var total = $("#method-payment-total").val();
                var dibayar = $("#method-payment-received").val();
                var kembali = $("#method-payment-change").val();          

                var journal_item_ref = 0;

                if (metode_pembayaran == 0) {
                    notif(0, 'Metode Pembayaran wajib dipilih');
                    next = false;
                }

                if (metode_pembayaran > 0) {
                    var modal_metode_pembayaran = '';
                    if (metode_pembayaran == 2) { //Transfer Bank
                        var modal_akun_transfer = $("#modal_akun_transfer").find(':selected').val();
                        var modal_nomor_ref_transfer = $("#modal_nomor_ref_transfer").val();
                        var modal_nama_pengirim = $("#modal_nama_pengirim").val();


                        if (modal_akun_transfer == 0) {
                            notif(0, 'Transfer ke Bank wajib dipilih');
                            next = false;
                        }

                        if (next) {
                            if (modal_nomor_ref_transfer.length == 0) {
                                notif(0, 'Nomor Ref Transfer wajib diisi');
                                next = false;
                            }
                        }

                        if (next) {
                            if (modal_nama_pengirim.length == 0) {
                                notif(0, 'Nama Pengirim wajib diisi');
                                next = false;
                            }
                        }
                        modal_metode_pembayaran = 'Transfer Bank';
                    } else if (metode_pembayaran == 3) { //EDC
                        var modal_jenis_kartu = $("#modal_jenis_kartu").find(':selected').val();
                        var modal_valid_tahun = $("#modal_valid_tahun").val();
                        var modal_valid_bulan = $("#modal_valid_bulan").val();
                        var modal_nomor_kartu = $("#modal_nomor_kartu").val();
                        var modal_bank_penerbit = $("#modal_bank_penerbit").find(':selected').val();
                        var modal_nama_pemilik = $("#modal_nama_pemilik").val();

                        if (modal_jenis_kartu == 0) {
                            notif(0, 'Jenis kartu wajib dipilih');
                            next = false;
                        }

                        if (next) {
                            if (modal_valid_tahun.length == 0) {
                                notif(0, 'Tahun wajib diisi');
                                next = false;
                            }
                        }

                        if (next) {
                            if (modal_valid_bulan.length == 0) {
                                notif(0, 'Bulan wajib diisi');
                                next = false;
                            }
                        }

                        if (next) {
                            if (modal_nomor_kartu.length == 0) {
                                notif(0, 'Nomor kartu wajib diisi');
                                next = false;
                            }
                        }

                        if (next) {
                            if (modal_bank_penerbit == 0) {
                                notif(0, 'Bank penerbit wajib dipilih');
                                next = false;
                            }
                        }

                        if (next) {
                            if (modal_nama_pemilik == 0) {
                                notif(0, 'Nama Pemilik kartu wajib diisi');
                                next = false;
                            }
                        }
                        modal_metode_pembayaran = 'EDC Debit & Kredit';
                    } else if (metode_pembayaran == 4) {
                        modal_metode_pembayaran = 'Gratis';
                        next = true;
                    } else if (metode_pembayaran == 5) {
                        var modal_akun_qris = $("#modal_akun_qris").find(':selected').val();

                        if (modal_akun_qris == 0) {
                            notif(0, 'Akun penampung QRIS wajib dipilih');
                            next = false;
                        }
                        modal_metode_pembayaran = 'QRIS';
                        // next = true;
                    } else if (metode_pembayaran == 1) {
                        var modal_akun_cash = $("#modal_akun_cash").find(':selected').val();

                        if (modal_akun_cash == 0) {
                            notif(0, 'Setor ke wajib dipilih');
                            next = false;
                        }
                        modal_metode_pembayaran = 'Tunai';
                        // next = true;
                    } else if (metode_pembayaran == 8){ // Down Payment / Deposit
                        modal_metode_pembayaran = 'Potong '+dp_alias;
                        journal_item_ref = $("#payment_contact_id").find(':selected').attr('data-contact-session');
                    }
                }

                if (next == true) {
                    if (parseFloat(removeCommas(dibayar)) > parseFloat(removeCommas(total))) {
                        // console.log('dibayar > total');
                    } else if (parseFloat(removeCommas(dibayar)) == parseFloat(removeCommas(total))) {
                        // console.log('dibayar > total');
                    } else {
                        console.log('dibayar < total');
                        notif(0, 'Masukkan Jumlah yg sesuai');
                        // dibayar.focus();
                        next = false;
                    }
                    // console.log(dibayar+','+total);
                }
                console.log('DOM => Total: '+modal_total+', Dibayar: '+modal_total_dibayar+', Kembali: '+modal_total_kembali);
                console.log('NOT DOM => Total: '+removeCommas(total)+', Dibayar: '+removeCommas(dibayar)+', Kembali: '+removeCommas(kembali)); 
                if(next){
                    if(metode_pembayaran == 4){
                        if(parseFloat(removeCommas(total)) == 0){
                            total = $("#method-payment-total-before").val();
                            next=true;
                            console.log('Disk 100%');
                        }else{
                            console.log('Disk BIasa');
                        }
                    }else{
                        if(modal_total_dibayar < modal_total){
                            notif(0,'Harap memasukkan Jumlah yg sesuai');
                            next =false;
                        }
                    }
                }

                if (next == true) {
                    $.confirm({
                        title: 'Konfirmasi Pembayaran',
                        content: 'Lanjutkan proses ke pembayaran secara <b>'+modal_metode_pembayaran+'</b> dengan jumlah yg dimasukkan adalah <b>'+dibayar+'</b>',
                        columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                        autoClose: 'button_2|20000',
                        closeIcon: true,
                        closeIconClass: 'fas fa-times',
                        buttons: {
                            button_1: {
                                text: 'Bayar',
                                btnClass: 'btn-primary',
                                keys: ['enter'],
                                action: function () {
                                    // var discount = $("#method-payment-discount");
                                    var received = $("#method-payment-received");
                                    var change = $("#method-payment-change");
                                    var order_list_id = $("#order_list_id");
                                    var prepare = {
                                        tipe: identity,
                                        payment_contact_id: payment_contact_id,
                                        payment_contact_checkbox: $(".payment_contact_checbox").attr('data-flag'),
                                        payment_contact_name: $("#payment_contact_name").val(),
                                        payment_contact_phone: $("#payment_contact_phone").val(),
                                        method: metode_pembayaran,
                                        method_name:modal_metode_pembayaran,
                                        modal_akun_cash:$("#modal_akun_cash").find(":selected").val(),
                                        modal_akun_transfer:$("#modal_akun_transfer").find(":selected").val(),
                                        modal_akun_edc:$("#modal_akun_edc").find(":selected").val(),
                                        modal_akun_gratis:$("#modal_akun_gratis").find(":selected").val(),
                                        modal_akun_qris:$("#modal_akun_qris").find(":selected").val(),                                                                                                            
                                        transfer_nama_pengirim:modal_nama_pengirim,
                                        transfer_nomor_ref_transfer:modal_nomor_ref_transfer,
                                        card_type: card_type,
                                        // digital_type: digital_type.find(':selected').val(),                                    
                                        card_bank: card_bank,
                                        card_year: card_year,
                                        card_month: card_month,
                                        card_name: card_name,
                                        card_number: card_number,
                                        // note: note.val(),
                                        total: total, //477,000
                                        total_before: $("#method-payment-total-before").val(), //530,000
                                        voucher_id: $("#modal-payment-voucher-nominal").attr('data-id'),
                                        // total_before_fee: $("#method-payment-total").attr('data-total'), //0
                                        // discount: discount,
                                        received: dibayar,
                                        change: kembali,
                                        order_list_id: order_list_id.val(),
                                        journal_item_ref:journal_item_ref
                                    };
                                    var data = {
                                        action: 'bill-create',
                                        data: JSON.stringify(prepare)
                                    };
                                    $.ajax({
                                        type: "post",
                                        url: url,
                                        data: data,
                                        dataType: 'json',
                                        cache: 'false',
                                        beforeSend: function(){
                                            notif(1,'Silahkan tunggu, Proses pembayaran berlangsung');
                                            $("#btn-save-payment").attr('disabled',true);
                                            $("#btn-save-payment").html('<span class="fas fa-spinner fa-spin"></span> Loading');
                                        },
                                        success: function (d) {
                                            if (parseInt(d.status) === 1) { //Success
                                                
                                                $("#btn-save-payment").attr('disabled',false);
                                                $("#btn-save-payment").html('<span class="fas fa-cash-register white"></span> Bayar');
                                                                                        
                                                $("#modal-trans-save").modal('hide');
                                                $("#method-payment-total").val('');
                                                $("#method-payment-received").val('');
                                                $("#method-payment-change").val('');
                                                // $("#modal-trans-save").toggle();

                                                // //Prepare Print
                                                // $(".btn-print-payment").attr('data-id', d.result.trans_id);
                                                // $(".btn-print-payment").attr('data-number', d.result.trans_number);
                                                // $(".btn-print-payment").attr('data-session', d.result.trans_session);

                                                // //Set Text
                                                // $(".modal-print-trans-number").html(':' + d.result.trans_number);
                                                // $(".modal-print-trans-date").html(':' + d.result.trans_date);                                            
                                                // $(".modal-print-trans-paid-type-name").html(':' + d.result.trans_paid_type_name);
                                                // $(".modal-print-trans-total").html(':' + d.result.trans_total);
                                                // $(".modal-print-trans-total-paid").html(':' + d.result.trans_total_received);
                                                // $(".modal-print-trans-total-change").html(':' + d.result.trans_total_change);
                                                
                                                // $("#modal-print-contact-name").val(':' + d.result.contact_name);
                                                // $("#modal-print-contact-phone").val(':' + d.result.contact_phone);
                                                
                                                // $(".btn-send-whatsapp").attr('data-id',d.result.trans_id).attr('data-number',d.result.trans_number).attr('data-date',d.result.trans_date).attr('data-total',d.result.trans_total).attr('data-contact-id',0).attr('data-contact-name',d.result.contact_name).attr('data-contact-phone',d.result.contact_phone);

                                                setTimeout(function () {
                                                    // $("#modal-payment-print").modal({backdrop: 'static', keyboard: false});
                                                    var params = {
                                                        trans_id: d.result.trans_id,
                                                        trans_session: d.result.trans_session,
                                                        trans_number: d.result.trans_number, 
                                                        trans_date: d.result.trans_date,
                                                        paid_type_name: d.result.trans_paid_type_name,
                                                        trans_total: d.result.trans_total,
                                                        trans_total_paid: d.result.trans_total_received,
                                                        trans_total_change: d.result.trans_total_change,
                                                        contact_id: d.result.contact_id,
                                                        contact_name: d.result.contact_name,
                                                        contact_phone: d.result.contact_phone,                                                                                                          
                                                    }
                                                    loadPaymentSuccess(params);
                                                }, 1000);

                                                notif(1, d.message);
                                                loadRoom({});
                                                loadPaymentItem();
                                                order_table.ajax.reload(null,false);
                                                trans_table.ajax.reload(null,false);
                                                $("#payment_contact_id").val(0).trigger('change');
                                            } else { //No Data
                                                notif(0, d.message);
                                                $("#btn-save-payment").attr('disabled',false);
                                                $("#btn-save-payment").html('<span class="fas fa-cash-register white"></span> Bayar');
                                            }
                                        },
                                        error: function (xhr, Status, err) {
                                            notif(0,err);
                                            $("#btn-save-payment").attr('disabled',false);
                                            $("#btn-save-payment").html('<span class="fas fa-cash-register white"></span> Bayar');                                        
                                        }
                                    });
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
            }
        });
        $(document).on("click", ".payment_contact_checkbox", function (e) {
            var check = $(".payment_contact_checkbox").attr('data-flag');
            if (check == 0) {
                checkBoxPaymentNonmember(0);
            } else {
                checkBoxPaymentNonmember(1);
            }
        });

        $(document).on("change", "#modal_metode_pembayaran", function (e) { //Cash, Transfer, EDC, QRIS
            e.preventDefault();
            e.stopPropagation();

            //Reset DOM Total
            modal_down_payment  = 0;
            modal_total_dibayar = 0;

            var id = $(this).find(':selected').val();
            $("#modal_metode_pembayaran_cash").hide(300);
            $("#modal_metode_pembayaran_transfer").hide(300);
            $("#modal_metode_pembayaran_edc").hide(300);
            $("#modal_metode_pembayaran_gratis").hide(300);
            $("#modal_metode_pembayaran_qris").hide(300);
            $("#modal_metode_pembayaran_down_payment").hide(300);
            
            loadDownPayment({action:0}); //Reset Form Down Payment

            if (parseInt(id) == 1) {
                $("#modal_metode_pembayaran_cash").show(300);
                $("#modal_akun_cash").removeAttr('disabled').attr('readonly', false);
            } else if (parseInt(id) == 2) {
                $("#modal_metode_pembayaran_transfer").show(300);
                $("#modal_akun_transfer").removeAttr('disabled').attr('readonly', false);
                $("#modal_nomor_ref_transfer").attr('readonly', false);
                $("#modal_nama_pengirim").attr('readonly', false);
            } else if (parseInt(id) == 3) {
                $("#modal_metode_pembayaran_edc").show(300);
                $("#modal_akun_edc").removeAttr('disabled').attr('readonly', false);                
                $("#modal_jenis_kartu").removeAttr('disabled').attr('readonly', false);
                $("#modal_valid_tahun").attr('readonly', false);
                $("#modal_valid_bulan").attr('readonly', false);
                $("#modal_nomor_kartu").attr('readonly', false);
                $("#modal_kode_transaksi").attr('readonly', false);
                $("#modal_bank_penerbit").removeAttr('disabled').attr('readonly', false);
                $("#modal_nama_pemilik").attr('readonly', false);
            }else if(parseInt(id) == 4){
                $("#modal_metode_pembayaran_gratis").show(300);
                $("#modal_akun_gratis").removeAttr('disabled').attr('readonly', false);                
            }else if(parseInt(id) == 5){
                $("#modal_metode_pembayaran_qris").show(300);
                $("#modal_akun_qris").removeAttr('disabled').attr('readonly', false);                
            }else if(parseInt(id) == 8){
                $("#modal_metode_pembayaran_down_payment").show(300);
                $("#modal_akun_down_payment").removeAttr('disabled').attr('readonly', false);
                var cid = $("#payment_contact_id").find(':selected').val();
                var cna = $("#payment_contact_id").find(":selected").text();
                var cns = $("#payment_contact_id").find(":selected").attr('contact-session');                
                var prepare_contact = {
                    action:1,
                    contact_id: parseInt(cid),
                    contact_name: cna,
                    contact_session: cns
                };
                loadDownPayment(prepare_contact);
            }

            //Reset Modal Down Payment
            modal_down_payment = 0;
        });
        $(document).on("input", "#method-payment-received", function (e) { //Masukkan Jumlah (Rp) Auto Input Kembalian
            e.preventDefault(); e.stopPropagation();
            modal_total = modal_total;
            modal_total_dibayar = removeCommas($(this).val());
            modal_total_kembali = parseFloat(modal_total_dibayar) - parseFloat(modal_total);
            $("#method-payment-change").val(modal_total_kembali);
        });

        //Room
        $(document).on("click",".btn-room-click",function(e) {
            e.preventDefault();
            e.stopPropagation();
            $("#search-produk-tab-detail").val('');
            var data_id = $(this).attr('data-id');
            var data_is_use = $(this).attr('data-use-type');   
            if(data_is_use == 1){
                var order_id        = $(this).attr('data-order-id');
                var order_number    = $(this).attr('data-order-number');
                var ref_id          = $(this).attr('data-ref-id');
                var ref_name        = $(this).attr('data-ref-name');
                var grand_total     = $(this).attr('data-order-total');
            }else{
                var ref_name        = $(this).attr('data-ref-name');
            }

            if(parseInt(data_id) > 0){
                if(parseInt(data_is_use) > 0){ //Room CheckIn
                    notif(1,'Memuat '+ref_name);
                    var params = {
                        order_id: order_id,
                        order_number: order_number,
                        ref_id:ref_id,
                        ref_name:ref_name,
                        grand_total:grand_total
                    };
                    // loadBookingNavigation(params);
                    loadOrderDetail(params['order_id']);
                }else{ //Room Available
                    notif(1,'CheckIn '+ref_name);
                    loadOrderItem({ref_id:data_id,contact_id:0});
                    formOrder({action:1,ref_name:ref_name}); //Display Model Booking
                }
            }
        });

        //Voucher
        $(document).on("keyup","#modal-payment-voucher",function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).val($(this).val().toUpperCase());
        });        
        $(document).on("click","#btn-voucher-search",function(e) {
            e.preventDefault();
            e.stopPropagation();
            var term = $("#modal-payment-voucher").val();
            var button_action = $(this).attr('data-id');
            if(button_action == 1){
                if(term.length > 2){
                    let form = new FormData();
                    form.append('action', 'redeem_search');
                    form.append('voucher_code', term);
                    $.ajax({
                        type: "post",
                        url: url_voucher,
                        data: form, 
                        dataType: 'json', cache: 'false', 
                        contentType: false, processData: false,
                        beforeSend:function(){
                            $("#btn-voucher-search").html('<span class="fas fa-spinner fa-spin"></span> Loading');
                        },
                        success:function(d){
                            let s = d.status;
                            let m = d.message;
                            let r = d.result;
                            if(parseInt(s) == 1){ //Voucher Available / Ready
                                notif(s,m);
                                voucherProcess(1,r);
                            }else if(parseInt(s) == 4){ //Voucher Expired
                                var params = {
                                    voucher_status:r.voucher_status,
                                    voucher_type_name:r.voucher_type_name,
                                    voucher_code:r.voucher_code,
                                    voucher_period:r.voucher_period
                                };
                                voucherProcess(4,params);
                            }else{ //Voucher Not Found
                                notif(0,m);
                                voucherReset(0);
                            }
                        },
                        error:function(xhr,status,err){
                            notif(0,err);
                            voucherReset(1);
                        }
                    });
                }else{
                    notif(0,'Klaim Voucher harus diisi');
                    $("#modal_payment_voucher").focus();
                }
            }else if(button_action ==4){
                voucherReset(4);
            }
        });

        $(document).on("click", "#btn-preview", function (e) {
            order_table.ajax.reload();
        });
        $(document).on("click", ".btn-product-tab-click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var category_id = $(this).attr('data-id');
            var search = '';
            loadProductTabDetail({category_id:category_id,search:search});
        });
        $(document).on("input", "#search-produk-tab-detail", function(e){
            var category_id = 0;
            var search = $(this).val();
            var ln = $(this).val().length;
            if (parseInt(ln) > 2) {
                loadProductTabDetail({category_id:category_id,search:search});                
            }
        });
        $(document).on("click", ".btn-label", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-order-id');
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
                            // notif(s,m);
                            // notifSuccess(m);
                            /* hint zz_for or zz_each */
                        }
                        self.setTitle(m);
                    }).fail(function () {
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                onContentReady: function () {
                    var self = this;
                    var content = '';
                    var dsp = '';

                    let r = self.ajaxResponse.data;
                    dsp += '<form id="jc_form">';
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                    dsp += '    <div class="form-group">';
                    // dsp += '    <label class="form-label">Label</label>';
                    dsp += '        <select id="jc_select" name="jc_select" class="form-control">';
                    dsp += '            <option value="Label">Label</option>';
                    if (parseInt(r['status']) == 1) {
                        for (var ss = 0; ss < r['result'].length; ss++) {
                            dsp += '          <option value="' + r['result'][ss]['ref_name'] + '">' + r['result'][ss]['ref_name'] + '</option>';
                        }
                    }
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
                                form.append('order_id', id);
                                form.append('order_label', select);
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
                                            order_table.ajax.reload(null, false);
                                        } else {
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
        $(document).on("click", ".btn-send-whatsapp", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // console.log($(this));
            var trans_id = $(this).attr('data-id');

            // var number = $("#message_contact_number").val();
            // var name = $("#message_contact_name").val();
            // var teks = $("#message_text").val();

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
        $(document).on("click", "#btn-save-contact", function (e) {
            e.preventDefault();
            var next = true;

            var kode = $("#form-master input[name='kode_contact']");
            var nama = $("#form-master input[name='nama_contact']");

            if (next == true) {
                // if ($("input[id='kode_contact']").val().length == 0) {
                //     notif(0, 'Kode wajib diisi');
                //     $("#kode_contact").focus();
                //     next = false;
                // }
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
                    notif(0, 'Telepon wajib diisi');
                    $("#telepon_1_contact").focus();
                    next = false;
                }
            }

            if (next == true) {
                /*
                if ($("textarea[id='alamat_contact']").val().length == 0) {
                    notif(0, 'Alamat wajib diisi');
                    $("#alamat_contact").focus();
                    next = false;
                }
                */
            }

            if (next == true) {
                var prepare = {
                    tipe: 2,
                    kode: $("input[id='kode_contact']").val(),
                    nama: $("input[id='nama_contact']").val(),
                    perusahaan: $("input[id='perusahaan_contact']").val(),
                    telepon_1: $("input[id='telepon_1_contact']").val(),
                    email_1: $("input[id='email_1_contact']").val(),
                    alamat: $("textarea[id='alamat_contact']").val(),
                    status: 1
                }
                var data = {
                    action: 'create-from-modal',
                    data: JSON.stringify(prepare)
                };
                $.ajax({
                    type: "POST",
                    url: url_contact,
                    data: data,
                    dataType: 'json',
                    cache: false,
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) == 1) { /* Success Message */
                            notif(1, d.message);
                            formKontakNew();
                            $("#modal-contact").modal('toggle');
                            $("#order_contact_id").val(0).trigger('change');
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

        /* Print */
        $(document).on("click", ".btn-print", function (e) { // Print Button
            e.preventDefault();
            var order_id = $(this).attr("data-id");
            var order_session = $(this).attr("data-session");
            var x = screen.width / 2 - 700 / 2;
            var y = screen.height / 2 - 450 / 2;
            var print_url = url_print + '/' + order_id;
            // var print_url = url_print_payment + '/' + tsession;
            // var win = window.open(print_url, 'Print Payment', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
            var set_print_url = url_print + '_orders/' + order_id;
            if(parseInt(order_id) > 0){
                $.ajax({
                    type: "get",
                    url: set_print_url,
                    data: {action: 'print_raw'},
                    dataType: 'json',cache: 'false',
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
            }else{
                notif(0,'Data tidak di temukan');
            }        
        });
        $(document).on("click", ".btn-print-payment", function () {
            var trans_id = $(this).attr("data-id");
            var trans_session = $(this).attr("data-session");
            var x = screen.width / 2 - 700 / 2;
            var y = screen.height / 2 - 450 / 2;
            var print_url = url_print + '/' + trans_id;

            // var print_url = url_print_payment + '/' + tsession;
            // var win = window.open(print_url, 'Print Payment', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
            if(parseInt(trans_id) > 0){
                var set_print_url = url_print + '_transaction/' + trans_id;
                $.ajax({
                    type: "get",
                    url: set_print_url,
                    data: {action: 'print_raw'},
                    dataType: 'json',cache: 'false',
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
            }else{
                notif(0,'Data tidak ditemukan / belum dibayar');
            }          
        });                
        $(document).on("click",".btn-print-all",function() {
            var id = $(this).attr("data-id"); 
            var action = $(this).attr('data-action'); //1,2
            var request = $(this).attr('data-request'); //report_purchase_buy_recap
            var format = $(this).attr('data-format'); //html, xls
            var contact = $("#filter_kontak").find(':selected').val();
            var product = $("#filter_produk").find(':selected').val();

            var order = $("#filter_order").find(':selected').val();
            if(order == 0){
                order = 'order_date';
            }else if(order == 1){
                order = 'order_number';
            }else if(order == 3){
                order = 'order_total';
            }else{
                order = 'order_date';
            }

            // var dir = $("#filter_dir").find(':selected').val();    
            var dir = 'asc';
            product = 0;
            //alert('#btn-print-all on Click'+action+','+request);
            // var x = screen.width / 2 - 700 / 2;
            // var y = screen.height / 2 - 450 / 2;
            // var print_url = url_print +'/'+ action + '/' +request+ '/' +contact+ '/' + $("#start").val() + '/' + $("#end").val();
            var print_url = url_report +'/' 
            + request + '/'
            + $("#start").val() + '/'
            + $("#end").val() + '/' 
            + contact + "?product="+product+"&format="+format+"&order="+order+"&dir="+dir;    
            window.open(print_url,'_blank');
            // var request = $('.btn-print-all').data('request');
            // var print_url = url_print +'/'+ request + '/'+ $("#start").val() +'/'+ $("#end").val();
            // var win = window.open(print_url,'Print','width=700,height=485,left=' + x + ',top=' + y + '').print();   
        });
        $(document).on("click",".btn-print-all-trans",function() {
            var id = $(this).attr("data-id"); 
            var action = $(this).attr('data-action'); //1,2
            var request = $(this).attr('data-request'); //report_purchase_buy_recap
            var format = $(this).attr('data-format'); //html, xls
            var contact = $("#filter_kontak_2").find(':selected').val();
            var product = $("#filter_produk_2").find(':selected').val();
            var type_paid = $("#filter_type_paid_2").find(':selected').val();

            var order = $("#filter_order_2").find(':selected').val();
            if(order == 0){
                order = 'trans_date';
            }else if(order == 1){
                order = 'trans_number';
            }else if(order == 3){
                order = 'trans_total';
            }else{
                order = 'trans_date';
            }

            // var dir = $("#filter_dir").find(':selected').val();    
            var dir = 'asc';
            product = 0;
            //alert('#btn-print-all on Click'+action+','+request);
            // var x = screen.width / 2 - 700 / 2;
            // var y = screen.height / 2 - 450 / 2;
            // var print_url = url_print +'/'+ action + '/' +request+ '/' +contact+ '/' + $("#start").val() + '/' + $("#end").val();
            var print_url = url_report +'/' 
            + request + '/'
            + $("#start").val() + '/'
            + $("#end").val() + '/' 
            + contact + "?product="+product+"&format="+format+"&order="+order+"&dir="+dir+"&type_paid="+type_paid;    
            window.open(print_url,'_blank');
            // var request = $('.btn-print-all').data('request');
            // var print_url = url_print +'/'+ request + '/'+ $("#start").val() +'/'+ $("#end").val();
            // var win = window.open(print_url,'Print','width=700,height=485,left=' + x + ',top=' + y + '').print();   
        });

        //Voucher
        function voucherProcess(voucher_status,params){
            console.log('voucherProcess()');
            console.log(params);
            var r = params;
            if(voucher_status==1){
                var voucher_id = 0;
                var voucher_price = 0;
                var voucher_minimum = 0;
                var voucher_discount = 0;
                var voucher_value = 0;

                if(parseInt(r.voucher_id) > 0){
                    voucher_id = r.voucher_id;
                    voucher_type = r.voucher_type;
                }

                let title   = '<span class="fas fa-check"></span> Voucher / Promo '+r.voucher_status;
                let content = '<b>'+r.voucher_type_name+'</b> dengan kode <b>'+r.voucher_code+'</b> tersedia dan dapat digunakan';
                content += '<br>Voucher ini bernilai<br>';

                if(voucher_type==1){
                    voucher_price = r.voucher_price;
                    voucher_value = voucher_price;
                    content += '<br>Potongan Transaksi sebesar <b>'+r.voucher_price_format+'</b>';
                    if(parseInt(r.voucher_minimum_transaction) > 0){
                        voucher_minimum = r.voucher_minimum_transaction;
                        content += '<br>Syarat Transaksi minimum <b>'+r.voucher_minimum_transaction_format+'</b>';
                    }
                }else if(voucher_type==2){
                    voucher_discount = r.voucher_discount_percentage;
                    voucher_value = voucher_discount;
                    content += '<br>Diskon sebesar <b>'+r.voucher_discount_percentage_format+'%</b>';
                }
                
                $.confirm({
                    title: title,
                    content: content,
                    columnClass: 'col-md-5 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                    closeIcon: true, closeIconClass: 'fas fa-times',
                    animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                    buttons: {
                        button_1: {
                            text: '<span class="fas fa-check"></span> Gunakan Voucher Ini',
                            btnClass: 'btn-dark',
                            keys: ['Escape'],
                            action: function(){
                                // var voucher_nominal = $("#modal-payment-voucher-nominal").val();
                                // voucher_value diambil dari voucher_price
                                var next = true;

                                //Set Voucher ID 
                                $("#modal-payment-voucher-nominal").attr('data-id',voucher_id);
                                
                                //Perhitungan
                                var grand_total = parseFloat(removeCommas($("#method-payment-total-before").val()));
                                var total = 0;

                                if(voucher_type ==1){ //Voucher
                                    $("#modal-payment-voucher-nominal").val(addCommas(voucher_value));
                                    total = grand_total - voucher_price;    
                                    if(parseInt(r.voucher_minimum_transaction) > 0){
                                        if(grand_total < voucher_minimum){
                                            lack_total = voucher_minimum - grand_total;

                                            let title   = '<span class="fas fa-times"></span> Voucher Gagal';
                                            let content = 'Transaksi belum memenuhi syarat untuk menggunakan voucher ini.<br><br>';
                                                content += 'Grand Total Saat Ini : <b>'+addCommas(grand_total)+'</b><br>'; 
                                                content += 'Minimum Transaksi : <b>'+addCommas(voucher_minimum)+'</b><br>';
                                                content += 'Transaksi Kurang : <b>'+addCommas(lack_total)+'</b>';
                                            $.confirm({
                                                title: title, content:content, columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
                                                // autoClose: 'button_1|20000',
                                                closeIcon: true, closeIconClass: 'fas fa-times',
                                                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                                                buttons: {
                                                    button_1: {
                                                        text: 'Tutup',
                                                        btnClass: 'btn-danger',
                                                        keys: ['Escape'],
                                                        action: function(){
                                                        }
                                                    }
                                                }
                                            });

                                            next=false;
                                        }
                                    }
                                }else if(voucher_type ==2){ //Promo
                                    $("#modal-payment-voucher-nominal").val(voucher_value+'%');
                                    if(voucher_value == 100){
                                        total_percent = grand_total;
                                        total = grand_total - total_percent;
                                    }else{
                                        // total_percent = grand_total / voucher_value; //195000 / 50
                                        total_percent = grand_total * (voucher_value / 100); //195000 / 50                                        
                                        total = grand_total - total_percent;                                        
                                    }
                                    console.log(total_percent+', '+total);
                                    $("#modal-payment-voucher-nominal").val(addCommas(total_percent));                                    
                                }             

                                if(next){    
                                    notif(1,'Total Tagihan berhasil dirubah dari '+addCommas(grand_total)+' menjadi '+addCommas(total));
                                    $("#btn-voucher-search").html('<span class="fas fa-trash"></span> Hapus Voucher');   
                                    $("#btn-voucher-search").attr('data-id',4);                                                                         
                                    $("#method-payment-total").val(total);
                                    modal_total = total;
                                }else{
                                    voucherReset(1);
                                }
                            }
                        },
                        button_2: {
                            text: '<span class="fas fa-times"></span> Batal',
                            btnClass: 'btn-danger',
                            keys: ['Escape'],
                            action: function(){
                                voucherReset(1);
                            }
                        }                                
                    }
                });                
            }else if (voucher_status==4){
                var r = params;
                let title   = 'Voucher / Promo '+r.voucher_status;
                let content = '<b>'+r.voucher_type_name+'</b> dengan kode <b>'+r.voucher_code+'</b> telah <b>'+r.voucher_status+'</b> hanya berlaku di periode <b>'+r.voucher_period+'</b>';
                $.confirm({
                    title: title,
                    content: content,
                    columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
                    autoClose: 'button_1|30000',
                    closeIcon: true, closeIconClass: 'fas fa-times',
                    animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                    buttons: {
                        button_1: {
                            text: '<span class="fas fa-times"></span> Tutup',
                            btnClass: 'btn-danger',
                            keys: ['Escape'],
                            action: function(){
                                $("#btn-voucher-search").html('<span class="fas fa-check-double"></span> Gunakan');   
                            }
                        }
                    }
                });
            }
        }
        function voucherReset(action){
            console.log('voucherReset('+action+')');
            if(action == 0){
                // $("#modal-payment-voucher").val('');
                $("#modal-payment-voucher-nominal").val('');
                $("#modal-payment-voucher-nominal").attr('data-id',0);     
                $("#btn-voucher-search").attr('data-id',1);        
                $("#btn-voucher-search").html('<span class="fas fa-check-double"></span> Gunakan');
            }else if(action == 1){
                $("#modal-payment-voucher").val('');
                $("#modal-payment-voucher-nominal").val('');
                $("#modal-payment-voucher-nominal").attr('data-id',0);            
                $("#btn-voucher-search").attr('data-id',1);     
                $("#btn-voucher-search").html('<span class="fas fa-check-double"></span> Gunakan');
            }else if(action == 4){            
                var grand_total = $("#method-payment-total-before").val();
                $("#modal-payment-voucher").val('');
                $("#modal-payment-voucher-nominal").val('');
                $("#modal-payment-voucher-nominal").attr('data-id',0);    
                $("#btn-voucher-search").attr('data-id',1);                
                $("#btn-voucher-search").html('<span class="fas fa-check-double"></span> Gunakan');
                $("#method-payment-total").val(addCommas(grand_total));
                modal_total = removeCommas(grand_total);
                notif(1,'Total Tagihan berhasil dikembalikan '+addCommas(grand_total));
            }
        }

        function loadProductTab() { /* Not Used, Replace by Controller , load-product-tab */
            // var product_tab = $("#product-tab");
            var prepare = {
                tipe: identity,
                ref_type: 8,
                ref_flag: 1
            };
            var data = {
                action: 'pos-load-product-tab',
                data: JSON.stringify(prepare)
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
                        if (parseInt(d.total_records) > 0) {
                            $("#product-tab ul").html('');
                            var dsp = '';
                            var total_records = parseInt(d.total_records);
                            dsp += '<li class="active"><a href="#" class="btn-product-tab-click" data-id="-1" role="" data-toggle="tab" style="padding: 8px!important;">PROMO</a></li>';
                            dsp += '<li><a href="#" class="btn-product-tab-click" data-id="0" role="" data-toggle="tab" style="padding: 8px!important;">Semua</a></li>';
                            for (var a = 0; a < total_records; a++) {
                                dsp += '<li><a href="#" class="btn-product-tab-click" data-id="' + d.result[a]['category_id'] + '" role="" data-toggle="tab" style="padding: 8px!important;">' + d.result[a]['category_name'] + '</a></li>';
                            }
                            $("#product-tab ul").html(dsp);
                        }else{
                            notif(0,d.message);
                        }
                    } else {
                        notif(0, d.message);
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0, err);
                }
            });
        }
        function loadProductTabDetail(params){
            var prepare = {
                tipe: identity,
                category_id: params['category_id'],
                search: params['search']
            };
            var prepare_data = JSON.stringify(prepare);
            var data = {
                action: 'pos-load-product-tab-detail',
                data: prepare_data
            };
            $.ajax({
                type: "post",
                url: url,
                data: data,
                dataType: 'json',
                cache: 'false',
                beforeSend: function () {
                    $("#product-tab-detail").html('<span class="fas fa-spinner fa-spin"></span> Loading...');
                },
                success: function (d) {
                    // console.log('To:'+d.status);
                    if (parseInt(d.status) == 1) { //Success
                        if (parseInt(d.total_records) > 0) {
                            $("#product-tab-detail").html('');
                            // notif(1,d.total_records);

                            var dsp = '';
                            var total_records = parseInt(d.total_records);
                            for (var a = 0; a < total_records; a++) {

                                // dsp += '<tr>';
                                //   dsp += '<td>'+d.result[a]['COL_1']+'</td>';
                                //   dsp += '<td>'+d.result[a]['COL_2']+'</td>';
                                //   dsp += '<td>'+d.result[a]['COL_3']+'</td>';
                                //   dsp += '<td>';
                                //     dsp += '<button class="btn-action btn btn-primary" data-id="'+d.result[a]['COL_1']+'">';
                                //     dsp += d.result[a]['COL_1'];
                                //     dsp += '</button>';
                                //   dsp += '</td>';
                                // dsp += '</tr>';
                                if (d.result[a]['product_image'] != undefined) {
                                    product_images = base_url + d.result[a]['product_image'];
                                }else{
                                    product_images = product_image;
                                }

                                var set_color = 'template';
                                var set_price = '';
                                var price = d.result[a]['product_price_sell'];
                                if (parseFloat(d.result[a]['product_price_promo']) > 1) {
                                    set_color = 'blue';
                                    set_price = '<span style="text-decoration:line-through;">Rp. ' + addCommas(d.result[a]['product_price_sell_format']) + '</span> - Rp. ' + addCommas(d.result[a]['product_price_promo_format']);
                                    price = d.result[a]['product_price_promo'];
                                }else{
                                    if(parseFloat(d.result[a]['product_price_sell']) > 0){
                                        set_price = 'Rp. ' + addCommas(d.result[a]['product_price_sell_format']);
                                    }else{
                                        set_price = 'Rp. 0'; 
                                    }
                                }

                                dsp += '<div class="col-md-4 col-xs-6 col-sm-6 btn-save-order-item product-tab-detail-item"';
                                        dsp += 'data-id="' + d.result[a]['product_id'] + '"';
                                        dsp += 'data-qty="1"';
                                        dsp += 'data-satuan="' + d.result[a]['product_unit'] + '"';
                                        dsp += 'data-price="' + price + '">';
                                    dsp += '<div class="col-md-12 col-sm-12 product-tab-color-' + set_color + '" style="">';
                                        dsp += '<img src="' + product_images + '" class="img-responsive" style="margin-top:20px;">';
                                    dsp += '</div>';
                                    dsp += '<div class="col-md-12 col-sm-12 product-tab-color-' + set_color + '">';
                                        dsp += '<p class="product-name" style="">' + d.result[a]['product_name'] + '</p>';
                                        // dsp += '<p class="product-price" style="">' + d.result[a]['category_name'] + '</p>';
                                        dsp += '<p class="product-price" style="">' + set_price + '</p>';
                                    dsp += '</div>';
                                dsp += '</div>';
                            }
                            $("#product-tab-detail").html(dsp);
                        }else{
                            $("#product-tab-detail").html(d.message);
                        }
                    } else { //No Data
                        notif(0, d.message);
                        $("#product-tab-detail").html(d.message);
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0, err);
                }
            });            
        }
        function loadRoom(params) { /* load-reference */
            console.log('Search : ? Tidak ada respon');
            if(params['search']){
                var searchw = params['search'];
            }else{
                var searchw = '';
            }

            var prepare = {
                ref_type: 7,
                search: searchw
            };
            var data = {
                action: 'pos-load-ref',
                data: JSON.stringify(prepare)
            };
            $.ajax({
                type: "post",
                url: url,
                data: data,
                dataType: 'json',
                cache: 'false',
                beforeSend: function(){
                    $("#room-tab-detail").html('<span class="fas fa-spinner fa-spin"></span> Loading...');
                },
                success: function (d){
                    if (parseInt(d.status) == 1) {
                        if (parseInt(d.total_records) > 0) {
                            $("#room-tab-detail").html('');
                            var dsp = '';
                            var total_records = parseInt(d.total_records);
                            for (var b = 0; b < total_records; b++) {
                                // var order_id = d.result[b]['order_id'];
                                var ref_id = d.result[b]['ref_id'];
                                var ref_name = d.result[b]['ref_name'];
                                var ref_note = d.result[b]['ref_icon'];
                                var ref_use = parseInt(d.result[b]['ref_use_type']);                                
                                // var order_number = d.result[b]['order_number'];
                                // var order_total = d.result[b]['order_grand_total'];
                                // var order_date_format = d.result[b]['order_date_format'];
                                // var order_down_payment = d.result[b]['order_with_dp'];

                                // var image = '<?php #echo site_url('upload/product/product2.png'); ?>';
                                // var set_ref = "fas fa-clipboard";
                                // var set_color = "#ecf0f2";
                                // // if(ref_name=='Take Away'){
                                // var set_ref = "fas fa-key";
                                // // set_color = "#d1dade";
                                // // }
                                var background_color='';
                                var ref_status = '';
                                if(ref_use == 0){
                                    background_color = 'background-color: #b7e7a1;';
                                    ref_status = 'Available';
                                }else if(ref_use == 1){
                                    background_color = 'background-color: #f193a6;';
                                    ref_status = 'Check-In';
                                }else if(ref_use == 2){
                                    background_color = 'background-color: #99c4ed;';
                                    ref_status = 'Booking';                                    
                                }else if(ref_use == 4){
                                    background_color = 'background-color: #f1b8b8;';
                                    ref_status = 'Maintenance';                                    
                                }
                                var set_attr = '';
                                set_attr = 'data-id="' + ref_id + '" style="'+background_color+'" data-flag="'+d.result[b]['ref_flag']+'" data-use-type="'+d.result[b]['ref_use_type']+'"';
                                if(parseInt(ref_use) == 1){
                                    set_attr += ' data-order-id="'+d.result[b]['order']['order_id']+'"';
                                    set_attr += ' data-order-number="'+d.result[b]['order']['order_number']+'"';
                                    set_attr += ' data-ref-id="'+d.result[b]['order']['ref_id']+'"';
                                    set_attr += ' data-ref-name="'+d.result[b]['order']['ref_name']+'"';
                                    set_attr += ' data-order-total="'+d.result[b]['order']['order_total']+'"';
                                }else{
                                    set_attr += ' data-ref-id="'+d.result[b]['ref_id']+'"';
                                    set_attr += ' data-ref-name="'+d.result[b]['ref_name']+'"';
                                }

                                dsp += '<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 btn-room-click room-tab-detail-item"';
                                dsp += set_attr+">"; /*background-color:' + set_color + '; */
                                    dsp += '<div class="col-md-12 col-sm-12" style="padding:12px 0px;cursor:pointer;border:1px solid white;">';
                                        dsp += '<div class="col-md-12 col-sm-12" style="text-align:center;">';
                                            dsp += '<span class="' + ref_note + ' fa-2x"></span>';
                                        dsp += '</div>';
                                        dsp += '<div class="col-md-12 col-sm-12" style="text-align:center;">';
                                            dsp += '<span class="order-ref"><b style="font-size:16px;">' + ref_name + '</b></span></br>';
                                            dsp += '<span class="order-ref"><b style="font-size:12px;">' + ref_status + '</b></span></br>';
                                            // dsp += '<span class="order-total">Rp. ' + addCommas(order_total) + '</br>';
                                            // dsp += '<span class="order-date">' + order_date_format + '</br>';
                                            // var order_dp = 0;
                                            // var order_dp_label = '';
                                            // if (parseFloat(order_down_payment) > 0) {
                                            //     order_dp = order_down_payment;
                                            //     order_dp_label = '<span class="label">Down Payment Rp. ' + addCommas(order_dp) + '</label>';
                                            // }
                                            // dsp += '<span class="order-dp-total">' + order_dp_label + '</br>';
                                        dsp += '</div>';
                                    dsp += '</div>';                                    
                                dsp += '</div>';
                            }
                            $("#room-tab-detail").html(dsp);
                        } else {
                            $("#room-tab-detail").html('');
                        }
                    } else {
                        notif(0, d.message);
                    }                    
                },
                error: function (xhr, Status, err) {
                    notif(0, err);
                }
            });
        }
        function loadOrderItem(params) { /* load-order-items  ref_id,contact_id*/
            // if(parseInt(ref_id) > 0){
                var ref_id = params['ref_id'];
                var contact_id = params['contact_id'];

                var data = {
                    action: 'load-order-items',
                    tipe: identity,
                    ref_id:ref_id,
                    contact_id:contact_id
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json',
                    cache: 'false',
                    beforeSend: function () {
                        $("#table-item tbody").html('<tr><td colspan="4" style="padding-top:8px!important;padding-bottom:8px!important;text-align:center;"><span class="fas fa-spinner fa-spin"></span> Loading...</td></tr>');
                    },
                    success: function (d) {
                        if (parseInt(d.status) === 1) { //Success
                            // notif(0,d.message);
                            var total_records = d.total_records;
                            if (parseInt(total_records) > 0) {
                                var dsp = '';
                                var total_recordss = parseInt(d.total_records.length);
                                for (var a = 0; a < total_records; a++) {
                                    dsp += '<tr class="tr-trans-item-id" data-id="' + d.result[a]['order_item_id'] + '">';
                                    dsp += '<td>';
                                    dsp += '<button class="btn-delete-order-item btn btn-danger" data-id="' + d.result[a]['order_item_id'] + '" data-ref-id="' + ref_id + '" data-nama="' + d.result[a]['product_name'] + '" data-kode="' + d.result[a]['product_code'] + '">';
                                    dsp += '<span class="fas fa-trash-alt"></span>';
                                    dsp += '</button>';
                                    dsp += '</td>';
                                    dsp += '<td>' +
                                            d.result[a]['product_name'] + '<br>' +
                                            'Rp.' + d.result[a]['order_item_price'] + ' x ' + d.result[a]['order_item_qty'] + '';

                                    // Discount
                                    /*
                                        var discount = d.result[a]['order_item_discount'];
                                        if(parseInt(discount) > 0){
                                            dsp += '<br><button class="btn btn-delete-order-item-discount btn-danger btn-mini" data-id="'+d.result[a]['order_item_id']+'" data-name="'+d.result[a]['product_name']+'" data-qty="'+d.result[a]['order_item_qty']+'" data-note="'+d.result[a]['order_item_note']+'" data-discount="'+d.result[a]['order_item_discount']+'" data-price="'+d.result[a]['order_item_price']+'" data-total="'+d.result[a]['order_item_total']+'" data-total-after-discount="'+d.result[a]['order_item_total_after_discount']+'">X</button><button class="btn-click-order-item-discount btn btn-default btn-mini" data-id="'+d.result[a]['order_item_id']+'" data-name="'+d.result[a]['product_name']+'" data-qty="'+d.result[a]['order_item_qty']+'" data-note="'+d.result[a]['order_item_note']+'" data-discount="'+d.result[a]['order_item_discount']+'" data-price="'+d.result[a]['order_item_price']+'" data-total="'+d.result[a]['order_item_total']+'" data-total-after-discount="'+d.result[a]['order_item_total_after_discount']+'">';
                                            dsp += '<span class="fa fa-pencil"></span> Discount: '+d.result[a]['order_item_discount'];
                                            dsp += '</button>';  
                                        }else{
                                            dsp += '<br><button class="btn-click-order-item-discount btn btn-info btn-mini" data-id="'+d.result[a]['order_item_id']+'" data-name="'+d.result[a]['product_name']+'" data-qty="'+d.result[a]['order_item_qty']+'" data-note="'+d.result[a]['order_item_note']+'" data-discount="'+d.result[a]['order_item_discount']+'" data-price="'+d.result[a]['order_item_price']+'" data-total="'+d.result[a]['order_item_total']+'" data-total-after-discount="'+d.result[a]['order_item_total_after_discount']+'">';
                                            dsp += '<span class="fas fa-percentage"></span> Discount';
                                            dsp += '</button>';
                                        }
                                    */

                                    var has_other_price = d.result[a]['has_other_price'];
                                    var total_other_price = parseInt(has_other_price.length);
                                    if (has_other_price) {
                                        for (var other_price = 0; other_price < total_other_price; other_price++) {

                                            dsp += '<br><button class="btn btn-save-order-item-price-other btn-default btn-mini"'
                                                    + ' data-order-item-id="' + d.result[a]['order_item_id'] + '"'
                                                    + ' data-product-id="' + d.result[a]['product_id'] + '"'
                                                    + ' data-product-name="' + d.result[a]['product_name'] + '"'
                                                    + ' data-product-price-id="' + d.result[a]['has_other_price'][other_price]['product_price_id'] + '"'
                                                    + ' data-product-price-name="' + d.result[a]['has_other_price'][other_price]['product_price_name'] + '"'
                                                    + ' data-product-price-price="' + d.result[a]['has_other_price'][other_price]['product_price_price'] + '"'
                                                    + ' data-order-item-qty="' + d.result[a]['order_item_qty'] + '"'
                                                    + ' data-note="' + d.result[a]['has_other_price'][other_price]['product_price_name'] + '"'
                                                    + ' data-ref-id="' + ref_id + '">';
                                            dsp += '<span class="fas fa-file"></span> Pakai Harga ' + d.result[a]['has_other_price'][other_price]['product_price_name'] + ': ';
                                            dsp += '<b>' + addCommas(d.result[a]['has_other_price'][other_price]['product_price_price']) + '</b>';
                                            dsp += '</button>';
                                        }
                                    }

                                    // Noted
                                    var note = d.result[a]['order_item_note'];
                                    if (note) {
                                        dsp += '<br><button class="btn btn-delete-order-item-note btn-danger btn-mini" data-id="' + d.result[a]['order_item_id'] + '" data-ref-id="' + ref_id + '" data-note="' + d.result[a]['order_item_note'] + '">X</button><button class="btn-save-order-item-note btn btn-default btn-mini" data-id="' + d.result[a]['order_item_id'] + '" data-name="' + d.result[a]['product_name'] + '" data-qty="' + d.result[a]['order_item_qty'] + '" data-note="' + d.result[a]['order_item_note'] + '">';
                                        dsp += '<span class="fa fa-pencil"></span> Catatan: ' + d.result[a]['order_item_note'];
                                        dsp += '</button></button>';
                                    } else {
                                        dsp += '<br><button class="btn-save-order-item-note btn btn-info btn-mini" data-id="' + d.result[a]['order_item_id'] + '" data-ref-id="' + ref_id + '" data-name="' + d.result[a]['product_name'] + '" data-qty="' + d.result[a]['order_item_qty'] + '" data-note="' + d.result[a]['order_item_note'] + '">';
                                        dsp += '<span class="fas fa-plus"></span> Catatan';
                                        dsp += '</button>';
                                    }

                                    // Plus + Minus -
                                    dsp += '</td>';
                                    dsp += '<td style="text-align:right;">';
                                        dsp += '<div class="group-plus-minus">';
                                            dsp += '<button class="btn btn-save-order-item-plus-minus btn-small btn-warning" data-id="' + d.result[a]['order_item_id'] + '" data-ref-id="' + ref_id + '" data-qty="' + d.result[a]['order_item_qty'] + '" data-price="' + d.result[a]['order_item_price'] + '" data-operator="decrease" data-discount="' + d.result[a]['order_item_discount'] + '" style="border-radius:50%;"><span class="fas fa-minus"></span></button>&nbsp;&nbsp;';
                                            // dsp += '<input class="form-controls" value="' + d.result[a]['order_item_qty_format'] + '" style="border:2px solid #1f3853;width:32px;height:40px;text-align:center;" disabled="true">&nbsp;&nbsp;';
                                            dsp += '<button class="btn btn-small btn-default" style="border-radius:50%;" onclick="return;" type="button">' + d.result[a]['order_item_qty_format'] + '</button>';                                            
                                            dsp += '<button class="btn btn-save-order-item-plus-minus btn-small btn-success" data-id="' + d.result[a]['order_item_id'] + '" data-ref-id="' + ref_id + '" data-qty="' + d.result[a]['order_item_qty'] + '" data-price="' + d.result[a]['order_item_price'] + '" data-operator="increase" data-discount="' + d.result[a]['order_item_discount'] + '" style="border-radius:50%;"><span class="fas fa-plus"></span></button>';
                                        dsp += '</div>';
                                    dsp += '</td>';
                                    dsp += '<td style="text-align:right;">' + d.result[a]['order_item_total'] + '</td>';
                                    dsp += '</tr>';
                                }
                                $("#table-item tbody").html(dsp);
                                $("#total_produk").val(d.total_produk);
                                $("#subtotal").val(d.subtotal);
                                $("#total_diskon").val(d.total_diskon);
                                $("#total").val(addCommas(d.total));
                                // $("#btn-cancel-order").css('display', 'inline');
                                // $("#btn-save-order").css('display', 'inline');
                                $("#btn-cancel-order").attr('disabled',false);
                                $("#btn-save-order").attr('disabled',false);
                                $("#total_down_payment").attr('readonly', false);

                                // $("select[id='ref']").append(''+'<option value="'+d.ref.ref_id+'">'+d.ref.ref_name+'</option>');
                                // $("select[id='ref']").val(d.ref.ref_id).trigger('change');
                                // $("#modal_booking_title").html(d.ref.ref_name);
                                // if(parseInt(d.ref.ref_id) > 0){
                                //     $("select[id='ref']").append(''+'<option value="'+d.ref.ref_id+'" data-use-type="'+d.ref.ref_use_type+'" data-flag="'+d.ref.ref_flag+'">'+d.ref.ref_name+'</option>');
                                //     $("select[id='ref']").val(d.ref.ref_id).trigger('change');
                                //     console.log(d.ref.ref_id);
                                // }
                                // formOrder({action:2});
                            } else {
                                $("#table-item tbody").html('');
                                $("#table-item tbody").html('<tr><td colspan="3" style="padding-top:8px!important;padding-bottom:8px!important;text-align:center;">Tidak ada item produk</td></tr>');
                            }
                            if(ref_id > 0){
                                $("select[id='ref']").append(''+'<option value="'+d.ref.ref_id+'" data-use-type="'+d.ref.ref_use_type+'" data-flag="'+d.ref.ref_flag+'">'+d.ref.ref_name+'</option>');
                                $("select[id='ref']").val(d.ref.ref_id).trigger('change');
                                formOrder({action:1,ref_name:d.ref.ref_name});
                            }                                                                                    
                        } else { //No Data
                            // notif(1,d.message);
                            $("#table-item tbody").html('');
                            $("#table-item tbody").html('<tr><td colspan="4" style="padding-top:8px!important;padding-bottom:8px!important;text-align:center;">Tidak ada item produk</td></tr>'); // 
                            $("#total_produk").val(0);
                            $("#subtotal").val(0);
                            $("#total_diskon").val(0);
                            $("#total").val(0);
                            // $("#btn-cancel-order").css('display', 'none');
                            // $("#btn-save-order").css('display', 'none');
                            $("#btn-cancel-order").attr('disabled',true);
                            $("#btn-save-order").attr('disabled',true);    
                            if(ref_id > 0){
                                $("select[id='ref']").append(''+'<option value="'+d.ref.ref_id+'" data-use-type="'+d.ref.ref_use_type+'" data-flag="'+d.ref.ref_flag+'">'+d.ref.ref_name+'</option>');
                                $("select[id='ref']").val(d.ref.ref_id).trigger('change');
                                formOrder({action:2,ref_name:d.ref.ref_name});
                            }                                                         
                        }

                   
                    },
                    error: function (xhr, Status, err) {
                        notif(0,err);
                        $("#table-item tbody").html('<tr><td colspan="4" style="padding-top:8px!important;padding-bottom:8px!important;text-align:center;">Error!</td></tr>');
                    }
                });
            // }
        }
        function loadPaymentItem() { /* load-payment-item */
            console.log('loadPaymentItem()');
            $("#modal-payment-form").modal('hide');
            var data = {
                action: 'bill-load',
                data: ''
            };
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                cache: 'false',
                beforeSend: function () {
                    notif(1,'Memuat '+order_alias+' yg di bayar');
                    $("#table-payment-item tbody").html('<tr><td colspan="3" style="padding-top:8px!important;padding-bottom:8px!important;text-align:center;"><span class="fas fa-spinner fa-spin"></span> Loading...</td></tr>');
                    formPaymentReset();
                    setTimeout(function () {
                        $("#modal-payment-form").modal("show");                          
                    }, 500); 
                },
                success: function (d) {
                    if (parseInt(d.status) === 1) {
                        notif(1, d.message);
                        var total_records = d.total_records;
                        if (parseInt(total_records) > 0) {
                            var dsp = '';
                            var total_recordss = parseInt(d.total_records.length);
                            for (var a = 0; a < total_records; a++) {
                                var order_list = '';
                                /*
                                    // Discount
                                    var discount = d.result[a]['order_item_discount'];
                                    if(parseInt(discount) > 0){
                                        order_list += '<br><button class="btn btn-delete-order-item-discount btn-danger btn-mini" data-id="'+d.result[a]['order_item_id']+'" data-name="'+d.result[a]['product_name']+'" data-qty="'+d.result[a]['order_item_qty']+'" data-note="'+d.result[a]['order_item_note']+'" data-discount="'+d.result[a]['order_item_discount']+'" data-price="'+d.result[a]['order_item_price']+'" data-total="'+d.result[a]['order_item_total']+'" data-total-after-discount="'+d.result[a]['order_item_total_after_discount']+'">X</button><button class="btn-click-item-discount btn btn-default btn-mini" data-id="'+d.result[a]['order_item_id']+'" data-name="'+d.result[a]['product_name']+'" data-qty="'+d.result[a]['order_item_qty']+'" data-note="'+d.result[a]['order_item_note']+'" data-discount="'+d.result[a]['order_item_discount']+'" data-price="'+d.result[a]['order_item_price']+'" data-total="'+d.result[a]['order_item_total']+'" data-total-after-discount="'+d.result[a]['order_item_total_after_discount']+'">';
                                        order_list += '<span class="fa fa-pencil"></span> Discount: '+d.result[a]['order_item_discount'];
                                        order_list += '</button>';  
                                    }else{
                                        order_list += '<br><button class="btn-click-item-discount btn btn-info btn-mini" data-id="'+d.result[a]['order_item_id']+'" data-name="'+d.result[a]['product_name']+'" data-qty="'+d.result[a]['order_item_qty']+'" data-note="'+d.result[a]['order_item_note']+'" data-discount="'+d.result[a]['order_item_discount']+'" data-price="'+d.result[a]['order_item_price']+'" data-total="'+d.result[a]['order_item_total']+'" data-total-after-discount="'+d.result[a]['order_item_total_after_discount']+'">';
                                        order_list += '<span class="fas fa-percentage"></span> Discount';
                                        order_list += '</button>';
                                    }

                                    // Note
                                    var note = d.result[a]['order_item_note'];
                                    if(note){
                                        order_list += '<br><button class="btn btn-delete-order-item-note btn-danger btn-mini" data-id="'+d.result[a]['order_item_id']+'" data-note="'+d.result[a]['order_item_note']+'">X</button><button class="btn-click-item-note btn btn-default btn-mini" data-id="'+d.result[a]['order_item_id']+'" data-name="'+d.result[a]['product_name']+'" data-qty="'+d.result[a]['order_item_qty']+'" data-note="'+d.result[a]['order_item_note']+'">';
                                        order_list += '<span class="fa fa-pencil"></span> Catatan: '+d.result[a]['order_item_note'];
                                        order_list += '</button></button>';  
                                    }else{
                                        order_list += '&nbsp;<button class="btn-click-item-note btn btn-info btn-mini" data-id="'+d.result[a]['order_item_id']+'" data-name="'+d.result[a]['product_name']+'" data-qty="'+d.result[a]['order_item_qty']+'" data-note="'+d.result[a]['order_item_note']+'">';
                                        order_list += '<span class="fas fa-plus"></span> Catatan';
                                        order_list += '</button>';
                                    }
                                */
                                var items = d.result[a]['order_items'];
                                order_list += '<br>';
                                for (var i = 0; i < parseInt(items.length); i++) {
                                    order_list += '<br>';
                                    order_list += d.result[a]['order_items'][i]['product_name'];
                                    order_list += '<br>Rp. ' + d.result[a]['order_items'][i]['order_item_price'];
                                    order_list += ' X ' + d.result[a]['order_items'][i]['order_item_qty'];
                                    if (d.result[a]['order_items'][i]['order_item_discount'] != "0") {
                                        order_list += '&nbsp;<i>(Diskon: - ' + d.result[a]['order_items'][i]['order_item_discount'] + ')</i>';
                                    }
                                    if (d.result[a]['order_items'][i]['order_item_note'] != undefined) {
                                        order_list += '<br><i>' + d.result[a]['order_items'][i]['order_item_note'] + '</i>';
                                    }
                                    order_list += '<br>';
                                }                         

                                dsp += '<tr class="tr-trans-item-id" data-id="' + d.result[a]['order_item_id'] + '">';
                                dsp += '<td>';
                                    dsp += '<button class="btn-remove-payment btn btn-danger" data-id="' + d.result[a]['order_id'] + '" data-ref="' + d.result[a]['ref_name'] + '" data-number="' + d.result[a]['order_number'] + '">';
                                        dsp += '<span class="fas fa-trash-alt"></span>';
                                    dsp += '</button><br>';
                                    dsp += '<button class="btn-print btn btn-info" data-id="' + d.result[a]['order_id'] + '" data-number="' + d.result[a]['order_number'] + '" data-session="' + d.result[a]['order_session'] + '">';
                                        dsp += '<span class="fas fa-print"></span>';
                                    dsp += '</button>';                                    
                                dsp += '</td>';
                                dsp += '<td>';
                                    dsp += '<label class="label label-primary"><b>'+ d.result[a]['ref_name'] + '</label></b><br>';
                                    // dsp += '<label class="label label-success"><b><i class="fas fa-user"></i> '+ d.result[a]['employee_name'] + '</b></label><br>';
                                    dsp += '<b>'+d.result[a]['employee_name']+'</b><br>';
                                    dsp += '<b>'+d.result[a]['order_number']+'</b><br>';
                                    dsp += '<b>'+d.result[a]['order_date_format']+'</b><br>';
                                    dsp += '<b>'+ d.result[a]['contact_name'] + '</b>';                                        
                                    dsp += order_list;
                                dsp += '</td>';
                                    dsp += '<td style="text-align:right;"><b>Rp. ' + addCommas(d.result[a]['order_total_grand']) + '</b></td>';
                                dsp += '</tr>';

                                //Check is Member or Not
                                if(parseInt(d.result[0]['contact_is_member']) == 1){
                                    checkBoxPaymentNonmember(0);
                                    $("select[id='payment_contact_id']").append(''+'<option value="'+d.result[0]['order_contact_id']+'" data-contact-session="'+d.result[0]['contact_session']+'">'+d.result[0]['contact_code']+' - '+d.result[0]['contact_name']+' '+d.result[0]['contact_phone']+'</option>');
                                    $("select[id='payment_contact_id']").val(d.result[0]['order_contact_id']).trigger('change');
                                }else{
                                    checkBoxPaymentNonmember(1); 
                                    $("#payment_contact_name").val(d.result[0]['contact_name']);
                                    $("#payment_contact_phone").val(d.result[0]['contact_phone']);                                                                   
                                }
                            }
                            $("#table-payment-item tbody").html(dsp);
                            $("#order_list_id").val(d.order_list_id);
                            
                            $("#subtotal_payment").val(d.subtotal);
                            $("#total_diskon_payment").val(d.total_diskon);
  
                            $("#method-payment-total-before").val(d.total);
                            $("#method-payment-total").val(d.total);
                            $("#method-payment-total").attr('data-total', d.total);
                            modal_total = d.total_raw;

                            // setTimeout(function () {
                            //     // $("#modal-payment-form").modal({backdrop: 'static', keyboard: false});                          
                            // }, 500);                       

                            // $("#total_subtotal").val(d.subtotal);
                            // $("#total_down_payment_payment").val(d.total_down_payment);
                        } else {
                            // $("#table-payment-item tbody").html('');
                            $("#table-payment-item tbody").html('<tr><td colspan="3">Tidak ada daftar '+order_alias+' yg akan di bayar</td></tr>');
                            $("#order_list_id").val('');
                        }
                    } else {
                        // notif(0,d.message);
                        // $("#table-payment-item tbody").html('');
                        $("#table-payment-item tbody").html('<tr><td colspan="4">Tidak ada daftar '+order_alias+' yg akan di bayar</td></tr>');
                        
                        // $("#total_produk_payment").val(0);
                        $("#subtotal_payment").val(0);
                        $("#total_diskon_payment").val(0);
                        // $("#total_payment").val(0);
                        
                        $("#method-payment-total-before").val(0);
                        $("#method-payment-total").val(0);

                        // $("#modal-payment-form").modal('hide');
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0,err);
                }
            });
        }

        //Modal
        function loadOrderDetail(order_id) { /* read-order */
            $("#modal-order-addon").modal('hide');
            if(parseInt(order_id) > 0){
                setTimeout(function () {
                    var id = order_id;
                    var total = $(this).attr('data-total');
                    var next = true;

                    if (next == true) {
                        var prepare = {
                            tipe: identity,
                            order_id: id
                        };
                        var prepare_data = JSON.stringify(prepare);
                        var data = {
                            action: 'pos-read',
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
                                $(".btn-prepare-payment")
                                .attr('data-id', d.result['order_id']).attr('data-ids', 'AS')
                                .attr('data-grand-total', d.total_grand);

                                $("#btn-print-order")
                                .attr('data-id', d.result['order_id'])
                                .attr('data-number', d.result['order_number']);

                                $("#btn-addon-order")
                                .attr('data-order-id', d.result['order_id'])
                                .attr('data-order-number', d.result['order_number'])
                                .attr('data-ref-name', d.result['ref_name'])
                                .attr('data-ref-id', d.result['ref_id']);

                                $(".btn-cancel-order")
                                .attr('data-order-id', d.result['order_id'])
                                .attr('data-order-number', d.result['order_number'])
                                .attr('data-grand-total', d.total_grand)
                                .attr('data-ref-id', d.result['ref_id'])
                                .attr('data-ref-name', d.result['ref_name']);    

                                $(".btn-move-room-order")
                                .attr('data-order-id', d.result['order_id'])
                                .attr('data-order-number', d.result['order_number'])
                                .attr('data-ref-id', d.result['ref_id'])
                                .attr('data-ref-name', d.result['ref_name']);                                

                                $("#modal-payment-order-ref").html(d.result['ref_name']);
                                $("#modal-payment-order-date").html(d.result['order_date_format']);         
                                $("#modal-payment-ref-name").html(d.result['ref_name']);
                                $("#modal-payment-contact-name").html(d.result['contact_name']);
                                $("#modal-payment-employee-name").html(d.result['employee_name']);
                                $("#modal-payment-contact-non").html(d.result['order_contact_name']);

                                if (parseInt(d.status) == 1) { //Success
                                    $("#modal-payment-list").modal('show');
                                    $("#modal-payment-order-list tbody").html('');
                                    if (parseInt(d.total_records) > 0) {

                                        var dsp = '';
                                        var total = 0;
                                        // var total_records = parseInt(d.total_records.length);
                                        var grand_total = 0;
                                        for (var a = 0; a < d.total_records; a++) {
                                            dsp += '<tr>';
                                            dsp += '<td><b>' + d.result_item[a]['product_name'] + '</b>';
                                            dsp += '<br>Rp. ' + addCommas(d.result_item[a]['order_item_price']) + ', ';
                                            if (d.result_item[a]['order_item_note'] != undefined) {
                                                dsp += '<br><i>(' + d.result_item[a]['order_item_note'] + ')</i>';
                                            }

                                            // Discount
                                            var discount = d.result_item[a]['order_item_discount'];
                                            if (parseInt(discount) > 0) {
                                                // dsp += '<br><button class="btn btn-delete-order-item-discount btn-danger btn-mini" data-order-id="' + d.result_item[a]['order_item_order_id'] + '" data-id="' + d.result_item[a]['order_item_id'] + '" data-name="' + d.result_item[a]['product_name'] + '" data-qty="' + d.result_item[a]['order_item_qty'] + '" data-discount="' + d.result_item[a]['order_item_discount'] + '" data-price="' + d.result_item[a]['order_item_price'] + '" data-total="' + d.result_item[a]['order_item_total'] + '" data-total-after-discount="' + d.result_item[a]['order_item_total_after_discount'] + '">X</button><button class="btn-click-item-discount btn btn-default btn-mini" data-id="' + d.result_item[a]['order_item_id'] + '" data-name="' + d.result_item[a]['product_name'] + '" data-qty="' + d.result_item[a]['order_item_qty'] + '" data-discount="' + d.result_item[a]['order_item_discount'] + '" data-price="' + d.result_item[a]['order_item_price'] + '" data-total="' + d.result_item[a]['order_item_total'] + '" data-total-after-discount="' + d.result_item[a]['order_item_total_after_discount'] + '">';
                                                // dsp += '<span class="fa fa-pencil"></span> Discount: ' + d.result_item[a]['order_item_discount'];
                                                // dsp += '</button>';
                                            } else {
                                                // dsp += '<br><button class="btn-click-order-item-discount btn btn-info btn-mini" data-order-id="' + d.result_item[a]['order_item_order_id'] + '" data-id="' + d.result_item[a]['order_item_id'] + '" data-name="' + d.result_item[a]['product_name'] + '" data-qty="' + d.result_item[a]['order_item_qty'] + '" data-discount="' + d.result_item[a]['order_item_discount'] + '" data-price="' + d.result_item[a]['order_item_price'] + '" data-total="' + d.result_item[a]['order_item_total'] + '" data-total-after-discount="' + d.result_item[a]['order_item_total_after_discount'] + '">';
                                                // dsp += '<span class="fas fa-percentage"></span> Discount';
                                                // dsp += '</button>';
                                            }

                                            dsp += '</td>';
                                            dsp += '<td>' + d.result_item[a]['order_item_qty'] + '</td>';
                                            dsp += '<td style="text-align:right;"><b>' + addCommas(d.result_item[a]['order_item_total']) + '</b></td>';
                                            dsp += '<td><button class="btn-delete-order-item btn btn-danger" data-id="' + d.result_item[a]['order_item_id'] + '" data-nama="' + d.result_item[a]['product_name'] + '" data-kode="' + d.result_item[a]['product_name'] + '" data-order-id="' + d.result_item[a]['order_item_order_id'] + '"><span class="fas fa-trash-alt"></span></button></td></td>';
                                            dsp += '</tr>';
                                            // total = parseFloat(total) + parseFloat(d.result_item[a]['order_item_total']);
                                        }

                                        // dsp += '<tr>';
                                        // dsp += '<td><b>Total</b></td>';
                                        // dsp += '<td></td>';
                                        // dsp += '<td style="text-align:right;">' + addCommas(d.total) + '</td>';
                                        // dsp += '<td></td>';
                                        // dsp += '</tr>';

                                        // dsp += '<tr>';
                                        // dsp += '<td><b>Down Payment</b></td>';
                                        // dsp += '<td></td>';
                                        // dsp += '<td style="text-align:right;">' + addCommas(d.total_dp) + '</td>';
                                        // dsp += '<td></td>';
                                        // dsp += '</tr>';

                                        dsp += '<tr style="background-color: #ecf0f2;">';
                                        dsp += '<td><b>GRAND TOTAL</b></td>';
                                        dsp += '<td></td>';
                                        dsp += '<td style="text-align:right;"><b>' + addCommas(d.total_grand) + '</b></td>';
                                        dsp += '<td></td>';
                                        dsp += '</tr>';

                                        $("#modal-payment-order-list tbody").append(dsp);
                                    }
                                } else {
                                    $("#modal-payment-order-list tbody").html('');
                                    $("#modal-payment-order-list tbody").html('<tr><td colspan="4">Tidak ada Produk</td></tr>');                                    
                                    $("#modal-payment-list").modal('show');                                    
                                    notif(0, d.message);
                                }
                            },
                            error: function (xhr, Status, err) {
                                notif(0, err);
                            }
                        });
                    }
                }, 1000);
            }else{
                notif(0,order_alias+' tidak ditemukan');
            }
        } 
        function loadPaymentMethod(params){
            console.log('loadPaymentMethod()');
        }
        function loadPaymentSuccess(params){
            var d = params; 
            //Prepare Print
            $(".btn-print-payment").attr('data-id', d.trans_id);
            $(".btn-print-payment").attr('data-number', d.trans_number);
            $(".btn-print-payment").attr('data-session', d.trans_session);

            // $("#modal-trans-save").modal('toggle');

            //Set Text
            $(".modal-print-trans-number").html(' :' + d.trans_number);
            $(".modal-print-trans-date").html(': ' + d.trans_date);                                            
            $(".modal-print-trans-paid-type-name").html(': ' + d.paid_type_name);
            $(".modal-print-trans-total").html(': ' + addCommas(d.trans_total));
            $(".modal-print-trans-total-paid").html(': ' + addCommas(d.trans_total_paid));
            $(".modal-print-trans-total-change").html(': ' + addCommas(d.trans_total_change));
            
            $("#modal-print-contact-name").val(' ' + d.contact_name);
            $("#modal-print-contact-phone").val(' ' + d.contact_phone);
            
            $(".btn-send-whatsapp").attr('data-id',d.trans_id)
            .attr('data-number',d.trans_number)
            .attr('data-date',d.trans_date)
            .attr('data-total',d.trans_total)
            .attr('data-contact-id',d.contact_id)
            .attr('data-contact-name',d.contact_name)
            .attr('data-contact-phone',d.contact_phone);

            $("#modal-payment-print").modal({backdrop: 'static', keyboard: false});
        }
        function loadDownPayment(params){ //Untuk Mengambil Sisa (Uang Muka / Deposit), Dari Contact_Id
            if(params['action'] == 1){
                $("#modal_dp_contact_id").append('<option value="'+params['contact_id']+'">'+params['contact_name']+'</option>');

                var cn_id = params['contact_id'];
                let form = new FormData();
                form.append('action', 'down-payment-balance');
                form.append('contact_id', cn_id);
                form.append('journal_type', 7);                
                $.ajax({
                    type: "post",
                    url: url_finance,
                    data: form, 
                    dataType: 'json', cache: 'false', 
                    contentType: false, processData: false,
                    beforeSend:function(){
                        $("#modal_dp_contact_id").val(params['contact_id']).trigger('change');
                        modal_down_payment = 0;
                    },
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            var balance = r.balance;
                            var balance_format = r.balance_format;                            
                            $("#modal_dp_balance").val(balance_format);
                            modal_down_payment = balance;

                            //Auto Set Modal Dibayar
                            if(modal_down_payment >= modal_total){                            
                                modal_total_dibayar = modal_total;
                                notif(1,'Saldo '+dp_alias+' bisa dipakai');
                                $("#method-payment-received").val(modal_total);
                            }else{
                                notif(0,'Saldo '+dp_alias+' tidak mencukupi');
                            }
                            // console.log('Total: '+modal_total+', DownPayment: '+modal_down_payment+', Dibayar: '+modal_total_dibayar+', Kembali: '+modal_total_kembali);      
                        }else{
                            $("#modal_dp_balance").val(0);
                            modal_down_payment = 0;
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                        modal_down_payment = 0;
                    }
                });
            }else{
                $("#modal_dp_contact_id").html('<option value="0">-- Pilih --</option>');
                $("#modal_dp_balance").val(0);
            }
            // console.log('Total: '+modal_total+', DownPayment: '+modal_down_payment+', Dibayar: '+modal_total_dibayar+', Kembali: '+modal_total_kembali);            
        }

        //Not Used
        function loadBookingNavigation(params){ //Not Used
            console.log('loadBookingNavigation() NOT USED');
            $.confirm({
                title: 'Pilihan Menu',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
                autoClose: 'button_2|100000',
                closeIcon: false, closeIconClass: 'fas fa-times', 
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content: function(){
                },
                onContentReady: function(e){
                    let self    = this;
                    let content = '';
                    let dsp     = '';
                    var attr    = '';

                    var oid = params['order_id'];
                    var on  = params['order_number'];
                    var ri = params['ref_id'];
                    var rn = params['ref_name'];
                    var gt = params['grand_total'];

                    attr = 'data-order-id="'+oid+'" data-order-number="'+on+'" data-ref-id="'+ri+'" data-ref-name="'+rn+'" data-grand-total="'+gt+'"';
                    
                    // dsp += '<div></div>';
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                    // dsp += '<div class="navbar-inner" class="visible-xs visible-sm">';
                    // dsp += '<div class="header-seperation">';

                    dsp += '<ul class="ul-user-navigation">';
                        dsp += '<li><a href="#" class="btn-move-room-order" '+attr+'><i class="fas fa-arrow-right"></i><span style="position: relative;">&nbsp;Pindah '+ref_alias+'</span></a></li>';
                        dsp += '<li><a href="#" class="btn-addon-order" '+attr+'><i class="fas fa-plus"></i><span style="position: relative;">&nbsp;Tambah Produk</span></a></li>';
                        dsp += '<li><a href="#" class="btn-prepare-payment"   '+attr+'><i class="fas fa-check white"></i><span style="position: relative;">&nbsp;Masukkan ke Antrian '+payment_alias+'</span></a></li>';
                        dsp += '<li><a href="#" class="btn-cancel-order" '+attr+'><i class="fas fa-times"></i><span style="position: relative;">&nbsp;Batalkan '+order_alias+'</span></a></li>';
                    dsp += '</ul>';
                    dsp += '</div>';
                    // dsp += '</div>';
                    content = dsp;
                    self.setContentAppend(content);
                },
                buttons: {
                    button_2: {
                        text: 'Tutup',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }
                }
            });
        }      
        function loadUnpaidOrder() { /* Not Used -- load-unpaid-order */
            $.alert('Not Used');
            var payment_tab = $("#payment-tab");
            var prepare = {
                tipe: identity,
                order_flag: 0
            };
            var data = {
                action: 'pos-load-unpaid',
                data: JSON.stringify(prepare)
            };
            $.ajax({
                type: "post",
                url: url,
                data: data,
                dataType: 'json',
                cache: 'false',
                beforeSend: function () {
                    $("#payment-tab-detail").html('<span class="fas fa-spinner fa-spin"></span> Loading...');
                },
                success: function (d) {
                    if (parseInt(d.status) == 1) {
                        if (parseInt(d.total_records) > 0) {
                            $("#payment-tab-detail").html('');
                            var dsp = '';
                            var total_records = parseInt(d.total_records);
                            for (var b = 0; b < total_records; b++) {
                                var order_id = d.result[b]['order_id'];
                                var ref_name = d.result[b]['ref_name'];
                                var order_number = d.result[b]['order_number'];
                                var order_total = d.result[b]['order_grand_total'];
                                var order_date_format = d.result[b]['order_date_format'];
                                var order_down_payment = d.result[b]['order_with_dp'];

                                var image = '<?php echo site_url('upload/product/product2.png'); ?>';
                                var set_ref = "fas fa-clipboard";
                                var set_color = "#ecf0f2";
                                // if(ref_name=='Take Away'){
                                // set_ref = "fas fa-shipping-fast";
                                // set_color = "#d1dade";
                                // }
                                dsp += '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 btn-read-payment payment-tab-detail-item padding-remove-side"';
                                dsp += 'data-id="' + order_id + '">'; /*background-color:' + set_color + '; */
                                    dsp += '<div class="col-md-12 col-sm-12" style="padding:12px 0px;cursor:pointer;border:1px solid white;">';
                                        dsp += '<div class="col-md-12 col-sm-12" style="text-align:center;">';
                                            dsp += '<span class="' + set_ref + ' fa-2x"></span>';
                                        dsp += '</div>';
                                        dsp += '<div class="col-md-12 col-sm-12" style="text-align:center;">';
                                            dsp += '<span class="order-ref"><b style="font-size:14px;">' + order_number + '</b></span></br>';
                                            dsp += '<span class="order-total">Rp. ' + addCommas(order_total) + '</br>';
                                            dsp += '<span class="order-date">' + order_date_format + '</br>';
                                            dsp += '<span class="order-contact-name"><b>' + d.result[b]['contact_name'] + '</b></br>';   
                                            dsp += '<span class="order-employee-name"><b>By ' + d.result[b]['employee_name'] + '</b></br>';
                                            var order_dp = 0;
                                            var order_dp_label = '';
                                            if (parseFloat(order_down_payment) > 0) {
                                                order_dp = order_down_payment;
                                                order_dp_label = '<span class="label">Down Payment Rp. ' + addCommas(order_dp) + '</label>';
                                            }
                                            dsp += '<span class="order-dp-total">' + order_dp_label + '</br>';
                                            dsp += '<span class="order-ref"><b style="font-size:14px;">' + ref_name + '</b></span></br>';
                                        dsp += '</div>';
                                    dsp += '</div>';                                    
                                dsp += '</div>';
                            }
                            $("#payment-tab-detail").html(dsp);
                        } else {
                            $("#payment-tab-detail").html('<div class="col-md-12 col-sm-12 padding-remove-side prs-0">Tidak ada daftar '+payment_alias+'</div>');
                        }
                    } else {
                        notif(0, d.message);
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0, err);
                }
            });
        }

        // Other
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
        // loadDownPayment({action:1,contact_id:28,journal_type:7});
        // loadOrderItem({ref_id:0,contact_id:0});
        // loadPaymentItem();        
    });

    function formOrder(params){
        if(params['action'] == 0){
            $("#modal-order").modal('hide');
        }else if(params['action'] == 1){
            $("#modal-order").modal({backdrop: 'static', keyboard: false});
            setTimeout(function(){ 
                var ref_n = $("#ref").find(":selected").text();                
                $("#modal_booking_title").html(params['ref_name']);
            }, 500);
        }else if(params['action'] == 2){ // Found Temporary
            $("#modal-order").modal({backdrop: 'static', keyboard: false});
            setTimeout(function(){ 
                var ref_n = $("#ref").find(":selected").text();                
                $("#modal_booking_title").html(params['ref_name']);
            }, 800);
        }
        // console.log('formOrder: '+JSON.stringify(params));
    }
    function formPaymentReset(){
        //Transfer
        $("#modal_nomor_ref_transfer").val('');
        $("#modal_nama_pengirim").val('');        
        //EDC
        $("#modal_bank_penerbit").val(0).trigger('change');
        $("#modal_jenis_kartu").val(0).trigger('change');                
        $("#modal_valid_tahun").val('');
        $("#modal_valid_bulan").val('');
        $("#modal_nomor_kartu").val('');
        $("#modal_nama_pemilik").val('');
        $("#modal_kode_transaksi").val('');

        //Voucher
        $("#modal-payment-voucher").val('');
        $("#modal-payment-received").val(0);                        
    }
    function formWhatsApp(params) {
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
        content += 'Apakah anda ingin mengirim Invoice ?<br><br>';
        let title = 'Kirim Invoice ke WhatsApp';
        $.confirm({
            title: title,
            columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
            autoClose: 'button_2|30000',
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
                dsp += '    <div class="form-group">';
                dsp += '    <label class="form-label">Total</label>';
                dsp += '        <input id="jc_total" name="jc_total" class="form-control" value="' + addCommas(d['trans_total']) + '" readonly>';
                dsp += '    </div>';
                dsp += '</div>';
                dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                dsp += '    <div class="form-group">';
                dsp += '    <label class="form-label">Nama Penerima</label>';
                dsp += '        <input id="jc_contact_name" name="jc_contact_name" class="form-control" value="' + d['contact_name'] + '">';
                dsp += '    </div>';
                dsp += '</div>';
                dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                dsp += '    <div class="form-group">';
                dsp += '    <label class="form-label">Nomor Whatsapp</label>';
                dsp += '        <input id="jc_contact_number" name="jc_contact_number" class="form-control" value="' + d['contact_phone'] + '">';
                dsp += '    </div>';
                dsp += '</div>';
                dsp += '</form>';
                content = dsp;
                self.setContentAppend(content);
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
                                action: 'whatsapp-send-message-invoice-trans-order',
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

        return;
        $.confirm({
            title: 'Kirim Invoice ke WhatsApp',
            content: content,
            columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
            autoClose: 'button_2|1000000',
            closeIcon: true,
            closeIconClass: 'fas fa-times',
            buttons: {
                button_1: {
                    text: 'Kirim',
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        var data = {
                            action: 'whatsapp-send-message-invoice-trans-order',
                            trans_id: d['trans_id'],
                            contact_id: d['contact_id'],
                            contact_name: d['contact_name'],
                            contact_phone: d['contact_phone'],
                        }
                        $.ajax({
                            type: "POST",
                            url: url_message,
                            data: data,
                            dataType: 'json',
                            cache: false,
                            beforeSend: function () {},
                            success: function (d) {
                                if (parseInt(d.status) == 1) {
                                    notif(1, d.message);
                                    order_table.ajax.reload(null, false);
                                    $("#modal-trans-save").modal('hide');
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

    }
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
    function formTransNew() {
        formTransSetDisplay(0);
        $("#form-trans input").val();
        $("select[name='order_contact_id']").val(0).trigger('change');
        $("select[name='order_sales_id']").val(0).trigger('change');        
        $("select[name='ref']").val(0).trigger('change');
        // $("#btn-new-order").hide();
        $("#btn-save-order").show();
        $("#btn-cancel-order").show();

        //Reset Table Item
        $("#table-item tbody").html('<tr><td colspan="4" style="text-align:center;">Kosong</td></tr>');
        $("#total_produk").val(0);
        $("#total").val(0);        
    }
    function formTransCancel() {
        formTransSetDisplay(1);
        $("#form-trans input").val();
        $("select[name='order_contact_id']").val(0).trigger('change');
        $("select[name='order_sales_id']").val(0).trigger('change');        
        $("select[name='ref']").val(0).trigger('change');        
        // $("#btn-new-order").show();
        $("#btn-save-order").hide();
        $("#btn-update-order").hide();
        $("#btn-cancel-order").hide();
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
            // "kontak",
            "order_sales_id",
            // "ref"
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
            "satuan",
            "qty",
            "harga",
        ];
        $("input[name='qty']").val(1);
        $("input[name='harga']").val(0);

        for (var i = 0; i <= attrInput.length; i++) {
            $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        }
        $("" + form + " input[name='satuan']").attr('readonly', true);
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
    function checkBoxOrderNonmember(flag) {
        if (flag == 0) {
            $("#order_contact_checkbox_flag").prop("checked", true);
            $(".order_contact_checkbox").attr("data-flag", 1);
            $("#order_contact_name").attr('readonly',true);
            $("#order_contact_phone").attr('readonly',true);            
            $("#order_contact_id").removeAttr('disabled');            
        } else {
            $("#order_contact_checkbox_flag").prop("checked", false);
            $(".order_contact_checkbox").attr("data-flag", 0);
            $("#order_contact_name").attr('readonly',false);
            $("#order_contact_phone").attr('readonly',false);            
            $("#order_contact_name").val('');
            $("#order_contact_phone").val('');                                                
            $("#order_contact_id").attr('disabled',true);
            $("#order_contact_id").val(0).trigger('change');      
        }
        var fl = $(".order_contact_checkbox").attr('data-flag');   
    }
    function checkBoxPaymentNonmember(flag) {
        if (flag == 0) { //Aktif
            $("#payment_contact_checkbox_flag").prop("checked", true);
            $(".payment_contact_checkbox").attr("data-flag", 1);
            $("#payment_contact_name").attr('readonly',true);
            $("#payment_contact_phone").attr('readonly',true);            
            $("#payment_contact_id").removeAttr('disabled');            
        } else {
            $("#payment_contact_checkbox_flag").prop("checked", false);
            $(".payment_contact_checkbox").attr("data-flag", 0);
            // $("#payment_contact_name").val('');            
            $("#payment_contact_name").attr('readonly',false);
            $("#payment_contact_phone").attr('readonly',false);                                    
            $("#payment_contact_id").attr('disabled',true);
            $("#payment_contact_id").val(0).trigger('change');      
        }
        var fl = $(".payment_contact_checkbox").attr('data-flag');   
    }
</script>