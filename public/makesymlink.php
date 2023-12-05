<?php

echo realpath($_SERVER["DOCUMENT_ROOT"]);

echo symlink(realpath($_SERVER["DOCUMENT_ROOT"]).'/storage/app', $_SERVER["DOCUMENT_ROOT"].'/public/storage');

?>
