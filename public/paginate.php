<?php

  $query = $_POST["query"];
  $query = str_replace(" ", "%20", $query);
  // $_GET[$query];

  // $query = "elections";

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_PORT => "8983",
		// CURLOPT_URL => "http://localhost:8983/solr/nutch/select?q=content:" . $query . "&rows=1000",
    CURLOPT_URL => "http://localhost:8983/solr/nutch/select?q=content:elections&rows=10000",
    // CURLOPT_URL => "http://localhost:8983/solr/nutch/select?fq=content:%22" . $query . "%22&q=content:elections&rows=10000",
    // echo "$query",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
     "Cache-Control: no-cache",
     "Postman-Token: 02d781a6-4205-46af-a8a1-c6e545743690"
   ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {

    $json_tot = json_decode($response, true);

    $json_a = $json_tot[response][docs];
    $array_length = count($json_a);
		// echo $array_length;


    echo "<div id='allResults' style='display:none'>";

    // DISPLAY GROUPS CRAWLED
    $arr = array();
    $grouping = array();
    for ($i = 0; $i <= $array_length; $i++) {
      $each_group = $json_a[$i][content];
      $arr[] = $each_group;
    }

    for ($j=0; $j < count($arr); $j++) { 
      // print_r(explode("·", $arr[$j]));
      $grouping[] = explode("·", $arr[$j]);

      // echo $tweets[0][2];

      
     // echo "<br>GROUP NUMBER: " . $j . " - STRING LENGTH: " . strlen($arr[$j]) . "<br>";
     // echo $arr[$j];
     // echo "<br><br>";
    }


    $tweets[] = array();
    for ($k=1; $k < count($grouping); $k++) { 
      for ($x=1; $x < count($grouping[$k]); $x++) { 

        $tweets[] = $grouping[$k][$x];

        // echo "<br><br>";
        // echo $k . " - " . $x;
        // echo "<br>";
        // echo $grouping[$k][$x];
        // echo "<br><br>";
      }
    }



    for ($z=1; $z < count($tweets); $z++) { 
        // echo "<br><br>";
        // echo "TWEET #: " . $z;
        // echo "<br>";
        // echo $tweets[$z];
        // echo "<br><br>";

      echo "<div class='single-result'>";

      echo "<table id='myTable'>";
      // echo "TWEET # " . $z;
      echo "<tr>";

      echo "<td>";
      echo "<table>";

      echo "<tr>";
      echo "<td>";
      echo "<div class='votation' style='text-align: center;'>" . "<a href='?upTweet=" . $z . "'". ">" . "↑" . "</a>" . "</div>" ;
      echo "</td>";
      echo "</tr>";

      echo "<tr style='font-size:12px; text-align: center; font-weight: 400;'>";
      echo "<td>";
      echo $z;
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td>";
      echo "<div class='votation'>" . "<a href='?downTweet=" . $z . "'". ">" . "↓" . "</a>" . "</div>" ;
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      echo "</td>";


      echo "<td class='single-tweet'>";
      echo $tweets[$z];
      echo "</td>"; 

      echo "</tr>";
      echo "</table>";

      echo "<hr>";

      echo "</div>";
    }

    echo "TOTAL NUMBER OF TWEETS: " . count($tweets);
    echo "<br>";

    if(isset($_GET['upTweet'])){
      // upTweet();
      // echo "up" . ($_GET[upTweet] + 1);
      // echo "<br><br>";
      // echo "FEEDBACK UP";
      // echo "<br>";
      // echo ($_GET[upTweet] - 1);
      // array_splice($tweets, ($_GET[upTweet] - 1), 0, $tweets[$_GET[upTweet]]);
      // print_r($tweets);
      // echo $tweets[$_GET[upTweet]];
    }

    if(isset($_GET['downTweet'])){
      // downTweet();
      // echo "up" . ($_GET[downTweet] + 1);
      // echo "<br><br>";
      // echo "FEEDBACK DOWN";
      // echo "<br>";
      // echo ($_GET[downTweet] + 1);
      // array_splice($tweets, ($_GET[downTweet] + 1), 0, $tweets[$_GET[downTweet]]);
      // print_r($tweets);
      // echo $tweets[$_GET[downTweet]];
    }
  }

  echo "</div>";
  ?>
