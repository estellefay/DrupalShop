dcomposer:
	docker-compose exec web composer install

bash:
	docker-compose exec web bash

dup:
	sudo chmod -R 777 .
	sudo chmod 644 docker/db/custom.cnf
	docker-compose up

dupd:
	sudo chmod -R 777 .
	sudo chmod 644 docker/db/custom.cnf
	docker-compose up -d

dbuild:
	docker-compose build

drm:
	docker-compose rm

dps:
	docker-compose ps
	
dstop:
	docker-compose stop

drestart: dstop dupd

###