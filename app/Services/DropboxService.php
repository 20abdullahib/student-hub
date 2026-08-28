<?php

namespace App\Services;

use App\Models\DropboxAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DropboxService
{
public function verifyCredentials(array $credentials): bool
{
    try {
        $clientId = trim($credentials['client_id']);
        $clientSecret = trim($credentials['client_secret']);
        $refreshToken = trim($credentials['refresh_token']);

        $response = Http::withoutVerifying() // <--- Disables cURL SSL certificate check
            ->asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->post('https://api.dropbox.com/oauth2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ]);

        if (!$response->successful()) {
            Log::error('Dropbox OAuth verification response error: ' . $response->body());
            return false;
        }

        return !empty($response->json('access_token'));
    } catch (\Exception $e) {
        Log::error('Dropbox verification failed: ' . $e->getMessage());
        return false;
    }
}

    public function refreshAccessToken(DropboxAccount $account): void
    {
        try {
            $response = Http::asForm()
                ->withBasicAuth($account->client_id, $account->client_secret)
                ->post('https://api.dropbox.com/oauth2/token', [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $account->refresh_token,
                ]);

            if ($response->successful()) {
                $account->update([
                    'access_token' => $response->json('access_token'),
                    'token_expires_at' => now()->addSeconds($response->json('expires_in')),
                ]);
            } else {
                Log::error("Failed to refresh token for account {$account->id}: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Token refresh failed for account {$account->id}: " . $e->getMessage());
        }
    }

    public function ensureValidToken(DropboxAccount $account): void
    {
        if ($this->needsTokenRefresh($account)) {
            $this->refreshAccessToken($account);
            // Refresh account instance from database to get the new access_token
            $account->refresh();
        }
    }

    private function needsTokenRefresh(DropboxAccount $account): bool
    {
        // 1. Check database expiration timestamp first (avoids an unnecessary HTTP call)
        if ($account->token_expires_at && now()->addMinutes(2)->gte($account->token_expires_at)) {
            return true;
        }

        // 2. Fallback check against Dropbox check endpoint
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $account->access_token,
                'Content-Type' => 'application/json',
            ])->post('https://api.dropboxapi.com/2/check/user', [
                'query' => 'ping'
            ]);

            return !$response->successful();
        } catch (\Exception $e) {
            Log::error("Token check failed: " . $e->getMessage());
            return true;
        }
    }

    public function getAccountFiles(DropboxAccount $account): array
    {
        $this->ensureValidToken($account);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $account->access_token,
                'Content-Type' => 'application/json',
            ])->post('https://api.dropboxapi.com/2/files/list_folder', [
                'path' => '',
            ]);

            return $response->successful() 
                ? $this->formatFiles($response->json()['entries'] ?? [], $account)
                : [];
        } catch (\Exception $e) {
            Log::error("File listing failed: " . $e->getMessage());
            return [];
        }
    }

    private function formatFiles(array $files, DropboxAccount $account): array
    {
        return collect($files)->filter(function ($file) {
            return ($file['.tag'] ?? '') === 'file';
        })->map(function ($file) use ($account) {
            return [
                'name' => $file['name'],
                'path' => $file['path_lower'],
                'link' => $this->getTemporaryLink($account, $file['path_lower']),
            ];
        })->values()->toArray();
    }

    private function getTemporaryLink(DropboxAccount $account, string $filePath): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $account->access_token,
                'Content-Type' => 'application/json',
            ])->post('https://api.dropboxapi.com/2/files/get_temporary_link', [
                'path' => $filePath,
            ]);

            return $response->successful() ? $response->json()['link'] : '';
        } catch (\Exception $e) {
            Log::error("Temporary link failed: " . $e->getMessage());
            return '';
        }
    }
}