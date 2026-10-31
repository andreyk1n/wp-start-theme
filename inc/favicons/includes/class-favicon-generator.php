<?php

defined('ABSPATH') || exit;

class Theme_Favicon_Generator
{

    private string $directory;

    public function __construct()
    {
        $this->directory = get_stylesheet_directory() . '/inf/favicons/generated/';
    }

    /**
     * Generate favicon files.
     */
    public function generate($source_path)
    {

        if (!file_exists($source_path)) {
            return new WP_Error('missing_source', 'Файл не знайдено.');
        }

        if (!wp_mkdir_p($this->directory)) {
            return new WP_Error('directory_error', 'Не вдалося створити директорію.');
        }

        $sizes = [
            'favicon-16x16.png' => 16,
            'favicon-32x32.png' => 32,
            'favicon-48x48.png' => 48,
            'apple-touch-icon.png' => 180,
            'android-chrome-192x192.png' => 192,
            'android-chrome-512x512.png' => 512,
            'mstile-150x150.png' => 150,
        ];

        foreach ($sizes as $filename => $size) {

            $editor = wp_get_image_editor($source_path);

            if (is_wp_error($editor)) {
                return $editor;
            }

            $result = $editor->resize($size, $size, true);

            if (is_wp_error($result)) {
                return $result;
            }

            $saved = $editor->save(
                $this->directory . $filename,
                'image/png'
            );

            if (is_wp_error($saved)) {
                return $saved;
            }
        }

        if (!$this->generate_ico()) {
            return new WP_Error('ico_error', 'Не вдалося створити favicon.ico.');
        }

        $this->generate_manifest();
        $this->generate_browserconfig();

        return true;
    }

    /**
     * Generate ICO file.
     */
    private function generate_ico()
    {

        $files = [
            $this->directory . 'favicon-16x16.png',
            $this->directory . 'favicon-32x32.png',
            $this->directory . 'favicon-48x48.png',
        ];

        $images = [];
        $offset = 6 + (16 * count($files));

        foreach ($files as $file) {

            $image = file_get_contents($file);

            if ($image === false) {
                return false;
            }

            $size = getimagesize($file);

            $images[] = [
                'width' => $size[0],
                'height' => $size[1],
                'data' => $image,
                'offset' => $offset,
            ];

            $offset += strlen($image);
        }

        $header = pack('vvv', 0, 1, count($images));
        $entries = '';
        $image_data = '';

        foreach ($images as $image) {

            $entries .= pack(
                'CCCCvvVV',
                $image['width'],
                $image['height'],
                0,
                0,
                1,
                32,
                strlen($image['data']),
                $image['offset']
            );

            $image_data .= $image['data'];
        }

        return file_put_contents(
            $this->directory . 'favicon.ico',
            $header . $entries . $image_data
        ) !== false;
    }

    /**
     * Generate web manifest.
     */
    private function generate_manifest()
    {

        $manifest = [
            'name' => get_bloginfo('name'),
            'short_name' => get_bloginfo('name'),
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#ffffff',
            'icons' => [
                [
                    'src' => 'android-chrome-192x192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                ],
                [
                    'src' => 'android-chrome-512x512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                ],
            ],
        ];

        file_put_contents(
            $this->directory . 'site.webmanifest',
            wp_json_encode(
                $manifest,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
    }

    /**
     * Generate browserconfig.xml.
     */
    private function generate_browserconfig()
    {

        $xml = '<?xml version="1.0" encoding="utf-8"?>'
            . '<browserconfig>'
            . '<msapplication>'
            . '<tile>'
            . '<square150x150logo src="mstile-150x150.png"/>'
            . '<TileColor>#ffffff</TileColor>'
            . '</tile>'
            . '</msapplication>'
            . '</browserconfig>';

        file_put_contents(
            $this->directory . 'browserconfig.xml',
            $xml
        );
    }

    /**
     * Delete all generated favicon files.
     */
    public function delete(): bool
    {

        $files = [
            'favicon-source.png',
            'favicon-source.jpg',
            'favicon-source.webp',

            'favicon.ico',

            'favicon-16x16.png',
            'favicon-32x32.png',
            'favicon-48x48.png',

            'apple-touch-icon.png',

            'android-chrome-192x192.png',
            'android-chrome-512x512.png',

            'mstile-150x150.png',

            'site.webmanifest',
            'browserconfig.xml',
        ];

        foreach ($files as $file) {

            $path = $this->directory . $file;

            if (file_exists($path) && !unlink($path)) {
                return false;
            }
        }

        delete_option('theme_favicon_version');

        return true;
    }

}