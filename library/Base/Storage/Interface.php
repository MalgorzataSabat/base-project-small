<?php
/**
 * Created by PhpStorm.
 * User: Robert Rogiński
 * Date: 08.05.14
 * Time: 15:04
 */
interface Base_Storage_Interface
{

    public function add($id, $data, $type);

    public function set($type, $data);


}