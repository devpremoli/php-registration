<?php
session_start();
session_unset();
session_destroy();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
echo json_encode(['success' => true]);
