<?php

/**
 * Action ajax hook functions
 */
add_action( 'wp_ajax_rpp_save_redirection_url',         'rpp_save_redirection_url_for_ajax' ); 
add_action( 'wp_ajax_rpp_save_geo_redirection_url',     'rpp_save_geo_redirection_url_for_ajax' );
add_action( 'wp_ajax_rpp_update_redirection_link',      'rpp_update_redirection_link_for_ajax' );
add_action( 'wp_ajax_rpp_update_geo_redirection_link',  'rpp_update_geo_redirection_link_for_ajax' );
add_action( 'wp_ajax_rpp_delete_redirection_link',      'rpp_delete_redirection_link_for_ajax' );
add_action( 'wp_ajax_rpp_delete_geo_redirection_link',  'rpp_delete_geo_redirection_link_for_ajax' );

/**
 * Save Redirection URL for ajax function
 */
function rpp_save_redirection_url_for_ajax() {

	if ( isset( $_POST['rpp_nonce'] ) ) {
		$ajax_nonce = $_POST['rpp_nonce'];	
		if ( ! wp_verify_nonce( $ajax_nonce, 'rpp-ajax-nonce' ) ) {
			echo json_encode( array(
				'success' => 0,
				'error'   => __("You can not save redirection url.")
			));
			wp_die();
		}
	} else {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not save redirection url.")
		));
		wp_die();
	}

	if ( !current_user_can('edit_others_posts') ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not save redirection url.")
		));
		wp_die();
	}

	if ( !isset( $_POST['source_url'] ) || !isset( $_POST['target_url'] ) || !isset( $_POST['redirection_type'] ) ||!isset($_POST['redirection_url_name']) ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("Source URL or target URL or redirection type or redirection url name is not setted. Refresh and try again.")
		));
		wp_die();
	}

	if ( !filter_var( $_POST['target_url'], FILTER_VALIDATE_URL ) || !filter_var( $_POST['source_url'], FILTER_VALIDATE_URL ) ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("URL is not valid. Try valid url.")
		));
		wp_die();
	}

	global $wpdb;

	$source_url = esc_url_raw( $_POST['source_url'] );
	$target_url = esc_url_raw( $_POST['target_url'] );
	$redirection_type = sanitize_text_field( $_POST['redirection_type'] );
	$redirection_url_name = sanitize_text_field( $_POST['redirection_url_name'] );

	while ( substr( $source_url, -1, 1 ) == '/' ) {
		$source_url = substr( $source_url, 0, -1 );
		if( substr( $source_url, -1, 1 ) != '/' ) {
			break;
		}
	}

	$source_url1 = $source_url;
	$source_url = $source_url . '/';

	$redirection_url_table_name = $wpdb->prefix . 'rpp_redirection_links';

	$existance_checking = $wpdb->get_row( "SELECT * FROM {$redirection_url_table_name} WHERE source_url = '{$source_url}' OR source_url = '{$source_url1}' ;" );

	if ( $existance_checking == NULL || $existance_checking == array() ) {
		$redirection_url_data = array(
			'source_url'       => $source_url,
			'target_url'       => $target_url,
			'redirection_url_name' => $redirection_url_name,
			'redirection_type' => $redirection_type,
			'created_date'     => date("Y-m-d H:i:s"),
		);
		$wpdb->insert( $redirection_url_table_name, $redirection_url_data );
		$insert_id = $wpdb->insert_id;		
	} else {
		$redirection_link_id = $existance_checking->redirection_link_id;
		$redirection_url_data = array(
			'source_url'       => $source_url,
			'target_url'       => $target_url,
			'redirection_url_name' => $redirection_url_name,
			'redirection_type' => $redirection_type,
			'created_date'     => date("Y-m-d H:i:s"),
		);
		$result = $wpdb->update( $redirection_url_table_name, $redirection_url_data, array( 'redirection_link_id' => $redirection_link_id ) );
		if( $result ) {
			$insert_id = $redirection_link_id;
		} else {
			$insert_id = false;
		}
	}	

	if ( $insert_id ) {
		$result_array = array(
			'success' => 1
		);
	} else {
		$result_array = array(
			'success' => 0,
			'error'   => "Some errors. Refresh and try again."
		);
	}

	$result_array = json_encode( $result_array );
	echo $result_array;
	wp_die();
}

