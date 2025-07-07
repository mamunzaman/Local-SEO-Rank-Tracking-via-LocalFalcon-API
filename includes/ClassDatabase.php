<?php
//require_once("global.data.php");
include 'global.data.php';

/****************
 * FOR DATA INSERT UPDATE AND DELETE CHECK THIS LIKE AS REFERENCE
 * https://www.vultr.com/docs/create-a-centralized-php-data-object-class-for-mysql/
 */
class DatabaseGateway{
    public  $error    = '';
    public $tableName = 'localfalcon_request_lists';

    private function dbConnect()
    {
        // call every class and function
        Logger::log("DatabaseClass dbConnect() instantiated");

        try {

            $db_host = $GLOBALS['db_host'];
            $db_user = $GLOBALS['db_user'];
            $db_password = $GLOBALS['db_password'];
            $db_name = $GLOBALS['db_name'];
            //$port = '3306';
            /*
            $db_name     = 'focke_survey_join';
            $db_user     = 'root';
            $db_password = 'root';
            $db_host     = 'localhost';
            */
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // error reporting
            $pdo = new PDO("mysql:host=" . $db_host  . ";charset=utf8;dbname=" . $db_name, $db_user, $db_password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $pdo;

        } catch(PDOException $e) {
            return $this->error = $e->getMessage();
        }
    }

    /**************
     * @param $sql
     * @param string $data
     * @return array|string|void select statement return.
     */
    public function query($sql, $data = '')
    {
        try {

            $pdo  = $this->dbConnect();

            if ($this->error != '') {
                return $this->error;
            }

            $stmt = $pdo->prepare($sql);

            if (!empty($data)) {
                foreach ($data as $key => &$val) {
                    $stmt->bindParam($key, $val, PDO::PARAM_STR);
                }
            }

            $stmt->execute();
            $response = [];

            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $response[] = $row;
            }

            $pdo = null;

            return $response;

        } catch(PDOException $e) {

            return $this->error = $e->getMessage();
        }
    }

    /*************
     * @param $sql
     * @param $data
     * @return string|void return execute of a query
     */
    public function executeTransaction($sql, $data)
    {
        try {
            $pdo = $this->dbConnect();

            if ($this->error != '') {
                return $this->error;
            }

            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($data);
            } catch(PDOException $e) {
                return $this->error = $e->getMessage();
            }
        } catch(PDOException $e) {
            return $this->error =  $e->getMessage();
        }
    }

}