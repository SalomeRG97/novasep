<?php
// ============================================================
//  NOVASEP — Manejador de correo para Trabaja con Nosotros
//  Archivo: api/mail-handler.php   Version: 1.2 (HTML)
// ============================================================

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metodo no permitido.']);
    exit;
}

// ============================================================
//  CONFIGURACION
// ============================================================
define('EMAIL_DESTINO',  'seleccion@novaseguridad.com.co');
define('EMAIL_REMITE',   'noreply@novaseguridad.com.co');
define('NOMBRE_EMPRESA', 'NOVASEP Formulario Web');
define('LOG_FILE',       __DIR__ . '/mail_errors.log');

// ============================================================
//  VALIDAR CAMPOS
// ============================================================
$nombre       = trim($_POST['nombre']        ?? '');
$cedula       = trim($_POST['cedula']        ?? '');
$telefono     = trim($_POST['telefono']      ?? '');
$email        = trim($_POST['email']         ?? '');
$ciudad       = trim($_POST['ciudad']        ?? '');
$cargoInteres = trim($_POST['cargo_interes'] ?? '');

if (!$nombre || !$cedula || !$telefono || !$email || !$ciudad || !$cargoInteres) {
    echo json_encode(['success' => false, 'error' => 'Faltan campos obligatorios.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'El correo del aspirante no es valido.']);
    exit;
}

// ============================================================
//  VALIDAR ARCHIVO ADJUNTO
// ============================================================
if (empty($_FILES['cv_file']) || $_FILES['cv_file']['error'] !== UPLOAD_ERR_OK) {
    $errores = [
        UPLOAD_ERR_INI_SIZE   => 'El archivo supera el limite del servidor.',
        UPLOAD_ERR_FORM_SIZE  => 'El archivo supera el limite del formulario.',
        UPLOAD_ERR_PARTIAL    => 'El archivo se subio de forma incompleta.',
        UPLOAD_ERR_NO_FILE    => 'No se selecciono ningun archivo.',
        UPLOAD_ERR_NO_TMP_DIR => 'Error interno del servidor (tmp).',
        UPLOAD_ERR_CANT_WRITE => 'Error interno del servidor (escritura).',
    ];
    $codigo = $_FILES['cv_file']['error'] ?? UPLOAD_ERR_NO_FILE;
    echo json_encode(['success' => false, 'error' => $errores[$codigo] ?? 'Error al recibir el archivo.']);
    exit;
}

$archivo    = $_FILES['cv_file'];
$nombreArch = basename($archivo['name']);
$tmpPath    = $archivo['tmp_name'];
$tamano     = $archivo['size'];
$extension  = strtolower(pathinfo($nombreArch, PATHINFO_EXTENSION));

if (!in_array($extension, ['pdf', 'doc', 'docx'])) {
    echo json_encode(['success' => false, 'error' => 'Formato no permitido. Solo PDF, DOC o DOCX.']);
    exit;
}
if ($tamano > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'error' => 'El archivo supera el maximo de 5 MB.']);
    exit;
}

$contenidoArch = file_get_contents($tmpPath);
if ($contenidoArch === false) {
    registrar_error("No se pudo leer el archivo: {$nombre} ({$email})");
    echo json_encode(['success' => false, 'error' => 'Error interno al procesar el archivo.']);
    exit;
}
$archivoCodificado = chunk_split(base64_encode($contenidoArch));
$mimeArch = mime_content_type($tmpPath) ?: 'application/octet-stream';
$extMayus = strtoupper($extension);

// ============================================================
//  PLANTILLA HTML DEL CORREO
// ============================================================
function fila(string $etiqueta, string $valor): string {
    $valor = htmlspecialchars($valor);
    return "
        <tr>
          <td style=\"padding:11px 16px;background:#f7f7f7;border-bottom:1px solid #ebebeb;
                      color:#666666;font-size:13px;font-weight:600;width:38%;
                      font-family:Helvetica,Arial,sans-serif;\">{$etiqueta}</td>
          <td style=\"padding:11px 16px;background:#ffffff;border-bottom:1px solid #ebebeb;
                      color:#1a1a1a;font-size:14px;
                      font-family:Helvetica,Arial,sans-serif;\">{$valor}</td>
        </tr>";
}

$fecha = (new DateTime('now', new DateTimeZone('America/Bogota')))->format('d/m/Y \a \l\a\s H:i') . ' (hora Colombia)';
$cargoHtml = htmlspecialchars($cargoInteres);

