1) Installing Vendors
----------------------------------

    curl -s http://getcomposer.org/installer | php

    php composer.phar install

2) Checking your System Configuration
-------------------------------------

    php app/check.php

    http://localhost/path/to/symfony/app/web/config.php

3) Install database
--------------------------------

    php app/console doctrine:database:drop --force

    php app/console doctrine:database:create
    php app/console doctrine:schema:create
    php app/console doctrine:fixtures:load


4) Browsing
--------------------------------
