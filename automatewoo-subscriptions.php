<?php
/*
 * Plugin Name: AutomateWoo - Subscriptions Add-on
 * Plugin URI: https://github.com/Prospress/automatewoo-subscriptions/
 * Description: Advanced actions for automating a subscription's lifecycle with AutomateWoo.
 * Author: Prospress Inc.
 * Author URI: https://prospress.com/
 * License: GPLv3
 * Version: 1.0.0
 * Requires at least: 4.0
 * Tested up to: 4.8
 *
 * WC requires at least: 3.0
 * WC tested up to: 3.5
 *
 * GitHub Plugin URI: Prospress/automatewoo-subscriptions
 * GitHub Branch: master
 *
 * Copyright 2018 Prospress, Inc.  (email : freedoms@prospress.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package		AutomateWoo Subscriptions
 * @author		Prospress Inc.
 * @since		1.0
 */

require_once( 'includes/class-pp-dependencies.php' );

if ( false === PP_Dependencies::is_woocommerce_active( '3.0' ) ) {
	PP_Dependencies::enqueue_admin_notice( 'AutomateWoo - Subscriptions Add-on', 'WooCommerce', '3.0' );
	return;
}

if ( false === PP_Dependencies::is_subscriptions_active( '2.4' ) ) {
	PP_Dependencies::enqueue_admin_notice( 'AutomateWoo - Subscriptions Add-on', 'WooCommerce Subscriptions', '2.4' );
	return;
}

if ( false === PP_Dependencies::is_automatewoo_active( '4.4' ) ) {
	PP_Dependencies::enqueue_admin_notice( 'AutomateWoo - Subscriptions Add-on', 'AutomateWoo', '4.4' );
	return;
}

