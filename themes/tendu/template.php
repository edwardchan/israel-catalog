<?php
// $Id: template.php,v 1.2.2.6.2.12 2009/02/22 23:07:50 tombigel Exp $
/**
 * Tendu Drupal - A CSS Theme For Developers
 * Author: Tom Bigelajzen (http://drupal.org/user/173787) - http://tombigel.com
 * Initial Drupal 6 porting: 
 *   Lior Kesos (http://drupal.org/user/41517)
 *   Zohar Stolar (http://drupal.org/user/48488) 
 *   http://www.linnovate.net
 */

/* 
 * Force refresh of theme registry.
 * DEVELOPMENT USE ONLY - COMMENT OUT FOR PRODUCTION
 */
//drupal_rebuild_theme_registry();

/*
 * Initialize theme settings
 */
if (is_null(theme_get_setting('toggle_accesibility_links'))) { 
  global $theme_key;
  /*
   * The default values for the theme variables. Make sure $defaults exactly
   * matches the $defaults in the theme-settings.php file.
   */
  $defaults = array(            
    'toggle_language_switcher' => 1,
    'toggle_accesibility_links' => 1,
  );

  // Get default theme settings.
  $settings = theme_get_settings($theme_key);
  // Don't save the toggle_node_info_ variables.
  if (module_exists('node')) {
    foreach (node_get_types() as $type => $name) {
      unset($settings['toggle_node_info_' . $type]);
    }
  }
  // Save default theme settings.
  variable_set(
    str_replace('/', '_', 'theme_'. $theme_key .'_settings'),
    array_merge($defaults, $settings)
  );
  // Force refresh of Drupal internals.
  theme_get_setting('', TRUE);
}

function set_language_switcher(){  
  //If there is more then one language defined, add language switcher to page.tpl (defined in theme settings)  
  $lang_switch =  module_invoke('locale', 'block', 'view');
  return '<h2>'.$lang_switch['subject'].'</h2>'.$lang_switch['content'];
}
/**
 * Implement HOOK_theme
 * - Add conditional stylesheets:
 *   For more information: http://msdn.microsoft.com/en-us/library/ms537512.aspx
 */
function tendu_theme(&$existing, $type, $theme, $path){
  
  // Compute the conditional stylesheets.
  if (!module_exists('conditional_styles')) {
    include_once $base_path . drupal_get_path('theme', 'tendu') . '/template.conditional-styles.inc';
    // _conditional_styles_theme() only needs to be run once.
    if ($theme == 'tendu') {
      _conditional_styles_theme($existing, $type, $theme, $path);
    }
  }  
  $templates = drupal_find_theme_functions($existing, array('phptemplate', $theme));
  $templates += drupal_find_theme_templates($existing, '.tpl.php', $path);
  return $templates;
}

/**
 * Override or insert PHPTemplate variables into the page templates.
 * 
 * Note about body classes:
 *  Most of the variables here are Drupals default.  
 *  I changed "page_type" and "node_type" to not add the page/node id to the class,
 *  because I never needed the Drupal classes but I did find a use for a more general page or node type
 *  class, and also added some of my own.
 *  You can change anything here but the dependencies of Tendu's layout must stay intact:
 *  if ($vars['left'] && $vars['right']) {
 *    $body_classes[] = 'two-sidebars';
 *  } elseif (!$vars['left'] && !$vars['right']){
 *    $body_classes[] = 'no-sidebars';
 *  } else{
 *    $body_classes[] = 'one-sidebar';
 *  }
 *  if ($vars['left']) {
 *    $body_classes[] = 'with-sidebar-first';
 *  }
 *  if ($vars['right']) {
 *    $body_classes[] = 'with-sidebar-second';
 *  }  
 *    
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 */

