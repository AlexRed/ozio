<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
<!DOCTYPE html>
<html xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<script type='text/javascript'>
var ozio_fullscreen=1;
</script>
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/oziofullscreen/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/oziofullscreen/css/oziofullscreen.css" type="text/css" />
</head>
<body>
<a class="close_fullscreen" href="<?php echo $this->baseurl ?>">
<img src="<?php echo $this->baseurl; ?>/templates/oziofullscreen/images/chiudi.png">
</a>
<jdoc:include type="component" />
</body>
</html>