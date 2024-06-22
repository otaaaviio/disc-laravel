echo 'running sail up...'
./vendor/bin/sail up -d

echo 'initializing reverb'
./vendor/bin/sail art reverb:start
