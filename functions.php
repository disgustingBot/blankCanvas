<?php

require_once 'inc/custom_posts.php';
require_once 'inc/form_handler.php';
require_once 'inc/new_ajax.php';

if(!is_admin()){
  require_once 'inc/multi_cards.php';
}

function lattte_setup(){
  wp_enqueue_style('style', get_stylesheet_uri(), NULL, microtime(), 'all');
	wp_enqueue_script('modules', get_theme_file_uri('/js/modules.js'), NULL, microtime(), true);



  // register our main script but do not enqueue it yet
  wp_register_script( 'main', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), NULL, microtime(), true );
  // now the most interesting part
  // we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
  // you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()
  wp_localize_script( 'main', 'lt_data', array(
    'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
    'homeurl' => site_url(),
    'front_page' => is_front_page(),
  ) );

  wp_enqueue_script( 'main' );
}
add_action('wp_enqueue_scripts', 'lattte_setup');

// Adding Theme Support

function gp_init() {
  add_theme_support('post-thumbnails');
  add_theme_support('title-tag');
  add_theme_support('html5',
    array('comment-list', 'comment-form', 'search-form')
  );
  // add_theme_support( 'woocommerce' );
  // add_theme_support( 'wc-product-gallery-zoom' );
  // add_theme_support( 'wc-product-gallery-lightbox' );
  // add_theme_support( 'wc-product-gallery-slider' );
}
add_action('after_setup_theme', 'gp_init');
















// this removes the "Archive" word from the archive title in the archive page
add_filter('get_the_archive_title',function($title){
  if(is_category()){$title=single_cat_title('',false);
  }elseif(is_tag()){$title=single_tag_title('',false);
  }elseif(is_author()){$title='<span class="vcard">'.get_the_author().'</span>';
  }return $title;
});






function excerpt($charNumber){
  if(!$charNumber){$charNumber=1000000;}
  $excerpt = get_the_excerpt();
  if(strlen($excerpt)<=$charNumber){return $excerpt;}else{
    $excerpt = substr($excerpt, 0, $charNumber);
    $result  = substr($excerpt, 0, strrpos($excerpt, ' '));
    // $result .= $result . '(...)';
    // return var_dump($excerpt);
    return $result;
  }
}









 function register_menus() {
   // register_nav_menu('navBar',__( 'Header' ));
   // register_nav_menu('navBarMobile',__( 'Header Mobile' ));
   // register_nav_menu('contactMenu',__( 'Contact Menu' ));
   // add_post_type_support( 'page', 'excerpt' );
 }
 add_action( 'init', 'register_menus' );
















 function get_img_url_by_slug($slug){
   return wp_get_attachment_url( get_img_id_by_slug($slug));
 }

 function get_img_id_by_slug( $slug ) {
   $args = array(
    'post_type' => 'attachment',
    'name' => sanitize_title($slug),
    'posts_per_page' => 1,
    'post_status' => 'inherit',
   );
   $_header = get_posts( $args );
   $header = $_header ? array_pop($_header) : null;
   return $header ? $header->ID : '';
 }

  // function responsive_img($id, $class, $size){
 function get_responsive_img($args){
   if(!isset($args['id']) and !isset($args['slug'])) return '';
   if(!isset($args['id'])){ $args['id'] = get_img_id_by_slug($args['slug']); }
   $id = $args['id'];
   // var_dump(get_img_id_by_slug($args['slug']));
   $defaults = array(
     'class' => 'responsive_img',
     'sizes' => array(
       ['572', '80'],
       ['768', '40'],
     ),
     'default_size' => 30,
     'unit' => 'vw',
     'size_name' => 'Medium',
     'width' => 400,
     'height' => 300,
     'loading' => 'lazy',
   );
   foreach ($defaults as $key => $value) {
     if (!isset($args[$key])) { $args[$key] = $value; }
   }

   $img = '<img';
   $img .= ' class="'.$args['class'].'"';
   $img .= ' loading="'.$args['loading'].'"';
   $img .= ' width="'.$args['width'].'"';
   $img .= ' height="'.$args['height'].'"';

   $src = wp_get_attachment_image_src( $id, $args['size_name'] )[0];
   $img .= ' src="'.$src.'"';

   $srcset = wp_get_attachment_image_srcset( $id, $args['size_name'] );
   $img .= ' srcset="'.$srcset.'"';

   // var_dump($args['sizes']);
   $sizes = array_map(function ($value)use($args){ return "(max-width: ".$value[0]."px) ".$value[1].$args['unit'];}, $args['sizes']);
   $sizes = implode(", ", $sizes) . ", ".$args['default_size'].$args['unit'];
   // var_dump($sizes);
   $img .= ' sizes="'.$sizes.'"';

   $alt = get_post_meta( $id, '_wp_attachment_image_alt', true);
   $img .= ' alt="'.$alt.'"';
   $img .= ' />';

   return $img;
   // return "<img class='$class' loading='lazy' width='400' height='300' src='".esc_attr( $src )."' srcset='".esc_attr( $srcset )."' sizes='".esc_attr( $sizes )."' alt='".esc_attr( $alt )."' />";
 }
 function responsive_img($args){echo get_responsive_img($args);}












  add_action('admin_init', 'my_general_section');
  function my_general_section() {
      add_settings_section(
          'custom_settings', // Section ID
          'Custom Settings', // Section Title
          'my_section_options_callback', // Callback
          'general' // What Page?  This makes the section show up on the General Settings Page
      );

      add_settings_field( // Option 1
          'contact_form_to', // Option ID
          'Recive messages from contact form here', // Label
          'my_textbox_callback', // !important - This is where the args go!
          'general', // Page it will be displayed (General Settings)
          'custom_settings', // Name of our section
          array( // The $args
              'contact_form_to' // Should match Option ID
          )
      );

      register_setting('general','contact_form_to', 'esc_attr');
      // register_setting('general','option_2', 'esc_attr');
  }

  function my_section_options_callback() { // Section Callback
      echo '<p>A little message on editing info</p>';
  }

  function my_textbox_callback($args) {  // Textbox Callback
      $option = get_option($args[0]);
      echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
  }














//Second solution : two or more files.
//If you're using a child theme you could use:
// get_stylesheet_directory_uri() instead of get_template_directory_uri()
add_action( 'admin_enqueue_scripts', 'load_admin_styles' );
function load_admin_styles() {
  // wp_enqueue_style( 'admin_css_foo', get_template_directory_uri() . '/css/backoffice.css', false, '1.0.0' );
}
