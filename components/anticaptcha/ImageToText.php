<?php

namespace app\components\anticaptcha;

/**
 * Class ImageToText
 * @package app\components\anticaptcha
 */
class ImageToText extends Anticaptcha implements AntiCaptchaTaskProtocol
{
    private $body;

    private $phrase = false;

    private $case = false;

    private $numeric = false;

    private $math = 0;

    private $minLength = 0;

    private $maxLength = 0;


    /**
     * @return array
     */
    public function getPostData()
    {
        return [
            'type'      => 'ImageToTextTask',
            'body'      => str_replace("\n", '', $this->body),
            'phrase'    => $this->phrase,
            'case'      => $this->case,
            'numeric'   => $this->numeric,
            'math'      => $this->math,
            'minLength' => $this->minLength,
            'maxLength' => $this->maxLength,
        ];
    }

    /**
     * @return mixed
     */
    public function getTaskSolution()
    {
        return $this->taskInfo->solution->text;
    }

    /**
     * @param $fileName
     *
     * @return bool
     */
    public function setFile($fileName)
    {

        if (file_exists($fileName)) {

            if (filesize($fileName) > 100) {
                $this->body = base64_encode(file_get_contents($fileName));

                return true;
            } else {
                $this->setErrorMessage("file $fileName too small or empty");
            }

        } else {
            $this->setErrorMessage("file $fileName not found");
        }

        return false;

    }

    /**
     * @param $value
     */
    public function setPhraseFlag($value)
    {
        $this->phrase = $value;
    }

    /**
     * @param $value
     */
    public function setCaseFlag($value)
    {
        $this->case = $value;
    }

    /**
     * @param $value
     */
    public function setNumericFlag($value)
    {
        $this->numeric = $value;
    }

    /**
     * @param $value
     */
    public function setMathFlag($value)
    {
        $this->math = $value;
    }

    /**
     * @param $value
     */
    public function setMinLengthFlag($value)
    {
        $this->minLength = $value;
    }

    /**
     * @param $value
     */
    public function setMaxLengthFlag($value)
    {
        $this->maxLength = $value;
    }

}