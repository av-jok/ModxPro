<?php

if (!class_exists('modPHPMailer')) {
    /** @noinspection PhpIncludeInspection */
    require MODX_CORE_PATH . 'model/modx/mail/modphpmailer.class.php';
}

class AppMail extends modPHPMailer
{
    /** @var PHPMailer $mailer */
    public $mailer;


    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        if ($key == modMail::MAIL_BODY) {
            $emogrifier = new \Pelago\Emogrifier($value);
            $value = $emogrifier->emogrify();

            $this->set(modMail::MAIL_BODY_TEXT, $this->mailer->html2text(nl2br($value)));
        }
        parent::set($key, $value);
    }


    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function send(array $attributes = [])
    {
        //$this->set(modMail::MAIL_ENCODING, 'base64');

        return parent:: send($attributes);
    }
}