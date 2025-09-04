/**
 * Jグランツ Enhanced Pro - 管理画面JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';

    /**
     * 初期化
     */
    function init() {
        bindEvents();
        initTooltips();
        checkSystemStatus();
    }

    /**
     * イベントバインド
     */
    function bindEvents() {
        // 設定フォーム送信
        $('.jgrants-settings-form').on('submit', handleSettingsSubmit);
        
        // テスト実行ボタン
        $('.jgrants-test-button').on('click', handleTestExecution);
        
        // API キー表示/非表示切り替え
        $('.jgrants-toggle-password').on('click', togglePasswordVisibility);
        
        // 自動保存機能
        $('.jgrants-auto-save').on('change', handleAutoSave);
    }

    /**
     * ツールチップ初期化
     */
    function initTooltips() {
        $('.jgrants-tooltip').each(function() {
            var $this = $(this);
            var title = $this.attr('title');
            
            if (title) {
                $this.removeAttr('title');
                $this.append('<span class="jgrants-tooltip-text">' + title + '</span>');
            }
        });
    }

    /**
     * システム状態チェック
     */
    function checkSystemStatus() {
        var $statusElements = $('.jgrants-status-check');
        
        $statusElements.each(function() {
            var $this = $(this);
            var checkType = $this.data('check-type');
            
            performStatusCheck(checkType, $this);
        });
    }

    /**
     * 状態チェック実行
     */
    function performStatusCheck(type, $element) {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'jgrants_status_check',
                check_type: type,
                nonce: jgrants_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateStatusElement($element, response.data.status, response.data.message);
                }
            },
            error: function() {
                updateStatusElement($element, 'error', 'チェックに失敗しました');
            }
        });
    }

    /**
     * 状態要素更新
     */
    function updateStatusElement($element, status, message) {
        $element.removeClass('jgrants-status-good jgrants-status-error jgrants-status-disabled');
        $element.addClass('jgrants-status-' + status);
        
        if (message) {
            $element.text(message);
        }
    }

    /**
     * 設定フォーム送信処理
     */
    function handleSettingsSubmit(e) {
        var $form = $(this);
        var $submitButton = $form.find('[type="submit"]');
        
        // バリデーション
        if (!validateSettingsForm($form)) {
            e.preventDefault();
            return false;
        }

        // 送信中状態
        $submitButton.prop('disabled', true).text('保存中...');
        
        // フォーム送信後の処理は通常のPHP処理に任せる
        setTimeout(function() {
            $submitButton.prop('disabled', false).text('設定を保存');
        }, 2000);
    }

    /**
     * 設定フォームバリデーション
     */
    function validateSettingsForm($form) {
        var isValid = true;
        var errors = [];

        // Gemini API キーチェック
        var $apiKey = $form.find('[name="gemini_api_key"]');
        if ($apiKey.length && $apiKey.val().trim() === '') {
            errors.push('Gemini API キーは必須です');
            markFieldError($apiKey);
            isValid = false;
        }

        // 投稿間隔チェック
        var $interval = $form.find('[name="post_interval"]');
        if ($interval.length) {
            var interval = parseInt($interval.val());
            if (interval < 60 || interval > 3600) {
                errors.push('投稿間隔は60-3600秒の範囲で指定してください');
                markFieldError($interval);
                isValid = false;
            }
        }

        // エラー表示
        if (errors.length > 0) {
            showNotice('error', errors.join('<br>'));
        }

        return isValid;
    }

    /**
     * フィールドエラーマーク
     */
    function markFieldError($field) {
        $field.addClass('jgrants-field-error');
        
        setTimeout(function() {
            $field.removeClass('jgrants-field-error');
        }, 3000);
    }

    /**
     * テスト実行処理
     */
    function handleTestExecution(e) {
        e.preventDefault();
        
        var $button = $(this);
        var testType = $button.data('test-type') || 'full';
        
        // ボタン状態変更
        $button.prop('disabled', true).text('テスト実行中...');
        
        // AJAX でテスト実行
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'jgrants_run_test',
                test_type: testType,
                nonce: jgrants_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    showNotice('success', 'テストが完了しました');
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    }
                } else {
                    showNotice('error', response.data.message || 'テストに失敗しました');
                }
            },
            error: function() {
                showNotice('error', 'テスト実行中にエラーが発生しました');
            },
            complete: function() {
                $button.prop('disabled', false).text('テスト実行');
            }
        });
    }

    /**
     * パスワード表示切り替え
     */
    function togglePasswordVisibility(e) {
        e.preventDefault();
        
        var $button = $(this);
        var $input = $button.siblings('input[type="password"], input[type="text"]');
        
        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $button.text('非表示');
        } else {
            $input.attr('type', 'password');
            $button.text('表示');
        }
    }

    /**
     * 自動保存処理
     */
    function handleAutoSave() {
        var $field = $(this);
        var fieldName = $field.attr('name');
        var fieldValue = $field.val();
        
        // デバウンス処理
        clearTimeout($field.data('autosave-timer'));
        
        var timer = setTimeout(function() {
            saveFieldValue(fieldName, fieldValue);
        }, 1000);
        
        $field.data('autosave-timer', timer);
    }

    /**
     * フィールド値保存
     */
    function saveFieldValue(name, value) {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'jgrants_auto_save',
                field_name: name,
                field_value: value,
                nonce: jgrants_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    showNotice('success', '自動保存しました', 2000);
                }
            }
        });
    }

    /**
     * 通知表示
     */
    function showNotice(type, message, duration) {
        duration = duration || 4000;
        
        var $notice = $('<div class="jgrants-notice notice-' + type + '">' + message + '</div>');
        
        // 既存の通知を削除
        $('.jgrants-notice').remove();
        
        // 新しい通知を挿入
        $('.wrap h1').after($notice);
        
        // 自動非表示
        setTimeout(function() {
            $notice.fadeOut(function() {
                $(this).remove();
            });
        }, duration);
    }

    /**
     * ユーティリティ: デバウンス
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    /**
     * ユーティリティ: ローカルストレージ保存
     */
    function saveToLocalStorage(key, value) {
        try {
            localStorage.setItem('jgrants_' + key, JSON.stringify(value));
        } catch (e) {
            console.warn('ローカルストレージへの保存に失敗しました:', e);
        }
    }

    /**
     * ユーティリティ: ローカルストレージ取得
     */
    function getFromLocalStorage(key) {
        try {
            var value = localStorage.getItem('jgrants_' + key);
            return value ? JSON.parse(value) : null;
        } catch (e) {
            console.warn('ローカルストレージからの取得に失敗しました:', e);
            return null;
        }
    }

    // 初期化実行
    init();

    // グローバルオブジェクトとして公開
    window.JGrantsAdmin = {
        showNotice: showNotice,
        saveToLocalStorage: saveToLocalStorage,
        getFromLocalStorage: getFromLocalStorage
    };
});