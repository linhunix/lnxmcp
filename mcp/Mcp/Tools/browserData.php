<?php
/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <lnxmcp@linhunix.com>
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
        $this->result["mobile"]=$this->isMobile ();
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
    /**
     * Check if  is mobile device 
     * @return bool 
     */
    function isMobile(){
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        if(
            preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent,0,4))
            ){
                return true;
            }
            return false;
    }

}
