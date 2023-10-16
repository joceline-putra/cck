
<script>
$(document).ready(function() {
  // $("#modal-statistic").modal('toggle');  
  var url = "<?= base_url('dashboard/manage'); ?>";   
  // var csrfData = {};
  // csrfData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
  // var csrfData = '<?php echo $this->security->get_csrf_hash(); ?>';

  // Auto Set Navigation
  var identity = "<?php echo $identity; ?>";
  var menu_link = "<?php echo $_view;?>";
  $(".nav-tabs").find('li[class="active"]').removeClass('active');
  $(".nav-tabs").find('li[data-name="statistic"]').addClass('active');    
  
  // var url = "<?= base_url('keuangan/manage'); ?>";
  var url_print = "<?= base_url('keuangan/print_operasional'); ?>"; 
  var url_print_all = "<?= base_url('report/report_operasional'); ?>";   
  // $("select").select2();

  var start = $("#start").val();
  var end = $("#end").val();

  $("#start, #end").datepicker({
    // defaultDate: new Date(),
    format: 'dd-mm-yyyy',
    autoclose: true,
    enableOnReadOnly: true,
    language: "id",
    todayHighlight: true,
    weekStart: 1 
  }).on('change', function(){
    
    chart_one1(start,end);
    // chart_two(start,end);
    total_cash_balance();
    total_cash_in_month(); //3,8
    total_cash_out_month(); //4,8,9

    top_cash_bank();
    top_contact();
    top_expense();
  }); 

  // 1. Chart Configuration
  var config_chart_one    = {
    type: 'pie',
    data: {
      datasets: [
        {
          data: [10,10,10,10,10],
          backgroundColor:['#F64971','#F88D32','#FBC445','#F95AEF','#2D8FE6'],
        }
      ],
      labels: [
        'Data 1',
        'Data 2',
        'Data 3',
        'Data 4',
        'Data 5'
      ]
    },
    options: {
      title:{
        display:false,
        text:'Chart One'
      },      
      responsive: true,
      maintainAspectRatio: true,
      animation: {
        easing: 'easeInOutQuad',
        duration: 520
      },      
      legend:{
        position:'right'
      },
      elements: {
        line: {
          tension: 0.4
        }
      },      
      plugins:{
        labels:{
          render:'percentage',
          fontColor:'white',
          fontStyle:'bold'
        }
      }
    }
  };
  var config_chart_two    = {
    type: 'bar',
    data: {
      datasets: [
        {
          label:'Target',
          data: [10,18,56],
          backgroundColor:['#F64971','#F64971','#F64971'],          
        },{
          label:'Actual',
          data: [15,25,43],
          backgroundColor:['#36A6A3','#36A6A3','216160'],
        }
      ],
      labels: [
        'Data 1',
        'Data 2',
        'Data 3'
      ]
    },
    options: {
      title:{
        display:false,
        text:'Chart Actual & Target'
      },      
      responsive: true,
      maintainAspectRatio: true,
      animation: {
        easing: 'easeInOutQuad',
        duration: 520
      },
      elements: {
        line: {
          tension: 0.4
        }
      },      
      legend: {
        position: 'bottom',
        display: true,
      },  
      point: {
        backgroundColor: 'white'
      },          
      scales: {
        yAxes: [{
          display: true,
          color: 'rgba(200, 200, 200, 0.05)',
          gridLines: {
            color: 'rgba(200, 200, 200, 0.05)',
            lineWidth: 1
          },          
          ticks: {
            beginAtZero: true,
            callback: function(value, index, values) {
              return value.toLocaleString();
            }                                    
          }
        }],
        xAxes:[{
          display:true,
          color: 'rgba(200, 200, 200, 0.05)',
          gridLines: {
            color: 'rgba(200, 200, 200, 0.05)',
            lineWidth: 1
          },                  
        }]
      }
    }
  }; 

  // 2. Chart Setup
  var id_chart_one    = document.getElementById('chart-one').getContext('2d');  
  var id_chart_two    = document.getElementById('chart-two').getContext('2d');   

  // 3. Chart Load
  window.chart_one    = new Chart(id_chart_one, config_chart_one);
  window.chart_two    = new Chart(id_chart_two, config_chart_two);

  function chart_one1(start,end){
    var data = {
      action: 'finance-list-top-cost-out',
      type: [4,9],
      start: start,
      end: end
    };
    $.ajax({
      type: "post",
      url: url,
      data: data,
      dataType: 'json',
      cache: 'false',
      beforeSend:function(){},
      success:function(d){
        if (parseInt(d.status) === 1){
          var datas = d.result;
          var labels = [];
          var xlabels = [];
          for (var i = 0; i < datas.length; i++) {
            labels.push(datas[i].name);
            xlabels.push(datas[i].total);      
          }
          config_chart_one.data.labels = labels;
          config_chart_one.data.datasets[0].data = xlabels;   
          chart_one.update();
        }else{
          notifError(d.message);
        }
      },
      error:function(xhr, Status, err){
        notifError(err);
      }
    });
  }

  function chart_two(start,end){
    var prepare = {
      tipe: identity,
      start: start,
      end: end
    };
    var prepare_data = JSON.stringify(prepare);
    var data = {
      action: 'action_name',
      data: prepare_data
    };
    $.ajax({
      type: "post",
      url: url,
      data: data,
      dataType: 'json',
      cache: 'false',    
      beforeSend:function(){},
      success:function(d){
        if (parseInt(d.status) === 1){
          notifSuccess(d.message);
        }else{
          notifError(d.message);
        }            
      },
      error:function(xhr, Status, err){
        notifError(err);
      }
    });
  }

  function total_cash_balance(type){
    var request = 'total-cash-balance';     
    var data = {
      action:request,
      type:type,
      start:$("#start").val(),
      end:$("#end").val(),      
    };
    $.ajax({ 
       type: "post",
       url: url, 
       data: data,
       dataType: 'json',
       cache: false, 
       asynch:true,
       success: function(d){
        var total = '';
        if(d['result'].total_cash_balance == null){
          total = 0;
        }else{
          total = d['result'].total_cash_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        $('#total-cash-balance').text('Rp. '+total+'');
       },
       error : function(data){          
       } 
    }); 
  }
  function total_cash_in_month(){
    var request = 'total-cash-in-month';     
    var data = {
      action:request,
      type:[3,8],
      start:$("#start").val(),
      end:$("#end").val(),      
    };
    $.ajax({ 
       type: "post",
       url: url, 
       data: data,
       dataType: 'json',
       cache: false, 
       asynch:true,
       success: function(d){
        var total = '';
        if(d['result'].total_cash_in == null){
          total = 0;
        }else{
          total = d['result'].total_cash_in.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // if(parseInt(type)==1){
          // $('#total-buy-month').text('Rp. '+total+'');
        // }else if(parseInt(type)==2){
          $('#total-cash-in-month').text('Rp. '+total+'');          
        // }else{

        // }
       },
       error : function(data){          
       } 
    }); 
  }    
  function total_cash_out_month(){
    var request = 'total-cash-out-month';     
    var data = {
      action:request,
      type:[4,8,9],
      start:$("#start").val(),
      end:$("#end").val(),      
    };
    $.ajax({ 
       type: "post",
       url: url, 
       data: data,
       dataType: 'json',
       cache: false, 
       asynch:true,
       success: function(d){
        var total = '';
        if(d['result'].total_cash_out == null){
          total = 0;
        }else{
          total = d['result'].total_cash_out.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // if(parseInt(type)==1){
          // $('#total-buy-month').text('Rp. '+total+'');
        // }else if(parseInt(type)==2){
          $('#total-cash-out-month').text('Rp. '+total+'');          
        // }else{

        // }
       },
       error : function(data){          
       } 
    }); 
  }  
  function top_cash_bank(){
    var start = $("#start").val();
    var end = $("#end").val();    
    var data = {
      action: 'finance-list-top-cash-bank',
      type: 3,
      limit:4,
      start: start,
      end: end
    };
    $.ajax({
      type: "post",
      url: url,
      data: data,
      dataType: 'json',
      cache: 'false',
      beforeSend:function(){},
      success:function(d){
        if (parseInt(d.status) === 1){
          var datas = d.result;
          //Prepare List Contact
          $("#table-top-cash-bank tbody").html('');          
          if(parseInt(datas.length) > 0){          
            var dsp = '';    
            $.each(datas, function (i, val) {
              dsp += '<tr>';
                dsp += '<td class="v-align-middle"><span class="muted bold text-primary">'+val['name']+'</span></td>';
                dsp += '<td class="text-right"><span class="muted bold text-success">Rp. '+addCommas(val['total'])+'</span></td>';
                dsp += '<td class="v-align-middle"></td>';
              dsp += '</tr>';
            });
          }else{
            dsp += '<tr>';
            dsp += '<td class="text-center" colspan="3">Tidak ada data</td>';
            dsp += '</tr>';
          }
          $("#table-top-cash-bank tbody").html(dsp);
        }else{
          notifError(d.message);
        }
      },
      error:function(xhr, Status, err){
        notifError(err);
      }
    });
  }
  function top_contact(){
    var start = $("#start").val();
    var end = $("#end").val();    
    var data = {
      action: 'finance-list-top-contact',
      type:[3],
      limit:4,
      start: start,
      end: end
    };
    $.ajax({
      type: "post",
      url: url,
      data: data,
      dataType: 'json',
      cache: 'false',
      beforeSend:function(){},
      success:function(d){
        if (parseInt(d.status) === 1){
          var datas = d.result;
          //Prepare List Contact
          $("#table-top-contact tbody").html('');          
          if(parseInt(datas.length) > 0){
            var dsp = '';    
            $.each(datas, function (i, val) {
              dsp += '<tr>';
                dsp += '<td class="v-align-middle"><span class="muted bold text-primary">'+val['name']+'</span></td>';
                dsp += '<td class="text-right"><span class="muted bold text-success">Rp. '+addCommas(val['total'])+'</span></td>';
                dsp += '<td class="v-align-middle"></td>';
              dsp += '</tr>';
            });
          }else{
            dsp += '<tr>';
            dsp += '<td class="text-center" colspan="3">Tidak ada data</td>';
            dsp += '</tr>';
          }                              
          $("#table-top-contact tbody").html(dsp);
        }else{
          notifError(d.message);
        }
      },
      error:function(xhr, Status, err){
        notifError(err);
      }
    });
  }
  function top_expense(){
    var start = $("#start").val();
    var end = $("#end").val();    
    var data = {
      action: 'finance-list-top-cost-out',
      type:[4,8,9],
      limit:4,
      start: start,
      end: end
    };
    $.ajax({
      type: "post",
      url: url,
      data: data,
      dataType: 'json',
      cache: 'false',
      beforeSend:function(){},
      success:function(d){
        if (parseInt(d.status) === 1){
          var datas = d.result;
          //Prepare List Expense
          $("#table-top-expense tbody").html('');          
          if(parseInt(datas.length) > 0){
            var dsp = '';    
            $.each(datas, function (i, val) {
              dsp += '<tr>';
                dsp += '<td class="v-align-middle"><span class="muted bold text-primary">'+val['name']+'</span></td>';
                dsp += '<td class="text-right"><span class="muted bold text-success">Rp. '+addCommas(val['total'])+'</span></td>';
                dsp += '<td class="v-align-middle"></td>';
              dsp += '</tr>';
            });
          }else{
            dsp += '<tr>';
            dsp += '<td class="text-center" colspan="3">Tidak ada data</td>';
            dsp += '</tr>';
          }                              
          $("#table-top-expense tbody").html(dsp);
        }else{
          notifError(d.message);
        }
      },
      error:function(xhr, Status, err){
        notifError(err);
      }
    });
  }  
  function addCommas(string){
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
  $(document).on("click",".btn-show-all",function(e) {
    e.preventDefault();
    e.stopPropagation();
    var id = $(this).attr('data-id');
    $.alert('Sedang dikerjakan');
    // $("#modal-statistic").modal('toggle');
    /*
    var prepare = {
      id: $("#BY_DATA_ID").attr('data-id'),
      code: $("#BY_INPUT").val(),    
      name: $("#BY_SELECT").find(':selected').val()    
    };
    var prepare_data = JSON.stringify(prepare);
    var data = {
      action: 'action_name',
      data: prepare_data
    };
    $.ajax({
        type: "post/get",
        url: url,
        data: data,
        dataType: 'json',
        cache: 'false',    
        beforeSend:function(){},
        success:function(d){
          if (parseInt(d.status) === 1){
            notifSuccess(d.message);
            }else{
                notifError(d.message);
            }            
        },
        error:function(xhr, Status, err){
            notifError(err);
        }
    });
    */
  });

  chart_one1(start,end);
  // chart_two2(start,end);
  total_cash_balance();
  total_cash_in_month();
  total_cash_out_month();

  top_cash_bank();
  top_contact();
  top_expense();  
});
</script>