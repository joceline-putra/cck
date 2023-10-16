		<ul class="nav nav-tabs" role="tablist">
			<!-- 
            <li class="" data-name="statistic">
			  	<a href="<?php echo base_url('purchase');?>">
          			<span class="fas fa-file"></span> Statistik
          		</a>
			</li>
            -->
			<?php
			$ng = $session['user_data']['menu_access'];
			foreach($ng as $b){
				if($b['menu_group_id'] == 41){
					foreach($b['sub_menu'] as $n){
						$navigation_url = base_url() . $n['menu_link'];
						if($n['user_menu_flag'] == 1){
							echo '<li class="" data-name="' . $n['menu_link'] . '">';
								echo '<a href="' . $navigation_url . '">';
									echo '<span class="'.$n['menu_icon'].'"></span>&nbsp;' . $n['menu_name'];
								echo '</a>';
							echo '</li>';            
						}
					}            
				}
			}
			?> 					
			<!--
			<li class="" data-name="inventory/stock_opname">
			  	<a href="<?php #echo base_url('inventory/stock_opname');?>">
          			<span class="fas fa-file-alt"></span> Opname Stock
          		</a>
			</li>
			<li class="" data-name="inventory/stock_transfer">
			  	<a href="<?php #echo base_url('inventory/stock_transfer');?>">
          			<span class="fas fa-file-alt"></span> Transfer Stock
          		</a>
			</li>
			<li class="" data-name="inventory/goods_out">
			  	<a href="<?php #echo base_url('inventory/goods_out');?>">
          			<span class="fas fa-sign-in-alt"></span> Pemakaian Produk
          		</a>
			</li>
			<li class="hide" data-name="inventory/goods_in">
			  	<a href="<?php #echo base_url('inventory/goods_in');?>">
          			<span class="fas fa-truck-moving"></span> Pemasukan Produk
          		</a>
			</li>
			-->																		                            
		</ul>