/**
 * Update Redirection URL for ajax function
 */
function rpp_update_redirection_link_for_ajax() {
	if ( isset( $_POST['rpp_nonce'] ) ) {
		$ajax_nonce = $_POST['rpp_nonce'];	
		if ( ! wp_verify_nonce( $ajax_nonce, 'rpp-ajax-nonce' ) ) {
			echo json_encode( array(
				'success' => 0,
				'error'   => __("You can not update redirection url.")
			));
			wp_die();
		}
	} else {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not update redirection url.")
		));
		wp_die();
	}

	if ( !current_user_can('edit_others_posts') ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not update redirection url.")
		));
		wp_die();
	}

	if ( !isset( $_POST['source_url'] ) || !isset( $_POST['target_url'] ) || !isset( $_POST['redirection_type'] ) || !isset( $_POST['redirection_link_id'] ) || !is_numeric( $_POST['redirection_link_id'] ) || !isset($_POST['redirection_url_name'])) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not update redirection url.")
		));
		wp_die();
	}

	if ( !filter_var( $_POST['source_url'], FILTER_VALIDATE_URL ) || !filter_var( $_POST['target_url'], FILTER_VALIDATE_URL ) ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("URL is not valid. Try valid url.")
		));
		wp_die();
	}

	global $wpdb;

	$redirection_link_id = sanitize_text_field( $_POST['redirection_link_id'] );
	$source_url          = esc_url_raw( sanitize_text_field( $_POST['source_url'] ) );
	$target_url          = esc_url_raw( sanitize_text_field( $_POST['target_url'] ) );
	$redirection_url_name= sanitize_text_field( $_POST['redirection_url_name'] );
	$redirection_type    = sanitize_text_field( $_POST['redirection_type'] );

	while ( substr( $source_url, -1, 1 ) == '/' ) {
		$source_url = substr( $source_url, 0, -1 );
		if( substr( $source_url, -1, 1 ) != '/' ) {
			break;
		}
	}

	$source_url = $source_url . '/';

	$redirection_url_table_name = $wpdb->prefix . 'rpp_redirection_links';

	$existance_checking = $wpdb->get_row( "SELECT * FROM {$redirection_url_table_name} WHERE redirection_link_id = {$redirection_link_id} ;" );

	if ( $existance_checking != NULL && $existance_checking != array() ) {

		$redirection_url_data = array(
			'source_url'          => $source_url,
			'target_url'          => $target_url,
			'redirection_url_name'=> $redirection_url_name,
			'hits_count'          => 0,
			'unique_hits_count'   => 0,
			'redirection_type'    => $redirection_type,
			'created_date'        => date("Y-m-d H:i:s"),
		);
		$result = $wpdb->update( $redirection_url_table_name, $redirection_url_data, array( 'redirection_link_id' => $redirection_link_id ) );
		if( $result ) {
			$insert_id = $redirection_link_id;
		} else {
			$insert_id = false;
		}
	}	

	if ( $insert_id ) {
		$result_array = array(
			'success' => 1
		);
	} else {
		$result_array = array(
			'success' => 0,
			'error'   => "Some errors. Refresh and try again."
		);
	}

	$result_array = json_encode( $result_array );
	echo $result_array;
	wp_die();
}

/**
 * Delete a Redirection link for ajax function
 */
