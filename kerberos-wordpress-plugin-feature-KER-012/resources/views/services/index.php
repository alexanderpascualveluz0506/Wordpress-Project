<div class="wrap">
    <h2><?php _e('Services', 'kerberos'); ?>
        <a href="<?php echo admin_url('admin.php?page=services&action=new-service'); ?>"
            class="add-new-h2"><?php _e('Add New', 'kerberos'); ?></a>
    </h2>

    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">

        <?php
        $service_list_table = new ServicesDatatable();
        $service_list_table->prepare_items();
        $service_list_table->search_box('search', 'search_id');
        $service_list_table->display();
        ?>
    </form>
</div>
    