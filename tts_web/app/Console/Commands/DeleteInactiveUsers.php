<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeleteInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:inactive-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete inactive users based on specified conditions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ninetyDaysAgo = Carbon::now()->subMinutes(10)->toDateTimeString();

        $usersToDelete = DB::table('users')
            ->leftJoin('news', 'users.id', '=', 'news.id_user')
            ->where('users.role', 'user')
            ->where(function ($query) use ($ninetyDaysAgo) {
                $query->whereNull('news.id') // Chưa có bài viết
                    ->orWhere('news.created_at', '<=', $ninetyDaysAgo); // Bài viết cuối cùng lớn hơn 90 ngày
            })
            ->where('users.created_at', '<=', $ninetyDaysAgo) // Tạo tài khoản cách đây hơn 90 ngày
            ->select('users.id')
            ->get();

        foreach ($usersToDelete as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update ([
                    'deleted_at' => Carbon::now(),
                ]);
            $this->info('User ID ' . $user->id . ' has been soft deleted.');
        }

        $this->info('Inactive users have been processed.');
    }
}
