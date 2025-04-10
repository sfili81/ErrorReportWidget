<?php

namespace sfili81\ErrorReportWidget;

use Yii;
use yii\base\Widget;
//use sfili81\JsErrorMailHandler;
use frontend\components\JsErrorMailHandler\JsErrorMailHandlerAssets;

/**
 * JsErrorMailHandler widget send an email when encounter a Javascript Error
 * on browser console.
 *
 *
 * 
 *
 */
class ErrorReportWidget extends Widget
{
    private $subject = 'Javascript error in browser console';
    private $mailTo;//set here your email if you don't use params
    private $setFrom;//set where the error comes from
    public function run(){
        JsErrorMailHandlerAssets::register($this->view);

        $this->mailTo = Yii::$app->params['supportEmail'];//set here your email if you don't use params
        $this->setFrom = Yii::$app->name;//set where the error comes from

        // Gestisci la richiesta AJAX (invio email)
        $this->handleAjaxRequest();
    }

    protected function handleAjaxRequest()
    {
        // Controlliamo se c'è una richiesta AJAX per l'invio dell'errore
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            // PREVIENE OUTPUT HTML
            ob_clean(); 

            $request = Yii::$app->request;
            // Recupera i dati inviati tramite FormData
            $message = $request->post('message');
            $source = $request->post('source');
            $lineno = $request->post('lineno');
            $colno  = $request->post('colno');
            $error  = $request->post('error');

            // Crea un identificatore unico per l'errore
            $errorId = $message . $source . $lineno . $colno;

             // Usa la cache di Yii2 per evitare invii duplicati
            $cache = Yii::$app->cache;

            $sessionKey = 'error_' . md5($errorId);  // Chiave unica per l'errore

            //dd($cache->get($sessionKey));
             // Se l'errore è già stato memorizzato nella cache, non inviarlo di nuovo
            if ($cache->get($sessionKey)) {
                // Imposta codice 204 No Content (significa: "richiesta ok, ma nessun contenuto")
                Yii::$app->response->statusCode = 204;

                Yii::$app->response->send();
                Yii::$app->end();
            }

            // Memorizza l'errore nella cache (imposta una scadenza di 5 minuti)
            $cache->set($sessionKey, true, 300);

            // Crea il corpo dell'email con tutte le informazioni dell'errore
            $body = "Error JS:\n\n";
            $body .= "Message: $message\n";
            $body .= "File: $source\n";
            $body .= "Line: $lineno:$colno\n";
            $body .= "Stack: $error";

            // Invia l'email (oppure log)
            $this->sendEmail($body);

            // Imposta codice 204 No Content (significa: "richiesta ok, ma nessun contenuto")
            Yii::$app->response->statusCode = 204;

            Yii::$app->response->send();
            Yii::$app->end();
                }
    }

    protected function sendEmail($body): bool
    {
        return Yii::$app->mailer->compose()
            ->setTo(to: $this->mailTo)
            ->setFrom([ $this->mailTo => $this->setFrom ])
            ->setSubject($this->subject)
            ->setTextBody($body)
            ->send();
    }



}