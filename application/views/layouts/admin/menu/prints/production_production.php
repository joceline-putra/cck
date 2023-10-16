<?php 
$view = isset($_GET['p']) ? 0 : 1; 
// $view = $_GET['p'];
?>
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
            <?php 
            if($view==0){}else{
            ?>
            <img src="<?php echo $branch_logo;?>" class="img-responsive" style="width: 134px;">
          <?php } ?>
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
            <b>Pelaksana:</b><br>
            <?php echo $header['contact_name'];?><br>
            <!-- <?php echo $header['contact_address'];?><br> -->
            <!-- <?php echo $header['contact_phone_1'];?><br> -->
            <!-- <?php echo $header['contact_email_1'];?><br>                                               -->
          </div>
          <div class="col-xs-5">
            <table>
              <tr><td>Tanggal</td><td>: <?php echo date("d-M-Y", strtotime($header['trans_date']));?></td></tr>
              <tr><td>Ref</td><td>:<?php echo $header['trans_ref_number'];?></td></tr>                           
            </table>
          </div>          
        </p>
      </div>      
    <!-- CONTENT -->
      <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:15px;">

        <?php if($view == 0){ ?>
        <!-- Raw Material & Costs Material -->
        <b>Bahan Baku & Biaya Yang Diserap: </b>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Bahan Baku</th>          
              <th class="text-right">Qty</th>
              <th class="text-left">Satuan</th>    
              <th class="text-left">Gudang Pengambilan</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $num = 1;
            $num2 = 1;            
            $total_sub = 0;
            $total_biaya = 0;
            $total_discount = 0;
            $total_grand = 0;
            foreach($content as $v){
              if($v['trans_item_position'] ==2){
                $trans_note = '-';
                if(!empty($v['trans_item_note'])){
                  $trans_note = '<br><b><i>'.$v['trans_item_note'].'</i></b>';
                }
                $price_qty = $v['trans_item_out_price']*$v['trans_item_out_qty'];
                $total_sub = $total_sub + $price_qty;
                echo '<tr>';
                  echo '<td class="text-right">'.$num++.'</td>';
                  echo '<td>'.$v['product_name'].'</td>';                 
                  echo '<td style="text-align:right;">'.number_format($v['trans_item_out_qty'],2,'.',',').'</td>';
                  echo '<td>'.$v['trans_item_unit'].'</td>';         
                  echo '<td>'.$v['location_code'].' - '.$v['location_name'].'</td>';                      
                echo '</tr>';
              }
            }           ?>
          </tbody>
        </table>

        <!-- Finish Goods -->
        <b>Produk Jadi yang dihasilkan: </b>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Produk Jadi</th>
              <th class="text-right">Qty</th>  
              <th class="text-left">Satuan</th>
              <th class="text-left">Gudang Penempatan</th>                          
            </tr>
          </thead>
          <tbody>
            <?php
            $num = 1;
            $total_sub = 0;
            $total_discount = 0;
            $total_grand = 0;
            foreach($content as $v){
              if($v['trans_item_position'] ==1){
                $trans_note = '-';
                if(!empty($v['trans_item_note'])){
                  $trans_note = '<br><b><i>'.$v['trans_item_note'].'</i></b>';
                }
                echo '<tr>';
                  echo '<td class="text-right">'.$num++.'</td>';
                  echo '<td>'.$v['product_name'].'</td>';
                  echo '<td class="text-right">'.$v['trans_item_in_qty'].'</td>';
                  echo '<td>'.$v['trans_item_unit'].'</td>';
                  echo '<td>'.$v['location_code'].' - '.$v['location_name'].'</td>';                          
                echo '</tr>';
              }
            }                            
            ?>
          </tbody>
        </table>     
        <?php }else{ ?>
        <!-- Raw Material & Costs Material -->
        <b>Bahan Baku & Biaya Yang Diserap: </b>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Bahan Baku</th>
              <th class="text-right">Qty</th>
              <th class="text-left">Satuan</th>              
              <th class="text-right">Harga Satuan Terserap</th>     
              <th class="text-right">Jumlah</th>    
              <th class="text-left">Gudang Pengambilan</th>                             
            </tr>
          </thead>
          <tbody>
            <?php
            $num = 1;
            $num2 = 1;            
            $total_sub = 0;
            $total_biaya = 0;
            $total_discount = 0;
            $total_grand = 0;
            foreach($content as $v){
              if($v['trans_item_position'] ==2){
                $trans_note = '-';
                if(!empty($v['trans_item_note'])){
                  $trans_note = '<br><b><i>'.$v['trans_item_note'].'</i></b>';
                }
                $price_qty = $v['trans_item_out_price']*$v['trans_item_out_qty'];
                $total_sub = $total_sub + $price_qty;
                echo '<tr>';
                  echo '<td class="text-right">'.$num++.'</td>';
                  echo '<td>'.$v['product_name'].'</td>';
                  echo '<td style="text-align:right;">'.number_format($v['trans_item_out_qty'],2,'.',',').'</td>';
                  echo '<td>'.$v['trans_item_unit'].'</td>';
                  echo '<td style="text-align:right;">'.number_format($v['trans_item_out_price'],2,'.',',').'</td>';
                  echo '<td style="text-align:right;">'.number_format($price_qty,2,'.',',').'</td>';
                  echo '<td>'.$v['location_code'].' - '.$v['location_name'].'</td>';             
                echo '</tr>';
              }
            }
            echo '<tr>';
              echo '<td colspan="4"></td>';
              echo '<td style="text-align:right"><b>Total Bahan Baku</b></td>';
              echo '<td style="text-align:right">'.number_format($total_sub,2,'.',',').'</td>';
            echo '</tr>';            
            $journal = $result['journal'];
            if(!empty($journal)){ ?>
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Biaya</th>
              <th class="text-left" colspan="3">Keterangan</th>    
              <th class="text-right">Jumlah</th>              
            </tr>
            <?php

            // var_dump($journal);die;
              foreach($journal as $c){
                if(intval($c['account_group']) == 5){
                  // $trans_note = '-';
                  // if(!empty($v['trans_item_note'])){
                    // $trans_note = '<br><b><i>'.$v['trans_item_note'].'</i></b>';
                  // }
                  // $price_qty = $v['trans_item_out_price']*$v['trans_item_out_qty'];
                  // $total_sub = $total_sub + $price_qty;
                  echo '<tr>';
                    echo '<td class="text-right">'.$num2++.'</td>';
                    echo '<td>'.$c['account_name'].'</td>';
                    echo '<td colspan="3"></td>';
                    echo '<td style="text-align:right;">'.number_format($c['journal_item_debit'],2,'.',',').'</td>';
                  echo '</tr>';

                  $total_biaya = $total_biaya + $c['journal_item_debit'];
                  $total_sub = $total_sub + $c['journal_item_debit'];
                }
              }
            }            
              echo '<tr>';
                echo '<td colspan="4"></td>';
                echo '<td style="text-align:right"><b>Total Biaya</b></td>';
                echo '<td style="text-align:right">'.number_format($total_biaya,2,'.',',').'</td>';
              echo '</tr>';                                   
              echo '<tr>';
                echo '<td colspan="4"></td>';
                echo '<td style="text-align:right"><b>Total Bahan Baku + Biaya</b></td>';
                echo '<td style="text-align:right">'.number_format($total_sub,2,'.',',').'</td>';
              echo '</tr>';                                   
            ?>
          </tbody>
        </table>

        <!-- Finish Goods -->
        <b>Produk Jadi yang dihasilkan: </b>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Produk Jadi</th>
              <th class="text-right">Qty</th>
              <th class="text-left">Satuan</th>              
              <th class="text-right">Harga Satuan / HPP</th>     
              <th class="text-right">Total</th>     
              <th class="text-left">Gudang Penempatan</th>                          
            </tr>
          </thead>
          <tbody>
            <?php
            $num = 1;
            $total_sub = 0;
            $total_discount = 0;
            $total_grand = 0;
            foreach($content as $v){
              if($v['trans_item_position'] ==1){
                $trans_note = '-';
                if(!empty($v['trans_item_note'])){
                  $trans_note = '<br><b><i>'.$v['trans_item_note'].'</i></b>';
                }
                $price_qty = $v['trans_item_in_price']*$v['trans_item_in_qty'];
                $total_sub = $total_sub + $price_qty;
                echo '<tr>';
                  echo '<td class="text-right">'.$num++.'</td>';
                  echo '<td>'.$v['product_name'].'</td>';
                  echo '<td style="text-align:right;">'.number_format($v['trans_item_in_qty'],2,'.',',').'</td>';
                  echo '<td>'.$v['trans_item_unit'].'</td>';                  
                  echo '<td style="text-align:right;">'.number_format($v['trans_item_in_price'],2,'.',',').'</td>';
                  echo '<td style="text-align:right;">'.number_format($price_qty,2,'.',',').'</td>'; 
                  echo '<td>'.$v['location_code'].' - '.$v['location_name'].'</td>';               
                echo '</tr>';
              }
            }
              echo '<tr>';
                echo '<td colspan="4"></td>';
                echo '<td style="text-align:right"><b>Total Produk Jadi</b></td>';
                echo '<td style="text-align:right">'.number_format($total_sub,2,'.',',').'</td>';
              echo '</tr>';                               
            ?>
          </tbody>
        </table>    
        <?php } ?>   
      </div>  
     <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:4px;">
        <p>
          <div class="col-xs-12" style="padding-left:0;">
            Keterangan:<br><?php echo $header['trans_note'];?>
            <!-- <b>Lokasi Penempatan Stok Produk Jadi:</b><br> -->
            <?php #echo $header['location_name'].' ['.$header['location_code'].']';?><br>
          </div>       
        </p>
      </div>        
    </div>
  </div>
</div>

<script>
</script>
</body>
</html>