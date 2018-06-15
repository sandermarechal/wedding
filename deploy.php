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

desc('Reload PHP');
task('php:reload', function () {
    run('sudo /etc/init.d/php7.1-fpm reload');
});

// Hook tasks into flow
before('deploy:symlink', 'database:migrate');
before('deploy:symlink', 'assets:webpack');
after('deploy:symlink', 'php:reload');
after('deploy:failed', 'deploy:unlock');
