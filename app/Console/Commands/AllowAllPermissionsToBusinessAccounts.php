<?php

namespace App\Console\Commands;

use App\Permission;
use Illuminate\Console\Command;
use App\Role;

class AllowAllPermissionsToBusinessAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign-permissions:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The Command can be used to assign all the permissions to the existing business accounts. This command is very useful when a new permission is created and that permission should be assigned to all the existing business accounts.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $roles = Role::query()->where('name', '=', 'super_admin')->get();
        foreach ($roles as $role){
            $permissions = Permission::query()->select('id')->get()->pluck('id')->toArray();
            $role->perms()->sync($permissions);
        }
    }
}
