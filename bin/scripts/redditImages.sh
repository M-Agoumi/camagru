curl -s -A "reddit scraper" "https://www.reddit.com/r/pics.json?limit=100" | \
jq -r '.data.children | map(.data.url_overridden_by_dest)[] | select(.)' | \
grep -E "(jpg|jpeg|png|mp4)$" | \
xargs wget -U "reddit downloader"
