<?php
/**
 * Bosa Crypto back compat functionality
 *
 * Prevents Bosa Crypto from running on WordPress versions prior to 5.0,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 5.0.
 *
 * @since Bosa Crypto 1.0.0
 */

/**
 * Prevent switching to Bosa Crypto on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since Bosa Crypto 1.0.0
 */
function bosa_crypto_switch_theme() {
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'bosa_crypto_upgrade_notice' );
}
add_action( 'after_switch_theme', 'bosa_crypto_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Bosa Crypto on WordPress versions prior to 5.0.
 *
 * @since Bosa Crypto 1.0.0
 * @global string $wp_version WordPress version.
 */
function bosa_crypto_upgrade_notice() {
	/* translators: %s - WordPress version*/
	$message = sprintf( esc_html__( 'Bosa Crypto requires at least WordPress version 5.0. You are running version %s. Please upgrade and try again.', 'bosa-crypto' ),  $GLOBALS['wp_version'] ) ;
	printf( '<div class="error"><p>%s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 5.0.
 *
 * @since Bosa Crypto 1.0.0
 *
 * @global string $wp_version WordPress version.
 */
function bosa_crypto_customize() {
	/* translators: %s - WordPress version*/
	wp_die( sprintf( esc_html__( 'Bosa Crypto requires at least WordPress version 5.0. You are running version %s. Please upgrade and try again.', 'bosa-crypto' ), esc_html($GLOBALS['wp_version'] ) ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'bosa_crypto_customize' );

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 5.0.
 *
 * @since Bosa Crypto 1.0.0
 * @global string $wp_version WordPress version.
 */
function bosa_crypto_preview() {
	if ( isset( $_GET['preview'] ) ) {
		/* translators: %s - WordPress version*/
		wp_die( sprintf( esc_html__( 'Bosa Crypto requires at least WordPress version 5.0. You are running version %s. Please upgrade and try again.', 'bosa-crypto' ), esc_html( $GLOBALS['wp_version'] ) ) );
	}
}
add_action( 'template_redirect', 'bosa_crypto_preview' );
