<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Process;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class BackupController
{
    public function __invoke(): BinaryFileResponse|StreamedResponse|RedirectResponse
    {
        $driver = config('database.default');

        /** @var array<string, mixed> $dbConfig */
        $dbConfig = config("database.connections.{$driver}");

        if ($dbConfig['driver'] === 'sqlite') {
            $path = (string) $dbConfig['database'];

            if ($path === ':memory:') {
                return redirect()
                    ->route('admin.settings.index')
                    ->with('error', 'In-memory SQLite database cannot be backed up.');
            }

            return response()->download($path, 'backup-'.now()->format('Y-m-d').'.sqlite');
        }

        $result = Process::run([
            'mysqldump',
            '--host='.(string) $dbConfig['host'],
            '--port='.(string) $dbConfig['port'],
            '--user='.(string) $dbConfig['username'],
            '--password='.(string) $dbConfig['password'],
            (string) $dbConfig['database'],
        ]);

        if ($result->failed()) {
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Database backup failed. Ensure mysqldump is available.');
        }

        $sql = $result->output();
        $filename = 'backup-'.now()->format('Y-m-d-His').'.sql';

        return new StreamedResponse(function () use ($sql): void {
            echo $sql;
        }, Response::HTTP_OK, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
