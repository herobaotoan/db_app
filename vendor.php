<?php
//Add Product form
echo <<<GFG
<div class="container-fluid bg-primary text-light p-2">
    <h1>Add product</h1>
</div>
<form method="post" action="main.php">
    <div class="container-fluid p-2">
    <div class="row gx-5">
        <div class="col-1">
        <label for="Name" class="form-label">Name:</label>
        </div>
        <div class="col-3 pb-3">
        <input type="text" class="form-control" placeholder="Ex: Iphone, TV, Car ,etc." name="name" required>
        </div>
    </div>
    <div class="row gx-5">
        <div class="col-1">
        <label for="Price" class="form-label">Price:</label>
        </div>
        <div class="col-3 pb-3">
        <input type="text" class="form-control" placeholder="Ex: 50.5, 99.9, etc." name="price" required>
        </div>
    </div>
    <div class="row gx-5">
        <div class = "col-1">
        <input type="submit" name="A" value="Add Product" class="btn btn-primary">
        </div>
    </div>
    </div>   
</form>
<hr>
GFG;

//Display vendor's product
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
//Get product's information
$rows = $pdo->query("SELECT * FROM products WHERE Vid = $Uid;");
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
                <td>
                    $Pname
                    <input type="text" placeholder="Change name" name="name" required>
                </td>
                <td>
                    $price
                    <input type="number" placeholder="Change price" name="price" required>
                </td>
                <td>
                    $status
                    <input type="hidden" name="status" value=$status>
                </td>
                <td><input type="submit" name="B" class="btn btn-success" value="Edit"></td>
            </form>
            </tr>
    GFG;
}
echo "</tbody></table>";
?>