function rpp_delete_redirection_link_for_ajax() {

	if ( isset( $_POST['rpp_nonce'] ) ) {
		$ajax_nonce = $_POST['rpp_nonce'];	
		if ( ! wp_verify_nonce( $ajax_nonce, 'rpp-ajax-nonce' ) ) {
			echo json_encode( array(
				'success' => 0,
				'error'   => __("You can not delete that link.")
			));
			wp_die();
		}
	} else {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not delete that link.")
		));
		wp_die();
	}

	if ( !current_user_can('edit_others_posts') ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not delete that link.")
		));
		wp_die();
	}

	if ( !isset( $_POST['redirection_link_id'] ) || !is_numeric( $_POST['redirection_link_id'] ) ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("Redirection link id is not valid. Refresh and try again.")
		));
		wp_die();
	}

	global $wpdb;

	$redirection_link_id = sanitize_text_field(  $_POST['redirection_link_id'] );

	$redirection_url_table_name = $wpdb->prefix . 'rpp_redirection_links';

	$result = $wpdb->query( "DELETE  FROM {$redirection_url_table_name} WHERE redirection_link_id = {$redirection_link_id} ;" );

	if ( $result ) {
		$result_array = array(
			'success' => 1
		);
	} else {
		$result_array = array(
			'success' => 0,
			'error'   => "Some errors. Refresh and try again."
		);
	}

	$result_array = json_encode( $result_array );
	echo $result_array;
	wp_die();
}


/**
 * Save Geo Redirection URL for ajax function
 */
function rpp_save_geo_redirection_url_for_ajax() {

	if ( isset( $_POST['rpp_nonce'] ) ) {
		$ajax_nonce = $_POST['rpp_nonce'];	
		if ( ! wp_verify_nonce( $ajax_nonce, 'rpp-ajax-nonce' ) ) {
			echo json_encode( array(
				'success' => 0,
				'error'   => __("You can not save geo redirection url.")
			));
			wp_die();
		}
	} else {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not save geo redirection url.")
		));
		wp_die();
	}

	if ( !current_user_can('edit_others_posts') ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not save geo redirection url.")
		));
		wp_die();
	}

	if ( !isset( $_POST['country'] ) || !isset( $_POST['target_url'] ) || !isset( $_POST['destination_url'] ) || !isset( $_POST['destination_type'] )) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("Country or target URL or destination URL destination type is not setted. Refresh and try again.")
		));
		wp_die();
	}

	if ( !filter_var( $_POST['target_url'], FILTER_VALIDATE_URL ) || !filter_var( $_POST['destination_url'], FILTER_VALIDATE_URL ) ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("URL is not valid. Try valid url.")
		));
		wp_die();
	}

	global $wpdb;

	$country           = sanitize_text_field( $_POST['country'] );
	$target_url        = esc_url_raw( $_POST['target_url'] );
	$destination_url   = esc_url_raw( $_POST['destination_url'] );
	$destination_type  = sanitize_text_field( $_POST['destination_type'] );

	while ( substr( $target_url, -1, 1 ) == '/' ) {
		$target_url = substr( $target_url, 0, -1 );
		if( substr( $target_url, -1, 1 ) != '/' ) {
			break;
		}
	}

	$target_url1 = $target_url;
	$target_url = $target_url . '/';

	$geo_redirection_url_table_name = $wpdb->prefix . 'rpp_geo_redirection_links';

	$existance_checking = $wpdb->get_row( "SELECT * FROM {$geo_redirection_url_table_name} WHERE (target_url = '{$target_url}' OR target_url = '{$target_url1}') AND country = '{$country}' ;" );

	if ( $existance_checking == NULL || $existance_checking == array() ) {
		$geo_redirection_url_data = array(
			'country'          => $country,
			'target_url'       => $target_url,
			'destination_url'  => $destination_url,
			'destination_type' => $destination_type,
			'created_date'     => date("Y-m-d H:i:s"),
		);
		$wpdb->insert( $geo_redirection_url_table_name, $geo_redirection_url_data );
		$insert_id = $wpdb->insert_id;		
	} else {
		$geo_redirection_link_id = $existance_checking->geo_redirection_link_id;
		$geo_redirection_url_data = array(
			'country'          => $country,
			'target_url'       => $target_url,
			'destination_url'  => $destination_url,
			'destination_type' => $destination_type,
			'created_date'     => date("Y-m-d H:i:s"),
		);
		$result = $wpdb->update( $geo_redirection_url_table_name, $geo_redirection_url_data, array( 'geo_redirection_link_id' => $geo_redirection_link_id ) );
		if( $result ) {
			$insert_id = $geo_redirection_link_id;
		} else {
			$insert_id = false;
		}
	}	

	if ( $insert_id ) {
		$result_array = array(
			'success' => 1
		);
	} else {
		$result_array = array(
			'success' => 0,
			'error'   => "Some errors. Refresh and try again."
		);
	}

	$result_array = json_encode( $result_array );
	echo $result_array;
	wp_die();
}

