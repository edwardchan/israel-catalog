<?php
// $Id: node.tpl.php,v 1.1.2.1 2008/11/13 08:07:26 tombigel Exp $

/**
 * @file node.tpl.php
 *
 * Theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: Node body or teaser depending on $teaser flag.
 * - $picture: The authors picture of the node output from
 *   theme_user_picture().
 * - $date: Formatted creation date (use $created to reformat with
 *   format_date()).
 * - $links: Themed links like "Read more", "Add new comment", etc. output
 *   from theme_links().
 * - $name: Themed username of node author output from theme_user().
 * - $node_url: Direct url of the current node.
 * - $terms: the themed list of taxonomy term links output from theme_links().
 * - $submitted: themed submission information output from
 *   theme_node_submitted().
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $teaser: Flag for the teaser state.
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 */

$gallery = '';
foreach ($node->field_image_cache as $image) {
  $small = theme('imagecache','prod-small', $image['filepath'], $image['data']['title'], $image['data']['alt']);
  $big = theme('imagecache','prod-main', $image['filepath'], $image['data']['title'], $image['data']['alt']);
  $gallery .= '<li>' . $small . '<div class="panel-content">' . $big . '</div></li>
  ';
};
?>
<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">
  <div id="gen-wrapper">
    <div id="gen-image">
	  <div id="gen-main-image-wrapper">
		<ul id="genmainimage">
		  <?php print $gallery; ?>
		</ul>
	  </div>
	</div>
  </div>
  <div id="gen-else">
	<div class="main">
	  <div id="gen-title"><h1><?php print $title ?></h1></div>
	  <div class="share">
        <a id="vid-scroll" href="#"><?php print t('Watch video')?></a>     
        <span class="label"><?php print t('Spread the word');?></span>
        <a href="" class="fb">F</a>
        <a href="" class="twit">T</a>
        <a href="/print/<?php print $node->nid?>" class="print">P</a>
        <a href="/printmail/<?php print $node->nid?>" class="mail">M</a>
        <?php print theme('addthis_button',$node, FALSE);?>
     </div>
   	  <div id="gen-main"><?php print $node->content['body']['#value']; ?></div>
	</div>
	<div class="nums">
  	    <span class="sku"><?php print t('SKU').' : '. $node->model;?> </span>
		<?php if ($node->field_isbn[0]['value']) print '<span class="ISBN">' . t('ISBN') . ' : ' . $node->field_isbn[0]['value'] . '</span>'; ?>
  	</div>
	<div id="gen-shop">
  	  <div class="form">
    	  <?php print $node->content['add_to_cart']["#value"]; ?>
      </div>
      <div class="price">
        <?php print t('MSRP').': '.uc_currency_format($node->list_price); ?>
	      <?php print t('Price').': '.uc_currency_format($node->list_price); ?>
	      <?php //print t('Sell price').': <span class="theprice">'.uc_currency_format($node->sell_price).'</span>'; ?>
	      <?php print t('Sell price').': <span class="theprice">'.uc_currency_format(cpo_isr_special_get_price_display($node)).'</span>'; ?>
	  </div>
	  <div class="problem"><?php print l(t('Have a problem? Click here'),'help');?></div>
	</div>
  </div>
 
  <div id="gen-tabs-wrapper">
    <div id="gen-tabs">
	  <ul>
	    <li><a href="#details"><?php print t('Details & Features'); ?></a></li>
	    <li><a href="#specifications"><?php print t('Specifications'); ?></a></li>
	    <li><a href="#reviews"><?php print t('Reviews'); ?></a></li>
	  </ul>
	  <div id="details">DETAILS TAB</div>
	  <div id="specifications">SPECS TABS</div>
	  <div id="reviews">REVIEWS TABS</div>
	</div>
	<div id="gen-video">
	  <object width="480" height="385"><param name="movie" value="http://www.youtube.com/v/<?php print $node->field_youtube_id[0]['value'];?>?fs=1&amp;hl=en_US&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/<?php print $node->field_youtube_id[0]['value'];?>?fs=1&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>
	</div>
  </div>
  <div class="gen-admin-links"><?php //print $links; ?></div>
</div>
