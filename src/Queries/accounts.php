PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#> 
CONSTRUCT{
	?s a bgo:Account ;
		bgo:accountId ?accountId ;
	    bgo:amount ?amount ;
	    bgo:referenceAmount ?referenceAmount ;
	    bgo:title ?title  ;
	    bgo:description ?description ;
	    bgo:depiction ?depiction	
} 
WHERE {
	?s a bgo:Account ;
		bgo:accountId ?accountId ;
	    bgo:amount ?amount .
	    
	OPTIONAL { ?s bgo:referenceAmount ?referenceAmount }
	OPTIONAL { ?s bgo:title ?title  }
	OPTIONAL { ?s bgo:description ?description }
	OPTIONAL { ?s bgo:depiction ?depiction	}
}