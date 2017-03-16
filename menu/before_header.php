<?php if ( has_nav_menu( 'before_header' ) ) : // Check if there's a menu assigned to the 'before_header' location. ?>

	<nav <?php hybrid_attr( 'menu', 'before_header' ); ?>>

		<?php wp_nav_menu(
			array(
				'theme_location'  => 'before_header',
				'container'       => '',
				'menu_id'         => 'menu-before_header-items',
				'menu_class'      => 'menu-items',
				'fallback_cb'     => '',
				'items_wrap'      => '<div class="wrap"><ul id="%s" class="%s">%s</ul></div>'
			)
		); ?>

	</nav><!-- #menu-before_header -->

<?php endif; // End check for menu. ?>