
<script>
    $(document).ready(function () {
        var identity = "<?php echo $identity; ?>";

        // $("[class*='tab-pane-sub']").addClass('active');
        // $.alert('loadRoom() ');
        /* Get Value */
        // ($("#selector").is(":checked") == true) ? 1 : 0
        // $("input[name='selector']:checked").val();
        
        /* Set Value */
        // $("input[name='selector'][value='1']").prop("checked", true).change();
        
        //Url
        var url = "<?= base_url('front_office/booking'); ?>";
        let url_print = "<?php base_url('front_office/booking'); ?>";
            
        let url_tool = "<?php base_url('search/manage'); ?>";
        var url_image = "<?php site_url('upload/noimage.png'); ?>";

        let image_width = "<?= $image_width;?>";
        let image_height = "<?= $image_height;?>";

        let orderID = 0;
        let orderItemID = 0;
        let orderSESSION = 0;
        
        var module_approval = parseInt("<?php echo $module_approval; ?>");
        var module_attachment = parseInt("<?php echo $module_attachment; ?>"); 

        $(function() {
            setInterval(function(){ 
                //SummerNote
                // $('#order_note').summernote({
                //     placeholder: 'Tulis keterangan disini!',
                //     tabsize: 4,
                //     height: 350
                // });  
            }, 3000);
        });

        var aa = '';
        //Croppie
        var upload_crop_img_1 = $('#modal_croppie_canvas_1').croppie({
            enableExif: true,
            viewport: {width: image_width, height: image_height},
            boundary: {width: parseInt(image_width)+10, height: parseInt(image_height)+10},
            url: url_image,
        });
        var upload_crop_img_2 = $('#modal_croppie_canvas_2').croppie({
            enableExif: true,
            viewport: {width: image_width, height: image_height},
            boundary: {width: parseInt(image_width)+10, height: parseInt(image_height)+10},
            url: url_image,
        });        
        $('.files_link_1').magnificPopup({
            type: 'image',
            mainClass: 'mfp-with-zoom', // this class is for CSS animation below
                zoom: {
                    enabled: true, // By default it's false, so don't forget to enable it
                    duration: 300, // duration of the effect, in milliseconds
                    easing: 'ease-in-out', // CSS transition easing function
                    // The "opener" function should return the element from which popup will be zoomed in
                    // and to which popup will be scaled down
                    // By defailt it looks for an image tag:
                    opener: function (openerElement) {
                        // openerElement is the element on which popup was initialized, in this case its <a> tag
                        // you don't need to add "opener" option if this code matches your needs, it's defailt one.
                        return openerElement.is('img') ? openerElement : openerElement.find('img');
                    }
                }
        });
        $('.files_link_2').magnificPopup({
            type: 'image',
            mainClass: 'mfp-with-zoom', // this class is for CSS animation below
                zoom: {
                    enabled: true, // By default it's false, so don't forget to enable it
                    duration: 300, // duration of the effect, in milliseconds
                    easing: 'ease-in-out', // CSS transition easing function
                    // The "opener" function should return the element from which popup will be zoomed in
                    // and to which popup will be scaled down
                    // By defailt it looks for an image tag:
                    opener: function (openerElement) {
                        // openerElement is the element on which popup was initialized, in this case its <a> tag
                        // you don't need to add "opener" option if this code matches your needs, it's defailt one.
                        return openerElement.is('img') ? openerElement : openerElement.find('img');
                    }
                }
        });        
        //Select2
        /*
        $('#select').select2({
            //dropdownParent:$("#modal-id"), //If Select2 Inside Modal
            //placeholder: '<i class="fas fa-search"></i> Search',
            //width:'100%',
            placeholder: {
                id: '0',
                text: '-- Pilih --'
            },
            minimumInputLength: 0,
            allowClear: true,
            ajax: {
                type: "get",
                url: url_tool,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        action:'search',
                        type: 1,
                        source: 'select_source'
                    };
                    return query;
                },
                processResults: function (data){
                    var datas = [];
                    $.each(data, function(key, val){
                        datas.push({
                            'id' : val.id,
                            'text' : val.text
                        });
                    });
                    return {
                        results: datas
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
                    // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            },
            templateSelection: function(datas) { //When Option on Click
                if (!datas.id) { return datas.text; }
                //Custom Data Attribute
                $(datas.element).attr('data-alamat', datas.alamat);
                return datas.text;
            }
        });
        $("#select").on('change', function(e){
            // Do Something
        });
        */
        // $("select").select2();

        //Date Clock Picker
        $("#order_start_date, #order_end_date").datepicker({
            // defaultDate: new Date(),
            format: 'dd-M-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1,
            // timezone:"+0700"
        }).on('change', function(e){
        });
        $('.clockpicker').clockpicker({
            default: 'now',
            placement: 'bottom',
            align: 'left',
            donetext: 'Done',
            autoclose: true
        }).on('change', function(e){
        });
        $("#filter_start_date, #filter_end_date").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1 
        }).on('change', function(e){
            e.stopImmediatePropagation();
            order_table.ajax.reload();
        });

        //Autonumeric
        const autoNumericOption = {
            digitGroupSeparator : ',', 
            decimalCharacter  : '.',
            decimalCharacterAlternative: '.', 
            decimalPlaces: 0,
            watchExternalChanges: true
        };
        let orderPRICE = new AutoNumeric('#order_price', autoNumericOption);
        let paidTOTAL = new AutoNumeric('#paid_total', autoNumericOption);        

        //Datatable
        let order_table = $("#table_order").DataTable({
            // "processing": true,
            // "rowReorder": { selector: 'td:nth-child(1)'},
            "responsive": true,
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function(d) {
                    d.action = 'load_checkin';
                    d.tipe = 222;
                    d.search = {value:$("#filter_search").val()};
                    d.date_start = $("#filter_start_date").val();
                    d.date_end = $("#filter_end_date").val();
                    d.filter_branch = $("#filter_branch").find(':selected').val();
                    d.filter_ref_price = $("#filter_ref_price").find(':selected').val();
                    d.filter_ref = $("#filter_ref").find(':selected').val();     
                    d.filter_paid = $("#filter_paid_flag").find(':selected').val();                                                            
                    d.filter_flag = $("#filter_flag").find(':selected').val();
                    d.length = $("#filter_length").find(':selected').val();
                },
                dataSrc: function(data) {
                    return data.result;
                }
            },
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
            "columnDefs": [
                {"targets":0, "title":"Action", "searchable":true, "orderable":true},                    
                {"targets":1, "title":"Tgl", "searchable":true, "orderable":true},
                {"targets":2, "title":"Nomor", "searchable":true, "orderable":true},
                {"targets":3, "title":"Type", "searchable":true, "orderable":true},
                {"targets":4, "title":"Kamar", "searchable":true, "orderable":true},            
                {"targets":5, "title":"Kontak", "searchable":true, "orderable":true},
                {"targets":6, "title":"Total", "searchable":true, "orderable":true},
                {"targets":7, "title":"Pembayaran", "searchable":true, "orderable":true},                    
                {"targets":8, "title":"Status", "searchable":false, "orderable":true},               
            ],
            "order": [[0, 'ASC']],
            "columns": [{
                    'data': 'order_id',
                    className: 'text-left',
                    render: function(data, meta, row) {
                        var dsp = ''; var label = 'Error Status'; var icon = 'fas fa-cog'; var color = 'white'; var bgcolor = '#d1dade';
                        if(parseInt(row.order_flag) == 1){
                        //  dsp += '<label style="color:#6273df;">Aktif</label>';
                        label = 'Aktif';
                        icon = 'fas fa-lock';
                        bgcolor = '#0aa699';
                        }else if(parseInt(row.order_flag) == 4){
                        //  dsp += '<label style="color:#ff194f;">Terhapus</label>';
                        label = 'Terhapus';
                        icon = 'fas fa-trash';
                        bgcolor = '#f35958';
                        }else if(parseInt(row.order_flag) == 0){
                        //   dsp += '<label style="color:#ff9019;">Nonaktif</label>';
                        label = 'Nonaktif';
                        icon = 'fas fa-unlock';
                        // color = 'green';
                        bgcolor = '#ff9019';
                        }

                        /* Button Action Concept 2 */
                        dsp += '&nbsp;<div class="btn-group">';
                        // dsp += '    <button class="btn btn-mini btn-default"><span class="fas fa-cog"></span></button>';
                        dsp += '    <button class="btn btn-mini btn-default dropdown-toggle btn-demo-space" data-toggle="dropdown" aria-expanded="true"><span class="fas fa-cog"></span><span class="caret"></span> Aksi</button>';
                        dsp += '    <ul class="dropdown-menu">';
                        dsp += '        <li>';
                        dsp += '            <a class="btn_edit_order" style="cursor:pointer;"';
                        dsp += '                data-order-id="'+data+'" data-order-number="'+row.order_number+'" data-order-flag="'+row.order_flag+'" data-order-session="'+row.order_session+'">';
                        dsp += '                <span class="fas fa-eye"></span> Lihat';
                        dsp += '            </a>';
                        dsp += '        </li>';
                        if(parseInt(row.order_flag) < 4) {
                            if(parseInt(row.order_item_flag_checkin) === 0){
                                    dsp += '<li>'; 
                                    dsp += '    <a class="btn_update_flag_order_item" style="cursor:pointer;"';
                                    dsp += '        data-order-id="'+data+'" data-order-item-id="'+row.order_item_id+'" data-order-number="'+row.order_number+'" data-order-flag="1" data-order-session="'+row.order_session+'" data-order-branch-id="'+row.order_item_branch_id+'" data-order-ref-id="'+row.order_item_ref_id+'">';
                                    dsp += '        <span class="fas fa-lock"></span> CheckIn';
                                    dsp += '    </a>';
                                    dsp += '</li>';
                            }
                            if(parseInt(row.order_item_flag_checkin) === 1){
                                    dsp += '<li>';
                                    dsp += '    <a class="btn_update_flag_order_item" style="cursor:pointer;"';
                                    dsp += '        data-order-id="'+data+'" data-order-item-id="'+row.order_item_id+'" data-order-number="'+row.order_number+'" data-order-flag="2" data-order-session="'+row.order_session+'" data-product-name="'+row.product_name+'">';
                                    dsp += '        <span class="fas fa-ban"></span> Checkout';
                                    dsp += '    </a>';
                                    dsp += '</li>';
                            }
                        }
                        if(parseInt(row.order_flag) == 0) {                        
                                dsp += '<li>';
                                dsp += '    <a class="btn_update_flag_order" style="cursor:pointer;"';
                                dsp += '        data-order-id="'+data+'" data-order-number="'+row.order_number+'" data-order-flag="4" data-order-session="'+row.order_session+'">';
                                dsp += '        <span class="fas fa-trash"></span> Batal';
                                dsp += '    </a>';
                                dsp += '</li>';
                        }
                        dsp += '        <li class="divider"></li>';
                        dsp += '        <li>';
                        dsp += '            <a class="btn_print_order" style="cursor:pointer;" data-order="'+ data +'" data-order-session="'+row.order_session+'">';
                        dsp += '                <span class="fas fa-print"></span> Print';
                        dsp += '            </a>';
                        dsp += '        </li>';
                        dsp += '    </ul>';
                        dsp += '</div>';
                        return dsp;
                    }
                },
                {
                    'data': 'order_item_start_date',
                    className: 'text-left',
                    render: function(data, meta, row) {
                        var dsp = '';
                        dsp += moment(row.order_item_start_date).format("DD-MMM-YYYY");
                        return dsp;
                    }
                },{
                    'data': 'order_number',
                    className: 'text-left',
                    render: function(data, meta, row) {
                        var dsp = '';
                        dsp += row.order_number;
                        // if(row.contact_email_1 != undefined){
                            // dsp += '<br>'+row.contact_email_1;
                        // }
                        return dsp;
                    }
                },{
                    'data': 'price_name',
                    className: 'text-left',
                    render: function(data, meta, row) {
                        var dsp = '';
                        dsp += row.price_name;
                        return dsp;
                    }
                },{
                    'data': 'ref_name',
                    className: 'text-left',
                    render: function(data, meta, row) {
                        var dsp = '';
                        dsp += '['+row.ref_name+']';
                        if(row.product_id != undefined){
                            dsp += ' - '+ row.product_name;
                        }
                        return dsp;
                    }
                },{
                    'data': 'order_contact_name',
                    className: 'text-left',
                    render: function(data, meta, row) {
                        var dsp = '';
                        dsp += row.order_contact_name;
                        return dsp;
                    }
                },{
                    'data': 'order_total',
                    className: 'text-left',
                    render: function(data, meta, row) {
                        var dsp = '';
                        dsp += addCommas(row.order_total);
                        return dsp;
                    }
                },{
                    'data': 'product_name',
                    className: 'text-left',
                    render: function(data, meta, row) {
                        var dsp = '';
                        if(parseInt(row.order_paid) == 0){
                            var sts = 'Belum Lunas';
                            var ic = 'fas fa-thumbs-down';
                            var lg = 'danger';
                        }else if(parseInt(row.order_paid) == 1){
                            var sts = 'Lunas';
                            var ic = 'fas fa-thumbs-up';
                            var lg = 'success';
                        }
                        var set_product = row.order_item_type_2 + ' | ' + row.ref_name + ' | ' +row.product_name + ' | ' + row.price_name;
                        var st = 'data-product="'+set_product+'" data-id="'+row.order_id+'" data-from="orders" data-number="'+row.order_number+'" data-contact-name="'+row.order_contact_name+'" data-contact-id="'+row.contact_id+'" data-date="'+ moment(row.order_item_start_date).format("DD-MMM-YYYY, HH:mm")+'" data-total="'+ addCommas(row.order_total)+'" data-type="'+row.order_type+'" data-contact-type="'+row.contact_type+'"';
                        dsp += '<span '+st+' class="btn_paid_info label label-'+lg+'" style="cursor:pointer;color:white;"><span class="'+ic+'"></span>&nbsp;'+sts+'</span>';
                        return dsp;
                    }
                },{
                    'data': 'order_item_flag_checkin',
                    className: 'text-left',
                    render: function(data, meta, row) {
                        var dsp = '';
                        if(parseInt(row.order_item_flag_checkin) == 0){
                            dsp += '<label class="label" style="background-color:#ff9019;color:white;">Waiting</label>';
                        }else if(parseInt(row.order_item_flag_checkin) == 1){
                            dsp += '<label class="label" style="background-color:#0aa699;color:white;">Check-In</label>';
                        }else if(parseInt(row.order_item_flag_checkin) == 2){
                            dsp += '<label class="label" style="background-color:#f35958;color:white;">Check-Out</label>';
                        } else if(parseInt(row.order_item_flag_checkin) == 4){
                            dsp += '<label class="label" style="background-color:#f35958;color:white;">Batal</label>';
                        }                       
                        return dsp;
                    }
                }
            ]
        });
        $("#table_order_filter").css('display','none');
        $("#table_order_length").css('display','none');
        $("#filter_length").on('change', function(e){
            var value = $(this).find(':selected').val(); 
            $('select[name="table_order_length"]').val(value).trigger('change');
            order_table.ajax.reload();
        });
        $("#filter_branch").on('change', function(e){ order_table.ajax.reload(); });
        $("#filter_ref_price").on('change', function(e){ order_table.ajax.reload(); });
        $("#filter_ref").on('change', function(e){ order_table.ajax.reload(); });                
        $("#filter_paid_flag").on('change', function(e){ order_table.ajax.reload(); });                
        $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ order_table.ajax.reload(); }else if(parseInt(ln) < 1){ order_table.ajax.reload();} });


        //CRUD
        $(document).on("click","#btn_save_order", function(e) {
            e.preventDefault(); e.stopPropagation();
            let next = true;
            /* If id not exist, UPDATE if id exist */
            /*
            if ($("#booking_id").val().length === 0 || parseInt($("#booking_id").val()) === 0) {
                if ($("#booking_id").val().length === 0) {
                    next = false;
                    notif(0,'ID wajib diisi');
                }
            }
            */
            if(next){
                if (!$("input[name='order_branch_id']:checked").val()) {
                    next = false;
                    notif(0,'Cabang wajib pilih');
                }
            }
            if(next){
                if (!$("input[name='order_ref_price_id']:checked").val()) {
                    next = false;
                    notif(0,'Tipe Pesanan wajib pilih');
                }
            }
            if(next){
                if (!$("input[name='order_ref_id']:checked").val()) {
                    next = false;
                    notif(0,'Jenis Kamar wajib pilih');
                }
            }  
            if(next){
                if (!$("input[name='order_product_id']:checked").val()) {
                    next = false;
                    notif(0,'Nomor Kamar wajib pilih');
                }
            }              
            
            if(next){
                if ($("input[name='order_contact_name']").val().length == 0) {
                    next = false;
                    notif(0,'Nama Pemesan wajib diisi');
                }
            }             
            if(next){
                if (orderPRICE.rawValue < 1) {
                    next = false;
                    notif(0,'Harga tidak boleh kosong');
                }
            }

            /* Prepare ajax for UPDATE */
            /* If Form Validation Complete checked */
            if(next){
                var form = new FormData($("#form_booking")[0]);
                form.append('action', 'create_update');
                form.append('order_type', identity);                
                // form.set('order_ref_id',$("input[name='order_ref_id']:checked").val());
                form.set('order_start_date', $("#order_start_date").datepicker('getFormattedDate', 'yyyy-mm-dd'));
                form.set('order_end_date', $("#order_end_date").datepicker('getFormattedDate', 'yyyy-mm-dd')); 
                form.set('order_price', orderPRICE.rawValue);
                form.set('paid_total', paidTOTAL.rawValue);
                form.set('files_1', 0); //Bukti Bayar
                form.set('files_1', 0); //Foto KTP  
                form.append('upload_1', $("#files_preview_1").attr('data-save-img'));
                form.append('upload_2', $("#files_preview_2").attr('data-save-img'));                   
                if(orderID > 0){
                    form.append('order_id', orderID);
                }
                $.ajax({
                    type: "post",
                    url: url,
                    data: form, 
                    dataType: 'json', cache: 'false', 
                    contentType: false, processData: false,   
                    beforeSend:function(x){
                        // x.setRequestHeader('Authorization',"Bearer " + bearer_token);
                        // x.setRequestHeader('X-CSRF-TOKEN',csrf_token);
                    },
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            order_table.ajax.reload();
                            // $("#modal_order").modal("hide");
                            /* hint zz_for or zz_each */
                            
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
        $(document).on("click","#btn_save_order_2",function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            var next =true;
            if($("#order_type").val().length == 0){
                notif(0,'order_TYPE wajib diisi');
                $("#order_type").focus();
                next=false;
            }else if($("#order_name").val().length == 0){
                notif(0,'order_NAME wajib diisi');
                $("#order_name").focus();
                next=false;
            }else if($("#order_note").val().length == 0){
                notif(0,'order_NOTE wajib diisi');
                $("#order_note").focus();
                next=false;
            }else if($("#order_flag").find(':selected').val() == 0){
                notif(0,'order_FLAG wajib diisi');
                $("#order_flag").focus();
                next=false;
            }else{
                var form = new FormData($("#form_order")[0]);
                // var form = new FormData();
                form.append('action', 'create');
                form.append('upload1', $("#order_preview").attr('data-save-img'));
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form, dataType:"json",
                    cache: false, contentType: false, processData: false,
                    beforeSend:function(){},
                    success:function(d){
                        var s = d.status;
                        var m = d.message;
                        var r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            formBookingReset();
                            /* hint zz_for or zz_each */
                            order_table.ajax.reload();
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
        $(document).on("click","#btn_save_order_1", function(e) {
            e.preventDefault(); e.stopPropagation();
            let next = true;
            /* If id not exist, UPDATE if id exist */
            /*
            if ($("#order_id").val().length === 0 || parseInt($("#order_id").val()) === 0) {
                if ($("#order_id").val().length === 0) {
                    next = false;
                    notif(0,'ID wajib diisi');
                }
            }
            */
            if(next){
                if (!$("input[name='order_branch_id']:checked").val()) {
                    next = false;
                    notif(0,'Cabang wajib pilih');
                }
            }
            if(next){
                if (!$("input[name='order_ref_price_id']:checked").val()) {
                    next = false;
                    notif(0,'Tipe Pesanan wajib pilih');
                }
            }
            if(next){
                if (!$("input[name='order_ref_id']:checked").val()) {
                    next = false;
                    notif(0,'Jenis Kamar wajib pilih');
                }
            }  
            
            if(next){
                if ($("input[name='order_contact_name']").val().length == 0) {
                    next = false;
                    notif(0,'Nama Pemesan wajib diisi');
                }
            }             
            if(next){
                if (orderPRICE.rawValue < 1) {
                    next = false;
                    notif(0,'Harga tidak boleh kosong');
                }
            }

            /* Prepare ajax for UPDATE */
            /* If Form Validation Complete checked */
            if(next){
                var form = new FormData($("#form_order")[0]);
                form.append('action', 'create_update');
                form.append('order_type', identity);                
                // form.set('order_ref_id',$("input[name='order_ref_id']:checked").val());
                form.set('order_start_date', $("#order_start_date").datepicker('getFormattedDate', 'yyyy-mm-dd'));
                form.set('order_end_date', $("#order_end_date").datepicker('getFormattedDate', 'yyyy-mm-dd')); 
                form.set('order_price', orderPRICE.rawValue);                       
                if(orderID > 0){
                    form.append('order_id', orderID);
                }
                $.ajax({
                    type: "post",
                    url: url,
                    data: form, 
                    dataType: 'json', cache: 'false', 
                    contentType: false, processData: false,   
                    beforeSend:function(x){
                        // x.setRequestHeader('Authorization',"Bearer " + bearer_token);
                        // x.setRequestHeader('X-CSRF-TOKEN',csrf_token);
                    },
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            order_table.ajax.reload();
                            $("#modal_order").modal("hide");
                            /* hint zz_for or zz_each */
                            
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
        $(document).on("click",".btn_edit_order",function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            var id       = $(this).attr('data-order-id');
            // var session  = $(this).attr('data-order-session');
            var number     = $(this).attr('data-order-number');

            var form = new FormData();
            form.append('action', 'read');
            form.append('order_id', id);
            // form.append('order_session', session);
            form.append('order_number', number);
            $.ajax({
                type: "post",
                url: url,
                data: form, dataType:"json",
                cache: false, contentType: false, processData: false,
                beforeSend:function(){},
                success:function(d){
                    var s = d.status;
                    var m = d.message;
                    var r = d.result;
                    if(parseInt(s)==1){ /* Success Message */

                        $("#order_id").val(d.result.order_id);
                        $("#order_session").val(d.result.order_session);
                        $("#order_type").val(d.result.order_type).trigger('change');
                        $("#order_name").val(d.result.order_name);
                        // $("#order_note").val(d.result.order_note);
                        // $('#order_note').summernote('code', d.result.order_note);
                        $("#order_flag").val(d.result.order_flag).trigger('change');
                        // $("#order_date_created").val(d.result.order_date_created);

                        // $("#files_preview").attr('src',d.result.Booking_image);
                        // $(".files_link").attr('href',d.result.Booking_image);
                        $(".order_branch_id[value=branch_"+d.result_item.order_item_branch_id+"]").prop("checked", true).change();
                        // $(".order_type_2[value="+d.result_item.price_name+"]").prop("checked", true).change();                        
                        $(".order_ref[value="+d.result_item.ref_id+"]").prop("checked", true).change();  

                        $("#order_start_date").datepicker("update", moment(d.result_item.order_item_start_date).format("DD-MM-YYYY"));
                        $("#order_end_date").datepicker("update", moment(d.result_item.order_item_end_date).format("DD-MM-YYYY")); 
                        
                        $("#order_start_hour").val(moment(d.result_item.order_item_start_date).format("HH:mm")).trigger("change");
                        $("#order_end_hour").val(moment(d.result_item.order_item_end_date).format("HH:mm")).trigger("change");

                        $("#order_price").val(d.result_item.order_item_price);
                        $("#order_contact_code").val(d.result_item.order_contact_code);
                        $("#order_contact_name").val(d.result_item.order_contact_name);
                        $("#order_contact_phone").val(d.result_item.order_contact_phone);                                                                                                                                   


                        orderID = d.result.order_id;
                        $("#modal_order").modal('show');
                        loadAttachment(d.result.order_id);
                        loadPaid(d.result.order_id);
                        formBookingSetDisplay(0);
                    }else{
                        notif(0,d.message);
                    }
                },
                error:function(xhr, Status, err){
                    notif(0,'Error');
                }
            });
        });
        $(document).on("click",".btn_delete_order",function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            var next     = true;
            var id       = $(this).attr('data-order-id');
            var session  = $(this).attr('data-order-session');
            var name     = $(this).attr('data-order-name');

            $.confirm({
                title: 'Hapus!',
                content: 'Apakah anda ingin menghapus <b>'+name+'</b> ?',
                buttons: {
                    confirm:{ 
                        btnClass: 'btn-danger',
                        text: 'Ya',
                        action: function () {
                            
                            var form = new FormData();
                            form.append('action', 'delete');
                            form.append('order_id', id);
                            form.append('order_session', session);
                            form.append('order_name', name);
                            form.append('order_flag', 4);

                            $.ajax({
                                type: "POST",
                                url : url,
                                data: form,
                                dataType:'json',
                                cache: false,
                                contentType: false,
                                processData: false,
                                success:function(d){
                                    if(parseInt(d.status)==1){ 
                                        notif(d.status,d.message); 
                                        order_table.ajax.reload(null,false);
                                    }else{ 
                                        notif(d.status,d.message); 
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
        });
        $(document).on("click",".btn_update_flag_order",function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            var next     = true;
            var oid       = $(this).attr('data-order-id');       
            var oss     = $(this).attr('data-order-session');
            var onu     = $(this).attr('data-order-number');
            var oflag     = $(this).attr('data-order-flag');

            if(parseInt(oflag) == 0){
                var set_flag = 0;
                var msg = 'menonaktifkan';
            }else if(parseInt(oflag) == 1){
                var set_flag = 1;
                var msg = 'mengaktifkan';
            }else{
                var set_flag = 4;
                var msg = 'membatalkan';
            }

            $.confirm({
                title: 'Konfirmasi!',
                content: 'Apakah anda ingin '+msg+' <b>'+onu+'</b> ?',
                buttons: {
                    confirm:{ 
                        btnClass: 'btn-danger',
                        text: 'Batalkan',
                        action: function () {
                            
                            var form = new FormData();
                            form.append('action', 'update_flag');
                            form.append('order_id', oid);                          
                            form.append('order_session', oss);
                            form.append('order_number', onu);
                            form.append('order_flag', oflag);

                            $.ajax({
                                type: "POST",
                                url : url,
                                data: form,
                                dataType:'json',
                                cache: false,
                                contentType: false,
                                processData: false,
                                success:function(d){
                                    if(parseInt(d.status)==1){ 
                                        notif(d.status,d.message); 
                                        order_table.ajax.reload(null,false);
                                    }else{ 
                                        notif(d.status,d.message); 
                                    }
                                }
                            });
                        }
                    },
                    cancel:{
                        btnClass: 'btn-default',
                        text: 'Tutup', 
                        action: function () {
                            // $.alert('Canceled!');
                        }
                    }
                }
            });
        }); 

        //CRUD ITEM
        $(document).on("click",".btn_update_flag_order_item",function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            var do_checkin        = false;
            var oid         = $(this).attr('data-order-id');
            var otd         = $(this).attr('data-order-item-id');            
            var oss         = $(this).attr('data-order-session');
            var onu         = $(this).attr('data-order-number');
            var oflag       = $(this).attr('data-order-flag');
            var opr         = $(this).attr('data-product-name');

            if(parseInt(oflag) == 0){
                var set_flag = 0;
                var msg = 'waiting';
            }else if(parseInt(oflag) == 1){
                var set_flag = 1;
                var msg = 'checkin';
                do_checkin = true;
            }else if(parseInt(oflag) == 2){
                var set_flag = 1;
                var msg = 'checkout';
            }else{
                var set_flag = 4;
                var msg = 'membatalkan';
            }

            if(do_checkin){
                var obranch       = $(this).attr('data-order-branch-id');   
                var oref          = $(this).attr('data-order-ref-id');             
                // 'Apakah anda ingin '+msg+' <b>'+onu+'</b> ?'
                let title   = 'Konfirmasi Check-IN';
                $.confirm({
                    title: title,
                    // icon: 'fas fa-check',
                    columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                    animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                    content: function(){
                        let self = this;
                        let form = new FormData();
                        form.append('action','room_get');
                        form.append('branch_id',obranch);                        
                        form.append('ref_id',oref);                        
                
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
                        }).fail(function(){
                            self.setContent('Something went wrong, Please try again.');
                        });
                    },
                    onContentReady: function(){
                        let self = this;
                        let content = '';
                        let dsp     = '';
                
                        let d = self.ajaxResponse.data;
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                
                        if(parseInt(s)==1){
                            dsp += '<form id="jc_form">';
                                dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                                dsp += '    <div class="form-group">';
                                dsp += '    <label class="form-label">Pilih Kamar</label>';
                                dsp += '        <select id="jc_select" name="jc_select" class="form-control">';
                                dsp += '            <option value="1">Pilih Kamar</option>';
                                r.forEach(async (v, i) => {
                                                    dsp += '<option value="'+v['product_id']+'">'+v['product_name']+' - ['+ v['branch_name'] +']</option>';
                                });
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
                        }else{
                            self.setContentAppend('<div>Content ready!</div>');
                        }
                    },
                    buttons: {
                        button_1: {
                            text:'<i class="fas fa-check white"></i> Proses',
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            action: function(){
                                let self      = this;
                
                                let select    = self.$content.find('#jc_select').val();
                                
                                if(select == 0){
                                    $.alert('Kamar mohon dipilih dahulu');
                                    return false;
                                } else{
                                    var form = new FormData();
                                    form.append('action', 'update_flag_item');
                                    form.append('order_id', oid);
                                    form.append('order_item_id', otd);                            
                                    form.append('order_session', oss);
                                    form.append('order_number', onu);
                                    form.append('order_item_flag_checkin', oflag);
                                    form.append('product_id', select);

                                    $.ajax({
                                        type: "POST",
                                        url : url,
                                        data: form,
                                        dataType:'json',
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        success:function(d){
                                            if(parseInt(d.status)==1){ 
                                                notif(d.status,d.message); 
                                                order_table.ajax.reload(null,false);
                                            }else{ 
                                                notif(d.status,d.message); 
                                            }
                                        }
                                    });
                                }            
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
            }else{
                if(oflag == 2){
                    var cnt = 'Checkout kamar <b>'+opr+ '</b> ?';
                }else{
                    var cnt ='Apakah anda ingin '+msg+' <b>'+onu+'</b> ?' 
                }
                $.confirm({
                    title: 'Konfirmasi!',
                    content: cnt,
                    buttons: {
                        confirm:{ 
                            btnClass: 'btn-primary',
                            text: 'Ya',
                            action: function () {
                                
                                var form = new FormData();
                                form.append('action', 'update_flag_item');
                                form.append('order_id', oid);
                                form.append('order_item_id', otd);                            
                                form.append('order_session', oss);
                                form.append('order_number', onu);
                                form.append('order_item_flag_checkin', oflag);
                                form.append('product_name',opr);

                                $.ajax({
                                    type: "POST",
                                    url : url,
                                    data: form,
                                    dataType:'json',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success:function(d){
                                        if(parseInt(d.status)==1){ 
                                            notif(d.status,d.message); 
                                            order_table.ajax.reload(null,false);
                                        }else{ 
                                            notif(d.status,d.message); 
                                        }
                                    }
                                });
                            }
                        },
                        cancel:{
                            btnClass: 'btn-danger',
                            text: 'Batal', 
                            action: function () {
                                // $.alert('Canceled!');
                            }
                        }
                    }
                });
            }
        });        
        /*
        $(document).on("click",".btn_save_order_item",function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
        });
        $(document).on("click",".btn_edit_order_item",function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
        });
        $(document).on("click",".btn_update_order_item",function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
        });
        $(document).on("click",".btn_delete_order_item",function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
        });
        */

        //Additional
        $("input[type=radio][name=order_branch_id]").on("change", function(e) {
            
            $("#order_price").val(0);
            
            e.preventDefault();
            e.stopPropagation();
            if(orderID == 0){
                let form = new FormData();
                form.append('action', 'room_ref');
                form.append('branch_id', $("input[name='order_branch_id']:checked").val());                
                $.ajax({
                    type: "post",
                    url: url,
                    data: form, 
                    dataType: 'json', cache: 'false', 
                    contentType: false, processData: false,
                    beforeSend:function(x){
                        // x.setRequestHeader('Authorization',"Bearer " + bearer_token);
                        // x.setRequestHeader('X-CSRF-TOKEN',csrf_token);
                    },
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            let total_records = r.length;
                            if(parseInt(total_records) > 0){
                                $("#order_ref_id").html('');
                            
                                var dsp = '';
                                r.forEach(async (v, i) => {
                                    dsp += '<input id="ref_'+v['ref_id']+'" name="order_ref_id" value="'+v['ref_id']+'" type="radio"><label class="radio_group_label radio_bg" for="ref_'+v['ref_id']+'">'+v['ref_name']+'</label>';
                                });
                                $("#order_ref_id").html(dsp);
                            }else{
                                $("#order_ref_id").html('<input id="ref_0" name="order_ref_id" value="0" type="radio"><label for="ref_0">Data tidak ditemukan</label>');                                
                            }
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
        // $(document).on("change", "input[type=radio][name=order_branch_id]", function(e) {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     if(orderID == 0){
        //         loadRoom();
        //     }            
        // });          
        $(document).on("change", "input[type=radio][name=order_ref_price_id]", function(e) { //Not Used
            e.preventDefault();
            e.stopPropagation();
            if(orderID == 0){
                loadRefPrice();
            }            
        });         
        $(document).on("change", "input[type=radio][name=order_ref_id]", function(e) {
            e.preventDefault();
            e.stopPropagation();
            if(orderID == 0){
                loadRefPrice();
            }            
        });                
        function loadRefPrice(){ //Load ref_price and room

            $("#order_price").val(0);

            if(parseInt($("input[name='order_ref_id']:checked").val()) > 0){
                let form = new FormData();
                form.append('action', 'room_price');
                form.append('branch_id',  $("input[name=order_branch_id]:checked").val());
                form.append('ref_id',  $("input[name=order_ref_id]:checked").val());                                                
                form.append('ref_price_sort',  $("input[name=order_ref_price_id]:checked").val());
                $.ajax({
                    type: "post",
                    url: url,
                    data: form, 
                    dataType: 'json', cache: 'false', 
                    contentType: false, processData: false,
                    beforeSend:function(x){
                        // x.setRequestHeader('Authorization',"Bearer " + bearer_token);
                        // x.setRequestHeader('X-CSRF-TOKEN',csrf_token);
                    },
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            // notif(s,m);
                            $("#order_price").val(r.price_value);

                            //Display Room
                            let re = d.rooms;
                            let total_records = re.length;
                            if(parseInt(total_records) > 0){
                                $("#order_product_id").html('');
                            
                                let dsp = '';
                                for(let a=0; a < total_records; a++) {
                                    let value = re[a];
                                    dsp += `<input id="order_product_id_${value['product_id']}" type="radio" name="order_product_id" value="${value['product_id']}">
                                            <label class="radio_group_label radio_bg" for="order_product_id_${value['product_id']}">${value['product_name']}</label>
                                    `;                                        
                            
                                }
                                $("#order_product_id").html(dsp);
                            }
                        }else{
                            notif(s,m);
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                    }
                });
            }
        }
        function loadRoom(){ //Not Used

            $("#order_price").val(0);
            
            if(parseInt($("input[name='order_ref_id']:checked").val()) > 0){
                let form = new FormData();
                form.append('action','room_get');
                form.append('branch_id',  $("input[name=booking_branch_id]:checked").val());
                form.append('ref_id',  $("input[name=booking_ref_id]:checked").val());                                                
                // form.append('ref_price_sort',  $("input[name=order_ref_price_id]:checked").val());

                // form.append('branch_id',obranch);                        
                // form.append('ref_id',oref);                        
                $.ajax({
                    type: "post",
                    url: url,
                    data: form, 
                    dataType: 'json', cache: 'false', 
                    contentType: false, processData: false,
                    beforeSend:function(x){
                        // x.setRequestHeader('Authorization',"Bearer " + bearer_token);
                        // x.setRequestHeader('X-CSRF-TOKEN',csrf_token);
                    },
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            // notif(s,m);
                            $("#order_price").val(r.price_value);
                        }else{
                            notif(s,m);
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                    }
                });
            }
        }        
        
        $(document).on("click","#btn_new_order",function(e) {
            formBookingReset();
            $("#modal_order").modal('show');
        });
        $(document).on("click","#btn_cancel_order",function(e) {
            formBookingReset();
            $("#modal_order").modal('hide');            
        });
        $(document).on("click",".btn_print_order",function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            var id = $(this).attr('data-order-id');
            var session = $(this).attr('data-order-session');
            if(parseInt(id) > 0){
                var x = screen.width / 2 - 700 / 2;
                var y = screen.height / 2 - 450 / 2;
                var print_url = url_print+'?action=print&data='+session;
                var win = window.open(print_url,'Print','width=700,height=485,left=' + x + ',top=' + y + '').print();
                //var win = window.open(print_url,'_blank');
            }else{
                notif(0,'Dokumen belum di buka');
            }
        });
        $(document).on("click","#btn_export_order",function(e) {
            e.stopPropagation();
            $.alert('Fungsi belum dibuat');
        });
        $(document).on("click","#btn_print_order",function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            // var id = $(this).attr('data-order-id');
            $.alert('Fungsi belum dibuat');
        });
        $(document).on("click","#btn_cancel_order_item",function(e) {
            formBookingItemReset();
        });
        function loadBookingItem(order_id = 0){
            if(parseInt(order_id) > 0){
                $.ajax({
                    type: "post",
                    url: "<?= base_url('order'); ?>",
                    data: {
                        action:'load_order_item_2',
                        order_item_order_id:order_id
                    },
                    dataType: 'json', cache: 'false', 
                    beforeSend:function(){},
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            // zz_for, zz_each, zz_for_group, zz_each_group
                        }else{
                            notif(s,m);
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                    }
                });
            }else{

            }
        }

        function formBookingReset(){
            $("#form_order input[type='text']")
            .not("input[id='order_start_date']")
            .not("input[id='order_end_date']").val('');

            $("#order_start_hour").val("14:00").trigger('change');
            $("#order_end_hour").val("12:00").trigger('change');

            // $("#order_start_date").datepicker("update", moment().format("d-M-yyyy"));
            // $("#order_end_date").datepicker("update", moment().add(365, "days").format("d-M-yyyy"));            

            // $("#form_order textarea").val('');

            // $("#files_link").attr('href',url_image);
            // $("#files_preview").attr('src',url_image);
            // $("#files_preview").attr('data-save-img',url_image);
            orderID = 0;
            loadAttachment(0);
        } 

        //Approval Button
        var approval_table = 'orders';
        $(document).on("click",".btn-attachment-info-2",function(e) {
            e.preventDefault();
            e.stopPropagation();
            var fid = $(this).attr('data-id');
            var ffrom = $(this).attr('data-from');
            var fnum = $(this).attr('data-number');
            var fcnm = $(this).attr('data-contact-name');
            var fcid = $(this).attr('data-contact-id');
            var fdt = $(this).attr('data-date');		
            var ftt = $(this).attr('data-total');						
            var ftpe = $(this).attr('data-type');
            var ftp = $(this).attr('data-contact-type');	
            var fp = $(this).attr('data-product');	            																							
            
            orderID = fid;
            var title   = 'Info Attachment '+fnum;
            $.confirm({
                title: title,
                columnClass: 'col-lg-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true,
                closeIconClass: 'fas fa-times',    
                animation:'zoom',
                closeAnimation:'bottom',
                animateFromElement:false,      
                content: function(){
                    var self = this;
                    var url = "<?= base_url('approval'); ?>"; //CI

                    var form = new FormData();
                    form.append('action','load_file_history');
                    form.append('file_from_id',fid);
                    form.append('file_from_table',ffrom);

                    return $.ajax({
                        url: url,
                        data: form,
                        dataType: 'json',
                        type: 'post',
                        cache: 'false', contentType: false, processData: false,
                    }).done(function (d) {
                        var s = d.status;
                        var m = d.message;
                        var r = d.result;
                        var tr = d.total_records;

                            var dsp = '';
                            dsp += '<table class="table-default">';
                            dsp += '<tr><td><b>Nomor</b></td><td>: '+fnum+'</td></tr>';
                            dsp += '<tr><td><b>Tanggal</b></td><td>: '+fdt+'</td></tr>';
                            dsp += '<tr><td><b>Kamar</b></td><td>: '+fp+'</td></tr>';                            
                            dsp += '<tr><td><b>Kontak</b></td><td>: '+fcnm+'</td></tr>';
                            dsp += '<tr><td><b>Total</b></td><td>: '+ftt+'</td></tr>';			                  	                  
                            dsp += '</table>';
                            dsp += '<br><b>Attachment Terkait</b>';

                            dsp += '<table id="table_attachment" class="table table-bordered">';
                            dsp += '  <thead>';
                            dsp += '    <th>Name</th>';
                            dsp += '    <th style="text-align:right;">Size</th>';
                            dsp += '    <th>Date Created</th>';
                            dsp += '    <th>Format</th>';
                            dsp += '  </thead>';
                            dsp += '  <tbody>';
                            if(parseInt(s) == 1){                            
                                if(parseInt(tr) > 0){
                                    r.forEach(async (v, i) => {
                                
                                        var siz = '1 kb';
                                        if(v['file_type'] == 1){
                                            siz = v['file']['size_unit'];
                                        }

                                        var attr = 'data-file-type="'+v['file_type']+'" data-file-id="'+v['file_id']+'" data-file-session="'+v['file_session']+'" data-file-name="'+v['file']['name']+'" data-file-format="'+v['file']['format']+'" data-file-src="'+v['file']['src']+'"';                                                                                      
                                        dsp += '<tr>';
                                        dsp += '<td><a class="btn_attachment_preview" href="#" '+attr+'>'+v['file']['name']+'</a></td>';
                                        dsp += '<td style="text-align:right;">'+siz+'</td>';
                                        dsp += '<td>'+ moment(v['date']['date_created']).format("DD-MMM-YY, HH:mm")+'</td>';
                                            dsp += '<td>'+v['file']['format_label']+'</td>';
                                            // dsp += '<td>';
                                            //     dsp += '<button type="button" class="btn-action btn btn-primary" data-id="'+v['approval_id']+'">';
                                            //     dsp += 'Action';
                                            //     dsp += '</button>';
                                            // dsp += '</td>';
                                    dsp += '</tr>';       
                                    });
                                }
                            }else{
                                dsp += '    <tr><td colspan="4">Tidak ada attachment</td></tr>';
                            }
                            dsp += '  </tbody>';
                            dsp += '</table>';
                            dsp += `<div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                    <div class="pull-right">                            
                                        <button id="btn_link_add" class="btn btn-primary btn-small" type="button">
                                            <i class="fas fa-link"></i>
                                            Tambah Link Sharing
                                        </button>    
                                        <button id="btn_attachment_add" class="btn btn-primary btn-small" type="button">
                                            <i class="fas fa-paperclip"></i>
                                            Tambah Attachment
                                        </button>                                                                                                                                                                                                         
                                    </div>
                                </div>
                            </div>`;                            
                        // }else{
                        //     // notif(s,m);
                        // }            
                        // self.setTitle('Info Attachment');
                        self.setContentAppend(dsp);
                    }).fail(function(){
                        self.setContent('Something went wrong, Please try again.');
                    });
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
        });        
        $(document).on("click", "#btn_approval_add", function(e){ //Not Used
            e.preventDefault();
            e.stopPropagation();
            var next = true;
            var id = $("#id_document").val();
            id = orderID;
            notif(1,'Memuat Persetujuan');
            if (parseInt(id) > 0) {
                $.ajax({
                    type: "post",
                    url: url,
                    data: {
                        action: 'read',
                        tipe: identity,
                        id: id
                    },
                    dataType: 'json',
                    cache: 'false',
                    success: function (d) {
                        if (parseInt(d.status) === 1) {
                            // notif(1,d.message);
                            var approval_flag = parseInt(d.result.trans_approval_flag);
                            if (approval_flag > 0) {
                                notif(1, 'Dokumen ' + d.result.order_number + ' sudah di setujui');
                            } else {
                                var trans_id = d.result.order_id;
                                var trans_session = d.result.order_session;
                                var trans_number = d.result.order_number;                        
                                $.confirm({
                                    title: 'Pilih User Persetujuan',
                                    icon: 'fas fa-lock',                            
                                    columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                                    autoClose: 'button_2|60000',
                                    closeIcon: true,
                                    closeIconClass: 'fas fa-times',
                                    animation: 'zoom',
                                    closeAnimation: 'bottom',
                                    animateFromElement: false,
                                    content: function () {
                                        var self = this;
                                    },
                                    onContentReady: function () {
                                        let self = this;
                                        var dsp = '';
                                        dsp += '<form id="jc_form">';
                                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                                            dsp += '<input type="hidden" id="approval_trans_id" name="approval_trans_id" value="' + trans_id + '">';
                                            dsp += '<input type="hidden" id="approval_trans_session" name="approval_trans_session" value="' + trans_session + '">';
                                            dsp += '</div>';
                                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                                            dsp += '    <p>Dokumen <b>'+trans_number+'</b> akan diajukan persetujuan.</p>';
                                            dsp += '</div>';                                            
                                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                                            dsp += '    <div class="form-group">';
                                            dsp += '    <label class="form-label">Urutan Persetujuan</label>';
                                            dsp += '        <select id="approval_level" name="approval_level" class="form-control">';
                                            dsp += '            <option value="1">User Pertama</option>';
                                            dsp += '            <option value="2">User Kedua</option>';
                                            dsp += '            <option value="3">User Ketiga</option>';
                                            dsp += '            <option value="4">User Keempat</option>';                                                                                        
                                            dsp += '        </select>';
                                            dsp += '    </div>';
                                            dsp += '</div>';
                                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                                            dsp += '    <div class="form-group">';
                                            dsp += '    <label class="form-label">Kepada User</label>';
                                            dsp += '        <select id="approval_contact" name="approval_contact" class="form-control">';
                                            dsp += '            <option value="0">Pilih User Persetujuan</option>';
                                            dsp += '        </select>';
                                            dsp += '    </div>';
                                            dsp += '</div>';
                                        dsp += '</form>';
                                        content = dsp;
                                        self.setContentAppend(content);
                                        $('#approval_contact').select2({
                                            dropdownParent:$(".jconfirm-box-container"),
                                            width:'100%',
                                            minimumInputLength: 0,
                                            placeholder: {
                                                id: '0',
                                                text: '-- Pilih --'
                                            },
                                            allowClear: true,
                                            ajax: {
                                                type: "get",
                                                url: "<?= base_url('search/manage');?>",
                                                dataType: 'json',
                                                delay: 250,
                                                data: function (params) {
                                                    var query = {
                                                        search: params.term,
                                                        tipe: 1,
                                                        source: 'users'
                                                    };
                                                    return query;
                                                },
                                                processResults: function (data){
                                                    var datas = [];
                                                    $.each(data, function(key, val){
                                                        datas.push({
                                                            'id' : val.id,
                                                            'text' : val.text
                                                        });
                                                    });
                                                    return {
                                                        results: datas
                                                    };
                                                },
                                                cache: true
                                            },
                                            escapeMarkup: function(markup){ 
                                                return markup; 
                                            },
                                            templateResult: function(datas){ //When Select on Click
                                                if($.isNumeric(datas.id) == true){
                                                    return datas.text;
                                                }
                                            },
                                            templateSelection: function(datas) { //When Option on Click
                                                if($.isNumeric(datas.id) == true){
                                                    return datas.text;
                                                }
                                            }
                                        });
                                    },
                                    buttons: {
                                        button_1: {
                                            text: '<span class="fas fa-thumbs-up"></span> Proses',
                                            btnClass: 'btn-primary',
                                            keys: ['enter'],
                                            action: function () {
                                                var trans_id = this.$content.find('#approval_trans_id').val();
                                                var trans_session = this.$content.find('#approval_trans_session').val();
                                                var user_id = this.$content.find('#approval_contact').find(':selected').val();
                                                var level = this.$content.find('#approval_level').find(':selected').val();                                                
                                                if (parseInt(user_id) == 0) {
                                                    notif(0, 'User harus dipilih');
                                                    return false;
                                                } else {
                                                    var data = {
                                                        action: 'create',
                                                        trans_id: trans_id,
                                                        trans_session: trans_session,
                                                        user_id: user_id,
                                                        from_table: approval_table,
                                                        approval_level: level
                                                    };
                                                    $.ajax({
                                                        type: "post",
                                                        url: "<?= base_url('approval'); ?>",
                                                        data: data,
                                                        dataType: 'json',
                                                        cache: 'false',
                                                        beforeSend: function () {},
                                                        success: function (d) {
                                                            if (parseInt(d.status) === 1) {
                                                                notif(d.status, d.message);
                                                                loadApproval(trans_id);
                                                                order_table.ajax.reload(null,false);
                                                            } else { //No Data
                                                                notif(d.status, d.message);
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
                                            text: '<span class="fas fa-times"></span> Batal',
                                            btnClass: 'btn-default',
                                            keys: ['Escape'],
                                            action: function () {
                                                //Close
                                            }
                                        }
                                    }
                                });
                            }
                        } else {
                            notif(0, d.message);
                        }
                    }, error: function (xhr, Status, err) {
                        notif(0, err);
                    }
                });
            } else {
                notif(0, 'Simpan data terlebih dahulu');
            }
        });
        $(document).on("click","#btn_attachment_add", function(e){
            e.preventDefault();
            e.stopPropagation();
            var next = true;
            var id = $("#id_document").val();
            id = orderID;
            // notif(1,'Memuat Attachment');
            if(parseInt(id)){
                let title   = 'Browse File';
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
                
                        dsp += '<form id="jc_form" method="post" enctype="multipart/form-data">';
                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Pilih File yg akan di upload (maks 2 MB)</label>';
                            dsp += '        <input id="jc_input" name="jc_input" type="file" class="form-control">';
                            dsp += '    </div>';
                            dsp += '</div>';
                        dsp += '</form>';
                        content = dsp;
                        self.setContentAppend(content);
                    },
                    buttons: {
                        button_1: {
                            text:'<i class="fas fa-upload white"></i> Upload',
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            action: function(e){
                                let self      = this;
                
                                // let input     = self.$content.find('#jc_input').val();
                                let input     = self.$content.find('#jc_input')[0].files[0];                        
                                // if(!input){
                                    // $.alert('File belum dipilih');
                                    // return false;
                                // } else{
                                    // $('#upload1')[0].files[0]
                                    let form = new FormData();
                                    form.append('action', 'file_create');
                                    form.append('from_table',approval_table);
                                    form.append('trans_id',id);
                                    form.append('source', input);
                                    $.ajax({
                                        type: "post",
                                        url: "<?= base_url('approval'); ?>",
                                        data: form, dataType: 'json',
                                        cache: 'false', contentType: false, processData: false,
                                        beforeSend: function() {},
                                        success: function(d) {
                                            let s = d.status;
                                            let m = d.message;
                                            let r = d.result;
                                            if(parseInt(s) == 1){
                                                notif(s, m);
                                                loadAttachment(id);
                                                order_table.ajax.reload(null,false);
                                            }else{
                                                notif(s,m);
                                                // notifSuccess(m);
                                            }
                                        },
                                        error: function(xhr, status, err) {
                                            notif(0,err);
                                        }
                                    });
                                // }
                            }
                        },
                        button_2: {
                            text: '<i class="fas fa-times white"></i> Batal',
                            btnClass: 'btn-success',
                            keys: ['Escape'],
                            action: function(){
                                //Close
                            }
                        }
                    }
                });
            }else{
                notif(0,'Simpan data terlebih dahulu');
            }
        });
        $(document).on("click","#btn_link_add", function(e){
            e.preventDefault();
            e.stopPropagation();
            var next = true;
            var id = $("#id_document").val();
            id = orderID;
            // notif(1,'Memuat Attachment');
            if(parseInt(id)){
                let title   = 'Link URL Share';
                $.confirm({
                    title: title,
                    icon: 'fas fa-link',
                    columnClass: 'col-md-5 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
                    closeIcon: true, closeIconClass: 'fas fa-times', 
                    animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                    content: function(){
                    },
                    onContentReady: function(e){
                        let self    = this;
                        let content = '';
                        let dsp     = '';
                
                        dsp += '<form id="jc_form" method="post" enctype="multipart/form-data">';
                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">URL Sharing</label>';
                            dsp += '        <input id="jc_input" name="jc_input" type="text" class="form-control">';
                            dsp += '    </div>';
                            dsp += '</div>';
                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Nama File</label>';
                            dsp += '        <input id="jc_input2" name="jc_input2" type="text" class="form-control">';
                            dsp += '    </div>';
                            dsp += '</div>';                    
                        dsp += '</form>';
                        content = dsp;
                        self.setContentAppend(content);
                        setTimeout(() => {
                            $("#jc_input").focus();
                        }, 1000);
                    },
                    buttons: {
                        button_1: {
                            text:'<i class="fas fa-save white"></i> Simpan',
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            action: function(e){
                                let self      = this;
                
                                let input     = self.$content.find('#jc_input').val();
                                let input2     = self.$content.find('#jc_input2').val();                                                
                                if(!input){
                                    $.alert('URL belum diisi');
                                    return false;
                                } else{
                                    let form = new FormData();
                                    form.append('action', 'file_create_link');
                                    form.append('from_table',approval_table);
                                    form.append('trans_id',id);
                                    form.append('file_url',input);
                                    form.append('file_name',input2);                            
                                    $.ajax({
                                        type: "post",
                                        url: "<?= base_url('approval'); ?>",
                                        data: form, dataType: 'json',
                                        cache: 'false', contentType: false, processData: false,
                                        beforeSend: function() {},
                                        success: function(d) {
                                            let s = d.status;
                                            let m = d.message;
                                            let r = d.result;
                                            if(parseInt(s) == 1){
                                                notif(s, m);
                                                loadAttachment(id);
                                                order_table.ajax.reload(null,false);
                                            }else{
                                                notif(s,m);
                                                // notifSuccess(m);
                                            }
                                        },
                                        error: function(xhr, status, err) {
                                            notif(0,err);
                                        }
                                    });
                                }
                            }
                        },
                        button_2: {
                            text: '<i class="fas fa-times white"></i> Batal',
                            btnClass: 'btn-default',
                            keys: ['Escape'],
                            action: function(){
                                //Close
                            }
                        }
                    }
                });
            }else{
                notif(0,'Simpan data terlebih dahulu');
            }
        });   
        $(document).on("click",".btn_attachment_preview", function(e){
            e.preventDefault();
            e.stopPropagation();
            var params = {
                file_id:$(this).attr('data-file-id'),
                file_type:$(this).attr('data-file-type'),        
                file_session:$(this).attr('data-file-session'),
                file_format:$(this).attr('data-file-format'),
                file_name:$(this).attr('data-file-name'),
                file_src:$(this).attr('data-file-src')
            };
            attachmentPreview(params);
        });
        $(document).on("click","#btn_attachment_delete", function(e){ //Not Used
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-id');
            console.log("#btn_attachment_delete");
        });  

        //Attachment Info
                
        //Approval Function
        // loadAttachment() on btn-save, btn-read
        function loadApproval(data_id){
            if(parseInt(data_id) > 0){
                $.ajax({
                    type: "post",
                    url: "<?= base_url('approval'); ?>",
                    data: {
                        action: 'load_approval_history',
                        approval_from_table:approval_table,
                        approval_from_id:data_id,
                    }, 
                    dataType: 'json', cache: 'false', 
                    beforeSend:function(){},
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            let total_records = d.total_records;
                            if(parseInt(total_records) > 0){
                                $("#table_approval tbody").html('');
                                var dsp = '';
                                r.forEach(async (v, i) => {
                                    dsp += '<tr>';
                                        dsp += '<td>'+ moment(v['approval_date_created']).format("DD-MMM-YYYY, HH:mm")+'</td>';
                                        dsp += '<td>'+v['user_to']['username']+'</td>';
                                        dsp += '<td>'+v['flag']['label']+'</td>';
                                        // dsp += '<td>';
                                        //     dsp += '<button type="button" class="btn-action btn btn-primary" data-id="'+v['approval_id']+'">';
                                        //     dsp += 'Action';
                                        //     dsp += '</button>';
                                        // dsp += '</td>';
                                    dsp += '</tr>';
                                });
                                $("#table_approval tbody").html(dsp);
                                $("#badge_approval").html(total_records).removeClass('badge-default').addClass('badge-success');                    
                            }else{
                                $("#table_approval tbody").html('<tr><td colspan="3">Tidak ada data persetujuan</td></tr>');
                                $("#badge_approval").html(0).removeClass('badge-success').addClass('badge-default');  
                            }
                        }else{
                            // notif(s,m);
                            $("#table_approval tbody").html('<tr><td colspan="4">Tidak ada data persetujuan</td></tr>');
                            $("#badge_approval").html(0).removeClass('badge-success').addClass('badge-default');                 
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                    }
                });
            }else{
                $("#table_approval tbody").html('<tr><td colspan="4">Tidak ada data persetujuan</td></tr>');
                $("#badge_approval").html(0).removeClass('badge-success').addClass('badge-default');             
            }
        }
        function loadAttachment(data_id){
            if(parseInt(data_id) > 0){    
                $.ajax({
                    type: "post",
                    url: "<?= base_url('approval'); ?>",
                    data: {
                        action: 'load_file_history',
                        file_from_table:approval_table,
                        file_from_id:data_id,
                    }, 
                    dataType: 'json', cache: 'false', 
                    beforeSend:function(){},
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            let total_records = d.total_records;
                            if(parseInt(total_records) > 0){
                                $("#table_attachment tbody").html('');
                                var dsp = '';
                                r.forEach(async (v, i) => {      
                                    
                                    var siz = '0 kb';
                                    if(v['file_type'] == 1){
                                        siz = v['file']['size_unit'];
                                    }

                                    var attr = 'data-file-type="'+v['file_type']+'" data-file-id="'+v['file_id']+'" data-file-session="'+v['file_session']+'" data-file-name="'+v['file']['name']+'" data-file-format="'+v['file']['format']+'" data-file-src="'+v['file']['src']+'"';                                                                                      
                                    dsp += '<tr>';
                                    dsp += '<td><a class="btn_attachment_preview" href="#" '+attr+'>'+v['file']['name']+'</a></td>';
                                    dsp += '<td style="text-align:right;">'+siz+'</td>';
                                    dsp += '<td>'+ moment(v['date']['date_created']).format("DD-MMM-YY, HH:mm")+'</td>';
                                        dsp += '<td>'+v['file']['format_label']+'</td>';
                                        // dsp += '<td>';
                                        //     dsp += '<button type="button" class="btn-action btn btn-primary" data-id="'+v['approval_id']+'">';
                                        //     dsp += 'Action';
                                        //     dsp += '</button>';
                                        // dsp += '</td>';
                                    dsp += '</tr>';
                                });
                                $("#table_attachment tbody").html(dsp);
                                $("#badge_attachment").html(total_records).removeClass('badge-default').addClass('badge-success');
                            }else{
                                $("#table_attachment tbody").html('<tr><td colspan="3">Tidak ada data attachment</td></tr>');
                                $("#badge_attachment").html(0).removeClass('badge-success').addClass('badge-default');  
                            }
                        }else{
                            // notif(s,m);
                            $("#table_attachment tbody").html('<tr><td colspan="4">Tidak ada data attachment</td></tr>');
                            $("#badge_attachment").html(0).removeClass('badge-success').addClass('badge-default');                 
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                    }
                });
            }else{
                $("#table_attachment tbody").html('<tr><td colspan="4">Tidak ada data attachment</td></tr>');
                $("#badge_attachment").html(0).removeClass('badge-success').addClass('badge-default');            
            }    
        }
        function attachmentPreview(params){
            var fid = params.file_id;
            var fss = params.file_session;   
            var ffr = params.file_format;        
            var fnm = params.file_name;    
            var fsr = params.file_src;
            var fty = params.file_type;

            $.confirm({
                title: fnm,
                icon: 'fas fa-paperclip',
                columnClass: 'col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true, closeIconClass: 'fas fa-times',    
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content:function(){},
                onContentReady: function(){
                    let self = this;
                    var dsp = '';
                    if(ffr == 'png'){
                        dsp = '<img src="'+ fsr+'" class="img-responsive" style="margin:0 auto;">';
                    }else if((ffr == 'xls') || (ffr == 'xlsx')){
                        dsp = '<img src="'+ fsr+'" class="img-responsive" style="margin:0 auto;">';
                    }else if(ffr == 'pdf'){
                        dsp += '<!DOCTYPE html>';
                        dsp += '<html>';
                        dsp += '    <body>';
                        dsp += '        <object data="'+fsr+'" type="application/pdf" width="100%" height="500px">';
                        dsp += '        <p>Unable to display PDF file. <a href="'+fsr+'">Download</a> instead.</p>';
                        dsp += '        </object>';
                        dsp += '    </body>';
                        dsp += '</html>';
                    }else if(ffr == 'link'){
                        // dsp += '<iframe src="'+fsr+'" title="'+fnm+'" style="width:100%;"></iframe>';
                        dsp += 'Klik <b>Buka Tab Baru</b> untuk melihat';
                    }
                    console.log(fsr);
                    self.setContentAppend(dsp);
                },
                buttons: {
                    button_1: {
                        text:'<i class="fas fa-external-link-alt white"></i> Buka Tab baru',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            attachmentOpenTab(params);                    
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-edit white"></i> Ganti Nama',
                        btnClass: 'btn-primary',
                        keys: ['Escape'],
                        action: function(){
                            attachmentRenameForm(params);        
                        }
                    },
                    button_3: {
                        text: '<i class="fas fa-trash white"></i> Hapus',
                        btnClass: 'btn-danger',
                        action: function(){
                            attachmentRemoveForm(params);
                        }
                    }
                }
            });    
        }
        function attachmentRemoveForm(params){
            let title   = 'Konfirmasi';
            let content = 'Data yg sudah dihapus tidak akan bisa dikembalikan lagi.';
            $.confirm({
                title: title,
                icon: 'fas fa-check',
                content: content,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
                autoClose: 'button_2|30000',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                buttons: {
                    button_1: {
                        text: '<i class="fas fa-trash white"></i> Hapus',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            $.ajax({
                                    type: "post",
                                    url: "<?= base_url('approval'); ?>",
                                    data: {
                                        action:'file_delete',
                                        file_id:params.file_id,
                                    }, 
                                    dataType: 'json',
                                    cache: 'false',
                                    beforeSend: function() {},
                                    success: function(d) {
                                        let s = d.status;
                                        let m = d.message;
                                        let r = d.result;
                                        if(parseInt(s) == 1){
                                            var ffid = $("#id_document").val();
                                            loadAttachment(ffid);
                                            // attachmentPreview(r);
                                            notif(s,m);
                                        }else{
                                            notif(s,m);
                                        }
                                    },
                                    error: function(xhr, status, err) {}
                                });
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-window-close white"></i> Batal',
                        btnClass: 'btn-default',
                        keys: ['Escape'],
                        action: function(){
                            attachmentPreview(params);
                        }
                    }
                }
            });
        }
        function attachmentRenameForm(params){
            let title   = 'Ganti Nama';
            $.confirm({
                title: title,
                icon: 'fas fa-check',
                columnClass: 'col-md-5 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
                autoClose: 'button_2|10000',
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
                        dsp += '    <label class="form-label">Nama File</label>';
                        dsp += '        <input id="jc_input" name="jc_input" class="form-control" value="'+params.file_name+'">';
                        dsp += '    </div>';
                        dsp += '</div>';
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);
                    $("#jc_input").focus();
                },
                buttons: {
                    button_1: {
                        text:'<i class="fas fa-save white"></i> Simpan',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){
                            let self      = this;
                            let input     = self.$content.find('#jc_input').val();
                            if(!input){
                                $.alert('Mohon diisi dahulu');
                                return false;
                            } else{
                                $.ajax({
                                    type: "post",
                                    url: "<?= base_url('approval'); ?>",
                                    data: {
                                        action:'file_rename',
                                        file_id:params.file_id,
                                        file_name:input
                                    }, 
                                    dataType: 'json',
                                    cache: 'false',
                                    beforeSend: function() {},
                                    success: function(d) {
                                        let s = d.status;
                                        let m = d.message;
                                        let r = d.result;
                                        if(parseInt(s) == 1){
                                            var ffid = $("#id_document").val();
                                            loadAttachment(ffid);
                                            // attachmentPreview(r);
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
                        text: '<i class="fas fa-window-close white"></i> Batal',
                        btnClass: 'btn-default',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                            attachmentPreview(params);
                        }
                    }
                }
            });
        }
        function attachmentOpenTab(params){
            window.open(params.file_src,'Print','width=700,height=485,left=200,top=100').print();    
        }  

        $(document).on("click",".btn_paid_info", function(e){
            e.preventDefault();
            e.stopPropagation();
            var fid = $(this).attr('data-id');
            var ffrom = $(this).attr('data-from');
            var fnum = $(this).attr('data-number');
            var fcnm = $(this).attr('data-contact-name');
            var fcid = $(this).attr('data-contact-id');
            var fdt = $(this).attr('data-date');		
            var ftt = $(this).attr('data-total');						
            var ftpe = $(this).attr('data-type');
            var ftp = $(this).attr('data-contact-type');	
            var fp = $(this).attr('data-product');	            																							
            
            orderID = fid;
            
            var stitle   = 'Riwayat Pembayaran '+fnum;
            $.confirm({
                title: stitle,
                columnClass: 'col-lg-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true,
                closeIconClass: 'fas fa-times',    
                animation:'zoom',
                closeAnimation:'bottom',
                animateFromElement:false,      
                content: function(){
                    var self = this;
                    var form = new FormData();
                    form.append('action','load_paid_history');
                    form.append('paid_order_id',fid);

                    return $.ajax({
                        url: url,
                        data: form,
                        dataType: 'json',
                        type: 'post',
                        cache: 'false', contentType: false, processData: false,
                    }).done(function (d) {
                        var s = d.status;
                        var m = d.message;
                        var r = d.result;
                        var tr = d.total_records;

                            var dsp = '';
                            dsp += '<table class="table-default">';
                            dsp += '<tr><td><b>Nomor</b></td><td>: '+fnum+'</td></tr>';
                            dsp += '<tr><td><b>Tanggal</b></td><td>: '+fdt+'</td></tr>';
                            dsp += '<tr><td><b>Kamar</b></td><td>: '+fp+'</td></tr>';                            
                            dsp += '<tr><td><b>Kontak</b></td><td>: '+fcnm+'</td></tr>';
                            dsp += '<tr><td><b>Total</b></td><td>: '+ftt+'</td></tr>';			                  	                  
                            dsp += '</table>';
                            dsp += '<br><b>Pembayaran Terkait</b>';

                            dsp += '<table id="table_paid" class="table table-bordered">';
                            dsp += '  <thead>';
                            dsp += '    <th>File</th>';
                            dsp += '    <th>Date</th>';
                            dsp += '    <th style="text-align:left;">Method</th>';
                            dsp += '    <th style="text-align:right;">Total</th>';
                            dsp += '  </thead>';
                            dsp += '  <tbody>';

                            if(parseInt(s) == 1){                            
                                if(parseInt(tr) > 0){
                                    r.forEach(async (v, i) => {
                                
                                        var siz = '1 kb';
                                        if(v['paid_size'] > 0){
                                            siz = v['paid']['size_unit'];
                                        }

                                        var attr = 'data-paid-payment-type="'+v['paid_payment_type']+'" data-paid-id="'+v['paid_id']+'" data-paid-session="'+v['paid_session']+'" data-paid-number="'+v['paid_number']+'" data-paid-format="'+v['paid']['format']+'" data-paid-src="'+v['paid']['src']+'" data-paid-name="'+v['paid_name']+'"';                                                                                      
                                        dsp += '<tr>';
                                        dsp += '<td><a class="btn_paid_preview" href="#" '+attr+'><span class="fas fa-paperclip"></span> '+v['paid_name']+'</a></td>';
                                        dsp += '<td>'+ moment(v['date']['date']).format("DD-MMM-YY")+'</td>';
                                        // dsp += '<td style="text-align:right;">'+siz+'</td>';
                                        dsp += '<td style="text-align:left;">'+v['paid_payment_method']+'</td>';                                    
                                        dsp += '<td style="text-align:right;">'+addCommas(v['paid_total'])+'</td>';
                                            // dsp += '<td>';
                                            //     dsp += '<button type="button" class="btn-action btn btn-primary" data-id="'+v['approval_id']+'">';
                                            //     dsp += 'Action';
                                            //     dsp += '</button>';
                                            // dsp += '</td>';
                                    dsp += '</tr>';       
                                    });
                                }else{
                                    dsp += '    <tr><td colspan="3">Tidak ada data</td></tr>';
                                }
                            }
                            dsp += '  </tbody>';
                            dsp += '</table>';
                            dsp += `<div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="pull-right">  
                                                <button id="btn_paid_add" class="btn btn-primary btn-small" type="button">
                                                    <i class="fas fa-paperclip"></i>
                                                    Tambah Pembayaran
                                                </button>                                                                                                                                                                                                         
                                            </div>
                                        </div>
                                    </div>`;
                        // }else{
                            // notif(s,m);
                        // }            
                        // self.setTitle('Info Attachment');
                        self.setContentAppend(dsp);
                    }).fail(function(){
                        self.setContent('Something went wrong, Please try again.');
                    });
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
        });        
        $(document).on("click","#btn_paid_add", function(e){
            e.preventDefault();
            e.stopPropagation();
            var next = true;
            var id = $(this).attr('data-id');
            id = orderID;
            // notif(1,'Memuat Attachment');
            if(parseInt(id)){
                let title   = 'Tambah Pembayaran';
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
                
                        dsp += '<form id="jc_form" method="post" enctype="multipart/form-data">';
                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Metode</label>';
                            dsp += '        <select id="jc_metode" name="jc_metode"class="form-control">';
                            dsp += '            <option value="CASH" selected>Cash</option>';
                            dsp += '            <option value="TRANSFER">Transfer</option>';
                            dsp += '        </select>';
                            dsp += '    </div>';
                            dsp += '</div>';
                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Jumlah (Rp)</label>';
                            dsp += '        <input id="jc_total" name="jc_total" type="text" class="form-control">';
                            dsp += '    </div>';
                            dsp += '</div>';
                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Pilih Bukti Pembayaran yg akan di upload (maks 2 MB)</label>';
                            dsp += '        <input id="jc_input" name="jc_input" type="file" class="form-control">';
                            dsp += '    </div>';
                            dsp += '</div>';
                        dsp += '</form>';
                        content = dsp;
                        self.setContentAppend(content);
                        new AutoNumeric('#jc_total', autoNumericOption);                    
                    },
                    buttons: {
                        button_1: {
                            text:'<i class="fas fa-upload white"></i> Upload',
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            action: function(e){
                                let self      = this;
                                // let input     = self.$content.find('#jc_input').val();
                                let input     = self.$content.find('#jc_input')[0].files[0];
                                let metode     = self.$content.find('#jc_metode').val();
                                let total      = self.$content.find('#jc_total').val();                                                                                        
                                // if(!input){
                                    // $.alert('File belum dipilih');
                                    // return false;
                                // } else{
                                    // $('#upload1')[0].files[0]
                                    let form = new FormData();
                                    form.append('action', 'paid_create');
                                    form.append('paid_order_id',id);
                                    form.append('paid_total',total);
                                    form.append('paid_payment_method',metode);                                                                        
                                    form.append('source', input);
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
                                                loadPaid(id);
                                                order_table.ajax.reload(null,false);
                                            }else{
                                                notif(s,m);
                                                // notifSuccess(m);
                                            }
                                        },
                                        error: function(xhr, status, err) {
                                            notif(0,err);
                                        }
                                    });
                                // }
                            }
                        },
                        button_2: {
                            text: '<i class="fas fa-times white"></i> Batal',
                            btnClass: 'btn-success',
                            keys: ['Escape'],
                            action: function(){
                                //Close
                            }
                        }
                    }
                });
            }else{
                notif(0,'Simpan data terlebih dahulu');
            }            
        });
        $(document).on("click",".btn_paid_preview", function(e){
            e.preventDefault();
            e.stopPropagation();
            var params = {
                paid_id:$(this).attr('data-paid-id'),
                // file_type:$(this).attr('data-paid-type'),        
                paid_name:$(this).attr('data-paid-name'),      
                paid_number:$(this).attr('data-paid-number'),
                paid_format:$(this).attr('data-paid-format'),
                // file_name:$(this).attr('data-file-name'),
                paid_src:$(this).attr('data-paid-src')
            };
            paidPreview(params);            
        });
        $(document).on("click","#btn_paid_delete", function(e){ //Not Used

        });

        function loadPaid(data_id){
            console.log('loadPaid('+data_id+')');
            if(parseInt(data_id) > 0){    
                $.ajax({
                    type: "post",
                    url: url,
                    data: {
                        action: 'load_paid_history',
                        paid_order_id:data_id,
                    }, 
                    dataType: 'json', cache: 'false', 
                    beforeSend:function(){},
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            let total_records = d.total_records;
                            if(parseInt(total_records) > 0){
                                $("#table_paid tbody").html('');
                                var dsp = '';
                                r.forEach(async (v, i) => {      
                                    
                                    // var siz = '0 kb';
                                    // if(v['paid_size'] > 0){
                                    //     siz = v['paid']['size_unit'];
                                    // }

                                    var attr = 'data-paid-payment-type="'+v['paid_payment_type']+'" data-paid-id="'+v['paid_id']+'" data-paid-session="'+v['paid_session']+'" data-paid-number="'+v['paid_number']+'" data-paid-format="'+v['paid']['format']+'" data-paid-src="'+v['paid']['src']+'" data-paid-name="'+v['paid_name']+'"';                                                                                      
                                    dsp += '<tr>';
                                    dsp += '<td><a class="btn_paid_preview" href="#" '+attr+'><span class="fas fa-paperclip"></span> '+v['paid_name']+'</a></td>';
                                    dsp += '<td>'+ moment(v['date']['date']).format("DD-MMM-YY")+'</td>';
                                    // dsp += '<td style="text-align:right;">'+siz+'</td>';
                                    dsp += '<td style="text-align:left;">'+v['paid_payment_method']+'</td>';                                    
                                    dsp += '<td style="text-align:right;">'+addCommas(v['paid_total'])+'</td>';
                                        // dsp += '<td>';
                                        //     dsp += '<button type="button" class="btn-action btn btn-primary" data-id="'+v['approval_id']+'">';
                                        //     dsp += 'Action';
                                        //     dsp += '</button>';
                                        // dsp += '</td>';
                                    dsp += '</tr>';
                                });
                                $("#table_paid tbody").html(dsp);
                                $("#badge_paid").html(total_records).removeClass('badge-default').addClass('badge-success');
                            }else{
                                $("#table_paid tbody").html('<tr><td colspan="3">Tidak ada data pembayaran</td></tr>');
                                $("#badge_paid").html(0).removeClass('badge-success').addClass('badge-default');  
                            }
                        }else{
                            // notif(s,m);
                            $("#table_paid tbody").html('<tr><td colspan="4">Tidak ada data pembayaran</td></tr>');
                            $("#badge_paid").html(0).removeClass('badge-success').addClass('badge-default');                 
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                    }
                });
            }else{
                $("#table_paid tbody").html('<tr><td colspan="4">Tidak ada data pembayaran</td></tr>');
                // $("#badge_attachment").html(0).removeClass('badge-success').addClass('badge-default');            
            }              
        }
        function paidPreview(params){
            console.log(params);
            var fid = params.paid_id;
            var fss = params.paid_session;   
            var ffr = params.paid_format;        
            var fsr = params.paid_src;
            var fnm = params.paid_name;   
            var fnb = params.paid_number;                
            // var fty = params.file_type;

            $.confirm({
                title: fnm,
                icon: 'fas fa-paperclip',
                columnClass: 'col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true, closeIconClass: 'fas fa-times',    
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content:function(){},
                onContentReady: function(){
                    let self = this;
                    var dsp = '';
                    if(ffr == 'png'){
                        dsp = '<img src="'+ fsr+'" class="img-responsive" style="margin:0 auto;">';
                    }else if((ffr == 'xls') || (ffr == 'xlsx')){
                        dsp = '<img src="'+ fsr+'" class="img-responsive" style="margin:0 auto;">';
                    }else if(ffr == 'pdf'){
                        dsp += '<!DOCTYPE html>';
                        dsp += '<html>';
                        dsp += '    <body>';
                        dsp += '        <object data="'+fsr+'" type="application/pdf" width="100%" height="500px">';
                        dsp += '        <p>Unable to display PDF file. <a href="'+fsr+'">Download</a> instead.</p>';
                        dsp += '        </object>';
                        dsp += '    </body>';
                        dsp += '</html>';
                    }else if(ffr == 'link'){
                        // dsp += '<iframe src="'+fsr+'" title="'+fnm+'" style="width:100%;"></iframe>';
                        dsp += 'Klik <b>Buka Tab Baru</b> untuk melihat';
                    }
                    console.log(fsr);
                    self.setContentAppend(dsp);
                },
                buttons: {
                    button_1: {
                        text:'<i class="fas fa-external-link-alt white"></i> Buka Tab baru',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            paidOpenTab(params);                    
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-edit white"></i> Ganti Nama',
                        btnClass: 'btn-primary',
                        keys: ['Escape'],
                        action: function(){
                            paidRenameForm(params);        
                        }
                    },
                    button_3: {
                        text: '<i class="fas fa-trash white"></i> Hapus',
                        btnClass: 'btn-danger',
                        action: function(){
                            paidRemoveForm(params);
                        }
                    }
                }
            });               
        }
        function paidRemoveForm(params){
            console.log(params);
            let title   = 'Konfirmasi';
            let content = 'Data yg sudah dihapus tidak akan bisa dikembalikan lagi.';
            $.confirm({
                title: title,
                icon: 'fas fa-check',
                content: content,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
                autoClose: 'button_2|30000',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                buttons: {
                    button_1: {
                        text: '<i class="fas fa-trash white"></i> Hapus',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            $.ajax({
                                    type: "post",
                                    url: url,
                                    data: {
                                        action:'paid_delete',
                                        paid_id:params.paid_id,
                                    }, 
                                    dataType: 'json',
                                    cache: 'false',
                                    beforeSend: function() {},
                                    success: function(d) {
                                        let s = d.status;
                                        let m = d.message;
                                        let r = d.result;
                                        if(parseInt(s) == 1){
                                            // var ffid = $("#id_document").val();
                                            ffid = orderID;
                                            loadPaid(ffid);
                                            // attachmentPreview(r);
                                            notif(s,m);
                                            order_table.ajax.reload(null,false);
                                        }else{
                                            notif(s,m);
                                        }
                                    },
                                    error: function(xhr, status, err) {}
                                });
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-window-close white"></i> Batal',
                        btnClass: 'btn-default',
                        keys: ['Escape'],
                        action: function(){
                            attachmentPreview(params);
                        }
                    }
                }
            });            
        }
        function paidRenameForm(params){
            let title   = 'Ganti Nama';
            $.confirm({
                title: title,
                icon: 'fas fa-check',
                columnClass: 'col-md-5 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
                autoClose: 'button_2|10000',
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
                        dsp += '    <label class="form-label">Nama Pembayaran</label>';
                        dsp += '        <input id="jc_input" name="jc_input" class="form-control" value="'+params.paid_name+'">';
                        dsp += '    </div>';
                        dsp += '</div>';
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);
                    $("#jc_input").focus();
                },
                buttons: {
                    button_1: {
                        text:'<i class="fas fa-save white"></i> Simpan',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){
                            let self      = this;
                            let input     = self.$content.find('#jc_input').val();
                            if(!input){
                                $.alert('Mohon diisi dahulu');
                                return false;
                            } else{
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    data: {
                                        action:'paid_rename',
                                        paid_id:params.paid_id,
                                        paid_name:input
                                    }, 
                                    dataType: 'json',
                                    cache: 'false',
                                    beforeSend: function() {},
                                    success: function(d) {
                                        let s = d.status;
                                        let m = d.message;
                                        let r = d.result;
                                        if(parseInt(s) == 1){
                                            loadPaid(params.paid_order_id);
                                            paidPreview(r);
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
                        text: '<i class="fas fa-window-close white"></i> Batal',
                        btnClass: 'btn-default',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                            paidPreview(params);
                        }
                    }
                }
            });
        }
        function paidOpenTab(params){
            console.log(params);
            window.open(params.paid_src,'Print','width=700,height=485,left=200,top=100').print();                
        }

        //Image Croppie
        $(document).on('change', '#files_1', function(e) {
            if($("#files_1").val() == ''){
                $("#files_preview_1").attr('src', url_image);
                $("#files_link_1").attr('href', url_image);            
                $("#files_preview_1").attr('data-save-img', '');
                return;
            }
            var reader = new FileReader();
            reader.onload = function(e) {
                upload_crop_img_1.croppie('bind', {
                    url: e.target.result
                }).then(function (blob) {
                    // aa = btoa(blob);s
                    $("#modal_croppie_1").modal("show");
                    setTimeout(function(){$('#modal_croppie_canvas_1').croppie('bind');}, 300);
                });
            };
            reader.readAsDataURL(this.files[0]);
        });
        $(document).on('change', '#files_2', function(e) {
            if($("#files_2").val() == ''){
                $("#files_preview_2").attr('src', url_image);
                $("#files_link_2").attr('href', url_image);            
                $("#files_preview_2").attr('data-save-img', '');
                return;
            }
            var reader = new FileReader();
            reader.onload = function(e) {
                upload_crop_img_2.croppie('bind', {
                    url: e.target.result
                }).then(function (blob) {
                    // aa = btoa(blob);s
                    $("#modal_croppie_2").modal("show");
                    setTimeout(function(){$('#modal_croppie_canvas_2').croppie('bind');}, 300);
                });
            };
            reader.readAsDataURL(this.files[0]);
        });       
        $(document).on('click', '#modal_croppie_cancel_1', function(e){
            e.preventDefault();
            e.stopPropagation();
            $("#files_1").val('');
            $("#files_preview_1").attr('data-save-img', '');
            $("#files_preview_1").attr('src', url_image);
            $("#files_link_1").attr('href', url_image);
        });
        $(document).on('click', '#modal_croppie_save_1', function(e){
            e.preventDefault();
            e.stopPropagation();
            upload_crop_img_1.croppie('result', {
                type: 'canvas',
                size: 'viewport',
            }).then(function (resp) {
                $("#files_preview_1").attr('src', resp);
                $("#files_link_1").attr('href', resp);
                $("#files_preview_1").attr('data-save-img', resp);
                $("#modal_croppie_1").modal("hide");
            });
        }); 
        $(document).on('click', '#modal_croppie_cancel_2', function(e){
            e.preventDefault();
            e.stopPropagation();
            $("#files_2").val('');
            $("#files_preview_2").attr('data-save-img', '');
            $("#files_preview_2").attr('src', url_image);
            $("#files_link_2").attr('href', url_image);
        });
        $(document).on('click', '#modal_croppie_save_2', function(e){
            e.preventDefault();
            e.stopPropagation();
            upload_crop_img_2.croppie('result', {
                type: 'canvas',
                size: 'viewport',
            }).then(function (resp) {
                $("#files_preview_2").attr('src', resp);
                $("#files_link_2").attr('href', resp);
                $("#files_preview_2").attr('data-save-img', resp);
                $("#modal_croppie_2").modal("hide");
            });
        });           
   
        // imgInp.onchange = evt => {
        //     const [file] = imgInp.files
        //     if (file) {
        //         blah.src = URL.createObjectURL(file)
        //     }
        // }
        // $("#files").on("change", function(e){
        //     const [file] = this.files;
        //     console.log(file);
        //     if (file) {
        //         $("#files_preview").attr('src',URL.createObjectURL(file));
        //     }
        // });
                    
    }); //End of Document Ready
    function formBookingSetDisplay(value){ // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        if(value == 1){ var flag = true; }else{ var flag = false; }
        //Attr Input yang perlu di setel
        var form = '#form_order'; 
        var attrInput = [
        "order_name","order_date","order_hour"
        ];
        for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag); }

        //Attr Textarea yang perlu di setel
        var attrText = [
        "order_note"
        ];
        for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

        //Attr Select yang perlu di setel
        var atributSelect = [
        "order_flag",
        "order_type",
        ];
        for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag); }
    }
</script>