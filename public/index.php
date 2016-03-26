<?php
	require "../vendor/autoload.php";
	require "./configs/Database.php";
	require "./models/Stock.php";

	// connection
	$connectionString = \App\Configs\Database::$engine . 
						":host=" . \App\Configs\Database::$host . 
						";port=" . \App\Configs\Database::$port . 
						";dbname=" . \App\Configs\Database::$database . 
						";charset=UTF8;";
	$connection = new PDO(
		$connectionString,
		\App\Configs\Database::$username,
		\App\Configs\Database::$password
	);
	$connection->query("SET NAMES utf8;");

	// models
	$Stock = new \App\Models\Stock($connection);


	// app
	$app = new \Slim\App([
		'Stock' => $Stock
	]);

	/**
	* Add items to stock
	* @param product_code
	* @param product_name
	* @param amount
	*/
	$app->post("/stock/add", function ($request, $response, $args) {
		try {
			$body = $request->getParsedBody();

			$product_code = $body["product_code"];
			$product_name = $body["product_name"];
			$amount = $body["amount"];

			if($stock = $this->get('Stock')->add($product_code, $product_name, $amount)) {

				$message = [
					"currentStock" => $stock,
					"message" => "{$amount} items of {$product_name} has been added to stock"
				];
		   		
		   		return $response->withJson($message);
				
			} else {
				throw new Exception();
			}
		   	
		} catch (Exception $ex) {
			$message = [
				"message" => "Product cannot be added. Please try again"
			];
	   		
	   		return $response->withStatus(500)->withJson($message);
		}
	});

	/**
	* Remove items from stock
	* @param product_code
	* @param product_name
	* @param amount
	*/
	$app->post("/stock/remove", function ($request, $response, $args) {
		try {
			$body = $request->getParsedBody();

			$product_code = $body["product_code"];
			$product_name = $body["product_name"];
			$amount = $body["amount"];

			if($stock = $this->get('Stock')->remove($product_code, $product_name, $amount)) {

				$message = [
					"currentStock" => $stock,
					"message" => "{$amount} items of {$product_name} has been added to stock"
				];
		   		
		   		return $response->withJson($message);
				
			} else {
				throw new Exception();
			}
		   	
		} catch (Exception $ex) {
			$message = [
				"message" => "Product cannot be removed. Please try again"
			];
	   		
	   		return $response->withStatus(500)->withJson($message);
		}
	});

	$app->run();

?>