function tendu_preprocess_page(&$vars) {  

  //Set Theme Settings dependent variables
  $vars['language_switcher'] = (theme_get_setting('toggle_language_switcher'))? set_language_switcher() : '';
  $vars['accesibility_links'] = (theme_get_setting('toggle_accesibility_links'))? true : false;
      
  // Add conditional stylesheets.
  if (!module_exists('conditional_styles')) {
    $vars['styles'] .= $vars['conditional_styles'] = variable_get('conditional_styles_' . $GLOBALS['theme'], '');
  }
  
  // Build array of helpful body classes
  $body_classes = array();
  // Page user is logged in
  $body_classes[] = ($vars['logged_in']) ? 'logged-in' : 'not-logged-in';
  // Page is front page
  $body_classes[] = ($vars['is_front']) ? 'front' : 'not-front'; 
  
  //Clean these strings from special characters (TODO: do we need this check?)
  $_page_type = str_replace(array('][', '_', ' '), '-', arg(0));
  $_node_type = str_replace(array('][', '_', ' '), '-', $vars['node']->type);
  // Page type (for admin, node, etc.)
  $body_classes[] = preg_replace('![^abcdefghijklmnopqrstuvwxyz0-9-_]+!s', '', 'page-' . $_page_type);
  //If node page, print node type
  if (isset($vars['node']) && $vars['node']->type) {
    $body_classes[] = 'node-type-'. $_node_type;
  }
  
  //Add classes depended on sidebars
  if ($vars['left'] && $vars['right']) {
    $body_classes[] = 'two-sidebars';
  } elseif (!$vars['left'] && !$vars['right']){
    $body_classes[] = 'no-sidebars';
  } else{
    $body_classes[] = 'one-sidebar';
  }

  if ($vars['left']) {
    $body_classes[] = 'with-sidebar-first';
  }
  if ($vars['right']) {
    $body_classes[] = 'with-sidebar-second';
  }
  $body_classes = array_filter($body_classes); // Remove empty elements
  $vars['body_classes'] = implode(' ', $body_classes);// Create class list separated by spaces
  
  if ( isset($_GET['ajaxit']) && $_GET['ajaxit'] == 1 ) {
    $vars['template_file'] = 'page-ajax';
  }
}

/**
 * Override block variables to add a variable with "first" and "last" classes to blocks per region
 * Again, most of the variables here are Drupals default.
 * I added $block_region_placement to pass the first/last string to the tpl.
 *  
 * @param $variables
 * A list of block variables
 */
