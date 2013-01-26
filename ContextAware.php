<?php
/**
 * A tiny contexts holder for Silex applications.
 * 
 * Copyright (C) 2013 Johannes Schmidt
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App;

use Silex\Application;

/**
 * Provides a contexts holder for Silex applications.
 * 
 * Contexts are useful e.g. for safely control application behavior in 
 * functional and/or unit tests.
 * 
 * Get a ContextAware singleton via App\ContextAware::newInstance().
 * A context can be set up then in ContextAware::createApplication() which also 
 * returnes a Silex\Application.
 * 
 * <var>array('test' => true)</var> e.g. defines a context.
 * 
 * Shared as e.g. <var>$app['ca']</var> this context can be retrieved by 
 * <var>$app['ca']['test']</var>.
 * 
 * Once set up contexts cannot be modified.
 * 
 * @author  Johannes Schmidt <joschmidt@users.sourceforge.net>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU GPL 
 */
class ContextAware implements \ArrayAccess
{
    /**
     * Holds an instance of App\ContextAware.
     * 
     * @var App\ContextAware
     */
    private static $_instance = null;
    
    /**
     * Holds an instance of Silex\Application.
     * 
     * @var Silex\Application
     */
    private $_app;
    
    /**
     * Holds the unmodifiable contexts.
     * 
     * @var array
     */
    private $_values;
    
    /**
     * The constructor.
     * 
     * @return void
     */
    private function __construct()
    {
        self::$_instance = $this;
        $this->_app = null;
        $this->_values = array();
    }
    
    /**
     * Returns the singleton instance.
     * 
     * @return App\ContextAware A singleton instance of App\ContextAware.
     */
    public static function newInstance()
    {
        return is_null(self::$_instance) 
            ? new self() 
            : self::$_instance;
    }
    
    /**
     * Registers contexts and returns an instance of Silex\Application.
     * If an instance has already been created at runtime that instance 
     * is returned. 
     * New contexts will be added.
     * 
     * @param array $values The contexts.
     * 
     * @return Silex\Application An instance of Silex\Application.
     */
    public function createApplication(array $values = array())
    {
        if (is_null($this->_app)) {
            $this->_values = $values;
            return $this->_app = new Application();
        } else {
            $this->_values = $this->_values + $values;
            return $this->_app; 
        }
    }
    
    /**
     * \ArrayAccess::offsetExists()
     * 
     * @see \ArrayAccess::offsetExists()
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->_values);
    }
    
    /**
     * \ArrayAccess::offsetGet()
     * 
     * @see \ArrayAccess::offsetGet()
     */
    public function offsetGet($key)
    {
        if (!array_key_exists($key, $this->_values)) {
            throw new \InvalidArgumentException(
                sprintf('Identifier "%s" is not defined.', $key)
            );
        }
        return $this->_values[$key];
    }
    
    /**
     * \ArrayAccess::offsetSet()
     * 
     * @see \ArrayAccess::offsetSet()
     * @throws \Exception 
     */
    public function offsetSet($key, $value)
    {
        throw new \Exception('A context cannot be modified afterwards.');
    }
    
    /**
     * \ArrayAccess::offsetUnset()
     * 
     * @see \ArrayAccess::offsetUnset()
     * @throws \Exception 
     */
    public function offsetUnset($key)
    {
        throw new \Exception('A context cannot be modified afterwards.');
    }
}