<?php

$user_meta = get_user_meta( get_the_author_meta( 'ID' ), strtolower( MT_THEME_NAME ) . '_options', true );

?>
<div id="post-author">
	<div class="row">
		<div class="col-xs-2">
			<?php

			if ( ! empty( $user_meta['user_avatar'] ) ) {
				$avatar = wp_get_attachment_image_src( $user_meta['user_avatar'], 'full' );
				echo '<img src="' . esc_attr( $avatar[0] ) . '" alt="' . esc_attr( get_the_author() ) . '" width="111" class="avatar">';
			} else {
				echo get_avatar( get_the_author_meta( 'ID' ), 110 );
			}

			?>

		</div><!--.col-xs-2-->

		<div class="col-xs-10">
			<div class="content">
				<h3><?php esc_html( the_author() ); ?></h3>
				<p><?php esc_html( the_author_meta( 'description' ) ); ?></p>

				<ul class="social">
					<?php if ( ! empty( $user_meta['user-facebook-url'] ) ) : ?>
						<li class="facebook">
							<a href="<?php echo esc_attr( $user_meta['user-facebook-url'] ); ?>"><span class="nc-icon-glyph socials-1_logo-facebook"></span></a>
						</li>
					<?php endif ?>
					<?php if ( ! empty( $user_meta['user-twitter-url'] ) ) : ?>
						<li class="twitter">
							<a href="<?php echo esc_attr( $user_meta['user-twitter-url'] ); ?>"><span class="nc-icon-glyph socials-1_logo-twitter"></span></a>
						</li>
					<?php endif ?>
					<?php if ( ! empty( $user_meta['user-linkedin-url'] ) ) : ?>
						<li class="linkedin">
							<a href="<?php echo esc_attr( $user_meta['user-linkedin-url'] ); ?>"><span class="nc-icon-glyph socials-1_logo-linkedin"></span></a>
						</li>
					<?php endif ?>
				</ul>
			</div>
		</div><!--.col-xs-10-->
	</div><!--.row-->
</div><!--#post-author-->
