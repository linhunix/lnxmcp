<?php

////////////////////////////////////////////////////////////////////////////////
//  MAIL INTERACTION
////////////////////////////////////////////////////////////////////////////////
/**
 * lnxStdMail function.
 *
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param string $additional_headers
 * @param string $additional_parameters
 * @param string $from
 * @param array  $attachDoc
 * @param string $template
 */
function lnxStdMail($to, $subject, $message, $additional_headers = null, $additional_parameters = null, $from = null, $attachDoc = array(), $template = null)
{
    $scopeIn = array(
        'to' => $to,
        'subject' => $subject,
        'message' => $message,
    );
    if ($additional_headers != null) {
        $scopeIn['headers'] = $additional_headers;
    }
    if ($additional_parameters != null) {
        $scopeIn['parameters'] = $additional_parameters;
    }
    if ($from != null) {
        $scopeIn['from'] = $from;
    }
    if ($attachDoc != null) {
        $scopeIn['files'] = $attachDoc;
    }
    if ($template != null) {
        $scopeIn['template'] = $template;
    }
    lnxmcp()->mail('sendmail', $scopeIn);
}
