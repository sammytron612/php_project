<?php

class DatabaseConnection {
    private $servername = "localhost";
    private $username = "root";
    private $password = "targa123";
    private $dbname = "starred_repos";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli(
            $this->servername,
            $this->username,
            $this->password,
            $this->dbname
        );
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        } else{
            // echo "Connected successfully";
            return $this->conn;
        }
    }

    public function close() {
        $this->conn->close();
    }

    public function storeRepo($repos, $username) {
        foreach ($repos as $repo) {

            $existing = $this->checkIfExists($username, $repo['name']);

            if(!$existing){
                $query = $this->conn->prepare("INSERT INTO repos (username, repo_name, description, url, stars_count) VALUES (?, ?, ?, ?, ?)");
                $query->bind_param("ssssi", 
                    $username,
                    $repo['name'],
                    $repo['description'],
                    $repo['html_url'],
                    $repo['stargazers_count']
                );
                $query->execute();
                $query->close();

            }
            
        }
        return;
    }

    public function checkIfExists($username, $repo) {
        ### check if user already has existing repos by same name###
        $existing = $this->conn->prepare("SELECT username FROM repos WHERE username = ? AND repo_name = ?");
        $existing->bind_param("ss", $username, $repo);
        $existing->execute();
        $result = $existing->get_result();
        $existing->close();

        //var_dump($result->num_rows > 0);
        //die();
        
        return ($result->num_rows > 0);
    }

    public function getUsernames($page) : array {

        /// get totlal records to determine if last page ///
        $countQuery = $this->conn->prepare("SELECT COUNT(DISTINCT username) as total FROM repos");
        $countQuery->execute();
        $countResult = $countQuery->get_result();
        $totalRecords = $countResult->fetch_assoc()['total'];
        $countQuery->close();
        
        $isLastPage = ($page * LIMIT) >= $totalRecords;
       
        $query = $this->conn->prepare("
                SELECT username, COUNT(*) AS repo_count
                FROM repos
                GROUP BY username
                ORDER BY username limit ? OFFSET ?;
                ");

        $offset = ($page - 1) * LIMIT; // Calculate the offset
        $limit = LIMIT; // Use the constant value on main page

        $query->bind_param("ii", $limit, $offset);
        $query->execute();
        $result = $query->get_result();
        $results = $result->fetch_all(MYSQLI_ASSOC);
        $query->close();
        return ['results' => $results, 'lastPage' => $isLastPage];
    }

// Fetch user's repositories from database
public function getUserRepos($username) {
    $query = $this->conn->prepare("SELECT * FROM repos WHERE username = ? ORDER BY repo_name");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    $repos = $result->fetch_all(MYSQLI_ASSOC);
    $query->close();
    return $repos;
}
}