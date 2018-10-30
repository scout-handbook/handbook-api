set -e
URLS=$(echo "    urls: [$(ls -d */ | grep -Ev "node_modules|vendor" | awk -v ORS= '{print "{url: \"https://raw.githubusercontent.com/GenaBitu/HandbookAPI/master/" $1 "swagger.yaml\", name: \"Version " substr($1, 2) "\"}, "}' | sed 's/..$//')],")
sed -i -e "/<title>Swagger UI<\/title>/c <title>Handbook API</title>" node_modules/swagger-ui-dist/index.html
sed -i -e "/url: \"https:\/\/petstore\.swagger\.io\/v2\/swagger\.json\",/c \\${URLS}" node_modules/swagger-ui-dist/index.html
