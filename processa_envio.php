<?php

require "Bibliotecas/PHPMailer/PHPMailer.php";
require "Bibliotecas/PHPMailer/Exception.php";
require "Bibliotecas/PHPMailer/POP3.php";
require "Bibliotecas/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Mensagem {

    private $para = null ;
    private $assunto = null ;
    private $mensagem = null ;
    public $status = array('status_conexao' => null , 'descricao' => '') ;

    public function __set($atributo , $valor) {
        $this->$atributo = $valor ;
    }

    public function __get($atributo) {
        return $this->$atributo ;
    }
    
    public function MensagemVálida() {
        if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
            return false ;
        }
        return true ;
    }

}

$mensagem = new Mensagem() ;

$mensagem->__set("para" , $_POST['para'] ) ;
$mensagem->__set("assunto" , $_POST['assunto'] ) ;
$mensagem->__set("mensagem" , $_POST['mensagem'] ) ;


if(!$mensagem->MensagemVálida()) { 
    echo"Mensagem Inválida" ;
    header("Location: index.php?success=false"); 
} 

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                //Enable verbose debug output
    $mail->isSMTP();                                      //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                 //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                             //Enable SMTP authentication
    $mail->Username   = 'testemello00@gmail.com';         //SMTP username
    $mail->Password   = 'qein ayzo wnmu mryn';            //SMTP password
    $mail->SMTPSecure = "tls";                            //Enable implicit TLS encryption
    $mail->Port       = 587;                              //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('testemello00@gmail.com' , 'AppSendEmail'); //
    $mail->addAddress($mensagem->__get('para'));                           //Add a recipient
    //$mail->addAddress('ellen@example.com');                  //Name is optional
    $mail->addReplyTo('testemello00@gmail.com', 'AppSendEmail');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                    //Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto'); 
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'Utilize um servidor SMTP , com client q suporte o html para exibir o conteudo do email .';

    $mail->send();

    $mensagem->status['status_conexao'] = 1;
    $mensagem->status['descricao'] = "Mensagem enviada com sucesso !";

    header("Location: index.php?success=true"); 
    
} catch (Exception $e) {
    
    $mensagem->status['status_conexao'] = 2;
    $mensagem->status['descricao'] = "Não foi possível enviar a mensagem. Detalhes do erro: {$mail->ErrorInfo}";
    echo $mensagem->status['descricao'] ;

}

?>

