<?php


add_action(        'admin_post_lt_form_handler', 'lt_form_handler');
add_action( 'admin_post_nopriv_lt_form_handler', 'lt_form_handler');
function lt_form_handler() {
	$debugMode = false;
	$respuesta = array();


  $link=$_POST['link'];

	if($_POST['a00'] != ""){
		$link = add_query_arg( array('no' => 'go',), $link );
	} else {
    $email='molinerozadkiel@gmail.com';

    





		// $message='';

    // foreach ($_POST as $key => $value) {
    //   if ( $key != 'a00' && $key != 'action' && $key != 'link' && $key != 'status' && $key != 'submit' && $key != 'g-recaptcha-response' ) {
    //     $message=$message.'<strong>'.$key.':</strong> '.$value.' - <br>';
    //   }
    // }

    $the_slug = 'contact-response';
    $args=array(
      'name'           => $the_slug,
      'post_type'      => 'mail',
      'post_status'    => 'publish',
      'posts_per_page' => 1,
    );
    $mail=new WP_Query();
    $mail->query($args);
  
    while($mail->have_posts()){$mail->the_post();
      $subject = get_the_title();
      $message = get_the_content();
    }







    $headers = array('Content-Type: text/html; charset=UTF-8');


    $site = '6LeE7b8ZAAAAAJSXcqghZa6spv9aUCuaZm1k8hjh';
    $scrt = '6LeE7b8ZAAAAALYT37X6JwPR0gbVFLpU7I-J-DOk';

    $response = $_POST['g-recaptcha-response'];
    $payload = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$scrt.'&response='.$response);

    $result = json_decode($payload,true);
    if ($result['success']!=1) {
    $link = add_query_arg( array( 'status' => 'bot' , ), $link );
    } else {


      // if (wp_mail( $email , $subject , $message , $headers )) {
      if (wp_mail( $_POST['email'] , $subject , $message , $headers )) {
        $link = add_query_arg( array( 'status' => 'sent' , ), $link );
      } else {
        $link = add_query_arg( array( 'status' => 'error', ), $link );
      }
    }
	}
	wp_redirect($link);
	// if($debugMode){echo wp_json_encode($respuesta);}
	exit();
}






add_action(        'wp_ajax_lt_ajax_mail', 'lt_ajax_mail');
add_action( 'wp_ajax_nopriv_lt_ajax_mail', 'lt_ajax_mail');
function lt_ajax_mail() {
	$debugMode = true;
	$respuesta = array();


  // $link=$_POST['link'];
  $cont=json_decode(stripslashes($_POST['cont']));
  $currency        = $_POST[ 'currency' ];
  $country         = $_POST[ 'country' ];
  $phone           = $_POST[ 'phone' ];
  $city            = $_POST[ 'city' ];
  $name            = $_POST[ 'name' ];
  $mail            = $_POST[ 'mail' ];
  $title           = $_POST[ 'title' ];
  $destino_city    = $_POST[ 'destino_city' ];
  $destino_country = $_POST[ 'destino_country' ];

  $destino = '';
  $origen = " - En: <span class='ubicacion'>$country - $city</span>";
  if(isset($_POST['destino_country'])){
    $destino = "Hasta: <span class='ubicacion'>$destino_country - $destino_city</span>";
    $origen = " - Desde: <span class='ubicacion'>$country - $city</span>";
  }

	// if($_POST['a00'] != ""){
    // $respuesta['gate0'] = 'ROBOT';
		// $link = add_query_arg( array('status' => 'nope',), $link );
	// } else {
    // $respuesta['gate0'] = 'no es robot';



    // $respuesta['cont'] = json_encode($cont);
    $totalPrice = 0;
    $tablaDePrecios = '';
    foreach ($cont as $key => $value) {
      $respuesta[$key] = $value;
      if($value->singlePrice != 'Precio no disponible'){
        $finalPrice = $value->qty * $value->singlePrice;
        $totalPrice = $totalPrice + $finalPrice;

        // $finalPrice = $finalPrice . ' ' . $currency;
        $finalPrice = number_format($finalPrice, 2, ',', ' ') . ' ' . $currency;
        // $singlePrice = $value->singlePrice . ' ' . $currency;

        
        $singlePrice = number_format($value->singlePrice, 2, ',', ' ') . ' ' . $currency;

      } else {
        $finalPrice = '-';
        $singlePrice = $value->singlePrice;
      }
      $clase = '';
      if($key & 1){
        $clase = 'budget-row-colored';
      }
      
    # code...
      // $tablaDePrecios = $tablaDePrecios . $key . " - " . $value . "<br>";
      $tablaDePrecios = $tablaDePrecios . "
      <tr class='budget-row $clase'>
        <td>$value->code</td>
        <td>$value->qty</td>
        <td>$singlePrice</td>
        <td> - </td>
        <td>$finalPrice</td>
      </tr>";
    }
    $totalPrice = number_format($totalPrice, 2, ',', ' ') . ' ' . $currency;



    $mail1 = 'molinerozadkiel@gmail.com';
    $mail2 = 'tomas.moralparra@gmail.com';

		$subject="Cotizacion para $name";
    // $message='';
    // $message=$mail;

    $respuesta['test'] = 'testeo de respuesta';


    // foreach ($_POST as $key => $value) {
    //   if ( $key != 'a00' && $key != 'action' && $key != 'link' && $key != 'status' && $key != 'submit' && $key != 'g-recaptcha-response' ) {
    //     $message=$message.'<strong>'.$key.':</strong> '.$value.' - <br>';
    //   }
    // }

    require_once 'mailv1.php';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    // wp_mail( $mail1 , $subject , $message , $headers );
    // wp_mail( $mail2 , $subject , $message , $headers );
    if (wp_mail( $mail , $subject , $message , $headers )) {
      // $link = add_query_arg( array( 'status' => 'sent' , ), $link );
      $respuesta['gate1'] = 'mail enviado';
    } else {
      // $link = add_query_arg( array( 'status' => 'error', ), $link );
      $respuesta['gate1'] = 'error al enviar mensaje';
    }
	// }
	if($debugMode){echo wp_json_encode($respuesta);}
	exit();
}



?>
