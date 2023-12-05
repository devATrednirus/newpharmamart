<?php $sty = '';
if(!empty($_GET['debu'])) {
  if($_GET['debu'] == 1)  {
    echo "Me master.blade.php";
    $sty = ' style="border: 1px solid;" ';
  }
} ?>
