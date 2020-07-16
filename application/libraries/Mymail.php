<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH.'libraries/swiftmailer/swift_required.php';

class Mymail
{
    public function send($subject, $to = array(), $content, $attach = false)
    {
        if (env('CI_ENV') == 'production' || env('MAIL_HOST') == 'smtp.mailtrap.io') {
            $spool = new Swift_FileSpool(FCPATH.'tmp/spool');
            $transport = Swift_SpoolTransport::newInstance($spool);
            $mailer = Swift_Mailer::newInstance($transport);
            $message = Swift_Message::newInstance();
            $message->setSubject($subject);
            $message->setFrom(array(env('MAIL_FROM') => 'Buku Sekolah PT. Mitra Edukasi Nusantara'));
            $message->setTo($to);
            // $message->setBcc(array('bukusekolah.gramedia@gmail.com'));
            $message->setBcc(array('bs.mitraedu@gmail.com'));
            $message->setBody($content.'<br /><hr /><p style="font-size:9px;">Penting untuk diketahui !!!<br />Email ini dikirim secara otomatis.<br />Tidak perlu membalas ke alamat email ini<br /></p>',
                'text/html');
            if ($attach) {
                $message->attach(Swift_Attachment::fromPath($attach));
            }
            $mailer->send($message);
        } else {
            return;
        }
    }

    public function processSpool()
    {
        $spool = new Swift_FileSpool(FCPATH.'tmp/spool');
        $spool_transport = Swift_SpoolTransport::newInstance($spool);
        $smtp_transport = Swift_SmtpTransport::newInstance(env('MAIL_HOST'), env('MAIL_PORT'),
            env('MAIL_SECURITY'))->setUsername(env('MAIL_USER'))->setPassword(env('MAIL_PASSWORD'));
        $spool = $spool_transport->getSpool();
        $spool->flushQueue($smtp_transport);
    }

    public function sendBlast($subject, $to = array(), $content, $attach = false)
    {
        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 587,
            'tls')->setUsername('bs.mitraedu@gmail.com')->setPassword('Selalu0k3');
        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setFrom(array('bs.mitraedu@gmail.com' => 'Buku Sekolah PT. Mitra Edukasi Nusantara'));
        $message->setTo($to);
        $message->setBcc(array('bukusekolah.gramedia@gmail.com'));
        $message->setBody($content.'<br /><hr /><p style="font-size:9px;">Penting untuk diketahui !!!<br />Email ini dikirim secara otomatis.<br />Tidak perlu membalas ke alamat email ini<br /></p>',
            'text/html');
        if ($attach) {
            $message->attach(Swift_Attachment::fromPath($attach));
        }
        $mailer->send($message);
    }
}
