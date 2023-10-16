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
          <div class="col-xs-6 text-left">
            <p style="text-align:left;">
              <b><?php echo $result['branch']['branch_name'];?></b><br>
              <?php echo $result['branch']['branch_address'];?><br>
              Tel:<?php echo $result['branch']['branch_phone_1'];?>, 
              Email:<?php echo $result['branch']['branch_email_1'];?>          
            </p>            
          </div> 
          <div class="col-xs-3 text-right">
            <b onclick="window.print();" style="cursor:pointer;"><?php echo $title; ?></b><br>
            <?php echo $header['order_number'];?>
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
              <tr><td class="text-left">Nomor</td><td>: <b onclick="window.print();" style="cursor:pointer;"><?php echo $header['order_number'];?></b></td></tr>
              <tr><td class="text-left">Tanggal</td><td>: <?php echo date("d-M-Y", strtotime($header['order_date']));?></td></tr>
              <tr><td>Masa Berlaku</td><td>: <?php echo date("d-M-Y", strtotime($header['order_date_due']));?></td></tr>
              <tr><td class="text-left">Referensi</td><td>: <?php echo $header['order_ref_number'];?></td></tr>
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
            <th class="text-right">Satuan</th>
            <th class="text-right">Harga</th>          
            <th class="text-right">Total</th>            
          </tr>
        </thead>
        <tbody>
          <?php
          $num = 1;
          $subtotal = 0;
          $dp = 0;
          $total = 0;
          foreach($content as $v){

            if(!empty($v['order_item_note'])){
              $order_note = '<br><b><i>'.$v['order_item_note'].'</i></b>';
            }else{
              $order_note = '-';
            }

            $subtotal = $subtotal + $v['order_item_total'];
            echo '<tr>';
              echo '<td>'.$v['product_name'].'</td>';
              echo '<td style="text-align:right;">'.number_format($v['order_item_qty'],2,'.',',').'</td>';
              echo '<td style="text-align:right;">'.$v['order_item_unit'].'</td>';
              echo '<td style="text-align:right;">'.number_format($v['order_item_price'],2,'.',',').'</td>';              
              echo '<td style="text-align:right;">'.number_format($v['order_item_total'],2,'.',',').'</td>';                
            echo '</tr>';
          }

            //Subtotal
            echo '<tr>';
              echo '<td colspan="3"></td>';
              echo '<td style="text-align:right"><b>Subtotal</b></td>';
              echo '<td style="text-align:right">'.number_format($subtotal,2,'.',',').'</td>';
            echo '</tr>';

            if($header['order_with_dp'] > 0){
              echo '<tr>';
                echo '<td colspan="3"></td>';
                echo '<td style="text-align:right"><b>';
                echo 'Down Payment</b><br>'.$header['account_code'].' - '.$header['account_name'];
                echo '</td>';
                echo '<td style="text-align:right">'.number_format($header['order_with_dp'],2,'.',',').'</td>';
              echo '</tr>';          
              $dp = $header['order_with_dp'];    
            }                

            $total = $subtotal - $dp;
            echo '<tr>';
              echo '<td colspan="3"></td>';
              echo '<td style="text-align:right"><b>Total</b></td>';
              echo '<td style="text-align:right">'.number_format($total,2,'.',',').'</td>';
            echo '</tr>';                    
          ?>
        </tbody>
      </table>
      </div>  
      <div class="col-md-12 col-xs-12" style="">
        Keterangan: <?php echo $header['order_note'];?><br>
      </div>  

      <!-- FOOTER -->
      <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:25px;">
        <div class="col-md-3 col-xs-4">  
        </div>
      </div>        
    </div>
  </div>
</div>

<script>
</script>
</body>
</html>