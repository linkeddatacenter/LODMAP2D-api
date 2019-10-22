#
# Returns BGO portion to render the partitions ( requires data provided by app.php and overview.php)
#
# It should generate something like:
##	<urn:bgo:test:p1> bgo:hasAccountSubSet <urn:bgo:test:p1_1>,
##	        <urn:bgo:test:p1_2> ;
##	    bgo:hasDefaultAccountSubSet [ ] ;
##	    bgo:withGroupFunction [ a bgo:TrendAverage ;
##	            bgo:hasTotalizer [ bgo:filteredFormat "€ %s"^^bgo:Template ;
##	                    bgo:format "€ %s" ;
##	                    bgo:precision 2 ;
##	                    bgo:ratioFormatter <urn:bgo:test:trend_formatter> ] ] ;
##	    bgo:withSortCriteria bgo:natural_sort ;
##	    bgo:withSortOrder bgo:ascending_sort .
##	
##	<urn:bgo:test:p2> bgo:hasAccountSubSet <urn:bgo:test:p2_1>,
##	        <urn:bgo:test:p2_2> .
##	
##	<urn:bgo:test:p1_1> bgo:abstract "This use **markdown**"^^bgo:MDString ;
##	    bgo:description "An account subset" ;
##	    bgo:hasAccount <urn:bgo:test:account_1> ;
##	    bgo:label "default",
##	        "subset 2" ;
##	    bgo:title "Default subset for p1",
##	        "Subset 1 title",
##	        "Subset 2 title" .
##	
##	<urn:bgo:test:p1_2> bgo:abstract "This use **markdown**"^^bgo:MDString ;
##	    bgo:description "An account subset" ;
##	    bgo:hasAccount <urn:bgo:test:account_1> ;
##	    bgo:label "default",
##	        "subset 2" ;
##	    bgo:title "Default subset for p1",
##	        "Subset 1 title",
##	        "Subset 2 title" .
##	
##	<urn:bgo:test:p2_1> bgo:abstract "This use **markdown**"^^bgo:MDString ;
##	    bgo:description "An account subset" ;
##	    bgo:hasAccount <urn:bgo:test:account_1>,
##	        <urn:bgo:test:account_2> ;
##	    bgo:label "subset 2" ;
##	    bgo:title "Subset 1 title",
##	        "Subset 2 title" .
##	
##	<urn:bgo:test:p2_2> bgo:abstract "This use **markdown**"^^bgo:MDString ;
##	    bgo:description "An account subset" ;
##	    bgo:hasAccount <urn:bgo:test:account_1>,
##	        <urn:bgo:test:account_2> ;
##	    bgo:label "subset 2" ;
##	    bgo:title "Subset 1 title",
##	        "Subset 2 title" .
##	
##	<urn:bgo:test:trend_formatter> bgo:format "%s%"^^bgo:Template ;
##	    bgo:maxValue 100 ;
##	    bgo:minValue -100 ;
##	    bgo:moreThanMaxFormat ">100%"^^bgo:Template ;
##	    bgo:precision 2 ;
##	    bgo:scaleFactor 100 .
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT{

    ?partition
    	bgo:withSortOrder ?withSortOrder ;
    	bgo:withSortCriteria ?withSortCriteria ;
    	bgo:withGroupFunction ?withGroupFunction ;    
        bgo:hasAccountSubSet ?accountSubSet ;
        bgo:hasDefaultAccountSubSet ?defaultAccountSubSet
    .
    
    ?withGroupFunction 
    	a ?groupFunctionType ;
    	bgo:hasTotalizer ?groupTotalizer .
    
    ?groupTotalizer
        bgo:filteredFormat ?filteredFormat ;
    	bgo:ratioFormatter ?ratioFormatter
    .
        
    ?subset 
	    bgo:icon ?subSetIcon ;
	    bgo:depiction ?subSetDepiction ;
	    bgo:label ?subSetLabel ;
	    bgo:title ?subSetTitle ;
	    bgo:description ?subSetDescription ;
	    bgo:abstract ?subSetAbstract ;
	    bgo:hasAccount ?subsetAccount 
	.
    
    
    ?formatter
    	bgo:format ?format ;
    	bgo:precision ?precision ;
		bgo:scaleFactor ?scaleFactor ;
		bgo:maxValue ?maxValue ;
		bgo:minValue ?minValue ; 
		bgo:moreThanMaxFormat ?moreThanMaxFormat ;
		bgo:lessThanMinFormat ?lessThanMinFormat 
	.

} 
WHERE {
	<?php if ($domainId) {?>
		?domain bgo:domainId "<?php echo $domainId;?>" ;
			bgo:hasAccount ?account .
	<?php } else { ?>
		FILTER NOT EXISTS { ?domain bgo:domainId [] } .
	<?php }?>

	?domain bgo:hasOverview/bgo:hasPartitions/bgo:hasPartition ?partition .

    OPTIONAL {  ?partition bgo:withSortOrder ?withSortOrder }
	OPTIONAL {  ?partition bgo:withSortCriteria ?withSortCriteria }
	OPTIONAL {  
		?partition bgo:withGroupFunction ?withGroupFunction 
		OPTIONAL { 
			?withGroupFunction  bgo:hasTotalizer ?groupTotalizer 
			OPTIONAL { 
				?groupTotalizer 
                    bgo:filteredFormat ?filteredFormat ;
                	bgo:ratioFormatter ?ratioFormatter
            }
		}		
		OPTIONAL { ?withGroupFunction  a ?groupFunctionType }
	}
    OPTIONAL {  ?partition bgo:hasAccountSubSet ?accountSubSet }
    OPTIONAL {  ?partition bgo:hasDefaultAccountSubSet ?defaultAccountSubSet }

	OPTIONAL {
		?partition bgo:hasAccountSubSet|bgo:hasDefaultAccountSubSet ?subset .
	    OPTIONAL { ?subset bgo:icon ?subSetIcon }
	    OPTIONAL { ?subset bgo:depiction ?subSetDepiction }
	    OPTIONAL { ?subset bgo:label ?subSetLabel }
	    OPTIONAL { ?subset bgo:title ?subSetTitle }
	    OPTIONAL { ?subset bgo:description ?subSetDescription }
	    OPTIONAL { ?subset bgo:abstract ?subSetAbstract }
	    OPTIONAL { 
	    	?subset bgo:hasAccount ?subsetAccount .
	    	<?php if ($domainId) echo "?domain bgo:hasAccount ?subsetAccount ."; ?>
	    }
	}

 
    OPTIONAL {
    	{ 
        	?partition bgo:withGroupFunction/bgo:hasTotalizer/bgo:ratioFormatter ?formatter.
    	}
    	UNION
    	{ 
        	?partition bgo:withGroupFunction/bgo:hasTotalizer ?formatter.
    	}
    	OPTIONAL { ?formatter bgo:format ?format  }
    	OPTIONAL { ?formatter bgo:scaleFactor ?scaleFactor }
    	OPTIONAL { ?formatter bgo:precision ?precision }
    	OPTIONAL { ?formatter bgo:maxValue ?maxValue }
    	OPTIONAL { ?formatter bgo:minValue ?minValue } 
    	OPTIONAL { ?formatter bgo:nanFormat ?nanFormat }
    	OPTIONAL { ?formatter bgo:moreThanMaxFormat ?moreThanMaxFormat }
    	OPTIONAL { ?formatter bgo:lessThanMinFormat ?lessThanMaxFormat } 
	}
}
