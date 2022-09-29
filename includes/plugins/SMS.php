<?php

namespace PHPCPSMS;

class SMS
{
    protected $apiUrl = "https://api.cpsms.dk/v2", $apiKey;

    public $recipient, $sender = 'FitforKids', $message, $dlr_url, $encoding, $timestamp, $reference, $flash, $format;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send()
    {
        return $this->makeRequest("POST", "send", $this->createParameters());
    }

    public function credits()
    {
        return $this->makeRequest("GET", "creditvalue", []);
    }

    protected function createParameters()
    {
        $parameters = [
            "to" => (strlen($this->recipient) < 10 ? '45' . $this->recipient : $this->recipient),
            "message" => $this->message,
            "from" => $this->sender,
        ];

        $variables = array("dlr_url", "encoding", "timestamp", "reference", "flash", "format");

        foreach ($variables as $variable) {
            if (isset($this->$variable))
                $parameters[$variable] = $this->$variable;
        }

        return $parameters;
    }

    protected function makeRequest($method, $endpoint, $data)
    {   
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl . '/' . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,-+
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic " . $this->apiKey,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
