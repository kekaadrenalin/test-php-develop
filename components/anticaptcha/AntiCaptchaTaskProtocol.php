<?php

namespace app\components\anticaptcha;

/**
 * Interface AntiCaptchaTaskProtocol
 * @package app\components\anticaptcha
 */
interface AntiCaptchaTaskProtocol
{
    /**
     * @return array
     */
    public function getPostData();

    /**
     * @return mixed
     */
    public function getTaskSolution();
}