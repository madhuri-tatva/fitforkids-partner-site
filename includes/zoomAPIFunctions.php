<?php

require_once(MAIN_PATH."/plugins/guzzlehttp/vendor/autoload.php");

function list_meetings($next_page_token = '') {
    global $db;
    $db->where('provider', 'zoom');
    $arr_token = $db->getOne('zoom_oauth_details');

    $token = json_decode($arr_token['provider_value']);

    if (!empty($token)) {
        $accessToken = $token->access_token;

        if (!empty($accessToken)) {
            $zoomUserAry = listUsers();

            if (!empty($zoomUserAry) && $zoomUserAry != 'Token_Refreshed') {
                $newdata = $meetingIdAry = array();
                foreach ($zoomUserAry as $k => $v) {                                       
                    try {
                        $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);

                        $arr_request = [
                            "headers" => [
                                "Authorization" => "Bearer $accessToken"
                            ],
                            "json" => [
                                "type" => "upcoming",
                                "page_size" => 30
                            ]
                        ];

                        if (!empty($next_page_token)) {
                            $arr_request['query'] = ["next_page_token" => $next_page_token];
                        }

                        $response = $client->request('GET', '/v2/users/' . $k . '/meetings', $arr_request);

                        $data = json_decode($response->getBody());

                        if (!empty($data)) {
                            if (!empty($data->next_page_token)) {
                                $data = list_meetings($data->next_page_token);
                            }
                            // custom logic to list upcoming meetings only 
                            if (isset($data->meetings) && !empty($data->meetings)) {
                                foreach ($data->meetings as $key => $value) {
                                    
                                    if ((strcasecmp('FFK Status Meeting', $value->topic) != 0 && strcasecmp('My Meeting', $value->topic) != 0) && !in_array($value->id, $meetingIdAry)) {

                                        $date = strtotime($value->start_time . ' + ' . $value->duration . ' minute');
                                        $currentDate = strtotime(date('Y-m-d H:i:s'));

                                        // $currentDate = strtotime('+'. $value->duration .' minutes',date('Y-m-d H:i:s'));

                                        if ($date > $currentDate) {
                                            $value = meetings_details($value->id);
                                            array_push($newdata, $value);
                                            array_push($meetingIdAry, $value->id);
                                        } else {
                                            continue;
                                        }
                                    } else {
                                        continue;
                                    }
                                }
                            }
                        }
                    } catch (Exception $e) {
                        if (401 == $e->getCode()) {
                            // return 'refreshing_token';
                            $refreshedToken = refreshZoomToken();
                            return $refreshedToken;
                        } else {
                            echo $e->getMessage();
                        }
                    }
                }
                usort($newdata, function ($item1, $item2) {
                    return $item1->start_time <=> $item2->start_time;
                });
                return $newdata;
            } else {
                return $zoomUserAry;
            }
        }
    } else {
        return 'Authentication';
    }
}

function meetings_details($meetingId) {
    global $db;
    if (!empty($meetingId)) {
        $db->where('provider', 'zoom');
        $arr_token = $db->getOne('zoom_oauth_details');

        $token = json_decode($arr_token['provider_value']);

        if (!empty($token)) {
            $accessToken = $token->access_token;
            if (!empty($accessToken)) {
                try {
                    $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);
                    $response = $client->request('GET', '/v2/meetings/' . $meetingId, [
                        "headers" => [
                            "Authorization" => "Bearer $accessToken"
                        ]
                    ]);
                    $data = json_decode($response->getBody());

                    if (!empty($data)) {
                        $meetingHostName = meetingHostDetails($data->host_email);
                        if (!empty($meetingHostName)) {
                            $data->host_name = $meetingHostName;
                        }

                        return $data;
                    }
                } catch (Exception $e) {
                    if (401 == $e->getCode()) {                        
                        $refreshedToken = refreshZoomToken();
                        return $refreshedToken;
                    } else {
                        echo $e->getMessage();
                    }
                }
            }
        } else {
            return 'Authentication';
        }
    }
}

function meetingHostDetails($userId) {
    global $db;
    if (!empty($userId)) {

        $db->where('provider', 'zoom');
        $arr_token = $db->getOne('zoom_oauth_details');

        $token = json_decode($arr_token['provider_value']);

        if (!empty($token)) {
            $accessToken = $token->access_token;
            if (!empty($accessToken)) {
                try {
                    $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);
                    $response = $client->request('GET', '/v2/users/' . $userId, [
                        "headers" => [
                            "Authorization" => "Bearer $accessToken"
                        ]
                    ]);
                    $data = json_decode($response->getBody());

                    if (!empty($data)) {
                        $hostName = $data->first_name . ' ' . $data->last_name;
                        return $hostName;
                    }
                } catch (Exception $e) {
                    if (401 == $e->getCode()) {                        
                        $refreshedToken = refreshZoomToken();
                        return $refreshedToken;
                    } else {
                        echo $e->getMessage();
                    }
                }
            }
        } else {
            return 'Authentication';
        }
    }
}

