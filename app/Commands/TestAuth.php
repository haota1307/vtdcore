<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class TestAuth extends BaseCommand
{
    protected $group = 'RBAC';
    protected $name = 'test:auth';
    protected $description = 'Test authentication system';

    public function run(array $params)
    {
        CLI::write('=== Testing Authentication System ===', 'yellow');
        
        // Test AuthService
        $auth = service('auth');
        
        CLI::write("AuthService loaded: " . ($auth ? '✅ YES' : '❌ NO'), $auth ? 'green' : 'red');
        
        // Get current user
        $user = $auth->user();
        
        if ($user) {
            CLI::write("Current user:", 'green');
            CLI::write("- ID: {$user['id']}", 'white');
            CLI::write("- Username: {$user['username']}", 'white');
            CLI::write("- Email: {$user['email']}", 'white');
        } else {
            CLI::write("❌ No user logged in!", 'red');
        }
        
        // Test session
        CLI::write("\nSession info:", 'cyan');
        $session = service('session');
        $sessionData = $session->get();
        CLI::write("Session data keys: " . implode(', ', array_keys($sessionData)), 'white');
        
        // Check if user_id exists in session
        $userId = $session->get('user_id');
        CLI::write("User ID in session: " . ($userId ? $userId : 'NULL'), $userId ? 'green' : 'red');
        
        // Test login manually
        CLI::write("\nTesting manual login for user ID 2 (admin):", 'cyan');
        
        $db = Database::connect();
        $user = $db->table('users')->where('id', 2)->get()->getRowArray();
        
        if ($user) {
            CLI::write("Found user: {$user['username']} ({$user['email']})", 'green');
            
            // Try to login using attempt method
            $loginResult = $auth->attempt($user['email'], 'admin123');
            
            if ($loginResult) {
                CLI::write("✅ Login successful!", 'green');
                
                // Check user again
                $currentUser = $auth->user();
                if ($currentUser) {
                    CLI::write("Current user after login: {$currentUser['username']}", 'green');
                }
            } else {
                CLI::write("❌ Login failed!", 'red');
            }
        } else {
            CLI::write("❌ User ID 1 not found!", 'red');
        }
        
        CLI::write("\n=== Test Complete ===", 'yellow');
    }
}
