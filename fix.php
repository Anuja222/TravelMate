<?php $c = file_get_contents("app/controllers/Admin/Report.php"); file_put_contents("app/controllers/Admin/Report.php", preg_replace("/^\xEF\xBB\xBF/", "", $c)); ?>
