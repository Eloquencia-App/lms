<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Utils
{
    public function checkCookie($cookie): bool
    {
        include 'config.php';
        if(isset($_COOKIE[$cookie])) {
            $req = $db->prepare('SELECT COUNT(*) FROM tokens WHERE token = :cookie AND expiration > NOW()');
            $req->execute(array(
                'cookie' => $_COOKIE[$cookie]
            ));
            $req = $req->fetch();
            if ($req[0] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function sendRecoveryEmail($email, $token) {
        $mail = new PHPMailer(true);
        include 'config.php';
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = $mailConfig['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $mailConfig['username'];
            $mail->Password = $mailConfig['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $mailConfig['port'];
            $mail->setFrom($mailConfig['username'], 'Eloquéncia');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Récupération de votre mot de passe';
            $mail->Body = 'Bonjour,<br><br>Vous avez demandé la réinitialisation de votre mot de passe. Pour ce faire, veuillez cliquer sur le lien suivant : <a href="https://eloquencia.org/lms/resetpassword.php?reset=' . $token . '">Réinitialiser mon mot de passe</a><br><br>Cordialement,<br>L\'équipe Eloquéncia';
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }


}