<?php

define( 'CONVERTICA_DIR', trailingslashit( get_template_directory() ) );
define( 'CONVERTICA_URL', trailingslashit( get_template_directory_uri() ) );
define( 'CONVERTICA_LIB', CONVERTICA_DIR . 'lib/' );
define( 'HYBRID_DIR', trailingslashit( get_template_directory() ) . 'lib/hybrid-core/' );
define( 'HYBRID_URI', trailingslashit( get_template_directory_uri() ) . 'lib/hybrid-core/' );
define( 'BACKGROUND_IMAGE', get_template_directory_uri() . '/images/background.png');

require_once HYBRID_DIR . 'hybrid.php';
require_once CONVERTICA_DIR . 'inc/custom-background.php';
require_once CONVERTICA_DIR . 'inc/custom-header.php';
require_once CONVERTICA_DIR . 'inc/theme.php';
require_once CONVERTICA_LIB . 'mobile-detect.php';
require_once CONVERTICA_LIB . 'convertica_mobile_functions.php';

if ( file_exists( CONVERTICA_DIR . 'dev/dev.php' ) ) {
	require_once CONVERTICA_DIR . 'dev/dev.php';
}

new Hybrid();

add_action( 'after_setup_theme', 'convertica_setup', 5 );

$convertica_defaults = convertica_get_defaults();

function convertica_setup( ) {
	global $convertica_defaults;
	$convertica_defaults = convertica_get_defaults();
	add_theme_support( 'convertica-semantic-ui' );
	add_theme_support( 'convertica-slicknav' );
	add_action( 'admin_enqueue_scripts', 'convertica_admin_styles' );
	add_action( 'wp_enqueue_scripts', 'convertica_enqueue_scripts' , 4);
	add_action( 'customize_register', 'convertica_customize_register' );
	add_action( 'convertica_before_loop','convertica_archive_header');	
	add_filter( 'body_class', 'convertica_body_classes');
	// Theme layouts.
	add_theme_support( 'theme-layouts', array(
		'default' => is_rtl() ? '2c-r' : '2c-l' 
	) );
	add_theme_support( 'hybrid-core-template-hierarchy' ); // Enable custom template hierarchy.
	add_theme_support( 'get-the-image' ); // The best thumbnail/image script ever.
	add_theme_support( 'breadcrumb-trail' ); // Breadcrumbs. Yay!
	add_theme_support( 'cleaner-gallery' ); // Nicer [gallery] shortcode implementation.
	add_theme_support( 'automatic-feed-links' ); // Automatically add feed links to <head>.
	// Post formats.
	/*add_theme_support(
	'post-formats',
	array( 'aside', 'audio', 'chat', 'image', 'gallery', 'link', 'quote', 'status', 'video' )
	);
	*/
	remove_theme_support( 'post-formats' );
	// Handle content width for embeds and images.
	hybrid_set_content_width( 1280 );
}

function convertica_archive_header(){
	
	if(function_exists('is_bbPress') && is_bbPress()) return;

	if ( ! is_front_page() && hybrid_is_plural() ) { // If viewing a multi-post page

		locate_template( array( 'misc/archive-header.php' ), true ); // Loads the misc/archive-header.php template.

	} // End check for multi-post page. 
}

function clog( $string = '', $debug = false, $echo = true ) {
	echo '<pre>';
	print_r( $string );
	echo '</pre>';
}



