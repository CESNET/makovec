@include('vendor/autoload.php')

@setup
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, 'envoy');

    try {
        $dotenv->load();
        $dotenv->required([
            'TARGET_SERVER', 'TARGET_USER', 'TARGET_DIR',
            'REPOSITORY',
            'APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL',
            'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD',
        ])->notEmpty();
    } catch(Exception $e) {
        echo "Something went wrong:\n\n";
        echo "{$e->getMessage()} \n\n";
        exit;
    }

    $server                = $_ENV['TARGET_SERVER'];
    $user                  = $_ENV['TARGET_USER'];
    $dir                   = $_ENV['TARGET_DIR'];

    $repository            = $_ENV['REPOSITORY'];
    $branch                = $branch ?? 'main';

    $app_name              = $_ENV['APP_NAME'];
    $app_env               = $_ENV['APP_ENV'];
    $app_debug             = $_ENV['APP_DEBUG'];
    $app_url               = $_ENV['APP_URL'];

    $db_host               = $_ENV['DB_HOST'];
    $db_database           = $_ENV['DB_DATABASE'];
    $db_username           = $_ENV['DB_USERNAME'];
    $db_password           = $_ENV['DB_PASSWORD'];

    $mail_mailer           = $_ENV['MAIL_MAILER'] ?? 'log';
    $mail_host             = $_ENV['MAIL_HOST'] ?? 'localhost';
    $mail_port             = $_ENV['MAIL_PORT'] ?? '25';
    $mail_username         = $_ENV['MAIL_USERNAME'] ?? null;
    $mail_password         = $_ENV['MAIL_PASSWORD'] ?? null;
    $mail_encryption       = $_ENV['MAIL_ENCRYPTION'] ?? null;
    $mail_from_address     = $_ENV['MAIL_FROM_ADDRESS'] ?? null;
    $mail_from_name        = $_ENV['MAIL_FROM_NAME'] ?? null;
    $mail_replyto_address  = $_ENV['MAIL_REPLYTO_ADDRESS'] ?? null;
    $mail_replyto_name     = $_ENV['MAIL_REPLYTO_NAME'] ?? null;

    $slack_hook            = $_ENV['LOG_SLACK_WEBHOOK_URL'] ?? null;
    $slack_channel         = $_ENV['LOG_SLACK_CHANNEL'] ?? null;

    $destination = (new DateTime)->format('YmdHis');
    $symlink = 'current';
@endsetup

@servers(['web' => "$user@$server"])

