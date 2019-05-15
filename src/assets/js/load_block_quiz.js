(function ($, Drupal) {
    Drupal.behaviors.loadmore = {
      attach: function (context, settings) {
        $('.load_block_quiz', context).ready(function () {
console.log('hola');
        });
    }
}
})(jQuery, Drupal);
