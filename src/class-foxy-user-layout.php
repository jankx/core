<?php

class Foxy_User_Layout {
	public static function user_layout( $layout_args, $users, $widget_args = null ) {
		$user_classes = 'fx-users';
		$tag = array(
			'context' => 'user-layout-item',
			'class' => 'item',
		);
		$has_carousel = ( 'use' === array_get( $widget_args, 'use_carousel', false ) );

		if ( $has_carousel ) {
			$user_classes .= ' owl-carousel';
		} else {
			$user_classes .= ' row';
			$tag = array_merge( $tag, array(
				'mobile_columns' => 12,
				'tablet_columns' => 6,
				'desktop_columns' => 4,
			));
		}
		echo '<div class="'. $user_classes .'">';
		foreach ( $users as $user ) {
			$user_link = apply_filters( 'foxy_user_link', get_author_posts_url( $user->ID, $user->user_nicename ) );
			Foxy::ui()->tag( $tag );
			?>
			<a href="<?php echo $user_link; ?>" title="<?php echo $user->display_name; ?>">
				<div class="text-center">
					<img src="<?php echo get_avatar_url( $user->email ); ?>" alt="<?php echo $user->display_name; ?>">
				<?php if ( ! $has_carousel ): ?><h4 class="name"><?php echo $user->display_name; ?></h4><?php endif; ?>
				</div>
			</a>
			</div>
			<?php
		}
		echo '</div>';
	}
}
