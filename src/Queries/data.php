#
# Returns all bgo data
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#> 
CONSTRUCT { ?s ?p ?o }
WHERE {
	?s ?p ?o 
	FILTER STRSTARTS(STR(?p), STR(bgo:))
}
