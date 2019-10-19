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
        bgo:hasTotalizer ?tableTotalizer ;
        bgo:hasSearchPane ?searchPane ;
        bgo:headerTitle ?headerTitle ;
        bgo:headerAmount ?headerAmount ;
        bgo:headerTrend ?headerTrend ;
        bgo:hasSearchPane ?searchPane ;
        bgo:headerDescription ?headerDescription 
    .
    
    
	# Icon, label and title metadata are extracted by app.php
	# Partition list extracted by app.php
    ?overview 
    	bgo:hasTrendColorScheme ?trendColorScheme ;
        bgo:hasTotalizer ?overviewTotalizer ;
        bgo:hasTagCloud ?tagCloud ;
        bgo:hasSearchPane ?searchPane ;
        bgo:hasTooltip ?tooltip 
    .
    
    ?partitions bgo:hasPartition ?partition .
    
    # Icon, label and title metadata are extracted by app.php
    ?partition
    	bgo:partitionId ?partitionId ;
    	bgo:withSortOrder ?withSortOrder ;
    	bgo:withSortCriteria ?withSortCriteria ;
    	bgo:withGroupFunction ?withGroupFunction ;    
        bgo:hasAccountSubSet ?accountSubSet ;
        bgo:hasDefaultAccountSubSet ?defaultAccountSubSet
    .
    
    ?withGroupFunction 
    	a ?groupFunctionType ;
    	bgo:hasTotalizer ?groupTotalizer .
    
    
    ?accountSubSet 
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
    
    
    ?totalizer
        bgo:filteredFormat ?filteredFormat ;
    	bgo:ratioFormatter ?ratioFormatter
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
	<?php if ($domainId) {?>
		?domain bgo:domainId "<?php echo $domainId;?>" ;
			bgo:hasAccount ?account .
	<?php } else { ?>
		FILTER NOT EXISTS { ?domain bgo:domainId [] } .
	<?php }?>
	
	?domain bgo:hasTableView|bgo:hasOverview ?accountsView .
	
	?domain 
		bgo:hasTableView  ?tableView ;
		bgo:hasOverview  ?overview .

	OPTIONAL {
        ?accountsView bgo:hasSearchPane ?searchPane .
    	OPTIONAL { ?searchPane  bgo:label ?searchPaneLabel }
	}
	
	OPTIONAL {     
        { ?accountsView bgo:hasTotalizer ?totalizer }
        UNION
        { ?overview bgo:hasPartitions/bgo:hasPartition/bgo:withGroupFunction/bgo:hasTotalizer ?totalizer }
        
        OPTIONAL { ?totalizer bgo:filteredFormat ?filteredFormat }
    	OPTIONAL { ?totalizer bgo:ratioFormatter ?ratioFormatter }
    }
    
    OPTIONAL { ?tableView bgo:hasTotalizer ?tableTotalizer }
    OPTIONAL { ?tableView bgo:hasSearchPane ?searchPane }
    OPTIONAL { ?tableView bgo:headerTitle ?headerTitle }
    OPTIONAL { ?tableView bgo:headerAmount ?headerAmount }
    OPTIONAL { ?tableView bgo:headerTrend ?headerTrend }
    OPTIONAL { ?tableView bgo:hasSearchPane ?searchPane }
    OPTIONAL { ?tableView bgo:headerDescription ?headerDescription }


	OPTIONAL { ?overview bgo:hasTrendColorScheme ?trendColorScheme }
    OPTIONAL { ?overview bgo:hasTotalizer ?overviewTotalizer }
    OPTIONAL { ?overview bgo:hasTagCloud ?tagCloud }
    OPTIONAL { ?overview bgo:hasSearchPane ?searchPane }
    OPTIONAL { ?overview bgo:hasTooltip ?tooltip }
    
	OPTIONAL {
    	?overview bgo:hasPartitions/bgo:hasPartition ?partition .
    	?partition bgo:partitionId ?partitionId .
        OPTIONAL {  ?partition bgo:withSortOrder ?withSortOrder }
    	OPTIONAL {  ?partition bgo:withSortCriteria ?withSortCriteria }
    	OPTIONAL {  
    		?partition bgo:withGroupFunction ?withGroupFunction 
    		OPTIONAL { ?withGroupFunction  bgo:hasTotalizer ?groupTotalizer }		
    		OPTIONAL { ?withGroupFunction  a ?groupFunctionType }
    	}
        OPTIONAL {  ?partition bgo:hasAccountSubSet ?accountSubSet }
        OPTIONAL {  ?partition bgo:hasDefaultAccountSubSet ?defaultAccountSubSet }
    }
    
	OPTIONAL {
		?overview bgo:hasPartitions/bgo:hasPartition ?partition .
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
    	?overview bgo:hasTooltip ?tooltip .
	    OPTIONAL { ?tooltip bgo:amountFormatter ?amountFormatter }
	    OPTIONAL { ?tooltip bgo:referenceFormatter ?amountFormatter }
	    OPTIONAL { ?tooltip bgo:trendFormatter ?trendFormatter }
    }

	
	OPTIONAL {
    	?overview bgo:hasTrendColorScheme ?trendColorScheme .	
        OPTIONAL { ?trendColorScheme bgo:title ?trendColorSchemeTitle }
        OPTIONAL { ?trendColorScheme  bgo:noTrendColor ?noTrendColor }
        OPTIONAL { ?trendColorScheme bgo:rateTreshold ?rateTreshold }
    }
    
    OPTIONAL {
    	?overview bgo:hasTrendColorScheme/bgo:rateTreshold ?rateTreshold .
    	OPTIONAL { ?rateTreshold bgo:rate ?rate }
    	OPTIONAL { ?rateTreshold bgo:colorId ?colorId }
    }
	    
    
	OPTIONAL {
		?overview bgo:hasTagCloud ?tagCloud .
		?tagCloud bgo:hasTag ?tag .
		?tag 
			bgo:label ?tagLabel ;
			bgo:tagWeight ?tagWeight 
	}

	
	{
      SELECT ?account ?accountId ?amount ?title  ?referenceAmount ?description ?depiction WHERE {
        ?account
            bgo:accountId ?accountId ;
            bgo:amount ?amount .

        OPTIONAL { ?account bgo:title ?title  }
        OPTIONAL { ?account bgo:referenceAmount ?referenceAmount }
        OPTIONAL { ?account bgo:description ?description }
        OPTIONAL { ?account bgo:depiction ?depiction	}
      }
    }
	

    OPTIONAL {
    	{
    		?overview bgo:hasTooltip ?tooltip . 
    		?tooltip bgo:amountFormatter|bgo:referenceFormatter|bgo:trendFormatter ?formatter 
    	}
    	UNION
    	{ 
    		?tableView bgo:amountFormatter|bgo:referenceFormatter|bgo:trendFormatter ?formatter 
    	}
    	UNION
    	{ 
        	?accountsView bgo:hasTotalizer/bgo:ratioFormatter ?formatter.
    	}
    	UNION
    	{ 
        	?partition bgo:withGroupFunction/bgo:hasTotalizer/bgo:ratioFormatter ?formatter.
    	}
    	UNION
    	{ 
        	?accountsView bgo:hasTotalizer ?formatter.
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
    	OPTIONAL { ?formatter bgo:lessThanMinFormat ?moreThanMaxFormat } 
	}

}
