set -e
URLS=$(echo "    urls: [$(ls -d */ | grep -v "vendor" | awk -v ORS= '{print "{url: \"https://raw.githubusercontent.com/GenaBitu/HandbookAPI/master/" $1 "swagger.yaml\", name: \"Version " substr($1, 2) "\"}, "}' | sed 's/..$//')],")
git clone https://github.com/swagger-api/swagger-ui.git
cd swagger-ui
npm install
sed -i -e "/<title>Swagger UI<\/title>/c <title>Handbook API</title>" dist/index.html
sed -i -e "/url: \"http:\/\/petstore\.swagger\.io\/v2\/swagger\.json\",/c \\${URLS}" dist/index.html
grep -Fq "$URLS" dist/index.html
npm run build
