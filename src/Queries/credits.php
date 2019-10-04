#
# Returns data related to a BGO Credits page
#  
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#> 
CONSTRUCT {
	?credits
    	bgo:icon ?icon ;
    	bgo:title ?title ;
    	bgo:abstract ?abstract	
} 
WHERE {
	<?php if ($domainId) echo "?domain bgo:domainId \"$domainId\" .";?>
	?domain bgo:hasCredits ?credits .
	OPTIONAL { ?credits bgo:icon ?icon }
	OPTIONAL { ?credits bgo:title ?title }
	OPTIONAL { ?credits bgo:abstract ?abstract}
}
