<?php if ( has_nav_menu( 'after_header' ) ) : // Check if there's a menu assigned to the 'after_header' location. ?>

	<nav <?php hybrid_attr( 'menu', 'after_header' ); ?>>

		<?php wp_nav_menu(
			array(
				'theme_location'  => 'after_header',
				'container'       => '',
				'menu_id'         => 'menu-after_header-items',
				'menu_class'      => 'menu-items',
				'fallback_cb'     => '',
				'items_wrap'      => '<div class="wrap"><ul id="%s" class="%s">%s</ul></div>'
			)
		); ?>

	</nav><!-- #menu-after_header -->

<?php endif; // End check for menu. ?>