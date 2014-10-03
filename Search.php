<?php
   $connection = mysqli_connect("localhost", "root", "root25", "winestore");
    /*
        localhost - it's location of the mysql server, usually localhost
        root - your username
        third is your password
         
        if connection fails it will stop loading the page and display an error
    */
     
   
    /* tutorial_search is the name of database we've created */
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Search results</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<?php
		$winename = $_GET['winename'];
		$wineregion = $_GET['region_name'];
		$wineryname = $_GET ['wineryname'];
		$minyear = $_GET ['minyear'];
		$maxyear = $_GET ['maxyear'];
		$noWine = $_GET ['nowines'];
		$noCust = $_GET ['nocustomer'];
		$mincost = $_GET ['mincost'];
		$maxcost = $_GET ['maxcost'];
		
		
    //$query = $_GET['name'];      
    
		if ($minyear == NULL)
			$minyear = "1970";
		
		if ($maxyear == NULL)
			$maxyear = "1999";
		
		if ($mincost == NULL)
			$mincost = "5";
		
		if ($maxcost == NULL)
			$maxcost = "30";
			
		if($wineregion == "All")
			$wineregion = "";	
			
		#if ($noWine== NULL)
			#$noWine = "999";
         
        /*$raw_results = mysqli_query($connection, "SELECT * FROM wine
            WHERE (`wine_name` LIKE '%".$query."%')") or die(mysql_error());*/
			
			/*$raw_result = "Select * FROM wine INNER JOIN wine_type ON(wine.wine_type = wine_type.wine_type_id)";*/
			
			$raw_result = "	SELECT DISTINCT customer.cust_id, wine.wine_id, wine.wine_name, grape_variety.variety, 
			wine.wine_type, wine.year,inventory.on_hand, wine.winery_id, 
			wine.description, winery.winery_name, region.region_name, 
			inventory.cost, count(wine.wine_id) AS cust
				from wine 
						INNER JOIN wine_variety
							ON wine_variety.wine_id = wine.wine_id
						INNER JOIN grape_variety
							ON grape_variety.variety_id = wine_variety.variety_id
						INNER JOIN winery
							ON wine.winery_id = winery.winery_id
						INNER JOIN region
							ON winery.region_id = region.region_id
						INNER JOIN inventory 
							ON wine.wine_id = inventory.wine_id
						INNER JOIN items
							ON wine.wine_id = items.wine_id
						INNER JOIN customer	
							ON items.cust_id = customer.cust_id
					
				
				WHERE wine.wine_name like '%".$winename."%' 
				and wine.year between '". $minyear ."' and '". $maxyear . "'
				and region.region_name like '%".$wineregion."%'
				and winery.winery_name like '%".$wineryname."%'
				and inventory.on_hand >= '". $noWine . "'
				#and customer.cust_id >= '".$noCust."'
				and inventory.cost between '". $mincost ."' and '". $maxcost . "'
				
				
				GROUP BY wine.wine_id 
				HAVING count(wine.wine_id) >= '$noCust'
				ORDER BY wine.wine_id
				
				";
				//"SELECT * FROM `wine` WHERE wine.wine_name like '%".$winename."%' Order By wine.year ASC, wine.wine_name";
				

											
							
             
            $raw_results = mysqli_query ($connection, $raw_result);
			echo "<table border= '1'>
					<tr>
						<td>Wine ID</td>
						<td>Wine Name</td>
						<td>Wine Variety</td>
						<td>Year</td>
						<td>Winery Name</td>
						<td>Region Name</td>
						<td>Stock</td>
						<td>No. of Customer</td>
						<td>Cost</td>
					</tr>" ;
					
            while($row = mysqli_fetch_array($raw_results)){
				echo "<tr>";
				echo "<td>".$row['wine_id']."</td>";
				echo "<td>".$row['wine_name']."</td>";
				echo "<td>".$row['variety']."</td>";
				echo "<td>".$row['year']."</td>";
				echo "<td>".$row['winery_name']."</td>";
				echo "<td>".$row['region_name']."</td>";
				echo "<td>".$row['on_hand']."</td>";
				echo "<td>".$row['cust']."</td>";
				echo "<td>".$row['cost']."</td>";
				echo "</tr>";
                          
            }
             echo "</table>";
			 
			 mysqli_close($connection);
        
       
         
    
   
?>
</body>
</html>
