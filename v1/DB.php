<?php


    /**
     * this class defines database connections
     */
    class DB
    {
        public $config;
        public $logger;
        
        public function __construct()
        {
            $configs = parse_ini_file("config/config.ini", true);
            $configs = json_decode(json_encode($configs));

            $this->config = $configs->database;
            $this->logger = new MenuLogger($configs->log);
        }

        /**
         * fetch statement
		 * @param string $sql
		 * @param null|array $bindingParams
		 * @param null|string $line
		 *
		 * @return array
		 */

        public function fetch($sql, $bindingParams = null, $line = null)
        {
            try {
                
                $conn = new PDO('mysql:host=' . $this->config->host . ';dbname=' . $this->config->dbname, $this->config->username, $this->config->password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

                $stmt = $conn->prepare($sql);
                $stmt->execute($bindingParams);
                $results = array();
                while ($row = $stmt->fetchObject()) {
                    $results[] = $row;
                }

                $stmt = null;
                $conn = null;
                return $results;
            } catch (Exception $ex) {
                
                $this->logger->EXCEPTION(" SQL $line SQL $sql  " . $ex->getMessage() . " TRACE " . $ex->getTraceAsString());
                error_log(" SQL $line SQL $sql  " . $ex->getMessage() . " TRACE " . $ex->getTraceAsString());

                $stmt = null;
                $conn = null;
                exit(1);
            }
        }

		/**
         * fetchOne
		 * @param string $sql
		 * @param array $bindingParams
		 * @param string $line
		 *
		 * @return bool|mixed
		 */
        public function fetchOne($sql, $bindingParams = null, $line = null)
        {
            $t1 = $this->getmicrotime();
            
            try {
                $conn = new PDO('mysql:host=' . $this->config->host . ';dbname=' . $this->config->dbname, $this->config->username,$this->config->password,array('unix_socket' => '/tmp/mysql.sock'));
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare($sql);
                $stmt->execute($bindingParams);
                $results = false;
                while ($row = $stmt->fetchObject()) {
                    $results = $row;
                }

                $stmt = null;
                $conn = null;
                $t2 = $this->getmicrotime();
                $time = $t2 - $t1;
                return $results;

            } catch (Exception $ex) {

                $this->logger->EXCEPTION(" SQL $line SQL $sql  " . $ex->getMessage() . " TRACE " . $ex->getTraceAsString());
                $stmt = null;
                $conn = null;
                exit(1);
            }
        }

		public function fetchOneMain($sql, $bindingParams = null, $line = null)
		{
			$t1 = $this->getmicrotime();
			
			try {
				$conn = new PDO('mysql:host=' . $this->config->host . ';dbname=' . $this->config->dbname, $this->config->username,$this->config->password,array('unix_socket' => '/tmp/mysql.sock'));
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$stmt = $conn->prepare($sql);
				$stmt->execute($bindingParams);
				$results = false;
				while ($row = $stmt->fetchObject()) {
					$results = $row;
				}

				$stmt = null;
				$conn = null;
				$t2 = $this->getmicrotime();
				return $results;
				
			} catch (Exception $ex) {
			    
				$this->logger->EXCEPTION(" SQL $line SQL $sql  " . $ex->getMessage() . " TRACE " . $ex->getTraceAsString());
				$stmt = null;
				$conn = null;
				exit(1);
			}
		}

        public function fetchMain($sql, $bindingParams = null, $line = null)
        {
            $t1 = $this->getmicrotime();
            
            try {
                $conn = new PDO('mysql:host=' . $this->config->host . ';dbname=' . $this->config->dbname, $this->config->username,$this->config->password,array('unix_socket' => '/tmp/mysql.sock'));
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare($sql);
                $stmt->execute($bindingParams);
                $results = array();
                while ($row = $stmt->fetchObject()) {
                    $results[] = $row;
                }

                $stmt = null;
                $conn = null;
                $t2 = $this->getmicrotime();
                $time = $t2 - $t1;
                return $results;
            } catch (Exception $ex) {
                
                $this->logger->EXCEPTION(" SQL $line SQL $sql " . $ex->getMessage() . " TRACE " . $ex->getTraceAsString());
                $stmt = null;
                $conn = null;
                exit(1);
            }
        }

        public function insert($sql, $bindingParams = null, $line = null)
        {
            $t1 = $this->getmicrotime();
            
            try {
                
                $conn = new PDO('mysql:host=' . $this->config->host . ';dbname=' . $this->config->dbname, $this->config->username,$this->config->password,array('unix_socket' => '/tmp/mysql.sock'));
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare($sql);
                $stmt->execute($bindingParams);
                $lastInsertId = $conn->lastInsertId();
                $stmt = null;
                $conn = null;
                $t2 = $this->getmicrotime();
                $time = $t2 - $t1;
                return $lastInsertId;
                
            } catch (Exception $ex) {
                
                $this->logger->EXCEPTION(" SQL $line SQL $sql  " . $ex->getMessage() . " TRACE " . $ex->getTraceAsString());
                $stmt = null;
                $conn = null;
                exit(1);
            }
        }

        public function update($sql, $bindingParams = null, $line = null)
        {
            $t1 = $this->getmicrotime();

            try {
                
                $conn = new PDO('mysql:host=' . $this->config->host . ';dbname=' . $this->config->dbname, $this->config->username,$this->config->password,array('unix_socket' => '/tmp/mysql.sock'));
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare($sql);
                $stmt->execute($bindingParams);
                $rowCount = $stmt->rowCount();
                $stmt = null;
                $conn = null;
                $t2 = $this->getmicrotime();
                $time = $t2 - $t1;
                return $rowCount;
                
            } catch (Exception $ex) {
                
                $this->logger->EXCEPTION(" SQL $line SQL $sql " . $ex->getMessage() . " TRACE " . $ex->getTraceAsString());
                $stmt = null;
                $conn = null;
                exit(1);
            }
        }

        public function executeBatch($sql, $load = array(), $line = null)
        {
            $t1 = $this->getmicrotime();

            try {
                
                $conn = new PDO('mysql:host=' . $this->config->host . ';dbname=' . $this->config->dbname, $this->config->username,$this->config->password,array('unix_socket' => '/tmp/mysql.sock'));
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->beginTransaction();
                $stmt = $conn->prepare($sql);
                $rowCount = 0;

                foreach ($load as $key => $value) {
                    $status = $stmt->execute($value);
                    $rowCount = $rowCount + $status;
                }

                $conn->commit();
                $stmt = null;
                $conn = null;
                $t2 = $this->getmicrotime();
                $time = $t2 - $t1;
                return $rowCount;
                
            } catch (Exception $ex) {
                
                $this->logger->EXCEPTION(" SQL $line SQL $sql " . $ex->getMessage() . " TRACE " . $ex->getTraceAsString());
                $stmt = null;
                $conn = null;
                exit(1);
            }
        }

        /**
         * @param     string  $sql
         * @param array $load
         * @param null  $line
         *
         * @return int
         */
        public function batchUpdate($sql, $bindingParams = array(), $line = null)
        {

            try {
                
                $conn = new PDO('mysql:host=' . $this->config->host . ';dbname=' . $this->config->dbname, $this->config->username,$this->config->password,array('unix_socket' => '/tmp/mysql.sock'));
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->beginTransaction();
                $stmt = $conn->prepare($sql);
                $rowCount = 0;

                foreach ($bindingParams as $value) {

                    $stmt->execute($value);
                    $rowCount = $stmt->rowCount() + $rowCount;
                }

                $conn->commit();
                $stmt = null;
                $conn = null;
                
                return $rowCount;
            } catch (Exception $ex) {
                
                $this->logger->EXCEPTION(" SQL $line SQL $sql " . $ex->getMessage() . " TRACE " . $ex->getTraceAsString());
                $stmt = null;
                $conn = null;
                exit(1);
                
            }
        }

        public function getmicrotime()
        {

            list ($msec, $sec) = explode(" ", microtime());
            return ((float)$msec + (float)$sec);
        }
    }

?>
