<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private $mailer;
    private $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/mail.php';
        $this->mailer = new PHPMailer(true);

        $this->setupMailer();
    }

    private function setupMailer()
    {
        try {
            $this->mailer->SMTPDebug = 0; // 0 = off, 1 = client messages, 2 = client and server messages
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['smtp']['host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['smtp']['username'];
            $this->mailer->Password = $this->config['smtp']['password'];
            $this->mailer->SMTPSecure = $this->config['smtp']['encryption'];
            $this->mailer->Port = $this->config['smtp']['port'];
            
            // Options SSL supplémentaires
            $this->mailer->SMTPOptions = $this->config['smtp']['smtp_options'];
            
            $this->mailer->setFrom(
                $this->config['smtp']['from_email'],
                $this->config['smtp']['from_name']
            );
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
        } catch (Exception $e) {
            throw new Exception('Erreur de configuration mail : ' . $e->getMessage());
        }
    }

    public function sendPasswordReset($email, $resetLink)
    {
        try {
            $this->mailer->addAddress($email);
            $this->mailer->Subject = 'Réinitialisation de votre mot de passe';
            
            // Template HTML pour l'email
            $this->mailer->Body = "
                <div style='padding: 20px; background: #f9f9f9;'>
                    <h2 style='color: #333;'>Réinitialisation de mot de passe</h2>
                    <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
                    <p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
                    <p>
                        <a href='{$resetLink}' 
                           style='background: #ff6b6b; color: white; padding: 10px 20px; 
                                  text-decoration: none; border-radius: 5px; display: inline-block;'>
                            Réinitialiser mon mot de passe
                        </a>
                    </p>
                    <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
                    <p>Ce lien expirera dans 1 heure.</p>
                </div>
            ";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            throw new Exception('Erreur d\'envoi d\'email : ' . $e->getMessage());
        }
    }

    public function sendEmailVerification($email, $verificationLink)
    {
        try {
            $this->mailer->addAddress($email);
            $this->mailer->Subject = 'Vérification de votre adresse email';
            
            $this->mailer->Body = "
                <div style='padding: 20px; background: #f9f9f9;'>
                    <h2 style='color: #333;'>Vérification de votre email</h2>
                    <p>Merci de confirmer votre adresse email en cliquant sur le lien ci-dessous :</p>
                    <p>
                        <a href='{$verificationLink}' 
                           style='background: #ff6b6b; color: white; padding: 10px 20px; 
                                  text-decoration: none; border-radius: 5px; display: inline-block;'>
                            Vérifier mon email
                        </a>
                    </p>
                    <p>Si vous n'avez pas créé de compte, ignorez cet email.</p>
                    <p>Ce lien expirera dans 24 heures.</p>
                </div>
            ";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            throw new Exception('Erreur d\'envoi d\'email : ' . $e->getMessage());
        }
    }
}
