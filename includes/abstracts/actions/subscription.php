<?php

namespace AutomateWoo_Subscriptions;

defined( 'ABSPATH' ) || exit;


/**
 * Abstract class to define action on a subscription object.
 *
 * @class Abstract_Action_Subscription
 * @since 1.0
 */
abstract class Abstract_Action_Subscription extends \AutomateWoo\Action {


	/**
	 * A subscription is needed so that it can be edited by instances of this action.
	 *
	 * @var array
	 */
	public $required_data_items = [ 'subscription' ];

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

}