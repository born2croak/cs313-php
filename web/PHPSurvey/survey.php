
<?php
  session_start();
  unset($_POST);
  //$_SESSION["hasVisited"] = false;
  $url = "/PHPSurvey/results.php";
  if (isset($_SESSION["hasVisited"]) && $_SESSION["hasVisited"] == true) {
    header( "Location: $url");
    die();
  }

  if (isset($_SESSION["DATA_ERR"]) && $_SESSION["DATA_ERR"] != "") {
    $error = $_SESSION["DATA_ERR"];
    echo "<script>window.alert($error)</script>";
  }
?>

<DOCTYPE! HTML>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>PHP Survey</title>
  <!-- Bootstrap core css-->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- Custom CSS overrides -->
  <link rel="stylesheet" type="text/css" href="../stylesheets/navstyle.css" />
  <link rel="stylesheet" type="text/css" href="../stylesheets/surveystyle.css" />

</head>
<body>
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="#top">CS313 - Tim Gifford</a>
      </div>
      <ul class="nav nav-pills">
        <li>
          <a href="../index.php">Home</a>
        </li>
        <li class="dropdown, active">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Lessons
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../PHPSurvey/survey.php">Week 03: PHP Survey</a></li>
            <li><a href="../lessons.html">Coming Soon!</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <div class="jumbotron">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <h1>PHP Survey</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <a href="results.php" role="button" class="btn btn-primary btn-lg" id="skipButton">Skip to results -></a>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
        <form method="post" action="results.php">
          <fieldset class="form-group">
            <legend>Choose your favorite Captain!</legend>
            <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="captainPicker" id="kirk" value="Kirk" />
                James T Kirk
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="captainPicker" id="nemo" value="Nemo" />
                Prince "Nemo" Dakkar
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="captainPicker" id="rogers" value="Rogers" />
                Steve Rogers
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="captainPicker" id="sparrow" value="Sparrow" />
                Jack Sparrow
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="captainPicker" id="reynolds" value="Reynolds" />
                Malcolm Reynolds
              </label>
            </div>
          </fieldset>
          <div class="form-group">
            <legend>Favorite epic mount?</legend>
            <input type="text" class="form-control" name="epicMountText" aria-describedby="epicMountTextHelp" placeholder="Hippogriff? Shai-Hulud? A Dragon?!?" aria-required="false"/>
            <small id="epicMountTextHelp" class="form-text text-muted">What beast would you ride into glorious battle?</small>
          </div>
          <div class="form-check">
            <legend>What would you like in your Sandwich?</legend>
            <div class="container">
              <div class="row">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Steak"/>Steak
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Chicken"/>Chicken
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Bacon"/>Bacon
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Turkey"/>Turkey
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Ham"/>Ham
                </label>
              </div>
            </div>
            <div class="container">
              <div class="row">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Lettuce"/>Lettuce
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Pickles"/>Pickles
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Olives"/>Olives
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Tomatoes"/>Tomatoes
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Onions"/>Onions
                </label>
              </div>
            </div>
            <div class="container">
              <div class="row">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="American Cheese"/>American Cheese
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Pepperjack Cheese"/>Pepperjack Cheese
                </label>
              </div>
            </div>
            <div class="container" id="condimentArea">
              <div class="row">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Mayonnaise"/>Mayonnaise
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Mustard"/>Mustard
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Ketchup"/>Ketchup
                </label>
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="subSandwich[]" value="Black Pepper"/>Black Pepper
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <legend>Are you a morning person?</legend>
            <div class="container">
              <div class="row">
                <div class="col-sm-1">
                  <p>
                    Zombie
                  </p>
                </div>
                <div class="col-sm-10">
                  <input type="range" min="0" max="10" value="5" step="1" name="morningScale" id="morningScale1" onchange="showScaleValue(this.value)"/>
                </div>
                <div class="col-sm-1">
                  <p>
                    Richard Simmons
                  </p>
                </div>
              </div>
            </div>
            <p class="text-center">
              <span class="badge" id="showMorningScale">5</span>
            </p>
          </div>
          <input type="submit" class="btn btn-info btn-lg" value="Submit" />
        </form>
      </div>
    </div>
  <!-- Bootstrap core JavaScript
   ================================================== -->
   <!-- Placed at the end of the document so the pages load faster -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
   <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <script src="../JavaScript/survey.js"></script>
</body>
</html>
</DOCTYPE!>
