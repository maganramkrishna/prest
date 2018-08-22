<?php

namespace Prest\Constants;

/**
 * Prest\Constants\Services
 *
 * @package Prest\Constants
 */
class Services
{
    // Phalcon
    const APPLICATION = "application";
    const DISPATCHER = "dispatcher";
    const ROUTER = "router";
    const URL = "url";
    const REQUEST = "request";
    const RESPONSE = "response";
    const COOKIES = "cookies";
    const FILTER = "filter";
    const FLASH = "flash";
    const FLASH_SESSION = "flashSession";
    const SESSION = "session";
    const EVENTS_MANAGER = "eventsManager";
    const DB = "db";
    const SECURITY = "security";
    const CRYPT = "crypt";
    const TAG = "tag";
    const ESCAPER = "escaper";
    const ANNOTATIONS = "annotations";
    const MODELS_MANAGER = "modelsManager";
    const MODELS_METADATA = "modelsMetadata";
    const TRANSACTION_MANAGER = "transactionManager";
    const MODELS_CACHE = "modelsCache";
    const VIEWS_CACHE = "viewsCache";
    const ASSETS = "assets";

    // Prest
    const AUTH_MANAGER = "authManager";
    const ACL = "acl";
    const TOKEN_PARSER = "tokenParser";
    const QUERY = "query";
    const USER_SERVICE = "userService";
    const URL_QUERY_PARSER = "urlQueryParser";
    const ERROR_HELPER = "errorHelper";
    const FORMAT_HELPER = "formatHelper";
    const FRACTAL_MANAGER = 'fractalManager';
    const PHQL_QUERY_PARSER = 'phqlQueryParser';
}
