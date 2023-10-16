		<ul class="nav nav-tabs" role="tablist">
			<li class="" data-name="report/sales/sell/recap">
			  	<a href="<?php echo base_url('report/sales/sell/recap');?>">
          			<span class="fas fa-clipboard-list"></span> Penjualan Rekap
          		</a>
			</li>
			<li class="" data-name="report/sales/sell/detail">
			  	<a href="<?php echo base_url('report/sales/sell/detail');?>">
          			<span class="fas fa-newspaper"></span> Penjualan Rinci
          		</a>
			</li>
			<li class="" data-name="report/sales/sell/account_receivable">
			  	<a href="<?php echo base_url('report/sales/sell/account_receivable');?>">
          			<span class="fas fa-file-alt"></span> Piutang Customer
          		</a>
			</li>					
			<li class="" data-name="report/sales/return/detail">
			  	<a href="<?php echo base_url('report/sales/return/detail');?>">
          			<span class="fas fa-file-alt"></span> Retur Jual
          		</a>
			</li>			
			<li class="" data-name="report/sales/order/detail">
			  	<a href="<?php echo base_url('report/sales/order/detail');?>">
          			<span class="fas fa-newspaper"></span> Sales Order Rinci
          		</a>
			</li>
			<li class="" data-name="report/sales/prepare/detail">
			  	<a href="<?php echo base_url('report/sales/prepare/detail');?>">
          			<span class="fas fa-newspaper"></span> Prepare Rinci
          		</a>
			</li>															
			<?php 
			/*
				foreach($navigation as $n):
					$navigation_url = base_url().$n['menu_link']; 
					echo '<li class="" data-name="'.$n['menu_link'].'">';
					  	echo '<a href="'.$navigation_url.'">';
		          			echo '<span class="fas fa-file-alt"></span>&nbsp;'.$n['menu_name'];
		        		echo '</a>';
					echo '</li>';
				endforeach;
			*/
			?>                             
		</ul>