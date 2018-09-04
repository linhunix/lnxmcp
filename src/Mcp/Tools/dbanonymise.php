#!/usr/bin/env php
<?php
/**
 * LinHUniX Web Application Framework
 * Kingswood Catering
 *
 * Script to anonymise production data for use in development environments
 *
 * @author    Ashley Kitson
 * @copyright LinHUniX Communications Limited, 2017, UK
 * @license   GPL 3.0 See LICENSE.md
 */
namespace LinHUniX\Mcp\Tools;

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Sdk\Tools\Dbanon\AnonymiseCommand;

$app = new Application('dbanonymise.php', '1.0.0');
$cmd = new AnonymiseCommand();
$app->add($cmd);
$app->setDefaultCommand($cmd->getName(), true);
$app->run();

