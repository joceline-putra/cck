		<ul class="nav nav-tabs" role="tablist">
			<li class="hide" data-name="statistic">
			  	<a href="<?php echo base_url('checkup');?>">
          			<span class="fas fa-chart-line"></span> Statistik
          		</a>
			</li>
			<li class="" data-name="checkup/medicine">
			  	<a href="<?php echo base_url('checkup/medicine');?>">
          			<span class="fas fa-diagnoses"></span> Check Up Medicine
          		</a>
			</li>
			<li class="" data-name="checkup/laboratory">
			  	<a href="<?php echo base_url('checkup/laboratory');?>">
          			<span class="fas fa-vials"></span> Check Up Laboratory
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