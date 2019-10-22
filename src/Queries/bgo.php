#
# Returns all bgo data
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#> 
CONSTRUCT { ?s ?p ?o }
WHERE {
	{ ?s ?p ?o FILTER STRSTARTS(STR(?p), STR(bgo:)) }
	UNION 
	{
		# in BGO all types can be inferred from functional properties except for GroupFunction subclasses
		[] bgo:withGroupFunction ?s .
		?s ?p ?o 
		FILTER( ?p  = rdf:type ) 
	}
}
