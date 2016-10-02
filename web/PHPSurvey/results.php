<?php
  $nameCaptain = $nameEpicMount = "";
  $subTestArray = array("Steak"=>"1", "Chicken"=>"1", "Bacon"=>"1", "Turkey"=>"1", "Ham"=>"1", "Lettuce"=>"1", "Pickles"=>"1", "Olives"=>"1", "Tomatoes"=>"1", "Onions"=>"1", "American Cheese"=>"1", "Pepperjack Cheese"=>"1", "Mayonnaise"=>"1", "Mustard"=>"1", "Ketchup"=>"1", "Pepper"=>"1");
  $captKey = array();
  $mountKey = array();
  $subKey = array();
  $scaleKey = array();
  $morningScaleValue = 0;
  $errCapt = $errMount = $errSub = $errScale = "";
  $testArray = array("Kirk", "Reynolds", "Reynolds");

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["captainPicker"])) {
      $errCapt = "Please choose your favorite captain";
    } else {
      $nameCaptain = test_input($_POST["captainPicker"]);
      appendToFile("fileCapt.txt", $nameCaptain);
    }

    if (empty($_POST["epicMountText"])) {
      $errMount = "Please choose an epic mount";
    } else if (!preg_match("/^[a-zA-Z ]*$/",$nameEpicMount)) {
      $errMount = "Name invalid - Do not use special characters";
      $nameEpicMount = "";
    } else {
      $nameEpicMount = test_input($_POST["epicMountText"]);
      appendToFile("fileMount.txt", $nameEpicMount);
    }

    if (!isset($_POST["subSandwich"])) {
      $errSub = "Please choose some sandwich ingredients";
    } else {
      $arraySub = $_POST["subSandwich"];
      test_subSandwich($arraySub);
      appendToFile("fileSub.txt", $arraySub);
    }

    if (empty($_POST["morningScale"]) || !ctype_digit($_POST["morningScale"])) {
      $errScale = "Whoa?  You broke the scale.  Fix it!";
    } else {
      $morningScaleValue = $_POST["morningScale"];
      appendToFile("fileScale.txt", $morningScaleValue);
    }
  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  function test_subSandwich(&$arrayData) {
    global $subTestArray;
    $arrlength = count($arrayData);
    for($x = 0; $x < $arrlength; $x++) {
      $arrayData[$x] = trim($arrayData[$x]);
      $data = $arrayData[$x];
      if (!isset($subTestArray[$data]) || $subTestArray[$data]<1)
      {
        $errSub = "Invalid sub data at $x";
        unset($arrayData);
        break;
      }
    }
  }

  function readFileToArray($fileName) {
    //Reads a simple file format.  One answer per line
    $myfile = fOpen($fileName, "r") or die("Unable to open file!");
    $arrayRead = array();
    while(!feof($myfile)) {
      $line = trim(fgets($myfile));
      $arrayRead[] = $line;
    }
    fclose($myfile);
    return $arrayRead;
  }

  function tallyResults(&$arrayRead, &$arrayKey) {
    //Take an array and tally the amount of times array values repeat against a keyed array of those values
    $arrlength = count($arrayRead);
    for ($x = 0; $x < $arrlength; $x++) {
      if (array_key_exists($arrayRead[$x], $arrayKey)) {
        $arrayKey[$arrayRead[$x]] += 1;
      } else {
        $arrayKey[$arrayRead[$x]] = 1;
      }
    }
  }

  function appendToFile($fileName, &$data) {
    $myfile = fOpen($fileName, "a") or die("Unable to open file");
    if (is_array($data)) {
      $arrlength = count($data);
      for ($x = 0; $x < $arrlength; $x++) {
        fwrite($myfile, $data[$x] . "\n");
      }
    } else {
      fwrite($myfile, $data . "\n");
    }
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
      <h1>Results</h1>
    </div>
  </div>
  <div class = "container">
    <div class = "row">
      <p>
        <?php
        echo "$errCapt";
        echo "$nameCaptain<br/>";
        echo "$errMount";
        echo "$nameEpicMount<br/>";
        echo "$errSub";
        if(isset($arraySub)) {echo implode(", ", $arraySub) . "<br/>";} else {echo "<br />";}
        echo "$errScale";
        echo "$morningScaleValue<br/>";
        $captArray = readFileToArray("fileCapt.txt");
        if(isset($captArray)) {echo implode(", ", $captArray) . "<br/>";} else {echo "<br />";}
        tallyResults($captArray, $captKey);
        echo $captKey['Reynolds'];
        ?>
      </p>
    </div>
  </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="../JavaScript/survey.js"></script>
</body>
</html>
</DOCTYPE!>
