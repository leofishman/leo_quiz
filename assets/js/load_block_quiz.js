(function ($, Drupal) {
    Drupal.behaviors.checkanswer = {
      attach: function (context, settings) {

        $('.load_block_quiz', context).change(function () {
          if (event.target.name == 'quiz_answer'){
            event.path[2].getElementsByClassName('quiz_submit_button')[0].disabled = false;
          }
        });

        $('.load_block_quiz',context).click(function () {
          let answers = '';

          if (event.target.name == 'quiz_submit_button') {
            let nid  = event.target.id;
            let option_choosen = $('input[name=quiz_answer]:checked').val();

            let url = '/api/quiz?quiz=' + nid;
            $(event.path[2]).empty();
         //   $('<div class="quiz_message">Checking your ansewer...</div>').appendTo('div.nid div#' + nid); //.getElementsByClassName('.quiz_message')).empty(); //[0].html('<div class="checking answer">Checking your ansewer...</div>');
        //    $('.quiz_submit_button').attr("disabled", true);
        //    $('.quiz_option_radio').attr("disabled", true);

            
            $.post(url, {answer: option_choosen}, function (data, status) {
              if (status == "success") {
                $(event.path[2]).empty();
                $('<div class="quiz_message"><div class="' + data.quiz.status +'">' + data.quiz.message + '</div></div>').appendTo('div.nid div#' + nid);

                $('<div class="quiz_title">' + data.quiz.Title + '</div>').appendTo('div.nid div#' + nid);

                if (data.quiz.question.length != 0 && data.quiz.answers.length != 0) {
                  answers = '';
                  for (i = 0; i < 4; i++) {
                    answers += '<input type="radio" class="quiz_option_radio" name="quiz_answer" value="' + data.quiz.answers[i] + '"/>  ' + data.quiz.answers[i] + '</input><br />'
                    // $(leo_quiz_blocks).append('<input type="radio" class="quiz_option_radio" name="quiz_answer" value="' + data.quiz.answers[i]  + '"/>  ' + data.quiz.answers[i] + '</input><br />').appendTo('#quiz_options');
                  }
                  $('<div class="quiz_question">' + data.quiz.question + '?</div><div id="quiz_options">' + answers + '</div>').appendTo('div.nid div#' + nid);
                  $('<div class="quiz_submit"><button type="button" name="quiz_submit_button" disabled="true" class="quiz_submit_button"  id="' + nid + '">send</button></div>').appendTo('div.nid div#' + nid);
                }
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
      function loadQuiz(leo_quiz_blocks, nid) {
        let url = '/api/quiz?quiz=' + nid;

        $.get(url, [], function (data, status) {
          if (status == "success") {
            //let block_class = 'quiz_block_' + nid;
            $(leo_quiz_blocks).empty();
            $(leo_quiz_blocks).append('<div class="nid"><div id="' + nid + '"></span></div>');


            if (data.quiz.status && data.quiz.message) {
              $('<div class="quiz_message"><div class="' + data.quiz.status +'">' + data.quiz.message + '</div></div>').appendTo('div.nid div#'+nid);
              //$(leo_quiz_blocks).append('<div class="quiz_message ' + data.quiz.status +'">' + data.quiz.message + '</div>');
            }
            if (data.quiz.Title) {
              $('<div class="quiz_title">' + data.quiz.Title + '</div>').appendTo('div.nid div#'+nid);
              //$(leo_quiz_blocks).append('<div class="quiz_title">' + data.quiz.Title + '</div>');
            }
            if (data.quiz.question && data.quiz.answers) {
              answers = '';
              for (i = 0; i < 4; i++) {
                answers += '<input type="radio" class="quiz_option_radio" name="quiz_answer" value="' + data.quiz.answers[i]  + '"/>  ' + data.quiz.answers[i] + '</input><br />'
              }
              $('<div class="quiz_question">' + data.quiz.question + '?</div><div id="quiz_options">' + answers + '</div>').appendTo('div.nid div#'+nid);
              $('<div class="quiz_submit"><button type="button" name="quiz_submit_button" disabled="true" class="quiz_submit_button"  id="' + nid + '">send</button></div>').appendTo('div.nid div#'+nid);
            }
          }
        });

        return url;
      }
      let nid = 0;
      let answers = '';
      let leo_quiz_blocks = $(".load_block_quiz");
      leo_quiz_blocks.each( function () {
        nid  = $(this).find("span").attr("id");
        loadQuiz($(this),nid);
        }
      )
    });

  })(jQuery, drupalSettings);
  