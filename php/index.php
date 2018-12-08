	<?php
	require('../config/header.php');
	$content=file_get_contents('../html/homepage.html');
	if (!isset($_SESSION['cart']))
	{
		$_SESSION['cart'] = array(array());
		$_SESSION['cart']=array_filter($_SESSION['cart']);
	}
	if(($_SERVER['REQUEST_METHOD']=='GET')){
		if(isset($_GET['cart'])){
			if((empty($_SESSION['cart']))){
				$newCart=array($_GET['cart'],1);
				$_SESSION['cart'][] = $newCart;
			}
			else{
				$cartSize = count($_SESSION['cart']);
				$isin=false;;
				for($i=0; $i<$cartSize; $i++){
					if($_GET['cart']==$_SESSION['cart'][$i][0]){
						$isin=true;
					}
				}
				if(!$isin){
						$newCart=array($_GET['cart'],1);
						$_SESSION['cart'][] = $newCart;
				}
				
			}
		}
		if(isset($_GET['page'])){
			$Itemlimit=(($_GET['page'])-1)*9+1;
		}
		else{
			$Itemlimit=0;
		}
		if (isset($_GET['sortitem'])){
			$sortitem = $_GET['sortitem'];
		}
		else{
			$sortitem = 'itemName';
		}
		if (isset($_GET['listorder'])){
			$listorder = $_GET['listorder'];
		}
		else{
			$listorder = 'ASC';
		}
		$sql = "SELECT COUNT(itemNumber) FROM warehouse WHERE itemQuantity>0";
		$result = mysqli_query($database, $sql);
		$count = mysqli_fetch_row($result);
		$count = $count[0];
		
		$rowNumber=9;
		
		$sql="SELECT * FROM warehouse WHERE itemQuantity>0 ORDER BY $sortitem $listorder LIMIT $Itemlimit,$rowNumber";
		$result=mysqli_query($database,$sql);
		$shopItems='<div class="col-md-12">
				<div class="container">
				 <div class="row">
				 	<div id="filter" align="center">
					<br>
				<form id="filter" method="get" action="index.php">
				<br>
				<br>
				<br>
				<select class="form-control filter" name="listorder">
					<option value="ASC">-</option>
					<option value="ASC">A-Z</option>
					<option value="DESC">Z-A</option>
				</select>
				<select class="form-control filter" name="sortitem">
					<option value="itemName">-</option>
					<option value="itemName" >Name</option>
					<option value="itemPrice">Price</option>
				</select>
				<input class="btn btn-group-vertical btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off" value="Apply Filter" type="submit">
				</form>
				</div>';
		while($row=mysqli_fetch_assoc($result)){
			if(is_logged()){
			$shopItems.= '<div class="col-md-4" align="center">
							<h4>'.$row['itemName'].'</h4>
							<p class="img"><img align="center" src='.$row['itemPic'].'></p>
							<p>'.$row['itemPrice'].' Ft</p>
							<p><a id='.$row['itemNumber'].' href="index.php?cart='.$row['itemNumber'].'"><input class="btn btn-group-vertical btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off" value="Buy" type="submit"></a></p>
					</div>';
			}
			else{
				$shopItems.='<div class="col-md-4" align="center">
							<h4>'.$row['itemName'].'</h4>
							<p class="img"><img align="center" src='.$row['itemPic'].'></p>
							<p>'.$row['itemPrice'].'</p>
							<p><a href="../php/login.php">Please login to buy</a></p>
					</div>';
			}
		}
		$pages = ceil($count/$rowNumber);
		$shopItems.='</div></div></div>';
		for($i=1; $i<($pages+1); $i++){
			$shopItems .= "<div align='center'><a class='pageswitch' href='index.php?page=$i&listorder=$listorder&sortitem=$sortitem&rowNumber=$rowNumber'>$i</a></div";
		}
	}
	else{
		
	}
	$content=str_replace("::items",$shopItems,$content);
	require('../config/footer.php');
?>