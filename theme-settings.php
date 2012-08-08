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

  $form['chromeframe'] = array(
  '#type' => 'fieldset',
  '#title' => t('Chrome Frame'),
  '#description' => t('Google\'s Chrome Frame is an open source project for Internet Explorer 6, 7, 8, and 9 that allows those version of Internet Explorer to <a href="https://www.youtube.com/watch?v=sjW0Bchdj-w&feature=player_embedded" target="_blank">harness the power of Google Chrome\'s engine</a>.'),
  '#weight' => -100,
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
      'callback' => 'aurora_chromeframe_ajax_save'
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

  $form['optimizations'] = array(
   '#type' => 'fieldset',
   '#title' => t('Optimizations'),
   '#description' => t('Various little optimizations for your theme.'),
   '#weight' => -99,
  );
 
  // $form['optimizations']['aurora_min_html'] = array(
  //   '#type' => 'checkbox',
  //   '#title' => t('Minimize HTML'),
  //   '#default_value' => theme_get_setting('aurora_min_html'),
  //   '#ajax' => array(
  //     'callback' => 'aurora_chromeframe_ajax_save'
  //   ),
  //   '#description' => t('Will run Nathan Smith\'s <a href="http://sonspring.com/journal/html5-in-drupal-7#_minification" target="_blank">page minification</a> over your output HTML.'),
  // );
 
  $form['optimizations']['aurora_footer_js'] = array(
    '#type' => 'checkbox',
    '#title' => t('Move JavaScript to the Bottom'),
    '#default_value' => theme_get_setting('aurora_footer_js'),
    '#ajax' => array(
      'callback' => 'aurora_chromeframe_ajax_save'
    ),
    '#description' => t('Will move all JavaScript to the bottom of your page. This can be overridden on an individual basis by setting the <pre>\'force header\' => true</pre> option in <pre>drupal_add_js</pre> or by using <pre>hook_js_alter</pre> to add the option to other JavaScript files.'),
  );
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

function aurora_chromeframe_ajax_save($form, $form_state) {
  $theme = $form_state['build_info']['args'][0];
  $theme_settings = variable_get('theme_' . $theme . '_settings', array());
  $trigger = $form_state['triggering_element'] ['#name'];
  
  $theme_settings[$trigger] = $form_state['input'][$trigger];
  
  if (empty($theme_settings[$trigger])) {
    $theme_settings[$trigger] = 0;
  }
  variable_set('theme_' . $theme . '_settings', $theme_settings);
}