function convertica_enqueue_scripts( ) {
	
	//wp_enqueue_style( 'convertica-normalize', CONVERTICA_URL . 'css/normalize.css' );
	
	wp_register_script( 'modernizr', CONVERTICA_URL . 'lib/foundation-5/js/vendor/modernizr', array( ), '2.8.3', true );
	if ( current_theme_supports( 'convertica-foundation' ) ) {
		wp_enqueue_script( 'convertica-foundation-script', CONVERTICA_URL . 'lib/foundation-5/js/foundation.min.js', array(
			 'jquery',
			'modernizr' 
		), false, true );
		wp_enqueue_style( 'convertica-foundation-style', CONVERTICA_URL . 'lib/foundation-5/css/foundation.min.css' );
	}
	if ( current_theme_supports( 'convertica-semantic-ui' ) ) {
		wp_enqueue_script( 'convertica-semantic-script', CONVERTICA_URL . 'lib/semantic-ui/semantic.min.js', array(
			 'jquery' 
		), false, true );
		wp_enqueue_style( 'convertica-semantic-style', CONVERTICA_URL . 'lib/semantic-ui/semantic.min.css' );
	}
	if ( current_theme_supports( 'convertica-ui-kit' ) ) {
		wp_enqueue_script( 'convertica-ui-kit-script', CONVERTICA_URL . 'lib/uikit/js/uikit.min.js', array(
			 'jquery' 
		), false, true );
		wp_enqueue_style( 'convertica-ui-kit-style', CONVERTICA_URL . 'lib/uikit/css/uikit.min.css' );
	}
	
	if ( current_theme_supports( 'convertica-bootstrap' ) ) {
		wp_enqueue_script( 'convertica-bootstrap-script', CONVERTICA_URL . 'lib/bootstrap/js/bootstrap.min.js', array(
			 'jquery' 
		), false, true );
		wp_enqueue_style( 'convertica-bootstrap-style', CONVERTICA_URL . 'lib/bootstrap/css/bootstrap.min.css' );
		wp_enqueue_style( 'convertica-bootstrap-theme', CONVERTICA_URL . 'lib/bootstrap/css/bootstrap-theme.min.css' );
	}
	if ( current_theme_supports( 'convertica-slicknav' ) ) {
		wp_enqueue_script( 'convertica-slicknav-script', CONVERTICA_URL . 'lib/slicknav/jquery.slicknav.min.js', array(
			 'jquery' 
		), false, true );
		
	}
	if(current_user_can('edit_theme_options')) {
		$style_version = microtime();
	}
	else {
		$style_version = false;
	}
	wp_enqueue_style( 'convertica-sass-style', CONVERTICA_URL . 'css/style.css', array(), $style_version);

	wp_enqueue_style( 'dashicons' );
}
add_action( 'wp_head', 'convertica_enqueue_base_scripts' );
function convertica_enqueue_base_scripts( ) {
?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#menu-before_header-items').slicknav({ /* responsive menu: primary nav menu  */
			prependTo: "#menu-before_header .wrap",
			label: '<span class="menu-icon">&#8801;</span>&ensp;Menu',
			duration: 400,
			closedSymbol: '<span class="dashicons dashicons-arrow-right"></span>',
			openedSymbol: '<span class="dashicons dashicons-arrow-down"></span>'  
		});
		
		jQuery('#menu-after_header-items').slicknav({ /* responsive menu: secondary nav menu */
			prependTo: "#menu-after_header .wrap",
			label: '<span class="menu-icon">&#8801;</span>&ensp;Menu',
			duration: 400,
			closedSymbol: '<span class="dashicons dashicons-arrow-right"></span>',
			openedSymbol: '<span class="dashicons dashicons-arrow-down"></span>' 
		});
	});
	</script>
	<?php
}
function convertica_admin_styles( ) {
	wp_enqueue_style( 'convertica-admin-style', CONVERTICA_URL . 'css/admin.css', false, '1.0.0' );
}



function convertica_body_classes( $classes ){
	$classes[] = convertica_get_mod('layout_style_setting');
	$classes[] = get_option( 'stylesheet' );
	if( 'page' == get_option('show_on_front') && is_front_page()){
		$classes[] = 'front';
		//unset($classes['home']); //doesn't work
	}
	return $classes;
}

function convertica_get_mod($mod){
	global $convertica_defaults;
	
	return get_theme_mod($mod,$convertica_defaults[$mod]);
}

function convertica_get_mods(){
	global $convertica_defaults;
	
	//$mods = get_theme_mods();
	$mods = array();
	foreach($convertica_defaults as $key => $value)
		{
			$mods[$key] = convertica_get_mod($key);
			//clog($key);
			//clog(convertica_get_mod($key));
		}
	
	return wp_parse_args($mods, $convertica_defaults);
}



