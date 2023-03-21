<?php
function add_elementor_widget_categories( $elements_manager ) {

    $elements_manager->add_category(
        'Cool Grid',
        [
            'title' => __( 'Cool Grid', 'coolgrid-elementor' ),
            'icon' => 'eicon-posts-grid',
        ]
    );

}

add_action( 'elementor/elements/categories_registered', 'add_elementor_widget_categories' );