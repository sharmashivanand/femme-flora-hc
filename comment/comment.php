<li <?php hybrid_attr( 'comment' ); ?>>

	<article>
		<header class="comment-meta">
			<?php echo get_avatar( $comment, 48 ); ?>
			<cite <?php hybrid_attr( 'comment-author' ); ?>><?php comment_author_link(); ?></cite>
			<time <?php hybrid_attr( 'comment-published' ); ?>><?php printf( esc_html__( '%s ago', 'femme-flora' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?></time>
			<a <?php hybrid_attr( 'comment-permalink' ); ?>><span class="screen-reader-text">Permalink to comment</span> #<?php comment_ID(); ?></a>
			<?php edit_comment_link(__('Edit', 'femme-flora')); ?>
		</header><!-- .comment-meta -->

		<div <?php hybrid_attr( 'comment-content' ); ?>>
			<?php comment_text(); ?>
		</div><!-- .comment-content -->

		<?php hybrid_comment_reply_link(); ?>
	</article>

<?php // No closing </li> is needed.  WordPress will know where to add it. ?>