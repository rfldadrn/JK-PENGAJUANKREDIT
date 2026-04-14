<?php

// Load configurations first (before session_start)
require_once '../config/config.php';
require_once '../config/database.php';

// Start session (after ini_set in config.php)
session_start();

// Load core classes
require_once '../core/Database.php';
require_once '../core/Model.php';
require_once '../core/Controller.php';
require_once '../core/App.php';

// Initialize application
$app = new App();
