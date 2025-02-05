<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bosa Crypto
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>

<?php if( !get_theme_mod( 'bosa_crypto_disable_preloader', false )): ?>
	<div id="site-preloader">
		<div class="preloader-content">
			<?php
				$bosa_crypto_src = '';
				if( get_theme_mod( 'bosa_crypto_preloader_animation', 'animation_one' ) == 'animation_one' ){
					$bosa_crypto_src = get_template_directory_uri() . '/assets/images/preloader1.gif';
				}elseif( get_theme_mod( 'bosa_crypto_preloader_animation', 'animation_one' ) == 'animation_two' ){
					$bosa_crypto_src = get_template_directory_uri() . '/assets/images/preloader2.gif';
				}elseif( get_theme_mod( 'bosa_crypto_preloader_animation', 'animation_one' ) == 'animation_three' ){
					$bosa_crypto_src = get_template_directory_uri() . '/assets/images/preloader3.gif';
				}elseif( get_theme_mod( 'bosa_crypto_preloader_animation', 'animation_one' ) == 'animation_four' ){
					$bosa_crypto_src = get_template_directory_uri() . '/assets/images/preloader4.gif';
				}elseif( get_theme_mod( 'bosa_crypto_preloader_animation', 'animation_one' ) == 'animation_five' ){
					$bosa_crypto_src = get_template_directory_uri() . '/assets/images/preloader5.gif';
				} 

				echo apply_filters( 'bosa_crypto_preloader', '<img src="'. esc_url( $bosa_crypto_src ) .'" alt="">'); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</div>
	</div>
<?php endif; ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'bosa-crypto' ); ?></a>

	<?php if( get_theme_mod( 'bosa_crypto_header_layout', 'header_one' ) == '' || get_theme_mod( 'bosa_crypto_header_layout', 'header_one' ) == 'header_one' ){
		get_template_part( 'template-parts/header/header', 'one' );
	}elseif( get_theme_mod( 'bosa_crypto_header_layout', 'header_one' ) == 'header_two' ){
		get_template_part( 'template-parts/header/header', 'two' );
	}elseif( get_theme_mod( 'bosa_crypto_header_layout', 'header_one' ) == 'header_three' ) {
		get_template_part( 'template-parts/header/header', 'three' );
	}elseif( get_theme_mod( 'bosa_crypto_header_layout', 'header_one' ) == 'header_fourteen' ) {
		get_template_part( 'template-parts/header/header', 'fourteen' );
	} ?>