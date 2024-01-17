<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mystore";

$conn = new mysqli($servername, $username, $password, $dbname);

$name = "";
$unit = "";
$price = "";
$expiry_date = "";
$available_inventory = "";
$image_path = "";

$errorMessage = "";
$successMessage = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST["id"]) || empty($_POST["id"])) {
        header("location: /mystore/index.php");
        exit;
    }

    
    $id = mysqli_real_escape_string($conn, $_POST["id"]);
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $unit = mysqli_real_escape_string($conn, $_POST["unit"]);
    $price = mysqli_real_escape_string($conn, $_POST["price"]);
    $expiry_date = mysqli_real_escape_string($conn, $_POST["expiry_date"]);
    $available_inventory = mysqli_real_escape_string($conn, $_POST["available_inventory"]);
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image_upload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    
    if (empty($name) || empty($unit) || empty($price) || empty($expiry_date) || empty($available_inventory)) {
        $errorMessage = "All the fields are required";
    } else {
        // Check if a file was uploaded
        if ($_FILES["image_upload"]["error"] == UPLOAD_ERR_OK) {
            // Check file type
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");
            if (in_array($imageFileType, $allowed_extensions)) {
                // Define target directory for uploaded files
                $target_dir = "uploads/";

                // Get the original name of the file from the client machine
                $original_filename = basename($_FILES["image_upload"]["name"]);

                // Generate a unique name for the file to avoid overwriting existing files
                $unique_filename = uniqid() . "_" . $original_filename;

                // Construct the full path to the target file
                $target_file = $target_dir . $unique_filename;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)) {
                    // Update the image_path in the database with the new file path
                    $image_path = $target_file;
                } else {
                    // Handle the case when the file could not be moved
                    $errorMessage = "Error uploading the file.";
                }
            } else {
                $errorMessage = "Invalid file type. Allowed types are jpg, jpeg, png, and gif.";
            }
        }

        // Update the data in the database
        $sql = "UPDATE inventory SET name = '$name', unit = '$unit', price = '$price', expiry_date = '$expiry_date', available_inventory = '$available_inventory', image_path = '$image_path' WHERE id = $id";

        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $conn->error;
        } else {
            $successMessage = "Product was updated";

          

            header("location: /mystore/index.php");
            exit;
        }
    }
}


if (!isset($id) && isset($_GET["id"]) && !empty($_GET["id"])) {
    $id = mysqli_real_escape_string($conn, $_GET["id"]);
    $sql = "SELECT * FROM inventory WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /mystore/index.php");
        exit;
    }

    $name = $row["name"];
    $unit = $row["unit"];
    $price = $row["price"];
    $expiry_date = $row["expiry_date"];
    $available_inventory = $row["available_inventory"];
    $image_path = $row["image_path"];
}
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
    <div class="container my-5">
        <h2> Edit Product</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
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
                    <input type="number" class="form-control" name="available_inventory" value="<?php echo $available_inventory; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Image Upload</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" name="image_upload">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Preview Image</label>
                <div class="col-sm-6">
                    <?php if (!empty($image_path)) : ?>
                        <img src="<?php echo $image_path; ?>" alt="Product Image" style="max-width: 200px; max-height: 200px;">
                    <?php else : ?>
                        <p>No image available</p>
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
