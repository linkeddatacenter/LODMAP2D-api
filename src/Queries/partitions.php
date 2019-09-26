PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT {
	?s ?p ?o
} WHERE {

	VALUES ?firstLevelTypes {
		bgo:Overview
		bgo:Partition
		bgo:TableView
		bgo:TagCloud
		bgo:TrendColorScheme
	}
	
	
	VALUES ?listProp {
		bgo:hasPartitionList
		bgo:rateTreshold
	}
	

	# all first level subjects
	{ ?s a ?firstLevelTypes }
	
	# plus all objects referred by first level subjects
	UNION { ?firstlevel a ?firstLevelTypes; ?x ?s }
	
	# plus all rdf lists (any level). 
	UNION { ?subject ?listProp ?s }
	UNION {
		?subject ?listProp ?list .
		?list rdf:rest* ?s
	}
	UNION {
    	?subject ?listProp ?list.
    	?list rdf:rest* ?listRest.
    	?listRest rdf:first ?s 
	}

	
	?s ?p ?o	
		
}
