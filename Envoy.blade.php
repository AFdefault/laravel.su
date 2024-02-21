@servers(['web' => ['root@84.38.181.107']])

@task('deploy', ['on' => ['web']])
su deployer
cd /home/deployer/laravel.su/current
git pull

php artisan cache:clear
php artisan config:clear

composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --classmap-authoritative

php artisan view:clear
php artisan migrate --force
php artisan optimize
php artisan view:cache
php artisan storage:link
@endtask