<!-- Additional Script -->
<script src="<?php echo base_url();?>assets/core/plugins/alasql.js" type="text/javascript"></script>   
<script src="<?php echo base_url();?>assets/core/plugins/html5-qrcode.min.js" type="text/javascript"></script>

<script>
    var identity            = "<?php echo $identity; ?>";
    var url                 = "<?= base_url('pos2'); ?>";
    var url_print           = "<?= base_url('pos2/prints'); ?>";
    var url_print_all       = "<?= base_url('pos2/report'); ?>";
    var url_trans           = "<?= base_url('transaksi/manage'); ?>";   
    var url_search          = "<?= base_url('search/manage'); ?>";
    var url_report          = "<?= base_url('report'); ?>";     
    var url_contact         = "<?= base_url('kontak/manage'); ?>";  
    var url_message         = "<?= base_url('message'); ?>";          
    var base_url            = "<?= site_url(); ?>";
    
    var product_image       = "<?= site_url('upload/noimage.png'); ?>";

    let contact_1_alias     = "<?php echo $contact_1_alias; ?>";
    let contact_2_alias     = "<?php echo $contact_2_alias; ?>";
    let ref_alias           = "<?php echo $ref_alias; ?>";     
    let order_alias         = "<?php echo $order_alias; ?>";
    let trans_alias         = "<?php echo $trans_alias; ?>";    
    let payment_alias       = "<?php echo $payment_alias; ?>";
    let dp_alias            = "<?php echo $dp_alias; ?>";
    var whatsapp_config     = "<?php echo $whatsapp_config; ?>";
    let contact_non_id      = "<?php echo $non_contact['contact_id']; ?>";

    $(document).ready(function () {
        // $("body").toggleMenu();
        // $("body").condensMenu();  
        // $("#horizontal-menu").css('margin-left','0!important');
        // $("#page-content").css("margin-left","0");
        // $('#sidebar .start .sub-menu').css('display','none');
        $("#search_product_tab").focus();

        // Start of Daterange
        // var start = moment().startOf('month');
        var start = moment().startOf('day');        
        var end   = moment();
        function set_daterangepicker_trans(start, end) {
            $("#filter_trans_date").attr('data-start',start.format('DD-MM-YYYY HH:mm'));
            $("#filter_trans_date").attr('data-end',end.format('DD-MM-YYYY HH:mm'));
            $('#filter_trans_date span').html(start.format('D-MMM-YYYY HH:mm') + '&nbsp;&nbsp;sd&nbsp;&nbsp;' + end.format('D-MMM-YYYY HH:mm'));
        }
        function set_daterangepicker_order(start, end) {
            $("#filter_order_date").attr('data-start',start.format('DD-MM-YYYY HH:mm'));
            $("#filter_order_date").attr('data-end',end.format('DD-MM-YYYY HH:mm'));
            $('#filter_order_date span').html(start.format('D-MMM-YYYY HH:mm') + '&nbsp;&nbsp;sd&nbsp;&nbsp;' + end.format('D-MMM-YYYY HH:mm'));
        }        
        $('#filter_order_date').daterangepicker({
            "startDate": start, //mm/dd/yyyy
            "endDate": end, ////mm/dd/yyyy
            "timePicker": true,
            "timePicker24Hour": true,            
            "showDropdowns": true,
            // "minYear": 2019,
            // "maxYear": 2020,
            "autoApply": false,
            "alwaysShowCalendars": true,
            "opens": "right",
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
                "daysOfWeek": ["Min","Sen","Sel","Rab","Kam","Jum","Sab"],
                "monthNames": ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
                "firstDay": 1
            }
        }, function(start, end, label) {
            // console.log(start.format('YYYY-MM-DD')+' to '+end.format('YYYY-MM-DD'));
            set_daterangepicker_order(start,end);
            // checkup_table.ajax.reload();
        });
        $('#filter_trans_date').daterangepicker({
            "startDate": start, //mm/dd/yyyy
            "endDate": end, ////mm/dd/yyyy
            "timePicker": true,
            "timePicker24Hour": true,            
            "showDropdowns": true,
            // "minYear": 2019,
            // "maxYear": 2020,
            "autoApply": false,
            "alwaysShowCalendars": true,
            "opens": "right",
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
                "daysOfWeek": ["Min","Sen","Sel","Rab","Kam","Jum","Sab"],
                "monthNames": ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
                "firstDay": 1
            }
        }, function(start, end, label) {
            // console.log(start.format('YYYY-MM-DD')+' to '+end.format('YYYY-MM-DD'));
            set_daterangepicker_trans(start,end);
            // checkup_table.ajax.reload();
        });
        
        $('#filter_order_date').on('apply.daterangepicker', function(ev, picker) {
            // console.log(ev+', '+picker);
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            order_table.ajax.reload();
        });        
        $('#filter_trans_date').on('apply.daterangepicker', function(ev, picker) {
            // console.log(ev+', '+picker);
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            trans_table.ajax.reload();
        });

        set_daterangepicker_order(start,end); 
        set_daterangepicker_trans(start,end);         
        // End of Daterange
                
        $("#trans_date").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        }).on('changeDate', function () {
        });
        $("#start, #end").datepicker({
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

        //Global Variable
        var transId    = 0;
        var transTotal = 0;
        var transItemTotal = 0;
        var transProductCount = 0;

        var roomId     = 0;
        var salesId    = 0;
        var trans       = [];
        var transItemsList = [];
        
        var paymentMethod = 0;
        var barcodeMode   = 0;

        const local        = window.localStorage;
        let productStorage = [];
        
        //AutoNumeric
        const autoNumericOption = {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalCharacterAlternative: '.',
            decimalPlaces: 0,
            watchExternalChanges: true      
        };
        new AutoNumeric('#payment_total_before', autoNumericOption);
        new AutoNumeric('#payment_total', autoNumericOption);
        new AutoNumeric('#payment_received', autoNumericOption);
        new AutoNumeric('#payment_change', autoNumericOption);     

		// Barcode scenner
        if(barcodeMode > 0){
            $("#search-produk-tab-detail").focus();
        }
		let scannerConfig = {
			fps: 60,
			qrbox: {
				width: 200,
				height: 200
			},
			rememberLastUsedCamera: true
            // formatsToSupport: {
            //     QR_CODE, AZTEC,
            //     CODABAR, CODE_39, CODE_93, CODE_128,
            //     DATA_MATRIX,
            //     MAXICODE,
            //     ITF,
            //     EAN_13, EAN_8,
            //     PDF_417, RSS_14, RSS_EXPANDED,
            //     UPC_A, UPC_E, UPC_EAN_EXTENSION
            // }
		};
		let scanner = new Html5QrcodeScanner(
			"scanner-div", scannerConfig
		);
        function scannerResult(decodedText, decodedResult) {
            // var encodeStr = btoa('String');
            // var decodeStr = atob('ewoicHJvZHVjdF9jb2RlIjoiQSIsCiJwcm9kdWN0X2lkIjoxLAoicHJvZHVjdF9uYW1lIjoiQW5la2EgR2V0dWsiLAoicHJvZHVjdF9ub3RlIjoiQiIsCiJwcm9kdWN0X3ByaWNlIjoxNzUwMDAsCiJwcm9kdWN0X3F0eSI6MSwKInByb2R1Y3RfdG90YWwiOjE3NTAwMCwKInByb2R1Y3RfdHlwZSI6IjEiLAoicHJvZHVjdF91bml0IjoiUGNzIgp9');            
            
            /* Method 1 (Use Base64) */
            // var decodeStr       = atob(decodedText);
            // var params          = JSON.parse(decodeStr);
            // var pid             = params.product_id;

            /* Method 2 (Not Used Base64) */
            // var decodeStr   = atob(decodedText);
            // var params      = JSON.parse(decodedText);
            // console.log(params);
            // var pid         = params.product_id;

            /* Method 3 (Select From LocalStorage by barcode ) */
            var products = JSON.parse(window.localStorage.getItem('products')) || [];
            var q = alasql('SELECT * FROM ? WHERE product_barcode = "'+decodedText+'"',[products]);
            var pid = q[0]['product_id'];
            var params = {
                'product_id':q[0]['product_id'],
                'product_code':q[0]['product_code'],
                'product_name':q[0]['product_name'],
                'product_unit':q[0]['product_unit'],
                'product_qty':1,
                'product_price':parseFloat(q[0]['product_price_sell']),
                'product_total':parseFloat(q[0]['product_price_sell'] * 1),
                'product_note':'',
                'product_type':q[0]['product_type'],
            }

            /* 
                var pp = {
                    'product_id':p.product_id,
                    'product_code':p.product_code,
                    'product_name':p.product_name,
                    'product_unit':p.product_unit,
                    'product_qty':parseInt(p.product_qty),
                    'product_price':parseFloat(p.product_price),
                    'product_total':parseFloat(p.product_price) * parseInt(p.product_qty),
                    'product_note':'',
                    'product_type':p.product_type,
                }
                var pps = JSON.parse(decodeStr);
            */

            var product_id = pid;
            if(parseInt(pid) > 0){
                scannerInput(params);
            }else{
                notif(0,'QR/Bar Code tidak terbaca');
            }
            // scanner.clear();
        }
        function scannerInput(params){
            var product_id      = params['product_id'];
            var product_code    = params['product_code'];
            var product_name    = params['product_name'];
            var product_unit    = params['product_unit'];
            var product_qty     = params['product_qty'];
            var product_price   = params['product_price'];
            var product_type    = params['product_type'];

            var indexs = transItemsList.findIndex(o => {
				return o.product_id === parseInt(product_id);
			});
            if(indexs > -1){ //Update when exist
                current_qty = transItemsList[indexs].product_qty;
                transItemsList[indexs].product_qty = parseInt(current_qty) + 1;
                transItemsList[indexs].product_total = parseFloat(parseInt(current_qty) + 1) * parseFloat(transItemsList[indexs].product_price); 
                notif(1,product_name);
            }else if(indexs == -1){ //Add when not exist
                var selected_data = {
                    'product_id':parseInt(product_id),
                    'product_code':product_code,
                    'product_name':product_name,
                    'product_unit':product_unit,
                    'product_qty':parseInt(product_qty),
                    'product_price':parseFloat(product_price),
                    'product_total':parseFloat(product_price) * parseInt(product_qty),
                    'product_note':'',
                    'product_type':product_type,
                };
                transItemsList.push(selected_data);
                notif(1,product_name);
            }                             
            loadTransItems(transItemsList);
        }   
        
        //Datatable Order Config
        let order_table = $("#table_order").DataTable({
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load_order';
                    d.tipe = identity;
                    // d.date_start = $("#start").val();
                    // d.date_end = $("#end").val();
                    d.date_start = $("#filter_order_date").attr('data-start');
                    d.date_end = $("#filter_order_date").attr('data-end');
                    d.filter_contact = $("#filter_trans_contact").find(':selected').val();
                    d.filter_type_paid = $("#filter_trans_type_paid").find(':selected').val();
                    d.length = $("#filter_trans_length").find(':selected').val();
                    d.search = {
                        value: $("#filter_trans_search").val()
                    };
                    // d.user_role =  $("#select_role").val();
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": "Tanggal", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Nomor "+order_alias, "searchable": true, "orderable": true},
                {"targets": 2, "title": contact_1_alias, "searchable": false, "orderable": true, "className": "text-left"},
                {"targets": 3, "title": contact_2_alias, "searchable": false, "orderable": true, "className": "text-left"},
                {"targets": 4, "title": ref_alias, "searchable": false, "orderable": true, "className": "text-left"},                                
                {"targets": 5, "title": "Total", "searchable": false, "orderable": true},
                {"targets": 6, "title": "Status", "searchable": false, "orderable": true},
                {"targets": 7, "title": "Action", "searchable": false, "orderable": false}
            ],
            "order": [
                [0, 'desc']
            ],
            "columns": [{
                    'data': 'order_date_format',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += data;
                        return dsp;
                    }
                }, {
                    'data': 'order_number',
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
                },{
                    'data': 'contact_name',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += data;
                        return dsp;
                    }
                },{
                    'data': 'employee_name',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += data;
                        return dsp;
                    }
                },{
                    'data': 'ref_name',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += data;
                        return dsp;
                    }
                },{
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
                    'data': 'order_flag', className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        if (parseInt(row.order_flag) == 1) {
                            dsp += '&nbsp;<label class="label label-success">Lunas</label>';
                        }else if (parseInt(row.order_flag) == 4) {
                            dsp += '&nbsp;<label class="label label-danger">Batal</label>';
                        }
                        return dsp;
                    }
                }, {
                    'data': 'order_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<button class="btn_print_order btn btn-mini btn-info" data-id="' + data + '" data-number="' + row.order_number + '" data-session="' + row.order_session + '">';
                        dsp += '<span class="fas fa-print"></span>';
                        dsp += '</button>';
                        
                        if((row.order_trans_id !== undefined) || (row.order_trans_id == null)){
                            dsp += '&nbsp;<button class="btn_print_payment btn btn-mini btn-success" data-id="' + row.order_trans_id + '" data-session="">';
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
        $("#table_order_filter").css('display', 'none');
        $("#table_order_length").css('display', 'none');  
        $("#filter_order_search").on('input', function (e) {
            var ln = $(this).val().length;
            if (parseInt(ln) > 3) {
                trans_table.ajax.reload();
            }
        });        
        $("#filter_order_type_paid, #filter_order_contact").on("change", function(e){
            trans_table.ajax.reload();
        });
        $("#filter_order_length").on('change', function (e) {
            var value = $(this).find(':selected').val();
            $('select[name="table_trans_length"]').val(value).trigger('change');
            trans_table.ajax.reload();
        });

        //Datatable Trans Config        
        let trans_table = $("#table_trans").DataTable({
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load_trans';
                    d.tipe = identity;
                    // d.date_start = $("#start").val();
                    // d.date_end = $("#end").val();
                    d.date_start = $("#filter_trans_date").attr('data-start');
                    d.date_end = $("#filter_trans_date").attr('data-end');
                    d.filter_contact = $("#filter_trans_contact").find(':selected').val();
                    d.filter_type_paid = $("#filter_trans_type_paid").find(':selected').val();
                    d.length = $("#filter_trans_length").find(':selected').val();
                    d.search = {
                        value: $("#filter_trans_search").val()
                    };
                    // d.user_role =  $("#select_role").val();
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": "Tanggal", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Nomor "+trans_alias, "searchable": true, "orderable": true},
                {"targets": 2, "title": contact_1_alias, "searchable": false, "orderable": true, "className": "text-left"},
                {"targets": 3, "title": "Total", "searchable": true, "orderable": true},
                {"targets": 4, "title": "Status", "searchable": true, "orderable": true},
                {"targets": 5, "title": "Action", "searchable": false, "orderable": false}
            ],
            "order": [
                [0, 'desc']
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
                        if(row.trans_contact_name == undefined){
                            dsp += '<label class="label label-inverse">'+ contact_1_alias +'</label>&nbsp;';
                            dsp += row.contact_name;
                        }else{
                            dsp += row.trans_contact_name;                                                                    
                        }
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
                                dsp += '<label class="label label-inverse" style="padding:2px 4px;">Gratis</label>';
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
                        dsp += '&nbsp;<button class="btn_print_payment btn btn-mini btn-success" data-id="' + row.trans_id + '" data-session="">';
                        dsp += '<span class="fas fa-print"></span>';
                        dsp += '</button>';

                        if(whatsapp_config == 1){
                            dsp += '&nbsp;<button class="btn btn_send_whatsapp btn-mini btn-primary"';
                            dsp += 'data-number="'+row.trans_number+'" data-id="'+data+'" data-total="'+row.trans_total+'" data-date="'+row.trans_date_format+'" data-contact-id="'+row.contact_id+'" data-contact-name="'+row.trans_contact_name+'" data-contact-phone="'+row.trans_contact_phone+'">';
                            dsp += '<span class="fab fa-whatsapp primary"></span></button>'
                        }
                        dsp += '&nbsp;<button class="btn_delete_payment btn btn-mini btn-danger" data-id="'+ data +'" data-number="'+row.trans_number+'">';
                        dsp += '<span class="fas fa-trash"></span> ';
                        dsp += '</button>';   
                        return dsp;
                    }
                }]
        }); 
        $("#table_trans_filter").css('display', 'none');
        $("#table_trans_length").css('display', 'none');  
        $("#filter_trans_length").on('change', function (e) {
            var value = $(this).find(':selected').val();
            $('select[name="table_trans_length"]').val(value).trigger('change');
            trans_table.ajax.reload();
        });
        $("#filter_trans_search").on('input', function (e) {
            var ln = $(this).val().length;
            if (parseInt(ln) > 3) {
                trans_table.ajax.reload();
            }
        });  
        $("#filter_trans_type_paid, #filter_trans_contact").on("change", function(e) {
            e.preventDefault();
            e.stopPropagation();
            trans_table.ajax.reload();
        });

        $('#trans_ref_id').select2({ /* Meja or Ruangan */ 
            readonly:true,
            placeholder: '<i class="fas fa-object-group"></i> '+ref_alias,
            ajax: {
                type: "get",
                url: url_search,
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
        $('#trans_sales_id').select2({ /* Waitress */
            placeholder: {
                id: '0',
                text: contact_2_alias
            },
            allowClear: true,
            ajax: {
                type: "get",
                url: url_search,
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
        $('#trans_contact_id, #payment_contact_id, #filter_order_contact, #filter_trans_contact').select2({
            placeholder: {
                id: '0',
                text: 'Semua '+contact_1_alias+' / Non'
            },
            allowClear: true,
            ajax: {
                type: "get",
                url: url_search,
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
        $("#trans_contact_id").on("change", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var this_val = $(this).find(':selected').val();
            if (this_val == '-') {
                setTimeout(() => {
                    // $("#modal-payment-form").modal({backdrop: 'static', keyboard: false});   
                    $("#modal-contact").modal('show');                       
                }, 200);
                formKontakNew();
            }
        });
        
        // Navigation
        $(document).on("click",".btn_show_trans",function(e) {
            e.preventDefault();
            e.stopPropagation();
            activeTab("tab3");
        });
        $(document).on("click",".btn_back_trans",function(e) {
            e.preventDefault();
            e.stopPropagation();
            activeTab("tab1");
        });    
        $(document).on("click",".btn_back_order",function(e) {
            e.preventDefault();
            e.stopPropagation();
            activeTab("tab0");
        });                
        $(document).on("click",".btn_show_order",function(e) {
            e.preventDefault();
            e.stopPropagation();
            activeTab("tab1");
        });
        $(document).on("click",".btn_menu_toggle", function (e) {
            var i = $(this).attr('data-id');
            if(i == 1){
                activeTab('tab2');  
            }else if(i == 2){
                activeTab('tab3');  
            }else if(i == 3){
                activeTab('tab3');  
            }else if(i == 4){
                activeTab('tab4');  
            }
            // else if(i == 3){
            //     loadPaymentItem();
            // }else if(i == 4){
            //     var url_redirect = "<?= base_url('login/logout'); ?>";
            //     $.redirect(url_redirect,[],"POST","_self"); 
            // }           
        }); 
        $(document).on("click",".btn_cart",function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(".div_btn_cart").hide();
            $(".div_btn_cart_return").show();
            $("#product-search").hide(300);
            $("#product-tab").hide(300);
        });
        $(document).on("click",".btn_cart_return",function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(".div_btn_cart_return").hide(); 
            $(".div_btn_cart").show();
            $("#product-search").show(300);
            $("#product-tab").show(300);
        });

        // Trans
        $(document).on("click",".btn_new_trans",function(e) {
            e.preventDefault();
            e.stopPropagation();
            formTransReset();            
            activeTab("tab0");
        });        
        $(document).on("click","#btn_save_trans",function(e) {
            e.preventDefault();
            e.stopPropagation();
            var next = true;
            var trans_is_member       = parseInt($(".trans_contact_checkbox").attr('data-flag'));
            var trans_contact_id      = $("#trans_contact_id").find(':selected').val();
            var trans_contact_name    = $("#trans_contact_name").val();   
            var trans_contact_phone   = $("#trans_contact_phone").val();

            // var trans_ref_id             = $("#trans_ref_id").find(':selected').val();
            // var trans_sales_id        = $("#trans_sales_id").find(':selected').val();
            var trans_ref_id          = 0;
            var trans_sales_id        = 0;                           
            var trans_date            = $("#trans_date").val();                                    
            var trans_item_count      = transItemsList.length;

            if(parseInt(trans_item_count) < 1){
                notif(0,order_alias+' Detail masih kosong');
                return false;
            }

            if(next){
                if(trans_is_member == 1){ //Member
                    if((trans_contact_id == 0) || (trans_contact_id < 1) || (trans_contact_id == undefined) || (trans_contact_id == 'undefined')){
                        next=false;
                        notif(0,contact_1_alias+' wajib dipilih');
                    }
                }else{ //Non Member
                    if(parseInt(trans_contact_name.length) < 2){
                        next=false;
                        notif(0,'Non '+contact_1_alias+' wajib tulis Nama');
                    }
                }
            }

            if(next){
                if(trans_is_member == 0){
                    trans_contact_id = 0;
                }
                var trans_data = {
                    'contact_is_register':trans_is_member,
                    'contact_id':trans_contact_id,
                    'contact_name':trans_contact_name,
                    'contact_phone':trans_contact_phone,
                    // 'trans_ref_id':trans_ref_id,
                    // 'trans_sales_id':trans_sales_id,
                    'trans_date':trans_date,
                    'trans_total_price':transItemTotal,
                    'trans_total_product':transProductCount,
                    'room_id':$("#trans_ref_id").find(':selected').val(),
                    'sales_id':$("#trans_sales_id").find(':selected').val()
                }
                trans = trans_data;
                loadTrans(trans);
                loadTransItems(transItemsList);
                checkBoxPaymentNonMember(trans_is_member);                
                loadPayment(trans,transItemsList);
                activeTab('tab2');
                notif('Metode Pembayaran');
            }
        });
        $(document).on("click","#btn_reset_trans",function(e) {
            e.preventDefault();
            e.stopPropagation();
            let title   = 'Konfirmasi';
            let content = 'Detail transaksi akan di kosongkan';
            $.confirm({
                title: title,
                content: content,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
                autoClose: 'button_2|30000',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                buttons: {
                    button_1: {
                        text:'<i class="fas fa-check white"></i> Ok',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            formTransReset();
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-times white"></i> Batal',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }
                }
            });
        });

        // DOM Trans
        $(document).on("click",".btn_save_trans_item",function(e) { // DOM Add 
            e.preventDefault();
            e.stopPropagation();
            var product_id = $(this).attr('data-product-id');
            var product_code = $(this).attr('data-product-code');
            var product_name = $(this).attr('data-product-name');                        
            var product_unit = $(this).attr('data-product-unit');
            var product_qty = $(this).attr('data-product-qty');
            var product_price = $(this).attr('data-product-price');  
            var product_type = $(this).attr('data-product-type');                

            if(parseFloat(product_price) > 0){
                var indexs = transItemsList.findIndex(o => {
                    return o.product_id === parseInt(product_id);
                });
                if(indexs > -1){ //Update when exist
                    current_qty = transItemsList[indexs].product_qty;
                    transItemsList[indexs].product_qty = parseInt(current_qty) + 1;
                    transItemsList[indexs].product_total = parseFloat(parseInt(current_qty) + 1) * parseFloat(transItemsList[indexs].product_price); 
                    cartAnimation();
                    notif(1,product_name);
                }else if(indexs == -1){ //Add when not exist
                    var selected_data = {
                        'product_id':parseInt(product_id),
                        'product_code':product_code,
                        'product_name':product_name,
                        'product_unit':product_unit,
                        'product_qty':parseInt(product_qty),
                        'product_price':parseFloat(product_price),
                        'product_total':parseFloat(product_price) * parseInt(product_qty),
                        'product_note':'',
                        'product_type':product_type,
                    };
                    transItemsList.push(selected_data);
                    cartAnimation();
                    notif(1,product_name);
                }                             
                loadTransItems(transItemsList);
            }else{
                makeConfirm(0,'Harga Jual <b>'+product_name+'</b> tidak ditemukan');
            }
        });
        $(document).on("click",".btn_save_trans_item_note",function(e) { // Dom Update-Note 
            var pid = parseInt($(this).attr('data-product-id'));
            var pnm = $(this).attr('data-product-name'); 
            var pnn = $(this).attr('data-product-note');

            if(pnn == undefined){
                pnn = '';
            }

            let title   = 'Catatan '+pnm;
            $.confirm({
                title: title,
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
                        dsp += '        <input id="jc_input" name="jc_input" class="form-control" value="'+pnm+'" readonly>';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Catatan</label>';
                        dsp += '        <input id="jc_input_note" name="jc_input_note" class="form-control" value="'+pnn+'">';
                        dsp += '    </div>';
                        dsp += '</div>';
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);
                    $("#jc_input_note").focus();
                },
                buttons: {
                    button_1: {
                        text:'<i class="fas fa-check white"></i> Ok',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){
                            let self      = this;
            
                            let input     = self.$content.find('#jc_input_note').val();
                            if(!input){
                                $.alert('Catatan mohon diisi dahulu');
                                return false;
                            } else{
                                var indexs = transItemsList.findIndex(o => {
                                    return o.product_id === pid;
                                });
                                transItemsList[indexs].product_note = input;
                                loadTransItems(transItemsList);
                            }
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-check white"></i> Tutup',
                        btnClass: 'btn-success',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }
                }
            });

        }); 
        $(document).on("click",".btn_save_trans_item_plus_minus",function(e) { // Dom Update [+] or [-]
			var id = parseInt($(this).attr('data-product-id'));
			var opr = $(this).attr('data-operator');
            var indexs = transItemsList.findIndex(o => {
				return o.product_id === id;
			});
            current_qty = transItemsList[indexs].product_qty;
            if(opr == 'increase'){
                transItemsList[indexs].product_qty = parseInt(current_qty) + 1;
                transItemsList[indexs].product_total = parseFloat(parseInt(current_qty) + 1) * parseFloat(transItemsList[indexs].product_price);                
            }else if(opr == 'decrease'){
                if((parseInt(transItemsList[indexs].product_qty) - 1) > 0){
                    transItemsList[indexs].product_qty = parseInt(current_qty) - 1;
                    transItemsList[indexs].product_total = parseFloat(parseInt(current_qty) - 1) * parseFloat(transItemsList[indexs].product_price);
                }else{
                    // console.log('Habis');
                }
            }
            loadTransItems(transItemsList);
        });         
        $(document).on("click",".btn_delete_trans_item",function(e) { // DOM Remove 
			var id = parseInt($(this).attr('data-product-id'));
			var index = transItemsList.findIndex(o => {
				return o.product_id === id;
			});
			if (index !== -1){
				transItemsList.splice(index, 1);
				loadTransItems(transItemsList);
			}
        }); 
        $(document).on("click",".btn_delete_trans_item_note",function(e) { // Dom Remove-Note
			var id = parseInt($(this).attr('data-product-id'));
			var i = transItemsList.findIndex(o => {
				return o.product_id === id;
			});
            transItemsList[i].product_note = '';
			loadTransItems(transItemsList);
        });
        
        // Payment
        $(document).on("click","#btn_save_payment",function(e) {
            // console.log('');
            e.preventDefault();
            e.stopPropagation();
            globalVariableCheck();
            var next = true;
            var paid_total      = $("#payment_total").val();
            var paid_received   = $("#payment_received").val();
            var paid_change     = $("#payment_change").val();   

            var payment_is_member       = parseInt($(".payment_contact_checkbox").attr('data-flag'));
            var payment_contact_id      = $("#payment_contact_id").find(':selected').val();
            var payment_contact_name    = $("#payment_contact_name").val();   
            var payment_contact_phone   = $("#payment_contact_phone").val();

            var trans_ref_id          = $("#trans_ref_id").find(':selected').val();
            var trans_sales_id        = $("#trans_sales_id").find(':selected').val();
            // var trans_ref_id          = 0;
            // var trans_sales_id        = 0;                           
            var trans_date            = $("#trans_date").val();                                    
            var trans_item_count      = transItemsList.length;

            if(parseInt(trans_item_count) < 1){
                notif(0,order_alias+' Detail masih kosong');
                next = false;
                return false;
            }

            if(next){ //Checking Customer
                if(payment_is_member == 1){ //Member
                    if((payment_contact_id == 0) || (payment_contact_id < 1) || (payment_contact_id == undefined) || (payment_contact_id == 'undefined')){
                        next=false;
                        notif(0,contact_1_alias+' wajib dipilih');
                    }
                }else{ //Non Member
                    if(parseInt(payment_contact_name.length) < 2){
                        next=false;
                        notif(0,'Non '+contact_1_alias+' wajib tulis Nama');
                    }
                }
            }    

            if(next){
                if(parseInt(paymentMethod) < 1){
                    notif(0,'Pilih metode pembayarannya');
                    next = false;
                }
            }

            if(next){ //Total Received Check
                if (parseFloat(removeCommas(paid_received)) > parseFloat(removeCommas(paid_total))) {
                    // console.log('dibayar > total');
                } else if (parseFloat(removeCommas(paid_received)) == parseFloat(removeCommas(paid_total))) {
                    // console.log('dibayar > total');
                } else {
                    notif(0, 'Jumlah kurang besar');
                    next = false;
                }
            }
                        
            if(next){
                    // voucher_code
                    // btn_voucher_search
                    // voucher_nominal 
                var payment_data ={
                    payment_contact_checkbox: $(".payment_contact_checbox").attr('data-flag'),
                    payment_non_contact_id: contact_non_id,
                    payment_contact_name: $("#payment_contact_name").val(),
                    payment_contact_phone: $("#payment_contact_phone").val(),
                    payment_contact_id: payment_contact_id,
                    payment_total_before: transTotal,
                    payment_total: transTotal,
                    payment_total_received: parseFloat(removeCommas(paid_received)),
                    payment_total_change: parseFloat(removeCommas(paid_change)),
                    trans_item_list: transItemsList,
                    ref_id:trans_ref_id,
                    sales_id:trans_sales_id,
                    account_cash: $("#cash_account").find(':selected').val(),
                    account_transfer:$("#transfer_account").find(':selected').val(),
                    account_edc:$("#edc_account").find(':selected').val(),
                    account_free:$("#free_account").find(':selected').val(),
                    account_qris:$("#qris_account").find(':selected').val(),
                    payment_method:paymentMethod,
                    info_bank_ref:$("#transfer_number").val(),
                    info_bank_account_name:$("#transfer_name").val(),
                    info_card_note:$("#edc_note").val(),
                    info_card_number:$("#edc_card_number").val(),
                    info_card_account_name:$("#edc_name").val(),
                    info_card_year:$("#edc_year").val(),
                    info_card_month:$("#edc_month").val(),                    
                    info_card_type:$("#edc_card_type").find(':selected').val(),
                }
                var data = {
                    action: 'payment_create',
                    data: JSON.stringify(payment_data)
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data, 
                    dataType: 'json', cache: 'false', 
                    beforeSend:function(){},
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            formTransReset();
                            formPaymentReset();
                            globalVariableReset();
                            trans_table.ajax.reload();
                            var p = {
                                trans_id:d.result.trans_id,
                                trans_number:d.result.trans_number,
                                trans_date:d.result.trans_date,
                                trans_session:d.result.trans_session,
                                trans_total:d.result.trans_total,  
                                trans_total_received:d.result.trans_total_received,
                                trans_total_change:d.result.trans_total_change,
                                contact_id:d.result.contact_id,
                                contact_name:d.result.contact_name,
                                contact_phone:d.result.contact_phone,
                                paid_type_id:d.result.paid_type_id,
                                paid_type_name:d.result.paid_type_name
                            }
                            paymentSuccess(p);
                            activeTab("tab0");
                        }else{
                            notif(s,m);
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                    }
                });
            }        
        });
        $(document).on("click",".btn_print_payment",function(e) {
            var trans_id = $(this).attr("data-id");
            var trans_session = $(this).attr("data-session");
            var trans_receipt = {
                trans_id:trans_id,
                trans_session:trans_session
            };
            printReceipt(trans_receipt);
        });
        $(document).on("click",".btn_print_all_payment",function(e) {
            e.preventDefault();
            e.stopPropagation();
            let request = $(this).attr('data-request');
            let format = $(this).attr('data-format');
            let alias1 = trans_alias;
            let title   = 'Print Data '+alias1;
            $.confirm({
                title: title,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
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
                        dsp += '    <label class="form-label">'+contact_1_alias+'</label>';
                        dsp += '        <select id="filter_contact2" name="filter_type2" class="form-control">';
                        dsp += '            <option value="0" selected>Semua '+contact_1_alias+'</option>';                                                                                                                                         
                        dsp += '        </select>';
                        dsp += '    </div>';
                        dsp += '</div>';  
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Metode Bayar</label>';
                        dsp += '        <select id="filter_type_paid2" name="filter_type_paid2" class="form-control">';
                        dsp += '            <option value="0">Semua</option>';
                        dsp += '            <option value="1">Cash</option>';
                        dsp += '            <option value="2">Transfer</option>';
                        dsp += '            <option value="3">EDC</option>';
                        dsp += '            <option value="4">Gratis</option>';
                        dsp += '            <option value="5">QRIS</option>';                                                                                                                        
                        dsp += '        </select>';
                        dsp += '    </div>';
                        dsp += '</div>';    
                        dsp += '<div class="hide col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Produk</label>';
                        dsp += '        <select id="filter_product2" name="filter_product2" class="form-control">';
                        dsp += '            <option value="0">Semua</option>';                                                                                                                                         
                        dsp += '        </select>';
                        dsp += '    </div>';
                        dsp += '</div>';                             
                        dsp += '<div class="hide col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-left">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">'+ref_alias+'</label>';
                            dsp += '        <select id="filter_ref2" name="filter_ref2" class="form-control">';
                            dsp += '            <option value="0">Semua</option>';                                             
                            dsp += '        </select>';
                            dsp += '    </div>';
                            dsp += '</div>';
                            dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-right">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">'+contact_2_alias+'</label>';
                            dsp += '        <select id="filter_sales2" name="filter_sales2" class="form-control">';
                            dsp += '            <option value="0">Semua</option>';                                             
                            dsp += '        </select>';
                            dsp += '    </div>';
                            dsp += '</div>';                                                         
                        dsp += '</div>';                                                                        
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-left">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Urut Berdasarkan</label>';
                            dsp += '        <select id="filter_order2" name="filter_order2" class="form-control">';
                            dsp += '            <option value="1">Nama '+contact_1_alias+'</option>';
                            dsp += '            <option value="2">Kode '+contact_1_alias+'</option>';
                            dsp += '            <option value="3">Kategori '+contact_1_alias+'</option>';
                            dsp += '            <option value="4">Harga Beli</option>';
                            dsp += '            <option value="5">Harga Jual</option>';
                            dsp += '            <option value="6">Stok</option>';                                                                                                                        
                            dsp += '        </select>';
                            dsp += '    </div>';
                            dsp += '</div>';
                            dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-right">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Sort</label>';
                            dsp += '        <select id="filter_dir2" name="filter_dir2" class="form-control">';
                            dsp += '            <option value="0">Urut Naik</option>';
                            dsp += '            <option value="1">Urut Menurun</option>';
                            dsp += '        </select>';
                            dsp += '    </div>';
                            dsp += '</div>';        
                        dsp += '</div>';                
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);

                    $('#filter_contact2').select2({
                        placeholder: {
                            id: '0',
                            text: 'Semua'
                        },                             
                        dropdownParent:$(".jconfirm-box-container"),
                        allowClear: true,                     
                        ajax: {
                            type: "get",
                            url: url_search,
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                var query = {
                                    search: params.term,
                                    tipe: 2,
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
                        templateSelection: function (datas) {
                            if (!datas.id) {
                                return datas.text;
                            }
                            if (parseInt(datas.id) > 0) {
                                return datas.text;
                            }
                        },
                        templateResult: function (d) {
                            if (d.id > 0) {
                                return d.text;
                            }
                        }
                    });
                    /*
                    $('#filter_product2').select2({
                        placeholder: {
                            id: '0',
                            text: 'Semua'
                        },                             
                        dropdownParent:$(".jconfirm-box-container"),
                        allowClear: true,                     
                        ajax: {
                            type: "get",
                            url: url_search,
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                var query = {
                                    search: params.term,
                                    tipe:2,
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
                        templateSelection: function (datas) {
                            if (!datas.id) {
                                return datas.text;
                            }
                            if (parseInt(datas.id) > 0) {
                                return datas.text;
                            }
                        },
                        templateResult: function (d) {
                            if (d.id > 0) {
                                return d.text;
                            }
                        }
                    });
                    $('#filter_ref2').select2({
                        placeholder: {
                            id: '0',
                            text: 'Semua'
                        },                             
                        dropdownParent:$(".jconfirm-box-container"),
                        allowClear: true,                     
                        ajax: {
                            type: "get",
                            url: url_search,
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                var query = {
                                    search: params.term,
                                    tipe:7,
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
                        templateSelection: function (datas) {
                            if (!datas.id) {
                                return datas.text;
                            }
                            if (parseInt(datas.id) > 0) {
                                return datas.text;
                            }
                        },
                        templateResult: function (d) {
                            if (d.id > 0) {
                                return d.text;
                            }
                        }
                    }); 
                    $('#filter_sales2').select2({
                        placeholder: {
                            id: '0',
                            text: 'Semua'
                        },                             
                        dropdownParent:$(".jconfirm-box-container"),
                        allowClear: true,                     
                        ajax: {
                            type: "get",
                            url: url_search,
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                var query = {
                                    search: params.term,
                                    tipe:3,
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
                        templateSelection: function (datas) {
                            if (!datas.id) {
                                return datas.text;
                            }
                            if (parseInt(datas.id) > 0) {
                                return datas.text;
                            }
                        },
                        templateResult: function (d) {
                            if (d.id > 0) {
                                return d.text;
                            }
                        }
                    });
                    */                                                                   
                },
                buttons: {
                    button_1: {
                        text:'Print',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){
                            let self      = this;
                            let filter_order    = self.$content.find('#filter_order2').val();
                            let filter_dir      = self.$content.find('#filter_dir2').val(); 
                            let filter_contact  = self.$content.find('#filter_contact2').find(':selected').val(); 
                            let filter_product  = self.$content.find('#filter_product2').find(':selected').val(); 
                            let filter_ref      = self.$content.find('#filter_ref2').find(':selected').val(); 
                            let filter_sales    = self.$content.find('#filter_sales2').find(':selected').val();                                                                                  
                            let filter_type     = self.$content.find('#filter_type_paid2').find(':selected').val();                                                                                                              
                            
                            if(filter_order == 0){
                                $.alert('Urut mohon dipilih dahulu');
                                return false;
                            } else{
                                // var filter_ref = $("#filter_ref").find(':selected').val();
                                // var filter_type = $("#filter_type").find(':selected').val();
                                // var filter_contact = $("#filter_contact").find(':selected').val();
                                // var filter_city = $("#filter_city").find(':selected').val();
                                // var filter_flag = $("#filter_flag").find(':selected').val();

                                // var filter_order    = 0;
                                // var filter_dir      = 0;
                                var p = url_print_all + '?request=' + request;
                                    p += '&format='+format;
                                    p += '&start_date='+$("#start").val();
                                    p += '&end_date='+$("#end").val();                                    
                                    p += '&contact='+filter_contact;
                                    p += '&product='+filter_product;
                                    p += '&type_paid=' + filter_type;
                                    p += '&ref='+filter_ref;
                                    p += '&sales='+filter_sales;                                    
                                    p += '&start=0&limit=0'; 
                                    p += '&order=' + filter_order + '&dir=' + filter_dir;

                                if(format == 'html'){
                                    var win = window.open(p,'_blank').print();
                                }else{
                                    $.ajax({
                                        type: "get",url: p,data: {action: 'print_raw'},dataType: 'json',cache: 'false',
                                        beforeSend: function () { notif(1, 'Perintah print dikirim'); },
                                        success: function (d) {
                                            var s = d.status; var m = d.message;
                                            if (parseInt(s) == 1) {
                                                if(parseInt(d.print_to) == 0){
                                                    window.open(d.print_url).print();
                                                }else{                            
                                                    return printFromUrl(d.print_url);                              
                                                }
                                            } else { notif(s, m); }
                                        }, error: function (xhr, Status, err) { notif(0, 'Error'); }
                                    });
                                }
                            }
                        }
                    },
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
        });
        $(document).on("click",".btn_delete_payment", function (e) {
            e.preventDefault();
            var t_id = $(this).attr('data-id');
            var number = $(this).attr("data-number");      
            var content = 'Apakah anda ingin menghapus <b>' + number + '</b> ?';
            $.confirm({
                title: 'Hapus!',
                content: content,
                buttons: {
                    confirm: {
                        btnClass: 'btn-default',
                        text: 'Hapus',
                        action: function () {
                            var data = {
                                action: 'payment_delete',
                                trans_id: t_id,
                                trans_idnumber: number
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        trans_table.ajax.reload(null,false);
                                    } else {
                                        notif(0, d.message);
                                    }
                                }
                            });
                        }
                    },
                    cancel: {
                        btnClass: 'btn-danger',
                        text: 'Tutup',
                        action: function () {
                            // $.alert('Canceled!');
                        }
                    }
                }
            });
        });

        /* Print */
        $(document).on("click", ".btn_print_order", function (e) { // Print Button
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
        $(document).on("click", ".btn_print_payment", function () {
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
        $(document).on("click",".btn_print_all_order",function() {
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
            + $("#filter_order_date").attr('data-start') + '/'
            + $("#filter_order_date").attr('data-end') + '/' 
            + contact + "?product="+product+"&format="+format+"&order="+order+"&dir="+dir;    
            window.open(print_url,'_blank');
            // var request = $('.btn-print-all').data('request');
            // var print_url = url_print +'/'+ request + '/'+ $("#start").val() +'/'+ $("#end").val();
            // var win = window.open(print_url,'Print','width=700,height=485,left=' + x + ',top=' + y + '').print();   
        });
        $(document).on("click",".btn_print_all_trans",function() {
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
            + $("#filter_trans_date").attr('data-start') + '/'
            + $("#filter_trans_date").attr('data-end') + '/' 
            + contact + "?product="+product+"&format="+format+"&order="+order+"&dir="+dir+"&type_paid="+type_paid;    
            window.open(print_url,'_blank');
            // var request = $('.btn-print-all').data('request');
            // var print_url = url_print +'/'+ request + '/'+ $("#start").val() +'/'+ $("#end").val();
            // var win = window.open(print_url,'Print','width=700,height=485,left=' + x + ',top=' + y + '').print();   
        });

        // Other
        $(document).on("click","#trans_contact_name", function (e) {
            e.preventDefault(); e.stopPropagation();
            var s = $("#trans_contact_name").val();
            modalContactName(s);
        }); 
        $(document).on("click","#trans_contact_phone", function (e) {
            e.preventDefault(); e.stopPropagation();
            var s = $("#trans_contact_phone").val();
            modalContactPhone(s);
        });                
        $(document).on("click",".trans_contact_checkbox", function (e) {
            var check = $(".trans_contact_checkbox").attr('data-flag');
            if (check == 0) {
                checkBoxTransNonMember(0);
            } else {
                checkBoxTransNonMember(1);
            }
        }); 
        $(document).on("click",".payment_contact_checkbox", function (e) {
            var check = $(".payment_contact_checkbox").attr('data-flag');
            if (check == 0) {
                checkBoxPaymentNonMember(0);
            } else {
                checkBoxPaymentNonMember(1);
            }
        });
        $(document).on("click",".barcode_checkbox", function (e) {
            var check = $(".barcode_checkbox").attr('data-flag');
            if (check == 0) {
                checkBoxBarcode(0);
            } else {
                checkBoxBarcode(1);
                $("#search_product_tab").focus();
            }
        });                 
        $(document).on("click",".btn_product_tab", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var category_id = $(this).attr('data-id');
            var search = $("#search_product_tab").val();
            loadProductTabDetail({category_id:category_id,search:search});
        });
        $(document).on("click",".btn_payment_method",function(e) { //Method Payment Click DIV
            e.preventDefault();
            e.stopPropagation();
            var payment_method_id = $(this).attr('data-id');
            $(this).parent().find('.active').removeClass('active');
            $(this).addClass('active');
            paymentMethod = payment_method_id;
            //Reset DOM Total
            // modal_down_payment  = 0;
            // modal_total_dibayar = 0;

            $("#payment_choice_cash").hide(300);
            $("#payment_choice_transfer").hide(300);
            $("#payment_choice_edc").hide(300);
            $("#payment_choice_gratis").hide(300);
            $("#payment_choice_qris").hide(300);
            $("#payment_choice_down_payment").hide(300);
            // loadDownPayment({action:0}); //Reset Form Down Payment

            if (parseInt(paymentMethod) == 1) {
                $("#payment_choice_cash").show(300);
                $("#cash_account").removeAttr('disabled').attr('readonly', false);
            } else if (parseInt(paymentMethod) == 2) {
                $("#payment_choice_transfer").show(300);
                $("#transfer_account").removeAttr('disabled').attr('readonly', false);
                $("#transfer_number").attr('readonly', false);
                $("#transfer_name").attr('readonly', false);
            } else if (parseInt(paymentMethod) == 3) {
                $("#payment_choice_edc").show(300);
                $("#edc_account").removeAttr('disabled').attr('readonly', false);                
                $("#edc_card_type").removeAttr('disabled').attr('readonly', false);
                $("#edc_year").attr('readonly', false);
                $("#edc_month").attr('readonly', false);
                $("#edc_card_number").attr('readonly', false);
                $("#edc_note").attr('readonly', false);
                // $("#edc_bank_penerbit").removeAttr('disabled').attr('readonly', false);
                $("#edc_name").attr('readonly', false);
            }else if(parseInt(paymentMethod) == 4){
                // $("#payment_choice_gratis").show(300);
                // $("#modal_akun_gratis").removeAttr('disabled').attr('readonly', false);                
            }else if(parseInt(paymentMethod) == 5){
                $("#payment_choice_qris").show(300);
                $("#qris_account").removeAttr('disabled').attr('readonly', false);                
            }else if(parseInt(paymentMethod) == 8){
                $("#payment_choice_down_payment").show(300);
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
            console.log(paymentMethod);
            //Reset Modal Down Payment
            // modal_down_payment = 0; 
            // var s = removeCommas($("#payment_total_before").val());
            // modalNumberChoice(s);                       
        });
        $(document).on("click","#payment_received", function (e) {
            e.preventDefault(); e.stopPropagation();
            var s = removeCommas($("#payment_total_before").val());
            modalNumberChoice(s);
        });
        $(document).on("input","#payment_received", function (e) {
            e.preventDefault(); e.stopPropagation();
            var payment_total = transTotal;
            var payment_total_received = removeCommas($(this).val());
            var payment_total_change = parseFloat(payment_total_received) - parseFloat(payment_total);
            $("#payment_change").val(payment_total_change);
            console.log('Total:'+transTotal+', Bayar:'+payment_total_received+', Kembali:'+payment_total_change);
        });    
        $(document).on("click","#btn_save_contact", function (e) {
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
                            $("#modal-contact").modal('hide');
                            $("#trans_contact_id").val(0).trigger('change');
                            setTimeout(() => {
                                $("select[id='trans_contact_id']").append(''+'<option value="'+d.result.id+'">'+d.result.nama+' - '+d.result.telepon_1+'</option>');
                                $("select[id='trans_contact_id']").val(d.result.id).trigger('change');
                            },200);
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
        $(document).on("click",".btn_send_whatsapp", function (e) {
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
                    contact_phone: $(this).attr('data-contact-phone'),
                    // contact_emmail: $(this).attr('data-contact-email')
                }
                formSendReceipt(params);
            } else {
                notif(0, 'Data tidak ditemukan');
            }
        });
        $(document).on("click",".btn_room_click",function(e) {
            e.preventDefault();
            e.stopPropagation();
            var data_id = $(this).attr('data-id');
            var data_is_use = $(this).attr('data-use-type');   
            var ref_id          = $(this).attr('data-ref-id');
            var ref_name        = $(this).attr('data-ref-name');
            var ref_group_name  = $(this).attr('data-ref-group-name');

            if(data_is_use == 1){
                var order_id        = $(this).attr('data-order-id');
                var order_number    = $(this).attr('data-order-number');
                var grand_total     = $(this).attr('data-order-total');
            }

            if(parseInt(data_id) > 0){
                if(parseInt(data_is_use) > 0){ 
                    //Room CheckIn
                    notif(1,'Loading '+ref_name+' - '+ref_group_name);
                    var params = {
                        order_id: order_id,
                        order_number: order_number,
                        ref_id:ref_id,
                        ref_name:ref_name,
                        grand_total:grand_total
                    };
                    console.log(params);
                    // loadOrderDetail(params['order_id']);
                }else{ 
                    //Room Available
                    notif(1,ref_name+' - '+ref_group_name);
                    // loadOrderItem({ref_id:data_id,contact_id:0});

                    // formOrder({action:1,ref_name:ref_name});
                    $("#trans_ref_id").append('<option value="'+ref_id+'">'+ref_name+'</option>');
                    $("#trans_ref_id").val(ref_id).trigger('change');
                    $("#trans_ref_label").html(ref_name+'<br>'+ref_group_name);
                    activeTab("tab1");
                }
            }            
        });
        $(document).on("keyup", "#search_product_tab", function(e){
            var category_id = 0;
            var search = $(this).val();
            var ln = $(this).val().length;

            // let timer;              // Timer identifier
            // const waitTime = 1000;   // Wait time in milliseconds 

            // if (parseInt(ln) > 2) {
                // timer = setTimeout(() => {
                    loadProductTabDetail({category_id:category_id,search:search});     
                // }, waitTime);                
            // }
        });
        $(document).on("click",".barcode_label",function(e) {
            e.preventDefault();
            e.stopPropagation();
            $("#modal-scanner").modal('show');
            return scanner.render(scannerResult);  
        });

        function loadRoom(params) { /* load-reference */
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
                action: 'load_ref',
                data: JSON.stringify(prepare)
            };
            $.ajax({
                type: "post",
                url: url,
                data: data,
                dataType: 'json',
                cache: 'false',
                beforeSend: function(){
                    $("#div_room").html('<b style="color:var(--form-font-color);">Loading...</b>');
                },
                success: function (d){
                    if (parseInt(d.status) == 1) {
                        if (parseInt(d.total_records) > 0) {
                            $("#div_room").html('');
                            var dsp = '';
                            var total_records = parseInt(d.total_records);
                            let r = d.result_group;

                            if(parseInt(total_records) > 0){
                                $("#div_room").html('');
                            
                                //Looping Group Header
                                for(let a=0; a < total_records; a++) {
                                    let group = r[a]['index'];
                                    let group_name = r[a]['name'];
                                    let group_data = r[a]['data'];
                                    console.log(a);
                                    //Create Header of Group
                                    dsp += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">';
                                        dsp += '<div class="col-lg-12 col-md-12 col-sm-12 padding-remove-side"><h5 style=""><b>'+group_name+'</b></h5>';
                                        dsp += '</div>';
                                        dsp += '<div class="col-lg-12 col-md-12 col-sm-12 padding-remove-side prs-5">';
                                            //Looping Data of Group Header
                                            for(let b=0; b < group_data.length; b++) {
                                                // console.log(a+' => '+b)
                                                let value = group_data[b];
                                                // dsp += '<div>';
                                                    // dsp += '<td>'+value['COL_1']+'</td>';
                                                        // var order_id = d.result[b]['order_id'];
                                                        var ref_id = group_data[b]['ref_id'];
                                                        var ref_name = group_data[b]['ref_name'];
                                                        var ref_icon = group_data[b]['ref_icon'];
                                                        var ref_use = parseInt(group_data[b]['ref_use_type']);                                
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
                                                        // set_attr = ' style="'+background_color+'"';
                                                        set_attr = 'data-id="' + ref_id + '" style="'+background_color+'" data-flag="'+group_data[b]['ref_flag']+'" data-use-type="'+group_data[b]['ref_use_type']+'"';
                                                        if(parseInt(ref_use) == 1){
                                                            set_attr += ' data-order-id="'+group_data[b]['order']['order_id']+'"';
                                                            set_attr += ' data-order-number="'+group_data[b]['order']['order_number']+'"';
                                                            set_attr += ' data-order-total="'+group_data[b]['order']['order_total']+'"';
                                                            set_attr += ' data-ref-id="'+group_data[b]['ref_id']+'"';
                                                            set_attr += ' data-ref-name="'+group_data[b]['ref_name']+'"';
                                                            set_attr += ' data-ref-group-name="'+group_name+'"';                                                            
                                                        }else{
                                                            set_attr += ' data-ref-id="'+group_data[b]['ref_id']+'"';
                                                            set_attr += ' data-ref-name="'+group_data[b]['ref_name']+'"';
                                                            set_attr += ' data-ref-group-name="'+group_name+'"';
                                                        }

                                                        dsp += '<div class="btn_room_click div_room_detail col-lg-2 col-md-2 col-sm-4 col-xs-6"';
                                                        dsp += set_attr+">"; /*background-color:' + set_color + '; */
                                                            dsp += '<div class="col-md-12 col-sm-12" style="padding:12px 0px;cursor:pointer;border:1px solid white;">';
                                                                dsp += '<div class="col-md-12 col-sm-12" style="text-align:center;">';
                                                                    dsp += '<span class="' + ref_icon + ' fa-2x"></span>';
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
                                        dsp += '</div>';                                            
                                    dsp += '</div>';
                                }
                            }
                            $("#div_room").html(dsp);
                        } else {
                            $("#div_room").html('');
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
            //Plan A (Offline)
                var products = JSON.parse(window.localStorage.getItem('products')) || [];
                var p = "SELECT * FROM ? "; var w = "WHERE";
                if((parseInt(params['category_id']) > 0)){
                    w += " product_category_id = "+parseInt(params['category_id']);        
                }else if((params['search'].length > 0)){
                    if(params['search'].length > 0){
                        w += ' product_code LIKE "%'+params['search']+'%" OR product_barcode LIKE "%'+params['search']+'%" OR product_name LIKE "%'+params['search']+'%"';
                    }                    
                }else{
                    w += " product_category_id > 0";
                }
                var d = alasql(p+w,[products]);
                var total_records = parseInt(d.length);
                $("#product_tab_detail").html('');
                if (parseInt(d.length) > 0) {
                    $("#product_tab_detail").html('Loading...');
                    var dsp = '';
                    for (var a = 0; a < total_records; a++) {

                        if (d[a]['product_image'] != undefined) {
                            product_images = base_url + d[a]['product_image'];
                        }else{
                            product_images = product_image;
                        }
                        var set_color = 'template';
                        var set_price = 'Rp. ' + addCommas(d[a]['product_price_sell_format']);
                        var price = d[a]['product_price_sell'];
                        if (parseFloat(d[a]['product_price_promo']) > 1) {
                            set_color = 'blue';
                            set_price = '<span style="text-decoration:line-through;">Rp. ' + addCommas(d[a]['product_price_sell_format']) + '</span> - Rp. ' + addCommas(d[a]['product_price_promo_format']);
                            price = d[a]['product_price_promo'];
                        }

                        dsp += '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 btn_save_trans_item product_tab_detail_item"';
                                dsp += 'data-product-id="' + d[a]['product_id'] + '"';
                                dsp += 'data-product-code="' + d[a]['product_code'] + '"';
                                dsp += 'data-product-name="' + d[a]['product_name'] + '"';
                                dsp += 'data-product-type="' + d[a]['product_type'] + '"';
                                dsp += 'data-product-qty="1"';
                                dsp += 'data-product-unit="' + d[a]['product_unit'] + '"';
                                dsp += 'data-product-price="' + price + '">';
                            dsp += '<div class="col-md-12 col-sm-12 product-tab-color-' + set_color + '" style="">';
                                dsp += '<img src="' + product_images + '" class="img-responsive" style="margin-top:20px;">';
                            dsp += '</div>';
                            dsp += '<div class="col-md-12 col-sm-12 product-tab-color-' + set_color + '">';
                                dsp += '<p class="product-name" style="">' + d[a]['product_name'] + '</p>';
                                // dsp += '<p class="product-price" style="">' + d.result[a]['category_name'] + '</p>';
                                dsp += '<p class="product-price" style="">' + set_price + '</p>';
                            dsp += '</div>';
                        dsp += '</div>';

                        //Auto Input when display 1
                        if(total_records == 1){
                            var items = {
                                id:d[a]['product_id'],
                                code:d[a]['product_code'],
                                name:d[a]['product_name'],
                                unit:d[a]['product_unit'],
                                qty:1,
                                price:price,
                                type:d[a]['product_type']
                            }
                            autoInputItem(items);
                        }                        
                    }
                    $("#product_tab_detail").html(dsp);
                }else{
                    $("#product_tab_detail").html('Tidak ada produk');
                }
                return;

            //Plan B (Online)
                var prepare = {
                    tipe: identity,
                    category_id: params['category_id'],
                    search: params['search']
                };
                var data = {
                    action: 'load_product_tab_detail',
                    data: JSON.stringify(prepare)
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    dataType: 'json',
                    cache: 'false',
                    beforeSend: function () {
                        $("#product_tab_detail").html('Loading...');
                    },
                    success: function (d) {
                        if (parseInt(d.status) == 1) {
                            if (parseInt(d.total_records) > 0) {
                                $("#product_tab_detail").html('');
                                var dsp = '';
                                var total_records = parseInt(d.total_records);
                                for (var a = 0; a < total_records; a++) {

                                    if (d.result[a]['product_image'] != undefined) {
                                        product_images = base_url + d.result[a]['product_image'];
                                    }else{
                                        product_images = product_image;
                                    }

                                    var set_color = 'template';
                                    var set_price = 'Rp. ' + addCommas(d.result[a]['product_price_sell_format']);
                                    var price = d.result[a]['product_price_sell'];
                                    if (parseFloat(d.result[a]['product_price_promo']) > 1) {
                                        set_color = 'blue';
                                        set_price = '<span style="text-decoration:line-through;">Rp. ' + addCommas(d.result[a]['product_price_sell_format']) + '</span> - Rp. ' + addCommas(d.result[a]['product_price_promo_format']);
                                        price = d.result[a]['product_price_promo'];
                                    }

                                    dsp += '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 btn_save_trans_item product_tab_detail_item"';
                                            dsp += 'data-product-id="' + d.result[a]['product_id'] + '"';
                                            dsp += 'data-product-code="' + d.result[a]['product_code'] + '"';
                                            dsp += 'data-product-name="' + d.result[a]['product_name'] + '"';
                                            dsp += 'data-product-type="' + d.result[a]['product_type'] + '"';
                                            dsp += 'data-product-qty="1"';
                                            dsp += 'data-product-unit="' + d.result[a]['product_unit'] + '"';
                                            dsp += 'data-product-price="' + price + '">';
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
                                $("#product_tab_detail").html(dsp);
                            }else{
                                $("#product_tab_detail").html(d.message);
                            }
                        } else {
                            notif(0, d.message);
                            $("#product_tab_detail").html(d.message);
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, err);
                    }
                });          
        }
        function loadTrans(trans_data){
            let v = trans_data;
            transId = v['trans_id'];
            $("#trans_date").val(moment(v['trans_date']).format("DD-MMM-YYYY"));
            $("#trans_date").attr('data-raw',moment(v['trans_date']).format("YYYY-MM-DD"));

            $("#trans_number").val(v['trans_number']);
            $("#trans_contact_name").val(v['contact_name']);
            $("#trans_contact_phone").val(v['contact_phone']);

            $("select[id='trans_contact_id']").append(''+'<option value="'+v['contact_id']+'">'+v['contact_name']+'</option>');
            $("select[id='trans_contact_id']").val(v['contact_id']).trigger('change');

            $("select[id='trans_sales_id']").append(''+'<option value="'+v['sales_id']+'">'+v['sales_fullname']+'</option>');
            $("select[id='trans_sales_id']").val(v['sales_id']).trigger('change');    

            $("select[id='trans_ref_id']").append(''+'<option value="'+v['ref_id']+'">'+v['ref_name']+'</option>');
            $("select[id='trans_ref_id']").val(v['ref_id']).trigger('change');    
            
            trans = trans_data;        
        }
        function loadTransItems(trans_items){
			let total_records = trans_items.length;
            transTotal = 0;
            transItemTotal = 0;
			if(parseInt(total_records) > 0){
				$("#table_trans_item tbody").html('');
				$("#table_trans_item_modal tbody").html('');                
				var dsp = '';
				trans_items.forEach(async (v, i) => {
                    transItemTotal = transItemTotal + v['product_total'];
                    transTotal = transTotal + v['product_total'];                    
                    var set_attr = 'data-product-id="'+v['product_id']+'" data-product-code="'+v['product_code']+'" data-product-name="'+v['product_name']+'" data-product-type="'+v['product_type']+'"';
					
                    dsp += '<tr class="tr-trans-item-id" data-id="'+v['product_id']+'">';
						dsp += '<td>';
							dsp += '<button type="button" class="btn_delete_trans_item btn btn-danger" '+set_attr+'>';
							    dsp += '<i class="fas fa-trash-alt"></i>';
							dsp += '</button>';
						dsp += '</td>';					
						dsp += '<td>';
                            dsp += v['product_name']+'<br>';
                            dsp += addCommas(v['product_price'])+' x '+addCommas(v['product_qty'])+'<br>';
                            if(v['product_note'].length > 1){
                                dsp += '<button class="btn btn_delete_trans_item_note btn-danger btn-mini"'; 
                                dsp += 'type="button" '+set_attr+'>';
                                    dsp += 'X';
                                dsp += '</button>';
                                dsp += '<button class="btn_save_trans_item_note btn btn-default btn-mini" '+set_attr+' data-product-note="'+v['product_note']+'" type="button">';
                                    dsp += '<span class="fa fa-pencil"></span>'; 
                                    dsp += 'Catatan: '+v['product_note']+'';
                                dsp += '</button>';
                            }else{
                                dsp += '<button class="btn_save_trans_item_note btn btn-info btn-mini" '+set_attr+' type="button">';
                                    dsp += '<span class="fas fa-plus"></span> Catatan';
                                dsp += '</button>';
                            }
                        dsp += '</td>';
						dsp += '<td style="text-align:right;">';
                            dsp += '<div class="group-plus-minus">';
                            dsp += '    <button class="btn btn_save_trans_item_plus_minus btn-small btn-warning" data-operator="decrease" '+set_attr+'>';
                            dsp += '        <span class="fas fa-minus"></span>';
                            dsp += '    </button>';
                            dsp += '    <button class="btn btn-small btn-default" onclick="return;" type="button">';
                                        dsp += addCommas(v['product_qty']);
                            dsp += '    </button>';
                            dsp += '    <button class="btn btn_save_trans_item_plus_minus btn-small btn-success" data-operator="increase" '+set_attr+'>';
                            dsp += '        <span class="fas fa-plus"></span>';
                            dsp += '    </button>';
                            dsp += '</div>';
                        dsp += '</td>';
						dsp += '<td style="text-align:right;">'+addCommas(v['product_total'])+'</td>';																							
					dsp += '</tr>';
				});                
				$("#table_trans_item tbody").html(dsp);
				$("#table_trans_item_modal tbody").html(dsp);        
                console.log('loadTransItems():');        
                console.log(trans_items);
                // console.log(JSON.stringify(trans_items));
			}else{
				$("#table_trans_item tbody").html('');
			}
            transItemsList = trans_items;
            transProductCount = total_records;  
            $("#trans_total").val(addCommas(transItemTotal));
            $("#trans_product_count").val(addCommas(transProductCount));
            $(".trans_product_count_span").html(addCommas(transProductCount));    
            $(".trans_product_total_span").html(addCommas(transItemTotal));               
            if(barcodeMode > 0){
                // console.log(barcodeMode);
                // $("#search-produk-tab-detail").focus();
                $("#search_product_tab").val('');
                $("#search_product_tab").focus();
            }        
        }
        function loadPayment(trans_data,trans_items_data){
            var c_id = $("#trans_contact_id").find(':selected');
            var c_nm = $("#trans_contact_name").val();
            var c_ph = $("#trans_contact_phone").val();

            // var c_is_register = trans_data['contact_is_register'];
            // var c_id = trans_data['contact_id'];            
            // var c_nm = trans_data['contact_name'];
            // var c_ph = trans_data['contact_phone'];                        

            //Contact
            // $("#payment_contact_checkbox").val(trans_data['']);
            $("#payment_contact_name").val(c_nm);
            $("#payment_contact_phone").val(c_ph);
            $("#payment_contact_id").append('<option value="'+c_id.val()+'">'+c_id.text()+'</option>');
            $("#payment_contact_id").val(c_id.val()).trigger('change');
            // $("#payment_contact_id").val(c_id).trigger('change');

            $("#payment_total_before").val(trans_data['trans_total_price']);
            $("#payment_total").val(trans_data['trans_total_price']);
            $("#payment_received").val(0);
            $("#payment_change").val(0);                                    

        }
               
        // Form 
        function formTransReset(){
            $("#form_trans input").not("input[id='trans_date']").val('');
            // $("#form_trans input").not("input[id='id_document']")
            //         .not("input[id='tipe']")
            //         .not("input[id='tgl']")
            //         .not("input[id='tgl_tempo']").val('');
            $("#form_trans select").val(0).trigger('change');
            trans             = [];
            transItemsList    = [];
            loadTrans(trans);
            loadTransItems(transItemsList);                            
        }
        function formPaymentReset(){
            $("#transfer_number").val('');
            $("#transfer_name").val('');
            $("#edc_card_type").val(0).trigger('change');
            $("#edc_year").val('');
            $("#edc_month").val('');
            $("#edc_card_number").val('');
            $("#edc_name").val('');
            $("#edc_note").val('');

            $("#payment_contact_id").val(0).trigger('change');
            $("#payment_contact_name").val('');
            $("#payment_contact_phone").val('');
            $("#payment_total_before").val(0);  
            $("#payment_total").val(0);
            $("#payment_received").val(0);
            $("#payment_change").val(0);    
        }
        function formTransSetDisplay(value){ // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
            if(value == 1){ var flag = true; }else{ var flag = false; }
            //Attr Input yang perlu di setel
            var form = '#form_trans'; 
            var attrInput = [
            // "tax_name","tax_percent"
            ];
            for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag); }

            //Attr Textarea yang perlu di setel
            var attrText = [
            ];
            for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

            //Attr Select yang perlu di setel
            var atributSelect = [
            // "tax_flag"
            ];
            for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag); }   
        }
        function formKontakNew() {
            $("#kode_contact").val('');
            $("#nama_contact").val('');
            $("#perusahaan_contact").val('');
            $("#telepon_1_contact").val('');
            $("#email_1_contact").val('');
            $("#alamat_contact").val('');
        }
        function formSendReceipt(params) { //ols is formWhatsApp()
            var d = {
                trans_id: params['trans_id'],
                trans_number: params['trans_number'],
                trans_date: params['trans_date'],
                trans_total: params['trans_total'],
                contact_id: params['contact_id'],
                contact_name: params['contact_name'],
                contact_phone: params['contact_phone'],
                // contact_email: params['contact_email']
            }
            var content = '';
            var ctitle = 'Tanda Terima';
            content += 'Apakah anda ingin mengirim '+ctitle+' ?<br><br>';
            let title = 'Kirim '+ctitle;
            $.confirm({
                title: title,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
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
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Nomor '+ctitle+'</label>';
                        dsp += '        <input id="jc_number" name="jc_number" class="form-control" value="' + d['trans_number'] + '" readonly>';
                        dsp += '    </div>';
                        dsp += '</div>';                    
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">';
                        dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-left prs-0">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Tgl Transaksi</label>';
                        dsp += '        <input id="jc_date" name="jc_date" class="form-control" value="' + d['trans_date'] + '" readonly>';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-right prs-0">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Total</label>';
                        dsp += '        <input id="jc_total" name="jc_total" class="form-control" value="' + addCommas(d['trans_total']) + '" readonly>';
                        dsp += '    </div>';
                        dsp += '</div>';                        
                    dsp += '</div>';
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">';                    
                        dsp += '<div class="col-md-5 col-xs-5 col-sm-5 padding-remove-left prs-0">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Nama</label>';
                        dsp += '        <input id="jc_contact_name" name="jc_contact_name" class="form-control" value="' + d['contact_name'] + '">';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-7 col-xs-7 col-sm-7 padding-remove-right prs-0">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Whatsapp</label>';
                        dsp += '        <input id="jc_contact_number" name="jc_contact_number" class="form-control" value="' + d['contact_phone'] + '">';
                        dsp += '    </div>';
                        dsp += '</div>';
                    dsp += '</div>';
                    dsp += '<div class="hide col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">';
                    dsp += '    <div class="form-group">';
                    dsp += '    <label class="form-label">Email</label>';
                    dsp += '        <input id="jc_contact_email" name="jc_contact_email" class="form-control" value="' + d['contact_email'] + '">';
                    dsp += '    </div>';
                    dsp += '</div>';                    
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);
                },
                buttons: {
                    button_1: {
                        text: '<i class="fas fa-paper-plane white"></i> Kirim ',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function (e) {
                            let self = this;

                            let name = self.$content.find('#jc_contact_name').val();
                            let number = self.$content.find('#jc_contact_number').val();

                            if (!name) {
                                $.alert('Nama diisi dahulu');
                                return false;
                            } else if (!number) {
                                $.alert('Nomor WhatsApp diisi dahulu');
                                return false;
                            } else {
                                var data = {
                                    action: 'whatsapp-send-message-invoice-trans-order',
                                    trans_id: d['trans_id'],
                                    contact_id: d['contact_id'],
                                    contact_name: name,
                                    contact_phone: number,
                                    contact_email: ''
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
                        text: '<i class="fas fa-window-close white"></i> Tidak Jadi',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function () {
                            //Close
                        }
                    }
                }
            });
        }
        function checkBoxTransNonMember(flag) {
            if (flag == 0) {
                $("#trans_contact_checkbox_flag").prop("checked", true);
                $(".trans_contact_checkbox").attr("data-flag", 1);
                $("#trans_contact_name").attr('readonly',true);
                $("#trans_contact_phone").attr('readonly',true);            
                $("#trans_contact_id").removeAttr('disabled');            
            } else {
                $("#trans_contact_checkbox_flag").prop("checked", false);
                $(".trans_contact_checkbox").attr("data-flag", 0);
                $("#trans_contact_name").attr('readonly',false);
                $("#trans_contact_phone").attr('readonly',false);            
                $("#trans_contact_name").val('');
                $("#trans_contact_phone").val('');                                                
                $("#trans_contact_id").attr('disabled',true);
                $("#trans_contact_id").val(0).trigger('change');      
            }
            var fl = $(".trans_contact_checkbox").attr('data-flag');   
        }
        function checkBoxPaymentNonMember(flag) {
            if (flag == 1) {
                $("#payment_contact_checkbox_flag").prop("checked", true);
                $(".payment_contact_checkbox").attr("data-flag", 1);
                $("#payment_contact_name").attr('readonly',true);
                $("#payment_contact_phone").attr('readonly',true);            
                $("#payment_contact_id").removeAttr('disabled');            
            } else {
                $("#payment_contact_checkbox_flag").prop("checked", false);
                $(".payment_contact_checkbox").attr("data-flag", 0);
                $("#payment_contact_name").attr('readonly',false);
                $("#payment_contact_phone").attr('readonly',false);            
                $("#payment_contact_name").val('');
                $("#payment_contact_phone").val('');                                                
                $("#payment_contact_id").attr('disabled',true);
                $("#payment_contact_id").val(0).trigger('change');      
            }
            var fl = $(".payment_contact_checkbox").attr('data-flag');   
        } 
        function checkBoxBarcode(flag) {
            if (flag == 1) {
                $("#barcode_checkbox_flag").prop("checked", true);
                $(".barcode_checkbox").attr("data-flag", 0);        
            } else {
                $("#barcode_checkbox_flag").prop("checked", false);
                $(".barcode_checkbox").attr("data-flag", 1);    
            }
            var fl = $(".barcode_checkbox").attr('data-flag');  
            barcodeMode = flag; 
            if(barcodeMode > 0){
                $("#search-produk-tab-detail").focus();
            }
            // console.log('checkBoxBarcode() => '+flag);
        }
        function autoInputItem(item){
            var product_id = item['id'];
            var product_code = item['code'];
            var product_name = item['name'];                        
            var product_unit = item['unit'];
            var product_qty = item['qty'];
            var product_price = item['price'];  
            var product_type = item['type'];                
            
            if(parseFloat(product_price) > 0){
                var indexs = transItemsList.findIndex(o => {
                    return o.product_id === parseInt(product_id);
                });
                if(indexs > -1){ //Update when exist
                    current_qty = transItemsList[indexs].product_qty;
                    transItemsList[indexs].product_qty = parseInt(current_qty) + 1;
                    transItemsList[indexs].product_total = parseFloat(parseInt(current_qty) + 1) * parseFloat(transItemsList[indexs].product_price); 
                    cartAnimation();
                    notif(1,product_name);
                }else if(indexs == -1){ //Add when not exist
                    var selected_data = {
                        'product_id':parseInt(product_id),
                        'product_code':product_code,
                        'product_name':product_name,
                        'product_unit':product_unit,
                        'product_qty':parseInt(product_qty),
                        'product_price':parseFloat(product_price),
                        'product_total':parseFloat(product_price) * parseInt(product_qty),
                        'product_note':'',
                        'product_type':product_type,
                    };
                    transItemsList.push(selected_data);
                    cartAnimation();
                    notif(1,product_name);
                }
                loadTransItems(transItemsList);
            }else{
                makeConfirm(0,'Harga Jual <b>'+product_name+'</b> tidak ditemukan');
            }
        }

        // Print
        function printFromUrl(url) {
            var beforeUrl = 'intent:';
            var afterUrl = '#Intent;';
            // Intent call with component
            //afterUrl += 'component=ru.a402d.rawbtprinter.activity.PrintDownloadActivity;'
            afterUrl += 'package=ru.a402d.rawbtprinter;end;';
            document.location = beforeUrl + encodeURI(url) + afterUrl;
            return false;
        }        
        function printReceipt(params){
            var x = screen.width / 2 - 700 / 2;
            var y = screen.height / 2 - 450 / 2;
            var print_url = url_print + '/' + params['trans_id'];

            // var print_url = url_print_payment + '/' + tsession;
            // var win = window.open(print_url, 'Print Payment', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
            if(parseInt(params['trans_id']) > 0){
                var set_print_url = url_print + '_transaction/' + params['trans_id'];
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
        }
        function paymentSuccess(params){
            var d = params; 

            //Prepare Print
            $(".btn_print_payment").attr('data-id', d.trans_id);
            $(".btn_print_payment").attr('data-number', d.trans_number);
            $(".btn_print_payment").attr('data-session', d.trans_session);

            // $("#modal-trans-save").modal('toggle');

            //Set Text
            $(".modal-print-trans-number").html(': ' + d.trans_number);
            $(".modal-print-trans-date").html(': ' + d.trans_date);                                            
            $(".modal-print-trans-paid-type-name").html(': ' + d.paid_type_name);
            $(".modal-print-trans-total").html(': ' + addCommas(d.trans_total));
            $(".modal-print-trans-total-paid").html(': ' + addCommas(d.trans_total_received));
            $(".modal-print-trans-total-change").html(': ' + addCommas(d.trans_total_change));
            
            $("#modal-print-contact-name").val(' ' + d.contact_name);
            $("#modal-print-contact-phone").val(' ' + d.contact_phone);
            
            $(".btn_send_whatsapp").attr('data-id',d.trans_id)
            .attr('data-number',d.trans_number)
            .attr('data-date',d.trans_date)
            .attr('data-total',d.trans_total)
            .attr('data-contact-id',d.contact_id)
            .attr('data-contact-name',d.contact_name)
            .attr('data-contact-phone',d.contact_phone);            
            $("#modal-payment-print").modal({backdrop: 'static', keyboard: false});
        }

        // Global Variable              
        function globalVariableCheck(){
            // console.log('transId => '+transId);
            // console.log('transTotal => '+transTotal);
            // console.log('transItemTotal => '+transItemTotal);
            // console.log('transProductCount => '+transProductCount);
            // console.log('trans => '+JSON.stringify(trans));
            // console.log('transItemList => '+JSON.stringify(transItemsList));
            // console.log('paymentMethod => '+paymentMethod);                                                            
        }
        function globalVariableReset(){
            transId    = 0;
            transTotal = 0;
            transItemTotal = 0;
            transProductCount = 0;
            paymentMethod = 0;    
            roomId = 0;
            salesId = 0;     
        }
        function modalNumberChoice(nominal){ //Pending Development
            if(parseFloat(nominal) > 0){
                let title   = 'Jumlah Yang Diterima';
                $.confirm({
                    title: title,
                    columnClass: 'col-md-5 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                    closeIcon: true, closeIconClass: 'fas fa-times', 
                    animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                    content: function(){
                    },
                    onContentReady: function(e){
                        let self    = this;
                        let content = '';
                        let dsp     = '';
                        var nm      = parseInt(0);
                        var nml     = nominal.toString().length
                        // dsp += '<div class="row">';
                            dsp += '<div id="div_number_choice" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="'+nominal+'">';
                                    dsp += '<div class="col-md-12 col-sm-12">';
                                        dsp += '<p>Uang Pas<br>'+addCommas(nominal)+'</p>';
                                    dsp += '</div>';
                            dsp += '    </div>';                                           
                            dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="0">';
                                    dsp += '<div class="col-md-12 col-sm-12">';
                                        dsp += '<p>Ketik Nominal<br>Sendiri</p>';
                                    dsp += '</div>';
                            dsp += '    </div>';     
                            
                            var ioi = '0';
                            for(var ss=0; ss<parseInt(nml); ss++){
                                ioi += '0';
                            }
                            var convert = ioi;                        
                            convert = ioi.replace(0,'');
                            convert = convert.replace(0,'');
                            convert = convert.replace(0,'');

                            var number_dom = parseFloat(convert);
                            if(parseFloat(nominal) < parseFloat(10+convert)){ //100
                                dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="'+parseFloat(10+convert)+'">';
                                        dsp += '<div class="col-md-12 col-sm-12">';
                                            dsp += '<p style="padding-top:10px;">'+addCommas(parseFloat(10+convert))+'</p>';
                                        dsp += '</div>';
                                dsp += '    </div>';
                            }

                            if(parseFloat(nominal) < parseFloat(15+convert)){ //150
                                dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="'+parseFloat(15+convert)+'">';
                                        dsp += '<div class="col-md-12 col-sm-12">';
                                            dsp += '<p style="padding-top:10px;">'+addCommas(parseFloat(15+convert))+'</p>';
                                        dsp += '</div>';
                                dsp += '    </div>';
                            } 

                            if(parseFloat(nominal) < parseFloat(20+convert)){ //200
                                dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="'+parseFloat(20+convert)+'">';
                                        dsp += '<div class="col-md-12 col-sm-12">';
                                            dsp += '<p style="padding-top:10px;">'+addCommas(parseFloat(20+convert))+'</p>';
                                        dsp += '</div>';
                                dsp += '    </div>';
                            } 

                            if(parseFloat(nominal) < parseFloat(25+convert)){ //250
                                dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="'+parseFloat(25+convert)+'">';
                                        dsp += '<div class="col-md-12 col-sm-12">';
                                            dsp += '<p style="padding-top:10px;">'+addCommas(parseFloat(25+convert))+'</p>';
                                        dsp += '</div>';
                                dsp += '    </div>';
                            } 

                            // if(parseFloat(nominal) < parseFloat(30+convert)){ //300
                            //     dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="'+parseFloat(30+convert)+'">';
                            //             dsp += '<div class="col-md-12 col-sm-12">';
                            //                 dsp += '<p style="padding-top:10px;">'+addCommas(parseFloat(30+convert))+'</p>';
                            //             dsp += '</div>';
                            //     dsp += '    </div>';
                            // }

                            if(parseFloat(nominal) < parseFloat(50+convert)){ //500
                                dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="'+parseFloat(50+convert)+'">';
                                        dsp += '<div class="col-md-12 col-sm-12">';
                                            dsp += '<p style="padding-top:10px;">'+addCommas(parseFloat(50+convert))+'</p>';
                                        dsp += '</div>';
                                dsp += '    </div>';
                            } 

                            if(parseFloat(nominal) < parseFloat(70+convert)){ //700
                                dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="'+parseFloat(70+convert)+'">';
                                        dsp += '<div class="col-md-12 col-sm-12">';
                                            dsp += '<p style="padding-top:10px;">'+addCommas(parseFloat(70+convert))+'</p>';
                                        dsp += '</div>';
                                dsp += '    </div>';
                            } 

                            if(parseFloat(nominal) < parseFloat(75+convert)){ //750
                                dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="'+parseFloat(75+convert)+'">';
                                        dsp += '<div class="col-md-12 col-sm-12">';
                                            dsp += '<p style="padding-top:10px;">'+addCommas(parseFloat(75+convert))+'</p>';
                                        dsp += '</div>';
                                dsp += '    </div>';
                            } 

                            if(parseFloat(nominal) < parseFloat(90+convert)){ //900
                                dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="'+parseFloat(90+convert)+'">';
                                        dsp += '<div class="col-md-12 col-sm-12">';
                                            dsp += '<p style="padding-top:10px;">'+addCommas(parseFloat(90+convert))+'</p>';
                                        dsp += '</div>';
                                dsp += '    </div>';
                            } 

                            if(parseFloat(nominal) < parseFloat(100+convert)){ //1000
                                dsp += '    <div class="btn_number_choice col-md-4 col-xs-6 col-sm-6 padding-remove-side" data-value="'+parseFloat(100+convert)+'">';
                                        dsp += '<div class="col-md-12 col-sm-12">';
                                            dsp += '<p style="padding-top:10px;">'+addCommas(parseFloat(100+convert))+'</p>';
                                        dsp += '</div>';
                                dsp += '    </div>';
                            }
                            dsp += '    </div>';     

                            dsp += '</div>';
                        // dsp += '</div>';
                        dsp += '<input id="jc_inp" name="jc_inp" value="0" type="hidden">';
                        content = dsp;
                        self.setContentAppend(content);
                        $(".btn_number_choice").on("click", function(e){
                            e.preventDefault();
                            e.stopPropagation();
                            var set_received = $(this).attr('data-value');
                            $("#jc_inp").val(set_received);  
                            $("#payment_received").val(addCommas(set_received));
                            $("#payment_change").val(addCommas(parseFloat(set_received)-parseFloat(nominal)));                             
                            jconfirm.instances[0].close();
                        });
                        self.buttons.button_1.hide();
                    },
                    buttons: {
                        button_1: {
                            text: ' ',
                            // btnClass: 'btn-danger',
                            keys: ['Escape'],
                            action: function(){
                                //Close
                            }
                        }
                    }
                });
            }
        }
        function modalContactName(input){ //Pending Development
            let title   = 'Nama '+contact_1_alias;
            $.confirm({
                title: 'Masukan '+title,
                // icon: 'fas fa-user-check fa-1x',
                columnClass: 'col-md-5 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true, closeIconClass: 'fas fa-times', 
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content: function(){
                },
                onContentReady: function(e){
                    let self    = this;
                    let content = '';
                    let dsp     = '';
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                    dsp += '    <div class="form-group">';
                    dsp += '    <label class="form-label">'+title+'</label>';
                    dsp += '        <input id="jc_input" name="jc_input" class="form-control" value="'+input+'">';
                    dsp += '    </div>';
                    dsp += '</div>';
                    content = dsp;
                    self.setContentAppend(content);
                    self.$content.find('#jc_input').focus();
                },
                buttons: {
                    button_1: {
                        text: '<i class="fas fa-check white"></i> Ok',
                        btnClass: 'btn-primary',
                        keys: ['Enter'],
                        action: function(){
                            var self = this;
                            let inp = self.$content.find('#jc_input').val();
                            $("#trans_contact_name").val(inp);
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-times white"></i> Batal',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }                    
                }
            });
        }
        function modalContactPhone(input){ //Pending Development
            let title   = 'Telepon '+contact_1_alias;
            $.confirm({
                title: 'Masukan '+title,
                // icon: 'fas fa-user-check fa-1x',
                columnClass: 'col-md-5 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true, closeIconClass: 'fas fa-times', 
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content: function(){
                },
                onContentReady: function(e){
                    let self    = this;
                    let content = '';
                    let dsp     = '';
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                    dsp += '    <div class="form-group">';
                    dsp += '    <label class="form-label">'+title+'</label>';
                    dsp += '        <input id="jc_input" name="jc_input" class="form-control" value="'+input+'">';
                    dsp += '    </div>';
                    dsp += '</div>';
                    content = dsp;
                    self.setContentAppend(content);
                    self.$content.find('#jc_input').focus();
                },
                buttons: {
                    button_1: {
                        text: '<i class="fas fa-check white"></i> Ok',
                        btnClass: 'btn-primary',
                        keys: ['Enter'],
                        action: function(){
                            var self = this;
                            let inp = self.$content.find('#jc_input').val();
                            $("#trans_contact_phone").val(inp);
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-times white"></i> Batal',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }                    
                }
            });
        }               
        function makeConfirm(action,message){
            if(action == 0){
                var ic = '<span class="fas fa-info-circle"></span> Informasi';
            }else if(action == 1){
                var ic = '<span class="fas fa-question-circle"></span> Petunjuk';
            }
            $.confirm({
                title: ic,
                content: message,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
                autoClose: 'button_1|30000',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                buttons: {
                    button_1: {
                        text:'Tutup',
                        btnClass: 'btn-danger',
                        keys: ['enter'],
                        action: function(){
                        }
                    },
                }
            });
        }
        async function cartAnimation() {
            $(".btn_cart").css('background-color','');
            $(".btn_cart").css('background-color','var(--form-background-color)');
            let myPromise = new Promise(function(resolve) {
                setTimeout(function() {
                    $(".btn_cart").css('background-color','');
                    $(".btn_cart").css('background-color','var(--form-background-color-hover)');
                    resolve("Done");
            }, 200);
            });
            await myPromise;
        }
        //Sync
        function localStorage(){
            $.ajax({
                type: "post",
                url: url+'/sync_product',
                dataType: 'json', cache: 'false', 
                beforeSend:function(){},
                success:function(d){
                    let s = d.status;
                    let m = d.message;
                    let r = d.result;
                    if(parseInt(s) == 1){
                        local.setItem('products',JSON.stringify(r));
                        // var product_local = JSON.parse(window.localStorage.getItem('products') || "[]"); 
                        // productStorage.push(product_local);
                        // console.log(productStorage);
                    }else{
                        notif(s,m);
                    }
                },
                error:function(xhr,status,err){
                    notif(0,err);
                }
            });
        } 
        localStorage();
        loadRoom({});
        /*  
            var p = {
                trans_id:7,
                trans_number:'Invoice-2309-00002',
                trans_date:"05-Sep-2023, 15:27",
                trans_session:"OKLKFNWE285RG2VZH7RY",
                trans_total:'58500',  
                trans_total_received:'70000',
                trans_total_change:'11500',
                contact_id:'7',
                contact_name:'Joce',
                contact_phone:'081225518118'                                                                                                                                                                                                                                  
            }
            paymentSuccess(p);
        */
        // scannerResult(1,2);
        // modalNumberChoice(120);
    });    
</script>