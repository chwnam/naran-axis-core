<?php
/**
 * Plugin Name:       Naran Axis Core
 * Description:       A WordPress must-use (MU) plugin for developing highly customized, modern PHP based websites.
 * Version:           0.0.0
 * Plugin URI:        https://github.com/chwnam/naran-axis-core
 * Author:            Changwoo
 * Author URI:        https://blog.changwoo.pe.kr
 * Textdomain:        axis
 * Domain Path:       languages/
 * Network:           false
 * Requires at least: 5.5
 * Requires PHP:      7.4
 * License:           GPLv2 or later
 * License URI:       https://github.com/chwnam/naran-axis-core/blob/main/LICENSE
 */

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    define('NARAN_AXIS_CORE_MAIN', __FILE__);
    define('NARAN_AXIS_VERSION', '0.0.0');
} else {
    add_action(
        'admin_notices',
        function () {
            echo '<div class="notice notice-error"><p>'
                . __('Please run \'composer da\' to run Naran Axis Core plugin correctly.', 'axis')
                . '</p></div>';
        }
    );
}
