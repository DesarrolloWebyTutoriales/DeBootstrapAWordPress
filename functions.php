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

	//----//
	function add_dreamsconsulting_css_js() 
	{
		// Registrar los estilos css
		wp_register_style( 'bootstrap-min-css', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array(), '
			3.3.4', 'all' );
		wp_register_style( 'fontawesome-min-css', get_stylesheet_directory_uri() . '/css/font-awesome.min.css', array(), '
			4.7.0', 'all' );

		$query_args_1 = array(
		'family' => 'Open+Sans|Roboto+Condensed:300|Signika'
		);
		wp_register_style( 
			'google-fonts-1', 
			add_query_arg( $query_args_1, '//fonts.googleapis.com/css' ), 
			array(), 
			null 
		);

		wp_register_style( 'owl-carousel-css', get_stylesheet_directory_uri() . '/css/owl.carousel.css', array(), '2.2.1', 'all' );	
		wp_register_style( 'owl-theme-css', get_stylesheet_directory_uri() . '/css/owl.theme.default.min.css', array(), '2.2.1', 'all' );
		wp_register_style( 'animate-css', get_stylesheet_directory_uri() . '/css/animate.css', array(), '3.5.2', 'all' );			
		wp_register_style( 'style-css', get_stylesheet_directory_uri() . '/style.css', array(), '1.3.4', 'all' );

	    // Registrar los scripts js
    	wp_register_script( 'bootstrap-min-js', get_template_directory_uri().'/js/bootstrap.min.js', '', '', true );
    	wp_register_script( 'owl-carousel-min', get_template_directory_uri().'/js/owl.carousel.min.js', '', '', true );    	
		wp_register_script( 
			'googlemap',
			'https://maps.googleapis.com/maps/api/js?key=AIzaSyCKH_Kfp9e1DmJJMKQBiAtQ_oGhIU3QfFE',
			array(),
			null,
			false
		);		
		wp_register_script( 'cargar-js', get_template_directory_uri().'/js/cargar.js', '', '', true );

		// css
		wp_enqueue_style('bootstrap-min-css');
		wp_enqueue_style('fontawesome-min-css');
		wp_enqueue_style('google-fonts-1');
		wp_enqueue_style('owl-carousel-css');
		wp_enqueue_style('owl-theme-css');
		wp_enqueue_style('animate-css');
		wp_enqueue_style('style-css');		

		// js
		wp_enqueue_script('bootstrap-min-js');
		wp_enqueue_script('owl-carousel-min');
		wp_enqueue_script('googlemap');
		wp_enqueue_script('cargar-js');

	}
	add_action( 'wp_enqueue_scripts', 'add_dreamsconsulting_css_js' );

	function add_scripts() {
	        wp_deregister_script('jquery');
	        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', '', '1.12.4', false);
	        wp_enqueue_script( 'jquery' );
	}
	add_action('wp_enqueue_scripts', 'add_scripts'); 	

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

	/*-----------------------------------------------------
	 * 			widget para el footer
	 *----------------------------------------------------*/		

	function footer_widget_init()
	{
		register_sidebar( array(
			'name' => __( 'Pie de pagina', 'Dreams Consulting' ),
			'id' => 'footer-1',
			'description' => __( 'Un área de widget para el pie de pagina', 'dreams consulting' ),
			'before_widget' => '<div id="%1$s" class="headwidget %2$s">',
			'after_widget' => '</div>',

		) );

	}
	add_action( 'widgets_init', 'footer_widget_init' );

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
	function jg_slider($content = null)
	{  

		ob_start();

		$args_elecciones = array(
		  'post_type' => 'elecciones',
		  'post_status' => 'publish',
		  'ignore_sticky_posts' => true,
		  'posts_per_page' => -1,
		  'order' => 'ASC'
		); 

		$elecciones_data = new WP_Query($args_elecciones); 	

		?>

		<div id="slider-elecciones" class="owl-carousel owl-theme">	

			<?php

				if($elecciones_data->have_posts()):

				    while($elecciones_data->have_posts()):

				        $elecciones_data->the_post();

				        $img_file   = get_field('imagen');
				        $titulo  	= get_field('titulo');
				        $fecha      = get_field('fecha');
				        $archivo 	= get_field('subir_archivo');
			?>

				       <article class="text-center">
							<div id="bloque-izq">
								<img src="<?php echo $img_file['url']; ?>" class="img-responsive" />
							</div>
							<div id="bloque-der">
								<p><?php echo $titulo; ?></p>
								<p><a href="<?php echo $archivo['url']; ?>"><?php echo $fecha; ?></a></p>
							</div>
				       
				       </article>

			<?php

					endwhile;
					wp_reset_postdata();

				endif;

			?>

		</div>
		<!-- -->

	<?php

		$content  = ob_get_contents();
		ob_end_clean(); 
		return $content; 
		 
	}

	add_shortcode('jgslider','jg_slider');

	// Crear Shortcode para fornulario de contacto
	function contact_form_customize($atts)
	{

		$html  = '<form id="fcontacto" method="post">';
		$html .= '<div class="col-sm-6">
		              <div class="form-group">
		                <input type="text" name="nombres" class="form-control" placeholder="Nombres" />
		              </div> 
		              <div class="form-group">
		                <input type="text" name="apellidos" class="form-control" placeholder="Apellidos" />
		              </div>
		              <div class="form-group">
		                <input type="text" name="email" class="form-control" placeholder="E-mail" />
		              </div>                  
		           </div>';
		$html .= '<div class="col-sm-6">
		              <div class="form-group">
		                <input type="text" name="asunto" class="form-control" placeholder="Asunto" />
		              </div>
		              <div class="form-group">
		                <textarea name="mensaje" class="form-control" rows="4" placeholder="Mensaje"></textarea>
		              </div>
		            </div>
		            
		            <div class="col-sm-12">
		              <div class="form-group">
		                <button type="button" class="btn btn-default enviar">Enviar</button>
		              </div>

		           </div>';
		$html .= '<div id="estado"></div>';
		$html .= '</form>';

		return $html;

	}
	add_shortcode('contact-form-customize','contact_form_customize');

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
	 * 	  Declaro la funcion addComentarios() Para el registro de comentarios
	      en el detalle de la entrada
	 *----------------------------------------------------*/
	add_action( 'wp_ajax_addComentarios', 'addComentarios' );
	add_action( 'wp_ajax_nopriv_addComentarios', 'addComentarios' );	
	function addComentarios()
	{

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
	 *----------------------------------------------------*/	

	function add_my_favicon() {
	   $favicon_path = get_template_directory_uri() . '/imagenes/favicon.png';

	   echo '<link rel="shortcut icon" href="' . $favicon_path . '" />';
	}

	add_action( 'wp_head', 'add_my_favicon' ); //front end
	add_action( 'admin_head', 'add_my_favicon' ); //admin end

	/*-----------------------------------------------------
	 * 	 Función de wordpress para administrar las opciones del tema
	 *----------------------------------------------------*/	

	function theme_settings_page()
	{
    ?>
	    <div class="wrap">
	    <h1>Theme Panel</h1>
	    <form method="post" action="options.php" enctype="multipart/form-data">
	        <?php
	            settings_fields("section");
	            do_settings_sections("theme-options");      
	            submit_button(); 
	        ?>
	    </form>
		</div>
	<?php		
	}

	function add_theme_menu_item()
	{
		add_menu_page("Theme Panel", "Theme Panel", "manage_options", "theme-panel", "theme_settings_page", null, 99);
	}

	add_action("admin_menu", "add_theme_menu_item");	

	/*-----------------------------------------------------
	 * 	 Función de wordpress para mostrar que opciones
	 del tema se van a administrar
	 *----------------------------------------------------*/

	function display_search_header()
	{
		?>
			<input type="checkbox" name="theme_layout" value="1" <?php checked(1, get_option('theme_layout'), true); ?> /> Si
		<?php
	}	 

	function display_facebook_element()
	{
		?>
	    	<input type="text" name="facebook_url" id="facebook_url" value="<?php echo get_option('facebook_url'); ?>" style="width: 50%;" />
	    <?php
	}

	function display_twitter_element()
	{
		?>
	    	<input type="text" name="twitter_url" id="twitter_url" value="<?php echo get_option('twitter_url'); ?>" style="width: 50%;" />
	    <?php
	}

	function display_google_plus_element()
	{
		?>
			<input type="text" name="google_plus_url" id="google_plus_url" value="<?php echo get_option('google_plus_url'); ?>" style="width: 50%;" />
		<?php
	}

	function display_youtube_element()
	{
		?>
			<input type="text" name="youtube_url" id="youtube_url" value="<?php echo get_option('youtube_url'); ?>" style="width: 50%;" />
		<?php
	}

	function display_pinterest_element()
	{
		?>
			<input type="text" name="pinterest_url" id="pinterest_url" value="<?php echo get_option('pinterest_url'); ?>" style="width: 50%;" />
		<?php
	}
	function display_instagram_element()
	{
		?>
			<input type="text" name="instagram_url" id="instagram_url" value="<?php echo get_option('instagram_url'); ?>" style="width: 50%;" />
		<?php
	}		
	function display_linkedin_element()
	{
		?>
			<input type="text" name="linkedin_url" id="linkedin_url" value="<?php echo get_option('linkedin_url'); ?>" style="width: 50%;" />			
		<?php
	}

	function display_phone_whatsapp()
	{
		?>
			<input type="text" name="phone_ws" id="phone_ws" value="<?php echo get_option('phone_ws'); ?>" style="width: 50%;" />			
		<?php		
	}
	function display_link_customize()
	{
		?>
			<input type="text" name="customize_url_ws" id="customize_url_ws" value="<?php echo get_option('customize_url_ws'); ?>" style="width: 50%;" />			
		<?php		
	}
	function display_email_element()
	{
		?>
			<input type="text" name="email_url" id="email_url" value="<?php echo get_option('email_url'); ?>" style="width: 50%;" />			
		<?php
	}		

	/*-----------------------------------------------------
	 * 	 Función de wordpress para administrar los derechos
		 de autor y administrar el logo en el pie de pagina
	 *----------------------------------------------------*/

	function display_copyright_element()
	{
		?>
	    	<input type="text" name="copyright_text" id="copyright_text" value="<?php echo get_option('copyright_text'); ?>" style="width: 50%;" />
	    <?php

	}

	function logo_setting() 
	{  
		$options = get_option('plugin_options'); 

		if($options=="")
		{
			echo "";
		}
		else
		{
	?>
		<p><img src="<?php echo $options['logo']; ?>" alt="Logo" /></p>
	<?php
		}
	?>
		<input type="file" name="logo" />
	<?php
	}

	function display_theme_panel_fields()
	{
		

	    add_settings_field("theme_layout", "Desea desactivar el buscador del header ?", "display_search_header", "theme-options", "section");

	    add_settings_field("facebook_url", "Facebook Profile Url", "display_facebook_element", "theme-options", "section");		
		add_settings_field("twitter_url", "Twitter Profile Url", "display_twitter_element", "theme-options", "section");
		add_settings_field("google_plus_url", "Google Plus Profile Url", "display_google_plus_element", "theme-options", "section");
		add_settings_field("youtube_url", "Youtube Channel Url", "display_youtube_element", "theme-options", "section");
		add_settings_field("instagram_url", "Instagram Account", "display_instagram_element", "theme-options", "section");			
		add_settings_field("pinterest_url", "Pinterest Profile Url", "display_pinterest_element", "theme-options", "section");
		add_settings_field("linkedin_url", "Linkedin Profile Url", "display_linkedin_element", "theme-options", "section");
		add_settings_field("email_url", "E-mail de la empresa", "display_email_element", "theme-options", "section");
		add_settings_field("phone_ws", "Whatsapp number", "display_phone_whatsapp", "theme-options", "section");
		add_settings_field("customize_url_ws", "Enlace de whatsapp", "display_link_customize", "theme-options", "section");

	    add_settings_field("copyright_text", "Copyright Text", "display_copyright_element", "theme-options", "section");

		// add settings filed with callback test_logo_display.
		add_settings_field('logo', 'Logo:', 'logo_setting', "theme-options", 'section'); // LOGO
	    
		add_settings_section("section", "Cabecera y Footer Social Media", null, "theme-options");
		register_setting("section", "theme_layout");
	    register_setting("section", "facebook_url");
	    register_setting("section", "twitter_url");
	    register_setting("section", "google_plus_url");
	    register_setting("section", "youtube_url");
	    register_setting("section", "instagram_url");	    
	    register_setting("section", "pinterest_url");
	    register_setting("section", "linkedin_url");
	    register_setting("section", "email_url");	    
	    register_setting("section", "phone_ws");
	    register_setting("section", "customize_url_ws");
	    register_setting("section", "copyright_text");
		register_setting("section", 'logo');
		register_setting('section', 'plugin_options', 'validate_setting');

	}
	add_action("admin_init", "display_theme_panel_fields");	

	function validate_setting($plugin_options) 
	{ 
		$keys = array_keys($_FILES); 

		$i = 0; 
		foreach ( $_FILES as $image ) 
		{   
			// if a files was upload   
			if ($image['size']) 
			{     
				// if it is an image     
				if ( preg_match('/(jpg|jpeg|png|gif)$/', $image['type']) ) {       
					$override = array('test_form' => false);       
					// save the file, and store an array, containing its location in $file       
					$file = wp_handle_upload( $image, $override );       
					$plugin_options[$keys[$i]] = $file['url'];     
				} 
				else 
				{       
				// Not an image.        
				$options = get_option('plugin_options');       
				$plugin_options[$keys[$i]] = $options[$logo];       
				// Die and let the user know that they made a mistake.       
				wp_die('No image was uploaded.');     
				}   
			}   
			// Else, the user didn't upload a file.   
			// Retain the image that's already on file.   
			else 
			{     
				$options = get_option('plugin_options');     
				$plugin_options[$keys[$i]] = $options[$keys[$i]];   
			} 

		$i++; 

		} 
		
		return $plugin_options;

	}

	function change( $data ) {

	    $message = null;
	    $type = null;

	    if ( null != $data ) {

	        if ( false === get_option( 'theme-panel' ) ) {

	            add_option( 'theme-panel', $data );
	            $type = 'updated';
	            $message = __( 'Successfully saved 11', 'tex-domain' );

	        } else {

	            update_option( 'theme-panel', $data );
	            $type = 'updated';
	            $message = __( 'Successfully updated 22', 'tex-domain' );

	        }

	    } else {

	        $type = 'error';
	        $message = __( 'Data can not be empty', 'tex-domain' );

	    }

	    add_settings_error(
	        'myUniqueIdentifier',
	        esc_attr( 'settings_updated' ),
	        $message,
	        $type
	    );

	}
