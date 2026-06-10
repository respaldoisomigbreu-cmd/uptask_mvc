<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;


class Email{

public string $email;
public string $nombre;
public string $token;


    public function __construct(string $email, string $nombre, string $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    
        public function enviarConfirmacion(){
        //crea la instancia
        $email = new PHPMailer();

        //configura el servidor d correeo
        $email->isSMTP();
        $email->Host = $_ENV['EMAIL_HOST'];  //SERVIDOR DE CORREO MAILTRAP
        $email->SMTPAuth = true;
        $email->Port = $_ENV['EMAIL_PORT'];
        $email->Username = $_ENV['EMAIL_USER'];        //SERVIDOR DE CORREO MAILTRAP
        $email->Password = $_ENV['EMAIL_PASS'];        //SERVIDOR DE CORREO MAILTRAP

        //destinatario
        $email->setFrom('cuentas@uptask.com');
        $email->addAddress('cuentas@uptask.com', 'uptask.com');

        //contenido del correo
        $email->isHTML(TRUE);
        $email->CharSet = 'UTF-8';
        $email->Subject ='Confirma tu Cuenta';


        $contenido ='<html>';
        $contenido .="<p>Hola <strong> " . $this->nombre . "</strong> Has Creado tu Cuenta en Uptask, solo debes Confirmarla en el siguiente enlace</p>";
        $contenido .= "<p>Preciona Aquí: <a href=' ". $_ENV['APP_URL'] ." /confirmar?token=" . $this->token ."'>Confirmar la Cuenta</a></p>";
        $contenido .= "<p>Si tu no creaste esta cuenta, Puedes ignorar este mensaje</p>";
        $contenido .='</html>';

        $email->Body = $contenido;

        //enviar Email
        $email->send();
        }
    
        public function enviarInstrucciones(){
        //crea la instancia
        $email = new PHPMailer();

        //configura el servidor d correeo
        $email->isSMTP();
        $email->Host = $_ENV['EMAIL_HOST'];  //SERVIDOR DE CORREO MAILTRAP
        $email->SMTPAuth = true;
        $email->Port = $_ENV['EMAIL_PORT'];
        $email->Username = $_ENV['EMAIL_USER'];        //SERVIDOR DE CORREO MAILTRAP
        $email->Password = $_ENV['EMAIL_PASS'];        //SERVIDOR DE CORREO MAILTRAP

        //destinatario
        $email->setFrom('cuentas@uptask.com');
        $email->addAddress('cuentas@uptask.com', 'uptask.com');

        //contenido del correo
        $email->isHTML(TRUE);
        $email->CharSet = 'UTF-8';
        $email->Subject ='Restablece tu Password';


        $contenido ='<html>';
        $contenido .="<p>Hola <strong> " . $this->nombre . "</strong> Has Olvidado tu Password en Uptask, solo debes restablecer tu password en el siguiente enlace</p>";
        $contenido .= "<p>Preciona Aquí: <a href=' ". $_ENV['APP_URL'] ." /reestablecer?token=" . $this->token ."'>Restablece tu Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste restablecer tu password, Puedes ignorar este mensaje</p>";
        $contenido .='</html>';

        $email->Body = $contenido;

        //enviar Email
        $email->send();

        }

        }