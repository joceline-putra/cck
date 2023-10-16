<script>
    $(document).ready(function () {
        // var csrfData = {};
        // csrfData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
        // var csrfData = '<?php echo $this->security->get_csrf_hash(); ?>';

        // $("body").condensMenu();  
        // $('#sidebar .start .sub-menu').css('display','none');
        // $("#modal-trans-save").modal({backdrop: 'static', keyboard: false});

        // Auto Set Navigation
        var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        // $(".nav-tabs").find('li[class="active"]').removeClass('active');
        // $(".nav-tabs").find('li[data-name="' + menu_link + '"]').addClass('active');
        // console.log(menu_link);
        var url = "<?= base_url('flow'); ?>";
        var url_print = "<?= base_url('flow/prints'); ?>";
        var url_print_all = "<?= base_url('report/report_flow'); ?>";

        var counter = true;
        $("#start, #end").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        }).on('change', function () {
            if (counter) {
                index.ajax.reload();
                counter = false;
            }
            setTimeout(function () {
                counter = true;
            })
        });

        $("#tgl").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        }).on('change', function () {

        });

        const autoNumericOption = {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalCharacterAlternative: '.',
            decimalPlaces: 2,
            watchExternalChanges: true //!!!        
        };
        // new AutoNumeric('#jumlah', autoNumericOption);
        // new AutoNumeric('#account_debit_total', autoNumericOption);
        // new AutoNumeric('#account_credit_total', autoNumericOption);    
        // new AutoNumeric('#e_total_debit', autoNumericOption);
        // new AutoNumeric('#e_total_credit', autoNumericOption);        

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
                    d.date_start = $("#start").val();
                    d.date_end = $("#end").val();
                    // d.start = $("#table-data").attr('data-limit-start');
                    // d.length = $("#table-data").attr('data-limit-end');
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
                {"targets": 0, "title": "Tipe", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Nama", "searchable": true, "orderable": true},
                {"targets": 2, "title": "Telepon & Email", "searchable": true, "orderable": true},
                {"targets": 3, "title": "Catatan", "searchable": true, "orderable": true},
                {"targets": 4, "title": "Status", "searchable": true, "orderable": true},
                {"targets": 5, "title": "Action", "searchable": false, "orderable": false}
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [
                {'data': 'flow_date_created'},
                {'data': 'flow_name', className: 'text-left'},
                {'data': 'flow_phone'},
                {'data': 'flow_data'},
                {'data': 'flow_flag', className: 'text-left', render: function (data, meta, row) {
                        var dsp = '';
                        if (parseInt(row.flow_flag) === 1) {
                            dsp += 'Terkonfirmasi';
                        } else {
                            dsp += 'Pending';
                        }

                        return dsp;
                    }
                },
                {'data': 'flow_id', className: 'text-left', render: function (data, meta, row) {
                        var dsp = '';
                        // disp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';

                        // dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="'+ data +'">';
                        // dsp += '<span class="fas fa-pencil"></span>Edit';
                        // dsp += '</button>';

                        // dsp += '<button class="btn-print btn btn-mini btn-primary" data-id="' + data +
                        //   '" data-number="' + row.journal_number + '">';
                        // dsp += '<span class="fas fa-print"></span> Print';
                        // dsp += '</button>';

                        dsp += '<button class="btn-delete btn btn-mini btn-danger" data-id="' + data +
                                '" data-number="' + row.flow_session + '">';
                        dsp += '<span class="fas fa-trash"></span> Hapus';
                        dsp += '</button>';

                        // if (parseInt(row.flag) === 1) {
                        //   dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-primary"';
                        //   dsp += 'data-nomor="'+row.trans_nomor+'" data-kode="'+row.kode+'" data-id="'+data+'" data-flag="'+row.trans_flag+'">';
                        //   dsp += '<span class="fas fa-check-square primary"></span></button>';
                        // }else{ 
                        //   dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
                        //   dsp += 'data-nama="'+row.nama+'" data-kode="'+row.kode+'" data-id="'+data+'" data-flag="'+row.flag+'">';
                        //   dsp += '<span class="fas fa-times danger"></span></button>';
                        // }

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
            }
        });
        $('#table-data').on('page.dt', function () {
            var info = index.page.info();
            // console.log( 'Showing page: '+info.page+' of '+info.pages);
            var limit_start = info.start;
            var limit_end = info.end;
            var length = info.length;
            var page = info.page;
            var pages = info.pages;
            console.log(limit_start, limit_end);
            $("#table-data").attr('data-limit-start', limit_start);
            $("#table-data").attr('data-limit-end', limit_end);
        });

        // New Button
        $(document).on("click", "#btn-new", function (e) {
            formTransNew();
            // $("#div-form-trans").show(300);
            $("#div-form-trans").show(300);
            $(this).hide();
        });

        $(document).on("click", "#btn-cancel", function (e) {
            formTransCancel();
            $("#div-form-trans").hide(300);
        });

        // Save Button
        $(document).on("click", "#btn-save", function (e) {
            e.preventDefault();
            var next = true;
            var id_document = $("input[id=id_document]").val();

            if (parseInt(id_document) > 0) {
                notif(0, 'Silahkan refresh halaman ini');
                next = false;
            }

            // if (next == true) {
            //   if ($("select[id='account_kredit']").find(':selected').val() == 0) {
            //     notif(0, 'Bayar dari harus dipilih');
            //     next = false;
            //   }
            // }

            if (next == true) {
                if ($("select[id='kontak']").find(':selected').val() == 0) {
                    notif(0, 'Penerima harus dipilih');
                    next = false;
                }
            }

            // if (next == true) {
            //   if ($("select[id='cara_pembayaran']").find(':selected').val() == 0) {
            //     notif(0, 'Cara Pembayaran harus dipilih');
            //     next = false;
            //   }
            // }

            if (next == true) {
                var total_item = $("input[id='total_item']").val();
                if (parseInt(total_item) == 0) {
                    notif(0, 'Minimal satu rincian di input');
                    next = false;
                }
            }

            // if ($("select[id='account_debit']").find(':selected').val() == $("select[id='account_kredit']").find(
            //     ':selected').val()) {
            //   notif(0, 'Debit dan Kredit tidak boleh sama');
            //   next = false;
            // }

            // if(next == true){
            //   var total_debit = removeCommas($("#total_debit").val());
            //   var total_credit = removeCommas($("#total_credit").val());
            //    // console.log(total_debit+','+total_credit);
            //   if(parseFloat(total_debit) !== parseFloat(total_credit)){
            //     notif(0, 'Total debit & Total kredit harus imbang');
            //     next=false;
            //   }       
            // }

            if (next == true) {

                //Prepare all Data
                var prepare = {
                    tipe: identity,
                    tgl: $("input[id='tgl']").val(),
                    nomor: $("input[id='nomor']").val(),
                    kontak: $("select[id='kontak']").find(':selected').val(),
                    // debit: $("select[id='account_debit']").find(':selected').val(),
                    akun: $("select[id='account_kredit']").find(':selected').val(),
                    // jumlah: $("input[id='jumlah']").val(),
                    keterangan: $("textarea[id='keterangan']").val(),
                    cara_pembayaran: $("select[id='cara_pembayaran']").find(':selected').val()
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
                        if (parseInt(d.status) == 1) {
                            /* Success Message */
                            notif(1, d.message);
                            $("input[id=id_document]").val(d.result.journal_id);
                            // alert(d.result.journal_id);
                            $("input[id=nomor]").val(d.result.journal_number);
                            index.ajax.reload();
                            $(".btn-print").attr('data-id', d.result.order_id);
                            $(".btn-print").attr('data-number', d.result.order_number);
                            // formTransNew();
                            // formTransSetDisplay(0);
                        } else { //Error
                            notif(0, d.message);
                        }
                        checkDocumentExist();
                    },
                    error: function (xhr, Status, err) {
                        notif(0, 'Error');
                    }
                });

            }
        });

        // Edit Button
        $(document).on("click", ".btn-edit", function (e) {
            // formMasterSetDisplay(0);

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
                    if (parseInt(d.status) == 1) {
                        /* Success Message */
                        $("#form-trans input[name='id_document']").val(d.result.journal_id);
                        var dd = d.result.journal_date.substr(8, 2);
                        var mm = d.result.journal_date.substr(5, 2);
                        var yy = d.result.journal_date.substr(0, 4);
                        var set_date = dd + '-' + mm + '-' + yy;

                        $("#form-trans input[name='tgl']").datepicker("update", set_date);
                        $("#form-trans input[name='nomor']").val(d.result.journal_number);
                        $("textarea[id='keterangan']").val(d.result.journal_note);
                        $("select[name='kontak']").append('' +
                                '<option value="' + d.result.contact_id + '" data-alamat="' + d.result.contact_address + '" data-telepon="' + d.result.phone_1 + '" data-email="' + d.result.email_1 + '">' +
                                d.result.contact_name +
                                '</option>');
                        $("select[name='kontak']").val(d.result.contact_id).trigger('change');
                        $("select[name='account_kredit']").append('' +
                                '<option value="' + d.result.account_id + '" data-kode="' + d.result.account_code + '" data-nama="' + d.result.account_name + '">' +
                                d.result.account_code + ' - ' + d.result.account_name + '</option>');
                        $("select[name='account_kredit']").val(d.result.account_id).trigger('change');
                        $("select[name='cara_pembayaran']").val(d.result.journal_paid_type).trigger('change');

                        loadJournalItems();
                        $("#btn-new").hide();
                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-cancel").show();
                        // $("#btn-update").attr('data-id',d.result.journal_id);
                        // $("#btn-print").attr('data-id',d.result.journal_id);            
                        scrollUp('content');
                    } else {
                        notif(0, d.message);
                    }
                    checkDocumentExist();
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
            var id = $("#form-trans input[name='id_document']").val();
            var kode = $("#form-trans input[name='kode']");
            var nama = $("#form-trans input[name='nama']");

            if ((id == '') || parseInt(id) == 0) {
                notif(0, 'Dokumen tidak ditemukan');
                next = false;
            }

            if (next == true) {
                if ($("select[id='kontak']").find(':selected').val() == 0) {
                    notif(0, 'Penerima harus diisi');
                    next = false;
                }
            }

            // if(next == true){
            //   var total_debit = removeCommas($("#total_debit").val());
            //   var total_credit = removeCommas($("#total_credit").val());
            //    // console.log(total_debit+','+total_credit);
            //   if(parseFloat(total_debit) !== parseFloat(total_credit)){
            //     notif(0, 'Total debit & Total kredit harus imbang');
            //     next=false;
            //   }       
            // }

            if (next == true) {
                var prepare = {
                    tipe: identity,
                    id: id,
                    tgl: $("input[id='tgl']").val(),
                    nomor: $("input[id='nomor']").val(),
                    kontak: $("select[id='kontak']").find(':selected').val(),
                    akun: $("select[id='account_kredit']").find(':selected').val(),
                    keterangan: $("textarea[id='keterangan']").val(),
                    // cara_pembayaran: $("select[id='cara_pembayaran']").find(':selected').val()          
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
                            // $("#btn-save").hide();
                            // $("#btn-update").hide();
                            // $("#btn-cancel").hide();
                            // $("#form-trans input").val(); 
                            // formTransSetDisplay(1);      
                            notif(1, d.message);
                            index.ajax.reload(null, false);
                        } else {
                            notif(0, d.message);
                        }
                        checkDocumentExist();
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
            var number = $(this).attr("data-number");

            $.confirm({
                title: 'Hapus!',
                content: 'Apakah anda ingin menghapus <b>' + number + '</b> ?',
                buttons: {
                    confirm: {
                        btnClass: 'btn-danger',
                        text: 'Ya',
                        action: function () {
                            var data = {
                                action: 'delete',
                                tipe: identity,
                                id: id,
                                number: number
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        index.ajax.reload(null, false);
                                    } else {
                                        notif(0, d.message);
                                    }
                                    checkDocumentExist();
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

        // Print Button
        $(document).on("click", "#btn-print", function () {
            // var id = $(this).attr("data-id");
            var id = $("#form-trans input[id='id_document']").val();
            if (parseInt(id) > 0) {
                var x = screen.width / 2 - 700 / 2;
                var y = screen.height / 2 - 450 / 2;
                var print_url = url_print + '/' + id;
                // console.log(print_url);
                var next = true;
                // if(next == true){
                //   var total_debit = removeCommas($("#total_debit").val());
                //   var total_credit = removeCommas($("#total_credit").val());
                //    // console.log(total_debit+','+total_credit);
                //   if(parseFloat(total_debit) !== parseFloat(total_credit)){
                //     notif(0, 'Total debit & Total kredit harus imbang');
                //     next=false;
                //   }       
                // }

                if (next) {
                    var win = window.open(print_url, 'Print Kirim Uang', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
                    var data = id;
                    // $.post(url_print, {id:data}, function (data) {
                    //     var w = window.open(print_url,'Print');
                    //     w.document.open();
                    //     w.document.write(data);
                    //     w.document.close();
                    // });
                }
            } else {
                notif(0, 'Dokumen belum di buka');
            }
        });

        function addCommas(string) {
            string += '';
            var x = string.split('.');
            var x1 = x[0];
            var x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }
        function removeCommas(string) {

            return string.split(',').join("");
        }
        function formTransNew() {
            formTransSetDisplay(0);
            $("#form-trans input").not("input[id='tipe']").not("input[id='tgl']").not("input[id='tgl_tempo']").val('');
            // $("#btn-new").hide();
            $("#btn-save").show();
            $("#btn-cancel").show();
            $("#form-trans input[id='id_document']").val(0);
        }
        function formTransCancel() {
            formTransSetDisplay(1);
            $("#form-trans input").not("input[id='tipe']").not("input[id='tgl']").not("input[id='tgl_tempo']").val('');
            // $("#btn-new").show();
            // $("#btn-save").hide();
            // $("#btn-update").hide();
            // $("#btn-cancel").hide();
            $("select").val(0).trigger('change');
            $("#form-trans input[id='id_document']").val(0);
            $("#btn-new").show();
            loadJournalItems();
        }
    });

    function checkDocumentExist() {
        var id = $("#id_document").val();
        // alert(id);
        if (parseInt(id) > 0) {
            $("#btn-new").show();
            $("#btn-save").hide();
            $("#btn-update").show();
            $("#btn-cancel").show();
            $("#btn-print").show();
        } else {
            $("#btn-update").hide();
            $("#btn-print").hide();
        }
    }
    function formKontakNew() {
        $("#kode").val('');
        $("#nama").val('');
        $("#perusahaan").val('');
        $("#telepon_1").val('');
        $("#email_1").val('');
        $("#alamat").val('');
    }
    function formAccountNew() {
        $("#kode-akun").val('');
        $("#nama-akun").val('');
    }

    function formTransItemCancel() {
        $("input[id='account_debit_note']").val('');
        $("input[id='account_debit_total']").val('');
        $("input[id='account_credit_total']").val('');
        // $("#btn-new").show();
        // $("#btn-save").hide();
        // $("#btn-update").hide();
        // $("#btn-cancel").hide();
        $("select[id='account_debit_account']").val(0).trigger('change');
    }
    function formTransSetDisplay(value) { // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        if (value == 1) {
            var flag = true;
        } else {
            var flag = false;
        }
        //Attr Input yang perlu di setel
        var form = '#form-trans';
        var attrInput = [
            "nomor",
            // "jumlah",
            "keterangan"
        ];

        for (var i = 0; i <= attrInput.length; i++) {
            $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        }

        //Attr Textarea yang perlu di setel
        /*
         var attrText = [
         "keterangan"
         ];
         for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }
         */

        //Attr Select yang perlu di setel 
        var atributSelect = [
            "kontak",
            "cara_pembayaran",
            "account_debit_account",
            "account_kredit"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>