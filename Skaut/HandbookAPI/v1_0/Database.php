<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or die('Restricted access.');

use Skaut\HandbookAPI\v1_0\Exception\ConnectionException;
use Skaut\HandbookAPI\v1_0\Exception\ExecutionException;
use Skaut\HandbookAPI\v1_0\Exception\NotFoundException;
use Skaut\HandbookAPI\v1_0\Exception\QueryException;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Database
{
    private static $db;
    private static $instanceCount = 0;
    private $SQL;
    private $statement;

    public function __construct()
    {
        $_API_SECRETS_EXEC = 1;
        $SECRETS = require($_SERVER['DOCUMENT_ROOT'] . '/api-secrets.php');
        if (self::$instanceCount < 1) {
            try {
                self::$db = new \PDO($SECRETS->db_dsn . ';charset=utf8mb4', $SECRETS->db_user, $SECRETS->db_password);
                self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
            } catch (\PDOException $e) {
                throw new ConnectionException($e);
            }
        }
        self::$instanceCount = self::$instanceCount + 1;
    }

    /** @SuppressWarnings(PHPMD.CamelCaseParameterName) */
    public function prepare(string $SQL) : void
    {
        $this->SQL = $SQL;
        $this->statement = self::$db->prepare($this->SQL);
        if (!$this->statement) {
            throw new QueryException($this->SQL, self::$db);
        }
    }

    public function bindParam(string $name, &$value, int $dataType) : void
    {
        $this->statement->bindParam($name, $value, $dataType);
    }

    public function execute(string $resourceName = "") : void
    {
        if (!$this->statement->execute()) {
            if ($this->statement->errorCode() == 23000) {
                // Foreign key constraint fail
                throw new NotFoundException($resourceName);
            }
            throw new ExecutionException($this->SQL, $this->statement);
        }
    }

    public function rowCount() : int
    {
        return $this->statement->rowCount();
    }

    public function bindColumn($name, &$value) : void
    {
        $this->statement->bindColumn($name, $value);
    }

    public function fetch() : bool
    {
        return $this->statement->fetch(\PDO::FETCH_BOUND);
    }

    public function fetchRequire(string $resourceName) : void
    {
        if (!$this->fetch()) {
            throw new NotFoundException($resourceName);
        }
    }

    public function fetchAll() : array
    {
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function beginTransaction() : void
    {
        self::$db->beginTransaction();
    }

    public function endTransaction() : void
    {
        try {
            self::$db->commit();
        } catch (\PDOException $e) {
            throw new ConnectionException($e);
        }
    }

    public function __destruct()
    {
        if (isset($this->statement)) {
            $this->statement = null;
        }
        self::$instanceCount = self::$instanceCount - 1;
        if (self::$instanceCount === 0) {
            self::$db = null;
        }
    }
}
