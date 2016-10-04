<?php
  session_start();

  $subTestArray = array("Steak"=>"1", "Chicken"=>"1", "Bacon"=>"1", "Turkey"=>"1", "Ham"=>"1", "Lettuce"=>"1", "Pickles"=>"1", "Olives"=>"1", "Tomatoes"=>"1", "Onions"=>"1", "American Cheese"=>"1", "Pepperjack Cheese"=>"1", "Mayonnaise"=>"1", "Mustard"=>"1", "Ketchup"=>"1", "Black Pepper"=>"1");
  $captKey = array();
  $mountKey = array();
  $subKey = array();
  $scaleKey = array();

  if (isset($_POST)) {
    testPostData();
  }

  function testPOSTdata () {
    $dataErr = "";
    $nameCaptain = $nameEpicMount = "";
    $arraySub = array();
    $morningScaleValue = 0;

    if (!isset($_SESSION["hasVisited"]) || $_SESSION["hasVisited"] == false) {
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["captainPicker"])) {
          $dataErr = $dataErr . "Please choose your favorite captain/n";
        } else {
          $nameCaptain = test_input($_POST["captainPicker"]);
        }

        if (empty($_POST["epicMountText"])) {
          $dataErr = $dataErr . "Please choose an epic mount/n";
        } else if (!preg_match("/^[a-zA-Z ]*$/",$_POST["epicMountText"])) {
          $dataErr = $dataErr . "Name invalid - Do not use special characters/n";
        } else {
          $nameEpicMount = test_input($_POST["epicMountText"]);
        }

        if (!isset($_POST["subSandwich"])) {
          $dataErr = $dataErr . "Please choose some sandwich ingredients/n";
        } else {
          $arraySub = $_POST["subSandwich"];
          //$dataErr = $dataErr . test_subSandwich($arraySub);
        }

        if (empty($_POST["morningScale"]) || !ctype_digit($_POST["morningScale"])) {
          $dataErr = $dataErr . "Whoa?  You broke the scale.  Fix it!";
        } else {
          $morningScaleValue = $_POST["morningScale"];
        }
      }

      if ($dataErr === "")
      {
        appendToFile("fileCapt.txt", $nameCaptain);
        appendToFile("fileMount.txt", $nameEpicMount);
        appendToFile("fileSub.txt", $arraySub);
        appendToFile("fileScale.txt", $morningScaleValue);
        $_SESSION["hasVisited"] = true;
        unset($_POST);
      } else {
        ob_start();
        $_SESSION["DATA_ERR"] = $dataErr;
        while (ob_get_status())
        {
          ob_end_clean();
        }
        header( "Location: /PHPSurvey/survey.php");
        unset($_POST);
        die();
      }

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
        $errSub = "Invalid sub data at $x/n";
        unset($arrayData);
        return $errSub;
      }
      return "";
    }
  }

  function setVisited() {

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
      } else if ($arrayRead[$x]!=""){
        $arrayKey[$arrayRead[$x]] = 1;
      }
    }
  }

  function getAvg(&$arrayRead) {
    $arrAvg = 0.0;
    $arrlength = count($arrayRead);
    for ($x = 0; $x < $arrlength; $x++) {
      $arrAvg += $arrayRead[$x];
    }
    $arrAvg /= $arrlength - 1;
    return $arrAvg;
  }

  function sortResults(&$arrayKey) {
    //sorts an associative array by element
    arsort($arrayKey);
  }

  function appendToFile($fileName, &$data) {
    $myfile = fOpen($fileName, "a") or die("Unable to open file");
    if (is_array($data)) {
      $arrlength = count($data);
      for ($x = 0; $x < $arrlength; $x++) {
        if ($data[$x] != "") {
          fwrite($myfile, $data[$x] . "\n");
        }
      }
    } else {
      if ($data != "") {
        fwrite($myfile, $data . "\n");
      }
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
  <script src="../JavaScript/results.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>

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
    <div class ="row">
      <h2>Most Popular Captain</h2>
      <?php
        $captArray = readFileToArray("fileCapt.txt");
        tallyResults($captArray, $captKey);
      ?>
      <canvas id="captChart" width="400" height="400"></canvas>
      <script type="text/javascript">
        drawCaptChart(<?php echo json_encode($captKey); ?>);
      </script>
    </div>
    <div class="row">
      <h2>Top 5 Epic Mounts</h2>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>
              Epic Beast
            </th>
            <th>
              Number of votes
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
            $mountArray = readFileToArray("fileMount.txt");
            tallyResults($mountArray, $mountKey);
            sortResults($mountKey);
            $count = 0;
            foreach ($mountKey as $key => $val) {
              if ($count > 4) {
                break;
              } else {
              echo "<tr><td>$key</td><td>$val</td></tr>";
              $count++;
              }
            }
          ?>
        </tbody>
      </table>
    </div>
    <div class="row">
      <h2>Top 5 Sandwich Ingredients</h2>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>
              Ingredient
            </th>
            <th>
              Number of votes
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
            $subArray = readFileToArray("fileSub.txt");
            tallyResults($subArray, $subKey);
            sortResults($subKey);
            $count = 0;
            foreach ($subKey as $key => $val) {
              if ($count > 4) {
                break;
              } else {
              echo "<tr><td>$key</td><td>$val</td></tr>";
              $count++;
              }
            }
          ?>
        </tbody>
      </table>
    </div>
    <div class="panel-group text-center">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h2>Our visitors are only</h2>
        </div>
        <div class="panel-body">
          <h2>
          <?php
            $countArray = readFileToArray("fileScale.txt");
            $countAvg = round(getAvg($countArray),2);
            echo "$countAvg/10<br />awake in the morning, on average.";
          ?>
          </h2>
        </div>
      </div>
    </div>
  </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="../JavaScript/survey.js"></script>
</body>
</html>
</DOCTYPE!>
