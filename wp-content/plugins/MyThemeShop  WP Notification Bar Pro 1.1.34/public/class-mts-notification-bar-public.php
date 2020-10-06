<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    MTSNB
 * @subpackage MTSNB/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    MTSNB
 * @subpackage MTSNB/public
 * @author     Your Name <email@example.com>
 */
class MTSNB_Public {

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
	 * @param    string    $plugin_name    The name of the plugin.
	 * @param    string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Add image sizes for posts thumbs in notification bar.
	 *
	 * @since    1.0.0
	 */
	public function add_image_sizes() {

		add_image_size( 'mtsnb-thumb', 50, 50, true );
	}

	/**
	 * Set lastvisited cookie for setting New label in Slide-in Notification
	 *
	 * @since    1.1.22
	 */
	public function add_lastvisited_cookie() {
		if(!isset($_COOKIE['mtsnb_lastvisited'])) {
			$secure = ( 'https' === parse_url( home_url(), PHP_URL_SCHEME ) );
			$time = current_time('timestamp');
			setcookie( 'mtsnb_lastvisited', $time, time() + (10 * 365 * 24 * 60 * 60), COOKIEPATH, COOKIE_DOMAIN, $secure );
		}
	}

	/**
	 * Ajax newsletter
	 *
	 * @since    1.0.0
	 */
	public function add_email() {

		// No First Name
		if (!isset($_POST['first_name'])) {
			$_POST['first_name'] = '';
		}

		// No Last Name
		if (!isset($_POST['last_name'])) {
			$_POST['last_name'] = '';
		}

		if(isset($_POST['consent']) && $_POST['consent'] === 'no') {
			echo json_encode(array(
				'status'	=> 'warning',
				'message'	=> apply_filters('mtsnb_required_consent_error', __( 'Please check the consent checkbox.', $this->plugin_name )),
			));

			die();
		}

		$b_prefix = $_POST['ab_variation'] === 'b' ? 'b_' : '';

		$meta_values = get_post_meta( $_POST['bar_id'], '_mtsnb_data', true );

		$success_mesage = stripcslashes( $meta_values[$b_prefix.'newsletter_success_text'] );

		// MailChimp
		if ($_POST['type'] == 'MailChimp') {

			require(MTSNB_PLUGIN_DIR . 'includes/mailchimp/MailChimp.php');

			if (!isset($meta_values[$b_prefix.'MailChimp_api_key']) || $meta_values[$b_prefix.'MailChimp_api_key'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'MailChimp account is not setup properly.', $this->plugin_name ),
				));

				die();
			}

