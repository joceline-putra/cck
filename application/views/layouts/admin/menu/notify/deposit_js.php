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
        d.action = 'deposit_load';
        d.date_start = $("#start").val();
        d.date_end = $("#end").val();        
        d.length = $("#filter_length").find(':selected').val();
        d.filter_flag = $("#filter_flag").find(':selected').val();
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
      {"targets":2, "title":"Jumlah","searchable":true,"orderable":true,"className":'text-right'},
      {"targets":3, "title":"User","searchable":true,"orderable":true},
      {"targets":4, "title":"Action","searchable":false,"orderable":false}    
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
          dsp += '&nbsp;'+row.balance_number+'<br>';
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
          dsp += addCommas(row.balance_debit);
          return dsp;
        }        
      },{
        'data': 'balance_credit',
        className: 'text-right',
        render: function(data, meta, row) {
          var dsp = '';
          dsp += row.user_username+'<br>';
          dsp += row.user_phone_1+'<br>';
          return dsp;
        }
      },{
        'data': 'balance',
        className: 'text-right',
        render: function(data, meta, row) {
          var dsp = '';
          // dsp += addCommas(row.balance);
          // return dsp;
          if(row.balance_flag == 0){
            dsp += '<button class="btn-update btn btn-mini btn-primary" data-session="'+ row.balance_session +'" data-debit="'+addCommas(row.balance_debit)+'">';
            dsp += '<span class="fas fa-check"></span>Apprrove';
            dsp += '</button>&nbsp;';
            dsp += '<button class="btn-edit btn btn-mini btn-danger btn-delete" data-session="'+row.balance_session+'" data-debit="'+addCommas(row.balance_debit)+'"><span class="fas fa-trash"></span></button>&nbsp;';
          }else if(row.balance_flag == 1){
            dsp += '<label class="label label-primary"><span class="fas fa-thumbs-up"></span> Confirmed</label>';
          }else if(row.balance_flag == 4){
            dsp += '<label class="label label-danger"><span class="fas fa-backspace"></span> Deleted</label>';
          }          
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
  $(document).on("click","#filter_flag",function(e) {
    index.ajax.reload();
  });

  // Edit Button
  $(document).on("click",".btn-update",function() {
    event.preventDefault();
    var session = $(this).attr("data-session");
    var debit = $(this).attr("data-debit");    
    $.confirm({
      title: 'Konfirmasi Pembayaran!',
      content: 'Apakah anda ingin konfirmasi pembayaran <b>'+session+'</b> dengan nominal <b>'+debit+'</b> ?',
      buttons: {
        confirm:{ 
          btnClass: 'btn-danger',
          text: 'Ya',
          action: function () {
            var data = {
              action: 'deposit_update',
              session:session,
              status:1
            }
            $.ajax({
              type: "POST",     
              url : url,     
              data: data,
              dataType:'json',
              success:function(d){
                if(parseInt(d.status)==1){ 
                  notif(1,d.message); 
                  index.ajax.reload(null,false);
                }else{ 
                  notif(0,d.message); 
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
  $(document).on("click",".btn-delete",function() {
    event.preventDefault();
    var session = $(this).attr("data-session");
    var debit = $(this).attr("data-debit");

    $.confirm({
      title: 'Hapus!',
      content: 'Apakah anda ingin menghapus <b>'+session+'</b> dengan nominal <b>'+debit+'</b> ?',
      buttons: {
        confirm:{ 
          btnClass: 'btn-danger',
          text: 'Ya',
          action: function () {
            var data = {
              action: 'deposit_delete',
              session:session,
              status:4
            }
            $.ajax({
              type: "POST",     
              url : url,     
              data: data,
              dataType:'json',
              success:function(d){
                if(parseInt(d.status)==1){ 
                  notif(1,d.message); 
                  index.ajax.reload(null,false);
                }else{ 
                  notif(0,d.message); 
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
});
</script>