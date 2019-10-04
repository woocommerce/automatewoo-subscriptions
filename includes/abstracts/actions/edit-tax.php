<?php

namespace AutomateWoo_Subscriptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define shared methods to add, remove or update tax line items on a subscription.
 *
 * @class Abstract_Action_Subscription_Edit_Tax
 * @since 1.0
 */
abstract class Abstract_Action_Subscription_Edit_Tax extends \AutomateWoo\Action_Subscription_Edit_Item_Abstract {


	/**
	 * Flag to define whether to include an "All tax rates" option in the tax
	 * method select field for this action.
	 *
	 * @var bool
	 */
	protected $include_all_tax_rate = false;


	/**
	 * Flag to define whether to include the tax rate select field.
	 *
	 * @var bool
	 */
	protected $load_tax_rate_field = true;


	/**
	 * Flag to define whether the instance of this action requires a name text input field.
	 *
	 * @var bool
	 */
	protected $load_name_field = false;


	/**
	 * Add a tax selection field to the action's admin UI for store owners to choose what
	 * tax to edit on the trigger's subscription.
	 *
	 * Optionally also add the quantity input field for the tax if the instance requires it.
	 */
	function load_fields() {

		if ( $this->load_tax_rate_field ) {
			$this->add_tax_select_field();
		}

		if ( $this->load_name_field ) {
			$this->add_name_field();
		}
	}


	/**
	 * Method to get the tax fields input on the workflow.
	 *
	 * @return array
	 */
	protected function get_object_for_edit() {
		return [
			'tax_rate_id'    => $this->get_option( 'tax_rate_id' ),
			'line_item_name' => $this->get_option( 'line_item_name' ),
		];
	}


	/**
	 * Add a tax selection field for this action
	 */
	protected function add_tax_select_field() {

		$tax_rates    = $this->get_tax_rates();
		$rate_options = array();

		foreach ( $tax_rates as $tax_rate_id => $tax_rate ) {
			$rate_options[ $tax_rate_id ] = sprintf( '%1$s (Rate: %2$s%%)', $tax_rate->tax_rate_name, $tax_rate->tax_rate ) ;
		}
		// WC_Tax::get_rate_code( $rate )
		// WC_Tax::get_rate_percent( $rate )

		$select = new \AutomateWoo\Fields\Select();
		$select->set_required();
		$select->set_name( 'tax_rate_id' );
		$select->set_title( __( 'Tax Rate', 'automatewoo-subscriptions' ) );
		$select->set_options( $rate_options );
		$this->add_field( $select );
	}


	/**
	 * Get the codes of all non-AutomateWoo taxs.
	 *
	 * @return array Tax rates (as both key and value of array)
	 */
	protected function get_tax_rates() {
		global $wpdb;

		$tax_rates = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_tax_rates ORDER BY tax_rate_name LIMIT 250", OBJECT_K );

		return $tax_rates;
	}


	/**
	 * Get the title to display on the name field for this action
	 */
	protected function get_name_field_title() {
		return __( 'Custom Tax Label', 'automatewoo-subscriptions' );
	}


	/**
	 * Get the description to display on the name field for this action
	 */
	protected function get_name_field_description() {
		return __( 'Optionally set a custom label for the tax line item added to the subscription. Defaults to label set on tax via WooCommerce > Settings > Tax administration screen.', 'automatewoo-subscriptions' );
	}
}
