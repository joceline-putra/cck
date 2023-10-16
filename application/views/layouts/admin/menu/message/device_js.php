<!-- <script src="<?php #echo base_url();?>assets/core/plugins/jquery-qrcode/qrcode.js"></script> -->
<!-- <script src="<?php #echo base_url();?>assets/core/plugins/jquery-qrcode/jquery.qrcode.js"></script>	 -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script> -->
<script>
    $(document).ready(function () {
        var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="' + menu_link + '"]').addClass('active');

        // console.log(identity);
        var url = "<?= base_url('device'); ?>";
        $("select").select2();
        $(".date").datepicker({
            // defaultDate: new Date(),
            format: 'yyyy-mm-dd',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        });
        /*
        $('#group').select2({
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
                        source: 'devices_groups'
                    };
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
                // $(data.element).attr('data-contact-id', data.id);
                // $(data.element).attr('data-contact-name', data.nama);
                // $(data.element).attr('data-contact-phone', data.telepon);            
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });
        */
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
                    d.media = $("#filter_media").find(':selected').val();
                    d.length = $("#filter_length").find(':selected').val();
                    d.search = {
                        value: $("#filter_search").val()
                    };
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": "Sender", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Media", "searchable": true, "orderable": true},
                {"targets": 2, "title": "Label", "searchable": true, "orderable": true},
                {"targets": 3, "title": "Action", "searchable": false, "orderable": false}
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [{
                    'data': 'device_media',
                    render: function (data, meta, row) {
                        var dsp = '';
                        if(data == 'WhatsApp'){
                            dsp += row.device_number;
                        }else if(data == 'Email'){
                            dsp += row.device_mail_email;
                        }else{
                            dsp += '-';
                        }
                        return dsp;
                    }
                }, {
                    'data': 'device_media'
                }, {
                    'data': 'device_label'
                }, {
                    'data': 'device_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="' + data + '">';
                        dsp += '<span class="fas fa-edit"></span>Edit';
                        dsp += '</button>';
                        
                        if (parseInt(row.device_flag) === 1) {
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-success"';
                            dsp += 'data-nama="' + row.device_number + '" data-id="' + data + '" data-flag="' + row.device_flag + '">';
                            dsp += '<span class="fas fa-check-square primary"></span></button>';
                        } else {
                            // dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
                            // dsp += 'data-nama="' + row.device_number + '" data-id="' + data + '" data-flag="' + row.device_flag + '">';
                            // dsp += '<span class="fas fa-times danger"></span></button>';
                        }
                        dsp += '&nbsp;<button class="btn btn-delete btn-mini btn-danger"';
                        dsp += 'data-nama="' + row.device_number + '" data-id="' + data + '" data-flag="' + row.device_flag + '">';
                        dsp += '<span class="fas fa-times danger"></span></button>';

                        if(row.device_media == 'WhatsApp'){
                            dsp += '&nbsp;<button class="btn_restart btn btn-mini btn-info" data-device-id="' + data + '" data-device-number="' + row.device_number + '">';
                            dsp += '<span class="fas fa-bolt"> Restart</span>';
                            dsp += '</button>';

                            dsp += '&nbsp;<button class="btn_check btn btn-mini btn-info" data-device-id="' + data + '" data-device-number="' + row.device_number + '">';
                            dsp += '<span class="fas fa-arrow-up"> Check Status</span>';
                            dsp += '</button>';                        

                            dsp += '&nbsp;<button class="btn_qrcode btn btn-mini btn-info" data-device-id="' + data + '" data-device-number="' + row.device_number + '">';
                            dsp += '<span class="fas fa-qrcode"> QR Code</span>';
                            dsp += '</button>';    
                        }else if(row.device_media == 'Email'){
                            // dsp += '';
                        }
                        return dsp;
                    }
                }]
        });

        //Datatable Config
        $("#table-data_filter").css('display', 'none');
        $("#table-data_length").css('display', 'none');
        $("#filter_length").on('change', function (e) {
            index.ajax.reload();
        });
        $("#filter_media").on('change', function (e) {
            index.ajax.reload();
        });        
        $("#filter_search").on('input', function (e) {
            var ln = $(this).val().length;
            if (parseInt(ln) > 3) {
                index.ajax.reload();
            } else if (parseInt(ln) < 1) {
                index.ajax.reload();
            }
        });
        $('#table-data').on('page.dt', function () {
            var info = index.page.info();
            var limit_start = info.start;
            var limit_end = info.end;
            var length = info.length;
            var page = info.page;
            var pages = info.pages;
            $("#table-data-in").attr('data-limit-start', limit_start);
            $("#table-data-in").attr('data-limit-end', limit_end);
        });

        // Save Button
        $(document).on("click", "#btn-save", function (e) {
            e.preventDefault();
            var next = true;
            var media = $("#device_media").find(":selected").val();
            if(media == 'WhatsApp'){
                if (next == true) {
                    if ($("input[id='number']").val().length == 0) {
                        notif(0, 'Nomor wajib diisi');
                        $("#number").focus();
                        next = false;
                    }
                }

                if (next == true) {
                    if ($("input[id='label']").val().length == 0) {
                        notif(0, 'Label wajib diisi');
                        $("#label").focus();
                        next = false;
                    }
                }
            }else if(media == 'Email'){
                if (next == true) {
                    if ($("input[id='device_mail_host']").val().length == 0) {
                        notif(0, 'SMTP Host wajib diisi');
                        $("#device_mail_host").focus();
                        next = false;
                    }
                }

                if (next == true) {
                    if ($("input[id='device_mail_email']").val().length == 0) {
                        notif(0, 'SMTP Email wajib diisi');
                        $("#device_mail_email").focus();
                        next = false;
                    }
                }                
                if (next == true) {
                    if ($("input[id='device_mail_password']").val().length == 0) {
                        notif(0, 'SMTP Password wajib diisi');
                        $("#device_mail_password").focus();
                        next = false;
                    }
                }       
                if (next == true) {
                    if ($("input[id='device_mail_reply_alias']").val().length == 0) {
                        notif(0, 'SMTP Repply-To wajib diisi');
                        $("#device_mail_reply_alias").focus();
                        next = false;
                    }
                }                                                
            }else{
                notif(0,'Media belum dipilih');
                next = false;
                return;
            }


            if (next == true) {
                var prepare = {
                    // tipe: identity,
                    // device_number: $("input[id='number']").val(),
                    // device_token: $("input[id='token']").val(), 
                    // device_session: $("input[id='session']").val(),
                    // device_flag: $("select[id='status']").find(':selected').val()
                };
                // var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'create',
                    // data: prepare_data
                    // tipe: identity,
                    device_number: $("input[id='number']").val(),
                    device_label: $("input[id='label']").val(),
                    // device_auth: $("input[id='auth']").val(),
                    device_flag: $("select[id='status']").find(':selected').val(),
                    // device_group: $("select[id='group']").find(':selected').val()
                    device_media: $('#device_media').val(),
                    device_mail_host: $('#device_mail_host').val(),
                    device_mail_port: $('#device_mail_port').find(":selected").val(),
                    device_mail_email: $('#device_mail_email').val(),
                    device_mail_password: $('#device_mail_password').val(),
                    device_mail_from_alias: $('#device_mail_from_alias').val(),
                    device_mail_reply_alias: $('#device_mail_reply_alias').val(),
                    device_mail_label_alias: $('#device_mail_label_alias').val(),                    
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json',
                    cache: false,
                    beforeSend: function () {
                        notif(1,'Sedang menambahkan');
                        $("#btn-save").attr('disabled',true);
                    },
                    success: function (d) {
                        if (parseInt(d.status) == 1) { /* Success Message */
                            notif(1, d.message);
                            $("#btn-save").attr('disabled',false);                            
                            index.ajax.reload();
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

        // Edit Button
        $(document).on("click", ".btn-edit", function (e) {
            formMasterSetDisplay(0);
            $("#form-master input[name='kode']").attr('readonly', true);

            e.preventDefault();
            var id = $(this).data("id");
            var data = {
                action: 'read',
                tipe: identity,
                id: id
            };
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
                        $("#form-master input[name='id_document']").val(d.result.device_id);
                        $("#form-master input[name='number']").val(d.result.device_number);
                        $("#form-master input[name='auth']").val(d.result.device_token);
                        $("#form-master input[name='label']").val(d.result.device_label);
                        $("#form-master select[name='status']").val(d.result.device_flag).trigger('change');

                        $("#device_media").val(d.result.device_media).trigger('change');
                        $("#device_mail_host").val(d.result.device_mail_host);
                        $("#device_mail_port").val(d.result.device_mail_port).trigger('change');
                        $("#device_mail_email").val(d.result.device_mail_email);
                        // $("#device_mail_password").val(d.result.device_mail_password);
                        $("#device_mail_from_alias").val(d.result.device_mail_from_alias);
                        $("#device_mail_reply_alias").val(d.result.device_mail_reply_alias);
                        $("#device_mail_label_alias").val(d.result.device_mail_label_alias);

                        // $("select[id='group']").append('' +
                        //         '<option value="' + d.result.group_id + '">' +
                        //         d.result.group_name +
                        //         '</option>');
                        // $("select[id='group']").val(d.result.group_id).trigger('change');

                        $("#btn-new").hide();
                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-cancel").show();
                        scrollUp('content');
                        $("#device_media").attr('disabled',true);
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
            var id = $("#id_dokumen").val();
            var kode = $("#number");
            var nama = $("#auth");
            var media = $("#device_media").find(":selected").val();

            if (id == '') {
                notif(0, 'ID tidak ditemukan');
                next = false;
            }

            if(media == 'WhatsApp'){
                if (kode.val().length == 0) {
                    notif(0, 'Nomor wajib diisi');
                    kode.focus();
                    next = false;
                }

                if (nama.val().length == 0) {
                    notif(0, 'Token wajib diisi');
                    nama.focus();
                    next = false;
                }
            }else if(media == 'Email'){
                if (next == true) {
                    if ($("input[id='device_mail_host']").val().length == 0) {
                        notif(0, 'SMTP Host wajib diisi');
                        $("#device_mail_host").focus();
                        next = false;
                    }
                }

                if (next == true) {
                    if ($("input[id='device_mail_email']").val().length == 0) {
                        notif(0, 'SMTP Email wajib diisi');
                        $("#device_mail_email").focus();
                        next = false;
                    }
                }                
                if (next == true) {
                    // if ($("input[id='device_mail_password']").val().length == 0) {
                    //     notif(0, 'SMTP Password wajib diisi');
                    //     $("#device_mail_password").focus();
                    //     next = false;
                    // }
                }       
                if (next == true) {
                    if ($("input[id='device_mail_reply_alias']").val().length == 0) {
                        notif(0, 'SMTP Repply-To wajib diisi');
                        $("#device_mail_reply_alias").focus();
                        next = false;
                    }
                }                                                
            }

            if (next == true) {
                var prepare = {
                    // tipe: identity,
                    // id: $("input[id=id_document]").val(),
                    // device_number: $("input[id='number']").val(),
                    // device_token: $("input[id='token']").val(), 
                    // device_session: $("input[id='session']").val(),
                    // device_flag: $("select[id='status']").find(':selected').val()
                };
                // var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'update',
                    // data: prepare_data
                    // tipe: identity,
                    id: $("input[id=id_document]").val(),
                    device_number: $("input[id='number']").val(),
                    device_label: $("input[id='label']").val(),
                    device_auth: $("input[id='auth']").val(),
                    device_flag: $("select[id='status']").find(':selected').val(),
                    // device_group: $("select[id='group']").find(':selected').val()
                    device_media: $('#device_media').val(),
                    device_mail_host: $('#device_mail_host').val(),
                    device_mail_port: $('#device_mail_port').find(":selected").val(),
                    device_mail_email: $('#device_mail_email').val(),
                    device_mail_password: $('#device_mail_password').val(),
                    device_mail_from_alias: $('#device_mail_from_alias').val(),
                    device_mail_reply_alias: $('#device_mail_reply_alias').val(),
                    device_mail_label_alias: $('#device_mail_label_alias').val(),                      
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
                            $("#btn-new").show();
                            $("#btn-save").hide();
                            $("#btn-update").hide();
                            $("#btn-cancel").hide();
                            $("#form-master input").val();
                            formMasterSetDisplay(1);
                            notif(1, d.message);
                            index.ajax.reload(null, false);
                        } else {
                            notif(0, d.message);
                        }
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
            var user = $(this).attr("data-nama");
            $.confirm({
                title: 'Hapus!',
                content: 'Apakah anda ingin menghapus data ini ?',
                buttons: {
                    confirm: {
                        btnClass: 'btn-danger',
                        text: 'Ya',
                        action: function () {
                            var data = {
                                action: 'delete',
                                id: id
                            };
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (d) {
                                    if (parseInt(d.status) = 1) {
                                        notif(1, d.message);
                                        index.ajax.reload();
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
                content: 'Apakah anda ingin <b>' + msg + '</b> data ini ?',
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
                                tipe: identity,
                                id: id,
                                flag: set_flag,
                                nama: nama,
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
                                        index.ajax.reload(null, false);
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

        $(document).on("click", ".btn_restart",function(e) {
            var nm          = $(this).attr('data-device-number');
            var did         = $(this).attr('data-device-id');
            let title   = 'Restart';
            let content = 'Anda ingin restart device <b>'+nm+'</b> ini';
            $.confirm({
                title: title,
                content: content,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
                autoClose: 'button_2|30000',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                buttons: {
                    button_1: {
                        text:'Proses',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            var forma = new FormData();
                            forma.append('action','restart');
                            forma.append('device_id', did);
                            $.ajax({
                                type: "post",
                                url: url,
                                data: forma, 
                                dataType: 'json', cache: 'false', 
                                contentType: false, processData: false,
                                beforeSend:function(){},
                                success:function(d){
                                    let s = d.status;
                                    let m = d.message;
                                    let r = d.result;
                                    if(parseInt(s) == 1){
                                        notif(s,m);
                                    }else{
                                        notif(s,m);
                                    }
                                },
                                error:function(xhr,status,err){
                                    notif(0,err);
                                }
                            });
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
        $(document).on("click", ".btn_check",function(e) {
            var nm          = $(this).attr('data-device-number');
            var did         = $(this).attr('data-device-id');            
            let title   = 'Cek Status';
            let content = 'Anda ingin cek status device ini';
            $.confirm({
                title: title,
                content: content,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
                autoClose: 'button_2|30000',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                buttons: {
                    button_1: {
                        text:'Proses',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            let next     = true;
                            let forma = new FormData();
                            forma.append('action','check_status');
                            forma.append('device_id', did);
                            $.ajax({
                                type: "post",
                                url: url,
                                data: forma, 
                                dataType: 'json', cache: 'false', 
                                contentType: false, processData: false,
                                beforeSend:function(){},
                                success:function(d){
                                    let s = d.status;
                                    let m = d.message;
                                    let r = d.result;
                                    if(parseInt(s) == 1){
                                        notif(s,m);
                                    }else{
                                        notif(s,m);
                                    }
                                },
                                error:function(xhr,status,err){
                                    notif(0,err);
                                }
                            });
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
        $(document).on("click", ".btn_qrcode",function(e) {
            var nm          = $(this).attr('data-device-number');
            var did         = $(this).attr('data-device-id');            
            let title   = 'Request QR Code';
            let content = 'Anda ingin request QR Code ?';
            
            $.confirm({
                title: title,
                content: content,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
                autoClose: 'button_2|30000',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                buttons: {
                    button_1: {
                        text:'Proses',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            $("#modal-qrcode").modal({backdrop: 'static', keyboard: false}); 
                            var params = {
                                device_number: nm,
                                device_id: did
                            };
                            loadQRCode(params);
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
        $(document).on("click", "#btn_qrcode_repeat",function(e) {
            e.preventDefault();
            e.stopPropagation();
            $("#modal-qrcode").modal('toggle');  
            $("#qrcodes").html('Your QR will display here');
            var id = $(this).attr('data-id');
            var params = {
                device_id:id
            };
            loadQRCode(params);
                $("#modal-qrcode").modal("hide"); 
            setTimeout(function(){
                $("#modal-qrcode").modal({backdrop: 'static', keyboard: false}); 
            }, 1000);     
        });
        $(document).on("change","#device_media",function(e) {
            e.preventDefault();
            e.stopPropagation();
            let option = $(this).find(":selected").val();
            $(".div_whatsapp").hide(100);
            $(".div_email").hide(100);
            if(option == 'WhatsApp'){
                $(".div_whatsapp").show(300);
            }
            else if(option == 'Email'){                
                $(".div_email").show(300);                
            }
        });
        $(document).on("input","#device_mail_email",function(e) {
            e.preventDefault();
            e.stopPropagation();
            var txt = $(this).val();
            $("#device_mail_from_alias").val(txt);
        });
        $(document).on("focusin","#device_mail_password",function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(".p_information").show(300);
        });
        $(document).on("focusout","#device_mail_password",function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(".p_information").hide(300);
        });        
        function loadQRCode(params){
            let next     = true;
            let forma = new FormData();
            forma.append('action','request_qr_code');
            forma.append('device_id', params['device_id']);
            $.ajax({
                type: "post",
                url: url,
                data: forma, 
                dataType: 'json', cache: 'false', 
                contentType: false, processData: false,
                beforeSend:function(){},
                success:function(d){
                    let s = d.status;
                    let m = d.message;
                    let r = d.result;
                    if(parseInt(s) == 1){
                        // $("#modal-qrcode").modal("toggle"); 
                        $("#btn_qrcode_repeat").attr('data-id',params['device_id']); 
                        // $("#qrcodes").html('Your QR will display here');

                        var set_qrcode = 'data:image/png;base64,'+r.result;
                        console.log(set_qrcode);

                        setTimeout(function(){
                            // Cara Pertama
                            $("#qrcodes").attr('src',set_qrcode);

                            // Cara Kedua
                            // var qrcode = new QRious({
                            //     element: document.getElementById("qrcodes"),
                            //     background: '#ffffff',
                            //     backgroundAlpha: 1,
                            //     foreground: 'black',
                            //     foregroundAlpha: 1,
                            //     level: 'H',
                            //     padding: 10,
                            //     size: 500,
                            //     value: set_qrcode
                            // });

                            // // Cara Ketiga
                            // var qrcode = new QRCode(document.getElementById("qrcodes"), {
                            //     text: set_qrcode,
                            //     width: 512,
                            //     height: 512,
                            //     colorDark : "#5868bf",
                            //     colorLight : "#ffffff",
                            // });      

                        }, 1000);                   
                    }else{
                        $("#modal-qrcode").modal("toggle");                         
                        notif(s,m);
                    }
                },
                error:function(xhr,status,err){
                    notif(0,err);
                }
            });            
        }
    });

    function formNew() {
        formMasterSetDisplay(0);
        $("#form-master input").val();
        $("#device_media").val(0).trigger('change');
        $("#btn-new").hide();
        $("#btn-save").show();
        $("#btn-cancel").show();
    }
    function formCancel() {
        formMasterSetDisplay(1);
        $("#form-master input").val();
        $("#device_media").val(0).trigger('change');        
        $("#btn-new").show();
        $("#btn-save").hide();
        $("#btn-update").hide();
        $("#btn-cancel").hide();
    }
    function formMasterSetDisplay(value) { // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        if (value == 1) {
            var flag = true;
        } else {
            var flag = false;
        }
        //Attr Input yang perlu di setel
        var form = '#form-master';
        var attrInput = [
            "number",
            // "auth",
            "label",
            "device_mail_host","device_mail_email","device_mail_password","device_mail_from_alias","device_mail_reply_alias"
        ];

        for (var i = 0; i <= attrInput.length; i++) {
            $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        }

        // Attr Textarea yang perlu di setel
        // var attrText = [
        //   "keterangan"
        // ];
        // for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

        //Attr Select yang perlu di setel
        var atributSelect = [
            "status",
            "device_media","device_mail_port"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>