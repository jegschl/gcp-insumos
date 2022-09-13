<?php

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


// PHP: Quitar "(opcional)" de los campos no requeridos
add_filter( 'woocommerce_form_field' , 'remove_checkout_optional_fields_label', 10, 4 );
function remove_checkout_optional_fields_label( $field, $key, $args, $value ) {
    // Solo en la página de checkout.
    if( is_checkout() && ! is_wc_endpoint_url() ) {
        $optional = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
        $field = str_replace( $optional, '', $field );
    }
    return $field;
}

// JQuery: Needed for checkout fields to Remove "(optional)" from our non required fields
add_filter( 'wp_footer' , 'remove_checkout_optional_fields_label_script' );
function remove_checkout_optional_fields_label_script() {
    // Solo en la página de checkout.
    if( ! ( is_checkout() && ! is_wc_endpoint_url() ) ) return;

    $optional = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
    ?>
    <script>
    jQuery(function($){
        // Evento "update" en el formulario del checkout.
        $(document.body).on('update_checkout', function(){
            $('#billing_company_rut_field label > .optional').remove();
            $('#billing_company_field label > .optional').remove();
            $('#billing_company_field label > .optional').remove();
            $('#billing_company_state_field label > .optional').remove();
            $('#billing_company_city_field label > .optional').remove();
            $('#billing_company_address_1_field label > .optional').remove();
            $('#shipping_postcode_field label > .optional').remove();
            $('#shipping_state_field label > .optional').remove();
        });
    });
    </script>
    <?php
}


define('EMP_BF_FLDS_DEFAULT_CLASS_SET',array('form-row','dte_fields_wrapper_hidden'));

add_filter( 'woocommerce_checkout_fields', 'emp_bf_setup_checkout_core_fields', 30, 1 );
function emp_bf_setup_checkout_core_fields( $checkout_fields ){
	
	$dte_initial_class = apply_filters('emp_bf_default_flds_class_set',EMP_BF_FLDS_DEFAULT_CLASS_SET);
	
	$checkout_fields['billing']['billing_email']['priority'] = 20;
	$checkout_fields['billing']['billing_email']['class'][] = 'form-row-last';
	$k = array_search('form-row-wide',$checkout_fields['billing']['billing_email']['class']);
	if($k != false ){
		unset($checkout_fields['billing']['billing_email']['class'][$k]);
	}
	
	$checkout_fields['billing']['billing_phone']['priority'] = 21;

	$checkout_fields['billing']['billing_company_rut']['label'] = "RUT empresa <abbr class=\"required\" title=\"obligatorio\">*</abbr>";
	$checkout_fields['billing']['billing_company_rut']['required'] = false;
	$checkout_fields['billing']['billing_company_rut']['priority'] = 92;
	$checkout_fields['billing']['billing_company_rut']['class'] = $dte_initial_class;
	
	$checkout_fields['billing']['billing_company']['label'] = "Razón Social <abbr class=\"required\" title=\"obligatorio\">*</abbr>";
	$checkout_fields['billing']['billing_company']['priority'] = 90;
	$checkout_fields['billing']['billing_company']['required'] = false;
	$checkout_fields['billing']['billing_company']['class'] = $dte_initial_class;
	
	$checkout_fields['billing']['billing_postcode']['priority'] = 70;

	
	return $checkout_fields;
}


