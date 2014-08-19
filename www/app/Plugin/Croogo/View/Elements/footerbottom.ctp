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

<?php if($displayInternForADayIntro) : ?>

<!-- Add fancyBox -->
<link rel="stylesheet" href="/js/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>

<!-- Modal popup start -->
<div id="myAds" style="display:none;width:500px;">
	<img src="/img/logo_intern_for_aday.png">
			<p style="font-size: 16px">
                Check out Botangle's First Event!  What's it like at a Startup? Hang out with us for a day and find out!
				You will see what a day in a life of a Founder is by working with a
				company for a day! There are many opportunities to improve your
				skills like programming, working as a team, and much more that
				just can't be taught in a classroom.
			</p>
			<div class="pull-right">
				<a class="btn" href="#" onclick="$.fancybox.close();">Continue to Botangle</a>
				<a class="btn btn-primary2" href="http://internforaday.co/">Go to intern for a day</a>
			</div>
		</div>
<!-- Modal popup end -->

<script type="text/javascript">
    $.fancybox.open([
    {
        href : '#myAds'
    }   
], {
    beforeLoad: function() {
		jQuery('body').append('<div class="modal-backdrop in"></div>');
	},
	afterClose: function() {
		jQuery(".modal-backdrop").remove();
	}
});
</script>
<?php endif; ?>