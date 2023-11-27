
<script>
    $(document).ready(function () {
        var url = "<?= base_url('inventory/manage'); ?>";
        //var url_print = "<?= base_url('report/prints'); ?>"; 
        var url_print = "<?= base_url('report'); ?>";
        var url_product = "<?= base_url('produk/manage'); ?>";
        var url_print_stock_card = "<?= base_url('report/report_stock_card'); ?>";
        // $.alert('Datatable: Belum sepenuhnya betul Sampai disini');

        var product_alias = "<?php echo $product_alias; ?>";

        $(".nav-tabs").find('li[class="active"]').removeClass('active');
	    $(".nav-tabs").find('li[data-name="report/inventory/product/stock_moving"]').addClass('active');  

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

        /*
        $('#filter_location').select2({
            minimumInputLength: 0,
            allowClear: true,
            placeholder: {
                id: '0',
                text: '-- Semua --'
            },
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1, //1=Supplier, 2=Asuransi
                        source: 'locations'
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
                // $(datas.element).attr('data-alamat', datas.alamat);
                // $(datas.element).attr('data-telepon', datas.telepon);
                // $(datas.element).attr('data-email', datas.email);
                if ($.isNumeric(datas.id) == true) {
                    return datas.text;
                    // return '<i class="fas fa-warehouse '+datas.id.toLowerCase()+'"></i> '+datas.text;
                }
            }
        });
        */
        $('#filter_product').select2({
            minimumInputLength: 0,
            allowClear: true,
            placeholder: {
                id: '0',
                text: '-- Semua --'
            },
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1,
                        // category: 1,
                        branch: $("#filter_location").find(":selected").val(),
                        source: 'products_other'
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
                if ($.isNumeric(datas.id) == true) {
                    return datas.text;
                    // return '<i class="fas fa-warehouse '+datas.id.toLowerCase()+'"></i> '+datas.text;
                }
            }
        });
        $('#filter_categories').select2({
            minimumInputLength: 0,
            allowClear: true,
            placeholder: {
                id: '0',
                text: '-- Semua --'
            },
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1, //1=Produk, 2=News
                        source: 'categories'
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
        var index = $("#table-data").DataTable({
            // "processing": true,
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load-stock-moving';
                    d.tipe = 1;
                    d.date_start = $("#start").val();
                    d.date_end = $("#end").val();
                    // d.start = $("#table-data").attr('data-limit-start');
                    // d.length = $("#table-data").attr('data-limit-end'); 
                    d.length = $("#filter_length").find(':selected').val();
                    d.location = $("#filter_location").find(':selected').val();
                    d.product = $("#filter_product").find(':selected').val();
                    d.order[0]['column'] = $("#filter_order").find(':selected').val();
                    d.order[0]['dir'] = $("#filter_dir").find(':selected').val();
                    d.category = $("#filter_categories").find(':selected').val();                 
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
                {"targets": 0, "title": "Nama "+product_alias, "searchable": false, "orderable": false},
                {"targets": 1, "title": "Satuan", "searchable": false, "orderable": false},
                {"targets": 2, "title": "Cabang", "searchable": false, "orderable": false},
                {"targets": 3, "title": "Awal", "searchable": false, "orderable": false},
                {"targets": 4, "title": "Masuk", "searchable": false, "orderable": false},
                {"targets": 5, "title": "Keluar", "searchable": false, "orderable": false},
                {"targets": 6, "title": "Akhir", "searchable": false, "orderable": false},
                {"targets": 7, "title": "Print", "searchable": false, "orderable": false}
            ],
            // "order": [
            //   [0, 'asc']
            // ],
            "columns": [{
                    'data': 'product_name'
                }, {
                    'data': 'product_unit'
                }, {
                    'data': 'location_name'
                }, {
                    'data': 'start_qty', className: 'text-right',
                    render: function (data, meta, row) {
                        return addCommas(data);
                    }
                }, {
                    'data': 'in_qty', className: 'text-right',
                    render: function (data, meta, row) {
                        return addCommas(data);
                    }
                }, {
                    'data': 'out_qty', className: 'text-right',
                    render: function (data, meta, row) {
                        return addCommas(data);
                    }
                }, {
                    'data': 'balance', className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<a href="#" class="btn-product-stock" data-product-id="' + row.product_id + '" data-product-name="' + row.product_name + '" data-product-unit="' + row.product_unit + '"><b>' + addCommas(data) + '</b></a>';
                        return dsp;
                    }
                }, {
                    'data': 'product_id',
                    render: function (data, meta, row) {
                        var dsp = '';
                        var loc = $("#filter_location").find(':selected').val();
                        var btn = 'btn-default';
                        if (parseInt(loc) > 0) {
                            btn = 'btn-primary';
                        }

                        dsp += '&nbsp;<button class="btn-print-stock-card btn btn-mini ' + btn + '" data-id="' + data + '">';
                        dsp += '<span class="fas fa-print"></span> Kartu Stok';
                        dsp += '</button>';

                        return dsp;
                    }
                }]
        });

        //Datatable Config  
        $("#table-data_filter").css('display', 'none');
        $("#table-data_length").css('display', 'none');
        $(document).on("change", "#filter_location", function (e) {
            index.ajax.reload();
        });
        $(document).on("change", "#filter_product", function (e) {
            index.ajax.reload();
        });
        $(document).on("change", "#filter_order", function (e) {
            index.ajax.reload();
        });
        $(document).on("change", "#filter_dir", function (e) {
            index.ajax.reload();
        });
        $(document).on("change", "#filter_categories", function (e) {
            index.ajax.reload();
        });

        // $(document).on("click", ".btn-product-stock", function (e) {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     var product_id = $(this).attr('data-product-id');
        //     var product_name = $(this).attr('data-product-name');
        //     var product_unit = $(this).attr('data-product-unit');
        //     var title = 'Lokasi Stok';
        //     $.confirm({
        //         title: title,
        //         columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
        //         autoClose: 'button_2|30000',
        //         closeIcon: true,
        //         closeIconClass: 'fas fa-times',
        //         animation: 'zoom',
        //         closeAnimation: 'bottom',
        //         animateFromElement: false,
        //         content: function () {
        //             var self = this;

        //             var form = new FormData();
        //             form.append('action', 'stock');
        //             form.append('id', product_id);
        //             form.append('tipe', 1);

        //             return $.ajax({
        //                 url: url_product,
        //                 data: form,
        //                 dataType: 'json',
        //                 type: 'post',
        //                 cache: 'false', contentType: false, processData: false,
        //             }).done(function (d) {
        //                 var s = d.status;
        //                 var m = d.message;
        //                 var r = d.result;
        //                 if (parseInt(s) == 1) {
        //                     // notif(s,m);
        //                     // notifSuccess(m);
        //                     /* hint zz_for or zz_each */
        //                     var dsp = '';
        //                     var total_data = r.length;
        //                     dsp += 'Barang :<b>' + product_name + '</b><br>';
        //                     dsp += 'Satuan :<b>' + product_unit + '</b><br><br>';
        //                     dsp += '<table class="table table-bordered">';
        //                     dsp += '  <thead>';
        //                     dsp += '    <tr>';
        //                     dsp += '      <th>Gudang</th>';
        //                     dsp += '      <th>Stok</th>';
        //                     dsp += '      <th>Action</th>';
        //                     dsp += '    <tr>';
        //                     dsp += '  </thead>';
        //                     dsp += '  <tbody>';
        //                     for (var a = 0; a < total_data; a++) {
        //                         dsp += '<tr class="tr-price-item-id" data-id="' + d.result[a]['product_price_id'] + '">';
        //                         dsp += '<td>' + d.result[a]['location_name'] + '</td>';
        //                         dsp += '<td style="text-align:right;">' + addCommas(d.result[a]['qty_balance']) + '</td>';
        //                         dsp += '<td>';
        //                         dsp += '<button type="button" class="btn-product-stock-card btn btn-mini btn-primary" data-url="' + d.result[a]['stock_card_url'] + '">';
        //                         dsp += '<span class="fas fa-file-alt"></span>';
        //                         dsp += '&nbsp;Kartu Stok</button>';
        //                         dsp += '</td>';
        //                         dsp += '</tr>';
        //                     }
        //                     dsp += '  </tbody>';
        //                     dsp += '</table>';

        //                     self.setContentAppend(dsp);
        //                 } else {
        //                     // notif(s,m);
        //                     // notifSuccess(m);
        //                 }
        //                 // self.setTitle(m);
        //                 // self.setContentAppend('<br>Version: ' + d.short_name); //Json Return
        //                 // self.setContentAppend('<img src="'+ d.icons[0].src+'" class="img-responsive" style="margin:0 auto;">'); // Image Return
        //                 /*type_your_code_here*/

        //             }).fail(function () {
        //                 self.setContent('Something went wrong, Please try again.');
        //             });
        //         },
        //         onContentReady: function () {
        //             var self = this;
        //             var content = '';
        //             var dsp = '';

        //             var d = self.ajaxResponse.data;

        //             var s = d.status;
        //             var m = d.message;
        //             var r = d.result;

        //             if (parseInt(s) == 1) {
        //                 // dsp += '<div>Content is ready after process !</div>';
        //                 // dsp += '<form id="jc_form">';
        //                 //     dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
        //                 //     dsp += '    <div class="form-group">';
        //                 //     dsp += '    <label class="form-label">Input</label>';
        //                 //     dsp += '        <input id="jc_input" name="jc_input" class="form-control">';
        //                 //     dsp += '    </div>';
        //                 //     dsp += '</div>';
        //                 //     dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
        //                 //     dsp += '    <div class="form-group">';
        //                 //     dsp += '    <label class="form-label">Textarea</label>';
        //                 //     dsp += '        <textarea id="jc_textarea" name="alamat" class="form-control" rows="4"></textarea>';
        //                 //     dsp += '    </div>';
        //                 //     dsp += '</div>';
        //                 //     dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
        //                 //     dsp += '    <div class="form-group">';
        //                 //     dsp += '    <label class="form-label">Select</label>';
        //                 //     dsp += '        <select id="jc_select" name="jc_select" class="form-control">';
        //                 //     dsp += '            <option value="1">Ya</option>';
        //                 //     dsp += '            <option value="2">Tidak</option>';
        //                 //     dsp += '        </select>';
        //                 //     dsp += '    </div>';
        //                 //     dsp += '</div>';
        //                 // dsp += '</form>';
        //                 // content = dsp;
        //                 // self.setContentAppend(content);
        //                 // self.buttons.button_1.disable();
        //                 // self.buttons.button_2.disable();

        //                 // this.$content.find('form').on('submit', function (e) {
        //                 //      e.preventDefault();
        //                 //      self.$$formSubmit.trigger('click'); // reference the button and click it
        //                 // });
        //             } else {
        //                 self.setContentAppend('<div>Content ready!</div>');
        //             }
        //         },
        //         buttons: {
        //             cancel: {
        //                 btnClass: 'btn-default',
        //                 text: 'Tutup',
        //                 action: function () {
        //                     // $.alert('Canceled!');
        //                 }
        //             }
        //         }
        //     });
        // });
        // $(document).on("click", ".btn-product-stock-card", function (e) {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     console.log($(this));
        //     var print_stock = $(this).attr('data-url');

        //     var x = screen.width / 2 - 700 / 2;
        //     var y = screen.height / 2 - 450 / 2;
        //     var print_url = print_stock;
        //     var win = window.open(print_url, 'Print Kartu Stok', 'width=880,height=500,left=' + x + ',top=' + y + '');
        // });
        $(document).on("click", ".btn-print-all", function () {
            var id = $(this).attr("data-id");
            var action = $(this).attr('data-action'); //1,2
            var request = $(this).attr('data-request'); //report_purchase_buy_recap
            var format = $(this).attr('data-format'); //html, xls
            var location = $("#filter_location").find(':selected').val();
            var product = $("#filter_product").find(':selected').val();
            var category = $("#filter_categories").find(':selected').val();

            var order = $("#filter_order").find(':selected').val();
            if (order == 0) {
                order = 'product_code';
            } else if (order == 1) {
                order = 'product_name';
            }

            var dir = $("#filter_dir").find(':selected').val();
            //alert('#btn-print-all on Click'+action+','+request);
            // var x = screen.width / 2 - 700 / 2;
            // var y = screen.height / 2 - 450 / 2;
            // var print_url = url_print +'/'+ action + '/' +request+ '/' +contact+ '/' + $("#start").val() + '/' + $("#end").val();
            var print_url = url_print + '/'
                    + request + '/'
                    + $("#start").val() + '/'
                    + $("#end").val() + '/'
                    + location + "?product=" + product + "&format=" + format + "&order=" + order + "&dir=" + dir + "&category=" + category;
            window.open(print_url, '_blank');
            // var request = $('.btn-print-all').data('request');
            // var print_url = url_print +'/'+ request + '/'+ $("#start").val() +'/'+ $("#end").val();
            // var win = window.open(print_url,'Print','width=700,height=485,left=' + x + ',top=' + y + '').print();   
        });
        $(document).on("click", ".btn-print-stock-card", function () {
            var id = $(this).attr("data-id");
            var action = $(this).attr('data-action'); //1,2
            var request = $(this).attr('data-request'); //report_purchase_buy_recap
            var format = 'html'; //html, xls
            var location = $("#filter_location").find(':selected').val();
            var order = 'product_name';
            var dir = 'asc';
            var ver = 1;
            console.log(location);
            if (parseInt(location) > 0) {
                var print_url = url_print_stock_card + '/'
                        + $("#start").val() + '/'
                        + $("#end").val() + '/'
                        + location + "?product=" + id + "&format=" + format + "&order=" + order + "&ver=" + ver + "&dir=" + dir;
                window.open(print_url, '_blank');
            } else {
                notif(0, 'Gudang harus dipilih dahulu');
            }
            // var request = $('.btn-print-all').data('request');
            // var print_url = url_print +'/'+ request + '/'+ $("#start").val() +'/'+ $("#end").val();
            // var win = window.open(print_url,'Print','width=700,height=485,left=' + x + ',top=' + y + '').print();   
        });
    });
</script>