<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />   
<link href="<?php echo base_url();?>assets/webarch/css/_print.css?_=<?php echo date('d-m-Y');?>" rel="stylesheet" type="text/css" />   
<style>
    body{
        font-family: monospace;
    }
    thead > tr > td {
        font-weight: bold;
    }
</style>
<div class="container-fluid">
    <title><?php echo $title; ?></title>      
    <div id="print-paper" class="col-md-20" style="">
        <!-- Header -->
        <!--<div id="print-header" class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">   
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <?php echo $title2;?><br>
                    <a href='#' onclick="window.print();">
                        <?php echo $title3;?>
                    </a>
                </div>                               
                <div class="col-md-5 col-xs-5 col-xs-5 padding-remove-left">
                  PERIODE : <?php echo $periode_awal.' sd '.$periode_akhir;?>
                </div>
          </div>
      </div>-->

    <div class="col-md-12 col-xs-12">
        <div class="col-md-2 col-sm-2 col-xs-2" style="padding-left:0px;">
            <img src="<?php echo $branch_logo;?>" style="width:150px;" class="img-responsive">
        </div>
        <div class="col-md-10 col-sm-10 col-xs-10" style="padding-left:0px;">
        <a href='#' onclick="window.print();">
            <?php echo $title; ?>
        </a>
        <br>
        Periode : <?php echo $periode; ?>
        <br>
        <?php 
            if(!empty($location)){
                echo 'Gudang : ' . $location['location_name'].'<br>';
            }

            if(!empty($product)){
                echo $product_alias.' : [ ' . $product['product_name'] . ' ] ' .$product['product_name'].'<br>';
                echo 'Satuan : ' .$product['product_unit'].'<br>';                
            }          
            // echo $version;
            $colspan=6;  
            $td="hide";
            $footer="";
            if($version==2){
                $colspan=8;
                $td="";
                $footer="hide";
                $mutasi_col = 8;
            }
        ?>
        </div>
    </div>
      <!-- Content -->
     <!-- <div id="print-content" class="col-md-12 col-sm-12 col-xs-12">-->
        <!--<div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">-->

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <td rowspan="2" class="text-right">No</td>
                        <td rowspan="2" >Tanggal</td>
                        <td rowspan="2" >Transaksi</td>                     
                        <td rowspan="2" >Nomor Transaksi</td>              
                        <td rowspan="2" >Keterangan</td>
                        <td rowspan="2" >Satuan</td>                        
                        <td colspan="<?php echo $colspan;?>" style="text-align:center;">Mutasi</td>                        
                    </tr>
                    <tr>          
                        <td style="text-align:right;">Masuk</td>
                        <td class="<?php echo $td;?>" style="text-align:right;">Masuk Harga</td>                        
                        <td style="text-align:right;">Keluar</td>
                        <td class="<?php echo $td;?>" style="text-align:right;">Keluar Harga</td>                        
                        <td style="text-align:right;">Saldo</td>
                        <td class="<?php echo $td;?>" style="text-align:left;">Ref</td>                        
                    </tr>                    
                </thead>
            <tbody>
                <?php 
                $num=1;
                $total_debit=0;
                $total_credit=0;                
                $total_balance=0;             
                foreach($content as $v):
                    $trans_number = '';
                    $trans_number .= $v['trans_number'];
                    $trans_number .= !empty($v['contact_name']) ? '<br>'.$v['contact_name'] : '';                    

                    if($v['trans_type_name']=="Saldo Awal"){ 
                        $total_balance = $total_balance + $v['qty_balance'];
                        ?>
                        <tr data-trans-id="<?php echo $v['trans_item_id'];?>">
                            <td class="text-right"><?php echo $num++; ?></td>
                            <td><?php echo $v['trans_item_date_format'];?></td>
                            <td colspan="4"><?php echo $v['trans_type_name'];?></td>
                            <td class="text-right"><?php echo ($v['qty_in'] > 0) ? number_format($v['qty_in'],2,'.',',') : '';?></td>
                            <td class="<?php echo $td;?> text-right"><?php echo number_format($v['price_in'],2,'.',',');?></td>
                            <td class="text-right"><?php echo ($v['qty_out'] > 0) ? number_format($v['qty_out'],2,'.',',') : '';?></td>
                            <td class="<?php echo $td;?> text-right"><?php echo number_format($v['price_out'],2,'.',',');?></td>
                            <td class="text-right"><?php echo number_format($v['qty_balance'],2,'.',',');?></td>
                            <td class="<?php echo $td;?>"></td>
                        </tr>
                    <?php
                    }else{                    
                    ?>
                        <tr data-trans-id="<?php echo $v['trans_product_id'];?>">
                             <td class="text-right"><?php echo $num++; ?></td>
                             <td><?php echo date("d/m/Y, H:i", strtotime($v['trans_date']));?></td>
                             <td><?php echo $v['trans_type_name'];?></td>
                             <td style="text-align:left;"><?php echo $trans_number;?></td>   
                             <td style="text-align:left;"><?php echo !empty($v['trans_note']) ? $v['trans_note'] : '-';?></td>
                             <td class="text-left"><?php echo $v['trans_item_unit'];?></td>
                             <td class="text-right"><?php echo ($v['qty_in'] > 0) ? number_format($v['qty_in'],2,'.',',') : '';?></td>
                             <td class="<?php echo $td;?> text-right"><?php echo number_format($v['price_in'],2,'.',',');?></td>
                             <td class="text-right"><?php echo ($v['qty_out'] > 0) ? number_format($v['qty_out'],2,'.',',') : '';?></td>
                             <td class="<?php echo $td;?> text-right"><?php echo number_format($v['price_out'],2,'.',',');?></td>
                             <td class="text-right"><?php echo number_format($v['qty_balance'],2,'.',',');?></td>
                             <td class="<?php echo $td;?>" style="text-align:left;"><?php echo $v['trans_ref'];?></td>
                        </tr>    
                    <?php                     
                    }
                $total_debit = $total_debit + $v['qty_in'];
                $total_credit = $total_credit + $v['qty_out'];
                $total_balance = $total_balance + $total_debit - $total_credit;
                // $total_trans = $total_trans + $v['trans_item_sell_total'];                  
                endforeach;
                ?>      
                <tr>
                    <td class="<?php echo $footer;?>" colspan="<?php echo $colspan;?>"><b>Total</b></td>
                    <td class="<?php echo $footer;?>" style="text-align: right;"><b><?php echo number_format($total_debit,2,'.',',');?></b></td>
                    <td class="<?php echo $footer;?>" style="text-align: right;"><b><?php echo number_format($total_credit,2,'.',',');?></b></td>
                    <td class="<?php echo $footer;?>" style="text-align: right;"><b><?php echo number_format($total_balance,2,'.',',');?></b></td>
                </tr>
            </tbody>
        </table> 
        <!-- </div> -->
    <!-- </div>    -->

        <!-- Footer -->
        <!--<div id="print-footer" class="col-md-12 col-sm-12 col-xs-12">
          <div>Dicetak :  <?php echo ucfirst($session['user_data']['user_name']);?> | <?php echo date("d-m-Y H:i:s");?></div>
      </div> -->                           
  </div>    

</div>