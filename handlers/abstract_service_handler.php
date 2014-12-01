<?php
/**
 * Class AbstractServiceHandler is abstract class for all handler related class.
 */
abstract class AbstractServiceHandler
{
    abstract public function execute(RestRequest $req);
}