add_filter( 'woocommerce_checkout_fields', 'emp_bf_set_checkout_fields_conf', 31, 1 );
function emp_bf_set_checkout_fields_conf( $checkout_fields ){
	
	$dte_initial_class = apply_filters('emp_bf_default_flds_class_set',EMP_BF_FLDS_DEFAULT_CLASS_SET);
		
	$checkout_fields['billing']['billing_dte_type'] = array(
		'label' => 'Documento de Compra',
		'priority' => 80,
		'required' => true,
		'class' => array('form-row-wide'),
		'type' => 'radio',
		'options' => array(
			'boleta'     => 'Boleta',
			'factura'    => 'Factura'
		  )
	);

	$checkout_fields['billing']['billing_company_rut'] = array(
		'label' => 'RUT Empresa <abbr class="required" title="obligatorio">*</abbr>',
		'priority' => 90,
		'required' => false,
		'class' => $dte_initial_class
	);
	
	$checkout_fields['billing']['billing_company_business_line'] = array(
		'label' => 'Giro Empresa <abbr class="required" title="obligatorio">*</abbr>',
		'priority' => 91,
		'required' => false,
		'class' => $dte_initial_class
	);
	
	/*$checkout_fields['billing']['billing_company_contact_name'] = array(
		'label' => 'Nombre <abbr class="required" title="obligatorio">*</abbr>',
		'priority' => 92,
		'required' => false,
		'class' => $dte_initial_class
	);*/
	
	/*$checkout_fields['billing']['billing_company_contact_last_names'] = array(
		'label' => 'Nombre <abbr class="required" title="obligatorio">*</abbr>',
		'priority' => 93,
		'required' => false,
		'class' => $dte_initial_class
	);*/
	
	$checkout_fields['billing']['billing_company_address_1'] = array(
		'label' => 'Dirección Empresa <abbr class="required" title="obligatorio">*</abbr>',
		'priority' => 94,
		'required' => false,
		'class' => $dte_initial_class
	);
	
	$checkout_fields['billing']['billing_company_state'] = array(
		'label' => 'Comuna Empresa <abbr class="required" title="obligatorio">*</abbr>',
		'priority' => 95,
		'required' => false,
		'class' => $dte_initial_class
	);
	
	$checkout_fields['billing']['billing_company_city'] = array(
		'label' => 'Ciudad Empresa <abbr class="required" title="obligatorio">*</abbr>',
		'priority' => 96,
		'required' => false,
		'class' => $dte_initial_class
	);
	
	/*$checkout_fields['billing']['billing_company_contact_mobile_phone'] = array(
		'label' => 'Teléfono móvil <abbr class="required" title="obligatorio">*</abbr>',
		'priority' => 97,
		'required' => false,
		'class' => $dte_initial_class
	);

	$checkout_fields['billing']['billing_company_contact_email'] = array(
		'label' => 'Correo electrónico <abbr class="required" title="obligatorio">*</abbr>',
		'priority' => 98,
		'required' => false,
		'class' => $dte_initial_class
	);*/

	return $checkout_fields;
}


/* Genera código JS para cambiar el orden de los campos sin afectar el plugin de chilexpress. */
add_action('wp_head','emp_change_city_state_order_by_js',40);
function emp_change_city_state_order_by_js(){
	$actv_plgs_nms = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
	write_log('Plugins activos en WP:');
	write_log($actv_plgs_nms);

	$fields_sort_required = true;
	$plg_string_idfr_chilexpress = 'chilexpress-woo-oficial/chilexpress-woo-oficial.php';
	if ( !in_array( $plg_string_idfr_chilexpress, $actv_plgs_nms ) ) {
		$fields_sort_required = false;
	}
	
	if(is_checkout() ){
		ob_start();
		?>
		<style>
			.dte_fields_wrapper_hidden {display: none !important; visibility: hidden !important;}
			.dte_fields_wrapper_visible {display: inline-block; visibility: show; float: none !important;}
		</style>
		<script>
			jQuery(function ($) {
				const fields_sort_required = <?php echo $fields_sort_required ? "true" : "false"; ?>;
				const invoiceFields = Array(
					'billing_company_rut_field',
					'billing_company_field',
					'billing_company_business_line_field',
					'billing_company_state_field',
					'billing_company_city_field',
					'billing_company_address_1_field'
				);
				function emp_update_field_order(){
					//debugger;
					
					console.log('emp_update_field_order: Configurando el orden de los campos del checkout...');
					const dteTypeFieldWrapEl = document.getElementById('billing_dte_type_field');
					const postCodeFieldWrapEl = document.getElementById('billing_postcode_field');
					const cityFieldWrapEl = document.getElementById('billing_city_field');
					const stateFieldWrapEl = document.getElementById('billing_state_field');
					const address1FieldWrapEl = document.getElementById('billing_address_1_field');
					const fieldsParent = cityFieldWrapEl.parentNode;
					
					if(fields_sort_required){
						fieldsParent.insertBefore(stateFieldWrapEl,address1FieldWrapEl);
						fieldsParent.insertBefore(cityFieldWrapEl,address1FieldWrapEl);
						fieldsParent.insertBefore(postCodeFieldWrapEl,dteTypeFieldWrapEl);
					}

					postCodeFieldWrapEl.classList.add("dte_fields_wrapper_hidden");
				}

				function setFieldHidden(item, index) {
					const invoiceFldElemnt = document.getElementById(item);
					invoiceFldElemnt.classList.add("dte_fields_wrapper_hidden");
					invoiceFldElemnt.classList.remove("dte_fields_wrapper_visible");
				} 
				function setFieldVisible(item, index) {
					const invoiceFldElemnt = document.getElementById(item);
					invoiceFldElemnt.classList.add("dte_fields_wrapper_visible");
					invoiceFldElemnt.classList.remove("dte_fields_wrapper_hidden");
				}

				function emp_dte_invoice_fields_turn_on(){		
					invoiceFields.forEach(setFieldVisible);
				}

				function emp_dte_invoice_fields_turn_off(){
					invoiceFields.forEach(setFieldHidden);
				}

				function emp_configure_dte_rb(){
					console.log('emp_configure_dte_rb: Configurando el evento "change" del radiobutton billing_dte_type...');
					$("input:radio[name=billing_dte_type]").change(function(){
						console.log('emp_configure_dte_rb: Datos del objeto this (input:radio[name=billing_dte_type]):');
						console.log(this);
						console.log('emp_configure_dte_rb: Valor de this.val: ' + $(this).val() + '.');
						if( $(this).val() == 'factura' ){
							emp_dte_invoice_fields_turn_on();
						} else {
							emp_dte_invoice_fields_turn_off();
						}
					});
				}

				$(document).ready(() => {
					emp_update_field_order();
					emp_configure_dte_rb();
					if($('#billing_dte_type_factura').is(':checked')) { 
						emp_dte_invoice_fields_turn_on();
					} else {
						emp_dte_invoice_fields_turn_off();
					}
				});

			});
		</script>

		<?php
		echo ob_get_clean();
    }
}


