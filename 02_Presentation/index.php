<!DOCTYPE html>
<html>
<head>
    <title>Contao Konferenz 2018</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="remark/css/main.css">
</head>
<body>
<?php include_once 'Classes/SlideLoader.php'; ?>

<textarea id="source">
<?php echo Esit\Classes\SlideLoader::loadSlides(); ?>
</textarea>

<script src="remark/js/remark-latest.min.js"></script>
<script src="remark/js/main.js"></script>
</body>
</html>