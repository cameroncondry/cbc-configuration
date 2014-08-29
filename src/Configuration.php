<?php

namespace CBC\Utility;

use CBC\Utility\Exception\OutOfBoundException;

class Configuration
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = [];
        $this->setAll($config);
    }

    /**
     * Retrieves a configuration item from the configuration object
     *
     * @param array|string $key
     * @return array
     * @throws \CBC\Utility\Exception\OutOfBoundException if the provided configuration key does not exist
     */
    public function get($key)
    {
        $config = $this->config;
        $path = $this->getPath($key);

        while ($step = array_shift($path)) {
            if (!isset($config[$step])) {
                throw new OutOfBoundException(sprintf('"%s" is not a valid configuration key in "%s"', $step, $key));
            }

            $config = $config[$step];
        }

        return $config;
    }

    /**
     * Retrieves all configuration items from the configuration object
     *
     * @return array
     */
    public function getAll()
    {
        return $this->config;
    }

    /**
     * Tests if a key exists in the configuration object
     *
     * @param mixed $key
     * @return bool
     */
    public function has($key)
    {
        $config = $this->config;
        $path = $this->getPath($key);

        while ($step = array_shift($path)) {
            if (!isset($config[$step])) {
                return false;
            }

            $config = $config[$step];
        }

        return true;
    }

    /**
     * Adds a configuration item to the configuration object
     *
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this->setConfig($key, $value, $this->config);

        return $this;
    }

    /**
     * Adds a configuration array to the configuration object
     *
     * @param array $config
     * @return $this
     */
    public function setAll(array $config = [])
    {
        foreach ($config as $key => $data) {
            $this->set($key, $data);
        }

        return $this;
    }

    /**
     * Parse path into an array using dot notation.
     *
     * input: 'first.second.third'
     * output: ['first', 'second', 'third']
     *
     * @param mixed $path
     * @return array
     */
    protected function getPath($path)
    {
        $path = is_array($path) ? $path : array_filter(explode('.', $path));
        $path = array_map('strtolower', $path);

        return $path;
    }

    /**
     * Save configuration into specified configuration array.
     *
     * input: ['app.foo' => true, 'app' => ['bar' => false]]
     * result: ['app' => ['foo' => true, 'bar' => false]]
     *
     * @param mixed $key
     * @param mixed $value
     * @param array $config
     */
    protected function setConfig($key, $value, array &$config)
    {
        $path = $this->getPath($key);

        while ($step = array_shift($path)) {
            if (!isset($config[$step]) || !is_array($config[$step])) {
                $config[$step] = [];
            }

            $config = & $config[$step];
        }

        if (!is_array($value)) {
            $config = $value;
        } else {
            foreach ($value as $key => $data) {
                $this->setConfig($key, $data, $config);
            }
        }
    }
}
