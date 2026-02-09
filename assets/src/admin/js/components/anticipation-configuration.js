import { urlParams, isCreditCardGatewaySettingsPage } from "../utils/screen";

const ALLOWED_PERSON_TYPE = "JURIDICA";

jQuery(function ($) {
  if (!isCreditCardGatewaySettingsPage()) {
    return;
  }

  const gateway = urlParams.get("section");
  const ajaxUrl = window.ajaxurl;
  const ajaxNonce = _wooAsaasAdminSettings.nonce;

  const $enviromentSelect = $(`#woocommerce_${gateway}_endpoint`);
  const $anticipationCheckbox = $(`#woocommerce_${gateway}_anticipation`);
  const $apiKeyField = $(`#woocommerce_${gateway}_api_key`);
  const $loaderElement = $('<span class="preloader"></span>');
  const $saveButton = $(".woocommerce-save-button");
  const $isEmptyApiKey = $apiKeyField.val() === "";

  let isRequesting = false;
  let allowAnticipation = false;

  $apiKeyField.on("change", function () {
    if (!$isEmptyApiKey && $(this).val() !== "") {
      return;
    }
    $anticipationCheckbox.prop("checked", false);
    $anticipationCheckbox.prop("disabled", true);
  });

  $enviromentSelect.on("change", () => checkAnticipationOption());

  $anticipationCheckbox.on("change", function () {
    const enabled = $(this).prop("checked");

    if (!enabled || isRequesting) {
      checkAnticipationOption();
      return;
    }

    checkAnticipationAllowedPersonType();
  });

  checkAnticipationAllowed();

  function checkAnticipationAllowed() {
    $.ajax({
      url: ajaxUrl,
      type: "POST",
      data: { action: "check_anticipation_allowed" },
      success: function (response) {
        const { data } = response;
        allowAnticipation = !!data.allowAnticipation;
      },
    });
  }

  function checkAnticipationOption() {
    $anticipationCheckbox.prop("checked", false);
    $anticipationCheckbox.after($loaderElement);
    $anticipationCheckbox.hide();

    $.ajax({
      url: ajaxUrl,
      type: "POST",
      data: { action: "check_anticipation_option", _nonce: ajaxNonce },
      beforeSend: function () {
        disableCheckboxState();
      },
      success: function () {
        enableCheckboxState();
      },
    });
  }

  function checkAnticipationAllowedPersonType() {
    $anticipationCheckbox.after($loaderElement);
    $anticipationCheckbox.hide();

    $.ajax({
      url: ajaxUrl,
      data: {
        action: "check_anticipation_allowed_person_type",
        _nonce: ajaxNonce,
      },
      beforeSend: function () {
        disableCheckboxState();
      },
      success: function (response) {
        const { data } = response;
        allowAnticipation = data.personType === ALLOWED_PERSON_TYPE;

        enableCheckboxState();
      },
      error: function () {
        disableCheckboxOnEnviromentChange();
      },
    });
  }

  function disableCheckboxState() {
    isRequesting = true;

    $anticipationCheckbox.prop("disabled", true);
    $saveButton.prop("disabled", true);
  }

  function enableCheckboxState() {
    isRequesting = false;

    $anticipationCheckbox.prop("disabled", false);
    $loaderElement.remove();
    $anticipationCheckbox.show();
    $saveButton.prop("disabled", false);
  }

  function disableCheckboxOnEnviromentChange() {
    isRequesting = false;

    $loaderElement.remove();
    $anticipationCheckbox.prop("checked", false);
    $anticipationCheckbox.prop("disabled", true);
    $anticipationCheckbox.show();
    $saveButton.prop("disabled", false);
  }

  $saveButton.on("click", function () {
    if (allowAnticipation) {
      return;
    }
    $anticipationCheckbox.prop("checked", false);
  });
});
