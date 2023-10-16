		<ul class="nav nav-tabs" role="tablist">
			<li class="" data-name="report/purchase/buy/recap">
			  	<a href="<?php echo base_url('report/purchase/buy/recap');?>">
          			<span class="fas fa-clipboard-list"></span> Pembelian Rekap
          		</a>
			</li>
			<li class="" data-name="report/purchase/buy/detail">
			  	<a href="<?php echo base_url('report/purchase/buy/detail');?>">
          			<span class="fas fa-newspaper"></span> Pembelian Rinci
          		</a>
			</li>
			<li class="" data-name="report/purchase/buy/account_payable">
			  	<a href="<?php echo base_url('report/purchase/buy/account_payable');?>">
          			<span class="fas fa-file-alt"></span> Hutang Supplier
          		</a>
			</li>
			<li class="" data-name="report/purchase/return/detail">
			  	<a href="<?php echo base_url('report/purchase/return/detail');?>">
          			<span class="fas fa-file-alt"></span> Retur Beli
          		</a>
			</li>		
			<li class="" data-name="report/purchase/order/detail">
			  	<a href="<?php echo base_url('report/purchase/order/detail');?>">
          			<span class="fas fa-newspaper"></span> Purchase Order Rinci
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