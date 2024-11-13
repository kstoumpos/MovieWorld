<?php

include ("connection.php");
global $databaseHost;
global $databasePassword;
global $databaseUsername;
global $databaseName;

session_start();

?>
<html lang="en">
    <head>
        <title>Movie World - Homepage</title>
        <style>
            .header {
                display: flex;
            }

            .header-col {
                flex: 50%;
                padding: 10px;
            }

            .header-col:nth-child(2) {
                text-align: right;
            }

            .movies-container {
                max-width: 85%;
            }

            .movie {
                border: 2px solid black;
                border-radius: 8px;
                padding: 8px;
            }

            .movie-head {
                display: flex;
            }

            .movie-title, .movie-dop, .movie-footer-left, .movie-posted  {
                flex: 50%;
            }

            .movie-desc {
                margin: 10px 0;
            }

            .movie-dop, .movie-posted {
                text-align: right;
            }

            .movie-footer, .main {
                display: flex;
            }

            .add-new {
                background-color: lightgreen;
                color: white;
                border: 2px solid black;
                border-radius: 4px;
                padding: 5px;
                margin-left: 15px;
                margin-top: 15px;
            }

            .movies-container {
                flex: 80%;
            }

            .filters {
                background-color: darkgrey;
                color: dodgerblue;
                border: 2px solid black;
                border-radius: 4px;
                padding: 5px;
                margin-left: 15px;
                margin-top: 15px;
            }

        </style>
    </head>
    
    <body>
        <div class="header">
            <div class="header-col">
                Movie World
            </div>
            <div class="header-col">
                <?php
                if( isset( $_SESSION['valid'] ) ) { ?>
                    Welcome back <?php echo $_SESSION['name'] ?>! <a href='logout.php'>Logout</a>
                    <?php
                } else {
                    echo "<a class='login' href='login.php'>Login</a> or <a class='register' href='register.php'>Register</a>";
                }
                ?>
            </div>
        </div>
        <div class="main">
            <div class="movies-container">
            <?php
            $movies = null;

            // Check connection
            $mysqli = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            if (isset($_SESSION['valid'])) {
                $stmt = $mysqli->prepare("SELECT * FROM movies WHERE username = ?");
                $stmt->bind_param("s", $_SESSION['valid']);
                $stmt->execute();
            } else {
                $stmt = $mysqli->prepare("SELECT * FROM movies");
                $stmt->execute();
            }

            $movies = $stmt->get_result();

            while ( $res = mysqli_fetch_array( $movies ) ) { ?>
            <div class="movie">
                <div class="movie-head">
                    <div class="movie-title">
                        <?php echo $res['title']; ?>
                    </div>
                    <div class="movie-dop">
                        Posted <?php echo $res['dop']; ?>
                    </div>
                </div>
                <div class="movie-desc">
                    <?php echo $res['description']; ?>
                </div>
                <div class="movie-footer">
                    <div class="movie-footer-left">
                        <?php if (  $_SESSION['valid'] == $res['username'] ) { ?>
                            <?php echo $res['likes']; ?> <button class="like-button" data-movie-id="<?php echo $res['id']; ?>">Likes</button> |
                            <?php echo $res['hates']; ?> <button class="hate-button" data-movie-id="<?php echo $res['id']; ?>">Hates</button>
                        <?php  } else { ?>
                            <?php echo $res['likes']; ?> Likes |
                            <?php echo $res['hates']; ?> Hates
                        <?php  } ?>
                    </div>
                   
                    <div class="movie-posted">
                        <?php if (  $_SESSION['valid'] == $res['username'] ) {
                            echo "Posted by you.";
                        } else {
                            echo "Posted by ".$res['username'];
                        }?>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
            <div class="sidebar">
                <a class="add-new" href="add.php">New Movie</a>
                <div class="filters">
                    Sort by:<br>
                    <label for="Likes">Likes</label>
                    <input type="checkbox" id="Likes" name="Likes" value="Likes"><br>
                    <label for="Hates">Hates</label>
                    <input type="checkbox" id="Hates" name="Hates" value="Hates"><br>
                    <label for="Dates">Dates</label>
                    <input type="checkbox" id="Dates" name="Dates" value="Dates"><br>
                </div>
            </div>
        </div>
        <div id="footer">
            <script>
                const likeButtons = document.querySelectorAll('.like-button');
                const hateButtons = document.querySelectorAll('.hate-button');

                likeButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const movieId = button.getAttribute("data-movie-id");
                        console.log(movieId);
                        fetch('rate_movie.php', {
                            method: 'POST',
                            body: new URLSearchParams({
                                movieId: movieId,
                                rating: 'like'
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                // Update the UI based on the response
                                // e.g., change button color, update like/hate counts
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    });
                });

                hateButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const movieId = button.dataset.movieId;
                        fetch('rate_movie.php', {
                            method: 'POST',
                            body: new URLSearchParams({
                                movieId: movieId,
                                rating: 'hate'
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                // Update the UI based on the response
                                // e.g., change button color, update like/hate counts
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    });
                });
            </script>
        </div>
    </body>
</html>
