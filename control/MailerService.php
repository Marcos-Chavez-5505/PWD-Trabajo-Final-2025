<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
    require $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/util/vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use Dotenv\Dotenv;

class MailerService{
    private PHPMailer $mail;

    public function __construct(){
        $dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/');
        $dotenv->safeLoad();

        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['SMTP_HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Port = $_ENV['SMTP_PORT'];
        $this->mail->Username = $_ENV['SMTP_USER'];
        $this->mail->Password = $_ENV['SMTP_PASS'];
    }

    /**
     * Recibe el ID de la compra y el estado (numérico)
     * Genera el correo, genera un PDF y lo adjunta
     * 
     * @param string $emailDestino
     * @param int $idCompra
     * @param string $cetDescripcion
     * @param float $montoTotal
     * @return bool
     */
    public function enviarCambioEstado($emailDestino, $idCompra, $proximoEstado){

        switch ($proximoEstado){
            case 1:
                $estado = 'Iniciada';
                break;
            case 2:
                $estado = 'Aceptada';
                break;
            case 3:
                $estado = 'Enviada';
                break;
            case 4:
                $estado = 'Cancelada';
                break;
        }

        try {
            $this->mail->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_FROM_NAME']);
            $this->mail->addAddress($emailDestino);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Actualización del estado de su compra';
            $this->mail->Body = '
                Su compra ha cambiado de estado.<br>
                <b>Estado actual:</b> ' . $estado . '<br><br>
                Adjuntamos la factura en PDF.
            ';

            // crear PDF
            $pdf = new ControlPDF();
            $rutaPDF = $pdf->generarPdf($idCompra, $estado); 
            $this->mail->addAttachment($rutaPDF, 'factura_' . $idCompra . '.pdf');


            return $this->mail->send();

        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $e->getMessage());
            return false;
        }
    }

    public function generarMail($idCompra, $proximoEstado){
        $rta = false;
        $email = '';

        $array = $this->resumenCompra($idCompra);
        $email = $array['email'];

        if ($email !== ''){
            $this->enviarCambioEstado($email, $idCompra, $proximoEstado);
            $rta = true;
        }
        return $rta;
    }

    public function resumenCompra($idCompra){
        $arrayResumen = [
            'email' => '',
            'productos' => [], // valores no usados
            'monto_final' => 0
        ];

        $compra = new Compra();
        $compra->buscar($idCompra);
        $usuarioMail = $compra->getObjUsuario()->getUsmail();

        if ($usuarioMail){
            $arrayResumen['email'] = $usuarioMail;
        }

        return $arrayResumen;
    }
}