function convertica_customize_register( $wp_customize ) {
	global $convertica_defaults;

	$defaults      = $convertica_defaults;
	$settings_type = 'theme_mod';
	$transport     = 'refresh';


	$wp_customize->add_panel( 'convertica_panel', array('priority' => 10,'capability' => 'edit_theme_options','title' => __( 'Convertica Layout', 'femme-flora' ),'description' => __( 'Tune the raw power of Convertica', 'femme-flora' )));
	$wp_customize->remove_section( 'layout', 30 );

	$wp_customize->add_section( 'layout', array('title' => esc_html__( 'Layout', 'hybrid-core' ),'priority' => 10,'panel' => 'convertica_panel' ) );
	$wp_customize->add_setting( 'layout_style_setting',array('default' => $defaults[ 'layout_style_setting' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'convertica_sanitizer') );
	$wp_customize->add_control( 'convertica_layout_style_control', array('label' => __( 'Layout Style', 'femme-flora' ),'section' => 'layout','settings' => 'layout_style_setting','type' => 'radio','priority' => '10','choices' => convertica_choices('layout_style_setting')) );
	$wp_customize->add_setting( 'layout_body_bg_color',array('default' => $defaults[ 'layout_body_bg_color' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'sanitize_hex_color') );
	$wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'layout_body_bg_color_control', array('label' => __( 'Body Background Color', 'femme-flora' ),'section' => 'layout','settings' => 'layout_body_bg_color', 'priority' => '10') ) );
	$wp_customize->add_setting( 'layout_wrap_bg_color',array('default' => $defaults[ 'layout_wrap_bg_color' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'sanitize_hex_color') );
	$wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'layout_wrap_bg_color_control', array('label' => __( 'Wrap Background Color', 'femme-flora' ),'section' => 'layout','settings' => 'layout_wrap_bg_color', 'priority' => '10') ) );
	$wp_customize->add_section( 'convertica_archives_section', array('title' => __( 'Archive Options', 'femme-flora' ), 'priority' => 35, 'description' => __( 'Select display options for archives.', 'femme-flora' ), 'panel' => 'convertica_panel' ) );
	$wp_customize->add_setting( 'archive_style', array('default' => $defaults[ 'archive_style' ], 'type' => $settings_type, 'transport' => $transport, 'sanitize_callback' => 'convertica_sanitizer') );
	$wp_customize->add_control( 'convertica_archive_control', array('label' => __( 'Content Style', 'femme-flora' ),'section' => 'convertica_archives_section','settings' => 'archive_style','type' => 'select','choices' => convertica_choices('archive_style')) );
	$wp_customize->add_setting( 'archive_featured_image_setting', array('default' => $defaults[ 'archive_featured_image_setting' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'convertica_sanitizer') );
	$wp_customize->add_control( 'convertica_archive_featured_image_control', array('label' => __( 'Show Featured Image', 'femme-flora' ),'section' => 'convertica_archives_section','settings' => 'archive_featured_image_setting','type' => 'select','choices' => convertica_choices('archive_featured_image_setting')) );
	$wp_customize->add_setting( 'archive_featured_image_size_setting', array('default' => $defaults[ 'archive_featured_image_size_setting' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'convertica_sanitizer') );
	$wp_customize->add_control( 'convertica_archive_image_size_control', array('label' => __( 'Featured Image Size', 'femme-flora' ),'section' => 'convertica_archives_section','settings' => 'archive_featured_image_size_setting','type' => 'select','choices' => convertica_choices('archive_featured_image_size_setting')) );
	$wp_customize->add_setting( 'archive_featured_image_float_setting', array('default' => $defaults[ 'archive_featured_image_float_setting' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'convertica_sanitizer') );
	$wp_customize->add_control( 'convertica_archive_image_float_control', array('label' => __( 'Float', 'femme-flora' ),'section' => 'convertica_archives_section','settings' => 'archive_featured_image_float_setting','type' => 'select','choices' => convertica_choices('archive_featured_image_float_setting')) );
	$wp_customize->add_setting( 'archive_breadcrumbs_setting', array('default' => $defaults[ 'archive_breadcrumbs_setting' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'convertica_sanitizer') );
	$wp_customize->add_control( 'convertica_archive_breadcrumbs_control', array('label' => __( 'Breadcrumbs', 'femme-flora' ),'section' => 'convertica_archives_section','settings' => 'archive_breadcrumbs_setting','type' => 'radio','choices' => convertica_choices('archive_breadcrumbs_setting')) );
	$wp_customize->add_section( 'convertica_layout_widths_section', array('title' => __( 'Layout Widths', 'femme-flora' ), 'priority' => 30, 'description' => __( 'Select Layout Widths.', 'femme-flora' ), 'panel' => 'convertica_panel' ) );
	$wp_customize->add_setting( 'layout_padding', array('default' => $defaults[ 'layout_padding' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'layout_padding_control', array('label' => __( 'Column Padding', 'femme-flora' ),'section' => 'convertica_layout_widths_section','settings' => 'layout_padding','type' => 'number' ) );
	$wp_customize->add_setting( 'layout_content_1c', array('default' => $defaults[ 'layout_content_1c' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'absint') );
	$wp_customize->add_control( 'layout_content_1c_control', array('label' => __( 'Content Width&#8208;Single Column View', 'femme-flora' ),'section' => 'convertica_layout_widths_section','settings' => 'layout_content_1c','type' => 'number' ) );
	$wp_customize->add_setting( 'layout_content_2c', array('default' => $defaults[ 'layout_content_2c' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'layout_content_2c_control', array('label' => __( 'Content Width&#8208;Two Column View', 'femme-flora' ),'section' => 'convertica_layout_widths_section','settings' => 'layout_content_2c','type' => 'number' ) );
	$wp_customize->add_setting( 'layout_sb_2c', array('default' => $defaults[ 'layout_sb_2c' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'absint') );
	$wp_customize->add_control( 'layout_sb_2c_control', array('label' => __( 'Sidebar Width&#8208;Two Column View', 'femme-flora' ),'section' => 'convertica_layout_widths_section','settings' => 'layout_sb_2c','type' => 'number') );
	$wp_customize->add_setting( 'layout_content_3c', array('default' => $defaults[ 'layout_content_3c' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'layout_content_3c_control', array('label' => __( 'Content Width&#8208;Three Column View', 'femme-flora' ),'section' => 'convertica_layout_widths_section','settings' => 'layout_content_3c','type' => 'number') );
	$wp_customize->add_setting( 'layout_sb_3c', array('default' => $defaults[ 'layout_sb_3c' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'layout_sb_3c_control', array('label' => __( 'Sidebar Width&#8208;Three Column View', 'femme-flora' ),'section' => 'convertica_layout_widths_section','settings' => 'layout_sb_3c','type' => 'number') );
	$wp_customize->add_setting( 'layout_sb_sub_3c', array('default' => $defaults[ 'layout_sb_sub_3c' ],'type' => $settings_type,'transport' => $transport,'sanitize_callback' => 'absint') );
	$wp_customize->add_control( 'layout_sb_sub_3c_control', array('label' => __( 'Sidebar Subsidiary&#8208;Three Column View', 'femme-flora' ),'section' => 'convertica_layout_widths_section','settings' => 'layout_sb_sub_3c','type' => 'number') );
}

function convertica_sanitizer($value, $objsetting) {
	if ( array_key_exists( $value, convertica_choices($objsetting->id) ) ) {
        return $value;
    }
    else {
    	global $convertica_defaults;
        return $convertica_defaults[$objsetting->id];
    }	
}

function convertica_get_all_image_sizes( $size = '' ) {
	global $_wp_additional_image_sizes;
	$sizes                           = array( );
	$get_intermediate_image_sizes    = get_intermediate_image_sizes();
	$get_intermediate_image_sizes[ ] = 'full';

	$image_sizes = array( );
	foreach ( $get_intermediate_image_sizes as $value ) {
		$image_sizes[ $value ] = ucwords( $value );
	}
	return $image_sizes;
}

function convertica_customizer_css( ) {
	$padding__page = convertica_get_mod('layout_padding');
	$body_bg_color = convertica_get_mod('layout_body_bg_color');
	$wrap_bg_color = convertica_get_mod('layout_wrap_bg_color');
	$css           = '.breadcrumbs {
		margin-bottom: ' . ( $padding__page / 2 ) . 'px;
	}
	.plural .sticky {
		padding: ' . $padding__page . 'px;
	}
	.fullwidth #container {
		background-color: '.$wrap_bg_color.' !important;
	}
	.fullwidth #container {
		border-top: 0;
		border-bottom: 0;
		width: auto !important;
	}
	.wrap {
		margin:auto;
		background-color: '.$wrap_bg_color.' !important;
	}
	body {
		background-color: '.$body_bg_color.' !important;
	}
	';
	$css           = apply_filters( 'convertica_settings_css', $css );
	echo '<style type="text/css">' . $css . '</style>';
}

add_action( 'wp_head', 'convertica_customizer_css' );

/*
Returns defaults. Make shure the choices are valid.
*/

function convertica_get_defaults( ) {
	$defaults = array(
		// settings that have choices
		'archive_style' => 'excerpts',
		'layout_style_setting' => 'boxed',
		'archive_featured_image_setting' => '1',
		'archive_featured_image_size_setting' => 'full',
		'archive_featured_image_float_setting' => 'none',
		'archive_breadcrumbs_setting' => '1',

		// settings that don't have choices
		'layout_padding' => '35', // We'll add units after some calculations
		'layout_content_1c' => '940', // We'll add units after some calculations
		'layout_content_2c' => '710',
		'layout_sb_2c' => '195',
		'layout_content_3c' => '460',
		'layout_sb_3c' => '195',
		'layout_sb_sub_3c' => '195',
		'layout_body_bg_color' => '#ffffff',
		'layout_wrap_bg_color' => '#ffffff',
	);
	return apply_filters( 'convertica_settings_defaults', $defaults );
}

function convertica_choices($setting = false){
	$choices = array();

	$choices['archive_style'] = array( 'excerpts' => 'Excerpts', 'content' => 'Full Content');
	$choices['layout_style_setting'] = array('fullwidth'=> 'Full Width', 'boxed' => 'Boxed' );
	$choices['archive_featured_image_setting'] = array('1'=> 'Yes', '0' => 'No' );
	$choices['archive_featured_image_size_setting'] = convertica_get_all_image_sizes();
	$choices['archive_featured_image_float_setting'] = array( 'none' => 'none', 'center' => 'Center', 'left' => 'Left', 'right' => 'Right');
	$choices['archive_breadcrumbs_setting'] = array('1'=> 'Yes', '0' => 'No' );

	$choices = apply_filters( 'convertica_settings_choices', $choices, $setting );

	if($setting) {
		return $choices[$setting];
	}

	return $choices;
}

// show breadcrumbs conditionally
function convertica_show_breadcrumb( ) {
	// Allow filtering this till we handle custom posts and taxonomies
	$show = apply_filters( 'convertica_show_breadcrumb', convertica_get_mod('archive_breadcrumbs_setting') );

	if ( $show ) {

		if(function_exists('yoast_breadcrumb')){
			yoast_breadcrumb('<nav id="breadcrumbs" class="breadcrumbs">','</nav>');
		}
		elseif( function_exists('bcn_display') ) {
			echo '<nav class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">' . bcn_display(1) . ' </nav>';
		}
		else {
			breadcrumb_trail( array(
			'container' => 'nav',
			'separator' => '>',
			'show_on_front' => false,
			'labels' => array(
				 'browse' => esc_html__( 'You are here:', 'femme-flora' ) 
				) 
			) );
		}
	}
}

add_filter( 'convertica_settings_css', 'convertica_responsive_css' );
function convertica_responsive_css( $css ) {
	$padding__page             = convertica_get_mod('layout_padding');
	$size__site_content_1c     = convertica_get_mod('layout_content_1c');
	$size__site_content_2c     = convertica_get_mod('layout_content_2c');
	$size__site_sidebar_2c     = convertica_get_mod('layout_sb_2c');
	$size__site_content_3c     = convertica_get_mod('layout_content_3c');
	$size__site_sidebar_3c     = convertica_get_mod('layout_sb_3c');
	$size__site_sidebar_sub_3c = convertica_get_mod('layout_sb_sub_3c');
	$size__site_wrap_1c        = $padding__page + $size__site_content_1c + $padding__page;
	$size__site_page_1c        = $size__site_wrap_1c;
	$size__site_wrap_2c        = $padding__page + $size__site_content_2c + $padding__page + $size__site_sidebar_2c + $padding__page;
	$size__site_page_2c        = $size__site_wrap_2c;
	$size__site_wrap_3c        = $padding__page + $size__site_sidebar_sub_3c + $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page;
	$size__site_page_3c        = $size__site_wrap_3c;
	$size__column_container    = $size__site_content_3c + $padding__page + $size__site_sidebar_3c;
	$size__column_container    = $size__site_content_3c + $padding__page + $size__site_sidebar_3c;
	$widths[ 'three-two' ]     = $padding__page + $size__site_sidebar_sub_3c + $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page; // From the size of three column content width + sb width to size of three col wrap
	$widths[ 'three-one' ]     = $padding__page + $size__site_sidebar_3c + $padding__page + $size__site_content_3c + $padding__page; // From the size of three column content width to size of three col content + sb width
	$widths[ 'three-zero' ]    = $padding__page + $size__site_content_3c + $padding__page; // From min up to the size of three column content width
	$widths[ 'two-one' ]       = $padding__page + $size__site_content_2c + $padding__page + $size__site_sidebar_2c + $padding__page; // From bigger than bp of 2-col-content up to the size of two column wrap width
	$widths[ 'two-zero' ]      = $padding__page + $size__site_content_2c + $padding__page; // From min up to the size of two column wrap width
	$widths[ 'one-zero' ]      = $padding__page + $size__site_content_1c + $padding__page; // From min up to the size of single column wrap width
	$widths[ 'min-width' ]     = min( ( $size__site_content_1c + ( 2 * $padding__page ) ), ( $size__site_content_2c + ( 2 * $padding__page ) ), ( $size__site_content_3c + ( 2 * $padding__page ) ) );
	$responsive                = '.wrap {
			padding: 0.618em 1em;
			width: auto;
		}
	.wrap #menu-after_header-items {
			margin-left: -' . $padding__page . 'px;
			margin-right: -' . $padding__page . 'px;
			padding-left: ' . $padding__page . 'px;
			padding-right: ' . $padding__page . 'px;
		}
	.wrap #menu-before_header-items {
			margin-left: -' . $padding__page . 'px;
			margin-right: -' . $padding__page . 'px;
			padding-left: ' . $padding__page . 'px;
			padding-right: ' . $padding__page . 'px;
		}

	@media only screen and (max-width: ' . $widths[ 'min-width' ] . 'px ) {
		#menu-before_header-items,
		#menu-after_header-items {
			display: none;
		}
	}
	@media only screen and (min-width: ' . $widths[ 'min-width' ] . 'px ) {
		#site-title, #site-description {
			text-align: left;
		}
		.slicknav_menu {
			display: none;
		}
	}
	@media only screen and (min-width: ' . $widths[ 'one-zero' ] . 'px ) {
		.layout-1c #container {
			width: ' . $size__site_page_1c . 'px;
		}
		.layout-1c .wrap {
			width: ' . $size__site_wrap_1c . 'px;
			padding: 0.81em ' . $padding__page . 'px;
		}
		.layout-1c #content {
			width: ' . $size__site_content_1c . 'px;
		}
	}
	@media only screen and (min-width: ' . $widths[ 'two-one' ] . 'px ) {
		.layout-2c-l #container {
			width: ' . $size__site_page_2c . 'px;
		}
		.layout-2c-l .wrap {
			width: ' . $size__site_wrap_2c . 'px;
			padding:  0.81em ' . $padding__page . 'px;
		}
		.layout-2c-l #content {
			width: ' . $size__site_content_2c . 'px;
			float:left;
		}
		.layout-2c-l #sidebar-primary {
			width: ' . $size__site_sidebar_2c . 'px;
			float: right;
		}
		.layout-2c-r #container {
			width: ' . $size__site_page_2c . 'px;
		  }
		.layout-2c-r .wrap {
			width: ' . $size__site_wrap_2c . 'px;
			padding:  0.81em ' . $padding__page . 'px;
		  }
		.layout-2c-r #content {
			width: ' . $size__site_content_2c . 'px;
			float:right;
		  }
		.layout-2c-r #sidebar-primary {
			width: ' . $size__site_sidebar_2c . 'px;
			float: left;
		  }
	}
	@media only screen and (min-width: ' . $widths[ 'three-one' ] . 'px ) {
		.layout-3c-l #container {
			width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page ) . 'px;
			}
		.layout-3c-l .wrap {
			width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page ) . 'px ;
			padding:  0.81em ' . $padding__page . 'px;
			}
		.layout-3c-l .column-container {
			width: ' . $size__column_container . 'px;
			}
		.layout-3c-l #content {
			width: ' . $size__site_content_3c . 'px;
			float: left;
			}
		.layout-3c-l #sidebar-primary {
			width: ' . $size__site_sidebar_3c . 'px;
			float: right;
			}
		.layout-3c-l #sidebar-subsidiary {
			width: ' . $size__site_sidebar_sub_3c . 'px;
			}

		.layout-3c-r #container {
				width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page ) . 'px;
			}
		.layout-3c-r .wrap {
				width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page ) . 'px;
				padding:  0.81em ' . $padding__page . 'px;
			}
		.layout-3c-r .column-container {
				width: ' . $size__column_container . 'px;
			}
		.layout-3c-r #content {
				width: ' . $size__site_content_3c . 'px;
				float: right;
			}

		.layout-3c-r #sidebar-primary {
				width: ' . $size__site_sidebar_3c . 'px;
				float: left;
			}
		.layout-3c-r #sidebar-subsidiary {
				width: ' . $size__site_sidebar_sub_3c . 'px;
			}
		
		.layout-3c-c #container {
				width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page ) . 'px;
			}
		.layout-3c-c .wrap {
				width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page ) . 'px;
				padding:  0.81em ' . $padding__page . 'px;
			}
		.layout-3c-c .column-container {
				width: ' . $size__column_container . 'px;
			}
		.layout-3c-c #content {
				width: ' . $size__site_content_3c . 'px;
				float: left;
			}
		.layout-3c-c #sidebar-primary {
				width: ' . $size__site_sidebar_3c . 'px;
				float: right;
			}
		.layout-3c-c #sidebar-subsidiary {
				width: ' . $size__site_sidebar_sub_3c . 'px;
			}
	}

	@media only screen and (min-width: ' . $widths[ 'three-two' ] . 'px ) {
		.layout-3c-l #container {
				width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page + $size__site_sidebar_sub_3c + $padding__page ) . 'px;
			}
		.layout-3c-l .wrap {
				width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page + $size__site_sidebar_sub_3c + $padding__page ) . 'px;
				padding:  0.81em ' . $padding__page . 'px;
			}
		.layout-3c-l .column-container {
				width: ' . $size__column_container . 'px;
				float: left;
			}
		.layout-3c-l #content {
				width: ' . $size__site_content_3c . 'px;
				float: left;
			}

		.layout-3c-l #sidebar-primary {
				width: ' . $size__site_sidebar_3c . 'px;
				float: right;
			}
		.layout-3c-l #sidebar-subsidiary {
				width: ' . $size__site_sidebar_sub_3c . 'px;
				float: right;
			}
		
		.layout-3c-r #container {
				width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page + $size__site_sidebar_sub_3c + $padding__page ) . 'px;
			}
		.layout-3c-r .wrap {
				width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page + $size__site_sidebar_sub_3c + $padding__page ) . 'px;
				padding:  0.81em ' . $padding__page . 'px;
			}
		.layout-3c-r .column-container {
				width: ' . $size__column_container . 'px;
				float: right;
			}
		.layout-3c-r #content {
				width: ' . $size__site_content_3c . 'px;
				float: right;
			}
		.layout-3c-r #sidebar-primary {
				width: ' . $size__site_sidebar_3c . 'px;
				float: left;
			}
		.layout-3c-r #sidebar-subsidiary {
				width: ' . $size__site_sidebar_sub_3c . 'px;
				float: left;
			}
		
		.layout-3c-c #container {
				width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page + $size__site_sidebar_sub_3c + $padding__page ) . 'px;
			}
		.layout-3c-c .wrap {
				width: ' . ( $padding__page + $size__site_content_3c + $padding__page + $size__site_sidebar_3c + $padding__page + $size__site_sidebar_sub_3c + $padding__page ) . 'px;
				padding:  0.81em ' . $padding__page . 'px;
			}
		.layout-3c-c .column-container {
				width: ' . $size__column_container . 'px;
				float: right;
			}
		.layout-3c-c #content {
				width: ' . $size__site_content_3c . 'px;
				float: left;
			}
		.layout-3c-c #sidebar-primary {
				width: ' . $size__site_sidebar_3c . 'px;
				float: right;
			}
		.layout-3c-c #sidebar-subsidiary {
				width: ' . $size__site_sidebar_sub_3c . 'px;
				float: left;
			}
	}
