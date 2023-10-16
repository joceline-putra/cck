<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library files
require_once APPPATH.'third_party/phpmailer/Exception.php';
require_once APPPATH.'third_party/phpmailer/PHPMailer.php';
require_once APPPATH.'third_party/phpmailer/SMTP.php';

class PHPMailer_Lib {

    public function __construct(){
        $this->_ci =& get_instance();
        $this->_ci->load->config('email');
        $this->_mailer = new PHPMailer(true);        
        log_message('debug', 'PHPMailer class is loaded.');
    }
    public function load(){
        $mail = new PHPMailer(true);
        return $mail;
    }
    public function sendMailSMTP($mail_to = "", $mail_subject = "", $mail_content = ""){
        if(empty($mail_to)){
            return [
                'status' => 0,
                'message' => 'Masukkan email pengirim'
            ];
        }
        if(empty($mail_subject)){
            return [
                'status' => 0,
                'message' => 'Masukkan subject email'
            ];
        }
        if(empty($mail_content)){
            return [
                'status' => 0,
                'message' => 'Masukkan kontent email'
            ];
        }
        
        $smtp_host              = $this->_ci->config->item("smtp_host");
        $smtp_auth              = $this->_ci->config->item("smtp_auth");
        $smtp_user              = $this->_ci->config->item("smtp_user");
        $smtp_pass              = $this->_ci->config->item("smtp_pass");
        $smtp_crypto            = $this->_ci->config->item("smtp_crypto");
        $smtp_port              = $this->_ci->config->item("smtp_port");
        $smtp_timeout           = $this->_ci->config->item("smtp_timeout");
        $mail_set_from          = $this->_ci->config->item("mail_set_from");
        $mail_set_from_alias    = $this->_ci->config->item("mail_set_from_alias");
        $mail_set_reply_to      = $this->_ci->config->item("mail_set_reply_to");
        $protocol               = $this->_ci->config->item("protocol");
        $mailtype               = $this->_ci->config->item("mailtype");
        $charset                = $this->_ci->config->item("charset");
        $wordwrap               = $this->_ci->config->item("wordwrap");

        // SMTP configuration
        $this->_mailer->isSMTP();
        $this->_mailer->Host             = $smtp_host;
        $this->_mailer->SMTPAuth         = $smtp_auth;
        $this->_mailer->Username         = $smtp_user;
        $this->_mailer->Password         = $smtp_pass;
        $this->_mailer->SMTPSecure       = $smtp_crypto;
        $this->_mailer->Port             = $smtp_port;

        // Email From
        $this->_mailer->setFrom($mail_set_from, $mail_set_from_alias);
        $this->_mailer->addReplyTo($mail_set_reply_to, $mail_set_from_alias);

        // Email To
        $this->_mailer->addAddress($mail_to);
        // $this->_mailer->addCC('cc@example.com');
        // $this->_mailer->addBCC('bcc@example.com');

        // Attachments
        // $this->_mailer->addAttachment('/upload/medical/odontogram/amf.png');

        // Content
        $this->_mailer->isHTML(true);
        $this->_mailer->Subject = $mail_subject;
        $this->_mailer->Body = $mail_content;

        // send mail
        if($this->_mailer->send()) {
            return [
                'status' => 1,
                'message' => 'Berhasil mengirim email'
            ];
        } else {
            return [
                'status' => 0,
                'message' => 'Mailer error : ' . $this->_mailer->ErrorInfo
            ];
        }
    }    
}