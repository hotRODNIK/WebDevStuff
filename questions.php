<?php
  // Check to see that the user has entered a name, else redirect
  session_start();
  if (!isset($_SESSION['user'])){
    // Redirect to the start page
    header("Location: index.php");
  }
  elseif ($_SESSION['num'] > 10){
    header("Location: table.php");
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Question <?php echo $_SESSION['num']; ?></title>
    <link rel="stylesheet" href="styles/styles.css">
  </head>
  <body>
    <header>
      <div class="title">
        <h1>Question <?php echo $_SESSION['num']; ?></h1>
        <h2>Score: <?php echo $_SESSION['score']; ?></h2>
      </div>
    </header>
    <main>
      <div class="content">
        <?php
          include "includes/library.php";

          if (isset($_POST['next'])){
            // Connect to the database and get the question
              $pdo = connectdb();
              $question = $pdo->prepare("SELECT question FROM a2_quest WHERE id = ?");
              $question->execute([$_SESSION['num']]);
              $q = $question->fetchColumn();

              // Output the question
              echo "<p>$q</p>";
          }
        ?>
        <form id="answer" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <label for="answer">Input Answer Here</label>
          <input type="text" name="answer">
          <input type="submit" name="submit" value="Submit">
          <input type="submit" name="next" value="Show Question">
        </form>
        <?php
          if (isset($_POST['submit'])){
            // Get the answer from the database and compare it against the one provided
            $pdo = connectdb();
            $a = $pdo->prepare("SELECT answer, correct, choicecount FROM a2_ans WHERE fk_questid = ?");
            $a->execute([$_SESSION['num']]);

            // Pull values out
            $res = $a->fetch();
            $ans = $res['answer'];
            $count = $res['choicecount'];
            $numRight = $res['correct'];
            $userAns = $_POST['answer'];

            // Compare the answer from the database against the one provided
            if ($ans === $userAns){
              // Echo out a message and compute stats
              echo "<span>Correct</span>";
              $count++;
              $numRight++;
              $_SESSION['score'] += 10;

              // Update results in the database
              $up = $pdo->prepare("UPDATE a2_ans SET correct = ?, choicecount = ? WHERE fk_questid = ?");
              $up->execute([$numRight, $count, $_SESSION['num']]);
            }
            else{
              // Echo out a message and compute stats
              echo "<span>Incorrect, the correct answer was $ans</span>";
              $count++;
              $_SESSION['score'] -= 10;

              // Update results in the database
              $up = $pdo->prepare("UPDATE a2_ans SET choicecount = ? WHERE fk_questid = ?");
              $up->execute([$count, $_SESSION['num']]);
            }

            // Increment the question counter
            $_SESSION['num']++;
          }
        ?>
      </div>
    </main>
  </body>
</html>
