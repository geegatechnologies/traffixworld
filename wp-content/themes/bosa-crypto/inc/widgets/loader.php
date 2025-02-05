<?php
/**
* Load widget components
*
* @since Bosa Crypto 1.0.0
*/
// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require_once get_parent_theme_file_path( '/inc/widgets/class-base-widget.php' );
require_once get_parent_theme_file_path( '/inc/widgets/latest-posts.php' );
require_once get_parent_theme_file_path( '/inc/widgets/author.php' );
// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
/**
 * Register widgets
 *
 * @since Bosa Crypto 1.0.0
 */
/**
* Load all the widgets
* @since Bosa Crypto 1.0.0
*/
function bosa_crypto_register_widget() {

	$widgets = array(
		'Bosa_Crypto_Latest_Posts_Widget',
		'Bosa_Crypto_Author_Widget',
	);

	foreach ( $widgets as $key => $value) {
    	register_widget( $value );
	}
}
add_action( 'widgets_init', 'bosa_crypto_register_widget' );