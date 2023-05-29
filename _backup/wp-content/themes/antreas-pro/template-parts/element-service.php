<?php
$service_icon_type = get_post_meta( get_the_ID(), 'service_icon_type', true );
$service_icon = get_post_meta( get_the_ID(), 'service_icon', true );
$service_image = get_post_meta( get_the_ID(), 'service_image', true );
?>

<div class="service">
	<?php if ( $service_icon_type !== 'image' ) { ?>
		
			<?php antreas_icon( $service_icon, 'primary-color service-icon' ); ?>
		
	<?php } ?>
	<div class="service-body">
		<?php if ( $service_icon_type === 'image' && $service_image !== '' ) { ?>
			
				<img class="service-image" src="<?php echo $service_image; ?>">
			
		<?php } ?>

		<h4 class="service-title">
			<?php the_title(); ?>
		</h4>
	
		<div class="service-content">
			<?php the_excerpt(); ?>
		</div>
		<?php antreas_edit(); ?>
	</div>
</div>
