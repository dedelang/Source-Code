<?php

namespace Dedelang\Library;
use Dedelang\Engine\DB\Query;
class Authentication{

	protected static $table;

	protected static $where;

	// protect
	public function from($table){
		self::$table=$table;
		return new self();
	}

	public function condition($where){
		self::$where=$where;
		return new self();
	}

	public function values($where){
		self::$where=$where;
		return new self();
	}

	public function success(){
		Query::table('users')
					->insert('name','email')
					->values('dahlan','dahlan@gmail')
					->run();
		// return new self();
	}

	private function process(){
		$result= Query::table('users')
						->select('*')
						->where('id','=',1)
						->and('name','=','aidil')
						->getAll();
	}
}



 ?>
