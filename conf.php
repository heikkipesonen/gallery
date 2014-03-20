<?php
ORM::configure('mysql:host=localhost;dbname=mrfoto');
ORM::configure('username', 'kissa');
ORM::configure('password', 'kala');
ORM::configure('return_result_sets', true);
ORM::configure('table_prefix','');

define('BASE_URL','mrfoto/app/backend');
define('ADMIN_USERNAME', 'koira');
define('ADMIN_PASSWORD','kissa');
?>