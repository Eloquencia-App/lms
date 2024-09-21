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

    public function getNameFirstname($token): string
    {
        include 'config.php';
        $req = $db->prepare('SELECT user_id FROM tokens WHERE token = :token');
        $req->execute(array(
            'token' => $token
        ));
        $req = $req->fetch();
        $req2 = $db->prepare('SELECT name, firstname FROM members WHERE ID = :id');
        $req2->execute(array(
            'id' => $req['user_id']
        ));
        $req2 = $req2->fetch();
        return $req2['name'] . ' ' . $req2['firstname'];
    }

    public function getNextLesson($token): array
    {
        include 'config.php';
        $req = $db->prepare('SELECT lessons_history FROM members WHERE ID = (SELECT user_id FROM tokens WHERE token = :token)');
        $req->execute(array(
            'token' => $token
        ));
        $req = $req->fetch();
        if ($req['lessons_history'] == null) {
            $req['lessons_history'] = '{}';
        }
        $json = json_decode($req['lessons_history'], true);
        $lessonIds = empty($json) ? '0' : implode(',', array_keys($json));

        $req2 = $db->prepare('SELECT ID, title, summary FROM lessons WHERE ID NOT IN (' . $lessonIds . ') ORDER BY ID ASC LIMIT 1');
        $req2->execute();
        $res = $req2->fetch();
        if ($res == null) {
            return array('ID' => 0, 'title' => 'Félicitations !', 'summary' => 'Vous avez terminé toutes les leçons disponibles.');
        } else {
            return $res;
        }
    }

    public function setLessonHistory($token, $lesson_id): void
    {
        include 'config.php';
        $req = $db->prepare('SELECT lessons_history FROM members WHERE ID = (SELECT user_id FROM tokens WHERE token = :token)');
        $req->execute(array(
            'token' => $token
        ));
        $req = $req->fetch();
        if ($req['lessons_history'] == null) {
            $req['lessons_history'] = '{}';
        }
        $json = json_decode($req['lessons_history'], true);

        if (!array_key_exists($lesson_id, $json)) {
            $json[$lesson_id] = 1;
        }

        $req2 = $db->prepare('UPDATE members SET lessons_history = :lessons_history WHERE ID = (SELECT user_id FROM tokens WHERE token = :token)');
        $req2->execute(array(
            'lessons_history' => json_encode($json),
            'token' => $token
        ));
    }

    public function getAnnouncement(): array
    {
        include 'config.php';
        $req = $db->prepare('SELECT value, state FROM settings WHERE name = "announcement_lms"');
        $req->execute();
        $result = $req->fetch();
        $result['value'] = json_decode($result['value']);
        return $result;
    }
}