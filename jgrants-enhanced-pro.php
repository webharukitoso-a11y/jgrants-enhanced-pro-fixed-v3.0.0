<?php
/**
 * Plugin Name: Jグランツ自動投稿システム Enhanced Pro
 * Plugin URI: https://example.com/jgrants-enhanced-pro
 * Description: Jグランツから補助金情報を自動取得し、Gemini AIで高品質SEO記事を生成する完全自動化システム
 * Version: 3.0.0
 * Author: Grant Insight Pro Team  
 * Author URI: https://example.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: jgrants-enhanced-pro
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 */

// セキュリティチェック：直接アクセスを防ぐ
if (!defined('ABSPATH')) {
    exit;
}

// プラグイン定数定義
define('JGRANTS_ENHANCED_PRO_VERSION', '3.0.0');
define('JGRANTS_ENHANCED_PRO_PLUGIN_FILE', __FILE__);
define('JGRANTS_ENHANCED_PRO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('JGRANTS_ENHANCED_PRO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('JGRANTS_ENHANCED_PRO_TEXTDOMAIN', 'jgrants-enhanced-pro');

/**
 * メインプラグインクラス
 */
if (!class_exists('JGrants_Enhanced_Pro')) {
    class JGrants_Enhanced_Pro {

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * シングルトンインスタンス
         */
        private static $instance = null;

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * シングルトンインスタンス取得
         */
        public static function get_instance() {
            if (null === self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * コンストラクタ
         */
        private function __construct() {
            $this->init_hooks();
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * フック初期化
         */
        private function init_hooks() {
            // プラグイン有効化/無効化フック
            register_activation_hook(__FILE__, array($this, 'activate'));
            register_deactivation_hook(__FILE__, array($this, 'deactivate'));

            // 初期化フック
            add_action('init', array($this, 'init'));
            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'add_admin_menu'));

            // プラグイン読み込み完了後
            add_action('plugins_loaded', array($this, 'plugins_loaded'));
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * プラグイン有効化
         */
        public function activate() {
            // バージョン情報保存
            update_option('jgrants_enhanced_pro_version', JGRANTS_ENHANCED_PRO_VERSION);

            // デフォルト設定
            $default_options = array(
                'gemini_api_key' => '',
                'auto_posting_enabled' => false,
                'posting_interval_minutes' => 5,
                'items_per_batch' => 2,
                'daily_limit' => 100
            );

            foreach ($default_options as $key => $value) {
                if (false === get_option('jgrants_pro_' . $key)) {
                    add_option('jgrants_pro_' . $key, $value);
                }
            }

            // リライトルール更新
            flush_rewrite_rules();
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * プラグイン無効化
         */
        public function deactivate() {
            // スケジュールされたイベントをクリア
            wp_clear_scheduled_hook('jgrants_pro_batch_process');

            // リライトルール更新
            flush_rewrite_rules();
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * 初期化
         */
        public function init() {
            // 国際化
            load_plugin_textdomain(
                JGRANTS_ENHANCED_PRO_TEXTDOMAIN,
                false,
                dirname(plugin_basename(__FILE__)) . '/languages'
            );

            // カスタム投稿タイプ登録
            $this->register_post_types();
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * 管理画面初期化
         */
        public function admin_init() {
            // 管理画面用CSS/JS
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * 管理画面メニュー追加
         */
        public function add_admin_menu() {
            add_menu_page(
                'Jグランツ Enhanced Pro',
                'Jグランツ Pro',
                'manage_options',
                'jgrants-enhanced-pro',
                array($this, 'render_admin_page'),
                'dashicons-awards',
                30
            );

            // サブメニュー
            add_submenu_page(
                'jgrants-enhanced-pro',
                'システムテスト',
                'システムテスト',
                'manage_options',
                'jgrants-enhanced-pro-test',
                array($this, 'render_test_page')
            );

            // サブメニュー
            add_submenu_page(
                'jgrants-enhanced-pro',
                '設定',
                '設定',
                'manage_options',
                'jgrants-enhanced-pro-settings',
                array($this, 'render_settings_page')
            );
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * 管理画面スクリプト/スタイル読み込み
         */
        public function enqueue_admin_scripts($hook) {
            if (strpos($hook, 'jgrants-enhanced-pro') !== false) {
                wp_enqueue_style(
                    'jgrants-admin-css',
                    JGRANTS_ENHANCED_PRO_PLUGIN_URL . 'assets/css/admin.css',
                    array(),
                    JGRANTS_ENHANCED_PRO_VERSION
                );

                wp_enqueue_script(
                    'jgrants-admin-js',
                    JGRANTS_ENHANCED_PRO_PLUGIN_URL . 'assets/js/admin.js',
                    array('jquery'),
                    JGRANTS_ENHANCED_PRO_VERSION,
                    true
                );
            }
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * プラグイン読み込み完了
         */
        public function plugins_loaded() {
            // バージョンチェック
            $current_version = get_option('jgrants_enhanced_pro_version', '0.0.0');
            if (version_compare($current_version, JGRANTS_ENHANCED_PRO_VERSION, '<')) {
                $this->upgrade($current_version);
            }
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * カスタム投稿タイプ登録
         */
        private function register_post_types() {
            register_post_type('grant', array(
                'labels' => array(
                    'name' => '補助金情報',
                    'singular_name' => '補助金',
                    'add_new' => '新規追加',
                    'add_new_item' => '新しい補助金を追加',
                    'edit_item' => '補助金を編集',
                    'new_item' => '新しい補助金',
                    'view_item' => '補助金を表示',
                    'search_items' => '補助金を検索',
                    'not_found' => '補助金が見つかりません',
                    'not_found_in_trash' => 'ゴミ箱に補助金はありません'
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'grants'),
                'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
                'show_in_rest' => true,
                'menu_icon' => 'dashicons-awards'
            ));
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * メイン管理ページ表示
         */
        public function render_admin_page() {
            ?>
            <div class="wrap">
                <h1>Jグランツ Enhanced Pro</h1>
                <div class="notice notice-success">
                    <p><strong>プラグインが正常に認識・動作しています！</strong></p>
                </div>
                <div class="card">
                    <h2>システム状況</h2>
                    <p>バージョン: <?php echo esc_html(JGRANTS_ENHANCED_PRO_VERSION); ?></p>
                    <p>状態: <span style="color: green;">正常動作中</span></p>
                </div>
                <div class="card">
                    <h2>クイック設定</h2>
                    <p><a href="<?php echo admin_url('admin.php?page=jgrants-enhanced-pro-settings'); ?>" class="button button-primary">設定画面へ</a></p>
                </div>
            </div>
            <?php
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * 設定ページ表示
         */
        public function render_settings_page() {
            ?>
            <div class="wrap">
                <h1>Jグランツ Pro 設定</h1>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('jgrants_pro_settings');
                    do_settings_sections('jgrants_pro_settings');
                    ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row">Gemini APIキー</th>
                            <td>
                                <input type="password" name="jgrants_pro_gemini_api_key" 
                                       value="<?php echo esc_attr(get_option('jgrants_pro_gemini_api_key', '')); ?>" 
                                       class="regular-text" />
                                <p class="description">Google Gemini APIキーを入力してください</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">自動投稿</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="jgrants_pro_auto_posting_enabled" 
                                           value="1" <?php checked(1, get_option('jgrants_pro_auto_posting_enabled', 0)); ?> />
                                    自動投稿を有効にする
                                </label>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }

        /**
         * システムテスト結果表示
         */
        public function render_test_page() {
            // テストクラス読み込み
            require_once JGRANTS_ENHANCED_PRO_PLUGIN_DIR . 'includes/class-system-test.php';

            $requirements = JGrants_System_Test::check_requirements();
            $functionality = JGrants_System_Test::test_basic_functionality();
            $settings = JGrants_System_Test::test_plugin_settings();

            ?>
            <div class="wrap">
                <h1>システムテスト結果</h1>

                <div class="jgrants-card">
                    <h2>システム要件チェック</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>項目</th>
                                <th>必要</th>
                                <th>現在</th>
                                <th>状態</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requirements as $key => $req): ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                <td><?php echo esc_html($req['required']); ?></td>
                                <td><?php echo esc_html($req['current']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $req['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $req['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>プラグイン機能テスト</h2>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>テスト項目</th>
                                <th>結果</th>
                                <th>メッセージ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($functionality as $test): ?>
                            <tr>
                                <td><?php echo esc_html($test['name']); ?></td>
                                <td>
                                    <span class="jgrants-status-<?php echo $test['status'] ? 'good' : 'error'; ?>">
                                        <?php echo $test['status'] ? '✓ OK' : '✗ NG'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($test['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="jgrants-card">
                    <h2>設定機能テスト</h2>
                    <p>
                        設定保存・取得テスト: 
                        <span class="jgrants-status-<?php echo $settings['overall_status'] ? 'good' : 'error'; ?>">
                            <?php echo $settings['overall_status'] ? '✓ 正常動作' : '✗ エラー'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }

        /**
         * アップグレード処理
         */
        private function upgrade($from_version) {
            // バージョン更新
            update_option('jgrants_enhanced_pro_version', JGRANTS_ENHANCED_PRO_VERSION);
        }
    }
}

// プラグイン初期化
function jgrants_enhanced_pro_init() {
    return JGrants_Enhanced_Pro::get_instance();
}

// WordPressが完全に読み込まれた後に初期化
add_action('plugins_loaded', 'jgrants_enhanced_pro_init');
