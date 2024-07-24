<?php

namespace MeuMouse\Flexify_Checkout\Init;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Class for init plugin
 * 
 * @since 1.0.0
 * @version 3.7.3
 * @package MeuMouse.com
 */
class Init {
  
  /**
   * Construct function
   * 
   * @since 1.0.0
   * @version 3.7.0
   * @return void
   */
  public function __construct() {
    // set default options
    add_action( 'admin_init', array( $this, 'flexify_checkout_set_default_options' ) );

    // set default checkout fields options
    add_action( 'admin_init', array( $this, 'set_checkout_fields_steps_options' ) );

    // Inter bank module actions
    add_action( 'admin_init', array( $this, 'inter_bank_module_actions' ) );

    // check if inter bank module is active and exists expire date
    if ( class_exists('Module_Inter_Bank') && ! empty( self::get_setting('inter_bank_expire_date') ) ) {
      // Hook for schedule remind inter bank credentials
      add_action( 'wp_loaded', array( $this, 'schedule_remind_inter_bank_credentials' ) );

      // Hook for send email remind
      add_action( 'remind_expire_inter_bank_credentials_event', array( $this, 'remind_expire_inter_bank_credentials' ) );
    }

    // set to default settings on reset
    add_action( 'admin_init', array( $this, 'reset_plugin_settings' ) );
  }


