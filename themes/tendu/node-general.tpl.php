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
$gallery = '<ul id="genmainimage">';
$spclass = '';
$g_print = '';
if (count($node->field_image_cache) == 1) {
  $spclass = ' singleimage';
}
foreach ($node->field_image_cache as $image) {
  $small = theme('imagecache','prod-small', $image['filepath'], $image['data']['title'], $image['data']['alt']);
  $big = theme('imagecache','prod-main', $image['filepath'], $image['data']['title'], $image['data']['alt']);
  $gallery .= '<li class="productitem">' . $small . '<div class="panel-content"><a href="/' . $image['filepath'] . '" rel="prettyPhoto[pp_' . $node->nid . ']" title="' . $image['data']['title'] . '">' . $big . '</a></div></li>';
  $g_print .= '<span>' . $big . '</span>';
};
$gallery .= '</ul>';
if (arg(0) == 'print') {
  $gallery = $g_print;
}
$atts = ' no-atts';
if (count($node->attributes) >= 1) {
  $atts = '';
};
$spec = FALSE;
if ($node->field_authorartist[0]['value'] ||
  $node->field_publisher[0]['value'] ||
  $node->field_originalreleasedate[0]['value'] ||
  $node->field_pages[0]['value'] ||
  $node->field_genre[0]['value'] ||
  $node->field_binding[0]['value'] ||
  $node->field_media[0]['value'] ||
  $node->field_numberofsongs[0]['value'] ||
  $node->field_numberofcds[0]['value'] ||
  $node->field_sound[0]['value'] ||
  $node->field_runtime[0]['value'] ||
  $node->field_systemreq[0]['value'] ||
  $node->field_age[0]['value'] ||
  $node->field_sizecm[0]['value'] ||
  $node->field_language[0]['value'] ||  	
  $node->field_silverweight_int[0]['value'] ||  	
  $node->field_sizeinch[0]['value']
  ) {
  $spec = TRUE;
}
$labels = '';
$freeterms = taxonomy_node_get_terms_by_vocabulary($node,2);
foreach ($freeterms as $fterm) {
  if ($fterm->name != 'Upsell') {
    $labels .= '<span id="fterm-' . $fterm->tid . '" class="free-term">' . $fterm->name . '</span>';
  }
}
//dpm($node);
//dpm($node->field_prod_offline[0]['value']);
?>
<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">
  <div class="gen-outer-wrapper">
    <div class="outer-top"></div> 
    <div class="outer-middle">
      <div id="gen-wrapper">
        <div id="gen-image" class="fpt-hider">
	      <div id="gen-main-image-wrapper" class="someclass<?php print $spclass; ?>">
	        <?php print $gallery; ?>
	      </div>
	    </div>
      </div>
    <div id="gen-else">
	  <div class="main">
        <?php if (arg(0) != 'print'): ?>
	        <div itemscope itemtype="http://schema.org/Product">
		    <div id="gen-title"><h1 itemprop="name"><?php print $title ?></h1></div>
		    <?php 
		    $design = fivestar_get_votes('node', $node->nid, 'vote', NULL);
		    $rating= $design['average']['value'];
		    $total= $design['count']['value'];
		    //dsm($design);
		    ?>
		    <div itemprop="description"></div>
		  <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		    <div>
		      <meta itemprop="ratingValue" content="<?php print $rating / 20; ?>">		      
		      <meta itemprop="bestRating" content="5"/>
		      <meta itemprop="worstRating" content="1"/>
		      <meta itemprop="ratingCount" content="<?php print $total; ?>"/>
		    </div>
		  </div>
		</div>
	    <div class="share">
          <div class="first-sharelinks">
		    <?php if(!empty($node->field_youtube_id[0]['value'])) { ?>
		    <div class="vid-link"><a id="vid-scroll" href="#"><?php print t('Video');?></a></div>
		    <?php } ?>
            <div class="print-link"><a href="/print/<?php print $node->nid?>" class="print"><?php print t("Print"); ?></a></div>
            <div class="printmail-link"><a href="/printmail/<?php print $node->nid?>" class="mail"><?php print t("Send"); ?></a></div>
		  </div>
		  <?php if($user->uid != 1) { ?>
		    <!-- AddThis Button BEGIN -->
		    <div class="addthis_toolbox addthis_default_style">
		      <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		      <a class="addthis_button_tweet"></a>
		      <a class="addthis_counter addthis_pill_style"></a>
		    </div>
		    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4cca861e762f4cb8"></script>
		    <!-- AddThis Button END -->
		  <?php } ?>
        </div>
	    <?php endif; ?>
		<?php if ($fterm != ''): ?>
		<div id="gen-terms">
		  <div id="gen-terms-wrapper">
		    <?php print $labels; ?>
		  </div>
		</div>
		<?php endif; //google translate sippt was below here?>
		
	    <div id="gen-main" class="gen-brief">
	    	    <?php print $node->content['fivestar_widget']['#value'];                   
	    ?>
	    <!--<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
	<meta itemprop="worstRating" content="1">
	<meta itemprop="ratingValue" content="<?php //print $rating / 20; ?>">
	<meta itemprop="bestRating" content="5">
