<?php
/**
 * Bosa Crypto Theme Customizer
 *
 * @package Bosa Crypto
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function bosa_crypto_customize_register( $wp_customize ) {

	// Load custom control functions.
	require get_template_directory() . '/inc/customizer/controls.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

	// Register custom section types.
	$wp_customize->register_section_type( 'Bosa_Crypto_Customize_Section_Upsell' );

	// Register sections.
	$wp_customize->add_section(
		new Bosa_Crypto_Customize_Section_Upsell(
			$wp_customize,
			'bosa_crypto_theme_upsell',
			array(
				'title'    => esc_html__( 'Bosa Pro', 'bosa-crypto' ),
				'pro_text' => esc_html__( 'Upgrade To Pro', 'bosa-crypto' ),
				'pro_url'  => 'https://bosathemes.com/bosa-pro',
				'priority'  => 1,
			)
		)
	);
}
add_action( 'customize_register', 'bosa_crypto_customize_register' );

/**
 * Restructures WooCommerce product catalog customizer options.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function bosa_crypto_woocommerce_customizer_structure( $wp_customize ) {

	if ( class_exists( 'WooCommerce') ) {
		$wp_customize->get_control( 'woocommerce_shop_page_display' )->priority  = '600';
		$wp_customize->get_control( 'woocommerce_category_archive_display' )->priority  = '610';
		$wp_customize->get_control( 'woocommerce_default_catalog_orderby' )->priority  = '620';
	}
}
add_action( 'customize_register', 'bosa_crypto_woocommerce_customizer_structure', 15 );

/**
 * Enqueue style for custom customize control.
 */
