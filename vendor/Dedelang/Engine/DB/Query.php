<?php

namespace Dedelang\Engine\DB;

use Dedelang\Engine\DB\Knex;

class Query extends Knex{


	public static function table($table){

		self::$table = $table;

		return new self();

	}
	public static function select(){

		self::$select = 'SELECT '.implode(', ', func_get_args());

		return new self();
	}

	public static function insert(){

		$sql ="INSERT INTO ".self::$table;

		$sql .=' ('.implode(', ', func_get_args()).') ';

		$num = count(func_get_args());

		$sql .='VALUES ('.substr(str_repeat("?,", $num),0,-1).')';

		self::$insert = $sql;

		return new self();

	}

	public static function update(){

		$sql = "UPDATE ".self::$table." SET ";

		$sql .=implode('=?, ', func_get_args()).'=?';

		self::$update = $sql;

		return new self();

	}

  public static function join($table,$join_type=''){

    self::$joins[] = $table;

    self::$join_type = $join_type;

    return new self();

  }

  public static function on($value){

    self::$on[] = $value;

    return new self();

  }

	public static function delete($table){

		$sql = "DELETE FROM ".$table;

		self::$delete = $sql;

		return new self();

	}

	public static function values(){

		self::$values=func_get_args();

		return new self();

	}



	public static function getOne(){

      try {
        self::knex();

				(self::$union ? $sql = self::$union :((self::$query ? $sql = self::$query : $sql = self::statement())));

        $query = self::$connection->prepare($sql);

        $query->execute(self::$values);

        $result = $query->fetch();

        self::$rows = $query->rowCount();

				self::reset();
				
        return $result;

      } catch (\Exception $e) {

        self::error_result('error',$e);

      }



	}

  public static function getAll(){

    try {
      self::knex();

      (self::$union ? $sql = self::$union :((self::$query ? $sql = self::$query : $sql = self::statement())));

    	$query = self::$connection->prepare($sql);

      $query->execute(self::$values);

    	$result = $query->fetchAll();

      self::$rows = $query->rowCount();

			self::reset();

      return $result;

    } catch (\Exception $e) {

      self::error_result('error',$e);

    }


  }

  public static function run(){

		self::knex();

		$query='';

		if(self::$insert){

			$query = self::$insert;

		}elseif(self::$update){

			$query = self::$update;

			$query .=self::$where;

		}elseif(self::$delete){

			$query = self::$delete;

			$query .=self::$where;

		}

    try {
      $query = self::$connection->prepare($query);

      $query->execute(self::$values);

      self::$rows = $query->rowCount();

      self::reset();

  		return $query;

    } catch (\Exception $e) {

      self::error_result('error',$e);

    }

	}

  public static function checkDuplicate(){

    try {
      self::knex();

      $bindings = func_get_args();

      self::$where = ' WHERE '.$bindings[0].$bindings[1].'?';

      self::$select = "SELECT * ";

      $sql = self::statement();

      $query = self::$connection->prepare($sql);

  		$query->execute([$bindings[2]]);

  		$result = $query->fetch();

      self::$rows = $query->rowCount();

      (self::rows()>1 ? self::error_result('duplicate',$e) : '');

      self::reset();

      return new self();

    } catch (\Exception $e) {

      self::error_result('error',$e);

    }


  }

  public static function where(){

		$bindings = func_get_args();

		self::$where = ' WHERE '.$bindings[0].' '.$bindings[1].' '.'?';

    self::$values[] = $bindings[2];

		return new self();

	}

  public static function wheres(){

		$bindings = func_get_args();

    foreach ($bindings as $value) {

      self::$wheres[] = explode(",",$value);

    }

    $x=0;

    $sql='';

    $t_union = count(self::$tables)-1;

    foreach (self::$selects as $key => $value) {


       $sql .= " SELECT ".$value." FROM ".self::$tables[$x];

       $sql .=" WHERE ".self::$wheres[$x][0].self::$wheres[$x][1]."?";

       $sql .=" UNION ".self::$tables[$t_union];

       self::$values[]=self::$wheres[$x][2];

       $x++;

    }

    (self::$tables[$t_union]=='' ? $sql = mb_substr($sql, 0, -6) : $sql = mb_substr($sql, 0, -10));

    self::$union = $sql;

		return new self();

	}
  public static function query($query){

    self::$query = $query;

    return new self();
  }

