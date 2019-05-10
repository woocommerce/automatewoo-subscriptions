<?php

namespace AutomateWoo_Subscriptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define shared methods to add, remove or update shipping line items on a subscription.
 *
 * @class Abstract_Action_Subscription_Edit_Shipping
 * @since 1.0
 */
abstract class Abstract_Action_Subscription_Edit_Shipping extends \AutomateWoo\Action_Subscription_Edit_Item_Abstract {


	/**
	 * Flag to define whether to include an "All shipping methods" option in the shipping
	 * method select field for this action.
	 *
	 * @var bool
	 */
	protected $include_all_shipping_method = false;


	/**
	 * Add a shipping selection field to the action's admin UI for store owners to choose what
	 * shipping to edit on the trigger's subscription.
	 *
	 * Optionally also add the quantity input field for the shipping if the instance requires it.
	 */
	function load_fields() {
		$this->add_shipping_select_field();

		if ( $this->load_name_field ) {
			$this->add_name_field();
		}

		if ( $this->load_cost_field ) {
			$this->add_cost_field();
		}
	}


	/**
	 * Method to get the shipping fields input on the workflow.
	 *
	 * @return array
	 */
	protected function get_object_for_edit() {
		return [
			'shipping_method_id' => $this->get_option( 'shipping_method_id' ),
			'line_item_name'     => $this->get_option( 'line_item_name' ),
			'line_item_cost'     => $this->get_option( 'line_item_cost', true ),
		];
	}


	/**
	 * Add a shipping selection field for this action
	 */
	protected function add_shipping_select_field() {
		$select = new \AutomateWoo\Fields\Select();
		$select->set_required();
		$select->set_name( 'shipping_method_id' );
		$select->set_title( __( 'Shipping Method', 'automatewoo-subscriptions' ) );
		$select->set_options( $this->get_shipping_method_titles() );
		$this->add_field( $select );
	}


	/**
	 * Get the codes of all non-AutomateWoo shippings.
	 *
	 * @return array Shipping codes (as both key and value of array)
	 */
	protected function get_shipping_method_titles() {

		$shipping_method_titles = [];

		if ( $this->include_all_shipping_method ) {
			$shipping_method_titles['all'] = __( 'All shipping methods', 'automatewoo-subscriptions' );
		}

		foreach ( $this->get_shipping_methods() as $shipping_method_id => $shipping_method ) {
			$shipping_method_titles[ $shipping_method_id ] = $shipping_method->get_method_title();
		}

		return $shipping_method_titles;
	}


	/**
	 * Get the codes of all non-AutomateWoo shippings.
	 *
	 * @return \WC_Shipping_Method[]
	 */
	protected function get_shipping_methods() {
		return WC()->shipping() ? WC()->shipping->load_shipping_methods() : [];
	}


	/**
	 * Get the title to display on the name field for this action
	 */
	protected function get_name_field_title() {
		return __( 'Custom Shipping Name', 'automatewoo-subscriptions' );
	}


	/**
	 * Get the description to display on the name field for this action
	 */
	protected function get_name_field_description() {
		return __( 'Optionally set a custom name for the shipping line item added to the subscription. Defaults to "shipping".', 'automatewoo-subscriptions' );
	}


	/**
	 * Get the title to display on the price field for this action
	 */
	protected function get_cost_field_title() {
		return __( 'Custom Shipping Cost', 'automatewoo-subscriptions' );
	}


	/**
	 * Get the description to display on the price field for this action
	 */
	protected function get_cost_field_description() {
		return __( 'Optionally set an amount to use for the shipping cost. Do not include a currency symbol. Total line item cost will be this amount + tax. Defaults to zero.', 'automatewoo-subscriptions' );
	}
}