/**
 * Update Geo Redirection URL for ajax function
 */
function rpp_update_geo_redirection_link_for_ajax() {
	if ( isset( $_POST['rpp_nonce'] ) ) {
		$ajax_nonce = $_POST['rpp_nonce'];	
		if ( ! wp_verify_nonce( $ajax_nonce, 'rpp-ajax-nonce' ) ) {
			echo json_encode( array(
				'success' => 0,
				'error'   => __("You can not update geo redirection url.")
			));
			wp_die();
		}
	} else {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not update geo redirection url.")
		));
		wp_die();
	}

	if ( !current_user_can('edit_others_posts') ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not update geo redirection url.")
		));
		wp_die();
	}

	if ( !isset( $_POST['country'] ) || !isset( $_POST['target_url'] ) || !isset( $_POST['destination_url'] ) || !isset( $_POST['destination_type'] ) || !isset( $_POST['geo_redirection_link_id'] ) || !is_numeric( $_POST['geo_redirection_link_id'] ) ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not update geo redirection url.")
		));
		wp_die();
	}

	if ( !filter_var( $_POST['destination_url'], FILTER_VALIDATE_URL ) || !filter_var( $_POST['target_url'], FILTER_VALIDATE_URL ) ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("URL is not valid. Try valid url.")
		));
		wp_die();
	}

	global $wpdb;

	$geo_redirection_link_id = sanitize_text_field( $_POST['geo_redirection_link_id'] );
	$country          = sanitize_text_field( $_POST['country'] );
	$target_url       = esc_url_raw( $_POST['target_url'] );
	$destination_url  = esc_url_raw( $_POST['destination_url'] );
	$destination_type = sanitize_text_field( $_POST['destination_type'] );

	while ( substr( $target_url, -1, 1 ) == '/' ) {
		$target_url = substr( $target_url, 0, -1 );
		if( substr( $target_url, -1, 1 ) != '/' ) {
			break;
		}
	}

	$target_url = $target_url . '/';

	$geo_redirection_url_table_name = $wpdb->prefix . 'rpp_geo_redirection_links';

	$existance_checking = $wpdb->get_row( "SELECT * FROM {$geo_redirection_url_table_name} WHERE geo_redirection_link_id = {$geo_redirection_link_id} ;" );

	if ( $existance_checking != NULL && $existance_checking != array() ) {

		$geo_redirection_url_data = array(
			'country'           => $country,
			'target_url'        => $target_url,
			'destination_url'   => $destination_url,
			'destination_type'  => $destination_type,
			'hits_count'        => 0,
			'unique_hits_count' => 0,
			'created_date'      => date("Y-m-d H:i:s"),
		);
		$result = $wpdb->update( $geo_redirection_url_table_name, $geo_redirection_url_data, array( 'geo_redirection_link_id' => $geo_redirection_link_id ) );
		if( $result ) {
			$insert_id = $geo_redirection_link_id;
		} else {
			$insert_id = false;
		}
	}	

	if ( $insert_id ) {
		$result_array = array(
			'success' => 1
		);
	} else {
		$result_array = array(
			'success' => 0,
			'error'   => "Some errors. Refresh and try again."
		);
	}

	$result_array = json_encode( $result_array );
	echo $result_array;
	wp_die();
}

/**
 * Delete a Redirection link for ajax function
 */
