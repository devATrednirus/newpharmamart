<?php

echo realpath($_SERVER["DOCUMENT_ROOT"]);

echo symlink(realpath($_SERVER["DOCUMENT_ROOT"]).'/storage/app', '/home2/redniruscare/mart.redniruscare.com/public/storage');

?>
