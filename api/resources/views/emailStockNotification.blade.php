<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<style>

		body {
			width: 100%;
			padding: 0;
			margin: 0;
			font-family: 'Verdana';
			overflow-x: hidden;
		}
		
		.header {
			width: 100%;
			height: auto;
			padding: 5px 20px;
			background-color: #e8e8e8;
			margin: 0;
			color: white;
		}

		.body {
			padding: 5px 20px;
			color: grey;
		}

		.header h2 {
			font-size: 20px;
			color: #9d9d9d
		}

		h3 {
			font-size: 16px;
			margin-bottom: 30px;
			color: #9d9d9d;
		}

		h4 {
			font-size: 14px;
			color: #9d9d9d;
		}

		hr {
			margin: 25px 0;
			color: #e8e8e8;
		}

		ul {
			list-style: none;
			padding: 0;
			margin: 0;
		}

		li {
			margin-bottom: 10px;
			font-size: 14px;
			color: #9d9d9d;
		}

		p {
			color: #9d9d9d;
		}

	</style>

</head>
<body>

	<div class="header">		
		<h2>Notificación de stock</h2>
	</div>

	<div class="body">		

        <h3>El producto {{ $product['product'] }} tiene un stock restante de {{ $product['stock'] }} unidades.</h3>

	</div>
	
</body>
</html>