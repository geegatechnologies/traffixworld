<?php
$bosa_crypto_page_one 	= get_theme_mod( 'bosa_crypto_testimonials_page_one', '' );
$bosa_crypto_page_two 	= get_theme_mod( 'bosa_crypto_testimonials_page_two', '' );
$bosa_crypto_page_three	= get_theme_mod( 'bosa_crypto_testimonials_page_three', '' );

$bosa_crypto_page_array = array();
$bosa_crypto_has_page = false;
if( !empty( $bosa_crypto_page_one ) ){
	$bosa_crypto_has_page = true;
	$bosa_crypto_page_array['page_one'] = array(
		'ID' => $bosa_crypto_page_one,
	);
}
if( !empty( $bosa_crypto_page_two ) ){
	$bosa_crypto_has_page = true;
	$bosa_crypto_page_array['page_two'] = array(
		'ID' => $bosa_crypto_page_two,
	);
}
if( !empty( $bosa_crypto_page_three ) ){
	$bosa_crypto_has_page = true;
	$bosa_crypto_page_array['page_three'] = array(
		'ID' => $bosa_crypto_page_three,
	);
}

if( !get_theme_mod( 'bosa_crypto_disable_testimonials_section', true ) && $bosa_crypto_has_page ){ ?>
	<section class="section-testimonials-area">
			<div class="row justify-content-center">
				<?php foreach( $bosa_crypto_page_array as $bosa_crypto_each_page ){ ?>
					<div class="col-md-6 col-xl-4">
						<article class="testimonials-item">							
							<h4 class="entry-text">	
								<?php 
								$bosa_crypto_excerpt = get_the_excerpt( $bosa_crypto_each_page[ 'ID' ] );
								$bosa_crypto_result  = wp_trim_words( $bosa_crypto_excerpt, 20, '' );
								echo esc_html( $bosa_crypto_result );
								?>
							</h4>
							<div class="author-detail d-flex align-items-center">
								<div class="author-content">
									<figure class= "featured-image">
										<?php echo get_the_post_thumbnail( $bosa_crypto_each_page[ 'ID' ], 'bosa-crypto-420-300' ); ?>
									</figure>
									<div class="author-detail-star" >										
										<h5 class="entry-title">
											<a href="<?php echo esc_url( get_permalink( $bosa_crypto_each_page[ 'ID' ] ) ); ?>">
												<?php echo esc_html( get_the_title( $bosa_crypto_each_page[ 'ID' ] ) ); ?>
												
											</a>
										</h5>		
										<div class="testimonials-star-container">
											 <i class="fa-solid fa-star"></i>
											 <i class="fa-solid fa-star"></i>
											 <i class="fa-solid fa-star"></i>
											 <i class="fa-solid fa-star"></i>
											 <i class="fa-solid fa-star"></i>
										</div>	
									</div>
								</div>
							</div>
						</article>
					</div>
				<?php } ?>
			</div>
	</section>
<?php } 