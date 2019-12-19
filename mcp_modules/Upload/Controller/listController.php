<?php

namespace LinHUniX\Upload\Controller;

use LinHUniX\Mcp\Model\mcpBaseModelClass;

/**
 * LinHUniX Web Application Framework.
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version GIT:2018-v2
 */
class listController extends mcpBaseModelClass
{
    /**
     *  function moduleInit() 
     */
    protected function moduleInit(){
        $this->spacename=__NAMESPACE__;
        $this->classname=__CLASS__;
    }

    /**
     * check args is ok.
     *
     * @return bool
     */
    private function check_args()
    {
        //// CHECK IF CAN WORK
        if (!isset($this->argIn['category'])) {
            $this->warning('category is not present');

            return false;
        }

        return true;
    }

    /**
     * @param string $app_user User folder
     * @param string $dir_save dedicate storage folder
     *
     * @return bool
     */
    private function check_folder($app_user, $dir_save)
    {
        if (!file_exists($dir_save)) {
            if (is_writable($app_user)) {
                mkdir($dir_save);
            } else {
                $this->warning($dir_save.' is not present');

                return false;
            }
        }
        if (!is_writable($dir_save)) {
            $this->warning($dir_save.' is not writable');

            return false;
        }

        return true;
    }

    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal.
     */
    protected function moduleCore()
    {
        if ($this->check_args() == false) {
            return false;
        }
        // GET PARAMETERS;
        $app_user = $this->getRes('path.userfiles');
        $cat = $this->argIn['category'];
        $dir_save = $app_user.DIRECTORY_SEPARATOR.$cat;
        if ($this->check_folder($app_user, $dir_save) == false) {
            return false;
        }
        $allowall = true;
        $allowlist = array();
        if (isset($this->argIn['allowlist'])) {
            $allowall = false;
            $allowlist = $this->argIn['allowlist'];
        }
        //lnxPutJsonFile($meta[$tag], $dir_save, $meta[$tag]['metaname'], 'json');
        $list=\scandir($dir_save);
        if (!is_array($list)){
            $this->warning($dir_save.' is empty or not accessible');
            return false;            
        }
        $result=array();
        foreach ($list as $dirfile) {
            $err='none';
            if (($dirfile=='.') or ($dirfile == '..')){
                continue;
            }
            $file_arr = explode('.', $dirfile);
            $file_type = array_pop($file_arr);
            $file_name = implode('.', $file_arr);
            if ($file_type=='json'){
                continue;
            }
            if ($allowall == false) {
                if (!in_array($file_type, $allowlist)) {
                    $err = $file_type.' is denied !!';
                    $this->warning($file_type.' is denied !!');
                    continue;
                }
            }
            $result[$dirfile]=lnxGetJsonFile($file_name,$dir_save,'json');
            $result[$dirfile]["error"]=$err;
        }
        $this->argOut=$result;
        return $result;
    }
}
