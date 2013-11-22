<?php

namespace Base\Filter;

use Zend_Filter_Interface;
use HTMLPurifier_Config;
use HTMLPurifier;

/**
 * 过滤危险的 html ，防止 xss
 */
class Html implements Zend_Filter_Interface
{

    /**
     *
     * @var HTMLPurifier
     */
    protected $purifier;

    public function filter($dirtyData)
    {
        return $this->getCleanCode($dirtyData);
    }

    public function getPurifier()
    {
        if(empty($this->purifier)) {
            $config = HTMLPurifier_Config::createDefault();
            $realpathFilter = new \Zend_Filter_Realpath();
            $cacheDir = __DIR__.'/../../../runtime/caches/htmlpurifier';
            if(!is_writable($cacheDir)) {
                throw new \Exception("Can't write cache dir.", 500);
            }
            $config->set('Cache.SerializerPath', $cacheDir);
            $this->purifier = new HTMLPurifier($config);
        }
        return $this->purifier;
    }

    private function getCleanCode($dirtyData)
    {
        $cleanData = $dirtyData;

        if(is_array($dirtyData)) {
            foreach ($dirtyData as $key => $value) {
                $cleanData[$key] = $this->getPurifier()->purify($value);
            }
        } else {
            $cleanData = $this->getPurifier()->purify($dirtyData);
        }
        return $cleanData;
    }
}
