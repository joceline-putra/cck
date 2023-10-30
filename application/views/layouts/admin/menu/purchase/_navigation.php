<ul class="nav nav-tabs" role="tablist">
    <!-- <li class="" data-name="statistic">
        <a href="<?php #echo base_url('purchase'); ?>">
            <span class="fas fa-file"></span> Statistik
        </a>
    </li> -->
    <?php 
    foreach($session['user_data']['menu_access'] as $i):

        if($i['menu_group_id'] == 40){
            foreach($i['sub_menu'] as $v){
                if($v['user_menu_flag'] == 1){
                    $menu_icon = !empty($v['menu_icon']) ? $v['menu_icon'] : 'fas fa-folder-open';
                    echo '
                        <li class="" data-name="'.$v['menu_link'].'">
                            <a href="'.base_url($v['menu_link']).'">
                                <span class="'.$menu_icon.'"></span> '.$v['menu_name'].'
                            </a>
                        </li>                
                    ';          
                }
            }
        }
    endforeach;
    ?>			
	<li class="hide" data-name="finance/prepaid_expense">
		<a href="<?php echo base_url('finance/prepaid_expense'); ?>">
			<span class="fas fa-folder-open"></span> Down Payment Pembelian
		</a>
	</li>    		
    <!--
	<li class="" data-name="purchase/quotation">
        <a href="<?php #echo base_url('purchase/quotation'); ?>">
            <span class="fas fa-file-alt"></span> Penawaran Pembelian
    	</a>
    </li>
    <li class="" data-name="purchase/order">
        <a href="<?php #echo base_url('purchase/order'); ?>">
            <span class="fas fa-file-alt"></span> Purchase Order
    	</a>
    </li>
    <li class="" data-name="purchase/buy">
        <a href="<?php #echo base_url('purchase/buy'); ?>">
            <span class="fas fa-file-alt"></span> Pembelian
    	</a>
    </li>
    <li class="hide" data-name="purchase/return">
        <a href="<?php #echo base_url('purchase/return'); ?>">
            <span class="fas fa-file-alt"></span> Retur Pembelian
    	</a>
    </li>
    <li class="hide" data-name="finance/account_payable">
        <a href="<?php #echo base_url('finance/account_payable'); ?>">
            <span class="fas fa-file-alt"></span> Bayar Hutang Pembelian
    	</a>
    </li> 
	-->												                         
</ul>