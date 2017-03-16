<?php

add_action('convertica_before_header','convertica_before_header_menu','11');
add_action('convertica_after_header','convertica_after_header_menu');

function convertica_before_header_menu(){
	hybrid_get_menu( 'before_header' ); // Loads the menu/primary.php template.
}
function convertica_after_header_menu(){
	hybrid_get_menu( 'after_header' ); // Loads the menu/primary.php template.
}



add_action('convertica_after_entry_header','convertica_after_entry_header');

function convertica_after_entry_header(){
	if(convertica_get_mod('archive_featured_image_setting') == '1') {
		$float = 'align'.convertica_get_mod('archive_featured_image_float_setting');
		get_the_image(array('size' => convertica_get_mod('archive_featured_image_size_setting'), 'image_class' => "featured after $float"));
	}
}

# Register custom image sizes.
add_action( 'init', 'hybrid_base_register_image_sizes', 5 );

# Register custom menus.
add_action( 'init', 'hybrid_base_register_menus', 5 );

# Register custom layouts.
add_action( 'hybrid_register_layouts', 'hybrid_base_register_layouts' );

# Register sidebars.
add_action( 'widgets_init', 'hybrid_base_register_sidebars', 5 );

# Add custom scripts and styles
add_action( 'wp_enqueue_scripts', 'hybrid_base_enqueue_scripts', 5 );
add_action( 'wp_enqueue_scripts', 'hybrid_base_enqueue_styles',  5 );

/**
 * Registers custom image sizes for the theme.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function hybrid_base_register_image_sizes() {

	// Sets the 'post-thumbnail' size.
	//set_post_thumbnail_size( 150, 150, true );
}

/**
 * Registers nav menu locations.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function hybrid_base_register_menus() {
	register_nav_menu( 'before_header', esc_html_x( 'Before Header', 'nav menu location', 'femme-flora' ) );
	register_nav_menu( 'after_header', esc_html_x( 'After Header', 'nav menu location', 'femme-flora' ) );
	register_nav_menu( 'footer', esc_html_x( 'Footer', 'nav menu location', 'femme-flora' ) );
}

/**
 * Registers layouts.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function hybrid_base_register_layouts() {

	hybrid_register_layout( '1c',   array( 'label' => esc_html__( '1 Column', 'femme-flora' ), 'image' => '%s/images/layouts/1c.svg'   ) );
	hybrid_register_layout( '2c-l', array( 'label' => esc_html__( '2 Columns: Content / Sidebar', 'femme-flora' ), 'image' => '%s/images/layouts/2c-l.svg' ) );
	hybrid_register_layout( '2c-r', array( 'label' => esc_html__( '2 Columns: Sidebar / Content', 'femme-flora' ), 'image' => '%s/images/layouts/2c-r.svg' ) );
	hybrid_register_layout( '3c-l', array( 'label' => esc_html__( '3 Columns: Content / Sidebar / Sidebar', 'femme-flora' ), 'image' => '%s/images/layouts/3c-l.svg' ) );
	hybrid_register_layout( '3c-r', array( 'label' => esc_html__( '3 Columns: Sidebar / Sidebar / Content', 'femme-flora' ), 'image' => '%s/images/layouts/3c-r.svg' ) );
	hybrid_register_layout( '3c-c', array( 'label' => esc_html__( '3 Columns: Sidebar / Content / Sidebar', 'femme-flora' ), 'image' => '%s/images/layouts/3c-c.svg' ) );
}

/**
 * Registers sidebars.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function hybrid_base_register_sidebars() {

	hybrid_register_sidebar(
		array(
			'id'          => 'primary',
			'name'        => esc_html_x( 'Primary', 'sidebar', 'femme-flora' ),
			'description' => esc_html__( 'This is the primary sidebar if you are using a two or three column layout option.', 'femme-flora' )
		)
	);

	hybrid_register_sidebar(
		array(
			'id'          => 'subsidiary',
			'name'        => esc_html_x( 'Subsidiary', 'sidebar', 'femme-flora' ),
			'description' => esc_html__( 'This is the subsidiary sidebar if you are using a three column layout option.', 'femme-flora' )
		)
	);

	hybrid_register_sidebar(
		array(
			'id'          => 'after_entry',
			'name'        => esc_html_x( 'After Entry', 'sidebar', 'femme-flora' ),
			'description' => esc_html__( 'This is after entry widget area.', 'femme-flora' )
		)
	);

	hybrid_register_sidebar(
		array(
			'id'          => 'before_header',
			'name'        => esc_html_x( 'Before Header', 'sidebar', 'femme-flora' ),
			'description' => esc_html__( 'This is before header widget area.', 'femme-flora' )
		)
	);
	hybrid_register_sidebar(
		array(
			'id'          => 'after_header',
			'name'        => esc_html_x( 'After Header', 'sidebar', 'femme-flora' ),
			'description' => esc_html__( 'This is after header widget area.', 'femme-flora' )
		)
	);
	hybrid_register_sidebar(
		array(
			'id'          => 'before_footer',
			'name'        => esc_html_x( 'Before Footer', 'sidebar', 'femme-flora' ),
			'description' => esc_html__( 'This is before footer widget area.', 'femme-flora' )
		)
	);	
}

/**
 * Load scripts for the front end.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function hybrid_base_enqueue_scripts() {
}

/**
 * Load stylesheets for the front end.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function hybrid_base_enqueue_styles() {

	// Load one-five base style.
	//wp_enqueue_style( 'hybrid-one-five' );

	// Load gallery style if 'cleaner-gallery' is active.
	if ( current_theme_supports( 'cleaner-gallery' ) )
		wp_enqueue_style( 'hybrid-gallery' );

	// Load parent theme stylesheet if child theme is active.
	if ( is_child_theme() )
		wp_enqueue_style( 'hybrid-parent' );

	// Load active theme stylesheet.
	wp_enqueue_style( 'hybrid-style' );
}