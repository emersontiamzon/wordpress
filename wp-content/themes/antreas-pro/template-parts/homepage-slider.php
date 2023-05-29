<?php $query = new WP_Query( 'post_type=cpo_slide&posts_per_page=-1&order=ASC&orderby=menu_order' ); ?>
<?php if ( $query->posts ) : ?>
	<div id="slider" class="slider">
		<div class="slider-slides cycle-slideshow" <?php antreas_slider_data(); ?>>
			<?php foreach ( $query->posts as $post ) : ?>

				<?php setup_postdata( $post ); ?>

				<?php $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), array( 1500, 7000 ), false, '' ); ?>

				<?php $slide_position           = get_post_meta( get_the_ID(), 'slide_position', true ); ?>
				<?php $slide_color              = get_post_meta( get_the_ID(), 'slide_color', true ); ?>
				<?php $slide_button_text_1      = get_post_meta( get_the_ID(), 'slide_button_text_1', true ); ?>
				<?php $slide_button_url_1       = get_post_meta( get_the_ID(), 'slide_button_url_1', true ); ?>
				<?php $slide_button_text_2      = get_post_meta( get_the_ID(), 'slide_button_text_2', true ); ?>
				<?php $slide_button_url_2       = get_post_meta( get_the_ID(), 'slide_button_url_2', true ); ?>
				<?php $slide_title              = get_post_meta( get_the_ID(), 'slide_title', true ); ?>
				<?php $slide_title_font_size    = get_post_meta( get_the_ID(), 'slide_title_font_size', true ); ?>
				<?php $slide_content_font_size  = get_post_meta( get_the_ID(), 'slide_content_font_size', true ); ?>
				<?php $slide_overlay_color      = get_post_meta( get_the_ID(), 'slide_overlay_color', true ); ?>
				<?php $slide_overlay_opacity    = get_post_meta( get_the_ID(), 'slide_overlay_opacity', true ); ?>

				<div id="slide-<?php echo get_the_ID(); ?>" class="slide slide-<?php echo get_the_ID(); ?> cycle-slide-active <?php echo esc_attr( $slide_position ) . ' ' . esc_attr( $slide_color ); ?>" style="background-image:url(<?php echo esc_url( $image_url[0] ); ?>);">
					<div class="slide-overlay" style="<?php echo $slide_overlay_color ? 'background-color: ' . esc_attr( $slide_overlay_color ) . ';' : ''; ?> <?php echo $slide_overlay_opacity ? 'opacity:' . esc_attr( $slide_overlay_opacity ) . ';' : ''; ?>"></div>
					<div class="slide-body">
						<div class="container">
							<div class="slide-caption">
								<?php if ( '1' != $slide_title ) : ?>
									<h2 class="slide-title" style="<?php echo $slide_title_font_size ? 'font-size: ' . esc_attr( $slide_title_font_size ) . 'px;' : ''; ?>">
										<?php the_title(); ?>
									</h2>
								<?php endif; ?>

								<div class="slide-content" style="<?php echo $slide_content_font_size ? 'font-size: ' . esc_attr( $slide_content_font_size ) . 'px;' : ''; ?>">
									<?php the_content(); ?>
								</div>

								<?php if ( '' !== $slide_button_url_1 && '' !== $slide_button_text_1 ) : ?>
									<a class="slide-button button" href="<?php echo esc_url( $slide_button_url_1 ); ?>">
										<?php echo esc_html( $slide_button_text_1 ); ?>
									</a>
								<?php endif; ?>

								<?php if ( '' !== $slide_button_url_2 && '' !== $slide_button_text_2 ) : ?>
									<a class="slide-button slide-button--second button" href="<?php echo esc_url( $slide_button_url_2 ); ?>">
										<?php echo esc_html( $slide_button_text_2 ); ?>
									</a>
								<?php endif; ?>

								<?php antreas_edit(); ?>
							</div>
							<div class="slide-image">
								<?php antreas_get_media( get_post_meta( get_the_ID(), 'slide_image', true ) ); ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( count( $query->posts ) > 1 ) : ?>
			<?php wp_enqueue_script( 'antreas_cycle' ); ?>
			<button class="slider-prev" data-cycle-cmd="pause"></button>
			<button class="slider-next" data-cycle-cmd="pause"></button>
			<div class="slider-pages" data-cycle-cmd="pause"></div>
		<?php endif; ?>
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>
