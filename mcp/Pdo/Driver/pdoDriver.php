<?php

namespace LinHUniX\Pdo\Driver;

use LinHUniX\Mcp\Model\mcpBaseModelClass;
use \PDO;

/*
 * @copyright Content copyright to linhunix.com 2003-2018
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @version GIT:2018-v1
 * this new class implement the PDO mode to connect on databases;
 * @see [vendor]/mcp/Head.php
 */

class pdoDriver extends mcpBaseModelClass
{

    var $PDO;
    var $tables;
    var $tabcount;
    var $tmp;
    var $cache;
    var $debug;
    var $database;
    var $dburlcon;

    function __construct(\LinHUniX\Mcp\masterControlProgram &$mcp, array $scopeCtl, array $scopeIn)
    {
        parent::__construct($mcp, $scopeCtl, $scopeIn);
        $i = 0;
        if (isset($scopeIn["dburlcon"])) {
            $this->debug = false;
            $this->dburlcon = $scopeIn["dburlcon"];
            $this->database = $scopeIn["database"];
            $username = $scopeIn["username"];
            $password = $scopeIn["password"];
            $options = $scopeIn["options"];
            $this->getMcp()->info("dburlcon:" . $this->dburlcon);
            $this->getMcp()->debug("username:" . $username);
            $this->getMcp()->info("database:" . $this->database);
            try {
                $this->PDO = new PDO($this->dburlcon, $username, $password, $options);
            } catch (\Exception $e) {
                $mcp->warning("DBCONN: ERR=" . $e->getMessage());
                return null;
            }
            $data = $this->getTable("SHOW TABLES");
            if (is_array($data)) {
                foreach ($data as $dt) {
                    foreach ($dt as $k => $v) {
                        $this->tables[$i] = $v;
                        $i++;
                    }
                }
            }
        }else{
            $this->warning("Not Db Connection Found!!");
            return null;
        }
        $this->tabcount = $i;
        $this->tmp = array();
        $this->cache = array();
    }
    /*
     * remplace the mysqlLegacyRealEscapeString
     * @see mysqlLegacyRealEscapeString(),PDO->quote()
     * for an issue on Php 5.6 change to
     */

    public function real_escape_string($value)
    {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
        return str_replace($search, $replace, $value);
    }

    //Check if the table exists in the database
    public function table_exist($tablename)
    {
        if (in_array($tablename, $this->tables)) {
            return true;
        } else {
            return false;
        }
    }

    public function intexec($query)
    {
        try {
            $query = str_replace("'", '"', $query);
            return $this->PDO->exec($query);
        } catch (\Exception $e) {
            $this->getMcp()->warning($this->database . $e->getMessage());
            return false;
        }
    }

    public function execute($query, $var = array())
    {
        foreach ($var as $k => $v) {
            $query = str_replace('[' . $k . ']', $this->real_escape_string($v), $query);
        }
        if ($this->intexec($query) == false) {
            $this->getMcp()->debug("[OK]" . $this->database . "=" . $query);
            return false;
        }
        $this->getMcp()->warning("[KO]" . $this->database . "=" . $query);
        return true;
    }

    public function querycache($sql, $starttime = 0, $finishtime = -1)
    {
        // counter
        if (!isset($this->cache['cnt'])) {
            $cnt = 0;
        } else {
            $cnt = $this->cache['cnt'];
        }
        if (empty($cnt)) {
            $cnt = 0;
        }
        $cnt++;
        $this->cache['cnt'] = $cnt;
        $this->cache['last'] = $sql;
        $qcnt = 0;
        if (isset($this->cache[$sql])) {
            $qcnt = $this->cache[$sql]['cnt'];
        }
        $qcnt++;
        $this->cache[$sql]['cnt'] = $qcnt;
        $this->cache[$sql][$qcnt] = sprintf('%0.6f', $finishtime - $starttime);
        $this->getMcp()->debug("(" . $starttime . "-" . $finishtime . ")" . $sql);
    }

