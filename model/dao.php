<?php
	# CRUD operations
	require_once 'db-connection.php';
	class Dao extends DBConnection {

		public function executeQuery($query, $values) {

			$stmt=NULL;
			if ($values != NULL) {
				$stmt=$this->conn->prepare($query);
				$stmt->execute($values) or die('Failed to execute select on table ${table_name}..!');
			} else {
				$stmt=$this->conn->query($query) or die('Failed to execute select on table ${table_name}..!');
			}

			return $stmt->fetchAll(PDO::FETCH_ASSOC); 
		}

		public function executeSelect($table_name, $attributes, $values) {

			# build the query
			$query="SELECT * FROM ${table_name}";
			if ($attributes != NULL && $values != NULL) {
				$query.=" WHERE ";
				foreach ($attributes as $idx=>$attribute) {
					$query.=" ${attribute} = ?";
					if ($idx < count($attributes) - 1) {
						$query.=" AND";
					}
				}
			}

			# execute query
			$stmt=$this->conn->prepare($query);
			$stmt->execute($values) or die('Failed to execute select on table ${table_name}..!');
			
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}
?>