<?php
class Foxy_Meta_Framework_WordPress extends Foxy_Meta_Framework_Base {
    public function factory( $post_type, $args ) {
        list( $tabs, $fields ) = $this->group_all_fields( $args['args'] );
    }
}