';
	return $css . $responsive;
}

add_action('convertica_after_entry','convertica_after_entry_widget');

function convertica_after_entry_widget( ) {
	$show = apply_filters('convertica_show_sb_after_entry',true);
	if($show) {
		hybrid_get_sidebar('after_entry');
	}
}

add_action('convertica_before_header','convertica_sb_before_header');
add_action('convertica_after_header','convertica_sb_after_header');
add_action('convertica_before_footer','convertica_sb_before_footer');

function convertica_sb_before_header(){
	$show = apply_filters('convertica_show_sb_before_header',true);
	if($show) {
		hybrid_get_sidebar('before_header');
	}
}

function convertica_sb_after_header(){
	$show = apply_filters('convertica_show_sb_after_header',true);
	if($show) {
		hybrid_get_sidebar('after_header');
	}
}

function convertica_sb_before_footer(){
	$show = apply_filters('convertica_show_sb_before_footer',true);
	if($show) {
		hybrid_get_sidebar('before_footer');
	}
}

add_filter('convertica_show_sb_after_entry','convertica_show_sb_after_single_entry', 10 , 2);

function convertica_show_sb_after_single_entry( $show ){
	if(is_single()) {
		return true;
	}
	return false;
}

add_filter('convertica_show_default_widget_content','convertica_hide_default_widget_content');

