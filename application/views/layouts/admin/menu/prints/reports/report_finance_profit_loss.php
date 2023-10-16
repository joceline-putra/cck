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
        <div class="col-md-12 col-xs-12">
            <div class="col-md-2 col-sm-2 col-xs-2" style="padding-left:0px;">
                <img src="<?php echo $branch_logo;?>" style="width:150px;" class="img-responsive">
            </div>
            <div class="col-md-10 col-sm-10 col-xs-10" style="padding-left:0px;">
                <a href='#' onclick="window.print();"><?php echo $title; ?></a>
                <br>Periode : <?php echo $periode; ?><br>
                <?php 
                    if(!empty($account)){
                        echo 'Akun : ['.$account['account_code'].' ] '.$account['account_name'].'<br>';
                    }
                    $header_colspan = 2;
                ?>
            </div>
        </div>
        <!--         
        <div id="print-content" class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
        -->
                <br>
            <table class="table table-bordered table-hover">
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
                                        <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>
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
                                        <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>
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
	                                    <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>
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
	                                    <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>
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
	                                    <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>
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
        <!-- 
            </div>
        </div>   
        -->

        <!--
        <div id="print-footer" class="col-md-12 col-sm-12 col-xs-12">
            <div>Dicetak :  <?php #echo ucfirst($session['user_data']['user_name']);?> | <?php #echo date("d-m-Y H:i:s");?></div>
        </div> 
        -->
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