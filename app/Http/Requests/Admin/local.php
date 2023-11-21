<?php

$file = 'includes/localIP.txt';

$current = file_get_contents($file);
if (isset($_GET['updateIp'])) {
    if ($_SERVER['REMOTE_ADDR'] != $current) {
echo        $current = $_SERVER['REMOTE_ADDR'];

        file_put_contents($file, $current);
        file_put_contents('/sftpusers/chroot/heac/institute-master/localIP.txt', $current);
    }
    exit;
}
if ($current != '') {
echo $current;
exit;
    header('Location:http://'.$current);
    exit;
}
