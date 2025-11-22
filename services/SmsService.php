<?php

namespace app\services;

use Yii;
use yii\base\Component;

class SmsService extends Component
{
    public $apiKey;
    public $testMode = true;

    public function init()
    {
        parent::init();
        if ($this->testMode) {
            $this->apiKey = 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ';
        }
    }

    public function sendSms($phone, $message)
    {
        if ($this->testMode) {
            // В тестовом режиме логируем вместо реальной отправки
            Yii::info("SMS to {$phone}: {$message}", 'sms');
            return true;
        }

        $url = 'http://smspilot.ru/api.php';
        
        $params = [
            'send' => $message,
            'to' => $this->formatPhone($phone),
            'apikey' => $this->apiKey,
            'format' => 'json'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        curl_close($ch);

        Yii::info("SMS response: {$response}", 'sms');
        
        return $response !== false;
    }

    private function formatPhone($phone)
    {
        // Убираем все нецифровые символы
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Если номер начинается с 8, заменяем на 7
        if (strlen($phone) == 11 && $phone[0] == '8') {
            $phone = '7' . substr($phone, 1);
        }
        
        return $phone;
    }
}