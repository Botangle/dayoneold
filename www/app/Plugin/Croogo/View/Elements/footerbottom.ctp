<div id="footer">
	<div class="container">
		<div class="row-fluid">
			<div class="span4 fotter-left"> &copy; <?php echo date('Y'); ?>. All right reserved. botangle.com </div>
			<div class="span5 fotter-right pull-right">
				<ul class="nav nav-pills pull-right">
                    <?php /*
					<li><a href="#" title="Blog">Blog</a></li>
					<li><a href="#" title="Sitemap">Sitemap</a></li>
                    <li>
						<?php
						echo $this->Html->link(
								__('Terms of use'), '/terms'
								, array('class' => ' active', 'title' => __('Terms of use'))
						);
						?>
					</li>
 */?>
					<li>
						<?php
						echo $this->Html->link(
								__('Privacy Policy / Refunds'), '/policies'
								, array('class' => ' active', 'title' => __('Privacy Policy / Refunds'))
						);
						?>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>


<?php echo $this->Html->script(array('/croogo/js/prettyCheckable.js',));
?>
<?php
echo $this->Html->css(array(
	'/croogo/css/prettyCheckable.css',
));
?>
<script type='text/javascript'>
	$().ready(function() {

	if( $('input:checkbox').length )  {
		$('input:checkbox').prettyCheckable({
			color: 'red'

		});
	}

	});

    <?php /* Lucky Orange JS tracking script */ ?>
    window.__wtw_lucky_site_id = 23539;
    (function() {
        var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
        wa.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://cdn') + '.luckyorange.com/w.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
    })();
</script>		