function rpp_delete_geo_redirection_link_for_ajax() {

	if ( isset( $_POST['rpp_nonce'] ) ) {
		$ajax_nonce = $_POST['rpp_nonce'];	
		if ( ! wp_verify_nonce( $ajax_nonce, 'rpp-ajax-nonce' ) ) {
			echo json_encode( array(
				'success' => 0,
				'error'   => __("You can not delete that link.")
			));
			wp_die();
		}
	} else {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not delete that link.")
		));
		wp_die();
	}

	if ( !current_user_can('edit_others_posts') ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("You can not delete that link.")
		));
		wp_die();
	}

	if ( !isset( $_POST['geo_redirection_link_id'] ) || !is_numeric( $_POST['geo_redirection_link_id'] ) ) {
		echo json_encode( array(
			'success' => 0,
			'error'   => __("Geo Redirection link id is not valid. Refresh and try again.")
		));
		wp_die();
	}

	global $wpdb;

	$geo_redirection_link_id = sanitize_text_field( $_POST['geo_redirection_link_id'] );

	$geo_redirection_url_table_name = $wpdb->prefix . 'rpp_geo_redirection_links';

	$result = $wpdb->query( "DELETE  FROM {$geo_redirection_url_table_name} WHERE geo_redirection_link_id = {$geo_redirection_link_id} ;" );

	if ( $result ) {
		$result_array = array(
			'success' => 1
		);
	} else {
		$result_array = array(
			'success' => 0,
			'error'   => "Some errors. Refresh and try again."
		);
	}

	$result_array = json_encode( $result_array );
	echo $result_array;
	wp_die();
}


/**
 * Delete a redirection link
 */
function rpp_delete_a_redirection_link( $id ) {
	global $wpdb;

	$redirection_url_table_name = $wpdb->prefix . 'rpp_redirection_links';
	$count = $wpdb->get_var( "SELECT COUNT(*) FROM {$redirection_url_table_name} WHERE redirection_link_id = {$id} ;" );

	if ( $count >= 1 ) {
		$wpdb->query( "DELETE FROM {$redirection_url_table_name} WHERE redirection_link_id = {$id} ;" );
	}
}

/**
 * Delete a geo redirection link
 */
function rpp_delete_a_geo_redirection_link( $id ) {
	global $wpdb;

	$geo_redirection_url_table_name = $wpdb->prefix . 'rpp_geo_redirection_links';
	$count = $wpdb->get_var( "SELECT COUNT(*) FROM {$geo_redirection_url_table_name} WHERE geo_redirection_link_id = {$id} ;" );

	if ( $count >= 1 ) {
		$wpdb->query( "DELETE  FROM {$geo_redirection_url_table_name} WHERE geo_redirection_link_id = {$id} ;" );
	}
} 


/**
 * Checking url and redirect redirection url
 */
add_action( 'init', 'rpp_checking_redirection_url', 5 );

