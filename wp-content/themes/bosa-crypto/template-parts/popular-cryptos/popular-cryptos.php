<?php
$bosa_crypto_crypto_title 	= get_theme_mod( 'bosa_crypto_crypto_title','' );
$bosa_crypto_blog_crypto_one_ID 	= get_theme_mod( 'bosa_crypto_blog_crypto_image_one','' );
$bosa_crypto_blog_crypto_two_ID 	= get_theme_mod( 'bosa_crypto_blog_crypto_image_two','' );       
$bosa_crypto_blog_crypto_three_ID  = get_theme_mod( 'bosa_crypto_blog_crypto_image_three','' );
$bosa_crypto_blog_crypto_four_ID 	= get_theme_mod( 'bosa_crypto_blog_crypto_image_four','' );
$bosa_crypto_blog_crypto_five_ID 	= get_theme_mod( 'bosa_crypto_blog_crypto_image_five','' );
$bosa_crypto_blog_crypto_six_ID 	= get_theme_mod( 'bosa_crypto_blog_crypto_image_six','' );

$bosa_crypto_crypto_array = array();
$bosa_crypto_has_crypto = false;
$bosa_crypto_has_crypto_content = false;
$bosa_crypto_has_crypto_number = 0;

if( !empty( $bosa_crypto_crypto_title ) || !empty( $bosa_crypto_blog_crypto_desc_one ) || !empty( $bosa_crypto_blog_crypto_desc_two )){
	$bosa_crypto_has_crypto_content = true;
}
if( !empty( $bosa_crypto_blog_crypto_one_ID ) ){
	$bosa_crypto_blog_crypto_one  = wp_get_attachment_image_src( $bosa_crypto_blog_crypto_one_ID,'bosa-crypto-590-310');
 	if ( is_array(  $bosa_crypto_blog_crypto_one ) ){
 		$bosa_crypto_has_crypto = true;
   	 	$bosa_crypto_blog_crypto_one = $bosa_crypto_blog_crypto_one[0];
   	 	$bosa_crypto_crypto_array['image_one'] = array(
			'ID' => $bosa_crypto_blog_crypto_one,
		);	
  	}
}
if( !empty( $bosa_crypto_blog_crypto_two_ID ) ){
	$bosa_crypto_blog_crypto_two = wp_get_attachment_image_src( $bosa_crypto_blog_crypto_two_ID,'bosa-crypto-590-310');
	if ( is_array(  $bosa_crypto_blog_crypto_two ) ){
		$bosa_crypto_has_crypto = true;	
        $bosa_crypto_blog_crypto_two = $bosa_crypto_blog_crypto_two[0];
        $bosa_crypto_crypto_array['image_two'] = array(
			'ID' => $bosa_crypto_blog_crypto_two,
		);	
  	}
}
if( !empty( $bosa_crypto_blog_crypto_three_ID ) ){	
	$bosa_crypto_blog_crypto_three = wp_get_attachment_image_src( $bosa_crypto_blog_crypto_three_ID,'bosa-crypto-590-310');
	if ( is_array(  $bosa_crypto_blog_crypto_three ) ){
		$bosa_crypto_has_crypto = true;
      	$bosa_crypto_blog_crypto_three = $bosa_crypto_blog_crypto_three[0];
      	$bosa_crypto_crypto_array['image_three'] = array(
			'ID' => $bosa_crypto_blog_crypto_three,
		);	
  	}
}
if( !empty( $bosa_crypto_blog_crypto_four_ID ) ){	
	$bosa_crypto_blog_crypto_four = wp_get_attachment_image_src( $bosa_crypto_blog_crypto_four_ID,'bosa-crypto-590-310');
	if ( is_array(  $bosa_crypto_blog_crypto_four ) ){
		$bosa_crypto_has_crypto = true;
      	$bosa_crypto_blog_crypto_four = $bosa_crypto_blog_crypto_four[0];
      	$bosa_crypto_crypto_array['image_four'] = array(
			'ID' => $bosa_crypto_blog_crypto_four,
		);	
  	}
}

if( !empty( $bosa_crypto_blog_crypto_five_ID ) ){	
	$bosa_crypto_blog_crypto_five = wp_get_attachment_image_src( $bosa_crypto_blog_crypto_five_ID,'bosa-crypto-590-310');
	if ( is_array(  $bosa_crypto_blog_crypto_five ) ){
		$bosa_crypto_has_crypto = true;
      	$bosa_crypto_blog_crypto_five = $bosa_crypto_blog_crypto_five[0];
      	$bosa_crypto_crypto_array['image_five'] = array(
			'ID' => $bosa_crypto_blog_crypto_five,
		);	
  	}
}
if( !empty( $bosa_crypto_blog_crypto_six_ID ) ){	
	$bosa_crypto_blog_crypto_six = wp_get_attachment_image_src( $bosa_crypto_blog_crypto_six_ID,'bosa-crypto-590-310');
	if ( is_array(  $bosa_crypto_blog_crypto_six ) ){
		$bosa_crypto_has_crypto = true;
      	$bosa_crypto_blog_crypto_six = $bosa_crypto_blog_crypto_six[0];
      	$bosa_crypto_crypto_array['image_six'] = array(
			'ID' => $bosa_crypto_blog_crypto_six,
		);	
  	}
}
$bosa_crypto_has_crypto_number = count($bosa_crypto_crypto_array);
if( !get_theme_mod( 'bosa_crypto_disable_popular_cryptos_section', true ) && ( $bosa_crypto_has_crypto || $bosa_crypto_has_crypto_content) ){ ?>
	<section class="section-crypto-area">
		<?php if ( $bosa_crypto_has_crypto_content ){?>
			<h2 class="crypto-section-title">
				<?php echo esc_html( get_theme_mod( 'bosa_crypto_crypto_title', '' ) ); ?>
			</h2>
		<?php } ?>
		<div class="crypto-slider_container" attribute="<?php echo esc_html($bosa_crypto_has_crypto_number); ?>">
			<div class="crypto-slider">
				<?php foreach( $bosa_crypto_crypto_array as $bosa_crypto_each_crypto ){ ?>
					<figure class= "featured-image">
						<img src="<?php echo esc_url( $bosa_crypto_each_crypto['ID'] ); ?>">
					</figure>
				<?php } ?>
			</div>
		</div>
	</section>
<?php } 