function convertica_hide_default_widget_content($show){
	return false;
}

add_action('convertica_do_header','convertica_header');

function convertica_header(){
	$show = apply_filters('convertica_show_header',true);
	if($show) {
	?>
	<header <?php hybrid_attr( 'header' ); ?>>
		<div class="wrap">
			<?php
			do_action('convertica_before_branding');
			?>
			<?php if ( display_header_text() ) : // If user chooses to display header text. ?>
				<div <?php hybrid_attr( 'branding' ); ?>>
					<?php
					hybrid_site_title();
					hybrid_site_description(); 
					?>
				</div><!-- #branding -->
			<?php endif; // End check for header text. ?>
			<?php
			do_action('convertica_after_branding');
			?>
		</div><!-- .wrap -->
	</header><!-- #header -->
		<?php if ( get_header_image() && ! display_header_text() ) : // If there's a header image but no header text. ?>
			<a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home"><img class="header-image" src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
		<?php elseif ( get_header_image() ) : // If there's a header image. ?>
			<img class="header-image" src="<?php header_image(); ?>" width="<?php echo absint( get_custom_header()->width ); ?>" height="<?php echo absint( get_custom_header()->height ); ?>" alt="" />
		<?php endif; // End check for header image.
	}
}

add_action('convertica_after_branding','femme_flora_floral');

