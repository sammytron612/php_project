<?php
/////dummy ajax content/////

header('Content-Type: application/json');

try {
    usleep(200000); // Simulate network delay //
    
    $input = json_decode(file_get_contents('php://input'), true);
    $page = isset($input['page']) ? (int)$input['page'] : 1;
    $limit = 2;

    // Generate dummy data
    $html = '';
    for ($i = 0; $i < $limit; $i++) {
        $imageId = ($page * 10) + $i;
        $html .= '
            <div class="lorem">
                <img src="https://picsum.photos/id/' . $imageId . '/500/" alt="Placeholder Image" style="float: left; margin-right: 15px; margin-bottom: 10px; max-width: 150px;">
                <h3>Dynamic Content, Item ' . ($i + 1) . '</h3>
                <p>Content was loaded via AJAX from the server. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    Why do we use it?.</p>
            </div>';
    }

    
    echo json_encode([
        'success' => true,
        'html' => $html,
        'page' => $page,
        'count' => $limit
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
?>