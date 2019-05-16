(function ($, Drupal) {
    Drupal.behaviors.loadmore = {
      attach: function (context, settings) {

        $('.quiz_block', context).change(function () {


        });
    }
}
})(jQuery, Drupal);



(function ($, drupalSettings) {
    $(document).ready(function () {
      function loadQuiz(nid) {
        let url = '/api/quiz?quiz=' + nid;

        $.get(url, [], function (data, status) {
          $('.quiz_block').empty();
          $('.quiz_block').append('<div class="quiz_title">' + data.quiz.Title + '</div>');
          $('.quiz_block').append('<div class="quiz_question">' + data.quiz.question + '?</div><div id="quiz_options"></div>');

          for (i = 0; i < 4; i++) {
            $('<input type="radio" class="quiz_option_radio" name="quiz_answer" value="' + data.quiz.answers[i]  + '"/>  ' + data.quiz.answers[i] + '</input><br />').appendTo('#quiz_options');
          }
          $('.quiz_block').append('<div class="quiz_submit"><button type="button" disabled="true" class="quiz_submit_button">send</button></div>');
        });

        return url;
      }
      let nid  = $(".load_block_quiz").find("span").attr("id");
    });


  })(jQuery, drupalSettings);
  