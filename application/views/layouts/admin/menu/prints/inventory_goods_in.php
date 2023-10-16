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
          <div class="col-xs-7" style="padding-left:0;">
            <b>Karyawan Pelaksana:</b><br>
            <?php echo $header['contact_name'];?><br>
            <!-- <?php echo $header['contact_address'];?><br> -->
            <!-- <?php echo $header['contact_phone_1'];?><br> -->
            <!-- <?php echo $header['contact_email_1'];?><br>                                               -->
          </div>
          <div class="col-xs-5">
            <table>
              <tr><td>Document Date</td><td>: <?php echo date("d-M-Y", strtotime($header['trans_date']));?></td></tr>
              <tr><td>Due Date</td><td>: <?php echo date("d-M-Y", strtotime($header['trans_date_due']));?></td></tr>
              <!-- <tr><td>Sales</td><td>:</td></tr> -->
              <tr><td>Ref Num</td><td>:<?php echo $header['trans_ref_number'];?></td></tr>                           
            </table>
          </div>          
        </p>
      </div>      
      <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:4px;">
        <p>
          <div class="col-md-7" style="padding-left:0;">
            <b>Gudang Pengambilan:</b><br>
            <?php echo '['.$header['location_code'].'] '.$header['location_name'];?><br>
          </div>        
        </p>
      </div>      


    <!-- CONTENT -->
      <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:15px;">
        <b>Rincian Barang: </b>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Barang / Product</th>
              <th class="text-right">Qty</th>
              <th class="text-left">Satuan</th>              
              <th class="text-right">Harga Satuan</th>                           
              <th class="text-right">Total</th>            
            </tr>
          </thead>
          <tbody>
            <?php
            $num = 1;
            $total_stok = 0;
            $total_sub = 0;
            $total_discount = 0;
            $total_grand = 0;
            foreach($content as $v){
              if($v['trans_item_position'] ==1){
                $trans_note = '-';
                if(!empty($v['trans_item_note'])){
                  $trans_note = '<br><b><i>'.$v['trans_item_note'].'</i></b>';
                }
                $price_qty = $v['trans_item_out_price']*$v['trans_item_in_qty'];
                $total_sub = $total_sub + $price_qty;
                $total_stok = $total_stok + $v['trans_item_in_qty'];
                echo '<tr>';
                  echo '<td class="text-right">'.$num++.'</td>';
                  echo '<td>'.$v['product_name'].'</td>';
                  echo '<td style="text-align:right;">'.number_format($v['trans_item_in_qty'],2,'.',',').'</td>';
                  echo '<td>'.$v['trans_item_unit'].'</td>';                  
                  echo '<td style="text-align:right;">'.number_format($v['trans_item_in_price'],2,'.',',').'</td>';
                  echo '<td style="text-align:right;">'.number_format($price_qty,2,'.',',').'</td>';                
                echo '</tr>';
              }
            }
              echo '<tr>';
                echo '<td colspan="2"></td>';
                echo '<td class="text-right">'.number_format($total_stok,2,'.',',').'</td>';       
                echo '<td></td>';         
                echo '<td style="text-align:right"><b>Total Nilai Barang</b></td>';
                echo '<td style="text-align:right">'.number_format($total_sub,2,'.',',').'</td>';
              echo '</tr>';                               
            ?>
          </tbody>
        </table>        
      </div>  
      <div class="col-md-12 col-xs-12" style="">
        Note:<?php echo $header['trans_note'];?>
      </div>      
    </div>
  </div>
</div>

<script>
</script>
</body>
</html>