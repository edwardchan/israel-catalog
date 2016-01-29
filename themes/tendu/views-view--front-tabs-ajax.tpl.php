<div class="<?php print $classes; ?>">
  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
		<div class="clearfix"></div>
		<div class="fptabs-pager-buttons">
        <a class="prev" id="<?php print 'pags-' . $css_class; ?>-prev" href="#"><span>prev</span></a>
	    <a class="next" id="<?php print 'pags-' . $css_class; ?>-next" href="#"><span>next</span></a>
		</div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>
</div>