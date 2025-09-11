<?php

function searchStarredRepos($username) {
    $url = "https://api.github.com/users/" . urlencode($username) . "/starred";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-App');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/vnd.github.v3+json'));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        return json_decode($response, true);
    } else {
        echo "Error: HTTP " . $httpCode . " - Unable to fetch data from GitHub<br>";
        return null;
    }
}

?>