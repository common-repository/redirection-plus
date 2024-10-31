<?php

/**
 * Geo Redirection Links table core
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class RPP_Geo_Redirection_Links_Table extends WP_List_Table {

    /**
     * Prepare the items for the table to process
     */
    public function prepare_items()
    {
        $columns   = $this->get_columns();
        $hidden    = $this->get_hidden_columns();
        $sortable  = $this->get_sortable_columns();
        $this->process_bulk_action();

        // Pagination config
        $per_page = 10;
        $current_page = $this->get_pagenum();
        if ( 1 < $current_page ) {
            $offset = $per_page * ( $current_page - 1 );
        } else {
            $offset = 0;
        }
        // Get table data and count
        $data = $this->table_data( $per_page, $offset );
        $count = $this->table_count();
        // Set sort
        usort( $data, array( &$this, 'sort_data' ) );
        // Set the pagination
        $this->set_pagination_args( array(
            'total_items' => $count,
            'per_page'    => $per_page,
            'total_pages' => ceil( $count / $per_page )
        ) );
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $this->items = $data;
                
    }

    /**
     * Override the parent columns method.
     */
    public function get_columns() {
        $columns = array(
            'cb'                => '<input type="checkbox" />',
            'country'           => __( 'Country', 'redirection-plus' ),
            'target_url'        => __( 'Target Page/Post URL', 'redirection-plus' ),
            'destination_url'   => __( 'Destination URL', 'redirection-plus' ),
            'destination_type'  => __( 'Destination Type', 'redirection-plus' ),
            'hits'              => __( 'Hits', 'redirection-plus' ),
            'unique_hits_count' => __( 'Unique Hits', 'redirection-plus' ),
            'created_date'      => __( 'Created Date', 'redirection-plus' )
        );
        return $columns;
    }

    /**
     * Render the bulk edit checkbox
     */
    public function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />', $item['id']
        );
    }

    /**
     * Define which columns are hidden
     */
    public function get_hidden_columns() {
        return array();
    }

    /**
     * Define the sortable columns
     */
    public function get_sortable_columns() {
        return array(
            'country'      => array( 'country', false ),
            'created_date' => array( 'created_date', false )
        );
    }

    /**
     * Returns an associative array containing the bulk action
     */
    public function get_bulk_actions() {
        $actions = array(
            'delete' => __( 'Delete', 'redirection-plus' )
        );

        return $actions;
    }

    public function process_bulk_action()
    {
        if ( 'delete' === $this->current_action() ) {
            $ids = ( isset($_REQUEST['id']) && is_array($_REQUEST['id']) ) ? $_REQUEST['id'] : array();
            foreach ( $ids as $id ) {
                // Delete link
                if ( current_user_can('edit_others_posts') && is_numeric( $id ) ) {
                   rpp_delete_a_geo_redirection_link( $id );
                }
            }
        } 
    }

    // For url column
    public function column_country( $item ) {
        $actions = array(
            'rpp_edit_geo_redirection_url'         => sprintf( '<a href="javascript:;">' . __( 'Edit', 'redirection-plus' ) . '</a>' ),
            'trash rpp_trash_geo_redirection_url'  => sprintf( '<a href="javascript:;">' . __( 'Delete', 'redirection-plus' ) . '</a>' ),
            'rpp_geo_redirection_click_anaytics'   => sprintf( '<a href="https://jannatqualitybacklinks.com/product/redirection-plus-pro/" target="_blank">' . __( 'Unlock Click Analytics', 'redirection-plus' ) . '</a>' )
        );

        return sprintf( '<span class="rpp_edit_geo_redirection_country" country_code="%1$s">%2$s</span> %3$s', $item['country_code'], $item['country'], $this->row_actions( $actions ) );
    }

    // for destination type column
    public function column_destination_type( $item ) {
        return sprintf( '%1$d Redirection', $item['destination_type'] );
    } 

    // For target url
    public function column_target_url( $item ) {
        return sprintf( '<input class="rpp_geo_redirection_target_url" style="width: 150px;" type="text" value="%1$s" /><a style="padding-top: 0px;" href="javascript:;" class="btn btn-default rpp_clipboard_btn"><img style="width: 25px;" src="' . plugins_url('img/copy--v1.png', RPP_PLUGIN_FILE) . '" /></a>', rawurldecode( $item['target_url'] ));
    }

    /**
     * Get the table data
     */
    private function table_data( $per_page, $offset )
    {
        global $wpdb;
        $geo_redirection_link_table_name  = $wpdb->prefix . "rpp_geo_redirection_links";

        // Get all links data
        $all_links_data   = $wpdb->get_results( "SELECT * FROM $geo_redirection_link_table_name ORDER BY country LIMIT {$per_page} OFFSET {$offset} ;" );

        $table_data = array();
        foreach ( $all_links_data as $link_data ) {
            $row_data = array(
                'id'                => $link_data->geo_redirection_link_id,
                'country_code'      => $link_data->country,
                'country'           => $this->code_to_country($link_data->country),
                'target_url'        => $link_data->target_url,
                'destination_url'   => $link_data->destination_url,
                'hits'              => $link_data->hits_count, //$this->get_link_clicks($link_data->target_url, $link_data->country),
                'unique_hits_count' => $link_data->unique_hits_count, //$this->get_link_unique_clicks($link_data->target_url, $link_data->country),
                'destination_type'  => $link_data->destination_type,
                'created_date'      => $link_data->created_date,
            );
            array_push( $table_data, $row_data );
        }

        return $table_data;
    }

    public function get_link_clicks($url, $country_code) {
        global $wpdb;
        $link_url = $url;
        while ( substr( $link_url, -1, 1 ) == '/' ) {
            $link_url = substr( $link_url, 0, -1 );
            if( substr( $link_url, -1, 1 ) != '/' ) {
                break;
            }
        }
        $link_clicks_table_name = $wpdb->prefix . 'rpp_link_clicks';
        $counts = $wpdb->get_var( "
            SELECT COUNT(id) as clicks 
            FROM {$link_clicks_table_name} 
            WHERE (target_url = '{$link_url}' OR target_url = '{$link_url}/') AND anchor_text = '' AND country_code = '{$country_code}' ;" 
        );
        return $counts;
    }

     public function get_link_unique_clicks($url, $country_code) {
        global $wpdb;
        $link_url = $url;
        while ( substr( $link_url, -1, 1 ) == '/' ) {
            $link_url = substr( $link_url, 0, -1 );
            if( substr( $link_url, -1, 1 ) != '/' ) {
                break;
            }
        }
        $link_clicks_table_name = $wpdb->prefix . 'rpp_link_clicks';
        $counts = $wpdb->get_var( "
            SELECT count(*) 
            FROM (
                SELECT ip 
                FROM {$link_clicks_table_name} 
                WHERE (target_url = '{$link_url}' OR target_url = '{$link_url}/') AND anchor_text = '' AND country_code = '{$country_code}' 
                GROUP BY ip
            ) AA ;"
        );
        return $counts;
    }

    /**
     * Get the table count
     */
    private function table_count()
    {
        global $wpdb;
        $redirection_link_table_name  = $wpdb->prefix . "rpp_geo_redirection_links";

        $count = $wpdb->get_var( "SELECT COUNT(*) FROM $geo_redirection_link_table_name ;" );
        
        return $count;
    }

    
    /**
     * Define what data to show on each column of the table
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'country':
            case 'target_url':
            case 'destination_url':
            case 'hits':
            case 'unique_hits_count':
            case 'destination_type':
            case 'created_date':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
  
    /**
     * Allows to sort the data by the variables set in the $_GET
     */
    private function sort_data( $first, $second ) {
        // Set defaults
        $orderby = 'country';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if( !empty( $_GET['orderby'] ) )
        {
            $orderby = sanitize_text_field( $_GET['orderby'] );
        }
        // If order is set use this as the order
        if( !empty( $_GET['order'] ) )
        {
            $order = sanitize_text_field( $_GET['order'] );
        }
        $result = strcmp( $first[ $orderby ], $second[ $orderby ] );
        if( $order === 'asc' )
        {
            return $result;
        }
        return -$result;
    }

    /**
     * Get country name with country code
     */
    function code_to_country( $code ){

        $code = strtoupper($code);

        $countryList = array(
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BQ' => 'Bonaire, Sint Eustatius and Saba',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CD' => 'Congo',
            'CG' => 'Congo , the Democratic Republic of the',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote d\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',            
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and McDonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KR' => 'Korea, Republic of',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'SS' => 'South Sudan',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard and Jan Mayen Islands',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Minor Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        );

        if( !$countryList[$code] ) return $code;
        else return $countryList[$code];
    }
} 
