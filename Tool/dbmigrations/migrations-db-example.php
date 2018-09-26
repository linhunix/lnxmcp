<?php
/**
 * LinHUniX Web Application Framework
 *
 * Development environment doctrine-migrations database configuration file
 * Copy this file to /migrations-db.php
 *
 * DO NOT commit it to git.
 *
 * simply replace the getenv()s with string values or set the values in your environment
 *
 * @author    Ashley Kitson
 * @copyright LinHUniX Communications Limited, 2017, UK
 * @license   GPL 3.0 See LICENSE.md
 */
return array(
    'dbname' => getenv('SDK_DB_NAME1'),
    'user' => getenv('SDK_DB_UID'),
    'password' => getenv('SDK_DB_PWD'),
    'host' => getenv('SDK_DB_HOST'),
    'driver' => 'mysqli'
);