![ldc](http://linkeddata.center/resources/v4/logo/Logo-colori-trasp_oriz-640x220.png)

API for LODMAP2D application
============================

LODMAP2D-api is a set of linked data resources to feed a [LODMAP2D application](https://github.com/linkeddatacenter/LODMAP2D).
The code queries a [Bubble Graph Ontology](http://linkeddata.center/lodmap-bgo/v1) 
contained into a knowledge graph through a SPARQL service endpoint.

LODMAP2D-api exposes the following resources:

resource                       | payload
------------------------------ | -------------------
/app[.ttl]                     | common LODMAP2D application layout data.
/accounts[.ttl]                | an index of all accounts, including just information used to render the partitions view
/partitions[.ttl]              | LODMAP2Ddata for all partition views
/account/*account_id*[.ttl]    | LODMAP2D data for a *account_id* account. 
/credits[.ttl]                 | LODMAP2D application credits data 
/terms[.ttl]                   | LODMAP2D application terms & conditions data 

If no resources match criteria defined in correspondent src/Queries, an empty RDF graph is returned (i.e. no 404 error)
Only RDF turtle serialization is supported.

## Developers quick start

The project is shipped with a [Docker](https://docker.com) setup that makes it easy to get a containerized  environment up and running. If you do not already have Docker on your computer, 
[it's the right time to install it](https://docs.docker.com/install/).

Start all required services running `docker-compose up -d` and wait some seconds to let the knowledge graph platform to warm-up and loading test data...

Docker compose will run the Smart Data as a Service Platform at http://localhost:8080/sdaas
and will run a simple api endpoint at http://localhost:8000/

Cleanup docker resources with  `docker-compose down`

Read the [CONTRIBUTING file](CONTRIBUTING.md) for more info.


## License

MIT. Please see [License File](LICENSE) for more information.