<?php
$field_prefix = "_em_";
if(isset($atts['sortOrder'])){
    $sort_order = strtoupper($atts['sortOrder']);
}
if(isset($atts['sortField'])){
    $sort_field = $field_prefix.$atts['sortField'];
}

    $args = array(
        'post_type' => 'employee', // Your CPT name
        'posts_per_page' => -1,
        'meta_key'       => $sort_field ?? '_em_name',
        'orderby'        => 'meta_value',
        'order'          => $sort_order ?? 'ASC'
    );


    $employees = new WP_Query($args);

    if ($employees->have_posts()) :
?>
    <button id="toggleSortOrder">Toggle Sort Order (Ascending)</button>
    <table id="employee-table">
        <thead>
            <tr>
                <th data-sort="name">Name
                    <span id="nameSortIndicator"></span>
                </th>
                <th data-sort="email">Email
                    <span id="emailSortIndicator"></span>
                </th>
                <th data-sort="age">Age
                    <span id="ageSortIndicator"></span>
                </th>
                <th data-sort="date_of_hiring">Date of Hiring
                    <span id="date_of_hiringSortIndicator"></span>
                </th>
            </tr>
        </thead>
        <tbody id="employee-tbody">
            <?php while ($employees->have_posts()) : $employees->the_post(); ?>
                <tr>
                    <td><?php echo esc_html(get_post_meta(get_the_ID(), '_em_name', true)); ?></td>
                    <td><?php echo esc_html(get_post_meta(get_the_ID(), '_em_email', true)); ?></td>
                    <td><?php echo esc_html(get_post_meta(get_the_ID(), '_em_age', true)); ?></td>
                    <td><?php echo esc_html(get_post_meta(get_the_ID(), '_em_date_of_hiring', true)); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php
wp_reset_postdata();
endif;