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

      // add_settings_field( // Option 2
      //     'option_2', // Option ID
      //     'Option 2', // Label
      //     'my_textbox_callback', // !important - This is where the args go!
      //     'general', // Page it will be displayed
      //     'custom_settings', // Name of our section (General Settings)
      //     array( // The $args
      //         'option_2' // Should match Option ID
      //     )
      // );

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




















  /*
  =selectBox

  This function generates a selectBox object

  PARAMETROS:
  $name => el nombre visible o "label" del select
  $options => un vector del tipo:
  array(
    'option_1_slug' => 'option_1_text',
    'option_2_slug' => 'option_2_text',
    'option_3_slug' => 'option_3_text',
  )
  $empty_label => el nombre visible de la opcion de vaciar el select
  $slug => el nombre invisible del select (para CSS) se concatena a selectBox, ejemplo:
  $slug = 'MiSelect' resulta en la clase -> 'selectBoxMiSelect'
  */
  function selectBox($name, $options = array(), $empty_label = 'Vaciar', $slug = false){
  	if(!$slug){ $slug = sanitize_title($name); }
  	?>
  	<div class="SelectBox selectBox<?php echo $slug; ?>" tabindex="1" id="selectBox<?php echo $slug; ?>">
  		<div class="selectBoxButton" onclick="altClassFromSelector('focus', '#selectBox<?php echo $slug; ?>')">
  			<p class="selectBoxPlaceholder"><?php echo $name; ?></p>
  			<p class="selectBoxCurrent" id="selectBoxCurrent<?php echo $slug; ?>"></p>
  		</div>
  		<div class="selectBoxList focus">
  			<label for="nul<?php echo $slug; ?>" class="selectBoxOption" id="selectBoxOptionNul"><?= $empty_label; ?>
  				<input
  					class="selectBoxInput"
  					id="nul<?php echo $slug; ?>"
  					type="radio"
  					name="<?php echo $slug; ?>"
  					onclick="selectBoxControler('','#selectBox<?php echo $slug; ?>','#selectBoxCurrent<?php echo $slug; ?>')"
  					value="0"
  					<?php if(!isset($_GET[$slug])){ ?>
  						checked
  					<?php } ?>
  				>
  				<!-- <span class="checkmark"></span> -->
  				<p class="colrOptP"></p>
  			</label>


  			<?php foreach ($options as $opt_slug => $opt_name) {
  				$opt_name = preg_replace('/\s+/', ' ', trim($opt_name)); ?>

  				<label for="<?php echo $slug; ?>_<?php echo $opt_slug; ?>" class="selectBoxOption">
  					<input
  						class="selectBoxInput <?php echo $opt_slug; ?>"
  						type="radio"
  						id="<?php echo $slug; ?>_<?php echo $opt_slug; ?>"
  						name="<?php echo $slug; ?>"
  						onclick="selectBoxControler('<?php echo $opt_name; ?>', '#selectBox<?php echo $slug; ?>', '#selectBoxCurrent<?php echo $slug; ?>')"
  						value="<?php echo $opt_slug; ?>"
  						<?php if(isset($_GET[$slug]) && $_GET[$slug] == $opt_slug){ ?>
  							checked
  						<?php } ?>
  					>
  					<!-- <span class="checkmark"></span> -->
  					<p class="colrOptP"><?php echo $opt_name; ?></p>
  				</label>


  			<?php } ?>
  		</div>
  	</div>
  <?php }














//Second solution : two or more files.
//If you're using a child theme you could use:
// get_stylesheet_directory_uri() instead of get_template_directory_uri()
add_action( 'admin_enqueue_scripts', 'load_admin_styles' );
function load_admin_styles() {
  // wp_enqueue_style( 'admin_css_foo', get_template_directory_uri() . '/css/backoffice.css', false, '1.0.0' );
}
