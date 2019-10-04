#
# Returns data related to a BGO Terms page
#  
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#> 
CONSTRUCT {
	?terms
    	bgo:icon ?icon ;
    	bgo:title ?title ;
    	bgo:abstract ?abstract	
} 
WHERE {
	<?php if ($domainId) echo "?domain bgo:domainId \"$domainId\" .";?>
	?domain bgo:hasTerms ?terms .
	OPTIONAL { ?terms bgo:icon ?icon }
	OPTIONAL { ?terms bgo:title ?title }
	OPTIONAL { ?terms bgo:abstract ?abstract}
}
