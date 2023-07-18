<?php
session_start();
include 'db_connection.php';

// Kiểm tra xem đã nhập từ khóa tìm kiếm hay chưa
if (isset($_POST['noidung'])) {
    $keyword = $_POST['noidung'];

    // Tính toán số phần tử trên mỗi trang và trang hiện tại
    $itemsPerPage = 4; // Số phim hiển thị trên mỗi trang
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Trang hiện tại, mặc định là trang đầu tiên

    // Tính toán số phần tử bỏ qua
    $offset = ($page - 1) * $itemsPerPage;
    if ($offset < 0) {
        $offset = 0;
    }

    // Truy vấn danh sách phim từ cơ sở dữ liệu với phân trang và tìm kiếm
    $sql = "SELECT * FROM movies WHERE (genre LIKE '%$keyword%' OR title LIKE '%$keyword%' OR othertitle LIKE '%$keyword%') LIMIT $itemsPerPage OFFSET $offset";
    $result = $connection->query($sql);

    // Truy vấn để đếm tổng số phim phù hợp với từ khóa tìm kiếm
    $sqlCount = "SELECT COUNT(*) AS total FROM movies WHERE (genre LIKE '%$keyword%' OR title LIKE '%$keyword%' OR othertitle LIKE '%$keyword%')";
    $resultCount = $connection->query($sqlCount);
    $rowCount = $resultCount->fetch_assoc();
    $totalItems = $rowCount['total']; // Tổng số phim phù hợp
    $totalPages = ceil($totalItems / $itemsPerPage); // Tổng số trang

    // Xử lý khi người dùng nhấp vào nút Đến trang hoặc bấm Enter
    if (isset($_POST['go']) || isset($_POST['targetPage'])) {
        $targetPage = isset($_POST['targetPage']) ? $_POST['targetPage'] : $_POST['targetPageEnter'];
        // Kiểm tra xem trang hợp lệ hay không
        if ($targetPage >= 1 && $targetPage <= $totalPages) {
            $page = $targetPage;
            $offset = ($page - 1) * $itemsPerPage;
            $sql = "SELECT * FROM movies WHERE (genre LIKE '%$keyword%' OR title LIKE '%$keyword%' OR othertitle LIKE '%$keyword%') LIMIT $itemsPerPage OFFSET $offset";
            $result = $connection->query($sql);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManhwaMovies - Tìm kiếm</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/fav-icon.png" type="image/x-icon">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        /* CSS cho phần tìm kiếm và phân trang */
        /* ... */
    </style>
</head>
<body>
    <header>
        <!-- Header code -->
    </header>
    
    <section class="popular container" id="popular" style="margin-top: 80px;">
        <div class="heading">
            <h2 class="heading-title">Kết quả tìm kiếm cho "<?php echo $keyword; ?>"</h2>
        </div>
        <div class="popular-content">
            <div class="movie-grid">
                <?php
                // Hiển thị danh sách phim tìm kiếm
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="movie-box">
                            <img src="' . $row['image'] . '" alt="" class="movie-box-img">
                            <div class="box-text">
                                <h2 class="movie-title">' . $row['title'] . '</h2>
                                <span class="movie-type">' . $row['genre'] . '</span>
                                <a href="chitietphim.php?id=' . $row['id'] . '&user_id=' . $_SESSION['user_id'] . '" class="watch-btn play-btn">
                                    <i class="bx bx-right-arrow"></i>
                                </a>
                            </div>
                        </div>';
                    }
                } else {
                    echo "Không tìm thấy kết quả phù hợp.";
                }
                ?>
            </div>
        </div>
        <div class="pagination">
            <!-- Phân trang code -->
        </div>
    </section>

    <script src="js/main.js"></script>
</body>
</html>

<?php
$connection->close();
?>
