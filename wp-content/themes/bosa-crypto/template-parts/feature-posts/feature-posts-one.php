<?php
$bosa_crypto_posts_per_page_count = get_theme_mod( 'bosa_crypto_feature_posts_posts_number', 6 );
$bosa_crypto_feature_posts_id = get_theme_mod( 'bosa_crypto_feature_posts_category', '' );

$bosa_crypto_query = new WP_Query( apply_filters( 'bosa_crypto_blog_args', array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => $bosa_crypto_posts_per_page_count,
	'cat'                 => $bosa_crypto_feature_posts_id,
	'offset'              => 0,
	'ignore_sticky_posts' => 1
)));

$bosa_crypto_posts_array = $bosa_crypto_query->get_posts();
$bosa_crypto_show_feature_posts = count( $bosa_crypto_posts_array ) > 0 && is_home();

if( !get_theme_mod( 'bosa_crypto_disable_feature_posts_section', false ) && $bosa_crypto_show_feature_posts ){
	$bosa_crypto_feature_title_desc_align = get_theme_mod( 'bosa_crypto_feature_posts_section_title_desc_alignment', 'left' );
	if ( $bosa_crypto_feature_title_desc_align == 'left' ){
		$bosa_crypto_feature_title_desc_align = 'text-left';
	}else if ( $bosa_crypto_feature_title_desc_align == 'center' ){
		$bosa_crypto_feature_title_desc_align = 'text-center';
	}else{
		$bosa_crypto_feature_title_desc_align = 'text-right';
	} ?>
	<section class="section-feature-posts-area feature-posts-layout-one">
		<?php if( ( !get_theme_mod( 'bosa_crypto_disable_feature_posts_section_title', true ) && get_theme_mod( 'bosa_crypto_feature_posts_section_title', '' ) ) || ( !get_theme_mod( 'bosa_crypto_disable_feature_posts_section_description', true ) && get_theme_mod( 'bosa_crypto_feature_posts_section_description', '' ) ) ){ ?>
			<div class="section-title-wrap <?php echo esc_attr( $bosa_crypto_feature_title_desc_align ); ?> ">
				<?php if( !get_theme_mod( 'bosa_crypto_disable_feature_posts_section_title', true ) && get_theme_mod( 'bosa_crypto_feature_posts_section_title', '' ) ) { ?>
					<h2 class="section-title"><?php echo esc_html( get_theme_mod( 'bosa_crypto_feature_posts_section_title', '' ) ); ?></h2>
				<?php } 
				if(  !get_theme_mod( 'bosa_crypto_disable_feature_posts_section_description', true ) && get_theme_mod( 'bosa_crypto_feature_posts_section_description', '' ) ){ ?>
					<p><?php echo esc_html( get_theme_mod( 'bosa_crypto_feature_posts_section_description', '' ) ); ?></p>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="content-wrap">
			<div class="row">
			<?php

				while ( $bosa_crypto_query->have_posts() ) : $bosa_crypto_query->the_post();
				$bosa_crypto_render_feature_post_image_size = get_theme_mod( 'bosa_crypto_render_feature_post_image_size', 'bosa-crypto-420-300' );
				$bosa_crypto_image 							= get_the_post_thumbnail_url( get_the_ID(), $bosa_crypto_render_feature_post_image_size );

				$bosa_crypto_columns_class = '';
				if( get_theme_mod( 'bosa_crypto_feature_posts_columns', 'three_columns' ) == 'one_column' ){
					$bosa_crypto_columns_class = 'col-md-12';
				}elseif( get_theme_mod( 'bosa_crypto_feature_posts_columns', 'three_columns' ) == 'two_columns' ){
					$bosa_crypto_columns_class = 'col-md-6';
				}elseif( get_theme_mod( 'bosa_crypto_feature_posts_columns', 'three_columns' ) == 'three_columns' ){
					$bosa_crypto_columns_class = 'col-md-4';
				}elseif( get_theme_mod( 'bosa_crypto_feature_posts_columns', 'three_columns' ) == 'four_columns' ){
					$bosa_crypto_columns_class = 'col-md-3';
				}
				?>
					<div class="<?php echo esc_attr( $bosa_crypto_columns_class ); ?>">
						<article class="post feature-posts-content-wrap <?php echo esc_attr( get_theme_mod( 'bosa_crypto_feature_posts_text_alignment', 'text-left' ) ); ?>">
							<div class="feature-posts-image" style="background-image: url( <?php echo esc_url( $bosa_crypto_image ); ?> );">
								<div class="feature-posts-content">
									<?php
									if( 'post' == get_post_type() ): 
										$bosa_crypto_categories_list = get_the_category_list( ' ' );
										if( $bosa_crypto_categories_list && !get_theme_mod( 'bosa_crypto_hide_featured_posts_category', false ) ):
										printf( '<span class="cat-links">' . '%1$s' . '</span>', $bosa_crypto_categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										endif;
									endif;
									?>
									<?php 
										if( !get_theme_mod( 'bosa_crypto_disable_feature_posts_title', false ) ){
											?>
											<h3 class="feature-posts-title">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h3>
											<?php
										}
									?>
									<div class="entry-meta">
										<?php
											if( !get_theme_mod( 'bosa_crypto_hide_featured_posts_date', false ) ): ?>
												<span class="posted-on">
													<a href="<?php echo esc_url( bosa_crypto_get_day_link() ); ?>" >
														<?php echo esc_html(get_the_date('M j, Y')); ?>
													</a>
												</span>
											<?php endif; 
											if( !get_theme_mod( 'bosa_crypto_hide_featured_posts_author', false ) ): ?>
												<span class="byline">
													<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
														<?php echo esc_html( get_the_author() ) ?>
													</a>
												</span>
											<?php endif; 
											if( !get_theme_mod( 'bosa_crypto_hide_featured_posts_comment', false ) ): ?>
												<span class="comments-link">
													<a href="<?php comments_link(); ?>">
														<?php echo absint( wp_count_comments( get_the_ID() )->approved ); ?>
													</a>
												</span>
											<?php endif; ?>
									</div>
								</div>
							</div>
						</article>
					</div>
				<?php
				endwhile; 
				wp_reset_postdata();
			?>
			</div>
		</div>
	</section>
<?php }