<?php
// send_mail.php - simple email sender for the contact form
header('Content-Type: application/json; charset=utf-8');

function clean($str){
    // remove control characters and basic header injection vectors
    $str = filter_var($str, FILTER_SANITIZE_STRING);
    $str = preg_replace('/[\r\n]+/', ' ', $str);
    return trim($str);
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['success'=>false, 'error'=>'Método inválido.']);
    exit;
}

$name = isset($_POST['name']) ? clean($_POST['name']) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$phone = isset($_POST['phone']) ? clean($_POST['phone']) : '';
$message = isset($_POST['message']) ? clean($_POST['message']) : '';

// basic validation
if(empty($name) || empty($email) || empty($message)){
    echo json_encode(['success'=>false, 'error'=>'Por favor completa los campos requeridos.']);
    exit;
}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo json_encode(['success'=>false, 'error'=>'Email inválido.']);
    exit;
}

$to = 'gerencia@sinstelec.com'; // destinatario configurado según lo solicitado
$subject = 'Contacto desde sinstelec.com - ' . $name;

$body = "Has recibido un nuevo mensaje desde el formulario de contacto de sinstelec.com:\n\n";
$body .= "Nombre: " . $name . "\n";
$body .= "Email: " . $email . "\n";
$body .= "Teléfono: " . $phone . "\n\n";
$body .= "Mensaje:\n" . $message . "\n";

$headers = 'From: ' . $name . ' <' . $email . '>\r\n' .
           'Reply-To: ' . $email . '\r\n' .
           'X-Mailer: PHP/' . phpversion();

// try sending mail
$sent = @mail($to, $subject, $body, $headers);

if($sent){
    echo json_encode(['success'=>true]);
} else {
    // In many shared hosts, mail() may be disabled. Provide helpful error.
    echo json_encode(['success'=>false, 'error'=>'El servidor no pudo enviar el correo. Verifica la configuración de correo en el hosting.']);
}
?>
