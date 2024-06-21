echo 'copying .env.example to .env...'
cp .env.example .env

echo 'installing Composer dependencies within Docker container...'
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    --env-file .env \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

echo 'building Docker containers...'
./vendor/bin/sail build --no-cache

echo 'running sail up...'
./vendor/bin/sail up -d

echo 'generating application key...'
./vendor/bin/sail art key:generate

echo 'migrating database and seeding...'
./vendor/bin/sail art migrate:fresh --seed

echo 'optimizing laravel...'
./vendor/bin/sail art optimize

echo 'initializing reverb'
./vendor/bin/sail art reverb:start
