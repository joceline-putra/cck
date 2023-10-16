<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    // let identity = "<?php #echo $identity; ?>";
    let url = "<?= base_url('recipient'); ?>";
    let url_print = "<?= base_url('recipient'); ?>";
    var url_tool = "<?= base_url('search/manage'); ?>";
    var url_image = "<?= site_url('upload/noimage.png'); ?>";
    var view = "<?php echo $_view; ?>";

    let image_width = "<?= $image_width;?>";
    let image_height = "<?= $image_height;?>";

    $(".nav-tabs").find('li[class="active"]').removeClass('active');
    $(".nav-tabs").find('li[data-name="' + view + '"]').addClass('active');
    
    $(function() {
        // setInterval(function(){ 
        // }, 3000);
    });


    //Croppie
    var upload_crop_img = $('#modal-croppie-canvas').croppie({
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
    $('#recipient_group_id').select2({
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
                    source: 'recipients_groups'
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
    $("#recipient_group_id").on('change', function(e){
        // Do Something
    });
    // $("select").select2();

    //Date Clock Picker
    $("#recipient_birth").datepicker({
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
        recipient_table.ajax.reload();
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
    let recipient_table = $("#table_recipient").DataTable({
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
            {"targets":0, "width":"20%", "title":"Nama", "searchable":true, "orderable":true},
            {"targets":1, "width":"20%", "title":"Telepon", "searchable":true, "orderable":true},
            {"targets":2, "width":"20%", "title":"Email", "searchable":true, "orderable":true},
            {"targets":3, "width":"20%", "title":"Group Kontak", "searchable":true, "orderable":true},                        
            {"targets":4, "width":"20%", "title":"Status", "searchable":true, "orderable":true},
        ],
        "order": [[0, 'ASC']],
        "columns": [
            {
                'data': 'recipient_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += row.recipient_name;
                    return dsp;
                }
            },{
                'data': 'recipient_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += row.recipient_phone;
                    // if(row.contact_email_1 != undefined){
                        // dsp += '<br>'+row.contact_email_1;
                    // }
                    return dsp;
                }
            },{
                'data': 'recipient_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += row.recipient_email;
                    // if(row.contact_email_1 != undefined){
                        // dsp += '<br>'+row.contact_email_1;
                    // }
                    return dsp;
                }
            },{
                'data': 'recipient_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    if(row.recipient_group_id != undefined){
                        dsp += '<label class="label btn_search_recipient_group_name" data-value="'+row.group_name+'" style="cursor:pointer;">'+row.group_name+'</label>';
                    }
                    return dsp;
                }
            },{
                'data': 'recipient_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = ''; var label = 'Error Status'; var icon = 'fas fa-cog'; var color = 'white'; var bgcolor = '#d1dade';
                    if(parseInt(row.recipient_flag) == 1){
                    //  dsp += '<label style="color:#6273df;">Aktif</label>';
                       label = 'Aktif';
                       icon = 'fas fa-lock';
                       bgcolor = '#0aa699';
                    }else if(parseInt(row.recipient_flag) == 4){
                       //  dsp += '<label style="color:#ff194f;">Terhapus</label>';
                       label = 'Terhapus';
                       icon = 'fas fa-trash';
                       bgcolor = '#f35958';
                    }else if(parseInt(row.recipient_flag) == 0){
                       //   dsp += '<label style="color:#ff9019;">Nonaktif</label>';
                       label = 'Nonaktif';
                       icon = 'fas fa-unlock';
                       // color = 'green';
                       bgcolor = '#ff9019';
                    }

                        /* Button Action Concept 1 */
                        // dsp += '&nbsp;<button class="btn btn_edit_recipient btn-mini btn-primary"';
                        // dsp += 'data-recipient-id="'+data+'" data-recipient-name="'+row.recipient_name+'" data-recipient-flag="'+row.recipient_flag+'" data-recipient-session="'+row.recipient_session+'">';
                        // dsp += '<span class="fas fa-edit primary"></span> Edit</button>';

                        // dsp += '&nbsp;<button class="btn btn_delete_recipient btn-mini btn-danger"';
                        // dsp += 'data-recipient-id="'+data+'" data-recipient-name="'+row.recipient_name+'" data-recipient-flag="4" data-recipient-session="'+row.recipient_session+'">';
                        // dsp += '<span class="fas fa-trash danger"></span> Hapus</button>';

                        // if (parseInt(row.recipient_flag) === 1) {
                        //   dsp += '&nbsp;<button class="btn btn_update_flag_recipient btn-mini btn-primary" style="background-color:#ff9019;"';
                        //   dsp += 'data-recipient-id="'+data+'" data-recipient-name="'+row.recipient_name+'" data-recipient-flag="0" data-recipient-session="'+row.recipient_session+'">';
                        //   dsp += '<span class="fas fa-ban"></span> Nonaktifkan</button>';                      
                        // }else if (parseInt(row.recipient_flag) === 0) {
                        //   dsp += '&nbsp;<button class="btn btn_update_flag_recipient btn-mini btn-primary" style="background-color:#6273df;"';
                        //   dsp += 'data-recipient-id="'+data+'" data-recipient-name="'+row.recipient_name+'" data-recipient-flag="1" data-recipient-session="'+row.recipient_session+'">';
                        //   dsp += '<span class="fas fa-check primary"></span> Aktifkan</button>';
                        // }

                       /* Button Action Concept 2 */
                       dsp += '&nbsp;<div class="btn-group">';
                       // dsp += '    <button class="btn btn-mini btn-default" style="background-color:'+bgcolor+';color:'+color+'"><span class="'+icon+'"></span> '+label+'</button>';
                       dsp += '    <button class="btn btn-mini btn-default dropdown-toggle btn-demo-space" style="background-color:'+bgcolor+';color:'+color+';" data-toggle="dropdown" aria-expanded="true"><span class="'+icon+'"></span> '+label+' <span class="caret" style="color:'+color+'"></span> </button>';
                       dsp += '    <ul class="dropdown-menu">';
                       dsp += '        <li>';
                       dsp += '            <a class="btn_edit_recipient" style="cursor:pointer;"';
                       dsp += '                data-recipient-id="'+data+'" data-recipient-name="'+row.recipient_name+'" data-recipient-flag="'+row.recipient_flag+'" data-recipient-session="'+row.recipient_session+'">';
                       dsp += '                <span class="fas fa-edit"></span> Edit';
                       dsp += '            </a>';
                       dsp += '        </li>';                       
                       if(parseInt(row.recipient_flag) == 1){
                               dsp += '<li>';
                               dsp += '    <a class="btn_update_flag_recipient" style="cursor:pointer;"';
                               dsp += '        data-recipient-id="'+data+'" data-recipient-name="'+row.recipient_name+'" data-recipient-flag="0" data-recipient-session="'+row.recipient_session+'">';
                               dsp += '        <span class="fas fa-ban"></span> Nonaktifkan';
                               dsp += '    </a>';
                               dsp += '</li>';
                       }else if(parseInt(row.recipient_flag) == 0) {
                               dsp += '<li>'; 
                               dsp += '    <a class="btn_update_flag_recipient" style="cursor:pointer;"';
                               dsp += '        data-recipient-id="'+data+'" data-recipient-name="'+row.recipient_name+'" data-recipient-flag="1" data-recipient-session="'+row.recipient_session+'">';
                               dsp += '        <span class="fas fa-lock"></span> Aktifkan';
                               dsp += '    </a>';
                               dsp += '</li>';
                               dsp += '<li>'; 
                               dsp += '    <a class="btn_update_flag_recipient" style="cursor:pointer;"';
                               dsp += '        data-recipient-id="'+data+'" data-recipient-name="'+row.recipient_name+'" data-recipient-flag="4" data-recipient-session="'+row.recipient_session+'">';
                               dsp += '        <span class="fas fa-lock"></span> Hapus';
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
    $("#table_recipient_filter").css('display','none');
    $("#table_recipient_length").css('display','none');
    $("#filter_length").on('change', function(e){
        var value = $(this).find(':selected').val(); 
        $('select[name="table_recipient_length"]').val(value).trigger('change');
        recipient_table.ajax.reload();
    });
    $("#filter_flag").on('change', function(e){ recipient_table.ajax.reload(); });
    $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 2){ recipient_table.ajax.reload(); }else if(parseInt(ln) < 1){ recipient_table.ajax.reload();} });

    let recipient_group_table = $("#table_recipient_group").DataTable({
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
                d.action = 'load_recipient_group';
                d.length = $("#filter_group_length").find(':selected').val();
                d.filter_group_flag = $("#filter_group_flag").find(':selected').val();
                d.search = {value:$("#filter_group_search").val()};
            },
            dataSrc: function(data) {
                return data.result;
            }
        },
        "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        "columnDefs": [
            {"targets":0, "width":"40%", "title":"Nama Group", "searchable":true, "orderable":true},
            {"targets":1, "width":"20%", "title":"Jumlah Kontak", "searchable":true, "orderable":true},
            {"targets":2, "width":"30%", "title":"Flag", "searchable":true, "orderable":true},
        ],
        "order": [[0, 'ASC']],
        "columns": [
            {
                'data': 'group_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += row.group_name;
                    return dsp;
                }
            },{
                'data': 'group_id',
                className: 'text-right',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += '<label class="label btn_click_count_recipient_group" data-id="'+row.group_id+'" data-name="'+row.group_name+'" data-count="'+row.group_count+'" style="cursor:pointer;">'+addCommas(row.group_count)+' Data</label>';
                    return dsp;
                }
            },{
                'data': 'group_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = ''; var label = 'Error Status'; var icon = 'fas fa-cog'; var color = 'white'; var bgcolor = '#d1dade';
                    if(parseInt(row.group_flag) == 1){
                    //  dsp += '<label style="color:#6273df;">Aktif</label>';
                       label = 'Aktif';
                       icon = 'fas fa-lock';
                       bgcolor = '#0aa699';
                    }else if(parseInt(row.group_flag) == 4){
                       //  dsp += '<label style="color:#ff194f;">Terhapus</label>';
                       label = 'Terhapus';
                       icon = 'fas fa-trash';
                       bgcolor = '#f35958';
                    }else if(parseInt(row.group_flag) == 0){
                       //   dsp += '<label style="color:#ff9019;">Nonaktif</label>';
                       label = 'Nonaktif';
                       icon = 'fas fa-unlock';
                       // color = 'green';
                       bgcolor = '#ff9019';
                    }
                        dsp += '&nbsp;<button class="btn btn_edit_recipient_group btn-mini btn-primary"';
                        dsp += 'data-group-id="'+data+'" data-group-name="'+row.group_name+'" data-group-flag="'+row.group_flag+'">';
                        dsp += '<span class="fas fa-edit primary"></span> Edit</button>';

                        dsp += '&nbsp;<button class="btn btn_import_recipient_group btn-mini btn-success"';
                        dsp += 'data-group-id="'+data+'" data-group-name="'+row.group_name+'">';
                        dsp += '<span class="fas fa-file-excel success"></span> Import Excel</button>';

                        if(parseInt(row.group_flag) === 0) {
                            dsp += '&nbsp;<button class="btn btn_update_flag_recipient_group btn-mini btn-primary"';
                            dsp += 'data-group-id="'+data+'" data-group-name="'+row.group_name+'" data-group-flag="1">';
                            dsp += '<span class="fas fa-lock"></span> Aktifkan</button>';                                  
                        }else if(parseInt(row.group_flag) === 1){
                            dsp += '&nbsp;<button class="btn btn_update_flag_recipient_group btn-mini btn-warning"';
                            dsp += 'data-group-id="'+data+'" data-group-name="'+row.group_name+'" data-group-flag="0">';
                            dsp += '<span class="fas fa-ban"></span> Nonaktifkan</button>';                               
                        }
                            /*
                        if((parseInt(row.group_flag) < 1) || (parseInt(row.group_flag) == 4)) {
                            dsp += '&nbsp;<button class="btn btn_update_flag_recipient_group btn-mini btn-danger"';
                            dsp += 'data-group-id="'+data+'" data-group-name="'+row.group_name+'" data-group-flag="4">';
                            dsp += '<span class="fas fa-trash danger"></span> Hapus</button>';                               
                        }
                       */
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
    $("#table_recipient_group_filter").css('display','none');
    $("#table_recipient_group_length").css('display','none');
    $("#filter_group_length").on('change', function(e){
        var value = $(this).find(':selected').val(); 
        $('select[name="table_recipient_group_length"]').val(value).trigger('change');
        recipient_group_table.ajax.reload();
    });
    $("#filter_group_flag").on('change', function(e){ recipient_group_table.ajax.reload(); });
    $("#filter_group_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 2){ recipient_group_table.ajax.reload(); }else if(parseInt(ln) < 1){ recipient_group_table.ajax.reload();} });

    //CRUD
    $(document).on("click","#btn_save_recipient",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next =true;
        if($("#recipient_name").val().length == 0){
            notif(0,'Nama wajib diisi');
            $("#recipient_name").focus();
            next=false;
        }else if($("#recipient_flag").find(':selected').val() == 0){
            notif(0,'Status wajib diisi');
            $("#recipient_flag").focus();
            next=false;
        }else{
            var form = new FormData($("#form_recipient")[0]);
            // var form = new FormData();
            form.append('action', 'create');
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
                        formRecipientReset();
                        $("#btn_new_recipient").show(300);
                        /* hint zz_for or zz_each */
                        recipient_table.ajax.reload();
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
    $(document).on("click",".btn_edit_recipient",function(e) { //works
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var id       = $(this).attr('data-recipient-id');
        var session  = $(this).attr('data-recipient-session');
        var name     = $(this).attr('data-recipient-name');

        var form = new FormData();
        form.append('action', 'read');
        form.append('recipient_id', id);
        form.append('recipient_session', session);
        form.append('recipient_name', name);
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
                    $("#div_form_recipient").show(300);
                    
                    $("#recipient_id").val(d.result.recipient_id);
                    $("#recipient_branch_id").val(d.result.recipient_branch_id);
                    $("#recipient_name").val(d.result.recipient_name);
                    $("#recipient_phone").val(d.result.recipient_phone);
                    $("#recipient_email").val(d.result.recipient_email);
                    $("#recipient_session").val(d.result.recipient_session);
                    $("#recipient_flag").val(d.result.recipient_flag).trigger('change');
                    $("#group_name").val(d.result.group_name);

                    if(d.result.recipient_group_id != undefined){
                        $("select[id='recipient_group_id']").append(''+'<option value="'+d.result.recipient_group_id+'">'+d.result.group_name+'</option>');
                        $("select[id='recipient_group_id']").val(d.result.recipient_group_id).trigger('change');
                    }
                    
                    if(d.result.recipient_birth != undefined){
                        // $("#recipient_birth").val(moment(d.result.recipient_birth).format("DD-MMM-YYYY"));
                        $("#recipient_birth").datepicker("update", moment(d.result.recipient_birth).format("DD-MM-YYYY"));
                    }

                    $("#btn_new_recipient").hide();
                    $("#btn_save_recipient").hide();
                    $("#btn_update_recipient").show();
                    $("#btn_cancel_recipient").show();
                    // scrollUp('content');
                    formRecipientSetDisplay(0);
                    //loadRecipientItem(r.recipient_id);
                    //formRecipientItemSetDisplay(0);
                }else{
                    $("#div_form_recipient").hide(300);
                    notif(0,d.message);
                }
            },
            error:function(xhr, Status, err){
                notif(0,'Error');
            }
        });
    });
    $(document).on("click","#btn_update_recipient",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next =true;
        var id = $("#recipient_id").val();
        var session = $("#recipient_session").val();
        if(parseInt(id) > 0){
            if($("#recipient_name").val().length == 0){
                notif(0,'Nama wajib diisi');
                $("#recipient_name").focus();
                next=false;
            }else if($("#recipient_flag").val().length == 0){
                notif(0,'Status wajib diisi');
                $("#recipient_flag").focus();
                next=false;
            }else{
                var form = new FormData($("#form_recipient")[0]);
                form.append('action', 'update');
                form.append('recipient_id', id);
                form.append('recipient_session', session);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form,
                    dataType:"json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend:function(){},
                    success:function(d){
                        var s = d.status;
                        var m = d.message;
                        var r = d.result;
                        if(parseInt(s)==1){
                            formRecipientReset();
                            $("#btn_new_recipient").show(300);
                            notif(s,m);
                            recipient_table.ajax.reload(null,false);
                        }else{
                            notif(s,m);  
                        }
                    },
                    error:function(xhr, Status, err){
                        notif(0,err);
                    }
                });
            }
        }else{
            notif(0,'Data belum dibuka');
        }
    });
    $(document).on("click",".btn_delete_recipient",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next     = true;
        var id       = $(this).attr('data-recipient-id');
        var session  = $(this).attr('data-recipient-session');
        var name     = $(this).attr('data-recipient-name');

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
                        form.append('recipient_id', id);
                        form.append('recipient_session', session);
                        form.append('recipient_name', name);
                        form.append('recipient_flag', 4);

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
                                    recipient_table.ajax.reload(null,false);
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

    $(document).on("click",".btn_update_flag_recipient",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next     = true;
        var id       = $(this).attr('data-recipient-id');
        var session  = $(this).attr('data-recipient-session');
        var name     = $(this).attr('data-recipient-name');
        var flag     = $(this).attr('data-recipient-flag');

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
                        form.append('recipient_id', id);
                        form.append('recipient_session', session);
                        form.append('recipient_name', name);
                        form.append('recipient_flag', set_flag);

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
                                    recipient_table.ajax.reload(null,false);
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
    $(document).on("click","#btn_modal_recipient_group",function(e) {
        e.preventDefault();
        e.stopPropagation();
        $("#modal_recipient_group").modal('show');
        recipient_group_table.ajax.reload();
    });
    $(document).on("click",".btn_search_recipient_group_name",function(e) {
        e.preventDefault();
        e.stopPropagation();
        var did = $(this).attr('data-value');
        if(did.length > 0){
            $("#filter_search").val(did);
            recipient_table.ajax.reload();
        }
    });
    
    //CRUD Group
    $(document).on("click","#btn_save_recipient_group",function(e) {
        let title   = 'Buat Group '+ "<?php echo $title; ?>" + ' Baru';
        $.confirm({
            title: title,
            icon: 'fas fa-check',
            columnClass: 'col-md-45 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
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
                    dsp += '    <label class="form-label">Nama Group <?php echo $title; ?></label>';
                    dsp += '        <input id="jc_input" name="jc_input" class="form-control">';
                    dsp += '    </div>';
                    dsp += '</div>';
                dsp += '</form>';
                content = dsp;
                self.setContentAppend(content);
                $('#jc_input').focus();
            },
            buttons: {
                button_1: {
                    text:'Simpan Group',
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function(e){
                        let self      = this;
        
                        let input     = self.$content.find('#jc_input').val();
                        
                        if(!input){
                            $.alert('Nama Group mohon diisi dahulu');
                            return false;
                        }else{
                            let form = new FormData();
                            form.append('action', 'create_recipient_group');
                            form.append('group_name', input);
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
                                        /*type_your_code_here*/
                                        recipient_group_table.ajax.reload(null,false);
                                    }else{
                                        notif(s,m);
                                        // notifSuccess(m);
                                    }
                                },
                                error: function(xhr, status, err) {}
                            });
                        }
                    }
                },
                button_2: {
                    text: 'Tutup',
                    btnClass: 'btn-default',
                    keys: ['Escape'],
                    action: function(){
                        //Close
                    }
                }
            }
        });
    });
    $(document).on("click",".btn_edit_recipient_group",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var gid = $(this).attr('data-group-id');
        let title   = 'Edit Group Kontak';
        $.confirm({
            title: title,
            icon: 'fas fa-check',
            columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
            autoClose: 'button_2|10000',    
            closeIcon: true, closeIconClass: 'fas fa-times',    
            animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
            content: function(){
                let self = this;
                let form = new FormData();
                form.append('action','read_recipient_group');
                form.append('group_id',gid);
        
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
                    var dsp = '';
                    self.setTitle(m);
                    self.setContentAppend(dsp);
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
                        dsp += '    <label class="form-label">Nama Group</label>';
                        dsp += '        <input id="jc_input" name="jc_input" class="form-control" value="'+r.group_name+'">';
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
                    text:'Perbarui',
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function(){
                        let self      = this;
                        let input     = self.$content.find('#jc_input').val();
                        if(!input){
                            $.alert('Nama Group mohon diisi dahulu');
                            return false;
                        } else{
                            let form = new FormData();
                            form.append('action', 'update_recipient_group');
                            form.append('group_id', gid);
                            form.append('group_name', input);
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
                                        recipient_group_table.ajax.reload(null,false);
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
    $(document).on("click",".btn_update_recipient_group",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
    });
    $(document).on("click",".btn_delete_recipient_group",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
    });
    $(document).on("click",".btn_update_flag_recipient_group",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next     = true;
        var id       = $(this).attr('data-group-id');
        var name     = $(this).attr('data-group-name');
        var flag     = $(this).attr('data-group-flag');

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
                        form.append('action', 'update_flag_group');
                        form.append('group_id', id);
                        form.append('group_name', name);
                        form.append('group_flag', set_flag);

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
                                    recipient_group_table.ajax.reload(null,false);
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
    $(document).on("click",".btn_click_count_recipient_group",function(e) {
        e.preventDefault();
        e.stopPropagation();
        var gname = $(this).attr('data-name');
        var gcount = $(this).attr('data-count');
        if(parseInt(gcount) > 0){
            $("#modal_recipient_group").modal('hide');
            $("#filter_search").val(gname);
            setTimeout(() => {
                notif(1,'Mencari Data');
            }, 1000);            
            recipient_table.ajax.reload();
        }else{
            notif(0,'Data terkait tidak ditemukan');
        }
    });
    $(document).on("click",".btn_import_recipient_group",function(e) {
        e.preventDefault();
        e.stopPropagation();
        var gid = $(this).attr('data-group-id');
        var gname = $(this).attr('data-group-name');        
        let title   = 'Import Kontak ke '+gname;
        $.confirm({
            title: title,
            icon: 'fas fa-check',
            columnClass: 'col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
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
                    dsp += '        <label class="form-label">Contoh Penulisan Excel</label>';
                    dsp += '        <img src="<?php echo base_url('upload/template/template_kontak_broadcast.png');?>" class="img-responsive" style="width:100%;">';
                    dsp += '    </div>';
                    dsp += '</div>';
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                    dsp += '    <div class="form-group">';
                    dsp += '    <label class="form-label">Pilih File Excel ( .xls / .xlsx )</label>';
                    dsp += '        <input id="excel_file" name="excel_file" type="file" class="form-control" accept=".xls, .xlsx" required>';
                    dsp += '        <p>Template excel dapat diunduh <a href="<?php echo base_url('upload/template/template_kontak_broadcast.xlsx');?>">disini</a>';
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
        
                        let id     = gid;
                        let input  = self.$content.find('#excel_file').val();
                        
                        if(!id){
                            $.alert('Group tidak ditemukan');
                            return false;
                        } else if(!input){
                            $.alert('File excel belum dipilih');
                            return false;
                        } else{
                            let form = new FormData();
                            form.append('action', 'import_recipient_from_excel');
                            form.append('group_id', gid);
                            form.append('excel_file', $('#excel_file')[0].files[0]);
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
                                        recipient_group_table.ajax.reload(null,false);
                                        /*type_your_code_here*/
                                    }else{
                                        notif(s,m);
                                        // notifSuccess(m);
                                    }
                                },
                                error: function(xhr, status, err) {}
                            });
                        }
                    }
                },
                button_2: {
                    text: 'batal',
                    btnClass: 'btn-danger',
                    keys: ['Escape'],
                    action: function(){
                        //Close
                    }
                }
            }
        });
    });
    
    //Additional
    $(document).on("click","#btn_new_recipient",function(e) {
        formRecipientReset();
        formRecipientSetDisplay(0);
        $("#div_form_recipient").show(300);
        $("#btn_new_recipient").hide(300);
    });
    $(document).on("click","#btn_cancel_recipient",function(e) {
        formRecipientReset();
        formRecipientSetDisplay(1);
        $("#div_form_recipient").hide(300);
        $("#btn_new_recipient").show(300);
    });
    $(document).on("click",".btn_print_recipient",function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log($(this));
        var id = $(this).attr('data-recipient-id');
        var session = $(this).attr('data-recipient-session');
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
    $(document).on("click","#btn_export_recipient",function(e) {
        e.stopPropagation();
        $.alert('Fungsi belum dibuat');
    });
    $(document).on("click","#btn_print_recipient",function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log($(this));
        // var id = $(this).attr('data-recipient-id');
        $.alert('Fungsi belum dibuat');
    });
    $(document).on("click","#btn_cancel_recipient_group",function(e) {
        formRecipientItemReset();
    });
    function loadRecipientgroup(recipient_id = 0){
        alert('loadRecipientgroup();');
        if(parseInt(recipient_id) > 0){
            $.ajax({
                type: "post",
                url: "<?= base_url('recipient'); ?>",
                data: {
                    action:'load_recipient_item_2',
                    recipient_item_recipient_id:recipient_id
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
                    setTimeout(function(){$('#modal-croppie-canvas').croppie('bind');}, 300);
            });
        };
        reader.readAsDataURL(this.files[0]);
    });
    $(document).on('click', '#modal-croppie-cancel', function(e){
        e.preventDefault(); e.stopPropagation();
        $("#files").val('');
        $("#files_preview").attr('data-save-img', '');
        $("#files_preview").attr('src', url_image);
        $("#files_link").attr('href', url_image);
    });
    $(document).on('click', '#modal-croppie-save', function(e){
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

    function loadPlugin(){
    }
    function formRecipientReset(){
        $("#form_recipient input")
        .not("input[id='recipient_hour']")
        .not("input[id='recipient_birth']")
        .not("input[id='recipient_date_start']")
        .not("input[id='recipient_date_end']").val('');
        $("#form_recipient textarea").val('');

        $("#files_link").attr('href',url_image);
        $("#files_preview").attr('src',url_image);
        $("#files_preview").attr('data-save-img',url_image);

        $("#filter_search").val('');
        $("#btn_save_recipient").show();
        $("#btn_update_recipient").hide();
        $("#btn_cancel_recipient").show();
        $("#div_form_recipient").hide(300);
    } 
    function formRecipientGroupReset(){
        $("#form_recipient_group input")
        .not("input[id='recipient_group_date_start']")
        .not("input[id='recipient_group_date_end']").val('');
        $("#form_recipient_group textarea").val('');
    
        $("#btn_save_recipient_group").show(300);
        $("#btn_update_recipient_group").hide(300);
        $("#btn_cancel_recipient_group").hide(300);
    } 
   
}); //End of Document Ready
    // window.setInterval(loadPlugin(),3000);
    function formRecipientSetDisplay(value){ // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        if(value == 1){ var flag = true; }else{ var flag = false; }
        //Attr Input yang perlu di setel
        var form = '#form_recipient'; 
        var attrInput = [
            "recipient_name","recipient_birth","recipient_phone","recipient_email"
        ];
        for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag); }

        //Attr Textarea yang perlu di setel
        var attrText = [
            // "recipient_note"
        ];
        for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

        //Attr Select yang perlu di setel
        var atributSelect = [
            "recipient_flag",
            "recipient_group_id",
        ];
        for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag); }
    }
    function formRecipientGroupSetDisplay(value){ // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        if(value == 1){ var flag = true; }else{ var flag = false; }
        //Attr Input yang perlu di setel
        var form = '#form_recipient_group'; 
        var attrInput = [
            "group_id"
        ];
        for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag); }

        //Attr Textarea yang perlu di setel
        var attrText = [
        ];
        for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

        //Attr Select yang perlu di setel
        var atributSelect = [
            "group_name",
        ];
        for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag); }
    } 
</script>