  /**
   * Set default options
   * 
   * @since 1.0.0
   * @version 3.7.3
   * @return array
   */
  public function set_default_data_options() {
    $options = array(
      'enable_flexify_checkout' => 'yes',
      'enable_autofill_company_info' => 'no',
      'enable_street_number_field' => 'yes',
      'enable_back_to_shop_button' => 'no',
      'enable_skip_cart_page' => 'no',
      'enable_terms_is_checked_default' => 'yes',
      'enable_aditional_notes' => 'no',
      'enable_optimize_for_digital_products' => 'no',
      'enable_link_image_products' => 'no',
      'enable_fill_address' => 'yes',
      'enable_change_product_quantity' => 'yes',
      'enable_remove_product_cart' => 'yes',
      'enable_ddi_phone_field' => 'no',
      'enable_hide_coupon_code_field' => 'no',
      'enable_auto_apply_coupon_code' => 'no',
      'enable_assign_guest_orders' => 'yes',
      'enable_inter_bank_pix_api' => 'no',
      'enable_inter_bank_ticket_api' => 'no',
      'checkout_header_type' => 'logo',
      'search_image_header_checkout' => '',
      'header_width_image_checkout' => '200',
      'unit_header_width_image_checkout' => 'px',
      'text_brand_checkout_header' => 'Checkout',
      'set_primary_color' => '#141D26',
      'set_primary_color_on_hover' => '#33404D',
      'set_placeholder_color' => '#33404D',
      'flexify_checkout_theme' => 'modern',
      'input_border_radius' => '0.5',
      'unit_input_border_radius' => 'rem',
      'h2_size' => '1.5',
      'h2_size_unit' => 'rem',
      'enable_thankyou_page_template' => 'yes',
      'pix_gateway_title' => 'Pix',
      'pix_gateway_description' => 'Pague via transferência imediata Pix a qualquer hora, a aprovação é imediata!',
      'pix_gateway_email_instructions' => 'Clique no botão abaixo para ver os dados de pagamento do seu Pix.',
      'pix_gateway_receipt_key' => '',
      'pix_gateway_expires' => '30',
      'bank_slip_gateway_title' => 'Boleto bancário',
      'bank_slip_gateway_description' => 'Pague com boleto. Aprovação de 1 a 3 dias úteis após o pagamento.',
      'bank_slip_gateway_email_instructions' => 'Clique no botão abaixo para acessar seu boleto ou utilize a linha digitável para pagar via Internet Banking.',
      'bank_slip_gateway_expires' => '3',
      'bank_slip_gateway_footer_message' => 'Pagamento do pedido #{order_id}. Não receber após o vencimento.',
      'inter_bank_client_id' => '',
      'inter_bank_client_secret' => '',
      'inter_bank_debug_mode' => 'no',
      'inter_bank_env_mode' => 'yes',
      'enable_unset_wcbcf_fields_not_brazil' => 'no',
      'enable_manage_fields' => 'no',
      'get_address_api_service' => 'https://viacep.com.br/ws/{postcode}/json/',
      'api_auto_fill_address_param' => 'logradouro',
      'api_auto_fill_address_neightborhood_param' => 'bairro',
      'api_auto_fill_address_city_param' => 'localidade',
      'api_auto_fill_address_state_param' => 'uf',
      'logo_header_link' => get_permalink( wc_get_page_id('shop') ),
      'inter_bank_expire_date' => '',
      'enable_field_masks' => 'yes',
      'enable_display_local_pickup_kangu' => 'no',
      'text_header_step_1' => 'Informações do cliente',
      'text_header_step_2' => 'Endereço de entrega',
      'text_header_step_3' => 'Formas de pagamento',
      'text_header_sidebar_right' => 'Carrinho',
      'text_check_step_1' => 'Contato',
      'text_check_step_2' => 'Entrega',
      'text_check_step_3' => 'Pagamento',
      'text_previous_step_button' => 'Voltar',
      'text_shipping_methods_label' => 'Formas de entrega',
      'set_font_family' => 'inter',
      'font_family' => array(
        'inter' => array(
          'font_name' => esc_html__( 'Inter', 'flexify-checkout-for-woocommerce' ),
          'font_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap',
        ),
        'poppins' => array(
          'font_name' => esc_html__( 'Poppins', 'flexify-checkout-for-woocommerce' ),
          'font_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
        ),
        'montserrat' => array(
          'font_name' => esc_html__( 'Montserrat', 'flexify-checkout-for-woocommerce' ),
          'font_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
        ),
        'open_sans' => array(
          'font_name' => esc_html__( 'Open Sans', 'flexify-checkout-for-woocommerce' ),
          'font_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
        ),
        'rubik' => array(
          'font_name' => esc_html__( 'Rubik', 'flexify-checkout-for-woocommerce' ),
          'font_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap',
        ),
        'roboto' => array(
          'font_name' => esc_html__( 'Roboto', 'flexify-checkout-for-woocommerce' ),
          'font_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap',
        ),
        'lato' => array(
          'font_name' => esc_html__( 'Lato', 'flexify-checkout-for-woocommerce' ),
          'font_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap',
        ),
        'raleway' => array(
          'font_name' => esc_html__( 'Raleway', 'flexify-checkout-for-woocommerce' ),
          'font_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap',
        ),
        'nunito' => array(
          'font_name' => esc_html__( 'Nunito', 'flexify-checkout-for-woocommerce' ),
          'font_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap',
        ),
        'quicksand' => array(
          'font_name' => esc_html__( 'Quicksand', 'flexify-checkout-for-woocommerce' ),
          'font_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap',
        ),
        'urbanist' => array(
          'font_name' => esc_html__( 'Urbanist', 'flexify-checkout-for-woocommerce' ),
          'font_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap',
        ),
      ),
      'check_password_strenght' => 'yes',
      'email_providers_suggestion' => 'yes',
      'set_email_providers' => array(
        'gmail.com',
        'outlook.com',
        'hotmail.com',
        'live.com',
        'yahoo.com',
        'msn.com',
        'icloud.com',
      ),
      'display_opened_order_review_mobile' => 'no',
      'text_contact_customer_review' => '{{ first_name }} {{ last_name }} <br> {{ phone }} <br> {{ email }}',
      'text_shipping_customer_review' => '{{ address_1 }}, {{ number }}, {{ city }} - {{ state }} (CEP: {{ postcode }})',
    );

    return apply_filters( 'flexify_checkout_set_default_options', $options );
  }


  /**
   * Gets the items from the array and inserts them into the option if it is empty,
   * or adds new items with default value to the option
   * 
   * @since 2.3.0
   * @version 3.5.0
   * @return void
   */
  public function flexify_checkout_set_default_options() {
    $default_options = $this->set_default_data_options();
    $get_options = get_option('flexify_checkout_settings', array());

    // if empty settings
    if ( empty( $get_options ) ) {
        update_option( 'flexify_checkout_settings', $default_options );
    } else {
        // iterate for each plugin settings
        foreach ( $get_options as $option => $value ) {
          // iterate for each default settings
          foreach ( $default_options as $index => $option_value ) {
            if ( ! isset( $get_options[$index] ) ) {
              $get_options[$index] = $option_value;
            }
          }
        }

        update_option( 'flexify_checkout_settings', $get_options );
    }
  }


  /**
   * Checks if the option exists and returns the indicated array item
   * 
   * @since 1.0.0
   * @version 2.3.0
   * @param $key | Array key
   * @return mixed | string or false
   */
  public static function get_setting( $key ) {
    $default_options = get_option('flexify_checkout_settings', array());

    // check if array key exists and return key
    if ( isset( $default_options[$key] ) ) {
        return $default_options[$key];
    }

    return false;
  }


