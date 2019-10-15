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
class initController extends mcpBaseModelClass {
    /**
     * check args is ok 
     * @return boolean
     */
    private function check_args(){
       //// CHECK IF CAN WORK 
        if (!isset ($_FILES)) {
            $this->warning("_FILES IS NOT ENABLE");
            return false;
        }
        if (!is_array($_FILES)) {
            $this->warning("_FILES IS NOT VALID");
            return false;
        }
        if (!isset($this->argIn['category'])){
            $this->warning("category is not present");
            return false;
        }
        return true;
    }
    /**
     * @param string $app_user User folder
     * @param string $dir_save dedicate storage folder
     * @return boolean
     */
    private function check_folder($app_user,$dir_save){
        if (!file_exists($dir_save)) {
            if (is_writable($app_user)) {
                mkdir($dir_save);
            } else {
                $this->warning($dir_save." is not present");
                return false;    
            }
        }
        if (!is_writable($dir_save)) {
            $this->warning($dir_save." is not writable");
            return false;
        }
        return true;
    }

    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal.
     */
    protected function moduleCore(){
        if ($this->check_args()==false){
            return false;
        }
        // GET PARAMETERS;
        $app_user=$this->getRes("path.userfile");
        $cat=$this->argIn['category'];
        $dir_save=$app_user.DIRECTORY_SEPARATOR.$cat;
        if ($this->check_folder($app_user,$dir_save)==false){
            return false;
        }
        $convertname=false;
        $fileconvert='';
        if (isset($this->argIn['fileconvert'])){
            $convertname=true;
            $fileconvert=$this->argIn['fileconvert'];
        }
        $allowall=true;
        $allowlist=array();
        if (isset($this->argIn['allowlist'])){
            $allowall=false;
            $allowlist=$this->argIn['allowlist'];
        }
    }
}