<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['sendmail_protocol']  = 'sendmail';
$config['sendmail_smtp_host'] = 'smtp.gmail.com';
$config['sendmail_smtp_port'] = 465;
$config['sendmail_smtp_user'] = 'xxxx@xxxxx';
$config['sendmail_smtp_pass'] = 'xxxxxx';
$config['sendmail_mailtype']  = 'html';
$config['sendmail_charset']   = 'iso-8859-1';
$config['sendmail_newline']   = "\r\n";
$config['sendmail_crlf']      = "\r\n";
$config['sendmail_from']      = "xxxxx@xxxxxxxx";

$config['confirmation_subject'] = 'Cuenta de usuario en el sistema Vuldash';
$config['confirmation_message'] = '<html><body>' . 
                            '<h1>Confirme su cuenta de usuario en el sistema Vuldash</h1>' .
                            '<p>Usted ha recibido este email porque se ha registrado como un usuario Vuldash.com.</br>' .
                            'Para confirmar su acceso de la cuenta, por favor activela en el siguiente enlace <a href="@link">Activar</a></p>' .
                            '</body></html>';

$config['forgetpass_subject'] = 'Olvido de contrase&ntilde;a en Vuldash';
$config['forgetpass_message'] = '<html><body>' . 
                            '<h1>Olvido; de contrase&ntilde;a en Vuldash?</h1>' .
                            '<p>Usted ha recibido este email porque ha ingresado en la opci&oacute;n "Olvid&eacute; mi Contrase&ntilde;a" en Vuldash.com.</br>' .
                            'Para reacitvar su cuenta siga el siguiente enlace,  <a href="@link">Activar</a></p>' .
                            '<p>Si Usted no fue quien ingrs&oacute; en dicha opci&oacute;n desestime &eacute;ste mensaje.</p>'.
                            '</body></html>';

$config['google_site_key'] = 'xxxxxxxxxxxxx';
$config['google_secret_key'] = 'xxxxxxxxxxxxxxxx';
