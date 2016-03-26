<?php
	require "../vendor/autoload.php";

	$app = new \Slim\App;


	/**
	* Add items to stock
	* @param product_code
	* @param product_name
	* @param amount
	*/
	$app->post("/stock/add", function ($request, $response, $args) {
		$body = $request->getParsedBody();

		$product_code = $body["product_code"];
		$product_name = $body["product_name"];
		$amount = $body["amount"];

		$message = [
			"product_code" => $product_code,
			"amount" => $amount,
			"message" => "{$product_name} has been added {$amount} items to stock"
		];

	    return $response->withJson($message);
	});

	/**
	* Remove items from stock
	* @param product_code
	* @param product_name
	* @param amount
	*/
	$app->post("/stock/remove", function ($request, $response, $args) {
		$body = $request->getParsedBody();

		$product_code = $body["product_code"];
		$product_name = $body["product_name"];
		$amount = $body["amount"];

		$message = [
			"product_code" => $product_code,
			"amount" => $amount,
			"message" => "{$product_name} has been removed {$amount} items from stock"
		];

	    return $response->withJson($message);
	});

	$app->run();

?>