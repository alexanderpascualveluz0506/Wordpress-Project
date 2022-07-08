<?php
defined("KER_EXEC") or die("Silent is golden");


class KerProductBundle
{
    /**
     * Build the instance
     */
    public function __construct() {
        // ...
        add_action('wp_ajax_my_action',array( $this, 'my_action' ) );
        add_action('wp_ajax_nopriv_my_action', array( $this, 'my_action' ));

        add_action('product_type_selector', [$this, "wcpt_add_bundle_type"]);
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_tab' ), 50 );   
        add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_tab_content' ) );
      
        add_action( 'woocommerce_process_product_meta', array( $this, 'action_save_product_meta' ) );
       
    }
    
    /**
     * Add Experience Product Tab.
     *
     * @param array $tabs
     *
     * @return mixed
     */

    public function wcpt_add_bundle_type($type) {
    // Key should be exactly the same as in the class product_type
    $type[ 'bundle' ] = __('Product Bundle');

    return $type;
    } 

    public function add_product_tab( $tabs ) {

      $tabs['bundle'] = array(
        'label' => __('Product Bundle', 'wcpt'),
        'target' => 'bundle_options',
        'class' => 'show_if_bundle',
        'priority' => 1,
      );
        
      return $tabs;
    }
    
    /**
     * Add Content to Product Tab
     */
    public function add_product_tab_content() { 
      ?>
      <div id='bundle_options' class='panel woocommerce_options_panel hidden'>
        <div class='options_group'>
            
            <p class="form-field">
                <label for="items"><?php _e( 'Search Item', 'woocommerce' ); ?></label>
                    <select class="wc-product-search" style="width: 50%;" id="items" name="items" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>" >
                        <?php
                        $product_object = new WC_Product($post->ID);
                        $product_ids = $product_object->get_upsell_ids( 'edit' );
                        ?>
                        <?php foreach ( $product_ids as $product_id ) : ?>
                           <?php $product = wc_get_product( $product_id ); ?>
                               <?php if ( is_object( $product ) ) : ?>
                                    <?php echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) .  ' >' . wp_kses_post( $product->get_formatted_name() ) . '</option>'; ?>
                                <?php endif; ?>
                    
                        <?php endforeach; ?>
                    </select> <?php echo wc_help_tip( __( 'please enter the product name.', 'woocommerce' ) ); ?>
            </p>

            <div class="div-items" style="margin-left:160px">
                <table id="bundle-table" cellpadding="10" >
                    <?php $items = get_post_meta(get_the_ID(), '_bundle_items', true); ?>
                    <?php $default_qty = get_post_meta(get_the_ID(), '_default_bundle_qty', true); ?>
                    <?php if(isset($items) && is_array($items)) : ?>
                        <?php foreach($items as $index=>$text) : ?>    
                            <?php $product = wc_get_product($text); ?>
                                <tr class="item-row">
                                    <td>
                                        <?php echo $product->get_formatted_name(); ?>
                                        <br><?php echo $product->get_price();?>
                                        <input type="hidden" name="item_name[]" value="<?php echo  $product->get_formatted_name(); ?> ">
                                        <input type="hidden" value="<?php echo $product->get_price();?>" name="price[]" class="item_price">
                                        <input type="hidden" value="<?php echo $text; ?>" name="item_id[]" id="item_id">  
                                     </td>
                                     
                                    <td><input type="number" name="item_default_qty[]"  value="<?php echo $default_qty[$index]; ?>" style="width:50px;" class="qty"></td>
                                    <td class="total" ><?php echo number_format($product->get_price()*$default_qty[$index],2) ?></td> 
                                    <td><button type="button" class="myRemoveButton" data-id="<?php echo $text; ?>">Remove</button></td>
                                </tr>
                                
                    
                        <?php endforeach ?>         
                    <?php endif; ?>
                </table> 
            </div>
     
            <p class="form-field"> 
                <label id="_sale_price_dates_from"><?php _e( 'Regular Price', 'woocommerce' ); ?> (<?php echo get_woocommerce_currency_symbol() ?>) </label>
                <span id="regular_price">
                    <?php if(!empty( get_post_meta( get_the_ID(), '_regular_price', true ))) : ?>
                        <?php echo get_post_meta( get_the_ID(), '_regular_price', true ); ?>
                    <?php else : ?>
                        <?php echo "0.00"; ?>
                    <?php endif; ?>
                </span>
                <input type="hidden" id="bundle_regular_price" value="<?php echo get_post_meta( get_the_ID(), '_regular_price', true ); ?>" name="_regular_price">
            </p>

