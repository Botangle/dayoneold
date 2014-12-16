<div class="span3 LeftMenu-Block"><ul>
		<li>
			<?php
			$cls = "";
			$cls1 = "";
			$cls2 = "";
			$cls3 = "";
			$cls4 = "";
			$cls5 = "";
			$cls6 = "";
			$cls7 = "";
			if (isset($this->params['named']['slug']) && $this->params['named']['slug'] == 'about') {
				$cls = "active";
			} else if (isset($this->params['named']['slug']) && $this->params['named']['slug'] == 'faq') {
				$cls1 = "active";
			} else if (isset($this->params['named']['slug']) && $this->params['named']['slug'] == 'terms') {
				$cls2 = "active";
			} else if (isset($this->params['named']['slug']) && $this->params['named']['slug'] == 'contact') {
				$cls3 = "active";
			} else if ($this->params->action == 'reportbug') {
				$cls4 = "active";
			} else if (isset($this->params['named']['slug']) && $this->params['named']['slug'] == 'updates') {
				$cls5 = "active";
			} else if (isset($this->params['named']['slug']) && $this->params['named']['slug'] == 'media') {
				$cls6 = "active";
			} else if (isset($this->params['named']['slug']) && $this->params['named']['slug'] == 'testimonials') {
				$cls7 = "active";
			}


			echo $this->Html->link(
					__('About Us'), '/about'
					, array('class' => $cls, 'title' => __('About Us'))
			);
			?>

			<!--        <li> <?php
			echo $this->Html->link(
					__('Faq'), '/faq'
					, array('class' => $cls1, 'title' => __('faq'))
			);
			?></li>-->
			<!--        <li>
			<?php
			echo $this->Html->link(
					__('Testimonials'), '/testimonials'
					, array('class' => $cls7, 'title' => __('testimonials'))
			);
			?></li>-->
		<li>
			<?php
			echo $this->Html->link(
					__('Contact Us'), '/contact'
					, array('class' => $cls3, 'title' => __('Contact Us'))
			);
			?>

		</li>
		<!--        <li> <?php
		echo $this->Html->link(
				__('Terms of use'), '/terms'
				, array('class' => $cls2, 'title' => __('Terms of use'))
		);
		?></li>-->
		<!--        <li>
		<?php
		echo $this->Html->link(
				__('Updates'), '/updates'
				, array('class' => $cls5, 'title' => __('Updates'))
		);
		?>
				</li> -->
		<!--        <li>
		<?php
		echo $this->Html->link(
				__('Media'), '/media'
				, array('class' => $cls6, 'title' => __('Media'))
		);
		?>
				</li>  -->
		<li>
			<?php
			echo $this->Html->link(
					__('Report Bug'), '/reportbug'
					, array('class' => $cls4, 'title' => __('Report Bug'))
			);
			?>
		</li>  
    </ul>
</div>