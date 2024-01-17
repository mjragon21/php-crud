<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My store</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar bg-body-tertiary bg-gray-700">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="https://img.icons8.com/fluency/48/online-store.png" alt="My store" width="30" height="24">
                <b>
                    My Store
                </b>

            </a>
        </div>
    </nav>
    <div class="container my-5">
        <h3>List of Products</h3>
        <a href="/mystore/create.php" class="btn btn-primary" role="button">New Product</a>
        <br>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Expiration Date</th>
                        <th>Available Inventory</th>
                        <th>Inventory Cost</th>
                        <th>Image</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "mystore";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $sql = "SELECT * FROM inventory";
                    $result = $conn->query($sql);

                    if (!$result) {
                        die("Invalid query:" . $connection->error);
                    }

                    while ($row = $result->fetch_assoc()) {

                        echo "
                    <tr>
                    <td>$row[id]</td>
                    <td>$row[name]</td>
                    <td>$row[unit]</td>
                    <td>$row[price]</td>
                    <td>$row[expiry_date]</td>
                    <td>$row[available_inventory]</td>
                    <td>$row[inventory_cost]</td>
                            <td><img src='$row[image_path]' alt='image' style='max-width: 100px; max-height: 100px;'></td>

                            
                            <td> 
                            <a href='/mystore/edit.php?id=$row[id]' class='btn btn-primary btn-sm'> Edit</a>
                            <a href='/mystore/delete.php?id=$row[id]' class='btn btn-danger btn-sm'> Delete</a>                 
                            </td>                    
                            </tr>";
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>