</div>
	  	  -->
	  	  
	    <?php if(!empty($node->field_special_title[0]['value'])) { ?>
		    <div class="gen-supertext"><?php print $node->field_special_title[0]['value'];?></div>			
		  <?php } ?>
			<div class="field-brief"><?php print $node->field_brief[0]['value']; ?></div>
			<?php if(!empty($node->field_prod_availability[0]['value'])) { ?>
			<div class="field-availability">Process Time (business days): <?php print $node->field_prod_availability[0]['value'];?></div>
			<?php } ?>
		</div>
        <?php //print $tran_options; ?>
	    <?php //print translate_this_display_button(); ?>
	  </div>
	  <div class="nums">
  	    <span class="sku"><?php print t('SKU').' : '. $node->model;?> </span>
		<?php if ($node->field_isbn[0]['value']) print '<span class="ISBN">' . t('ISBN') . ' : ' . $node->field_isbn[0]['value'] . '</span>'; ?>
  	  </div>
	  <?php
		$block = module_invoke('gtranslate', 'block', 'view', 0);
		print '<div class="ggtrans"></div>';
	  ?>
	  <?php if ($node->field_prod_offline[0]['value'] == 'TRUE') {$atts .= ' no-buy'; }?>
	    <div id="gen-shop" class="ind<?php print $atts; ?>">
	  <?php if (arg(0) != 'print'): ?>
	    <?php if (($node->field_param_link_text[0]['value'] && $node->field_param_link_text[0]['value'] != '') && ($node->field_parameterlink[0]['value'] && $node->field_parameterlink[0]['value'] != '')): ?>
          <?php print '<div class="gen-link-to"><a href="' . $node->field_parameterlink[0]['value'] . '" target="_blank">' . $node->field_param_link_text[0]['value'] . '</a></div>'; ?>
		<?php endif; ?>
  	    <div class="form">
	      <?php if ($node->field_prod_offline[0]['value'] != 'TRUE'): ?>
      	    <?php print $node->content['add_to_cart']["#value"]; ?>
	      <?php else: ?>
      	    <?php print cpo_isr_more_get_disabled_prod_text($node); ?>
		  <?php endif; ?>
        </div>
	  <?php endif; ?>
      <div class="prices">
	    <?php 
		  if ($node->field_sale_price[0]['value']) {
            print '<div class="pp-msrp">' . t('MSRP').': <span class="msrprice">'. uc_currency_format($node->list_price) . '</span></div>'; 
	        print '<div class="pp-pr">' . t('Price').': <span class="ppprice">'. uc_currency_format($node->sell_price) . '</span></div>'; 
	        print '<div class="pp-se">' . t('Sale price').': <span class="theprice">'. uc_currency_format(cpo_isr_special_get_price_display($node)).'</span></div>'; 
		  }
		  else {
            print '<div class="pp-msrp">' . t('MSRP').': <span class="msrprice">'.uc_currency_format($node->list_price) . '</span></div>'; 
	        print '<div class="pp-se">' . t('Sale price').': <span class="theprice">'.uc_currency_format(cpo_isr_special_get_price_display($node)).'</span></div>'; 
		  }
		 ?>

	  </div>
	  <?php if (arg(0) != 'print' && $node->field_prod_offline[0]['value'] != 'TRUE'): ?>
	  <div class="problem"><?php print variable_get('cpo_isr_special_product_text', t('<a href="http://www.israel-catalog.com/help">Have a problem? Click here</a>'));?></div>
	  <?php endif; ?>
	  
	</div>
  </div>
  </div>
 <div class="outer-bottom"></div> 
 </div>
  <div id="gen-tabs-wrapper">
    <div id="gen-tabs">
	  <?php if (arg(0) != 'print'): ?>
	  <ul>
	    <li><a href="#details"><?php print t('Details & Features'); ?></a></li>
		<?php if ($spec): ?>
	    <li><a href="#specifications"><?php print t('Specifications'); ?></a></li>
		<?php endif; ?>
		<?php //if(isset($node->content['facebook_comments'])){ ?>
		<!-- <li><a href="#fcomments"><?php //print t('Comments'); ?></a></li> -->
		<?php //} ?>
	  </ul>
	  <?php endif; ?>
	  <div id="details"><div class="tabs-in-wrapper">
      <?php print $node->content['body']['#value']; ?>
          <?php //dsm($node);
         if ($appid = variable_get('facebook_comments_appid', '733615273329934')) {
             $element = '<meta property="fb:app_id" content="' . $appid . '" />';
             drupal_set_html_head($element);
          }
          global $base_url;
            $fb_url = $base_url .'/'. drupal_get_path_alias($_GET['q']); 
         
          ?>
           <div id="fb-root"></div>
          <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <div class="fb-comments" data-href="<?php echo $fb_url; ?>" data-width="100%" data-numposts="15" data-colorscheme="light"></div>
     
          
         <?php //echo $node->content['facebook_comments']['#value'];?>
    </div></div>
	  <?php if ($spec): ?>
	  <div id="specifications"><div class="tabs-in-wrapper">
	    <ul>
	      <?php if ($node->field_language[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Language');?>:</span>
			  <span class="value"> <?php print $node->field_language[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_authorartist[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Author/Artist');?>:</span>
			  <span class="value"> <?php print $node->field_authorartist[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_publisher[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Publisher');?>:</span>
			  <span class="value"> <?php print $node->field_publisher[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_originalreleasedate[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Original Release date');?>:</span>
			  <span class="value"> <?php print $node->field_originalreleasedate[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_pages[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Pages');?>:</span>
			  <span class="value"> <?php print $node->field_pages[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_genre[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Genre');?>:</span>
			  <span class="value"> <?php print $node->field_genre[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_binding[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Binding');?>:</span>
			  <span class="value"> <?php print $node->field_binding[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_media[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Media');?>:</span>
			  <span class="value"> <?php print $node->field_media[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_numberofsongs[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Number of songs');?>:</span>
			  <span class="value"> <?php print $node->field_numberofsongs[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_numberofcds[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Number of CDs');?>:</span>
			  <span class="value"> <?php print $node->field_numberofcds[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_sound[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Sound');?>:</span>
			  <span class="value"> <?php print $node->field_sound[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_runtime[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Runtime');?>:</span>
			  <span class="value"> <?php print $node->field_runtime[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_systemreq[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('System Requirements');?>:</span>
			  <span class="value"> <?php print $node->field_systemreq[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_age[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Age');?>:</span>
			  <span class="value"> <?php print $node->field_age[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_sizecm[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Size (cm)');?>:</span>
			  <span class="value"> <?php print $node->field_sizecm[0]['value'];?> </span>
			</li>
		  <?php endif;?>
	      <?php if ($node->field_sizeinch[0]['value']): ?>
		    <li>
			  <span class="label"><?php print t('Size (Inch)');?>:</span>
			  <span class="value"> <?php print $node->field_sizeinch[0]['value'];?> </span>
			</li>
		  <?php endif;?>
		  <?php if ($node->field_silverweight_int[0]['value']):?>
		    <li>
			  <span class="label"><?php print t('Silver weight');?>:</span>
			  <span class="value"><?php print $node->field_silverweight_int[0]['value'] . ' grams'; ?></span>
			</li>
		  <?php endif;?>
		</ul>
	  </div></div>
	  <?php endif; ?>
	  <?php //if(isset($node->content['facebook_comments'])){ ?>
	   <div id="fcomments">
	   
	   </div>
	   <?php //} ?>
	</div>
  </div>
</div>
