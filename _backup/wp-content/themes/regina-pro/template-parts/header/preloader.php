<?php

//Preloader options
$type                 = get_theme_mod( 'regina_preloader_type', 'center-atom' );
$minimal_text         = get_theme_mod( 'regina_minimal_text', 'Loading...' );
$regina_text_color    = get_theme_mod( 'regina_text_color', '#333333' );
$custom_loader        = get_theme_mod( 'regina_custom_loader', '' );
$regina_graphic_color = get_theme_mod( 'regina_graphic_color', '#08cae8' );

?>

<?php

if ( 'corner-top' == $type && $regina_graphic_color ) {
	echo '<style>.pace .pace-activity{background:' . $regina_graphic_color . '}</style>';
} elseif ( 'loading-bar' == $type && $regina_graphic_color ) {
	echo '<style>
.pace .pace-progress{background:' . $regina_graphic_color . ';}
.pace .pace-progress{color:' . $regina_graphic_color . ';}
.pace .pace-activity{box-shadow: inset 0 0 0 2px ' . $regina_graphic_color . ', inset 0 0 0 7px #FFF;}
</style>';
} elseif ( 'center-radar' == $type && $regina_graphic_color ) {
	echo '<style>.pace .pace-activity,.pace .pace-activity:before{border-color:' . $regina_graphic_color . ' transparent transparent}</style>';
} elseif ( 'center-atom' == $type && $regina_graphic_color ) {
	echo '<style>.pace .pace-activity,.pace .pace-activity:before,.pace .pace-activity:after{border-color:' . $regina_graphic_color . '}</style>';
}

?>

<!-- Site Preloader -->
<div id="page-loader">
	<div class="page-loader-inner">
		<div class="loader">
			<?php
			if ( 'minimal' == $type ) {
				$style = '';
				if ( $regina_text_color ) {
					$style = 'style="color:' . esc_attr( $regina_text_color ) . '"';
				}
				?>
				<!-- cool inlined style here :) -->
				<strong <?php echo esc_attr( $style ); ?>><?php echo esc_html( $minimal_text ); ?></strong>
			<?php
			} elseif ( 'custom' == $type ) {
				echo '<img src="' . esc_url( $custom_loader ) . '">';
			}
?>

		</div>
	</div>
</div>
<!-- END Site Preloader -->


