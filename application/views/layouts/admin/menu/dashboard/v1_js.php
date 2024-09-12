<input class="hidden" id="iduser" name="iduser" value="<?php echo $session['user_data']['user_id']; ?>">
<script>
    // Start document ready
    $(document).ready(function () {
        var url_approval = "<?= base_url('approval'); ?>"; //Not Used
        var url_dashboard = "<?= base_url('dashboard'); ?>";
        var url_trans = "<?= base_url('transaksi/manage'); ?>"; //Not Used
        var url_message         = "<?= base_url('message'); ?>";
        
        // Booking Variable
        // let bookingDATA;
        // $("#modal_booking_cece").modal('show');
        $('#chart-three-branch').select2({
            //dropdownParent:$("#modal-id"), //If Select2 Inside Modal, $(".jconfirm-box-container") if jConfirm
            //placeholder: '<i class="fas fa-search"></i> Search',
            //width:'100%',
            tags:true,
            minimumInputLength: 0,
            placeholder: {
                id: '0',
                text: 'Semua Cabang'
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage');?>",
                dataType: 'json',
                delay: 250,
                 beforeSend:function(x){
                    // x.setRequestHeader('Authorization',"Bearer " + bearer_token);
                    // x.setRequestHeader('X-CSRF-TOKEN',csrf_token);
                },
                data: function (params) {
                    var query = {
                        search: params.term,
                        // tipe: 1,
                        source: 'branchs'
                    };
                    return query;
                },
                processResults: function (data){
                    var datas = [];
                    $.each(data, function(key, val){
                        datas.push({
                            'id' : val.id,
                            'text' : val.nama
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
                //if (!datas.id) { return datas.text; }
                if($.isNumeric(datas.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            },
            templateSelection: function(datas) { //When Option on Click
                //if (!datas.id) { return datas.text; }
                //Custom Data Attribute
                //$(datas.element).attr('data-column', datas.column);        
                //return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                if($.isNumeric(datas.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            }
        }); 
        $('#branch').select2({
            //dropdownParent:$("#modal-id"), //If Select2 Inside Modal, $(".jconfirm-box-container") if jConfirm
            //placeholder: '<i class="fas fa-search"></i> Search',
            //width:'100%',
            tags:true,
            minimumInputLength: 0,
            placeholder: {
                id: '0',
                text: 'Semua Cabang'
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage');?>",
                dataType: 'json',
                delay: 250,
                 beforeSend:function(x){
                    // x.setRequestHeader('Authorization',"Bearer " + bearer_token);
                    // x.setRequestHeader('X-CSRF-TOKEN',csrf_token);
                },
                data: function (params) {
                    var query = {
                        search: params.term,
                        // tipe: 1,
                        source: 'branchs'
                    };
                    return query;
                },
                processResults: function (data){
                    var datas = [];
                    $.each(data, function(key, val){
                        datas.push({
                            'id' : val.id,
                            'text' : val.nama
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
                //if (!datas.id) { return datas.text; }
                if($.isNumeric(datas.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            },
            templateSelection: function(datas) { //When Option on Click
                //if (!datas.id) { return datas.text; }
                //Custom Data Attribute
                //$(datas.element).attr('data-column', datas.column);        
                //return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                if($.isNumeric(datas.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            }
        });         
        $(document).on("change","#chart-three-branch",function(e) {
            e.preventDefault();
            e.stopPropagation();
            var var_custom = $(this).find(':selected').val();
            chart_recap_all(var_custom);
        });
        $(document).on("change","#branch",function(e) {
            e.preventDefault();
            e.stopPropagation();
            var var_custom = $(this).find(':selected').val();
            load_room(var_custom);
        });        
        
        // Variable
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            const randomScalingFactor = function () {
                return Math.round(Math.random() * 100 * (Math.random() > 0.5 ? -1 : 1));
            };
            $('#dashboard_user').select2();
            $('.date').datepicker({
                // defaultDate: new Date(),
                format: 'dd-mm-yyyy',
                autoclose: true,
                enableOnReadOnly: true,
                language: "id",
                todayHighlight: true,
                weekStart: 1
            }).on("changeDate", function (e) {
                console.log('changeDate from .date');
            });

        // Start of Daterange
            var start = moment().subtract(1, 'days');
            var end = moment();
            $('#filter_date').daterangepicker({
                startDate: start, //mm/dd/yyyy
                endDate: end, ////mm/dd/yyyy
                "showDropdowns": true,
                "minYear": 2019,
                // "maxYear": 2020,
                "autoApply": false,
                "alwaysShowCalendars": true,
                "opens": "center",
                "buttonClasses": "btn btn-sms",
                "applyButtonClasses": "btn-primaryd",
                "cancelClass": "btn-defaults",
                "ranges": {
                    'Hari ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 hari terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 hari terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                "locale": {
                    "format": "MM/DD/YYYY",
                    "separator": " - ",
                    "applyLabel": "Apply",
                    "cancelLabel": "Cancel",
                    "fromLabel": "From",
                    "toLabel": "To",
                    "customRangeLabel": "Custom",
                    "weekLabel": "W",
                    "daysOfWeek": ["Mn", "Sn", "Sl", "Rb", "Km", "Jm", "Sb"],
                    "monthNames": ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                    "firstDay": 1
                }
            }, function (start, end, label) {
                // console.log(start.format('YYYY-MM-DD')+' to '+end.format('YYYY-MM-DD'));
                set_daterangepicker(start, end);
                // checkup_table.ajax.reload();
            });
            $('#filter_date').on('apply.daterangepicker', function (ev, picker) {
                console.log(ev + ', ' + picker);
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                $("#dashboard-notif").html('');
                checkDashboardActivity(1);
            });
            function set_daterangepicker(start, end) {
                $("#filter_date").attr('data-start', start.format('DD-MM-YYYY'));
                $("#filter_date").attr('data-end', end.format('DD-MM-YYYY'));
                $('#filter_date span').html(start.format('D-MMM-YYYY') + '&nbsp;&nbsp;&nbsp;&nbsp;sd&nbsp;&nbsp;&nbsp;&nbsp;' + end.format('D-MMM-YYYY'));
            }        
            set_daterangepicker(start, end);
        // End of Daterange

        // Dashboard Scroll Activities
            var limit_start = 1;
            var next_ = true; // true = data ada dimuat kembali & false = data tidak ada!
            if (next_ == true) { //Start on Refresh Page
                next_ = false;
                checkDashboardActivity(limit_start);
            }
            $(window).on("scroll", function (e) {
                var scrollTop = Math.round($(window).scrollTop());
                var height = Math.round($(window).height());
                var dashboardHeight = Math.round($(document).height());
                if ($(window).scrollTop() + $(window).height() > ($(document).height() - 100) && next_ == true) {
                    next_ = false;
                    limit_start = limit_start + 1;
                    checkDashboardActivity(limit_start);
                }
            });
        // End of Dashboard School

        // Approval
            $(document).on("click", ".btn-approvel-user", function (e) {
                e.preventDefault();
                e.stopPropagation();
                var id = $(this).attr('data-user-id');
                var name = $(this).attr('data-user-name');
                $.alert('Function belum tersedia');
            });
            $(document).on("click", ".btn-approval-print", function (e) {
                e.preventDefault();
                e.stopPropagation();
                var url = $(this).data('url');
                window.open(url, '_blank');
            });
            $(document).on("click", ".btn-approval-action", function (e) {
                e.preventDefault();
                e.stopPropagation();
                var approval_session = $(this).attr('data-approval-session');
                var trans_session = $(this).attr('data-trans-session');
                var trans_number = $(this).attr('data-trans-number');
                var trans_total = $(this).attr('data-trans-total');
                var contact_name = $(this).attr('data-contact-name');
                // $.alert('Function belum tersedia'+session+', '+trans_number);
                $.confirm({
                    title: 'Konfirmasi Persetujuan',
                    content: 'Apakah anda ingin menindaklanjuti dokumen <b>' + trans_number + '</b> @' + contact_name + ' senilai <b>IDR ' + addCommas(trans_total) + '</b> ?',
                    columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                    // autoClose: 'button_5|60000',
                    closeIcon: true,
                    closeIconClass: 'fas fa-times',
                    animation: 'zoom',
                    closeAnimation: 'bottom',
                    animateFromElement: false,
                    onContentReady: function(e){
                        let self    = this;
                        let content = '';
                        let dsp     = '';
                
                        // dsp += '<div>Content is ready after process !</div>';
                        dsp += '<form id="jc_form">';
                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Komentar</label>';
                            dsp += '        <textarea id="jc_textarea" name="alamat" class="form-control" rows="1" style="height:48px!important;"></textarea>';
                            dsp += '    </div>';
                            dsp += '</div>';
                        dsp += '</form>';
                        content = dsp;
                        self.setContentAppend(content);
                        // self.buttons.button_1.disable();
                        // self.buttons.button_2.disable();
                
                        // this.$content.find('form').on('submit', function (e) {
                        //      e.preventDefault();
                        //      self.$$formSubmit.trigger('click'); // reference the button and click it
                        // });
                    },                
                    buttons: {
                        button_1: {
                            text: '<i class="fas fa-check-square"></i> Setujui', btnClass: 'btn-primary',
                            action: function () {
                                let self      = this;
                                // let input = self.$content.find("#jc_textarea").val();
                                // if(!input){
                                    // $.alert('Mohon isi komentarnya');
                                    // return false;
                                // }else{
                                    $.ajax({type: "post", url: url_approval,
                                        data: {
                                            action: 'update',
                                            approval_session: approval_session,
                                            approval_flag:1,
                                            approval_comment:input
                                        }, dataType: 'json', cache: 'false',
                                        success: function (d) {
                                            notif(d.status, d.message);
                                            checkApprovalRequest();
                                        }
                                    });
                                // }
                            }
                        },
                        button_3: {
                            text: '<i class="fas fa-times"></i> Tolak', btnClass: 'btn-danger',
                            action: function () {
                                let self      = this;
                                let input = self.$content.find("#jc_textarea").val();
                                if(!input){
                                    $.alert('Mohon isi komentarnya');
                                    self.$content.focus();
                                    return false;
                                }else{
                                    $.ajax({type: "post", url: url_approval,
                                        data: {
                                            action: 'update',
                                            approval_session: approval_session,
                                            approval_flag: 3,
                                            approval_comment:input
                                        }, dataType: 'json', cache: 'false',
                                        success: function (d) {
                                            notif(d.status, d.message);
                                            checkApprovalRequest();
                                        }
                                    });
                                }
                            }
                        }
                    }
                });
            });
            $(document).on("click", ".link", function (e) {
                $.alert('Not Ready');
                var url = $(this).data('url');
                window.open(url, '_blank');
            });
        // Enf of Approval

        // Booking
        $(document).on("click","#btn_top_cece_date_due", function (e) {
            e.preventDefault();
            e.stopPropagation();
            top_cece_date_due();
        });
        $(document).on("click",".btn_booking_info", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-order-id');
            var item_id = $(this).attr('data-order-item-id');    
            var v = JSON.parse(atob($(this).attr('data-order'))); 
            console.log(v);     
            $(".book_number").html(v['order_number']);   
            $(".book_date").html(moment(v['order_date']).format("DD-MMM-YYYY, HH:mm"));      
            $("input[name='book_contact_name']").val(v['order_contact_name']);
            $("input[name='book_contact_phone']").val(v['order_contact_phone']);  
            $(".book_checkin_date").html(moment(v['order_item_start_date']).format("DD-MMM-YYYY, HH:mm")+' to '+moment(v['order_item_end_date']).format("DD-MMM-YYYY, HH:mm"));                                                           
            $(".book_room").html(v['branch_name']+' - '+v['product_name']+' - '+v['ref_name']);  
            $(".book_total").html(addCommas(v['order_total']));
            $(".book_total_paid").html(addCommas(v['order_total_paid']));
            $(".book_expired_day").html(v['order_item_expired_day']+' hari lagi');

            $(".btn_send_whatsapp_reminder")
                .attr('data-order-id',v['order_id'])
                .attr('data-order-number',v['order_number'])
                .attr('data-order-date',v['order_date'])
                .attr('data-total',v['order_total'])
                .attr('data-contact-name',v['order_contact_name'])
                .attr('data-contact-phone',v['order_contact_phone'])                                
                .attr('data-remaining',v['order_item_expired_day']) 
                .attr('data-checkin',moment(v['order_item_start_date']).format("DD-MMM-YYYY, HH:mm")+' to '+moment(v['order_item_end_date']).format("DD-MMM-YYYY, HH:mm"))                 
            ;
            $("#modal_booking_cece").modal('show');                            
        });
        $(document).on("click",".btn_send_whatsapp_reminder", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var trans_id = $(this).attr('data-order-id');
            if (parseInt(trans_id) > 0) {
                var params = {
                    trans_id: trans_id,
                    trans_number: $(this).attr('data-order-number'),
                    trans_date: $(this).attr('data-order-date'),
                    trans_total: $(this).attr('data-total'),
                    contact_name: $(this).attr('data-contact-name'),
                    contact_phone: $(this).attr('data-contact-phone'),
                    remaining_day:$(this).attr('data-remaining'),
                    checkin:$(this).attr('data-checkin')                    
                    // contact_emmail: $(this).attr('data-contact-email')
                }
                formSendReceipt(params);
            } else {
                notif(0, 'Data tidak ditemukan');
            }
        });
        function formSendReceipt(params) { //ols is formWhatsApp()
            console.log(params);
            var d = {
                trans_id: params['trans_id'],
                trans_number: params['trans_number'],
                trans_date: params['trans_date'],
                trans_total: params['trans_total'],
                // contact_id: params['contact_id'],
                contact_name: params['contact_name'],
                contact_phone: params['contact_phone'],
                // contact_email: params['contact_email'],
                remaining_day:params['remaining_day'],
                checkin:params['checkin']
            }
            var content = '';
            var ctitle = 'Reminder Perpanjangan';
            content += 'Apakah anda ingin mengirim '+ctitle+' ?<br><br>';
            let title = 'Kirim '+ctitle;
            $.confirm({
                title: title,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation: 'zoom', closeAnimation: 'bottom', animateFromElement: false, useBootstrap: true,
                content: function () {
                    var dsp = '';
                    dsp += content;
                    return dsp;
                },
                onContentReady: function (e) {
                    let self = this;
                    let content = '';
                    let dsp = '';

                    // dsp += '<div>'+content+'</div>';
                    dsp += '<form id="jc_form">';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Tgl CheckIn Sebelumnya</label>';
                        dsp += '        <input id="jc_number" name="jc_number" class="form-control" value="' + d['checkin'] + '" readonly>';
                        dsp += '    </div>';
                        dsp += '</div>';                    
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">';
                        dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-left prs-0">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Sisa Hari</label>';
                        dsp += '        <input id="jc_date" name="jc_date" class="form-control" value="' + d['remaining_day'] + ' hari lagi" readonly>';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-right prs-0">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Total</label>';
                        dsp += '        <input id="jc_total" name="jc_total" class="form-control" value="' + addCommas(d['trans_total']) + '" readonly>';
                        dsp += '    </div>';
                        dsp += '</div>';                        
                    dsp += '</div>';
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">';                    
                        dsp += '<div class="col-md-5 col-xs-5 col-sm-5 padding-remove-left prs-0">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Nama</label>';
                        dsp += '        <input id="jc_contact_name" name="jc_contact_name" class="form-control" value="' + d['contact_name'] + '">';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-7 col-xs-7 col-sm-7 padding-remove-right prs-0">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Whatsapp</label>';
                        dsp += '        <input id="jc_contact_number" name="jc_contact_number" class="form-control" value="' + d['contact_phone'] + '">';
                        dsp += '    </div>';
                        dsp += '</div>';
                    dsp += '</div>';                  
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);
                },
                buttons: {
                    button_1: {
                        text: '<i class="fas fa-paper-plane white"></i> Kirim ',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function (e) {
                            let self = this;

                            let name = self.$content.find('#jc_contact_name').val();
                            let number = self.$content.find('#jc_contact_number').val();

                            if (!name) {
                                $.alert('Nama diisi dahulu');
                                return false;
                            } else if (!number) {
                                $.alert('Nomor WhatsApp diisi dahulu');
                                return false;
                            } else {
                                var data = {
                                    action: 'whatsapp-send-message-reminder-rebooking',
                                    order_id: d['trans_id'],
                                    // contact_id: d['contact_id'],
                                    contact_name: name,
                                    contact_phone: number
                                }
                                $.ajax({
                                    type: "POST",
                                    url: url_message,
                                    data: data,
                                    dataType: 'json',
                                    cache: false,
                                    beforeSend: function () {},
                                    success: function (d) {
                                        let s = d.status;
                                        let m = d.message;
                                        let r = d.result;
                                        if (parseInt(d.status) == 1) {
                                            notif(1, d.message);
                                        } else {
                                            notif(0, d.message);
                                        }
                                    },
                                    error: function (xhr, status, err) {}
                                });
                            }
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-window-close white"></i> Tidak Jadi',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function () {
                            //Close
                        }
                    }
                }
            });
        }  
        // End of Booking
        /* Chart One chart_trans_last_order() */
            var id_chart_one = document.getElementById("chart-one").getContext("2d");
            gradient = id_chart_one.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(105, 220, 219, .1)');
            gradient.addColorStop(0.25, 'rgba(105, 220, 219, .1)');
            gradient.addColorStop(1, 'rgba(105, 220, 219, 1)');
            var config_chart_one = {
                type: 'line',
                data: {
                    labels: ["1", "2", "3", "4", "5", "6"],
                    datasets: [
                        {
                            label: "Pemasukan",
                            data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
                            borderWidth: 2,
                            pointBorderColor: '#36a6a3',
                            pointBackgroundColor: 'white',
                            backgroundColor: '#36a6a36b',
                            borderColor: '#36a6a3'
                        },
                        {
                            label: "Biaya Operasional",
                            data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
                            borderWidth: 2,
                            pointBorderColor: '#ef6605',
                            pointBackgroundColor: 'white',
                            backgroundColor: '#ef66057a',
                            borderColor: '#ef6605'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    title: {
                        display: false,
                        text: 'Chart.js Line Chart - Legend'
                    },
                    animation: {
                        easing: 'easeInOutQuad',
                        duration: 520,
                        onComplete: function () {
                            // var chartInstance = this.chart,
                            // ctx = chartInstance.ctx;

                            // ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            // ctx.textAlign = 'center';
                            // ctx.textBaseline = 'bottom';

                            // this.data.datasets.forEach(function(dataset, i) {
                            //   var meta = chartInstance.controller.getDatasetMeta(i);
                            //   meta.data.forEach(function(bar, index) {
                            //     var data = dataset.data[index];
                            //     ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            //   });
                            // });
                        }
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    elements: {
                        line: {
                            tension: 0.4
                        }
                    },
                    scales: {
                        xAxes: [{
                                type: 'category',
                                lineWidth: 2,
                                display: true,
                                color: 'rgba(200, 200, 200, 0.05)',
                                gridLines: {
                                    color: 'rgba(200, 200, 200, 0.05)',
                                    lineWidth: 2
                                },
                                scaleLabel: {
                                    display: false,
                                    labelString: 'date'
                                }
                            }],
                        yAxes: [
                            {
                                display: true,
                                lineWidth: 1,
                                color: 'rgba(200, 200, 150, 0.08)',
                                gridLines: {
                                    color: 'rgba(200, 200, 200, 0.08)',
                                    lineWidth: 1
                                },
                                scaleLabel: {
                                    display: false,
                                    labelString: 'order'
                                },
                                ticks: {
                                    callback: function (value, index, values) {
                                        var ret = value;
                                        console.log(ret);
                                        return parseInt(ret).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                    }
                                }
                            }
                        ]
                    },
                    point: {
                        backgroundColor: 'white'
                    },
                    tooltips: {
                        titleFontFamily: 'Open Sans',
                        backgroundColor: 'rgba(0,0,0,0.3)',
                        titleFontColor: 'white',
                        caretSize: 5,
                        cornerRadius: 2,
                        xPadding: 10,
                        yPadding: 10
                    }
                }
            };
            window.chart_one = new Chart(id_chart_one, config_chart_one);

        /* Chart Two chart_trans_buy_sell() */
            var id_chart_two = document.getElementById('chart-two').getContext('2d');
            var config_chart_two = {
                type: 'bar',
                data: {
                    labels: ['-', '-', '-', '-', '-', '-'],
                    datasets: [
                        {
                            label: 'Pembelian',
                            data: [30, 10, 20, 45, 25, 15],
                            backgroundColor: '#DB2E59',
                            borderWidth: 4,
                            pointBorderColor: '#DB2E59',
                            pointBackgroundColor: 'white',
                            borderColor: '#DB2E59'
                        }, {
                            label: 'Penjualan',
                            data: [40, 30, 75, 100, 65, 50],
                            backgroundColor: '#0090d9',
                            borderWidth: 4,
                            pointBorderColor: '#0090d9',
                            pointBackgroundColor: 'white',
                            borderColor: '#0090d9'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scaleShowValues: true,
                    title: {
                        display: false,
                        text: 'Grafik Jual Beli'
                    },
                    animation: {
                        easing: 'easeInOutQuad',
                        duration: 520,
                        onComplete: function () {
                            var chartInstance = this.chart,
                                    ctx = chartInstance.ctx;
                            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    // ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                });
                            });
                        }
                    },
                    legend: {
                        position: 'bottom',
                        display: true,
                    },
                    elements: {
                        line: {
                            tension: 0.2
                        }
                    },
                    scales: {
                        xAxes: [
                            {
                                ticks: {
                                    beginAtZero: false,
                                    display: true,
                                    callback: function (value, index, values) {
                                        return value.toLocaleString();
                                    }
                                },
                                gridLines: {
                                    color: 'rgba(200, 200, 200, 0.08)',
                                    lineWidth: 1
                                }
                            }
                        ],
                        yAxes: [{
                                ticks: {
                                    beginAtZero: false,
                                    display: true,
                                    callback: function (value, index, values) {
                                        return value.toLocaleString();
                                    }
                                },
                                gridLines: {
                                    color: 'rgba(200, 200, 200, 0.08)',
                                    lineWidth: 1
                                }
                            }]
                    },
                }
            };
            window.chart_two = new Chart(id_chart_two, config_chart_two);

        /* Chart Three chart_trans_three() */
            var id_chart_three = document.getElementById('chart-three').getContext('2d');
            var config_chart_three = {
                type: 'line',
                data: {
                    labels: ['-', '-', '-', '-', '-', '-'],
                    datasets: [
                        // {
                        //     label: 'Beli',
                        //     data: [30, 10, 20, 45, 25, 15],
                        //     backgroundColor: 'transparent',
                        //     borderWidth: 2,
                        //     pointBorderColor: '#DB2E59',
                        //     pointBackgroundColor: '#DB2E59',
                        //     borderColor: '#DB2E59'
                        // }, 
                        {
                            label: 'Penjualan Kamar',
                            data: [40, 30, 75, 100, 65, 50],
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            pointBorderColor: '#0090d9',
                            pointBackgroundColor: '#0090d9',
                            borderColor: '#0090d9'
                        }, 
                        // {
                        //     label: 'Pemasukan',
                        //     data: [90, 60, 35, 100, 60, 40],
                        //     backgroundColor: 'transparent',
                        //     borderWidth: 2,
                        //     pointBorderColor: '#36a6a3',
                        //     pointBackgroundColor: '#36a6a3',
                        //     borderColor: '#36a6a3'
                        // }, {
                        //     label: 'Biaya',
                        //     data: [50, 40, 20, 80, 50, 20],
                        //     backgroundColor: 'transparent',
                        //     borderWidth: 2,
                        //     pointBorderColor: '#ef6605',
                        //     pointBackgroundColor: '#ef6605',
                        //     borderColor: '#ef6605'
                        // }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    title: {
                        display: false,
                        text: 'Grafik Jual Beli'
                    },
                    animation: {
                        easing: 'easeInOutQuad',
                        duration: 520,
                        onComplete: function () {
                            var chartInstance = this.chart,
                                    ctx = chartInstance.ctx;
                            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    // ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                });
                            });
                        }
                    },
                    legend: {
                        position: 'bottom',
                        display: true,
                    },
                    elements: {
                        line: {
                            tension: 0.1
                        }
                    },
                    scales: {
                        xAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                    callback: function (value, index, values) {
                                        return value.toLocaleString();
                                    }
                                },
                                gridLines: {
                                    color: 'rgba(200, 200, 200, 0.08)',
                                    lineWidth: 1
                                }
                            }
                        ],
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                    callback: function (value, index, values) {
                                        var dsp = '';
                                        // dsp += value.toLocaleString();
                                        dsp += numberToLabel(value);
                                        return dsp;
                                    }
                                },
                                gridLines: {
                                    // color: 'rgba(200, 200, 200, 0.04)',
                                    // lineWidth: 1
                                }
                            }
                        ]
                    },
                    tooltips: 
                    {
                        callbacks: 
                        {
                            label: function(tooltipItem, data) 
                            {
                            const title = data.labels[tooltipItem.index];
                            const dataset = data.datasets[tooltipItem.datasetIndex];
                            const value = dataset.data[tooltipItem.index]; 
                            // return title + ': ' + Number(value).toFixed(2) + "%";   
                                return intlFormat(value.toFixed(2));
                            }
                        },
                    }                    
                }
            };
            window.chart_three = new Chart(id_chart_three, config_chart_three);

        /* Chart Account chart_account_realtime() */
            var id_chart_account = document.getElementById('chart-account').getContext('2d');
            var config_chart_account = {
                type: 'bar',
                data: {
                    labels: ['-', '-', '-', '-', '-', '-'],
                    datasets: [
                        {
                            label: '',
                            data: [100, 100, 100, 100, 100, 100],
                            backgroundColor: ['#36A6A3', '#F99EB4', '#8AC5F3', '#FDE19A', '#BF9EFE', '#FBC48E'],
                            borderWidth: 0,
                            // pointBorderColor: '#36a6a3',
                            // pointBackgroundColor: 'white',
                            // borderColor: '#36a6a3'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scaleShowValues: true,
                    title: {
                        display: false,
                        text: 'Grafik Jual Beli'
                    },
                    animation: {
                        easing: 'easeInOutQuad',
                        duration: 520,
                        onComplete: function () {
                            // var chartInstance = this.chart,
                            // ctx = chartInstance.ctx;

                            // ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            // ctx.textAlign = 'center';
                            // ctx.textBaseline = 'bottom';

                            // this.data.datasets.forEach(function(dataset, i) {
                            //   var meta = chartInstance.controller.getDatasetMeta(i);
                            //   meta.data.forEach(function(bar, index) {
                            //     var data = dataset.data[index];
                            //     ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            //   });
                            // });
                        }
                    },
                    legend: {
                        position: 'bottom',
                        display: false,
                    },
                    elements: {
                        line: {
                            tension: 0.1
                        }
                    },
                    scales: {
                        xAxes: [
                            {
                                ticks: {
                                    beginAtZero: false,
                                    display: true,
                                    callback: function (value, index, values) {
                                        return value.toLocaleString();
                                    }
                                },
                                gridLines: {
                                    color: 'rgba(200, 200, 200, 0.08)',
                                    lineWidth: 1
                                }
                            }
                        ],
                        yAxes: [{
                                ticks: {
                                    beginAtZero: false,
                                    display: true,
                                    callback: function (value, index, values) {
                                        return value.toLocaleString();
                                    }
                                },
                                gridLines: {
                                    color: 'rgba(200, 200, 200, 0.08)',
                                    lineWidth: 1
                                }
                            }]
                    },
                }
            };
            window.chart_account = new Chart(id_chart_account, config_chart_account);
            
        /* Chart Expense chart_account_expense() */
            var id_chart_expense = document.getElementById('chart-expense').getContext('2d');
            var config_chart_expense = {
                type: 'pie',
                data: {
                    labels: ['-', '-', '-', '-', '-', '-'],
                    datasets: [
                        {
                            data: [100, 100, 100, 100, 100, 100],
                            backgroundColor: ['#ff6384', '#4bc0c0', '#36a2eb', '#ffcd56', '#ff9f40'],
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    title: {
                        display: false,
                        text: 'Grafik Jual Beli'
                    },
                    animation: {
                        easing: 'easeInOutQuad',
                        duration: 520,
                    },
                    legend: {
                        position: 'bottom',
                        display: true,
                    },
                    elements: {
                        line: {
                            tension: 0.2
                        }
                    },
                }
            };
            window.chart_expense = new Chart(id_chart_expense, config_chart_expense);

        function chart_trans_last_order(type) { //Chart One 
            var data = {
                action: 'chart-trans-last'
            };
            $.ajax({
                url: '<?= base_url('dashboard/manage') ?>',
                data: data,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                success: function (d) {
                    if (parseInt(d.status) === 1) {
                        // var buy = d.result_buy;
                        // var sell = d.result_sell;
                        var res = d.results;

                        var labels = [];
                        var result_income = [];
                        var result_expense = [];

                        for (var i = 0; i < res.length; i++) {
                            labels.push(res[i].chart_name);
                            result_income.push(parseInt(res[i].chart_income));
                            result_expense.push(parseInt(res[i].chart_expense));
                        }

                        config_chart_one.data.labels = labels;
                        config_chart_one.data.datasets[0].data = result_income;
                        config_chart_one.data.datasets[1].data = result_expense;
                        // config_chart_one.options.scales.yAxes[0].ticks.labels = xlabels;
                        chart_one.update();
                    }
                }
            });
        }
        function chart_trans_buy_sell(type) { //Chart Two
            var data = {
                action: 'chart-trans-buy-sell',
                type: type
            };
            $.ajax({
                url: '<?= base_url('dashboard/manage') ?>',
                data: data,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                success: function (d) {
                    if (parseInt(d.status) === 1) {
                        var buy = d.result_buy;
                        var sell = d.result_sell;
                        var res = d.results;

                        var labels = [];
                        var result_buy = [];
                        var result_sell = [];

                        for (var i = 0; i < res.length; i++) {
                            labels.push(res[i].chart_name);
                            result_buy.push(parseInt(res[i].chart_buy));
                            result_sell.push(parseInt(res[i].chart_sell));
                        }
                        // console.log(labels);
                        config_chart_two.data.labels = labels;
                        config_chart_two.data.datasets[0].data = result_buy;
                        config_chart_two.data.datasets[1].data = result_sell;
                        // config_chart_two.options.scales.yAxes[0].ticks.labels = labels;
                        chart_two.update();
                    }
                }
            });
        }
        function chart_trans_threes(type) { //Chart Three NOT USED
            var data = {
                action: 'chart-trans-buy-sell',
                type: type
            };
            $.ajax({
                url: '<?= base_url('dashboard/manage') ?>',
                data: data,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                success: function (d) {
                    if (parseInt(d.status) === 1) {
                        var buy = d.result_buy;
                        // var sell = d.result_sell;
                        var res = d.results;

                        var labels = [];
                        var result_buy = [];
                        // var result_sell = [];

                        for (var i = 0; i < res.length; i++) {
                            labels.push(res[i].chart_name);
                            result_buy.push(parseInt(res[i].chart_buy));
                            // result_sell.push(parseInt(res[i].chart_sell));
                        }
                        // console.log(labels);
                        config_chart_three.data.labels = labels;
                        config_chart_three.data.datasets.data = result_buy;
                        // config_chart_three.data.datasets[1].data= result_sell;
                        // config_chart_two.options.scales.yAxes[0].ticks.labels = labels;
                        chart_three.update();
                    }
                }
            });
        }
        function chart_trans_three(type) { //Chart Three
            var data = {
                action: 'chart-trans-last',
                type: type
            };
            $.ajax({
                url: '<?= base_url('dashboard/manage') ?>',
                data: data,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                success: function (d) {
                    if (parseInt(d.status) === 1) {
                        // var buy = d.result_buy;
                        // var sell = d.result_sell;

                        // var buy2 = d.result_buy2;
                        // var sell2 = d.result_sell2;

                        var res = d.results;

                        var res2 = d.results2;

                        var labels = [];
                        var result_buy = [];
                        var result_sell = [];
                        var result_income = [];
                        var result_expense = [];

                        // var result_buy2 = [];
                        // var result_sell2 = [];

                        for (var i = 0; i < res.length; i++) {
                            labels.push(res[i].chart_name);
                            result_buy.push(parseInt(res[i].chart_buy));
                            result_sell.push(parseInt(res[i].chart_sell));
                            result_income.push(parseInt(res[i].chart_income));
                            result_expense.push(parseInt(res[i].chart_expense));
                            // result_buy2.push(parseInt(res2[i].chart_buy));
                            // result_sell2.push(parseInt(res2[i].chart_sell));
                        }
                        // console.log(result_buy);
                        // console.log(result_expense);
                        config_chart_three.data.labels = labels;
                        config_chart_three.data.datasets[0].data = result_buy;
                        config_chart_three.data.datasets[1].data = result_sell;
                        config_chart_three.data.datasets[2].data = result_income;
                        config_chart_three.data.datasets[3].data = result_expense;
                        // config_chart_three.data.datasets[2].data= result_buy2;
                        // config_chart_four.data.datasets[3].data= result_sell2;
                        // config_chart_two.options.scales.yAxes[0].ticks.labels = labels;
                        chart_three.update();
                    }
                }
            });
        }
        function chart_account_realtime() { //Chart Account
            var data = {
                action: 'chart-account',
            };
            $.ajax({
                url: '<?= base_url('dashboard/manage') ?>',
                data: data,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                success: function (d) {
                    if (parseInt(d.status) === 1) {
                        $('.account-no-data').remove();
                        $('.data-top-account').remove();
                        var disp = '';
                        $.each(d['results'], function (i, obj) {
                            disp += '<tr class="data-top-account">';
                            disp += '<td class="v-align-middle btn-account-info" data-id="' + obj.account_id + '"><span class="text-danger" style="cursor:pointer;color:#156397;">' + obj.account_name + '</span></td>';
                            disp += '<td class="" style="text-align:right;"><span class="text-default text-right">Rp. ' + obj.balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</span> </td>';
                            // disp += '<td class="text-left;"><span class="text-default">'+obj.last_insert+'</span> </td>';
                            disp += '</tr>';
                        });
                        $(".top-account-data").append(disp);

                        var buy = d.result_buy;
                        var sell = d.result_sell;
                        var res = d.results;

                        var labels = [];
                        var result_buy = [];
                        var result_color = [];

                        var color = '';
                        for (var i = 0; i < res.length; i++) {
                            labels.push(res[i].account_name);
                            result_buy.push(parseInt(res[i].balance));
                            // result_color.push('red');
                        }
                        // backgroundColor: ['#ff6384','#4bc0c0','#36a2eb','#ffcd56','#ff9f40'],
                        // console.log(labels);
                        config_chart_account.data.labels = labels;
                        config_chart_account.data.datasets[0].data = result_buy;
                        // config_chart_account.data.backgroundColor= result_color;

                        // config_chart_two.options.scales.yAxes[0].ticks.labels = labels;
                        chart_account.update();
                    }
                }
            });
        }
        function chart_account_expense() { //Chart Expense
            var data = {
                action: 'chart-expense',
            };
            $.ajax({
                url: '<?= base_url('dashboard/manage') ?>',
                data: data,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                success: function (d) {
                    $('.expense-no-data').remove();
                    if (parseInt(d.status) === 1) {
                        // $('.top-expense-data').remove();             
                        var disp = '';
                        $.each(d['results'], function (i, obj) {
                            disp += '<tr class="data-top-account">';
                            disp += '<td class="v-align-middle btn-account-info" data-id="' + obj.account_id + '"><span class="text-danger" style="cursor:pointer;color:#156397;">' + obj.account_name + '</span></td>';
                            disp += '<td class="" style="text-align:right;"><span class="text-default text-right">Rp. ' + obj.balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</span> </td>';
                            // disp += '<td class="text-left;"><span class="text-default">'+obj.last_insert+'</span> </td>';
                            disp += '</tr>';
                        });
                        // console.log(disp);
                        $(".top-expense-data").append(disp);

                        var buy = d.result_buy;
                        var sell = d.result_sell;
                        var res = d.results;

                        var labels = [];
                        var result_buy = [];
                        var result_color = [];

                        var color = '';
                        for (var i = 0; i < res.length; i++) {
                            labels.push(res[i].account_name);
                            result_buy.push(parseInt(res[i].balance));
                        }
                        config_chart_expense.data.labels = labels;
                        config_chart_expense.data.datasets[0].data = result_buy;
                        // config_chart_two.options.scales.yAxes[0].ticks.labels = labels;
                        chart_expense.update();
                    }
                }
            });
        }
        function chart_recap_all(branch_id){
            var data = {
                action: 'chart-trans-last',
                branch:branch_id
            };
            $.ajax({
                url: '<?= base_url('dashboard/manage') ?>',
                data: data,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                success: function (d) {
                    if (parseInt(d.status) === 1) {
                        var res = d.results;

                        var labels          = [];
                        var result_buy      = [];
                        var result_sell     = [];
                        var result_income   = [];
                        var result_expense  = [];

                        for (var i = 0; i < res.length; i++) {
                            labels.push(res[i].chart_name);
                            // result_buy.push(parseInt(res[i].chart_buy));
                            result_sell.push(parseInt(res[i].chart_sell));
                            // result_income.push(parseInt(res[i].chart_income));
                            // result_expense.push(parseInt(res[i].chart_expense));
                        }

                        config_chart_three.data.labels = labels;
                        // config_chart_three.data.datasets[0].data = result_buy;
                        config_chart_three.data.datasets[0].data = result_sell;
                        // config_chart_three.data.datasets[2].data = result_income;
                        // config_chart_three.data.datasets[3].data = result_expense;
                        chart_three.update();

                        // config_chart_two.data.labels = labels;
                        // config_chart_two.data.datasets[0].data = result_buy;
                        // config_chart_two.data.datasets[1].data = result_sell;                 
                        // chart_two.update();    
                        
                        // config_chart_one.data.labels = labels;
                        // config_chart_one.data.datasets[0].data = result_income;
                        // config_chart_one.data.datasets[1].data = result_expense;                 
                        // chart_one.update();                            
                    }
                }
            });            
        }
        function load_room(branch_id){
            var data = {
                action: 'load-room',
                branch:branch_id
            };
            $.ajax({
                url: '<?= base_url('dashboard/manage') ?>',
                data: data,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                success: function (d) {
                    if (parseInt(d.status) === 1) {          
                        let r = d.result;
                        let total_records = r.length;
                        if(parseInt(total_records) > 0){
                            $("#div_room_status").html('');
                        
                            var dsp = '';
                            r.forEach(async (v, i) => {
                        
                                var sat = 'data-order-id="'+v['order_item_order_id']+'" data-order-item-id="'+v['order_item_id']+'"' 
                                           + 'data-product-id="'+v['product_id']+'"'
                                           + 'data-product-name="'+v['product_name']+'"'
                                           + 'data-ref-name="'+v['ref_name']+'"'                                                                                      
                                ;

                                if(parseInt(v['order_item_id']) > 0){
                                    var scolor = 'background-color: #651215;cursor:pointer;';
                                    var sgues = checkStringLength(v['order_contact_name']);
                                }else{
                                    var scolor = 'background-color: #12651c;';
                                    var sgues = 'Ready';                                    
                                }                                
                                dsp += `
                                    <div class="col-md-2 col-xs-6 div_room_status_child">
                                        <div class="col-md-12 col-xs-12 btn_room_status" style="${scolor}" ${sat}>                                    
                                            <div class="col-md-12 col-xs-12">
                                                <p>
                                                    <b>${v['product_name']}</b><br>
                                                    <b>${checkStringLength(v['ref_name'])}</b>
                                                </p>       
                                            </div>
                                            <div class="col-md-12 col-xs-12">
                                                <p><b>${sgues}</b></p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                    // dsp += '<td>'+v['col_3']+'</td>';
                                    // dsp += '<td>';
                                    //     dsp += '<button type="button" class="btn-action btn btn-primary" data-id="'+v['col_4']+'">';
                                    //     dsp += 'Action';
                                    //     dsp += '</button>';
                                    // dsp += '</td>';
                                // dsp += '</tr>';
                        
                            });
                            $("#div_room_status").html(dsp);
                        }             
                    }
                }
            });            
        }        
        $(document).on("click",".btn_room_status", function(e){
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-order-id');
            if(parseInt(id) > 0){
                let form = new FormData();
                form.append('action', 'read');
                form.append('order_id', id);            
                $.ajax({
                    type: "post",
                    url: '<?= base_url('front_office/booking') ?>',
                    data: form, 
                    dataType: 'json', cache: 'false', 
                    contentType: false, processData: false,
                    beforeSend:function(x){
                        // x.setRequestHeader('Authorization',"Bearer " + bearer_token);
                        // x.setRequestHeader('X-CSRF-TOKEN',csrf_token);
                    },
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        let ri = d.result_item;                    
                        if(parseInt(s) == 1){
                            notif(s,m);
                            $("#modal_booking_read").modal('show');
                            $(".rbook_number").html(':'+r.order_number);
                            $(".rbook_date").html(':'+formatDateTime(r.order_date));   
                            $("#rbook_contact_name").val(ri.order_contact_name);
                            $("#rbook_contact_phone").val(ri.order_contact_phone);     
                            $(".rbook_room").html(': '+ri.product_name + ' / '+ri.ref_name + ' / '+ ri.branch_name);     
                            $(".rbook_total").html(': '+numberWithCommas(r.order_total));                                                                                                                        
                            $(".rbook_checkin_date").html(': '+formatDateTime(ri.order_item_start_date)+' '+formatDateTime(ri.order_item_end_date));
                        }else{
                            notif(s,m);
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                    }
                });
            }else{
                console.log('Booking ID not found');
            }
        });
        
        //Enable 
        // load_room(3);
        /* CARD */
        // total_transaction_month();
        // total_cash_in_month('2,3');
        // total_cash_out_month('1,4,8');

        /* CHART */
        // chart_trans_last_order(1);
        // chart_trans_buy_sell(1);
        // chart_trans_three(1);
        // chart_account_realtime();
        // chart_account_realtime();
        // chart_account_expense();
        chart_recap_all(0); //Need Disable chart_trans_last_order(), chart_trans_buy_sell(), chart_trans_three()

        //Disabled
        // total_transaction_day(1);
        // total_transaction_day(2);
        // top_product(1); 
        top_cece_date_due(); 
        // top_product(2);
        // top_contact(1);
        // top_trans_overdue(1);
        // top_trans_overdue(2);

        /* info payment method */
        // get_payment_method(1);
        // get_payment_method(2);
        // get_payment_method(3);
        // get_payment_method(4);
        // get_payment_method(5);
        // get_payment_method(6);

        /* Dashboard Activity */
        function checkDashboardActivity(limit_start) {
            // $.playSound("http://www.noiseaddicts.com/samples_1w72b820/3721.mp3");
            var awal = $("#filter_date").attr('data-start');
            var akhir = $("#filter_date").attr('data-end');
            var user = $("#dashboard_user").val();
            var data = {
                action: 'dashboard',
                start: awal,
                end: akhir,
                user: user,
                limit_start: limit_start,
                limit_end: 10,
            };
            $.ajax({
                type: "post",
                url: "<?= base_url('aktivitas/manage/'); ?>",
                data: data,
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                    $("#dashboard-notif").append("<div class='loading-pages text-center' style='color: black;padding-top:10px'><i class='fas fa-spinner fa-spin m-2'></i> Sedang Memuat...</div>");
                },
                success: function (d) {
                    if (parseInt(d.total_records) > 0) {
                        $(".loading-pages").remove();
                        $.each(d.result, function (i, val) {

                            var teks = '';
                            if (val.act_action == 1) {
                                teks += '<a href="#">';
                                teks += '<span class="label label-success" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_action_name + '</span>';
                                teks += '</a>';
                                // teks += '<a href="#" style="color:#54BAB9;cursor:default;">'+val.act_action_name;
                                // teks += '</a>';                            
                            } else if (val.act_action == 2) {
                                // var teks_1 = 'membuat';
                                // var teks_2 = '<span class="label label-inverse">'+ val.text2 +'</span>&nbsp;';
                                // var teks_3 = '<span class="label label-success">'+ val.nomor_dokumen +'</span>&nbsp;';
                                // var teks_4 = '<span class="label label-purple"></span>&nbsp;</a>';
                                // var teks_5 = '<span class="label label-red">'+ val.kontak_nama +'</span>&nbsp;';
                                // var teks_6 = '<b>'+ val.text3 +'</b>';
                                teks += val.act_action_name + '&nbsp;';

                                if (val.act_type == 1) {
                                    var color = '#ef6238';
                                } else if (val.act_type == 2) {
                                    var color = '#9465ec';
                                }

                                if (val.act_text_1 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-inverse" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_1 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_2 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-primary" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_2 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_3 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-success" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_3 + '</span>';
                                    teks += '</a>';
                                }
                                // teks += '<a href="#" style="color:#54BAB9;cursor:default;">'+val.act_action_name;
                                // teks += '</a>';              
                            } else if (val.act_action == 3) {
                                // var teks_1 = 'membuat';
                                // var teks_2 = '<span class="label label-inverse">'+ val.text2 +'</span>&nbsp;';
                                // var teks_3 = '<span class="label label-success">'+ val.nomor_dokumen +'</span>&nbsp;';
                                // var teks_4 = '<span class="label label-purple"></span>&nbsp;</a>';
                                // var teks_5 = '<span class="label label-red">'+ val.kontak_nama +'</span>&nbsp;';
                                // var teks_6 = '<b>'+ val.text3 +'</b>';
                                teks += val.act_action_name + '&nbsp;';

                                if (val.act_type == 1) {
                                    var color = '#ef6238';
                                } else if (val.act_type == 2) {
                                    var color = '#9465ec';
                                } else {
                                    var color = '#c0216e';
                                }

                                if (val.act_text_1 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label" style="background-color:' + color + ';color:white;padding:1px 6px;">' + val.act_text_1 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_2 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-success" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_2 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_3 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-inverse" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_3 + '</span>';
                                    teks += '</a>';
                                }
                                // teks += '<a href="#" style="color:#54BAB9;cursor:default;">'+val.act_action_name;
                                // teks += '</a>';              
                            } else if (val.act_action == 4) {
                                teks += val.act_action_name + '&nbsp;';

                                if (val.act_text_1 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-inverse" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_1 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_2 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-primary" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_2 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_3 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-success" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_3 + '</span>';
                                    teks += '</a>';
                                }
                            } else if (val.act_action == 5) {
                                teks += val.act_action_name + '&nbsp;';

                                if (val.act_text_1 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label" style="background-color:' + color + ';color:white;padding:1px 6px;">' + val.act_text_1 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_2 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-danger" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_2 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_3 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-inverse" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_3 + '</span>';
                                    teks += '</a>';
                                }
                            } else if (val.act_action == 6) {
                                teks += val.act_action_name + '&nbsp;';

                                if (val.act_text_1 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label" style="background-color:#ff6384;color:white;padding:1px 6px;">' + val.act_text_1 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_2 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-danger" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_2 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_3 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-inverse" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_3 + '</span>';
                                    teks += '</a>';
                                }
                            } else if (val.act_action == 7) {
                                teks += val.act_action_name + '&nbsp;';

                                if (val.act_text_1 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-success" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_1 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_2 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-success" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_2 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_3 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-success" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_3 + '</span>';
                                    teks += '</a>';
                                }
                            } else if (val.act_action == 8) {
                                teks += val.act_action_name + '&nbsp;';

                                if (val.act_text_1 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-danger" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_1 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_2 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-danger" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_2 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_3 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-danger" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_3 + '</span>';
                                    teks += '</a>';
                                }
                            } else if (val.act_action == 9) {
                                // teks += val.act_action_name + '&nbsp;';
                                teks += val.act_action_name;
                                // if(val.act_text_1 !== 0){
                                // 	teks += val.act_text_1+'&nbsp;';
                                // }

                                if (val.act_text_1 !== 0) {
                                    // teks += '<a href="#">';
                                    // teks += '<span class="label" style="background-color:#ef6605;color:white;padding:1px 6px;">' + val.act_text_1 + '</span>';
                                    // teks += '</a>&nbsp';
                                    // teks += val.act_text_1;
                                    teks += val.act_text_1.toLowerCase()+'&nbsp;';
                                }

                                if (val.act_text_2 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label" style="padding:1px 6px;">' + val.act_text_2 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_3 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label" style="padding:1px 6px;">' + val.act_text_3 + '</span>';
                                    teks += '</a>';
                                }
                                
                                if (val.act_text_4 !== 0) {
                                    var si = '';
                                    if(val.act_text_4 == "Approve"){
                                        si = '<span class="label" style="background-color:#008dd5;color:white;padding:1px 6px;"><i class="fas fa-check" style="font-size:12px;"></i> ' + val.act_text_4 + '</span>';                                        
                                    }else if(val.act_text_4 == "Tolak"){
                                        si = '<span class="label" style="background-color:#d72d57;color:white;padding:1px 6px;"><i class="fas fa-times" style="font-size:12px;"></i> ' + val.act_text_4 + '</span>';
                                    }else if(val.act_text_4 == "Tunda"){
                                        si = '<span class="label" style="background-color:#ee6605;color:white;padding:1px 6px;"><i class="fas fa-hand-paper" style="font-size:12px;"></i> ' + val.act_text_4 + '</span>';
                                    }else if(val.act_text_4 == "Hapus"){
                                        si = '<span class="label" style="background-color:#d72d57;color:white;padding:1px 6px;"><i class="fas fa-trash" style="font-size:12px;"></i> ' + val.act_text_4 + '</span>';
                                    }else{
                                        si = 'Error';
                                    }                                    
                                    teks += '&nbsp;<a href="#">';
                                    teks += si;
                                    teks += '</a>';
                                    teks += '&nbsp; '+val.act_text_5;
                                }                                
                            } else if (val.act_action == 10) {
                                teks += val.act_action_name + '&nbsp;';

                                // if(val.act_text_1 !== 0){
                                // 	teks += val.act_text_1+'&nbsp;';
                                // }

                                if (val.act_text_1 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label" style="color:black;padding:1px 6px;"><i class="' + val.act_icon + '"></i>&nbsp;' + val.act_text_1 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_2 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-inverse" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_2 + '</span>';
                                    teks += '</a>&nbsp';
                                }

                                if (val.act_text_3 !== 0) {
                                    teks += '<a href="#">';
                                    teks += '<span class="label label-danger" style="background-color:#54BAB9;color:white;padding:1px 6px;">' + val.act_text_3 + '</span>';
                                    teks += '</a>';
                                }
                            }
                            // else if(val.text1 == "menerbitkan"){
                            //   var teks_1 = 'menerbitkan';
                            //   var teks_2 = '<span class="label label-inverse">'+ val.text2 +'</span>&nbsp;';
                            //   var teks_3 = '<span class="label label-success">'+ val.nomor_dokumen +'</span>&nbsp;';
                            //   var teks_4 = '<span class="label label-purple"></span>&nbsp;</a>';
                            //   var teks_5 = '<span class="label label-red">'+ val.kontak_nama +'</span>&nbsp;';
                            //   var teks_6 = '<b>'+ val.text3 +'</b>';
                            // }else if(val.text1 == "menambahkan"){
                            //   var teks_1 = 'menambahkan';
                            //   var teks_2 = '<span class="label label-success">'+ val.text2 +'</span>&nbsp;';
                            //   var teks_3 = '<span class="label label-default">'+ val.text3 +'</span>&nbsp;';
                            //   var teks_4 = '<span class="label label-inverse">'+ val.text4 +'</span>&nbsp;</a>';
                            //   var teks_5 = '<span class="label label-red"></span>&nbsp;';
                            //   var teks_6 = '<b></b>';
                            // }else if(val.text1 == "APPROVE"){
                            //   var teks_1 = '<span class="label label-success"><i class="fa fa-check"></i> Approved</span></a>';
                            //   var teks_2 = '<span class="label label-inverse">'+ val.text2 +'</span>&nbsp;';
                            //   var teks_3 = '<span class="label label-primary">'+ val.nomor_dokumen +'</span>&nbsp;';
                            //   var teks_4 = '<span class="label label-primary"></span>&nbsp;</a>';
                            //   var teks_5 = '<span class="label label-red"></span>&nbsp;';
                            //   var teks_6 = '<b></b>';
                            // }else if(val.text1 == "TOLAK"){
                            //   var teks_1 = '<span class="label label-danger"><i class="fa hand-paper-o"></i> Tolak</span></a>';
                            //   var teks_2 = '<span class="label label-inverse">'+ val.text2 +'</span>&nbsp;';
                            //   var teks_3 = '<span class="label label-primary">'+ val.nomor_dokumen +'</span>&nbsp;';
                            //   var teks_4 = '<span class="label label-primary"></span>&nbsp;</a>';
                            //   var teks_5 = '<span class="label label-red"></span>&nbsp;';
                            //   var teks_6 = '<b></b>';
                            // }else if(val.text1 == "membatalkan"){
                            //   var teks_1 = 'membatalkan';
                            //   var teks_2 = '<span class="label label-inverse">'+ val.text2 +'</span>&nbsp;';
                            //   var teks_3 = '<span class="label label-success">'+ val.nomor_dokumen +'</span>&nbsp;';
                            //   var teks_4 = ''+ val.text4 +'&nbsp';
                            //   var teks_5 = '<span class="label label-default">'+ val.karyawan_nama +'</span>&nbsp;';
                            //   var teks_6 = '';
                            // }
                            else {
                                teks += 'error fetch the content';
                            }

                            if (val.user_firstname == "") {
                                var display_name = val.user_username;
                            } else {
                                var display_name = val.user_firstname;
                            }

                            $("#dashboard-notif").append('' +
                                    '<div class="p-t-10 b-b b-grey">' +
                                    '<div class="post overlap-left-10">' +
                                    '<div class="user-profile-pic-wrapper">' +
                                    '<div class="user-profile-pic-2x tiles label-black white-border">' +
                                    '<div class="text-white inherit-size p-t-10 p-l-15">' +
                                    '<i class="fa fa-user fa-lg"></i> ' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="info-wrapper small-width">' +
                                    '<div class="info text-black">' +
                                    '<p>' +
                                    '<a href="#"><b>' + val.user + '&nbsp;</b></a>&nbsp;' +
                                    teks +
                                    // '<span>'+teks +'</span>'+
                                    // teks_3 +
                                    '<span class="label" style="background-color:#7484e6;color:white;"></span>' +
                                    '<a href="#"><span class="label label-primary"></span></a>' +
                                    '</p>' +
                                    '<p class="muted small-text">' + val.date_time + '</p>' +
                                    '</div>' +
                                    '<div class="clearfix"></div>' +
                                    '</div>' +
                                    '<div class="clearfix"></div>' +
                                    '</div>' +
                                    '');
                        });
                        next_ = true;
                    } else {
                        next_ = false;
                        limit_start = 1;
                        $(".loading-pages").remove();
                        // $("#dashboard-notif").append("<div class='loading-pages text-center' style='color: black;padding-top:10px'>Tidak ada aktifitas</div>");
                    }
                    // console.log('checkDashboardActivity => '+limit_start+','+data.limit_end+' Next : '+next_);
                    // }
                },
                error: function (data) {
                    // checkInternet('offline');
                }
            });
            var waktu = setTimeout("checkDashboardActivity()", 6000000);
        }
        function intlFormat(num) {
            return new Intl.NumberFormat().format(Math.round(num * 10) / 10);
        }
        function numberToLabel(num) {
            if (num >= 1000000)
                return intlFormat(num / 1000000) + 'M';
            if (num >= 1000)
                return intlFormat(num / 1000) + 'k';
            return intlFormat(num);
        }
        function checkStringLength(str) {
            // Check if the length of the string is greater than 10 characters
            if (str.length > 13) {
                // Return only the first 5 characters
                return str.substring(0, 12)+'...';
            } else {
                // Return the full string
                return str;
            }
        }
        function formatDateTime(input) {
            // Split the input into date and time parts
            const [datePart, timePart] = input.split(' ');

            // Split the date part into year, month, and day
            const [year, month, day] = datePart.split('-');

            // Convert the month number to month name
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            const monthName = monthNames[parseInt(month) - 1];

            // Format the time to 'HH:MM' format
            const time = timePart.substring(0, 5);

            // Return the formatted date and time
            return `${parseInt(day)} ${monthName} ${year}, ${time}`;
        }        
    });
    // End1 of document ready

    /* Info Selling 19 Load */
    var url = "<?= base_url('dashboard/manage'); ?>";
    var url_trans = "<?= base_url('transaksi/manage'); ?>";


    function top_contact() { console.log('top_contact() 1');
        var start = $("#start").val();
        var end = $("#end").val();
        var data = {
            action: 'finance-list-top-contact',
            type: [2, 3],
            limit: 5,
            start: start,
            end: end
        };
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: 'json',
            cache: 'false',
            beforeSend: function () {},
            success: function (d) {
                if (parseInt(d.status) === 1) {
                    var datas = d.result;
                    //Prepare List Contact
                    $("#table-top-contact tbody").html('');
                    if (parseInt(datas.length) > 0) {
                        $("#top_contact").show(300);
                        var dsp = '';
                        $.each(datas, function (i, val) {
                            dsp += '<tr>';
                            dsp += '<td class="v-align-middle btn-contact-info" data-id="' + val['contact_id'] + '" data-type="trans" data-trans-type=""><span><a href="#" style="cursor:pointer;color:#156397;">' + val['name'] + '</a></span></td>';
                            dsp += '<td class="text-right"><span>Rp. ' + addCommas(val['total']) + '</span></td>';
                            // dsp += '<td class="v-align-middle">'+val['last_insert']+'</td>';
                            dsp += '</tr>';
                        });
                    } else {
                        $("#top_contact").hide(300);                        
                        dsp += '<tr>';
                        dsp += '<td class="text-center" colspan="2">Tidak ada data</td>';
                        dsp += '</tr>';
                    }
                    $("#table-top-contact tbody").html(dsp);
                } else {
                    notifError(d.message);
                }
            },
            error: function (xhr, Status, err) {
                notifError(err);
            }
        });
    }
    function top_cece_date_due() { console.log('top_cece_date_due() 1');
        var start = $("#start").val();
        var end = $("#end").val();
        var data = {
            action: 'fo-cece-date-due',
            // type: [2, 3],
            limit: 5,
            // start: start,
            // end: end
        };
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: 'json',
            cache: 'false',
            beforeSend: function () {},
            success: function (d) {
                if (parseInt(d.status) === 1) {
                    var datas = d.result;
                    //Prepare List Contact
                    $("#table_top_date_due tbody").html('');
                    if (parseInt(datas.length) > 0) {
                        // $("#top_contact").show(300);
                        var dsp = '';
                        $.each(datas, function (i, val) {
                            var lbl = '[' + val['branch_name'] + '] '+ val['product_name'];

                            if(val['order_item_expired_day_2'] < 0){
                                var exp = val['order_item_expired_day_2'] + ' lewat';
                            }else{
                                var exp = val['order_item_expired_day_2'] + ' hari lagi';
                            }
                            // var dd = {
                            //     'data-id':val['order_item_id'],
                            //     'data-value':JSON.stringify(val)
                            // };
                            // console.log(dd);
                            var vvv = btoa(JSON.stringify(val));
                            // var vvv = JSON.stringify(val);
                            dsp += '<tr>';
                            dsp += '<td class="v-align-middle"><span>'+
                               '<a class="btn_booking_info" data-order-id="' + val['order_id'] + '" data-order-item-id="'+ val['order_item_id']+'" data-order="'+vvv+'" href="#" style="cursor:pointer;color:#156397;">' + 
                                lbl + 
                                '</a></span></td>';
                                dsp += '<td class="text-left"><span>' + moment(val['order_item_start_date']).format("DD-MMM-YY") + ' sd ' + moment(val['order_item_end_date']).format("DD-MMM-YY") + '</span></td>';
                                dsp += '<td class="text-right"><span>' + exp + '</span></td>';
                            // dsp += '<td class="v-align-middle">'+val['last_insert']+'</td>';
                            dsp += '</tr>';
                        });
                    } else {
                        $("#top_date_due").hide(300);                        
                        dsp += '<tr>';
                        dsp += '<td class="text-center" colspan="3">Tidak ada data</td>';
                        dsp += '</tr>';
                    }
                    $("#table_top_date_due tbody").html(dsp);
                } else {
                    notifError(d.message);
                }
            },
            error: function (xhr, Status, err) {
                notifError(err);
            }
        });
    }    
    function top_trans_overdue(type) { console.log('top_trans_overdue() 2');
        var data = {
            action: 'trans-unpaid-and-overdue',
            type: type
        };
        $.ajax({
            type: "post",
            url: url_trans,
            data: data,
            dataType: 'json',
            cache: 'false',
            beforeSend: function () {},
            success: function (d) {
                if (parseInt(d.status) === 1) {
                    var datas = d.result;

                    if (type == 1) {
                        $("#table-top-buy-overdue tbody").html('');
                        if (parseInt(datas.length) > 0) {
                            $("#top_buy_overdue").show(300);                                
                            var dsp = '';
                            $.each(datas, function (i, val) {
                                dsp += '<tr>';
                                dsp += '<td class="v-align-middle"><span><a href="#" style="cursor:pointer;color:#156397;">' + val['label'] + '</a></span></td>';
                                dsp += '<td class="text-right"><span>Rp. ' + addCommas(val['total']) + '</span></td>';
                                // dsp += '<td class="v-align-middle">'+val['last_insert']+'</td>';
                                dsp += '</tr>';
                            });
                        } else {
                            $("#top_buy_overdue").hide(300);                                
                            dsp += '<tr>';
                            dsp += '<td class="text-center" colspan="2">Tidak ada data</td>';
                            dsp += '</tr>';
                        }
                        $("#table-top-buy-overdue tbody").html(dsp);
                    } else if (type == 2) {
                        $("#table-top-sell-overdue tbody").html('');
                        if (parseInt(datas.length) > 0) {
                            $("#top_sell_overdue").show(300);                                      
                            var dsp = '';
                            $.each(datas, function (i, val) {
                                dsp += '<tr>';
                                dsp += '<td class="v-align-middle"><span><a href="#" style="cursor:pointer;color:#156397;">' + val['label'] + '</a></span></td>';
                                dsp += '<td class="text-right"><span>Rp. ' + addCommas(val['total']) + '</span></td>';
                                // dsp += '<td class="v-align-middle">'+val['last_insert']+'</td>';
                                dsp += '</tr>';
                            });
                        } else {
                            $("#top_sell_overdue").hide(300);                                  
                            dsp += '<tr>';
                            dsp += '<td class="text-center" colspan="2">Tidak ada data</td>';
                            dsp += '</tr>';
                        }
                        $("#table-top-sell-overdue tbody").html(dsp);
                    }
                } else {
                    notifError(d.message);
                }
            },
            error: function (xhr, Status, err) {
                notifError(err);
            }
        });
    }
    function top_product(type) { console.log('top_product() 2');
        var request = 'top-product';
        var data = {
            action: request,
            type: type
        };
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: 'json',
            cache: false,
            success: function (d) {

                if (parseInt(type) == 1) {
                    $('.buy-no-data').remove();
                    $('.data-top-buy').remove();
                    var disp = '';
                    if (d['result'].length > 0) {
                        $("#top_buy_data").show(300);
                        $.each(d['result'], function (i, obj) {
                            disp += '<tr class="data-top-buy">';
                            disp += '<td class="v-align-middle btn-header-product-stock-min-track" data-id="' + obj.product_id + '" data-name="' + obj.product_name + '"><span class="text-danger" style="cursor:pointer;">' + obj.product_name + '</span></td>';
                            // disp += '<td><span>Rp. '+obj.trans_item_in_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'</span> </td>';
                            disp += '<td class="text-right"><span>' + addCommas(obj.total_item_qty) + ' ' + obj.trans_item_unit + '</span> </td>';
                            // disp += '<td><span>'+obj.time_ago+'</span> </td>';
                            disp += '</tr>';
                        });
                    } else {
                        $("#top_buy_data").css('display','none');
                        disp += '<tr class="buy-no-data"><td colspan="3" style="text-align: center;">-- Data tidak tersedia --</td></tr>';
                    }
                    $(".top-buy-data").append(disp);
                } else if (parseInt(type) == 2) {
                    $('.sell-no-data').remove();
                    $('.data-top-sell').remove();
                    var disp = '';
                    if (d['result'].length > 0) {
                        $("#top_sell_data").hide(300);                        
                        $.each(d['result'], function (i, obj) {
                            disp += '<tr class="data-top-sell">';
                            disp += '<td class="v-align-middle btn-header-product-stock-min-track" data-id="' + obj.product_id + '" data-name="' + obj.product_name + '"><span class="text-success" style="cursor:pointer;">' + obj.product_name + '</span></td>';
                            // disp += '<td><span>Rp. '+obj.trans_item_sell_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'</span> </td>';
                            disp += '<td class="text-right"><span>' + addCommas(obj.total_item_qty) + ' ' + obj.trans_item_unit + '</span> </td>';
                            // disp += '<td><span>'+obj.time_ago+'</span> </td>';
                            disp += '</tr>';
                        });
                    } else {
                        $("#top_sell_data").css('display','none');                        
                        disp += '<tr class="sell-no-data"><td colspan="3" style="text-align: center;">-- Data tidak tersedia --</td></tr>';
                    }
                    $(".top-sell-data").append(disp);
                }
            },
            error: function (data) {
            }
        });
    }    
    function total_cash_in_month(type) { console.log('total_cash_in_month() 1');
        var request = 'total-cash-in-month';
        var data = {
            action: request,
            type: type
        };
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: 'json',
            cache: false,
            asynch: true,
            success: function (d) {
                var total = '';
                if (d['result'].total_cash_in == null) {
                    total = 0;
                } else {
                    // total = d['result'].total_cash_in.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    total = addCommas(d.result.total_cash_in);
                }

                // if(parseInt(type)==1){
                // $('#total-buy-month').text('Rp. '+total+'');
                // }else if(parseInt(type)==2){
                $('#total-cash-in-month').text('Rp. ' + total + '');
                // }else{

                // }
            },
            error: function (data) {
            }
        });
    }
    function total_cash_out_month(type) { console.log('total_cash_out_month() 1');
        var request = 'total-cash-out-month';
        var data = {
            action: request,
            type: type
        };
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: 'json',
            cache: false,
            asynch: true,
            success: function (d) {
                var total = '';
                if (d['result'].total_cash_out == null) {
                    total = 0;
                } else {
                    // total = d['result'].total_cash_out.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    total = addCommas(d.result.total_cash_out);
                }

                // if(parseInt(type)==1){
                // $('#total-buy-month').text('Rp. '+total+'');
                // }else if(parseInt(type)==2){
                $('#total-cash-out-month').text('Rp. ' + total + '');
                // }else{

                // }
            },
            error: function (data) {
            }
        });
    }
    function total_transaction_month() { console.log('total_transaction_month() 2');
        var request = 'total-transaction-month';
        var data = {
            action: request
        };
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: 'json',
            cache: false,
            asynch: true,
            success: function (d) {
                var total_records = d.result;
                for(let a=0; a < total_records.length; a++) {
                    $('#total-buy-month').text('Rp. ' + addCommas(d.result[0].total_month) + '');
                    $('#total-sell-month').text('Rp. ' + addCommas(d.result[1].total_month) + '');
                }
            },
            error: function (data) {
            }
        });
    }
    function total_transaction_day(type) { console.log('total_transaction_day() 2');
        var request = 'total-transaction-day';
        var data = {
            action: request,
            type: type
        };
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: 'json',
            cache: false,
            success: function (d) {
                var total = '';
                if (d['result'].total_transaction_day == null) {
                    total = 0;
                } else {
                    // total = d['result'].total_transaction_day.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    total = addCommas(d.result.total_transaction_day);
                }

                if (parseInt(type) == 1) {
                    $('.total-buy-day').text('Rp. ' + total + '');
                } else if (parseInt(type) == 2) {
                    $('.total-sell-day').text('Rp. ' + total + '');
                }
            },
            error: function (data) {
            }
        });
    }
    /*
        function get_payment_method($payment_method){
            var request = 'get-payment-method';
            var data = {
                request: request,
                payment_method: $payment_method
            };
            $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: 'json',
            cache: false,
            success: function(d){
                if(d.method == 1){
                $('.payment.cash').text(d.result.total_paid_type);
                }else if(d.method == 2){
                $('.payment.card').text(d.result.total_paid_type);
                }else if(d.method == 3){
                $('.payment.dana').text(d.result.total_paid_type);
                }else if(d.method == 4){
                $('.payment.gopay').text(d.result.total_paid_type);
                }else if(d.method == 5){
                $('.payment.ovo').text(d.result.total_paid_type);
                }else if(d.method == 6){
                $('.payment.shopeepay').text(d.result.total_paid_type);
                }
            },
            error : function(data){
            }
        });
    }*/
    function checkApprovalRequest() { console.log('checkApprovalRequest() 1');
        $.ajax({
            type: "post",
            data: {
                action: 'load-approval-list'
            },
            // url : "http://localhost:8888/git/gps/services/controls/Approval.php?action=load-data-by-user-session",
            url: "<?= base_url('approval'); ?>",
            success: function (d) {
                var dsp = '';
                var result = JSON.parse(d);
                // Jumlah Permintaan
                var total = result['total_records'];
                $("#badge-permintaan-approval").html(total);

                // Tampilkan Element jika ada
                if (total > 0) {
                    $("#panel-zero").show('slow');
                    $.each(result['result'], function (i, item) {
                        
                        if(item.approval_from_table == 'orders'){
                            var dtype = 'order';
                            var binfo = "btn-contact-info";
                            var surl = '<?php echo base_url('order/prints/'); ?>';
                        }else if(item.approval_from_table == 'trans'){
                            var dtype = 'trans';
                            var surl = '<?php echo base_url('transaksi/prints/'); ?>';
                            var binfo = "btn-contact-info";                                                        
                        }
                        dsp += '<tr>';
                            dsp += '<td>';
                            dsp += '<b><a href="#" class="btn-approval-user" data-user-id="' + item.user_from_id + '" data-user-name="' + item.user_from_username + '">' + item.user_from_username + '</a></b>';
                            dsp += '' + item.text_short + '';
                            dsp += '<b><a href="#" target="_blank" style="cursor:pointer;padding-top:4px;color:#0d638f;" class="btn-approval-print" data-url="' + surl + item.trans_id + '">';
                            dsp += '<i class="fas fa-file-signature"></i>&nbsp;' + item.trans_number + '</a></b>';
                            dsp += ' untuk <a href="#" class="btn-contact-info" data-id="'+item.contact_id+'" data-type="'+dtype+'" style="cursor:pointer;"><b>@' + item.contact_name + '</b></a>';
                            dsp += ' total <b>Rp' + addCommas(item.trans_total) + '</b>';
                            dsp += '&nbsp;<button class="btn btn-mini btn-primary btn-approval-action" data-approval-session="' + item.approval_session + '" data-trans-session="' + item.approval_from_session + '" data-trans-number="' + item.trans_number + '" data-trans-total="' + item.trans_total + '" data-contact-name="' + item.contact_name + '"><i class="fas fa-check"></i>Konfirmasi</button>';
                            dsp += '</td>';
                            dsp += '<td style="text-align:right;">';
                            dsp += item.time_ago + '';
                            dsp += '</td>';
                        dsp += '</tr>';
                    });
                    $("#table-request-approval tbody").html(dsp);
                } else {
                    $("#panel-zero").hide('slow');
                    $("#table-request-approval tbody").html('');
                }
            }
        });
        // var waktu = setTimeout("checkApprovalRequest()",6000000);
    }

    //Additional
    function notif($type,$msg) {
        if (parseInt($type) === 1) {
            //Toastr.success($msg);
            Toast.fire({
            type: 'success',
            title: $msg
            });
        } else if (parseInt($type) === 0) {
            //Toastr.error($msg);
            Toast.fire({
            type: 'error',
            title: $msg
            });
        }
    }    
    function loader($stat) {
        if ($stat == 1) {
            swal({
                title: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
                html: '<span style="font-size: 14px;">Loading...</span>',
                width: '20%',
                showConfirmButton: false,
                allowOutsideClick: false
            });
        } else if ($stat == 0) {
            swal.close();
        }
    }
    function modal_form(params) {
        $("#modal-form .modal-title").html(params['title']);
        $("#modal-form #modal-size").addClass('modal-' + params['size']);
        var button = params['button'].length;
        console.log(button);
        for (var i = 0; i < parseInt(button); i++) {
            console.log(params.button[i]);
        }
        $("#modal-form").modal({backdrop: 'static', keyboard: false});
    }    

    // checkApprovalRequest();

</script>