			if (!isset($meta_values[$b_prefix.'MailChimp_list']) || $meta_values[$b_prefix.'MailChimp_list'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'MailChimp: No list specified.', $this->plugin_name ),
				));

				die();
			}

			$MailChimp = new WPS_MailChimp($meta_values[$b_prefix.'MailChimp_api_key']);
			$double_optin = isset( $meta_values[$b_prefix.'MailChimp_single_optin'] ) ? false : true;
			$result = $MailChimp->call('lists/subscribe', array(
                'id'                => $meta_values[$b_prefix.'MailChimp_list'],
                'email'             => array('email'=>$_POST['email']),
                'merge_vars'        => array('FNAME'=>$_POST['first_name'], 'LNAME'=>$_POST['last_name']),
                'double_optin'      => $double_optin,
                'update_existing'   => false,
                'replace_interests' => false,
                'send_welcome'      => true,
            ));

            if ($result) {

	            if (isset($result['email'])) {

					echo json_encode(array(
						'status'		=> 'check',
						'message'		=> $success_mesage,
					));

					die();
	            }

	            else if (isset($result['status']) && $result['status'] == 'error') {
					echo json_encode(array(
						'status'		=> 'warning',
						'message'		=> $result['error'],
					));

					die();
	            }
            } else {

	            echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'Unable to subscribe.', $this->plugin_name ),
				));

				die();
            }
		}

		//SendInBlue
		if ($_POST['type'] == 'sendinblue') {

			require(MTSNB_PLUGIN_DIR . 'includes/sendinblue/SendInBlue.php');

			if (!isset($meta_values[$b_prefix.'sendinblue_api_key']) || $meta_values[$b_prefix.'sendinblue_api_key'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'SendInBlue account is not setup properly.', $this->plugin_name ),
				));
				die();
			}

			if (!isset($meta_values[$b_prefix.'sendinblue_list']) || $meta_values[$b_prefix.'sendinblue_list'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'SendInBlue: No list specified.', $this->plugin_name ),
				));
				die();
			}

			$api = new SendinBlue($meta_values[$b_prefix.'sendinblue_api_key']);
			$result = $api->get_user( array( 'email' => $_POST['email'] ) );
			// user exists already
			$lists = array();
			if( isset( $result['code'] ) && 'success' == $result['code'] ) {
				if( !empty( $result['data']['listid'] ) ) {
					$lists = $result['data']['listid'];
				}

		    if ( !in_array( $listId, $lists) ) {
					$lists[] = $listId;
				}
			} else {
				$lists[] = $meta_values[$b_prefix.'sendinblue_list'];
			}
			$result = $api->create_update_user(
				array(
	        'email' => $_POST['email'],
	        'attributes' => array(
						'FIRSTNAME' => isset($_POST['first_name']) ? $_POST['first_name'] : '',
					),
        	'listid' => $lists
  			));

				if( isset( $result['code'] ) && 'success' != $result['code'] ) {
					echo json_encode(array(
						'status'	=> 'warning',
						'message'	=> $result['message'],
					));
					die();
				}
				$success_mesage = stripcslashes( $meta_values[$b_prefix.'newsletter_success_text'] );
				echo json_encode(array(
					'status'		=> 'check',
					'message'		=> $success_mesage,
				));
				die();
		}

		// ConvertKit
		if ($_POST['type'] == 'convertkit') {

			require(MTSNB_PLUGIN_DIR . 'includes/convertkit/ConvertKit.php');

			if (!isset($meta_values[$b_prefix.'convertkit_api_key']) || $meta_values[$b_prefix.'convertkit_api_key'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'ConvertKit account is not setup properly.', $this->plugin_name ),
				));
				die();
			}

			if (!isset($meta_values[$b_prefix.'convertkit_list']) || $meta_values[$b_prefix.'convertkit_list'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'ConvertKit: No list specified.', $this->plugin_name ),
				));
				die();
			}
			$email = $_POST['email'];
			$name = $_POST['first_name'];
			$convertkit = new MTSNB_ConvertKitApi($meta_values[$b_prefix.'convertkit_api_key']);
			$subscription = $convertkit->subscribeToAForm($meta_values[$b_prefix.'convertkit_list'], $email, $name);

			$subscription_details = json_decode($subscription, 1);
			$subscription_details = $subscription_details['subscription'];
			if( !isset( $subscription_details['subscriber'] )) {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> $subscription_details['state'],
				));
			} else {
				$success_mesage = stripcslashes( $meta_values[$b_prefix.'newsletter_success_text'] );
				echo json_encode(array(
					'status'	=> 'check',
					'message'	=> $success_mesage,
				));

			}

			die();
		}

		// Drip
		if ($_POST['type'] == 'drip') {

			if (!isset($meta_values[$b_prefix.'drip_account_id']) || $meta_values[$b_prefix.'drip_account_id'] == '' || !isset($meta_values[$b_prefix.'drip_api_key']) || $meta_values[$b_prefix.'drip_api_key'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'Drip account is not setup properly.', $this->plugin_name ),
				));
				die();
			}

			if (!isset($meta_values[$b_prefix.'drip_list']) || $meta_values[$b_prefix.'drip_list'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'Drip: No list specified.', $this->plugin_name ),
				));
				die();
			}

			require(MTSNB_PLUGIN_DIR . 'includes/drip/drip.php');
			$drip = new MTSNB_DripApi($meta_values[$b_prefix.'drip_api_key']);
			$double_optin = isset( $meta_values[$b_prefix.'benchmark_single_optin'] ) ? false : true;

			$result = $drip->subscribe_subscriber( array(
				'account_id'	=> $meta_values[$b_prefix.'drip_account_id'],
				'campaign_id'	=> $meta_values[$b_prefix.'drip_list'],
				'email'			=> $_POST['email'],
				'custom_fields'	=> array('name' => $_POST['first_name']),
				'double_optin'	=> $double_optin
			));
			if ( false === $result && $drip->get_error_message() ) {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> $drip->get_error_message(),
				));
			} else {
				$success_mesage = $success_mesage = stripcslashes( $meta_values[$b_prefix.'newsletter_success_text'] );
				echo json_encode( array(
					'status' => 'check',
					'message' => $success_mesage,
				));
			}
			die();
		}

		// BenchmarkEmail
		if ($_POST['type'] == 'benchmark') {

			require(MTSNB_PLUGIN_DIR . 'includes/benchmarkemail/BenchmarkEmail.php');

			if (!isset($meta_values[$b_prefix.'benchmark_username']) || $meta_values[$b_prefix.'benchmark_username'] == '' || !isset($meta_values[$b_prefix.'benchmark_password']) || $meta_values[$b_prefix.'benchmark_password'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'BenchmarkEmail account is not setup properly.', $this->plugin_name ),
				));
				die();
			}

			if (!isset($meta_values[$b_prefix.'benchmark_list']) || $meta_values[$b_prefix.'benchmark_list'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'BenchmarkEmail: No list specified.', $this->plugin_name ),
				));
				die();
			}

			$Benchmark = new MTSNB_Benchmark();
			$double_optin = isset( $meta_values[$b_prefix.'benchmark_single_optin'] ) ? false : true;
			$data = array(
				'username' => $meta_values[$b_prefix.'benchmark_username'],
				'password' => $meta_values[$b_prefix.'benchmark_password'],
				'email' => $_POST['email'],
				'name' => isset($_POST['first_name']) ? $_POST['first_name'] : '',
				'last_name' => isset($_POST['last_name']) ? $_POST['last_name'] : '',
				'double_optin' => $double_optin,
				'list_name' => $meta_values[$b_prefix.'benchmark_list'],
			);
			$result = $Benchmark->subscribe($data);

			if ($result) {

				if (isset($result['status']) && $result['status'] == 'subscribed') {
					echo json_encode(array(
						'status'		=> 'check',
						'message'		=> $success_mesage,
					));
					die();
				} else {
					echo json_encode(array(
						'status'	=> 'warning',
						'message'	=> $result['message'],
					));
				}
			} else {

				echo json_encode(array(
				'status'	=> 'warning',
				'message'	=> __( 'Unable to subscribe.', $this->plugin_name ),
				));

				die();
			}
		}

		// add email to active_campaign
		if ($_POST['type'] == 'active_campaign') {

			require_once( MTSNB_PLUGIN_DIR . 'includes/activecampaign/ActiveCampaign.class.php');

			if (!isset($meta_values[$b_prefix.'active_campaign_api_key']) || $meta_values[$b_prefix.'active_campaign_api_key'] == '' || !isset($meta_values[$b_prefix.'active_campaign_api_url']) || $meta_values[$b_prefix.'active_campaign_api_url'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'ActiveCampaign account is not setup properly.', $this->plugin_name ),
				));

				die();
			}

			if (!isset($meta_values[$b_prefix.'active_campaign_list']) || $meta_values[$b_prefix.'active_campaign_list'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'ActiveCampaign: No list specified.', $this->plugin_name ),
				));

				die();
			}

			$email = $_POST['email'];
			$list_id = $meta_values[$b_prefix.'active_campaign_list'];

			$active_campaign = new ActiveCampaign($meta_values[$b_prefix.'active_campaign_api_url'], $meta_values[$b_prefix.'active_campaign_api_key']);
			$lists = $active_campaign->api('list/list?ids=all');
			$response = $active_campaign->api('contact/view?email=' . $email);
			$exists = isset( $response->id );

			$data = array();
			$data['email'] = $email;
			$data['ip4'] = $_SERVER['REMOTE_ADDR'];
			if ( isset($_POST['first_name']) && !empty($_POST['first_name']) ) {
				$data['first_name'] = $_POST['first_name'];
			}
			$data["status[{$list_id}]"] = 1;
			$data["instantresponders[{$list_id}]"] = 1;

			// already exits
			if ( $exists ) {
				$lists = explode( '-', $response->listslist );

				if ( !in_array( ''.$list_id, $lists ) ) {

					$data['id'] = $response->id;

					$lists[] = $list_id;
					foreach($lists as $list_id) {
						$data["p[{$list_id}]"] = $list_id;
					}

					$active_campaign->api('contact/edit', $data);

				}
				echo json_encode( array(
					'status' => 'check',
					'message' => $success_mesage,
				));
				die();
			}

			$data["p[{$list_id}]"] = $list_id;
			$result = $active_campaign->api('contact/add', $data);

			if ($result) {

				if (isset( $result->success ) && isset( $result->subscriber_id )) {

					echo json_encode(array(
						'status'		=> 'check',
						'message'		=> $success_mesage,
					));

					die();
				} else if (isset( $result->error )) {
					echo json_encode(array(
					'status'		=> 'warning',
					'message'		=> $result->error,
					));

					die();
				}
			} else {

				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'Unable to subscribe.', $this->plugin_name ),
				));

				die();
			}
		}

		// add email to constant_contact
		if ($_POST['type'] == 'constant_contact') {

			$options = array(
				'api_key' => $meta_values[$b_prefix.'constant_contact_api_key'],
				'token'	=> $meta_values[$b_prefix.'constant_contact_token'],
				'list_id' => $meta_values[$b_prefix.'constant_contact_list'],
			);
			$identity = array(
				'email' => $_POST['email'],
			);
			if ( isset($_POST['first_name']) && !empty($_POST['first_name']) ) {
				$identity['name'] = $_POST['first_name'];
			}
			require_once( MTSNB_PLUGIN_DIR . 'includes/constant-contact/ConstantContact.class.php');
			$api = new MTSNB_ConstantContact();
			$status = $api->subscribe($identity, $options);
			if($status['status'] == 'warning') {
				echo json_encode( array(
					'status' => 'warning',
					'message' => $status['message'],
				));
			} else {
				echo json_encode( array(
					'status' => 'check',
					'message' => $success_mesage,
				));
			}
			die();
		}

		// Add email to aweber
		else if ($_POST['type'] == 'aweber') {

			require(MTSNB_PLUGIN_DIR . 'includes/aweber/aweber_api.php');

			if (!isset($meta_values[$b_prefix.'aweber']['consumer_key']) || $meta_values[$b_prefix.'aweber']['consumer_key'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> __( 'Aweber account is not setup properly', $this->plugin_name ),
				));

				die();
			}

			$aweber = new AWeberAPI($meta_values[$b_prefix.'aweber']['consumer_key'], $meta_values[$b_prefix.'aweber']['consumer_secret']);

			try {
				$account = $aweber->getAccount($meta_values[$b_prefix.'aweber']['access_key'], $meta_values[$b_prefix.'aweber']['access_secret']);
				$list = $account->loadFromUrl('/accounts/' . $account->id . '/lists/' . $meta_values[$b_prefix.'aweber_list']);

				$subscriber = array(
					'email' 	=> $_POST['email'],
					'name'		=> $_POST['first_name'] . ' ' . $_POST['last_name'],
					'ip' 		=> $_SERVER['REMOTE_ADDR']
				);

				$newSubscriber = $list->subscribers->create($subscriber);

				echo json_encode(array(
					'status'		=> 'check',
					'message'		=> $success_mesage,
				));

				die();

			} catch (AWeberAPIException $exc) {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> $exc->message,
				));

				die();
			}
		}

		// Add email to Get Response
		else if ($_POST['type'] == 'getresponse') {

			require(MTSNB_PLUGIN_DIR . 'includes/getresponse/getresponse.php');

			$api = new GetResponse($meta_values[$b_prefix.'getresponse_api_key']);

			$result = $api->addContact(
				array(
					'campaign'   => array( 'campaignId' => $meta_values[$b_prefix.'getresponse_campaign'] ),
					'name'       => $_POST['first_name'] . ' ' . $_POST['last_name'],
					'email'      => $_POST['email'],
					'dayOfCycle' => 0,
				)
			);

			if( isset( $result->uuid ) || 202 === intval( $api->http_status ) ) {

				die( json_encode( array(
					'status'  => 'check',
					'message' => $success_mesage,
				) ) );
			}

			die( json_encode( array(
				'status'  => 'warning',
				'message' => __( 'Unable to subscribe', $this->plugin_name ),
			) ) );
		}

		// Add email to Campaign Monitor
		else if ($_POST['type'] == 'campaignmonitor') {

			require(MTSNB_PLUGIN_DIR . 'includes/campaignmonitor/csrest_subscribers.php');

			$wrap = new CS_REST_Subscribers($meta_values[$b_prefix.'campaignmonitor_list'], $meta_values[$b_prefix.'campaignmonitor_api_key']);

			// Check if subscribor is already subscribed
			$result = $wrap->get($_POST['email']);

			if ($result->was_successful()) {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> 'You are already subscribed to this list.',
				));

				die();
			}

			$result = $wrap->add(array(
				'EmailAddress' 	=> $_POST['email'],
				'Name' 			=> $_POST['first_name'] . ' ' . $_POST['last_name'],
				'Resubscribe' 	=> true
			));

			if ($result->was_successful()) {

				echo json_encode(array(
					'status'		=> 'check',
					'message'		=> $success_mesage,
				));

				die();

			} else {

				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> $result->response->Message,
				));

				die();
			}
		}

		// Add email to Mad Mimi
		else if ($_POST['type'] == 'madmimi') {

			require(MTSNB_PLUGIN_DIR . 'includes/madmimi/MadMimi.class.php');

			$mailer = new MadMimi($meta_values[$b_prefix.'madmimi_username'], $meta_values[$b_prefix.'madmimi_api_key']);

			// No Email
			if (!isset($_POST['email']) || $_POST['email'] == '') {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> 'No Email address provided.'
				));
				die();
			}

			// Invalid Email Address
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> 'Invalid Email provided.'
				));
				die();
			}

			try {

				// Check if user is already in list
				$result = $mailer->Memberships($_POST['email']);
				$lists  = new SimpleXMLElement($result);

				if ($lists->list) {
					foreach ($lists->list as $l) {
						if ($l['name'] == $meta_values[$b_prefix.'madmimi_list']) {

							echo json_encode(array(
								'status'		=> 'check',
								'message'		=> 'You are already subscribed to this list.',
							));

							die();
						}
					}
			    }

				$result = $mailer->AddMembership($meta_values[$b_prefix.'madmimi_list'], $_POST['email'], array(
					'first_name'	=> $_POST['first_name'],
					'last_name'		=> $_POST['last_name'],
				));

				echo json_encode(array(
					'status'		=> 'check',
					'message'		=> $success_mesage,
				));

				die();

			} catch (RuntimeException $exc) {

				echo json_encode(array(
					'status'	=> 'warning',
					'message'	=> $msg,
				));

				die();
			}
		}

		die();
	}

	/**
	 * Checks if BOT is visiting the site
	 *
	 * @since  1.0.3
	 */
	public function is_bot() {

		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/bot|spider|crawler|curl|^$/i', $_SERVER['HTTP_USER_AGENT'] ) ) {

			return true;

		} else {

			return false;
		}
	}

	/**
	 * Add impression via AJAX
	 *
	 * @since  1.0.3
	 */
	public function add_impression() {

		if ( !isset( $_POST['bar_id'] ) || $this->is_bot() || current_user_can('edit_published_posts') ) {

			die();
		}

		$bar_id = $_POST['bar_id'];

		$mtsnb_stats = get_option( 'mtsnb_stats' );

		if ( isset( $mtsnb_stats[ $bar_id ]['impressions'] ) ) {

			$mtsnb_stats[ $bar_id ]['impressions'] = (int) $mtsnb_stats[ $bar_id ]['impressions'] + 1;

		} else {

			$mtsnb_stats[ $bar_id ]['impressions'] = 1;
		}

		$ab_variation = isset( $_POST['ab_variation'] ) ? $_POST['ab_variation'] : 'none';

		if ( 'none' !== $ab_variation ) {

			if ( 'b' == $ab_variation ) {

				if ( isset( $mtsnb_stats[ $bar_id ]['b_impressions'] ) ) {

					$mtsnb_stats[ $bar_id ]['b_impressions'] = (int) $mtsnb_stats[ $bar_id ]['b_impressions'] + 1;

				} else {

					$mtsnb_stats[ $bar_id ]['b_impressions'] = 1;
				}

			} else {

				if ( isset( $mtsnb_stats[ $bar_id ]['a_impressions'] ) ) {

					$mtsnb_stats[ $bar_id ]['a_impressions'] = (int) $mtsnb_stats[ $bar_id ]['a_impressions'] + 1;

				} else {

					$mtsnb_stats[ $bar_id ]['a_impressions'] = 1;
				}
			}

		}

		update_option( 'mtsnb_stats', $mtsnb_stats );

		die();
	}

	/**
	 * Add click via AJAX
	 *
	 * @since  1.0.3
	 */
	public function add_click() {

		if ( !isset( $_POST['bar_id'] ) || $this->is_bot() || current_user_can('edit_published_posts') ) {

			die();
		}

		$bar_id = $_POST['bar_id'];

		$mtsnb_stats = get_option( 'mtsnb_stats' );

		if ( isset( $mtsnb_stats[ $bar_id ]['clicks'] ) ) {

			$mtsnb_stats[ $bar_id ]['clicks'] = (int) $mtsnb_stats[ $bar_id ]['clicks'] + 1;

		} else {

			$mtsnb_stats[ $bar_id ]['clicks'] = 1;
		}

		$ab_variation = isset( $_POST['ab_variation'] ) ? $_POST['ab_variation'] : 'none';

		if ( 'none' !== $ab_variation ) {

			if ( 'b' == $ab_variation ) {

				if ( isset( $mtsnb_stats[ $bar_id ]['b_clicks'] ) ) {

					$mtsnb_stats[ $bar_id ]['b_clicks'] = (int) $mtsnb_stats[ $bar_id ]['b_clicks'] + 1;

				} else {

					$mtsnb_stats[ $bar_id ]['b_clicks'] = 1;
				}

			} else {

				if ( isset( $mtsnb_stats[ $bar_id ]['a_clicks'] ) ) {

					$mtsnb_stats[ $bar_id ]['a_clicks'] = (int) $mtsnb_stats[ $bar_id ]['a_clicks'] + 1;

				} else {

					$mtsnb_stats[ $bar_id ]['a_clicks'] = 1;
				}
			}

		}

		update_option( 'mtsnb_stats', $mtsnb_stats );

		die();
	}
}
