<?php

namespace App\Models;

class Stock {

	private $connection = null;

	function __construct(&$connection) {
		$this->connection = &$connection;
	}

	public function add($product_code, $product_name, $amount) {
		if(!$this->findByCode($product_code) && !$this->create($product_code, $product_name)) {
			return false;
		}
		
		if($this->addItem($product_code, $amount)) {
			return $this->findByCode($product_code);
		} else {
			return false;
		}
	}

	public function remove($product_code, $product_name, $amount) {
		if(!$this->findByCode($product_code) && !$this->create($product_code, $product_name)) {
			return false;
		}

		if($this->removeItem($product_code, $amount)) {
			return $this->findByCode($product_code);
		} else {
			return false;
		}
	}

	private function findByCode($product_code) {
		$statement = $this->connection->prepare(
            "SELECT * from stocks where product_code = :product_code"
        );

        $statement->execute([
        	':product_code' => $product_code
    	]);

    	$result = $statement->fetch(\PDO::FETCH_ASSOC);

        return $result;
	}

	private function create($product_code = "", $product_name = "", $amount = 0) {
		$statement = $this->connection->prepare(
            "INSERT into stocks (product_code, product_name, amount) values(:product_code, :product_name, :amount)"
        );

        return $statement->execute([
        	":product_code" => $product_code,
        	":product_name" => $product_name,
        	":amount" => $amount
    	]);
	}

	private function addItem($product_code, $amount) {
		$statement = $this->connection->prepare(
            "UPDATE stocks set amount = amount + :amount where product_code = :product_code"
        );

        return $statement->execute([
        	":product_code" => $product_code,
        	":amount" => $amount
    	]);
	}

	private function removeItem($product_code, $amount) {
		$statement = $this->connection->prepare(
            "UPDATE stocks set amount = amount - :amount where product_code = :product_code"
        );

        return $statement->execute([
        	":product_code" => $product_code,
        	":amount" => $amount
    	]);
	}
}

?>