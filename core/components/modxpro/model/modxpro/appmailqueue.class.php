<?php

/**
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property array $properties
 * @property string $createdon
 */
class appMailQueue extends xPDOSimpleObject
{
    /**
     * @return bool
     */
    public function send()
    {
        $properties = $this->get('properties');
        $sender = $this->xpdo->getOption('emailsender');
        $site = $this->xpdo->getOption('site_name');
        /** @var appMail $mail */
        $mail = $this->xpdo->getService('mail', 'AppMail', MODX_CORE_PATH . 'components/extras/model/');
        $mail->setHTML(true);
        $mail->set(modMail::MAIL_SUBJECT, $this->subject);
        $mail->set(modMail::MAIL_BODY, $this->body);
        $mail->set(modMail::MAIL_SENDER, $this->xpdo->getOption('sender', $properties, $sender));
        $mail->set(modMail::MAIL_FROM, $this->xpdo->getOption('from', $properties, $sender));
        $mail->set(modMail::MAIL_FROM_NAME, $this->xpdo->getOption('fromName', $properties, $site));
        $mail->address('to', $this->to);
        $mail->address('reply-to', $this->xpdo->getOption('reply-to', $properties, $sender));
        $send = $mail->send();
        $mail->reset();
        if (!$send) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not send email: ' . $mail->mailer->ErrorInfo);
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, print_r($this->toArray(), 1));

            return false;
        }

        return true;
    }

}