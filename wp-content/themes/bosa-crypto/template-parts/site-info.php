<?php
/**
 * Template part for displaying site info
 *
 * @package Bosa Crypto
 */

?>

<div class="site-info">
	<?php echo wp_kses_post( html_entity_decode( esc_html__( 'Copyright &copy; ' , 'bosa-crypto' ) ) );
		echo esc_html( date( 'Y' ) . ' ' . get_bloginfo( 'name' ) );
		echo esc_html__( '. Powered by', 'bosa-crypto' );
	?>
	<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'bosa-crypto' ) ); ?>" target="_blank">
		<?php
			printf( esc_html__( 'WordPress', 'bosa-crypto' ) );
		?>
	</a>
</div><!-- .site-info -->