            <p class="form-field">
                <label id="_sale_price_dates_from"><?php _e( 'From', 'woocommerce' ); ?></label>
                <input type="date" name="_sale_price_dates_from" id="_sale_price_dates_from" value="<?php echo get_post_meta( get_the_ID(), '_sale_price_dates_from', true ); ?>">
            </p>
   
            <p class="form-field"> 
                <label id="_sale_price_dates_from"><?php _e( 'To', 'woocommerce' ); ?></label>
                <input type="date" name="_sale_price_dates_to" id="_sale_price_dates_to"  value="<?php echo get_post_meta( get_the_ID(), '_sale_price_dates_to', true ); ?>">
            </p>

            <p class="form-field">
                <label id="_bundle_price"><?php _e( 'Bundle Discount', 'woocommerce' ); ?></label> 
                <input id="woosb_discount" name="bundle_discount" type="number" value="<?php echo get_post_meta( get_the_ID(), '_bundle_discount', true ); ?>" placeholder="%" style="width:80px;">
                <input id="woosb_discount_amount" name="bundle_discount_amount" type="text" value="<?php echo get_post_meta( get_the_ID(), '_bundle_discount_amount', true ); ?>" placeholder="<?php echo get_woocommerce_currency_symbol(); ?>" style="width:80px;" style="width:80px;" placeholder="<?php echo get_woocommerce_currency_symbol() ?>">  
                <?php echo wc_help_tip( __( 'If you fill both, the amount will be usede.', 'woocommerce' ) ); ?>
            </p> 

        </div>
      </div>
    
    <?php
    }
    

    public function my_action() {
        $product_id= ($_POST['post_id'] );
        $product = wc_get_product( $product_id );
        ?>
            <tr class="item-row">
                <td>
                    <?php echo $product->get_formatted_name(); ?>
                    <br><?php echo $product->get_price();?>
                    <input type="hidden" name="item_name[]" value="<?php echo  $product->get_formatted_name(); ?> ">
                    <input type="hidden" value="<?php echo $product->get_price();?>" name="price[]" class="item_price">
                    <input type="hidden" value=<?php echo $product_id; ?> name="item_id[]" id="item_id">  
                </td>
                    <td><input type="number" name="item_default_qty[]" value="1" style="width:50px;" class="qty"></td>
                    <td class="total"><?php echo $product->get_price();?></td> 
                    <td><button type="button" class="myRemoveButton">Remove</button></td>
            </tr>

        <?php
        wp_die(); 
        }
        
    public function action_save_product_meta( $post_id ) {
        if ( ! empty( $_POST['bundle_discount_amount'] ) ) {
            update_post_meta($post_id, '_bundle_discount_amount',wc_clean( $_POST['bundle_discount_amount'] ) );
        }
        elseif ( ! empty( $_POST['bundle_discount'] ) ) {
                update_post_meta($post_id, '_bundle_discount',wc_clean( $_POST['bundle_discount'] ) );
        }

        if ( ! empty( $_POST['_sale_price_dates_from'] ) ) {
            update_post_meta($post_id, '_sale_price_dates_from', wc_clean( $_POST['_sale_price_dates_from'] ));
        }
        if ( ! empty( $_POST['_sale_price_dates_to'] ) ) {
            update_post_meta($post_id, '_sale_price_dates_to', wc_clean( $_POST['_sale_price_dates_to'] ));
        }
        if ( ! empty( $_POST['_regular_price'] ) ) {
            update_post_meta($post_id, '_regular_price', wc_clean( $_POST['_regular_price'] ));
        }
        if ( ! empty( $_POST['item_default_qty'] ) ) {
            update_post_meta($post_id, '_default_bundle_qty', wc_clean( $_POST['item_default_qty'] ));
        }
        if ( ! empty( $_POST['item_id'] ) ) {
            update_post_meta($post_id, '_bundle_items', wc_clean( $_POST['item_id'] ));
        }

        //update_post_meta($post_id, '_sale_price', "24.00"); 

        
    }
        
}
