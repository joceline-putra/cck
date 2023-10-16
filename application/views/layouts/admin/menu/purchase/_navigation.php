<ul class="nav nav-tabs" role="tablist">
    <!-- <li class="" data-name="statistic">
        <a href="<?php #echo base_url('purchase'); ?>">
            <span class="fas fa-file"></span> Statistik
        </a>
    </li> -->
    <?php
    $ng = $session['user_data']['menu_access'];
    foreach($ng as $b){
        if($b['menu_group_id'] == 40){
            foreach($b['sub_menu'] as $n){
                $navigation_url = base_url() . $n['menu_link'];
                if($n['user_menu_flag'] == 1){
                    echo '<li class="" data-name="' . $n['menu_link'] . '">';
                        echo '<a href="' . $navigation_url . '">';
                            echo '<span class="fas fa-file-alt"></span>&nbsp;' . $n['menu_name'];
                        echo '</a>';
                    echo '</li>';            
                }
            }            
        }
    }
    ?>    		
	<li class="" data-name="finance/prepaid_expense">
		<a href="<?php echo base_url('finance/prepaid_expense'); ?>">
			<span class="fas fa-file-alt"></span> Down Payment Pembelian
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