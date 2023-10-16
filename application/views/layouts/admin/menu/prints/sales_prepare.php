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
    margin-top: 4px;
    margin-bottom: 4px;
  }
  .footer-box > div{
    border: 1px solid #eaeaea;
    height: 120px;
  }
  .footer-box > div > div > h5{
    text-align: center;
    margin-top:4px;
    margin-bottom:2px;
  }    
</style>
<body>
  
<div class="container-fluid">
  <div class="row">
    <div class="col-md-5 col-sm-12 col-xs-12" style="border:1px solid gray;">
      <!-- HEADER -->
      <div class="col-md-12 col-sm-12 col-xs-12">
        <p>
          <div class="col-xs-3" style="padding-left:0px;">
            <!-- <img src="<?php echo $branch_logo;?>" class="img-responsive" style="width: 134px;"> -->
            <!-- <b onclick="window.print();" style="cursor:pointer;"><?php echo $title; ?></b><br> -->
            <p style="text-align:left;">
              <b><?php echo $result['branch']['branch_name'];?></b><br>
              <!--<?php echo $result['branch']['branch_address'];?><br>
              Tel:<?php echo $result['branch']['branch_phone_1'];?>, 
              Email:<?php echo $result['branch']['branch_email_1'];?>  -->         
            </p>              
          </div>
          <div class="col-xs-6 text-center">
          
          </div>  
          <div class="col-xs-3 text-left">
            <!-- <?php echo $header['order_number'];?> -->
          </div>                        
        </p>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:4px;">
        <p>
          <div class="col-xs-5" style="padding-left:0;">
            <b>Customer:</b><br>
            <?php echo $header['contact_name'];?><br>
            <!-- <?php echo $header['contact_address'];?><br> -->
            <!-- <?php echo $header['contact_phone_1'];?><br> -->
            <!-- <?php echo $header['contact_email_1'];?><br>                                               -->
          </div>
          <div class="col-xs-7">
            <table>
              <tr><td class="text-left">Nomor</td><td>: <b onclick="window.print();" style="cursor:pointer;"><?php echo $header['order_number'];?></b></td></tr>
              <tr><td class="text-left">Tanggal</td><td>: <?php echo date("d-M-Y", strtotime($header['order_date']));?></td></tr>
              <!-- <tr><td>Masa Berlaku</td><td>: <?php echo date("d-M-Y", strtotime($header['order_date_due']));?></td></tr> -->
              <!-- <tr><td>Nomor Referensi Customer</td><td>:<?php echo $header['order_ref_number'];?></td></tr> -->
            </table>
          </div>          
        </p>
      </div>      

      <!-- CONTENT -->
      <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:15px;">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-left">Deskripsi</th>
            <th class="text-right">Qty</th>
            <th class="text-left">Satuan</th>
            <th class="text-left">Checkbox</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $num = 1;
          $subtotal = 0;
          foreach($content as $v){

            if(!empty($v['order_item_note'])){
              $order_note = '<br><b><i>'.$v['order_item_note'].'</i></b>';
            }else{
              $order_note = '-';
            }

            $subtotal = $subtotal + $v['order_item_total'];

            $discount_percentage = '';
            $discount_nominal = $v['order_item_discount'];            
            if($v['order_item_discount'] > 0){
              $discount_percentage = number_format(($v['order_item_discount']/$v['order_item_price']) * 100, 0);
              $discount_percentage = $discount_percentage.'%';
            }

            if($v['order_item_flag'] == 1){
              $flag = 'Check';
            }else{
              $flag = 'Unchecked';
            }

            echo '<tr>';
              echo '<td>'.$v['product_name'].'</td>';
              echo '<td style="text-align:right;">'.number_format($v['order_item_qty'],2,'.',',').'</td>';
              echo '<td style="text-align:left;">'.$v['order_item_unit'].'</td>';       
              echo '<td>'.$flag.'</td>';        
            echo '</tr>';
          }

            //Subtotal
            // echo '<tr>';
            //   echo '<td colspan="3"></td>';
            //   echo '<td style="text-align:right"><b>Grand Total</b></td>';
            //   echo '<td style="text-align:right">'.number_format($subtotal,2,'.',',').'</td>';
            // echo '</tr>';                        
          ?>
        </tbody>
      </table>
      </div> 
       
      <div class="col-md-12 col-xs-12" style="">
        Keterangan: <?php echo $header['order_note'];?><br>
      </div>        

      <!-- FOOTER -->         
      <div class="col-md-12 col-sm-12 col-xs-12 footer-box">
        <div class="col-md-6 col-xs-6">
          <div class="col-md-12 col-xs-12"><h5>Prepare Oleh</h5></div>
          <div class="col-md-12 col-xs-12">
            <p style="text-align: center;margin-top:20px;">
              <?php echo ucwords($header['user_username']);?><br>
              <?php echo date("d-M-Y, H:i", strtotime($header['order_date_created']));?>
            </p>            
          </div>          
        </div>                 
        <div class="col-md-6 col-xs-6">
          <div class="col-md-12 col-xs-12"><h5>Mengetahui</h5></div>
          <div class="col-md-12 col-xs-12">&nbsp;</div>          
        </div>
  
    </div>
  </div>
</div>

<script>
</script>
</body>
</html>