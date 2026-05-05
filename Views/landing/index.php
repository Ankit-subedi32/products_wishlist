<?php
include __DIR__ . "/../../Config/connection.php";
session_start();
$search = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}


$limit = 6;    // yeuta page ma kati rakhne vanyara 

$page = 1;      // default page 1(suru ma kun aaune)

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if ($page < 1) {            //page 1 vanda badi na vaya page 1 rakhne 
    $page = 1;
}

$offset = ($page - 1) * $limit;   // data chai kati bata set garne page ma like (1-1)*6 = 0 first ma 0 bata start garne =>(1-6) first ma (7-12)=>second ma first ko lai skip garxa 

$searchLike = "%$search%";

//kati ota xa vanayara herna lai 
$countQuery = "
    SELECT COUNT(*) as total 
    FROM products 
    WHERE name LIKE ? 
    OR description LIKE ? 
    OR price LIKE ?
";

$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param("sss", $searchLike, $searchLike, $searchLike);
$countStmt->execute();

$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();

$totalProducts = $countRow['total'];  // for readable using total alias
//totalProducts le total kati ota products xa lauxa .


$totalPages = ceil($totalProducts / $limit); // kati ota page chainxa vanyara check garxa 
//celi le chai round up garxa . aaune condition ma like 2.5 lai 3 garxa  

//products haru launa lai also for search (yedi search vayana vanya sabai aauxa aane search ma vako milya tei aauxa  mathi search lai declare garyaxa)
$query = "
    SELECT * FROM products 
    WHERE name LIKE ? 
    OR description LIKE ? 
    OR price LIKE ? 
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("sssii", $searchLike, $searchLike, $searchLike, $limit, $offset);
$stmt->execute();

$result = $stmt->get_result();

if(isset($_SESSION['wishlist_error'])){
    echo "<div class='alert alert-danger'>".$_SESSION['wishlist_error']."</div>";
    unset($_SESSION['wishlist_error']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-4">

        <!-- WELCOME -->
        <h2>
            <?php
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] == 'user' || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superadmin') {
                    echo "Welcome, " . $_SESSION['name'];
                }
            }
            ?>
        </h2>

        <a href="/wishlist/views/wishlist/index.php" class="btn btn-info">Wishlist</a>

        <!-- SEARCH FORM -->
        <form method="GET" class="mb-3">

            <input type="text" name="search" class="form-control mb-2" value="<?php echo htmlspecialchars($search); ?>"
                placeholder="Search products">

            <button type="submit" class="btn btn-success">
                Search
            </button>

        </form>

        <h3>All Products</h3>

        <!-- PRODUCT LIST -->
        <div class="row text-center">

            <?php
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($rows as $row) {
                ?>

                <div class="col-md-4 mb-3">

                    <div class="card">

                        <img src="../../<?php echo htmlspecialchars($row['imgPath']); ?>" height="200">

                        <div class="card-body">

                            <h5><?php echo htmlspecialchars($row['name']); ?></h5>

                            <p><?php echo htmlspecialchars($row['description']); ?></p>

                            <p>Price: <?php echo htmlspecialchars($row['price']); ?></p>
                        </div>

                    </div>

                </div>

                <?php
            }
            ?>

        </div>

        <!-- PAGINATION -->
        <nav>
            <ul class="pagination justify-content-center">

                <!-- PREVIOUS -->
                <!-- page yeuta aathwa thorai xa vane previous na aawos vanyara disable garne  -->
                <li class="page-item <?php if ($page <= 1)
                    echo 'disabled'; ?>">

                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>">
                        Previous
                    </a>
                </li>

                <!-- PAGE NUMBERS -->
                <?php
                for ($i = 1; $i <= $totalPages; $i++) {
                    ?>

                    <li class="page-item <?php if ($i == $page)
                        echo 'active'; ?>">

                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>">

                            <?php echo $i; ?>

                        </a>

                    </li>

                    <?php
                }
                ?>

                <!-- NEXT -->
                <li class="page-item <?php if ($page >= $totalPages)
                    echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>">
                        Next
                    </a>
                </li>

            </ul>
        </nav>

    </div>

</body>

</html>