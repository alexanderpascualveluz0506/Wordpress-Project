<?php
 ob_start();
if (! class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class BookingDatatable extends \WP_List_Table
{
    public function __construct()
    {
        parent::__construct([
            'singular' => 'book',
            'plural' => 'books',
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
            case 'datetime':
                return $item->datetime;

            // case 'time':
            //     return $item->time;

            case 'provider_id':
                return $item->provider_id;

            case 'customer_id':
                return $item->customer_id;

            case 'notes':
                return $item->notes;

            case 'service_id':
                return $item->service_id;

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
            'customer_id' => __('Customer', 'kerberos'),
            'datetime' => __('Datetime', 'kerberos'),
            'service_id' => __('Service', 'kerberos'),
            'provider_id' => __('Provider', 'kerberos'),
            'status' => __('Status', 'kerberos'),
            'notes' => __('Notes', 'kerberos'),
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
    public function column_customer_id($item)
    {
        $actions = [];
        $actions['edit'] = sprintf('<a href="%s" data-id="%d" title="%s">%s</a>', admin_url('admin.php?page=bookings&action=edit-booking&id=' . $item->id), $item->id, __('Edit this item', 'kerberos'), __('Edit', 'kerberos'));
        $actions['delete'] = sprintf('<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url('admin.php?page=bookings&action=delete-booking&id=' . $item->id), $item->id, __('Delete this item', 'kerberos'), __('Delete', 'kerberos'));

        return sprintf('<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url('admin.php?page=bookings&action=view-booking&id=' . $item->id), $item->customer_id, $this->row_actions($actions));
    }

    /**
     * Get sortable columns
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        $sortable_columns = [
            'customer_id' => [ 'customer_id', true ],
            'provider_id' => [ 'provider_id', true ],
            'service_id' => [ 'service_id', true ],
            'datetime' => [ 'datetime', true ],
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
            $table_name = $wpdb->prefix . 'kerberos';
                $ids = isset($_REQUEST['book_id']) ? $_REQUEST['book_id'] : array();
                if (is_array($ids)) $ids = implode(',', $ids);
                print_r($ids);
                if (!empty($ids)) {
                    $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)"); 
                }
                wp_redirect( home_url() .'/wp-admin/admin.php?page=bookings');
                exit;
            
         
        endif;

        if ($action_single=="delete-booking"):
            global $wpdb;
            $table_name = $wpdb->prefix . 'kerberos';
                if (!empty($id)) {
                    $wpdb->query("DELETE FROM $table_name WHERE id IN($id)");
                }
                wp_redirect( home_url() .'/wp-admin/admin.php?page=bookings');
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
            '<input type="checkbox" name="book_id[]" value="%d" />',
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
        $this->page_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '2';

        // only ncessary because we have sample data
        $args = [
            'offset' => $offset,
            'number' => $per_page,
        ];

        if (isset($_REQUEST['orderby']) && isset($_REQUEST['order'])) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order'] = $_REQUEST['order'] ;
        }

        $this->items = ker_get_all_book($args);

        $this->set_pagination_args([
            'total_items' => ker_get_book_count(),
            'per_page' => $per_page,
        ]);
    }
}
