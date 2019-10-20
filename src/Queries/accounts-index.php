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
    	    bgo:depiction ?depiction .
    } 
    WHERE {
    	<?php if ($domainId) {?>
    		?domain bgo:domainId "<?php echo $domainId;?>" ;
    			bgo:hasAccount ?account .
    	<?php }?>
    
        ?account
            bgo:accountId ?accountId ;
            bgo:amount ?amount .
    
        OPTIONAL { ?account bgo:title ?title  }
        OPTIONAL { ?account bgo:referenceAmount ?referenceAmount }
        OPTIONAL { ?account bgo:description ?description }
        OPTIONAL { ?account bgo:depiction ?depiction	}
    
    }
