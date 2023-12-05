<?php
 $path = dirname(__FILE__);
 $directory=$path."/images";
 $files = scandir($directory);
 foreach($files as $key=>$name){
    $oldName = $name;
    $newName = strtolower($name);
    rename("$directory/$oldName","$directory/$newName");
  }
?>
