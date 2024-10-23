<?php

namespace App\Mail;

use App\Models\EmailContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class ThemeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data, $type, $template,$subject;

    public function __construct($data, $type,$subject=NULL)
    {
        $this->data = $data;
        $this->type = $type;
        $this->subject = $subject;
        $this->template = EmailContent::where('email_type', $type)->first();
    }
    public function build()
    {
        if($this->subject){
            $this->subject($this->subject);
        }
        // Sử dụng nội dung HTML đã lưu trong $this->template->content
        $content = $this->template->content;

        // Thay thế các tag
        $content = $this->replaceTags($content);

        // Đọc CSS từ các tệp
        $bootstrapCss = file_get_contents(public_path('assets/builderjs/templates/default/css/bootstrap.min.css'));
        $customCss = file_get_contents(public_path('assets/builderjs/templates/default/css/custom.css'));

        // Kết hợp CSS
        $css = $bootstrapCss . "\n" . $customCss;

        // Chuyển đổi CSS thành inline
        $cssToInlineStyles = new CssToInlineStyles();
        $contentWithInlineCss = $cssToInlineStyles->convert($content, $css);

        // Trả về email với nội dung đã được inline
        return $this->html($contentWithInlineCss);
    }
    protected function replaceTags($content)
    {
        $replaceTags = [
            '{WEB_NAME}' => config('app.name') ?? NULL,
            '{URL_FORGOTPASSWORD}' => $this->data['resetLink'] ?? NULL,
            '{CONTACT_NAME}' => $this->data['name'] ?? NULL,
            '{CONTACT_EMAIL}' => $this->data['email'] ?? NULL,
            '{CONTACT_PHONE}' => $this->data['phone'] ?? NULL,
            '{URL_VERIFY}' => $this->data['veirfyLink'] ?? NULL,
            '{URL_INVOICE}' => $this->data['url_invoice'] ?? NULL,
            '{ID_INVOICE}' => $this->data['id_invoice'] ?? NULL,
            '{CREATE_AT_INVOICE}' => $this->data['created_at_invoice'] ?? NULL,
            '{PAYMENT_METHOD_INVOICE}' => $this->data['payment_method_invoice'] ?? NULL,
            '{ORDER_STATUS}' => $this->data['order_status_invoice'] ?? NULL,
            '{PRICE_INVOICE}' => $this->data['price_invoice'] ?? NULL,
            '{SUBSCRIBER_NAME}'  => $this->data['name'] ?? NULL,
            '{SUBSCRIBER_EMAIL}' => $this->data['email'] ?? NULL,
        ];
        return str_replace(array_keys($replaceTags), array_values($replaceTags), $content);
    }
}
