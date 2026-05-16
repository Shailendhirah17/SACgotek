<?php

namespace App\Http\Controllers\SuperAdmin\Backup;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    /**
     * Display backup management page.
     */
    public function index()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];

        if (File::exists($backupPath)) {
            $files = File::files($backupPath);
            foreach ($files as $file) {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => round($file->getSize() / (1024 * 1024), 2) . ' MB',
                    'date' => date('Y-m-d H:i:s', $file->getMTime()),
                    'path' => $file->getPathname(),
                ];
            }
            // Sort by date descending
            usort($backups, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
        }

        return view('backEnd.superAdmin.backup.index', compact('backups'));
    }

    /**
     * Create a new database backup.
     */
    public function create()
    {
        try {
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupPath . '/' . $filename;

            // Get database config
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            // Execute mysqldump
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s 2>&1',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            exec($command, $output, $returnVar);

            if ($returnVar !== 0 || !File::exists($filepath) || File::size($filepath) === 0) {
                // Fallback: try with artisan if mysqldump fails
                try {
                    Artisan::call('backup:run', ['--only-db' => true]);
                } catch (\Exception $e) {
                    // Create a simple schema dump
                    $tables = \DB::select('SHOW TABLES');
                    $sqlDump = "-- Database Backup: {$database}\n-- Date: " . date('Y-m-d H:i:s') . "\n\n";

                    foreach ($tables as $table) {
                        $tableName = array_values((array) $table)[0];
                        $createTable = \DB::select("SHOW CREATE TABLE `{$tableName}`");
                        if (!empty($createTable)) {
                            $sqlDump .= $createTable[0]->{'Create Table'} . ";\n\n";
                        }
                    }

                    File::put($filepath, $sqlDump);
                }
            }

            $currentAdmin = Auth::guard('superadmin')->user();
            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'backup_created',
                'System',
                null,
                "Created database backup: {$filename}"
            );

            return back()->with('message-success', "Backup created: {$filename}");

        } catch (\Exception $e) {
            Log::error('Backup creation failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to create backup: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file.
     */
    public function download($filename)
    {
        $filepath = storage_path('app/backups/' . basename($filename));

        if (!File::exists($filepath)) {
            return back()->with('message-danger', 'Backup file not found.');
        }

        return response()->download($filepath);
    }

    /**
     * Delete a backup file.
     */
    public function destroy($filename)
    {
        try {
            $filepath = storage_path('app/backups/' . basename($filename));

            if (File::exists($filepath)) {
                File::delete($filepath);

                $currentAdmin = Auth::guard('superadmin')->user();
                SuperAdminAuditLog::log(
                    $currentAdmin->id,
                    'backup_deleted',
                    'System',
                    null,
                    "Deleted backup: {$filename}"
                );

                return back()->with('message-success', 'Backup deleted.');
            }

            return back()->with('message-danger', 'Backup file not found.');

        } catch (\Exception $e) {
            Log::error('Backup deletion failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to delete backup.');
        }
    }
}