function tendu_preprocess_block(&$variables) {
  
  $block_region_placement = array();  
  static $block_counter = array();
  
  // All blocks get an independent counter for each region.
  if (!isset($block_counter[$variables['block']->region])) {
    $block_counter[$variables['block']->region] = 1;
  }
  
  //Get a list of all blocks in this block's region
  $list = block_list($variables['block']->region);
  //Set class "first" to the first block
  if ($block_counter[$variables['block']->region] == 1) {
     $block_region_placement[] = 'block-first';
  }
  //Set class "last" to the last block
  if ($block_counter[$variables['block']->region] == count($list)) {
     $block_region_placement[] = 'block-last';
  }
  $block_region_placement = array_filter($block_region_placement); // Remove empty elements
  $variables['block_region_placement'] = implode(' ', $block_region_placement);// Create class list separated by spaces
  
  // Continue with Drupal default variables
  $variables['block_zebra'] = ($block_counter[$variables['block']->region] % 2) ? 'odd' : 'even';
  $variables['block_id'] = $block_counter[$variables['block']->region]++;

  $variables['template_files'][] = 'block-'. $variables['block']->region;
  $variables['template_files'][] = 'block-'. $variables['block']->module;
  $variables['template_files'][] = 'block-'. $variables['block']->module .'-'. $variables['block']->delta;
}
function tendu_links($links, $attributes = array('class' => 'links')) {
  if ($links['addthis']){unset($links['addthis']);}
  global $language;
  $output = '';

  if (count($links) > 0) {
    $output = '<ul'. drupal_attributes($attributes) .'>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = $key;

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class .= ' first';
      }
      if ($i == $num_links) {
        $class .= ' last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
          && (empty($link['language']) || $link['language']->language == $language->language)) {
        $class .= ' active';
      }
      $output .= '<li'. drupal_attributes(array('class' => $class)) .'>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      else if (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span'. $span_attributes .'>'. $link['title'] .'</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}

/**
 * Display the attribute selection form elements.
 *
 * @ingroup themeable
 * @see _uc_attribute_alter_form()
 */
function tendu_uc_attribute_add_to_cart($form) {
  $output = '<div class="attributes">';
  $stripes = array('even' => 'odd', 'odd' => 'even');
  $parity = 'even';
  foreach (element_children($form) as $aid) {
    $parity = $stripes[$parity];
    $classes = array('attribute', 'attribute-'. $aid, $parity);
    $output .= '<div class="'. implode(' ', $classes) .'">';
	if ($form[$aid]['#required'] == 1) {
	  $form[$aid]['#attributes'] = array('validate' => 'required:true');
	}
    $output .= drupal_render($form[$aid]);
    $output .= '</div>';
  }

  $output .= drupal_render($form) .'</div>';

  return $output;
}

function tendu_theme_menu_tree($menu_name) {
  $menutree = _megamenu_get_menu_tree($menu_name);

  $skin = _megamenu_get_skin_by_name($menu_name);
  $menu_orientation = _megamenu_get_menu_orientation_by_name($menu_name);

  // TODO: Currently, these attributes are set menu wide. Eventually these might should be set per menu level?
  $slot_orientation = _megamenu_get_slot_orientation_by_name($menu_name); /* TODO: temp value, should be attached to branch level in admin interface */
  $slot_attributes = _megamenu_get_slot_attributes_by_name($menu_name); /* TODO: temp value, should be attached to twig level in admin interface. */

  $output = '<ul id="megamenu-'.$menu_name.'" class="megamenu-menu '.$menu_orientation .' megamenu-skin-'.$skin.' bb-hide">'."\n";

  $t1_position = 0;
  $branch_count = count($menutree);
  foreach ($menutree as $branch) {

    $count_attributes = _megamenu_count_attributes($t1_position, $branch_count);
    $t1_position++;

    // TODO: Add an ID scheme (for faster js and css selection)
    $output .= '  <li class="megamenu-parent megamenu-parent-'.$count_attributes.'">'."\n";
    $output .= '    <h2 class="megamenu-parent-title">'.l($branch['link']['link_title'], $branch['link']['link_path']).'</h2>'."\n";

    if ($branch['below']){
      $output .= '    <ul class="megamenu-bin megamenu-slots-'.$slot_orientation.'">'."\n";

      $t2_position = 0;
      $twig_count = count($branch['below']);
      foreach ($branch['below'] as $twig) {
        $count_attributes = _megamenu_count_attributes($t2_position, $twig_count);
        $t2_position++;

        // TODO: Add na ID scheme (for faster js and css selection)
        $output .= '      <li class="megamenu-slot megamenu-slot-'. $count_attributes .'">'."\n";
        $output .= '        <h3 class="megamenu-slot-title">'.l($twig['link']['link_title'], $twig['link']['link_path']).'</h3>'."\n";

        if ($twig['below']){
          $output .= '  	 <ul class="megamenu-items '.$slotattributes.'">'."\n";

          $t3_position = 0;
          $leaf_count = count($twig['below']);
          foreach ($twig['below'] as $leaf) {
            $count_attributes = _megamenu_count_attributes($t3_position, $leaf_count);
            $t3_position++;

            $output .= '           <li class="megamenu-item megamenu-item-'.$count_attributes.'">'.l($leaf['link']['link_title'], $leaf['link']['link_path']).'</li>'."\n";
          } // END leaf iteration

          $output .= '  	 </ul>'."\n";
        } // END leaf detection

        $output .= '      </li>'."\n";
      } // END twig iteration

      $output .= '    </ul>'."\n";
    }  // END twig detection

    $output .= '  </li>'."\n";
  } // END branch iteration

  $output .= '</ul>'."\n";

  return $output;
}

/**
 * Wrap the "Add to Cart" form in a <div>.
 *
 * @ingroup themeable
 */
function tendu_uc_product_add_to_cart($node, $teaser = 0, $page = 0) {
  //dpm($node);
  if ($node->field_prod_offline[0][value] == 'true') {
    $output = '<div class="add-to-disabled">' . t('No add to cart now...') . '</div>';
  }
  else {
    $output = '<div class="add-to-cart">';
    if ($node->nid) {
      $output .= drupal_get_form('uc_product_add_to_cart_form_'. $node->nid, $node);
    }
    else {
      $output .= drupal_get_form('uc_product_add_to_cart_form', $node);
    }
	$output .= '</div>';
  }
  return $output;
}

/**
 * Wrap the "$" in a span.
 */
function tendu_uc_price($value, $context, $options) {

if(mb_substr($value,0,1) == '$') {
 $value = '<span class="currency">$</span>' . mb_substr($value,1) ;
}
  // Fixup class names.
  if (!is_array($context['class'])) {
    $context['class'] = array();
  }
  foreach ($context['class'] as $key => $class) {
    $context['class'][$key] = 'uc-price-'. $class;
  }
  $context['class'][] = 'uc-price';
  // Class the element.
  $output = '<span class="'. implode(' ', $context['class']) .'">';
  // Prefix(es).
  if ($options['label'] && isset($options['prefixes'])) {
    $output .= '<span class="price-prefixes">'. implode('', $options['prefixes']) .'</span>';
  }
  // Value.
  $output .= $value;
  // Suffix(es).
  if ($options['label'] && isset($options['suffixes'])) {
    $output .= '<span class="price-suffixes">'. implode('', $options['suffixes']) .'</span>';
  }
  $output .= '</span>';

  return $output;
}

function tendu_breadcrumb($breadcrumb) {
  $res = '<div class="breadcrumb">';
  foreach($breadcrumb as $crumb) {
    if($crumb == end($breadcrumb))  {
	  $res .= '<span class="bread">' . $crumb . '</span>';
	}else{
	  $res .= '<span class="bread">' . $crumb . '</span><span class="seperator"></span>';
	}
    
  }
  
  $res .= '</div>';
  return $res;
}

/**
 * Return a themed ad of type ad_image.
 *
 * @param @ad
 *   The ad object.
 * @return
 *   A string containing the ad markup.
 */
function tendu_ad_image_ad($ad) {
  //dsm($ad);
  if (isset($ad->aid) && (isset($ad->filepath) || isset($ad->remote_image))) {
    $output = '<div class="image-advertisement" id="ad-'. $ad->aid .'">';
    if (isset($ad->url) && !empty($ad->url)) {
      $image = theme('ad_image_image', !empty($ad->remote_image) ? $ad->remote_image : $ad->filepath, check_plain($ad->tooltip), check_plain($ad->tooltip));
      $output .= l($image, $ad->redirect .'/@HOSTID___', array('attributes' => ad_link_attributes(), 'absolute' => TRUE, 'html' => TRUE));
    }
    else {
      $output .= theme('ad_image_image', !empty($ad->remote_image) ? $ad->remote_image : $ad->filepath, check_plain($ad->tooltip), check_plain($ad->tooltip));
    }
    $output .= '</div>';
    return $output;
  }
}

/**
 * Theme function for a CAPTCHA element.
 *
 * Render it in a fieldset if a description of the CAPTCHA
 * is available. Render it as is otherwise.
 */
function tendu_captcha($element) {
  /*if (!empty($element['#description']) && isset($element['captcha_widgets'])) {
    $fieldset = array(
      '#type' => 'fieldset',
      '#title' => t('CAPTCHA'),
      '#description' => $element['#description'],
      '#children' => $element['#children'],
      '#attributes' => array('class' => 'captcha'),
    );
    return theme('fieldset', $fieldset);
  }*/
  //else {
    return '<div class="captcha">'. $element['#children'] .'</div>';
  //}
}

/**
 * Theme function for a fieldset element.
 *
 * Shlomi - http://vincent.polenordstudio.fr/snafu/?p=230.
 */
function tendu_fieldset($element) {
  if (!empty($element['#collapsible'])) {
    drupal_add_js('misc/collapse.js');

    if (!isset($element['#attributes']['class'])) {
      $element['#attributes']['class'] = '';
    }

    $element['#attributes']['class'] .= ' collapsible';
    if (!empty($element['#collapsed'])) {
      $element['#attributes']['class'] .= ' collapsed';
    }
  }

  return '<fieldset id="'. strip_tags(str_replace(" ","-",$element['#title'])). '" '. drupal_attributes($element['#attributes']) .'>'. ($element['#title'] ? '<legend>'. $element['#title'] .'</legend>' : '') . (isset($element['#description']) && $element['#description'] ? '<div class="description">'. $element['#description'] .'</div>' : '') . (!empty($element['#children']) ? $element['#children'] : '') . (isset($element['#value']) ? $element['#value'] : '') ."</fieldset>\n";
}

/**
 * Add markup and styling to the registration form.
 * Shlomi
 * @ingroup themeable
function tendu_user_register($form) {
	dpm($form);
}
 */


/**
 * Theme function to render pane on cart page.
 */
function tendu_pane_upsell($products, $msg = 'Related Products') {
  global $conf;
  if (empty($products)) return '';

  $output = '';
  $output .= '<div id="sub-fp-cart">
	<ul>
		<li><a href="#sub-tabs-1">'. t($msg) .'</a></li>
	</ul>
	<div id="sub-tabs-1">
		<div class="caro-me">';
  foreach ($products as $related) {
    $output .= '<div class="cart-related-item fpt-hider">';
    $output .= theme('upsell_item', $related);
    $output .= '</div>';
  }
  $output .= '   </div>
  		<div class="clearfix"></div>
		<div class="fptabs-cart-buttons">
        <a class="prev" id="pag-cart-prev" href="#"><span>prev</span></a>
	    <a class="next" id="pag-cart-next" href="#"><span>next</span></a>
		</div>
	</div>
</div>';

  return $output;
}

/**
 * Renders upsell item in block or checkout pane.
 */
function tendu_upsell_item($node) {
  
  $testmutation = 7572;
  $istestquery = FALSE;
  $testquery = array();
  if (multivariate_study_is_enabled($testmutation)) {
    $istestquery = TRUE;
    $mutation = multivariate_mutation_get($testmutation);
	
	switch ($mutation[0]) {
	  case 0:
	    $testquery['state'] = (int)0;  
	  break;
	  case 1:
	    $testquery['state'] = (int)1;  
	  break;
	}
  }
  
  $output = '';
  $output .= '<div class="related-item-wrapper">';
  
  $output .= '<div class="views-field-field-image-cache-fid">';
  $img = '<img src="' . imagecache_create_url('frontabs', $node->field_image_cache[0]['filepath']) . '"/>'; 

  if($istestquery && array_key_exists('state',$testquery)) {
    $output .= l($img,'node/' . $node->nid,array('html' => true,'query'=>array('mutationid'=>$testmutation, 'state' => $testquery['state'])));
  }else{
    $output .= l($img,'node/' . $node->nid,array('html' => true));   
  }
  
  $output .= '</div>';
  
  $output .= '<div class="views-field-title">';
  
  if($istestquery && array_key_exists('state',$testquery)) {
    $output .= l($node->title,'node/' . $node->nid, array('query'=>array('mutationid'=>$testmutation, 'state' => $testquery['state']),'attributes' => array('class' => 'buy-related-item'), 'html' => TRUE));
  }else{
    $output .= l($node->title,'node/' . $node->nid, array('attributes' => array('class' => 'buy-related-item'), 'html' => TRUE));
  }
  
  $output .= '</div>';
  
  $output .= '<div class="views-field-sell-price">';
  $output .= '<label class="views-label-sell-price">';
  $output .= t('Price:');
  $output .= '</label>';
  $output .= uc_price($node->sell_price, array('type' => 'amount'));
  $output .= '</div>';
  
  $output .= '</div>';

  // Only show an "add to cart" link if there is a value for Sell Price
  // TODO: Make destination configurable
  $weight = variable_get('uc_product_field_weight', array(
    'image' => -2,
    'display_price' => -1,
    'model' => 0,
    'list_price' => 2,
    'cost' => 3,
    'sell_price' => 4,
    'weight' => 5,
    'dimensions' => 6,
    'add_to_cart' => 10,
  ));

  $product = array(
    '#node' => $node,
  );

  // Only display the list price if the field is configured in Product Settings.
  $enabled = variable_get('uc_product_field_enabled', array(
    'image' => 1,
    'display_price' => 1,
    'model' => 1,
    'list_price' => 0,
    'cost' => 0,
    'sell_price' => 1,
    'weight' => 0,
    'dimensions' => 0,
    'add_to_cart' => 1,
  ));

  $context = array(
    'revision' => 'themed',
    'type' => 'product',
    'class' => array('product'),
    'subject' => array('node' => $node),
  );

  if ($enabled['list_price']) {
    $context['class'][1] = 'list';
    $context['field'] = 'list_price';
    $product['list_price'] = array(
      '#value' => uc_price($node->list_price, $context),
      '#access' => $enabled['list_price'],
      '#weight' => $weight['list_price'],
    );
  }

  $context['class'][1] = 'sell';
  $context['field'] = 'sell_price';
  $product['sell_price'] = array(
    '#value' => uc_price($node->sell_price, $context),
    '#access' => $enabled['display_price'],
    '#weight' => $weight['display_price'],
  );


    $product['add_to_cart'] = array(
      '#value' => theme('uc_product_add_to_cart', $node, TRUE, FALSE),
      '#access' => $enabled['add_to_cart'],
      '#weight' => $weight['add_to_cart'],
    );

  
  drupal_alter('uc_upsell_product', $product);
  $output .= drupal_render($product);
//  $output .= 'test';
  return $output;
}

function tendu_cart_review_table($show_subtotal = TRUE) {
  $subtotal = 0;

  // Set up table header.
  $header = array(
    array('data' => t('Your Items'), 'class' => 'image'),
	array('data' => t(''), 'class' => 'desc'),
	array('data' => t('Item Price'), 'class' => 'itemprice'),
	array('data' => t('Quantity'), 'class' => 'qty'),
    array('data' => t('Price'), 'class' => 'price'),
  );

  $context = array();

  // Set up table rows.
  $contents = uc_cart_get_contents();
  foreach ($contents as $item) {

    $price_info = array(
      'price' => $item->price,
      'qty' => $item->qty,
    );

    $context['revision'] = 'altered';
    $context['type'] = 'cart_item';
    $context['subject'] = array(
      'cart' => $contents,
      'cart_item' => $item,
      'node' => node_load($item->nid),
    );

    $total = uc_price($price_info, $context);
    $subtotal += $total;
    $prodnode = $context['subject']['node'];
    $description = l($prodnode->title,'node/' . $prodnode->nid) . uc_product_get_description($item);

    // Remove node from context to prevent the price from being altered.
    $context['revision'] = 'themed-original';
    $context['type'] = 'amount';
 // dsm($item);
  
    
	unset($context['subject']);
 // dsm($prodnode);
	$prodimg = l('<img src="' . imagecache_create_url('cart', $prodnode->field_image_cache[0]['filepath']) . '"/>','node/' . $prodnode->nid, array('html' => true));
  
    $rows[] = array(
	  array('data' =>  $prodimg, 'class' => 'image'),
	  array('data' =>  $description, 'class' => 'desc'),
      array('data' => t('@qty', array('@qty' => $item->price)), 'class' => 'itemprice'),
      array('data' => t('@qty&times;', array('@qty' => $item->qty)), 'class' => 'qty'),
      array('data' => uc_price($total, $context), 'class' => 'price'),
    );
    unset($prodimg);
  }

  // Add the subtotal as the final row.
  if ($show_subtotal) {
    $context = array(
      'revision' => 'themed-original',
      'type' => 'amount',
    );
    $rows[] = array(
	  'data' => array(
	    array('data' => '<span id="backtocart-link">' . l('Back to cart','cart/') . '</span> ' , 'colspan' => 2, 'class' => 'backtocart-wrapper'),
        array('data' => '<span id="subtotal-title">' . t('Subtotal:') . '</span> ' . uc_price($subtotal, $context), 'colspan' => 3, 'class' => 'subtotal')),
      'class' => 'lastrow',
    );
  }
  $output = '<div class="tableheader">' . t('Currently In Your Shopping Cart') .'</div>';

  return $output . theme('table', $header, $rows, array('class' => 'cart-review'));
}

function tendu_apachesolr_facet_link($facet_text, $path, $options = array(), $count, $active = FALSE, $num_found = NULL) {
  $options['attributes']['class'][] = 'apachesolr-facet';
  if ($active) {
    $options['attributes']['class'][] = 'active';
  }
  $options['attributes']['class'] = implode(' ', $options['attributes']['class']);
  return apachesolr_l($facet_text,  $path, $options) ." ($count)";
}

function tendu_apachesolr_facet_list($items, $display_limit = 0) {
  // theme('item_list') expects a numerically indexed array.
  $items = array_values($items);
  // If there is a limit and the facet count is over the limit, hide the rest.
  if (($display_limit > 0) && (count($items) > $display_limit)) {
    // Show/hide extra facets.
    drupal_add_js(drupal_get_path('module', 'apachesolr') . '/apachesolr.js');
    // Split items array into displayed and hidden.
    $hidden_items = array_splice($items, $display_limit);
    foreach ($hidden_items as $hidden_item) {
      if (!is_array($hidden_item)) {
        $hidden_item = array('data' => $hidden_item);
      }
      $hidden_item['class'] = isset($hidden_item['class']) ? $hidden_item['class'] . ' apachesolr-hidden-facet' : 'apachesolr-hidden-facet';
      $items[] = $hidden_item;
    }
  }
  $admin_link = '';
  if (user_access('administer search')) {
    $admin_link = l(t('Configure enabled filters'), 'admin/settings/apachesolr/enabled-filters');
  }
  return theme('item_list', $items) . $admin_link;
}

function tendu_apachesolr_sort_link($text, $path, $options = array(), $active = FALSE, $direction = '') {
  //dsm($text);
  //dsm($path);
  //dsm($options);
  $icon = '';
  if ($direction) {
    $icon = ' '. theme('tablesort_indicator', $direction);
  }
  if ($active) {
    if (isset($options['attributes']['class'])) {
      $options['attributes']['class'] .= ' active';
    }
    else {
      $options['attributes']['class'] = 'active';
    }
  }
  return $icon . apachesolr_l($text, $path, $options);
}

function tendu_apachesolr_sort_list($items) {
  //dsm($items);
  // theme('item_list') expects a numerically indexed array.
  //$items = array_values($items);
  //return theme('item_list', $items);
  //dsm($items);
  //drupal_add_css('sites/all/modules/cpo_rcm_special/tablesort.css','theme');
  //drupal_add_js('sites/all/modules/cpo_rcm_special/sortlist.js','theme');
  $return = '<dl class="sortbydrop"><dt><a href="#"><span>Select sorting</span></a></dt><dd><ul>';
  foreach ($items as $key => $value) {
    $return .= '<li>' . $value . '</li>';
  }
  $return .= '</ul></dd></dl>';
  return $return;
}

/**
 * Return current search block contents
 */
function tendu_apachesolr_currentsearch($total_found, $links) {
  //$total_found == 1 ? $items = 'item' : $items = 'items';
  //$return = '<h2>Current Search</h2>';
  //$return .= '<div class="search-found">Found <span>' . $total_found . '</span> results</div>';
  $return .= '<div class="search-facet-title">' . t('You\'ve selected') . '</div>';
  $return .= '<div class="search-facet-links">' . theme_item_list($links) . '</div>';
  //$return .= '<div class="search-side-facet-bottom"></div>';
  return $return;
}

/**
 * Process variables for search-result.tpl.php.
 *
 * The $variables array contains the following arguments:
 * - $result
 * - $type
 *
 * @see search-result.tpl.php
 */
function tendu_preprocess_search_result(&$variables) {
  //dpm($result);
  //dpm($variables);
  $result = $variables['result'];
  
  $variables['nid'] = $result['fields']['nid'];
  
  

  $variables['url'] = check_url($result['link']);
  $variables['title'] = check_plain($result['title']);

  $info = array();
  if (!empty($result['type'])) {
    $info['type'] = check_plain($result['type']);
  }
  if (!empty($result['user'])) {
    $info['user'] = $result['user'];
  }
  if (!empty($result['date'])) {
    $info['date'] = format_date($result['date'], 'small');
  }
  if (isset($result['extra']) && is_array($result['extra'])) {
    $info = array_merge($info, $result['extra']);
  }
  // Check for existence. User search does not include snippets.
  $variables['snippet'] = isset($result['snippet']) ? $result['snippet'] : '';
  // Provide separated and grouped meta information..
  $variables['info_split'] = $info;
  $variables['info'] = implode(' - ', $info);
  // Provide alternate search result template.
  $variables['template_files'][] = 'search-result-'. $variables['type'];
}


/**
 * Add markup and styling to the checkout panes.
 *
 * @ingroup themeable
 * @see uc_cart_checkout_form()
 */
function tendu_uc_cart_checkout_form($form) {
  drupal_add_css(drupal_get_path('module', 'uc_cart') .'/uc_cart.css');

  $output = '<div id="checkout-instructions">'. check_markup(variable_get('uc_checkout_instructions', ''), variable_get('uc_checkout_instructions_format', FILTER_FORMAT_DEFAULT), FALSE) .'</div>';

  foreach (element_children($form['panes']) as $pane_id) {
    if (function_exists(($func = _checkout_pane_data($pane_id, 'callback')))) {
      $result = $func('theme', $form['panes'][$pane_id], NULL);
      if (!empty($result)) {
        $output .= $result;
        $form['panes'][$pane_id] = array();
      }
      else {
        $output .= drupal_render($form['panes'][$pane_id]);
      }
    }
    else {
      $output .= drupal_render($form['panes'][$pane_id]);
    }
  }

/* Shlomi - change to Mailchimp - using uc_mailchimp module */
//  $output .= '<div id="checkout-form-bottom"><div class="cfb-left"><div class="cfb-l-left"><input id="signup-newsletter" class="cfb-che" type="checkbox" value="1" name="" checked="yes"></div><div class="cfb-l-right"><div class="cfb-l-title">Signup for the Israel-Catalog.com newsletter</div><div class="cfb-l-text">Yes, please sign me up for the newletter, containing informatrion about new brands, sale and special on the website.</div></div></span></div>'. drupal_render($form) .'<div class="cfb-right"></div></div>';
  $output .= '<div id="checkout-form-bottom">'. drupal_render($form) .'<div class="cfb-right"></div></div>';
$output .= '<script type="text/javascript"> var myHTML = document.getElementById("edit-panes-uc-mailchimp-subscribe-uc-mailchimp-subscribe-1d0ecc628a-wrapper").innerHTML; myHTML = myHTML.replace(" name"," CHECKED name"); document.getElementById("edit-panes-uc-mailchimp-subscribe-uc-mailchimp-subscribe-1d0ecc628a-wrapper").innerHTML = myHTML; </script>';

  return $output;
}

/**
 * Theme the table listing the items in the shopping cart block.
 *
 * @param $items
 *   An associative array of item information containing the keys 'qty',
 *      'title', 'price', and 'desc'.
 * @ingroup themeable
 */
function tendu_uc_cart_block_items($items) {
  // If there are items in the shopping cart...
  if ($items) {
    $output = '<table class="cart-block-items"><tbody>';
    $row_class = 'odd';

    $context = array(
      'revision' => 'themed',
      'type' => 'price',
    );

    // Loop through each item.
    foreach ($items as $item) {
      $context['subject'] = array(
        'cart_item' => $item,
        'node' => node_load($item->nid),
      );
      // Add the basic row with quantity, title, and price.
      $output .= '<tr class="'. $row_class .'"><td class="cart-block-item-qty">'. $item['qty'] .'</td>'
                .'<td class="cart-block-item-title">'. $item['title'] .'</td>'
                .'<td class="cart-block-item-price">'. uc_price($item['price'], $context) .'</td></tr>';

      // Add a row of description if necessary.
      if ($item['desc']) {
        $output .= '<tr class="'. $row_class .'"><td colspan="3" class="cart-block-item-desc">'. $item['desc'] .'</td></tr>';
      }

      // Alternate the class for the rows.
      $row_class = ($row_class == 'odd') ? 'even' : 'odd';
    }

    $output .= '</tbody></table>';
  }
  else {
    // Otherwise display an empty message.
    $output = '<p>'. variable_get('cpo_isr_more_empty_cart', 'There are no products in your shopping cart..') .'</p>';
  }

  return $output;
}

/**
 * Return the text displayed for an empty shopping cart.
 *
 * @ingroup themeable
 */
function tendu_uc_empty_cart() {
  return '<p>'. variable_get('cpo_isr_more_empty_cart', 'There are no products in your shopping cart..') .'</p>';
}

function tendu_apachesolr_unclick_link($facet_text, $path, $options = array()) {
  if (empty($options['html'])) {
    $facet_text = check_plain($facet_text);
  }
  else {
    // Don't pass this option as TRUE into apachesolr_l().
    unset($options['html']);
  }
  if (strpos($facet_text, '[') == 0) {
    $trans = array("[" => "$", "TO " => "- $", "]" => "");
    $facet_text = strtr($facet_text, $trans);
  }
  $options['attributes']['class'] = 'apachesolr-unclick';
  return apachesolr_l("(X)", $path, $options) . ' '. $facet_text;
}


function tendu_uc_cart_checkout_review($panes, $form) {
  drupal_add_css(drupal_get_path('module', 'uc_cart') .'/uc_cart.css');
  //global $user;
  //$output = check_markup(variable_get('uc_checkout_review_instructions', uc_get_message('review_instructions')), variable_get('uc_checkout_review_instructions_format', FILTER_FORMAT_DEFAULT), FALSE)
  //         .'<div class="order-review-table custom-review-table"><div class="rev-top"></div><div class="rev-middle">';
  //if ($user->uid == 1) {
		   $output = check_markup(variable_get('uc_checkout_review_instructions', uc_get_message('review_instructions')), variable_get('uc_checkout_review_instructions_format', FILTER_FORMAT_DEFAULT), FALSE)
            .'<div class="rev-top-top">' . $form . '<div class="br-text">' . variable_get('cpo_isr_special_review_text', t('Please press "Submit My Order" only once. Your payment method will be charged.')) . '</div></div>'
            .'<div class="order-review-table custom-review-table"><div class="rev-top"></div><div class="rev-middle">';
  //}
  foreach ($panes as $title => $data) {
    $output .= '<div class="review-pane"><div class="pane-title-row">'. $title
              .'</div></div>';
    if (is_array($data)) {
      foreach ($data as $row) {
        if (is_array($row)) {
          $output .= '<div class="rev-title"><div class="rev-label">'. $row['title'] .':</div><div class="rev-data">'
                   . $row['data'] .'</div></div>';
        }
        else {
		  //dpm($row);
          $output .= '<div class="rev-row">'. $row .'</div>';
        }
      }
    }
    else {
      $output .= '<div class="rev-something">'. $data .'</div>';
    }
  }

  $output .= '</div><div class="rev-bottom"></div><div class="rev-b-w"><div class="rev-buttons">' . $form . '</div></div></div><div class="br-text">' . variable_get('cpo_isr_special_review_text', t('Please press "Submit My Order" only once. Your payment method will be charged.')) . '</div>';

  return $output;
}

function tendu_menu_item_link($link) {
  //global $user;
  //if ($user->uid == 1 && $link['menu_name'] == 'secondary-links') {
  if ($link['menu_name'] == 'secondary-links' && $link['link_title'] == 'Learn') {
    //dpm($link);
	//$options = array('attributes' => array('target' => '_blank'));
	$link['localized_options']['attributes']['target'] = '_blank';
  }

  return l($link['title'], $link['href'], $link['localized_options']);
}

/**
 * Render an icon to display in the Administration Menu.
 *
 * @ingroup themeable
 */
function tendu_admin_menu_icon() {
  // ssl?
  $ht = 'http';
  if (arg(0) == 'cart' && arg(1)) {
    $ht .= 's';
  }
  return '<img class="admin-menu-icon more-class" src="'. $ht .'://cdn3.israel-catalog.com/sites/default/files/tendu_default_favicon.png" width="16" height="16" alt="'. t('Home') .'" />';
}

function tendu_preprocess_uc_packing_slip(&$variables) {
  $tokens = uc_store_token_values('global');
  $variables['site_logo'] = $tokens['site-logo'];
  $variables['store_name'] = $tokens['store-name'];
  $variables['store_address'] = $tokens['store-address'];
  $variables['store_phone'] = $tokens['store-phone'];

  $order = $variables['order'];
  $variables['order_link'] = l($order->order_id, url('user/'. $order->uid .'/order/'. $order->order_id, array('absolute' => TRUE)));
  $variables['order_email'] = check_plain($order->primary_email);
  $variables['billing_address'] = uc_order_address($order, 'billing');
  $variables['billing_phone'] = check_plain($order->billing_phone);
  $variables['shipping_address'] = uc_order_address($order, 'delivery');
  $variables['shipping_phone'] = check_plain($order->delivery_phone);

  if (module_exists('uc_payment')) {
    $tokens = uc_payment_token_values('order', $order);
    $variables['payment_method'] = $tokens['order-payment-method'];
  }
  else {
    $variables['payment_method'] = '';
  }

  $shipment = $variables['shipment'];
  $variables['carrier'] = check_plain($shipment->carrier);
  $variables['tracking_number'] = check_plain($shipment->tracking_number);
  $variables['packages'] = $shipment->packages;
  $variables['ship_type'] = check_plain(db_result(db_query("SELECT title FROM uc_order_line_items WHERE order_id=%d AND type='shipping'", $order->order_id)));
  $variables['allorder'] = check_plain($order->billing_first_name . ' ' . $order->billing_last_name);
  $variables['shiptype'] = 'NO';
}
