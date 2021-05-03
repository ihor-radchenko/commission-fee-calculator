build:
	docker build -t commission-fee-calculator .

run:
	docker run -v ~/Projects/commission-fee-calculator:/opt/project --rm commission-fee-calculator php bin/application.php storage/input.csv
