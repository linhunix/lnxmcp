<?php

/**
 * LinHUniX Web Application Framework.
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version GIT:2018-v2
 */

namespace LinHUniX\Wait\Service;

use LinHUniX\Mcp\Model\mcpServiceModelClass;

class waitprocessService extends mcpServiceModelClass
{
    /**
     *  function moduleInit().
     */
    protected function moduleInit()
    {
        $this->spacename = __NAMESPACE__;
        $this->classname = __CLASS__;
    }

    /**
     *  function moduleSingleTon().
     */
    protected function moduleSingleTon()
    {
        $this->callCmd(
            array(
                'type' => 'queryJson',
                'module' => 'Wait',
                'vendor' => 'LinHUniX',
                'name' => 'sqllite_Init',
            ),
            array()
        );
    }

    /***
     * function prc_reset(){
     * [T]= prc
     * [E]= reset
     */
    public function prc_reset()
    {
        $this->callCmd(
            array(
                'type' => 'queryJson',
                'module' => 'Wait',
                'vendor' => 'LinHUniX',
                'name' => 'sqllite_selfdelete',
            ),
            array()
        );
    }

    /***
     * function prc_check(){
     * [T]= prc
     * [E]= check
     */
    public function prc_check()
    {
        if (!isset($this->argIn['action'])) {
            return false;
        }
        if ($this->argIn['action'] == '') {
            return false;
        }
        $this->prc_reset();
        if (!isset($this->argIn['value_in'])) {
            $this->argIn['value_in'] = array();
        }
        if ($this->argIn['value_in'] == '') {
            $this->argIn['value_in'] = array();
        }
        if (!isset($this->argIn['value_out'])) {
            $this->argIn['value_out'] = array();
        }
        if ($this->argIn['value_out'] == '') {
            $this->argIn['value_out'] = array();
        }
        if (!isset($this->argIn['del'])) {
            $this->argIn['del'] = 0;
        }
        if ($this->argIn['del'] == '') {
            $this->argIn['del'] = 0;
        }
        if (!isset($this->argIn['sts'])) {
            $this->argIn['sts'] = 0;
        }
        if ($this->argIn['sts'] == '') {
            $this->argIn['sts'] = 0;
        }
        $res = $this->callCmd(
            array(
                'type' => 'queryJson',
                'module' => 'Wait',
                'vendor' => 'LinHUniX',
                'name' => 'sqllite_show',
            ),
            $this->argIn
        );
        if (isset($res['name'])) {
            return false;
        }
        $res = $this->callCmd(
            array(
                'type' => 'queryJson',
                'module' => 'Wait',
                'vendor' => 'LinHUniX',
                'name' => 'sqllite_add',
            ),
            $this->argIn
        );

        return true;
    }

    /***
     * function prc_update(){
     * [T]= prc
     * [E]= update
     */
    public function prc_update()
    {
        if (!isset($this->argIn['action'])) {
            return false;
        }
        if ($this->argIn['action'] == '') {
            return false;
        }
        $this->prc_reset();
        if (!isset($this->argIn['value_in'])) {
            $this->argIn['value_in'] = array();
        }
        if ($this->argIn['value_in'] == '') {
            $this->argIn['value_in'] = array();
        }
        if (!isset($this->argIn['value_out'])) {
            $this->argIn['value_out'] = array();
        }
        if ($this->argIn['value_out'] == '') {
            $this->argIn['value_out'] = array();
        }
        if (!isset($this->argIn['del'])) {
            $this->argIn['del'] = 0;
        }
        if ($this->argIn['del'] == '') {
            $this->argIn['del'] = 0;
        }
        if (!isset($this->argIn['sts'])) {
            $this->argIn['sts'] = 0;
        }
        if ($this->argIn['sts'] == '') {
            $this->argIn['sts'] = 0;
        }
        $res = $this->callCmd(
            array(
                'type' => 'queryJson',
                'module' => 'Wait',
                'vendor' => 'LinHUniX',
                'name' => 'sqllite_show',
            ),
            $this->argIn
        );
        if (isset($res['name'])) {
            $res = $this->callCmd(
                array(
                    'type' => 'queryJson',
                    'module' => 'Wait',
                    'vendor' => 'LinHUniX',
                    'name' => 'sqllite_update',
                ),
                $this->argIn
            );
        } else {
            $res = $this->callCmd(
                array(
                    'type' => 'queryJson',
                    'module' => 'Wait',
                    'vendor' => 'LinHUniX',
                    'name' => 'sqllite_add',
                ),
                $this->argIn
            );
        }

        return true;
    }

    /***
       * function prc_show(){
       * [T]= prc
       * [E]= show
       */
    public function prc_show()
    {
        if (!isset($this->argIn['action'])) {
            return false;
        }
        if ($this->argIn['action'] == '') {
            return false;
        }
        $this->prc_reset();
        $res = $this->callCmd(
            array(
                'type' => 'queryJson',
                'module' => 'Wait',
                'vendor' => 'LinHUniX',
                'name' => 'sqllite_show',
            ),
            $this->argIn
        );
        if (isset($res['name'])) {
            return false;
        }
        $res = $this->callCmd(
            array(
                'type' => 'queryJson',
                'module' => 'Wait',
                'vendor' => 'LinHUniX',
                'name' => 'sqllite_add',
            ),
            $this->argIn
        );
        if (is_array($res)) {
            return $res;
        }

        return false;
    }
}
