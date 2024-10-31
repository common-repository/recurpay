<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://recurpay.com/
 * @since      1.0.0
 *
 * @package    Recurpay
 * @subpackage Recurpay/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Recurpay
 * @subpackage Recurpay/public
 * @author     Recurpay Dev  <help@recurpay.com>
 */
class Recurpay_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	private function getBaseURL() {
		$recurpay_base_url = "";
		$options = get_option( 'recurpay_options' );
		if( !empty($options['endpoint']) && $options['endpoint'] ) {
			$recurpay_base_url = preg_replace('#^https?://#', '', esc_url($options['endpoint']));
		}

		return 'https://'.$recurpay_base_url;
	}

	private function isDemoMode() {
		$isDemoMode = "";
		$options = get_option( 'recurpay_options' );
		if( !empty($options['debug_mode']) && $options['debug_mode'] ) {
			$isDemoMode = $options['debug_mode'];
		}

		return $isDemoMode;
	}

	private function isDirectCheckout() {
		$options = get_option( 'recurpay_options' );
		if( !empty($options['direct_checkout']) && $options['direct_checkout'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/recurpay-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/recurpay-public.js', array( 'jquery' ), $this->version, false );
	}

	public function add_content_after_addtocart_button_func() {
		/*
		* Content below "Add to cart" Button.
		* Recurpay Widget Handling
		*/

		$options = get_option( 'recurpay_options' );
		if( !empty($options['status']) && $options['status'] == 'true' ) {
			echo '<div class="subscription-field" style="display:none !important;">
					<input type="text" id="subscription-data" name="subscription" value="subscription">
					<input type="text" id="PlanId" name="PlanId">
					</div>
					<div id="recurpay-pdp-widget"></div>';
		}
	}

	public function custom_text_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
		/*
		* Add Property to line item
		* Recurpay Widget Handling
		*/

		$subscription_text = filter_input( INPUT_POST, 'subscription' );
		$PlanId_text = filter_input( INPUT_POST, 'PlanId' );

		if ( empty( $subscription_text ) || empty( $PlanId_text ) ) {
			return $cart_item_data;
		}

		$cart_item_data['subscription'] = $subscription_text;
		$cart_item_data['PlanId'] = $PlanId_text;

		return $cart_item_data;
	}

	public function custom_text_cart( $item_data, $cart_item ) {
		/*
		* Showing line item property on cart
		* Recurpay Widget Handling
		*/

		$options = get_option( 'recurpay_options' );

		if( !empty($options['status']) && $options['status'] == 'true' ) {

			if ( empty( $cart_item['subscription'] ) || empty( $cart_item['PlanId'] ) ) {
				return $item_data;
			}

			//Show if is subscription
			// $item_data[] = array(
			// 	'key'     => __( 'Subscription', 'Subscription' ),
			// 	'value'   => wc_clean( $cart_item['subscription'] ),
			// 	'display' => 'True',
			// );

			//Show Plan ID 
			// $item_data[] = array(
			// 	'key'     => __( 'PlanId', 'PlanId' ),
			// 	'value'   => wc_clean( $cart_item['PlanId'] ),
			// 	'display' => '',
			// );

			return $item_data;
		}
	}

	public function recurpay_widget_js() {

		if(is_product()){

			// Get Current product
			$product = wc_get_product();
			$prd_id = $product->get_id();
			$recurpay_base_url = $this->getBaseURL();
			$debug_mode = $this->isDemoMode() ?? false;

			// Enable Below product id var for testing and change product ID to test Product  
			// $prd_id = 2664701886549;

			$request = wp_remote_get( esc_attr(esc_url($recurpay_base_url)).'/api/storefront/product/'.$prd_id.'/plans.json' );

			$swithToJS = false;

			if ($debug_mode) {
				var_dump($request);
			}

			if( is_wp_error( $request ) ) {
				$swithToJS = true;
				// return false; // Bail early
			}

			if( !$swithToJS ){
				$body = wp_remote_retrieve_body( $request );
				$data = json_decode( $body );
				echo "<script id='recurpay_prd_data'> window.recurpay_data = ".json_encode($data)."</script>";
			}

			?>

			<script type="text/javascript">
				jQuery(document).ready(function() {
					<?php if( $swithToJS ){ ?> 
						window.recurpay_data = window.recurpay_data || {};
						jQuery.ajax({
							url: '<?php echo esc_attr(esc_url($recurpay_base_url)).'/api/storefront/product/'.$prd_id.'/plans.json'?>',
							type: 'GET',
							async: true,
							success: function(data) {
								window.recurpay_data = data;
								recurpaySubscription();
							},
							error: function(data) {
								console.log(error);
								return false;
							}
						});
					<?php } ?>

					function recurpaySubscription(){
						var plan = "";

						function randomStrings() {
							var recurChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
							var recurLength = 24;
							var recurString = '';
							for (var i = 0; i < recurLength; i++) {
								var recurNum = Math.floor(Math.random() * recurChars.length);
								recurString += recurChars.substring(recurNum, recurNum + 1);
							}
							return (recurString);
						}

						var recurToken = "";

						if (typeof(Storage) !== "undefined") {
							if (localStorage.getItem("recurpayTracking") == null) {
								var recurTrack = randomStrings();
								localStorage.setItem("recurpayTracking", recurTrack);
								recurToken = recurTrack;
							} else {
								recurToken = localStorage.getItem("recurpayTracking");
							}
						}

						if(!window.recurpay_data || window.recurpay_data == 'ERROR' || !window.recurpay_data.success || !window.recurpay_data.data ||  !window.recurpay_data.data.plans || !window.recurpay_data.data.plans.length ) {
							return;
						}

						var prepaidCount = 0,
						regularCount = 0,
						prepaidDiscount = [],
						maxPrepaidDiscount = '',
						pdpTemplate = "",
						showTabs = false,
						GetPlans = "",
						tabHTML = "";

						jQuery.each(window.recurpay_data.data.plans, function(key, plan) {
							var DiscountPercentageHtml = '',
								TrialDescriptionHtml = '',
								DescriptionHtml = '',
								PlanHtml = '',
								PlanType = 'regular';

							if(plan.type){
								PlanType = plan.type;

								if(PlanType == 'prepaid'){
									prepaidCount += prepaidCount+1;
									prepaidDiscount.push(plan.discount.value);
								}
								else{
									regularCount += regularCount+1; 
								}
							}
							if(plan.discount.type == "percentage" && plan.discount.value != 0){
								DiscountPercentageHtml = '<span class="recurpay__discount">(' + plan.discount.value + '% Off)</span>';
							}

							if(plan.description) {
								DescriptionHtml = '<div class="recurpay__description">' + plan.description + '</div>';
							}

							if(plan.trial.description) {
								TrialDescriptionHtml  = '<div class="recurpay__description"><span class="recurpay__description--trial">Trial Details : </span>' + plan.trial.description +'</div>';
							}
							
							PlanHtml += '<div class="recurpay__plan" plan-type="'+PlanType+'" plan-id="'+plan.id+'"><label class="recurpay__plan--label">';
							if(key == 0){
								PlanHtml += '<input type="radio" name="recurpay-input" class="recurpay__plan--input" checked="checked" data-plan="' + plan.id + '" data-plan-name="' + plan.name + '" data-plan-type="'+PlanType+'">';
							}
							else{
								PlanHtml += '<input type="radio" name="recurpay-input" class="recurpay__plan--input" data-plan="' + plan.id + '" data-plan-name="' + plan.name + '" data-plan-type="'+PlanType+'">';
							}

							PlanHtml += '<span class="recurpay__plan--checkmark"></span><span class="recurpay__plan--title">' + plan.name + DiscountPercentageHtml+'</span></label>';
							PlanHtml += '<div class="recurpay__plan--description">' + DescriptionHtml + TrialDescriptionHtml + '</div></div>';
							GetPlans += PlanHtml;
						})

						if(regularCount > 0 && prepaidCount >0){
							showTabs = true;
							maxPrepaidDiscount = Math.max.apply(Math,prepaidDiscount);
						}

						if(showTabs){
							tabHTML += '<div class="recurpay-tabs"><div class="recur-tab regular-tab active" plan-type="regular">Regular</div><div class="recur-tab prepaid-tab" plan-type="prepaid"><span>Prepaid</span>'

							if(maxPrepaidDiscount >0 ){
								tabHTML += '<span class="recur-disc-tag">('+maxPrepaidDiscount+'% Off)</span>'
							}
							tabHTML += '</div></div>';
						}

						pdpTemplate += '<div class="recurpay__widget" data-recurpay-widget><div class="new__label"><span class="new__label--text">New</span></div><div class="recurpay__content">'
						pdpTemplate += '<label class="recurpay__label"><div class="recurpay__label--text"><input type="checkbox" class="recurpay__checkbox" autocomplete="off" data-subscribe-input /><span class="recurpay__checkmark"></span>'
						pdpTemplate += 'Subscribe Now and Save</div>'
						pdpTemplate += '</label><div class="recurpay__plans">'
						pdpTemplate += '<div data-recurpay-plans>'+tabHTML+GetPlans+'</div><div class="recurpay__action"><button type="button" data-recurpay-action>Subscribe Now</button></div>'
						pdpTemplate += '</div></div></div>';
						pdpTemplate += '<input type="hidden" id="recurpay_plan_name" name="" value="">';
						pdpTemplate += '<input type="hidden" id="recurpay_plan_id" name="" value="">';

						jQuery('#recurpay-pdp-widget').html(pdpTemplate);

						function recurProperties(){
							if(jQuery('.recurpay__checkbox').is(":checked")){
								var planName = jQuery('[name="recurpay-input"]:checked').attr('data-plan-name'),
									planId = jQuery('[name="recurpay-input"]:checked').attr('data-plan');

								jQuery('#recurpay_plan_name').attr("name","subscription").val(planName);
								jQuery('#recurpay_plan_id').attr("name","PlanId").val(planId);
							}
							else {
								jQuery('#recurpay_plan_name').attr("name","");
								jQuery('#recurpay_plan_id').attr("name","")
							}
						}

						jQuery(document).on('click',".recur-tab", function(e) {
							e.preventDefault();
							var $this = jQuery(this);
							var subType = $this.attr("plan-type");
							var recurInput = jQuery('.recurpay__plan[plan-type="'+subType+'"]').find("[name='recurpay-input']");

							if($this.hasClass("active")){
								jQuery('.recurpay__plan').hide();
								jQuery('.recurpay__plan[plan-type="'+subType+'"]').show();
							if((jQuery('.recurpay__plan[plan-type="'+subType+'"]').find("[name='recurpay-input']:checked").length) <=0){
								jQuery(recurInput).first().prop("checked",true).change();
							}
							}
							else{
								jQuery('.recur-tab').removeClass("active");
								$this.addClass("active");
								jQuery('.recurpay__plan').hide();
								jQuery('.recurpay__plan[plan-type="'+subType+'"]').show();
								jQuery(recurInput).first().prop("checked",true).change();
							}
						});

						if(jQuery('.recur-tab').length){
							jQuery('.recur-tab')[0].click();
						}

						jQuery(document).on('change',".recurpay__checkbox", function(e) {
							var $this = jQuery(this);
							if($this.is(':checked')){
								jQuery('.single_add_to_cart_button').hide();
								jQuery('.recurpay__plans').show();
							}
							else{
								jQuery('.single_add_to_cart_button').show();
								jQuery('.recurpay__plans').hide();
							}
							recurProperties();
						});

						jQuery(document).on('change',"[name='recurpay-input']", function(e) {
							recurProperties();
						});

						jQuery(document).on('click',"[data-recurpay-action]", function(e) {
							addSubscription();
						});

						function addSubscription(){
							var variantSelector = jQuery(jQuery('[data-recurpay-action]').parents('form')).find('[name="variation_id"]').attr('value') || jQuery(jQuery('[data-recurpay-action]').parents('form')).find('[name="add-to-cart"]').attr('value'),
								quantitySelector = jQuery(jQuery('[data-recurpay-action]').parents('form')).find('[name="quantity"]'),
								subscriptionPlanId = jQuery('[name="recurpay-input"]:checked').attr('data-plan'),
								subscriptionPlanName = jQuery('[name="recurpay-input"]:checked').attr('data-plan-name'),
								subscriptionPlanType = jQuery('[name="recurpay-input"]:checked').attr('data-plan-type'),
								planVariant = parseInt(variantSelector),
								planQuantity = 1,
								planParams = [];

							let token_id = localStorage.getItem("recurpayTracking"); 

							//Get Current User ID
							window.current_user = "guest";

							<?php if ($current_user_id) { ?>
								window.current_user = 'guest' || "<?php echo esc_js($current_user_id); ?>"
							<?php } ?>

							if(quantitySelector.length) {
								planQuantity = parseInt(quantitySelector.val());
							}

							planParams = [{
								variant_id: planVariant,
								quantity: planQuantity,
								plan_id: subscriptionPlanId,
								type: "SUBSCRIPTION",
								properties:[],
								currency: '<?php echo esc_js(get_woocommerce_currency()) ?>'
							}]

							if(subscriptionPlanType == "prepaid"){

								var getCheckoutData = JSON.stringify(planParams),
									getCartAttributes = [],
									cartNote = "";
									checkoutForm = jQuery("<form action='<?php echo esc_attr(esc_url($recurpay_base_url)).'/checkout/initiate.rp'?>' method='post' style='display:none;'>" +
													"<input type='hidden' name='line_items' value='"+getCheckoutData+"' />"+
													"<input type='hidden' name='customer_id' value='"+current_user+"' />" +
													"<input type='hidden' name='token_id' value='"+token_id+"' />" +
													"<input type='hidden' name='note_attributes' value='"+getCartAttributes+"' />" +
													"<input type='hidden' name='note' value='"+cartNote+"' />" +
													"</form>");
									jQuery('body').append(checkoutForm);
									checkoutForm.submit();
							}
							else {
								<?php if( !$this->isDirectCheckout() ) { ?>
									// Click Woocommerce Add to cart
									jQuery(jQuery('[data-recurpay-action]').parents('form')).find('.single_add_to_cart_button').trigger('click');
								<?php } else { ?>
									var getCheckoutData = JSON.stringify(planParams),
									getCartAttributes = [],
									cartNote = "";
									checkoutForm = jQuery("<form action='<?php echo esc_attr(esc_url($recurpay_base_url)).'/checkout/initiate.rp'?>' method='post' style='display:none;'>" +
													"<input type='hidden' name='line_items' value='"+getCheckoutData+"' />"+
													"<input type='hidden' name='customer_id' value='"+current_user+"' />" +
													"<input type='hidden' name='token_id' value='"+token_id+"' />" +
													"<input type='hidden' name='note_attributes' value='"+getCartAttributes+"' />" +
													"<input type='hidden' name='note' value='"+cartNote+"' />" +
													"</form>");
									jQuery('body').append(checkoutForm);
									checkoutForm.submit();
								<?php } ?>
							}
						}
					}
					recurpaySubscription();
				});
			</script>

			<?php
		}
	}

	private function recurpayGetCart(){
		$current_user_id = "guest";
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$current_user_id = $current_user->ID;
		}
		$product_names=array(); 
		?>
		<div id="recurpay__cart_data">
		<script >
			var options = [];
			window.current_user = "<?php echo esc_js($current_user_id); ?>"
			window.subscriptionExist = false;	
			<?php foreach ( WC()->cart->get_cart() as $cart_item ) { ?>
				<?php if ( empty( $cart_item['subscription'] ) || empty( $cart_item['PlanId'] ) ) { ?>
					options.push({
						variant_id: <?php echo esc_js($cart_item['data']->get_id()); ?>,
						quantity: <?php echo esc_js($cart_item['quantity']); ?>,
						type: "ONETIME",
						currency: '<?php echo esc_js(get_woocommerce_currency()) ?>'
					})
				<?php }
				else{ ?>
						options.push({
							variant_id: <?php echo esc_js($cart_item['data']->get_id()); ?>,
							quantity: <?php echo esc_js($cart_item['quantity']) ?>,
							type: "SUBSCRIPTION",
							plan_id: "<?php echo esc_js($cart_item['PlanId']) ?>",
							currency: '<?php echo esc_js(get_woocommerce_currency()) ?>'
						});
					subscriptionExist = true;
				<?php } ?>
			<?php } ?>
		</script>
		</div>
	<?php }

	public function recurpay_cart_data( $fragments ) {
		ob_start();
		$this->recurpayGetCart();
		$fragments['#recurpay__cart_data'] = ob_get_clean();
		return $fragments;
	}

	public function recurpay_cart_script( ) {
		$this->recurpayGetCart();
		$options = get_option( 'recurpay_options' );
		$recurpay_base_url = $this->getBaseURL();
		?>
		<script>
			jQuery('.woocommerce-cart-form .product-quantity .qty').on('keydown', function(ev) {
				if(ev.key === 'Enter') {
					// Avoid form submit
					ev.preventDefault();
				}
			});

			//.wc-proceed-to-checkout .btn, .proceed-to-checkout , .button.checkout.wc-forward , .checkout-button.button
			jQuery(document).on('click','<?php echo esc_attr($options["checkout_class"])?>', function(e) {
				e.preventDefault();
				let token_id = localStorage.getItem("recurpayTracking");

				if(subscriptionExist){
					var getCheckoutData = JSON.stringify(options),
						getCartAttributes = [],
						cartNote = "";
						checkoutForm = jQuery("<form action='<?php echo  esc_attr(esc_url($recurpay_base_url)).'/checkout/initiate.rp'?>' method='post' style='display:none;'>" +
										"<input type='hidden' name='line_items' value='"+getCheckoutData+"' />"+
										"<input type='hidden' name='customer_id' value='"+current_user+"' />" +
										"<input type='hidden' name='token_id' value='"+token_id+"' />" +
										"<input type='hidden' name='note_attributes' value='"+getCartAttributes+"' />" +
										"<input type='hidden' name='note' value='"+cartNote+"' />" +
										"</form>");
						jQuery('body').append(checkoutForm);
						checkoutForm.submit();
				}
				else{
					location.href = "/checkout/"
				}
			})
		</script>
    <?php
	}

	// My Account
	public function recurpay_subscription() {
		add_rewrite_endpoint( 'recurpaysubscription', EP_ROOT | EP_PAGES );
	}

	public function subscriptions_query_vars( $vars ) {
		$vars[] = 'recurpaysubscription';
		return $vars;
	}

	public function subscription_link_my_account( $items ) {
		
		//Place the menu at the third Place
		$recurpaysubscription_items = array('recurpaysubscription' => __( 'Subscriptions', 'recurpay' ));
		$recurpaysubscription_items = array_slice( $items, 0, 2, true ) + $recurpaysubscription_items + array_slice( $items, 2, count( $items ), true );
		return $recurpaysubscription_items;
	}

	public function subscription_content() { ?>
		<h3>Manage Subscriptions</h3>
		<div class="subscription-box"></div>
		<?php
	}

	public function recurpay_flush_rewrite_rules() {
		flush_rewrite_rules();
	}

	public function subscription_account(){

		$current_userdata_id = "guest";
		if ( is_user_logged_in() ) {
			$current_userdata = wp_get_current_user();
			$current_userdata_id = $current_userdata->ID;
		}

		$recurpay_base_url = $this->getBaseURL();
	?>
	<script>
		jQuery(document).ready(function() {
			var current_userid = "<?php echo esc_attr($current_userdata_id); ?>"

			jQuery.ajax({
				url: '<?php echo esc_attr(esc_url($recurpay_base_url)) ?>/api/storefront/account/customers/'+current_userid+'/subscriptions/count.json',
				type: 'get',
				success: function( data, textStatus, jQxhr ){
					accountTemplate = "";
					if( data != "ERROR" && data.success && data.data.subscription ) {
						accountTemplate += '<div class="subscription__account" data-subscription-block>'
						accountTemplate += '<div class="subscription__account--ribbon">New</div>'
						accountTemplate += '<div class="subscription__account--content">'
						accountTemplate += '<div class="subscription__account--heading">Subscriptions <span data-subscription-count>('+data.data.subscription.count+')</span></div>'
						if(data.data.subscription.count !== 0){
							accountTemplate += '<div class="subscription__account--text" data-active-subscriptions>Pause, reschedule or cancel at your convenience.</div>' 
							accountTemplate += '<div class="subscription__account--button" id="recurpay-manage-subscriptions"><a href="#">Manage Subscriptions</a></div>'
						}
						else{
							accountTemplate += '<div class="subscription__account--text" data-active-subscriptions>There are no active subscriptions</div>' 
							accountTemplate += '<div class="subscription__account--button"><a href="/">Start Subscribing</a></div>'
						}
						accountTemplate += '</div></div>'

						jQuery('.subscription-box').html(accountTemplate);
					}
				},
				error: function( jqXhr, textStatus, errorThrown ){
					console.log( errorThrown );
					jQuery('.subscription-box .active-text').html("Something went wrong");
					jQuery('.subscription-box .start-link').html('<a href="/">Start Subscribing</a>');
				}
			});

			jQuery(document).on('click', '#recurpay-manage-subscriptions', function(e) {
				e.preventDefault();
				var accountForm = jQuery("<form action='<?php echo esc_attr(esc_url($recurpay_base_url)) ?>/storefront/account/authenticate/<?php echo esc_js($current_userdata_id); ?>' method='post' style='display:none;'></form>");
				jQuery('body').append(accountForm);
				accountForm.submit();
			});
		});
	</script>
	<?php
	}
}