add_action( 'customize_controls_enqueue_scripts', 'bosa_crypto_custom_customize_enqueue' );
function bosa_crypto_custom_customize_enqueue() {
	wp_enqueue_style( 'bosa-crypto-customize-controls', get_template_directory_uri() . '/inc/customizer/customizer.css' );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function bosa_crypto_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function bosa_crypto_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function bosa_crypto_customize_preview_js() {
	wp_enqueue_script( 'bosa-crypto-customizer', get_template_directory_uri() . '/inc/customizer/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'bosa_crypto_customize_preview_js' );

function bosa_crypto_customize_getting_js() {
	wp_enqueue_script( 'bosa-crypto-customizer-getting-started', get_template_directory_uri() . '/inc/getting-started/getting-started.js', array( 'customize-controls', 'jquery' ), true );
}
add_action( 'customize_controls_enqueue_scripts', 'bosa_crypto_customize_getting_js' );


/**
 * Sanitize checkboxes
 */

function bosa_crypto_sanitize_checkbox( $input ){
	if ( $input === true ) {
        return true;
    } else {
        return false;
    }
}

/**
 * Sanitize Selects
 */
function bosa_crypto_sanitize_select( $input, $setting ){
          
    $input = sanitize_key($input);

    $choices = $setting->manager->get_control( $setting->id )->choices;
                      
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );                
      
}


/**
 * Popular Crypto section callback
 */
function bosa_crypto_is_enable_popular_cryptos() {
	$value = get_theme_mod( 'bosa_crypto_disable_popular_cryptos_section', true );

	if ( $value ) {
		return false;
	} else {
		return true;
	} 
}

/**
 * Testimonials section callback
 */
function bosa_crypto_is_enable_testimonials_section() {
	$value = get_theme_mod( 'bosa_crypto_disable_testimonials_section', true );

	if ( $value ) {
		return false;
	} else {
		return true;
	} 
}

/**
 * Crypto Info section callback
 */
function bosa_crypto_is_enable_crypto_info_section() {
	$value = get_theme_mod( 'bosa_crypto_disable_crypto_info_section', true );

	if ( $value ) {
		return false;
	} else {
		return true;
	} 
}

function bosa_crypto_customizer_structure( $wp_customize ) {

	// Blog Homepage Options
	$wp_customize->add_panel( 'bosa_crypto_blog_homepage_options', array(
	    'title' 	 => esc_html__( 'Blog Homepage', 'bosa-crypto' ),
	    'priority'	 => 120,
	    'capability' => 'edit_theme_options',
	) );

	// Responsive
	$wp_customize->add_section( 'bosa_crypto_blog_page_responsive', array(
	    'title'		 => esc_html__( 'Responsive', 'bosa-crypto' ),
	    'description'    => esc_html__( 'These options will only apply to Tablet and Mobile devices. Please
	    	click on below Tablet or Mobile Icons to see changes.', 'bosa-crypto' ),
	    'panel' 	 => 'bosa_crypto_blog_homepage_options',
	    'priority'	 => 50,
	    'capability' => 'edit_theme_options',
	) );

	// Blog Popular Cryptos
	$wp_customize->add_section( 'bosa_crypto_blog_popular_cryptos', array(
	    'title'          => esc_html__( 'Popular Cryptos', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_blog_homepage_options',
	    'capability'     => 'edit_theme_options',
	    'priority'       => 26,
	) );

	$wp_customize->add_setting( 'bosa_crypto_disable_popular_cryptos_section', array(
	    'default' 			=> true,
	    'sanitize_callback' => 'bosa_crypto_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'bosa_crypto_disable_popular_cryptos_section', array(
	    'label'        => esc_html__( 'Disable Popular Cryptos Section', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'section'      => 'bosa_crypto_blog_popular_cryptos',
		'priority'	   => 10, 
	) );

	$wp_customize->add_setting('bosa_crypto_crypto_title', array(
		'default'=>'',
		'sanitize_callback'=>'sanitize_text_field',
	));

	$wp_customize->add_control('bosa_crypto_crypto_title', array(
		'label'     => esc_html__( 'Crypto Title', 'bosa-crypto' ),
		'section'	=> 'bosa_crypto_blog_popular_cryptos',
		'type'		=> 'text',
		'priority'	=> 20,
	));

	$wp_customize->add_setting( 'bosa_crypto_blog_crypto_image_one', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'bosa_crypto_blog_crypto_image_one',
			array(
				'label'           => esc_html__( 'Crypto One Image', 'bosa-crypto' ),
				'section'         => 'bosa_crypto_blog_popular_cryptos',
				'mime_type'       => 'image',
				'priority'	      => 30
			)
		)
	);

	$wp_customize->add_setting( 'bosa_crypto_blog_crypto_image_two', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'bosa_crypto_blog_crypto_image_two',
			array(
				'label'           => esc_html__( 'Crypto Two Image', 'bosa-crypto' ),
				'section'         => 'bosa_crypto_blog_popular_cryptos',
				'mime_type'       => 'image',
				'priority'	      => 40
			)
		)
	);

	$wp_customize->add_setting( 'bosa_crypto_blog_crypto_image_three', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'bosa_crypto_blog_crypto_image_three',
			array(
				'label'           => esc_html__( 'Crypto Three Image', 'bosa-crypto' ),
				'section'         => 'bosa_crypto_blog_popular_cryptos',
				'mime_type'       => 'image',
				'priority'	      => 50
			)
		)
	);

	$wp_customize->add_setting( 'bosa_crypto_blog_crypto_image_four', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'bosa_crypto_blog_crypto_image_four',
			array(
				'label'           => esc_html__( 'Crypto Four Image', 'bosa-crypto' ),
				'section'         => 'bosa_crypto_blog_popular_cryptos',
				'mime_type'       => 'image',
				'priority'	      => 60
			)
		)
	);

	$wp_customize->add_setting( 'bosa_crypto_blog_crypto_image_five', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'bosa_crypto_blog_crypto_image_five',
			array(
				'label'           => esc_html__( 'Crypto Five Image', 'bosa-crypto' ),
				'section'         => 'bosa_crypto_blog_popular_cryptos',
				'mime_type'       => 'image',
				'priority'	      => 70
			)
		)
	);

	$wp_customize->add_setting( 'bosa_crypto_blog_crypto_image_six', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Media_Control( 
			$wp_customize, 
			'bosa_crypto_blog_crypto_image_six',
			array(
				'label'           => esc_html__( 'Crypto six Image', 'bosa-crypto' ),
				'section'         => 'bosa_crypto_blog_popular_cryptos',
				'mime_type'       => 'image',
				'priority'	      => 80
			)
		)
	);

	// responsive popular crypto
	$wp_customize->add_setting( 'bosa_crypto_disable_mobile_popular_cryptos', array(
	    'default' => false,
	    'sanitize_callback' => 'bosa_crypto_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'bosa_crypto_disable_mobile_popular_cryptos', array(
	    'label'        	=> esc_html__( 'Disable Popular Cryptos Section', 'bosa-crypto' ),
	    'type'			=> 'checkbox',
	    'priority' 		=> 26,
	    'section' 		=> 'bosa_crypto_blog_page_responsive',
	    'active_callback' => 'bosa_crypto_is_enable_popular_cryptos',
	) );

	// Blog testimonials
	$wp_customize->add_section( 'bosa_crypto_blog_testimonials', array(
	    'title'          => esc_html__( 'Testimonials', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_blog_homepage_options',
	    'capability'     => 'edit_theme_options',
	    'priority'       => 27,	
	) );

	$wp_customize->add_setting( 'bosa_crypto_disable_testimonials_section', array(
	    'default' 			=> true,
	    'sanitize_callback' => 'bosa_crypto_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'bosa_crypto_disable_testimonials_section', array(
	    'label' 	=> __( 'Disable Testimonials Section', 'bosa-crypto' ),
	    'type' 		=> 'checkbox',
	    'priority'	=> 10,
	    'section' 	=> 'bosa_crypto_blog_testimonials', 
	) );

	$wp_customize->add_setting( 'bosa_crypto_testimonials_page_one', array(
		'sanitize_callback' => 'bosa_crypto_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'bosa_crypto_testimonials_page_one', array(
		'label'       => esc_html__( 'Page 1', 'bosa-crypto' ),
		'type'        => 'select',
		'section'     => 'bosa_crypto_blog_testimonials',
		'placeholder' => esc_html__( 'Select Page 1', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_pages(),
		'priority'	  => 20,
	) );

	$wp_customize->add_setting( 'bosa_crypto_testimonials_page_two', array(
		'sanitize_callback' => 'bosa_crypto_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'bosa_crypto_testimonials_page_two', array(
		'label'       => esc_html__( 'Page 2', 'bosa-crypto' ),
		'type'        => 'select',
		'section'     => 'bosa_crypto_blog_testimonials',
		'placeholder' => esc_html__( 'Select Page 2', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_pages(),
		'priority'	  => 30,
	) );

	$wp_customize->add_setting( 'bosa_crypto_testimonials_page_three', array(
		'sanitize_callback' => 'bosa_crypto_sanitize_select',
		'default' 			=> '',
	) );

	$wp_customize->add_control( 'bosa_crypto_testimonials_page_three', array(
		'label'       => esc_html__( 'Page 3', 'bosa-crypto' ),
		'type'        => 'select',
		'section'     => 'bosa_crypto_blog_testimonials',
		'placeholder' => esc_html__( 'Select Page 3', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_pages(),
		'priority'	  => 40,
	) );

	// responsive testimonials
	$wp_customize->add_setting( 'bosa_crypto_disable_mobile_testimonials', array(
	    'default' => false,
	    'sanitize_callback' => 'bosa_crypto_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'bosa_crypto_disable_mobile_testimonials', array(
	    'label'        => esc_html__( 'Disable Testimonials', 'bosa-crypto' ),
	    'type' => 'checkbox',
	    'priority' => 27,
	    'section' => 'bosa_crypto_blog_page_responsive',
	    'active_callback' => 'bosa_crypto_is_enable_testimonials_section',
	) );

	//Blog Crypto Info
	$wp_customize->add_section( 'bosa_crypto_blog_crypto_info', array(
	    'title'          => esc_html__( 'Crypro Info', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_blog_homepage_options',
	    'capability'     => 'edit_theme_options',
	    'priority'       => 28,
	) );

	$wp_customize->add_setting( 'bosa_crypto_disable_crypto_info_section', array(
	    'default' 			=> true,
	    'sanitize_callback' => 'bosa_crypto_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'bosa_crypto_disable_crypto_info_section', array(
	    'label'        => esc_html__( 'Disable Crypto Info Section', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'section'      => 'bosa_crypto_blog_crypto_info',
		'priority'	   => 10, 
	) );

	$wp_customize->add_setting('bosa_crypto_crypto_info_one_title', array(
		'default'=>'',
		'sanitize_callback'=>'sanitize_text_field',
	));

	$wp_customize->add_control('bosa_crypto_crypto_info_one_title', array(
		'label'     => esc_html__( 'Info One Title', 'bosa-crypto' ),
		'section'	=> 'bosa_crypto_blog_crypto_info',
		'type'		=> 'text',
		'priority'	=> 20,
	));

	$wp_customize->add_setting('bosa_crypto_crypto_info_one_content', array(
		'default'=>'',
		'sanitize_callback'=>'sanitize_text_field',
	));

	$wp_customize->add_control('bosa_crypto_crypto_info_one_content', array(
		'label'     => esc_html__( 'Info One Content', 'bosa-crypto' ),
		'section'	=> 'bosa_crypto_blog_crypto_info',
		'type'		=> 'text',
		'priority'	=> 30,
	));

	$wp_customize->add_setting('bosa_crypto_crypto_info_two_title', array(
		'default'=>'',
		'sanitize_callback'=>'sanitize_text_field',
	));

	$wp_customize->add_control('bosa_crypto_crypto_info_two_title', array(
		'label'     => esc_html__( 'Info Two Title', 'bosa-crypto' ),
		'section'	=> 'bosa_crypto_blog_crypto_info',
		'type'		=> 'text',
		'priority'	=> 40,
	));

	$wp_customize->add_setting('bosa_crypto_crypto_info_two_content', array(
		'default'=>'',
		'sanitize_callback'=>'sanitize_text_field',
	));

	$wp_customize->add_control('bosa_crypto_crypto_info_two_content', array(
		'label'     => esc_html__( 'Info Two Content', 'bosa-crypto' ),
		'section'	=> 'bosa_crypto_blog_crypto_info',
		'type'		=> 'text',
		'priority'	=> 50,
	));

	$wp_customize->add_setting('bosa_crypto_crypto_info_three_title', array(
		'default'=>'',
		'sanitize_callback'=>'sanitize_text_field',
	));

	$wp_customize->add_control('bosa_crypto_crypto_info_three_title', array(
		'label'     => esc_html__( 'Info Three Title', 'bosa-crypto' ),
		'section'	=> 'bosa_crypto_blog_crypto_info',
		'type'		=> 'text',
		'priority'	=> 60,
	));

	$wp_customize->add_setting('bosa_crypto_crypto_info_three_content', array(
		'default'=>'',
		'sanitize_callback'=>'sanitize_text_field',
	));

	$wp_customize->add_control('bosa_crypto_crypto_info_three_content', array(
		'label'     => esc_html__( 'Info Three Content', 'bosa-crypto' ),
		'section'	=> 'bosa_crypto_blog_crypto_info',
		'type'		=> 'text',
		'priority'	=> 70,
	));

	$wp_customize->add_setting('bosa_crypto_crypto_info_four_title', array(
		'default'=>'',
		'sanitize_callback'=>'sanitize_text_field',
	));

	$wp_customize->add_control('bosa_crypto_crypto_info_four_title', array(
		'label'     => esc_html__( 'Info Four Title', 'bosa-crypto' ),
		'section'	=> 'bosa_crypto_blog_crypto_info',
		'type'		=> 'text',
		'priority'	=> 80,
	));

	$wp_customize->add_setting('bosa_crypto_crypto_info_four_content', array(
		'default'=>'',
		'sanitize_callback'=>'sanitize_text_field',
	));

	$wp_customize->add_control('bosa_crypto_crypto_info_four_content', array(
		'label'     => esc_html__( 'Info Four Content', 'bosa-crypto' ),
		'section'	=> 'bosa_crypto_blog_crypto_info',
		'type'		=> 'text',
		'priority'	=> 90,
	));

	// responsive Crypto Info
	$wp_customize->add_setting( 'bosa_crypto_disable_mobile_crypto_info', array(
	    'default' => false,
	    'sanitize_callback' => 'bosa_crypto_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'bosa_crypto_disable_mobile_crypto_info', array(
	    'label'        => esc_html__( 'Disable Crypto Info', 'bosa-crypto' ),
	    'type' => 'checkbox',
	    'priority' => 28,
	    'section' => 'bosa_crypto_blog_page_responsive',
	    'active_callback' => 'bosa_crypto_is_enable_crypto_info_section',
	) );
}
add_action( 'customize_register', 'bosa_crypto_customizer_structure', 15 );

/**
 * Kirki Customizer
 *
 * @return void
 */
add_action( 'init' , 'bosa_crypto_kirki_fields', 999, 1 );

function bosa_crypto_kirki_fields(){

	/**
	* If kirki is not installed do not run the kirki fields
	*/

	if ( !class_exists( 'Kirki' ) ) {
		return;
	}

	Kirki::add_config( 'bosa-crypto', array(
		'capability'  => 'edit_theme_options',
		'option_type' => 'theme_mod',
	) );

	// Site Identity - Title & Tagline
	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Logo Image Width', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_logo_width',
		'section'      => 'bosa_crypto_title_tagline',
		'transport'    => 'postMessage',
		'priority'     => '8',
		'default'      => 270,
		'choices'      => array(
			'min'  => 50,
			'max'  => 270,
			'step' => 5,
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Site Title', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_site_title',
		'section'      => 'bosa_crypto_title_tagline',
		'priority'     => '10',
		'default'      => false,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Site Tagline', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_site_tagline',
		'section'      => 'bosa_crypto_title_tagline',
		'priority'     => '20',
		'default'      => false,
	) );

	// Colors Options
	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Body Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_site_body_text_color',
		'section'      => 'bosa_crypto_colors',
		'default'      => '#333333',
		'priority'     => '20',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),

	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'General Heading Text Color (H1 - H6)', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_site_heading_text_color',
		'section'      => 'bosa_crypto_colors',
		'default'      => '#030303',
		'priority'     => '30',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),

	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'General Link Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_site_general_link_color',
		'section'      => 'bosa_crypto_colors',
		'default'      => '#a6a6a6',
		'priority'     => '35',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Page and Single Post Title', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_header_textcolor',
		'section'      => 'bosa_crypto_colors',
		'default'      => '#101010',
		'priority'     => '40',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Primary Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_site_primary_color',
		'section'      => 'bosa_crypto_colors',
		'default'      => '#00D094',
		'priority'     => '50',
	) );
	
	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_site_hover_color',
		'section'      => 'bosa_crypto_colors',
		'default'     => '#0A4FD5',
		'priority'    => '60',
	) );

	// Header Options
	Kirki::add_panel( 'bosa_crypto_header_options', array(
	    'title' => esc_html__( 'Header', 'bosa-crypto' ),
	    'priority' => '10',
	) );

	// Header Style Options
	Kirki::add_section( 'bosa_crypto_header_style_options', array(
	    'title'      => esc_html__( 'Style', 'bosa-crypto' ),
	    'panel'      => 'bosa_crypto_header_options',	   
	    'capability' => 'edit_theme_options',
	    'priority'   => '30',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Header Layouts', 'bosa-crypto' ),
		'description' => esc_html__( 'Select layout & scroll below to change its options', 'bosa-crypto' ),
		'type'        => 'radio-image',
		'settings'    => 'bosa_crypto_header_layout',
		'section'     => 'bosa_crypto_header_style_options',
		'default'     => 'header_one',
		'priority'	  => '40',
		'choices'     => apply_filters( 'bosa_crypto_header_layout_filter', array(
			'header_one'    => get_template_directory_uri() . '/assets/images/header-layout-1.png',
			'header_two'    => get_template_directory_uri() . '/assets/images/header-layout-2.png',
			'header_three'  => get_template_directory_uri() . '/assets/images/header-layout-3.png',
			'header_fourteen'   => get_stylesheet_directory_uri() . '/assets/images/header-layout-14.png'
		) ),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Top Header Section', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_top_header_section',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => false,
		'priority'	   => '50',
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_header_two_home_separator',
	    'section'     => 'bosa_crypto_header_style_options',
	    'default'     => esc_html__( 'Transparent Header Options', 'bosa-crypto' ),
	    'priority'	  => '60',
	    'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_two' ),
			),
		),
	) );

	// Header two 
	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Header Site Logo', 'bosa-crypto' ),
		'description'  => esc_html__( 'Fully white or light color with image dimensions 320 by 120 pixels is recommended.', 'bosa-crypto' ),
		'type'         => 'image',
		'settings'     => 'bosa_crypto_header_separate_logo',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '',
		'priority'	  =>  '70',
		'choices'     => array(
			'save_as' => 'id',
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Header Site Title Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_site_title_color_transparent_header',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#ffffff',
		'priority'	   => '80',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_two' ),
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_site_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Header Site Tagline Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_site_tagline_color_transparent_header',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#e6e6e6',
		'priority'	   => '90',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_two' ),
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_site_tagline',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Top Header Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_transparent_header_top_background_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '',
		'priority'	   => '100',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_two' ),
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Top Header Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_transparent_header_top_header_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#ffffff',
		'priority'	   => '110',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_two' ),
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Top Header Text Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_top_hover_color_transparent_header',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#0A4FD5',
		'priority'	   => '120',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Bottom Header Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_transparent_header_bottom_background_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '',
		'priority'	   => '130',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_two' ),
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Header Menu Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_content_color_transparent_header',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#ffffff',
		'priority'	   => '140',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_two' ),
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Bottom Header Text Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_content_hover_color_transparent_header',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#0A4FD5',
		'priority'	   => '150',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_header_two_general_separator',
	    'section'     => 'bosa_crypto_header_style_options',
	    'default'     => esc_html__( 'Non Transparent Header Options', 'bosa-crypto' ),
	    'priority'	  => '160',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Non Transparent Header Site Title Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_site_title_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#030303',
		'priority'	   => '170',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_site_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Non Transparent Header Site Tagline Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_site_tagline_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#767676',
		'priority'	   => '180',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_site_tagline',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Non Transparent Top Header Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_top_header_background_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '',
		'priority'	   => '190',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Non Transparent Top Header Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_top_header_text_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#333333',
		'priority'	   => '200',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Non Transparent Top Header Text Link Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_top_header_text_link_hover_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#0A4FD5',
		'priority'	  =>  '210',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Top Header Section Border', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_top_header_border',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => false,
		'priority'	   => '220',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Non Transparent Mid Header Background Color', 'bosa-crypto' ),
		'description'  => esc_html__( 'It can be used as a transparent background color over image.', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_mid_header_background_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '',
		'priority'	   => '230',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_three' ),
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Non Transparent Mid Header Text Link Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_mid_header_text_link_hover_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#0A4FD5',
		'priority'	   => '240',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_three' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Mid Header Section Border', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_mid_header_border',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => false,
		'priority'	   => '250',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_three' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Non Transparent Bottom Header Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_bottom_header_background_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '',
		'priority'	  =>  '260',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Non Transparent Bottom Header Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_bottom_header_text_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#333333',
		'priority'	   => '270',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Non Transparent Bottom Header Text Link Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_bottom_header_text_link_hover_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#0A4FD5',
		'priority'	   =>  '280',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Sub Menu Link Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_sub_menu_link_hover_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#0A4FD5',
		'priority'	   =>  '290',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Header Height (in px)', 'bosa-crypto' ),
		'description' => esc_html__( 'This option will only apply to Desktop. Please click on below Desktop Icon to see changes. Automatically adjust by theme default in the responsive devices.
		', 'bosa-crypto' ),
		'type'        => 'slider',
		'settings'    => 'bosa_crypto_header_image_height',
		'section'     => 'bosa_crypto_header_style_options',
		'transport'   => 'postMessage',
		'default'     => 100,
		'priority'	  => '300',
		'choices'     => array(
			'min'  => 50,
			'max'  => 1200,
			'step' => 10,
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_contact_details_separator',
	    'section'     => 'bosa_crypto_header_style_options',
	    'default'     => esc_html__( 'Contact Details Options', 'bosa-crypto' ),
	    'priority'	  => '310',
	    'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two', 'header_fourteen' ),
			),
		),
	) );

    // Contact Detail Options
	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Contact Details', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_contact_detail',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => false,
		'priority'	   => '320',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two', 'header_fourteen' ),
			),
		),
	) );

	// Header contact labels for header 14
	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Contact Detail Icon color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_header_icon_color',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '#B7B7B7',
		'priority'	   => '330',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_fourteen' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_contact_detail',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Phone Number Label', 'bosa-crypto' ),
		'type'         => 'text',
		'settings'     => 'bosa_crypto_header_phone_label',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => "",
		'priority'	   => '340',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_fourteen' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_contact_detail',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Phone Number', 'bosa-crypto' ),
		'type'         => 'text',
		'settings'     => 'bosa_crypto_contact_phone',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '',
		'priority'	   => '350',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_contact_detail',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two', 'header_fourteen' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Email', 'bosa-crypto' ),
		'type'         => 'text',
		'settings'     => 'bosa_crypto_contact_email',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '',
		'priority'	   => '360',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_contact_detail',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two', 'header_fourteen' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Address', 'bosa-crypto' ),
		'type'         => 'text',
		'settings'     => 'bosa_crypto_contact_address',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => '',
		'priority'	   => '370',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_contact_detail',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two', 'header_fourteen' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_header_button_separator',
	    'section'     => 'bosa_crypto_header_style_options',
	    'default'     => esc_html__( 'Header Button Options', 'bosa-crypto' ),
	    'priority'	  => '380',
	    'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two', 'header_fourteen' ),
			),
		),
	) );

	// Header button
	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Header Buttons', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_header_button',
		'section'      => 'bosa_crypto_header_style_options',
		'default'      => false,
		'priority'	   => '390',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two', 'header_fourteen' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Header Buttons', 'bosa-crypto' ),
		'type'         => 'repeater',
		'settings'     => 'bosa_crypto_header_button_repeater',
		'section'      => 'bosa_crypto_header_style_options',
		'priority'	   => '400',
		'row_label' => array(
			'type'  => 'text',
			'value' => esc_html__( 'Button', 'bosa-crypto' ),
		),
		'default' => array(
			array(
				'header_btn_type' 			=> 'button-outline',
				'header_btn_bg_color'		=> '#00D094',
				'header_btn_border_color'	=> '#1a1a1a',
				'header_btn_text_color'		=> '#1a1a1a',
				'header_btn_hover_color'	=> '#0A4FD5',
				'header_btn_text' 			=> '',
				'header_btn_link' 			=> '',
				'header_btn_target'			=> true,
				'header_btn_radius'			=> 0,
			),		
		),
		'fields' => array(
			'header_btn_type' => array(
				'label'       => esc_html__( 'Button Type', 'bosa-crypto' ),
				'type'        => 'select',
				'default'     => 'button-outline',
				'choices'  	  => array(
					'button-primary' => esc_html__( 'Background Button', 'bosa-crypto' ),
					'button-outline' => esc_html__( 'Border Button', 'bosa-crypto' ),
					'button-text'    => esc_html__( 'Text Only Button', 'bosa-crypto' ),
				),
			),
			'header_btn_bg_color' 	=> array(
				'label'       		=> esc_html__( 'Non Transparent Header Button Background Color', 'bosa-crypto' ),
				'description' 		=> esc_html__( 'For background button type only.', 'bosa-crypto' ),
				'type'        		=> 'color',
				'default'     		=> '#00D094',
			),
			'header_btn_border_color' 	=> array(
				'label'      	 		=> esc_html__( 'Non Transparent Header Button Border Color', 'bosa-crypto' ),
				'description' 			=> esc_html__( 'For border button type only.', 'bosa-crypto' ),
				'type'       		 	=> 'color',
				'default'     			=> '#1a1a1a',
			),
			'header_btn_text_color' => array(
				'label'       		=> esc_html__( 'Non Transparent Header Button Text Color', 'bosa-crypto' ),
				'type'        		=> 'color',
				'default'     		=> '#1a1a1a',
			),
			'header_btn_hover_color' => array(
				'label'       		=> esc_html__( 'Non Transparent Header Button Hover Color', 'bosa-crypto' ),
				'type'        		=> 'color',
				'default'     		=> '#0A4FD5',
			),
			'header_btn_text' => array(
				'label'       => esc_html__( 'Button Text', 'bosa-crypto' ),
				'type'        => 'text',
				'default' 	  => '',
			),
			'header_btn_link' => array(
				'label'       => esc_html__( 'Button Link', 'bosa-crypto' ),
				'type'        => 'text',
				'default' 	  => '',
			),
			'header_btn_target' => array(
				'label'       	=> esc_html__( 'Open Link in New Window', 'bosa-crypto' ),	
				'type'        	=> 'checkbox',
				'default'	  	=> true,
			),
			'header_btn_radius' => array(
				'label'       	=> esc_html__( 'Button Radius (px)', 'bosa-crypto' ),
				'type'        	=> 'number',
				'default'	  	=> 0,
				'choices'     	=> array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			),	
		),
		'choices' => array(
			'limit' => 2,
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_header_button',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_fourteen' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Header Buttons', 'bosa-crypto' ),
		'type'         => 'repeater',
		'settings'     => 'bosa_crypto_transparent_header_button_repeater',
		'section'      => 'bosa_crypto_header_style_options',
		'priority'	   => '410',
		'row_label' => array(
			'type'  => 'text',
			'value' => esc_html__( 'Button', 'bosa-crypto' ),
		),
		'default' => array(
			array(
				'transparent_header_btn_type' 				=> 'button-outline',
				'transparent_header_home_btn_bg_color'		=> '#00D094',
				'transparent_header_home_btn_border_color'	=> '#ffffff',
				'transparent_header_home_btn_text_color'	=> '#ffffff',
				'transparent_header_btn_bg_color'			=> '#00D094',
				'transparent_header_btn_border_color'		=> '#1a1a1a',
				'transparent_header_btn_text_color'			=> '#1a1a1a',
				'transparent_header_btn_hover_color'		=> '#0A4FD5',
				'transparent_header_btn_text' 				=> '',
				'transparent_header_btn_link' 				=> '',
				'transparent_header_btn_target'				=> true,
				'transparent_header_btn_radius'				=> 0,
			),		
		),
		'fields' => array(
			'transparent_header_btn_type' => array(
				'label'       => esc_html__( 'Button Type', 'bosa-crypto' ),
				'type'        => 'select',
				'default'     => 'button-outline',
				'choices'  	  => array(
					'button-primary' => esc_html__( 'Background Button', 'bosa-crypto' ),
					'button-outline' => esc_html__( 'Border Button', 'bosa-crypto' ),
					'button-text'    => esc_html__( 'Text Only Button', 'bosa-crypto' ),
				),
			),
			'transparent_header_home_btn_bg_color' 	=> array(
				'label'       		=> esc_html__( 'Transparent Header Button Background Color', 'bosa-crypto' ),
				'description' 		=> esc_html__( 'For background button type only.', 'bosa-crypto' ),
				'type'        		=> 'color',
				'default'     		=> '#00D094',
			),
			'transparent_header_home_btn_border_color' 	=> array(
				'label'      	 		=> esc_html__( 'Transparent Header Button Border Color', 'bosa-crypto' ),
				'description' 			=> esc_html__( 'For border button type only.', 'bosa-crypto' ),
				'type'       		 	=> 'color',
				'default'     			=> '#ffffff',
			),
			'transparent_header_home_btn_text_color' => array(
				'label'       		=> esc_html__( 'Transparent Header Button Text Color', 'bosa-crypto' ),
				'type'        		=> 'color',
				'default'     		=> '#ffffff',
			),
			'transparent_header_btn_bg_color' 	=> array(
				'label'       		=> esc_html__( 'Non Transparent Header Button Background Color', 'bosa-crypto' ),
				'description' 		=> esc_html__( 'For background button type only.', 'bosa-crypto' ),
				'type'        		=> 'color',
				'default'     		=> '#00D094',
			),
			'transparent_header_btn_border_color' 	=> array(
				'label'      	 		=> esc_html__( 'Non Transparent Header Button Border Color', 'bosa-crypto' ),
				'description' 			=> esc_html__( 'For border button type only.', 'bosa-crypto' ),
				'type'       		 	=> 'color',
				'default'     			=> '#1a1a1a',
			),
			'transparent_header_btn_text_color' => array(
				'label'       		=> esc_html__( 'Non Transparent Header Button Text Color', 'bosa-crypto' ),
				'type'        		=> 'color',
				'default'     		=> '#1a1a1a',
			),
			'transparent_header_btn_hover_color' => array(
				'label'       		=> esc_html__( 'Button Hover Color', 'bosa-crypto' ),
				'type'        		=> 'color',
				'default'     		=> '#0A4FD5',
			),
			'transparent_header_btn_text' => array(
				'label'       => esc_html__( 'Button Text', 'bosa-crypto' ),
				'type'        => 'text',
				'default' 	  => '',
			),
			'transparent_header_btn_link' => array(
				'label'       => esc_html__( 'Button Link', 'bosa-crypto' ),
				'type'        => 'text',
				'default' 	  => '',
			),
			'transparent_header_btn_target' => array(
				'label'       	=> esc_html__( 'Open Link in New Window', 'bosa-crypto' ),	
				'type'        	=> 'checkbox',
				'default'	  	=> true,
			),
			'transparent_header_btn_radius' => array(
				'label'       	=> esc_html__( 'Button Radius (px)', 'bosa-crypto' ),
				'type'        	=> 'number',
				'default'	  	=> 0,
				'choices'     	=> array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			),	
		),
		'choices' => array(
			'limit' => 1,
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_header_button',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Search', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_search_icon',
		'section'     => 'bosa_crypto_header_style_options',
		'default'     => false,
		'priority'	  => '420',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Hamburger Widget Menu Icon', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_hamburger_menu_icon',
		'section'     => 'bosa_crypto_header_style_options',
		'default'     => false,
		'priority'	  =>  '430',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Header Buttons Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_header_buttons_font_control',
		'section'      => 'bosa_crypto_header_style_options',
		'priority'	   => '440',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '600',
			'font-size'      => '14px',
			'text-transform' => 'none',
			'line-height'    => '1',
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.site-header .header-btn a',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_header_button',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two', 'header_fourteen' ),
			),
		),
	) );

	// Header contact labels typography for header 14

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Contact Label Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_contact_label_font_control',
		'section'      => 'bosa_crypto_header_style_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => 'regular',
			'font-size'      => '13px',
			'text-transform' => 'none',
			'line-height'    => '1.6',
		),
		'priority'	  => '450',
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => array( '.header-fourteen .bottom-contact a span' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_contact_detail',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_fourteen' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Contact Content Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_contact_content_font_control',
		'section'      => 'bosa_crypto_header_style_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '600',
			'font-size'      => '16px',
			'text-transform' => 'none',
			'line-height'    => '1.6',
		),
		'priority'	  => '460',
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => array( '.header-fourteen .bottom-contact a' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_contact_detail',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_fourteen' ),
			),
		),
	) );

	// Header Media Options
	Kirki::add_section( 'bosa_crypto_header_wrap_media_options', array(
	    'title'      => esc_html__( 'Media', 'bosa-crypto' ),
	    'panel'      => 'bosa_crypto_header_options',	   
	    'capability' => 'edit_theme_options',
	    'priority'   => '30',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Header Image Slider', 'bosa-crypto' ),
		'description' => esc_html__( 'Recommended image size 1920x550 pixel. Add only one image to make header banner.', 'bosa-crypto' ),
		'type'        => 'repeater',
		'section'     => 'bosa_crypto_header_wrap_media_options',
		'row_label' => array(
			'type'  => 'text',
		),
		'button_label' => esc_html__('Add New Image', 'bosa-crypto' ),
		'settings'     => 'bosa_crypto_header_image_slider',
		'default'      => array(
				array(
					'slider_item' 	=> '',
					)			
		),
		'fields' => array(
			'slider_item' => array(
				'label'       => esc_html__( 'Image', 'bosa-crypto' ),
				'type'        => 'image',
				'default'     => '',
				'choices'     => array(
					'save_as' => 'id',
				),
			)
		),
		'choices' => array(
			'limit' => 2,
		),
		'priority'	  =>  10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_header_image_size',
		'section'     => 'bosa_crypto_header_wrap_media_options',
		'default'     => 'full',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'	  =>  20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Background Image Size', 'bosa-crypto' ),
		'type'         => 'radio',
		'settings'     => 'bosa_crypto_header_image_size',
		'section'      => 'bosa_crypto_header_wrap_media_options',
		'default'      => 'cover',
		'choices'      => array(
			'cover'    => esc_html__( 'Cover', 'bosa-crypto' ),
			'pattern'  => esc_html__( 'Pattern / Repeat', 'bosa-crypto' ),
			'norepeat' => esc_html__( 'No Repeat', 'bosa-crypto' ),
		),
		'priority'	  =>  30,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Slide Effect', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_header_slider_effect',
		'section'     => 'bosa_crypto_header_wrap_media_options',
		'default'     => 'fade',
		'choices'  => array(
			'fade'             => esc_html__( 'Fade', 'bosa-crypto' ),
			'horizontal-slide' => esc_html__( 'Slide', 'bosa-crypto' ),
		),
		'priority'	  =>  40,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Fade Control Time ( in sec )', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_slider_header_fade_control',
		'section'      => 'bosa_crypto_header_wrap_media_options',
		'default'      => 5,
		'choices' => array(
				'min' => '3',
				'max' => '60',
				'step'=> '1',
		),
		'priority'	  =>  50,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_slider_effect',
				'operator' => '==',
				'value'    => 'fade',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Arrows', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_header_slider_arrows',
		'section'      => 'bosa_crypto_header_wrap_media_options',
		'default'      => false,
		'priority'	  =>  60,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Dots', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_header_slider_dots',
		'section'      => 'bosa_crypto_header_wrap_media_options',
		'default'      => false,
		'priority'	  =>  70,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Auto Play', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_header_slider_autoplay',
		'section'      => 'bosa_crypto_header_wrap_media_options',
		'default'      => true,
		'priority'	  =>  80,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Auto Play Timeout ( in sec )', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_slider_header_autoplay_speed',
		'section'      => 'bosa_crypto_header_wrap_media_options',
		'default'      => 4,
		'choices' => array(
				'min' => '1',
				'max' => '60',
				'step'=> '1',
		),
		'priority'	  =>  90,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_header_slider_autoplay',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Parallax Scrolling', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_parallax_scrolling',
		'section'     => 'bosa_crypto_header_wrap_media_options',
		'default'     => true,
		'priority'	  =>  100,
	) );

	// Header Elements Options
	Kirki::add_section( 'bosa_crypto_header_elements_options', array(
	    'title'      => esc_html__( 'Elements', 'bosa-crypto' ),
	    'panel'      => 'bosa_crypto_header_options',	   
	    'capability' => 'edit_theme_options',
	    'priority'   => '30',
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_fixed_header_separator',
	    'section'     => 'bosa_crypto_header_elements_options',
	    'default'     => esc_html__( 'Fixed Header Options', 'bosa-crypto' ),
	    'priority'	  =>  10,
	) );
	
	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Fixed Header', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_fixed_header',
		'section'     => 'bosa_crypto_header_elements_options',
		'default'     => true,
		'priority'	  =>  20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Logo', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_fixed_header_logo',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => false,
		'priority'	  =>  30,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Separate Logo for Fixed Header', 'bosa-crypto' ),
		'description'  => esc_html__( 'Image dimensions 320 by 120 pixels is recommended. It will change in fixed header only.', 'bosa-crypto' ),
		'type'         => 'image',
		'settings'     => 'bosa_crypto_fixed_header_separate_logo',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => '',
		'choices'     => array(
			'save_as' => 'id',
		),
		'priority'	  =>  40,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header_logo',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two' ),
			),
		),

	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Logo Image Width', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_fixed_header_logo_width',
		'section'      => 'bosa_crypto_header_elements_options',
		'transport'    => 'postMessage',
		'default'      => 270,
		'choices'      => array(
			'min'  => 50,
			'max'  => 270,
			'step' => 5,
		),
		'priority'	  =>  50,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header_logo',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Site Title', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_fixed_header_site_title',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => false,
		'priority'	  =>  60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_site_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Fixed Header Site Title Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_site_title_color_fixed_header',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => '',
		'priority'	  =>  70,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header_site_title',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_site_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Site Tagline', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_fixed_header_site_tagline',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => false,
		'priority'	  =>  80,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_site_tagline',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Fixed Header Site Tagline Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_site_tagline_color_fixed_header',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => '',
		'priority'	  =>  90,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header_site_tagline',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_site_tagline',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Fixed Header Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_bg_color_fixed_header',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => '',
		'priority'	  =>  100,	
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Fixed Header Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_text_color_fixed_header',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => '',
		'priority'	  =>  110,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Fixed Header Text Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_text_hover_color_fixed_header',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => '',
		'priority'	  =>  120,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Fixed Header Button Background Color', 'bosa-crypto' ),
		'description'  => esc_html__( 'For background button type only.', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_fixed_header_button_background_color',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => '',
		'priority'	  =>  130,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_header_button',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Fixed Header Button Border Color', 'bosa-crypto' ),
		'description'  => esc_html__( 'For border button type only.', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_fixed_header_button_border_color',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => '',
		'priority'	  =>  140,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_header_button',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Fixed Header Button Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_fixed_header_button_text_color',
		'section'      => 'bosa_crypto_header_elements_options',
		'default'      => '',
		'priority'	  =>  150,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_header_button',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	// Responsive
	Kirki::add_section( 'bosa_crypto_header_responsive', array(
	    'title'      => esc_html__( 'Responsive', 'bosa-crypto' ),
	    'description'    => esc_html__( 'These options will only apply to Tablet and Mobile devices. Please
	    	click on below Tablet or Mobile Icons to see changes.', 'bosa-crypto' ),
	    'capability' => 'edit_theme_options',
	    'priority'   => '80',
	    'panel'      => 'bosa_crypto_header_options',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Top Header Menu Section', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_mobile_top_header',
		'section'      => 'bosa_crypto_header_responsive',
		'default'      => false,
		'priority'	   =>  10,
	) );

	Kirki::add_field( 'bosa-crypto', array(	
		'label'       => esc_html__( 'Top Header Bar Name', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_top_bar_name',
		'section'     => 'bosa_crypto_header_responsive',
		'default'     => esc_html__( 'TOP MENU', 'bosa-crypto' ),
		'priority'	  =>  20,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_mobile_top_header',
				'operator' => '=',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Header Menu Text', 'bosa-crypto' ),
		'type'         => 'text',
		'settings'     => 'bosa_crypto_responsive_header_menu_text',
		'section'      => 'bosa_crypto_header_responsive',
		'default'      => esc_html__( 'MENU', 'bosa-crypto' ),
		'priority'	   =>  30,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Top Header Section Border', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_mobile_top_header_border',
		'section'      => 'bosa_crypto_header_responsive',
		'default'      => false,
		'priority'	   =>  40,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Mid Header Section Border', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_mobile_mid_header_border',
		'section'      => 'bosa_crypto_header_responsive',
		'default'      => false,
		'priority'	   =>  50,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_three', 'header_fourteen' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Fixed Header', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_mobile_fixed_header',
		'section'     => 'bosa_crypto_header_responsive',
		'default'     => true,
		'priority'	  =>  60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_fixed_header',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Header Secondary Menu', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_secondary_menu',
		'section'     => 'bosa_crypto_header_responsive',
		'default'     => false,
		'priority'	  =>  70,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_three' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_mobile_top_header',
				'operator' => '=',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Header Contact Details', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_mobile_contact_details',
		'section'     => 'bosa_crypto_header_responsive',
		'default'     => false,
		'priority'	  =>  80,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_contact_detail',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two', 'header_fourteen' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_mobile_top_header',
				'operator' => '=',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Header Search', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_mobile_search_icon',
		'section'     => 'bosa_crypto_header_responsive',
		'default'     => false,
		'priority'	  =>  90,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_search_icon',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_disable_mobile_top_header',
				'operator' => '=',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Header Buttons', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_mobile_header_buttons',
		'section'     => 'bosa_crypto_header_responsive',
		'default'     => false,
		'priority'	  =>  100,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_header_button',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => 'contains',
				'value'    => array( 'header_one', 'header_two' , 'header_fourteen'),
			),
			array(
				'setting'  => 'bosa_crypto_disable_mobile_top_header',
				'operator' => '=',
				'value'    => false,
			),
		),
	) );


	// Theme Skin Options
	Kirki::add_section( 'bosa_crypto_skins_options', array(
	    'title'      => esc_html__( 'Site Skins', 'bosa-crypto' ),
	    'description' => esc_html__( 'All color options except primary color will be overridden by the theme in dark and B&W skin.', 'bosa-crypto' ),
	    'capability' => 'edit_theme_options',
	    'priority'   => '80',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Select Theme Skin', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_skin_select',
		'section'     => 'bosa_crypto_skins_options',
		'default'     => 'default',
		'priority'	  =>  10,
		'choices'  => array(
			'default'    => esc_html__( 'Default', 'bosa-crypto' ),
			'dark'       => esc_html__( 'Dark', 'bosa-crypto' ),
			'blackwhite' => esc_html__( 'Black & White', 'bosa-crypto' ),
		)
	) );

	// Social Media Options
	Kirki::add_panel( 'bosa_crypto_social_media_options', array(
	    'title'          => esc_html__( 'Social Media', 'bosa-crypto' ),
	    'priority'       => '96',
	) );

	Kirki::add_section( 'bosa_crypto_social_media_elements_options', array(
	    'title'          => esc_html__( 'Elements', 'bosa-crypto' ),
	    'capability'     => 'edit_theme_options',
	    'priority'       => '10',
	    'panel'			 => 'bosa_crypto_social_media_options',		
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable from Header', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_header_social_links',
		'section'      => 'bosa_crypto_social_media_elements_options',
		'default'      => false,
		'priority'	   =>  10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable from Footer', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_footer_social_links',
		'section'      => 'bosa_crypto_social_media_elements_options',
		'default'      => false,
		'priority'	   =>  20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Footer Social Icons Size', 'bosa-crypto' ),
		'description' => esc_html__( 'Only applicable to the footer social icons.', 'bosa-crypto' ),
		'type'        => 'number',
		'settings'    => 'bosa_crypto_social_icons_size',
		'section'     => 'bosa_crypto_social_media_elements_options',
		'transport'   => 'postMessage',
		'default'     => 15,
		'choices'     => array(
			'min'  => 10,
			'max'  => 100,
			'step' => 1,
		),
		'priority'	  =>  30,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_footer_social_links',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Social Links', 'bosa-crypto' ),
		'type'        => 'repeater',
		'description' => esc_html__( 'By default, Social Icons will appear in both header and footer section.', 'bosa-crypto' ),
		'section'     => 'bosa_crypto_social_media_elements_options',
		'row_label' => array(
			'type'  => 'text',
			'value' => esc_html__( 'Social Link', 'bosa-crypto' ),
		),
		'settings' => 'bosa_crypto_social_media_links',
		'default' => array(
			array(
				'icon' 		=> '',
				'link' 		=> '',
				'target' 	=> true,
				),		
		),
		'fields' => array(
			'icon' => array(
				'label'       => esc_html__( 'Fontawesome Icon', 'bosa-crypto' ),
				'type'        => 'text',
				'description' => esc_html__( 'Input Icon name. For Example:- fab fa-facebook For more icons https://fontawesome.com/icons?d=gallery&m=free', 'bosa-crypto' ),
			),
			'link' => array(
				'label'       => esc_html__( 'Link', 'bosa-crypto' ),
				'type'        => 'text',
			),
			'target' => array(
				'label'       => esc_html__( 'Open Link in New Window', 'bosa-crypto' ),
				'type'        => 'checkbox',
				'default' 	  => true,
			),			
		),
		'choices' => array(
			'limit' => 20,
		),
		'priority' =>  40,
	) );

	// Responsive
	Kirki::add_section( 'bosa_crypto_social_responsive', array(
	    'title'          => esc_html__( 'Responsive', 'bosa-crypto' ),
	    'description'    => esc_html__( 'These options will only apply to Tablet and Mobile devices. Please
	    	click on below Tablet or Mobile Icons to see changes.', 'bosa-crypto' ),
	    'capability'     => 'edit_theme_options',
	    'priority'       => '20',
	    'panel'			 => 'bosa_crypto_social_media_options',		
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Social Icons from Header', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_mobile_social_icons_header',
		'section'     => 'bosa_crypto_social_responsive',
		'default'     => false,
		'priority'	  =>  10,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_header_social_links',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Social Icons from Footer', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_mobile_social_icons_footer',
		'section'     => 'bosa_crypto_social_responsive',
		'default'     => false,
		'priority'	  =>  20,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_footer_social_links',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	//Typography Options
	Kirki::add_section( 'bosa_crypto_typography', array(
	    'title'          => esc_html__( 'Typography', 'bosa-crypto' ),
	    'capability'     => 'edit_theme_options',
	    'priority'       => '95',
	    'reset'          => 'typography',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Site Title', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_site_title_font_control',
		'section'      => 'bosa_crypto_typography',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '600',
			'font-size'      => '22px',
			'text-transform' => 'none',
		),
		'priority'	  =>  10,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.site-header .site-branding .site-title',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Site Description', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_site_description_font_control',
		'section'      => 'bosa_crypto_typography',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => 'regular',
			'font-size'      => '14px',
			'text-transform' => 'none',
		),
		'priority'	  =>  20,
		'transport'   => 'auto',
		'output'   => array(
			array(
				'element' => '.site-header .site-branding .site-description',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Main Menu', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_main_menu_font_control',
		'section'      => 'bosa_crypto_typography',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'font-size'      => '15px',
			'text-transform' => 'uppercase',
			'variant'        => '600',
			'line-height'    => '1.5',
		),
		'priority'	  =>  30,
		'transport'   => 'auto',
		'output'   => array(
			array(
				'element' => array( '.main-navigation ul.menu li a', '.slicknav_menu .slicknav_nav li a' )
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_main_menu_description_info',
	    'section'     => 'bosa_crypto_typography',
	    'default'     => esc_html__( 'Below Main Menu Description setting will work after enabling description section in the menu. Please check https://bosathemes.com/docs/bosa/how-to-setup/how-to-setup-menu-description Documentation for more information.', 'bosa-crypto' ),
	    'priority'	  =>  40,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Main Menu Description', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_main_menu_description_font_control',
		'section'      => 'bosa_crypto_typography',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'font-size'      => '11px',
			'text-transform' => 'none',
			'variant'        => 'regular',
			'line-height'    => '1.3',
		),
		'priority'	  =>  50,
		'transport'   => 'auto',
		'output'   => array(
			array(
				'element' => array( '.main-navigation .menu-description, .slicknav_menu .menu-description' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Body', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_body_font_control',
		'section'      => 'bosa_crypto_typography',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => 'regular',
			'font-size'      => '15px',
		),
		'priority'	  =>  60,
		'transport'   => 'auto',
		'output' => array( 
			array(
				'element' => 'body',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'General Title (H1 - H6)', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_general_title_font_control',
		'section'      => 'bosa_crypto_typography',
		'default'  => array(
			'font-family'    => 'Poppins',
			'text-transform' => 'none',
		),
		'priority'	  =>  70,
		'transport'   => 'auto',
		'output'   => array(
			array(
				'element' => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ),
			),
		),	
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Page & Single Post Title', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_page_title_font_control',
		'section'      => 'bosa_crypto_typography',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '600',
			'font-size'      => '48px',
			'text-transform' => 'none',
		),
		'priority'	  =>  80,
		'transport'   => 'auto',
		'output'   => array(
			array(
				'element' => array( '.page-title' ),
			),
		),
	) );

	// Site Layouts Options
	Kirki::add_panel( 'bosa_crypto_site_layout_options', array(
	    'title' => esc_html__( 'Site Layouts', 'bosa-crypto' ),
	    'priority' => '90',
	) );

	Kirki::add_section( 'bosa_crypto_site_layout_style_options', array(
	    'title'          => esc_html__( 'Style', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_site_layout_options',
	    'capability'     => 'edit_theme_options',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Site Layouts', 'bosa-crypto' ),
		'description' => esc_html__( 'Default / Box / Frame / Full / Extend', 'bosa-crypto' ),
		'type'        => 'radio-image',
		'settings'    => 'bosa_crypto_site_layout',
		'section'     => 'bosa_crypto_site_layout_style_options',
		'default'     => 'default',
		'choices'  => array(
			'default' => get_template_directory_uri() . '/assets/images/default-layout.png',
			'box'     => get_template_directory_uri() . '/assets/images/box-layout.png',
			'frame'   => get_template_directory_uri() . '/assets/images/frame-layout.png',
			'full'    => get_template_directory_uri() . '/assets/images/full-layout.png',
			'extend'  => get_template_directory_uri() . '/assets/images/extend-layout.png',
		),
		'priority'	  =>  10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_box_frame_background_color',
		'section'      => 'bosa_crypto_site_layout_style_options',
		'default'      => '',
		'priority'	   =>  20,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_site_layout',
				'operator' => 'contains',
				'value'    => array( 'box', 'frame' ),
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Background Image', 'bosa-crypto' ),
		'type'         => 'image',
		'settings'     => 'bosa_crypto_box_frame_background_image',
		'section'      => 'bosa_crypto_site_layout_style_options',
		'default'      => '',
		'choices'     => array(
			'save_as' => 'id',
		),
		'priority'	  =>  30,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_site_layout',
				'operator' => 'contains',
				'value'    => array( 'box', 'frame' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Background Image Size', 'bosa-crypto' ),
		'type'         => 'radio',
		'settings'     => 'bosa_crypto_box_frame_image_size',
		'section'      => 'bosa_crypto_site_layout_style_options',
		'default'      => 'cover',
		'choices'      => array(
			'cover'    => esc_html__( 'Cover', 'bosa-crypto' ),
			'pattern'  => esc_html__( 'Pattern / Repeat', 'bosa-crypto' ),
			'norepeat' => esc_html__( 'No Repeat', 'bosa-crypto' ),
		),
		'priority'	   =>  40,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_site_layout',
				'operator' => 'contains',
				'value'    => array( 'box', 'frame' ),
			),
		),
	) );

	Kirki::add_section( 'bosa_crypto_site_layout_elements_options', array(
	    'title'          => esc_html__( 'Elements', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_site_layout_options',
	    'capability'     => 'edit_theme_options',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Site Layouts (Box & Frame) Shadow', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_site_layout_shadow',
		'section'      => 'bosa_crypto_site_layout_elements_options',
		'default'      => false,
		'priority'	   =>  10,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_site_layout',
				'operator' => 'contains',
				'value'    => array( 'box', 'frame' ),
			),
		),
	) );

	// Sidebar Options
	Kirki::add_section( 'bosa_crypto_sidebar_options', array(
	    'title'          => esc_html__( 'Sidebar', 'bosa-crypto' ),
	    'capability'     => 'edit_theme_options',
	    'priority'       => '98',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Sidebar Layouts', 'bosa-crypto' ),
		'description' => esc_html__( 'Right / Left / Both / None', 'bosa-crypto' ),
		'type'        => 'radio-image',
		'settings'    => 'bosa_crypto_sidebar_settings',
		'section'     => 'bosa_crypto_sidebar_options',
		'default'     => 'right',
		'choices'  => array(
			'right'      => get_template_directory_uri() . '/assets/images/right-sidebar.png',
			'left'       => get_template_directory_uri() . '/assets/images/left-sidebar.png',
			'right-left' => get_template_directory_uri() . '/assets/images/right-left-sidebar.png',
			'no-sidebar' => get_template_directory_uri() . '/assets/images/no-sidebar.png',
		),
		'priority'	  =>  10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Widget Title Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_sidebar_widget_title_font_control',
		'section'      => 'bosa_crypto_sidebar_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '500',
			'font-size'      => '16px',
			'text-transform' => 'uppercase',
			'line-height'    => '1.4',
		),
		'priority'	  =>  20,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.sidebar .widget .widget-title',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_sidebar_settings',
				'operator' => 'contains',
				'value'    => array( 'right', 'left', 'right-left' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Sidebar Widget Title Border', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_sidebar_widget_title_border',
		'section'      => 'bosa_crypto_sidebar_options',
		'default'      => false,
		'priority'	   =>  30,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_sidebar_settings',
				'operator' => 'contains',
				'value'    => array( 'right', 'left', 'right-left' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Sticky Position', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_sticky_sidebar',
		'section'      => 'bosa_crypto_sidebar_options',
		'default'      => false,
		'priority'	   =>  40,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_sidebar_settings',
				'operator' => 'contains',
				'value'    => array( 'right', 'left', 'right-left' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Sidebar in Blog Page', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_sidebar_blog_page',
		'section'     => 'bosa_crypto_sidebar_options',
		'default'     => false,
		'priority'	  =>  50,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_sidebar_settings',
				'operator' => 'contains',
				'value'    => array( 'right', 'left', 'right-left' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Sidebar in Single Post', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_sidebar_single_post',
		'section'     => 'bosa_crypto_sidebar_options',
		'default'     => false,
		'priority'	  =>  60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_sidebar_settings',
				'operator' => 'contains',
				'value'    => array( 'right', 'left', 'right-left' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Sidebar in Page', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_sidebar_page',
		'section'     => 'bosa_crypto_sidebar_options',
		'default'     => true,
		'priority'	  =>  70,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_sidebar_settings',
				'operator' => 'contains',
				'value'    => array( 'right', 'left', 'right-left' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Sidebar in WooCommerce', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_sidebar_woocommerce_page',
		'section'     => 'bosa_crypto_sidebar_options',
		'default'     => false,
		'priority'	  =>  80,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_sidebar_settings',
				'operator' => 'contains',
				'value'    => array( 'right', 'left', 'right-left' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Sidebar in WooCommerce Shop', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_sidebar_woocommerce_shop',
		'section'     => 'bosa_crypto_sidebar_options',
		'default'     => false,
		'priority'	  =>  90,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_sidebar_settings',
				'operator' => 'contains',
				'value'    => array( 'right', 'left', 'right-left' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_sidebar_woocommerce_page',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Sidebar in WooCommerce Single Product', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_sidebar_woocommerce_single',
		'section'     => 'bosa_crypto_sidebar_options',
		'default'     => false,
		'priority'	  =>  100,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_sidebar_settings',
				'operator' => 'contains',
				'value'    => array( 'right', 'left', 'right-left' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_sidebar_woocommerce_page',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	// Footer Options
	Kirki::add_panel( 'bosa_crypto_footer_options', array(
	    'title' => esc_html__( 'Footer', 'bosa-crypto' ),
	    'priority' => '110',
	) );

	// Footer Widgets Options
	Kirki::add_section( 'bosa_crypto_footer_widgets_options', array(
	    'title'          => esc_html__( 'Footer Widgets', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_footer_options',
	    'capability'     => 'edit_theme_options',
	    'priority' 		 => 10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Footer Widget Area', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_footer_widget',
		'section'      => 'bosa_crypto_footer_widgets_options',
		'default'      => false,
		'priority'	   =>  10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Footer Widget Title Border', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_footer_widget_title_border',
		'section'      => 'bosa_crypto_footer_widgets_options',
		'default'      => false,
		'priority'	   =>  20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Footer Widget Item List Border ', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_footer_widget_list_item_border',
		'section'      => 'bosa_crypto_footer_widgets_options',
		'default'      => false,
		'priority'	   =>  30,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Widget Columns Layouts', 'bosa-crypto' ),
		'type'        => 'radio-image',
		'settings'    => 'bosa_crypto_footer_widget_layout',
		'section'     => 'bosa_crypto_footer_widgets_options',
		'default'     => 'footer_widget_layout_one',
		'choices'  => array(
			'footer_widget_layout_one'    => get_template_directory_uri() . '/assets/images/widget-layout-1.png',
			'footer_widget_layout_two'    => get_template_directory_uri() . '/assets/images/widget-layout-2.png',
			'footer_widget_layout_three'    => get_template_directory_uri() . '/assets/images/widget-layout-3.png',
			'footer_widget_layout_four' => get_template_directory_uri() . '/assets/images/widget-layout-4.png',
		),
		'priority'	   =>  40,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Footer Widget Area Top Padding(in px)', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_footer_widget_area_top_padding',
		'section'      => 'bosa_crypto_footer_widgets_options',
		'default'      => 0,
		'priority'	   =>  50,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Footer Widget Area Bottom Padding(in px)', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_footer_widget_area_bottom_padding',
		'section'      => 'bosa_crypto_footer_widgets_options',
		'default'      => 50,
		'priority'	   =>  60,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Section Background Color', 'bosa-crypto' ),
		'description'  => esc_html__( 'It can be used as a transparent background color over image.', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_top_footer_background_color',
		'section'      => 'bosa_crypto_footer_widgets_options',
		'default'      => '',
		'priority'	   =>  70,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Widget Title Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_top_footer_widget_title_color',
		'section'      => 'bosa_crypto_footer_widgets_options',
		'default'      => '#030303',
		'priority'	   =>  80,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Widgets Link Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_top_footer_widget_link_color',
		'section'      => 'bosa_crypto_footer_widgets_options',
		'default'      => '#656565',
		'priority'	   =>  90,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Widgets Content Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_top_footer_widget_content_color',
		'section'      => 'bosa_crypto_footer_widgets_options',
		'default'      => '#656565',
		'priority'	   =>  100,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Widgets Link Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_top_footer_widget_link_hover_color',
		'section'      => 'bosa_crypto_footer_widgets_options',
		'default'      => '#0A4FD5',
		'priority'	   =>  110,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Widget Title Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_footer_widget_title_font_control',
		'section'      => 'bosa_crypto_footer_widgets_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '500',
			'font-size'      => '18px',
			'text-transform' => 'none',
			'line-height'    => '1.4',
		),
		'priority'	  =>  120,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.site-footer .widget .widget-title',
			),
		),
	) );

	// Footer Style Options
	Kirki::add_section( 'bosa_crypto_footer_style_options', array(
	    'title'          => esc_html__( 'Style', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_footer_options',
	    'capability'     => 'edit_theme_options',
	    'priority' 		 => 20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Bottom Footer Area', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_bottom_footer',
		'section'      => 'bosa_crypto_footer_style_options',
		'default'      => false,
		'priority'	   => 10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Footer Layouts', 'bosa-crypto' ),
		'type'        => 'radio-image',
		'settings'    => 'bosa_crypto_footer_layout',
		'section'     => 'bosa_crypto_footer_style_options',
		'default'     => 'footer_one',
		'choices'  	  => apply_filters( 'bosa_crypto_footer_layout_filter', array(
			'footer_one'   => get_template_directory_uri() . '/assets/images/footer-layout-1.png',
			'footer_two'   => get_template_directory_uri() . '/assets/images/footer-layout-2.png',
			'footer_three' => get_template_directory_uri() . '/assets/images/footer-layout-3.png',
		) ),
		'priority'	   => 20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Bottom Footer Area Top Padding(in px)', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_bottom_footer_area_top_padding',
		'section'      => 'bosa_crypto_footer_style_options',
		'default'      => 30,
		'priority'	   => 30,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Bottom Footer Area Bottom Padding(in px)', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_bottom_footer_area_bottom_padding',
		'section'      => 'bosa_crypto_footer_style_options',
		'default'      => 30,
		'priority'	   => 40,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Background Color', 'bosa-crypto' ),
		'description'  => esc_html__( 'It can be used as a transparent background color over image.', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_bottom_footer_background_color',
		'section'      => 'bosa_crypto_footer_style_options',
		'default'      => '',
		'priority'	   => 50,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_bottom_footer_text_color',
		'section'      => 'bosa_crypto_footer_style_options',
		'default'      => '#656565',
		'priority'	   => 60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Text Link Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_bottom_footer_text_link_color',
		'section'      => 'bosa_crypto_footer_style_options',
		'default'      => '#383838',
		'priority'	   => 70,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Text Link Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_bottom_footer_text_link_hover_color',
		'section'      => 'bosa_crypto_footer_style_options',
		'default'      => '#0A4FD5',
		'priority'	   => 80,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Bottom Footer Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_footer_style_font_control',
		'section'      => 'bosa_crypto_footer_style_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '400',
			'font-size'      => '14px',
			'text-transform' => 'none',
			'line-height'    => '1.6',
		),
		'priority'	   => 90,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => array( '.site-footer .site-info', '.site-footer .footer-menu ul li a' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Select Image', 'bosa-crypto' ),
		'type'         => 'image',
		'settings'     => 'bosa_crypto_bottom_footer_image',
		'section'      => 'bosa_crypto_footer_style_options',
		'default'      => '',
		'choices'     => array(
			'save_as' => 'id',
		),
		'priority'	   => 100,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_footer_layout',
				'operator' => 'contains',
				'value'    => array( 'footer_one', 'footer_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'    => esc_html__( 'Image Link', 'bosa-crypto' ),
		'type'     => 'link',
		'settings' => 'bosa_crypto_bottom_footer_image_link',
		'section'  => 'bosa_crypto_footer_style_options',
		'default'  => '',
		'priority'	   => 110,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_footer_layout',
				'operator' => 'contains',
				'value'    => array( 'footer_one', 'footer_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'    => esc_html__( 'Image Target', 'bosa-crypto' ),
		'description' => esc_html__( 'If enabled, the page will be open in an another browser tab.', 'bosa-crypto' ),
		'type'     => 'checkbox',
		'settings' => 'bosa_crypto_bottom_footer_image_target',
		'section'  => 'bosa_crypto_footer_style_options',
		'default'  => true,
		'priority'	   => 120,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_footer_layout',
				'operator' => 'contains',
				'value'    => array( 'footer_one', 'footer_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Image Width', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_bottom_footer_image_width',
		'section'      => 'bosa_crypto_footer_style_options',
		'transport'    => 'postMessage',
		'default'      => 270,
		'choices'      => array(
			'min'  => 10,
			'max'  => 1140,
			'step' => 5,
		),
		'priority'	   => 130,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_footer_layout',
				'operator' => 'contains',
				'value'    => array( 'footer_one', 'footer_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_bottom_footer_image_size',
		'section'     => 'bosa_crypto_footer_style_options',
		'default'     => 'full',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'	   => 140,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_footer_layout',
				'operator' => 'contains',
				'value'    => array( 'footer_one', 'footer_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Footer Menu', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_footer_menu',
		'section'      => 'bosa_crypto_footer_style_options',
		'default'      => false,
		'priority'	   => 150,
	) );

	// Media Footer Options
	Kirki::add_section( 'bosa_crypto_media_footer_options', array(
	    'title'          => esc_html__( 'Media', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_footer_options',
	    'capability'     => 'edit_theme_options',
	    'priority' 		 => 30,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Select Background Image', 'bosa-crypto' ),
		'description' => esc_html__( 'Recommended image size 1920x550 pixel.', 'bosa-crypto' ),
		'type'        => 'image',
		'settings'    => 'bosa_crypto_footer_image',
		'section'     => 'bosa_crypto_media_footer_options',
		'default'      => '',
		'choices'     => array(
			'save_as' => 'id',
		),
		'priority'	  =>  10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_footer_image_size',
		'section'     => 'bosa_crypto_media_footer_options',
		'default'     => 'full',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'	  =>  20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Background Image Size', 'bosa-crypto' ),
		'type'         => 'radio',
		'settings'     => 'bosa_crypto_footer_image_size',
		'section'      => 'bosa_crypto_media_footer_options',
		'default'      => 'cover',
		'choices'      => array(
			'cover'    => esc_html__( 'Cover', 'bosa-crypto' ),
			'pattern'  => esc_html__( 'Pattern / Repeat', 'bosa-crypto' ),
			'norepeat' => esc_html__( 'No Repeat', 'bosa-crypto' ),
		),
		'priority'	   =>  30,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Parallax Scrolling', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_footer_parallax_scrolling',
		'section'     => 'bosa_crypto_media_footer_options',
		'default'     => true,
		'priority'	  =>  40,
	) );

	// Footer Elements Options
	Kirki::add_section( 'bosa_crypto_elements_footer_options', array(
	    'title'          => esc_html__( 'Elements', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_footer_options',
	    'capability'     => 'edit_theme_options',
	    'priority' 		 => 40,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Scroll to Top', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_scroll_top',
		'section'     => 'bosa_crypto_elements_footer_options',
		'default'     => false,
		'priority'	  =>  10,
	) );

	// Responsive
	Kirki::add_section( 'bosa_crypto_footer_responsive', array(
	    'title'          => esc_html__( 'Responsive', 'bosa-crypto' ),
	    'description'    => esc_html__( 'These options will only apply to Tablet and Mobile devices. Please
	    	click on below Tablet or Mobile Icons to see changes.', 'bosa-crypto' ),
	    'capability'     => 'edit_theme_options',
	    'panel'			 => 'bosa_crypto_footer_options',
	    'priority' 		 => 50,		
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Footer Widget Area', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_responsive_footer_widget',
		'section'     => 'bosa_crypto_footer_responsive',
		'default'     => false,
		'priority'	  =>  10,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_footer_widget',
				'operator' => '=',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Scroll Top', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_mobile_scroll_top',
		'section'     => 'bosa_crypto_footer_responsive',
		'default'     => true,
		'priority'	  =>  20,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_scroll_top',
				'operator' => '=',
				'value'    => false,
			),
		),
	) );

	// Blog Homepage Options

	// Main Banner / Post Slider 
	Kirki::add_section( 'bosa_crypto_main_slider_options', array(
	    'title'          => esc_html__( 'Banner / Post Slider', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_blog_homepage_options',
	    'capability'     => 'edit_theme_options',
	    'priority'       => '10',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Section', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_main_slider',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => false,
		'priority'	  =>  10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Slider / Banner', 'bosa-crypto' ),
		'type'        => 'radio-buttonset',
		'settings'    => 'bosa_crypto_main_slider_controls',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 'slider',
		'choices'  => array(
			'slider' => esc_html__( 'Slider', 'bosa-crypto' ),
			'banner' => esc_html__( 'Banner', 'bosa-crypto' ),

		),
		'priority'	  =>  20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Height (in px)', 'bosa-crypto' ),
		'description' => esc_html__( 'This option will only apply to Desktop. Please click on below Desktop Icon to see changes. Automatically adjust by theme default in the responsive devices.
		', 'bosa-crypto' ),
		'type'        => 'slider',
		'settings'    => 'bosa_crypto_main_slider_height',
		'section'     => 'bosa_crypto_main_slider_options',
		'transport'   => 'postMessage',
		'default'     => 550,
		'choices'     => array(
			'min'  => 50,
			'max'  => 1500,
			'step' => 10,
		),
		'priority'	  =>  30,
	) );

	// Slider settings
	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_slider_image_size',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 'bosa-crypto-1370-550',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'	  =>  40,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Category', 'bosa-crypto' ),
		'description' => esc_html__( 'Recent posts will show if any category is not chosen. Recommended posts containing feature images size with 1920x940 pixel.', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_slider_category',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => '',
		'placeholder' => esc_html__( 'Select category', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_post_categories(),
		'priority'	  =>  50,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Slider Layout', 'bosa-crypto' ),
		'description' => esc_html__( 'Select layout & scroll below to change its options', 'bosa-crypto' ),
		'type'        => 'radio-image',
		'settings'    => 'bosa_crypto_main_slider_layout',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 'main_slider_one',
		'choices'     => apply_filters( 'bosa_crypto_slider_layout_filter', array(
			'main_slider_one'    => get_template_directory_uri() . '/assets/images/slider-layout-1.png',
		) ),
		'priority'	  =>  60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'type'        => 'color',
		'label'       => esc_html__( 'Slider Background Color', 'bosa-crypto' ),
		'settings'    => 'bosa_crypto_background_color_main_slider',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => '',
		'priority'	  =>  70,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Title Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_slider_post_title_color',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => '#ffffff',
		'priority'	   =>  80,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_hide_slider_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Category Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_slider_post_category_color',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => '#ebebeb',
		'priority'	   =>  90,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_hide_slider_category',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Meta Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_slider_post_meta_color',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => '#ebebeb',
		'priority'	   =>  100,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				array(
					'setting'  => 'bosa_crypto_hide_slider_date',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_slider_author',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_slider_comment',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Meta Icon Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_slider_post_meta_icon_color',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => '#00D094',
		'priority'	   =>  110,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				array(
					'setting'  => 'bosa_crypto_hide_slider_date',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_slider_author',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_slider_comment',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_slider_post_text_color',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => '#ffffff',
		'priority'	   =>  120,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_hide_slider_excerpt',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'type'        => 'color',
		'label'       => esc_html__( 'Hover Color', 'bosa-crypto' ),
		'settings'    => 'bosa_crypto_separate_hover_color_for_main_slider',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => '#0A4FD5',
		'priority'	  =>  130,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Background Image Size', 'bosa-crypto' ),
		'type'         => 'radio',
		'settings'     => 'bosa_crypto_main_slider_image_size',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => 'cover',
		'choices'      => array(
			'cover'    => esc_html__( 'Cover', 'bosa-crypto' ),
			'pattern'  => esc_html__( 'Pattern / Repeat', 'bosa-crypto' ),
			'norepeat' => esc_html__( 'No Repeat', 'bosa-crypto' ),
		),
		'priority'	   =>  140,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Width Controls', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_slider_width_controls',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 'full',
		'choices'  => array(
			'full'   => esc_html__( 'Full', 'bosa-crypto' ),
			'boxed'  => esc_html__( 'Boxed', 'bosa-crypto' ),
		),
		'priority'	  =>  150,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Slide Effect', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_main_slider_effect',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 'fade',
		'choices'  => array(
			'fade'             => esc_html__( 'Fade', 'bosa-crypto' ),
			'horizontal-slide' => esc_html__( 'Slide', 'bosa-crypto' ),
		),
		'priority'	  =>  160,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Fade Control Time ( in sec )', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_slider_fade_control',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => 5,
		'choices' => array(
				'min' => '3',
				'max' => '60',
				'step'=> '1',
		),
		'priority'	   =>  170,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_main_slider_effect',
				'operator' => '==',
				'value'    => 'fade',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Content Alignment', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_main_slider_content_alignment',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 'center',
		'choices'  => array(
			'center' => esc_html__( 'Center', 'bosa-crypto' ),
			'left'   => esc_html__( 'Left', 'bosa-crypto' ),
			'right'  => esc_html__( 'Right', 'bosa-crypto' ),
		),
		'priority'	  =>  180,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Display Slider on', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_display_main_slider_on',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 'below_header',
		'choices'  => array(
			'below_header'            => esc_html__( 'Below Header', 'bosa-crypto' ),
			'below_featured_posts' => esc_html__( 'Below Featured Posts', 'bosa-crypto' ),
		),
		'priority'	  =>  190,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Arrows', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_slider_arrows',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => false,
		'priority'	   =>  200,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Dots', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_slider_dots',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => false,
		'priority'	   =>  210,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Auto Play', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_slider_autoplay',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => true,
		'priority'	   =>  220,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Auto Play Timeout ( in sec )', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_slider_autoplay_speed',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => 4,
		'choices' => array(
				'min' => '1',
				'max' => '60',
				'step'=> '1',
		),
		'priority'	   =>  230,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_disable_slider_autoplay',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post View Number', 'bosa-crypto' ),
		'description'  => esc_html__( 'Number of posts to show.', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_slider_posts_number',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => 6,
		'choices' => array(
				'min' => '1',
				'max' => '20',
				'step' => '1',
		),
		'priority'	   =>  240,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Title', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_slider_title',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => false,	
		'priority'	  =>  250,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Title Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_main_slider_title_font_control',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '600',
			'font-size'      => '50px',
			'text-transform' => 'uppercase',
			'line-height'    => '1.4',
		),
		'priority'	  =>  260,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-banner .banner-content .entry-title',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_hide_slider_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable category', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_slider_category',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => false,
		'priority'	  =>  270,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );	

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Category Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_main_slider_cat_font_control',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '400',
			'font-size'      => '15px',
			'text-transform' => 'uppercase',
			'line-height'    => '1.6',
		),
		'priority'	  =>  280,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-banner .banner-content .entry-header .cat-links a',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_hide_slider_category',
				'operator' => '==',
				'value'    => false,
				),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Date', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_slider_date',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => false,
		'priority'	  =>  290,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Author', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_slider_author',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => false,
		'priority'	  =>  300,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Comments Link', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_slider_comment',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => false,
		'priority'	  =>  310,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Meta Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_main_slider_meta_font_control',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '400',
			'font-size'      => '13px',
			'text-transform' => 'capitalize',
			'line-height'    => '1.6',
		),
		'priority'	  =>  320,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-banner .banner-content .entry-meta a',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				array(
				'setting'  => 'bosa_crypto_hide_slider_date',
				'operator' => '==',
				'value'    => false,
				),
				array(
				'setting'  => 'bosa_crypto_hide_slider_author',
				'operator' => '==',
				'value'    => false,
				),
				array(
				'setting'  => 'bosa_crypto_hide_slider_comment',
				'operator' => '==',
				'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Excerpt', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_slider_excerpt',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => false,
		'priority'	  =>  330,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Excerpt Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_main_slider_excerpt_font_control',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '400',
			'font-size'      => '15px',
			'text-transform' => 'initial',
			'line-height'    => '1.8',
		),
		'priority'	  =>  340,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-banner .banner-content .entry-text p',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_hide_slider_excerpt',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Excerpt Length', 'bosa-crypto' ),
		'type'        => 'number',
		'settings'    => 'bosa_crypto_slider_excerpt_length',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 25,
		'choices' => array(
			'min' => '5',
			'max' => '100',
			'step' => '5',
		),
		'priority'	  =>  350,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_slider_excerpt',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Slider Button', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_slider_button',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => false,
		'priority'	  =>  360,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Slider Button', 'bosa-crypto' ),
		'type'        => 'repeater',
		'settings'    => 'bosa_crypto_main_slider_button_repeater',
		'section'     => 'bosa_crypto_main_slider_options',
		'row_label' => array(
			'type'  => 'text',
			'value' => esc_html__( 'Button', 'bosa-crypto' ),
		),
		'default' => array(
			array(
				'slider_btn_type' 			=> 'button-outline',
				'slider_btn_bg_color' 		=> '#00D094',
				'slider_btn_border_color' 	=> '#ffffff',
				'slider_btn_text_color' 	=> '#ffffff',
				'slider_btn_hover_color' 	=> '#0A4FD5',
				'slider_btn_text' 			=> '',
				'slider_btn_radius' 		=> 0,
			),		
		),
		'fields' => array(
			'slider_btn_type' => array(
				'label'       => esc_html__( 'Button Type', 'bosa-crypto' ),
				'type'        => 'select',
				'default'     => 'button-outline',
				'choices'  => array(
					'button-primary' => esc_html__( 'Background Button', 'bosa-crypto' ),
					'button-outline' => esc_html__( 'Border Button', 'bosa-crypto' ),
					'button-text'    => esc_html__( 'Text Only Button', 'bosa-crypto' ),
				),
			),
			'slider_btn_bg_color' => array(
				'label'       => esc_html__( 'Button Background Color', 'bosa-crypto' ),
				'description' => esc_html__( 'For background button type only.', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#00D094',
			),
			'slider_btn_border_color' => array(
				'label'       => esc_html__( 'Button Border Color', 'bosa-crypto' ),
				'description' => esc_html__( 'For border button type only.', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#ffffff',
			),
			'slider_btn_text_color' => array(
				'label'       => esc_html__( 'Button Text Color', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#ffffff',
			),
			'slider_btn_hover_color' => array(
				'label'       => esc_html__( 'Button Hover Color', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#0A4FD5',
			),
			'slider_btn_text' => array(
				'label'       => esc_html__( 'Text', 'bosa-crypto' ),
				'type'        => 'text',
				'default'	  => '',
			),
			'slider_btn_radius' => array(
				'label'       => esc_html__( 'Button Radius (px)', 'bosa-crypto' ),
				'type'        => 'number',
				'default'	  => 0,
				'choices'     => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			),
		),
		'choices' => array(
			'limit' => 1,
		),
		'priority'	  =>  370,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_hide_slider_button',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Slider Button Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_main_slider_button_font_control',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '500',
			'font-size'      => '15px',
			'text-transform' => 'capitalize',
			'line-height'    => '1',
		),
		'priority'	  =>  380,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-banner .slide-inner .banner-content .button-container a',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'slider',
			),
			array(
				'setting'  => 'bosa_crypto_hide_slider_button',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	// Banner settings
	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_banner_image_size',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 'bosa-crypto-1920-550',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'	  =>  390,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'type'        => 'color',
		'label'       => esc_html__( 'Banner Background Color', 'bosa-crypto' ),
		'settings'    => 'bosa_crypto_background_color_main_banner',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => '',
		'priority'	  =>  400,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Title Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_banner_title_color',
		'section'	   => 'bosa_crypto_main_slider_options',
		'default'      => '#ffffff',
		'priority'	   =>  410,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
			array(
				'setting'  => 'bosa_crypto_disable_banner_title',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Subtitle Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_banner_subtitle_color',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => '#ffffff',
		'priority'	   =>  420,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
			array(
				'setting'  => 'bosa_crypto_disable_banner_subtitle',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Select Image', 'bosa-crypto' ),
		'description' => esc_html__( 'Recommended image size 1920x940 pixel.', 'bosa-crypto' ),
		'type'        => 'image',
		'settings'    => 'bosa_crypto_banner_image',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'	  => '',
		'priority'	  =>  430,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
		),
		'choices'     => array(
			'save_as' => 'id',
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Background Image Size', 'bosa-crypto' ),
		'type'         => 'radio',
		'settings'     => 'bosa_crypto_main_banner_image_size',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'      => 'cover',
		'choices'      => array(
			'cover'    => esc_html__( 'Cover', 'bosa-crypto' ),
			'pattern'  => esc_html__( 'Pattern / Repeat', 'bosa-crypto' ),
			'norepeat' => esc_html__( 'No Repeat', 'bosa-crypto' ),
		),
		'priority'	   =>  440,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Width Controls', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_banner_width_controls',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 'full',
		'choices'  => array(
			'full'   => esc_html__( 'Full', 'bosa-crypto' ),
			'boxed'  => esc_html__( 'Boxed', 'bosa-crypto' ),
		),
		'priority'	  =>  450,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Content Alignment', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_main_banner_content_alignment',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 'center',
		'choices'  => array(
			'center' => esc_html__( 'Center', 'bosa-crypto' ),
			'left'   => esc_html__( 'Left', 'bosa-crypto' ),
			'right'  => esc_html__( 'Right', 'bosa-crypto' ),
		),
		'priority'	  =>  460,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Display Banner on', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_display_banner_on',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => 'below_header',
		'choices'  => array(
			'below_header'            => esc_html__( 'Below Header', 'bosa-crypto' ),
			'below_featured_posts' => esc_html__( 'Below Featured Posts', 'bosa-crypto' ),
		),
		'priority'	  =>  470,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Title', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_banner_title',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => false,
		'priority'	  =>  480,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Title', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_banner_title',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => '',
		'priority'	  =>  490,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
			array(
				'setting'  => 'bosa_crypto_disable_banner_title',
				'operator' => '==',
				'value'    => false,
			),
		),
		'partial_refresh' => array(
			'bosa_crypto_banner_title' => array(
				'selector'        => '.banner_title',
				'render_callback' => 'bosa_crypto_get_banner_title',
			)
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Title Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_banner_title_font_control',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '600',
			'font-size'      => '50px',
			'text-transform' => 'uppercase',
			'line-height'    => '1.4',
		),
		'priority'	  =>  500,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-banner .banner-content .entry-title',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
			array(
				'setting'  => 'bosa_crypto_disable_banner_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Subtitle', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_banner_subtitle',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => false,
		'priority'	  =>  510,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Subtitle', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_banner_subtitle',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => '',
		'priority'	  =>  520,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
			array(
				'setting'  => 'bosa_crypto_disable_banner_subtitle',
				'operator' => '==',
				'value'    => false,
			),
		),
		'partial_refresh' => array(
			'bosa_crypto_banner_subtitle' => array(
				'selector'        => '.banner_subtitle',
				'render_callback' => 'bosa_crypto_get_banner_subtitle',
			)
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Subtitle Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_main_banner_subtitle_font_control',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '400',
			'font-size'      => '15px',
			'text-transform' => 'initial',
			'line-height'    => '1.8',
		),
		'priority'	  =>  530,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-banner .banner-content .entry-subtitle',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
			array(
				'setting'  => 'bosa_crypto_disable_banner_subtitle',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Banner Buttons', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_banner_buttons',
		'section'     => 'bosa_crypto_main_slider_options',
		'default'     => false,
		'priority'	  =>  540,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Banner Buttons', 'bosa-crypto' ),
		'type'        => 'repeater',
		'settings'    => 'bosa_crypto_main_banner_buttons_repeater',
		'section'     => 'bosa_crypto_main_slider_options',
		'row_label' => array(
			'type'  => 'text',
			'value' => esc_html__( 'Button', 'bosa-crypto' ),
		),
		'default' => array(
			array(
				'banner_btn_type' 			=> 'button-outline',
				'banner_btn_bg_color' 		=> '#00D094',
				'banner_btn_border_color' 	=> '#ffffff',
				'banner_btn_text_color' 	=> '#ffffff',
				'banner_btn_hover_color' 	=> '#0A4FD5',
				'banner_btn_text' 			=> '',
				'banner_btn_link' 			=> '',
				'banner_btn_target'			=> true,
				'banner_btn_radius' 		=> 0,
			),		
		),
		'fields' => array(
			'banner_btn_type' => array(
				'label'       => esc_html__( 'Button Type', 'bosa-crypto' ),
				'type'        => 'select',
				'default'     => 'button-outline',
				'choices'  => array(
					'button-primary' => esc_html__( 'Background Button', 'bosa-crypto' ),
					'button-outline' => esc_html__( 'Border Button', 'bosa-crypto' ),
					'button-text'    => esc_html__( 'Text Only Button', 'bosa-crypto' ),
				),
			),
			'banner_btn_bg_color' => array(
				'label'       => esc_html__( 'Button Background Color', 'bosa-crypto' ),
				'description' => esc_html__( 'For background button type only.', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#00D094',
			),
			'banner_btn_border_color' => array(
				'label'       => esc_html__( 'Button Border Color', 'bosa-crypto' ),
				'description' => esc_html__( 'For border button type only.', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#ffffff',
			),
			'banner_btn_text_color' => array(
				'label'       => esc_html__( 'Button Text Color', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#ffffff',
			),
			'banner_btn_hover_color' => array(
				'label'       => esc_html__( 'Button Hover Color', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#0A4FD5',
			),
			'banner_btn_text' => array(
				'label'       => esc_html__( 'Text', 'bosa-crypto' ),
				'type'        => 'text',
				'default'	  => '',
			),
			'banner_btn_link' => array(
				'label'       => esc_html__( 'Link', 'bosa-crypto' ),
				'type'        => 'text',
				'default'	  => '',	
			),
			'banner_btn_target' => array(
				'label'       => esc_html__( 'Open Link in New Window', 'bosa-crypto' ),
				'type'        => 'checkbox',
				'default'	  => true,	
			),
			'banner_btn_radius' => array(
				'label'       => esc_html__( 'Button Radius (px)', 'bosa-crypto' ),
				'type'        => 'number',
				'default'	  => 0,
				'choices'     => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			),
		),
		'choices' => array(
			'limit' => 1,
		),
		'priority'	  =>  550,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
			array(
				'setting'  => 'bosa_crypto_disable_banner_buttons',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Banner Button Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_main_banner_button_font_control',
		'section'      => 'bosa_crypto_main_slider_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '500',
			'font-size'      => '15px',
			'text-transform' => 'capitalize',
			'line-height'    => '1',
		),
		'priority'	  =>  560,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-banner .banner-content .button-container a',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_main_slider_controls',
				'operator' => '==',
				'value'    => 'banner',
			),
			array(
				'setting'  => 'bosa_crypto_disable_banner_buttons',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	// Featured Posts Options
	Kirki::add_section( 'bosa_crypto_feature_posts_options', array(
	    'title'          => esc_html__( 'Featured Posts', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_blog_homepage_options',
	    'capability'     => 'edit_theme_options',
	    'priority'       => '20',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Featured Posts Section', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_feature_posts_section',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'      => false,
		'priority'	   =>  10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Section Title', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_feature_posts_section_title',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'      => true,
		'priority'	   =>  20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Section Title', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_feature_posts_section_title',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => '',
		'priority'	  =>  30,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_feature_posts_section_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Section Title Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_feature_posts_section_title_font_control',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '600',
			'font-size'      => '24px',
			'text-transform' => 'none',
			'line-height'    => '1.2',
		),
		'priority'	  =>  40,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-feature-posts-area .section-title',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_feature_posts_section_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Section Description', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_feature_posts_section_description',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'      => true,
		'priority'	   =>  50,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Section Description', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_feature_posts_section_description',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => '',
		'priority'	  =>  60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_feature_posts_section_description',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Section Description Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_feature_posts_section_description_font_control',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => 'regular',
			'font-size'      => '16px',
			'text-transform' => 'none',
			'line-height'    => '1.8',
		),
		'priority'	  =>  70,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-feature-posts-area .section-title-wrap p',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_feature_posts_section_description',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Section Title and Description Alignment', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_feature_posts_section_title_desc_alignment',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => 'left',
		'choices'     => array(
			'left'	 	=> esc_html__( 'Left', 'bosa-crypto' ),
			'center'  	=> esc_html__( 'Center', 'bosa-crypto' ),   
			'right'		=> esc_html__( 'Right', 'bosa-crypto' ),
		),
		'priority'	  	  =>  80,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'bosa_crypto_disable_feature_posts_section_title',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_disable_feature_posts_section_description',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Section Layout', 'bosa-crypto' ),
		'description' => esc_html__( 'Select layout & scroll below to change its options', 'bosa-crypto' ),
		'type'        => 'radio-image',
		'settings'    => 'bosa_crypto_feature_posts_section_layouts',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => 'feature_one',
		'choices'     => apply_filters( 'bosa_crypto_feature_posts_section_layouts_filter', array(
			'feature_one'    => get_template_directory_uri() . '/assets/images/feature-post-layout-1.png',
		) ),
		'priority'	  =>  90,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Title Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_featured_post_title_color',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'      => '#FFFFFF',
		'priority'	   =>  100,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_disable_feature_posts_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Category Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_featured_post_category_bgcolor',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'      => '#00D094',
		'priority'	   =>  110,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_featured_posts_category',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Category Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_featured_post_category_color',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'      => '#FFFFFF',
		'priority'	   =>  120,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_hide_featured_posts_category',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Meta Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_featured_post_meta_color',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'      => '#FFFFFF',
		'priority'	   =>  130,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				array(
					'setting'  => 'bosa_crypto_hide_featured_posts_date',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_featured_posts_author',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_featured_posts_comment',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Meta Icon Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_featured_post_meta_icon_color',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'      => '#00D094',
		'priority'	   =>  140,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				array(
					'setting'  => 'bosa_crypto_hide_featured_posts_date',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_featured_posts_author',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_featured_posts_comment',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_featured_post_hover_color',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'      => '#0A4FD5',
		'priority'	   =>  150,
	) );


	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Columns', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_feature_posts_columns',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => 'three_columns',
		'placeholder' => esc_attr__( 'Select category', 'bosa-crypto' ),
		'choices'  => array(
			'one_column'    => esc_html__( '1 Column', 'bosa-crypto' ),
			'two_columns'   => esc_html__( '2 Columns', 'bosa-crypto' ),
			'three_columns' => esc_html__( '3 Columns', 'bosa-crypto' ),
			'four_columns'  => esc_html__( '4 Columns', 'bosa-crypto' ),
		),
		'priority'	   =>  160,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Category', 'bosa-crypto' ),
		'description' => esc_html__( 'Recent posts will show if any category is not chosen.', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_feature_posts_category',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => '',
		'placeholder' => esc_html__( 'Select category', 'bosa-crypto' ), 
		'choices'     => bosa_crypto_get_post_categories(),
		'priority'	   =>  170,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Featured Posts Overlay Opacity', 'bosa-crypto' ),
		'type'        => 'number',
		'settings'    => 'bosa_crypto_feature_posts_overlay_opacity',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => 4,
		'choices' => array(
			'min' => '0',
			'max' => '9',
			'step' => '1',
		),
		'priority'	   =>  180,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post View Number', 'bosa-crypto' ),
		'description'  => esc_html__( 'Number of posts to show.', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_feature_posts_posts_number',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'      => 6,
		'choices' => array(
			'min' => '1',
			'max' => '48',
			'step' => '1',
		),
		'priority'	   =>  190,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Height (in px)', 'bosa-crypto' ),
		'description'  => esc_html__( 'This option will only apply to Desktop. Please click on below Desktop Icon to see changes. Automatically adjust by theme default in the responsive devices.
		', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_feature_posts_height',
		'section'      => 'bosa_crypto_feature_posts_options',
		'transport'    => 'postMessage',
		'default'      => 250,
		'choices' => array(
			'min' => '100',
			'max' => '1200',
			'step' => '10',
		),
		'priority'	   =>  200,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_feature_post_image_size',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => 'bosa-crypto-420-300',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'	  =>  210,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Background Image Size', 'bosa-crypto' ),
		'type'         => 'radio',
		'settings'     => 'bosa_crypto_feature_posts_image_size',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'      => 'cover',
		'choices'      => array(
			'cover'    => esc_html__( 'Cover', 'bosa-crypto' ),
			'pattern'  => esc_html__( 'Pattern / Repeat', 'bosa-crypto' ),
			'norepeat' => esc_html__( 'No Repeat', 'bosa-crypto' ),
		),
		'priority'	   =>  220,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Posts Border Radius (px)', 'bosa-crypto' ),
		'type'        => 'slider',
		'settings'    => 'bosa_crypto_feature_posts_radius',
		'section'     => 'bosa_crypto_feature_posts_options',
		'transport'	  => 'postMessage', 
		'default'     =>  0,
		'choices'     => array(
			'min'  => 0,
			'max'  => 50,
			'step' => 1,
		),
		'priority'	   =>  230,
	) );

		Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Post Text Alignment', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_feature_posts_text_alignment',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => 'text-left',
		'choices'     => array(
			'text-left'	 	=> esc_html__( 'Left', 'bosa-crypto' ),
			'text-center'  	=> esc_html__( 'Center', 'bosa-crypto' ),   
			'text-right'	=> esc_html__( 'Right', 'bosa-crypto' ),
		),
		'priority'	   =>  240,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Post Content Alignment', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_feature_posts_title_alignment',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => 'align-bottom',
		'choices'     => array(
			'align-top'	 	=> esc_html__( 'Top', 'bosa-crypto' ),
			'align-center'  => esc_html__( 'Center', 'bosa-crypto' ),   
			'align-bottom'  => esc_html__( 'Bottom', 'bosa-crypto' ),
		),
		'priority'	   =>  250,
	) ); 

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Title', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_feature_posts_title',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => false,
		'priority'	  =>  260,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Title Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_feature_posts_font_control',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '500',
			'font-size'      => '18px',
			'text-transform' => 'uppercase',
			'line-height'    => '1.4',
		),
		'priority'	  =>  270,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.post .feature-posts-content .feature-posts-title',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_feature_posts_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Title Divider', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_feature_title_divider',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => false,
		'priority'	  =>  280,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_feature_posts_title',
				'operator' => '==',
				'value'    => false,
			),
			array(
				'setting'  => 'bosa_crypto_disable_feature_posts_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Posts category', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_featured_posts_category',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => false,
		'priority'	  =>  290,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Category Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_featured_posts_cat_font_control',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '400',
			'font-size'      => '13px',
			'text-transform' => 'capitalize',
			'line-height'    => '1',
		),
		'priority'	  =>  300,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.post .feature-posts-content .cat-links a',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_featured_posts_category',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Date', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_featured_posts_date',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => false,
		'priority'	  =>  310,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Author', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_featured_posts_author',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => false,
		'priority'	  =>  320,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Comment', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_featured_posts_comment',
		'section'     => 'bosa_crypto_feature_posts_options',
		'default'     => false,
		'priority'	  =>  330,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Meta Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_featured_posts_meta_font_control',
		'section'      => 'bosa_crypto_feature_posts_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '400',
			'font-size'      => '13px',
			'text-transform' => 'capitalize',
			'line-height'    => '1.6',
		),
		'priority'	  =>  340,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.post .feature-posts-content .entry-meta a',
			),
		),
		'active_callback' => array(
			array(
				array(
				'setting'  => 'bosa_crypto_hide_featured_posts_date',
				'operator' => '==',
				'value'    => false,
				),
				array(
				'setting'  => 'bosa_crypto_hide_featured_posts_author',
				'operator' => '==',
				'value'    => false,
				),
				array(
				'setting'  => 'bosa_crypto_hide_featured_posts_comment',
				'operator' => '==',
				'value'    => false,
				),
			),
		),
	) );

	// Latest Posts Options
	Kirki::add_section( 'bosa_crypto_latest_posts_options', array(
	    'title'          => esc_html__( 'Latest Posts', 'bosa-crypto' ),
	    'description'    => esc_html__( 'More options are available in Blog Page Section.', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_blog_homepage_options',
	    'capability'     => 'edit_theme_options',
	    'priority'       => '30',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Latest Posts Section From Homepage', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_latest_posts_section',
		'section'     => 'bosa_crypto_latest_posts_options',
		'default'     => false,
		'priority'	  =>  10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Section Title', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_latest_posts_section_title',
		'section'      => 'bosa_crypto_latest_posts_options',
		'default'      => true,
		'priority'	   =>  20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Section Title', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_latest_posts_section_title',
		'section'     => 'bosa_crypto_latest_posts_options',
		'default'     => '',
		'priority'	  =>  30,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_latest_posts_section_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Section Title Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_latest_posts_section_title_font_control',
		'section'      => 'bosa_crypto_latest_posts_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '600',
			'font-size'      => '24px',
			'text-transform' => 'none',
			'line-height'    => '1.2',
		),
		'priority'	  =>  40,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-post-area .section-title-wrap .section-title',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_latest_posts_section_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Section Description', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_latest_posts_section_description',
		'section'      => 'bosa_crypto_latest_posts_options',
		'default'      => true,
		'priority'	   =>  50,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Section Description', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_latest_posts_section_description',
		'section'     => 'bosa_crypto_latest_posts_options',
		'default'     => '',
		'priority'	  =>  60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_latest_posts_section_description',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Section Description Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_latest_posts_section_description_font_control',
		'section'      => 'bosa_crypto_latest_posts_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => 'regular',
			'font-size'      => '16px',
			'text-transform' => 'none',
			'line-height'    => '1.8',
		),
		'priority'	  =>  70,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-post-area .section-title-wrap p',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_latest_posts_section_description',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Section Title and Description Alignment', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_latest_posts_section_title_desc_alignment',
		'section'     => 'bosa_crypto_latest_posts_options',
		'default'     => 'left',
		'choices'     => array(
			'left'	 	=> esc_html__( 'Left', 'bosa-crypto' ),
			'center'  	=> esc_html__( 'Center', 'bosa-crypto' ),   
			'right'		=> esc_html__( 'Right', 'bosa-crypto' ),
		),
		'priority'	   =>  80,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'bosa_crypto_disable_latest_posts_section_title',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_disable_latest_posts_section_description',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Category', 'bosa-crypto' ),
		'description' => esc_html__( 'Recent posts will show if any category is not chosen.', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_latest_posts_category',
		'section'     => 'bosa_crypto_latest_posts_options',
		'default'     => '',
		'placeholder' => esc_html__( 'Select category', 'bosa-crypto' ), 
		'choices'     => bosa_crypto_get_post_categories(),
		'priority'	  =>  90,
	) );

	// Highlighted Posts Options
	Kirki::add_section( 'bosa_crypto_highlight_posts_options', array(
	    'title'          => esc_html__( 'Highlighted Posts', 'bosa-crypto' ),
	    'panel'          => 'bosa_crypto_blog_homepage_options',
	    'capability'     => 'edit_theme_options',
	    'priority'       => '40',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Highlighted Posts Section', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_highlight_posts_section',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => false,
		'priority'	   =>  10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Section Title', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_highlight_posts_section_title',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => true,
		'priority'	   =>  20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Section Title', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_highlight_posts_section_title',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => '',
		'priority'	  =>  30,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_highlight_posts_section_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Section Title Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_highlight_posts_section_title_font_control',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '600',
			'font-size'      => '24px',
			'text-transform' => 'none',
			'line-height'    => '1.2',
		),
		'priority'	  =>  40,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-highlight-post .section-title',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_highlight_posts_section_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Section Description', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_highlight_posts_section_description',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => true,
		'priority'	   =>  50,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Section Description', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_highlight_posts_section_description',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => '',
		'priority'	  =>  60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_highlight_posts_section_description',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Section Description Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_highlight_posts_section_description_font_control',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => 'regular',
			'font-size'      => '16px',
			'text-transform' => 'none',
			'line-height'    => '1.8',
		),
		'priority'	  =>  70,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.section-highlight-post .section-title-wrap p',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_highlight_posts_section_description',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Section Title and Description Alignment', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_highlight_posts_section_title_desc_alignment',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => 'left',
		'choices'     => array(
			'left'	 	=> esc_html__( 'Left', 'bosa-crypto' ),
			'center'  	=> esc_html__( 'Center', 'bosa-crypto' ),   
			'right'		=> esc_html__( 'Right', 'bosa-crypto' ),
		),
		'priority'	   => 80,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'bosa_crypto_disable_highlight_posts_section_title',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_disable_highlight_posts_section_description',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Section Layout', 'bosa-crypto' ),
		'description' => esc_html__( 'Select layout & scroll below to change its options', 'bosa-crypto' ),
		'type'        => 'radio-image',
		'settings'    => 'bosa_crypto_highlight_posts_section_layouts',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => 'highlighted_one',
		'choices'     => array(
			'highlighted_one'    => get_template_directory_uri() . '/assets/images/highlight-layout-1.png',
			'highlighted_two'    => get_template_directory_uri() . '/assets/images/highlight-layout-2.png',
		),
		'priority'	   =>  90,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Category', 'bosa-crypto' ),
		'description' => esc_html__( 'Recent posts will show if any category is not chosen.', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_highlight_posts_category',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => '',
		'placeholder' => esc_html__( 'Select category', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_post_categories(),
		'priority'	  =>  100,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_highlight_post_image_size',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => '',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'	  =>  110,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Title Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_highlight_post_title_color',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => '#030303',
		'priority'	   =>  120,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_hide_highlight_posts_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );
	
	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Category Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_highlight_post_category_bgcolor',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => '#00D094',
		'priority'	   =>  130,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_highlight_posts_section_layouts',
				'operator' => '==',
				'value'    => 'highlighted_one',
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_hide_highlight_posts_category',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Category Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_highlight_post_category_color',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => '#FFFFFF',
		'priority'	   =>  140,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_hide_highlight_posts_category',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Meta Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_highlight_post_meta_color',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => '#7a7a7a',
		'priority'	   =>  150,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				array(
					'setting'  => 'bosa_crypto_hide_highlight_posts_date',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_highlight_posts_author',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_highlight_posts_comment',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Meta Icon Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_highlight_post_meta_icon_color',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => '#00D094',
		'priority'	   =>  160,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'bosa_crypto_hide_highlight_posts_date',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_highlight_posts_author',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_highlight_posts_comment',
					'operator' => '==',
					'value'    => false,
				),
			),
		),

	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_highlight_post_hover_color',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => '#0A4FD5',
		'priority'	   =>  170,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Post Border Radius (px)', 'bosa-crypto' ),
		'type'        => 'slider',
		'settings'     => 'bosa_crypto_highlight_posts_radius',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'transport'   => 'postMessage',
		'default'      =>  0,
		'choices'     => array(
			'min'  => 0,
			'max'  => 50,
			'step' => 1,
		),
		'priority'	   =>  180,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Slider Columns', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_highlight_posts_slides_show',
 		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => 3,
		'priority'	   => 190,
		'choices' => array(
			'min' => '2',
			'max' => '4',
			'step'=> '1',
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Arrows', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_highlight_posts_arrows',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => false,
		'priority'	   => 200,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Dots', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_highlight_posts_dots',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => false,
		'priority'	   => 210,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Slider Auto Play', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_highlight_posts_autoplay',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => true,
		'priority'	   => 220,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Slider Auto Play Timeout ( in sec )', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_highlight_posts_autoplay_speed',
 		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => 4,
		'choices' => array(
			'min' => '1',
			'max' => '60',
			'step'=> '1',
		),
		'priority'	   => 230,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_highlight_posts_autoplay',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Slider Post View Number', 'bosa-crypto' ),
		'description'  => esc_html__( 'Number of posts to show.', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_highlight_posts_posts_number',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'      => 6,
		'choices' => array(
			'min' => '1',
			'max' => '20',
			'step' => '1',
		),
		'priority'	   => 240,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post category', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_highlight_posts_category',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => false,
		'priority'	  => 250,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Category Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_highlight_posts_cat_font_control',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '400',
			'font-size'      => '13px',
			'text-transform' => 'capitalize',
			'line-height'    => '1',
		),
		'priority'	  => 260,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.highlight-post-slider .post .cat-links a',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_highlight_posts_category',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Title', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_highlight_posts_title',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => false,
		'priority'	  => 270,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Title Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_highlight_posts_title_font_control',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '500',
			'font-size'      => '18px',
			'text-transform' => 'none',
			'line-height'    => '1.4',
		),
		'priority'	  => 280,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.highlight-post-slider .post .entry-content .entry-title',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_highlight_posts_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Date', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_highlight_posts_date',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => false,
		'priority'	  => 290,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Author', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_highlight_posts_author',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => false,
		'priority'	  => 300,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Comment', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_highlight_posts_comment',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => false,
		'priority'	  => 310,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Meta Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_highlight_posts_meta_font_control',
		'section'      => 'bosa_crypto_highlight_posts_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '400',
			'font-size'      => '13px',
			'text-transform' => 'capitalize',
			'line-height'    => '1.6',
		),
		'priority'	  => 320,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.highlight-post-slider .post .entry-meta a',
			),
		),
		'active_callback' => array(
			array(
				array(
				'setting'  => 'bosa_crypto_hide_highlight_posts_date',
				'operator' => '==',
				'value'    => false,
				),
				array(
				'setting'  => 'bosa_crypto_hide_highlight_posts_author',
				'operator' => '==',
				'value'    => false,
				),
				array(
				'setting'  => 'bosa_crypto_hide_highlight_posts_comment',
				'operator' => '==',
				'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Image', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_highlight_posts_image',
		'section'     => 'bosa_crypto_highlight_posts_options',
		'default'     => false,
		'priority'	  => 330,
	) );

	// Responsive
	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Main Slider / Banner', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_mobile_main_slider',
		'section'     => 'bosa_crypto_blog_page_responsive',
		'default'     => false,
		'priority'	  => 10,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_main_slider',
				'operator' => '=',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Featured Posts', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_mobile_feature_posts',
		'section'      => 'bosa_crypto_blog_page_responsive',
		'default'      => false,
		'priority'	   => 20,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_feature_posts_section',
				'operator' => '=',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Latest Posts', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_mobile_latest_posts',
		'section'      => 'bosa_crypto_blog_page_responsive',
		'default'      => false,
		'priority'	   => 30,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_latest_posts_section',
				'operator' => '=',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Highlighted Posts', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_mobile_highlight_posts',
		'section'      => 'bosa_crypto_blog_page_responsive',
		'default'      => false,
		'priority'	   => 40,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_disable_highlight_posts_section',
				'operator' => '=',
				'value'    => false,
			),
		),
	) );


	// Blog Page Options
    Kirki::add_panel( 'bosa_crypto_blog_page_options', array(
	    'title'          => esc_html__( 'Blog Page', 'bosa-crypto' ),
	    'priority'       => '130',
	) );

    // Blog Page Style Options
	Kirki::add_section( 'bosa_crypto_blog_page_style_options', array(
	    'title'      => esc_html__( 'Style', 'bosa-crypto' ),
	    'panel'      => 'bosa_crypto_blog_page_options',	   
	    'capability' => 'edit_theme_options',
	    'priority'   => '10',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Post Layouts', 'bosa-crypto' ),
		'description' => esc_html__( 'Grid / List / Single', 'bosa-crypto' ),
		'type'        => 'radio-image',
		'settings'    => 'bosa_crypto_archive_post_layout',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     => 'list',
		'choices'  	  => apply_filters( 'bosa_crypto_archive_post_layout_filter', array(
			'grid'           => get_template_directory_uri() . '/assets/images/grid-layout.png',
			'list'           => get_template_directory_uri() . '/assets/images/list-layout.png',
			'single'         => get_template_directory_uri() . '/assets/images/single-layout.png',
		) ),
		'priority'    => 10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Post View Number', 'bosa-crypto' ),
		'description' => esc_html__( 'Number of posts to show.', 'bosa-crypto' ),
		'type'        => 'number',
		'settings'    => 'bosa_crypto_archive_post_per_page',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     => 10,
		'choices'  => array(
			'min' => '0',
			'max' => '20',
			'step' => '1',
		),
		'priority'    => 20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_post_image_size',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     => '',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'    => 30,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Posts Border Radius (px)', 'bosa-crypto' ),
		'type'        => 'slider',
		'settings'    => 'bosa_crypto_latest_posts_radius',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     =>  0,
		'transport'   => 'postMessage',
		'choices'     => array(
			'min'  => 0,
			'max'  => 50,
			'step' => 1,
		),
		'priority'    => 40,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Title Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_blog_post_title_color',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'default'      => '#101010',
		'priority'     => 50,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_hide_post_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Category Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_blog_post_category_color',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'default'      => '#00D094',
		'priority'     => 60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_category',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Meta Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_blog_post_meta_color',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'default'      => '#7a7a7a',
		'priority'     => 70,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				array(
					'setting'  => 'bosa_crypto_hide_date',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_author',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_comment',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Meta Icon Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_blog_post_meta_icon_color',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'default'      => '#00D094',
		'priority'     => 80,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'bosa_crypto_hide_date',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_author',
					'operator' => '==',
					'value'    => false,
				),
				array(
					'setting'  => 'bosa_crypto_hide_comment',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_blog_post_text_color',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'default'      => '#333333',
		'priority'     => 90,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_hide_blog_page_excerpt',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Hover Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_blog_post_hover_color',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'default'      => '#0A4FD5',
		'priority'     => 100,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Title', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_post_title',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     => false,
		'priority'    => 110,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Title Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_blog_post_title_font_control',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '500',
			'font-size'      => '21px',
			'text-transform' => 'none',
			'line-height'    => '1.4',
		),
		'priority'    => 120,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '#primary article .entry-title',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_post_title',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Category', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_category',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     => false,
		'priority'    => 130,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Category Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_blog_post_cat_font_control',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '400',
			'font-size'      => '13px',
			'text-transform' => 'uppercase',
			'line-height'    => '1.6',
		),
		'priority'    => 140,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '#primary .post .entry-content .entry-header .cat-links a',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_category',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Date', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_date',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     => false,
		'priority'    => 150,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Author', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_author',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     => false,
		'priority'    => 160,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Comments Link', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_comment',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     => false,
		'priority'    => 170,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Meta Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_blog_post_meta_font_control',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'default'  => array(
			'font-family'    => 'Poppins',
			'variant'        => '400',
			'font-size'      => '13px',
			'text-transform' => 'capitalize',
			'line-height'    => '1.6',
		),
		'priority'    => 180,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '#primary .entry-meta',
			),
		),
		'active_callback' => array(
			array(
				array(
				'setting'  => 'bosa_crypto_hide_date',
				'operator' => '==',
				'value'    => false,
				),
				array(
				'setting'  => 'bosa_crypto_hide_author',
				'operator' => '==',
				'value'    => false,
				),
				array(
				'setting'  => 'bosa_crypto_hide_comment',
				'operator' => '==',
				'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Excerpt', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_blog_page_excerpt',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     => false,
		'priority'    => 190,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Excerpt Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_blog_post_excerpt_font_control',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '400',
			'font-size'      => '15px',
			'text-transform' => 'initial',
			'line-height'    => '1.8',
		),
		'priority'    => 200,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '#primary .entry-text p',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_blog_page_excerpt',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Excerpt Length', 'bosa-crypto' ),
		'description' => esc_html__( 'Select number of words to display in excerpt', 'bosa-crypto' ),
		'type'        => 'number',
		'settings'    => 'bosa_crypto_post_excerpt_length',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     => 15,
		'choices' => array(
			'min'  => '5',
			'max'  => '60',
			'step' => '5',
		),
		'priority'    => 210,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_blog_page_excerpt',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Post Button', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_post_button',
		'section'     => 'bosa_crypto_blog_page_style_options',
		'default'     => true,
		'priority'    => 220,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Button', 'bosa-crypto' ),
		'type'         => 'repeater',
		'settings'     => 'bosa_crypto_blog_page_button_repeater',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'row_label' => array(
			'type'  => 'text',
			'value' => esc_html__( 'Button', 'bosa-crypto' ),
		),
		'default' => array(
			array(
				'blog_btn_type' 		=> 'button-text',
				'blog_btn_bg_color'		=> '#00D094',
				'blog_btn_border_color'	=> '#1a1a1a',
				'blog_btn_text_color'	=> '#1a1a1a',
				'blog_btn_hover_color'	=> '#0A4FD5',
				'blog_btn_text' 		=> '',
				'blog_btn_radius'		=> 0,
			),		
		),
		'priority'    => 230,
		'fields' => array(
			'blog_btn_type' => array(
				'label'       => esc_html__( 'Button Type', 'bosa-crypto' ),
				'type'        => 'select',
				'default'     => 'button-text',
				'choices'  => array(
					'button-primary' => esc_html__( 'Background Button', 'bosa-crypto' ),
					'button-outline' => esc_html__( 'Border Button', 'bosa-crypto' ),
					'button-text'    => esc_html__( 'Text Only Button', 'bosa-crypto' ),
				),
			),
			'blog_btn_bg_color' => array(
				'label'       => esc_html__( 'Button Background Color', 'bosa-crypto' ),
				'description' => esc_html__( 'For background button type only.', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#00D094',
			),
			'blog_btn_border_color' => array(
				'label'       => esc_html__( 'Button Border Color', 'bosa-crypto' ),
				'description' => esc_html__( 'For border button type only.', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#1a1a1a',
			),
			'blog_btn_text_color' => array(
				'label'       => esc_html__( 'Button Text Color', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#1a1a1a',
			),
			'blog_btn_hover_color' => array(
				'label'       => esc_html__( 'Button Hover Color', 'bosa-crypto' ),
				'type'        => 'color',
				'default'     => '#0A4FD5',
			),
			'blog_btn_text' => array(
				'label'       => esc_html__( 'Button Text', 'bosa-crypto' ),
				'type'        => 'text',
				'default' 	  => '',
			),
			'blog_btn_radius' => array(
				'label'       => esc_html__( 'Button Radius (px)', 'bosa-crypto' ),
				'type'        => 'number',
				'default'	  => 0,
				'choices'     => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			),	
		),
		'choices' => array(
			'limit' => 1,
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_post_button',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Post Button Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_blog_post_button_font_control',
		'section'      => 'bosa_crypto_blog_page_style_options',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '600',
			'font-size'      => '14px',
			'text-transform' => 'capitalize',
			'line-height'    => '1.6',
		),
		'priority'    => 240,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '#primary .post .entry-text .button-container a',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_post_button',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	// Blog Page Elements Options
	Kirki::add_section( 'bosa_crypto_blog_page_elements_options', array(
	    'title'      => esc_html__( 'Elements', 'bosa-crypto' ),
	    'panel'      => 'bosa_crypto_blog_page_options',	   
	    'capability' => 'edit_theme_options',
	    'priority'   => '20',
	) );

	Kirki::add_field( 'bosa-crypto',  array(
		'label'       => esc_html__( 'Blog Archive Pages Title', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_disable_blog_page_title',
		'section'     => 'bosa_crypto_blog_page_elements_options',
		'default'     => 'enable_all_pages',
		'choices'     => array(
			'enable_all_pages'  => esc_html__( 'Enable in all', 'bosa-crypto' ),
			'disable_all_pages' => esc_html__( 'Disable from all', 'bosa-crypto' ),
		),
		'priority'    => 10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Pagination', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_pagination',
		'section'     => 'bosa_crypto_blog_page_elements_options',
		'default'     => false,
		'priority'    => 20,
	) );

	// Single Post Options
	Kirki::add_section( 'bosa_crypto_single_post_options', array(
	    'title'          => esc_html__( 'Single Post', 'bosa-crypto' ),
	    'capability'     => 'edit_theme_options',
	    'priority'       => '140',
	) );

	Kirki::add_field( 'bosa-crypto',  array(
		'label'       => esc_html__( 'Post Title', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_disable_single_post_title',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => 'enable_all_pages',
		'choices'     => array(
			'enable_all_pages'    => esc_html__( 'Enable in all', 'bosa-crypto' ),
			'disable_all_pages'   => esc_html__( 'Disable from all', 'bosa-crypto' ),
		),
		'priority'    => 10,
	) );

	Kirki::add_field( 'bosa-crypto',  array(
		'label'       => esc_html__( 'Post Title Position', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_post_title_position',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => 'above_feature_image',
		'choices'     => array(
			'below_feature_image' => esc_html__( 'Below Feature Image', 'bosa-crypto' ),
			'above_feature_image' => esc_html__( 'Top of the Page', 'bosa-crypto' ),
		),
		'priority'    => 20,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'bosa_crypto_disable_transparent_header_post',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'bosa_crypto_header_layout',
					'operator' => '!=',
					'value'    => 'header_two',
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Feature Image', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_single_feature_image',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => 'show_in_all_pages',
		'choices' => array(
			'show_in_all_pages'    => esc_html__( 'Show in all Pages', 'bosa-crypto' ),
			'disable_in_all_pages' => esc_html__( 'Disable in all Pages', 'bosa-crypto' ),
		),
		'priority'    => 30,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'bosa_crypto_disable_transparent_header_post',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'bosa_crypto_header_layout',
					'operator' => '!=',
					'value'    => 'header_two',
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_single_post_image_size',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => 'bosa-crypto-1370-550',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'    => 40,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'bosa_crypto_single_feature_image',
					'operator' => '==',
					'value'    => 'show_in_all_pages',
				),
				array(
					'setting'  => 'bosa_crypto_disable_transparent_header_post',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Transparent Header', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_transparent_header_post',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => true,
		'priority'    => 50,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Transparent Header Banner Height (in px)', 'bosa-crypto' ),
		'type'        => 'slider',
		'settings'    => 'bosa_crypto_transparent_header_banner_post_height',
		'section'     => 'bosa_crypto_single_post_options',
		'transport'   => 'postMessage',
		'default'     => 400,
		'choices'     => array(
			'min'  => 50,
			'max'  => 1500,
			'step' => 10,
		),
		'priority'    => 60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_transparent_header_post',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Header Banner Image Size', 'bosa-crypto' ),
		'type'         => 'radio',
		'settings'     => 'bosa_crypto_transparent_header_banner_post_size',
		'section'      => 'bosa_crypto_single_post_options',
		'default'      => 'cover',
		'choices'      => array(
			'cover'    => esc_html__( 'Cover', 'bosa-crypto' ),
			'pattern'  => esc_html__( 'Pattern / Repeat', 'bosa-crypto' ),
			'norepeat' => esc_html__( 'No Repeat', 'bosa-crypto' ),
		),
		'priority'    => 70,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_transparent_header_post',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Transparent Header Banner Overlay Opacity', 'bosa-crypto' ),
		'type'        => 'number',
		'settings'    => 'bosa_crypto_transparent_header_banner_post_opacity',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => 4,
		'choices' => array(
			'min' => '0',
			'max' => '9',
			'step' => '1',
		),
		'priority'    => 80,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_transparent_header_post',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Date', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_single_post_date',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => false,
		'priority'    => 90,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Comments Link', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_single_post_comment',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => false,
		'priority'    => 100,
	) );
	
	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable category', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_single_post_category',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => false,
		'priority'    => 110,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Tag Links', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_single_post_tag_links',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => false,
		'priority'    => 120,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Author', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_single_post_author',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => false,
		'priority'    => 130,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Author Section Title', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_single_post_author_title',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => esc_html__( 'About the Author', 'bosa-crypto' ),
		'priority'    => 140,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_single_post_author',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Related Posts', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_hide_related_posts',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => false,
		'priority'    => 150,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Related Posts Section Title', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_related_posts_title',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => esc_html__( 'You may also like these', 'bosa-crypto' ),
		'priority'    => 160,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_related_posts',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'description' => esc_html__( 'For related posts.', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_related_post_image_size',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => 'bosa-crypto-420-300',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'    => 170,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_related_posts',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Related Posts Items', 'bosa-crypto' ),
		'description' => esc_html__( 'Total number of related posts to show.', 'bosa-crypto' ),
		'type'        => 'number',
		'settings'    => 'bosa_crypto_related_posts_count',
		'section'     => 'bosa_crypto_single_post_options',
		'default'     => 4,
		'choices' => array(
			'min' => '1',
			'max' => '12',
			'step' => '1',
		),
		'priority'    => 180,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_hide_related_posts',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	// Pages Options
	Kirki::add_section( 'bosa_crypto_pages_options', array(
	    'title'          => esc_html__( 'Pages', 'bosa-crypto' ),
	    'capability'     => 'edit_theme_options',
	    'priority'       => '150',
	) );

	Kirki::add_field( 'bosa-crypto',  array(
		'label'       => esc_html__( 'Page Title', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_disable_page_title',
		'section'     => 'bosa_crypto_pages_options',
		'default'     => 'disable_front_page',
		'choices'     => array(
			'disable_all_pages'   => esc_html__( 'Disable from all', 'bosa-crypto' ),
			'enable_all_pages'    => esc_html__( 'Enable in all', 'bosa-crypto' ),
			'disable_front_page'  => esc_html__( 'Disable from frontpage only', 'bosa-crypto' ),
		),
		'priority'    => 10,
	) );

	Kirki::add_field( 'bosa-crypto',  array(
		'label'       => esc_html__( 'Page Title Position', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_page_title_position',
		'section'     => 'bosa_crypto_pages_options',
		'default'     => 'above_feature_image',
		'choices'     => array(
			'below_feature_image' => esc_html__( 'Below Feature Image', 'bosa-crypto' ),
			'above_feature_image' => esc_html__( 'Top of the Page', 'bosa-crypto' ),
		),
		'priority'    => 20,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'bosa_crypto_disable_transparent_header_page',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'bosa_crypto_header_layout',
					'operator' => '!=',
					'value'    => 'header_two',
				),
			),
		),
	) );
	
	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Feature Image', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_page_feature_image',
		'section'     => 'bosa_crypto_pages_options',
		'default'     => 'show_in_all_pages',
		'choices' => array(
			'show_in_all_pages'    => esc_html__( 'Show in all Pages', 'bosa-crypto' ),
			'disable_in_all_pages' => esc_html__( 'Disable in all Pages', 'bosa-crypto' ),
			'disable_in_frontpage' => esc_html__( 'Disable in Frontpage only', 'bosa-crypto' ),
			'show_in_frontpage'    => esc_html__( 'Show in Frontpage only', 'bosa-crypto' ),
		),
		'priority'    => 30,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'bosa_crypto_disable_transparent_header_page',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'bosa_crypto_header_layout',
					'operator' => '!=',
					'value'    => 'header_two',
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_pages_image_size',
		'section'     => 'bosa_crypto_pages_options',
		'default'     => 'bosa-crypto-1370-550',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'    => 40,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'bosa_crypto_page_feature_image',
					'operator' => 'contains',
					'value'    => array( 'show_in_all_pages', 'show_in_frontpage', 'disable_in_frontpage' ),
				),
				array(
					'setting'  => 'bosa_crypto_disable_transparent_header_page',
					'operator' => '==',
					'value'    => false,
				),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Transparent Header', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_transparent_header_page',
		'section'     => 'bosa_crypto_pages_options',
		'default'     => true,
		'priority'    => 50,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Transparent Header Banner Height (in px)', 'bosa-crypto' ),
		'type'        => 'slider',
		'settings'    => 'bosa_crypto_transparent_header_banner_page_height',
		'section'     => 'bosa_crypto_pages_options',
		'transport'   => 'postMessage',
		'default'     => 400,
		'choices'     => array(
			'min'  => 50,
			'max'  => 1500,
			'step' => 10,
		),
		'priority'    => 60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_transparent_header_page',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Transparent Header Banner Image Size', 'bosa-crypto' ),
		'type'         => 'radio',
		'settings'     => 'bosa_crypto_transparent_header_banner_page_size',
		'section'      => 'bosa_crypto_pages_options',
		'default'      => 'cover',
		'choices'      => array(
			'cover'    => esc_html__( 'Cover', 'bosa-crypto' ),
			'pattern'  => esc_html__( 'Pattern / Repeat', 'bosa-crypto' ),
			'norepeat' => esc_html__( 'No Repeat', 'bosa-crypto' ),
		),
		'priority'    => 70,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_transparent_header_page',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Transparent Header Banner Overlay Opacity', 'bosa-crypto' ),
		'type'        => 'number',
		'settings'    => 'bosa_crypto_transparent_header_banner_page_opacity',
		'section'     => 'bosa_crypto_pages_options',
		'default'     => 4,
		'choices' => array(
			'min' => '0',
			'max' => '9',
			'step' => '1',
		),
		'priority'    => 80,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_header_layout',
				'operator' => '==',
				'value'    => 'header_two',
			),
			array(
				'setting'  => 'bosa_crypto_disable_transparent_header_page',
				'operator' => '==',
				'value'    => false,
			),
		),
	) );

	// 404 Error Page
	Kirki::add_section( 'bosa_crypto_error404_options', array(
	    'title'          => esc_html__( '404 Page', 'bosa-crypto' ),
	    'capability'     => 'edit_theme_options',
	    'priority'       => '160',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Image', 'bosa-crypto' ),
		'description' => esc_html__( 'Recommended image size 360x200 pixel.', 'bosa-crypto' ),
		'type'        => 'image',
		'settings'    => 'bosa_crypto_error404_image',
		'section'     => 'bosa_crypto_error404_options',
		'default'     => '',
		'choices'     => array(
			'save_as' => 'id',
		),
		'priority'    => 10,
	) );
	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Choose Image Size', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_render_error404_image_size',
		'section'     => 'bosa_crypto_error404_options',
		'default'     => 'full',
		'placeholder' => esc_html__( 'Select Image Size', 'bosa-crypto' ),
		'choices'     => bosa_crypto_get_intermediate_image_sizes(),
		'priority'    => 20,
	) );

	// Preloader Options
	Kirki::add_section( 'bosa_crypto_preloader_options', array(
	    'title'          => esc_html__( 'Preloader', 'bosa-crypto' ),
	    'capability'     => 'edit_theme_options',
	    'priority'       => '170',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Disable Preloading', 'bosa-crypto' ),
		'type'        => 'checkbox',
		'settings'    => 'bosa_crypto_disable_preloader',
		'section'     => 'bosa_crypto_preloader_options',
		'default'     => false,
		'priority'    => 10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Preloading Animations', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_preloader_animation',
		'section'     => 'bosa_crypto_preloader_options',
		'default'     => 'animation_one',
		'choices'  => array(
			'animation_one'       => esc_html__( 'Animation One', 'bosa-crypto' ),
			'animation_two'       => esc_html__( 'Animation Two', 'bosa-crypto' ),
			'animation_three'     => esc_html__( 'Animation Three', 'bosa-crypto' ),
			'animation_four'      => esc_html__( 'Animation Four', 'bosa-crypto' ),
			'animation_five'      => esc_html__( 'Animation Five', 'bosa-crypto' ),
		),
		'priority'    => 20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Image Width', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_preloader_custom_image_width',
		'section'      => 'bosa_crypto_preloader_options',
		'transport'    => 'postMessage',
		'default'      => 40,
		'choices'      => array(
			'min'  => 10,
			'max'  => 200,
			'step' => 1,
		),
		'priority'    => 30,
	) );


	// Breadcrumbs
	Kirki::add_section( 'bosa_crypto_breadcrumbs_options', array(
	    'title'          => esc_html__( 'Breadcrumbs', 'bosa-crypto' ),
	    'capability'     => 'edit_theme_options',
	    'priority'       => '180',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Breadcrumbs', 'bosa-crypto' ),
		'type'        => 'select',
		'settings'    => 'bosa_crypto_breadcrumbs_controls',
		'section'     => 'bosa_crypto_breadcrumbs_options',
		'default'     => 'show_in_all_page_post',
		'choices'  => array(
			'disable_in_all_pages'     => esc_html__( 'Disable in all Pages Only', 'bosa-crypto' ),
			'disable_in_all_page_post' => esc_html__( 'Disable in all Pages & Posts', 'bosa-crypto' ),
			'show_in_all_page_post'    => esc_html__( 'Show in all Pages & Posts', 'bosa-crypto' ),
		),
		'priority'    => 10,
	) );

	// WooCommerce

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'General / Style', 'bosa-crypto' ),
		'type'        => 'radio-buttonset',
		'settings'    => 'bosa_crypto_woocommerce_product_catalog_tabs',
		'section'     => 'woocommerce_product_catalog',
		'default'     => 'product_catalog_general_tab',
		'choices'  => array(
			'product_catalog_general_tab'     => esc_html__( 'General', 'bosa-crypto' ),
			'product_catalog_style_tab'     => esc_html__( 'Style', 'bosa-crypto' ),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Shop Page Title', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_shop_page_title',
		'section'      => 'woocommerce_product_catalog',
		'default'      => false,
		'priority'     => 10,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_product_card_separator',
	    'section'     => 'woocommerce_product_catalog',
	    'default'     => esc_html__( 'Product Wrapper Options', 'bosa-crypto' ),
	    'priority'    => 20,
	    'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'			=> esc_html__( 'Product Layout Type', 'bosa-crypto' ),
		'type'			=> 'radio-image',
		'settings'		=> 'bosa_crypto_woocommerce_product_layout_type',
		'section'		=> 'woocommerce_product_catalog',
		'default'		=> 'product_layout_grid',
		'choices'		=> array(
			'product_layout_grid'		=> get_template_directory_uri() . '/assets/images/product-layout-type-one.png',
			'product_layout_list'		=> get_template_directory_uri() . '/assets/images/product-layout-type-two.png',
		),
		'priority'      => 30,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'			=> esc_html__( 'Product Card Layout', 'bosa-crypto' ),
		'type'			=> 'radio-image',
		'settings'		=> 'bosa_crypto_woocommerce_product_card_layout',
		'section'		=> 'woocommerce_product_catalog',
		'default'		=> 'product_layout_one',
		'choices'		=> array(
			'product_layout_one'		=> get_template_directory_uri() . '/assets/images/product-card-layout-one.png',
			'product_layout_two'		=> get_template_directory_uri() . '/assets/images/product-card-layout-two.png',
		),
		'priority'      => 40,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'			=> esc_html__( 'Text Alignment', 'bosa-crypto' ),
		'type'			=> 'select',
		'settings'		=> 'bosa_crypto_woocommerce_product_card_text_alignment',
		'section'		=> 'woocommerce_product_catalog',
		'default'		=> 'text-center',
		'choices'		=> array(
			'text-left'		=> esc_html__( 'Left', 'bosa-crypto' ),
			'text-center'	=> esc_html__( 'Center', 'bosa-crypto' ),
			'text-right'	=> esc_html__( 'Right', 'bosa-crypto' ),
		),
		'priority'       => 50,
		'transport'		 => 'postMessage',
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Products Per Row', 'bosa-crypto' ),
		'description'  => esc_html__( 'How many products should be shown per row?', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_woocommerce_shop_product_column',
		'section'      => 'woocommerce_product_catalog',
		'default'      => 3,
		'choices' => array(
			'min' => '1',
			'max' => '4',
			'step'=> '1',
		),
		'priority'     => 60,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_layout_type',
				'operator' => '==',
				'value'    => 'product_layout_grid',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Products Per Row', 'bosa-crypto' ),
		'description'  => esc_html__( 'How many products should be shown per row?', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_woocommerce_shop_list_column',
		'section'      => 'woocommerce_product_catalog',
		'default'      => 2,
		'choices' => array(
			'min' => '1',
			'max' => '3',
			'step'=> '1',
		),
		'priority'     => 70,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_layout_type',
				'operator' => '==',
				'value'    => 'product_layout_list',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Product Display Per Page', 'bosa-crypto' ),
		'type'         => 'number',
		'settings'     => 'bosa_crypto_woocommerce_product_per_page',
		'section'      => 'woocommerce_product_catalog',
		'default'      => 9,
		'choices' => array(
			'min' => '1',
			'max' => '60',
			'step'=> '1',
		),
		'priority'    => 80,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Rating', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_shop_page_rating',
		'section'      => 'woocommerce_product_catalog',
		'default'      => false,
		'priority'     => 85,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_add_to_cart_separator',
	    'section'     => 'woocommerce_product_catalog',
	    'default'     => esc_html__( 'Add To Cart Button Options', 'bosa-crypto' ),
	    'priority'    => 90,
	    'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'			=> esc_html__( 'Add To Cart Button Layout', 'bosa-crypto' ),
		'type'			=> 'radio-image',
		'settings'		=> 'bosa_crypto_woocommerce_add_to_cart_button',
		'section'		=> 'woocommerce_product_catalog',
		'default'		=> 'cart_button_two',
		'choices'		=> array(
			'cart_button_one'		=> get_template_directory_uri() . '/assets/images/cart-button-one.png',
			'cart_button_two'		=> get_template_directory_uri() . '/assets/images/cart-button-two.png',
			'cart_button_three'		=> get_template_directory_uri() . '/assets/images/cart-button-three.png',
			'cart_button_four'		=> get_template_directory_uri() . '/assets/images/cart-button-four.png',
		),
		'priority'      => 100,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_icon_group_separator',
	    'section'     => 'woocommerce_product_catalog',
	    'default'     => esc_html__( 'Icon Group Options', 'bosa-crypto' ),
	    'priority'    => 110,
	    'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'			=> esc_html__( 'Icon Group Layout', 'bosa-crypto' ),
		'description'	=> esc_html__( 'Yith WooCommerce Compare, Wishlist and Quick View are grouped together. Install these plugins to use this option.', 'bosa-crypto' ),
		'type'			=> 'radio-image',
		'settings'		=> 'bosa_crypto_icon_group_layout',
		'section'		=> 'woocommerce_product_catalog',
		'default'		=> 'group_layout_one',
		'choices'		=> array(
			'group_layout_one'		=> get_template_directory_uri() . '/assets/images/iconbox-layout-one.png',
			'group_layout_two'		=> get_template_directory_uri() . '/assets/images/iconbox-layout-two.png',
			'group_layout_three'	=> get_template_directory_uri() . '/assets/images/iconbox-layout-three.png',
			'group_layout_four'		=> get_template_directory_uri() . '/assets/images/iconbox-layout-four.png',
		),
		'priority'      => 120,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_sale_tag_separator',
	    'section'     => 'woocommerce_product_catalog',
	    'default'     => esc_html__( 'Sale Tag Options', 'bosa-crypto' ),
	    'priority'    => 130,
	    'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'			=> esc_html__( 'Sale Tag Layout', 'bosa-crypto' ),
		'type'			=> 'radio-image',
		'settings'		=> 'bosa_crypto_woocommerce_sale_tag_layout',
		'section'		=> 'woocommerce_product_catalog',
		'default'		=> 'sale_tag_layout_one',
		'choices'		=> array(
			'sale_tag_layout_one'		=> get_template_directory_uri() . '/assets/images/product-badge-style-one.png',
			'sale_tag_layout_two'		=> get_template_directory_uri() . '/assets/images/product-badge-style-two.png',
		),
		'priority'      => 140,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
			array(
				'setting'  => 'bosa_crypto_icon_group_layout',
				'operator' => '!=',
				'value'    => 'group_layout_four',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Sale Badge Text', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_woocommerce_sale_badge_text',
		'section'     => 'woocommerce_product_catalog',
		'default'     => esc_html__( 'Sale!', 'bosa-crypto' ),
		'priority'    => 150,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Enable Sale Badge Percentage', 'bosa-crypto' ),
		'description' => esc_html__( 'Replaces sale badge text with sale percent.', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_enable_sale_badge_percent',
		'section'      => 'woocommerce_product_catalog',
		'default'      => false,
		'priority'     => 160,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'       => esc_html__( 'Sale Badge Percentage Text', 'bosa-crypto' ),
		'description' => esc_html__( 'Input text to accompany with percentage {value} tag. Example: {value}% OFF!', 'bosa-crypto' ),
		'type'        => 'text',
		'settings'    => 'bosa_crypto_woocommerce_sale_badge_percent',
		'section'     => 'woocommerce_product_catalog',
		'default'     => esc_html__( '-{value}%', 'bosa-crypto' ),
		'priority'    => 170,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_general_tab',
			),
			array(
				'setting'  => 'bosa_crypto_enable_sale_badge_percent',
				'operator' => '==',
				'value'    => true,
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_product_card_style_separator',
	    'section'     => 'woocommerce_product_catalog',
	    'default'     => esc_html__( 'Product Wrapper Options', 'bosa-crypto' ),
	    'priority'    => 210,
	    'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'			=> esc_html__( 'Product Card Style', 'bosa-crypto' ),
		'type'			=> 'radio-image',
		'settings'		=> 'bosa_crypto_woocommerce_product_card_style',
		'section'		=> 'woocommerce_product_catalog',
		'default'		=> 'card_style_one',
		'choices'		=> array(
			'card_style_one'		=> get_template_directory_uri() . '/assets/images/product-card-style-one.png',
			'card_style_two'		=> get_template_directory_uri() . '/assets/images/product-card-style-two.png',
			'card_style_three'		=> get_template_directory_uri() . '/assets/images/product-card-style-three.png',
		),
		'priority'    => 220,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Product Image Radius', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_shop_product_image_radius',
		'section'      => 'woocommerce_product_catalog',
		'transport'    => 'postMessage',
		'default'      => 0,
		'choices'      => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'priority'    => 230,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Product Card Border Radius', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_shop_product_card_radius',
		'section'      => 'woocommerce_product_catalog',
		'transport'    => 'postMessage',
		'default'      => 0,
		'choices'      => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'priority'    => 240,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_card_style',
				'operator' => 'contains',
				'value'    => array( 'card_style_two', 'card_style_three' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_add_to_cart_style_separator',
	    'section'     => 'woocommerce_product_catalog',
	    'default'     => esc_html__( 'Add To Cart Button Options', 'bosa-crypto' ),
	    'priority'    => 250,
	    'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Add to Cart Button Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_add_to_cart_bg_color',
		'section'      => 'woocommerce_product_catalog',
		'default'      => '#333333',
		'priority'    => 260,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_add_to_cart_button',
				'operator' => 'contains',
				'value'    => array( 'cart_button_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Add to Cart Button Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_add_to_cart_white_bg_color',
		'section'      => 'woocommerce_product_catalog',
		'default'      => '#ffffff',
		'priority'    => 260,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_add_to_cart_button',
				'operator' => 'contains',
				'value'    => array( 'cart_button_four' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Add to Cart Button Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_add_to_cart_text_color',
		'section'      => 'woocommerce_product_catalog',
		'default'      => '#ffffff',
		'priority'    => 270,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_add_to_cart_button',
				'operator' => 'contains',
				'value'    => array( 'cart_button_two' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Add to Cart Button Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_add_to_cart_black_text_color',
		'section'      => 'woocommerce_product_catalog',
		'default'      => '#333333',
		'priority'    => 270,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_add_to_cart_button',
				'operator' => 'contains',
				'value'    => array( 'cart_button_three' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Add to Cart Button Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_cart_four_black_text_color',
		'section'      => 'woocommerce_product_catalog',
		'default'      => '#333333',
		'priority'     => 270,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_add_to_cart_button',
				'operator' => 'contains',
				'value'    => array( 'cart_button_four' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Add to Cart Button Radius', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_add_cart_button_radius',
		'section'      => 'woocommerce_product_catalog',
		'transport'    => 'postMessage',
		'default'      => 0,
		'choices'      => array(
			'min'  => 0,
			'max'  => 50,
			'step' => 1,
		),
		'priority'    => 280,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_add_to_cart_button',
				'operator' => 'contains',
				'value'    => array( 'cart_button_two', 'cart_button_four' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Add to Cart Button Spacing', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_cart_four_diagonal_spacing',
		'section'      => 'woocommerce_product_catalog',
		'transport'    => 'postMessage',
		'default'      => 10,
		'choices'      => array(
			'min'  => 0,
			'max'  => 50,
			'step' => 1,
		),
		'priority'    => 290,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_add_to_cart_button',
				'operator' => 'contains',
				'value'    => array( 'cart_button_four' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_icon_group_style_separator',
	    'section'     => 'woocommerce_product_catalog',
	    'default'     => esc_html__( 'Icon Group Options', 'bosa-crypto' ),
	    'priority'    => 300,
	    'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Icon Group Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_icon_group_bg_color',
		'section'      => 'woocommerce_product_catalog',
		'default'      => '#ffffff',
		'priority'    => 303,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Icon Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_icon_group_text_color',
		'section'      => 'woocommerce_product_catalog',
		'default'      => '#383838',
		'priority'    => 305,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Icon Group Border Radius', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_icon_group_one_border_radius',
		'section'      => 'woocommerce_product_catalog',
		'transport'    => 'postMessage',
		'default'      => 100,
		'choices'      => array(
			'min'  => 0,
			'max'  => 100,
			'step' => 1,
		),
		'priority'    => 310,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_icon_group_layout',
				'operator' => '==',
				'value'    => 'group_layout_one',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Icon Group Border Radius', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_icon_group_two_border_radius',
		'section'      => 'woocommerce_product_catalog',
		'transport'    => 'postMessage',
		'default'      => 0,
		'choices'      => array(
			'min'  => 0,
			'max'  => 50,
			'step' => 1,
		),
		'priority'    => 310,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_icon_group_layout',
				'operator' => '==',
				'value'    => 'group_layout_two',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Icon Group Border Radius', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_icon_group_three_border_radius',
		'section'      => 'woocommerce_product_catalog',
		'transport'    => 'postMessage',
		'default'      => 0,
		'choices'      => array(
			'min'  => 0,
			'max'  => 50,
			'step' => 1,
		),
		'priority'    => 310,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_icon_group_layout',
				'operator' => '==',
				'value'    => 'group_layout_three',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Icon Group Border Radius', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_icon_group_four_border_radius',
		'section'      => 'woocommerce_product_catalog',
		'transport'    => 'postMessage',
		'default'      => 100,
		'choices'      => array(
			'min'  => 0,
			'max'  => 100,
			'step' => 1,
		),
		'priority'    => 310,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_icon_group_layout',
				'operator' => '==',
				'value'    => 'group_layout_four',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Icon Group Spacing', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_icon_group_diagonal_spacing',
		'section'      => 'woocommerce_product_catalog',
		'transport'    => 'postMessage',
		'default'      => 10,
		'choices'      => array(
			'min'  => 0,
			'max'  => 50,
			'step' => 1,
		),
		'priority'    => 320,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_icon_group_layout',
				'operator' => 'contains',
				'value'    => array( 'group_layout_three', 'group_layout_four' ),
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
	    'type'        => 'custom',
	    'settings'    => 'bosa_crypto_sale_tag_style_separator',
	    'section'     => 'woocommerce_product_catalog',
	    'default'     => esc_html__( 'Sale Tag Options', 'bosa-crypto' ),
	    'priority'    => 330,
	    'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Sale Tag Background Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_sale_tag_bg_color',
		'section'      => 'woocommerce_product_catalog',
		'default'      => '#00D094',
		'priority'    => 340,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Sale Tag Text Color', 'bosa-crypto' ),
		'type'         => 'color',
		'settings'     => 'bosa_crypto_sale_tag_text_color',
		'section'      => 'woocommerce_product_catalog',
		'default'      => '#ffffff',
		'priority'    => 350,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_skin_select',
				'operator' => 'contains',
				'value'    => array( 'default', 'blackwhite' ),
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Sale Button Border Radius', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_sale_button_border_radius',
		'section'      => 'woocommerce_product_catalog',
		'transport'    => 'postMessage',
		'default'      => 0,
		'choices'      => array(
			'min'  => 0,
			'max'  => 50,
			'step' => 1,
		),
		'priority'    => 360,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Sale Button Spacing', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_sale_button_diagonal_spacing',
		'section'      => 'woocommerce_product_catalog',
		'transport'    => 'postMessage',
		'default'      => 8,
		'choices'      => array(
			'min'  => 0,
			'max'  => 50,
			'step' => 1,
		),
		'priority'    => 370,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Product Title Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_shop_product_title_font_control',
		'section'      => 'woocommerce_product_catalog',
		'default'  => array(
			'font-family'     => 'Poppins',
			'variant'         => '400',
			'font-style'      => 'normal',
			'font-size'       => '15px',
			'text-transform'  => 'none',
			'line-height'     => '1.4',
			'letter-spacing'  => '0',
			'text-decoration' => 'none',
			'color'			  => '',
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.woocommerce ul.products li.product .woocommerce-loop-product__title',
			),
		),
		'priority'    => 380,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Product Price Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_shop_product_price_font_control',
		'section'      => 'woocommerce_product_catalog',
		'default'  => array(
			'font-family'     => 'Open Sans',
			'variant'         => '700',
			'font-style'      => 'normal',
			'font-size'       => '16px',
			'text-transform'  => 'none',
			'line-height'     => '1.3',
			'letter-spacing'  => '0',
			'text-decoration' => 'none',
			'color'			  => '',
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.woocommerce ul.products li.product .price .amount',
			),
		),
		'priority'    => 390,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
		),
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Add to Cart Button Typography', 'bosa-crypto' ),
		'type'         => 'typography',
		'settings'     => 'bosa_crypto_shop_cart_button_font_control',
		'section'      => 'woocommerce_product_catalog',
		'default'  => array(
			'font-family'     => 'Poppins',
			'variant'         => 'regular',
			'font-style'      => 'normal',
			'font-size'       => '14px',
			'text-transform'  => 'none',
			'line-height'     => '1.5',
			'letter-spacing'  => '0',
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element' => '.woocommerce .product-inner .button, .woocommerce .product-inner .added_to_cart',
			),
		),
		'priority'    => 400,
		'active_callback' => array(
			array(
				'setting'  => 'bosa_crypto_woocommerce_product_catalog_tabs',
				'operator' => '==',
				'value'    => 'product_catalog_style_tab',
			),
			array(
				'setting'  => 'bosa_crypto_woocommerce_add_to_cart_button',
				'operator' => '!=',
				'value'    => array( 'cart_button_four' ),
			),
		),
	) );

	Kirki::add_section( 'bosa_crypto_woocommerce_single_product', array(
	    'title'      => esc_html__( 'Single Products', 'bosa-crypto' ),
	    'panel'      => 'woocommerce',	   
	    'capability' => 'edit_theme_options',
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Image Zoom / Magnification', 'bosa-crypto' ),
		'description'  => esc_html__( 'Every slider step changes 10% zoom to the previous zoom level. For example: 1.1 zoom level is now 110% zoom.', 'bosa-crypto' ),
		'type'         => 'slider',
		'settings'     => 'bosa_crypto_single_product_iamge_magnify',
		'section'      => 'bosa_crypto_woocommerce_single_product',
		'default'      => 1,
		'choices'      => array(
			'min'  => 0,
			'max'  => 3,
			'step' => 0.1,
		),
		'priority'     => 10,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Single Product Page Title', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_single_product_title',
		'section'      => 'bosa_crypto_woocommerce_single_product',
		'default'      => true,
		'priority'     => 20,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Breadcrumbs', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_single_product_breadcrumbs',
		'section'      => 'bosa_crypto_woocommerce_single_product',
		'default'      => false,
		'priority'     => 30,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable SKU', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_single_product_sku',
		'section'      => 'bosa_crypto_woocommerce_single_product',
		'default'      => false,
		'priority'     => 40,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Category', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_single_product_category',
		'section'      => 'bosa_crypto_woocommerce_single_product',
		'default'      => false,
		'priority'     => 50,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Tags', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_single_product_tags',
		'section'      => 'bosa_crypto_woocommerce_single_product',
		'default'      => false,
		'priority'     => 60,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Product Tabs', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_single_product_tabs',
		'section'      => 'bosa_crypto_woocommerce_single_product',
		'default'      => false,
		'priority'     => 70,
	) );

	Kirki::add_field( 'bosa-crypto', array(
		'label'        => esc_html__( 'Disable Related Products', 'bosa-crypto' ),
		'type'         => 'checkbox',
		'settings'     => 'bosa_crypto_disable_single_product_related_products',
		'section'      => 'bosa_crypto_woocommerce_single_product',
		'default'      => false,
		'priority'     => 80,
	) );
}