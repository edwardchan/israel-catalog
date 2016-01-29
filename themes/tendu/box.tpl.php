<?php
// $Id: box.tpl.php,v 1.3 2007/12/16 21:01:45 goba Exp $

/**
 * @file box.tpl.php
 *
 * Theme implementation to display a box.
 *
 * Available variables:
 * - $title: Box title.
 * - $content: Box content.
 *
 * @see template_preprocess()
 */
?>
<div class="box-wrapper-top"></div>
<div class="box-wrapper-content">
<div class="box">

<?php if ($title && $title != 'Search results'): ?>
  <h2><?php print $title ?></h2>
<?php endif; ?>
  <div class="content-top"></div>
  <div class="content"><?php print $content ?></div>
  <div class="content-bottom"></div>
</div>
</div>
<div class="box-wrapper-bottom"></div>