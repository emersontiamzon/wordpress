<form method="get" class="search-form nav-menu-search" action="<?php echo home_url( '/' ); ?>">
	<input type="text" name="s" class="search-field" id="search" placeholder="<?php _e( 'Search', 'regina' ); ?>" value="<?php echo get_search_query(); ?>">
	<button class="icon"><i class="fa fa-search"></i></button>
</form>
