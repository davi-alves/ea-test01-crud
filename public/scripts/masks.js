;(function ($, VMasker) {
  var _masks = {
    phone: '(99) 9999-9999',
    cellphone: '(99) 9 9999-9999'
  }

  function applyMasks () {
    Object.keys(_masks).forEach(function (mask) {
      if (!_masks.hasOwnProperty(mask)) {
        return
      }

      VMasker(document.querySelectorAll('[data-mask-' + mask + ']'))
        .maskPattern(_masks[ mask ])
    })

    VMasker(document.querySelectorAll('[data-mask-money'))
      .maskMoney()
  }

  $(applyMasks)
})(window.$, window.VMasker)
