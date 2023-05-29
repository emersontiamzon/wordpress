<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
		<div class="image">
			<?php the_post_thumbnail(); ?>
		</div>
	<?php } ?>
	<h2 class="title">
		<a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
	</h2>
	<p class="meta"><?php the_date( 'F d, Y', '', '' ); ?> - <?php echo __( 'by', 'regina' ); ?>
		<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
		<?php
		$categories = get_the_category( get_the_ID() );
		if ( $categories ) {
			_e( '  -  in', 'regina' );
		}

		foreach ( $categories as $key => $category ) {
			$category_url = get_category_link( $category->term_id );
			echo ' <a href="' . esc_attr( $category_url ) . '">' . esc_html( $category->name ) . '</a>';
			if ( $key + 1 < count( $categories ) ) {
				echo ',';
			}
		}
		?>
	</p>

	<p class="comments">
		<a href="#"><span class="nc-icon-outline ui-2_chat-round"></span><?php comments_number( __( 'No Comments', 'regina' ), __( '1 Comment', 'regina' ), __( '% Comment', 'regina' ) ); ?>
		</a>
	</p>
	<div class="body">
		<?php the_excerpt(); ?>
	</div>
</article>
