<?php

namespace Deployer;

require 'recipe/symfony.php';

// Project name
set('application', 'huwelijk');

// Project repository
set('repository', 'file:///home/sander/dev/huwelijk');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys 
add('shared_files', ['.env']);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);

// Hosts
host('sylvester')
    ->set('branch', 'production')
    ->set('deploy_path', '/var/www/astrid-en-sander.nl')
;
    
// Tasks
task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'database:migrate');
