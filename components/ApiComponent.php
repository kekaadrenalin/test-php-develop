<?php

namespace app\components;

use yii\base\BaseObject;

class ApiComponent extends BaseObject
{
    /** @var string */
    protected $iin;

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
}