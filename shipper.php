<?php
$rows = $pdo->query("SELECT * FROM users WHERE Uid = $Uid");
foreach ($rows as $row){
    $hub = $row['Adress'];
}

?>
<!-- Display products in assigned hub -->
<div class="container-fluid bg-primary text-light p-2">
    <div class="row">
        <h1 class="col-auto">HUB: <?php echo $hub;?></h1>
        <div class="col-auto">
            <form method="post" action="main.php">
            <select name="hub" class="form-select">
                <option value="hub1">hub1</option>
                <option value="hub2">hub2</option>
                <option value="hub3">hub3</option>
            </select>
        </div>
        <div class="col-auto">
            <input type="submit" class="btn btn-warning" name="change_hub" value="Change Hub">
            </form>
        </div>
    </div>
</div>
<table class="table table-striped table-info">
<thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Distance (from hub to customer)</th>
        <th>Status</th>
        <th></th>
        <th></th>
    </tr>
</thead>
<tbody>

<?php
$Hid = 0;
if ($hub == 'hub1') {$Hid = 1;}
if ($hub == 'hub2') {$Hid = 2;}
if ($hub == 'hub3') {$Hid = 3;}
//Very long query. Get all information needed
$rows = $pdo->query("SELECT products.*, orders.Hid, orders.Pstatus,
                    hubs.Longitude as HubLong, hubs.Latitude as HubLat,
                    users.Longitude as UserLong, users.Latitude as UserLat 
                    FROM products JOIN orders ON orders.Pid = products.Pid JOIN hubs ON orders.Hid = hubs.Hid JOIN users ON orders.Cid = users.Uid 
                    WHERE orders.Hid = $Hid;");
foreach ($rows as $row){
    $Pname = $row['Pname'];
    $Pid = $row['Pid'];
    $Price = $row['Price'];
    $Status = $row['Pstatus'];
    if ($Status == 'Ordered') {$Status = 'Ready';}
    $hub_longitude = $row['HubLong'];
    $hub_latitude = $row['HubLat'];
    $customer_longitude = $row['UserLong'];
    $customer_latitude = $row['UserLat'];
    //Calculate distance from HUB to CUSTOMER
    $Distance = sqrt(pow($customer_longitude-$hub_longitude,2) + pow($customer_latitude-$hub_latitude,2));
    echo<<<GFG
        <tr>
        <form method="post" action="main.php">
            <td>
                $Pid
                <input type="hidden" name="Pid" value=$Pid>
            </td>
            <td>$Pname</td>
            <td>$Price</td>
            <td>$Distance</td>
            <td>$Status</td>
            <td><input type="submit" name="change_status" class="btn btn-success" value="Ship"></td>
            <td><input type="submit" name="change_status" class="btn btn-success" value="Cancel"></td>
        </form>
        </tr>
    GFG;
}

?>

</tbody>
</table>

