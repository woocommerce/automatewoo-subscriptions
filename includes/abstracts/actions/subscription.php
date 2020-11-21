<?php

namespace AutomateWoo_Subscriptions;

defined( 'ABSPATH' ) || exit;


/**
 * Abstract class to define action on a subscription object.
 *
 * @class Abstract_Action_Subscription
 * @since 1.2.2
 */
abstract class Abstract_Action_Subscription extends \AutomateWoo\Action {

	/**
	 * A subscription is needed so that it can be edited by instances of this action.
	 *
	 * @var array
	 */
	public $required_data_items = array( 'subscription' );

	public function load_admin_details() {
		parent::load_admin_details();
		$this->group = __( 'Subscription', 'automatewoo' );
	}

	/**
	 * Get the subscription passed in by the workflow's trigger.
	 *
	 * @return \WC_Subscription|false
	 */
	protected function get_subscription_to_edit() {
		return $this->workflow->data_layer()->get_subscription();
	}

	/**
	 * Edit the item managed by this class on the subscription passed in the workflow's trigger
	 *
	 * @throws \Exception When there is an error.
	 */
	public function run() {
		$subscription = $this->get_subscription_to_edit();

		if ( ! $subscription ) {
			return;
		}

		$this->edit_subscription( $subscription );
		$this->add_note( $subscription );
	}

	/**
	 * Add a note to record the edit action on the subscription.
	 *
	 * @param \WC_Subscription $subscription Instance of the subscription being edited by this action.
	 */
	protected function add_note( $subscription ) {
		$subscription->add_order_note( $this->get_note( $subscription ), false, false );
	}
}