function deleteMeeting($meetingId) {
    global $db;
    if ($meetingId) {
        $db->where("meeting_id", $meetingId);
        $db->delete("zoom_live_videochat_details");

        $db->where('provider', 'zoom');
        $arr_token = $db->getOne('zoom_oauth_details');

        $token = json_decode($arr_token['provider_value']);
        if (!empty($token)) {
            $accessToken = $token->access_token;
            $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);

            $response = $client->request('DELETE', '/v2/meetings/' . $meetingId, [
                "headers" => [
                    "Authorization" => "Bearer $accessToken"
                ]
            ]);

            if (204 == $response->getStatusCode()) {
                //success
            }
        } else {
            return 'Authentication';
        }
    }
}

function listUsers($next_page_token = '') {
    global $db;
    $db->where('provider', 'zoom');
    $arr_token = $db->getOne('zoom_oauth_details');

    $token = json_decode($arr_token['provider_value']);
    if (!empty($token)) {
        $accessToken = $token->access_token;
        if (!empty($accessToken)) {
            try {
                $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);

                $arr_request = [
                    "headers" => [
                        "Authorization" => "Bearer $accessToken"
                    ],
                    "json" => [
                        "status" => "active",
                        "page_size" => 30
                    ]
                ];

                if (!empty($next_page_token)) {
                    $arr_request['query'] = ["next_page_token" => $next_page_token];
                }

                $response = $client->request('GET', '/v2/users', $arr_request);

                $data = json_decode($response->getBody());

                if (!empty($data)) {
                    if (!empty($data->next_page_token)) {
                        $data = listUsers($data->next_page_token);
                    }
                    //custom logic
                    $zoomUserAry = array();
                    foreach ($data->users as $k => $user) {
                        $zoomUserAry[$user->id] = array(
                            'name' => $user->first_name . ' ' . $user->last_name,
                            'email' => $user->email
                        );
                    }

                    return $zoomUserAry;
                }
            } catch (Exception $e) {
                if (401 == $e->getCode()) {
                    // return 'refreshing_token';
                    $refreshedToken = refreshZoomToken();
                    return $refreshedToken;
                } else {
                    echo $e->getMessage();
                }
            }
        }
    } else {
        return 'Authentication';
    }
}

function loggedinZoomUser() {
    global $db;
    $db->where('provider', 'zoom');
    $arr_token = $db->getOne('zoom_oauth_details');

    $token = json_decode($arr_token['provider_value']);
    if (!empty($token)) {
        $accessToken = $token->access_token;
        if (!empty($accessToken)) {
            try {
                $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);

                $arr_request = [
                    "headers" => [
                        "Authorization" => "Bearer $accessToken"
                    ]
                ];

                $response = $client->request('GET', '/v2/users/me', $arr_request);

                $data = json_decode($response->getBody());

                if (!empty($data)) {
                    return $data->id;
                }
            } catch (Exception $e) {
                if (401 == $e->getCode()) {
                    // return 'refreshing_token';
                    $refreshedToken = refreshZoomToken();
                    return $refreshedToken;
                } else {
                    echo $e->getMessage();
                }
            }
        }
    } else {
        return 'Authentication';
    }
}

function refreshZoomToken() {
    global $db;
    $db->where('provider', 'zoom');
    $arr_token = $db->getOne('zoom_oauth_details');

    if (!empty($arr_token)) {
        $token = json_decode($arr_token['provider_value']);

        if (!empty($token->refresh_token)) {
            try {
                $client = new GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);
                $response = $client->request('POST', '/oauth/token', [
                    "headers" => [
                        "Authorization" => "Basic " . base64_encode(ZOOM_CLIENT_ID . ':' . ZOOM_CLIENT_SECRET)
                    ],
                    'form_params' => [
                        "grant_type" => "refresh_token",
                        "refresh_token" => $token->refresh_token
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                if (!empty($data)) {
                    $expired_at = json_decode($data['expires_in']);

                    $refresh_token_data = array(
                        'provider_value' => json_encode($data),
                        'updated_at' => $db->now()
                    );

                    $db->where('provider', 'zoom');
                    $db->update('zoom_oauth_details', $refresh_token_data);

                    $db->rawQuery("UPDATE pg_zoom_oauth_details SET expired_at = DATE_ADD(updated_at, INTERVAL " . $expired_at . " second) WHERE provider = 'zoom'");
                    return 'Token_Refreshed';
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
?>

