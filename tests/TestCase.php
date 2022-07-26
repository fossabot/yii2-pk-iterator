<?php

namespace serhioli\Yii2\Db\PkIterator\Tests;

use yii\console\Application;
use yii\helpers\ArrayHelper;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public static $params;

    /**
     * @var array Database connection configuration.
     */
    protected $dbConfig = [
        'dsn'      => 'mysql:host=127.0.0.1;',
        'username' => '',
        'password' => '',
    ];
    /**
     * @var \yii\db\Connection database connection instance.
     */
    protected $db;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assertTrue(
            extension_loaded('pdo') && extension_loaded('pdo_mysql'),
            'pdo and pdo_mysql extension are required.'
        );

        $this->mockApplication([
            'components' => [
                'db' => self::getParam('db')
            ]
        ]);
    }

    protected function tearDown(): void
    {
        $this->destroyApplication();
    }

    /**
     * Returns a test configuration param from /data/config.php
     *
     * @param string $name   params name
     * @param mixed $default default value to use when param is not set.
     *
     * @return mixed  the value of the configuration param
     */
    public static function getParam($name, $default = null)
    {
        if (static::$params === null) {
            static::$params = require(__DIR__ . '/data/config.php');
        }

        return isset(static::$params[$name]) ? static::$params[$name] : $default;
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     *
     * @param array $config    The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication(array $config = [], string $appClass = Application::class)
    {
        new $appClass(
            ArrayHelper::merge([
                'id'         => 'testapp',
                'basePath'   => __DIR__,
                'vendorPath' => $this->getVendorPath(),
            ], $config)
        );
    }

    protected function getVendorPath()
    {
        $reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);

        return dirname($reflection->getFileName(), 2);
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        \Yii::$app = null;
    }
}
