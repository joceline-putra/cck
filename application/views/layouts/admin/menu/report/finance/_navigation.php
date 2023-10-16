		<ul class="nav nav-tabs" role="tablist">
			<li class="" data-name="report">
			  	<a href="<?php echo base_url('report');?>">
          			<span class="fas fa-clipboard-list"></span> Bisnis
          		</a>
			</li>
			<li class="" data-name="report/finance/journal">
			  	<a href="<?php echo base_url('report/finance/journal');?>">
          			<span class="fas fa-clipboard-list"></span> Jurnal
          		</a>
			</li>
			<li class="" data-name="report/finance/ledger">
			  	<a href="<?php echo base_url('report/finance/ledger');?>">
          			<span class="fas fa-newspaper"></span> Buku Besar
          		</a>
			</li>
			<!-- <li class="" data-name="report/finance/trial_balance">
			  	<a href="<?php echo base_url('report/finance/trial_balance');?>">
          			<span class="fas fa-file-alt"></span> Neraca Saldo
          		</a>
			</li> -->
			<li class="" data-name="report/finance/worksheet">
			  	<a href="<?php echo base_url('report/finance/worksheet');?>">
          			<span class="fas fa-book-open"></span> Kertas Kerja
          		</a>
			</li>
			<li class="" data-name="report/finance/profit_loss">
			  	<a href="<?php echo base_url('report/finance/profit_loss');?>">
          			<span class="fas fa-file-alt"></span> Laba Rugi
          		</a>
			</li>												
			<li class="" data-name="report/finance/balance">
			  	<a href="<?php echo base_url('report/finance/balance');?>">
          			<span class="fas fa-balance-scale"></span> Neraca
          		</a>
			</li>	
			<li class="" data-name="report/finance/cash_in">
			  	<a href="<?php echo base_url('report/finance/cash_in');?>">
          			<span class="fas fa-file-alt"></span> Pemasukan Uang
          		</a>
			</li>	
			<li class="" data-name="report/finance/cash_out">
			  	<a href="<?php echo base_url('report/finance/cash_out');?>">
          			<span class="fas fa-file-alt"></span> Pengeluaran Uang
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