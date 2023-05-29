<?php

// loads the shortcodes class, WordPress is loaded with it
require_once( 'class-macho-shortcodes.php' );

// get popup type
$popup     = trim( $_GET['popup'] );
$shortcode = new Macho_Shortcodes( $popup );
global $msm_shortcodes_config;



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<body>
<div id="macho-popup">
	<div id="macho-shortcode-wrap">
		<div id="macho-sc-form-wrap">
			<table id="macho-sc-form-table" class="macho-shortcode-selector">
				<tbody>
					<tr class="form-row">
						<td class="label">Choose Shortcode</td>
						<td class="field">
							<div class="macho-form-select-field">
							<div class="macho-shortcodes-arrow">&#xf107;</div>
								<select name="macho_select_shortcode" id="macho_select_shortcode" class="macho-form-select macho-input">
									<?php
									foreach ( $msm_shortcodes_config as $shortcode_key => $shortcode_value ) :
										if ( $shortcode_key == $popup ) :
											$selected = 'selected="selected"';
										else :
											$selected = '';
										endif;
										?>
										<option value="<?php echo $shortcode_key; ?>" <?php echo $selected; ?>><?php echo $shortcode_value['title']; ?> Shortcode</option>
									<?php endforeach; ?>
								</select>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<form method="post" id="macho-sc-form">
				<table id="macho-sc-form-table">
					<?php echo $shortcode->output; ?>
					<tbody class="macho-sc-form-button">
						<tr class="form-row">
							<td class="field"><a href="#" class="macho-insert">Insert Shortcode</a></td>
						</tr>
					</tbody>
				</table>
				<!-- /#macho-sc-form-table -->
			</form>
			<!-- /#macho-sc-form -->
		</div>
		<!-- /#macho-sc-form-wrap -->
		<div class="clear"></div>
	</div>
	<!-- /#macho-shortcode-wrap -->
	<script type="text/javascript">
		//jQuery('#macho_select_shortcode').selectize();
	</script>
</div>
<!-- /#macho-popup -->
</body>
</html>
