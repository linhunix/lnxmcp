<?php
/**
 * Created by PhpStorm.
 * User: freetimers
 * Date: 9/4/2018
 * Time: 10:11 AM
 */
namespace LinHUniX\Mcp\Model;
use LinHUniX\Mcp\masterControlProgram;

interface mcpServiceProviderClass
{
    public function register(masterControlProgram $mcp,mcpConfigArrayModelClass &$cfg) ;
}