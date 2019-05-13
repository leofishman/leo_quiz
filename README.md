For this challenge, we’ll be building a small quiz application. Our client is The Foo Company, a school in Washington, DC. They would like to be able to give their students quizzes in an online application, so that’s what we’re going to build. These quizzes can be placed anywhere throughout the site - WYSIWYG’s, blocks, etc.

The specs from the client are as follows:
The page has a persistent title throughout (for example, “Math Unit 2 Quiz”)
The student sees one question at a time, with multiple answer options. They select an answer and click a ‘next’ button in the lower right of the screen.
If the student gets the question right, he/she moves on to the next question. If they get the question wrong, the application tells them to try again, at which point they can select another answer and click ‘next’ again.
Once the student gets the last question answered correctly, the quiz goes to a final screen that congratulates the student and confirms that the quiz is over.

Additional requirements from our Project Manager:
Create a content type called “Quiz” which allows users to create the title, question, available answers, and the correct answer. 
The quiz UI will be built as a Single Page Application using Drupal as a backend API. You may use any JavaScript libraries you want - bonus points for being minimal. 
You should create this API as a module (simple one route endpoint). This endpoint will be in the following format: /api/quiz?quiz=[node-id]
The endpoint will return the JSON necessary to create the quiz UI (content within the Quiz content type).
There can be any number of questions for a quiz.
There will always be 4 available options/answers to choose from.
A quiz will be placed on a page by simply putting a div in the following format: <div class=”quiz” data-quiz=”123”></div> Where 123 is the node ID.
There could be any number of quizzes on a single page.