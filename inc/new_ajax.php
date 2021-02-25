<?php




add_action('pre_get_posts','alter_query_2');

function alter_query_2($query) {
	if(!is_admin()){
		//gets the global query var object
		// global $wp_query;

		//gets the front page id set in options
		$front_page_id = get_option('page_on_front');

		// don't execute if not main cycle or cycle is defined
		if ( !$query->is_main_query() ){
			if(!isset($query->query_vars['cycle'])) return;
		}
		if(!isset($query->query_vars['cycle'])){
			$cycle = 'filters';
		} else {
			$cycle = $query->query_vars['cycle'];
		}
		// echo '<h1>TEST</h1>';
		// echo get_query_var( 'paged' );

			// $args['paged'] = $page;
			// if (isset($_GET['page']) AND $_GET['page'] <= $max_page ) {


		if(isset($_GET[$cycle])){
			$fil_pa_sea = json_decode( stripslashes($_GET[$cycle]), true );
			if(isset($fil_pa_sea['page'])){
				$query-> set('paged' , $fil_pa_sea['page']);
			} else {
				$query-> set('paged' , 1);
			}

			// var_dump($_GET[$cycle]);
			if(isset($fil_pa_sea['tax'])){
				// var_dump($fil_pa_sea['tax']);
				$query->query_vars['tax_query'] = array();
				foreach ($fil_pa_sea['tax'] as $key => $value) {
					if(isset($value['taxonomy']) and isset($value['parent']) and isset($value['terms'])){
						$query->query_vars['tax_query'][$value['parent']] = array(
							'taxonomy' => $value['taxonomy'],
							'field'    => 'slug',
							'terms'    => $value['terms'],
							// 'operator' => 'IN',
						);
					}
				}
			}


			if(isset($fil_pa_sea['search'])){
				$query->query_vars['s'] = $fil_pa_sea['search'];
			}
		}
		// var_dump($query->query_vars['tax_query']);


		// if (isset($_GET['pag'])) {
		// 	$query-> set('paged' , $_GET['pag']);
		// } else {
		// 	$query-> set('paged' , 1);
		// }

		// $filters = array('programas', 'reefer', 'special', 'condition', 'size');
		// foreach ($filters as $key => $value) {
		// 	if (isset($_GET[$value])) {
		// 		$query->query_vars['tax_query'][$value] = array(
		// 			'taxonomy' => 'product_cat',
		// 			'field'    => 'slug',
		// 			'terms'    => $_GET[$value],
		// 		);
		// 	}
		// }

		//we remove the actions hooked on the '__after_loop' (post navigation)
		remove_all_actions ( '__after_loop');
	}
}







// // aqui la idea es que esta funcion ingresa los filtros y paginacion
// // de la url en los argumentos del cycle
// function process_args ($name, $args){
//
// 	if(isset($_GET[$name])){
// 		$fil_pa_sea = json_decode( stripslashes($_GET[$name]), true );
// 		if(isset($fil_pa_sea['page'])){
// 			$args['paged'] = $fil_pa_sea['page'];
// 		}
// 	}
// 	return $args;
// }















