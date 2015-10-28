<?php namespace E1Stuff\LayeredContainer\Test\Fake;

/**
 * Date: 28.10.15
 * Time: 22:51
 * Author: Ivan Voskoboinyk
 * Email: ivan.voskoboinyk@gmail.com
 */
class Helper
{
    public $key;

    /**
     * Helper constructor.
     */
    public function __construct($key)
    {
        $this->key = $key;
    }
}