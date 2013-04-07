<?php include ('functions.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<script src="js/jquery-1.8.2.min.js"></script>
<script src="js/jquery.zclip.min.js"></script>
<title><?php echo get_seo_title(); ?></title>
<style> @import url(style.css); </style>
<? //Get the Site Options

$options = $connection->prepare("SELECT * FROM options WHERE id = 1");
$options->execute(array());
$result = $options->fetch(PDO::FETCH_ASSOC);
?>
</head>
<body>
<div id="main">
<header>
<h1><a id="maintitle" href='index.php'><?=$resultX['title']?></a></h1>
<h2><?=$resultX['homedesc']?></h2>
<meta name="keywods" content="<?=$resultX['metakeywords']?>">
<meta name="description" content="<?=$resultX['homedesc']?>">
</header>

<div id="topmenu">
<?php show_categories();?>
</div>