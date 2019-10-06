#
# Returns data related to an AccountView 
# To be consistent with BGO, data about somain are also required (see. app.php )
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT { 
	?domain bgo:hasAccountView ?accountView .
    
    ?accountView 
    	bgo:amountFormatter ?amountFormatter ;
    	bgo:referenceFormatter ?referenceFormatter ;
    	bgo:trendFormatter ?trendFormatter ;
    	bgo:hasHistoricalPerspective ?historicalPerspective ;
    	bgo:hasBreakdownPerspective ?breakdownPerspective
    .
    
    ?historicalPerspective
    	bgo:title ?titleHistoricalPerspective ;
    	bgo:amountFormatter   ?amountFormatterHistoricalPerspective 
    .
    
    ?breakdownPerspective
    	bgo:title ?titleBreackdownPerspective ;
    	bgo:amountFormatter   ?amountFormatterBreackdownPerspective;
    	bgo:hasTotalizer   ?totalizerBreackdownPerspective  
    .
	
	?perspective
    	bgo:title ?titlePerspective ;
    	bgo:amountFormatter   ?amountFormatterPerspectve 
    .
    
    
	?formatter
    	bgo:format ?format  ;
    	bgo:scaleFactor ?scaleFactor ;
    	bgo:precision ?precision ;
    	bgo:maxValue ?maxValue;
    	bgo:minValue ?minValue ; 
    	bgo:nanFormat ?nanFormat ;
    	bgo:moreThanMaxFormat ?moreThanMaxFormat ;
    	bgo:lessThanMinFormat ?moreThanMaxFormat
    . 	
} 
WHERE {
	<?php if ($domainId) {?>
		?domain bgo:domainId "<?php echo $domainId;?>" .
	<?php } else { ?>
		FILTER NOT EXISTS { ?domain bgo:domainId [] } .
	<?php }?>
	?domain bgo:hasAccountView  ?accountView .
	
	OPTIONAL { ?accountView bgo:amountFormatter ?amountFormatter }
    OPTIONAL { ?accountView bgo:referenceFormatter ?referenceFormatter }
    OPTIONAL { ?accountView bgo:trendFormatter ?trendFormatter }
    OPTIONAL { ?accountView bgo:hasHistoricalPerspective ?historicalPerspective }
    OPTIONAL { ?accountView bgo:hasBreakdownPerspective ?breakdownPerspective }

	OPTIONAL {
		?accountView bgo:hasHistoricalPerspective|bgo:hasBreakdownPerspective ?perspective .
		OPTIONAL { ?perspective  bgo:title ?titlePerspective }
		OPTIONAL { ?perspective  bgo:amountFormatter  ?amountFormatterPerspectve  }
	}
	
	OPTIONAL {
		?accountView bgo:hasBreakdownPerspective/bgo:hasTotalizerhas ?totalizerBreackdownPerspective 
	}
	
	OPTIONAL {
		{ ?accountView bgo:amountFormatter|bgo:referenceFormatter|bgo:trendFormatter ?formatter }
		UNION
		{ 
			?accountView bgo:hasHistoricalPerspective|bgo:hasBreakdownPerspective ?perspective .
			?perspective  bgo:amountFormatter ?formatter
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