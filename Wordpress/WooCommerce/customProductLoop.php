<?php

function cpl_category_name ( $product_id ) {
    $cat_list = wc_get_product_category_list( $product_id );
    return $cat_list;
}

function cpl_product_tags ( $product_id ) {
    $tags = get_the_terms( $product_id, 'product_tag');

    if ( $tags && !is_wp_error( $tags ) ) {
        foreach ( $tags as $tag ) {
            ?>
            <a class="cp-product-tag" href="<?php echo get_term_link( $tag ) ?>"><?php echo $tag->name ?></a>
            <?php
        }
    }
}

function cpl_get_price( $product_id ) {
    return; // don't show any prices

    $product = wc_get_product( $product_id );
    $price = wc_price( $product->get_regular_price() );
    
    if ( $product->is_on_sale() ) {
        $sale_price = wc_price( $product->get_sale_price() );
        $html = '<span class="cp-sale-price">' . $sale_price . '</span><span class="cp-regular-price">' . $price . '</span>';
        return $html;
    } else {
        return $price;
    }
}

function custom_product_loop( $atts = [] ) {
    /*
        attributes: 
        category    product category (comma separated)
        how_many    how many products to display
    */

    $category = explode(",", $atts['category']);
    $howManyProducts = $atts['how_many'];

    $whichCategory = 'all';

    // query args

    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'limit' => $howManyProducts,
        'order' => 'DESC',
        'category' => $category
    );

    $products = wc_get_products( $args );
    ?>
    <div class="custom-product-grid">
    <?php
    if ( $products ) {
        foreach ( $products as $product ) {
            ?>
            <div class="cp-wrapper">
                <div class="cp-item">
                        <div class="cp-image">
                            <a href="<?php echo get_permalink( $product->id ) ?>">
                            <img src="<?php echo wp_get_attachment_image_src( $product->image_id, 'large' )[0] ?>" />
                            </a>
                        </div>
                        <div class="cp-product-info">
                            <div class="cp-pn-wl">
                                <div class="cp-product-name"><a href="<?php echo get_permalink( $product->id ) ?>"><?php echo $product->name ?></a></div>
                                <div class="cp-category"><?php echo cpl_category_name( $product->id ) ?></div>
                            </div>
                            <div class="cp-price-container">
                                <?php echo cpl_get_price( $product->id); ?> 
                            </div>
                            <div class="cp-product-tags">
                                <?php cpl_product_tags( $product->id ) ?>
                            </div>
                            <div class="cp-button-container">
                                <div class="cp-social-share">
                                    <a class="cp-share" target="_blank" href="<?php echo 'https://facebook.com/sharer.php?display=page&u=' . urlencode( get_permalink( $product->id ) ); ?>"><i class="fab fa-facebook-f"></i></a>
                                </div>
                                <div class="cp-order-now-container">
                                    <a class="cp-order-now" id="cpl-quick-view" href="#" data-id="<?php echo $product->id ?>"><i class="fas fa-eye"></i>Quick View</a>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <?php
        }        
    } else {
        echo 'No products in selected category.';
    }
    ?>
    </div>
    <?php
}

add_shortcode('custom_product_loop', 'custom_product_loop');