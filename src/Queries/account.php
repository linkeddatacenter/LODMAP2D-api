#
# Returns data related to an Account with bgo:accountId = $resourceId
# To be consistent with BGO, data about perspectives are also required (see. perspectives.php )
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#> 
CONSTRUCT {
	?account 
		bgo:accountId ?accountId ;
	    bgo:amount ?amount ;
	    bgo:referenceAmount ?referenceAmount ;
	    bgo:title ?title ;
	    bgo:description ?description ;
	    bgo:abstract ?abstract ;
	    bgo:depiction ?depiction ;
	    bgo:versionLabel ?versionLabel ;
	    bgo:hasHistoryRec ?historyRec ;
	    bgo:hasBreakdown ?breakdown 
	 .
	 
	 ?historyRec bgo:versionLabel ?historyVersion ; bgo:amount ?historyAmount . 
	 ?breakdown bgo:title ?breakdownTitle; bgo:amount ?breakdownAmount . 
}
WHERE {
	?account bgo:accountId  ?accountId ; bgo:amount ?amount .
	FILTER( ?accountId = "<?php echo $resourceId;?>" )
	
	<?php if ($domainId) echo "?domain bgo:domainId \"$domainId\"; bgo:hasAccount/bgo:accountId \"$resourceId\" . ";?>
	
	OPTIONAL { ?account bgo:title ?title }
    OPTIONAL { ?account bgo:referenceAmount ?referenceAmount }
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
	    
}