PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#> 
CONSTRUCT {
	?account a bgo:Account ;
		bgo:accountId ?accountId ;
	    bgo:amount ?amount ;
	    bgo:referenceAmount ?referenceAmount ;
	    bgo:title ?title ;
	    bgo:description ?description ;
	    bgo:abstract ?abstract ;
	    bgo:depiction ?depiction ;
	    bgo:versionLabel ?versionLabel ;
	    bgo:hasHistoryRec ?historyRec ;
	    bgo:hasBreakdown ?breakdown ;
	    
	    ?projecting ?perspective
	 .
	 
	 ?historyRec bgo:versionLabel ?historyVersion ; bgo:amount ?historyAmount . 
	 ?breakdown bgo:title ?breakdownTitle; bgo:amount ?breakdownAmount . 
	 
	 ?perspective ?perspectivePropery ?perspectiveValue.
}
WHERE {
	
	VALUES ?projecting {
		bgo:usesHistoricalPerspective
		bgo:usesBreakdownPerspective
		bgo:usesMetadataPerspective
	}

	?account a bgo:Account ; bgo:accountId  ?accountId .
	FILTER( ?accountId = "<?php echo $resourceId;?>" )
	
    OPTIONAL { ?account bgo:amount ?amount }
    OPTIONAL { ?account bgo:referenceAmount ?referenceAmount }
    OPTIONAL { ?account bgo:title ?title }
    OPTIONAL { ?account bgo:description ?description }
    OPTIONAL { ?account bgo:abstract ?abstract }
    OPTIONAL { ?account bgo:depiction ?depiction }
    OPTIONAL { ?account bgo:versionLabel ?versionLabel }
	
	OPTIONAL { 
		?account bgo:hasHistoryRec ?historyRec .
		?historyRec  bgo:versionLabel ?historyVersion ; bgo:amount ?historyAmount
	}
	
	OPTIONAL { 
		?account bgo:hasBreakdown ?breakdown .
		?breakdown bgo:title ?breakdownTitle ;  bgo:amount ?breakdownAmount 
	}	
	
	OPTIONAL { 
		?account ?projecting ?perspective .
	 	?perspective ?perspectivePropery ?perspectiveValue
	}
	    
}