<?php

class Foxy_Embed {
	protected $site_key;
	protected $url_info;
	protected $embed_options;

	public function __construct( $url, $embed_options = array() ) {
		$this->parse_url( $url );
		$this->set_options( $embed_options );
	}

	public function parse_url( $url ) {
		$this->url_info = wp_parse_url( $url );
		$this->site_key = preg_replace(
			'/[\s|.|-|_]{1,}/',
			'_',
			foxy_get_domain_name( $this->url_info['host'] )
		);
	}

	public function set_options( $options = array() ) {
		$this->options = wp_parse_args(
			$options, array(
				'autoplay' => false,
			)
		);
	}

	public function youtube( $video_info, $options ) {
		$options = apply_filters(
			'foxy_embed_youtube_options', wp_parse_args(
				$options, array(
					'allow_fullscreen' => true,
					'start'            => 0,
					'allow'            => 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture',
				)
			)
		);
		parse_str( $video_info['query'], $query );
		$embed  = "<iframe
		src=\"https://www.youtube-nocookie.com/embed/{$query['v']}?start={$options['start']}\"
		allow=\"{$options['allow']}\"";
		$embed .= $options['allow_fullscreen'] ? 'allowfullscreen' : '';
		$embed .= '></iframe>';

		return $embed;
	}

	public function content( $echo = true ) {
		$callback = array( $this, $this->site_key );
		$content  = '';
		if ( is_callable( $callback ) ) {
			$content = call_user_func( $callback, $this->url_info, $this->options );
		}
		if ( ! $echo ) {
			return $content;
		}
		echo $content;
	}
}
