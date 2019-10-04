#
# Returns a minimal set of properties for all Account in BGO
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT{
	?account 
		bgo:accountId ?accountId ;
	    bgo:amount ?amount ;
	    bgo:title ?title  ;
	    bgo:referenceAmount ?referenceAmount ;
	    bgo:description ?description ;
	    bgo:depiction ?depiction	
	.
		
    ?tableView 
        bgo:hasTotalizer ?totalizer ;
        bgo:hasSearchPane ?searchPane ;
        bgo:headerTitle ?headerTitle ;
        bgo:headerAmount ?headerAmount ;
        bgo:headerTrend ?headerTrend ;
        bgo:hasSearchPane ?searchPane ;
        bgo:headerDescription ?headerDescription 
    .
    
    ?totalizer
        bgo:filteredFormat ?filteredFormat ;
    	bgo:ratioFormatter ?ratioFormatter
    .
    
	# Icon, label and title metadata are extracted by app.php
	# Partition list extracted by app.php
    ?overview 
    	bgo:hasTrendColorScheme ?trendColorScheme ;
        bgo:hasTotalizer ?totalizer ;
        bgo:hasTagCloud ?tagCloud ;
        bgo:hasSearchPane ?searchPane ;
        bgo:hasTooltip ?tooltip 
    .
    
    # Icon, label and title metadata are extracted by app.php
    ?partition
    	bgo:withSortOrder ?withSortOrder ;
    	bgo:withSortCriteria ?withSortCriteria;
    	bgo:withGroupFunction ?withGroupFunction ;    
        bgo:hasAccountSubSet ?accountSubset ;
        bgo:hasDefaultAccountSubSet ?defaultAccountSubSet
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
    
    ?tooltip
	    bgo:amountFormatter ?amountFormatter;
	    bgo:referenceFormatter ?amountFormatter;
	    bgo:trendFormatter ?trendFormatter
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
	
	?trendColorScheme 
        bgo:title ?trendColorSchemeTitle ;
        bgo:noTrendColor ?noTrendColor ;
        bgo:rateTreshold ?rateTreshold
    .
    
    ?rateTreshold
    	bgo:rate ?rate ;
    	bgo:colorId ?colorId
    . 
    
	?searchPane 
    	bgo:label ?searchPaneLabel
	.

	?tagCloud bgo:hasTag ?tag .
	?tag
		bgo:label ?tagLabel ;
		bgo:tagWeight ?tagWeight 
	.

} 
WHERE {
	<?php if ($domainId) echo "?domain bgo:domainId \"$domainId\" ; bgo:hasAccount ?account .";?>
	
	?account
		bgo:accountId ?accountId ;
	    bgo:amount ?amount .
	    
	OPTIONAL { ?account bgo:title ?title  }
	OPTIONAL { ?account bgo:referenceAmount ?referenceAmount }
	OPTIONAL { ?account bgo:description ?description }
	OPTIONAL { ?account bgo:depiction ?depiction	}
	
	OPTIONAL {
    	?domain bgo:hasTableView  ?tableView .
        OPTIONAL { ?tableView bgo:hasTotalizer ?totalizer }
        OPTIONAL { ?tableView bgo:hasSearchPane ?searchPane }
        OPTIONAL { ?tableView bgo:headerTitle ?headerTitle }
        OPTIONAL { ?tableView bgo:headerAmount ?headerAmount }
        OPTIONAL { ?tableView bgo:headerTrend ?headerTrend }
        OPTIONAL { ?tableView bgo:hasSearchPane ?searchPane }
        OPTIONAL { ?tableView bgo:headerDescription ?headerDescription }
    }
    
    OPTIONAL {     
        ?domain bgo:hasTableView|bgo:hasOverview  ?accountsView .
        ?accountsView bgo:hasTotalizer ?totalizer
        OPTIONAL { ?totalizer bgo:filteredFormat ?filteredFormat }
    	OPTIONAL { ?totalizer bgo:ratioFormatter ?ratioFormatter }
    }
    

	OPTIONAL {
    	?domain bgo:hasOverview  ?overview .
    	OPTIONAL { ?overview bgo:hasTrendColorScheme ?trendColorScheme }
        OPTIONAL { ?overview bgo:hasTotalizer ?totalizer }
        OPTIONAL { ?overview bgo:hasTagCloud ?tagCloud }
        OPTIONAL { ?overview bgo:hasSearchPane ?searchPane }
        OPTIONAL { ?overview bgo:hasTooltip ?tooltip }
    }
    
	OPTIONAL {
    	?domain bgo:hasOverview/bgo:hasPartitions/bgo:hasPartitionList/rdf:rest*/rdf:first ?partition .
        OPTIONAL {  ?partition bgo:withSortOrder ?withSortOrder }
    	OPTIONAL {  ?partition bgo:withSortCriteria ?withSortCriteria }
    	OPTIONAL {  ?partition bgo:withGroupFunction ?withGroupFunction }
        OPTIONAL {  ?partition bgo:hasAccountSubSet ?accountSubset }
        OPTIONAL {  ?partition bgo:hasDefaultAccountSubSet ?defaultAccountSubSet }
    }
    
	OPTIONAL {
		?domain bgo:hasOverview/bgo:hasPartitions/bgo:hasPartitionList/rdf:rest*/rdf:first ?partition .
		?partition bgo:hasAccountSubSet|bgo:hasDefaultAccountSubSet ?subset .
	    OPTIONAL { ?subset bgo:icon ?subSetIcon }
	    OPTIONAL { ?subset bgo:depiction ?subSetDepiction }
	    OPTIONAL { ?subset bgo:label ?subSetLabel }
	    OPTIONAL { ?subset bgo:title ?subSetTitle }
	    OPTIONAL { ?subset bgo:description ?subSetDescription }
	    OPTIONAL { ?subset bgo:abstract ?subSetAbstract }
	    OPTIONAL { 
	    	?subset bgo:hasAccount ?subsetAccount .
	    	<?php if ($domainId) echo "?domain bgo:hasAccount ?subsetAccount .";?>
	    }
	}

    
    OPTIONAL {
    	?domain bgo:hasOverview/bgo:hasTooltip ?tooltip .
	    OPTIONAL { ?tooltip bgo:amountFormatter ?amountFormatter }
	    OPTIONAL { ?tooltip bgo:referenceFormatter ?amountFormatter }
	    OPTIONAL { ?tooltip bgo:trendFormatter ?trendFormatter }
    }
    
    OPTIONAL {
		{
			?domain bgo:hasOverview/bgo:hasTooltip ?tooltip . 
			?tooltip bgo:amountFormatter|bgo:referenceFormatter|bgo:trendFormatter ?formatter 
		}
		UNION
		{ 
			?domain bgo:hasTableView|bgo:hasOverview  ?accountsView .
        	?accountsView bgo:hasTotalizer/bgo:ratioFormatter ?formatter.
		}
    	OPTIONAL { ?formatter bgo:format ?format  }
    	OPTIONAL { ?formatter bgo:scaleFactor ?scaleFactor }
    	OPTIONAL { ?formatter bgo:precision ?precision }
    	OPTIONAL { ?formatter bgo:maxValue ?maxValue }
    	OPTIONAL { ?formatter bgo:minValue ?minValue } 
    	OPTIONAL { ?formatter bgo:nanFormat ?nanFormat }
    	OPTIONAL { ?formatter bgo:moreThanMaxFormat ?moreThanMaxFormat }
    	OPTIONAL { ?formatter bgo:lessThanMinFormat ?moreThanMaxFormat } 
	}  
	
	OPTIONAL {
    	?domain bgo:hasOverview/bgo:hasTrendColorScheme ?trendColorScheme .	
        OPTIONAL { ?trendColorScheme bgo:title ?trendColorSchemeTitle }
        OPTIONAL { ?trendColorScheme  bgo:noTrendColor ?noTrendColor }
        OPTIONAL { ?trendColorScheme bgo:rateTreshold ?rateTreshold }
    }
    
    OPTIONAL {
    	?domain bgo:hasOverview/bgo:hasTrendColorScheme/bgo:rateTreshold ?rateTreshold .
    	OPTIONAL { ?rateTreshold bgo:rate ?rate }
    	OPTIONAL { ?rateTreshold bgo:colorId ?colorId }
    }
	
	OPTIONAL {
		?domain bgo:hasTableView|bgo:hasOverview  ?accountsView .
        ?accountsView bgo:hasSearchPane ?searchPane .
    	OPTIONAL { ?searchPane  bgo:label ?searchPaneLabel }
	}
	
	OPTIONAL {
		?domain bgo:hasOverview/bgo:hasTagCloud ?tagCloud .
		?tagCloud bgo:hasTag ?tag .
		?tag 
			bgo:label ?tagLabel ;
			bgo:tagWeight ?tagWeight 
	}

}
