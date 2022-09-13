<?php 
function mymedi_child_register_scripts(){
    $parent_style = 'mymedi-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', array('font-awesome-5', 'mymedi-reset'), mymedi_get_theme_version() );
    wp_enqueue_style( 'mymedi-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'mymedi_child_register_scripts' );
add_filter('emp_import_brands_taxonomy_name',function($brand){return 'pwb-brand';});

include_once("includes/emp_fact_bolt.php");

add_filter( 'woocommerce_checkout_fields', 'emp_theme_setup_checkout_fields', 900, 1 );
function emp_theme_setup_checkout_fields( $checkout_fields ){
	$dte_initial_class = apply_filters('emp_bf_default_flds_class_set',EMP_BF_FLDS_DEFAULT_CLASS_SET);

        $checkout_fields['billing']['billing_email']['priority'] = 30;
        $checkout_fields['billing']['billing_email']['class'][] = 'form-row-first';
        $k = array_search('form-row-wide',$checkout_fields['billing']['billing_email']['class']);
        if($k != false ){
                unset($checkout_fields['billing']['billing_email']['class'][$k]);
        }

        $k = array_search('form-row-last',$checkout_fields['billing']['billing_email']['class']);
        if($k != false ){
                unset($checkout_fields['billing']['billing_email']['class'][$k]);
        }

        $checkout_fields['billing']['billing_phone']['priority'] = 40;
        $checkout_fields['billing']['billing_phone']['class'][] = 'form-row-last';

        $checkout_fields['billing']['billing_state']['priority'] = 50;
        $checkout_fields['billing']['billing_state']['class'] = ['form-row','form-row-first','update_totals_on_change'];

        
        $checkout_fields['billing']['billing_city']['class'] = ['form-row','form-row-last','update_totals_on_change'];
        
        $checkout_fields['billing']['billing_address_1']['priority'] = 70;
        $checkout_fields['billing']['billing_address_2']['priority'] = 71;
        $checkout_fields['billing']['billing_address_3']['priority'] = 72;

        $checkout_fields['billing']['billing_company_rut']['label'] = "RUT empresa <abbr class=\"required\" title=\"obligatorio\">*</abbr>";
        $checkout_fields['billing']['billing_company_rut']['required'] = false;
        $checkout_fields['billing']['billing_company_rut']['priority'] = 92;
        $checkout_fields['billing']['billing_company_rut']['class'][] = 'form-row-first';

        $checkout_fields['billing']['billing_company']['label'] = "Razón Social <abbr class=\"required\" title=\"obligatorio\">*</abbr>";
        $checkout_fields['billing']['billing_company']['priority'] = 90;
        $checkout_fields['billing']['billing_company']['required'] = false;
        $checkout_fields['billing']['billing_company']['class'][] = 'form-row-first';

        $checkout_fields['billing']['billing_company_business_line']['class'][] = 'form-row-last';


	    $checkout_fields['billing']['billing_company_address_1']['class'][] = 'form-row-last';

        $checkout_fields['billing']['billing_company_state']['class'][] = 'form-row-first';

        $checkout_fields['billing']['billing_company_city']['class'][] = 'form-row-last';

        $checkout_fields['billing']['billing_postcode']['priority'] = 100;

        return $checkout_fields;
}

include_once("includes/local_config.php");

add_filter( 'woocommerce_package_rates', 'hide_shipping_weight_based', 10, 2 );
function hide_shipping_weight_based( $rates, $package ) {
    if($package['destination']['state']=='R8'){
        $nrs = [];
        $allowed_shipping_method_regex = apply_filters('gcp_allowed_shipping_methods_for_' . $package['destination']['city'],['/chilexpress_woo_oficial:(.*?)/']);
        foreach( $rates AS $id => $data ) {
            foreach($allowed_shipping_method_regex AS $re){
                $permitido = preg_match($re, $id) === 1 ? true : false;
                if(!$permitido)
                    break;
            }
            if($permitido){
                $nr[$id] = $data;
            }
        }
        return $nr;
    }
    return $rates;
}

add_action('wp_footer','emp_checkout_fields_order');
function emp_checkout_fields_order(){
    if(is_checkout()){
    ?>
    <style>
        #billing_first_name_field {
            order: 0 !important;
        }

        #billing_last_name_field {
            order: 1 !important;
        }

        #billing_email_field {
            order: 2 !important;
        }

        #billing_phone_field {
            order: 3 !important;
        }

        #billing_country_field {
            order: 4 !important;
        }

        #billing_state_field {
            order: 5 !important;
        }

        #billing_city_field {
            order: 6 !important;
        }

        #billing_address_1_field {
            order: 7 !important;
        }

        #billing_address_2_field {
            order: 8 !important;
        }

        #billing_address_3_field {
            order: 9;
        }

        #billing_dte_type_field {
            order: 10 !important;
        }

        #billing_company_field {
            order: 11  !important;
        }

        #billing_company_business_line_field {
            order: 12 !important;
        }

        #billing_company_rut_field {
            order: 13 !important;
        }

        #billing_company_address_1_field {
            order: 14 !important;
        }

        #billing_company_state_field {
            order: 15 !important;
        }

        #billing_company_city_field {
            order: 16 !important;
        }
    </style>

    <?php
    }
}