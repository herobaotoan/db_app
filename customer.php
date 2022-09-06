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
    <input type="submit" class="btn btn-sm btn-primary" name="load" value="Page">
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
$rows = $pdo->query("SELECT * FROM products ORDER BY Pid DESC LIMIT $PageNo;");
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
    $document = $collection->find()->toArray();
    for ($i = 0; $i < count($document); $i++){
        if ($document[$i]['Pid'] == $id){
            $newly_added = false;
            $count = 0;
            foreach($document[$i] as $key => $value)
            {
                if ($count > 1){ //Skip the first 2 keys and values (which is _id an Pid)
                    echo<<<GFG
                    <td>$key: $value</td>
                    GFG;
                } $count++;
            }
        }
    }
    echo "</tr>";
}
echo "</tbody></table>";

?>
