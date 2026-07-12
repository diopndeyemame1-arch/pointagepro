<?php
session_start();

session_unset();

session_destroy();

header("Location: /COUR-TELLY-TECH/pointagepro/public/index.php?page=login");
exit;