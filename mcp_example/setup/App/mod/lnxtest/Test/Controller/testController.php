<?php

namespace lnxtest\Test\Controller;

/*
 *
 * @author    Andrea Morello :extended on 26/10/2018 LinHUniX ltd
 */
use LinHUniX\Mcp\Model\mcpBaseModelClass;
/**
 * This Api generate the meta tags for reload color Filter.
 */
class testController extends mcpBaseModelClass
{


    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal.
     */
    protected function moduleCore()
    {
        $this->argOut=array(
            "test"=>"ok"
        );
        $this->debug("this is a test");
        return "OK";
    }
}