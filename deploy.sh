set -e
URLS=$(echo "    urls: [$(ls -d */ | grep -E "v.*\\..*" | awk -v ORS= '{print "{url: \"https://gitlab.com/skaut-handbook/handbook-api/raw/master/" $1 "swagger.yaml\", name: \"Version " substr($1, 2) "\"}, "}' | sed 's/..$//')],")
sed -i -e "/<title>Swagger UI<\/title>/c <title>Handbook API</title>" public/index.html
sed -i -e "/url: \"https:\/\/petstore\.swagger\.io\/v2\/swagger\.json\",/c \\${URLS}" public/index.html