  /**
   * Set default options checkout fields
   * 
   * @since 3.0.0
   * @version 3.1.0
   * @return void
   */
  public function set_checkout_fields_steps_options() {
    $get_fields = self::get_wc_native_checkout_fields();
    $get_field_options = get_option('flexify_checkout_step_fields', array());
    $get_field_options = maybe_unserialize( $get_field_options );

    // create options if array is empty
    if ( empty( $get_field_options ) ) {
        $fields = array();

        foreach ( $get_fields as $key => $value ) {
          $fields[$key] = $value;
        }

        update_option('flexify_checkout_step_fields', maybe_serialize( $fields ) );
    } else {
      foreach ( $get_fields as $key => $value ) {
        if ( ! isset( $get_field_options[$key] ) ) {
            $get_field_options[$key] = $value;
        }
      }

      update_option( 'flexify_checkout_step_fields', maybe_serialize( $get_field_options ) );
    }

    /**
     * Add integration with Brazilian Market on WooCommerce plugin
     * 
     * @since 1.0.0
     */
    if ( class_exists('Extra_Checkout_Fields_For_Brazil') && ! isset( $get_field_options['billing_cpf'] ) ) {
      $wcbcf_fields = self::get_wcbcf_fields();
      $get_field_options = maybe_unserialize( $get_field_options );

      // Add Brazilian Market on WooCommerce fields to existing options
      $get_field_options = array_merge( $get_field_options, $wcbcf_fields );
      update_option('flexify_checkout_step_fields', maybe_serialize( $get_field_options ));
    }
  }


