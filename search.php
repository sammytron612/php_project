<?php

session_start();
require 'db_functions.php';
require 'curl_functions.php';

define("LIMIT", 5); /// pagination offset ///

$page = 1;

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
}


$results = fetchUsers($page);
$users = $results['results'];
$lastPage = $results['lastPage'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';

    if ($username) {
        $repos = searchStarredRepos($username);
        if ($repos) {
            $db = new DatabaseConnection();
            $db->storeRepo($repos,$username);
            $db->close();
            // Set success message in session
            $_SESSION['message'] = "User '" . htmlspecialchars($username) . "' and their starred repositories have been successfully added to the database!";
            $_SESSION['message_type'] = 'success';

            // Redirect to refresh the page and update the table
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['message'] = "No starred repositories found for " . htmlspecialchars($username) . " or an error occurred.";
            $_SESSION['message_type'] = 'error';
        }
       
    }
}


function fetchUsers($page) : array {
    $db = new DatabaseConnection();
    $results = $db->getUsernames($page);
    $db->close();
    return $results;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Github stars</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="grid-container">
        <div>
            <h2>Search GitHub User's Starred Repositories</h2>
            <!-- Display message if exists -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message <?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'error'; ?>">
                    <?php echo $_SESSION['message']; ?>
                </div>
                <?php 
                // Clear the message after displaying //
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
            <?php endif; ?>
            <form action="search.php" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                <br><br>
                <input type="submit" value="Submit" class="primary-button">
            </form>
        </div>

        <div>
         <h2>Existing Users in Database:</h2>
            <table>
    
                <thead>
                    <td>User</td>
                    <td>Starred Repo Count</td>
                    <td>Action</td>
                </thead>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['repo_count']); ?></td>
                    <td><a href="view_repos.php?user=<?php echo urlencode($user['username']); ?>" class="primary-button">View Repos</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <div class="pagination">
                <div>Page <?php echo $page; ?></div>
                <div>
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="primary-button">Previous</a>
                    <?php else: ?>
                        <span class="primary-button disabled">Previous</span>
                    <?php endif; ?>

                    <?php if ($lastPage): ?>
                        <span class="primary-button disabled">Next</span>
                    <?php else: ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="primary-button">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
