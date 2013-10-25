<?php
function time_elapsed_string($ptime) {
// from http://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
        }
    }
}
function h($text) { return htmlspecialchars($text); }
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<title>SMGTools Home Page</title>
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="nivo-slider.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	 <script src="jquery.nivo.slider.pack.js"></script>

	<script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.2.0/respond.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/r29/html5.min.js"></script>
	<script>
	$(window).load(function() {
			$('#slider').nivoSlider({
					directionNav: false,
					controlNav: false,
					effect: 'sliceDown',
					pauseOnHover: true
			});
	});
	</script>

</head>
<body>


<div class="container">
<div class="row">

<section class="col-lg-4 col-sm-4 eventscol">
	<h2>Graduate Events</h2>
	<?php
		$events = @file_get_contents("https://smgapps.bu.edu/smgnet/smgcalendarU/todayjson_smgworld_gpo.cfm");
		if($events != false) {
			$events = json_decode($events,true);
			foreach ($events["DATA"] as $dci => $datachunk) {
				foreach ($datachunk as $index => $data) {
					if(is_numeric($index)) {
						//echo $events["COLUMNS"][$index];
						$events["DATA"][$dci][$events["COLUMNS"][$index]] = $data;
						unset($events["DATA"][$dci][$index]);
						// var_dump($datachunk);
					}

				}
				// var_dump($datachunk);
			}
			$events = $events["DATA"];
			foreach($events as $event) { 
				$date = date_create_from_format('F, d Y G:i:s',$event["EVENTDATE"]);
				$endtime = date_create_from_format('F, d Y G:i:s',$event["ENDTIME"]);
				$enddata = '';

				if($endtime->format('G:i A') === $date->format('G:i A')
					 || $endtime->format('G:i') === '0:00') {
					// nil
				} else if($date->format('i') == '00') {
					$enddata = date_format($endtime,' \- g A' );
				} else {
					$enddata = date_format($endtime,' \- g A' );
				}


				$fmt = '';
				// echo $date->format('G:i:s');
				if($date->format('G:i') == '0:00') {
					$fmt = 'D M j, Y';
				} else if($date->format('i') == '00') {
					$fmt = 'D M j, Y \a\t g A';
				} else {
					$fmt = 'D M j, Y \a\t g:i A';
				}
				$newDate = date_format($date,$fmt) . $enddata;
				?>
	<article>
	
		<?php if($event["WEBLINK"] != 'http://') { ?>
			<a target="_blank" href="<?=h($event["WEBLINK"])?>">
		<?php } ?>
			<h3><?=h($event["TITLE"])?></h3>
				<time><?=h($newDate)?></time>
		<?php if($event["LOCATION"] != "BU") { ?>
			<p><?=h($event["LOCATION"])?></p>
		<?php } ?>
		<?php if($event["WEBLINK"] != 'http://') { ?></a><?php } ?>
	</article>
	<?php }
		}
	?>
	<a href="http://smgworld.bu.edu/gpo-calendar/" target="_blank">All Graduate Events >></a>
	<hr>
	<p class=" text-warning text-center warning-p">SMGtools is down every Friday between 6 a.m. and 7:30 a.m. Please plan accordingly.</p>
	<hr>
	<article class="">
		<h2>Links</h2>
		<div class="grid">
		 	<a href="http://smgworld.bu.edu/fcc/" target="_blank">
			<div class="linktext2">Career Center</div></a>
		 </div>		
		 <div class="grid">
		 	<a href="http://www.bu.edu/studentlink/" target="_blank">
			<div class="linktext2">Student Link</div></a>
		 </div>
		 <div class="grid">
		 	<a href="http://smgworld.bu.edu/techhelp/smgtools/" target="_blank">
			<div class="linktext2">Tech Help</div></a>
		 </div>
		 <div class="grid">
		 	<a href="http://www.bu.edu/reg/dates/odates.html" target="_blank">
			<div class="linktext2">BU Calendar</div></a>
		 </div>
		 <div class="grid">
			<a href="http://smgapps.bu.edu/maps/maps595.cfm" target="_blank"><div class="linktext2">Floor Maps</div></a>
		 </div>
		 <div class="grid">
		 	<a href="http://smgworld.bu.edu/" target="_blank">
			<div class="linktext2">SMG World</div></a>
		 </div>	 
		 <div class="grid">
			<a href="http://www.bu.edu/library/management/index.shtml" target="_blank"><div class="linktext2">Pardee Library</div></a>
		 </div>
		<div class="grid">
			<a href="http://smgapps.bu.edu/smgnet/Directories/faculty_directory.cfm" target="_blank">
			<div class="linktext2">SMG Directory</div></a>
		 </div>
		 <div class="grid">
			<a href="https://smgapps.bu.edu/CopyCenter/default.aspx" target="_blank">
			<div class="linktext2">Course Packets</div></a>
		 </div>
		 <div class="grid">
			<a href="http://www.bu.edu/police/" target="_blank">
			<div class="linktext2">BU Police</div></a>
		 </div>
		 
		<div class="grid">
			<a href="http://smgworld.bu.edu/upo/" target="_blank"><div class="linktext2">UPO</div></a>
		 </div>
		<div class="grid">
			<a href="http://smgworld.bu.edu/gpo/" target="_blank"><div class="linktext2">GPO</div></a>
		 </div>
	</article>
	<hr>
		

