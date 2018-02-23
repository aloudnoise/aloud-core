<?php
namespace app\helpers\bigbluebutton;

class CreateMeetingParameters extends \BigBlueButton\Parameters\CreateMeetingParameters
{

    private $presentations = [];

    /**
     * @return array
     */
    public function getPresentations()
    {
        return $this->presentations;
    }

    /**
     * @param $nameOrUrl
     * @param null $content
     *
     * @return CreateMeetingParameters
     */
    public function addPresentation($nameOrUrl, $content = null)
    {
        $this->presentations[$nameOrUrl] = !$content ?: base64_encode($content);

        return $this;
    }

    public function getPresentationsAsXML()
    {
        $result = '';

        if (!empty($this->presentations)) {
            $xml    = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><modules/>');
            $module = $xml->addChild('module');
            $module->addAttribute('name', 'presentation');

            foreach ($this->presentations as $nameOrUrl => $content) {
                if ($this->presentations[$nameOrUrl] === true) {
                    $module->addChild('document')->addAttribute('url', $nameOrUrl);
                } else {
                    $document = $module->addChild('document');
                    $document->addAttribute('name', $nameOrUrl);
                    $document[0] = $content;
                }
            }
            $result = $xml->asXML();
        }

        return $result;
    }


}