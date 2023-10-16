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
            <b>Supplier:</b><br>
            <?php echo $header['contact_name'];?><br>
            <?php echo $header['contact_address'];?><br>
            <?php echo $header['contact_phone_1'];?><br>
            <?php echo $header['contact_email_1'];?><br>                                              
          </div>
          <div class="col-xs-5">
            <table>
              <tr><td>Tanggal</td><td>: <?php echo date("d-M-Y", strtotime($header['trans_date']));?></td></tr>                         
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
            <th class="text-left">Deskripsi</th>
            <th class="text-right">Qty / Satuan</th>
            <th class="text-right">Harga</th>
            <th class="text-left">Lokasi Penempatan</th>            
            <th class="text-left">Pajak</th>
            <th class="text-right">Total</th>            
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
            $ppn = 0;
            
            //Trans Note
            $trans_note = '-';
            if(!empty($v['trans_item_note'])){
              $trans_note = '<br><b><i>'.$v['trans_item_note'].'</i></b>';
            }

            //Pajak
            $price_qty = $v['trans_item_sell_price']*$v['trans_item_in_qty'];
            $ppn_label = $v['trans_item_ppn'] == 1 ? 'P' : 'N';
            if($v['trans_item_ppn']==1){
              $ppn = $price_qty * 0.1;
              // $price_qty = ($v['trans_item_in_price']*$v['trans_item_in_qty']) + $ppn;
            }

            $total_sub = $total_sub + $price_qty;
            $total_ppn = $total_ppn + $ppn;
            echo '<tr>';
              echo '<td style="text-align:right;">'.$num++.'</td>';
              echo '<td>'.$v['product_name'].'</td>';
              echo '<td style="text-align:right;">'.number_format($v['trans_item_in_qty'],2,'.',',').' '.$v['trans_item_unit'].'</td>';
              echo '<td style="text-align:right;">'.number_format($v['trans_item_sell_price'],2,'.',',').'</td>';
              echo '<td style="text-align:left;">'.$v['location_name'].'</td>'; 
              echo '<td style="text-align:left;">'.$ppn_label.'</td>';              
              echo '<td style="text-align:right;">'.number_format($price_qty,2,'.',',').'</td>';                
            echo '</tr>';
          }

            $total_grand = $total_sub + $total_ppn + $total_discount;
            if(floatval($header['trans_total_ppn']) > 0){            
              echo '<tr>';
                echo '<td colspan="4"></td>';
                echo '<td style="text-align:right"><b>Subtotal</b></td>';
                echo '<td style="text-align:right">'.number_format($total_sub,2,'.',',').'</td>';
              echo '</tr>';
              echo '<tr>';
                echo '<td colspan="4"></td>';
                echo '<td style="text-align:right"><b>Ppn</b></td>';
                echo '<td style="text-align:right">'.number_format($total_ppn,2,'.',',').'</td>';
              echo '</tr>';  
            }
            // echo '<tr>';
            //   echo '<td colspan="4"></td>';
            //   echo '<td style="text-align:right"><b>Total Discount</b></td>';
            //   echo '<td style="text-align:right">'.number_format($total_discount,2,'.',',').'</td>';
            // echo '</tr>';   
            echo '<tr>';
              echo '<td colspan="4"></td>';
              echo '<td style="text-align:right"><b>Grand Total</b></td>';
              echo '<td style="text-align:right">'.number_format($total_grand,2,'.',',').'</td>';
            echo '</tr>';                                    
          ?>
        </tbody>
      </table>
      </div>  
      <div class="col-md-12 col-xs-12" style="">
        Keterangan:<?php echo $header['trans_note'];?>
      </div>      

        <?php 
          // $set_qrcode = base_url('prints/print_qrcode/').$header['trans_session'];
          // echo $set_qrcode;
        ?>
        <!-- <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=<?php echo $set_qrcode;?>&choe=UTF-8" title="Link to Google.com" /> -->
    </div>
  </div>
</div>

<script>
</script>
</body>
</html>