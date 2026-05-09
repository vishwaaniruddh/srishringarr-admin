<?php

namespace Api\V1\Core;

use PDO;
use PDOException;
use Api\V1\Core\Response;

class Database {
    private static $instance = null;
    private $connections = [];

    private function __construct() {
        $this->setupDefaultConnection();
    }

    private function setupDefaultConnection() {
        $config = [
            'host' => '127.0.0.1',
            'user' => 'reporting',
            'pass' => 'reporting',
            'db'   => 'u464193275_srishrinjewels'
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

    private function setupWooConnection() {
        $config = [
            'host' => '193.203.184.203',
            'user' => 'u464193275_FCSOL',
            'pass' => 'caMrYFsAmF',
            'db'   => 'u464193275_ib3Xh'
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
            // We don't die here because remote might be down
            return null;
        }
    }

    private function setupPosConnection() {
        $config = [
            'host' => '127.0.0.1',
            'user' => 'reporting',
            'pass' => 'reporting',
            'db'   => 'u464193275_srishringarr'
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

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection($type = 'default') {
        if ($type === 'woo' && !isset($this->connections['woo'])) {
            $this->setupWooConnection();
        }
        if ($type === 'pos' && !isset($this->connections['pos'])) {
            $this->setupPosConnection();
        }
        return $this->connections[$type] ?? null;
    }
}
