<?php
?>
<div class="container-fluid bg-primary text-light p-2">
    <h1>List of Products</h1>
</div>
<div class="row m-2">
    <div class="col-auto">
        <form method="post" action="main.php">
        <select name="ProductNo">
            <option selected value="1">1</option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
        </select>
        <input type="submit" class="btn btn-sm btn-primary" name="PageNo" value="Page">
        </form>
    </div>
    <div class="col-2">
        <form method="post" action="main.php">
            <input type="text" style="width: 50%" placeholder="Name" name="name">
            <input type="submit" class="btn btn-sm btn-primary" name="search_name" value="Search">
        </form>
    </div>
    <div class="col-3">
        <form method="post" action="main.php">
            <input type="number" style="width: 30%" placeholder="Price <=" name="price1">
            <input type="number" style="width: 30%" placeholder="<= Price" name="price2">
            <input type="submit" class="btn btn-sm btn-primary" name="search_price" value="Search">
        </form>
    </div>
    <div class="col-3">
        <form method="post" action="main.php">
            <input type="text" style="width: 30%" placeholder="Key" name="key">
            <input type="text" style="width: 30%" placeholder="Value" name="value">
            <input type="submit" class="btn btn-sm btn-primary" name="search_attribute" value="Search">
        </form>
    </div>
    <div class="col-3">
        <form method="post" action="main.php">
            <label for="Distance" class="form-label">Distance < </label>
            <input type="number" style="width: 30%" placeholder="Ex: 1000" name="distance">
            <input type="submit" class="btn btn-sm btn-primary" name="search_distance" value="Search">
        </form>
    </div>
</div>


<!-- Display All Products -->

<table class="table table-striped table-info">
<thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Status</th>
        <th>Vendor</th>
        <th>Distance</th>
        <th></th>
        <th>Extra info</th>
    </tr>
</thead>
<tbody>

<?php
$longs = $pdo->query("SELECT Longitude FROM users WHERE $Uid = Uid;");
$lats = $pdo->query("SELECT Latitude FROM users WHERE $Uid = Uid;");
foreach($longs as $long){
    $long_value = $long['Longitude'];
}
foreach($lats as $lat){
    $lat_value = $lat['Latitude'];
}

//Retrieve Longitute & Latitude values from hubs
$hubs_longs = $pdo->query("SELECT Longitude from hubs;");
$hubs_lats = $pdo->query("SELECT Latitude from hubs;");
$hubs_long_value = array();
$hubs_lat_value = array();
foreach($hubs_longs as $hubs_long){
    array_push($hubs_long_value,$hubs_long['Longitude']);
}
foreach($hubs_lats as $hubs_lat){
    array_push($hubs_lat_value,$hubs_lat['Latitude']);
}


$rows = $pdo->query("SELECT *, UName, Longitude, Latitude FROM products JOIN users ON Vid = Uid $where $and ORDER BY Pid DESC LIMIT $PageNo;");
//Descending order is to display *Most recently* added products first
    foreach ($rows as $row) {
    //Get product's status
    $id = $row['Pid'];
    $status = 'Available';
    $rows2 = $pdo->query("SELECT * FROM orders WHERE Pid = $id;");
    foreach ($rows2 as $row2) {
        if ($row2['Pid'] == $id){
            $status = $row2['Pstatus'];
        }
    }
    $Pname = $row['Pname'];
    $price = $row['Price'];
    //Get product's vendor info
    $vendor_name = $row['UName'];
    $vendor_long = $row['Longitude'];
    $vendor_lat = $row['Latitude'];
    $product_distance = sqrt(pow($long_value-$vendor_long,2) + pow($lat_value-$vendor_lat,2));
    // Calculation for shortest distance from product to hub (to send to nearest hub if customer buy)
    $distance_value = array();
    for ($x = 0; $x < 3; $x++) {
        array_push($distance_value,sqrt(pow($vendor_long-$hubs_long_value[$x],2) + pow($vendor_lat-$hubs_lat_value[$x],2))) ;
    }
    $nearest_hub = array_search(min($distance_value), $distance_value) + 1;
    //Get product's extra attributes
    $attributeCheck = false; //For search attribute engine
    $keys = array();
    $values = array();
    $document = $collection->find()->toArray();
    for ($i = 0; $i < count($document); $i++){
        if ($document[$i]['Pid'] == $id){
            $newly_added = false;
            $count = 0;
            foreach($document[$i] as $key => $value)
            {
                if ($count > 1){
                    array_push($keys, $key);
                    array_push($values, $value);
                    if ($key == $keySearch && $value == $valueSearch){ $attributeCheck = true; } //For search attribute engine
                } $count++;
            }
        }
    }
    if ($product_distance > $distanceSearch) {$attributeSearch = true;}
    if (!$attributeSearch || ($attributeSearch && $attributeCheck)){
        echo<<<GFG
            <tr>
            <form method="post" action="main.php">
                <td>
                    $id
                    <input type="hidden" name="id" value=$id>
                </td>
                <td>$Pname</td>
                <td>$price</td>
                <td>
                    $status
                    <input type="hidden" name="status" value=$status>
                    <input type="hidden" name="hubNo" value=$nearest_hub>
                </td>
                <td>$vendor_name</td>
                <td>$product_distance</td>
                <td><input type="submit" name="Buy_product" class="btn btn-success" value="Buy"></td>
            </form>
        GFG;
        for ($i = 0; $i < count($keys); $i++){
            $key = $keys[$i];
            $value = $values[$i];
            echo "<td>$key: $value</td>";
        }
        echo "</tr>";
    }
}
echo "</tbody></table>";

?>
