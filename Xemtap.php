<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';

// Kiểm tra xem id phim và số tập đã được truyền vào hay chưa
if (isset($_GET['id']) && isset($_GET['episode'])) {
    $id = $_GET['id'];
    $episode = $_GET['episode'];

    // Truy vấn thông tin phim dựa trên id phim
    $sql_movie = "SELECT title FROM movies WHERE id = $id";
    $result_movie = $connection->query($sql_movie);

    // Kiểm tra xem có kết quả trả về hay không
    if ($result_movie->num_rows > 0) {
        $row_movie = $result_movie->fetch_assoc();
        $title = $row_movie['title'];

        // Truy vấn danh sách tập phim dựa trên id phim
        $sql_episodes = "SELECT episode_number, video_link FROM episodes WHERE movie_id = $id";
        $result_episodes = $connection->query($sql_episodes);

        // Kiểm tra xem có kết quả trả về hay không
        if ($result_episodes->num_rows > 0) {
            // Lưu trữ danh sách tập phim
            $episodes = array();

            // Lặp qua kết quả truy vấn và lấy thông tin các tập phim
            while ($row_episodes = $result_episodes->fetch_assoc()) {
                $episodeNumber = $row_episodes['episode_number'];
                $videoLink = $row_episodes['video_link'];
                $episodes[] = array('number' => $episodeNumber, 'link' => $videoLink);
            }

            // Kiểm tra xem số tập phim có hợp lệ hay không
            if (isset($_GET['episode']) && is_numeric($_GET['episode']) && $_GET['episode'] >= 1 && $_GET['episode'] <= count($episodes)) {
                $episode = $_GET['episode'];
            } else {
                $episode = 1; // Mặc định hiển thị tập đầu tiên
            }

            // Hiển thị trang
            echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Xem Tập Phim - ' . $title . ' - Tập ' . $episode . '</title>
                <link rel="stylesheet" href="css/style.css">
                <link rel="shortcut icon" href="img/fav-icon.png" type="image/x-icon">
                <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
                <style>
                /* CSS cho phần khung xem phim */
                .video-container {
                    position: relative;
                    width: 100%;
                    height: 0;
                    padding-bottom: 56.25%; /* Tỷ lệ 16:9 */
                }
                
                .video-container iframe {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                }
                
                .movie-details.container {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-top: 60px;
                }
                
                .episode-list {
                    margin-top: 20px;
                    padding: 15px 20px;
                    background-color: #272735;
                    border-radius: 8px;
                    display: flex;
                    flex-direction: column;
                    align-items: flex-start;
                }
                
                .episode-list h3 {
                    font-size: 24px;
                    margin-bottom: 5px;
                    color: #E91A46;
                }
                
                .episode-list ul {
                    padding: 0;
                    display: flex;
                    flex-direction: row;
                    flex-wrap: wrap;
                }
                
                .episode-list li {
                    padding: 10px 15px;
                    border-radius: 10px;
                    margin-right: 10px;
                    background-color: #272735;
                    color: white;
                }
                
                .episode-list li:hover {
                    background-color: #E91A46;
                    color: white;
                    cursor: pointer;
                }
                
                .episode-list li.current-episode {
                    background-color: #E91A46;
                    color: white;
                }
            
                
                .episode-list li a {
                    color: white; /* Màu chữ trắng */
                }
                
                .current-episode {
                    color: white; /* Màu chữ trắng */
                }
                
                .comment-section {
                    margin-top: 20px;
                    padding: 10px;
                    background-color: #272735;
                }
                .current-episode-title {
                    margin-top: 20px;
                    font-weight: bold;
                    font-size: 28px;
                    margin-bottom: 20px;
                }
                </style>
            </head>
            <body>
                <header>
                    <div class="nav container">
                        <a href="TrangChu.html" class="logo">
                            Movie<span>Manhwa</span>
                        </a>
                        <div class="search-box">
                            <form method="post" style="display: flex;">
                                <input type="text" name="noidung" autocomplete="off" id="search-input" placeholder="Search Movies">
                                <button class="search-button" type="submit" name="btn">
                                    <a href="Search.html"><i class="bx bx-search"></i></a>
                                </button>
                            </form>
                        </div>
                        <a href="#" class="user">
                            <img src="img/images.png" alt="" class="user-img">
                        </a>
                        <div class="navbar">
                            <a href="TrangChu.html" class="nav-link">
                                <i class="bx bx-home"></i>
                                <span class="nav-link-title">Trang chủ</span>
                            </a>
                            <a href="#home" class="nav-link">
                                <i class="bx bxs-hot"></i>
                                <span class="nav-link-title">Thịnh hành</span>
                            </a>
                            <a href="PhimBo.php" class="nav-link nav-active">
                                <i class="bx bxs-movie"></i>
                                <span class="nav-link-title">Phim bộ</span>
                            </a>
                            <a href="PhimLe.php" class="nav-link">
                                <i class="bx bxs-film"></i>
                                <span class="nav-link-title">Phim lẻ</span>
                            </a>
                            <a href="#home" class="nav-link">
                                <i class="bx bx-category"></i>
                                <span class="nav-link-title">Thể loại</span>
                            </a>
                            <a href="#home" class="nav-link">
                                <i class="bx bx-heart"></i>
                                <span class="nav-link-title">Yêu thích</span>
                            </a>
                        </div>
                    </div>
                </header>
                <section class="movie-details container">
                  <div class="container">
                    <div class="current-episode-title">
                        <!-- Hiển thị tên bộ phim và số tập -->
                        <p>Phim ' . $title . ' - Tập ' . $episode . '</p>
                    </div>
                    <div class="video-container">
                        <!-- Khung xem tập phim -->
                        <iframe src="' . $episodes[$episode-1]['link'] . '" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <div class="episode-list">
                        <!-- Danh sách các tập phim -->
                        <h3>Danh sách tập phim</h3>
                        <ul>';

                        echo '<li><a href="XemTrailer.php?id=' . $id . '">Trailer</a></li>';
                        foreach ($episodes as $episodeItem) {
                            $currentClass = ($episodeItem['number'] == $episode) ? 'current-episode' : '';
                            echo '<li class="' . $currentClass . '"><a href="XemTap.php?id=' . $id . '&episode=' . $episodeItem['number'] . '">Tập ' . $episodeItem['number'] . '</a></li>';
                            
                        }
                       

                        echo '
                        </ul>
                    </div>

                    <div class="comment-section">
                        <!-- Khung bình luận -->
                        <h3>Bình luận</h3>
                        <!-- Thêm form bình luận hoặc hiển thị danh sách bình luận -->
                    </div>

                  </div>
                </section>
                <script src="js/main.js"></script>
            </body>
            </html>';
        } else {
            echo "Không tìm thấy tập phim.";
        }
    } else {
        echo "Không tìm thấy thông tin phim.";
    }
} else {
    echo "Không có đủ thông tin để xem tập phim.";
}

// Đóng kết nối cơ sở dữ liệu
$connection->close();
?>
