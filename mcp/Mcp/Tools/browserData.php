<?php
/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2019, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 *
 */
namespace LinHUniX\Mcp\Tools;
class browserData
{
    public $languages = array(
        'af'=>"afrikaans",
        'ar'=>"arabic",
        'bg'=>"bulgarian",
        'ca'=>"catalan",
        'cs'=>"czech",
        'da'=>"danish",
        'de'=>"german",
        'el'=>"greek",
        'en'=>"english",
        'es'=>"spanish",
        'et'=>"estonian",
        'fi'=>"finnish",
        'fr'=>"french",
        'gl'=>"galician",
        'he'=>"hebrew",
        'hi'=>"hindi",
        'hr'=>"croatian",
        'hu'=>"hungarian",
        'id'=>"indonesian",
        'it'=>"italian",
        'ja'=>"japanese",
        'ko'=>"korean",
        'ka'=>"georgian",
        'lt'=>"lithuanian",
        'lv'=>"latvian",
        'ms'=>"malay",
        'nl'=>"dutch",
        'no'=>"norwegian",
        'pl'=>"polish",
        'pt'=>"portuguese",
        'ro'=>"romanian",
        'ru'=>"russian",
        'sk'=>"slovak",
        'sl'=>"slovenian",
        'sq'=>"albanian",
        'sr'=>"serbian",
        'sv'=>"swedish",
        'th'=>"thai",
        'tr'=>"turkish",
        'uk'=>"ukrainian",
        'zh'=>"chinese",
        );
    private $user_agent ="";
    private $result=array();
    public function __construct ()
    {
        $this->user_agent=$_SERVER['HTTP_USER_AGENT'];
        $this->result["platform"]=$this->getOS ();
        $this->result["browser"]=$this->getBrowser ();
        $this->result["navigator"]=$this->getNavigator ();
        $this->result["language"]=$this->getLanguage ();
    }
    public function getResult(){
        return $this->result;
    }
    public function getJsonRes(){
        return json_encode ($this->result);
    }
    public function getJsonPlain(){
        return '{'
            .'"Browser Name":"'.$this->result['browser']['name'].'",'
            .'"Browser Version":"'.$this->result['browser']['version'].'",'
            .'"Browser Extra":"'.$this->result['browser']['extra'].'",'
            .'"Browser Size":"N.C.",'
            .'"Browser Cookie":"N.C.",'
            .'"Platform Name":"'.$this->result['platform']['name'].'",'
            .'"Platform Version":"'.$this->result['platform']['version'].'",'
            .'"Platform Extra":"'.$this->result['platform']['extra'].'",'
            .'"Platform Mobile":"N.C.",'
            .'"Navigator Name":"'.$this->result['navigator']['name'].'",'
            .'"Navigator Version":"'.$this->result['navigator']['version'].'",'
            .'"Navigator Extra":"'.$this->result['navigator']['extra'].'.",'
            .'"Language":"'.$this->result['language'].'."'
            ."}";

    }
    private function getNavigator(){
        return array(
            "name"=>"Php",
            "version"=>"Not Required",
            "extra"=>$this->user_agent
        );
    }
    private function getOS ()
    {
        $os_platform = "Unknown OS Platform";
        $os_array = array (
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );
        foreach ($os_array as $regex => $value) {
            if (preg_match ($regex, $this->user_agent)) {
                $os_platform = $value;
            }
        }
        return array(
            "name"=>$os_platform,
            "version"=>"",
            "extra"=>""
        );
    }

    private function getBrowserName ()
    {

        $browser = array("Unknown Browser","Unknown");
        $browser_array = array (
            '/msie/i' => array('Microsoft Internet Explorer','MSIE'),
            '/firefox/i' => array('Mozilla Firefox','Firefox'),
            '/safari/i' => array('Apple Safari','Safari'),
            '/chrome/i' => array('Google Chrome','Chrome'),
            '/edge/i' => array('Microsoft Edge','Edge'),
            '/opera/i' => array('Opera','Opera'),
            '/netscape/i' => array('Netscape Navigator','Netscape'),
            '/maxthon/i' => array('Maxthon Cloud',' Maxthon'),
            '/konqueror/i' => array('Opensource Konqueror','Konqueror'),
            '/mobile/i' => array('Handheld Browser','Mobile')
        );
        foreach ($browser_array as $regex => $value)
            if (preg_match ($regex, $this->user_agent))
                $browser = $value;
        return $browser;
    }
    private function getBrowser ()
    {
        $barr=$this->getBrowserName ();
        $bname=$barr[0];
        $ub=$barr[1];
        $version = "";
        // finally get the correct version number
        $known = array ('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join ('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all ($pattern,  $this->user_agent, $matches)) {
            // we have no matching number just continue
        }
        // see how many we have
        $i = count ($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos ( $this->user_agent, "Version") < strripos ( $this->user_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }
        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }
        return array (
            'name' => $bname,
            'version' => $version,
            'extra' => $pattern
        );
    }
    /**
     * Get Language 
     *
     * @return string language
     */
    function getLanguage()
    {
        $_AL=strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        // Try to detect Primary language if several languages are accepted.
        foreach($this->languages as $K=>$Vx)
        {
            if(strpos($_AL, $K)===0){
                return $K;
            }
        }
        // Try to detect any language if not yet detected.
        foreach($this->languages as $K=>$Vx)
        {
            if(strpos($_AL, $K)!==false){
                return $K;
            }
        }
        return "en";
    }

}
