<?php

// inital defines

define("UDS_TEMPLATE_NAME", "iMag");
define("UDS_THEME_SWITHCING", true); // Allow theme switching from GET parameters
define("UDS_THEME_PREVIEW", true); //triggers special behavior only needed in preview
// define themes
$uds_themes = array(
	'contrast' => array(
		'name' => 'Contrast',
		'file' => null
	),
	'dark' => array(
		'name' => 'Dark',
		'file' => 'theme-dark.css'
	)
);

// define backgrounds	
$uds_backgrounds = array(
	'none' => array(
		'name' => 'None',
		'file' => null
	),
	'carbon' => array(
		'name' => 'Carbon',
		'file' => 'theme-bg-carbon.css'
	), 
	'paper' => array(
		'name' => 'Paper',
		'file' => 'theme-bg-paper.css'
	), 
	'darkstars' => array(
		'name' => 'Dark Stars',
		'file' => 'theme-bg-darkstars.css'
	),
	'wood' => array(
		'name' => 'Wood',
		'file' => 'theme-bg-wood.css'
	),
	'darkwood' => array(
		'name' => 'Dark Wood',
		'file' => 'theme-bg-darkwood.css'
	),
	'vintage' => array(
		'name' => 'Vintage',
		'file' => 'theme-bg-vintage.css'
	),
	'stripes-angle' => array(
		'name' => 'Stripes Angle',
		'file' => 'theme-bg-stripes-angle.css'
	),
	'stripes-vertical' => array(
		'name' => 'stripes-vertical',
		'file' => 'theme-bg-stripes-vertical.css'
	)
);

// define additions to WP
function get_first_image() {
	global $post, $posts;
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches[1][0];
	if(empty($first_img)){ //Defines a default image
		$first_img = dirname(__FILE__)."/images/portfolio-default.jpg";
	}
	return $first_img;
}

function is_ajax()
{
	return $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

function the_breadcrumbs()
{
	global $post;
	if(is_page()){
		$ancestors = get_post_ancestors($post->ID);
		if(!empty($ancestors)){
			foreach($ancestors as $ancestor){
				echo "<a href='".get_permalink($ancestor)."'>".get_the_title($ancestor).'</a> &raquo; ';
			}
		}
		echo "<a href='".get_permalink()."'>".get_the_title().'</a>';
	} else {
		echo "<a href='".get_bloginfo('url')."'>".get_bloginfo('name')."</a> &raquo; ";
		the_category(', ');
		echo " &raquo; <a href='".get_permalink()."'>".get_the_title().'</a>';
	}
}


// set support for post thumbnails
if (function_exists( 'add_theme_support' )){
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 600, 200, false );
}

// init options and admin
add_action('init', 'uds_init');
function uds_init()
{
	add_option('uds-heading', UDS_TEMPLATE_NAME);
	add_option('uds-subheading', '');
	add_option('uds-heading-setup', 'text');
	add_option('uds-billboard', '');
	add_option('slideshow-type', 'uBillboard');
	add_option('delay', '5000');
    add_option('width', '950');
    add_option('height', '250');
    add_option('family', 'helvetica');
    add_option('size', '22');
    add_option('color', 'white');
    add_option('weight', 'bold');
    add_option('position', '6px 23px');

	if(is_admin()){
		add_thickbox();
		$cssdir = "/wp-content/plugins/slideshow/css/";
		wp_enqueue_style('admin', $cssdir.'admin.css', false, false, 'screen');
		wp_enqueue_style('jquery-ui', $cssdir.'ui-lightness/jquery-ui-1.7.2.custom.css', false, false, 'screen');
	}
}
function uploadFile()
{
    $baseDir =  bloginfo('url');
}

function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth )
{
      // load image and get image size
      $img = imagecreatefromjpeg( "{$pathToImages}" );
      $width = imagesx( $img );
      $height = 115;

      // calculate thumbnail size
      $new_width = $thumbWidth;
      $new_height = 115;

      // create a new temporary image
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      // copy and resize old image into new image
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      // save thumbnail into a file
      imagejpeg( $tmp_img, "{$pathToThumbs}" );
      
      $Img = end(explode("/", $pathToThumbs));
      return $Img;
}

