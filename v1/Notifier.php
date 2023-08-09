<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 06/04/20
 * Time: 07:55
 */
require_once 'Library.php';

class Notifier
{

    public $endpoint = "https://api.psmt.pidva.africa";

    public function __construct()
    {

    }

    public function notify($reference_number,$progressStatus,$progressPercentage,$description) {
https://agmapi.legitimate-technology.co.ke/api
        $response = array(
            'description' => $description,
            'referenceNumber' => $reference_number,
            'progressStatus' => $progressStatus,
            'progressPercentage' => $progressPercentage
        );

        $url = $this->endpoint."/api/v1/request/update";

        // send callback

        // Create a new cURL resource
        $ch = curl_init($url);

        $payload = json_encode($response);

        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Token:69c5b67d41d43b6c2d284d912767c93dd057180d2eedd8f84aa76e5847861615'
        ));

        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the POST request
        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);

        return;
    }

    public function generateAPIKeys($client_id) {

        $response = array(
            'client_id' => $client_id
        );

        $url = $this->endpoint."/api/v1/key/create";

        // send callback

        // Create a new cURL resource
        $ch = curl_init($url);

        $payload = json_encode($response);

        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Token:69c5b67d41d43b6c2d284d912767c93dd057180d2eedd8f84aa76e5847861615'
        ));

        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the POST request
        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);

        return;
    }

    public function resetPassword($client_id,$account_type) {

        $response = array(
            'client_id' => $client_id,
            'account_type' => $account_type,
        );

        $url = $this->endpoint."/api/v1/email/password";

        // send callback

        // Create a new cURL resource
        $ch = curl_init($url);

        $payload = json_encode($response);

        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Token:69c5b67d41d43b6c2d284d912767c93dd057180d2eedd8f84aa76e5847861615'
        ));

        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the POST request
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close cURL resource
        curl_close($ch);

        return $status;
    }

}
