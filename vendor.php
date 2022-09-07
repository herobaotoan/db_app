<?php
?>
<!-- Add Product form -->
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
        <div class = "col-auto">
        <input type="submit" name="A" value="Add Product" class="btn btn-sm btn-primary">
        <span class="form-text">You can add extra information later</span>
        </div>
    </div>
    </div>   
</form>
<hr>
<!-- GFG; -->


<!-- Display vendor's product -->
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
        <th>Extra info</th>
    </tr>
</thead>
<tbody>
<?php
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
                    <input type="text" style="width: 40%" placeholder="Name" name="name" required>
                </td>
                <td>
                    $price
                    <input type="number" style="width: 50%" placeholder="Price" name="price" required>
                </td>
                <td>
                    $status
                    <input type="hidden" name="status" value=$status>
                </td>
                <td><input type="submit" name="B" class="btn btn-sm btn-success" value="Edit"></td>
            </form>
    
    GFG;
    $newly_added = true; //To check if product is new and doesn't have any exta information yet.
    //Get product extra information
    $document = $collection->find()->toArray();
    for ($i = 0; $i < count($document); $i++){
        if ($document[$i]['Pid'] == $id){
            $newly_added = false;
            $count = 0;
            foreach($document[$i] as $key => $value)
            {
                if ($count > 1){ //Skip the first 2 keys and values (which is _id an Pid)
                    echo<<<GFG
                    <td>
                    <form method="post" action="main.php">
                        $key: $value
                        <input type="hidden" name="key" value=$key>
                        <input type="hidden" name="value" value=$value>
                        <input type="hidden" name="status" value=$status>
                        <input type="submit" name="Delete_info" class="btn btn-sm btn-success" value="DELETE">
                    </form>
                    </td>
                    GFG;
                } $count++;
            }
        }
    }
    echo<<<GFG
    <td>
        <form method="post" action="main.php">
            <input type="text" name="key" style="width: 20%" placeholder='key'required>
            <input type="text" name="value" style="width: 20%" placeholder='value'required>
            <input type="hidden" name="id" value=$id>
            <input type="hidden" name="newly_added" value=$newly_added>
            <input type="submit" name="Add_info" class="btn btn-sm btn-success" value="Add">
        </form>
    </td>
    </tr>
    GFG;
}
?>
</tbody>
</table>
