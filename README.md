## 1:

```sh
composer install
```

## 2:

```sh
cp .env.example .env
```

## 3:

```sh
php artisan key:generate
```

## 4:

```sh
php artisan storage:link
```

## 5:

```sh
sh docker-script/compose_up.sh
```

## 6:

Domain chính:
```
http://127.0.0.1:81/
```

phpMyAdmin:
```
http://127.0.0.1:8081/
```




















































########################################################
docker laravel with kafka

```
docker file
version: '2.2'
services:
    nginx:
        build:
            context: .
            dockerfile: docker/Dockerfile.nginx
            args:
                PHP_FPM_SERVER: php
                PHP_FPM_PORT: 9000
        ports:
            - ${DOCKER_NGINX_EXTERNAL_PORT-8000}:80
        depends_on:
            - php
        volumes:
            - .:/var/www/
        networks:
            - backend

    php:
        build:
            context: .
            dockerfile: docker/Dockerfile
            args:
                XDEBUG: "true"
        volumes:
            - .:/var/www/
            - ./docker/php.ini:/usr/local/etc/php/conf.d/php.ini
        environment:
            APP_ENV: local
        networks:
            - backend

    redis:
        image: redis:5.0.6
        ports:
            - ${DOCKER_REDIS_EXTERNAL_PORT-6379}:6379
        networks:
            - backend

    mysql:
        image: mysql:8.0
        volumes:
            - mysqldb:/var/lib/mysql
        environment:
            MYSQL_DATABASE: app_db
            MYSQL_ROOT_PASSWORD: secret
            MYSQL_ALLOW_EMPTY_PASSWORD: "true"
        ports:
            - "3309:3306"
        command: [ 'mysqld', '--character-set-server=utf8mb4', '--collation-server=utf8mb4_unicode_ci' ]
        networks:
            - backend

    kafka-ui:
        image: provectuslabs/kafka-ui:latest
        ports:
            - "8080:8080"
        depends_on:
            - kafka0
            - schema-registry0
            - kafka-connect0
        environment:
            KAFKA_CLUSTERS_0_NAME: local
            KAFKA_CLUSTERS_0_BOOTSTRAPSERVERS: kafka0:29092
            KAFKA_CLUSTERS_0_METRICS_PORT: 9997
            KAFKA_CLUSTERS_0_SCHEMAREGISTRY: http://schema-registry0:8085
            KAFKA_CLUSTERS_0_KAFKACONNECT_0_NAME: first
            KAFKA_CLUSTERS_0_KAFKACONNECT_0_ADDRESS: http://kafka-connect0:8083
            DYNAMIC_CONFIG_ENABLED: 'true'  # not necessary, added for tests
        networks:
            - backend

    zookeeper:
        image: confluentinc/cp-zookeeper:7.2.1
        hostname: zookeeper
        ports:
            - "2181:2181"
        volumes:
            - zookeeperData:/var/lib/zookeeper/data
            - zookeeperTxnLogs:/var/lib/zookeeper/log
        environment:
            ZOOKEEPER_CLIENT_PORT: 2181
            ZOOKEEPER_TICK_TIME: 2000
        networks:
            - backend

    kafka0:
        image: confluentinc/cp-kafka:7.2.1
        hostname: kafka0
        depends_on:
            - mysql
            - zookeeper
        ports:
            - "9092:9092"
            - "9997:9997"
        networks:
            - backend
        volumes:
            - kafkaData:/var/lib/kafka/data
        environment:
            KAFKA_BROKER_ID: 1
            KAFKA_ZOOKEEPER_CONNECT: 'zookeeper:2181'
            KAFKA_LISTENER_SECURITY_PROTOCOL_MAP: PLAINTEXT:PLAINTEXT,PLAINTEXT_HOST:PLAINTEXT
            KAFKA_ADVERTISED_LISTENERS: PLAINTEXT://kafka0:29092,PLAINTEXT_HOST://localhost:9092
            KAFKA_OFFSETS_TOPIC_REPLICATION_FACTOR: 1
            KAFKA_GROUP_INITIAL_REBALANCE_DELAY_MS: 0
            KAFKA_CONFLUENT_LICENSE_TOPIC_REPLICATION_FACTOR: 1
            KAFKA_CONFLUENT_BALANCER_TOPIC_REPLICATION_FACTOR: 1
            KAFKA_TRANSACTION_STATE_LOG_MIN_ISR: 1
            KAFKA_TRANSACTION_STATE_LOG_REPLICATION_FACTOR: 1
            KAFKA_JMX_PORT: 9997
            KAFKA_JMX_HOSTNAME: kafka0

    schema-registry0:
        image: confluentinc/cp-schema-registry:7.2.1
        ports:
            - "8085:8085"
        depends_on:
            - kafka0
        networks:
            - backend
        environment:
            SCHEMA_REGISTRY_KAFKASTORE_BOOTSTRAP_SERVERS: PLAINTEXT://kafka0:29092
            SCHEMA_REGISTRY_KAFKASTORE_SECURITY_PROTOCOL: PLAINTEXT
            SCHEMA_REGISTRY_HOST_NAME: schema-registry0
            SCHEMA_REGISTRY_LISTENERS: http://schema-registry0:8085

            SCHEMA_REGISTRY_SCHEMA_REGISTRY_INTER_INSTANCE_PROTOCOL: "http"
            SCHEMA_REGISTRY_LOG4J_ROOT_LOGLEVEL: INFO
            SCHEMA_REGISTRY_KAFKASTORE_TOPIC: _schemas

    kafka-connect0:
        build:
            context: .
            dockerfile: ./docker/Dockerfile.kafkaconnect
            args:
                image: confluentinc/cp-kafka-connect:7.2.1
        ports:
            - "8083:8083"
        depends_on:
            - kafka0
            - schema-registry0
        networks:
            - backend
        environment:
            CONNECT_BOOTSTRAP_SERVERS: kafka0:29092
            CONNECT_GROUP_ID: compose-connect-group
            CONNECT_CONFIG_STORAGE_TOPIC: _connect_configs
            CONNECT_CONFIG_STORAGE_REPLICATION_FACTOR: 1
            CONNECT_OFFSET_STORAGE_TOPIC: _connect_offset
            CONNECT_OFFSET_STORAGE_REPLICATION_FACTOR: 1
            CONNECT_STATUS_STORAGE_TOPIC: _connect_status
            CONNECT_STATUS_STORAGE_REPLICATION_FACTOR: 1
            CONNECT_KEY_CONVERTER: org.apache.kafka.connect.storage.StringConverter
            CONNECT_KEY_CONVERTER_SCHEMA_REGISTRY_URL: http://schema-registry0:8085
            CONNECT_VALUE_CONVERTER: org.apache.kafka.connect.storage.StringConverter
            CONNECT_VALUE_CONVERTER_SCHEMA_REGISTRY_URL: http://schema-registry0:8085
            CONNECT_INTERNAL_KEY_CONVERTER: org.apache.kafka.connect.json.JsonConverter
            CONNECT_INTERNAL_VALUE_CONVERTER: org.apache.kafka.connect.json.JsonConverter
            CONNECT_REST_ADVERTISED_HOST_NAME: kafka-connect0
            CONNECT_PLUGIN_PATH: "/usr/share/java,/usr/share/confluent-hub-components"

volumes:
    mysqldb:
        driver: local
    kafkaData:
        driver: local
    zookeeperData:
        driver: local
    zookeeperTxnLogs:
        driver: local

networks:
    backend:
        driver: "bridge"
```



