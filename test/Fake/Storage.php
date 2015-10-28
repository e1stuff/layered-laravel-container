<?php namespace E1Stuff\LayeredContainer\Test\Fake;

/**
 * Date: 28.10.15
 * Time: 22:52
 * Author: Ivan Voskoboinyk
 * Email: ivan.voskoboinyk@gmail.com
 */
class Storage
{
    private $id;

    /**
     * Storage constructor.
     * @param Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->id = $helper;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}