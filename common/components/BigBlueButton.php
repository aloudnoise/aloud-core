<?php
namespace common\components;

use app\helpers\Url;
use BigBlueButton\Util\UrlBuilder;

class BigBlueButton extends \BigBlueButton\BigBlueButton
{

    public function __construct()
    {
        $this->securitySalt     = \Yii::$app->params['bigbluebutton']['secret'];
        $this->bbbServerBaseUrl = \Yii::$app->params['bigbluebutton']['url'];
        $this->urlBuilder       = new UrlBuilder($this->securitySalt, $this->bbbServerBaseUrl);
    }

    public function createHook()
    {
        $url = $this->urlBuilder->buildUrl("hooks/create", 'callbackURL='.\Yii::$app->params['api_url'].'bbb');
        return $this->processXmlResponse($url);
    }

    /**
     * A private utility method used by other public methods to process XML responses.
     *
     * @param  string            $url
     * @param  string            $payload
     * @return \SimpleXMLElement
     * @throws \RuntimeException
     */
    private function processXmlResponse($url, $payload = '', $contentType = 'application/xml')
    {
        if (extension_loaded('curl')) {
            $ch = curl_init();
            if (!$ch) {
                throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
            }
            $timeout = 10;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            if ($payload != '') {
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-type: ' . $contentType,
                    'Content-length: ' . strlen($payload),
                ]);
            }
            $data = curl_exec($ch);
            if ($data === false) {
                throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
            }
            curl_close($ch);

            return new \SimpleXMLElement($data);
        }

        if ($payload != '') {
            throw new \RuntimeException('Post XML data set but curl PHP module is not installed or not enabled.');
        }

        try {
            $response = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);

            return new \SimpleXMLElement($response);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException('Failover curl error: ' . $e->getMessage());
        }
    }


}