</section>
<section class="col-lg-8 col-sm-8">
			<div class="slider-wrapper">
    <div id="slider" class="nivoSlider">
    	<?php
$pageID = isset($_GET["page"]) ? $_GET["page"] : "grads";
$images = explode("\n",@file_get_contents("images.txt"));
foreach ($images as $index=>$image) {
	$image_exp = explode("||",$image);
	$name = h($image_exp[0]);
	$caption = h($image_exp[1]);
	$link = h($image_exp[2]);
	if(!in_array($pageID,explode('|',$image_exp[3])) ) {
		continue;
	}
	if($link == '') { ?>
		<img src="img/<?=$name?>" title="<?=$caption?>">			
<?php } else { ?>
		<a href="<?=$link?>" target="_blank">
			<img src="img/<?=$name?>" title="<?=$caption?>">
		</a>
<?php }
}
    	?>
    </div>
</div>
<section class="col-lg-6 news-col">
	
	
	<?php
	if(file_exists("newscache.htm") && filemtime("newscache.htm") > time()-300) {
		$nt= "<time class='newstime'>updated " . time_elapsed_string(filemtime("newscache.htm")) . "</time>";
		echo str_replace('<time class="newstime">updated just now</time>', $nt, file_get_contents("newscache.htm"));
	} else { ob_start(); ?>
	<h2>News</h2>
	
	<!-- <time class="text-muted newstime small">updated just now</time> -->
	<!--  above would be deceptive b/c doesnt update after page load -->
	
	<?php
	$feed = @simplexml_load_file("http://management.bu.edu/feed/?cat=60");
	if($feed != false) {
		$items = $feed->channel->item;
		foreach ($items as $item) {
			$imagedata = @file_get_contents($item->link);
			if($imagedata != false) {
				$imagedata = explode('<div class="banner-container"><img src="',$imagedata);
				if(isset($imagedata[1])) {
					$imagedata = explode('"',$imagedata[1])[0];
					$imagedata = 'http://management.bu.edu' . $imagedata;
				} else {
					$imagedata = false;
				}
			}

	?>
	<article>
		<a target="_blank" href="<?=h($item->link)?>" class="clearfix">
			<?php if($imagedata != false) { ?>
				<img src="<?=h($imagedata)?>" class="img-thumbnail">
			<?php } ?>
			<h3><?=h($item->title)?></h3>

		</a>
	</article>
	<?php
		}
	} ?>


</section>
<section class="col-lg-6 hourscol">



	<h2>Hours</h2>
	<?php
	$hourdata = @file_get_contents("https://smgserv1.bu.edu/hours2/data.php");
	if($hourdata != false) {
		$hourdata = json_decode($hourdata, true);
	}
	//var_dump($hourdata);
	// from get2.php
	$output = "";
	foreach ($hourdata as $name => $place) { ?>
		<section>
			<?php if($name == "Notice") {
				if(isset($place["subhead"]) && $place["subhead"]) { ?>
					<h3 class="text-success"><?=h($place["subhead"])?></h3>
			<?php } if(isset($place["status"]) && $place["status"]) { ?>
					<p class="text-success"><?=h($place["status"])?></p>
			<?php } ?>
		</section>
	<?php continue; } ?>
			<h3><?=h($name)?></h3>
			<?php if(isset($place["status"]) && $place["status"]) { ?>
				<p class="text-danger"><?=h($place["status"])?></p>
			<?php } else { ?>
				<ul>
					<?php foreach ($place as $key => $value) { if($value) { ?>
						<li><strong><?=h($key)?></strong> <?=h($value)?></li>
					<?php } } ?>
				</ul>
			<?php }?>
		</section>
	<?php } ?>

	<?php
		$newsOutput =ob_get_contents();
		file_put_contents("newscache.htm",$newsOutput);
	}
	?>
</section>
</section>
</div>
</div>

</body>
</html>