<?php

/* dados recebidos

    $data = array(
        //
    );
*/

$name       = $data["name"];
$mail       = $data["mail"];
$subject    = $data["subject"];
$message    = $data["message"];
$content    = "Contato pelo site Open System: <br>
    Nome: $name <br>
    E-mail: $mail <br>
    Mensagem: $message";

// VALIDAÇÃO
$validation = true;
if ($name == "" || $mail == "" || $message == "") {
    $validation = false;
    $erro = "Por favor, preencha corretamente os campos." . $name . "//". $mail . "//". $subject . "//". $message;
}

// VALIDAR EMAIL
//...

//execucao
if ($validation == true) {
    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;
    //Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';
    //Set the hostname of the mail server
    $mail->Host = 'mail.opensystem-ce.com.br';
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;
    
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';
    
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;

    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "web@opensystem-ce.com.br";
    //Password to use for SMTP authentication
    $mail->Password = "opensystem@2015";
    //Set who the message is to be sent from
    $mail->setFrom('suporte@opensystem-ce.com.br', 'Contato Open System');
    //Set an alternative reply-to address
    $mail->addReplyTo('suporte@opensystem-ce.com.br', 'Daniel Rodrigues');
    //Set who the message is to be sent to
    $mail->addAddress('suporte@opensystem-ce.com.br', 'Suporte Open System');
    //Set the subject line
    $mail->Subject = $subject;

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
    $mail->msgHTML( $content );
    
    //Replace the plain text body with one created manually
    $mail->Body = $content;
    $mail->AltBody = $content;
    
    //Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.png');
    
    //send the message, check for errors
    if (!$mail->send()) {
        $erro = "Mailer Error: " . $mail->ErrorInfo;
        echo '<div class="alert alert-error">' . $erro . '</div>';
    } else {
        $msg = "Mensagem enviada com sucesso";
        echo '<div class="alert alert-success">' . $msg . '</div>';
    }

}else {
    echo '<div class="alert alert-error">' . $erro . '</div>';
    die;
}