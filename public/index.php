<!DOCTYPE html>
<html>
<head>
	<title>CRAWLER</title>
	<link rel="stylesheet" href="assets/css/stylesheet.css">
  <meta name="viewport" content="width=1024">
</head>
<body>
	<h1>🇺🇸 US ELECTIONS 2020 TWEETS SEARCH ENGINE 🇺🇸</h1>

	<form action="" method="POST">
		<!-- <input type="text" /> -->
    <input type="text" name="query" id="querybox" onkeyup="searchEngine()" placeholder="Search for tweets..">
  </form> 


  <?php include 'pagination.php';?>


  <script src="assets/js/script.js"></script>

  <script type="text/javascript">
    var jArray = <?php echo json_encode($tweets); ?>;

    for(var i=1; i<jArray.length; i++){
      // LIST ALL TWEETS IN CONSOLE LOG
      // console.log(jArray[i]);
    }
  </script>

</body>
</html>