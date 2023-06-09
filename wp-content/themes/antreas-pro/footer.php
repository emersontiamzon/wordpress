<?php do_action( 'antreas_after_main' ); ?>
			
			<section id="subfooter" class="subfooter">
				<div class="container">
					<?php do_action( 'antreas_subfooter' ); ?>
				</div>
			</section>

			<?php do_action( 'antreas_before_footer' ); ?>
			<footer id="footer" class="footer">
				<div class="container">
					<?php do_action( 'antreas_footer' ); ?>
				</div>

				<?php if ( antreas_get_option( 'enable_go_to_top' ) === true ) : ?>
					<a id="back-to-top" href="#" title="<?php esc_attr_e( 'Back To Top', 'antreas' ); ?>"></a>
				<?php endif; ?>

			</footer>
			<?php do_action( 'antreas_after_footer' ); ?>
			
			<div class="clear"></div>
		</div><!-- wrapper -->
		<?php do_action( 'antreas_after_wrapper' ); ?>
	</div><!-- outer -->
	<?php wp_footer(); ?>
</body>
</html>
