lint:
	vendor/bin/php-cs-fixer fix src
unit:
	vendor/symfony/phpunit-bridge/bin/simple-phpunit -c phpunit.xml.dist
