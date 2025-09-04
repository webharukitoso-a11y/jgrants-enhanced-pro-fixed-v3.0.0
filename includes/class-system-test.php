<?php
/**
 * Jグランツ Enhanced Pro - テストクラス
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

/**
 * システムテストクラス
 */
if (!class_exists('JGrants_System_Test')) {
    class JGrants_System_Test {

        /**
         * システム要件チェック
         */
        public static function check_requirements() {
            $results = array(
                'php_version' => array(
                    'required' => '7.4',
                    'current' => PHP_VERSION,
                    'status' => version_compare(PHP_VERSION, '7.4', '>=')
                ),
                'wordpress_version' => array(
                    'required' => '6.0',
                    'current' => get_bloginfo('version'),
                    'status' => version_compare(get_bloginfo('version'), '6.0', '>=')
                ),
                'curl_available' => array(
                    'required' => true,
                    'current' => function_exists('curl_init'),
                    'status' => function_exists('curl_init')
                ),
                'json_available' => array(
                    'required' => true,
                    'current' => function_exists('json_encode'),
                    'status' => function_exists('json_encode')
                )
            );

            return $results;
        }

        /**
         * プラグイン動作テスト
         */
        public static function test_basic_functionality() {
            $tests = array();

            // WordPressフック動作テスト
            $tests['wordpress_hooks'] = array(
                'name' => 'WordPress フック',
                'status' => function_exists('add_action') && function_exists('add_filter'),
                'message' => function_exists('add_action') ? 'フック機能は正常です' : 'フック機能に問題があります'
            );

            // データベース接続テスト
            global $wpdb;
            $tests['database'] = array(
                'name' => 'データベース接続',
                'status' => !empty($wpdb) && method_exists($wpdb, 'get_results'),
                'message' => !empty($wpdb) ? 'データベース接続は正常です' : 'データベース接続に問題があります'
            );

            // 管理者権限テスト
            $tests['admin_capabilities'] = array(
                'name' => '管理者権限',
                'status' => current_user_can('manage_options'),
                'message' => current_user_can('manage_options') ? '管理者権限があります' : '管理者権限が必要です'
            );

            return $tests;
        }

        /**
         * プラグイン設定テスト
         */
        public static function test_plugin_settings() {
            // テスト用設定の保存・取得
            $test_key = 'jgrants_pro_test_setting';
            $test_value = 'test_value_' . time();

            // 設定保存テスト
            $save_result = update_option($test_key, $test_value);

            // 設定取得テスト
            $retrieved_value = get_option($test_key);

            // テスト設定削除
            delete_option($test_key);

            return array(
                'save_setting' => $save_result,
                'retrieve_setting' => ($retrieved_value === $test_value),
                'overall_status' => ($save_result && $retrieved_value === $test_value)
            );
        }
    }
}
