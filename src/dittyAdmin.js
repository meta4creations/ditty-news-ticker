import "./assets/css/admin.scss";

jQuery(function ($) {
  // Setup strict mode
  (function () {
    "use strict";

    $("#poststuff").trigger("ditty_init_fields");

    $("body").on(
      "click",
      '.ditty-export-posts input[type="checkbox"]',
      function (e) {
        var $checkbox = $(e.target),
          $group = $checkbox.parents(".ditty-input--checkboxes__group"),
          $button = $(".ditty-export-button"),
          checkboxes = $group.find('input[type="checkbox"]'),
          isChecked = $checkbox.is(":checked"),
          value = $checkbox.attr("value");

        if ("select_all" === value) {
          checkboxes.each(function () {
            if ($(this)[0] !== $checkbox[0]) {
              $(this).prop("checked", isChecked);
            }
          });
        }

        // Check if any checkboxes are selected
        var enableButton = false;
        checkboxes.each(function () {
          if ($(this).is(":checked")) {
            enableButton = true;
          }
        });

        if (enableButton) {
          $button.attr("disabled", false);
        } else {
          $button.attr("disabled", "disabled");
        }
      }
    );
  })();
});
