<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="description" content="Webpage description goes here" />
  <meta charset="utf-8">
  <title><?php echo $title; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="">
  <link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
</head>
<style>
  body{
    font-family: monospace;
  }
  .title{
    font-weight: 800;
    text-transform: uppercase;
    text-align: left;
  }
</style>
<body>
  
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 col-sm-12 col-xs-12" style="border:1px solid gray;">
      <!-- HEADER -->
      <div class="col-md-12 col-sm-12 col-xs-12">
        <p>
          <div class="col-xs-3">
            <img src="<?php echo $branch_logo;?>" class="img-responsive" style="width: 134px;">
          </div>
          <div class="col-xs-3">
          </div>
          <div class="col-xs-6 text-left">
            <p style="text-align:left;">
              <b><?php echo $result['branch']['branch_name'];?></b><br>
              <?php echo $result['branch']['branch_address'];?><br>
              Tel:<?php echo $result['branch']['branch_phone_1'];?>, 
              Email:<?php echo $result['branch']['branch_email_1'];?>          
            </p>            
          </div>          
        </p>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <p class="title"><?php echo $title;?></p>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:4px;">
        <p>
          <div class="col-xs-6" style="padding-left:0;">
            <table>
              <tr><td class="text-left">Nomor</td><td>: <b onclick="window.print();" style="cursor:pointer;"><?php echo $header['journal_number'];?></b></td></tr>
              <tr><td class="text-left">Tanggal</td><td>: <?php echo date("d-M-Y", strtotime($header['journal_date']));?></td></tr>
            </table>
          </div>
          <div class="col-xs-6">                                 
          </div>
        </p>
      </div>      

      <!-- CONTENT -->
      <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:15px;">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-left">Transfer Dari Akun [Kredit]</th>
              <th class="text-left">Transfer Ke Akun [Debit]</th>              
            </tr>
          </thead>
          <tbody>
            <tr>
            <?php
            $subtotal_debit = 0;
            $subtotal_credit = 0;            
            foreach($content as $v){
              // echo '<tr>';
              if(!empty($v['journal_item_note'])){
                $order_note = '<br><b><i>'.$v['journal_item_note'].'</i></b>';
              }else{
                $order_note = '-';
              }

              $subtotal_debit = $subtotal_debit + $v['journal_item_debit'];
              $subtotal_credit = $subtotal_credit + $v['journal_item_credit'];              
                echo '<td>['.$v['account_code'].']&nbsp;&nbsp;'.$v['account_name'].'</td>';
                // echo '<td>'.$header['journal_total'].'</td>';                
            }
              echo '</tr>';
              echo '<tr>';
                echo '<td colspan="4"><b>Sebesar: </b>'.$journal_total_raw.'</b></td>';
              echo '</tr>'; 
              echo '<tr>';
                echo '<td colspan="4"><b>Terbilang: </b>'.$journal_total.'</b></td>';
              echo '</tr>';              
            ?>
          </tbody>
        </table>
      </div>  
      <div class="col-md-12 col-xs-12" style="">
        Keterangan: <?php echo $header['journal_note'];?><br>
      </div>  

      <!-- FOOTER -->
      <style>
        .footer-box{
          margin-top: 25px;
          margin-bottom: 25px;          
        }
        .footer-box > div{
          border: 1px solid gray;
          height: 150px;
        }
        .footer-box > div > div > h5{
          text-align: center;
        }
      </style>
      <div class="col-md-12 col-sm-12 col-xs-12 footer-box">
        <div class="col-md-3 col-xs-4">
          <div class="col-md-12 col-xs-12"><h5>Dibuat Oleh</h5></div>
          <div class="col-md-12 col-xs-12">&nbsp;</div>          
        </div>
        <div class="col-md-3 col-xs-4">
          <div class="col-md-12 col-xs-12"><h5>Diperiksa Oleh</h5></div>
          <div class="col-md-12 col-xs-12">&nbsp;</div>          
        </div>        
        <div class="col-md-3 col-xs-4">
          <div class="col-md-12 col-xs-12"><h5>Disetujui Oleh</h5></div>
          <div class="col-md-12 col-xs-12">&nbsp;</div>          
        </div>                
        <div class="col-md-3 col-xs-4">
          <div class="col-md-12 col-xs-12"><h5>Diterima Oleh</h5></div>
          <div class="col-md-12 col-xs-12">&nbsp;</div>          
        </div>                        
      </div>        
    </div>
  </div>
</div>

<script>
</script>
</body>
</html>