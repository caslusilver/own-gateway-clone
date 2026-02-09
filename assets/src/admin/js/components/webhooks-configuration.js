import {debounce} from '../utils/debounce';
import {
    isStatusPage,
    isGatewaySettingsPage,
    urlParams,
} from '../utils/screen';

jQuery(function ($) {
    const gateway = urlParams.get('section');
    const ajaxUrl = window.ajaxurl;
    const ajaxNonce = _wooAsaasAdminSettings.nonce;

    const $anticipationCheckbox = $(`#woocommerce_${gateway}_anticipation`);
    const $environmentSelect = $(`#woocommerce_${gateway}_endpoint`);
    const $apiKeyField = $(`#woocommerce_${gateway}_api_key`);
    const $emailField = $(`#woocommerce_${gateway}_email_notification`);
    const $reenableButton = $('.reenable-queue');
    const $saveButton = $('.woocommerce-save-button');

    const $statusConnectionRow = $('#webhook-status-connection');
    const $statusQueueRow = $('#webhook-status-queue');

    const $loaderElement = $('<span class="preloader"></span>');
    const $messageElement = $('<span class="apikey-message"></span>');

    const messageIcons = {
        'yes': '<mark class="connectivity-mark yes"><span class="dashicons dashicons-yes"></span></mark>',
        'warning': '<mark class="connectivity-mark warning"><span class="dashicons dashicons-warning"></span></mark>',
        'error': '<mark class="connectivity-mark error"><span class="dashicons dashicons-no"></span></mark>'
    };

    $emailField.prop('required', true);

    if (isStatusPage()) {
        apiConnectionHealthCheck();
        webhookHealthCheck();
    }

    function apiConnectionHealthCheck() {
        $.ajax({
            url: ajaxUrl,
            data: {action: 'api_connection_health_check'},
            success: function (response) {
                let status = response.data.status;

                $statusConnectionRow.find('.connection-status').html(messageIcons[status]).show();
            },
            error: function (response) {
                let status = response.responseJSON.data.status;
                let message = response.responseJSON.data.message;
                let code = response.responseJSON.data.code;

                $statusConnectionRow.find('.connection-status').html(messageIcons[status] + ' (' + code + ') - ' + message).show();
            }
        }).always(function () {
            $statusConnectionRow.find('.loader').hide();
        });
    }

    function webhookHealthCheck() {
        $.ajax({
            url: ajaxUrl,
            data: {action: 'webhook_health_check'},
            success: function (response) {
                $statusQueueRow.find('.loader').hide();
                console.log(response);
                const isEnabled = response.data.enabled;
                const isInterrupted = response.data.interrupted;

                if (isEnabled && !isInterrupted) {
                    $statusQueueRow.find('.queue-yes').show();
                    disableQueueButton(false);

                    return;
                }

                $statusQueueRow.find('.queue-error').show();
                enableQueueButton();
            }, error: function () {
                $statusQueueRow.find('.queue-error').show();
            },
        }).always(function () {
            $statusQueueRow.find('.loader').hide();
        });
    }

    if (isGatewaySettingsPage()) {
        const validateSettings = async () => {
            try {
                const isConnected = await checkApiConnectionStatus();

                if (isConnected) {
                    await checkWebhookStatus();
                    return;
                }

                disableQueueButton(false);
                enableSaveButton();
            } catch (error) {
                disableQueueButton(false);
                enableSaveButton();
            }
        };
        void validateSettings();

        $environmentSelect.on('change', function () {
            void validateSettings();
        });

        $apiKeyField.on('input', debounce(function () {
            void validateSettings();
        }, 1200));
    }

    function checkApiConnectionStatus() {
        let apiKey = $apiKeyField.val();
        let environmentUrl = $environmentSelect.val();

        $loaderElement.remove();
        $messageElement.remove();

        if (apiKey === '') {
            return Promise.resolve(false);
        }

        $apiKeyField.after($loaderElement);
        return new Promise((resolve) => {
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'check_api_connection_status',
                    url: environmentUrl,
                    api_key: apiKey,
                    _nonce: ajaxNonce,
                },
                beforeSend: function () {
                    disableSaveButton();
                },
                success: function (response) {
                    let status = response.data.status;
                    let message = response.data.message;

                    $messageElement.html(messageIcons[status] + message);

                    $anticipationCheckbox.prop('disabled', false);

                    resolve(true);
                },
                error: function (response) {
                    let status = response.responseJSON.data.status;
                    let message = response.responseJSON.data.message;
                    let code = response.responseJSON.data.code;

                    $messageElement.html(messageIcons[status] + ' (' + code + ')  - ' + message);

                    $anticipationCheckbox.prop('disabled', true);

                    resolve(false);
                }
            }).always(function () {
                $apiKeyField.after($messageElement);
                enableSaveButton();
                $loaderElement.remove();
            });
        });
    }

    function checkWebhookStatus() {
        const apiKey = $apiKeyField.val();
        let environmentUrl = $environmentSelect.val();

        return new Promise((resolve) => {
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'check_webhook_status',
                    url: environmentUrl,
                    api_key: apiKey,
                    _nonce: ajaxNonce,
                },
                beforeSend: function () {
                    disableSaveButton();
                },
                success: function (response) {
                    const isEnabled = response.data.enabled;
                    const isInterrupted = response.data.interrupted;

                    if (!isEnabled || isInterrupted) {
                        enableQueueButton();
                    }

                    updateExistingWebhookEmail(response.data.email);
                    resolve(true);
                },
                error: function () {
                    disableQueueButton(false);

                    resolve(false);
                }
            }).always(function () {
                enableSaveButton();
            });
        });
    }

    $reenableButton.on('click', function (e) {
        e.preventDefault();

        disableQueueButton(false);
        reenableWebhookQueue();
    });

    function reenableWebhookQueue() {
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {action: 'reenable_webhook_queue'},
            beforeSend: function () {
                $reenableButton.html('<span class="preloader"></span> Aguarde...');
            },
            success: function (response) {
                const {enabled, interrupted} = response.data;
                const settingsState = !enabled || interrupted;

                disableQueueButton(settingsState);

                $statusQueueRow.find('.error').removeClass('error').addClass('yes');
                $statusQueueRow
                    .find('.dashicons-no')
                    .removeClass('dashicons-no')
                    .addClass('dashicons-yes');

                let messageContainer = $('<span class="reenable-message"></span>');
                messageContainer.insertAfter($reenableButton);
                messageContainer.html('✅ Fila reativada com sucesso');

                $reenableButton.text('Reabilitar fila de webhooks');

                setTimeout(function () {
                    messageContainer.remove();
                }, 5000);
            },
            error: function () {
            },
        });
    }

    function updateExistingWebhookEmail(email) {
        const emailField = $emailField.val();

        if (emailField === email) {
            return;
        }

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {action: 'update_existing_webhook_email'},
        });
    }

    function enableSaveButton() {
        $saveButton.prop('disabled', false);
    }

    function disableSaveButton() {
        $saveButton.prop('disabled', true);
    }

    function enableQueueButton() {
        $reenableButton.prop('disabled', false);
    }

    function disableQueueButton() {
        $reenableButton.attr('disabled', true);
    }
});