<?php

namespace App\Console\Commands;

use DB;
use Validator;
use App\Enums\Roles;
use App\Models\Role;
use App\Models\User;
use App\Models\Email;
use App\Enums\AppModes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Users\ExternalCreateRequest;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'First command to run to install application, migrate, seed, update and create first user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        // Welcome message
        $this->displayWelcomeMessage();
        $this->confirm("Are you ready to begin ?");

        // migrate db
        $this->displaySectionMessage('Migrate Database Tables');
        $this->migrateDB();

        // seed db
        $this->displaySectionMessage('Seed static datas');
        $this->seedDB();

        // create link
        $this->displaySectionMessage('Create symlink for storage');
        $this->createStorageLink();

        // Create API key
        $this->displaySectionMessage('Create an API Key for Backoffice App');
        $this->createApiKeys();

        // Create goodfood user
        $this->displaySectionMessage('Create the root user');
        $this->createRoot();

        // Set app mode
        $this->displaySectionMessage('Set the app mode');
        $this->setAppMode();

        return 0;


    }

    /**
     * Migrate les tables de la BDD
     *
     * @return void
     */
    public function migrateDB() : void {
        $this->call('migrate', [
            '--force' => true
        ]);
    }

    /**
     * Seed static data
     *
     * @return void
     */
    public function seedDB() : void {
        $this->call('db:seed', [
            '--force' => true
        ]);
    }

    /**
     * Create the storage link for public storage
     *
     * @return void
     */
    public function createStorageLink() : void {
        $this->call('storage:link');
    }

    /**
     * Create the admin account
     *
     * @return void
     */
    public function createRoot() : void {

        if(User::count() > 0) {
            $this->warn("An user is allready exist in the system, skip this task");
            return ;
        }

        $email_input = $this->ask("Set email for the admin account");

        $lastname = $this->ask("Set name for the admin account");

        $firstname = $this->ask("Set the firstname for the admin account");

        $phone = $this->ask('Set phone for the admin account');
        
        $password = $this->secret("Enter the password for the admin account");

        $confirm_password = $this->secret("Confirm the password");

        
        $validator = Validator::make([
            'email' => $email_input,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phone' => $phone,
            'password' => $password,
            'password_confirmation' => $confirm_password
        ], (new ExternalCreateRequest)->rules());
        
        if($validator->fails()) {
            $errors = $validator->errors()->all();
            foreach($errors as $key => $error) {
                $this->warn("{$key} : $error");
            }
            return ;
        }
        
        $role = Role::whereCode(Roles::goodfood->value)->first();
        
        $email = Email::create(['email' => $email_input]);
        
        $password = Hash::make($password);
        
        $user = new User(compact('lastname', 'firstname', 'phone', 'password'));

        $user->emailLogin()->associate($email);

        $user->save();
        
        $user->roles()->sync([$role->id]);

    }

    /**
     * Set App mode
     *
     * @return void
     */
    public function setAppMode() : void {

        $mode = $this->choice(
            "Turn application mode to : ", 
            [
                AppModes::configuration->value, 
                AppModes::normal->value
            ]
        );

        $this->call("app:mode", [
            'mode' => $mode
        ]);

    }

    /**
     * Display the welcome installer message
     *
     * @return void
     */
    public function displayWelcomeMessage() : void {

        $this->info('--------------------------------------------------');
        $this->info('');
        $this->info(strtoupper("Welcome to GoodFood installer"));
        $this->info(strtoupper("This is the wizard installation"));
        $this->info(strtoupper("After this command you will able to use the API"));
        $this->info('');
        $this->info('--------------------------------------------------');

    }

    /**
     * Show a section message
     *
     * @param string $message
     * @return void
     */
    public function displaySectionMessage(string $message) : void {

        $this->info('--------------------------------');
        $this->info('');
        $this->info(strtoupper($message));
        $this->info('');
        $this->info('--------------------------------');

    }

    /**
     * Créé les clés d'API si aucune clé n'existe
     *
     * @return void
     */
    public function createApiKeys() : void {

        if(DB::table('oauth_clients')->count('id') > 0) {
            $this->warn("Apis key allready exists, skipping this task");
        } else {
            $this->warn("Please keep this keys, you will not able to retreive it");
            // Create backoffice client
            $this->call("passport:client", [
                '--client' => true,
                '--name' => "GOODFOODAPI-Backoffice"
            ]);

            // Create mobile client
            $this->call("passport:client", [
                '--client' => true,
                '--name' => "GOODFOODAPI-Mobile"
            ]);
        }

    }

}
