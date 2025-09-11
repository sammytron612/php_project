<?php
require_once 'db_functions.php';


$username = isset($_GET['user']) ? trim($_GET['user']) : '';

if (!$username) {
    header("Location: search.php");
    exit();
}

$db = new DatabaseConnection();
$repos = $db->getUserRepos($username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Repositories for <?php echo htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="main">
        <div style="flex: 1; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Repositories for <?php echo htmlspecialchars($username); ?></h2>
            <a href="search.php" class="primary-button">← Back to Search</a>
        </div>
        
        <div>
            <?php if (!empty($repos)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Repository Name</th>
                            <th>Description</th>
                            <th>Stars</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($repos as $repo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($repo['repo_name']); ?></td>
                            <td><?php echo htmlspecialchars($repo['description'] ?: 'No description'); ?></td>
                            <td><?php echo htmlspecialchars($repo['stars_count']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($repo['url']); ?>" target="_blank" class="primary-button">View on GitHub</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No repositories found for this user.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
