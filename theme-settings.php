<?php
/**
 * Implements hook_form_system_theme_settings_alter() function.
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function aurora_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL) {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  // From the always awesome Zen
  if (isset($form_id)) {
   return;
  }
  drupal_add_css(drupal_get_path('theme', 'aurora') . '/css/settings.css');
  
  //////////////////////////////
  // Chrome Frame
  //////////////////////////////

  $form['chromeframe'] = array(
  '#type' => 'fieldset',
  '#title' => t('Chrome Frame'),
  '#description' => t('Google\'s Chrome Frame is an open source project for Internet Explorer 6, 7, 8, and 9 that allows those version of Internet Explorer to <a href="!link target="_blank">harness the power of Google Chrome\'s engine</a>.', array('!link' => 'https://www.youtube.com/watch?v=sjW0Bchdj-w&feature=player_embedded"')),
  '#weight' => -100,
  '#attributes' => array('class' => array('aurora-row-left')),
  '#prefix' => '<span class="aurora-settigns-row">',
  '#parents' => array('vtb'),
  );

  $form['chromeframe']['aurora_enable_chrome_frame'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Chrome Frame'),
    '#default_value' => theme_get_setting('aurora_enable_chrome_frame'),
    '#ajax' => array(
      'callback' => 'aurora_chromeframe_options',
      'wrapper' => 'cf-settings',
      'method' => 'replace'
    ),
    
   );
 
  $form['chromeframe']['aurora_min_ie_support'] = array(
    '#type' => 'select',
    '#title' => t('Minimum supported Internet Explorer version'),
    '#options' => array(
      10 => t('Internet Explorer 10'),
      9 => t('Internet Explorer 9'),
      8 => t('Internet Explorer 8'),
      7 => t('Internet Explorer 7'),
      6 => t('Internet Explorer 6'),
    ),
    '#default_value' => theme_get_setting('aurora_min_ie_support'),
    '#description' => t('The minimum version number of Internet Explorer that you actively support. The Chrome Frame prompt will display for any version below this version number.'),
    '#prefix' => '<span id="cf-settings">',
    '#suffix' => '</span>',
    '#ajax' => array(
      'callback' => 'aurora_ajax_settings_save'
     ),
  );

  if (theme_get_setting('aurora_enable_chrome_frame') || $form_state['rebuild']) {
   if ($form_state['rebuild']) {
     if ($form_state['triggering_element']['#name'] == 'aurora_enable_chrome_frame') {
       if ($form_state['triggering_element']['#value'] == 1) {
       
         $form['chromeframe']['aurora_min_ie_support']['#disabled'] = false;
       }
       else {
         $form['chromeframe']['aurora_min_ie_support']['#disabled'] = true;
       }
     }
   }
   else {
     $form['chromeframe']['aurora_min_ie_support']['#disabled'] = false;
   }
  }
  else {
   $form['chromeframe']['aurora_min_ie_support']['#disabled'] = true;
  }
  
  //////////////////////////////
  // Optimizations
  //////////////////////////////

  $form['optimizations'] = array(
   '#type' => 'fieldset',
   '#title' => t('Optimizations'),
   '#description' => t('Various little optimizations for your theme.'),
   '#weight' => -99,
   '#attributes' => array('class' => array('aurora-row-right')),
   '#suffix' => '</span>',
  );
 
  $form['optimizations']['aurora_footer_js'] = array(
    '#type' => 'checkbox',
    '#title' => t('Move JavaScript to the Bottom'),
    '#default_value' => theme_get_setting('aurora_footer_js'),
    '#ajax' => array(
      'callback' => 'aurora_ajax_settings_save'
    ),
    '#description' => t('Will move all JavaScript to the bottom of your page. This can be overridden on an individual basis by setting the <pre>\'force header\' => true</pre> option in <pre>drupal_add_js</pre> or by using <pre>hook_js_alter</pre> to add the option to other JavaScript files.'),
  );
  
  $form['optimizations']['aurora_html_tags'] = array(
    '#type' => 'checkbox',
    '#title' => t('Prune HTML Tags'),
    '#default_value' => theme_get_setting('aurora_html_tags'),
    '#ajax' => array(
      'callback' => 'aurora_ajax_settings_save'
    ),
    '#description' => t('Prunes your <pre>style</pre>, <pre>link</pre>, and <pre>script</pre> tags as <a href="!link" target="_blank"> suggested by Nathan Smith</a>.', array('!link' => 'http://sonspring.com/journal/html5-in-drupal-7#_pruning')),
  );
  
  //////////////////////////////
  // Development
  //////////////////////////////
  
  $form['development'] = array(
    '#type' => 'fieldset',
    '#title' => t('Development'),
    '#description' => t('Theme like you\'ve never themed before! <div class="messages warning"><strong>WARNING:</strong> These options incur huge performance penalties and <em>must</em> be turned off on production websites.</div>'),
    '#weight' => 52
  );
  
  $form['development']['aurora_rebuild_registry'] = array(
    '#type' => 'checkbox',
    '#title' => t('Rebuild Theme Registry on Reload'),
    '#default_value' => theme_get_setting('aurora_rebuild_registry'),
    '#ajax' => array(
      'callback' => 'aurora_ajax_settings_save'
    ),
    '#description' => t('<a href="!link" target="_blank">Rebuild the theme registry</a> during project development.', array('!link' => 'http://drupal.org/node/173880#theme-registry')),
  );
  
  $form['development']['aurora_livereload'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable LiveReload'),
    '#default_value' => theme_get_setting('aurora_livereload'),
    '#ajax' => array(
      'callback' => 'aurora_ajax_settings_save'
    ),
    '#description' => t('Enable <a href="!link" target="_blank">LiveReload</a> to refresh your browser without you needing to. Awesome for designing in browser.', array('!link' => 'http://livereload.com/')),
    '#weight' => 200,
  );
  
  $form['development']['aurora_viewport_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Viewport Width Indicator'),
    '#default_value' => theme_get_setting('aurora_viewport_width'),
    '#ajax' => array(
      'callback' => 'aurora_ajax_settings_save'
    ),
    '#description' => t('Displays an indicator of the viewport. Tap/click to toggle between <em>em</em> and <em>px</em>/'),
    '#weight' => 225,
  );
  
  $form['development']['aurora_modernizr_debug'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Modernizr Indicator'),
    '#default_value' => theme_get_setting('aurora_modernizr_debug'),
    '#ajax' => array(
      'callback' => 'aurora_ajax_settings_save'
    ),
    '#description' => t('Displays an indicator of <a href="!link" target="_blank">Modernizr</a> detected features. Tap/click to toggle display of all of the available features.', array('!link' => 'http://modernizr.com/')),
    '#weight' => 250,
  );
  
  //////////////////////////////
  // Theme Settings Update
  //////////////////////////////
  unset($form['theme_settings']['toggle_main_menu']);
  unset($form['theme_settings']['toggle_secondary_menu']);
  $form['theme_settings']['toggle_breadcrumbs'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show Breadcrumbs'),
    '#default_value' => theme_get_setting('toggle_breadcrumbs'),
  );
  
  $form['theme_settings']['toggle_logo']['#ajax'] = array('callback' => 'aurora_ajax_settings_save');
  $form['theme_settings']['toggle_name']['#ajax'] = array('callback' => 'aurora_ajax_settings_save');
  $form['theme_settings']['toggle_slogan']['#ajax'] = array('callback' => 'aurora_ajax_settings_save');
  $form['theme_settings']['toggle_node_user_picture']['#ajax'] = array('callback' => 'aurora_ajax_settings_save');
  $form['theme_settings']['toggle_comment_user_picture']['#ajax'] = array('callback' => 'aurora_ajax_settings_save');
  $form['theme_settings']['toggle_comment_user_verification']['#ajax'] = array('callback' => 'aurora_ajax_settings_save');
  $form['theme_settings']['toggle_favicon']['#ajax'] = array('callback' => 'aurora_ajax_settings_save');
  $form['theme_settings']['toggle_breadcrumbs']['#ajax'] = array('callback' => 'aurora_ajax_settings_save');
  
  //////////////////////////////
  // Logo and Favicon
  //////////////////////////////
  $form['logo']['#weight'] = 10;
  $form['logo']['#attributes']['class'][] = 'aurora-row-left';
  $form['logo']['#prefix'] = '<span class="aurora-settigns-row">';
  $form['logo']['default_logo']['#ajax'] = array('callback' => 'aurora_ajax_settings_save');
  
  $form['favicon']['#weight'] = 11;
  $form['favicon']['#attributes']['class'][] = 'aurora-row-right';
  $form['favicon']['#suffix'] = '</span>';
  $form['favicon']['default_favicon']['#ajax'] = array('callback' => 'aurora_ajax_settings_save');
}

function aurora_chromeframe_options($form, $form_state) {
  $form_state['hello'] = 'world';
  $theme = $form_state['build_info']['args'][0];
  $theme_settings = variable_get('theme_' . $theme . '_settings', array());
  
  $theme_settings['aurora_enable_chrome_frame'] = $form_state['input']['aurora_enable_chrome_frame'];
  variable_set('theme_' . $theme . '_settings', $theme_settings);

  if ($form_state['input']['aurora_enable_chrome_frame'] == 1) {
    $form['chromeframe']['aurora_min_ie_support']['#disabled'] = false;
    return drupal_render($form['chromeframe']['aurora_min_ie_support']);
  }
  else {
    $form['chromeframe']['aurora_min_ie_support']['#disabled'] = true;
    return drupal_render($form['chromeframe']['aurora_min_ie_support']);
  }
  return '';
}

function aurora_ajax_settings_save($form, $form_state) {
  $theme = $form_state['build_info']['args'][0];
  $theme_settings = variable_get('theme_' . $theme . '_settings', array());
  $trigger = $form_state['triggering_element'] ['#name'];
  
  $theme_settings[$trigger] = $form_state['input'][$trigger];
  
  if (empty($theme_settings[$trigger])) {
    $theme_settings[$trigger] = 0;
  }
  variable_set('theme_' . $theme . '_settings', $theme_settings);
}