<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    let identity = 0;
    let url = "<?= base_url('printer'); ?>";
    let url_print = "<?= base_url('printer'); ?>";
    let url_tool = "<?= base_url('search/manage'); ?>";

    $(function() {
        // setInterval(function(){ 
        //     //SummerNote
        //     $('#printer_note').summernote({
        //         placeholder: 'Tulis keterangan disini!',
        //         tabsize: 4,
        //         height: 200
        //     });  
        // }, 3000);
    });

    $('.upload1-link').magnificPopup({
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
    $("#printer_date").datepicker({
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
        printer_table.ajax.reload();
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
    let printer_table = $("#table-data").DataTable({
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
            {"targets":0, "width":"30%", "title":"Nama Printer", "searchable":true, "orderable":true},
            {"targets":1, "width":"20%", "title":"Tipe", "searchable":true, "orderable":true},
            {"targets":2, "width":"20%", "title":"Status", "searchable":false, "orderable":true},            
            {"targets":3, "width":"20%", "title":"Action", "searchable":true, "orderable":true},
        ],
        "order": [[0, 'ASC']],
        "columns": [
            {
                'data': 'printer_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += row.printer_name;
                    return dsp;
                }
            },{
                'data': 'printer_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    if(row.printer_type == 1){
                        dsp += 'Deskjet';
                    }else if(row.printer_type == 2){
                        dsp += 'Dot Matrik';
                    }else if(row.printer_type == 3){
                        dsp += 'Label Works';
                    }else if(row.printer_type == 4){
                        dsp += 'Receipt Thermal';
                    }else{
                        dsp += '-';
                    }
                    return dsp;
                }
            },{
                'data': 'printer_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    if(row.printer_flag == 1){
                        dsp += 'Aktif';
                    }else if(row.printer_flag == 4){
                        dsp += 'Terhapus';
                    }else{
                        dsp += 'Nonaktif';
                    }
                    return dsp;
                }
            },{                
                'data': 'printer_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = ''; var label = 'Error Status'; var icon = 'fas fa-cog'; var color = 'white'; var bgcolor = '#d1dade';
                    if(parseInt(row.printer_flag) == 1){
                    //  dsp += '<label style="color:#6273df;">Aktif</label>';
                       label = 'Aktif';
                       icon = 'fas fa-lock';
                       bgcolor = '#0aa699';
                    }else if(parseInt(row.printer_flag) == 4){
                       //  dsp += '<label style="color:#ff194f;">Terhapus</label>';
                       label = 'Terhapus';
                       icon = 'fas fa-trash';
                       bgcolor = '#f35958';
                    }else if(parseInt(row.printer_flag) == 0){
                       //   dsp += '<label style="color:#ff9019;">Nonaktif</label>';
                       label = 'Nonaktif';
                       icon = 'fas fa-unlock';
                       // color = 'green';
                       bgcolor = '#ff9019';
                    }

                       /* Button Action Concept 2 */
                       dsp += '&nbsp;<div class="btn-group">';
                       // dsp += '    <button class="btn btn-mini btn-default"><span class="fas fa-cog"></span></button>';
                       dsp += '    <button class="btn btn-mini btn-default dropdown-toggle btn-demo-space" data-toggle="dropdown" aria-expanded="true"><span class="fas fa-cog"></span><span class="caret"></span> </button>';
                       dsp += '    <ul class="dropdown-menu">';
                       dsp += '        <li>';
                       dsp += '            <a class="btn_edit_printer" style="cursor:pointer;"';
                       dsp += '                data-printer-id="'+data+'" data-printer-name="'+row.printer_name+'" data-printer-flag="'+row.printer_flag+'" data-printer-session="'+row.printer_session+'">';
                       dsp += '                <span class="fas fa-edit"></span> Edit';
                       dsp += '            </a>';
                       dsp += '        </li>';
                       // if(parseInt(row.printer_flag) === 0) {
                               dsp += '<li>'; 
                               dsp += '    <a class="btn_update_flag_printer" style="cursor:pointer;"';
                               dsp += '        data-printer-id="'+data+'" data-printer-name="'+row.printer_name+'" data-printer-flag="'+row.printer_flag+'" data-printer-session="'+row.printer_session+'">';
                               dsp += '        <span class="fas fa-lock"></span> Aktifkan';
                               dsp += '    </a>';
                               dsp += '</li>';
                       // }else if(parseInt(row.printer_flag) === 1){
                               dsp += '<li>';
                               dsp += '    <a class="btn_update_flag_printer" style="cursor:pointer;"';
                               dsp += '        data-printer-id="'+data+'" data-printer-name="'+row.printer_name+'" data-printer-flag="'+row.printer_flag+'" data-printer-session="'+row.printer_session+'">';
                               dsp += '        <span class="fas fa-ban"></span> Nonaktifkan';
                               dsp += '    </a>';
                               dsp += '</li>';
                       // }
                       if((parseInt(row.printer_flag) < 1) || (parseInt(row.printer_flag) == 4)) {
                               dsp += '<li>';
                               dsp += '    <a class="btn_update_flag_printer" style="cursor:pointer;"';
                               dsp += '        data-printer-id="'+data+'" data-printer-name="'+row.printer_name+'" data-printer-flag="4" data-printer-session="'+row.printer_session+'">';
                               dsp += '        <span class="fas fa-trash"></span> Hapus';
                               dsp += '    </a>';
                               dsp += '</li>';
                       }
                       dsp += '    </ul>';
                       dsp += '</div>';

                       /* Button Action Concept 2 */
                    //    dsp += '&nbsp;<div class="btn-group">';
                    //    // dsp += '    <button class="btn btn-mini btn-default" style="background-color:'+bgcolor+';color:'+color+'"><span class="'+icon+'"></span> '+label+'</button>';
                    //    dsp += '    <button class="btn btn-mini btn-default dropdown-toggle btn-demo-space" style="background-color:'+bgcolor+';color:'+color+';" data-toggle="dropdown" aria-expanded="true"><span class="'+icon+'"></span> '+label+' <span class="caret" style="color:'+color+'"></span> </button>';
                    //    dsp += '    <ul class="dropdown-menu">';
                    //    if(parseInt(row.printer_flag) == 1){
                    //            dsp += '<li>';
                    //            dsp += '    <a class="btn_update_flag_printer" style="cursor:pointer;"';
                    //            dsp += '        data-printer-id="'+data+'" data-printer-name="'+row.printer_name+'" data-printer-flag="'+row.printer_flag+'" data-printer-session="'+row.printer_session+'">';
                    //            dsp += '        <span class="fas fa-ban"></span> Nonaktifkan';
                    //            dsp += '    </a>';
                    //            dsp += '</li>';
                    //    }else if(parseInt(row.printer_flag) == 0) {
                    //            dsp += '<li>'; 
                    //            dsp += '    <a class="btn_update_flag_printer" style="cursor:pointer;"';
                    //            dsp += '        data-printer-id="'+data+'" data-printer-name="'+row.printer_name+'" data-printer-flag="'+row.printer_flag+'" data-printer-session="'+row.printer_session+'">';
                    //            dsp += '        <span class="fas fa-lock"></span> Aktifkan';
                    //            dsp += '    </a>';
                    //            dsp += '</li>';
                    //    }
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
    $("#table-data_filter").css('display','none');
    $("#table-data_length").css('display','none');
    $("#filter_length").on('change', function(e){
        var value = $(this).find(':selected').val(); 
        $('select[name="table-data_length"]').val(value).trigger('change');
        printer_table.ajax.reload();
    });
    $("#filter_flag").on('change', function(e){ printer_table.ajax.reload(); });
    $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ printer_table.ajax.reload(); }else if(parseInt(ln) < 1){ printer_table.ajax.reload();} });
    $('#table-data').on('page.dt', function () {
        var info = printers.page.info();
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
    $(document).on("click","#btn_save_printer",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next =true;
        if($("#printer_type").val().length == 0){
            notif(0,'Tipe wajib diisi');
            $("#printer_type").focus();
            next=false;
        }else if($("#printer_name").val().length == 0){
            notif(0,'Nama wajib diisi');
            $("#printer_name").focus();
            next=false;
        }else{
            var form = new FormData($("#form-printer")[0]);
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
                        // formPrinterReset();
                        loadPrinterItem(r.id);
                        formPrinterItemSetDisplay(0);
                        $("#printer_id").val(r.id);
                        /* hint zz_for or zz_each */
                        printer_table.ajax.reload();
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
    $(document).on("click",".btn_edit_printer",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var id       = $(this).attr('data-printer-id');
        var session  = $(this).attr('data-printer-session');
        var name     = $(this).attr('data-printer-name');

        var form = new FormData();
        form.append('action', 'read');
        form.append('printer_id', id);
        form.append('printer_session', session);
        form.append('printer_name', name);
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
                    $("#div-form-printer").show(300);
                    
                    $("#printer_id").val(d.result.printer_id);
                    $("#printer_session").val(d.result.printer_session);
                    $("#printer_type").val(d.result.printer_type).trigger('change');
                    $("#printer_name").val(d.result.printer_name);
                    $("#printer_ip").val(d.result.printer_ip);                    
                    // $("#printer_note").val(d.result.printer_note);
                    $("#printer_flag").val(d.result.printer_flag).trigger('change');
                    // $("#printer_date_created").val(d.result.printer_date_created);

                    $("#btn_new_printer").hide();
                    $("#btn_save_printer").hide();
                    $("#btn_update_printer").show();
                    $("#btn_cancel_printer").show();

                    $("#btn_update_printer_item").hide(300);
                    $("#btn_save_printer_item").show(300);                    
                    // scrollUp('content');
                    formPrinterSetDisplay(0);
                    loadPrinterItem(r.printer_id);
                    formPrinterItemSetDisplay(0);
                }else{
                    $("#div-form-printer").hide(300);
                    notif(0,d.message);
                }
            },
            error:function(xhr, Status, err){
                notif(0,'Error');
            }
        });
    });
    $(document).on("click","#btn_update_printer",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next =true;
        var id = $("#printer_id").val();
        var session = $("#printer_session").val();
        if(parseInt(id) > 0){
            if($("#printer_type").val().length == 0){
                notif(0,'printer_TYPE wajib diisi');
                $("#printer_type").focus();
                next=false;
            }else if($("#printer_name").val().length == 0){
                notif(0,'printer_NAME wajib diisi');
                $("#printer_name").focus();
                next=false;
            }else if($("#printer_flag").val().length == 0){
                notif(0,'printer_FLAG wajib diisi');
                $("#printer_flag").focus();
                next=false;
            }else{
                var form = new FormData($("#form-printer")[0]);
                form.append('action', 'update');
                form.append('printer_id', id);
                form.append('printer_session', session);
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
                            // formReset();
                            notif(s,m);
                            printer_table.ajax.reload(null,false);
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
    $(document).on("click",".btn_delete_printer",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next     = true;
        var id       = $(this).attr('data-printer-id');
        var session  = $(this).attr('data-printer-session');
        var name     = $(this).attr('data-printer-name');

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
                        form.append('printer_id', id);
                        form.append('printer_session', session);
                        form.append('printer_name', name);
                        form.append('printer_flag', 4);

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
                                    notif(s,d.message); 
                                    printer_table.ajax.reload(null,false);
                                }else{ 
                                    notif(s,d.message); 
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

    $(document).on("click",".btn_update_flag_printer",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next     = true;
        var id       = $(this).attr('data-printer-id');
        var session  = $(this).attr('data-printer-session');
        var name     = $(this).attr('data-printer-name');
        var flag     = $(this).attr('data-printer-flag');

        if(parseInt(flag) == 0){
            var set_flag = 1;
            var msg = 'mengaktifkan';
        }else if(parseInt(flag) == 1){
            var set_flag = 0;
            var msg = 'menonaktifkan';
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
                        form.append('printer_id', id);
                        form.append('printer_session', session);
                        form.append('printer_name', name);
                        form.append('printer_flag', set_flag);

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
                                    printer_table.ajax.reload(null,false);
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
    $(document).on("click","#btn_save_printer_item",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var id = $("#printer_id").val();
        var next = true;
        if(parseInt(id) > 0){
            if($("#printer_paper_width").val().length == 0){
                notif(0,'Lebar wajib diisi');
                $("#printer_paper_width").focus();
                next=false;
            }else if($("#printer_paper_height").val().length == 0){
                notif(0,'Panjang wajib diisi');
                $("#printer_paper_height").focus();
                next=false;
            }else{
                let form = new FormData($("#form-printer-item")[0]);
                form.append('action', 'create_printer_item');
                form.append('printer_parent_id',id);
                $.ajax({
                    type: "post",
                    url: url,
                    data: form, 
                    dataType: 'json', cache: 'false', 
                    contentType: false, processData: false,
                    beforeSend:function(){},
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            /* hint zz_for or zz_each */
                            formPrinterItemReset();
                            loadPrinterItem(id);
                            formPrinterItemSetDisplay(0);
                        }else{
                            notif(s,m);
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                    }
                });
            }
        }else{
            notif('Silahkan refresh halaman ini');
        }
    });
    $(document).on("click",".btn_edit_printer_item",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var id = $(this).attr('data-id');
        if(parseInt(id) > 0){
            $.ajax({
                type: "post",
                url: url,
                data: {
                    action:'read_printer_item',
                    printer_id:id,
                }, 
                dataType: 'json', cache: 'false', 
                beforeSend:function(){},
                success:function(d){
                    let s = d.status;
                    let m = d.message;
                    let r = d.result;
                    if(parseInt(s) == 1){
                        formPrinterItemSetDisplay(0);                        
                        notif(s,m);
                        $("#id_printer_item").val(d.result.printer_id);
                        $("#printer_paper_design").val(d.result.printer_paper_design);
                        $("#printer_paper_width").val(d.result.printer_paper_width);
                        $("#printer_paper_height").val(d.result.printer_paper_height);      
                        // $('#printer_note').summernote('code', d.result.printer_note);
                        $("#btn_update_printer_item").show(300);
                        $("#btn_cancel_printer_item").show(300);                                          
                        $("#btn_save_printer_item").hide(300);
                    }else{
                        notif(s,m);
                    }
                },
                error:function(xhr,status,err){
                    notif(0,err);
                }
            });
        }else{
            notif(0,'ID tidak ditemukan'); 
        }
    });
    $(document).on("click","#btn_update_printer_item",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var printer_id = $("#printer_id").val();        
        var id = $("#id_printer_item").val();
        var next = true;
        if(parseInt(id) > 0){
            if($("#printer_paper_width").val().length == 0){
                notif(0,'Lebar wajib diisi');
                $("#printer_paper_width").focus();
                next=false;
            }else if($("#printer_paper_height").val().length == 0){
                notif(0,'Panjang wajib diisi');
                $("#printer_paper_height").focus();
                next=false;
            }else{            
                let form = new FormData($("#form-printer-item")[0]);
                form.append('action', 'update_printer_item');
                form.append('printer_id',id);            
                $.ajax({
                    type: "post",
                    url: url,
                    data: form, 
                    dataType: 'json', cache: 'false',
                    contentType: false, processData: false, 
                    beforeSend:function(){},
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            formPrinterItemReset();
                            loadPrinterItem(printer_id);
                            formPrinterItemSetDisplay(0);
                        }else{
                            notif(s,m);
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                    }
                });
            }
        }else{
            notif(0,'ID tidak ditemukan'); 
        }
    });
    $(document).on("click",".btn_delete_printer_item",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var printer_id = $("#printer_id").val();          
        var id = $(this).attr('data-id');
        if(parseInt(id) > 0){      
            $.ajax({
                type: "post",
                url: url,
                data: {
                    action:'delete_printer_item',
                    printer_id:id
                }, 
                dataType: 'json', cache: 'false',
                // contentType: false, processData: false, 
                beforeSend:function(){},
                success:function(d){
                    let s = d.status;
                    let m = d.message;
                    let r = d.result;
                    if(parseInt(s) == 1){
                        notif(s,m);
                        loadPrinterItem(printer_id);
                    }else{
                        notif(s,m);
                    }
                },
                error:function(xhr,status,err){
                    notif(0,err);
                }
            });
        }else{
            notif(0,'ID tidak ditemukan'); 
        }        
    });

    //Additional
    $(document).on("click","#btn_new_printer",function(e) {
        formPrinterReset();
        formPrinterSetDisplay(0);         
        $("#div-form-printer").show(300);
        $("#btn_new_printer").hide(300);
    });
    $(document).on("click","#btn_cancel_printer",function(e) {
        formPrinterReset();
        $("#div-form-printer").hide(300); 
        $("#btn_new_printer").show(300);          
    });
    $(document).on("click",".btn_print_printer",function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log($(this));
        var id = $(this).attr('data-printer-id');
        var session = $(this).attr('data-printer-session');
        if(parseInt(id) > 0){
            var x = screen.width / 2 - 700 / 2;
            var y = screen.height / 2 - 450 / 2;
            var print_url = url_print+'?action=print&data='+session;
            var win = window.open(print_url,'Print','width=700,height=485,left=' + x + ',top=' + y + '').print();
        }else{
            notif(0,'Dokumen belum di buka');
        }
    });
    $(document).on("click","#btn_export_printer",function(e) {
        e.stopPropagation();
        $.alert('Fungsi belum dibuat');
    });
    $(document).on("click","#btn_print_printer",function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log($(this));
        // var id = $(this).attr('data-printer-id');
        $.alert('Fungsi belum dibuat');
    });
    $(document).on("click","#btn_cancel_printer_item",function(e) {
        formPrinterItemReset();
        formPrinterItemSetDisplay(0);  
    });
   
    $(document).on("click","#btn_test_matrix_printer",function(e) {
        e.preventDefault();
        e.stopPropagation();
    
        var print_url = url_print;
        var i = $(this).attr('data-id');
        var s = $(this).attr('data-session');
    
        /* Option 1 */
        var x = screen.width / 2 - 700 / 2;
        var y = screen.height / 2 - 450 / 2;
        var title = 'Print';
        console.log(url_print);
        window.open(print_url+'/matrix/'+i,'Print','width=700,height=485,left=' + x + ',top=' + y + '').print();
        //window.open(print_url,'_blank');
    
        /* Option 2 */
        // var formData = {
        //     action:'print',
        //     id:i,
        //     session:s
        // };
        // $.post(print_url, formData, function (result) {
        //     var w = window.open(print_url,'Print','width=700,height=485,left=' + x + ',top=' + y + '');
        //     w.document.open();
        //     w.document.write(result);
        //     // w.document.close();
        // });
    
    });
    $(document).on("click","#btn_test_barcode_printer",function(e) {
        e.preventDefault();
        e.stopPropagation();
        var valu = '20230526121239';
        // var valu = $(this).attr('data-session');        
        printDemoBarCodeCustom(valu);
        notif(1,'printDemoBarCodeCustom Started');
    });    
    $(document).on("click","#btn_test_qrcode_printer",function(e) {
        e.preventDefault();
        e.stopPropagation();
        // var valu = $(this).attr('data-session');
        var valu = 'c4737bbf9f6f3816d630b69a52d0a011jbt';
        printQrCodeCustom(valu); 
        notif(1,'printQrCodeCustom Started');
    });

    function loadPrinterItem(printer_id = 0){
        if(parseInt(printer_id) > 0){
            $.ajax({
                type: "post",
                url: "<?= base_url('printer'); ?>",
                data: {
                    action:'load_printer_item_2',
                    printer_item_printer_id:printer_id
                },
                dataType: 'json', cache: 'false', 
                beforeSend:function(){},
                success:function(d){
                    let s = d.status;
                    let m = d.message;
                    let r = d.result;
                    if(parseInt(s) == 1){
                        let r = d.result;
                        let total_records = d.total_records;
                        if(parseInt(total_records) > 0){
                            $("#table_printer_item tbody").html('');
                        
                            let dsp = '';
                            for(let a=0; a < total_records; a++) {
                                let value = r[a];
                                dsp += '<tr>';
                                    // dsp += '<td>'+value['printer_paper_design']+'</td>';
                                    dsp += '<td style="text-align:right;">'+value['printer_paper_width']+'</td>';
                                    dsp += '<td style="text-align:right;">'+value['printer_paper_height']+'</td>';                                    
                                    dsp += '<td>';
                                        dsp += '<button type="button" class="btn-action btn btn-primary btn_edit_printer_item btn-mini btn-small" data-id="'+value['printer_id']+'" data-session="'+value['printer_session']+'">';
                                        dsp += 'Edit';
                                        dsp += '</button>';
                                        dsp += '<button type="button" class="btn-action btn btn-danger btn_delete_printer_item btn-mini btn-small" data-id="'+value['printer_id']+'" data-session="'+value['printer_session']+'">';
                                        dsp += 'Hapus';
                                        dsp += '</button>';                                        
                                    dsp += '</td>';
                                dsp += '</tr>';
                        
                            }
                            $("#table_printer_item tbody").html(dsp);
                        }
                    }else{
                        // notif(s,m);
                    }
                },
                error:function(xhr,status,err){
                    notif(0,err);
                }
            });
        }else{

        }
    }

}); //End of Document Ready

