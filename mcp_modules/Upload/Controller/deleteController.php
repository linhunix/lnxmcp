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
class deleteController extends mcpBaseModelClass
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
        if (!isset($this->argIn['files'])) {
            $this->warning('file is not present');
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
        $filetodel =  $this->argIn['files'];
        $filestodel = $filetodel;
        if (!is_array($filetodel)){
            $filestodel=array($filetodel);
        }
        $results=array();
        foreach ($filestodel as $myfile){
            $file_arr = explode('.', $myfile);
            $file_type = array_pop($file_arr);
            $file_name = implode('.', $file_arr);
            $result=$this->argIn;
            $result['type']=$file_type;
            $result['basenane']=$file_name;
            $result['metaname']=$file_name;       
            if (!file_exists($dir_save.'/'.$myfile)){
                $result["error"]=$myfile.' not found!!';
                $results[$myfile]=$result;
                continue;
            }
            try {
                unlink($dir_save.'/'.$myfile);
                unlink($dir_save.'/'.$file_name.'.json');
            }catch(Exception $e){
                $result["error"]=$e->getMessage();
            }
            $results[$myfile]=$result;
        }
        $this->argOut=$results;
        return $results;
    }
}
