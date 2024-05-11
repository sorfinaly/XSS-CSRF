<?php
// // Set Content Security Policy header for sorfina domain

header("Content-Security-Policy: default-src 'self' https://sorfina data:; script-src 'self' 'unsafe-inline'; style-src 'self' https://fonts.googleapis.com 'unsafe-inline'; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; object-src 'none';");


?>
