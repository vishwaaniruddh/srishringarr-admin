<?php

namespace Api\V1\Core;

use PDO;
use PDOException;
use Api\V1\Core\Response;

class Database
{
    private static $instance = null;
    private $connections = [];
    private $isLocal;

    private function __construct()
    {
        $this->detectEnvironment();
        $this->setupDefaultConnection();
    }

    private function detectEnvironment()
    {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $this->isLocal = (
            $host === 'localhost' ||
            $host === '127.0.0.1' ||
            strpos($host, '192.168.') === 0 ||
            strpos($host, '10.') === 0
        );
    }

    private function setupDefaultConnection()
    {
        $config = $this->isLocal ? [
            'host' => 'localhost',
            'user' => 'reporting',
            'pass' => 'reporting',
            'db' => 'u464193275_srishrinjewels'
        ] : [
            'host' => 'localhost',
            'user' => 'u464193275_srishrinjuser',
            'pass' => '9b@hMgk!=zI',
            'db' => 'u464193275_srishrinjewels'
        ];

        try {
            $conn = new PDO(
                "mysql:host=" . $config['host'] . ";dbname=" . $config['db'],
                $config['user'],
                $config['pass']
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connections['default'] = $conn;
        } catch (PDOException $e) {
            Response::error("Primary connection failed: " . $e->getMessage(), 500);
        }
    }

    private function setupWooConnection()
    {
        // WooCommerce is always remote
        $config = [
            'host' => 'localhost',
            'user' => 'u464193275_FCSOL',
            'pass' => 'caMrYFsAmF',
            'db' => 'u464193275_ib3Xh'
        ];

        try {
            $conn = new PDO(
                "mysql:host=" . $config['host'] . ";dbname=" . $config['db'],
                $config['user'],
                $config['pass']
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connections['woo'] = $conn;
        } catch (PDOException $e) {
            return null;
        }
    }

    private function setupPosConnection()
    {
        $config = $this->isLocal ? [
            'host' => 'localhost',
            'user' => 'reporting',
            'pass' => 'reporting',
            'db' => 'u464193275_srishringarr'
        ] : [
            'host' => 'localhost',
            'user' => 'u464193275_sarmicropos',
            'pass' => 'Mypos1234',
            'db' => 'u464193275_srishringarr'
        ];

        try {
            $conn = new PDO(
                "mysql:host=" . $config['host'] . ";dbname=" . $config['db'],
                $config['user'],
                $config['pass']
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connections['pos'] = $conn;
        } catch (PDOException $e) {
            Response::error("POS connection failed: " . $e->getMessage(), 500);
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection($type = 'default')
    {
        if ($type === 'woo' && !isset($this->connections['woo'])) {
            $this->setupWooConnection();
        }
        if ($type === 'pos' && !isset($this->connections['pos'])) {
            $this->setupPosConnection();
        }
        return $this->connections[$type] ?? null;
    }

    public function isLocal()
    {
        return $this->isLocal;
    }
}
