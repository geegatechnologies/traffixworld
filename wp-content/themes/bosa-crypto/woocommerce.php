<?php
/**
 * The template for displaying archived woocommerce products
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @package Bosa Crypto
 */
get_header(); 
?>
<div id="content" class="site-content">
	<div class="container">
		<section class="wrap-detail-page ">
			<?php if( bosa_crypto_wooCom_is_product_page() || bosa_crypto_wooCom_is_shop() ){
				if( ( bosa_crypto_wooCom_is_product_page() && !get_theme_mod( 'bosa_crypto_disable_single_product_title', true ) ) || ( bosa_crypto_wooCom_is_shop() && !get_theme_mod( 'bosa_crypto_disable_shop_page_title', false ) ) ){ ?>
					<h1 class="page-title">
						<?php woocommerce_page_title(); ?>
					</h1>
				<?php } ?>
			<?php }else{ ?>
				<h1 class="page-title">
					<?php woocommerce_page_title(); ?>
				</h1>
			<?php } ?>
				<?php
				if( !bosa_crypto_wooCom_is_product_page() ){
					if( get_theme_mod( 'bosa_crypto_breadcrumbs_controls', 'show_in_all_page_post' ) == 'disable_in_all_pages' || get_theme_mod( 'bosa_crypto_breadcrumbs_controls', 'show_in_all_page_post' ) == 'show_in_all_page_post' ){
						bosa_crypto_breadcrumb_wrap();
					}
				} ?>
				<div class="row">
					<?php
					$bosa_crypto_getSidebarClass = bosa_crypto_get_sidebar_class();
					$bosa_crypto_sidebarClass = 'col-12';
					if( !bosa_crypto_wooCom_is_product_page() ){
						$bosa_crypto_sidebarClass = $bosa_crypto_getSidebarClass[ 'sidebarClass' ];
						if( !get_theme_mod( 'bosa_crypto_disable_sidebar_woocommerce_shop', false ) ){
							bosa_crypto_woo_product_detail_left_sidebar( $bosa_crypto_getSidebarClass[ 'sidebarColumnClass' ] );
						}
					}	
					?>
					
					<div id="primary" class="content-area <?php echo esc_attr( $bosa_crypto_sidebarClass ); ?>">
						<main id="main" class="site-main post-detail-content woocommerce-products" role="main">
							<?php if ( have_posts() ) :
								woocommerce_content();
							endif;
							?>
						</main><!-- #main -->
					</div><!-- #primary -->
					<?php
					if( !bosa_crypto_wooCom_is_product_page() ){
						if( !get_theme_mod( 'bosa_crypto_disable_sidebar_woocommerce_shop', false ) ){
							bosa_crypto_woo_product_detail_right_sidebar( $bosa_crypto_getSidebarClass[ 'sidebarColumnClass' ] );
						}
					} ?>
				</div>
		</section>
	</div><!-- #container -->
</div><!-- #content -->
<?php
get_footer();
