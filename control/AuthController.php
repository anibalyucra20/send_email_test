<?php
require_once("../model/AuthModel.php");
require_once __DIR__ . '/../library/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../library/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../library/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json; charset=utf-8');

$obj = new AuthModel();
$tipo = $_GET['tipo'] ?? '';

function enviarCodigo($correo, $nombre, $codigo)
{
    // CARGA PHPMailer (elige el método A o B de arriba)
    $mail = new PHPMailer(true);

    // Config exacta que pasaste
    $SMTP_HOST = 'mail.iestphuanta.edu.pe';
    $SMTP_USER = 'servermail@iestphuanta.edu.pe';
    $SMTP_PASS = 'wM{gHfoxSMFfq@%q';
    $FROM_EMAIL = 'servermail@iestphuanta.edu.pe';
    $FROM_NAME  = 'Soporte';

    // Debug a error_log (0 en producción)
    $mail->SMTPDebug   = 2;
    $mail->Debugoutput = function ($str, $level) {
        error_log("PHPMailer: $str");
    };

    try {
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($FROM_EMAIL, $FROM_NAME);
        $mail->addAddress($correo, $nombre);
        $mail->isHTML(true);
        $mail->Subject = 'Tu código de verificación';
        $mail->Body    = "
            <p>Hola <strong>{$nombre}</strong>,</p>
            <p>Tu código de verificación es:</p>
            <h2 style='letter-spacing:4px;'>{$codigo}</h2>
            <p>Este código vence en 30 minutos.</p>
        ";
        $mail->AltBody = "Código de verificación: {$codigo} (vence en 30 minutos)";

        // SMTP 465 SSL (SMTPS)
        $mail->isSMTP();
        $mail->Host       = $SMTP_HOST; 
        $mail->SMTPAuth   = true;
        $mail->Username   = $SMTP_USER;
        $mail->Password   = $SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // <— importante
        $mail->Port       = 465;
        $mail->Timeout    = 20;
        $mail->SMTPKeepAlive = false;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('PHPMailer Exception: ' . $e->getMessage());
        error_log('PHPMailer ErrorInfo: ' . $mail->ErrorInfo);
        return false;
    }
}




// SIGNUP: registra y envía código
if ($tipo === 'signup') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $pass = $_POST['contrasena'] ?? '';

    if ($nombre === '' || $correo === '' || $pass === '') {
        echo json_encode(['status' => false, 'msg' => 'Error, campos vacíos']);
        exit;
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => false, 'msg' => 'Correo no válido']);
        exit;
    }
    if (strlen($pass) < 8) {
        echo json_encode(['status' => false, 'msg' => 'La contraseña debe tener mínimo 8 caracteres']);
        exit;
    }

    if ($obj->existeCorreo($correo) > 0) {
        echo json_encode(['status' => false, 'msg' => 'El correo ya está registrado']);
        exit;
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $codigo = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $vence = date('Y-m-d H:i:s', strtotime('+30 minutes'));
    $id = $obj->registrar($nombre, $correo, $hash, $codigo, 0, $vence);
    if ($id <= 0) {
        echo json_encode(['status' => false, 'msg' => 'No se pudo registrar']);
        exit;
    }

    if (!enviarCodigo($correo, $nombre, $codigo)) {
        echo json_encode(['status' => false, 'msg' => 'No se pudo enviar el correo de verificación']);
        exit;
    }
    echo json_encode(['status' => true, 'msg' => 'Registro exitoso']);
    exit;
}

// VERIFICAR CÓDIGO
if ($tipo === 'verificar_codigo') {
    $correo = trim($_POST['correo'] ?? '');
    $codigo = trim($_POST['codigo'] ?? '');

    if ($correo === '' || $codigo === '') {
        echo json_encode(['status' => false, 'msg' => 'Completa correo y código']);
        exit;
    }

    $u = $obj->getPorCorreo($correo);
    if (!$u) {
        echo json_encode(['status' => false, 'msg' => 'Usuario no encontrado']);
        exit;
    }

    if ((int)$u->estado === 1) {
        echo json_encode(['status' => true, 'msg' => 'La cuenta ya está verificada']);
        exit;
    }

    if ($u->codigo !== $codigo) {
        echo json_encode(['status' => false, 'msg' => 'Código incorrecto']);
        exit;
    }

    if (strtotime($u->fecha_vencimiento) < time()) {
        echo json_encode(['status' => false, 'msg' => 'El código ha expirado. Reenviamos uno nuevo desde el botón.']);
        exit;
    }

    if ($obj->activar($u->id)) {
        echo json_encode(['status' => true, 'msg' => 'Cuenta activada']);
        exit;
    } else {
        echo json_encode(['status' => false, 'msg' => 'No se pudo activar']);
        exit;
    }
}

// REENVIAR CÓDIGO
if ($tipo === 'reenviar_codigo') {
    $correo = trim($_POST['correo'] ?? '');
    if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => false, 'msg' => 'Correo inválido']);
        exit;
    }

    $u = $obj->getPorCorreo($correo);
    if (!$u) {
        echo json_encode(['status' => false, 'msg' => 'Usuario no encontrado']);
        exit;
    }
    if ((int)$u->estado === 1) {
        echo json_encode(['status' => false, 'msg' => 'Tu cuenta ya está verificada']);
        exit;
    }

    $codigo = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $vence = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    if (!$obj->actualizarCodigo($u->id, $codigo, $vence)) {
        echo json_encode(['status' => false, 'msg' => 'No se pudo generar nuevo código']);
        exit;
    }

    if (!enviarCodigo($u->correo, $u->nombre, $codigo)) {
        echo json_encode(['status' => false, 'msg' => 'No se pudo enviar el correo']);
        exit;
    }

    echo json_encode(['status' => true, 'msg' => 'Se envió un nuevo código a tu correo']);
    exit;
}

// LOGIN (requiere estado=1)
if ($tipo === 'login') {
    $correo = trim($_POST['correo'] ?? '');
    $pass = $_POST['contrasena'] ?? '';

    if ($correo === '' || $pass === '') {
        echo json_encode(['status' => false, 'msg' => 'Error, campos vacíos']);
        exit;
    }

    $u = $obj->getPorCorreo($correo);
    if (!$u) {
        echo json_encode(['status' => false, 'msg' => 'Usuario no encontrado']);
        exit;
    }

    if ((int)$u->estado !== 1) {
        echo json_encode(['status' => false, 'msg' => 'Debes verificar tu correo antes de iniciar sesión']);
        exit;
    }

    if (!password_verify($pass, $u->password)) {
        echo json_encode(['status' => false, 'msg' => 'Contraseña incorrecta']);
        exit;
    }

    session_start();
    $_SESSION['sis_email_id'] = $u->id;
    $_SESSION['sis_email_id_usuario'] = $u->nombre;
    echo json_encode(['status' => true, 'msg' => 'ok']);
    exit;
}

echo json_encode(['status' => false, 'msg' => 'Tipo no soportado']);
