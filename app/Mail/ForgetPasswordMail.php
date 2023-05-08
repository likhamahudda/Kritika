<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailval;
    public $subject;
    public $pdf_output;
    public $pdf_name;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emailval,$subject,$pdf_output='',$pdf_name='')
    {
        $this->emailval = $emailval;
        $this->subject = $subject;
        $this->pdf_output = $pdf_output;
        $this->pdf_name = $pdf_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $send = $this->subject($this->subject)
            ->html($this->emailval);
        if(!empty($this->pdf_name)){
            $attachments = explode(",",$this->pdf_output);
            foreach($attachments as $attachment){
                // get file name from file path
                $myFile = pathinfo($attachment);
                $file_name = $myFile['basename'];
                $send = $this->attach($attachment, array(
                    'as' => $file_name, 
                    'mime' => 'application/pdf')
                );
            }
            //$send = $this->attachData(base64_decode($this->pdf_output), $this->pdf_name);
        }
        return $send;
    }
}