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
            $response = Http::asForm()->post('https://api.dropbox.com/oauth2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $credentials['refresh_token'],
                'client_id' => $credentials['client_id'],
                'client_secret' => $credentials['client_secret'],
            ]);

            return $response->successful() && $response->json('access_token');
        } catch (\Exception $e) {
            Log::error('Dropbox verification failed: ' . $e->getMessage());
            return false;
        }
    }

    public function refreshAccessToken(DropboxAccount $account): void
    {
        try {
            $response = Http::asForm()->post('https://api.dropbox.com/oauth2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $account->refresh_token,
                'client_id' => $account->client_id,
                'client_secret' => $account->client_secret,
            ]);

            if ($response->successful()) {
                $account->update([
                    'access_token' => $response->json('access_token'),
                    'token_expires_at' => now()->addSeconds($response->json('expires_in')),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Token refresh failed for account {$account->id}: " . $e->getMessage());
        }
    }

    public function ensureValidToken(DropboxAccount $account): void
    {
        if ($this->needsTokenRefresh($account)) {
            $this->refreshAccessToken($account);
        }
    }

    private function needsTokenRefresh(DropboxAccount $account): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $account->access_token,
                'Content-Type' => 'application/json',
            ])->post('https://api.dropboxapi.com/2/check/user', []);

            return !$response->successful();
        } catch (\Exception $e) {
            Log::error("Token check failed: " . $e->getMessage());
            return true;
        }
    }

    public function getAccountFiles(DropboxAccount $account): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $account->access_token,
                'Content-Type' => 'application/json',
            ])->post('https://api.dropboxapi.com/2/files/list_folder', [
                'path' => '',
            ]);

            return $response->successful() 
                ? $this->formatFiles($response->json()['entries'], $account)
                : [];
        } catch (\Exception $e) {
            Log::error("File listing failed: " . $e->getMessage());
            return [];
        }
    }

    private function formatFiles(array $files, DropboxAccount $account): array
    {
        return collect($files)->filter(function ($file) {
            return $file['.tag'] === 'file';
        })->map(function ($file) use ($account) {
            return [
                'name' => $file['name'],
                'path' => $file['path_lower'],
                'link' => $this->getTemporaryLink($account, $file['path_lower']),
            ];
        })->toArray();
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