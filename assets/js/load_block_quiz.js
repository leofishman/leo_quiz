(function ($, Drupal) {
    Drupal.behaviors.loadmore = {
      attach: function (context, settings) {

        $('.quiz_block', context).change(function () {
          if (event.target.name == 'quiz_answer'){
            $('.quiz_submit_button').attr("disabled", false);
          }

        });

        $('.quiz_block',context).click(function () {
         
          if (event.target.name == 'quiz_submit_button') {
            $('.quiz_message').text('Checking your ansewer...');
            $('.quiz_submit_button').attr("disabled", true);
            $('.quiz_option_radio').attr("disabled", true);
            let option_choosen = $('input[name=quiz_answer]:checked').val();
            let nid  = $(".load_block_quiz").find("span").attr("id");
            let url = '/api/quiz?quiz=' + nid;
            
            $.post(url, {answer: option_choosen}, function (data, status) {
             // console.log('session', data.quiz.session, 'lastquestion', data.quiz.lastquestion, 'last_q_from_session', data.quiz.lastquestionfromsession);
              if (status == "success") {
                $('.quiz_block').empty();
                $('.quiz_block').append('<div class="quiz_message ' + data.quiz.status +'">' + data.quiz.message + '</div>');
                $('.quiz_block').append('<div class="quiz_title">' + data.quiz.Title + '</div>');
                $('.quiz_block').append('<div class="quiz_question">' + data.quiz.question + '?</div><div id="quiz_options"></div>');
      
                for (i = 0; i < 4; i++) {
                  $('<input type="radio" class="quiz_option_radio" name="quiz_answer" value="' + data.quiz.answers[i]  + '"/>  ' + data.quiz.answers[i] + '</input><br />').appendTo('#quiz_options');
                }
                $('.quiz_block').append('<div class="quiz_submit"><button type="button" name="quiz_submit_button" disabled="true" class="quiz_submit_button">send</button></div>');
              }

            });

         //   console.log(option_choosen);
          }
        });
    }
}
})(jQuery, Drupal);



(function ($, drupalSettings) {
    $(document).ready(function () {
      function loadQuiz(nid) {
        let url = '/api/quiz?quiz=' + nid;

        $.get(url, [], function (data, status) {
          if (status == "success") {
            $('.quiz_block').empty();
            if (data.quiz.status && data.quiz.message) {
              $('.quiz_block').append('<div class="quiz_message ' + data.quiz.status +'">' + data.quiz.message + '</div>');
            }
            if (data.quiz.Title) {
              $('.quiz_block').append('<div class="quiz_title">' + data.quiz.Title + '</div>');
            }
            if (data.quiz.question) {
              $('.quiz_block').append('<div class="quiz_question">' + data.quiz.question + '?</div><div id="quiz_options"></div>');

            }
            if (data.quiz.answers) {
              for (i = 0; i < 4; i++) {
                $('<input type="radio" class="quiz_option_radio" name="quiz_answer" value="' + data.quiz.answers[i]  + '"/>  ' + data.quiz.answers[i] + '</input><br />').appendTo('#quiz_options');
              }
              $('.quiz_block').append('<div class="quiz_submit"><button type="button" name="quiz_submit_button" disabled="true" class="quiz_submit_button">send</button></div>');

            }

          }
        });

        return url;
      }
      let nid  = $(".load_block_quiz").find("span").attr("id");
      loadQuiz(nid);
      
    });

  })(jQuery, drupalSettings);
  