// PAGINATION
// bloque inspirado en el comienzo de este post:
// https://rudrastyh.com/wordpress/load-more-and-pagination.html
function ajax_paginator_2( $query ){

	// the function works only with $query that's why we must use query_posts() instead of WP_Query()
	// global $query;

	// // remove the trailing slash if necessary
	// $first_page_url = untrailingslashit( $first_page_url );
	//
	//
	// // it is time to separate our URL from search query
	// $first_page_url_exploded = array(); // set it to empty array
	// $first_page_url_exploded = explode("/?", $first_page_url);
	// // by default a search query is empty
	// $search_query = '';
	// // if the second array element exists
	// if( isset( $first_page_url_exploded[1] ) ) {
	// 	$search_query = "/?" . $first_page_url_exploded[1];
	// 	$first_page_url = $first_page_url_exploded[0];
	// }

	// get parameters from $query object
	// how much posts to display per page (DO NOT SET CUSTOM VALUE HERE!!!)
	$posts_per_page = (int) $query->query_vars['posts_per_page'];
	// current page
	$current_page = (int) $query->query_vars['paged'];
	// the overall amount of pages
	$max_page = $query->max_num_pages;

	// we don't have to display pagination or load more button in this case
	if( $max_page <= 1 ) return;

	// set the current page to 1 if not exists
	if( empty( $current_page ) || $current_page == 0) $current_page = 1;

	// you can play with this parameter - how much links to display in pagination
	$links_in_the_middle = 4;
	$links_in_the_middle_minus_1 = $links_in_the_middle-1;

	// the code below is required to display the pagination properly for large amount of pages
	// I mean 1 ... 10, 12, 13 .. 100
	// $first_link_in_the_middle is 10
	// $last_link_in_the_middle is 13
	$first_link_in_the_middle = $current_page - floor( $links_in_the_middle_minus_1/2 );
	$last_link_in_the_middle = $current_page + ceil( $links_in_the_middle_minus_1/2 );

	// some calculations with $first_link_in_the_middle and $last_link_in_the_middle
	if( $first_link_in_the_middle <= 0 ) $first_link_in_the_middle = 1;
	if( ( $last_link_in_the_middle - $first_link_in_the_middle ) != $links_in_the_middle_minus_1 ) { $last_link_in_the_middle = $first_link_in_the_middle + $links_in_the_middle_minus_1; }
	if( $last_link_in_the_middle > $max_page ) { $first_link_in_the_middle = $max_page - $links_in_the_middle_minus_1; $last_link_in_the_middle = (int) $max_page; }
	if( $first_link_in_the_middle <= 0 ) $first_link_in_the_middle = 1;

	// begin to generate HTML of the pagination
	$pagination = '<nav class="pagination" role="navigation">';

	// when to display "..." and the first page before it
	if ($first_link_in_the_middle >= 3 && $links_in_the_middle < $max_page) {
		$pagination.= '<a class="pagination_link" data-pagination="1">1</a>';

		if( $first_link_in_the_middle != 2 )
			$pagination .= '<span class="page-numbers extend">...</span>';
	}

	// arrow left (previous page)
	if ($current_page != 1)
		$pagination.= '<a class="pagination_link prev" data-pagination="prev">prev</a>';


	// loop page links in the middle between "..." and "..."
	for($i = $first_link_in_the_middle; $i <= $last_link_in_the_middle; $i++) {
		if($i == $current_page) {
			$pagination.= '<span class="paginationCurrent">'.$i.'</span>';
		} else {
			$pagination .= '<a class="pagination_link" data-pagination="'.$i.'">'.$i.'</a>';
		}
	}

	// arrow right (next page)
	if ($current_page != $last_link_in_the_middle )
		$pagination.= '<a class="pagination_link next" data-pagination="next">next</a>';


	// when to display "..." and the last page after it
	if ( $last_link_in_the_middle < $max_page ) {

		if( $last_link_in_the_middle != ($max_page-1) )
			$pagination .= '<span class="page-numbers extend">...</span>';

		$pagination .= '<a class="pagination_link" data-pagination="'. $max_page .'">'. $max_page .'</a>';
	}

	// end HTML
	// $pagination.= "</div></nav>\n";
	$pagination.= "</nav>\n";

	// haha, this is our load more posts link
	// if( $current_page < $max_page )
		// $pagination.= '<div id="misha_loadmore">More posts</div>';

	// replace first page before printing it
	echo str_replace(array("/page/1?", "/page/1\""), array("?", "\""), $pagination);
}






// Receive the Request post that came from AJAX
add_action( 'wp_ajax_lt_pagination_2', 'lt_pagination_2' );
// We allow non-logged in users to access our pagination
add_action( 'wp_ajax_nopriv_lt_pagination_2', 'lt_pagination_2' );
function lt_pagination_2() {
	//gets the global query var object
	// global $wp_query;
	// $respuesta = [];

	if(!is_admin()){
	  require_once 'inc/multi_cards.php';
	}

	$args = json_decode( stripslashes( $_POST['query'] ), true );
	// var_dump( $args );

	// $page = sanitize_text_field($_POST['page']);
	// $args['paged'] = $page;
	$args['post_status'] = 'publish';
	$card = $_POST['card'];

	// query_posts( $args );

	$my_query = new WP_Query($args);

	include 'multi_cards.php';

	if( $my_query->have_posts() ){
		// run the loop
		while($my_query->have_posts()){$my_query->the_post();
			// usar la tarjeta apropiada
			// TODO: escape and sanitize the input from user
			// this is totally not safe php
			call_user_func($card);

		}
		echo ajax_paginator_2($my_query);
	}

	exit();
}

?>
