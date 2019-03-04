<?php 
	// INITIAL SETTINGS
	require("config.php");
	
	$day = date("d");
	
	$d = $day;
	$qq = array();
	while($d <= 150) {
		array_push($qq, 'Ps'.$d);
		$d = $d + 30;
	}
	array_push($qq, 'Pro'.$day);
	
	$API_URL = "https://api.esv.org/v3/passage/text/";
	
	// GET SCRIPTURE VERSES FROM ESV ONLINE
	$curl = curl_init();
	$params = array(
		'q' => implode(',',$qq),
		'include-headings' => 'false',
        'include-footnotes'=> 'false',
        'include-verse-numbers' => true,
        'include-short-copyright'=> 'false',
        'include-passage-references'=> true
		);
	$url = $API_URL . '?' . http_build_query($params);

	
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
	    "cache-control: no-cache",
	    'Authorization: Token ' . API_KEY
	  ),
	));	
    
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	
	$response = json_decode($response, true);
	?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<style>
		.footer-main p {
			margin-bottom: 0;
		}
		h4 {
			margin-top: 1em;
		}
	</style>
	<title>Daily Psalms and Proverbs</title>
  </head>
  
  <body>	  
  <main role="main" class="container-fluid bg-light">
  <div class="container">
    <h1>Daily Psalms and Proverbs
	    <small class="text-muted"> For <?php echo date("l F d, Y");?></small>
    </h1>
	<?php
		foreach($response["passages"] as $passage) {
		?><div><?php
			$verses = preg_split("/\[\d*\]/", $passage);
			preg_match("/(\w*\s\d+)(.*)/", $verses[0], $matches);
			$chapter = $matches[0];
			$zero = str_replace($chapter, "", $verses[0]);
			?><h4><?php echo $chapter;?></h4><?php
				?><p><?php echo $zero; ?></p><?php
				for($i=1; $i < count($verses); $i++ ){
					?><p><?php
						echo '<small>'.$i.'</small>';
						echo $verses[$i];
					?></p><?php
				}			
		?></div><?php
		}
	?>
	</div>
	</main><!-- /.container -->
		<div class="text-muted bg-dark footer-main">
			<div class="container">
			<p>This shows five psalms and one chapter of Proverbs for each day; reading this daily will go through all of the Psalms and Proverbs each month. Created by Samuel Kordik.</p>
			<p><small>Scripture quotations are from the ESV&copy; Bible (The Holy Bible, English Standard Version®), copyright &copy; 2001 by Crossway, a publishing ministry of Good News Publishers. Used by permission. All rights reserved. You may not copy or download more than 500 consecutive verses of the ESV Bible or more than one half of any book of the ESV Bible.</small></p>
			</div>
		</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>