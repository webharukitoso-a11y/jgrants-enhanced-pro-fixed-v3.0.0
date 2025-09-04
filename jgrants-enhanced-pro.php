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
define('JGRANTS_ENHANCED_PRO_VERSION', '3.0.1');
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
         * シングルトンインスタンス
         */
        private static $instance = null;

        /**
         * プラグインオプション
         */
        private $options = array();

        /**
         * インスタンス取得
         */
        public static function get_instance() {
            if (null === self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * コンストラクタ
         */
        private function __construct() {
            $this->init();
        }

        /**
         * 初期化
         */
        private function init() {
            // アクティベーション・非アクティベーション
            register_activation_hook(JGRANTS_ENHANCED_PRO_PLUGIN_FILE, array($this, 'activate'));
            register_deactivation_hook(JGRANTS_ENHANCED_PRO_PLUGIN_FILE, array($this, 'deactivate'));

            // 管理画面フック
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

            // プラグイン設定の読み込み
            $this->load_options();
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
                'auto_post_enabled' => false,
                'post_interval' => 300, // 5分
                'daily_limit' => 100,
                'category_id' => 0
            );

            // 既存設定がない場合のみデフォルト設定を保存
            if (!get_option('jgrants_enhanced_pro_options')) {
                update_option('jgrants_enhanced_pro_options', $default_options);
            }

            // カスタム投稿タイプの作成
            $this->create_custom_post_type();
            flush_rewrite_rules();
        }

        /**
         * プラグイン非有効化
         */
        public function deactivate() {
            // 予定されたイベントをクリア
            wp_clear_scheduled_hook('jgrants_pro_auto_fetch');
            flush_rewrite_rules();
        }

        /**
         * オプション読み込み
         */
        private function load_options() {
            $this->options = get_option('jgrants_enhanced_pro_options', array());
        }

        /**
         * 管理画面メニュー追加
         */
        public function add_admin_menu() {
            add_menu_page(
                'Jグランツ Pro',
                'Jグランツ Pro',
                'manage_options',
                'jgrants-enhanced-pro',
                array($this, 'render_admin_page'),
                'dashicons-grants',
                30
            );

            add_submenu_page(
                'jgrants-enhanced-pro',
                'システムテスト',
                'システムテスト', 
                'manage_options',
                'jgrants-test',
                array($this, 'render_test_page')
            );

            add_submenu_page(
                'jgrants-enhanced-pro',
                '設定',
                '設定',
                'manage_options',
                'jgrants-settings',
                array($this, 'render_settings_page')
            );
        }

        /**
         * 管理画面初期化
         */
        public function admin_init() {
            register_setting('jgrants_enhanced_pro_settings', 'jgrants_enhanced_pro_options', array($this, 'validate_options'));
        }

        /**
         * 管理画面スクリプト読み込み
         */
        public function admin_enqueue_scripts($hook) {
            if (strpos($hook, 'jgrants') !== false) {
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
         * メイン管理画面
         */
        public function render_admin_page() {
            ?>
            <div class="wrap">
                <h1>Jグランツ自動投稿システム Enhanced Pro</h1>
                
                <div class="jgrants-dashboard">
                    <div class="jgrants-card">
                        <h2>システム状況</h2>
                        <div class="jgrants-status">
                            <p>
                                <strong>プラグインバージョン:</strong> <?php echo esc_html(JGRANTS_ENHANCED_PRO_VERSION); ?>
                            </p>
                            <p>
                                <strong>自動投稿:</strong> 
                                <span class="jgrants-status-<?php echo !empty($this->options['auto_post_enabled']) ? 'good' : 'disabled'; ?>">
                                    <?php echo !empty($this->options['auto_post_enabled']) ? '有効' : '無効'; ?>
                                </span>
                            </p>
                            <p>
                                <strong>Gemini API:</strong> 
                                <span class="jgrants-status-<?php echo !empty($this->options['gemini_api_key']) ? 'good' : 'error'; ?>">
                                    <?php echo !empty($this->options['gemini_api_key']) ? '設定済み' : '未設定'; ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="jgrants-card">
                        <h2>クイックアクション</h2>
                        <div class="jgrants-actions">
                            <a href="<?php echo admin_url('admin.php?page=jgrants-test'); ?>" class="button button-primary">
                                システムテスト実行
                            </a>
                            <a href="<?php echo admin_url('admin.php?page=jgrants-settings'); ?>" class="button">
                                設定画面
                            </a>
                        </div>
                    </div>
                </div>

                <style>
                .jgrants-dashboard {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 20px;
                    margin-top: 20px;
                }
                .jgrants-card {
                    background: #fff;
                    border: 1px solid #ccd0d4;
                    padding: 20px;
                    border-radius: 4px;
                }
                .jgrants-status-good { color: #00a32a; font-weight: bold; }
                .jgrants-status-error { color: #d63638; font-weight: bold; }
                .jgrants-status-disabled { color: #646970; }
                .jgrants-actions .button { margin-right: 10px; }
                </style>
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
                                <td><?php echo esc_html(is_bool($req['required']) ? ($req['required'] ? 'Yes' : 'No') : $req['required']); ?></td>
                                <td><?php echo esc_html(is_bool($req['current']) ? ($req['current'] ? 'Yes' : 'No') : $req['current']); ?></td>
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

                <style>
                .jgrants-card {
                    background: #fff;
                    border: 1px solid #ccd0d4;
                    padding: 20px;
                    margin: 20px 0;
                    border-radius: 4px;
                }
                .jgrants-status-good { color: #00a32a; font-weight: bold; }
                .jgrants-status-error { color: #d63638; font-weight: bold; }
                </style>
            </div>
            <?php
        }

        /**
         * 設定画面
         */
        public function render_settings_page() {
            if (isset($_POST['submit'])) {
                check_admin_referer('jgrants_settings_nonce');
                
                $options = array(
                    'gemini_api_key' => sanitize_text_field($_POST['gemini_api_key'] ?? ''),
                    'auto_post_enabled' => !empty($_POST['auto_post_enabled']),
                    'post_interval' => absint($_POST['post_interval'] ?? 300),
                    'daily_limit' => absint($_POST['daily_limit'] ?? 100),
                    'category_id' => absint($_POST['category_id'] ?? 0)
                );
                
                update_option('jgrants_enhanced_pro_options', $options);
                $this->options = $options;
                
                echo '<div class="notice notice-success"><p>設定を保存しました。</p></div>';
            }

            $categories = get_categories();
            ?>
            <div class="wrap">
                <h1>Jグランツ Pro 設定</h1>
                
                <form method="post" action="">
                    <?php wp_nonce_field('jgrants_settings_nonce'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="gemini_api_key">Gemini API キー</label>
                            </th>
                            <td>
                                <input type="password" 
                                       id="gemini_api_key" 
                                       name="gemini_api_key" 
                                       value="<?php echo esc_attr($this->options['gemini_api_key'] ?? ''); ?>" 
                                       class="regular-text" />
                                <p class="description">Google Gemini API キーを入力してください。</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">自動投稿</th>
                            <td>
                                <fieldset>
                                    <label>
                                        <input type="checkbox" 
                                               name="auto_post_enabled" 
                                               value="1" 
                                               <?php checked(!empty($this->options['auto_post_enabled'])); ?> />
                                        自動投稿を有効にする
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="post_interval">投稿間隔（秒）</label>
                            </th>
                            <td>
                                <input type="number" 
                                       id="post_interval" 
                                       name="post_interval" 
                                       value="<?php echo esc_attr($this->options['post_interval'] ?? 300); ?>" 
                                       min="60" 
                                       max="3600" 
                                       class="small-text" />
                                <p class="description">記事生成の間隔を秒で指定（60-3600秒）</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="daily_limit">1日の投稿上限</label>
                            </th>
                            <td>
                                <input type="number" 
                                       id="daily_limit" 
                                       name="daily_limit" 
                                       value="<?php echo esc_attr($this->options['daily_limit'] ?? 100); ?>" 
                                       min="1" 
                                       max="1000" 
                                       class="small-text" />
                                <p class="description">1日あたりの最大投稿数</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="category_id">投稿カテゴリー</label>
                            </th>
                            <td>
                                <select id="category_id" name="category_id">
                                    <option value="0">未分類</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>" 
                                                <?php selected($this->options['category_id'] ?? 0, $category->term_id); ?>>
                                            <?php echo esc_html($category->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button('設定を保存'); ?>
                </form>
            </div>
            <?php
        }

        /**
         * オプション検証
         */
        public function validate_options($input) {
            $validated = array();
            
            $validated['gemini_api_key'] = sanitize_text_field($input['gemini_api_key'] ?? '');
            $validated['auto_post_enabled'] = !empty($input['auto_post_enabled']);
            $validated['post_interval'] = max(60, min(3600, absint($input['post_interval'] ?? 300)));
            $validated['daily_limit'] = max(1, min(1000, absint($input['daily_limit'] ?? 100)));
            $validated['category_id'] = absint($input['category_id'] ?? 0);
            
            return $validated;
        }

        /**
         * カスタム投稿タイプ作成
         */
        private function create_custom_post_type() {
            register_post_type('jgrants_grant', array(
                'labels' => array(
                    'name' => '補助金情報',
                    'singular_name' => '補助金',
                ),
                'public' => true,
                'has_archive' => true,
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
                'menu_icon' => 'dashicons-money-alt',
                'rewrite' => array('slug' => 'grants')
            ));
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