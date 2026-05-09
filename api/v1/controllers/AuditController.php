<?php

namespace Api\V1\Controllers;

use Api\V1\Core\Controller;
use Api\V1\Core\Response;
use Api\V1\Core\Logger;

class AuditController extends Controller {
    public function logs() {
        $logs = Logger::getLogs();
        Response::success("Logs retrieved", $logs);
    }

    public function clear() {
        Logger::clearLogs();
        Response::success("Logs cleared");
    }
}
