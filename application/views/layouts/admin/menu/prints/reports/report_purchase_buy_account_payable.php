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
        Sampai Dengan : <?php echo $periode; ?>
        <br>
        <?php 
            if(!empty($contact)){
                // if(!empty($contact['contact_code'])){
                    echo $contact_alias.' : ['.$contact['contact_code'].' ] '.$contact['contact_name'].'<br>';
                // }
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
                        <td class="text-center"><b>#</b></td>
                        <td><b>Tanggal</b></td>
                        <td><b>Tgl Jth Tempo</b></td>                        
                        <td><b>Transaksi</b></td>
                        <td><b>Nomor</b></td>
                        <td><b>Keterangan</b></td>                     
                        <td style="text-align:right;"><b>Tagihan</b></td>
                        <td style="text-align:right;"><b>Dibayar</b></td>                             
                        <td style="text-align:right;"><b>Sisa Hutang</b></td>                     
                    </tr>
                </thead>
            <tbody>
                <?php 
                $last = '';
                $background_color = '#f5f5f5';
                $hide = '';
                $total = 0;
                $total_paid = 0;
                $balance = 0;
                foreach($content as $v):
                    $total = $total + $v['trans_total'];
                    $total_paid = $total_paid + $v['trans_total_paid'];
                    $balance = $balance + $v['balance'];
                    $document_number = $v['trans_number'];                    
                    $contact_name = floatval($v['contact_id'] > 0) ? $v['contact_name'] : '&nbsp;&nbsp;&nbsp;&nbsp;'.$v['contact_name'];
                    if($last !== $v['contact_name']){
                        $num = 1;

                        if($v['contact_name'] == 'TOTAL'){
                            $background_color = 'e0dada';
                            $hide='hide';
                        }

                        if(intval($v['contact_id']) > 0){
                    ?>
                    <tr class="<?php echo $hide; ?>">
                        <td colspan="9" style="background-color: <?php echo $background_color;?>;">
                            <?php echo '#'.$contact_name;?>
                        </td>
                    </tr>
                    <tr data-trans-id="<?php echo $v['trans_id'];?>">
                        <td class="text-center"></td>
                        <td><?php echo $v['trans_date_format'];?></td>
                        <td><?php echo $v['trans_date_due_format']; echo ($v['trans_date_due_over'] < 1) ? ' <b>Jatuh Tempo</b>': '';?></td>         
                        <td><?php echo $v['type_name'];?></td>        
                        <td><?php echo $v['trans_number'];?></td>  
                        <td><?php echo $v['trans_note'];?></td>          
                        <td class="text-right"><?php echo number_format($v['trans_total']);?></td> 
                        <td class="text-right"><?php echo number_format($v['trans_total_paid']);?></td>                                                                                  
                        <td class="text-right"><?php echo number_format($v['balance']);?></td>                                        
                    </tr>    
                    <?php
                        }else{ 
                            // if($v['journal_group_session'] == 'TOTAL'){
                            ?>
                    <tr data-trans-id="" style="background-color: <?php echo $background_color;?>;">
                        <td class="text-left" colspan="5"><b>TOTAL</b></td>
                        <td class="text-right"><b><?php echo number_format($v['trans_total']);?></b></td>  
                        <td class="text-right"><?php echo number_format($v['trans_total_paid']);?></td>                                                                                
                        <td class="text-right"><b><?php echo number_format($v['balance']);?></b></td>                                        
                    </tr>        
                    <?php   
                            // }                    
                        }
                    $last = $v['contact_name']; 
                    }else{ 
                        // if(intval($v['account_id']) > 0){
                        ?>
                        <tr data-trans-id="<?php echo $v['trans_id'];?>">
                            <td class="text-center"></td>
                            <td><?php echo $v['trans_date_format'];?></td>
                            <td><?php echo $v['trans_date_due_format']; echo ($v['trans_date_due_over'] < 1) ? ' <b>Jatuh Tempo</b>': '';?></td>          
                            <td><?php echo $v['type_name'];?></td>                                                    
                            <td><?php echo $v['trans_number'];?></td>                
                            <td><?php echo $v['trans_note'];?></td>                  
                            <td class="text-right"><?php echo number_format($v['trans_total']);?></td>
                            <td class="text-right"><?php echo number_format($v['trans_total_paid']);?></td>                                                                                      
                            <td class="text-right"><?php echo number_format($v['balance']);?></td>                                        
                        </tr> 
                    <?php 
                        // }
                    }                                   
                endforeach;
                ?>      
                <tr>
                    <td colspan="6"><b>Total</b></td>
                    <td class="text-right"><b><?php echo number_format($total);?></b></td>
                    <td class="text-right"><b><?php echo number_format($total_paid);?></b></td>                    
                    <td class="text-right"><b><?php echo number_format($balance);?></b></td>
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