<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');

$logger = new \app\src\Log();
$dbcache = new \app\src\DBCache();

/**
 * Before router middleware checks to see
 * if the user is logged in.
 */
$app->before('GET|POST|PUT|DELETE|PATCH|HEAD', '/v1.*', function() use ($app) {
    if (!isUserLoggedIn()) {
        redirect(url('/login/'));
    }
});

$app->group('/v1', function() use ($app, $orm, $logger, $dbcache) {

    /**
     * Will result in /v1/ which is the root
     * of the api and contains no content.
     */
    $app->get('/', function () use($app) {
        $app->res->_format('json', 204);
    });

    /**
     * Will result in /api/dbtable/
     */
    $app->get('/(\w+)', function ($table) use($app, $orm) {

        if (isset($_GET['by']) === true) {
            if (isset($_GET['order']) !== true) {
                $_GET['order'] = 'ASC';
            }
            $table->orderBy($_GET['by'], $_GET['order']);
        }

        if (isset($_GET['limit']) === true) {
            $table->limit($_GET['limit']);
            if (isset($_GET['offset']) === true) {
                $table->offset($_GET['offset']);
            }
        }

        $table = $orm->$table();
        /**
         * Use closure as callback.
         */
        $q = $table->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });
        /**
         * If the database table doesn't exist, then it
         * is false and a 404 should be sent.
         */
        if ($q === false) {
            $app->res->_format('json', 404);
        }
        /**
         * If the query is legit, but there
         * is no data in the table, then a 200
         * status should be sent. Why? Check out
         * the accepted answer at
         * http://stackoverflow.com/questions/13366730/proper-rest-response-for-empty-table/13367198#13367198
         */ elseif (empty($q) === true) {
            $app->res->_format('json');
        }
        /**
         * If we get to this point, the all is well
         * and it is ok to process the query and print
         * the results in a json format.
         */ else {
            $app->res->_format('json', 200, $q);
        }
    });

    /**
     * Will result in /api/dbtable/columnname/data/
     */
    $app->get('/(\w+)/(\w+)/(.*)', function ($table, $field, $any) use($app, $orm, $dbcache) {
        $table = $orm->$table();
        $q = $table->select()->where("$field = ?", $any);

        $results = $dbcache->getCache("$table-$any");

        if (empty($results)) {
            /**
             * Use closure as callback.
             */
            $results = $q->find(function($data) {
                $array = [];
                foreach ($data as $d) {
                    $array[] = $d;
                }
                return $array;
            });
            $dbcache->writeCache("$table-$any", $results);
        }
        /**
         * If the database table doesn't exist, then it
         * is false and a 404 should be sent.
         */
        if ($results === false) {
            $app->res->_format('json', 404);
        }
        /**
         * If the query is legit, but there
         * is no data in the table, then a 200
         * status should be sent. Why? Check out
         * the accepted answer at
         * http://stackoverflow.com/questions/13366730/proper-rest-response-for-empty-table/13367198#13367198
         */ elseif (empty($results) === true) {
            $app->res->_format('json');
        }
        /**
         * If we get to this point, the all is well
         * and it is ok to process the query and print
         * the results in a json format.
         */ else {
            $app->res->_format('json', 200, $results);
        }
    });

    $app->delete('/(\w+)/(\w+)/(\d+)', function($table, $field, $id) use($app, $orm) {

        $query = [
            sprintf('DELETE FROM %s WHERE %s = ?', $table, $field),
        ];

        $query = sprintf('%s;', implode(' ', $query));
        $result = $orm->query($query, [$id]);

        if ($result === false) {
            $app->res->_format('json', 404);
        } else if (empty($result) === true) {
            $app->res->_format('json', 204);
        } else {
            $app->res->_format('json');
        }
    });

    if (in_array($http = strtoupper($_SERVER['REQUEST_METHOD']), ['POST', 'PUT']) === true) {
        if (preg_match('~^\x78[\x01\x5E\x9C\xDA]~', $data = _file_get_contents('php://input')) > 0) {
            $data = gzuncompress($data);
        }
        if ((array_key_exists('CONTENT_TYPE', $_SERVER) === true) && (empty($data) !== true)) {
            if (strncasecmp($_SERVER['CONTENT_TYPE'], 'application/json', 16) === 0) {
                $GLOBALS['_' . $http] = json_decode($data, true);
            } else if ((strncasecmp($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded', 33) === 0) && (strncasecmp($_SERVER['REQUEST_METHOD'], 'PUT', 3) === 0)) {
                parse_str($data, $GLOBALS['_' . $http]);
            }
        }
        if ((isset($GLOBALS['_' . $http]) !== true) || (is_array($GLOBALS['_' . $http]) !== true)) {
            $GLOBALS['_' . $http] = [];
        }
        unset($data);
    }

    $app->post('/(\w+)/', function($table) use($app, $orm, $logger) {

        if (empty($_POST) === true) {
            $app->flash('error_message', '204 - Error: No Content');
            redirect($app->req->server['HTTP_REFERER']);
        } elseif (is_array($_POST) === true) {
            $queries = [];

            if (count($_POST) == count($_POST, COUNT_RECURSIVE)) {
                $_POST = [$_POST];
            }

            foreach ($_POST as $row) {
                $data = [];

                foreach ($row as $key => $value) {
                    $data[sprintf('%s', $key)] = $value;
                }

                $query = [
                    sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, implode(', ', array_keys($data)), implode(', ', array_fill(0, count($data), '?'))),
                ];

                $queries[] = [
                    sprintf('%s;', implode(' ', $query)),
                    $data,
                ];
            }

            if (count($queries) > 1) {
                $orm->query()->beginTransaction();

                while (is_null($query = array_shift($queries)) !== true) {
                    if (($result = $orm->query($query[0], array_values($query[1]))) === false) {
                        $orm->query->rollBack();
                        break;
                    }
                }

                if (($result !== false) && ($orm->query->inTransaction() === true)) {
                    $result = $orm->query()->commit();
                }
            } else if (is_null($query = array_shift($queries)) !== true) {
                $result = $orm->query($query[0], array_values($query[1]));
            }

            if ($result === false) {
                $app->flash('error_message', '409 - Error: Conflict');
                redirect($app->req->server['HTTP_REFERER']);
            } else {
                $app->flash('success_message', '201 - Success: Created');
                /* Write to logs */
                $logger->setLog('New Record', 'x-www-form-urlencoded', $table, get_persondata('uname'));
                redirect($app->req->server['HTTP_REFERER']);
            }
        }
    });

    $app->post('/(\w+)/(\w+)/(\d+)', function($table, $field, $id) use($app, $orm, $logger, $dbcache) {

        if (empty($_POST) === true) {
            $app->flash('error_message', '204 - Error: No Content');
            redirect($app->req->server['HTTP_REFERER']);
        } else if (is_array($_POST) === true) {
            $data = [];

            foreach ($_POST as $key => $value) {
                $data[$key] = sprintf('%s = ?', $key);
            }

            $query = [
                sprintf('UPDATE %s SET %s WHERE %s = ?', $table, implode(', ', $data), $field),
            ];

            $query = sprintf('%s;', implode(' ', $query));
            $values = array_values($_POST);
            $result = $orm->query($query, array_merge($values, [$id]));

            if ($result === false) {
                $app->flash('error_message', '409 - Error: Confict');
                redirect($app->req->server['HTTP_REFERER']);
            } else if (empty($result) === true) {
                $app->flash('error_message', '204 - Error: No Content');
                redirect($app->req->server['HTTP_REFERER']);
            } else {
                $dbcache->clearCache("$table-$id");
                $app->flash('success_message', '200 - Success: Ok');
                /* Write to logs */
                $logger->setLog('Update Record', 'x-www-form-urlencoded', $table, get_persondata('uname'));
                redirect($app->req->server['HTTP_REFERER']);
            }
        }
    });
});