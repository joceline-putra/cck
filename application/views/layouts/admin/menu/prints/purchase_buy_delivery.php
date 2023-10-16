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
  .footer-box{
    margin-top: 25px;
    margin-bottom: 25px;
  }
  .footer-box > div{
    border: 1px solid gray;
    height: 200px;
  }
  .footer-box > div > div > h5{
    text-align: center;
  }  
</style>
<body>
  
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 col-sm-12 col-xs-12" style="">
      <!-- HEADER -->
      <div class="col-md-12 col-sm-12 col-xs-12">
        <p>
          <div class="col-xs-3">
            <img src="<?php echo $branch_logo;?>" class="img-responsive" style="width: 134px;">
          </div>
          <div class="col-xs-6">
            <p style="text-align:center;">
              <b><?php echo $result['branch']['branch_name'];?></b><br>
              <?php echo $result['branch']['branch_address'];?><br>
              Tel:<?php echo $result['branch']['branch_phone_1'];?>, 
              Email:<?php echo $result['branch']['branch_email_1'];?>          
            </p>
          </div>
          <div class="col-xs-3 text-right">
            <b onclick="window.print();" style="cursor:pointer;"><?php echo $title; ?></b><br>
            <?php echo $header['trans_number'];?>
          </div>          
        </p>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:4px;">
        <p>
          <div class="col-xs-6" style="padding-left:0;">
            <b>Supplier:</b><br>
            <?php echo $header['contact_name'];?><br>
            <?php echo $header['contact_address'];?><br>
            <?php echo $header['contact_phone_1'];?><br>
            <?php echo $header['contact_email_1'];?><br>                                              
          </div>
          <div class="col-xs-6">
            <table>
              <tr><td>Tanggal</td><td>: <?php echo date("d-M-Y", strtotime($header['trans_date']));?></td></tr>
              <tr><td>Tanggal Jth Tempo</td><td>: <?php echo date("d-M-Y", strtotime($header['trans_date_due']));?></td></tr>
              <!-- <tr><td>Sales</td><td>:</td></tr> -->
              <tr><td>Nomor Referensi</td><td>:<?php echo $header['trans_ref_number'];?></td></tr>                           
            </table>
          </div>          
        </p>
      </div>      

    <!-- CONTENT -->
      <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:15px;">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-right">No</th>
            <th class="text-left">Produk</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Box</th>                       
          </tr>
        </thead>
        <tbody>
          <?php
          $num = 1;
          $total_sub = 0;
          $total_ppn = 0;
          $total_discount = 0;
          $total_grand = 0;
          foreach($content as $v){
            echo '<tr>';
              echo '<td class="text-right">'.$num++.'</td>';
              echo '<td>'.$v['product_name'].'</td>';
              echo '<td style="text-align:right;">'.number_format($v['trans_item_out_qty'],2,'.',',').' '.$v['trans_item_unit'].'</td>';
              echo '<td style="text-align:right;">'.number_format($v['trans_item_pack'],2,'.',',').' '.$v['trans_item_unit'].'</td>';                           
            echo '</tr>';
          }                                   
          ?>
        </tbody>
      </table>
      </div>  
      <div class="col-md-12 col-xs-12" style="">
        Keterangan:<?php echo $header['trans_note'];?>
        <br>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12 footer-box">
        <div class="col-md-3 col-xs-4">
          <div class="col-md-12 col-xs-12"><h5>Dibuat Oleh</h5></div>
          <div class="col-md-12 col-xs-12">
            <p style="text-align: center;margin-top:60px;">
              <?php echo ucwords($result['footer']['user_creator']['user_name']);?><br>
              <?php echo date("d-M-Y, H:i", strtotime($result['header']['trans_date_created']));?>
            </p>            
          </div>          
        </div>
        <div class="col-md-3 col-xs-4">
          <div class="col-md-12 col-xs-12"><h5>Driver</h5></div>
          <div class="col-md-12 col-xs-12">
            <p style="text-align: center;margin-top:60px;"></p>
          </div>          
        </div>                
        <div class="col-md-3 col-xs-4">
          <div class="col-md-12 col-xs-12"><h5>Penerima</h5></div>
          <div class="col-md-12 col-xs-12">&nbsp;</div>          
        </div>
        <div class="col-md-3 col-xs-4">
          <div class="col-md-12 col-xs-12"><h5>Approval</h5></div>
          <div class="col-md-12 col-xs-12">
          <?php 
            // $set_qrcode = base_url('prints/print_history/').$header['trans_session'];
          ?>
          <!-- <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=<?php echo $set_qrcode;?>&choe=UTF-8" title="Link to Google.com" /> -->
          </div>          
        </div>        
      </div>
      <div class="col-md-12 col-xs-12" style="">
      </div>                 
    </div>
  </div>
</div>

<script>
</script>
</body>
</html>