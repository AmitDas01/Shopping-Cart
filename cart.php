<?php
    session_start();
    $database_name = "product_details";
    $conn = mysqli_connect("localhost","root","",$database_name);
    if (isset($_POST["add"])){
        if (isset($_SESSION["cart"])){
            $item_array_id = array_column($_SESSION["cart"],"product_id");
            if (!in_array($_GET["id"],$item_array_id)){
                $count = count($_SESSION["cart"]);
                $item_array = array(
                    'product_id' => $_GET["id"],
                    'item_name' => $_POST["hidden_name"],
                    'product_price' => $_POST["hidden_price"],
                    'item_quantity' => $_POST["quantity"],
                );
                $_SESSION["cart"][$count] = $item_array;
                echo '<script>window.location="cart.php"</script>';
            }else{
                echo '<script>alert("Product is already Added to Cart")</script>';
                echo '<script>window.location="cart.php"</script>';
            }
        }else{
            $item_array = array(
                'product_id' => $_GET["id"],
                'item_name' => $_POST["hidden_name"],
                'product_price' => $_POST["hidden_price"],
                'item_quantity' => $_POST["quantity"],
            );
            $_SESSION["cart"][0] = $item_array;
        }
    }
    if (isset($_GET["action"])){
        if ($_GET["action"] == "delete"){
            foreach ($_SESSION["cart"] as $keys => $value){
                if ($value["product_id"] == $_GET["id"]){
                    unset($_SESSION["cart"][$keys]);
                    echo '<script>alert("Product has been Removed...!")</script>';
                    echo '<script>window.location="cart.php"</script>';
                }
            }
        }
    }
?>


<!DOCTYPE html>
<html>
	<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shopping Cart</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<style>
			@import url('https://fonts.googleapis.com/css?family=Titillium+Web&display=swap');
			*{
				font-family: "Titillium Web", sans-serif;
			}
			.product{
				border: 1px solid black;
				margin: -1px 19px 3px -1px;
				padding: 10px;
				text-align: center;
				background-color: #efefef;			
			}
			table,th,tr{
				text-align: center;
			}
			.title2{
				text-align: center;
				padding:2%;
				color: #66afe9;
				background-color: #efefef;
			}
			h2{
				text-align: center;
				color: #66afe9;
				background-color: #efefef;
				padding: 2%;
			}
			table th{
				background-color: #efefef;
			}
		</style>
	</head>
	<body>
		<div class="container">
				<?php
					$sql = "SELECT * FROM product ORDER BY id ASC";
					$result = mysqli_query($conn, $sql);
					if(mysqli_num_rows($result) > 0){

						while($row = mysqli_fetch_assoc($result)){

				?>

			<div class="col-md-3">
				<form action="cart.php?action=add&id=<?php echo $row["id"] ?>" method="POST">
					<div class="product">
						<img src="<?php echo $row["image"] ?>" class="img-responsive" style="height: 150px; width: 300px">
						<h5 class="text-info"><?php echo $row["name"] ?></h5>
						<h5 class="text-danger"><?php echo $row["price"] ?></h5>					
						<input type="text" name="quantity" class="form-control" value="1">
						<input type="hidden" name="hidden_name" value="<?php echo $row["name"] ?>">
						<input type="hidden" name="hidden_price" value="<?php echo $row["price"] ?>">
						<input type="submit" name="add" class="btn btn-success" value="Add to Cart" style="margin-top: 5px">
					</div>
				</form>
			</div>

		<?php
				}
			}
		?>

		<div style="clear: both"></div>
		<h3 class="title2">Shopping Cart Details</h3>
		<div class="table-responsive">
			<table class="table table-bordered">
			<tr>
				<th width="30%">Product Name</th>
				<th width="10%">Quantity</th>
				<th width="15%">Price</th>
				<th width="15%">Total Price</th>
				<th width="30%">Remove Item</th>
			</tr>

			<?php
				if(!empty($_SESSION["cart"])){
					$total = 0;
					foreach ($_SESSION["cart"] as $key => $value){
			?>
			<tr>
				<td><?php echo $value["item_name"]; ?></td>
				<td><?php echo $value["item_quantity"]; ?></td>
				<td>$ <?php echo $value["product_price"]; ?></td>
				<td>$ <?php echo number_format($value["item_quantity"] * $value["product_price"], 2); ?></td>
				<td><a href="cart.php?action=delete&id=<?php echo $value["product_id"]; ?>"><span class="text-danger">Remove Item</span></a></td>
			</tr>
			<?php
				$total = $total + ($value["item_quantity"] * $value["product_price"]);
			}
			?>
			<tr>
				<td colspan="3" align="right">Total</td>
				<th align="right">$ <?php echo number_format($total, 2); ?></th>
			</tr>
			<?php
				}	
			?>
			</table>
		</div>

		</div>

		
		
	</body>
</html>