  /**
   * Process for remove inter bank files
   * 
   * @since 2.3.0
   * @return void
   */
  public function inter_bank_module_actions() {
    if ( isset( $_POST['exclude_inter_bank_crt_key_files'] ) ) {
        $uploads_dir = wp_upload_dir();
        $upload_path = $uploads_dir['basedir'] . '/flexify_checkout_integrations/';
        $crt_file = get_option('flexify_checkout_inter_bank_crt_file');
        $key_file = get_option('flexify_checkout_inter_bank_key_file');

        // exclude crt file
        if ( ! empty( $crt_file ) ) {
            $file_path = $upload_path . $crt_file;

            if ( file_exists( $file_path ) ) {
              wp_delete_file( $file_path );
            }
        }

        // exclude key file
        if ( ! empty( $key_file ) ) {
            $file_path = $upload_path . $key_file;

            if ( file_exists( $file_path ) ) {
              wp_delete_file( $file_path );
            }
        }

        delete_option('flexify_checkout_inter_bank_crt_file');
        delete_option('flexify_checkout_inter_bank_key_file');
    }

    if ( isset( $_POST['active_inter_bank_module'] ) ) {
      if ( ! function_exists( 'activate_plugin' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
      }

      $plugin_path = 'module-inter-bank-for-flexify-checkout/module-inter-bank-for-flexify-checkout.php';
      $activate = activate_plugin( $plugin_path );

      if ( false === $activate ) {
          $error_message = get_plugin_activation_error( $plugin_path );
          echo '<div class="notice notice-error">
          <p>'. sprintf( esc_html( 'Erro ao ativar o plugin:', 'flexify-checkout-for-woocommerce' ), $error_message ) .'</p>
          </div>';
      } else {
        echo '<div class="notice notice-success">
        <p>'. esc_html( 'O módulo adicional foi ativo com sucesso!', 'flexify-checkout-for-woocommerce' ) .'</p>
        </div>';
      }
    }
  }


  /**
   * Create e-mail for remind admin to change Inter bank credentials
   * 
   * @since 3.2.0
   * @return void
   */
  public function remind_expire_inter_bank_credentials() {
    $to = get_option('admin_email');
    $subject = 'IMPORTANTE: As credenciais da sua aplicação do banco Inter irão expirar em breve!';
    $message = 'Este é um aviso para te lembrar que as credenciais de integração com o Módulo adicional banco Inter para Flexify Checkout para WooCommerce irão expirar em 7 dias. Não esqueça de fazer a atualização das credenciais para não desativar a forma de pagamento em sua loja.';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail( $to, $subject, $message, $headers );
  }


  /**
   * Schedule email for remind admin to change Inter bank credentials
   * 
   * @since 3.2.0
   * @version 3.7.2
   * @return void
   */
  public function schedule_remind_inter_bank_credentials() {
    $expire_date = self::get_setting('inter_bank_expire_date');

    // Convert date to Y-m-d format
    $expire_date_formated = \DateTime::createFromFormat('d/m/Y', $expire_date)->format('Y-m-d');

    // Subtract 7 days from the expiration date
    $send_date_email = date( 'Y-m-d', strtotime( '-7 days', strtotime( $expire_date_formated ) ) );

    // Schedule email sending
    $timestamp_send_email = strtotime( $send_date_email . ' 08:00:00' );
    wp_schedule_single_event( $timestamp_send_email, 'remind_expire_inter_bank_credentials_event' );
  }


  /**
   * Reset settings to default
   * 
   * @since 3.5.0
   * @version 3.7.0
   * @return void
   */
  public function reset_plugin_settings() {
    if ( isset( $_POST['confirm_reset_settings'] ) ) {
      delete_option('flexify_checkout_settings');
      delete_option('flexify_checkout_step_fields');
      delete_option('flexify_checkout_conditions');
      delete_option('flexify_checkout_alternative_license_activation');
      delete_transient('flexify_checkout_api_request_cache');
      delete_transient('flexify_checkout_api_response_cache');
      delete_transient('flexify_checkout_license_status_cached');
    }
  }


  /**
   * Get checkout step fields
   * 
   * @since 3.0.0
   * @return array
   */
  public static function get_wc_native_checkout_fields() {
    $checkout_fields = array(
      'billing_email' => array(
        'id' => 'billing_email',
        'type' => 'email',
        'label' => esc_html__( 'Endereço de e-mail', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'required' => 'yes',
        'priority' => '1',
        'source' => 'native',
        'enabled' => 'yes',
        'step' => '1',
      ),
      'billing_first_name' => array(
          'id' => 'billing_first_name',
          'type' => 'text',
          'label' => esc_html__( 'Nome', 'flexify-checkout-for-woocommerce' ),
          'position' => 'left',
          'classes' => '',
          'label_classes' => '',
          'required' => 'yes',
          'priority' => '2',
          'source' => 'native',
          'enabled' => 'yes',
          'step' => '1',
      ),
      'billing_last_name' => array(
        'id' => 'billing_last_name',
        'type' => 'text',
        'label' => esc_html__( 'Sobrenome', 'flexify-checkout-for-woocommerce' ),
        'position' => 'right',
        'classes' => '',
        'label_classes' => '',
        'required' => 'yes',
        'priority' => '3',
        'source' => 'native',
        'enabled' => 'yes',
        'step' => '1',
      ),
      'billing_phone' => array(
        'id' => 'billing_phone',
        'type' => 'tel',
        'label' => esc_html__( 'Telefone', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'input_mask' => '(00) 00000-0000',
        'required' => 'yes',
        'priority' => '4',
        'source' => 'native',
        'enabled' => 'yes',
        'step' => '1',
      ),
      'billing_company' => array(
        'id' => 'billing_company',
        'type' => 'text',
        'label' => esc_html__( 'Empresa', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'required' => 'yes',
        'priority' => '5',
        'source' => 'native',
        'enabled' => 'no',
        'step' => '1',
      ),
      'billing_country' => array(
        'id' => 'billing_country',
        'type' => 'select',
        'label' => esc_html__( 'País', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'required' => 'yes',
        'priority' => '14',
        'source' => 'native',
        'enabled' => 'yes',
        'step' => '2',
      ),
      'billing_postcode' => array(
        'id' => 'billing_postcode',
        'type' => 'tel',
        'label' => esc_html__( 'CEP', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'input_mask' => '00000-000',
        'required' => 'yes',
        'priority' => '15',
        'source' => 'native',
        'enabled' => 'yes',
        'step' => '2',
      ),
      'billing_address_1' => array(
        'id' => 'billing_address_1',
        'type' => 'text',
        'label' => esc_html__( 'Endereço', 'flexify-checkout-for-woocommerce' ),
        'position' => 'left',
        'classes' => '',
        'label_classes' => '',
        'required' => 'yes',
        'priority' => '16',
        'source' => 'native',
        'enabled' => 'yes',
        'step' => '2',
      ),
      'billing_address_2' => array(
        'id' => 'billing_address_2',
        'type' => 'text',
        'label' => esc_html__( 'Apartamento, suíte, unidade, etc.', 'flexify-checkout-for-woocommerce' ),
        'position' => 'right',
        'classes' => '',
        'label_classes' => '',
        'required' => 'no',
        'priority' => '19',
        'source' => 'native',
        'enabled' => 'yes',
        'step' => '2',
      ),
      'billing_city' => array(
        'id' => 'billing_city',
        'type' => 'text',
        'label' => esc_html__( 'Cidade', 'flexify-checkout-for-woocommerce' ),
        'position' => 'left',
        'classes' => '',
        'label_classes' => '',
        'required' => 'yes',
        'priority' => '20',
        'source' => 'native',
        'enabled' => 'yes',
        'step' => '2',
      ),
      'billing_state' => array(
        'id' => 'billing_state',
        'type' => 'select',
        'label' => esc_html__( 'Estado', 'flexify-checkout-for-woocommerce' ),
        'position' => 'right',
        'classes' => '',
        'label_classes' => '',
        'required' => 'yes',
        'priority' => '21',
        'source' => 'native',
        'enabled' => 'yes',
        'step' => '2',
      ),
    );

    return apply_filters( 'flexify_checkout_get_fields', $checkout_fields );
  }


  /**
   * Get fields from Brazilian Market on WooCommerce plugin
   * 
   * @since 3.0.0
   * @return array
   */
  public static function get_wcbcf_fields() {
    return array(
      'billing_persontype' => array(
        'id' => 'billing_persontype',
        'type' => 'select',
        'label' => esc_html__( 'Tipo de Pessoa', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'required' => 'yes',
        'priority' => '6',
        'source' => 'plugin',
        'enabled' => 'yes',
        'step' => '1',
      ),
      'billing_cpf' => array(
        'id' => 'billing_cpf',
        'type' => 'tel',
        'label' => esc_html__( 'CPF', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'input_mask' => '000.000.000-00',
        'required' => 'yes',
        'priority' => '7',
        'source' => 'plugin',
        'enabled' => 'yes',
        'step' => '1',
      ),
      'billing_cnpj' => array(
        'id' => 'billing_cnpj',
        'type' => 'tel',
        'label' => esc_html__( 'CNPJ', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'input_mask' => '00.000.000/0000-00',
        'required' => 'yes',
        'priority' => '8',
        'source' => 'plugin',
        'enabled' => 'yes',
        'step' => '1',
      ),
      'billing_ie' => array(
        'id' => 'billing_ie',
        'type' => 'tel',
        'label' => esc_html__( 'Inscrição Estadual', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'input_mask' => '',
        'required' => 'no',
        'priority' => '9',
        'source' => 'plugin',
        'enabled' => 'no',
        'step' => '1',
      ),
      'billing_cellphone' => array(
        'id' => 'billing_cellphone',
        'type' => 'tel',
        'label' => esc_html__( 'Celular', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'input_mask' => '(00) 00000-0000',
        'required' => 'no',
        'priority' => '10',
        'source' => 'plugin',
        'enabled' => 'no',
        'step' => '1',
      ),
      'billing_rg' => array(
        'id' => 'billing_rg',
        'type' => 'text',
        'label' => esc_html__( 'RG', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'input_mask' => '',
        'required' => 'no',
        'priority' => '11',
        'source' => 'plugin',
        'enabled' => 'no',
        'step' => '1',
      ),
      'billing_birthdate' => array(
        'id' => 'billing_birthdate',
        'type' => 'tel',
        'label' => esc_html__( 'Data de nascimento', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'input_mask' => '',
        'required' => 'no',
        'priority' => '12',
        'source' => 'plugin',
        'enabled' => 'no',
        'step' => '1',
      ),
      'billing_gender' => array(
        'id' => 'billing_gender',
        'type' => 'select',
        'label' => esc_html__( 'Gênero', 'flexify-checkout-for-woocommerce' ),
        'position' => 'full',
        'classes' => '',
        'label_classes' => '',
        'required' => 'no',
        'priority' => '13',
        'source' => 'plugin',
        'enabled' => 'no',
        'step' => '1',
      ),
      'billing_number' => array(
        'id' => 'billing_number',
        'type' => 'number',
        'label' => esc_html__( 'Número da residência', 'flexify-checkout-for-woocommerce' ),
        'position' => 'right',
        'classes' => '',
        'label_classes' => '',
        'required' => 'yes',
        'priority' => '17',
        'source' => 'plugin',
        'enabled' => 'yes',
        'step' => '2',
      ),
      'billing_neighborhood' => array(
        'id' => 'billing_neighborhood',
        'type' => 'text',
        'label' => esc_html__( 'Bairro', 'flexify-checkout-for-woocommerce' ),
        'position' => 'left',
        'classes' => '',
        'label_classes' => '',
        'required' => 'no',
        'priority' => '18',
        'source' => 'plugin',
        'enabled' => 'yes',
        'step' => '2',
      ),
    );
  }
}

new Init();