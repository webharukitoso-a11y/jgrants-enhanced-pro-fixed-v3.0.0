jQuery(document).ready(function($) {
    // Jグランツ Enhanced Pro Admin JavaScript

    // 設定フォームの処理
    $('#jgrants-settings-form').on('submit', function(e) {
        var apiKey = $('#jgrants_pro_gemini_api_key').val();
        if (!apiKey.trim()) {
            alert('Gemini APIキーを入力してください。');
            e.preventDefault();
            return false;
        }
    });

    // APIキーのマスク/表示切り替え
    $('.jgrants-toggle-api-key').on('click', function(e) {
        e.preventDefault();
        var input = $(this).prev('input');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).text('非表示');
        } else {
            input.attr('type', 'password');
            $(this).text('表示');
        }
    });

    // 接続テストボタン
    $('.jgrants-test-connection').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var originalText = button.text();

        button.prop('disabled', true).text('接続中...');

        // TODO: AJAX接続テストを実装
        setTimeout(function() {
            button.prop('disabled', false).text(originalText);
            alert('接続テスト機能は今後実装予定です。');
        }, 2000);
    });

    // 成功メッセージの自動非表示
    $('.notice.is-dismissible').delay(5000).fadeOut();

    console.log('Jグランツ Enhanced Pro Admin JS loaded');
});
