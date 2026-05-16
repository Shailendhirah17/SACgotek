<?php

namespace App\Http\Controllers\SuperAdmin\SystemLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SystemLogController extends Controller
{
    /**
     * Display system logs.
     */
    public function index(Request $request)
    {
        $logPath = storage_path('logs');
        $logFiles = [];

        if (File::exists($logPath)) {
            $files = File::files($logPath);
            foreach ($files as $file) {
                if ($file->getExtension() === 'log') {
                    $logFiles[] = [
                        'name' => $file->getFilename(),
                        'size' => round($file->getSize() / 1024, 2) . ' KB',
                        'date' => date('Y-m-d H:i:s', $file->getMTime()),
                    ];
                }
            }
            usort($logFiles, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
        }

        // Read selected log
        $selectedLog = $request->get('file', 'laravel.log');
        $logContent = '';
        $logFilePath = $logPath . '/' . basename($selectedLog);

        if (File::exists($logFilePath)) {
            // Read last 500 lines
            $lines = file($logFilePath);
            $logContent = implode('', array_slice($lines, -500));
        }

        return view('backEnd.superAdmin.system_logs.index', compact('logFiles', 'logContent', 'selectedLog'));
    }

    /**
     * Clear a specific log file.
     */
    public function clear(Request $request)
    {
        $filename = basename($request->input('file', 'laravel.log'));
        $filepath = storage_path('logs/' . $filename);

        if (File::exists($filepath)) {
            File::put($filepath, '');
            return back()->with('message-success', "Log file '{$filename}' cleared.");
        }

        return back()->with('message-danger', 'Log file not found.');
    }

    /**
     * Download a log file.
     */
    public function download($filename)
    {
        $filepath = storage_path('logs/' . basename($filename));

        if (File::exists($filepath)) {
            return response()->download($filepath);
        }

        return back()->with('message-danger', 'Log file not found.');
    }
}