  public static function selects(){

    self::$selects = func_get_args();

    return new self();
  }

  public static function union(){

    self::$tables = func_get_args();

    (count(func_get_args())<3 ? self::$tables[2] ='' : '');

    return new self();
  }

  public static function having(){

    $bindings = func_get_args();

		self::$where = ' HAVING '.$bindings[0].' '.$bindings[1].' '.'?';

    self::$values[] = $bindings[2];

		return new self();

  }

  public static function in(){

    $bindings = func_get_args();

    $value = str_replace(",","','",$bindings[1]);

    self::$in = " WHERE ".$bindings[0]." IN ('".$value."')";

    return new self();
  }

  public function notin(){

    $bindings = func_get_args();

    $value = str_replace(",","','",$bindings[1]);

    self::$notin = " WHERE ".$bindings[0]." NOT IN ('".$value."')";

    return new self();

  }
	public static function and(){

		$bindings = func_get_args();

		self::$and = ' AND '.$bindings[0].$bindings[1].'?';

    self::$values[] = $bindings[2];

		return new self();

	}

  public static function or(){

		$bindings = func_get_args();

		self::$or = ' OR '.$bindings[0].$bindings[1].'?';

    self::$values[] = $bindings[2];

		return new self();

	}

  public static function not(){

		$bindings = func_get_args();

		self::$not = ' NOT '.$bindings[0].$bindings[1].'?';

    self::$values[] = $bindings[2];

		return new self();

	}

  public static function rows(){

    return self::$rows;

  }
  public static function limit($limit){

		self::$limit = ' LIMIT '.$limit;

		return new self();

	}

  public static function offset($offset){

    self::$offset = ' OFFSET '.$offset;

    return new self();

  }

  public static function orderBy($orderBy){

    self::$orderBy = ' ORDER BY '.$orderBy;

    return new self();

  }

	public static function execute($sql){
    try {
      self::knex();

  		$query = self::$connection->prepare($sql);

      $query->execute(self::$values);
      
      self::reset();

  		return $query;

    } catch (\Exception $e) {

      self::error_result('error',$e);

    }

	}

  public static function display(){

    echo self::statement();

  }

	private static function statement(){

		$sql ='';

		(self::$select ? $sql .=self::$select:'');

		(self::$table ? $sql .=' FROM '.self::$table:'');

		(self::$joins ? $sql .=self::statement_join():'');

		(self::$where ? $sql .=self::$where:'');

		(self::$having ? $sql .=self::$having:'');

		(self::$in ? $sql .=self::$in:'');

		(self::$notin ? $sql .=self::$notin:'');

		(self::$and ? $sql .=self::$and:'');

		(self::$or ? $sql .=self::$or:'');

		(self::$not ? $sql .=self::$not:'');

		(self::$orderBy ? $sql .=self::$orderBy:'');

		(self::$limit ? $sql .=self::$limit:'');

		(self::$offset ? $sql .=self::$offset:'');

		return $sql;

	}

  private static function statement_join(){

    $x=0;

    $sql='';

    foreach (self::$joins as $value) {

      $sql .= ' '.self::$join_type.' JOIN '.$value.' ';

      $sql .= ' ON ('.self::$on[$x].')';

      $x++;

    }

    return $sql;

  }

  private static function error_result($file,$e){

      include("vendor/Dedelang/View/Template/error.php");

      die();
  }

  private static function reset(){

    self::$table = null;

    self::$tables=[];

    self::$query= null;

    self::$data = [];

    self::$bindings = [];

    self::$lastId= null;

    self::$where= null;

    self::$union=null;

    self::$wheres=[];

    self::$having=null;

  	self::$and=null;

    self::$or= null;
    
    self::$not=null;

    self::$in=null;

    self::$notin=null;

    self::$select= null;

    self::$selects= [];

    self::$joins= [];

    self::$join_type=null;

    self::$on=[];

    self::$orderBy= null;

    self::$limit= null;

    self::$offset= null;

    self::$rows = 0;

  	self::$insert= null;

  	self::$update= null;

  	self::$delete= null;

  	self::$values = [];
  }
}
 ?>
