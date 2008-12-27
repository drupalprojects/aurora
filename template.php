<?php

/**
 * ABOUT
 *
 *  The template.php file is one of the most useful files when creating or modifying Drupal themes.
 *  You can add new regions for block content, modify or override Drupal's theme functions, 
 *  intercept or make additional variables available to your theme, and create custom PHP logic.
 *  For more information, please visit the Theme Developer's Guide on Drupal.org:
 *  http://drupal.org/node/509
 */

 
/**
 * OVERRIDING THEME FUNCTIONS
 *
 *  The Drupal theme system uses special theme functions to generate HTML output automatically.
 *  Often we wish to customize this HTML output.  To do this, we have to override the theme function.
 *  You have to first find the theme function that generates the output, and then "catch" it and modify it here.
 *  The easiest way to do it is to copy the original function in its entirety and paste it here, changing
 *  the prefix from theme_ to zen_.  For example:
 *
 *   original:  theme_breadcrumb() 
 *   theme override:   aurora_breadcrumb()
 *
 *  See the following example. In this theme, we want to change all of the breadcrumb separator links from  >> to ::
 *
 */

/**
  * Return a themed breadcrumb trail.
  *
  * @param $breadcrumb
  *   An array containing the breadcrumb links.
  * @return a string containing the breadcrumb output.
  */
/* function aurora_breadcrumb($breadcrumb) {
   if (!empty($breadcrumb)) {
     return '<div class="breadcrumb">'. implode(' :: ', $breadcrumb) .'</div>';
   }
 } */
 
 
/** 
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *  The most powerful function available to themers is the _phptemplate_variables() function. It allows you
 *  to pass newly created variables to different template (tpl.php) files in your theme. Or even unset ones you don't want
 *  to use.
 *
 *  It works by switching on the hook, or name of the theme function, such as:
 *    - page
 *    - node
 *    - comment
 *    - block
 *
 * By switching on this hook you can send different variables to page.tpl.php file, node.tpl.php
 * (and any other derivative node template file, like node-forum.tpl.php), comment.tpl.php, and block.tpl.php
 *
 */

 
function aurora_preprocess_node(&$vars) {
  // Send a new variable, $has_terms, to see wether the current node has any terms
  $vars['has_terms'] = count(taxonomy_node_get_terms($vars['node'])) > 0;
  return $vars;
}

function aurora_tabs($tabs, $attributes = array()) {
  $attributes['class'] .= ' tabs';
  $out = '<ul class="tabs">';
  foreach ($tabs as $tab) $out .= theme('menu_local_task', theme('menu_item_link', $tab));
  $out .= '</ul>';
  return $out;
}