function femme_flora_floral() {
	?>
<div id="floral"><img src="<?php echo get_template_directory_uri() ?>/images/flower-trans.png" /></div>
	<?php
}

add_action('convertica_do_footer','convertica_footer');

function convertica_footer(){
	?>
	<footer <?php hybrid_attr( 'footer' ); ?>>
		<div class="wrap">
		<?php hybrid_get_menu( 'footer' ); // Loads the menu/subsidiary.php template. ?>
			<p class="credit">
				<?php printf(
					// Translators: 1 is current year, 2 is site name/link, 3 is WordPress name/link, and 4 is theme name/link.
					esc_html__( 'Copyright &#169; %1$s %2$s. Powered by %3$s. %4$s is built on %5$s.', 'femme-flora' ), 
					date_i18n( 'Y' ), hybrid_get_site_link(), hybrid_get_wp_link(), hybrid_get_theme_link(), '<a href="http://themehybrid.com/hybrid-core">Hybrid Core</a>'
				); ?>
			</p><!-- .credit -->
		</div><!-- .wrap -->
		</footer><!-- #footer -->
		<?php
}


add_action('convertica_do_sidebar','convertica_sidebar_primary');

function convertica_sidebar_primary() {
	$show = apply_filters('convertica_show_sidebar_primary', true);
	if($show) {
		hybrid_get_sidebar( 'primary' ); // Loads the sidebar/primary.php template. 
	}
}

add_action('convertica_do_sidebar_alt','convertica_sidebar_subsidiary');

function convertica_sidebar_subsidiary() {
	$show = apply_filters('convertica_show_sidebar_subsidiary', true);
	if($show) {
		hybrid_get_sidebar( 'subsidiary' ); // Loads the sidebar/primary.php template. 
	}
}

add_filter('convertica_background_defaults','femme_flora_background_defaults');

function femme_flora_background_defaults($defaults) {
	$defaults['default-position-x'] = 'center';
	$defaults['default-attachment'] = 'fixed';
	return $defaults;
}

add_filter('convertica_header_defaults','femme_flora_header_defaults');

function femme_flora_header_defaults($defaults) {
	$defaults['default-text-color'] = '#fff';
	return $defaults;
}