    //Get Database Query Results
    public function queryReturnResultSet($sql, $logging = false)
    {
        if ($this->debug) {
            list($usec, $sec) = explode(' ', microtime());
            $starttime = ((float)$usec + (float)$sec);
        }
        try {
            $statement = $this->PDO->query($sql);
        } catch (Exception $e) {
            $this->getMcp()->warning($this->database . $e->getMessage());
            return null;
        }
        if ($this->debug) {
            list($usec, $sec) = explode(' ', microtime());
            $finishtime = ((float)$usec + (float)$sec);
            $this->querycache($sql, $starttime, $finishtime);
        }
        return $statement;
    }

    public function simpleQuery($sql, $var = array(), $err = true)
    {
        $result_set = array();
        $this->getMcp()->debug("queryIn:" . $this->database . "=" . $sql);
        if (isset($var["WHERE"])) {
            $sql = str_replace('[WHERE]', $var["WHERE"], $sql);
            unset($var["WHERE"]);
        }
        foreach ($var as $k => $v) {
            $sql = str_replace('[' . $k . ']', $this->real_escape_string($v), $sql);
        }
        $this->getMcp()->debug("queryOut:" . $this->database . "=" . $sql);
        try {
            $statement = $this->queryReturnResultSet($sql);
            if ($statement == null) {
                if ($err == false) {
                    $this->getMcp()->critical($this->database . ":" . $sql . " NULL DATA!!!");
                } else {
                    $this->getMcp()->warning($this->database . ":" . $sql . " NULL DATA!!!");
                }
                return false;
            } else {
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $result_set[] = $row;
                }
            }
            @$statement->closeCursor();
        } catch (Exception $e) {
            if ($err == false) {
                $this->getMcp()->critical($this->database . $e->getMessage());
            } else {
                $this->getMcp()->warning($this->database . $e->getMessage());
            }
        }
        if ($result_set == null) {
            return false;
        }
        return $result_set;
    }

    public function simpleCount($sql, $var = array(), $err = true)
    {
        $res = $this->simpleQuery($sql, $var, $err);
        return count($res);
    }

    //Execute Database Query
    public function query($sql, $logging = false)
    {
        //$this->freeresult();
        $this->tmp['Data'] = $this->simpleQuery($sql);
        $this->tmp['DSrc'] = $sql;
        $this->tmp['RMin'] = 0;
        $this->tmp['RMax'] = count($this->tmp['Data']);
        $this->getMcp()->debug("query:" . print_r($this->tmp, 1));
        return $this->tmp['Data'];
    }

    public function getTable($sql, $var = array(), $err = true)
    {
        $rs = $this->simpleQuery($sql, $var, $err);
        if (empty($rs)) {
            $this->getMcp()->warning($this->database . " Data is Null");
            return false;
        }
        return $rs;
    }

    // Compatibility Mode for The older code 
    public function data_table($sql, $var = array(), $err = true)
    {
        $this->getTable($sql);
    }

    public function dataWalk($sql, $callback, $var = array(), &$funarr = array(), $err = true)
    {
        $res = $this->data_table($sql, $var, $err);
        if (count($res) > 0) {
            return array_walk($res, $callback, $funarr);
        }
        return false;
    }

    public function firstRow($sql, $var = array(), $err = true)
    {
        $rs = $this->simpleQuery($sql, $var, $err);
        if ((!is_array($rs)) || (!isset($rs[0]))) {
            $this->getMcp()->warning($this->database . " Data is Null");
            return false;
        }
        return $rs[0];
    }

    public function data_row($sql, $var = array(), $err = true)
    {
        return $this->firstRow($sql);
    }

    //Get Next Database Record
    public function nextRow(&$statement = false)
    {
        if ($statement == false) {
            if (!isset($this->tmp['Data'])) {
                $this->getMcp()->warning($this->database . " Data is Null");
                return false;
            }
            if (!is_array($this->tmp['Data'])) {
                $this->getMcp()->warning($this->database . " Data not array");
                return $this->tmp['Data'];
            }
            $rmin = $this->tmp['RMin'];
            if ($rmin > $this->tmp['RMax']) {
                $this->getMcp()->warning($this->database . " Data RMin over RMax");
                return false;
            }
            $rmin++;
            $this->tmp['RMin'] = $rmin;
            $this->getMcp()->debug("next_record:" . print_r($this->tmp, 1));
            return $this->tmp['Data'][$rmin];
        }
        if (is_array($statement)) {
            return next($statement);
        }
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function next_record(&$statement = false)
    {
        return $this->nextRow($statement);
    }

    //Seek Database Results
    public function seek($position = 0, $statement = false)
    {
        if ($statement == false) {
            if (!isset($this->tmp['Data'])) {
                $this->getMcp()->warning($this->database . "Data not array");
                return false;
            }
            if ($position > $this->tmp['RMax']) {
                $this->getMcp()->warning($this->database . "Data RMin over RMax");
                return false;
            }
            $this->tmp['RMin'] = $position;
            $this->getMcp()->warning($this->database . "query:" . print_r($this->tmp, 1));
            return $this->tmp['Data'][$position];
        }
        if (is_array($statement)) {
            return $statement[$position];
        }
        return $statement->fetch(PDO::FETCH_ASSOC, $position);
    }

    public function freeresult($statement = false)
    {
        if ($statement != false) {
            $statement->closeCursor();
        }
        $this->tmp = array();
    }

    //Get Number Database Rows - CHECK - function below num_rows()
    public function numRows($statement = false)
    {
        if ($statement === false) {
            if (!isset($this->tmp['RMax'])) {
                $this->getMcp()->warning($this->database . " Data not array");
                return false;
            }
            return $this->tmp['RMax'];
        }
        if (is_array($statement)) {
            return count($statement);
        }
        try {
            if ( method_exists($statment,"fetchColumn")) {
                return $statement->fetchColumn();
            }
        } catch (Exception $e) {
            $this->getMcp()->warning($this->database . $e->getMessage());
        }
        return 0;
    }

    //Get Affected Database Rows
    public function affectedRows($resultSet = false)
    {
        return $this->numRows($resultSet);
    }

    //Get Number Database Rows
    public function num_rows($resultSet = false)
    {
        return $this->numRows($resultSet);
    }

    //Get Insert ID - CHECK - function below insertID()
    public function lastData()
    {
        return $this->tmp['Data'];
    }

    //Delete Row from table
    public function delRow($_table, $_id)
    {
        if (($_table != '') && ($_id > 0)) {
            $_result = $this->execute('DELETE FROM ' . $_table . ' WHERE id = ' . $_id);
        }
        if ($_result) {
            return true;
        }
        return false;
    }

    //Get Row from table
    public function getRow($_fields, $_table, $_id)
    {
        if ($_id > 0) {
            $_result = array();
            $_res = $this->queryReturnResultSet('SELECT * FROM ' . $_table . ' WHERE id = ' . $_id . ' LIMIT 1;');
            while ($_row = $_res->fetch(PDO::FETCH_ASSOC)) {
                foreach ($_fields as $_field) {
                    $_result[$_field] = stripslashes($_row[$_field]);
                }
            };
            return $_result;
        }
        return null;
    }

    //Get Rows from table
    public function getRows($_fields, $_table, $_order_by, $_from, $_size, $_where_stmt = '')
    {
        $_result = array();
        $_cnt = 0;
        $_where_stmt = $_where_stmt != '' ? ' WHERE ' . $_where_stmt : '';
        $_res = $this->queryReturnResultSet('SELECT * FROM `' . $_table . '` ' . $_where_stmt . ' ORDER BY ' . $_order_by . ' LIMIT ' . $_from . ', ' . $_size . ';');
        while ($_row = $_res->fetch(PDO::FETCH_ASSOC)) {
            foreach ($_fields as $_field) {
                $_result[$_cnt][$_field] = stripslashes($_row[$_field]);
            }
            $_cnt++;
        };

        return $_result;
    }

    //Get Last ID from table
    public function getLastId($_table)
    {
        $_res = $this->firstRow('SELECT id FROM `' . $_table . '` ORDER BY id DESC LIMIT 1;');
        if (isset($_row['id'])) {
            return $_row['id'];
        }
        return null;
    }

    //Add/Update Row
    function setRow($_fields, $_table, $run = true)
    {
        if ((count($_fields) > 0) && ($_table != '')) {
            $_stmt = $this->getSql($_fields, $_table);
            if ($run)
                $_result = $this->query($_stmt);
            else
                $_result = $_stmt;
        }
        if ($_result) {
            return $_result;
        }
        return false;
    }

    //GET SetRow SQL
    function getSql($_fields, $_table)
    {
        $_stmt = '';
        if ($_fields['id'] > 0) {
            $_stmt .= 'UPDATE `' . $_table . '` SET ';
            foreach ($_fields as $_key => $_val) {
                if ($_val != '') {
                    $_stmt .= '`' . $_key . '` = \'' . addslashes($_val) . '\',';
                }
            }
            $_stmt = substr($_stmt, 0, strlen($_stmt) - 1);
            $_stmt .= ' WHERE id = ' . $_fields['id'] . ';';
        } else {
            $_stmt = 'INSERT INTO `' . $_table . '` ( ';
            foreach ($_fields as $_key => $_val) {
                if ($_val != '') {
                    $_stmt .= '`' . $_key . '`,';
                }
            }
            $_stmt = substr($_stmt, 0, strlen($_stmt) - 1);
            $_stmt .= ' ) VALUES ( ';

            foreach ($_fields as $_key => $_val) {
                if ($_val != '') {
                    $_stmt .= '\'' . addslashes($_val) . '\',';
                }
            }
            $_stmt = substr($_stmt, 0, strlen($_stmt) - 1);
            $_stmt .= ' );';
        }

        return $_stmt;
    }

    /**
     * $scope array is 
     * var ["T"]:
     *  e  = execute : exec query with boolean results  
     *  f  = firstRow : return only first row 
     *  q  = retrive array of all results 
     *  c  = return the count of the results 
     *  s  = return the sql information 
     *  r  = return sql and env 
     *  other case return false;
     * var ["Q"] = query 
     * var ["V"] = contain the values that need to remplace on query scripts 
     * @author Andrea Morello <andrea.morello@linhunix.com>
     * @version GIT:2018-v1
     * @param Container $GLOBALS["cfg"] Dipendecy injection for Pimple\Container
     * @param array $this->argIn temproraney array auto cleanable 
     * @return boolean|array query results 
     */
    public function moduleCore()
    {
        if ($this->PDO==null){
            $this->getMcp()->warning("Database Connection Error (not initalizzed!!)");
            return false;
        }
        if ((empty($this->argIn["Q"]))) {
            return false;
        }
        try {
            switch ($this->argIn["T"]) {
                case "e":
                    $this->argOut = $this->execute($this->argIn["Q"], $this->argIn["V"]);
                    break;
                case "f":
                    $this->argOut = $this->firstRow($this->argIn["Q"], $this->argIn["V"]);
                    break;
                case "q":
                    $this->argOut = $this->simpleQuery($this->argIn["Q"], $this->argIn["V"]);
                    break;
                case "c":
                    $this->argOut = $this->simpleCount($this->argIn["Q"], $this->argIn["V"]);
                    break;
                case "s":
                    $this->argOut = $this->argIn["Q"];
                    break;
                case "r":
                    $this->argOut = array(
                        "sql" => $this->argIn["Q"],
                        "env" => $this->argIn["E"]
                    );
                    break;
            }
        } catch (Exception $e) {
            $this->getMcp()->warning("QueryIdx:Index=" . $this->argIn["I"] . ",Error:" . $e->getMessage());
        }
    }
}
