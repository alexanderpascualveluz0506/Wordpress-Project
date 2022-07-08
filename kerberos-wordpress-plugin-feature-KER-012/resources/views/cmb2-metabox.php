<?php

/**
     * Metabox for the user profile screen
     */
    $cmb_user = new_cmb2_box([
        'id' => 'yourprefix_user_edit',
        'title' => esc_html__('User Profile Metabox', 'cmb2'), // Doesn't output for user boxes
        'object_types' => [ 'user' ], // Tells CMB2 to use user_meta vs post_meta
        'show_names' => true,
        'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
    ]);


    $cmb_user->add_field([
        'name' => esc_html__('Gender', 'cmb2'),
        //'desc'    => esc_html__( 'field description (optional)', 'cmb2' ),
        'id' => 'gender',
        'type' => 'select',
    'options' => [
            'male' => esc_html__('Male', 'cmb2'),
            'female' => esc_html__('Female', 'cmb2'),

        ],
    ]);
?>