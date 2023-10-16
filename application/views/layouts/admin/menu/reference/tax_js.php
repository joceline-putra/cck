<script type="text/javascript">
$(document).ready(function() {
    let menu_link = "<?php echo $_view;?>";
    $(".nav-tabs").find('li[class="active"]').removeClass('active');
    $(".nav-tabs").find('li[data-name="' + menu_link + '"]').addClass('active');
        
    // console.log(identity);
    let url = "<?= base_url('tax'); ?>";  

    $(function() {
    });

    //Select2
    /*
    $('#select').select2({
        placeholder: '--- Pilih ---',
        minimumInputLength: 0,
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
    $("#tax_date").datepicker({
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
        tax_table.ajax.reload();
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
    let tax_table = $("#table-data").DataTable({
        "serverSide": true,
        "ajax": {
            url: url,
            type: 'post',
            dataType: 'json',
            cache: 'false',
            data: function(d) {
                d.action = 'load';
                d.length = $("#filter_length").find(':selected').val();
                d.filter_flag = $("#filter_flag").find(':selected').val();
                d.search = {value:$("#filter_search").val()};
            },
            dataSrc: function(data) {
                return data.result;
            }
        },
        "columnDefs": [
            {"targets":0, "width":"30%", "title":"Nama", "searchable":true, "orderable":true},
            {"targets":1, "width":"20%", "title":"Persentase %", "searchable":true, "orderable":true, "className":"text-right"},
            {"targets":2, "width":"20%", "title":"Flag", "searchable":true, "orderable":true},
        ],
        "order": [[0, 'ASC']],
        "columns": [
            {
                'data': 'tax_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += row.tax_name;
                    return dsp;
                }
            },{
                'data': 'tax_id',
                className: 'text-right',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += row.tax_percent;
                    // if(row.contact_email_1 != undefined){
                        // dsp += '<br>'+row.contact_email_1;
                    // }
                    return dsp;
                }
            },{
                'data': 'tax_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = ''; var label = 'Error Status'; var icon = 'fas fa-cog'; var color = 'white'; var bgcolor = '#d1dade';
                    if(parseInt(row.tax_flag) == 1){
                    //  dsp += '<label style="color:#6273df;">Aktif</label>';
                       label = 'Aktif';
                       icon = 'fas fa-lock';
                       bgcolor = '#0090d9';
                    }else if(parseInt(row.tax_flag) == 4){
                       //  dsp += '<label style="color:#ff194f;">Terhapus</label>';
                       label = 'Terhapus';
                       icon = 'fas fa-trash';
                       bgcolor = '#f35958';
                    }else if(parseInt(row.tax_flag) == 0){
                       //   dsp += '<label style="color:#ff9019;">Nonaktif</label>';
                       label = 'Nonaktif';
                       icon = 'fas fa-unlock';
                       // color = 'green';
                       bgcolor = '#ff9019';
                    }


                        /* Button Action Concept 1 */
                        // dsp += '&nbsp;<button class="btn btn_edit_tax btn-mini btn-primary"';
                        // dsp += 'data-tax-id="'+data+'" data-tax-name="'+row.tax_name+'" data-tax-flag="'+row.tax_flag+'" data-tax-session="'+row.tax_session+'">';
                        // dsp += '<span class="fas fa-edit primary"></span> Edit</button>';

                        // dsp += '&nbsp;<button class="btn btn_delete_tax btn-mini btn-danger"';
                        // dsp += 'data-tax-id="'+data+'" data-tax-name="'+row.tax_name+'" data-tax-flag="'+row.tax_flag+'" data-tax-session="'+row.tax_session+'">';
                        // dsp += '<span class="fas fa-trash danger"></span> Hapus</button>';

                        // if (parseInt(row.tax_flag) === 1) {
                        //   dsp += '&nbsp;<button class="btn btn_update_flag_tax btn-mini btn-primary" style="background-color:#ff9019;"';
                        //   dsp += 'data-tax-id="'+data+'" data-tax-name="'+row.tax_name+'" data-tax-flag="'+row.tax_flag+'" data-tax-session="'+row.tax_session+'">';
                        //   dsp += '<span class="fas fa-ban"></span> Nonaktifkan</button>';                      
                        // }else if (parseInt(row.tax_flag) === 0) {
                        //   dsp += '&nbsp;<button class="btn btn_update_flag_tax btn-mini btn-primary" style="background-color:#6273df;"';
                        //   dsp += 'data-tax-id="'+data+'" data-tax-name="'+row.tax_name+'" data-tax-flag="'+row.tax_flag+'" data-tax-session="'+row.tax_session+'">';
                        //   dsp += '<span class="fas fa-check primary"></span> Aktifkan</button>';
                        // }

                       /* Button Action Concept 2 */
                       dsp += '&nbsp;<div class="btn-group">';
                       // dsp += '    <button class="btn btn-mini btn-default"><span class="fas fa-cog"></span></button>';
                       dsp += '    <button class="btn btn-mini btn-default dropdown-toggle btn-demo-space" data-toggle="dropdown" aria-expanded="true"><span class="fas fa-cog"></span><span class="caret"></span> </button>';
                       dsp += '    <ul class="dropdown-menu">';
                       dsp += '        <li>';
                       dsp += '            <a class="btn_edit_tax" style="cursor:pointer;"';
                       dsp += '                data-tax-id="'+data+'" data-tax-name="'+row.tax_name+'" data-tax-flag="'+row.tax_flag+'" data-tax-session="'+row.tax_session+'">';
                       dsp += '                <span class="fas fa-edit"></span> Edit';
                       dsp += '            </a>';
                       dsp += '        </li>';
                       // if(parseInt(row.tax_flag) === 0) {
                               dsp += '<li>'; 
                               dsp += '    <a class="btn_update_flag_tax" style="cursor:pointer;"';
                               dsp += '        data-tax-id="'+data+'" data-tax-name="'+row.tax_name+'" data-tax-flag="'+row.tax_flag+'" data-tax-session="'+row.tax_session+'">';
                               dsp += '        <span class="fas fa-lock"></span> Aktifkan';
                               dsp += '    </a>';
                               dsp += '</li>';
                       // }else if(parseInt(row.tax_flag) === 1){
                               dsp += '<li>';
                               dsp += '    <a class="btn_update_flag_tax" style="cursor:pointer;"';
                               dsp += '        data-tax-id="'+data+'" data-tax-name="'+row.tax_name+'" data-tax-flag="'+row.tax_flag+'" data-tax-session="'+row.tax_session+'">';
                               dsp += '        <span class="fas fa-ban"></span> Nonaktifkan';
                               dsp += '    </a>';
                               dsp += '</li>';
                       // }
                       if((parseInt(row.tax_flag) < 1) || (parseInt(row.tax_flag) == 4)) {
                               dsp += '<li>';
                               dsp += '    <a class="btn_update_flag_tax" style="cursor:pointer;"';
                               dsp += '        data-tax-id="'+data+'" data-tax-name="'+row.tax_name+'" data-tax-flag="4" data-tax-session="'+row.tax_session+'">';
                               dsp += '        <span class="fas fa-trash"></span> Hapus';
                               dsp += '    </a>';
                               dsp += '</li>';
                       }
                       dsp += '        <li class="divider"></li>';
                    //    dsp += '        <li>';
                    //    dsp += '            <a class="btn_print_tax" style="cursor:pointer;" data-tax="'+ data +'" data-tax-session="'+row.tax_session+'">';
                    //    dsp += '                <span class="fas fa-print"></span> Print';
                    //    dsp += '            </a>';
                    //    dsp += '        </li>';
                       dsp += '    </ul>';
                       dsp += '</div>';

                       /* Button Action Concept 2 */
                       dsp += '&nbsp;<div class="btn-group">';
                       // dsp += '    <button class="btn btn-mini btn-default" style="background-color:'+bgcolor+';color:'+color+'"><span class="'+icon+'"></span> '+label+'</button>';
                       dsp += '    <button class="btn btn-mini btn-default dropdown-toggle btn-demo-space" style="background-color:'+bgcolor+';color:'+color+';" data-toggle="dropdown" aria-expanded="true"><span class="'+icon+'"></span> '+label+' <span class="caret" style="color:'+color+'"></span> </button>';
                       dsp += '    <ul class="dropdown-menu">';
                       if(parseInt(row.tax_flag) == 1){
                               dsp += '<li>';
                               dsp += '    <a class="btn_update_flag_tax" style="cursor:pointer;"';
                               dsp += '        data-tax-id="'+data+'" data-tax-name="'+row.tax_name+'" data-tax-flag="'+row.tax_flag+'" data-tax-session="'+row.tax_session+'">';
                               dsp += '        <span class="fas fa-ban"></span> Nonaktifkan';
                               dsp += '    </a>';
                               dsp += '</li>';
                       }else if(parseInt(row.tax_flag) == 0) {
                               dsp += '<li>'; 
                               dsp += '    <a class="btn_update_flag_tax" style="cursor:pointer;"';
                               dsp += '        data-tax-id="'+data+'" data-tax-name="'+row.tax_name+'" data-tax-flag="'+row.tax_flag+'" data-tax-session="'+row.tax_session+'">';
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
    $("#table-data_filter").css('display','none');
    $("#table-data_length").css('display','none');
    $("#filter_length").on('change', function(e){
        var value = $(this).find(':selected').val(); 
        $('select[name="table-data_length"]').val(value).trigger('change');
        tax_table.ajax.reload();
    });
    $("#filter_flag").on('change', function(e){ tax_table.ajax.reload(); });
    $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ tax_table.ajax.reload(); }else if(parseInt(ln) < 1){ tax_table.ajax.reload();} });
    $('#table-data').on('page.dt', function () {
        var info = taxs.page.info();
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
    $(document).on("click","#btn_save_tax",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next =true;
        if($("#tax_name").val().length == 0){
            notifError('Nama wajib diisi');
            $("#tax_name").focus();
            next=false;
        }else if($("#tax_percent").val().length == 0){
            notifError('Persentase wajib diisi');
            $("#tax_percent").focus();
            next=false;
        }else{
            var form = new FormData($("#form-tax")[0]);
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
                        formtaxCancel();
                        tax_table.ajax.reload();
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
    $(document).on("click",".btn_edit_tax",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var id       = $(this).attr('data-tax-id');
        var session  = $(this).attr('data-tax-session');
        var name     = $(this).attr('data-tax-name');

        var form = new FormData();
        form.append('action', 'read');
        form.append('tax_id', id);
        form.append('tax_session', session);
        form.append('tax_name', name);
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
                    // $("#div-form-trans").show(300);
                    notif(d.status,d.message);
                    $("#tax_id").val(d.result.tax_id);
                    $("#tax_session").val(d.result.tax_session);
                    $("#tax_name").val(d.result.tax_name);
                    $("#tax_percent").val(d.result.tax_percent);                    
                    // $("#tax_flag").val(d.result.tax_flag).trigger('change');

                    $("#btn_new_tax").hide();
                    $("#btn_save_tax").hide();
                    $("#btn_update_tax").show();
                    $("#btn_cancel_tax").show();
                    // scrollUp('content');
                    formtaxSetDisplay(0);
                }else{
                    // $("#div-form-trans").hide(300);
                    notif(d.status,d.message);
                }
            },
            error:function(xhr, Status, err){
                notif(0,d.message);
            }
        });
    });
    $(document).on("click","#btn_update_tax",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next =true;
        var id = $("#tax_id").val();
        var session = $("#tax_session").val();
        if(parseInt(id) > 0){
            if($("#tax_name").val().length == 0){
                notifError('tax_NAME wajib diisi');
                $("#tax_name").focus();
                next=false;
            }else if($("#tax_percent").val().length == 0){
                notifError('Persentase wajib diisi');
                $("#tax_percent").focus();
                next=false;
            }else{
                var form = new FormData($("#form-tax")[0]);
                form.append('action', 'update');
                form.append('tax_id', id);
                form.append('tax_session', session);
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
                            formtaxCancel();
                            notif(s,m);
                            tax_table.ajax.reload(null,false);
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
            notifError('Data belum dibuka');
        }
    });
    $(document).on("click",".btn_delete_tax",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next     = true;
        var id       = $(this).attr('data-tax-id');
        var session  = $(this).attr('data-tax-session');
        var name     = $(this).attr('data-tax-name');

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
                        form.append('tax_id', id);
                        form.append('tax_session', session);
                        form.append('tax_name', name);
                        form.append('tax_flag', 4);

                        $.ajax({
                            type: "POST",
                            url : url+"?action=delete",
                            data: form,
                            dataType:'json',
                            cache: false,
                            contentType: false,
                            processData: false,
                            success:function(d){
                                if(parseInt(d.status)==1){ 
                                    notifSuccess(d.message); 
                                    tax_table.ajax.reload(null,false);
                                }else{ 
                                    notifError(d.message); 
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

    $(document).on("click",".btn_update_flag_tax",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next     = true;
        var id       = $(this).attr('data-tax-id');
        var session  = $(this).attr('data-tax-session');
        var name     = $(this).attr('data-tax-name');
        var flag     = $(this).attr('data-tax-flag');

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
                        form.append('action', 'update-flag');
                        form.append('tax_id', id);
                        form.append('tax_session', session);
                        form.append('tax_name', name);
                        form.append('tax_flag', set_flag);

                        $.ajax({
                            type: "POST",
                            url : url,
                            data: form,
                            dataType:'json',
                            cache: false,
                            contentType: false,
                            processData: false,
                            success:function(d){
                                notif(d.status, d.message); 
                                if(parseInt(d.status)==1){ 
                                    tax_table.ajax.reload(null,false);
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
    $(document).on("click","#btn_new_tax",function(e) {
        formtaxNew();
    });
    $(document).on("click","#btn_cancel_tax",function(e) {
        formtaxCancel();
    });
    $(document).on("click",".btn_print_tax",function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log($(this));
        var id = $(this).attr('data-tax-id');
        var session = $(this).attr('data-tax-session');
        if(parseInt(id) > 0){
            var x = screen.width / 2 - 700 / 2;
            var y = screen.height / 2 - 450 / 2;
            var print_url = url_print+'?action=print&data='+session;
            var win = window.open(print_url,'Print','width=700,height=485,left=' + x + ',top=' + y + '').print();
        }else{
            notifError('Dokumen belum di buka');
        }
    });
    $(document).on("click","#btn_export_tax",function(e) {
        e.stopPropagation();
        $.alert('Fungsi belum dibuat');
    });
    $(document).on("click","#btn_print_tax",function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log($(this));
        // var id = $(this).attr('data-tax-id');
        $.alert('Fungsi belum dibuat');
    });
});

function formtaxNew(){
    formtaxSetDisplay(0);  
    $("#div-form-trans").show(300);
  
    $("#form-tax input").val('');

    $("#btn_new_tax").hide();
    $("#btn_save_tax").show();
    $("#btn_cancel_tax").show();
}
function formtaxCancel(){
    formtaxSetDisplay(1);
    $("#form-tax input").val('');
    $("#form-tax textarea").val('');
  
    $("#btn_new_tax").css('display','inline');
    $("#btn_save_tax").hide();
    $("#btn_update_tax").hide();
    $("#btn_cancel_tax").hide();
    // $("#div-form-trans").hide(300);
} 
function formtaxSetDisplay(value){ // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
    if(value == 1){ var flag = true; }else{ var flag = false; }
    //Attr Input yang perlu di setel
    var form = '#form-tax'; 
    var attrInput = [
       "tax_name","tax_percent"
    ];
    for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag); }

    //Attr Textarea yang perlu di setel
    var attrText = [
    ];
    for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

    //Attr Select yang perlu di setel
    var atributSelect = [
       "tax_flag"
    ];
    for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag); }      
}
</script>