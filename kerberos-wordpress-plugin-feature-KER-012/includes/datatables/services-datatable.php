<?php

if (! class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class ServicesDatatable extends \WP_List_Table
{
    public function __construct()
    {
        parent::__construct([
            'singular' => 'service',
            'plural' => 'services',
            'ajax' => false,
        ]);
    }

    public function get_table_classes()
    {
        return [ 'widefat', 'fixed', 'striped', $this->_args['plural'] ];
    }

    /**
     * Message to show if no designation found
     *
     * @return void
     */
    public function no_items()
    {
        _e('', 'kerberos');
    }

    /**
     * Default column values if no callback found
     *
     * @param  object  $item
     * @param  string  $column_name
     *
     * @return string
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'service_name':
                return $item->service_name;

            case 'service_description':
                return $item->service_description;

            default:
                return isset($item->$column_name) ? $item->$column_name : '';
        }
    }

    /**
     * Get the column names
     *
     * @return array
     */
    public function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'service_name' => __('Name', 'kerberos'),
            'service_description' => __('Description', 'kerberos'),
            'service_image' => __('Image', 'kerberos'),
            
        ];

        return $columns;
    }   

    
    /**
     * Render the designation name column
     *
     * @param  object  $item
     *
     * @return string
     */
    public function column_service_name($item)
    {
        $actions = [];
        $actions['edit'] = sprintf('<a href="%s" data-id="%d" title="%s">%s</a>', admin_url('admin.php?page=services&action=edit-service&id=' . $item->id), $item->id, __('Edit this item', 'kerberos'), __('Edit', 'kerberos'));
        $actions['delete'] = sprintf('<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url('admin.php?page=services&action=delete-service&id=' . $item->id), $item->id, __('Delete this item', 'kerberos'), __('Delete', 'kerberos'));

        return sprintf('<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url('admin.php?page=services&action=view-service&id=' . $item->id), $item->service_name, $this->row_actions($actions));
    }

    public function column_service_image($item)
    { ?>
       <img src="<?php echo $item->service_image ?>" width="60" height="60">
    <?php }

 
    /**
     * Get sortable columns
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        $sortable_columns = [
            'service_name' => [ 'service_name', true ],
            'service_description' => [ 'service_description', true ],
            'service_image' => [ 'service_image', true ],            
        ];

        return $sortable_columns;
    }

    /**
     * Set the bulk actions
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        $actions = [
            'bulk-trash' => __('Move to Trash', 'kerberos'),
        ];

        return $actions;
    }
    public function process_bulk_action() {

        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {
            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];
            
            if ( ! wp_verify_nonce( $nonce, $action ) )
                wp_die( 'Nope! Security check failed!' );
            }
       
        
        $action_bulk = $this->current_action();
        $action_single = isset($_GET['action']) ? $_GET['action'] : 'home';
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($action_bulk=="bulk-trash"):
            global $wpdb;
            $table_name = $wpdb->prefix . 'services';
            $ids = isset($_REQUEST['service_id']) ? $_REQUEST['service_id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);
            print_r($ids);
            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)"); 
            }
            wp_redirect( home_url() .'/wp-admin/admin.php?page=services');
            exit;
        
        endif;

        if ($action_single=="delete-service"):
            global $wpdb;
            $table_name = $wpdb->prefix . 'services';
                if (!empty($id)) {
                    $wpdb->query("DELETE FROM $table_name WHERE id IN($id)");
                }
                wp_redirect( home_url() .'/wp-admin/admin.php?page=services');
                exit;
               
        endif;

    }


    /**
     * Render the checkbox column
     *
     * @param  object  $item
     *
     * @return string
     */
    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="service_id[]" value="%d" />',
            $item->id
        );
    }

    /**
     * Set the views
     *
     * @return array
     */
    public function get_views_()
    {
        $status_links = [];
        $base_link = admin_url('admin.php?page=sample-page');

        foreach ($this->counts as $key => $value) {
            $class = ($key == $this->page_status) ? 'current' : 'status-' . $key;
            $status_links[ $key ] = sprintf('<a href="%s" class="%s">%s <span class="count">(%s)</span></a>', add_query_arg([ 'status' => $key ], $base_link), $class, $value['label'], $value['count']);
        }

        return $status_links;
    }

    /**
     * Prepare the class items
     *
     * @return void
     */
    public function prepare_items()
    {
        $this->process_bulk_action();
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [ $columns, $hidden, $sortable ];

        $per_page = 10;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;
        //$this->page_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '2';

        // only ncessary because we have sample data
        $args = [
            'offset' => $offset,
            'number' => $per_page,
        ];

        if (isset($_REQUEST['orderby']) && isset($_REQUEST['order'])) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order'] = $_REQUEST['order'] ;
        }

        $this->items = ker_get_all_services($args);

        $this->set_pagination_args([
            'total_items' => ker_get_services_count(),
            'per_page' => $per_page,
        ]);
    }
}
