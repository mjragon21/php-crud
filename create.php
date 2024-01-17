<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mystore";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




$name = "";
$unit = "";
$price = "";
$expiry_date = "";
$available_inventory = "";
$image_path = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $unit = mysqli_real_escape_string($conn, $_POST["unit"]);
    $price = mysqli_real_escape_string($conn, $_POST["price"]);
    $expiry_date = mysqli_real_escape_string($conn, $_POST["expiry_date"]);
    $available_inventory = mysqli_real_escape_string($conn, $_POST["available_inventory"]);
    $target_dir = "uploads/"; // Create a directory named 'uploads' in your project directory
    $target_file = $target_dir . basename($_FILES["image_upload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image_upload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $errorMessage = "File is not an image.";
            $uploadOk = 0;
        }
    }


    if ($_FILES["image_upload"]["size"] > 500000) {
        $errorMessage = "Sorry, your file is too large.";
        $uploadOk = 0;
    }


    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }


    if ($uploadOk == 0) {
        $errorMessage = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
            $successMessage = "The file " . htmlspecialchars(basename($_FILES["image_upload"]["name"])) . " has been uploaded.";
        } else {
            $errorMessage = "Sorry, there was an error uploading your file.";
        }
    }
}

do {
    if (empty($name) || empty($unit) || empty($price) || empty($expiry_date) || empty($available_inventory) || empty($image_path)) {
        $errorMessage = "All the fields are required";
        break;
    }

    // add new product to  database
    $sql = "INSERT INTO inventory (name, unit, price, expiry_date, available_inventory, image_path)" .
        "VALUES ('$name', '$unit', '$price', '$expiry_date', '$available_inventory', '$image_path')";
    $result = $conn->query($sql);

    if (!$result) {
        $errorMessage = "Invalid query:" . $conn->error;
        break;
    }



    $name = "";
    $unit = "";
    $price = "";
    $expiry_date = "";
    $available_inventory = "";
    $image_path = "";

    $successMessage = "Product was added";

    header("location: /mystore/index.php");
    exit;
} while (false);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My store</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="/mystore/index.php">
                <img src="https://img.icons8.com/fluency/48/online-store.png" alt="My store" width="30" height="24">
                <b>
                    My Store
                </b>

            </a>
        </div>
    </nav>
    <div class="container my-5">
        <h3> New Product</h3>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-primary alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>

        <form method="post" enctype="multipart/form-data">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Unit</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="unit" value="<?php echo $unit; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Price</label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" name="price" value="<?php echo $price; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Expiration Date</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="expiry_date" value="<?php echo $expiry_date; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Available Inventory</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="available_inventory" value="<?php echo $available_inventory; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Image Upload</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" name="image_upload">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-6">
                    <?php if (!empty($image_path)) : ?>
                        <img src="<?php echo $image_path; ?>" alt="Product Image" style="max-width: 200px; max-height: 200px;">
                    <?php else : ?>
                        
                    <?php endif; ?>
                </div>
            </div>

            <?php
            if (!empty($successMessage)) {
                echo "
                    <div class='row mb-3'>
                        <div class='offset-sm-3 col-sm-6'>
                            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>$successMessage</strong>
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    </div>
                    </div>
                ";
            }

            ?>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-danger" href="/mystore/index.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>