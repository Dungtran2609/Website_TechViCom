<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $contentHtml;

    public function __construct($subjectText, $contentHtml)
    {
        $this->subjectText = $subjectText;
        $this->contentHtml = $contentHtml;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
            ->html($this->contentHtml);
    }
}
