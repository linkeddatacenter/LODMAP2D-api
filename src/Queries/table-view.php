#
# Returns accounts table view properties 
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT{
		
    ?tableView 
        bgo:amountFormatter ?amountFormatter ;
        bgo:referenceFormatter ?referenceFormatter ;
        bgo:trendFormatter ?trendFormatter ;
        bgo:hasTotalizer ?totalizer ;
        bgo:hasSearchPane ?searchPane ;
        bgo:headerTitle ?headerTitle ;
        bgo:headerAmount ?headerAmount ;
        bgo:headerTrend ?headerTrend ;
        bgo:headerDescription ?headerDescription ;
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

	?searchPane 
    	bgo:label ?searchPaneLabel
	.

} 
WHERE {
	<?php if ($domainId) {?>
		?domain bgo:domainId "<?php echo $domainId;?>" ;
			bgo:hasAccount ?account .
	<?php } else { ?>
		FILTER NOT EXISTS { ?domain bgo:domainId [] } .
	<?php }?>
	
	?domain  bgo:hasTableView  ?tableView  .
	
    OPTIONAL { ?tableView bgo:hasSearchPane ?searchPane }
    OPTIONAL { ?tableView bgo:headerTitle ?headerTitle }
    OPTIONAL { ?tableView bgo:headerAmount ?headerAmount }
    OPTIONAL { ?tableView bgo:headerTrend ?headerTrend }
    OPTIONAL { ?tableView bgo:hasSearchPane ?searchPane }
    OPTIONAL { ?tableView bgo:headerDescription ?headerDescription }
    OPTIONAL { ?tableView bgo:amountFormatter ?amountFormatter }
    OPTIONAL { ?tableView bgo:referenceFormatter ?referenceFormatter }
    OPTIONAL { ?tableView bgo:trendFormatter ?trendFormatter }

	OPTIONAL {
        ?tableView bgo:hasSearchPane ?searchPane .
    	OPTIONAL { ?searchPane  bgo:label ?searchPaneLabel }
	}
	
	OPTIONAL {     
        ?tableView bgo:hasTotalizer ?totalizer .       
        OPTIONAL { ?totalizer bgo:filteredFormat ?filteredFormat }
    	OPTIONAL { ?totalizer bgo:ratioFormatter ?ratioFormatter }
    }
    

    OPTIONAL {
    	{ ?tableView bgo:hasTotalizer|bgo:amountFormatter|bgo:referenceFormatter|bgo:trendFormatter ?formatter }
    	UNION
    	{ ?tableView bgo:hasTotalizer/bgo:ratioFormatter ?formatter }
    	OPTIONAL { ?formatter bgo:format ?format  }
    	OPTIONAL { ?formatter bgo:scaleFactor ?scaleFactor }
    	OPTIONAL { ?formatter bgo:precision ?precision }
    	OPTIONAL { ?formatter bgo:maxValue ?maxValue }
    	OPTIONAL { ?formatter bgo:minValue ?minValue } 
    	OPTIONAL { ?formatter bgo:nanFormat ?nanFormat }
    	OPTIONAL { ?formatter bgo:moreThanMaxFormat ?moreThanMaxFormat }
    	OPTIONAL { ?formatter bgo:lessThanMinFormat ?lessThanMinFormat } 
	}

}
