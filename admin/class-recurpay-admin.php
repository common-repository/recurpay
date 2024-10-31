<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://recurpay.com/
 * @since      1.0.0
 *
 * @package    Recurpay
 * @subpackage Recurpay/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Recurpay
 * @subpackage Recurpay/admin
 * @author     Recurpay Dev  <help@recurpay.com>
 */
class Recurpay_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Recurpay_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Recurpay_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/recurpay-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Recurpay_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Recurpay_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/recurpay-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function recurpay_options_page() {
		add_menu_page(
			'Recurpay Settings',
			'Recurpay',
			'manage_options',
			'recurpay',
			array($this, 'recurpay_render_plugin_settings_page'),
			'dashicons-admin-generic',
			60
		);
	}

	public function recurpay_render_plugin_settings_page() {
		?>
		<ul role="tablist" class="setting-tabs mega-tabs">
			<li role="presentation" class="setting-tab-container"><button id="app-landing" role="tab" type="button" tabindex="0" class="btn-tab setting-tabs-tab  mega-tabs setting-tabs-tab--selected" aria-selected="true" aria-controls="app-landing-content" aria-label="Intro"><span class="setting-tabs-title">Getting Started</span></button></li>
			<li role="presentation" class="setting-tab-container"><button id="app-settings" role="tab" type="button" tabindex="-1" class="btn-tab setting-tabs-tab " aria-selected="false" aria-controls="app-settings-content" aria-label="Settings"><span class="setting-tabs-title">Settings</span></button></li>
			<a href="#" id="chat-widget">
				<button type="button" class="setting-button setting-button--primary setup-btn start-config btn--primary">
					<span class="setting-button__Content">
						<span class="setting-button__Text">Live Chat</span>
					</span>
				</button>
			</a>
		</ul>
		
		<div class="setting-tabs-panel mega-panel" id="app-landing-content" data-tab="app-landing" role="tabpanel" aria-labelledby="Intro" tabindex="-1" style="display: block;">
			<div class="setting-cards-section">
				<div class="setting-card">
					<div class="setting-callout-container">
						<div class="setting-cards-section">
							<div class="setting-callout-card">
								<div class="setting-callout-card-content">
									<div class="setting-callout-card-title">
										<h2 class="setting-heading">Welcome to Recurpay</h2>
									</div>
									<div class="setting-text-container">
										<p>Recurpay has built this subscription app to help you get started with subscriptions and recurring payments within a few minutes. </p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="setting-callout-container">
						<div class="setting-cards-section">
							<div class="setting-callout-card">
								<div class="setting-callout-card-content">
									<div class="setting-callout-card-title">
										<h2 class="setting-heading featured-heading">Features</h2>
									</div>
									<div class="featured-section">
										<ul class="setting-list">
											<li class="setting-list-item">
												<span class="featured-subhead">Supports both one time & subscription products together</span><br>
												<span class="featured-subtext">
													Let your customers checkout for One-Time and Subscription products at the same time with this subscription app.
												</span>
											</li>
											<li class="setting-list-item">
												<span class="featured-subhead">Best in class subscription management app for your customers.</span><br>
												<span class="featured-subtext">
													Let your customers skip, reschedule, cancel and add/remove products right from their account without reaching out to you. They can also add a one time product in their upcoming subscription delivery with no need to check out once again :)
												</span>
											</li>
											<li class="setting-list-item">
												<span class="featured-subhead">Capture recurring payments for subscriptions with automatic billing</span>
												<span class="featured-subtext">
													Automatically bill the customer card used on checkout to create recurring orders for subscriptions. No manual checkouts every time.
												</span>
											</li>
										</ul>
									</div>
									<div class="start-setup">
										<a href="https://www.recurpay.com/?signup" class="text-decoration-none" target="_blank">
											<button type="button" class="setting-button setting-button--primary final-config-save">
												<span class="setting-button__Content">
													<span class="setting-button__Text">Signup and get started</span>
												</span>
											</button>
										</a>
									</div>
								</div>
								<img class="featured-img" src="https://cdn.shopify.com/s/files/1/0012/0658/3356/files/dribbbble.jpg?56641" alt="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="setting-tabs-panel mega-panel" id="app-settings-content" data-tab="app-settings"role="tabpanel" aria-labelledby="Intro" tabindex="-1" style="display: none;">
			<div class="setting-cards-section">
				<div class="setting-card">
					<div class="setting-callout-container">
						<div class="setting-cards-section">
							<div class="setting-callout-card">
								<div class="setting-callout-card-content">
									<div class="setting-callout-card-title">
										<h2 class="setting-heading">Settings</h2>
									</div>
									<div class="setting-text-container">
										<p>View and manage your store settings and setup subscriptions.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="setting-callout-container">
						<div class="setting-cards-section">
							<div class="setting-callout-card">
								<div class="setting-callout-card-content">
									<div class="featured-section">
										<form action="options.php" method="post">
											<?php 
											settings_fields( 'recurpay_options' );
											do_settings_sections( 'recurpay_plugin' ); ?>
											<div class="start-setup final-setup-wrapper">
												<button type="submit" class="setting-button setting-button--primary start-config">
													<span class="setting-button__Content">
														<span class="setting-button__Text">Save Changes</span>
													</span>
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function recurpay_register_settings() {

		register_setting( 'recurpay_options', 'recurpay_options',[] );
		add_settings_section( 'api_settings', '', array($this, 'recurpay_section_text'), 'recurpay_plugin' );
		add_settings_field( 'recurpay_setting_endpoint', 'Recurpay URL', array($this,'recurpay_setting_endpoint'), 'recurpay_plugin', 'api_settings' );
		add_settings_field( 'recurpay_checkout_class', 'Checkout Button Custom Class', array($this,'recurpay_checkout_class'), 'recurpay_plugin', 'api_settings' );
		add_settings_field( 'recurpay_setting_status', 'Show subscription widget to customers on store', array($this,'recurpay_setting_status'), 'recurpay_plugin', 'api_settings' );
		add_settings_field( 'recurpay_setting_checkout', 'Redirect customers to checkout directly', array($this,'recurpay_setting_checkout'), 'recurpay_plugin', 'api_settings' );
		add_settings_field( 'recurpay_setting_debug_mode', '', array($this,'recurpay_setting_debug_mode'), 'recurpay_plugin', 'api_settings' );

	}

	public function recurpay_section_text() {
		echo '';
	}

	public function recurpay_setting_endpoint() {
		$options = get_option( 'recurpay_options' );
		echo ' <div class="label-group-box">
        <input type="text" class="input-box" placeholder="https://recurpay.store.com/" name="recurpay_options[endpoint]" value="'.(empty($options['endpoint'])?"":$options['endpoint']).'" />
    	</div>';
	}

	public function recurpay_checkout_class(){
		$options = get_option( 'recurpay_options' );
		echo '<div class="label-group-box">
        <input type="text" class="input-box" placeholder="button.checkout-button, .wc-forward" name="recurpay_options[checkout_class]" value="'.(empty($options['checkout_class'])?"":$options['checkout_class']).'" />
    	</div>';
	}

	public function recurpay_setting_status() {
		$options = get_option( 'recurpay_options' );

		echo '<div class="label-group-box">
			<label class="checkbox-label">
				<input name="recurpay_options[status]" class="toggle-checkbox filled-in custom-filled-checkbox" type="checkbox" '.(empty($options['status'])?"":"checked").' />
				<span class="checkbox-description">Show subscription widget to customers on store</span>
			</label>
    	</div>';
	}

	public function recurpay_setting_checkout() {
		$options = get_option( 'recurpay_options' );
		echo '<div class="label-group-box">
			<label class="checkbox-label">
				<input name="recurpay_options[direct_checkout]" class="toggle-checkbox filled-in custom-filled-checkbox" type="checkbox" '.(empty($options['direct_checkout'])?"":"checked").' />
				<span class="checkbox-description">Redirect customers to checkout directly</span>
			</label>
		</div>';
	}

	public function recurpay_setting_debug_mode() {
		$options = get_option( 'recurpay_options' );
		echo '<div class="label-group-box" style="display:none;">
        <label class="checkbox-label">
            <input name="recurpay_options[debug_mode]" class="toggle-checkbox filled-in custom-filled-checkbox" type="checkbox" '.(empty($options['debug_mode'])?"":"checked").' />
            <span class="checkbox-description">Debug Mode</span>
        </label>
    	</div>';
	}

}
