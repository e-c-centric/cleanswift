<?php

require_once '../classes/overview_class.php';

$overview = new overview_class();

function getOverviewInfo(){
    global $overview;
    $info = $overview->getOverview();
    return $info;
}