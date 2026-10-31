<?php

defined('ABSPATH') || exit;

class Theme_Favicon_Admin
{

    private Theme_Favicon_Generator $generator;

    public function __construct()
    {

        $this->generator = new Theme_Favicon_Generator();

        add_action('admin_menu', [$this, 'register_page']);

        add_action(
            'admin_post_theme_favicon_upload',
            [$this, 'handle_upload']
        );

        add_action(
            'admin_post_theme_favicon_delete',
            [$this, 'handle_delete']
        );
    }

    /**
     * Register admin page.
     */
    public function register_page()
    {

        add_theme_page(
            'Favicon Generator',
            'Favicon Generator',
            'manage_options',
            'theme-favicon-generator',
            [$this, 'render_page']
        );
    }

    /**
     * Render admin page.
     */
    public function render_page()
    {

        ?>
        <div class="wrap">

            <h1>Favicon Generator</h1>

            <p>
                Цей інструмент дозволяє автоматично створити всі необхідні
                фавіконки для сайту на основі одного зображення.
            </p>

            <p>
                Завантажте квадратне зображення у форматі PNG, JPG або WebP.
                Рекомендований розмір — 512 × 512 px або більше.
            </p>

            <hr>

            <h2>Завантажити фавіконку</h2>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">

                <input type="hidden" name="action" value="theme_favicon_upload">

                <?php wp_nonce_field('theme_favicon_upload', 'favicon_nonce'); ?>

                <input type="file" name="favicon" accept="image/png,image/jpeg,image/webp" required>

                <?php submit_button('Завантажити та згенерувати'); ?>

            </form>

            <?php

            $favicon_exists = file_exists(
                get_stylesheet_directory() . '/inf/favicons/generated/favicon.ico'
            );

            if ($favicon_exists):

                ?>

                <hr>

                <h2>Видалення фавіконок</h2>

                <p>
                    Видалити всі згенеровані фавіконки та завантажений оригінал?
                </p>

                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                    onsubmit="return confirm('Ви впевнені, що хочете видалити всі фавіконки?');">

                    <input type="hidden" name="action" value="theme_favicon_delete">

                    <?php wp_nonce_field('theme_favicon_delete', 'favicon_delete_nonce'); ?>

                    <?php
                    submit_button(
                        'Видалити фавіконки',
                        'delete',
                        'submit',
                        false
                    );
                    ?>

                </form>

            <?php endif; ?>

            <?php if (isset($_GET['favicon_status'])): ?>

                <?php if ($_GET['favicon_status'] === 'success'): ?>

                    <div class="notice notice-success is-dismissible">
                        <p>Фавіконки успішно згенеровано!</p>
                    </div>

                <?php elseif ($_GET['favicon_status'] === 'deleted'): ?>

                    <div class="notice notice-success is-dismissible">
                        <p>Усі фавіконки успішно видалено!</p>
                    </div>

                <?php else: ?>

                    <div class="notice notice-error is-dismissible">
                        <p>Під час виконання операції виникла помилка.</p>
                    </div>

                <?php endif; ?>

            <?php endif; ?>

        </div>
        <?php
    }

    /**
     * Handle image upload.
     */
    public function handle_upload()
    {

        if (!current_user_can('manage_options')) {
            wp_die('Недостатньо прав.');
        }

        check_admin_referer('theme_favicon_upload', 'favicon_nonce');

        if (empty($_FILES['favicon']['tmp_name'])) {
            $this->redirect('error');
        }

        $file = $_FILES['favicon'];

        if (!empty($file['error']) || !is_uploaded_file($file['tmp_name'])) {
            $this->redirect('error');
        }

        $image = wp_check_filetype_and_ext(
            $file['tmp_name'],
            $file['name'],
            [
                'jpg|jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
            ]
        );

        if (empty($image['type'])) {
            $this->redirect('error');
        }

        $image_info = getimagesize($file['tmp_name']);

        if (!$image_info || $image_info[0] < 180 || $image_info[1] < 180) {
            $this->redirect('error');
        }

        $extension = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ][$image['type']];

        $directory = get_stylesheet_directory() . '/inf/favicons/generated/';

        if (!wp_mkdir_p($directory) || !is_writable($directory)) {
            $this->redirect('error');
        }

        // Remove previous source files.
        foreach (['jpg', 'png', 'webp'] as $ext) {
            $old_file = $directory . 'favicon-source.' . $ext;

            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }

        $source = $directory . 'favicon-source.' . $extension;

        if (!move_uploaded_file($file['tmp_name'], $source)) {
            $this->redirect('error');
        }

        $result = $this->generator->generate($source);

        if (is_wp_error($result)) {
            $this->redirect('error');
        }

        update_option('theme_favicon_version', time());

        $this->redirect('success');
    }

    /**
     * Handle favicon deletion.
     */
    public function handle_delete()
    {

        if (!current_user_can('manage_options')) {
            wp_die('Недостатньо прав.');
        }

        check_admin_referer(
            'theme_favicon_delete',
            'favicon_delete_nonce'
        );

        $result = $this->generator->delete();

        if (!$result) {
            $this->redirect('error');
        }

        $this->redirect('deleted');
    }

    /**
     * Redirect after upload.
     */
    private function redirect($status)
    {

        wp_safe_redirect(
            add_query_arg(
                [
                    'page' => 'theme-favicon-generator',
                    'favicon_status' => $status,
                ],
                admin_url('themes.php')
            )
        );

        exit;
    }

}