$htmlCorreo = "<!DOCTYPE html>
<html lang=\"es\">
<head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width,initial-scale=1\"></head>
<body style=\"margin:0;padding:0;background:#f2f2f2;font-family:Helvetica,Arial,sans-serif;\">
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"background:#f2f2f2;padding:40px 16px;\">
  <tr><td align=\"center\">
    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"
           style=\"max-width:580px;background:#ffffff;border-radius:8px;overflow:hidden;
                  box-shadow:0 2px 12px rgba(0,0,0,0.10);\">

      <!-- ENCABEZADO -->
      <tr>
        <td style=\"background:#0a0a0a;padding:24px 36px;\">
          <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
              <td>
                <span style=\"color:#CC0000;font-size:20px;font-weight:800;letter-spacing:3px;\">NOVASEP</span>
                <span style=\"color:rgba(255,255,255,0.5);font-size:12px;margin-left:10px;letter-spacing:1px;\">SELECCION DE PERSONAL</span>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- BANER ROJO -->
      <tr>
        <td style=\"background:#CC0000;padding:22px 36px;\">
          <p style=\"margin:0;color:#ffffff;font-size:18px;font-weight:700;letter-spacing:.3px;\">
            Nueva postulacion recibida
          </p>
          <p style=\"margin:5px 0 0;color:rgba(255,255,255,0.80);font-size:13px;\">
            Recibido el {$fecha} desde novaseguridad.com.co
          </p>
        </td>
      </tr>

      <!-- CUERPO -->
      <tr>
        <td style=\"padding:32px 36px;\">
          <p style=\"margin:0 0 20px;color:#444444;font-size:14px;line-height:1.7;\">
            Se ha recibido una nueva aplicacion al cargo de
            <strong style=\"color:#CC0000;\">{$cargoHtml}</strong>.
            A continuacion encontrara los datos del aspirante y su hoja de vida adjunta.
          </p>

          <!-- TABLA DE DATOS -->
          <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"
                 style=\"border-collapse:collapse;border:1px solid #ebebeb;border-radius:6px;overflow:hidden;\">
            " . fila('Nombre completo', $nombre)
               . fila('Cedula', $cedula)
               . fila('Telefono / WhatsApp', $telefono)
               . fila('Correo electronico', $email)
               . fila('Ciudad de residencia', $ciudad)
               . fila('Cargo de interes', $cargoInteres) . "
          </table>

          <!-- NOTA ADJUNTO -->
          <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:24px;\">
            <tr>
              <td style=\"background:#fff8f8;border-left:4px solid #CC0000;border-radius:0 6px 6px 0;
                          padding:14px 18px;\">
                <p style=\"margin:0;color:#555;font-size:13px;line-height:1.6;\">
                  <strong>&#128206; Hoja de vida adjunta</strong> en formato {$extMayus}
                  — archivo: <em>{$nombreArch}</em>
                </p>
              </td>
            </tr>
          </table>

          <!-- BOTON RESPONDER -->
          <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:28px;\">
            <tr>
              <td align=\"center\">
                <a href=\"mailto:{$email}?subject=Tu%20postulacion%20en%20NOVASEP\"
                   style=\"display:inline-block;background:#CC0000;color:#ffffff;text-decoration:none;
                           padding:12px 32px;border-radius:4px;font-size:14px;font-weight:700;
                           letter-spacing:.5px;\">
                  Responder al aspirante
                </a>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- PIE DE PAGINA -->
      <tr>
        <td style=\"background:#f7f7f7;border-top:1px solid #e8e8e8;padding:18px 36px;\">
          <p style=\"margin:0;color:#aaaaaa;font-size:11px;line-height:1.7;text-align:center;\">
            Mensaje generado automaticamente por el formulario <em>Trabaja con nosotros</em> de
            <a href=\"https://www.novaseguridad.com.co\" style=\"color:#CC0000;text-decoration:none;\">novaseguridad.com.co</a>.<br>
            No responda a este mensaje directamente. Use el correo del aspirante indicado arriba.
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>";

// ============================================================
//  CONSTRUIR CORREO MIME (HTML + adjunto)
// ============================================================
$limite = '----NovaSepBoundary' . md5(uniqid(microtime(), true));
$asunto = '=?UTF-8?B?' . base64_encode("Nueva postulacion: {$nombre} - {$cargoInteres}") . '?=';

$encabezados  = 'From: ' . NOMBRE_EMPRESA . ' <' . EMAIL_REMITE . '>' . "\r\n";
$encabezados .= 'Reply-To: ' . $email . "\r\n";
$encabezados .= 'MIME-Version: 1.0' . "\r\n";
$encabezados .= 'Content-Type: multipart/mixed; boundary="' . $limite . '"' . "\r\n";
$encabezados .= 'X-Mailer: PHP/' . phpversion();

// Parte 1: HTML
$cuerpo  = '--' . $limite . "\r\n";
$cuerpo .= "Content-Type: text/html; charset=UTF-8\r\n";
$cuerpo .= "Content-Transfer-Encoding: base64\r\n\r\n";
$cuerpo .= chunk_split(base64_encode($htmlCorreo));

// Parte 2: Adjunto
$cuerpo .= '--' . $limite . "\r\n";
$cuerpo .= "Content-Type: {$mimeArch}; name=\"{$nombreArch}\"\r\n";
$cuerpo .= "Content-Transfer-Encoding: base64\r\n";
$cuerpo .= "Content-Disposition: attachment; filename=\"{$nombreArch}\"\r\n\r\n";
$cuerpo .= $archivoCodificado . "\r\n";
$cuerpo .= '--' . $limite . '--';

// ============================================================
//  ENVIAR
// ============================================================
$enviado = mail(EMAIL_DESTINO, $asunto, $cuerpo, $encabezados, '-f ' . EMAIL_REMITE);

if ($enviado) {
    echo json_encode(['success' => true]);
} else {
    registrar_error("mail() fallo: {$nombre} ({$email}) cargo={$cargoInteres}");
    echo json_encode([
        'success' => false,
        'error'   => 'No se pudo enviar el correo. Por favor intente de nuevo o contactenos directamente.'
    ]);
}

function registrar_error(string $mensaje): void {
    $linea = date('[Y-m-d H:i:s] ') . $mensaje . PHP_EOL;
    file_put_contents(LOG_FILE, $linea, FILE_APPEND | LOCK_EX);
}
