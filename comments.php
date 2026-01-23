<?php
if (post_password_required()) {
    return;
}
?>

<?php if (have_comments()) : ?>
    <h3>Коментарі</h3>
    <ul>
        <?php wp_list_comments(); ?>
    </ul>
<?php endif; ?>

<?php comment_form(); ?>
