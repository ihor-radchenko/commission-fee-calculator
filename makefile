build:
	docker build -t commission-fee-calculator .

run-develop:
	docker run -v ~/Projects/commission-fee-calculator:/opt/project --rm \
		--env CURRENCIES_STORE_DRIVER=stub \
		commission-fee-calculator php bin/application.php storage/input.csv

run:
	docker run -v ~/Projects/commission-fee-calculator:/opt/project --rm commission-fee-calculator php bin/application.php storage/input.csv
