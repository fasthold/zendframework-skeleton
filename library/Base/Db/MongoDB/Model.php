<?php
/**
 * This file is part of ZendFramework skeleton.
 */

namespace Base\Db\MongoDB;

use Mawelous\Yamop\Model as ORM;

/**
 * Base Model
 */
class Model extends ORM
{
    /**
     * Throw an exception if an entry is not found
     * @param  array  $query        MongoDB query array
     * @param  array  $fields       Fields you want to retrieve
     * @param  string $errorMessage Error message
     * @return array
     */
    public static function findOneOrFail($query = array(), $fields = array(), $errorMessage = null)
    {
        $errorMessage = is_null($errorMessage) ? "No data found" : $errorMessage;
        $data = self::findOne($query, $fields);
        if(empty($data)) {
            throw new \Base\Db\Exception\ModelNotFoundException($errorMessage);
        }
        return $data;
    }
}
