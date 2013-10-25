<!DOCTYPE html>
<?php
if(!empty($_POST)) {
	// var_dump($_POST);
	$theFiles = $_FILES['Files'];
	$newFiles = '';
	$allowed_exts = array('jpg','gif','png','jpeg');
	$cbNames = array('main_page','undergrads','grads','fac_staff');
	foreach(array_keys($theFiles['name']) as $i) {
		$newname = '';
		$ext = explode(".",$theFiles['name'][$i]);
		$ext = array_pop($ext);

		$cbs = array();
		foreach ($cbNames as $cbName) {
			if($_POST[$cbName][$i] == 'on') {
				array_push($cbs,$cbName);
			}
		}

		if($theFiles['name'][$i] != '') {
			if(in_array($ext, $allowed_exts, true)) {
				 $newname= md5_file($theFiles['tmp_name'][$i]) . "." . $ext;
				 move_uploaded_file($theFiles['tmp_name'][$i], "img/" . $newname);
			}
		} else if(isset($_POST['OldFiles'][$i])) { 
			$newname = $_POST['OldFiles'][$i];
		}
		$newFiles .= $newname . '||' . $_POST['Caption'][$i] . '||' . $_POST['Link'][$i] . '||' .
			implode('|',$cbs) . "\n";
		//implode(glue, pieces)
	}

	$newFiles = substr($newFiles, 0,-1);

	$file = fopen("images.txt","w");
	fwrite($file,$newFiles);
	fclose($file); 
	header('Location: admin.php');
} ?>
<html>
	<head>
		<title>Slideshow</title>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="admin.css">
	</head>
	<body>
		<form method="POST" action="admin.php" enctype="multipart/form-data">
			<ol class="imagelist">
			<?php
			foreach (explode("\n",file_get_contents("images.txt")) as $image) {
				$image_exp = explode("||",$image);
				$name = htmlspecialchars($image_exp[0]);
				$caption = htmlspecialchars($image_exp[1]);
				$link = htmlspecialchars(isset($image_exp[2]) ? $image_exp[2] : '');
				$cbs = explode('|',$image_exp[3]);
			?>
				<li>
					<figure>
						<img src="img/<?=$name?>">
						<input type="hidden" name="OldFiles[]" value="<?=$name?>">
						<button class="remove-btn" type="button">Remove</button>
						<input type="file" name="Files[]">
						<fieldset>
							<input type="text" placeholder="Caption" name="Caption[]" value="<?=$caption?>">
							<input type="text" placeholder="Link (or empty)" name="Link[]" value="<?=$link?>">
							Show for:
							<input type="checkbox" name="main_page[]"
								<?=in_array('main_page',$cbs)?'checked':''?>>Main Page
							<input type="checkbox" name="undergrads[]"
								<?=in_array('undergrads',$cbs)?'checked':''?>>Undergrads
							<input type="checkbox" name="grads[]"
								<?=in_array('grads',$cbs)?'checked':''?>>Grads
							<input type="checkbox" name="fac_staff[]"
								<?=in_array('fac_staff',$cbs)?'checked':''?>>Fac/Staff
						</fieldset>
					</figure>
				</li>
			<?php } ?>
			</ol>
			<button class="add-btn" type="button">Add Image...</button>
			<button><strong>Save Data</strong></button>
		</form>
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script src="admin.js"></script>
	</body>
</html>
