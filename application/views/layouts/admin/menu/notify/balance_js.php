<script>
$(document).ready(function() {   

  var identity = 0;
  var view = "<?php echo $_view; ?>";  
  var url = "<?= base_url('notify'); ?>"; 
  var url_image = "<?= site_url('upload/noimage.png'); ?>";
  $(".nav-tabs").find('li[class="active"]').removeClass('active');
  $(".nav-tabs").find('li[data-name="'+view+'"]').addClass('active');
  $("#start, #end").datepicker({
    // defaultDate: new Date(),
    format: 'dd-mm-yyyy',
    autoclose: true,
    enableOnReadOnly: true,
    language: "id",
    todayHighlight: true,
    weekStart: 1 
  }).on('changeDate', function(){ 
    index.ajax.reload(); 
  }); 

  // $("#filter_search").focus();
  var index = $("#table-data").DataTable({
    // "processing": true,
    "serverSide": true,
    "ajax": {
      url: url,
      type: 'post',
      dataType: 'json',
      cache: 'false',
      data: function(d) {
        d.action = 'balance_load_report';
        d.date_start = $("#start").val();
        d.date_end = $("#end").val();        
        d.length = $("#filter_length").find(':selected').val();
        // d.filter_bank = $("#filter_bank").find(':selected').val();        
        d.search = {
          value:$("#filter_search").val()
        };
      },
      dataSrc: function(data) {
        return data.result;
      }
    },
    "columnDefs": [
      {"targets":0, "title":"Tanggal","searchable":true,"orderable":true},
      {"targets":1, "title":"Keterangan","searchable":true,"orderable":true},
      {"targets":2, "title":"Masuk","searchable":true,"orderable":true,"className":'text-right'},            
      {"targets":3, "title":"Keluar","searchable":true,"orderable":true},
      {"targets":4, "title":"Saldo","searchable":false,"orderable":false}    
    ],
    "order": [
      [0, 'desc']
    ],
    "columns": [{
        'data': 'balance_id',
        className: 'text-left',
        render: function(data, meta, row) {
          var dsp = '';
            dsp += moment(row.balance_date).format("DD-MMM-YYYY HH:mm");
            dsp += '<br><label class="label">'+row.balance_date_time_ago+'</label>';
          return dsp;
        }
      },{
        'data': 'balance_type_name',
        className: 'text-left',
        render: function(data, meta, row) {
          var dsp = '';
          if(parseInt(row.balance_position) == 1){
            dsp += '<i class="fas fa-arrow-alt-circle-down" style="color:#099a8c;"></i>';
          }else if(parseInt(row.balance_position) == 2){
            dsp += '<i class="fas fa-arrow-alt-circle-up" style="color:#f35958;"></i>';
          }else{
            dsp += 'Error';
          }          
          dsp += '&nbsp;'+row.balance_type_name+'<br>';
          // if(row.balance_note != undefined){
            // dsp += row.balance_note;
          // }else{ dsp += '-'; }
          return dsp;
        }
      },{
        'data': 'balance_debit',
        className: 'text-right',
        render: function(data, meta, row) {
          var dsp = '';
          dsp += addCommas(row.debit);
          return dsp;
        }        
      },{
        'data': 'balance_credit',
        className: 'text-right',
        render: function(data, meta, row) {
          var dsp = '';
          dsp += addCommas(row.credit);
          return dsp;
        }
      },{
        'data': 'balance',
        className: 'text-right',
        render: function(data, meta, row) {
          var dsp = '';
          dsp += addCommas(row.balance);
          return dsp;
          // dsp += '<button class="btn-edit btn btn-mini btn-primary" data-s="'+ row.balance_session +'">';
          // dsp += '<span class="fas fa-edit"></span>View';
          // dsp += '</button>';
          
          return dsp;
        }
    }]
  });

  //Datatable Config
  $("#table-data_filter").css('display','none');  
  $("#table-data_length").css('display','none');
  $("#filter_length").on('change', function(e){
    var value = $(this).find(':selected').val(); 
    $('select[name="table-data_length"]').val(value).trigger('change');
    index.ajax.reload();     
  });     
  $("#filter_flag").on('change', function(e){ index.ajax.reload(); });      
  $("#filter_bank").on('change', function(e){ index.ajax.reload(); });        
  $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ index.ajax.reload(); }else if(parseInt(ln) < 1){ index.ajax.reload();} });        
  $('#table-data').on('page.dt', function () {
    var info = index.page.info();
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

    $(document).on("click","#btn_balance_deposit",function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log($(this));
        var id = $(this).attr('data-id');

        $.confirm({
            title: 'Deposit Uang',
            columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
            autoClose: 'button_2|100000',
            closeIcon: true,
            closeIconClass: 'fas fa-times', 
            animation:'zoom',
            closeAnimation:'bottom',
            animateFromElement:false,         
            content: function(){        
                var self = this;
                var ds = '';
                ds += '<div class="form-group">';
                ds += '<div class="controls">';
                    ds += '<label>Nominal Deposit</label>';
                    // ds += '<input type="password" name="cpassword" id="cpassword" style="width:100%">';
                    ds += '<select class="form-control" id="nominal">';
                    ds += '<option value="15000">Rp 15.000</option>';
                    ds += '<option value="50000">Rp 50.000</option>';
                    ds += '<option value="100000">Rp 100.000</option>';
                    ds += '<option value="250000">Rp 250.000</option>';
                    ds += '<option value="500000">Rp 500.000</option>';                    
                    ds += '<option value="1000000">Rp 1.000.000</option>';                    
                    ds += '</select>';
                ds += '</div>';
                ds += '</div>';              
                return ds;
            },
            onContentReady: function(){
                // alert('A');
                //this.setContentAppend('<div>Content ready!</div>');
                // bind to events
                //var jc = this;
                //this.$content.find('form').on('submit', function (e) {
                    // if the user submits the form by pressing enter in the field.
                    //e.preventDefault();
                    //jc.$$formSubmit.trigger('click'); // reference the button and click it
                //});        
            },
            buttons: {
                button_1: {
                text:'Ok [Enter]',
                btnClass: 'btn-primary',
                keys: ['enter'],
                action: function(){
                    var slct = this.$content.find('#nominal :selected').val();

                    // if(slct == ''){
                    //   $.alert('Password tidak boleh kosong');
                    //     return false;
                    // }
                    
                    var data = {
                        action: 'balance_create_debit',
                        type:1,
                        debit:slct
                    };
                    $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    dataType: 'json',
                    success:function(d){
                        if(d.status == 1){
                        notif(1,d.message);
                        }else{
                        notif(0,d.message);
                        }
                    }
                    });
                }
                },
                button_2: {
                text: 'Close [Esc]',
                btnClass: 'btn-danger',
                keys: ['Escape'],
                action: function(){
                    //Close
                }
                }
            }
        });
    });
  // Edit Button
  $(document).on("click",".btn-edit",function(e) {
    e.preventDefault();
    var s = $(this).data("s");
    var data = {
      action: 'balance_read',
      s:s
    }
    $.ajax({
      type: "POST",     
      url: url,
      data: data,
      dataType:'json',
      cache: false,
      success:function(d){
        if(parseInt(d.status)==1){ /* Success Message */
          notif(1,d.message);
        }else{
          notif(0,d.message);
        }
      }
    });  
  });
    function checkBalance(){
        $.ajax({
            type: "post",
            data:{
                action:'balance_load_user_balance'
            },
            url: url,
            dataType:'json',
            success:function(d){
                $("#btn_balance_end").html('<i class="fa fa-bullseye"></i> Poin Anda Rp. <b>'+addCommas(d.result.balance)+'</b>');
            }
        });
        var waktu = setTimeout("checkBalance()",6000000);
    }
    checkBalance();
});
</script>