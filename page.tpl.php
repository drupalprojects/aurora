<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language->language ?>" xml:lang="<?php print $language->language ?>">

<head>
  <title><?php print $head_title ?></title>
  <?php print $head ?>
  <?php print $styles ?>
  <?php print $scripts ?>
  <script type="text/javascript"><?php /* Needed to avoid Flash of Unstyle Content in IE */ ?> </script>
  </head>

<body id="<?php if ($is_front) { print 'home'; } else { print 'second'; } ?>">
  <div id="page" class="<?php if ($left || $right) { print "one-sidebar"; } if ($right && $left) { print " two-sidebars"; }?>">
  
    <div id="header">
    
      <div id="logo-title">
       
        <?php if ($logo): ?>
          <a href="<?php print $base_path ?>" title="<?php print t('Home') ?>">
            <img src="<?php print $logo ?>" alt="<?php print t('Home') ?>" id="logo" />
          </a>
        <?php endif; ?>
        
        <?php if ($site_name): ?>
          <h1 id='site-name'>
            <a href="<?php print $base_path ?>" title="<?php print t('Home') ?>">
              <?php print $site_name ?>
            </a>
          </h1>
        <?php endif; ?>
        
        <?php if ($site_slogan): ?> 
          <div id='site-slogan'>
            <?php print $site_slogan ?>
          </div>
        <?php endif; ?>	        
      </div>
  
      <div class="menu <?php if ($primary_links) { print "withprimary"; } if ($secondary_links) { print " withsecondary"; } ?> ">          
          <?php if ($secondary_links): ?>
            <div id="secondary" class="clear-block">
              <?php print aurora_tabs($secondary_links, array('class' => 'secondary')) ?>
            </div>
          <?php endif; ?>

		  
      </div>
      
      <?php if ($header): ?>
        <div id="header-region">
          <?php print $header ?>
        </div>
      <?php endif; ?> 	
    </div>	
	
		 <?php if ($primary_links): ?>
            <div id="primary" class="clear-block">
              <?php print aurora_tabs($primary_links, array('class' => 'primary')) ?>
            </div>
          <?php endif; ?>
		  
	      

	
	<div id="container" class="<?php if ($left) { print "withleft"; } if ($right) { print " withright"; }?> clear-block">     
      <div id="main-wrapper">	  
      <div id="main" class="clear-block">
        <?php print $breadcrumb ?>
        <?php if ($mission): ?><div id="mission"><?php print $mission ?></div><?php endif; ?>
        <?php if ($content_top):?><div id="content-top"><?php print $content_top ?></div><?php endif; ?>
        <?php if ($title): ?><h1 class="title"><?php print $title ?></h1><?php endif; ?>
        <?php if ($tabs): ?><div class="tabs"><?php print $tabs ?></div><?php endif; ?>
        <?php print $help ?>
        <?php print $messages ?>
        <?php print $content ?>
        <?php if ($content_bottom): ?><div id="content-bottom"><?php print $content_bottom ?></div><?php endif; ?>
      </div>
      </div>
      
      <?php if ($left): ?>
        <div id="sidebar-left" class="sidebar">
          <?php print $search_box ?>     
          <?php print $left ?>
        </div>
      <?php endif; ?>  

      <?php if ($right): ?>
        <div id="sidebar-right" class="sidebar">
          <?php print $right ?>
        </div>
      <?php endif; ?>

    </div>

    <div id="footer">
      <?php print $footer ?>
      <?php print $footer_message ?>
    </div>
	<div class="copy">
		<p>Powered by <a href="http://www.drupal.org/">Drupal</a> - <!-- Please do not remove this command line --> Design by <a href="http://www.artinet.ru/">artinet</a></p>
	</div>
    
    <?php print $closure ?>
    
  </div>
</body>
</html>
