
<script>
    $(document).ready(function () {
        $("#modal_order").modal('show');
        
        //Identity
        var identity = "<?php echo $identity; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="front_office/booking"]').addClass('active');

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


        //Croppie
        var upload_crop_img = $('#modal_croppie_canvas').croppie({
            enableExif: true,
            viewport: {width: image_width, height: image_height},
            boundary: {width: parseInt(image_width)+10, height: parseInt(image_height)+10},
            url: url_image,
        });
        $('.files_link').magnificPopup({
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
        $("#order_date").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1 
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
        // new AutoNumeric('#some_id', autoNumericOption);

        //Datatable
        /*
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
                    d.action = 'load';
                    d.length = $("#filter_length").find(':selected').val();
                    d.date_start = $("#filter_start_date").val();
                    d.date_end = $("#filter_end_date").val();
                    d.filter_flag = $("#filter_flag").find(':selected').val();
                    d.search = {value:$("#filter_search").val()};
                },
                dataSrc: function(data) {
                    return data.result;
                }
            },
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
            "columnDefs": [
                {"targets":0, "width":"30%", "title":"Name", "searchable":true, "orderable":true},
                {"targets":1, "width":"20%", "title":"Note", "searchable":true, "orderable":true},
                {"targets":2, "width":"20%", "title":"Flag", "searchable":true, "orderable":true},
            ],
            "order": [[0, 'ASC']],
            "columns": [
                {
                    'data': 'order_id',
                    className: 'text-left',
                    render: function(data, meta, row) {
                        var dsp = '';
                        dsp += row.order_name;
                        return dsp;
                    }
                },{
                    'data': 'order_id',
                    className: 'text-left',
                    render: function(data, meta, row) {
                        var dsp = '';
                        dsp += row.order_note;
                        // if(row.contact_email_1 != undefined){
                            // dsp += '<br>'+row.contact_email_1;
                        // }
                        return dsp;
                    }
                },{
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

                            /* Button Action Concept 1 */
                            // dsp += '&nbsp;<button class="btn btn_edit_order btn-mini btn-primary"';
                            // dsp += 'data-order-id="'+data+'" data-order-name="'+row.order_name+'" data-order-flag="'+row.order_flag+'" data-order-session="'+row.order_session+'">';
                            // dsp += '<span class="fas fa-edit primary"></span> Edit</button>';

                            // dsp += '&nbsp;<button class="btn btn_delete_order btn-mini btn-danger"';
                            // dsp += 'data-order-id="'+data+'" data-order-name="'+row.order_name+'" data-order-flag="4" data-order-session="'+row.order_session+'">';
                            // dsp += '<span class="fas fa-trash danger"></span> Hapus</button>';

                            // if (parseInt(row.order_flag) === 1) {
                            //   dsp += '&nbsp;<button class="btn btn_update_flag_order btn-mini btn-primary" style="background-color:#ff9019;"';
                            //   dsp += 'data-order-id="'+data+'" data-order-name="'+row.order_name+'" data-order-flag="0" data-order-session="'+row.order_session+'">';
                            //   dsp += '<span class="fas fa-ban"></span> Nonaktifkan</button>';                      
                            // }else if (parseInt(row.order_flag) === 0) {
                            //   dsp += '&nbsp;<button class="btn btn_update_flag_order btn-mini btn-primary" style="background-color:#6273df;"';
                            //   dsp += 'data-order-id="'+data+'" data-order-name="'+row.order_name+'" data-order-flag="1" data-order-session="'+row.order_session+'">';
                            //   dsp += '<span class="fas fa-check primary"></span> Aktifkan</button>';
                            // }

                        /* Button Action Concept 2
                        dsp += '&nbsp;<div class="btn-group">';
                        // dsp += '    <button class="btn btn-mini btn-default"><span class="fas fa-cog"></span></button>';
                        dsp += '    <button class="btn btn-mini btn-default dropdown-toggle btn-demo-space" data-toggle="dropdown" aria-expanded="true"><span class="fas fa-cog"></span><span class="caret"></span> </button>';
                        dsp += '    <ul class="dropdown-menu">';
                        dsp += '        <li>';
                        dsp += '            <a class="btn_edit_order" style="cursor:pointer;"';
                        dsp += '                data-order-id="'+data+'" data-order-name="'+row.order_name+'" data-order-flag="'+row.order_flag+'" data-order-session="'+row.order_session+'">';
                        dsp += '                <span class="fas fa-edit"></span> Edit';
                        dsp += '            </a>';
                        dsp += '        </li>';
                        // if(parseInt(row.order_flag) === 0) {
                                dsp += '<li>'; 
                                dsp += '    <a class="btn_update_flag_order" style="cursor:pointer;"';
                                dsp += '        data-order-id="'+data+'" data-order-name="'+row.order_name+'" data-order-flag="1" data-order-session="'+row.order_session+'">';
                                dsp += '        <span class="fas fa-lock"></span> Aktifkan';
                                dsp += '    </a>';
                                dsp += '</li>';
                        // }else if(parseInt(row.order_flag) === 1){
                                dsp += '<li>';
                                dsp += '    <a class="btn_update_flag_order" style="cursor:pointer;"';
                                dsp += '        data-order-id="'+data+'" data-order-name="'+row.order_name+'" data-order-flag="0" data-order-session="'+row.order_session+'">';
                                dsp += '        <span class="fas fa-ban"></span> Nonaktifkan';
                                dsp += '    </a>';
                                dsp += '</li>';
                        // }
                        if((parseInt(row.order_flag) < 1) || (parseInt(row.order_flag) == 4)) {
                                dsp += '<li>';
                                dsp += '    <a class="btn_update_flag_order" style="cursor:pointer;"';
                                dsp += '        data-order-id="'+data+'" data-order-name="'+row.order_name+'" data-order-flag="4" data-order-session="'+row.order_session+'">';
                                dsp += '        <span class="fas fa-trash"></span> Hapus';
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

                        /* Button Action Concept 2
                        dsp += '&nbsp;<div class="btn-group">';
                        // dsp += '    <button class="btn btn-mini btn-default" style="background-color:'+bgcolor+';color:'+color+'"><span class="'+icon+'"></span> '+label+'</button>';
                        dsp += '    <button class="btn btn-mini btn-default dropdown-toggle btn-demo-space" style="background-color:'+bgcolor+';color:'+color+';" data-toggle="dropdown" aria-expanded="true"><span class="'+icon+'"></span> '+label+' <span class="caret" style="color:'+color+'"></span> </button>';
                        dsp += '    <ul class="dropdown-menu">';
                        if(parseInt(row.order_flag) == 1){
                                dsp += '<li>';
                                dsp += '    <a class="btn_update_flag_order" style="cursor:pointer;"';
                                dsp += '        data-order-id="'+data+'" data-order-name="'+row.order_name+'" data-order-flag="'+row.order_flag+'" data-order-session="'+row.order_session+'">';
                                dsp += '        <span class="fas fa-ban"></span> Nonaktifkan';
                                dsp += '    </a>';
                                dsp += '</li>';
                        }else if(parseInt(row.order_flag) == 0) {
                                dsp += '<li>'; 
                                dsp += '    <a class="btn_update_flag_order" style="cursor:pointer;"';
                                dsp += '        data-order-id="'+data+'" data-order-name="'+row.order_name+'" data-order-flag="'+row.order_flag+'" data-order-session="'+row.order_session+'">';
                                dsp += '        <span class="fas fa-lock"></span> Aktifkan';
                                dsp += '    </a>';
                                dsp += '</li>';
                        }
                        dsp += '    </ul>';
                        dsp += '</div>';
                        return dsp;
                    }
                }
            ],
            "language": {
                "sProcessing":    "Sedang memuat",
                "sLengthMenu":    "Tampil _MENU_ data",
                "sZeroRecords":   "Data tidak ada",
                "sEmptyTable":    "Data tidak ada",
                "sInfo":          "Data _START_ sampai _END_ dari _TOTAL_ data",
                "sInfoEmpty":     "Data 0 sampai 0 dari 0 data",
                "sInfoFiltered":  "(saring total _MAX_ data)",
                "sInfoPostFix":   "",
                "sSearch":        "Cari:",
                "sUrl":           "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Loading...",
                "oPaginate": {
                    "sFirst":    "Pertama",
                    "sLast":    "Terakhir",
                    "sNext":    "Selanjutnya",
                    "sPrevious": "Sebelumnya"
                },
                "oAria": {
                    "sSortAscending":  ": Mengurutkan A-Z / 0-9",
                    "sSortDescending": ": Mengurutkan Z-A / 9-0"
                }
            }
        });
        $("#table_order_filter").css('display','none');
        $("#table_order_length").css('display','none');
        $("#filter_length").on('change', function(e){
            var value = $(this).find(':selected').val(); 
            $('select[name="table_order_length"]').val(value).trigger('change');
            order_table.ajax.reload();
        });
        $("#filter_flag").on('change', function(e){ order_table.ajax.reload(); });
        $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ order_table.ajax.reload(); }else if(parseInt(ln) < 1){ order_table.ajax.reload();} });
        */

        //CRUD
        $(document).on("click","#btn_save_order2",function(e) {
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
        $(document).on("click","#btn_save_order", function(e) {
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

            if(next){
                if ($("#order_input").val().length === 0) {
                    next = false;
                    notif(0,'Input_id wajib diisi');
                }
            }
            
            if(next){
                if ($("#order_select").find(':selected').val() === 0) {
                    next = false;
                    notif(0,'Select_id wajib dipilih');
                }
            }
            */

            /* Prepare ajax for UPDATE */
            /* If Form Validation Complete checked */
            if(next){
                /* let url = "services/controls/order.php?action=action_name";*/ //Native
                /* let url = "<?= base_url('order'); ?>"; */ //CI
                
                var form = new FormData($("#form_order")[0]);
                form.append('action', 'create_update');
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
                            index.ajax.reload();
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
            var session  = $(this).attr('data-order-session');
            var name     = $(this).attr('data-order-name');

            var form = new FormData();
            form.append('action', 'read');
            form.append('order_id', id);
            form.append('order_session', session);
            form.append('order_name', name);
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
                        $("#div_form_order").show(300);
                        
                        $("#order_id").val(d.result.order_id);
                        $("#order_session").val(d.result.order_session);
                        $("#order_type").val(d.result.order_type).trigger('change');
                        $("#order_name").val(d.result.order_name);
                        // $("#order_note").val(d.result.order_note);
                        $('#order_note').summernote('code', d.result.order_note);
                        $("#order_flag").val(d.result.order_flag).trigger('change');
                        // $("#order_date_created").val(d.result.order_date_created);

                        $("#files_preview").attr('src',d.result.Booking_image);
                        $(".files_link").attr('href',d.result.Booking_image);

                        $("#btn_new_order").hide();
                        $("#btn_save_order").hide();
                        $("#btn_update_order").show();
                        $("#btn_cancel_order").show();
                        // scrollUp('content');
                        formBookingSetDisplay(0);
                        //loadBookingItem(r.order_id);
                        //formBookingItemSetDisplay(0);
                    }else{
                        $("#div_form_order").hide(300);
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
            var id       = $(this).attr('data-order-id');
            var session  = $(this).attr('data-order-session');
            var name     = $(this).attr('data-order-name');
            var flag     = $(this).attr('data-order-flag');

            if(parseInt(flag) == 0){
                var set_flag = 0;
                var msg = 'menonaktifkan';
            }else if(parseInt(flag) == 1){
                var set_flag = 1;
                var msg = 'mengaktifkan';
            }else{
                var set_flag = 4;
                var msg = 'menghapus';
            }

            $.confirm({
                title: 'Konfirmasi!',
                content: 'Apakah anda ingin '+msg+' <b>'+name+'</b> ?',
                buttons: {
                    confirm:{ 
                        btnClass: 'btn-danger',
                        text: 'Ya',
                        action: function () {
                            
                            var form = new FormData();
                            form.append('action', 'update_flag');
                            form.append('order_id', id);
                            form.append('order_session', session);
                            form.append('order_name', name);
                            form.append('order_flag', set_flag);

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

        //CRUD ITEM
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
        /*
        $('#files').change(function(e) {
            var fileName = e.target.files[0].name;
            var fileReader = new FileReader();
            fileReader.onload = function(e) {
                $('#files_preview').attr('src', e.target.result).width(150).height(150);
                $('.files_link').attr('href', e.target.result);
            };
            fileReader.readAsDataURL(this.files[0]);
        });
        */

        //Image Croppie
        $(document).on('change', '#files', function(e) {
            if($("#files").val() == ''){
                $("#files_preview").attr('src', url_image);
                $("#files_link").attr('href', url_image);
                $("#files_preview").attr('data-save-img', '');
                return;
            }
            var reader = new FileReader();
            reader.onload = function(e) {
            upload_crop_img.croppie('bind', {
                url: e.target.result
                }).then(function () {
                    $("#modal-croppie").modal("show");
                        setTimeout(function(){$('#modal_croppie_canvas').croppie('bind');}, 300);
                });
            };
            reader.readAsDataURL(this.files[0]);
        });
        $(document).on('click', '#modal_croppie_cancel', function(e){
            e.preventDefault(); e.stopPropagation();
            $("#files").val('');
            $("#files_preview").attr('data-save-img', '');
            $("#files_preview").attr('src', url_image);
            $("#files_link").attr('href', url_image);
        });
        $(document).on('click', '#modal_croppie_save', function(e){
            e.preventDefault(); e.stopPropagation();
            upload_crop_img.croppie('result', {
                type: 'canvas',
                size: 'viewport',
            }).then(function (resp) {
                $("#files_preview").attr('src', resp);
                $("#files_link").attr('href', resp);
                $("#files_preview").attr('data-save-img', resp);
                $("#modal-croppie").modal("hide");
            });
        });

        // window.setInterval(loadPlugin(),3000);
        function loadPlugin(){
        }
        function formBookingReset(){
            $("#form_order input")
            .not("input[id='order_hour']")
            .not("input[id='order_date']")
            .not("input[id='order_date_start']")
            .not("input[id='order_date_end']").val('');
            $("#form_order textarea").val('');

            $("#files_link").attr('href',url_image);
            $("#files_preview").attr('src',url_image);
            $("#files_preview").attr('data-save-img',url_image);

            // $("#btn_save_order").show();
            // $("#btn_update_order").hide();
            // $("#btn_cancel_order").show();
            // $("#div_form_order").hide(300);
        } 

    //Approval Button
    var approval_table = 'orders';
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
                                                            index.ajax.reload(null,false);
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
                                            index.ajax.reload(null,false);
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
                                            index.ajax.reload(null,false);
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
    $(document).on("click","#btn_attachment_delete", function(e){
        e.preventDefault();
        e.stopPropagation();
        var id = $(this).attr('data-id');
        console.log("#btn_attachment_delete");
    });        
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
                                    
                                    var siz = '';
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