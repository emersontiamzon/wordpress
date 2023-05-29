<div class="team-member">
	<?php the_post_thumbnail( 'medium', array( 'class' => 'team-member-image', 'title' => '' ) ); ?>
	<div class="team-member-body">
		<h3 class="team-member-title"><?php the_title(); ?></h3>
		<div class="team-member-description">
			<?php echo get_post_meta( get_the_ID(), 'team_description', true ); ?>
		</div>
		<div class="team-member-content">
			<?php the_content(); ?>
		</div>
		<?php antreas_team_links(); ?>
		<?php antreas_edit(); ?>
	</div>
</div>