function is_valid_text($rs){
	$rs = trim($rs);
	$validation = !empty($rs);
	return $validation;	
}

function is_valid_email($eml){
	$rs = trim($eml);
	$validation = !empty($rs);
	return $validation;	
}

function is_valid_phone($rs){
	$rs = trim($rs);
	$validation = !empty($rs);
	return $validation;	
}

function is_valid_rut($rs){
	$rs = trim($rs);
	$r = strtoupper(preg_replace('/[^k0-9]/i', '', $rs));
    $sub_rut = substr($r, 0, strlen($r) - 1);
    $sub_dv = substr($r, -1);
    $x = 2;
    $s = 0;
    for ($i = strlen($sub_rut) - 1; $i >= 0; $i--) {
        if ($x > 7) {
            $x = 2;
        }
        $s += $sub_rut[$i] * $x;
        $x++;
    }
    $dv = 11 - ($s % 11);
    if ($dv == 10) {
        $dv = 'K';
    }
    if ($dv == 11) {
        $dv = '0';
    }


    if ($dv != $sub_dv) {
    	return false;
    }

    return true;
}


/* Procesa el checkout. */
add_action('woocommerce_checkout_process', 'emp_checkout_field_process');
function emp_checkout_field_process() {
	write_log('woocommerce_checkout_process[emp_checkout_field_process]: Campos en $_POST');
	write_log($_POST);
    // Se revisa si está solicitándose factura o boleta para intentar validar los datos en caso de ser factura.
    if ( $_POST['billing_dte_type'] == 'factura'){
		
		// Validación de la razón social.
		if( !is_valid_text($_POST['billing_company']) )
			wc_add_notice( __( 'Razón Social inválida.' ), 'error' );
		
		// Validación del giro.
		if( !is_valid_text($_POST['billing_company_business_line']) )
			wc_add_notice( __( 'El giro de empresa inválido.'), 'error' );
		
		// Validación de la región
		if( !is_valid_text($_POST['billing_company_state']) )
			wc_add_notice( __( 'Comuna de empresa inválida.'), 'error' );
		
		// Validación de la ciudad/localidad.
		if( !is_valid_text($_POST['billing_company_city']) )
			wc_add_notice( __( 'Ciudad de empresa inválida.'), 'error' );
		
		// Validando la dirección.
		if( !is_valid_text($_POST['billing_company_address_1']) )
			wc_add_notice( __( 'Dirección de empresa inválida.'), 'error' );
		/*
		// Validando el nombre.
		if( !is_valid_text($_POST['billing_company_contact_name']) )
			wc_add_notice( __( 'Nombre de contacto de empresa inválido.'), 'error' );
		
		// Validando los apellidos.
		if( !is_valid_text($_POST['billing_company_contact_last_names']) )
			wc_add_notice( __( 'Apellidos de contacto de empresa inválidos.'), 'error' );
		
		// Validando el teléfono.
		if( !is_valid_phone($_POST['billing_company_contact_mobile_phone']) )
			wc_add_notice( __( 'Teléfono móvil de contacto de empresa inválido.'), 'error' );
		
		// Validando el teléfono.
		if( !is_valid_email($_POST['billing_company_contact_email']) )
			wc_add_notice( __( 'Teléfono móvil de contacto de empresa inválido.'), 'error' );
		*/

		if( !is_valid_rut($_POST['billing_company_rut']))
        	wc_add_notice( __( 'El rut ingresado no es válido' ), 'error' );
	}
}

