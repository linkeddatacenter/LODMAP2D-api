![ldc](http://linkeddata.center/resources/v4/logo/Logo-colori-trasp_oriz-640x220.png)

API for LODMAP2D application
============================

LODMAP2D-api is a set of linked data resources to feed a [LODMAP2D application](https://github.com/linkeddatacenter/LODMAP2D).
The code queries a [Bubble Graph Ontology](http://linkeddata.center/lodmap-bgo/v1) 
contained into a knowledge graph through a SPARQL service endpoint.

LODMAP2D-api exposes the following resources:

resource                                             | payload
---------------------------------------------------- | -------------------
/[*domain_id*/]app[.*extension*]                     | common LODMAP2D application layout data.
/[*domain_id*/]table-view[.*extension*]              | LODMAP2D tableview metadata
/[*domain_id*/]overview[.*extension*]                | LODMAP2D data for overview
/[*domain_id*/]partitions[.*extension*]              | LODMAP2D data for all partition views 
/[*domain_id*/]account-view[.*extension*]            | LODMAP2D data for AccountView and related perspective
/[*domain_id*/]accounts-index[.*extension*]          | LODMAP2D data for all account
/[*domain_id*/]account/*account_id*[.*extension*]    | LODMAP2D data for a *account_id* account. 
/[*domain_id*/]credits[.*extension*]                 | LODMAP2D application credits data 
/[*domain_id*/]terms[.*extension*]                   | LODMAP2D application terms & conditions data 
/bgo[.extension]					                 | returns all BGO data in the knowledge graph

These APIs are designed to support this LODMAP2d config rules:

```
[
    { "regexp": ".*", "targets": [LODMAP2D_DATA + "app.ttl"] },
    { "regexp": ".*/(table|partition|account).*$", "targets": [LODMAP2D_DATA + "accounts-index.ttl"] },
    { "regexp": ".*/table$", "targets": [LODMAP2D_DATA + "table-view.ttl"], "isLast": true },
    { "regexp": ".*/partition/(\\w+)$", "targets": [LODMAP2D_DATA + "overview.ttl", LODMAP2D_DATA + "partitions.ttl"], "isLast": true },
    { "regexp": ".*/account/(\\w+)$", "targets": [LODMAP2D_DATA + "account-view.ttl", LODMAP2D_DATA + "account/$1.ttl"], "isLast": true },
    { "regexp": ".*/(credits|terms)$", "targets": [LODMAP2D_DATA + "$1.ttl"], "isLast": true }
 ]
```

where LODMAP2D_DATA contains the URL of the api server.


**Experimental feature:**

It is also possible to query a subdomain prefixing the resources with the sub domain id, e.g.

- /*my_domain*/app

If no resources found, an empty RDF graph is returned.

LODMAP2D-api supports HTTP content negotiation and the extensions: ttl, turtle, n3, txt, nt, ntriples, rdf, xml, rdfs, owl, jsonld, json . 
If none specified, *turtle* is used.

Only UTF8 charset supported.

## Quickstart with docker

The project is shipped with a [Docker](https://docker.com) setup that makes it easy to get a containerized  environment up and running. If you do not already have Docker on your computer, 
[it's the right time to install it](https://docs.docker.com/install/).


```
docker-compose up -d
```

This process will run and populate a knowledge graph with the BGO test data and will run an instance of the latest LODMAP2D-api image:

- the knowledge graph workbench  will be available at http://localhost:8081/sdaas
- the test api endpoint will be available at http://localhost:8000/

let the system warm-up for about 30 seconds and try APIs from Postman of from your browser:

- http://localhost:8000/app.ttl 
- http://localhost:8000/partitions.ttl
- http://localhost:8000/accounts.ttl
- http://localhost:8000/account/account_1.ttl
- http://localhost:8000/credits.ttl
- http://localhost:8000/terms.ttl
- http://localhost:8000/test/app.ttl
- http://localhost:8000/test/app.ntriples
- http://localhost:8000/test/app.json
- http://localhost:8000/bgo.ttl
- ....


**Using LODMAP2D-api with LODMAP2D:**

LODMAP2D-api supports out-of-the-box the default LODMAP2D docker image .
Just let the environment variable *LODMAP2D_DATA* pointing to your api server. Try with:

```
docker run -d --name app -e LODMAP2D_DATA=http://localhost:8000/ -p 8080:80 linkeddatacenter/lodmap2d
```

Point your browser to http://localhost/ and enjoy.

cleanup docker resources:

```
docker rm -f app
docker-compose down
```

**Using the pre-bulid docker image**

LODMAP2D-api is available in DockerHub repository

The linkeddatacenter/lodmap2d-api supports following environment variables:

- **LODMAP2D_BACKEND** refers to the SPARQL service endpoint URL (defaults to http://sdaas:8080/sdaas/sparql ) of a knowledge graph containing a BGO
- **LODMAP2D_CORS_ALLOWEDORIGINS** that defaults to '*'
- **LODMAP2D_CACHE_EXPIRE** resource expiration time. Defaults to '+1 hour', accepts  any valid [DateTime string](https://www.php.net/manual/en/datetime.formats.php)

For example: `docker --rm run -e LODMAP2D_BACKEND=https://data.budget.g0v.it/sparql -p 8000:80 linkeddatacenter/lodmap2d-api`

 
## Developers

See [CONTRIBUTING file](CONTRIBUTING.md) for more info.

## License

MIT. Please see [License File](LICENSE) for more information.