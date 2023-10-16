<script>
    $(document).ready(function () {
        var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="product/warehouse"]').addClass('active');

        // console.log(identity);
        var url = "<?= base_url('konfigurasi/manage'); ?>";
        // $("select").select2();
        $(".date").datepicker({
            // defaultDate: new Date(),
            format: 'yyyy-mm-dd',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        });
        $('#user').select2({
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
                        source: 'users'
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
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                return datas.nama;
            },
            templateSelection: function (data, container) {
                // Add custom attributes to the <option> tag for the selected option
                // $(data.element).attr('data-custom-attribute', data.customValue);
                // $("input[name='satuan']").val(data.satuan);
                return data.nama;
            }
        });

        var index = $("#table-data").DataTable({
            // "processing": true,
            // "responsive": true,
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load';
                    d.tipe = identity;
                    // d.user_role =  $("#select_role").val();
                    // d.search = {
                    //   value:$("#filter_search").val()
                    // };  
                    d.filter_flag = $("#filter_flag").find(':selected').val();
                    d.search = {
                        value: $("#filter_search").val()
                    };
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": "Kode Gudang", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Nama", "searchable": true, "orderable": true},
                {"targets": 2, "title": "Keterangan", "searchable": true, "orderable": true},
                {"targets": 3, "title": "Action", "searchable": true, "orderable": true},
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [{
                    'data': 'location_code'
                }, {
                    'data': 'location_name'
                }, {
                    'data': 'location_note'
                }, {
                    'data': 'location_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // disp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';

                        dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="' + data + '">';
                        dsp += '<span class="fas fa-edit"></span>Edit';
                        dsp += '</button>';

                        if (parseInt(row.location_flag) === 1) {
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-success"';
                            dsp += 'data-nama="' + row.location_name + '" data-kode="' + row.location_code + '" data-id="' + data + '" data-flag="' + row.location_flag + '">';
                            dsp += '<span class="fas fa-check-square primary"></span> Aktif</button>';
                        } else {
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
                            dsp += 'data-nama="' + row.location_name + '" data-kode="' + row.location_code + '" data-id="' + data + '" data-flag="' + row.location_flag + '">';
                            dsp += '<span class="fas fa-times danger"></span> Nonaktif</button>';
                        }

                        return dsp;
                    }
                }]
        });

        //Datatable Config
        $("#table-data_filter").css('display', 'none');
        $("#table-data_length").css('display', 'none');
        $("#filter_length").on('change', function (e) {
            var value = $(this).find(':selected').val();
            $('select[name="table-data_length"]').val(value).trigger('change');
            index.ajax.reload();
        });
        $("#filter_flag").on('change', function (e) {
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
            $("#table-data").attr('data-limit-start', limit_start);
            $("#table-data").attr('data-limit-end', limit_end);
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

            if (next == true) {
                var prepare = {
                    tipe: identity,
                    kode: $("input[id='kode']").val(),
                    nama: $("input[id='nama']").val(),
                    keterangan: $("input[id='keterangan']").val(),
                    user: $("select[id='user']").find(':selected').val(),
                    status: $("select[id='status']").find(':selected').val()
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
            // $("#form-master input[name='kode']").attr('readonly',true);

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
                        // activeTab('tab1'); // Open/Close Tab By ID
                        // notif(1,d.result.id);ss
                        $("#form-master input[name='id_document']").val(d.result.location_id);
                        $("#form-master input[name='kode']").val(d.result.location_code);
                        $("#form-master input[name='nama']").val(d.result.location_name);
                        $("#form-master input[name='keterangan']").val(d.result.location_note);
                        // $("select[name='user']").html('');           

                        $("#form-master select[name='status']").val(d.result.location_flag).trigger('change');

                        $("#form-master select[name='user']").append('' + '<option value="' + d.result.user_id + '">' + d.result.user_username + '</option>');
                        $("#form-master select[name='user']").val(parseInt(d.result.user_id)).trigger('change');
                        // console.log(d.result.user_username+', '+d.result.user_id);

                        $("#btn-new").hide();
                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-cancel").show();
                        // scrollUp('content');
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
                var prepare = {
                    tipe: identity,
                    id: $("input[id=id_document]").val(),
                    kode: $("input[id='kode']").val(),
                    nama: $("input[id='nama']").val(),
                    keterangan: $("input[id='keterangan']").val(),
                    user: $("select[id='user']").find(':selected').val(),
                    status: $("select[id='status']").find(':selected').val()
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
            var kode = $(this).attr("data-kode");
            var user = $(this).attr("data-nama");
            $.confirm({
                title: 'Hapus!',
                content: 'Apakah anda ingin menghapus <b>' + user + '</b> ?',
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

    });

    function formNew() {
        formMasterSetDisplay(0);
        $("#form-master input").val();
        $("#kode").focus();
        $("#btn-new").hide();
        $("#btn-save").show();
        $("#btn-cancel").show();
    }
    function formCancel() {
        formMasterSetDisplay(1);
        $("#form-master input").val();
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
            "kode",
            "nama",
            "keterangan"
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
            "user",
            "status"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>