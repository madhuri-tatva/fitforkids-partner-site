<?php

require_once("includes/config.php");

require_once("plugins/guzzlehttp/vendor/autoload.php");

//require_once $_SERVER["DOCUMENT_ROOT"].'/plugins/guzzlehttp/vendor/autoload.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];
} else {
    $code = '';
}

if (!empty($code)) {

    try {
        $client = new GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);

        $response = $client->request('POST', '/oauth/token', [
            "headers" => [
                "Authorization" => "Basic " . base64_encode(ZOOM_CLIENT_ID . ':' . ZOOM_CLIENT_SECRET)
            ],
            'form_params' => [
                "grant_type" => "authorization_code",
                "code" => $code,
                "redirect_uri" => ZOOM_REDIRECT_URI
            ],
        ]);

        $token = json_decode($response->getBody()->getContents(), true);
        if ($token) {
//             echo json_encode($token);
            // Insert Access token to DB

            $expired_at = json_decode($token['expires_in']);

            $time = date("Y-m-d h:i:s", strtotime("+" . $expired_at . " seconds"));

            $zoomData = array(
                "provider" => 'zoom',
                "provider_value" => json_encode($token),
                'created_at' => $db->now()
            );
            
            $db->where('provider', 'zoom');
            $db->delete("zoom_oauth_details");
            
            $insertedId = $db->insert("zoom_oauth_details", $zoomData);

            if ($insertedId) {
                $db->rawQuery("UPDATE pg_zoom_oauth_details SET expired_at = DATE_ADD(created_at, INTERVAL " . $expired_at . " second) WHERE id = " . $insertedId);
                // header("Location:" . $basehref . "admin-zoom");
                header("Location:" . $basehref . "overview");
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>