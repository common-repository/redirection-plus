<?php

/**
 * Create a new table class for Redirection links that extends the WP_List_Table
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class RPP_Redirection_Links_Table extends WP_List_Table {

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
            'url'               => __( 'Source URL', 'redirection-plus' ),
            'redirection_url_name'  => __( 'Name', 'redirection-plus' ),
            'redirection_type'  => __( 'Redirection Type', 'redirection-plus' ),
            'target_url'        => __( 'Destination URL', 'redirection-plus' ),
            'hits'              => __( 'Hits', 'redirection-plus' ),
            'unique_hits_count'       => __( 'Unique Hits', 'redirection-plus' ),
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
            'url'          => array( 'url', false ),
            'created_date' => array( 'created_date', false )
        );
    }

    /**
     * Returns an associative array containing the bulk action
     */
    public function get_bulk_actions() {
        $actions = array(
            'delete'               => __( 'Delete', 'redirection-plus' ),
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
                   rpp_delete_a_redirection_link( $id );
                }
            }
        } 
    }

    // For url column
    public function column_url( $item ) {
        $actions = array(
            'rpp_edit_redirection_url'         => sprintf( '<a href="javascript:;">' . __( 'Edit', 'redirection-plus' ) . '</a>' ),
            'trash rpp_trash_redirection_url'  => sprintf( '<a href="javascript:;">' . __( 'Delete', 'redirection-plus' ) . '</a>' ),
            'rpp_redirection_click_anaytics'   => sprintf( '<a href="https://jannatqualitybacklinks.com/product/redirection-plus-pro/" target="_blank">' . __( 'Unlock Click Analytics', 'redirection-plus' ) . '</a>' )
        );

        return sprintf( '<input class="rpp_edit_redirection_source_url" style="width: 150px;" type="text" value="%1$s" /><a style="padding-top: 0px;" href="javascript:;" class="btn btn-default rpp_clipboard_btn"><img style="width: 25px;" src="' . plugins_url('img/copy--v1.png', RPP_PLUGIN_FILE) . '" /></a> %2$s', rawurldecode( $item['url'] ), $this->row_actions( $actions ));
    }

    // for redirection type column
    public function column_redirection_type( $item ) {
        return sprintf( '%1$d Redirection', $item['redirection_type'] );
    } 

    /**
     * Get the table data
     */
    private function table_data( $per_page, $offset )
    {
        global $wpdb;
        $redirection_link_table_name  = $wpdb->prefix . "rpp_redirection_links";

        // Get all links data
        $all_links_data   = $wpdb->get_results( "SELECT * FROM $redirection_link_table_name ORDER BY source_url LIMIT {$per_page} OFFSET {$offset} ;" );

        $table_data = array();
        foreach ( $all_links_data as $link_data ) {
            $row_data = array(
                'id'                => $link_data->redirection_link_id,
                'url'               => $link_data->source_url,
                'redirection_url_name' => $link_data->redirection_url_name,
                'target_url'        => $link_data->target_url,
                'hits'              => $link_data->hits_count, //$this->get_link_clicks($link_data->source_url, $link_data->redirection_url_name),
                'unique_hits_count' => $link_data->unique_hits_count, //$this->get_unique_link_clicks($link_data->source_url, $link_data->redirection_url_name),
                'redirection_type'  => $link_data->redirection_type,
                'created_date'      => $link_data->created_date,
            );
            array_push( $table_data, $row_data );
        }

        return $table_data;
    }

    public function get_link_clicks($url, $url_name) {
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
            WHERE (target_url = '{$link_url}' OR target_url = '{$link_url}/') AND anchor_text = ''  ;" 
        );
        return $counts;
    }

    public function get_unique_link_clicks($url, $url_name) {
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
                WHERE (target_url = '{$link_url}' OR target_url = '{$link_url}/') AND anchor_text = ''  
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
        $redirection_link_table_name  = $wpdb->prefix . "rpp_redirection_links";

        $count = $wpdb->get_var( "SELECT COUNT(*) FROM $redirection_link_table_name ;" );
        
        return $count;
    } 
    
    /**
     * Define what data to show on each column of the table
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'url':
            case 'redirection_url_name':
            case 'redirection_type':
            case 'target_url':
            case 'hits':
            case 'unique_hits_count':
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
        $orderby = 'url';
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
} 