function imageLoad(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#upload1-src')
                .attr('src', e.target.result)
                .width(150)
                .height(150);
            $('.upload1-link')
            .attr('href', e.target.result);
            // $("#gambar-perbarui").removeClass('hide');
            // $("#upload1-hapus").removeClass('hide');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
// window.setInterval(loadPlugin(),3000);
function loadPlugin(){
}
function formPrinterReset(){
    formPrinterSetDisplay(1);
    $("#form-printer input")
    .not("input[id='printer_date_start']")
    .not("input[id='printer_date_end']").val('');
    $("#form-printer textarea").val('');

    $("#btn_save_printer").show();
    $("#btn_update_printer").hide();
    $("#btn_cancel_printer").show();
    $("#div-form-printer").hide(300);    
}
function formPrinterItemReset(){
    formPrinterItemSetDisplay(1);    
    $("#form-printer-item input")
    .not("input[id='printer_item_date_start']")
    .not("input[id='printer_item_date_end']").val('');
    $("#form-printer-item textarea").val('');

    $("#btn_save_printer_item").show(300);
    $("#btn_update_printer_item").hide(300);
    $("#btn_cancel_printer_item").hide();  
}
function formPrinterSetDisplay(value){ // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
    if(value == 1){ var flag = true; }else{ var flag = false; }
    //Attr Input yang perlu di setel
    var form = '#form-printer'; 
    var attrInput = [
       "printer_name","printer_ip"
    ];
    for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag); }

    //Attr Textarea yang perlu di setel
    var attrText = [
       "printer_note"
    ];
    for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

    //Attr Select yang perlu di setel
    var atributSelect = [
       "printer_flag",
       "printer_type",
    ];
    for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag); }
}
function formPrinterItemSetDisplay(value){ // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
    if(value == 1){ var flag = true; }else{ var flag = false; }
    //Attr Input yang perlu di setel
    var form = '#form-printer-item'; 
    var attrInput = [
       "printer_paper_name","printer_paper_design","printer_paper_height","printer_paper_width"
    ];
    for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag); }

    //Attr Textarea yang perlu di setel
    var attrText = [
    ];
    for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

    //Attr Select yang perlu di setel
    var atributSelect = [
    ];
    for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag); }
}
</script>

