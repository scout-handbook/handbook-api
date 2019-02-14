set -e
mkdir -p public/definitions
for dir in $(ls -d */ | grep -E "v.*\\..*" | sed 's/.$//');
	do
		cp $dir/swagger.yaml public/definitions/$dir.yaml;
	done;
URLS=$(echo "    urls: [$(ls -d */ | grep -E "v.*\\..*" | sed 's/.$//' | awk -v ORS= '{print "{url: \"definitions/" $1 ".yaml\", name: \"Version " substr($1, 2) "\"}, "}' | sed 's/..$//')],")
sed -i -e "/<title>Swagger UI<\/title>/c <title>Handbook API</title>" public/index.html
sed -i -e "/url: \"https:\/\/petstore\.swagger\.io\/v2\/swagger\.json\",/c \\${URLS}" public/index.html
