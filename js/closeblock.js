(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.closeblock = {
    attach: function (context, settings) {
      var $blockSettings = settings.closeBlockSettings,
          $close_button = $('<span />').addClass('closeblock-button').html($blockSettings.close_block_button_text),
          $close_container = $('<div />').addClass('closeblock').append($close_button);

      $(once('close-block', '.close-block')).each(function () {
        if (cookies.get('closeblock-' + $(this).attr('id'))) {
          $('#' + $(this).attr('id')).hide();
        } else {
          $('#' + $(this).attr('id')).show();
          $(this).prepend($close_container);
        }
      });

      $(once('closeblock-button', '.closeblock-button')).click(function () {
        switch ($blockSettings.close_block_type) {
          case 'fadeOut':
            $(this).closest('.close-block').fadeOut($blockSettings.close_block_speed);
            break;
          case 'slideUp':
            $(this).closest('.close-block').slideUp($blockSettings.close_block_speed);
            break;
          default:
            $(this).closest('.close-block').hide();
            break;
        }
        cookies.set('closeblock-' + $(this).closest('.close-block').attr('id'), '1', { path: '/', expires: parseInt($blockSettings.reset_cookie_time) });
      });

      $(once('closeblock-clear-cookie-button', '#closeblock-clear-cookie-button')).click(function () {
        for (var $item in cookies.get()) {
          if ($item.indexOf('closeblock-') >= 0) {
            cookies.remove($item, { path: '/' });
          }
        }
      });
    }
  }
})(jQuery, Drupal, once, window.Cookies);
