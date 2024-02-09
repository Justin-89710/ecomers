<?php
//error_reporting
error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// connect to database
$db = new sqlite3('../database/database.db');

//start session
session_start();

//check if user is rank 1
if ($_SESSION['Rank'] != '1') {
    header("Location: ../index.php");
    exit();
}

//search code
if (isset($_POST['searchform'])) {
    $search = $_POST['search'];
    $searchquery = "SELECT * FROM items WHERE title LIKE '%$search%'";
    $searchresult = $db->query($searchquery);
}

//form code
if (isset($_POST['submitform'])) {
    $Title = $_POST['Title'];
    $Price = $_POST['Price'];
    $number = $_POST['number'];
    $Category = $_POST['Category'];
    $Description = $_POST['Description'];
    $img1 = null;
    $img2 = null;
    $img3 = null;
    $img4 = null;

    // Image 1
    if (isset($_FILES['img1'])) {
        $img1 = $_FILES['img1']['name'];
        $target_dir = "../img/";
        $target_file = $target_dir . basename($_FILES["img1"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["img1"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["img1"]["size"] > 500000) {
            $error = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            $error = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["img1"]["tmp_name"], $target_file)) {
                $error = "The file " . basename($_FILES["img1"]["name"]) . " has been uploaded.";
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Image 2
    if (!empty($_FILES['img2']['name'])) {
        $img2 = $_FILES['img2']['name'];
        $target_file = $target_dir . basename($_FILES["img2"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["img2"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["img2"]["size"] > 500000) {
            $error = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            $error = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["img2"]["tmp_name"], $target_file)) {
                $error = "The file " . basename($_FILES["img2"]["name"]) . " has been uploaded.";
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $img2 = null;
    }

    // Image 3
    if (!empty($_FILES['img3']['name'])) {
        $img3 = $_FILES['img3']['name'];
        $target_file = $target_dir . basename($_FILES["img3"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["img3"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["img3"]["size"] > 500000) {
            $error = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            $error = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["img3"]["tmp_name"], $target_file)) {
                $error = "The file " . basename($_FILES["img3"]["name"]) . " has been uploaded.";
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $img3 = null;
    }

    // Image 4
    if (!empty($_FILES['img4']['name'])) {
        $img4 = $_FILES['img4']['name'];
        $target_file = $target_dir . basename($_FILES["img4"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["img4"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["img4"]["size"] > 500000) {
            $error = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            $error = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["img4"]["tmp_name"], $target_file)) {
                $error = "The file " . basename($_FILES["img4"]["name"]) . " has been uploaded.";
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $img4 = null;
    }

    //insert data into database
    $sql = "INSERT INTO items (title, price, left, catagory, description, img1, img2, img3, img4) VALUES ('$Title', '$Price', '$number', '$Category', '$Description', '$img1', '$img2', '$img3', '$img4')";
    $db->exec($sql);
    header("Location: ../index.php");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/additem.css">
    <title>Add item</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../index.php">Ecom</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <?php
            if ($_SESSION['Rank'] == '1') {
                echo '<li class="nav-item">
                <a class="nav-link" href="#">Add item</a>
            </li>';
            }

            ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Dropdown
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0 col-md-4" name="form">
            <div style="position: relative;">
                <input class="custom_search form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search">
                <span class="search_span"></span>
            </div>
            <button class="ml-lg-3 btn btn-success my-2 my-sm-0 search_button tx--white" type="submit" ripple="ripple" name="searchform">Search</button>
        </form>
        <?php
        if ($searchresult =! null){

        }
        ?>
    </div>
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo '<a href="index/logout.php">
    <button type="button" class="btn search_button tx--white" data-toggle="modal" data-target="#exampleModal">
        Logout
    </button>
    </a>';
    } else {
        echo '<a href="index/login.php">
    <button type="button" class="btn search_button tx--white" data-toggle="modal" data-target="#exampleModal">
        Login
    </button>
    </a>';
    }
    ?>
</nav>

<!-- Add item form -->
<div class="container">
    <header class="header">
        <h1 id="title" class="text-center">Item upload form</h1>
        <p id="description" class="text-center">
            Upload a new item to the website!
        </p>
    </header>
    <div class="form-wrap">
        <form id="survey-form" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="Title-label" for="Title">Title</label>
                        <input type="text" name="Title" id="Title" placeholder="Enter the title of the item" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="Price-label" for="Price">Price</label>
                        <input type="number" name="Price" id="Price" placeholder="Enter the price of the item" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="number-label" for="number">Number of items</label>
                        <input type="number" name="number" id="number" class="form-control" placeholder="Enter the number of items" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Category</label>
                        <select id="dropdown" name="Category" class="form-control" required>
                            <option disabled selected value>Select</option>
                            <option value="Hoodie">Hoodie</option>
                            <option value="T-shirt">T-shirt</option>
                            <option value="Hat">Hat</option>
                            <option value="Pants">Pants</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row imgup">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Upload photo 1 (required)</label>
                        <input type="file" name="img1" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Upload photo 2 (optional)</label>
                        <input type="file" name="img2" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Upload photo 3 (optional)</label>
                        <input type="file" name="img3" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Upload photo 4 (optional)</label>
                        <input type="file" name="img4" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea id="Description" class="form-control" name="Description" placeholder="Enter The Description of the item here..."></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <button type="submit" id="submit" class="btn btn-primary btn-block" name="submitform">Submit item</button>
                </div>
            </div>

            <!--error message-->
            <?php
            if (isset($error)) {
                echo "<p class='alert alert-danger'>$error</p>";
            }
            ?>

        </form>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="../JS/additem.js"></script>
</body>
</html>
