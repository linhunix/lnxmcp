<?php
namespace LinHUniX\Mail\Service;

/**
 * LinHUniX Web Application Framework
 *
 * @author    Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version   GIT:2018-v2
 */


use LinHUniX\Mail\Component\POP3;
use LinHUniX\Mail\Component\SMTP;
use LinHUniX\Mail\Component\PHPMailer;
use LinHUniX\Mcp\masterControlProgram;
use LinHUniX\Mcp\Model\mcpBaseModelClass;
use LinHUniX\Mcp\Model\mcpDebugModelClass;
use LinHUniX\Mcp\Model\mcpConfigArrayModelClass;
use LinHUniX\Mcp\Model\mcpServiceProviderModelClass;

class mailService extends mcpBaseModelClass
{
    private $Smtp;
    private $Mailer;
    private $Pop3;
    private $From;
    private $TplPath;
    private $trace;
    private $domine;
    private function loadTemplate($content, $template, $useNL2BR = false) {
        $tplf = "";
        $tplb = "";
        $tplr = "";
        if ($content == null) {
            $content = "";
        }
        if (!empty($this->TplPath)) {
            $tplf = $this->TplPath;
        }
        $tplf .= $template;
        if (file_exists($tplf)) {
            $fileContents = nl2br(stripslashes(file_get_contents($tplf)));
            if ($useNL2BR) {
                return strip_tags(
                        str_replace('[content here]', $content, $fileContents)
                );
            }
            return str_replace('[content here]', nl2br($content), $fileContents);
        }
    }
    
    /**
     * MailHeader2String
     *
     * @param  mixed $to
     * @param  mixed $subject
     * @param  mixed $header
     * @param  mixed $caller
     * @param  mixed $status
     * @param  mixed $extra
     * @return string log
     */
    private function MailHeader2String($to, $subject, $header, $caller, $status, $extra) {
        return "{  Sts:\"" . $status . "\",To:\"" . $to . "\",Subject:\"" . $subject . "\",header:\"" . print_r($header, 1) . "|" . $caller . "|" . $extra . "\"}";
    }
    /**
     * This class use Zend Mail Frameworks and get informations form $dic and FTConfig class
     * as extension of the normal function, is present a tracker log of the sent mail 
     * @param string $to reciver name 
     * @param string $subject needed 
     * @param string $message if is null : Regards \n" and generalMailFull FTConfig 
     * @param array $additional_headers option
     * @param string $additional_parameters not used really only for compatibility
     * @param string $from sender mail 
     * @return boolean
     * @see FTConfig
     * @example TestFTMail
     * @assert ("andrea.morello@freetimers.com","TestMail","Demo Morselli") == true
     */
    public function stdMailWithDoc($to, $subject, $message, array $attachDoc = array(), $additional_headers = null, $additional_parameters = null, $from = null) {
        return $this->stdMail($to, $subject, $message, $additional_headers, $additional_parameters, $from, $attachDoc);
    }
    /**
     * This class use Zend Mail Frameworks and get informations form $dic and FTConfig class
     * as extension of the normal function, is present a tracker log of the sent mail 
     * @param string $to reciver name 
     * @param string $subject needed 
     * @param string $message if is null : Regards \n" and generalMailFull FTConfig 
     * @param array $additional_headers option
     * @param string $additional_parameters not used really only for compatibility
     * @param string $from sender mail 
     * @return boolean
     * @see FTConfig
     * @example TestFTMail
     * @assert ("andrea.morello@freetimers.com","TestMail","Demo Morselli") == true
     */
    public function stdMail($to, $subject, $message, $additional_headers = null, $additional_parameters = null, $from = null, array $attachDoc = array(),$html=false) {
        try{
            $mymail = clone $this->Mailer;
            if ($additional_headers!=null){
                $mymail->header=$additional_headers;
            }
            $mymail->addAddress($to);
            $mymail->Subject=$subject;
            foreach ($attachDoc as $attname=>$filetoadd){
                $html=true;
                if(file_exists($filetoadd)==false){
                    $filetoadd=$this->mcp->getResource("path")+$filetoadd;
                    if(file_exists($filetoadd)==false){
                        continue;
                    }
                }
                if (is_numeric($attname)){
                    $attname=basename($filetoadd);
                }
                $mymail->addAttachment($filetoadd,$attname);
            }
            if ($html==true){
                $mymail->isHTML(true);
            }
            $mymail->body=$message;
            $mymail->send();
            return true;
        }catch(\Exception $e){
            $this->mcp->error($e->get_message());            
        }
        return false;
    }
    /**
     * Register the settings as a provider with a container
     *
     */
    public function __construct (masterControlProgram &$mcp, array $scopeCtl, array $scopeIn){
        parent::__construct($mcp, $scopeCtl, $scopeIn);
        $this->Smtp=new SMTP();
        $this->Pop3=new POP3();
        $this->Mailer=new PHPMailer();
        $this->Trace=false;
        /// trace config 
        $level = $mcp->getCfg ("app.level");
        if ($level == mcpDebugModelClass::DEBUG) {
            $this->trace=true;
        }
        $this->domine = $mcp->getResource("mail.domine");
        $this->From = $mcp->getResource("mail.from");
        $smtphost = $mcp->getResource("mail.smtp.host");
        $smtpport = $mcp->getResource("mail.smtp.port");
        $smtpuser = $mcp->getResource("mail.smtp.user");
        $smtppass = $mcp->getResource("mail.smtp.pass");
        $smtpasec = $mcp->getResource("mail.smtp.type");
        $pop3host = $mcp->getResource("mail.pop3.host");
        $pop3user = $mcp->getResource("mail.pop3.user");
        $pop3pass = $mcp->getResource("mail.pop3.pass");
        $pop3asec = $mcp->getResource("mail.pop3.type");
        if ($smtphost==null){
            $smtphost="localhost";
        }
        if ($smtpport==null){
            $smtpport="25";
        }
        if ($this->From==null){
            if($this->domine!=null){
                $this->From="noreply@".$this->domine;
            }
        }
        ////// init config
        if ($this->trace==true){
            $this->Mailer->SMTPDebug=2;
        }
        switch($smtpasec){
            case "tls":
            $this->Mailer->SMTPSecure="tls";
            case "user":
            $this->Mailer->SMTPAuth = true; 
            $this->Mailer->Username = $smtpuser;
            $this->Mailer->Password = $smptpass;
            case "smtp":
            $this->Mailer->isSMTP();
            $this->Mailer->Host = $smtphost;
            $this->Mailer->Port = $smtpport; 
        }
    }
    /**
     * @author Andrea Morello <andrea.morello@freetimers.com>
     * @version GIT:2018-v1
     * @param Container $dic dependency injection with Pimple\Container 
     * @param array $scope temporaney array from system auto cleanable
     * @return boolean status of the operations 
     * @see mcpBaseModelClass Class 
     */
    protected function moduleCore() {
        $to = @$this->argIn["to"];
        $from = @$this->argIn["from"];
        $subject = @$this->argIn["subject"];
        $message = @$this->argIn["message"];
        $headers = @$this->argIn["headers"];
        $parameters = @$this->argIn["parameters"];
        if (isset($this->argIn["template"])) {
            $message = $this->loadTemplate($message, $this->argIn["template"]);
        }
        $this->argOut = $this->stdMail($to, $subject, $message, $headers, $parameters, $from);
    }
}