<?php

namespace LinHUniX\Mail\Service;

/*
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

class mailService extends mcpBaseModelClass
{
    private $Smtp;
    private $Mailer;
    private $Pop3;
    private $From;
    private $TplPath;
    private $trace;
    private $domine;
    private $testmail;

    private function loadTemplate($content, $template, $useNL2BR = false)
    {
        $tplf = '';
        $tplb = '';
        $tplr = '';
        if ($content == null) {
            $content = '';
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
     * MailHeader2String.
     *
     * @param mixed $to
     * @param mixed $subject
     * @param mixed $header
     * @param mixed $caller
     * @param mixed $status
     * @param mixed $extra
     *
     * @return string log
     */
    private function MailHeader2String($to, $subject, $header, $caller, $status, $extra)
    {
        return '{  Sts:"'.$status.'",To:"'.$to.'",Subject:"'.$subject.'",header:"'.print_r($header, 1).'|'.$caller.'|'.$extra.'"}';
    }

    /**
     * This class use Zend Mail Frameworks and get informations form $dic and FTConfig class
     * as extension of the normal function, is present a tracker log of the sent mail.
     *
     * @param string $to                    reciver name
     * @param string $subject               needed
     * @param string $message               if is null : Regards \n" and generalMailFull FTConfig
     * @param array  $additional_headers    option
     * @param string $additional_parameters not used really only for compatibility
     * @param string $from                  sender mail
     *
     * @return bool
     *
     * @see FTConfig
     *
     * @example TestFTMail
     * @assert ("andrea.morello@linhunix.com","TestMail","Demo Morselli") == true
     */
    public function stdMailWithDoc($to, $subject, $message, $attachDoc = array(), $additional_headers = null, $additional_parameters = null, $from = null)
    {
        return $this->stdMail($to, $subject, $message, $additional_headers, $additional_parameters, $from, $attachDoc);
    }

    /**
     * This class use Zend Mail Frameworks and get informations form $dic and FTConfig class
     * as extension of the normal function, is present a tracker log of the sent mail.
     *
     * @param string $to                    reciver name
     * @param string $subject               needed
     * @param string $message               if is null : Regards \n" and generalMailFull FTConfig
     * @param array  $additional_headers    option
     * @param string $additional_parameters not used really only for compatibility
     * @param string $from                  sender mail
     *
     * @return bool
     *
     * @see FTConfig
     *
     * @example TestFTMail
     * @assert ("andrea.morello@linhunix.com","TestMail","Demo Morselli") == true
     */
    public function stdMail($to, $subject, $message, $additional_headers = null, $additional_parameters = null, $from = null, $attachDoc = array(), $html = false)
    {
        $this->mcp->info('StdMail: from/to/subject = '.$from.'/'.$to.'/'.$subject);
        try {
            $mymail = clone $this->Mailer;
            if ($additional_headers != null) {
                $mymail->header = $additional_headers;
            }
            if (!empty($this->testmail)) {
                $to = str_replace('@', '(at)', $to);
                $to = str_replace('<', '(', $to);
                $to = str_replace('>', ')', $to);
                $to .= '<'.$this->testmail.'>';
            }
            $mymail->addAddress($to);
            $mymail->Subject = $subject;
            if (!is_array($attachDoc)) {
                $attachDoc = array($attachDoc);
            }
            foreach ($attachDoc as $attname => $filetoadd) {
                $html = true;
                if (file_exists($filetoadd) == false) {
                    $filetoadd = $this->mcp->getResource('path').$filetoadd;
                    if (file_exists($filetoadd) == false) {
                        continue;
                    }
                }
                if (is_numeric($attname)) {
                    $attname = basename($filetoadd);
                }
                $mymail->addAttachment($filetoadd, $attname);
            }
            if ($html == true) {
                $mymail->isHTML(true);
            }
            $mymail->Body = $message;
            lnxmcp()->debugVar('Mail Class', 'body', $mymail->Body);
            $stsmail = 'unsuccess!!';
            $retmail = false;
            if ($mymail->send() == true) {
                $stsmail = 'success done !! ';
                $retmail = true;
            }
            if ($retmail == false) {
                $this->mcp->warning('StdMail: '.$stsmail.' sent from/to/subject = '.$from.'/'.$to.'/'.$subject);
                $this->mcp->warning('StdMail:'.$mymail->ErrorInfo);
            } else {
                $this->mcp->info('StdMail: '.$stsmail.' sent from/to/subject = '.$from.'/'.$to.'/'.$subject);
            }

            return $retmail;
        } catch (\Exception $e) {
            $this->mcp->error('StdMail>>ERROR:'.$e->get_message());
        }

        return false;
    }

    /**
     * Register the settings as a provider with a container.
     */
    public function __construct(masterControlProgram &$mcp, array $scopeCtl, array $scopeIn = array())
    {
        parent::__construct($mcp, $scopeCtl, $scopeIn = array());
        /// trace config
        try {
            $this->Smtp = new SMTP();
            $this->Pop3 = new POP3();
            $this->Mailer = new PHPMailer();
            $this->Trace = false;
            $this->testmail = '';
            $level = $mcp->getCfg('app.level');
            if ($level == mcpDebugModelClass::DEBUG) {
                $this->trace = true;
                $this->Mailer->SMTPDebug = 3;
                $this->Mailer->Debugoutput = 'error_log';
                $this->Smtp->do_debug = 3;
                $this->Smtp->Debugoutput = 'error_log';
                $this->Pop3->do_debug = 1;
            }
            if (!isset($scopeIn['config'])) {
                $scopeIn['config'] == 'SOURCE';
            }
            $this->debug('TYPE'.$scopeIn['config']);
            if ($scopeIn['config'] == 'Env') {
                $this->domine = $_SERVER['HOSTNAME'];
                if (isset($scopeIn['mail.domine'])) {
                    $this->domine = getenv($scopeIn['mail.domine']);
                }
                if (isset($scopeIn['mail.from'])) {
                    $this->From = getenv($scopeIn['mail.from']);
                }
                if (isset($scopeIn['mail.test'])) {
                    $this->testmail = getenv($scopeIn['mail.test']);
                }
                if (isset($scopeIn['mail.smtp.host'])) {
                    $smtphost = getenv($scopeIn['mail.smtp.host']);
                }
                if (isset($scopeIn['mail.smtp.port'])) {
                    $smtpport = getenv($scopeIn['mail.smtp.port']);
                }
                if (isset($scopeIn['mail.smtp.user'])) {
                    $smtpuser = getenv($scopeIn['mail.smtp.user']);
                }
                if (isset($scopeIn['mail.smtp.pass'])) {
                    $smtppass = getenv($scopeIn['mail.smtp.pass']);
                }
                if (isset($scopeIn['mail.smtp.type'])) {
                    $smtpasec = getenv($scopeIn['mail.smtp.type']);
                }
                if (isset($scopeIn['mail.pop3.host'])) {
                    $pop3host = getenv($scopeIn['mail.pop3.host']);
                }
                if (isset($scopeIn['mail.pop3.user'])) {
                    $pop3user = getenv($scopeIn['mail.pop3.user']);
                }
                if (isset($scopeIn['mail.pop3.pass'])) {
                    $pop3pass = getenv($scopeIn['mail.pop3.pass']);
                }
                if (isset($scopeIn['mail.pop3.type'])) {
                    $pop3asec = getenv($scopeIn['mail.pop3.type']);
                }
            }
            if ($scopeIn['config'] == 'SOURCE') {
                $this->domine = $mcp->getResource('mail.domine');
                $this->From = $mcp->getResource('mail.from');
                $this->testmail = getenv($scopeIn['mail.test']);
                $smtphost = $mcp->getResource('mail.smtp.host');
                $smtpport = $mcp->getResource('mail.smtp.port');
                $smtpuser = $mcp->getResource('mail.smtp.user');
                $smtppass = $mcp->getResource('mail.smtp.pass');
                $smtpasec = $mcp->getResource('mail.smtp.type');
                $pop3host = $mcp->getResource('mail.pop3.host');
                $pop3user = $mcp->getResource('mail.pop3.user');
                $pop3pass = $mcp->getResource('mail.pop3.pass');
                $pop3asec = $mcp->getResource('mail.pop3.type');
            }
            if ($smtphost == null) {
                $smtphost = 'localhost';
            }
            if ($smtpport == null) {
                $smtpport = '25';
            }
            if (($this->domine == null) or ($this->domine == '')) {
                $this->domine = $_SERVER['HOSTNAME'];
            }
            if (($this->From == null) or ($this->From == '')) {
                $this->From = 'noreply@'.$this->domine;
            }
            ////// init config
            if ($this->trace == true) {
                $this->Mailer->SMTPDebug = 2;
            }
            if ($smtpasec == null) {
                if ($smtpuser == null) {
                    $smtpasec = 'sendmail';
                } else {
                    $smtpasec = 'user';
                }
            }
            switch ($smtpasec) {
                case 'tls':
                    $this->Mailer->SMTPSecure = 'tls';
                    // no break
                case 'user':
                    $this->Mailer->SMTPAuth = true;
                    $this->Mailer->Username = $smtpuser;
                    $this->Mailer->Password = $smptpass;
                    // no break
                case 'smtp':
                    $this->Mailer->isSMTP();
                    $this->Mailer->Host = $smtphost;
                    $this->Mailer->Port = $smtpport;
            }
        } catch (\Exception $e) {
            $this->getMcp()->warning('MailService-Init:'.$e->getMessage());
        }
    }

    /**
     * @author Andrea Morello <andrea.morello@linhunix.com>
     *
     * @version GIT:2018-v1
     *
     * @param Container $dic   dependency injection with Pimple\Container
     * @param array     $scope temporaney array from system auto cleanable
     *
     * @return bool status of the operations
     *
     * @see mcpBaseModelClass Class
     */
    protected function moduleCore()
    {
        $error = '';
        $to = '';
        if (isset($this->argIn['to'])) {
            $to = $this->argIn['to'];
        }
        if (empty($to)) {
            $error .= 'to,';
        }
        $subject = '';
        if (isset($this->argIn['subject'])) {
            $subject = $this->argIn['subject'];
        }
        if (empty($subject)) {
            $error .= 'subject,';
        }
        $message = '';
        if (isset($this->argIn['message'])) {
            $message = $this->argIn['message'];
        }
        if ($message == '') {
            $error .= 'message,';
        }
        if ($error != '') {
            lnxmcp()->warning('Mail Class has empty sender '.$error.' !!');
        }
        $from = '';
        if (isset($this->argIn['from'])) {
            $from = $this->argIn['from'];
        }
        $files = '';
        if (isset($this->argIn['files'])) {
            $files = $this->argIn['files'];
        }
        $headers = '';
        if (isset($this->argIn['headers'])) {
            $headers = $this->argIn['headers'];
        }
        $parameters = '';
        if (isset($this->argIn['parameters'])) {
            $parameters = $this->argIn['parameters'];
        }
        if (isset($this->argIn['template'])) {
            $premsg = $message;
            $message = $this->loadTemplate($message, $this->argIn['template']);
            if ($message == '') {
                lnxmcp()->warning('Mail Class has template '.$this->argIn['template'].' generate a empty message  !!');
                $message = $premsg;
            }
        }
        lnxmcp()->debugVar('Mail Class', 'message', $message);
        $this->argOut = $this->stdMailWithDoc($to, $subject, $message, $files, $headers, $parameters, $from);
    }
}