function rpp_checking_redirection_url() {
	if (!is_admin()) {

		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
			$url = "https"; 
		else
			$url = "http"; 
		
		// Here append the common URL characters. 
		$url .= "://"; 
		
		// Append the host(domain name, ip) to the URL. 
		$url .= $_SERVER['HTTP_HOST']; 
		
		// Append the requested resource location to the URL 
		$url .= $_SERVER['REQUEST_URI'];

		while ( substr( $url, -1, 1 ) == '/' ) {
			$url = substr( $url, 0, -1 );
			if( substr( $url, -1, 1 ) != '/' ) {
				break;
			}
		}

		if (strpos($url, 'wc-ajax=get_refreshed_fragments') === false && strpos($url, 'wc-ajax=get_refreshed_fragments') !== 0) {

			$url1 = $url;
			$url = $url . '/';

			//whether ip is from share internet
			if (!empty($_SERVER['HTTP_CLIENT_IP']))
			{
				$ip_address = $_SERVER['HTTP_CLIENT_IP'];
			}
			//whether ip is from proxy
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			//whether ip is from remote address
			else
			{
				$ip_address = $_SERVER['REMOTE_ADDR'];
			}

			$browser_name = rpp_get_browser_name($_SERVER['HTTP_USER_AGENT']);

			$ipdat = @json_decode(file_get_contents( "http://www.geoplugin.net/json.gp?ip=" . $ip_address)); 
			$country_code = $ipdat->geoplugin_countryCode;
			$country_name = $ipdat->geoplugin_countryName;
			
			global $wpdb; 

			$link_clicks_table_name = $wpdb->prefix . 'rpp_link_clicks';
			$existance_checking_link_clicks = $wpdb->get_row( "SELECT * FROM {$link_clicks_table_name} WHERE (target_url = '{$url}' OR target_url = '{$url1}') AND ip = '{$ip_address}' ;" );
			$link_click_unique = false;
			if ( $existance_checking_link_clicks == NULL || $existance_checking_link_clicks == array() ) {
				$link_click_unique = true;
			}

			if ( strpos( $url, 'wp-admin' ) === false && strpos( $url, 'wp-admin') !== 0 && strpos( $url, 'wp-cron' ) === false && strpos( $url, 'wp-cron') !== 0 ) {
				$url_data = array(
					'ip'           => $ip_address,
					'anchor_text'  => '',
					'target_url'   => $url,
					'browser_name' => $browser_name,
					'country_code' => $country_code,
					'country_name' => $country_name,
					'date'         => date("Y-m-d"),
				);
				$wpdb->insert( $link_clicks_table_name, $url_data );
			}

			//geo redirection
			$geo_redirection_url_table_name = $wpdb->prefix . 'rpp_geo_redirection_links';

			$existance_checking_geo_redirection = $wpdb->get_row( "SELECT * FROM {$geo_redirection_url_table_name} WHERE target_url = '{$url}' OR target_url = '{$url1}' ;" );
			if ( $existance_checking_geo_redirection != NULL && $existance_checking_geo_redirection != array() ) {

				$existance_checking_geo_redirection1 = $wpdb->get_row( "SELECT * FROM {$geo_redirection_url_table_name} WHERE (target_url = '{$url}' OR target_url = '{$url1}' ) AND country = '{$country_code}' ;" );
				if ( $existance_checking_geo_redirection1 != NULL && $existance_checking_geo_redirection1 != array() ) {
					$geo_redirection_link_id = $existance_checking_geo_redirection1->geo_redirection_link_id;

					$destination_url = $existance_checking_geo_redirection1->destination_url;
					$destination_type = $existance_checking_geo_redirection1->destination_type;
					
					if($link_click_unique) {
						$unique_clicks = $existance_checking_geo_redirection1->unique_hits_count + 1;
					} else {
						$unique_clicks = $existance_checking_geo_redirection1->unique_hits_count;
						if ($unique_clicks == 0 ) {
							$unique_clicks = 1;
						}
					}
					$geo_redirection_url_data = array(
						'hits_count' => $existance_checking_geo_redirection1->hits_count + 1,
						'unique_hits_count' => $unique_clicks,
					); 

					$wpdb->update( $geo_redirection_url_table_name, $geo_redirection_url_data, array( 'geo_redirection_link_id' => $geo_redirection_link_id ) );

					header("Location: " . $destination_url, TRUE, $destination_type);
					exit;
				}
			}

			$redirection_url_table_name = $wpdb->prefix . 'rpp_redirection_links';
			$redirection_url_existance_checking = $wpdb->get_row( "SELECT * FROM {$redirection_url_table_name} WHERE source_url = '{$url}' OR source_url = '{$url1}' ;" );
			if ( $redirection_url_existance_checking != NULL && $redirection_url_existance_checking != array() ) {
				$target_url = $redirection_url_existance_checking->target_url;
				$redirection_type = $redirection_url_existance_checking->redirection_type;

				$redirection_link_id = $redirection_url_existance_checking->redirection_link_id;
				
				$target_url = $redirection_url_existance_checking->target_url;
				
				if($link_click_unique) {
					$unique_clicks = $redirection_url_existance_checking->unique_hits_count + 1;
				} else {
					$unique_clicks = $redirection_url_existance_checking->unique_hits_count;
					if ( $unique_clicks == 0 ) {
						$unique_clicks = 1;
					}
				}
				$redirection_url_data = array(
					'hits_count' => $redirection_url_existance_checking->hits_count + 1,
					'unique_hits_count' => $unique_clicks,
				);
				
				$target_url = $redirection_url_existance_checking->target_url;
				$result = $wpdb->update( $redirection_url_table_name, $redirection_url_data, array( 'redirection_link_id' => $redirection_link_id ) );


				header("Location: " . $target_url, TRUE, $redirection_type);
				exit;
			}  
		}
	}
} 

/**
 * Get client browser name
 */
function rpp_get_browser_name($user_agent)
{
  if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
  elseif (strpos($user_agent, 'Edge')) return 'Edge';
  elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
  elseif (strpos($user_agent, 'Safari')) return 'Safari';
  elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
  elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
  return 'Other';
}

