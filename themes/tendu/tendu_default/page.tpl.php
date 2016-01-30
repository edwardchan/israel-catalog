<?php
// $id$
/**
 * Tendu Drupal - A CSS Theme For Developers
 * Author: Tom Bigelajzen (http://drupal.org/user/173787) - http://tombigel.com
 * Initial Drupal 6 porting:
 *   Lior Kesos (http://drupal.org/user/41517)
 *   Zohar Stolar (http://drupal.org/user/48488)
 *   http://www.linnovate.net
 */
if(!$user) {global $user;}
if (arg(0) == 'taxonomy' && arg(1) == 'term' && is_numeric(arg(2))) {
  $title = cpo_isr_special_get_real_title(arg(2));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language->language; ?>" xml:lang="<?php print $language->language; ?>">
  <head>
    <title><?php print $head_title; ?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php print $head; ?>
	<?php //<link type="text/css" rel="stylesheet" media="all" href="/sites/all/themes/tendu/fix.css"> ?>
    <?php print $styles; ?>

	<?php if (arg(0) == 'cart' && arg(1) == 'checkout'): ?>
	<!--[if IE 7]>
    <link type="text/css" rel="stylesheet" media="all" href="/sites/all/themes/tendu/ie7.css?o" />
    <![endif]-->
	<?php endif; ?>
    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <?php print $scripts; ?>


<!--<link rel="stylesheet" href="<?php //echo base_path().drupal_get_path('theme','tendu_default'); ?>/media768.css" media="screen and (max-width: 768px)">
<link rel="stylesheet" href="<?php //echo base_path().drupal_get_path('theme','tendu_default'); ?>/media480.css" media="only screen and (max-width: 480px)">
<link rel="stylesheet" href="<?php //echo base_path().drupal_get_path('theme','tendu_default'); ?>/media320.css" media="only screen and (max-width: 320px)">
<link rel="stylesheet" href="<?php //echo base_path().drupal_get_path('theme','tendu_default'); ?>/media240.css" media="only screen and (max-width: 240px)">


    -->

    <?php print cpo_isr_special_get_adds(); ?>
  </head>
  <body class="<?php print $body_classes; ?>">
    <?php if (arg(0) != 'cart' && arg(0) != 'admin'): ?>
	<?php endif; ?>
    <?php if (!empty($above_header)): ?>
      <div class="above-wrapper">
      <div id="above-header">
	    <?php print $above_header; ?>
      </div>
      </div>
      <!-- /above-header-->
      <?php endif; ?>
    <div id="page">

      <div id="header">

        <?php if ($accesibility_links):?>
        <a name="top" class="accessibility-link" href="#after-header"><?php print t('[Skip Header and Navigation]');?></a>
        <a class="accessibility-link" href="#content"><?php print t('[Jump to Main Content]');?></a>
        <?php endif;?>

        <a href="<?php print $front_page; ?>" name="home" title="Buy Israeli Made Products Online - Israel Catalog Web Store" rel="home" id="mobile-logo-image"><img src="<?php echo $base_path.drupal_get_path('theme','tendu'); ?>/images/mobile-logo.png"  alt="Buy Israeli Made Products Online - Israel Catalog Web Store" width="200" height="120" /></a>

        <a href="<?php print $front_page; ?>" name="home" title="Buy Israeli Made Products Online - Israel Catalog Web Store" rel="home" id="small-mobile-logo-image"><img src="<?php echo $base_path.drupal_get_path('theme','tendu'); ?>/images/small-mobile-logo.png"  alt="Buy Israeli Made Products Online - Israel Catalog Web Store" width="100" height="70" /></a>


        <div id="header-content">

          <div id="site-details">
            <?php if ($logo): ?>
            <<?php ($is_front && !$site-name) ? print 'h1' : print 'div'; ?> id="site-logo">
              <a href="<?php print $front_page; ?>" name="home" title="Buy Israeli Made Products Online - Israel Catalog Web Store" rel="home"><img src="<?php print $logo; ?>" alt="Buy Israeli Made Products Online - Israel Catalog Web Store" id="logo-image" /></a>
            </<?php ($is_front && !$site-name) ? print 'h1' : print 'div'; ?>>
            <!-- /logo -->
            <?php endif; ?>

            <?php if ($site_name): ?>
            <<?php ($is_front) ? print 'h1' : print 'div'; ?> id='site-name'>
              <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home">
                <?php print $site_name; ?>
              </a>
            </<?php ($is_front) ? print 'h1' : print 'div'; ?>>
            <!-- /site-name -->
            <?php endif; ?>

            <?php if ($site_slogan): ?>
            <blockquote id='site-slogan'>
              <?php print $site_slogan; ?>
            </blockquote>
            <!-- /site-slogan -->
            <?php endif; ?>

          </div>
          <!-- /site-details -->

          <div style="float:left; margin-top: 10px;" id="logo-container" >
             <a href="<?php print $front_page; ?>" name="home" title="Buy Israeli Made Products Online - Israel Catalog Web Store" rel="home"><img src="<?php echo $base_path; ?>logo/logo.png" alt="Buy Israeli Made Products Online - Israel Catalog Web Store" id="logo-image" /></a>
		  </div>
          <div id="header-blocks">

			<?php if (variable_get('cpo_isr_special_display_contact_block', TRUE)): ?>
			  <div class="hed-cont-active"><?php print variable_get('cpo_isr_special_display_contact_block_active', t('Active')); ?></div>
			<?php endif; ?>
			<?php if (!variable_get('cpo_isr_special_display_contact_block', TRUE)): ?>
			  <div class="hed-cont-active"><?php print variable_get('cpo_isr_special_display_contact_block_notactive', t('Not active')); ?></div>
			<?php endif; ?>
			<?php if (!empty($header)) print $header; ?>
          </div>

        </div>

        <?php if (!empty($primary_links) or !empty($secondary_links) or !empty($main_nav)): ?>
        <div id="main-nav">

          <?php if (!empty($primary_links)): ?>
          <h2 class="primary-links-title"><?php print t('Primary Links');?></h2>
          <?php print theme('links', $primary_links, array('class' => 'menu primary-links')); ?>
          <?php endif; ?>

          <?php if (!empty($secondary_links)): ?>
          <h2 class="secondary-links-title"><?php print t('Secondary Links');?></h2>
          <?php print theme('links', $secondary_links, array('class' => 'menu secondary-links')); ?>
          <?php endif; ?>

          <?php if (!empty($main_nav)) print $main_nav; ?>

        </div>
        <!-- /main-nav -->
        <?php endif; ?>

      </div>
      <!-- /header -->

      <?php if ($accesibility_links):?>
      <a class="accessibility-target" name="after-header"></a>
      <?php endif;?>

      <?php if (!empty($content_before)):?>
      <div id="before-content" class="extra-region">
        <?php print $content_before; ?>
      </div>
      <!-- /before-content -->
      <?php endif; ?>

      <div id="main" <?php if (empty($content_after)) print 'class="footer-spacer"';?>>

         <?php if (($breadcrumb && arg(0) != 'search') or $title or $help or $messages or (!empty($tabs) && ($user->uid != 0))): ?>
         <div id="content-header">

           <div id="content-info">
             <?php if (arg(0) != 'search') print $breadcrumb; ?>
             <?php print $messages; ?>
             <?php print $help; ?>
           </div>

            <?php if (!empty($tabs) && ($user->uid != 0)): ?>
            <div class="tabs">
              <div class="tabs-top"></div>
              <div class="tabs-mid"><?php print $tabs; ?></div>
              <div class="tabs-bot"></div>
            </div>
            <!-- /tabs -->
            <?php endif; ?>

           <?php if ($accesibility_links):?>
           <a class="accessibility-target" name="content"></a>
           <?php endif;?>

           <?php if ($title && !$is_front && ($node->type!='general') && arg(0) != 'search'): ?>
           <div id="content-title" >
		   <?php if(arg(0) == 'taxonomy' && arg(1) == 'term'): ?>
		     <div class="ct-title" style="float: left;width: 56%;">
		   <?php endif; ?>
               <?php ($is_front) ? print 'h2' : print 'h1'; ?> class="title">
                 <?php  print $title; ?>
               </<?php ($is_front) ? print 'h2' : print 'h1'; ?>>
		   <?php if(arg(0) == 'taxonomy' && arg(1) == 'term'): ?>
		     </div>
		     <div style="float:right;margin-top: 6px;">
		  <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
          <g:plusone></g:plusone>
          </div>
		  <div class="ct-buttons" style="width: 300px;">
		    <!-- AddThis Button BEGIN -->
		    <div class="addthis_toolbox addthis_default_style">
		      <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		      <a class="addthis_button_tweet"></a>
		      <a class="addthis_counter addthis_pill_style"></a>
		    </div>
		    <script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js#username=xa-4cca861e762f4cb8"></script>
		    <!-- AddThis Button END -->

		  </div>

		  <?php endif; ?>
           </div>
           <?php endif; ?>
         </div>
         <!-- /content-header -->
         <?php endif; ?>

        <?php if ($left || $inner_left_sidebar): ?>
        <div id="sidebar-first" class="sidebar-region">
		  <?php if ($inner_left_sidebar): ?>
		    <div id="sidebar-first-inner">
			  <div id="sfi-top"></div>
		      <div id="sfi-middle"><div class="sfi-m-title"><?php print t('Narrow your search'); ?></div><?php print $inner_left_sidebar; ?></div>
			  <div id="sfi-bottom"></div>
			</div>
		    <!-- /sidebar-first-inner -->
		  <?php endif; ?>
          <?php print $left; ?>
        </div>
         <!-- /sidebar-first -->
        <?php endif; ?>

        <?php if ($right): ?>
        <div id="sidebar-second" class="sidebar-region">
          <?php print $right; ?>
        </div>
        <!-- /sidebar-second -->
        <?php endif; ?>

        <div id="content">

         <?php if ($mission): ?>
         <div id="mission">
           <?php print $mission; ?>
         </div>
         <!-- /mission -->
         <?php endif; ?>

         <?php if (!empty($content_top)):?>
         <div id="content-top" class="content-region">
           <?php print $content_top; ?>
         </div>
         <!-- /content-top -->
         <?php endif; ?>


         <?php if (!empty($content)):?>
         <div id="content-area" class="content-region">
           <div id="default-content">
             <?php print $content; ?>
           </div>
         </div>
         <!-- /content-area -->
         <?php endif; ?>

         <?php if (!empty($content_bottom)):?>
         <div id="content-bottom" class="content-region">
           <?php print $content_bottom; ?>
         </div>
          <!-- /content-bottom -->
         <?php endif; ?>

        </div>
        <!-- /content -->
      </div>
      <!-- /main -->

      <?php if (!empty($content_after)):?>
      <div id="after-content" class="footer-spacer extra-region">
        <?php print $content_after; ?>
      </div>
       <!-- /after-content -->
      <?php endif; ?>

      <?php if (!empty($content_down)):?>
      <div id="after-content-down" class="footer-spacer extra-region">
        <?php print $content_down; ?>
      </div>
       <!-- /after-content-down -->
      <?php endif; ?>



    </div>
    <!-- /page -->
    <div class="footer-wrapper">
    <div id="footer">
        <?php if (!empty($footer)):?>

        <div id="footer-blocks">
          <?php print $footer; ?>
        </div>
        <?php endif;?>

        <?php if (!empty($footer_message)):?>
        <div id="footer-message">
          <?php print $footer_message; ?>
        </div>
        <?php endif;?>

        <?php if ($accesibility_links):?>
        <a class="accessibility-link" href="#top"><?php print t('[Jump to Top]');?></a>
        <a class="accessibility-link" href="#content"><?php print t('[Jump to Main Content]');?></a>
        <?php endif;?>

      </div>
      <!-- /footer -->
    </div>
    <!-- /footer-wrapper -->
    <?php print $closure; ?>

      <script type="text/javascript">
        try {
        PostAffTracker.track();
        } catch (err) { }

      </script>

      <style>

#uc-cart-pane-quotes {
    background: url("/sites/all/themes/tendu/tendu_default/images/top-tabs-big.jpg") no-repeat scroll center top #FFF;

    position: relative;
}

#uc-cart-pane-quotes .solid-border {
    border: 0px;
    padding: 15px;
    background: url("/sites/all/themes/tendu/tendu_default/images/bottom-tabs-big.jpg") no-repeat scroll center bottom rgba(0, 0, 0, 0);
}


      </style>
  </body>
</html>
