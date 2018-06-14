<?php

namespace Deployer;

require 'recipe/symfony4.php';

// Project name
set('application', 'huwelijk');

// Project repository
set('repository', 'git@github.com:sandermarechal/wedding.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);

// Hosts
host('sylvester')
    ->set('branch', 'production')
    ->set('deploy_path', '/var/www/astrid-en-sander.nl')
;
    
// Tasks
desc('Build webpack assets');
task('assets:webpack', function () {
    run('cd {{release_path}} && yarn install');
    run('cd {{release_path}} && yarn run encore dev');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'database:migrate');
before('deploy:symlink', 'assets:webpack');
