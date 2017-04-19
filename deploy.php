<?php

namespace Deployer;

require_once 'recipe/common.php';

function cronjob_exists($command) {
    $cronjob_exists = false;
    $crontab = run('crontab -l');
    $crontabs = explode("\n", $crontab);
    if (isset($crontab) && is_array($crontabs)) {
        $crontabs = array_flip($crontabs);
        $cronjob_exists = isset($crontabs[$command]);
    }
    return $cronjob_exists;
}

function append_cronjob($command) {
    if (is_string($command) && !empty($command)) {
        $output = run('echo -e "`crontab -l`\n'.$command.'" | crontab -');
    }
    return $output;
}

// Configuration
serverList('deploy/servers.yml');

set('bin/npm', function () {
    return (string)run('which npm');
});

set('env', 'prod');
set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('http_user', 'www-data');
set('default_stage', 'production');
set('repository', 'git@github.com:tchapi/tuneefy.git');
set('clear_paths', [
  './README.md',
  './LICENSE',
  './.gitignore',
  './.htaccess.example',
  './config.example.php',
  './.gitignore',
  './.git',
  './package.json',
  './node_modules',
  './deploy',
  './deploy.php',
]);

// Deploy main task
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:minify_js',
    'deploy:clear_paths',
    'deploy:writable',
    'deploy:symlink',
    'deploy:crontab',
    'deploy:unlock',
    'cleanup',
]);

// Tasks
desc('Deploy production parameters');
task('deploy:parameters', function () {
    upload('./deploy/config.{{env}}.php', '{{deploy_path}}/release/config.php');
});

desc('Add crontab for the watchdog');
task('deploy:crontab', function () {
    $crontab = parse('00 06 * * * {{bin/php}} {{deploy_path}}/current/admin/watchdog/watchdog.php >> {{deploy_path}}/current/admin/watchdog/watchdog.log 2>&1');
    if (!cronjob_exists($crontab)) {
      append_cronjob($crontab);
      $output = run('crontab -l');
      writeln('<info>Cronjob isntalled</info>');
      writeln('<info>' . $output . '</info>');
    } else {
      writeln('<info>Cronjob already present</info>');
    }
});

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    // The user must have rights for restart service
    // /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
    run('sudo systemctl restart php7.0-fpm.service');
});

desc('Minify production JS');
task('deploy:minify_js', function() {
  run("cd {{release_path}} && {{bin/npm}} install && {{bin/npm}} run minify");
});

// Hooks
after('deploy', 'success');
after('deploy:symlink', 'php-fpm:restart');
after('deploy:update_code', 'deploy:parameters');
after('deploy:failed', 'deploy:unlock');
