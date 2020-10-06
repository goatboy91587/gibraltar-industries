<?php
/**
 * BenchmarkEmail Subscription
 */
if ( !class_exists( 'MTSNB_Benchmark' ) ) :
	class MTSNB_Benchmark {

		const API_URI = '//api.benchmarkemail.com/1.3';

		private $rpc;

		public function __construct() {

			require_once ABSPATH . 'wp-includes/class-IXR.php';

			$this->rpc = new IXR_CLIENT( self::API_URI );
		}

		public function init( $user, $pass ) {

			if ( ! $this->rpc->query( 'login', $user, $pass ) ) {
				return array(
					'status' => 'failed',
					'message' => $this->rpc->getErrorMessage()
				);
			}
			
			return $this->rpc->getResponse();
		}

		public function get_lists( $user, $pass ) {

			$token = $this->init( $user, $pass );

			if ( ! $this->rpc->query( 'listGet', $token, '', 1, 50, '', '' ) ) {
				return array(
					'status' => 'failed',
					'message' => $this->rpc->getErrorMessage()
				);
			}

			$result = $this->rpc->getResponse();

			$lists = array();
			foreach( $result as $list ) {
				$lists[ $list['id'] ] = $list['listname'];
			}

			return $lists;
		}

	  public function subscribe( $data ) {

			$token = $this->init( $data['username'], $data['password'] );

			$vars = array();
			$vars['email'] = $data['email'];

			if ( !empty( $data['name'] ) ) {
				$vars['firstname'] = $data['name'];
			}
			if ( !empty( $data['last_name'] ) ) {
				$vars['lastname'] = $data['last_name'];
			}

			$double_optin = isset( $data['double_optin'] ) && $data['double_optin'] ? '1' : '0';

			if ( ! $this->rpc->query(
				'listAddContactsOptin',
				$token,
				$data['list_name'],
				array( $vars ),
				$double_optin
			) ) {
				return array(
					'status' => 'failed',
					'message' => $this->rpc->getErrorMessage()
				);
			}

			return array(
				'status' => 'subscribed'
			);
		}
	}
endif;