<script charset="utf-8" async>
    var CodePageType = 'GENERAL'; // 'PT210'
    // ***************************************************
    //  LIBs
    // ***************************************************
    function RawBtTransport() {
        this.send = function (prn) {
            let S = "#Intent;scheme=rawbt;";
            let P = "package=ru.a402d.rawbtprinter;end;";
            let textEncoded = "base64," + btoa(unescape(prn));
            window.location.href = "intent:" + textEncoded + S + P;
        };

        return this;
    }
    function EscPosDriver() {
        this.defaultCodePages = {
            'CP437': 0,
            'CP932': 1,
            'CP850': 2,
            'CP860': 3,
            'CP863': 4,
            'CP865': 5,
            'CP857': 13,
            'CP737': 14,
            'ISO_8859-7': 15,
            'CP1252': 16,
            'CP866': 17,
            'CP852': 18,
            'CP858': 19,
            'ISO88596': 22,
            'WINDOWS1257': 25,
            'CP864': 28,
            'WINDOWS1255': 32,
            'CP861': 56,
            'CP855': 60,
            'CP862': 62,
            'CP869': 66,
            'WINDOWS1250': 72,
            'WINDOWS1251': 73,
            'WINDOWS1253': 90,
            'WINDOWS1254': 91,
            'WINDOWS1256': 92,
            'WINDOWS1258': 94,
            'CP775': 95,
            'CP874': 255,
            'GBK': -1
        };

        this.goojprtCodePages = {
            "CP437": "0",
            "CP932": "1",
            "CP850": "2",
            "CP860": "3",
            "CP863": "4",
            "CP865": "5",
            "CP1251": "6",
            "CP866": "7",
            "CP775": "9",
            "CP862": "15",
            "CP1252": "16",
            "WINDOWS1253": "17",
            "CP852": "18",
            "CP858": "19",
            "CP864": "22",
            "CP737": "24",
            "WINDOWS1257": "25",
            "CP85": "29",
            "WINDOWS1256": "34",
            "CP874": "47",
            'GBK': "-1"
        };


        function intval(mixedVar, base) {
            var tmp, match

            var type = typeof mixedVar

            if (type === 'boolean') {
                return +mixedVar
            } else if (type === 'string') {
                if (base === 0) {
                    match = mixedVar.match(/^\s*0(x?)/i)
                    base = match ? (match[1] ? 16 : 8) : 10
                }
                tmp = parseInt(mixedVar, base || 10)
                return (isNaN(tmp) || !isFinite(tmp)) ? 0 : tmp
            } else if (type === 'number' && isFinite(mixedVar)) {
                return mixedVar < 0 ? Math.ceil(mixedVar) : Math.floor(mixedVar)
            } else {
                return 0
            }
        }

        function chr(x) {
            x = intval(x);
            hexString = x.toString(16);
            if (hexString.length % 2) {
                hexString = '0' + hexString;
            }
            return "%" + hexString;
        }

        this.encodeByte = function (b) {
            return chr(b);
        };

        const LF = chr(10);
        const CR = chr(13);
        const ESC = chr(27);
        const FS = chr(28);
        const GS = chr(29);
        const ON = '1';
        const OFF = '0';

        this.lf = function (lines) {
            if (lines === undefined || lines < 2) {
                return LF + CR;
            } else {
                return ESC + "d" + chr(lines);
            }
        };

        this.alignment = function (aligment) {
            return ESC + "a" + chr(aligment);
        };

        this.cut = function (mode, lines) {
            return GS + "V" + chr(mode) + chr(lines);
        };

        this.feedForm = function () {
            return chr(12);
        };

        /**
            * Some slip printers require `ESC q` sequence to release the paper.
            */
        this.release = function () {
            return ESC + chr(113);
        };


        this.feedReverse = function (lines) {
            return ESC + 'e' + chr(1 * lines);
        };

        this.setPrintMode = function (mode) {
            return ESC + "!" + chr(1 * mode);
        };

        this.barcode = function (content, type) {
            return GS + "k" + chr(1 * type) + chr(content.length) + content;
        };

        this.setBarcodeHeight = function (height) {
            return GS + "h" + chr(1 * height);
        };

        this.setBarcodeWidth = function (width) {
            return GS + "w" + chr(1 * width);
        };

        this.setBarcodeTextPosition = function (position) {
            return GS + "H" + chr(1 * position);
        };

        this.emphasis = function (mode) {
            return ESC + "E" + (mode ? ON : OFF);
        };

        this.underline = function (mode) {
            return ESC + "-" + chr(1 * mode);
        };

        this.initialize = function () {
            return ESC + '@';
        };

        this.setCharacterTable = function (number) {
            if (number < 0) {
                return FS + '&';
            }
            return FS + '.' + ESC + "t" + chr(1 * number);

        };

        this.setDefaultCharacterTable = function (cpname) {
            if (CodePageType == 'PT210') {
                return this.setCharacterTable(this.goojprtCodePages[cpname]);
            }
            return this.setCharacterTable(this.defaultCodePages[cpname]);
        };


        this.wrapperSend2dCodeData = function (fn, cn, data, m) {
            if (data === undefined) {
                data = '';
            }
            if (m === undefined) {
                m = '';
            }
            let n = data.length + m.length + 2;
            header = chr(n % 256) + chr(n / 256);
            return GS + "(k" + header + cn + fn + m + data;
        };

        this.qrCode = function (code, ec, size, model) {
            let r = '';
            let cn = '1'; // Code type for QR code
            // Select model: 1, 2 or micro.
            r += this.wrapperSend2dCodeData(String.fromCharCode(65), cn, String.fromCharCode(48 + 1 * model) + String.fromCharCode(0));
            // Set dot size.
            r += this.wrapperSend2dCodeData(String.fromCharCode(67), cn, String.fromCharCode(1 * size));
            // Set error correction level: L, M, Q, or H
            r += this.wrapperSend2dCodeData(String.fromCharCode(69), cn, String.fromCharCode(48 + 1 * ec));
            // Send content & print
            r += this.wrapperSend2dCodeData(String.fromCharCode(80), cn, code, '0');
            r += this.wrapperSend2dCodeData(String.fromCharCode(81), cn, '', '0');

            return r;
        };

        return this;
    }
    function PosPrinterJob(driver, transport) {
        /**
            * @type EscPosDriver
            */
        this.driver = driver;

        /**
            * @type RawBtTransport
            */
        this.transport = transport;


        this.buffer = [];


        // ----------------------------------
        //  CONFIGURE
        // ----------------------------------
        this.encoding = 'CP437';
        this.characterTable = 0;

        this.setEncoding = function (encoding) {
            this.encoding = encoding;
            this.buffer.push(this.driver.setDefaultCharacterTable(encoding.toUpperCase()));
            return this;
        };
        this.setCharacterTable = function (number) {
            this.characterTable = number;
            this.buffer.push(this.driver.setCharacterTable(number));
            return this;
        };


        // ----------------------------------
        //  SEND TO PRINT
        // ----------------------------------

        this.execute = function () {
            this.transport.send(this.buffer.join(''));
            return this;
        };

        // ----------------------------------
        //  HIGH LEVEL FUNCTION
        // ----------------------------------

        this.initialize = function () {
            this.buffer.push(this.driver.initialize());
            return this;
        };


        /**
            *
            * @param {string} string
            */
        this.print = function (string, encoding) {
            let bytes = iconv.encode(string, encoding || this.encoding);
            let s = '';
            let self = this;
            bytes.forEach(function (b) {
                s = s + self.driver.encodeByte(b);
            });
            this.buffer.push(s);
            return this;
        };

        /**
            *
            * @param {string} string
            */
        this.printLine = function (string, encoding) {
            this.print(string, encoding);
            this.buffer.push(this.driver.lf());
            return this;
        };


        this.printText = function (text, aligment, size) {
            if (aligment === undefined) {
                aligment = this.ALIGNMENT_LEFT;
            }
            if (size === undefined) {
                size = this.FONT_SIZE_NORMAL;
            }
            this.setAlignment(aligment);
            this.setPrintMode(size);
            this.printLine(text);
            return this;
        };


        // ----------------------------------
        //  FONTS
        // ----------------------------------

        // user friendly names
        this.FONT_SIZE_SMALL = 1;
        this.FONT_SIZE_NORMAL = 0;
        this.FONT_SIZE_MEDIUM1 = 33;
        this.FONT_SIZE_MEDIUM2 = 16;
        this.FONT_SIZE_MEDIUM3 = 49;
        this.FONT_SIZE_BIG = 48; // BIG

        // bits for ESC !
        this.FONT_A = 0; // A
        this.FONT_B = 1; // B
        this.FONT_EMPHASIZED = 8;
        this.FONT_DOUBLE_HEIGHT = 16;
        this.FONT_DOUBLE_WIDTH = 32;
        this.FONT_ITALIC = 64;
        this.FONT_UNDERLINE = 128;

        this.setPrintMode = function (mode) {
            this.buffer.push(this.driver.setPrintMode(mode));
            return this;
        };


        this.setEmphasis = this.emphasis = function (mode) {
            this.buffer.push(this.driver.emphasis(mode));
            return this;
        };

        this.bold = function (on) {
            if (on === undefined) {
                on = true;
            }
            this.buffer.push(this.driver.emphasis(on));
            return this;
        };

        this.UNDERLINE_NONE = 0;
        this.UNDERLINE_SINGLE = 1;
        this.UNDERLINE_DOUBLE = 2;

        this.underline = function (mode) {
            if (mode === true || mode === undefined) {
                mode = this.UNDERLINE_SINGLE;
            } else if (mode === false) {
                mode = this.UNDERLINE_NONE;
            }
            this.buffer.push(this.driver.underline(mode));
            return this;
        };


        // ----------------------------------
        //  ALIGNMENT
        // ----------------------------------

        this.ALIGNMENT_LEFT = 0;
        this.ALIGNMENT_CENTER = 1;
        this.ALIGNMENT_RIGHT = 2;

        this.setAlignment = function (aligment) {
            if (aligment === undefined) {
                aligment = this.ALIGNMENT_LEFT;
            }

            this.buffer.push(this.driver.alignment(aligment));
            return this;
        };


        // ----------------------------------
        //  BARCODE
        // ----------------------------------

        this.BARCODE_UPCA = 65;
        this.BARCODE_UPCE = 66;
        this.BARCODE_JAN13 = 67;
        this.BARCODE_JAN8 = 68;
        this.BARCODE_CODE39 = 69;
        this.BARCODE_ITF = 70;
        this.BARCODE_CODABAR = 71;
        this.BARCODE_CODE93 = 72;
        this.BARCODE_CODE128 = 73;

        this.printBarCode = function (content, type) {
            if (type === undefined) {
                type = this.BARCODE_CODE39;
            }
            this.buffer.push(this.driver.barcode(content, type));
            return this;
        };

        /**
            * Set barcode height.
            *
            * height Height in dots. If not specified, 8 will be used.
            */
        this.setBarcodeHeight = function (height) {
            if (height === undefined) {
                height = 30;
            }
            this.buffer.push(this.driver.setBarcodeHeight(height));
            return this;
        };

        /**
            * Set barcode bar width.
            *
            * width Bar width in dots. If not specified, 3 will be used.
            *  Values above 6 appear to have no effect.
            */
        this.setBarcodeWidth = function (width) {
            if (width === undefined) {
                width = 3;
            }
            this.buffer.push(this.driver.setBarcodeWidth(width));
            return this;
        };


        /**
            * Indicates that HRI (human-readable interpretation) text should not be
            * printed, when used with Printer::setBarcodeTextPosition
            */
        this.BARCODE_TEXT_NONE = 0;
        /**
            * Indicates that HRI (human-readable interpretation) text should be printed
            * above a barcode, when used with Printer::setBarcodeTextPosition
            */
        this.BARCODE_TEXT_ABOVE = 1;
        /**
            * Indicates that HRI (human-readable interpretation) text should be printed
            * below a barcode, when used with Printer::setBarcodeTextPosition
            */
        this.BARCODE_TEXT_BELOW = 2;


        /**
            * Set the position for the Human Readable Interpretation (HRI) of barcode characters.
            *
            * position. Use Printer::BARCODE_TEXT_NONE to hide the text (default),
            *  or any combination of Printer::BARCODE_TEXT_ABOVE and Printer::BARCODE_TEXT_BELOW
            *  flags to display the text.
            */
        this.setBarcodeTextPosition = function (position) {
            if (position === undefined) {
                position = this.BARCODE_TEXT_NONE;
            }
            this.buffer.push(this.driver.setBarcodeTextPosition(position));
            return this;
        };

        // ----------------------------------
        //  QRCODE
        // ----------------------------------

        this.QR_ECLEVEL_L = 0;
        this.QR_ECLEVEL_M = 1;
        this.QR_ECLEVEL_Q = 2;
        this.QR_ECLEVEL_H = 3;

        this.QR_SIZES_1 = 1;
        this.QR_SIZES_2 = 2;
        this.QR_SIZES_3 = 3;
        this.QR_SIZES_4 = 4;
        this.QR_SIZES_5 = 5;
        this.QR_SIZES_6 = 6;
        this.QR_SIZES_7 = 7;
        this.QR_SIZES_8 = 8;

        this.QR_MODEL_1 = 1;
        this.QR_MODEL_2 = 2;
        this.QR_MICRO = 3;

        this.printQrCode = function (code, ec, size, model) {
            if (ec === undefined) {
                ec = this.QR_ECLEVEL_L;
            }
            if (size === undefined) {
                size = this.QR_SIZES_3;
            }
            if (model === undefined) {
                model = this.QR_MODEL_2;
            }

            this.buffer.push(this.driver.qrCode(code, ec, size, model));
            return this;
        };


        /**
            * Make a full cut, when used with Printer::cut
            */
        this.CUT_FULL = 65;
        /**
            * Make a partial cut, when used with Printer::cut
            */
        this.CUT_PARTIAL = 66;

        this.cut = function (mode, lines = 3) {
            if (mode === undefined) {
                mode = this.CUT_FULL;
            }
            if (lines === undefined) {
                lines = 3;
            }
            this.buffer.push(this.driver.cut(mode, lines));
            return this;
        };

        /**
            * Print and feed line / Print and feed n lines.
            *
            */
        this.feed = this.lf = function (lines) {
            this.buffer.push(this.driver.lf(lines));
            return this;
        };

        /**
            * Some printers require a form feed to release the paper. On most printers, this
            * command is only useful in page mode, which is not implemented in this driver.
            */
        this.feedForm = function () {
            this.buffer.push(this.driver.feedForm());
            return this;
        };


        /**
            * Some slip printers require `ESC q` sequence to release the paper.
            */
        this.release = function () {
            this.buffer.push(this.driver.relese());
            return this;
        };

        /**
            * Print and reverse feed n lines.
            */
        this.feedReverse = function (lines) {
            if (lines === undefined) {
                lines = 1;
            }
            this.buffer.push(this.driver.feedReverse(lines));
            return this;
        };

        // ---------------------------------
        //  SHORT SYNTAX
        // ---------------------------------

        // alignment

        this.left = function () {
            this.buffer.push(this.driver.alignment(this.ALIGNMENT_LEFT));
            return this;
        };

        this.right = function () {
            this.buffer.push(this.driver.alignment(this.ALIGNMENT_RIGHT));
            return this;
        };

        this.center = function () {
            this.buffer.push(this.driver.alignment(this.ALIGNMENT_CENTER));
            return this;
        };


        return this;
    }
    // ==================================================
    // MAIN
    // ===================================================
    function getCurrentTransport() {
        return new RawBtTransport();
    }
    function getCurrentDriver() {
        return new EscPosDriver();
    }
    const countryNames = {
        // 'hy': 'РђСЂРјС�?РЅСЃРєРёР№',
        'sq': 'Albanian',
        //        'ar': 'Arabic',
        //        'bn': 'Bengali',
        'bg': 'Bulgarian',
        'ca': 'Catalan',
        'zh': 'Chinese',
        'hr': 'Croatian',
        'cs': 'Czech',
        'da': 'Danish',
        'nl': 'Dutch',
        'en': 'English',
        'et': 'Estonian',
        'fi': 'Finnish (Suomi)',
        'fr': 'French',
        'de': 'Deutsch',
        'el': 'Greek',
        //        'hi': 'Hindi',
        'hu': 'Hungarian',
        'it': 'Italian',
        'id': 'Indonesian',
        'lt': 'Lithuanian',
        'ms': 'Malay',
        'no': 'Norwegian',
        'pl': 'Polish',
        'pt': 'Portuguese',
        'ru': 'Russian',
        'sk': 'Slovak',
        'sl': 'Slovenian',
        'es': 'Spanish',
        'sv': 'Swedish',
        'th': 'Thai',
        'tr': 'Turkish',
        'uk': 'Ukrainian'
    };
    const defaultCP = {
        // 'hy': 'ArmSCII8',
        'zh': 'GBK',
        'sq': 'cp858',
        'bg': 'cp866',
        'ca': 'cp437',
        'hr': 'cp1252',
        'cs': 'cp1252',
        'da': 'cp858',
        'nl': 'cp858',
        'et': 'cp858',
        'fi': 'cp858',
        'fr': 'cp858',
        'de': 'cp858',
        'el': 'windows1253',
        'hu': 'cp852',
        'id': 'cp437',
        'it': 'cp858',
        'lt': 'cp852',
        'ms': 'cp437',
        'no': 'cp858',
        'pl': 'cp852',
        'pt': 'cp858',
        'ru': 'cp866',
        'sk': 'cp852',
        'sl': 'cp852',
        'es': 'cp858',
        'sv': 'cp858',
        'th': 'cp874',
        'tr': 'cp857',
        'uk': 'cp866',
        'en': 'cp437'
    };
    // ------------------------------------------------------
    // DEMO
    // ------------------------------------------------------
    function alignDemo() {
        var c = new PosPrinterJob(getCurrentDriver(), getCurrentTransport());
        c.initialize();

        c.printText("Text align:", c.ALIGNMENT_LEFT, c.FONT_SIZE_BIG);
        c.printText("Left aligned", c.ALIGNMENT_LEFT);
        c.printText("Center aligned", c.ALIGNMENT_CENTER);
        c.printText("Right aligned", c.ALIGNMENT_RIGHT);
        c.center().print('center()').lf().right().print('right()').lf().left().print('left()').lf();

        c.feed(2);
        c.execute();
    }
    function decorDemo() {
        var c = new PosPrinterJob(getCurrentDriver(), getCurrentTransport());
        c.initialize();

        c.printText("Font decoration:", c.ALIGNMENT_LEFT, c.FONT_SIZE_BIG);
        c.printText("underline text as ESC !", c.ALIGNMENT_LEFT, c.FONT_UNDERLINE);
        c.underline(c.UNDERLINE_SINGLE).print(' one dot underline ').underline(c.UNDERLINE_DOUBLE).lf().print(' double dots underline ').underline(c.UNDERLINE_NONE).lf();
        c.printText("emphasized text as ESC !", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED);
        c.emphasis(true).print('Emphasis TEXT').emphasis(false).printLine(' and bold off text');
        c.feed(2);
        c.execute();
    }
    function fontDemo() {
        var c = new PosPrinterJob(getCurrentDriver(), getCurrentTransport());
        c.initialize();

        c.printText("Font sizes:", c.ALIGNMENT_LEFT, c.FONT_SIZE_BIG);
        c.printText("small FONT", c.ALIGNMENT_LEFT, c.FONT_SIZE_SMALL);
        c.printText("medium 1 FONT", c.ALIGNMENT_LEFT, c.FONT_SIZE_MEDIUM1);
        c.printText("medium 2 FONT", c.ALIGNMENT_LEFT, c.FONT_SIZE_MEDIUM2);
        c.printText("medium 3 FONT", c.ALIGNMENT_LEFT, c.FONT_SIZE_MEDIUM3);
        c.printText("big FONT", c.ALIGNMENT_LEFT, c.FONT_SIZE_BIG);
        c.printText("double WIDTH", c.ALIGNMENT_LEFT, c.FONT_DOUBLE_WIDTH);
        c.printText("double HEIGHT", c.ALIGNMENT_LEFT, c.FONT_DOUBLE_HEIGHT);
        c.setPrintMode(c.FONT_SIZE_BIG + c.FONT_UNDERLINE).printLine("Example");
        c.feed(3);

        c.execute();
    }
    function encodingDemo() {
        var c = new PosPrinterJob(getCurrentDriver(), getCurrentTransport());
        c.initialize();

        c.setEncoding(defaultCP['en']);
        c.printText("Chines", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['zh']).printLine("жЃ­е–њж‚�?!\nж‚�?е·Із»�?ж€ђеЉџзљ„иїћжЋ�?дёЉдє†ж€‘д»¬зљ„дѕїжђєеј�?и“ќз‰™ж‰“еЌ°жњєпјЃ", "GBK");

        c.setEncoding(defaultCP['en']);
        c.printText("Indonesian", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['id']).printLine("Kamu gimana kabarnya? Terima kasih", "cp437");

        c.setEncoding(defaultCP['en']);
        c.printText("Portugal", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['pt']).printLine("LuГ­s argГјia Г  JГєlia que В«braГ§Гµes, fГ©, chГЎ, Гіxido, pГґr, zГўngГЈoВ» eram palavras do portuguГЄs", "cp860");

        c.setEncoding(defaultCP['en']);
        c.printText("Spanish", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['es']).printLine("El pingГјino Wenceslao hizo kilГіmetros bajo exhaustiva lluvia y frГ­o, aГ±oraba a su querido cachorro.", "cp437");


        c.setEncoding(defaultCP['en']);
        c.printText("Danish", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['da']).printLine("Quizdeltagerne spiste jordbГ¦r med flГёde, mens cirkusklovnen Wolther spillede pГ�? xylofon.", "cp850");

        c.setEncoding(defaultCP['en']);
        c.printText("German", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['de']).printLine("Falsches Гњben von Xylophonmusik quГ¤lt jeden grГ¶Гџeren Zwerg.", "cp850");

        c.setEncoding(defaultCP['en']);
        c.printText("Greek", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['el']).printLine("ОћОµПѓОєОµПЂО¬О¶П‰ П„О·ОЅ П€П…П‡ОїП†ОёПЊПЃО± ОІОґОµО»П…ОіОјОЇО±", "cp737");


        c.setEncoding(defaultCP['en']);
        c.printText("French", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['fr']).printLine("Le cЕ“ur dГ©Г§u mais l'Гўme plutГґt naГЇve, LouГїs rГЄva de crapaГјter en canoГ« au delГ  des Г®les, prГ�?s du mГ¤lstrГ¶m oГ№ brГ»lent les novГ¦.", "cp1252");

        //        c.printText("Irish Gaelic", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        //        c.setCharacterTable(16).printLine("D'fhuascail ГЌosa, Гљrmhac na hГ“ighe Beannaithe, pГіr Г‰ava agus ГЃdhaimh.", "cp1252");

        c.setEncoding(defaultCP['en']);
        c.printText("Hungarian", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['hu']).printLine("ГЃrvГ­ztЕ±rЕ‘ tГјkГ¶rfГєrГіgГ©p.", "cp852");

        //        c.printText("Icelandic", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        //        c.setCharacterTable(2).printLine("KГ¦mi nГЅ Г¶xi hГ©r ykist ГѕjГіfum nГє bГ¦Г°i vГ­l og ГЎdrepa.", "cp850");

        c.setEncoding(defaultCP['en']);
        c.printText("Latvian", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['lt']).printLine("GlДЃЕѕЕЎД·Е«Е†a rЕ«Д·Д«ЕЎi dzД“rumДЃ ДЌiepj Baha koncertflД«ДЈeДјu vДЃkus.", "cp1257");

        c.setEncoding(defaultCP['en']);
        c.printText("Polish", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['pl']).printLine("PchnД…Д‡ w tД™ Е‚ГіdЕє jeЕјa lub oЕ›m skrzyЕ„ fig.", "cp1257");

        c.setEncoding(defaultCP['en']);
        c.printText("Russian", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['ru']).printLine("Р’ С‡Р°С‰Р°С… СЋРіР° Р¶РёР» Р±С‹ С†РёС‚СЂСѓСЃ? Р�?Р°, РЅРѕ С„Р°Р»СЊС€РёРІС‹Р№ СЌРєР·РµРјРїР»С�?СЂ!", "cp866");

        c.setEncoding(defaultCP['en']);
        c.printText("Turkish", c.ALIGNMENT_LEFT, c.FONT_EMPHASIZED).bold(false);
        c.setEncoding(defaultCP['tr']).printLine("PijamalД± hasta, yaДџД±z ЕџofГ¶re Г§abucak gГјvendi.", "cp857");

        c.feed(3);
        c.execute();
    }
    function pangrams(lang) {
        switch (lang) {
            // case 'hy': return 'ФїЦЂХ¶ХЎХґ ХЎХєХЎХЇХ« ХёЦ‚ХїХ�?Х¬ Ц‡ Х«Х¶Х®Х« ХЎХ¶Х°ХЎХ¶ХЈХ«ХЅХї Х№Х�?Х¶Х�?ЦЂЦ‰';
            case 'sq':
                return 'UnГ« mund tГ« ha qelq dhe nuk mГ« gjen gjГ«.';
            case 'bg':
                return 'РњРѕРіР° РґР° С�?Рј СЃС‚СЉРєР»Рѕ, С‚Рѕ РЅРµ РјРё РІСЂРµРґРё.';
            case 'ca':
                return 'Puc menjar vidre, que no em fa mal.';
            case 'hr':
                return 'Ja mogu jesti staklo, i to mi ne ЕЎteti.';
            case 'cs':
                return 'Mohu jГ­st sklo, neublГ­ЕѕГ­ mi.';
            case 'da':
                return 'Jeg kan spise glas, det gГёr ikke ondt pГ�? mig.';
            case 'nl':
                return 'Ik kan glas eten, het doet mДі geen kwaad.';
            case 'et':
                return 'Ma vГµin klaasi sГјГјa, see ei tee mulle midagi.';
            case 'ph':
                return '-unknown-';
            case 'fi':
                return 'Voin syГ¶dГ¤ lasia, se ei vahingoita minua.';
            case 'fr':
                return 'Je peux manger du verre, Г§a ne me fait pas mal.';
            case 'ka':
                return 'бѓ›бѓбѓњбѓђбѓЎ бѓ•бѓ­бѓђбѓ› бѓ“бѓђ бѓђбѓ бѓђ бѓ›бѓўбѓ™бѓбѓ•бѓђ.';
            case 'de':
                return 'Ich kann Glas essen, ohne mir zu schaden.';
            case 'el':
                return 'ОњПЂОїПЃПЋ ОЅО± П†О¬П‰ ПѓПЂО±ПѓОјО­ОЅО± ОіП…О±О»О№О¬ П‡П‰ПЃОЇП‚ ОЅО± ПЂО¬ОёП‰ П„ОЇПЂОїП„О±.';
            case 'hi':
                return 'а¤®а�?€а¤‚ а¤•а¤ѕа¤Ѓа¤љ а¤–а¤ѕ а¤ёа¤•а¤¤а¤ѕ а¤№а�?‚а¤Ѓ, а¤®а�?Ѓа¤ќа�?‡ а¤‰а¤ё а¤ёа�?‡ а¤•а�?‹а¤€ а¤Єа�?Ђа¤Ўа¤ѕ а¤�?а¤№а�?Ђа¤‚ а¤№а�?‹а¤¤а�?Ђ.';
            case 'hu':
                return 'ГЃrvГ­ztЕ±rЕ‘ tГјkГ¶rfГєrГіgГ©p.';
            case 'id':
                return 'Cwm fjordbank glyphs vext quiz.';
            case 'it':
                return 'Posso mangiare il vetro e non mi fa male.';
            case 'lv':
                return 'Es varu Д“st stiklu, tas man nekaitД“.';
            case 'lt':
                return 'AЕЎ galiu valgyti stiklД… ir jis manД™s neЕѕeidЕѕia';
            case 'mk':
                return 'РњРѕР¶Р°Рј РґР° СР°РґР°Рј СЃС‚Р°РєР»Рѕ, Р° РЅРµ РјРµ С€С‚РµС‚Р°.';
            case 'ms':
                return 'Saya boleh makan kaca dan ia tidak mencederakan saya.';
            case 'no':
                return 'Eg kan eta glas utan Г�? skada meg. Jeg kan spise glass uten Г�? skade meg.';
            case 'pl':
                return 'PchnД…Д‡ w tД™ Е‚ГіdЕє jeЕјa lub oЕ›m skrzyЕ„ fig.';
            case 'pt':
                return 'O prГіximo vГґo Г  noite sobre o AtlГўntico, pГµe freqГјentemente o Гєnico mГ©dico.';
            case 'ro':
                return 'Pot sДѓ mДѓnГўnc sticlДѓ И™i ea nu mДѓ rДѓneИ™te.';
            case 'ru':
                return 'Р’ С‡Р°С‰Р°С… СЋРіР° Р¶РёР» Р±С‹ С†РёС‚СЂСѓСЃ? Р�?Р°, РЅРѕ С„Р°Р»СЊС€РёРІС‹Р№ СЌРєР·РµРјРїР»С�?СЂ!';
            case 'sr':
                return 'Р€Р° РјРѕРіСѓ СРµСЃС‚Рё СЃС‚Р°РєР»Рѕ, Рё С‚Рѕ РјРё РЅРµ С€С‚РµС‚Рё. Ja mogu jesti staklo, i to mi ne ЕЎteti.';
            case 'sk':
                return 'StarГЅ kГґЕ€ na hЕ•be knГ­h Еѕuje tГ­ЕЎko povГ¤dnutГ© ruЕѕe, na stДєpe sa Д�?ateДѕ uДЌГ­ kvГЎkaЕ�? novГє Гіdu o Еѕivote.';
            case 'sl':
                return 'Lahko jem steklo, ne da bi mi ЕЎkodovalo.';
            case 'es':
                return 'Puedo comer vidrio, no me hace daГ±o.';
            case 'sv':
                return 'Jag kan Г¤ta glas utan att skada mig.';
            case 'th':
                return 'аё‰аё±аё™аёЃаёґаё™аёЃаёЈаё°аё€аёЃа№„аё�?а№‰ а№Ѓаё•а№€аёЎаё±аё™а№„аёЎа№€аё—аёіа№ѓаё«а№‰аё‰аё±аё™а№Ђаё€а№‡аёљ';
            case 'tr':
                return 'PijamalД± hasta, yaДџД±z ЕџofГ¶re Г§abucak gГјvendi.';
            case 'uk':
                return 'Р§СѓС�?С€ С—С…, РґРѕС†СЋ, РіР°? РљСѓРјРµРґРЅР° Р¶ С‚Рё, РїСЂРѕС‰Р°Р№СЃС�? Р±РµР· Т‘РѕР»СЊС„С–РІ!';
            case 'vi':
                return 'TГґi cГі thб»ѓ Дѓn thб»§y tinh mГ  khГґng hбєЎi gГ¬.';

            case 'en':
            default:
                return 'A quick brown fox jumps over the lazy dog';

        }
    }
    function loremIpsum(lang) {
        switch (lang) {
            // case 'hy':  return 'Lorem Ipsum-Х�? ХїХєХЎХЈЦЂХёЦ‚Х©ХµХЎХ¶ Ц‡ ХїХєХЎХЈЦЂХЎХЇХЎХ¶ ХЎЦЂХ¤ХµХёЦ‚Х¶ХЎХўХ�?ЦЂХёЦ‚Х©ХµХЎХ¶ Х°ХЎХґХЎЦЂ Х¶ХЎХ­ХЎХїХ�?ХЅХѕХЎХ® ХґХёХ¤Х�?Х¬ХЎХµХ«Х¶ ХїХ�?Ц„ХЅХї Х§: ХЌХЇХЅХЎХ® 1500-ХЎХЇХЎХ¶Х¶Х�?ЦЂХ«ЦЃ` Lorem Ipsum-Х�? Х°ХЎХ¶Х¤Х«ХЅХЎЦЃХ�?Х¬ Х§ ХїХєХЎХЈЦЂХЎХЇХЎХ¶ ХЎЦЂХ¤ХµХёЦ‚Х¶ХЎХўХ�?ЦЂХёЦ‚Х©ХµХЎХ¶ ХЅХїХЎХ¶Х¤ХЎЦЂХї ХґХёХ¤Х�?Х¬ХЎХµХ«Х¶ ХїХ�?Ц„ХЅХї, Х«Х¶Х№Х�? ХґХ« ХЎХ¶Х°ХЎХµХї ХїХєХЎХЈЦЂХ«Х№Х« ХЇХёХІХґХ«ЦЃ ХїХЎЦЂХўХ�?ЦЂ ХїХЎХјХЎХїХ�?ХЅХЎХЇХ¶Х�?ЦЂХ« Ц…ЦЂХ«Х¶ХЎХЇХ¶Х�?ЦЂХ« ХЈХ«ЦЂЦ„ ХЅХїХ�?ХІХ®Х�?Х¬ХёЦ‚ Х»ХЎХ¶Ц„Х�?ЦЂХ« ХЎЦЂХ¤ХµХёЦ‚Х¶Ц„ Х§: Ф±ХµХЅ ХїХ�?Ц„ХЅХїХ�? ХёХ№ ХґХ«ХЎХµХ¶ ХЇХЎЦЂХёХІХЎЦЃХ�?Х¬ Х§ ХЈХёХµХЎХїЦ‡Х�?Х¬ Х°Х«Х¶ХЈ Х¤ХЎЦЂХЎХ·ЦЂХ»ХЎХ¶, ХЎХµХ¬Ц‡ Х¶Х�?ЦЂХЎХјХѕХ�?Х¬ Х§ Х§Х¬Х�?ХЇХїЦЂХёХ¶ХЎХµХ«Х¶ ХїХєХЎХЈЦЂХёЦ‚Х©ХµХЎХ¶ ХґХ�?Х»` ХґХ¶ХЎХ¬ХёХѕ Х§ХЎХєХ�?ХЅ ХЎХ¶ЦѓХёЦѓХёХ­: Ф±ХµХ¶ Х°ХЎХµХїХ¶Х« Х§ Х¤ХЎЦЂХ±Х�?Х¬ 1960-ХЎХЇХЎХ¶Х¶Х�?ЦЂХ«Х¶ Lorem Ipsum ХўХёХѕХЎХ¶Х¤ХЎХЇХёХІ Letraset Х§Х»Х�?ЦЂХ« Х©ХёХІХЎЦЂХЇХґХЎХ¶ ХЎЦЂХ¤ХµХёЦ‚Х¶Ц„ХёЦ‚Хґ, Х«ХЅХЇ ХЎХѕХ�?Х¬Х« ХёЦ‚Х· Х°ХЎХґХЎХЇХЎЦЂХЈХ№ХЎХµХ«Х¶ ХїХєХЎХЈЦЂХёЦ‚Х©ХµХЎХ¶ ХЎХµХ¶ХєХ«ХЅХ« Х®ЦЂХЎХЈЦЂХ�?ЦЂХ« Х©ХёХІХЎЦЂХЇХґХЎХ¶ Х°Х�?ХїЦ‡ХЎХ¶Ц„ХёХѕ, Х«Х¶Х№ХєХ«ХЅХ«Х¶ Х§ Aldus PageMaker-Х�?, ХёЦЂХ�? Х¶Х�?ЦЂХЎХјХёЦ‚Хґ Х§ Lorem Ipsum-Х« ХїХЎЦЂХЎХїХ�?ХЅХЎХЇХ¶Х�?ЦЂ:';
            case 'sq':
                return 'Lorem Ipsum Г«shtГ« njГ« tekst shabllon i industrisГ« sГ« printimit dhe shtypshkronjave. Lorem Ipsum ka qenГ« teksti shabllon i industrisГ« qГ« nga vitet 1500, kur njГ« shtypГ«s i panjohur morri njГ« galeri shkrimesh dhe i ngatГ«rroi pГ«r tГ« krijuar njГ« libГ«r mostГ«r. Teksti i ka mbijetuar jo vetГ«m pesГ« shekujve, por edhe kalimit nГ« shtypshkrimin elektronik, duke ndenjur nГ« thelb i pandryshuar. U bГ« i njohur nГ« vitet 1960 me lГ«shimin e letrave \'Letraset\' qГ« pГ«rmbanin tekstin Lorem Ipsum, e nГ« kohГ«t e fundit me aplikacione publikimi si Aldus PageMaker qГ« pГ«rmbajnГ« versione tГ« Lorem Ipsum.';
            case 'bg':
                return 'Lorem Ipsum Рµ РµР»РµРјРµРЅС‚Р°СЂРµРЅ РїСЂРёРјРµСЂРµРЅ С‚РµРєСЃС‚, РёР·РїРѕР»Р·РІР°РЅ РІ РїРµС‡Р°С‚Р°СЂСЃРєР°С‚Р° Рё С‚РёРїРѕРіСЂР°С„СЃРєР°С‚Р° РёРЅРґСѓСЃС‚СЂРёС�?. Lorem Ipsum Рµ РёРЅРґСѓСЃС‚СЂРёР°Р»РµРЅ СЃС‚Р°РЅРґР°СЂС‚ РѕС‚ РѕРєРѕР»Рѕ 1500 РіРѕРґРёРЅР°, РєРѕРіР°С‚Рѕ РЅРµРёР·РІРµСЃС‚РµРЅ РїРµС‡Р°С‚Р°СЂ РІР·РµРјР° РЅС�?РєРѕР»РєРѕ РїРµС‡Р°С‚Р°СЂСЃРєРё Р±СѓРєРІРё Рё РіРё СЂР°Р·Р±СЉСЂРєРІР°, Р·Р° РґР° РЅР°РїРµС‡Р°С‚Р° СЃ С‚С�?С… РєРЅРёРіР° СЃ РїСЂРёРјРµСЂРЅРё С€СЂРёС„С‚РѕРІРµ. РўРѕР·Рё РЅР°С‡РёРЅ РЅРµ СЃР°РјРѕ Рµ РѕС†РµР»С�?Р» РїРѕРІРµС‡Рµ РѕС‚ 5 РІРµРєР°, РЅРѕ Рµ РЅР°РІР»С�?Р·СЉР» Рё РІ РїСѓР±Р»РёРєСѓРІР°РЅРµС‚Рѕ РЅР° РµР»РµРєС‚СЂРѕРЅРЅРё РёР·РґР°РЅРёС�? РєР°С‚Рѕ Рµ Р·Р°РїР°Р·РµРЅ РїРѕС‡С‚Рё Р±РµР· РїСЂРѕРјС�?РЅР°. РџРѕРїСѓР»С�?СЂРёР·РёСЂР°РЅ Рµ РїСЂРµР· 60С‚Рµ РіРѕРґРёРЅРё РЅР° 20С‚Рё РІРµРє СЃСЉСЃ РёР·РґР°РІР°РЅРµС‚Рѕ РЅР° Letraset Р»РёСЃС‚Рё, СЃСЉРґСЉСЂР¶Р°С‰Рё Lorem Ipsum РїР°СЃР°Р¶Рё, РїРѕРїСѓР»С�?СЂРµРЅ Рµ Рё РІ РЅР°С€Рё РґРЅРё РІСЉРІ СЃРѕС„С‚СѓРµСЂ Р·Р° РїРµС‡Р°С‚РЅРё РёР·РґР°РЅРёС�? РєР°С‚Рѕ Aldus PageMaker, РєРѕР№С‚Рѕ РІРєР»СЋС‡РІР° СЂР°Р·Р»РёС‡РЅРё РІРµСЂСЃРёРё РЅР° Lorem Ipsum.';
            case 'ca':
                return 'Lorem Ipsum Г©s un text de farciment usat per la indГєstria de la tipografia i la impremta. Lorem Ipsum ha estat el text estГ ndard de la indГєstria des de l\'any 1500, quan un impressor desconegut va fer servir una galerada de text i la va mesclar per crear un llibre de mostres tipogrГ fiques. No nomГ©s ha sobreviscut cinc segles, sinГі que ha fet el salt cap a la creaciГі de tipus de lletra electrГІnics, romanent essencialment sense canvis. Es va popularitzar l\'any 1960 amb el llanГ§ament de fulls Letraset que contenien passatges de Lorem Ipsum, i mГ©s recentment amb programari d\'autoediciГі com Aldus Pagemaker que inclou versions de Lorem Ipsum.';
            case 'hr':
                return 'Lorem Ipsum je jednostavno probni tekst koji se koristi u tiskarskoj i slovoslagarskoj industriji. Lorem Ipsum postoji kao industrijski standard joЕЎ od 16-og stoljeД‡a, kada je nepoznati tiskar uzeo tiskarsku galiju slova i posloЕѕio ih da bi napravio knjigu s uzorkom tiska. Taj je tekst ne samo preЕѕivio pet stoljeД‡a, veД‡ se i vinuo u svijet elektronskog slovoslagarstva, ostajuД‡i u suЕЎtini nepromijenjen. Postao je popularan tijekom 1960-ih s pojavom Letraset listova s odlomcima Lorem Ipsum-a, a u skorije vrijeme sa software-om za stolno izdavaЕЎtvo kao ЕЎto je Aldus PageMaker koji takoД‘er sadrЕѕi varijante Lorem Ipsum-a.';
            case 'cs':
                return 'Lorem Ipsum je demonstrativnГ­ vГЅplЕ€ovГЅ text pouЕѕГ­vanГЅ v tiskaЕ™skГ©m a knihaЕ™skГ©m prЕЇmyslu. Lorem Ipsum je povaЕѕovГЎno za standard v tГ©to oblasti uЕѕ od zaДЌГЎtku 16. stoletГ­, kdy dnes neznГЎmГЅ tiskaЕ™ vzal kusy textu a na jejich zГЎkladД› vytvoЕ™il speciГЎlnГ­ vzorovou knihu. Jeho odkaz nevydrЕѕel pouze pД›t stoletГ­, on pЕ™eЕѕil i nГЎstup elektronickГ© sazby v podstatД› beze zmД›ny. NejvГ­ce popularizovГЎno bylo Lorem Ipsum v ЕЎedesГЎtГЅch letech 20. stoletГ­, kdy byly vydГЎvГЎny speciГЎlnГ­ vzornГ­ky s jeho pasГЎЕѕemi a pozdД›ji pak dГ­ky poДЌГ­taДЌovГЅm DTP programЕЇm jako Aldus PageMaker.';
            case 'da':
                return 'Lorem Ipsum er ganske enkelt fyldtekst fra print- og typografiindustrien. Lorem Ipsum har vГ¦ret standard fyldtekst siden 1500-tallet, hvor en ukendt trykker sammensatte en tilfГ¦ldig spalte for at trykke en bog til sammenligning af forskellige skrifttyper. Lorem Ipsum har ikke alene overlevet fem Г�?rhundreder, men har ogsГ�? vundet indpas i elektronisk typografi uden vГ¦sentlige Г¦ndringer. SГ¦tningen blev gjordt kendt i 1960\'erne med lanceringen af Letraset-ark, som indeholdt afsnit med Lorem Ipsum, og senere med layoutprogrammer som Aldus PageMaker, som ogsГ�? indeholdt en udgave af Lorem Ipsum.';
            case 'nl':
                return 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren \'60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
            case 'et':
                return 'Lorem Ipsum on lihtsalt proovitekst, mida kasutatakse printimis- ja ladumistГ¶Г¶stuses. See on olnud tГ¶Г¶stuse pГµhiline proovitekst juba alates 1500. aastatest, mil tundmatu printija vГµttis hulga suvalist teksti, et teha trГјkinГ¤idist. Lorem Ipsum ei ole ainult viis sajandit sГ¤ilinud, vaid on ka edasi kandunud elektroonilisse trГјkiladumisse, jГ¤Г¤des sealjuures peaaegu muutumatuks. See sai tuntuks 1960. aastatel Letraset\'i lehtede vГ¤ljalaskmisega, ja hiljuti tekstiredaktoritega nagu Aldus PageMaker, mis sisaldavad erinevaid Lorem Ipsumi versioone.';
            case 'ph':
                return 'Ang Lorem Ipsum ay ginagamit na modelo ng industriya ng pagpriprint at pagtytypeset. Ang Lorem Ipsum ang naging regular na modelo simula pa noong 1500s, noong may isang di kilalang manlilimbag and kumuha ng galley ng type at ginulo ang pagkaka-ayos nito upang makagawa ng libro ng mga type specimen. Nalagpasan nito hindi lang limang siglo, kundi nalagpasan din nito ang paglaganap ng electronic typesetting at nanatiling parehas. Sumikat ito noong 1960s kasabay ng pag labas ng Letraset sheets na mayroong mga talata ng Lorem Ipsum, at kamakailan lang sa mga desktop publishing software tulad ng Aldus Pagemaker ginamit ang mga bersyon ng Lorem Ipsum.';
            case 'fi':
                return 'Lorem Ipsum on yksinkertaisesti testausteksti, jota tulostus- ja ladontateollisuudet kГ¤yttГ¤vГ¤t. Lorem Ipsum on ollut teollisuuden normaali testausteksti jo 1500-luvulta asti, jolloin tuntematon tulostaja otti kaljuunan ja sekoitti sen tehdГ¤kseen esimerkkikirjan. Se ei ole selvinnyt vain viittГ¤ vuosisataa, mutta myГ¶s loikan elektroniseen kirjoitukseen, jГ¤Г¤den suurinpiirtein muuntamattomana. Se tuli kuuluisuuteen 1960-luvulla kun Letraset-paperiarkit, joissa oli Lorem Ipsum pГ¤tkiГ¤, julkaistiin ja vielГ¤ myГ¶hemmin tietokoneen julkistusohjelmissa, kuten Aldus PageMaker joissa oli versioita Lorem Ipsumista.';
            case 'fr':
                return 'Le Lorem Ipsum est simplement du faux texte employГ© dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l\'imprimerie depuis les annГ©es 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour rГ©aliser un livre spГ©cimen de polices de texte. Il n\'a pas fait que survivre cinq siГ�?cles, mais s\'est aussi adaptГ© Г  la bureautique informatique, sans que son contenu n\'en soit modifiГ©. Il a Г©tГ© popularisГ© dans les annГ©es 1960 grГўce Г  la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus rГ©cemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.';
            case 'ka':
                return 'Lorem Ipsum бѓЎбѓђбѓ‘бѓ�?бѓ­бѓ“бѓ бѓ“бѓђ бѓўбѓбѓћбѓќбѓ’бѓ бѓђбѓ¤бѓбѓЈбѓљбѓ бѓбѓњбѓ“бѓЈбѓЎбѓўбѓ бѓбѓбѓЎ бѓЈбѓ�?бѓбѓњбѓђбѓђбѓ бѓЎбѓќ бѓўбѓ�?бѓ�?бѓЎбѓўбѓбѓђ. бѓбѓ’бѓ бѓЎбѓўбѓђбѓњбѓ“бѓђбѓ бѓўбѓђбѓ“ 1500-бѓбѓђбѓњбѓ бѓ¬бѓљбѓ�?бѓ‘бѓбѓ“бѓђбѓњ бѓбѓ�?бѓЄбѓђ, бѓ бѓќбѓ“бѓ�?бѓЎбѓђбѓЄ бѓЈбѓЄбѓњбѓќбѓ‘бѓ›бѓђ бѓ›бѓ‘бѓ�?бѓ­бѓ“бѓђбѓ•бѓ›бѓђ бѓђбѓ›бѓ¬бѓ§бѓќбѓ‘ бѓ“бѓђбѓ–бѓ’бѓђбѓ–бѓ�? бѓ¬бѓбѓ’бѓњбѓбѓЎ бѓЎбѓђбѓЄбѓ“бѓ�?бѓљбѓ бѓ�?бѓ’бѓ–бѓ�?бѓ›бѓћбѓљбѓђбѓ бѓ бѓ“бѓђбѓ‘бѓ�?бѓ­бѓ“бѓђ. бѓ›бѓбѓЎбѓ бѓўбѓ�?бѓ�?бѓЎбѓўбѓ бѓђбѓ бѓђбѓ›бѓђбѓ бѓўбѓќ 5 бѓЎбѓђбѓЈбѓ™бѓЈбѓњбѓбѓЎ бѓ›бѓђбѓњбѓ«бѓбѓљбѓ–бѓ�? бѓ�?бѓ�?бѓ›бѓќбѓ бѓ©бѓђ, бѓђбѓ бѓђбѓ›бѓ�?бѓ“ бѓ›бѓђбѓњ бѓ“бѓ¦бѓ�?бѓ›бѓ“бѓ�?, бѓ�?бѓљбѓ�?бѓ�?бѓўбѓ бѓќбѓњбѓЈбѓљбѓ бѓўбѓбѓћбѓќбѓ’бѓ бѓђбѓ¤бѓбѓбѓЎ бѓ“бѓ бѓќбѓ›бѓ“бѓ�?бѓЄ бѓЈбѓЄбѓ•бѓљбѓ�?бѓљбѓђбѓ“ бѓ›бѓќбѓђбѓ¦бѓ¬бѓбѓђ. бѓ’бѓђбѓњбѓЎбѓђбѓ™бѓЈбѓ—бѓ бѓ�?бѓ‘бѓЈбѓљбѓ бѓћбѓќбѓћбѓЈбѓљбѓђбѓ бѓќбѓ‘бѓђ бѓ›бѓђбѓЎ 1960-бѓбѓђбѓњ бѓ¬бѓљбѓ�?бѓ‘бѓ�?бѓ бѓ’бѓђбѓ›бѓќбѓЎбѓЈбѓљбѓ›бѓђ Letraset-бѓбѓЎ бѓЄбѓњбѓќбѓ‘бѓбѓљбѓ›бѓђ бѓўбѓ бѓђбѓ¤бѓђбѓ бѓ�?бѓўбѓ�?бѓ‘бѓ›бѓђ бѓ›бѓќбѓЈбѓўбѓђбѓњбѓђ, бѓЈбѓ¤бѓ бѓќ бѓ›бѓќбѓ’бѓ•бѓбѓђбѓњбѓ�?бѓ‘бѓбѓ— бѓ™бѓ вЂ�? Aldus PageMaker-бѓбѓЎ бѓўбѓбѓћбѓбѓЎ бѓЎбѓђбѓ’бѓђбѓ›бѓќбѓ›бѓЄбѓ�?бѓ›бѓљбѓќ бѓћбѓ бѓќбѓ’бѓ бѓђбѓ›бѓ�?бѓ‘бѓ›бѓђ, бѓ бѓќбѓ›бѓљбѓ�?бѓ‘бѓ�?бѓбѓЄ Lorem Ipsum-бѓбѓЎ бѓЎбѓ®бѓ•бѓђбѓ“бѓђбѓЎбѓ®бѓ•бѓђ бѓ•бѓ�?бѓ бѓЎбѓбѓ�?бѓ‘бѓ бѓбѓ§бѓќ бѓ©бѓђбѓ�?бѓ�?бѓњбѓ�?бѓ‘бѓЈбѓљбѓ.';
            case 'de':
                return 'Lorem Ipsum ist ein einfacher Demo-Text fГјr die Print- und Schriftindustrie. Lorem Ipsum ist in der Industrie bereits der Standard Demo-Text seit 1500, als ein unbekannter Schriftsteller eine Hand voll WГ¶rter nahm und diese durcheinander warf um ein Musterbuch zu erstellen. Es hat nicht nur 5 Jahrhunderte Гјberlebt, sondern auch in Spruch in die elektronische Schriftbearbeitung geschafft (bemerke, nahezu unverГ¤ndert). Bekannt wurde es 1960, mit dem erscheinen von "Letraset", welches Passagen von Lorem Ipsum enhielt, so wie Desktop Software wie "Aldus PageMaker" - ebenfalls mit Lorem Ipsum.';
            case 'el':
                return 'О¤Ої Lorem Ipsum ОµОЇОЅО±О№ О±ПЂО»О¬ О­ОЅО± ОєОµОЇОјОµОЅОї П‡П‰ПЃОЇП‚ ОЅПЊО·ОјО± ОіО№О± П„ОїП…П‚ ОµПЂО±ОіОіОµО»ОјО±П„ОЇОµП‚ П„О·П‚ П„П…ПЂОїОіПЃО±П†ОЇО±П‚ ОєО±О№ ПѓП„ОїО№П‡ОµО№ОїОёОµПѓОЇО±П‚. О¤Ої Lorem Ipsum ОµОЇОЅО±О№ П„Ої ОµПЂО±ОіОіОµО»ОјО±П„О№ОєПЊ ПЂПЃПЊП„П…ПЂОї ПЊПѓОїОЅ О±П†ОїПЃО¬ П„Ої ОєОµОЇОјОµОЅОї П‡П‰ПЃОЇП‚ ОЅПЊО·ОјО±, О±ПЂПЊ П„ОїОЅ 15Ої О±О№ПЋОЅО±, ПЊП„О±ОЅ О­ОЅО±П‚ О±ОЅПЋОЅП…ОјОїП‚ П„П…ПЂОїОіПЃО¬П†ОїП‚ ПЂО®ПЃОµ О­ОЅО± ОґОїОєОЇОјО№Ої ОєО±О№ О±ОЅО±ОєО¬П„ОµП€Оµ П„О№П‚ О»О­ОѕОµО№П‚ ОіО№О± ОЅО± ОґО·ОјО№ОїП…ПЃОіО®ПѓОµО№ О­ОЅО± ОґОµОЇОіОјО± ОІО№ОІО»ОЇОїП…. ОЊП‡О№ ОјПЊОЅОї ОµПЂО№ОІОЇП‰ПѓОµ ПЂО­ОЅП„Оµ О±О№ПЋОЅОµП‚, О±О»О»О¬ ОєП…ПЃО№О¬ПЃП‡О·ПѓОµ ПѓП„О·ОЅ О·О»ОµОєП„ПЃОїОЅО№ОєО® ПѓП„ОїО№П‡ОµО№ОїОёОµПѓОЇО±, ПЂО±ПЃО±ОјО­ОЅОїОЅП„О±П‚ ОјОµ ОєО¬ОёОµ П„ПЃПЊПЂОї О±ОЅО±О»О»ОїОЇП‰П„Ої. О€ОіО№ОЅОµ ОґО·ОјОїП†О№О»О­П‚ П„О· ОґОµОєО±ОµП„ОЇО± П„ОїП… \'60 ОјОµ П„О·ОЅ О­ОєОґОїПѓО· П„П‰ОЅ ОґОµО№ОіОјО¬П„П‰ОЅ П„О·П‚ Letraset ПЊПЂОїП… ПЂОµПЃО№ОµО»О¬ОјОІО±ОЅО±ОЅ О±ПЂОїПѓПЂО¬ПѓОјО±П„О± П„ОїП… Lorem Ipsum, ОєО±О№ ПЂО№Ої ПЂПЃПЊПѓП†О±П„О± ОјОµ П„Ої О»ОїОіО№ПѓОјО№ОєПЊ О·О»ОµОєП„ПЃОїОЅО№ОєО®П‚ ПѓОµО»О№ОґОїПЂОїОЇО·ПѓО·П‚ ПЊПЂП‰П‚ П„Ої Aldus PageMaker ПЂОїП… ПЂОµПЃО№ОµОЇП‡О±ОЅ ОµОєОґОїП‡О­П‚ П„ОїП… Lorem Ipsum.';
            case 'hi':
                return 'Lorem Ipsum а¤›а¤Єа¤ѕа¤€ а¤�?а¤° а¤…а¤•а�?Ќа¤·а¤° а¤Їа�?‹а¤ња¤�? а¤‰а¤¦а�?Ќа¤Їа�?‹а¤— а¤•а¤ѕ а¤�?а¤• а¤ёа¤ѕа¤§а¤ѕа¤°а¤Ј а¤Ўа¤®а�?Ђ а¤Єа¤ѕа¤  а¤№а�?€. Lorem Ipsum а¤ёа¤�? а�?§а�?«а�?¦а�?¦ а¤•а�?‡ а¤¬а¤ѕа¤¦ а¤ёа�?‡ а¤…а¤­а�?Ђ а¤¤а¤• а¤‡а¤ё а¤‰а¤¦а�?Ќа¤Їа�?‹а¤— а¤•а¤ѕ а¤®а¤ѕа¤�?а¤• а¤Ўа¤®а�?Ђ а¤Єа¤ѕа¤  а¤®а¤�? а¤—а¤Їа¤ѕ, а¤ња¤¬ а¤�?а¤• а¤…а¤ња�?Ќа¤ћа¤ѕа¤¤ а¤®а�?Ѓа¤¦а�?Ќа¤°а¤• а¤�?а�?‡ а¤�?а¤®а�?‚а¤�?а¤ѕ а¤Іа�?‡а¤•а¤° а¤�?а¤• а¤�?а¤®а�?‚а¤�?а¤ѕ а¤•а¤їа¤¤а¤ѕа¤¬ а¤¬а¤�?а¤ѕа¤€. а¤Їа¤№ а¤�? а¤•а�?‡а¤µа¤І а¤Єа¤ѕа¤Ѓа¤љ а¤ёа¤¦а¤їа¤Їа�?‹а¤‚ а¤ёа�?‡ а¤ња�?Ђа¤µа¤їа¤¤ а¤°а¤№а¤ѕ а¤¬а¤Іа�?Ќа¤•а¤ї а¤‡а¤ёа¤�?а�?‡ а¤‡а¤Іа�?‡а¤•а�?Ќа¤џа�?Ќа¤°а�?‰а¤�?а¤їа¤• а¤®а�?Ђа¤Ўа¤їа¤Їа¤ѕ а¤®а�?‡а¤‚ а¤›а¤Іа¤ѕа¤‚а¤— а¤Іа¤—а¤ѕа¤�?а�?‡ а¤•а�?‡ а¤¬а¤ѕа¤¦ а¤­а�?Ђ а¤®а�?‚а¤Іа¤¤а¤ѓ а¤…а¤Єа¤°а¤їа¤µа¤°а�?Ќа¤¤а¤їа¤¤ а¤°а¤№а¤ѕ. а¤Їа¤№ 1960 а¤•а�?‡ а¤¦а¤¶а¤• а¤®а�?‡а¤‚ Letraset Lorem Ipsum а¤…а¤‚а¤¶ а¤Їа�?Ѓа¤•а�?Ќа¤¤ а¤Єа¤¤а�?Ќа¤° а¤•а�?‡ а¤°а¤їа¤Іа�?Ђа¤њ а¤•а�?‡ а¤ёа¤ѕа¤�? а¤Іа�?‹а¤•а¤Єа�?Ќа¤°а¤їа¤Ї а¤№а�?Ѓа¤†, а¤�?а¤° а¤№а¤ѕа¤І а¤№а�?Ђ а¤®а�?‡а¤‚ Aldus PageMaker Lorem Ipsum а¤•а�?‡ а¤ёа¤‚а¤ёа�?Ќа¤•а¤°а¤Ја�?‹а¤‚ а¤ёа¤№а¤їа¤¤ а¤¤а¤°а¤№ а¤Ўа�?‡а¤ёа�?Ќа¤•а¤џа�?‰а¤Є а¤Єа�?Ќа¤°а¤•а¤ѕа¤¶а¤�? а¤ёа�?‰а¤«а�?Ќа¤џа¤µа�?‡а¤Їа¤° а¤•а�?‡ а¤ёа¤ѕа¤�? а¤…а¤§а¤їа¤• а¤Єа�?Ќа¤°а¤ља¤Іа¤їа¤¤ а¤№а�?Ѓа¤†.';
            case 'hu':
                return 'A Lorem Ipsum egy egyszerГ» szГ¶vegrГ©szlete, szГ¶vegutГЎnzata a betГ»szedГµ Г©s nyomdaiparnak. A Lorem Ipsum az 1500-as Г©vek Гіta standard szГ¶vegrГ©szletkГ©nt szolgГЎlt az iparban; mikor egy ismeretlen nyomdГЎsz Г¶sszeГЎllГ­totta a betГ»kГ©szletГ©t Г©s egy pГ©lda-kГ¶nyvet vagy szГ¶veget nyomott papГ­rra, ezt hasznГЎlta. Nem csak 5 Г©vszГЎzadot Г©lt tГєl, de az elektronikus betГ»kГ©szleteknГ©l is vГЎltozatlanul megmaradt. Az 1960-as Г©vekben nГ©pszerГ»sГ­tettГ©k a Lorem Ipsum rГ©szleteket magukbafoglalГі Letraset lapokkal, Г©s legutГіbb softwarekkel mint pГ©ldГЎul az Aldus Pagemaker.';
            case 'id':
                return 'Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf. Ia tidak hanya bertahan selama 5 abad, tapi juga telah beralih ke penataan huruf elektronik, tanpa ada perubahan apapun. Ia mulai dipopulerkan pada tahun 1960 dengan diluncurkannya lembaran-lembaran Letraset yang menggunakan kalimat-kalimat dari Lorem Ipsum, dan seiring munculnya perangkat lunak Desktop Publishing seperti Aldus PageMaker juga memiliki versi Lorem Ipsum.';
            case 'it':
                return 'Lorem Ipsum Г�? un testo segnaposto utilizzato nel settore della tipografia e della stampa. Lorem Ipsum Г�? considerato il testo segnaposto standard sin dal sedicesimo secolo, quando un anonimo tipografo prese una cassetta di caratteri e li assemblГІ per preparare un testo campione. Г€ sopravvissuto non solo a piГ№ di cinque secoli, ma anche al passaggio alla videoimpaginazione, pervenendoci sostanzialmente inalterato. Fu reso popolare, negli anni вЂ™60, con la diffusione dei fogli di caratteri trasferibili вЂњLetrasetвЂќ, che contenevano passaggi del Lorem Ipsum, e piГ№ recentemente da software di impaginazione come Aldus PageMaker, che includeva versioni del Lorem Ipsum.';
            case 'lv':
                return 'Lorem Ipsum вЂ“ tas ir teksta salikums, kuru izmanto poligrДЃfijДЃ un maketД“ЕЎanas darbos. Lorem Ipsum ir kДјuvis par vispДЃrpieЕ†emtu teksta aizvietotДЃju kopЕЎ 16. gadsimta sДЃkuma. TajДЃ laikДЃ kДЃds nezinДЃms iespiedД“js izveidoja teksta fragmentu, lai nodrukДЃtu grДЃmatu ar burtu paraugiem. Tas ir ne tikai pДЃrdzД«vojis piecus gadsimtus, bet bez ievД“rojamДЃm izmaiЕ†ДЃm saglabДЃjies arД« mЕ«sdienДЃs, pДЃrejot uz datorizД“tu teksta apstrДЃdi. TДЃ popularizД“ЕЎanai 60-tajos gados kalpoja Letraset burtu paraugu publicД“ЕЎana ar Lorem Ipsum teksta fragmentiem un, nesenДЃ pagДЃtnД“, tДЃdas maketД“ЕЎanas programmas kДЃ Aldus PageMaker, kuras ЕЎablonu paraugos ir izmantots Lorem Ipsum teksts.';
            case 'lt':
                return 'Lorem ipsum - tai fiktyvus tekstas naudojamas spaudos ir grafinio dizaino pasaulyje jau nuo XVI a. pradЕѕios. Lorem Ipsum tapo standartiniu fiktyviu tekstu, kai neЕѕinomas spaustuvininkas atsitiktine tvarka iЕЎdД—liojo raides atspaudЕі prese ir tokiu bЕ«du sukЕ«rД— raidЕѕiЕі egzemplioriЕі. Е is tekstas iЕЎliko beveik nepasikeitД™s ne tik penkis amЕѕius, bet ir ДЇЕѕengД— i kopiuterinio grafinio dizaino laikus. Jis iЕЎpopuliarД—jo XX a. ЕЎeЕЎtajame deЕЎimtmetyje, kai buvo iЕЎleisti Letraset lapai su Lorem Ipsum iЕЎtraukomis, o vД—liau -leidybinД— sistema AldusPageMaker, kurioje buvo ir Lorem Ipsum versija.';
            case 'mk':
                return 'Lorem Ipsum Рµ РµРґРЅРѕСЃС‚Р°РІРµРЅ РјРѕРґРµР» РЅР° С‚РµРєСЃС‚ РєРѕС СЃРµ РєРѕСЂРёСЃС‚РµР» РІРѕ РїРµС‡Р°С‚Р°СЂСЃРєР°С‚Р° РёРЅРґСѓСЃС‚СЂРёСР°. Lorem ipsum Р±РёР» РёРЅРґСѓСЃС‚СЂРёСЃРєРё СЃС‚Р°РЅРґР°СЂРґ РєРѕС СЃРµ РєРѕСЂРёСЃС‚РµР» РєР°РєРѕ РјРѕРґРµР» СѓС€С‚Рµ РїСЂРµРґ 1500 РіРѕРґРёРЅРё, РєРѕРіР° РЅРµРїРѕР·РЅР°С‚ РїРµС‡Р°С‚Р°СЂ Р·РµР» РєСѓС‚РёСР° СЃРѕ Р±СѓРєРІРё Рё РіРё СЃР»РѕР¶РёР» РЅР° С‚Р°РєРѕРІ РЅР°С‡РёРЅ Р·Р° РґР° РЅР°РїСЂР°РІРё РїСЂРёРјРµСЂРѕРє РЅР° РєРЅРёРіР°. Р РЅРµ СЃР°РјРѕ С€С‚Рѕ РѕРІРѕС РјРѕРґРµР» РѕРїСЃС‚Р°РЅР°Р» РїРµС‚ РІРµРєРѕРІРё С‚СѓРєСѓ РїРѕС‡РЅР°Р» РґР° СЃРµ РєРѕСЂРёСЃС‚Рё Рё РІРѕ РµР»РµРєС‚СЂРѕРЅСЃРєРёС‚Рµ РјРµРґРёСѓРјРё, РєРѕС СЃРµ СѓС€С‚Рµ РЅРµ Рµ РїСЂРѕРјРµРЅРµС‚. РЎРµ РїРѕРїСѓР»Р°СЂРёР·РёСЂР°Р» РІРѕ 60-С‚РёС‚Рµ РіРѕРґРёРЅРё РЅР° РґРІР°РµСЃРµС‚С‚РёРѕС‚ РІРµРє СЃРѕ РёР·РґР°РІР°СљРµС‚Рѕ РЅР° Р·Р±РёСЂРєР° РѕРґ СЃС‚СЂР°РЅРё РІРѕ РєРѕРё СЃРµ РЅР°РѕС“Р°Р»Рµ Lorem Ipsum РїР°СЃСѓСЃРё, Р° РґРµРЅРµСЃ РІРѕ СЃРѕС„С‚РІРµСЂСЃРєРёРѕС‚ РїР°РєРµС‚ РєР°РєРѕ С€С‚Рѕ Рµ Aldus PageMaker РІРѕ РєРѕС СЃРµ РЅР°РѕС“Р° РІРµСЂР·РёСР° РЅР° Lorem Ipsum.';
            case 'ms':
                return 'Lorem Ipsum adalah text contoh digunakan didalam industri pencetakan dan typesetting. Lorem Ipsum telah menjadi text contoh semenjak tahun ke 1500an, apabila pencetak yang kurang terkenal mengambil sebuah galeri cetak dan merobakanya menjadi satu buku spesimen. Ia telah bertahan bukan hanya selama lima kurun, tetapi telah melonjak ke era typesetting elektronik, dengan tiada perubahan ketara. Ia telah dipopularkan pada tahun 1960an dengan penerbitan Letraset yang mebawa kangungan Lorem Ipsum, dan lebih terkini dengan sofwer pencetakan desktop seperti Aldus PageMaker yang telah menyertakan satu versi Lorem Ipsum.';
            case 'no':
                return 'Lorem Ipsum er rett og slett dummytekst fra og for trykkeindustrien. Lorem Ipsum har vГ¦rt bransjens standard for dummytekst helt siden 1500-tallet, da en ukjent boktrykker stokket en mengde bokstaver for Г�? lage et prГёveeksemplar av en bok. Lorem Ipsum har tГ�?lt tidens tann usedvanlig godt, og har i tillegg til Г�? bestГ�? gjennom fem Г�?rhundrer ogsГ�? tГ�?lt spranget over til elektronisk typografi uten vesentlige endringer. Lorem Ipsum ble gjort allment kjent i 1960-Г�?rene ved lanseringen av Letraset-ark med avsnitt fra Lorem Ipsum, og senere med sideombrekkingsprogrammet Aldus PageMaker som tok i bruk nettopp Lorem Ipsum for dummytekst.';
            case 'pl':
                return 'Lorem Ipsum jest tekstem stosowanym jako przykЕ‚adowy wypeЕ‚niacz w przemyЕ›le poligraficznym. ZostaЕ‚ po raz pierwszy uЕјyty w XV w. przez nieznanego drukarza do wypeЕ‚nienia tekstem prГіbnej ksiД…Ејki. PiД™Д‡ wiekГіw pГіЕєniej zaczД…Е‚ byД‡ uЕјywany przemyЕ›le elektronicznym, pozostajД…c praktycznie niezmienionym. SpopularyzowaЕ‚ siД™ w latach 60. XX w. wraz z publikacjД… arkuszy Letrasetu, zawierajД…cych fragmenty Lorem Ipsum, a ostatnio z zawierajД…cym rГіЕјne wersje Lorem Ipsum oprogramowaniem przeznaczonym do realizacji drukГіw na komputerach osobistych, jak Aldus PageMaker';
            case 'pt':
                return 'O Lorem Ipsum Г© um texto modelo da indГєstria tipogrГЎfica e de impressГЈo. O Lorem Ipsum tem vindo a ser o texto padrГЈo usado por estas indГєstrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espГ©cime de livro. Este texto nГЈo sГі sobreviveu 5 sГ©culos, mas tambГ©m o salto para a tipografia electrГіnica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilizaГ§ГЈo das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicaГ§ГЈo como o Aldus PageMaker que incluem versГµes do Lorem Ipsum.';
            case 'ro':
                return 'Lorem Ipsum este pur Еџi simplu o machetДѓ pentru text a industriei tipografice. Lorem Ipsum a fost macheta standard a industriei Г®ncДѓ din secolul al XVI-lea, cГўnd un tipograf anonim a luat o planЕџetДѓ de litere Еџi le-a amestecat pentru a crea o carte demonstrativДѓ pentru literele respective. Nu doar cДѓ a supravieЕЈuit timp de cinci secole, dar Еџi a facut saltul Г®n tipografia electronicДѓ practic neschimbatДѓ. A fost popularizatДѓ Г®n anii \'60 odatДѓ cu ieЕџirea colilor Letraset care conЕЈineau pasaje Lorem Ipsum, iar mai recent, prin programele de publicare pentru calculator, ca Aldus PageMaker care includeau versiuni de Lorem Ipsum.';
            case 'ru':
                return 'Lorem Ipsum - СЌС‚Рѕ С‚РµРєСЃС‚-"СЂС‹Р±Р°", С‡Р°СЃС‚Рѕ РёСЃРїРѕР»СЊР·СѓРµРјС‹Р№ РІ РїРµС‡Р°С‚Рё Рё РІСЌР±-РґРёР·Р°Р№РЅРµ. Lorem Ipsum С�?РІР»С�?РµС‚СЃС�? СЃС‚Р°РЅРґР°СЂС‚РЅРѕР№ "СЂС‹Р±РѕР№" РґР»С�? С‚РµРєСЃС‚РѕРІ РЅР° Р»Р°С‚РёРЅРёС†Рµ СЃ РЅР°С‡Р°Р»Р° XVI РІРµРєР°. Р’ С‚Рѕ РІСЂРµРјС�? РЅРµРєРёР№ Р±РµР·С‹РјС�?РЅРЅС‹Р№ РїРµС‡Р°С‚РЅРёРє СЃРѕР·РґР°Р» Р±РѕР»СЊС€СѓСЋ РєРѕР»Р»РµРєС†РёСЋ СЂР°Р·РјРµСЂРѕРІ Рё С„РѕСЂРј С€СЂРёС„С‚РѕРІ, РёСЃРїРѕР»СЊР·СѓС�? Lorem Ipsum РґР»С�? СЂР°СЃРїРµС‡Р°С‚РєРё РѕР±СЂР°Р·С†РѕРІ. Lorem Ipsum РЅРµ С‚РѕР»СЊРєРѕ СѓСЃРїРµС€РЅРѕ РїРµСЂРµР¶РёР» Р±РµР· Р·Р°РјРµС‚РЅС‹С… РёР·РјРµРЅРµРЅРёР№ РїС�?С‚СЊ РІРµРєРѕРІ, РЅРѕ Рё РїРµСЂРµС€Р°РіРЅСѓР» РІ СЌР»РµРєС‚СЂРѕРЅРЅС‹Р№ РґРёР·Р°Р№РЅ. Р•РіРѕ РїРѕРїСѓР»С�?СЂРёР·Р°С†РёРё РІ РЅРѕРІРѕРµ РІСЂРµРјС�? РїРѕСЃР»СѓР¶РёР»Рё РїСѓР±Р»РёРєР°С†РёС�? Р»РёСЃС‚РѕРІ Letraset СЃ РѕР±СЂР°Р·С†Р°РјРё Lorem Ipsum РІ 60-С… РіРѕРґР°С… Рё, РІ Р±РѕР»РµРµ РЅРµРґР°РІРЅРµРµ РІСЂРµРјС�?, РїСЂРѕРіСЂР°РјРјС‹ СЌР»РµРєС‚СЂРѕРЅРЅРѕР№ РІС‘СЂСЃС‚РєРё С‚РёРїР° Aldus PageMaker, РІ С€Р°Р±Р»РѕРЅР°С… РєРѕС‚РѕСЂС‹С… РёСЃРїРѕР»СЊР·СѓРµС‚СЃС�? Lorem Ipsum.';
            case 'sr':
                return 'Lorem Ipsum СРµ СРµРґРЅРѕСЃС‚Р°РІРЅРѕ РјРѕРґРµР» С‚РµРєСЃС‚Р° РєРѕСРё СЃРµ РєРѕСЂРёСЃС‚Рё Сѓ С€С‚Р°РјРїР°СЂСЃРєРѕС Рё СЃР»РѕРІРѕСЃР»Р°РіР°С‡РєРѕС РёРЅРґСѓСЃС‚СЂРёСРё. Lorem ipsum СРµ Р±РёРѕ СЃС‚Р°РЅРґР°СЂРґ Р·Р° РјРѕРґРµР» С‚РµРєСЃС‚Р° СРѕС€ РѕРґ 1500. РіРѕРґРёРЅРµ, РєР°РґР° СРµ РЅРµРїРѕР·РЅР°С‚Рё С€С‚Р°РјРїР°СЂ СѓР·РµРѕ РєСѓС‚РёССѓ СЃР° СЃР»РѕРІРёРјР° Рё СЃР»РѕР¶РёРѕ РёС… РєР°РєРѕ Р±Рё РЅР°РїСЂР°РІРёРѕ СѓР·РѕСЂР°Рє РєСљРёРіРµ. РќРµ СЃР°РјРѕ С€С‚Рѕ СРµ РѕРІР°С РјРѕРґРµР» РѕРїСЃС‚Р°Рѕ РїРµС‚ РІРµРєРѕРІР°, РЅРµРіРѕ СРµ С‡Р°Рє РїРѕС‡РµРѕ РґР° СЃРµ РєРѕСЂРёСЃС‚Рё Рё Сѓ РµР»РµРєС‚СЂРѕРЅСЃРєРёРј РјРµРґРёСРёРјР°, РЅРµРїСЂРѕРјРµРЅРёРІС€Рё СЃРµ. РџРѕРїСѓР»Р°СЂРёР·РѕРІР°РЅ СРµ С€РµР·РґРµСЃРµС‚РёС… РіРѕРґРёРЅР° РґРІР°РґРµСЃРµС‚РѕРі РІРµРєР° Р·Р°СРµРґРЅРѕ СЃР° Р»РёСЃС‚РѕРІРёРјР° Р»РµС‚РµСЂСЃРµС‚Р° РєРѕСРё СЃСѓ СЃР°РґСЂР¶Р°Р»Рё Lorem Ipsum РїР°СЃСѓСЃРµ, Р° РґР°РЅР°СЃ СЃР° СЃРѕС„С‚РІРµСЂСЃРєРёРј РїР°РєРµС‚РѕРј Р·Р° РїСЂРµР»РѕРј РєР°Рѕ С€С‚Рѕ СРµ Aldus PageMaker РєРѕСРё СРµ СЃР°РґСЂР¶Р°Рѕ Lorem Ipsum РІРµСЂР·РёСРµ.';
            case 'sk':
                return 'Lorem Ipsum je fiktГ­vny text, pouЕѕГ­vanГЅ pri nГЎvrhu tlaДЌovГ­n a typografie. Lorem Ipsum je ЕЎtandardnГЅm vГЅplЕ€ovГЅm textom uЕѕ od 16. storoДЌia, keД�? neznГЎmy tlaДЌiar zobral sadzobnicu plnГє tlaДЌovГЅch znakov a pomieЕЎal ich, aby tak vytvoril vzorkovГє knihu. PreЕѕil nielen pГ¤Е�? storoДЌГ­, ale aj skok do elektronickej sadzby, a pritom zostal v podstate nezmenenГЅ. SpopularizovanГЅ bol v 60-tych rokoch 20.storoДЌia, vydanГ­m hГЎrkov Letraset, ktorГ© obsahovali pasГЎЕѕe Lorem Ipsum, a neskГґr aj publikaДЌnГЅm softvГ©rom ako Aldus PageMaker, ktorГЅ obsahoval verzie Lorem Ipsum.';
            case 'sl':
                return 'Lorem Ipsum je slepi tekst, ki se uporablja pri razvoju tipografij in pri pripravi za tisk. Lorem Ipsum je v uporabi Еѕe veДЌ kot petsto let saj je to kombinacijo znakov neznani tiskar zdruЕѕil v vzorДЌno knjigo Еѕe v zaДЌetku 16. stoletja. To besedilo pa ni zgolj preЕѕivelo pet stoletij, temveДЌ se je z malenkostnimi spremembami uspeЕЎno uveljavilo tudi v elektronskem namiznem zaloЕѕniЕЎtvu. Na priljubljenosti je Lorem Ipsum pridobil v sedemdesetih letih prejЕЎnjega stoletja, ko so na trg lansirali Letraset folije z Lorem Ipsum odstavki. V zadnjem ДЌasu se Lorem Ipsum pojavlja tudi v priljubljenih programih za namizno zaloЕѕniЕЎtvo kot je na primer Aldus PageMaker.';
            case 'es':
                return 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estГЎndar de las industrias desde el aГ±o 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usГі una galerГ­a de textos y los mezclГі de tal manera que logrГі hacer un libro de textos especimen. No sГіlo sobreviviГі 500 aГ±os, sino que tambien ingresГі como texto de relleno en documentos electrГіnicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creaciГіn de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y mГЎs recientemente con software de autoediciГіn, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.';
            case 'sv':
                return 'Lorem Ipsum Г¤r en utfyllnadstext frГ�?n tryck- och fГ¶rlagsindustrin. Lorem ipsum har varit standard Г¤nda sedan 1500-talet, nГ¤r en okГ¤nd boksГ¤ttare tog att antal bokstГ¤ver och blandade dem fГ¶r att gГ¶ra ett provexemplar av en bok. Lorem ipsum har inte bara Г¶verlevt fem Г�?rhundraden, utan Г¤ven Г¶vergГ�?ngen till elektronisk typografi utan stГ¶rre fГ¶rГ¤ndringar. Det blev allmГ¤nt kГ¤nt pГ�? 1960-talet i samband med lanseringen av Letraset-ark med avsnitt av Lorem Ipsum, och senare med mjukvaror som Aldus PageMaker.';
            case 'th':
                return 'Lorem Ipsum аё„аё·аё­ а№Ђаё™аё·а№‰аё­аё«аёІаё€аёіаё�?аё­аё‡а№Ѓаёљаёља№ЂаёЈаёµаёўаёља№† аё—аёµа№€а№ѓаёЉа№‰аёЃаё±аё™а№ѓаё™аёаёёаёЈаёЃаёґаё€аё‡аёІаё™аёћаёґаёЎаёћа№Њаё«аёЈаё·аё­аё‡аёІаё™а№ЂаёЈаёµаёўаё‡аёћаёґаёЎаёћа№Њ аёЎаё±аё™а№„аё�?а№‰аёЃаё�?аёІаёўаёЎаёІа№Ђаё›а№‡аё™а№Ђаё™аё·а№‰аё­аё«аёІаё€аёіаё�?аё­аё‡аёЎаёІаё•аёЈаёђаёІаё™аё‚аё­аё‡аёаёёаёЈаёЃаёґаё€аё�?аё±аё‡аёЃаё�?а№€аёІаё§аёЎаёІаё•аё±а№‰аё‡а№Ѓаё•а№€аё�?аё•аё§аёЈаёЈаё©аё—аёµа№€ 16 а№ЂаёЎаё·а№€аё­а№Ђаё„аёЈаё·а№€аё­аё‡аёћаёґаёЎаёћа№Ња№‚аё™а№Ђаё™аёЎа№Ђаё„аёЈаё·а№€аё­аё‡аё«аё™аё¶а№€аё‡аё™аёіаёЈаёІаё‡аё•аё±аё§аёћаёґаёЎаёћа№ЊаёЎаёІаёЄаё�?аё±аёљаёЄаё±аёљаё•аёіа№Ѓаё«аё™а№€аё‡аё•аё±аё§аё­аё±аёЃаё©аёЈа№Ђаёћаё·а№€аё­аё—аёіаё«аё™аё±аё‡аёЄаё·аё­аё•аё±аё§аё­аёўа№€аёІаё‡ Lorem Ipsum аё­аёўаё№а№€аёўаё‡аё„аё‡аёЃаёЈаё°аёћаё±аё™аёЎаёІа№„аёЎа№€а№ѓаёЉа№€а№Ѓаё„а№€а№Ђаёћаёµаёўаё‡аё«а№‰аёІаё�?аё•аё§аёЈаёЈаё© а№Ѓаё•а№€аё­аёўаё№а№€аёЎаёІаё€аё™аё–аё¶аё‡аёўаёёаё„аё—аёµа№€аёћаё�?аёґаёЃа№‚аё‰аёЎа№Ђаё‚а№‰аёІаёЄаё№а№€аё‡аёІаё™а№ЂаёЈаёµаёўаё‡аёћаёґаёЎаёћа№Њаё�?а№‰аё§аёўаё§аёґаёаёµаё—аёІаё‡аё­аёґа№Ђаё�?а№‡аёЃаё—аёЈаё­аё™аёґаёЃаёЄа№Њ а№Ѓаё�?аё°аёўаё±аё‡аё„аё‡аёЄаё аёІаёћа№Ђаё�?аёґаёЎа№„аё§а№‰аё­аёўа№€аёІаё‡а№„аёЎа№€аёЎаёµаёЃаёІаёЈа№Ђаё›аё�?аёµа№€аёўаё™а№Ѓаё›аё�?аё‡ аёЎаё±аё™а№„аё�?а№‰аёЈаё±аёљаё„аё§аёІаёЎаё™аёґаёўаёЎаёЎаёІаёЃаё‚аё¶а№‰аё™а№ѓаё™аёўаёёаё„ аё„.аё�?. 1960 а№ЂаёЎаё·а№€аё­а№Ѓаёња№€аё™ Letraset аё§аёІаё‡аё€аёіаё«аё™а№€аёІаёўа№‚аё�?аёўаёЎаёµаё‚а№‰аё­аё„аё§аёІаёЎаёљаё™аё™аё±а№‰аё™а№Ђаё›а№‡аё™ Lorem Ipsum а№Ѓаё�?аё°аё�?а№€аёІаёЄаёёаё�?аёЃаё§а№€аёІаё™аё±а№‰аё™ аё„аё·аё­а№ЂаёЎаё·а№€аё­аё‹аё­аёџаё—а№Ња№Ѓаё§аёЈа№ЊаёЃаёІаёЈаё—аёіаёЄаё·а№€аё­аёЄаёґа№€аё‡аёћаёґаёЎаёћа№Њ (Desktop Publishing) аё­аёўа№€аёІаё‡ Aldus PageMaker а№„аё�?а№‰аёЈаё§аёЎа№Ђаё­аёІ Lorem Ipsum а№Ђаё§аё­аёЈа№ЊаёЉаё±а№€аё™аё•а№€аёІаё‡а№† а№Ђаё‚а№‰аёІа№„аё§а№‰а№ѓаё™аё‹аё­аёџаё—а№Ња№Ѓаё§аёЈа№Њаё�?а№‰аё§аёў';
            case 'tr':
                return 'Lorem Ipsum, dizgi ve baskД± endГјstrisinde kullanД±lan mД±gД±r metinlerdir. Lorem Ipsum, adД± bilinmeyen bir matbaacД±nД±n bir hurufat numune kitabД± oluЕџturmak Гјzere bir yazД± galerisini alarak karД±ЕџtД±rdД±ДџД± 1500\'lerden beri endГјstri standardД± sahte metinler olarak kullanД±lmД±ЕџtД±r. BeЕџyГјz yД±l boyunca varlД±ДџД±nД± sГјrdГјrmekle kalmamД±Еџ, aynД± zamanda pek deДџiЕџmeden elektronik dizgiye de sД±Г§ramД±ЕџtД±r. 1960\'larda Lorem Ipsum pasajlarД± da iГ§eren Letraset yapraklarД±nД±n yayД±nlanmasД± ile ve yakД±n zamanda Aldus PageMaker gibi Lorem Ipsum sГјrГјmleri iГ§eren masaГјstГј yayД±ncД±lД±k yazД±lД±mlarД± ile popГјler olmuЕџtur.';
            case 'uk':
                return 'Lorem Ipsum - С†Рµ С‚РµРєСЃС‚-"СЂРёР±Р°", С‰Рѕ РІРёРєРѕСЂРёСЃС‚РѕРІСѓС�?С‚СЊСЃС�? РІ РґСЂСѓРєР°СЂСЃС‚РІС– С‚Р° РґРёР·Р°Р№РЅС–. Lorem Ipsum С�?, С„Р°РєС‚РёС‡РЅРѕ, СЃС‚Р°РЅРґР°СЂС‚РЅРѕСЋ "СЂРёР±РѕСЋ" Р°Р¶ Р· XVI СЃС‚РѕСЂС–С‡С‡С�?, РєРѕР»Рё РЅРµРІС–РґРѕРјРёР№ РґСЂСѓРєР°СЂ РІР·С�?РІ С€СЂРёС„С‚РѕРІСѓ РіСЂР°РЅРєСѓ С‚Р° СЃРєР»Р°РІ РЅР° РЅС–Р№ РїС–РґР±С–СЂРєСѓ Р·СЂР°Р·РєС–РІ С€СЂРёС„С‚С–РІ. "Р РёР±Р°" РЅРµ С‚С–Р»СЊРєРё СѓСЃРїС–С€РЅРѕ РїРµСЂРµР¶РёР»Р° Рї\'С�?С‚СЊ СЃС‚РѕР»С–С‚СЊ, Р°Р»Рµ Р№ РїСЂРёР¶РёР»Р°СЃС�? РІ РµР»РµРєС‚СЂРѕРЅРЅРѕРјСѓ РІРµСЂСЃС‚СѓРІР°РЅРЅС–, Р·Р°Р»РёС€Р°СЋС‡РёСЃСЊ РїРѕ СЃСѓС‚С– РЅРµР·РјС–РЅРЅРѕСЋ. Р’РѕРЅР° РїРѕРїСѓР»С�?СЂРёР·СѓРІР°Р»Р°СЃСЊ РІ 60-РёС… СЂРѕРєР°С… РјРёРЅСѓР»РѕРіРѕ СЃС‚РѕСЂС–С‡С‡С�? Р·Р°РІРґС�?РєРё РІРёРґР°РЅРЅСЋ Р·СЂР°Р·РєС–РІ С€СЂРёС„С‚С–РІ Letraset, С�?РєС– РјС–СЃС‚РёР»Рё СѓСЂРёРІРєРё Р· Lorem Ipsum, С– РІРґСЂСѓРіРµ - РЅРµС‰РѕРґР°РІРЅРѕ Р·Р°РІРґС�?РєРё РїСЂРѕРіСЂР°РјР°Рј РєРѕРјРї\'СЋС‚РµСЂРЅРѕРіРѕ РІРµСЂСЃС‚СѓРІР°РЅРЅС�? РЅР° РєС€С‚Р°Р»С‚ Aldus Pagemaker, С�?РєС– РІРёРєРѕСЂРёСЃС‚РѕРІСѓРІР°Р»Рё СЂС–Р·РЅС– РІРµСЂСЃС–С— Lorem Ipsum.';
            case 'vi':
                return 'Lorem Ipsum chб»‰ Д‘ЖЎn giбєЈn lГ  mб»™t Д‘oбєЎn vДѓn bбєЈn giбєЈ, Д‘Ж°б»Јc dГ№ng vГ o viб»‡c trГ¬nh bГ y vГ  dГ n trang phб»�?c vб»�? cho in бє�?n. Lorem Ipsum Д‘ГЈ Д‘Ж°б»Јc sб»­ dб»�?ng nhЖ° mб»™t vДѓn bбєЈn chuбє©n cho ngГ nh cГґng nghiб»‡p in бє�?n tб»« nhб»Їng nДѓm 1500, khi mб»™t hб»Ќa sД© vГґ danh ghГ©p nhiб»Ѓu Д‘oбєЎn vДѓn bбєЈn vб»›i nhau Д‘б»ѓ tбєЎo thГ nh mб»™t bбєЈn mбє«u vДѓn bбєЈn. ДђoбєЎn vДѓn bбєЈn nГ y khГґng nhб»Їng Д‘ГЈ tб»“n tбєЎi nДѓm thбєї kб»‰, mГ  khi Д‘Ж°б»Јc ГЎp dб»�?ng vГ o tin hб»Ќc vДѓn phГІng, nб»™i dung cб»§a nГі vбє«n khГґng hб»Ѓ bб»‹ thay Д‘б»•i. NГі Д‘ГЈ Д‘Ж°б»Јc phб»• biбєїn trong nhб»Їng nДѓm 1960 nhб»ќ viб»‡c bГЎn nhб»Їng bбєЈn giбє�?y Letraset in nhб»Їng Д‘oбєЎn Lorem Ipsum, vГ  gбє§n Д‘Гўy hЖЎn, Д‘Ж°б»Јc sб»­ dб»�?ng trong cГЎc б»©ng dб»�?ng dГ n trang, nhЖ° Aldus PageMaker.';

            case 'en':
            default:
                return 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';

        }
    }
    // ---------------------------------------------------------------------
    // INIT DEMO
    // ----------------------------------------------------------------------
    let userLangFull = (navigator.language || navigator.userLanguage).split('-', 2);
    let userLang = userLangFull[0];

    function prepareDemo() {
        let sel_lang = document.getElementById('lang');
        for (let entry in countryNames) {
            sel_lang.options[sel_lang.options.length] = new Option(countryNames[entry], entry);
        }
        sel_lang.value = userLang;
    }
    // ----------------------------------------------------------------------
    // DEMO TEXT
    // ----------------------------------------------------------------------
    function printDemoText() {
        let text = document.getElementById('demotext').value;
        var c = new PosPrinterJob(getCurrentDriver(), getCurrentTransport());
        c.initialize();
        c.setEncoding(defaultCP[userLang]);

        let align = document.querySelector('input[name="align"]:checked').value;
        c.setAlignment(align);
        let font_flags = document.querySelectorAll('input[name="font"]');

        let i;
        let font = 0;
        for (i = 0; i < font_flags.length; i++) {
            if (font_flags[i].checked) {
                font = font + 1 * font_flags[i].value;
            }
        }
        c.setPrintMode(font);

        c.printLine(text);
        c.feed(2);
        c.execute();
    }
    // ----------------------------------------------------------------------
    // DEMO BARCODE
    // ----------------------------------------------------------------------
    function printDemoBarCode() {
        let tip = document.getElementById('bartype').value;
        let text = document.getElementById('demobarcode').value;
        let hri = document.querySelector('input[name="hri"]:checked').value;
        let h = document.getElementById('barcode_h').value;


        let c = new PosPrinterJob(getCurrentDriver(), getCurrentTransport());
        c.initialize();
        c.setBarcodeTextPosition(hri).setBarcodeHeight(h);
        c.printBarCode(text, tip).lf();
        c.feed(1);
        c.execute();
    }
    function printDemoBarCodeCustom(input) {
        // let tip = document.getElementById('bartype').value;
        // let text = document.getElementById('demobarcode').value; //value
        // let hri = document.querySelector('input[name="hri"]:checked').value; //0=None, 1=Above, 2=Below
        // let h = document.getElementById('barcode_h').value; //162px

        /* 
        65 = UPCA
        66 = UPCE
        67 = JAN13
        68 = JAN8
        69 = CODE39 Selected
        70 = ITF 
        71 = CODABAR 
        72 = CODE93 
        73 = CODE128 
        */
        let tip = 69; //Alternatif
        let text = input;
        let hri = 2;
        let h = 64;

        let c = new PosPrinterJob(getCurrentDriver(), getCurrentTransport());
        c.initialize();
        c.setBarcodeTextPosition(hri).setBarcodeHeight(h);
        c.printBarCode(text, tip).lf();
        // c.feed(1);
        c.execute();
    }      
    function democode(s) {
        let o = s.options;
        let i;
        for (i = 0; i < o.length; i++) {
            if (o[i].value == s.value) {
                document.getElementById("demobarcode").value = o[i].attributes['data-example'].value;
            }
        }
    }
    // ----------------------------------------------------------------------
    // DEMO QRCODE
    // ----------------------------------------------------------------------
    function printQrCode() {
        // let code = document.getElementById('demoqrcode').value;
        // let e = document.getElementById('QR_EC').value;
        // let s = document.getElementById('QR_SIZE').value;
        // let m = document.getElementById('QR_MODEL').value;
        let code = 'https://rawbt.402d.ru/app2/index.php';
        let e = 2;
        let s = 8; 
        let m = 1;

        let c = new PosPrinterJob(getCurrentDriver(), getCurrentTransport());
        c.initialize();
        c.center();
        c.printQrCode(code, e, s, m).lf();
        //            c.left().setPrintMode(c.FONT_SIZE_SMALL).printLine(code);
        c.feed(2);
        c.execute();
    }
    function printQrCodeCustom(input) {
        // let code = document.getElementById('demoqrcode').value;
        // let e = document.getElementById('QR_EC').value;
        // let s = document.getElementById('QR_SIZE').value;
        // let m = document.getElementById('QR_MODEL').value;
        // let code = 'https://rawbt.402d.ru/app2/index.php';
        // alert(input);
        let code = input;
        let e = 2;
        let s = 8; 
        let m = 1;

        let c = new PosPrinterJob(getCurrentDriver(), getCurrentTransport());
        c.initialize();
        c.center();
        c.printQrCode(code, e, s, m).lf();
        //            c.left().setPrintMode(c.FONT_SIZE_SMALL).printLine(code);
        c.feed(1);
        c.execute();
    } 
    // switch
    // more
    // demo
    // header
    // lang
    // demotext
    // bartype
    // demobarcode
    // show_h
    // barcode_h

    // demoqrcode
    // QR_EC
    // QR_SIZE
    // QR_MODEL            
</script>