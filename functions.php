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

add_filter( 'woocommerce_checkout_fields', 'emp_theme_setup_checkout_fields', 40, 1 );
function emp_theme_setup_checkout_fields( $checkout_fields ){
	$dte_initial_class = apply_filters('emp_bf_default_flds_class_set',EMP_BF_FLDS_DEFAULT_CLASS_SET);

        $checkout_fields['billing']['billing_email']['priority'] = 21;
        //$checkout_fields['billing']['billing_email']['class'] = $dte_initial_class;
        $checkout_fields['billing']['billing_email']['class'][] = 'form-row-first';
        $k = array_search('form-row-wide',$checkout_fields['billing']['billing_email']['class']);
        if($k != false ){
                unset($checkout_fields['billing']['billing_email']['class'][$k]);
        }

        $k = array_search('form-row-last',$checkout_fields['billing']['billing_email']['class']);
        if($k != false ){
                unset($checkout_fields['billing']['billing_email']['class'][$k]);
        }

        $checkout_fields['billing']['billing_phone']['priority'] = 30;
        $checkout_fields['billing']['billing_phone']['class'][] = 'form-row-last';

        $checkout_fields['billing']['billing_state']['priority'] = 50;
        $k = array_search('form-row-wide',$checkout_fields['billing']['billing_state']['class']);
        if($k != false ){
                unset($checkout_fields['billing']['billing_state']['class'][$k]);
        }
        $checkout_fields['billing']['billing_state']['class'][] = 'form-row-first';

        $checkout_fields['billing']['billing_city']['priority'] = 60;
        $k = array_search('form-row-wide',$checkout_fields['billing']['billing_city']['class']);
        if($k != false ){
                unset($checkout_fields['billing']['billing_city']['class'][$k]);
        }
        $checkout_fields['billing']['billing_city']['class'][] = 'form-row-last';
        
        $checkout_fields['billing']['billing_address_1']['priority'] = 80;
        $checkout_fields['billing']['billing_address_2']['priority'] = 81;

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

        $checkout_fields['billing']['billing_postcode']['priority'] = 70;

        return $checkout_fields;
}

add_filter( 'woocommerce_checkout_fields', 'name_second' );

function name_second( $checkout_fields ) {
$checkout_fields['billing']['billing_first_name']['priority'] = 1;
return $checkout_fields;
}

add_filter( 'woocommerce_checkout_fields', 'lastname_third' );

function lastname_third( $checkout_fields ) {
$checkout_fields['billing']['billing_last_name']['priority'] = 2;
return $checkout_fields;
}

add_filter( 'woocommerce_checkout_fields', 'state' );

function state( $checkout_fields ) {
$checkout_fields['billing']['billing_state']['priority'] = 4;
return $checkout_fields;
}

//billing_state

add_filter( 'woocommerce_checkout_fields', 'name_second' );

/* function name_second( $checkout_fields ) {
	$checkout_fields['billing']['billing_first_name']['priority'] = 90;
	return $checkout_fields;
} */

//include_once("includes/emp_brands_import_support.php");


if (!function_exists('write_log')) {
    /* Escribe datos al log de WP. */
    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
  		}
    }
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