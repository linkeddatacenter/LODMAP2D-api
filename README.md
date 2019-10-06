![ldc](http://linkeddata.center/resources/v4/logo/Logo-colori-trasp_oriz-640x220.png)

API for LODMAP2D application
============================

LODMAP2D-api is a set of linked data resources to feed a [LODMAP2D application](https://github.com/linkeddatacenter/LODMAP2D).
The code queries a [Bubble Graph Ontology](http://linkeddata.center/lodmap-bgo/v1) 
contained into a knowledge graph through a SPARQL service endpoint.

LODMAP2D-api exposes the following resources:

resource                               | payload
-------------------------------------- | -------------------
/app[.*extension*]                     | common LODMAP2D application layout data.
/partitions[.*extension*]              | LODMAP2D data for all partition views with account index
/accounts[.*extension*]                | LODMAP2D data for AccountView and related perspective
/account/*account_id*[.*extension*]    | LODMAP2D data for a *account_id* account. 
/credits[.*extension*]                 | LODMAP2D application credits data 
/terms[.*extension*]                   | LODMAP2D application terms & conditions data 


It is also possible to query a subdomain prefixing the resources with the sub domain id, i.e:

- /*sub_domain_id*/app[.ttl] 
- /*sub_domain_id*/partitions[.ttl] 
- /*sub_domain_id*/accounts[.ttl]
- /*sub_domain_id*/account/*account_id*[.ttl]
- /*sub_domain_id*/credits[.ttl] 
- /*sub_domain_id*/terms[.ttl]

If no resources match criteria defined in correspondent src/Queries, an empty RDF graph is returned (i.e. no 404 error)

LODMAP2D-api supports HTTP content negotiation and the extensions: ttl, turtle, n3, txt, nt, ntriples, rdf, xml, rdfs, owl, jsonld, json . 
If none specified *turtle* is used.

Only UTF8 charset supported.

## Try it with docker

This process will run and populate a knowledge graph with the  BGO test data and will run an instance of the latest LODMAP2D-api image.

Docker compose required.

```
cd tests/system
docker-compose up -d
```

let the system warm-up for about 30 seconds and than try APIs from Postman of from your browser:

- http://localhost:8000/app.ttl 
- http://localhost:8000/partitions.ttl
- http://localhost:8000/accounts.ttl
- http://localhost:8000/account/account_1.ttl
- http://localhost:8000/credits.ttl
- http://localhost:8000/terms.ttl
- http://localhost:8000/test/app.ttl
- http://localhost:8000/test/app.ntriples
- http://localhost:8000/test/app.json
- ....


**Using LODMAP2D-api with LODMAP2D:**

LODMAP2D-api supports out-of-the-box the default LODMAP2D dereferencing rules .
Just let the environment variable *VUE_APP_LODMAP2D_DATA* pointing to your api server. Try it now:

```
docker run -d --name app -e VUE_APP_LODMAP2D_DATA=http://localhost:8000/ -p 80:80 linkeddatacenter/lodmap2d
```

Point your browser to http://localhost/ and enjoy.

cleanup docker resources:

```
docker rm -f app
docker-compose down
```
cd ../LOD

## Developers quick start

The project is shipped with a [Docker](https://docker.com) setup that makes it easy to get a containerized  environment up and running. If you do not already have Docker on your computer, 
[it's the right time to install it](https://docs.docker.com/install/).

Start all required services running `docker-compose up -d` and wait some seconds to let the knowledge graph platform to warm-up and loading test data...

Docker compose will run the Smart Data as a Service Platform at http://localhost:8081/sdaas
and will run a simple test endpoint at http://localhost:8000/

Cleanup docker resources with  `docker-compose down`

Read the [CONTRIBUTING file](CONTRIBUTING.md) for more info.


## License

MIT. Please see [License File](LICENSE) for more information.