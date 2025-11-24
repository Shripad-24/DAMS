<?php
session_start();
session_destroy();
header('Location: /dams/pages/login.php');
exit;
