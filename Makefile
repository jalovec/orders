DOCKER_DIR=docker

up:
	composer install

install:
	@cd ${DOCKER_DIR} && docker compose up -d --build && \
	if [ $$? -eq 0 ]; then \
	    echo 'System is running at http://localhost:8000'; \
	else \
	    echo 'Failed to start system. Check Docker logs for details.'; \
	fi

db:
	@echo 'Start migrations'
	@docker exec -it docker-php php bin/console doctrine:migrations:migrate

