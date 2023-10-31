<script>
    $(document).ready(function () {
        var identity = "<?php echo $identity; ?>";
        var url = "<?= base_url('konfigurasi/manage'); ?>";

        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="configuration/menu"]').addClass('active');

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
        // $("#modal-menu").modal('toggle');
        var index = $("#table-data").DataTable({
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load';
                    d.tipe = identity;
                    d.length = $("#filter_length").find(':selected').val();
                    d.search = {
                        value: $("#filter_search").val()
                    };
                    d.filter_parent = $("#filter_parent").find(':selected').val();
                    d.filter_flag = $("#filter_flag").find(':selected').val();
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": "Parent Menu", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Child Menu", "searchable": true, "orderable": true},
                {"targets": 2, "title": "Link", "searchable": true, "orderable": true},
                {"targets": 3, "title": "Action", "searchable": false, "orderable": false},
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [
                {'data': 'parent_id',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<span class="' + row.parent_icon + '"></span> ' + row.parent_name;
                        return dsp;
                    }
                },
                {'data': 'child_name',
                    render: function (data, meta, row) {
                        var dsp = '';

                        // if (parseInt(row.menu_parent_id) == 0) {

                        // }else{ 
                        // dsp += '&nbsp;&nbsp;&nbsp;&nbsp-&nbsp;'+row.menu_name;
                        // }
                        dsp += '<span class="' + row.child_icon + '"></span> ' + row.child_name;
                        
                        // dsp += row.child_name;
                        return dsp;
                    }
                },
                {'data': 'child_link',
                    render: function (data, meta, row) {
                        var dsp = '';
                        var site = "<?php echo site_url(); ?>" + row.child_link;
                        dsp += '<a href="#" class="btn-visit" data-url="' + site + '">' + row.child_link + '</a>';
                        return dsp;
                    }
                },
                {'data': 'child_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // disp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';

                        dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="' + data + '">';
                        dsp += '<span class="fas fa-edit"></span>Edit';
                        dsp += '</button>';

                        if(parseInt(row.child_flag) < 4){
                            if (parseInt(row.child_flag) === 1) {
                                dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-success"';
                                dsp += 'data-nama="' + row.child_name + '" data-link="' + row.child_link + '" data-id="' + data + '" data-flag="' + row.child_flag + '">';
                                dsp += '<span class="fas fa-check-square primary"></span> Aktif</button>';
                            } else {
                                dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-warning"';
                                dsp += 'data-nama="' + row.child_name + '" data-link="' + row.child_link + '" data-id="' + data + '" data-flag="' + row.child_flag + '">';
                                dsp += '<span class="fas fa-times warning"></span> Nonaktif</button>';
                            
                                dsp += '&nbsp;<button class="btn btn-delete btn-mini btn-danger"';
                                dsp += 'data-nama="' + row.child_name + '" data-link="' + row.child_link + '" data-id="' + data + '" data-flag="4">';
                                dsp += '<span class="fas fa-trash danger"></span> Hapus</button>';
                            }
                        }else{
                            dsp += '&nbsp;<button class="btn btn-delete btn-mini btn-danger"';
                            dsp += 'data-nama="' + row.child_name + '" data-link="' + row.child_link + '" data-id="' + data + '" data-flag="1">';
                            dsp += '<span class="fas fa-trash danger"></span> Terhapus</button>';                          
                        }

                        return dsp;
                    }
                }
            ]
        });
        //Datatable Config
        $("#table-data_filter").css('display', 'none');
        $("#table-data_length").css('display', 'none');
        $("#filter_length").on('change', function (e) {
            var value = $(this).find(':selected').val();
            $('select[name="table-data_length"]').val(value).trigger('change');
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
            // console.log( 'Showing page: '+info.page+' of '+info.pages);
            // console.log(limit_start,limit_end);
            // $("#table-data-in").attr('data-limit-start',limit_start);
            // $("#table-data-in").attr('data-limit-end',limit_end);
        });

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
                        source: 'menus',
                        parent: 0
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
                return data.text;
            }
        });
        $('#filter_parent').select2({
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
                        source: 'menus',
                        parent: 0
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
                return data.text;
            }
        });
        $("#filter_parent, #filter_flag").on('change', function (e) {
            index.ajax.reload();
        });

        $('input:radio[name="parent"]').on('change', function(e){
            var v = $(this).val();
            $("input[name=parent][value!='"+v+"']").removeAttr('checked');
            $("input[name=parent][value='"+v+"'").attr('checked', 'checked');
            if(v == 0){
                $("#group").val(0).trigger('change');
                $("#group").attr('disabled',true);                
            }else{
                $("#group").removeAttr('disabled');                
            }
        });

        // New Button
        $(document).on("click", "#btn-new", function (e) {
            formNew();
            $("#modal-menu").modal('toggle');            
        });
        // Cancel Button
        $(document).on("click", "#btn-cancel", function (e) {
            e.preventDefault();
            formCancel();
            $("#modal-menu").modal('toggle');              
        });

        // Save Button
        $(document).on("click", "#btn-save", function (e) {
            e.preventDefault();
            var next = true;

            var nama = $("#form-master input[name='nama']");
            var link = $("#form-master input[name='link']");
            var parent = $("input[name=parent]:checked").val();

            if (next == true) {
                if ($("input[id='nama']").val().length == 0) {
                    notif(0, 'Nama wajib diisi');
                    $("#nama").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='link']").val().length == 0) {
                    notif(0, 'Link wajib diisi');
                    $("#link").focus();
                    next = false;
                }
            }

            if (next == true) {
                if(parent == 1){
                    if ($("select[id='group']").find(':selected').val() == 0) {
                        notif(0, 'Parent wajib dipilih');
                        next = false;
                    }
                }
            }

            if (next == true) {
                var prepare = {
                    tipe: identity,
                    nama: $("input[id='nama']").val(),
                    link: $("input[id='link']").val(),
                    icon: $("input[id='icon']").val(),
                    group: $("select[id='group']").find(':selected').val(),
                    status:$("input[name=status]:checked").val(),
                    parent:$("input[name=parent]:checked").val()                    
                }
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'create',
                    data: prepare_data
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
                            notif(1, d.message);
                            $("#modal-menu").modal('toggle');
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
            e.preventDefault();
            var id = $(this).data("id");
            var data = {
                action: 'read',
                tipe: identity,
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
                        // notif(1,d.result.id);
                        $("#form-master input[name='id_document']").val(d.result.menu_id);
                        $("#form-master input[name='nama']").val(d.result.menu_name);
                        $("#form-master input[name='link']").val(d.result.menu_link);
                        $("#form-master input[name='icon']").val(d.result.menu_icon);                        
                        // $("#form-master select[name='status']").val(d.result.menu_flag).trigger('change');

                        // $("#form-master select[name='group']").val(d.result.menu_parent_id).trigger('change');
                        if(parseInt(d.result.menu_parent_id) > 0){
                            $("select[id='group']").append('' +
                                    '<option value="' + d.parent.menu_id + '">' +
                                    d.parent.menu_name +
                                    '</option>');
                            $("select[id='group']").val(d.parent.menu_id).trigger('change');
                            $("input[name=parent][value=1]").attr('checked', 'checked');
                        }else{
                            $("select[id='group']").val(0).trigger('change');
                            $("input[name=parent][value=0]").attr('checked', 'checked');                        
                        }

                        $("input[name=status][value="+parseInt(d.result.menu_flag)+"]").attr('checked', 'checked');

                        $("#btn-new").hide();
                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-cancel").show();
                        // scrollUp('content');
                        $("#modal-menu").modal('toggle');
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
            var id = $("#form-master input[name='id_dokumen']").val();
            var nama = $("#form-master input[name='nama']");
            var link = $("#form-master input[name='link']");
            var parent = $("input[name=parent]:checked").val();
            
            if (id == '') {
                notif(0, 'ID tidak ditemukan');
                next = false;
            }

            if (nama.val().length == 0) {
                notif(0, 'Nama wajib diisi');
                nama.focus();
                next = false;
            }

            if (link.val().length == 0) {
                notif(0, 'Link wajib diisi');
                link.focus();
                next = false;
            }

            if (next == true) {
                if(parent == 1){
                    if ($("select[id='group']").find(':selected').val() == 0) {
                        notif(0, 'Parent wajib dipilih');
                        next = false;
                    }
                }
            }

            if (next == true) {
                var prepare = {
                    tipe: identity,
                    id: $("input[id=id_document]").val(),
                    kode: $("input[id='kode']").val(),
                    nama: $("input[id='nama']").val(),
                    link: $("input[id='link']").val(),
                    icon: $("input[id='icon']").val(),
                    group: $("select[id='group']").find(':selected').val(),
                    status:$("input[name=status]:checked").val(),
                    parent:$("input[name=parent]:checked").val()                        
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
                            $("#btn-save").hide();
                            // $("#btn-update").hide();
                            // $("#btn-cancel").hide();
                            $("#form-master input").val();
                            formMasterSetDisplay(1);
                            notif(1, d.message);
                            index.ajax.reload(null, false);
                            $("#modal-menu").modal('toggle');
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

        // Delete Button
        $(document).on("click", ".btn-delete", function () {
            event.preventDefault();
            var id = $(this).attr("data-id");
            var kode = $(this).attr("data-kode");
            var nama = $(this).attr("data-nama");
            $.confirm({
                title: 'Hapus!',
                content: 'Apakah anda ingin menghapus <b>' + nama + '</b> ?',
                buttons: {
                    confirm: {
                        btnClass: 'btn-danger',
                        text: 'Ya',
                        action: function () {
                            var data = {
                                action: 'set-active', //delete
                                kode:kode,
                                tipe: identity,
                                id: id,
                                flag:4,
                                nama: nama
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: "json",
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        index.ajax.reload(null, false);
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
                                tipe: identity,
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

        // Btn Visit
        $(document).on("click", ".btn-visit", function (e) {
            var url = $(this).attr('data-url');
            $.confirm({
                title: 'Pengalihan Halaman',
                content: 'Anda akan diarahkan ke halaman ini <b>' + url + '</b>',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                autoClose: 'button_2|10000',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                animation: 'zoom',
                closeAnimation: 'bottom',
                animateFromElement: false,
                buttons: {
                    button_1: {
                        text: 'Ok',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function () {
                            window.open(url, "_blank");
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
    });

    function formNew() {
        formMasterSetDisplay(0);
        $("input[name=status][value=1]").attr('checked', 'checked');
        $("input[name=parent][value=0]").attr('checked', 'checked');        
        $("#form-master input").val();
        // $("#btn-new").hide();
        $("#btn-save").show();
        $("#btn-cancel").show();
    }
    function formCancel() {
        formMasterSetDisplay(1);
        $("#form-master input").val();
        // $("#btn-new").css('display', 'inline');
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
            "nama",
            "link",
            "icon"
        ];
        //$("input[name='kode']").val(0);

        for (var i = 0; i <= attrInput.length; i++) {
            $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        }

        //Attr Textarea yang perlu di setel
        var attrText = [
            // "keterangan"
        ];
        for (var i = 0; i <= attrText.length; i++) {
            $("" + form + " textarea[name='" + attrText[i] + "']").attr('readonly', flag);
        }

        //Attr Select yang perlu di setel
        var atributSelect = [
            "status",
            "group"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>