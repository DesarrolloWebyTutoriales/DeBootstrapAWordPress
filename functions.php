<?php

	// Register Custom Navigation Walker

	if ( ! file_exists( get_template_directory() . '/wp-bootstrap-navwalker.php' ) ) {
		// file does not exist... return an error.
		return new WP_Error( 'class-wp-bootstrap-navwalker-missing', __( 'It appears the class-wp-bootstrap-navwalker.php file may be missing.', 'wp-bootstrap-navwalker' ) );
	} else {
		// file exists... require it.
	    require_once get_template_directory() . '/wp-bootstrap-navwalker.php';
	}	

	if (function_exists('register_sidebar')) 
	
	register_sidebar();

	register_nav_menus( array(
  		'primary' => __( 'Menu Principal', 'Dreams Consulting' ),
 	) );

	// Soporte imagenes destacadas (featured images)
	add_theme_support( 'post-thumbnails' );	

	function wpdocs_filter_wp_title( $title, $sep ) 
	{
	    global $paged, $page;
	 
	    if ( is_feed() )
	        return $title;
	 
	    // Add the site name.
	    $title .= get_bloginfo( 'name' );
	 
	    // Add the site description for the home/front page.
	    $site_description = get_bloginfo( 'description', 'display' );
	    if ( $site_description && ( is_home() || is_front_page() ) )
	        $title = "$title $sep $site_description";
	 
	    // Add a page number if necessary.
	    if ( $paged >= 2 || $page >= 2 )
	        $title = "$title $sep " . sprintf( __( 'Page %s', 'yankee' ), max( $paged, $page ) );
	 
	    return $title;
	}
	add_filter( 'wp_title', 'wpdocs_filter_wp_title', 10, 2 );

	// header buscador
	function header_widgets_init()
	{
		register_sidebar( array(
		    'name' => __( 'Header Buscador', 'Dreams Consulting' ),
		    'id' => 'search-1',
		    'description' => __( 'Un área de widget opcional para el header del buscador', 'dreams consulting' ),
		    'before_widget' => '<div id="%1$s" class="headwidget %2$s">',
		    'after_widget' => "</div>",
		) );
	    
	}
	add_action( 'widgets_init', 'header_widgets_init' );	

	// sidebar derecha
	function sidebarDerecha()
	{

		register_sidebar( array(
		    'name' => __( 'Sidebar Derecha', 'Dreams Consulting' ),
		    'id' => 'sidebar-derecha',
		    'description' => __( 'Widgets para el Blog', 'dreams consulting' ),
		    'before_widget' => '<aside id="%1$s" class="sidebar-derecha headwidget %2$s">',
		    'after_widget' => "</aside>",
		    'before_title'  => '<h1>',
			'after_title'   => '</h1>'
		) );

	}
	add_action( 'widgets_init', 'sidebarDerecha' );

	// Paginacion de resultados de los post types
	function wp_pagination($wp_query)
	{
	    global $wp_rewrite;
	    $pages = '';
	    //Calcular de paginas que tiene
	    $max = $wp_query->max_num_pages;

	    //Que pagina estoy
	    global $paged;
	    $current = ($paged) ? $paged : 1;
	    $a['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));
	    $a['total'] = $max;
	    $a['current'] = $current;
	    $total = 1; //1 - display the text "Page N of N", 0 - not display
	    //Links a izquierda y derecha
	    $a['mid_size'] = 3; //how many links to show on the left and right of the current
	    $a['end_size'] = 1; //how many links to show in the beginning and end
	    //$a['prev_text'] = '<'; //text of the "Previous page" link
	    $a['prev_text'] = 'Anterior';
	    $a['next_text'] = 'Siguiente';
	    if ($max > 1) {
	        echo '<div class="enigma_blog_pagination">';
	    }
	    echo '<div class="enigma_blog_pagi">';
	    if ($total == 1 && $max > 1) {
	        //$pages = '<span class="pages">Page ' . $current . ' of ' . $max . '</span>'."\r\n";
	        echo $pages . paginate_links($a);
	    }
	    if ($max > 1) {
	        echo '</div>';
	    }
	    echo '</div>';

	}	

	// paginacion de los resultados de busqueda
	function wp_pagination_search() 
	{
	    global $wp_rewrite; 
	    global $wp_query;   
	    $pages = '';
	    //Calcular de paginas que tiene
	    $max = $wp_query->max_num_pages;
	    //Que pagina estoy
	    if (!$current = get_query_var('paged')) $current = 1;
	    $a['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));
	    $a['total'] = $max;
	    $a['current'] = $current;
	    $total = 1; //1 - display the text "Page N of N", 0 - not display
	    
	    //Links a izquierda y derecha
	    $a['mid_size'] = 5; //how many links to show on the left and right of the current
	    $a['end_size'] = 1; //how many links to show in the beginning and end
	    //$a['prev_text'] = '<'; //text of the "Previous page" link
	    $a['prev_text'] = 'Anterior';
	    $a['next_text'] = 'Siguiente';
	    if ($max > 1) 
	        echo '<div class="enigma_blog_pagination">';
	        echo '<div class="enigma_blog_pagi">';
	    if ($total == 1 && $max > 1) {
	       //$pages = '<span class="pages">Page ' . $current . ' of ' . $max . '</span>'."\r\n";
	       echo $pages . paginate_links($a);
	    }
	    if ($max > 1) 
	        echo '</div>';
	        echo '</div>';
	}

	// Crear Shorcote Carousel
	function jg_slider($atts)
	{  

		$args_elecciones = array(
		  'post_type' => 'elecciones',
		  'post_status' => 'publish',
		  'ignore_sticky_posts' => true,
		  'posts_per_page' => -1,
		  'order' => 'ASC'
		); 

		$elecciones_data = new WP_Query($args_elecciones); 

		$texto = '';
		$texto .='<div id="slider-elecciones" class="owl-carousel owl-theme">';		

        if($elecciones_data->have_posts()):

            while($elecciones_data->have_posts()):

                $elecciones_data->the_post();

                $img_file   = get_field('imagen');
                $titulo  	= get_field('titulo');
                $fecha      = get_field('fecha');
                $archivo 	= get_field('subir_archivo');

                $texto .= '<article class="text-center">';
                $texto .= '<div id="bloque-izq">';
                $texto .= '<img src="'.$img_file['url'].'" class="img-responsive" />';
                $texto .= '</div>';
                $texto .= '<div id="bloque-der">';
                $texto .= '<p>'.$titulo.'</p>';
                $texto .= '<p><a href="'.$archivo['url'].'">'.$fecha.'</a></p>';
                $texto .= '</div>';
                $texto .= '</article>';

            endwhile;

        $texto .='</div>';

        endif;

        return $texto; 
		 
	}

	add_shortcode('jgslider','jg_slider');

	/*-----------------------------------------------------
	 * 			Declaro una funcion para el ajax
	 *----------------------------------------------------*/	

	add_action('wp_head', 'myplugin_ajaxurl');
	function myplugin_ajaxurl() {
	   echo '<script type="text/javascript">
	           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
	         </script>';   

	} 

	/*-----------------------------------------------------
	 * 	     Declaro la funcion enviarContacto()
	 		 Para el envio de correos
	 *----------------------------------------------------*/

	add_action( 'wp_ajax_enviarMail', 'enviarMail' );
	add_action( 'wp_ajax_nopriv_enviarMail', 'enviarMail' );

	function enviarMail()
	{

		// Enviar los datos del formulario.

		$nombres      = $_POST['nombres'];
		$apellidos    = $_POST['apellidos'];
		$email        = $_POST['email'];
		$asunto       = $_POST['asunto'];
		$mensaje      = $_POST['mensaje'];

		// Establecer el asunto
		$subject = 'Dreams Consulting - Nuevo mensaje del usuario(a) '.$nombres;

		$msg  = 'Nombres: '.$nombres.'<br />';
		$msg .= 'Apellidos: '.$apellidos.'<br />';		
		$msg .= 'E-mail: '.$email.'<br />';   
		$msg .= 'Asunto: '.$asunto.'<br /><br />';
		$msg .= '--------------------MENSAJE---------------------<br />';
		$msg .= $mensaje; 

	    $headers 	= 	array(
	        'Content-Type: text/html; charset=UTF-8',
	        'From: Dreams LC <esaenz22@gmail.com>' . "\r\n"
	    );
	    $email = array(
	    	'clientes.esaenz@gmail.com',
	        'esaenz22@gmail.com',
	        'informes@esaenz.com'
	    );
	    $mail = wp_mail( $email, $subject, $msg, $headers);
	    
		if (!$mail) 
		{
		  echo "<p>Mailer Error</p>";
		} 
		else 
		{
		  echo "<p>Gracias $nombres. Tu mensaje se envio correctamente.</p>";
		}

		die();		  

	}

	/*-----------------------------------------------------
	 * 	     Declaro la funcion inscripcionSuscritos()
	 		 Para el registro de suscritos
	 *----------------------------------------------------*/	
	add_action( 'wp_ajax_inscripcionSuscritos', 'inscripcionSuscritos' );
	add_action( 'wp_ajax_nopriv_inscripcionSuscritos', 'inscripcionSuscritos' );	
	function inscripcionSuscritos()
	{

		$nombres       		= $_POST['nombres'];
		$empresa       		= $_POST['empresa'];
		$ciudad_pais   		= $_POST['ciudad_pais'];
		$email		   		= $_POST['email_2'];
		$acepta_terminos	= $_POST['acepta'];

		// creo la variable post almacenando en un array los valores como post_title, post_content,
		// post_status, post_type y el post del autor que por defecto es 1.
		$post = array(
		  'post_title'     => $nombres, // The title of your post.
		  'post_content'   => '',
		  'post_status'    => 'publish', // Default 'draft'.
		  'post_type'      => 'suscritos', // Default 'post'.
		  'post_author'    => 1 // The user ID number of the author. Default is the current user ID.
		);

		// creo la variable $post_id y lo almaceno con la funcion del wordpress wp_insert_post()
		// para registralo en la base de datos indicandole como parametro el valor del post.
		$post_id = wp_insert_post($post); 

		// llamo a la funcion add_post_meta para especificar los custom fields que se han creado
		// en el administrador del wordpress.
		add_post_meta($post_id, 'nombres', $nombres);
		add_post_meta($post_id, 'empresa', $empresa);
		add_post_meta($post_id, 'ciudad_pais', $ciudad_pais);		
		add_post_meta($post_id, 'email', $email);
		add_post_meta($post_id, 'acepto_terminos', $acepta_terminos);	
	    
		echo "Gracias $nombres. Te has suscrito a Dreams LC.";

		die();

	}

	/*-----------------------------------------------------
	 * 	     Function de wordpress para administrar el logo
	 		 de la imagen
	 *----------------------------------------------------*/	

	function themename_custom_header_setup() 
	{
	    $args = array(
	        'default-image'      => get_template_directory_uri() . '/imagenes/logo.png',
	        'default-text-color' => '000',
	        'width'              => 120,
	        'height'             => 36,
	        'flex-width'         => true,
	        'flex-height'        => true,
	    );

	    add_theme_support( 'custom-header', $args );

	}
	add_action( 'after_setup_theme', 'themename_custom_header_setup' );

	/*-----------------------------------------------------
	 * 	 Función de wordpress para administrar el favicon
	 	 de la plantilla a medida
	 *----------------------------------------------------*/	

	function add_my_favicon() {
	   $favicon_path = get_template_directory_uri() . '/imagenes/favicon.png';

	   echo '<link rel="shortcut icon" href="' . $favicon_path . '" />';
	}

	add_action( 'wp_head', 'add_my_favicon' ); //front end
	add_action( 'admin_head', 'add_my_favicon' ); //admin end	