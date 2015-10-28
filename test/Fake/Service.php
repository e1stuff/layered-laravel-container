<?php namespace E1Stuff\LayeredContainer\Test\Fake;

/**
 * Date: 28.10.15
 * Time: 22:51
 * Author: Ivan Voskoboinyk
 * Email: ivan.voskoboinyk@gmail.com
 */
class Service
{
    /** @var Helper */
    public $helper;
    /** @var Storage */
    public $storage;

    /**
     * Service constructor.
     * @param Storage $storage
     * @param Helper $helper
     */
    public function __construct(Storage $storage, Helper $helper)
    {
        $this->helper = $helper;
        $this->storage = $storage;
    }
}