//add_filter('woocommerce_email_order_meta_keys','emp_email_order_meta_keys',10,1);
add_action( 'woocommerce_email_order_meta', 'emp_email_order_meta', 10, 3 );
function emp_email_order_meta( $order_obj, $sent_to_admin, $plain_text ){
	
	$dteType = get_post_meta($order_obj->get_order_number(), '_billing_dte_type', true);
	if (!empty($dteType)){
		ob_start();
		if ( $plain_text === false ) {
			?> <p><strong>DTE para venta:</strong> <?php echo $dteType; ?></p> <?php
			if( $dteType == 'factura' ){
				$razonSocial = get_post_meta( $order_obj->get_order_number(), '_billing_company', true );
				$giro = get_post_meta( $order_obj->get_order_number(), '_billing_company_business_line', true );
				$region = get_post_meta( $order_obj->get_order_number(), '_billing_company_state', true );
				$locCiudad = get_post_meta( $order_obj->get_order_number(), '_billing_company_city', true );
				$direccion = get_post_meta( $order_obj->get_order_number(), '_billing_company_address_1', true );
				$rut = get_post_meta( $order_obj->get_order_number(), '_billing_company_rut', true );
				?>
				<ul>
					<li><strong>RUT</strong> <?php echo $rut; ?></li>
					<li><strong>Razón Social Empresa</strong> <?php echo $razonSocial; ?></li>
					<li><strong>Giro Empresa:</strong> <?php echo $giro; ?></li>
					<li><strong>Comuna Empresa:</strong> <?php echo $region; ?></li>
					<li><strong>Localidad/Ciudad Empresa:</strong> <?php echo $locCiudad; ?></li>
					<li><strong>Direccón Empresa:</strong> <?php echo $direccion; ?></li>
				</ul>
				<?php
			}
		} else{
			?> DTE para venta: <?php echo $dteType; ?><?php
			if( $dteType == 'factura' ){
				$razonSocial = get_post_meta( $order_obj->get_order_number(), '_billing_company', true );
				$giro = get_post_meta( $order_obj->get_order_number(), '_billing_company_business_line', true );
				$region = get_post_meta( $order_obj->get_order_number(), '_billing_company_state', true );
				$locCiudad = get_post_meta( $order_obj->get_order_number(), '_billing_company_city', true );
				$direccion = get_post_meta( $order_obj->get_order_number(), '_billing_company_address_1', true );
				$rut = get_post_meta( $order_obj->get_order_number(), '_billing_company_rut', true );
				?>
				\t* RUT Empresa: <?php echo $rut; ?>.
				\t* Razón Social Empresa: <?php echo $razonSocial; ?>.
				\t* Giro Empresa: <?php echo $giro; ?>.
				\t* Comuna Empresa: <?php echo $region; ?>.
				\t* Localidad/Ciudad Empresa: <?php echo $locCiudad; ?>.
				\t* Direccón Empresa: <?php echo $direccion; ?>.
				<?php
			}
		}
		$order_meta_billing_data = ob_get_clean();
		echo $order_meta_billing_data;
	}
}

add_action('woocommerce_admin_order_data_after_billing_address','emp_admin_order_data_after_billing_address',9,1);
function emp_admin_order_data_after_billing_address($order){
	$dteType = get_post_meta($order->id, '_billing_dte_type', true);
	if (!empty($dteType)){
		$html_wraper_field_code = '<p><strong>' . __('DTE para venta') . ':</strong> ';
		$html_wraper_field_code .= $dteType;
		$html_wraper_field_code .= '</p>';
		echo $html_wraper_field_code;
		
		if( $dteType == 'factura' ){
			$html_wraper_field_code = '<p><strong>' . __('RUT Empresa') . ':</strong> ';
			$html_wraper_field_code .= get_post_meta($order->id, '_billing_company_rut', true);
			$html_wraper_field_code .= '</p>';
			echo $html_wraper_field_code;

			$html_wraper_field_code = '<p><strong>' . __('Razón Social Empresa') . ':</strong> ';
			$html_wraper_field_code .= get_post_meta($order->id, '_billing_company', true);
			$html_wraper_field_code .= '</p>';
			echo $html_wraper_field_code;
			
			$html_wraper_field_code = '<p><strong>' . __('Giro Empresa') . ':</strong> ';
			$html_wraper_field_code .= get_post_meta($order->id, '_billing_company_business_line', true);
			$html_wraper_field_code .= '</p>';
			echo $html_wraper_field_code;
			
			$html_wraper_field_code = '<p><strong>' . __('Región Empresa') . ':</strong> ';
			$html_wraper_field_code .= get_post_meta($order->id, '_billing_company_state', true);
			$html_wraper_field_code .= '</p>';
			echo $html_wraper_field_code;
			
			$html_wraper_field_code = '<p><strong>' . __('Localidad/Ciudad Empresa') . ':</strong> ';
			$html_wraper_field_code .= get_post_meta($order->id, '_billing_company_city', true);
			$html_wraper_field_code .= '</p>';
			echo $html_wraper_field_code;
			
			$html_wraper_field_code = '<p><strong>' . __('Dirección Empresa') . ':</strong> ';
			$html_wraper_field_code .= get_post_meta($order->id, '_billing_company_address_1', true);
			$html_wraper_field_code .= '</p>';
			echo $html_wraper_field_code;
		}
		
	}
}

?>
