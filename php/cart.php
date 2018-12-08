<?php	
	error_reporting(0);
	function reindex(&$the_array) { 
		if(!empty($the_array)){
			$temp = $the_array; 
			$the_array = array(); 
			foreach($temp as $value) { 
				$the_array[] = $value; 
			}		
		}
		else{
			$the_array='';
		}
	}  
	require('../config/header.php');
	$content=file_get_contents('../html/cart.html');
	if(is_logged()){
	
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			if (isset($_POST['empty']))
				$_SESSION['cart'] = array(array());
				$_SESSION['cart'] = array_filter($_SESSION['cart']);
		}

		if($_SERVER['REQUEST_METHOD']=='GET'){
			//Row delete from Cart
			if (isset($_GET['itemNum'])){
				$itemNumber = $_GET['itemNum'];
				$cartSize = count($_SESSION['cart']);
				for($i=0; $i<$cartSize;$i++){
						if($_SESSION['cart'][$i][0]==$itemNumber){
							unset($_SESSION['cart'][$i]);
							reindex($_SESSION['cart']);
					}
				}
			}
			if(isset($_GET['quantity'])){
				$quantity=$_GET['quantity'];
				$itemNumber = $_GET['product'];
				$cartSize = count($_SESSION['cart']);
				
				for($i=0; $i<$cartSize;$i++){
					if($_SESSION['cart'][$i][0]==$itemNumber){
						$_SESSION['cart'][$i][1]=$quantity;
					}
					
				}
				var_dump($_SESSION['cart'][$i][1]);
			}
		}

		if (isset($_SESSION['cart'])){ $cart = $_SESSION['cart'];
			if (empty($cart)){
				$cartTable = 'Cart is empty!';
			}
			else {
				$cartTable = '<h2>Cart</h2>
					<form method="post" action="cart.php">
					<table class="table table-striped">
					<thead><tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Delete</tr> </thead>';
					foreach($cart as $itemNumber){	
						$sql = "SELECT * FROM warehouse WHERE itemNumber='$itemNumber[0]'";
						$result = mysqli_query($database, $sql);
						$row = mysqli_fetch_row($result);
						$cartTable .= '<tr>
						<td>'.$row[1].'</td>
						<td>'.$row[3].' Ft</td>
						<td><input id='.$itemNumber[0].' class="cart" type="text" value="'.$itemNumber[1].'" size=3 name="quantity"></td>	
						<td><a href="cart.php?itemNum='.$itemNumber[0].'">X</a></td>
						</tr>';
					}
				$totalprice=0;
				$totalquantity=0;
				$cartSize = count($_SESSION['cart']);
				
				for($i=0; $i<$cartSize; $i++){
					$totalquantity+=$_SESSION['cart'][$i][1];
				}
				for($i=0; $i<$cartSize; $i++){
					$qt=$_SESSION['cart'][$i][1];
					$id=$_SESSION['cart'][$i][0];
					$sql = "SELECT * FROM warehouse WHERE itemNumber='$id'";
					$result = mysqli_query($database, $sql);
					$row = mysqli_fetch_row($result);
					$totalprice+=$row[3]*$qt;
				
				
				}
				$cartTable.='<tr><td>Total Price</td><td>'.$totalprice.' Ft</td><td>'.$totalquantity.'</td><td></td></tr>';
				
				$cartTable .= '</table>';
				if (!empty($cart)){
					$cartTable .= '
						<input class="btn btn-group-vertical btn-primary" type="submit" name="empty" value="Empty cart">
						<input class="btn btn-group-vertical btn-primary" type="submit" name="order" value="Order">';
				}
				if(isset($_POST['order'])){
					$sql="INSERT INTO orders(buyer,orderDate,shipped) VALUES('".$_SESSION['username']."',NOW(),'0')";
					$result=mysqli_query($database,$sql);
					$last_id = mysqli_insert_id($database);
					foreach($cart as $rows){
						$sql="INSERT INTO orderitems(orderID,itemID,quantity) VALUES('$last_id','".$rows[0]."','".$_POST["quantity"]."')";
						$result=mysqli_query($database,$sql);
					}
					$_SESSION['cart'] = array(array());
					$_SESSION['cart'] = array_filter($_SESSION['cart']);
					$cartTable='<h2><br><br><br>Successful purchase</h2>';
					
				}
			}
		}
	}	
	else{
		$cartTable = '<a href="../php/login.php">Please login to buy!</a>';
	}
	
	$content = str_replace('::cart',$cartTable,$content);
	require('../config/footer.php');
?>