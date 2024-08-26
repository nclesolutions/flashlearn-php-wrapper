<?php

class FlashlearnAPI
{
    private $apiUrl;
    private $token;

    public function __construct($apiUrl, $token = null)
    {
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->token = $token;
    }

    public function authenticate($email, $password)
    {
        $response = $this->request('POST', '/login', [
            'email' => $email,
            'password' => $password,
        ]);

        if (isset($response['access_token'])) {
            $this->token = $response['access_token'];
            return $response['access_token'];
        }

        return $response;
    }

    public function getProfile()
    {
        return $this->request('GET', '/profile');
    }

    public function getGrades()
    {
        return $this->request('GET', '/grades');
    }

    public function getHomework()
    {
        return $this->request('GET', '/homework');
    }

    public function getProjects()
    {
        return $this->request('GET', '/projects');
    }

    private function request($method, $endpoint, $data = [])
    {
        $url = $this->apiUrl . $endpoint;

        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        if ($this->token) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
        ];

        if ($method === 'POST' || $method === 'PUT') {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        } elseif ($method === 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        if ($httpCode >= 400) {
            throw new Exception('API error: ' . $response, $httpCode);
        }

        return $responseData;
    }
}
