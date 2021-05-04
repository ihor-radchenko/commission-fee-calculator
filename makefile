build:
	docker build -t commission-fee-calculator .

install:
	docker run -v ${PWD}:/opt/project --rm commission-fee-calculator composer install

run-develop:
	docker run -v ${PWD}:/opt/project --rm \
		--env CURRENCIES_STORE_DRIVER=stub \
		commission-fee-calculator php bin/application.php storage/input.csv

run:
	docker run -v ${PWD}:/opt/project --rm commission-fee-calculator php bin/application.php storage/input.csv

test:
	docker run -v ${PWD}:/opt/project --rm commission-fee-calculator composer test
