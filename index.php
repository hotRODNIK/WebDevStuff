<?php
// Check to see that the user has entered a name, else redirect
session_start();
if (isset($_SESSION['user'])){
  // Redirect to the start page
  header("Location: questions.php");
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Nick's Dope Quiz - Welcome</title>
    <link rel="stylesheet" href="styles/styles.css">
  </head>
  <body>
    <header>
      <div class="title">
        <h1>Nick's Dope Quiz</h1>
        <span>Enter a name to get started, then press the start button.</span>
      </div>
    </header>
    <main>
      <div class="content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="login">
          <div class="user">
            <label for="user">Enter a Name</label>
            <input type="text" name="user">
          </div>
          <div class="sub">
            <input type="submit" name="submit" value="Submit">
          </div>
        </form>
      </div>
    </main>

    <?php
      //include "includes/library.php";

      // If the submit button is pressed, then start the game
      if (isset($_POST['submit']) && $_POST['user'] !== ""){
          // Pull the username out from the post array
          $name = $_POST['user'];

          // Save the username, new score and question number into the session array and start the session
          session_start();
          $_SESSION['user'] = $name;
          $_SESSION['score'] = 0;
          $_SESSION['num'] = 1;

          // Redirect to the questions page
          header("Location: questions.php");
          exit();
      }
    ?>

  </body>
</html>
