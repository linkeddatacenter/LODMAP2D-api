API for LODMAP2D application
============================

An endpoint compliant with the [LODMAP2D application](https://github.com/linkeddatacenter/LODMAP2D) to an RDF knowledg graph containing
a [BubbleGraph Ontology](https://github.com/linkeddatacenter/LODMAP-ontologies/tree/master/v1/bgo) 

It exposes the following resources:

resource                       | payload
------------------------------ | -------------------
/app[.ttl]                     | common LODMAP2D application layout data.
/account/*account_id*[.ttl]    | data for a *account_id* account
/partition/*partition_id*[.ttl]| data for *partition_id* partition
/credits[.ttl]                 | LODMAP2D application credits data 
/terms[.ttl]                   | LODMAP2D application terms & conditions data 
/accounts[.ttl]                | an index of all accounts, including just information used to render bubbles and tooltips



## Developers quick start

This project is based on the [µSilex framework](https://github.com/linkeddatacenter/uSilex), a superlight modern 
implementation of the old Silex project. It is fully based on PSR standards; without a single *if* nor a *loop*, this project provides:

- REStful APIs
- Cross-Origin Resource Sharing (CORS) support
- response compression
- full http cache magement
- fast routing with URI template

The platform is shipped with a [Docker](https://docker.com) setup that makes it easy to get a containerized  environment up and running. If you do not already have Docker on your computer, 
[it's the right time to install it](https://docs.docker.com/install/).

Create dependencies and cleanup the code:

```
docker run --rm -ti -v $PWD/.:/app composer composer install
docker run --rm -ti -v $PWD/.:/app composer composer cs-fix
```


Run a simple RDF  knowledge graph containing a bgo ontology:

```
docker network create test
docker run -d --name sdaas --network test -p 8080:8080 -v $PWD/.:/workspace linkeddatacenter/sdaas-ce
```

Go to http://localhost:8080/sdaas#update and load the file tests/system/data.trig (serialized in [RDF TRIG](https://www.w3.org/TR/trig)


Run the API server conected to the test network (it connetcs to http://sdaas:8080/sdaas/sparql endpoint as backend)

```
docker run -it --name api --rm --network test -p 8000:8000 -v $PWD/.:/app composer php -S "0.0.0.0:8000" index.php
```

Test it using an http client (e.g. Postman) or just with a browser (e.g. http://localhost:8000/app )

cleanup docker resources:

```
docker network rm test
docker rm -f sdaas

```

## Publish image to dockerhub


```
docker build -t linkeddatacenter/lodmap2d-api .
docker login --username=yourhubusername --email=youremail@company.com
docker tag linkeddatacenter/lodmap2d-api linkeddatacenter/lodmap2d-api:x.y.z
docker push linkeddatacenter/lodmap2d-api
```

## Using LODMAP2D API image

If you need to personalize the sparql queries needed to generate bgo data streams, create a directory containing
your new query (e.g. *myqueries*) than you can personalize the LODMAP2D docker image creating a Dockerfile like this:

```
FROM linkeddatacenter/lodmap2d-api

COPY ./myqueries/*.ru /app/src/Queries/
ENV LODMAP2D_BACKEND "here your sparql endpoint service url"
```


## License

MIT. Please see [License File](LICENSE) for more information.