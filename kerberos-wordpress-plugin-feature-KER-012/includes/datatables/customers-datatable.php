<?php

if (! class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class CustomersDatatable extends \WP_List_Table
{
    public function __construct()
    {
        parent::__construct([
            'singular' => 'customer',
            'plural' => 'customers',
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
            case 'display_name':
                return $item->display_name;

            case 'user_email':
                return $item->user_email;

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
            'display_name' => __('Name', 'kerberos'),
            'user_email' => __('Email', 'kerberos'),
            'gender' => __('Gender', 'kerberos'),            
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
    public function column_display_name($item)
    {
        $actions = [];
        $actions['edit'] = sprintf('<a href="%s" data-id="%d" title="%s">%s</a>', admin_url('admin.php?page=kerberos&action=edit-customer&id=' . $item->ID), $item->ID, __('Edit this item', 'kerberos'), __('Edit', 'kerberos'));
        $actions['delete'] = sprintf('<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url('admin.php?page=kerberos&action=delete-customer&id=' . $item->ID), $item->ID, __('Delete this item', 'kerberos'), __('Delete', 'kerberos'));

        return sprintf('<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url('admin.php?page=kerberos&action=view-customer&id=' . $item->ID), $item->display_name, $this->row_actions($actions));
    }
    public function column_customer_id($item)
    {
        $actions = [];
        $actions['edit'] = sprintf('<a href="%s" data-id="%d" title="%s">%s</a>', admin_url('admin.php?page=kerberos&action=edit-service&id=' . $item->id), $item->id, __('Edit this item', 'kerberos'), __('Edit', 'kerberos'));
        $actions['delete'] = sprintf('<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url('admin.php?page=kerberos&action=delete-service&id=' . $item->id), $item->id, __('Delete this item', 'kerberos'), __('Delete', 'kerberos'));

        return sprintf('<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url('admin.php?page=kerberos&action=view-service&id=' . $item->id), $item->customer_id, $this->row_actions($actions));
    }
    public function column_gender($item)
    {
        $all_meta_for_user = get_user_meta( $item->ID );
        echo ucwords($all_meta_for_user['gender'][0]);
        //print_r( $all_meta_for_user );


    }
    

    /**
     * Get sortable columns
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        $sortable_columns = [
            'display_name' => [ 'display_name', true ],
            'uesr_email' => [ 'user_email', true ],
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
            'trash' => __('Move to Trash', 'kerberos'),
        ];

        return $actions;
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
            '<input type="checkbox" name="customers_id[]" value="%d" />',
            $item->ID
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

        $this->items = ker_get_all_customers($args);

        $this->set_pagination_args([
            'total_items' => ker_get_customers_count(),
            'per_page' => $per_page,
        ]);
    }
}
