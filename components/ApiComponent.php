<?php

namespace app\components;

use Yii;
use DateTime;

use yii\base\BaseObject;
use yii\base\Exception;

use yii\helpers\Json;

use app\components\anticaptcha\ImageToText;

/**
 * Class ApiComponent
 * @package app\components
 */
class ApiComponent extends BaseObject
{
    const MAIN_URL = 'http://kgd.gov.kz/apps/services/culs-taxarrear-search-web/rest/search';

    const CAPTCHA_URL = 'http://kgd.gov.kz/apps/services/CaptchaWeb/generate?';

    const API_KEY = '66f5fa58da14ec11bf8082004e0a82e4';

    /** @var string */
    protected $iin;

    protected $uuid;

    /**
     * ApiComponent constructor.
     *
     * @param string $iin
     * @param array  $config
     */
    public function __construct(string $iin, $config = [])
    {
        $this->iin = $iin;

        parent::__construct($config);
    }

    public function send()
    {
        $this->uuid = $this->generateUUID();
        $t = $this->generateUUID();

        $api = new ImageToText;
        $api->setKey(self::API_KEY);

        $captchaUrl = self::CAPTCHA_URL . http_build_query([
                'uid' => $this->uuid,
                't'   => $t,
            ]);

        $file = file_get_contents($captchaUrl);
        $captchaFile = Yii::getAlias('@upload') . DIRECTORY_SEPARATOR . 'captcha';
        file_put_contents($captchaFile, $file);

        $api->setFile($captchaFile);

        if (!$api->createTask()) {
            $api->debout('API v2 send failed - ' . $api->getErrorMessage(), 'red');

            throw new Exception('Ошибка проверки каптчи');
        }

        $taskId = $api->getTaskId();

        if (!$api->waitForResult()) {
            $api->debout("could not solve captcha", "red");
            $api->debout($api->getErrorMessage());

            throw new Exception('Ошибка проверки каптчи');
        } else {
            $captchaText = $api->getTaskSolution();
        }

        $answer = $this->request($captchaText);

        if (!empty($answer['captchaError'])) {
            // ToDo
            return var_dump($answer['captchaError']);
        }

        return '<pre>' . print_r($answer, true) . '</pre>';
    }

    /**
     * @return string
     */
    private function generateUUID(): string
    {
        $date = (int)(new DateTime())->format('Uu');

        return preg_replace_callback('/[xy]/i', function ($matches) use (&$date) {
            $r = round(($date + rand(0, 16)) % 16, 0, PHP_ROUND_HALF_DOWN);
            $date = floor($date / 16);

            return ($matches[0] == 'x' ? $r : base_convert(($r & 0x3 | 0x8), 10, 16));
        }, 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx');
    }

    /**
     * @param string $captchaText
     *
     * @return array
     * @throws Exception
     */
    private function request(string $captchaText)
    {
        $ch = curl_init();

        $postData = [
            'captcha-id'         => $this->uuid,
            'iinBin'             => $this->iin,
            'captcha-user-value' => $captchaText,
        ];

        curl_setopt($ch, CURLOPT_URL, self::MAIN_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

        $postDataEncoded = Json::encode($postData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataEncoded);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Content-Length: ' . strlen($postDataEncoded),
        ]);

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        $result = curl_exec($ch);
        $curlError = curl_error($ch);

        if ($curlError != "") {
            throw new Exception("Curl error: $curlError");
        }

        curl_close($ch);

        return Json::decode($result);
    }
}