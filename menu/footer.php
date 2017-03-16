<?php if ( has_nav_menu( 'footer' ) ) : // Check if there's a menu assigned to the 'footer' location. ?>

	<nav <?php hybrid_attr( 'menu', 'footer' ); ?>>

		<?php wp_nav_menu(
			array(
				'theme_location'  => 'footer',
				'container'       => '',
				'menu_id'         => 'menu-footer-items',
				'menu_class'      => 'menu-items',
				'fallback_cb'     => '',
				'items_wrap'      => '<ul id="%s" class="%s">%s</ul>',
				'depth'           => 1,
			)
		); ?>

	</nav><!-- #menu-footer -->

<?php endif; // End check for menu. ?>