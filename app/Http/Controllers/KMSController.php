<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Storage;

class KMSController extends Controller
{
    public function encryptData($data,$userId)
    {
        // Generate a random encryption key of 256 bits (32 bytes)
        $encryptionKey = random_bytes(32);

        // Encrypt the data using AES-256-CBC algorithm
        $encryptedData = $this->encryptWithAES($data, $encryptionKey);

        // Associate the encryption key with the user ID and save it in a JSON file
        $this->saveKeyToFile($userId, $encryptionKey);

        // Return the encrypted data
        return  $encryptedData;
    }

    public function encryptCard($data, $userId)
    {
    // Retrieve the encryption key associated with the user ID
    $encryptionKey = $this->getKeyFromFile($userId);

    // Encrypt the data using AES-256-CBC algorithm
    $encryptedData = $this->encryptWithAES($data, $encryptionKey);

    return $encryptedData;
    }

    public function decryptData($data,$userId)
    {
        // Retrieve the encryption key associated with the user ID from the JSON file
        $encryptionKey = $this->getKeyFromFile($userId);

        // Decrypt the data using the encryption key
        $decryptedData = $this->decryptWithAES($data, $encryptionKey);

        // Return the decrypted data
        return  $decryptedData;
    }

    private function encryptWithAES($data, $encryptionKey)
    {
        // Create an instance of Encrypter with the encryption key and AES-256-CBC algorithm
        $encrypter = new Encrypter($encryptionKey, 'AES-256-CBC');

        // Encrypt the data
        return $encrypter->encrypt($data);
    }

    private function decryptWithAES($encryptedData, $encryptionKey)
    {
        // Create an instance of Encrypter with the encryption key and AES-256-CBC algorithm
        $encrypter = new Encrypter($encryptionKey, 'AES-256-CBC');

        // Decrypt the data
        return $encrypter->decrypt($encryptedData);
    }

    private function saveKeyToFile($userId, $encryptionKey)
    {
        // Load existing keys from the JSON file
        $keys = json_decode(Storage::get('database/encryption_keys.json'), true) ?? [];
    
        // Associate the encryption key with the user ID
        $keys[$userId] = base64_encode($encryptionKey);
    
        // Save the updated keys to the JSON file
        Storage::put('database/encryption_keys.json', json_encode($keys));
    }
    
    private function getKeyFromFile($userId)
    {
        // Load keys from the JSON file
        $keys = json_decode(Storage::get('database/encryption_keys.json'), true);
    
        // Retrieve the encryption key associated with the user ID
        return isset($keys[$userId]) ? base64_decode($keys[$userId]) : null;
    }
    
}