// init stlyes
add_action("wp_print_styles", "uds_styles");
function uds_styles()
{
	global $uds_themes, $uds_backgrounds;
    $width = get_option('width'); 
    $height = get_option('height');
    $uploades = wp_upload_dir();
    $image = createThumbs(get_bloginfo('url')."/wp-content/plugins/slideshow/images/billboard-shadow.jpg",$uploades['basedir']."/billboardShadow.jpg", $width);
	$cssdir = "/wp-content/plugins/slideshow/css/";
	if(!is_admin()){
		wp_enqueue_style('style', $cssdir.'slideshow.php?width='.$width."&height=$height&image=$image", false, false, 'screen');

		$theme = get_option('uds-theme');
		$background = get_option('uds-background');
		
		if(UDS_THEME_SWITCHING){
			@session_start();
			
			if(!empty($_SESSION['uds-theme']) && in_array($_SESSION['uds-theme'], array_keys($uds_themes))){
				$theme = $_SESSION['uds-theme'];
			}
			
			if(!empty($_SESSION['uds-background']) && in_array($_SESSION['uds-background'], array_keys($uds_backgrounds))){
				$background = $_SESSION['uds-background'];
			}
			
			if(!empty($_GET['uds-theme']) && in_array($_GET['uds-theme'], array_keys($uds_themes))){
				$_SESSION['uds-theme'] = $_GET['uds-theme'];
				$theme = $_GET['uds-theme'];
			}
			
			if(!empty($_GET['uds-background']) && in_array($_GET['uds-background'], array_keys($uds_backgrounds))){
				$_SESSION['uds-background'] = $_GET['uds-background'];
				$background = $_GET['uds-background'];
			}
		}

		define("UDS_CURRENT_THEME", $theme);

		// load current theme
		if($uds_themes[$theme]['file'] != null){
			wp_enqueue_style('theme', $cssdir.$uds_themes[$theme]['file'], false, false, 'screen');
		}
	
		// load current background
		if($uds_backgrounds[$background]['file'] != null){
			wp_enqueue_style('background', $cssdir.$uds_backgrounds[$background]['file'], false, false, 'screen');
		}
		
		// load custom CSS
		//wp_enqueue_style('custom', $cssdir.'custom.css', false, false, 'screen');
	}
}

// init scripts
add_action("wp_print_scripts", "uds_scripts");
function uds_scripts()
{
	$jsdir = "/wp-content/plugins/slideshow/js/";

	if(!is_admin()){
		wp_enqueue_script("_jquery", $jsdir."jquery.js");
		wp_enqueue_script("easing", $jsdir."jquery.easing.js");
		wp_enqueue_script("cycle", $jsdir."jquery.cycle.js");
		wp_enqueue_script("event.hover", $jsdir."jquery.hover.js");
		$key = get_option("uds-google-key");
		if(!empty($key)){		
			wp_enqueue_script("google.ajax", "http://www.google.com/jsapi?key=".$key, false, false);
		}
		$key = get_option("uds-billboard-type");
		if($key == 'uBillboard'){		
			wp_enqueue_script("uBillboard", $jsdir."jquery.ubillboard.min.js");
		}
		wp_enqueue_script("scripts", $jsdir."scripts.js");
		if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	} else {
		wp_enqueue_script("jquery-ui-tabs");
		wp_enqueue_script("jquert-cookie", $jsdir."jquery_cookie.js");
		wp_enqueue_script("admin", $jsdir."admin.js");
	}
}

// setup Admin menu entry
add_action('admin_menu', 'uds_menu');
function uds_menu()
{
	add_options_page('Slideshow', 'Slideshow', '8', 'imag', 'uds_admin');
}

// Admin menu entry handling
function uds_admin()
{
	global $uds_themes, $uds_backgrounds;
	include dirname(__FILE__)."/admin-options.php";
}



//include 'shortcodes.php';
//include 'widgets/widgets.php';

?>