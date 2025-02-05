<?php
$bosa_crypto_posts_per_page_count = get_theme_mod( 'bosa_crypto_highlight_posts_posts_number', 6 );
$bosa_crypto_highlight_posts_id = get_theme_mod( 'bosa_crypto_highlight_posts_category', '' );

$bosa_crypto_query = new WP_Query( apply_filters( 'bosa_crypto_blog_args', array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => $bosa_crypto_posts_per_page_count,
	'cat'                 => $bosa_crypto_highlight_posts_id,
	'offset'              => 0,
	'ignore_sticky_posts' => 1
)));

$bosa_crypto_posts_array = $bosa_crypto_query->get_posts();
$bosa_crypto_show_highlight_posts = count( $bosa_crypto_posts_array ) > 0 && is_home();

if( $bosa_crypto_show_highlight_posts && !get_theme_mod( 'bosa_crypto_disable_highlight_posts_section', false ) ){
	$bosa_crypto_highlight_title_desc_align = get_theme_mod( 'bosa_crypto_highlight_posts_section_title_desc_alignment', 'left' );
	if ( $bosa_crypto_highlight_title_desc_align == 'left' ){
		$bosa_crypto_highlight_title_desc_align = 'text-left';
	}else if ( $bosa_crypto_highlight_title_desc_align == 'center' ){
		$bosa_crypto_highlight_title_desc_align = 'text-center';
	}else {
		$bosa_crypto_highlight_title_desc_align = 'text-right';
	} ?>
	<section class="section-highlight-post highlight-layout-two">
		<div class="section-highlight-inner">
			<?php if( ( !get_theme_mod( 'bosa_crypto_disable_highlight_posts_section_title', true ) && get_theme_mod( 'bosa_crypto_highlight_posts_section_title', '' ) ) || ( !get_theme_mod( 'bosa_crypto_disable_highlight_posts_section_description', true ) && get_theme_mod( 'bosa_crypto_highlight_posts_section_description', '' ) ) ){ ?>
				<div class="section-title-wrap <?php echo esc_attr( $bosa_crypto_highlight_title_desc_align ); ?>">
					<?php if( !get_theme_mod( 'bosa_crypto_disable_highlight_posts_section_title', true ) && get_theme_mod( 'bosa_crypto_highlight_posts_section_title', '' ) ){ ?>
						<h2 class="section-title"><?php echo esc_html( get_theme_mod( 'bosa_crypto_highlight_posts_section_title', '' ) ); ?></h2>
					<?php }
					if( !get_theme_mod( 'bosa_crypto_disable_highlight_posts_section_description', true ) && get_theme_mod( 'bosa_crypto_highlight_posts_section_description', '' ) ){ ?>
						<p><?php echo esc_html( get_theme_mod( 'bosa_crypto_highlight_posts_section_description', '' ) ); ?></p>
					<?php } ?>
				</div>
			<?php } ?>
			<div class="highlight-post-slider">
				<?php
					while ( $bosa_crypto_query->have_posts() ) : $bosa_crypto_query->the_post();
				?>
					<div class="slide-item">
						<?php 
						$bosa_crypto_noThumbnail='';
						if( get_theme_mod( 'bosa_crypto_hide_highlight_posts_image', false ) || !has_post_thumbnail() ){
							$bosa_crypto_noThumbnail = 'has-no-thumbnail';
						}
						?>
						<div class="slide-inner">
							<article id="post-<?php the_ID(); ?>" <?php post_class( $bosa_crypto_noThumbnail ) ?>>
								<?php
					        	$bosa_crypto_highlight_two_size = 'bosa-crypto-420-300';
					        	$bosa_crypto_render_highlight_post_image_size = get_theme_mod( 'bosa_crypto_render_highlight_post_image_size', '' );
					        	if ( empty( $bosa_crypto_render_highlight_post_image_size ) ){
					        		$bosa_crypto_render_highlight_post_image_size = $bosa_crypto_highlight_two_size;
					        	}
								$bosa_crypto_image    = get_the_post_thumbnail_url( get_the_ID(), $bosa_crypto_render_highlight_post_image_size );
								$bosa_crypto_image_id = get_post_thumbnail_id();
								$bosa_crypto_alt      = get_post_meta( $bosa_crypto_image_id, '_wp_attachment_image_alt', true);

								if ( !get_theme_mod( 'bosa_crypto_hide_highlight_posts_image', false ) && has_post_thumbnail()){ ?>
									<figure class="featured-image">
										<a href="<?php the_permalink(); ?>">
											<img src="<?php echo esc_url( $bosa_crypto_image ); ?>" alt="<?php echo esc_attr( $bosa_crypto_alt ); ?>">
										</a>
									</figure>
								<?php } ?>
								<div class="post-content-wrap">
									<?php if( 'post' == get_post_type() ): 
										$bosa_crypto_categories_list = get_the_category_list( ' ' );
										if( $bosa_crypto_categories_list && !get_theme_mod( 'bosa_crypto_hide_highlight_posts_category', false ) ):
									
										printf( '<span class="cat-links">' . '%1$s' . '</span>', $bosa_crypto_categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											
									endif; endif; ?>
									<?php if( !get_theme_mod( 'bosa_crypto_hide_highlight_posts_title', false ) ){ ?>
										<div class="entry-content">
											<h3 class="entry-title">
												<a href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a>
											</h3>
										</div>
									<?php } ?>
									<div class="entry-meta">
										<?php
											if( !get_theme_mod( 'bosa_crypto_hide_highlight_posts_date', false ) ): ?>
												<span class="posted-on">
													<a href="<?php echo esc_url( bosa_crypto_get_day_link() ); ?>" >
														<?php echo esc_html(get_the_date('M j, Y')); ?>
													</a>
												</span>
											<?php endif; 
											if( !get_theme_mod( 'bosa_crypto_hide_highlight_posts_author', false ) ): ?>
												<span class="byline">
													<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
														<?php echo esc_html( get_the_author() ); ?>
													</a>
												</span>
											<?php endif; 
											if( !get_theme_mod( 'bosa_crypto_hide_highlight_posts_comment', false ) ): ?>
												<span class="comments-link">
													<a href="<?php comments_link(); ?>">
														<?php echo absint( wp_count_comments( get_the_ID() )->approved ); ?>
													</a>
												</span>
											<?php endif; ?>
										</div>
									</div>
							</article>
						</div>
					</div>
				<?php
				endwhile; 
				wp_reset_postdata();
				?>
			</div>
			<?php if( ( !get_theme_mod( 'bosa_crypto_disable_highlight_posts_arrows', false ) || !get_theme_mod( 'bosa_crypto_disable_highlight_posts_dots', false ) ) && !( count( $bosa_crypto_posts_array ) <= get_theme_mod( 'bosa_crypto_highlight_posts_slides_show', 3 ) ) ) { ?>
				<div class="wrap-arrow">
				    <ul class="slick-control">
				        <?php if ( !get_theme_mod( 'bosa_crypto_disable_highlight_posts_arrows', false ) ){ ?>
					        <li class="highlight-posts-prev">
					        	<span></span>
					        </li>
				    	<?php } 
				    	if ( !get_theme_mod( 'bosa_crypto_disable_highlight_posts_dots', false ) ){ ?>
			        		<div class="highlight-posts-dots"></div>
			        	<?php } 
				        if ( !get_theme_mod( 'bosa_crypto_disable_highlight_posts_arrows', false ) ){ ?>
					        <li class="highlight-posts-next">
					        	<span></span>
					        </li>
				    	<?php } ?>
				    </ul>
				</div>
			<?php } ?>
		</div>
	</section>
<?php }