PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT { 
	?domain a bgo:Domain ;
	 	bgo:title ?title ;
        bgo:description ?description ;
        bgo:abstract ?abstract ;
        bgo:hasSocialSharing  ?socialSharing ;
        bgo:hasCopyrigth ?copyright ;
        
        ?hasMenu ?menu
    .
    
    ?menu bgo:withCustomMenuItems ?list .
	?listRest rdf:first ?head ; rdf:rest ?tail .
	?head ?p ?o .
} 
WHERE {
	?domain a bgo:Domain .
	OPTIONAL { ?domain bgo:title ?title }
	OPTIONAL { ?domain bgo:description ?description }
	OPTIONAL { ?domain bgo:abstract ?abstract }
	OPTIONAL { ?domain bgo:hasSocialSharing  ?socialSharing }
	OPTIONAL { ?domain bgo:hasCopyrigth ?copyright }

	
	# See https://stackoverflow.com/questions/44221975/how-to-write-a-sparql-construct-query-that-returns-an-rdf-list
	OPTIONAL { 
		
    	VALUES ?hasMenu {
    		bgo:hasNavigationMenu
    		bgo:hasOptionMenu
    		bgo:hasFooterMenu
    	}
    	
		?domain ?hasMenu ?menu .
		?menu bgo:withCustomMenuItems ?list .
		?list rdf:rest* ?listRest .
		?listRest rdf:first ?head ; rdf:rest ?tail .
		
		?head ?p ?o
     }

}