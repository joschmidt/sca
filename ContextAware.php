<?php
/**
 * A tiny contexts holder for Silex applications.
 * 
 * Copyright (C) 2013 Johannes Schmidt <joschmidt@users.sourceforge.net>
 * 
 * This library is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU Lesser General Public License as published by 
 * the Free Software Foundation; either version 3 of the License, or 
 * (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU Lesser General Public License for more details.
 *  
 * You should have received a copy of the GNU Lesser General Public License 
 * along with this library; if not, see <https://www.gnu.org/licenses/lgpl.html>.
 */

namespace App;

/**
 * Provides a contexts holder for Silex applications.
 * 
 * Contexts are useful to e.g. allow safe control of application behavior in 
 * functional and/or unit tests.
 * 
 * Get a ContextAware singleton via App\ContextAware::newInstance().
 * A context can be set up then in ContextAware::setContext().
 * 
 * ContextAware::setContext(array('test' => true)) e.g. defines a context.
 * If shared as e.g. $app['ca'] this context can be retrieved by $app['ca']['test'].
 * 
 * Once set up contexts cannot be modified.
 * 
 * @author  Johannes Schmidt <joschmidt@users.sourceforge.net>
 * @license GNU LGPL <https://www.gnu.org/licenses/lgpl.html>
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
     * Registers contexts.
     * New contexts will be appended, existing ones will be preserved.
     * 
     * @param array $values The contexts.
     * 
     * @return void
     */
    public function setContext(array $values = array())
    {
        if (empty($this->_values)) {
            $this->_values = $values;
        } else {
            $this->_values = $this->_values + $values;
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
     * @throws \InvalidArgumentException If the key is unknown.
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
     * @throws \Exception In an attempt to add new keys or change the values of existing keys.
     */
    public function offsetSet($key, $value)
    {
        throw new \Exception('A context cannot be modified afterwards.');
    }
    
    /**
     * \ArrayAccess::offsetUnset()
     * 
     * @see \ArrayAccess::offsetUnset()
     * @throws \Exception In an attempt to unset a key.
     */
    public function offsetUnset($key)
    {
        throw new \Exception('A context cannot be modified afterwards.');
    }
}