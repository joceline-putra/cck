
<script>
    $(document).ready(function () {
        var url = "<?= base_url('report'); ?>";
        //var url_print = "<?= base_url('report/prints'); ?>"; 
        var url_print = "<?= base_url('report'); ?>";
        var url_print_trans = "<?= base_url('transaksi/print_history'); ?>";

        var contact_alias = "<?php echo $customer_alias; ?>";

        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="report/sales/sell/account_receivable"]').addClass('active');
        // $.alert('Datatable: Belum sepenuhnya betul Sampai disini');
        const autoNumericOption = {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalCharacterAlternative: ',',
            decimalPlaces: 0,
            watchExternalChanges: true //!!!        
        };

        $("#start, #end").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        }).on('change', function () {
            index.ajax.reload();
        });

        $('#filter_contact').select2({
            //dropdownParent:$("#modal-id"), //If Select2 Inside Modal
            placeholder: '<i class="fas fa-warehouse"></i> Search',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 2,
                        source: 'contacts'
                    }
                    return query;
                },
                processResults: function (datas, params) {
                    params.page = params.page || 1;
                    return {
                        results: datas,
                        pagination: {
                            more: (params.page * 10) < datas.count_filtered
                        }
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
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute
                $(datas.element).attr('data-alamat', datas.alamat);
                $(datas.element).attr('data-telepon', datas.telepon);
                $(datas.element).attr('data-email', datas.email);
                if ($.isNumeric(datas.id) == true) {
                    return datas.text;
                    // return '<i class="fas fa-warehouse '+datas.id.toLowerCase()+'"></i> '+datas.text;
                }
            }
        });

        var collapsedGroups = {};
        var top = '';
        var parent = '';
        var index = $("#table-data").DataTable({
            // "processing": true,
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load-report-sales-sell-account-receivable';
                    // d.tipe = 1;
                    d.date_start = $("#start").val();
                    d.date_end = $("#end").val();
                    // d.start = $("#table-data").attr('data-limit-start');
                    // d.length = $("#table-data").attr('data-limit-end'); 
                    d.length = $("#filter_length").find(':selected').val();
                    d.contact = $("#filter_contact").find(':selected').val();
                    // d.product = $("#filter_product").find(':selected').val();             
                    // d.order[0]['column'] = $("#filter_order").find(':selected').val();
                    // d.order[0]['dir'] = $("#filter_dir").find(':selected').val();     
                    // d.search = {
                    // value:$("#filter_search").val()
                    // };               
                    // d.user_role =  $("#select_role").val();
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": contact_alias+" / Tanggal", "searchable": false, "orderable": false},
                {"targets": 1, "title": "Transaksi", "searchable": false, "orderable": false},
                {"targets": 2, "title": "Nomor", "searchable": false, "orderable": false},
                {"targets": 3, "title": "Jth Tempo", "searchable": false, "orderable": false, "class": "text-right"},
                {"targets": 4, "title": "Keterangan", "searchable": false, "orderable": false, "class": "text-right"},
                {"targets": 5, "title": "Tagihan", "searchable": false, "orderable": false, "class": "text-right"},
                {"targets": 6, "title": "Dibayar", "searchable": false, "orderable": false, "class": "text-right"},
                {"targets": 7, "title": "Sisa Piutang", "searchable": false, "orderable": false, "class": "text-right"}
            ],
            // "order": [
            //   [0, 'asc']
            // ],
            "columns": [{
                    'data': 'trans_date_format',
                    render: function (data, meta, row) {
                        return '&nbsp;&nbsp;&nbsp;' + data;
                    }
                }, {
                    'data': 'type_name'
                }, {
                    'data': 'trans_number',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // dsp += row.trans_number;
                        dsp += '<a class="btn-print" data-id="' + row.trans_id + '" data-session="' + row.trans_session + '" style="cursor:pointer;">';
                        dsp += '<span class="fas fa-file-alt"></span>&nbsp;' + row.trans_number;
                        dsp += '</a>';
                        return dsp;
                    }
                }, {
                    'data': 'trans_date_due_format',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += data;
                        var date_due_over = parseInt(row.trans_date_due_over);
                        if (date_due_over > 0) {
                            dsp += '<br><span class="label" style="color:white;background-color:#ff6665;padding:1px 4px;">Jatuh Tempo</span>';
                        }
                        return dsp;
                    }
                }, {
                    'data': 'trans_note'
                }, {
                    'data': 'trans_total', className: 'text-right',
                    render: function (data, meta, row) {
                        return addCommas(row.trans_total);
                    }
                }, {
                    'data': 'trans_total_paid', className: 'text-right',
                    render: function (data, meta, row) {
                        return addCommas(row.trans_total_paid);
                    }
                }, {
                    'data': 'balance', className: 'text-right',
                    render: function (data, meta, row) {
                        return addCommas(row.balance);
                    }
                }
            ],
            "rowGroup": {
                dataSrc: ['contact_name'],
                startRender: function (row, data, level) {
                    var all;
                    var dsp = '';
                    if (level === 0) {
                        top = data;
                        all = data;
                        // console.log(all);
                    } else {
                        // if parent collapsed, nothing to do
                        if (!!collapsedGroups[top]) {
                            return;
                        }
                        all = top + data;
                    }
                    var collapsed = !!collapsedGroups[all];
                    // console.log(collapsed);
                    row.nodes().each(function (r) {
                        r.style.display = collapsed ? 'none' : '';
                    });

                    // console.log(row[0][1]['journal_item_id']);
                    // dsp += '<tr>';
                    //   dsp += '<td colspan="4">'+ data +' ('+rows.count()+')</td>';
                    // dsp += '<tr>';
                    // console.log(row.level[1]);
                    // return dsp;
                    // Add category name to the <tr>. NOTE: Hardcoded colspan
                    // console.log(data);
                    if (data !== 'No group') {
                        return $('<tr/>')
                                .append('<td colspan="8" style="background-color:#dfe3e4;">' + data + ' (' + row.count() + ')</td>')
                                .attr('data-name', all)
                                .toggleClass('collapsed', collapsed);
                    } else {
                        // return
                    }
                }
            },
            "language": {
                "emptyTable": "Data tidak tersedia"
            }
        });

        //Datatable Config  
        $("#table-data_filter").css('display', 'none');
        $("#table-data_length").css('display', 'none');
        $("#table-data_info").css('display', 'none');
        $("#table-data_paginate").css('display', 'none');
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

        $(document).on("change", "#filter_contact", function (e) {
            index.ajax.reload();
        });

        $(document).on("click", ".btn-print-all", function () {
            var id = $(this).attr("data-id");
            var action = $(this).attr('data-action'); //1,2
            var request = $(this).attr('data-request'); //report_purchase_buy_recap
            var format = $(this).attr('data-format'); //html, xls
            var contact = $("#filter_contact").find(':selected').val();
            var order = 'trans_date';
            var dir = 'asc';

            // if(parseInt(id) > 0){
            var print_url = url_print + '/'
                    + request + '/'
                    + $("#start").val() + '/'
                    + $("#end").val() + '/'
                    + contact + "?format=" + format + "&order=" + order + "&dir=" + dir;
            window.open(print_url, '_blank');
            // }else{
            // $.alert('Akun Perkiraan harus dipilih dahulu');
            // }
        });
        // Print Button
        $(document).on("click", ".btn-print", function () {
            // var id = $(this).attr("data-id");
            var id = $(this).attr('data-session');
            if (id) {
                var x = screen.width / 2 - 700 / 2;
                var y = screen.height / 2 - 450 / 2;
                var print_url = url_print_trans + '/' + id;
                // console.log(print_url);
                var win = window.open(print_url, 'Print', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
                var data = id;
                // $.post(url_print, {id:data}, function (data) {
                //     var w = window.open(print_url,'Print');
                //     w.document.open();
                //     w.document.write(data);
                //     w.document.close();
                // });
            } else {
                notif(0, 'Dokumen belum di buka');
            }
        });
    });
</script>