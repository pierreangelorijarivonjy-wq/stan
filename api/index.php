<?php

// Suppress deprecation notices for compatibility with newer PHP versions
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Forward request to the Laravel index.php
require __DIR__ . '/../public/index.php';
