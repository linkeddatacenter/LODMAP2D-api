PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#> 
CONSTRUCT {?s ?p ?o} 
WHERE {
	?s a bgo:CreditsView; ?p?o
}
