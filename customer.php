<?php
//Display All Products
echo <<<GFG
<div class="container-fluid bg-primary text-light p-2">
<h1>List of Products</h1>
</div>
<table class="table table-striped table-info">
<thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Status</th>
        <th></th>
    </tr>
</thead>
<tbody>
GFG;
$rows = $pdo->query("SELECT * FROM products ORDER BY Pid DESC;");
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
            </tr>
    GFG;
}
echo "</tbody></table>";

?>