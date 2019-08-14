<?php
include_once('phpcas/libraries/CAS.php');
phpCAS::client(CAS_VERSION_2_0, 'cas.taihenw.com', 80, 'cas');
phpCAS::logout();
?>
