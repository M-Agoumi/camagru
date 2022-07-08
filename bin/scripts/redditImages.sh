if [ "$#" -ne 1 ]; then
	curl -s -A "reddit scraper" "https://www.reddit.com/r/pics.json?limit=100" >> var/source.json
	cat "var/source.json" | \
	jq -r '.data.children | map(.data.url_overridden_by_dest)[] | select(.)' | \
	grep -E "(jpg|jpeg|png|mp4)$" | \
	xargs wget -P "public/uploads/post" -U "reddit downloader"
else
	for var in "$@"
    do
    	curl -s -A "reddit scraper" "https://www.reddit.com/r/$var.json?limit=50" >> var/source.json
    	cat "var/source.json" | \
        jq -r '.data.children | map(.data.url_overridden_by_dest)[] | select(.)' | \
        grep -E "(jpg|jpeg|png|mp4)$" | \
        xargs wget -P "public/uploads/post" -U "reddit downloader"
    done
fi
