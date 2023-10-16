<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />   
<link href="<?php echo base_url();?>assets/webarch/css/_print.css?_=<?php echo date('d-m-Y');?>" rel="stylesheet" type="text/css" />   
<style>
    body{
        font-family: monospace;
    }
    table a{
        cursor:pointer;
    }
    a:hover{
        text-decoration: none;
        font-weight: 800;
    }
    a:active{
        text-decoration: none;
        font-weight: 800;
    }  
    a:focus{
        text-decoration: none;
        font-weight: 800;
    }        
</style>
<div class="container-fluid">
    <title><?php echo $title; ?></title>      
    <div id="print-paper" class="col-md-8" style="">
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
                <a href='#' onclick="window.print();"><?php echo $title; ?></a>
                <br>Periode : <?php echo $periode; ?><br>
                <?php if(!empty($account)){ echo 'Akun : ['.$account['account_code'].' ] '.$account['account_name'].'<br>'; }
                ?>
            </div>
        </div>

        <!-- LABA RUGI -->
        <?php $header_colspan = 2; ?>
        <table class="hide table table-bordered table-hover">
            <tbody>
                <?php 
                    //Start
                    $total_revenue = 0;
                    $total_cogs    = 0;
                    $total_revenue_reduce_cogs = 0;
                    $total_cost = 0;

                    $total_revenue_other = 0;
                    $total_cost_other = 0;

                    $total_netto = 0;
                    $total_brutto = 0;

                    $total_final = 0;
                ?>
                <!-- Pendapatan --> 
                <tr><td>&nbsp;&nbsp;&nbsp;<b>Pendapatan</b></td></tr>                
                <?php
                foreach($content as $v):
                    if(intval($v['account_group']) == 4){

                        if(intval($v['group_sub_id']) == 13){
                            if(intval($v['account_id']) > 0){
                                $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                            }else{
                                $account_name = '&nbsp;&nbsp;&nbsp;<b>'.$v['account_name'].' dari Penjualan</b>';
                            } 

                                $hide='';
                                if(intval($v['account_id']) > 0){
                                    $colspan='1';
                                    $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                                    $profit_loss_credit = number_format($v['profit_loss_credit']);                                                          
                                    $profit_loss_end = number_format($v['profit_loss_end']);
                                    $balance_debit = number_format($v['balance_debit']);                                                    
                                    $balance_credit = number_format($v['balance_credit']);                                                 
                                    $balance_end = number_format($v['balance_end']);
                                }else{
                                    $colspan=1;
                                    $profit_loss_debit = '';                                                          
                                    $profit_loss_credit = '';
                                    $profit_loss_end = '';
                                    $balance_debit = '';                                                    
                                    $balance_credit = '';
                                    $balance_end = '';
                                }
                                ?>
                                <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                    <td colspan="<?php echo $colspan;?>"><?php echo $account_name;?></td>
                                    <td class="hide text-right <?php echo $hide;?>"><?php echo $profit_loss_debit;?></td>                                                          
                                    <td class="hide text-right <?php echo $hide;?>"><?php echo $profit_loss_credit;?></td>                                                          
                                    <td class="text-right <?php echo $hide;?>"><?php echo $profit_loss_end;?>&nbsp;&nbsp;</td> 	 
                                </tr>                   	
                            <?php      
                            $total_revenue = $total_revenue + $v['profit_loss_end'];    
                        }                     
                    }                                                     
                endforeach;
                ?>
                <tr><td>&nbsp;&nbsp;&nbsp;Total Pendapatan dari Penjualan</td><td style="text-align:right;"><b><?php echo number_format($total_revenue,0,'.',','); ?></b></td></tr>
                <!-- End of Pendapatan -->

                <!-- Harga Pokok Penjualan / HPP -->
                <tr><td>&nbsp;&nbsp;&nbsp;<b>Harga Pokok Penjualan</b></td></tr>
                <?php
                foreach($content as $v):
                    if(intval($v['account_group']) == 5){

                        if(intval($v['group_sub_id']) == 15){
                            if(intval($v['account_id']) > 0){
                                $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                            }else{
                                $account_name = '&nbsp;&nbsp;&nbsp;<b>'.$v['account_name'].' / Biaya Operasional</b>';
                            } 

                                $hide='';
                                if(intval($v['account_id']) > 0){
                                    $colspan='1';
                                    $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                                    $profit_loss_credit = number_format($v['profit_loss_credit']);                                                          
                                    $profit_loss_end = number_format($v['profit_loss_end']);
                                    $balance_debit = number_format($v['balance_debit']);                                                    
                                    $balance_credit = number_format($v['balance_credit']);                                                 
                                    $balance_end = number_format($v['balance_end']);
                                }else{
                                    $colspan=1;
                                    $profit_loss_debit = '';                                                          
                                    $profit_loss_credit = '';
                                    $profit_loss_end = '';
                                    $balance_debit = '';                                                    
                                    $balance_credit = '';
                                    $balance_end = '';
                                }
                                ?>
                                <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                    <td colspan="<?php echo $colspan;?>"><?php echo $account_name;?></td>
                                    <td class="hide text-right <?php echo $hide;?>"><?php echo $profit_loss_debit;?></td>                                                          
                                    <td class="hide text-right <?php echo $hide;?>"><?php echo $profit_loss_credit;?></td>    
                                    <td class="text-right <?php echo $hide;?>"><?php echo $profit_loss_end;?>&nbsp;&nbsp;</td>                                   
                                </tr>                       
                            <?php 
                            $total_cogs = $total_cogs + $v['profit_loss_end'];                             
                        }
                    }                                                     
                endforeach;
                ?>   
                <tr><td>&nbsp;&nbsp;&nbsp;Total Harga Pokok Penjualan</td><td style="text-align:right;"><b><?php echo number_format($total_cogs,0,'.',','); ?></b></td></tr>                
                <!-- End of HPP -->

                <?php //Laba Kotor   
                    $total_revenue_reduce_cogs = $total_revenue - $total_cogs;                
                ?>
                <tr style="background-color:#f9f5f5;"><td><b>Laba Kotor / Total Pendapatan</b></td><td class="text-right"><b><?php echo number_format($total_revenue_reduce_cogs,0,'.',',');?></b></td></tr>
                <tr><td colspan="<?php echo $header_colspan;?>">&nbsp;</td></tr>

                <!-- Biaya -->
                <tr><td colspan="<?php echo $header_colspan;?>">&nbsp;&nbsp;&nbsp;Biaya Operasional / Beban</td></tr>             
                <?php
                foreach($content as $v):
                    if(intval($v['account_group']) == 5){ //Biaya
                        
                        if(intval($v['group_sub_id']) == 16){ //Beban / Biaya Operasional
                            // if(intval($v['account_id']) > 0){
                                $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                            // }else{
                                // $account_name = '&nbsp;&nbsp;&nbsp;<b>'.$v['account_name'].' / Biaya Operasional</b>';
                            // } 


                                $hide='';
                                if(intval($v['account_id']) > 0){
                                    $colspan='1';
                                    $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                                    $profit_loss_credit = number_format($v['profit_loss_credit']);                                                          
                                    $profit_loss_end = number_format($v['profit_loss_end']);
                                    $balance_debit = number_format($v['balance_debit']);                                                    
                                    $balance_credit = number_format($v['balance_credit']);                                                 
                                    $balance_end = number_format($v['balance_end']);
                                }else{
                                    $colspan=1;
                                    $profit_loss_debit = '';                                                          
                                    $profit_loss_credit = '';
                                    $profit_loss_end = '';
                                    $balance_debit = '';                                                    
                                    $balance_credit = '';
                                    $balance_end = '';
                                }

                                ?>
                                <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                    <td colspan="<?php echo $colspan;?>"><?php echo $account_name;?></td>
                                    <td class="hide text-right <?php echo $hide;?>"><?php echo $profit_loss_debit;?></td>                                                          
                                    <td class="hide text-right <?php echo $hide;?>"><?php echo $profit_loss_credit;?></td>    
                                    <td class="text-right <?php echo $hide;?>"><?php echo $profit_loss_end;?>&nbsp;&nbsp;</td>                                   
                                </tr>                       
                            <?php  
                            $total_cost = $total_cost + $v['profit_loss_end'];           
                        }
                    }                                                     
                endforeach;
                ?>                 
                <tr><td><b>&nbsp;&nbsp;&nbsp;Total Biaya</b></td><td style="text-align: right;"><b><?php echo number_format($total_cost,0,'.',',');?></b></td></tr>
                <!-- End of Biaya -->

                <?php //Laba Bersih
                    $total_netto = $total_revenue_reduce_cogs - $total_cost;
                ?>
                <tr style="background-color:#f9f5f5;"><td><b>Pendapatan Bersih Operasional</b></td><td style="text-align: right;"><b><?php echo (floatval($total_netto) > 0) ? number_format($total_netto,0,'.',',') : '('.number_format(str_replace('-','',$total_netto),0,'.',',').')';?></b></td>   </tr>                
                <tr><td colspan="2">&nbsp;</td></tr>

                <!-- Pendapatan Lainnya -->
                <tr><td>&nbsp;&nbsp;&nbsp;<b>Pendapatan Lainnya</b></td></tr>
                <?php 
                foreach($content as $v):
                    if(intval($v['account_group']) == 4){

                        if(intval($v['group_sub_id']) == 14){
                            if(intval($v['account_id']) > 0){
                                $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                            }else{
                                $account_name = '&nbsp;&nbsp;&nbsp;<b>'.$v['account_name'].' dari Penjualan</b>';
                            } 

                                $hide='';
                                if(intval($v['account_id']) > 0){
                                    $colspan='1';
                                    $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                                    $profit_loss_credit = number_format($v['profit_loss_credit']);                                                          
                                    $profit_loss_end = number_format($v['profit_loss_end']);
                                    $balance_debit = number_format($v['balance_debit']);                                                    
                                    $balance_credit = number_format($v['balance_credit']);                                                 
                                    $balance_end = number_format($v['balance_end']);
                                }else{
                                    $colspan=1;
                                    $profit_loss_debit = '';                                                          
                                    $profit_loss_credit = '';
                                    $profit_loss_end = '';
                                    $balance_debit = '';                                                    
                                    $balance_credit = '';
                                    $balance_end = '';
                                }
                                ?>
                                <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                    <td colspan="<?php echo $colspan;?>"><?php echo $account_name;?></td>
                                    <td class="hide text-right <?php echo $hide;?>"><?php echo $profit_loss_debit;?></td>                                                          
                                    <td class="hide text-right <?php echo $hide;?>"><?php echo $profit_loss_credit;?></td>                                                          
                                    <td class="text-right <?php echo $hide;?>"><?php echo $profit_loss_end;?>&nbsp;&nbsp;</td> 	 
                                </tr>                   	
                            <?php      
                            $total_revenue_other = $total_revenue_other + $v['profit_loss_end'];    
                        }                     
                    }                                                     
                endforeach;
                ?>
                <tr><td>&nbsp;&nbsp;&nbsp;<b>Total Pendapatan Lainnya</b></td><td class="text-right"><b><?php echo number_format($total_revenue_other,0,'.',',');?></b></td></tr
                <tr><td colspan="2">&nbsp;</td></tr>
                <!-- End of Pendapatan Lainnya -->

                <!-- Biaya Lainnya -->
                <tr><td>&nbsp;&nbsp;&nbsp;<b>Biaya Lainnya</b></td></tr>
                <?php
                foreach($content as $v):
                    if(intval($v['account_group']) == 5){ //Biaya
                        
                        if(intval($v['group_sub_id']) == 17){ //Beban Lainnya
                            // if(intval($v['account_id']) > 0){
                                $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                            // }else{
                                // $account_name = '&nbsp;&nbsp;&nbsp;<b>'.$v['account_name'].' / Biaya Operasional</b>';
                            // } 


                                $hide='';
                                if(intval($v['account_id']) > 0){
                                    $colspan='1';
                                    $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                                    $profit_loss_credit = number_format($v['profit_loss_credit']);                                                          
                                    $profit_loss_end = number_format($v['profit_loss_end']);
                                    $balance_debit = number_format($v['balance_debit']);                                                    
                                    $balance_credit = number_format($v['balance_credit']);                                                 
                                    $balance_end = number_format($v['balance_end']);
                                }else{
                                    $colspan=1;
                                    $profit_loss_debit = '';                                                          
                                    $profit_loss_credit = '';
                                    $profit_loss_end = '';
                                    $balance_debit = '';                                                    
                                    $balance_credit = '';
                                    $balance_end = '';
                                }

                                ?>
                                <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                    <td colspan="<?php echo $colspan;?>"><?php echo $account_name;?></td>
                                    <td class="hide text-right <?php echo $hide;?>"><?php echo $profit_loss_debit;?></td>                                                          
                                    <td class="hide text-right <?php echo $hide;?>"><?php echo $profit_loss_credit;?></td>    
                                    <td class="text-right <?php echo $hide;?>"><?php echo $profit_loss_end;?>&nbsp;&nbsp;</td>                                   
                                </tr>                       
                            <?php  
                            $total_cost_other = $total_cost_other + $v['profit_loss_end'];           
                        }
                    }                                                     
                endforeach;
                ?>
                <tr><td>&nbsp;&nbsp;&nbsp;<b>Total Biaya Lainnya</b></td><td style="text-align: right;"><b><?php echo number_format($total_cost_other,0,'.',',');?></b></td></tr>
                <tr><td colspan="<?php echo $header_colspan;?>">&nbsp;</td></tr>      
                <!-- End of Biaya Lainnya -->

                <?php //Total Final
                    $total_final = $total_netto + $total_revenue_other - $total_cost_other;
                ?>
                <tr style="background-color:#f9f5f5;"><td><b>Pendapatan Bersih</b></td><td style="text-align: right;"><b><?php echo (floatval($total_final) > 0) ? number_format($total_final,0,'.',',') : '('.number_format(str_replace('-','',$total_final),0,'.',',').')';?></b></td></tr>
                <tr><td colspan="<?php echo $header_colspan;?>">&nbsp;</td></tr>
            </tbody>
        </table> 

        <!-- SEPARATOR -->
        <!-- NERACA -->
        <?php $total_laba_rugi = $total_final; ?>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <td rowspan="2" colspan="1"><b>Akun Perkiraan</b></td>    
                    <td colspan="3" style="text-align:center;"><b>Neraca</b></td>                  		              		                		                  		              		
                </tr>
                <tr>
                    <td style="text-align:center;"><b>Debit</b></td>     
                    <td style="text-align:center;"><b>Kredit</b></td>
                    <td style="text-align:center;"><b>Balance</b></td>                                                                                                                 
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_profit_loss_debit=0;
                $total_profit_loss_credit=0;
                $total_profit_loss_end=0;                
                $total_balance_debit=0;
                $total_balance_credit=0;
                $total_balance_end=0;
                $last = '';

                $t_asset_lancar = 0;
                $t_asset_tetap = 0;              
                $t_asset_other = 0;                                
                $t_asset_tdk_lancar = 0; // Not used  
                $t_asset_depresiasi = 0;

                $t_liabilitas_pendek = 0;
                $t_liabilitas_panjang = 0;
                $t_modal = 0;                  

                $total_asset = 0;
                $total_liabilitas = 0;                                
                $total_liabilitas_dan_modal = 0;   
                             
                $header_colspan = 5;
                $header_colspan_6 = 5;                
                $header_sub_colspan = 3;     
                $as = 0;           
                ?>
                    <!-- ASSET -->
                    <tr><td colspan="<?php echo $header_colspan;?>"><b>Aset</b></td></tr>           
                    
                        <!-- Aset || Aset Lancar --> 
                        <tr><td colspan="<?php echo $header_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Aset Lancar</b></td></tr><?php 
                        foreach($content as $v):
                            if(intval($v['account_group']) == 1){

                                if(intval($v['account_id']) > 0){
                                    $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                                    if($v['group_sub_id'] < 5){ //Asset Lancar
                                        $colspan='';
                                        $hide='';
                                        $start_debit = number_format($v['start_debit']);                                                          
                                        $start_credit = number_format($v['start_credit']);
                                        $movement_debit = number_format($v['movement_debit']);                                                          
                                        $movement_credit = number_format($v['movement_credit']);
                                        $end_debit = number_format($v['end_debit']);                                                  
                                        $end_credit = number_format($v['end_credit']);
                                        $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                                        $profit_loss_credit = number_format($v['profit_loss_credit']);
                                        $balance_debit = number_format($v['balance_debit']);                                                    
                                        $balance_credit = number_format($v['balance_credit']);
                                        $balance_end = number_format($v['balance_end']);                                    
                                            ?>
                                            <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                                <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_debit;?></td>                                                          
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_credit;?></td>
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_end;?></td> 	                                                                                                                                      
                                            </tr>
                                        <?php
                                        $t_asset_lancar = $t_asset_lancar + $v['balance_end'];
                                    }  
                                }
                            }                                              
                        endforeach; ?>
                        <tr><td colspan="<?php echo $header_sub_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Total Aset Lancar</b></td><td style="text-align:right;"><b><?php echo number_format($t_asset_lancar);?></b></td></tr>    
                        <tr><td colspan="<?php echo $header_colspan_6;?>">&nbsp;</td></tr>
                        <!-- End Aset || Aset Tidak Lancar -->

                        <!-- Aset || Aset Tetap --> 
                        <tr><td colspan="<?php echo $header_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Aset Tetap</b></td></tr><?php 
                        foreach($content as $v):
                            if(intval($v['account_group']) == 1){

                                if(intval($v['account_id']) > 0){
                                    $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                                    if($v['group_sub_id'] == 5){ //Asset Tetap
                                        $colspan='';
                                        $hide='';
                                        $start_debit = number_format($v['start_debit']);                                                          
                                        $start_credit = number_format($v['start_credit']);
                                        $movement_debit = number_format($v['movement_debit']);                                                          
                                        $movement_credit = number_format($v['movement_credit']);
                                        $end_debit = number_format($v['end_debit']);                                                  
                                        $end_credit = number_format($v['end_credit']);
                                        $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                                        $profit_loss_credit = number_format($v['profit_loss_credit']);
                                        $balance_debit = number_format($v['balance_debit']);                                                    
                                        $balance_credit = number_format($v['balance_credit']);
                                        $balance_end = number_format($v['balance_end']);                                    
                                            ?>
                                            <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                                <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>	 
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_debit;?></td>                                                          
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_credit;?></td>
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_end;?></td> 	                                                                                                                                      
                                            </tr>
                                        <?php
                                        $t_asset_tetap = $t_asset_tetap + $v['balance_end'];
                                    }  
                                }
                            }                                              
                        endforeach; ?>
                        <tr>
                            <td colspan="<?php echo $header_sub_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Total Aset Tetap</b></td>
                            <td style="text-align:right;"><b><?php echo number_format($t_asset_tetap);?></b></td></tr>    
                        <tr><td colspan="<?php echo $header_colspan_6;?>">&nbsp;</td></tr>
                        <!-- End Aset || Aset Tetap -->

                        <!-- Start Aset || Aset Depresiasi -->
                        <tr><td colspan="<?php echo $header_sub_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Aset Depresiasi</b></td><td></td></tr><?php 
                        foreach($content as $v):
                            if(intval($v['account_group']) == 1){

                                if(intval($v['account_id']) > 0){
                                    $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                                    if($v['group_sub_id'] == 7 ){ //Asset Depresiasi
                                        $colspan='';
                                        $hide='';
                                        $start_debit = number_format($v['start_debit']);                                                          
                                        $start_credit = number_format($v['start_credit']);
                                        $movement_debit = number_format($v['movement_debit']);                                                          
                                        $movement_credit = number_format($v['movement_credit']);
                                        $end_debit = number_format($v['end_debit']);                                                  
                                        $end_credit = number_format($v['end_credit']);
                                        $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                                        $profit_loss_credit = number_format($v['profit_loss_credit']);
                                        $balance_debit = number_format($v['balance_debit']);                                                    
                                        $balance_credit = number_format($v['balance_credit']);
                                        $balance_end = number_format($v['balance_end']);                                    
                                            ?>
                                            <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                                <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_debit;?></td>                                                          
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_credit;?></td> 	 
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_end;?></td> 	                                                                                                                                                                                  
                                            </tr>
                                        <?php
                                        $t_asset_depresiasi = $t_asset_depresiasi + $v['balance_end'];
                                    }  
                                }
                            }                                              
                        endforeach; ?>
                        <tr>
                            <td colspan="<?php echo $header_sub_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Total Aset Depresiasi</b></td>
                            <td style="text-align:right;"><b><?php echo $t_asset_depresiasi;?></b></td></tr>    
                        <tr><td colspan="<?php echo $header_colspan_6;?>">&nbsp;</td></tr>                    
                        <!-- End Aset || Aset Depresiasi -->
                    
                        <!-- Start Aset || Aset Lain -->
                        <tr><td colspan="<?php echo $header_sub_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Aset Lainnya</b></td><td></td></tr><?php 
                        foreach($content as $v):
                            if(intval($v['account_group']) == 1){

                                if(intval($v['account_id']) > 0){
                                    $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                                    if($v['group_sub_id'] == 6 ){ //Asset Lainnya
                                        $colspan='';
                                        $hide='';
                                        $start_debit = number_format($v['start_debit']);
                                        $start_credit = number_format($v['start_credit']);
                                        $movement_debit = number_format($v['movement_debit']);                                                          
                                        $movement_credit = number_format($v['movement_credit']);
                                        $end_debit = number_format($v['end_debit']);                                                  
                                        $end_credit = number_format($v['end_credit']);
                                        $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                                        $profit_loss_credit = number_format($v['profit_loss_credit']);
                                        $balance_debit = number_format($v['balance_debit']);                                                    
                                        $balance_credit = number_format($v['balance_credit']);
                                        $balance_end = number_format($v['balance_end']);
                                            ?>
                                            <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                                <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_debit;?></td>                                                          
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_credit;?></td> 	 
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_end;?></td> 	                                                                                                                                                                                  
                                            </tr>
                                        <?php
                                        $t_asset_other = $t_asset_other + $v['balance_end'];
                                    }  
                                }
                            }                                              
                        endforeach; ?>
                        <tr>
                            <td colspan="<?php echo $header_sub_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Total Aset Lainnya</b></td>
                            <td style="text-align:right;"><b><?php echo $t_asset_other;?></b></td></tr>    
                        <tr><td colspan="<?php echo $header_colspan_6;?>">&nbsp;</td></tr>                    
                        <!-- End Aset || Aset Lainnya -->

                    <?php 
                        // echo $t_asset_lancar.', '.$t_asset_tdk_lancar.', '.$t_asset_depresiasi;die;
                        $total_asset = $t_asset_lancar + $t_asset_tetap + $t_asset_tdk_lancar + $t_asset_other + $t_asset_depresiasi; 
                    ?>
                    <tr style="background-color:#dfdfdf;">
                        <td colspan="<?php echo $header_sub_colspan;?>"><b>Total Aset</b></td><td style="text-align: right;"><b><?php echo (floatval($total_asset) > 0) ? number_format($total_asset,0,'.',',') : '('.number_format(str_replace('-','',$total_asset),0,'.',',').')';?></b></td>
                    </tr>     
                    <tr><td colspan="<?php echo $header_colspan_6;?>">&nbsp;&nbsp;&nbsp;</td></tr>   
                    <!-- END OF ASSET -->


                    <!-- LIABILITAS DAN MODAL -->
                    <tr><td colspan="<?php echo $header_colspan;?>"><b>Liabilitas dan Modal</b></td></tr> 
                        <!-- Liabilitas || Liabilitas Jangka Pendek -->                  
                        <tr><td style="color:red;" colspan="<?php echo $header_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Liabilitas Jangka Pendek</b></td></tr>      
                        <?php 
                        foreach($content as $v):
                            if(intval($v['account_group']) == 2){

                                if(intval($v['account_id']) > 0){
                                    $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                                        $colspan='';
                                        $hide='';
                                        $start_debit = number_format($v['start_debit']);
                                        $start_credit = number_format($v['start_credit']);
                                        $movement_debit = number_format($v['movement_debit']);
                                        $movement_credit = number_format($v['movement_credit']);
                                        $end_debit = number_format($v['end_debit']);
                                        $end_credit = number_format($v['end_credit']);
                                        $profit_loss_debit = number_format($v['profit_loss_debit']);
                                        $profit_loss_credit = number_format($v['profit_loss_credit']);
                                        $balance_debit = number_format($v['balance_debit']);
                                        $balance_credit = number_format($v['balance_credit']);
                                        $balance_end = number_format($v['balance_end']);      

                                    if($v['group_sub_id'] > 7 and $v['group_sub_id'] < 11){ //
                                            ?>                   
                                            <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                                <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>	 
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_debit;?></td>                                                          
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_credit;?></td> 	                                                     
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_end;?></td> 	                                                                                                                                      
                                            </tr>
                                        <?php
                                        $t_liabilitas_pendek = $t_liabilitas_pendek + $v['balance_end'];
                                    }  
                                }
                            }
                        endforeach; ?>

                        <!-- Liabilitas || Liabilitas Jangka Panjang -->                  
                        <tr><td style="color:red;" colspan="<?php echo $header_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Liabilitas Jangka Panjang</b></td></tr>      
                        <?php 
                        foreach($content as $v):
                            if(intval($v['account_group']) == 2){

                                if(intval($v['account_id']) > 0){
                                    $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                                        $colspan='';
                                        $hide='';
                                        $start_debit = number_format($v['start_debit']);
                                        $start_credit = number_format($v['start_credit']);
                                        $movement_debit = number_format($v['movement_debit']);
                                        $movement_credit = number_format($v['movement_credit']);
                                        $end_debit = number_format($v['end_debit']);
                                        $end_credit = number_format($v['end_credit']);
                                        $profit_loss_debit = number_format($v['profit_loss_debit']);
                                        $profit_loss_credit = number_format($v['profit_loss_credit']);
                                        $balance_debit = number_format($v['balance_debit']);
                                        $balance_credit = number_format($v['balance_credit']);
                                        $balance_end = number_format($v['balance_end']);      

                                    if($v['group_sub_id'] == 11){ //
                                            ?>                   
                                            <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                                <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>	 
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_debit;?></td>                                                          
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_credit;?></td> 	                                                     
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_end;?></td> 	                                                                                                                                      
                                            </tr>
                                        <?php
                                        $t_liabilitas_panjang = $t_liabilitas_panjang + $v['balance_end'];

                                    }  
                                }
                            }
                        endforeach; 
                        $total_liabilitas = $t_liabilitas_panjang + $t_liabilitas_pendek;
                        ?>                        
                        <tr><td style="color:red;" colspan="<?php echo $header_sub_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Total Liabilitas</b></td><td style="text-align:right;"><b><?php echo number_format($total_liabilitas);?></b></td></tr>  
                        <tr><td colspan="<?php echo $header_colspan_6;?>">&nbsp;&nbsp;&nbsp;</td></tr>     

                        <!-- Modal Pemilik -->
                        <tr><td style="color:red;" colspan="<?php echo $header_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Modal Pemilik</b></td></tr><?php 
                        foreach($content as $v):
                            if(intval($v['account_group']) == 3){

                                if(intval($v['account_id']) > 0){
                                    $account_name = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                                        $colspan='';
                                        $hide='';
                                        $start_debit = number_format($v['start_debit']);                                                          
                                        $start_credit = number_format($v['start_credit']);
                                        $movement_debit = number_format($v['movement_debit']);                                                          
                                        $movement_credit = number_format($v['movement_credit']);
                                        $end_debit = number_format($v['end_debit']);                                                  
                                        $end_credit = number_format($v['end_credit']);
                                        $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                                        $profit_loss_credit = number_format($v['profit_loss_credit']);
                                        $balance_debit = number_format($v['balance_debit']);                                                    
                                        $balance_credit = number_format($v['balance_credit']);                                              
                                        $balance_end = number_format($v['balance_end']);

                                    // if($v['group_sub_id'] > 4){ //Asset Tidak Lancar
                                            ?>
                                            <tr data-trans-id="<?php echo $v['temp_id'];?>">
                                                <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_debit;?></td>                                                          
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_credit;?></td> 	
                                                <td class="text-right <?php echo $hide;?>"><?php echo $balance_end;?></td> 	                                                                                                                                                                                  
                                            </tr>
                                        <?php
                                        $t_modal = $t_modal + $v['balance_end'];
                                    // }  
                                }
                            }                                              
                        endforeach; ?>   
                        
                        <tr style="background-color:#dfdfdf;">
                            <td colspan="<?php echo $header_sub_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Pendapatan Periode Ini</b></td><td style="text-align: right;"><b><?php echo number_format($total_laba_rugi);?></b></td>             
                        </tr>                  
                        <tr><td style="color:red;" colspan="<?php echo $header_sub_colspan;?>">&nbsp;&nbsp;&nbsp;<b>Total Modal Pemilik</b></td><td style="text-align:right;"><b><?php echo number_format($total_laba_rugi+$t_modal);?></b></td></tr> 
                        <tr><td colspan="<?php echo $header_colspan_6;?>">&nbsp;&nbsp;&nbsp;</td></tr>    


                    <?php $total_liabilitas_dan_modal = $t_liabilitas_pendek + $t_liabilitas_panjang + $t_modal + $total_laba_rugi; ?>
                    <tr style="background-color:#dfdfdf;">
                        <td colspan="<?php echo $header_sub_colspan;?>"><b>Total Liabilitas & Modal</b></td><td style="text-align: right;"><b><?php echo (floatval($total_liabilitas_dan_modal) > 0) ? number_format($total_liabilitas_dan_modal,0,'.',',') : '('.number_format(str_replace('-','',$total_asset),0,'.',',').')';?></b></td>             
                    </tr>          


                <!-- Not Used -->
                <?php 
                $total_profit_loss_credit = $total_netto + $total_revenue_other;
                $total_profit_loss_debit  = $total_cost_other;

                $status = '-';
                if(floatval($total_profit_loss_debit) === floatval($total_profit_loss_credit)){
                	$status = 'Data Imbang';
                }else if(floatval($total_profit_loss_debit) > floatval($total_profit_loss_credit)){
                    $status = 'Perusahaan anda sedang merugi';
                }else if(floatval($total_profit_loss_debit) < floatval($total_profit_loss_credit)){
                    $status = 'Perusahaan anda sedang untung';
                }

                $diff_profit_lost = abs($total_profit_loss_debit - $total_profit_loss_credit);
                $diff_balance = abs($total_balance_debit - $total_balance_credit);
                ?>      
                <tr>
                	<td colspan="5">&nbsp;</td>
                </tr>                 
                <tr class="hide">
                	<td colspan="1">Selisih</td>
                	<td colspan="2" style="text-align:center;"><?php echo number_format($diff_profit_lost,0,'.',',');?></td>
                	<td colspan="2" style="text-align:center;"><?php echo number_format($diff_balance,0,'.',',');?></td>
                </tr>
                <tr class="hide">
                	<td colspan="1">Keputusan</td>
                	<td colspan="4" style="text-align:center;"><?php echo $status;?></td>
                </tr>                
            </tbody>
        </table>     



        <br><br>




        <!-- END -->
        <table class="hide table table-bordered table-hover">
            <thead>
                <tr>
                    <td rowspan="2" colspan="1">Akun Perkiraan</td>    
                    <td colspan="2" style="text-align:center;">Laba Rugi</td>
                    <td colspan="2" style="text-align:center;">Neraca</td>                  		              		                		                  		              		
                </tr>
                <tr>          
                    <td style="text-align:center;">Debit</td>     
                    <td style="text-align:center;">Kredit</td>   
                    <td style="text-align:center;">Debit</td>     
                    <td style="text-align:center;">Kredit</td>                                                                                             
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_profit_loss_debit=0;
                $total_profit_loss_credit=0;
                $total_balance_debit=0;
                $total_balance_credit=0;
                $last = '';
                foreach($content as $v):
                    if(intval($v['account_id']) > 0){
                    	$account_name = '&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                	}else{
                    	$account_name = '<b>'.$v['account_name'].'</b>';
                	} 

                        if(intval($v['account_id']) > 0){
                            $colspan='';
                            $hide='';
                            $start_debit = number_format($v['start_debit']);                                                          
                            $start_credit = number_format($v['start_credit']);
                            $movement_debit = number_format($v['movement_debit']);                                                          
                            $movement_credit = number_format($v['movement_credit']);
                            $end_debit = number_format($v['end_debit']);                                                  
                            $end_credit = number_format($v['end_credit']);
                            $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                            $profit_loss_credit = number_format($v['profit_loss_credit']);
                            $balance_debit = number_format($v['balance_debit']);                                                    
                            $balance_credit = number_format($v['balance_credit']);
                        }else{
                            $colspan=11;
                            $hide='hide';
                            $start_debit = '';                                                          
                            $start_credit = '';
                            $movement_debit = '';                                                          
                            $movement_credit = '';
                            $end_debit = '';                                                  
                            $end_credit = '';
                            $profit_loss_debit = '';                                                          
                            $profit_loss_credit = '';
                            $balance_debit = '';                                                    
                            $balance_credit = '';

                            if($account_name == '<b>Total</b>'){
                                $hide='';
                                $colspan=1;
                                $start_debit        = '<b>'.number_format($v['start_debit']).'</b>';                                                          
                                $start_credit       = '<b>'.number_format($v['start_credit']).'</b>';
                                $movement_debit     = '<b>'.number_format($v['movement_debit']).'</b>';                                                          
                                $movement_credit    = '<b>'.number_format($v['movement_credit']).'</b>';
                                $end_debit          = '<b>'.number_format($v['end_debit']).'</b>';                                                  
                                $end_credit         = '<b>'.number_format($v['end_credit']).'</b>';
                                $profit_loss_debit  = '<b>'.number_format($v['profit_loss_debit']).'</b>';                                                          
                                $profit_loss_credit = '<b>'.number_format($v['profit_loss_credit']).'</b>';
                                $balance_debit      = '<b>'.number_format($v['balance_debit']).'</b>';                                                    
                                $balance_credit     = '<b>'.number_format($v['balance_credit']).'</b>';

                                $total_profit_loss_debit    = $v['profit_loss_debit'];
                                $total_profit_loss_credit   = $v['profit_loss_credit'];  
                                $total_balance_debit        = $v['balance_debit'];
                                $total_balance_credit       = $v['balance_credit'];                                     
                            }                            
                        }
                        ?>
	                    <tr data-trans-id="<?php echo $v['temp_id'];?>">
	                        <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>
	                        <td class="text-right <?php echo $hide;?>"><?php echo $profit_loss_debit;?></td>                                                          
	                        <td class="text-right <?php echo $hide;?>"><?php echo $profit_loss_credit;?></td> 	 
	                        <td class="text-right <?php echo $hide;?>"><?php echo $balance_debit;?></td>                                                          
	                        <td class="text-right <?php echo $hide;?>"><?php echo $balance_credit;?></td> 	                                                                                                                                      
	                    </tr>                   	
                    <?php                                                                                
                endforeach;

                $status = '-';
                if(floatval($total_profit_loss_debit) === floatval($total_profit_loss_credit)){
                	$status = 'Data Imbang';
                }else if(floatval($total_profit_loss_debit) > floatval($total_profit_loss_credit)){
                    $status = 'Perusahaan anda sedang merugi';
                }else if(floatval($total_profit_loss_debit) < floatval($total_profit_loss_credit)){
                    $status = 'Perusahaan anda sedang untung';
                }

                $diff_profit_lost = abs($total_profit_loss_debit - $total_profit_loss_credit);
                $diff_balance = abs($total_balance_debit - $total_balance_credit);
                ?>      
                <tr>
                	<td colspan="1">Selisih</td>
                	<td colspan="2" style="text-align:center;"><?php echo number_format($diff_profit_lost,0,'.',',');?></td>
                	<td colspan="2" style="text-align:center;"><?php echo number_format($diff_balance,0,'.',',');?></td>
                </tr>
                <tr>
                	<td colspan="1">Keputusan</td>
                	<td colspan="4" style="text-align:center;"><?php echo $status;?></td>
                </tr>                
            </tbody>
        </table>                        
  </div>    

</div>
<script src="<?php echo base_url();?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>  
<script>
    $(document).ready(function() {   
        let ledger_url = "<?= $print_ledger_url; ?>";
        let ledger_date = "<?= $print_date_period; ?>"; 
        let ledger_param = "<?= $print_parameter; ?>";                

        $(".btn_print_ledger").on("click",function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            var id = $(this).attr('data-account-id');
            /* hint zz_ajax */
            window.open(ledger_url+'/'+ledger_date+'/'+id+''+ledger_param,'_blank'); 
        });
    });
</script>