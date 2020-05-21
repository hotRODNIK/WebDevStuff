<?php
  // Check to see that the user has entered a name, else redirect
  session_start();
  if (!isset($_SESSION['user'])){
    // Redirect to the start page
    header("Location: index.php");
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Results</title>
    <link rel="stylesheet" href="styles/styles.css">
  </head>
  <body>
    <header>
      <div class="title">
        <h1>Results for <?php echo $_SESSION['user']; ?></h1>
      </div>
    </header>
    <main>
      <div class="content">
        <?php
          include "includes/library.php";

          // Pull values out
          $score = $_SESSION['score'];
          $user = $_SESSION['user'];

          // End the session
          session_destroy();

          // Connect to the database and update the scores
          $pdo = connectdb();
          $i = $pdo->prepare("INSERT INTO a2_scores (username, score) VALUES (?, ?)");
          $i->execute([$user, $score]);

          // Output results and the high score table
          echo "<span>Your score was $score</span>";

          // Customized Message
          if ($score <= 4){
            echo "<span> Rating: Noob.</span>";
          }
          else{
            echo "<span> Rating: Pass.</span>";
          }

          // High score table output
          $pull = $pdo->prepare("SELECT username, score FROM a2_scores WHERE score > 80 LIMIT 1;");
          $pull->execute([]);
          $res = $pull->fetch();
          $name = $res['username'];
          $score = $res['score'];
          echo "<p>High Score: Username: $name, Score: $score</p>";
          exit();
        ?>
      </div>
    </main>
  </body>
</html>
