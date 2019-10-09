#
# Returns data related to the BGO application framework with a set of reusable components
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT { 
	?domain # a bgo:Domain ;
        bgo:description ?description ;
        bgo:abstract ?abstract ;
        bgo:hasSocialSharing  ?socialSharing ;
        bgo:hasOverview  ?overview ;
        bgo:hasTableview  ?tableView ;
        bgo:hasCredits  ?credits ;
        bgo:hasTerms  ?terms ;
        bgo:hasCopyrigth ?copyright ;       
        ?hasMenu ?menu 
    .
     
    # Menu construction
    ?domain ?hasMenu ?menu .
    ?menu bgo:withCustomMenuItem ?menuItem .
    
    ?menuItem 
    	bgo:link ?menuItemLink 
    .
    
    ?overview 
    	bgo:hasPartitions ?partitions . 
    	
    ?partitions 
    	bgo:hasPartition ?partition
    . 
    
    ?bgoThings 
        bgo:icon ?icon ;
    	bgo:label ?label ;
    	bgo:title ?title
    . 
  
} 
WHERE {
	<?php if ($domainId) {?>
		?domain bgo:domainId "<?php echo $domainId;?>" .
	<?php } else { ?>
		FILTER NOT EXISTS { ?domain bgo:domainId [] } .
	<?php }?>
	
	?domain 
		bgo:hasOverview ?overview ;
		bgo:hasTableView ?tableView ;
        bgo:hasCredits  ?credits ;
        bgo:hasTerms  ?terms 
    .
    
    {
    	{ ?domain bgo:hasOverview|bgo:hasTableView|bgo:hasCredits|bgo:hasTerms ?bgoThings }
    	UNION
    	{ ?overview bgo:hasPartitions ?bgoThings }
    	UNION
    	{ ?overview bgo:hasPartitions/bgo:hasPartition ?bgoThings }
    	UNION
    	{ 
    		?domain bgo:hasNavigationMenu|bgo:hasOptionMenu|bgo:hasFooterMenu ?menu .
    		?menu  bgo:withCustomMenuItem ?bgoThings
    	}
    
        OPTIONAL { ?bgoThings bgo:icon ?icon }
     	OPTIONAL { ?bgoThings bgo:label ?label }
     	OPTIONAL { ?bgoThings bgo:title ?title }
    
    }
    
	
	OPTIONAL { ?domain bgo:description ?description }
	OPTIONAL { ?domain bgo:abstract ?abstract }
	OPTIONAL { ?domain bgo:hasSocialSharing  ?socialSharing }
	OPTIONAL { ?domain bgo:hasCopyrigth ?copyright }

	
	# Menu construction
	# See https://stackoverflow.com/questions/44221975/how-to-write-a-sparql-construct-query-that-returns-an-rdf-list
	OPTIONAL { 		
    	VALUES ?hasMenu {
    		bgo:hasNavigationMenu
    		bgo:hasOptionMenu
    		bgo:hasFooterMenu
    	}
    	
		?domain ?hasMenu ?menu .
		?menu bgo:withCustomMenuItem ?menuItem .
		
     	OPTIONAL { ?menuItem bgo:link ?menuItemLink }
     }
     
   	OPTIONAL { ?overview bgo:hasPartitions ?partitions } 

    
    
    # Minimal partitions  data for menu building    
    OPTIONAL { 
    	?overview bgo:hasPartitions ?partitions .
       	OPTIONAL { 
       		?partitions bgo:hasPartition ?partition 
       	} 
    }  
    

}