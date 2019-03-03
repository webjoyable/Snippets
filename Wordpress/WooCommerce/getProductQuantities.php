<?php

/**
 * Get product stock quantites from entire category
 */

function sl_get_stock_quantity( $cat ) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'product_cat' => $cat
    );

    $loop = new WP_Query( $args );

    while ( $loop->have_posts() ) : $loop->the_post();

    global $product;
    $stock += $product->get_stock_quantity();

    endwhile;
    wp_reset_query();

    return $stock ? $stock : 0;
}