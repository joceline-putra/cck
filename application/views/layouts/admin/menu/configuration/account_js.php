<script>
    $(document).ready(function () {
        // $("#modal-account").modal('toggle');
        var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="configuration/account"]').addClass('active');

        var url = "<?= base_url('Konfigurasi/manage'); ?>";
        var id_master = 0;
        var url_print_all = "<?= base_url('configuration/account/print'); ?>";    

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
         const autoNumericOption = {
         digitGroupSeparator : '.', 
         decimalCharacter  : ',', 
         decimalCharacterAlternative: ',', 
         decimalPlaces: 0,
         watchExternalChanges: true //!!!        
         };
         new AutoNumeric('#harga_jual', autoNumericOption);  
         */
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
                    // d.length = $("#filter_length").find(':selected').val();
                    d.length = $("#table-data_length").find(':selected').val();
                    d.search = {
                        value: $("#filter_search").val()
                    };
                    d.account_group = $("#filter_account_group").find(':selected').val();
                    d.account_group_sub = $("#filter_account_group_sub").find(':selected').val();
                    d.account_flag = $("#filter_account_flag").find(':selected').val();
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "columnDefs": [
                {"targets": 0, "title": "Kode", "searchable": true, "orderable": true, "width":"10%"},
                {"targets": 1, "title": "Nama", "searchable": true, "orderable": true, "width":"30%"},
                {"targets": 2, "title": "Group", "searchable": true, "orderable": true, "width":"10%"},
                {"targets": 3, "title": "Sub Group", "searchable": true, "orderable": true, "width":"30%"},
                {"targets": 4, "title": "Action", "searchable": false, "orderable": false, "width":"10%"}
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [
                {'data': 'account_code',
                    render: function (data, meta, row) {
                        var dsp = '';

                        if (parseInt(row.account_parent_id) > 0) {
                            dsp = '&nbsp;&nbsp;&nbsp;' + row.account_code;
                        } else {
                            dsp = '<b>' + row.account_code + '</b>';
                        }

                        if (parseInt(row.account_locked) > 0) {
                            dsp += '&nbsp;<i class="fas fa-lock"></i>';
                        } else {
                            // dsp += '&nbsp;<i class="fas fa-lock-open"></i>';
                        }
                        return dsp;
                    }
                },
                {'data': 'account_name',
                    render: function (data, meta, row) {
                        var dsp = '';
                        if (parseInt(row.account_parent_id) > 0) {
                            dsp = '&nbsp;&nbsp;&nbsp;' + row.account_name;
                        } else {
                            dsp = '<b>' + row.account_name + '</b>';
                        }
                        return dsp;
                    }
                },
                {'data': 'account_group', className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';

                        if (parseInt(row.account_group) === 1) {
                            dsp += 'Asset';
                        } else if (parseInt(row.account_group) === 2) {
                            dsp += 'Liabilitas';
                        } else if (parseInt(row.account_group) === 3) {
                            dsp += 'Ekuitas';
                        } else if (parseInt(row.account_group) === 4) {
                            dsp += 'Pendapatan';
                        } else if (parseInt(row.account_group) === 5) {
                            dsp += 'Biaya';
                        } else {
                            dsp += '-';
                        }
                        return dsp;
                    }
                },
                {'data': 'account_group_sub_name'
                },
                {'data': 'account_id', className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="' + data + '">';
                        dsp += '<span class="fas fa-edit"></span>Edit';
                        dsp += '</button>';
                        if(parseInt(row.account_flag) < 4){
                            if (parseInt(row.account_flag) === 1) {
                                dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-success"';
                                dsp += 'data-nama="' + row.account_name + '" data-kode="' + row.account_code + '" data-id="' + data + '" data-flag="' + row.account_flag + '">';
                                dsp += '<span class="fas fa-check-square primary"></span> Aktif</button>';
                            } else {
                                dsp += '&nbsp;<button class="btn btn-set-active btn-mini" style="background-color:#fe882b;color:white;"';
                                dsp += 'data-nama="' + row.account_name + '" data-kode="' + row.account_code + '" data-id="' + data + '" data-flag="' + row.account_flag + '">';
                                dsp += '<span class="fas fa-times danger"></span> Nonaktif</button>';

                                dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
                                dsp += 'data-nama="' + row.account_name + '" data-kode="' + row.account_code + '" data-id="' + data + '" data-flag="4">';
                                dsp += '<span class="fas fa-trash danger"></span> Hapus</button>';                                
                            }
                        }else{
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
                            dsp += 'data-nama="' + row.account_name + '" data-kode="' + row.account_code + '" data-id="' + data + '" data-flag="1">';
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
            if (parseInt(ln) > 2) {
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
            $("#table-data-in").attr('data-limit-start', limit_start);
            $("#table-data-in").attr('data-limit-end', limit_end);
        });
        $("#filter_account_group").on('change', function (e) {
            index.ajax.reload();
        });
        $("#filter_account_group_sub").on('change', function (e) {
            index.ajax.reload();
        });
        $("#filter_account_flag").on('change', function (e) {
            index.ajax.reload();
        });

        // New Button
        $(document).on("click", "#btn-new", function (e) {
            formNew();
            $("#modal-account").modal('toggle');
        });

        // Cancel Button
        $(document).on("click", "#btn-cancel", function (e) {
            formCancel();
            $("#modal-account").modal('toggle');
        });

        // Save Button
        $(document).on("click", "#btn-save", function (e) {
            e.preventDefault();
            var next = true;

            var kode = $("#form-master input[name='kode']");
            var nama = $("#form-master input[name='nama']");

            if (next == true) {
                if ($("input[id='kode']").val().length == 0) {
                    notif(0, 'Kode wajib diisi');
                    $("#kode").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='nama']").val().length == 0) {
                    notif(0, 'Nama wajib diisi');
                    $("#nama").focus();
                    next = false;
                }
            }

            // if (next == true) {
            //     if ($("select[id='group']").find(':selected').val() == 0) {
            //         notif(0, 'Group Akun wajib dipilih');
            //         next = false;
            //     }
            // }

            // if (next == true) {
            //     if ($("select[id='group_sub']").find(':selected').val() == 0) {
            //         notif(0, 'Group Sub Akun wajib dipilih');
            //         next = false;
            //     }
            // }

            if (next == true) {
                var prepare = {
                    tipe: identity,
                    nama: $("input[id='nama']").val(),
                    kode: $("input[id='kode']").val(),
                    // group: $("select[id='group']").find(':selected').val(),
                    // group_sub: $("select[id='group_sub']").find(':selected').val(),
                    group: 5,
                    group_sub: 16,                    
                    group_sub_name: "Beban",
                    group_sub_name: $("select[id='group_sub']").find(':selected').text(),
                    // status: $("select[id='status']").find(':selected').val(),
                    status:$("input[name=status]:checked").val(),
                    // account_locked: $("select[id='account_locked']").find(':selected').val(),
                    // account_locked:$("input[name=account_locked]:checked").val(),
                    account_locked:0,                    
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
                        // $("#form-master input[name='id_document']").val(d.result.account_id);
                        $("#form-master select[name='group']").val(d.result.account_group).trigger('change');
                        $("#form-master select[name='group_sub']").val(d.result.account_group_sub).trigger('change');
                        $("#form-master input[name='kode']").val(d.result.account_code);
                        $("#form-master input[name='nama']").val(d.result.account_name);
                        // $("#form-master select[name='status']").val(d.result.account_flag).trigger('change');
                        // $("#form-master select[name='account_locked']").val(d.result.account_locked).trigger('change');

                        // $("input[name=status]").removeAttr('checked');
                        // $("input[name=account_locked]").removeAttr('checked');                        
                        $("input[name=status][value="+parseInt(d.result.account_flag)+"]").attr('checked', 'checked');
                        $("input[name=account_locked][value="+parseInt(d.result.account_locked)+"]").attr('checked', 'checked');                        

                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-cancel").show();
                        $("#modal-account").modal('toggle');

                        id_master = d.result.account_id;
                    } else {
                        notif(0, d.message);
                        id_master = 0;
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
            // var id = $("#form-master input[name='id_dokumen']").val();
            var id = id_master;
            var kode = $("#form-master input[name='kode']");
            var nama = $("#form-master input[name='nama']");

            if (id == '') {
                notif(0, 'ID tidak ditemukan');
                next = false;
            }

            if (kode.val().length == 0) {
                notif(0, 'Kode wajib diisi');
                kode.focus();
                next = false;
            }

            if (nama.val().length == 0) {
                notif(0, 'Nama wajib diisi');
                nama.focus();
                next = false;
            }

            if (next == true) {
                if ($("select[id='group']").find(':selected').val() == 0) {
                    notif(0, 'Group Akun wajib dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='group_sub']").find(':selected').val() == 0) {
                    notif(0, 'Group Sub Akun wajib dipilih');
                    next = false;
                }
            }
            if (next == true) {
                var prepare = {
                    tipe: identity,
                    // id:$("input[id=id_document]").val(),
                    id: id_master,
                    kode: $("input[id='kode']").val(),
                    nama: $("input[id='nama']").val(),
                    // group: $("select[id='group']").find(':selected').val(),
                    // group_sub: $("select[id='group_sub']").find(':selected').val(),
                    // group_sub_name: $("select[id='group_sub']").find(':selected').text(),
                    group: 5,
                    group_sub: 16,                    
                    group_sub_name: "Beban",
                    group_sub_name: $("select[id='group_sub']").find(':selected').text(),                    
                    // status: $("select[id='status']").find(':selected').val(),
                    status:$("input[name=status]:checked").val(),
                    // account_locked: $("select[id='account_locked']").find(':selected').val(),
                    // account_locked:$("input[name=account_locked]:checked").val(),
                    account_locked:0,                                      
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
                            // $("#btn-new").show();
                            $("#btn-save").hide();
                            $("#btn-update").hide();
                            $("#btn-cancel").hide();
                            $("#form-master input").val();
                            formMasterSetDisplay(1);
                            notif(1, d.message);
                            index.ajax.reload(null, false);
                            $("#modal-account").modal('toggle');
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
                                action: 'remove',
                                id: id
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: "json",
                                success: function (d) {
                                    if (parseInt(d.status) = 1) {
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
            } else if (flag == 0){
                var set_flag = 1;
                var msg = 'aktifkan';
            } else if (flag == 4) {
                var set_flag = 4;
                var msg = 'menghapus';
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

        $(document).on("click", "#btn-print-all", function () {
            let alias1 = 'Akun Perkiraan';
            let title   = 'Print Data '+alias1;
            $.confirm({
                title: title,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
                autoClose: 'button_2|100000',
                closeIcon: true, closeIconClass: 'fas fa-times', 
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content: function(){           
                },
                onContentReady: function(e){
                    let self    = this;
                    let d = self.ajaxResponse.data;
                    let content = '';
                    let dsp     = '';
                    
                    dsp += '<form id="jc_form">';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Group '+alias1+'</label>';
                        dsp += '        <select id="filter_group2" name="filter_group2" class="form-control">';
                        dsp += '            <option value="0">Semua</option>';
                        dsp += '            <option value="1">[1] Asset / Harta</option>';
                        dsp += '            <option value="2">[2] Liabilitas / Kewajiban</option>';
                        dsp += '            <option value="3">[3] Ekuitas / Modal</option>';
                        dsp += '            <option value="4">[4] Pendapatan / HPP</option>';
                        dsp += '            <option value="5">[5] Cost / Biaya</option>';                                                                                                                        
                        dsp += '        </select>';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Group Sub '+alias1+'</label>';
                        dsp += '        <select id="filter_group_sub2" name="filter_group_sub2" class="form-control">';
                        dsp += '            <option value="0">Semua</option>';
                        dsp += '            <option value="1">Akun Piutang</option>';
                        dsp += '            <option value="2">Aktiva Lancar Lainnya</option>';
                        dsp += '            <option value="3">Kas & Bank</option>';
                        dsp += '            <option value="4">Persediaan</option>';
                        dsp += '            <option value="5">Aktiva Tetap</option>';
                        dsp += '            <option value="6">Aktiva Lainnya</option>';
                        dsp += '            <option value="7">Depresiasi & Amortisasi</option>';
                        dsp += '            <option value="8">Akun Hutang</option>';
                        dsp += '            <option value="10">Kewajiban Lancar Lainnya</option>';
                        dsp += '            <option value="12">Ekuitas</option>';
                        dsp += '            <option value="13">Pendapatan</option>';
                        dsp += '            <option value="14">Pendapatan Lainnya</option>';
                        dsp += '            <option value="15">Harga Pokok Penjualan</option>';
                        dsp += '            <option value="16">Beban</option>';
                        dsp += '            <option value="17">Beban Lainnya</option>';                                                                                                                                         
                        dsp += '        </select>';
                        dsp += '    </div>';
                        dsp += '</div>';         
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Status '+alias1+'</label>';
                        dsp += '        <select id="filter_flag2" name="filter_flag2" class="form-control">';
                        dsp += '            <option value="a">Semua</option>';
                        dsp += '            <option value="1">Aktif</option>';
                        dsp += '            <option value="0">Nonaktif</option>';
                        dsp += '            <option value="4">Terhapus</option>';                                                                        
                        dsp += '        </select>';
                        dsp += '    </div>';
                        dsp += '</div>';                                                                                                         
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-left">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Urut Berdasarkan</label>';
                            dsp += '        <select id="filter_order2" name="filter_order2" class="form-control">';
                            dsp += '            <option value="1">Kode '+alias1+'</option>';
                            dsp += '            <option value="2">Nama '+alias1+'</option>';
                            dsp += '            <option value="3">Group '+alias1+'</option>';                                                                                                                  
                            dsp += '        </select>';
                            dsp += '    </div>';
                            dsp += '</div>';
                            dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-side">';
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
                },
                buttons: {
                    button_1: {
                        text:'Print',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){
                            let self      = this;
                            let filter_order     = self.$content.find('#filter_order2').val();
                            let filter_dir       = self.$content.find('#filter_dir2').val(); 
                            let filter_group     = self.$content.find('#filter_group2').val();
                            let filter_group_sub = self.$content.find('#filter_group_sub2').val();   
                            let filter_flag      = self.$content.find('#filter_flag2').find(':selected').val();                                                                                                                
                            
                            if(filter_order == 0){
                                $.alert('Urut mohon dipilih dahulu');
                                return false;
                            } else{
                                var request = $('.btn-print-all').data('request');
                                var p = url_print_all + '/?group=' + filter_group;
                                    p += '&group_sub=' + filter_group_sub + '&flag=' + filter_flag;
                                    p += '&start=0&limit=0'; 
                                    p += '&order=' + filter_order + '&dir=' + filter_dir;
                                    p += '&image=0';
                                var win = window.open(p,'_blank');
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
    });

    function formNew() {
        formMasterSetDisplay(0);
        $("input[name=status][value=1]").attr('checked', 'checked');
        $("input[name=account_locked][value=0]").attr('checked', 'checked');        
        $("#form-master input").val();
        // $("#btn-new").hide();
        $("#btn-save").show();
        $("#btn-update").hide();
        $("#btn-cancel").show();
    }
    function formCancel() {
        formMasterSetDisplay(1);
        $("input[name=status][value=1]").attr('checked', 'checked');
        $("input[name=account_locked][value=0]").attr('checked', 'checked');                
        $("#form-master input").val();
        // $("#btn-new").show();
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
            "kode",
            "nama",
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
            "group",
            "group_sub",
            "account_locked"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>