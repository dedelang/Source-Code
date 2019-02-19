<?php

namespace Dedelang\Engine\DB;

use PDO;

use PDOException;

class Knex{

	protected static $connection;

	protected static $table;

	protected static $tables=[];

	protected static $query;

	protected static $data = [];

	protected static $bindings = [];

	protected static $lastId;

	protected static $where;

	protected static $union;

	protected static $wheres=[];

	protected static $having;

	protected static $and;

	protected static $or;

	protected static $not;

	protected static $in;

	protected static $notin;

	protected static $select;

	protected static $selects = [];

	protected static $joins = [];

	protected static $join_type;

	protected static $on = [];

	protected static $orderBy;

	protected static $limit;

	protected static $offset;

	protected static $rows = 0;

	protected static $insert;

	protected static $update;

	protected static $delete;

	protected static $values = [];

 	protected static function knex(){

        try {

            self::$connection = new PDO('mysql:host='. getenv('DB_HOST') . ';dbname=' .getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));

						self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

						self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

						self::$connection->exec('SET NAMES utf8');

				} catch (PDOException $e) {
					
					include("vendor/Dedelang/View/Template/error.php");

					die();

				}

			return new self();

  }


}


 ?>
