<?php
?>
<div class="container-fluid bg-primary text-light p-2">
    <h1>List of Products</h1>
</div>
<div class="row m-2">
    <div class="col-auto">
        <form method="post" action="main.php">
        <select name="PageNo">
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
</div>


<!-- Display All Products -->

<table class="table table-striped table-info">
<thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Status</th>
        <th></th>
        <th>Extra info</th>
    </tr>
</thead>
<tbody>

<?php
$rows = $pdo->query("SELECT * FROM products $where $and ORDER BY Pid DESC LIMIT $PageNo;");
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
                </td>
                <td><input type="submit" name="C" class="btn btn-success" value="Buy"></td>
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
