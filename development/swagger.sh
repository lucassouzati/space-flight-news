#!/bin/bash

php ../vendor/bin/openapi --bootstrap ./swagger-constants.php --output ../public/swagger ./swagger.php ../app/Http/Controllers/Api
