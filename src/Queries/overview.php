#
# Returns BGO portion to render the overview page ( requires data provided by app.php)
#
# It should generate something like:
##	<urn:bgo:test:overview> bgo:hasSearchPane <urn:bgo:test:search_pane> ;
##	    bgo:hasTagCloud <urn:bgo:test:tag_cloud> ;
##	    bgo:hasTooltip [ bgo:amountFormatter <urn:bgo:test:amount_formatter> ;
##	            bgo:trendFormatter <urn:bgo:test:trend_formatter> ] ;
##	    bgo:hasTotalizer <urn:bgo:test:filtered_total_formatter> ;
##	    bgo:hasTrendColorScheme <urn:bgo:test:trend_color_scheme> .
##	
##	<urn:bgo:test:amount_formatter> bgo:format "€ %s"^^bgo:Template ;
##	    bgo:precision 2 .
##	
##	<urn:bgo:test:filtered_total_formatter> bgo:filteredFormat "In evidenza: € %s"^^bgo:Template ;
##	    bgo:format "Totale:  € %s" ;
##	    bgo:precision 2 ;
##	    bgo:ratioFormatter [ bgo:format " ( %s% del tot.)"^^bgo:Template ;
##	            bgo:maxValue 100 ;
##	            bgo:minValue 1 ;
##	            bgo:moreThanMaxFormat "" ;
##	            bgo:precision 2 ;
##	            bgo:scaleFactor 100 ] .
##	
##	<urn:bgo:test:search_pane> bgo:label "Cerca" .
##	
##	<urn:bgo:test:tag_cloud> bgo:hasTag 
##		[ bgo:label "Tewsst3" ; bgo:tagWeight 1.0 ],
##			...
##	
##	<urn:bgo:test:trend_color_scheme> bgo:noTrendColor "#cdcdcd"^^bgo:RGB ;
##	    bgo:rateTreshold 
##			[ bgo:colorId "#fdae61"^^bgo:RGB ; bgo:rate -0.1 ],
##			...
##	    bgo:title "Variazione percentuale rispetto al valore di acquisto" .
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
	# Icon, label and title metadata are extracted by app.php
	# Partition list extracted by app.php
    ?overview 
    	bgo:hasTrendColorScheme ?trendColorScheme ;
        bgo:hasTotalizer ?overviewTotalizer ;
        bgo:hasTagCloud ?tagCloud ;
        bgo:hasSearchPane ?searchPane ;
        bgo:hasTooltip ?tooltip 
    .
    
    ?tooltip
	    bgo:amountFormatter ?amountFormatter;
	    bgo:referenceFormatter ?referenceFormatter;
	    bgo:trendFormatter ?trendFormatter
    .
    
    ?overviewTotalizer
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

	?domain bgo:hasOverview ?overview .
	
	OPTIONAL { ?overview bgo:hasTrendColorScheme ?trendColorScheme }
    OPTIONAL { ?overview bgo:hasTotalizer ?overviewTotalizer }
    OPTIONAL { ?overview bgo:hasTagCloud ?tagCloud }
    OPTIONAL { ?overview bgo:hasSearchPane ?searchPane }
    OPTIONAL { ?overview bgo:hasTooltip ?tooltip }

	OPTIONAL {
        ?overview bgo:hasSearchPane ?searchPane .
    	OPTIONAL { ?searchPane  bgo:label ?searchPaneLabel }
	}
	
	OPTIONAL {     
        ?overview bgo:hasTotalizer ?overviewTotalizer
        OPTIONAL { ?overviewTotalizer bgo:filteredFormat ?filteredFormat }
    	OPTIONAL { ?overviewTotalizer bgo:ratioFormatter ?ratioFormatter }
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

    OPTIONAL {
    	{
    		?overview bgo:hasTooltip ?tooltip . 
    		?tooltip bgo:amountFormatter|bgo:referenceFormatter|bgo:trendFormatter ?formatter 
    	}
    	UNION
    	{ 
        	?overview bgo:hasTotalizer ?formatter.
    	}
    	UNION
    	{ 
        	?overview bgo:hasTotalizer/bgo:ratioFormatter ?formatter.
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
