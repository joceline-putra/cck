<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />   
<link href="<?php echo base_url();?>assets/webarch/css/_print.css?_=<?php echo date('d-m-Y');?>" rel="stylesheet" type="text/css" />   
<style>
    body{
        font-family: monospace;
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
            if(!empty($contact)){
                echo $contact_alias.' : ' . $contact['contact_name'].'<br>';
                echo 'Alamat : ' . $contact['contact_address'];                
            }
            if(!empty($type_paid)){
                echo 'Metode Bayar : ' . $type_paid['paid_name'].'<br>'; 
            }            
        ?>
        </div>
    </div>
      <!-- Content -->
     <!-- <div id="print-content" class="col-md-12 col-sm-12 col-xs-12">-->
        <!--<div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">-->

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background-color:#eaeaea;">
                        <td><b>No</b></td>
                        <td><b>Tanggal</b></td>
                        <td><b>Nomor</b></td>
                        <td><b><?php echo $contact_alias;?></b></td>
                        <td style="text-align: right;"><b>Total</b></td>
                        <td style="text-align: right;"><b>Discount (-)</b></td>
                        <td style="text-align: right;"><b>Voucher (-)</b></td>                                                
                        <td style="text-align: right;"><b>Tagihan</b></td>
                        <td style="text-align: right;"><b>Dibayar</b></td>                 
                        <td style="text-align: left;"><b>Metode Pemmbayaran</b></td>                                     
                    </tr>
                </thead>
            <tbody>
                <?php 
                $num=1;
                $total_trans=0;
                $total_paid=0;
                $total_balance=0;               

                $total_dpp = 0;
                $total_discount = 0;
                $total_voucher = 0; 
                foreach($content as $v):
                
                    $trans_sisa = $v['trans_total'] - $v['trans_total_paid'];
                    $contact_address = !empty($v['contact_address']) ? '<br>'.$v['contact_address'] : '';
                    $contact_phone_1 = !empty($v['contact_phone']) ? '<br>'.$v['contact_phone'] : '';
                    $trans_contact_name = !empty($v['trans_contact_name']) ? '<br>'.$v['trans_contact_name'] : '';
                ?>
                <!-- <td style="text-align:right;"><?php #echo number_format($trans_sisa);?></td>-->
                <tr data-trans-id="<?php echo $v['trans_id'];?>">
                     <td><?php echo $num++; ?></td>
                     <td><?php echo $v['trans_date'];?></td>
                     <td><?php echo $v['trans_number'];?></td>
                     <td><?php echo $v['contact_name'].$contact_address.$contact_phone_1.$trans_contact_name;?></td>  
                     <td style="text-align:right;"><?php echo number_format($v['trans_total_dpp']);?></td>
                     <td style="text-align:right;"><?php echo number_format($v['trans_discount']);?></td>
                     <td style="text-align:right;"><?php echo number_format($v['trans_voucher']);?></td>                                                                                      
                     <td style="text-align:right;"><?php echo number_format($v['trans_total']);?></td>                     
                     <td style="text-align:right;"><?php echo number_format($v['trans_total_paid']);?></td>                                                    
                     <td><?php echo $v['trans_paid_type_name'];?></td>
                 </tr>    
                <?php 
                $total_dpp = $total_dpp + $v['trans_total_dpp'];
                $total_discount = $total_discount + $v['trans_discount'];
                $total_voucher = $total_voucher + $v['trans_voucher'];

                $total_trans = $total_trans + $v['trans_total'];
                $total_paid = $total_paid + $v['trans_total_paid'];                  
                $total_balance= $total_balance + $trans_sisa;                                  
                endforeach;
                ?>      
                <tr>
                    <td colspan="4"><b>Total</b></td>
                    <td style="text-align: right;"><b><?php echo number_format($total_dpp);?></b></td>
                    <td style="text-align: right;"><b><?php echo number_format($total_discount);?></b></td>
                    <td style="text-align: right;"><b><?php echo number_format($total_voucher);?></b></td>
                    <td style="text-align: right;"><b><?php echo number_format($total_trans);?></b></td>
                    <td style="text-align: right;"><b><?php echo number_format($total_paid);?></b></td>                                                        
                    <td></td>
                </tr>
                   <!-- <td style="text-align: right;"><b><?php #echo number_format($total_balance);?></b></td>                       -->
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