@task('deploy', ['confirm' => true])
    echo "=> Install {{ $app_name }} into ~/{{ $dir }}/{{ $destination }}/ at {{ $user }}"@"{{ $server }}..."

    if [ ! -d {{ $dir }} ]; then
        echo "Create ~/{{ $dir }}/ directory"
        mkdir -p {{ $dir }}
    fi

    echo "Change directory to ~/{{ $dir }}/"
        cd {{ $dir }}

    echo "Clone '{{ $branch }}' branch of {{ $repository }} into ~/{{ $dir }}/{{ $destination }}/"
        git clone {{ $repository }} --branch={{ $branch }} --depth=1 -q {{ $destination }}

    if [ -f .env ]; then
        echo "~/{{ $dir }}/.env file exists, read its APP_KEY value"
        APP_KEY=`grep '^APP_KEY=' .env | grep 'base64:' | cut -d '=' -f 2-`
    fi

    echo "Change directory to ~/{{ $dir }}/{{ $destination }}/"
        cd {{ $destination }}

    echo "Prepare .env file"
        cp .env.example .env

    echo "Update .env file"
        sed -i "s%APP_NAME=.*%APP_NAME={{ $app_name }}%; \
        s%APP_ENV=.*%APP_ENV={{ $app_env }}%; \
        s%APP_DEBUG=.*%APP_DEBUG={{ $app_debug }}%; \
        s%APP_URL=.*%APP_URL={{ $app_url }}%; \
        s%DB_HOST=.*%DB_HOST={{ $db_host }}%; \
        s%DB_DATABASE=.*%DB_DATABASE={{ $db_database }}%; \
        s%DB_USERNAME=.*%DB_USERNAME={{ $db_username }}%; \
        s%DB_PASSWORD=.*%DB_PASSWORD={{ $db_password }}%; \
        s%MAIL_MAILER=.*%MAIL_MAILER={{ $mail_mailer }}%; \
        s%MAIL_HOST=.*%MAIL_HOST={{ $mail_host }}%; \
        s%MAIL_PORT=.*%MAIL_PORT={{ $mail_port }}%; \
        s%MAIL_USERNAME=.*%MAIL_USERNAME={{ $mail_username }}%; \
        s%MAIL_PASSWORD=.*%MAIL_PASSWORD={{ $mail_password }}%; \
        s%MAIL_ENCRYPTION=.*%MAIL_ENCRYPTION={{ $mail_encryption }}%; \
        s%MAIL_FROM_ADDRESS=.*%MAIL_FROM_ADDRESS={{ $mail_from_address }}%; \
        s%MAIL_FROM_NAME=.*%MAIL_FROM_NAME={{ $mail_from_name }}%; \
        s%MAIL_REPLYTO_ADDRESS=.*%MAIL_REPLYTO_ADDRESS={{ $mail_replyto_address }}%; \
        s%MAIL_REPLYTO_NAME=.*%MAIL_REPLYTO_NAME={{ $mail_replyto_name }}%; \
        s%LOG_SLACK_WEBHOOK_URL=.*%LOG_SLACK_WEBHOOK_URL={{ $slack_hook }}%; \
        s%LOG_SLACK_CHANNEL=.*%LOG_SLACK_CHANNEL=#{{ $slack_channel }}%" .env

    if [ ! -d ../storage ]; then
        echo "~/{{ $dir }}/storage/ directory does not exists, create it"
        mv storage ..

        echo "Fix ACL to ~/{{ $dir }}/storage/ directory"
        setfacl -Rm g:www-data:rwx,d:g:www-data:rwx ../storage
    else
        echo "Remove cloned storage/ directory as we already have one"
        rm -rf storage
    fi

    echo "Symlink ~/{{ $dir }}/storage/ to ~/{{ $dir }}/{{ $destination }}/storage/"
        ln -s ../storage

    echo "Fix permissions to bootstrap/cache/ directory"
        setfacl -Rm g:www-data:rwx,d:g:www-data:rwx bootstrap/cache

    echo "Install composer dependencies"
        composer install -q --no-dev --optimize-autoloader --no-ansi --no-interaction --no-progress --prefer-dist

    if [ -v APP_KEY ]; then
        echo "Reuse the current APP_KEY"
        sed -i "s%^APP_KEY=.*%APP_KEY=${APP_KEY}%" .env
    else
        echo "Generate a new APP_KEY"
        php artisan key:generate -q --no-ansi --no-interaction
    fi

    echo "Migrate database tables"
        php artisan migrate --force -q --no-ansi --no-interaction

    categories=`echo "SELECT count(*) FROM categories;" | mysql makovec | tail -1`
        if(($categories == 0)); then
            echo "Seed Categories table because it is empty"
            php artisan db:seed --force -q --no-ansi CategorySeeder
        fi

    echo "Optimize"
        php artisan optimize:clear -q --no-ansi --no-interaction

    echo "Cache config"
        php artisan config:cache -q --no-ansi --no-interaction

    echo "Cache routes"
        php artisan route:cache -q --no-ansi --no-interaction

    echo "Cache views"
        php artisan view:cache -q --no-ansi --no-interaction

    echo "Reload PHP-FPM"
        sudo systemctl reload php8.1-fpm

    echo "Copy ~/{{ $dir }}/{{ $destination }}/.env to ~/{{ $dir }}/.env"
        cp .env ..

    echo "Update ~/{{ $dir }}/{{ $symlink }}/ symlink to ~/{{ $dir }}/{{ $destination }}/"
        cd ..
        ln -snf {{ $destination }} {{ $symlink }}
@endtask

@task('cleanup')
    cd {{ $dir }}
    find . -maxdepth 1 -name "20*" | sort | head -n -3 | xargs rm -rf
    echo "Cleaned up all but the last 3 deployments."
@endtask

@finished
    @slack($slack_hook, $slack_channel, "$app_name deployed to $server.")
@endfinished
