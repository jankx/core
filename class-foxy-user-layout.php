<?php

class Foxy_User_Layout {
	public static function user_layout( $layout_args, $users, $widget_args = null ) {
		echo '<div class="owl-carousel">';
		foreach ( $users as $user ) {
			$user_link = apply_filters( 'foxy_user_link', get_author_posts_url( $user->ID, $user->user_nicename ) )
			?>
			<a href="<?php echo $user_link; ?>" title="<?php echo $user->display_name; ?>">
			<img src="<?php echo get_avatar_url( $user->email ); ?>" alt="<?php echo $user->display_name; ?>">
			</a>
			<?php
		}
		echo '</div>';
	}
}
