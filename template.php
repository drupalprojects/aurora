<?php
/**
 * Implements hook_preprocess_html()
 */
function aurora_preprocess_html(&$vars) {
  // Viewport!
  $viewport = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'viewport',
      'content' => 'width=device-width, initial-scale=1',
    ),
  );
  drupal_add_html_head($viewport, 'viewport');
  
  if (theme_get_setting('aurora_enable_chrome_frame')) {
    // Force IE to use most up-to-date render engine.
    $xua = array(
      '#tag' => 'meta',
      '#attributes' => array(
        'http-equiv' => 'X-UA-Compatible',
        'content' => 'IE=edge,chrome=1',
      ),
    );
    drupal_add_html_head($xua, 'x-ua-compatible');
    
    // Chrome Frome
    $chromeframe['wrapper'] = '<!--[if lt IE ' . theme_get_setting('aurora_min_ie_support') . ' ]>';
    $chromeframe['include']['element'] = array(
      '#tag' => 'script',
      '#attributes' => array(
        'src' => '//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js',
      ),
    );
    $chromeframe['launch']['element'] = array(
      '#tag' => 'script',
      '#attributes' => array(),
      '#value' => 'window.attachEvent(\'onload\',function(){CFInstall.check({mode:\'overlay\'})})',
    );

    $vars['chromeframe_array'] = $chromeframe;
  }
  
  //////////////////////////////
  // HTML5 Base Theme Forwardport
  //
  // Backports the following changes made to Drupal 8:
  // - #1077566: Convert html.tpl.php to HTML5.
  //////////////////////////////
  // Initializes attributes which are specific to the html and body elements.
  $vars['html_attributes_array'] = array();
  $vars['body_attributes_array'] = array();

  // HTML element attributes.
  $vars['html_attributes_array']['lang'] = $GLOBALS['language']->language;
  $vars['html_attributes_array']['dir'] = $GLOBALS['language']->direction ? 'rtl' : 'ltr';

  // Update RDF Namespacing
  if (module_exists('rdf')) {
    // Adds RDF namespace prefix bindings in the form of an RDFa 1.1 prefix
    // attribute inside the html element.
    $prefixes = array();
    foreach (rdf_get_namespaces() as $prefix => $uri) {
      $vars['html_attributes_array']['prefix'][] = $prefix . ': ' . $uri . "\n";
    }
  }
}

/**
 * Implements hook_process_html().
 */
function aurora_process_html(&$vars) {
  //////////////////////////////
  // Chrome Frame
  //////////////////////////////
  if (theme_get_setting('aurora_enable_chrome_frame')) {
    $cf_array = $vars['chromeframe_array'];
    $cf = $cf_array['wrapper'] . theme_html_tag($cf_array['include']) . theme_html_tag($cf_array['launch']) . '<![endif]-->';

    $vars['page_top'] .= $cf;
  }
  
  //////////////////////////////
  // HTML5 Base Theme Forwardport
  //
  // Backports the following changes made to Drupal 8:
  // - #1077566: Convert html.tpl.php to HTML5.
  //////////////////////////////
  // Flatten out html_attributes and body_attributes.
  $vars['html_attributes'] = drupal_attributes($vars['html_attributes_array']);
  $vars['body_attributes'] = drupal_attributes($vars['body_attributes_array']);
  
  //////////////////////////////
  // Minify HTML
  //////////////////////////////
  // Need to test more, on initial test was giving larger results!
  // if (theme_get_setting('aurora_min_html')) {
  //     $before = array(
  //       "/>\s\s+/",
  //       "/\s\s+</",
  //       "/>\t+</",
  //       "/\s\s+(?=\w)/",
  //       "/(?<=\w)\s\s+/"
  //     );
  // 
  //     $after = array('> ', ' <', '> <', ' ', ' ');
  // 
  //     // Page top.
  //     $page_top = $vars['page_top'];
  //     $page_top = preg_replace($before, $after, $page_top);
  //     $vars['page_top'] = $page_top;
  // 
  //     // Page content.
  //     if (!preg_match('/<pre|<textarea/', $vars['page'])) {
  //       $page = $vars['page'];
  //       $page = preg_replace($before, $after, $page);
  //       $vars['page'] = $page;
  //     }
  // 
  //     // Page bottom.
  //     $page_bottom = $vars['page_bottom'];
  //     $page_bottom = preg_replace($before, $after, $page_bottom);
  //     $vars['page_bottom'] = $page_bottom . drupal_get_js('footer');
  //   }
}

/**
 * Implements hook_js_alter().
 */
function aurora_js_alter(&$javascript) {
  if (theme_get_setting('aurora_footer_js')) {
    foreach ($javascript as &$js) {
      if (empty($js['force header']) || !$js['force header']) {
        $js['scope'] = 'footer';
      }
      else {
        $js['scope'] = 'header';
      }
    }
  }
}

//////////////////////////////
// HTML5 Base Theme Forwardport
//////////////////////////////
/**
 * Implements the hook_preprocess_comment().
 *
 * Backports the following variable changes to Drupal 8:
 * - #1189816: Convert comment.tpl.php to HTML5.
 */
function aurora_preprocess_comment(&$variables) {
  $variables['user_picture'] = theme_get_setting('toggle_comment_user_picture') ? theme('user_picture', array('account' => $comment)) : '';
}

/**
 * Implements hook_preprocess_user_profile_category().
 *
 * Backports the following changes to made Drupal 8:
 * - #1190218: Convert user-profile-category.tpl.php to HTML5.
 */
function aurora_preprocess_user_profile_category(&$variables) {
  $variables['classes_array'][] = 'user-profile-category-' . drupal_html_class($variables['title']);
}

/**
 * Implements hook_css_alter().
 *
 * Backports the following CSS changes made to Drupal 8:
 * - #1216950: Clean up the CSS for Block module.
 * - #1216948: Clean up the CSS for Aggregator module.
 * - #1216972: Clean up the CSS for Color module.
 *
 */
function aurora_css_alter(&$css) {
  // Path to the theme's CSS directory.
  $dir = drupal_get_path('theme', 'html5') . '/css';

  // Swap out aggregator.css with the aggregator.theme.css provided by this theme.
  $aggregator = drupal_get_path('module', 'aggregator');
  if (isset($css[$aggregator . '/aggregator.css'])) {
    $css[$aggregator . '/aggregator.css']['data'] = $dir . '/aggregator/aggregator.theme.css';
  }
  if (isset($css[$aggregator . '/aggregator-rtl.css'])) {
    $css[$aggregator . '/aggregator-rtl.css']['data'] = $dir . '/aggregator/aggregator.theme-rtl.css';
  }

  // Swap out block.css with the block.admin.css provided by this theme.
  $block = drupal_get_path('module', 'block');
  if (isset($css[$block . '/block.css'])) {
    $css[$block . '/block.css']['data'] = $dir . '/block/block.admin.css';
  }

  // Swap out color.css with the color.admin.css provided by this theme.
  $color = drupal_get_path('module', 'color');
  if (isset($css[$color . '/color.css'])) {
    $css[$color . '/color.css']['data'] = $dir . '/color/color.admin.css';
  }
  if (isset($css[$color . '/color-rtl.css'])) {
    $css[$color . '/color-rtl.css']['data'] = $dir . '/color/color.admin-rtl.css';
  }

}

/**
 * Implements hook_preprocess_node().
 *
 * Backports the following changes made to Drupal 8:
 * - #1077602: Convert node.tpl.php to HTML5.
 */
function aurora_preprocess_node(&$variables) {
  // Add article ARIA role.
  $variables['attributes_array']['role'] = 'article';
}
