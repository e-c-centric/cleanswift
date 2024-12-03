<?php

header('Content-Type: application/json');

require_once '../controllers/overview_controller.php';

$overview = getOverviewInfo();

echo json_encode($overview);