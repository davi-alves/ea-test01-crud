;(function ($) {
  function binds () {
    var dels = document.querySelectorAll('.action-delete')
    Array.prototype.forEach.call(dels, function (el) {
      el.addEventListener('click', function (e) {
        var confirm = window.confirm('Deseja realmente deletar?')
        if (!confirm) {
          e.preventDefault()
        }
      })
    })
  }

  $(binds)
})(window.$)
