
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    let url = "<?php base_url('user/group'); ?>";

    let groupId = 0;

    $(".nav-tabs").find('li[class="active"]').removeClass('active');
    $(".nav-tabs").find('li[data-name="user/group"]').addClass('active');

    $(function() {

    });

    //Datatable
    let group_table = $("#table_group").DataTable({
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
            {"targets":0, "width":"10%", "title":"ID", "searchable":true, "orderable":true},
            {"targets":1, "width":"70%", "title":"Group Name", "searchable":true, "orderable":true},
            {"targets":2, "width":"20%", "title":"Flag", "searchable":true, "orderable":true},
        ],
        "order": [[0, 'ASC']],
        "columns": [
            {
                'data': 'user_group_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += row.user_group_id;
                    return dsp;
                }
            },{
                'data': 'user_group_name',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = '';
                    dsp += row.user_group_name;
                    // if(row.contact_email_1 != undefined){
                        // dsp += '<br>'+row.contact_email_1;
                    // }
                    return dsp;
                }
            },{
                'data': 'user_group_id',
                className: 'text-left',
                render: function(data, meta, row) {
                    var dsp = ''; var label = 'Error Status'; var icon = 'fas fa-cog'; var color = 'white'; var bgcolor = '#d1dade';
                    if(parseInt(row.user_group_flag) == 1){
                    //  dsp += '<label style="color:#6273df;">Aktif</label>';
                       label = 'Aktif';
                       icon = 'fas fa-lock';
                       bgcolor = '#0aa699';
                    }else if(parseInt(row.user_group_flag) == 4){
                       //  dsp += '<label style="color:#ff194f;">Terhapus</label>';
                       label = 'Terhapus';
                       icon = 'fas fa-trash';
                       bgcolor = '#f35958';
                    }else if(parseInt(row.user_group_flag) == 0){
                       //   dsp += '<label style="color:#ff9019;">Nonaktif</label>';
                       label = 'Nonaktif';
                       icon = 'fas fa-unlock';
                       // color = 'green';
                       bgcolor = '#ff9019';
                    }

                        /* Button Action Concept 1 */
                        dsp += '&nbsp;<button class="btn btn_edit_group btn-mini btn-primary"';
                        dsp += 'data-group-id="'+data+'" data-group-name="'+row.user_group_name+'" data-group-flag="'+row.user_group_flag+'">';
                        dsp += '<span class="fas fa-edit primary"></span> Edit</button>';

                        dsp += '&nbsp;<button class="btn btn_delete_group btn-mini btn-danger"';
                        dsp += 'data-group-id="'+data+'" data-group-name="'+row.user_group_name+'" data-group-flag="4">';
                        dsp += '<span class="fas fa-trash danger"></span> Hapus</button>';
                    return dsp;
                }
            }
        ]
    });
    $("#table_group_filter").css('display','none');
    $("#table_group_length").css('display','none');
    $("#filter_length").on('change', function(e){
        var value = $(this).find(':selected').val(); 
        $('select[name="table_group_length"]').val(value).trigger('change');
        group_table.ajax.reload();
    });
    $("#filter_flag").on('change', function(e){ group_table.ajax.reload(); });
    $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ group_table.ajax.reload(); }else if(parseInt(ln) < 1){ group_table.ajax.reload();} });
    $('#table_group').on('page.dt', function () {
        var info = groups.page.info();
        var limit_start = info.start;
        var limit_end = info.end;
        var length = info.length;
        var page = info.page;
        var pages = info.pages;
        // console.log( 'Showing page: '+info.page+' of '+info.pages);
        // console.log(limit_start,limit_end);
        $("#table_group-in").attr('data-limit-start',limit_start);
        $("#table_group-in").attr('data-limit-end',limit_end);
    });

    //CRUD
    $(document).on("click","#btn_save_group",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next =true;
        if($("#group_name").val().length == 0){
            notif(0,'group_NAME wajib diisi');
            $("#group_name").focus();
            next=false;
        }else{
            var form = new FormData($("#form_group")[0]);
            form.append('action', 'create_update');
            form.append('group_id', groupId);
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
                        formGroupReset();
                        $("#modal_group").modal("hide");                        
                        /* hint zz_for or zz_each */
                        group_table.ajax.reload();
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
    $(document).on("click",".btn_edit_group",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var id       = $(this).attr('data-group-id');
        var name     = $(this).attr('data-group-name');

        var form = new FormData();
        form.append('action', 'read');
        form.append('group_id', id);
        form.append('group_name', name);
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
                    groupId = d.result.user_group_id;
                    $("#group_name").val(d.result.user_group_name);
                    formGroupSetDisplay(0);
                    $("#modal_group").modal('show');
                    //loadGroupItem(r.group_id);
                    //formGroupItemSetDisplay(0);
                }else{
                    notif(0,d.message);
                }
            },
            error:function(xhr, Status, err){
                notif(0,'Error');
            }
        });
    });
    $(document).on("click",".btn_delete_group",function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        var next     = true;
        var id       = $(this).attr('data-group-id');
        var name     = $(this).attr('data-group-name');

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
                        form.append('group_id', id);
                        form.append('group_name', name);
                        form.append('group_flag', 4);

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
                                    group_table.ajax.reload(null,false);
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
    $(document).on("click","#btn_new_group",function(e) {
        formGroupReset();
        formGroupSetDisplay(0);
        $("#modal_group").modal('show');
    });
    $(document).on("click","#btn_cancel_group",function(e) {
        formGroupReset();
        formGroupSetDIsplay(1);
        $("#modal_group").modal('hide');
        groupId = 0;
    });
    function formGroupReset(){
        $("#form_group input")
        .not("input[id='group_hour']")
        .not("input[id='group_date']")
        .not("input[id='group_date_start']")
        .not("input[id='group_date_end']").val('');
        $("#form_group textarea").val('');

    } 
}); //End of Document Ready
function formGroupSetDisplay(value){ // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
    if(value == 1){ var flag = true; }else{ var flag = false; }
    //Attr Input yang perlu di setel
    var form = '#form_group'; 
    var attrInput = [
       "group_name"
    ];
    for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag); }

    //Attr Textarea yang perlu di setel
    var attrText = [
       "group_note"
    ];
    for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

    //Attr Select yang perlu di setel
    var atributSelect = [
       "group_flag",
       "group_type",
    ];
    for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag); }
}
</script>