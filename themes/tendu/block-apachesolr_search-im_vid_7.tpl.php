<?php
// $Id: block.tpl.php,v 1.1.2.2.2.6 2009/02/26 08:07:39 tombigel Exp $

/**
 * @file block.tpl.php
 *
 * Theme implementation to display a block.
 *
 * Available variables:
 * - $block->subject: Block title.
 * - $block->content: Block content.
 * - $block->module: Module that generated the block.
 * - $block->delta: This is a numeric id connected to each module.
 * - $block->region: The block region embedding the current block.
 *
 * Helper variables:
 * - $block_zebra: Outputs 'odd' and 'even' dependent on each block region.
 * - $zebra: Same output as $block_zebra but independent of any block region.
 * - $block_id: Counter dependent on each block region.
 * - $id: Same output as $block_id but independent of any block region.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Tendu Specific:
 * - $block_region_placement: Outputs 'first and 'last' to the first and the last blocks on each block region.
 * 
 * Suport for "block-class" and "block-theme" modules (Not included in the theme): 
 * - $blocktheme: Blocktheme's machine readable block name.
 * - block_class($block): Block classes defined in admin/build/block
 * 
 * @see template_preprocess()
 * @see template_preprocess_block()
 */
?>
<div id="block-<?php print $block->module .'-'. $block->delta; ?>" class="block block-<?php print $block->module ?> <?php print $block_region_placement ?> block-<?php print $block_zebra ?><?php if ($blocktheme != '') print ' '.$blocktheme; if (function_exists(block_class)) print ' '.block_class($block); ?>">
<?php if ($block->subject): ?>
  <h2><?php print $block->subject; ?></h2>
<?php endif;?>

  <div class="content">
    <div class="search-side-facet-top"></div>
    <div class="search-side-facet-middle"><?php print $block->content ?></div>
    <div class="search-side-facet-bottom"></div>
  </div>
</div>