<?php if (!class_exists('CaptchaConfiguration')) { return; }

// BotDetect PHP Captcha configuration options

return [
  // Captcha configuration for contact page
  'ContactCaptcha' => [
    'UserInputID' => 'captchaCode',
    'CodeLength' => CaptchaRandomization::GetRandomCodeLength(4, 6),
    'ImageStyle' => ImageStyle::AncientMosaic,
  ],

];