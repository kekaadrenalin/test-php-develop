/* globals jQuery */
(function ($) {
  var $pjaxBox = $('#pjax-box');

  $(document)
    .on('pjax:send', function () {
      $pjaxBox.find('#progress-bar').show();
      $pjaxBox.find('#result-box').html('');
    })
    .on('pjax:complete', function () {
      $pjaxBox.find('#progress-bar').hide();
    });

  $pjaxBox.on('click', '#btn-save', function (e) {
    e.preventDefault();
    $.get($(this).data('target'), function (r) {
      if (r.success === 'ok') {
        window.location = r.uri;
      } else {
        alert('Возникла ошибка');
      }
    });
  });
})(jQuery);