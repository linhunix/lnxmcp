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
class uploadController extends mcpBaseModelClass
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
        if (!isset($_FILES)) {
            $this->warning('_FILES IS NOT ENABLE');

            return false;
        }
        if (!is_array($_FILES)) {
            $this->warning('_FILES IS NOT VALID');

            return false;
        }
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
        $app_user = $this->getRes('path.userfile');
        $cat = $this->argIn['category'];
        $dir_save = $app_user.DIRECTORY_SEPARATOR.$cat;
        if ($this->check_folder($app_user, $dir_save) == false) {
            return false;
        }
        $convertname = false;
        $fileconvert = '';
        if (isset($this->argIn['fileconvert'])) {
            $convertname = true;
            $fileconvert = $this->argIn['fileconvert'];
            if ($fileconvert == '') {
                $fileconvert = '[category]_[rand]';
            }
        }
        $allowall = true;
        $allowlist = array();
        if (isset($this->argIn['allowlist'])) {
            $allowall = false;
            $allowlist = $this->argIn['allowlist'];
        }
        $allowAllFields = true;
        $allowFields = array();
        if (isset($this->argIn['allowfields'])) {
            $allowAllFields = false;
            $allowFields = $this->argIn['allowfields'];
        }
        $meta = array();
        if (\is_array($_FILES)){
            $mdie=false;
            foreach ($_FILES as $tag => $value) {
                $mdie=true;
                $meta[$tag] = $value;
                $meta[$tag]['date_std'] = date("Y-m-d H:i:s");
                $meta[$tag]['date_unx'] = date('U');
                $meta[$tag]['category'] = $cat;
                $meta[$tag]['field'] = $tag;
                $meta[$tag]['original_name'] = $_FILES[$tag]['name'];
                $file_load = $_FILES[$tag]['tmp_name'];
                if (empty($file_load)) {
                    $meta[$tag]['error'] = 'Error on load '.$tag;
                    $this->warning('Error on load '.$tag);
                    continue;
                }
                $file_arr = explode('.', $_FILES[$tag]['name']);
                $file_type = array_pop($file_arr);
                $file_name = implode('.', $file_arr);
                $file_name = preg_replace('/[^A-Za-z0-9\-\_]/', '', $file_name);
                if ($file_name==""){
                    $file_name=$tag;
                } 
                $meta[$tag]['original_name'] = $_FILES[$tag]['name'];
                $meta[$tag]['type'] = $file_type;
                $meta[$tag]['basename'] = $file_name;
                $meta[$tag]['user']=$this->getCommon("user");
                if ($allowall == false) {
                    if (!in_array($file_type, $allowlist)) {
                        $meta[$tag]['error'] = $file_type.' is denied !!';
                        $this->warning($file_type.' is denied !!');
                        continue;
                    }
                }
                if ($allowAllFields == false) {
                    if (!in_array($tag, $allowFields)) {
                        $meta[$tag]['error'] = $tag.' field is denied !!';
                        $this->warning($tag.' field is denied !!');
                        continue;
                    }
                }
                $meta[$tag]['newname'] = $file_name;
                $newfile = $file_name;
                if ($convertname == true) {
                    $newfile = $fileconvert;
                    $rand = rand(0, 100000);
                    $newfile = str_replace('[rand]', $rand, $newfile);
                    $newfile = str_replace('[basename]', $file_name, $newfile);
                    $newfile = str_replace('[filetype]', $file_type, $newfile);
                    $newfile = str_replace('[field]', $tag, $newfile);
                    foreach ($this->$argIn as $ksrc => $vsrc) {
                        $newfile = str_replace('['.$ksrc.']', $vsrc, $newfile);
                    }
                    $meta[$tag]['newname'] = $newfile;
                }
                $meta[$tag]['metaname'] = $meta[$tag]['newname'];
                $meta[$tag]['newname'] .= '.'.$file_type;
                $file_load = $_FILES[$tag]['tmp_name'];
                $file_save = $dir_save.DIRECTORY_SEPARATOR.$meta[$tag]['newname'];
                if (!copy($file_load, $file_save)) {
                    $meta[$tag]['error'] = $file_save.' can be generate !!';
                    $this->warning($file_save.' can be generate !!');
                    continue;
                }
                lnxPutJsonFile($meta[$tag],$meta[$tag]['metaname'], $dir_save, 'json');
            }
        }
        $this->argOut=$meta;
        return $meta;
    }
}
