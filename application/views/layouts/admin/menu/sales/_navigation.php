<ul class="nav nav-tabs" role="tablist">
	<!--
	<li class="" data-name="statistic">
		<a href="<?php #echo base_url('sales'); ?>">
			<span class="fas fa-file"></span> Statistik
		</a>
	</li> -->
    <?php
    $ng = $session['user_data']['menu_access'];
    foreach($ng as $b){
        if($b['menu_group_id'] == 39){
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
	<li class="" data-name="finance/down_payment">
		<a href="<?php echo base_url('finance/down_payment'); ?>">
			<span class="fas fa-file-alt"></span> Down Payment
		</a>
	</li>    		
    <!--
	<li class="" data-name="sales/quotation">
        <a href="<?php #echo base_url('sales/quotation'); ?>">
            <span class="fas fa-file-alt"></span> Penawaran Penjualan
    	</a>
    </li>
    <li class="" data-name="sales/order">
        <a href="<?php #echo base_url('sales/order'); ?>">
        <span class="fas fa-file-alt"></span> Sales Order
    	</a>
    </li>
    <li class="" data-name="sales/sell">
        <a href="<?php #echo base_url('sales/sell'); ?>">
            <span class="fas fa-file-alt"></span> Penjualan
    	</a>
    </li>
    <li class="hide" data-name="sales/return">
        <a href="<?php #echo base_url('sales/return'); ?>">
            <span class="fas fa-file-alt"></span> Retur Penjualan
    	</a>
    </li>
    <li class="hide" data-name="finance/account_receivable">
        <a href="<?php #echo base_url('finance/account_receivable'); ?>">
        	<span class="fas fa-file-alt"></span> Bayar Piutang Penjualan
    	</a>
    </li>
    <li class="hide" data-name="sales/point_of_sales">
        <a href="<?php #echo base_url('sales/point_of_sales'); ?>">
        	<span class="fas fa-file-alt"></span> Point of Sales
    	</a>
    </li> -->															                             
</ul>