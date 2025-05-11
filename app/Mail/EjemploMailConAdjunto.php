<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EjemploMailConAdjunto extends Mailable
{
    use Queueable, SerializesModels;

    public $mensaje;
    public $filePath;
    public $fileName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mensaje, $filePath = null, $fileName = null)
    {
        $this->mensaje = $mensaje;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /*return $this->view('emails.ejemplo')
                    ->with(['mensaje' => $this->mensaje])
                    ->attach($this->filePath, [
                        'as' => $this->fileName,
                        'mime' => mime_content_type($this->filePath),
                    ]);*/
        $email = $this->view('emails.ejemplo')
            ->with(['mensaje' => $this->mensaje])
            ->subject('Sistema SAGE');

        if ($this->filePath && $this->fileName) {
            $email->attach($this->filePath, [
                'as' => $this->fileName,
                'mime' => mime_content_type($this->filePath),
            ]);
        }

        return $email;
    }
}
