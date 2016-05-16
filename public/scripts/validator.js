;(function ($, Parsley) {
  Parsley
    .on('field:error', function () {
      var parent = this.$element.closest('.form-group')
      if (parent.length) {
        parent
          .removeClass('has-success')
          .addClass('has-error')
      }
    })
    .on('field:success', function () {
      var parent = this.$element.closest('.form-group')
      if (parent.length) {
        parent
          .addClass('has-success')
          .removeClass('has-error')
      }
    })

  function applyValidations () {
    var forms = document.querySelectorAll('[data-validate-form]')
    Array.prototype.forEach.call(forms, function (form) {
      $(form).parsley({
        errorsMessagesDisabled: true
      })
    })
  }

  $(applyValidations)
})(window.$, window.Parsley)
