PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#> 
CONSTRUCT {?s ?p ?o} WHERE {
	{ GRAPH <urn:bgo:partition:<?php echo "$resourceId"?>> {?s?p?o} }
	UNION
	{ ?s bgo:partitionId "<?php echo "$resourceId"?>" ; ?p ?o }
}