<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    let url = "<?php base_url('voucher'); ?>";
    let url_print = "<?php base_url('voucher'); ?>";
    let url_tool = "<?php base_url('search/manage'); ?>";
    var url_image = "<?= site_url('upload/voucher/noimage.png'); ?>";

    $(".nav-tabs").find('li[class="active"]').removeClass('active');
    $(".nav-tabs").find('li[data-name="product/voucher"]').addClass('active');
    
    let image_width = "<?= $image_width;?>";
    let image_height = "<?= $image_height;?>";    
    
    var mode_edit = 0;

    $(function() {
        setInterval(function(){ 
            //SummerNote
            // $('#voucher_note').summernote({
            //     placeholder: 'Tulis keterangan disini!',
            //     tabsize: 4,
            //     height: 350
            // });  
        }, 3000);
    });

    // $("#files_link").attr('href',url_image);
    // $("#files_preview").attr('src',url_image);

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

    //Croppie
    var upload_crop_img = null;
    upload_crop_img = $('#modal-croppie-canvas').croppie({
		enableExif: true,
		viewport: {width: image_width, height: image_height},
		boundary: {width: parseInt(image_width)+10, height: parseInt(image_height)+10},
		url: url_image,
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
                }
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
    $("#voucher_date_start, #voucher_date_end").datepicker({
        // defaultDate: new Date(),
        minDate:0,
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
        voucher_table.ajax.reload();
    });

    //Autonumeric
    const autoNumericOption = {
        digitGroupSeparator : ',', 
        decimalCharacter  : '.',
        decimalCharacterAlternative: '.', 
        decimalPlaces: 0,
        watchExternalChanges: true
    };
    new AutoNumeric('#voucher_minimum_transaction', autoNumericOption);
    new AutoNumeric('#voucher_price', autoNumericOption);
    new AutoNumeric('#voucher_discount_percentage', autoNumericOption);        

    //Datatable
    let voucher_table = $("#table_data").DataTable({
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
                d.filter_type = $("#filter_type").find(':selected').val();                
                d.search = {value:$("#filter_search").val()};
            },
            dataSrc: function(data) {
                return data.result;
            }
        },
        "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        "columnDefs": [
            {"targets":0, "width":"15%", "title":"Tanggal Terbit", "searchable":true, "orderable":true},
            {"targets":1, "width":"20%", "title":"Gambar", "searchable":false, "orderable":false},
            {"targets":2, "width":"25%", "title":"Kode Voucher/Promo", "searchable":true, "orderable":true},
            {"targets":3, "width":"20%", "title":"Informasi", "searchable":true, "orderable":true},
            {"targets":4, "width":"20%", "title":"Status & Action", "searchable":false, "orderable":false},
        ],
        "order": [[0, 'DESC']],
        "columns": [
            {
                'data': 'voucher_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += row.voucher_date_created_format+'<br>';
                    dsp += row.voucher_date_created_time_ago;                    
                    return dsp;
                }
            },{
                'data': 'voucher_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += '<img src="'+row.voucher_url+'" style="width:174px;" class="img-responsive files_link"><br>';
                    return dsp;
                }
            },{
                'data': 'voucher_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    if(row.voucher_type == 1){ //Voucher
                        dsp += '<label class="label" style="background-color:#fac870;color:#00000;">'+row.voucher_type_name+'</label>&nbsp;';
                        dsp += '<label class="label" style="">Rp. '+addCommas(row.voucher_price)+'</label><br>';
                        dsp += '<label class="label label-inverse" style="position:relative;top:6px;">'+row.voucher_code+'</label><br>';
                        if(row.voucher_title != undefined){
                            dsp += '<b style="position:relative;top:8px;">'+row.voucher_title+'</b>';
                        }                                                
                    }else if(row.voucher_type == 2){ //Promo
                        dsp += '<label class="label" style="background-color:#ff9daf;color:#000000;">'+row.voucher_type_name+'</label>&nbsp;';
                        dsp += '<label class="label label-inverse">'+row.voucher_code+'</label><br>';
                        // dsp += '<label class="label label-inverse">Diskon '+row.voucher_discount_percentage+'%</label><br>';
                        // dsp += '<label class="label" style="position:relative;top:6px;">Diskon '+row.voucher_discount_percentage+'%</label><br>';
                        if(row.voucher_title != 'undefined'){
                            dsp += '<b style="position:relative;top:4px;">'+row.voucher_title+'</b>';
                        }                                                
                    }

                    // dsp += row.voucher_code;
                    // if(row.contact_email_1 != undefined){
                        // dsp += '<br>'+row.contact_email_1;
                    // }
                    return dsp;
                }
            },{
                'data': 'voucher_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    if(row.voucher_status == 'Available'){
                        dsp += '<label class="label label-primary">'+row.voucher_status+'</label>&nbsp;';
                        dsp += '<label class="label label-success">'+row.voucher_expired_day+' hari</label><br>';
                        dsp += '<label class="label label-inverse" style="position:relative;top:6px;">'+row.voucher_period+'</label>';
                    }else if(row.voucher_status == 'Expired'){
                        dsp += '<label class="label label-danger">'+row.voucher_status+'</label><br>';
                        dsp += '<label class="label label-danger" style="position:relative;top:6px;">'+row.voucher_period+'</label>';
                    }
                    // if(row.contact_email_1 != undefined){
                        // dsp += '<br>'+row.contact_email_1;
                    // }
                    return dsp;
                }
            },{
                'data': 'voucher_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = ''; var label = 'Error Status'; var icon = 'fas fa-cog'; var color = 'white'; var bgcolor = '#d1dade';
                    if(parseInt(row.voucher_flag) == 1){
                    //  dsp += '<label style="color:#6273df;">Aktif</label>';
                       label = 'Aktif';
                       icon = 'fas fa-lock';
                       bgcolor = '#0090d9';
                    }else if(parseInt(row.voucher_flag) == 4){
                       //  dsp += '<label style="color:#ff194f;">Terhapus</label>';
                       label = 'Terhapus';
                       icon = 'fas fa-trash';
                       bgcolor = '#f35958';
                    }else if(parseInt(row.voucher_flag) == 0){
                       //   dsp += '<label style="color:#ff9019;">Nonaktif</label>';
                       label = 'Nonaktif';
                       icon = 'fas fa-unlock';
                       // color = 'green';
                       bgcolor = '#ff9019';
                    }


                        /* Button Action Concept 1 */
                        // dsp += '&nbsp;<button class="btn btn_edit_voucher btn-mini btn-primary"';
                        // dsp += 'data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="'+row.voucher_flag+'" data-voucher-session="'+row.voucher_session+'">';
                        // dsp += '<span class="fas fa-edit primary"></span> Edit</button>';

                        // dsp += '&nbsp;<button class="btn btn_delete_voucher btn-mini btn-danger"';
                        // dsp += 'data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="4" data-voucher-session="'+row.voucher_session+'">';
                        // dsp += '<span class="fas fa-trash danger"></span> Hapus</button>';

                        // if (parseInt(row.voucher_flag) === 1) {
                        //   dsp += '&nbsp;<button class="btn btn_update_flag_voucher btn-mini btn-primary" style="background-color:#ff9019;"';
                        //   dsp += 'data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="0" data-voucher-session="'+row.voucher_session+'">';
                        //   dsp += '<span class="fas fa-ban"></span> Nonaktifkan</button>';                      
                        // }else if (parseInt(row.voucher_flag) === 0) {
                        //   dsp += '&nbsp;<button class="btn btn_update_flag_voucher btn-mini btn-primary" style="background-color:#6273df;"';
                        //   dsp += 'data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="1" data-voucher-session="'+row.voucher_session+'">';
                        //   dsp += '<span class="fas fa-check primary"></span> Aktifkan</button>';
                        // }

                       /* Button Action Concept 2 */
                       dsp += '<div class="btn-group">';
                       // dsp += '    <button class="btn btn-mini btn-default" style="background-color:'+bgcolor+';color:'+color+'"><span class="'+icon+'"></span> '+label+'</button>';
                       dsp += '    <button class="btn btn-mini btn-default dropdown-toggle btn-demo-space" style="background-color:'+bgcolor+';color:'+color+';" data-toggle="dropdown" aria-expanded="true"><span class="'+icon+'"></span> '+label+' <span class="caret" style="color:'+color+'"></span> </button>';
                       dsp += '    <ul class="dropdown-menu">';
                                dsp += '        <li>';
                                dsp += '            <a class="btn_edit_voucher" style="cursor:pointer;"';
                                dsp += '                data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="'+row.voucher_flag+'" data-voucher-session="'+row.voucher_session+'">';
                                dsp += '                <span class="fas fa-eye"></span> Lihat';
                                dsp += '            </a>';
                                dsp += '        </li>';                       
                       if(parseInt(row.voucher_flag) == 1){
                               dsp += '<li>';
                               dsp += '    <a class="btn_update_flag_voucher" style="cursor:pointer;"';
                               dsp += '        data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="0" data-voucher-session="'+row.voucher_session+'">';
                               dsp += '        <span class="fas fa-ban"></span> Nonaktifkan';
                               dsp += '    </a>';
                               dsp += '</li>';
                       }else if(parseInt(row.voucher_flag) == 0) {
                               dsp += '<li>'; 
                               dsp += '    <a class="btn_update_flag_voucher" style="cursor:pointer;"';
                               dsp += '        data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="1" data-voucher-session="'+row.voucher_session+'">';
                               dsp += '        <span class="fas fa-lock"></span> Aktifkan';
                               dsp += '    </a>';
                               dsp += '</li>';
                       }
                               dsp += '<li>';
                               dsp += '    <a class="btn_update_flag_voucher" style="cursor:pointer;"';
                               dsp += '        data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="4" data-voucher-session="'+row.voucher_session+'">';
                               dsp += '        <span class="fas fa-trash"></span> Hapus';
                               dsp += '    </a>';
                               dsp += '</li>';                       
                       dsp += '    </ul>';
                       dsp += '</div>';

                    //    /* Button Action Concept 2 */
                    //    dsp += '&nbsp;<div class="btn-group">';
                    //    // dsp += '    <button class="btn btn-mini btn-default"><span class="fas fa-cog"></span></button>';
                    //    dsp += '    <button class="btn btn-mini btn-default dropdown-toggle btn-demo-space" data-toggle="dropdown" aria-expanded="true">Action <span class="caret"></span></button>';
                    //    dsp += '    <ul class="dropdown-menu">';
                    //    dsp += '        <li>';
                    //    dsp += '            <a class="btn_edit_voucher" style="cursor:pointer;"';
                    //    dsp += '                data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="'+row.voucher_flag+'" data-voucher-session="'+row.voucher_session+'">';
                    //    dsp += '                <span class="fas fa-eye"></span> Lihat';
                    //    dsp += '            </a>';
                    //    dsp += '        </li>';
                    //    if(parseInt(row.voucher_flag) === 0) {
                    //            dsp += '<li>'; 
                    //            dsp += '    <a class="btn_update_flag_voucher" style="cursor:pointer;"';
                    //            dsp += '        data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="1" data-voucher-session="'+row.voucher_session+'">';
                    //            dsp += '        <span class="fas fa-lock"></span> Aktifkan';
                    //            dsp += '    </a>';
                    //            dsp += '</li>';
                    //    }else if(parseInt(row.voucher_flag) === 1){
                    //            dsp += '<li>';
                    //            dsp += '    <a class="btn_update_flag_voucher" style="cursor:pointer;"';
                    //            dsp += '        data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="0" data-voucher-session="'+row.voucher_session+'">';
                    //            dsp += '        <span class="fas fa-ban"></span> Nonaktifkan';
                    //            dsp += '    </a>';
                    //            dsp += '</li>';
                    //    }
                    // //    if((parseInt(row.voucher_flag) < 1) || (parseInt(row.voucher_flag) == 4)) {
                    //            dsp += '<li>';
                    //            dsp += '    <a class="btn_update_flag_voucher" style="cursor:pointer;"';
                    //            dsp += '        data-voucher-id="'+data+'" data-voucher-code="'+row.voucher_code+'" data-voucher-flag="4" data-voucher-session="'+row.voucher_session+'">';
                    //            dsp += '        <span class="fas fa-trash"></span> Hapus';
                    //            dsp += '    </a>';
                    //            dsp += '</li>';
                    // //    }
                    // //    dsp += '        <li class="divider"></li>';
                    // //    dsp += '        <li>';
                    // //    dsp += '            <a class="btn_print_voucher" style="cursor:pointer;" data-voucher="'+ data +'" data-voucher-session="'+row.voucher_session+'">';
                    // //    dsp += '                <span class="fas fa-print"></span> Print';
                    // //    dsp += '            </a>';
                    // //    dsp += '        </li>';
                    //    dsp += '    </ul>';
                    //    dsp += '</div>';
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
    $("#table_data_filter").css('display','none');
    $("#table_data_length").css('display','none');
    $("#filter_length").on('change', function(e){
        var value = $(this).find(':selected').val(); 
        $('select[name="table-data_length"]').val(value).trigger('change');
        voucher_table.ajax.reload();
    });
    $("#filter_flag").on('change', function(e){ voucher_table.ajax.reload(); });
    $("#filter_type").on('change', function(e){ voucher_table.ajax.reload(); });
    $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ voucher_table.ajax.reload(); }else if(parseInt(ln) < 1){ voucher_table.ajax.reload();} });
    $('#table-data').on('page.dt', function () {
        var info = vouchers.page.info();
        var limit_start = info.start;
        var limit_end = info.end;
        var length = info.length;
        var page = info.page;
        var pages = info.pages;
        // console.log( 'Showing page: '+info.page+' of '+info.pages);
        // console.log(limit_start,limit_end);
        $("#table-data-in").attr('data-limit-start',limit_start);
        $("#table-data-in").attr('data-limit-end',limit_end);
    });

    //CRUD
    $(document).on("click","#btn_save_voucher",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next =true;
        var voucher_type = $("#voucher_type").find(':selected').val();
        if($("#voucher_type").val().length == 0){
            notif(0,'Jenis wajib diisi');
            $("#voucher_type").focus();
            next=false;
        // }else if($("#voucher_code").val().length == 0){
        //     notif(0,'voucher_code wajib diisi');
        //     $("#voucher_code").focus();
        //     next=false;
        // }else if($("#voucher_note").val().length == 0){
        //     notif(0,'voucher_NOTE wajib diisi');
        //     $("#voucher_note").focus();
        //     next=false;
        // }else if($("#voucher_flag").find(':selected').val() == 0){
        //     notif(0,'voucher_FLAG wajib diisi');
        //     $("#voucher_flag").focus();
        //     next=false;

        }else{
            if(voucher_type == 2){
                if($("#voucher_discount_percentage").val() == 100){
                    notif(0,'Diskon harus dibawah 100%');
                    next=false;
                }
            }
            if(next){
                var form = new FormData($("#form_voucher")[0]);
                // var form = new FormData();
                form.append('upload1', $("#files_preview").attr('data-save-img'));
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
                            formVoucherReset();
                            $("#btn_new_voucher").show();
                            /* hint zz_for or zz_each */
                            voucher_table.ajax.reload();
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
    });
    $(document).on("click",".btn_edit_voucher",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var id       = $(this).attr('data-voucher-id');
        var session  = $(this).attr('data-voucher-session');
        var name     = $(this).attr('data-voucher-code');

        var form = new FormData();
        form.append('action', 'read');
        form.append('voucher_id', id);
        form.append('voucher_session', session);
        form.append('voucher_code', name);
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
                    $("#div_form_voucher").show(300);
                    mode_edit = 1;
                    formVoucherType(mode_edit,d.result.voucher_type);
                    // setInterval(function(){ 
                        // formVoucherSetDisplay(1);
                    // }, 1000);                    
                    var dd1 = d.result.voucher_date_start.substr(8, 2);
                    var mm1 = d.result.voucher_date_start.substr(5, 2);
                    var yy1 = d.result.voucher_date_start.substr(0, 4);
                    var set_date1 = dd1 + '-' + mm1 + '-' + yy1;

                    var dd2 = d.result.voucher_date_end.substr(8, 2);
                    var mm2 = d.result.voucher_date_end.substr(5, 2);
                    var yy2 = d.result.voucher_date_end.substr(0, 4);
                    var set_date2 = dd2 + '-' + mm2 + '-' + yy2;                    
                    
                    $("#voucher_id").val(d.result.voucher_id);
                    $("#voucher_session").val(d.result.voucher_session);
                    $("#voucher_type").val(d.result.voucher_type).trigger('change');
                    $("#voucher_code").val(d.result.voucher_code);
                    $("#voucher_title").val(d.result.voucher_title);
                    // $('#voucher_note').summernote('code', d.result.voucher_note);
                    $("#voucher_flag").val(d.result.voucher_flag).trigger('change');
                    $("#voucher_date_start").datepicker("update", set_date1);
                    $("#voucher_date_end").datepicker("update", set_date2);

                    $("#voucher_minimum_transaction").val(d.result.voucher_minimum_transaction);
                    $("#voucher_price").val(d.result.voucher_price);
                    $("#voucher_discount_percentage").val(d.result.voucher_discount_percentage);                                        
                    $("#voucher_flag").val(d.result.voucher_flag).trigger('change');                    

                    $("#files_preview").attr('src',d.result.voucher_url);
                    $(".files_link").attr('href',d.result.voucher_url);

                    $("#btn_new_voucher").hide();
                    $("#btn_save_voucher").hide();
                    $("#btn_update_voucher").hide();
                    $("#btn_cancel_voucher").show();
                    // scrollUp('content');


                    //loadVoucherItem(r.voucher_id);
                    //formVoucherItemSetDisplay(0);
                }else{
                    $("#div_form_voucher").hide(300);
                    notif(0,d.message);
                }
            },
            error:function(xhr, Status, err){
                notif(0,'Error');
            }
        });
    });
    $(document).on("click","#btn_update_voucher",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next =true;
        var id = $("#voucher_id").val();
        var session = $("#voucher_session").val();
        if(parseInt(id) > 0){
            if($("#voucher_type").val().length == 0){
                notif(0,'voucher_TYPE wajib diisi');
                $("#voucher_type").focus();
                next=false;
            }else if($("#voucher_code").val().length == 0){
                notif(0,'voucher_code wajib diisi');
                $("#voucher_code").focus();
                next=false;
            }else if($("#voucher_note").val().length == 0){
                notif(0,'voucher_NOTE wajib diisi');
                $("#voucher_note").focus();
                next=false;
            }else if($("#voucher_flag").val().length == 0){
                notif(0,'voucher_FLAG wajib diisi');
                $("#voucher_flag").focus();
                next=false;
            }else{
                var form = new FormData($("#form_voucher")[0]);
                form.append('action', 'update');
                form.append('voucher_id', id);
                form.append('voucher_session', session);
                form.append('upload1', $("#files_preview").attr('data-save-img'));
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
                            formVoucherReset();
                            notif(s,m);
                            voucher_table.ajax.reload(null,false);
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
    $(document).on("click",".btn_delete_voucher",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next     = true;
        var id       = $(this).attr('data-voucher-id');
        var session  = $(this).attr('data-voucher-session');
        var name     = $(this).attr('data-voucher-code');

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
                        form.append('voucher_id', id);
                        form.append('voucher_session', session);
                        form.append('voucher_code', name);
                        form.append('voucher_flag', 4);

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
                                    voucher_table.ajax.reload(null,false);
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

    $(document).on("click",".btn_update_flag_voucher",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next     = true;
        var id       = $(this).attr('data-voucher-id');
        var session  = $(this).attr('data-voucher-session');
        var name     = $(this).attr('data-voucher-code');
        var flag     = $(this).attr('data-voucher-flag');

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
                        form.append('voucher_id', id);
                        form.append('voucher_session', session);
                        form.append('voucher_code', name);
                        form.append('voucher_flag', set_flag);

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
                                    voucher_table.ajax.reload(null,false);
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

    //Additional
    $(document).on("click","#btn_new_voucher",function(e) {
        formVoucherReset();
        formVoucherSetDisplay(0);
        $("#div_form_voucher").show(300)
        $("#btn_new_voucher").hide(300)
    });
    $(document).on("click","#btn_cancel_voucher",function(e) {
        formVoucherReset();
        formVoucherSetDisplay(1);
        $("#div_form_voucher").hide(300);
        $("#btn_new_voucher").show(300);
        mode_edit = 0;
    });
    $(document).on("click",".btn_print_voucher",function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log($(this));
        var id = $(this).attr('data-voucher-id');
        var session = $(this).attr('data-voucher-session');
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
    $(document).on("click","#btn_export_voucher",function(e) {
        e.stopPropagation();
        $.alert('Fungsi belum dibuat');
    });
    $(document).on("click","#btn_print_voucher",function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log($(this));
        // var id = $(this).attr('data-voucher-id');
        $.alert('Fungsi belum dibuat');
    });
    $(document).on("click","#btn_cancel_voucher_item",function(e) {
        formVoucherItemReset();
    });
    $(document).on("change","#voucher_type",function(e) {
        e.preventDefault();
        e.stopPropagation();
        let id = $(this).find(':selected').val();
        formVoucherType(mode_edit,id);
    });
    
    function loadVoucherItem(voucher_id = 0){
        if(parseInt(voucher_id) > 0){
            $.ajax({
                type: "post",
                url: "<?= base_url('voucher'); ?>",
                data: {
                    action:'load_voucher_item_2',
                    voucher_item_voucher_id:voucher_id
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
    function formVoucherType(editable,voucher_type){
        var vmt = $("#voucher_minimum_transaction");
        var vp  = $("#voucher_price");        
        var vdp = $("#voucher_discount_percentage");                
        
        if(editable == 0){
            //Default
            vmt.attr('readonly',true);
            vp.attr('readonly',true);
            vdp.attr('readonly',true);
            
            if(voucher_type==1){ //Voucher
                vmt.attr('readonly',false);
                vp.attr('readonly',false);
                // vdp.attr('readonly',false);                        
            }else if(voucher_type==2){ //Promo   
                vdp.attr('readonly',false);
            }
        }
    }

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
		e.preventDefault();
		e.stopPropagation();
		$("#files").val('');
		$("#files_preview").attr('data-save-img', '');
		$("#files_preview").attr('src', url_image);
        $("#files_link").attr('href', url_image);
	});
	$(document).on('click', '#modal-croppie-save', function(e){
		e.preventDefault();
		e.stopPropagation();
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
    function formVoucherReset(){
        $("#form_voucher input")
        .not("input[id='voucher_hour']")
        .not("input[id='voucher_date']")
        .not("input[id='voucher_date_start']")
        .not("input[id='voucher_date_end']").val('');
        $("#form_voucher textarea").val('');
        
        $("#voucher_type").val(0).trigger('change');
        $("#voucher_minimum_transaction").attr('readonly');
        $("#voucher_discount_percentage").attr('readonly');
        
        $("#voucher_price").attr('readonly');        
        $("#files_link").attr('href',url_image);
        $("#files_preview").attr('src',url_image);
        $("#files_preview").attr('data-save-img','');

        $("#new_save_voucher").show();
        $("#btn_save_voucher").show();
        $("#btn_update_voucher").hide();
        $("#btn_cancel_voucher").show();
        $("#div_form_voucher").hide(300);
    } 
}); //End of Document Ready

// window.setInterval(loadPlugin(),3000);
function loadPlugin(){
}
function formVoucherSetDisplay(value){ // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
    if(value == 1){ var flag = true; }else{ var flag = false; }
    //Attr Input yang perlu di setel
    var form = '#form_voucher'; 
    var attrInput = [
       "voucher_code","voucher_title",
    //    "voucher_discount_percentage","voucher_minimum_transaction","voucher_price"
    ];
    for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag);
    }
    //Attr Textarea yang perlu di setel
    var attrText = [
       "voucher_title"
    ];
    for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

    //Attr Select yang perlu di setel
    var atributSelect = [
       "voucher_flag",
       "voucher_type",
    ];
    for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag);
    }
}
</script>