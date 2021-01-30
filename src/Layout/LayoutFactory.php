<?php


namespace Naran\Axis\Core\Layout;


class LayoutFactory
{
    public static function pluginLayout(array $args = []): PluginLayout
    {
        try {
            $args = wp_parse_args($args, static::getDefaultArgs());

            if (empty($args['main_file'])) {
                throw new LayoutFailException('Argument \'main_file\' is required for plugin layout.');
            }

            if (empty($args['slug'])) {
                $dirname    = dirname($args['main_file']);
                $pluginPath = untrailingslashit(wp_normalize_path(WP_PLUGIN_DIR));
                if ($dirname === $pluginPath) {
                    $args['slug'] = pathinfo($args['main_file'], PATHINFO_FILENAME);
                } else {
                    $args['slug'] = pathinfo($dirname, PATHINFO_BASENAME);
                }
            }

            if (empty($args['title'])) {
                $args['title'] = $args['slug'];
            }

            $layout = new PluginLayout();

            $layout->setMainFile($args['main_file']);
            $layout->setSlug($args['slug']);
            self::setOptionalArgs($layout, $args);
        } catch (LayoutFailException $e) {
            wp_die(esc_html($e->getMessage()), 'Layout failure');
        }

        LayoutPool::add($layout);

        return $layout;
    }

    private static function getDefaultArgs(): array
    {
        return [
            'main_file'       => '',
            'slug'            => '',
            'textdomain'      => '',
            'title'           => '',
            'version'         => '',
            'scheme_path'     => null,
            'module_provider' => null,
        ];
    }

    private static function setOptionalArgs(LayoutInterface $layout, array $args)
    {
        if ( ! empty($args['textdomain'])) {
            $layout->setTextdomain($args['textdomain']);
        }

        if ( ! empty($args['title'])) {
            $layout->setTitle($args['title']);
        }

        if ( ! empty($args['version'])) {
            $layout->setVersion($args['version']);
        }

        if ( ! empty($args['scheme_path'])) {
            $layout->setSchemePath($args['scheme_path']);
        } elseif ($layout instanceof PluginLayout) {
            $layout->setSchemePath(dirname($layout->getMainFile()) . '/src/scheme.php');
        } elseif ($layout instanceof ThemeLayout) {
            $layout->setSchemePath(get_stylesheet_directory() . '/src/scheme.php');
        }

        $layout->setModuleProvider($args['module_provider']);
    }

    public static function themeLayout(array $args = []): ThemeLayout
    {
        try {
            $args = wp_parse_args($args, static::getDefaultArgs());

            if (empty($args['slug'])) {
                throw new LayoutFailException('Argument \'slug\' is required for a theme layout.');
            }

            if (empty($args['title'])) {
                $args['title'] = $args['slug'];
            }

            $layout = new ThemeLayout();
            self::setOptionalArgs($layout, $args);
        } catch (LayoutFailException $e) {
            wp_die(esc_html($e->getMessage()), 'Layout failure');
        }

        LayoutPool::add($layout);

        return $layout;
    }
}
